<?
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/patrimonio/Inventario.model.php");
require_once("model/patrimonio/InventarioBem.model.php");
require_once("model/patrimonio/TransferenciaBens.model.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once ("model/patrimonio/BemCedente.model.php");
require_once("model/configuracao/DBDepartamento.model.php");
require_once("model/configuracao/DBDivisaoDepartamento.model.php");

require_once("model/CgmFactory.model.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet       = db_utils::postMemory($_GET,0);
$iInstit    = db_getsession("DB_instit") ;
$sWhere     = "instit = {$iInstit} ";
$aCabecalho = array();

/**
 * Filtro por Situação
 */
$aSituacao = array();
if (isset($oGet->lAberto) && $oGet->lAberto == 1) {
  $aSituacao[] = $oGet->lAberto;
}
if (isset($oGet->lAnulado) && $oGet->lAnulado == 2) {
  $aSituacao[] = $oGet->lAnulado ;
}
if (isset($oGet->lProcessado) && $oGet->lProcessado == 3) {
  $aSituacao[] = $oGet->lProcessado ;
}

if(count($aSituacao) > 0){

  $sWhere      .= " and inventario.t75_situacao in (".implode(",",$aSituacao).")";  
}

/**
 * Filtro por Data 
 */
if (isset($oGet->dtDataInicial) && !empty($oGet->dtDataInicial)) {
  
  $sWhere      .= " and inventario.t75_periodoinicial >= '{$oGet->dtDataInicial}' ";
  $aCabecalho[] = "Período Inicial: {$oGet->dtDataInicial}";
}
if (isset($oGet->dtDataFinal) && !empty($oGet->dtDataFinal)) {
  
  $sWhere      .= " and inventario.t75_periodofinal <= '{$oGet->dtDataFinal}' ";
  $aCabecalho[] = "Período Final: {$oGet->dtDataFinal}";
}


/**
 *  Filtro por Inventário 
 */
if (isset($oGet->iInventarioInicial) && !empty($oGet->iInventarioInicial)) {
  
  $sWhere      .= " and inventario.t75_sequencial >= {$oGet->iInventarioInicial} ";
  $aCabecalho[] = "Inventário Inicial: {$oGet->iInventarioInicial}";
}

if (isset($oGet->iInventarioFinal) && !empty($oGet->iInventarioFinal)) {
  
  $sWhere      .= " and inventario.t75_sequencial <= {$oGet->iInventarioFinal} ";
  $aCabecalho[] = "Inventário Final: {$oGet->iInventarioFinal}";
}

$sOrder         = "inventario.t75_sequencial";
$oDaoInventario = db_utils::getDao("inventario");

$sCampos  = " t75_sequencial,    ";
$sCampos .= "t75_dataabertura,   ";
$sCampos .= "t75_periodoinicial, ";
$sCampos .= "t75_periodofinal,   ";
$sCampos .= "t75_exercicio,      ";
$sCampos .= "t75_processo,       ";
$sCampos .= "t75_acordocomissao, ";
$sCampos .= "t75_observacao,     ";
$sCampos .= "t75_db_depart,      ";
$sCampos .= "t75_situacao,       ";
$sCampos .= "descrdepto,         ";
$sCampos .= "ac08_descricao      ";


$sSqlInventario = $oDaoInventario->sql_query(null, $sCampos, $sOrder, $sWhere);

//echo $sSqlInventario; die();

$rsInventario   = $oDaoInventario->sql_record($sSqlInventario);
$aInventarios   = array();

if ($oDaoInventario->numrows == 0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_inventarios002.nenhum_bem_cadastrado');
  db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
}

$ValorTotalGeral      = 0;
for ($i = 0; $i < $oDaoInventario->numrows; $i++){
  
  $oRsInventario                         = db_utils::fieldsMemory($rsInventario, $i);
  $oInventario                           = new Inventario($oRsInventario->t75_sequencial);
  
  $oStdInventario                        = new stdClass();
  
  $oStdInventario->nValorTotalInventario = 0;
  $oStdInventario->iCodigoInventario     = $oInventario->getInventario();
  $oStdInventario->dtDataInicial         = db_formatar($oInventario->getPeriodoInicial(),"d");
  $oStdInventario->dtDataFinal           = db_formatar($oInventario->getPeriodoFinal(),"d");
  $oStdInventario->sSituacao             = $oInventario->getSituacaoString();
  $oStdInventario->sObservacao           = substr($oInventario->getObservacao(), 0, 60);
  $oStdInventario->sDepartamento         = $oRsInventario->descrdepto;
  $oStdInventario->sComissao             = $oRsInventario->ac08_descricao;
  $aBensInventario                       = $oInventario->getBens();
  
  
  
  foreach($aBensInventario as $oBem) {
    
    $oStdInventario->nValorTotalInventario += $oBem->getValorDepreciavel();
  }
  
  $ValorTotalGeral += $oStdInventario->nValorTotalInventario;
  $aInventarios[] = $oStdInventario;

  unset($oRsInventario);
  unset($oStdInventario);
}


//die();
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$iAlturalinha    = 4;
$iFonte          = 6;
$head2           = "RELATÓRIO DE INVENTÁRIO";
$iCabecalho      = count($aCabecalho);
$ValorTotalGeral = db_formatar($ValorTotalGeral,"f");

for($i = 0; $i < $iCabecalho; $i++) {
    
    $iHead  = $i+3;
    $sHead  = "head{$iHead}";
    $$sHead = $aCabecalho[$i];
}


$oPdf->AddPage("L");
imprimirCabecalho($oPdf, $iAlturalinha, true);
$iTotalInventarios = count($aInventarios);

foreach ($aInventarios as $oStdInventario) {
	
  $oPdf->setfont('arial','',$iFonte);
  $oPdf->cell(15, $iAlturalinha, $oStdInventario->iCodigoInventario                      , "TBR", 0, "R", 0);
  $oPdf->cell(90, $iAlturalinha, $oStdInventario->sObservacao                            , "TBR", 0, "L", 0);
  $oPdf->cell(55, $iAlturalinha, $oStdInventario->sDepartamento                          , "TBR", 0, "L", 0);
  $oPdf->cell(50, $iAlturalinha, $oStdInventario->sComissao                              , "TBR", 0, "L", 0);
  $oPdf->cell(15, $iAlturalinha, $oStdInventario->dtDataInicial                          , "TBR", 0, "C", 0);
  $oPdf->cell(15, $iAlturalinha, $oStdInventario->dtDataFinal                            , "TBR", 0, "C", 0);
  $oPdf->cell(20, $iAlturalinha, $oStdInventario->sSituacao                              , "TBR", 0, "L", 0);
  $oPdf->cell(20, $iAlturalinha, db_formatar($oStdInventario->nValorTotalInventario,"f") , "TLB", 1, "R", 0);
  
  imprimirCabecalho($oPdf, $iAlturalinha, false);
}

if ( $oPdf->GetY() > $oPdf->h - 25) {
  $oPdf->AddPage("L");
}

// TOTALIZADORS
$oPdf->setfont('arial','B',$iFonte);
$oPdf->cell(265,  $iAlturalinha, "TOTAL DE REGISTROS: ", "TB",  0, "R", 0);
$oPdf->cell(15 ,  $iAlturalinha, $iTotalInventarios    , "TB",  1, "R", 0);
$oPdf->cell(265,  $iAlturalinha, "TOTAL DE VALORES: "  , "TB",  0, "R", 0);
$oPdf->cell(15 ,  $iAlturalinha, $ValorTotalGeral      , "TB",  1, "R", 0);
$oPdf->output();


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    
    if ( !$lImprime ) {                                                      
    	                                                                     
      $oPdf->AddPage("L");                                                   
    }                                                                        
                                                                             
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(15, $iAlturalinha, "Inventario"     , "TRB", 0, "C", 1);
    $oPdf->cell(90, $iAlturalinha, "Descrição"      , "TLB", 0, "C", 1);
    $oPdf->cell(55, $iAlturalinha, "Departamento"   , "TLB", 0, "C", 1);
    $oPdf->cell(50, $iAlturalinha, "Comissão"       , "TLB", 0, "C", 1);
    $oPdf->cell(15, $iAlturalinha, "Período Inicial", "TLB", 0, "C", 1);
    $oPdf->cell(15, $iAlturalinha, "Período Final"  , "TLB", 0, "C", 1);
    $oPdf->cell(20, $iAlturalinha, "Situação"       , "TLB", 0, "C", 1);
    $oPdf->cell(20, $iAlturalinha, "Valor Total"    , "TLB", 1, "C", 1);
  }
}



?>