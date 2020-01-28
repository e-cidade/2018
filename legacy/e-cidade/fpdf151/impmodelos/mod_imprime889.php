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
    $quant_itens = 20;
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

$iVias=$iVias;

for ($i = 0;$i < $iVias;$i++){
	  
		if (($i % 2 ) == 0) {
	    $this->objpdf->AddPage();
	  }

		if ($this->linhasdositens <= $quant_itens){
      $comeco = 0;
    }


	   $this->objpdf->setfillcolor(245);
          $this->objpdf->roundedrect($xcol-2,$xlin-19,206,290,2,'DF','1234');
          $this->objpdf->setfillcolor(255,255,255);
          $this->objpdf->Setfont('Arial','B',8);
          $this->objpdf->text(110,$xlin-13,'SOLICITAÇÃO DE TRANSFERÊNCIA N'.chr(176).' '.$this->Rnumero);


	  $this->objpdf->Image('imagens/files/logo_boleto.png',10,$xlin-17,12);
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(30,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',9);
	  $this->objpdf->text(30,$xlin-11,$this->enderpref);
	  $this->objpdf->text(30,$xlin-8,$this->municpref);
	  $this->objpdf->text(30,$xlin-5,$this->telefpref);
	  $this->objpdf->text(30,$xlin-2,$this->emailpref);
	  
	  
	  $this->objpdf->Roundedrect($xcol,$xlin+1,$xcol+95,15,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+5,'Departamento de Origem ');
	  $this->objpdf->text($xcol+2,$xlin+9,'Código ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+22,$xlin+9,':  '.$this->codalmox);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+13,'Departamento');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+22,$xlin+13,':  '.$this->almox);

	  $this->objpdf->Roundedrect($xcol+103,$xlin+1,$xcol+95,15,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+105,$xlin+5,'Departamento de Destino ');
	  $this->objpdf->text($xcol+105,$xlin+9,'Código ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+125,$xlin+9,':  '.$this->coddepartamento);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+105,$xlin+13,'Departamento');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+125,$xlin+13,':  '.$this->Rdepart);
	  

	  $this->objpdf->Roundedrect($xcol,$xlin+20,202,185,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+169,$xlin+25,'QUANTIDADES');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+25,'CÓDIGO');
	  $this->objpdf->text($xcol+32,$xlin+25,'DESCRIÇÃO');
	  //$this->objpdf->text($xcol+95 ,$xlin+24,'LOCAL');
	  $this->objpdf->text($xcol+117,$xlin+25,'UNIDADE');
	  $this->objpdf->text($xcol+166,$xlin+30,'REQUISIT.');
	  $this->objpdf->text($xcol+183,$xlin+30,'FORNECIDA');
	 //$this->objpdf->text($xcol+12,$xlin+28,'OBS. ITEM');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+30);

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
     
      /* if (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) != ''){
         $obsitens=substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,110);
          $this->objpdf->multicell(180,4,str_replace("\n",'',($obsitens)));
	     }*/
       if ($quant_itens==8 && (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) == '')){
         $obsitens="";
         $this->objpdf->multicell(180,4,$obsitens);
       }
       
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
	  $result_endent = pg_exec("select j14_nome as j14_nome_almox, numero as numero_almox, compl as compl_almox, j13_descr as j13_descr_almox, fonedepto as fone_almox, ramaldepto as ramal_almox, faxdepto as fax_almox
														from db_departender 
														inner join db_depart on db_depart.coddepto = db_departender.coddepto
														inner join ruas on j14_codigo = codlograd 
														inner join bairro on j13_codi = codbairro where db_departender.coddepto = " . $this->coddepartamento);
	if (pg_numrows($result_endent) > 0) {
  	  db_fieldsmemory($result_endent,0,true);
	  global $j14_nome_almox;
	  global $numero_almox;
	  global $compl_almox;
	  global $j13_descr_almox;
	  global $fone_almox;
	  global $ramal_almox;
	  global $fax_almox;	 
	}
	  
	  $this->objpdf->Roundedrect($xcol,$xlin+209,$xcol+197,20,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+215,'Endereço de Entrega dos Materiais');
	  $this->objpdf->text($xcol+2,$xlin+220,'Rua:');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+10,$xlin+220, @$j14_nome_almox);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+225,'Bairro:');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+12,$xlin+225, @$j13_descr_almox);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+80,$xlin+220,'Nº:');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+85,$xlin+220, @$numero_almox);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+120,$xlin+220,'Complemento:');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+140,$xlin+220, @$compl_almox);	 
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+220);
	  $this->objpdf->setx($xcol+1);
	   
	  $this->objpdf->Roundedrect($xcol,$xlin+235,$xcol+197,25,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+10,$xlin+238,'Atendente/Almoxarife');
	  $this->objpdf->text($xcol+70,$xlin+265,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->line($xcol+30,$xlin+250,$xcol+185,$xlin+250);
	  $this->objpdf->text($xcol+92,$xlin+255,'Responsável Almoxarifado');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+220);
	  $this->objpdf->setx($xcol+1);
	  
	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;
  }
?>
