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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

$oJson      = new services_json();
$oParametro = $oJson->decode(db_stdClass::db_stripTagsJsonSemEscape(str_replace("\\","",$_POST["json"])));
$oRetorno        = new stdClass();
$oRetorno->erro  = false;
$oRetorno->mensagem = "";

$iInstituicaoSessao = db_getsession("DB_instit");
$iAnoSessao         = db_getsession("DB_anousu");

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "reprocessarSlip":

      $oDataInicial = new DBDate($oParametro->dtInicial);
      $oDataFinal   = new DBDate($oParametro->dtFinal);

      $sWhere   = "    k17_instit = {$iInstituicaoSessao} ";
      $sWhere  .= "and k17_data between '{$oDataInicial->getDate()}' ";
      $sWhere  .= "                 and '{$oDataFinal->getDate()}'   ";
      $sWhere  .= "group by slip.k17_codigo ";
      $sWhere  .= "having count(*) > 2 ";


      $oDaoSlip         = new cl_slip();
      $sSqlPesquisaSlip = $oDaoSlip->sql_query_pagamento_cheque(null, "k17_codigo, count(*)", null, $sWhere);
      $rsPesquisaSlip   = $oDaoSlip->sql_record($sSqlPesquisaSlip);
      if ($oDaoSlip->numrows == 0) {
        throw new BusinessException("Nenhum slip encontrado para os filtros selecionados.");
      }

      $aCodigosSlip = array();
      for ($iRowSlip = 0; $iRowSlip < $oDaoSlip->numrows; $iRowSlip++) {
        $aCodigosSlip[] = db_utils::fieldsMemory($rsPesquisaSlip, $iRowSlip)->k17_codigo;
      }

      $sSlipsExcluir      = implode(",", $aCodigosSlip);
      $oDaoConlancamSlip  = new cl_conlancamslip();
      $sCamposLancamento  = "c84_conlancam as lancamento";
      $sWhereLancamento   = "c84_slip in ({$sSlipsExcluir})";
      $sSqlLancamentoSlip = $oDaoConlancamSlip->sql_query_file(null, $sCamposLancamento, null, $sWhereLancamento);
      $rsBuscaLancamento  = $oDaoConlancamSlip->sql_record($sSqlLancamentoSlip);

      $rsCriaTabelaTemporaria = db_query("create temp table bkp_reprocessalancamentoslip as {$sSqlLancamentoSlip}");

      if (pg_num_rows(db_query('select * from bkp_reprocessalancamentoslip')) > 0) {

        foreach (getTabelasContabilidade() as $sCampo => $sTabela) {

          $sSqlExcluirContabilidade = "delete from {$sTabela} where {$sCampo} in (select lancamento from bkp_reprocessalancamentoslip);";
          $rsExclusao = db_query($sSqlExcluirContabilidade);
          if (!$rsExclusao) {
            throw new BusinessException("Não foi possível excluir os dados da contabilidade.\n\n".pg_last_error());
          }
        }
      }

      $sCampos  = "corrente.k12_conta,   ";
      $sCampos .= "corrente.k12_id,      ";
      $sCampos .= "corrente.k12_data,    ";
      $sCampos .= "corrente.k12_autent,  ";
      $sCampos .= "corrente.k12_valor,   ";
      $sCampos .= "corrente.k12_estorn   ";
      $sWhere   = "corlanc.k12_codigo  = $1";
      $oDaoCorLanc = new cl_corlanc();
      $sSqlBuscaAutenticacao = $oDaoCorLanc->sql_query_corrente_slip($sCampos, null, $sWhere);
      $rsPrepare = pg_prepare("pesquisa_autenticacao_slip", $sSqlBuscaAutenticacao);
      if (!$rsPrepare) {
        throw new DBException("Não foi possível preparar a query para execução.");
      }

      /**
       * Executa os lançamentos na contabilidade
       */
      foreach ($aCodigosSlip as $iCodigoSlip) {

        $rsExecutaBusca = pg_execute("pesquisa_autenticacao_slip", array($iCodigoSlip));
        $iTotalAutenticacao = pg_num_rows($rsExecutaBusca);
        if (!$rsExecutaBusca || $iTotalAutenticacao == 0) {
          throw new DBException("Não foi possível buscar a autenticação do slip {$iCodigoSlip}.\n\n".pg_last_error());
        }

        for ($iRowAutenticacao = 0; $iRowAutenticacao < $iTotalAutenticacao; $iRowAutenticacao++) {

          $oStdDadosAutenticacao = db_utils::fieldsMemory($rsExecutaBusca, $iRowAutenticacao);

          $oContaPagadora = ContaPlanoPCASPRepository::getContaByCodigo(null, $iAnoSessao, $oStdDadosAutenticacao->k12_conta, $iInstituicaoSessao);
          $oAutenticacao = new AutenticacaoTesouraria();
          $oAutenticacao->setAutenticacao($oStdDadosAutenticacao->k12_autent);
          $oAutenticacao->setTerminal($oStdDadosAutenticacao->k12_id);
          $oAutenticacao->setData(new DBDate($oStdDadosAutenticacao->k12_data));
          $oAutenticacao->setContaPagadora($oContaPagadora);
          $oAutenticacao->setValor(abs($oStdDadosAutenticacao->k12_valor));
          $oAutenticacao->setEstorno($oStdDadosAutenticacao->k12_estorn == 't' ? true : false);

          $oTransferencia = TransferenciaFactory::getInstance(null, $iCodigoSlip);
          $oTransferencia->executarLancamentoContabilidade($oAutenticacao);
        }
      }

      $oRetorno->mensagem = "Reprocessamento concluído.";

      break;

  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro = true;
  $oRetorno->mensagem = $eErro->getMessage();;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);

/**
 * Retorna uma array associativo com as tabelas da contabilidade.
 * @return array
 */
function getTabelasContabilidade() {

  $aTabelasContabilidade = array(
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
  return $aTabelasContabilidade;
}