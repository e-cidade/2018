<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once('classes/db_acordocomissao_classe.php');
require_once('classes/db_acordocomissaomembro_classe.php');
require_once("classes/db_acordotipo_classe.php");
require_once("classes/db_acordopenalidade_classe.php");
require_once("classes/db_acordogarantia_classe.php");
require_once('model/AcordoComissao.model.php');
require_once('model/Acordo.model.php');
require_once('model/AcordoItem.model.php');
require_once('model/AcordoComissaoMembro.model.php');
require_once("model/AcordoPenalidade.model.php");
require_once("model/AcordoGarantia.model.php");
require_once("model/CgmFactory.model.php");
require_once('model/CgmBase.model.php');
require_once('model/CgmFisico.model.php');
require_once('model/CgmJuridico.model.php');
require_once("model/MaterialCompras.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/licitacao.model.php");
require_once("model/ProcessoCompras.model.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("model/AcordoDocumento.model.php");
require_once("model/empenho/EmpenhoFinanceiro.model.php");
require_once("model/Dotacao.model.php");
require_once("model/configuracao/Instituicao.model.php");
require_once("model/ProcessoCompras.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
switch($oParam->exec) {

  case 'itensConsulta' :

    $oRetorno->dados = array();

  	$oAcordo    = new Acordo($oParam->ac16_sequencial);
  	$aPosicao   = $oAcordo->getPosicoes();
  	$nSomaTotal = 0;

  	foreach ($aPosicao as $oPosicao) {
  	  
  		foreach ($oPosicao->getItens() as $oDado) {

	  		$oItem = new stdClass();
	  		
	  		if ($oPosicao->getTipo() == 1) {

	  			$oItem->aditamento = '';
          $oItem->tipo       = '';
	  		} else {

	  		  $oItem->aditamento = urlencode($oPosicao->getDescricaoTipo());
          $oItem->tipo       = $oPosicao->getTipo();
	  		}

	  		$oItem->codigo      = $oDado->getMaterial()->getMaterial();
	  		$oItem->descricao   = $oDado->getMaterial()->getDescricao();
	  		$oItem->quantidade  = $oDado->getQuantidade();
	  		$oItem->unidade     = $oDado->getUnidade();
	  		$oItem->vlrUnit     = $oDado->getValorunitario();
	  		$oItem->vlrTotal    = $oDado->getValorTotal();
	  		$oItem->dotacoes    = $oDado->getDotacoes();
	  		$oItem->saldos      = $oDado->getSaldos();
	  		$oItem->ordem       = $oDado->getOrdem();
	  		$oItem->elemento    = $oDado->getElemento();
	  		$oItem->unidademed  = $oDado->getDescricaoUnidade();
	  		$oItem->observacao  = $oDado->getResumo();
	      $nSomaTotal        += $oDado->getValorTotal();
	      $oRetorno->dados[]  = $oItem;
  		}
  	}
  	$oRetorno->ac16_sequencial = $oParam->ac16_sequencial;


    //Soma do valor total de cada item
    $oRetorno->nValorTotal = $nSomaTotal;

    $oRetorno->detalhe  = $oParam->detalhe;

    break;

  case "licitacoesConsulta":

    $oAcordo               = new Acordo($oParam->ac16_sequencial);
    $aLicitacoesVinculadas = $oAcordo->getLicitacoes();
    $oRetorno->dados       = array();

    foreach ($aLicitacoesVinculadas as $oLicitacao) {


      $oStdDados     = $oLicitacao->getDados();
      $oStdLicitacao = new stdClass();
      $oStdLicitacao->iCodigoLicitacao     = $oStdDados->l20_codigo;
      $oStdLicitacao->sObjetoLicitacao     = $oStdDados->l20_objeto;
      $oStdLicitacao->sLocalLicitacao      = $oStdDados->l20_local;
      $oStdLicitacao->dtCriacaoLicitacao   = $oStdDados->l20_datacria;
      $oStdLicitacao->iModalidadeLicitacao = $oStdDados->l20_codtipocom;
      $oStdLicitacao->sModalidadeLicitacao = utf8_encode($oStdDados->l03_descr);

      $oRetorno->dados[] = $oStdLicitacao;
    }

    $oRetorno->detalhe         = $oParam->detalhe;
    $oRetorno->ac16_sequencial = $oParam->ac16_sequencial;

    break;

  case "processodecomprasConsulta":

    $oAcordo                       = new Acordo($oParam->ac16_sequencial);
    $aProcessosDeComprasVinculados = $oAcordo->getProcessosDeCompras();
    $oRetorno->dados               = array();

    foreach ($aProcessosDeComprasVinculados as $oProcessoDeCompra) {

      $oStdProcesso = new stdClass();
      $oStdProcesso->iCodigoProcesso        = $oProcessoDeCompra->getCodigo();
      /**
       * Coloquei (string) na frente pois com o substr pode retornar um booleano,
       * caso não venha conteudo.
       */
      $oStdProcesso->sResumoProcesso        = (string)substr($oProcessoDeCompra->getResumo(), 0, 65);
      $oStdProcesso->dtEmissaoProcesso      = $oProcessoDeCompra->getDataEmissao();
      $oStdProcesso->iCodigoDepartamento    = $oProcessoDeCompra->getCodigoDepartamento();
      $oStdProcesso->sDescricaoDepartamento = $oProcessoDeCompra->getDescricaoDepartamento();

      $oRetorno->dados[] = $oStdProcesso;
    }

    $oRetorno->detalhe         = $oParam->detalhe;
    $oRetorno->ac16_sequencial = $oParam->ac16_sequencial;

    break;

  case "empenhosConsulta":

    $oAcordo             = new Acordo($oParam->ac16_sequencial);
    $aEmpenhosVinculados = $oAcordo->getEmpenhos();
    $oRetorno->dados    = array();

    foreach ($aEmpenhosVinculados as $oEmpenhoFinanceiro) {

      $oStdEmpenho = new stdClass();
      $oStdEmpenho->iNumeroEmpenho          = $oEmpenhoFinanceiro->getNumero();
      $oStdEmpenho->iCodigoEmpenho          = $oEmpenhoFinanceiro->getCodigo();
      $oStdEmpenho->iAnoEmpenho             = $oEmpenhoFinanceiro->getAnoUso();
      $oStdEmpenho->iCaracteristicaPeculiar = $oEmpenhoFinanceiro->getCaracteristicaPeculiar();
      $oStdEmpenho->iValorEmpenho           = $oEmpenhoFinanceiro->getValorEmpenho();
      $oStdEmpenho->dtEmissaoEmpenho        = $oEmpenhoFinanceiro->getDataEmissao();
      /**
       * Coloquei (string) na frente pois com o substr pode retornar um booleano,
       * caso não venha conteudo.
       */
      $oStdEmpenho->sResumoEmpenho          = (string)substr($oEmpenhoFinanceiro->getResumo(), 0, 65);

      $oRetorno->dados[] = $oStdEmpenho;
    }

    $oRetorno->detalhe         = $oParam->detalhe;
    $oRetorno->ac16_sequencial = $oParam->ac16_sequencial;

    break;

  case 'empenhamentosConsulta' :

  	$oAcordo         = new Acordo($oParam->ac16_sequencial);
  	$aEmpenhamentos  = $oAcordo->getAutorizacoes();
    $oRetorno->dados = array();

  	foreach ($aEmpenhamentos as $oEmpenhamento) {

  		$oAut = new stdClass();
  		$oAut->codigoAutorizacao = $oEmpenhamento->codigo;
  		$oAut->empenho           = $oEmpenhamento->empenho;
  		$oAut->codigoempenho     = $oEmpenhamento->codigoempenho;
  		$oAut->dataEmissao       = $oEmpenhamento->dataemissao;
  		$oAut->dataAnulacao      = $oEmpenhamento->dataanulacao;
  		$oAut->valor             = $oEmpenhamento->valor;

  		$oRetorno->dados[]       = $oAut;
  	}

    $oRetorno->detalhe  = $oParam->detalhe;
    $oRetorno->ac16_sequencial = $oParam->ac16_sequencial;

    break;

  case 'aditamentosConsulta' :

    $oRetorno->dados = array();

    $oAcordo = new Acordo($oParam->ac16_sequencial);
    $aDados = $oAcordo->getPosicoes();

    
    foreach ($aDados as $oDado) {
      
      $oItem = new stdClass();
	    $oItem->codigo      = $oDado->getCodigo();
	    $oItem->situacao    = urlencode($oDado->getDescricaoTipo());
	    $oItem->data        = $oDado->getData();
	    $oItem->emergencial = $oDado->isEmergencial();
	    $oItem->vigencia    = urlencode($oDado->getVigenciaInicial()." até ".$oDado->getVigenciaFinal());
	    $oItem->numeroAditamento = urlencode($oDado->getNumeroAditamento());
      $oRetorno->dados[] = $oItem;
    }

    $oRetorno->detalhe          = $oParam->detalhe;
    $oRetorno->ac16_sequencial  = $oParam->ac16_sequencial;

    break;

  case 'rescisoesConsulta' :

  	$oRetorno->dados = array();

    $oAcordo = new Acordo($oParam->ac16_sequencial);
    $aDados = $oAcordo->getRecisoes();
    foreach ($aDados as $oDado) {

      $oRecisao = new stdClass();

      $oRecisao->data      = $oDado->ac10_datamovimento;
      $oRecisao->hora      = $oDado->ac10_hora;
      $oRecisao->usuario   = urlencode($oDado->login);
      $oRecisao->motivo    = urlencode($oDado->ac10_obs);

      $oRetorno->dados[] = $oRecisao;
    }
    $oRetorno->detalhe          = $oParam->detalhe;
    $oRetorno->ac16_sequencial  = $oParam->ac16_sequencial;

    break;

  case 'anulacoesConsulta' :


    $oRetorno->dados = array();

    $oAcordo = new Acordo($oParam->ac16_sequencial);
    $aDados = $oAcordo->getAnulacoes();
    //print_r($aDados);

    foreach ($aDados as $oDado) {

      $oAnulacao = new stdClass();

      $oAnulacao->data      = $oDado->ac10_datamovimento;
      $oAnulacao->hora      = $oDado->ac10_hora;
      $oAnulacao->usuario   = urlencode($oDado->login);
      $oAnulacao->motivo    = urlencode($oDado->ac10_obs);

      $oRetorno->dados[] = $oAnulacao;
    }

    $oRetorno->detalhe          = $oParam->detalhe;
    $oRetorno->ac16_sequencial  = $oParam->ac16_sequencial;


    break;

  case 'aditamentosDetalhes' :

    $oRetorno->dados = array();

    $oAcordo    = new Acordo($oParam->ac16_sequencial);
    $aDados     = $oAcordo->getPosicoes();
    $nSomaTotal = 0;
    foreach ($aDados as $oPosicao) {

    	if ($oPosicao->getCodigo() == $oParam->ac26_sequencial) {

    		$aItens = $oPosicao->getItens();

    		if (count($aItens) > 0) {

	        foreach ($aItens as $oDado) {

			      $oItem              = new stdClass();
			      $oItem->codigo      = $oDado->getMaterial()->getMaterial();
			      $oItem->descricao   = $oDado->getMaterial()->getDescricao();
			      $oItem->quantidade  = $oDado->getQuantidade();
			      $oItem->unidade     = $oDado->getUnidade();
			      $oItem->vlrUnit     = $oDado->getValorunitario();
			      $oItem->vlrTotal    = $oDado->getValorTotal();
			      $oItem->dotacoes    = $oDado->getDotacoes();
            $oItem->saldos      = $oDado->getSaldos();
			      $nSomaTotal        += $oDado->getValorTotal();

			      $oRetorno->dados[]         = $oItem;
			      $oRetorno->ac16_sequencial = $oParam->ac16_sequencial;

			    }

    		} else {

    			$oRetorno->dados = false;
    		}
    	}
    }

    $oRetorno->nValorTotal = $nSomaTotal;

    break;
  case 'paralisacoesConsulta':

    $oAcordo             = AcordoRepository::getByCodigo($oParam->ac16_sequencial);
    $aParalisacoes       = array();
    $oRetorno->dados     = array();
    $oRetorno->detalhe   = $oParam->detalhe;
    $aParalisacoesAcordo = $oAcordo->getParalisacoes();
    foreach ($aParalisacoesAcordo as $oParalisacao) {

      $oDadosParalisacao              = new stdClass();
      $oDadosParalisacao->datainicial = $oParalisacao->getDataInicio()->getDate(DBDate::DATA_PTBR);
      $oDadosParalisacao->datafinal   = '';
      $oDadosParalisacao->usuario     = '';
      $oDadosParalisacao->observacao  = '';
      if ($oParalisacao->getDataTermino() != '') {
        $oDadosParalisacao->datafinal = $oParalisacao->getDataTermino()->getDate(DBDate::DATA_PTBR);;
      }

      $oUltimoMovimento = $oParalisacao->getUltimaMovimentacao();
      if (!empty($oUltimoMovimento)) {

        $oUsuario                      = new UsuarioSistema($oUltimoMovimento->getUsuario());
        $oDadosParalisacao->usuario    = urlencode($oUsuario->getNome());
        $oDadosParalisacao->observacao = urlencode($oUltimoMovimento->getObservacao());
      }
      $oRetorno->dados[] = $oDadosParalisacao;
    }
    break;
}
echo $oJson->encode($oRetorno);