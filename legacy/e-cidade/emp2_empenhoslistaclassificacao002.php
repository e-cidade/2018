<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
try {

  $oRelatorioEmpenhoClassificao = new RelatorioEmpenhoClassificacaoCredores();
  if (!empty($oGet->data_inicial)) {
    $oRelatorioEmpenhoClassificao->setVencimentoInicial(new DBDate($oGet->data_inicial));
  }

  if (!empty($oGet->data_final)) {
    $oRelatorioEmpenhoClassificao->setVencimentoFinal(new DBDate($oGet->data_final));
  }

  if (!empty($oGet->recursos)) {
    $oRelatorioEmpenhoClassificao->setRecursos($oGet->recursos);
  }

  if (!empty($oGet->credores)) {
    $oRelatorioEmpenhoClassificao->setFornecedores($oGet->credores);
  }

  if (!empty($oGet->listas)) {
    $oRelatorioEmpenhoClassificao->setClassificacoes($oGet->listas);
  }

  $oRelatorioEmpenhoClassificao->setExercicio($oGet->exercicio);
  $oRelatorioEmpenhoClassificao->setSituacaoPagamento($oGet->situacao_pagamento);
  $oRelatorioEmpenhoClassificao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
  $oRelatorioEmpenhoClassificao->emitir();


} catch (Exception $e) {

  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
  exit;
}