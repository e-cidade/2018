<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
$sCaminhoMensagem = "patrimonial.compras.com2_relatorioatasvigentes.";

$oParametros  = db_utils::postMemory($_GET);
$oDataInicial = new DBDate($oParametros->dtInicial);
$oDataFinal   = new DBDate($oParametros->dtFinal);
if (empty($oParametros->dtInicial) || empty($oParametros->dtFinal)) {

  db_redireciona('db_erros.php?db_erro='._M("{$sCaminhoMensagem}vigencia_nao_informada"));
  exit;
}

$oDaoCompilacoes = new cl_solicitaregistropreco();
$sCampos         = "distinct pc10_numero, pc10_resumo, pc54_datainicio, pc54_datatermino";

$sWhere  = "pc10_instit = ".db_getsession("DB_instit");
$sWhere .= " and pc10_solicitacaotipo = 6";
$sWhere .= " and ('{$oDataInicial->getDate()}'::date  -' 1 day'::interval, '{$oDataFinal->getDate()}'::date +' 1 day'::interval) ";
$sWhere .= "   overlaps (pc54_datainicio, pc54_datatermino)";
$sWhere .= " and pc67_solicita is null";
$sWhere .= " and l20_licsituacao = 1";

if (!empty($oParametros->materiais)) {
  $sWhere .= " and pc16_codmater in ({$oParametros->materiais}) ";
}

if (!empty($oParametros->fornecedor)) {
  $sWhere .= " and pc21_numcgm = {$oParametros->fornecedor} and pc24_pontuacao = 1 ";
}

$aCompilacoes    = array();
$sSqlCompilacoes = $oDaoCompilacoes->sql_query_registro_licitacao(null, $sCampos, "pc10_numero", $sWhere);
$rsCompilacoes   = $oDaoCompilacoes->sql_record($sSqlCompilacoes);
if (!$rsCompilacoes || $oDaoCompilacoes->numrows == 0) {

  $oParametros = (Object) array('inicio' => $oDataInicial->getDate(DBDate::DATA_PTBR),
                                'fim' => $oDataFinal->getDate(DBDate::DATA_PTBR)
                               );
  $sMensagem  = _M("{$sCaminhoMensagem}sem_atas_no_periodo", $oParametros);

  db_redireciona("db_erros.php?db_erro={$sMensagem}");
  exit;
}
$iTotalLinhas = $oDaoCompilacoes->numrows;
for ($iRegistro = 0; $iRegistro < $iTotalLinhas; $iRegistro++) {

  $oDadosRegistro                   = db_utils::fieldsMemory($rsCompilacoes, $iRegistro);
  $oDadosRegistro->pc54_datainicio  = new DBDate($oDadosRegistro->pc54_datainicio);
  $oDadosRegistro->pc54_datatermino = new DBDate($oDadosRegistro->pc54_datatermino);
  $aCompilacoes[]                   = $oDadosRegistro;
}

$oPdf = new PDF("P", "mm", "A4");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$head2 = "Emiss�o de Atas";
$head3 = "Data de Vig�ncia: ".$oDataInicial->getDate(DBDate::DATA_PTBR)." at� ".$oDataFinal->getDate(DBDate::DATA_PTBR);

$lEscreveCabecalho      = true;
$iAlturaLinha           = 4;
$iAlturaLinhaObservacao = 2.8;
$sFonte                 = 'arial';
$oPdf->AddPage();
foreach ($aCompilacoes as $oCompilacao) {

  $sTexto = $oCompilacao->pc10_resumo;
  $oPdf->SetFont($sFonte, '', 6);
  $iALturaCelula = $oPdf->NbLines(130, $sTexto) * $iAlturaLinhaObservacao;
  if ($iALturaCelula < $iAlturaLinha) {

    $iALturaCelula          = $iAlturaLinha;
    $iAlturaLinhaObservacao = $iAlturaLinha;
  }
  if ($oPdf->h - 15 < $oPdf->getY() + $iALturaCelula || $lEscreveCabecalho) {

    if (!$lEscreveCabecalho) {
      $oPdf->AddPage();

    }
    $oPdf->SetFont($sFonte, 'b', 6);
    $oPdf->Cell(20, $iAlturaLinha, "Compila��o", 1, 0, 'C', 1);
    $oPdf->Cell(130, $iAlturaLinha, "Resumo", 1, 0, 'C', 1);
    $oPdf->Cell(40, $iAlturaLinha, "Data de Vig�ncia", 1, 1, 'C', 1);
    $oPdf->SetFont($sFonte, '', 6);
    $lEscreveCabecalho = false;
  }

  $sDataVigencia  = $oCompilacao->pc54_datainicio->getDate(DBDate::DATA_PTBR)." a ";
  $sDataVigencia .= $oCompilacao->pc54_datatermino->getDate(DBDate::DATA_PTBR);
  $oPdf->Cell(20, $iALturaCelula, $oCompilacao->pc10_numero, 1, 0, 'C');
  $iAlturaInicialLinha = $oPdf->getY();
  $oPdf->MultiCell(130, $iAlturaLinhaObservacao , $sTexto, "TLR", 'L');
  $iAlturaFinal = $oPdf->getY();
  $oPdf->SetXY(160, $iAlturaInicialLinha);
  $oPdf->SetFont($sFonte, '', 7);
  $oPdf->Cell(40, $iALturaCelula, $sDataVigencia, 1, 1, 'C');

  $iAlturaDesenhoLinha = $oPdf->GetY();
  if ($iAlturaDesenhoLinha < $iAlturaFinal) {
    $iAlturaDesenhoLinha = $iAlturaFinal;
  }
  $oPdf->Line(10, $iAlturaDesenhoLinha, 200, $iAlturaDesenhoLinha);
  $iAlturaLinhaObservacao = 2.8;
}
$oPdf->Output();