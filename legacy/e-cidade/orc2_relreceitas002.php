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
include("libs/db_sql.php");
include("libs/db_liborcamento.php");

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);


$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ; 
  $xvirg = ', ';
}
$head2 = "DEMONSTRATIVO DA RECEITA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head4 = "INSTITUIÇÕES : ".$descr_inst;

$sele = ' d.o70_instit in ('.str_replace('-',', ',$db_selinstit).') ';

$result = db_receitasaldo(11,1,2,true,$sele);
//db_criatabela($result);exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pagina = 1;
$alt = 5;
$estrut = 0;

for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;

  if($estrutural == 400000000000000)
    continue;
    
  if($pdf->gety() > $pdf->h-30 || $pagina == 1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2);
    $pdf->cell(95,$alt,"RECEITA",0,0,"L",0);
    $pdf->cell(37,$alt,"RECURSO",0,0,"L",0);
    $pdf->cell(15,$alt,"REDUZ",0,0,"R",0);
    $pdf->cell(20,$alt,"PREVISÃO",0,0,"R",0);
    $pdf->cell(20,$alt,"ADICIONAL",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',6);
  }
  $troca = 0;
  if (substr($estrutural,1,10) == '0000000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,$o57_descr ,0,$troca,"L",0);
  }elseif (substr($estrutural,2,9) == '000000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,' '.$o57_descr,0,$troca,"L",0);
  }elseif (substr($estrutural,3,8) == '00000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'  '.$o57_descr,0,$troca,"L",0);
  }elseif (substr($estrutural,4,7) == '0000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'   '.$o57_descr,0,$troca,"L",0);
  }elseif (substr($estrutural,5,8) == '00000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'    '.$o57_descr,0,$troca,"L",0);
  }elseif (substr($estrutural,7,6) == '000000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'      '.$o57_descr,0,$troca,"L",0);
  }elseif(substr($estrutural,9,4) == '0000'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'        '.$o57_descr,0,$troca,"L",0);
  }elseif(substr($estrutural,11,2) == '00'){
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'          '.$o57_descr,0,$troca,"L",0);
  }else{
     $pdf->cell(23,$alt,db_formatar($estrutural,'receita'),0,0,"L",0);
     $pdf->cell(73,$alt,'            '.$o57_descr,0,$troca,"L",0);
  }
 if($estrut != $estrutural) {
    if($o70_codrec != 0 ){
      $pdf->cell(7,$alt,db_formatar($o70_codigo,'s','0',4,'e'),0,0,"L",0);
      $pdf->cell(35,$alt,substr($o15_descr,0,27),0,0,"L",0);
      $pdf->cell(10,$alt,$o70_codrec."-".db_CalculaDV($o70_codrec),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",0);
      $total += $saldo_inicial;
    } 
    
  }
  $pdf->ln();
  $estrut = $estrutural;
}

$pdf->ln(3);
$pdf->setfont('arial','B',6);
$pdf->cell(50,$alt,'',0,0,"L",0);
$pdf->cell(120,$alt,'TOTAL GERAL :',0,0,"C",0,'.');
$pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",0);

$pdf->Output();

pg_exec("commit");

?>