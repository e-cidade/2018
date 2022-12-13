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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_registroprecoparam_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clregistroprecoparam = new cl_registroprecoparam;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {

   db_inicio_transacao();
   $result = $clregistroprecoparam->sql_record($clregistroprecoparam->sql_query(db_getsession("DB_instit")));
   if($clregistroprecoparam->numrows == 0) {

     $clregistroprecoparam->pc08_instit = db_getsession("DB_instit");
     $clregistroprecoparam->incluir(db_getsession("DB_instit"));
   } else {

     $clregistroprecoparam->pc08_instit = db_getsession("DB_instit");
     $clregistroprecoparam->alterar(db_getsession("DB_instit"));
   }
   db_fim_transacao();
}
$db_opcao = 2;

$sWhere = " pc08_instit = " .db_getsession("DB_instit");
$result = $clregistroprecoparam->sql_record($clregistroprecoparam->sql_query(null, "*", null, $sWhere));
if($result!=false && $clregistroprecoparam->numrows>0){
  db_fieldsmemory($result,0);
}
$db_botao = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
    <?
    include(modification("forms/db_frmregistroprecoparam.php"));
    ?>
    </center>
	</td>
 </tr>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clregistroprecoparam->erro_status=="0"){
    $clregistroprecoparam->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clregistroprecoparam->erro_campo!=""){
      echo "<script> document.form1.".$clregistroprecoparam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clregistroprecoparam->erro_campo.".focus();</script>";
    }
  }else{
    $clregistroprecoparam->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>