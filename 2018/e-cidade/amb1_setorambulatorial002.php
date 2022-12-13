<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_setorambulatorial_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoSetorambulatorial = new cl_setorambulatorial;
$db_opcao    = 22;
$db_botao    = false;
$sPosScripts = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoSetorambulatorial->alterar($sd91_codigo);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoSetorambulatorial->erro_msg . '");' . "\n";

  if ($oDaoSetorambulatorial->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoSetorambulatorial->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoSetorambulatorial->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoSetorambulatorial->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $oDaoMovimentacaoProntuario = new cl_movimentacaoprontuario;
  $sWhere                     = " sd102_setorambulatorial = {$chavepesquisa} ";
  $sQuery                     = $oDaoMovimentacaoProntuario->sql_query_file(null, "1", null, $sWhere);
  $rsMovimentoProntuario      = db_query($sQuery);

  $db_opcao = 22;
  if( $rsMovimentoProntuario && pg_num_rows($rsMovimentoProntuario) > 0 ) {
    $sPosScripts .= 'alert("Setor não pode ser alterado pois possui vínculo com FAA.");' . "\n";
  } else {

    $db_opcao = 2;
    $db_botao = true;
    $result   = $oDaoSetorambulatorial->sql_record( $oDaoSetorambulatorial->sql_query($chavepesquisa) );
    db_fieldsmemory($result, 0);
  }
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "sd91_unidades", true, 1, "sd91_unidades", true);';

include("forms/db_frmsetorambulatorial.php");
?>
