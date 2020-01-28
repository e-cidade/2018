<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/exceptions/BusinessException.php");
require_once("interfaces/IRegraLancamentoContabil.interface.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once "model/contabilidade/planoconta/ContaPlano.model.php";

db_app::import("CgmFactory");
db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import('contabilidade.contacorrente.*');

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

switch ($oParam->exec) {


  /**
   * Case para Incluir a inscrição passiva e efetuar os lançamentos contábeis
   */
  case "incluirInscricao":

    db_inicio_transacao();
    try {

      /*
       * Setamos as propriedades da inscricao
       */
      $oInscricaoPassivo = new InscricaoPassivoOrcamento();
      $oInscricaoPassivo->setAnoElemento(db_getsession("DB_anousu"));
      $oInscricaoPassivo->setCodigoElemento($oParam->c36_codele);
      $oInscricaoPassivo->setCodigoHistorico($oParam->c36_conhist);
      $oInscricaoPassivo->setDataInscricao(date("Y-m-d", db_getsession("DB_datausu")));
      $oInscricaoPassivo->setFavorecido(CgmFactory::getInstanceByCgm($oParam->c36_cgm));
      $oInscricaoPassivo->setInstituicao(new Instituicao(db_getsession("DB_instit")));
      $oInscricaoPassivo->setObservacaoHistorico(addslashes(db_stdClass::normalizeStringJson($oParam->c36_observacaoconhist)));
      $oInscricaoPassivo->setUsuarioInscricao(new UsuarioSistema(db_getsession("DB_id_usuario")));

      /*
       * Percorremos o array com os itens cadastrados na tela pelo usuário e criamos um objeto do tipo
       * InscricaoPassivoOrcamentoItem e adicionamos este objeto a InscricaoPassiva
       */
      $nValorTotalItens = 0;
      foreach ($oParam->aItens as $iIndice => $oStdItem) {

        /*
         * Configuramos os valores para o formato banco
         */
        $iQuantidadeItem    = str_replace(",", ".", $oStdItem->c38_quantidade);
        $nValorTotalItem    = str_replace(",", ".", $oStdItem->c38_valortotal);
        $nValorUnitarioItem = str_replace(",", ".", $oStdItem->c38_valorunitario);

        $oItem = new InscricaoPassivoOrcamentoItem();
        $oItem->setMaterialCompras(new MaterialCompras($oStdItem->c38_pcmater));
        $oItem->setObservacao(addslashes(db_stdClass::normalizeStringJson($oStdItem->c38_observacao)));
        $oItem->setQuantidade($iQuantidadeItem);
        $oItem->setValorTotal($nValorTotalItem);
        $oItem->setValorUnitario($nValorUnitarioItem);
        $oInscricaoPassivo->adicionarItem($oItem);
        $nValorTotalItens += $nValorTotalItem;
      }

      /*
       * Método que vai persistir os dados
       */
      $oInscricaoPassivo->salvar();

      /**
       * Instanciamos o objeto do tipo LancamentoAuxiliarInscricao e setamos suas propriedades.
       * Este objeto tera como responsabilidade salvar os dados em tabelas auxiliares caso seja necessario.
       */
      $oLancamentoAuxiliarInscricao = new LancamentoAuxiliarInscricao();
      $oLancamentoAuxiliarInscricao->setInscricao($oInscricaoPassivo->getSequencial());
      $oLancamentoAuxiliarInscricao->setAnoElemento(db_getsession("DB_anousu"));
      $oLancamentoAuxiliarInscricao->setCodigoElemento($oInscricaoPassivo->getCodigoElemento());
      $oLancamentoAuxiliarInscricao->setFavorecido($oInscricaoPassivo->getFavorecido()->getCodigo());
      $oLancamentoAuxiliarInscricao->setHistorico($oParam->c36_conhist);
      $oLancamentoAuxiliarInscricao->setInscricao($oInscricaoPassivo->getSequencial());
      $oLancamentoAuxiliarInscricao->setObservacaoHistorico(db_stdClass::normalizeStringJson($oParam->c36_observacaoconhist));
      $oLancamentoAuxiliarInscricao->setValorTotal($nValorTotalItens);

      /**
       * Descobrimos o documento em conhistdoc que deveremos executar os lançamentos contábeis
       */
      $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento(80);
      $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

      $oEventoContabil = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
      $oEventoContabil->executaLancamento($oLancamentoAuxiliarInscricao);

      $oRetorno->message = urlencode("Inscrição {$oInscricaoPassivo->getSequencial()} cadastrada com sucesso!");

      db_fim_transacao(false);

    } catch (BusinessException $eErro) {      

      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }

  break;

  /**
   * Anula a inscricao
   */
  case "anularInscricao":

    try {

      /*
       * Persistimos os dados da anulacao
       */
      db_inicio_transacao();
      $oInscricaoPassivo = new InscricaoPassivoOrcamento($oParam->c36_sequencial);
      $iInscricaoPassivo = $oInscricaoPassivo->getSequencial();
      $dtAnulacao        = $oInscricaoPassivo->getDataAnulacao();
      
      if (!empty($dtAnulacao)) {
        throw new BusinessException("A inscrição {$iInscricaoPassivo} está anulada.");
      }
      
      $oInscricaoPassivo->setDataAnulacao(date("Y-m-d", db_getsession("DB_datausu")));
      $oInscricaoPassivo->setObservacaoAnulacao($oParam->c39_observacao);
      $oInscricaoPassivo->setUsuarioInscricaoAnulacao(new UsuarioSistema(db_getsession("DB_id_usuario")));
      $oInscricaoPassivo->anular();     

      /*
       * Buscamos os itens e somamos os seus valores totais para efetuarmos os lancamentos contabeis
       */
      $aItensInscricao  = $oInscricaoPassivo->getItens();
      $nValorTotalItens = 0;
      foreach ($aItensInscricao as $iIndice => $oItem) {
        $nValorTotalItens += $oItem->getValorTotal();
      }

      /**
       * Instanciamos o objeto do tipo LancamentoAuxiliarInscricao e setamos suas propriedades.
       * Este objeto tera como responsabilidade salvar os dados em tabelas auxiliares caso seja necessario.
       */
      $oLancamentoAuxiliarInscricao = new LancamentoAuxiliarInscricao();
      $oLancamentoAuxiliarInscricao->setInscricao($oParam->c36_sequencial);
      $oLancamentoAuxiliarInscricao->setAnoElemento($oInscricaoPassivo->getAnoElemento());
      $oLancamentoAuxiliarInscricao->setCodigoElemento($oInscricaoPassivo->getCodigoElemento());
      $oLancamentoAuxiliarInscricao->setFavorecido($oInscricaoPassivo->getFavorecido()->getCodigo());
      $oLancamentoAuxiliarInscricao->setHistorico($oInscricaoPassivo->getCodigoHistorico());
      $oLancamentoAuxiliarInscricao->setInscricao($oInscricaoPassivo->getSequencial());
      $oLancamentoAuxiliarInscricao->setObservacaoHistorico(addslashes(db_stdClass::normalizeStringJson($oInscricaoPassivo->getObservacaoHistorico())));
      $oLancamentoAuxiliarInscricao->setValorTotal($nValorTotalItens);

      /**
       * Descobrimos o documento em conhistdoc que deveremos executar os lançamentos contábeis
       */
      $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento(81);
      $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

      $oEventoContabil = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
      $oEventoContabil->executaLancamento($oLancamentoAuxiliarInscricao);

      $oRetorno->message = urlencode("Anulação efetuada com sucesso!");
      db_fim_transacao(false);

    } catch (BusinessException $eErro ) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }

  break;

  case 'buscaElementoItem':

    try {

      $oDaoPcMaterEle  = db_utils::getDao('pcmaterele');
      $sCampos         = "pc07_codele, o56_elemento, o56_descr, pc01_servico";
      $sWhere          = "     pc07_codmater = {$oParam->iCodigoMaterial}";

      if (isset($oParam->iCodigoElemento) && !empty($oParam->iCodigoElemento)) {
        $sWhere         .= " and pc07_codele = {$oParam->iCodigoElemento}";
      }

      $sSqlElementos   = $oDaoPcMaterEle->sql_query(null, null, $sCampos, 'o56_descr', $sWhere);
      $rsElementos     = $oDaoPcMaterEle->sql_record($sSqlElementos);
      $iLinhasRetorno  = $oDaoPcMaterEle->numrows;

      if ($iLinhasRetorno == 0) {

        $sMsgErro = "Houve problemas ao loclizar os Elementos de despesa do material.\n\n Contate o Suporte.";
        throw new BusinessException($sMsgErro);
      }

      for ($i = 0; $i < $iLinhasRetorno; $i++) {

        $oLinha = db_utils::fieldsMemory($rsElementos, $i);
        $oDado  = new stdClass();
        //$oDado->lServico                = $oLinha->pc01_servico;
        $oDado->elemento                = substr($oLinha->o56_elemento, 0, 7);
        $oDado->estrutural              = $oLinha->o56_elemento;
        $oDado->descricao               = urlencode($oLinha->o56_descr);
        $oDado->codigoElemento          = $oLinha->pc07_codele;
        $oDado->codigoElementoDescricao = "{$oLinha->pc07_codele} - ".urlencode($oLinha->o56_descr);

        $oRetorno->lServico = $oLinha->pc01_servico;
        $oRetorno->dados[] = $oDado;
      }
    } catch (BusinessException $oErro) {

      $oRetorno->message  = $oErro->getMessage();
      $oRetorno->status   = 2;
    }
  break;
}

echo $oJson->encode($oRetorno);