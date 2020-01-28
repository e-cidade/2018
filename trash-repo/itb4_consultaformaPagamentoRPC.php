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
require_once("classes/db_itbitransacaoformapag_classe.php");
require_once("classes/db_itbi_classe.php");

$oPost  = db_utils::postMemory($_POST);

$oJson 					 = new services_json();
$clitbitransacaoformapag = new cl_itbitransacaoformapag();
$clitbi					 = new cl_itbi();


  if ($oPost->tipoPesquisa == "formasDisponiveis") {
  	
    $sWhere   	    	   = " 	   it25_itbitransacao = {$oPost->codtransacao} ";
    $sWhere             .= " and it25_ativo         = 't'                    ";
    if ( $oPost->tipoITBI == "urbano") {
      $sWhere  	    	  .= " and ( it27_tipo = 1 or it27_tipo = 3 )";
    } else if ($oPost->tipoITBI == "rural") {
      $sWhere  	    	  .= " and ( it27_tipo = 2 or it27_tipo = 3 )";
    }

	$rsConsultaFormasPgto  = $clitbitransacaoformapag->sql_record($clitbitransacaoformapag->sql_query(null,"*","it28_sequencial",$sWhere));    
   							  
    if ( $clitbitransacaoformapag->numrows > 0 ) {
      $aRetornaCampos = db_utils::getColectionByRecord($rsConsultaFormasPgto,false,false,true);
    } else {
      $sMensagem 	    = "Nenhuma forma de pagamento cadastrada!";
      $iStatus   	    = 2;
      $aRetornaCampos = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));
    } 
    
  } else if ($oPost->tipoPesquisa == "formasCadastradas") {

  	$rsConsultaFormasPgto = $clitbi->sql_record($clitbi->sql_query_pag($oPost->codguia,"*","it28_sequencial"));
  	
  	if ( $clitbi->numrows > 0 ) {
  	  $aRetornaCampos = db_utils::getColectionByRecord($rsConsultaFormasPgto,false,false,true);
  	} else {
      $sMensagem 	    = "Nenhuma forma de pagamento cadastrada!";
      $iStatus   	    = 2;
      $aRetornaCampos = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem));  		
  	}
  	
  } else if ($oPost->tipoPesquisa == "validaLiberacao") {


    $sMensagem  = "";
    $lValidacao = true;
    
    /*
     * Verificamos se a guia que estamos tentando liberar possui transmitente e adquirente cadastrados
     */
    $sSqlTransmitente = "select distinct 
                                it03_tipo 
                           from itbinome 
                          where it03_guia = {$oPost->iCodGuia} 
                            and it03_tipo in ('T','C')"; 
    $rsTransmitente   = db_query($sSqlTransmitente);
    
    if (pg_num_rows($rsTransmitente) < 2) {
      $sMensagem  = "Não é permitido envio de uma guia sem transmitentes e adquirentes cadastrados!";
      $lValidacao = false;
    }
    
    $aRetornaCampos = array("lValidacao"=>$lValidacao, "sMensagem"=>urlencode($sMensagem));
    
  }

  echo $oJson->encode($aRetornaCampos);
?>