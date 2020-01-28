<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhfundamentacaolegal_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoRhfundamentacaolegal = new cl_rhfundamentacaolegal;
$db_opcao    = 22;
$db_botao    = false;
$sPosScripts = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoRhfundamentacaolegal->rh137_instituicao = db_getsession("DB_instit");
  $oDaoRhfundamentacaolegal->alterar($rh137_sequencial);

  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoRhfundamentacaolegal->erro_msg . '");' . "\n";

  if ($oDaoRhfundamentacaolegal->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoRhfundamentacaolegal->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoRhfundamentacaolegal->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoRhfundamentacaolegal->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoRhfundamentacaolegal->sql_record( $oDaoRhfundamentacaolegal->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh137_tipodocumentacao", true, 1, "rh137_tipodocumentacao", true);';

include("forms/db_frmrhfundamentacaolegal.php");