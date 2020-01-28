<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//con4_empenhopassivo.RPC.php
require_once "libs/db_stdlib.php";
require_once "std/db_stdClass.php";
require_once "libs/db_utils.php";
require_once "libs/db_app.utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/JSON.php";
require_once "dbforms/db_funcoes.php";
require_once "model/contabilidade/lancamento/LancamentoAuxiliarInscricao.model.php";
require_once "interfaces/ILancamentoAuxiliar.interface.php";
require_once "interfaces/IRegraLancamentoContabil.interface.php";
require_once "model/empenho/AutorizacaoEmpenho.model.php";
require_once "classes/empenho.php";
require_once "classes/db_empnotaele_classe.php";
require_once "classes/db_pagordemnota_classe.php";
require_once "classes/db_pagordem_classe.php";
require_once "classes/db_pagordemele_classe.php";
require_once "model/contabilidade/planoconta/ContaPlano.model.php";


db_app::import("CgmFactory");
db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.lancamento.*");
db_app::import('contabilidade.contacorrente.*');
db_app::import('financeiro.*');
db_app::import("Dotacao");
db_app::import("empenho.*");
db_app::import("exceptions.*");


$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$aDadosRetorno      = array();

  switch ($oParam->exec) {

    /**
     * case para gera��o do empenho passivo
     */

    case 'gerarEmpenhoPassivo':

      try {

        $iInscricaoPassivo       = $oParam->iInscricaoPassivo                               ;
        $nValorDotacao           = $oParam->nValorDotacao                                   ;
        $iFavorecido             = $oParam->iFavorecido                                     ;
        $iDotacao                = $oParam->iDotacao                                        ;
        $iTipoCompra             = $oParam->iTipoCompra                                     ;
        $iTipoLicitacao          = $oParam->iTipoLicitacao                                  ;
        $iLicitacao              = $oParam->iLicitacao                                      ;
        $iTipoEmpenho            = $oParam->iTipoEmpenho                                    ;
        $iHistorico              = $oParam->iHistorico                                      ;
        $iEvento                 = $oParam->iEvento                                         ;
        $iCodEle                 = $oParam->iCodEle                                         ;
        $sDestino                = $oParam->sDestino                                        ;
        $iCaracteristicaPeculiar = $oParam->iCaracteristicaPeculiar                         ;
        $sResumo                 = $oParam->sResumo                                         ;
        $lLiquidar               = $oParam->lLiquidar == 1 ? true : false;
        $iNumeroNota             = $oParam->iNota                                           ;
        $dtNota                  = implode("-", array_reverse(explode("/",$oParam->dNota))) ;
        $sInfoPagamento          = $oParam->sInfoPagamento                                  ;
        $iAnousu                 = db_getsession("DB_anousu");


        db_inicio_transacao();

        $oInscricaoPassivo    = new InscricaoPassivoOrcamento($iInscricaoPassivo);
        $aItensInscricao      = $oInscricaoPassivo->getItens();
        $nValorTotalInscricao = $oInscricaoPassivo->getValorTotalInscricao();

        /**
         * Incluimos a autoriza��o de empenho pois n�o podemos gerar um empenho sem antes autoriz�-lo
         */
        $oAutorizacaoEmpenho = new AutorizacaoEmpenho();
        $oAutorizacaoEmpenho->setDotacao($iDotacao);
        $oAutorizacaoEmpenho->setCaracteristicaPeculiar($iCaracteristicaPeculiar);
        $oAutorizacaoEmpenho->setTipoLicitacao($iTipoLicitacao);
        $oAutorizacaoEmpenho->setFornecedor(CgmFactory::getInstanceByCgm($iFavorecido));
        $oAutorizacaoEmpenho->setValor($nValorTotalInscricao);
        $oAutorizacaoEmpenho->setTipoEmpenho($iTipoEmpenho);
        $oAutorizacaoEmpenho->setTipoCompra($iTipoCompra);
        $oAutorizacaoEmpenho->setDestino($sDestino);
        $oAutorizacaoEmpenho->setNumeroLicitacao($iLicitacao);
        $oAutorizacaoEmpenho->setResumo($sResumo);

        /**
         * Percorremos o array de itens da inscri��o, adicionado ele ao objeto da autoriza��o de empenho
         */
        foreach ($aItensInscricao as $oItemInscricao) {

          $oItem = new stdClass();
          $oItem->codigomaterial = $oItemInscricao->getMaterialCompras()->getMaterial();
          $oItem->quantidade     = $oItemInscricao->getQuantidade();
          $oItem->valortotal     = $oItemInscricao->getValorTotal();
          $oItem->observacao     = $oItemInscricao->getObservacao();
          $oItem->codigoelemento = $oInscricaoPassivo->getCodigoElemento();
          $oItem->valorunitario  = $oItemInscricao->getValorUnitario();
          $oAutorizacaoEmpenho->addItem($oItem);
        }

        /**
         * Salvamos a autoriza��o e os itens
         */
        $oAutorizacaoEmpenho->salvar();

        /**
         * @todo definir quem deve fazer o v�nculo entre a autorizacao de empenho e a inscricao
         */
        $oDaoVinculoAutorizaInscricao = db_utils::getDao('empautorizainscricaopassivo');
        $oDaoVinculoAutorizaInscricao->e16_empautoriza      = $oAutorizacaoEmpenho->getAutorizacao();
        $oDaoVinculoAutorizaInscricao->e16_inscricaopassivo = $oInscricaoPassivo->getSequencial();
        $oDaoVinculoAutorizaInscricao->incluir();
        if ($oDaoVinculoAutorizaInscricao->erro_status == 0) {

          $sMsgErro  = "Imposs�vel criar o v�nculo entre a autoriza��o de empenho e a inscri��o.\n\n";
          $sMsgErro .= "Erro T�cnico: {$oDaoVinculoAutorizaInscricao->erro_msg}";
          throw new BusinessException($sMsgErro);
        }


        /**
         * Setamos as propriedades do empenho
         */
        $oDotacao = new Dotacao($iDotacao, $iAnousu);
        $oEmpenho = new EmpenhoFinanceiro();
  		  $oEmpenho->setAnoUso($iAnousu);
  		  $oEmpenho->setDotacao($oDotacao);
  		  $oEmpenho->setCgm(CgmFactory::getInstanceByCgm($iFavorecido));
  		  $oEmpenho->setDataEmissao(date("Y-m-d", db_getsession("DB_datausu")));
  		  $oEmpenho->setDataVencimento(date("Y-m-d", db_getsession("DB_datausu")));
  		  $oEmpenho->setValorOrcamento($nValorDotacao);
  		  $oEmpenho->setValorEmpenho($nValorTotalInscricao);
  		  $oEmpenho->setValorLiquidado('0');
  		  $oEmpenho->setValorPago('0');
  		  $oEmpenho->setValorAnulado('0');
  		  $oEmpenho->setTipoEmpenho($iTipoEmpenho);
  		  $oEmpenho->setResumo($sResumo);
  		  $oEmpenho->setDestino($sDestino);
  		  $oEmpenho->setInstituicao(new Instituicao(db_getsession('DB_instit')));
  		  $oEmpenho->setTipoCompra($iTipoCompra);
  		  $oEmpenho->setTipoEvento($iEvento);
  		  $oEmpenho->setCaracteristicaPeculiar($iCaracteristicaPeculiar);
  		  $oEmpenho->setAutorizacaoEmpenho($oAutorizacaoEmpenho);

  		  /**
  		   * Pegamos os itens da autoriza��o inclusos anteriormente pois precisamos do mesmo e55_sequen na empempitem
  		   */
  		  $aItensAutorizacao  = $oAutorizacaoEmpenho->getItens();
  		  $aItensLiquidarNota = array();
  		  foreach ($aItensAutorizacao as $oItemAutorizacao) {

          $oEmpenhoItem = new EmpenhoFinanceiroItem();
          $oEmpenhoItem->setCodigoElemento($oInscricaoPassivo->getCodigoElemento());
          $oEmpenhoItem->setDescricao($oItemAutorizacao->observacao);
          $oEmpenhoItem->setItemMaterialCompras(new MaterialCompras($oItemAutorizacao->codigomaterial));
          $oEmpenhoItem->setQuantidade($oItemAutorizacao->quantidade);
          $oEmpenhoItem->setSequencialAutorizacaoItem($oItemAutorizacao->sequencial);
          $oEmpenhoItem->setValorTotal($oItemAutorizacao->valortotal);
          $oEmpenhoItem->setValorUnitario($oItemAutorizacao->valorunitario);
  		    $oEmpenho->adicionarItem($oEmpenhoItem);

  		    /*
  		     * Verifico se � necess�rio liquidar. Caso seja, alimento um array contendo os itens que devemos
  		     * liquidar. Fiz a alimenta��o do array aqui para poupar um foreach mais a baixo ap�s salvar o empenho
  		     */
  		    if ($lLiquidar) {

  		      $oDadosItemLiquidar             = new db_stdClass();
  		      $oDadosItemLiquidar->sequen     = $oItemAutorizacao->sequencial;
  		      $oDadosItemLiquidar->quantidade = $oItemAutorizacao->quantidade;
  		      $oDadosItemLiquidar->vlrtot     = $oItemAutorizacao->valortotal;
  		      $oDadosItemLiquidar->vlruni     = $oItemAutorizacao->valorunitario;
  		      $aItensLiquidarNota[]           = $oDadosItemLiquidar;
  		      unset($oDadosItemLiquidar);
  		    }
  		  }

  		  /**
  		   * Salvamos o empenho e seus itens
         */
  		  $oEmpenho->salvar();

  		  /**
  		   * Inicio dos Lan�amentos cont�beis
  		   */
  			$oLancamentoAuxiliarEmpenho = new LancamentoAuxiliarEmpenhoPassivo();
  			$oLancamentoAuxiliarEmpenho->setCaracteristicaPeculiar($iCaracteristicaPeculiar);
  			$oLancamentoAuxiliarEmpenho->setComplemento(db_stdClass::normalizeStringJson($sResumo));
  			$oLancamentoAuxiliarEmpenho->setCodigoElemento($iCodEle);
  			$oLancamentoAuxiliarEmpenho->setNumeroEmpenho($oEmpenho->getNumero());
  			$oLancamentoAuxiliarEmpenho->setCodigoDotacao($iDotacao);
  			$oLancamentoAuxiliarEmpenho->setFavorecido($oInscricaoPassivo->getFavorecido()->getCodigo());
  			$oLancamentoAuxiliarEmpenho->setHistorico($iHistorico);
  			$oLancamentoAuxiliarEmpenho->setObservacaoHistorico(db_stdClass::normalizeStringJson($sResumo));
  			$oLancamentoAuxiliarEmpenho->setValorTotal($nValorTotalInscricao);
  			$oLancamentoAuxiliarEmpenho->setInscricao($oInscricaoPassivo->getSequencial());

  			/**
  			 * Descobrimos o documento em conhistdoc que deveremos executar os lan�amentos cont�beis
  			 */
  			$oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento(10);
  			$oDocumentoContabil->setValorVariavel("[numeroempenho]", $oEmpenho->getNumero());
  			$iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

  			/**
  			 * Executamos os lan�amentos cont�beis para o EMPENHO
  			 */
  			$oEventoContabil = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
  			$oEventoContabil->executaLancamento($oLancamentoAuxiliarEmpenho);
  			unset($oEventoContabil);
  			unset($oDocumentoContabil);
  			unset($iCodigoDocumentoExecutar);
  			unset($oLancamentoAuxiliarEmpenho);

  			/**
  			 * Verifico se o usu�rio selecionou para liquidar o empenho.
  			 */
  			$sMsgLiquidar = "";
  			if ($lLiquidar) {

  			  $oFunctionsEmpenho = new empenho();
  			  $oFunctionsEmpenho->setEmpenho($oEmpenho->getNumero());
  			  $oFunctionsEmpenho->gerarOrdemCompra($iNumeroNota,
  			                                       $nValorTotalInscricao,
  			                                       $aItensLiquidarNota,
  			                                       true, // Liquidar ap�s gerar OC
  			                                       $oParam->dNota,
  			                                       $sInfoPagamento,
  			                                       false); // Transacao Ativa
  			  if ($oFunctionsEmpenho->erro_status == 0 && trim($oFunctionsEmpenho->erro_msg) != "") {
  			    throw new BusinessException($oFunctionsEmpenho->erro_msg);
  			  } else if ($oFunctionsEmpenho->sMsgErro != "") {
  			  	throw new BusinessException($oFunctionsEmpenho->sMsgErro);
  			  }
  			  $sMsgLiquidar = "e liquidado ";
  			}

        $oRetorno->iStatus        = 1;
			  $oRetorno->sMessage       = urlencode("Empenho {$oEmpenho->getCodigo()}/{$oEmpenho->getAnoUso()} salvo {$sMsgLiquidar}com sucesso!");
			  $oRetorno->iNumeroEmpenho = $oEmpenho->getNumero();
			  $oRetorno->lImprimir      = $oParam->lImprimir;
			  db_fim_transacao(false);

      } catch (Exception $eErroException) {

        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode(str_replace("\\n", "\n", $eErroException->getMessage()));
        db_fim_transacao(true);
      } catch (BusinessException $eErroBusiness) {

        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode(str_replace("\\n", "\n", $eErroBusiness->getMessage()));
        db_fim_transacao(true);
      }
    break;


    /**
     * retorna os dados da inscri��o
     */
    case 'getDadosInscricao':

      try {

        $oInscricaoPassivoOrcamento     = new InscricaoPassivoOrcamento($oParam->iInscricao);
        $aItensInscricao                = $oInscricaoPassivoOrcamento->getItens();
        $nValorTotalInscricaoPassivo    = $oInscricaoPassivoOrcamento->getValorTotalInscricao();
        $oRetorno->iCodigoFavorecido    = $oInscricaoPassivoOrcamento->getFavorecido()->getCodigo();
        $oRetorno->sNomeFavorecido      = $oInscricaoPassivoOrcamento->getFavorecido()->getNome();
        $oRetorno->iCodigoElemento      = $oInscricaoPassivoOrcamento->getCodigoElemento();
        $oRetorno->sDescricaoElemento   = $oInscricaoPassivoOrcamento->getDescricaoElemento();
        $oRetorno->iElemento            = $oInscricaoPassivoOrcamento->getDesdobramentoElemento();
        $oRetorno->iAnoDesdobramento    = $oInscricaoPassivoOrcamento->getAnoElemento();
        $oRetorno->iCodigoHistorico     = $oInscricaoPassivoOrcamento->getCodigoHistorico();
        $oRetorno->sObservacaoHistorico = $oInscricaoPassivoOrcamento->getObservacaoHistorico();
        $oRetorno->dDataInscricao       = $oInscricaoPassivoOrcamento->getDataInscricao();
        $oRetorno->nValorTotalInscricao = $nValorTotalInscricaoPassivo;
        if ($oInscricaoPassivoOrcamento->hasEmpenho()) {
          throw new BusinessException("A inscri��o passiva {$oParam->iInscricao} j� est� empenhada.");
        }

      } catch (BusinessException $eErro) {

        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode(str_replace("\\n", "\n", $eErros->getMessage()));
      }
    break;

    /**
     * Retorna o saldo da dotacao
     */
    case 'getSaldoDotacao':

      $iCodigoDotacao          = $oParam->iDotacao;
      $iAno                    = db_getsession("DB_anousu");
      $oDotacao                = new Dotacao($iCodigoDotacao, $iAno);
      $oRetorno->iSaldoDotacao = $oDotacao->getSaldoAtualMenosReservado();

    break;
  }
echo $oJson->encode($oRetorno);
?>