<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(!isset($abas)){
  echo "<script>location.href='pro1_cgmcorreto004.php?db_opcao=3'</script>";
  exit;
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcgmcorreto = new cl_cgmcorreto;
$clcgmerrado  = new cl_cgmerrado;
$db_botao = false;

$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

  $sql1= "select * from cgmcorreto where z10_codigo = $z10_codigo";
  $result1= db_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if($linhas1 >0){
	db_fieldsmemory($result1,0);
	if($z10_proc=='t'){
		db_msgbox("Você não pode excluir um CGM já processado pela rotina Elimina Duplos");
	}else{
		db_inicio_transacao();
		$db_opcao = 3;
		$clcgmerrado->excluir($z10_codigo);
		$clcgmcorreto->excluir($z10_codigo);
		db_fim_transacao();
	}
}


}else if(isset($chavepesquisa)){
   //die($chavepesquisa);
	echo "
   <script>
	parent.iframe_cgmerrado.location.href='pro1_cgmerrado001.php?z11_codigo=$chavepesquisa';
	document.formaba.cgmerrado.disabled=false;
   </script>";
   $db_opcao = 3;
   $result = $clcgmcorreto->sql_record($clcgmcorreto->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?

	include("forms/db_frmcgmcorreto.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clcgmcorreto->erro_status=="0"){
    $clcgmcorreto->erro(true,false);
  }else{
    $clcgmcorreto->erro(true,false);
    echo "<script>parent.iframe_cgmcorreto.location.href='pro1_cgmcorreto003.php?abas=1';\n</script>";
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>