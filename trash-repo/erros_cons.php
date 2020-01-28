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

//die($clempautitem->sql_query($e61_autori,"","e55_item,pc01_descrmater,e55_codele,e55_descr,e55_quant,e55_vltot,o56_elemento,o56_descr as \"dl_Descrição do elemento\""));
$result_empautitem=$clempautitem->sql_record($clempautitem->sql_query($e61_autori,"","e55_item,pc01_descrmater,e55_codele,e55_descr,e55_quant,e55_vltot,o56_elemento as o56_elemento_itens,o56_descr"));
$numrows=$clempautitem->numrows;
$pdf->setfont('arial','b',8);
$pdf->cell(90,$alt,'ITENS DO EMPENHO',0,1,"L",0);
for($i = 0; $i<$numrows; $i++){
  db_fieldsmemory($result_empautitem,$i,true);
  if($pdf->gety() > $pdf->h - 30 || $troca!=0){
    if($pdf->gety() > $pdf->h - 30){
      $pdf->addpage("L");
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(18,$alt,$RLe55_item,1,0,"C",1);
    $pdf->cell(18,$alt,$RLe55_codele,1,0,"C",1);
    $pdf->cell(50,$alt,$RLe55_descr,1,0,"C",1);
    $pdf->cell(18,$alt,$RLe55_quant,1,0,"C",1);
    $pdf->cell(18,$alt,$RLe55_vltot,1,0,"C",1);
    $pdf->cell(18,$alt,"Elemento",1,0,"C",1);
    $pdf->cell(65,$alt,"Descrição elemento",1,0,"C",1);
    $pdf->cell(65,$alt,$RLpc01_descrmater,1,1,"C",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(18,$alt,$e55_item,"T",0,"C",0);
  $pdf->cell(18,$alt,$e55_codele,"T",0,"C",0);
  $pdf->cell(50,$alt,substr($e55_descr,0,40),"T",0,"L",0);
  $pdf->cell(18,$alt,$e55_quant,"T",0,"C",0);
  $pdf->cell(18,$alt,$e55_vltot,"T",0,"R",0);
  $pdf->cell(18,$alt,$o56_elemento_itens,"T",0,"C",0);
  $pdf->cell(65,$alt,$o56_descr,"T",0,"L",0);
  $pdf->multicell(65,$alt,substr($pc01_descrmater,0,55),"T","L",0);
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(270,$alt,'TOTAL DE ITENS NESTE EMPENHO :  '.$total,"T",1,"L",0);
$pdf->ln(10);
//  $result_apolitem = $clapolitemi

 $sql = " select c70_codlan,
		 c70_data, 
		 c53_descr,
		 c70_valor
	  from conlancamemp
	       inner join conlancam on c70_codlan = c75_codlan
	       inner join conlancamdoc on c71_codlan = c70_codlan
	       inner join conhistdoc on c53_coddoc = c71_coddoc
	  where  c75_numemp=$e60_numemp
	  order by c75_codlan	   
	";

//die($sql);
$result_lancamentos=$clconlancamemp->sql_record($sql);
$numrows_lancamentos=$clconlancamemp->numrows;
$pdf->setfont('arial','b',8);
if($numrows_lancamentos==0){
  $pdf->cell(0,$alt,'EMPENHO SEM LANÇAMENTOS',0,1,"L",0);
}else{
  $pdf->cell(0,$alt,'LANÇAMENTOS',0,1,"L",0);
  for($i = 0; $i<$numrows_lancamentos; $i++){
    db_fieldsmemory($result_lancamentos,$i,true,true);
    if($pdf->gety() > $pdf->h - 30 || $troca!=0 || $i==0){
      if($pdf->gety() > $pdf->h - 30){
	$pdf->addpage("L");
      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,$RLc70_codlan,1,0,"C",1);
      $pdf->cell(30,$alt,$RLc70_data,1,0,"C",1);
      $pdf->cell(65,$alt,$RLc53_descr,1,0,"C",1);
      $pdf->cell(30,$alt,$RLc70_valor,1,1,"C",1);
      $troca = 0;
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(30,$alt,$c70_codlan,"T",0,"C",0);
    $pdf->cell(30,$alt,$c70_data,"T",0,"C",0);
    $pdf->cell(65,$alt,$c53_descr,"T",0,"L",0);
    $pdf->cell(30,$alt,$c70_valor,"T",1,"R",0);
    $total2++;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(155,$alt,'TOTAL DE LANÇAMENTOS NESTE EMPENHO :  '.$total2,"T",0,"L",0);
}

$pdf->ln(10);
//die($clempnota->sql_query_file(null,"*",'',"e69_numemp=$e60_numemp")); 
$result_notas = $clempnota->sql_record($clempnota->sql_query_file(null,"*",'',"e69_numemp=$e60_numemp")); 
$numrows_notas = $clempnota->numrows;
if($numrows_notas==0){
  $pdf->cell(0,$alt,'EMPENHO SEM NOTAS',0,1,"L",0);
}else{
  $pdf->cell(0,$alt,'NOTAS DO EMPENHO',0,1,"L",0);
  for($i = 0; $i<$numrows_notas; $i++){
    db_fieldsmemory($result_notas,$i,true);
    if($pdf->gety() > $pdf->h - 30 || $troca!=0 || $i==0){
      if($pdf->gety() > $pdf->h - 30){
	$pdf->addpage("L");
      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,$RLe70_codnota,1,0,"C",1);
      $pdf->cell(30,$alt,$RLe69_numero,1,0,"C",1);
      $pdf->cell(30,$alt,$RLe70_valor,1,0,"C",1);
      $pdf->cell(30,$alt,"Liquidado",1,0,"C",1);
      $pdf->cell(30,$alt,"Anulado",1,0,"C",1);
      $pdf->cell(30,$alt,$RLe69_dtnota,1,1,"C",1);
      $troca = 0;
    }
    $tot2_valor  = 0 ;
    $tot2_vlrliq = 0  ;          
    $tot2_vlranu = 0 ;
 
//    die($clempnotaele->sql_query_file($e69_codnota,null,"sum(e70_valor) as tot_valor,sum(e70_vlranu) as tot_vlranu,sum(e70_vlrliq) as tot_vlrliq"));
    $result_notaele  = $clempnotaele->sql_record($clempnotaele->sql_query_file($e69_codnota,null,"sum(e70_valor) as tot_valor,sum(e70_vlranu) as tot_vlranu,sum(e70_vlrliq) as tot_vlrliq"));
    $numrows_notaele = $clempnotaele->numrows;
	   //rotina que totaliza os valores
    if($numrows_notaele==0){
      $tot_valor  = '0.00' ;
      $tot_vlrliq = "0.00"  ;          
      $tot_vlranu = "0.00" ;
    }else{
      db_fieldsmemory($result_notaele,0,true);
      $pdf->setfont('arial','',6);
      $pdf->cell(30,$alt,$e69_codnota,"T",0,"C",0);
      $pdf->cell(30,$alt,$e69_numero,"T",0,"C",0);
      $pdf->cell(30,$alt,db_formatar($tot_valor,"f"),"T",0,"C",0);
      $pdf->cell(30,$alt,db_formatar($tot_vlrliq,"f"),"T",0,"C",0);
      $pdf->cell(30,$alt,db_formatar($tot_vlranu,"f"),"T",0,"C",0);
      $pdf->cell(30,$alt,db_formatar($e69_dtnota,"d"),"T",1,"C",0);
      $tot2_valor  +=  $tot_valor ;
      $tot2_vlrliq +=  $tot_vlrliq;          
      $tot2_vlranu +=  $tot_vlranu;
    }
  } 
  $pdf->cell(60,$alt,"Total","T",0,"R",0);
  $pdf->cell(30,$alt,db_formatar($tot2_valor,"f"),"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($tot2_vlrliq,"f"),"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($tot2_vlranu,"f"),"T",1,"C",0);
}
?>