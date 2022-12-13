<?
global $contapagina;
	$contapagina=1;
////////// MODELO 26  -  TRANSFERÊNCIAS DE MATERIAIS 
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
	  $this->objpdf->text(110,$xlin-13,'TRANSFERÊNCIA DE MATERIAIS N'.chr(176).' '.$this->Rnumero);
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
	  $this->objpdf->text($xcol+2,$xlin+6,'Departamento Origem:');
	  
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+32,$xlin+6,':  '.$this->Rdepart);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+10,'Usuario');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+22,$xlin+10,':  '.$this->Rnomeus);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+10,'Departamento Destino:');
	  $this->objpdf->Setfont('Arial','',8);
	  
	  $this->objpdf->text($xcol+140,$xlin+10,':  '.$this->Rdepartdest);
	  
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
	  $this->objpdf->text($xcol+2,$xlin+26,'CÓDIGO');
	  $this->objpdf->text($xcol+25,$xlin+26,'DESCRIÇÃO');
	  $this->objpdf->text($xcol+80,$xlin+26,'UNID. SAÍDA');
	  $this->objpdf->text($xcol+115,$xlin+26,'QUANT. TRANSFERIDA');
	  
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
	     $this->objpdf->cell(22,3,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,1,"C",0);
	     
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

?>