<?
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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_debcontapedido_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldebcontapedido = new cl_debcontapedido;

$oGet           = new _db_fields();
$oGet           = db_utils::postMemory($HTTP_GET_VARS);

if ( isset( $oGet->sTipo ) ) {
  $tipo                              = $oGet->sTipo;
  $cldebcontapedido->pagina_retorno .= '?sTipo='.$tipo;  
}
if ( !isset( $chavepesquisa ) && !isset( $d63_codigo ) ) {
	echo "<script>parent.document.formaba.debito.disabled = true;</script>";
}
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $cldebcontapedido->alterar($d63_codigo);
  db_fim_transacao();  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cldebcontapedido->sql_record($cldebcontapedido->sql_query_info($chavepesquisa));
   if ($cldebcontapedido->numrows>0){ 
   db_fieldsmemory($result,0);
   if ($d70_numcgm!=""){
   	$tipo="CGM";
   	$codtipo=$d70_numcgm;
   }else if ($d68_matric!=""){
   	$tipo="MATRIC";
   	$codtipo=$d68_matric;
   }else if ($d69_inscr!=""){
   	$tipo="INSCR";
   	$codtipo=$d69_inscr;
   }
   $nome_banco=$nomebco;
   echo "<script>
               parent.iframe_debito.location.href='cai4_debcontapeddeb001.php?tipo=$tipo&codtipo=$codtipo&codigo=$chavepesquisa';\n
               parent.document.formaba.debito.disabled = false;\n
	 </script>";
   $db_botao = true;
   }else{
   	
   	echo "<script>alert('Inconsistencia de Informações!!\nContate Suporte!!');location.href='cai4_debcontapedaba002.php?tipo=$tipo';</script>";
   	exit;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdebcontapedido.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($cldebcontapedido->erro_status=="0"){
    $cldebcontapedido->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldebcontapedido->erro_campo!=""){
      echo "<script> document.form1.".$cldebcontapedido->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldebcontapedido->erro_campo.".focus();</script>";
    };
  }else{
    $cldebcontapedido->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>