<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

db_app::import("contabilidade.relatorios.AnexoDemonstrativoReceitaPublica");
db_app::import("contabilidade.relatorios.AnexoReceitaCorrenteLiquida");
db_app::import("relatorioContabil");

$oGet         = db_utils::postMemory($_GET);
$iAnoUso      = db_getsession('DB_anousu');
$sInstit      = str_replace("-", ",", $oGet->sInstit);
$oAnexoRecPublica = new AnexoDemonstrativoReceitaPublica($iAnoUso, $oGet->iCodRel, $oGet->iPeriodo);
$oAnexoRecPublica->setInstituicoes($sInstit);
$oDadosAnexo = $oAnexoRecPublica->getDados();

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

$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "Demonstrativo da Dívida Pública (LRF, Art. 5º)";
$head3 = "Lei Orçamentária Anual de ".db_getsession("DB_anousu");
$head4 = "Instituições: {$sDescricaoInstitucoes}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$iAltura       = 4;
$lPrimeiroLaco = true;
$nTotalCorrente = 0;
$nTotalCapital  = 0;

foreach ($oDadosAnexo as $iLinha => $oDado) {
  
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    montaCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }
  if ($iLinha == 1) {

    foreach ($oDado->dadoslinha as $oLinhaConf) {
      
      $oPdf->setX(50);
      $oPdf->setfont('Arial', '', 6);
      $oPdf->cell(80, $iAltura, $oLinhaConf->descricao, "R", 0, "L");
      $oPdf->cell(30, $iAltura, db_formatar($oLinhaConf->valor, 'f'), 0, 1, "R");
    }
  } else {
  
    $sBordaDesc  = "TR";
    $sBordaValor = "T";
    $sValor = db_formatar($oDado->valor, 'f');
    if ($iLinha == 4) {

      $sValor = round($oDado->valor, 2);
      $sBordaDesc  = "BR";
      $sBordaValor = "B";
    }
    
    $oPdf->setX(50);
    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell(80, $iAltura, $oDado->descricao, "RTB", 0, "L");
    $oPdf->cell(30, $iAltura, $sValor, "TB", 1, "R");
  }
}

$oPdf->ln();
$oAnexoRecPublica->getNotaExplicativa($oPdf, $oGet->iPeriodo);
$oPdf->Output();

function montaCabecalho ($oPdf, $iAltura) {

  $oPdf->addPage();
  $oPdf->setfont('Arial', 'b', 6);
  $oPdf->setX(50);
  $oPdf->cell(110, $iAltura+2, "R$ 1,00", 0, 1, "R");
  $oPdf->setX(50);
  $oPdf->cell(80, $iAltura+2, "Dívida Fundada", "TBR", 0, "C");
  $oPdf->cell(30, $iAltura+2, "Total", "TB", 1, "C");
}

?>