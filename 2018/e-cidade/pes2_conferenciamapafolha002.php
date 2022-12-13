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

/**
 * 
 * @author Iuri Guntchnigg
 * @revision $Author: dbalberto $
 * @version $Revision: 1.7 $
 */
include ("fpdf151/pdf.php");
require("libs/db_utils.php");
require("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_GET["json"]));
$oParam->iTipo = 10;
/**
 * Consultamos todos os recursos que existam no calculo da folha
 */
$sWhere     = "";
$sWhereSlip = "";
if ($oParam->sSigla != 'r') {
  
  $sWhere     = " and rh72_siglaarq = '{$oParam->sSigla}' ";
  $sWhereSlip = " and rh79_siglaarq = '{$oParam->sSigla}' ";
  
  if (isset($oParam->sSemestre)) {
    
    $sWhere     .= " and rh72_seqcompl <> '0' ";
    $sWhereSlip .= " and rh79_seqcompl <> '0' ";
    
    if ($oParam->sSemestre != '') {
      
      $sWhere     .= " and rh72_seqcompl = '{$oParam->sSemestre}' ";
      $sWhereSlip .= " and rh79_seqcompl = '{$oParam->sSemestre}' ";
      
    }
    
  }
  
}

$aTiposFolha["r48"] = array("sigla" => "r48", "tabela" => "gerfcom");
$aTiposFolha["r20"] = array("sigla" => "r20", "tabela" => "gerfres");
$aTiposFolha["r35"] = array("sigla" => "r35", "tabela" => "gerfs13");
$aTiposFolha["r22"] = array("sigla" => "r22", "tabela" => "gerfadi");
$aTiposFolha["r14"] = array("sigla" => "r14", "tabela" => "gerfsal");      
$aRecursos     = array();
$sSqlRecursos  = "select distinct o15_codigo,";
$sSqlRecursos .= "       o15_descr ";
$sSqlRecursos .= "  from rhempenhofolha";
$sSqlRecursos .= "       inner join orctiporec on rh72_recurso = o15_codigo";
$sSqlRecursos .= " where rh72_anousu =  {$oParam->iAnoFolha}";
$sSqlRecursos .= "   and rh72_mesusu =  {$oParam->iMesFolha}";
$sSqlRecursos .= "       {$sWhere}";
$sSqlRecursos .= " union ";
$sSqlRecursos .= "select distinct o15_codigo,";
$sSqlRecursos .= "       o15_descr ";
$sSqlRecursos .= "  from rhslipfolha";
$sSqlRecursos .= "       inner join orctiporec on rh79_recurso = o15_codigo";
$sSqlRecursos .= " where rh79_anousu =  {$oParam->iAnoFolha}";
$sSqlRecursos .= "   and rh79_mesusu =  {$oParam->iMesFolha}";
$sSqlRecursos .= "       {$sWhereSlip}";
$sSqlRecursos .= " order by o15_codigo";
$rsRecursos    = db_query($sSqlRecursos);
if (pg_num_rows($rsRecursos) > 0) {
  
  $iNumRowsRecursos = pg_num_rows($rsRecursos);
  for ($i = 0; $i < $iNumRowsRecursos; $i++) {
    
    $oRecurso = db_utils::fieldsMemory($rsRecursos, $i);
    $oRecurso->nValorEmpenhos       = 0;
    $oRecurso->nValorSlips          = 0;
    $oRecurso->nValorRetencoes      = 0;
    $oRecurso->nValorDeducaoReceita = 0;
    $oRecurso->nValorBanco          = 0;
    $oRecurso->nValorTotal          = 0;
    $oRecurso->nDiferenca           = 0;
    
    /**
     * Somamos todos os valores pagos em empenhos do recurso
     */
    $sSqlTotalEmpenhos  = "select round(sum(case when rh73_pd = 2 then rh73_valor *-1 when rh73_pd= 1 then rh73_valor end), 2) as valorempenho";
    $sSqlTotalEmpenhos .= "  from rhempenhofolha ";
    $sSqlTotalEmpenhos .= "       inner join rhempenhofolharhemprubrica on rh72_sequencial       = rh81_rhempenhofolha";
    $sSqlTotalEmpenhos .= "       inner join rhempenhofolharubrica on rh81_rhempenhofolharubrica = rh73_sequencial";
    $sSqlTotalEmpenhos .= " where rh72_recurso     = {$oRecurso->o15_codigo} ";
    $sSqlTotalEmpenhos .= "   and rh72_anousu      =  {$oParam->iAnoFolha}";
    $sSqlTotalEmpenhos .= "   and rh72_mesusu      =  {$oParam->iMesFolha}";
    $sSqlTotalEmpenhos .= "   and rh73_tiporubrica = 1";
    $sSqlTotalEmpenhos .= "   and rh72_tipoempenho = 1";
    $sSqlTotalEmpenhos .= "   and rh73_instit      = ".db_getsession('DB_instit');
    $sSqlTotalEmpenhos .= "       {$sWhere}";
    $rsTotalEmpenhos    = db_query($sSqlTotalEmpenhos);
    $oRecurso->nValorEmpenhos = db_utils::fieldsMemory($rsTotalEmpenhos, 0)->valorempenho;
     
    /**
     * Somamos todos os valores pagos em slips do recurso
     */
    $sSqlTotalSlip  = "select round(sum(case when rh73_pd = 2 then rh73_valor *-1  when rh73_pd= 1 then rh73_valor end), 2) as valorslip";
    //$sSqlTotalSlip  = "select round(sum(rh73_valor), 2) as valorslip";
    $sSqlTotalSlip .= "  from rhslipfolha ";
    $sSqlTotalSlip .= "       inner join  rhslipfolharhemprubrica on rh79_sequencial         = rh80_rhslipfolha";
    $sSqlTotalSlip .= "       inner join rhempenhofolharubrica on rh80_rhempenhofolharubrica = rh73_sequencial";
    $sSqlTotalSlip .= " where rh79_recurso     = {$oRecurso->o15_codigo} ";
    $sSqlTotalSlip .= "   and rh79_anousu      =  {$oParam->iAnoFolha}";
    $sSqlTotalSlip .= "   and rh79_mesusu      =  {$oParam->iMesFolha}";
    $sSqlTotalSlip .= "   and rh73_tiporubrica = 3";
    $sSqlTotalSlip .= "   and rh73_instit      = ".db_getsession('DB_instit');
    
    $sSqlTotalSlip .= "       {$sWhereSlip}";
    $rsTotalSlip    = db_query($sSqlTotalSlip);
    $oRecurso->nValorSlips = db_utils::fieldsMemory($rsTotalSlip, 0)->valorslip;
    
    /**
     * Somamos todos os valores pagos em retencoes do recurso
     */
    $sSqlTotalRet  = "select sum(valorRet) as valorRet from ( ";
    $sSqlTotalRet .= "select round(sum(coalesce(rh73_valor, 0)), 2) as valorRet";
    $sSqlTotalRet .= "  from rhempenhofolha ";
    $sSqlTotalRet .= "       inner join rhempenhofolharhemprubrica on rh72_sequencial       = rh81_rhempenhofolha";
    $sSqlTotalRet .= "       inner join rhempenhofolharubrica on rh81_rhempenhofolharubrica = rh73_sequencial";
    $sSqlTotalRet .= "       inner join rhempenhofolharubricaretencao on rh73_sequencial = rh78_rhempenhofolharubrica";
    $sSqlTotalRet .= "       inner join retencaotiporec               on rh78_retencaotiporec = e21_sequencial";
    $sSqlTotalRet .= " where rh72_recurso     = {$oRecurso->o15_codigo} ";
    $sSqlTotalRet .= "   and rh72_anousu      =  {$oParam->iAnoFolha}";
    $sSqlTotalRet .= "   and rh72_mesusu      =  {$oParam->iMesFolha}";
    $sSqlTotalRet .= "   and rh73_tiporubrica = 2                   ";
    $sSqlTotalRet .= "   and rh73_pd          = 2";               
    $sSqlTotalRet .= "   and rh73_instit      = ".db_getsession('DB_instit');
    $sSqlTotalRet .= "   and e21_retencaotiporecgrupo = 2";
    $sSqlTotalRet .= "       {$sWhere} ";
    $sSqlTotalRet .= "   union all ";
    $sSqlTotalRet .= "select round(sum(coalesce(rh73_valor, 0)), 2) as valorRet";
    $sSqlTotalRet .= "  from rhslipfolha ";
    $sSqlTotalRet .= "       inner join rhslipfolharhemprubrica on rh79_sequencial         = rh80_rhslipfolha";
    $sSqlTotalRet .= "       inner join rhempenhofolharubrica on rh80_rhempenhofolharubrica = rh73_sequencial";
    $sSqlTotalRet .= "       inner join rhempenhofolharubricaretencao on rh73_sequencial = rh78_rhempenhofolharubrica";
    $sSqlTotalRet .= "       inner join retencaotiporec               on rh78_retencaotiporec = e21_sequencial";
    $sSqlTotalRet .= " where rh79_recurso     = {$oRecurso->o15_codigo} ";
    $sSqlTotalRet .= "   and rh79_anousu      =  {$oParam->iAnoFolha}";
    $sSqlTotalRet .= "   and rh79_mesusu      =  {$oParam->iMesFolha}";
    $sSqlTotalRet .= "   and rh73_tiporubrica = 2                   ";
    $sSqlTotalRet .= "   and rh73_pd          = 2";               
    $sSqlTotalRet .= "   and rh73_instit      = ".db_getsession('DB_instit');
    $sSqlTotalRet .= "   and e21_retencaotiporecgrupo = 2";
    $sSqlTotalRet .= "       {$sWhereSlip}";
    $sSqlTotalRet .= "   ) as x";
    $rsTotalRet    = db_query($sSqlTotalRet);
    $oRecurso->nValorRetencoes = db_utils::fieldsMemory($rsTotalRet, 0)->valorret;
    
    /**
     * Somamos todos os valores pagos em deduções de receita
     */
    $sSqlTotalDed  = "select round(sum(coalesce(rh73_valor, 0)), 2) as valorded";
    $sSqlTotalDed .= "  from rhempenhofolha ";
    $sSqlTotalDed .= "       inner join rhempenhofolharhemprubrica on rh72_sequencial       = rh81_rhempenhofolha";
    $sSqlTotalDed .= "       inner join rhempenhofolharubrica on rh81_rhempenhofolharubrica = rh73_sequencial";
    $sSqlTotalDed .= "       inner join rhempenhofolharubricaretencao on rh73_sequencial = rh78_rhempenhofolharubrica";
    $sSqlTotalDed .= "       inner join retencaotiporec               on rh78_retencaotiporec = e21_sequencial";
    $sSqlTotalDed .= " where rh72_recurso     = {$oRecurso->o15_codigo} ";
    $sSqlTotalDed .= "   and rh72_anousu      =  {$oParam->iAnoFolha}";
    $sSqlTotalDed .= "   and rh72_mesusu      =  {$oParam->iMesFolha}";
    $sSqlTotalDed .= "   and rh73_tiporubrica = 2                   ";
    $sSqlTotalDed .= "   and rh73_pd          = 2";               
    $sSqlTotalDed .= "   and rh73_instit      = ".db_getsession('DB_instit');
    $sSqlTotalDed .= "   and e21_retencaotiporecgrupo = 4";
    $sSqlTotalDed .= "       {$sWhere}";
    $rsTotalDed    = db_query($sSqlTotalDed);
    $oRecurso->nValorDeducaoReceita   = db_utils::fieldsMemory($rsTotalDed, 0)->valorded;
    
    /**
     * Calculamos os totais da folha
     */
    if ($oParam->sSigla == 'r') {
      $aTiposFolhaCalcular = $aTiposFolha; 
    } else {
      $aTiposFolhaCalcular[$oParam->sSigla] = $aTiposFolha[$oParam->sSigla];
    }
    foreach ($aTiposFolhaCalcular as $aTipoCalculo) {
      
      $sFolha          = $aTipoCalculo["sigla"]; 
      $sTabela         = $aTipoCalculo["tabela"];
      
      $sSqlTotalBanco  = "select ";
      $sSqlTotalBanco .= "       round(sum(case when {$sFolha}_pd = 1 then {$sFolha}_valor ";
      $sSqlTotalBanco .= "                  when {$sFolha}_pd = 2 then {$sFolha}_valor * (-1) end ),2) as valorFolha";  
      $sSqlTotalBanco .= "  from {$sTabela}";
      $sSqlTotalBanco .= "       inner join rhlota  on r70_codigo = to_number({$sFolha}_lotac,'9999') ";
      $sSqlTotalBanco .= "   and r70_instit = {$sFolha}_instit   ";
      $sSqlTotalBanco .= "       inner join (select distinct rh25_codigo,"; 
      $sSqlTotalBanco .= "                          rh25_recurso ";
      $sSqlTotalBanco .= "                     from rhlotavinc where rh25_anousu = {$oParam->iAnoFolha}";
      $sSqlTotalBanco .= "                      ) as rhlotavinc"; 
      $sSqlTotalBanco .= "        on rh25_codigo = r70_codigo ";
      $sSqlTotalBanco .= "  where {$sFolha}_anousu = {$oParam->iAnoFolha}";
      $sSqlTotalBanco .= "    and {$sFolha}_mesusu = {$oParam->iMesFolha}";
      $sSqlTotalBanco .= "    and {$sFolha}_instit = ".db_getsession("DB_instit")." ";
      $sSqlTotalBanco .= "    and {$sFolha}_pd <> 3  ";
      $sSqlTotalBanco .= "  and rh25_recurso = {$oRecurso->o15_codigo}";
      if (isset($oParam->sSemestre)) { 
        if($oParam->sSemestre != '') {
          $sSqlTotalBanco .= " and r48_semest  = {$oParam->sSemestre}";
          
        }
        $sSqlTotalBanco .= " and r48_semest  <>  0";
      }
      $rsTotalBanco    = db_query($sSqlTotalBanco);
      $oRecurso->nValorBanco += db_utils::fieldsMemory($rsTotalBanco, 0)->valorfolha;
    }
    $oRecurso->nValorTotal = ($oRecurso->nValorEmpenhos  + $oRecurso->nValorSlips) - 
                             ($oRecurso->nValorRetencoes + $oRecurso->nValorDeducaoReceita);
    $oRecurso->nDiferenca  = $oRecurso->nValorTotal - $oRecurso->nValorBanco;

    $aRecursos[$oRecurso->o15_codigo] = $oRecurso;
  }
}

switch ($oParam->sSigla) {
	
  case "r14":
	  
		$sPonto = "Salário";
	  break;
	  
  case "r48":
    
  	$sPonto = "Complementar";
    break;
    
  case "r35":
    
    $sPonto = "13o Salário";  	
    break;
    
  case "r20":
    
  	$sPonto = "Rescisão";
    break;
  
  case "r22":
    
  	$sPonto = "Adiantamento";
    break;      
    
  default:
    
    $sPonto = "Todos";
    break;    	
}

switch ($oParam->iTipo) {
	 
  case 1:
    
	  $sTipoPonto = "Salário";
	  break;
	  
  case 2:
    
    $sTipoPonto = "Previdência";
    break;
    
  case 3:
    
    $sTipoPonto = "FGTS";
    break;  	
}

$head2 = "Conferência do Mapa da Folha";
$head3 = "Mês: {$oParam->iMesFolha}";
$head4 = "Ano: {$oParam->iAnoFolha}";
$head5 = "Ponto: {$sPonto}";
//$head6 = "Tipo: {$sTipoPonto}";

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
$pdf->AddPage();
$pdf->setfillcolor(244);
$sFonte = "arial";
$iFonte = 7;
$iAlt   = 4; 
//cabecalhoRelatorio($pdf, $sFonte, $iAlt);
$i = 1;
$nTotalEmpenho   = 0;
$nTotalDescontos = 0;
$nTotalRetencao  = 0;
$nTotalSlip      = 0;
$nTotalBanco     = 0;
$nTotal          = 0;
$nTotalDiferenca = 0;
$pdf->SetFillColor(240);
foreach ($aRecursos as $oRecurso) {
  
  if ($i == 1 || $pdf->GetY() > $pdf->h - 25) {
    cabecalhoRelatorio($pdf, $sFonte, $iAlt);
  }
  
  $pdf->SetFont($sFonte, "", 7);
  $pdf->cell(18, $iAlt, $oRecurso->o15_codigo, "TBR", 0, "R");
  $pdf->cell(50, $iAlt, substr($oRecurso->o15_descr, 0, 30), 1, 0, "L");
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorEmpenhos, "f"), "TBL", 0, "R");  
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorSlips, "f"), "TBL", 0, "R");  
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorRetencoes, "f"), "TBL", 0, "R");  
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorDeducaoReceita, "f"), "TBL", 0, "R");  
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorTotal, "f"), "TBL", 0, "R"); 
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nValorBanco, "f"), "TBL", 0, "R");
  $pdf->cell(30, $iAlt, db_formatar($oRecurso->nDiferenca, "f"), "TBL", 1, "R");
  $i++;
  $nTotal           += $oRecurso->nValorTotal;
  $nTotalBanco      += $oRecurso->nValorBanco;
  $nTotalRetencao   += $oRecurso->nValorRetencoes;
  $nTotalDiferenca  += $oRecurso->nDiferenca;
  $nTotalEmpenho    += $oRecurso->nValorEmpenhos;
  $nTotalSlip       += $oRecurso->nValorSlips;
  $nTotalDescontos  += $oRecurso->nValorDeducaoReceita;
  
}

$pdf->SetFont($sFonte,"b",8);
$pdf->cell(68, $iAlt, "Total", 1, 0, "R", 1);
$pdf->cell(30, $iAlt, db_formatar($nTotalEmpenho, "f"), "TBL", 0, "R", 1);  
$pdf->cell(30, $iAlt, db_formatar($nTotalSlip, "f"), "TBL", 0, "R", 1);  
$pdf->cell(30, $iAlt, db_formatar($nTotalRetencao, "f"), "TBL", 0, "R", 1);  
$pdf->cell(30, $iAlt, db_formatar($nTotalDescontos, "f"), "TBL", 0, "R", 1);  
$pdf->cell(30, $iAlt, db_formatar($nTotal, "f"), "TBL", 0, "R", 1); 
$pdf->cell(30, $iAlt, db_formatar($nTotalBanco, "f"), "TBL", 0, "R", 1);
$pdf->cell(30, $iAlt, db_formatar($nTotalDiferenca, "f"), "TBL", 0, "R", 1);  
$pdf->Output();

function cabecalhoRelatorio (&$pdf, $sFonte, $iAlt) {

  $pdf->SetFont($sFonte,"b",8);
  $pdf->cell(18, $iAlt, "Código", "TBR", 0, "C", 1);
  $pdf->cell(50, $iAlt, "Recurso", 1, 0, "C", 1);
  $pdf->cell(30, $iAlt, "Empenhos", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Desp. Extra", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Receita", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Ded. Receita", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Total", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Banco", "TBL", 0, "C", 1);  
  $pdf->cell(30, $iAlt, "Diferença", "TBL", 1, "C", 1);  
}

?>