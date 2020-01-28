<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_descartemedicamento_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoDescartemedicamento = new cl_descartemedicamento;
$db_botao                = false;
$db_opcao                = 33;
$db_opcaomedicamento     = 3;
$sPosScripts             = "";

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoDescartemedicamento->excluir($sd107_sequencial);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoDescartemedicamento->erro_msg . '");' . "\n";

  if ($oDaoDescartemedicamento->erro_status != "0") {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoDescartemedicamento->sql_record( $oDaoDescartemedicamento->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "sd107_medicamento", true, 1, "sd107_medicamento", true);';

include("forms/db_frmdescartemedicamento.php");