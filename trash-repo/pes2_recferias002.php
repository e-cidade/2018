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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libsys.php");
include_once 'dbagata/classes/core/AgataAPI.class';
require ("model/documentoTemplate.model.php");
require_once("std/db_stdClass.php");

$oPost = db_utils::postMemory($_GET);


$sTipo = $oPost->tipo;
$iAno  = $oPost->ano;
if(strlen($oPost->mes)==1){
	$iMes  = '0'.$oPost->mes;	
} else {
	$iMes  = $oPost->mes;
} 
$instit = db_getsession("DB_instit");


ini_set("error_reporting","E_ALL & ~NOTICE");

$clagata = new cl_dbagata("pessoal/pes2_recferias002.agt");

$api = $clagata->api;

$sCaminhoSalvoSxw = "tmp/docSalvoSxw".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
$api->setOutputPath($sCaminhoSalvoSxw);



$api->setParameter('$anofolha' ,$oPost->ano);
$api->setParameter('$mesfolha' ,$oPost->mes);
$api->setParameter('$instit'   ,$instit);


$sWhere = "		 cadferia.r30_anousu     = {$iAno}   
				   and cadferia.r30_mesusu     = {$iMes}     
				   and rhpessoal.rh01_instit   = {$instit}     
				   and (    cadferia.r30_proc1 = '{$iAno}/{$iMes}'   
				         or cadferia.r30_proc2 = '{$iAno}/{$iMes}'           
				        ) ";


if ( $sTipo == "m" ) {

  if(isset($oPost->fre) && $oPost->fre != "") {
	   $sWhere .= " and r30_regist in ('".str_replace(",","','",$oPost->fre)."') ";
  }elseif((isset($oPost->rei) && $oPost->rei != "" ) && (isset($oPost->ref) && $oPost->ref != "")){
	   $sWhere .= " and r30_regist between '$oPost->rei' and '$oPost->ref' ";
	}else if(isset($oPost->rei) && $oPost->rei != ""){
	   $sWhere .= " and r30_regist >= '$oPost->rei' ";
	}else if(isset($oPost->ref) && $oPost->ref != ""){
	   $sWhere .= " and r30_regist <= '$oPost->ref' ";
  }		
	
	
} else if ( $sTipo == "l" ) {

  if(isset($oPost->flt) && $oPost->flt != "") {
	   $sWhere .= " and r70_estrut in ('".str_replace(",","','",$oPost->flt)."') ";
  }elseif((isset($oPost->lti) && $oPost->lti != "" ) && (isset($oPost->ltf) && $oPost->ltf != "")){
	   $sWhere .= " and r70_estrut between '$oPost->lti' and '$oPost->ltf' ";
	}else if(isset($oPost->lti) && $oPost->lti != ""){
	   $sWhere .= " and r70_estrut >= '$oPost->lti' ";
	}else if(isset($oPost->ltf) && $oPost->ltf != ""){
	   $sWhere .= " and r70_estrut <= '$oPost->ltf' ";
  }	
	
} else if ( $sTipo == "t" ) {
	
  if(isset($oPost->flc) && $oPost->flc != "" ) {
	   $sWhere .= " and rh55_estrut in ('".str_replace(",","','",$oPost->flc)."') ";
  }elseif((isset($oPost->lci) && $oPost->lci != "" ) && (isset($oPost->lcf) && $oPost->lcf != "")){
	   $sWhere .= " and rh55_estrut between '$oPost->lci' and '$oPost->lcf' ";
	}else if(isset($oPost->lci) && $oPost->lci != ""){
	   $sWhere .= " and rh55_estrut >= '$oPost->lci' ";
	}else if(isset($oPost->lcf) && $oPost->lcf != ""){
	   $sWhere .= " and rh55_estrut <= '$oPost->lcf' ";
	}
	
}
				        

$xml = $api->getReport();

$xml["Report"]["DataSet"]["Query"]["Where"] 	= $sWhere;

$api->setReport($xml);


try {
	$oRecModel = new documentoTemplate(1); 
	
}catch (Exception $oErro){
	$erro =  $oErro->getMessage();
	header("Location: db_erros.php?fechar=true&db_erro=$erro");
}
		
//ob_start();
//$ok      = $api->generateReport();
$ok 		 = $api->parseOpenOffice($oRecModel->getArquivoTemplate());
if($ok==true){
	$sNomeRelatorio   = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".pdf";
	
	$sComandoConverte = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);
	
	if (!$sComandoConverte) {
		db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gera PDF");		
	}else{
		db_redireciona($sNomeRelatorio);
	}
}else {
	db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gera relatório !!!");
}

?>