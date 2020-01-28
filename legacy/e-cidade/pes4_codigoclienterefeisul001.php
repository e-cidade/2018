<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_codigoclienterefeisul_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oDaoCodigoclienterefeisul = new cl_codigoclienterefeisul;
$db_opcao    = 22;
$db_botao    = false;
$sPosScripts = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $sWhere       = "rh171_instit = ".db_getsession("DB_instit");
  $sSqlRefeisul = $oDaoCodigoclienterefeisul->sql_query(null, "*", null, $sWhere); 
  $result       = $oDaoCodigoclienterefeisul->sql_record( $sSqlRefeisul);

  $oDaoCodigoclienterefeisul->rh171_instit = db_getsession("DB_instit");
  if ($result == false || $oDaoCodigoclienterefeisul->numrows == 0) {
    $oDaoCodigoclienterefeisul->incluir($rh171_sequencial);
  } else {
    $oDaoCodigoclienterefeisul->alterar($rh171_sequencial);
  }
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoCodigoclienterefeisul->erro_msg . '");' . "\n";

  if ($oDaoCodigoclienterefeisul->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoCodigoclienterefeisul->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoCodigoclienterefeisul->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoCodigoclienterefeisul->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}

$db_opcao = 2;
$db_botao = true;
$sSql     =   $oDaoCodigoclienterefeisul->sql_query(null, "*", null, "rh171_instit = ".db_getsession("DB_instit")); 
$result   = $oDaoCodigoclienterefeisul->sql_record($sSql);

if ($result != false && $oDaoCodigoclienterefeisul->numrows > 0) {
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh171_instit", true, 1, "rh171_instit", true);';

include(modification("forms/db_frmcodigoclienterefeisul.php"));
?>
