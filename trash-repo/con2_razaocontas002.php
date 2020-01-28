<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include ("fpdf151/pdf.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcorgao_classe.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_conlancamcgm_classe.php");
include ("classes/db_conlancamval_classe.php");
include ("classes/db_conlancam_classe.php");
include ("classes/db_orcsuplem_classe.php");
include ("classes/db_conlancamrec_classe.php");
include ("classes/db_conlancamemp_classe.php");
include ("classes/db_conlancamdot_classe.php");
include ("classes/db_conlancamdig_classe.php");
include ("libs/db_libcontabilidade.php");
include ("classes/db_conplano_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo();
$clconlancamval = new cl_conlancamval();
$clconlancamcgm = new cl_conlancamcgm();
$clconlancam = new cl_conlancam();
$auxiliar = new cl_conlancam();
$clorcsuplem = new cl_orcsuplem();
$clconlancamrec = new cl_conlancamrec();
$clconlancamemp = new cl_conlancamemp();
$clconlancamdot = new cl_conlancamdot();
$clconlancamdig = new cl_conlancamdig();
$clconplano = new cl_conplano();

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$clrotulo->label("c60_descr");
$clrotulo->label("c53_descr");
$clrotulo->label("c53_coddoc");

///////////////////////////////////////////////////////////////////////
$data1 = "";
$data2 = "";
$instit = db_getsession("DB_instit");
$contaold = null;
@ $data1 = "$data1_ano-$data1_mes-$data1_dia";
@ $data2 = "$data2_ano-$data2_mes-$data2_dia";

$anousu = db_getsession("DB_anousu");

if (strlen($data1) < 7) {
  $data1 = $anousu . "-01-01";
}
if (strlen($data2) < 7) {
  $data2 = $anousu . "-12-31";
}

//---------
if (isset($lista)) {
  $w = "(";
  $tamanho = sizeof($lista);
  for($x = 0; $x < sizeof($lista); $x ++) {
    $w = $w . "$lista[$x]";
    if ($x < $tamanho - 1) {
      $w = $w . ",";
    }
  }
  $w = $w . ")";
}

//$sql = "select c61_codcon from conplanoreduz where c61_reduz in $w";
//$res = pg_exec($sql);
// $codcon = pg_result($res, 0, 0);
//--  monta sql
$txt_where = "1=1";
$txt_where .= "and conplanoreduz.c61_instit =" . db_getsession("DB_instit");
if (isset($lista)) {
  $txt_where = $txt_where . " and conplanoreduz.c61_reduz in $w";
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


//echo $sql_analitico; exit;


$res = pg_exec($sql_analitico);
// db_criatabela($res);
// exit;


$head2 = "RAZÃO POR CONTA";
$head5 = "PERÍODO : " . db_formatar($data1, 'd') . " à " . db_formatar($data2, 'd');

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$tam = '4';
$imprime_header = true;
$contador = 0;
$pdf->SetFont('Arial', '', 7);

if (isset($c53_coddoc) && $c53_coddoc > 0) {
  $txt_where .= " and c53_coddoc = $c53_coddoc     ";

}

for($contas = 0; $contas < pg_numrows($res); $contas ++) {
  
  db_fieldsmemory($res, $contas);
  if (($contaold != $c61_reduz) && $contaold != null && $quebrapaginaporconta == 's') {
    
    //if (isset($contasemmov))
    // {
    $pdf->addpage("L");
    $repete = true;
    //}
  

  }
  $conta_atual = $c61_reduz;
  
  $txt_where2 = $txt_where . " and conplanoreduz.c61_reduz = $c61_reduz  and conplanoreduz.c61_instit =" . db_getsession("DB_instit");
  
  $txt_where2 .= " and c69_data between '$data1' and '$data2'  and conplanoreduz.c61_instit =" . db_getsession("DB_instit");
  
  $sql_analitico = "select
                        conplanoreduz.c61_codcon,
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
                        case when c69_debito = conplanoreduz.c61_reduz then 
                        'D' 
                        else 'C' end  as tipo,                      
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
						z01_nome
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

                     left outer join conlancamdoc on c71_codlan  = c69_codlan 
                     left outer join conhistdoc   on c53_coddoc  = conlancamdoc.c71_coddoc 
                     left outer join conlancamrec on c74_codlan = c69_codlan 
                                                 and c74_anousu = c69_anousu
                     left outer join conlancamsup on c79_codlan = c69_codlan

		     left outer join conlancamemp on c75_codlan = c69_codlan
		     left outer join empempenho   on  e60_numemp = conlancamemp.c75_numemp

		     left outer join conlancamdot on c73_codlan = c69_codlan
                                                 and c73_anousu = c69_anousu
		     left join conlancamcgm on c76_codlan = c69_codlan
		     left join  cgm on z01_numcgm = c76_numcgm
		     left outer join conlancamdig on c78_codlan = c69_codlan
		     left outer join conlancamcompl on c72_codlan = c69_codlan
         where conplanoreduz.c61_anousu = " . $anousu . " and " . $txt_where2 . " order by conplano.c60_estrut, c69_data,c69_codlan,c69_sequen";
  
  $reslista = pg_exec($sql_analitico);
  if (pg_numrows($reslista) > 0) {
    db_fieldsmemory($reslista, 0);
    
    $datasaldo = $c69_data;
  } else {
    $datasaldo = $data1;
  }
  
  $sinal_dia = '';
  $saldo_dia = 0;
  $total_dia_debito = 0;
  $total_dia_credito = 0;
  
  $tot_mov_debito = 0;
  $tot_mov_credito = 0;
  $saldo_anterior = "";
  $repete = "";
  $repete_colunas = false;
  
  //------------------------------------------------------
  if (pg_numrows($reslista) > 0) {
    
    for($x = 0; $x < pg_numrows($reslista); $x ++) {
      db_fieldsmemory($reslista, $x);
      
      if ($datasaldo != $c69_data and isset($saldopordia)) {
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
          } else {
            // não sei
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
        $pdf->Cell(20, $tam, "ESTRUTURAL:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "$c60_estrut", 0, 1, "L", 0);
        $pdf->Cell(20, $tam, "DESCRIÇÃO:", 0, 0, "L", 0);
        $pdf->Cell(20, $tam, "$conta_descr", 0, 0, "L", 0);
        $pdf->Ln();
        //--- saldo anterior
        $saldo_anterior = 0;
        $sinal_anterior = 'D';
        $saldo_final_funcao = 0;
        $c61_reduz_old = $c61_reduz; // na função abaixoa tem um GLobal c61_reduz...
        db_inicio_transacao();
        $r_anterior = db_planocontassaldo_matriz($anousu, $data1, $data2, false, "c61_reduz = $c61_reduz and c61_instit=$instit");
        db_fim_transacao(true);
        @ $saldo_anterior = pg_result($r_anterior, 0, "saldo_anterior");
        @ $sinal_anterior = pg_result($r_anterior, 0, "sinal_anterior");
        @ $saldo_final_funcao = pg_result($r_anterior, 0, "saldo_final");
        $c61_reduz = $c61_reduz_old; // devolvemos o valor a globals;
        

        $pdf->setX(228);
        $pdf->Cell(30, $tam, "SALDO ANTERIOR:", '0', 0, "R", 0);
        $pdf->Cell(5, $tam, $sinal_anterior, '0', 0, "C", 0);
        $pdf->Cell(20, $tam, db_formatar($saldo_anterior, 'f'), '0', 0, "R", 0);
        $pdf->ln();
        //-----------------------------
        //---- totalizadores do movimento
        $tot_mov_debito = 0;
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
        $pdf->Cell(20, $tam, "DOTAÇÂO", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "EMPENHO", '1', 0, "L", 0);
        $pdf->Cell(23, $tam, "SUPLEMENTAÇÂO", '1', 0, "L", 0);
        $pdf->Cell(90, $tam, "DOCUMENTO", '1', 0, "L", 0);
        $pdf->Cell(20, $tam, "DEBITO", '1', 0, "R", 0);
        $pdf->Cell(20, $tam, "CREDITO", '1', 1, "R", 0);
      }
      // $pdf->Ln();
      $pdf->Cell(20, $tam, "$c69_codlan", 0, 0, "L", 0);
      $pdf->Cell(20, $tam, "$c69_sequen", 0, 0, "L", 0);
      $pdf->Cell(20, $tam, db_formatar($c69_data, 'd'), 0, 0, "L", 0);
      
      //if (empty ($c53_coddoc))
      //		$pdf->cell(173, $tam, "HISTORICO: $c50_descr ".substr($c72_complem,1,95)."...", 0, 0, "L", 0); // recurso
      //	else {
      $pdf->Cell(20, $tam, "$c74_codrec", '0', 0, "L", 0);
      $pdf->Cell(20, $tam, "$c73_coddot", '0', 0, "L", 0);
      $pdf->Cell(20, $tam, "$e60_codemp" . "/" . "$e60_anousu", '0', 0, "L", 0);
      $pdf->Cell(23, $tam, "$c79_codsup", '0', 0, "L", 0);
      // $pdf->Cell(90, $tam, "( $c53_coddoc ) $c53_descr", 0, 0, "L", 0);
      $pdf->Cell(90, $tam, "$c53_descr", 0, 0, "L", 0);
      //}
      if ($tipo == "C")
        $pdf->Cell(20, $tam, "", 0, 0, "R", 0); // imprime esse espação no lugar do debito 
      $pdf->Cell(20, $tam, db_formatar($c69_valor, 'f'), 0, 0, "R", 0);
      // -- totalizadores do movimento -------------
      if ($tipo == "D") {
        $tot_mov_debito += $c69_valor;
        $total_dia_debito += $c69_valor;
      } else {
        $tot_mov_credito += $c69_valor;
        $total_dia_credito += $c69_valor;
      }
      //--------------   //   ----------------------
      

      if (isset($contrapartida) && $contrapartida == "on") {
        $pdf->ln();
        $pdf->setX(40);
        
        $pdf->Cell(30, $tam, "CONTRAPARTIDA :", 0, 0, "L", 0); // imprime esse espação no lugar do debito
        

        if ($c61_reduz == $c69_debito) {
          $pdf->Cell(100, $tam, "($c69_credito) $credito_descr ", 0, 1, "L", 0);
        } else {
          $pdf->Cell(100, $tam, "($c69_debito) $debito_descr ", 0, 1, "L", 0);
        }
      } else {
        $pdf->ln();
      }
      if ($relatorio == "a") {
        if (! isset($contrapartida)) {
          $pdf->ln(2);
        }
        //if (empty ($c53_coddoc)) {
        $txt = "";
        if ($c75_numemp != "") {
          $txt = $e60_resumo;
        }
        if (isset($z01_numcgm) && $z01_numcgm != '') {
          $txt = " CGM: $z01_numcgm : $z01_nome, " . $txt;
        }
        $pdf->setX(40);
        $pdf->multicell(200, $tam, "HISTORICO: $c50_descr $c72_complem  $txt ", 0, 1, 0); // recurso
      

      //}
      }
    
    } // end for
    // imprime totalizador da movimentação 
    if (pg_num_rows($reslista) > 0) {
      
      if (isset($saldopordia)) {
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
      
      if (isset($saldopordia)) {
        $pdf->ln();
        $pdf->setX(203);
        $pdf->Cell(40, $tam, "SALDO DIA:", '0', 0, "R", 0);
      }
      
      $saldo_dia = abs($total_dia_debito - $total_dia_credito);
      
      if ($sinal_dia == 'D') {
        if (isset($saldopordia)) {
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 0, "R", 0);
          $pdf->Cell(20, $tam, '', '0', 1, "R", 0);
        }
      } else {
        if (isset($saldopordia)) {
          $pdf->Cell(20, $tam, '', '0', 0, "R", 0);
          $pdf->Cell(20, $tam, db_formatar(abs($saldo_dia), 'f'), '0', 1, "R", 0);
        }
      }
      if (isset($saldopordia)) {
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
      } else {
        // não sei
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
    //include("fpdf151/geraarquivo.php");
    }
    //$contaold = $c61_reduz;
  } else {
    
    $reduz = $c61_reduz;
    $descr = $c60_descr;
    $estrut = $c60_estrut;
    
    db_inicio_transacao();
    $r_anterior = db_planocontassaldo_matriz($anousu, $data1, $data2, false, "c61_reduz = $reduz and c61_instit=$instit");
    db_fim_transacao(true);
    //  db_criatabela($r_anterior);
    if (isset($contasemmov)) {
      
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
      $pdf->Cell(20, $tam, "DOTAÇÂO", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "EMPENHO", '1', 0, "L", 0);
      $pdf->Cell(23, $tam, "SUPLEMENTAÇÂO", '1', 0, "L", 0);
      $pdf->Cell(90, $tam, "DOCUMENTO", '1', 0, "L", 0);
      $pdf->Cell(20, $tam, "DEBITO", '1', 0, "R", 0);
      $pdf->Cell(20, $tam, "CREDITO", '1', 1, "R", 0);
      $pdf->Ln();
      // saldo final
      $pdf->setX(228);
      $pdf->Cell(30, $tam, "SALDO FINAL:", '0', 0, "R", 0);
      $pdf->Cell(5, $tam, $sinal_final, '0', 0, "C", 0);
      // $total_saldo_final = $tot_mov_debito - $tot_mov_credito;
      $pdf->Cell(20, $tam, db_formatar(($saldo_anterior < 0 ? $saldo_anterior * - 1 : $saldo_anterior), 'f'), '0', 0, "R", 0);
      $pdf->ln();
    
    }
  
  }
  if (pg_num_rows($reslista) > 0 || isset($contasemmov)) {
    $contaold = $conta_atual;
  } else {
    
    $contaold = null;
  
  }
}
$pdf->output();
?>