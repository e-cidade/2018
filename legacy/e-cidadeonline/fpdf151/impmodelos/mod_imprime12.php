<?php


$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(126,$xlin-13,'NOTA DE ANULAÇÃO N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-13,db_formatar($this->notaanulacao,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);

	$this->objpdf->text(134,$xlin-3,'        EMPENHO : ');
	$this->objpdf->text(175,$xlin-3,trim($this->codemp)."/".$this->anousu);



        $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);

        /// retangulo dos dados da dotação
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+7,'Órgao');
	$this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	$this->objpdf->text($xcol+2,$xlin+15,'Função');
	
	$this->objpdf->text($xcol+2,$xlin+22,'Proj/Ativ');
	$this->objpdf->text($xcol+2,$xlin+30,'Rubrica');
	$this->objpdf->text($xcol+2,$xlin+42,'Recurso');
	$this->objpdf->text($xcol+2,$xlin+48,'Licitação');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	$this->objpdf->text($xcol+17,$xlin+22,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	
	$this->objpdf->text($xcol+17,$xlin+30,':  '.db_formatar($this->sintetico,'elemento'));
	$this->objpdf->setxy($xcol+18,$xlin+31);
	$this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");
	
	$this->objpdf->text($xcol+17,$xlin+42,':  '.$this->recurso.' - '.$this->descr_recurso);
	
	$this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);
	
	
        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
	$this->objpdf->text($xcol+107,$xlin+11,'Nome');
	$this->objpdf->text($xcol+107,$xlin+15,'Endereço');
	$this->objpdf->text($xcol+107,$xlin+19,'Município');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
	$this->objpdf->text($xcol+124,$xlin+11,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+15,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+19,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	
	///// retangulo dos valores
	$this->objpdf->rect($xcol+106,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+157,$xlin+23.5,'Valor Empenhado');
	$this->objpdf->text($xcol+108,$xlin+34.0,'Valor Orçado');
	$this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
	$this->objpdf->text($xcol+108,$xlin+44.5,'Valor Anulado');
	$this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+108,$xlin+27,'SEQ. EMP. N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	$this->objpdf->text($xcol+180,$xlin+27.5,db_formatar($this->empenhado,'f'));
	$this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
	$this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
	$this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->anulado,'f'));
	$this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant + $this->anulado,'f'));
	
        /// retangulo do corpo do empenho 
	$this->objpdf->rect($xcol,$xlin+60,15,130,2,'DF','');
	$this->objpdf->rect($xcol+15,$xlin+60,137,130,2,'DF','');
	$this->objpdf->rect($xcol+152,$xlin+60,25,163,2,'DF','');
	$this->objpdf->rect($xcol+177,$xlin+60,25,163,2,'DF','');
	$this->objpdf->rect($xcol,$xlin+190,152,33,2,'DF','');
	
	//// retangulos do titulo do corpo do empenho
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
	$this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
	$this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

	//// título do corpo do empenho
	$this->objpdf->text($xcol+2,$xlin+58,'QUANT');
	$this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+154,$xlin+58,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
        $maiscol = 0;
	
	/// monta os dados para itens do empenho
        $this->objpdf->SetWidths(array(15,137,25,25));
	$this->objpdf->SetAligns(array('C','L','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+62);
	$this->objpdf->Setfont('Arial','',7);
        $ele = 0;
	$xtotal = 0;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  $this->objpdf->Setfont('Arial','B',7);
          $this->objpdf->Row(array('',
	  			   db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),
				   '',
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	  $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
 /////// troca de pagina
	    
	}

        if ($pagina == 1){
           $this->objpdf->rect($xcol,$xlin+223,152,6,2,'DF','34');
           $this->objpdf->rect($xcol+152,$xlin+223,25,6,2,'DF','34');
           $this->objpdf->rect($xcol+177,$xlin+223,25,6,2,'DF','34');
	   
//           $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	   
	   
//	   $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->SetFont('Arial','',7);
	   $this->objpdf->text($xcol+2,$xlin+227,'DESTINO : ',0,1,'L',0);
	   $this->objpdf->text($xcol+30,$xlin+227,$this->destino,0,1,'L',0);
	  
	   $this->objpdf->setxy($xcol+1,$xlin+195);
	   $this->objpdf->text($xcol+2,$xlin+194,'MOTIVO : ',0,1,'L',0);
	   $this->objpdf->multicell(147,3.5,$this->resumo);
	   
	   $this->objpdf->text($xcol+159,$xlin+227,'T O T A L',0,1,'L',0);
	   $this->objpdf->setxy($xcol+185,$xlin+222);
	   $this->objpdf->cell(30,10,db_formatar($this->empenhado - $xtotal,'f'),0,0,'f');
/*
           $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	   $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	   $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	   $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
	   
	   $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	   $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	   $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
	   
	   $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	   
	   $this->objpdf->SetFont('Arial','',6);
	   $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	   $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
	   $this->objpdf->text($xcol+19,$xlin+227,'TÉCNICO CONTÁBIL');
	   $this->objpdf->text($xcol+13,$xlin+240,'SECRETÁRIO(A) DA FAZENDA');
	   
	   $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	   $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
	  
	   $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	   $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	   $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	   $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	   $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	   $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
	  
           $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
	   $this->objpdf->SetFont('Arial','',7);
	   $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
	   $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
	   $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
	   $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
	   $this->objpdf->text($xcol+2,$xlin+261,'R$');
	   $this->objpdf->text($xcol+102,$xlin+261,'R$');
	   $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
	   $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
	   $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
	   $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
	   $this->objpdf->SetFont('Arial','',6);
	   $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
	   $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);
	   
	   $this->objpdf->SetFont('Arial','',4);
           $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
	   $this->objpdf->setfont('Arial','',11);
*/
           $xlin = 169;
        }



?>
