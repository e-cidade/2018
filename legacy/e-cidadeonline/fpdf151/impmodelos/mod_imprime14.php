<?php

$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->setleftmargin(4);
	$pagina = 1;
	$xlin = 30;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-28,206,292,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO  N'.CHR(176));
	$this->objpdf->text(165,$xlin-13,db_formatar($this->codaidof,'s','0',6,'e'));
	$this->objpdf->text(180,$xlin-13,"/".$this->ano);
        $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	
	$this->objpdf->Setfont('Arial','b',12);
	$this->objpdf->text($xcol+50,$xlin+25,"AUTORIZAÇÃO DE IMPRESSÃO DE DOCUMENTOS");
	$this->objpdf->text($xcol+60,$xlin+31,"FISCAIS DO IMPOSTO SOBRE SERVIÇOS");
	
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+80,$xlin+45,"ESTABELECIMENTO GRÁFICO");
	$this->objpdf->rect($xcol,$xlin+50,$xcol+198,30,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+50,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+60,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+70,$xcol+96,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+70,$xcol+98,10,2,'','1234');
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+58,"NOME:");
	$this->objpdf->text($xcol+2,$xlin+68,"ENDEREÇO:");
	$this->objpdf->text($xcol+2,$xlin+78,"INSCRIÇÃO MUNICIPAL:");
	$this->objpdf->text($xcol+102,$xlin+78,"INSCRIÇÃO DO CNPJ:");
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+12,$xlin+58,$this->nome_graf);
	$this->objpdf->text($xcol+22,$xlin+68,$this->ender_graf);
	$this->objpdf->text($xcol+38,$xlin+78,$this->inscr_graf);
	$this->objpdf->text($xcol+138,$xlin+78,$this->cnpj_graf);

	$this->objpdf->Setfont('Arial','B',8);
    $this->objpdf->text($xcol+81,$xlin+93,"ESTABELECIMENTO USUÁRIO");
	$this->objpdf->rect($xcol,$xlin+98,$xcol+198,30,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+98,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+108,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+118,$xcol+98,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+118,$xcol+96,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+118,$xcol+98,10,2,'','1234');
    $this->objpdf->text($xcol+2,$xlin+106,"NOME:");
	$this->objpdf->text($xcol+2,$xlin+116,"ENDEREÇO:");
	$this->objpdf->text($xcol+2,$xlin+126,"INSCRIÇÃO MUNICIPAL:");
	$this->objpdf->text($xcol+102,$xlin+126,"INSCRIÇÃO DO CNPJ:");
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+12,$xlin+106,$this->nome_usu);
	$this->objpdf->text($xcol+22,$xlin+116,$this->ender_usu);
	$this->objpdf->text($xcol+38,$xlin+126,$this->inscr_usu);
	$this->objpdf->text($xcol+138,$xlin+126,$this->cnpj_usu);
	
	
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+80,$xlin+141,"DOCUMENTO A SER IMPRESSO");
	$this->objpdf->rect($xcol,$xlin+146,$xcol+198,50,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+146,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+156,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+146,$xcol+65,20,2,'','1234');
	$this->objpdf->rect($xcol+110,$xlin+146,$xcol+88,20,2,'','1234');
        $this->objpdf->text($xcol+25,$xlin+152,"NUMERAÇÃO");
	$this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+20,$xlin+164,$this->notaini."   A   ".$this->notafin);
	$this->objpdf->Setfont('Arial','B',8);
        $this->objpdf->text($xcol+78,$xlin+152,"QUANTIDADE");
        $this->objpdf->text($xcol+150,$xlin+152,"ESPÉCIE");
        $this->objpdf->text($xcol+2,$xlin+171,"OBSERVAÇÕES:");
	$this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+85,$xlin+164,$this->quant);
        $this->objpdf->text($xcol+115,$xlin+164,$this->especie);
	$this->objpdf->SetXY($xcol+2,$xlin+173);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->multicell(198,4,$this->obs,0,"L");   

	$this->objpdf->Setfont('Arial','B',8);
	
	$this->objpdf->text($xcol+125,$xlin+235,"Código de Autenticidade da Certidão");
	$codigobarra=$this->codigo;
	
	//$pdf->MultiCell(180, 15, $t1, 0, "R", 0);
	$this->objpdf->text($xcol+125,$xlin+243,$codigobarra);
	//$this->objpdf->text($xcol+125,$xlin+243,"$cod");
	$this->objpdf->setfillcolor(000);
	$this->objpdf->int25(115,275,$codigobarra, 15, 0.341);
	//$pdf->MultiCell(180, 3, "Código de Autenticidade da Certidão", 0, "R", 0);
	
	/*
	$this->objpdf->text($xcol+40,$xlin+144,"PEDIDO");
	$this->objpdf->rect($xcol,$xlin+145,$xcol+95,80,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+145,$xcol+95,8,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+153,$xcol+95,16,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+169,$xcol+95,8,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+177,$xcol+95,24,2,'','1234');

    $this->objpdf->text($xcol+140,$xlin+144,"ENTREGA");
   	$this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,48,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+153,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+153,$xcol+47,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+161,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+169,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+177,$xcol+95,16,2,'','1234');
	
        $this->objpdf->text($xcol+137,$xlin+197,"REPARTIÇÃO FISCAL");
        $this->objpdf->text($xcol+141,$xlin+203,"AUTORIZAMOS");
	$this->objpdf->rect($xcol+103,$xlin+198,$xcol+95,27,2,'','1234');
	
	$this->objpdf->Setfont('Arial','',7);
        
	$this->objpdf->text($xcol+2,$xlin+151,"DATA:________________DE______________________________DE_____________");
      	$this->objpdf->text($xcol+1,$xlin+156,"NOME DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
      	$this->objpdf->text($xcol+3,$xlin+165,"___________________________________________________________________");
      	$this->objpdf->text($xcol+1,$xlin+172,"DOCUMENTO DE IDENTIDADE");
      	$this->objpdf->text($xcol+1,$xlin+200,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
      	$this->objpdf->text($xcol+1,$xlin+224,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO GRÁFICO");
     	
       
        $this->objpdf->text($xcol+105,$xlin+151,"DATA:________________DE______________________________DE_____________");
        $this->objpdf->text($xcol+104,$xlin+156,"DOC. FISCAL N°.:");
        $this->objpdf->text($xcol+155,$xlin+156,"SÉRIE:");
        $this->objpdf->text($xcol+104,$xlin+164,"RECEBIDO POR");	
        $this->objpdf->text($xcol+104,$xlin+172,"DOCUMENTO DE IDENTIDADE");	
      	$this->objpdf->text($xcol+116,$xlin+192,"ASSINATURA DO RESPONSÁVEL PELO RECEBIMENTO");
        
	$this->objpdf->text($xcol+105,$xlin+211,"EM________________DE______________________________DE_____________");
      	$this->objpdf->text($xcol+115,$xlin+224,"ASSINATURA E CARIMBO DA AUTORIDADE COMPETENTE");
	
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+90,$xlin+229,"IMPORTANTE");
	$this->objpdf->rect($xcol,$xlin+230,$xcol+198,30,2,'','1234');
        $this->objpdf->Setfont('Arial','b',7);	 
        $this->objpdf->text($xcol+10,$xlin+236,"NO RODAPÉ DAS NOTAS FISCAIS DEVERÁ CONSTAR OBRIGATORIAMENTE:");
        $this->objpdf->text($xcol+10,$xlin+242,"- A QUANTIDADE DE TALÕES IMPRESSOS:");
        $this->objpdf->text($xcol+10,$xlin+246,"- A NUMERAÇÃO:");
        $this->objpdf->text($xcol+10,$xlin+250,"- O NUMERO DA AUTORIZAÇÃO PARA IMPRESSÃO:");
        $this->objpdf->text($xcol+10,$xlin+254,"- O CNPJ E O NÚMERO DE INSCRIÇÃO MUNICIPAL DA GRÁFICA:");
        */
?>