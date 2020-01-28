<?
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_conrelinfo_classe.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_pagordem_classe.php"));

$clempempenho = new cl_empempenho;
$clpagordem   = new cl_pagordem;

parse_str($_SERVER['QUERY_STRING']);
$oGet = db_utils::postMemory($_GET);

$data1 = 0;
$data2 = 0;
@ $data1 = "$dt1_ano-$dt1_mes-$dt1_dia";
@ $data2 = "$dt2_ano-$dt2_mes-$dt2_dia";
$dta1 = split("-",$data1);
$dta2 = split("-",$data2);
$head5 =  "De  : $dta1[2]/$dta1[1]/$dta1[0] ";
$head5 =  "Ate : $dta2[2]/$dta2[1]/$dta2[0] ";
if (strlen($data1) < 3) {
	unset ($data1);
}
if (strlen($data2) < 3) {
	unset ($data2);
}

$campos     = "distinct e60_numemp, e60_codemp, e60_vencim, e60_emiss, z01_nome::text, e60_vlremp, e60_vlranu";
$campos    .= ", e60_vlrliq, e60_vlrpag, round(e60_vlrliq-e60_vlrpag,2)::float8 as saldoliq";
$campos    .= ", round(e60_vlremp-e60_vlranu-e60_vlrpag,2)::float8 as saldo";
$sql        = "";
$where_sql  = "";
$where      = "";
if (isset ($e60_numemp) and ($e60_numemp != "")) {
	$where_sql .= " e60_numemp = $e60_numemp and ";
}

if (isset($e60_codemp) and $e60_codemp != "" ) {
	  $arr = split("/",$e60_codemp);
	  if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
	  	$where_sql .= " e60_codemp =  '".$arr[0]."' and e60_anousu = ".$arr[1]." and ";
	  }else{
	  	$where_sql .= " e60_codemp =  '".$arr[0]."' and e60_anousu = ".db_getsession("DB_anousu")." and ";
      }
}
if (isset ($o58_coddot) and ($o58_coddot != "")){
	$where_sql .= " o58_coddot=$o58_coddot and o58_anousu = ".db_getsession("DB_anousu")." and";
}
if (isset ($pc01_codmater) and ($pc01_codmater != "")) {
	$where_sql .= " pc01_codmater = $pc01_codmater and ";
}
if (isset ($z01_numcgm) and ($z01_numcgm != "") ){
	$where_sql .= " e60_numcgm = $z01_numcgm and ";
}
if (isset($e53_codord) and $e53_codord !=""){
	$where_sql .= " e50_codord = $e53_codord and ";
}

$sql = $clempempenho->sql_query(null, $campos,"z01_nome", " $where_sql  e60_instit = ".db_getsession("DB_instit"));
if ((isset ($dt1) and $dt1 != "") and (isset ($dt2) and $dt2 != "")){
	$sql = $clempempenho->sql_query(null, $campos,"z01_nome", "$where_sql e60_emiss between '$dt1' and '$dt2' and e60_instit = ".db_getsession("DB_instit"));
}
if (isset($pc01_codmater) and $pc01_codmater !=""){
   $campos .= ",pc01_descrmater";
   $sql = $clempempenho->sql_query_itemmaterial(null,$campos,"z01_nome"," $where_sql e60_instit = ".db_getsession("DB_instit"));
   if ((isset ($dt1) and $dt1 != "") and (isset ($dt2) and $dt2 != "")){
      $sql = $clempempenho->sql_query_itemmaterial(null, $campos,"z01_nome", "$where_sql e60_emiss between '$dt1' and '$dt2' and e60_instit = ".db_getsession("DB_instit"));
   }
}

if (isset ($o50_estrutdespesa) && ($o50_estrutdespesa != "")) {
	$matriz = split('\.', $o50_estrutdespesa);
	for ($i = 0; $i < count($matriz); $i ++) {
		switch ($i) {
			case 0 : //orgao
				$o40_orgao = $matriz[$i];
				break;
			case 1 : //unidade
				$o41_unidade = $matriz[$i];
				break;
			case 2 : //funcao
				$o52_funcao = $matriz[$i];
				break;
			case 3 : //subfuncao
				$o53_subfuncao = $matriz[$i];
				break;
			case 4 : //programa
				$o54_programa = $matriz[$i];
				break;
			case 5 : //projativ
				$o55_projativ = $matriz[$i];
				break;
			case 6 : //elemento de despesa
				$o56_elemento = $matriz[$i];
				break;
			case 7 : //tipo de  recurso
				$o58_codigo = $matriz[$i];
				break;
		}
	}
}
if (!empty ($o40_orgao)) {
	$where .= " and o58_orgao = $o40_orgao ";
}
if (!empty ($o41_unidade)) {
	if ($where != "")
		$where .= " and o58_unidade = $o41_unidade ";
}
if (!empty ($o52_funcao)) {
	$where .= " and o58_funcao = $o52_funcao ";
}
if (!empty ($o53_subfuncao)) {
	$where .= " and o58_subfuncao = $o53_subfuncao ";
}
if (!empty ($o54_programa)) {
	$where .= " and o58_programa = $o54_programa ";
}
if (!empty ($o55_projativ)) {
	$where .= " and o58_projativ = $o55_projativ ";
}
if (!empty ($o56_elemento)) {
	$where .= " and o58_elemento = $o56_elemento ";
}
if (!empty ($o58_codigo)) {
	$where .= " and o58_codigo = $o58_codigo ";
}

if (isset ($e53_codord) and $e53_codord != "") {
	$sql = $clpagordem->sql_query(null, $campos, null, " e50_codord = $e53_codord and e60_instit = ".db_getsession("DB_instit"));
}

$sql1 = $sql.$where;

if (!empty($oGet->e150_numeroprocesso)) {

  $sSqlConsultaBase = $sql;
  if (!empty($newsql) && $newsql == "true") {
    $sSqlConsultaBase = $sql1;
  }

  $sSqlNovoSqlParaProcesso = "
    select * 
      from ({$sSqlConsultaBase}) as consulta_principal
           inner join empempaut on empempaut.e61_numemp = consulta_principal.e60_numemp
           inner join empautorizaprocesso on empautorizaprocesso.e150_empautoriza = empempaut.e61_autori
     where empautorizaprocesso.e150_numeroprocesso ilike '{$oGet->e150_numeroprocesso}%'
  ";
  $sql1 = $sSqlNovoSqlParaProcesso;
  $sql  = $sSqlNovoSqlParaProcesso;
}


if (isset ($newsql) && ($newsql == "true")) {
	$result = db_query($sql1);
} else {
	$result = db_query($sql);
}

$head2 = "RELATÓRIO DE EMPENHOS";
$head3 = "Dotação : ".$o58_coddot;
if ($pc01_codmater != 0){
  db_fieldsmemory($result,1);
  $head4 = "Material : ".$pc01_descrmater;
}else{
  $head4 = "Material : ".$pc01_codmater;
}
$head5 = "CGM : ".$z01_numcgm;
$head6 =  "De  : $dta1[2]/$dta1[1]/$dta1[0] ";
$head7 =  "Ate : $dta2[2]/$dta2[1]/$dta2[0] ";
$head8 =  "Processo: {$oGet->e150_numeroprocesso}";

$somaemp      = 0;
$somaliq      = 0;
$somapag      = 0;
$somaanu      = 0;
$somasaldoliq = 0;
$somasaldo    = 0;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$alt    = 4;
$pagina = 1;

$pdf->addpage();
$pdf->setfont('arial', 'b', 6);
$pdf->cell(15, $alt, "Número", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Empenho", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Data Emiss.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Vcto.", 'TRB', 0, "C", 0);
$pdf->cell(43, $alt, "Credor", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Valor Emp.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Valor Liq.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Valor Pag.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Valor Anu.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Saldo liq.", 'TRB', 0, "C", 0);
$pdf->cell(15, $alt, "Saldo", 'TB', 1, "C", 0);
$pdf->setfont('arial', '', 6);
for($i=0;$i< pg_numrows($result);$i++) {
	db_fieldsmemory($result,$i);
	$pdf->cell(15, $alt,$e60_numemp, 'R', 0, "R", 0);
	$pdf->cell(15, $alt,$e60_codemp, 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($e60_emiss,"d"), 'R', 0, "C", 0);
	$pdf->cell(15, $alt,db_formatar($e60_vencim,"d"), 'R', 0, "C", 0);
	$pdf->cell(43, $alt,substr($z01_nome,0,30), 'R', 0, "L", 0);
	$pdf->cell(15, $alt,db_formatar($e60_vlremp,'f'), 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($e60_vlrliq,'f'), 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($e60_vlrpag,'f'), 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($e60_vlranu,'f'), 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($saldoliq,'f'), 'R', 0, "R", 0);
	$pdf->cell(15, $alt,db_formatar($saldo,'f'), '', 1, "R", 0);

        $somaemp += $e60_vlremp;
	$somaliq += $e60_vlrliq;
	$somapag += $e60_vlrpag;
	$somaanu += $e60_vlranu;
	$somasaldoliq += $saldoliq;
	$somasaldo    += $saldo;

}
$pdf->setfont('arial', 'b', 6);
$pdf->cell(103, $alt,"Total : ".$i, 'TBR', 0, "C", 0);
$pdf->cell(15, $alt,db_formatar($somaemp,'f'), 'TBR', 0, "R", 0);
$pdf->cell(15, $alt,db_formatar($somaliq,'f'), 'TBR', 0, "R", 0);
$pdf->cell(15, $alt,db_formatar($somapag,'f'), 'TBR', 0, "R", 0);
$pdf->cell(15, $alt,db_formatar($somaanu,'f'), 'TBR', 0, "R", 0);
$pdf->cell(15, $alt,db_formatar($somasaldoliq,'f'), 'TBR', 0, "R", 0);
$pdf->cell(15, $alt,db_formatar($somasaldo,'f'), 'TB', 1, "R", 0);
$pdf->Output();
?>