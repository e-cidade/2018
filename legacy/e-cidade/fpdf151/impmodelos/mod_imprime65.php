<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

$this->objpdf->AliasNbPages();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->lmargin = 0;

if (($this->qtdcarne % 2 ) == 0 ){
  $this->objpdf->AddPage('L');
}


if($this->atualizaquant == true){
  $this->qtdcarne += 1;
}

$nFonte      = 7;
$iAlt        = 4;
$iAltLabel   = 3;
$nFonteLabel = 5;

$iXPosPagina = 90;
$iYPosPagina = $this->objpdf->getY() - 7;

$this->especie_doc     = "RC";
$this->aceite          = "N";
$this->localpagamento  = " QUALQUER BANCO ATÉ O VENCIMENTO ";


$this->totalrec   = 0;
$this->totaldesc  = 0;
$this->totalacres = 0;


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

$this->objpdf->setY($iYPosPagina);

$this->objpdf->Image($this->imagemlogo,$this->objpdf->getX(),$this->objpdf->getY(),27,6);
$this->objpdf->ln($iAlt+3);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(78,$iAltLabel,'Cedente'   ,'TLR',1,'L',0);
$this->objpdf->SetFont('Arial','' ,$nFonte);
$this->objpdf->cell(78,$iAlt,$this->prefeitura,'LRB',1,'L',0);

$this->objpdf->SetFont('Arial','',$nFonte);

$sInstrucao1 = " Tipo/Exercício: ".@$this->tipo_exerc." ".$this->descr12_1." \n";

if( @$this->valororigem != "" ){
  $sInstrucao1 .= " Valor origem= ".trim($this->valororigem);
}

if( @$this->valtotal != "" ){

  if (!empty($this->totaldescunica)){

    $nValor              = DBNumber::toCurrency($this->valor_cobrado);
    $nValorDescontoUnica = DBNumber::toCurrency($this->totaldescunica);
    $nValorComDesconto   = $nValor + $nValorDescontoUnica;
    $this->valtotal      = db_formatar($nValorComDesconto,'f');
  }

  $sInstrucao1 .= " Valor corrigido = ".trim($this->valtotal);
}
if( @$this->desconto_abatimento != "" ){
  if (!empty($this->totaldescunica)){
    $this->desconto_abatimento = $this->totaldescunica;
  }
  $sInstrucao1 .= " Desconto/Abatimento = ".trim($this->desconto_abatimento);
  unset($this->totaldescunica);
}
if( @$this->mora_multa != "" ){
  $sInstrucao1 .= " Mora/Multa = ".trim($this->mora_multa);
}
if( @$this->valor_cobrado != "" ){
  $sInstrucao1 .= " Valor do documento = ".trim($this->valor_cobrado);
}

$iYPos = $this->objpdf->getY();

$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(78,$iAltLabel,'Instruções'   ,'TLR',1,'L',0);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->multicell(50,3,$sInstrucao1,'L');

$iXPos = 63;

$this->objpdf->setXY($iXPos,$iYPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'Agência/Código Cedente'   ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(10,$iAltLabel,'Parcela'                  ,'TLR',0,"L");
$this->objpdf->cell(15,$iAltLabel,'Vencimento'               ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(10,$iAlt,$this->descr10                  ,'BLR',0,"L");
$this->objpdf->cell(15,$iAlt,$this->dtparapag                ,'BLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'Nosso Número'             ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);

if( strlen($this->nosso_numero) > 18 ){
  $this->objpdf->SetFont('Arial','', 6);
}
$this->objpdf->cell(25,$iAlt,$this->nosso_numero             ,'BLR',1,"L");

$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'(=) Valor do Documento'   ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(25,$iAlt,$this->valor_cobrado            ,'BLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'(-) Desconto / Abatimento','TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(25,$iAlt,''                              ,'BLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'(+) Mora Multa'           ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(25,$iAlt,''                              ,'BLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'(+) Outros Acréscimos'    ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(25,$iAlt,@$this->outros_acrecimos        ,'BLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(25,$iAltLabel,'(=) Valor Cobrado'        ,'TLR',1,"L");
$this->objpdf->setX($iXPos);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(25,$iAlt,''                              ,'BLR',1,"L");

$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(78,$iAltLabel,'Sacado'                   ,'TLR',1,"L");
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(78,$iAlt,substr($this->descr11_1,0,42)   ,'LR' ,1,"L");
$this->objpdf->cell(78,$iAlt,"CPF/CNPJ: ".db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')),'LR',1,"L");
$this->objpdf->cell(78,$iAlt,$this->descr11_2                ,'LR'  ,1,"L");

if (!isset($this->ufcgm)) {
  $this->ufcgm = $this->uf_config;
}

$this->objpdf->cell(78,$iAlt,$this->munic." / ".$this->ufcgm." / CEP-".$this->cep,'BLR' ,1,"L");
$this->objpdf->Line(10,$iYPos,10,$this->objpdf->getY());

$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial','',4);

$sBase  = db_getsession('DB_base');
$sHora  = db_hora();
$sUser  = db_getsession('DB_login');
$sData  = date('d/m/Y',db_getsession('DB_datausu'));
$sTexto = " Usuário: {$sUser}         Base: {$sBase}         Data: {$sData}         Hora: {$sHora}";

$this->objpdf->TextWithDirection(9.5,$this->objpdf->getY(),$sTexto,'U');




$this->objpdf->setXY($iXPosPagina,$iYPosPagina);
$this->objpdf->Image($this->imagemlogo,$this->objpdf->getX(),$this->objpdf->getY(),32,6);
$this->objpdf->cell(45 ,$iAlt+3,''                     ,'RB' ,0,'C',0);
$this->objpdf->SetFont('Arial','b',$nFonte+7);
$this->objpdf->cell(15 ,$iAlt+3,$this->numbanco        ,'LRB',0,'C',0);
$this->objpdf->SetFont('Arial','b',$nFonte+6);
$this->objpdf->cell(137,$iAlt+3,$this->linha_digitavel ,'LB' ,1,'C',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(147,$iAltLabel,'Local de Pagamento' ,'TLR',0,'L',0);
$this->objpdf->cell(20 ,$iAltLabel,'Parcela'            ,'TR' ,0,'L',0);
$this->objpdf->cell(30 ,$iAltLabel,'Vencimento'         ,'TR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonte);
$this->objpdf->cell(147,$iAlt,$this->localpagamento,'BRL',0,'L',0);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(20 ,$iAlt,$this->descr10       ,'BR' ,0,'L',0);
$this->objpdf->cell(30 ,$iAlt,$this->dtparapag     ,'BR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(147,$iAltLabel,'Cedente'               ,'TLR',0,'L',0);
$this->objpdf->cell(50 ,$iAltLabel,'Agência/Código Cedente','TR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(147,$iAlt,$this->prefeitura     ,'BRL',0,'L',0);
$this->objpdf->cell(50 ,$iAlt,$this->agencia_cedente,'BR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(32 ,$iAltLabel,'Data do Documento'    ,'TLR',0,'L',0);
$this->objpdf->cell(35 ,$iAltLabel,'Número do Documento'  ,'TR' ,0,'L',0);
$this->objpdf->cell(22 ,$iAltLabel,'Espécie Doc.'         ,'TR' ,0,'L',0);
$this->objpdf->cell(26 ,$iAltLabel,'Aceite'               ,'TR' ,0,'L',0);
$this->objpdf->cell(32 ,$iAltLabel,'Data do Processamento','TR' ,0,'L',0);
$this->objpdf->cell(50 ,$iAltLabel,'Nosso Número'         ,'TR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(32 ,$iAlt,$this->data_processamento,'BLR',0,'L',0);
$this->objpdf->cell(35 ,$iAlt,$this->descr9            ,'BR' ,0,'L',0);
$this->objpdf->cell(22 ,$iAlt,$this->especie_doc       ,'BR' ,0,'L',0);
$this->objpdf->cell(26 ,$iAlt,$this->aceite            ,'BR' ,0,'L',0);
$this->objpdf->cell(32 ,$iAlt,date('d/m/Y')            ,'BR' ,0,'L',0);
$this->objpdf->cell(50 ,$iAlt,$this->nosso_numero      ,'BR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(32 ,$iAltLabel,'Uso do Banco'          ,'TLR',0,'L',0);
$this->objpdf->cell(30 ,$iAltLabel,'Carteira'              ,'TR' ,0,'L',0);
$this->objpdf->cell(20 ,$iAltLabel,'Espécie'               ,'TR' ,0,'L',0);
$this->objpdf->cell(25 ,$iAltLabel,'Quantidade'            ,'TR' ,0,'L',0);
$this->objpdf->cell(40 ,$iAltLabel,'Valor'                 ,'TR' ,0,'L',0);
$this->objpdf->cell(50 ,$iAltLabel,'(=) Valor do Documento','TR' ,1,'L',0);

$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(32 ,$iAlt,''                  ,'BLR',0,'L',0);
$this->objpdf->cell(30 ,$iAlt,$this->carteira     ,'BR' ,0,'L',0);
$this->objpdf->cell(20 ,$iAlt,$this->especie      ,'BR' ,0,'L',0);
$this->objpdf->cell(25 ,$iAlt,@$this->quantidade  ,'BR' ,0,'L',0);
$this->objpdf->cell(40 ,$iAlt,@$this->valorhis    ,'BR' ,0,'L',0);
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

$iYPos = $this->objpdf->getY();
$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(147,$iAltLabel,'Instruções'   ,'TLR',1,'L',0);
$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->multicell(147,2.9,$sInstrucao,0);

$iXPos = 147+$iXPosPagina;

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
$this->objpdf->cell(50,$iAlt,''                              ,'BLR',1,"L");
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

$this->objpdf->Line($iXPosPagina,$iYPos,$iXPosPagina,$this->objpdf->getY());


$this->objpdf->setX($iXPosPagina);
$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(20,$iAlt,'Sacado'                      ,'TL' ,0,"L");
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(127,$iAlt,substr($this->descr11_1,0,42),'TR' ,0,"L");
$this->objpdf->cell(50,$iAlt,''                           ,'TLR',1,"L");
$this->objpdf->setX($iXPosPagina);
$this->objpdf->cell(20,$iAlt,''                           ,'L'  ,0,"L");
$this->objpdf->cell(127,$iAlt,"CPF/CNPJ: ".db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')),'R',0,"L");
$this->objpdf->cell(50,$iAlt,''                           ,'R'  ,1,"L");
$this->objpdf->setX($iXPosPagina);
$this->objpdf->cell(20,$iAlt,''                           ,'L'  ,0,"L");
$this->objpdf->cell(127,$iAlt,$this->descr11_2             ,'R'  ,0,"L");
$this->objpdf->cell(50,$iAlt,''                           ,'LR' ,1,"L");
$this->objpdf->setX($iXPosPagina);

if (!isset($this->ufcgm)) {
  $this->ufcgm = $this->uf_config;
}

$this->objpdf->SetFont('Arial','b',$nFonteLabel);
$this->objpdf->cell(20,$iAlt-1,'Sacador/Avalista'                                  ,'BL' ,0,"L");
$this->objpdf->SetFont('Arial','',$nFonte);
$this->objpdf->cell(127,$iAlt-1,$this->munic." / ".$this->ufcgm." / CEP-".$this->cep,'BR' ,0,"L");
$this->objpdf->cell(50,$iAlt-1,''                                                  ,'BLR',1,"L");


if ($this->codigo_barras != null) {
  $this->objpdf->int25($iXPosPagina,$this->objpdf->getY()+1,$this->codigo_barras,15,0.3);
}

$this->objpdf->setY($this->objpdf->getY()+25);