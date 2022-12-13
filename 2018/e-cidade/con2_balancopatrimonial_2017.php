<?php

require_once(modification("fpdf151/assinatura.php"));
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
include_once modification("libs/db_sessoes.php");
include_once modification("libs/db_usuariosonline.php");
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_app.utils.php'));
require_once(modification("libs/db_libtxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libpostgres.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("fpdf151/PDFDocument.php"));

$oGet = db_utils::postMemory($_GET);

$aQuadros = array();
try {

    if (empty($oGet->periodo)) {
        throw new Exception("Período não informado.");
    }

    if (empty($oGet->db_selinstit)) {
        throw new Exception("Instituição não informada.");
    }

    $lExibirExercicioAnterior = isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior === "true";

    if ($oGet->lQuadroPrincipal === "true") {
        $aQuadros[] = BalancoPatrimonialDCASP2017::QUADRO_PRINCIPAL;
    }

    if ($oGet->lQuadroAtivoPassado === "true") {
        $aQuadros[] = BalancoPatrimonialDCASP2017::QUADRO_ATIVOS_PASSIVOS;
    }

    if ($oGet->lQuadroCompensacao === "true") {
        $aQuadros[] = BalancoPatrimonialDCASP2017::QUADRO_CONTAS_COMPENSACAO;
    }

    if ($oGet->lQuadroSuperavitDeficit === "true") {
        $aQuadros[] = BalancoPatrimonialDCASP2017::QUADRO_SUPERAVIT;
    }

    if (empty($aQuadros)) {
        throw new Exception("Relatório não informado.");
    }

    $oRelatorio = new BalancoPatrimonialDCASP2017(db_getsession('DB_anousu'),
        BalancoPatrimonialDCASP2017::CODIGO_RELATORIO,
        $oGet->periodo
    );
    $oRelatorio->setExibirExercicioAnterior($lExibirExercicioAnterior);
    $oRelatorio->setInstituicoes(str_replace('-', ',', $oGet->db_selinstit));
    $oRelatorio->setExibirQuadros($aQuadros);
    $oRelatorio->emitir();

} catch (Exception $e) {
    db_redireciona("db_erros.php?db_erro=" . $e->getMessage());
}