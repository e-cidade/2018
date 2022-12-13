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
include(modification("classes/db_db_sysregrasacessocanc_classe.php"));
include(modification("classes/db_db_sysregrasacesso_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_sysregrasacessocanc = new cl_db_sysregrasacessocanc;
$cldb_sysregrasacesso = new cl_db_sysregrasacesso;
$db_opcao = 1;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$cldb_sysregrasacessocanc->db49_idacesso = $db49_idacesso;
$cldb_sysregrasacessocanc->db49_id_usuario = $db49_id_usuario;
$cldb_sysregrasacessocanc->db49_dtcancela = $db49_dtcancela;
$cldb_sysregrasacessocanc->db49_observ = $db49_observ;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_sysregrasacessocanc->incluir($db49_idacesso);
    $erro_msg = $cldb_sysregrasacessocanc->erro_msg;
    if($cldb_sysregrasacessocanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_sysregrasacessocanc->alterar($db49_idacesso);
    $erro_msg = $cldb_sysregrasacessocanc->erro_msg;
    if($cldb_sysregrasacessocanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_sysregrasacessocanc->excluir($db49_idacesso);
    $erro_msg = $cldb_sysregrasacessocanc->erro_msg;
    if($cldb_sysregrasacessocanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
  unset($db49_observ);

}

$result = $cldb_sysregrasacessocanc->sql_record($cldb_sysregrasacessocanc->sql_query($db49_idacesso));
if($result!=false && $cldb_sysregrasacessocanc->numrows>0){
  db_fieldsmemory($result,0);
  $db_opcao = 2;
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
<center>

<table width="795" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<?
	include(modification("forms/db_frmdb_sysregrasacessocanc.php"));
	?>
	</td>
  </tr>
</table>
</center>

</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$cldb_sysregrasacessocanc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cldb_sysregrasacessocanc->erro_campo.".focus();</script>";
    }
}
?>
