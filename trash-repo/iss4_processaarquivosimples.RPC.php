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
 
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");


$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
$aErros                 = array();
try {
  
  db_inicio_transacao();
  switch ($oParam->exec) {
    
    /**
     * case do preprocessamento
     * para verificar as inconsistencias no arquivo antes do processamento
     */
    case "preProcessamento":

    	$oDaoIssArqSimples         = db_utils::getDao("issarqsimples");
    	$aErros                    = $oDaoIssArqSimples->validaArquivo($oParam->iIssArquivo);

      $oRetorno->aErros          = $aErros;
      $oRetorno->iInconsistencia = 1;
    	
    	rsort($aErros);
      db_putsession("aInconsistenciaSimples", $aErros);
    break;
    
    case "processar":
    	
    	$iRegistro = $oParam->iRegistro;
    	$sArquivo  = $oParam->sArquivo ;
    	$iBanco    = $oParam->iBanco   ;
    	$iAgencia  = $oParam->iAgencia ;
    	$iConta    = $oParam->iConta   ;   
    
    	$oDaoIssArqSimples = db_utils::getDao("issarqsimples");
    	
    	$lProcessamento    = $oDaoIssArqSimples->processaArquivo($iRegistro, $sArquivo, $iBanco, $iAgencia, $iConta);
   break;
    /**
     * case para buscar outras inscriчѕes do CNPJ
     */ 
    case "getVariasInscricoes":
      
      $iCgcCpf = $oParam->iCnpj;
      $iLinha  = $oParam->iLinha;
      
      require_once("classes/db_issbase_classe.php");
      $oDaoIssBase = new cl_issbase();
      
      $sCamposInscricao  = "q02_inscr, q02_numcgm, z01_nome ";
      
      $sSqlInscricao = $oDaoIssBase->sql_query(null, $sCamposInscricao, null, "z01_cgccpf = '{$iCgcCpf}' ");
      
      $rsInscricao   = $oDaoIssBase->sql_record($sSqlInscricao);
      $aInscricao    = db_utils::getCollectionByRecord($rsInscricao, true, false, true);
      
      foreach ($aInscricao as $iIndiceInscricao => $oValorInscricao) {
        
        $oDadosInscricao = new stdClass();
        $oDadosInscricao->q02_inscr   = $oValorInscricao->q02_inscr;      
        $oDadosInscricao->q02_numcgm  = $oValorInscricao->q02_numcgm;
        $oDadosInscricao->z01_nome    = $oValorInscricao->z01_nome;
        $aDadosRetorno[] = $oDadosInscricao; 
        
      }
      
      $oRetorno->aDados = $aDadosRetorno;
      $oRetorno->iCnpj  = $iCgcCpf;
      $oRetorno->iLinha = $iLinha;
    
    break;    
    
    /**
     * case para realizar o vinculo com cnpj e inscricao desejada
     */
    case "vincularInscricao":
    
      require_once("classes/db_issarqsimplesregissbase_classe.php");
      
      $iCnpj      = $oParam->iCnpj ;
      $iInscricao = $oParam->iInscricao   ;    
      $iCgm       = $oParam->iCgm   ; 
      $iLinha     = $oParam->iLinha ;
      
      $oDaoissarqsimplesregissbase = new cl_issarqsimplesregissbase;
      $oDaoissarqsimplesregissbase->excluir(null, "q134_issarqsimplesreg = {$iLinha}");
      
      $oDaoissarqsimplesregissbase->q134_inscr            = $iInscricao;
      $oDaoissarqsimplesregissbase->q134_issarqsimplesreg = $iLinha;
 
      $oDaoissarqsimplesregissbase->incluir(null);
      if ($oDaoissarqsimplesregissbase->erro_status == "0") {
        
        throw new Exception($oDaoissarqsimplesregissbase->erro_msg);
      }
       
      $oRetorno->sMessage = "Vinculaчуo Realizada com Sucesso.";
      
    break;    
    
    /**
     * Caso para buscar vсrios CGMs com o mesmo CNPJ para que um seja corrigido
     */
    case 'getVariosCgm' :
    	
    	require_once 'classes/db_cgm_classe.php';
    	
    	$iCgcCpf    = $oParam->iCnpj;
    	$sCamposCgm = 'z01_nome, z01_numcgm, z01_cgccpf';
    	
    	$oDaoCgm    = new cl_cgm;
    	$sSqlCgm    = $oDaoCgm->sql_query_file(null, $sCamposCgm, null, "z01_cgccpf = '{$iCgcCpf}'");
    	$rsCgm      = $oDaoCgm->sql_record($sSqlCgm);
    	            
    	$aCgm       = db_utils::getCollectionByRecord($rsCgm, true, false, true);
    	
    	/**
    	 * validamos permissao de alteraчуo do Cgm, do usuario logado
    	 */
    	
    	$iAno       = db_getsession('DB_anousu');
    	$iModulo    = 604;
    	$iItemMenu  = 1306;
    	$lPermissao = 'false';
    	
    	if (db_permissaomenu($iAno, $iModulo, $iItemMenu ) == "true"){
    		$lPermissao = 'true';
    	}    	
    	
    	foreach($aCgm as $iIndiceCgm => $oValorCgm) {
    		
    		$oDadoCgm = new stdClass();
    		$oDadoCgm->z01_nome   = $oValorCgm->z01_nome;
    		$oDadoCgm->z01_cgccpf = $oValorCgm->z01_cgccpf;
    		$oDadoCgm->z01_numcgm = $oValorCgm->z01_numcgm;
    		$aDadosRetorno[] = $oDadoCgm;
    	}
    	
    	$oRetorno->aDados     = $aDadosRetorno;
    	$oRetorno->lPermissao = $lPermissao;
    	
    break;    
    
    case "salvaComplementar":
    	
    	$oDaoIssArqSimplesReg = db_utils::getDao('issarqsimplesreg');
    	 
    	$oDaoIssArqSimplesReg->q23_sequencial = $oParam->iRegistro;
    	$oDaoIssArqSimplesReg->q23_acao       = $oParam->iOpcao;
    	$oDaoIssArqSimplesReg->alterar($oParam->iRegistro);
    	 
    	if ($oDaoIssArqSimplesReg->erro_status == "0") {
    		throw new Exception($oDaoIssArqSimplesReg->erro_msg);
    	}
    	 
    break;    
    
    default:
      throw new ErrorException("Nenhuma Opчуo Definida");
    break;
  }
  
  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  
  echo $oJson->encode($oRetorno);
  
  db_fim_transacao();
} catch (Exception $eErro){
	
	db_fim_transacao(true);
	
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>