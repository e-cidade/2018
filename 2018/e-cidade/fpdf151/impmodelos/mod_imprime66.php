<?php
/* Obtem os dados de custas do recibo */
$oPartilhaCustasRepository = \ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas::getInstance();
$this->aCustas = $oPartilhaCustasRepository->getDadosRecibo($this->numnov_recibo);

$this->objpdf->AliasNbPages();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->line(2,148.5,208,148.5);

$xlin = 20;
$xcol = 4;

$this->objpdf->setfillcolor(245);
$this->objpdf->roundedrect($xcol-2, $xlin-18,206,185.5,2,'DF','1234');
$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','B',11);
$this->objpdf->text(160, $xlin-13,'RECIBO DO SACADO ');

if (substr($this->dtparapag,4,1)=='-' || substr($this->dtparapag,7,1)=='/') {
	$this->dtparapag =  db_formatar($this->dtparapag,'d');	
}
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(142, $xlin-8,"DOCUMENTO VÁLIDO ATÉ: ".$this->dtparapag);

$str_via = 'Contribuinte';
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->Image('imagens/files/'.$this->logo,15,@$xlin-17,12);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(40, $xlin-15, $this->prefeitura);
$this->objpdf->Setfont('Arial','',9);

$this->objpdf->text(40, $xlin-11,$this->enderpref);
$this->objpdf->text(40, $xlin-8, $this->municpref);
$this->objpdf->text(40, $xlin-5, $this->telefpref);
$this->objpdf->text(40, $xlin-2, $this->emailpref);

$this->objpdf->Roundedrect(@$xcol,@$xlin+2,@$xcol+119,20,2,'DF','1234');

$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text(@$xcol+2,@$xlin+4,'Identificação:');
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+2,  $xlin+7,  'Nome : ');
$this->objpdf->text($xcol+17, $xlin+7,  $this->descr11_1); 
$this->objpdf->text($xcol+2,  $xlin+11, 'Endereço : ');
$this->objpdf->text($xcol+17, $xlin+11, $this->descr11_2); 
$this->objpdf->text($xcol+2,  $xlin+15, 'Bairro : ');
$this->objpdf->text($xcol+17, $xlin+15, $this->bairrocontri);
$this->objpdf->text($xcol+2,  $xlin+19, 'Município : ');
$this->objpdf->text($xcol+17, $xlin+19, $this->munic);
$this->objpdf->text($xcol+75, $xlin+15, 'CEP : ');
$this->objpdf->text($xcol+83, $xlin+15, $this->cep);
$this->objpdf->text($xcol+75, $xlin+19, 'CNPJ/CPF:');
$this->objpdf->text($xcol+90, $xlin+19, db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')));
$this->objpdf->Setfont('Arial','',6);

$this->objpdf->Roundedrect(@$xcol+126,@$xlin+2,76,20,2,'DF','1234');
$this->objpdf->text($xcol+128,  $xlin, 'Data :'. date("d-m-Y",db_getsession("DB_datausu")). ' - Hora : '.date("H:i:s"));
$this->objpdf->text($xcol+128,$xlin+4, $this->identifica_dados);
$this->objpdf->text($xcol+128,$xlin+7, $this->tipoinscr);

if(isset($this->refant) && $this->refant!="") {
  $this->objpdf->text($xcol+140,$xlin+7, $this->nrinscr." Ref. Ant.: ".$this->refant);
  $this->objpdf->text($xcol+140,$xlin+10, "PQL: ".$this->pql_localizacao);
} else {
  $this->objpdf->text($xcol+140,$xlin+7, $this->nrinscr );
}
$this->objpdf->text($xcol+128,$xlin+13,"Rua : ");
$this->objpdf->text($xcol+140,$xlin+13,$this->nomepriimo);
$this->objpdf->text($xcol+128,$xlin+17,$this->tipocompl);
$this->objpdf->text($xcol+140,$xlin+17,$this->nrpri.(isset($this->complpri)&&$this->complpri!=""?" / ".$this->complpri:"") );
$this->objpdf->text($xcol+128,$xlin+21,"Bairro : ");
$this->objpdf->text($xcol+140,$xlin+21,$this->bairropri);


// PRIMEIRO QUADRO GERAL
$this->objpdf->Roundedrect($xcol,$xlin+24,202,60,2,'DF','1234'); // quadro das receitas
$this->objpdf->sety($xlin+26);

$iVertical   = $this->objpdf->gety();

if ( $this->k03_tipo != 13 ) {

  $this->objpdf->cell(10, 3, "Exercício"           , 0, 0, "L", 0);
  $this->objpdf->cell(20, 3, "Valor Original (R$)" , 0, 0, "R", 0);
  $this->objpdf->cell(20, 3, "Valor Corrigido (R$)" , 0, 0, "R", 0);
  $this->objpdf->cell(20, 3, "Juros (R$)"    , 0, 0, "R", 0);
  $this->objpdf->cell(20, 3, "Multa (R$)"    , 0, 0, "R", 0);
  $this->objpdf->cell(20, 3, "Total (R$)"          , 0, 1, "R", 0);

}

$nTotalHistorico = 0;
$nTotalCorrigido = 0;
$nTotalJuro      = 0;
$nTotalMulta     = 0;
$nTotalDesconto  = 0;
$nTotal          = 0;


$this->objpdf->Setfont('Arial','',5);
foreach ($this->aExercValor as $aExercValor ) {
// Comentadas modificações em conversa com dal pozzo e evandro, este que se equivocou modificar lógica 
 // if ( $this->k03_tipo != 13 ) {

    $this->objpdf->cell(10, 3, $aExercValor->exerc , 0, 0, "L", 0);
    $this->objpdf->cell(20, 3, db_formatar($aExercValor->historico,"f") , 0, 0, "R", 0);
    $this->objpdf->cell(20, 3, db_formatar($aExercValor->corrigido,"f") , 0, 0, "R", 0);
    $this->objpdf->cell(20, 3, db_formatar($aExercValor->juro,"f") , 0, 0, "R", 0);
    $this->objpdf->cell(20, 3, db_formatar($aExercValor->multa,"f") , 0, 0, "R", 0);
    $this->objpdf->cell(20, 3, db_formatar(($aExercValor->total),"f") , 0, 1, "R", 0);

  //}

  $nTotalHistorico += $aExercValor->historico;  
  $nTotalCorrigido += $aExercValor->corrigido;
  $nTotalJuro      += $aExercValor->juro;
  $nTotalMulta     += $aExercValor->multa;
  $nTotalDesconto  += $aExercValor->desconto;
  $nTotal          += $aExercValor->total;

}

//if ( $this->k03_tipo != 13 ) {

  $this->objpdf->cell(10, 3, "Totais"                           , "T", 0, "L", 0);
  $this->objpdf->cell(20, 3, db_formatar($nTotalHistorico, "f") , "T", 0, "R", 0);
  $this->objpdf->cell(20, 3, db_formatar($nTotalCorrigido, "f") , "T", 0, "R", 0);
  $this->objpdf->cell(20, 3, db_formatar($nTotalJuro, "f")      , "T", 0, "R", 0);
  $this->objpdf->cell(20, 3, db_formatar($nTotalMulta, "f")     , "T", 0, "R", 0);
  $this->objpdf->cell(20, 3, db_formatar($nTotal, "f")          , "T", 1, "R", 0);

// } else {
//   $this->objpdf->cell(10, 3, ""                                                        , "", 0, "L", 0);
//   $this->objpdf->cell(20, 3, "TOTAL DA PARCELA: " . db_formatar($nTotal, "f")          , "", 1, "R", 0);
// }


/**
 * Soma valor total do documento com valor da taixa bancaria: 
 */
$nTotal += $this->nTaxaBancaria; 

$this->juros               = db_formatar($nTotalJuro,"f");
$this->multas              = db_formatar($nTotalMulta,"f");
$this->totalacres          = $nTotalJuro+$nTotalMulta;
$this->mora_multa          = $this->totalacres;
$this->totaldesc           = $nTotalDesconto;
$this->desconto_abatimento = db_formatar(abs($this->totaldesc),"f");
$this->corrigido           = db_formatar($nTotalCorrigido, "f");
if ($this->partilhaTipoLancamento == "") {
  $this->valor_cobrado       = db_formatar(($nTotal+$this->nTotalValorTaxas)-$nTotalDesconto, "f");
} else {
  $this->valor_cobrado       = db_formatar($nTotal-$nTotalDesconto, "f");
}

$this->objpdf->SetY($this->objpdf->getY()+4);

$this->objpdf->multicell(118,3,'HISTÓRICO :   '.$this->descr12_1."\n".@$this->sHistoricoIniciaisParcelamento."\n".$this->sMensagemContribuinte, 0, "J", 0);
$this->objpdf->SetX($xcol+3);

//===================     QUADRO PROCESSO E TAXAS ===============================
if (count($this->aCustas) > 0 ) {
  
  $this->objpdf->Roundedrect( 130, $xlin+26  ,73, 55,0,"DF"); 
  
  $this->objpdf->SetY(46);
  $this->objpdf->SetX(130);
  $this->objpdf->SetFont('Arial','B',6);
  $this->objpdf->cell(73, 3,  "Processo Nº: {$this->sCodforo}",1,1,"L");

  /*
   * Caso a variavel $this->partilhaTipoLancamento seja vazia, significa que estão sendo geradas custas automáticas
   * para o recibo que está sendo emitido, neste caso as custas são mostradas. 
   */
  if ( $this->partilhaTipoLancamento == "10") {
  
    $this->objpdf->SetX(130);
    $this->objpdf->cell(73, 3,  $this->sGrupoTaxa, 0,1,"L"); 
    $this->objpdf->SetFont('Arial','',6);
      
    for ($iInd = 0; $iInd < count($this->aTaxas); $iInd++) {
      
      $this->objpdf->SetX(130);
      $this->objpdf->cell(50, 3,  $this->aTaxas[$iInd]["descricao"],0,0,"L");
      $this->objpdf->cell(5, 3,  "R$",0,0,"L");
      $this->objpdf->cell(18, 3,  db_formatar($this->aTaxas[$iInd]["valor"],"f"),0,1,"R");
      
    }
    
    $this->objpdf->SetX(130);
    $this->objpdf->cell(50, 3,  "",0,0,"L");
    $this->objpdf->cell(5, 3,  "R$","T",0,"L");
    $this->objpdf->cell(18, 3,  db_formatar($this->nTotalValorTaxas,"f"),"T",1,"R");
      
  } else {
    
    $this->objpdf->SetX(130);
    $this->objpdf->SetFont('Arial','B',6);
    $this->objpdf->cell(23, 3,  "Situação : ",0,0,"L");
    $this->objpdf->SetFont('Arial','',6);
    $this->objpdf->cell(50, 3,  $this->partilhaTipoLancamento,0,1,"L");
    
    $iAlt = 0;
    if ($this->partilhaDtPaga != ""  && $this->partilhaTipoLancamento == "Custas Pagas") {
      $this->objpdf->SetX(130);
      $this->objpdf->SetFont('Arial','B',6);      
      $this->objpdf->cell(23, 3,  "Data de Pagamento : ",0,0,"L");
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->cell(50, 3,  $this->partilhaDtPaga,0,1,"L");  
      $iAlt = 4;    
    }
    
   // label 
    $this->objpdf->SetY(54+$iAlt);
    $this->objpdf->SetX(130);
    $this->objpdf->SetFont('Arial','B',6);
    $this->objpdf->cell(50, 3,  "Observação : ",0,0,"L");
   // avlor a coluna
    $this->objpdf->SetY(56+$iAlt);
    $this->objpdf->SetX(130);
    $this->objpdf->SetFont('Arial','',6);
    $this->objpdf->MultiCell(73, 3, $this->partilhaObs, 0, "left", false);

    $column = $this->objpdf->GetY(130);
    /*total de custas */
    $iTotalCustas = count($this->aCustas);
    $tipoCustas = array('1' => "Custas a pagar", '2' => "Custas Pagas", '3' => "Custas Isentas");
    $aTiposEscritos = array();
//      if($iTotalCustas < 6)  {
//          $column = $column + 5;
//      } else  {
//          $column = $column + 3;
//      }
    $iEspacoEntreLinhas = ($iTotalCustas < 6 ? 5 : 3);
    foreach ($this->aCustas as $iIndice => $oCustas) {

        if (!in_array($oCustas->v35_tipolancamento, $aTiposEscritos)) {

            $column += ($iIndice == 0 ? 2 : 5);
            $this->objpdf->SetXY(130, $column);
            $this->objpdf->SetFont('Arial','BU',7);
            $this->objpdf->Cell(50, 3, $tipoCustas[$oCustas->v35_tipolancamento] . " (R$)" ,0,0,"L");
            $this->objpdf->SetFont('Arial','',6);
            $aTiposEscritos[] = $oCustas->v35_tipolancamento;
            $column += $iAlt;
        }
        $column += $iEspacoEntreLinhas;


        $this->objpdf->SetY($column);
      $this->objpdf->SetX(130);
      $this->objpdf->SetFont('Arial','',6);  
      $this->objpdf->MultiCell(73, 3, $oCustas->ar36_receita,  0, "left", false);
    
      $this->objpdf->SetY($column);
      $this->objpdf->SetX(137);
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->MultiCell(73, 3, $oCustas->ar36_descricao, 0, "justification", false);
    
      $this->objpdf->SetY($column);
      $this->objpdf->SetX(187);
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->MultiCell(73, 3, db_formatar(abs($oCustas->valor),"f", ' ', 15, 'e', 2),  0, "justification", false);
    }
   
  }  
}
//====================================================================================

$xlin+= 15;

// SEGUNDO QUADRO
$this->objpdf->Roundedrect($xcol,$xlin+72,202,75,2,'DF','1234'); // historico

$this->objpdf->SetY($xlin+75);

$this->objpdf->cell(60, 5, "AGÊNCIA / CÓD do CEDENTE"                                  , "TL", 0, "L", 0);
$this->objpdf->cell(25, 5, "ESPÉCIE"                                                   , "TL", 0, "L", 0);
$this->objpdf->cell(50, 5, ""                                                          , "TL", 0, "L", 0);
$this->objpdf->cell(55, 5, "NOSSO NÚMERO"                                              , "TLR", 1, "L", 0);
$this->objpdf->cell(60, 3, $this->agencia_cedente                                      , "LB", 0, "L", 0); //AGÊNCIA / CÓD do CEDENTE
$this->objpdf->cell(25, 3, $this->especie                                              , "LB", 0, "L", 0); //ESPÉCIE
$this->objpdf->cell(50, 3, ""                                                          , "LB", 0, "L", 0);
$this->objpdf->cell(55, 3, $this->nosso_numero                                         , "LBR", 1, "L", 0); //NOSSO NÚMERO

$this->objpdf->cell(105, 5, "SACADO"                                                   , "L",   0, "L", 0);
$this->objpdf->cell(30,  5, "QUANTIDADE"                                               , "L",   0, "L", 0);
$this->objpdf->cell(55,  5, " (=) VALOR DO DOCUMENTO"                                  , "LR",   1, "L", 0);
$this->objpdf->setfont('arial', '', 8);
$this->objpdf->cell(105, 3, ""                                                         , "L",   0, "L", 0);
$this->objpdf->cell(28,  3, ""                                                         , "L",   0, "L", 0);
$this->objpdf->cell(57,  3, "X"                                                        , "R",   0, "L", 0);
$this->objpdf->cell(55,  3, ""                                                         ,   0,   1, "L", 0);
$this->objpdf->setfont('arial', '', 6);
$this->objpdf->cell(105, 3, $this->predescr3_1                                         , "L",   0, "L", 0); //SACADO
$this->objpdf->cell(30,  3, @$this->quantidade                                         , "L",   0, "L", 0); //Quandidade
$this->objpdf->cell(55,  3, $this->valor_cobrado , "LR",   1, "L", 0); //VALOR DO DOCUMENTO

$this->objpdf->cell(60, 5, "Nº do DOCUMENTO"                                           , "TL", 0, "L", 0);
$this->objpdf->cell(45, 5, " (-) DESCONTO/ABATIMENTO"                                  , "TL", 0, "L", 0);
$this->objpdf->cell(45, 5, " (+) MORA MULTA"                                           , "TL", 0, "L", 0);
$this->objpdf->cell(40, 5, " VALOR COBRADO"                                            , "TLR", 1, "L", 0);
$this->objpdf->cell(60, 5, $this->descr9                                               ,  "L", 0, "L", 0); //Nº do DOCUMENTO
//$this->objpdf->cell(45, 5, db_formatar(abs($this->totaldesc),"f")                      ,  "L", 0, "L", 0); //(-) DESCONTO/ABATIMENTO
$this->objpdf->cell(45, 5, "" ,  "L", 0, "L", 0); //(-) DESCONTO/ABATIMENTO
//$this->objpdf->cell(45, 5, db_formatar($this->totalacres,"f")                          ,  "L", 0, "L", 0); //(+) MORA MULTA
$this->objpdf->cell(45, 5, "" ,  "L", 0, "L", 0); //(+) MORA MULTA
$this->objpdf->cell(40, 5, $this->valor_cobrado  ,  "LR", 1, "L", 0); //VALOR COBRADO

// MENSSAGENS
$this->objpdf->Roundedrect( 10, 139  ,190, 30,0,"DF");
$this->objpdf->cell(190, 4, "Mensagens:",  "L", 1, "L", 0);
$this->objpdf->MultiCell(190 , 4 , $this->msgcontribuinte ,  0, "J",0);

$this->objpdf->SetY(169);
$this->objpdf->cell(110, 4, "",  0, 0, "R", 0);
$this->objpdf->cell(80 , 4, "AUTENTICAÇÃO MECÂNICA",  0, 1, "L", 0);

$xlin+= 35;

$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial','',4);

$sBase  = db_getsession('DB_base');
$sHora  = db_hora();
$sUser  = db_getsession('DB_login');
$sData  = date('d/m/Y',db_getsession('DB_datausu'));
$sTexto = " Usuário: {$sUser}         Base: {$sBase}         Data: {$sData}         Hora: {$sHora}"; 
 
$this->objpdf->TextWithDirection(3.6,$xlin+95,$sTexto,'U');
 
/*********************************************************************************************************************************************************/
// incluir a ficha de compensação
include modification("fpdf151/impmodelos/mod_imprime666.php"); 
 
?>
