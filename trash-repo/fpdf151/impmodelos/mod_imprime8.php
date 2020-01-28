<?php
        global $contapagina;
	$contapagina=1;
	$this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetAutoPageBreak('on',0);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin    = 20;
	$xcol    =  4;
	$comeco  =  0;
	$passada =  0;
/*	
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
*/	   
  $vias = 2;
  $vias *= round($this->linhasbens/20);

  if ($vias == 0) {
    $vias = 2;
  }

  for ($i = 0;$i < $vias;$i++){
    if (($i % 2 ) == 0) {
      $this->objpdf->AddPage();
    }   
		$this->objpdf->setfillcolor(245);
		$this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
		$this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
		$this->objpdf->Setfont('Arial','B',11);
		$this->objpdf->text(150,$xlin-13,'TRANSFERÊNCIA N'.chr(176).'  '.$this->codtransf);
		$this->objpdf->text(159,$xlin-8,$this->datacalc);
		$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
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
		$this->objpdf->text($xcol+136,$xlin+9,':  '.$this->destino);
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+106,$xlin+16,'Data de Transferência:');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+138,$xlin+16,db_formatar($this->datatransf,"d"));

//		$this->objpdf->setfillcolor(245);
		$this->objpdf->Roundedrect($xcol,$xlin+24,202,70,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+27,'Itens a Transmitir :');
		$this->objpdf->Setfont('Arial','b',8);
		/*
		 * Cria cabeçalho da lista de itens
		 */
        $this->objpdf->SetXY($xcol,$xlin+28);
    
    $this->objpdf->cell(10, 4, "BEM"              , 0, 0, "C", 0);
    $this->objpdf->cell(25, 4, "PLACA"            , 0, 0, "C", 0);
    $this->objpdf->cell(40, 4, "DESCRIÇÃO"        , 0, 0, "C", 0);
    $this->objpdf->cell(24, 4, "CLASSIFICAÇÃO"    , 0, 0, "C", 0);
    $this->objpdf->cell(40, 4, "DIVISÃO ORIGEM"   , 0, 0, "C", 0);
    $this->objpdf->cell(40, 4, "DIVISÃO DESTINO"  , 0, 0, "C", 0);
    $this->objpdf->cell(23, 4, "SITUAÇÃO"         , 0, 1, "C", 0);
	  
    $this->objpdf->Setfont('Arial','',7);

    $maiscol = 0;
    $yy      = $this->objpdf->gety();

		for($ii = $comeco; $ii < $this->linhasbens ; $ii++) {

		  $iResBem            = trim(pg_result($this->recordbens,$ii,$this->bem));
		  $sPlacaIdent        = trim(pg_result($this->recordbens,$ii,$this->t52_ident));
		  
		  $sResDescricao      = trim(pg_result($this->recordbens,$ii,$this->descr_bem));
		  if (strlen($sResDescricao) > 20) {
		  	$sResDescricao = substr(trim(pg_result($this->recordbens,$ii,$this->descr_bem)),0,20)."...";
		  }
		  		  
		  $iResClassificacao  = pg_result($this->recordbens,$ii,$this->class_bem);
		  
		  $sResDivisaoOrigem  = trim(pg_result($this->recordbens,$ii,$this->divorigem));
		  if (strlen($sResDivisaoOrigem) > 24) {
		    $sResDivisaoOrigem = substr(trim(pg_result($this->recordbens,$ii,$this->divorigem)),0,24)."...";
		  }
		  
		  $sResDivisaoDestino = trim(pg_result($this->recordbens,$ii,$this->divdestino));
		  if (strlen($sResDivisaoDestino) > 24) {
		  	$sResDivisaoDestino = substr(trim(pg_result($this->recordbens,$ii,$this->divdestino)),0,24)."...";
		  }
		  
		  $sResCondicao = substr(trim(pg_result($this->recordbens,$ii,$this->situacao)),0,10);
		  
      $this->objpdf->SetX($xcol);
      $this->objpdf->cell(10, 3, $iResBem           , 0, 0, "R", 0);
      $this->objpdf->cell(25, 3, $sPlacaIdent       , 0, 0, "C", 0);
      $this->objpdf->cell(40, 3, $sResDescricao     , 0, 0, "L", 0);
      $this->objpdf->cell(24, 3, $iResClassificacao , 0, 0, "C", 0);

      /**
       * Verifica se existe setado divisão de origem e destino 
       * para então imprimir os mesmo no PDF.
       */
      if ( isset($sResDivisaoOrigem) && trim($sResDivisaoOrigem) != "" ) {
        $this->objpdf->cell(40, 3, $sResDivisaoOrigem , 0, 0, "L", 0);  
      } else {
        $this->objpdf->cell(40, 3, "NÃO HÁ DIVISÃO"   , 0, 0, "L", 0);
      }
      if ( isset($sResDivisaoDestino) && trim($sResDivisaoDestino) != "" ) {
        $this->objpdf->cell(40, 3, $sResDivisaoDestino, 0, 0, "L", 0);
      } else {
        $this->objpdf->cell(40, 3, "NÃO HÁ DIVISÃO"   , 0, 0, "L", 0);
      }
      $this->objpdf->cell(23, 3, $sResCondicao      , 0, 1, "L", 0);
      

      if (($ii % 20 ) == 0 && $ii > 0 && $passada == 0) {
        
        $maiscol = 0;
        $passada ++;
        $comeco  = $ii+1;
        break;
      }

      if (($ii+1) == $this->linhasbens) {
        $comeco  = 0;
        $passada = 0;
        break;  
      }

      if (($ii % 20) == 0 && $ii > 0 && $passada > 0){

        $maiscol = 0;
        $this->objpdf->sety($yy);
        $comeco  = $ii+1;
		    $passada = 0;
		    break;
	    }
		}

    $iAlturaAntiga = $this->objpdf->getY();
    $iMargemAntiga = $this->objpdf->getX(); 

    $this->objpdf->setY($xlin + 94);
    $this->objpdf->setX(5);

		$this->objpdf->Setfont('Arial','',8);

    $sObservacao = "OBSERVAÇÕES: " . trim($this->obstransf, "\n");
    $sObservacao = substr($sObservacao, 0, 400);
    $iLinhasOcupadasObservacao = $this->objpdf->NbLines(200, $sObservacao);

    if ( $iLinhasOcupadasObservacao > 4 ) {

      $aObservacao = explode("\n", $sObservacao, 4);
      array_pop($aObservacao);
      $sObservacao = implode("\n", $aObservacao);
    }

    $this->objpdf->multicell(200, 5, $sObservacao, 0, "L");

    $this->objpdf->setY($iAlturaAntiga);
    $this->objpdf->setX($iMargemAntiga); 

		$this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
		$this->objpdf->text($xcol+30,$xlin+120,'TRANSMITENTE');
		$this->objpdf->line($xcol+135,$xlin+116,$xcol+195,$xlin+116);
		$this->objpdf->text($xcol+155,$xlin+120,'RECEBEDOR');
		
    if (($i % 2 ) == 0)
      $xlin = 169;
    else
      $xlin = 20;

  }


