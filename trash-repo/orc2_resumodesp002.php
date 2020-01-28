<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("libs/db_liborcamento.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinst ;
        $xvirg = ', ';
}

if($tipo_balanco==2){
  $xtipo = "-EMPENHADO";
}else if($tipo_balanco==3){
  $xtipo = "-LIQUIDADO";
}else{
  $xtipo = "-PAGO";
}  

$head3 = "RESUMO DA DESPESA - $xtipo";
$head4 = "EXERCÍCIO    : ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head7 = "PERÍODO      : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d');

// funcao para gerar work

$tipo_agrupa = substr($nivel,0,1) ;
$nivelele = substr($nivelele,0,1);

$tipo_rel = 2;

if($nivelele=='3'||$nivelele=='0'){
  $ccampo = "o58_funcao";
  $qcampo = "o58_funcao";
  $cabec = "Função";
  $ccampod = " o52_descr ";
  $relac  = " inner join orcfuncao on orcdotacao.o58_funcao = orcfuncao.o52_funcao ";
  if($nivelele=='0')
    $tipo_rel =1;
  $nivelele='3';
}else if($nivelele=='4'){  
  $ccampo = "o58_subfuncao";
  $qcampo = "o58_subfuncao";
  $cabec = "Sub-Função";
  $ccampod = " o53_descr ";
  $relac = " inner join orcsubfuncao on orcdotacao.o58_subfuncao = orcsubfuncao.o53_subfuncao ";
}else if($nivelele=='5'){  
  $ccampo = "o58_programa";
  $qcampo = "o58_programa";
  $cabec = "Programa";
  $ccampod = " o54_descr ";
  $relac = "  inner join orcprograma on orcdotacao.o58_programa = orcprograma.o54_programa and orcprograma.o54_anousu = ".db_getsession("DB_anousu");
}else if($nivelele=='6'){  
  $ccampo = "o58_projativ";
  $qcampo = "o58_projativ";
  $cabec = "Proj/Ativ";
  $ccampod = " o55_descr ";
  $relac = " inner join orcprojativ on orcdotacao.o58_projativ = orcprojativ.o55_projativ and orcprojativ.o55_anousu = ".db_getsession("DB_anousu");
}else if($nivelele=='7'){  
  $ccampo = "o56_elemento";
  $qcampo = "o58_elemento";
  $cabec = "Elemento";
  $ccampod = " o56_descr ";
  $relac = "  inner join orcelemento on orcdotacao.o58_codele = orcelemento.o56_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu  ";
}else if($nivelele=='8'){  
  $ccampo = "o58_codigo";
  $qcampo = "o58_codigo";
  $cabec = "Recurso";
  $ccampod = " o15_descr ";
  $relac = "  inner join orctiporec on orcdotacao.o58_codigo = orctiporec.o15_codigo  ";
}
if($tipo_agrupa==1){
  $sql = "create temp table work as 
          select distinct o58_orgao as orgao, 0 as unidade, $ccampo as campo,$ccampod as descr,
	         0.00 as vlr1,
	         0.00 as vlr2,
	         0.00 as vlr3,
	         0.00 as vlr4
  	  from orcdotacao
	       inner join orcorgao on orcdotacao.o58_orgao = orcorgao.o40_orgao and orcorgao.o40_anousu = ".db_getsession("DB_anousu")." 
	       $relac
	  where o58_anousu = ".db_getsession("DB_anousu");
}else{
  $sql = "create temp table work as 
          select distinct o58_orgao as orgao, o58_unidade as unidade, $ccampo as campo,$ccampod as descr, 
	         0.00 as vlr1,
	         0.00 as vlr2,
	         0.00 as vlr3,
	         0.00 as vlr4
  	  from orcdotacao 
	       inner join orcunidade on  orcdotacao.o58_orgao = orcunidade.o41_orgao and orcdotacao.o58_unidade = orcunidade.o41_unidade and orcunidade.o41_anousu = orcdotacao.o58_anousu 
	       $relac
	  where o58_anousu = ".db_getsession("DB_anousu");
}

$result = pg_exec($sql);

//$result = pg_exec("select * from work");
//db_criatabela($result);exit;


$xcampos = split("-",$orgaos);

$where = '';
$virgula = '';
$where1 = '';
if($tipo_agrupa == '1')
   $where = " o58_orgao in (";
else{
   $where1 = " o58_orgao in (";
   $where = " o58_unidade in (";
}
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   if($tipo_agrupa == 1){
     $where .= $virgula.$xxcampos[1];
   }else{
     $where1 .= $virgula.$xxcampos[1];
     $where .= $virgula.$xxcampos[2];
   }
   $virgula = ', ';
}

  // segundo filtro funcao-subfuncao-prog ..

$xcampos = split("-",$orgaosele);

$virgula = '';

if($where1!="")
  $where = $where1 .") and  $where ";

if($tipo_rel=='2'){

 $where .= ") and $ccampo in (";
 for($i=0;$i < sizeof($xcampos);$i++){
     $xxcampos = split("_",$xcampos[$i]);
     for($ii=0;$ii<sizeof($xxcampos);$ii++){
	if($ii > 0){
	   $where .= $virgula."'".$xxcampos[$ii]."'";
	   $virgula = ', ';
	}
     }
  }

}

$anousu  = db_getsession("DB_anousu");

$where .= ") and o58_instit in (".str_replace('-',', ',$db_selinstit).")";

  
  $result_rec = db_dotacaosaldo(7,2,$opcao,true,$where,$anousu,$perini,$perfin,2,$nivelele,null,$tipo_balanco,false);
  pg_exec("rollback");
//  db_criatabela($result_rec);
  $valor = 0;

  for($i=0;$i<pg_numrows($result_rec);$i++){
    db_fieldsmemory($result_rec,$i);
    if($tipo_balanco==1){
      $valor = $dot_ini;
    }else if($tipo_balanco == 2){
      $valor = $empenhado-$anulado;
    }else if($tipo_balanco == 3){
      $valor = $liquidado;
    }else {
      $valor = $pago;
    }
     
    if($tipo_agrupa==1){
      $sql = "update work set vlr1 = vlr1+$dot_ini, vlr2 = vlr2+$suplementado_acumulado, vlr3 = vlr3+$reduzido_acumulado, vlr4 = vlr4+$valor where work.campo = '".$$qcampo."' and orgao = ".$o58_orgao ;
    }else{
      $sql = "update work set vlr1 = vlr1+$dot_ini, vlr2 = vlr2+$suplementado_acumulado, vlr3 = vlr3+$reduzido_acumulado, vlr4 = vlr4+$valor where work.campo = '".$$qcampo."' and orgao = ".$o58_orgao." and unidade = ".$o58_unidade;
    }
    //echo $sql;
    $result = pg_exec($sql);
  }  
  // pesquisa as dotacoes

// $res = pg_query("select * from work");
 
//    db_criatabela($res) ;exit;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$qorgao = "";
$qunidade = "";

if($tipo_rel==2){
   if ($nivelele == 7 or $tipoagrupar == 1) {
     $sql = "select campo, descr,
	          sum(vlr1) as vlr1,
	          sum(vlr2 ) as vlr2,
	          sum(vlr3) as vlr3,
	          sum(vlr4) as vlr4
		from work 
		group by campo, descr
        order by campo";
     $result = pg_exec($sql);
   } else {
     $sql = "select * from work order by orgao,unidade,campo";
     $result = pg_exec($sql);
     $qorgao = pg_result($result,0,'orgao');
     $qunidade = pg_result($result,0,'unidade');
   }
}else{
   $sql = "select orgao,unidade,
	          sum(vlr1) as vlr1,
	          sum(vlr2 ) as vlr2,
	          sum(vlr3) as vlr3,
	          sum(vlr4) as vlr4
           from work 
	   group by orgao,unidade
	   order by orgao,unidade";
    $result = pg_exec($sql);
    $qorgao = pg_result($result,0,'orgao');
    $qunidade = pg_result($result,0,'unidade');
}


//db_criatabela($result);exit;
// aqui

$pagina = 1;

$qualou = "$qorgao$qunidade";

$totoper  = 0;

$ttvlr1 = 0;
$ttvlr2 = 0;
$ttvlr3 = 0;
$ttvlr4 = 0;

$tvlr1 = 0;
$tvlr2 = 0;
$tvlr3 = 0;
$tvlr4 = 0;
if($tipo_balanco==1){
  $emp_ = 'ORÇADO';
}else if($tipo_balanco == 2){
  $emp_ = 'EMPENHADO';
}else if($tipo_balanco == 3){
  $emp_ = 'LIQUIDADO';
}else {
  $emp_ = 'PAGO';
}

$pre = 1;

if($tipo_rel==2){

  $troca_secretaria = false;
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    if($vlr1+$vlr2+$vlr3+$vlr4==0)
      continue;
    if (!($nivelele == 7 or $tipoagrupar == 1)) {
      if (("$qualou" != "$orgao$unidade") ){
	 $troca_secretaria = true;
	 $qualou = "$orgao$unidade";
         $tval_emp = $tvlr1+$tvlr2-$tvlr3;
         if($tval_emp > 0){
           $tperc = $tvlr4 * 100 / $tval_emp;
	 }else{
	   $tperc = 0;
	 }
         if($pre == 1)
           $pre = 0;
         else
           $pre = 1;
	 $pdf->setfont('arial','',6);
	 $pdf->cell(75,$alt,"Total ",0,0,"L",$pre);
	 $pdf->cell(20,$alt,db_formatar($tvlr1,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tvlr2,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tvlr3,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tval_emp,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tvlr4,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tperc,'f'),0,0,"R",$pre);
	 $pdf->cell(20,$alt,db_formatar($tval_emp - $tvlr4,'f'),0,1,"R",$pre);
	 $tvlr1 = 0;
	 $tvlr2 = 0;
	 $tvlr3 = 0;
	 $tvlr4 = 0;
      }
    }
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $troca_secretaria = true;
    }
    if($troca_secretaria == true) {
      $troca_secretaria = false;

//      if ( $nive$lele != 7 $tipo_agrupa <> 3) {
      if ($tipoagrupar == 2) {
        $pdf->setfont('arial','b',7);
        $sql  = "select o40_descr 
  	  	 from orcorgao 
		 where o40_anousu = ".db_getsession("DB_anousu")." and
		       o40_orgao = ".$orgao;
        $resorg = pg_exec($sql);      
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
	  $resorg = pg_exec($sql);      
	  db_fieldsmemory($resorg,0);
	  $pdf->cell(10,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao'),0,0,"L",0);
	  $pdf->cell(50,$alt,$o41_descr,0,1,"L",0);
	  $pdf->cell(0,0.5,'',"TB",1,"C",0);
        }

      }

      $xx= 20;
      $pdf->setfont('arial','B',8);
      $pdf->cell($xx,$alt,$cabec,1,0,"R",1);
      $pdf->cell(55,$alt,"Descrição",1,0,"L",1);
      $pdf->cell(20,$alt,"DOT INI",1,0,"R",1);
      $pdf->cell(20,$alt,"SUPLEM",1,0,"R",1);
      $pdf->cell(20,$alt,"REDUZIDO",1,0,"R",1);
      $pdf->cell(20,$alt,"TOTAL",1,0,"R",1);
      $pdf->cell(20,$alt,$emp_,1,0,"R",1);
      $pdf->cell(20,$alt,"PERC",1,0,"R",1);
      $pdf->cell(20,$alt,"SALDO",1,1,"R",1);
      $pre = 1;
    }
    
    if($pre == 1)
      $pre = 0;
    else
      $pre = 1;
      
    $pdf->setfont('arial','',6);
    $val_emp = $vlr1+$vlr2-$vlr3;
    if ($val_emp > 0){
      $perc    = $vlr4 * 100 / $val_emp;
    } else {
      $perc =0;
    }  
    $pdf->cell(20,$alt,$campo,0,0,"R",$pre);
    $pdf->cell(55,$alt,substr($descr,0,40),0,0,"L",$pre);
    $pdf->cell(20,$alt,db_formatar($vlr1,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($vlr2,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($vlr3,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($val_emp,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($vlr4,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($perc,'f'),0,0,"R",$pre);
    $pdf->cell(20,$alt,db_formatar($val_emp - $vlr4,'f'),0,1,"R",$pre);

    $tvlr1 += $vlr1;
    $tvlr2 += $vlr2;
    $tvlr3 += $vlr3;
    $tvlr4 += $vlr4;

    $ttvlr1 += $vlr1;
    $ttvlr2 += $vlr2;
    $ttvlr3 += $vlr3;
    $ttvlr4 += $vlr4;

    
  }
   $tval_emp = $tvlr1+$tvlr2-$tvlr3;
   if($tval_emp > 0){
     $tperc = $tvlr4 * 100 / $tval_emp;
   }else{
     $tperc = 0;
   }
   if($pre == 1)
     $pre = 0;
   else
     $pre = 1;
   $pdf->setfont('arial','',6);
   $pdf->cell(75,$alt,"Total ",0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($tvlr1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tvlr2,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tvlr3,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tval_emp,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tvlr4,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tperc,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($tval_emp - $tvlr4,'f'),0,1,"R",$pre);

}else{

  $troca_secretaria = false;
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);

    if($vlr1+$vlr2+$vlr3+$vlr4 == 0 ){
      continue;
    }


    if (("$qualou" != "$orgao$unidade") ){
       $troca_secretaria = true;
       $qualou = "$orgao$unidade";
       $pdf->setfont('arial','',6);
    }

    
    if($pdf->gety()>$pdf->h-30 || $pagina ==1){
      $pagina = 0;
      $pdf->addpage("L");
      $pdf->setfont('arial','B',8);
      $pdf->cell(20,$alt,"Orgão".($unidade!=0?"/Unidade":""),1,0,"L",1);
      $pdf->cell(55,$alt,"Descrição",1,0,"L",1);
      $pdf->cell(20,$alt,"DOT INI",1,0,"R",1);
      $pdf->cell(20,$alt,"SUPLEM",1,0,"R",1);
      $pdf->cell(20,$alt,"REDUZ",1,0,"R",1);
      $pdf->cell(20,$alt,"TOTAL",1,0,"R",1);
      $pdf->cell(20,$alt,$emp_,1,0,"R",1);
      $pdf->cell(20,$alt,"PERC",1,0,"R",1);
      $pdf->cell(20,$alt,"SALDO",1,1,"R",1);
      $pre = 1;
   }
   
   $pdf->setfont('arial','b',6);
   if($pre == 1)
     $pre = 0;
   else
     $pre = 1;
   if($tipo_agrupa=='1'){
     $sql  = "select o40_descr 
		 from orcorgao 
		 where o40_anousu = ".db_getsession("DB_anousu")." and
		       o40_orgao = ".$orgao;
     $resorg = pg_exec($sql);      
     db_fieldsmemory($resorg,0);
     $pdf->cell(20,$alt,db_formatar($orgao,'orgao'),0,0,"L",$pre);
     $pdf->cell(55,$alt,$o40_descr,0,0,"L",$pre);
   }
   if($tipo_agrupa=='2'){
     $sql  = "select o41_descr 
		   from orcunidade 
		   where o41_anousu = ".db_getsession("DB_anousu")." and
			 o41_orgao = ".$orgao." and o41_unidade = ".$unidade;
     $resorg = pg_exec($sql);      
     db_fieldsmemory($resorg,0);
     $pdf->cell(20,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao'),0,0,"L",$pre);
     $pdf->cell(55,$alt,$o41_descr,0,0,"L",$pre);
      
   }
   $val_emp = $vlr1+$vlr2-$vlr3;
   if($val_emp > 0){
     $perc    = $vlr4 * 100 / $val_emp;
   }else{
     $perc = 0;
   }
   $pdf->setfont('arial','',6);
   $pdf->cell(20,$alt,db_formatar($vlr1,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($vlr2,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($vlr3,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($val_emp,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($vlr4,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($perc,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($val_emp - $vlr4,'f'),0,1,"R",$pre);


      $ttvlr1 += $vlr1;
      $ttvlr2 += $vlr2;
      $ttvlr3 += $vlr3;
      $ttvlr4 += $vlr4;


  }

}
$ttval_emp = $ttvlr1+$ttvlr2-$ttvlr3;
if($ttval_emp > 0){
  $ttperc    = $ttvlr4 * 100 / $ttval_emp;
}else{
  $ttperc = 0;
}
if($pre == 1)
  $pre = 0;
else
  $pre = 1;
$pdf->setfont('arial','',6);
$pdf->cell(75,$alt,"Total Geral ",1,0,"L",$pre);
$pdf->cell(20,$alt,db_formatar($ttvlr1,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttvlr2,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttvlr3,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttval_emp,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttvlr4,'f'),1,0,"R",$pre);
$pdf->cell(20,$alt,db_formatar($ttperc,'f'),1,0,"R",0);
$pdf->cell(20,$alt,db_formatar($ttval_emp - $ttvlr4,'f'),1,1,"R",$pre);


//include("fpdf151/geraarquivo.php");
$pdf->Output();
?>