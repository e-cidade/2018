<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("std/db_stdClass.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/exceptions/BusinessException.php");

$oGet          = db_utils::postMemory($_GET);
$iAnousu       = db_getsession("DB_anousu");
$iInstituicao  = db_getsession("DB_instit");
$iModeloRelatorio = $oGet->iModeloRelatorio;

$sWhere        = "     o01_sequencial  = {$oGet->siLei} ";
$sWhere       .= " and o119_sequencial = {$oGet->iVersao} " ;
$oDaoPpaVersao = new cl_ppaversao();
$sSqlPeriodo   = $oDaoPpaVersao->sql_query(null, " o01_anoinicio, o01_anofinal", null, $sWhere);
$rsPeriodoPPA  = $oDaoPpaVersao->sql_record($sSqlPeriodo);
$oPeriodoPPA   = db_utils::fieldsMemory($rsPeriodoPPA, 0);

/**
 * Busca descrição do Município da Instituição
 */
$oStdDadosInstituicao = db_stdClass::getDadosInstit($iInstituicao);
$oPPADespesa          = new ppa($oGet->siLei, 2, $oGet->iVersao);
$oStdDadosLei         = $oPPADespesa->oObjeto->oDadosLei;

/**
 * Inicia Impressão do PDF
 */

switch ($iModeloRelatorio) {

	case "1" :

		$head1  =  $oStdDadosInstituicao->nomeinst;
    $head2   = "PLANO PLURIANUAL";
    $head3  = "PROGRAMAS DE GESTÃO, MANUTENÇÃO E SERVIÇOS AO ESTADO";
    $head4  = "LEI: {$oGet->siLei} - {$oStdDadosLei->o01_descricao}";
    $head5  = "PERÍODO: {$oStdDadosLei->o01_anoinicio} / {$oStdDadosLei->o01_anofinal}";

	break;

	case "2" :

		$head1  =  $oStdDadosInstituicao->nomeinst;
	  $head2  = "LEI DE DIRETRIZES ORÇAMENTÁRIAS";
	  $head3  = "EXERCÍCIO DE: {$iAnousu}";
	  $head4  = "PROGRAMAS DE GESTÃO, MANUTENÇÃO E SERVIÇOS AO ESTADO";
	  $head5  = "LEI: {$oGet->siLei} - {$oStdDadosLei->o01_descricao}";
	  $head6  = "PERÍODO: {$oStdDadosLei->o01_anoinicio} / {$oStdDadosLei->o01_anofinal}";

	break;
}

/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 5;
$oPdf->AddPage();
$oPdf->SetFont('arial', '', 7);


/**
 * Função que imprime o cabeçalho da tabela
 * @param PDF     $oPdf
 * @param integer $iAlturaLinha
 */
function imprimirCabecalhoPrograma($oPdf, $iAlturaLinha) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  $oPdf->setfont('arial','b',8);
  $oPdf->ln(2);

  $oPdf->cell(132, $iAlturaLinha, "1.Descrição do Programa",  "TBR", 0, "C", 1);
  $oPdf->cell(60,  $iAlturaLinha, "1.1 Valor Global do Programa",  "LT", 1, "C", 1);

  $oPdf->cell(20,  $iAlturaLinha, "Código", "RTB", 0, "C", 1);
  $oPdf->cell(112, $iAlturaLinha, "Título", "LRTB", 0, "C", 1);
  $oPdf->cell(30,  $iAlturaLinha, "Ano", "LRTB", 0, "C", 1);
  $oPdf->cell(30,  $iAlturaLinha,"Valor", "TBL", 1, "C", 1);
  $oPdf->setfont('arial','',7);
}

/**
 * Função para imprimir a continuação da Página
 * @param Pdf     $oPdf
 * @param integer $iAlturaLinha
 */
function imprimirContinuacaoPagina($oPdf, $iAlturaLinha) {

  $oPdf->cell(198,$iAlturaLinha,'Continua na Página '.($oPdf->pageNo()+1)."/{nb}","T",1,"R",0);
  $oPdf->addpage();
  $oPdf->ln(2);
  $oPdf->cell(198,$iAlturaLinha,'Continuação '.($oPdf->pageNo()-1)."/{nb}","B",1,"R",0);
  imprimirCabecalhoPrograma($oPdf, $iAlturaLinha);
}


/**
 *
 * @param Program   $oPrograma
 * @param stdClass  $oStdEstimado
 * @param Pdf       $oPdf
 * @param integer   $iAlturaLinha
 * @param bool      $lImprimeCabecalho
 */
function imprimirPrograma ($oPrograma, $oStdEstimado, $oPdf, $iAlturaLinha, $lImprimeCabecalho, $lImprimeBotton, $lPrimeiraLinha, $iModeloRelatorio=null) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  if ($lImprimeCabecalho) {
    imprimirCabecalhoPrograma($oPdf, $iAlturaLinha);
  }
  $sBordas         = "R";
  $sDescricao      = "";
  $iCodigoPrograma = "";

  if ($lPrimeiraLinha) {

    $sDescricao      = substr($oPrograma->getDescricao(), 0 , 110);
    $iCodigoPrograma = $oPrograma->getCodigoPrograma();
  }

  if ($lImprimeBotton) {
    $sBordas    = "RB";
  }

  if (!empty($iModeloRelatorio) && $iModeloRelatorio == 2) {
    $sBordas = "RB";
  }

  $oPdf->setfont('arial','',7);
  $oPdf->cell(20,  $iAlturaLinha, $iCodigoPrograma, $sBordas,0,"C",0);


  $oPdf->cell(112, $iAlturaLinha, $sDescricao, $sBordas, 0, "L", 0);
  $oPdf->cell(30,  $iAlturaLinha, $oStdEstimado->iAno, "RLTB", 0, "C", 0);
  $oPdf->cell(30,  $iAlturaLinha, db_formatar($oStdEstimado->nValor, "f"), "LTB", 1, "R", 0);
}

$lImprimeCabecalho = true;

try {

	/*
	 * Buscamos os programas
	*/
	$sProgramas = $oGet->sProgramas;
	if (empty($sProgramas)) {

		$aWherePrograma  = array();
		$aWherePrograma[] = "o08_ppaversao = {$oGet->iVersao}";
		/**
		 * Alterado lógica para filtrar pelo competencia do ppa
		 */
		$aWherePrograma[] = "o08_ano between {$oPeriodoPPA->o01_anoinicio} and {$oPeriodoPPA->o01_anofinal}";
		if ($oGet->iTipo != "0") {
			$aWherePrograma[] = "o54_tipoprograma = {$oGet->iTipo}";
		}
		$sWherePrograma    = implode(" and ", $aWherePrograma);
		$oDAOPPADotacao    = db_utils::getDao("ppadotacao");
		$sCamposPrograma   = "array_to_string(array_accum(distinct o08_programa)::integer[], ', ') as lista_programa";
		$sSQLBuscaPrograma = $oDAOPPADotacao->sql_query_despesa_programa(null,  $sCamposPrograma, null, $sWherePrograma);
		$rsBuscaPrograma   = $oDAOPPADotacao->sql_record($sSQLBuscaPrograma);
		if ($oDAOPPADotacao->erro_status == "0") {
			throw new BusinessException("Nenhum programa localizado para o filtro selecionado.");
		}

		$sProgramas = db_utils::fieldsMemory($rsBuscaPrograma, 0)->lista_programa;
	}

	$aCodigoProgramas  = explode(",", $sProgramas);

  foreach($aCodigoProgramas as $iCodigoPrograma) {

    $oPrograma      = new Programa($iCodigoPrograma, $iAnousu);
    $aValores       = Programa::getValorGlobalEstimadoPPAPorAno($iCodigoPrograma, $iAnousu, $oGet->iVersao);
    $iTotalLinhas   = count($aValores);
    $iContadorLinha = 1;
    $lImprimeBotton = false;
    $lPrimeiraLinha = true;
    $lImprime         = false;
    foreach($aValores as $iValor) {

      if ($iValor > 0) {
        $lImprime         = true;
    	}
    }

    if (!$lImprime) {
    	continue;
    }


    foreach ($aValores as $iAno => $nValor) {

      $oStdEstimado = new stdClass();
      $oStdEstimado->nValor = $nValor;
      $oStdEstimado->iAno   = $iAno;
      if($iContadorLinha == $iTotalLinhas) {
        $lImprimeBotton = true;
      }

      imprimirPrograma($oPrograma, $oStdEstimado, $oPdf, $iAlturaLinha, $lImprimeCabecalho, $lImprimeBotton, $lPrimeiraLinha, $iModeloRelatorio);
      $lPrimeiraLinha    = false;
      $lImprimeCabecalho = false;
      $iContadorLinha++;
      if ($iModeloRelatorio == 2) {

      	break;
      }
    }
  }
} catch(Exception $eException){
  db_redireciona("db_erros.php?fechar=true&db_erro=[1] - {$eException->getMessage()}");
}

$oPdf->Output();