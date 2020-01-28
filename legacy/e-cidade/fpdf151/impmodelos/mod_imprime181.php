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
    //  8 itens por folha se algum item conter observação
    $quant_itens = 8;
    $qReg=$this->linhasdositens;
    $passou=0;
    for ($i=0; $i < $qReg; $i++){
       $contaobs++;

       if ((($contaobs==$quant_itens) || ($passou==0 && $qReg >=$quant_itens)) && ($qReg >=$quant_itens)){
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
    }else{
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

	  $iCorFundo = 245;
	  if (isset($this->iCorFundo) && $this->iCorFundo == 2) {
	  	$iCorFundo = 255;
	  }

	  $this->objpdf->setfillcolor($iCorFundo);
	  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
	  $this->objpdf->Setfont('Arial','B',11);
	  $this->objpdf->text(110,$xlin-13,'REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Rnumero);

	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->text(110,$xlin-9, 'TIPO: '.$this->Rauto);

    if ($this->Ratendrequi != null){
	       $this->objpdf->Setfont('Arial','B',8);
	       $this->objpdf->text(110,$xlin-5.5,'ATENDIMENTO DA REQUISIÇÃO DE SAÍDA DE MATERIAIS N'.chr(176).' '.$this->Ratendrequi);
    }

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
	  $this->objpdf->text($xcol+2,$xlin+6,'Departamento Solicitante ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+37,$xlin+6,':  '.$this->RdepartCod.' - '.$this->Rdepart);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+10,'Usuario');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+37,$xlin+10,':  '.$this->Rnomeus);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+6,'Hora ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+120,$xlin+6,':  '.$this->Rhora);
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+110,$xlin+10,'Data ');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+120,$xlin+10,':  '.db_formatar($this->Rdata,"d"));

	  $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+2,$xlin+14,'Almoxarifado Origem');
    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->text($xcol+37,$xlin+14,':  '.$this->Ralmoxarifado_cod.' - '.$this->Ralmoxarifado_nome);

    $this->objpdf->Setfont('Arial','',6);

	  $this->objpdf->Roundedrect($xcol,$xlin+18,202,78,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+160,$xlin+21,'QUANTIDADES');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+24,'CÓDIGO');
	  $this->objpdf->text($xcol+22,$xlin+24,'DESCRIÇÃO');
	  $this->objpdf->text($xcol+85 ,$xlin+24,'LOCAL');
	  $this->objpdf->text($xcol+132,$xlin+24,'UNID.');
	  $this->objpdf->text($xcol+145,$xlin+24,'REQUISIT.');
	  $this->objpdf->text($xcol+162,$xlin+24,'FORNECIDA');
	  $this->objpdf->text($xcol+182,$xlin+24,'ANULADA');
	  //$this->objpdf->text($xcol+12,$xlin+28,'OBS. ITEM');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+25);

	  $maiscol   = 0;
		$cont      = 0;
	  $yy 	     = $this->objpdf->gety();
	  $iTotalReq = 0;

    for($ii = $comeco;$ii < $this->linhasdositens ;$ii++) {
	     $cont++;
	     $iTotalReq += trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens));

	     /**
        * verifico se existe unidade de saída para o material, caso exista, utilizamos esta unidade de medida
        * para mostrar no relatório
        *
        * @todo verificar uma forma de otimizar este procedimento
	      */
       $iCodigoUnidadeSaida = pg_result($this->recorddositens,$ii, "unidade_saida_material");
       $sSiglaUnidadeSaida  = "";
	     if ($iCodigoUnidadeSaida != "") {

	       $oDaoMatUnid      = db_utils::getDao("matunid");
	       $sSqlBuscaUnidade = $oDaoMatUnid->sql_query_file($iCodigoUnidadeSaida, "m61_abrev");
	       $rsBuscaUnidade   = $oDaoMatUnid->sql_record($sSqlBuscaUnidade);
	       $sSiglaUnidadeSaida = db_utils::fieldsMemory($rsBuscaUnidade, 0)->m61_abrev;
	     }

       $this->objpdf->setx($xcol+3+$maiscol);
       $this->objpdf->cell(18.2,5,trim(pg_result($this->recorddositens,$ii,$this->rcodmaterial)),0,0,"L",0);
	     $this->objpdf->cell(62.8,5,substr(trim(pg_result($this->recorddositens,$ii,$this->rdescmaterial)),0,70),0,0,"L",0);
	     $this->objpdf->cell(47,5,pg_result($this->recorddositens,$ii,$this->rlocalizacao),0,0,"L",0);

	     if ($iCodigoUnidadeSaida != "" && $sSiglaUnidadeSaida != "") {
	       $this->objpdf->cell(10,5,$sSiglaUnidadeSaida,0,0,"L",0);
	     } else {
	       $this->objpdf->cell(10,5,pg_result($this->recorddositens,$ii,$this->runidadesaida),0,0,"L",0);
	     }

	     $this->objpdf->cell(20,5,trim(pg_result($this->recorddositens,$ii,$this->rquantdeitens)),0,0,"C",0);
	     $this->objpdf->cell(18,5,trim(pg_result($this->recorddositens,$ii,$this->rquantatend)),0,0,"C",0);
	     $this->objpdf->cell(18,5,trim(pg_result($this->recorddositens,$ii,$this->rquantanulada)),0,1,"C",0);
       if (trim(pg_result($this->recorddositens,$ii,$this->robsdositens)) != ''){
         $obsitens=substr(trim(pg_result($this->recorddositens,$ii,$this->robsdositens)),0,220);
          $this->objpdf->multicell(180,4,str_replace("\n",'',($obsitens)));
	     }
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

	  /*
	   * TOTALIZADORES
	   */
	  $this->objpdf->Setfont('Arial','b',8);
    $this->objpdf->text($xcol+24,$xlin+99.5,'Total de Itens: '.$cont.'/'.$this->linhasdositens);
    $this->objpdf->text($xcol+143,$xlin+99.5,'Total de Requisições: '.$iTotalReq.'/'.$this->Ttotalreq);


    $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->Roundedrect($xcol,$xlin+102,$xcol+115,21,2,'DF','1234'); //$this->objpdf->Roundedrect($xcol,$xlin+98,$xcol+105,25,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+2,$xlin+106,'OBS:');
	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+134,$xlin+120,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->line($xcol+130,$xlin+110,$xcol+195,$xlin+110);
	  $this->objpdf->text($xcol+152,$xlin+114,'RECEBEDOR');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->sety($xlin+107);
	  $this->objpdf->setx($xcol+1);
	  $this->objpdf->multicell(107,3,substr($this->Rresumo,0,450),0,"L");

	  $this->objpdf->Setfont('Arial','b',8);
	  $this->objpdf->text($xcol+196,$xlin+125, $this->objpdf->PageNo()."/{nb}");

	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;
  }
?>
