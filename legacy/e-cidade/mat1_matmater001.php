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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matmater_classe.php");
require_once("classes/db_transmater_classe.php");
require_once("classes/db_matmaterunisai_classe.php");
require_once("classes/db_matmatermaterialestoquegrupo_classe.php");
require_once("classes/db_db_almox_classe.php");

db_postmemory($HTTP_POST_VARS);

$clmatmater       = new cl_matmater;
$cltransmater     = new cl_transmater;
$clmatmaterunisai = new cl_matmaterunisai;
$cldb_almox       = new cl_db_almox;
$clmatmatermaterialestoquegrupo = new cl_matmatermaterialestoquegrupo;

$db_opcao = 1;
$sqlerro=false;
$db_botao = true;
if(isset($incluir)){

  db_inicio_transacao();

  $clmatmater->m60_ativo = $m60_ativo;
  $clmatmater->m60_descr = $m60_descr;
  $clmatmater->m60_codmatunid = $m60_codmatunid;
  $clmatmater->m60_quantent = $m60_quantent;
  $clmatmater->m60_codant = $m60_codant;

  $clmatmater->incluir($m60_codmater);
  $codigo=$clmatmater->m60_codmater;
  $erro_msg=$clmatmater->erro_msg;
  if ($clmatmater->erro_status==0){
     $sqlerro=true;
  }
  $clmatmaterunisai->incluir($codigo,$m62_codmatunid);
  if ($clmatmaterunisai->erro_status==0){
     $sqlerro=true;
     $erro_msg=$clmatmaterunisai->erro_msg;
  }

  $clmatmatermaterialestoquegrupo->m68_matmater = $codigo;
  $clmatmatermaterialestoquegrupo->m68_materialestoquegrupo = $m65_sequencial;
  $clmatmatermaterialestoquegrupo->incluir(null);
  if ($clmatmatermaterialestoquegrupo->erro_status==0){

     $sqlerro=true;
     $erro_msg = $clmatmatermaterialestoquegrupo->erro_msg;
  }
  db_fim_transacao($sqlerro);
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
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmmatmater.php");
	?>
    </center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){

  if($sqlerro == true){
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clmatmater->erro_campo!=""){
      echo "<script> document.form1.".$clmatmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatmater->erro_campo.".focus();</script>";
    }
    if($clmatmatermaterialestoquegrupo->erro_campo!=""){
      echo "<script> document.form1.m65_sequencial.style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.m65_sequencial.focus();</script>";
    }
  }else{

  	db_msgbox($erro_msg);
// Verifica departamento logado se eh deposito
    $res_db_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"*",null,"m91_depto = ".db_getsession("DB_coddepto")));
    if ($cldb_almox->numrows > 0){
         $flag_almox = "true";
    } else {
         $flag_almox = "false";
    }

    echo "<script>
               parent.iframe_transmater.location.href='mat1_transmateralt001.php?m60_codmater=".@$codigo."';\n
               parent.iframe_matmater.location.href='mat1_matmater002.php?chavepesquisa=".@$codigo."';\n
               parent.iframe_matmaterestoque.location.href='mat1_matmaterestoque001.php?m64_matmater=".@$codigo."&flag_almox=".$flag_almox."';\n
               parent.mo_camada('transmater');
               parent.document.formaba.transmater.disabled      = false;\n
               parent.document.formaba.matmaterestoque.disabled = false;\n
	 </script>";
  }
}
?>