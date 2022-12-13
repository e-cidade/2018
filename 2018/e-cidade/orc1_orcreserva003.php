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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_orcreserva_classe.php"));
require_once(modification("classes/db_orcreservager_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("libs/db_liborcamento.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clorcreserva    = new cl_orcreserva;
$clorcreservager = new cl_orcreservager;
$clorcdotacao    = new cl_orcdotacao;  //instancia dotação
$clorcreservaacordoitemdotacao = new cl_orcreservaacordoitemdotacao;

$db_botao = false;
$db_opcao = 33;
$op = 3;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
	$clorcreservaacordoitemdotacao->excluir(null, 'o84_orcreserva = ' . $o80_codres);
  $clorcreservager->excluir($o80_codres);
  $clorcreserva->excluir($o80_codres);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcreserva->sql_record($clorcreserva->sql_query_reservas($chavepesquisa));
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
<script>
  function dot() {
   //  document.form1.submit(); botao pesquisar dotação
  }
  function critica_form(){
         // cria imput com dados do botão 'inclui,altera,exclui
          var opcao= document.createElement("input");
	      opcao.setAttribute("type","hidden");
	      opcao.setAttribute("name","db_opcao");
	      opcao.setAttribute("value",document.form1.db_opcao.value);
 	      document.form1.appendChild(opcao);
           document.form1.submit();

   }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
	include("forms/db_frmorcreserva.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clorcreserva->erro_status=="0"){
    $clorcreserva->erro(true,false);
  }else{
    $clorcreserva->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
