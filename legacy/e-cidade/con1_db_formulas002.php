<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_db_formulas_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoDb_formulas = new cl_db_formulas;
$db_opcao    = 22;
$db_botao    = false;
$sPosScripts = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao      = 2;
  $oDaoDb_formulas->db148_nome    = trim($db148_nome);
  $oDaoDb_formulas->db148_formula = pg_escape_string(str_replace(array("\r\n", "\\"), array("\n", ""), $db148_formula));
  $oDaoDb_formulas->alterar($db148_sequencial);
  db_fim_transacao();

  $sPosScripts = 'alert("Alterado com sucesso.");' . "\n";

  if($oDaoDb_formulas->erro_status == '0') {
    $sPosScripts = 'alert("' . $oDaoDb_formulas->erro_msg . '");' . "\n";
  }

  if ($oDaoDb_formulas->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoDb_formulas->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoDb_formulas->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoDb_formulas->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoDb_formulas->sql_record( $oDaoDb_formulas->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "db148_titulo", true, 1, "db148_titulo", true);';

include(modification("forms/db_frmdb_formulas.php"));
