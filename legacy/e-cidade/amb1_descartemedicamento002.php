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
$db_opcao                = 22;
$db_opcaomedicamento     = 3;
$db_botao                = false;
$sPosScripts             = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoDescartemedicamento->sd107_data    = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoDescartemedicamento->sd107_hora    = db_hora();
  $oDaoDescartemedicamento->sd107_usuario = db_getsession('DB_id_usuario');
  $oDaoDescartemedicamento->alterar($sd107_sequencial);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoDescartemedicamento->erro_msg . '");' . "\n";

  if ($oDaoDescartemedicamento->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoDescartemedicamento->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoDescartemedicamento->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoDescartemedicamento->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoDescartemedicamento->sql_record( $oDaoDescartemedicamento->sql_query_dados_descarte($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "sd107_medicamento", true, 1, "sd107_medicamento", true);';

include("forms/db_frmdescartemedicamento.php");
