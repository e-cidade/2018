<?php

use ECidade\Tributario\Arrecadacao\Relatorio\TarifaArrecadacao;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('libs/db_utils.php'));

try {

  $get = db_utils::postMemory($_GET);

  if (empty($get->dataInicial)) {
    throw new ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_inicial_vazio"));
  }

  if (empty($get->dataFinal)) {
    throw new ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_final_vazio"));
  }

  $dataInicial = new DBDate($get->dataInicial);
  $dataFinal   = new DBDate($get->dataFinal);
  if ($dataInicial->getTimeStamp() > $dataFinal->getTimeStamp()) {
    throw new ParameterException(_M(TarifaArrecadacao::MENSAGENS . "data_inicial_maior_final"));
  }

  if (empty($get->codigoBanco)) {
    throw new ParameterException(_M(TarifaArrecadacao::MENSAGENS . "banco_invalido"));
  }

  $tarifaArrecadao = new TarifaArrecadacao();
  $tarifaArrecadao->setDataInicial($dataInicial);
  $tarifaArrecadao->setDataFinal($dataFinal);
  $tarifaArrecadao->setBanco(new Banco($get->codigoBanco));
  $tarifaArrecadao->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
  $tarifaArrecadao->emitir();


} catch (Exception $e) {
  db_redireciona('db_erros.php?db_erro='.$e->getMessage());
  exit;
}