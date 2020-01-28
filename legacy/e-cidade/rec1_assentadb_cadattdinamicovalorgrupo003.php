<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_assentadb_cadattdinamicovalorgrupo_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoAssentadb_cadattdinamicovalorgrupo = new cl_assentadb_cadattdinamicovalorgrupo;
$db_botao    = false;
$db_opcao    = 33;
$sPosScripts = "";

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoAssentadb_cadattdinamicovalorgrupo->excluir($h80_assenta, $h80_db_cadattdinamicovalorgrupo);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoAssentadb_cadattdinamicovalorgrupo->erro_msg . '");' . "\n";

  if ($oDaoAssentadb_cadattdinamicovalorgrupo->erro_status != "0") {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoAssentadb_cadattdinamicovalorgrupo->sql_record( $oDaoAssentadb_cadattdinamicovalorgrupo->sql_query($chavepesquisa, $chavepesquisa1) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "h80_assenta", true, 1, "h80_assenta", true);';

include("forms/db_frmassentadb_cadattdinamicovalorgrupo.php");
?>
