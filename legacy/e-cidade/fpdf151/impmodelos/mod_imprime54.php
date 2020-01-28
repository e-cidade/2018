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
//echo "V: $imprimevalidmin";

// Caixa externa
$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); 
$this->objpdf->text(130,$xlin-15,"ORÇAMENTO N".CHR(176));
$this->objpdf->text(185,$xlin-15,db_formatar($this->orccodigo,'s','0',6,'e'));	
$this->objpdf->text(130,$xlin-11,$this->labdados.CHR(176));
$this->objpdf->text(185,$xlin-11,db_formatar($this->Snumero,'s','0',6,'e'));	
$this->objpdf->Setfont('Arial','',7);
$this->objpdf->text(130,$xlin-8,"Departamento");
$this->objpdf->text(130,$xlin-5,"Fone / Ramal");
$this->objpdf->text(130,$xlin-2,"Fax");
$this->objpdf->text(146,$xlin-8,":".$this->coddepto);
$this->objpdf->text(151,$xlin-8,"-".$this->Sdepart);	
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
$this->objpdf->text($xcol+109,$xlin+13,'Prazo entrega produtos');
$this->objpdf->text($xcol+109,$xlin+17,'Validade orçamento');

$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+109,$xlin+22,'Cotaçao Prévia Licitação');
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->text($xcol+  2,$xlin+13,$this->labtitulo);
$this->objpdf->text($xcol+109,$xlin+17,$this->labtipo);
$this->objpdf->text($xcol+  2,$xlin+17,'Data');
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+  2,$xlin+21,"Condições de pagamento:");
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+ 40,$xlin+21,"05 dias contados da data de entrega do material.");

// Imprime dados do orçamento e solicitação
$this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->orccodigo);
$this->objpdf->text($xcol+130,$xlin+ 8,':  '.$this->orcdtlim);
$this->objpdf->text($xcol+171,$xlin+ 8,':  '.$this->orchrlim);
$this->objpdf->text($xcol+ 23,$xlin+ 13,':  '.$this->Snumero);
$this->objpdf->text($xcol+143,$xlin+ 13,':  '.$this->orcprazo);
$this->objpdf->text($xcol+143,$xlin+ 17,':  '.$this->orcvalidade);

$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+143,$xlin+ 22,":  {$this->orccotacao}");
$this->objpdf->Setfont('Arial','',8);

if(isset($this->Sdata) && trim($this->Sdata)!=""){
  $this->Sdata = db_formatar($this->Sdata,'d');
}
if(trim($this->labtipo)!=""){
  $this->objpdf->text($xcol+125,$xlin+17,':  '.$this->Stipcom);
}
$this->objpdf->text($xcol+ 23,$xlin+17,':  '.$this->Sdata);

//$this->objpdf->setxy($xcol+24,$xlin+18);
//$this->objpdf->cell(3,4,':  ',0,0,"L",0);
$this->objpdf->setxy($xcol+24,$xlin+22);

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
//$this->objpdf->multicell(175,4,stripslashes($Sresumo),0,"J");
//$this->objpdf->ln();

$this->objpdf->rect($xcol,$xlin+3,$xcol+198,$this->objpdf->gety()-($xlin-1),2,'DF','1234');
/*
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+  2,$xlin+21,'Observações ');
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+24,$xlin+21,':');
*/
if (trim($this->orcobs) != "" && strlen(trim($this->orcobs)) > 125){
  $obs_oc = substr($this->orcobs,0,125)." ...";
} else {
  $obs_oc = trim($this->orcobs);
}

//$this->objpdf->text($xcol+26,$xlin+25,$obs_oc);

$xlin = ($this->objpdf->getY()-26);

// Moldura de resumo e observacoes
$this->objpdf->rect($xcol,$xlin+32,$xcol+198,18,2,'DF','1234');
$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+2,$xlin+36,"Resumo:");
$this->objpdf->text($xcol+2,$xlin+48,"Observações:");

$this->objpdf->Setfont('Arial','',8);
$this->objpdf->setxy($xcol+23,$xlin+33);
$this->objpdf->multicell(175,4,stripslashes($Sresumo),0,"J");
$this->objpdf->setxy($xcol+23,$xlin+45);
$this->objpdf->multicell(175,4,stripslashes($obs_oc),0,"J");

$xlin = $this->objpdf->getY()-28;

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

$getdoy = $this->objpdf->gety()-14;

$contadepart = 0;
$alturaini   = (200-($xlin));
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
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->SetAligns(array('C','C','L'));
  $this->objpdf->SetWidths(array(30,30,142));
  for($i=0;$i<$this->linhasdosdepart;$i++){
    db_fieldsmemory($this->recorddosdepart,$i);
    $solicita  = trim(pg_result($this->recorddosdepart,$i,$this->Snumdepart));
    $codigodep = trim(pg_result($this->recorddosdepart,$i,$this->Scoddepto));
    $descrdep  = trim(pg_result($this->recorddosdepart,$i,$this->Sdescrdepto));
    $this->objpdf->Row(array($solicita,$codigodep,$descrdep),4,false,4);
  }
}

$getdoy = $this->objpdf->gety()+2-$xlin; 
$oDoc   = new libdocumento(1505);
$oDoc->getparagrafos();

//verificamos se existe o primeiro paragrafo
if (isset($oDoc->aParagrafos[1])){
   eval($oDoc->aParagrafos[1]->db02_texto);
}

/*
$this->objpdf->Setfont('Arial','B',8); 
$this->objpdf->sety($getdoy+$xlin);

$this->objpdf->MultiCell(200,4,"Queiram por obséquio, indicar sempre preço(s), líquido(s) tanto unitário(s) como total(is) nele(s) incluídas todas as parcelas de impostos, fretes e outras despesas se houver.",0,"L");

$getdoy = $this->objpdf->gety()+2-$xlin; 
*/
// Caixa com Rotulos item, quantidade, descricao, valor
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->rect($xcol    ,$xlin+$getdoy,12,6,2,'DF','12');
$this->objpdf->rect($xcol+ 12,$xlin+$getdoy,15,6,2,'DF','12');
$this->objpdf->rect($xcol+ 27,$xlin+$getdoy,88,6,2,'DF','12');
if ($imprimevalidmin=="true"){
   $this->objpdf->rect($xcol+115,$xlin+$getdoy,25,6,2,'DF','12');
  $this->objpdf->rect($xcol+140,$xlin+$getdoy,24,6,2,'DF','12');
}
else{
$this->objpdf->rect($xcol+115,$xlin+$getdoy,49,6,2,'DF','12');
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

$alturaini += 13; // 18 ï¿½ o tamanho da caixa com dados dos produtos.

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

// Caixa dos materiais ou serviï¿½os

$this->objpdf->rect($xcol+ 27,$xlin+$getdoy+6,88,$alturaini,2,'DF','34');

// Caixa das marcas
if ($imprimevalidmin=="true"){
$this->objpdf->rect($xcol+115,$xlin+$getdoy+6,49,$alturaini,2,'DF','34');
}else{
$this->objpdf->rect($xcol+115,$xlin+$getdoy+6,49,$alturaini,2,'DF','34');
}

// Caixa dos validade minima
if ($imprimevalidmin=="true"){
  $this->objpdf->rect($xcol+140,$xlin+$getdoy+6,24,$alturaini,2,'DF','34');
}

//$this->objpdf->rect($xcol+138,$xlin+$getdoy+6,26,$alturaini,2,'DF','34');

// Caixa dos valores unitï¿½rios
$this->objpdf->rect($xcol+164,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');
// Caixa dos valores totais dos itens
$this->objpdf->rect($xcol+183,$xlin+$getdoy+6,19,$alturaini,2,'DF','34');
// Caixa dos validade minima
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
$this->objpdf->sety($xlin+48);

$alt = 4;
// Label das colunas
$this->objpdf->SetWidths(array(12,15,113,24,19,19));
$this->objpdf->SetAligns(array('C','C','J','C','R','R'));

$this->objpdf->text($xcol+   2,$xlin+$getdoy+4,'SEQ');
$this->objpdf->text($xcol+  13,$xlin+$getdoy+4,'QUANT');
$this->objpdf->text($xcol+  56,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
if ($imprimevalidmin=="true"){
  $this->objpdf->text($xcol+ 123,$xlin+$getdoy+4,'MARCA');
}else{
  $this->objpdf->text($xcol+ 135,$xlin+$getdoy+4,'MARCA');
}
if ($imprimevalidmin=="true"){
  $this->objpdf->text($xcol+ 141,$xlin+$getdoy+4,'VALIDAD. MIN.');
}

//$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'MARCA');
//$this->objpdf->text($xcol+ 139,$xlin+$getdoy+4,'VAL. MIN.');
$this->objpdf->text($xcol+ 165,$xlin+$getdoy+4,'VLR UNIT.');
$this->objpdf->text($xcol+ 184,$xlin+$getdoy+4,'VLR TOT.');

$maiscol = 0;

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

//         echo strlen(trim($resumo)); exit;
     }
  
     $unid		= pg_result($this->recorddositens,$ii,$this->sunidade);
     $codunid   = pg_result($this->recorddositens,$ii,$this->scodunid);
     $servico   = pg_result($this->recorddositens,$ii,$this->sservico);
     $quantunid = pg_result($this->recorddositens,$ii,$this->squantunid);	  
     $susaquant = pg_result($this->recorddositens,$ii,$this->susaquant);
     
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
              if (strlen(trim($this->declaracao)) > 0 || isset($oDoc->aParagrafos[2])) {
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
         $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); 
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
      
         $xlin = -24;
         $this->objpdf->Setfont('Arial','B',8);
      
      // Caixas dos label's
         $this->objpdf->rect($xcol    ,$xlin+54,12,6,2,'DF','12');
         $this->objpdf->rect($xcol+ 12,$xlin+54,15,6,2,'DF','12');
         $this->objpdf->rect($xcol+ 27,$xlin+54,113,6,2,'DF','12');
         $this->objpdf->rect($xcol+140,$xlin+54,24,6,2,'DF','12');
//         $this->objpdf->rect($xcol+138,$xlin+54,26,6,2,'DF','12');
         $this->objpdf->rect($xcol+164,$xlin+54,19,6,2,'DF','12');
         $this->objpdf->rect($xcol+183,$xlin+54,19,6,2,'DF','12');
      
         $this->objpdf->rect($xcol,    $xlin+54,12,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+ 12,$xlin+54,15,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+ 27,$xlin+54,113,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+140,$xlin+54,24,($linha-25),2,'DF','34');
//         $this->objpdf->rect($xcol+138,$xlin+54,26,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+164,$xlin+54,19,($linha-25),2,'DF','34');
         $this->objpdf->rect($xcol+183,$xlin+54,19,($linha-25),2,'DF','34');
      
         $this->objpdf->sety($xlin+66);
         $alt = 4;     
      
      // Label das colunas
         $this->objpdf->text($xcol+   2,$xlin+58,'SEQ');
         $this->objpdf->text($xcol+  13,$xlin+58,'QUANT');
         $this->objpdf->text($xcol+  56,$xlin+58,'MATERIAL OU SERVIÇO');
         if ($imprimevalidmin=="true"){
          $this->objpdf->text($xcol+ 122,$xlin+$getdoy+4,'MARCA');
         }else{
         $this->objpdf->text($xcol+ 135,$xlin+$getdoy+4,'MARCA');
         }

         if ($imprimevalidmin=="true"){
           $this->objpdf->text($xcol+ 142,$xlin+$getdoy+4,'VALIDAD. MIN.');
         }

   //    $this->objpdf->text($xcol+ 145,$xlin+58,'MARCA');
  //       $this->objpdf->text($xcol+ 139,$xlin+58,'VAL. MIN.');
         $this->objpdf->text($xcol+ 165,$xlin+58,'VLR UNIT.');
         $this->objpdf->text($xcol+ 184,$xlin+58,'VLR TOT.');
         
         $maiscol = 0;
         $xlin    = 20;

         $muda_pagina = true;    
     }

// Separa cada item com uma linha
     if($ii > 0 && $muda_pagina == false && $volta_impressao == 0){
         $linha = $this->objpdf->gety()+3;
         $this->objpdf->line(4,$linha,206,$linha);
         $this->objpdf->ln();
     }	  

     $this->objpdf->Setfont('Arial','',10);
  
     if($volta_impressao == 0){
         $this->objpdf->Row(array(($ii+1),
                                  pg_result($this->recorddositens,$ii,$this->quantitem),
                                  stripslashes($descricaoitem)."\n$unid\n\n",
                                  '',
                                  '',
                                  ''),5,false,3);
     }else if($volta_impressao == 1){
         $resumo = $texto_impressao;
     }else if($volta_impressao == 2){
         $prazo = $texto_impressao;
     }else if($volta_impressao == 3){
         $pgto = $texto_impressao;
     }

     if((isset($resumo) && $resumo!="" && $volta_impressao == 0) || $volta_impressao == 1){
			// echo "<pre>".$resumo."</pre>";
			// exit;
			//  $resumo = str_replace(".","\n",$resumo);    
         $volta_impressao = 0;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
         if (strlen($resumo) > 1900){  // Tarcisio: Tratamento para resumos maiores que 2000 caracteres 
                                       //           erro encontrado em Eldorado.
           $volta_impressao = 4;
           $texto_impressao = substr($resumo,1895,strlen($resumo));
           $resumo          = substr($resumo,0,1895);
         }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $resumo = $this->objpdf->Row_multicell(array('','',stripslashes($resumo),'',''),
                                                $dist,false,5,0,true,true,3,($this->objpdf->h - 35));
         if($resumo != ""){
             $volta_impressao = 1;
             $texto_impressao = $resumo;
             $ii--;
         }

         if ($volta_impressao == 4){
             $volta_impressao = 1;
             $ii--;
         }
     }

     if((isset($prazo) && $prazo!="" && $volta_impressao == 0) || $volta_impressao == 2){
         $volta_impressao = 0;
         $prazo = $this->objpdf->Row_multicell(array('','',stripslashes($prazo),'',''),
                                               $dist,false,5,0,true,true,3,($this->objpdf->h - 35));
         if($prazo != ""){
             $volta_impressao = 2;
             $texto_impressao = $prazo;
             $ii--;
         }
     }
     
     if((isset($pgto) && $pgto!="" && $volta_impressao == 0) || $volta_impressao == 3){
         $volta_impressao = 0;
         $pgto = $this->objpdf->Row_multicell(array('','',stripslashes($pgto),'',''),
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

if (strlen(trim($this->declaracao)) > 0 || isset($oDoc->aParagrafos[2])) {
     $linha = (270);
} else {
     $linha = (280);
}

$this->objpdf->Setfont('Arial','B',6.5);
$this->objpdf->rect($xcol,    $linha,140,10,2,'DF','34');
$this->objpdf->rect($xcol+140,$linha, 24,10,2,'DF','34');
$this->objpdf->text($xcol+140,($linha+6),'T O T A L   G E R A L');
$this->objpdf->rect($xcol+164,$linha, 19,10,2,'DF','34');
$this->objpdf->rect($xcol+183,$linha, 19,10,2,'DF','34');

if (strlen(trim($this->declaracao)) > 0) {
	$this->objpdf->Setfont('Arial','B',6);
  $this->objpdf->sety($linha+12);
  $this->objpdf->multicell(200,3,trim($this->declaracao),0,"J");
}

//$getdoy = $this->objpdf->gety()+2-$xlin;
//$this->objpdf->Setfont('Arial','B',8);
//$this->objpdf->sety($linha+12);

//$this->objpdf->MultiCell(200,4,"ï¿½ indispensï¿½vel a observaï¿½ï¿½o dos itens abaixo:
//O material serï¿½ entregue em 03 dias, contados da data de recebimento de seu orï¿½amento.
//Condiï¿½ï¿½es de pagamento 05 dias contados da data de entrefa do material.
//Em ____/____/_______",0,"L");

if (isset($oDoc->aParagrafos[2])){
   eval($oDoc->aParagrafos[2]->db02_texto);
}

?>
