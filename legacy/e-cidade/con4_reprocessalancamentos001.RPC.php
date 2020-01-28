<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//con4_reprocessalancamentos001.RPC.php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/materialestoque.model.php"));
require_once(modification("model/material/MaterialAlmoxarifado.model.php"));

$oJson                   = new services_json();
$oParam                  = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno                = new stdClass();
$oRetorno->iStatus       = 1;
$oRetorno->sMensagem     = '';
$iInstituicaoSessao      = db_getsession("DB_instit");
$aDadosRetorno           = array();
$sCaminhoMensagem   = "financeiro.contabilidade.con4_reprocessalancamentos001.";

/**
 * funcao que ira verificar a data do lancamento a ser processada e comparar
 * com o fechamento da contabilidade, não podemos processar lançamentos que estejam igual ou abaixo
 * da data de fechamento.
 * caso o lançamento esteja igual ou abaixo (invalido) retornamos exception
 * senão retorna true
 * @param date $dtLancamento
 * @return bollean
 */
function verificaPeriodoContabilidade($dtLancamento){

	$oDaoConDataConf    = db_utils::getDao("condataconf");
	$iInstituicao       = db_getsession("DB_instit");
	$iAnoUsu            = db_getsession("DB_anousu");
	$sWhereConDataConf  = "     c99_anousu = {$iAnoUsu}";
	$sWhereConDataConf .= " and c99_instit = {$iInstituicao}";
	$sWhereConDataConf .= " and c99_data >= '{$dtLancamento}'";
	$sCaminhoMensagem   = "financeiro.contabilidade.con4_reprocessalancamentos001.";
	$sSqlConDataConf = $oDaoConDataConf->sql_query_file (null, null, "*", null, $sWhereConDataConf);
	$rsConDataConf   = $oDaoConDataConf->sql_record($sSqlConDataConf);

	if ($oDaoConDataConf->numrows > 0) {
		throw new BusinessException(_M($sCaminhoMensagem."periodoEncerrado"));
	}
	return true;
}

try {
  switch ($oParam->sExec) {

    case "operacoesExtraOrcamentaria" :

      //$iLancamento      = $oParam->iLancamento;
      $iDocumento       = $oParam->iDocumento;
      $dtInicial        = $oParam->dtInicial;
      $dtFinal          = $oParam->dtFinal;
      $iSlipInicial     = $oParam->iSlipInicial;
      $iSlipFinal       = $oParam->iSlipFinal;
      $oDaoConlancam    = db_utils::getDao("conlancam");

      if ( empty($iSlipInicial) && !empty($iSlipFinal) ) {
        $iSlipInicial = $iSlipFinal;
      }
      if (empty($iSlipFinal) && !empty($iSlipInicial)) {
        $iSlipFinal = $iSlipInicial;
      }
      $sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
      $sWhereConlancam  .= " and slip.k17_instit = {$iInstituicaoSessao} ";

      if (!empty($dtInicial) && empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$iAnoUsu}-01-01' ";
      }
      if (empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$iAnoUsu}-01-01' and '{$dtFinal}' ";
      }
      if (!empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";
      }
      // conlancamslip
      if (!empty($iSlipInicial) ||  !empty($iSlipFinal)) {
        $sWhereConlancam .= " and c84_slip between {$iSlipInicial} and {$iSlipFinal} ";
      }

      $sCamposConLancam  = " distinct conlancam.*,    ";
      $sCamposConLancam .= " extract(year from c70_data) as anolancamento ";

      $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaExtraOrcamentario(null, $sCamposConLancam, "c70_codlan", $sWhereConlancam);
      $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);

      if ($oDaoConlancam->numrows == 0) {

        throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
      }

      //percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
      db_inicio_transacao();
      $iTotalRegistrosProcessados = 0;

      for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {

        $oDadosConlancam     = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);
        // primeiro verificamos a data do lancamento está no periodo valido da contabilidade
        verificaPeriodoContabilidade($oDadosConlancam->c70_data);
        // instanciamos o lancamento auxiliar

        $oEventoContabil     = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
        $aLancamentos        = $oEventoContabil->getEventoContabilLancamento();
        $iHistorico          = $aLancamentos[0]->getHistorico();
        $oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
        $oLancamentoAuxiliar->setHistorico($iHistorico);

        $oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        $iTotalRegistrosProcessados++;
      }


      db_fim_transacao(false);

      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
      $oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));



      break;

    case "recriarLancamentosMovimentacaoPatrimonial":

      $iLancamento      = !empty($oParam->iLancamento) ? $oParam->iLancamento : null;
      $iDocumento       = $oParam->iDocumento;
      $dtInicial        = $oParam->dtInicial;
      $dtFinal          = $oParam->dtFinal;
      $iNota            = $oParam->iNota;
      $iEmpenho         = $oParam->iEmpenho;
      $oDaoConlancam    = new cl_conlancam();

      $sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
      // Filtros padrao
      if (!empty($iLancamento)) {
        $sWhereConlancam .= " and c70_codlan = {$iLancamento}";
      }
      if (!empty($dtInicial) && empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$iAnoUsu}-01-01' ";
      }
      if (empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$iAnoUsu}-01-01' and '{$dtFinal}' ";
      }
      if (!empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";
      }
      // conlancamnota
      if (!empty($iNota)) {
        $sWhereConlancam .= " and empnotaord.m72_codnota = {$iNota} ";
      }
      // conlancamemp
      if (!empty($iEmpenho)) {
        $sWhereConlancam .= " and c75_numemp = {$iEmpenho} ";
      }

      $sCamposConLancam  = " distinct ";
      $sCamposConLancam .= " c70_data,      ";
      $sCamposConLancam .= " c75_numemp,    ";
      $sCamposConLancam .= " m72_codnota,    ";
      $sCamposConLancam .= " extract(year from c70_data) as ano_lancamento ";

      $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, $sCamposConLancam, 3, $sWhereConlancam);
      $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);

      if ($oDaoConlancam->numrows == 0) {
        throw new Exception("Nenhum lançamento localizado para o filtro selecionado.");
      }

      $sSqlRemover = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, 'c70_codlan', null, $sWhereConlancam);
      $rsRemover = db_query($sSqlRemover);
      if (!$rsRemover) {
        throw new Exception("Erro ao buscar lançamentos.");
      }
      $aLancamentosRemover = db_utils::getCollectionByRecord($rsRemover);

      db_inicio_transacao();

      $iTotalRegistrosProcessados = 0;

      for ($iRowLancamento = 0; $iRowLancamento < $oDaoConlancam->numrows; $iRowLancamento++) {

        $oStdBuscaLancamento = db_utils::fieldsMemory($rsConlancam, $iRowLancamento);
        $aItens = array();
        $oDaoEmpNotaItem = new cl_empnotaitem();
        $sSqlBuscaItemNota = $oDaoEmpNotaItem->sql_query_empenho_item(null, "e62_numemp, e62_sequen, e72_valor", null, "e72_codnota = {$oStdBuscaLancamento->m72_codnota}");
        $rsBuscaItemNota   = $oDaoEmpNotaItem->sql_record($sSqlBuscaItemNota);

        if ($oDaoEmpNotaItem->erro_status == "0") {
          throw new Exception("Nenhum item encontrado para a nota {$oStdBuscaLancamento->m72_codnota}.");
        }

        /**
         * Percorremos os itens da nota para executar o lançamento contábil
         */
        for ($iRowItem = 0; $iRowItem < $oDaoEmpNotaItem->numrows; $iRowItem++) {

          $oStdDadosItem = db_utils::fieldsMemory($rsBuscaItemNota, $iRowItem);

          $oDaoItemOC   = new cl_matestoqueitemoc();
          $aWhereItemOc = array(
            "matordemitem.m52_numemp = {$oStdDadosItem->e62_numemp}"
            ,"matordemitem.m52_sequen = {$oStdDadosItem->e62_sequen}"
          );

          $sSqlBuscaItemOC = $oDaoItemOC->sql_query(null, null, "m70_codmatmater, m51_codordem", "m52_sequen", implode(' and ', $aWhereItemOc));
          $rsBuscaItemOC   = $oDaoItemOC->sql_record($sSqlBuscaItemOC);

          if ($oDaoItemOC->erro_status == "0") {
            throw new BusinessException("Item do empenho não localizado na ordem de compra.");
          }

          $oDadoItemOrdemCompra = db_utils::fieldsMemory($rsBuscaItemOC, 0);
          $oMaterialAlmoxarifado = new MaterialAlmoxarifado($oDadoItemOrdemCompra->m70_codmatmater);

          if (empty($aItens[$oMaterialAlmoxarifado->getGrupo()->getCodigo()])) {

            $oDaoOrdemLancamento = new cl_conlancamordem();
            $sSqlBuscaOrdem      = $oDaoOrdemLancamento->sql_query_nota('c03_ordem', 'c03_ordem', "c66_codnota = {$oStdBuscaLancamento->m72_codnota} and c71_coddoc = {$iDocumento}");
            $rsBuscaOrdem        = $oDaoOrdemLancamento->sql_record($sSqlBuscaOrdem);
            if ($oDaoOrdemLancamento->erro_status == "0") {
              throw new Exception("Ordem do lançamento para reprocessar não encontrada.");
            }

            $iRowResource = 0;
            if ($oDaoOrdemLancamento->numrows == $oDaoEmpNotaItem->numrows) {
              $iRowResource = $iRowItem;
            }

            $oStdItemOrdem = new stdClass();
            $oStdItemOrdem->nValor = 0;
            $oStdItemOrdem->iOrdemLancamento = db_utils::fieldsMemory($rsBuscaOrdem, $iRowResource)->c03_ordem;
            $oStdItemOrdem->iOrdemCompra = $oDadoItemOrdemCompra->m51_codordem;

            $aItens[$oMaterialAlmoxarifado->getGrupo()->getCodigo()] = $oStdItemOrdem;
          }

          $aItens[$oMaterialAlmoxarifado->getGrupo()->getCodigo()]->nValor += $oStdDadosItem->e72_valor;
        }

        /**
         * Executa o lançamento contábil do item
         */
        foreach ($aItens as $iGrupo => $oDadosItem) {

          $oGrupo             = new MaterialGrupo($iGrupo);
          $oEventoContabil    = new EventoContabil($iDocumento, $oStdBuscaLancamento->ano_lancamento);
          $oEventoContabil->setOrdem($oDadosItem->iOrdemLancamento);
          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oStdBuscaLancamento->c75_numemp);
          $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado();
          $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
          $oLancamentoAuxiliarEmLiquidacao->setGrupoMaterial($oGrupo);
          $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oStdBuscaLancamento->c75_numemp);
          $oLancamentoAuxiliarEmLiquidacao->setValorTotal($oDadosItem->nValor);
          $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($oGrupo->getConta());
          $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($oStdBuscaLancamento->m72_codnota);
          $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico('Lançamento em liquidação da ordem de compra ' . $oDadosItem->iOrdemCompra);
          $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
          $oLancamentoAuxiliarEmLiquidacao->setSaida($oEventoContabil->estorno());

          /**
           * Dados para conta corrente credor e despesa
           */
          $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
          $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
          $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
          $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
          $oLancamentoAuxiliarEmLiquidacao->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

          $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao, $oStdBuscaLancamento->c70_data);
          $iTotalRegistrosProcessados++;
        }
      }

      foreach ($aLancamentosRemover as $oStdCodigoLancamento) {
        excluirLancamentos($oStdCodigoLancamento->c70_codlan);
      }

      db_fim_transacao(false);
      $oRetorno->sMensagem = _M($sCaminhoMensagem."processado", (object)array('total_registro' => $iTotalRegistrosProcessados));

    break;

    case "reprocessarLancamentosMovimentacaoPatrimonial" :

      $iLancamento      = $oParam->iLancamento;
      $iDocumento       = $oParam->iDocumento;
      $dtInicial        = $oParam->dtInicial;
      $dtFinal          = $oParam->dtFinal;
      $iNota            = $oParam->iNota;
      $iEmpenho         = $oParam->iEmpenho;
      $oDaoConlancam    = new cl_conlancam;

      $sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
      // Filtros padrao
      if (!empty($iLancamento)) {
        $sWhereConlancam .= " and c70_codlan = {$iLancamento}";
      }
      if (!empty($dtInicial) && empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$iAnoUsu}-01-01' ";
      }
      if (empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$iAnoUsu}-01-01' and '{$dtFinal}' ";
      }
      if (!empty($dtInicial) && !empty($dtFinal)) {
        $sWhereConlancam .= " and c70_data between '{$dtInicial}' and '{$dtFinal}' ";
      }
      // conlancamnota
      if (!empty($iNota)) {
        $sWhereConlancam .= " and empnotaord.m72_codnota = {$iNota} ";
      }
      // conlancamemp
      if (!empty($iEmpenho)) {
        $sWhereConlancam .= " and c75_numemp = {$iEmpenho} ";
      }

      $sCamposConLancam  = " distinct c70_codlan,    ";
      $sCamposConLancam .= " c70_data,      ";
      $sCamposConLancam .= " c70_valor,     ";
      $sCamposConLancam .= " c75_numemp,    ";
      $sCamposConLancam .= " m72_codnota,   ";
      $sCamposConLancam .= " extract(year from c70_data) as anolancamento ";

      if (in_array($iDocumento, array(700, 701, 702, 703, 704))) {
        $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoBensPatrimonial(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam);
        $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);
      } else {

        // @FIXME - Necessario realizar melhoria na busca dos lancamentos
        // bug #6848, versao 1.31 do arquivo classes/db_conlancam_classe.php
        // foi alterando este metodo obrigando lancamento ter vinculo com empenho
        // ajustado para procurar com inner join caso nao encontre, buscar com left join
        $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam);
        $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);
        if ($oDaoConlancam->numrows == 0) {
          $sSqlConlancam = $oDaoConlancam->sql_query_reprocessaMovimentacaoPatrimonial(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam, false);
          $rsConlancam   = $oDaoConlancam->sql_record($sSqlConlancam);
        }
      }

      if ($oDaoConlancam->numrows == 0) {
        throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
      }

      /**
       * percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
       */
      db_inicio_transacao();

      $iTotalRegistrosProcessados = 0;

      for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {

        $oDadosConlancam = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);

        /**
         * primeiro verificamos a data do lancamento está no periodo valido da contabilidade
         */
        verificaPeriodoContabilidade($oDadosConlancam->c70_data);

        if ( !empty($oDadosConlancam->c75_numemp) ) {

          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosConlancam->c75_numemp);
          if ( $iInstituicaoSessao !== $oEmpenhoFinanceiro->getInstituicao()->getSequencial() ) {
            continue;
          }
        }

        if ( !empty($oDadosConlancam->m72_codnota) ) {
          $oNota = new NotaLiquidacao($oDadosConlancam->m72_codnota);
        }

        /**
         * instanciamos o lancamento auxiliar
         */
        $oEventoContabil = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
        $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
        $iHistorico      = $aLancamentos[0]->getHistorico();

        try {
          $oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
        } catch (Exception $e) {

          if ($e->getCode() == 208) {
            $oRetorno->iLote = $e->iLote;
          }

          throw new Exception($e->getMessage());
        }

        $oLancamentoAuxiliar->setHistorico($iHistorico);

        /**
         * Não reprocessa lancamentos de bens que não foram baixados
         */
        if (in_array($iDocumento, array(701, 702)) && !$oLancamentoAuxiliar->getBem()->isBaixado()) {
          continue;
        }


        $oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        $iTotalRegistrosProcessados++;
      }

      db_fim_transacao(false);

      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
      $oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));

    break;

    case "reprocessarLancamentos":

    	$iLancamento      = $oParam->iLancamento;
    	$iDocumento       = $oParam->iDocumento;
    	$dtInicial        = $oParam->dtInicial;
    	$dtFinal          = $oParam->dtFinal;
    	$iAcordo          = $oParam->iAcordo;
    	$iEmpenho         = $oParam->iEmpenho;
    	$iPassivo         = $oParam->iPassivo;
    	$oDaoConlancam    = db_utils::getDao("conlancam");
    	$sCaminhoMensagem = "financeiro.contabilidade.con4_reprocessalancamentos001.";

    	$sCamposConLancam  = " c70_codlan,  ";
    	$sCamposConLancam .= " c70_data,    ";
    	$sCamposConLancam .= " c70_valor,   ";
      $sCamposConLancam .= " c75_numemp,   ";
      $sCamposConLancam .= " c87_acordo,   ";
    	$sCamposConLancam .= "extract(year from c70_data) as anolancamento ";

    	$sWhereConlancam   = " c71_coddoc = {$iDocumento} ";
    	// Filtros padrao
    	if (!empty($iLancamento)) {
    		$sWhereConlancam .= " and c70_codlan = {$iLancamento}";
    	}
    	if (!empty($dtInicial) && empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$dtInicial}' and c70_data <= '{$iAnoUsu}-01-01' ";
    	}
    	if (empty($dtInicial) && !empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$iAnoUsu}-01-01' and c70_data <= '{$dtFinal}' ";
    	}
    	if (!empty($dtInicial) && !empty($dtFinal)) {
    		$sWhereConlancam .= " and c70_data >= '{$dtInicial}' and c70_data <= '{$dtFinal}' ";
    	}
    	// filtros especificos:
    	   // conlancamacordo
    	if (!empty($iAcordo)) {
    		$sWhereConlancam .= " and c87_acordo = {$iAcordo} ";
    	}
    	   // conlancamemp
    	if (!empty($iEmpenho)) {
    		$sWhereConlancam .= " and c75_numemp = {$iEmpenho} ";
    	}
    	   // conlancaminscricaopassivo
      if (!empty($iPassivo)) {
      	$sWhereConlancam .= " and c37_inscricaopassivo = {$iPassivo} ";
      }
    	$sSqlConlancam  = $oDaoConlancam->sql_query_reprocessamento(null, $sCamposConLancam, 'c70_codlan', $sWhereConlancam);
    	$rsConlancam    = $oDaoConlancam->sql_record($sSqlConlancam);

    	// caso nao venha lançamentos informamos o usuário.
    	if ($oDaoConlancam->numrows == 0) {
    		throw new BusinessException(_M($sCaminhoMensagem."buscaLancamentos"));
    	}
    	//percorre os lançamentos chamando os lancamentos auxiliares e fazendo o evento contabil
    	db_inicio_transacao();
      $iTotalRegistrosProcessados = 0;
    	for ($iResultLancamento = 0; $iResultLancamento < $oDaoConlancam->numrows; $iResultLancamento++) {

    		$oDadosConlancam     = db_utils::fieldsMemory($rsConlancam, $iResultLancamento);
    		// primeiro verificamos a data do lancamento está no periodo valido da contabilidade
    		verificaPeriodoContabilidade($oDadosConlancam->c70_data);


        if ( !empty($oDadosConlancam->c75_numemp) ) {

          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oDadosConlancam->c75_numemp);
          if ( $iInstituicaoSessao !== $oEmpenhoFinanceiro->getInstituicao()->getSequencial() ) {
            continue;
          }
        }

        if ( !empty($oDadosConlancam->c87_acordo) ) {

          $oAcordo = new Acordo($oDadosConlancam->c87_acordo);
          if ( $iInstituicaoSessao !== $oAcordo->getInstit() ) {
            continue;
          }
        }

    		// instanciamos o lancamento auxiliar
    		$oLancamentoAuxiliar = LancamentoAuxiliarFactory::getInstance($iDocumento, $oDadosConlancam->c70_codlan);
    		$oEventoContabil     = new EventoContabil($iDocumento, $oDadosConlancam->anolancamento);
    		$oEventoContabil->reprocessaLancamentos($oDadosConlancam->c70_codlan, $oLancamentoAuxiliar, $oDadosConlancam->c70_data);
        $iTotalRegistrosProcessados++;
    	}
    	db_fim_transacao(false);

      $oStdMensagemSucesso = new stdClass();
      $oStdMensagemSucesso->total_registro = $iTotalRegistrosProcessados;
    	$oRetorno->sMensagem = (_M($sCaminhoMensagem."processado", $oStdMensagemSucesso));
    break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;

  }

  $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
}

/**
 * @param $iCodigoLancamento
 * @throws Exception
 */
function excluirLancamentos($iCodigoLancamento) {

  $aTabelas = array(
    'c44_conlancam' => 'conencerramentolancam',
    'c80_conlancam' => 'conlancamaberturaexercicio',
    'c105_codlan' => 'conlancamaberturaexercicioorcamento',
    'c87_codlan' => 'conlancamacordo',
    'c110_codlan' => 'conlancambem',
    'c77_codlan' => 'conlancambol',
    'c76_codlan' => 'conlancamcgm',
    'c72_codlan' => 'conlancamcompl',
    'c08_codlan' => 'conlancamconcarpeculiar',
    'c23_conlancam' => 'conlancamcorgrupocorrente',
    'c86_conlancam' => 'conlancamcorrente',
    'c106_codlan' => 'conlancamdepreciacao',
    'c78_codlan' => 'conlancamdig',
    'c71_codlan' => 'conlancamdoc',
    'c73_codlan' => 'conlancamdot',
    'c67_codlan' => 'conlancamele',
    'c75_codlan' => 'conlancamemp',
    'c88_codlan' => 'conlancamimp',
    'c108_codlan' => 'conlancaminscrestosapagar',
    'c37_conlancam' => 'conlancaminscricaopassivo',
    'c85_codlan' => 'conlancaminventario',
    'c103_conlancam' => 'conlancammatestoqueinimei',
    'c66_codlan' => 'conlancamnota',
    'c80_codlan' => 'conlancamord',
    'c82_codlan' => 'conlancampag',
    'c100_codlan' => 'conlancamprovisaodecimoterceiro',
    'c101_codlan' => 'conlancamprovisaoferias',
    'c74_codlan' => 'conlancamrec',
    'c113_codlan' => 'conlancamreconhecimentocontabil',
    'c84_conlancam' => 'conlancamslip',
    'c79_codlan' => 'conlancamsup',
    'e33_conlancam' => 'pagordemdescontolanc',
    'e51_codlan' => 'pagordemval',
    'c02_codlan' => 'conlancaminstit',
    'c03_codlan' => 'conlancamordem',
    'c69_codlan' => 'conlancamval',
    'c70_codlan' => 'conlancam',
  );

  /**
   * Excluimos os lançamentos contábeis contacorrentedetalheconlancamval para então incluirmos nas contas corretas
   */
  $oDaoConlancamval = new cl_conlancamval();
  $sSqlConlancamval = $oDaoConlancamval->sql_query_file(null, "c69_sequen", null, "c69_codlan = $iCodigoLancamento");
  $rsConlancamval   = $oDaoConlancamval->sql_record($sSqlConlancamval);

  for ($iConlancamval = 0 ; $iConlancamval < $oDaoConlancamval->numrows; $iConlancamval++) {

    $c69_sequen = db_utils::fieldsMemory($rsConlancamval, $iConlancamval)->c69_sequen;
    $oDaoExcluirDetalheConlancamVal = new cl_contacorrentedetalheconlancamval();
    $oDaoExcluirDetalheConlancamVal->excluir(null, "c28_conlancamval = {$c69_sequen} ");

    if ($oDaoExcluirDetalheConlancamVal->erro_status == "0") {
      throw new Exception("Erro ao excluir vinculo do lançamento com conta corrente.");
    }

    $oDaoConlancamValExclusao = new cl_conlancamval();

    /**
     * Excluimos os lançamentos contábeis para então incluirmos nas contas corretas
     */
    $oDaoConlancamValExclusao->excluir(null,"c69_sequen = {$c69_sequen}");
    if ($oDaoConlancamValExclusao->erro_status == "0") {
      throw new Exception("Erro ao excluir contas do lançamento.");
    }
  }

  foreach ($aTabelas as $sCampo => $sTabela) {

    $lExecutou = db_query("delete from $sTabela where $sCampo = $iCodigoLancamento");

    if (!$lExecutou) {
      throw new Exception("Erro ao excluir lançamentos.");
    }
  }
}
