<?php

	
     if( strtoupper(trim($this->municpref)) == 'GUAIBA') {

	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	//Inserindo usuario e data no rodape
    $this->objpdf->Setfont('Arial','I',6);
    $this->objpdf->text($xcol+3,$xlin+276,"Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y")."");
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	$this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));

    if($this->Scodemp!=""){
      $this->objpdf->text(137,$xlin-3,'EMPENHO N'.CHR(176));
      $this->objpdf->text(180,$xlin-3,$this->Scodemp);
    }

	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-12,$this->enderpref);
	$this->objpdf->text(40,$xlin- 9,$this->municpref);
	$this->objpdf->text(40,$xlin- 6,$this->telefpref);
	$this->objpdf->text(40,$xlin- 3,$this->emailpref);
	$this->objpdf->text(40,$xlin,db_formatar($this->cgcpref,'cnpj'));

	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+4,'Dados da Compra');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+ 8,'Licitação');
	$this->objpdf->text($xcol+2,$xlin+12,'Tipo de Compra');
	$this->objpdf->text($xcol+2,$xlin+16,'Prazo de Entrega');
	$this->objpdf->text($xcol+2,$xlin+20,'Observações');
	$this->objpdf->text($xcol+2,$xlin+24,'Cond.de Pagto');
	$this->objpdf->text($xcol+2,$xlin+28,'Outras Condições');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+27,$xlin+ 8,':  '.$this->num_licitacao.'  -  '.$this->descr_licitacao);
	$this->objpdf->text($xcol+27,$xlin+12,':  '.$this->descr_tipocompra);
	$this->objpdf->text($xcol+27,$xlin+16,':  '.$this->prazo_ent);
	$this->objpdf->text($xcol+27,$xlin+20,':  '.$this->obs);
	$this->objpdf->text($xcol+27,$xlin+24,':  '.$this->cond_pag);
	$this->objpdf->text($xcol+27,$xlin+28,':  '.$this->out_cond);

	$this->objpdf->rect($xcol+106,$xlin+2,96,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+110,$xlin+4,'Dados da Dotação');
	$this->objpdf->Setfont('Arial','B',8);

	
	$this->objpdf->text($xcol+108,$xlin+ 8,'Dotação');
	$this->objpdf->text($xcol+108,$xlin+11.5,'Órgão');
	$this->objpdf->text($xcol+108,$xlin+15,'Unidade');
	$this->objpdf->text($xcol+108,$xlin+18.5,'Proj/Ativ');
	$this->objpdf->text($xcol+108,$xlin+22,'Elemento');
	$this->objpdf->text($xcol+108,$xlin+25.5,'Recurso');
	$this->objpdf->text($xcol+178,$xlin+25.5,'Reduz');
	$this->objpdf->text($xcol+108,$xlin+29,'Destino');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
	$this->objpdf->text($xcol+122,$xlin+11.5,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+122,$xlin+15,':  '.db_formatar($this->orgao,'orgao').db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+122,$xlin+18.5,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	$this->objpdf->text($xcol+122,$xlin+22,':  '.$this->descrdotacao);
	$this->objpdf->text($xcol+122,$xlin+25.5,':  '.$this->recurso.' - '.$this->descr_recurso);
	$this->objpdf->text($xcol+188,$xlin+25.5,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
	$this->objpdf->text($xcol+122,$xlin+29,':  '.$this->destino);

	$this->objpdf->rect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+  2,$xlin+50,'Contato');
	$this->objpdf->text($xcol+110,$xlin+50,'Telefone');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+159,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	$this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
	$this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telefone);
	
	$this->objpdf->Setfont('Arial','B',8);
//	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
	$this->objpdf->rect($xcol    ,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	$this->objpdf->rect($xcol,    $xlin+60,15,122,2,'DF','34');
	$this->objpdf->rect($xcol+ 15,$xlin+60,20,122,2,'DF','34');
	$this->objpdf->rect($xcol+ 35,$xlin+60,107,122,2,'DF','34');

	$this->objpdf->rect($xcol+142,$xlin+60,30,155,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+60,30,155,2,'DF','34');
	
	$this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	$this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	$this->objpdf->text($xcol+120 ,$xlin+211,'T O T A L');

        $this->objpdf->SetXY(172,$xlin+205);
	$this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");


	$this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	
   	$this->objpdf->sety($xlin+28);
	$alt = 4;
	
	$this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
	$this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	$this->objpdf->text($xcol+  70,$xlin+58,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+58,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+58,'VALOR TOTAL');
        $maiscol = 0;
	
        $this->objpdf->SetWidths(array(10,22,105,30,30));
	$this->objpdf->SetAligns(array('C','C','L','R','R'));
	
	$this->objpdf->setleftmargin(8);
	$this->objpdf->sety($xlin+61);
        $ele = 0;
	$xtotal = 0;

	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  if($this->usa_sub == 'f'){
            $this->objpdf->Setfont('Arial','B',7);
            if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
            {
               $this->objpdf->cell(32,4,'',0,0,"C",0);
               $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
               $ele = pg_result($this->recorddositens,$ii,$this->analitico);
            }
	  }
          $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	  if(pg_result($this->recorddositens,$ii,$this->Snumero)!=""){
            $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".'SOLICITAÇÃO: '.pg_result($this->recorddositens,$ii,$this->Snumero);
	  }
          $this->objpdf->Setfont('Arial','',7);
	 
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   $descricaoitem."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valor),'v'," ",$this->casadec),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	  
	  $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);

	  $this->objpdf->Setfont('Arial','B',8);
 /////// troca de pagina
         
	  if( ( $this->objpdf->gety() > $this->objpdf->h - 100 && $pagina == 1 ) || 
	      ( $this->objpdf->gety() > $this->objpdf->h - 30 && $pagina != 1 )){

            if ($this->objpdf->PageNo() == 1){
	       $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
	       $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');
	       
	       /*
	       //// ASSINATURAS DA AUTORIZACAO
	       $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
	       $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
	       $visto =  "VISTO";
	       
//	       $ass_cont   = $this->assinatura(1006,$cont);
//	       $ass_ord    = $this->assinatura(1002,$ord);
               if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
                  $ass_cont   = $this->assinatura(1006,$cont);
                  $ass_ord    = $this->assinatura(1002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }else{
                  $ass_cont   = $this->assinatura(51006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }
																			     
               $this->objpdf->SetXY(2,$y);

               $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
	       
               $this->objpdf->SetXY(72,$y);
               $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
	       
               $this->objpdf->SetXY(137,$y);
               $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	       */

		$this->objpdf->rect($xcol,$xlin+217,100,55,2,'DF','1234');
		$this->objpdf->rect($xcol+102,$xlin+217,100,55,2,'DF','1234');
		$this->objpdf->setfillcolor(0,0,0);

		$y = 260;


	       $this->objpdf->Setfont('Arial','',6);
	       //// ASSINATURAS DA AUTORIZACAO
	       $cont =  "__________________________________";
	       $ord =   "ORDENADOR DA DESPESA";
	       $visto =  "";
	       
               if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
                  $ass_cont   = $this->assinatura(1006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }else{
                  $ass_cont   = $this->assinatura(51006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }
               $this->objpdf->SetXY(20,$y);
               
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_cont,0,"C",0);
	       
               $this->objpdf->SetXY(122,$y);
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_ord,0,"C",0);
	       
               $this->objpdf->SetXY(137,$y);
	       //////
		
	       
	   $this->objpdf->Setfont('Arial','B',8);
           $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	       
//	   $this->objpdf->SetFont('Arial','',4);
//         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	   $this->objpdf->setfont('Arial','',11);
//         $xlin = 169;
	       //////
	       
               $this->objpdf->setfillcolor(0,0,0);
//	       $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
               $this->objpdf->SetFont('Arial','',4);
               $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
               $this->objpdf->setfont('Arial','',11);


	       if ($pagina == 1){
 		 $this->objpdf->setxy($xcol+1,$xlin+187);
 		 $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
 		 $this->objpdf->Setfont('Arial','',7);
		 $this->objpdf->multicell(140,3.5,$this->resumo);
		 $this->objpdf->Setfont('Arial','B',8);
	       }
	       
            }else{
	       $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
	    }
            $this->objpdf->addpage();
            $pagina += 1;	   
	    
  	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;
	//Inserindo usuario e data no rodape
        $this->objpdf->Setfont('Arial','I',6);
        $this->objpdf->text($xcol+3,$xlin+276,"Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y")."");
	
	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	    $this->objpdf->setfillcolor(255,255,255);
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	    $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	    $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	    $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
	    $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	    $this->objpdf->Setfont('Arial','',9);
	    $this->objpdf->text(40,$xlin-11,$this->enderpref);
	    $this->objpdf->text(40,$xlin-8,$this->municpref);
	    $this->objpdf->text(40,$xlin-5,$this->telefpref);
	    $this->objpdf->text(40,$xlin-2,$this->emailpref);
	    
            $xlin = -30;
	    $this->objpdf->Setfont('Arial','B',8);

  	    $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	    $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
	    $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
	    $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	    $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	    $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
	    $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
	    $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
	    $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

	    $this->objpdf->sety($xlin+66);
	    $alt = 4;

	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	    $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	    $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
	    $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));

	    $maiscol = 0;

	  }

	}

        if ($pagina == 1){
	  $this->objpdf->setxy($xcol+1,$xlin+187);
	  $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->multicell(140,3.5,$this->resumo);
	  $this->objpdf->Setfont('Arial','B',8);
	}

//	$this->objpdf->SetXY(172,$xlin+205);
//	$this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	//	echo $this->numaut."<br>";
	//	echo $pagina;exit;
	if ($pagina == 1){
		$this->objpdf->rect($xcol,$xlin+217,100,55,2,'DF','1234');
		$this->objpdf->rect($xcol+102,$xlin+217,100,55,2,'DF','1234');
//		$this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
		$this->objpdf->setfillcolor(0,0,0);

		$y = 260;


	       $this->objpdf->Setfont('Arial','',6);
	       //// ASSINATURAS DA AUTORIZACAO
	       $cont =  "__________________________________";
	       $ord =   "ORDENADOR DA DESPESA";
	       $visto =  "";
	       
               if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
                  $ass_cont   = $this->assinatura(1006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }else{
                  $ass_cont   = $this->assinatura(51006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }
               $this->objpdf->SetXY(20,$y);
               
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_cont,0,"C",0);
	       
               $this->objpdf->SetXY(122,$y);
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_ord,0,"C",0);
	       
               $this->objpdf->SetXY(137,$y);
//               $this->objpdf->MultiCell(70,2,$ass_visto,0,"C",0);
	       //////
		
	       
	   $this->objpdf->Setfont('Arial','B',8);
           $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	       
//	   $this->objpdf->SetFont('Arial','',4);
//         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	   $this->objpdf->setfont('Arial','',11);
//         $xlin = 169;
        }
     } else {
          // quando nao e guaiba
	  $this->objpdf->AliasNbPages();
	  $this->objpdf->AddPage();
	  $this->objpdf->settopmargin(1);
	  $pagina = 1;
	  $xlin = 20;
	  $xcol = 4;
	  //Inserindo usuario e data no rodape
      $this->objpdf->Setfont('Arial','I',6);
      $this->objpdf->text($xcol+3,$xlin+276,"Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y")."");
      
	  $this->objpdf->setfillcolor(245);
	  $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	  $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	  $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	  $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
    
    if($this->Scodemp!=""){
      $this->objpdf->text(137,$xlin-3,'EMPENHO N'.CHR(176));
      $this->objpdf->text(180,$xlin-3,$this->Scodemp);
    }
	  $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->text(40,$xlin-12,$this->enderpref);
	  $this->objpdf->text(40,$xlin- 9,$this->municpref);
	  $this->objpdf->text(40,$xlin- 6,$this->telefpref);
	  $this->objpdf->text(40,$xlin- 3,$this->emailpref);
	  $this->objpdf->text(40,$xlin,db_formatar($this->cgcpref,'cnpj'));

	  $this->objpdf->rect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+2,$xlin+4,'Dados da Compra');
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->text($xcol+2,$xlin+ 8,'Licitação');
	  $this->objpdf->text($xcol+2,$xlin+12,'Tipo de Compra');
	  $this->objpdf->text($xcol+2,$xlin+16,'Prazo de Entrega');
	  $this->objpdf->text($xcol+2,$xlin+20,'Observações');
	  $this->objpdf->text($xcol+2,$xlin+24,'Cond.de Pagto');
	  $this->objpdf->text($xcol+2,$xlin+28,'Outras Condições');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+27,$xlin+ 8,':  '.$this->num_licitacao.'  -  '.$this->descr_licitacao);
	  $this->objpdf->text($xcol+27,$xlin+12,':  '.$this->descr_tipocompra);
	  $this->objpdf->text($xcol+27,$xlin+16,':  '.$this->prazo_ent);
	  $this->objpdf->text($xcol+27,$xlin+20,':  '.$this->obs);
	  $this->objpdf->text($xcol+27,$xlin+24,':  '.$this->cond_pag);
	  $this->objpdf->text($xcol+27,$xlin+28,':  '.$this->out_cond);

	  $this->objpdf->rect($xcol+106,$xlin+2,96,28,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+110,$xlin+4,'Dados da Dotação');
	  $this->objpdf->Setfont('Arial','B',8);

	  
	  $this->objpdf->text($xcol+108,$xlin+ 8,'Dotação');
	  $this->objpdf->text($xcol+108,$xlin+11.5,'Órgão');
	  $this->objpdf->text($xcol+108,$xlin+15,'Unidade');
	  $this->objpdf->text($xcol+108,$xlin+18.5,'Proj/Ativ');
	  $this->objpdf->text($xcol+108,$xlin+22,'Elemento');
	  $this->objpdf->text($xcol+108,$xlin+25.5,'Recurso');
	  $this->objpdf->text($xcol+178,$xlin+25.5,'Reduz');
	  $this->objpdf->text($xcol+108,$xlin+29,'Destino');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
	  $this->objpdf->text($xcol+122,$xlin+11.5,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	  $this->objpdf->text($xcol+122,$xlin+15,':  '.db_formatar($this->orgao,'orgao').db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	  $this->objpdf->text($xcol+122,$xlin+18.5,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	  $this->objpdf->text($xcol+122,$xlin+22,':  '.$this->descrdotacao);
	  $this->objpdf->text($xcol+122,$xlin+25.5,':  '.$this->recurso.' - '.$this->descr_recurso);
	  $this->objpdf->text($xcol+188,$xlin+25.5,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
	  $this->objpdf->text($xcol+122,$xlin+29,':  '.$this->destino);

	  $this->objpdf->rect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	  $this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	  $this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	  $this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	  $this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	  $this->objpdf->text($xcol+  2,$xlin+46,'Município');
	  $this->objpdf->text($xcol+115,$xlin+46,'CEP');
	  $this->objpdf->text($xcol+  2,$xlin+50,'Contato');
	  $this->objpdf->text($xcol+110,$xlin+50,'Telefone');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+159,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	  $this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	  $this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	  $this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	  $this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	  $this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	  $this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	  $this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
	  $this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telefone);
	  
	  $this->objpdf->Setfont('Arial','B',8);
  //	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
	  $this->objpdf->rect($xcol    ,$xlin+54,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
	  $this->objpdf->rect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
	  $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	  $this->objpdf->rect($xcol,    $xlin+60,15,122,2,'DF','34');
	  $this->objpdf->rect($xcol+ 15,$xlin+60,20,122,2,'DF','34');
	  $this->objpdf->rect($xcol+ 35,$xlin+60,107,122,2,'DF','34');

	  $this->objpdf->rect($xcol+142,$xlin+60,30,155,2,'DF','');
	  $this->objpdf->rect($xcol+172,$xlin+60,30,155,2,'DF','34');
	  
	  $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+120 ,$xlin+211,'T O T A L');


	  $this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	  
	  $this->objpdf->sety($xlin+28);
	  $alt = 4;
	  
	  $this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
	  $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	  $this->objpdf->text($xcol+  70,$xlin+58,'MATERIAL OU SERVIÇO');
	  $this->objpdf->text($xcol+ 145,$xlin+58,'VALOR UNITÁRIO');
	  $this->objpdf->text($xcol+ 176,$xlin+58,'VALOR TOTAL');
	  $maiscol = 0;
	  
	  $this->objpdf->SetWidths(array(10,22,105,30,30));
	  $this->objpdf->SetAligns(array('C','C','L','R','R'));
	  
	  $this->objpdf->setleftmargin(8);
	  $this->objpdf->sety($xlin+61);
	  $ele = 0;
	  $xtotal = 0;

	  for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	    db_fieldsmemory($this->recorddositens,$ii);
	    if($this->usa_sub == 'f'){
	      $this->objpdf->Setfont('Arial','B',7);
	      if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
	      {
		 $this->objpdf->cell(32,4,'',0,0,"C",0);
		 $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
		 $ele = pg_result($this->recorddositens,$ii,$this->analitico);
	      }
	    }
	    $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	    if(pg_result($this->recorddositens,$ii,$this->Snumero) != "") {
              $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".'SOLICITAÇÃO: '.pg_result($this->recorddositens,$ii,$this->Snumero);
	    }
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
				     pg_result($this->recorddositens,$ii,$this->quantitem),
				     $descricaoitem."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
				     db_formatar(pg_result($this->recorddositens,$ii,$this->valor),'v'," ",$this->casadec),
				     db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	    
	    $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
	    

	    $this->objpdf->Setfont('Arial','B',8);
   /////// troca de pagina
	    if( ( $this->objpdf->gety() > $this->objpdf->h - 110 && $pagina == 1 ) || 
	        ( $this->objpdf->gety() > $this->objpdf->h - 40  && $pagina != 1 )){
	      if ($this->objpdf->PageNo() == 1){
		 $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
		 $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');

                 $this->objpdf->SetXY(172,$xlin+205);
                 $this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");
		 
		 $y = 260;

		 //// ASSINATURAS DA AUTORIZACAO
		 $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
		 $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		 $visto = "VISTO";
		 
  //	       $ass_cont   = $this->assinatura(1006,$cont);
  //	       $ass_ord    = $this->assinatura(1002,$ord);
  
		 if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){

                    $cont =  "__________________________________"."\n"."CONTABILIDADE";
                    $pref =  "__________________________________"."\n"."PREFEITO MUNICIPAL";
                    $ord =   "AUTORIZO"."\n\n"."__________________________________";
                    $visto = "VISTO";
                    $ch_compras = "__________________________________"."\n"."CHEFE COMPRAS";


                    $ass_pref   = $this->assinatura(1000,$pref);
                    $ass_secfaz = $this->assinatura(1002,$ord);
                    $ass_visto  = $this->assinatura(5000,$visto);
                    $ass_usu    = $this->assinatura_usuario();
                    /// primeiro quadro
                    $this->objpdf->SetXY(2,$y-15);
                    $this->objpdf->MultiCell(70,3,"__________________________________"."\n".$ass_usu,0,"C",0);

                    $this->objpdf->SetXY(2,$y+8);
                    $this->objpdf->MultiCell(70,3,$ch_compras,0,"C",0);
		    
                    $this->objpdf->setfillcolor(0,0,0);
                    $this->objpdf->Setfont('Arial','B',8);
                    $this->objpdf->text($xcol+10,$xlin+270,$this->municpref.', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.db_getsession("DB_anousu").'.');

                    /// segundo quadro
                    $this->objpdf->Setfont('Arial','',6);

                    $this->objpdf->SetXY(72,$y-15);
                    $this->objpdf->MultiCell(65,3,$cont,0,"C",0);

                    $this->objpdf->SetXY(72,$y);
                    $this->objpdf->MultiCell(65,3,"HÁ RECURSOS FINANCEIROS:",0,"C",0);

                    $this->objpdf->SetXY(72,$y+8);
                    $this->objpdf->MultiCell(65,3,$ass_secfaz,0,"C",0);

                    $this->objpdf->setfillcolor(0,0,0);
//                  $this->objpdf->Setfont('Arial','B',8);
                    $this->objpdf->text($xcol+95,$xlin+263,'CONFERIDO');
                    $this->objpdf->text($xcol+91,$xlin+270,'______/______/______');

                    /// terceiro quadro
                    $this->objpdf->Setfont('Arial','',6);
                    $this->objpdf->SetXY(137,$y);
                    $this->objpdf->MultiCell(70,3,$ass_pref,0,"C",0);

                    $this->objpdf->text($xcol+165,$xlin+263,'AUTORIZA');
                    $this->objpdf->text($xcol+161,$xlin+270,'______/______/______');
                 }elseif(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		    $this->objpdf->SetXY(72,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 }else{
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
		    $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	  	    $this->objpdf->setfillcolor(0,0,0);
                    if(strtoupper(trim($this->municpref)) == 'CHARQUEADAS' || strtoupper(trim($this->municpref)) == 'BAGE' ){
                      $this->objpdf->text($xcol+10,$xlin+233,$this->municpref.', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.db_getsession("DB_anousu").'.');
		    }else{
		      $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		    }
		 } 
		/* 
		 if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }else{
		    $ass_cont   = $this->assinatura(51006,$cont);
		    $ass_ord    = $this->assinatura(51002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }
																			       
		 $this->objpdf->SetXY(2,$y);

		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		 } else {
		   $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		 }
		 
		 
		 $this->objpdf->SetXY(72,$y);
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		 } else {
		   $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(137,$y);
		 $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		 /////
		 
		 $this->objpdf->setfillcolor(0,0,0);
		 $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
	         */
		 $this->objpdf->SetFont('Arial','',4);
		 $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
		 $this->objpdf->setfont('Arial','',11);


		 if ($pagina == 1){
		   $this->objpdf->setxy($xcol+1,$xlin+187);
		   $this->objpdf->text($xcol+2,$xlin+186,'RESUMO: ',0,1,'L',0);
		   $this->objpdf->Setfont('Arial','',7);
		   $this->objpdf->multicell(140,3.5,$this->resumo);
		   $this->objpdf->Setfont('Arial','B',8);
		 }
		 
	      }else{
		 $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
	      }
	      $this->objpdf->addpage();
	      $pagina += 1;	   
	      
	      $this->objpdf->settopmargin(1);
	      $xlin = 20;
	      $xcol = 4;
	      //Inserindo usuario e data no rodape
          $this->objpdf->Setfont('Arial','I',6);
          $this->objpdf->text($xcol+3,$xlin+276,"Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y")."");

	      
	      $this->objpdf->setfillcolor(245);
	      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	      $this->objpdf->setfillcolor(255,255,255);
	      $this->objpdf->Setfont('Arial','B',9);
	      $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	      $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	      $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	      $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
	      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	      $this->objpdf->Setfont('Arial','B',9);
	      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	      $this->objpdf->Setfont('Arial','',9);
	      $this->objpdf->text(40,$xlin-11,$this->enderpref);
	      $this->objpdf->text(40,$xlin-8,$this->municpref);
	      $this->objpdf->text(40,$xlin-5,$this->telefpref);
	      $this->objpdf->text(40,$xlin-2,$this->emailpref);
	      
	      $xlin = -30;
	      $this->objpdf->Setfont('Arial','B',8);

	      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
	      $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
	      $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	      $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	      $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
	      $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
	      $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

	      $this->objpdf->sety($xlin+66);
	      $alt = 4;

	      $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	      $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	      $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
	      $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));

	      $maiscol = 0;

	    }

	  }

	  if ($pagina == 1){
	    $this->objpdf->setxy($xcol+1,$xlin+187);
	    $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->multicell(140,3.5,$this->resumo);
	    $this->objpdf->Setfont('Arial','B',8);
	  }

//	  $this->objpdf->SetXY(172,$xlin+205);
//	  $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	  //	echo $this->numaut."<br>";
	  //	echo $pagina;exit;
	  if ($pagina == 1){
                  $this->objpdf->SetXY(172,$xlin+205);
	          $this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");
		    
		  $this->objpdf->rect($xcol,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->rect($xcol+68,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->setfillcolor(0,0,0);

		  $y = 260;


		 $this->objpdf->Setfont('Arial','',6);
		 //// ASSINATURAS DA AUTORIZACAO
//		 $cont =  "__________________________________";
//		 $ord =   "__________________________________";
//		 $visto =  "VISTO";


		 //// ASSINATURAS DA AUTORIZACAO
		 $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
		 $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		 $visto = "VISTO";
		 
  //	       $ass_cont   = $this->assinatura(1006,$cont);
  //	       $ass_ord    = $this->assinatura(1002,$ord);
  
		 if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){
		 
		    $cont =  "__________________________________"."\n"."CONTABILIDADE";
		    $pref =  "__________________________________"."\n"."PREFEITO MUNICIPAL";
		    $ord =   "AUTORIZO"."\n\n"."__________________________________";
		    $visto = "VISTO";
		    $ch_compras = "__________________________________"."\n"."CHEFE COMPRAS";

		    
		    $ass_pref   = $this->assinatura(1000,$pref);
		    $ass_secfaz = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $ass_usu    = $this->assinatura_usuario();
		    /// primeiro quadro
		    $this->objpdf->SetXY(2,$y-15);
		    $this->objpdf->MultiCell(70,3,"__________________________________"."\n".$ass_usu,0,"C",0);
		    
		    $this->objpdf->SetXY(2,$y+8);
		    $this->objpdf->MultiCell(70,3,$ch_compras,0,"C",0);
		    
		    $this->objpdf->setfillcolor(0,0,0);
	            $this->objpdf->Setfont('Arial','B',8);
                    $this->objpdf->text($xcol+10,$xlin+270,$this->municpref.', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.db_getsession("DB_anousu").'.');
//		    $this->objpdf->text($xcol+10,$xlin+270,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		   
		    /// segundo quadro
		    $this->objpdf->Setfont('Arial','',6);
		    
		    $this->objpdf->SetXY(72,$y-15);
		    $this->objpdf->MultiCell(65,3,$cont,0,"C",0);
		    
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(65,3,"HÁ RECURSOS FINANCEIROS:",0,"C",0);
		   
		    $this->objpdf->SetXY(72,$y+8);
		    $this->objpdf->MultiCell(65,3,$ass_secfaz,0,"C",0);
		    
		    $this->objpdf->setfillcolor(0,0,0);
//	            $this->objpdf->Setfont('Arial','B',8);
		    $this->objpdf->text($xcol+95,$xlin+263,'CONFERIDO');
		    $this->objpdf->text($xcol+91,$xlin+270,'______/______/______');
		    
		    /// terceiro quadro
		    $this->objpdf->Setfont('Arial','',6);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,3,$ass_pref,0,"C",0);
		    
		    $this->objpdf->text($xcol+165,$xlin+263,'AUTORIZA');
		    $this->objpdf->text($xcol+161,$xlin+270,'______/______/______');

                 }elseif(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		    $this->objpdf->SetXY(72,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 }else{
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
		    $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	  	    $this->objpdf->setfillcolor(0,0,0);
                    if(strtoupper(trim($this->municpref)) == 'CHARQUEADAS' || strtoupper(trim($this->municpref)) == 'BAGE'){
                      $this->objpdf->text($xcol+10,$xlin+233,$this->municpref.', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.db_getsession("DB_anousu").'.');
		    }else{
		      $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		    }
		 } 



/*
		 if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }else{
		    $ass_cont   = $this->assinatura(51006,$cont);
		    $ass_ord    = $this->assinatura(51002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }

		 $this->objpdf->SetXY(2,$y);
		 
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		 } else {
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\n".$ass_cont,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(72,$y);
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		 } else {
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\n".$ass_ord,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(137,$y);
		 $this->objpdf->MultiCell(70,2,$ass_visto,0,"C",0);
		 //////
		  
		 
	     $this->objpdf->Setfont('Arial','B',8);
	     $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
*/


  //	   $this->objpdf->SetFont('Arial','',4);
  //         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
  //	   $this->objpdf->setfont('Arial','',11);
  //         $xlin = 169;
        }
	
     }


     




?>
