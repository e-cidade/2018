<?php

//  MODELO ALTERNATIVO DE CARNE COM DETALHES DE JUROS E MULTA

	if (($this->qtdcarne % 3 ) == 0 ){
        $this->objpdf->AddPage();
    }
	
/**************************************************************************************/
//////////////////   G U I A   D O   C O N T R I B U I N T E   /////////////////////////
/**************************************************************************************/

	$this->objpdf->SetLineWidth(0.05);
    $this->qtdcarne += 1;
    $top = $this->objpdf->GetY();
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
	$this->objpdf->Cell(200,3,"",0,1,"R",0);
	$this->objpdf->SetX(10);
    $this->objpdf->Cell(80,4,$this->tipodebito,0,0,"C",0);
    $this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Cell(03,4,'1ª Via Contribuinte',0,0,"R",0);
	$this->objpdf->SetFont('Arial','B',8);
	$this->objpdf->SetX(105);
	$this->objpdf->Cell(90,4,$this->tipodebito,0,0,"C",0);
    $this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Cell(05,4,'2ª Via Prefeitura',0,1,"R",0);

	$y = $this->objpdf->GetY()-1;
//	$y = $this->objpdf->GetY()+4;
	$this->objpdf->Image('imagens/files/'.$this->logo,8,$y-14,8);
	$this->objpdf->Image('imagens/files/'.$this->logo,95,$y-14,8);
	$this->objpdf->SetFont('Times','',5);
	
	$this->objpdf->RoundedRect(10,$y+1,20,6,2,'DF','1234'); // matricula/ inscrição
	$this->objpdf->RoundedRect(31,$y+1,20,6,2,'DF','1234'); // cod. de arrecadação
	$this->objpdf->RoundedRect(52,$y+1,15,6,2,'DF','1234'); // parcela guia contribuinte

	$this->objpdf->RoundedRect(68,$y+1,17,6,2,'DF','1234'); // vencimento 
	$this->objpdf->RoundedRect(68,$y+8,17,6,2,'DF','1234'); // valor documento 
	$this->objpdf->RoundedRect(68,$y+15,17,6,2,'DF','1234'); // desconto 
	$this->objpdf->RoundedRect(68,$y+22,17,6,2,'DF','1234'); // multa 
	$this->objpdf->RoundedRect(68,$y+29,17,6,2,'DF','1234'); // juros 
	$this->objpdf->RoundedRect(68,$y+36,17,6,2,'DF','1234'); // valor total
	//$this->objpdf->RoundedRect(85,$y+1,06,40,2,'DF','1234'); // vertical

	$this->objpdf->RoundedRect(10,$y+8,57,16,2,'DF','1234'); // nome / endereço
	$this->objpdf->RoundedRect(10,$y+25,57,17,2,'DF','1234'); // instruçoes


	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+3,$this->titulo1); // matricula/ inscrição
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(13,$y+6,$this->descr1); // numero da matricula ou inscricao

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(32,$y+3,$this->titulo2); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(33,$y+6,$this->descr2); // numpre

    $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(53,$y+3,$this->titulo5); // Parcela guia contribuinte
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(55,$y+6,$this->descr5); // Parcela inicial e total de parcelas

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+10,$this->titulo3); // contribuinte/endereço
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(13,$y+13,$this->descr3_1); // nome do contribuinte
	$this->objpdf->Text(13,$y+16,$this->descr3_2); // endereço
	$this->objpdf->Text(40,$y+22,$this->descr17); // SQL

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+27,$this->titulo4); // Instruções

	$this->objpdf->SetFont('Arial','B',7);
    $xx = $this->objpdf->getx();
    $yy = $this->objpdf->gety();

    $this->objpdf->setleftmargin(10);
    $this->objpdf->setrightmargin(120);
    $this->objpdf->sety($y+27);
    $this->objpdf->multicell(0,3,$this->descr4_1); // Instruções 1 - linha 1
    $this->objpdf->multicell(0,3,$this->descr4_2); // Instruções 1 - linha 2
    $this->objpdf->setxy($xx,$yy-1);
	
/**/

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+3,$this->titulo6); // Vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(70,$y+6,$this->descr6); // Data de Vencimento

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+10,"Valor documento"); // Valor do documento
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(69,$y+13,$this->descr7); 

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+17,"Desconto"); // valor do desconto
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(69,$y+20,""); 
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+24,"Multa"); // valor da multa
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(69,$y+27,"");
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+31,"Juros"); // valor do juros
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(69,$y+34,""); 

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(69,$y+38,$this->titulo7." Total"); // valor total
	$this->objpdf->SetFont('Arial','B',6);
	$this->objpdf->Text(69,$y+41,""); 

/**/

/**************************************************************************************/
//////////////////////////   G U I A   D O   B A N C O  ////////////////////////////////
/**************************************************************************************/

	$this->objpdf->RoundedRect(95,$y+1,20,6,2,'DF','1234'); // matricula / inscricao
	$this->objpdf->RoundedRect(116,$y+1,25,6,2,'DF','1234'); // cod. arrecadacao
	$this->objpdf->RoundedRect(142,$y+1,15,6,2,'DF','1234'); // parcela
	$this->objpdf->RoundedRect(158,$y+1,19,6,2,'DF','1234'); // livre

	$this->objpdf->RoundedRect(95,$y+8,82,14,2,'DF','1234'); // nome / endereco
	$this->objpdf->RoundedRect(95,$y+23,82,20,2,'DF','1234'); // instrucoes
	
////////  COLUNA COM OS VALOR DO DOC,DESCONTO, MULTA, JUROS, VLR TOTAL...

	$this->objpdf->RoundedRect(178,$y+1,23,6,2,'DF','1234');  // vencimento
    $this->objpdf->RoundedRect(178,$y+8,23,6,2,'DF','1234');  // valor documento
    $this->objpdf->RoundedRect(178,$y+15,23,6,2,'DF','1234'); // valor desconto
    $this->objpdf->RoundedRect(178,$y+22,23,6,2,'DF','1234'); // valor multa
    $this->objpdf->RoundedRect(178,$y+29,23,6,2,'DF','1234'); // valor juros
    $this->objpdf->RoundedRect(178,$y+36,23,6,2,'DF','1234'); // valor total
/*
$titulo17  = null;
$titulo18  = null;
$descr17   = null;
$descr18   = null;
*/	  


////////////////////////////////////////////////////////////////////////////////////////

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+3,$this->titulo8); // matricula / inscricao
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+6,$this->descr8); // numero da matricula ou inscricao

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(117,$y+3,$this->titulo9); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(119,$y+6,$this->descr9); // numpre

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(143,$y+3,$this->titulo10); // parcela
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(146,$y+6,$this->descr10); // parcela e total das parcelas

    $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(159,$y+3,$this->titulo13); // livre
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(162,$y+6,$this->descr13); // livre

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+10,$this->titulo11); // contribuinte / endereço
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+13,$this->descr11_1); // nome do contribuinte
	$this->objpdf->Text(97,$y+16,$this->descr11_2); // endereço
	$this->objpdf->Text(150,$y+21,$this->descr17); // Setor Quadra Lote

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+25,$this->titulo12); // instruções
	$this->objpdf->SetFont('Arial','B',7);
    $xx = $this->objpdf->getx();
    $yy = $this->objpdf->gety();
    $this->objpdf->setleftmargin(97);
    $this->objpdf->setrightmargin(2);
    $this->objpdf->sety($y+25);

	// mensagem de instruções da guia prefeitura 
    $this->objpdf->multicell(80,3,$this->descr12_1); // Instruções 2 - linha 1
    $this->objpdf->multicell(80,3,$this->descr12_2); // Instruções 2 - linha 2
    $this->objpdf->setxy($xx,$yy);

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+3,$this->titulo14); // vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+6,$this->descr14); // data de vencimento


/**/

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+10,"Valor Documento"); // valor do documento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+13,$this->descr15); // total de URM ou valor

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+17,"Desconto"); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+20,""); // total de URM ou valor

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+24,"Multa"); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+27,""); // total de URM ou valor

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+31,"Juros"); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+34,""); // total de URM ou valor
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+38,$this->titulo15." Total"); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+41,""); // total de URM ou valor

/**/

	$this->objpdf->SetLineWidth(0.05);
	$this->objpdf->SetDash(1,1);
    $this->objpdf->Line(93,$y-10,93,$y+60); // linha tracejada vertical
 	$this->objpdf->SetDash();
	$this->objpdf->Ln(70);
	$this->objpdf->SetFillColor(0,0,0);
	$this->objpdf->SetFont('Arial','',10);

    $this->objpdf->SetFont('Arial','',4);
    $this->objpdf->TextWithDirection(2,$y+30,$this->texto,'U'); // texto no canhoto do carne
    $this->objpdf->TextWithDirection(87,$y+35,'A U T E N T I C A C A O   M E C Â N I C A','U'); // texto no canhoto do carne
    $this->objpdf->TextWithDirection(203,$y+35,'A U T E N T I C A C A O   M E C Â N I C A','U'); // texto no canhoto do carne
	$this->objpdf->SetFont('Arial','',9);
	
	// mensagem do canto inferior esquerdo da guia do contribuinte
	$y += 10;
	$this->objpdf->Text(10,$y+38,$this->descr16_1); //
	$this->objpdf->Text(10,$y+42,$this->descr16_2); //
	$this->objpdf->Text(10,$y+46,$this->descr16_3); //
	$this->objpdf->Text(105,$y+38,$this->linha_digitavel);
	$this->objpdf->int25(95,$y+39,$this->codigo_barras,11,0.341);
	$y += 15;
	$this->objpdf->SetY($y+50);

?>
