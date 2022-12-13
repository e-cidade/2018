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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoV as Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV;

try {

  $instituicaoSemRPPS    = '';
  $rsInstituicoes        = db_query($sql = "SELECT codigo FROM db_config WHERE db21_tipoinstit IN (".
                                              Instituicao::TIPO_PREFEITURA                       .','.
                                              Instituicao::TIPO_CAMARA                           .','.
                                              Instituicao::TIPO_SECRETARIA_DA_EDUCACAO           .','.
                                              Instituicao::TIPO_SECRETARIA_DA_SAUDE              .','.
                                              Instituicao::TIPO_AUTARQUIA_EXCETO_RPPS            .','.
                                              Instituicao::TIPO_FUNDACAO                         .','.
                                              Instituicao::TIPO_EMPRESA_ESTATAL_DEPENDENTE       .','.
                                              Instituicao::TIPO_EMPRESA_ESTATAL_NAO_DEPENDENTE   .','.
                                              Instituicao::TIPO_CONSORCIO                        .','.
                                              Instituicao::TIPO_OUTRAS                           .','.
                                              Instituicao::TIPO_MINISTERIO_PUBLICO_ESTADUAL
                                            .")");

  $aInstituicaoesSemRPPS = array();
  $aInstituicaoesSemRPPS = db_utils::makeCollectionFromRecord($rsInstituicoes, function ($oRetorno) {
    return $oRetorno->codigo;
  });

  if(empty($aInstituicaoesSemRPPS)) {
    throw new Exception("Não há instituições que não sejam do tipo RPPS.");
  }

  $instituicaoSemRPPS = implode(', ', $aInstituicaoesSemRPPS);

  if (empty($oGet->periodo)) {
    throw new Exception(_M('financeiro.contabilidade.con2_emissaoAnexoV.codigo_periodo_invalido'));
  }

  $oAnexo = new AnexoV($iAnoSessao, AnexoV::CODIGO_RELATORIO, $oGet->periodo);
  $oAnexo->setInstituicoes($instituicaoSemRPPS);

  $oRelatorio = new Layout();
  $oRelatorio->setAnexo($oAnexo);
  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}