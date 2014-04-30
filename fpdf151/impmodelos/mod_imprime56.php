<?php
 
if (($this->qtdcarne % 4 ) == 0 ){
  $this->objpdf->AddPage();
}
$this->objpdf->SetAutoPageBreak(true, 1);
$this->objpdf->SetLineWidth(0.05);
$top = $this->objpdf->GetY()-3;
$this->objpdf->SetFont('Arial','B',8);
$this->objpdf->SetTextColor(0,0,0);
$this->objpdf->SetFillColor(250,250,250);
$this->objpdf->SetX(20);
$this->objpdf->Text(17,$top+2,$this->prefeitura,0,0,"L",0);
$this->objpdf->SetFont('Arial','B',8);
$this->objpdf->SetX(105);
$this->objpdf->Text(135,$top+3,"RECIBO DE ENTREGA",0,1,"L",0);
//$this->objpdf->Ln(1);
$this->objpdf->SetFont('Arial','B',6);
$this->objpdf->SetX(15);
$this->objpdf->Cell(70,5,$this->tipodebito,0,0,"R",0);
$this->objpdf->SetFont('Arial','B',6);

$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(115,$top+9,"PELO PRESENTE ACUSO O RECEBIMENTO DO CARNÊ PARA",0,1,"L",0);
$this->objpdf->Text(115,$top+12,"PAGAMENTO DE ".$this->tipodebito,0,1,"L",0);
$this->objpdf->SetFont('Arial','',7);
$this->objpdf->Text(110,$top+25,$this->munic." , _______ de __________________________ de ".date('Y', db_getsession('DB_datausu')),0,1,"L",0);
$this->objpdf->Text(100,$top+34,"Nome       :  ________________________________________________________________","L",0);
$this->objpdf->Text(100,$top+41,"Endereço :  ________________________________________________________________",0,1,"L",0);
$this->objpdf->Text(100,$top+50,"Assinatura :  _______________________________________________________________",0,1,"L",0);

$y = $this->objpdf->GetY() + 3;

$this->objpdf->Image('imagens/files/'.$this->logo,8,$y-9,8);
$this->objpdf->SetFont('Times','',5);
$this->objpdf->RoundedRect(10,$y+1,47,6,2,'DF','1234'); // Matricula/ Inscrição
$this->objpdf->RoundedRect(58,$y+1,25,6,2,'DF','1234'); // Data Emissão
$this->objpdf->RoundedRect(10,$y+8,73,15,2,'DF','1234'); // Nome / Endereço
$this->objpdf->RoundedRect(10,$y+24,73,27,2,'DF','1234'); // Dados

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13,$y+3,$this->titulo1); // Matricula/ Inscrição
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(13,$y+6,$this->descr1); // Numero da Matricula ou Inscricao

//$this->objpdf->SetFont('Arial','',5);
//$this->objpdf->Text(39,$y+3,$this->titulo2); // Cod. de Arrecadação
//$this->objpdf->SetFont('Arial','B',7);
//$this->objpdf->Text(39,$y+6,$this->descr2); // Numpre

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(63,$y+3,"Data Emissão"); // Data Emissão
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(63,$y+6,date('d/m/Y', db_getsession('DB_datausu'))); // Data Emissão

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13,$y+10,$this->titulo3); // Contribuinte/Endereço
$this->objpdf->SetFont('Arial','B',$this->Tfont);
$this->objpdf->Text(13,$y+13,$this->contrcapa); // Nome do Contribuinte
$this->objpdf->Text(13,$y+16,$this->endcapa); // Endereço
$this->objpdf->Text(13,$y+19,$this->bairrocapa.($this->cidufcapa==""?"":" - ".$this->cidufcapa)); // bairro municipio
$this->objpdf->Text(13,$y+22,$this->cepcapa); // CEP
$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13,$y+26,$this->titulo4); // Dados
$this->objpdf->SetFont('Arial','B',$this->Tfont);
$xx = $this->objpdf->getx();
$yy = $this->objpdf->gety();

$this->objpdf->setleftmargin(10);
$this->objpdf->setrightmargin(120);

$this->objpdf->sety($y+28);
$this->objpdf->setx(12);
$this->objpdf->MultiCell(70,3,$this->dados1,0,1,"L",0);
$this->objpdf->setx(12);
$this->objpdf->Cell(35,3,$this->dados2,0,0,"L",0);
$this->objpdf->Cell(35,3,$this->dados3,0,1,"L",0);
$this->objpdf->setx(12);
$this->objpdf->Cell(35,3,$this->dados4,0,0,"L",0);
$this->objpdf->Cell(35,3,$this->dados5,0,1,"L",0);
$this->objpdf->setx(12);
$this->objpdf->Cell(35,3,$this->dados6,0,0,"L",0);
$this->objpdf->Cell(35,3,$this->dados7,0,1,"L",0);
$this->objpdf->setx(12);
$this->objpdf->Cell(35,3,$this->dados8,0,0,"L",0);
$this->objpdf->Cell(35,3,$this->dados9,0,1,"L",0);
$this->objpdf->setx(12);
$this->objpdf->Cell(35,3,$this->dados10,0,0,"L",0);
$this->objpdf->Cell(35,3,$this->dados11,0,1,"L",0);
$this->objpdf->setxy($xx,$yy-1);

$xx = $this->objpdf->getx();
$yy = $this->objpdf->gety();
$this->objpdf->setleftmargin(97);
$this->objpdf->setrightmargin(2);
$this->objpdf->SetAutoPageBreak(true, 1);
$this->objpdf->sety($y+24);

$this->objpdf->setxy($xx,$yy);

$this->objpdf->SetLineWidth(0.05);
$this->objpdf->SetDash(1,1);
$this->objpdf->Line(93,$y-25,93,$y+55); // linha tracejada vertical
$this->objpdf->SetDash();
$this->objpdf->Ln(75);
$this->objpdf->SetFillColor(0,0,0);
$this->objpdf->SetFont('Arial','',10);

/*if ($this->linha_digitavel != null) {
  $this->objpdf->Text(105,$y+38,$this->linha_digitavel);
}
if ($this->codigo_barras != null) {
  $this->objpdf->int25(95,$y+39,$this->codigo_barras,15,0.341);
}*/

//$this->objpdf->SetY($this->objpdf->gety()-5);

$this->objpdf->SetLineWidth(0.05);
$this->objpdf->SetDash(1,1);
$this->objpdf->Line(0,$this->objpdf->gety()-12, $this->objpdf->w ,$this->objpdf->gety()-12); // linha tracejada vertical
$this->objpdf->SetDash();
?>
