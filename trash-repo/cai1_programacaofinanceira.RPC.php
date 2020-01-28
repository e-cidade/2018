<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("model/ProgramacaoFinanceira.model.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson               = new services_json();
$oRetorno            = new stdClass(); 
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->status    = 0;
$oRetorno->aParcelas = array();
$iIdUsuario          = db_getsession('DB_id_usuario');

switch ($oParam->exec) {

  case "processar":
    
    try {
    	
      if (isset($_SESSION["oProgramacaoFinanceira"])) {

      	db_inicio_transacao();
      	
      	$oProgramacaoFinanceira   = $_SESSION["oProgramacaoFinanceira"];
	      $oProgramacaoFinanceira->setIdUsuario($iIdUsuario);
	      $oProgramacaoFinanceira->setPeriodicidade($oParam->periodicidade);
	      $oProgramacaoFinanceira->setDiaPagamento(str_pad(($oParam->diapagamento),2,"0",STR_PAD_LEFT));
	      $oProgramacaoFinanceira->setValorTotal($oParam->valortotal);
	      $oProgramacaoFinanceira->processar($oParam->numparcelas,str_pad(($oParam->mesinicial),2,"0",STR_PAD_LEFT));
	      
	      $oRetorno->aParcelas      = $oProgramacaoFinanceira->getParcelas();
	      $oRetorno->iPeriodicidade = $oProgramacaoFinanceira->getPeriodicidade();
	      $oRetorno->iDiaPagamento  = $oProgramacaoFinanceira->getDiaPagamento();
	      $oRetorno->iCodigo        = $oProgramacaoFinanceira->getCodigo();
	      $oRetorno->iMesInicial    = $oProgramacaoFinanceira->getMesInicial();
	      
	      db_fim_transacao(false);
      }
      
    } catch (Exception $eErro) {
      
    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
    break;   
    
  case "incluirParcela":
    
    try {
      
      if (isset($_SESSION["oProgramacaoFinanceira"])) {
        
      	db_inicio_transacao();
      	
        $oProgramacaoFinanceira   = $_SESSION["oProgramacaoFinanceira"];
        $sDataPagamento           = implode("-",array_reverse(explode("/",$oParam->dtpagamento)));
        $nValorParcela            = str_replace(",", ".",$oParam->valorparcela);
        $oProgramacaoFinanceira->incluirParcela($oParam->parcela, $sDataPagamento, $nValorParcela);
        
        $oRetorno->aParcelas      = $oProgramacaoFinanceira->getParcelas();
        $oRetorno->iPeriodicidade = $oProgramacaoFinanceira->getPeriodicidade();
        $oRetorno->iDiaPagamento  = $oProgramacaoFinanceira->getDiaPagamento(); 
        $oRetorno->iCodigo        = $oProgramacaoFinanceira->getCodigo();
        $oRetorno->iMesInicial    = $oProgramacaoFinanceira->getMesInicial();
        
        db_fim_transacao(false);
      }
    } catch (Exception $eErro) {

    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
    break;
    
  case "alterarParcela":
    
    try {
      
    	if (isset($_SESSION["oProgramacaoFinanceira"])) {
    		
    		db_inicio_transacao();
    		
        $sDataPagamento           = implode("-",array_reverse(explode("/",$oParam->dtpagamento)));
        $nValorParcela            = str_replace(",", ".",$oParam->nvalorparcela);
    	  $oProgramacaoFinanceira   = $_SESSION["oProgramacaoFinanceira"];
        $oProgramacaoFinanceira->alterarParcela($oParam->parcela, $sDataPagamento, $nValorParcela);
        
        $oRetorno->aParcelas      = $oProgramacaoFinanceira->getParcelas();
        $oRetorno->iPeriodicidade = $oProgramacaoFinanceira->getPeriodicidade();
        $oRetorno->iDiaPagamento  = $oProgramacaoFinanceira->getDiaPagamento(); 
        $oRetorno->iCodigo        = $oProgramacaoFinanceira->getCodigo();
        $oRetorno->iMesInicial    = $oProgramacaoFinanceira->getMesInicial();
        
        db_fim_transacao(false);
    	}
    } catch (Exception $eErro) {

    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
    break;
     
  case "excluirParcela" :
    
    try {
      
    	db_inicio_transacao();
    	
      $oProgramacaoFinanceira   = $_SESSION["oProgramacaoFinanceira"];     
      $oProgramacaoFinanceira->excluirParcela($oParam->parcela);
      
      $oRetorno->aParcelas      = $oProgramacaoFinanceira->getParcelas();
      $oRetorno->iPeriodicidade = $oProgramacaoFinanceira->getPeriodicidade();
      $oRetorno->iDiaPagamento  = $oProgramacaoFinanceira->getDiaPagamento();
      $oRetorno->iCodigo        = $oProgramacaoFinanceira->getCodigo();
      $oRetorno->iMesInicial    = $oProgramacaoFinanceira->getMesInicial();
      
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
    break;
    
  case "salvarProgramacao" :
    
    try {
    	
    	db_inicio_transacao();
    	
      if (isset($_SESSION["oProgramacaoFinanceira"])) {
        $oProgramacaoFinanceira = $_SESSION["oProgramacaoFinanceira"];     
      } else {
        $oProgramacaoFinanceira = new ProgramacaoFinanceira($oParam->codigo);
        $_SESSION["oProgramacaoFinanceira"] = $oProgramacaoFinanceira;
      }
           
      $oProgramacaoFinanceira->save();
      
      $oRetorno->aParcelas      = $oProgramacaoFinanceira->getParcelas();
      $oRetorno->iPeriodicidade = $oProgramacaoFinanceira->getPeriodicidade();
      $oRetorno->iDiaPagamento  = $oProgramacaoFinanceira->getDiaPagamento();
      $oRetorno->iCodigo        = $oProgramacaoFinanceira->getCodigo();
      $oRetorno->iMesInicial    = $oProgramacaoFinanceira->getMesInicial();
      
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
    break;
    
  case "getDados" :
    
    try {
      
    	db_inicio_transacao();
    	
     	$oProgramacaoFinanceira             = new ProgramacaoFinanceira($oParam->codigo);
     	$_SESSION["oProgramacaoFinanceira"] = $oProgramacaoFinanceira;
    	$oRetorno->aParcelas                = $oProgramacaoFinanceira->getParcelas();
    	$oRetorno->iPeriodicidade           = $oProgramacaoFinanceira->getPeriodicidade();
    	$oRetorno->iDiaPagamento            = $oProgramacaoFinanceira->getDiaPagamento(); 
    	$oRetorno->iCodigo                  = $oProgramacaoFinanceira->getCodigo();
    	$oRetorno->iMesInicial              = $oProgramacaoFinanceira->getMesInicial();
    	
    	if (empty($oRetorno->iMesInicial)) {
    	  $oRetorno->iMesInicial  = date('m', db_getsession('DB_datausu'));
    	}
    	
    	db_fim_transacao(false);
    } catch (Exception $eErro) {

    	db_fim_transacao(true);
      $oRetorno->status  = 1;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    
  	break;
}

echo $oJson->encode($oRetorno);
?>