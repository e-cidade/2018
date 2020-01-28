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
$clbenstransfdes    = new cl_benstransfdes();
$cldb_usuarios      = new cl_db_usuarios();
$cldb_depart        = new cl_db_depart();
$cldepartdiv        = new cl_departdiv();
$cldb_depusu        = new cl_db_depusu();

$db_opcao = 1;
$db_botao = true;

if(isset($oPost->incluir)){
	
  $lSqlErro = false;
  
  db_inicio_transacao();
  
  if($oPost->t93_data_dia!="" && $oPost->t93_data_mes!="" && $oPost->t93_data_ano!=""){
    
  	$dtData = $oPost->t93_data_ano."-".$oPost->t93_data_mes."-".$oPost->t93_data_dia;
    
    if( $dtData < date("Y-m-d") ){
      $lSqlErro = true;
      $sMsgErro = _M("patrimonial.patrimonio.db_frmbenstransflote.data_invalida");
    }
    
  }
    
  if( !$lSqlErro ){
  	
  	if ( isset($oPost->codclabens) && trim($oPost->codclabens) != "" ) {
  	  $clbenstransf->t93_clabens  = $oPost->codclabens;
  	} 
  	
  	if ( isset($oPost->divOrigem) && trim($oPost->divOrigem) != "" &&  $oPost->divOrigem != "0" ) {
  	  $clbenstransf->t93_divisao  = $oPost->divOrigem;
  	}
  	
  	$clbenstransf->t93_codtran    = $oPost->t93_codtran;
  	$clbenstransf->t93_data       = $dtData;
  	$clbenstransf->t93_depart     = $oPost->t93_depart;
  	$clbenstransf->t93_id_usuario = $oPost->t93_id_usuario;
  	$clbenstransf->t93_instit     = db_getsession('DB_instit');
  	$clbenstransf->t93_obs        = $oPost->t93_obs;
  	
    $clbenstransf->incluir(null);
    
    if($clbenstransf->erro_status==0){
      $lSqlErro = true;
    }
    
    $sMsgErro = $clbenstransf->erro_msg;
     
  }
  
  if( !$lSqlErro ){
  	
    if ( isset($oPost->divDestino) && trim($oPost->divDestino) != "" && $oPost->divDestino != "0" ) {
      $clbenstransfdes->t94_divisao  = $oPost->divDestino;
    }  	
  	
  	$clbenstransfdes->t94_codtran = $clbenstransf->t93_codtran;
  	$clbenstransfdes->t94_depart  = $oPost->t94_depart;
  	
    $clbenstransfdes->incluir($clbenstransf->t93_codtran,$oPost->t94_depart);
    
    if($clbenstransfdes->erro_status==0){
      $lSqlErro = true;
    }
    
    $sMsgErro = $clbenstransfdes->erro_msg;
    
  }

  db_fim_transacao($lSqlErro);
  
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
<body bgcolor=#CCCCCC>
	   <?
	     include("forms/db_frmbenstransflote.php");
	   ?>
</body>
</html>
<?
if(isset($oPost->incluir)){

	db_msgbox($sMsgErro);
  
  if ( $lSqlErro ){
  	
    if($clbenstransf->erro_campo!=""){
      echo "<script> document.form1.".$clbenstransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransf->erro_campo.".focus();</script>";
    }
    
  }else{
  	
    echo " <script>                                                                                                                  ";
    echo "                                                                                                                           ";
    echo "   var sQuery  = 'iCodTransf={$clbenstransf->t93_codtran}';                                                                ";
    echo "   parent.iframe_benstransfcodigo.location.href='pat1_benstransfcodigolote001.php?'+sQuery;                                "; 
    echo "   parent.document.formaba.benstransfcodigo.disabled = false;                                                              ";   
    echo "   parent.mo_camada('benstransfcodigo');                                                                                   ";      
    echo "   parent.iframe_benstransf.location.href='pat1_benstransflote012.php?chavepesquisa={$clbenstransf->t93_codtran}';         ";
    echo "                                                                                                                           ";
    echo " </script>                                                                                                                 ";                                                                                                                  
    
  }
  
}
?>