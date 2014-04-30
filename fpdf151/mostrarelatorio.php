<?

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
define('FPDF_FONTPATH','font/');
require('fpdf.php');

class PDF extends FPDF {
  //Page header
  function Header() {
    //Logo
    //$this->Image('../logo.png',8,4,30);
    //Arial bold 15
    //    $this->SetFont('Arial','B',15);
    //Move to the right
    //  $this->Cell(80);
    //Title
    //   $this->Cell(30,10,'Title',1,0,'C');
    //Line break
    $this->Ln(10);
  }

  //Page footer
  function Footer() {
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'página '.$this->PageNo().' de {nb}',0,0,'C');
  }
}

if(!file_exists("/tmp/".$arquivo)) {
  echo "<script> 
  alert('Codigo nao Encontrado.');
  window.close();
  </script>";
  exit;
}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$arq = file("/tmp/".$arquivo);

$tam = sizeof($arq);
$tamfonte = 6.5;
$pdf->SetFont('Courier','',$tamfonte);

$contlinha = 1;
$totlinha = 61;

for($i = 0;$i < $tam;$i++) {
  $xarq = $arq[$i];

 /* 
  if((strpos("####".$xarq,chr(27)."&l84P")>0) ||
     (strpos("####".$xarq,chr(27)."&l72P")>0)){
    $coluna = strpos("####".$xarq,"&l84P");
    if($coluna==0){
      $coluna = strpos("####".$xarq,"&l72P");
    }
    $totlinha = substr($xarq,$coluna-2,2);
    $totlinha = $totlinha - 5;
  }
  */
  if(strpos("####".$xarq,chr(27)."[4i")>0){
    break;
  }
/*
  $controm = strpos("##".$xarq,"\n");
  if($controm>0){
    $arq[$i] = substr($arq[$i],$controm-1);
  }*/
  $arq[$i] = str_replace(chr(27)."&f7y4X","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s#7#H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s3B","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s0B","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."&d0D","",$arq[$i]);

  $arq[$i] = str_replace(chr(27)."(s10H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."[4i","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."&l84P","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."&l72P","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s16.67H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s#16.67#H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s7b4102T","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s#12#H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s#10#H","",$arq[$i]);
  $arq[$i] = str_replace(chr(27)."(s#8#H","",$arq[$i]);

  $arq[$i] = str_replace("C".chr(8).",","Ç",$arq[$i]);
  $arq[$i] = str_replace("c".chr(8).",","ç",$arq[$i]);
  $arq[$i] = str_replace("a".chr(8)."~","ã",$arq[$i]);
  $arq[$i] = str_replace("A".chr(8)."~","Ã",$arq[$i]);
  $arq[$i] = str_replace("o".chr(8)."~","õ",$arq[$i]);
  $arq[$i] = str_replace("O".chr(8)."~","Õ",$arq[$i]);
  $arq[$i] = str_replace("o".chr(8)."'","ó",$arq[$i]);
  $arq[$i] = str_replace("O".chr(8)."'","Ó",$arq[$i]);
  $arq[$i] = str_replace("a".chr(8)."'","á",$arq[$i]);
  $arq[$i] = str_replace("A".chr(8)."'","Á",$arq[$i]);
  $arq[$i] = str_replace("o".chr(8)."^","ô",$arq[$i]);
  $arq[$i] = str_replace("O".chr(8)."^","Ô",$arq[$i]);
  $arq[$i] = str_replace("A".chr(8)."^","Â",$arq[$i]);
  $arq[$i] = str_replace("A".chr(8)."^","Â",$arq[$i]);
  $arq[$i] = str_replace("e".chr(8)."^","ê",$arq[$i]);
  $arq[$i] = str_replace("E".chr(8)."^","Ê",$arq[$i]);
  $arq[$i] = str_replace(chr(15),"",$arq[$i]);
  $arq[$i] = str_replace(chr(18),"",$arq[$i]);
  $arq[$i] = str_replace(chr(8),"",$arq[$i]);
  $arq[$i] = str_replace(chr(12),"",$arq[$i]);
  $arq[$i] = str_replace("\n","",$arq[$i]);
  if(strrchr($xarq,chr(18)) && strpos($xarq,chr(18)) < (strlen($xarq)/2)) {
    $tamfonte = 10.5;
  }
  if(strrchr($xarq,chr(15)) && strpos($xarq,chr(15)) < (strlen($xarq)/2)) {
    $tamfonte = 6.5;
  }
  $pdf->SetFont('Courier','',$tamfonte);
  $pdf->Cell(0,3,$arq[$i],0,1);
  if(strrchr($xarq,chr(18)) && strpos($xarq,chr(18)) == (strlen($xarq) - 2)) {
    $tamfonte = 10.5;
  }
  if(strrchr($xarq,chr(15)) && strpos($xarq,chr(15)) == (strlen($xarq) - 2)) {
    $tamfonte = 6.5;
  }	
  //( ( strrchr($xarq,chr(12)) ) ) { 
  // $pdf->AddPage();
 //
  $contlinha ++;
  if($contlinha == $totlinha || strrchr($xarq,chr(12)) ){
    $contlinha = 1;
    $pdf->AddPage();
  }
}

$pdf->Output();
//header('Content-Type: application/pdf');
?>
 
