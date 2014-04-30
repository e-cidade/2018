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
require_once("fpdf151/pdf.php");
require_once("libs/db_liborcamento.php");
db_app::import("contabilidade.relatorios.AnexoProgramaObrasPrestacaoServico");
db_app::import("relatorioContabil");
$oGet         = db_utils::postMemory($_GET);
$iAnoUso      = db_getsession('DB_anousu');
$sInstit      = str_replace("-", ",", $oGet->sInstit);
$oAnexoProgObras = new AnexoProgramaObrasPrestacaoServico($iAnoUso, $oGet->iCodRel, $oGet->iPeriodo);
$oAnexoProgObras->setOrigemFase($oGet->iOrigemFase);
$oAnexoProgObras->setInstituicoes($sInstit);
$oDadosAnexo = $oAnexoProgObras->getDados();

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
$aPeriodos         = $oAnexoProgObras->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $oGet->iPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}
$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "PROGRAMA ANUAL DE TRABALHO DO GOVERNO EM TERMOS DE REALIZAÇÕES DE OBRAS E PRESTAÇÃO DE SERVIÇOS";
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
$oPdf->SetAutoPageBreak(false);
$iAltura        = 4;
$lPrimeiroLaco  = true;
$nTotalServicos = 0;
$nTotalObras    = 0;

foreach ($oDadosAnexo as $oDado) {
  
  if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco) {

    montaCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }

  if ($oDado->total == 0) {
    continue;
  }
  
  $oPdf->setfont('Arial', 'B', 6);
  $oPdf->cell(100, $iAltura, trim($oDado->descr), "R", 0, "L");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->servicos, 'f'), "LR", 0, "R");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->obras, 'f'), "LR", 0, "R");
  $oPdf->cell(30, $iAltura, db_formatar($oDado->total, 'f'), "L", 1, "R");

  foreach ($oDado->unidades as $oUnidade) {

    if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco) {

      montaCabecalho($oPdf, $iAltura);
      $lPrimeiroLaco = false;
    }
    if ($oUnidade->total == 0) {
      continue;
    }
    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell(100, $iAltura, "    ".trim($oUnidade->descr), "R", 0, "L");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->servicos, 'f'), "LR", 0, "R");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->obras, 'f'), "LR", 0, "R");
    $oPdf->cell(30, $iAltura, db_formatar($oUnidade->total, 'f'), "L", 1, "R");
    
    foreach ($oUnidade->programas as $oPrograma) {
      
      if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco) {

        montaCabecalho($oPdf, $iAltura);
        $lPrimeiroLaco = false;
      }
      if ($oPrograma->total == 0) {
        continue;
      }
      $oPdf->setfont('Arial', '', 6);
      $oPdf->cell(100, $iAltura, "        ".trim($oPrograma->descr), "R", 0, "L");
      $oPdf->cell(30, $iAltura, db_formatar($oPrograma->servicos, 'f'), "LR", 0, "R");
      $oPdf->cell(30, $iAltura, db_formatar($oPrograma->obras, 'f'), "LR", 0, "R");
      $oPdf->cell(30, $iAltura, db_formatar($oPrograma->total, 'f'), "L", 1, "R");
    }
  }

  $nTotalServicos += $oDado->servicos;
  $nTotalObras    += $oDado->obras;
}

$oPdf->setfont('Arial', 'B', 6);
$oPdf->cell(100, $iAltura, "Total Geral:", "TBR", 0, "R");
$oPdf->cell(30, $iAltura, db_formatar($nTotalServicos, 'f'), 1, 0, "R");
$oPdf->cell(30, $iAltura, db_formatar($nTotalObras, 'f'), 1, 0, "R");
$oPdf->cell(30, $iAltura, db_formatar(($nTotalServicos+$nTotalObras), 'f'), "LTB", 1, "R");
$oPdf->ln();
$oAnexoProgObras->getNotaExplicativa($oPdf, $oGet->iPeriodo);
$oPdf->Output();

/**
 * Monta o Cabeçalho do PDF
 * @param object  $oPdf
 * @param integer $iAltura
 */
function montaCabecalho ($oPdf, $iAltura) {

  $oPdf->addPage();
  $oPdf->setfont('Arial', 'b', 8);
  $oPdf->cell(100, $iAltura+2, "Especificação", "TRB", 0, "C");
  $oPdf->cell(30, $iAltura+2, "Serviços", 1, 0, "C");
  $oPdf->cell(30, $iAltura+2, "Obras", 1, 0, "C");
  $oPdf->cell(30, $iAltura+2, "Total", "TBL", 1, "C");
}
?>