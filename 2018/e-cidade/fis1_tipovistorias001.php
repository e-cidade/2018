<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_tipovistorias_classe.php"));
include(modification("dbforms/db_funcoes.php"));

if(!isset($abas)){
  echo "<script>location.href='fis1_tipovistorias005.php'</script>";
  exit;
}
db_postmemory($HTTP_POST_VARS);
$cltipovistorias = new cl_tipovistorias;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
	$cltipovistorias->y77_instit = db_getsession('DB_instit');
  $cltipovistorias->incluir($y77_codtipo);
  db_fim_transacao();
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
	include(modification("forms/db_frmtipovistorias.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($cltipovistorias->erro_status=="0"){
    $cltipovistorias->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltipovistorias->erro_campo!=""){
      echo "<script> document.form1.".$cltipovistorias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipovistorias->erro_campo.".focus();</script>";
    };
  }else{
    $cltipovistorias->erro(true,false);
    echo "<script>parent.mo_camada('vistrec');</script>";
    echo "<script>parent.document.formaba.vistrec.disabled=false;</script>";
    echo "<script>parent.iframe_vistrec.location.href='fis1_tipovistoriasrec001.php?abas=1&y78_codtipo=".$cltipovistorias->y77_codtipo."';</script>";
    echo "<script>parent.iframe_tipovist.location.href='fis1_tipovistorias002.php?abas=1&chavepesquisa=".$cltipovistorias->y77_codtipo."';</script>";
  };
};
?>