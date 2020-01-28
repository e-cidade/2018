<?php

  global $contapagina;
	$contapagina=1;
	
	////////// MODELO 18  -  REQUISIÇÃO DE SAÍDA DE MATERIAIS 
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetAutoPageBreak('on',0);
  $this->objpdf->line(2,148.5,208,148.5);
	
	$xlin       = 20;
	$xcol       = 4;
	$comeco     = 0;
	$passada    = 0;
  $observacao = false;
  $cont_obs   = 0;
  $quant_itens= 0;
  $obs        = null;
  $iVias      = 0;
  $iPag_a     = false;
  $iPag_b     = false;
  $comeco     = 0;
  $passou=0;


//verifica se algum item tem observação
for ($j=0 ;$j<$this->linhasdositens;$j++){
       $obs=trim(pg_result($this->recorddositens,$j,$this->robsdositens));
       if ($obs != null){
           $cont_obs++;
       }
}

$total=0;
$contaobs=0;

if ($cont_obs > 0){
// 8 itens por folha se algum item conter observação
    $quant_itens = 8;
    $qReg=$this->linhasdositens;
    $passou=0;
    for ($i=0; $i < $qReg; $i++){
       $contaobs++;
       if ((($contaobs==$quant_itens) || ($passou==0 && $qReg >$quant_itens)) && ($qReg >$quant_itens)){
          $iVias++;
          $total+=$contaobs;
          $contaobs=0;
          $iPag_a=true;
          $passou=1;
       }
       if (isset($total) && $qReg < $quant_itens){
           $iVias++;   
           $contaobs=0;
           $iPag_b=true;        
           break;
       }
    }

if ($iPag_a==true && $iPag_b==true){
    $iVias=$iVias;
}elseif ($iPag_a==true && $iPag_b==false){
    $iVias=$iVias;
}elseif ($iPag_a==false && $iPag_b==true){
    $iVias=1;
}

}else{
  //14 itens por folha se não conter observação em nenhum item
    $quant_itens = 14;
    $qReg=$this->linhasdositens;
    for ($i=0; $i < $qReg; $i++){
       $contaobs++;
       if ((($contaobs==$quant_itens) || ($passou==0 && $qReg >$quant_itens)) && ($qReg >$quant_itens)){   
          $iVias++;
          $total+=$contaobs;
          $contaobs=0;
          $iPag_a=true;
          $passou=1;
       }
       if (isset($total) && $qReg < $quant_itens){
           $iVias++;
           $contaobs=0;
           $iPag_b=true;
           break;
       }
    
    }  

    if ($iPag_a==true && $iPag_b==true){
        $iVias=$iVias;
    }elseif ($iPag_a==true && $iPag_b==false){
        $iVias=$iVias;
    }elseif ($iPag_a==false && $iPag_b==true){
        $iVias=1;
    }


}

$iVias=$iVias*2;

for ($i = 0;$i < $iVias;$i++){
	  
		if (($i % 2 ) == 0) {
	    $this->objpdf->AddPage();
	  }

		if ($this->linhasdositens <= $quant_itens){
      $comeco = 0;
    }


	   $this->objpdf->setfillcolor(245);
          $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
          $this->objpdf->setfillcolor(255,255,255);
          $this->objpdf->Setfont('Arial','B',8);
          $this->objpdf->text(110,$xlin-13,'RECIBO DE ENTREGA DE MEDICAMENTOS N'.chr(176).' '.$this->Rnumero);



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
          $this->objpdf->text($xcol+2,$xlin+5,'Departamento ');
          $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->text($xcol+24,$xlin+5,':  '.$this->Rcoddepart." ".$this->Rdepart);
          $this->objpdf->Setfont('Arial','b',8);
          $this->objpdf->text($xcol+2,$xlin+14,'CGS ');
          $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->text($xcol+24,$xlin+14,':  '.$this->Rnomeus);
          $this->objpdf->Setfont('Arial','b',8);
          
          $this->objpdf->Setfont('Arial','b',8);
          $this->objpdf->text($xcol+110,$xlin+6,'Hora ');
          $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->text($xcol+128,$xlin+6,':  '.$this->Rhora);
          $this->objpdf->Setfont('Arial','b',8);
          $this->objpdf->text($xcol+140,$xlin+6,'Data ');
          $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->text($xcol+150,$xlin+6,':  '.$this->Rdata);
          $this->objpdf->Setfont('Arial','b',8);
          $this->objpdf->text($xcol+110,$xlin+14,'Atendente ');
          $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->text($xcol+128,$xlin+14,':  '.$this->ratendente." ".$this->rcodatend);
          $this->objpdf->Setfont('Arial','',6);


	  $this->objpdf->Roundedrect($xcol,$xlin+28,202,75,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',6);
	  $this->objpdf->text($xcol+173,$xlin+32,'QUANTIDADES');
	  $this->objpdf->Setfont('Arial','b',6);
	  $this->objpdf->text($xcol+1,$xlin+35,'CÓDIGO');
	  $this->objpdf->text($xcol+15,$xlin+35,'DESCRIÇÃO');
	  $this->objpdf->text($xcol+102,$xlin+35,'MOTIVO');
    $this->objpdf->text($xcol+150, $xlin+35,'DATA DEVOLUCÃO');//linha a mais, data de cada devolucao
	  $this->objpdf->text($xcol+173,$xlin+35,'REQUISIT.');
	  $this->objpdf->text($xcol+185,$xlin+35,'DEVOLVIDA');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->sety($xlin+35);

	  $maiscol = 0;
		$cont    = 0;
	  $yy 	   = $this->objpdf->gety();
   
    for ($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {
        
       $cont++;
       $dData  = trim(pg_result($this->recorddositens, $ii,$this->recorddata));
       $dData  = db_formatar($dData,"d");  
       $this->objpdf->setx($xcol+3+$maiscol);
       $this->objpdf->cell(10,5,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
	     $this->objpdf->cell(87,5,trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,0,"L",0);
	     $this->objpdf->cell(50,5,substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,40),0,0,"L",0);
       $this->objpdf->cell(25,5, $dData, 0, 0, "L",0);//linha a mais(data de cada devolucaoO)
	     $this->objpdf->cell(10,5,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);
	     $this->objpdf->cell(5,5,trim(pg_result($this->recorddositens,$ii,$this->rquantatend)),0,1,"C",0);
     
       /*if (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) != ''){
         $obsitens=substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,110);
          $this->objpdf->multicell(180,4,str_replace("\n",'',($obsitens)));
	     }
       if ($quant_itens==8 && (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) == '')){
         $obsitens="";
         $this->objpdf->multicell(180,4,$obsitens);
       }*/
       
       if ((($ii+1) % $quant_itens ) == 0 && $ii > 0 && $passada == 0){
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

	     if ($cont == $quant_itens && $passada > 0){
				 $maiscol = 0;
				 $this->objpdf->sety($yy);
			 	 $comeco  = $ii+1;
			 	 $passada = 0;
				 break;
	     }
            
	  }
	   
	  $this->objpdf->Roundedrect($xcol,$xlin+106,$xcol+105,20,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+110,'Motivo:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+134,$xlin+120,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->line($xcol+130,$xlin+110,$xcol+195,$xlin+110);
	  $this->objpdf->text($xcol+152,$xlin+114,'RECEBEDOR');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+110);
	  $this->objpdf->setx($xcol+1);
	  $this->objpdf->multicell(107,3,substr($this->Rresumo,0,450),0,"L");
	  
	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;
  }
?>
