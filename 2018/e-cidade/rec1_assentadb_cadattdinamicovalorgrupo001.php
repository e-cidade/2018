<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_assentadb_cadattdinamicovalorgrupo_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoAssentadb_cadattdinamicovalorgrupo = new cl_assentadb_cadattdinamicovalorgrupo;
$db_opcao    = 1;
$db_botao    = true;
$sPosScripts = "";

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoAssentadb_cadattdinamicovalorgrupo->incluir($h80_assenta, $h80_db_cadattdinamicovalorgrupo);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoAssentadb_cadattdinamicovalorgrupo->erro_msg . '");' . "\n";

  if ($oDaoAssentadb_cadattdinamicovalorgrupo->erro_status == '0') {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoAssentadb_cadattdinamicovalorgrupo->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoAssentadb_cadattdinamicovalorgrupo->erro_campo}.classList.add('form-error');\n";
      $sPosScripts .= "document.form1.{$oDaoAssentadb_cadattdinamicovalorgrupo->erro_campo}.focus();\n";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}

$sPosScripts .=  'js_tabulacaoforms("form1", "h80_assenta", true, 1, "h80_assenta", true);';

include("forms/db_frmassentadb_cadattdinamicovalorgrupo.php");
?>
