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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;

if(substr($nivel,0,1) == '7')
  $tipo_agrupa = 1;
elseif(substr($nivel,0,1) == '1')
  $tipo_agrupa = 2;
elseif(substr($nivel,0,1) == '2')
  $tipo_agrupa = 3;

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinstabrev ;
        $xvirg = ', ';
}
$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
  $tipo_balanco = 1;
  $opcao = 1;
}else{
  $xtipo = "BALANÇO";
  if($tipo_balanco==2){
    $xtipo .= "-EMPENHADO";
  }else if($tipo_balanco==3){
    $xtipo .= "-LIQUIDADO";
  }else{
    $xtipo .= "-PAGO";
  }

  if($opcao == 3)
    $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}

$head3 = "RESUMO DA DESPESA - CONSOLIDAÇÃO GERAL";
$head4 = "ANEXO (2) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

// funcao para gerar work
db_query("begin");

if($tipo_agrupa==1){
  $sql = "create temp table work as
          select 0 as orgao, 0 as unidade, o56_elemento as elemento,o56_descr as descr,0::float8 as valor
  	  from orcelemento
	  where o56_anousu = ".db_getsession("DB_anousu");
}elseif($tipo_agrupa==2){
  $sql = "create temp table work as
          select o40_orgao as orgao, 0 as unidade, o56_elemento as elemento,o56_descr as descr,0::float8 as valor
  	  from orcelemento, orcorgao
	  where o56_anousu = ".db_getsession("DB_anousu")." and o40_anousu = ".db_getsession("DB_anousu");
}else{
  $sql = "create temp table work as
          select o41_orgao as orgao, o41_unidade as unidade, o56_elemento as elemento,o56_descr as descr,0::float8 as valor
  	  from orcelemento, orcunidade
	  where o56_anousu = ".db_getsession("DB_anousu")." and o41_anousu = ".db_getsession("DB_anousu");

}

$result = db_query($sql);

$xcampos = split("-",$orgaos);

$virgula = '';
$tem_orgao = 0;
$where_orgao = " o58_orgao in (";
if (substr($nivel,0,1)!=7)
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   if (!isset($xxcampos["1"])) continue;
   $where_orgao .= $virgula.$xxcampos["1"];
   $virgula = ', ';
   $tem_orgao = 1;
}
$where_orgao .= ")";

$virgula = '';
$tem_unidade = 0;
$where_unidade = " o58_unidade in (";
if (substr($nivel,0,1)!=7)
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   if (!isset($xxcampos["2"])) continue;
   $where_unidade .= $virgula.$xxcampos["2"];
   $virgula = ', ';
   $tem_unidade =1;
}
$where_unidade .= ")";

$virgula = '';
$tem_elemento =0;
$where_elemento = " cast(o56_elemento as bigint) in (";
if (substr($nivel,0,1)=="7")
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   if (!isset($xxcampos["1"])) continue;
   $where_elemento .= $virgula.$xxcampos["1"];
   $virgula = ', ';
   $tem_elemento =1;
}
$where_elemento .= ")";

$where = "";
if ($tem_orgao)
   $where .= $where_orgao;
if ($tem_unidade)
   $where .= " and  ".$where_unidade;
if ($tem_elemento)
   $where .= $where_elemento;

$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$where .= " and o58_instit in (".str_replace('-',', ',$db_selinstit).")";

$result_rec = db_dotacaosaldo(7,2,3,true,$where,$anousu,$dataini,$datafin,null,null,null,$tipo_balanco);

$valor = 0;

for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);

  if($tipo_balanco == 2){
    $valor = $empenhado-$anulado;
  }else if($tipo_balanco == 3){
    $valor = $liquidado;
  }else if($tipo_balanco == 4) {
    $valor = $pago;
  } else {
    $valor = $dot_ini;
  }

  if($tipo_agrupa==1){
    $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento'";

  }elseif($tipo_agrupa==2){
    $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento' and orgao = ".$o58_orgao;
  }else{
    $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento' and orgao = ".$o58_orgao. " and unidade = ".$o58_unidade;
  }

  $result = db_query($sql);

  $executa = true;
  $conta = 0;
  while ($executa==true){
    $o58_elemento = db_le_mae($o58_elemento,false);
    // altera work
    if($tipo_agrupa==1){
      $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento'";
    }elseif($tipo_agrupa==2){
      $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento' and orgao = ".$o58_orgao;
    }elseif($tipo_agrupa==3){
      $sql = "update work set valor = valor+$valor where work.elemento = '$o58_elemento' and orgao = ".$o58_orgao. " and unidade = ".$o58_unidade;
    }

    $result = db_query($sql);

    if(substr($o58_elemento,2,11)=="00000000000"){
      $executa = false;
    }
    $conta ++;
    if($conta>10) $executa = false;
  }

}

// pesquisa as dotacoes
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$sql = "select * from work order by orgao,unidade,elemento";
$result = db_query($sql);

$pagina = 1;
$valorx = 0;
$totalvalorx = 0;
$qorgao = pg_result($result,0,'orgao');
$qunidade = pg_result($result,0,'unidade');
$qualou = $qorgao.$qunidade;
$totproj  = 0;
$totativ  = 0;
$totoper  = 0;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if($valor == 0)
    continue;

  if (($qualou != $orgao.$unidade) && ($totproj > 0)){
     $pagina = 1;
     $qualou = $orgao.$unidade;
     $pdf->setfont('arial','B',6);
     $pdf->ln(3);
     $pdf->cell(132,$alt,'T O T A L ',0,0,"L",0);
     $pdf->setfont('arial','',6);
     $pdf->cell(20,$alt,db_formatar($totproj,'f'),0,0,"R",0);
     $totproj  = 0;
     $totativ  = 0;
     $totoper  = 0;

  }
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage();
      $pdf->setfont('arial','b',7);


      if($tipo_agrupa!=1){
	$sql  = "select o40_descr
		 from orcorgao
		 where o40_anousu = ".db_getsession("DB_anousu")." and
		       o40_orgao = ".$orgao;
	$resorg = db_query($sql);
	db_fieldsmemory($resorg,0);
	$pdf->cell(0,0.5,'',"TB",1,"C",0);
	$pdf->cell(10,$alt,db_formatar($orgao,'orgao'),0,0,"L",0);
	$pdf->cell(50,$alt,$o40_descr,0,1,"L",0);
        if($tipo_agrupa==2)
	   $pdf->cell(0,0.5,'',"TB",1,"C",0);
      }
      if($tipo_agrupa==3){
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
      $pdf->ln(2);
      $pdf->cell(132,$alt,"NATUREZA/",0,0,"R",0);
      $pdf->cell(20,$alt,"CATEGORIA",0,1,"R",0);
      $pdf->cell(25,$alt,"CÓDIGO",0,0,"L",0);
      $pdf->cell(67,$alt,"ESPECIFICAÇÃO",0,0,"L",0);
      $pdf->cell(20,$alt,"ELEMENTO",0,0,"R",0);
      $pdf->cell(20,$alt,"MODALIDADE",0,0,"R",0);
      $pdf->cell(20,$alt,"ECONÔMICA",0,1,"R",0);
      $pdf->cell(0,$alt,'',"T",1,"C",0);

    }
    $pdf->setfont('arial','',6);
    $pdf->cell(25,$alt,db_formatar($elemento,'elemento'),0,0,"L",0);
    if(substr($elemento,2,3) == "000"){
       $xx = 1;
       $pdf->cell($xx,$alt,"",0,0,"R",0);
       $pdf->cell(55+11,$alt,$descr,0,0,"L",0);
       $pdf->cell(20,$alt,"",0,0,"R",0);
       $pdf->cell(20,$alt,"",0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
       $totproj  += $valor;
    }elseif(substr($elemento,3,2) == "00"){
       $xx = 3;
       $pdf->cell($xx,$alt,"",0,0,"R",0);
       $pdf->cell(55+9,$alt,$descr,0,0,"L",0);
       $pdf->cell(20,$alt,"",0,0,"R",0);
       $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,"",0,1,"R",0);
       $totativ  += $valor;
    }else{
       $xx = 5;
       $pdf->cell($xx,$alt,"",0,0,"R",0);
       $pdf->cell(55+7,$alt,$descr,0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
       $pdf->cell(20,$alt,'',0,0,"R",0);
       $pdf->cell(20,$alt,"",0,0,"R",0);
       $pdf->cell(20,$alt,"",0,1,"R",0);
       $totoper  += $valor;
    }

  if(pg_numrows($result)>($i+1) && $i > 0){
    $auxorgao   = pg_result($result,$i+1,'orgao');
    $auxunidade = pg_result($result,$i+1,'unidade');
    if($orgao != $auxorgao && $unidade != $auxunidade ){
      if($tipo_agrupa!=1){
        $pdf->cell(110,$alt,"",0,0,"L",0);
        $pdf->cell(40,$alt,"",0,0,"R",0);
        $pdf->cell(20,$alt,"Total ",0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($valorx,'f'),0,1,"R",0);
      }
      $totalvalorx += $valorx;
      $valorx = 0;
    }
  }
     $qualou = $orgao.$unidade;

}
if($totproj > 0){
  $pdf->ln(3);
  $pdf->cell(132,$alt,'T O T A L ',0,0,"L",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(20,$alt,db_formatar($totproj,'f'),0,0,"R",0);
}

if($origem!=1){
  if($valorx > 0){
    $pdf->cell(110,$alt,"",0,0,"L",0);
    $pdf->cell(40,$alt,'',0,0,"R",0);
    $pdf->cell(20,$alt,"Total ",0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($valorx,'f'),0,1,"R",0);
  }
}

$valorm = 0;
$descrm = "";
$pagina = 1;

$sql = "select elemento,
               descr,
	       sum(valor)
	from work
	where substr(elemento,4,10) = '0000000000'
	  and substr(elemento,2,1) != ''
	  and substr(elemento,3,1) != '0'
        group by elemento,
 	      descr
	order by elemento";
$result = db_query($sql);

$pagina = 0;
$pdf->addpage();
$pdf->setfont('arial','b',8);

$pdf->cell(45,$alt,'',0,0,"L",0);
$pdf->cell(40,$alt,"Resumo",0,1,"L",0);
$pdf->cell(4,$alt,'',0,1,"L",0);
$desp_cor = 0;
$desp_cap = 0;
$res_con = 0;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $pdf->setfont('arial','',7);
  if(substr($elemento,1,1) == "3" ){
    $pdf->cell(45,$alt,'',0,0,"L",0);
    $pdf->cell(50,$alt,$descr,0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($sum,'f'),0,1,"R",0);
    $desp_cor += $sum;
  }
}
$pdf->setfont('arial','b',8);
$pdf->cell(45,$alt,'',0,0,"R",0);
$pdf->cell(50,$alt,"Total das Despesas Correntes",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($desp_cor,'f'),"T",1,"R",0);
$pdf->ln(3);

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $pdf->setfont('arial','',7);
  if(substr($elemento,1,1) == "4" ){
    $pdf->cell(45,$alt,'',0,0,"L",0);
    $pdf->cell(50,$alt,$descr,0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($sum,'f'),0,1,"R",0);
    $desp_cap += $sum;
  }
}
$pdf->setfont('arial','b',8);
$pdf->cell(45,$alt,'',0,0,"R",0);
$pdf->cell(50,$alt,"Total das Despesas de Capital",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($desp_cap,'f'),"T",1,"R",0);
$pdf->ln(3);

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $pdf->setfont('arial','',7);
  if(substr($elemento,1,1) == "9" ||substr($elemento,1,1) == "7" ){
    $pdf->cell(45,$alt,'',0,0,"L",0);
    $pdf->cell(50,$alt,$descr,0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($sum,'f'),0,1,"R",0);
    $res_con += $sum;
  }
}
$pdf->setfont('arial','b',8);
$pdf->cell(45,$alt,'',0,0,"R",0);
$pdf->cell(50,$alt,"Total das Reservas de Contingências",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($res_con,'f'),"T",1,"R",0);
$pdf->ln(3);

$pdf->cell(45,$alt,'',0,0,"R",0);
$pdf->cell(50,$alt,"Total Geral",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($res_con + $desp_cap + $desp_cor,'f'),"T",1,"R",0);

$pdf->ln(14);

if($origem != "O"){
  assinaturas($pdf,$classinatura,'BG');
}

db_query("commit");

$pdf->Output();
