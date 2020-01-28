<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_cadimobil_classe.php");
require_once("classes/db_imobil_classe.php");

$clcadimobil = new cl_cadimobil;
$climobil    = new cl_imobil;
$clcadimobil->rotulo->label();
$climobil->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('z01_numcgm');
$clrotulo->label('j14_codigo');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where = "";
if ($lista != "") {
	if (isset ($ver) and $ver == "com") {
		$where = " and j14_codigo in  ($lista)";
	} else {
		$where = " and j14_codigo not in  ($lista)";
	}
}

$head3 = "RELATÓRIO DE CONTRIBUINTE POR RUA";

$result = db_query("select distinct j14_codigo, j14_nome from proprietario_ender where j14_codigo > 0 $where");
if (pg_numrows($result) == 0){

   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
   exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$numrows = pg_numrows($result);
for($x = 0; $x < $numrows; $x++){
   db_fieldsmemory($result,$x);

	   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	      $pdf->addpage("");
   }
   $troca=1;
   $pdf->setfont('arial','b',10);
   $pdf->cell(180,$alt,$RLj14_codigo.": ".$j14_codigo." - ".$j14_nome,"B",1,"L",0);
   $result_contrib = db_query("select distinct z01_numcgm,z01_nome,j39_numero,j39_compl from proprietario_nome inner join proprietario_ender on proprietario_ender.j01_matric=proprietario_nome.j01_matric where j14_codigo=$j14_codigo");
   $numrows_contrib = pg_numrows($result_contrib);
   if ($numrows_contrib==0){
   		$pdf->cell(160,$alt,"Não existem contribuintes vinculadas.",0,1,"C",0);
   		$pdf->setfont('arial','b',8);
   		$pdf->cell(160,$alt,'TOTAL DE CONTRIBUINTES  :  '.$total,"T",0,"R",0);
   		$pdf->Ln();
   		$pdf->Ln();
   		$troca = 0;
   		continue;
   }else{
	   $pdf->setfont('arial','b',8);
	   $pdf->cell(30,$alt,$RLz01_numcgm,1,0,"C",1);
	   $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
	   $pdf->cell(20,$alt,"Número",1,0,"C",1);
	   $pdf->cell(50,$alt,"Complemento",1,1,"C",1);
   }
   $troca = 0;
   $p=0;
   $total = 0;
   for($w=0;$w<$numrows_contrib;$w++){
   		db_fieldsmemory($result_contrib,$w);
   		if ($pdf->gety() > $pdf->h - 30 ){
      		$pdf->addpage("");
      		$pdf->setfont('arial','b',10);
      		$pdf->cell(180,$alt,$RLj14_codigo.": ".$j14_codigo." - ".$j14_nome,"B",1,"L",0);
      		$pdf->setfont('arial','b',8);
	   		$pdf->cell(30,$alt,$RLz01_numcgm,1,0,"C",1);
	   $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
	   $pdf->cell(20,$alt,"Número",1,0,"C",1);
	   $pdf->cell(50,$alt,"Complemento",1,1,"C",1);
		   	$p=0;
   		}
   		$pdf->setfont('arial','',7);
   		$pdf->cell(30,$alt,$z01_numcgm,0,0,"C",$p);
   		$pdf->cell(80,$alt,$z01_nome,0,0,"L",$p);
   		$pdf->cell(20,$alt,$j39_numero,0,0,"C",$p);
   		$pdf->cell(50,$alt,$j39_compl,0,1,"L",$p);

   		if ($p==0){
   			$p=1;
   		}else{
   			$p=0;
   		}
   		$total++;
   }
   $pdf->setfont('arial','b',8);
   $pdf->cell(160,$alt,'TOTAL DE CONTRIBUINTES	:  '.$total,"T",0,"R",0);
   $pdf->Ln();
   $pdf->Ln();
}

$pdf->setfont('arial','b',8);
$pdf->Output();