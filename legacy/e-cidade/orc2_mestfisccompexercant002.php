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

// Código do relatório
$iCodRel = $oGet->iCodRel;

// Lista todas intituições selecionadas 
$sListaInstit = str_replace('-',',',$oGet->sListaInstit);

$cldb_config                = new cl_db_config; 
$clorccenarioeconomicoparam = new cl_orccenarioeconomicoparam();
$oRelataorioContabil        = new relatorioContabil($iCodRel);

// Objetos referente as linhas do Relatório
$oValorReceitaTotalCorr         = new linhaRelatorioContabil($iCodRel,1);
$oValorReceitasPrimariasCorr    = new linhaRelatorioContabil($iCodRel,2);
$oValorDespesaTotalCorr         = new linhaRelatorioContabil($iCodRel,3);
$oValorDespesasPrimariasCorr    = new linhaRelatorioContabil($iCodRel,4);
$oValorResultadoPrimarioCorr    = new linhaRelatorioContabil($iCodRel,5);
$oValorResultadoNominalCorr     = new linhaRelatorioContabil($iCodRel,6);
$oValorDivPublicConsolCorr      = new linhaRelatorioContabil($iCodRel,7);
$oValorDivConsolLiquidCorr      = new linhaRelatorioContabil($iCodRel,8);
$oValorReceitaTotalConst        = new linhaRelatorioContabil($iCodRel,9);
$oValorReceitasPrimariasConst   = new linhaRelatorioContabil($iCodRel,10);
$oValorDespesaTotalConst        = new linhaRelatorioContabil($iCodRel,11);
$oValorDespesasPrimariasConst   = new linhaRelatorioContabil($iCodRel,12);
$oValorResultadoPrimarioConst   = new linhaRelatorioContabil($iCodRel,13);
$oValorResultadoNominalConst    = new linhaRelatorioContabil($iCodRel,14);
$oValorDivPublicConsolConst     = new linhaRelatorioContabil($iCodRel,15);
$oValorDivConsolLiquidConst     = new linhaRelatorioContabil($iCodRel,16);

// Busca valores digitados manualmente para cada linha e coluna
$aValorReceitaTotalCorr      = $oValorReceitaTotalCorr->getValoresColunas();          
$aValorReceitasPrimariasCorr = $oValorReceitasPrimariasCorr->getValoresColunas();     
$aValorDespesaTotalCorr      = $oValorDespesaTotalCorr->getValoresColunas();          
$aValorDespesasPrimariasCorr = $oValorDespesasPrimariasCorr->getValoresColunas();     
$aValorResultadoPrimarioCorr = $oValorResultadoPrimarioCorr->getValoresColunas();     
$aValorResultadoNominalCorr  = $oValorResultadoNominalCorr->getValoresColunas();
$aValorDivPublicConsolCorr   = $oValorDivPublicConsolCorr->getValoresColunas();
$aValorDivConsolLiquidCorr   = $oValorDivConsolLiquidCorr->getValoresColunas();

$aValorReceitaTotalConst      = $oValorReceitaTotalConst->getValoresColunas();          
$aValorReceitasPrimariasConst = $oValorReceitasPrimariasConst->getValoresColunas();     
$aValorDespesaTotalConst      = $oValorDespesaTotalConst->getValoresColunas();          
$aValorDespesasPrimariasConst = $oValorDespesasPrimariasConst->getValoresColunas();     
$aValorResultadoPrimarioConst = $oValorResultadoPrimarioConst->getValoresColunas();     
$aValorResultadoNominalConst  = $oValorResultadoNominalConst->getValoresColunas();
$aValorDivPublicConsolConst   = $oValorDivPublicConsolConst->getValoresColunas();
$aValorDivConsolLiquidConst   = $oValorDivConsolLiquidConst->getValoresColunas();


// Define todos anos utilizados no relatório apartir do ano de referência 
$iAnoRef = db_getsession("DB_anousu") + 1 ; 
$iAno1   = $iAnoRef-3;
$iAno2   = $iAnoRef-2;
$iAno3   = $iAnoRef-1;
$iAno4   = $iAnoRef;
$iAno5   = $iAnoRef+1;
$iAno6   = $iAnoRef+2;

// Lista anos anteriores ao Ano de Referência
$aListaAnosAnt   = array($iAno1,$iAno2,$iAno3);

// Lista anos posteriores ao Ano de Referência
$aListaAnosPPA   = array($iAno4,$iAno5,$iAno6);

// Lista todos anos
$aListaTodosAnos = array($iAno1,$iAno2,$iAno3,$iAno4,$iAno5,$iAno6);

// Cria objeto para cada ano de cada linha
$oValoresRel = new stdClass();
$oValoresRel->nCorrente  = 0;
$oValoresRel->nConstante = 0;

$oReceitaTotal = new stdClass();
$oReceitaTotal->sDescricao = "";
$oReceitaTotal->aValores   = array(); 

// Cria objeto para cada linha do relatório
$oReceitasPrimarias = clone $oReceitaTotal;
$oDespesaTotal      = clone $oReceitaTotal;
$oDespesasPrimarias = clone $oReceitaTotal;
$oResultadoPrimario = clone $oReceitaTotal;
$oResultadoNominal  = clone $oReceitaTotal;
$oDivPublicConsol   = clone $oReceitaTotal;
$oDivConsolLiquid   = clone $oReceitaTotal;

// Cria array com todos objetos "Linhas" do relatório
$aLista = array( $oReceitaTotal,
                 $oReceitasPrimarias,
                 $oDespesaTotal,
                 $oDespesasPrimarias,
                 $oResultadoPrimario,
                 $oResultadoNominal,
                 $oDivPublicConsol,                 
                 $oDivConsolLiquid );

// Cria dinâmicamente o objeto valor para cada linha evitando referência de objetos                 
foreach ( $aLista as $iInd => $oLinha ){
	foreach ( $aListaTodosAnos as $iIndAno => $iAno ) {
		${"oValoresRel".$iInd}   = clone $oValoresRel;
    $oLinha->aValores[$iAno] = ${"oValoresRel".$iInd}; 		
	}
}

// Seta descrição de cada linha
$oReceitaTotal->sDescricao      = "Receita Total"; 
$oReceitasPrimarias->sDescricao = "Receitas Primárias(I)";   
$oDespesaTotal->sDescricao      = "Despesa Total"; 
$oDespesasPrimarias->sDescricao = "Despesas Primárias(II)"; 
$oResultadoPrimario->sDescricao = "Resultado Primário(III) = (I-II)"; 
$oResultadoNominal->sDescricao  = "Resultado Nominal"; 
$oDivPublicConsol->sDescricao   = "Dívida Pública Consolidada";
$oDivConsolLiquid->sDescricao   = "Dívida Consolidada Líquida";
 

// Busca taxa de inflação de cada ano
$sCamposDadosTaxaInf  = " o03_anoreferencia,                            ";
$sCamposDadosTaxaInf .= " sum(o03_valorparam) as valor                  ";

$sWhereDadosTaxaInf   = "     o02_orccenarioeconomicogrupo = 2          ";
$sWhereDadosTaxaInf  .= " and o03_anoreferencia between {$iAno1} and {$iAno6} ";
$sWhereDadosTaxaInf  .= " and o03_instit       = ".db_getsession('DB_instit');
$sWhereDadosTaxaInf  .= " group by o03_anoreferencia                    ";

$sSqlDadosTaxaInf     = $clorccenarioeconomicoparam->sql_query(null,$sCamposDadosTaxaInf,"o03_anoreferencia",$sWhereDadosTaxaInf);
$rsDadosTaxaInf       = $clorccenarioeconomicoparam->sql_record($sSqlDadosTaxaInf);
$iLinhasDadosTaxaInf  = $clorccenarioeconomicoparam->numrows;

if ( $iLinhasDadosTaxaInf > 0 ) {
  for ( $iInd=0; $iInd < $iLinhasDadosTaxaInf; $iInd++ ) {
    $oDadosTaxaInf = db_utils::fieldsMemory($rsDadosTaxaInf,$iInd);
    $aValTaxaDefla[$oDadosTaxaInf->o03_anoreferencia] = 1 + ($oDadosTaxaInf->valor/100);
  }   
}

foreach ( $aListaTodosAnos as $iIndAno => $iAno ){
	if ( !array_key_exists($iAno,$aValTaxaDefla)) {
		$sMsgErro = "Não consta informação sobre o valor da taxa de inflação no cenário macroeconômico para o ano {$iAno}!";
	  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
	}
}


// Calcula taxa de deflação de cada ano

foreach ($aListaAnosAnt as $iIndAno => $iAno ){
  $aTaxaDefla[$iAno] = $aValTaxaDefla[$iAno];
} 
$aTaxaDefla[$iAno4] = $aValTaxaDefla[$iAno4]; 
$aTaxaDefla[$iAno5] = $aTaxaDefla[$iAno4] * $aValTaxaDefla[$iAno5];
$aTaxaDefla[$iAno6] = $aTaxaDefla[$iAno5] * $aValTaxaDefla[$iAno6];


foreach ( $aListaTodosAnos as $iIndAno => $iAno ) {
  $aExcecaoPrimaria[$iAno] = 0 ;
}


foreach ( $aListaAnosAnt as $iIndAno => $iAno) {
	
	
	$dtDataIni      = "{$iAno}-01-01";
	
	if ( $iAno == db_getsession('DB_anousu') ) {
		
    $iMesFechado = date('m',db_getsession('DB_datausu')) - 1 ;		
 		$iMesFechado = str_pad($iMesFechado,2,"0",STR_PAD_LEFT);
    $iDias       = db_dias_mes($iAno,$iMesFechado);        
    $dtDataFin = "{$iAno}-{$iMesFechado}-{$iDias}";
    		
	} else {
    $dtDataFin = "{$iAno}-12-31";
	}
	
  // Calcula Receita Corrente de todos os anos anteriores ao de referência
  $sWhereReceita  = " o70_instit in ({$sListaInstit})";
	$rsReceita      = db_receitasaldo(11,1,3,true,$sWhereReceita,$iAno,$dtDataIni,$dtDataFin,false);
	$iLinhasReceita = pg_num_rows($rsReceita);
	
	db_query("drop table work_receita;");
	
	for ( $iInd=0; $iInd < $iLinhasReceita; $iInd++ ) {
	  
	  $oReceita = db_utils::fieldsMemory($rsReceita,$iInd);
	  
	  if ( $oReceita->o57_fonte == 400000000000000 || $oReceita->o57_fonte == 900000000000000 ) {
	    $oReceitaTotal->aValores[$iAno]->nCorrente += $oReceita->saldo_arrecadado_acumulado;
	  } 
	  
	  if ( $oReceita->o57_fonte == 413250000000000 || 
	       $oReceita->o57_fonte == 421000000000000 ||
	       $oReceita->o57_fonte == 423000000000000 ||
	       $oReceita->o57_fonte == 422000000000000 ) {

       $aExcecaoPrimaria[$iAno] += $oReceita->saldo_arrecadado_acumulado;
	     
	  }   
  }
  
  // Calcula Despesa Corrente de todos os anos anteriores ao de referência
	$sWhereDespesa  = " w.o58_instit in ({$sListaInstit})";
	$rsDespesa      = db_dotacaosaldo(8,2,3,true,$sWhereDespesa,$iAno,$dtDataIni,$dtDataFin);
	$iLinhasDespesa = pg_num_rows($rsDespesa);
	
	for ( $iInd=0; $iInd < $iLinhasDespesa; $iInd++ ) {
	  
	  $oDespesa = db_utils::fieldsMemory($rsDespesa,$iInd);
	  
	  if ( $oDespesa->o58_elemento{0} == 3 ) {
	    $oDespesaTotal->aValores[$iAno]->nCorrente += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado;
	  } 
	    
	  if ( substr($oDespesa->o58_elemento,0,3) != 332 && 
	       substr($oDespesa->o58_elemento,0,3) != 346 ) {
	      
	    $oDespesasPrimarias->aValores[$iAno]->nCorrente += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado;
	      
	  }   
	}
	
	if ( $iAno == db_getsession('DB_anousu') ) {
    $oReceitaTotal->aValores[$iAno]->nCorrente      = ($oReceitaTotal->aValores[$iAno]->nCorrente/$iMesFechado)*12; 
    $oDespesaTotal->aValores[$iAno]->nCorrente      = ($oDespesaTotal->aValores[$iAno]->nCorrente/$iMesFechado)*12;
    $aExcecaoPrimaria[$iAno]                        = ($aExcecaoPrimaria[$iAno]/$iMesFechado)*12;		
    $oDespesasPrimarias->aValores[$iAno]->nCorrente = ($oDespesasPrimarias->aValores[$iAno]->nCorrente/$iMesFechado)*12;
	}
	
}

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
  $aEstimativaDespesa = $oPPADespesa->getQuadroEstimativas(null,7);
} catch (Exception $eException ){
  $aEstimativaDespesa = array();
} 


foreach ($aListaAnosPPA as $iIndAno => $iAno ) {
	   
	// Calcula Receita Corrente de todos os anos posteriores ao de referência
	foreach ( $aEstimativaReceita as $iInd => $oReceita ) {
		
	  if ( substr($oReceita->iEstrutural,0,4) == 4000 || substr($oReceita->iEstrutural,0,4) == 9000 ) {
      $oReceitaTotal->aValores[$iAno]->nCorrente += $oReceita->aEstimativas[$iAno];
	  } 
	  
	  if ( $oReceita->iEstrutural == 413250000000000 || 
	       $oReceita->iEstrutural == 421000000000000 ||
	       $oReceita->iEstrutural == 423000000000000 ||
	       $oReceita->iEstrutural == 422000000000000 ) {
	       	
	    $aExcecaoPrimaria[$iAno] += $oReceita->aEstimativas[$iAno];
	    
	  }
	  
	}

  // Calcula Despesa Corrente de todos os anos posteriores ao de referência
	foreach ( $aEstimativaDespesa as $iInd => $oDespesa ) {
	  
	  if ( $oDespesa->iElemento{0} == 3 ) {
      $oDespesaTotal->aValores[$iAno]->nCorrente += $oDespesa->aEstimativas[$iAno];
	  } 
	  
	  if ( substr($oDespesa->iElemento,0,3) != 332 && 
	       substr($oDespesa->iElemento,0,3) != 346 ) {
      $oDespesasPrimarias->aValores[$iAno]->nCorrente += $oDespesa->aEstimativas[$iAno];     
	  }   
	  
	}
	
}


// Soma valores digitados manualmente as suas respectivas linhas e colunas 

  foreach ( $aValorReceitaTotalCorr as $iInd => $oLinhaManual ){
    $oReceitaTotal->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oReceitaTotal->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;
    $oReceitaTotal->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oReceitaTotal->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oReceitaTotal->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oReceitaTotal->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;
  }
  
  foreach ( $aValorReceitasPrimariasCorr as $iInd => $oLinhaManual ){
    $oReceitasPrimarias->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;    
    $oReceitasPrimarias->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;
  }
  
  foreach ( $aValorDespesaTotalCorr as $iInd => $oLinhaManual ){
    $oDespesaTotal->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oDespesaTotal->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;
    $oDespesaTotal->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oDespesaTotal->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oDespesaTotal->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oDespesaTotal->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;                    
  }
  
  foreach ( $aValorDespesasPrimariasCorr as $iInd => $oLinhaManual ){
    $oDespesasPrimarias->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;                    
  }
  
  foreach ( $aValorResultadoPrimarioCorr as $iInd =>   $oLinhaManual ){
    $oResultadoPrimario->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oResultadoPrimario->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;
    $oResultadoPrimario->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oResultadoPrimario->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oResultadoPrimario->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oResultadoPrimario->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;    
  }
  
  foreach ( $aValorResultadoNominalCorr as $iInd => $oLinhaManual ){
    $oResultadoNominal->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oResultadoNominal->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;    
    $oResultadoNominal->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oResultadoNominal->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oResultadoNominal->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oResultadoNominal->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;
  }
  
  foreach ( $aValorDivPublicConsolCorr as $iInd => $oLinhaManual ){
    $oDivPublicConsol->aValores[$iAno1]->nCorrente  += $oLinhaManual->colunas[0]->o117_valor;
    $oDivPublicConsol->aValores[$iAno2]->nCorrente  += $oLinhaManual->colunas[1]->o117_valor;
    $oDivPublicConsol->aValores[$iAno3]->nCorrente  += $oLinhaManual->colunas[2]->o117_valor;
    $oDivPublicConsol->aValores[$iAno4]->nCorrente  += $oLinhaManual->colunas[3]->o117_valor;
    $oDivPublicConsol->aValores[$iAno5]->nCorrente  += $oLinhaManual->colunas[4]->o117_valor;
    $oDivPublicConsol->aValores[$iAno6]->nCorrente  += $oLinhaManual->colunas[5]->o117_valor;    
  }
  
  foreach ( $aValorDivConsolLiquidCorr as $iInd => $oLinhaManual ){
    $oDivConsolLiquid->aValores[$iAno1]->nCorrente += $oLinhaManual->colunas[0]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno2]->nCorrente += $oLinhaManual->colunas[1]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno3]->nCorrente += $oLinhaManual->colunas[2]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno4]->nCorrente += $oLinhaManual->colunas[3]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno5]->nCorrente += $oLinhaManual->colunas[4]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno6]->nCorrente += $oLinhaManual->colunas[5]->o117_valor;            
  }

  foreach ( $aValorReceitaTotalConst as $iInd => $oLinhaManual ){
    $oReceitaTotal->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oReceitaTotal->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oReceitaTotal->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oReceitaTotal->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oReceitaTotal->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oReceitaTotal->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;                    
  }
  
  foreach ( $aValorReceitasPrimariasConst as $iInd => $oLinhaManual ){
    $oReceitasPrimarias->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oReceitasPrimarias->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;        
  }
  
  foreach ( $aValorDespesaTotalConst as $iInd => $oLinhaManual ){
    $oDespesaTotal->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oDespesaTotal->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oDespesaTotal->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oDespesaTotal->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oDespesaTotal->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oDespesaTotal->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;        
  }
  
  foreach ( $aValorDespesasPrimariasConst as $iInd => $oLinhaManual ){
    $oDespesasPrimarias->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oDespesasPrimarias->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;                    
  }
  
  foreach ( $aValorResultadoPrimarioConst as $iInd =>   $oLinhaManual ){
    $oResultadoPrimario->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oResultadoPrimario->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;    
    $oResultadoPrimario->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oResultadoPrimario->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oResultadoPrimario->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oResultadoPrimario->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;        
  }
  
  foreach ( $aValorResultadoNominalConst as $iInd => $oLinhaManual ){
    $oResultadoNominal->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oResultadoNominal->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oResultadoNominal->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oResultadoNominal->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oResultadoNominal->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oResultadoNominal->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;        
  }
  
  foreach ( $aValorDivPublicConsolConst as $iInd => $oLinhaManual ){
    $oDivPublicConsol->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oDivPublicConsol->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;
    $oDivPublicConsol->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oDivPublicConsol->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oDivPublicConsol->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oDivPublicConsol->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;                    
  }
  
  foreach ( $aValorDivConsolLiquidConst as $iInd => $oLinhaManual ){
    $oDivConsolLiquid->aValores[$iAno1]->nConstante += $oLinhaManual->colunas[0]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno2]->nConstante += $oLinhaManual->colunas[1]->o117_valor;    
    $oDivConsolLiquid->aValores[$iAno3]->nConstante += $oLinhaManual->colunas[2]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno4]->nConstante += $oLinhaManual->colunas[3]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno5]->nConstante += $oLinhaManual->colunas[4]->o117_valor;
    $oDivConsolLiquid->aValores[$iAno6]->nConstante += $oLinhaManual->colunas[5]->o117_valor;        
  }  
  
  

// Calcula Corrente das Receitas Primarias
foreach ( $aListaTodosAnos as $iIndAno => $iAno ) {
  $oReceitasPrimarias->aValores[$iAno]->nCorrente += $oReceitaTotal->aValores[$iAno]->nCorrente - $aExcecaoPrimaria[$iAno];
}

// Calcula Corrente do Resultado Primario 
foreach ( $aListaTodosAnos as $iInd => $iAno ) {
  $oResultadoPrimario->aValores[$iAno]->nCorrente += $oReceitasPrimarias->aValores[$iAno]->nCorrente - 
                                                    $oDespesasPrimarias->aValores[$iAno]->nCorrente;
}                                         

// Calcula Constante de todas linhas e colunas
foreach ( $aLista as $iInd => $oLinha ){
	foreach ( $aListaTodosAnos as $iIndAno => $iAno ){
		$oLinha->aValores[$iAno]->nConstante += $oLinha->aValores[$iAno]->nCorrente / $aTaxaDefla[$iAno]; 
	}
}

// Busca dados da instituição para o cabeçalho
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
$head5 = $iAnoRef;
$head6 = "METAS FISCAIS ATUAIS COMPARADAS COM AS FIXADAS NOS TRÊS EXERCÍCIOS ANTERIORES";
$head7 = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";

$pdf = new PDF('L');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();
  
$iAlt  = 4;
$iFont = 7; 
  
$pdf->setfont('arial','',$iFont);
  
$pdf->Cell(170,$iAlt,"AMF - Demonstrativo III (LRF, art.4º, §2º, inciso II)",0,0,"L",0);
$pdf->Cell(105,$iAlt,"R$ 1,00"										                          ,0,1,"R",0);
  
// Imprime cabeçalho dos valores Correntes
imprimeCabecalho($pdf,$iAlt,$iFont,$aListaTodosAnos,"CORRENTES");

// Imprime todas linhas dos valores Correntes
foreach ( $aLista as $iInd => $oLinha ){
	
	$pdf->cell(55,$iAlt,$oLinha->sDescricao,'R',0,'L',0);

  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno1]->nCorrente,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno2]->nCorrente,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno2]->nCorrente,$oLinha->aValores[$iAno1]->nCorrente),'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno3]->nCorrente,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno3]->nCorrente,$oLinha->aValores[$iAno2]->nCorrente),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno4]->nCorrente,'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno4]->nCorrente,$oLinha->aValores[$iAno3]->nCorrente),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno5]->nCorrente,'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno5]->nCorrente,$oLinha->aValores[$iAno4]->nCorrente),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno6]->nCorrente,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno6]->nCorrente,$oLinha->aValores[$iAno5]->nCorrente),'f'),'L',1,"C",0);
	
}

$pdf->cell(275,$iAlt,"",'T',1,'R',0);

// Imprime cabeçalho dos valores Constantes
imprimeCabecalho($pdf,$iAlt,$iFont,$aListaTodosAnos,"CONSTANTES");

// Imprime todas linhas dos valores Constantes
foreach ( $aLista as $iInd => $oLinha ){
  
  $pdf->cell(55,$iAlt,$oLinha->sDescricao,'R',0,'L',0);

  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno1]->nConstante,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno2]->nConstante,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno2]->nConstante,$oLinha->aValores[$iAno1]->nConstante),'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno3]->nConstante,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno3]->nConstante,$oLinha->aValores[$iAno2]->nConstante),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno4]->nConstante,'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno4]->nConstante,$oLinha->aValores[$iAno3]->nConstante),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno5]->nConstante,'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno5]->nConstante,$oLinha->aValores[$iAno4]->nConstante),'f'),'L',0,"C",0);  
  $pdf->Cell(20,$iAlt,db_formatar($oLinha->aValores[$iAno6]->nConstante,'f'),'L',0,"C",0);
  $pdf->Cell(20,$iAlt,db_formatar(calculaPerc($oLinha->aValores[$iAno6]->nConstante,$oLinha->aValores[$iAno5]->nConstante),'f'),'L',1,"C",0);
  
}

$pdf->cell(275,$iAlt,"",'T',1,'R',0);

// Imprime Notas Explicativas
$oRelataorioContabil->getNotaExplicativa($pdf,1,190);

$pdf->Output();
  
  
function imprimeCabecalho($pdf,$iAlt,$iFont,$aListaTodosAnos,$sCabecalho){
	
	
  $pdf->SetFont('Arial','B',$iFont);
	
	$pdf->Cell(55 ,$iAlt*2,'ESPECIFICAÇÃO'                 ,'TRB',0,"C",0);
	$pdf->Cell(220,$iAlt  ,"VALORES A PREÇOS {$sCabecalho}",'TLB',1,"C",0);
	
	$pdf->SetX(65);
	
	list($iAno1,$iAno2,$iAno3,$iAno4,$iAno5,$iAno6) = $aListaTodosAnos;
	
  $pdf->Cell(20,$iAlt,$iAno1,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,$iAno2,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,'%'   ,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,$iAno3,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,'%'   ,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,$iAno4,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,'%'   ,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,$iAno5,'TLB',0,"C",0);	
  $pdf->Cell(20,$iAlt,'%'   ,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,$iAno6,'TLB',0,"C",0);
  $pdf->Cell(20,$iAlt,'%'   ,'TLB',1,"C",0);	
  
	$pdf->SetFont('Arial','',$iFont);
	
}
  

/**
 * Calcula variação dos meses
 *
 * @param float $nValorRef
 * @param float $nValorAnt
 * @return float
 */

function calculaPerc($nValorRef,$nValorAnt){

	$nDiferenca = $nValorRef - $nValorAnt;
	
	if ($nDiferenca == 0 ) {
    $nValorFinal = 0;	
	} else {
		if ( $nValorAnt == 0 ) {
			$nValorFinal = $nDiferenca;
		} else { 
      $nValorFinal = ( $nDiferenca * 100 ) / $nValorAnt;
		}  
	}

	return $nValorFinal;
	
}

?>