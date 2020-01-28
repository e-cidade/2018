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

require_once("libs/db_liborcamento.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$xinstit    = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg      = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}
$tipo_rel = 1;

$head3 = "RESUMO DA DESPESA ";
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

// funcao para gerar work
  $sql = "create temp table work(
                 descr varchar(20),
	         vlr1  float8 ,
	         vlr2  float8,
	         vlr3  float8,
	         vlr4  float8,
	         vlr5  float8,
	         vlr6  float8,
	         vlr7  float8,
	         vlr8  float8,
	         vlr9  float8,
	         vlr10 float8,
	         vlr11 float8,
		 vlr12 float8
	 )";

$result = db_query($sql);
$anousu  = db_getsession("DB_anousu");
$where = " o58_instit in (".str_replace('-',', ',$db_selinstit).")";

for($mes=1;$mes<13;$mes++){

  echo $mes;
  db_query("begin");
  $result_rec = db_dotacaosaldo(1,2,3,true,$where,$anousu,db_getsession("DB_anousu")."-$mes-01",db_getsession("DB_anousu")."-$mes-01");
  db_query("rollback");

  $valor = 0;

  for($i=0;$i<pg_numrows($result_rec);$i++){
    db_fieldsmemory($result_rec,$i);

    $valor = $empenhado-$anulado;
    $sql = "update work set vlr$mes = vlr$mes+$valor ,descr = 'Despesa'" ;

    $result = db_query($sql);

  }
  // pesquisa as dotacoes
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

   $sql = "select descr,
                  sum(vlr1) as vlr1,
                  sum(vlr2) as vlr2,
                  sum(vlr3) as vlr3,
                  sum(vlr4) as vlr4,
                  sum(vlr5) as vlr5,
                  sum(vlr6) as vlr6,
                  sum(vlr7) as vlr7,
                  sum(vlr8) as vlr8,
                  sum(vlr9) as vlr9,
                  sum(vlr10) as vlr10,
                  sum(vlr11) as vlr11,
                  sum(vlr12) as vlr12
           from work
	   group by descr
	   order by descr";

$result = db_query($sql);

$pagina = 1;
$qorgao = pg_result($result,0,'orgao');
$qunidade = pg_result($result,0,'unidade');
$qualou = "$qorgao$unidade";
$totoper  = 0;

$tvlr1 = 0;
$tvlr2 = 0;
$tvlr3 = 0;
$tvlr4 = 0;
$tvlr5 = 0;
$tvlr6 = 0;
$tvlr7 = 0;
$tvlr8 = 0;
$tvlr9 = 0;
$tvlr10 = 0;
$tvlr11 = 0;
$tvlr12 = 0;

if($tipo_rel==2){

  $troca_secretaria = false;
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    if($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12==0)
      continue;

    if (("$qualou" != "$orgao$unidade") ){

       $troca_secretaria = true;
       $qualou = "$orgao$unidade";
       $pdf->setfont('arial','',6);
       $pdf->cell(75,$alt,"Total ",0,0,"L",0);
       $pdf->cell(15,$alt,db_formatar($tvlr1,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr2,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr3,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr4,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr5,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr6,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr7,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr8,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr9,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr10,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr11,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr12,'f'),0,0,"R",0);
       $pdf->cell(15,$alt,db_formatar($tvlr1+$tvlr2+$tvlr3+$tvlr4+$tvlr5+$tvlr6+$tvlr7+$tvlr8+$tvlr9+$tvlr10+$tvlr11+$tvlr12,'f'),0,1,"R",0);
       $tvlr1 = 0;
       $tvlr2 = 0;
       $tvlr3 = 0;
       $tvlr4 = 0;
       $tvlr5 = 0;
       $tvlr6 = 0;
       $tvlr7 = 0;
       $tvlr8 = 0;
       $tvlr9 = 0;
       $tvlr10 = 0;
       $tvlr11 = 0;
       $tvlr12 = 0;
    }
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $troca_secretaria = true;
    }
    if($troca_secretaria == true){
      $troca_secretaria = false;

      $pdf->setfont('arial','b',7);
      $sql  = "select o40_descr
		 from orcorgao
		 where o40_anousu = ".db_getsession("DB_anousu")." and
		       o40_orgao = ".$orgao;
      $resorg = db_query($sql);
      db_fieldsmemory($resorg,0);

      $pdf->cell(0,0.5,'',"TB",1,"C",0);
      $pdf->cell(10,$alt,db_formatar($orgao,'orgao'),0,0,"L",0);
      $pdf->cell(50,$alt,$o40_descr,0,1,"L",0);
      $pdf->setfont('arial','',6);

      if($tipo_agrupa=='2'){
	$sql  = "select o41_descr
		   from orcunidade
		   where o41_anousu = ".db_getsession("DB_anousu")." and
			 o41_orgao = ".$orgao." and o41_unidade = ".$unidade;
	$resorg = db_query($sql);
	db_fieldsmemory($resorg,0);
	$pdf->cell(10,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao'),0,0,"L",0);
	$pdf->cell(50,$alt,$o41_descr,0,1,"L",0);
	$pdf->cell(0,0.5,'',"TB",1,"C",0);
      }

      $xx= 20;
      $pdf->cell($xx,$alt,$cabec,0,0,"R",0);
      $pdf->cell(55,$alt,"Descrição",0,0,"L",0);
      $pdf->cell(15,$alt,"Janeiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Fevereiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Março",0,0,"R",0);
      $pdf->cell(15,$alt,"Abril",0,0,"R",0);
      $pdf->cell(15,$alt,"Maio",0,0,"R",0);
      $pdf->cell(15,$alt,"Junho",0,0,"R",0);
      $pdf->cell(15,$alt,"Julho",0,0,"R",0);
      $pdf->cell(15,$alt,"Agosto",0,0,"R",0);
      $pdf->cell(15,$alt,"Setembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Outubro",0,0,"R",0);
      $pdf->cell(15,$alt,"Novembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Dezembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Total",0,1,"R",0);
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(20,$alt,$campo,0,0,"R",0);
    $pdf->cell(55,$alt,$descr,0,0,"L",0);
    $pdf->cell(15,$alt,db_formatar($vlr1,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr2,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr3,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr4,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr5,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr6,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr7,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr8,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr9,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr10,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr11,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr12,'f'),0,0,"R",0);
    $pdf->cell(15,$alt,db_formatar($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12,'f'),0,1,"R",0);

    $tvlr1 += $vlr1;
    $tvlr2 += $vlr2;
    $tvlr3 += $vlr3;
    $tvlr4 += $vlr4;
    $tvlr5 += $vlr5;
    $tvlr6 += $vlr6;
    $tvlr7 += $vlr7;
    $tvlr8 += $vlr8;
    $tvlr9 += $vlr9;
    $tvlr10 += $vlr10;
    $tvlr11 += $vlr11;
    $tvlr12 += $vlr12;

    $ttvlr1 += $vlr1;
    $ttvlr2 += $vlr2;
    $ttvlr3 += $vlr3;
    $ttvlr4 += $vlr4;
    $ttvlr5 += $vlr5;
    $ttvlr6 += $vlr6;
    $ttvlr7 += $vlr7;
    $ttvlr8 += $vlr8;
    $ttvlr9 += $vlr9;
    $ttvlr10 += $vlr10;
    $ttvlr11 += $vlr11;
    $ttvlr12 += $vlr12;
  }

   $pdf->setfont('arial','',6);
   $pdf->cell(75,$alt,"Total ",0,0,"L",0);
   $pdf->cell(15,$alt,db_formatar($tvlr1,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr2,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr3,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr4,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr5,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr6,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr7,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr8,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr9,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr10,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr11,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr12,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($tvlr1+$tvlr2+$tvlr3+$tvlr4+$tvlr5+$tvlr6+$tvlr7+$tvlr8+$tvlr9+$tvlr10+$tvlr11+$tvlr12,'f'),0,1,"R",0);

}else{

  $troca_secretaria = false;
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);

    if (("$qualou" != "$orgao$unidade") ){
       $troca_secretaria = true;
       $qualou = "$orgao$unidade";
       $pdf->setfont('arial','',6);
    }

    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $pdf->setfont('arial','',6);
      $pdf->cell(20,$alt,"Orgão".($unidade!=0?"/Unidade":""),0,0,"L",0);
      $pdf->cell(55,$alt,"Descrição",0,0,"L",0);
      $pdf->cell(15,$alt,"Janeiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Fevereiro",0,0,"R",0);
      $pdf->cell(15,$alt,"Março",0,0,"R",0);
      $pdf->cell(15,$alt,"Abril",0,0,"R",0);
      $pdf->cell(15,$alt,"Maio",0,0,"R",0);
      $pdf->cell(15,$alt,"Junho",0,0,"R",0);
      $pdf->cell(15,$alt,"Julho",0,0,"R",0);
      $pdf->cell(15,$alt,"Agosto",0,0,"R",0);
      $pdf->cell(15,$alt,"Setembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Outubro",0,0,"R",0);
      $pdf->cell(15,$alt,"Novembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Dezembro",0,0,"R",0);
      $pdf->cell(15,$alt,"Total",0,1,"R",0);
   }

   $pdf->setfont('arial','b',6);
   if($tipo_agrupa=='1'){
     $sql  = "select o40_descr
		 from orcorgao
		 where o40_anousu = ".db_getsession("DB_anousu")." and
		       o40_orgao = ".$orgao;
     $resorg = db_query($sql);
     db_fieldsmemory($resorg,0);
     $pdf->cell(20,$alt,db_formatar($orgao,'orgao'),0,0,"L",0);
     $pdf->cell(55,$alt,$o40_descr,0,0,"L",0);
   }
   if($tipo_agrupa=='2'){
     $sql  = "select o41_descr
		   from orcunidade
		   where o41_anousu = ".db_getsession("DB_anousu")." and
			 o41_orgao = ".$orgao." and o41_unidade = ".$unidade;
     $resorg = db_query($sql);
     db_fieldsmemory($resorg,0);
     $pdf->cell(20,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao'),0,0,"L",0);
     $pdf->cell(55,$alt,$o41_descr,0,0,"L",0);

   }
   $pdf->setfont('arial','',6);
   $pdf->cell(15,$alt,db_formatar($vlr1,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr2,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr3,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr4,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr5,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr6,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr7,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr8,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr9,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr10,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr11,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr12,'f'),0,0,"R",0);
   $pdf->cell(15,$alt,db_formatar($vlr1+$vlr2+$vlr3+$vlr4+$vlr5+$vlr6+$vlr7+$vlr8+$vlr9+$vlr10+$vlr11+$vlr12,'f'),0,1,"R",0);

   $ttvlr1 += $vlr1;
   $ttvlr2 += $vlr2;
   $ttvlr3 += $vlr3;
   $ttvlr4 += $vlr4;
   $ttvlr5 += $vlr5;
   $ttvlr6 += $vlr6;
   $ttvlr7 += $vlr7;
   $ttvlr8 += $vlr8;
   $ttvlr9 += $vlr9;
   $ttvlr10 += $vlr10;
   $ttvlr11 += $vlr11;
   $ttvlr12 += $vlr12;

  }

}
$pdf->setfont('arial','',6);
$pdf->cell(75,$alt,"Total Geral ",1,0,"L",0);
$pdf->cell(15,$alt,db_formatar($ttvlr1,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr2,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr3,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr4,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr5,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr6,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr7,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr8,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr9,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr10,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr11,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr12,'f'),1,0,"R",0);
$pdf->cell(15,$alt,db_formatar($ttvlr1+$ttvlr2+$ttvlr3+$ttvlr4+$ttvlr5+$ttvlr6+$ttvlr7+$ttvlr8+$ttvlr9+$ttvlr10+$ttvlr11+$ttvlr12,'f'),1,1,"R",0);

$pdf->Output();