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
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));

require_once(modification("model/patrimonio/Bem.model.php"));
require_once(modification("model/patrimonio/BemCedente.model.php"));
require_once(modification("model/patrimonio/BemClassificacao.model.php"));
require_once(modification("model/patrimonio/PlacaBem.model.php"));
require_once(modification("model/patrimonio/BemHistoricoMovimentacao.model.php"));
require_once(modification("model/patrimonio/BemDadosMaterial.model.php"));
require_once(modification("model/patrimonio/BemDadosImovel.model.php"));
require_once(modification("model/patrimonio/BemTipoAquisicao.php"));
require_once(modification("model/patrimonio/BemTipoDepreciacao.php"));
require_once(modification("model/CgmFactory.model.php"));

/**
 * Variáveis de parâmetros passadas por GET
 * t52_bem
 * lDadosMaterial
 * lDadosImovel
 * lHistoricoMovimentacao
 * lHistoricoFinanceiro
 * lHistoricoPlaca
 */
$oGet = db_utils::postMemory($_GET, false);

/**
 * Iniciamos o processamento dos dados que serão exibidos na impressão
 */
$oBem             = new Bem($oGet->t52_bem);
$oClassificao     = $oBem->getClassificacao();
$oFornecedor      = $oBem->getFornecedor();
$oCedente         = $oBem->getCedente();
$oPlaca           = $oBem->getPlaca();
$oImovel          = $oBem->getDadosImovel();
$oMaterial        = $oBem->getDadosCompra();
$oTipoAquisicao   = $oBem->getTipoAquisicao();
$oTipoDepreciacao = $oBem->getTipoDepreciacao();

/**
* Carregamos a DAO e efetuamos a consulta necessária de Orgão e Unidade
*/
$oDaoDbDepartOrg          = db_utils::getDao('db_departorg');
$sCamposBuscaOrgaoUnidade = " db01_orgao, o40_descr, db01_unidade, o41_descr ";
$sWhereBuscaOrgaoUnidade  = "     db01_anousu = ".db_getsession("DB_anousu");
$sWhereBuscaOrgaoUnidade .= " AND db01_coddepto = {$oBem->getDepartamento()} ";
$sSqlBuscaOrgaoUnidade    = $oDaoDbDepartOrg->sql_query_orgunid(null, null, $sCamposBuscaOrgaoUnidade, null, $sWhereBuscaOrgaoUnidade);
$rsBuscaOrgaoUnidade      = $oDaoDbDepartOrg->sql_record($sSqlBuscaOrgaoUnidade);
$oOrgaoUnidade            = db_utils::fieldsMemory($rsBuscaOrgaoUnidade, 0);

/**
 * Carregamos a DAO e efetuamos a consulta necessária de Descricao de departamento
 */
$oDaoDbDepart             = db_utils::getDao('db_depart');
$sCamposBuscaDepartamento = " descrdepto, t30_codigo, t30_descr ";
$sWhereBuscaDepartamento  = " coddepto = {$oBem->getDepartamento()} ";
$iDivisao = $oBem->getDivisao();
if (!empty($iDivisao)) {
  $sWhereBuscaDepartamento  .= " AND t30_codigo = {$oBem->getDivisao()}";
}
$sSqlBuscaDepartamento    = $oDaoDbDepart->sql_query_div(null, $sCamposBuscaDepartamento, null, $sWhereBuscaDepartamento);
$rsBuscaDepartameto       = $oDaoDbDepart->sql_record($sSqlBuscaDepartamento);
$oDepartamento            = db_utils::fieldsMemory($rsBuscaDepartameto, 0);

/**
* Carregamos a DAO e efetuamos a consulta necessária de Convênios
*/
if ($oCedente != null){

  $oDaoConvenio         = db_utils::getDao('benscadcedente');
  $sCamposBuscaConvenio = " z01_nome ";
  $sWhereBuscaConvenio  = " t04_sequencial = {$oCedente->getCodigo()} ";
  $sSqlBuscaConvenio    = $oDaoConvenio->sql_query(null, $sCamposBuscaConvenio, null, $sWhereBuscaConvenio);
  $rsBuscaConvenio      = $oDaoConvenio->sql_record($sSqlBuscaConvenio);
  $oConvenio            = db_utils::fieldsMemory($rsBuscaConvenio, 0);
}

$sBem             = $oBem->getCodigoBem().' - '.$oBem->getDescricao();
$sClassificacao   = "";
if ($oClassificao != null){
  $sClassificacao = $oClassificao->getCodigo().' - '.$oClassificao->getDescricao();
}
$sOrgao           = $oOrgaoUnidade->db01_orgao.' - '.$oOrgaoUnidade->o40_descr;
$sUnidade         = $oOrgaoUnidade->db01_unidade.' - '.$oOrgaoUnidade->o41_descr;
$sDepartamento    = $oBem->getDepartamento().' - '.$oDepartamento->descrdepto;
$sDivisaoDepart   = $oBem->getDivisao().' - '.$oDepartamento->t30_descr;
$sFornecedor      = "";
$sTelefone        = "";
$sEmail           = "";
if ($oFornecedor != null){

  $sFornecedor = $oFornecedor->getCodigo().' - '.$oFornecedor->getNome();
  $sTelefone   = $oFornecedor->getTelefone();
  $sEmail   = $oFornecedor->getEmail();
}


$sCedente = "";
if ($oCedente != null){
  $sCedente = $oCedente->getCodigo().' - '.$oConvenio->z01_nome;
}
$sAquisicao       = db_formatar($oBem->getDataAquisicao(), 'd');
$sValorResidual   = trim(db_formatar($oBem->getValorResidual(), "f"));
$sValorAquisicao  = trim(db_formatar($oBem->getValorAquisicao(), "f"));
$sTipoDepreciacao = "";

if ($oTipoDepreciacao != null){
  $sTipoDepreciacao = $oTipoDepreciacao->getDescricao();
}
$sPlacaIdent  = "";
if ($oPlaca != null){
  $sPlacaIdent = $oPlaca->getNumeroPlaca();
}
$sCodigoLote   = "";
if ($oImovel != null){
  $sCodigoLote = $oImovel->getIdBql();
}
$sObservacoes  = $oBem->getObservacao();

/**
 * 
 */
if (isset($oGet->lDadosMaterial)){
  
  $oDadosMaterial                = new stdClass();
  $oDadosMaterial->sNotaFiscal   = "";
  $oDadosMaterial->sEmpenho      = "";
  $oDadosMaterial->sOrdemCompra  = "";
  $oDadosMaterial->sDataGarantia = "";
  $oDadosMaterial->sCredor       = "";
  if ($oMaterial != null){
    
    $oDadosMaterial->sNotaFiscal   = $oMaterial->getNotaFiscal();
    $oDadosMaterial->sEmpenho      = $oMaterial->getEmpenho();
    $oDadosMaterial->sOrdemCompra  = $oMaterial->getOrdemCompra();
    $oDadosMaterial->sDataGarantia = $oMaterial->getDataGarantia();
    $oDadosMaterial->sCredor       = $oMaterial->getCredor();
  }
}

if (isset($oGet->lDadosImovel)){
  
  $oDadosImovel              = new stdClass();
  $oDadosImovel->sLote       = "";
  $oDadosImovel->sObservacao = "";
  
  if ($oImovel != null){
    
    $oDadosImovel->sLote       = $oImovel->getIdBql();
    $oDadosImovel->sObservacao = $oImovel->getObservacao();
  }
}

if (isset($oGet->lHistoricoMovimentacao)){
  
  $oDaoHistBem                       = db_utils::getDao('histbem');
  $sCamposBuscaHistoricoMovimentacao = " t56_data, t56_histor, db_depart.descrdepto as descrdepto, t70_descr, z01_nome ";
  $sWhereBuscaHistoricoMovimentacao  = " t56_codbem = {$oGet->t52_bem} ";
  $sSqlBuscaHistoricoMovimentacao    = $oDaoHistBem->sql_query(null, $sCamposBuscaHistoricoMovimentacao, 
                                                               null, $sWhereBuscaHistoricoMovimentacao);
  $rsBuscaHistoricoMovimentacao      = $oDaoHistBem->sql_record($sSqlBuscaHistoricoMovimentacao);
  $aHistoricoMovimentacao            = db_utils::getCollectionByRecord($rsBuscaHistoricoMovimentacao);
}

if (isset($oGet->lHistoricoFinanceiro)){
  
  $oDaoBensHistoricoCalculoBem      = db_utils::getDao('benshistoricocalculobem');
  $sCamposBuscaHistoricoFinanceiro  = " t57_datacalculo,t58_valoranterior,t58_valorcalculado, t58_valoratual, ";
  $sCamposBuscaHistoricoFinanceiro .= " CASE WHEN t57_tipoprocessamento = 1 ";
  $sCamposBuscaHistoricoFinanceiro .= "      THEN 'Automático' ";
  $sCamposBuscaHistoricoFinanceiro .= "      ELSE 'Manual' END AS t57_tipoprocessamento, ";
  $sCamposBuscaHistoricoFinanceiro .= " CASE WHEN t57_tipocalculo = 1 ";
  $sCamposBuscaHistoricoFinanceiro .= "      THEN 'Depreciação' ";
  $sCamposBuscaHistoricoFinanceiro .= "      ELSE 'Reavaliação' END AS t57_tipocalculo, ";
  $sCamposBuscaHistoricoFinanceiro .= " CASE WHEN t57_processado IS FALSE ";
  $sCamposBuscaHistoricoFinanceiro .= "      THEN 'Desprocessado' ";
  $sCamposBuscaHistoricoFinanceiro .= "      ELSE 'Processado' END AS t57_processado, ";
  $sCamposBuscaHistoricoFinanceiro .= " fc_mesextenso(t57_mes, 'sigla') || '/' || t57_ano AS competencia, z01_nome ";
  $sWhereBuscaHistoricoFinanceiro   = " t58_bens = {$oGet->t52_bem} ";
  $sSqlBuscaHistoricoFinanceiro     = $oDaoBensHistoricoCalculoBem->sql_query(null, $sCamposBuscaHistoricoFinanceiro, 
                                                                              null, $sWhereBuscaHistoricoFinanceiro);
  $rsBuscaHistoricoFinanceiro       = $oDaoBensHistoricoCalculoBem->sql_record($sSqlBuscaHistoricoFinanceiro);
  $aHistoricoFinanceiro             = db_utils::getCollectionByRecord($rsBuscaHistoricoFinanceiro);
}

if (isset($oGet->lHistoricoPlaca)){

  $aHistoricoPlaca = array(); // @todo tratar para exibir se oPlaca for nulo no relatório em sí
  if ($oPlaca != null){
    
    $oDaoBensPlaca              = db_utils::getDao('bensplaca');
    $sCamposBuscaHistoricoPlaca = " t41_data, t41_obs, descrdepto, '{$oPlaca->getNumeroPlaca()} - ' || t41_placaseq as placa ";
    $sWhereBuscaHistoricoPlaca  = " t41_bem = {$oGet->t52_bem} ";
    $sSqlBuscaHistoricoPlaca    = $oDaoBensPlaca->sql_query(null, $sCamposBuscaHistoricoPlaca, 
                                                            null, $sWhereBuscaHistoricoPlaca);
    $rsBuscaHistoricoPlaca      = $oDaoBensPlaca->sql_record($sSqlBuscaHistoricoPlaca);
    $aHistoricoPlaca            = db_utils::getCollectionByRecord($rsBuscaHistoricoPlaca);
  }
}

/**
 * Começamos o arquivo PDF em sí
 */
$oPdf  = new PDF();
$oPdf->Open();
$oPdf->SetFillColor(235);
$head3 = "FICHA DO BEM";
$iAlturaCelula = 4;
$oPdf->AddPage();

$oPdf->setfont('arial','b',8);
$oPdf->cell(0,$iAlturaCelula,'DADOS DO BEM',0,1,"L",0);
$oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Bem :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sBem, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Classificação :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sClassificacao, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Órgão :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sOrgao, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Unidade :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sUnidade, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Departamento :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sDepartamento, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Divisão Depart. :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sDivisaoDepart, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Fornecedor :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sFornecedor, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Convênio :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sCedente, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Telefone :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sTelefone, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'E-mail :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sEmail, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Aquisição :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sAquisicao, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Valor Residual :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sValorResidual, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Valor Aquisição :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sValorAquisicao, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Tipo de Depreciação :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sTipoDepreciacao, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Placa Ident. :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sPlacaIdent, 0, 0, "L", 0);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Código do Lote :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->Cell(60, $iAlturaCelula, $sCodigoLote, 0, 1, "L", 0);

$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(30, $iAlturaCelula, 'Observações :', 0, 0, "R", 0);
$oPdf->SetFont('arial', '', 7);
$oPdf->MultiCell(150, $iAlturaCelula, $sObservacoes, 0, 1, "L", 0);


if (isset($oGet->lDadosMaterial)){
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$iAlturaCelula,'DADOS MATERIAL',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Nota Fiscal :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosMaterial->sNotaFiscal, 0, 0, "L", 0);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Empenho :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosMaterial->sEmpenho, 0, 1, "L", 0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Ordem de Compra :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosMaterial->sOrdemCompra, 0, 0, "L", 0);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Data Garantia :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosMaterial->sDataGarantia, 0, 1, "L", 0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Credor :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(150, $iAlturaCelula, $oDadosMaterial->sCredor, 0, 1, "L", 0);
}

if (isset($oGet->lDadosImovel)){
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$iAlturaCelula,'DADOS IMOVEL',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Lote :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosImovel->sLote, 0, 0, "L", 0);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(30, $iAlturaCelula, 'Observação :', 0, 0, "R", 0);
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(60, $iAlturaCelula, $oDadosImovel->sObservacao, 0, 1, "L", 0);
}

if (isset($oGet->lHistoricoMovimentacao)){
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$iAlturaCelula,'HISTÓRICO MOVIMENTAÇÃO',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(28, $iAlturaCelula, "Data", 1, 0, "C", 1);
  $oPdf->cell(54, $iAlturaCelula, "Histórico", 1, 0, "C", 1);
  $oPdf->cell(54, $iAlturaCelula, "Descrição Departamento", 1, 0, "C", 1);
  $oPdf->cell(54, $iAlturaCelula, "Descrição da Situação", 1, 1, "C", 1);
  
  foreach ($aHistoricoMovimentacao as $oMovimentacao){
    
    $oPdf->setfont('arial','',7);
    $oPdf->cell(28, $iAlturaCelula, db_formatar($oMovimentacao->t56_data, 'd'), 0, 0, "C", 0);
    $oPdf->cell(54, $iAlturaCelula, $oMovimentacao->t56_histor, 0, 0, "L", 0);
    $oPdf->cell(54, $iAlturaCelula, $oMovimentacao->descrdepto, 0, 0, "L", 0);
    $oPdf->cell(54, $iAlturaCelula, $oMovimentacao->t70_descr, 0, 1, "L", 0);
  }
}

if (isset($oGet->lHistoricoFinanceiro)){
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$iAlturaCelula,'HISTÓRICO FINANCEIRO',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(23, $iAlturaCelula, "Data", 1, 0, "C", 1);
  $oPdf->cell(18, $iAlturaCelula, "Vlr. Anter.", 1, 0, "C", 1);
  $oPdf->cell(18, $iAlturaCelula, "Vlr. Calc.", 1, 0, "C", 1);
  $oPdf->cell(18, $iAlturaCelula, "Vlr. Atual", 1, 0, "C", 1);
  $oPdf->cell(33, $iAlturaCelula, "Tp. Processamento", 1, 0, "C", 1);
  $oPdf->cell(28, $iAlturaCelula, "Tp. Cálculo", 1, 0, "C", 1);
  $oPdf->cell(23, $iAlturaCelula, "Processado", 1, 0, "C", 1);
  $oPdf->cell(23, $iAlturaCelula, "Competência", 1, 1, "C", 1);
  
  foreach ($aHistoricoFinanceiro as $oFinanceiro){
    
    $oPdf->setfont('arial','',7);
    $oPdf->cell(23, $iAlturaCelula, db_formatar($oFinanceiro->t57_datacalculo, "d"), 0, 0, "C", 0);
    $oPdf->cell(18, $iAlturaCelula, db_formatar($oFinanceiro->t58_valoranterior, "f"), 0, 0, "R", 0);
    $oPdf->cell(18, $iAlturaCelula, db_formatar($oFinanceiro->t58_valorcalculado, "f"), 0, 0, "R", 0);
    $oPdf->cell(18, $iAlturaCelula, db_formatar($oFinanceiro->t58_valoratual, "f"), 0, 0, "R", 0);
    $oPdf->cell(33, $iAlturaCelula, $oFinanceiro->t57_tipoprocessamento, 0, 0, "L", 0);
    $oPdf->cell(28, $iAlturaCelula, $oFinanceiro->t57_tipocalculo, 0, 0, "L", 0);
    $oPdf->cell(23, $iAlturaCelula, $oFinanceiro->t57_processado, 0, 0, "L", 0);
    $oPdf->cell(23, $iAlturaCelula, $oFinanceiro->competencia, 0, 1, "L", 0);
  }
}

if (isset($oGet->lHistoricoPlaca)){
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$iAlturaCelula,'PLACA',0,1,"L",0);
  $oPdf->cell(0,$iAlturaCelula,'','T',1,"R",0);
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(27, $iAlturaCelula, "Data Placa", 1, 0, "C", 1);
  $oPdf->cell(67, $iAlturaCelula, "Observação Referente a Placa", 1, 0, "C", 1);
  $oPdf->cell(67, $iAlturaCelula, "Descrição Departamento", 1, 0, "C", 1);
  $oPdf->cell(27, $iAlturaCelula, "Placa", 1, 1, "C", 1);
  // t41_data, t41_obs, descrdepto, '{$oPlaca->getNumeroPlaca()} - ' || t41_placaseq as placa
  
  foreach ($aHistoricoPlaca as $oPlacaInfo){
    
    $oPdf->setfont('arial','',7);
    $oPdf->cell(27, $iAlturaCelula, db_formatar($oPlacaInfo->t41_data, "d"), 0, 0, "C", 0);
    $oPdf->cell(67, $iAlturaCelula, $oPlacaInfo->t41_obs, 0, 0, "L", 0);
    $oPdf->cell(67, $iAlturaCelula, $oPlacaInfo->descrdepto, 0, 0, "L", 0);
    $oPdf->cell(27, $iAlturaCelula, $oPlacaInfo->placa, 0, 1, "C", 0);
  }
}
$oPdf->Output();
