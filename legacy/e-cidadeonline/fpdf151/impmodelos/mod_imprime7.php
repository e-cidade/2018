<?php

for($xxx = 0;$xxx < $this->nvias;$xxx++){	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	$ano = $this->ano;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(128,$xlin-13,'ORDEM DE PAGAMENTO N'.CHR(176).': ');
	$this->objpdf->text(177,$xlin-13,db_formatar($this->ordpag,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);

        /// retangulo dos dados da dota��o
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,39,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	//if($ano < 2005){
        //  $this->objpdf->text($xcol+2,$xlin+19,'RESTOS A PAGAR ');
	//}else{
	  $this->objpdf->text($xcol+2,$xlin+7,'�rgao');
	  $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	  $this->objpdf->text($xcol+2,$xlin+15,'Fun��o');
	
	  $this->objpdf->text($xcol+2,$xlin+19,'Proj/Ativ');
	  $this->objpdf->text($xcol+2,$xlin+23,'Dota��o');
	  $this->objpdf->text($xcol+2,$xlin+27,'Elemento');
	  $this->objpdf->text($xcol+2,$xlin+34,'Recurso');
	
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	  $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	  $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	  $this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	  $this->objpdf->text($xcol+17,$xlin+23,':  '.$this->dotacao);
	  $this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->elemento,'elemento'));
	  $this->objpdf->text($xcol+17,$xlin+30,'   '.$this->descr_elemento);
	  $this->objpdf->text($xcol+17,$xlin+34,':  '.$this->recurso.' - '.$this->descr_recurso);
	//}
        if($ano < db_getsession("DB_anousu")){
          $this->objpdf->text($xcol+2,$xlin+38,'RESTOS A PAGAR ');
	}
	
      
        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,27,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+9,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+9,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+107,$xlin+13,'Nome');
	$this->objpdf->text($xcol+107,$xlin+17,'Endere�o');
	$this->objpdf->text($xcol+107,$xlin+21,'Munic�pio');
	$this->objpdf->text($xcol+107,$xlin+25,'Banco/Ag./Conta');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+9,': '.$this->numcgm);
	$this->objpdf->text($xcol+157,$xlin+9,' :  '.$this->cnpj);
	$this->objpdf->text($xcol+124,$xlin+13,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+17,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+21,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	if($this->banco != null){
	  $agenciadv = "";
	  $contadv = "";
	  if(trim($this->agenciadv)!=""){
	    $agenciadv = "-".$this->agenciadv;
	  }
	  if(trim($this->contadv)!=""){
	    $contadv = "-".$this->contadv;
	  }
	  $this->objpdf->text($xcol+131,$xlin+25,': '.$this->banco.' / '.$this->agencia.$agenciadv.' / '.$this->conta.$contadv);
	}
	
	///// retangulo do empenho
	$this->objpdf->rect($xcol+106,$xlin+32,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32,47,9,2,'DF','1234');
	
        ///// retangulo dos itens	
        $this->objpdf->rect($xcol+102,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+127,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+152,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+177,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+000,$xlin+ 98,102,24,2,'DF','34');
        $this->objpdf->rect($xcol+000,$xlin+ 48,102,50,2,'DF','12');
        $this->objpdf->rect($xcol+102,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+127,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+152,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+177,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+102,$xlin+105, 75,17,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+105, 25,17,2,'DF','34');

        ///// retangulo das reten��es
        $this->objpdf->rect($xcol+177,$xlin+179, 25, 8,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+171, 25, 8,2,'DF','');
        $this->objpdf->rect($xcol+000,$xlin+133, 75,46,2,'DF','12');
        $this->objpdf->rect($xcol+000,$xlin+179, 75, 8,2,'DF','34');
        $this->objpdf->rect($xcol+75 ,$xlin+133, 25,46,2,'DF','12');
        $this->objpdf->rect($xcol+75 ,$xlin+179, 25, 8,2,'DF','34');
        $this->objpdf->rect($xcol+102,$xlin+133, 75,38,2,'DF','12');
        $this->objpdf->rect($xcol+102,$xlin+171, 75, 8,2,'DF','');
        $this->objpdf->rect($xcol+102,$xlin+179, 75, 8,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+133, 25,38,2,'DF','12');
//        $this->objpdf->Roundedrect($xcol+177,$xlin+179, 25,5,2,'DF','34');
 

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+34,'Empenho N'.chr(176));
	$this->objpdf->text($xcol+157,$xlin+34,'Valor do Empenho');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+130,$xlin+38,db_formatar($this->numemp,'s','0',6,'e'));
	$this->objpdf->text($xcol+180,$xlin+38,db_formatar($this->empenhado,'f'));
	
	//// retangulos do titulo do corpo do empenho
//	$this->objpdf->line($xcol,$xlin+42,$xcol+202,$xlin+42);

	
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text($xcol+2,$xlin+46,'Dados da Ordem de Pagto.');
	$this->objpdf->Setfont('Arial','B',6);
	
	//// t�tulo do corpo do empenho
        $maiscol = 0;
	
	/// monta os dados dos elementos da ordem de compra
        $this->objpdf->SetWidths(array(20,80,25,25,25,25));
		$this->objpdf->SetAligns(array('L','L','R','R','R','R'));
		$this->objpdf->setleftmargin(4);
		$this->objpdf->sety($xlin+48);
		$this->objpdf->cell(20,4,'ELEMENTO',0,0,"L");
		$this->objpdf->cell(80,4,'DESCRI��O',0,0,"L");
		$this->objpdf->cell(25,4,'VALOR',0,0,"R");
		$this->objpdf->cell(25,4,'ANULADO',0,0,"R");
		$this->objpdf->cell(25,4,'PAGO',0,0,"R");
		$this->objpdf->cell(25,4,'SALDO',0,1,"R");
		$this->objpdf->Setfont('Arial','',7);
        $total_pag = 0;
        $total_emp = 0;
        $total_anu = 0;
        $total_sal = 0;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  
	  $this->objpdf->Setfont('Arial','',7);
          $this->objpdf->Row(array(
	                           (pg_result($this->recorddositens,$ii,$this->elementoitem)),
				   (pg_result($this->recorddositens,$ii,$this->descr_elementoitem)),
	                           db_formatar(pg_result($this->recorddositens,$ii,$this->vlremp),'f'),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlranu),'f'), 
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlrpag),'f'),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlrsaldo),'f')),3,false,3);
          $total_emp  += pg_result($this->recorddositens,$ii,$this->vlremp);
          $total_anu  += pg_result($this->recorddositens,$ii,$this->vlranu);
	  $total_pag  += pg_result($this->recorddositens,$ii,$this->vlrpag);
          $total_sal  += pg_result($this->recorddositens,$ii,$this->vlrsaldo);
	}


	/// monta os dados das reten��es da ordem de compra
        $this->objpdf->SetWidths(array(10,62,25));
		$this->objpdf->SetAligns(array('C','L','R'));
		$this->objpdf->setleftmargin(4);
		$this->objpdf->setxy($xcol+102,$xlin+134);
		$this->objpdf->Setfont('Arial','B',10);
		$this->objpdf->text($xcol+104,$xlin+131,'Dados das Reten��es');
		$this->objpdf->text($xcol+2,$xlin+131,'Repasses');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->cell(10,4,'REC.',0,0,"L");
		$this->objpdf->cell(62,4,'DESCRI��O',0,0,"L");
		$this->objpdf->cell(25,4,'VALOR',0,1,"R");
		$this->objpdf->Setfont('Arial','',7);
        $total_ret = 0;
	for($ii = 0;$ii < $this->linhasretencoes ;$ii++) {
	  $this->objpdf->setx($xcol+102);
	  db_fieldsmemory($this->recordretencoes,$ii);
	  $this->objpdf->Setfont('Arial','',7);
          $this->objpdf->Row(array(
	                           pg_result($this->recordretencoes,$ii,$this->receita),
				   pg_result($this->recordretencoes,$ii,$this->dreceita),
				   db_formatar(pg_result($this->recordretencoes,$ii,$this->vlrrec),'f')),3,false,3);
	  $total_ret += pg_result($this->recordretencoes,$ii,$this->vlrrec);
	}





	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->setxy($xcol+100,$xlin+100);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->cell(25,4,db_formatar($total_emp,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_anu,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_pag,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_sal,'f'),0,1,"R");
	

	$this->objpdf->setxy($xcol+127,$xlin+107);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'TOTAL DA ORDEM',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_emp-$total_anu,'f'),0,1,"R");
	$this->objpdf->setx($xcol+127);
	$this->objpdf->cell(50,5,'OUTRAS ORDENS',0,0,"R");
	$this->objpdf->cell(23,4,db_formatar($this->outrasordens,'f'),0,1,"R");
	$this->objpdf->setx($xcol+127);
	$this->objpdf->cell(50,5,'VALOR RESTANTE',0,0,"R");
	$this->objpdf->cell(23,4,db_formatar($this->empenhado - $this->outrasordens - $total_emp - $total_anu ,'f'),0,1,"R");
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+102,'OBSERVA��ES :');
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->setxy($xcol,$xlin+103);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->multicell(102,4,$this->obs);
        
	/// total das reten��es
	$this->objpdf->setxy($xcol+127,$xlin+172);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'TOTAL ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_ret,'f'),0,1,"R");
	
	/// total dos repasses
	$this->objpdf->setxy($xcol,$xlin+181);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(75,5,'TOTAL ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar(0,'f'),0,1,"R");
        
	/// liquido da ordem de compra
	$this->objpdf->setxy($xcol+127,$xlin+181);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'L�QUIDO DA ORDEM DE PAGTO. ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_sal - $total_ret,'f'),0,1,"R");

	
        $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','');
        

        $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
        $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
        $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
        $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
        
        
	
	if($this->municpref == "GUAIBA"){
		    if (db_getsession("DB_instit")!=3){ 
	  	        $this->objpdf->SetFont('Arial','',6);
	            $this->objpdf->line($xcol+12,$xlin+221,$xcol+43,$xlin+221);
	            $this->objpdf->line($xcol+74,$xlin+221,$xcol+100,$xlin+221);
		    
		        $this->objpdf->text($xcol+13,$xlin+224,'JORGE ANTONIO POKORSKI');
		        $this->objpdf->text($xcol+76,$xlin+224,'MANOEL STRINGHINI');
		        $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
	            $this->objpdf->text($xcol+13,$xlin+227,'SECRET�RIO DA FAZENDA');
		   } else {  
                $this->objpdf->SetFont('Arial','',6);
	            $this->objpdf->line($xcol+12,$xlin+221,$xcol+43,$xlin+221);
	            $this->objpdf->line($xcol+74,$xlin+221,$xcol+100,$xlin+221);
		    
		        $this->objpdf->text($xcol+13,$xlin+224,'Aida Maria Kronnhardt');
		        $this->objpdf->text($xcol+76,$xlin+224,'Paulo Henrique Maganha');
		        $this->objpdf->text($xcol+76,$xlin+227,'Diretor Presidente');
	            $this->objpdf->text($xcol+13,$xlin+227,'Diretora Financeira');
  	   	   }

	}else{  
            $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
            $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
            $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	    
	    $this->objpdf->SetFont('Arial','',6);
	    $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	    $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
	    if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
	      $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	      $this->objpdf->text($xcol+19,$xlin+227,'T�CNICO CONT�BIL');
	      $this->objpdf->text($xcol+13,$xlin+240,'SECRET�RIO(A) DA FAZENDA');
	    }
	    $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	    $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
        }



	
       
        $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
        $this->objpdf->text($xcol+170,$xlin+207,'DATA');
        $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
        $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
        $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
        $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
       
        $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
        $this->objpdf->SetFont('Arial','',7);
        $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
        $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNIC�PIO DE '.$this->municpref.', A IMPORT�NCIA ABAIXO ESPECIFICADA, REFERENTE �:');
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
  	$this->objpdf->SetFont('Arial','',6);
        $this->objpdf->Text(200,296,($xxx+1).' via'); // texto no canhoto do carne
	$this->objpdf->setfont('Arial','',11);
        $xlin = 169;
      }   



?>
