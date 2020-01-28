<?php
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

/*
 *  modelo[analitico|sintetico]
 *  sintetico - somente codlan+sequencia, documento e valor
 *  analitico - imprimiri historico da tabela conlancamcompl
 *  imprime contrapartida - opcional
 * default - analitico
 *
 */
//require_once(modification("fpdf151/pdf.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orcorgao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("classes/db_conlancamcgm_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_orcsuplem_classe.php"));
require_once(modification("classes/db_conlancamrec_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamdot_classe.php"));
require_once(modification("classes/db_conlancamdig_classe.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/db_conplano_classe.php"));
require_once(modification("fpdf151/PDFDocument.php"));

define("BAIXA_BENS", 701);
define("APURACAO_VALOR_LIQUIDO", 703);
define("ENTRADA_MANUAL_ESTOQUE", 403);
define("SAIDA_MANUAL_ESTOQUE", 404);

db_postmemory($_GET);

$clrotulo       = new rotulocampo();
$clconlancamval = new cl_conlancamval();
$clconlancamcgm = new cl_conlancamcgm();
$clconlancam    = new cl_conlancam();
$auxiliar       = new cl_conlancam();
$clorcsuplem    = new cl_orcsuplem();
$clconlancamrec = new cl_conlancamrec();
$clconlancamemp = new cl_conlancamemp();
$clconlancamdot = new cl_conlancamdot();
$clconlancamdig = new cl_conlancamdig();
$clconplano     = new cl_conplano();

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$clrotulo->label("c60_descr");
$clrotulo->label("c53_descr");
$clrotulo->label("c53_coddoc");

///////////////////////////////////////////////////////////////////////
$instit   = db_getsession("DB_instit");
$contaold = null;
$anousu   = db_getsession("DB_anousu");

if (empty($data1)) {
  $data1 = $anousu."-01-01";
}
if (empty($data2)) {
  $data2 = $anousu."-12-31";
}
//--  monta sql
$txt_where  = " 1 = 1 ";
$txt_where .= " and conplanoreduz.c61_instit =" . db_getsession("DB_instit");
if (!empty($lista)) {
  $txt_where = $txt_where . " and conplanoreduz.c61_reduz in ($lista)";
}
if (isset($estrut_inicial) && $estrut_inicial != '') {

  $txt_where .= " and conplano.c60_estrut like '$estrut_inicial%' ";
}

//-----------------------------------------------------------------------------

$sql_analitico = "select
                        conplanoreduz.c61_codcon,
                        conplanoreduz.c61_reduz,
          		        conplano.c60_estrut,
                        conplano.c60_descr
                from conplanoreduz
                    inner join conplano     on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=conplanoreduz.c61_anousu
                where conplanoreduz.c61_anousu = " . $anousu . " and " . $txt_where . " order by conplano.c60_estrut";

//----------------------------------------------------------------------------

$res = db_query($sql_analitico);

$head2 = "RAZÃO POR CONTA";
$head5 = "PERÍODO : " . db_formatar($data1, 'd') . " à " . db_formatar($data2, 'd');

$pdf = new PDFDocument(); // abre a classe
$pdf->addHeaderDescription("\n".$head2);
$pdf->addHeaderDescription("\n\n".$head5);
$pdf->Open();          // abre o relatorio
$pdf->AliasNbPages();  // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$tam = '4';
$imprime_header = true;
$contador = 0;
$pdf->SetFont('Arial', '', 7);

if (!empty($sDocumentos)) {
  $txt_where .= " and c53_coddoc in ($sDocumentos)     ";
}

for($contas = 0; $contas < pg_numrows($res); $contas ++) {

  db_fieldsmemory($res, $contas);

  if (($contaold != $c61_reduz) && $contaold != null && $quebrapaginaporconta == 's') {

    $pdf->addpage("L");
    $repete = true;

  }
  $conta_atual = $c61_reduz;
  $txt_where2  = $txt_where . " and conplanoreduz.c61_reduz = $c61_reduz  and conplanoreduz.c61_instit = " . db_getsession("DB_instit");
  $txt_where2 .= " and c69_data between '$data1' and '$data2'  and conplanoreduz.c61_instit = " . db_getsession("DB_instit");

  $sql_analitico = "
    select conplanoreduz.c61_codcon,
           conplanoreduz.c61_reduz,
		       conplano.c60_estrut,
           conplano.c60_descr as conta_descr,
	         c69_codlan,
           c69_sequen,
           c69_data,
           c69_codhist,
           c53_coddoc,
           c53_descr,
           c69_debito,
			     debplano.c60_descr as debito_descr,
           c69_credito,
			     credplano.c60_descr as credito_descr,
           c69_valor,
           case when c69_debito = conplanoreduz.c61_reduz
             then 'D'
               else 'C'
           end  as tipo,
					 c50_codhist,
           c50_descr,
           c74_codrec,
           c79_codsup,
           c75_numemp,
           e60_codemp,
           e60_resumo,
           e60_anousu,
           c73_coddot,
           c76_numcgm,
           c78_chave,
           c72_complem ,
           z01_numcgm,
           z01_nome,
           ( select k81_codpla
               from conlancamcorrente
                    inner join corplacaixa on k82_data = c86_data and k82_id = c86_id and k82_autent = c86_autent
                    inner join placaixarec on k81_seqpla = k82_seqpla
              where c86_conlancam = c69_codlan ) as planilha,

           ( select distinct c84_slip
               from conlancamcorrente
                    inner join corlanc on c86_id     = k12_id
                                      and c86_data   = k12_data
                                      and c86_autent = k12_autent
                    inner join conlancamslip on c84_slip = k12_codigo
              where c86_conlancam = c69_codlan) as slip,
            m60_codmater,
            m60_descr,
            t52_bem,
            t52_descr,
            o15_codigo,
            o15_descr

      from conplanoreduz
           inner join conlancamval on  c69_anousu=conplanoreduz.c61_anousu and ( c69_debito=conplanoreduz.c61_reduz or c69_credito = conplanoreduz.c61_reduz)
           inner join conplano     on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=conplanoreduz.c61_anousu
           inner join conplanoreduz debval on debval.c61_anousu = conlancamval.c69_anousu and
                                              debval.c61_reduz  = conlancamval.c69_debito
           inner join conplano  debplano  on debplano.c60_anousu = debval.c61_anousu and
                                              debplano.c60_codcon = debval.c61_codcon
           inner join conplanoreduz credval on credval.c61_anousu = conlancamval.c69_anousu and
                                               credval.c61_reduz  = conlancamval.c69_credito
           inner join conplano  credplano  on credplano.c60_anousu = credval.c61_anousu and
                                              credplano.c60_codcon = credval.c61_codcon

           left join conhist          on c50_codhist = c69_codhist
           left join conlancamdoc on c71_codlan  = c69_codlan
           left join conhistdoc   on c53_coddoc  = conlancamdoc.c71_coddoc
           left join conlancamrec on c74_codlan = c69_codlan
                                       and c74_anousu = c69_anousu
           left join conlancamsup on c79_codlan = c69_codlan

           left join conlancamemp on c75_codlan = c69_codlan
           left join empempenho   on  e60_numemp = conlancamemp.c75_numemp

           inner join orctiporec on conplanoreduz.c61_codigo = o15_codigo

           left join conlancammatestoqueinimei on c103_conlancam        = c69_codlan
           left join matestoqueinimei          on c103_matestoqueinimei = m82_codigo
           left join matestoqueitem            on m71_codlanc           = m82_matestoqueitem
           left join matestoque                on m71_codmatestoque     = m70_codigo
           left join matmater                  on m70_codmatmater       = m60_codmater

           left join conlancambem on c110_codlan = c69_codlan
           left join bens         on c110_bem    = t52_bem

           left join conlancamdot on c73_codlan = c69_codlan
                                                  and c73_anousu = c69_anousu
           left join conlancamcgm on c76_codlan = c69_codlan
           left join cgm on z01_numcgm = c76_numcgm
           left join conlancamdig on c78_codlan = c69_codlan
           left join conlancamcompl on c72_codlan = c69_codlan
     where conplanoreduz.c61_anousu = {$anousu} and {$txt_where2}
     order by conplano.c60_estrut, c69_data,c69_codlan,c69_sequen ";

  $reslista = db_query($sql_analitico);

  if (!$reslista) {

    echo "ERRO<br><br><br><br><br>";
    die($sql_analitico);
  }




  if (pg_numrows($reslista) > 0) {

    db_fieldsmemory($reslista, 0);
    $datasaldo = $c69_data;
  } else {
    $datasaldo = $data1;
  }

  $sinal_dia         = '';
  $saldo_dia         = 0;
  $total_dia_debito  = 0;
  $total_dia_credito = 0;
  $tot_mov_debito    = 0;
  $tot_mov_credito   = 0;
  $saldo_anterior    = "";
  $repete            = "";
  $repete_colunas    = false;

  $iTotalRegistros = pg_num_rows($reslista);
  //------------------------------------------------------
  if ($iTotalRegistros > 0) {

    $iCor = 1;
    for($x = 0; $x < $iTotalRegistros; $x ++) {

      db_fieldsmemory($reslista, $x);

      if ($datasaldo != $c69_data && $saldopordia == 's') {

        $pdf->setX(203);
        $pdf->Cell(40, $tam, "MOVIMENTO DO DIA: ", "T", 0, "R", 0);
        $pdf->Cell(20, $tam, db_formatar($total_dia_debito, 'f'), "T", 0, "R", 0);
        $pdf->Cell(20, $tam, db_formatar($total_dia_credito, 'f'), "T", 0, "R", 0);
        // --- calcula saldo final ---

        if ($sinal_dia == "D")
          $total_dia_debito += $saldo_dia; else
          $total_dia_credito += $saldo_dia;

        if ($total_dia_debito > $total_dia_credito)
          $sinal_dia = "D"; else
          $sinal_dia = "C";

        $pdf->ln();
        $pdf->setX(203);
        $pdf->Cell(40, $tam, "SALDO DIA:", '0', 0, "R", 0);

        $saldo_dia = abs($total_dia_debito - $total_dia_credito);

        if ($sinal_dia == 'D') {
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 0, "R", 0);
          $pdf->Cell(20, $tam, '', '0', 1, "R", 0);
        } else {
          $pdf->Cell(20, $tam, '', '0', 0, "R", 0);
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 1, "R", 0);
        }
        $pdf->setX(203);
        $pdf->Cell(80, $tam, " ", "T", 0, "R", 0);
        $pdf->ln();

        $total_dia_debito = 0;
        $total_dia_credito = 0;

      }
      $datasaldo = $c69_data;
      if ($repete != $c61_codcon) {

        // --- imprime movimentação da conta anterior, se houver conta anterior
        if ($repete != "") {

          $pdf->setX(200);
          $pdf->Cell(40, $tam, "TOTAIS DA MOVIMENTAÇÃO:", 'T', 0, "R", 0);
          $pdf->Cell(20, $tam, db_formatar($tot_mov_debito, 'f'), 'T', 0, "R", 0);
          $pdf->Cell(20, $tam, db_formatar($tot_mov_credito, 'f'), 'T', 0, "R", 0);
          $pdf->ln();
          // --- calcula saldo final ---
          if ($tot_mov_debito > $tot_mov_credito)
            $sinal_final = "D"; else
            $sinal_final = "C";
          if ($saldo_anterior != "") {
            if ($sinal_anterior == "D")
              $tot_mov_debito += $saldo_anterior; else
              $tot_mov_credito += $saldo_anterior;
          }

          $pdf->setX(228);
          $pdf->Cell(30, $tam, "SALDO FINAL:", '0', 0, "R", 0);
          $pdf->Cell(5, $tam, $sinal_final, '0', 0, "C", 0);
          $total_saldo_final = $tot_mov_debito - $tot_mov_credito;
          $pdf->Cell(20, $tam, db_formatar(($total_saldo_final < 0 ? $total_saldo_final * - 1 : $total_saldo_final), 'f'), '0', 0, "R", 0);
          $pdf->ln();
          // --- fim calculo saldo  final -- // --


        }
        //------------------ //  ------------------
        $repete = $c61_codcon;
        $repete_colunas = true;
        $pdf->Ln(2);
        $pdf->Cell(20, $tam, "REDUZIDO:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "$c61_reduz", 0, 1, "L", 0);
        $pdf->Cell(20, $tam, "RECURSO:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "{$o15_codigo} - {$o15_descr}", 0, 1, "L", 0);
        $pdf->Cell(20, $tam, "ESTRUTURAL:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "$c60_estrut", 0, 1, "L", 0);
        $pdf->Cell(20, $tam, "DESCRIÇÃO:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "$conta_descr", 0, 0, "L", 0);
        $pdf->Ln();
        //--- saldo anterior
        $saldo_anterior     = 0;
        $sinal_anterior     = 'D';
        $saldo_final_funcao = 0;
        $c61_reduz_old      = $c61_reduz; // na função abaixoa tem um GLobal c61_reduz...
        db_inicio_transacao();
        $r_anterior = db_planocontassaldo_matriz($anousu, $data1, $data2, false, "c61_reduz = $c61_reduz and c61_instit=$instit");
        db_fim_transacao(true);
        @ $saldo_anterior     = pg_result($r_anterior, 0, "saldo_anterior");
        @ $sinal_anterior     = pg_result($r_anterior, 0, "sinal_anterior");
        @ $saldo_final_funcao = pg_result($r_anterior, 0, "saldo_final");
        $c61_reduz            = $c61_reduz_old; // devolvemos o valor a globals;

        $pdf->setX(228);
        $pdf->Cell(30, $tam, "SALDO ANTERIOR:", '0', 0, "R", 0);
        $pdf->Cell(5, $tam, $sinal_anterior, '0', 0, "C", 0);
        $pdf->Cell(20, $tam, db_formatar($saldo_anterior, 'f'), '0', 0, "R", 0);
        $pdf->ln();
        //-----------------------------
        //---- totalizadores do movimento
        $tot_mov_debito  = 0;
        $tot_mov_credito = 0;

        $sinal_dia = $sinal_anterior;
        $saldo_dia = $saldo_anterior;

      }
      // -- header das colunas
      if ($repete_colunas == true) {

        $repete_colunas = false;
        $pdf->Cell(20, $tam, "LAN", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "SEQ", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "DATA", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "RECEITA", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "DOTAÇÃO", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "EMPENHO", '1', 0, "L", 0);
        $pdf->Cell(23, $tam, "SUPLEMENTAÇÃO", '1', 0, "L", 0);
        $pdf->Cell(90, $tam, "DOCUMENTO", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "DÉBITO", '1', 0, "R", 0);
        $pdf->Cell(20, $tam, "CRÉDITO", '1', 1, "R", 0);
      }

      if ($iCor == 0) {
        $iCor = 1;
      } else {
        $iCor = 0;
      }

      $pdf->Cell(20, $tam, "$c69_codlan", 0, 0, "L", $iCor);
      $pdf->Cell(20, $tam, "$c69_sequen", 0, 0, "L", $iCor);
      $pdf->Cell(20, $tam, db_formatar($c69_data, 'd'), 0, 0, "C", $iCor);

      $pdf->Cell(20, $tam, "$c74_codrec", '0', 0, "L", $iCor);
      $pdf->Cell(20, $tam, "$c73_coddot", '0', 0, "L", $iCor);

      $sNumeroEmpenho = "{$e60_codemp} / {$e60_anousu}";
      if (empty($e60_codemp)) {
        $sNumeroEmpenho = "";
      }
      $pdf->Cell(20, $tam, $sNumeroEmpenho, '0', 0, "L", $iCor);
      $pdf->Cell(23, $tam, "$c79_codsup", '0', 0, "L", $iCor);
      $pdf->Cell(90, $tam, "$c53_coddoc-$c53_descr", 0, 0, "L", $iCor);
      if ($tipo == "C") {
        $pdf->Cell(20, $tam, "", 0, 0, "R", $iCor); // imprime esse espação no lugar do debito
        $pdf->Cell(20, $tam, db_formatar($c69_valor, 'f'), 0, 0, "R", $iCor);
      } else {
        $pdf->Cell(20, $tam, db_formatar($c69_valor, 'f'), 0, 0, "R", $iCor);
        $pdf->Cell(20, $tam, "", 0, 0, "R", $iCor); // imprime esse espação no lugar do credito
      }
      // -- totalizadores do movimento -------------
      if ($tipo == "D") {
        $tot_mov_debito   += $c69_valor;
        $total_dia_debito += $c69_valor;
      } else {
        $tot_mov_credito   += $c69_valor;
        $total_dia_credito += $c69_valor;
      }
      //--------------   //   ----------------------

      if ($contrapartida == "on") {

        $pdf->ln();
        $pdf->Cell(40, $tam, "", 0, 0, "L", $iCor);
        $pdf->Cell(30, $tam, "CONTRAPARTIDA :", 0, 0, "L", $iCor); // imprime esse espação no lugar do debito

        if ($c61_reduz == $c69_debito) {
          $pdf->Cell(203, $tam, "($c69_credito) $credito_descr ", 0, 1, "L", $iCor);
        } else {
          $pdf->Cell(203, $tam, "($c69_debito) $debito_descr ", 0, 1, "L", $iCor);
        }
      } else {
        $pdf->ln();
      }
      if ($relatorio == "a") {

        if ( $contrapartida == 'off') {
          $pdf->ln(2);
        }
        $txt = "";
        if ($c75_numemp != "") {
          $txt = $e60_resumo;
        }

        if (isset($z01_numcgm) && $z01_numcgm != '') {
          $txt = " CGM: $z01_numcgm : $z01_nome, " . $txt;
        }

        $sHistorico = "HISTÓRICO: {$c50_descr} {$c72_complem} {$txt}";
        $nMulticellHeight = $pdf->getMultiCellHeight(233, $tam, $sHistorico);
        if (!empty($planilha)) {

          $pdf->Cell(40, $tam, "", 0, 0, "L", $iCor);
          $pdf->Cell(233, $tam, "PLANILHA: {$planilha}", 0, 1, "L", $iCor);
        }
        if (!empty($slip)) {

          $pdf->Cell(40, $tam, "", 0, 0, "L", $iCor);
          $pdf->Cell(233, $tam, "SLIP:  {$slip}", 0, 1, "L", $iCor);
        }
        if (in_array($c53_coddoc, array(BAIXA_BENS, APURACAO_VALOR_LIQUIDO)) && !empty($t52_bem)) {

          $pdf->Cell(40, $tam, "", 0, 0, "L", $iCor);
          $pdf->Cell(233, $tam, "BEM: {$t52_bem} - {$t52_descr}", 0, 1, "L", $iCor);
        }
        if (in_array($c53_coddoc, array(ENTRADA_MANUAL_ESTOQUE, SAIDA_MANUAL_ESTOQUE)) && !empty($m60_codmater)) {

          $pdf->Cell(40, $tam, "", 0, 0, "L", $iCor);
          $pdf->Cell(233, $tam, "MATERIAL: {$m60_codmater} - {$m60_descr}", 0, 1, "L", $iCor);
        }
        $pdf->Cell(40, $nMulticellHeight, "", 0, 0, "L", $iCor);
        $pdf->multicell(233, $tam, $sHistorico, 0, 1, $iCor); // recurso
      }

    } // end for
    // imprime totalizador da movimentação
    if ($iTotalRegistros > 0) {

      if ($saldopordia == 's') {

        $pdf->ln();
        $pdf->setX(203);
        $pdf->Cell(40, $tam, "MOVIMENTO DO DIA: ", "T", 0, "R", 0);
        $pdf->Cell(20, $tam, db_formatar($total_dia_debito, 'f'), "T", 0, "R", 0);
        $pdf->Cell(20, $tam, db_formatar($total_dia_credito, 'f'), "T", 0, "R", 0);
      }
      // --- calcula saldo final ---

      if ($sinal_dia == "D")
        $total_dia_debito += $saldo_dia; else
        $total_dia_credito += $saldo_dia;

      if ($total_dia_debito > $total_dia_credito)
        $sinal_dia = "D"; else
        $sinal_dia = "C";

      if ($saldopordia == 's') {
        $pdf->ln();
        $pdf->setX(203);
        $pdf->Cell(40, $tam, "SALDO DIA:", '0', 0, "R", 0);
      }

      $saldo_dia = abs($total_dia_debito - $total_dia_credito);

      if ($sinal_dia == 'D') {
        if ($saldopordia == 's') {
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 0, "R", 0);
          $pdf->Cell(20, $tam, '', '0', 1, "R", 0);
        }
      } else {
        if ($saldopordia == 's') {
          $pdf->Cell(20, $tam, '', '0', 0, "R", 0);
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 1, "R", 0);
        }
      }
      if ($saldopordia == 's') {
        $pdf->setX(203);
        $pdf->Cell(80, $tam, " ", "T", 0, "R", 0);
      }

      $total_dia_debito = 0;
      $total_dia_debito = 0;

      $pdf->setX(203);

      $pdf->Cell(40, $tam, "TOTAIS DA MOVIMENTAÇÃO:", 'T', 0, "R", 0);
      $pdf->Cell(20, $tam, db_formatar($tot_mov_debito, 'f'), 'T', 0, "R", 0);
      $pdf->Cell(20, $tam, db_formatar($tot_mov_credito, 'f'), 'T', 0, "R", 0);
      $pdf->ln();
      // --- calcula saldo final ---
      if ($saldo_anterior != "") {
        if ($sinal_anterior == "D")
          $tot_mov_debito += $saldo_anterior; else
          $tot_mov_credito += $saldo_anterior;
      }

      if ($tot_mov_debito > $tot_mov_credito)
        $sinal_final = "D"; else
        $sinal_final = "C";

      $pdf->setX(203);
      $pdf->Cell(40, $tam, "SALDO FINAL:", '0', 0, "R", 0);
      $total_saldo_final = $tot_mov_debito - $tot_mov_credito;

      if ($sinal_final == 'D') {
        $pdf->Cell(20, $tam, db_formatar(abs($total_saldo_final), 'f'), '0', 0, "R", 0);
        $pdf->Cell(20, $tam, '', '0', 1, "R", 0);
      } else {
        $pdf->Cell(20, $tam, '', '0', 0, "R", 0);
        $pdf->Cell(20, $tam, db_formatar(abs($total_saldo_final), 'f'), '0', 1, "R", 0);
      }

      $pdf->ln();
      //--- fim calculo saldo final
    }
  } else {

    $reduz = $c61_reduz;
    $descr = $c60_descr;
    $estrut = $c60_estrut;

    db_inicio_transacao();
    $r_anterior = db_planocontassaldo_matriz($anousu, $data1, $data2, false, "c61_reduz = $reduz and c61_instit=$instit");
    db_fim_transacao(true);
    if ($contasemmov == 's') {

      $saldo_anterior = @ pg_result($r_anterior, 0, "saldo_anterior");
      $sinal_anterior = @ pg_result($r_anterior, 0, "sinal_anterior");
      $saldo_final_funcao = @pg_result($r_anterior, 0, "saldo_final");
      $pdf->Ln(2);
      $pdf->Cell(20, $tam, "REDUZIDO:", 0, 0, "L", 0);
      $pdf->Cell(20, $tam, "$reduz", 0, 1, "L", 0);
      $pdf->Cell(20, $tam, "ESTRUTURAL:", 0, 0, "L", 0);
      $pdf->Cell(20, $tam, "$c60_estrut", 0, 1, "L", 0);
      $pdf->Cell(20, $tam, "DESCRIÇÃO:", 0, 0, "L", 0);
      $pdf->Cell(20, $tam, "$descr", 0, 0, "L", 0);
      $pdf->Ln();
      $pdf->setX(228);
      $pdf->Cell(30, $tam, "SALDO ANTERIOR:", 'B', 0, "R", 0);
      $pdf->Cell(5, $tam, $sinal_anterior, 'B', 0, "C", 0);
      $pdf->Cell(20, $tam, db_formatar($saldo_anterior, 'f'), 'B', 0, "R", 0);
      $pdf->ln();
      $pdf->Cell(20, $tam, "LAN", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "SEQ", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "DATA", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "RECEITA", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "DOTAÇÃO", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "EMPENHO", '1', 0, "L", 0);
      $pdf->Cell(23, $tam, "SUPLEMENTAÇÃO", '1', 0, "L", 0);
      $pdf->Cell(90, $tam, "DOCUMENTO", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "DÉBITO", '1', 0, "R", 0);
      $pdf->Cell(20, $tam, "CRÉDITO", '1', 1, "R", 0);
      $pdf->Ln();
      // saldo final
      $pdf->setX(228);
      $pdf->Cell(30, $tam, "SALDO FINAL:", '0', 0, "R", 0);
      $pdf->Cell(5, $tam, $sinal_final, '0', 0, "C", 0);
      $pdf->Cell(20, $tam, db_formatar(($saldo_anterior < 0 ? $saldo_anterior * - 1 : $saldo_anterior), 'f'), '0', 0, "R", 0);
      $pdf->ln();
    }
  }

  $contaold = null;
  if ($iTotalRegistros > 0 || $contasemmov == 's') {
    $contaold = $conta_atual;
  }
}
$pdf->output();
