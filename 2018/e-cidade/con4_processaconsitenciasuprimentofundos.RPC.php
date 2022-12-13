<?php
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("classes/lancamentoContabil.model.php"));
require_once(modification("libs/JSON.php"));
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->erro    = false;
switch($oParam->exec) {

  case 'pesquisar' :
    $oRetorno->empenhos = getEmpenhosSuprimentoFundos($oParam);
    break;

  case 'processar' :

    $oRetorno->erros      = array();
    if (db_getsession("DB_id_usuario") != 1) {

      $oRetorno->erro    = true;
      $oRetorno->message = urlencode("Rotina apenas permitida para usuários administradores");
      break;
    }

    $aEmpenhosProcessados = array();
    $aEmpenhos            = getEmpenhosSuprimentoFundos($oParam);
    foreach ($aEmpenhos as $oEmpenho) {

      if (in_array($oEmpenho->e60_numemp, $aEmpenhosProcessados)) {
        continue;
      }

      try {

        db_inicio_transacao();
        $oEmpenhoFinanceiro  = new EmpenhoFinanceiro($oEmpenho->e60_numemp);
        processarCorrecaoLancamentoEmpenho($oEmpenho, $oEmpenhoFinanceiro);
        processarCorrecaoLancamendoLiquidacao($oEmpenho, $oEmpenhoFinanceiro);
        criarSuprimentoDeFundos($oEmpenho, $oEmpenhoFinanceiro);
        $aEmpenhosProcessados[] = $oEmpenho->e60_numemp;
        db_fim_transacao(false);

       } catch (Exception $o) {

        $oRetorno->erros[] = (object) array("empenho" => $oEmpenho->e60_numemp, "Erro:" =>$o->getMessage());
        $lErro = true;
        db_fim_transacao(true);
      }
    }
    break;
}
echo $oJson->encode($oRetorno);
function processarCorrecaoLancamentoEmpenho($oEmpenho, EmpenhoFinanceiro $oEmpenhoFinanceiro) {

  if ($oEmpenho->empenho_doc_1 == 0) {
    return false;
  }
  $aLancamentosEmpenho = getLancamentosDoEmpenhoComODocumento($oEmpenho, array(1));
  foreach ($aLancamentosEmpenho as $oLancamento) {

    $oDotacao = $oEmpenhoFinanceiro->getDotacao();
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setDotacao($oDotacao);
    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
    $oContaCorrenteDetalhe->setRecurso($oDotacao->getDadosRecurso());

    /**
     * Valida parametro de integracao da contabilidade com contratos
     */
    $oEventoContabil     = new EventoContabil(410, $oEmpenho->e60_anousu);
    $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
    $oLancamentoAuxiliar->setCaracteristicaPeculiar($oEmpenho->e60_concarpeculiar);
    $oLancamentoAuxiliar->setCodigoElemento($oLancamento->c67_codele);
    $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
    $oLancamentoAuxiliar->setNumeroEmpenho($oEmpenho->e60_numemp);
    $oLancamentoAuxiliar->setValorTotal($oLancamento->c70_valor);
    $oLancamentoAuxiliar->setObservacaoHistorico($oLancamento->c72_complem);
    $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
    $oLancamentoAuxiliar->setCodigoDotacao($oLancamento->c73_coddot);
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
    $oEventoContabil->setOrdem($oLancamento->c03_ordem);
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $oLancamento->c70_data);
    lancamentoContabil::excluirLancamento($oLancamento->c70_codlan);
  }
}

function processarCorrecaoLancamendoLiquidacao($oEmpenho, EmpenhoFinanceiro $oEmpenhoFinanceiro) {

  if ($oEmpenho->liquidacao_doc_3 == 0) {
    return false;
  }

  $aLancamentosEmpenho = getLancamentosDoEmpenhoComODocumento($oEmpenho, array(3, 23, 24));
  foreach ($aLancamentosEmpenho as $oLancamento) {

    $oPlanoContaOrcamento = new ContaOrcamento($oLancamento->c67_codele, $oEmpenho->e60_anousu, null, db_getsession("DB_instit") );
    $oPlanoConta          = $oPlanoContaOrcamento->getPlanoContaPCASP();
    if (empty($oPlanoConta)) {
      throw new Exception("Conta do orçamento {$oPlanoContaOrcamento->getEstrutural()}");
    }

    $oEventoContabil         = new EventoContabil(412, $oEmpenhoFinanceiro->getAnoUso());
    $aLancamentosCadastrados = $oEventoContabil->getEventoContabilLancamento();
    $oLancamentoAuxiliar     = new LancamentoAuxiliarEmpenhoLiquidacao();
    $oLancamentoAuxiliar->setObservacaoHistorico($oLancamento->c72_complem);
    $oLancamentoAuxiliar->setCodigoElemento($oLancamento->c67_codele);
    $oLancamentoAuxiliar->setCodigoContaPlano($oPlanoConta->getReduzido());
    if (!empty($oLancamento->c66_codnota)) {
      $oLancamentoAuxiliar->setCodigoNotaLiquidacao($oLancamento->c66_codnota);
    }
    $oLancamentoAuxiliar->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
    $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
    $oLancamentoAuxiliar->setValorTotal($oLancamento->c70_valor);
    $oLancamentoAuxiliar->setHistorico($aLancamentosCadastrados[0]->getHistorico());
    if (!empty($oLancamento->c80_codord)) {
      $oLancamentoAuxiliar->setCodigoOrdemPagamento($oLancamento->c80_codord);
    }
    $oLancamentoAuxiliar->setNumeroEmpenho($oEmpenhoFinanceiro->getNumero());

    $oLancamentoAuxiliar->setCaracteristicaPeculiarCredito($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
    $oLancamentoAuxiliar->setCaracteristicaPeculiarDebito ($oEmpenhoFinanceiro->getCaracteristicaPeculiar());

    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
    $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
    $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    $oEventoContabil->setOrdem($oLancamento->c03_ordem);
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $oLancamento->c70_data);
    lancamentoContabil::excluirLancamento($oLancamento->c70_codlan);
  }
}

function criarSuprimentoDeFundos($oEmpenho, EmpenhoFinanceiro $oEmpenhoFinanceiro) {

  $aListaDocumentosPesquisar = array();
  if ($oEmpenho->suprimento_fundos == 0) {
    $aListaDocumentosPesquisar[] = 5;
  }

  if ($oEmpenho->suprimento_fundos > 0 && count(getLancamentosDoEmpenhoComODocumento($oEmpenho, array(6))) > 0
    && $oEmpenho->prestacao_contas  == 0) {
    $aListaDocumentosPesquisar[] = 6;
  }

  if (count($aListaDocumentosPesquisar) == 0) {
    return false;
  }
  $aLancamentosEmpenho = getLancamentosDoEmpenhoComODocumento($oEmpenho, $aListaDocumentosPesquisar);
  foreach ($aLancamentosEmpenho as $oLancamento) {

    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setCredor($oEmpenhoFinanceiro->getCgm());
    $iCodigoDocumento = 90;
    if ($oLancamento->c71_coddoc == 6 && $oEmpenho->prestacao_contas == 0) {
      $iCodigoDocumento = 91;
    }
    $oEventoContabilPrestacaoConta     = new EventoContabil($iCodigoDocumento, $oEmpenhoFinanceiro->getAnoUso());
    $oLancamentoAuxiliarPrestacaoConta = new LancamentoAuxiliarEmpenho();
    $oLancamentoAuxiliarPrestacaoConta->setObservacaoHistorico("Lançamento de prestação de contas.");
    $oLancamentoAuxiliarPrestacaoConta->setFavorecido($oEmpenhoFinanceiro->getFornecedor()->getCodigo());
    $oLancamentoAuxiliarPrestacaoConta->setCodigoElemento($oLancamento->c67_codele);
    $oLancamentoAuxiliarPrestacaoConta->setCodigoDotacao($oLancamento->c73_coddot);
    $oLancamentoAuxiliarPrestacaoConta->setCaracteristicaPeculiar($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
    $oLancamentoAuxiliarPrestacaoConta->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
    $oLancamentoAuxiliarPrestacaoConta->setNumeroEmpenho($oEmpenho->e60_numemp);
    $oLancamentoAuxiliarPrestacaoConta->setValorTotal($oLancamento->c70_valor);
    $oLancamentoAuxiliarPrestacaoConta->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
    $oEventoContabilPrestacaoConta->setOrdem($oLancamento->c03_ordem);
    $oEventoContabilPrestacaoConta->executaLancamento($oLancamentoAuxiliarPrestacaoConta, $oLancamento->c70_data);
  }
}

/**
 * @param       $oEmpenho
 * @param array $aCodigoDocumento
 * @return array
 */
function getLancamentosDoEmpenhoComODocumento($oEmpenho, array $aCodigoDocumento) {

  $sListaLancamentos  = implode(",", $aCodigoDocumento);
  $sSqlLancamentosEmpenho  = " select c70_codlan,";
  $sSqlLancamentosEmpenho .= "        c70_data, ";
  $sSqlLancamentosEmpenho .= "        c70_valor,";
  $sSqlLancamentosEmpenho .= "        c72_complem,";
  $sSqlLancamentosEmpenho .= "        c73_coddot,";
  $sSqlLancamentosEmpenho .= "        c71_coddoc,";
  $sSqlLancamentosEmpenho .= "        c67_codele,";
  $sSqlLancamentosEmpenho .= "        c03_ordem,";
  $sSqlLancamentosEmpenho .= "        c66_codnota,";
  $sSqlLancamentosEmpenho .= "        c80_codord";
  $sSqlLancamentosEmpenho .= "   from conlancam";
  $sSqlLancamentosEmpenho .= "        inner join conlancamdoc    on c70_Codlan = c71_codlan";
  $sSqlLancamentosEmpenho .= "        inner join conlancamemp    on c70_Codlan = c75_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamcompl  on c70_Codlan = c72_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamdot    on c70_Codlan = c73_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamele    on c70_Codlan = c67_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamordem  on c70_codlan = c03_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamnota   on c70_codlan = c66_codlan";
  $sSqlLancamentosEmpenho .= "        left  join conlancamord    on c70_codlan = c80_codlan";
  $sSqlLancamentosEmpenho .= " where c71_coddoc in ({$sListaLancamentos})";
  $sSqlLancamentosEmpenho .= "   and c75_numemp = {$oEmpenho->e60_numemp}";
  $rsLancamentos           = db_query($sSqlLancamentosEmpenho);
  return db_utils::getCollectionByRecord($rsLancamentos);
}

function getEmpenhosSuprimentoFundos($oParam) {

  $sDataInicial           = $oParam->datainicial;
  $sDataFinal             = $oParam->datafinal;
  $iAno                   = $oParam->ano;
  $iInstituicao           = db_getsession("DB_instit");
  $sSqlQuerySuprimentosIncosistentes  = "select e60_numemp,";
  $sSqlQuerySuprimentosIncosistentes .= "       e60_codemp,";
  $sSqlQuerySuprimentosIncosistentes .= "       e60_instit,";
  $sSqlQuerySuprimentosIncosistentes .= "       e60_concarpeculiar,";
  $sSqlQuerySuprimentosIncosistentes .= "       e60_anousu,";
  $sSqlQuerySuprimentosIncosistentes .= "       empenho_doc_1,";
  $sSqlQuerySuprimentosIncosistentes .= "       empenho_doc_410,";
  $sSqlQuerySuprimentosIncosistentes .= "       liquidacao_doc_3,";
  $sSqlQuerySuprimentosIncosistentes .= "       liquidacao_doc_412,";
  $sSqlQuerySuprimentosIncosistentes .= "       pagamento,";
  $sSqlQuerySuprimentosIncosistentes .= "       suprimento_fundos,";
  $sSqlQuerySuprimentosIncosistentes .= "       prestacao_contas";
  $sSqlQuerySuprimentosIncosistentes .= "  from (select e60_numemp,";
  $sSqlQuerySuprimentosIncosistentes .= "             e60_codemp,";
  $sSqlQuerySuprimentosIncosistentes .= "             e60_instit,";
  $sSqlQuerySuprimentosIncosistentes .= "             e60_concarpeculiar,";
  $sSqlQuerySuprimentosIncosistentes .= "             e60_anousu,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 1 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc = 2 then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (1,2)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as empenho_doc_1,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 410 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc = 411 then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (410,411)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as empenho_doc_410,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc in (3,23) then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc in (4,24) then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (3,4,23,24)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as liquidacao_doc_3,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 412 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc = 413 then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (412,413)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as liquidacao_doc_412,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 5 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc = 6 then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (5,6)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as pagamento,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 90 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc in (91,92) then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (90,91,92)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as suprimento_fundos,";
  $sSqlQuerySuprimentosIncosistentes .= "             (select coalesce(sum(case when c71_coddoc = 414 then round(coalesce(c70_valor,0),2)";
  $sSqlQuerySuprimentosIncosistentes .= "                              when c71_coddoc = 415 then round(coalesce(c70_valor,0),2)*-1";
  $sSqlQuerySuprimentosIncosistentes .= "                                   else 0 end),0)";
  $sSqlQuerySuprimentosIncosistentes .= "                from conlancam";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamemp on c70_codlan = c75_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "                     inner join conlancamdoc on c70_codlan = c71_codlan";
  $sSqlQuerySuprimentosIncosistentes .= "               where c70_data between  '{$sDataInicial}' and '{$sDataFinal}'";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c71_coddoc in (414,415)";
  $sSqlQuerySuprimentosIncosistentes .= "                 and c75_numemp = e60_numemp) as prestacao_contas";
  $sSqlQuerySuprimentosIncosistentes .= "        from empempenho";
  $sSqlQuerySuprimentosIncosistentes .= "             inner join emppresta on e45_numemp = e60_numemp";
  $sSqlQuerySuprimentosIncosistentes .= "       where e60_anousu = {$iAno}";
  $sSqlQuerySuprimentosIncosistentes .= "         and e60_instit = {$iInstituicao}) as x ";
  $sSqlQuerySuprimentosIncosistentes .= "where (prestacao_contas > suprimento_fundos)";
  $sSqlQuerySuprimentosIncosistentes .= "   or (empenho_doc_1 > empenho_doc_410)";
  $sSqlQuerySuprimentosIncosistentes .= "   or (liquidacao_doc_3 > liquidacao_doc_412)";
  $sSqlQuerySuprimentosIncosistentes .= "   or (pagamento < suprimento_fundos)";

  $rsQuerySuprimentos = db_query($sSqlQuerySuprimentosIncosistentes);
  $iTotalEmpenhos     = pg_num_rows($rsQuerySuprimentos);

  $aEmpenhos = array();
  for ($iEmpenho = 0; $iEmpenho < $iTotalEmpenhos; $iEmpenho++) {
    $aEmpenhos[] = db_utils::fieldsMemory($rsQuerySuprimentos, $iEmpenho);
  }
  return $aEmpenhos;
}