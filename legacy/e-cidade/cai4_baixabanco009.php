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

require_once("fpdf151/pdf.php");
  
$iInstit = db_getsession("DB_instit");
$sql     = "   select discla.*,                                                                          \n";
$sql    .= "          disrec.k00_receit,                                                                 \n";
$sql    .= "          tabrec.k02_drecei,                                                                 \n";
$sql    .= "          disrec.vlrrec,                                                                     \n";
$sql    .= "          disarq.k00_conta,                                                                  \n";
$sql    .= "          disarq.arqret,                                                                     \n";
$sql    .= "          disarq.dtretorno,                                                                  \n";
$sql    .= "          disarq.dtarquivo,                                                                  \n";
$sql    .= "          saltes.k13_descr,                                                                  \n";
$sql    .= "          (select array_to_string(array_accum( distinct to_char( dtcredito::date, 'DD/MM/YYYY')),', ')  \n";
$sql    .= "             from disbanco                                                                   \n";
$sql    .= "            where codret = discla.codret                                                     \n";
$sql    .= "              and instit = $iInstit)     as dtcredito,                                       \n";
$sql    .= "          (select array_to_string(array_accum( distinct to_char( dtpago::date, 'DD/MM/YYYY')),', ')     \n";
$sql    .= "             from disbanco                                                                   \n";
$sql    .= "            where codret = discla.codret                                                     \n";
$sql    .= "              and instit = $iInstit)     as dtpago,                                          \n";
$sql    .= "          saltes.k13_descr                                                                   \n";
$sql    .= "     from discla                                                                             \n";
$sql    .= "         inner      join disrec on discla.codcla = disrec.codcla                             \n";
$sql    .= "         inner      join tabrec on tabrec.k02_codigo = disrec.k00_receit                     \n";
$sql    .= "          left outer join disarq on disarq.codret = discla.codret                            \n";
$sql    .= "          left outer join saltes on saltes.k13_conta = disarq.k00_conta                      \n";
$sql    .= "    where discla.codcla = $codcla                                                            \n";
$sql    .= "      and discla.instit = $iInstit                                                           \n";
$sql    .= "order by disrec.k00_receit                                                              \n";
$result  = db_query($sql);

$num = pg_numrows($result);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$linha = 0;
$pre = 0;
$total = 0;
$valor = 0;
$pagina = 0;
$receita = pg_result($result,0,"k00_receit");
$dreceita = pg_result($result,0,"k02_drecei");
$totrec = 0 ;
$totval = 0 ;

$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,12,"CLASSIFICAÇÃO - ".pg_result($result,0,"codcla"),0,"C",0);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(15, 3, "CÓD.RETORNO:"       . pg_result( $result,0,"codret" )              ,          0, 1, "L", 0);
$pdf->Cell(15, 3, "DATA CLASSIF:"      . db_formatar(pg_result($result,0,"dtcla"),'d'),          0, 1, "L",0);
$pdf->Cell(15, 3, "ARQUIVO:"           . trim( pg_result($result,0,"arqret") ) . "  DATA RETORNO: ".db_formatar(pg_result($result,0,"dtretorno"),"d")."  DATA ARQUIVO: ".db_formatar(pg_result($result,0,"dtarquivo"),"d") ,0,1,"L",0);
$pdf->Cell(15, 3, "CONTA:"             . pg_result($result,0,"k00_conta")." - ".strtoupper(pg_result($result,0,"k13_descr")),0,1,"L",0);
$pdf->Cell(15, 3, "DATAS DE CRÉDITO:"  . pg_result($result, 0, "dtcredito" ), 0, 1, "L", 0);
$pdf->Cell(15, 3, "DATAS DE PAGAMENTO:". pg_result($result, 0, "dtpago")    , 0, 1, "L", 0);
$pdf->Ln(2);                             
$pdf->Cell(30, 6, "AUTENTICAÇÃO"         ,1, 0, "C", 1);
$pdf->Cell(20, 6, "RECEITA"              ,1, 0, "C", 1);
$pdf->Cell(70, 6, "DESCRIÇÃO DA RECEITA" ,1, 0, "C", 1);
$pdf->Cell(30, 6, "VALOR"                ,1, 1, "C", 1);
                                               
for($i=0;$i<$num;$i++) {
   if($pdf->GetY() > ( $pdf->h - 30 )){
      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"CLASSIFICAÇÃO - ".pg_result($result,0,"codcla"),0,"C",0);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(15,3,"CÓD.RETORNO ".pg_result($result,0,"codret"),0,1,"L",0);
      $pdf->Cell(15,3,"DATA : ".db_formatar(pg_result($result,0,"dtcla"),'d'),0,1,"L",0);
      $pdf->Cell(15,3,"CONTA : ".pg_result($result,0,"k00_conta")." - ".strtoupper(pg_result($result,0,"k13_descr")),0,1,"L",0);
      $pdf->Ln(2);
      $pdf->Cell(30,6,"AUTENTICAÇÃO.",1,0,"C",1);
      $pdf->Cell(20,6,"RECEITA",1,0,"C",1);
      $pdf->Cell(70,6,"DESCRIÇÃO DA RECEITA",1,0,"C",1);
      $pdf->Cell(30,6,"VALOR",1,1,"C",1);
      $linha = 0;
   }
   db_fieldsmemory($result,$i);
   if ( $receita != $k00_receit ) {
   if($linha % 2 == 0){
     $pre = 0;
   }else {
     $pre = 1;
   }
       $pdf->SetFont('Arial','',7);
       $pdf->Cell(30,4,$dtaute,0,0,"C",$pre);
       $pdf->Cell(20,4,$receita,0,0,"C",$pre);
       $pdf->Cell(70,4,$dreceita,0,0,"L",$pre);
       $pdf->Cell(30,4,db_formatar($totval,'f'),0,1,"R",$pre);
//       $pdf->cell(120,4,"Total :  ".$totrec."  Registros","TB",0,"L",1);
//	   $pdf->cell(30,4,db_formatar($totval,'f'),"TB",1,"R",1);
//	   $pdf->Ln(2);
	   $totrec = 0;
	   $totval = 0;
         $linha += 1;
   }
//   $pdf->Cell(30,4,$dtaute,0,0,"C",$pre);
//   $pdf->Cell(20,4,$k00_receit,0,0,"C",$pre);
//   $pdf->Cell(70,4,$k02_drecei,0,0,"L",$pre);
//   $pdf->Cell(30,4,db_formatar($vlrrec,'f'),0,1,"R",$pre);
   $total += 1;
//   $linha += 1;
   $receita = $k00_receit;
   $dreceita = $k02_drecei;
   $valor += $vlrrec;
   $totrec += 1;
   $totval += $vlrrec;
}
   if($linha % 2 == 0){
     $pre = 0;
   }else {
     $pre = 1;
   }
       $pdf->SetFont('Arial','',7);
       $pdf->Cell(30,4,$dtaute,0,0,"C",$pre);
       $pdf->Cell(20,4,$receita,0,0,"C",$pre);
       $pdf->Cell(70,4,$dreceita,0,0,"L",$pre);
       $pdf->Cell(30,4,db_formatar($totval,'f'),0,1,"R",$pre);
//       $pdf->cell(120,4,"Total :  ".$totrec."  Registros","TB",0,"L",1);
//	   $pdf->cell(30,4,db_formatar($totval,'f'),"TB",1,"R",1);
//	   $pdf->Ln(2);
	   $totrec = 0;
	   $totval = 0;

//$pdf->cell(120,4,"Total :  ".$totrec."  Registros","TB",0,"L",1);
//$pdf->cell(30,4,db_formatar($totval,'f'),"TB",1,"R",1);
//$pdf->Ln(2);

$pdf->Ln(5);
$pdf->Cell(120,6,"Total Geral :   ","TB",0,"L",0);
$pdf->Cell(30,6,db_formatar($valor,'f'),"TB",0,"R",0);
$pdf->Output();

?>