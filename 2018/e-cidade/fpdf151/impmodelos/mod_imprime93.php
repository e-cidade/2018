<?

//// RECIBO
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->settopmargin(1);
$this->objpdf->line(2,148.5,208,148.5);
$xlin = 20;
$xcol = 4;
for ($i = 0;$i < 2;$i++) {  
  
  $this->objpdf->setfillcolor(245);
  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
  $this->objpdf->setfillcolor(255,255,255);
  //    $this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
  $this->objpdf->Setfont('Arial', 'B', 13);
  $this->objpdf->text(165, $xlin - 14, 'GRM');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text(150, $xlin - 10, 'Guia de Recolhimento Municipal ');
  $this->objpdf->text($xcol+128, $xlin - 4, 'Recibo válido até '.$this->dtvenc);  
  //Via
  if( $i == 0 ){
    $str_via = 'Contribuinte';
  }else{
    $str_via = 'Prefeitura';
  }
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text(178,$xlin-1,($i+1).'ª Via '.$str_via );
  
  $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
  $this->objpdf->Setfont('Arial','B',9);
  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
  $this->objpdf->Setfont('Arial','',9);  
  $this->objpdf->text(40,$xlin-11,$this->enderpref);
  $this->objpdf->text(40,$xlin-8,$this->municpref);
  $this->objpdf->text(40,$xlin-5,$this->telefpref);
  $this->objpdf->text($xcol+60,$xlin-5,"CNPJ: ");
  $this->objpdf->text($xcol+70,$xlin-5,db_formatar($this->cgcpref,'cnpj')); 
  $this->objpdf->text(40,$xlin-2,$this->emailpref);
  //    $this->objpdf->setfillcolor(245);
  
  $this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->text($xcol+2,$xlin+4,'Identificação:');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+2,$xlin+7,'Nome :');
  $this->objpdf->text($xcol+17,$xlin+7,$this->nome);
  $this->objpdf->text($xcol+2,$xlin+11,'Endereço :');
  $this->objpdf->text($xcol+17,$xlin+11,$this->ender);
  
  $this->objpdf->text($xcol+2,  $xlin+15, 'Bairro :');
  $this->objpdf->text($xcol+17, $xlin+15, $this->bairrocontri);
  
  $this->objpdf->text($xcol+2,$xlin+19,'Município :');
  $this->objpdf->text($xcol+17,$xlin+19,"{$this->munic}");
  $this->objpdf->text($xcol+75,$xlin+15,'CEP :');
  $this->objpdf->text($xcol+82,$xlin+15,$this->cep);
  
  $this->objpdf->text($xcol+128,  $xlin, 'Data :'. date("d/m/Y",db_getsession("DB_datausu")). ' Hora: '.date("H:i:s"));
    
  $this->objpdf->text($xcol+75,$xlin+19,'CNPJ/CPF:');
  $this->objpdf->text($xcol+90,$xlin+19, db_formatar ($this->cgccpf, (strlen ($this->cgccpf) == 11 ? 'cpf' : 'cnpj')));  
  
  $this->objpdf->Setfont('Arial','',6);
  
  $this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
  
  $this->objpdf->text($xcol+128,$xlin+4,$this->identifica_dados);
  
  $this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr);
  $this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr);
  
  $this->objpdf->text($xcol+128,$xlin+11,$this->tipolograd);
  $this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
  $this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
  $this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
  $this->objpdf->text($xcol+128,$xlin+19,$this->tipobairro);
  $this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);
  
  $this->objpdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
  $this->objpdf->sety($xlin+24);
  $maiscol = 0;
  $yy = $this->objpdf->gety();
  
  for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
    
    $this->obsdescr  = null;
    if ($ii == 14 ){
      $maiscol = 100;
      $this->objpdf->sety($yy);
    }
    if($ii==0 || $ii == 14){
      
      $this->objpdf->setx($xcol+3+$maiscol);
      $this->objpdf->cell(5,3,"Rec",0,0,"L",0);
      $this->objpdf->cell(7,3,"Reduz",0,0,"L",0);
      $this->objpdf->cell(63,3,"Descrição",0,0,"L",0);
      $this->objpdf->cell(15,3,"Valor",0,1,"R",0);
      
    }
    if (pg_result($this->recorddadospagto,$ii,"k00_hist") == 918){
      
        $this->obsdescr = "(desconto)";
    }
    $codtipo = pg_result($this->recorddadospagto,$ii,"codtipo");
    $valor   = pg_result($this->recorddadospagto,$ii,$this->valor);
    $this->objpdf->setx($xcol+3+$maiscol);
    $this->objpdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
    $this->objpdf->cell(7,3,"(".trim(pg_result($this->recorddadospagto,$ii,$this->receitared)).")",0,0,"R",0);
    if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)." ".$this->obsdescr ),0,0,"L",0);
    }else{ 
      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)." ".$this->obsdescr),0,0,"L",0);
    }
    $this->objpdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);    
  }
  $this->objpdf->Roundedrect($xcol,$xlin+71, 148, 30, 2,'DF','1234');
  $this->objpdf->SetY($xlin+72);
  $this->objpdf->SetX($xcol+3);
 
  $this->objpdf->Setfont('Arial','',5);
  $this->objpdf->multicell(150,2,'INSTRUÇÕES :   '.$this->historico);
  $this->objpdf->SetX($xcol+3);
  //dados do desconto

  $this->objpdf->Roundedrect(154, $xlin+71,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(154, $xlin+81.5,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(154, $xlin+92,25,9,2,'DF','1234');
  
  $this->objpdf->Roundedrect(181,$xlin+71,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+81.5,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+92,25,9,2,'DF','1234');

  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->text(156, $xlin+73,'Valor Documento');
  $this->objpdf->text(156, $xlin+83.5,'( - ) Desconto ');
  $this->objpdf->text(156, $xlin+94,'( - ) Outras Deduções');

  $this->objpdf->text(182, $xlin+73,'(+) Outros Acréscimos');
  $this->objpdf->text(182, $xlin+83.5,'( + ) Juros / Encargos ');
  $this->objpdf->text(182, $xlin+94,'( + ) Mora / Multa');
  
  $totalrec   = db_formatar($this->totalrec,'f');
  $totaldesc  = db_formatar(abs($this->totaldesc),'f');
  $totalacres = db_formatar($this->totalacres,'f');
  $valtotal   = $this->valtotal;
  $this->objpdf->setfont('Arial','',10);
  $this->objpdf->setxy(154,$xlin+71);
  $this->objpdf->cell(25,9,$totalrec,0,0,"R");
  $this->objpdf->setxy(154,$xlin+81.5);
  $this->objpdf->cell(25,9,$totaldesc,0,0,"R");
  $this->objpdf->setxy(154,$xlin+91.5);
  $this->objpdf->cell(25,9, db_formatar($this->outras_deducoes, 'f'),0,0,"R");
  
  $this->objpdf->setxy(181,$xlin+71);
  $this->objpdf->cell(25,9,$totalacres, 0, 0,"R");
  $this->objpdf->setxy(181,$xlin+81.5);
  $this->objpdf->cell(25, 9, db_formatar($this->juros_encargos,'f') ,0, 0, "R");
  $this->objpdf->setxy(181,$xlin+91);
  $this->objpdf->cell(25, 9, db_formatar($this->multa_mora, 'f'), 0, 0, "R");

  $this->objpdf->setx(15);
 
   ///Totais
  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->Roundedrect(120,$xlin+103,32,9,2,'DF','1234');
  $this->objpdf->Roundedrect(154,$xlin+103,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+103,25,9,2,'DF','1234');
  $this->objpdf->text(122,$xlin+105,'Código de Arrecadação');
  $this->objpdf->text(156,$xlin+105,'Vencimento');
  $this->objpdf->text(183,$xlin+105,'( = ) Valor Cobrado R$');
  $this->objpdf->setfont('Arial','',10);
  $this->objpdf->text(156,$xlin+109,$this->dtvenc);
  $this->objpdf->text(122,$xlin+109,$this->numpre);
  $this->objpdf->setfont('Arial','b',10);
  $this->objpdf->setxy(181,($xlin+103));
  $this->objpdf->cell(25,9,$valtotal,0,0,"R");
  
  $this->objpdf->SetFont('Arial','B',5);
  $this->objpdf->text(140,$xlin+116,"A   U   T   E   N   T   I   C   A   Ç   Ã   O      M   E   C   Â   N   I   C   A");

  if (isset($this->k12_codautent)){
     $this->objpdf->SetFont('Arial','',8);
     $this->objpdf->text(138,$xlin+122,$this->k12_codautent);
  }
  
  $this->objpdf->setfillcolor(0,0,0);
  $this->objpdf->SetFont('Arial','',4);
  $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
  //    $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto . ' - ' . ($i == 1?'2ª VIA - CONTRIBUINTE':'1ª VIA - PREFEITURA'),'U'); // texto no canhoto do carne
  $this->objpdf->setfont('Arial','',11);
  $this->objpdf->text(5,$xlin+108, @$this->linhadigitavel);
  
  if( $i == 1 ){
    $this->objpdf->int25(5,$xlin+110,$this->codigobarras, 12,0.341);
  }
  $xlin = 169;
 
}