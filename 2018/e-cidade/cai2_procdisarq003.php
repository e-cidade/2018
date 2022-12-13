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

$sqlhead = "select * from disarq where codret = $codret ";
$resulthead = pg_query($sqlhead);
$linhashead = pg_num_rows($resulthead);
if($linhashead >0){
  db_fieldsmemory($resulthead,0);

$head1 = "Identificador do arquivo: $codret";
$head3 = "Arquivo: $arqret";
$head5 = "Data arquivo: ".db_formatar($dtarquivo,"d");
$head7 = "Data retorno: ".db_formatar($dtretorno,"d");
$totaldif = 0;
$totalpago = 0;
$totalcalc =0;

$sql  = "  select disbanco.idret, ";
$sql .= "         disbanco.k00_numpre, ";
$sql .= "         disbanco.k00_numpar, ";
$sql .= "         disbanco.dtpago, ";
$sql .= "         disbanco.vlrpago, ";
$sql .= "         disbanco.vlrcalc, ";
$sql .= "         round(disbanco.vlrcalc - disbanco.vlrpago, 2) as diferenca , ";
$sql .= "         (disbancodiver.k44_sequencial is not null) as processado, ";
$sql .= "         disbancodiver.k44_coddiver, ";
$sql .= "         diversos.dv05_numcgm, ";
$sql .= "         (select k00_matric ";
$sql .= "            from arrematric ";
$sql .= "           where k00_numpre = dv05_numpre ";
$sql .= "           limit 1) as k00_matric ";
$sql .= "    from disbanco ";
$sql .= "         inner join disarq        on disarq.codret = disbanco.codret ";
$sql .= "         left  join disbancodiver on k44_idret     = disbanco.idret ";
$sql .= "         left  join diversos      on dv05_coddiver = k44_coddiver ";
$sql .= "   where disbanco.codret = $codret ";
$sql .= "     and cast(round(disbanco.vlrcalc - disbanco.vlrpago, 2) as numeric) > cast(0 as numeric) ";
$sql .= "     and disbanco.classi is true ";
$sql .= "     and disarq.autent is false ";
$sql .= "     and disbanco.instit = ".db_getsession("DB_instit")." ";
$sql .= "order by disbanco.idret " ;



$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$primeiro =0;
$total = 0;
 

$result = pg_exec($sql);
$linhas= pg_num_rows($result);
if($linhas>0){
  for($i=0;$i<$linhas;$i++) {
    db_fieldsmemory($result,$i);
    if($pdf->GetY() > ( $pdf->h - 30 )||($primeiro ==0)){
      $primeiro =1;
      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"RELATÓRIO DE LANÇAMENTOS DE DIFERENÇAS DE ARRECADAÇÃO.",0,"C",0);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(20,6,"CODRET",1,0,"C",1);
      $pdf->Cell(20,6,"NUMPRE",1,0,"C",1);
      $pdf->Cell(10,6,"PARC",1,0,"C",1);
      $pdf->Cell(20,6,"DATA PGTO",1,0,"C",1);
      $pdf->Cell(20,6,"VALOR PAGO",1,0,"C",1);
      $pdf->Cell(20,6,"VALOR CALC",1,0,"C",1);
      $pdf->Cell(20,6,"DIFERENÇA",1,0,"C",1);
      $pdf->Cell(20,6,"DIVERSOS",1,0,"C",1);
      $pdf->Cell(20,6,"CGM",1,0,"C",1);
      $pdf->Cell(20,6,"MATRÍCULA",1,0,"C",1);
     
      $pdf->Ln();

    }
    // para fazer zebrado
    if($i % 2 == 0){
      $pre = 0;
    }else {
      $pre = 1;
    }
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,5,$idret,0,0,"C",$pre);
    $pdf->Cell(20,5,$k00_numpre,0,0,"C",$pre);
    $pdf->Cell(10,5,$k00_numpar,0,0,"C",$pre);
    $pdf->Cell(20,5,db_formatar($dtpago,"d"),0,0,"C",$pre);
    $pdf->Cell(20,5,db_formatar($vlrpago,"f"),0,0,"R",$pre);
    $pdf->Cell(20,5,db_formatar($vlrcalc,"f"),0,0,"R",$pre);
    $pdf->Cell(20,5,db_formatar($diferenca,"f"),0,0,"R",$pre);
    $pdf->Cell(20,5,$k44_coddiver,0,0,"C",$pre);
    $pdf->Cell(20,5,$dv05_numcgm,0,0,"C",$pre);
    $pdf->Cell(20,5,$k00_matric,0,0,"C",$pre);    
    $pdf->Ln();
    $total ++;
    $totaldif = $diferenca + $totaldif;
    $totalpago = $vlrpago + $totalpago;
    $totalcalc = $vlrcalc +$totalcalc;
  }
}
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(70,6,"Total de Registros :   ".$total ,"TB",0,"L",0);
$pdf->Cell(20,6,db_formatar($totalpago,"f"),"TB",0,"R",0);
$pdf->Cell(20,6,db_formatar($totalcalc,"f"),"TB",0,"R",0);
$pdf->Cell(20,6,db_formatar($totaldif,"f"),"TB",0,"R",0);
$pdf->Cell(60,6,"","TB",0,"R",0);

$pdf->Output();

}else{
  db_msgbox(" Arquivo não encontrado.");
}
?>