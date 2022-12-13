<?php
   if ( ($this->qtdcarne % 4 ) == 0 ){
           $this->objpdf->AddPage();
        }
	$this->objpdf->SetLineWidth(0.05);
        $this->qtdcarne += 1;
        $top = $this->objpdf->GetY()-5;
        $this->objpdf->SetFont('Arial','B',8);
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFillColor(250,250,250);
	$this->objpdf->SetX(17);
	$this->objpdf->Text(17,$top,$this->prefeitura,0,0,"L",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Text(105,$top,$this->prefeitura,0,1,"L",0);
	$this->objpdf->SetX(170);
	$this->objpdf->SetX(17);
	$this->objpdf->SetFont('Arial','',7);
	$this->objpdf->Text(17,$top+3,$this->secretaria,0,0,"L",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Text(105,$top+3,$this->secretaria,0,1,"L",0);
	$this->objpdf->Ln(2);
	$this->objpdf->SetFont('Arial','B',8);
	$this->objpdf->SetX(10);
	$this->objpdf->Cell(90,4,$this->tipodebito,0,0,"C",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Cell(90,4,$this->tipodebito,0,1,"C",0);
	$y = $this->objpdf->GetY()-1;
	$this->objpdf->Image('imagens/files/'.$this->logo,8,$y-14,8);
	$this->objpdf->Image('imagens/files/'.$this->logo,95,$y-14,8);
	$this->objpdf->SetFont('Times','',5);
	$this->objpdf->RoundedRect(10,$y+1,32,6,2,'DF','1234'); // matricula/ inscrição
	$this->objpdf->RoundedRect(43,$y+1,27,6,2,'DF','1234'); // cod. de arrecadação
	$this->objpdf->RoundedRect(71,$y+1,20,6,2,'DF','1234'); // parcela

	$this->objpdf->RoundedRect(10,$y+8,81,12,2,'DF','1234'); // nome / endereço
	
	$this->objpdf->RoundedRect(10,$y+21,81,14,2,'DF','1234'); // instruçoes

	$this->objpdf->RoundedRect(10,$y+36,39,7,2,'DF','1234'); // vencimento
	$this->objpdf->RoundedRect(50,$y+36,41,7,2,'DF','1234'); // valor

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+3,$this->titulo1); // matricula/ inscrição
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(13,$y+6,$this->descr1); // numero da matricula ou inscricao

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(45,$y+3,$this->titulo2); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(47,$y+6,$this->descr2); // numpre
	
        $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(73,$y+3,$this->titulo5); // Parcela
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(76,$y+6,$this->descr5); // Parcela inicial e total de parcelas

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+10,$this->titulo3); // contribuinte/endereço
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(13,$y+13,$this->descr3_1); // nome do contribuinte
	$this->objpdf->Text(13,$y+16,$this->descr3_2); // endereço

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+23,$this->titulo4); // Instruções
	$this->objpdf->SetFont('Arial','B',7);
        $xx = $this->objpdf->getx();
        $yy = $this->objpdf->gety();
        $this->objpdf->setleftmargin(10);
        $this->objpdf->setrightmargin(120);
        $this->objpdf->sety($y+23);
        $this->objpdf->multicell(0,3,$this->descr4_1); // Instruções 1 - linha 1
        $this->objpdf->multicell(0,3,$this->descr4_2); // Instruções 1 - linha 2
        $this->objpdf->setxy($xx,$yy);

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+38,$this->titulo6); // Vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(20,$y+41,$this->descr6); // Data de Vencimento

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(53,$y+38,$this->titulo7); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(56,$y+41,$this->descr7); // qtd de URM ou valor
	  
	
	$this->objpdf->RoundedRect(95,$y+1,33,6,2,'DF','1234'); // matricula / inscricao
	$this->objpdf->RoundedRect(129,$y+1,27,6,2,'DF','1234'); // cod. arrecadacao
	$this->objpdf->RoundedRect(157,$y+1,20,6,2,'DF','1234'); // parcela
	$this->objpdf->RoundedRect(178,$y+1,31,6,2,'DF','1234'); // livre
	
	$this->objpdf->RoundedRect(95,$y+8,82,13,2,'DF','1234'); // nome / endereco
	$this->objpdf->RoundedRect(95,$y+22,114,13,2,'DF','1234'); // instrucoes
	
	$this->objpdf->RoundedRect(178,$y+8,31,6,2,'DF','1234'); // vencimento
	$this->objpdf->RoundedRect(178,$y+15,31,6,2,'DF','1234'); // valor
	
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+3,$this->titulo8); // matricula / inscricao
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+6,$this->descr8); // numero da matricula ou inscricao
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(131,$y+3,$this->titulo9); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(133,$y+6,$this->descr9); // numpre
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(159,$y+3,$this->titulo10); // parcela
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(162,$y+6,$this->descr10); // parcela e total das parcelas
	
        $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+3,$this->titulo13); // livre
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(183,$y+6,$this->descr13); // livre
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+10,$this->titulo11); // contribuinte / endereço
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+13,$this->descr11_1); // nome do contribuinte
	$this->objpdf->Text(97,$y+16,$this->descr11_2); // endereço
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+24,$this->titulo12); // instruções
	$this->objpdf->SetFont('Arial','B',7);
        $xx = $this->objpdf->getx();
        $yy = $this->objpdf->gety();
        $this->objpdf->setleftmargin(97);
        $this->objpdf->setrightmargin(2);
        $this->objpdf->sety($y+24);
        $this->objpdf->multicell(0,3,$this->descr12_1); // Instruções 2 - linha 1
        $this->objpdf->multicell(0,3,$this->descr12_2); // Instruções 2 - linha 2
        $this->objpdf->setxy($xx,$yy);
		
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+10,$this->titulo14); // vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+13,$this->descr14); // data de vencimento
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+17,$this->titulo15); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+20,$this->descr15); // total de URM ou valor

	$this->objpdf->SetLineWidth(0.05);
	$this->objpdf->SetDash(1,1);
    $this->objpdf->Line(93,$y-30,93,$y+60); // linha tracejada vertical
 	$this->objpdf->SetDash(); 
	$this->objpdf->Ln(70);
	$this->objpdf->SetFillColor(0,0,0);
	$this->objpdf->SetFont('Arial','',10);

        $this->objpdf->SetFont('Arial','',4);
        $this->objpdf->TextWithDirection(2,$y+30,$this->texto,'U'); // texto no canhoto do carne

	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text(10,$y+46,$this->descr16_1); // 
	$this->objpdf->Text(10,$y+50,$this->descr16_2); // 
	$this->objpdf->Text(10,$y+54,$this->descr16_3); // 
	$this->objpdf->Text(105,$y+38,$this->linha_digitavel);
	$this->objpdf->int25(95,$y+39,$this->codigo_barras,15,0.341);

?>
