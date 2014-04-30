<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
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


$head3 = "DEMONSTRATIVO DAS VARIAÇÕES PATRIMONIAIS";
$head4 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "ANEXO 15 - PERÍODO : ".strtoupper(db_mes($mesini))." A ".strtoupper(db_mes($mesfin));


  

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";

$anousu = db_getsession("DB_anousu");

//$dataini = db_getsession("DB_anousu").'-'.$mes.'-'.'01';
$dataini = db_getsession("DB_anousu").'-'.$mesini.'-01';
$datafin = db_getsession("DB_anousu").'-'.$mesfin.'-'.date('t',mktime(0,0,0,$mesfin,'01',db_getsession("DB_anousu")));
  

$result = db_receitasaldo(3,1,2,true,'',$anousu,$dataini,$datafin);  

//db_criatabela($result);exit;

$result1 = db_dotacaosaldo(3,3,2,true,'',$anousu,$dataini,$datafin);  

//db_criatabela($result1);exit;

$tipo = 2;
if($tipo == 1)
  $result2 = db_planocontassaldo(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
else
  $result2 = db_planosissaldo(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);

//db_criatabela($result2);exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4.2;
$pagina         = 1;
$maislinha      = 0;
$total_anterior    = 0;
$total_debitos  = 0;
$total_creditos = 0;
$total_final    = 0;
$xy1 = 0;
$xy2 = 0;
$xy3 = 0;
/////  Ativo Financeiro
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;
$f = 0;
$g = 0;
$h = 0;

$totalrec   = 0;
$totaldesp  = 0;

$totalmuta  = 0;
$totalmutp  = 0;

$totalvara  = 0;
$totalvarp  = 0;

$rec_descr  = array();
$rec_vlr    = array();

$fun_descr  = array();
$fun_vlr    = array();

$inta_descr = array();
$inta_vlr   = array();
$intp_descr = array();
$intp_vlr   = array();

$muta_descr = array();
$muta_vlr   = array();
$mutp_descr = array();
$mutp_vlr   = array();

$vara_descr = array();
$vara_vlr   = array();
$varp_descr = array();
$varp_vlr   = array();

$pdf->addpage();
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,"R E C E I T A S",0,0,"C",0);
$pdf->cell(95,$alt,"D E S P E S A S",0,1,"C",0);
$pdf->ln(3);
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   if($o57_descr == '')
     continue;
   $nivel = db_le_mae_sistema($o57_fonte,true);
   if($nivel > 2)
     $espaco = '    ';
   else
     $espaco = '';
   $rec_descr[$a] = $espaco.$o57_descr;
   $rec_vlr[$a]   = $saldo_arrecadado_acumulado;
   $a += 1;
}
for($ii=0;$ii<pg_numrows($result1);$ii++){
   db_fieldsmemory($result1,$ii);
   if($o57_descr == '')
     continue;
   if($ii == 0){
     $fun_descr[$b] = 'DESPESA POR FUNCOES';
     $fun_vlr[$b]   = '';
     $b +=1;
   }
   $fun_descr[$b] = '    '.$o52_descr;
   $fun_vlr[$b]   = $liquidado;
   $b += 1;
}
$pdf->ln(2);
for($i=0;$i<pg_numrows($result2);$i++){
   db_fieldsmemory($result2,$i);
   if (substr($estrutural,0,3) != "312" && substr($estrutural,0,3) != "412")
      continue;
   $nivel = db_le_mae_sistema($estrutural,true);
   if($nivel > 4)
     continue;
   if($nivel > 3)
     $espaco = '    ';
   else
     $espaco = '';
   if( substr($estrutural,0,1) == 4 ){
      $inta_descr[$c] = $espaco.$c60_descr;
      $inta_vlr[$c]   = $saldo_final;
      $c += 1;
   }else{
      $intp_descr[$d] = $espaco.$c60_descr;
      $intp_vlr[$d]   = $saldo_final;
      $d += 1;
   }
												
}
$numreg = (sizeof($rec_descr)>sizeof($fun_descr)?sizeof($rec_descr):sizeof($fun_descr));
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,"I - O R Ç A M E N T Á R I A",0,0,"L",0);
$pdf->cell(95,$alt,"I - O R Ç A M E N T Á R I A",0,1,"L",0);
$pdf->ln(3);
for($i=0;$i<$numreg;$i++){
  $pdf->setfont('arial','',7);
  if($i == 0){
    $totalrec   = $rec_vlr[$i];
    $pdf->setfont('arial','B',7);
    $alt = 7;
  }else{
    $pdf->setfont('arial','',7);
    $alt = 4;
  }
  if(isset($rec_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$rec_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($rec_vlr[$i]+0,'f'),0,0,"R",0);
  }else{
    $pdf->cell(95,$alt,"",0,0,"L",0);
  }
  if(isset($fun_vlr[$i]) ){ 
    $totaldesp  += $fun_vlr[$i];
    $pdf->cell(70,$alt,$fun_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($fun_vlr[$i]+0,'f'),0,0,"R",0);
  }
  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}

$numreg2 = (sizeof($inta_descr)>sizeof($intp_descr)?sizeof($inta_descr):sizeof($intp_descr));
for($i=0;$i<$numreg2;$i++){
  $pdf->setfont('arial','',7);
  if($i == 0){
    $totalrec   += $inta_vlr[$i];
    $totaldesp  += $intp_vlr[$i];
    $pdf->setfont('arial','B',7);
    $alt = 7;
  }else{
    $pdf->setfont('arial','',7);
    $alt = 4;
  }
  if(isset($inta_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$inta_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($inta_vlr[$i]+0,'f'),0,0,"R",0);
  }else{
    $pdf->cell(95,$alt,"",0,0,"L",0);
  }
  if(isset($intp_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$intp_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($intp_vlr[$i]+0,'f'),0,0,"R",0);
  }
  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}

$pdf->ln(3);
$pdf->setfont('arial','B',7);
$pdf->cell(70,$alt,'T O T A L',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($totalrec,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'T O T A L',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($totaldesp,'f'),0,1,"R",0);
$pdf->ln(5);

$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,"II - M U T A Ç Õ E S    P A T R I M O N I A I S",0,0,"L",0);
$pdf->cell(95,$alt,"II - M U T A Ç Õ E S    P A T R I M O N I A I S",0,1,"L",0);
$pdf->ln(3);

for($i=0;$i<pg_numrows($result2);$i++){
   db_fieldsmemory($result2,$i);
   if (substr($estrutural,0,3) != "313" && substr($estrutural,0,3) != "413")
      continue;
   $nivel = db_le_mae_sistema($estrutural,true);
   
 if($nivel > 5)
   continue;
   
 if($nivel == 3)
   $espaco = '';
 elseif($nivel == 4)
   $espaco = '   ';
 else
   $espaco = '      ';

   if( substr($estrutural,0,1) == 4 ){
      $muta_descr[$e] = $espaco.'   '.$c60_descr;
      $muta_vlr[$e]   = $saldo_final;
      $e += 1;
   }else{
      $mutp_descr[$f] = $espaco.'   '.$c60_descr;
      $mutp_vlr[$f]   = $saldo_final;
      $f += 1;
   }
												
}
$numreg3 = (sizeof($muta_descr)>sizeof($mutp_descr)?sizeof($muta_descr):sizeof($mutp_descr));
for($i=0;$i<$numreg3;$i++){
  $pdf->setfont('arial','',7);
  if($i == 0){
    $totalmuta = $muta_vlr[$i];
    $totalmutp = $mutp_vlr[$i];
    $pdf->setfont('arial','B',7);
    $alt = 7;
  }else{
    $pdf->setfont('arial','',7);
    $alt = 4;
  }
  if(isset($muta_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$muta_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($muta_vlr[$i]+0,'f'),0,0,"R",0);
  }else{
    $pdf->cell(95,$alt,"",0,0,"L",0);
  }
  if(isset($mutp_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$mutp_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($mutp_vlr[$i]+0,'f'),0,0,"R",0);
  }
  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}
$pdf->ln(3);

$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,"III - I N D E P E N D E N T E   D A   E X E C.   O R Ç A M.",0,0,"L",0);
$pdf->cell(95,$alt,"III - I N D E P E N D E N T E   D A   E X E C.   O R Ç A M.",0,1,"L",0);
$pdf->ln(3);

for($i=0;$i<pg_numrows($result2);$i++){
   db_fieldsmemory($result2,$i);
   if (substr($estrutural,0,3) != "321" && substr($estrutural,0,3) != "421")
      continue;
   $nivel = db_le_mae_sistema($estrutural,true);
   
 if($nivel > 5)
   continue;
   
 if($nivel == 3)
   $espaco = '';
 elseif($nivel == 4)
   $espaco = '   ';
 else
   $espaco = '      ';

   if( substr($estrutural,0,1) == 4 ){
      $vara_descr[$g] = $espaco.'   '.$c60_descr;
      $vara_vlr[$g]   = $saldo_final;
      $g += 1;
   }else{
      $varp_descr[$h] = $espaco.'   '.$c60_descr;
      $varp_vlr[$h]   = $saldo_final;
      $h += 1;
   }
												
}
$numreg5 = (sizeof($vara_descr)>sizeof($varp_descr)?sizeof($vara_descr):sizeof($varp_descr));
for($i=0;$i<$numreg5;$i++){
  $pdf->setfont('arial','',7);
  if($i == 0){
    $totalvara = $vara_vlr[$i];
    $totalvarp = $varp_vlr[$i];
    $pdf->setfont('arial','B',7);
    $alt = 7;
  }else{
    $pdf->setfont('arial','',7);
    $alt = 4;
  }
  if(isset($vara_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$vara_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($vara_vlr[$i]+0,'f'),0,0,"R",0);
  }else{
    $pdf->cell(95,$alt,"",0,0,"L",0);
  }
  if(isset($varp_vlr[$i]) ){ 
    $pdf->cell(70,$alt,$varp_descr[$i],0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($varp_vlr[$i]+0,'f'),0,0,"R",0);
  }
  $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
}

$pdf->ln(2);
$pdf->setfont('arial','B',7);

$totalreceitas = $totalrec + $totalmuta + $totalvara;
$totaldespesas = $totaldesp + $totalmutp + $totalvarp;
if($totalreceitas > $totaldespesas){
   $pdf->cell(95,$alt,'',0,0,"L",0);
   $pdf->cell(95,$alt,'R E S U L T A D O    P A T I M O N I A L',0,1,"L",0);
   $pdf->cell(95,$alt,'',0,0,"L",0);
   $pdf->cell(70,$alt,'SUPERAVIT VERIFICADO',0,0,"L",0,'','.');
   $pdf->cell(25,$alt,db_formatar($totalreceitas - $totaldespesas,'f'),0,1,"R",0);
   $superavit = $totalreceitas - $totaldespesas;
   $defict    = 0;
}else{
   $pdf->cell(95,$alt,'R E S U L T A D O    P A T I M O N I A L',0,1,"L",0);
   $pdf->cell(70,$alt,'DEFICIT VERIFICADO',0,0,"L",0,'','.');
   $pdf->cell(25,$alt,db_formatar($totaldespesas - $totalreceitas,'f'),0,1,"R",0);
   $defict    = $totaldespesas - $totalreceitas;
   $superavit = 0;

}
  
$pdf->ln(3);
$pdf->setfont('arial','B',7);
$pdf->cell(70,$alt,'T O T A L   G E R A L',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($totalreceitas + $defict ,'f'),0,0,"R",0);
$pdf->cell(70,$alt,'T O T A L   G E R A L',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($totaldespesas + $superavit,'f'),0,1,"R",0);




$pdf->Ln(15);

assinaturas(&$pdf,&$classinatura,'BG');




$pdf->Output();
   
?>