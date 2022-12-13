<?php
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "libs/db_liborcamento.php";
require_once "libs/db_libcontabilidade.php";
require_once "dbforms/db_funcoes.php";
require_once "fpdf151/PDFDocument.php";
require_once "fpdf151/assinatura.php";

$oGet = db_utils::postMemory($_GET);

try {

  if (empty($oGet->periodo)) {
    throw new Exception("Periodo não informado.");
  }

  $oInstituicao = InstituicaoRepository::getInstituicaoPrefeitura();

  $oRelatorio = new AnexoXIIDemonstrativoDasDespesasComSaude(db_getsession("DB_anousu"), AnexoXIIDemonstrativoDasDespesasComSaude::CODIGO_RELATORIO, $oGet->periodo );
  $oRelatorio->setInstituicoes($oInstituicao->getCodigo());

  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona("db_erros.php?db_erro=".$e->getMessage());
}
