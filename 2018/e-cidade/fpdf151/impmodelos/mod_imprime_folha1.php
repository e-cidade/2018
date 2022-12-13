<?
$pdf->cell(12,$alt,$registro. " - " .db_CalculaDV($registro),0,0,"C",0);
$pdf->cell(80,$alt,$nomeregi,0,0,"L",0);
$pdf->cell(8,$alt,"HM: ",0,0,"L",0);
$pdf->cell(15,$alt,$horasmes,0,0,"L",0);
$pdf->cell(15,$alt,"Admiss.: ",0,0,"L",0);
$pdf->cell(15,$alt,$admisrec,0,0,"L",0);
$pdf->cell(10,$alt,"Pad.: ",0,0,"L",0);

if(trim($clas1rec) != ""){
  $pdf->cell(15,$alt,$clas1rec,0,0,"L",0);
}else{
  $pdf->cell(15,$alt,$descpadr,0,0,"L",0);
}

$pdf->cell(10,$alt,"Situac.: ",0,0,"L",0);
$pdf->cell(15,$alt,$afastame,0,1,"L",0);

if($cargorec != ""){
  $pdf->cell(12,$alt,"Funcao: ",0,0,"L","0");
  $pdf->cell(80,$alt,$cargorec,0,0,"L",0);
}else{
  $pdf->cell(12,$alt,"Cargo: ",0,0,"L","0");
  $pdf->cell(80,$alt,$funcarec,0,0,"L",0);
}
$pdf->cell(10,$alt,"Lot: ",0,0,"L","0");
$pdf->cell(20,$alt,$lotacrec.' - '.$lotadescrcrec,0,1,"L",0);
?>
