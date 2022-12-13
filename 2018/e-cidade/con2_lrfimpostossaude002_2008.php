<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  include ("fpdf151/pdf.php");
  include ("fpdf151/assinatura.php");
  include ("libs/db_sql.php");
  include ("libs/db_liborcamento.php");
  include ("libs/db_libcontabilidade.php");
  include ("libs/db_libtxt.php");
  include ("dbforms/db_funcoes.php");
  include ("classes/db_conrelinfo_classe.php");
  include ("classes/db_orcparamrel_classe.php");
  include ("classes/db_empresto_classe.php");
  
  parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
  db_postmemory($_GET);
  
  $classinatura  = new cl_assinatura();
  $orcparamrel   = new cl_orcparamrel();
  $clconrelinfo  = new cl_conrelinfo();
  $clempresto    = new cl_empresto();
  
  $anousu = db_getsession("DB_anousu");
  $dt     = data_periodo($anousu, $periodo); // no dbforms/db_funcoes.php
  

  $dt_ini = $dt [0]; // data inicial do período
  $dt_fin = $dt [1]; // data final do período
  $texto  = $dt ['texto'];
  $txtper = $dt ['periodo'];
}
$lGeraColunaRps = false;

if ($periodo == '2S') {
  $lGeraColunaRps = true;  
}

//  tela do relatorio
$recita1 [0] = "RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS (I)";
$recita1 [1] = "  Impostos";
$recita1 [2] = "  Multas, Juros de Mora e Outros Encargos dos Impostos";
$recita1 [3] = "  Dívida Ativa dos Impostos";
$recita1 [4] = "  Multas, Juros de Mora, Atualização Monetária e Outros Encargos da Dívida Ativa dos Impostos";
$recita1 [5] = "  Receita de Transferências Constitucionais e Legais";
$recita1 [6] = "    Da União";
$recita1 [7] = "    Do Estado";
$recita1 [8] = "TRANSFERÊNCIA DE RECURSOS DO SISTEMA ÚNICO DE SAÚDE - SUS (II)";
$recita1 [9] = "  Da União para o Município";
$recita1 [10] = "  Do Estado para o Município";
$recita1 [11] = "  Demais Municípios para o Município";
$recita1 [12] = "  Outras Receitas do SUS";
$recita1 [13] = "RECEITAS DE OPERAÇÕES DE CRÉDITO VNCULADAS À SAÚDE (III)";
$recita1 [14] = "OUTRAS RECEITAS ORÇAMENTÁRIAS";
$recita1 [15] = "(-) DEDUÇÃO PARA O FUNDEB";

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

$subfuncao [0] = "Atenção Básica";
$subfuncao [1] = "Assistência Hospitalar e Ambulatorial";
$subfuncao [2] = "Suporte Profilático e Terapêutico";
$subfuncao [3] = "Vigilância Sanitária";
$subfuncao [4] = "Vigilância Epidemiológica";
$subfuncao [5] = "Alimentação e Nutrição ";
$subfuncao [6] = "Outras Subfunções";

$contas [0] = "(-)DESPESAS COM INATIVOS E PENCIONISTAS";
$contas [1] = "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE";
$contas [2] = "  Recursos de Transferênncias do Sistema Único de Saúde - SUS";
$contas [3] = "  Recursos de Operações de Crédito";
$contas [4] = "  Outros Recursos";

///////////////////////////////////////////////////////////////////
// 15 linhas de receita
$rec [0] = 0; // RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS (I)
$rec [1] = $orcparamrel->sql_parametro('24', '1'); // IMPOSTOS
$rec [2] = $orcparamrel->sql_parametro('24', '2'); // MULTAS, JUROS DE MORA E OUTROS ENCARGOS DOS IMPOSTOS
$rec [3] = $orcparamrel->sql_parametro('24', '3'); // DIVIDA ATIVA DOS IMPOSTOS
$rec [4] = $orcparamrel->sql_parametro('24', '4'); // MULTAS, JUROS DE MORA, ATUALIZACAO MON. E OUTROS ENC. DA DIVIDA ATIVA DOS IMPOSTOS
/////////////////////////////////////////////////////////////////////////////////////////////////////
$rec [5] = 0; // RECEITAS DE TRANSFERENCIAS CONSTITUCIONAIS E LEGAIS
$rec [6] = $orcparamrel->sql_parametro('24', '5'); // DA UNIAO
$rec [7] = $orcparamrel->sql_parametro('24', '6'); // DO ESTADO
////////////////////////////////////////////////////////////////////////////////////////////////////
$rec [8]  = 0; // TRANSFERENCIA DE RECURSOS DO SISTEMA UNICO DE SAUDE-SUS (II)
$rec [9]  = $orcparamrel->sql_parametro('24', '7'); // DA UNIAO  PARA O MUNICIPIO
$rec [10] = $orcparamrel->sql_parametro('24', '8'); // DO ESTADO PARA O MUNICIPIO
$rec [11] = $orcparamrel->sql_parametro('24', '9'); // DEMAIS MUNICIPIOS PARA O MUNICIPIO
$rec [12] = $orcparamrel->sql_parametro('24', '10'); // OUTRAS RECEITAS DO SUS
////////////////////////////////////////////////////////////////////////////////////////////////////
$rec [13] = $orcparamrel->sql_parametro('24', '11'); // REC. DE OP.DE CREDITO VINCULADAS A SAUDE
$rec [14] = $orcparamrel->sql_parametro('24', '12'); // OUTRAS RECEITAS ORCAMENTARIAS
$rec [15] = $orcparamrel->sql_parametro('24', '13'); // (-) DEDUCAO PARA O FUNDEB
////////////////////////////////////////////////////////////////////////////////////
// DESPESAS COM SAUDE
// PESSOAL E ENCARGOS SOCIAIS
$desp['1']['estrut']  = $orcparamrel->sql_parametro('24', '14');
$desp['1']['nivel']   = $orcparamrel->sql_nivel('24', '14');
$desp['1']['funcao']  = $orcparamrel->sql_funcao('24', '14');
$desp['1']['subfunc'] = $orcparamrel->sql_subfunc('24', '14');
$desp['1']['recurso'] = $orcparamrel->sql_recurso('24', '14');

// JUROS E ENCARGOS DA DIVIDA
$desp['2']['estrut']  = $orcparamrel->sql_parametro('24', '15');
$desp['2']['funcao']  = $orcparamrel->sql_funcao('24', '15');
$desp['2']['nivel']   = $orcparamrel->sql_nivel('24', '15');
$desp['2']['subfunc'] = $orcparamrel->sql_subfunc('24', '15');
$desp['2']['recurso'] = $orcparamrel->sql_recurso('24', '15');

// OUTRAS DESPESAS CORRENTES
$desp['3']['estrut']  = $orcparamrel->sql_parametro('24', '16');
$desp['3']['nivel']   = $orcparamrel->sql_nivel('24', '16');
$desp['3']['funcao']  = $orcparamrel->sql_funcao('24', '16');
$desp['3']['subfunc'] = $orcparamrel->sql_subfunc('24', '16');
$desp['3']['recurso'] = $orcparamrel->sql_recurso('24', '16');

// INVESTIMENTOS
$desp['4']['estrut']  = $orcparamrel->sql_parametro('24', '17');
$desp['4']['nivel']   = $orcparamrel->sql_nivel('24', '17');
$desp['4']['funcao']  = $orcparamrel->sql_funcao('24', '17');
$desp['4']['subfunc'] = $orcparamrel->sql_subfunc('24', '17');
$desp['4']['recurso'] = $orcparamrel->sql_recurso('24', '17');

// INVERSOES FINANCEIRAS
$desp['5']['estrut']  = $orcparamrel->sql_parametro('24', '18');
$desp['5']['nivel']   = $orcparamrel->sql_nivel('24', '18');
$desp['5']['funcao']  = $orcparamrel->sql_funcao('24', '18');
$desp['5']['subfunc'] = $orcparamrel->sql_subfunc('24', '18');
$desp['5']['recurso'] = $orcparamrel->sql_recurso('24', '18');

// AMORTIZACAO DA DIVIDA
$desp['6']['estrut']  = $orcparamrel->sql_parametro('24', '19');
$desp['6']['nivel']   = $orcparamrel->sql_nivel('24', '19');
$desp['6']['funcao']  = $orcparamrel->sql_funcao('24', '19');
$desp['6']['subfunc'] = $orcparamrel->sql_subfunc('24', '19');
$desp['6']['recurso'] = $orcparamrel->sql_recurso('24', '19');

// (-) Depesas proprias
// (-) DESPESAS COM INATIVOS E PENSIONISTAS
$desp_p[1]['estrut']  = $orcparamrel->sql_parametro('24', '20');
$desp_p[1]['nivel']   = $orcparamrel->sql_nivel('24', '20');
$desp_p[1]['funcao']  = $orcparamrel->sql_funcao('24', '20');
$desp_p[1]['subfunc'] = $orcparamrel->sql_subfunc('24', '20');
$desp_p[1]['recurso'] = $orcparamrel->sql_recurso('24', '20');

// RECURSOS DE TRANSFERENCIAS DO SUS
$desp_p[2]['estrut']  = $orcparamrel->sql_parametro('24', '21');
$desp_p[2]['nivel']   = $orcparamrel->sql_nivel('24', '21');
$desp_p[2]['funcao']  = $orcparamrel->sql_funcao('24', '21');
$desp_p[2]['subfunc'] = $orcparamrel->sql_subfunc('24', '21');
$desp_p[2]['recurso'] = $orcparamrel->sql_recurso('24', '21');

// RECURSOS DE OPERACOES DE CREDITO
$desp_p[3]['estrut']  = $orcparamrel->sql_parametro('24', '22');
$desp_p[3]['nivel']   = $orcparamrel->sql_nivel('24', '22');
$desp_p[3]['funcao']  = $orcparamrel->sql_funcao('24', '22');
$desp_p[3]['subfunc'] = $orcparamrel->sql_subfunc('24', '22');
$desp_p[3]['recurso'] = $orcparamrel->sql_recurso('24', '22');

// OUTROS RECURSOS
$desp_p[4]['estrut']  = $orcparamrel->sql_parametro('24', '23');
$desp_p[4]['nivel']   = $orcparamrel->sql_nivel('24', '23');
$desp_p[4]['funcao']  = $orcparamrel->sql_funcao('24', '23');
$desp_p[4]['subfunc'] = $orcparamrel->sql_subfunc('24', '23');
$desp_p[4]['recurso'] = $orcparamrel->sql_recurso('24', '23');

for($linha = 1; $linha <= 4; $linha ++) {
  $desp_p[$linha]['previni']  = 0;
  $desp_p[$linha]['prevatu']  = 0;
  $desp_p[$linha]['rpNaoProcessado']  = 0;
  $desp_p[$linha]['bimestre'] = 0;
}

// -------------------------------------------------------------------
// RESTOS A PAGAR COM DISPONIBILIDADE FINANCEIRA
$w_instit = str_replace('-', ', ', $db_selinstit);
$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores('24', $w_instit));
$VARIAVEL_COMPENSACAO = 0;
$nValorOutrosRecursos = 0;
if ($clconrelinfo->numrows > 0) {
  for($x = 0; $x < $clconrelinfo->numrows; $x ++) {
    db_fieldsmemory($res, $x);
    if ($c83_codigo == 350) {
      $VARIAVEL_COMPENSACAO = $c83_informacao;
    }
    if ($c83_codigo == 376) {
      $nValorOutrosRecursos += $c83_informacao;
    }
  }
}
// -------------------------------------------------------------------


$sele_work = 'o58_instit in (' . str_replace('-', ', ', $db_selinstit) . ')   ';
$result_despesa = db_dotacaosaldo(8, 2, 3, true, $sele_work, $anousu, $dt_ini, $dt_fin);


$db_filtro = ' o70_instit in (' . str_replace('-', ', ', $db_selinstit) . ')';
$result_rec = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
@ pg_exec("drop table work_receita");

//db_criatabela($result_despesa);exit;


// saldo dos rps inscritos e cancelados da saude
$m_rp [1] ['funcao'] = $orcparamrel->sql_funcao('24', '24');
$m_rp [1] ['subfunc'] = $orcparamrel->sql_subfunc('24', '24');
$m_rp [1] ['recurso'] = $orcparamrel->sql_recurso('24', '24');

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

$sele_work = " e60_instit in (" . str_replace("-", ", ", $db_selinstit) . ")";
$sele_work1 = " and e91_recurso in ($v_codigo)";
$sql_where_externo = " where $sele_work ";
$sql_order = " order by e91_recurso, e91_numemp ";

$dt_ini2 = $anousu . "-01-01";

$sqlperiodo = $clempresto->sql_rp($anousu, $sele_work, $dt_ini2, $dt_fin, $sele_work1, $sql_where_externo, $sql_order);
$sqlperiodo = " select e91_recurso,o15_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
                       sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
                from ($sqlperiodo) as x
                group by e91_recurso,o15_descr,e60_anousu
	          		order by e91_recurso,e60_anousu";

$result_restos_mde1 = @pg_query($sqlperiodo);
$numrows_restos_mde1 = @pg_numrows($result_restos_mde1);

$cancelado = 0;
$saldo = 0;
for($i = 0; $i < pg_numrows($result_restos_mde1); $i ++) {
  db_fieldsmemory($result_restos_mde1, $i);
  
  $saldo += (($e91_vlremp - $e91_vlranu - $vlranu) - ($e91_vlrpag + $vlrpag));
}

$db_filtro = " in (" . str_replace("-", ", ", $db_selinstit) . ")";
$result_restos_mde2 = db_rpsaldo($anousu, $db_filtro, $dt_ini2, $dt_fin, " o58_codigo     in (" . $v_codigo . ")  and 
                                   o58_funcao    in (" . $v_funcao . ")  and 
                                   o58_subfuncao in (" . $v_subfunc . ") ", " and c53_coddoc = 32 ");

//db_criatabela($result_restos_mde2);
for($i = 0; $i < pg_numrows($result_restos_mde2); $i ++) {
  db_fieldsmemory($result_restos_mde2, $i);
  $cancelado += $vlranu;
}

// DESPESAS POR SUBFUNCAO 
$m_desp_subfunc[1]['estrut']  = $orcparamrel->sql_parametro('24', '25');
$m_desp_subfunc[1]['nivel']   = $orcparamrel->sql_nivel('24', '25');
$m_desp_subfunc[1]['funcao']  = $orcparamrel->sql_funcao('24', '25');
$m_desp_subfunc[1]['subfunc'] = $orcparamrel->sql_subfunc('24', '25');
$m_desp_subfunc[1]['recurso'] = $orcparamrel->sql_recurso('24', '25');

$v_funcao  = '0';
$v_subfunc = '0';
$v_codigo  = '0';
$sp        = '';

foreach ( $m_desp_subfunc [1] ['funcao'] as $registro ) {
  $v_funcao .= $sp . $registro;
  $sp = ',';
}

$sp = '';
foreach ( $m_desp_subfunc [1] ['subfunc'] as $registro ) {
  $v_subfunc .= $sp . $registro;
  $sp = ',';
}

$sp = '';
foreach ( $m_desp_subfunc [1] ['recurso'] as $registro ) {
  $v_codigo .= $sp . $registro;
  $sp = ',';
}

$result_subfuncao = db_dotacaosaldo(8, 2, 3, true, 'o58_funcao in (' . $v_funcao . ') and o58_subfuncao in (' . $v_subfunc . ') and
                                                    o58_codigo in (' . $v_codigo . ') and o58_instit in (' . str_replace('-', ', ', $db_selinstit) . ')', $anousu, $dt_ini, $dt_fin);

//-------------------------------------------------RECEITAS-----------------------------------------
$total_rec_ini = 0;
$total_rec_atu = 0;
$total_rec_atebim = 0;
for($i = 0; $i < 16; $i ++) {
  $receitas_previni [$i] = 0;
  $receitas_prevatu [$i] = 0;
  $receitas_atebime [$i] = 0;
  $receitas_nobimes [$i] = 0;
}

//db_criatabela($result_rec);


// RECEITA DE IMPOSTOS LIQUIDA [1...4] + TOTAL DAS RECEITAS[0]
for($p = 1; $p <= 4; $p ++) {
  for($i = 0; $i < pg_numrows($result_rec); $i ++) {
    db_fieldsmemory($result_rec, $i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural, $rec [$p])) {
      $receitas_previni [$p] += $saldo_inicial;
      $receitas_prevatu [$p] += $saldo_inicial_prevadic;
      $receitas_atebime [$p] += $saldo_arrecadado_acumulado;
      $receitas_nobimes [$p] += $saldo_arrecadado;
    }
  }
}

// TRANSFERENCIAS CONSTITUCIONAIS E LEGAIS [6...7]
for($p = 6; $p <= 7; $p ++) {
  for($i = 0; $i < pg_numrows($result_rec); $i ++) {
    db_fieldsmemory($result_rec, $i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural, $rec [$p])) {
      $receitas_previni [$p] += $saldo_inicial;
      $receitas_prevatu [$p] += $saldo_inicial_prevadic;
      $receitas_atebime [$p] += $saldo_arrecadado_acumulado;
      $receitas_nobimes [$p] += $saldo_arrecadado;
    }
  }
}

// TOTAL DAS RECEITA DE IMPOSTOS LIQUIDA
$receitas_previni [5] = $receitas_previni [6] + $receitas_previni [7];
$receitas_prevatu [5] = $receitas_prevatu [6] + $receitas_prevatu [7];
$receitas_atebime [5] = $receitas_atebime [6] + $receitas_atebime [7];
$receitas_nobimes [5] = $receitas_nobimes [6] + $receitas_nobimes [7];

// TOTAL DAS RECEITA/TRANSFERENCIAS
$receitas_previni [0] = $receitas_previni [1] + $receitas_previni [2] + $receitas_previni [3] + $receitas_previni [4];
$receitas_prevatu [0] = $receitas_prevatu [1] + $receitas_prevatu [2] + $receitas_prevatu [3] + $receitas_prevatu [4];
$receitas_atebime [0] = $receitas_atebime [1] + $receitas_atebime [2] + $receitas_atebime [3] + $receitas_atebime [4];
$receitas_nobimes [0] = $receitas_nobimes [1] + $receitas_nobimes [2] + $receitas_nobimes [3] + $receitas_nobimes [4];

// TOTAL DAS RECEITAS/TRANSFERENCIAS CONSTITUCIONAIS (I)
$receitas_previni [0] += $receitas_previni [5];
$receitas_prevatu [0] += $receitas_prevatu [5];
$receitas_atebime [0] += $receitas_atebime [5];
$receitas_nobimes [0] += $receitas_nobimes [5];

// TRANSFERENCIA DE RECURSOS DO SISTEMA UNICO DE SAUDE-SUS (II)
for($p = 9; $p <= 12; $p ++) {
  for($i = 0; $i < pg_numrows($result_rec); $i ++) {
    db_fieldsmemory($result_rec, $i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural, $rec [$p])) {
      $receitas_previni [$p] += $saldo_inicial;
      $receitas_prevatu [$p] += $saldo_inicial_prevadic;
      $receitas_atebime [$p] += $saldo_arrecadado_acumulado;
      $receitas_nobimes [$p] += $saldo_arrecadado;
    }
  }
}

// TOTAL DAS TRANSFERENCIA DO SUS (II)
$receitas_previni [8] = $receitas_previni [9] + $receitas_previni [10] + $receitas_previni [11] + $receitas_previni [12];
$receitas_prevatu [8] = $receitas_prevatu [9] + $receitas_prevatu [10] + $receitas_prevatu [11] + $receitas_prevatu [12];
$receitas_atebime [8] = $receitas_atebime [9] + $receitas_atebime [10] + $receitas_atebime [11] + $receitas_atebime [12];
$receitas_nobimes [8] = $receitas_nobimes [9] + $receitas_nobimes [10] + $receitas_nobimes [11] + $receitas_nobimes [12];

for($p = 13; $p <= 15; $p ++) {
  for($i = 0; $i < pg_numrows($result_rec); $i ++) {
    db_fieldsmemory($result_rec, $i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural, $rec [$p])) {
      $receitas_previni [$p] += $saldo_inicial;
      $receitas_prevatu [$p] += $saldo_inicial_prevadic;
      $receitas_atebime [$p] += $saldo_arrecadado_acumulado;
      $receitas_nobimes [$p] += $saldo_arrecadado;
    }
  }
}
//------------------------------------------------- Despesas -----------------------------------------
for($x = 1; $x <= 6; $x ++) {
  $desp [$x] ['previni'] = 0;
  $desp [$x] ['prevatu'] = 0;
  $desp [$x] ['rpNaoProcessado'] = 0;
  $desp [$x] ['bimestre'] = 0;
}

///db_criatabela($result_despesa);exit;
for($i = 0; $i < pg_numrows($result_despesa); $i ++) {
  db_fieldsmemory($result_despesa, $i);
  
  for($linha = 1; $linha <= 6; $linha ++) {
    $nivel = $desp [$linha] ['nivel'];
    $estrutural = $o58_elemento . '00';
    $estrutural = substr($estrutural, 0, $nivel);
    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
    
    $v_funcao = $o58_funcao;
    $v_subfuncao = $o58_subfuncao;
    $v_recurso = $o58_codigo;
    
    if (in_array($v_estrutural, $desp [$linha] ['estrut'])) {
      if (count($desp [$linha] ['funcao']) == 0 || in_array($v_funcao, $desp [$linha] ['funcao'])) {
        if (count($desp [$linha] ['subfunc']) == 0 || in_array($v_subfuncao, $desp [$linha] ['subfunc'])) {
          if (count($desp [$linha] ['recurso']) == 0 || in_array($v_recurso, $desp [$linha] ['recurso'])) {
            
            $desp[$linha]['previni']         += $dot_ini;
            $desp[$linha]['prevatu']         += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
            $desp[$linha]['rpNaoProcessado'] += abs(round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2));
            $desp[$linha]['bimestre']        += $liquidado_acumulado;
          
          } 
        }
      } 
    } 
  } 
}
/*
echo "<pre>";
print_r($desp);
echo "</pre>";
exit;
*/
// DESPESAS PROPRIAS COM AÇOES E SERV. PUBLICOS DE SAUDE

//db_criatabela($result_despesa);exit;
for($i = 0; $i < pg_numrows($result_despesa); $i ++) {
  db_fieldsmemory($result_despesa, $i);
  
  for($linha = 1; $linha <= 4; $linha ++) {

    $nivel        = $desp_p [$linha] ['nivel'];
    $estrutural   = $o58_elemento . '00';
    $estrutural   = substr($estrutural, 0, $nivel);
    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
    
    $v_funcao     = $o58_funcao;
    $v_subfuncao  = $o58_subfuncao;
    $v_recurso    = $o58_codigo;
    
    if (in_array($v_estrutural, $desp_p [$linha] ['estrut'])) {
      if (count($desp_p [$linha] ['funcao']) == 0 || in_array($v_funcao, $desp_p [$linha] ['funcao'])) {
        if (count($desp_p [$linha] ['subfunc']) == 0 || in_array($v_subfuncao, $desp_p [$linha] ['subfunc'])) {
          if (count($desp_p [$linha] ['recurso']) == 0 || in_array($v_recurso, $desp_p [$linha] ['recurso'])) {
            
            $desp_p [$linha]['previni']         += $dot_ini;
            $desp_p [$linha]['prevatu']         += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
            $desp_p [$linha]['rpNaoProcessado'] += abs( round( $empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
            $desp_p [$linha]['bimestre']        += $liquidado_acumulado;
          
          }
        } 
      } 
    } 
  } 
} 

//echo "N: $nivel <br>";
//echo "E:$estrutural <br>";
//echo "F: $v_funcao <br>";
//echo "S: $v_subfuncao <br>";
//echo "R: $v_recurso <br>";

//------------------------------------funcao e subfuncao------------------------------------------------------------------------
$total_acum = 0;
$total_ini  = 0;
$total_atu  = 0;
$total_rp   = 0;

$sub301_dotini = 0;
$sub301_atuali = 0;
$sub301_rp     = 0;
$sub301_atebim = 0;

$sub302_dotini = 0;
$sub302_atuali = 0;
$sub302_rp     = 0;
$sub302_atebim = 0;

$sub303_dotini = 0;
$sub303_atuali = 0;
$sub303_rp     = 0;
$sub303_atebim = 0;

$sub304_dotini = 0;
$sub304_atuali = 0;
$sub304_rp     = 0;
$sub304_atebim = 0;

$sub305_dotini = 0;
$sub305_atuali = 0;
$sub305_rp     = 0;
$sub305_atebim = 0;

$sub306_dotini = 0;
$sub306_atuali = 0;
$sub306_rp     = 0;
$sub306_atebim = 0;

$subout_dotini = 0;
$subout_atuali = 0;
$subout_rp     = 0;
$subout_atebim = 0;

for($i = 0; $i < pg_numrows($result_subfuncao); $i ++) {
  db_fieldsmemory($result_subfuncao, $i);
  
  $nivel        = $m_desp_subfunc [1] ['nivel'];
  $estrutural   = $o58_elemento . '00';
  $estrutural   = substr($estrutural, 0, $nivel);
  $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
  
  $v_funcao    = $o58_funcao;
  $v_subfuncao = $o58_subfuncao;
  $v_recurso   = $o58_codigo;
  
  if (! in_array($v_estrutural, $m_desp_subfunc [1] ['estrut'])) {
    continue;
  }
  
  if (count($m_desp_subfunc [1] ['funcao']) == 0 || in_array($v_funcao, $m_desp_subfunc [1] ['funcao'])) {
    if (count($m_desp_subfunc [1] ['subfunc']) == 0 || in_array($v_subfuncao, $m_desp_subfunc [1] ['subfunc'])) {
      if (count($m_desp_subfunc [1] ['recurso']) == 0 || in_array($v_recurso, $m_desp_subfunc [1] ['recurso'])) {
        
        $total_acum += $liquidado_acumulado;
        $total_ini  += $dot_ini;
        $total_atu  += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);          
        $total_rp   += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          
        if ($o58_subfuncao == 301) {
          $sub301_dotini += $dot_ini;
          $sub301_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub301_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          $sub301_atebim += $liquidado_acumulado;
          continue;

        }
        
        if ($o58_subfuncao == 302) {
          $sub302_dotini += $dot_ini;
          $sub302_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub302_atebim += $liquidado_acumulado;
          $sub302_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          continue;

        }
        
        if ($o58_subfuncao == 303) {
          $sub303_dotini += $dot_ini;
          $sub303_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub303_atebim += $liquidado_acumulado;
          $sub303_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          continue;
        }
        
        if ($o58_subfuncao == 304) {
          $sub304_dotini += $dot_ini;
          $sub304_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub304_atebim += $liquidado_acumulado;
          $sub304_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          continue;
        }
        
        if ($o58_subfuncao == 305) {
          $sub305_dotini += $dot_ini;
          $sub305_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub305_atebim += $liquidado_acumulado;
          $sub305_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          continue;
        }
        
        if ($o58_subfuncao == 306) {
          $sub306_dotini += $dot_ini;
          $sub306_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
          $sub306_atebim += $liquidado_acumulado;
          $sub306_rp     += abs( round($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado,2) );
          continue;
        }
        
        $subout_dotini += $dot_ini;
        $subout_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
        $subout_atebim += $liquidado_acumulado;
        $subout_rp     += abs( $empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado );
        
      }
    }
  }
}

$desp_p[4]['bimestre'] += $nValorOutrosRecursos;
/////////////////////////////////////////////////////////////////////////////////
$n1 = 5;
$n2 = 10;

// end se incluido em outro arquivo

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select munic from db_config where codigo in (" . str_replace('-', ', ', $db_selinstit) . ") ");
$descr_inst = '';
db_fieldsmemory($resultinst, 0);
$descr_inst = $munic;

$vdt_fin = split("-", $dt_fin);

$head1 = "MUNICÍPIO DE " . strtoupper($descr_inst);
$head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head3 = "DEMONSTRATIVO DA RECEITA DE IMPOSTOS LÍQUIDA E DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE";
$head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head5 = db_mes("01", 1) . " A " . db_mes($vdt_fin [1], 1) . "/" . $anousu;

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
$pdf->cell(20, $alt, "INICIAL", "BL", 0, "C", 0);
$pdf->cell(20, $alt, "ATUALIZADA (a)", "BL", 0, "C", 0);
$pdf->cell(20, $alt, "Até o " . $txtper . " (b)", "TBL", 0, "C", 0);
$pdf->cell(20, $alt, "% (b/a)", "LTB", 1, "C", 0);
$alt = 3;

for($i = 0; $i < 16; $i ++) {
  $pdf->cell($iLarguraColunaDescr, $alt, $recita1 [$i], "", 0, "L", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_previni [$i], 'f'), "L", 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_prevatu [$i], 'f'), "L", 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($receitas_atebime [$i], 'f'), "L", 0, "R", 0);
  if ($receitas_prevatu [$i] != 0)
    $pdf->cell(20, $alt, db_formatar((($receitas_atebime [$i] / $receitas_prevatu [$i]) * 100), 'f'), "L", 1, "R", 0);
  else
    $pdf->cell(20, $alt, db_formatar(0, 'f'), "L", 1, "R", 0);

}

// TOTAL GERAL (I) + (II) + (III) + OUTRAS REC. ORCAMENTARIAS - DEDUCOES
$total_previni = ($receitas_previni [0] + $receitas_previni [8] + $receitas_previni [13] + $receitas_previni [14]) - abs($receitas_previni [15]);
$total_prevatu = ($receitas_prevatu [0] + $receitas_prevatu [8] + $receitas_prevatu [13] + $receitas_prevatu [14]) - abs($receitas_prevatu [15]);
$total_atebime = ($receitas_atebime [0] + $receitas_atebime [8] + $receitas_atebime [13] + $receitas_atebime [14]) - abs($receitas_atebime [15]);

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
$aDescricao['C2'] = "ATUALIZADA";
if (!$lGeraColunaRps){
  $sD2 = "(d)";
}
$aDescricao['D2'] = "Até o " . $txtper . " {$sD2}";
$aDescricao['E2'] = "INSCRITAS EM RP";
if (!$lGeraColunaRps){
  $sF2 = "(d/c)";
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
$aDescricao['F4'] = "((d+e)/c)";

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
  $nTotalPercCorrente = ( ( $aTotalDespCorrente['bimestre'] * 100 ) / $aTotalDespCorrente['prevatu'] );
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
if (!$lGeraColunaRps) {
  $sD2 = "(d)";
}
$aDescricao['D2'] = "Até o $txtper {$sD2}";
if (!$lGeraColunaRps) {
  $sF2 = "(e/desp saúde)";
}
$aDescricao['F2'] = "% {$sF2}";


$aDescricao['A4'] = "";
$aDescricao['B4'] = "";
$aDescricao['C4'] = "(f)";
$aDescricao['D4'] = "(g)";
$aDescricao['E4'] = "(h)";
$aDescricao['F4'] = "((g+h)/f)";

cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lGeraColunaRps);

$pdf->cell($iLarguraColunaDescr, $alt, "DESPESAS COM SAÚDE", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_ini, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atu, 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_atebim, 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_IV_rp, 'f'), "L", 0, "R", 0);
  @$nTotalPercIV = ( ( ( $total_IV_atebim + $total_IV_rp ) * 100 ) / $total_IV_atu );
}else{
  @$nTotalPercIV = ($total_IV_atebim * 100) / $total_IV_atu;
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nTotalPercIV, 'f'), "L", 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "(-) DESPESAS COM INATIVOS E PENSIONISTAS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['previni'],'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['prevatu'],'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['bimestre'],'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[1]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercInatPen = ( ( ( $desp_p[1]['bimestre'] + $desp_p[1]['rpNaoProcessado'] ) * 100 ) / $total_IV_atu );
}else{
  @$nPercInatPen = ( ( $desp_p[1]['bimestre'] * 100 ) / $total_IV_atu );
}
  
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercInatPen, 'f'), 'L', 1, "R", 0);

// caso a linha abaixo seja zerada,  o manual diz que os valores devem ser pegos do quadro da receita
// RRO , 4 Ed, pg 278
if ($desp_p [2] ['previni'] == 0 && $desp_p [2] ['prevatu'] == 0 && $desp_p [2] ['bimestre'] == 0) {
  $desp_p[2]['previni']  = $receitas_previni[8];
  $desp_p[2]['prevatu']  = $receitas_prevatu[8];
  $desp_p[2]['bimestre'] = $receitas_atebime[8];
}
if ($desp_p[3]['previni'] == 0 && $desp_p[3]['prevatu'] == 0 && $desp_p[3]['bimestre'] == 0) {
  $desp_p[3]['previni']  = $receitas_previni[11];
  $desp_p[3]['prevatu']  = $receitas_prevatu[11];
  $desp_p[3]['bimestre'] = $receitas_atebime[11];
}

$aTotalDespCustRecSaude['previni']         = ( $desp_p[2]['previni']  + $desp_p[3]['previni']  + $desp_p[4]['previni'] );
$aTotalDespCustRecSaude['prevatu']         = ( $desp_p[2]['prevatu']  + $desp_p[3]['prevatu']  + $desp_p[4]['prevatu'] );
$aTotalDespCustRecSaude['bimestre']        = ( $desp_p[2]['bimestre'] + $desp_p[3]['bimestre'] + $desp_p[4]['bimestre'] );
$aTotalDespCustRecSaude['rpNaoProcessado'] = ( $desp_p[2]['rpNaoProcessado'] + $desp_p[3]['rpNaoProcessado'] + $desp_p[4]['rpNaoProcessado'] );

$pdf->cell($iLarguraColunaDescr, $alt, "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['bimestre'],'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($aTotalDespCustRecSaude['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercDespCustRecSaude = ( ( ( $aTotalDespCustRecSaude['bimestre'] + $aTotalDespCustRecSaude['rpNaoProcessado'] ) * 100 ) / $aTotalDespCustRecSaude['prevatu'] );
}else{
  @$nPercDespCustRecSaude = ( ( $aTotalDespCustRecSaude['bimestre'] * 100 ) / $aTotalDespCustRecSaude['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercDespCustRecSaude, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Recursos de Transferências do Sistema Único de Saúde - SUS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[2]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercTransSUS = ( ( ( $desp_p[2]['bimestre'] + $desp_p[2]['rpNaoProcessado'] ) * 100 ) / $desp_p[2]['prevatu'] );
}else{
  @$nPercTransSUS = ( ( $desp_p[2]['bimestre'] * 100) / $desp_p[2]['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercTransSUS, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Recursos de Operações de Crédito", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['previni'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['prevatu'], 'f'), "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[3]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercOperCred = ( ( ( $desp_p[3]['bimestre'] + $desp_p[3]['rpNaoProcessado'] ) * 100 ) / $desp_p[3]['prevatu'] );
}else{
  @$nPercOperCred = ( ( $desp_p[3]['bimestre'] * 100 ) / $desp_p[3]['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($nPercOperCred, 'f'), 'L', 1, "R", 0);

$pdf->cell($iLarguraColunaDescr, $alt, espaco($n1) . "Outros Recursos", "0", 0,  "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['previni'], 'f'),  "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['prevatu'], 'f'),  "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['bimestre'], 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['rpNaoProcessado'], 'f'), "L", 0, "R", 0);
  @$nPercOutroRec = ( ( $desp_p[4]['bimestre'] + $desp_p[4]['rpNaoProcessado'] * 100 ) / $desp_p[4]['prevatu'] );
}else{
  @$nPercOutroRec = ( ( $desp_p[4]['bimestre'] * 100 ) / $desp_p[4]['prevatu'] );
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($desp_p[4]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);

$perc_rp = 0;
@ $perc_rp = ($desp_p [2] ['bimestre'] + $desp_p [3] ['bimestre'] + $desp_p [4] ['bimestre']) * 100 / $total_IV_atebim;

$pdf->cell($iLarguraColunaDescr, $alt, "(-)RP INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE REC.PROPRIOS VINCULADOS", "0", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, '-', "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, '-', "L", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($VARIAVEL_COMPENSACAO, 'f'), "L", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, '',"LR", 0, "R", 0);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($perc_rp, "f"), 'L', 1, "R", 0);

$total_V_ini    = 0 + $total_IV_ini - ($desp_p[1]['previni'] + $desp_p[2]['previni'] + $desp_p[3]['previni'] + $desp_p[4]['previni']);
$total_V_atu    = 0 + $total_IV_atu - ($desp_p[1]['prevatu'] + $desp_p[2]['prevatu'] + $desp_p[3]['prevatu'] + $desp_p[4]['prevatu']);
$total_V_rp     = 0 + $total_IV_rp  - ($desp_p[1]['rpNaoProcessado'] + $desp_p[2]['rpNaoProcessado'] + $desp_p[3]['rpNaoProcessado'] + $desp_p[4]['rpNaoProcessado']);
$total_V_atebim = 0 + $total_IV_atebim - ($desp_p [1]['bimestre'] + $desp_p[2]['bimestre'] + $desp_p[3]['bimestre'] + $desp_p [4]['bimestre'] + $VARIAVEL_COMPENSACAO);

$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE(V)", "TB", 0, "L", 0);
// $nTotalAteVBim = ($total_V_atebim * 100) / $total_IV_atebim;
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_ini, 'f'),    "TBL", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_atu, 'f'),    "TBLR", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, "","TB", 0, "R", 0);
  $total_V_atebim += $total_V_rp;
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_V_atebim, 'f'), "TB", 0, "R", 0);

// die("($total_V_atebim * 100) / $total_IV_atu");
//      (14191321.79 * 100) / 20216174.08
@$pdf->cell($iLarguraColunaValor, $alt, db_formatar(($total_V_atebim * 100) / $total_V_atu, 'f'), "TBL", 1, "R", 0);
$pdf->Ln(4);

//-------------------------------------RESTOS APAGAR-------------------------------------------------------------------------------
$pdf->cell($iLarguraColunaDescr, $alt, "CONTROLE DE RESTOS A PAGAR VINCULADOS À SAÚDE", "RT", 0, "C", 0);
$pdf->cell(80, $alt, "RP INSCR. COM DISP. FINANCEIRA DE RECURSOS PRÓPRIOS VINCULADOS", "BTL", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "INSCRITOS EM EXERCÍCIOS ANTERIORES", "", 0, "C", 0);
$pdf->cell(60, $alt, "Inscritos em Exercícios Anteriores ", "L", 0, "C", 0);
$pdf->cell(20, $alt, "Cancelados em ", "L", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "", "B", 0, "C", 0);
$pdf->cell(60, $alt, "", "BL", 0, "C", 0);
$pdf->cell(20, $alt, $anousu . " (VI)", "LB", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "RESTOS A PAGAR DE DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS COM SAÚDE", "R", 0, "L", 0);
$pdf->cell(60, $alt, "", "L", 0, "C", 0);
$pdf->cell(20, $alt, "", "L", 1, "C", 0);

$pdf->cell($iLarguraColunaDescr, $alt, "", "RB", 0, "L", 0);
$pdf->cell(60, $alt, db_formatar($saldo, 'f'), "BL", 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($cancelado, 'f'), "LB", 1, "R", 0);

$pdf->Ln(2);

$t_participacao = ( ( abs($total_V_atebim - $cancelado) / $total_I_atebim ) * 100 );
$pdf->cell(170,$alt, "PARTICIPAÇÃO DAS DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE NA RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERÊNCIAS", "TR", 0, "L", 0);
$pdf->cell(20, $alt, "", "TL", 1, "R", 0);
$pdf->cell(170,$alt, "CONSTITUCIONAIS E LEGAIS - LIMITE CONSTITUCIONAL 15% [(V - VI) / I]", "RB", 0, "L", 0);
$pdf->cell(20, $alt, db_formatar($t_participacao, 'f'), "LB", 1, "R", 0);

//-----------------------------------DESPESA POR SUBFUNÇAO----------------------------------------------------------------
$pdf->Ln(2);

$sF2 = "";
$sD2 = "";
if (!$lGeraColunaRps) {
  $sF2 = "i /(total i)";
  $sD2 = "(d)";
}
$aDescricao['A1'] = "DESPESA COM SAÚDE";
$aDescricao['A2'] = "(Por Subfunção)";
$aDescricao['D2'] = "Até o  $txtper {$sD2}";
$aDescricao['E2'] = "INSCRITAS EM RP";
$aDescricao['F2'] = "% i {$sF2}";

$aDescricao['C4'] = "(i)";
$aDescricao['D4'] = "(j)";
$aDescricao['E4'] = "(k)";
$aDescricao['F4'] = "((j+k)/i)";

cabecalhoDespesa($pdf,$aDescricao,$iLarguraColunaDescr,$iLarguraColunaValor,$alt,$iNumcols,$lGeraColunaRps);

for($i = 0; $i < 7; $i ++) {
  if ($i == 0) {
    if ($lGeraColunaRps) {
      $sub301_atebim += $sub301_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub301_dotini, 'f'),   "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub301_atuali, 'f'),   "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar(($sub301_atebim), 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar(($sub301_rp), 'f'),     "L", 0, "R", 0);
    }
    @$pdf->cell($iLarguraColunaValor, $alt, db_formatar(((($sub301_atebim) / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 1) {
    if ($lGeraColunaRps) {
      $sub302_atebim += $sub302_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub302_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub302_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub302_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub302_rp, 'f'),     "L", 0, "R", 0);
      $sub302_atebim += $sub302_rp;
    }
    @$pdf->cell($iLarguraColunaValor, $alt, db_formatar((($sub302_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 2) {
    if ($lGeraColunaRps) {
      $sub303_atebim += $sub303_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub303_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub303_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub303_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub303_rp, 'f'),     "L", 0, "R", 0);
    }
    @$pdf->cell($iLarguraColunaValor, $alt, db_formatar((($sub303_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 3) {
    if ($lGeraColunaRps) {
      $sub304_atebim += $sub304_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub304_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub304_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub304_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub304_rp, 'f'),     "L", 0, "R", 0);
    }
    @ $pdf->cell($iLarguraColunaValor, $alt, db_formatar((($sub304_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 4) {
    if ($lGeraColunaRps) {
      $sub305_atebim += $sub305_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub305_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub305_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub305_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub305_rp, 'f'),     "L", 0, "R", 0);
    }
    @ $pdf->cell($iLarguraColunaValor, $alt, db_formatar((($sub305_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 5) {
    if ($lGeraColunaRps) {
      $sub306_atebim += $sub306_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub306_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub306_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub306_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($sub306_rp, 'f'), "L", 0, "R", 0);
    }
    @ $pdf->cell($iLarguraColunaValor, $alt, db_formatar((($sub306_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 6) {
    if ($lGeraColunaRps) {
      $subout_atebim += $subout_rp;
    }
    $pdf->cell($iLarguraColunaDescr, $alt, $subfuncao [$i], "", 0, "L", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($subout_dotini, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($subout_atuali, 'f'), "L", 0, "R", 0);
    $pdf->cell($iLarguraColunaValor, $alt, db_formatar($subout_atebim, 'f'), "L", 0, "R", 0);
    if ($lGeraColunaRps) {
      $pdf->cell($iLarguraColunaValor, $alt, db_formatar($subout_rp, 'f'),     "L", 0, "R", 0);
    }
    @$pdf->cell($iLarguraColunaValor, $alt, db_formatar((($subout_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
  }
}
// echo $total_acum;
if ($lGeraColunaRps) {
  $total_acum += $total_rp;
}
$pdf->cell($iLarguraColunaDescr, $alt, "TOTAL", "TB", 0, "L", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_ini, 'f'),  "TBL", 0, "R", 0);
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_atu, 'f'),  "TBLR", 0, "R", 0);
if ($lGeraColunaRps) {
  $pdf->cell($iLarguraColunaValor, $alt, "", "TB", 0, "R", 0);
}
$pdf->cell($iLarguraColunaValor, $alt, db_formatar($total_acum, 'f'), "TB", 0, "R", 0);

@$pdf->cell($iLarguraColunaValor, $alt, db_formatar(($total_acum / $total_atu) * 100, "f"), "LTB", 1, "R", 0);

//------------------------------------------------

if (! isset($arqinclude)) {
  notasExplicativas(&$pdf, 24, "{$periodo}", 190);
  
  //assinaturas
  $pdf->Ln(15);
  
  assinaturas(&$pdf, &$classinatura, 'LRF');
  
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