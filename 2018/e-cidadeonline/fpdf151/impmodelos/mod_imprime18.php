<?php
	
        global $contapagina;
	$contapagina=1;
////////// MODELO 18  -  REQUISIÇÃO DE SAÍDA DE MATERIAIS 
	$this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetAutoPageBreak('on',0);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
	$comeco = 0;
	$passada = 0;
	if ($this->linhasdositens < 20)
	   $vias = 2;
	elseif ($this->linhasdositens < 40)
	   $vias = 4;
	elseif ($this->linhasdositens < 60)
	   $vias = 6;
	elseif ($this->linhasdositens < 80)
	   $vias = 8;
	elseif ($this->linhasdositens < 100)
	   $vias = 10;
       	for ($i = 0;$i < $vias;$i++){
	  if (($i % 2 ) == 0) {
	    $this->objpdf->AddPage();
	  }
	  $this->objpdf->setfillcolor(245);
	  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','B',11);
	  $this->objpdf->text(110,$xlin-13,'REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Rnumero);
//		$this->objpdf->text(159,$xlin-8,$this->datacalc);
	  $this->objpdf->Image('imagens/files/logo_boleto.png',10,$xlin-17,12);
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(30,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',9);
	  $this->objpdf->text(30,$xlin-11,$this->enderpref);
	  $this->objpdf->text(30,$xlin-8,$this->municpref);
	  $this->objpdf->text(30,$xlin-5,$this->telefpref);
	  $this->objpdf->text(30,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);
  
	  $this->objpdf->Roundedrect($xcol,$xlin+1,$xcol+98+100,15,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',8);
//		$this->objpdf->text($xcol+2,$xlin+5,'Origem:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+6,'Departamento ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+22,$xlin+6,':  '.$this->Rdepart);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+10,'Usuario');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+22,$xlin+10,':  '.$this->Rnomeus);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+6,'Hora ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+120,$xlin+6,':  '.$this->Rhora);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+10,'Data ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+120,$xlin+10,':  '.db_formatar($this->Rdata,"d"));
	  $this->objpdf->Setfont('Arial','',6);
  
/*		$this->objpdf->Roundedrect($xcol+104,$xlin+2,98,20,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+106,$xlin+5,'Destino:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+106,$xlin+9,'Departamento');
	  $this->objpdf->Setfont('Arial','',8);
//		$this->objpdf->text($xcol+128,$xlin+9,':  '.$this->destino);
*/

//		$this->objpdf->setfillcolor(245);
	  $this->objpdf->Roundedrect($xcol,$xlin+18,202,78,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+123,$xlin+23,'QUANTIDADES');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+26,'CÓDIGO');
	  $this->objpdf->text($xcol+25,$xlin+26,'DESCRIÇÃO');
	  $this->objpdf->text($xcol+80,$xlin+26,'UNID. SAÍDA');
	  $this->objpdf->text($xcol+115,$xlin+26,'REQUISIT.');
	  $this->objpdf->text($xcol+135,$xlin+26,'FORNECIDA');
	  $this->objpdf->text($xcol+170,$xlin+26,'OBS. ITEM');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+31);
	  $maiscol = 0;
	  $yy = $this->objpdf->gety();
	  for($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {
	     if (($ii % 20 ) == 0 && $ii > 0 && $passada == 0){
		$maiscol = 0;
		$passada ++;
		$comeco = $ii;
		break;
	     }elseif (($ii % 20 ) == 0 && $ii > 0 && ($ii % 20 ) != 0){
		$maiscol = 100;
		$this->objpdf->sety($yy);
	     }
	     
	     $this->objpdf->setx($xcol+3+$maiscol);
	     $this->objpdf->cell(13,3,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
	     $this->objpdf->cell(63,3,substr(trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,30),0,0,"L",0);
	     $this->objpdf->cell(33,3,pg_result($this->recorddositens,$ii,$this->runidadesaida),0,0,"L",0);
	     $this->objpdf->cell(20,3,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);
	     $this->objpdf->cell(22,3,trim(pg_result($this->recorddositens,$ii,$this->rquantatend)),0,0,"C",0);
	     $this->objpdf->cell(45,3,substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,40),0,1,"L",0);  
	     
	     if(($ii+1) == $this->linhasdositens ){
	       $comeco = 0;
	       $passada = 0;
	       break;  
	     }
	  }
	  $this->objpdf->Roundedrect($xcol,$xlin+98,$xcol+105,25,2,'DF','1234');
	  //$this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+102,'OBS:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+134,$xlin+120,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->line($xcol+130,$xlin+110,$xcol+195,$xlin+110);
	  $this->objpdf->text($xcol+152,$xlin+114,'RECEBEDOR');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+103);
	  $this->objpdf->setx($xcol+1);
	  $this->objpdf->multicell(107,3,substr($this->Rresumo,0,450),0,"L");
	      
	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;

      }
      /*global $contapagina;
	$contapagina=1;
////////// MODELO 18  -  REQUISIÇÃO DE SAÍDA DE MATERIAIS 
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;

	// Imprime caixa externa
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

        // Imprime o cabeçalho com dados sobre a prefeitura
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(122,$xlin-12,'REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.CHR(176));
	$this->objpdf->text(190,$xlin-12,db_formatar($this->Rnumero,'s','0',6,'e'));
        $this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text(  122,$xlin-8,'DEPART.');
	$this->objpdf->text(133.5,$xlin-8,': '.substr($this->Rdepart,0,40));
	$this->objpdf->text(  122,$xlin-4,'USUÁRIO');
	$this->objpdf->text(133.5,$xlin-4,': '.substr($this->Rnomeus,0,40));
	$this->objpdf->text(  122,$xlin  ,'DATA');
	$this->objpdf->text(133.5,$xlin  ,': '.db_formatar($this->Rdata,"d"));
	$this->objpdf->text(  162,$xlin  ,'HORA');
	$this->objpdf->text(  170,$xlin  ,': '.substr($this->Rhora,0,40));

	$this->objpdf->Setfont('Arial','B',6);
	$this->objpdf->text(  122,  $xlin + 2,'Página '.$pagina);

        $this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
	
	$numblines = $this->objpdf->NbLines(201,$this->Rresumo); // Número de linhas da OBSERVAÇÃO
	$alturabox = 15;
	if($numblines >= 3){
	  if($numblines > 3){
	    $alturabox = 8 + (2.1 * $numblines);
	  }
	  $soma =  3;
	}else if($numblines == 2){
	  $soma =  7;
	}else if($numblines == 1){
	  $soma =  9;
	}else if($numblines == 0){
	  $soma = 12;
	}
	
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(70,$xlin+9,"R  E  C  I  B  O     D  E     M  A  T  E  R  I  A  I  S");

	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->rect($xcol,$xlin+12,202,9,2,'DF','34');
	$this->objpdf->text(30,$xlin+18,"RECEBEMOS OS MATERIAIS ABAIXO ESPECIFICADOS RELATIVOS A REQUISIÇÃO N".CHR(176)." ".db_formatar($this->Rnumero,'s','0',6,'e'));

	$this->objpdf->rect($xcol    ,$xlin+23,15,224-$alturabox,2,'DF','34');
	$this->objpdf->rect($xcol+ 15,$xlin+23,58,224-$alturabox,2,'DF','34');
	$this->objpdf->rect($xcol+ 73,$xlin+23,33,224-$alturabox,2,'DF','34');
	$this->objpdf->rect($xcol+106,$xlin+35,19,212-$alturabox,2,'DF','34');
	$this->objpdf->rect($xcol+125,$xlin+35,19,212-$alturabox,2,'DF','34');
	$this->objpdf->rect($xcol+144,$xlin+23,58,224-$alturabox,2,'DF','34');
	
	$this->objpdf->rect($xcol    ,$xlin+23,202,12,2,'DF','34');
	$this->objpdf->rect($xcol+106,$xlin+23, 38,12,2,'DF','34');

	$this->objpdf->text($xcol+    1,$xlin+33,"CÓDIGO");
	$this->objpdf->text($xcol+   35,$xlin+33,"DESCRIÇÃO");
	$this->objpdf->text($xcol+   80,$xlin+33,"UNID. SAÍDA");
	$this->objpdf->text($xcol+113.3,$xlin+28,"QUANTIDADES");
	$this->objpdf->text($xcol+  107,$xlin+33,"REQUISIT.");
	$this->objpdf->text($xcol+  125,$xlin+33,"FORNECIDA");
	$this->objpdf->text($xcol+  164,$xlin+33,"OBS. ITEM");

	$xtotalreq = 0;
	$this->objpdf->setxy(4,$xlin+36);
	for($i=0;$i<$this->linhasdositens;$i++){
	  db_fieldsmemory($this->recorddositens,$i);
          $rcodmaterial  = trim(pg_result($this->recorddositens,$i,$this->rcodmaterial));
          $rdescmaterial = trim(pg_result($this->recorddositens,$i,$this->rdescmaterial));
          $runidadesaida = trim(pg_result($this->recorddositens,$i,$this->runidadesaida));
          $rquantdeitens = trim(pg_result($this->recorddositens,$i,$this->rquantdeitens));
          $robsdositens  = trim(pg_result($this->recorddositens,$i,$this->robsdositens));

	  $this->objpdf->SetWidths(array(15,58,33,19,19,58));
	  $nbcodmat = $this->objpdf->NbLines(15,$rcodmaterial);
	  $nbdesmat = $this->objpdf->NbLines(58,$rdescmaterial);
	  $nbunisai = $this->objpdf->NbLines(33,$runidadesaida);
	  $nbqtditm = $this->objpdf->NbLines(19,$rquantdeitens);
	  $nbobsitm = $this->objpdf->NbLines(58,$robsdositens);

          $numerodelinhas = max($nbcodmat,$nbdesmat,$nbunisai,$nbqtditm,$nbobsitm);
	  $alturaagora = $this->objpdf->gety();
	  $alturatotal = $this->objpdf->h - 30;

          if((($alturaagora > $alturatotal-$alturabox || ($alturaagora+($numerodelinhas*3) > $alturatotal-$alturabox)) && $pagina==1) || (($alturaagora > $alturatotal || ($alturaagora+($numerodelinhas*3) > $alturatotal)) && $pagina != 1)){
	    if(($alturaagora > $alturatotal-$alturabox || ($alturaagora+($numerodelinhas*3) > $alturatotal-$alturabox))&& $pagina==1){ 
	      // Imprime o OBSERVAÇÃO
	      // Caixa com OBESERVAÇÃO da requisição
	      $this->objpdf->Setfont('Arial','b',9);
	      $this->objpdf->text(5,$xlin+253-$alturabox,"OBESERVAÇÃO DA REQUISIÇÃO:");
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->setleftmargin(4);
	      $this->objpdf->sety($xlin+254-$alturabox);
	      $this->objpdf->SetWidths(array(201));
	      $this->objpdf->SetAligns(array('J'));
	      $this->objpdf->Row(array($this->Rresumo),3,false,4,0,0,true);
	      $this->objpdf->rect($xcol,$xlin+249-$alturabox,202,$alturabox,2,'DF','34');

	      $altboxass = $this->objpdf->gety();
	      
	      $this->objpdf->Setfont('Arial','b',9);
	      $this->objpdf->rect($xcol,$altboxass+$soma    ,202,21,2,'DF','34');
	      $this->objpdf->text(   20,$altboxass+$soma+12,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	      $this->objpdf->text(  125,$altboxass+$soma+17,"ASSINATURA DO RECEBEDOR");
	    }
	    $this->objpdf->addpage();
	    $passou = true;
	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;
	    $pagina += 1;

	    // Imprime caixa externa
	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

	    // Imprime o cabeçalho com dados sobre a prefeitura
	    $this->objpdf->setfillcolor(255,255,255);
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(122,$xlin-12,'REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.CHR(176));
	    $this->objpdf->text(190,$xlin-12,db_formatar($this->Rnumero,'s','0',6,'e'));
	    $this->objpdf->Setfont('Arial','B',7);
	    $this->objpdf->text(  122,$xlin-8,'DEPART.');
	    $this->objpdf->text(133.5,$xlin-8,': '.substr($this->Rdepart,0,40));
	    $this->objpdf->text(  122,$xlin-4,'USUÁRIO');
	    $this->objpdf->text(133.5,$xlin-4,': '.substr($this->Rnomeus,0,40));
	    $this->objpdf->text(  122,  $xlin,'DATA');
	    $this->objpdf->text(133.5,  $xlin,': '.db_formatar($this->Rdata,"d"));
	    $this->objpdf->text(  162,  $xlin,'HORA');
	    $this->objpdf->text(  170,  $xlin,': '.substr($this->Rhora,0,40));

	    $this->objpdf->Setfont('Arial','B',6);
	    $this->objpdf->text(  122,  $xlin + 2,'Página '.$pagina);

	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	    $this->objpdf->Setfont('Arial','',9);
	    $this->objpdf->text(40,$xlin-11,$this->enderpref);
	    $this->objpdf->text(40,$xlin- 8,$this->municpref);
	    $this->objpdf->text(40,$xlin- 5,$this->telefpref);
	    $this->objpdf->text(40,$xlin- 2,$this->emailpref);
	    $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

	    $this->objpdf->Setfont('Arial','',9);

	    $this->objpdf->rect($xcol    ,$xlin+03,15,269,2,'DF','34');
	    $this->objpdf->rect($xcol+ 15,$xlin+03,58,269,2,'DF','34');
	    $this->objpdf->rect($xcol+ 73,$xlin+03,33,269,2,'DF','34');
	    $this->objpdf->rect($xcol+106,$xlin+15,19,257,2,'DF','34');
	    $this->objpdf->rect($xcol+125,$xlin+15,19,257,2,'DF','34');
	    $this->objpdf->rect($xcol+144,$xlin+03,58,269,2,'DF','34');
	    
	    $this->objpdf->rect($xcol    ,$xlin+03,202,12,2,'DF','34');
	    $this->objpdf->rect($xcol+106,$xlin+03, 38,12,2,'DF','34');

	    $this->objpdf->text($xcol+    1,$xlin+ 13,"CÓDIGO");
	    $this->objpdf->text($xcol+   35,$xlin+ 13,"DESCRIÇÃO");
	    $this->objpdf->text($xcol+   80,$xlin+ 13,"UNID. SAÍDA");
	    $this->objpdf->text($xcol+113.3,$xlin+8.4,"QUANTIDADES");
	    $this->objpdf->text($xcol+  107,$xlin+ 13,"REQUISIT.");
	    $this->objpdf->text($xcol+  125,$xlin+ 13,"FORNECIDA");
	    $this->objpdf->text($xcol+  164,$xlin+ 13,"OBS. ITEM");
	    $this->objpdf->setxy(4,$xlin+16);
	  }

	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->SetWidths(array(15,58,33,19,19,58));
	  $this->objpdf->SetAligns(array('C','L','L','R','R','L'));
          $this->objpdf->Row(array($rcodmaterial,
	                           $rdescmaterial,
				   $runidadesaida,
				   $rquantdeitens,
				   '',
				   $robsdositens),3,false,4,0,0,true);
	  $xtotalreq += $rquantdeitens;
	  $passou = false;

	  if(($i+1)!=$this->linhasdositens && $passou==false){
	    $this->objpdf->ln(1);
            $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	    $this->objpdf->ln(1);
	  }	  
	}
        
	if($pagina == 1){
	  // Imprime o OBSERVAÇÃO
	  // Caixa com OBESERVAÇÃO da requisição
	  $this->objpdf->Setfont('Arial','b',9);
	  $this->objpdf->text(5,$xlin+253-$alturabox,"OBESERVAÇÃO DA REQUISIÇÃO:");
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->sety($xlin+254-$alturabox);
	  $this->objpdf->SetWidths(array(201));
	  $this->objpdf->SetAligns(array('J'));
	  $this->objpdf->Row(array($this->Rresumo),3,false,4,0,0,true);
	  $this->objpdf->rect($xcol,$xlin+249-$alturabox,202,$alturabox,2,'DF','34');

	  $altboxass = $this->objpdf->gety();
	  
	  $this->objpdf->Setfont('Arial','b',9);
	  $this->objpdf->rect($xcol,$altboxass+$soma   ,202,21,2,'DF','34');
	  $this->objpdf->text(   20,$altboxass+$soma+12,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->text(  125,$altboxass+$soma+17,"ASSINATURA DO RECEBEDOR");

	  $this->objpdf->rect($xcol    ,$xlin+239-$alturabox,202,8,2,'DF','34');
	  $this->objpdf->setxy(4,$xlin+241.7-$alturabox);
	  $this->objpdf->SetWidths(array(73,33,19,77));
	  $this->objpdf->SetAligns(array('L','C','R','L'));
          $this->objpdf->Row(array('',
	                           'T  O  T  A  I  S',
				   $xtotalreq,
				   ''),3,false,4,0,0,true);
	}else{
	  $this->objpdf->Setfont('Arial','b',9);
	  $this->objpdf->rect($xcol,$alturatotal+  17,202,8,2,'DF','34');
	  $this->objpdf->text(   84,$alturatotal+22.5,'T  O  T  A  I  S');
	  $this->objpdf->text(  112,$alturatotal+22.5,$xtotalreq);
	}
    */


?>
