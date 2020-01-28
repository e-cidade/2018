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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oDaoPcProc       = db_utils::getDao("pcproc");
$oDaoEmparametro  = db_utils::getDao("empparametro");
$oDaoPcProcItem   = db_utils::getDao("pcprocitem");
$oDaoDbDepartOrg  = db_utils::getDao("db_departorg");
$oDaoSolicitem    = db_utils::getDao("solicitem");

$oGet             = db_utils::postMemory($_GET);
$iNumeroViasDocumento              = 1;
$iNumeroCasasDecimaisValorUnitario = 2;

/**
 * Busca dos parâmetros as configurações para:
 * -> Número de casas decimais que será impresso os valores
 * -> Número de vias do documento 
 */
if ($oDaoEmparametro->numrows > 0) {

  $oParametrosEmpenho                = db_utils::fieldsMemory($rsParametrosEmpenho, 0);
  $iNumeroCasasDecimaisValorUnitario = $oParametrosEmpenho->e30_numdec;
  $iNumeroViasDocumento              = $oParametrosEmpenho->e30_nroviaaut;
  unset($oParametrosEmpenho);
}


/**
 * Verifica os filtros 
 */
$aWhereProcessoCompras = array();
if (isset($oGet->iProcessoInicial) && !empty($oGet->iProcessoInicial)) {
  $aWhereProcessoCompras[] = " pc80_codproc >= {$oGet->iProcessoInicial}";
}
if (isset($oGet->iProcessoFinal) && !empty($oGet->iProcessoFinal)) {
  $aWhereProcessoCompras[] = " pc80_codproc <= {$oGet->iProcessoFinal}";
}

if (isset($oGet->dtInicial) && !empty($oGet->dtInicial)) {
  
  $sDataInicial            = implode("-", array_reverse(explode("/", $oGet->dtInicial)));
  $aWhereProcessoCompras[] = " pc80_data >= '{$sDataInicial}'";
}
if (isset($oGet->dtFinal) && !empty($oGet->dtFinal)) {
  
  $sDataFinal              = implode("-", array_reverse(explode("/", $oGet->dtFinal)));
  $aWhereProcessoCompras[] = " pc80_data <= '{$sDataFinal}' ";
}

if (isset($oGet->iSituacao) && !empty($oGet->iSituacao)) {
  $aWhereProcessoCompras[] = " pc80_situacao = {$oGet->iSituacao}";
}

$sWhereProcessoCompras = implode(" and ", $aWhereProcessoCompras);

/**
 * 
 * Busca o(s) Processo(s) de compra(s)  
 */
$sCampos                       = " distinct pc80_codproc, pc80_data, pc80_situacao, descrdepto, coddepto";
$sSqlProcessoCompras           = $oDaoPcProc->sql_query(null, $sCampos, 'pc80_codproc', $sWhereProcessoCompras);
$rsDadosProcessoCompras        = $oDaoPcProc->sql_record($sSqlProcessoCompras);
$iTotalLinhasProcessoDeCompras = $oDaoPcProc->numrows;
if ($iTotalLinhasProcessoDeCompras == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique seu departamento.");
}

$aProcessoCompra = db_utils::getCollectionByRecord($rsDadosProcessoCompras, true);

$head1 = "Processo de Compra por Autorização";
$head2 = "Processo de Compras: {$oGet->iProcessoInicial} a {$oGet->iProcessoFinal}";
$head3 = "Período: {$oGet->dtInicial} a {$oGet->dtFinal}";

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

/**
 * Variáveis de Controle do pdf
 */
$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;

foreach ($aProcessoCompra as $aProcesso) {
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }
  
  $sDepartamento = "{$aProcesso->coddepto} - {$aProcesso->descrdepto}"; 
  $oPdf->SetFont("arial", "", 7);
  $oPdf->Cell(30,  $iHeigth, $aProcesso->pc80_codproc,                "TBR", 0, "C");
  $oPdf->Cell(40,  $iHeigth, $aProcesso->pc80_data,                   "TBR", 0, "C");
  $oPdf->Cell(50,  $iHeigth, getSituacao($aProcesso->pc80_situacao),  "TBR", 0, "L");
  $oPdf->Cell(70,  $iHeigth, $sDepartamento,                          "TBR", 1, "L");
  
}

/**
 * 
 * Retorna a Descrição da Situação
 * @param integer $iSituacao
 */
function getSituacao($iSituacao) {
  $sSituacao = "Todas";
  switch ($iSituacao) {
    case 1:
      $sSituacao = "Em Analise";
      break;
    case 2:
      $sSituacao = "Autorizado";
      break;
    case 3:
      $sSituacao = "Não Autorizado";
      break;
  }
  return $sSituacao;
}


/**
* Insere o cabeçalho do relatório 
* @param object $oPdf
* @param integer $iHeigth Altura da linha
*/
function setHeader($oPdf, $iHeigth) {

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->setfillcolor(235);
  $oPdf->AddPage();
  $oPdf->Cell(30,  $iHeigth, "Proc. Compras", "TBR", 0, "C", 1);
  $oPdf->Cell(40,  $iHeigth, "Data", "LTB", 0, "C", 1);
  $oPdf->Cell(50,  $iHeigth, "Situacao", "TBL", 0, "C", 1);
  $oPdf->Cell(70,  $iHeigth, "Departamento", "TBL", 1, "C", 1);
}

$oPdf->Output();