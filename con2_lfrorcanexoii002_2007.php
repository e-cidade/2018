<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("fpdf151/assinatura.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}
//$dt = datas_bimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
//$dt_ini = $dt[0]; // data inicial do período
//$dt_fin = $dt[1]; // data final do período


$classinatura = new cl_assinatura;

  $nivela = substr($nivel,0,1);
  $sele_work = ' o58_instit in ('.str_replace('-',', ',$db_selinstit).')';
  
  pg_exec("begin");
  pg_exec("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");
    
  $xcampos = split("-",$orgaos);
  
  for($i=0;$i < sizeof($xcampos);$i++){
     $where = '';
     $virgula = ''; 
     $xxcampos = split("_",$xcampos[$i]);
     for($ii=0;$ii<sizeof($xxcampos);$ii++){
        if($ii > 0){
          $where .= $virgula.$xxcampos[$ii];
	  $virgula = ', ';
	}
     }
     if($nivela == 1)
       $where .= ",0,0,0,0,0,0,0";
     if($nivela == 2)
       $where .= ",0,0,0,0,0,0";
     if($nivela == 3)
       $where .= ",0,0,0,0,0";
     if($nivela == 4)
       $where .= ",0,0,0,0";
     if($nivela == 5)
       $where .= ",0,0,0";
     if($nivela == 6)
       $where .= ",0,0";
     if($nivela == 7)
       $where .= ",0";
     pg_exec("insert into t values($where)");
  }

pg_exec("commit");

$anousu  = db_getsession("DB_anousu");
//$dataini = $dt_ini; //anousu.'-01-01'; //$dt_ini;
//$datafin = $dt_fin;

$dataini = $perini;
$datafin = $perfin;

$xagrupa = "Órgão";
$grupoini = 1;
$grupofin = 3;

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
    $head7 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head7 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}


$head2 = "DEMONSTRATIVO DA DESPESA POR";
$head3 = "FUNÇÃO/SUBFUNÇÃO/PROGRAMA";
$head4 = "ANEXO (2) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "AGRUPAMENTO : ".$xagrupa;

$result_dot = db_dotacaosaldo(4, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) != '91'",$anousu,$dataini,$datafin,8,0,true,1,false);

$sql = " select
            o58_funcao,
	    o52_descr,
	    o58_subfuncao,
	    o53_descr,
	    sum(dot_ini) as dot_ini_p,
	    sum(suplementado_acumulado) as suplementado_p,
	    sum(reduzido_acumulado) as reduzir_p,
	    sum(empenhado) as empenhado_p,
	    sum(anulado) as anulado_p,
	    sum(empenhado_acumulado) as empenhado_acumulado_p,
	    sum(anulado_acumulado) as anulado_acumulado_p,
	    sum(liquidado) as liquidado_p,
	    sum(liquidado_acumulado) as liquidado_acumulado_p
	 from ($result_dot) as x
	 group by
	     o58_subfuncao,o53_descr,o58_funcao,o52_descr
	 order by 
	    o58_funcao,
	    o58_subfuncao
       ";

$sql_grup = " select
            o58_funcao,
	    o52_descr,
	    sum(dot_ini)             as dot_ini_s,
	    sum(suplementado_acumulado)        as suplementado_s,
	    sum(reduzido_acumulado)            as reduzir,
	    sum(empenhado)           as empenhado_s,
	    sum(anulado)             as anulado_s,
	    sum(empenhado_acumulado) as empenhado_acumulado_s,
	    sum(anulado_acumulado)   as anulado_acumulado_s,
	    sum(liquidado)           as liquidado_s,
	    sum(liquidado_acumulado) as liquidado_acumulado_s
	 from ($result_dot) as x
	 group by
	     o58_funcao, o52_descr
	 order by 
	    o58_funcao
       ";
      
$result_grup = pg_exec($sql_grup);
$result = pg_exec($sql);
//db_criatabela($result_grup);
//db_criatabela($result);exit;


// despesa intraorcamentaria

$result_dot_intra = db_dotacaosaldo(4, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) = '91'",$anousu,$dataini,$datafin,8,0,true,1,false);

$sql = " select
            o58_funcao,
	    o52_descr,
	    o58_subfuncao,
	    o53_descr,
	    sum(dot_ini) as dot_ini_p,
	    sum(suplementado_acumulado) as suplementado_p,
	    sum(reduzido_acumulado) as reduzir_p,
	    sum(empenhado) as empenhado_p,
	    sum(anulado) as anulado_p,
	    sum(empenhado_acumulado) as empenhado_acumulado_p,
	    sum(anulado_acumulado) as anulado_acumulado_p,
	    sum(liquidado) as liquidado_p,
	    sum(liquidado_acumulado) as liquidado_acumulado_p
	 from ($result_dot_intra) as x
	 group by
	     o58_subfuncao,o53_descr,o58_funcao,o52_descr
	 order by 
	    o58_funcao,
	    o58_subfuncao
       ";

$sql_grup = " select
            o58_funcao,
	    o52_descr,
	    sum(dot_ini)             as dot_ini_s,
	    sum(suplementado_acumulado)        as suplementado_s,
	    sum(reduzido_acumulado)            as reduzir,
	    sum(empenhado)           as empenhado_s,
	    sum(anulado)             as anulado_s,
	    sum(empenhado_acumulado) as empenhado_acumulado_s,
	    sum(anulado_acumulado)   as anulado_acumulado_s,
	    sum(liquidado)           as liquidado_s,
	    sum(liquidado_acumulado) as liquidado_acumulado_s
	 from ($result_dot_intra) as x
	 group by
	     o58_funcao, o52_descr
	 order by 
	    o58_funcao
       ";
      
$result_grup_intra = pg_exec($sql_grup);
$result_intra = pg_exec($sql);



$soma1 = 0;   
$soma2 = 0;
$soma3 = 0;
$soma4 = 0;
$soma5 = 0;
$soma6 = 0;
$totalae = 0;
$y =0;
db_fieldsmemory($result,0);
$func_muda = $o58_funcao;
$total_e = 0;
for($y=0;$y<pg_numrows($result_grup);$y++){
   db_fieldsmemory($result_grup,$y);
   $soma_dot[$y]     = $dot_ini_s;
   $soma_dot_at[$y]  = $dot_ini_s + $suplementado_s - $reduzir;//
   $soma_emp[$y]     = $empenhado_s - $anulado_s;
   $soma_liq[$y]     = $liquidado_s;
   $soma_emp_ac[$y]  = $empenhado_acumulado_s - $anulado_acumulado_s;
   $soma_liq_ac[$y]  = $liquidado_acumulado_s;
   $total_e += $liquidado_acumulado_s;  
}

$total_e_intra = 0;
for($y=0;$y<pg_numrows($result_grup_intra);$y++){
   db_fieldsmemory($result_grup_intra,$y);
   $soma_dot_intra[$y]     = $dot_ini_s;
   $soma_dot_at_intra[$y]  = $dot_ini_s + $suplementado_s - $reduzir;//
   $soma_emp_intra[$y]     = $empenhado_s - $anulado_s;
   $soma_liq_intra[$y]     = $liquidado_s;
   $soma_emp_ac_intra[$y]  = $empenhado_acumulado_s - $anulado_acumulado_s;
   $soma_liq_ac_intra[$y]  = $liquidado_acumulado_s;
   $total_e_intra += $liquidado_acumulado_s;  
}


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

$total    = 0;
$troca    = 1;
$alt      = 4;
$qualo    = 0;
$qualu    = 0;
$totproj  = 0;
$totativ  = 0;
$totprojo = 0;
$totativo = 0;
$totproju = 0;
$totativu = 0;
$pagina = 1;

$pdf->addpage();

$pdf->setfont('arial','',4);
$pdf->ln(2);
$pdf->cell(01,$alt,'LRF, Art. 52, Inciso II, alínea"c" - Anexo II',"B",0,"L",0);
$pdf->cell(190,$alt,'R$ Unidades',"B",1,"R",0);
$pdf->setfont('arial','',6);
$pdf->cell(40,$alt,"",0,0,"C",0);
$pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
$pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
$pdf->cell(40,$alt,"Despesas Empenhadas","LR",0,"C",0);
$pdf->cell(50,$alt,"Despesas Liquidadas","LR",0,"C",0);
$pdf->cell(20,$alt,"Saldo A",0,1,"C",0);

$pdf->cell(40,$alt,"Função/SubFunção",0,0,"C",0);
$pdf->cell(20,$alt,"Inical","LR",0,"C",0);
$pdf->cell(20,$alt,"Atualizada","LR",0,"C",0);
$pdf->cell(20,$alt,"No","TLR",0,"C",0);
$pdf->cell(20,$alt,"Até o","TLR",0,"C",0);
$pdf->cell(17,$alt,"No","TLR",0,"C",0);
$pdf->cell(17,$alt,"Até o","TLR",0,"C",0);
$pdf->cell(8,$alt,"%","TLR",0,"C",0);
$pdf->cell(8,$alt,"%","TLR",0,"C",0);
$pdf->cell(20,$alt,"Liquidar",0,1,"C",0);

$pdf->cell(40,$alt,"","BR",0,"C",0);
$pdf->cell(20,$alt,"","BLR",0,"C",0);
$pdf->cell(20,$alt,"(a)","BLR",0,"C",0);
$pdf->cell(20,$alt,"Bimestre(b)","BLR",0,"C",0);
$pdf->cell(20,$alt,"Bimestre(c)","BLR",0,"C",0);
$pdf->cell(17,$alt,"Bimestre(d)","BLR",0,"C",0);
$pdf->cell(17,$alt,"Bimestre(e)","BLR",0,"C",0);
$pdf->cell(8,$alt,"(e/total)","BLR",0,"C",0);
$pdf->cell(8,$alt,"(e/a)","BLR",0,"C",0);
$pdf->cell(20,$alt,"(a-e)","B",1,"C",0);

db_fieldsmemory($result,0);
$funcao = 0;
$soma_dot_ini = 0;
$soma_atualizada = 0;
$soma_nobempenhado = 0;
$soma_aempenhado = 0;
$soma_nobliquidado = 0;
$soma_aliquidado = 0;
$ae = 0;
$soma_totalae =0;
$func_muda = 0; //$o58_funcao;
$subfunc_muda = $o58_subfuncao;   
$y = 0; 
$dot_inis             = 0;
$suplementados        = 0;
$empenhados           = 0;
$anulados             = 0;
$empenhado_acumulados = 0;
$anulado_acumulados   = 0;
$liquidados           = 0;
$liquidado_acumulados = 0;

$pdf->setfont('arial','B',5);
$pos_exceto_intra = $pdf->getY();
 

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $coltotal = $liquidado_acumulado_p;
  $soma_dot_ini      = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada   = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado   = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado   = $soma_aliquidado   + $liquidado_acumulado_p;
}

$pdf->cell(40,$alt,"DESPESAS(EXCETO INTRA-ORÇAM.)(I)","R",0,"L",0);
$pdf->setfont('arial','B',6);
$pdf->cell(20,$alt,db_formatar($soma_dot_ini,'f'),"LRT",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_atualizada,'f'),"LRT",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_nobempenhado,'f'),"LRT",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_aempenhado,'f'),"LRT",0,"R",0);
$pdf->cell(17,$alt,db_formatar($soma_nobliquidado,'f'),"LRT",0,"R",0);
$pdf->cell(17,$alt,db_formatar($soma_aliquidado,'f'),"LRT",0,"R",0);
@$totalae = ($soma_aliquidado/$soma_aliquidado)*100;
$pdf->cell(8,$alt,db_formatar($totalae,'f'),"LRT",0,"R",0);
@$ttotalae = ($soma_dot_ini/$soma_aliquidado)*100;
$pdf->cell(8,$alt," - ","LRT",0,"C",0);
$pdf->cell(20,$alt,db_formatar($soma_atualizada - $soma_aliquidado,'f'),"LT",1,"R",0);

$soma_dot_ini = 0;
$soma_atualizada = 0;
$soma_nobempenhado = 0;
$soma_aempenhado = 0;
$soma_nobliquidado = 0;
$soma_aliquidado = 0;


for($i=0;$i<pg_numrows($result);$i++){
  $ae = 0;
  $atotal =0;
  db_fieldsmemory($result,$i);
  if($pdf->gety() > $pdf->h-40){
     $pdf->cell(190,$alt,'Continua Próxima Página',"T",1,"L",0); 
     $pdf->cell(190,$alt,'',"T",1,"L",0);
     
     $pdf->addpage();
     $pdf->ln(2);
     $pdf->cell(190,$alt,'Continuação da Página Anterior',"T",1,"L",0);
     $pdf->cell(01,$alt,'LRF, Art. 52, Inciso II, alínea"c" - Anexo II',"B",0,"L",0);
     $pdf->cell(190,$alt,'R$ Unidade',"B",1,"R",0);
    
     $pdf->setfont('arial','',6);
     $pdf->cell(40,$alt,"",0,0,"C",0);
     $pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
     $pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
     $pdf->cell(40,$alt,"Despesas Empenhadas","LR",0,"C",0);
     $pdf->cell(50,$alt,"Despesas Liquidadas","LR",0,"C",0);
     $pdf->cell(20,$alt,"Saldo A",0,1,"C",0);

     $pdf->cell(40,$alt,"Função/SubFunção",0,0,"C",0);
     $pdf->cell(20,$alt,"Inical","LR",0,"C",0);
     $pdf->cell(20,$alt,"Atualizada","LR",0,"C",0);
     $pdf->cell(20,$alt,"No","TLR",0,"C",0);
     $pdf->cell(20,$alt,"Até o","TLR",0,"C",0);
     $pdf->cell(17,$alt,"No","TLR",0,"C",0);
     $pdf->cell(17,$alt,"Até o","TLR",0,"C",0);
     $pdf->cell(8,$alt,"%","TLR",0,"C",0);
     $pdf->cell(8,$alt,"%","TLR",0,"C",0);
     $pdf->cell(20,$alt,"Liquidar",0,1,"C",0);

     $pdf->cell(40,$alt,"","BR",0,"C",0);
     $pdf->cell(20,$alt,"","BLR",0,"C",0);
     $pdf->cell(20,$alt,"(a)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"Bimestre(b)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"Bimestre(c)","BLR",0,"C",0);
     $pdf->cell(17,$alt,"Bimestre(d)","BLR",0,"C",0);
     $pdf->cell(17,$alt,"Bimestre(e)","BLR",0,"C",0);
     $pdf->cell(8,$alt,"(e/total)","BLR",0,"C",0);
     $pdf->cell(8,$alt,"(e/a)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"(a-e)","B",1,"C",0);
  
  }

  if ($o58_funcao != $func_muda) {
   
    $pdf->setfont('arial','B',5);
    $pdf->cell(40,$alt,$o52_descr,"R",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot_at[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_emp[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_emp_ac[$y],'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($soma_liq[$y],'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($soma_liq_ac[$y],'f'),"LR",0,"R",0); 
    @$etotal = ($soma_liq_ac[$y]/$total_e)*100;
    $pdf->cell(8,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    @$ae = ($soma_liq_ac[$y]/$soma_dot_at[$y])*100;
    $func_muda = $o58_funcao;
    $pdf->cell(8,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot_at[$y]-$soma_liq_ac[$y],'f'),0,1,"R",0);
    $y++;

  }
    $pdf->setfont('arial','',5);
    $pdf->cell(40,$alt,"   ".substr($o53_descr,0,32),"R",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($dot_ini_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($dot_ini_p + $suplementado_p - $reduzir_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($empenhado_p - $anulado_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($empenhado_acumulado_p - $anulado_acumulado_p,'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($liquidado_p,'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($liquidado_acumulado_p,'f'),"LR",0,"R",0); 
    @$etotal = ($liquidado_acumulado_p/$total_e)*100;
    $pdf->cell(8,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    if (($dot_ini_p + $suplementado_p - $reduzir_p) != 0) {
      $ae = ($liquidado_acumulado_p/($dot_ini_p + $suplementado_p - $reduzir_p))*100;
    }else{
      $ae =0;
    }
    $pdf->cell(8,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar(($dot_ini_p + $suplementado_p - $reduzir_p)-$liquidado_acumulado_p,'f'),0,1,"R",0);
  
  $coltotal = $liquidado_acumulado_p;
  $soma_dot_ini      = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada   = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado   = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado   = $soma_aliquidado   + $liquidado_acumulado_p;
}

// intra orcamentaria
$xsoma_dot_ini = 0;
$xsoma_atualizada = 0;
$xsoma_nobempenhado = 0;
$xsoma_aempenhado = 0;
$xsoma_nobliquidado = 0;
$xsoma_aliquidado = 0;

$pdf->setfont('arial','B',5);

for($i=0;$i<pg_numrows($result_intra);$i++){
  db_fieldsmemory($result_intra,$i);
  $coltotal = $liquidado_acumulado_p;
  $xsoma_dot_ini      = $xsoma_dot_ini      + $dot_ini_p;
  $xsoma_atualizada   = $xsoma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $xsoma_nobempenhado = $xsoma_nobempenhado + ($empenhado_p - $anulado_p);
  $xsoma_aempenhado   = $xsoma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $xsoma_nobliquidado = $xsoma_nobliquidado + $liquidado_p;
  $xsoma_aliquidado   = $xsoma_aliquidado   + $liquidado_acumulado_p;
}

$pdf->cell(40,$alt,"DESPESAS (INTRA-ORÇAMENTÁRIA)(I)","R",0,"L",0);
$pdf->setfont('arial','B',6);
$pdf->cell(20,$alt,db_formatar($xsoma_dot_ini,'f'),"LR",0,"R",0);
$pdf->cell(20,$alt,db_formatar($xsoma_atualizada,'f'),"LR",0,"R",0);
$pdf->cell(20,$alt,db_formatar($xsoma_nobempenhado,'f'),"LR",0,"R",0);
$pdf->cell(20,$alt,db_formatar($xsoma_aempenhado,'f'),"LR",0,"R",0);
$pdf->cell(17,$alt,db_formatar($xsoma_nobliquidado,'f'),"LR",0,"R",0);
$pdf->cell(17,$alt,db_formatar($xsoma_aliquidado,'f'),"LR",0,"R",0);
@$totalae = ($xsoma_aliquidado/$xsoma_aliquidado)*100;
$pdf->cell(8,$alt,db_formatar($totalae,'f'),"LR",0,"R",0);
@$ttotalae = ($xsoma_dot_ini/$xsoma_aliquidado)*100;
$pdf->cell(8,$alt," - ","LR",0,"C",0);
$pdf->cell(20,$alt,db_formatar($xsoma_atualizada - $xsoma_aliquidado,'f'),"L",1,"R",0);



$y = 0; 
for($i=0;$i<pg_numrows($result_intra);$i++){
  $ae = 0;
  $atotal =0;
  db_fieldsmemory($result_intra,$i);
  if($pdf->gety() > $pdf->h-40){
     $pdf->cell(190,$alt,'Continua Próxima Página ',"T",1,"L",0); 
     $pdf->cell(190,$alt,'',"T",1,"L",0);
     
     $pdf->addpage();
     $pdf->ln(2);
     $pdf->cell(190,$alt,'Continuação da Página Anterior',"T",1,"L",0);
     $pdf->cell(01,$alt,'LRF, Art. 52, Inciso II, alínea"c" - Anexo II',"B",0,"L",0);
     $pdf->cell(190,$alt,'R$ Unidade',"B",1,"R",0);
    
     $pdf->setfont('arial','',6);
     $pdf->cell(40,$alt,"",0,0,"C",0);
     $pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
     $pdf->cell(20,$alt,"Dotação","LR",0,"C",0);
     $pdf->cell(40,$alt,"Despesas Empenhadas","LR",0,"C",0);
     $pdf->cell(50,$alt,"Despesas Liquidadas","LR",0,"C",0);
     $pdf->cell(20,$alt,"Saldo A",0,1,"C",0);

     $pdf->cell(40,$alt,"Função/SubFunção",0,0,"C",0);
     $pdf->cell(20,$alt,"Inical","LR",0,"C",0);
     $pdf->cell(20,$alt,"Atualizada","LR",0,"C",0);
     $pdf->cell(20,$alt,"No","TLR",0,"C",0);
     $pdf->cell(20,$alt,"Até o","TLR",0,"C",0);
     $pdf->cell(17,$alt,"No","TLR",0,"C",0);
     $pdf->cell(17,$alt,"Até o","TLR",0,"C",0);
     $pdf->cell(8,$alt,"%","TLR",0,"C",0);
     $pdf->cell(8,$alt,"%","TLR",0,"C",0);
     $pdf->cell(20,$alt,"Liquidar",0,1,"C",0);

     $pdf->cell(40,$alt,"","BR",0,"C",0);
     $pdf->cell(20,$alt,"","BLR",0,"C",0);
     $pdf->cell(20,$alt,"(a)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"Bimestre(b)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"Bimestre(c)","BLR",0,"C",0);
     $pdf->cell(17,$alt,"Bimestre(d)","BLR",0,"C",0);
     $pdf->cell(17,$alt,"Bimestre(e)","BLR",0,"C",0);
     $pdf->cell(8,$alt,"(e/total)","BLR",0,"C",0);
     $pdf->cell(8,$alt,"(e/a)","BLR",0,"C",0);
     $pdf->cell(20,$alt,"(a-e)","B",1,"C",0);
  
  }

  if ($o58_funcao != $func_muda) {
   
    $pdf->setfont('arial','B',5);
    $pdf->cell(40,$alt,$o52_descr,"R",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot_at_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_emp_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_emp_ac_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($soma_liq_intra[$y],'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($soma_liq_ac_intra[$y],'f'),"LR",0,"R",0); 
    @$etotal = ($soma_liq_ac_intra[$y]/$total_e_intra)*100;
    $pdf->cell(8,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    @$ae = ($soma_liq_ac_intra[$y]/$soma_dot_at_intra[$y])*100;
    $func_muda = $o58_funcao;
    $pdf->cell(8,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($soma_dot_at_intra[$y]-$soma_liq_ac_intra[$y],'f'),0,1,"R",0);
    $y++;
  
  }
  
    $pdf->setfont('arial','',5);
    $pdf->cell(40,$alt,"   ".substr($o53_descr,0,32),"R",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($dot_ini_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($dot_ini_p + $suplementado_p - $reduzir_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($empenhado_p - $anulado_p,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($empenhado_acumulado_p - $anulado_acumulado_p,'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($liquidado_p,'f'),"LR",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($liquidado_acumulado_p,'f'),"LR",0,"R",0); 
    @$etotal = ($liquidado_acumulado_p/$total_e)*100;
    $pdf->cell(8,$alt,db_formatar($etotal,'f'),"LR",0,"R",0);
    if (($dot_ini_p + $suplementado_p) != 0) {
        $ae = ($liquidado_acumulado_p/($dot_ini_p + $suplementado_p))*100;
    }else{
        $ae =0;
    }
    $pdf->cell(8,$alt,db_formatar($ae,'f'),"LR",0,"R",0);
    $pdf->cell(20,$alt,db_formatar(($dot_ini_p + $suplementado_p)-$liquidado_acumulado_p,'f'),0,1,"R",0);
  
  $coltotal = $liquidado_acumulado_p;
  $soma_dot_ini      = $soma_dot_ini      + $dot_ini_p;
  $soma_atualizada   = $soma_atualizada   + (($dot_ini_p + $suplementado_p) - $reduzir_p);
  $soma_nobempenhado = $soma_nobempenhado + ($empenhado_p - $anulado_p);
  $soma_aempenhado   = $soma_aempenhado   + ($empenhado_acumulado_p - $anulado_acumulado_p);
  $soma_nobliquidado = $soma_nobliquidado + $liquidado_p;
  $soma_aliquidado   = $soma_aliquidado   + $liquidado_acumulado_p;

}


$pdf->setfont('arial','B',6);
$pdf->cell(40,$alt,"Total","RTB",0,"L",0);
$pdf->cell(20,$alt,db_formatar($soma_dot_ini,'f'),"LRTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_atualizada,'f'),"LRTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_nobempenhado,'f'),"LRTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($soma_aempenhado,'f'),"LRTB",0,"R",0);
$pdf->cell(17,$alt,db_formatar($soma_nobliquidado,'f'),"LRTB",0,"R",0);
$pdf->cell(17,$alt,db_formatar($soma_aliquidado,'f'),"LRTB",0,"R",0);
@$totalae = ($soma_aliquidado/$soma_aliquidado)*100;
$pdf->cell(8,$alt,db_formatar($totalae,'f'),"LRTB",0,"R",0);
@$ttotalae = ($soma_dot_ini/$soma_aliquidado)*100;
$pdf->cell(8,$alt," - ","LRTB",0,"C",0);
$pdf->cell(20,$alt,db_formatar($soma_atualizada - $soma_aliquidado,'f'),"TB",1,"R",0);
$pdf->cell(190,$alt,'Fonte: Contabilidade',"",1,"L",0);


$pdf->ln(10);

// assinaturas
assinaturas(&$pdf,&$classinatura,'LRF');



$pdf->Output();

?>