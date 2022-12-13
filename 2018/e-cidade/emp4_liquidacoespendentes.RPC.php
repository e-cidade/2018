<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

$oDaoEmpagemov = new cl_empagemov;
$oDaoEmpnota   = new cl_empnota;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "verificarOrdenacaoPagamento":

      if (empty($oParam->movimentos)) {
        throw new ParameterException('Não foi informado nenhum movimento.');
      }

      $aCodigosMovimentos = array();
      $aCodigosNotas      = array();
      $aDados             = array();
      $aNotasParciais     = array();
      $aSelecionadosJustificar = array();
      $aMovimentoValor         = array();

      //Código das formas de pagamento a serem consultadas.
      $aFormasPagamentoBuscar = array();

      //Formas de pagamento, onde key = código e value = descrição.
      $aFormasPagamentos = array();

      foreach ($oParam->movimentos as $oStdMovimento) {

        $rsBuscaCodigoNota = db_query("select * from pagordemnota where e71_codord = {$oStdMovimento->iCodNota}");
        $lPagamentoNDA = empty($oStdMovimento->iCodForma);

        $rsValorMovimento = db_query("select * from empagemov where e81_codmov = {$oStdMovimento->iCodMov}");
        $nValorMovimento  = db_utils::fieldsMemory($rsValorMovimento, 0)->e81_valor;

        if ( empty($aNotasParciais[$oStdMovimento->iCodNota]) ) {

          $iCodigoNotaLiquidacao = db_utils::fieldsMemory($rsBuscaCodigoNota, 0)->e71_codnota;
          $oNota = new NotaLiquidacao($iCodigoNotaLiquidacao);
          $aNotasParciais[$oStdMovimento->iCodNota] = new stdClass;
          $aNotasParciais[$oStdMovimento->iCodNota]->oNota                = $oNota;
          $aNotasParciais[$oStdMovimento->iCodNota]->dtVencimento         = $oNota->getDataVencimento();
          $aNotasParciais[$oStdMovimento->iCodNota]->nValor               = $lPagamentoNDA ? 0 : round(($oStdMovimento->nValor + $oStdMovimento->nValorRetencao), 2);
          $aNotasParciais[$oStdMovimento->iCodNota]->nValorTotalMovimento = $lPagamentoNDA ? 0 : round($nValorMovimento, 2);
          $aNotasParciais[$oStdMovimento->iCodNota]->oStdMovimento        = $oStdMovimento;
          $aNotasParciais[$oStdMovimento->iCodNota]->aCodigoMovimentos    = array($oStdMovimento->iCodMov);

        } else {

          $aNotasParciais[$oStdMovimento->iCodNota]->nValor += $lPagamentoNDA ? 0 : round(($oStdMovimento->nValor + $oStdMovimento->nValorRetencao), 2);
          $aNotasParciais[$oStdMovimento->iCodNota]->nValorTotalMovimento += $lPagamentoNDA ? 0 : round($nValorMovimento, 2);
          $aNotasParciais[$oStdMovimento->iCodNota]->aCodigoMovimentos[]   = $oStdMovimento->iCodMov;
        }
      }


      $aJustificar = array();
      foreach ($aNotasParciais as $iCodigoOrdem => $oNotaParcial) {

        foreach ($aNotasParciais as $iCodigoOrdemComparacao => $oNotaParcialComparacao) {

          if ($iCodigoOrdem == $iCodigoOrdemComparacao) {
            continue;
          }

          $lValorParcial        = ($oNotaParcial->nValor < $oNotaParcial->nValorTotalMovimento);

          $lDataVencimentoMaior = true;
          if ($oNotaParcialComparacao->dtVencimento instanceof DBDate && $oNotaParcial->dtVencimento instanceof DBDate) {
            $lDataVencimentoMaior = ($oNotaParcialComparacao->dtVencimento->getTimeStamp() > $oNotaParcial->dtVencimento->getTimeStamp());
          }

          if ( ($lValorParcial && $lDataVencimentoMaior && !empty($oNotaParcialComparacao->oStdMovimento->iCodForma) )) {
            $aJustificar = array_merge($aJustificar, $oNotaParcialComparacao->aCodigoMovimentos);
          }
        }
      }


      foreach ($oParam->movimentos as $oMovimento) {

        $aCodigosMovimentos[]              = $oMovimento->iCodMov;
        $aMovimentos[$oMovimento->iCodMov] = $oMovimento;
        $aFormasPagamentoBuscar[]          = $oMovimento->iCodForma;
      }

      $sMovimentos = implode(',', $aCodigosMovimentos);
      $sCampos     = 'empempenho.*, empagemov.*, empnota.*, cc31_classificacaocredores, e09_sequencial';
      $sWhere      = "e81_codmov in ({$sMovimentos})";
      $sSql        = $oDaoEmpagemov->sql_query_classificacaocredores($sCampos, $sWhere);
      $rsDados     = $oDaoEmpagemov->sql_record($sSql);
      if ($oDaoEmpagemov->numrows == 0) {
        throw new DBException('Não foi possível verificar a ordem cronológica dos pagamentos.');
      }

      $aValoresNota = array();
      for ($iIndice = 0; $iIndice < $oDaoEmpagemov->numrows; $iIndice++) {

        $oDados   = db_utils::fieldsMemory($rsDados, $iIndice);
        $aDados[] = $oDados;

        if (empty($oDados->cc31_classificacaocredores)) {

          $sMensagem  = "O Empenho {$oDados->e60_codemp}/{$oDados->e60_anousu} não pertence a nenhuma Lista de Classificação de Credores. ";
          $sMensagem .= "Para prosseguir é necessário que esse Empenho esteja classificado através da rotina de Manutenção da Lista de Classificação de Credores.";
          throw new BusinessException($sMensagem);
        }

        $oLista = ListaClassificacaoCredorRepository::getPorCodigo($oDados->cc31_classificacaocredores);
        if (empty($oDados->e69_dtvencimento) &&  !$oLista->dispensa()) {

          $sMensagem  = "A Nota de Liquidação {$oDados->e69_codnota} (Empenho {$oDados->e60_codemp}/{$oDados->e60_anousu}) não possui Data de Vencimento. ";
          $sMensagem .= "Para prosseguir é necessário que seja informada a Data de Vencimento através da rotina de Manutenção da Lista de Classificação de Credores.";
          throw new BusinessException($sMensagem);
        }
      }

      /**
       * Aqui é verificado se os movimentos informados tem algum pagamento parcial.
       * É buscado o movimento originado da mesma ordem de pagamento.
       *
       * Caso o valor PAGO + VALOR SELECIONADO (agenda) seja igual ao valor TOTAL da OP, este movimento é
       * desconsiderado da validação
       */
      $aRemoverMovimentosValidacao = array();
      foreach ($aMovimentos as $iCodigoMovimento => $oStdMovimento) {

        $aWhere = array(
          "e82_codord = {$oStdMovimento->iCodNota}",
       );

        $sCampos = "e53_valor, sum(e53_vlrpag) as vlr_pago";
        $sWhere  = implode(' and ', $aWhere) . " group by e53_valor";
        $oDaoEmpOrd     = new cl_empord();
        $sSqlBuscaOrdem = $oDaoEmpOrd->sql_query_movimento_ordem($sCampos, $sWhere);
        $rsBuscaValorPagoPorOP = db_query($sSqlBuscaOrdem);
        if (!$rsBuscaValorPagoPorOP) {
          throw new Exception("Ocorreu um erro ao buscar os valores pagos por Ordem de Pagamento.");
        }

        if (pg_num_rows($rsBuscaValorPagoPorOP) == 0) {
          continue;
        }

        $oStdValorOrdem = db_utils::fieldsMemory($rsBuscaValorPagoPorOP, 0);
        $nValorAPagar   = $oStdMovimento->nValor + ($oStdValorOrdem->vlr_pago + $oStdMovimento->nValorRetencao);
        if ($oStdValorOrdem->e53_valor == round($nValorAPagar, 2)) {
          array_push($aRemoverMovimentosValidacao, $oStdMovimento->iCodMov);
        }
      }

//      $sNotas = implode(',', $aCodigosNotas);
      $sNotas = implode(',', $aRemoverMovimentosValidacao);
      $oRetorno->movimentos = array();


      /**
       * Busca a descrição das formas de pagamento dos movimentos que serão retornados.
       */
      $sCamposEmpAgeForma = "e96_codigo as codigo, e96_descr as descricao";
      $sWhereEmpAgeForma  = "e96_codigo in (" . implode(", ", array_unique($aFormasPagamentoBuscar)) . ")";

      $oDaoEmpAgeForma = new cl_empageforma();
      $sSqlEmpAgeForma = $oDaoEmpAgeForma->sql_query(null, $sCamposEmpAgeForma, null, $sWhereEmpAgeForma);
      $rsEmpAgeForma   = db_query($sSqlEmpAgeForma);
      if (!$rsEmpAgeForma) {
        throw new DBException("Não foi possível buscar as formas de pagamento.");
      }

      $iTotalEmpAgeForma = pg_num_rows($rsEmpAgeForma);
      for ($iIndice = 0; $iIndice < $iTotalEmpAgeForma; $iIndice++) {

        $oEmpAgeForma = db_utils::fieldsMemory($rsEmpAgeForma, $iIndice);
        $aFormasPagamentos[$oEmpAgeForma->codigo] = $oEmpAgeForma->descricao;
      }

      /**
       * Verifica nota por nota se há pagamentos anteriores pendentes
       */
      foreach ($aDados as $oDados) {

        /**
         * Pula caso a forma de pagamento seja NDA
         */
        if (empty($aMovimentos[$oDados->e81_codmov]->iCodForma)) {
          continue;
        }

        /**
         * Se o empenho foi dispensado não verifica pagamentos anteriores pendentes
         */
        $oLista = ListaClassificacaoCredorRepository::getPorCodigo($oDados->cc31_classificacaocredores );
        if ($oLista->dispensa()) {
          continue;
        }

        /**
         * Se a nota foi justificada não verifica pagamentos anteriores pendentes
         */
        if (!empty($oDados->e09_sequencial)) {
          continue;
        }

        $sCampos  = 'count(*) as resultado';
        $aWhere   = array();
        $aWhere[] = "cc31_classificacaocredores = {$oDados->cc31_classificacaocredores}";
        $aWhere[] = "e69_dtvencimento < '{$oDados->e69_dtvencimento}'";
        $aWhere[] = "e69_dtvencimento is not null";
        $aWhere[] = "e71_anulado is false";
        $aWhere[] = "e81_cancelado is null";
        $aWhere[] = "e53_vlrpag < (e70_valor-e70_vlranu)";
        $aWhere[] = "e85_codmov is null";
        $aWhere[] = "e60_instit = {$iInstituicaoSessao}";

        $sWhereSuspensao  = " ((not exists (select e69_codnota from empnotasuspensao where e69_codnota = cc36_empnota)";
        $sWhereSuspensao .= " or (select cc36_dataretorno from empnotasuspensao where e69_codnota = cc36_empnota order ";
        $sWhereSuspensao .= " by cc36_sequencial desc limit 1) is not null)) ";
        $aWhere[] = $sWhereSuspensao;

        if (!empty($sNotas)) {
          $aWhere[] = "e81_codmov not in ({$sNotas})";
        }
        $sSql = $oDaoEmpnota->sql_query_classificacaocredores($sCampos, implode(' and ', $aWhere));
        $rsResultado = $oDaoEmpnota->sql_record($sSql);
        if ($oDaoEmpnota->numrows == 0) {
          throw new DBException("Não foi possível verificar os dados do movimento {$oDados->e81_codmov}.");
        }

        /**
         * Se foram encontrados pagamentos anteriores pendentes, insere
         * os dados do movimento na lista de retorno para ser justificado
         */
        $iPagamentosPendentes = db_utils::fieldsMemory($rsResultado, 0)->resultado;
        if ($iPagamentosPendentes > 0 || in_array($oDados->e81_codmov, $aJustificar)) {

          $oDataVencimento = new DBDate($oDados->e69_dtvencimento);
          $oMovimento = new stdClass;
          $oMovimento->codigo_empenho   = $oDados->e69_numemp;
          $oMovimento->numero_empenho   = "{$oDados->e60_codemp}/{$oDados->e60_anousu}";
          $oMovimento->codigo_nota      = $oDados->e69_codnota;
          $oMovimento->codigo_movimento = $oDados->e81_codmov;
          $oMovimento->valor            = $oDados->e81_valor;
          $oMovimento->classificacao_credor = $oDados->cc31_classificacaocredores;
          $oMovimento->vencimento       = $oDataVencimento->getDate(DBDate::DATA_PTBR);
          $oMovimento->forma_pagamento  = '';

          if (isset($aMovimentos[$oDados->e81_codmov])
              && isset($aFormasPagamentos[$aMovimentos[$oDados->e81_codmov]->iCodForma])) {
            $oMovimento->forma_pagamento = $aFormasPagamentos[$aMovimentos[$oDados->e81_codmov]->iCodForma];
          }

          $oRetorno->movimentos[] = $oMovimento;
        }
      }

      break;

    case 'salvarJustificativa':

      if (empty($oParam->movimentos)) {
        throw new ParameterException('Nenhum movimento foi informado.');
      }

      foreach ($oParam->movimentos as $oMovimento) {

        if (empty($oMovimento->justificativa)) {
          throw new Exception("O campo Justificativa não preenchido para a nota {$oMovimento->codigo_nota}.");
        }

        $oDaoEmpagemovjustificativa = new cl_empagemovjustificativa;
        $oDaoEmpagemovjustificativa->e09_codmov        = $oMovimento->codigo_movimento;
        $oDaoEmpagemovjustificativa->e09_codnota       = $oMovimento->codigo_nota;
        $oDaoEmpagemovjustificativa->e09_justificativa = db_stdClass::normalizeStringJsonEscapeString($oMovimento->justificativa);
        $oDaoEmpagemovjustificativa->incluir(null);

        if ($oDaoEmpagemovjustificativa->erro_status == 0) {
          throw new DBException("Não foi possível salvar a justificativa para a Nota {$oMovimento->codigo_nota}.");
        }
      }

      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro = true;
  $oRetorno->mensagem = $e->getMessage();
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
