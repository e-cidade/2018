<?php
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

$iInstituicaoSessao = db_getsession('DB_instit');
try {

  $sMovimentos = !isset($_POST['movimentos']) ?: $_POST['movimentos'];
  if (!$sMovimentos) {
    throw new ParameterException('Não foram informados parâmetros.');
  }
  $aMovimentos = JSON::create()->parse(str_replace("\\", "", $_POST['movimentos']));
  $oRelatorio = new RelatorioNotasPendentes($aMovimentos, InstituicaoRepository::getInstituicaoByCodigo($iInstituicaoSessao));
  $oRelatorio->emitir();

} catch (Exception $oErro) {

  db_redireciona("db_erros.php?db_erro=" . $oErro->getMessage());
  exit;
}