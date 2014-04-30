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

include("fpdf151/pdf1.php");
include("classes/db_contlot_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_editalserv_classe.php");
include("classes/db_editalrua_classe.php");
$clcontlot = new cl_contlot;
$clcontlotv = new cl_contlotv;
$cleditalserv = new cl_editalserv;
$cleditalrua = new cl_editalrua;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
//$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$head1 = "LOTES";
$result= $clcontlot->sql_record($clcontlot->sql_query($contri,"","j34_setor,j34_quadra,j34_lote,j34_zona,j34_idbql","j34_setor,j34_quadra"));
$num = $clcontlot->numrows;
if($num<1){
  echo "
    <script>
     window.close();
     opener.alert('Não foram selecionados lotes para esta contribuição');
    </script>
  ";
}
$linha = 60;
$lin=0;
$pri=false;
for($i=0;$i<$num;$i++) {
   $lin++;
   if($linha>20){
      $linha = 0;
      $pdf->AddPage("P");
      $pdf->SetFont('Arial','B',8);
      $reso= $cleditalrua->sql_record($cleditalrua->sql_query($contri,"d02_codedi,d01_numero,j14_nome"));
      db_fieldsmemory($reso,0);
	$pdf->MultiCell("100",6,"CONTRIBUIÇÃO:".$contri." EDITAL:".$d01_numero."  RUA:".$j14_nome,1,"J",1,0);
      $pdf->ln();
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(12,6,"SETOR",1,0,"C",1);
      $pdf->Cell(15,6,"QUADRA",1,0,"C",1);
      $pdf->Cell(12,6,"LOTE",1,0,"C",1);
      $pdf->Cell(12,6,"ZONA",1,0,"C",1);
      $resulta= $cleditalserv->sql_record($cleditalserv->sql_query($contri,"","d04_quant,d04_vlrcal,d04_vlrval,d03_descr"));
      $numw=$cleditalserv->numrows;  
      for($j=0; $j<$numw; $j++){
        $pdf->Cell(25,6,"SERVIÇO".($j+1),1,0,"C",1);
        $pdf->Cell(25,6,"VALOR",1,0,"C",1);
      }
      if($pri==false){
         for($j=0; $j<$numw; $j++){
	     $xx="serv".$j;
	     $$xx="";
	     $yy="val".$j;
	     $$yy="";
	 }  
	 $pri=true; 
      }
      $pdf->ln();
   }
   if($linha % 2 == 0){
     $pre = 0;
   }else {
     $pre = 1;
   }
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',6);
   $pdf->Cell(12,4,$j34_setor,1,0,"C",0);
   $pdf->Cell(15,4,$j34_quadra,1,0,"C",0);
   $pdf->Cell(12,4,$j34_lote,1,0,"C",0);
   $pdf->Cell(12,4,$j34_zona,1,0,"C",0);
   $resul= $clcontlotv->sql_record($clcontlotv->sql_query($contri,"","","d06_fracao,d06_valor"));
   for($f=0; $f<$numw; $f++){
     db_fieldsmemory($resul,$f);
     $pdf->Cell(25,4,$d06_fracao."m",1,0,"C",0);
     $pdf->Cell(25,4,"R$ ".number_format($d06_valor,2,'.',''),1,0,"C",0);
     $xx="serv".$f;
     $$xx+=$d06_fracao;
     $yy="val".$f;
     $$yy+=$d06_valor;
   }
   $pdf->Ln();
   if($lin==$num){
     $pdf->Cell(51,4,"TOTAL:",1,0,"R",1);
     for($j=0; $j<$numw; $j++){
       $xx="serv".$j;
       $pdf->Cell(25,4,$$xx." m",1,0,"R",1);
       $yy="val".$j;
       $pdf->Cell(25,4,"R$ ".number_format($$yy,2),1,0,"R",1);
     }  
     $pdf->Ln();
   }  
   if($linha++>19){
      $pdf->Ln();
      $pdf->SetFont('Times','',6);
      $pdf->SetX(10);
      for($j=0; $j<$numw; $j++){
        db_fieldsmemory($resulta,$j);   
        $pdf->Cell(50,4,"SERVIÇO".($j+1).":".$d03_descr." m",1,0,"L",1);
        $pdf->Cell(50,4,"QUANT:".$d04_quant." VALOR PARA CÁLCULO: R$ ".number_format($d04_vlrcal,2,'.',''),1,0,"L",1);
       $pdf->ln();
     }
   }
}
 if($linha!=21){
      $pdf->Ln();
      $pdf->SetFont('Times','',6);
      $pdf->SetX(10);
      for($j=0; $j<$numw; $j++){
        db_fieldsmemory($resulta,$j);   
        $pdf->Cell(50,4,"SERVIÇO".($j+1).":".$d03_descr,1,0,"L",1);
        $pdf->Cell(50,4,"QUANT:".$d04_quant." VALOR PARA CÁLCULO: ".number_format($d04_vlrcal,2,'.',''),1,0,"L",1);
       $pdf->ln();
     }
   
 } 

$pdf->Ln(5);
//$pdf->Cell(95,6,Total :    $total,0,1,"L",0);
$pdf->Output();

?>