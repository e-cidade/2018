<?php

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta_plugin.php";
require_once "libs/db_sessoes.php";
require_once "dbforms/db_funcoes.php";
require_once "fpdf151/PDFDocument.php";

$oPdf = new PDFDocument();
$oPdf->open();
$oPdf->addPage();

$oPlugin = new Plugin('', "CadastroFornecedorMovimento");
$aConfig = PluginService::getPluginConfig($oPlugin);

$oAssinatura = new cl_assinatura();
$txtAssinatura = $oAssinatura->assinatura($aConfig["codigo_assinatura"]);
$oPdf->multiCell($oPdf->getAvailWidth(), 4, $txtAssinatura, 0, 'C');
$oPdf->showPDF();

 ?>