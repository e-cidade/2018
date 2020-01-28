<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_descartemedicamento_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoDescartemedicamento = new cl_descartemedicamento;
$db_opcao                = 1;
$db_opcaomedicamento     = 1;
$db_botao                = true;
$sPosScripts             = "";
$oPost                   = db_utils::postMemory($_POST);
$sMensagem               = '';
if (isset($incluir)) {

  try {

    db_inicio_transacao();

    $oMedicamento = new Medicamento($oPost->sd107_medicamento);
    if (empty($oMedicamento)) {
     throw new Exception ("Medicamento não Cadastrado no sistema");
    }

    $oDaoDescartemedicamento->sd107_data            = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoDescartemedicamento->sd107_hora            = db_hora();
    $oDaoDescartemedicamento->sd107_usuario         = db_getsession('DB_id_usuario');
    $oDaoDescartemedicamento->sd107_unidadesaida    = $oMedicamento->getUnidade()->getCodigo();
    $oDaoDescartemedicamento->sd107_quantidadetotal = $oMedicamento->getQuantidade();
    $oDaoDescartemedicamento->sd107_db_depart       = db_getsession("DB_coddepto");

    $oDaoDescartemedicamento->incluir($sd107_sequencial);
    if ($oDaoDescartemedicamento->erro_status == 0) {
      throw new BusinessException($oDaoDescartemedicamento->erro_msg);
    }
    $sMensagem  = $oDaoDescartemedicamento->erro_msg;
    db_fim_transacao(false);
  } catch (Exception $oErro) {

    $sMensagem = $oErro->getMessage();
    db_fim_transacao(true);
  }
  $sPosScripts .= 'alert("' .$sMensagem . '");' . "\n";
  if ($oDaoDescartemedicamento->erro_status == '0') {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoDescartemedicamento->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoDescartemedicamento->erro_campo}.classList.add('form-error');\n";
      $sPosScripts .= "document.form1.{$oDaoDescartemedicamento->erro_campo}.focus();\n";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}

$sPosScripts .=  'js_tabulacaoforms("form1", "sd107_medicamento", true, 1, "sd107_medicamento", true);';

include("forms/db_frmdescartemedicamento.php");