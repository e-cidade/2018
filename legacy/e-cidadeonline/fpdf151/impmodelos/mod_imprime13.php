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
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
        $this->objpdf->text(130,$xlin-15,"ORÇAMENTO N".CHR(176));
	$this->objpdf->text(185,$xlin-15,db_formatar($this->orccodigo,'s','0',6,'e'));	
	$this->objpdf->text(130,$xlin-11,$this->labdados.CHR(176));
	$this->objpdf->text(185,$xlin-11,db_formatar($this->Snumero,'s','0',6,'e'));	
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(130,$xlin-8,"Departamento");
	$this->objpdf->text(130,$xlin-5,"Fone / Ramal");
	$this->objpdf->text(130,$xlin-2,"Fax");
	$this->objpdf->text(146,$xlin-8,": ".$this->Sdepart);	
	$this->objpdf->text(146,$xlin-5,": ".$this->fonedepto." / ".$this->ramaldepto);	
	$this->objpdf->text(146,$xlin-2,": ".$this->faxdepto);	
	$this->objpdf->text(130,$xlin+1,$this->emaildepto);	
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

        // Caixa com dados do orçamento e solicitação 
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,27,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+5,'Dados do Orçamento/'.$this->labtitulo);
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+ 8,'Orçamento');
	$this->objpdf->text($xcol+109,$xlin+ 8,'Data Limite');
	$this->objpdf->text($xcol+150,$xlin+ 8,'Hora Limite');
	$this->objpdf->text($xcol+  2,$xlin+13,$this->labtitulo);
	$this->objpdf->text($xcol+109,$xlin+17,$this->labtipo);
	$this->objpdf->text($xcol+  2,$xlin+17,'Data');
//	$this->objpdf->text($xcol+  2,$xlin+21,'Departamento');
	$this->objpdf->text($xcol+  2,$xlin+21,'Resumo');
	$this->objpdf->Setfont('Arial','',8);
	
        // Imprime dados do orçamento e solicitação
	$this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->orccodigo);
	$this->objpdf->text($xcol+125,$xlin+ 8,':  '.$this->orcdtlim);
	$this->objpdf->text($xcol+166,$xlin+ 8,':  '.$this->orchrlim);
	$this->objpdf->text($xcol+ 23,$xlin+ 13,':  '.$this->Snumero);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(trim($this->labtipo)!=""){
	  $this->objpdf->text($xcol+125,$xlin+17,':  '.$this->Stipcom);
	}
	$this->objpdf->text($xcol+ 23,$xlin+17,':  '.$this->Sdata);
//	$this->objpdf->text($xcol+ 23,$xlin+ 21,':  '.$this->Sdepart);
	$this->objpdf->setxy($xcol+22,$xlin+18);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+18);
	$Sresumo = $this->Sresumo;
	$Sresumo = str_replace("\n",". ",$Sresumo);
	$Sresumo = str_replace("\r","",$Sresumo);

	$this->objpdf->multicell(175,4,$Sresumo,0,"J");

        // Caixa com dados dos fornecedores
	$this->objpdf->rect($xcol,$xlin+32,$xcol+198,16,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Fornecedor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+150,$xlin+42,'Contato');
	$this->objpdf->text($xcol+150,$xlin+46,'Fone/Fax');
	$this->objpdf->Setfont('Arial','',8);

        // Imprime dados dos fornecedores
	$this->objpdf->text($xcol+ 18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+163,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	$this->objpdf->text($xcol+ 18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.substr($this->compl,0,15));
	$this->objpdf->text($xcol+ 18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	if(trim($this->fax) != ""){
	  $this->fax = " / ".$this->fax;
	}
	$this->objpdf->text($xcol+163,$xlin+42,':  '.substr($this->contato,0,20));
	$this->objpdf->text($xcol+163,$xlin+46,':  '.$this->telefone.$this->fax);

        $getdoy = 50;

	$contadepart = 0;
	$alturaini = 216;
	if($this->linhasdosdepart>0){
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    $contadepart += 4;
	  }
          $setaut = $xlin + $getdoy;
	  $alturaini -= ($contadepart+15);

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+  4,$setaut+4,'DEPARTAMENTOS DAS SOLICITAÇÕES');

	  $this->objpdf->rect($xcol    ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+30 ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+60 ,$setaut+6,142,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+30 ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+60 ,$setaut+12,142,$contadepart+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   6,$setaut+11,'SOLICITAÇÃO');
	  $this->objpdf->text($xcol+  39,$setaut+11,'CÓDIGO');
	  $this->objpdf->text($xcol+ 125,$setaut+11,'DESCRIÇÃO');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','C','L'));
	  $this->objpdf->SetWidths(array(30,30,142));
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    db_fieldsmemory($this->recorddosdepart,$i);
	    $solicita  = trim(pg_result($this->recorddosdepart,$i,$this->Snumdepart));
	    $codigodep = trim(pg_result($this->recorddosdepart,$i,$this->Scoddepto));
	    $descrdep  = trim(pg_result($this->recorddosdepart,$i,$this->Sdescrdepto));
	    $this->objpdf->Row(array($solicita,$codigodep,$descrdep),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety()+2-$xlin;
	}


        // Caixa com Labels item, quantidade, descrição, valor 
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,14,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 14,$xlin+$getdoy,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 26,$xlin+$getdoy,20,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 46,$xlin+$getdoy,90,6,2,'DF','12');
	$this->objpdf->rect($xcol+136,$xlin+$getdoy,28,6,2,'DF','12');
	$this->objpdf->rect($xcol+164,$xlin+$getdoy,19,6,2,'DF','12');
	$this->objpdf->rect($xcol+183,$xlin+$getdoy,19,6,2,'DF','12');

	$menos = 16.9;
	if($this->linhasdosfornec==0){
	  $menos = 11;
	}
	if(isset($tiramenos)){
	  $menos = $menos+$tiramenos;
	  if($menos<0){
	    $menos = -$menos;
	  }
	}
        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,14,$alturaini,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 14,$xlin+$getdoy+6,12,$alturaini,2,'DF','34');
        // Caixa da referencia	
	$this->objpdf->rect($xcol+ 26,$xlin+$getdoy+6,20,$alturaini,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 46,$xlin+$getdoy+6,90,$alturaini,2,'DF','34');
        // Caixa das marcas
	$this->objpdf->rect($xcol+136,$xlin+$getdoy+6,28,$alturaini,2,'DF','');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+164,$xlin+$getdoy+6,19,$alturaini,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+183,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');

   	$this->objpdf->sety($xlin+48);
	
	$alt = 4;
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   4,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+  15,$xlin+$getdoy+4,'QUANT');
	$this->objpdf->text($xcol+  33,$xlin+$getdoy+4,'REF');
	$this->objpdf->text($xcol+  72,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'OBS');
	$this->objpdf->text($xcol+ 164,$xlin+$getdoy+4,'VALOR UNIT.');
	$this->objpdf->text($xcol+ 184,$xlin+$getdoy+4,'VALOR TOT.');
        $maiscol = 0;
	$this->objpdf->SetWidths(array(14,12,20,90,28,18,18));
	$this->objpdf->SetAligns(array('C','C','C','J','J','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+$getdoy+7);
	$this->objpdf->setfillcolor(235);

	$xtotal = 0;
        $muda_pagina = false;
	$pag = 1;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  if($ii!=0 && $muda_pagina!=true){
	    $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	  }	  
	  $this->objpdf->ln(2);
	  db_fieldsmemory($this->recorddositens,$ii);
	  $prazo = "";
	  $pgto  = "";
	  $resumo = "";

          $descricaoitem =trim(pg_result($this->recorddositens,$ii,$this->descricaoitem));
	  
	  if(trim(pg_result($this->recorddositens,$ii,$this->sprazo))!=""){
	    $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	    $prazo = "PRAZO: ".trim($prazo);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->spgto))!=""){
	    $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	    $pgto = "CONDIÇÃO: ".trim($pgto);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->sresum)!="")){
	    $resumo = "RESUMO: ".pg_result($this->recorddositens,$ii,$this->sresum);
	    if($descricaoitem == "" || $descricaoitem == null){
	      $descricaoitem = trim(pg_result($this->recorddositens,$ii,$this->sresum));
	      $resumo="";
	    }
	  }

	  if($muda_pagina == true){
	    $muda_pagina = false;
	    $this->objpdf->sety($xlin+12);
	  }
	  
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $codunid    = pg_result($this->recorddositens,$ii,$this->scodunid);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);	  
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  $valor      = pg_result($this->recorddositens,$ii,$this->valor);
	  $valorunit  = pg_result($this->recorddositens,$ii,$this->valorunit);
	  $marca      = pg_result($this->recorddositens,$ii,$this->marca);
	  
	  $dist = 2.7;
	  if(trim($codunid)!=""){
	    $unid = trim(substr($unid,0,10));
	    if($susaquant=="t"){
	      $unid .= " \n$quantunid UNIDADES\n";
	      $resumo = str_replace("\n","",$resumo);
	    }
	  }else if($servico=="t"){
	    $unid = "SERVIÇO";
	  }

	  $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   $unid,
				   $descricaoitem,
				   $marca,
				   db_formatar($valorunit, 'f'),
				   db_formatar($valor, 'f')),3,false,3);
	  if(isset($resumo) && $resumo!=""){
	    $this->objpdf->Row(array('','','',$resumo,'',''),$dist,false,2.7);
	  }
	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','','',$prazo,'','',''),$dist,false,2.7);
	  }	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','','',$pgto,'','',''),$dist,false,2.7);
	  }
	  
	  $this->objpdf->Setfont('Arial','B',8);
          /////// troca de pagina
	  if( $this->objpdf->gety() > $this->objpdf->h - 30){
	    if(($ii+1)!=$this->linhasdositens){
	      $pag++;
	      $muda_pagina=true;
	      $this->objpdf->Setfont('Arial','',7);
	      if ($pag != 1){
		$this->objpdf->Setfont('Arial','B',7);
		$this->objpdf->rect($xcol,    $xlin+262,136,10,2,'DF','34');
		$this->objpdf->rect($xcol+136,$xlin+262, 28,10,2,'DF','34');
		$this->objpdf->rect($xcol+164,$xlin+262, 19,10,2,'DF','34');
		$this->objpdf->rect($xcol+183,$xlin+262, 19,10,2,'DF','34');
		$this->objpdf->text($xcol+137,$xlin+268,'T O T A L   P Á G I N A');
		$this->objpdf->Setfont('Arial','',7);
	      }
	      $this->objpdf->addpage();
	      $pagina += 1;	   
	      
	      $this->objpdf->settopmargin(1);
	      $xlin = 20;
	      $xcol = 4;
	      $this->objpdf->setfillcolor(245);
	      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	      $this->objpdf->setfillcolor(255,255,255);
	      $this->objpdf->Setfont('Arial','B',9);
	      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	      $this->objpdf->text(130,$xlin-13,"ORÇAMENTO N".CHR(176));
	      $this->objpdf->text(185,$xlin-13,db_formatar($this->orccodigo,'s','0',6,'e'));	
	      $this->objpdf->text(130,$xlin-9,$this->labdados.CHR(176));
	      $this->objpdf->text(185,$xlin-9,db_formatar($this->Snumero,'s','0',6,'e'));	
	      $this->objpdf->Setfont('Arial','B',9);
	      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->text(40,$xlin-11,$this->enderpref);
	      $this->objpdf->text(40,$xlin-8,$this->municpref);
	      $this->objpdf->text(40,$xlin-5,$this->telefpref);
	      $this->objpdf->text(40,$xlin-2,$this->emailpref);
	      $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
	      
	      $xlin = -30;
	      $this->objpdf->Setfont('Arial','B',8);


	      // Caixas dos label's
	      $this->objpdf->rect($xcol    ,$xlin+54,14,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 14,$xlin+54,12,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 26,$xlin+54,20,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 46,$xlin+54,90,6,2,'DF','12');
	      $this->objpdf->rect($xcol+136,$xlin+54,28,6,2,'DF','12');
	      $this->objpdf->rect($xcol+164,$xlin+54,19,6,2,'DF','12');
	      $this->objpdf->rect($xcol+183,$xlin+54,19,6,2,'DF','12');

/*
        // Caixa da referencia	
	$this->objpdf->rect($xcol+ 26,$xlin+$getdoy+6,20,$alturaini,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 46,$xlin+$getdoy+6,90,$alturaini,2,'DF','34');
        // Caixa das marcas
	$this->objpdf->rect($xcol+136,$xlin+$getdoy+6,28,$alturaini,2,'DF','');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+164,$xlin+$getdoy+6,19,$alturaini,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+183,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');
*/


	      $this->objpdf->rect($xcol,    $xlin+54,14,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 14,$xlin+54,12,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 26,$xlin+54,20,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 46,$xlin+54,90,268,2,'DF','34');
	      $this->objpdf->rect($xcol+136,$xlin+54,28,268,2,'DF','34');
	      $this->objpdf->rect($xcol+164,$xlin+54,19,268,2,'DF','34');
	      $this->objpdf->rect($xcol+183,$xlin+54,19,268,2,'DF','34');
		  
	      $this->objpdf->sety($xlin+66);
	      $alt = 4;

	      // Label das colunas
	      $this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+  15,$xlin+58,'QUANT');
	      $this->objpdf->text($xcol+  33,$xlin+58,'REF');
	      $this->objpdf->text($xcol+  72,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+ 145,$xlin+58,'OBS');
	      $this->objpdf->text($xcol+ 164,$xlin+58,'VALOR UNIT.');
	      $this->objpdf->text($xcol+ 184,$xlin+58,'VALOR TOT.');
	      $maiscol = 0;
	      $xlin = 20;

	    }

	    $this->objpdf->ln(2);
	    if($ii+1==$this->linhasdositens){
	      $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	    }
	  }
	}
        // caixas para total

	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->rect($xcol,    $xlin+262,136,10,2,'DF','34');
	$this->objpdf->rect($xcol+136,$xlin+262, 28,10,2,'DF','34');
	$this->objpdf->rect($xcol+164,$xlin+262, 19,10,2,'DF','34');
	$this->objpdf->rect($xcol+183,$xlin+262, 19,10,2,'DF','34');
	$this->objpdf->text($xcol+137,$xlin+268,'T O T A L   G E R A L');
	
	//	echo $this->numaut."<br>";
	//	echo $pagina;exit;
     



?>
