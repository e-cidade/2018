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

//  F I C H A   D E   C O M P E N S A Ç Ã O



$y  = 189;
$x  = 5;

$xx = 15;
$yy = 0;

$this->objpdf->SetDash(1,1);
$this->objpdf->Line($x-5,     $y-0.4, $xx+$x+190, $y-0.4); //horiz
$this->objpdf->SetDash();

$this->objpdf->Line($x+42,$y+1,$x+42,$y+9);  //vert
$this->objpdf->Line($x+57,$y+1,$x+57,$y+9);  //vert

$this->objpdf->SetLineWidth(0.4);

$this->objpdf->Line($x,        $y+9, $xx+$x+182, $y+9);   // horiz linha inicial superior  1
$this->objpdf->Line($x,        $y+9, $x,         $y+87);  // vert  linha inicial lateral esquerda 1
$this->objpdf->Line($xx+$x+182,$y+9, $xx+$x+182, $y+87);  // vart  linha final lateral esquerda  2

$this->objpdf->SetLineWidth(0.2);

$this->objpdf->Line($x,  $y+17, $xx+$x+182, $y+17); //horiz  2
$this->objpdf->Line($x,  $y+24, $xx+$x+182, $y+24); //horiz  3

$this->objpdf->Line($x,  $y+31, $xx+$x+182, $y+31); //horiz  4
$this->objpdf->Line($x,  $y+38, $xx+$x+182, $y+38); //horiz  5
$this->objpdf->Line($x,  $y+45, $xx+$x+182, $y+45); //horiz  6

/* Texto de Responsabilidade */
$this->objpdf->Line($x+136, $y+52, $xx+$x+182, $y+52); //horiz  7
$this->objpdf->Line($x+136, $y+59, $xx+$x+182, $y+59); //horiz  8
$this->objpdf->Line($x+136, $y+66, $xx+$x+182, $y+66); //horiz  9
$this->objpdf->Line($x,     $y+73, $xx+$x+182, $y+73); //horiz 10

$this->objpdf->Line($x+136, $y+9,  $x+136, $y+87); //vert 2
$this->objpdf->Line($x+156, $y+9,  $x+156, $y+17); //vert linha vencimento

/* Linhas Verticais "Data do Documento" */
$this->objpdf->Line($x+27,  $y+31, $x+27,  $y+38); //vert
$this->objpdf->Line($x+73,  $y+31, $x+73,  $y+38); //vert
$this->objpdf->Line($x+99,  $y+31, $x+99,  $y+38);  //vert
$this->objpdf->Line($x+112, $y+31, $x+112, $y+38); //vert

/* Linhas Verticais "Uso do Banco" */
$this->objpdf->Line($x+32,  $y+38, $x+32,  $y+45); //vert
$this->objpdf->Line($x+53,  $y+38, $x+53,  $y+45); //vert
$this->objpdf->Line($x+78,  $y+38, $x+78,  $y+45); //vert
$this->objpdf->Line($x+108, $y+38, $x+108, $y+45); //vert

$this->objpdf->SetLineWidth(0.4);
$this->objpdf->Line($x,     $y+87, $xx+$x+182, $y+87); //horiz ultima linha

//Codigo de barras
$this->objpdf->SetFillColor(0,0,0);

if ($this->codigo_barras != null) {
  $this->objpdf->int25(10,$y+92.5,$this->codigo_barras,15,0.3);
}

//Quadrado inferior
$this->objpdf->Image($this->imagemlogo,$x,$y+1,32,7);
$this->objpdf->SetFont('Arial','b',14);
$this->objpdf->Text($x+43,  $y+7,$this->numbanco);      // numero do banco
$this->objpdf->SetFont('Arial','b',13);
if ($this->linha_digitavel != null) {
  $this->objpdf->Text($x+61,  $y+7,$this->linha_digitavel);
}
$this->objpdf->SetFont('Arial','b',5);
$this->objpdf->Text($x+3,   $y+11,"Local de Pagamento");
$this->objpdf->Text($x+138, $y+11,"Parcela");
$this->objpdf->Text($x+158, $y+11,"Vencimento");

$this->objpdf->Text($x+3,   $y+19,"Beneficiário");
$this->objpdf->Text($x+3,   $y+26,"Endereço do Beneficiário");

$this->objpdf->Text($x+138, $y+19,"Agência/Código Beneficiário");

$this->objpdf->Text($x+3,   $y+33,"Data do Documento");
$this->objpdf->Text($x+29,  $y+33,"Número do Documento");
$this->objpdf->Text($x+75,  $y+33,"Espécie Doc.");
$this->objpdf->Text($x+101, $y+33,"Aceite");
$this->objpdf->Text($x+114, $y+33,"Data do Processamento");
$this->objpdf->Text($x+138, $y+26,"Nosso Número");

$this->objpdf->Text($x+3,   $y+40,"Uso do banco");
$this->objpdf->Text($x+34,  $y+40,"Carteira");
$this->objpdf->Text($x+54,  $y+40,"Espécie");
$this->objpdf->Text($x+80,  $y+40,"Quantidade");
$this->objpdf->Text($x+110, $y+40,"Valor");
$this->objpdf->Text($x+138, $y+33,"( = ) Valor do Documento");

if ( isset($this->sTituloInstrucoes) && trim($this->sTituloInstrucoes) != '' ) {
  $this->objpdf->Text($x+3,   $y+40,$this->sTituloInstrucoes);
} else {
  $this->objpdf->Text($x+3,   $y+47,"TEXTO DE RESPONSABILIDADE DO BENEFICIÁRIO");
}

$this->objpdf->Text($x+138, $y+40,"( - ) Desconto / Abatimento");

$this->objpdf->Text($x+138, $y+47,"( - ) Outras Deduções");
$this->objpdf->Text($x+138, $y+54,"( + ) Mora / Multa");
$this->objpdf->Text($x+138, $y+61,"( + ) Outros Acréscimos");
$this->objpdf->Text($x+138, $y+68,"( = ) Valor Cobrado");
$this->objpdf->Text($x+3,   $y+75,"Pagador");
$this->objpdf->Text($x+3,   $y+85,"Pagador/Avalista");

$this->objpdf->SetFont('Arial','b',6);
$this->objpdf->Text($x+120, $y+90,"AUTENTICAÇÃO MECÂNICA / FICHA DE COMPENSAÇÃO");

$this->especie_doc     = "RC";
$this->aceite          = "N";
$this->localpagamento  = " QUALQUER BANCO ATÉ O VENCIMENTO ";

$this->objpdf->SetFont('Arial','b',8);
$this->objpdf->Text($x+3,   $y+15,$this->localpagamento);              // local de pagamento
$this->objpdf->SetFont('Arial','',8);
$this->objpdf->Text($x+138, $y+15,$this->descr10);                     // $this->parcela); // parcela
$this->objpdf->Text($x+158, $y+15,$this->dtparapag);                   // $this->dtvenc);  // vencimento


if ( isset($this->sCedenteBoleto) && trim($this->sCedenteBoleto) != '') {
  $this->objpdf->Text($x+3, $y+23,$this->sCedenteBoleto);              // cedente
} else {

  $cnpjBeneficiario = '';
  if (!empty($this->cnpjBeneficiario) && trim($this->cnpjBeneficiario) !== '') {
    $cnpjBeneficiario = "CNPJ: {$this->cnpjBeneficiario} ";
  }
  $this->objpdf->Text($x+3, $y+23,$this->prefeitura);                  // cedente
  $this->objpdf->Text($x+85, $y+23,$cnpjBeneficiario);
  $this->objpdf->Text($x+3, $y+30,$this->enderecoInstituicao);         // cedente
}

$this->objpdf->SetFont('Arial','b',8);
$this->objpdf->Text($x+125,   $y+23,$this->tipo_convenio);             // tipo_convenio
$this->objpdf->SetFont('Arial','',8);
$this->objpdf->Text($x+138, $y+23,$this->agencia_cedente);             // agencia do cedente


$this->objpdf->Text($x+3,   $y+37,$this->data_processamento);          // data do documento
$this->objpdf->Text($x+29,  $y+37,$this->descr9);                      // numero do documento
$this->objpdf->Text($x+75,  $y+37,$this->especie_doc);                 // especie do documento
$this->objpdf->Text($x+101, $y+37,$this->aceite);                      // aceite
$this->objpdf->Text($x+114, $y+37,date('d/m/Y'));                      // data de opercao data do processamento
$this->objpdf->Text($x+138, $y+30,$this->nosso_numero);                // nosso numero

$this->objpdf->Text($x+3,   $y+44,"");                                 // codigo do cedente
$this->objpdf->Text($x+34,  $y+44,$this->carteira);                    // carteira
$this->objpdf->Text($x+54,  $y+44,$this->especie);                     // especie
$this->objpdf->Text($x+80,  $y+44, empty($this->quantidade) ? null : $this->quantidade); // quantidade
$this->objpdf->Text($x+110, $y+44, empty($this->valorhis) ? null : $this->valorhis);     // valor
$this->objpdf->Text($x+133, $y+37, $this->valor_cobrado);              // valor do Documento

$this->objpdf->setXy($x+2,$y+48);
$this->objpdf->SetFont('Arial','',9);

if (is_null($this->sMensagemCaixa) || $this->sMensagemCaixa == '' ) {
  $this->sMensagemCaixa = $this->descr12_1;
}

$instrucao = "Tipo/Exercício: ".@$this->tipo_exerc."\n".@$this->sMensagemCaixa . " \n";

if(@$this->valororigem!=""){
  $instrucao .= " Valor origem= ".trim($this->valororigem);
}
if(@$this->valtotal!=""){
  $instrucao .= " Valor corrigido = ".trim($this->valtotal);
}
if(@$this->desconto_abatimento!=""){
  $instrucao .= " Desconto/Abatimento = ".trim($this->desconto_abatimento);
}
if(@$this->mora_multa!=""){
  $instrucao .= " Mora/Multa = ".trim($this->mora_multa);
}
if(@$this->valor_cobrado!=""){
  $instrucao .= " Valor do documento = ".trim($this->valor_cobrado);
}

$this->objpdf->SetFont('Arial','',6);
$this->objpdf->multicell(133,2,$instrucao);                            // Instrução
$this->objpdf->SetFont('Arial','',10);

$this->objpdf->setXY($x+136,$y+39);
$this->objpdf->cell(30,6,"",0,0,"R");                                  // desconto abatimento;
$this->objpdf->setXY($x+136,$y+46);
$this->objpdf->cell(30,6,'',0,0,"R");                                  // outras deduções
$this->objpdf->setXY($x+136,$y+53);
$this->objpdf->cell(30,6,"",0,0,"R");                                  // multa
$this->objpdf->setXY($x+136,$y+60);
$this->objpdf->cell(30,6,@$this->outros_acrecimos,0,0,"R");            // outros acrescimos
$this->objpdf->setXY($x+136,$y+67);
$this->objpdf->cell(30,6,"",0,0,"R");                                  // valor cobrado
$this->objpdf->SetFont('Arial','',8);
$this->objpdf->Text($x+19,  $y+77,substr($this->descr11_1,0,42));      // sacado 1
$this->objpdf->Text($x+93,  $y+77,"CPF/CNPJ: ".db_formatar(@$this->cgccpfcomprador,(strlen(@$this->cgccpfcomprador)<12?'cpf':'cnpj'))); // CPF/CNPJ
$this->objpdf->Text($x+19,  $y+80,$this->descr11_2);                   // sacado 2
if (!isset($this->ufcgm)) {
  $this->ufcgm = $this->uf_config;
}
$this->objpdf->Text($x+19,  $y+83,$this->munic." / ".$this->ufcgm." / CEP-".$this->cep);  // sacado 3