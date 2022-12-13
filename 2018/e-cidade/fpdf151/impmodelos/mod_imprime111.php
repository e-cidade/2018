<?php


    	
    	
        global $contapagina;
	$contapagina=1;
        if(!in_array("cl_orcreservasol",get_declared_classes())){
          include("classes/db_orcreservasol_classe.php"); 
	}
	$clorcreservasol = new cl_orcreservasol;
////////// MODELO 111  -  SOLICITAÇÃO DE COMPRA
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;

	// Imprime caixa externa
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

        // Imprime o cabeçalho com dados sobre a prefeitura
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->Snumero,'s','0',6,'e'));
        $this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text(130,$xlin-9,'ORGAO');
	$this->objpdf->text(142,$xlin-9,': '.substr($this->Sorgao,0,35));
	$this->objpdf->text(130,$xlin-5,'UNIDADE');
	$this->objpdf->text(142,$xlin-5,': '.substr($this->Sunidade,0,35));
        $this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);

 	$this->objpdf->Setfont('Arial','B',8);
	// caixa para frases
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,9,2,'DF','1234');
	$this->objpdf->SetXY(4,$xlin+4);
	$this->objpdf->MultiCell(202,4,'QUANDO NECESSÁRIO FRETE, O MESMO CORRERÁ POR CONTA DO FORNECEDOR',0,"C",0);
	$this->objpdf->SetXY(4,$xlin+8);
	$this->objpdf->MultiCell(202,4,'TODO FRETE DEVERÁ SER PAGO PELA EMPRESA REMETENTE - O MATERIAL DEVERÁ SER DE PRIMEIRA QUALIDADE',0,"C",0);
	$this->objpdf->Setfont('Arial','',8);

        // Caixa com dados da solicitação
	$this->objpdf->rect($xcol,$xlin+13,$xcol+198,10,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+15,'Dados do Solicitação');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+18,'Departamento');
	$this->objpdf->text($xcol+109,$xlin+18,'Tipo');
	$this->objpdf->text($xcol+  2,$xlin+22,'Data');
	$this->objpdf->text($xcol+109,$xlin+22,'Val. Aprox.');

	// Imprime dados da solicitação
	$this->objpdf->text($xcol+ 23,$xlin+18,':  '.$this->Sdepart);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(isset($this->Svalor) && trim($this->Svalor)!=""){
          $this->Svalor = db_formatar($this->Svalor,'f');
	}
	$this->objpdf->text($xcol+125,$xlin+18,':  '.$this->Stipcom);
	$this->objpdf->text($xcol+ 23,$xlin+22,':  '.$this->Sdata);
	$this->objpdf->text($xcol+125,$xlin+22,':  R$ '.$this->Svalor);

        $this->objpdf->text($xcol+  2,$xlin+27,'Resumo');
	$this->objpdf->setxy($xcol+22,$xlin+24);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+24);
	$posini = $this->objpdf->gety();
	$this->objpdf->multicell(175,4,trim(AddSlashes($this->Sresumo)),0,"j");
	$setaut = $this->objpdf->gety();
	$this->objpdf->rect($xcol,$xlin+24,$xcol+198,$setaut-$posini,2,'DF','1234');

        $getdoy = 32;
	$contafornec = 0;
	if($this->linhasdosfornec>0){
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"true",$contapagina);
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    $contafornec += 4;
	  }

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut+0.8,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+   4,$setaut+4.2,'FORNECEDORES SUGERIDOS ');

	  $this->objpdf->rect($xcol    ,$setaut+6.8,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+15 ,$setaut+6.8,64,6,2,'DF','12');
	  $this->objpdf->rect($xcol+79 ,$setaut+6.8,63,6,2,'DF','12');
	  $this->objpdf->rect($xcol+142,$setaut+6.8,40,6,2,'DF','12');
	  $this->objpdf->rect($xcol+182,$setaut+6.8,20,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12.8,15,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+15 ,$setaut+12.8,64,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+79 ,$setaut+12.8,63,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$setaut+12.8,40,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+182,$setaut+12.8,20,$contafornec+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   4,$setaut+11,'CGM');
	  $this->objpdf->text($xcol+30.5,$setaut+11,'NOME/RAZÃO SOCIAL');
	  $this->objpdf->text($xcol+ 103,$setaut+11,'ENDEREÇO');
	  $this->objpdf->text($xcol+ 155,$setaut+11,'MUNICÍPIO');
	  $this->objpdf->text($xcol+184.5,$setaut+11,'TELEFONE');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13.8);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','L','L','L','C'));
	  $this->objpdf->SetWidths(array(15,64,63,40,20));
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    db_fieldsmemory($this->recorddosfornec,$i);
	    $cgmforn   = trim(pg_result($this->recorddosfornec,$i,$this->cgmforn));
	    $nomeforn  = trim(pg_result($this->recorddosfornec,$i,$this->nomeforn));
	    $enderforn = trim(pg_result($this->recorddosfornec,$i,$this->enderforn));
	    $numforn   = trim(pg_result($this->recorddosfornec,$i,$this->numforn));
	    $municforn = trim(pg_result($this->recorddosfornec,$i,$this->municforn));
	    $foneforn  = trim(pg_result($this->recorddosfornec,$i,$this->foneforn));
	    $this->objpdf->Row(array($cgmforn,$nomeforn,$enderforn.", ".$numforn,$municforn,$foneforn),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety();
	  $getdoy+= 0.8;
	  $getdoy = $getdoy-$xlin;
	  $contafornec+= 8;
	}else{
	  $getdoy += 4.8;
	}
        // Caixas dos label's
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 15,$xlin+$getdoy,20,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 35,$xlin+$getdoy,107,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+$getdoy,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+$getdoy,30,6,2,'DF','12');

        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,15,193-$contafornec,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 15,$xlin+$getdoy+6,20,193-$contafornec,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 35,$xlin+$getdoy+6,107,193-$contafornec,2,'DF','34');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+142,$xlin+$getdoy+6,30,193-$contafornec,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+172,$xlin+$getdoy+6,30,193-$contafornec,2,'DF','34');

   	$this->objpdf->sety($xlin+28);
	
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   4,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+15.5,$xlin+$getdoy+4,'QUANTIDADE');
	$this->objpdf->text($xcol+  70,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+$getdoy+4,'VALOR TOTAL');
        $maiscol = 0;

	$this->objpdf->setleftmargin(8);
	$this->objpdf->sety($xlin+$getdoy+7);

	$xtotal = 0;
	$muda_pag = false;
	$index = 0;

	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  $this->objpdf->SetWidths(array(10,22,22,95,30,30));
	  $this->objpdf->SetAligns(array('C','C','C','J','R','R'));
	  $pagina = $this->objpdf->PageNo();
	  db_fieldsmemory($this->recorddositens,$ii);
	  if($ii!=0 && $muda_pag==false){
	    $muda_pag = false;
            $this->objpdf->ln(0.3);
            $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
            $this->objpdf->ln(1.3);
	  }
	  
	  $codigo  = pg_result($this->recorddositens,$ii,"pc11_codigo");
	  $item  = pg_result($this->recorddositens,$ii,$this->item);
	  $quantitem = pg_result($this->recorddositens,$ii,$this->quantitem);
	  $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	  $valoritem = db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),"f");
	  $valtot= pg_result($this->recorddositens,$ii,$this->svalortot);
	  $valimp= db_formatar($valtot,'f');
	  $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	  $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	  $resum = pg_result($this->recorddositens,$ii,$this->sresum);
	  $just  = pg_result($this->recorddositens,$ii,$this->sjust);
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  $scodpcmater= pg_result($this->recorddositens,$ii,$this->scodpcmater);

	  $xtotal += $valtot;

	  if((isset($descricaoitem) && (trim($descricaoitem)=="" || $descricaoitem==null)) || !isset($descricaoitem)){
	    $descricaoitem=$resum;
	  }

	  if(isset($scodpcmater) && trim($scodpcmater)!=""){
	    $scodpcmater = trim($scodpcmater)." - ";
	  }
	  if(isset($prazo) && trim($prazo)!=""){
	    $prazo = "\nPRAZO: ".trim($prazo);
	  }
	  if(isset($pgto) && trim($pgto)!=""){
	    $pgto = "\nCONDIÇÃO: ".trim($pgto);
	  }
	  if(isset($resum) && trim($resum)!=""){
	    $resum = "\nRESUMO: ".trim($resum);
	  }
	  if(isset($just) && trim($just)!=""){
	    $just = "\nJUSTIFICATIVA: ".trim($just);
	  }	  

          $barran = "";
	  if(strlen($scodpcmater.$descricaoitem)>53){
	    $barran = "\n";
	  }
	  if((isset($servico) && (trim($servico)=="f" || trim($servico)=="")) || !isset($servico)){
	    $unid = $barran."\n".trim($unid);
	    if($susaquant=="t"){
	      $unid .= " ($quantunid UNIDADES)\n";
	    }
	  }else{
	    $unid = $barran."\nSERVIÇO";
	  }

	  $descricaoitem .= " - ".$unid;
	    
          $distanciar = 0;
	  if((isset($prazo) && trim($prazo)=="") && (isset($pgto) && trim($pgto)=="") && (isset($resum) && trim($resum)=="") && (isset($just) && trim($just)=="")){
	    $distanciar = 4;
	  }

	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->Row(array($item,
	      		     $quantitem,
	      		     $scodpcmater.$descricaoitem,
	      		     $valoritem,
	      		     $valimp),3,false,$distanciar);

	  $dist = 2.7;
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  
	  if(isset($unid) && $unid!=""){
	    $this->objpdf->Row(array('','',$unid,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	  
	  $this->objpdf->Setfont('Arial','',7);

	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','',$prazo,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','',$pgto,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($resum) && $resum!=""){
	    $this->objpdf->Row(array('','',$resum,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($just) && $just!=""){
	    $this->objpdf->Row(array('','',$just,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }

	  $this->objpdf->SetWidths(array(10,22,26,26,26,26,'1',30,30));
	  $pass = false;
	  $arr_dotac = array();
	  for($i=0;$i<$this->linhasdasdotac;$i++){
	    db_fieldsmemory($this->recorddasdotac,$i);
	    if(pg_result($this->recorddasdotac,$i,$this->dcodigo)==$codigo && !in_array(pg_result($this->recorddasdotac,$i,$this->dcoddot),$arr_dotac)){
              if($pass==false){
	        $pass = true;
		$this->objpdf->Setfont('Arial','B',7);
		$distc = 3.5;
		$distb = 3;
		$this->objpdf->SetAligns(array('C','C','C','C','C','C','C','R','R'));
		$this->objpdf->Row(array('','',"\n",'',''),3,false,$dist);
		$this->objpdf->Row(array('','',"DOTAÇÃO","ANO","ELEMENTO","RESERVADO",'',''),3,false,$dist);
	      }	      
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->SetAligns(array('C','C','C','C','C','C','C','R','R'));
	      $dquant   = pg_result($this->recorddasdotac,$i,$this->dquant);
	      $danousu  = pg_result($this->recorddasdotac,$i,$this->danousu);
	      $dcoddot  = pg_result($this->recorddasdotac,$i,$this->dcoddot);
	      $dvalor   = pg_result($this->recorddasdotac,$i,$this->dvalor);
	      $delemento= pg_result($this->recorddasdotac,$i,$this->delemento);
//	      $dreserva = pg_result($this->recorddasdotac,$i,$this->dreserva);
	      array_push($arr_dotac,$dcoddot);
	      if(isset($dcoddot) && trim($dcoddot)!=""){
		$result_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres as codigodareserva","","o82_solicitem=$codigo and o80_coddot=$dcoddot"));
		$ddvalor = "NÃO";
		if($clorcreservasol->numrows>0){
		  $ddvalor = "SIM";
		}
	        $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
		$this->objpdf->Row(array('',$dquant,$dcoddot,$danousu,$delemento,$ddvalor,'',db_formatar($dvalor/$dquant,"f"),db_formatar($dvalor,"f")),$distc,false,$distb);
              }
	    }else{
	      $pass = false;
	    }
	  }
	}
	$this->objpdf->Setfont('Arial','B',8);
	$maislin = 248;
        if($contapagina == 1){
	  $maislin = 211;
	}
        $this->objpdf->text(180,$xlin+$maislin+20,db_formatar($xtotal,'f'));
	if ($contapagina == 1){
	  $this->objpdf->rect($xcol,    $xlin+224.7,142,10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+230.7,'T O T A L');
	  
	  $this->objpdf->rect($xcol,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->rect($xcol+68,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->setfillcolor(0,0,0);

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+5,$xlin+244,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->text($xcol+20,$xlin+256,"AUTORIZO",0,4);
	  $this->objpdf->text($xcol+5,$xlin+268,substr($this->Sorgao,0,35));

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+93,$xlin+256,"AUTORIZO",0,4);
	  if(strtoupper(trim($this->municpref)) != 'GUAIBA'){
	    $this->objpdf->text($xcol+83,$xlin+268,'DIV. DE ABASTECIMENTO',0,40);
	  }

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+150,$xlin+256,"ORDENADOR DA DESPESA",0,4);
        }else{
	  $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+268,'T O T A L');
	}	




?>
