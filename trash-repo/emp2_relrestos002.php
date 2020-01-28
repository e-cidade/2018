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

include ("fpdf151/pdf.php");
include ("libs/db_liborcamento.php");
include ("classes/db_empresto_classe.php");
include ("classes/db_cgm_classe.php");
// include ("dbforms/db_relrestos.php");

// este relatorio emite os restos a pagar do exercicio conforme o arquivo empresto e com a posicao de lancamentos ate a data
// informada, podendo ser acumulado por empenho, tipo de rp ou recurso


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempresto = new cl_empresto;
$clrotulo = new rotulocampo;


$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("e60_anousu");
$clrotulo->label("z01_nome");

$dtini = db_getsession("DB_anousu").'-01-01';
$dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
// por nota de empenho
//$tipo = "e";
// por tipo / exercicio
//$tipo = "a";
// por recurso
//$tipo = "r";
// somente com movimento no periodo
//$commov = "0";
$liquidar = 0;
$liquidado = 0;
$totanuant = 0;
$anulado_p = 0;
$liquidado_p = 0;
$pago_p = 0;
$pago_n_proc = 0;
$pago_proc = 0;
$aliquidadonp = 0;

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}
$head1 = "INSTITUIÇÕES : ".$descr_inst;

$sele_work = ' e60_instit in ('.str_replace('-', ', ', $db_selinstit).') ';

if ($o15_codigo != 0) {
	$sele_work1 = ' and e91_recurso  = '.$o15_codigo;
} else {
	$sele_work1 = '';
}


if ($commov == "1") {
	$sql_where_externo = " where (round(vlranu,2) + round(vlrliq,2) + round(vlrpag,2)) > 0 and $sele_work ";
} else {
	//if ($commov == "0"){
	$sql_where_externo = " where $sele_work ";
	//}elseif($commov == "2"){
	//  $sqlperiodo .= " where round((round(e91_vlremp,2) - (round(e91_vlranu,2)+round(vlranu,2))) - ( round(vlrpag,2)),2) > 0 and $sele_work ";
}
if ($opcao == "1") {
	$sql_where_externo .= " and (round(e91_vlrliq,2) + round(vlrliq,2)) > 0";
}
elseif ($opcao == "2") {
	$sql_where_externo .= " and (round(e91_vlrliq,2) + round(vlrliq,2)) = 0";
}
elseif ($opcao == "3") { // anulados
	$sql_where_externo .= " and (round(vlranu,2)) > 0";
}

if ($exercicio!=0){
        $sql_where_externo.=' and e60_anousu = '.$exercicio;
}  




$sql_order = "";
if ($tipo == "a") { // agrupado por orgao
	$sql_order = " order by e91_codtipo, e60_numemp";
}elseif ($tipo == "u") {
	$sql_order = " order by o58_orgao,o58_unidade,e91_numemp,e60_emiss ";
}elseif ($tipo == "t") {
	$sql_order = " order by e91_codtipo, e60_anousu, e60_codemp ";
}elseif ($tipo == "o") {
	$sql_order = " order by e91_recurso, e60_numemp";
}elseif ($tipo == "d") {
	$sql_order = " order by e60_emiss, e60_numemp";
}elseif ($tipo == "c") {
	$sql_order = " order by z01_nome,e60_emiss,e60_numemp";
}elseif ($tipo == "r") {
	$sql_order = " order by e91_recurso, e91_numemp ";
}elseif ($tipo == "r_e") { // recurso/elemento
	$sql_order = " order by e91_recurso, o56_elemento,e60_anousu";
}elseif ($tipo == "a_o") { // agrupa por orgão
	$sql_order = " order by o58_orgao,e60_anousu ";
}elseif ($tipo == "subfunc") { // empenho,subfuncao
	$sql_order = " order by o58_orgao,o58_subfuncao,e60_anousu ";
}

// echo $tipo."-".$sql_order;exit;


$sqlperiodo = $clempresto->sql_rp(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, $sql_order);

$head2 = "Emissão: Por Empenhos ";
if ($tipo == 'a') {
	$head2 = "Emissão: Total por Tipo e Exercício";
	$sqlperiodo = " select e91_codtipo,e90_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
	                       sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
	                from ($sqlperiodo) as x
	                group by e91_codtipo,e90_descr,e60_anousu
			order by e91_codtipo,e60_anousu
			
			";
} elseif ($tipo == 'r') {
	$head2 = "Emissão: Total por Recurso e Exercício";
	$sqlperiodo = " select e91_recurso,o15_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
                               sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
                        from ($sqlperiodo) as x
                        group by e91_recurso,o15_descr,e60_anousu
			order by e91_recurso,e60_anousu
			";

} elseif ($tipo == 'r_e') {
	$head2 = "Emissão: Total por Recurso e Exercício";
	$sqlperiodo = " select e91_recurso,o56_elemento,o56_descr, o15_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
                               sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
                        from ($sqlperiodo) as x
                        group by e91_recurso,o56_elemento,o56_descr, o15_descr,e60_anousu
			order by e91_recurso,o56_elemento,e60_anousu
			";

} elseif ($tipo =="a_o"){
	$head2 = "Emissão: Total por Orgão e Exercício";
	$sqlperiodo = " select o58_orgao,o40_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
                               sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
                        from ($sqlperiodo) as x
                        group by o58_orgao, o40_descr,e60_anousu
			order by o58_orgao,e60_anousu
			
			";

}

$res = $clempresto->sql_record($sqlperiodo);
// echo $sqlperiodo;
// db_criatabela($res);exit;

if ($clempresto->numrows == 0) {
   db_redireciona("db_erros.php?fechar=true&db_erro=Sem movimentação de restos a pagar.");
   exit;
}

$rows = $clempresto->numrows;

$head3 = "Relatório dos Restos à Pagar - ".db_getsession("DB_anousu");

$head4 = "Posiçao: ".db_formatar($dtfim, "d");
$impressao = $opcao;
if($impressao == 0){
  $impressao = "Todos";
}else if($impressao == 1){
  $impressao = "Liquidados";
}else if($impressao == 2){
  $impressao = "Não Liquidados";
}else{
  $impressao = "Anulados";
}
$head5 = "Opção de Impressão : ".$impressao;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$tam = '04';
$imprime_header = true;
$contador = 0;
$repete = "";
$xtipo = '';
$troca = 1;

// zera totalizadores  
$aliquidar = 0;
$aliquidado = 0;
$aanulado_p = 0; //usa-se dois "aa" pra dar uma diferença na palavra
$aliquidado_p = 0;
$aaliquidado_p = 0;
$apago_p = 0;
$apago_n_proc = 0;
$apago_proc = 0;

/*
<option value="e" >Empenho (Ordem Número)</option>
<option value="t" >Empenho (Ordem Tipo Resto)</option>
<option value="o" >Empenho (Ordem Recurso)</option>
<option value="d" >Empenho (Ordem Data de Emissão)</option>
<option value="c" >Empenho (Ordem Credor)</option>
<option value="a" >Tipo de Resto</option>
<option value="r" >Recurso</option>
*/

/*  geral analitico  por empennho */
if ($tipo == "e" || $tipo == "t" || $tipo == "o" || $tipo == "d" || $tipo == "c") {
	$pdf->SetFont('Arial', '', 7);
	$liquidar = 0;
	$liquidado = 0;
	$anulado_p = 0;
	$liquidado_p = 0;
	$liquidado_gp = 0;
	$pago_p = 0;
	$pago_n_proc = 0;
	$pago_proc = 0;

	db_fieldsmemory($res, 0);

	//}elseif($commov == "2"){

	$anoo = $e60_anousu;
	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);
		// testa novapagina 
		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}

		if ($tipo == 't' && $anoo != $e60_anousu) {
			/*
			 *  abaixo o codigo para imprimir totalizador !
			 */
			$anoo = $e60_anousu;
			$pdf->Cell(100, $tam, 'Total Exercício:', 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($aliquidar, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($aliquidado, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($aanulado_p, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($aliquidado_p, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($aaliquidado_p, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($apago_p, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar((($aliquidar + $aliquidado - $aanulado_p) - $apago_p), 'f'), 1, 0, "R", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(20, $tam, db_formatar($apago_n_proc, 'f'), 1, 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($apago_proc, 'f'), 1, 1, "R", 1);
			// zera totalizadores  
			$aliquidar = 0;
			$aliquidado = 0;
			$aanulado_p = 0; // usa-se dois "aa" pra dar uma diferença na palavra
			$aliquidado_p = 0;
			$aaliquidado_p = 0;
			$apago_p = 0;
			$apago_n_proc = 0;
			$apago_proc = 0;
		}

		if ($tipo == "t" || $tipo == "o") {
			if ($xtipo != ($tipo == "t" ? @ $e91_codtipo : @ $e91_recurso)) {
				$troca = 1;
				$xtipo = ($tipo == "t" ? @ $e91_codtipo : @ $e91_recurso);
				if ($x > 0) {
					$pdf->Cell(100, $tam, 'Total:', 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar((($liquidar + $liquidado - $anulado_p) - $pago_p), 'f'), 1, 0, "R", 1);
					$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 1);
				}
				$liquidar = 0;
				$liquidado = 0;
				$anulado_p = 0;
				$liquidado_p = 0;
				$liquidado_gp = 0;
				$pago_p = 0;
				$pago_n_proc = 0;
				$pago_proc = 0;
			}
		}
		if ($tipo == "t" ) {
			if ($xtipo != ($tipo == "t" ? @ $e91_codtipo : @ $o58_unidade)) {
				$troca = 1;
				$xtipo = ($tipo == "t" ? @ $e91_codtipo : @ $o58_unidade);
				if ($x > 0) {
					$pdf->Cell(100, $tam, 'Total:', 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar((($liquidar + $liquidado - $anulado_p) - $pago_p), 'f'), 1, 0, "R", 1);
					$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 1);
					$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 1);
				}
				$liquidar = 0;
				$liquidado = 0;
				$anulado_p = 0;
				$liquidado_p = 0;
				$liquidado_gp = 0;
				$pago_p = 0;
				$pago_n_proc = 0;
				$pago_proc = 0;
			}
		}
		
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			if ($tipo=='u'){
			   $pdf->cell(0, 5, ($tipo == "t" ? @ $e91_codtipo : @ ("Orgao".":".$o58_orgao." - "." Unidade: ".$o58_unidade)).' - '. ($tipo == "t" ? @ $e90_descr : @ $o41_descr), 0, 1, "L", 0);
		    }	else { 
        if ( $tipo == 't' || $tipo == 'o' ){
		  	     $pdf->cell(0, 5, ($tipo == "t" ? @ $e91_codtipo :  @ $e91_recurso ).' - '. ($tipo == "t" ? @ $e90_descr : @ $o15_descr), 0, 1, "L", 0);
        }
		    }  
			$pdf->SetFont('Arial', 'B', 7);

			$pdf->Cell(100, $tam, "Dados do empenho", 1, 0, "C", 1);
			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(100, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_numemp), 1, 0, "L", 1);
			$pdf->Cell(15, $tam, strtoupper($RLe60_codemp), 1, 0, "L", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_emiss), 1, 0, "C", 1);
			$pdf->Cell(50, $tam, strtoupper($RLz01_nome), 1, 0, "L", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar Geral", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar Geral", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 1, "C", 1);
		}
		$pdf->Cell(15, $tam, $e60_numemp, 1, 0, "L", 0);
		$pdf->Cell(15, $tam, $e60_codemp, 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);
		$pdf->Cell(50, $tam, substr($z01_nome, 0, 30), 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		// saldo a pagar geral !
		$liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrpag );
		$pdf->Cell(20, $tam, db_formatar( $liquidado_anterior - $vlranu - $vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

		if ($vlrpag+0 > $vlrliq+0) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}
		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

		// totalizador local     			
		$aliquidar += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$aliquidado += $e91_vlrliq - $e91_vlrpag;
		$aanulado_p += $vlranu;
		$aliquidado_p += $vlrliq;
		$aaliquidado_p += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq)); // A liquidar geral		
		$apago_p += $vlrpag;
		$apago_n_proc += $vlrnp;
		$apago_proc += $vlrp;
		// $aliquidadonp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)); //nao sei onde usa esta variavel !

		// somador  
		$liquidar += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
		$anulado_p += $vlranu;
		$liquidado_p += $vlrliq;
		$liquidado_gp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq)); // A liquidar geral
		$pago_p += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc += $vlrp;

	}
	$pdf->Cell(100, $tam, "TOTAL:", 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar(($liquidar + $liquidado - $anulado_p) - $pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
	if ($pago_p > $liquidado_p){
            $pago_n_proc = $pago_p - $liquidado_p;
	    $pago_proc   = $liquidado_p;
	}  else {
            $pago_proc   = $pago_p;
	    $pago_n_proc = 0;
	}  
	$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 0);

} elseif ($tipo=='r_e'){

	/*
	<option value="a" >Tipo de Resto</option>
	<option value="r" >Recurso</option>
	*/

	$taliq = 0;
	$tliq = 0;
	$tvlranu = 0;
	$tvlrliq = 0;
	$tvlrpag = 0;
	$tvlrnp = 0;
	$tvlrp = 0;
	$xtipo = '';
	$troca = 1;

	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);

		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}

		// testa novapagina 
		if ($xtipo !=  $o56_elemento) {
			//$troca 	   = 1;
			if ($x != 0) {
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->ln(3);
				$pdf->cell(0, 5,$e91_recurso.'-'.$o15_descr.'     '.$o56_elemento.'-'.$o56_descr, 0, 1, "L", 0);
			}
			$xtipo = $o56_elemento;
			$liquidar = 0;
			$liquidado = 0;
			$anulado_p = 0;
			$liquidado_p = 0;
			$pago_p = 0;
			$pago_n_proc = 0;
			$pago_proc = 0;
		}
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->cell(0, 5,$e91_recurso.'-'.$o15_descr.'    '.$o56_elemento.'-'.$o56_descr, 0, 1, "L", 0);
			$pdf->SetFont('Arial', 'B', 7);

			$pdf->Cell(15, $tam, '', 1, 0, "C", 1);

			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(100, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_anousu), 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			//  $pdf->Cell(20,$tam,"Anulado",1,0,"C",1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg Proc ", 1, 1, "C", 1);
		}
		$pdf->SetFont('Arial', '', 7);

		$pdf->Cell(15, $tam, $e60_anousu, 1, 0, "L", 1);

		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);

		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar(($vlrliq + $e91_vlrliq - $e91_vlrpag - $vlrpag), 'f'), 1, 0, "R", 0);

		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

		if ($vlrpag > $vlrliq) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}

		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

		$liquidar += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
		$totanuant += $e91_vlranu;
		$anulado_p += $vlranu;
		$liquidado_p += $vlrliq;
		$pago_p += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc += $vlrp;
		$aliquidadonp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq));

		$taliq += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$tliq += $e91_vlrliq - $e91_vlrpag;
		$tvlranu += $vlranu;
		$tvlrliq += $vlrliq;
		$tvlrpag += $vlrpag;
		$tvlrnp += $vlrnp;
		$tvlrp += $vlrp;

	}

	$pdf->Cell(15, $tam, "Total:", 1, 0, "L", 1);
	$pdf->Cell(20, $tam, db_formatar($taliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tliq, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlranu, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($aliquidadonp, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrliq + $tliq - $tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

	$pdf->Cell(20, $tam, db_formatar($tvlrnp, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrp, 'f'), 1, 1, "R", 1);

} elseif ($tipo=='subfunc'){
        //@
        //@ lista rps quebrando por orgao/subfunção
        //@ 

	// totais por orgão.subfuncao
	$liquidar    = 0;
	$liquidado   = 0;
 	$anulado_p   = 0;
        $liquidado_p = 0;
	$liquidado_gp= 0;
	$pago_p      = 0;
	$pago_n_proc = 0;
	$pago_proc   = 0;
        // totais gerais
	$t_liquidar    = 0;
	$t_liquidado   = 0;
 	$t_anulado_p   = 0;
        $t_liquidado_p = 0;
	$t_liquidado_gp= 0;
	$t_pago_p      = 0;
	$t_pago_n_proc = 0;
	$t_pago_proc   = 0;

	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);

		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}
		// testa novapagina 
		if ($xtipo !=  $o58_orgao.$o40_descr.$o58_subfuncao.$o53_descr) {
                        // imprime totalizador do orgão
			$pdf->Cell(100, $tam, "TOTAL:", 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar(($liquidar + $liquidado - $anulado_p) - $pago_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 0);

			if ($x != 0) {
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->ln(3);
				$pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr  SubFunção $o58_subfuncao $o53_descr",0, 1, "L", 0);
			}
			$xtipo = $o58_orgao.$o40_descr.$o58_subfuncao.$o53_descr;
			$liquidar    = 0;
			$liquidado   = 0;
		 	$anulado_p   = 0;
		        $liquidado_p = 0;
			$liquidado_gp= 0;
			$pago_p      = 0;
			$pago_n_proc = 0;
			$pago_proc   = 0;
		
		}
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr  SubFunção $o58_subfuncao $o53_descr", 0, 1, "L", 0);
			$pdf->SetFont('Arial', 'B', 7);

                        // header de pagina
		        $pdf->Cell(100, $tam, "Dados do empenho", 1, 0, "C", 1);
			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(100, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_numemp), 1, 0, "L", 1);
			$pdf->Cell(15, $tam, strtoupper($RLe60_codemp), 1, 0, "L", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_emiss), 1, 0, "C", 1);
			$pdf->Cell(50, $tam, strtoupper($RLz01_nome), 1, 0, "L", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar Geral", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar Geral", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 1, "C", 1);
		
		}
		$pdf->SetFont('Arial', '', 7);

               	$pdf->Cell(15, $tam, $e60_numemp, 1, 0, "L", 0);
		$pdf->Cell(15, $tam, $e60_codemp, 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);
		$pdf->Cell(50, $tam, substr($z01_nome, 0, 30), 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		// saldo a pagar geral !
		$liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
		$pdf->Cell(20, $tam, db_formatar($liquidado_anterior - $vlranu - $vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
		if ($vlrpag >= $vlrliq) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}
		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

                // totalizador por orgão.unidade
		$liquidar  += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
       	        $anulado_p += $vlranu;
                $liquidado_p += $vlrliq;
		$liquidado_gp+= $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)); // A liquidar geral
		$pago_p      += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc   += $vlrp;
                
		// totalizador geral
     	        $t_liquidar  += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$t_liquidado += $e91_vlrliq - $e91_vlrpag;
       	        $t_anulado_p += $vlranu;
                $t_liquidado_p  += $vlrliq;
		$t_liquidado_gp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)); // A liquidar geral
		$t_pago_p      += $vlrpag;
		$t_pago_n_proc += $vlrnp;
		$t_pago_proc   += $vlrp;

	}

	$pdf->Cell(100, $tam, "TOTAL:", 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar(($liquidar + $liquidado - $anulado_p) - $pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 0);

        $pdf->Ln();

	$pdf->Cell(100, $tam, "TOTAL GERAL:", 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidar, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_anulado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado_gp, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar(($t_liquidar + $t_liquidado - $t_anulado_p) - $t_pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
	if ($t_pago_p > $t_liquidado_p){
            $t_pago_n_proc = $t_pago_p - $t_liquidado_p;
	    $t_pago_proc   = $t_liquidado_p;
	}  else {
            $t_pago_proc   = $t_pago_p;
	    $t_pago_n_proc = 0;
	}  
	$pdf->Cell(20, $tam, db_formatar($t_pago_n_proc, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_pago_proc, 'f'), 1, 1, "R", 0);


} elseif ($tipo=='u'){
        //@
        //@ lista rps quebrando por orgão/unidade
        //@ 

	// totais por orgão.unidade
	$liquidar    = 0;
	$liquidado   = 0;
 	$anulado_p   = 0;
        $liquidado_p = 0;
	$liquidado_gp= 0;
	$pago_p      = 0;
	$pago_n_proc = 0;
	$pago_proc   = 0;
        // totais gerais
	$t_liquidar    = 0;
	$t_liquidado   = 0;
 	$t_anulado_p   = 0;
        $t_liquidado_p = 0;
	$t_liquidado_gp= 0;
	$t_pago_p      = 0;
	$t_pago_n_proc = 0;
	$t_pago_proc   = 0;

	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);

		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}
		// testa novapagina 
		if ($xtipo !=  $o58_orgao.$o40_descr.$o58_unidade.$o41_descr) {
                        // imprime totalizador do orgão
			$pdf->Cell(100, $tam, "TOTAL:", 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar(($liquidar + $liquidado - $anulado_p) - $pago_p, 'f'), 1, 0, "R", 0);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 0);
			$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 0);

			if ($x != 0) {
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->ln(3);
				$pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr  Unidade  $o58_unidade $o41_descr",0, 1, "L", 0);
			}
			$xtipo = $o58_orgao.$o40_descr.$o58_unidade.$o41_descr;
			$liquidar    = 0;
			$liquidado   = 0;
		 	$anulado_p   = 0;
		        $liquidado_p = 0;
			$liquidado_gp= 0;
			$pago_p      = 0;
			$pago_n_proc = 0;
			$pago_proc   = 0;
		
		}
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr  Unidade  $o58_unidade $o41_descr", 0, 1, "L", 0);
			$pdf->SetFont('Arial', 'B', 7);

                        // header de pagina
		        $pdf->Cell(100, $tam, "Dados do empenho", 1, 0, "C", 1);
			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(100, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_numemp), 1, 0, "L", 1);
			$pdf->Cell(15, $tam, strtoupper($RLe60_codemp), 1, 0, "L", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_emiss), 1, 0, "C", 1);
			$pdf->Cell(50, $tam, strtoupper($RLz01_nome), 1, 0, "L", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar Geral", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar Geral", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 1, "C", 1);
		
		}
		$pdf->SetFont('Arial', '', 7);

               	$pdf->Cell(15, $tam, $e60_numemp, 1, 0, "L", 0);
		$pdf->Cell(15, $tam, $e60_codemp, 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);
		$pdf->Cell(50, $tam, substr($z01_nome, 0, 30), 1, 0, "L", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		// saldo a pagar geral !
		$liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
		$pdf->Cell(20, $tam, db_formatar($liquidado_anterior - $vlranu - $vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
		if ($vlrpag >= $vlrliq) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}
		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

                // totalizador por orgão.unidade
		$liquidar  += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
       	        $anulado_p += $vlranu;
                $liquidado_p += $vlrliq;
		$liquidado_gp+= $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)); // A liquidar geral
		$pago_p      += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc   += $vlrp;
                
		// totalizador geral
     	        $t_liquidar  += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$t_liquidado += $e91_vlrliq - $e91_vlrpag;
       	        $t_anulado_p += $vlranu;
                $t_liquidado_p  += $vlrliq;
		$t_liquidado_gp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)); // A liquidar geral
		$t_pago_p      += $vlrpag;
		$t_pago_n_proc += $vlrnp;
		$t_pago_proc   += $vlrp;

	}

	$pdf->Cell(100, $tam, "TOTAL:", 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidar, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($anulado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($liquidado_gp, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar(($liquidar + $liquidado - $anulado_p) - $pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_n_proc, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($pago_proc, 'f'), 1, 1, "R", 0);

        $pdf->Ln();

	$pdf->Cell(100, $tam, "TOTAL GERAL:", 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidar, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_anulado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_liquidado_gp, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar(($t_liquidar + $t_liquidado - $t_anulado_p) - $t_pago_p, 'f'), 1, 0, "R", 0);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
	if ($t_pago_p > $t_liquidado_p){
            $t_pago_n_proc = $t_pago_p - $t_liquidado_p;
	    $t_pago_proc   = $t_liquidado_p;
	}  else {
            $t_pago_proc   = $t_pago_p;
	    $t_pago_n_proc = 0;
	}  
	$pdf->Cell(20, $tam, db_formatar($t_pago_n_proc, 'f'), 1, 0, "R", 0);
	$pdf->Cell(20, $tam, db_formatar($t_pago_proc, 'f'), 1, 1, "R", 0);



} elseif ($tipo=='a_o'){
        //@
        //@ agrupa por orgao
        //@ 
	$taliq = 0;
	$tliq = 0;
	$tvlranu = 0;
	$tvlrliq = 0;
	$tvlrpag = 0;
	$tvlrnp = 0;
	$tvlrp = 0;
	$xtipo = '';
	$troca = 1;

	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);

		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}

		// testa novapagina 
		if ($xtipo !=  $o58_orgao) {
			//$troca 	   = 1;
			if ($x != 0) {
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->ln(3);
				$pdf->cell(0, 5,$o58_orgao.'-'.$o40_descr, 0, 1, "L", 0);
			}
			$xtipo = $o58_orgao;
			$liquidar = 0;
			$liquidado = 0;
			$anulado_p = 0;
			$liquidado_p = 0;
			$pago_p = 0;
			$pago_n_proc = 0;
			$pago_proc = 0;
		}
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->cell(0, 5,$o58_orgao.'-'.$o40_descr, 0, 1, "L", 0);
			$pdf->SetFont('Arial', 'B', 7);

			$pdf->Cell(15, $tam, '', 1, 0, "C", 1);

			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(100, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_anousu), 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			//  $pdf->Cell(20,$tam,"Anulado",1,0,"C",1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg Proc ", 1, 1, "C", 1);
		}
		$pdf->SetFont('Arial', '', 7);

		$pdf->Cell(15, $tam, $e60_anousu, 1, 0, "L", 1);

		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);

		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar( ($e91_vlremp - ($e91_vlranu + $vlranu) - ($e91_vlrpag + $vlrpag) ), 'f'), 1, 0, "R", 0);

		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

		if ($vlrpag > $vlrliq) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}

		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

		$liquidar += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
		$totanuant += $e91_vlranu;
		$anulado_p += $vlranu;
		$liquidado_p += $vlrliq;
		$pago_p += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc += $vlrp;
		$aliquidadonp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq));

		$taliq += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$tliq += $e91_vlrliq - $e91_vlrpag;
		$tvlranu += $vlranu;
		$tvlrliq += $vlrliq;
		$tvlrpag += $vlrpag;
		$tvlrnp += $vlrnp;
		$tvlrp += $vlrp;

	}

	$pdf->Cell(15, $tam, "Total:", 1, 0, "L", 1);
	$pdf->Cell(20, $tam, db_formatar($taliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tliq, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlranu, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($aliquidadonp, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($taliq + $tliq - $tvlranu - $tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

	$pdf->Cell(20, $tam, db_formatar($tvlrnp, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrp, 'f'), 1, 1, "R", 1);


} else {

	/*
	<option value="a" >Tipo de Resto</option>
	<option value="r" >Recurso</option>
	*/

	$taliq = 0;
	$tliq = 0;
	$tvlranu = 0;
	$tvlrliq = 0;
	$tvlrpag = 0;
	$tvlrnp = 0;
	$tvlrp = 0;
	$xtipo = '';
	$troca = 1;

	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x);

		if ($commov == "2") {
			if (((round(round($e91_vlremp, 2) - (round($e91_vlranu, 2) + round($vlranu, 2)), 2)) - (round($e91_vlrpag, 2) + round($vlrpag, 2))) <= 0) {
				continue;
			}
		}

		// testa novapagina 
		if ($xtipo != ($tipo == "a" ? @ $e91_codtipo : @ $e91_recurso)) {
			//$troca 	   = 1;
			if ($x != 0) {
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->ln(3);
				$pdf->cell(0, 5, ($tipo == "a" ? @ $e91_codtipo : @ $e91_recurso).' - '. ($tipo == "a" ? @ $e90_descr : @ $o15_descr), 0, 1, "L", 0);
			}
			$xtipo = ($tipo == "a" ? @ $e91_codtipo : @ $e91_recurso);
			$liquidar = 0;
			$liquidado = 0;
			$anulado_p = 0;
			$liquidado_p = 0;
			$pago_p = 0;
			$pago_n_proc = 0;
			$pago_proc = 0;
		}
		if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;
			$pdf->addpage("L");
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->cell(0, 5, ($tipo == "a" ? @ $e91_codtipo : @ $e91_recurso).' - '. ($tipo == "a" ? @ $e90_descr : @ $o15_descr), 0, 1, "L", 0);
			$pdf->SetFont('Arial', 'B', 7);

			$pdf->Cell(15, $tam, '', 1, 0, "C", 1);

			$pdf->Cell(40, $tam, "Saldos Anteriores", 1, 0, "C", 1);
			$pdf->Cell(120, $tam, "Movimento do Período", 1, 0, "C", 1);
			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);
			$pdf->Cell(40, $tam, "Pagos no Período", 1, 1, "C", 1);

			$pdf->Cell(15, $tam, strtoupper($RLe60_anousu), 1, 0, "C", 1);

			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			//  $pdf->Cell(20,$tam,"Anulado",1,0,"C",1);

			$pdf->Cell(20, $tam, "Anulado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Liquidado", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Liquidar", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pago", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar liq.", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "A Pagar Geral", 1, 0, "C", 1);

			$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

			$pdf->Cell(20, $tam, "Pg N Proc ", 1, 0, "C", 1);
			$pdf->Cell(20, $tam, "Pg Proc ", 1, 1, "C", 1);
		}
		$pdf->SetFont('Arial', '', 7);

		$pdf->Cell(15, $tam, $e60_anousu, 1, 0, "L", 1);

		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - $e91_vlranu - $e91_vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlrliq - $e91_vlrpag, 'f'), 1, 0, "R", 0);

		$pdf->Cell(20, $tam, db_formatar($vlranu, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq)), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrpag, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar(($vlrliq + $e91_vlrliq - $e91_vlrpag - $vlrpag), 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar((($e91_vlremp-$e91_vlranu-$vlranu)-($e91_vlrpag+$vlrpag)), 'f'), 1, 0, "R", 0);

		$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

		if ($vlrpag > $vlrliq) {
			$vlrnp = $vlrpag - $vlrliq;
			$vlrp = $vlrliq;
		} else {
			$vlrp = $vlrpag;
			$vlrnp = 0;
		}

		$pdf->Cell(20, $tam, db_formatar($vlrnp, 'f'), 1, 0, "R", 0);
		$pdf->Cell(20, $tam, db_formatar($vlrp, 'f'), 1, 1, "R", 0);

		$liquidar += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$liquidado += $e91_vlrliq - $e91_vlrpag;
		$totanuant += $e91_vlranu;
		$anulado_p += $vlranu;
		$liquidado_p += $vlrliq;
		$pago_p += $vlrpag;
		$pago_n_proc += $vlrnp;
		$pago_proc += $vlrp;
		$aliquidadonp += $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq));

		$taliq += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
		$tliq += $e91_vlrliq - $e91_vlrpag;
		$tvlranu += $vlranu;
		$tvlrliq += $vlrliq;
		$tvlrpag += $vlrpag;
		$tvlrnp += $vlrnp;
		$tvlrp += $vlrp;

	}

	$pdf->Cell(15, $tam, "Total:", 1, 0, "L", 1);
	$pdf->Cell(20, $tam, db_formatar($taliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tliq, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlranu, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrliq, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($aliquidadonp, 'f'), 1, 0, "R", 1);

	$pdf->Cell(20, $tam, db_formatar($tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrliq + $tliq - $tvlrpag, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar(($taliq+$tliq)-$tvlranu-$tvlrpag, 'f'), 1, 0, "R", 1);

	$pdf->Cell(5, $tam, "", 0, 0, "C", 0);

	$pdf->Cell(20, $tam, db_formatar($tvlrnp, 'f'), 1, 0, "R", 1);
	$pdf->Cell(20, $tam, db_formatar($tvlrp, 'f'), 1, 1, "R", 1);

}

$pdf->output();
?>