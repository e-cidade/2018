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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_numprebloqpag_classe.php");
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch ($oParam->exec) {

  case "getDadosNumpre": 
  
	  $oDaoArrecad = db_utils::getDao('arrecad');
	  $sCampos = "arrecad.k00_numpre, 
	              arrecad.k00_numpar, 
	              arrecad.k00_tipo,
	              arretipo.k00_descr, 
	              arrecad.k00_receit,
	              tabrec.k02_descr,
	              arrecad.k00_dtvenc,
	              arrecad.k00_dtoper";
	  
	  $sOrdem  = " arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit";
	  
	  $sWhere  = " arrecad.k00_numpre = {$oParam->iNumpre}          ";
    $sWhere .= " and not exists ( select 1                        "; 
    $sWhere .= "                     from numprebloqpag            "; 
    $sWhere .= "                    where arrecad.k00_numpre = ar22_numpre "; 
    $sWhere .= "                      and arrecad.k00_numpar = ar22_numpar)";
    	  
    $rsNumpre = $oDaoArrecad->sql_record($oDaoArrecad->sql_query(null,$sCampos,$sOrdem,$sWhere));
	  if ($rsNumpre) {
	    $aRegistros = db_utils::getColectionByRecord($rsNumpre,0,false,false,true);
	  } else {
	    $sMensagem  = "Nenhum dado retornado";
	    $iStatus    = 2;
	    $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
	  }
	  
	  $oRetorno = new stdClass();
	  $oRetorno->aRegistros = $aRegistros;
	  echo $oJson->encode($oRetorno);
	  
	break;
	
	case "getDadosNumpreBloqueado": 
  
    $oDaoArrecad = db_utils::getDao('arrecad');
    $sCampos = "arrecad.k00_numpre, 
                arrecad.k00_numpar, 
                arrecad.k00_tipo,
                arretipo.k00_descr, 
                arrecad.k00_receit,
                tabrec.k02_descr,
                arrecad.k00_dtvenc,
                arrecad.k00_dtoper";
    $sOrdem  = "arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit";
    
    $sWhere  = " arrecad.k00_numpre = {$oParam->iNumpre}          ";
    $sWhere .= " and exists ( select 1                        "; 
    $sWhere .= "                     from numprebloqpag            "; 
    $sWhere .= "                    where arrecad.k00_numpre = ar22_numpre "; 
    $sWhere .= "                      and arrecad.k00_numpar = ar22_numpar)";
         
    $rsNumpre = $oDaoArrecad->sql_record($oDaoArrecad->sql_query(null,$sCampos,$sOrdem,$sWhere));
    if ($rsNumpre) {
      $aRegistros = db_utils::getColectionByRecord($rsNumpre,0,false,false,true);
    } else {
      $sMensagem  = "Nenhum dado retornado";
      $iStatus    = 2;
      $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
    }
    
    $oRetorno = new stdClass();
    $oRetorno->aRegistros = $aRegistros;
    echo $oJson->encode($oRetorno);
    
  break;
	  
  case "incluirNumpre":
  	 $oNumprebloqpag = db_utils::getDao('numprebloqpag');
  	 
  	 $sqlerro   = false;
  	 $iStatus   = 1;
     $sMensagem = "Operaчуo realizada com Sucesso";
  	 
     db_inicio_transacao();
  	 foreach ($oParam->aDados as $oRegistros ) {
  	 	
  	 	 	$oNumprebloqpag->ar22_numpre = $oRegistros->numpre;
  	 	 	$oNumprebloqpag->ar22_numpar = $oRegistros->numpar;
  	 	 	$oNumprebloqpag->incluir(null);
  	 	 	if ($oNumprebloqpag->erro_status == "0") {
  	 	 		$sMensagem = "Erro ao bloquear dщbito!\n\n$oNumprebloqpag->erro_msg";
  	 	 		$iStatus   = 2;
  	 	 		$sqlerro   = true;
  	 	 	}
  	 }
  	 db_fim_transacao($sqlerro);
  	
  	$oRetorno = new stdClass();
  	$oRetorno->iStatus = $iStatus;
  	$oRetorno->sMensagem = urlencode($sMensagem);
    echo $oJson->encode($oRetorno);
    
  break;	  
  
  case "excluirNumpre":
  	$oNumprebloqpag = db_utils::getDao('numprebloqpag');
     
     $sqlerro   = false;
     $iStatus   = 1;
     $sMensagem = "Operaчуo realizada com Sucesso";
     
     db_inicio_transacao();
     
     foreach ($oParam->aDados as $oRegistros ) {
      
        $oNumprebloqpag->ar22_numpre = $oRegistros->numpre;
        $oNumprebloqpag->ar22_numpar = $oRegistros->numpar;
        
        $sWhere  = " ar22_numpre = {$oRegistros->numpre} ";
        $sWhere .= " and ar22_numpar = {$oRegistros->numpar}";
        
        $oNumprebloqpag->excluir(null, $sWhere);
        if ($oNumprebloqpag->erro_status == "0") {
          $sMensagem = "Erro ao desbloquear dщbito!\n\n$oNumprebloqpag->erro_msg";
          $iStatus   = 2;
          $sqlerro   = true;
        }
     }
     db_fim_transacao($sqlerro);
    
    $oRetorno = new stdClass();
    $oRetorno->iStatus = $iStatus;
    $oRetorno->sMensagem = urlencode($sMensagem);
    echo $oJson->encode($oRetorno);
  break;	
	  
}
?>