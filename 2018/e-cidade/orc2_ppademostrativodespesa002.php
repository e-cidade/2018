<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/ppadespesa.model.php"));
require_once(modification("model/ppaVersao.model.php"));

$oGet       = db_utils::postMemory($_GET);
$oPPA       = new ppaDespesa($oGet->ppaversao);
$oPPA->setInstituicoes($oGet->sListaInstit);
$oDaoPPALei = db_utils::getDao("ppaversao");
$oPPAVersao     = new ppaVersao($oGet->ppaversao);
/**
 * Carregamos o quadro da estimativa da despesa, trazendo os valores agrupados pelo nivel
 * escolhido pelo usário.
 */
$aNiveis = array(
  1 => "Orgão",
  2 => "Unidade",
  3 => "Função",
  4 => "Subfunção",
  5 => "Programa",
  6 => "Projeto/Atividade",
  7 => "Elemento",
  8 => "Recurso",
);
try {

  if ($oGet->anofin > 2012) {
    $_SESSION["DB_use_pcasp"] = 't';
  }

  $aDespesas    = $oPPA->getQuadroEstimativas("", $oGet->nivel);

  if ($oGet->anofin > 2012) {
    $_SESSION["DB_use_pcasp"] = 'f';
  }

} catch (Exception $eException ) {
  die( $eException->getMessage());
}

$aAno                  = array();
$aValorTotalBase       = array();
$aValorTotalEstimativa = array();
$aAnoBase              = array();
$iAnoInicio            = $oGet->anoini - ppa::ANOS_PREVISAO_CALCULO;
$nValorTotalMedia      = 0;

for ($iInd = $iAnoInicio; $iInd < $oGet->anoini; $iInd++) {
  $aAnoBase[] = $iInd;
}
for ( $iInd = $oGet->anoini; $iInd <= $oGet->anofin; $iInd++ ) {
  $aAno[] = $iInd;
}
$sSqlPPALei  = $oDaoPPALei->sql_query($oGet->ppaversao);
$rsPPALei    = $oDaoPPALei->sql_record($sSqlPPALei);
$oLeiPPA     = db_utils::fieldsMemory($rsPPALei, 0);
$head2 = "Demonstrativo das Projeções da Despesa";
$head3 = "PPA - {$oGet->anoini} - {$oGet->anofin}";
$head4 = "Lei {$oLeiPPA->o01_numerolei} - {$oLeiPPA->o01_descricao}";
$head5 = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
$head6 = "Nível: ".$aNiveis[$oGet->nivel];
$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
$pdf->setfillcolor(235);
cabecalho($pdf, $aAnoBase, $aNiveis[$oGet->nivel], $aAno);

$alt      = 5;
$iTamanhoCelulaCodigo = 15;
$iTamanhoCelulaDescr  = 80;
if ($oGet->nivel == 7) {

  $iTamanhoCelulaCodigo = 25;
  $iTamanhoCelulaDescr  = 70;

}
foreach ($aDespesas as $oDespesa) {

  $nValorLinha = 0;
  foreach ($oDespesa->aEstimativas as $iAno => $nValorEstimado) {
    $nValorLinha += $nValorEstimado;
  }
  if (round($nValorLinha, 2) == 0) {
    continue;
  }
  if ($pdf->GetY() > $pdf->h - 30) {
    cabecalho($pdf, $aAnoBase, $aNiveis[$oGet->nivel], $aAno);
  }
  $pdf->setfont('arial','',7);

  $pdf->cell($iTamanhoCelulaCodigo , $alt, $oDespesa->iCodigo." - " , "TB", 0, "R", 0);
  $pdf->cell($iTamanhoCelulaDescr, $alt, $oDespesa->iEstrutural, "TB", 0, "L", 0);
  foreach ($oDespesa->aBaseCalculo as $iAno => $nValorBase) {

    $pdf->cell(20 , $alt, db_formatar($nValorBase, "f"), "TBL", 0, "R", 0);
    if (isset($aValorTotalBase[$iAno])) {
      $aValorTotalBase[$iAno] += $nValorBase;
    } else {
      $aValorTotalBase[$iAno] = $nValorBase;
    }
  }
  $pdf->cell(26 , $alt, db_formatar($oDespesa->nMediaBase, "f"), "TBL", 0, "R", 0);
  $nValorTotalMedia += $oDespesa->nMediaBase;
  foreach ($oDespesa->aEstimativas as $iAno => $nValorEstimado) {

    $pdf->cell(20 , $alt, db_formatar($nValorEstimado, "f"), "TBL", 0, "R", 0);
    if (isset($aValorTotalEstimativa[$iAno])) {
      $aValorTotalEstimativa[$iAno] += $nValorEstimado;
    } else {
      $aValorTotalEstimativa[$iAno] = $nValorEstimado;
    }

  }
  $pdf->Ln();
}

/**
 * Totalizadores
 */
$pdf->setfont('arial','b',7);
$pdf->cell(175 , $alt, "Totais", "TB", 0, "R", 0);
$pdf->cell(26 , $alt, db_formatar($nValorTotalMedia, "f"), "TBL", 0, "R", 0);
for ($iInd = 0; $iInd < count($aAno); $iInd++) {
  @$pdf->cell(20 , $alt, db_formatar($aValorTotalEstimativa[$aAno[$iInd]],"f") , "TBL", 0, "C", 0);
}
$pdf->Output();


function cabecalho($pdf, $aAnosBase, $sLabel, $aAnosProj) {

  $alt = 5;
  $pdf->setfont('arial','B',8);
  $pdf->addpage();

  $pdf->cell(95, $alt, "Dados da Despesa"    , "TBR", 0, "C", 1);
  $pdf->cell(80 , $alt, "Valores Liquidados" , "TBL", 0, "C", 1);
  $pdf->cell(26 , $alt, "Média dos quatro "  , "TL", 0, "C", 1);
  $pdf->cell(80 , $alt, "Valores Projetados" , "TBL", 1, "C", 1);
  $pdf->cell(95 , $alt, $sLabel , "TBR", 0, "C", 1);
  for ($iInd = 0; $iInd < count($aAnosBase); $iInd++) {
    $pdf->cell(20 , $alt, $aAnosBase[$iInd] , "TBL", 0, "C", 1);
  }
  $pdf->cell(26 , $alt, " últimos exercícios", "BL", 0, "C", 1);
  for ($iInd = 0; $iInd < count($aAnosProj); $iInd++) {
    $pdf->cell(20 , $alt, $aAnosProj[$iInd] , "TBL", 0, "C", 1);
  }
  $pdf->Ln();
  $iGetYCabecalho = $pdf->GetY();

}
