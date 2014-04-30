<?php
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->Setfont('Arial','B',12);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->roundedrect(05,05,200,288,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
	$this->objpdf->Image('imagens/files/'.$this->logo,45,9,20);
	$this->objpdf->text(70,15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',12);
	$this->objpdf->text(70,20,$this->enderpref);
	$this->objpdf->text(70,25,$this->municpref);
	$this->objpdf->text(70,30,$this->telefpref);
	$this->objpdf->text(70,35,$this->emailpref);

	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(15,45,110,35,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(16,47,'Identificação:');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(16,51,'Nome :');
	$this->objpdf->text(32,51,$this->nome);
	$this->objpdf->text(16,56,'Endereço :');
	$this->objpdf->text(32,56,$this->ender);
	$this->objpdf->text(16,60,'Município :');
	$this->objpdf->text(32,60,$this->munic);
	$this->objpdf->text(16,64,'CEP :');
	$this->objpdf->text(32,64,$this->cep);
	$this->objpdf->text(16,68,'Data :');
	$this->objpdf->text(32,68,date('d/m/Y'));
	$this->objpdf->text(50,68,'Hora: '.date("H:i:s"));
	$this->objpdf->text(16,72,$this->tipoinscr);
	$this->objpdf->text(32,72,$this->nrinscr);
	$this->objpdf->text(16,76,'IP :');
	$this->objpdf->text(32,76,$this->ip);
	$this->objpdf->Setfont('Arial','',6);
	
	$this->objpdf->Roundedrect(130,45,65,35,2,'DF','1234');
	$this->objpdf->text(132,47,$this->tipoinscr);
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,50,$this->nrinscr);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,55,'Logradouro :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,58,$this->nomepri);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,63,'Número/Complemento :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,66,$this->nrpri."      ".$this->complpri);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,71,'Bairro :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,74,$this->bairropri);
	
	$this->objpdf->Setfont('Arial','B',11);
	$this->objpdf->text(70,87,'RECIBO VÁLIDO ATÉ: '.$this->datacalc);
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(15,90,180,65,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',8);
	
	$this->objpdf->SetXY(17,96);
	if($this->taxabanc!=0){
	  $this->objpdf->Cell(20,4,'Taxa Bancária',0,0,"L",0);
	  $this->objpdf->Cell(20,4,db_formatar($this->taxabanc,'f'),0,1,"R",0);
	}
	
	for($i = 0;$i < $this->linhasdadospagto ;$i++) {
	   $this->objpdf->setx(17);
	   $this->objpdf->cell(5,4,trim(pg_result($this->recorddadospagto,$i,$this->receita)),0,0,"C",0);
           if ( trim(pg_result($this->recorddadospagto,$i,$this->ddreceita) ) == ''){
     		$this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->dreceita)),0,0,"L",0);
           }else{ 
		$this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->ddreceita)),0,0,"L",0);
           }
	   $this->objpdf->cell(15,4,db_formatar(pg_result($this->recorddadospagto,$i,$this->valor),'f'),0,1,"R",0);
	}
	$this->objpdf->SetXY(15,158);
	$this->objpdf->multicell(0,4,'HISTÓRICO :   '.$this->historico);
	$this->objpdf->setx(15);
	$this->objpdf->multicell(0,4,$this->histparcel);
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Roundedrect(10,195,190,46,2,'DF','1234');
	
	$this->objpdf->setfont('Arial','',6);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(40,200,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(93,200,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(146,200,48,10,2,'DF','1234');
	$this->objpdf->text(42,202,'Vencimento');
	$this->objpdf->text(95,202,'Código de Arrecadação');
	$this->objpdf->text(148,202,'Valor a Pagar');
	$this->objpdf->setfont('Arial','',10);
	$this->objpdf->text(48,207,$this->dtvenc);
	$this->objpdf->text(101,207,$this->numpre);
	$this->objpdf->text(153,207,$this->valtotal);
	
	$this->objpdf->SetDash(0.8,0.8);
	$this->objpdf->line(5,242.5,205,242.5);
	$this->objpdf->SetDash();
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Roundedrect(10,244,190,46,2,'DF','1234');
	$this->objpdf->setfont('Arial','',12);
	$this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->Image('imagens/files/'.$this->logo,12,200,25);
	$this->objpdf->text(60,218,$this->linhadigitavel);
	$this->objpdf->int25(60,220,$this->codigobarras,15,0.341);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(40,250,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(93,250,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(146,250,48,10,2,'DF','1234');
	$this->objpdf->setfont('Arial','',6);
	$this->objpdf->text(42,252,'Vencimento');
	$this->objpdf->text(95,252,'Código de Arrecadação');
	$this->objpdf->text(148,252,'Valor a Pagar');
	$this->objpdf->setfont('Arial','',10);
	$this->objpdf->text(48,257,$this->dtvenc);
	$this->objpdf->text(101,257,$this->numpre);
	$this->objpdf->text(153,257,$this->valtotal);
	$this->objpdf->Image('imagens/files/'.$this->logo,12,250,25);
    $this->objpdf->SetFont('Arial','',5);
    $this->objpdf->text(10,$this->objpdf->h-2,'Base: '.db_base_ativa());
 	$this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->setfont('Arial','',12);
	$this->objpdf->text(60,268,$this->linhadigitavel);
	$this->objpdf->int25(60,270,$this->codigobarras,15,0.341);




?>
