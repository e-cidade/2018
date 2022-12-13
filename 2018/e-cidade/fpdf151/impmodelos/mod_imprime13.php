<?
$this->objpdf->SetAutoPageBreak('on',0);
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);

$pagina = 1;
$xlin   = 20;
$xcol   = 4;

$imprimevalidmin="false";
//verifica validade minima
for($j = 0; $j < $this->linhasdositens; $j++){
       $oItens = db_utils::fieldsmemory($this->recorddositens,$j);
       if ($oItens->pc01_validademinima=="t"){
           $imprimevalidmin="true";
                                                    }
}


// Caixa externa
$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','B',9);

$oDaoDbConfig    = db_utils::getDao("db_config");
$sLogo           = $oDaoDbConfig->getParametrosInstituicao()->logo;

$this->objpdf->Image('imagens/files/'.$sLogo,15,$xlin-17,12); //.$this->logo
$this->objpdf->text(130,$xlin-15,"ORÇAMENTO N".CHR(176));
$this->objpdf->text(185,$xlin-15,db_formatar($this->orccodigo,'s','0',6,'e'));
$this->objpdf->text(130,$xlin-11,$this->labdados.CHR(176));
$this->objpdf->text(185,$xlin-11,db_formatar($this->Snumero,'s','0',6,'e'));
$this->objpdf->Setfont('Arial','',7);
$this->objpdf->text(130,$xlin-8,"Departamento");
$this->objpdf->text(130,$xlin-5,"Fone / Ramal");
$this->objpdf->text(130,$xlin-2,"Fax");
$this->objpdf->text(146,$xlin-8,": ".$this->Sdepart);
$this->objpdf->text(146,$xlin-5,": ".$this->fonedepto." / ".$this->ramaldepto);
$this->objpdf->text(146,$xlin-2,": ".$this->faxdepto);
$this->objpdf->text(130,$xlin+1,$this->emaildepto);
$this->objpdf->text(195,$xlin+1,"Página ".$pagina);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(40,$xlin-15,$this->prefeitura);
$this->objpdf->Setfont('Arial','',7);
$this->objpdf->text(40,$xlin-11,$this->enderpref);
$this->objpdf->text(40,$xlin- 8,$this->municpref);
$this->objpdf->text(40,$xlin- 5,$this->telefpref);
$this->objpdf->text(40,$xlin- 2,$this->emailpref);
$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

// Caixa com dados do orçamento e solicitação
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+2,$xlin+5,'Dados do Orçamento/'.$this->labtitulo);
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+  2,$xlin+ 8,'Orçamento');
$this->objpdf->text($xcol+109,$xlin+ 8,'Data Limite');
$this->objpdf->text($xcol+150,$xlin+ 8,'Hora Limite');
$this->objpdf->text($xcol+  2,$xlin+13,$this->labtitulo);
$this->objpdf->text($xcol+109,$xlin+17,$this->labtipo);
$this->objpdf->text($xcol+  2,$xlin+17,'Data');
$this->objpdf->text($xcol+  2,$xlin+21,'Resumo');
$this->objpdf->Setfont('Arial','',8);

// Imprime dados do orçamento e solicitação
$this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->orccodigo);
$this->objpdf->text($xcol+125,$xlin+ 8,':  '.$this->orcdtlim);
$this->objpdf->text($xcol+166,$xlin+ 8,':  '.$this->orchrlim);
$this->objpdf->text($xcol+ 23,$xlin+ 13,':  '.$this->Snumero);
if(isset($this->Sdata) && trim($this->Sdata)!=""){
  $this->Sdata = db_formatar($this->Sdata,'d');
}
if(trim($this->labtipo)!=""){
  $this->objpdf->text($xcol+125,$xlin+17,':  '.$this->Stipcom);
}
$this->objpdf->text($xcol+ 23,$xlin+17,':  '.$this->Sdata);
$this->objpdf->setxy($xcol+22,$xlin+18);
$this->objpdf->cell(3,4,':  ',0,0,"L",0);
$this->objpdf->setxy($xcol+24.5,$xlin+18);

$Sresumo = trim($this->Sresumo);
$vresumo = split("\n",$Sresumo);

if (count($vresumo) > 1){
  $Sresumo   = "";
  $separador = "";
  for ($i = 0; $i < count($vresumo); $i++){
    if (trim($vresumo[$i]) != ""){
      $separador = ". ";
      $Sresumo  .= $vresumo[$i].$separador;
    }
  }
}

if (count($vresumo) == 0){
  $Sresumo = str_replace("\n",". ",$Sresumo);
}

$Sresumo = str_replace("\r","",$Sresumo);

$this->objpdf->multicell(175,4,stripslashes($Sresumo),0,"J");
$this->objpdf->rect($xcol,$xlin+3,$xcol+198,$this->objpdf->gety()-($xlin+3),2,'DF','1234');

$xlin = ($this->objpdf->getY()-30);
// Caixa com dados dos fornecedores


$this->objpdf->rect($xcol,$xlin+32,$xcol+198,16,2,'DF','1234');
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+2,$xlin+34,'Dados do Fornecedor');
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
$this->objpdf->text($xcol+  2,$xlin+46,'Município');
$this->objpdf->text($xcol+115,$xlin+46,'CEP');
$this->objpdf->text($xcol+150,$xlin+42,'Contato');
$this->objpdf->text($xcol+150,$xlin+46,'Fone/Fax');
$this->objpdf->Setfont('Arial','',8);

// Imprime dados dos fornecedores
$this->objpdf->text($xcol+ 18,$xlin+ 38,':  '.$this->nome);
$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
$this->objpdf->text($xcol+163,$xlin+38,':  '.(trim($this->cnpj)!=""?(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')):""));
$this->objpdf->text($xcol+ 18,$xlin+ 42,':  '.$this->ender);
$this->objpdf->text($xcol+122,$xlin+42,':  '.substr($this->compl,0,15));
$this->objpdf->text($xcol+ 18,$xlin+ 46,':  '.(trim($this->cnpj)!=""?($this->munic.'-'.$this->uf):""));
$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
if(trim($this->fax) != ""){
  $this->fax = " / ".$this->fax;
}
$this->objpdf->text($xcol+163,$xlin+42,':  '.substr($this->contato,0,20));
$this->objpdf->text($xcol+163,$xlin+46,':  '.$this->telefone.$this->fax);

// Caixa com dados da entrega
$this->objpdf->rect($xcol,$xlin+50,$xcol+198,16,2,'DF','1234');
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+2,$xlin+52,'Dados dos Produtos');
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+2,$xlin+56,'Prazo de entrega ');
$this->objpdf->text($xcol+2,$xlin+60,'Validade do orçamento ');
$this->objpdf->text($xcol+2,$xlin+64,'Observações ');

$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+42,$xlin+56,": {$this->orcprazo}");
$this->objpdf->text($xcol+42,$xlin+60,": {$this->orcvalidade}");
$this->objpdf->text($xcol+42,$xlin+64,':');
$this->objpdf->text($xcol+46,$xlin+64,$this->orcobs);

$getdoy = 68;

$contadepart = 0;
$alturaini   = (208-($xlin));
if($this->linhasdosdepart>0){
  for($i=0;$i<$this->linhasdosdepart;$i++){
    $contadepart += 4;
  }
  $setaut = $xlin + $getdoy;
  $alturaini -= ($contadepart+15);

  $this->objpdf->Setfont('Arial','B',8);
  // Caixa de texto para labels
  $this->objpdf->rect($xcol    ,$setaut,202,6,2,'DF','12');
  $this->objpdf->text($xcol+  4,$setaut+4,'DEPARTAMENTOS DAS SOLICITAÇÕES');

  $this->objpdf->rect($xcol    ,$setaut+6,30,6,2,'DF','12');
  $this->objpdf->rect($xcol+30 ,$setaut+6,30,6,2,'DF','12');
  $this->objpdf->rect($xcol+60 ,$setaut+6,142,6,2,'DF','12');

  $this->objpdf->rect($xcol    ,$setaut+12,30,$contadepart+1,2,'DF','34');
  $this->objpdf->rect($xcol+30 ,$setaut+12,30,$contadepart+1,2,'DF','34');
  $this->objpdf->rect($xcol+60 ,$setaut+12,142,$contadepart+1,2,'DF','34');
  $this->objpdf->sety($xlin+66);

  // Label das colunas
  $this->objpdf->text($xcol+   6,$setaut+11,'SOLICITAÇÃO');
  $this->objpdf->text($xcol+  39,$setaut+11,'CÓDIGO');
  $this->objpdf->text($xcol+ 125,$setaut+11,'DESCRIÇÃO');

  // Seta altura nova para impressão dos dados
  $this->objpdf->sety($setaut+13);
  $this->objpdf->setx($xcol);
  $this->objpdf->setleftmargin(4);
  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->SetAligns(array('C','C','L'));
  $this->objpdf->SetWidths(array(30,30,142));
  for($i=0;$i<$this->linhasdosdepart;$i++){
    db_fieldsmemory($this->recorddosdepart,$i);
    $solicita  = trim(pg_result($this->recorddosdepart,$i,$this->Snumdepart));
    $codigodep = trim(pg_result($this->recorddosdepart,$i,$this->Scoddepto));
    $descrdep  = trim(pg_result($this->recorddosdepart,$i,$this->Sdescrdepto));
    $this->objpdf->Row(array($solicita,$codigodep,$descrdep),4,false,4);
  }
  $getdoy = $this->objpdf->gety()+2-$xlin;
}

// Caixa com Rotulos item, quantidade, descricao, valor
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->rect($xcol    ,$xlin+$getdoy,12,6,2,'DF','12');
$this->objpdf->rect($xcol+ 12,$xlin+$getdoy,15,6,2,'DF','12');
$this->objpdf->rect($xcol+ 27,$xlin+$getdoy,18,6,2,'DF','12');
$this->objpdf->rect($xcol+ 45,$xlin+$getdoy,70,6,2,'DF','12');
$this->objpdf->rect($xcol+115,$xlin+$getdoy,49,6,2,'DF','12');
if ($imprimevalidmin=="true"){
$this->objpdf->rect($xcol+141,$xlin+$getdoy,23,6,2,'DF','12');
}
$this->objpdf->rect($xcol+164,$xlin+$getdoy,19,6,2,'DF','12');
$this->objpdf->rect($xcol+183,$xlin+$getdoy,19,6,2,'DF','12');

$menos = 16.9;
if($this->linhasdosfornec==0){
  $menos = 11;
}
if(isset($tiramenos)){
  $menos = $menos+$tiramenos;
  if($menos<0){
    $menos = -$menos;
  }
}

$alturaini -= 18; // 18 é o tamanho da caixa com dados dos produtos.

/*if ($this->linhasdositens <= 18){
     if (strlen(trim($this->declaracao)) > 0){
          $alturaini = (137);
     }
}*/

/* 1 pagina */
// Caixa dos itens
$this->objpdf->rect($xcol,    $xlin+$getdoy+6,12,$alturaini,2,'DF','34');

// Caixa da quantidade
$this->objpdf->rect($xcol+ 12,$xlin+$getdoy+6,15,$alturaini,2,'DF','34');

// Caixa da referencia
$this->objpdf->rect($xcol+ 27,$xlin+$getdoy+6,18,$alturaini,2,'DF','34');

// Caixa dos materiais ou serviços
$this->objpdf->rect($xcol+ 45,$xlin+$getdoy+6,70,$alturaini,2,'DF','34');

// Caixa das marcas
//$this->objpdf->rect($xcol+115,$xlin+$getdoy+6,49,$alturaini,2,'DF','34');
if ($imprimevalidmin=="true"){

  $this->objpdf->rect($xcol+115,$xlin+$getdoy+6,49,$alturaini,2,'DF','34');
}
else{
$this->objpdf->rect($xcol+115,$xlin+$getdoy+6,49,$alturaini,2,'DF','34');
}

// Caixa dos validade minima
if ($imprimevalidmin=="true"){
$this->objpdf->rect($xcol+141,$xlin+$getdoy+6,23,$alturaini,2,'DF','34');
}

// Caixa dos valores unitários
$this->objpdf->rect($xcol+164,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');

// Caixa dos valores totais dos itens
$this->objpdf->rect($xcol+183,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');
// Caixa dos validade minima
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$this->objpdf->sety($xlin+48);

$alt = 4;
// Label das colunas
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->text($xcol+   2,$xlin+$getdoy+4,'ITEM');
$this->objpdf->text($xcol+  13,$xlin+$getdoy+4,'QUANT');
$this->objpdf->text($xcol+  28,$xlin+$getdoy+4,'UNIDADE');
$this->objpdf->text($xcol+  56,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
if ($imprimevalidmin=="true"){
$this->objpdf->text($xcol+ 122,$xlin+$getdoy+4,'MARCA');
}else{
$this->objpdf->text($xcol+ 135,$xlin+$getdoy+4,'MARCA');
}

if ($imprimevalidmin=="true"){
$this->objpdf->text($xcol+ 142,$xlin+$getdoy+4,'VALIDAD. MIN.');
}
$this->objpdf->text($xcol+ 167,$xlin+$getdoy+4,'VLR UNIT.');
$this->objpdf->text($xcol+ 186,$xlin+$getdoy+4,'VLR TOT.');

$maiscol = 0;
$this->objpdf->SetWidths(array(12,15,19,69,29,23,7,17));
$this->objpdf->SetAligns(array('C','C','C','J','J','J','R','R'));

$this->objpdf->setleftmargin(4);
$this->objpdf->sety($xlin+$getdoy+7);
$this->objpdf->setfillcolor(235);

$xtotal = 0;
$pag    = 1;
$muda_pagina     = false;
$volta_impressao = 0;

for($ii = 0; $ii < $this->linhasdositens; $ii++){
     db_fieldsmemory($this->recorddositens,$ii);

     $prazo  = "";
     $pgto   = "";
     $resumo = "";

     $descricaoitem = trim(pg_result($this->recorddositens,$ii,$this->descricaoitem));

     if(trim(pg_result($this->recorddositens,$ii,$this->sprazo))!=""){
         $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
         $prazo = "PRAZO: ".trim(stripslashes($prazo));
     }

     if(trim(pg_result($this->recorddositens,$ii,$this->spgto))!=""){
         $pgto = pg_result($this->recorddositens,$ii,$this->spgto);
         $pgto = "CONDIÇÃO: ".trim(stripslashes($pgto));
     }

     if(trim(pg_result($this->recorddositens,$ii,$this->sresum)!="")){
         $resumo = "RESUMO: ".pg_result($this->recorddositens,$ii,$this->sresum);
         if($descricaoitem == "" || $descricaoitem == null){
             $descricaoitem = trim(pg_result($this->recorddositens,$ii,$this->sresum));
             $resumo="";
         }
     }

     $unid			= pg_result($this->recorddositens,$ii,$this->sunidade);
     $codunid   = pg_result($this->recorddositens,$ii,$this->scodunid);
     $servico   = pg_result($this->recorddositens,$ii,$this->sservico);
     $quantunid = pg_result($this->recorddositens,$ii,$this->squantunid);
     $susaquant = pg_result($this->recorddositens,$ii,$this->susaquant);

//     $dvalidademinima = pg_result($this->recorddositens,$ii,$this->Dvalidademinima);


     $dist = 4;
     if(trim($codunid)!=""){
         $unid = trim(substr($unid,0,10));
         if($susaquant=="t"){
             $unid  .= " \n$quantunid UNIDADES\n";
             //$resumo = str_replace("\n","",$resumo);
         }
     }else if($servico=="t"){
         $unid = "SERVIÇO";
     }

// Recria cabecalho do relatorio antes de imprimir restante de itens
     if($this->objpdf->gety() > $this->objpdf->h - 50){
         $this->objpdf->AddPage();
         $this->objpdf->settopmargin(1);

         $pagina++;

         $xlin  = 20;
         $xcol  = 4;
         $linha = 262;

         $pos   = $ii;
         $pos  += 2;

         $indice    = round($this->linhasdositens/$pagina);
         $tot_itens = abs($this->linhasdositens - $indice);

// Testa se esta imprimindo a ultima pagina e tem declaracao definida
         if ($pos >= $tot_itens){
              if (strlen(trim($this->declaracao)) > 0) {
                   $linha = (265);
              } else {
                   $linha = (280);
              }
         }

// Caixa externa
         $this->objpdf->setfillcolor(245);
         $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

         $this->objpdf->setfillcolor(255,255,255);
         $this->objpdf->Setfont('Arial','B',9);
         $this->objpdf->Image('imagens/files/'.$sLogo,15,$xlin-17,12); //.$this->logo
         $this->objpdf->text(130,$xlin-15,"ORÇAMENTO N".CHR(176));
         $this->objpdf->text(185,$xlin-15,db_formatar($this->orccodigo,'s','0',6,'e'));
         $this->objpdf->text(130,$xlin-11,$this->labdados.CHR(176));
         $this->objpdf->text(185,$xlin-11,db_formatar($this->Snumero,'s','0',6,'e'));
         $this->objpdf->Setfont('Arial','',7);
         $this->objpdf->text(130,$xlin-8,"Departamento");
         $this->objpdf->text(130,$xlin-5,"Fone / Ramal");
         $this->objpdf->text(130,$xlin-2,"Fax");
         $this->objpdf->text(146,$xlin-8,": ".$this->Sdepart);
         $this->objpdf->text(146,$xlin-5,": ".$this->fonedepto." / ".$this->ramaldepto);
         $this->objpdf->text(146,$xlin-2,": ".$this->faxdepto);
         $this->objpdf->text(130,$xlin+1,$this->emaildepto);
         $this->objpdf->text(195,$xlin+1,"Página ".$pagina);
         $this->objpdf->Setfont('Arial','B',9);
         $this->objpdf->text(40,$xlin-15,$this->prefeitura);
         $this->objpdf->Setfont('Arial','',7);
         $this->objpdf->text(40,$xlin-11,$this->enderpref);
         $this->objpdf->text(40,$xlin- 8,$this->municpref);
         $this->objpdf->text(40,$xlin- 5,$this->telefpref);
         $this->objpdf->text(40,$xlin- 2,$this->emailpref);
         $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

         $xlin = -30;
         $this->objpdf->Setfont('Arial','B',8);

      // Caixas dos label's
	     $this->objpdf->rect($xcol    ,$xlin+54,12,6,2,'DF','12');
         $this->objpdf->rect($xcol+ 12,$xlin+54,15,6,2,'DF','12');
         $this->objpdf->rect($xcol+ 27,$xlin+54,18,6,2,'DF','12');
         $this->objpdf->rect($xcol+ 45,$xlin+54,70,6,2,'DF','12');
         $this->objpdf->rect($xcol+115,$xlin+54,49,6,2,'DF','12');
          if ($imprimevalidmin=="true"){
            $this->objpdf->rect($xcol+141,$xlin+54,23,6,2,'DF','12');
          }
         $this->objpdf->rect($xcol+164,$xlin+54,19,6,2,'DF','12');
         $this->objpdf->rect($xcol+183,$xlin+54,19,6,2,'DF','12');

         $this->objpdf->rect($xcol,    $xlin+54,12,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+ 12,$xlin+54,15,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+ 27,$xlin+54,18,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+ 45,$xlin+54,70,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+115,$xlin+54,49,($linha-25),2,'DF','34');
                   if ($imprimevalidmin=="true"){
            $this->objpdf->rect($xcol+141,$xlin+54,23,6,2,'DF','12');
          }
         $this->objpdf->rect($xcol+164,$xlin+54,19,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+183,$xlin+54,19,($linha-25),2,'DF','34');

         $this->objpdf->sety($xlin+66);
         $alt = 4;

      // Label das colunas
      $this->objpdf->text($xcol+   2,$xlin+58,'ITEM');
      $this->objpdf->text($xcol+  13,$xlin+58,'QUANT');
      $this->objpdf->text($xcol+  28,$xlin+58,'UNIDADE');
      $this->objpdf->text($xcol+  56,$xlin+58,'MATERIAL OU SERVIÇO');
       if ($imprimevalidmin=="true") {
         $this->objpdf->text($xcol+ 122,$xlin+58,'MARCA');
       } else {
         $this->objpdf->text($xcol+ 135,$xlin+58,'MARCA');
       }
       if ($imprimevalidmin=="true"){
         $this->objpdf->text($xcol+ 142,$xlin+58,'VALIDAD. MIN.');
       }
         $this->objpdf->text($xcol+ 167,$xlin+58,'VLR UNIT.');
         $this->objpdf->text($xcol+ 186,$xlin+58,'VLR TOT.');

         $maiscol = 0;
         $xlin    = 20;

         $muda_pagina = true;
     }

// Separa cada item com uma linha
     if($ii > 0 && $muda_pagina == false && $volta_impressao == 0){
         $linha = $this->objpdf->gety()+3;
         $this->objpdf->rect(4,$linha,202,0,1,'DF','1234');
         $this->objpdf->ln();
     }

     $this->objpdf->Setfont('Arial','',7);

     if($volta_impressao == 0){
         $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
                                  pg_result($this->recorddositens,$ii,$this->quantitem),
                                  $unid,
                                  stripslashes($descricaoitem)."\n\n",
                                  '',
                                  '',
                                  '',
                                  '',
                                  ),5,false,3);
     }else if($volta_impressao == 1){
         $resumo = $texto_impressao;
     }else if($volta_impressao == 2){
         $prazo = $texto_impressao;
     }else if($volta_impressao == 3){
         $pgto = $texto_impressao;
     }

     if((isset($resumo) && $resumo!="" && $volta_impressao == 0) || $volta_impressao == 1){

         $volta_impressao  = 0;

         /**
          *
          *  Verifica os casos em que o resumo não tem quebra e é maior que o tamanho restante da página
          *
          *  - É feito a correção inserindo uma quebra no ponto limite para a impressão do resumo
          *
          */

         // Largura total do multicell
         $iWidthMulticell  = $this->objpdf->widths[4];

         // Consulta o total de linhas restantes
         $iLinhasRestantes = ((( $this->objpdf->h - 25 ) - $this->objpdf->GetY()) / $dist );

         // Consulta o total de linhas que será utilizado no multicelll
         $iLinhasMulticell = $this->objpdf->NbLines($iWidthMulticell,$resumo);

         // Verifica se o total de linhas utilizadas no multicell é maior que as linhas restantes
         if ( $iLinhasMulticell > $iLinhasRestantes ) {

           // Total de carateres necessários para a impressão até o fim da página
           $iTotalCaract = ( $iWidthMulticell * $iLinhasRestantes );
           $iLimitString = $iTotalCaract;

           // Percorre o resumo do limite de caraceters até um ponto que haja espaço em branco para não quebre alguma palavra
           for ($iInd = $iTotalCaract; $iInd < strlen($resumo); $iInd++) {
             if ( $resumo{$iInd} == ' ') {
               $iLimitString = $iInd;
               break;
             }
           }

           // Insere quebra no ponto informado
           $resumo = substr($resumo,0,$iLimitString)."\n".substr($resumo,$iLimitString,strlen($resumo));
         }


         $resumo = $this->objpdf->Row_multicell(array('','','',stripslashes($resumo),'',''),
                                                $dist,false,5,0,true,true,3,($this->objpdf->h - 35));
         if($resumo != ""){
             $volta_impressao = 1;
             $texto_impressao = $resumo;
             $ii--;
         }
     }

     if((isset($prazo) && $prazo!="" && $volta_impressao == 0) || $volta_impressao == 2){
         $volta_impressao = 0;
         $prazo = $this->objpdf->Row_multicell(array('','','',stripslashes($prazo),'',''),
                                               $dist,false,5,0,true,true,3,($this->objpdf->h - 35));
         if($prazo != ""){
             $volta_impressao = 2;
             $texto_impressao = $prazo;
             $ii--;
         }
     }

     if((isset($pgto) && $pgto!="" && $volta_impressao == 0) || $volta_impressao == 3){
         $volta_impressao = 0;
         $pgto = $this->objpdf->Row_multicell(array('','','',stripslashes($pgto),'',''),
                                              $dist,false,5,0,true,true,3,($this->objpdf->h - 35));
         if($pgto != ""){
             $volta_impressao = 3;
             $texto_impressao = $pgto;
             $ii--;
         }
     }

     if ($muda_pagina == true){
         $linha = $xlin+10;
         $this->objpdf->rect(4,$linha,202,0,1,'DF','1234');
         $muda_pagina = false;
     }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// caixas para total e FIM do Relatorio

if (strlen(trim($this->declaracao)) > 0) {
     $linha = (265);
} else {
     $linha = (280);
}

$this->objpdf->Setfont('Arial','B',7);
$this->objpdf->rect($xcol,$linha, 164,10,2,'DF','34');
$this->objpdf->text($xcol+139,($linha+6),'T O T A L   G E R A L');
$this->objpdf->rect($xcol+164,$linha, 19,10,2,'DF','34');
$this->objpdf->rect($xcol+183,$linha, 19,10,2,'DF','34');

if (strlen(trim($this->declaracao)) > 0) {
	$this->objpdf->Setfont('Arial','B',6);
  $this->objpdf->sety($linha+12);
  $this->objpdf->multicell(200,3,trim($this->declaracao),0,"J");
}

?>
