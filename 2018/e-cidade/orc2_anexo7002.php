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


include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;

$xinstit = split("-", $db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinstabrev;
	$xvirg = ', ';
}

$xtipo = 0;
if ($origem == "O") {
	$xtipo = "ORÇAMENTO";
} else {
	$xtipo = "BALANÇO";
	if ($opcao == 3)
		$head6 = "PERÍODO : ".db_formatar($perini, 'd')." A ".db_formatar($perfin, 'd');
	else
		$head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini, 5, 2)))." A ".strtoupper(db_mes(substr($perfin, 5, 2)));
}

$head3 = "PROGRAMA DE TRABALHO DO GOVERNO ";
$head4 = "ANEXO (7) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$xcampos = split("-", $orgaos);

if (substr($nivel, 0, 1) == '1') {
	$xwhere1 = " trim(to_char(o58_orgao,'99')) in (";
}
elseif (substr($nivel, 0, 1) == '2') {
	$xwhere1 = " trim(to_char(o58_orgao,'99'))||'.'||trim(to_char(o58_unidade,'99')) in (";
}
elseif (substr($nivel, 0, 1) == '3') {
	$xwhere1 = " trim(to_char(o58_funcao,'9999999999999')) in (";
}
elseif (substr($nivel, 0, 1) == '4') {
	$xwhere1 = " trim(to_char(o58_subfuncao,'9999999999999')) in (";
}
elseif (substr($nivel, 0, 1) == '5') {
	$xwhere1 = " trim(to_char(o58_programa,'9999999999999')) in (";
}

$virgula1 = ' ';

for ($i = 0; $i < sizeof($xcampos); $i ++) {
	$xxcampos = split("_", $xcampos[$i]);
	$virgula = '';
	$where = "'";
	$where1 = "'";
	for ($ii = 0; $ii < sizeof($xxcampos); $ii ++) {
		if ($ii > 0) {
			$where .= $virgula.$xxcampos[$ii];
			$where1 .= $virgula.$xxcampos[$ii];
			$virgula = '.';
		}
	}
	$xwhere1 .= $virgula1.$where1."'";
	$virgula1 = ', ';

}

$xwhere1 .= ") and o58_instit in (".str_replace('-', ', ', $db_selinstit).")";

$anousu = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$xordem = " ";
if ($tipo_agrupa == 1) {
	$xxnivel = " 0 as o58_orgao, ''::varchar as o40_descr, 0 as o58_unidade, ''::varchar as o41_descr ";
	$inicial = 0;
}
elseif ($tipo_agrupa == 2) {
	$xxnivel = " o58_orgao, o40_descr, 0 as o58_unidade, ''::varchar as o41_descr ";
	$inicial = 1;
	$xordem = "  o58_orgao, o40_descr,";
}
elseif ($tipo_agrupa == 3) {
	$xxnivel = " o58_orgao, o40_descr, o58_unidade, o41_descr";
	$inicial = 2;
	$xordem = "  o58_orgao, o40_descr, o58_unidade,o41_descr,";
}

// o contador disse que deve sair o empenhado quando emitir a posição contábil
$tipo_balanco = 2; // empenhado
if ($origem == "O"){
  $tipo_balanco = 1;
}  

$teste = db_dotacaosaldo(5, 1, 3, true, $xwhere1, $anousu, $dataini, $datafin, $inicial, 3, true,$tipo_balanco);
$result = db_query($teste);

//db_criatabela($result);exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
$alt = 4;
$totproj = 0;
$totativ = 0;
$totoper = 0;
$qualou = 0;

$pagina = 1;

for ($i = 0; $i < pg_numrows($result); $i ++) {

	db_fieldsmemory($result, $i);

	if (empty ($o58_funcao)) {
	 //	continue;
	}

	if ($pdf->gety() > $pdf->h - 30 || $pagina == 1) {
		$pagina = 0;
		$pdf->addpage();
		$pdf->setfont('arial', 'b', 7);

		$pdf->cell(15, $alt, "CÓDIGO", 0, 0, "L", 0);
		$pdf->cell(80, $alt, "E S P E C I F I C A Ç Ã O", 0, 0, "L", 0);
		$pdf->cell(20, $alt, "PROJETOS", 0, 0, "R", 0);
		$pdf->cell(20, $alt, "ATIVIDADES", 0, 0, "R", 0);
		$pdf->cell(20, $alt, "OPER.ESPEC", 0, 0, "R", 0);
		$pdf->cell(20, $alt, "TOTAL", 0, 1, "R", 0);
		$pdf->cell(0, $alt, '', "T", 1, "C", 0);
	}
	$pdf->setfont('arial', '', 6);
	if (empty ($o58_elemento)) {
		if (!empty ($o58_projativ)) {
			$pdf->cell(15, $alt, db_formatar($o58_projativ, 'atividade'), 0, 0, "R", 0);
			$pdf->cell(80, $alt, "    ".$o55_descr, 0, 0, "L", 0);
			$pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
			$pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
			$pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
			$pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
		} else {

      if ($o58_programa == 0 && $o58_subfuncao == 0 && $o58_funcao == 0 && empty($o58_unidade) && $o58_orgao != 0) {

        $descr = $o40_descr;
        $pdf->cell(15, $alt, $o58_orgao, 0, 0, "L", 0);
        $pdf->cell(80, $alt, $descr, 0, 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
      }

      if ($o58_programa == 0 && $o58_subfuncao == 0 && $o58_funcao == 0 && !empty($o58_unidade)) {

        $descr = $o41_descr;
        $pdf->ln(2);
        $pdf->cell(15, $alt, db_formatar($o58_unidade, 'orgao'), 0, 0, "L", 0);
        $pdf->cell(80, $alt, $descr, 0, 0, "L", 0);
        $pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
        $pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
      }

			if ($o58_programa == 0 && $o58_subfuncao == 0 && $o58_funcao != 0) {

				$descr = $o52_descr;
				$pdf->ln(2);
				$pdf->cell(15, $alt, db_formatar($o58_funcao, 'orgao'), 0, 0, "L", 0);
				$pdf->cell(80, $alt, $descr, 0, 0, "L", 0);
				$pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
				$totproj += $proj;
				$totativ += $ativ;
				$totoper += $oper;
			} else if ($o58_programa == 0 && $o58_subfuncao != 0) {

				$descr = $o53_descr;
				$pdf->cell(15, $alt, db_formatar($o58_funcao, 'orgao').".".db_formatar($o58_subfuncao, 's', '0', 3, 'e'), 0, 0, "L", 0);
				$pdf->cell(80, $alt, $descr, 0, 0, "L", 0);
				$pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
			}
      if ($o58_programa != 0) {

				$descr = $o54_descr;
				$pdf->cell(15, $alt, db_formatar($o58_funcao, 'orgao').".".db_formatar($o58_subfuncao, 's', '0', 3, 'e').'.'.db_formatar($o58_programa, 's', '0', 4, 'e'), 0, 0, "L", 0);
				$pdf->cell(80, $alt, $descr, 0, 0, "L", 0);
				$pdf->cell(20, $alt, db_formatar($proj, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($ativ, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($oper, 'f'), 0, 0, "R", 0);
				$pdf->cell(20, $alt, db_formatar($proj + $ativ + $oper, 'f'), 0, 1, "R", 0);
			}
		}
		//  }else{
		// $pdf->cell(15,$alt,db_formatar($o56_elemento,'elemento'),0,0,"L",0);
		//  $pdf->cell(80,$alt,"    ".$o56_descr,0,0,"L",0);
		//  $pdf->cell(20,$alt,db_formatar($proj,'f'),0,0,"R",0);
		//  $pdf->cell(20,$alt,db_formatar($ativ,'f'),0,0,"R",0);
		//  $pdf->cell(20,$alt,db_formatar($oper,'f'),0,0,"R",0);
		//  $pdf->cell(20,$alt,db_formatar($proj+$ativ+$oper,'f'),0,1,"R",0);

	}
}
$pdf->setfont('arial', 'B', 6);
$pdf->ln(3);
$pdf->cell(15, $alt, '', 0, 0, "R", 0);
$pdf->cell(80, $alt, 'T O T A L', 0, 0, "L", 0);
$pdf->setfont('arial', '', 6);
$pdf->cell(20, $alt, db_formatar($totproj, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($totativ, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($totoper, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($totproj + $totativ + $totoper, 'f'), 0, 1, "R", 0);

/*
$pdf->cell(50,$alt,"Total",0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($tvalor1,'f'),0,0,"R",0);
$pdf->cell(40,$alt,db_formatar($tvalor2,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($tvalor3,'f'),0,0,"R",0);
$pdf->cell(35,$alt,db_formatar($ttotal4,'f'),0,1,"R",0);
*/


$pdf->ln(14);

if ($origem != "O") {

  assinaturas($pdf, $classinatura,'BG');

}


$pdf->Output();
