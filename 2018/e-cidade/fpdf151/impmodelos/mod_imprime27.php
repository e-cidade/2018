<?
global $contapagina;
	$contapagina=1;
////////// MODELO 26  -  TRANSFERÊNCIAS DE MATERIAIS 
	$this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetAutoPageBreak('on',0);
        $this->objpdf->line(2,148.5,208,148.5);
/*	if ($this->linhasdositens < 20)
	   $vias = 2;
	elseif ($this->linhasdositens < 40)
	   $vias = 4;
	elseif ($this->linhasdositens < 60)
	   $vias = 6;
	elseif ($this->linhasdositens < 80)
	   $vias = 8;
	elseif ($this->linhasdositens < 100)
	   $vias = 10;*/
$iTotalCopiasVia = 2;

/**
 * cria numero de quadros necessário para a impressao dos itens
 */
if (($this->linhasdositens/20) < 1) {
   $iNumerovias = 1;
} else {
   $iNumerovias = ceil(abs($this->linhasdositens/20))+1;
}
for ($iCopia = 0; $iCopia < $iTotalCopiasVia; $iCopia++) {
  
  $xlin    = 20;
  $xcol    = 4;
  $comeco  = 0;
  $passada = 0;
  $quebra  = 0;
  $icont   = 0;
  $iITensJaImpressos = 0;
  if ($iNumerovias == 1 && $iCopia > 0) {
    $quebra  = 1;
  }
  for ($iVia = 0;$iVia < $iNumerovias;$iVia++) {
  
		if ($quebra == 1) {
		  
      $quebra = 0; 
 		  $xlin   = 20;
 		  if ($iNumerovias == 1) {
 		    $xlin = 169;
 		  } else {
 	      $this->objpdf->AddPage();
 		  }
		} elseif (($iVia % 2 ) == 0) {
	     $this->objpdf->AddPage();
	  }
	  
	  $this->objpdf->setfillcolor(245);
	  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
	  $this->objpdf->Setfont('Arial','B',11);
	  $this->objpdf->text(110,$xlin-13,'TRANSFERÊNCIA DE MATERIAIS N'.chr(176).' '.$this->Rnumero);
	  $this->objpdf->Image('imagens/files/logo_boleto.png',10,$xlin-17,12);
	  
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
	  $this->objpdf->text($xcol+2,$xlin+6,'Departamento Origem');
	  
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+32,$xlin+6,':  '.$this->RdepartCod.' - '.$this->Rdepart);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+10,'Usuário');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+32,$xlin+10,':  '.$this->Rnomeus);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+10,'Departamento Destino');
	  $this->objpdf->Setfont('Arial','',8);
	  
	  $this->objpdf->text($xcol+140.3,$xlin+10,':  '.$this->RdepartdestCod.' - '.$this->Rdepartdest);
	  
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->Roundedrect($xcol,$xlin+18,202,78,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+26,'CÓDIGO');
	  $this->objpdf->text($xcol+25,$xlin+26,'DESCRIÇÃO');
	  $this->objpdf->text($xcol+80,$xlin+26,'UNID. SAÍDA');
	  $this->objpdf->text($xcol+115,$xlin+26,'QUANT. TRANSFERIDA');
	  $this->objpdf->text($xcol+155,$xlin+26,'LOTE');
	  $this->objpdf->text($xcol+175,$xlin+26,'VALIDADE');
	  
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+31);
	  $maiscol = 0;
	  $yy = $this->objpdf->gety();
	  
	  $itotalitens       = 0;
	  $iItensImpressos   = 0;
	  $iTotalITensPagina = 20;
	  
	  $iComeco           = $comeco;
	  for($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {
	  	
	  	 $itotalitens = $itotalitens + (pg_result($this->recorddositens,$ii,$this->rquantdeitens)); 
	     if ($iItensImpressos  == 19) {
	       
		      $maiscol = 0;
		      $passada ++;
		      $comeco  = $ii;
	        break;
	        
	      } else if ($iItensImpressos == 39) {

	   	    $maiscol  = 100;
  		    $this->objpdf->sety($yy);
  		    $this->objpdf->addPage();
	     }
	     
	     $this->objpdf->setx($xcol+3+$maiscol);
	     $this->objpdf->cell(13,3,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
	     $this->objpdf->cell(63,3,substr(trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,30),0,0,"L",0);
	     $this->objpdf->cell(33,3,pg_result($this->recorddositens,$ii,$this->runidadesaida),0,0,"L",0);
	     $this->objpdf->cell(37,3,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);

	     $sData = 
	     $this->objpdf->cell(22,3,pg_result($this->recorddositens,$ii,$this->rlote)?pg_result($this->recorddositens,$ii,$this->rlote):'-',0,0,"C",0);
       $this->objpdf->cell(22,3,trim(pg_result($this->recorddositens,$ii,$this->rvalidade)?
                           db_formatar(pg_result($this->recorddositens,$ii,$this->rvalidade), "d"):'-'),0,1,"C",0);
	     $iItensImpressos++;
	     $iITensJaImpressos++;
	     $icont++;
	     //echo ($iITensJaImpressos+1)." Total {$this->linhasdositens}<br>";
	  }
	  /*
     * TOTALIZADORES
     */   
    $this->objpdf->Setfont('Arial','b',8);
    if ($icont) {
      
	    $this->objpdf->text($xcol+24,$xlin+99.5,'Total de Itens: '.$icont.'/'.$this->linhasdositens);
	    $this->objpdf->text($xcol+115,$xlin+99.5,'Total Transferido '.$itotalitens.'/'.$this->totalQuantTransf);
    }
	  
	  $this->objpdf->Roundedrect($xcol,$xlin+103,$xcol+110,20,2,'DF','1234');
	  //$this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+107,'OBS:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+134,$xlin+120,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->line($xcol+130,$xlin+110,$xcol+195,$xlin+110);
	  $this->objpdf->text($xcol+152,$xlin+114,'RECEBEDOR');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+108);
	  $this->objpdf->setx($xcol+1);
	  $this->objpdf->multicell(107,3,substr($this->Rresumo,0,450),0,"L");
	  
	  $this->objpdf->Setfont('Arial','b',8);   
    $this->objpdf->text($xcol+196,$xlin+125, $this->objpdf->PageNo()."/{nb}");
	      
	  if (($iVia % 2 ) == 0) {
	    $xlin = 169;
	  } else {
	    $xlin = 20;
	    
	  }
    if (($iITensJaImpressos) == $this->linhasdositens) {
      break;  
    }
  }
}

?>
