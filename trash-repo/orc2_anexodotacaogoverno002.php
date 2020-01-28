<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");
db_app::import("contabilidade.relatorios.AnexoQuadroDotacaoGoverno");
db_app::import("relatorioContabil");
$oGet         = db_utils::postMemory($_GET);
$iAnoUso      = db_getsession('DB_anousu');
$sInstit      = str_replace("-", ",", $oGet->sInstit);
$oAnexoQuadro = new AnexoQuadroDotacaoGoverno($iAnoUso, $oGet->iCodRel, $oGet->iPeriodo);
$oAnexoQuadro->setOrigemFase($oGet->iOrigemFase);
$oAnexoQuadro->setInstituicoes($sInstit);
$oDadosAnexo = $oAnexoQuadro->getDados();

$aFases         = array(1 => "Orçamento", 
                        2 => "Empenhado", 
                        3 => "Liquidado", 
                        4 => "Pago");
$rsInstituicoes = pg_exec("select codigo, nomeinst, nomeinstabrev 
                             from db_config 
                            where codigo in ({$sInstit}) ");
$sDescricaoInstitucoes = '';
$sVirg                 = '';
$lAbrevia              = false;
for ($iInstit = 0; $iInstit < pg_num_rows($rsInstituicoes); $iInstit++) {
  
  $oInstit = db_utils::fieldsmemory($rsInstituicoes, $iInstit);
  if (strlen(trim($oInstit->nomeinstabrev)) > 0) {
    
    $sDescricaoInstitucoes .= $sVirg.$oInstit->nomeinstabrev;
    $lAbrevia               = true;
  } else {
    $sDescricaoInstitucoes .= $sVirg.$oInstit->nomeinst;
  }
  $sVirg = ', ';
}
if ($lAbrevia) {
  
  if (strlen($sDescricaoInstitucoes) > 42) {
    $sDescricaoInstitucoes = substr($sDescricaoInstitucoes, 0, 150);
  }
}

/**
 * Busca o periodo
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oAnexoQuadro->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $oGet->iPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}
$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "QUADRO DAS DOTAÇÕES POR ÓRGÃO DO GOVERNO E DA ADMINISTRAÇÃO";
$head3 = "Lei Orçamentária Anual de ".db_getsession("DB_anousu");
$head4 = "Instituições: {$sDescricaoInstitucoes}";

/**
 * Se a Origem/Fase for diferente de Orçamento
 */
if ($oGet->iOrigemFase != 1) {

  $head5 = "Valor: {$aFases[$oGet->iOrigemFase]}";
  $head6 = "Período: JANEIRO a {$sDescricaoPeriodo}";
}

if ($oGet->lConsolidado == 1) {
  $head2 .= " - Consolidado";
}
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$iAltura       = 4;
$lPrimeiroLaco = true;
$nTotalCorrente = 0;
$nTotalCapital  = 0;

foreach ($oDadosAnexo as $oDado) {
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    montaCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }
  
  if ($oDado->total == 0) {
    continue;
  }
  
  $oPdf->setfont('Arial', 'B', 6);
  $oPdf->cell(100, $iAltura, trim($oDado->descr), "R", 0, "L");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->despesacorrente, 'f'), "LR", 0, "R");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->despesacapital, 'f'), "LR", 0, "R");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->total, 'f'), "L", 1, "R");

  foreach ($oDado->unidades as $oUnidade) {
    
    if ($oUnidade->total == 0) {
      continue;
    }
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell(100, $iAltura, "    ".trim($oUnidade->descr), "R", 0, "L");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->despesacorrente, 'f'), "LR", 0, "R");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->despesacapital, 'f'), "LR", 0, "R");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->total, 'f'), "L", 1, "R");
  }
  
  $nTotalCorrente += $oDado->despesacorrente;
  $nTotalCapital  += $oDado->despesacapital;
}

$oPdf->setfont('Arial', 'B', 6);
$oPdf->cell(100, $iAltura, "Total Geral:", "TBR", 0, "R");
$oPdf->cell(30, $iAltura, db_formatar($nTotalCorrente, 'f'), 1, 0, "R");
$oPdf->cell(30, $iAltura, db_formatar($nTotalCapital, 'f'), 1, 0, "R");
$oPdf->cell(30, $iAltura, db_formatar(($nTotalCorrente+$nTotalCapital), 'f'), "LTB", 1, "R");
$oPdf->ln();
$oAnexoQuadro->getNotaExplicativa($oPdf, $oGet->iPeriodo);
$oPdf->Output();

function montaCabecalho ($oPdf, $iAltura) {

  $oPdf->addPage();
  $oPdf->setfont('Arial', 'b', 8);
  $oPdf->cell(100, $iAltura+2, "Especificação", "TRB", 0, "C");
  $oPdf->cell(30, $iAltura+2, "Despesa Corrente", 1, 0, "C");
  $oPdf->cell(30, $iAltura+2, "Despesa Capital", 1, 0, "C");
  $oPdf->cell(30, $iAltura+2, "Total", "TBL", 1, "C");
}
?>