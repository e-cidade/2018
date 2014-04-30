<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/transacaoContabilLancamento.model.php");
require_once("classes/db_contranslr_classe.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {
	
	/**
	 * Salva ou altera um lançamento
	 */
	case "salvarLancamento":

		try {
			
			$oLancamento = new transacaoContabilLancamento();
			$oLancamento->setCodigoTransacao($oParam->c46_seqtrans);
			$oLancamento->setObservacao($oParam->c46_observacao);
			$oLancamento->setHistorico($oParam->c46_ordem);
			$oLancamento->setValor(0);
			$oLancamento->setOrdem($oParam->c46_ordem);
			$oLancamento->setDescricao($oParam->c46_descricao);
			$oLancamento->save();
			
		} catch (Exception $eErro) {

			$oRetorno->message = url_encode($eErro->getMessage());
			$oRetorno->status  = 2;
		}
	break;
	
	/**
	 * Exclui um lançamento e suas dependencias
	 */
	case "excluirLancamento":
		
		try {
			
			$oLancamento = new transacaoContabilLancamento();
			$oLancamento->setCodigoTransacao($oParam->c46_seqtrans);
			$oLancamento->excluirLancamento();
			
		} catch (Exception $eErro) {
			
			$oRetorno->message = url_encode($eErro->getMessage());
			$oRetorno->status  = 2;
		}
  break;
	
  /**
   * Salva uma conta do lançamento
   */
  case "salvaContaLancamento":
  
  	try {
  			
  		$oLancamento = new transacaoContabilLancamento();
  		$oLancamento->salvarContaLancamento($oParam->iAno, $oParam->iCredito, $oParam->iDebito, $oParam->iInstituicao,
  				                                $oParam->iTipoResto, $oParam->iReferencia, $oParam->iCompara, $oParam->sObservacao);
  			
  	} catch (Exception $eErro) {
  			
  		$oRetorno->message = url_encode($eErro->getMessage());
  		$oRetorno->status  = 2;
  	}
  break;
  
  /**
   * Exclui uma conta do Lancamento
   */
  case "excluirContaLancamento":
  
  	try {
  			
  		$oLancamento = new transacaoContabilLancamento();
  		$oLancamento->excluirContaLancamento($oParam->c47_seqtranslr);
  			
  	} catch (Exception $eErro) {
  			
  		$oRetorno->message = url_encode($eErro->getMessage());
  		$oRetorno->status  = 2;
  	}
  break;
  
  /**
   * Busca todos os lancamentos de uma transacao
   */
  case "getLancamentos":
  	
  	$oLancamento            = new transacaoContabilLancamento($oParam->c45_seqtrans);
  	$oRetorno->aLancamentos = $oLancamento->getLancamentos();
  break;
  
  /**
   * Busca os dados de uma conta de um lançamento
   */
  case "getContaLancamento":
  	 
  	$oLancamento                = new transacaoContabilLancamento();
  	$oRetorno->oContaLancamento = $oLancamento->getContaLancamento($oParam->c47_seqtranslr);
  break;
  
  case 'ordenaTransacoes':
  
    try {
  
      for ($i = 0; $i < count($oParam->aDadosOrdem); $i++) {
  
        $oTransacao = new transacaoContabilLancamento($oParam->aDadosOrdem[$i]);
        $oTransacao->setOrdem($i + 1);
        $oTransacao->save();
      }
    } catch (Exception $eException) {
  
      $oRetorno->status  = 2;
      $oRetorno->message = $eException->getMessage();
    }
  break;
}
echo $oJson->encode($oRetorno);
?>