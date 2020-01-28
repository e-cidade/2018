<?php

/* Obtem dados da instituicao */
$sSqlDadosInstit  = " select db12_uf,                                   ";
$sSqlDadosInstit .= "        db12_extenso,                              ";
$sSqlDadosInstit .= "        nomeinst,                                  ";
$sSqlDadosInstit .= "        ender,                                     ";
$sSqlDadosInstit .= "        numero,                                    ";
$sSqlDadosInstit .= "        munic,                                     ";
$sSqlDadosInstit .= "        email,                                     ";
$sSqlDadosInstit .= "        telef,                                     ";
$sSqlDadosInstit .= "        cgc,                                       ";
$sSqlDadosInstit .= "        uf,                                        ";
$sSqlDadosInstit .= "        logo,                                      ";
$sSqlDadosInstit .= "        to_char(tx_banc,'99.99') as tx_banc,       ";
$sSqlDadosInstit .= "        numbanco,                                  ";
$sSqlDadosInstit .= "        db21_compl,                                ";
$sSqlDadosInstit .= "        cep                                        ";
$sSqlDadosInstit .= "   from db_config                                  ";
$sSqlDadosInstit .= "  inner join db_uf on db_uf.db12_uf = db_config.uf ";
$sSqlDadosInstit .= "  where codigo = ".db_getsession("DB_instit");

$rsDadosInstit = db_query($sSqlDadosInstit);

if (empty($rsDadosInstit)) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao consultar dados da instituição.  Contate o suporte!');
  break;
}

$oDadosInstit = db_utils::fieldsMemory($rsDadosInstit, 0);

$this->cep       = $oDadosInstit->cep;
$this->cgcpref   = $oDadosInstit->cgc;
$this->telefpref = $oDadosInstit->telef;
$this->emailpref = $oDadosInstit->email;

if (empty($this->prefeitura)) {
  $this->prefeitura = $oDadosInstit->nomeinst;
}

if (empty($this->enderpref)) {
  $this->enderpref = $oDadosInstit->ender;
}

if (empty($this->numeropref)) {
  $this->numeropref = $oDadosInstit->numero;
}

if (empty($this->compl)) {
  $this->compl = $oDadosInstit->db21_compl;
}

if (empty($this->municpref)) {
  $this->municpref = $oDadosInstit->munic;
}

if (empty($this->uf)) {
  $this->uf = $oDadosInstit->uf;
}

if (empty($this->ufInstit)) {
  $this->ufInstit = $oDadosInstit->uf;
}

if (empty($this->logo)) {
  $this->logo = $oDadosInstit->logo;
}

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

  $nFonte                = 6;
  $iAlt                  = 3;
  $iAltLabel             = 3;
  $nFonteLabel           = 4;

  $iXPosPagina           = 35.5;
  $iYPosPagina           = $this->objpdf->getY();

  $iXInicio              = 33.5;
  $iYInicio              = $this->objpdf->getY();

  $this->especie_doc     = "RC";
  $this->aceite          = "N";
  $this->localpagamento  = " QUALQUER BANCO ATÉ O VENCIMENTO ";

  $this->totalrec        = 0;
  $this->totaldesc       = 0;
  $this->totalacres      = 0;

  $aDadosContrib                   = explode("-",$this->descr11_1);
  $iReciboSacadoContribuinteCodigo = $aDadosContrib[0];
  $sReciboSacadoContribuinteTitulo = "Contribuinte";
  $iNroRec                         = count($this->arraycodreceitas);

  /**
   * Adicionamos o codigo da matricula ou da inscriçao no documento, ou mantemos o CGM.
   */
  if (!empty($this->iReciboSacadoContribuinteCodigo) && !empty($this->sReciboSacadoContribuinteTitulo)) {

    $iReciboSacadoContribuinteCodigo = $this->iReciboSacadoContribuinteCodigo;
    $sReciboSacadoContribuinteTitulo = $this->sReciboSacadoContribuinteTitulo;
  }

  for ( $iInd=0; $iInd < $iNroRec; $iInd++ ) {
      if (!isset($this->arraycodtipo) || empty($this->arraycodtipo[$iInd]) ) {
        continue;
     }

     if ($this->arraycodtipo[$iInd] == 't' && $this->arrayvalreceitas[$iInd] < 0 ){
        $this->totaldesc  += $this->arrayvalreceitas[$iInd];
     } else if ($this->arraycodtipo[$iInd] == 't' and $this->arrayvalreceitas[$iInd] > 0){
        $this->totalacres += $this->arrayvalreceitas[$iInd];
     } else {
        $this->totalrec   += $this->arrayvalreceitas[$iInd];
     }

  }

  if (empty($this->totaldesc) && !empty($this->descontototal) ) {
    $this->totaldesc = $this->descontototal;
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
  $this->objpdf->cell(30,$iAltLabel,'Agência/Código Beneficiário'   ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30 ,$iAlt,$this->agencia_cedente         ,'BLR',1,'L');
  $this->objpdf->cell(10,$iAltLabel,'Espécie'                  ,'TLR',0,"L");
  $this->objpdf->cell(20,$iAltLabel,'Carteira'                 ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(10,$iAlt,$this->especie                  ,'BLR',0,"L");
  $this->objpdf->cell(20,$iAlt, substr($this->carteira, 0, -1)   ,'BLR',1,"L");
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
  $this->objpdf->cell(30,$iAlt,(isset($this->outros_acrecimos) ? $this->outros_acrecimos : '') ,'BLR',1,"R");
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
  $this->objpdf->cell(30,$iAltLabel, $sReciboSacadoContribuinteTitulo      ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt, $iReciboSacadoContribuinteCodigo            ,'BLR',1,"L");
  $this->objpdf->cell(30,$iAltLabel,''                                     ,'TLR',1,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(30,$iAlt,'Recibo do Pagador'                          ,'BLR',1,"R");

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
  $this->objpdf->cell(90,$iAltLabel,'Beneficiário'               ,'TL',0,'L',0);
  $this->objpdf->cell(32, $iAltLabel, "CNPJ", 'LR', 0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'Agência/Código Beneficiário','TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(90,$iAlt,$this->prefeitura     ,'BRL',0,'L',0);
  $this->objpdf->cell(32, $iAlt,  db_formatar($this->cgcpref,'cnpj'), 'LR', 0, 'L', 0);
  $this->objpdf->cell(50 ,$iAlt,$this->agencia_cedente,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(122,$iAltLabel,'Endereço do Beneficiário'    ,'TLR',0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'Nosso Número'         ,'TR' ,1,'L',0);

    /* Endereco do beneficiario*/

  $sEnderPref = $this->enderpref;

  if (!empty($this->numeropref)) {
    $sEnderPref .= ", ".$this->numeropref;
  }

  if (!empty($this->compl)) {
    $sEnderPref .= ", ".$this->compl;
  }

  $sMunicPref = $this->municpref;

  if (!empty($this->ufInstit)) {
    $sMunicPref .= "/".$this->ufInstit;
  }

  if (!empty($this->cep)) {
    $sMunicPref .= " - CEP: ".$this->cep;
  }

  $sEnderecoBeneficiario = $sEnderPref . ' - ' . $sMunicPref;

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(122 ,$iAlt, $sEnderecoBeneficiario ,'LBR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAlt,$this->nosso_numero      ,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(22 ,$iAltLabel,'Data do Documento'    ,'TLR',0,'L',0);
  $this->objpdf->cell(35 ,$iAltLabel,'Número do Documento'  ,'TR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAltLabel,'Espécie Doc.'         ,'TR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAltLabel,'Aceite'               ,'TR' ,0,'L',0);
  $this->objpdf->cell(26 ,$iAltLabel,'Data do Processamento','TR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAltLabel,'(=) Valor do Documento','TR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(22 ,$iAlt,$this->data_processamento,'BLR',0,'L',0);
  $this->objpdf->cell(35 ,$iAlt,$this->descr9            ,'BR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAlt,$this->especie_doc       ,'BR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAlt,$this->aceite            ,'BR' ,0,'L',0);
  $this->objpdf->cell(26 ,$iAlt,date('d/m/Y')            ,'BR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAlt,$this->valor_cobrado,'BR' ,1,'L',0);

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(26 ,$iAltLabel,'Uso do Banco'          ,'TLR',0,'L',0);
  $this->objpdf->cell(25 ,$iAltLabel,'Carteira'              ,'TR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAltLabel,'Espécie'               ,'TR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAltLabel,'Quantidade'            ,'TR' ,0,'L',0);
  $this->objpdf->cell(32 ,$iAltLabel,'Valor'                 ,'TR' ,0,'L',0);
  $this->objpdf->cell(50, $iAltLabel,'(-) Desconto / Abatimento','TLR',1,"L");

  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(26 ,$iAlt,''                  ,'BLR',0,'L',0);
  $this->objpdf->cell(25 ,$iAlt, substr($this->carteira, 0, -1) ,'BR' ,0,'L',0);
  $this->objpdf->cell(17 ,$iAlt, $this->especie      ,'BR' ,0,'L',0);
  $this->objpdf->cell(22 ,$iAlt, (isset($this->quantidade) ? $this->quantidade : ''),'BR' ,0,'L',0);
  $this->objpdf->cell(32 ,$iAlt, (isset($this->valorhis) ? $this->valorhis : ''),'BR' ,0,'L',0);
  $this->objpdf->cell(50 ,$iAlt, '','BR' ,1,'L',0);

  /* Monta instrucao */
  $this->objpdf->SetFont('Arial','',$nFonte);

  $sInstrucao = '';

  /* Trata o tamanho da instrucao para não quebrar o layout do carne */
  if (isset($this->descr12_1)) {
    $aDescr12_1 = explode("\n", $this->descr12_1, 3);
    if (isset($this->tipo_exerc) && isset($this->descr12_1)) {
      $sInstrucao .= "Tipo/Exercício: ".$this->tipo_exerc." ".$aDescr12_1[0]." \n" . $aDescr12_1[1] . "\r\n";
    }

    $sDescr12_1Complementar = str_replace("\n", " ", $aDescr12_1[2]);
    $sDescr12_1Complementar = str_replace("\r", " ", $sDescr12_1Complementar);

    $sInstrucao .= $sDescr12_1Complementar;
    // aumentado tamanho para que caiba os 150 caractéres da mensagem 'Guia Contribuinte' informada em:
    // ARRECADAÇÃO > CADASTROS > TIPO DE DÉBITOS > ALTERAR TIPO DE DÉBITO
    $sInstrucao = substr($sInstrucao,0,280) . "\n";
  }

  if (isset($this->valororigem) && !empty($this->valororigem)) {
    $sInstrucao .= " Valor origem= ".trim($this->valororigem);
  }
  if (isset($this->valtotal) && !empty($this->valtotal)) {
    $sInstrucao .= " Valor corrigido = ".trim($this->valtotal);
  }
  if (isset($this->desconto_abatimento) && !empty($this->desconto_abatimento)) {
    $sInstrucao .= " Desconto/Abatimento = ".trim($this->desconto_abatimento);
  }
  if (isset($this->mora_multa) && !empty($this->mora_multa)) {
    $sInstrucao .= " Mora/Multa = ".trim($this->mora_multa);
  }
  if (isset($this->valor_cobrado) && !empty($this->valor_cobrado)) {
    $sInstrucao .= " Valor do documento = ".trim($this->valor_cobrado);
  }


  $this->predescr12_1 = "";
  $iYPos = $this->objpdf->getY();
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->SetFont('Arial','b',$nFonteLabel);
  $this->objpdf->cell(122,$iAltLabel,'Instruções'   ,'TLR',1,'L',0);
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->setX($iXPosPagina);
  $this->objpdf->multicell(122,3, $sInstrucao);
  $iXPos = 122+$iXPosPagina;
  $this->objpdf->setXY($iXPos,$iYPos);

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
  $this->objpdf->cell(50,$iAlt,(isset($this->outros_acrecimos) ? $this->outros_acrecimos : '') ,'BLR',1,"L");

  /* SAC BANCO */
  $this->objpdf->SetFont('Arial','b',$nFonte);
  if ($this->banco == "CAIXA" || $this->banco == "CEF") {

    $this->objpdf->setY($iYPos+22);
    $this->objpdf->setX($iXPosPagina);
    $this->objpdf->cell(122,$iAltLabel, "SAC CAIXA: 0800 726 0101 (informações, reclamações, sugestões e elogios)",'LR',1,"L");
    $this->objpdf->setX($iXPosPagina);
    $this->objpdf->cell(122,$iAltLabel-1, "Para pessoas com deficiência auditiva ou de fala: 0800 726 2492 OUVIDORIA: 0800 725 7474",'LR',1,"L");
    $this->objpdf->setX($iXPosPagina);
    $this->objpdf->cell(122,$iAltLabel-1, "caixa.gov.br",'LR',0,"L");

  } else if ($this->banco == "BANRISUL") {

    $this->objpdf->setY($iYPos+22);
    $this->objpdf->setX($iXPosPagina);
    $this->objpdf->cell(122,$iAltLabel+2, "",'LR',1,"L");
    $this->objpdf->setX($iXPosPagina);
    $this->objpdf->cell(122,$iAltLabel-1, "SAC BANRISUL: 0800 646 1515  -  OUVIDORIA BANRISUL: 0800 644 2200",'LR',0,"L");
  }
  /* FIM SAC BANCO */

  $this->objpdf->setY($iYPos+18);
  $this->objpdf->setX($iXPosPagina+122);
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
  $this->objpdf->cell(15,$iAlt,'Pagador'                     ,'L' ,0,"L");
  $this->objpdf->SetFont('Arial','',$nFonte);
  $this->objpdf->cell(72,$iAlt,substr($this->descr11_1,0,52),'0'  ,0,"L");
  $this->objpdf->cell(35,$iAlt, "CPF/CNPJ: ". (isset($this->cgccpf) ? db_formatar($this->cgccpf,(strlen($this->cgccpf)<12?'cpf':'cnpj')) : ''),'R',0,'L');
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
  $this->objpdf->cell(15 ,$iAlt,'Pagador/Avalista'                                  ,'L' ,0,"L");
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
