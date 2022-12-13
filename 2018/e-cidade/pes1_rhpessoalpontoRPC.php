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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

require_once("model/pontoFolha.model.php");

include("classes/db_rhpessoal_classe.php");
include("classes/db_pessoal_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_pontofs_classe.php");
include("classes/db_pontofa_classe.php");
include("classes/db_pontofe_classe.php");
include("classes/db_pontofr_classe.php");
include("classes/db_pontof13_classe.php");
include("classes/db_pontocom_classe.php");
include("classes/db_rhrubricas_classe.php");
include("classes/db_lotacao_classe.php");

$oPost  = db_utils::postMemory($_POST);
$oJson  = new services_json();

$clrhpessoal  = new cl_rhpessoal;
$clpessoal    = new cl_pessoal;
$clpontofx    = new cl_pontofx;
$clpontofs    = new cl_pontofs;
$clpontofa    = new cl_pontofa;
$clpontofe    = new cl_pontofe;
$clpontofr    = new cl_pontofr;
$clpontof13   = new cl_pontof13;
$clpontocom   = new cl_pontocom;
$clrhrubricas = new cl_rhrubricas;
$cllotacao    = new cl_lotacao;
$clrotulo     = new rotulocampo;
$oPontoFolha  = new pontoFolha();

$lErro    = false;
$sMsgErro = '';

if ( $oPost->sMethod == 'consultaRubricas') {

  try {
  	$aRetornoRubricas = $oPontoFolha->getRubricasPonto( $oPost->sTipoPonto,
									  	                                  $oPost->iMatric,
									  	                                  $oPost->iAnoUsu,
									  	                                  $oPost->iMesUsu,
									  	                                  db_getsession('DB_instit'));
  	
  } catch (Exception $eException) {
    $sMsgErro = $eException->getMessage();
    $lErro    = true;  	
  }
	
  if ( $lErro ) {
  	$aRetorno = array("lErro"=>true,
  	                   "sMsg"=>urlencode($sMsgErro));
  } else {
	  $aRetorno = array("lErro"     =>false,
	                    "aRubricas" =>$aRetornoRubricas);
  }

  echo $oJson->encode($aRetorno);

  

//*********************************************************************************************//  
// Verifica se já existe registro nas tabelas de acordo com o Tipo de Ponto informado 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'validarRubricas' ){
	
  $aReplace       = array("\\","(",")");
  $oDadosRubrica  = $oJson->decode(str_replace($aReplace,"",$oPost->oDadosRubrica));
  $aDadosRubricas = array($oDadosRubrica);	
	
  try {
    $lExiste  = $oPontoFolha->verificaRubrica($oPost->sTipoPonto,$aDadosRubricas);
  } catch ( Exception $eException ){
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }
  
  
  if ( $lErro ) {
    $aRetorno = array("lErro"=>true,
                      "sMsg"=>urlencode($sMsgErro));
  } else {
    $aRetorno = array("lErro"  =>false,
                      "lExiste"=>$lExiste);
  }

  echo $oJson->encode($aRetorno); 

  
  
//*********************************************************************************************//  
//  Inclusão de Rubricas 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'incluirRubricas' ){
  
  $aReplace       = array("\\","(",")");
  $oDadosRubrica  = $oJson->decode(str_replace($aReplace,"",$oPost->oDadosRubrica));
  $aDadosRubricas = array($oDadosRubrica);	
	
  
  db_inicio_transacao();
  
	try {
		$oPontoFolha->incluiRubricaPonto($oPost->sTipoPonto,$aDadosRubricas);
	} catch ( Exception $eException ){
		$lErro    = true;
		$sMsgErro = $eException->getMessage();
	}
  
	db_fim_transacao($lErro);
	
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));

  echo $oJson->encode($aRetorno);  

  
  
//*********************************************************************************************//  
//  Alteração de Rubricas 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'alterarRubricas' ){
  
  $aReplace       = array("\\","(",")");
  $oDadosRubrica  = $oJson->decode(str_replace($aReplace,"",$oPost->oDadosRubrica));
  $aDadosRubricas = array($oDadosRubrica);  
  
  db_inicio_transacao();
  
  try {
  	
  	if ($oPost->lSoma == 'true') {
  		$lSoma = true;
  	} else {
  		$lSoma = false;
  	}
  	
    $oPontoFolha->alteraRubricaPonto($oPost->sTipoPonto,$aDadosRubricas,$lSoma);
    
  } catch ( Exception $eException ){
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }
  
  db_fim_transacao($lErro);
  
  if ( $oPost->sTipoPonto  == 'fx' || $oPost->sTipoPonto  == 'fs' ) {
    
    if ( $oPost->sTipoPonto  == 'fx' ) {
      $sValorTipoPonto = 'fs';
    } else {
      $sValorTipoPonto = 'fx';
    }
    
    try {
      $lExisteValRepasse  = $oPontoFolha->verificaRubrica($sValorTipoPonto,$aDadosRubricas);
    } catch ( Exception $eException ){
      $lErro    = true;
      $sMsgErro = $eException->getMessage();
    }
  } else {
    $lExisteValRepasse = false; 
  }  

  
  $aRetorno = array("lErro"            =>$lErro,
                    "lExisteValRepasse"=>$lExisteValRepasse,
                    "sMsg"             =>urlencode($sMsgErro));  
  
  echo $oJson->encode($aRetorno);  
  
  
  
//*********************************************************************************************//  
//  Exclusão de Rubricas 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'excluirRubricas' ){
  
  $aReplace       = array("\\","(",")");
  $oDadosRubrica  = $oJson->decode(str_replace($aReplace,"",$oPost->oDadosRubrica));
  $aDadosRubricas = array($oDadosRubrica);  
  
  db_inicio_transacao();
  
  try {
    $oPontoFolha->excluiRubricaPonto($oPost->sTipoPonto,$aDadosRubricas);
  } catch ( Exception $eException ){
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }

  db_fim_transacao($lErro);
  
  if ( $oPost->sTipoPonto  == 'fx' || $oPost->sTipoPonto  == 'fs' ) {
    
    if ( $oPost->sTipoPonto  == 'fx' ) {
      $sValorTipoPonto = 'fs';
    } else {
      $sValorTipoPonto = 'fx';
    }
    
    try {
      $lExisteValRepasse  = $oPontoFolha->verificaRubrica($sValorTipoPonto,$aDadosRubricas);
    } catch ( Exception $eException ){
      $lErro    = true;
      $sMsgErro = $eException->getMessage();
    }
    
  } else {
    $lExisteValRepasse = false; 
  }  
  
  
  $aRetorno = array("lErro"            =>$lErro,
                    "lExisteValRepasse"=>$lExisteValRepasse,
                    "sMsg"             =>urlencode($sMsgErro));  

  echo $oJson->encode($aRetorno);  

  
  
  
//*********************************************************************************************//  
//  Repassar Valores 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'repassarValores' ){
  
  $aReplace       = array("\\","(",")");
  $oDadosRubrica  = $oJson->decode(str_replace($aReplace,"",$oPost->oDadosRubrica));
  $aDadosRubricas = array($oDadosRubrica);  
  
  db_inicio_transacao();
  
  try {
  	$oPontoFolha->repassarValoresFixoSalario($oPost->sTipoPonto,$aDadosRubricas,$oPost->dtDataAdm);
  } catch ( Exception $eException ){
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }
  
  db_fim_transacao($lErro);

  $aRetorno = array("lErro"=>$lErro,
                    "sMsg"=>urlencode($sMsgErro));


  echo $oJson->encode($aRetorno);

  
  
//*********************************************************************************************//  
//  Rubricas Automáricas 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'rubricasAutomaticas' ){
  
  try {
    $aListaRubricas = $oPontoFolha->getRubricasAutomaticas($oPost->iMatric);
  } catch ( Exception $eException ){
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }
  
  if ( !$lErro ) {
	  if ( empty($aListaRubricas) ) {
	  	$lErro    = true;
	  	$sMsgErro = 'Nenhuma rubrica encontrada!';
	  }
	
	  try {
	    $aListaRubricasCadastradas = $oPontoFolha->getRubricasPonto( $oPost->sTipoPonto,
						                                                       $oPost->iMatric,
						                                                       $oPost->iAnoUsu,
						                                                       $oPost->iMesUsu );
	    
	  } catch (Exception $eException) {
	    $sMsgErro = $eException->getMessage();
	    $lErro    = true;   
	  }  
  }
  
  if ( $lErro ) {
    $aRetorno = array("lErro"     =>true,
                       "sMsg"     =>urlencode($sMsgErro));
  } else {
    $aRetorno = array("lErro"                =>false,
                      "aRubricasCadastradas" =>$aListaRubricasCadastradas,
                      "aRubricas"            =>$aListaRubricas);
  }  

  echo $oJson->encode($aRetorno);

  
//*********************************************************************************************//  
//  Inclusão de Rubricas Automáticas 
//*********************************************************************************************//

} else if ( $oPost->sMethod == 'incluirRubricasAutomaticas' ){
  
  $aReplace              = array("\\","(",")");
  $aObjListaRubrica      = $oJson->decode(str_replace($aReplace,"",$oPost->aObjListaRubricas));

  if ($oPost->lRepasse == 'true') {
  	$lRepasse = true;
  } else {
  	$lRepasse = false;
  }
  
  
  db_inicio_transacao();
  
  try {
	  $oPontoFolha->incluiRubricasAutomaticas( $oPost->sTipoPonto,
			                                       $oPost->iAnoUsu,
			                                       $oPost->iMesUsu,
			                                       $oPost->iMatric,
			                                       $oPost->iLotac,
			                                       db_getsession('DB_instit'),
			                                       $aObjListaRubrica,
			                                       $lRepasse,
			                                       $oPost->dtDataAdm);
	} catch ( Exception $eException ){
	  $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }

  db_fim_transacao($lErro);
  
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));

  echo $oJson->encode($aRetorno);  

  
      
  
} 
  
?>