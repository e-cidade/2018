<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_benstransf_classe.php");
include("classes/db_benstransfdiv_classe.php");
include("classes/db_benstransfdes_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_departdiv_classe.php");
include("classes/db_benstransfcodigo_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clbenstransfcodigo = new cl_benstransfcodigo();
$clbenstransf       = new cl_benstransf();
$clbenstransfdiv    = new cl_benstransfdiv();
$clbenstransfdes    = new cl_benstransfdes();
$cldb_usuarios      = new cl_db_usuarios();
$cldb_depart        = new cl_db_depart();
$cldepartdiv        = new cl_departdiv();
$cldb_depusu        = new cl_db_depusu();

$db_opcao = 33;
$db_botao = false;

if(isset($oPost->excluir)){
	$db_opcao = 33;
  $lSqlErro = false;
  
  db_inicio_transacao();
  
  
  $clbenstransfdiv->excluir(null," t31_codtran = {$oPost->t93_codtran}");
    
  if($clbenstransfdiv->erro_status==0){
    $lSqlErro = true;
  }
    
  $sMsgErro = $clbenstransfdiv->erro_msg;
  
  if ( !$lSqlErro ) {
  	
	  $clbenstransfcodigo->excluir($oPost->t93_codtran);
	  
	  if($clbenstransfcodigo->erro_status==0){
	    $lSqlErro = true;
	  }
	    
	  $sMsgErro = $clbenstransfcodigo->erro_msg;  
  
  }
  
  
  if ( !$lSqlErro ) {

	  $clbenstransfdes->excluir($oPost->t93_codtran);
	   
	    
	  if($clbenstransfdes->erro_status==0){
	    $lSqlErro = true;
	  }
	    
	  $sMsgErro = $clbenstransfdes->erro_msg;
    
  }
  
  
  if ( !$lSqlErro ) {
  	
	  $clbenstransf->excluir($oPost->t93_codtran);
	   
	  if($clbenstransf->erro_status==0){
	    $lSqlErro = true;
	  }
	    
	  $sMsgErro = $clbenstransf->erro_msg;
  
  }     

  db_fim_transacao($lSqlErro);
  
} else if ( isset($oGet->chavepesquisa) && trim($oGet->chavepesquisa) != "" ) {
	

	$sCampos = "t93_codtran,
	            t93_data,
	            t93_id_usuario,
	            nome,
	            t93_depart,
	            a.descrdepto as descrdepto,
	            t93_divisao as divorigem,
	            b.t30_descr as descrdivorigem,
	            t93_clabens as codclabens ,
	            t64_descr as descrclabens,
	            t64_class as estrutclabens,
	            t93_obs,
	            t94_depart,
	            db_depart.descrdepto as depto_destino,
	            t94_divisao as divdestino,
              departdiv.t30_descr as descrdivdestino ";
	
  $rsConsultaDestino = $clbenstransfdes->sql_record($clbenstransfdes->sql_query_lote($oGet->chavepesquisa,null,$sCampos));
  
  if($clbenstransfdes->numrows > 0){
  	
    $oDadosTransf = db_utils::fieldsMemory($rsConsultaDestino,0);
    
    $divOrigem       = $oDadosTransf->divorigem;
    $divDestino      = $oDadosTransf->divdestino;
    $t93_codtran     = $oDadosTransf->t93_codtran;
    $aData           = explode("-",$oDadosTransf->t93_data);
    $t93_data_ano    = $aData[0];
    $t93_data_mes    = $aData[1];
    $t93_data_dia    = $aData[2];
    $t93_id_usuario  = $oDadosTransf->t93_id_usuario;
    $nome            = $oDadosTransf->nome;
    $codclabens      = $oDadosTransf->codclabens;
    $descrclabens    = $oDadosTransf->descrclabens;
    $descrdivorigem  = $oDadosTransf->descrdivorigem;
    $estrutclabens   = $oDadosTransf->estrutclabens;
    $t93_obs         = $oDadosTransf->t93_obs;
    $descrdivdestino = $oDadosTransf->descrdivdestino;
    $t93_depart      = $oDadosTransf->t93_depart;
    $descrdepto      = $oDadosTransf->descrdepto;
    $t94_depart      = $oDadosTransf->t94_depart;
    $depto_destino   = $oDadosTransf->depto_destino;
    $db_opcao        = 3;
    $db_botao        = true;
	
  }
  
	
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC >
	   <?
	     include("forms/db_frmbenstransflote.php");
	   ?>
</body>
</html>
<? 

  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

if(isset($oPost->excluir)){

  if ( $lSqlErro ){
  	
  	db_msgbox($sMsgErro);
  	
    if($clbenstransf->erro_campo!=""){
      echo "<script> document.form1.".$clbenstransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransf->erro_campo.".focus();</script>";
    }
    
  } else {
  	$clbenstransf->erro(true,true);
  }
  
} 
if ( $db_opcao == 33 ) {
	echo "<script>               ";
	echo "  js_pesquisaTransf(); "; 
	echo "</script>              ";
}
?>