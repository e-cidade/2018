<?
/*
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

if (! isset($arqinclude)) { // se este arquivo não esta incluido por outro
  set_time_limit(0);
  include(modification("fpdf151/pdf.php"));
  include(modification("fpdf151/assinatura.php"));
  include(modification("libs/db_sql.php"));
  include(modification("libs/db_liborcamento.php"));
  include(modification("libs/db_libcontabilidade.php"));
  include(modification("libs/db_libtxt.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("classes/db_conrelinfo_classe.php"));
  include(modification("classes/db_orcparamrel_classe.php"));
  include(modification("classes/db_empresto_classe.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("model/linhaRelatorioContabil.model.php"));
  require_once(modification("model/relatorioContabil.model.php"));
  parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
  db_postmemory($_GET);

  $classinatura  = new cl_assinatura();
  $orcparamrel   = new cl_orcparamrel();
  $clconrelinfo  = new cl_conrelinfo();
  $clempresto    = new cl_empresto();

  $anousu         = db_getsession("DB_anousu");
  $oDaoPeriodo     = db_utils::getDao("periodo");
  $iCodigoPeriodo  = $periodo;
  $sSqlPeriodo   = $oDaoPeriodo->sql_query($periodo);
  $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
  $dt            = data_periodo($anousu,$sSiglaPeriodo);
  $periodo       = $sSiglaPeriodo;


  $dt_ini = $dt [0]; // data inicial do período
  $dt_fin = $dt [1]; // data final do período
  $texto  = $dt ['texto'];
  $txtper = $dt ['periodo'];
}
$lGeraColunaRps = false;

if ($sSiglaPeriodo == '2S' || $sSiglaPeriodo == '6B') {
  $lGeraColunaRps = true;
}

$lEscreveBimestre = true;
if ($sSiglaPeriodo == '1S' || $sSiglaPeriodo == '2S') {
  $lEscreveBimestre = false;
} else {

  $dados  = data_periodo($anousu,$sSiglaPeriodo);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);

  $mesini    = strtoupper(db_mes($perini[1]));
  $mesfin    = strtoupper(db_mes($perfin[1]));
  $sBimestre = "{$mesini}/{$mesfin}";
  $lEscreveBimestre = true;
}

//  tela do relatorio
$recita1 [0]  = 'RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS (I)';
$recita1 [1]  = '  Impostos';
$recita1 [2]  = '  Multas, Juros de Mora e Divida Ativa dos Impostos';
$recita1 [3]  = '  Receita de Transferências Constitucionais e Legais';
$recita1 [4]  = '    Da União';
$recita1 [5]  = '    Do Estado';
$recita1 [6]  = 'TRANSFERÊNCIA DE RECURSOS DO SISTEMA ÚNICO DE SAÚDE - SUS (II)';
$recita1 [7]  = '  Da União para o Município';
$recita1 [8]  = '  Do Estado para o Município';
$recita1 [9]  = '  Demais Municípios para o Município';
$recita1 [10] = '  Outras Receitas do SUS';
$recita1 [11] = 'RECEITAS DE OPERAÇÕES DE CRÉDITO VINCULADAS À SAÚDE (III)';
$recita1 [12] = 'OUTRAS RECEITAS ORÇAMENTÁRIAS';
$recita1 [13] = '(-) DEDUÇÃO PARA O FUNDEB';

$despesa [0] = "DESPESAS CORRENTES";
$despesa [1] = "  Pessoal e Encargos Sociais";
$despesa [2] = "  Juros e Encargos da Dívida";
$despesa [3] = "  Outras Despesas Correntes";
$despesa [4] = "DESPESAS DE CAPITAL";
$despesa [5] = "  Investimentos";
$despesa [6] = "  Inversões Financeiras";
$despesa [7] = "  Amortização da Dívida";

$despesa2 [0] = "DESPESAS COM SAÚDE";
$despesa2 [1] = "(-)DESPESAS COM INATIVOS E PENSIONISTAS";
$despesa2 [2] = "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE";
$despesa2 [3] = "  Recursos de Transferênncias do Sistema Único de Saúde - SUS";
$despesa2 [4] = "  Recursos de Operações de Crédito";
$despesa2 [5] = "  Outros Recursos";
$despesa2 [6] = "(-)RP INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE ";
$despesa2 [7] = "RECURSOS PRÓPRIO VINCULADOS";

$subfuncao [1] = "Atenção Básica";
$subfuncao [2] = "Assistência Hospitalar e Ambulatorial";
$subfuncao [3] = "Suporte Profilático e Terapêutico";
$subfuncao [4] = "Vigilância Sanitária";
$subfuncao [5] = "Vigilância Epidemiológica";
$subfuncao [6] = "Alimentação e Nutrição ";
$subfuncao [7] = "Outras Subfunções";

$contas [0] = "(-)DESPESAS COM INATIVOS E PENCIONISTAS";
$contas [1] = "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE";
$contas [2] = "  Recursos de Transferênncias do Sistema Único de Saúde - SUS";
$contas [3] = "  Recursos de Operações de Crédito";
$contas [4] = "  Outros Recursos";

$iCodigoRelatorio   = 85;
$sListaInstituicoes = db_getsession("DB_instit");
///////////////////////////////////////////////////////////////////
// 15 linhas de receita

$aReceitasIgnorar = array(0,3,6);
$iParam = 1;
for ($iLinha = 0; $iLinha <= 13; $iLinha++) {

  $iIndiceLinha = $iLinha;
  $receitas_previni[$iIndiceLinha] = 0;
  $receitas_prevatu[$iIndiceLinha] = 0;
  $receitas_atebime[$iIndiceLinha] = 0;
  $receitas_nobimes[$iIndiceLinha] = 0;

  if (in_array($iIndiceLinha, $aReceitasIgnorar)) {
    continue;
  } else {

    $rec[$iLinha] = new linhaRelatorioContabil($iCodigoRelatorio, $iParam);
    $rec[$iLinha]->setPeriodo($iCodigoPeriodo);
    $rec[$iLinha]->parametro = $rec[$iLinha]->getParametros($anousu);
    $aColunas  = $rec[$iLinha]->getValoresSomadosColunas($sListaInstituicoes, $anousu);
    foreach ($aColunas as $oLinha) {

      $receitas_previni[$iLinha] += @$oLinha->colunas[1]->o117_valor;
      $receitas_prevatu[$iLinha] += @$oLinha->colunas[2]->o117_valor;
      $receitas_atebime[$iLinha] += @$oLinha->colunas[3]->o117_valor;
    }
    $iParam++;
  }
}
// DESPESAS COM SAUDE
// PESSOAL E ENCARGOS SOCIAIS
$iParam = 12;
for ($iLinha = 1; $iLinha <= 6; $iLinha++) {

  $desp[$iLinha]['previni']         = 0;
  $desp[$iLinha]['prevatu']         = 0;
  $desp[$iLinha]['rpNaoProcessado'] = 0;
  $desp[$iLinha]['bimestre']        = 0;

  $desp[$iLinha]['linha'] = new linhaRelatorioContabil($iCodigoRelatorio, $iParam);
  $desp[$iLinha]['linha']->setPeriodo($iCodigoPeriodo);
  $desp[$iLinha]['linha']->parametro = $desp[$iLinha]["linha"]->getParametros($anousu);
  $aLinhas = $desp[$iLinha]['linha']->getValoresSomadosColunas($sListaInstituicoes, $anousu);
  foreach ($aLinhas as $oLinha) {

    $desp[$iLinha]['previni']         += @$oLinha->colunas[1]->o117_valor;
    $desp[$iLinha]['prevatu']         += @$oLinha->colunas[2]->o117_valor;
    $desp[$iLinha]['bimestre']        += @$oLinha->colunas[3]->o117_valor;
    if ($lGeraColunaRps) {
      $desp[$iLinha]['rpNaoProcessado'] += @$oLinha->colunas[4]->o117_valor;
    }
  }
  $iParam++;

}


// (-) Depesas proprias
// (-) DESPESAS COM INATIVOS E PENSIONISTAS
$iParam               = 18;
$VARIAVEL_COMPENSACAO = 0;
$nValorOutrosRecursos = 0;
for ($iLinha = 1; $iLinha <= 5; $iLinha++) {

  $desp_p[$iLinha]['linha'] = new linhaRelatorioContabil($iCodigoRelatorio, $iParam);
  $desp_p[$iLinha]['linha']->setPeriodo($iCodigoPeriodo);
  $desp_p[$iLinha]['linha']->parametro = $desp_p[$iLinha]["linha"]->getParametros($anousu);
  $desp_p[$iLinha]['previni']          = 0;
  $desp_p[$iLinha]['prevatu']          = 0;
  $desp_p[$iLinha]['rpNaoProcessado']  = 0;
  $desp_p[$iLinha]['bimestre']         = 0;
  $aLinhas = $desp_p[$iLinha]['linha']->getValoresSomadosColunas($sListaInstituicoes, $anousu);
  foreach ($aLinhas as $oLinha) {

    $desp_p[$iLinha]['previni']         += @$oLinha->colunas[1]->o117_valor;
    $desp_p[$iLinha]['prevatu']         += @$oLinha->colunas[2]->o117_valor;
    $desp_p[$iLinha]['bimestre']        += @$oLinha->colunas[3]->o117_valor;
    if ($lGeraColunaRps) {
      $desp_p[$iLinha]['rpNaoProcessado'] += @$oLinha->colunas[4]->o117_valor;
    }
    if ($iLinha == 5) {
      $VARIAVEL_COMPENSACAO += @$oLinha->colunas[3]->o117_valor;
    }
  }
  $iParam++;
}

// -------------------------------------------------------------------
// RESTOS A PAGAR COM DISPONIBILIDADE FINANCEIRA

$sele_work = " o58_instit in ({$sListaInstituicoes})";
$result_despesa = db_dotacaosaldo(8, 2, 3, true, $sele_work, $anousu, $dt_ini, $dt_fin);


$db_filtro = " o70_instit in ({$sListaInstituicoes})";
$result_rec = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
@ db_query("drop table work_receita");

//db_criatabela($result_despesa);exit;


// saldo dos rps inscritos e cancelados da saude
$oLinhaRp = new linhaRelatorioContabil($iCodigoRelatorio, 23);
$oParametro = $oLinhaRp->getParametros($anousu);
$m_rp [1] ['funcao']  = $oParametro->orcamento->funcao->valor;
$m_rp [1] ['subfunc'] = $oParametro->orcamento->subfuncao->valor;
$m_rp [1] ['recurso'] = $oParametro->orcamento->recurso->valor;

$v_funcao = '0';
$v_subfunc = '0';
$v_codigo = '0';
$sp = '';

foreach ( $m_rp [1] ['funcao'] as $registro ) {
  $v_funcao .= $sp . $registro;
  $sp = ',';
}

$sp = '';
foreach ( $m_rp [1] ['subfunc'] as $registro ) {
  $v_subfunc .= $sp . $registro;
  $sp = ',';
}

$sp = '';
foreach ( $m_rp [1] ['recurso'] as $registro ) {
  $v_codigo .= $sp . $registro;
  $sp = ',';
}

$sele_work = " e60_instit in ({$sListaInstituicoes})";
$sele_work1       = " and e91_recurso in ($v_codigo)";
$sql_where_externo = " where $sele_work ";
$sql_order = " order by e91_recurso, e91_numemp ";

$dt_ini2 = $anousu . "-01-01";

$sqlperiodo  = $clempresto->sql_rp_novo($anousu, $sele_work, $dt_ini2, $dt_fin, $sele_work1, $sql_where_externo, "$sql_order ");
$result_restos_mde1 = @db_query($sqlperiodo);
$numrows_restos_mde1 = @pg_numrows($result_restos_mde1);

$saldo     = 0;
$cancelado = 0;
for($i = 0; $i < pg_numrows($result_restos_mde1); $i ++) {
  db_fieldsmemory($result_restos_mde1, $i);

//  Total de RP processado .....:  ($e91_vlrliq - $e91_vlrpag);
//  Total de RP não processado .:  ($e91_vlremp - $e91_vlranu - $e91_vlrliq);

  $saldo     += (($e91_vlrliq - $e91_vlrpag) + ($e91_vlremp - $e91_vlranu - $e91_vlrliq));

  $cancelado += $vlranuliq;

}

// DESPESAS POR SUBFUNCAO
$iParam = 24;
for ($i = 1; $i <= 7; $i++) {

  $m_desp_subfunc[$i]['estrut'] = new linhaRelatorioContabil($iCodigoRelatorio, $iParam);
  $m_desp_subfunc[$i]['estrut']->setPeriodo($iCodigoPeriodo);
  $m_desp_subfunc[$i]['estrut']->parametro =$m_desp_subfunc[$i]['estrut']->getParametros($anousu);
  $m_desp_subfunc[$i]['inicial']    = 0;
  $m_desp_subfunc[$i]['atualizada'] = 0;
  $m_desp_subfunc[$i]['bimestre']   = 0;
  $m_desp_subfunc[$i]['restos']     = 0;
  $aLinhas = $m_desp_subfunc[$i]['estrut']->getValoresSomadosColunas($sListaInstituicoes, $anousu);
  foreach ($aLinhas as $oLinha) {

    $m_desp_subfunc[$i]['inicial']    += $oLinha->colunas[1]->o117_valor;
    $m_desp_subfunc[$i]['atualizada'] += $oLinha->colunas[2]->o117_valor;
    $m_desp_subfunc[$i]['bimestre']   += $oLinha->colunas[3]->o117_valor;
    if ($lGeraColunaRps) {
      $m_desp_subfunc[$i]['restos']   += $oLinha->colunas[4]->o117_valor;
    }
  }
  $iParam++;
}
//-------------------------------------------------RECEITAS-----------------------------------------
$total_rec_ini    = 0;
$total_rec_atu    = 0;
$total_rec_atebim = 0;
$iTotalLinhasReceita = pg_num_rows($result_rec);
// RECEITA DE IMPOSTOS LIQUIDA [1...4] + TOTAL DAS RECEITAS[0]

for ($i = 0; $i < $iTotalLinhasReceita; $i ++) {


  $oReceita   = db_utils::fieldsmemory($result_rec, $i);
  for ($p = 0; $p <= 13; $p ++) {

   if (in_array($p, $aReceitasIgnorar)) {
      continue;
    }
    $estrutural = $oReceita->o57_fonte;
    $oParametro = $rec[$p]->parametro;
    $oReceitaCalcular = clone $oReceita;
    foreach ($oParametro->contas as $oEstrutural) {

      $oVerificacao  = $rec[$p]->match($oEstrutural ,$oParametro->orcamento, $oReceitaCalcular, 1);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oReceitaCalcular->saldo_inicial              *=-1;
          $oReceitaCalcular->saldo_inicial_prevadic     *=-1;
          $oReceitaCalcular->saldo_arrecadado_acumulado *=-1;
          $oReceitaCalcular->saldo_arrecadado           *=-1;
        }
        $receitas_previni[$p] += $oReceitaCalcular->saldo_inicial;
        $receitas_prevatu[$p] += $oReceitaCalcular->saldo_inicial_prevadic;
        $receitas_atebime[$p] += $oReceitaCalcular->saldo_arrecadado_acumulado;
        $receitas_nobimes[$p] += $oReceitaCalcular->saldo_arrecadado;
      }
    }
    unset ($oReceitaCalcular);
  }
  /**
   * calcula o valor para a linha , da despesa
   */
}


//echo "<pre>";
//print_r($receitas_previni);
//echo "</pre>";
//exit;
// TOTAL DAS RECEITA DE IMPOSTOS LIQUIDA
$receitas_previni [3] = $receitas_previni [4] + $receitas_previni [5];
$receitas_prevatu [3] = $receitas_prevatu [4] + $receitas_prevatu [5];
$receitas_atebime [3] = $receitas_atebime [4] + $receitas_atebime [5];
$receitas_nobimes [3] = $receitas_nobimes [4] + $receitas_nobimes [5];

// TOTAL DAS RECEITA/TRANSFERENCIAS
$receitas_previni [0] = $receitas_previni [1] + $receitas_previni [2] ;//+ 10 ;//+ $receitas_previni [3] + $receitas_previni [4];
$receitas_prevatu [0] = $receitas_prevatu [1] + $receitas_prevatu [2] ;//+ $receitas_prevatu [3] + $receitas_prevatu [4];
$receitas_atebime [0] = $receitas_atebime [1] + $receitas_atebime [2] ;//+ $receitas_atebime [3] + $receitas_atebime [4];
$receitas_nobimes [0] = $receitas_nobimes [1] + $receitas_nobimes [2] ;//+ $receitas_nobimes [3] + $receitas_nobimes [4];

// TOTAL DAS RECEITAS/TRANSFERENCIAS CONSTITUCIONAIS (I)
$receitas_previni [0] += $receitas_previni [3];
$receitas_prevatu [0] += $receitas_prevatu [3];
$receitas_atebime [0] += $receitas_atebime [3];
$receitas_nobimes [0] += $receitas_nobimes [3];

// TOTAL DAS TRANSFERENCIA DO SUS (II)
$receitas_previni [6] = $receitas_previni [7] + $receitas_previni [8] + $receitas_previni [9] + $receitas_previni [10];
$receitas_prevatu [6] = $receitas_prevatu [7] + $receitas_prevatu [8] + $receitas_prevatu [9] + $receitas_prevatu [10];
$receitas_atebime [6] = $receitas_atebime [7] + $receitas_atebime [8] + $receitas_atebime [9] + $receitas_atebime [10];
$receitas_nobimes [6] = $receitas_nobimes [7] + $receitas_nobimes [8] + $receitas_nobimes [9] + $receitas_nobimes [10];
//------------------------------------------------- Despesas -----------------------------------------

///db_criatabela($result_despesa);exit;
$iTotalLinhasDespesa = pg_num_rows($result_despesa);
for($i = 0; $i < $iTotalLinhasDespesa; $i ++) {

  $oDespesa = db_utils::fieldsmemory($result_despesa, $i);

  for($linha = 1; $linha <= 6; $linha ++) {

    $oParametro  = $desp[$linha]["linha"]->parametro;
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $desp[$linha]["linha"]->match($oConta,$oParametro->orcamento,$oDespesa, 2);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oDespesa->dot_ini;
          $oDespesa->suplementado_acumulado *= -1;
          $oDespesa->reduzido_acumulado     *= -1;
          $oDespesa->empenhado_acumulado    *= -1;
          $oDespesa->anulado_acumulado      *= -1;
          $oDespesa->liquidado_acumulado    *= -1;
        }

        $desp[$linha]['previni']         += $oDespesa->dot_ini;
        $desp[$linha]['prevatu']         += $oDespesa->dot_ini +
                                            ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
        $desp[$linha]['rpNaoProcessado'] += abs(round($oDespesa->empenhado_acumulado -
                                                      $oDespesa->anulado_acumulado -
                                                      $oDespesa->liquidado_acumulado,2)
                                                );
        $desp[$linha]['bimestre']        += $oDespesa->liquidado_acumulado;

      }
    }
  }

  for($linha = 1; $linha <= 4; $linha ++) {

    $oParametro  = $desp_p[$linha]["linha"]->parametro;
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $desp_p[$linha]["linha"]->match($oConta,$oParametro->orcamento,$oDespesa, 2);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oDespesa->dot_ini;
          $oDespesa->suplementado_acumulado *= -1;
          $oDespesa->reduzido_acumulado     *= -1;
          $oDespesa->empenhado_acumulado    *= -1;
          $oDespesa->anulado_acumulado      *= -1;
          $oDespesa->liquidado_acumulado    *= -1;
        }

        $desp_p[$linha]['previni']         += $oDespesa->dot_ini;
        $desp_p[$linha]['prevatu']         += $oDespesa->dot_ini +
                                            ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
        $desp_p[$linha]['rpNaoProcessado'] += abs(round($oDespesa->empenhado_acumulado -
                                                      $oDespesa->anulado_acumulado -
                                                      $oDespesa->liquidado_acumulado,2)
                                                );
        $desp_p[$linha]['bimestre']        += $oDespesa->liquidado_acumulado;

      }
    }

  }
}

//------------------------------------funcao e subfuncao------------------------------------------------------------------------
$total_acum = 0;
$total_ini  = 0;
$total_atu  = 0;
$total_rp   = 0;

for($i = 0; $i < pg_numrows($result_despesa); $i ++) {

  $oDespesa = db_utils::fieldsmemory($result_despesa, $i);
  for ($iLinha = 1; $iLinha <= 7; $iLinha++) {
    $oParametro  = $m_desp_subfunc[$iLinha]["estrut"]->parametro;
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $m_desp_subfunc[$iLinha]["estrut"]->match($oConta,$oParametro->orcamento,$oDespesa, 2);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oDespesa->dot_ini;
          $oDespesa->suplementado_acumulado *= -1;
          $oDespesa->reduzido_acumulado     *= -1;
          $oDespesa->empenhado_acumulado    *= -1;
       }

       $m_desp_subfunc[$iLinha]['inicial']    += $oDespesa->dot_ini;
       $m_desp_subfunc[$iLinha]['atualizada'] += $oDespesa->dot_ini +
                                                ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
       $m_desp_subfunc[$iLinha]['restos']     += abs(round($oDespesa->empenhado_acumulado -
                                                      $oDespesa->anulado_acumulado -
                                                        $oDespesa->liquidado_acumulado,2)
                                                  );
       $m_desp_subfunc[$iLinha]['bimestre']    += $oDespesa->liquidado_acumulado;

      }
    }
  }
}
for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

  $total_ini  += $m_desp_subfunc[$iLinha]['inicial'];
  $total_atu  += $m_desp_subfunc[$iLinha]['atualizada'];
  $total_acum += $m_desp_subfunc[$iLinha]['bimestre'];
  $total_rp   += $m_desp_subfunc[$iLinha]['restos'];
}
$desp_p[4]['bimestre'] += $nValorOutrosRecursos;
/////////////////////////////////////////////////////////////////////////////////
$n1 = 5;
$n2 = 10;

// end se incluido em outro arquivo

$resultinst = db_query("select munic from db_config where codigo in ({$sListaInstituicoes}) ");
$descr_inst = '';
db_fieldsmemory($resultinst, 0);
$descr_inst = $munic;

$vdt_fin = split("-", $dt_fin);

$head1 = "MUNICÍPIO DE " . strtoupper($descr_inst);
$head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head3 = "DEMONSTRATIVO DA RECEITA DE IMPOSTOS LÍQUIDA E DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE";
$head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head5 = db_mes("01", 1) . " A " . db_mes($vdt_fin [1], 1) . "/" . $anousu;
if ($lEscreveBimestre) {
  $head5 .= " / BIMESTRE: " . @$sBimestre;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();
$alt = 3;

$iLarguraColunaDescr = 110;
if ($lGeraColunaRps) {
  $iLarguraColunaValor = 16;
  $iNumcols = 3;
  $iTamanhoFonte = 5;
} else {
  $iLarguraColunaValor = 20;
  $iNumcols = 2;
  $iTamanhoFonte = 6;
}

$pdf->setfont('arial', '', $iTamanhoFonte);

// RECEITAS
$pdf->setX(10);
$pdf->cell(170, $alt, "RREO - ANEXO XVI(ADCT, art. 77)", "B", 0, "L", 0);
$pdf->cell(20, $alt, "R$ 1,00", "B", 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "", "T", 0, "R", 0);
$pdf->cell(20, $alt, "PREVISÃO", "TRL", 0, "C", 0);
$pdf->cell(20, $alt, "PREVISÃO", "TRL", 0, "C", 0);
$pdf->cell(40, $alt, "RECEITAS REALIZADAS", "TL", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "RECEITAS", "B", 0, "C", 0);
$pdf->cell(20, $alt, "INICIAL ", "BL", 0, "C", 0);
$pdf->cell(20, $alt, "ATUALIZADA (a)", "BL", 0, "C", 0);
$pdf->cell(20, $alt, "Até o " . $txtper . " (b)", "TBL", 0, "C", 0);
$pdf->cell(20, $alt, "% (b/a) *100", "LTB", 1, "C", 0);
$alt = 3;

for($i = 0; $i <= 13; $i ++) {

  $pdf->cell($iLarguraColunaDescr, $alt, $recita1 [$i], "", 0, "L", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_previni [$i], 'f'), "L", 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_prevatu [$i], 'f'), "L", 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_atebime [$i], 'f'), "L", 0, "R", 0);
  if ($receitas_prevatu [$i] != 0) {
    $pdf->cell(20, $alt, db_formatar((($receitas_atebime [$i] / $receitas_prevatu [$i]) * 100), 'f'), "L", 1, "R", 0);
  } else {
    $pdf->cell(20, $alt, db_formatar(0, 'f'), "L", 1, "R", 0);
  }
}

// TOTAL GERAL (I) + (II) + (III) + OUTRAS REC. ORCAMENTARIAS - DEDUCOES
$total_previni = ($receitas_previni [0] + $receitas_previni [6] + $receitas_previni [11] + $receitas_previni [12]) - abs($receitas_previni [13]);
$total_prevatu = ($receitas_prevatu [0] + $receitas_prevatu [6] + $receitas_prevatu [11] + $receitas_prevatu [12]) - abs($receitas_prevatu [13]);
$total_atebime = ($receitas_atebime [0] + $receitas_atebime [6] + $receitas_atebime [11] + $receitas_atebime [12]) - abs($receitas_atebime [13]);

$total_nobimes = " - ";
$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL", "TB", 0, "L", 0);
$pdf->cell(20, $alt, db_formatar($total_previni, 'f'), "TBL", 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($total_prevatu, 'f'), "TBL", 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($total_atebime, 'f'), "TBL", 0, "R", 0);
if ($total_prevatu > 0) {
  $pdf->cell(20, $alt, db_formatar(($total_atebime / $total_prevatu) * 100, 'f'), "TBL", 0, "R", 0);
} else {
  $pdf->cell(20, $alt, db_formatar(0, 'f'), "TBL", 0, "R", 0);
}

$total_I_atebim = $receitas_atebime [0];

// ------------------------  despesas --------------------------

$pdf->ln(4);
$sD2 = "";
$sF2 = "";

$aDescricao['A1'] = "DESPESA COM SAÚDE";
$aDescricao['B1'] = "DOTAÇÃO";
$aDescricao['C1'] = "DOTAÇÃO";
$aDescricao['D1'] = "DESPESAS LIQUIDADAS";

$aDescricao['A2'] = "(Por Grupo de Natureza da Despesa)";
$aDescricao['B2'] = "INICIAL";
$aDescricao['C2'] = "ATUALIZADA (c)";
if (!$lGeraColunaRps){
  $sD2 = "(d)";
}
$aDescricao['D2'] = "Até o " . $txtper . " {$sD2}";
$aDescricao['E2'] = "INSCRITAS EM RP";
if (!$lGeraColunaRps){
  $sF2 = "(d/c) * 100";
}
$aDescricao['F2'] = "% {$sF2}";

$aDescricao['A3'] = "";
$aDescricao['B3'] = "";
$aDescricao['C3'] = "";
$aDescricao['D3'] = "";
$aDescricao['E3'] = "NÃO PROC.";
$aDescricao['F3'] = "";

$aDescricao['A4'] = "";
$aDescricao['B4'] = "";
$aDescricao['C4'] = "(c)";
$aDescricao['D4'] = "(d)";
$aDescricao['E4'] = "(e)";
$aDescricao['F4'] = "((d+e)/c)*100";

cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lGeraColunaRps);

$aTotalDespCorrente['previni']         = ( $desp['1']['previni']  + $desp ['2']['previni'] + $desp['3']['previni'] );
$aTotalDespCorrente['prevatu']         = ( $desp['1']['prevatu']  + $desp ['2']['prevatu'] + $desp['3']['prevatu'] );
$aTotalDespCorrente['bimestre']        = ( $desp['1']['bimestre'] + $desp ['2']['bimestre'] + $desp['3']['bimestre'] );
$aTotalDespCorrente['rpNaoProcessado'] = ( $desp['1']['rpNaoProcessado'] + $desp ['2']['rpNaoProcessado'] + $desp['3']['rpNaoProcessado'] );

$pdf->cell($iLarguraColunaDescr, $alt, "DESPESAS CORRENTES", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt,  db_formatar( $aTotalDespCorrente['previni'] , 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt,  db_formatar( $aTotalDespCorrente['prevatu'] , 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt,  db_formatar( $aTotalDespCorrente['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt,db_formatar($aTotalDespCorrente['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  $nTotalPercCorrente = ( ( ( $aTotalDespCorrente['bimestre'] + $aTotalDespCorrente['rpNaoProcessado'] ) * 100 ) / $aTotalDespCorrente['prevatu'] );
}else{
@  $nTotalPercCorrente = ( ( $aTotalDespCorrente['bimestre'] * 100 ) / @$aTotalDespCorrente['prevatu'] );
}

$pdf->cell($iLarguraColunaValor, $alt, db_formatar( $nTotalPercCorrente, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [1], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['1']['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['1']['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['1']['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['1']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercCorPessoalEncargos = ( ( ( $desp ['1']['bimestre'] + $desp['1']['rpNaoProcessado'] ) * 100 ) / $desp['1']['prevatu'] );
}else{
  @$nPercCorPessoalEncargos = ( ($desp ['1']['bimestre'] * 100 ) / $desp['1']['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercCorPessoalEncargos, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [2], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['2']['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['2']['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['2']['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['2']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercJurosEncDivida = ( ( ( $desp['2']['bimestre'] + $desp['2']['rpNaoProcessado'] ) * 100 ) / $desp ['2'] ['prevatu']);
}else{
  @$nPercJurosEncDivida = ( ($desp['2']['bimestre'] * 100 ) / $desp ['2'] ['prevatu']);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercJurosEncDivida, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [3], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp ['3'] ['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp ['3'] ['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp ['3'] ['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['3']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercOutrasDespCor = ( ( ( $desp['3']['bimestre'] + $desp['3']['rpNaoProcessado'] ) * 100 ) / $desp['3']['prevatu'] );
}else{
  @$nPercOutrasDespCor = ( ($desp['3']['bimestre'] * 100 ) / $desp['3']['prevatu'] );
}

$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercOutrasDespCor, 'f'), 'L', 1, "R", 0);

$aTotalDespCapital['previni']         = ( $desp['4']['previni'] + $desp['5']['previni'] + $desp['6']['previni'] ) ;
$aTotalDespCapital['prevatu']         = ( $desp['4']['prevatu'] + $desp['5']['prevatu'] + $desp['6']['prevatu'] );
$aTotalDespCapital['bimestre']        = ( $desp['4']['bimestre'] + $desp['5']['bimestre'] + $desp['6']['bimestre'] );
$aTotalDespCapital['rpNaoProcessado'] = ( $desp['4']['rpNaoProcessado'] + $desp['5']['rpNaoProcessado'] + $desp['6']['rpNaoProcessado'] );

$pdf->cell($iLarguraColunaDescr, $alt, "DESPESAS DE CAPITAL", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCapital['previni'],  'f'),"L",0,"R",0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCapital['prevatu'],  'f'),"L",0,"R",0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCapital['bimestre'], 'f'),"L",0,"R",0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCapital['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nTotalPercDespCapital = ( ( ( $aTotalDespCapital['bimestre'] + $aTotalDespCapital['rpNaoProcessado'] ) * 100 ) / $aTotalDespCapital['prevatu'] );
}else{
  @$nTotalPercDespCapital = ( ( $aTotalDespCapital['bimestre']  * 100 ) / $aTotalDespCapital['prevatu'] );
}

$pdf->cell($iLarguraColunaValor, $alt, db_formatar( $nTotalPercDespCapital, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [5], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['4']['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['4']['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['4']['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['4']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercInvest = ( ( ( $desp['4']['bimestre'] + $desp['4']['rpNaoProcessado'] ) * 100 ) / $desp['4']['prevatu'] );
}else{
  @$nPercInvest = ( ( $desp['4']['bimestre'] * 100 ) / $desp['4']['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar( $nPercInvest, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [6], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['5']['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['5']['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['5']['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['5']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercInvFin = ( ( ( $desp['5']['bimestre'] + $desp['5']['rpNaoProcessado'] ) * 100 ) / $desp['5']['prevatu'] );
}else{
  @$nPercInvFin = ( ( $desp['5']['bimestre'] * 100 ) / $desp['5']['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercInvFin, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, $despesa [7], "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['6']['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['6']['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['6']['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp['6']['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercRpExeFin = ( ( ( $desp['6']['bimestre'] + $desp['6']['rpNaoProcessado'] ) * 100 ) / $desp['6']['prevatu'] );
}else{
  @$nPercRpExeFin = ( ( $desp['6']['bimestre'] * 100 ) / $desp['6']['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercRpExeFin, 'f'), 'L', 1, "R", 0);

$total_IV_ini    = ( $aTotalDespCorrente['previni']  + $aTotalDespCapital['previni'] );   // $desp['1']['previni'] + $desp['2']['previni'] + $desp['3']['previni'] + $desp['4']['previni'] + $desp['5']['previni'] + $desp['6']['previni'];
$total_IV_atu    = ( $aTotalDespCorrente['prevatu']  + $aTotalDespCapital['prevatu'] );   // $desp['1']['prevatu'] + $desp['2']['prevatu'] + $desp['3']['prevatu'] + $desp['4']['prevatu'] + $desp['5']['prevatu'] + $desp['6']['prevatu'];
$total_IV_atebim = ( $aTotalDespCorrente['bimestre'] + $aTotalDespCapital['bimestre'] );  // $desp['1']['bimestre'] + $desp['2']['bimestre'] + $desp['3']['bimestre'] + $desp['4']['bimestre'] + $desp['5']['bimestre'] + $desp['6']['bimestre'];
$total_IV_rp     = ( $aTotalDespCorrente['rpNaoProcessado'] + $aTotalDespCapital['rpNaoProcessado'] ); // $desp['1']['rpNaoProcessado'] + $desp['2']['rpNaoProcessado'] + $desp['3']['rpNaoProcessado'] + $desp['4']['rpNaoProcessado'] + $desp['5']['rpNaoProcessado'] + $desp['6']['rpNaoProcessado'];

if ($lGeraColunaRps){
  //$total_IV_atebim += $total_IV_rp;
} else {
  $total_IV_rp = 0;
}

$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL (IV)", "TB", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_ini, 'f'), "TBL", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atu, 'f'), "TBLR", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, "", "TB", 0, "R", 0);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atebim+$total_IV_rp, 'f'), "TB", 0, "R", 0);
@$nPercTotalIV = ( ( $total_IV_atebim * 100 ) / $total_IV_atu+$total_IV_rp );
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercTotalIV, 'f'), "LTB", 1, "R", 0);

// ------------------------------- * --------------------------- * ------------------------------- *  --------------

$pdf->ln(4);
$sF2 = "";
$sD2 = "";

$aDescricao['A1'] = "";
$aDescricao['D1'] = "DESPESAS LIQUIDADAS";
$aDescricao['A2'] = "DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE";
$aDescricao['C2'] = "ATUALIZADA";
if (!$lGeraColunaRps) {
  $sD2 = "(e)";
}
$aDescricao['D2'] = "Até o $txtper {$sD2}";
if (!$lGeraColunaRps) {
  $sF2 = "(e/V e)*100";
}
$aDescricao['F2'] = "% {$sF2}";


$aDescricao['A4'] = "";
$aDescricao['B4'] = "";
$aDescricao['C4'] = "";
$aDescricao['D4'] = "(f)";
$aDescricao['E4'] = "(g)";
$aDescricao['F4'] = "((f+g)/(f+g)g)*100";

cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lGeraColunaRps);

if ($desp_p[2]['previni'] == 0 && $desp_p[2]['prevatu'] == 0 && $desp_p[2]['bimestre'] == 0) {

  $desp_p[2]['previni']  = $receitas_previni[6];
  $desp_p[2]['prevatu']  = $receitas_prevatu[6];
  $desp_p[2]['bimestre'] = $receitas_atebime[6];
}

if ($desp_p[3]['previni'] == 0 && $desp_p[3]['prevatu'] == 0 && $desp_p[3]['bimestre'] == 0) {

  $desp_p[3]['previni']  = $receitas_previni[11];
  $desp_p[3]['prevatu']  = $receitas_prevatu[11];
  $desp_p[3]['bimestre'] = $receitas_atebime[11];
}

$pdf->cell($iLarguraColunaDescr, $alt, "DESPESAS COM SAÚDE (V) = (IV)", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_ini, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atu, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atebim, 'f'), "L", 0, "R", 0);
$total_IV_atebimfinal = $total_IV_atebim;
if ($lGeraColunaRps) {

  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_rp, 'f'), "L", 0, "R", 0);
//  $pdf->cell($iLarguraColunaValor, $alt, "sdfsddsf", "L", 0, "R", 0);

  @$nTotalPercIV = ( ( ( $total_IV_atebim  ) * 100 ) / $total_IV_atebim);
}else{
  @$nTotalPercIV = ($total_IV_atebim * 100) / $total_IV_atebim;
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nTotalPercIV, 'f'), "L", 1, "R", 0);
if ($lGeraColunaRps) {
  $total_IV_atebim += $total_IV_rp;
}
$pdf->cell($iLarguraColunaDescr, $alt, "(-) DESPESAS COM INATIVOS E PENSIONISTAS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['previni'],'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['prevatu'],'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['bimestre'],'f'), "L", 0, "R", 0);

if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercInatPen = ( ( ( $desp_p[1]['bimestre'] + $desp_p[1]['rpNaoProcessado'] ) * 100 ) / $total_IV_atebim );
}else{
  @$nPercInatPen = ( ( $desp_p[1]['bimestre'] * 100 ) / $total_IV_atebim );
}

$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercInatPen, 'f'), 'L', 1, "R", 0);

// caso a linha abaixo seja zerada,  o manual diz que os valores devem ser pegos do quadro da receita
// RRO , 4 Ed, pg 278

$aTotalDespCustRecSaude['previni']         = ( $desp_p[2]['previni']         + $desp_p[3]['previni']         + $desp_p[4]['previni'] );
$aTotalDespCustRecSaude['prevatu']         = ( $desp_p[2]['prevatu']         + $desp_p[3]['prevatu']         + $desp_p[4]['prevatu'] );
$aTotalDespCustRecSaude['bimestre']        = ( $desp_p[2]['bimestre']        + $desp_p[3]['bimestre']        + $desp_p[4]['bimestre'] );
$aTotalDespCustRecSaude['rpNaoProcessado'] = ( $desp_p[2]['rpNaoProcessado'] + $desp_p[3]['rpNaoProcessado'] + $desp_p[4]['rpNaoProcessado'] );

$iDespCusteadaOutrosRecursosDotIni  = $desp_p[2]['previni']  + $desp_p[4]['previni'];
$iDespCusteadaOutrosRecursosDotAt   = $desp_p[2]['prevatu']  + $desp_p[4]['prevatu'];
$iDespCusteadaOutrosRecursosAteSem  = $desp_p[2]['bimestre'] + $desp_p[4]['bimestre'];
$iDespCusteadaOutrosRecursosNaoProc = $desp_p[2]['rpNaoProcessado'] + $desp_p[3]['rpNaoProcessado'] + $desp_p[4]['rpNaoProcessado'];

$pdf->cell($iLarguraColunaDescr, $alt, "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['previni'], 'f'), "L", 0, "R", 0); // valor vindo errado (corrigido)
//$pdf->cell($iLarguraColunaValor, $alt, db_formatar($iDespCusteadaOutrosRecursosDotIni, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['prevatu'], 'f'), "L", 0, "R", 0);
//$pdf->cell($iLarguraColunaValor, $alt, db_formatar($iDespCusteadaOutrosRecursosDotAt, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['bimestre'],'f'), "L", 0, "R", 0);
//$pdf->cell($iLarguraColunaValor, $alt, db_formatar($iDespCusteadaOutrosRecursosAteSem,'f'), "L", 0, "R", 0);
$descustoutrecsau = $aTotalDespCustRecSaude['bimestre'];
if ($lGeraColunaRps) {

  //$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['rpNaoProcessado'], 'f'), "L", 0, "R", 0); // se for semestre
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($iDespCusteadaOutrosRecursosNaoProc, 'f'), "L", 0, "R", 0);

  @$nPercDespCustRecSaude = ( ( ( $aTotalDespCustRecSaude['bimestre'] + $aTotalDespCustRecSaude['rpNaoProcessado'] ) * 100 ) / $total_IV_atebim );

}else{

  @$nPercDespCustRecSaude = ( ( $aTotalDespCustRecSaude['bimestre'] * 100 ) / $total_IV_atebim );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercDespCustRecSaude, 'f'), 'L', 1, "R", 0);           // Total a Verificar

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Recursos de Transferências do Sistema Único de Saúde - SUS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['previni'], 'f'), "L", 0, "R", 0);              // valor A1
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['prevatu'], 'f'), "L", 0, "R", 0);              // Valor A2
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['bimestre'], 'f'), "L", 0, "R", 0);              // Valor A3
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);   // Valor A4
  @$nPercTransSUS = ( ( ( $desp_p[2]['bimestre'] + $desp_p[2]['rpNaoProcessado'] ) * 100 ) / $total_IV_atebim );
}else{
  @$nPercTransSUS = ( ( $desp_p[2]['bimestre']* 100) / $total_IV_atebim );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercTransSUS, 'f'), 'L', 1, "R", 0);                    // Valor A5

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Recursos de Operações de Crédito", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['previni'], 'f'), "L", 0, "R", 0);             // valor B1
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['prevatu'], 'f'), "L", 0, "R", 0);             // Valor B2
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['bimestre'], 'f'), "L", 0, "R", 0);             // Valor B3
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['rpNaoProcessado'], 'f') , "L", 0, "R", 0);  // Valor B4
  @$nPercOperCred = ( ( ( $desp_p[3]['bimestre'] + $desp_p[3]['rpNaoProcessado'] ) * 100 ) / $total_IV_atebim );
}else{
  @$nPercOperCred = (($desp_p[3]['bimestre'] * 100) / $total_IV_atebim);
}
//echo "({$desp_p[3]['bimestre']} * 100) / {$total_IV_atebim});";
//exit;
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercOperCred, 'f'), 'L', 1, "R", 0);                    // valor B5

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Outros Recursos", "0", 0,  "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['previni'], 'f'),  "L", 0, "R", 0);            // valor C1
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['prevatu'], 'f'),  "L", 0, "R", 0);            // Valor C2
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['bimestre'], 'f'), "L", 0, "R", 0);            // Valor C3
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);   // Valor C4
  @$nPercOutroRec = ( ( $desp_p[4]['bimestre'] + $desp_p[4]['rpNaoProcessado'] * 100 ) / $total_IV_atebim);
}else{
  @$nPercOutroRec = ( ( $desp_p[4]['bimestre'] * 100 ) / $total_IV_atebim );
}
@$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0); // Valor C5

$perc_rp = 0;
@ $perc_rp = ($VARIAVEL_COMPENSACAO) * 100 / $total_IV_atebim;

$pdf->cell($iLarguraColunaDescr, $alt, "(-)RP INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE REC.PROPRIOS VINCULADOS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, '-', "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, '-', "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($VARIAVEL_COMPENSACAO, 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, '',"LR", 0, "R", 0);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($perc_rp, "f"), 'L', 1, "R", 0);


$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE(VI)", "TB", 0, "L", 0);

$total_V_ini    = 0 + $total_IV_ini - ($desp_p[1]['previni']  + $aTotalDespCustRecSaude['previni']);
$total_V_atu    = 0 + $total_IV_atu - ($desp_p[1]['prevatu']  + $aTotalDespCustRecSaude['prevatu']);
$total_V_atebim = 0 + ($total_IV_atebim + $total_IV_rp)  - ($desp_p[1]['bimestre'] + $desp_p[1]['rpNaoProcessado'] +
                       $aTotalDespCustRecSaude['bimestre'] + $VARIAVEL_COMPENSACAO);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_ini, 'f'),    "TBL", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_atu, 'f'),    "TBLR", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, "","TB", 0, "R", 0);
  $total_V_atebim -= $iDespCusteadaOutrosRecursosNaoProc;
}

$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_atebim, 'f'), "TB", 0, "R", 0);

@$pdf->cell($iLarguraColunaValor, $alt, db_formatar(($total_V_atebim / $total_IV_atebimfinal) * 100, 'f'), "TBL", 1, "R", 0);
$pdf->Ln(4);

//-------------------------------------RESTOS APAGAR-------------------------------------------------------------------------------
$pdf->cell($iLarguraColunaDescr, $alt, "CONTROLE DE RESTOS A PAGAR VINCULADOS À SAÚDE", "RT", 0, "C", 0);
$pdf->cell(80, $alt, "RP INSCR. COM DISP. FINANCEIRA DE RECURSOS PRÓPRIOS VINCULADOS", "BTL", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "INSCRITOS EM EXERCÍCIOS ANTERIORES", "", 0, "C", 0);
$pdf->cell(60, $alt, "Inscritos em Exercícios Anteriores ", "L", 0, "C", 0);
$pdf->cell(20, $alt, "Cancelados em ", "L", 1, "C", 0);
$sDescrColuna = "(f)";
if ($lGeraColunaRps) {
  $sDescrColuna = "(h)";
}
$pdf->cell($iLarguraColunaDescr, $alt, "", "B", 0, "C", 0);
$pdf->cell(60, $alt, "", "BL", 0, "C", 0);
$pdf->cell(20, $alt, $anousu . " {$sDescrColuna}", "LB", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "RESTOS A PAGAR DE DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS COM SAÚDE(VII)", "R", 0, "L", 0);
$pdf->cell(60, $alt, "", "L", 0, "C", 0);
$pdf->cell(20, $alt, "", "L", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "", "RB", 0, "L", 0);
$pdf->cell(60, $alt, db_formatar($saldo, 'f'), "BL", 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($cancelado, 'f'), "LB", 1, "R", 0);

$pdf->Ln(2);

$t_participacao = @( ( abs($total_V_atebim - $cancelado) / $total_I_atebim ) * 100 );
$t_cancelado_rp = $cancelado;
$pdf->cell(170,$alt, "PARTICIPAÇÃO DAS DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE NA RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS", "TR", 0, "L", 0);
$pdf->cell(20, $alt, "", "TL", 1, "R", 0);
$pdf->cell(170,$alt, "CONSTITUCIONAIS E LEGAIS - LIMITE CONSTITUCIONAL 15% ((VI - VII f) / I)", "RB", 0, "L", 0);
$pdf->cell(20, $alt, db_formatar($t_participacao, 'f'), "LB", 1, "R", 0);

//-----------------------------------DESPESA POR SUBFUNÇAO----------------------------------------------------------------
$pdf->Ln(2);

$sF2 = "";
$sD2 = "";
if (!$lGeraColunaRps) {
  $sF2 = " (g /total g) * 100";
  $sD2 = "(g)";
}
$aDescricao['A1'] = "DESPESA COM SAÚDE";
$aDescricao['A2'] = "(Por Subfunção)";
$aDescricao['D2'] = "Até o  $txtper {$sD2}";
$aDescricao['E2'] = "INSCRITAS EM RP";
$aDescricao['F2'] = "% {$sF2}";

$aDescricao['C4'] = "";
$aDescricao['D4'] = "(i)";
$aDescricao['E4'] = "(j)";
$aDescricao['F4'] = "((i+j)/(total i+j) * 100)";

cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lGeraColunaRps);
if ($lGeraColunaRps) {
  $total_acum += $total_rp;
}
for($i = 1; $i <= 7; $i ++) {

  $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($m_desp_subfunc[$i]["inicial"], 'f'),   "L", 0, "R", 0);
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($m_desp_subfunc[$i]["atualizada"], 'f'),   "L", 0, "R", 0);
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($m_desp_subfunc[$i]["bimestre"], 'f'), "L", 0, "R", 0);
  if ($lGeraColunaRps) {
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar(($m_desp_subfunc[$i]["restos"]), 'f'),     "L", 0, "R", 0);
  }
  if ($lGeraColunaRps) {
     $m_desp_subfunc[$i]["bimestre"] += $m_desp_subfunc[$i]["restos"];
  }
  $nPercentualLinha = @(($m_desp_subfunc[$i]["bimestre"]/$total_acum)*100);
  @$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercentualLinha, 'f'), "L", 1, "R", 0);

}
// echo $total_acum;
$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL", "TB", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_ini, 'f'),  "TBL", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_atu, 'f'),  "TBLR", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, "", "TB", 0, "R", 0);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_acum, 'f'), "TB", 0, "R", 0);
@$pdf->cell($iLarguraColunaValor, $alt, "100", "LTB", 1, "R", 0);

//------------------------------------------------

if (! isset($arqinclude)) {
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo);

  //assinaturas
  $pdf->Ln(15);

  assinaturas($pdf, $classinatura, 'LRF');

  $pdf->Output();
}

function cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lUltimoP){

  $iLarguraColunaSpan = ( $iLarguraColunaValor * $iNumcols );
  $iLarguraTotal      = ( $iLarguraColunaDescr + $iLarguraColunaValor + $iLarguraColunaValor + $iLarguraColunaSpan );
  $pdf->cell($iLarguraColunaDescr,$alt,$aDescricao["A1"],"T",  0,"C",0);  // A1
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["B1"],"TRL",0,"C",0);  // B1
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["C1"],"TRL",0,"C",0);  // C1
  $pdf->cell($iLarguraColunaSpan, $alt,$aDescricao["D1"],"TL", 1,"C",0);  // D1

  $pdf->cell($iLarguraColunaDescr,$alt,$aDescricao["A2"],"",  0,"C",0);   // A2
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["B2"],"L", 0,"C",0);   // B2
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["C2"],"L", 0,"C",0);   // C2
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["D2"],"TL",0,"C",0);   // D2
  if ($lUltimoP) {
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["E2"],"TL",0,"C",0); // E2
  }
  $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["F2"],"LT",1,"C",0);   // F2

  if ($lUltimoP) {

    $pdf->cell($iLarguraColunaDescr,$alt,$aDescricao["A3"],"", 0,"C",0); // A3
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["B3"],"L",0,"C",0); // B3
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["C3"],"L",0,"C",0); // C3
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["D3"],"L",0,"C",0); // D3
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["E3"],"L",0,"C",0); // E3
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["F3"],"L",1,"C",0); // F3

    $pdf->cell($iLarguraColunaDescr,$alt,$aDescricao["A4"],"", 0,"C",0); // A4
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["B4"],"L",0,"C",0); // B4
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["C4"],"L",0,"C",0); // C4
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["D4"],"L",0,"C",0); // D4
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["E4"],"L",0,"C",0); // E4
    $pdf->cell($iLarguraColunaValor,$alt,$aDescricao["F4"],"L",1,"C",0); // F4

  }

  $pdf->cell($iLarguraTotal,0,"","T","1","C",0);

}

?>
