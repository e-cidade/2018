<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

global $contapagina;
$contapagina = 1;

////modelo de comprovante da requisição do laboratorio 
$this->objpdf->AliasNbPages();
$this->objpdf->settopmargin(1);
$this->objpdf->SetAutoPageBreak('on', 0);
$this->objpdf->line(2, 148.5, 208, 148.5);

$xlin        = 20;
$xcol        = 4;
$comeco      = 0;
$passada     = 0;
$observacao  = false;
$cont_obs    = 0;
$obs         = null;
$iPag_a      = false;
$iPag_b      = false;
$iVias       = 0;
$passou      = 0;
$quant_itens = 15;

$qReg        = $this->linhasdositens;
$total       = 0;
$contaobs    = 0;

for ($i = 0; $i < $qReg; $i++) {

  $contaobs++;

  if ((($contaobs == $quant_itens) || ($passou == 0 && $qReg > $quant_itens)) && ($qReg > $quant_itens)) {

    $iVias++;
    $total += $contaobs;
    $contaobs = 0;
    $iPag_a   = true;
    $passou   = 1;
  }

  if (isset($total) && $qReg < $quant_itens) {

    $iVias++;
    $contaobs = 0;
    $iPag_b   = true;
    break;
  }
}

if ($iPag_a == false) {
  $iVias = 1;
}

$iVias = $iVias * 2;

$this->objpdf->AddPage();

for ($i = 0; $i < $iVias; $i++) {

  if ($this->linhasdositens <= $quant_itens) {
    $comeco = 0;
  }

  $this->objpdf->setfillcolor(245);
  $this->objpdf->roundedrect($xcol - 2, $xlin - 18, 206, 144.5, 2, 'DF', '1234');
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text(110, $xlin - 13, 'RECIBO DE REQUISIÇÃO DE EXAMES N' . chr(176) . ' ' . $this->Rnumero);

  $this->objpdf->Image('imagens/files/logo_boleto.png', 10, $xlin - 17, 12);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(30, $xlin - 15, $this->prefeitura);
  $this->objpdf->Setfont('Arial', '', 9);
  $this->objpdf->text(30, $xlin - 11, $this->enderpref);
  $this->objpdf->text(30, $xlin - 8, $this->municpref);
  $this->objpdf->text(30, $xlin - 5, $this->telefpref);
  $this->objpdf->text(30, $xlin - 2, $this->emailpref);

  $this->objpdf->Roundedrect($xcol, $xlin + 1, $xcol + 98 + 100, 15, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 2, $xlin + 5, 'Laboratório ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 24, $xlin + 5, ':  ' . $this->Rlaboratorio);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 2, $xlin + 9, 'CGS ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 24, $xlin + 9, ':  ' . $this->Rpaciente);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 2, $xlin + 13, 'Departamento ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 24, $xlin + 13, ':  ' . $this->Rdepart);
  $this->objpdf->Setfont('Arial', 'b', 8);


  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 110, $xlin + 6, 'Hora ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 128, $xlin + 6, ':  ' . $this->Rhora);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 140, $xlin + 6, 'Data ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 150, $xlin + 6, ':  ' . db_formatar($this->Rdata, "d"));
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 110, $xlin + 10, 'Profissional ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 128, $xlin + 10, ': ' . $this->Rmedico);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 110, $xlin + 14, 'Atendente ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 128, $xlin + 14, ':  ' . $this->Rusuario . " " . $this->Rnomeusuario);
  $this->objpdf->Setfont('Arial', '', 6);

  $this->objpdf->Roundedrect($xcol, $xlin + 17, $xcol + 98 + 100, 10, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 2, $xlin + 21, 'Responsável ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 22, $xlin + 21, ':  ' . $this->Rresponsavel);
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 130, $xlin + 21, 'Contato ');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 150, $xlin + 21, ':  ' . $this->Rcontato);
  $this->objpdf->Setfont('Arial', 'b', 8);

  $this->objpdf->Roundedrect($xcol, $xlin + 28, 202, 75, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol, $xlin + 35, 'CÓDIGO');
  $this->objpdf->text($xcol + 20, $xlin + 35, 'SETOR');
  $this->objpdf->text($xcol + 70, $xlin + 35, 'EXAME');
  $this->objpdf->text($xcol + 149, $xlin + 35, 'COLETA');
  $this->objpdf->text($xcol + 169, $xlin + 35, 'HORA');
  $this->objpdf->text($xcol + 180, $xlin + 35, 'ENTREGA');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->sety($xlin + 30);
  
  $yy = $this->objpdf->gety();

  for ($ii = 0; $ii < $this->linhasdositens; $ii++) {

    $this->objpdf->setx($xcol + 3);
    $this->objpdf->cell(16, 5, trim(pg_result($this->recorddositens, $ii, $this->rcodrequisicao)), 0, 0, "L", 0);
    $this->objpdf->cell(50, 5, substr(trim(pg_result($this->recorddositens, $ii, $this->rsetor)), 0, 40), 0, 0, "L", 0);
    $this->objpdf->cell(80, 5, substr(pg_result($this->recorddositens, $ii, $this->rexame), 0, 40), 0, 0, "L", 0);
    $this->objpdf->cell(17, 5, (db_formatar(pg_result($this->recorddositens, $ii, $this->rdata), 'd')), 0, 0, "L", 0);
    $this->objpdf->cell(15, 5, trim(pg_result($this->recorddositens, $ii, $this->rhora)), 0, 0, "C", 0);
    $this->objpdf->cell(15, 5, (db_formatar(pg_result($this->recorddositens, $ii, $this->rentrega), 'd')), 0, 1, "C", 0);

    $this->objpdf->Roundedrect($xcol, $xlin + 28, 202, 75, 2, 'DF', '1234');
    $this->objpdf->Roundedrect($xcol, $xlin + 28, 202, 75, 2, 'DF', '1234');
    $this->objpdf->Setfont('Arial', 'b', 8);
    $this->objpdf->text($xcol, $xlin + 35, 'CÓDIGO');
    $this->objpdf->text($xcol + 20, $xlin + 35, 'SETOR');
    $this->objpdf->text($xcol + 70, $xlin + 35, 'EXAME');
    $this->objpdf->text($xcol + 149, $xlin + 35, 'COLETA');
    $this->objpdf->text($xcol + 169, $xlin + 35, 'HORA');
    $this->objpdf->text($xcol + 180, $xlin + 35, 'ENTREGA');
    $this->objpdf->Setfont('Arial', '', 6);
    $this->objpdf->sety($xlin + 35);
    
    $yy = $this->objpdf->gety();

    for ($iContadorItens = 0; $iContadorItens < $this->linhasdositens; $iContadorItens++) {

      $this->objpdf->setx($xcol + 3);
      $this->objpdf->cell(16, 4, trim(pg_result($this->recorddositens, $iContadorItens, $this->rcodrequisicao)), 0, 0, "L", 0);
      $this->objpdf->cell(50, 4, substr(trim(pg_result($this->recorddositens, $iContadorItens, $this->rsetor)), 0, 40), 0, 0, "L", 0);
      $this->objpdf->cell(80, 4, substr(pg_result($this->recorddositens, $iContadorItens, $this->rexame), 0, 40), 0, 0, "L", 0);
      $this->objpdf->cell(17, 4, (db_formatar(pg_result($this->recorddositens, $iContadorItens, $this->rdata), 'd')), 0, 0, "L", 0);
      $this->objpdf->cell(15, 4, trim(pg_result($this->recorddositens, $iContadorItens, $this->rhora)), 0, 0, "C", 0);
      $this->objpdf->cell(15, 4, (db_formatar(pg_result($this->recorddositens, $iContadorItens, $this->rentrega), 'd')), 0, 1, "C", 0);

      if (($iContadorItens + 1) == $quant_itens) {
        break;
      }
    }
  }

  if (trim($this->Rrequisito) != '') {

    $Rrequisito = substr(trim($this->Rrequisito), 0, 110);
    $this->objpdf->multicell(200, 4, str_replace("\n", '', ($Rrequisito)));
  }

  $this->objpdf->Roundedrect($xcol, $xlin + 106, $xcol + 105, 19, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 2, $xlin + 110, 'Obs:');
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->text($xcol + 134, $xlin + 120, strtoupper($this->municpref) . ', ' . substr($this->emissao, 8, 2) . ' DE ' . strtoupper(db_mes(substr($this->emissao, 5, 2))) . ' DE ' . substr($this->emissao, 0, 4) . '.');
  $this->objpdf->line($xcol + 130, $xlin + 110, $xcol + 195, $xlin + 110);
  $this->objpdf->text($xcol + 152, $xlin + 114, 'RECEBEDOR');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->sety($xlin + 110);
  $this->objpdf->setx($xcol + 1);
  $this->objpdf->multicell(115, 3, substr($this->Rresumo, 0, 450), 0, "L");

  $xlin = 20;

  if (($i % 2 ) == 0) {
    $xlin = 169;
  }
}
