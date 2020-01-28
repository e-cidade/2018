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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_itbi_classe.php");
include("classes/db_itbialt_classe.php");
include("classes/db_itbiavalia_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clitbi       = new cl_itbi;
$clitbialt    = new cl_itbialt;
$clitbiavalia = new cl_itbiavalia;
$db_opcao     = 22;
$db_botao     = false;
$sqlerro      = false;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();

  $sSqlItbiAntiga  = "select it14_dtliber, ";
  $sSqlItbiAntiga .= "       it14_dtvenc, ";
  $sSqlItbiAntiga .= "       it01_data ";
  $sSqlItbiAntiga .= "  from itbiavalia ";
  $sSqlItbiAntiga .= "       inner join itbi on itbi.it01_guia = itbiavalia.it14_guia  ";
  $sSqlItbiAntiga .= " where it14_guia = {$it01_guia}";
  $rsItbiAntiga    = db_query($sSqlItbiAntiga);
  
  if ( pg_num_rows($rsItbiAntiga) > 0 ) {

    $oItbiAntiga = db_utils::fieldsMemory($rsItbiAntiga,0);
    $clitbialt->it30_guia          = $it01_guia;
    $clitbialt->it30_usuario       = db_getsession('DB_id_usuario');
    $clitbialt->it30_dataalt       = date('Y-m-d',db_getsession('DB_datausu'));
    $clitbialt->it30_hora          = db_hora();
    $clitbialt->it30_dataliberacao = $oItbiAntiga->it14_dtliber;
    $clitbialt->it30_datavenc      = $oItbiAntiga->it14_dtvenc;
    $clitbialt->it30_dataitbi      = $oItbiAntiga->it01_data;
    $clitbialt->incluir(null); 
    $erro_msg = $clitbialt->erro_msg;
    if ($clitbialt->erro_status==0){

      db_msgbox($erro_msg);
      $sqlerro = true;

    }
  } 

  $db_opcao = 2;
  $db_botao = true;
  $guia = $it01_guia;
  $it01_data=$it01_data_ano."-".$it01_data_mes."-".$it01_data_dia;
  $clitbi->it01_data=$it01_data;
  $clitbi->alterar($guia);
  $erro_msg=$clitbi->erro_msg;
  
  if ($clitbi->erro_status==0){
  	$sqlerro=true;
  }
  
  if ($sqlerro==false){
  	
    $it14_dtvenc=$it14_dtvenc_ano."-".$it14_dtvenc_mes."-".$it14_dtvenc_dia;
    $clitbiavalia->it14_dtvenc=$it14_dtvenc;
    $it14_dtliber=$it14_dtliber_ano."-".$it14_dtliber_mes."-".$it14_dtliber_dia;
    $clitbiavalia->it14_dtliber=$it14_dtliber;
    $clitbiavalia->it14_guia=$guia;
    $clitbiavalia->alterar($guia);
    
    if ($clitbiavalia->erro_status==0){
    	$sqlerro=true;
    	$erro_msg=$clitbiavalia->erro_msg;
    }
  }
  
  db_fim_transacao($sqlerro);
  
} else if(isset($chavepesquisa)) {
	
   $db_opcao = 2;
   $db_botao = true;
   
   $result        = $clitbi->sql_record($clitbi->sql_query_file($chavepesquisa,'it01_guia,it01_data'));
   db_fieldsmemory($result,0);
   
   $result_avalia = $clitbiavalia->sql_record($clitbiavalia->sql_query_file($chavepesquisa,'it14_dtvenc,it14_dtliber'));
   db_fieldsmemory($result_avalia,0);    
}

if (isset($oGet->alteraguialib)) {
  if ($oGet->alteraguialib == 2) {
    echo "<script>parent.mo_camada('dados');</script> ";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<form name="form1" method="post" action="">
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td>
			<?
			  include("forms/db_frmitbilib.php");
		  ?>
		</td>
	  </tr>
	</table>
</form>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
	
  if ($clitbi->erro_status == "0" || $sqlerro == true) {
    db_msgbox($erro_msg);	
  } else {
  	db_msgbox($clitbi->erro_msg);
  	echo "<script>location.href='itb1_itbilib002.php';</script>";
  }
}

if(isset($db_opcao) && $db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>