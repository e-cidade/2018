<?php
ini_set('error_reporting', '0');
///////////////////////////////////////  MODELO 17  -  SOLICITA��O DE COMPRA SEGUNDO MODELO  ////////////////////////////////
  global $contapagina, $resparag, $resparagpadrao, $db61_texto, $db02_texto, $xtotal;
  $contapagina = 1;
  
  if (!in_array("cl_orcreservasol", get_declared_classes())) {
    include ("classes/db_orcreservasol_classe.php");
  }
  
  $clorcreservasol = new cl_orcreservasol;
  ////////// MODELO 17  -  SOLICITA��O DE COMPRA SEGUNDO MODELO 
  $this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $pagina = 1;
  $xlin   = 20;
  $xcol   = 4;

  // Imprime caixa externa
  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
   
  $sTipo              = "TIPO";
  $sDescricaoTipo     = 'SOLICITA��O DE COMPRA N'.CHR(176);
  $lImprimeTipo       = false;
  $lImprimeEstimativa = false;
  $lImprimeCompilacaoDescricao = false;
  
  if( isset($this->StipoSolicitacao) ){
    $sRodapeCabecalho   = ': '.substr($this->StipoSolicitacao, 1, 40);
    $iLicitacaoTipo     = substr($this->StipoSolicitacao, 0, 1);
  }else{
  	$iLicitacaoTipo     = $this->iTipo;
  }

  switch ($iLicitacaoTipo) {
    
    case '3':
    case '4':
    case '6':
      
      $sDescricaoTipo   = substr($this->StipoSolicitacao, 1, 40);
      $sDescricaoTipo   = $sDescricaoTipo;
      $sRodapeCabecalho = 'SOLICITA��O DE COMPRA N:  ';
      $lImprimeTipo     = true;
    break;  

    default:break;
  }

  if ( $iLicitacaoTipo == 5 || $iLicitacaoTipo == 4 ){
    if( $this->Stiposolicitacaopai == 6 ){
    	$lImprimeCompilacaoDescricao = true;
    }elseif( $this->Stiposolicitacaopai == 3 ){
    	$lImprimeEstimativa = true;
    }
  }
  // Imprime o cabe�alho com dados sobre a prefeitura
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text(130, $xlin -15, $sDescricaoTipo);
  
  if (!$lImprimeTipo) {
    $this->objpdf->text(185, $xlin - 15, db_formatar($this->Snumero, 's', '0', 6, 'e'));
  }
  
  if ($lImprimeCompilacaoDescricao) {
  	$this->objpdf->text(130, $xlin - 12, "Registro de Pre�o:" );
  	$this->objpdf->text(168.5, $xlin - 12, "compila��o ".db_formatar($this->Scodigosolicitacaopai, 's', '0', 6, 'e'));
  }
  
  if ($lImprimeEstimativa) {
  	$this->objpdf->text(130, $xlin - 12, "Abertura de Registro de Pre�o:" );
  	$this->objpdf->text(185, $xlin - 12, db_formatar($this->Scodigosolicitacaopai, 's', '0', 6, 'e'));
  }
  
  $this->objpdf->Setfont('Arial', 'B', 7);
  $this->objpdf->text(130, $xlin -7, 'ORG�O');
  $this->objpdf->text(142, $xlin -7, ': '.substr($this->Sorgao, 0, 40));
  $this->objpdf->text(130, $xlin -4, 'UNIDADE');
  $this->objpdf->text(142, $xlin -4, ': '.substr($this->Sunidade, 0, 40));
  $this->objpdf->text(130, $xlin -1, 'USU�RIO');
  $this->objpdf->text(142, $xlin -1, ': '.substr($this->Susuarioger, 0, 40));

  if (isset($this->StipoSolicitacao) && !$lImprimeTipo  ) {

  	$this->objpdf->text(130, $xlin +2, $sTipo);
  	$this->objpdf->text(142, $xlin +2, substr($sRodapeCabecalho, 0, 40));
    
  } else {

  	if(!$lImprimeCompilacaoDescricao){
    	$this->objpdf->text(130, $xlin +2, $sRodapeCabecalho.CHR(15). db_formatar($this->Snumero, 's', '0', 6, 'e'));
  	}
  }

  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(40, $xlin -15, $this->prefeitura);
  $this->objpdf->Setfont('Arial', '', 9);
  $this->objpdf->text(40, $xlin -11, $this->enderpref);
  $this->objpdf->text(40, $xlin -8, $this->municpref);
  $this->objpdf->text(40, $xlin -5, $this->telefpref);
  $this->objpdf->text(40, $xlin -2, $this->emailpref);
  $this->objpdf->text(40, $xlin +1, db_formatar($this->cgcpref, 'cnpj'));

  $this->objpdf->Setfont('Arial', 'B', 8);

  // caixa para frases
  $this->objpdf->rect($xcol, $xlin +3, $xcol +198, 9, 2, 'DF', '1234');
  $this->objpdf->SetXY(4, $xlin +4);
  $this->objpdf->MultiCell(202, 4, 'QUANDO NECESS�RIO FRETE, O MESMO CORRER� POR CONTA DO FORNECEDOR', 0, "C", 0);
  $this->objpdf->SetXY(4, $xlin +8);
  $this->objpdf->MultiCell(202, 4, 'TODO FRETE DEVER� SER PAGO PELA EMPRESA REMETENTE - O MATERIAL DEVER� SER DE PRIMEIRA QUALIDADE', 0, "C", 0);
  $this->objpdf->Setfont('Arial', '', 8);

  // Caixa com dados da solicita��o
  $this->objpdf->rect($xcol, $xlin +13, $xcol +198, 10, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol +2, $xlin +15, 'Dados da Solicita��o');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol +2, $xlin +18, 'Departamento');
  $this->objpdf->text($xcol +109, $xlin +18, 'Tipo');
  $this->objpdf->text($xcol +2, $xlin +22, 'Data');
  $this->objpdf->text($xcol +45, $xlin +22, 'Val. Aprox.');
  $this->objpdf->text($xcol +109, $xlin +22, 'P.A.');

  // Imprime dados da solicita��o
  if (isset($this->Scoddepart) && !empty($this->Scoddepart)) {
    $this->objpdf->text($xcol +23, $xlin +18, ":  {$this->Scoddepart}  - {$this->Sdepart}");  
  } else {
    $this->objpdf->text($xcol +23, $xlin +18, ": {$this->Sdepart}");  
  }
  
  if (isset ($this->Sdata) && trim($this->Sdata) != "") {
    $this->Sdata = db_formatar($this->Sdata, 'd');
  }
  if (isset ($this->Svalor) && trim($this->Svalor) != "") {
    $this->Svalor = db_formatar($this->Svalor, "f");
  }
  $this->objpdf->text($xcol +125, $xlin +18, ':  '.$this->Stipcom);
  $this->objpdf->text($xcol +23, $xlin +22, ':  '.$this->Sdata);
  $this->objpdf->text($xcol +60, $xlin +22, ':  R$ '.$this->Svalor);
  $this->objpdf->text($xcol +125, $xlin +22, ': '.@$this->processoAdministrativo);

  $this->objpdf->text($xcol +2, $xlin +27, 'Resumo');
  $this->objpdf->setxy($xcol +22, $xlin +24);
  $this->objpdf->cell(3, 4, ':  ', 0, 0, "L", 0);
  $this->objpdf->setxy($xcol +24.5, $xlin +24);
  $posini = $this->objpdf->gety();
  $this->objpdf->multicell(175, 4, trim(AddSlashes($this->Sresumo)), 0, "j");
  $setaut = $this->objpdf->gety();

  $oldsetaut = $setaut;

  $setaut += 8;
  $newsetaut = $setaut;
  if ($setaut > 64) {
    $newsetaut = $setaut -8;
    $tiramenos = $setaut -64;
    $setaut = $setaut - $posini;
  } else
    if ($setaut == 64) {
      $newsetaut = $setaut -8;
      $setaut -= 8;
    } else
      if ($setaut == 60) {
        $newsetaut = $setaut -4;
        $setaut -= 4;
      }

  $this->objpdf->rect($xcol, $xlin +24, $xcol +198, $newsetaut - $posini, 2, 'DF', '1234');

  $getdoy = 32;
  $contafornec = 0;
  if ($this->linhasdosfornec > 0) {
    $x = $this->muda_pag($pagina, $xlin, $xcol, "true", $contapagina);
    for ($i = 0; $i < $this->linhasdosfornec; $i ++) {
      $contafornec += 8;
      break;
    }
    $onze = 11;
    if ($oldsetaut +8 > 64) {
      $setaut += 36;
    }

    $this->objpdf->Setfont('Arial', 'B', 8);
    
    // Caixa de texto para labels 
    $this->objpdf->rect($xcol, $setaut +0.8, 202, 6, 2, 'DF', '12');
    $this->objpdf->text($xcol +4, $setaut +4.2, 'FORNECEDORES SUGERIDOS ');

    $this->objpdf->rect($xcol, $setaut +6.8, 15, 6, 2, 'DF', '12');
    $this->objpdf->rect($xcol +15, $setaut +6.8, 64, 6, 2, 'DF', '12');
    $this->objpdf->rect($xcol +79, $setaut +6.8, 63, 6, 2, 'DF', '12');
    $this->objpdf->rect($xcol +142, $setaut +6.8, 40, 6, 2, 'DF', '12');
    $this->objpdf->rect($xcol +182, $setaut +6.8, 20, 6, 2, 'DF', '12');

    $this->objpdf->rect($xcol, $setaut +12.8, 15, $contafornec +1, 2, 'DF', '34');
    $this->objpdf->rect($xcol +15, $setaut +12.8, 64, $contafornec +1, 2, 'DF', '34');
    $this->objpdf->rect($xcol +79, $setaut +12.8, 63, $contafornec +1, 2, 'DF', '34');
    $this->objpdf->rect($xcol +142, $setaut +12.8, 40, $contafornec +1, 2, 'DF', '34');
    $this->objpdf->rect($xcol +182, $setaut +12.8, 20, $contafornec +1, 2, 'DF', '34');
    $this->objpdf->sety($xlin +66);

    // Label das colunas
    $this->objpdf->text($xcol +4, $setaut +11, 'CGM');
    $this->objpdf->text($xcol +30.5, $setaut +11, 'NOME/RAZ�O SOCIAL');
    $this->objpdf->text($xcol +103, $setaut +11, 'ENDERE�O');
    $this->objpdf->text($xcol +155, $setaut +11, 'MUNIC�PIO');
    $this->objpdf->text($xcol +184.5, $setaut +11, 'TELEFONE');

    // Seta altura nova para impress�o dos dados
    $this->objpdf->sety($setaut +13.8);
    $this->objpdf->setx($xcol);
    $this->objpdf->setleftmargin(4);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->SetAligns(array ('C', 'L', 'L', 'L', 'C'));
    $this->objpdf->SetWidths(array (15, 64, 63, 40, 20));

    for ($i = 0; $i < $this->linhasdosfornec; $i ++) {

      db_fieldsmemory($this->recorddosfornec, $i);
      $cgmforn  = trim(pg_result($this->recorddosfornec, $i, $this->cgmforn));
      $nomeforn  = trim(pg_result($this->recorddosfornec, $i, $this->nomeforn));
      $enderforn = trim(pg_result($this->recorddosfornec, $i, $this->enderforn));
      $numforn   = trim(pg_result($this->recorddosfornec, $i, $this->numforn));
      $municforn = trim(pg_result($this->recorddosfornec, $i, $this->municforn));
      $foneforn  = trim(pg_result($this->recorddosfornec, $i, $this->foneforn));
      $cgccpf    = trim(pg_result($this->recorddosfornec, $i, $this->cgccpf));
      $this->objpdf->Row(array ($cgmforn, $nomeforn."\n"."CNPJ/CPF: ".$cgccpf, $enderforn.", ".$numforn, $municforn, $foneforn), 4, false, 4);
      break;
    }

    if($this->linhasdosfornec > 1){
        $this->objpdf->cell(20, 10, "Obs.: Existem mais ".($this->linhasdosfornec-1)." fornecedor(es) sugerido(s).",0,1,"L", 0);
    }
    $getdoy = $this->objpdf->gety();
    $getdoy = $getdoy - $xlin;
  } else {
    $getdoy += 4.8;
    if (($oldsetaut +8) > 64) {
      $getdoy += ($this->objpdf->NbLines(175,trim(AddSlashes($this->Sresumo)))*4)-12;
    }
  }

  // Caixas dos label's
  $this->objpdf->rect($xcol, $xlin + $getdoy, 10, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +10, $xlin + $getdoy, 12, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +22, $xlin + $getdoy, 22, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +44, $xlin + $getdoy, 98, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +142, $xlin + $getdoy, 30, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol +172, $xlin + $getdoy, 30, 6, 2, 'DF', '12');

  $menos = 16.9;
  if ($this->linhasdosfornec == 0) {
    $menos = 11;
  }
  if (isset ($tiramenos)) {
    $menos = $menos + $tiramenos;
    if ($menos < 0) {
      $menos = - $menos;
    }
  }

  if($this->linhasdosfornec >= 1){
      $menos = 53;
  }

  // Caixa dos itens
  $this->objpdf->rect($xcol, $xlin + $getdoy +6, 10, 194-$menos, 2, 'DF', '34');
  // Caixa da quantidade
  $this->objpdf->rect($xcol +10, $xlin + $getdoy +6, 12, 194-$menos, 2, 'DF', '34');

  $this->objpdf->rect($xcol +22, $xlin + $getdoy +6, 22, 194-$menos, 2, 'DF', '34');
  // Caixa dos materiais ou servi�os
  $this->objpdf->rect($xcol +44, $xlin + $getdoy +6, 98, 194-$menos, 2, 'DF', '34');
  // Caixa dos valores unit�rios
  $this->objpdf->rect($xcol +142, $xlin + $getdoy +6, 30, 194-$menos, 2, 'DF', '');
  // Caixa dos valores totais dos itens
  $this->objpdf->rect($xcol +172, $xlin + $getdoy +6, 30, 194-$menos, 2, 'DF', '34');

  $this->objpdf->sety($xlin +28);

  // Label das colunas
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol +2, $xlin + $getdoy +4, 'ITEM');
  $this->objpdf->text($xcol +11, $xlin + $getdoy +4, 'QUANT');
  $this->objpdf->text($xcol +30, $xlin + $getdoy +4, 'REF');
  $this->objpdf->text($xcol +70, $xlin + $getdoy +4, 'MATERIAL OU SERVI�O');
  $this->objpdf->text($xcol +145, $xlin + $getdoy +4, 'VALOR UNIT�RIO');
  $this->objpdf->text($xcol +176, $xlin + $getdoy +4, 'VALOR TOTAL');
  $maiscol = 0;

  $this->objpdf->setleftmargin(3);
  $this->objpdf->sety($xlin + $getdoy +7);

  $xtotal = 0;
  $muda_pag = false;
  $index = 0;

  $arr_antigadotac = Array ();
  $arr_antigaestru = Array ();
  $elementoant = "";
  for ($i = 0; $i < $this->linhasdasdotac; $i ++) {
        db_fieldsmemory($this->recorddasdotac, $i);
        $danousu    = pg_result($this->recorddasdotac, $i, $this->danousu);
        $dotacao    = pg_result($this->recorddasdotac, $i, $this->dcoddot);
        $estrutu    = pg_result($this->recorddasdotac, $i, $this->delemento);
        $descrunid  = pg_result($this->recorddasdotac, $i, $this->descrunid);
        $dcprojativ = pg_result($this->recorddasdotac, $i, $this->dcprojativ);
        $dctiporec  = pg_result($this->recorddasdotac, $i, $this->dctiporec);
        $dprojativ  = pg_result($this->recorddasdotac, $i, $this->dprojativ);
        $dtiporec   = pg_result($this->recorddasdotac, $i, $this->dtiporec);
        $ddescrest  = pg_result($this->recorddasdotac, $i, $this->ddescrest);

        $this->objpdf->SetWidths(array (10, 12, 24, 95, 30, 30));
        $this->objpdf->SetAligns(array ('C', 'C', 'C', 'J', 'R', 'R'));
        if(trim($dotacao) != ""){
            if(!in_array($dotacao.$danousu, $arr_antigadotac)){
                $arr_antigadotac[$dotacao.$danousu] = $dotacao.$danousu;
                $this->objpdf->Setfont('Arial', 'b', 7);
                if(!in_array($estrutu, $arr_antigaestru) && trim($estrutu) != ""){
                     $arr_antigaestru[$estrutu] = $estrutu;
                     if(isset($estrutu) && trim($estrutu) != ""){
                         $estrutu = " - ".$estrutu;
                     } else {
                         $estrutu = "";
                     }
                } else {
                     $estrutu = "";
                }
                if($i != 0 && $muda_pag == false){
                    $muda_pag = false;
                    $this->objpdf->ln(0.3);
                    $this->objpdf->rect(4, $this->objpdf->gety(), 202, 0, 1, 'DF', '1234');
                    $this->objpdf->ln(1.3);
                }
                $mais = $this->objpdf->NbLines(95,"Dota��o: ".$dotacao."/".$danousu.$estrutu);
                $mostra = $xlin;
                $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);      
                $this->objpdf->Row(array ('', '', '', "Dota��o: ".$dotacao."/".$danousu.$estrutu, '', ''), 3, false, 3);
        
                $mais = $this->objpdf->NbLines(95,"Unidade Or�ament�ria: ".$descrunid);
                $mostra = $xlin;
                $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);      
                $this->objpdf->Row(array ('', '', '', "Unidade Or�ament�ria: ".$descrunid, '', ''), 3, false, 3);

                $mais = $this->objpdf->NbLines(95,"Proj/Ativ: $dcprojativ - ".$dprojativ);
                $mostra = $xlin;
                $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
                $this->objpdf->Row(array ('', '', '', "Proj/Ativ: $dcprojativ - ".$dprojativ, '', ''), 3, false, 3, 0, 0, true);

                $mais = $this->objpdf->NbLines(95,"Elemento: ".$ddescrest);
                $mostra = $xlin;
                $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
                $this->objpdf->Row(array ('', '', '', "Elemento: ".$ddescrest, '', ''), 3, false, 3, 0, 0, true);

                $mais = $this->objpdf->NbLines(95,"Recurso: $dctiporec - ".$dtiporec);
                $mostra = $xlin;
                $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
                $this->objpdf->Row(array ('', '', '', "Recurso: $dctiporec - ".$dtiporec, '', ''), 3, false, 3, 0, 0, true);
            }
        } else {
            $mais = $this->objpdf->NbLines(95,"ITEM SEM DOTA��O");
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Setfont('Arial', 'B', 8);
            $this->objpdf->Row(array ('', '', '', "ITEM SEM DOTA��O", '', ''), 3, false, 3);
        }

        $codigo        = pg_result($this->recorddasdotac, $i, "pc11_codigo");
        $item          = pg_result($this->recorddasdotac, $i, $this->item);
        $descricaoitem = pg_result($this->recorddasdotac, $i, $this->descricaoitem);
        $quantitem     = pg_result($this->recorddasdotac, $i, $this->quantitem);
        
        $valoritem     = db_formatar(pg_result($this->recorddasdotac, $i, $this->valoritem),"f"," ",0,"d",$this->casadec);
        
        $valtot        = pg_result($this->recorddasdotac, $i, $this->svalortot);
        $valimp        = $valtot;
        $prazo         = str_replace("\\n", "\n",pg_result($this->recorddasdotac, $i, $this->sprazo));
        $pgto          = str_replace("\\n", "\n",pg_result($this->recorddasdotac, $i, $this->spgto));
        $resum         = str_replace("\\n", "\n",pg_result($this->recorddasdotac, $i, $this->sresum));
        $just          = str_replace("\\n", "\n",pg_result($this->recorddasdotac, $i, $this->sjust));
        $unid          = pg_result($this->recorddasdotac, $i, $this->sunidade);
        $abrevunid     = pg_result($this->recorddasdotac, $i, $this->sabrevunidade);
        $servico       = pg_result($this->recorddasdotac, $i, $this->sservico);
        $quantunid     = pg_result($this->recorddasdotac, $i, $this->squantunid);
        $susaquant     = pg_result($this->recorddasdotac, $i, $this->susaquant);
        $scodpcmater   = pg_result($this->recorddasdotac, $i, $this->scodpcmater);
        $selemento     = pg_result($this->recorddasdotac, $i, $this->selemento);
        $sdelemento    = pg_result($this->recorddasdotac, $i, $this->sdelemento);
        $dquant        = pg_result($this->recorddasdotac, $i, $this->dquant);
        $dvalor        = pg_result($this->recorddasdotac, $i, $this->dvalor);
        $dvaltot       = pg_result($this->recorddasdotac, $i, $this->dvalortot);

        if((isset ($descricaoitem) && (trim($descricaoitem) == "" || $descricaoitem == null)) || !isset ($descricaoitem)){
            $descricaoitem = $resum;
            unset ($resum);
        }

        if(isset ($scodpcmater) && trim($scodpcmater) != ""){
            $scodpcmater = trim($scodpcmater)." - ";
        }

        if(isset ($prazo) && trim($prazo) != ""){
            $prazo = "PRAZO: ".trim($prazo);
        }

        if(isset ($pgto) && trim($pgto) != ""){
            $pgto = "CONDI��O: ".trim($pgto);
        }

        if(isset ($resum) && trim($resum) != ""){
            $resum = "RESUMO: ".trim($resum);
        }

        if(isset ($just) && trim($just) != ""){
            $just = "JUSTIFICATIVA: ".trim($just);
        }

        if((isset ($servico) && (trim($servico) == "f" || trim($servico) == "")) || !isset ($servico)){
            $unid = trim(substr($unid, 0, 10));
            if($susaquant == "t"){
                $unid .= " \n$quantunid UNIDADES\n";
            }
        } else {
            $unid = "SERVI�O";
        }

        $distanciar = 0;
        if((isset ($prazo) && trim($prazo) == "")&&(isset ($pgto) && trim($pgto) == "")&&
           (isset ($resum) && trim($resum) == "")&&(isset ($just) && trim($just) == "")){
            $distanciar = 4;
        }

        if(trim($dvalor) != ""){
            $valimp    = $dvalor;
            $valoritem = db_formatar($dvaltot,"f"," ",0,"d",$this->casadec);
        }

        if(trim($dquant) != ""){
            $quantitem = $dquant;
        }

        $ddvalor = "";
        if(isset ($dotacao) && trim($dotacao) != ""){
            $result_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null, null, "o82_codres as codigodareserva,o80_valor as valorreserva", "", "o82_solicitem=$codigo and o80_coddot=$dotacao"));
            $ddvalor = "N�O";
            if($clorcreservasol->numrows > 0){
                db_fieldsmemory($result_orcreservasol, 0);
                global $valorreserva;
                if($valorreserva == $valimp){
                    $ddvalor = "TOTAL";
                } else {
                    $ddvalor = "PARCIAL - R$ ".db_formatar($valorreserva, "f");
                }
            }
        }

        $xtotal   += $valimp;
        $valimp    = db_formatar($valimp, "f");
        
        $this->objpdf->Setfont('Arial', 'B', 7);

        if(isset ($selemento) && trim($selemento) != "") {
            $this->objpdf->ln(1.5);
            $mais   = $this->objpdf->NbLines(95,db_formatar($selemento, 'elemento')." - ".$sdelemento);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Row(array ('', '', '', db_formatar($selemento, 'elemento')." - ".$sdelemento, '', ''), 3, false, 3);
        }

        $mais = $this->objpdf->NbLines(95,$scodpcmater.$descricaoitem);
        $mostra = $xlin;
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
        
        $this->objpdf->Row(array ($item, $quantitem, $unid, $scodpcmater.$descricaoitem, $valoritem, $valimp), 3, false, $distanciar, 0, 0, true);
        
        $dist = 3;

        $mostra = $xlin;
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,0);
				
        $this->objpdf->ln(3);
        
        if(isset ($ddvalor) && $ddvalor != ""){
            if((isset ($prazo) && $prazo != "")||(isset ($pgto) && $pgto != "")||
               (isset ($resum) && $resum != "" && (isset ($scodpcmater) && trim($scodpcmater) != ""))||
               (isset ($just) && $just != "")){
                $this->objpdf->ln(2.5);
            }
            $mais = $this->objpdf->NbLines(95,"RESERVA: ".$ddvalor);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Row(array ('', '', '', "RESERVA: ".$ddvalor, '', ''), 3, false, $dist, 0, 0, true);
        }

        $mostraunid = false;

        $this->objpdf->Setfont('Arial', '', 7);
        if(isset ($prazo) && $prazo != ""){
            $mais   = $this->objpdf->NbLines(95,$prazo);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Row(array ('', '', '', $prazo, '', ''), 3, false, $dist, 0, 0, true);
        }

        if(isset ($pgto) && $pgto != ""){
            $mais = $this->objpdf->NbLines(95,$pgto);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Row(array ('', '', '', $pgto, '', ''), 3, false, $dist, 0, 0, true);
        }

        if(isset ($resum) && $resum != "" && (isset ($scodpcmater) && trim($scodpcmater) != "")){
            $mais = $this->objpdf->NbLines(95,$resum);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            //$this->objpdf->ln(14);
            $this->objpdf->Row(array ('', '', '', $resum, '', ''), 3, false, $dist, 0, 0, true);
        }
 
        if(isset ($just) && $just != ""){
            $mais = $this->objpdf->NbLines(95,$just);
            $mostra = $xlin;
            $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
            $this->objpdf->Row(array ('', '', '', $just, '', ''), 3, false, $dist, 0, 0, true);
        }
        $this->objpdf->ln(1.5);
  }

  $this->objpdf->Setfont('Arial', 'B', 8);
  $maislin = 248;
  if ($contapagina == 1) {
    $maislin = 211;
  }

  if ($contapagina == 1) {

       $sqlparag  = "select db02_texto ";
       $sqlparag .= "  from db_documento ";
       $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
       $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
       $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
       $sqlparag .= " where db03_tipodoc = 1400 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
          
       $resparag = @db_query($sqlparag);
          
       if (@pg_numrows($resparag) > 0) {
            db_fieldsmemory($resparag,0);
            
            eval($db02_texto);
       } else {
            $sqlparagpadrao  = "select db61_texto ";
            $sqlparagpadrao .= "  from db_documentopadrao ";
            $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
            $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
            $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
            $sqlparagpadrao .= " where db60_tipodoc = 1400 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";
            
            $resparagpadrao = @db_query($sqlparagpadrao);
            if (@pg_numrows($resparagpadrao) > 0) {
              db_fieldsmemory($resparagpadrao,0);
              
              eval($db61_texto);
            }
       }
  } else {
       //$xlin -= 10;
	     $this->objpdf->rect($xcol, $xlin +262, 142, 10, 2, 'DF', '34');
       $this->objpdf->rect($xcol +142, $xlin +262, 30, 10, 2, 'DF', '34');
     	 $this->objpdf->rect($xcol +172, $xlin +262, 30, 10, 2, 'DF', '34');
       $this->objpdf->text($xcol +120, $xlin +268, 'T O T A L');
	     $this->objpdf->text($xcol +180, $xlin +268, db_formatar($xtotal, "f"));
  }

?>
