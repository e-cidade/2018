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

require_once ("fpdf151/pdf.php");
require_once ("std/DBDate.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/exceptions/ParameterException.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("social.FamiliaRepository");

$oGet   = db_utils::postMemory($_GET);
$aWhere = array();

/**
 * Verifica os filtros
 */
if (!empty($oGet->sCodigoFamiliar)) {
  $aWhere[] = " as15_codigofamiliarcadastrounico = '{$oGet->sCodigoFamiliar}' ";
}

if (!empty($oGet->iMes)) {
  
  $aWhere[] = " as08_mes = {$oGet->iMes} ";
  $head3    = "Mês: {$oGet->iMes}";
}

if (!empty($oGet->sAno)) {
  
  $aWhere[] = " as08_ano = '{$oGet->sAno}' ";
  $head3    = "Ano: {$oGet->sAno}";
}
$aWhere[] = " substr(ov02_nome, 1, 1) BETWEEN '{$oGet->sLetraInicio}' and '{$oGet->sLetraFinal}'";
$aWhere[] = " as03_tipofamiliar = 0 ";
/**
 * Realizamos a busca dos dados 
 */
$sWhere          = implode(" and", $aWhere);
$aCidadaoFamilia = FamiliaRepository::getFamiliasByFilter($sWhere, " ov02_nome ");
if (count($aCidadaoFamilia) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}
$aDadosVisita    = array();

/**
 * Organizamos os dados indexando-os pelo alfabeto
 */
foreach ($aCidadaoFamilia as $oCidadaoFamilia) {
  
  $oDados                  = new stdClass();
  $oResponsavel            = $oCidadaoFamilia->getResponsavel();
  $oDados->iCodigoFamiliar = $oCidadaoFamilia->getCodigoFamiliarCadastroUnico();
  $oDados->sNis            = $oResponsavel->getNis();
  $oDados->sNome           = $oResponsavel->getNome();
  $oDados->sBeneficios     = $oCidadaoFamilia->getListaBeneficios();
  ksort($oDados->sBeneficios);
  $sLetra                  = substr($oResponsavel->getNome(), 0, 1);
  $aDadosVisita[$sLetra][] = $oDados;
  unset($oDados);
}

$head1 = "Relatório de Visitas";
$head2 = "Filtros";
if (!empty($oGet->iMes) && !empty($oGet->sAno)) {
  $head3 = "Mês/Ano: {$oGet->iMes}/{$oGet->sAno}";
}
$head4 = "Letras: {$oGet->sLetraInicio} à {$oGet->sLetraFinal}";


$oPdf  = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;
$iTotalRegistroLetra = 0;
$iTotalRegistros     = 0;

/**
 * Realiza impressão dos dados
 */
foreach ($aDadosVisita as $iIndice => $aDados) {

  if ($oGet->sQuebraPagina == "true" || $lPrimeiroLaco) {
    	
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }
  
  foreach ($aDados as $oDados) {
  
    if ($oPdf->gety() > $oPdf->h - 20) {
      setHeader($oPdf, $iHeigth);
    }

    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(30, $iHeigth, "{$oDados->iCodigoFamiliar}", "TB", 0, "C");
    $oPdf->Cell(20, $iHeigth, $oDados->sNis,                "1",  0, "C");
    if (strlen($oDados->sNome) > 65) {
      $oDados->sNome = substr($oDados->sNome, 0, 64);
    }
    $oPdf->Cell(100,  $iHeigth, "{$oDados->sNome}",          "1", 0, "L");
    $sBeneficios = "";
    foreach ($oDados->sBeneficios as $oBenecifios) {
      
      $oDadosBeneficios->beneficios = $oBenecifios;
      if ($sBeneficios == "") {
        $sBeneficios = $oDadosBeneficios->beneficios->beneficio;
      } else {
        $sBeneficios = $sBeneficios.", ".$oDadosBeneficios->beneficios->beneficio;
      }
    }
    $oPdf->Cell(127,  $iHeigth, "{$sBeneficios}",    "TB", 1,"L");
    $iTotalRegistroLetra++;
    $iTotalRegistros++;
  }
  if ($oGet->sQuebraPagina == "true") {
    
    $oPdf->setfont('arial', 'b', 9);
    $oPdf->Cell(260, $iHeigth, "Total da letra \"{$iIndice}\": ", "TB", 0,"R");
    $oPdf->Cell(20,  $iHeigth, $iTotalRegistroLetra,              "TB", 1,"L");
  }
}
if ($oPdf->gety() > $oPdf->h - 20) {
  $oPdf->AddPage();
}
$oPdf->setfont('arial', 'b', 9);
$oPdf->Cell(260,  $iHeigth, "Total de registros: ", "TB", 0,"R");
$oPdf->Cell(20,  $iHeigth, $iTotalRegistros,        "TB", 1,"L");

/**
 * Imprime o cabeçalho 
 * @param object $oPdf
 * @param integer $iHeigth
 * @param boolean $lQuebraPagina
 */
function setHeader($oPdf, $iHeigth, $lQuebraPagina = true) {

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->setfillcolor(235);
  
  $oPdf->AddPage();
  
  $oPdf->Cell(30,  $iHeigth, "Código Familiar",     "TBR", 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "NIS",                 "LTB", 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Nome do Responsável", "TBL", 0, "C", 1);
  $oPdf->Cell(127, $iHeigth, "Benefícios",          "LTB", 1, "C", 1);
}

$oPdf->Output();