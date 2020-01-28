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

require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
db_app::import("contabilidade.relatorios.AnexoSumarioGeralReceita");
db_app::import("relatorioContabil");
$oGet         = db_utils::postMemory($_GET);
$iAnoUso      = db_getsession('DB_anousu');
$sInstit      = str_replace("-", ",", $oGet->sInstit);
$oAnexoSumario = new AnexoSumarioGeralReceita($iAnoUso, $oGet->iCodRel, $oGet->iPeriodo);
$oAnexoSumario->setOrigemFase($oGet->iOrigemFase);
$oAnexoSumario->setInstituicoes($sInstit);
$aDadosSumarioGeral = $oAnexoSumario->getDados();



$aFases         = array(1 => "Orçamento", 
                        2 => "Empenhado", 
                        3 => "Liquidado", 
                        4 => "Pago");
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

/**
 * Busca o periodo
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oAnexoSumario->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $oGet->iPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}
$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "SUMÁRIO GERAL DA RECEITA POR FONTES E DESPESA POR FUNÇÕES DO GOVERNO";
$head3 = "Lei Orçamentária Anual de ".db_getsession("DB_anousu");
$head4 = "Instituições: {$sDescricaoInstitucoes}";

/**
 * Se a Origem/Fase for diferente de Orçamento
 */
if ($oGet->iOrigemFase != 1) {

  $head5 = "Valor: {$aFases[$oGet->iOrigemFase]}";
  $head6 = "Período: JANEIRO a {$sDescricaoPeriodo}";
}

if ($oGet->lConsolidado == 1) {
  $head2 .= " - Consolidado";
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 7);
$iAlt         = 4;
$iPagina      = 1;
$iTamFonte    = 8;
$iFonte       = 7;
$iColunaDescr = 60;
$iColunaValor = 25;

$oPdf->addpage();
$oPdf->setfont('arial', 'b', $iFonte);
$oPdf->cell(95, $iAlt, "R E C E I T A S", 0, 0, "C", 0);
$oPdf->cell(95, $iAlt, "D E S P E S A S", 0, 1, "C", 0);
$iYInicial     = $oPdf->getY()+3;
$oPdf->line(10, $iYInicial-3, 200, $iYInicial-3);
$iValrYDespesa = 0;
$iValrYReceita = 0;
$oPdf->ln(3);

$oPdf->setfont('arial','', $iFonte);

/**
 * Percorre o array retornado pelo método getDados() imprimindo os resultados
 */
foreach ($aDadosSumarioGeral as $iIdLinha => $oRelatorio) {
  
  /**
   * Valida totalizador
   */
  $sBold = "";
  if ($oRelatorio->totalizar) {
    $sBold = "b";
  }
  
	if ($iIdLinha <= 19) {
		
    if ($iIdLinha == 1) {
    	
    	$oPdf->setfont('arial','B',$iFonte);
    	$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0);
   	  $oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f')                        , 0, 1, "R", 0);
    } else {
    	
    	$oPdf->setfont('arial',$sBold, $iFonte);
		  $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0,'','.');
		  $oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f')                         , 0, 1, "R", 0);
    }  
    
    $iValrYReceita = $oPdf->GetY();		
	}
	
	if ($iIdLinha == 22) {

	  $sBold = "";
    if ($oRelatorio->totalizar) {
      $sBold = "b";
    }
	  $oPdf->setXY(100, $iYInicial);
  	$oPdf->setfont('arial','B',$iFonte);
  	$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0);
  	$oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f')                        , 0, 1, "R", 0);
  	
    foreach ($oRelatorio->funcoes as $iIdFuncao => $oFuncao) {
      
      if ($oFuncao->total != 0) {
        
        $oPdf->setX(100);
        $oPdf->setfont('arial',$sBold, $iFonte);
        $oPdf->cell($iColunaDescr, $iAlt, "  ".str_pad($iIdFuncao, 2, "0", STR_PAD_LEFT)." - ". $oFuncao->descricao, 0, 0, "L", 0,'','.');
        $oPdf->cell($iColunaValor, $iAlt, db_formatar($oFuncao->total, 'f'), 0, 1, "R", 0);
      }
    }
    $iValrYDespesa = $oPdf->GetY();		
	}
}

if ($iValrYReceita > $iValrYDespesa) {
  $iValorYPatronais = ($iValrYReceita+5);
} else {
  $iValorYPatronais = ($iValrYDespesa+5);
}
$oPdf->line(10, $iValorYPatronais, 200, $iValorYPatronais);
/**
 * Valores Patronais
 */
$oPdf->setY($iValorYPatronais);
$oPdf->setfont('arial','B',$iFonte);
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aDadosSumarioGeral[20]->nivellinha).$aDadosSumarioGeral[20]->descricao, 0, 0, "L", 0);
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[20]->total, 'f')                        , 0, 1, "R", 0);

$oPdf->setXY(100, $iValorYPatronais);
$oPdf->setfont('arial','B',$iFonte);
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aDadosSumarioGeral[23]->nivellinha).$aDadosSumarioGeral[23]->descricao, 0, 0, "L", 0);
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[23]->total, 'f')                        , 0, 1, "R", 0);

$iValorYTotal = $oPdf->getY();
$oPdf->line(10, $iValorYTotal, 200, $iValorYTotal);
/**
 * Valores Totais
 */
$oPdf->setfont('arial','B',$iFonte);
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aDadosSumarioGeral[21]->nivellinha).$aDadosSumarioGeral[21]->descricao, 0, 0, "L", 0);
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[21]->total, 'f')                        , 0, 1, "R", 0);

$oPdf->setXY(100, $iValorYTotal);

$oPdf->setfont('arial','B',$iFonte);
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aDadosSumarioGeral[24]->nivellinha).$aDadosSumarioGeral[24]->descricao, 0, 0, "L", 0);
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[24]->total, 'f')                        , 0, 1, "R", 0);
$oPdf->line(98, $iYInicial-3, 98, $oPdf->GetY());
$oPdf->line(10, $oPdf->GetY(), 200, $oPdf->GetY());
$oPdf->Output();


function setIdentacao($iNivel) {
  
  $sEspaco = "";
  if ($iNivel > 1) {
    $sEspaco = str_repeat("   ", $iNivel);
  }
  return $sEspaco;
}
?>