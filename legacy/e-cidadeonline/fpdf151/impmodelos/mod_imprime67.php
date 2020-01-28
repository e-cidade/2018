<?

  $this->objpdf->AliasNbPages();
  $this->objpdf->setAutoPageBreak(1,1);
  $this->objpdf->lMargin = 1;
  $this->objpdf->tMargin = 3;
  $this->objpdf->rMargin = 1;

  if (($this->qtdcarne % 3 ) == 0 ){
    $this->objpdf->AddPage();
  }

	if($this->atualizaquant == true){
	  $this->qtdcarne += 1;
	}

  $nFonte      = 6;
  $iAlt        = 3;
  $iAltLabel   = 3;
  $nFonteLabel = 4;

  $iXPosPagina = 35.5;
  $iYPosPagina = $this->objpdf->getY();

  $iXInicio = 33.5;
  $iYInicio = $this->objpdf->getY();

  $this->especie_doc     = "RC";
  $this->aceite          = "N";
  $this->localpagamento  = " QUALQUER BANCO ATÉ O VENCIMENTO ";

  $this->totalrec   = 0;
  $this->totaldesc  = 0;
  $this->totalacres = 0;

  $aDadosContrib    = explode("-",$this->descr11_1);
  $iCgmContribuinte = $aDadosContrib[0];

  $iNroRec = count($this->arraycodreceitas);

  for ( $iInd=0; $iInd < $iNroRec; $iInd++ ) {

     if ($this->arraycodtipo[$iInd] == 't' && $this->arrayvalreceitas[$iInd] < 0 ){
        $this->totaldesc  += $this->arrayvalreceitas[$iInd];
     } else if (@$this->arraycodtipo[$iInd] == 't' and $this->arrayvalreceitas[$iInd] > 0){
        $this->totalacres += $this->arrayvalreceitas[$iInd];
     } else {
        $this->totalrec   += $this->arrayvalreceitas[$iInd];
     }

  }

  $this->desconto_abatimento = db_formatar(abs($this->totaldesc),'f');
  $this->mora_multa          = db_formatar(($this->totalacres),'f');
  $this->valor_cobrado       = $this->valtotal;
  $this->valtotal            = db_formatar(($this->totalrec),'f');

  $this->objpdf->SetFont('Arial','b',$nFonte+3);
  $this->objpdf->Image($this->imagemlogo,$this->objpdf->getX(),$this->objpdf->getY()+2,19,5);
  $this->objpdf->cell(20 ,$iAlt+5,''                     ,'RB' ,0,'C',0);
  $this->objpdf->cell(10 ,$iAlt+5,$this->numbanco        ,'LRB',1,'C',0);

  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(10,$iAltLabel,'Parcela'                  ,'TLR',0,"L");
  $this->objpdf->cell(20,$iAltLabel,'Vencimento'               ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(10,$iAlt,$this->descr10                  ,'BLR',0,"L");
  $this->objpdf->cell(20,$iAlt,$this->dtparapag                ,'BLR',1,"L");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'Agência/Código Cedente'   ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30 ,$iAlt,$this->agencia_cedente         ,'BLR',1,'L');
  $this->objpdf->cell(10,$iAltLabel,'Espécie'                  ,'TLR',0,"L");
  $this->objpdf->cell(20,$iAltLabel,'Carteira'                 ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(10,$iAlt,$this->especie                  ,'BLR',0,"L");
  $this->objpdf->cell(20,$iAlt,$this->carteira                 ,'BLR',1,"L");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(=) Valor Documento'      ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30 ,$iAlt,$this->valor_cobrado           ,'BLR',1,'R');
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(-) Desconto / Abatimento','TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,''                              ,'BLR',1,"R");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(-) Outras Deduções'      ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,''                              ,'BLR',1,"R");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(+) Mora / Multa'         ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,''                              ,'BLR',1,"R");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(+) Outros Acréscimos'    ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,@$this->outros_acrecimos        ,'BLR',1,"R");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'(=) Valor Cobrado'        ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,''                              ,'BLR',1,"R");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'Nosso Número'             ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,$this->nosso_numero             ,'BLR',1,"L");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'Nº do Documento'          ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,$this->descr9                   ,'BLR',1,"L");
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(30,$iAltLabel,'Contribuinte'             ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,$iCgmContribuinte               ,'BLR',1,"L");
  $this->objpdf->cell(30,$iAltLabel,''                         ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,'Recibo do Sacado'              ,'BLR',1,"R");

  $this->objpdf->SetDash(1,1);
  $this->objpdf->Line($iXInicio,$iYInicio,$iXInicio,$this->objpdf->getY()+20);
  $this->objpdf->SetDash();

  $this->objpdf->setXY($iXPosPagina,$iYPosPagina+1);
  $this->objpdf->Image($this->imagemlogo,$this->objpdf->getX(),$this->objpdf->getY(),27,6);
  $this->objpdf->cell(35 ,$iAlt+5,''                     ,'RB' ,0,'C',0);
  $this->objpdf->SetFont('Arial','b',$nFonte+6);
  $this->objpdf->cell(15 ,$iAlt+5,$this->numbanco        ,'LRB',0,'C',0);
  $this->objpdf->SetFont('Arial','b',$nFonte+5);
  $this->objpdf->cell(122,$iAlt+5,$this->linha_digitavel ,'LB' ,1,'C',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(122,$iAltLabel,'Local de Pagamento' ,'TLR',0,'L',0);
  $this->objpdf->cell(20 ,$iAltLabel,'Parcela'            ,'TR' ,0,'L',0);
  $this->objpdf->cell(30 ,$iAltLabel,'Vencimento'         ,'TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonte);
  $this->objpdf->cell(122,$iAlt,$this->localpagamento,'BRL',0,'L',0);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(20 ,$iAlt,$this->descr10       ,'BR' ,0,'L',0);
  $this->objpdf->cell(30 ,$iAlt,$this->dtparapag     ,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(122,$iAltLabel,'Cedente'               ,'TLR',0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'Agência/Código Cedente','TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(122,$iAlt,$this->prefeitura     ,'BRL',0,'L',0);
  $this->objpdf->cell(50 ,$iAlt,$this->agencia_cedente,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(22 ,$iAltLabel,'Data do Documento'    ,'TLR',0,'L',0);
  $this->objpdf->cell(35 ,$iAltLabel,'Número do Documento'  ,'TR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAltLabel,'Espécie Doc.'         ,'TR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAltLabel,'Aceite'               ,'TR' ,0,'L',0);
  $this->objpdf->cell(26 ,$iAltLabel,'Data do Processamento','TR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'Nosso Número'         ,'TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(22 ,$iAlt,$this->data_processamento,'BLR',0,'L',0);
  $this->objpdf->cell(35 ,$iAlt,$this->descr9            ,'BR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAlt,$this->especie_doc       ,'BR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAlt,$this->aceite            ,'BR' ,0,'L',0);
  $this->objpdf->cell(26 ,$iAlt,date('d/m/Y')            ,'BR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAlt,$this->nosso_numero      ,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(26 ,$iAltLabel,'Uso do Banco'          ,'TLR',0,'L',0);
  $this->objpdf->cell(25 ,$iAltLabel,'Carteira'              ,'TR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAltLabel,'Espécie'               ,'TR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAltLabel,'Quantidade'            ,'TR' ,0,'L',0);
  $this->objpdf->cell(32 ,$iAltLabel,'Valor'                 ,'TR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'(=) Valor do Documento','TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(26 ,$iAlt,''                  ,'BLR',0,'L',0);
  $this->objpdf->cell(25 ,$iAlt,$this->carteira     ,'BR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAlt,$this->especie      ,'BR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAlt,@$this->quantidade  ,'BR' ,0,'L',0);
  $this->objpdf->cell(32 ,$iAlt,@$this->valorhis    ,'BR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAlt,$this->valor_cobrado,'BR' ,1,'L',0);


  $this->objpdf->SetFont('Arial','',$nFonte);

  $sInstrucao = " Tipo/Exercício: ".@$this->tipo_exerc." ".$this->descr12_1." \n";

  if( @$this->valororigem != "" ){
    $sInstrucao .= " Valor origem= ".trim($this->valororigem);
  }
  if( @$this->valtotal != "" ){
    $sInstrucao .= " Valor corrigido = ".trim($this->valtotal);
  }
  if( @$this->desconto_abatimento != "" ){
    $sInstrucao .= " Desconto/Abatimento = ".trim($this->desconto_abatimento);
  }
  if( @$this->mora_multa != "" ){
    $sInstrucao .= " Mora/Multa = ".trim($this->mora_multa);
  }
  if( @$this->valor_cobrado != "" ){
    $sInstrucao .= " Valor do documento = ".trim($this->valor_cobrado);
  }
  if( @$this->predescr12_1 != "" ){
    $sInstrucao .= "\n".$this->predescr12_1;
  }

  $iYPos = $this->objpdf->getY();
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(122,$iAltLabel,'Instruções'   ,'TLR',1,'L',0);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->multicell(122,3,substr($sInstrucao,0,700));
  $iXPos = 122+$iXPosPagina;
  $this->objpdf->setXY($iXPos,$iYPos);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(50,$iAltLabel,'(-) Desconto / Abatimento','TLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt,''                              ,'BLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(50,$iAltLabel,'(-) Outras Deduções'      ,'TLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt,''                              ,'BLR',1,"R");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(50,$iAltLabel,'(+) Mora Multa'           ,'TLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt,''                              ,'BLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(50,$iAltLabel,'(+) Outros Acréscimos'    ,'TLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt,@$this->outros_acrecimos        ,'BLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(50,$iAltLabel,'(=) Valor Cobrado'        ,'TLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt,''                              ,'BLR',1,"L");
  $this->objpdf->setX($iXPos);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(50,$iAlt*2,''                            ,1    ,1,"L");

  $this->objpdf->Line($iXPosPagina,$iYPos,$iXPosPagina,$this->objpdf->getY());

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->cell(15 ,$iAlt-2,''                           ,'TL'  ,0,"L");
  $this->objpdf->cell(107,$iAlt-2,''                           ,'TR'  ,0,"L");
  $this->objpdf->cell(50 ,$iAlt-2,''                           ,'TR'  ,1,"L");
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(15,$iAlt,'Sacado'                     ,'L' ,0,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(72,$iAlt,substr($this->descr11_1,0,52),'0'  ,0,"L");
  $this->objpdf->cell(35,$iAlt, "CPF/CNPJ: ".db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')),'R',0,'L');
  $this->objpdf->cell(50,$iAlt,''                           ,'LR',1,"L");
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->cell(15,$iAlt,''                           ,'L'  ,0,"L");
  $this->objpdf->cell(107,$iAlt,$this->descr11_2            ,'R'  ,0,"L");
  $this->objpdf->cell(50,$iAlt,''                           ,'LR' ,1,"L");
  $this->objpdf->setX($iXPosPagina);

  if (!isset($this->ufcgm)) {
    $this->ufcgm = $this->uf_config;
  }

  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(15 ,$iAlt,'Sacador/Avalista'                                  ,'L' ,0,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(107,$iAlt,$this->munic." / ".$this->ufcgm." / CEP-".$this->cep,'R' ,0,"L");
  $this->objpdf->cell(50 ,$iAlt,''                                                  ,'LR',1,"L");
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->cell(15 ,$iAlt-2,''                           ,'BL'  ,0,"L");
  $this->objpdf->cell(107,$iAlt-2,''                           ,'BR'  ,0,"L");
  $this->objpdf->cell(50 ,$iAlt-2,''                           ,'BLR' ,1,"L");


  if ($this->codigo_barras != null) {
    $this->objpdf->int25($iXPosPagina,$this->objpdf->getY()+0.5,$this->codigo_barras,10,0.3);
  }

  $this->objpdf->setY($this->objpdf->getY()+20);
?>