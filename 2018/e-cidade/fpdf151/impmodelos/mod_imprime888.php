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
  $quant_itens = 5;
  $qReg        = $this->linhasdositens;
  $passou      = 0;
  for ($i=0; $i < $qReg; $i++){

     $contaobs++;
     if ((($contaobs == $quant_itens) || ($passou==0 && $qReg >= $quant_itens)) && ($qReg >= $quant_itens)){

        $iVias++;
        $total    += $contaobs;
        $contaobs  = 0;
        $iPag_a    = true;
        $passou    = 1;

     }
     if (isset($total) && $qReg < $quant_itens){

       $iVias++;
       $contaobs = 0;
       $iPag_b   = true;
       break;

     }

  }
  if ($iPag_a == true && $iPag_b == true) {
    $iVias = $iVias;
  } elseif ($iPag_a == true && $iPag_b == false) {
    $iVias = $iVias;
  } elseif ($iPag_a == false && $iPag_b == true) {
    $iVias = 1;
  }

}else{

  //14 itens por folha se não conter observação em nenhum item
  $quant_itens = 14;
  $qReg        = $this->linhasdositens;
  for ($i=0; $i < $qReg; $i++){
     $contaobs++;
     if ((($contaobs==$quant_itens) || ($passou==0 && $qReg >$quant_itens)) && ($qReg >$quant_itens)){

        $iVias++;
        $total    += $contaobs;
        $contaobs  = 0;
        $iPag_a    = true;
        $passou    = 1;

     }
     if (isset($total) && $qReg < $quant_itens){

         $iVias++;
         $contaobs = 0;
         $iPag_b   = true;
         break;

     }

    }
    if ($iPag_a == true && $iPag_b == true) {
      $iVias = $iVias;
    } elseif ($iPag_a==true && $iPag_b == false) {
      $iVias = $iVias;
    } elseif ($iPag_a == false && $iPag_b == true) {
      $iVias = 1;
    }

}

$iVias = $iVias*2;

for ($i = 0;$i < $iVias;$i++){

  if (($i % 2 ) == 0) {
    $this->objpdf->AddPage();
  }

  if ($this->linhasdositens <= $quant_itens){
    $comeco = 0;
  }

  $this->objpdf->setfillcolor(245);
  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'','1234');
  $this->objpdf->setfillcolor(255,255,255);
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text(110,$xlin-13,'RECIBO DE ENTREGA DE MEDICAMENTOS N'.chr(176).' '.$this->Rnumero);
  $this->objpdf->text(110,$xlin-10,'Retirada: '.$this->Rtipo);

  /*if ($this->Ratendrequi != null){
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text(110,$xlin-8,'ATENDIMENTO DA REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Ratendrequi);
  }*/

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
  $this->objpdf->text($xcol+24,$xlin+5,':  '.$this->Rdepart);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+2,$xlin+9,'CGS ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+24,$xlin+9,':  '.$this->Rnomeus);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+110,$xlin+9,'Atendente ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+125,$xlin+9,':  '.$this->ratendente." ".$this->rcodatend);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+2,$xlin+12,'Tipo de Receita ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+24,$xlin+12,':  '.$this->Rtpreceita);

  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+110,$xlin+12,'Receita ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+125,$xlin+12,':  '.$this->Rreceita);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+160,$xlin+12,'Validade ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+172,$xlin+12,':  '.db_formatar($this->Rdtvalidadereceita,"d"));

  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+110,$xlin+6,'Hora ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+125,$xlin+6,':  '.$this->Rhora);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+160,$xlin+6,'Data ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+172,$xlin+6,':  '.db_formatar($this->Rdata,"d"));
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+2,$xlin+15,'Profissional ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+24,$xlin+15,':  '.$this->rcodprof." ".$this->rprofissional);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+110,$xlin+15,'CRM ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+125,$xlin+15,':  '.$this->Rcrm);
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+160,$xlin+15,'CNS ');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+172,$xlin+15,':  '.$this->Rcns);

  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->Roundedrect($xcol,$xlin+17,$xcol+98+100,10,2,'DF','1234');
  if ($this->Rrequisitante != "") {

    $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+2,$xlin+21,'Requisitante ');
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+22,$xlin+21,':  '.$this->Rrequisitante);
    $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+130,$xlin+21,'Identidade ');
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+150,$xlin+21,':  '.$this->Rident);
    $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+2,$xlin+24,'Endereço ');
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+22,$xlin+24,':  '.$this->Rendereco);
    $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+130,$xlin+24,'Número ');
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+150,$xlin+24,':  '.$this->Rnumeros);
    $this->objpdf->Setfont('Arial','b',8);

  }

  $this->objpdf->Roundedrect($xcol,$xlin+28,202,75,2,'DF','1234');
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+169,$xlin+32,'QUANTIDADES');
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+2,$xlin+35,'CÓDIGO');
  $this->objpdf->text($xcol+32,$xlin+35,'DESCRIÇÃO');
  //$this->objpdf->text($xcol+95 ,$xlin+24,'LOCAL');
  $this->objpdf->text($xcol+117,$xlin+35,'UNIDADE');
  $this->objpdf->text($xcol+166,$xlin+35,'REQUISIT.');
  $this->objpdf->text($xcol+183,$xlin+35,'FORNECIDA');
  //$this->objpdf->text($xcol+12,$xlin+28,'OBS. ITEM');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->sety($xlin+35);

  $maiscol = 0;
  $cont    = 0;
  $yy 	   = $this->objpdf->gety();

  for($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {

    $cont++;
    $this->objpdf->setx($xcol+3+$maiscol);
    $this->objpdf->cell(28,5,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
    $this->objpdf->cell(85,5,substr(trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,40),0,0,"L",0);
    //$this->objpdf->cell(53,5,pg_result($this->recorddositens,$ii,$this->rlocalizacao),0,0,"L",0);
    $this->objpdf->cell(45,5,pg_result($this->recorddositens,$ii,$this->runidadesaida),0,0,"L",0);
    $this->objpdf->cell(25,5,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);
    $this->objpdf->cell(10,5,trim(pg_result($this->recorddositens,$ii,$this->rquantatend)),0,1,"C",0);
    $icodmater = pg_result($this->recorddositens,$ii,$this->rcodmaterial);

    if (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) != ''){

      $obsitens=substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,110);
      $this->objpdf->multicell(100,4,str_replace("\n",'',($obsitens)),0,"L");

    }
    if ($quant_itens==8 && (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) == '')){

      $obsitens="";
      $this->objpdf->cell(55,4,$obsitens,0,0,"L",0);

    }
    if ($this->Rdproxdisp[$icodmater] != "") {

      $this->objpdf->cell(60,4,"Próxima Dispensação: ".$this->Rdproxdisp[$icodmater],0,1,"L",0);

    } else {

      $this->objpdf->cell(60,4,"",0,1,"L",0);

    }
    if ((($ii+1) % $quant_itens ) == 0 && $ii > 0 && $passada == 0) {

      $maiscol = 0;
      $passada ++;
      $comeco  = $ii+1;
      break;

    }

    if (($ii+1) == $this->linhasdositens) {

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
  $this->objpdf->text($xcol+2,$xlin+110,'Posologia:');
  $this->objpdf->Setfont('Arial','b',8);
  $this->objpdf->text($xcol+134,$xlin+120,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
  $this->objpdf->line($xcol+130,$xlin+110,$xcol+195,$xlin+110);
  $this->objpdf->text($xcol+152,$xlin+114,'RECEBEDOR');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->sety($xlin+110);
  $this->objpdf->setx($xcol+1);
  $this->objpdf->multicell(107,3,substr($this->Rresumo,0,450),0,"L");
  
  if (($i % 2 ) == 0) {
    $xlin = 169;
  } else {
    $xlin = 20;
  }
}
?>
