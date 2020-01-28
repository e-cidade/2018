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
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/ppaReceita.model.php"));
require_once(modification("model/ppa.model.php"));
require_once(modification("model/ppaVersao.model.php"));

$oGet      = db_utils::postMemory($_GET);

if (empty($oGet->ppaversao)|| empty($oGet->ppalei)) {

  $sErroMsg = "Ocorreu uma falha ao gerar o relatório com os dados enviados.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
  exit;
}

$oPPA      = new ppa($oGet->ppalei, 1, $oGet->ppaversao);
$oPPA->setInstituicoes($oGet->sInstit);
$oPPAVersao     = new ppaVersao($oGet->ppaversao);
$aRecursos = array();
if (isset($oGet->sRecursos)) {
  $aRecursos = explode(",", $oGet->sRecursos);
}
try {
  $aReceitas    = $oPPA->getQuadroEstimativas($oGet->estrut, $aRecursos);
} catch (Exception $eException ) {
  //$lImprimeReceitaCorrente = false;
}
/**
 * Remontamos o quadro, agrupao por recurso
 */
if ($oGet->agrupaporrecurso == 1) {

  $oGrupoRecursos = $aReceitas;
  $aReceitas       = array();
  foreach ($oGrupoRecursos as $oRecurso) {

    if ($oRecurso->iReduz != "") {
      if (isset($aReceitas[$oRecurso->iRecurso])) {

        foreach ($oRecurso->aBaseCalculo as $iAno => $nValor) {
          $aReceitas[$oRecurso->iRecurso]->aBaseCalculo[$iAno] += $nValor;
        }
        foreach ($oRecurso->aEstimativas as $iAno => $nValor) {
          $aReceitas[$oRecurso->iRecurso]->aEstimativas[$iAno] += $nValor;
        }
        $aReceitas[$oRecurso->iRecurso]->nMediaBase  += $oRecurso->nMediaBase;
      } else {

        $oReceitaNova = new stdClass();
        $oReceitaNova->iEstrutural = $oRecurso->iRecurso;
        $oReceitaNova->sDescricao  = $oRecurso->sDescricaoRecurso;
        $oReceitaNova->nMediaBase  = $oRecurso->nMediaBase;
        $oReceitaNova->iRecurso    = $oRecurso->iRecurso;
        $oReceitaNova->iReduz      = "";
        foreach ($oRecurso->aBaseCalculo as $iAno => $nValor) {
          $oReceitaNova->aBaseCalculo[$iAno] = $nValor;
        }
        foreach ($oRecurso->aEstimativas as $iAno => $nValor) {
          $oReceitaNova->aEstimativas[$iAno] = $nValor;
        }
        $aReceitas[$oRecurso->iRecurso] = $oReceitaNova;
      }
    }
  }
}
$aAno       = array();
$aAnoBase   = array();
$iAnoInicio =  $oGet->anoini - ppa::ANOS_PREVISAO_CALCULO;
for ($iInd = $iAnoInicio; $iInd < $oGet->anoini; $iInd++) {
  $aAnoBase[] = $iInd;
}
for ( $iInd = $oGet->anoini; $iInd <= $oGet->anofin; $iInd++ ) {
  $aAno[] = $iInd;
}
$iAnoDaMedia = $oGet->anoini -1;
$iAno1 = $aAno[0];
$iAno2 = $aAno[1];
$iAno3 = $aAno[2];
$iAno4 = $aAno[3];

$nTotalAno1 = 0;
$nTotalAno2 = 0;
$nTotalAno3 = 0;
$nTotalAno4 = 0;

$oDaoPPAVersao = db_utils::getDao("ppaversao");
$sSqlPPALei    = $oDaoPPAVersao->sql_query($oGet->ppaversao);
$rsPPALei      = $oDaoPPAVersao->sql_record($sSqlPPALei);

$head4 = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
if ($oDaoPPAVersao->numrows > 0 ) {

  /**
   * Caso encontrou resultado vai colocar o valor no head4 e o head4 passa a ser o head5
   * na ordem de cabeçalhos
   */
  $oLeiPPA = db_utils::fieldsMemory($rsPPALei, 0);
  $head5   = $head4;
  $head4   = "Lei {$oLeiPPA->o01_numerolei} - {$oLeiPPA->o01_descricao}";
}

$head2 = "Demonstrativo das Projeções da Receita";
$head3 = "PPA - {$oGet->anoini} - {$oGet->anofin}";
$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
cabecalho($pdf, $aAnoBase, $iAnoDaMedia, $aAno);

$alt = 5;

//Array para armazenar a soma total de cada coluna
$aTotalizadores = array();
for($iIndice = 0; $iIndice <= 8; $iIndice++) {
  $aTotalizadores[] = 0;
}
if ($oGet->agrupaporrecurso == 1) {
  sort($aReceitas);
}

foreach ($aReceitas as $oReceita) {

  /*
   * Validamos as estimativas
   * caso todas estejam com valor 0, nao mostramos a linha
   */
  $iAnosEstimativas  = count($oReceita->aEstimativas);
  $iEstimativasNulas = 0;

  foreach ($oReceita->aEstimativas as $nValorEstimado) {

    if ($nValorEstimado == 0){
      $iEstimativasNulas++;
    }
  }
  if ($iAnosEstimativas == $iEstimativasNulas) {
    continue;
  }
  if ($pdf->GetY() > $pdf->h - 30) {
    cabecalho($pdf, $aAnoBase, $iAnoDaMedia, $aAno);
  }
  $pdf->setfont('arial','',7);
  if ($oReceita->iReduz == "" && $oGet->agrupaporrecurso == 2) {
    $pdf->setfont('arial','b',7);
  }
  $pdf->cell(25 , $alt, $oReceita->iEstrutural , "TBR", 0, "L", 0);
  $pdf->cell(60 , $alt, substr(urldecode($oReceita->sDescricao),0,38), "TBL", 0, "L", 0);
  $pdf->cell(10 , $alt, $oReceita->iRecurso    , "TBL", 0, "R", 0);
  $iIndice = 0;
  foreach ($oReceita->aBaseCalculo as $nValorBase) {
    $pdf->cell(20 , $alt, db_formatar($nValorBase, "f"), "TBL", 0, "R", 0);
    if ($oGet->agrupaporrecurso == 1) {
      $aTotalizadores[$iIndice] += $nValorBase;
    } else if	($oGet->agrupaporrecurso == 2 &&
      ($oReceita->iEstrutural == 400000000000000 || $oReceita->iEstrutural == 900000000000000)){
        $aTotalizadores[$iIndice] += $nValorBase;
      }

    $iIndice++;
  }
  $pdf->cell(26 , $alt, db_formatar($oReceita->nMediaBase, "f"), "TBL", 0, "R", 0);
  if ($oGet->agrupaporrecurso == 1) {
    $aTotalizadores[$iIndice] += $oReceita->nMediaBase;
  } else if ($oGet->agrupaporrecurso == 2 &&
    ($oReceita->iEstrutural == 400000000000000 || $oReceita->iEstrutural == 900000000000000)) {
      $aTotalizadores[$iIndice] += $oReceita->nMediaBase;
    }
  //$aTotalizadores[$iIndice] += $oReceita->nMediaBase;
  $iIndice++;

  foreach ($oReceita->aEstimativas as $nValorEstimado) {
    $pdf->cell(20 , $alt, db_formatar($nValorEstimado, "f"), "TBL", 0, "R", 0);
    //$aTotalizadores[$iIndice] += $nValorEstimado;
    if ($oGet->agrupaporrecurso == 1) {
      $aTotalizadores[$iIndice] += $nValorEstimado;
    } else if	($oGet->agrupaporrecurso == 2 &&
      ($oReceita->iEstrutural == 400000000000000 || $oReceita->iEstrutural == 900000000000000)){

	    	if(isset($aTotalizadores[$iIndice])){
	        $aTotalizadores[$iIndice] += $nValorEstimado;
	    	}
      }
    $iIndice++;
  }
  $pdf->Ln();
}
$pdf->cell(30 , $alt, ""         , "TB", 0, "R", 0);
$pdf->cell(65 , $alt, ""          , "TB", 0, "C", 0);
$pdf->cell(106 , $alt, "Total"            , "TB", 0, "R", 0);
$pdf->cell(20 , $alt, db_formatar($aTotalizadores[5], "f") , "TBL", 0, "R", 0);
$pdf->cell(20 , $alt, db_formatar($aTotalizadores[6], "f") , "TBL", 0, "R", 0);
$pdf->cell(20 , $alt, db_formatar($aTotalizadores[7], "f") , "TBL", 0, "R", 0);
$pdf->cell(20 , $alt, db_formatar($aTotalizadores[8], "f") , "TBL", 0, "R", 0);

$pdf->Output();

function cabecalho($pdf, $aAnosBase, $sLabelMedia, $aAnosProj) {

  $alt = 5;
  $pdf->setfont('arial','B',8);
  $pdf->addpage();

  $pdf->cell(95, $alt, "Dados da Receita"   , "TBR", 0, "C", 1);
  $pdf->cell(80 , $alt, "Valores Arrecadados", "TBL", 0, "C", 1);
  $pdf->cell(26 , $alt, "Média dos quatro "        , "TL", 0, "C", 1);
  $pdf->cell(80 , $alt, "Valores Projetados" , "TBL", 1, "C", 1);
  $pdf->cell(25 , $alt, "Estrutural"         , "TBR", 0, "C", 1);
  $pdf->cell(60 , $alt, "Descrição"          , "TBL", 0, "C", 1);
  $pdf->cell(10 , $alt, "Rec."            , "TBL", 0, "C", 1);
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