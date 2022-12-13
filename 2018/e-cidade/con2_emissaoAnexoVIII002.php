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
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoVIII;

$oGet               = db_utils::postMemory($_GET);
$iAnoSessao         = db_getsession('DB_anousu');
$iInstituicaoSessao = db_getsession('DB_instit');


try {

  if (empty($oGet->periodo)) {
    throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoVIII.codigo_periodo_invalido'));
  }

  if (empty($iInstituicaoSessao)) {
    throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoVIII.instituicao_nao_informado'));
  }

  $oRelatorio = new AnexoVIII($iAnoSessao, new Periodo($oGet->periodo), $iInstituicaoSessao);
  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}