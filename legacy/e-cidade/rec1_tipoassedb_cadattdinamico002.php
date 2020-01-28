<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tipoassedb_cadattdinamico_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico;
$db_opcao    = 22;
$db_botao    = false;
$sPosScripts = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoTipoassedb_cadattdinamico->alterar($h79_db_cadattdinamico, $h79_tipoasse);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoTipoassedb_cadattdinamico->erro_msg . '");' . "\n";

  if ($oDaoTipoassedb_cadattdinamico->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoTipoassedb_cadattdinamico->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoTipoassedb_cadattdinamico->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoTipoassedb_cadattdinamico->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoTipoassedb_cadattdinamico->sql_record( $oDaoTipoassedb_cadattdinamico->sql_query($chavepesquisa, $chavepesquisa1) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "h79_tipoasse", true, 1, "h79_tipoasse", true);';

include("forms/db_frmtipoassedb_cadattdinamico.php");
?>
