<?php

if (($this->qtdcarne % 4 ) == 0 ){
  $this->objpdf->AddPage();
}

$this->objpdf->SetLineWidth(0.05);
if($this->atualizaquant == true){
  $this->qtdcarne += 1;
}
$iAjusteColunaX = 7;

$top = $this->objpdf->GetY()-3;
$this->objpdf->SetFont('Arial','B',8);
$this->objpdf->SetTextColor(0,0,0);
$this->objpdf->SetFillColor(250,250,250);
$this->objpdf->SetX(17-$iAjusteColunaX);
$this->objpdf->Text(17-$iAjusteColunaX,$top,$this->prefeitura,0,0,"L",0);
$this->objpdf->SetX(105-$iAjusteColunaX);
$this->objpdf->Text(105-$iAjusteColunaX,$top,$this->prefeitura,0,1,"L",0);
$this->objpdf->SetX(170-$iAjusteColunaX);
$this->objpdf->SetX(17-$iAjusteColunaX);
$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(17-$iAjusteColunaX,$top+2,$this->secretaria,0,0,"L",0);
$this->objpdf->SetX(105-$iAjusteColunaX);
$this->objpdf->Text(105-$iAjusteColunaX,$top+2,$this->secretaria,0,1,"L",0);
$this->objpdf->Ln(2);
$this->objpdf->SetFont('Arial','B',8);
$this->objpdf->SetX(10-$iAjusteColunaX);
$this->objpdf->Cell(80-$iAjusteColunaX,4,$this->tipodebito,0,0,"C",0);
$this->objpdf->SetFont('Arial','B',6);


$this->objpdf->Cell(03+7,4,'1ª Via Contribuinte',0,0,"R",0);
$this->objpdf->SetFont('Arial','B',8);
$this->objpdf->SetX(105-$iAjusteColunaX);
$this->objpdf->Cell(90-$iAjusteColunaX,4,$this->tipodebito,0,0,"C",0);
$this->objpdf->SetFont('Arial','B',6);
$this->objpdf->Cell(05+7,4,'2ª Via Prefeitura',0,1,"R",0);

$y = $this->objpdf->GetY()-1;
$this->objpdf->Image('imagens/files/'.$this->logo,8,$y-14,8);
$this->objpdf->Image('imagens/files/'.$this->logo,95,$y-14,8);
$this->objpdf->SetFont('Times','',5);
$this->objpdf->RoundedRect(10-$iAjusteColunaX,$y+1,39,6,2,'DF','1234'); // matricula/ inscrição
$this->objpdf->RoundedRect(50-$iAjusteColunaX,$y+1,20,6,2,'DF','1234'); // cod. de arrecadação
$this->objpdf->RoundedRect(71-$iAjusteColunaX,$y+1,12,6,2,'DF','1234'); // parcela
//$this->objpdf->RoundedRect(85,$y+1,06,40,2,'DF','1234'); // tste


//die($this->descr6." -- ".$this->dtparapag);

/* se ja vencida coloca data para pagamento  */

//    $venc = substr($this->descr6,6,4)."-".substr($this->descr6,3,2)."-".substr($this->descr6,0,2);
//    if($this->dtparapag > $venc && $this->confirmdtpag == 't'){
  //        echo("if <br>");
  $this->objpdf->SetFont('Arial','B',6);
  $this->objpdf->Text(165-$iAjusteColunaX,$y-3,"Data para pagamento : ".$this->dtparapag);
  $this->objpdf->Text(58 -$iAjusteColunaX,$y-3,"Data para pagamento : ".$this->dtparapag);
  $this->objpdf->SetFont('Times','',5);

//    }else{
  //        echo("else <br>");
//:   }


$this->objpdf->RoundedRect(10-$iAjusteColunaX,$y+8,73,12,2,'DF','1234'); // nome / endereço

$this->objpdf->RoundedRect(10-$iAjusteColunaX,$y+21,73,14,2,'DF','1234'); // instruçoes

$this->objpdf->RoundedRect(10-$iAjusteColunaX,$y+36,39,7,2,'DF','1234'); // vencimento
$this->objpdf->RoundedRect(50-$iAjusteColunaX,$y+36,33,7,2,'DF','1234'); // valor

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13-$iAjusteColunaX,$y+3,$this->titulo1); // matricula/ inscrição
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(13-$iAjusteColunaX,$y+6,$this->descr1); // numero da matricula ou inscricao

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(52-$iAjusteColunaX,$y+3,$this->titulo2); // cod. de arrecadação
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(52-$iAjusteColunaX,$y+6,$this->descr2); // numpre

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(74-$iAjusteColunaX,$y+3,$this->titulo5); // Parcela
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(75-$iAjusteColunaX,$y+6,$this->descr5); // Parcela inicial e total de parcelas

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13-$iAjusteColunaX,$y+10,$this->titulo3); // contribuinte/endereço
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(13-$iAjusteColunaX,$y+13,substr($this->descr3_1, 0, 45)."..."); // nome do contribuinte
$this->objpdf->Text(13-$iAjusteColunaX,$y+16,$this->descr3_2); // endereço
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(13-$iAjusteColunaX,$y+19,substr($this->descr17,0,75)); // SQL

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13-$iAjusteColunaX,$y+23,$this->titulo4); // Instruções

$this->objpdf->SetFont('Arial','B',7);
$xx = $this->objpdf->getx();
$yy = $this->objpdf->gety();

$this->objpdf->setleftmargin(10-$iAjusteColunaX);
$this->objpdf->setrightmargin(120-$iAjusteColunaX);
$this->objpdf->sety($y+23);
$this->objpdf->multicell(70-$iAjusteColunaX,3,$this->descr4_1); // Instruções 1 - linha 1
$this->objpdf->multicell(70-$iAjusteColunaX,3,$this->descr4_2); // Instruções 1 - linha 2
$this->objpdf->setxy($xx,$yy-2);

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(13-$iAjusteColunaX,$y+38,$this->titulo6); // Vencimento
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(20-$iAjusteColunaX,$y+41,$this->descr6); // Data de Vencimento

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(53-$iAjusteColunaX,$y+38,$this->titulo7); // valor
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(56-$iAjusteColunaX,$y+41,$this->descr7); // qtd de URM ou valor


$this->objpdf->RoundedRect(95-$iAjusteColunaX,$y+1,40,6,2,'DF','1234'); // matricula / inscricao
$this->objpdf->RoundedRect(136-$iAjusteColunaX,$y+1,20,6,2,'DF','1234'); // cod. arrecadacao
$this->objpdf->RoundedRect(157-$iAjusteColunaX,$y+1,20,6,2,'DF','1234'); // parcela
$this->objpdf->RoundedRect(178-$iAjusteColunaX,$y+1,23,6,2,'DF','1234'); // livre

$this->objpdf->RoundedRect(95-$iAjusteColunaX,$y+8,82,13,2,'DF','1234'); // nome / endereco
$this->objpdf->RoundedRect(95-$iAjusteColunaX,$y+22,106,13,2,'DF','1234'); // instrucoes

$this->objpdf->RoundedRect(178-$iAjusteColunaX,$y+8,23,6,2,'DF','1234'); // vencimento
$this->objpdf->RoundedRect(178-$iAjusteColunaX,$y+15,23,6,2,'DF','1234'); // valor


$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(97-$iAjusteColunaX,$y+3,$this->titulo8); // matricula / inscricao
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(97-$iAjusteColunaX,$y+6,$this->descr8); // numero da matricula ou inscricao

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(138-$iAjusteColunaX,$y+3,$this->titulo9); // cod. de arrecadação
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(138-$iAjusteColunaX,$y+6,$this->descr9); // numpre

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(161-$iAjusteColunaX,$y+3,$this->titulo10); // parcela
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(162-$iAjusteColunaX,$y+6,$this->descr10); // parcela e total das parcelas

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(180-$iAjusteColunaX,$y+3,$this->titulo13); // livre
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(183-$iAjusteColunaX,$y+6,$this->descr13); // livre

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(97-$iAjusteColunaX,$y+10,$this->titulo11); // contribuinte / endereço
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(97-$iAjusteColunaX,$y+13,$this->descr11_1); // nome do contribuinte
$this->objpdf->Text(97-$iAjusteColunaX,$y+16,$this->descr11_2); // endereço
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(97-$iAjusteColunaX,$y+19,substr($this->descr17,0,92)); // SQL

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(97-$iAjusteColunaX,$y+24,$this->titulo12); // instruções
$this->objpdf->SetFont('Arial','B',7);

$xx = $this->objpdf->getx();
$yy = $this->objpdf->gety();
$this->objpdf->setleftmargin(97-$iAjusteColunaX);
$this->objpdf->setrightmargin(2);
$this->objpdf->sety($y+25);

// mensagem de instruções da guia prefeitura
$this->objpdf->SetFont('Arial','B',5);
$this->objpdf->multicell(100-$iAjusteColunaX,2,substr($this->descr12_1,0,274)); // Instruções 2 - linha 1
$this->objpdf->multicell(100-$iAjusteColunaX,2,$this->descr12_2); // Instruções 2 - linha 2
$this->objpdf->setxy($xx,$yy);

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(180-$iAjusteColunaX,$y+10,$this->titulo14); // vencimento
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(180-$iAjusteColunaX,$y+13,$this->descr14); // data de vencimento

$this->objpdf->SetFont('Arial','',5);
$this->objpdf->Text(180-$iAjusteColunaX,$y+17,$this->titulo15); // valor
$this->objpdf->SetFont('Arial','B',7);
$this->objpdf->Text(180-$iAjusteColunaX,$y+20,$this->descr15); // total de URM ou valor

$this->objpdf->SetLineWidth(0.05);
$this->objpdf->SetDash(1,1);
$this->objpdf->Line(93-$iAjusteColunaX,$y-25,93-$iAjusteColunaX,$y+55); // linha tracejada vertical
$this->objpdf->SetDash();
$this->objpdf->Ln(70);
$this->objpdf->SetFillColor(0,0,0);
$this->objpdf->SetFont('Arial','',10);

$this->objpdf->SetFont('Arial','',4);
$this->objpdf->TextWithDirection(2,$y+30,$this->texto,'U'); // texto no canhoto do carne
$this->objpdf->TextWithDirection(85-$iAjusteColunaX,$y+35,'A U T E N T I C A C A O   M E C Â N I C A','U'); // texto no canhoto do carne
$this->objpdf->TextWithDirection(203-$iAjusteColunaX,$y+35,'A U T E N T I C A C A O   M E C Â N I C A','U'); // texto no canhoto do carne
$this->objpdf->SetFont('Arial','',7);

// mensagem do canto inferior esquerdo da guia do contribuinte
$this->objpdf->Text(10-$iAjusteColunaX,$y+46,$this->descr16_1); //
$this->objpdf->Text(10-$iAjusteColunaX,$y+50,$this->descr16_2); //
$this->objpdf->Text(10-$iAjusteColunaX,$y+54,$this->descr16_3); //
if ($this->linha_digitavel != null) {
  $this->objpdf->Text(105-$iAjusteColunaX,$y+38,$this->linha_digitavel);
}
if ($this->codigo_barras != null) {
  $this->objpdf->int25(95-$iAjusteColunaX,$y+39,$this->codigo_barras,15,0.33);
}

$this->objpdf->SetLineWidth(0.05);
$this->objpdf->SetDash(1,1);
$this->objpdf->Line(0,$this->objpdf->gety()-13, $this->objpdf->w -$iAjusteColunaX,$this->objpdf->gety()-13); // linha tracejada vertical
$this->objpdf->SetDash();

?>
