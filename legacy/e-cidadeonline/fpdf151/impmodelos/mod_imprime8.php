<?php



	$this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
	$comeco = 0;
	$passada = 0;
	if ($this->linhasbens < 40)
	   $vias = 2;
	elseif ($this->linhasbens < 80)
	   $vias = 4;
	elseif ($this->linhasbens < 120)
	   $vias = 6;
	elseif ($this->linhasbens < 160)
	   $vias = 8;
	elseif ($this->linhasbens < 200)
	   $vias = 10;
       	for ($i = 0;$i < $vias;$i++){
	  if (($i % 2 ) == 0)
	     $this->objpdf->AddPage();
		$this->objpdf->setfillcolor(245);
		$this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
		$this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
		$this->objpdf->Setfont('Arial','B',11);
		$this->objpdf->text(150,$xlin-13,'TRANSFERÊNCIA N'.chr(176).'  '.$this->codtransf);
		$this->objpdf->text(159,$xlin-8,$this->datacalc);
		$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
		$this->objpdf->Setfont('Arial','B',9);
		$this->objpdf->text(40,$xlin-15,$this->prefeitura);
		$this->objpdf->Setfont('Arial','',9);
		$this->objpdf->text(40,$xlin-11,$this->enderpref);
		$this->objpdf->text(40,$xlin-8,$this->municpref);
		$this->objpdf->text(40,$xlin-5,$this->telefpref);
		$this->objpdf->text(40,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);
	
		$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+98,20,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+5,'Origem:');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+9,'Departamento ');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+22,$xlin+9,':  '.$this->origem);
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+16,'Usuario');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+22,$xlin+16,':  '.$this->usuario);
		$this->objpdf->Setfont('Arial','',6);
	
		$this->objpdf->Roundedrect($xcol+104,$xlin+2,98,20,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+106,$xlin+5,'Destino:');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+106,$xlin+9,'Departamento');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+128,$xlin+9,':  '.$this->destino);

//		$this->objpdf->setfillcolor(245);
		$this->objpdf->Roundedrect($xcol,$xlin+24,202,70,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+27,'Itens a Transmitir :');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+30,'BEM');
		$this->objpdf->text($xcol+25,$xlin+30,'DESCRIÇÃO');
		$this->objpdf->text($xcol+75,$xlin+30,'CLASSIFICAÇÃO');
		$this->objpdf->text($xcol+102,$xlin+30,'BEM');
		$this->objpdf->text($xcol+125,$xlin+30,'DESCRIÇÃO');
		$this->objpdf->text($xcol+175,$xlin+30,'CLASSIFICAÇÃO');
		$this->objpdf->Setfont('Arial','',8);
	   	$this->objpdf->sety($xlin+31);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
		for($ii = $comeco;$ii < $this->linhasbens ;$ii++) {
		   if (($ii % 40 ) == 0 && $ii > 0 && $passada == 0){
		      $maiscol = 0;
		      $passada ++;
		      $comeco = $ii;
                      break;
                   }elseif (($ii % 20 ) == 0 && $ii > 0 && ($ii % 40 ) != 0){
                      $maiscol = 100;
                      $this->objpdf->sety($yy);
                   }
		   
	   	   $this->objpdf->setx($xcol+3+$maiscol);
	   	   $this->objpdf->cell(5,3,trim(pg_result($this->recordbens,$ii,$this->bem)),0,0,"R",0);
	  	   $this->objpdf->cell(70,3,trim(pg_result($this->recordbens,$ii,$this->descr_bem)),0,0,"L",0);
 		   $this->objpdf->cell(15,3,pg_result($this->recordbens,$ii,$this->class_bem),0,1,"R",0);
		   if(($ii+1) == $this->linhasbens ){
		     $comeco = 0;
		     $passada = 0;
		     break;  
		   }
		}
		$this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
		$this->objpdf->text($xcol+30,$xlin+120,'TRANSMITENTE');
		$this->objpdf->line($xcol+135,$xlin+116,$xcol+195,$xlin+116);
		$this->objpdf->text($xcol+155,$xlin+120,'RECEBEDOR');
		
	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;

      }



?>
