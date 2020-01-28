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


include("fpdf151/pdf.php");
include("libs/db_sql.php");

include("fpdf151/assinatura.php");
$classinatura = new cl_assinatura;

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);



$head1 = "DEMONSTRATIVO DA DESPESA/SUBELEMENTO";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

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
  }else{
       $descr_inst .= $xvirg.$nomeinst; 
  }

  $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
//exit;

//echo $orgaos."<br>";
// 'nivel :'.substr($nivel,1,1)."<br>";
if (substr($nivel,1,1) == 'A'){
  $nivela = substr($nivel,0,1);
  $sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  if ($nivela >= 1) {
    $sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao) ";
  }
  if ($nivela >= 2) {
    $sele_work .= " and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
	}
  if ($nivela >= 3) {
    $sele_work .= " and exists (select 1 from t where t.o58_funcao = w.o58_funcao) ";
  }
  if ($nivela >= 4) {
    $sele_work .= " and exists (select 1 from t where t.o58_subfuncao = w.o58_subfuncao) ";
  }
  if ($nivela >= 5) {
    $sele_work .= " and exists (select 1 from t where t.o58_programa = w.o58_programa) ";
  }
  if ($nivela >= 6) {
    $sele_work .= " and exists (select 1 from t where t.o58_projativ = w.o58_projativ) ";
  }
  if ($nivela >= 7) {
    $sele_work .= " and exists (select 1 from t where t.o58_elemento = e.o56_elemento) ";
  }
  if ($nivela >= 8) {
    $sele_work .= " and exists (select 1 from t where t.o58_codigo = w.o58_codigo) ";
  }

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

//db_criatabela(pg_exec("select * from t"));
$anousu  = db_getsession("DB_anousu");
/*
$dataini = date("Y-m-d",db_getsession("DB_datausu"));
$datafin = date("Y-m-d",db_getsession("DB_datausu"));
*/
$dataini = $DBtxt21_ano.'-'.$DBtxt21_mes.'-'.$DBtxt21_dia;
$datafin = $DBtxt22_ano.'-'.$DBtxt22_mes.'-'.$DBtxt22_dia;

$head5 = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');

$result = db_dotacaosaldo($nivela,1,2,true,$sele_work,$anousu,$dataini,$datafin);
//db_criatabela($result);exit;
// funcao para gerar work
//db_criatabela(pg_exec("select * from work w inner join temporario t on $sele_work "));exit;



pg_exec("commit");

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

$totorgaodotini= 0;
$totorgaoempen = 0;
$totorgaoliqui = 0;
$totorgaopago  = 0;
$totorgaoatual = 0;

$totunidadotini= 0;
$totunidaempen = 0;
$totunidaliqui = 0;
$totunidapago  = 0;
$totunidaatual = 0;
$totunidaanter = 0;
$totorgaoanter = 0;
$totorgaoreser = 0;
$totunidareser = 0;

$pagina        = 1;
$dotini = 0;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);
  $dotini = $dot_ini;
/*
  if(empty($o58_funcao)){
    continue;
  }

  if(!empty($qorgao)){
    if($tipo_agrupa==2){
      if($orgao != $qorgao){
        continue;
      }
    }
  }
  if(!empty($qunidade)){
    if($tipo_agrupa==3){
      if($orgao != $qorgao){
        continue;
      }
      if($unidade != $qunidade){
        continue;
      }
    }
  }*/
  if($xorgao.$xunidade != $o58_orgao.$o58_unidade && $quebra_unidade == 'S' && $pagina != 1 && $totunidaanter != 0){
    $pdf->setfont('arial','b',7);
    $pagina = 1;
    $pdf->ln(3);
    $pdf->cell(50,$alt,'',0,0,"L",0);
    $pdf->cell(85,$alt,'TOTAL DA UNIDADE',0,0,"L",0,'.');
    $pdf->cell(20,$alt,db_formatar($totunidadotini,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidaempen,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidaliqui,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidapago,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
    $pdf->setfont('arial','',7);
    $totunidadotini = 0;
    $totunidaempen  = 0;
    $totunidaliqui  = 0;
    $totunidapago   = 0;
    $totunidaatual  = 0;
  }
  
  if($xorgao != $o58_orgao && $quebra_orgao =='S' ){
    $pdf->setfont('arial','b',7);
    $pagina = 1;
    $pdf->ln(3);
    $pdf->cell(50,$alt,'',0,0,"L",0);
    $pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');
    $pdf->cell(20,$alt,db_formatar($totorgaodotini,'f'),0,1,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaoempen,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaoliqui,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaopago,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totorgaoatual,'f'),0,1,"R",0);
    $pdf->setfont('arial','',7);
    $totorgaodotini = 0;
    $totorgaoempen  = 0;
    $totorgaoliqui  = 0;
    $totorgaopago   = 0;
    $totorgaoatual  = 0;
  }
  if($pdf->gety()>$pdf->h-30 || $pagina == 1){
    $pagina = 0;
    $qualou = $o58_orgao.$o58_unidade;
    $pdf->addpage('L');
    $pdf->setfont('arial','b',7);
    $pdf->ln(2);
    $pdf->cell(25,$alt,"DOTAÇÃO",0,0,"L",0);
    $pdf->cell(100,$alt,"RECURSO",0,0,"C",0);
    $pdf->cell(10,$alt,"REDUZ",0,0,"R",0);
    $pdf->cell(20,$alt,"DOT.INICIAL",0,0,"C",0);
    $pdf->cell(20,$alt,"EMPENHADO",0,0,"R",0);
    $pdf->cell(20,$alt,"LIQUIDADO",0,0,"R",0);
    $pdf->cell(20,$alt,"PAGO",0,0,"R",0);
    $pdf->cell(20,$alt,"ATUAL",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }

  if($xorgao != $o58_orgao && $o58_orgao != 0){
      $xorgao = $o58_orgao;
    if($nivela == 1){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);
      $totorgaodotini+= $dotini;
      $totorgaoempen += $empenhado_acumulado - $anulado_acumulado;
      $totorgaoliqui += $liquidado_acumulado;
      $totorgaopago  += $pago_acumulado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidadotini+= $dotini;
      $totunidaempen += $empenhado_acumulado - $anulado_acumulado;
      $totunidaliqui += $liquidado_acumulado;
      $totunidapago  += $pago_acumulado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,1,"L",0);
      $xunidade = 0;
    }
  }
  if("$o58_orgao.$o58_unidade" != "$xorgao.$xunidade" && $o58_unidade != 0){
    $xunidade = "$o58_unidade";
    if($nivela == 2){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o41_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);

      $totorgaoanter += $dotini;
      $totorgaoempen += $empenhado_acumulado - $anulado_acumulado;
      $totorgaoliqui += $liquidado_acumulado;
      $totorgaopago  += $pago_acumulado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $dotini;
      $totunidaempen += $empenhado_acumulado - $anulado_acumulado;
      $totunidaliqui += $liquidado_acumulado;
      $totunidapago  += $pago_acumulado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o41_descr,0,1,"L",0);
    }
  }
  
  if("$o58_orgao.$o58_unidade.$o58_funcao" != "$xfuncao" && $o58_funcao != 0 ){
    $xfuncao = "$o58_orgao.$o58_unidade.$o58_funcao";
    $descr = $o52_descr;
    if($nivela == 3){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);
      $totorgaoanter += $dotini;
      $totorgaoempen += $empenhado_acumulado - $anulado_acumulado;
      $totorgaoliqui += $liquidado_acumulado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $dotini;
      $totunidaempen += $empenhado_acumulado - $anulado_acumulado;
      $totunidaliqui += $liquidado_acumulado;
      $totunidapago  += $pago_acumulado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao" != "$xsubfuncao" && $o58_subfuncao != 0){
    $xsubfuncao = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao";
    $descr = $o53_descr;
    if($nivela == 4){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
      $totorgaoempen  += $empenhado_acumulado - $anulado_acumulado;
      $totorgaoliqui += $liquidado_acumulado;
      $totorgaopago  += $pago_acumulado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $dotini;
      $totunidaempen += $empenhado_acumulado - $anulado_acumulado;
      $totunidaliqui += $liquidado_acumulado;
      $totunidapago  += $pago_acumulado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa" != "$xprograma" && $o58_programa != 0){
    $xprograma = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa";
    $descr = $o54_descr;
    if($nivela == 5){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado_acumulado - $anulado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
      $totorgaoempen += $empenhado_acumulado - $anulado_acumulado;
      $totorgaoliqui += $liquidado_acumulado;
      $totorgaopago  += $pago_acumulado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $dotini;
      $totunidaempen += $empenhado_acumulado - $anulado_acumulado;
      $totunidaliqui += $liquidado_acumulado;
      $totunidapago  += $pago_acumulado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".db_formatar($o58_projativ,'projativ'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ" != "$xprojativ" && $o58_projativ != 0){
    $xprojativ = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ";
    $descr = $o55_descr;
    if($nivela == 6){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);

      if($nivela != 7){
      $totorgaoanter += $dotini;
      $totorgaoreser += $reservado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      }
    }else{
      $pdf->setfont('arial','b',7);
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenhado - $anulado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pago,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),0,1,"R",0);
      $pdf->setfont('arial','',7);
    }
  }
  if("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento" != "$xelemento" && $o58_elemento  != 0){
    $xelemento = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento";
    $descr = $o56_descr;
    if($nivela == 7){
      $pdf->cell(25,$alt,db_formatar($o58_elemento,'elemento'),0,0,"L",0);
      $pdf->cell(60,$alt,$descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
      $totorgaoreser += $reservado;
      $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totunidaanter += $atual;
      $totunidareser += $reservado;
      $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    }else{
//      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,0,0,"L",0);
//      $pdf->cell(60,$alt,$descr,0,1,"L",0);
    }
  }
  if($o58_codigo > 0){
    $descr = $o56_descr;
    $pdf->cell(20,$alt,$o58_elemento,1,0,"L",0);
    $pdf->cell(60,$alt,substr($descr,0,37),1,0,"L",0);
    $pdf->cell(10,$alt,db_formatar($o58_codigo,'s','0',4,'e'),1,0,"L",0);
    $pdf->cell(30,$alt,substr($o15_descr,0,20),1,0,"L",0);
    $pdf->cell(15,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($dotini,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($empenhado - $anulado,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($liquidado,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($pago,'f'),1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($dotini + $suplementado_acumulado - $reduzido_acumulado,'f'),1,1,"R",0);
      
      $totdotini += $dotini;
      $totempen += $empenhado - $anulado;
      $totliqui += $liquidado;
      $totpago  += $pago;
      $totatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
      
      $totanter += $dotini;
      $totempen += $empenhado - $anulado;
      $totliqui += $liquidado;
      $totpago  += $pago;
      $totatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    
    $totorgaodotini += $dotini;
    $totorgaoreser += $reservado;
    $totorgaoatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;
    
    $totunidaanter += $atual;
    $totunidareser += $reservado;
    $totunidaatual += $dotini + $suplementado_acumulado - $reduzido_acumulado;

    if($lista_subeleme=='S'){
    $sql = "select o56_codele,
                   o56_elemento,
		   o56_descr,
		   sum(case when c71_coddoc = 1 then c70_valor else 0 end ) as emp, 
		   sum(case when c71_coddoc = 2 then c70_valor else 0 end ) as anu_emp, 
		   sum(case when c71_coddoc = 3 then c70_valor else 0 end ) as liq, 
		   sum(case when c71_coddoc = 4 then c70_valor else 0 end ) as anu_liq, 
		   sum(case when c71_coddoc = 5 then c70_valor else 0 end ) as pag, 
		   sum(case when c71_coddoc = 6 then c70_valor else 0 end ) as anu_pag 
	   from orcdotacao
	        inner join conlancamdot on c73_coddot = o58_coddot
  	        inner join conlancamele on c67_codlan = c73_codlan
	        inner join orcelemento  on c67_codele = o56_codele and o56_anousu = orcdotacao.o58_anousu
	        inner join conlancam    on c70_codlan = c73_codlan
		inner join conlancamdoc on c70_codlan = c71_codlan
	   where o58_coddot = $o58_coddot
	   and c71_coddoc in (1,2,3,4,5,6)
	     and c70_data between '$dataini' and '$datafin'
             and o58_anousu = ".db_getsession('DB_anousu')."
           group by o56_codele,o56_elemento,o56_descr
				 ";

/*
      $sql = "select o56_codele,o56_elemento,o56_descr,sum(c70_valor) as total 
              from orcdotacao
	           inner join conlancamele on o58_codele = c67_codele
		   inner join orcelemento  on o56_codele = o58_codele
		   inner join conlancam    on c67_codlan = c70_codlan
		   inner join conlancamdot on c73_codlan = c70_codlan
	      where o58_coddot = $o58_coddot
		    and c70_data between '$dataini' and '$datafin'
		    and o58_anousu = ".db_getsession('DB_anousu')."
		    
	      group by o56_codele,o56_elemento,o56_descr";
*/
      $res = pg_exec($sql);
//      db_criatabela($res);
      for($ne=0;$ne<pg_numrows($res);$ne++){
	db_fieldsmemory($res,$ne);
        $pdf->cell(20,4,$o56_elemento,0,0,"L",0);
        $pdf->cell(60,4,substr($o56_descr,0,37),0,0,"L",0);
//        $pdf->cell(105,$alt,$o56_finali,0,1,"L",0);
       $pdf->cell(30,4,'',0,0,"L",0);
       $pdf->cell(25,4,'',0,0,"R",0);
       $pdf->cell(20,4,'',0,0,"R",0);
       $pdf->cell(20,4,db_formatar($emp - $anu_emp,'f'),0,0,"R",0);
       $pdf->cell(20,4,db_formatar($liq - $anu_liq,'f'),0,0,"R",0);
       $pdf->cell(20,4,db_formatar($pag - $anu_pag,'f'),0,0,"R",0);
       $pdf->cell(20,4,'',0,1,"R",0);
      }
      
    }
    
  }
}
if($quebra_unidade == 'S'){ 
$pdf->setfont('arial','b',7);
$pdf->ln(3);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(85,$alt,'TOTAL DA UNIDADE ',0,0,"L",0,'.');
$pdf->cell(20,$alt,db_formatar($totunidadotini,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totunidareser,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totunidaatual,'f'),0,1,"R",0);
}
$pdf->ln(3);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(85,$alt,'TOTAL DO ORGÃO ',0,0,"L",0,'.');
$pdf->cell(20,$alt,db_formatar($totorgaodotini,'f'),0,0,"R",0);
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
//db_criatabela(pg_exec("select * from temporario"));
$result = db_dotacaosaldo($nivela,3,2,true,$where,$anousu,$dataini,$datafin);
//db_criatabela($result);exit;
// funcao para gerar work
//db_criatabela(pg_exec("select * from work w inner join temporario t on $sele_work "));exit;






$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$troca          = 1;
$alt            = 4;
$qualou         = 0;
$totproj        = 0;
$totativ        = 0;
$pagina         = 1;
$xorgao         = 0;
$xunidade       = 0;
$xfuncao        = 0;
$xsubfuncao     = 0;
$xprograma      = 0;
$xprojativ      = 0;
$xelemento      = 0;
$totorgaodotini = 0;
$totorgaoanter  = 0;
$totorgaoreser  = 0;
$totorgaoatual  = 0;
$totunidaanter  = 0;
$totunidareser  = 0;
$totunidaatual  = 0;
$pagina = 1;

for($k=0;$k<pg_numrows($result);$k++){

  db_fieldsmemory($result,$k);
  $dotini = $dot_ini;
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
    $pdf->cell(20,$alt,"DOT.INICIAL",0,1,"C",0);
//    $pdf->cell(20,$alt,"RESERVADO",0,0,"R",0);
//    $pdf->cell(20,$alt,"SALDO ATUAL",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }
    if($nivela == 1){
      $pdf->cell(25,$alt,db_formatar($o58_orgao,'orgao'),0,0,"L",0);
      $pdf->cell(60,$alt,$o40_descr,0,0,"L",0);
      $pdf->cell(50,$alt,'',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
  //    $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
      $pdf->cell(20,$alt,db_formatar($dotini,'f'),0,1,"R",0);
//      $pdf->cell(20,$alt,db_formatar($reservado,'f'),0,0,"R",0);
//      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
      $totorgaodotini += $dotini;
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
//include("fpdf151/geraarquivo.php");


$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);

//echo $ass_pref;
if( $pdf->gety() > ( $pdf->h - 30 ) )
  $pdf->addpage();

$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);


$pdf->Output();

pg_exec("commit");

?>