<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

/**
 * Carregamos as libs necessárias
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");

require_once("model/CgmFactory.model.php");
require_once("model/MaterialCompras.model.php");

require_once("model/contabilidade/EventoContabil.model.php");
require_once("model/contabilidade/EventoContabilLancamento.model.php");

require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarReconhecimentoReceitaFatoGerador.model.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));

$iAnoUsoSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {
  
  /**
   * Inclui a transação na contranslan
   */
  case 'salvarTransacao':

  	db_inicio_transacao();
    try {
    	
      $oEventoContabil = new EventoContabil();
      $oEventoContabil->setAnoUso($iAnoUsoSessao);
      $oEventoContabil->setCodigoDocumento($oParam->c45_coddoc);
      $oEventoContabil->setInstituicao($iInstituicaoSessao);
      $oEventoContabil->setSequencialTransacao($oParam->c45_seqtrans);
      $oEventoContabil->salvar();

      $oEventoContabilLancamento = new EventoContabilLancamento();
      $oEventoContabilLancamento->setSequencialTransacao($oEventoContabil->getSequencialTransacao());
      $oEventoContabilLancamento->setDescricao(db_stdClass::normalizeStringJson($oParam->c46_descricao));
      $oEventoContabilLancamento->setHistorico($oParam->c46_codhist);
      $oEventoContabilLancamento->setObrigatorio($oParam->c46_obrigatorio);
      $oEventoContabilLancamento->setObservacao(db_stdClass::normalizeStringJson($oParam->c46_obs));
      $oEventoContabilLancamento->setSequencialLancamento($oParam->c46_seqtranslan);
      $oEventoContabilLancamento->setOrdem($oParam->c46_ordem);
      $oEventoContabilLancamento->setEvento(0);
      $oEventoContabilLancamento->setValor(0);
      $oEventoContabilLancamento->salvar();
      
      $oRetorno->iSequencialLancamento = $oEventoContabilLancamento->getSequencialLancamento();
      $oRetorno->iOrdem                = $oEventoContabilLancamento->getOrdem();
      $oRetorno->iSequencialTransacao  = $oEventoContabil->getSequencialTransacao();
      
      $oRetorno->message = urlencode("Evento contábil salvo com sucesso.");
      db_fim_transacao(false);
      
    } catch (Exception $eException) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\\n", "\n", urlencode($eException->getMessage()));
      db_fim_transacao(true);
    }
  break;
    
  case "getLancamentosEventoContabil":

  	try {
	  	$oEventoContabil            = new EventoContabil($oParam->iCodigoDocumento, $iAnoUsoSessao);
	  	$oRetorno->iCodigoDocumento = $oParam->iCodigoDocumento;
	  	$oRetorno->iAnoUso          = $iAnoUsoSessao;
	  	$oRetorno->iTransacao       = $oEventoContabil->getSequencialTransacao();
	  	$aLancamentos               = $oEventoContabil->getEventoContabilLancamento();
	  	$oRetorno->aLancamentos     = array();
	  	foreach ($aLancamentos as $oLancamento) {
	  		
	  		$oStdClass = new stdClass();
				$oStdClass->c46_seqtranslan = $oLancamento->getSequencialLancamento();
	  		$oStdClass->c46_seqtrans    = $oEventoContabil->getSequencialTransacao();
	  		$oStdClass->c46_descricao   =	urlencode($oLancamento->getDescricao());
	  		$oStdClass->c46_ordem       = $oLancamento->getOrdem();
	  		$oStdClass->c45_coddoc      = $oParam->iCodigoDocumento;
	  		$oRetorno->aLancamentos[]   = $oStdClass;
	  	}
  	} catch (Exception $eException) {
  		
  		$oRetorno->message = urlencode($eException->getMessage());
  		$oRetorno->status  = 2;
  	}
  	
  	break;
  	
  case "getInformacaoLancamento":
  	
  	$oEventoContabilLancamento = new EventoContabilLancamento($oParam->iCodigoLancamento);
  	$oRetorno->c46_seqtranslan = $oEventoContabilLancamento->getSequencialLancamento();
  	$oRetorno->c46_ordem       = $oEventoContabilLancamento->getOrdem();
  	$oRetorno->c46_descricao   = urlencode($oEventoContabilLancamento->getDescricao());
  	$oRetorno->c50_codhist     = $oEventoContabilLancamento->getHistorico();
  	$oRetorno->c46_obrigatorio = $oEventoContabilLancamento->getObrigatorio();
  	$oRetorno->c46_obs         = urlencode($oEventoContabilLancamento->getObservacao());
  	$oRetorno->c45_seqtrans    = $oEventoContabilLancamento->getSequencialTransacao();
  	
  	break;
  	
  	
  case 'excluirEventoContabil':
  	
  	db_inicio_transacao();
  	try {
  		
  		$oEventoContabilLancamento = new EventoContabilLancamento($oParam->iCodigoLancamento);
  		$oEventoContabilLancamento->excluir();
  		
  		$oEventoContabil   = new EventoContabil($oParam->iCodigoDocumento, $iAnoUsoSessao);
  		$iTotalLancamentos = count($oEventoContabil->getEventoContabilLancamento());
  		if ($iTotalLancamentos == 0) {
  			$oEventoContabil->excluir();
  		}
  		
  		$oRetorno->message = urlencode("Evento Contábil excluído com sucesso.");
  		
  		db_fim_transacao(false);
  		
  	} catch (Exception $eException) {
  		
  		$oRetorno->message = urlencode($eException->getMessage());
  		$oRetorno->status  = 2;
  		db_fim_transacao(true);
  	}
  	break;
  	
	case 'ordenaTransacoes':
	
		try {
	
			$iTotalLancamentoOrdenar = count($oParam->aDadosOrdem);
			for ($iRowLancamento = 0; $iRowLancamento < $iTotalLancamentoOrdenar; $iRowLancamento++) {
	
				$oEventoContabilLancamento = new EventoContabilLancamento($oParam->aDadosOrdem[$iRowLancamento]);
				$oEventoContabilLancamento->setOrdem($iRowLancamento + 1);
				$oEventoContabilLancamento->salvar();
			}
		} catch (Exception $eException) {
	
			$oRetorno->status  = 2;
			$oRetorno->message = urlencode($eException->getMessage());
		}
		break;
}
echo $oJson->encode($oRetorno);
