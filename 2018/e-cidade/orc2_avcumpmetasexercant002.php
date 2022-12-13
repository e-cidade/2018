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

$oGet	 = db_utils::postMemory($_GET);

// Código do relatório
$iCodRel = $oGet->iCodRel;

// Lista todas intituições selecionadas
$sListaInstit = str_replace('-',',',$oGet->sListaInstit);


$cldb_config                = new cl_db_config();
$clorccenarioeconomicoparam = new cl_orccenarioeconomicoparam();
$oRelataorioContabil        = new relatorioContabil($iCodRel);


// Objetos referente as linhas do Relatório
$oValorReceitaTotal         = new linhaRelatorioContabil($iCodRel,1);
$oValorReceitasPrimarias    = new linhaRelatorioContabil($iCodRel,2);
$oValorDespesaTotal         = new linhaRelatorioContabil($iCodRel,3);
$oValorDespesasPrimarias    = new linhaRelatorioContabil($iCodRel,4);
$oValorResultadoPrimario    = new linhaRelatorioContabil($iCodRel,5);
$oValorResultadoNominal     = new linhaRelatorioContabil($iCodRel,6);
$oValorDivPublicConsol      = new linhaRelatorioContabil($iCodRel,7);
$oValorDivConsolLiquid      = new linhaRelatorioContabil($iCodRel,8);

$iAnoSessao = db_getsession("DB_anousu");
// Busca valores digitados manualmente para cada linha e coluna
$aValorReceitaTotal         = $oValorReceitaTotal->getValoresColunas(null, null, null, $iAnoSessao);
$aValorReceitasPrimarias    = $oValorReceitasPrimarias->getValoresColunas(null, null, null, $iAnoSessao);
$aValorDespesaTotal         = $oValorDespesaTotal->getValoresColunas(null, null, null, $iAnoSessao);
$aValorDespesasPrimarias    = $oValorDespesasPrimarias->getValoresColunas(null, null, null, $iAnoSessao);
$aValorResultadoPrimario    = $oValorResultadoPrimario->getValoresColunas(null, null, null, $iAnoSessao);
$aValorResultadoNominal     = $oValorResultadoNominal->getValoresColunas(null, null, null, $iAnoSessao);
$aValorDivPublicConsol      = $oValorDivPublicConsol->getValoresColunas(null, null, null, $iAnoSessao);
$aValorDivConsolLiquid      = $oValorDivConsolLiquid->getValoresColunas(null, null, null, $iAnoSessao);


// Define todos anos utilizados no relatório apartir do ano de referência
$iAnoRef = db_getsession("DB_anousu")+1;
$iAnoAnt = $iAnoRef-2;


// Busca valor do PIB para todos anos
$sCamposDadosPIB  = " o03_anoreferencia,                            ";
$sCamposDadosPIB .= " sum(o03_valorparam) as valor                  ";

$sWhereDadosPIB   = "     o02_orccenarioeconomicogrupo = 3          ";
$sWhereDadosPIB  .= " and o03_tipovalor                = 2          ";
$sWhereDadosPIB  .= " and o03_anoreferencia            = {$iAnoAnt} ";
$sWhereDadosPIB  .= " and o03_instit                   = ".db_getsession('DB_instit');
$sWhereDadosPIB  .= " group by o03_anoreferencia                    ";

$sSqlDadosPIB     = $clorccenarioeconomicoparam->sql_query(null,$sCamposDadosPIB,null,$sWhereDadosPIB);
$rsDadosPIB       = $clorccenarioeconomicoparam->sql_record($sSqlDadosPIB);
$iLinhasDadosPIB  = $clorccenarioeconomicoparam->numrows;
//die($sSqlDadosPIB);
if ( $iLinhasDadosPIB > 0 ) {
	for( $iInd=0; $iInd < $iLinhasDadosPIB; $iInd++ ) {
		$oDadosPIB = db_utils::fieldsMemory($rsDadosPIB,$iInd);
		$nPIB = $oDadosPIB->valor;
	}
} else {
	db_redireciona("db_erros.php?fechar=true&db_erro=Não consta informação sobre o valor do PIB no cenário macroeconômico!");
}


$oReceitaTotal = new stdClass();
$oReceitaTotal->Descricao = "";
$oReceitaTotal->Prevista  = 0;
$oReceitaTotal->PrevPIB   = 0;
$oReceitaTotal->Realizada = 0;
$oReceitaTotal->RealPIB   = 0;
$oReceitaTotal->VarValor  = 0;
$oReceitaTotal->VarPerc   = 0;

$oReceitasPrimarias = clone $oReceitaTotal;
$oDespesaTotal      = clone $oReceitaTotal;
$oDespesasPrimarias = clone $oReceitaTotal;
$oResultadoPrimario = clone $oReceitaTotal;
$oResultadoNominal  = clone $oReceitaTotal;
$oDivPublicConsol   = clone $oReceitaTotal;
$oDivConsolLiquid   = clone $oReceitaTotal;

$oReceitaTotal->Descricao      = "Receita Total";
$oReceitasPrimarias->Descricao = "Receitas Primárias(I)";
$oDespesaTotal->Descricao      = "Despesa Total";
$oDespesasPrimarias->Descricao = "Despesas Primárias(II)";
$oResultadoPrimario->Descricao = "Resultado Primário(III) = (I-II)";
$oResultadoNominal->Descricao  = "Resultado Nominal";
$oDivPublicConsol->Descricao   = "Dívida Pública Consolidada";
$oDivConsolLiquid->Descricao   = "Dívida Consolidada Líquida";


$dtDataIni      = "{$iAnoAnt}-01-01";
$dtDataFin      = "{$iAnoAnt}-12-31";

$sWhereReceita  = " o70_instit in ({$sListaInstit})";
$rsReceita      = db_receitasaldo(11,1,3,true,$sWhereReceita,$iAnoAnt,$dtDataIni,$dtDataFin,false);
$iLinhasReceita = pg_num_rows($rsReceita);
db_query("drop table work_receita");

//db_criatabela($rsReceita);exit;


$nExcessaoRecPrimaria['Prevista']  = 0;
$nExcessaoRecPrimaria['Realizada'] = 0;

for ( $iInd=0; $iInd < $iLinhasReceita; $iInd++ ) {

	$oReceita = db_utils::fieldsMemory($rsReceita,$iInd);

	if ( $oReceita->o57_fonte == 400000000000000 || $oReceita->o57_fonte == 900000000000000 ) {

	  $oReceitaTotal->Prevista  += $oReceita->saldo_inicial + $oReceita->saldo_prevadic_acum;
	  $oReceitaTotal->Realizada += $oReceita->saldo_arrecadado_acumulado;

  }


  if ( $oReceita->o57_fonte == 413250000000000 ||
       $oReceita->o57_fonte == 421000000000000 ||
       $oReceita->o57_fonte == 423000000000000 ||
       $oReceita->o57_fonte == 422000000000000 ) {

     $nExcessaoRecPrimaria['Prevista']  += $oReceita->saldo_inicial + $oReceita->saldo_prevadic_acum;
		 $nExcessaoRecPrimaria['Realizada'] += $oReceita->saldo_arrecadado_acumulado;

  }

}


$oReceitasPrimarias->Prevista  = $oReceitaTotal->Prevista  - $nExcessaoRecPrimaria['Prevista'];
$oReceitasPrimarias->Realizada = $oReceitaTotal->Realizada - $nExcessaoRecPrimaria['Realizada'];




$sWhereDespesa  = " w.o58_instit in ({$sListaInstit})";
$rsDespesa      = db_dotacaosaldo(8,2,3,true,$sWhereDespesa,$iAnoAnt,$dtDataIni,$dtDataFin);
$iLinhasDespesa = pg_num_rows($rsDespesa);

$nExcessaoDespPrimaria['Prevista']  = 0;
$nExcessaoDespPrimaria['Realizada'] = 0;


for ( $iInd=0; $iInd < $iLinhasDespesa; $iInd++ ) {

	$oDespesa = db_utils::fieldsMemory($rsDespesa,$iInd);

	if ( $oDespesa->o58_elemento{0} == 3 ) {

	  $oDespesaTotal->Prevista  += $oDespesa->dot_ini + $oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado;
	  $oDespesaTotal->Realizada += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado;

	}

	if ( substr($oDespesa->o58_elemento,0,3) == 332 ||
	     substr($oDespesa->o58_elemento,0,3) == 346 ) {

	 	$nExcessaoDespPrimaria['Prevista']  += $oDespesa->dot_ini + $oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado;
    $nExcessaoDespPrimaria['Realizada'] += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado;

	}

}

$oDespesasPrimarias->Prevista  = $oDespesaTotal->Prevista  - $nExcessaoDespPrimaria['Prevista'];
$oDespesasPrimarias->Realizada = $oDespesaTotal->Realizada - $nExcessaoDespPrimaria['Realizada'];




foreach ( $aValorReceitaTotal as $iInd => $oLinhaManual ){

  $oReceitaTotal->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oReceitaTotal->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

foreach ( $aValorReceitasPrimarias as $iInd => $oLinhaManual ){

  $oReceitasPrimarias->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oReceitasPrimarias->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}
$oResultadoPrimario->Prevista  = $oReceitasPrimarias->Prevista  - $oDespesasPrimarias->Prevista;
$oResultadoPrimario->Realizada = $oReceitasPrimarias->Realizada - $oDespesasPrimarias->Realizada;

foreach ( $aValorDespesaTotal as $iInd => $oLinhaManual ){

  $oDespesaTotal->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oDespesaTotal->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

foreach ( $aValorDespesasPrimarias as $iInd => $oLinhaManual ){

  $oDespesasPrimarias->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oDespesasPrimarias->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

foreach ( $aValorResultadoPrimario as $iInd => $oLinhaManual ){

  $oResultadoPrimario->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oResultadoPrimario->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}


foreach ( $aValorResultadoNominal as $iInd => $oLinhaManual ){

	$oResultadoNominal->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
	$oResultadoNominal->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

foreach ( $aValorDivPublicConsol as $iInd => $oLinhaManual ){

  $oDivPublicConsol->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oDivPublicConsol->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

foreach ( $aValorDivConsolLiquid as $iInd => $oLinhaManual ){

  $oDivConsolLiquid->Prevista  += $oLinhaManual->colunas[0]->o117_valor;
  $oDivConsolLiquid->Realizada += $oLinhaManual->colunas[1]->o117_valor;

}

$aLista = array( $oReceitaTotal,
								 $oReceitasPrimarias,
								 $oDespesaTotal,
								 $oDespesasPrimarias,
								 $oResultadoPrimario,
                 $oResultadoNominal,
                 $oDivPublicConsol,
                 $oDivConsolLiquid );


foreach ( $aLista as $iInd => $oLinha ){

  $oLinha->PrevPIB   = ($oLinha->Prevista  / $nPIB)*100;
  $oLinha->RealPIB   = ($oLinha->Realizada / $nPIB)*100;
  $oLinha->VarValor  = $oLinha->Realizada  - $oLinha->Prevista;

  if ( $oLinha->VarValor != 0 || $oLinha->Prevista != 0 ) {
    $oLinha->VarPerc = ($oLinha->VarValor  / $oLinha->Prevista)*100;
  } else {
  	$oLinha->VarPerc = 0;
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
$head5 = $iAnoRef;
$head6 = "AVALIAÇÃO DO CUMPRIMENTO DAS METAS FISCAIS DO EXERCÍCIO ANTERIOR";

$pdf = new PDF('L');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();

$iAlt  = 4;
$iFont = 8;


$pdf->setfont('arial','',$iFont);

$pdf->Cell(170,$iAlt,"AMF - Demonstrativo II (LRF, art. 4º, §2º, inciso I)",0,0,"L",0);
$pdf->Cell(0  ,$iAlt,"R$ 1,00"										                         ,0,1,"R",0);

imprimeCabecalho($pdf,$iAlt,$iFont,$iAnoAnt);

foreach ( $aLista as $iInd => $oLinha ){

	$pdf->cell(65,$iAlt,$oLinha->Descricao                 ,'R',0,'L',0);
	$pdf->cell(45,$iAlt,db_formatar($oLinha->Prevista ,'f'),'L',0,'R',0);
	$pdf->cell(25,$iAlt,db_formatar($oLinha->PrevPIB  ,'f'),'L',0,'R',0);
	$pdf->cell(45,$iAlt,db_formatar($oLinha->Realizada,'f'),'L',0,'R',0);
	$pdf->cell(25,$iAlt,db_formatar($oLinha->RealPIB  ,'f'),'L',0,'R',0);
	$pdf->cell(45,$iAlt,db_formatar($oLinha->VarValor ,'f'),'L',0,'R',0);
	$pdf->cell(25,$iAlt,db_formatar($oLinha->VarPerc  ,'f'),'L',1,'R',0);

}

$pdf->cell(0,$iAlt,"",'T',1,'R',0);

// Imprime Notas Explicativas
$oRelataorioContabil->getNotaExplicativa($pdf,1,190);

$pdf->Output();


function imprimeCabecalho($pdf,$iAlt,$iFont,$iAnoAnt){


  $pdf->SetFont('Arial','B',$iFont);

	$pdf->Cell(65,$iAlt*3,"ESPECIFICAÇÃO"      ,'TRB',0,"C",0);
	$pdf->Cell(45,$iAlt  ,"Metas Previstas em" , 'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,""                   , 'TR',0,"C",0);
	$pdf->Cell(45,$iAlt  ,"Metas Realizadas em", 'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,""                   , 'TR',0,"C",0);
	$pdf->Cell(70,$iAlt  ,"Variação"           ,'TLB',1,"C",0);

	$pdf->SetX(75);

	$pdf->Cell(45,$iAlt  ,$iAnoAnt,'R' ,0,"C",0);
	$pdf->Cell(25,$iAlt  ,'% PIB' ,'R' ,0,"C",0);
	$pdf->Cell(45,$iAlt  ,$iAnoAnt,'R' ,0,"C",0);
	$pdf->Cell(25,$iAlt  ,'% PIB' ,'R' ,0,"C",0);
	$pdf->Cell(45,$iAlt  ,'Valor' ,'TR',0,"C",0);
	$pdf->Cell(25,$iAlt  ,'%'     ,'TL',1,"C",0);

	$pdf->SetX(75);

  $pdf->Cell(45,$iAlt  ,'( a )'            ,'BR',0,"C",0);
  $pdf->Cell(25,$iAlt  ,''                 ,'BR',0,"C",0);
  $pdf->Cell(45,$iAlt  ,'( b )'            ,'BR',0,"C",0);
  $pdf->Cell(25,$iAlt  ,''                 ,'BR',0,"C",0);
  $pdf->Cell(45,$iAlt  ,'( c ) = ( b - a )','BR',0,"C",0);
  $pdf->Cell(25,$iAlt  ,'( c / a ) x 100'  ,'BL',1,"C",0);


	$pdf->SetFont('Arial','',$iFont);

}

?>