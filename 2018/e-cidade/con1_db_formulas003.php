<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_db_formulas_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoDb_formulas = new cl_db_formulas;
$db_botao    = false;
$db_opcao    = 33;
$sPosScripts = "";

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoDb_formulas->excluir($db148_sequencial);
  db_fim_transacao();

  $sPosScripts = 'alert("Excluído com sucesso.");' . "\n";

  if($oDaoDb_formulas->erro_status == '0') {
    $sPosScripts = 'alert("' . $oDaoDb_formulas->erro_msg . '");' . "\n";
  }

  if ($oDaoDb_formulas->erro_status != "0") {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoDb_formulas->sql_record( $oDaoDb_formulas->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "db148_titulo", true, 1, "db148_titulo", true);';

include("forms/db_frmdb_formulas.php");
?>
