<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBseller Servicos de Informatica
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

if (empty($this->cep)) {
  $this->cep = $oDadosInstit->cep;
}

if (empty($this->telefpref)) {
  $this->telefpref = $oDadosInstit->telef;
}

if (empty($this->cgcpref)) {
  $this->cgcpref = $oDadosInstit->cgc;
}

if (empty($this->emailpref)) {
  $this->emailpref = $oDadosInstit->email;
}

if (empty($this->logo)) {
  $this->logo = $oDadosInstit->logo;
}

if (empty($this->cgccpfcomprador)) {
  $this->cgccpfcomprador = $this->cgccpf;
}

if (empty($this->codigo_barras)) {
  $this->codigo_barras = $this->codigobarras;
}

if (empty($this->linhadigitavel)) {
  $this->linhadigitavel = $this->linha_digitavel;
}

$this->sTituloInstrucoes = 'TEXTO DE RESPONSABILIDADE DO BENEFICIÁRIO';

$this->objpdf->AliasNbPages();
$this->objpdf->setAutoPageBreak(1, 1);
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->line(2, 148.5, 208, 148.5);

$xlin = 21;
$xcol = 4;

$this->objpdf->roundedrect($xcol-2, $xlin-18, 206, 185.5, 2, 'DF', '1234');

$this->objpdf->setfillcolor(245);
$this->objpdf->roundedrect($xcol-2, $xlin-18, 206, 185.5, 2, 'DF', '1234');
$this->objpdf->setfillcolor(255, 255, 255);

$this->objpdf->roundedrect($xcol, $xlin-16, 36, 16, 2, 'DF', '1234');

$this->objpdf->image($this->imagemlogo, $x+6, $y+9.5, 32, 7);

$this->objpdf->roundedrect($xcol+37, $xlin-16, 102, 16, 2, 'DF', '1234');

$this->objpdf->roundedrect($xcol+140, $xlin-16, 62, 16, 2, 'DF', '1234');

$this->objpdf->setfont('Arial','B',11);
$this->objpdf->text(154, $xlin-9, 'RECIBO DO PAGADOR ');

if (substr($this->dtparapag, 4, 1) == '-' || substr($this->dtparapag, 7, 1) == '/') {
 $this->dtparapag =  db_formatar($this->dtparapag,'d');
}

$this->objpdf->setfont('Arial', 'B', 9);
$this->objpdf->text(146, $xlin-5, "DOCUMENTO VÁLIDO ATÉ: ".$this->dtparapag);

$this->objpdf->Setfont('Arial', 'B', 8);

$this->objpdf->Image('imagens/files/'.$this->logo, 43, @$xlin-14.5, 10);
;
$this->objpdf->Setfont('Arial', 'b', 8);
$this->objpdf->text(83, $xlin-13, "Beneficiário");

$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->text(58, $xlin-10, $this->prefeitura);

$sEnderPref = $this->enderpref;

if (!empty($this->numeropref)) {
  $sEnderPref .= ", ".$this->numeropref;
}

if (!empty($this->compl)) {
  $sEnderPref .= ", ".$this->compl;
}

$sMunicPref = $this->municpref;

if (!empty($this->uf)) {
  $sMunicPref .= "/".$this->uf;
}

if (!empty($this->cep)) {
  $sMunicPref .= " - CEP: ".$this->cep;
}

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text(58,       $xlin-7.6, $sEnderPref);
$this->objpdf->text(58,       $xlin-5.2, $sMunicPref);
$this->objpdf->text(58,       $xlin-3,  $this->telefpref);
$this->objpdf->text($xcol+78, $xlin-3,  "CNPJ: ");
$this->objpdf->text($xcol+85, $xlin-3,  db_formatar($this->cgcpref,'cnpj'));
$this->objpdf->text(58,       $xlin-1,  $this->emailpref);

$this->objpdf->roundedrect(@$xcol, @$xlin+2, @$xcol+119, 20, 2, 'DF', '1234');

$this->objpdf->Setfont('Arial', '', 6);
$this->objpdf->text(@$xcol+2, @$xlin+4, 'Identificação do Pagador:');
$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->text($xcol+2,  $xlin+7,  'Nome: ');
$this->objpdf->text($xcol+17, $xlin+7,  $this->descr11_1);
$this->objpdf->text($xcol+2,  $xlin+11, 'Endereço: ');
$this->objpdf->text($xcol+17, $xlin+11, $this->descr11_2);

$this->objpdf->text($xcol+2,  $xlin+15, 'Bairro: ');
$this->objpdf->text($xcol+17, $xlin+15, $this->bairrocontri);

$this->objpdf->text($xcol+2,  $xlin+19, 'Município: ');
$this->objpdf->text($xcol+17, $xlin+19, $this->munic);
$this->objpdf->text($xcol+75, $xlin+15, 'CEP: ');
$this->objpdf->text($xcol+83, $xlin+15, $this->cep);
$this->objpdf->text($xcol+75, $xlin+19, 'CNPJ/CPF: ');
$this->objpdf->text($xcol+90, $xlin+19, db_formatar(@$this->cgccpf, (strlen(@$this->cgccpf)<12?'cpf':'cnpj')));
$this->objpdf->Setfont('Arial', '', 6);

$this->objpdf->roundedrect(@$xcol+124, @$xlin+2, 78, 20, 2, 'DF', '1234');

$this->objpdf->text($xcol+126, $xlin+4,  $this->identifica_dados);
$this->objpdf->text($xcol+126, $xlin+7,  $this->tipoinscr);
$this->objpdf->text($xcol+143, $xlin+7,  $this->nrinscr);
$this->objpdf->text($xcol+126, $xlin+11, "Rua : ");
$this->objpdf->text($xcol+143, $xlin+11, $this->nomepriimo);
$this->objpdf->text($xcol+126, $xlin+15, $this->tipocompl);
$this->objpdf->text($xcol+143, $xlin+15, $this->nrpri.(isset($this->complpri)&&$this->complpri!=""?" / ".$this->complpri:"") );
$this->objpdf->text($xcol+126, $xlin+19, "Bairro : ");
$this->objpdf->text($xcol+143, $xlin+19, $this->bairropri);

$this->objpdf->roundedrect($xcol, $xlin+24, 202, 61, 2, 'DF', '1234'); //Quadro das receitas

$this->objpdf->sety($xlin+24);
$maiscol = 0;
$yy = $this->objpdf->gety();
$intnumrows = count($this->arraycodreceitas);

$this->objpdf->setx($xcol+3+$maiscol);
$this->objpdf->cell(6,  3, "Rec",       0, 0, "L", 0);
$this->objpdf->cell(7,  3, "Reduz",     0, 0, "L", 0);
$this->objpdf->cell(64, 3, "Descrição", 0, 0, "L", 0);
$this->objpdf->cell(18, 3, "Valor",     0, 1, "R", 0);

$reccol           = $xcol+5;
$reccol2          = $xcol+5;

$bklin            = $xlin+30;
$bklin2           = $xlin+30;
$this->totalrec   = 0;
$this->totaldesc  = 0;
$this->totalacres = 0;
for($x=0;$x<$intnumrows;$x++){

  $this->obsdescr = null;
  if($x==50){

    db_redireciona('db_erros.php?fechar=true&db_erro=O numero de receitas ultrapassou o espaço limite do carne.  Contate o suporte!');
    break;
  }

  $this->objpdf->Text($reccol,    $bklin,$this->arraycodreceitas[$x]);
  $this->objpdf->Text($reccol+6,  $bklin,"(".$this->arrayreduzreceitas[$x].")");
  if (@$this->arraycodhist[$x] == 918){
    $this->obsdescr = " (desconto)";
  }
  $this->objpdf->Text($reccol + 12, $bklin,$this->arraydescrreceitas[$x].$this->obsdescr);

  if($x == 0){
    $this->objpdf->SetY($xlin+28);
  }
  $this->objpdf->cell($reccol + 82, 2,db_formatar($this->arrayvalreceitas[$x],'f'),0,1,"R",0);

  $iFormaCorrecao = pg_result(db_query("select k03_separajurmulparc
                                          from numpref
                                         where k03_instit = ".db_getsession("DB_instit")."
                                           and k03_anousu = ".db_getsession("DB_anousu")),0,0);
  if ($iFormaCorrecao == 1) {

    /*
     * Controle da composição
     * utilizado em Canela
     */
  	  if (@$this->arraycodhist[$x] == 918) {
        $this->totaldesc += $this->arrayvalreceitas[$x];
     } else if (@$this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] > 0 and @$this->arraycodhist[$x] != 918) {
        $this->totalacres += $this->arrayvalreceitas[$x];
     } else {
       $this->totalrec += $this->arrayvalreceitas[$x];
     }
  } else {

    if ($this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] < 0){
       $this->totaldesc += $this->arrayvalreceitas[$x];
    }else if (@$this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] > 0){
       $this->totalacres += $this->arrayvalreceitas[$x];
    }else{
       $this->totalrec += $this->arrayvalreceitas[$x];
    }
  }

  if($x==25){

    $bklin  = $bklin2-2;
    $reccol += 98;
    $this->objpdf->SetY($xlin+28);
  }

  $bklin += 2;
}

$xlin+= 15;

$this->objpdf->Roundedrect($xcol,$xlin+72,160,60,2,'DF','1234'); // historico
$this->objpdf->SetY($xlin+72);
$this->objpdf->SetX($xcol+3);
$this->objpdf->multicell(155,4,'HISTÓRICO :   '.$this->descr12_1);

$this->objpdf->SetX($xcol+3);
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->setx(15);

//Dados do desconto
$this->objpdf->Roundedrect($xcol+161, $xlin+72,    40, 60, 2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+163, $xlin+74.5,  36, 9,  2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+163, $xlin+86,    36, 9,  2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+163, $xlin+98,    36, 9,  2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+163, $xlin+109.5, 36, 9,  2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+163, $xlin+121,   36, 9,  2, 'DF', '1234');

$this->objpdf->Setfont('Arial', '', 6);

$this->objpdf->text($xcol+165, $xlin+77,    'Vencimento');
$this->objpdf->text($xcol+165, $xlin+88.5,  '( = ) Valor Devido');
$this->objpdf->text($xcol+165, $xlin+100.5, '( - ) Desconto');
$this->objpdf->text($xcol+165, $xlin+111.8, '( + ) Mora / Multa');
$this->objpdf->text($xcol+165, $xlin+123.5, '( = ) Valor Documento');

$this->objpdf->Setfont('Arial', '', 10);

$this->objpdf->text(184.2, $xlin+80.7, $this->dtparapag);

$this->objpdf->setxy($xcol+163, $xlin+86.7);
$this->objpdf->cell(36, 9, db_formatar($this->totalrec, "f"), 0, 0, "R");

$this->objpdf->setxy($xcol+163, $xlin+98.6);
$this->objpdf->cell(36, 9, db_formatar(abs($this->totaldesc), "f"), 0, 0, "R");

$this->objpdf->setxy($xcol+163, $xlin+110);
$this->objpdf->cell(36, 9, db_formatar($this->totalacres, "f"), 0, 0, "R");

$this->objpdf->Setfont('Arial', 'b', 10);
$this->objpdf->setxy($xcol+163, $xlin+122);
$this->objpdf->cell(36, 9, $this->valtotal, 0, 0, "R");

$xlin += 35;

$this->objpdf->Setfont('Arial', '', 6);

$this->objpdf->Roundedrect(120.1,     $xlin+103, 44, 9, 2, 'DF', '1234');
$this->objpdf->Roundedrect($xcol+161, $xlin+103, 40, 9, 2, 'DF', '1234');

$this->objpdf->text(134.1, $xlin+105, 'Nosso Número');
$this->objpdf->text(168,   $xlin+105, 'Nro. Documento/Cód. Arrecadação');

$this->objpdf->setfont('Arial', '', 10);

if (isset($this->linhadigitavel)){
  $this->objpdf->text(9.7, $xlin+101.5, $this->linhadigitavel);
}

$this->objpdf->text(124.1, $xlin+110, $this->nosso_numero);
$this->objpdf->text(175,   $xlin+110, $this->descr9);

$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial', '', 4);

$this->objpdf->int25(10, $xlin+103, $this->codigo_barras, 12, 0.3);

$sBase  = db_getsession('DB_base');
$sHora  = date("H:i:s");
$sUser  = db_getsession('DB_login');
$sData  = date('d/m/Y',db_getsession('DB_datausu'));
$sTexto = " Usuário: {$sUser}         Base: {$sBase}         Data: {$sData}         Hora: {$sHora}";

$this->objpdf->TextWithDirection(3.6, $xlin+95, $sTexto, 'U');

$this->valor_cobrado       = $this->valtotal;
$this->desconto_abatimento = db_formatar(abs($this->totaldesc),'f');
$this->mora_multa          = db_formatar(($this->totalacres),'f');
$this->valtotal            = db_formatar(($this->totalrec),'f');

/**
 * Inclui a Ficha de Compensação
 */
include(modification("fpdf151/impmodelos/mod_imprime922.php"));
