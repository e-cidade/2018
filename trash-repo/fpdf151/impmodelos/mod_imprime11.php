<?php
global $resparag, $resparagpadrao, $db61_texto, $db02_texto;

$contapagina = 1;
$flag_rodape = false;

if (!in_array("cl_orcreservasol",get_declared_classes())) {
  include("classes/db_orcreservasol_classe.php");
}
$clorcreservasol = new cl_orcreservasol;
////////// MODELO 11  -  SOLICITA��O DE COMPRA
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$pagina = 1;
$xlin = 20;
$xcol = 4;

// Imprime caixa externa
$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

// Imprime o cabe�alho com dados sobre a prefeitura
$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(130,$xlin-14,'SOLICITA��O DE COMPRA N'.CHR(176));
$this->objpdf->text(185,$xlin-14,db_formatar($this->Snumero,'s','0',6,'e'));
$this->objpdf->Setfont('Arial','B',7);

if (!empty($this->processo_administrativo)) {
  $this->objpdf->text(130,$xlin-11,'PROCESSO ADMINISTRATIVO: '.substr($this->processo_administrativo,0,40));
}

$this->objpdf->text(130,$xlin-8,'ORG�O');
$this->objpdf->text(142,$xlin-8,': '.substr($this->Sorgao,0,40));
$this->objpdf->text(130,$xlin-4,'UNIDADE');
$this->objpdf->text(142,$xlin-4,': '.substr($this->Sunidade,0,40));
$this->objpdf->text(130,$xlin,'USU�RIO');
$this->objpdf->text(142,$xlin,': '.substr($this->Susuarioger,0,40));
if (isset($this->iPlanoPacto) && $this->iPlanoPacto != "") {
  
  $this->objpdf->text(130,$xlin+2,'PLANO');
  $this->objpdf->text(142,$xlin+2,': '.substr($this->iPlanoPacto."-".$this->SdescrPacto,0,40));
}
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(40,$xlin-15,$this->prefeitura);
$this->objpdf->Setfont('Arial','',9);
$this->objpdf->text(40,$xlin-11,$this->enderpref);
$this->objpdf->text(40,$xlin- 8,$this->municpref);
$this->objpdf->text(40,$xlin- 5,$this->telefpref);
$this->objpdf->text(40,$xlin- 2,$this->emailpref);
$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

$this->objpdf->Setfont('Arial','B',8);
// caixa para frases
$this->objpdf->rect($xcol,$xlin+3,$xcol+198,9,2,'DF','1234');
$this->objpdf->SetXY(4,$xlin+4);
$this->objpdf->MultiCell(202,4,'QUANDO NECESS�RIO FRETE, O MESMO CORRER� POR CONTA DO FORNECEDOR',0,"C",0);
$this->objpdf->SetXY(4,$xlin+8);
$this->objpdf->MultiCell(202,4,'TODO FRETE DEVER� SER PAGO PELA EMPRESA REMETENTE - O MATERIAL DEVER� SER DE PRIMEIRA QUALIDADE',0,"C",0);
$this->objpdf->Setfont('Arial','',8);

// Caixa com dados da solicita��o
$this->objpdf->rect($xcol,$xlin+13,$xcol+198,10,2,'DF','1234');
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+2,$xlin+15,'Dados do Solicita��o');
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+  2,$xlin+18,'Departamento');
$this->objpdf->text($xcol+102,$xlin+18,'Tipo de Compra');
$this->objpdf->text($xcol+  2,$xlin+22,'Data');
if ($this->iTipo == 5) {
  
  $this->objpdf->text($xcol+50,$xlin+22,'Tipo');
  $this->objpdf->text($xcol+145,$xlin+22,'Val. Aprox.');
} else {
  $this->objpdf->text($xcol+109,$xlin+22,'Val. Aprox.');
}

// Imprime dados da solicita��o
$this->objpdf->text($xcol+ 23,$xlin+18,":  {$this->iCodigoDepartamento} - {$this->Sdepart}");
if (isset($this->Sdata) && trim($this->Sdata)!="") {
  $this->Sdata = db_formatar($this->Sdata,'d');
}

if (isset($this->Svalor) && trim($this->Svalor)!="") {
  $this->Svalor = db_formatar($this->Svalor,'f');
}

$this->objpdf->text($xcol+125,$xlin+18,':  '.$this->Stipcom);
$this->objpdf->text($xcol+ 23,$xlin+22,':  '.$this->Sdata);
if ($this->iTipo == 5) {
  
  $this->objpdf->text($xcol+ 57,$xlin+22,':  Registro Pre�o');
  $this->objpdf->text($xcol+ 80,$xlin+22,":  Licita��o: {$this->iNumeroLicitacao} -{$this->sModalidadeLicitacao}");
  $this->Svaloraprox= db_formatar($this->Svaloraprox,'f');
  $this->objpdf->text($xcol+160,$xlin+22,':  R$ '.$this->Svaloraprox);
  
} else {
 
  $this->Svaloraprox = db_formatar($this->Svaloraprox,'f');
  $this->objpdf->text($xcol+125,$xlin+22,':  R$ '.$this->Svaloraprox);
}
$this->objpdf->sety(50);
$this->objpdf->text($xcol+  2,$xlin+27,'Resumo');
$this->objpdf->setxy($xcol+22,$xlin+24);
$this->objpdf->cell(3,4,':  ',0,0,"L",0);
$this->objpdf->setxy($xcol+24.5,$xlin+25);
$posini = $this->objpdf->gety();
$this->objpdf->Setfont('Arial','',6);

/*
 * Utilizamos a l�gica abaixo para deixarmos apenas uma quebra de linha por linha
 * ao inv�s de termos v�rios \n ou \r, teremos apenas um \n
 */
$Sresumo = "";
$aResumo = array_unique(split("[\n\r]",$this->Sresumo));
$Sresumo = implode("\n",$aResumo); 
$this->Sresumo = $Sresumo;
  $iSomaHeight = 0;
/*
 * Testa se o resumo for nulo, se for altera a altura do quadro,
 * e trata a posi��o de altura 
 */
if($this->Sresumo != "") {
  $this->objpdf->multicell(180,2, substr(trim(stripslashes($this->Sresumo).""),0,735) ,0,"j");
  $getdoy = 32;
  
  $sStrLenResumo =  strlen($this->Sresumo);

  
  if ( $sStrLenResumo > 182 ) {
    $iSomaHeight = 3;
  }

}else {
	$this->objpdf->multicell(180,0, "" ,0,"j");
	$getdoy = 35;
}

$setaut = $this->objpdf->gety()+6;
$oldsetaut = $setaut;

$setaut += 8;
$newsetaut = $setaut;
if ($setaut>64) {
  $newsetaut = $setaut - 8;
  $tiramenos = $setaut-64;
  $setaut = $setaut-$posini;
} else if ($setaut==64) {
  $newsetaut = $setaut-8;
  $setaut -= 8;
} else if ($setaut==60) {
  $newsetaut = $setaut-4;
  $setaut -= 4;
} else if ($setaut < 60){
  $newsetaut = $setaut-8;
  $setaut -= 8;
}


//  db_msgbox($setaut.' -- '.$posini);

//$this->objpdf->rect($xcol,$xlin+24,$xcol+198,$newsetaut-$posini,2,'DF','1234');
$this->objpdf->rect($xcol,$xlin+24,$xcol+198,12+$iSomaHeight,2,'DF','1234');


  //$getdoy = 32;
$contafornec = 0;
if ($this->linhasdosfornec>0) {
  $x = $this->muda_pag($pagina,$xlin,$xcol,"true",$contapagina,0);
  for ($i=0; $i<$this->linhasdosfornec; $i++) {
    $contafornec += 8;
    break;
  }
  $onze = 11;
  if ($oldsetaut+8>64) {
    $setaut += 36;
  }
  
  $this->objpdf->Setfont('Arial','B',8);
  // Caixa de texto para labels
  $this->objpdf->rect($xcol    ,$setaut+0.8,202,6,2,'DF','12');
  $this->objpdf->text($xcol+  4,$setaut+4.2,'FORNECEDORES SUGERIDOS ');
  
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
  $this->objpdf->text($xcol+30.5,$setaut+11,'NOME/RAZ�O SOCIAL');
  $this->objpdf->text($xcol+ 103,$setaut+11,'ENDERE�O');
  $this->objpdf->text($xcol+ 155,$setaut+11,'MUNIC�PIO');
  $this->objpdf->text($xcol+184.5,$setaut+11,'TELEFONE');
  
  // Seta altura nova para impress�o dos dados
  $this->objpdf->sety($setaut+13.8);
  $this->objpdf->setx($xcol);
  $this->objpdf->setleftmargin(4);
  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->SetAligns(array('C','L','L','L','C'));
  $this->objpdf->SetWidths(array(15,64,63,40,20));
  for ($i=0; $i<$this->linhasdosfornec; $i++) {
    db_fieldsmemory($this->recorddosfornec,$i);
    $cgmforn   = trim(pg_result($this->recorddosfornec,$i,$this->cgmforn));
    $nomeforn  = trim(pg_result($this->recorddosfornec,$i,$this->nomeforn));
    $enderforn = trim(pg_result($this->recorddosfornec,$i,$this->enderforn));
    $numforn   = trim(pg_result($this->recorddosfornec,$i,$this->numforn));
    $municforn = trim(pg_result($this->recorddosfornec,$i,$this->municforn));
    $foneforn  = trim(pg_result($this->recorddosfornec,$i,$this->foneforn));
    $cgccpf    = trim(pg_result($this->recorddosfornec,$i,$this->cgccpf));
    $this->objpdf->Row(array($cgmforn,$nomeforn."\n"."CNPJ/CPF: ".$cgccpf,$enderforn.", ".$numforn,$municforn,$foneforn),4,false,4);
    break;
  }
  if ($this->linhasdosfornec > 1) {
    $this->objpdf->cell(20, 10, "Obs.: Existem mais ".($this->linhasdosfornec-1)." fornecedor(es) sugerido(s).",0,1,"L", 0);
  }
  $getdoy  = $this->objpdf->gety();
  //    $getdoy += 4.8;
  $getdoy  = $getdoy-$xlin;
  //    $contafornec+= 8;
} else {
  $getdoy += 4.8;
  if (($oldsetaut+8)>64) {
    $getdoy += ($this->objpdf->NbLines(175,trim($this->Sresumo))*4)-12;
  }
}

if (($oldsetaut+16)==64 && $this->linhasdosfornec > 0){
  $getdoy += 1;
}

if ($oldsetaut > 64 && $this->linhasdosfornec == 0){
  $getdoy -= 8;
}

// Caixas dos label's
$this->objpdf->rect($xcol    ,$xlin+$getdoy,10,6,2,'DF','12');
$this->objpdf->rect($xcol+ 10,$xlin+$getdoy,12,6,2,'DF','12');
$this->objpdf->rect($xcol+ 22,$xlin+$getdoy,22,6,2,'DF','12');
$this->objpdf->rect($xcol+ 44,$xlin+$getdoy,98,6,2,'DF','12');
$this->objpdf->rect($xcol+142,$xlin+$getdoy,30,6,2,'DF','12');
$this->objpdf->rect($xcol+172,$xlin+$getdoy,30,6,2,'DF','12');

$menos = 37;

if ($this->linhasdosfornec==0) {
  $menos = 31.3;
}

if (isset($tiramenos)) {
  $menos += $tiramenos;

  if ($menos<0) {
    $menos = -$menos;
  }
  
}

if ($this->linhasdosfornec >= 1) {
  
  $menos = ($setaut)-3.7;// - 43);
  
  if (($oldsetaut+16) >= 64  ){
    $menos += 31;
  }
  
}

if ($this->linhasdosfornec==0 && $oldsetaut > 64) {
  $menos = 23;
}


$this->objpdf->rect($xcol,    $xlin+$getdoy+6,10,224-$menos,2,'DF','34');
// Caixa da quantidade
$this->objpdf->rect($xcol+ 10,$xlin+$getdoy+6,12,224-$menos,2,'DF','34');

$this->objpdf->rect($xcol+ 22,$xlin+$getdoy+6,22,224-$menos,2,'DF','34');
// Caixa dos materiais ou servi�os
$this->objpdf->rect($xcol+ 44,$xlin+$getdoy+6,98,224-$menos,2,'DF','34');
// Caixa dos valores unit�rios
$this->objpdf->rect($xcol+142,$xlin+$getdoy+6,30,224-$menos,2,'DF','');

// Caixa dos valores totais dos itens
$this->objpdf->rect($xcol+172,$xlin+$getdoy+6,30,224-$menos,2,'DF','34');

$this->objpdf->sety($xlin+28);

// Label das colunas
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+   2,$xlin+$getdoy+4,'ITEM');
$this->objpdf->text($xcol+11,$xlin+$getdoy+4,'QUANT');
$this->objpdf->text($xcol+26,$xlin+$getdoy+4,'UNIDADE');
$this->objpdf->text($xcol+  70,$xlin+$getdoy+4,'MATERIAL OU SERVI�O');
$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'VALOR UNIT�RIO');
$this->objpdf->text($xcol+ 176,$xlin+$getdoy+4,'VALOR TOTAL');
$maiscol = 0;

$this->objpdf->setleftmargin(3);
$this->objpdf->sety($xlin+$getdoy+7);

$xtotal = 0;
$xtotalitem = 0;
$muda_pag = false;
$index = 0;

for ($ii = 0; $ii < $this->linhasdositens ; $ii++) {
  $itemant = "";
  $pass = false;
  $this->objpdf->SetWidths(array(10,12,24,95,30,30));
  $this->objpdf->SetAligns(array('C','C','C','J','R','R'));
  $pagina = $this->objpdf->PageNo();
  db_fieldsmemory($this->recorddositens,$ii);

  if ($ii!=0 && $muda_pag==false) {
    $muda_pag = false;
    $this->objpdf->ln(0.3);
    $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
    $this->objpdf->ln(1.3);
  }
  
  if ($this->objpdf->h > $this->objpdf->gety()){
       $lin_mais = $this->objpdf->h - $this->objpdf->gety();
  //     $muda_pag = true;
  } else {
       $lin_mais = 0;
//       $muda_pag = false;
  }

  $codigo         = pg_result($this->recorddositens,$ii,"pc11_codigo");
  $item           = pg_result($this->recorddositens,$ii,$this->item);
  $quantitem      = pg_result($this->recorddositens,$ii,$this->quantitem);
  $descricaoitem  = pg_result($this->recorddositens,$ii,$this->descricaoitem);
  $valoritem      = pg_result($this->recorddositens,$ii,$this->valoritem);
  $valtot         = pg_result($this->recorddositens,$ii,$this->svalortot);
  $valimp         = db_formatar($valtot,'f');
  $prazo          = pg_result($this->recorddositens,$ii,$this->sprazo);
  $pgto           = pg_result($this->recorddositens,$ii,$this->spgto);
  $resum          = pg_result($this->recorddositens,$ii,$this->sresum);
  $resum          = stripslashes(addslashes($resum));
  $just           = pg_result($this->recorddositens,$ii,$this->sjust);
  $unid           = pg_result($this->recorddositens,$ii,$this->sunidade);
  $abrevunid      = pg_result($this->recorddositens,$ii,$this->sabrevunidade);
  $servico        = pg_result($this->recorddositens,$ii,$this->sservico);
  $quantunid      = pg_result($this->recorddositens,$ii,$this->squantunid);
  $susaquant      = pg_result($this->recorddositens,$ii,$this->susaquant);
  $scodpcmater    = pg_result($this->recorddositens,$ii,$this->scodpcmater);
  $selemento      = pg_result($this->recorddositens,$ii,$this->selemento);
  $sdelemento     = pg_result($this->recorddositens,$ii,$this->sdelemento);
  
  
  $xtotal    += number_format($valtot, 2, '.', '');
  $xtotalitem+= number_format($valoritem,2, '.', '');

  if ($item == 8){
//    echo $this->objpdf->h."<br><br>";
//    echo $ii." => ".$this->objpdf->gety()." => ".var_dump($muda_pag)."<br>";
  }
  if ((isset($descricaoitem) && (trim($descricaoitem)=="" || $descricaoitem==null)) || !isset($descricaoitem)) {
    $descricaoitem=stripslashes(addslashes($resum));
    unset($resum);
  }
  
  if (isset($scodpcmater) && trim($scodpcmater)!="") {
    $scodpcmater = trim($scodpcmater)." - ";
  }
  if (isset($prazo) && trim($prazo)!="") {
    $prazo = "PRAZO: ".trim(stripslashes($prazo));
  }
  if (isset($pgto) && trim($pgto)!="") {
    $pgto = "CONDI��O: ".trim(stripslashes($pgto));
  }
  if (isset($resum) && trim($resum)!="") {
    $resum = "RESUMO: ".trim(stripslashes($resum));
    $resum = stripslashes($resum);
  }
  if (isset($just) && trim($just)!="") {
    $just = "JUSTIFICATIVA: ".trim(stripslashes($just));
  }
  
  if ((isset($servico) && (trim($servico)=="f" || trim($servico)=="")) || !isset($servico)) {
    $unid = trim(substr($unid,0,10));
    if ($susaquant=="t") {
      $unid .= " \n$quantunid UNIDADES\n";
    }
  } else {
    $unid = "SERVI�O";
  }
  
  //    $descricaoitem .= " - ".$unid;
  
  $distanciar = 0;
  $barran = "";
  if ((isset($prazo) && trim($prazo)!="") || (isset($pgto) && trim($pgto)!="") || (isset($resum) && trim($resum)!="") || (isset($just) && trim($just)!="")) {
    //      $this->objpdf->sety($this->objpdf->gety+4);
    $barran = "\n";
    $distanciar = 4;
  }
  if ((isset($prazo) && trim($prazo)=="") && (isset($pgto) && trim($pgto)=="") && (isset($resum) && trim($resum)=="") && (isset($just) && trim($just)=="")) {
    $distanciar = 4;
  } else {
  }
  
  $this->objpdf->Setfont('Arial','B',7);
  $mais = $this->objpdf->NbLines(95,db_formatar($selemento,'elemento')." - ".$sdelemento);
  $mostra = $xlin;
  $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);

  if (isset($selemento) && trim($selemento)!="") {
    $this->objpdf->Row(array('','','',db_formatar($selemento,'elemento')." - ".$sdelemento,'',''),3,false,4);
  }
  
  $mais = $this->objpdf->NbLines(95,$scodpcmater.$descricaoitem.$barran);
  $mostra = $xlin;
  $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
  $this->objpdf->Row(array($item, $quantitem, $unid, $scodpcmater.$descricaoitem.$barran, $valoritem, $valimp),3,false,$distanciar,0,true);
  
  $dist = 2.7;
  $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina,0);
  
  //    if (isset($unid) && $unid!="") {
    //      $this->objpdf->Row(array('','',$unid,'',''),3,false,$dist);
    //      $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
    //    }
    
    $this->objpdf->Setfont('Arial','',7);
    
    $mostraunid = false;
    
    if (isset($prazo) && $prazo!="") {
      // $mais = $this->objpdf->NbLines(95,$prazo);
      $mais = 0;
      $mostra = $xlin;
      while ($prazo != "") {
        $alturapaginafunc = $this->objpdf->h  - 58;
        if ($contapagina > 1) {
          $alturapaginafunc = $this->objpdf->h - 30;
        }
        $alturapaginafunc = (int)$alturapaginafunc;
        
        $prazo = $this->objpdf->Row_multicell(array('','','',$prazo,'',''),3,false,$dist,0,true,true,3,$alturapaginafunc);
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
      }
    }
    
    if (isset($pgto) && $pgto!="") {
      // $mais = $this->objpdf->NbLines(95,$pgto);
      $mais = 0;
      $mostra = $xlin;
      while ($pgto != "") {
        $alturapaginafunc = $this->objpdf->h  - 58;
        if ($contapagina > 1) {
          $alturapaginafunc = $this->objpdf->h - 30;
        }
        $alturapaginafunc = (int)$alturapaginafunc;
        
        $pgto = $this->objpdf->Row_multicell(array('','','',$pgto,'',''),3,false,$dist,0,true,true, 3,$alturapaginafunc);
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
      }
    }
    
    if (isset($resum) && $resum!="" && (isset($scodpcmater) && trim($scodpcmater)!="")) {
      // $mais = $this->objpdf->NbLines(95,$resum);
      $mais = 0;
      $mostra = $xlin;
      
      if (!empty($resum)) {
        
        $iTamanhoColuna        = 150;
        $iLimiteCaracterColuna = $iTamanhoColuna;
        $iTamanhoString        = strlen($resum);
        if ($iTamanhoString > $iLimiteCaracterColuna) {
          
          $iNumeroDeQuebra       = floor($iTamanhoString / $iLimiteCaracterColuna);
          $iPosicaoFinal         = 0; 
          for ($i = 0; $i < $iNumeroDeQuebra; $i++) {
            
            
            for ($iPosicaoString = $iLimiteCaracterColuna; $iPosicaoString >= $iPosicaoFinal;  $iPosicaoString--) {
              
              if ($resum[$iPosicaoString] == " ") {

                
                $sPrimeiraParte = substr($resum, 0, $iPosicaoString);
                $sSegundaParte  = substr($resum, $iPosicaoString, $iTamanhoString);
                $resum          = trim($sPrimeiraParte) . "\n" . trim($sSegundaParte);
                break ;
              }
            }
            $iPosicaoFinal          = $iLimiteCaracterColuna;
            $iLimiteCaracterColuna += $iTamanhoColuna;
            if ($iLimiteCaracterColuna > $iTamanhoString) {
              break;
            }
          }
        }
      }
      while ($resum != "") {
        $alturapaginafunc = $this->objpdf->h  - 58;
        if ($contapagina > 1) {
          $alturapaginafunc = $this->objpdf->h - 30;
        }
        $alturapaginafunc = (int)$alturapaginafunc;
        
        $resum = $this->objpdf->Row_multicell(array('','','',stripslashes($resum),'',''),3,false,$dist,0,true,true,3,$alturapaginafunc);
//      $resum = $this->objpdf->Row_multicell(array('','','','','',''),3,false,$dist,0,true,true,3,$alturapaginafunc);
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
      }
    }
    
    if (isset($just) && $just!="") {
      // $mais = $this->objpdf->NbLines(95,$just);
      $mais = 0;
      $mostra = $xlin;
      while ($just != "") {
        $alturapaginafunc = $this->objpdf->h  - 58;
        if ($contapagina > 1) {
          $alturapaginafunc = $this->objpdf->h - 30;
        }
        
        $alturapaginafunc = (int)$alturapaginafunc;
        $just = $this->objpdf->Row_multicell(array('','','',$just,'',''),3,false,$dist,0,true,true,3,$alturapaginafunc);
        $x = $this->muda_pag($pagina,$mostra,$xcol,"false",$contapagina,$mais);
      }
    }
   
    $this->objpdf->SetWidths(array(10,12,24,95,30,30));
    $this->objpdf->SetWidths(array(10,12,24,23.7,9,20.7,20.7,20.7,0.2,30,30));
    $arr_dotac = array();
    for ($i=0; $i<$this->linhasdasdotac; $i++) {
      db_fieldsmemory($this->recorddasdotac,$i);

      $aTestaDotac = array(pg_result($this->recorddasdotac,$i,$this->dcoddot),
                           pg_result($this->recorddasdotac,$i,$this->dcontrap));
      if (pg_result($this->recorddasdotac,$i,$this->dcodigo)==$codigo && !in_array($aTestaDotac,$arr_dotac)) {
        if ($item!=$itemant) {
          $pass = true;
          $this->objpdf->Setfont('Arial','B',7);
          $distc = 3.5;
          $distb = 3;
          $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina,2);
          $this->objpdf->SetAligns(array('C','C','C','C','C','C','C','C','R','R'));
          $this->objpdf->Row(array('','',"\n",'',''),3,false,$dist);
          $this->objpdf->Row(array('','','',"DOTA��O","CP","ANO","ELEMENTO","RESERVADO",'',''),3,false,$dist);
          $itemant = $item;
        }
        
        $this->objpdf->Setfont('Arial','',7);
        $this->objpdf->SetAligns(array('C','C',"C",'C','C','C','C','C','C','R','R'));
        $dquant             = pg_result($this->recorddasdotac,$i,$this->dquant);
        $danousu            = pg_result($this->recorddasdotac,$i,$this->danousu);
        $dcoddot            = pg_result($this->recorddasdotac,$i,$this->dcoddot);
        $dcontrap           = pg_result($this->recorddasdotac,$i,$this->dcontrap);
        $dvalor             = pg_result($this->recorddasdotac,$i,$this->dvalor);
        $delemento          = pg_result($this->recorddasdotac,$i,$this->delemento);
        $projAtividade      = pg_result($this->recorddasdotac,$i,$this->projAtividade);
        $projAtividadeDescr = pg_result($this->recorddasdotac,$i,$this->projAtividadeDescr);
        //        $dreserva = pg_result($this->recorddasdotac,$i,$this->dreserva);
        $arr_dotac[] = array($dcoddot,$dcontrap);
        if (isset($dcoddot) && trim($dcoddot)!="") {
          $result_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres as codigodareserva,o80_valor as valorreserva","","o82_solicitem=$codigo and o80_coddot=$dcoddot"));
          $ddvalor = "N�O";
          if ($clorcreservasol->numrows>0) {
            db_fieldsmemory($result_orcreservasol,0);
            global $valorreserva;
            $valorreserva = db_formatar($valorreserva,"f");
            if ($valorreserva==$valimp) {
              $ddvalor = "TOTAL";
            } else {
              $ddvalor = $valorreserva;
            }
          }
          $mais = $this->objpdf->NbLines(23.7,$delemento);
          $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina,$mais);
         $this->objpdf->Row(array('',$dquant,'',$dcoddot,$dcontrap,$danousu,$delemento,$ddvalor,'',db_formatar($dvalor/$dquant,'f'," ",0,"d",$this->casadec),db_formatar($dvalor,"f")),$distc,false,$distb);
         $this->objpdf->Setfont('Arial','B',7);
         $this->objpdf->Row(array('','','','PROJ/ATIVIDADE'),3,false,$dist);
         $this->objpdf->Setfont('Arial','',7);
         $this->objpdf->SetWidths(array(10,12,24,100));
         $this->objpdf->SetAligns(array('C','C','L',100));
         $this->objpdf->Row(array('','','',$projAtividade. ' - '.$projAtividadeDescr),$distc,false,$distb);
        }
      } else {
        $pass = false;
      }
    }
  }
  $this->objpdf->Setfont('Arial','B',8);
  $maislin = 248;
  if ($contapagina == 1) {
    $maislin = 211;
  }
  
  if ($contapagina == 1) {

   // Quando for uma pagina
             
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
      $flag_rodape = true;
            
    } else {
      
      $sqlparagpadrao  = "select db61_texto ";
      $sqlparagpadrao .= "  from db_documentopadrao ";
      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
      $sqlparagpadrao .= " where db60_tipodoc = 1400 order by db62_ordem";
            
      $resparagpadrao = @db_query($sqlparagpadrao);
            
      if (@pg_numrows($resparagpadrao) > 0) {
         db_fieldsmemory($resparagpadrao,0);
            
         eval($db61_texto);
         $flag_rodape = true;
      }
    }
      
  } else {
    $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
    $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
    $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
    $this->objpdf->text($xcol+120,$xlin+268,'T O T A L');
    $this->objpdf->text($xcol+180,$xlin+268,db_formatar($xtotal,"f"));
  }
?>