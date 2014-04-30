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
include ("fpdf151/assinatura.php");
include ("libs/db_sql.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_relatorio_recurso.php");
// include ("dbforms/db_relrestos.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_empresto_classe.php");

$classinatura = new cl_assinatura;
$clorctiporec = new cl_orctiporec;
$clempresto   = new cl_empresto;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

// GET //
// db_selinstit
// data_limite
// recurso

$anousu = db_getsession("DB_anousu");

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}
//// saldos bancarios do exercicio anterior
/*
 * quando usuario informa recurso usamos esta condição 
 */
$where_recurso = "";
if ($recurso > 0) {
	$where_recurso = " and c61_codigo = $recurso ";
}
$result_contas_inicial = pg_exec(sql_saldo_bancario($anousu, $anousu."-01-01", $db_selinstit, $where_recurso));

$result_contas = pg_exec(sql_saldo_bancario($anousu, $data_limite, $db_selinstit, $where_recurso));

//  db_criatabela($result_contas_inicial);
$nrows_inicial = pg_numrows($result_contas_inicial);
$nrows = pg_numrows($result_contas);
// exit;
$saldo_bancario_inicial = 0;
$saldo_bancario_atual = 0;
for ($h = 0; $h < $nrows_inicial; $h ++) {
	db_fieldsmemory($result_contas_inicial, $h);
	$valor = preg_split("/\s+/", $valor);
	if ($valor[0] != "2" || $valor[0] != "3") {
		$saldo_bancario_inicial += (float) str_replace(",", "", $valor[1]);
	}
}
for ($h = 0; $h < $nrows; $h ++) {
	db_fieldsmemory($result_contas, $h);
	$valor = preg_split("/\s+/", $valor);
	if ($valor[0] != "2" || $valor[0] != "3") {
		$saldo_bancario_atual += (float) str_replace(",", "", $valor[4]);
	}
}
// echo "<Br>saldo bancario 31/12/2005 ".$saldo_bancario_inicial;
// echo "<Br>  saldo bancario atual ".$saldo_bancario_atual;
//
$sql_where ="";
if ($recurso > 0) {
  $sql_where = ' and e91_recurso  = '.$recurso ; 
} 
$sele_work = ' e60_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$sql_restos = $clempresto->sql_rp(db_getsession("DB_anousu"),$sele_work,$anousu.'-01-01',$data_limite,$sql_where, " and ".$sele_work);

$sql_restos = " select sum(coalesce(round(e91_vlremp,2),0)) - sum(coalesce(round(e91_vlranu,2),0)) - sum(coalesce(round(e91_vlrpag,2),0)) as saldo_inicial_rp,
                       sum(coalesce(round(e91_vlremp,2),0)) - 
		         (sum(coalesce(round(e91_vlranu,2),0))
			  + sum(coalesce(round(vlranu,2),0)) ) - 
			 
		         (sum(coalesce(round(e91_vlrpag,2),0)) 
			  +sum(coalesce(round(vlrpag,2),0))) as saldo_atual_rp,
		       
                       sum(coalesce(round(vlranu,2),0)) as cancelamento_rp,
                       sum(coalesce(round(vlrpag,2),0)) as pagamento_rp
                from ($sql_restos) as x
              ";
			
$result_restos  = pg_exec($sql_restos);
if (pg_numrows($result_restos) > 0 ){
  db_fieldsmemory($result_restos,0);
   //  echo "<br> rp incial em 31/12 ".$saldo_inicial_rp;
   // echo "<br> rp saldo atual ".$saldo_atual_rp;
   // echo "<br> cancelamento  ".$cancelamento_rp;
   // echo "<br> pagamento   ".$pagamento_rp;
   // echo "<br>";
   // exit;
}
$sql_where ="";
if ($recurso > 0) {   
   $sql_where = " o70_codigo = ".$recurso; 
}
$result_receita = db_receitasaldo(1,3,3,true,$sql_where,$anousu,$anousu."-01-01",$data_limite);
if (pg_numrows($result_receita)>0){
	 db_fieldsmemory($result_receita,0);
}

$sql_where ="";
if ($recurso > 0) {
  $sql_where = ' and w.o58_codigo  = '.$recurso ; 
}
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).')  '.$sql_where ;
$sql_baldesp = db_dotacaosaldo(2,2,4,true,$sele_work,$anousu,$anousu."-01-01",$data_limite,8,0,true);
$sql_baldesp = "select (sum(empenhado_acumulado)-sum(anulado_acumulado)) as empenhado,
                                     sum(liquidado_acumulado) as liquidado,
                                     sum(pago_acumulado) as pago,
                                     sum(suplementado_acumulado) as suplementado,
                                     sum(especial_acumulado) as especial
                           from ($sql_baldesp) as x
                          ";
$result_baldesp = pg_exec($sql_baldesp);
if (pg_numrows($result_baldesp) > 0 ){
  db_fieldsmemory($result_baldesp,0);

  //echo "<br> empenhado ".$empenhado;
  //echo "<br> empenhado ".$liquidado;
  //echo "<br> empenhado ".$pago;
  //echo "<br> empenhado ".$especial;
  //echo "<br> empenhado ".$suplementado;  
  //echo "<br>";
}

//////////////////////////////////////////////////////////////////////////////////////
$res = $clorctiporec->sql_record($clorctiporec->sql_query_file($recurso));
if ($clorctiporec->numrows > 0){
   db_fieldsmemory($res,0);
}
if ($recurso == 0 )
   $o15_descr = "TODOS";
$head2 = "HISTÓRICO DO RECURSO ORÇAMENTÁRIO ";
$head3 = "RECURSO : ".$recurso . " : " .$o15_descr;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);

$pdf->setfont('arial', '', 10);
$alt = 6;
$tam = 150;

$pdf->addPage();

$pdf->cell($tam, $alt, "Saldo bancário inicial", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($saldo_bancario_inicial,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Saldo bancário atual em boletim", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($saldo_bancario_atual,'f'), 0, 1, "R", 0);

$pdf->Ln();

$pdf->cell($tam, $alt, "Receita do exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($saldo_arrecadado_acumulado,'f'), 0, 1, "R", 0);

$pdf->Ln();

$pdf->cell($tam, $alt, "Restos a pagar inicial", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($saldo_inicial_rp,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Restos a pagar atual", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($saldo_atual_rp,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Cancelamentos de RP no exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($cancelamento_rp,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Pagamento de RP no exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($pagamento_rp,'f'), 0, 1, "R", 0);

$pdf->Ln();

$pdf->cell($tam, $alt, "Empenhado no exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($empenhado,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Liquidado no exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($liquidado,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Pago no exercício", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($pago,'f'), 0, 1, "R", 0);

$pdf->Ln();

$pdf->cell($tam, $alt, "Suplementações", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($suplementado,'f'), 0, 1, "R", 0);

$pdf->cell($tam, $alt, "Creditos Especiais", 0, 0, "L", 0);
$pdf->cell(30, $alt, db_formatar($especial,'f'), 0, 1, "R", 0);



$pdf->Output();
?>