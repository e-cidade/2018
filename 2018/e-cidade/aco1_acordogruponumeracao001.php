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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_acordogruponumeracao_classe.php");
require_once("classes/db_acordogrupo_classe.php");
require_once("classes/db_acordo_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clacordogruponumeracao = new cl_acordogruponumeracao;
$clacordogrupo          = new cl_acordogrupo;
$clacordo               = new cl_acordo;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

$sSqlAcordoGrupo  = $clacordogrupo->sql_query($ac02_sequencial);
$rsSqlAcordoGrupo = $clacordogrupo->sql_record($sSqlAcordoGrupo);
if ($clacordogrupo->numrows > 0) {

  $oAcordoGrupo   = db_utils::fieldsMemory($rsSqlAcordoGrupo,0);
  $ac02_descricao = $oAcordoGrupo->ac02_descricao;
}

if (isset($incluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clacordogruponumeracao->ac03_acordogrupo = $ac02_sequencial;
    $clacordogruponumeracao->ac03_anousu      = $ac03_anousu;
    $clacordogruponumeracao->ac03_numero      = $ac03_numero;
    $clacordogruponumeracao->ac03_instit      = db_getsession('DB_instit');
    $clacordogruponumeracao->incluir(null);
    $erro_msg = $clacordogruponumeracao->erro_msg;
    if ($clacordogruponumeracao->erro_status == 0) {
      $sqlerro=true;
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {

	$iInstit     = db_getsession('DB_instit');
  $sCampos     = " acordo.ac16_acordogrupo, acordo.ac16_anousu, acordo.ac16_instit, acordo.ac16_numero    ";
  $sWhere      = " acordo.ac16_acordogrupo = {$ac02_sequencial} and acordo.ac16_anousu = {$ac03_anousu}   ";
  $sWhere     .= " and acordo.ac16_instit  = {$iInstit}         and acordo.ac16_numero::integer > {$ac03_numero} ";
	$sSqlAcordo  = $clacordo->sql_query(null, $sCampos, null,$sWhere);
	$rsSqlAcordo = $clacordo->sql_record($sSqlAcordo);
	if ($clacordo->numrows > 0) {

    $sqlerro   = true;
    $erro_msg  = "Usuário:\\n";
    $erro_msg .= "  Existem contratos com númeração maior que a informada!\\n";
    $erro_msg .= "  Alteração Cancelada!\\n";
	}

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clacordogruponumeracao->ac03_acordogrupo = $ac02_sequencial;
    $clacordogruponumeracao->ac03_anousu      = $ac03_anousu;
    $clacordogruponumeracao->ac03_numero      = $ac03_numero;
    $clacordogruponumeracao->alterar($ac03_sequencial);
    $erro_msg = $clacordogruponumeracao->erro_msg;
    if ($clacordogruponumeracao->erro_status == 0) {
      $sqlerro=true;
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clacordogruponumeracao->excluir($ac03_sequencial);
    $erro_msg = $clacordogruponumeracao->erro_msg;
    if ($clacordogruponumeracao->erro_status == 0) {
      $sqlerro=true;
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($opcao)) {

  $result = $clacordogruponumeracao->sql_record($clacordogruponumeracao->sql_query($ac03_sequencial));
  if ($result != false && $clacordogruponumeracao->numrows > 0) {
    db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table border="0" align="center" cellspacing="0" cellpadding="0" width="530">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC">
    <center>
		  <?
		    include("forms/db_frmacordogruponumeracao.php");
		  ?>
    </center>
  </td>
  </tr>
</table>
</body>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {

  db_msgbox($erro_msg);
  if ($clacordogruponumeracao->erro_campo != "") {

    echo "<script> document.form1.".$clacordogruponumeracao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clacordogruponumeracao->erro_campo.".focus();</script>";
  }
}
?>
</html>