<?php
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

require_once("libs/db_liborcamento.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");

db_postmemory($HTTP_POST_VARS);

$quebra_unidade = "S";
$quebra_orgao = "S";
$lista_subeleme="N";

$nivel = '8A';

  $sql8 = "  select  *
             from orcdotacao
	              inner join orctiporec on o58_codigo = o15_codigo
	              inner join orcelemento on o58_codele = o56_codele
                            and o56_anousu = o58_anousu
       where o58_orgao 	= $o40_orgao
         and o58_unidade 	= $unidade
   and o58_anousu = ".db_getsession("DB_anousu");
  $result8 = db_query($sql8);
  $orgaos = "";
  $colsub = "";
   for($i8=0;$i8<pg_numrows($result8);$i8++){
     db_fieldsmemory($result8,$i8);
     $orgaos .= $colsub."$o58_orgao"."$o58_unidade"."_$o58_funcao"."_$o58_subfuncao"."_$o58_programa"."_$o58_projativ"."_$o56_elemento"."_$o15_codigo" ;
      $colsub = "_";
   }

$tipo_mesini = 1;
$tipo_mesfim = 1;

//$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
//$tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
//$tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento
// 6 = recurso
$tipo_agrupa = 3;
$tipo_nivel = 6;

$qorgao = 0;
$qunidade = 0;

$head1 = "DEMONSTRATIVO DA DESPESA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}
$head5 = "INSTITUIÇÕES : ".$descr_inst;

if (substr($nivel,1,1) == 'A'){
  $nivela = substr($nivel,0,1);

db_query("create temp table t as select * from  orcdotacao where o58_orgao = $o40_orgao and o58_unidade = $unidade ");
$sele_work =  " o58_orgao = $o40_orgao and o58_unidade = $unidade ";

$anousu  = db_getsession("DB_anousu");
$dataini = date("Y-m-d",db_getsession("DB_datausu"));
$datafin = date("Y-m-d",db_getsession("DB_datausu"));
$result = db_dotacaosaldo($nivela,1,2,true,$sele_work,$anousu,$dataini,$datafin);

db_query("commit");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$troca         = 1;
$alt           = 4;
$qualou        = 0;
$totproj       = 0;
$totativ       = 0;
$pagina        = 1;
$xorgao        = 0;
$xunidade      = 0;
$xfuncao       = 0;
$xsubfuncao    = 0;
$xprograma     = 0;
$xprojativ     = 0;
$xelemento     = 0;
$totorgaoanter = 0;
$totorgaoreser = 0;
$totorgaoatual = 0;
$totunidaanter = 0;
$totunidareser = 0;
$totunidaatual = 0;
$pagina        = 1;

for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if($xorgao.$xunidade != $o58_orgao.$o58_unidade && $quebra_unidade == 'S' && $pagina != 1 && $totunidaanter != 0){
    $pdf->setfont('arial','b',7);
    $pagina = 1;
    $pdf->ln(3);
    $pdf->cell(50,$alt,'',0,0,"L",0);
    $pdf->cell(85,$alt,'TOTAL DA UNIDADE',0,0,"L",0,'.');
    $pdf->cell(20,$alt,db_formatar($totunidaanter,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidareser,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
    $pdf->setfont('arial','',7);
    $totunidaanter = 0;
    $totunidareser = 0;
    $totunidaatual = 0;
  }

  if($xorgao != $o58_orgao && $quebra_orgao =='S' ){
    $pdf->setfont('arial','b',7);
    $pagina = 1;
    $pdf->ln(3);
    $pdf->cell(50,$alt,'',0,0,"L",0);
    $pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');
    $pdf->cell(20,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);
    $pdf->setfont('arial','',7);
    $totorgaoanter = 0;
    $totorgaoreser = 0;
    $totorgaoatual = 0;
  }
  if($pdf->gety()>$pdf->h-30 || $pagina == 1){
    $pagina = 0;
    $qualou = $o58_orgao.$o58_unidade;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2);
    $pdf->cell(25,$alt,"DOTAÇÃO",0,0,"L",0);
    $pdf->cell(100,$alt,"RECURSO",0,0,"C",0);
    $pdf->cell(10,$alt,"REDUZ",0,0,"R",0);
    $pdf->cell(20,$alt,"SALDO ANT.",0,0,"C",0);
    $pdf->cell(20,$alt,"RESERVADO",0,0,"R",0);
    $pdf->cell(20,$alt,"SALDO ATUAL",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }

  if($xorgao != $o58_orgao && $o58_orgao != 0){
      $xorgao = $o58_orgao;
    if($nivela == 1){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,1,"L",0);
      $xunidade = 0;
    }
  }
  if($o58_orgao.$o58_unidade != $xorgao.$xunidade && $o58_unidade != 0){
    $xunidade = $o58_unidade;
    if($nivela == 2){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o41_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o41_descr,0,1,"L",0);
    }
  }

  if($o58_orgao.$o58_unidade.$o58_funcao != $xfuncao && $o58_funcao != 0 ){
    $xfuncao = $o58_orgao.$o58_unidade.$o58_funcao;
    $descr = $o52_descr;
    if($nivela == 3){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao != $xsubfuncao && $o58_subfuncao != 0){
    $xsubfuncao = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao;
    $descr = $o53_descr;
    if($nivela == 4){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa != $xprograma && $o58_programa != 0){
    $xprograma = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa;
    $descr = $o54_descr;
    if($nivela == 5){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ != $xprojativ && $o58_projativ != 0){
    $xprojativ = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ;
    $descr = $o55_descr;
    if($nivela == 6){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }else{
      $pdf->setfont('arial','b',7);
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $pdf->setfont('arial','',7);
    }
  }
  if($o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento != $xelemento && $o58_elemento  != 0){
    $xelemento = $o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento;
    $descr = $o56_descr;
    if($nivela == 7){
      $pdf->cell(25,$alt,db_formatar($o58_elemento,'elemento'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
  }
  if($o58_codigo > 0){
    $descr = $o56_descr;
    $pdf->cell(20,$alt,$o58_elemento,1,0,"L",0);
    $pdf->cell(60,$alt,substr($descr,0,37),1,0,"L",0);
    $pdf->cell(10,$alt,db_formatar($o58_codigo,'s','0',4,'e'),1,0,"L",0);
    $pdf->cell(30,$alt,substr($o15_descr,0,20),1,0,"L",0);
    $pdf->cell(15,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($atual,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($reservado,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),1,1,"R",0);
    $totorgaoanter += $atual;
    $totorgaoreser += $reservado;
    $totorgaoatual += $atual_menos_reservado;
    $totunidaanter += $atual;
    $totunidareser += $reservado;
    $totunidaatual += $atual_menos_reservado;

    if($lista_subeleme=='S'){

      $sql = "select *
              from orcelemento
	      where substr(o56_elemento,1,7) = '".str_replace('.','',substr($o58_elemento,0,7))."' and
	            substr(o56_elemento,8,5) != '00000' and o56_anousu = ".db_getsession("DB_anousu")." and
		    o56_liberado is true";
      $res = db_query($sql);
      for($ne=0;$ne<pg_numrows($res);$ne++){

	      db_fieldsmemory($res,$ne);
        $pdf->cell(20,$alt,$o56_elemento,0,0,"L",0);
        $pdf->cell(60,$alt,substr($o56_descr,0,37),0,0,"L",0);
        $pdf->cell(105,$alt,$o56_finali,0,1,"L",0);
      }

    }

  }
}
if($quebra_unidade == 'S'){

$pdf->setfont('arial','b',7);
$pdf->ln(3);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(85,$alt,'TOTAL DA UNIDADE ',0,0,"L",0,'.');
$pdf->cell(20,$alt,db_formatar($totunidaanter,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totunidareser,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
}
$pdf->ln(3);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');
$pdf->cell(20,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);

}else{
  $nivela = substr($nivel,0,1);
  $xcampos = str_replace('-',',',str_replace('pai_','',$orgaos));
  $where = '';
  if($nivela == 1){
    $where .= " w.o58_orgao in ($xcampos)";
  }elseif($nivela == 2){
    $xunid = split(",",$xcampos);
    $virgula = "";
    for($xu=0;$xu < sizeof($xunid);$xu++){
      @$xxcampos .= $virgula."'".$xunid[$xu]."'";
      $virgula = ', ';
    }
    $where .= " lpad(w.o58_orgao,2,'0')||lpad(w.o58_unidade,2,'0') in ($xxcampos)";
  }elseif($nivela == 3){
    $where .= " w.o58_funcao in ($xcampos)";
  }elseif($nivela == 4){
    $where .= " w.o58_subfuncao in ($xcampos)";
  }elseif($nivela == 5){
    $where .= " w.o58_programa in ($xcampos)";
  }elseif($nivela == 6){
    $where .= " w.o58_projativ in ($xcampos)";
  }elseif($nivela == 7){
    $where .= " e.o56_elemento in ($xcampos)";
  }elseif($nivela == 8){
    $where .= " w.o58_codigo in ($xcampos)";
  }

$anousu  = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu")."-01-01";
$datafin = date("Y-m-d",db_getsession("DB_datausu"));
$result = db_dotacaosaldo($nivela,3,2,true,$where,$anousu,$dataini,$datafin);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$troca         = 1;
$alt           = 4;
$qualou        = 0;
$totproj       = 0;
$totativ       = 0;
$pagina        = 1;
$xorgao        = 0;
$xunidade      = 0;
$xfuncao       = 0;
$xsubfuncao    = 0;
$xprograma     = 0;
$xprojativ     = 0;
$xelemento     = 0;
$totorgaoanter = 0;
$totorgaoreser = 0;
$totorgaoatual = 0;
$totunidaanter = 0;
$totunidareser = 0;
$totunidaatual = 0;
$pagina = 1;

for($k=0;$k<pg_numrows($result);$k++){

  db_fieldsmemory($result,$k);
  if($pdf->gety()>$pdf->h-30 || $pagina == 1){
    $pagina = 0;
    $qualou = $o58_orgao.$o58_unidade;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2);
    $pdf->cell(25,$alt,"DOTAÇÃO",0,0,"L",0);
    if($nivela == 1)
      $pdf->cell(100,$alt,"ÓRGÃO",0,0,"L",0);
    if($nivela == 2)
      $pdf->cell(100,$alt,"UNIDADE",0,0,"L",0);
    if($nivela == 3)
      $pdf->cell(100,$alt,"FUNÇÃO",0,0,"L",0);
    if($nivela == 4)
      $pdf->cell(100,$alt,"SUBFUNÇÃO",0,0,"L",0);
    if($nivela == 5)
      $pdf->cell(100,$alt,"PROGRAMA",0,0,"L",0);
    if($nivela == 6)
      $pdf->cell(100,$alt,"PROJ/ATIV",0,0,"L",0);
    if($nivela == 7)
      $pdf->cell(100,$alt,"ELEMENTO",0,0,"L",0);
    if($nivela == 8)
      $pdf->cell(100,$alt,"RECURSO",0,0,"L",0);
    $pdf->cell(10,$alt,"",0,0,"R",0);
    $pdf->cell(20,$alt,"SALDO ANT.",0,0,"C",0);
    $pdf->cell(20,$alt,"RESERVADO",0,0,"R",0);
    $pdf->cell(20,$alt,"SALDO ATUAL",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }
    if($nivela == 1){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    if($nivela == 2){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o41_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    $descr = $o52_descr;
    if($nivela == 3){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    $descr = $o53_descr;
    if($nivela == 4){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    $descr = $o54_descr;
    if($nivela == 5){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    $descr = $o55_descr;
    if($nivela == 6){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    $descr = $o56_descr;
    if($nivela == 7){
      $pdf->cell(25,$alt,$o58_elemento,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }
    if($nivela == 8){
      $pdf->cell(25,$alt,$o58_codigo,0,0,"L",0);
      $pdf->cell(60,$alt,$o15_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaoanter += $atual;
      $totorgaoreser += $reservado;
      $totorgaoatual += $atual_menos_reservado;
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $atual_menos_reservado;
    }

}

$pdf->ln(3);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(85,$alt,'TOTAL ',0,0,"L",0,'.');
$pdf->cell(20,$alt,db_formatar($totorgaoanter,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totorgaoreser,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);

}

db_query("commit");

$pdf->Output();