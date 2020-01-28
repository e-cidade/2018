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


include ("fpdf151/pdf.php");
include ("libs/db_liborcamento.php");
include ("libs/db_sql.php");
include ("fpdf151/assinatura.php");
include ("dbforms/db_balanc_desp.php");

$classinatura = new cl_assinatura;

//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$estrut = str_replace('.', '', $elemento);
//echo $estrut;exit;

$head1 = "BALANCETE DA DESPESA POR ELEMENTO";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
  } else {
       $descr_inst .= $xvirg.$nomeinst;
  }

	$xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head7 = "PERÍODO : ".db_formatar($perini, 'd').' a '.db_formatar($perfin, 'd');
$descrtit = "O ELEMENTO";
$sele_work = " e.o56_elemento like '".$estrut."%' and w.o58_instit in (".str_replace('-', ', ', $db_selinstit).") ";
//$sele_work = "";
$anousu = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$codigo_recurso = "";
if (isset ($imprime_recurso) && ($imprime_recurso == "sim")) {
	$result = db_dotacao_elemento_recurso($anousu, $dataini, $datafin, $sele_work);
} else {
	$result = db_dotacaosaldo(7, 3, 4, true, $sele_work, $anousu, $dataini, $datafin);
}

pg_exec("commit");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$pagina = 1;
$alt = 4;

$r_totgeraldot_ini = $totgeraldot_ini = 0;
$r_totgeralsuplementado_acumulado = $totgeralsuplementado_acumulado = 0;
$r_totgeralreduzido_acumulado = $totgeralreduzido_acumulado = 0;
$r_totgeralatual = $totgeralatual = 0;
$r_totgeralempenhado = $totgeralempenhado = 0;
$r_totgeralanulado = $totgeralanulado = 0;
$r_totgeralliquidado = $totgeralliquidado = 0;
$r_totgeralpago = $totgeralpago = 0;
$r_totgeralatual_a_pagar = $totgeralatual_a_pagar = 0;
$r_totgeralempenhado_acumulado = $totgeralempenhado_acumulado = 0;
$r_totgeralanulado_acumulado = $totgeralanulado_acumulado = 0;
$r_totgeralliquidado_acumulado = $totgeralliquidado_acumulado = 0;
$r_totgeralpago_acumulado = $totgeralpago_acumulado = 0;
$r_totgeralatual_a_pagar_liquidado = $totgeralatual_a_pagar_liquidado = 0;


//db_criatabela($result);exit;

for ($i = 0; $i < pg_numrows($result); $i ++) {

	db_fieldsmemory($result, $i);
	$codigo = db_formatar($o58_elemento, 'elemento');
	$descr = $o56_descr;

	if ($pdf->gety() > $pdf->h - 40 || $pagina == 1) {
		$pagina = 0;

		$pdf->addpage();
		$pdf->setfont('arial', 'b', 7);

		$pdf->ln(2);
		$pdf->cell(40, $alt, "", 0, 0, "C", 0);
		$pdf->cell(30, $alt, "SALDO INICIAL", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "SUPLEMENTAÇÕES", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "REDUÇÕES", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "TOTAL CRÉDITOS", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "SALDO DISPONÍVEL", 0, 1, "R", 0);
		$pdf->cell(40, $alt, "DOTAÇÃO", 0, 0, "L", 0);
		$pdf->cell(30, $alt, "EMPENHADO NO MÊS", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "ANULADO NO MÊS", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "LIQUIDADO NO MÊS", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "PAGO NO MÊS", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "A LIQUIDAR", 0, 1, "R", 0);
		$pdf->cell(40, $alt, "", 0, 0, "L", 0);
		$pdf->cell(30, $alt, "EMPENHADO NO ANO", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "ANULADO NO ANO", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "LIQUIDADO NO ANO", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "PAGO NO ANO", 0, 0, "R", 0);
		$pdf->cell(30, $alt, "A PAGAR LIQUIDO", 0, 1, "R", 0);
		$pdf->cell(0, $alt, '', "T", 1, "C", 0);

	}

	if (isset ($imprime_recurso) && ($imprime_recurso == "sim")) {
		if ($codigo_recurso != $o15_codigo) {
			if ($codigo_recurso != "") { // nesse caso tempos um totalizador
				$pdf->ln(3);
				$pdf->setfont('arial', 'b', 7);
				$pdf->cell(10, $alt, '', 0, 0, "L", 0);
				$pdf->cell(30, $alt, 'TOTAL DO RECURSO', 0, 0, "L", 0, '.');
				$pdf->cell(30, $alt, db_formatar($r_totgeraldot_ini, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralsuplementado_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeraldot_ini + $r_totgeralsuplementado_acumulado - $r_totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralatual, 'f'), 0, 1, "R", 0);
				$pdf->cell(40, $alt, "", 0, 0, "L", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralempenhado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralanulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralliquidado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralpago, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralatual_a_pagar, 'f'), 0, 1, "R", 0);
				$pdf->cell(40, $alt, "", 0, 0, "L", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralempenhado_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralanulado_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralliquidado_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralpago_acumulado, 'f'), 0, 0, "R", 0);
				$pdf->cell(30, $alt, db_formatar($r_totgeralatual_a_pagar_liquidado, 'f'), 0, 1, "R", 0);
				$pdf->setfont('arial', '', 7);
				$r_totgeraldot_ini = 0;
				$r_totgeralsuplementado_acumulado = 0;
				$r_totgeralreduzido_acumulado = 0;
				$r_totgeralatual = 0;
				$r_totgeralempenhado = 0;
				$r_totgeralanulado = 0;
				$r_totgeralliquidado = 0;
				$r_totgeralpago = 0;
				$r_totgeralatual_a_pagar = 0;
				$r_totgeralempenhado_acumulado = 0;
				$r_totgeralanulado_acumulado = 0;
				$r_totgeralliquidado_acumulado = 0;
				$r_totgeralpago_acumulado = 0;
				$r_totgeralatual_a_pagar_liquidado = 0;

			}
			$codigo_recurso = $o15_codigo;
			$pdf->ln(3);
			$pdf->setfont('arial', 'B', 10);
			$pdf->cell(10, $alt, $o15_codigo.'  -  '.$o15_descr, 0, 1, "L", 0); // imprime estrutural mais descrição
			$pdf->setfont('arial', '', 7);
		}

	}
	$pdf->ln();
	$pdf->setfont('arial', 'B', 8);
	$pdf->cell(10, $alt, $codigo.'  -  '.$descr, 0, 1, "L", 0); // imprime estrutural mais descrição
	$pdf->setfont('arial', '', 7);
	$pdf->cell(10, $alt, '', 0, 0, "L", 0);
	$pdf->cell(30, $alt, '', 0, 0, "L", 0, '.');

	$pdf->cell(30, $alt, db_formatar($dot_ini, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($suplementado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($reduzido_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($dot_ini + $suplementado_acumulado - $reduzido_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($atual, 'f'), 0, 1, "R", 0);

	$pdf->cell(40, $alt, "", 0, 0, "L", 0);
	$pdf->cell(30, $alt, db_formatar($empenhado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($anulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($liquidado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($pago, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado, 'f'), 0, 1, "R", 0);

	$pdf->cell(40, $alt, "", 0, 0, "L", 0);
	$pdf->cell(30, $alt, db_formatar($empenhado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($anulado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($liquidado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($pago_acumulado, 'f'), 0, 0, "R", 0);
	// $pdf->cell(30, $alt, db_formatar($atual_a_pagar_liquidado, 'f'), 0, 1, "R", 0);
        $pdf->cell(30, $alt, db_formatar($liquidado_acumulado-$pago_acumulado, 'f'), 0, 1, "R", 0);

	$pdf->ln(3);

	$totgeraldot_ini += $dot_ini;
	$r_totgeraldot_ini += $dot_ini;

	$totgeralsuplementado_acumulado += $suplementado_acumulado;
	$r_totgeralsuplementado_acumulado += $suplementado_acumulado;
	$totgeralreduzido_acumulado += $reduzido_acumulado;
	$r_totgeralreduzido_acumulado += $reduzido_acumulado;
	$totgeralatual += $atual;
	$r_totgeralatual += $atual;

	$totgeralempenhado += $empenhado;
	$r_totgeralempenhado += $empenhado;
	$totgeralanulado += $anulado;
	$r_totgeralanulado += $anulado;
	$totgeralliquidado += $liquidado;
	$r_totgeralliquidado += $liquidado;
	$totgeralpago += $pago;
	$r_totgeralpago += $pago;
	$totgeralatual_a_pagar += $empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado;
	$r_totgeralatual_a_pagar += $empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado;

	$totgeralempenhado_acumulado += $empenhado_acumulado;
	$r_totgeralempenhado_acumulado += $empenhado_acumulado;
	$totgeralanulado_acumulado += $anulado_acumulado;
	$r_totgeralanulado_acumulado += $anulado_acumulado;
	$totgeralliquidado_acumulado += $liquidado_acumulado;
	$r_totgeralliquidado_acumulado += $liquidado_acumulado;
	$totgeralpago_acumulado += $pago_acumulado;
	$r_totgeralpago_acumulado += $pago_acumulado;
	$totgeralatual_a_pagar_liquidado += $atual_a_pagar_liquidado;
	$r_totgeralatual_a_pagar_liquidado += ($liquidado_acumulado-$pago_acumulado);

}
if (isset ($imprime_recurso) && ($imprime_recurso == "sim")) {

	$pdf->ln(3);
	$pdf->setfont('arial', 'b', 7);
	$pdf->cell(10, $alt, '', 0, 0, "L", 0);
	$pdf->cell(30, $alt, 'TOTAL DO RECURSO', 0, 0, "L", 0, '.');
	$pdf->cell(30, $alt, db_formatar($r_totgeraldot_ini, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralsuplementado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeraldot_ini + $r_totgeralsuplementado_acumulado - $r_totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralatual, 'f'), 0, 1, "R", 0);
	$pdf->cell(40, $alt, "", 0, 0, "L", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralempenhado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralanulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralliquidado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralpago, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralatual_a_pagar, 'f'), 0, 1, "R", 0);
	$pdf->cell(40, $alt, "", 0, 0, "L", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralempenhado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralanulado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralliquidado_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralpago_acumulado, 'f'), 0, 0, "R", 0);
	$pdf->cell(30, $alt, db_formatar($r_totgeralatual_a_pagar_liquidado, 'f'), 0, 1, "R", 0);
	$pdf->setfont('arial', '', 7);

}

$pdf->setfont('arial', 'b', 7);
$pdf->ln(3);
$pdf->cell(10, $alt, '', 0, 0, "L", 0);
$pdf->cell(30, $alt, 'TOTAL GERAL ', 0, 0, "L", 0, '.');
$pdf->cell(30, $alt, db_formatar($totgeraldot_ini, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralsuplementado_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeraldot_ini + $totgeralsuplementado_acumulado - $totgeralreduzido_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralatual, 'f'), 0, 1, "R", 0);
$pdf->cell(40, $alt, "", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($totgeralempenhado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralanulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralliquidado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralpago, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralatual_a_pagar, 'f'), 0, 1, "R", 0);
$pdf->cell(40, $alt, "", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($totgeralempenhado_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralanulado_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralliquidado_acumulado, 'f'), 0, 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralpago_acumulado, 'f'), 0, 0, "R", 0);
// $pdf->cell(30, $alt, db_formatar($totgeralatual_a_pagar_liquidado, 'f'), 0, 1, "R", 0);
$pdf->cell(30, $alt, db_formatar($totgeralliquidado_acumulado-$totgeralpago_acumulado, 'f'), 0, 1, "R", 0);

$pdf->setfont('arial', '', 7);

$tes = "______________________________"."\n"."Tesoureiro";
$sec = "______________________________"."\n"."Secretaria da Fazenda";
$cont = "______________________________"."\n"."Contador";
$pref = "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000, $pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec = $classinatura->assinatura(1002, $sec);
$ass_tes = $classinatura->assinatura(1004, $tes);
$ass_cont = $classinatura->assinatura(1005, $cont);

//echo $ass_pref;
if ($pdf->gety() > ($pdf->h - 30))
	$pdf->addpage();

$largura = ($pdf->w) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura, 2, $ass_pref, 0, "C", 0, 0);
$pdf->setxy($largura, $pos);
$pdf->multicell($largura, 2, $ass_cont, 0, "C", 0, 0);

pg_free_result($result);
//include("fpdf151/geraarquivo.php");
$pdf->Output();
?>