<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_libtributario.php");
require_once("classes/db_arreprescr_classe.php");

db_postmemory($HTTP_SERVER_VARS);

$clarreprescr = new cl_arreprescr;
$where = " WHERE arreprescr.k30_anulado is false ";

if ($tipoorigem=="numpre"){
	$where .= " and arreprescr.k30_numpre = $valororigem";
}else	if ($tipoorigem=="numcgm"){
	$where .= " and arrenumcgm.k00_numcgm = $valororigem";
}else if ($tipoorigem=="matric"){
	$where .= " and arrematric.k00_matric = $valororigem";
}else if ($tipoorigem=="inscr"){
	$where .= " and arreinscr.k00_inscr = $valororigem";
}
$campos         = " distinct case
                               when arrematric.k00_numpre is not null then 'Matr-'||arrematric.k00_matric
															 when arreinscr.k00_numpre is not null then 'Insc-'|| arreinscr.k00_inscr
															 else 'CGM-'||arrenumcgm.k00_numcgm
														 end as origemdeb,
														   arreprescr.*,v01_exerc,login, k31_data, k31_hora, k31_obs,k01_descr,(k30_vlrcorr+k30_vlrjuros+k30_multa-k30_desconto) as valor ";
$sqlArreprescr  = " select $campos from arreprescr                                                     ";
$sqlArreprescr .= "      inner join arreinstit on arreinstit.k00_numpre =	arreprescr.k30_numpre        ";
$sqlArreprescr .= "                           and arreinstit.k00_instit =	".db_getsession('DB_instit');
$sqlArreprescr .= "        inner join arrenumcgm on arrenumcgm.k00_numpre = arreprescr.k30_numpre      ";
$sqlArreprescr .= "        left  join arrematric on arrematric.k00_numpre = arreprescr.k30_numpre      ";
$sqlArreprescr .= "        left  join arreinscr  on arreinscr.k00_numpre  = arreprescr.k30_numpre      ";
$sqlArreprescr .= "        inner join divida      on k30_numpre  = v01_numpre                          ";
$sqlArreprescr .= "                              and k30_numpar  = v01_numpar                          ";
$sqlArreprescr .= "        inner join prescricao  on k31_codigo  = k30_prescricao                      ";
$sqlArreprescr .= "        inner join db_usuarios on k31_usuario = id_usuario                          ";
$sqlArreprescr .= "        inner join tabrec      on k30_receit  = k02_codigo                          ";
$sqlArreprescr .= "        inner join histcalc    on k30_hist    = k01_codigo                          ";
$sqlArreprescr .= $where;
$rsArreprescr  =  $clarreprescr->sql_record($sqlArreprescr);

$numrows = $clarreprescr->numrows;

$head1 = "";
$head2 = "";
$head3 = db_getNomeSecretaria();
$head4 = "Relatório dos Débitos Prescritos";
$head5 = "";
$head7 = "";
$head8 = "";
$head9 = "";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->SetFillColor(220);

$corcab      = '210';

$pdf->SetFont('Arial','B',7);
$pdf->SetFillColor($corcab);
$pdf->cell(15,4,"Origem"      ,1,0,"C",1);
$pdf->cell(15,4,"Exercício"   ,1,0,"C",1);
$pdf->cell(20,4,"Numpre"      ,1,0,"C",1);
$pdf->cell(15,4,"Parcela"     ,1,0,"C",1);
$pdf->cell(20,4,"Vencimento"  ,1,0,"C",1);
$pdf->cell(15,4,"Receita"     ,1,0,"C",1);
$pdf->cell(20,4,"Vlr. His."   ,1,0,"C",1);
$pdf->cell(20,4,"Vlr.Corr."   ,1,0,"C",1);
$pdf->cell(20,4,"Multa"       ,1,0,"C",1);
$pdf->cell(20,4,"Juros"       ,1,0,"C",1);
$pdf->cell(20,4,"Total"       ,1,0,"C",1);
$pdf->cell(20,4,"Usuário"     ,1,0,"C",1);
$pdf->cell(55,4,"Observações" ,1,1,"C",1);
$pdf->cell(40,1,"" ,0,1,"C",0);
$pdf->SetFont('arial','',6);
$tottotal = 0;
for($i = 0;$i < $numrows;$i++) {
	db_fieldsmemory($rsArreprescr,$i);
	if($pdf->GetY() > ( $pdf->h - 30 )){
		$linha = 0;
		$pdf->AddPage("L");
		$pdf->SetFont('Arial','B',7);
    $pdf->cell(15,4,"Origem"      ,1,0,"C",1);
		$pdf->cell(15,4,"Exercício"   ,1,0,"C",1);
		$pdf->cell(20,4,"Numpre"      ,1,0,"C",1);
		$pdf->cell(15,4,"Parcela"     ,1,0,"C",1);
		$pdf->cell(20,4,"Vencimento"  ,1,0,"C",1);
		$pdf->cell(15,4,"Receita"     ,1,0,"C",1);
		$pdf->cell(20,4,"Vlr. His."   ,1,0,"C",1);
		$pdf->cell(20,4,"Vlr. Corr."  ,1,0,"C",1);
		$pdf->cell(20,4,"Multa"       ,1,0,"C",1);
		$pdf->cell(20,4,"Juros"       ,1,0,"C",1);
		$pdf->cell(20,4,"Total"       ,1,0,"C",1);
		$pdf->cell(20,4,"Usuário"     ,1,0,"C",1);
		$pdf->cell(55,4,"Observações" ,1,1,"C",1);
		$pdf->cell(40,1,"" ,0,1,"C",0);
		$pdf->SetFont('arial','',6);
	}
  if($i % 2 == 0){
    $corfundo = 236;
	}else{
	  $corfundo = 245;
	}
	$pdf->SetFillColor($corfundo);
	$pdf->Cell(15,4,$origemdeb                  	 ,0,0,"L",1);
	$pdf->Cell(15,4,$v01_exerc                     ,0,0,"C",1);
	$pdf->Cell(20,4,$k30_numpre	                 	 ,0,0,"C",1);
	$pdf->Cell(15,4,$k30_numpar	                   ,0,0,"C",1);
	$pdf->Cell(20,4,db_formatar($k30_dtvenc,'d')   ,0,0,"C",1);
	$pdf->Cell(15,4,$k30_receit                 	 ,0,0,"C",1);
	$pdf->Cell(20,4,db_formatar($k30_valor,'f')    ,0,0,"R",1);
	$pdf->Cell(20,4,db_formatar($k30_vlrcorr,'f')  ,0,0,"R",1);
	$pdf->Cell(20,4,db_formatar($k30_multa,'f')    ,0,0,"R",1);
	$pdf->Cell(20,4,db_formatar($k30_vlrjuros,'f') ,0,0,"R",1);
	$pdf->Cell(20,4,db_formatar($valor,'f')    		 ,0,0,"R",1);
	$pdf->Cell(20,4,$login		          	       	 ,0,0,"L",1);
	$pdf->Cell(55,4,(strlen($k31_obs)>40?substr($k31_obs,0,40)."...":$k31_obs),0,1,"L",1);
	$tottotal += $valor;
}

$pdf->SetFont('arial','B',6);
$pdf->cell($pdf->w-25,4,'TOTAL PAGO : '.db_formatar($tottotal,'f'),1,0,"R",0);
$pdf->Ln(3);

$pdf->Output();