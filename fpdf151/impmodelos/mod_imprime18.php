<?php
	
  global $contapagina;
	$contapagina=1;
	
	////////// MODELO 18  -  REQUISIÇÃO DE SAÍDA DE MATERIAIS 
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetAutoPageBreak('on',0);
  $this->objpdf->line(2,148.5,208,148.5);
	
	$xlin    = 20;
	$xcol    = 4;
	$comeco  = 0;
	$passada = 0;
	
	$iVias = 2;
  $iPag = ($this->linhasdositens/40);      
  
	if ($iPag < 1){
		$iPag = 1;
  }
 
	if ($this->linhasdositens > 20){
    $iPag *= 2;
  }

	$iVias *= $iPag;
 	
	if($iVias == 0){
	  $iVias = 2;
	}

	for ($i = 0;$i < $iVias;$i++){
    
		if (($i % 2 ) == 0) {
      $this->objpdf->AddPage();
	  }

    if ($this->linhasdositens <= 20){
      $comeco = 0;
    }

	  $this->objpdf->setfillcolor(245);
	  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
	  $this->objpdf->Setfont('Arial','B',11);
	  $this->objpdf->text(110,$xlin-13,'REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Rnumero);

    if ($this->Ratendrequi != null){
	       $this->objpdf->Setfont('Arial','B',8);
	       $this->objpdf->text(110,$xlin-8,'ATENDIMENTO DA REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Ratendrequi);
    }

	  $this->objpdf->Image('imagens/files/'.$this->logo,10,$xlin-17,12);
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(30,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',9);
	  $this->objpdf->text(30,$xlin-11,$this->enderpref);
	  $this->objpdf->text(30,$xlin-8,$this->municpref);
	  $this->objpdf->text(30,$xlin-5,$this->telefpref);
	  $this->objpdf->text(30,$xlin-2,$this->emailpref);
  
	  $this->objpdf->Roundedrect($xcol,$xlin+1,$xcol+98+100,15,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',8);
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
	  $this->objpdf->sety($xlin+29);

	  $maiscol = 0;
	  $cont		 = 0;
		$yy		   = $this->objpdf->gety();
	  for($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {
	     $cont++;
			 $this->objpdf->setx($xcol+3+$maiscol);
	     $this->objpdf->cell(13,3,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
	     $this->objpdf->cell(63,3,substr(trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,30),0,0,"L",0);
	     $this->objpdf->cell(33,3,pg_result($this->recorddositens,$ii,$this->runidadesaida),0,0,"L",0);
	     $this->objpdf->cell(20,3,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);
	     $this->objpdf->cell(22,3,trim(pg_result($this->recorddositens,$ii,$this->rquantatend)),0,0,"C",0);
	     $this->objpdf->multicell(50,3,substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,30),0,"L");  
			 
	     if ((($ii+1) % 20 ) == 0 && $ii > 0 && $passada == 0){
				 $maiscol = 0;
				 $passada ++;
				 $comeco  = $ii+1;
				 break;
	     }

	     if(($ii+1) == $this->linhasdositens){
         $comeco  = 0;
	       $passada = 0;
	       break;  
	     }

	     if ($cont == 20 && $passada > 0){
				 $maiscol = 0;
				 $this->objpdf->sety($yy);
    		 $comeco  = $ii+1;
		     $passada = 0;
				 break;
	     }
		
		
		}
	  $this->objpdf->Roundedrect($xcol,$xlin+98,$xcol+105,25,2,'DF','1234');
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
	  
	  if (($i % 2 ) == 0){
	    $xlin = 169;
	  }else{
	    $xlin = 20;
    } 
	}
?>
