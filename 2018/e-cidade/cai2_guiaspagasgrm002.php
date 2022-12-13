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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));

$oGet                      = db_utils::postMemory($_GET);
$oUnidadeGestoraRepository = new \ECidade\Tributario\Grm\Repository\UnidadeGestora();
$oUnidadeGestora           = $oUnidadeGestoraRepository->getById($oGet->unidade_gestora);

$oTipoRecolhimentoRepository = new \ECidade\Tributario\Grm\Repository\TipoRecolhimento();

$oGuiaRecolhimentoRepository = new \ECidade\Tributario\Grm\Repository\Recibo();
$aWhere       = array();
$aTextoFiltro = array();

$aEspecieIngresso = array(1 => 'Receita', 2 => 'DDO', 3=> 'Estorno de Despesa');
if (!empty($oGet->tipo_recolhimento)) {

  $aWhere[] = 'k174_tiporecolhimento = '.(int)$oGet->tipo_recolhimento;
  $oTipoRecolhimento = $oTipoRecolhimentoRepository->getTipoRecolhimento((int)$oGet->tipo_recolhimento);
  $aTextoFiltro[] = "Tipo de Recolhimento: {$oTipoRecolhimento->getCodigoRecolhimento()} - {$oTipoRecolhimento->getNome()}";
}

if (!empty($oGet->especie_ingresso)) {

  $aWhere[]       = 'k172_especieingresso = '.(int)$oGet->especie_ingresso;
  $aTextoFiltro[] = "Espécie de Ingresso: {$aEspecieIngresso[(int)$oGet->especie_ingresso]}";
}
$sFiltroData = '';
if (!empty($oGet->data_inicial)) {

  $oDataInicial = new DBDate($oGet->data_inicial);
  $aWhere[] = "k00_dtpaga >= '".$oDataInicial->getDate()."'";
  $sFiltroData .= 'De '.$oGet->data_inicial;
}

if (!empty($oGet->data_final)) {

  $oDataFinal = new DBDate($oGet->data_final);
  $aWhere[]   = "k00_dtpaga <= '".$oDataFinal->getDate()."'";
  $sFiltroData .= ' Até '.$oGet->data_final;
}
if (!empty($sFiltroData)) {
  $aTextoFiltro[] = $sFiltroData;
}
$sWhere      = implode(" and ", $aWhere);
$aPagamentos = $oGuiaRecolhimentoRepository->getRecibosPagosDaUnidadeGestora($oUnidadeGestora, $sWhere);
if (count($aPagamentos) == 0) {

  db_redireciona('db_erros.php?sMensagemErro=Nenhuma guia encontrada para os filtros informados');
  exit;
}

$aDados = array();
foreach ($aPagamentos as $oPagamento) {

  $iCodigoRecolhimento = $oPagamento->getTipoRecolhimento()->getCodigo();
  if (empty($aDados[$iCodigoRecolhimento])) {

    $oTipoRecolhimento         = new stdClass();
    $oTipoRecolhimento->codigo = $oPagamento->getTipoRecolhimento()->getCodigoRecolhimento();
    $oTipoRecolhimento->nome   = $oPagamento->getTipoRecolhimento()->getNome();
    $oTipoRecolhimento->guias  = array();
    $aDados[$iCodigoRecolhimento] = $oTipoRecolhimento;
  }
  $oTipoRecolhimento = $aDados[$iCodigoRecolhimento];

  $oCgm = $oPagamento->getCgm();
  $cidadao = $oPagamento->getCidadao();

  $oGuia                      = new \stdClass();
  $oGuia->cgm                 = $oCgm->getCodigo();
  $oGuia->nome                = $oCgm->getNome();
  $oGuia->cpf_cnpj            = $oCgm->isFisico() ? db_formatar($oCgm->getCpf(), 'CPF') : db_formatar($oCgm->getCnpj(), 'cnpj');

  if (!empty($cidadao)) {

    $oGuia->cgm = $cidadao->getCodigo();
    $oGuia->nome = $cidadao->getNome();
    $oGuia->cpf_cnpj = strlen($cidadao->getCpfCnpj()) === 11 ? db_formatar($cidadao->getCpfCnpj(), 'CPF') : db_formatar($cidadao->getCpfCnpj(), 'cnpj');
  }

  $oGuia->data_pagamento      = $oPagamento->getDataPagamento()->getDate(DBDate::DATA_PTBR);
  $oGuia->competencia         = $oPagamento->getCompetencia();
  $oGuia->referencia          = $oPagamento->getNumeroReferencia();
  $oGuia->valor               = $oPagamento->getValor();
  $oGuia->desconto            = $oPagamento->getValorDesconto();
  $oGuia->multa               = $oPagamento->getValorMulta();
  $oGuia->juros               = $oPagamento->getValorJuros();
  $oGuia->outros_acrescimos   = $oPagamento->getValorOutrosAcrescimento();
  $oGuia->total               = $oPagamento->getValorTotal();
  $oGuia->atributos           = $oPagamento->getAtributos();
  $oTipoRecolhimento->guias[] = $oGuia;
}
$head1 ='RELATÓRIO DE GUIA DE RECOLHIMENTO MUNICIPAL';
$head2 ='UNIDADE GESTORA: '.$oUnidadeGestora->getCodigo()." - ".$oUnidadeGestora->getNome();
$iHead = 3;

foreach ($aTextoFiltro as $sTextoFiltro) {
  ${"head$iHead"} = $sTextoFiltro;
  $iHead++;
}
$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$lAdicionaPagina = true;
$iAlturalinha = 4;
$iFonte       = 6;
foreach ($aDados as $oTipoRecolhimento) {

  foreach ($oTipoRecolhimento->guias as $oGuia) {

    $iTotalAlturaCamposDinamicos = count($oGuia->atributos) * $iAlturalinha;
    if ($lAdicionaPagina || $pdf->GetY() + (20 + $iTotalAlturaCamposDinamicos) > $pdf->h) {

      $pdf->AddPage();
      $lAdicionaPagina = false;
      $pdf->setfont('arial', 'b', 7);
      $pdf->Cell(70, $iAlturalinha, 'Recolhimento:'.$oTipoRecolhimento->codigo." - ".$oTipoRecolhimento->nome, 0, 1, 'L');
      $pdf->Cell(18, $iAlturalinha, 'CGM/Cidadão', '1', 0, 'C');
      $pdf->Cell(65, $iAlturalinha, 'Nome', '1', 0, 'C');
      $pdf->Cell(25, $iAlturalinha, 'CPF/CNPJ', '1', 0, 'C');
      $pdf->Cell(20, $iAlturalinha, 'Pagamento', '1', 0, 'C');
      $pdf->Cell(20, $iAlturalinha, 'Num. Ref', '1', 0, 'C');
      $pdf->Cell(18, $iAlturalinha, 'Competência', '1', 0, 'C');
      $pdf->Cell(18, $iAlturalinha, 'Valor', '1', 0, 'C');
      $pdf->Cell(18, $iAlturalinha, 'Desconto', '1', 0, 'C');
      $pdf->Cell(18, $iAlturalinha, 'Multa', '1', 0, 'C');
      $pdf->Cell(20, $iAlturalinha, 'Juros', '1', 0, 'C');
      $pdf->Cell(20, $iAlturalinha, 'Outros Acres.', '1', 0, 'C');
      $pdf->Cell(20, $iAlturalinha, 'Total', 1, 1, 'C');
      $pdf->setfont('arial', '', 7);
    }

    $pdf->Cell(18, $iAlturalinha, $oGuia->cgm, 1, 0, 'C');
    $pdf->Cell(65, $iAlturalinha, $oGuia->nome, 1, 0, 'L');
    $pdf->Cell(25, $iAlturalinha, $oGuia->cpf_cnpj, 1, 0, 'L');
    $pdf->Cell(20, $iAlturalinha, $oGuia->data_pagamento, 1, 0, 'C');
    $pdf->Cell(20, $iAlturalinha, $oGuia->referencia, 1, 0, 'L');
    $pdf->Cell(18, $iAlturalinha, $oGuia->competencia, 1, 0, 'C');
    $pdf->Cell(18, $iAlturalinha, db_formatar($oGuia->valor, 'f'), 1, 0, 'R');
    $pdf->Cell(18, $iAlturalinha, db_formatar($oGuia->desconto,'f'), 1, 0, 'R');
    $pdf->Cell(18, $iAlturalinha, db_formatar($oGuia->multa,'f'), 1, 0, 'R');
    $pdf->Cell(20, $iAlturalinha, db_formatar($oGuia->juros,'f'), 1, 0, 'R');
    $pdf->Cell(20, $iAlturalinha, db_formatar($oGuia->outros_acrescimos,'f'), 1, 0, 'R');
    $pdf->Cell(20, $iAlturalinha, db_formatar($oGuia->total,'f'), 1, 1, 'R');
    /**
     * Calculamos o tamanho da celula para data tipo
     */
    $iTamanhoCelula = 10;
    foreach ($oGuia->atributos as $atributo) {

      $iTamanhoTexto = $pdf->GetStringWidth($atributo->nome);
      if ($iTamanhoTexto > $iTamanhoCelula) {
        $iTamanhoCelula = $iTamanhoTexto;
      }
    }
    foreach ($oGuia->atributos as $atributo) {

      $pdf->setfont('arial', 'b', 7);
      $pdf->Cell($iTamanhoCelula + 5, $iAlturalinha, substr($atributo->nome, 0, 40), "BR", 0, 'L');
      $pdf->setfont('arial', '', 7);
      $pdf->Cell((272 - $iTamanhoCelula), $iAlturalinha, trim($atributo->valor), "B", 1, 'L');
    }
    $pdf->ln();
  }
  $lAdicionaPagina = true;
}


$pdf->Output();