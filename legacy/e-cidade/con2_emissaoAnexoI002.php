<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet               = db_utils::postMemory($_GET);
$iAnoSessao         = db_getsession('DB_anousu');
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoI;

try {

  if (empty($oGet->bimestre)) {
    throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoI.codigo_periodo_invalido'));
  }

  $sInstituicoes = str_replace('-', ',', $oGet->db_selinstit);

  if (empty($sInstituicoes)) {
    throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoI.instituicao_nao_informado'));
  }

  $oRelatorio = new AnexoI($iAnoSessao, new Periodo($oGet->bimestre), $sInstituicoes);
  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}