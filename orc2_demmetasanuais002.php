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
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_config_classe.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_orccenarioeconomicoparam_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("model/relatorioContabil.model.php");
require_once("std/db_stdClass.php");
require_once("model/ppa.model.php");
require_once("model/ppaReceita.model.php");
require_once("model/ppadespesa.model.php");
require_once("model/ppaVersao.model.php");

$oGet	    = db_utils::postMemory($_GET);
$oPPAVersao = new ppaVersao($oGet->iCodVersao);
// Código do Relatório
$iCodRel = $oGet->iCodRel;

// Lista das instituições selecionadas
$sListaInstit = str_replace('-',',',$oGet->sListaInstit);

$cldb_config                = new cl_db_config;
$clorccenarioeconomicoparam = new cl_orccenarioeconomicoparam();
$oRelatorioContabil         = new relatorioContabil($iCodRel);


// Objetos referente as linhas do Relatório
$oValorReceitaTotal             = new linhaRelatorioContabil($iCodRel,1);
$oValorReceitasPrimarias        = new linhaRelatorioContabil($iCodRel,2);
$oValorDespesaTotal             = new linhaRelatorioContabil($iCodRel,3);
$oValorDespesasPrimarias        = new linhaRelatorioContabil($iCodRel,4);
$oValorResultadoPrimario        = new linhaRelatorioContabil($iCodRel,5);
$oValorResultadoNominal         = new linhaRelatorioContabil($iCodRel,6);
$oValorDivPublicConsol          = new linhaRelatorioContabil($iCodRel,7);
$oValorDivConsolLiquid          = new linhaRelatorioContabil($iCodRel,8);
$oValorReceitaPrimariaAdvindas  = new linhaRelatorioContabil($iCodRel,9);
$oValorDespesasPrimariasGeradas = new linhaRelatorioContabil($iCodRel,10);
$oValorImpactoSaldo             = new linhaRelatorioContabil($iCodRel,11);

// Busca valores digitados manualmente para cada linha e coluna
$aValorReceitaTotal             = $oValorReceitaTotal->getValoresColunas();
$aValorReceitasPrimarias        = $oValorReceitasPrimarias->getValoresColunas();
$aValorDespesaTotal             = $oValorDespesaTotal->getValoresColunas();
$aValorDespesasPrimarias        = $oValorDespesasPrimarias->getValoresColunas();
$aValorResultadoPrimario        = $oValorResultadoPrimario->getValoresColunas();
$aValorResultadoNominal         = $oValorResultadoNominal->getValoresColunas();
$aValorDivPublicConsol          = $oValorDivPublicConsol->getValoresColunas();
$aValorDivConsolLiquid          = $oValorDivConsolLiquid->getValoresColunas();

$aValorReceitaPrimariaAdvindas  = $oValorReceitaPrimariaAdvindas->getValoresColunas();
$aValorDespesasPrimariasGeradas = $oValorDespesasPrimariasGeradas->getValoresColunas();
$aValorImpactoSaldo             = $oValorImpactoSaldo->getValoresColunas();

// Define todos anos utilizados no relatório apartir do ano de referência
$iAnoRef = db_getsession("DB_anousu");
$iAno1   = $iAnoRef+1;
$iAno2   = $iAnoRef+2;
$iAno3   = $iAnoRef+3;

//Lista todos Anos
$aListaAnos = array($iAno1,$iAno2,$iAno3);


// Cria objeto valor para cada ano de cada linha
$oValoresRel = new stdClass();
$oValoresRel->Corrente  = 0;
$oValoresRel->Constante = 0;
$oValoresRel->PIB       = 0;

$oReceitaTotal = new stdClass();
$oReceitaTotal->Descricao  = "";
$oReceitaTotal->aValores   = array();

// Cria objeto para cada linha do relatório
$oReceitasPrimarias        = clone $oReceitaTotal;
$oDespesaTotal             = clone $oReceitaTotal;
$oDespesasPrimarias        = clone $oReceitaTotal;
$oResultadoPrimario        = clone $oReceitaTotal;
$oResultadoNominal         = clone $oReceitaTotal;
$oDivPublicConsol          = clone $oReceitaTotal;
$oDivConsolLiquid          = clone $oReceitaTotal;

$oReceitaPrimariaAdvindas  = clone $oReceitaTotal;
$oDespesasPrimariasGeradas = clone $oReceitaTotal;
$oImpactoSaldo             = clone $oReceitaTotal;


// Cria array com todos objetos "Linhas" do relatório
$aLista = array( $oReceitaTotal,
                 $oReceitasPrimarias,
                 $oDespesaTotal,
                 $oDespesasPrimarias,
                 $oResultadoPrimario,
                 $oResultadoNominal,
                 $oDivPublicConsol,
                 $oDivConsolLiquid,
								 $oReceitaPrimariaAdvindas,
								 $oDespesasPrimariasGeradas,
								 $oImpactoSaldo
		);

// Cria dinâmicamente o objeto valor para cada linha evitando referência de objetos
foreach ( $aLista as $iInd => $oLinha ){
	foreach ( $aListaAnos as $iIndAno => $iAno ) {
		${"oValoresRel".$iInd}   = clone $oValoresRel;
    $oLinha->aValores[$iAno] = ${"oValoresRel".$iInd};
	}
}

// Seta descrição de cada linha
$oReceitaTotal->Descricao              = $oValorReceitaTotal->getDescricaoLinha();
$oReceitasPrimarias->Descricao         = $oValorReceitasPrimarias->getDescricaoLinha();
$oDespesaTotal->Descricao              = $oValorDespesaTotal->getDescricaoLinha();
$oDespesasPrimarias->Descricao         = $oValorDespesasPrimarias->getDescricaoLinha();
$oResultadoPrimario->Descricao         = $oValorResultadoPrimario->getDescricaoLinha();
$oResultadoNominal->Descricao          = $oValorResultadoNominal->getDescricaoLinha();
$oDivPublicConsol->Descricao           = $oValorDivPublicConsol->getDescricaoLinha();
$oDivConsolLiquid->Descricao           = $oValorDivConsolLiquid->getDescricaoLinha();
$oReceitaPrimariaAdvindas->Descricao   = $oValorReceitaPrimariaAdvindas->getDescricaoLinha();
$oDespesasPrimariasGeradas->Descricao  = $oValorDespesasPrimariasGeradas->getDescricaoLinha();
$oImpactoSaldo->Descricao              = $oValorImpactoSaldo->getDescricaoLinha();


// Busca PIB de cada ano

$sCamposDadosPIB  = " o03_anoreferencia,                          ";
$sCamposDadosPIB .= " sum(o03_valorparam) as valor                ";

$sWhereDadosPIB   = "     o02_orccenarioeconomicogrupo = 3        ";
$sWhereDadosPIB  .= " and o03_tipovalor                = 2        ";
$sWhereDadosPIB  .= " and o03_anoreferencia            between {$iAnoRef} and {$iAno3}";
$sWhereDadosPIB  .= " and o03_instit                   = ".db_getsession('DB_instit');
$sWhereDadosPIB  .= " group by o03_anoreferencia                  ";

$sSqlDadosPIB     = $clorccenarioeconomicoparam->sql_query(null,$sCamposDadosPIB,null,$sWhereDadosPIB);
$rsDadosPIB       = $clorccenarioeconomicoparam->sql_record($sSqlDadosPIB);
$iLinhasDadosPIB  = $clorccenarioeconomicoparam->numrows;

if ( $iLinhasDadosPIB > 0 ) {
	for( $iInd=0; $iInd < $iLinhasDadosPIB; $iInd++ ) {
		$oDadosPIB = db_utils::fieldsMemory($rsDadosPIB,$iInd);
		$aPIB[$oDadosPIB->o03_anoreferencia] = $oDadosPIB->valor;
	}
}

if ( !array_key_exists($iAno1,$aPIB) ) {
	db_redireciona("db_erros.php?fechar=true&db_erro=1 - Valor do PIB do Cenário Macroeconômico para o exercício $iAno1 não informado!");
}

if ( !array_key_exists($iAno2,$aPIB) ) {
	db_redireciona("db_erros.php?fechar=true&db_erro=1 - Valor do PIB do Cenário Macroeconômico para o exercício $iAno2 não informado!");
}

if ( !array_key_exists($iAno3,$aPIB)) {
	db_redireciona("db_erros.php?fechar=true&db_erro=1 - Valor do PIB do Cenário Macroeconômico para o exercício $iAno3 não informado!");
}


// Busca taxa de inflação de cada ano
$sCamposDadosTaxaInf  = " o03_anoreferencia,                          ";
$sCamposDadosTaxaInf .= " sum(o03_valorparam) as valor                ";

$sWhereDadosTaxaInf   = "     o02_orccenarioeconomicogrupo = 2        ";
$sWhereDadosTaxaInf  .= " and o03_anoreferencia            between {$iAnoRef} and {$iAno3}";
$sWhereDadosTaxaInf  .= " and o03_instit                   = ".db_getsession('DB_instit');
$sWhereDadosTaxaInf  .= " group by o03_anoreferencia                  ";

$sSqlDadosTaxaInf     = $clorccenarioeconomicoparam->sql_query(null,$sCamposDadosTaxaInf,null,$sWhereDadosTaxaInf);
$rsDadosTaxaInf       = $clorccenarioeconomicoparam->sql_record($sSqlDadosTaxaInf);
$iLinhasDadosTaxaInf  = $clorccenarioeconomicoparam->numrows;

if ( $iLinhasDadosTaxaInf > 0 ) {
	for ( $iInd=0; $iInd < $iLinhasDadosTaxaInf; $iInd++ ) {
		$oDadosTaxaInf = db_utils::fieldsMemory($rsDadosTaxaInf,$iInd);
    $aValTaxaDefla[$oDadosTaxaInf->o03_anoreferencia] = 1 + ($oDadosTaxaInf->valor/100);
	}
}

if ( !array_key_exists($iAno1,$aValTaxaDefla) ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Valor das taxa de inflação do Cenário Macroeconômico para o exercício $iAno1 não informado!");
}

if ( !array_key_exists($iAno2,$aValTaxaDefla) ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Valor das taxa de inflação do Cenário Macroeconômico para o exercício $iAno2 não informado!");
}

if ( !array_key_exists($iAno3,$aValTaxaDefla) ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Valor das taxa de inflação do Cenário Macroeconômico para o exercício $iAno3 não informado!");
}

// Calcula taxa de deflação de cada ano
$aTaxaDefla[$iAno1] = $aValTaxaDefla[$iAno1];
$aTaxaDefla[$iAno2] = $aTaxaDefla[$iAno1] * $aValTaxaDefla[$iAno2];
$aTaxaDefla[$iAno3] = $aTaxaDefla[$iAno2] * $aValTaxaDefla[$iAno3];


$oPPAReceita = new ppaReceita($oGet->iCodVersao);
$oPPADespesa = new ppaDespesa($oGet->iCodVersao);

$oPPAReceita->setInstituicoes($sListaInstit);
$oPPADespesa->setInstituicoes($sListaInstit);


// Busca todos dados da Receita
try {
  $aEstimativaReceita = $oPPAReceita->getQuadroEstimativas();
} catch (Exception $eException ){
  $aEstimativaReceita = array();
}


// Busca todos dados da Despesa
try {
  $aEstimativaDespesa = $oPPADespesa->getQuadroEstimativas("",7);
} catch (Exception $eException ){
  $aEstimativaDespesa = array();
}


foreach ( $aListaAnos as $iIndAno => $iAno ) {
  $aExcecaoPrimaria[$iAno] = 0 ;
}

// Busca valores "Corrente" da Receita para cada ano
foreach ( $aEstimativaReceita as $iInd => $oReceita ){

	if ( substr($oReceita->iEstrutural,0,4) == 4000 || substr($oReceita->iEstrutural,0,4) == 9000 ) {

		foreach ( $aListaAnos as $iIndAno => $iAno ) {
			$oReceitaTotal->aValores[$iAno]->Corrente += $oReceita->aEstimativas[$iAno];
		}
  }
  if ( $oReceita->iEstrutural == 413250000000000 ||
       $oReceita->iEstrutural == 421000000000000 ||
       $oReceita->iEstrutural == 423000000000000 ||
       $oReceita->iEstrutural == 422000000000000 ) {

    foreach ( $aListaAnos as $iIndAno => $iAno ) {
    	$aExcecaoPrimaria[$iAno] += $oReceita->aEstimativas[$iAno];
    }
  }
}

foreach ( $aListaAnos as $iIndAno => $iAno ) {
	$oReceitasPrimarias->aValores[$iAno]->Corrente += $oReceitaTotal->aValores[$iAno]->Corrente - $aExcecaoPrimaria[$iAno];
}

// Busca valores "Corrente" da Despesa para cada ano
foreach ( $aEstimativaDespesa as $iInd => $oDespesa ) {

  if ( $oDespesa->iElemento{0} == 3 ) {

  	foreach ( $aListaAnos as $iIndAno => $iAno ) {
  		$oDespesaTotal->aValores[$iAno]->Corrente += $oDespesa->aEstimativas[$iAno];
    }

  }
  if ( substr($oDespesa->iElemento,0,3) != 332 &&
       substr($oDespesa->iElemento,0,3) != 346 ) {

    foreach ( $aListaAnos as $iIndAno => $iAno ) {
    	$oDespesasPrimarias->aValores[$iAno]->Corrente += $oDespesa->aEstimativas[$iAno];
    }
  }
}

// Soma valores digitados manualmente as suas respectivas linhas e colunas

foreach ( $aValorReceitaTotal as $iInd => $oLinhaManual ){
  $oReceitaTotal->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oReceitaTotal->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oReceitaTotal->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oReceitaTotal->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oReceitaTotal->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oReceitaTotal->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorReceitasPrimarias as $iInd => $oLinhaManual ){
  $oReceitasPrimarias->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oReceitasPrimarias->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oReceitasPrimarias->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oReceitasPrimarias->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oReceitasPrimarias->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oReceitasPrimarias->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorDespesaTotal as $iInd => $oLinhaManual ){
  $oDespesaTotal->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oDespesaTotal->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oDespesaTotal->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oDespesaTotal->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oDespesaTotal->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oDespesaTotal->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorDespesasPrimarias as $iInd => $oLinhaManual ){
  $oDespesasPrimarias->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oDespesasPrimarias->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oDespesasPrimarias->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oDespesasPrimarias->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oDespesasPrimarias->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oDespesasPrimarias->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorResultadoPrimario as $iInd =>   $oLinhaManual ){
  $oResultadoPrimario->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oResultadoPrimario->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oResultadoPrimario->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oResultadoPrimario->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oResultadoPrimario->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oResultadoPrimario->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorResultadoNominal as $iInd => $oLinhaManual ){
  $oResultadoNominal->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oResultadoNominal->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oResultadoNominal->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oResultadoNominal->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oResultadoNominal->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oResultadoNominal->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorDivPublicConsol as $iInd => $oLinhaManual ){
  $oDivPublicConsol->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oDivPublicConsol->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oDivPublicConsol->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oDivPublicConsol->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oDivPublicConsol->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oDivPublicConsol->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorDivConsolLiquid as $iInd => $oLinhaManual ){
  $oDivConsolLiquid->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
  $oDivConsolLiquid->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
  $oDivConsolLiquid->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
  $oDivConsolLiquid->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
  $oDivConsolLiquid->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
  $oDivConsolLiquid->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorReceitaPrimariaAdvindas as $iInd => $oLinhaManual ){
	$oReceitaPrimariaAdvindas->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
	$oReceitaPrimariaAdvindas->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
	$oReceitaPrimariaAdvindas->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
	$oReceitaPrimariaAdvindas->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
	$oReceitaPrimariaAdvindas->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
	$oReceitaPrimariaAdvindas->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorDespesasPrimariasGeradas as $iInd => $oLinhaManual ){
	$oDespesasPrimariasGeradas->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
	$oDespesasPrimariasGeradas->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
	$oDespesasPrimariasGeradas->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
	$oDespesasPrimariasGeradas->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
	$oDespesasPrimariasGeradas->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
	$oDespesasPrimariasGeradas->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}

foreach ( $aValorImpactoSaldo as $iInd => $oLinhaManual ){
	$oImpactoSaldo->aValores[$iAno1]->Corrente  += $oLinhaManual->colunas[0]->o117_valor;
	$oImpactoSaldo->aValores[$iAno1]->Constante += $oLinhaManual->colunas[1]->o117_valor;
	$oImpactoSaldo->aValores[$iAno2]->Corrente  += $oLinhaManual->colunas[2]->o117_valor;
	$oImpactoSaldo->aValores[$iAno2]->Constante += $oLinhaManual->colunas[3]->o117_valor;
	$oImpactoSaldo->aValores[$iAno3]->Corrente  += $oLinhaManual->colunas[4]->o117_valor;
	$oImpactoSaldo->aValores[$iAno3]->Constante += $oLinhaManual->colunas[5]->o117_valor;
}
/*
$oImpactoSaldo
*/


foreach ( $aListaAnos as $iInd => $iAno ) {
	$oResultadoPrimario->aValores[$iAno]->Corrente += $oReceitasPrimarias->aValores[$iAno]->Corrente -
	                                                  $oDespesasPrimarias->aValores[$iAno]->Corrente;
}

foreach ( $aLista as $iInd => $oLinha ){
  foreach ( $aListaAnos as $iIndAno => $iAno ){
     $oLinha->aValores[$iAno]->Constante += $oLinha->aValores[$iAno]->Corrente/$aTaxaDefla[$iAno];
     $oLinha->aValores[$iAno]->PIB       += ($oLinha->aValores[$iAno]->Corrente/$aPIB[$iAno])*100;
  }
}

$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit')));
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
}
$head2 = "MUNICÍPIO DE ".$oConfig->munic;
$head3 = $sModelo;
$head4 = "ANEXO DE  METAS FISCAIS";
$head5 = "METAS ANUAIS";
$head6 = $iAno1;
$head7 = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";

$pdf = new PDF('L');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();

$iAlt  = 4;
$iFont = 7;


$pdf->setfont('arial','',$iFont);

$pdf->Cell(170,$iAlt,"AMF - Demonstrativo I (LRF, art. 4º, § 1º)",0,0,"L",0);
$pdf->Cell(0  ,$iAlt,"R$ 1,00"										               ,0,1,"R",0);

imprimeCabecalho($pdf,$iAlt,$iFont,$iAno1);


foreach ( $aLista as $iInd => $oLinha ){

	$pdf->cell(55,$iAlt,$oLinha->Descricao,'R',0,'L',0);

	foreach ( $aListaAnos as $iIndAno => $iAno ) {
	  $pdf->cell(25,$iAlt,db_formatar($oLinha->aValores[$iAno]->Corrente,'f') ,'L',0,'R',0);
	  $pdf->cell(25,$iAlt,db_formatar($oLinha->aValores[$iAno]->Constante,'f'),'L',0,'R',0);
	  $pdf->cell(25,$iAlt,db_formatar($oLinha->aValores[$iAno]->PIB,'f')      ,'L',0,'R',0);
	}

	$pdf->Ln();

}

$pdf->cell(0,$iAlt,"",'T',1,'R',0);

// Imprime Notas Explicativas
$oRelatorioContabil->getNotaExplicativa($pdf,1,190);

$pdf->Output();


function imprimeCabecalho($pdf,$iAlt,$iFont,$iAno1){

  $pdf->SetFont('Arial','B',$iFont);

	$pdf->Cell(55,$iAlt*3,"ESPECIFICAÇÃO" ,'TRB',0,"C",0);
	$pdf->Cell(75,$iAlt  ,$iAno1          ,    1,0,"C",0);
	$pdf->Cell(75,$iAlt  ,$iAno1+1        ,    1,0,"C",0);
	$pdf->Cell(75,$iAlt  ,$iAno1+2        ,'TLB',1,"C",0);

	$pdf->SetX(65);

	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'% PIB' ,'TR',0,"C",0);

	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'% PIB' ,'TR',0,"C",0);

	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'% PIB' ,'TL',1,"C",0);

	$pdf->SetX(65);

	$pdf->Cell(25,$iAlt  ,'Corrente ( a )'   ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Constante'        ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'( a / PIB ) x 100','BR',0,"C",0);

	$pdf->Cell(25,$iAlt  ,'Corrente ( b )'   ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Constante'        ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'( b / PIB ) x 100','BR',0,"C",0);

	$pdf->Cell(25,$iAlt  ,'Corrente ( c )'   ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'Constante'        ,'BR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'( c / PIB ) x 100','BL',1,"C",0);

	$pdf->SetFont('Arial','',$iFont);

}

?>