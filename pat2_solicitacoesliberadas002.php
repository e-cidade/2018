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

/**
 * Carregamos as libs necessárias
 */
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

/**
 * Instância da classe que faz a conversão de dados vindo em JSON
 */
$oJson = new Services_JSON();

/**
 * Carregamos na memória os dados passados por GET, convertemos o Array (aDepartamentos) que veio em JSON,
 * buscamos a descrição dos departamentos do filtro e criamos a string de "situação"
 * Dados:
 * sDataInicial e sDataFinal - intervalo de datas para o relatório
 * aDepartamentos - array de departamentos
 * iSituacao - Situação a ser filtrada:
 * 		1 - Liberadas
 * 		2 - Não Liberadas
 *    3 - Todas
 */
$oGet      = db_utils::postMemory($_GET);
$sSituacao = "";
switch ($oGet->iSituacao) {
  
  case 1:
    $sSituacao = 'Liberadas';
    break;
    
  case 2:
    $sSituacao = 'Não Liberadas';
    break;
    
  case 3:
    $sSituacao = "Todas";
    break;
}


if ($oGet->aDepartamentos != null) {
  $oGet->aDepartamentos = $oJson->decode(str_replace("\\", "", $oGet->aDepartamentos));
}
$sDepartamentos = "";
if ($oGet->aDepartamentos != null && count($oGet->aDepartamentos) > 0) {
  
  $oDaoDbDepart = db_utils::getDao('db_depart');
  $sWhereBuscaDepartamentos = " coddepto IN ( ";
  foreach ($oGet->aDepartamentos as $iDepto) {
    $sWhereBuscaDepartamentos .= "{$iDepto}, ";
  }
  $sWhereBuscaDepartamentos = substr($sWhereBuscaDepartamentos, 0, -2).")";
  $sSqlBuscaDepartamentos   = $oDaoDbDepart->sql_query_file(null, "*", null, $sWhereBuscaDepartamentos);
  $rsBuscaDepartemantos     = $oDaoDbDepart->sql_record($sSqlBuscaDepartamentos);
  $aDepartamentos           = db_utils::getCollectionByRecord($rsBuscaDepartemantos);
  foreach ($aDepartamentos as $oDepartamento) {
    $sDepartamentos .= "{$oDepartamento->descrdepto}, ";
  }
  $sDepartamentos = substr($sDepartamentos, 0, -2);
}

/**
 * Iniciamos a configuração da Query de consulta de solicitações para o relatório
 */
$sCamposBuscaSolicitacoes  = " distinct pc10_numero, pc10_depto || ' - ' || descrdepto as pc10_depto, pc10_data, ";
$sCamposBuscaSolicitacoes .= " pc10_resumo, ";
$sCamposBuscaSolicitacoes .= " case ";
$sCamposBuscaSolicitacoes .= "   when exists (SELECT 1 FROM solicitem WHERE pc11_numero = pc10_numero AND pc11_liberado IS TRUE) ";
$sCamposBuscaSolicitacoes .= "     THEN 'Sim' ";
$sCamposBuscaSolicitacoes .= "   WHEN NOT EXISTS (SELECT 1 FROM solicitem WHERE pc11_numero = pc10_numero AND pc11_liberado IS TRUE) ";
$sCamposBuscaSolicitacoes .= "     THEN 'Não' ";
$sCamposBuscaSolicitacoes .= " END AS aprovado";
                              	  
/**
 * Validamos os parâmetros da pesquisa e montamos o WHERE da Query
 */
$sWhereBuscaSolicitacoes   = "";

if (trim($oGet->sDataInicial) != "") {
  $oGet->sDataInicial       = implode('-', array_reverse(explode('/', $oGet->sDataInicial)));
  $oGet->sDataFinal         = implode('-', array_reverse(explode('/', $oGet->sDataFinal)));
  $sWhereBuscaSolicitacoes .= " pc10_data BETWEEN '{$oGet->sDataInicial}' AND '{$oGet->sDataFinal}' AND ";
}


switch ($oGet->iSituacao) {
  
  case 1:
    $sWhereBuscaSolicitacoes .= " EXISTS (SELECT 1 FROM solicitem WHERE pc11_numero = pc10_numero AND pc11_liberado IS TRUE) AND ";
    break;
  
  case 2:
    $sWhereBuscaSolicitacoes .= " NOT EXISTS (SELECT 1 FROM solicitem WHERE pc11_numero = pc10_numero AND pc11_liberado IS TRUE) AND ";
    break;
}

if (count($oGet->aDepartamentos) > 0) {
  
  $sWhereBuscaSolicitacoes .= " pc10_depto IN ( ";
  foreach ($oGet->aDepartamentos as $iDepartameto) {
    $sWhereBuscaSolicitacoes .= "{$iDepartameto}, ";
  }
  $sWhereBuscaSolicitacoes = substr($sWhereBuscaSolicitacoes, 0, -2)." ) AND ";
}

if ($sWhereBuscaSolicitacoes != "") {
  $sWhereBuscaSolicitacoes = substr($sWhereBuscaSolicitacoes, 0, -4);
}

/**
 * Carregamos a DAO e montamos a query através da mesma
 */
$oDaoSolicita          = db_utils::getDao('solicita');
$sSqlBuscaSolicitacoes = $oDaoSolicita->sql_query_liberadas(null, $sCamposBuscaSolicitacoes, 
                                                            null, $sWhereBuscaSolicitacoes);
$rsBuscaSolicitacoes   = $oDaoSolicita->sql_record($sSqlBuscaSolicitacoes);

/**
 * Testamos se a consulta retornou algum registro. Em caso positivo criamos o arquivo
 * PDF. Em caso negativo exibimos uma mensagem indicando o problema ao usuário.
 */
if ($oDaoSolicita->numrows > 0) {
  
  /**
   * Iniciamos o arquivo PDF em sí
   */
  $oPdf = new PDF();
  $oPdf->open();
  $oPdf->SetFillColor(235);
  $head3 = "Relatório de Solicitações Liberadas";
  $iAlturaCelula = 4;
  $oPdf->AddPage();
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->cell(0,$iAlturaCelula,'DADOS DA PESQUISA',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Data Inicial :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, db_formatar($oGet->sDataInicial, 'd'), 0, 0, "L", 0);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Data Final :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, db_formatar($oGet->sDataFinal, 'd'), 0, 1, "L", 0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Departamentos: ', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(150, $iAlturaCelula, $sDepartamentos, 0, 1, "L", 0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Situação: ', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(150, $iAlturaCelula, $sSituacao, 0, 1, "L", 0);
  
  /**
   * Iniciamos a tabela de solicitações retornadas na consulta
   */
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(0, $iAlturaCelula, 'SOLICITAÇÕES', 0, 1, "L", 0);
  $oPdf->cell(0, $iAlturaCelula, '', 'T', 1, "R", 0);
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(15, $iAlturaCelula, "Solicitação", 1, 0, "C", 1);
  $oPdf->cell(60, $iAlturaCelula, "Departamento", 1, 0, "C", 1);
  $oPdf->cell(15, $iAlturaCelula, "Data", 1, 0, "C", 1);
  $oPdf->cell(85, $iAlturaCelula, "Resumo", 1, 0, "C", 1);
  $oPdf->cell(15, $iAlturaCelula, "Liberado", 1, 1, "C", 1);
  
  for ($i = 0; $i < $oDaoSolicita->numrows; $i++) {
    
    $oSolicitacao = db_utils::fieldsMemory($rsBuscaSolicitacoes, $i);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(15, $iAlturaCelula, $oSolicitacao->pc10_numero, 0, 0, "R", 0);
    $oPdf->cell(60, $iAlturaCelula, substr($oSolicitacao->pc10_depto, 0, 40), 0, 0, "L", 0);
    $oPdf->cell(15, $iAlturaCelula, db_formatar($oSolicitacao->pc10_data, 'd'), 0, 0, "C", 0);
    $oPdf->cell(85, $iAlturaCelula, substr($oSolicitacao->pc10_resumo, 0, 50)."...", 0, 0, "L", 0);
    $oPdf->cell(15, $iAlturaCelula, $oSolicitacao->aprovado, 0, 1, "C", 0);
    unset($oSolicitacao);
  }
  
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=A consulta não retornou nenhum resultado. Favor verificar.");
}
$oPdf->Output();