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
require_once("classes/db_db_depart_classe.php");
require_once("classes/db_cfpatri_classe.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_clabens_classe.php");

$oGet = db_utils::postMemory($_GET);
/**
 * 
 * Vari�veis utilizadas no header do relat�rio
 */
$sPeriodo      = null;
$sClasse       = null;
$sDepartamento = null;

$sTipoImpressao = "Analitica";
if ($oGet->sImpressao == "S") {
  $sTipoImpressao = "Sint�tica ";
} else if ($oGet->sImpressao == "T") {
	$sTipoImpressao = "Acumulado";
}

$sWhere = " t55_codbem is null and t57_ativo is true and t57_processado is true ";

/** ***********************************************************
 *  *****************  FILTRO POR DATA  ***********************
 *  *********************************************************** */
/**
 * Se a data Final estiver vazia e a inicial preenchida
 * Busca todos registros apartir da data Inicial
 */
if (!empty($oGet->sDataInicio) && empty($oGet->sDataFinal)) {
      
  $sDataInicio = formataData($oGet->sDataInicio);   
  $sPeriodo    = $sDataInicio;
  $sWhere     .= " and t57_datacalculo >= '{$sDataInicio}'";
}

/**
 * Se a data Incial estiver vazia e a final preenchida 
 * Busca todos registros at� a data final
 */
if (empty($oGet->sDataInicio) &&  !empty($oGet->sDataFinal)) {

  $sDataFinal = formataData($oGet->sDataFinal);
  $sPeriodo   = $sDataFinal;        
  $sWhere    .= " and t57_datacalculo <= '{$sDataFinal}'";
}

/**
 * Se ambas as Datas estiv�rem v�lidas, faz um between entre elas 
 */
if (!empty($oGet->sDataInicio) && !empty($oGet->sDataFinal)) {
  
  $sDataFinal  = formataData($oGet->sDataFinal);
  list($iDiaInicio, $iMesInicio, $iAnoInicio) = explode("/", $oGet->sDataInicio);
  list($iDiaFinal, $iMesFinal, $iAnoFinal)    = explode("/", $oGet->sDataFinal);
  
  
  $sPeriodo    = "{$oGet->sDataInicio} at� {$oGet->sDataFinal}";
  $sWhere     .= " and cast( t57_ano||lpad(t57_mes,2,'0') as integer) ";
  $sWhere     .= " 		     between '{$iAnoInicio}{$iMesInicio}' and '{$iAnoFinal}{$iMesFinal}'"; 
  
//   cast( t57_ano||lpad(t57_mes,2,'0') as integer) between '201201' and '201204'
}

/** ***********************************************************
 *  *****************  FILTRO POR CLASSE **********************
 *  *********************************************************** */

/**
 * Se a Classe Final estiver vazia e a inicial preenchida
 * Busca todos registros apartir da Classe Inicial
 */

if (!empty($oGet->sClasseInicio) && empty($oGet->sClasseFim)) {
  
  $sClasse = $oGet->sClasseInicio;
  $sWhere .= " and t64_codcla >= '{$oGet->sClasseInicio}'";
}

/**
 * Se a data Incial estiver vazia e a final preenchida 
 * Busca todos registros at� a data final
 */
if (empty($oGet->sClasseInicio) && !empty($oGet->sClasseFim)) {
  
  $sClasse = $oGet->sClasseFim;
  $sWhere .= " and t64_codcla <= '{$oGet->sClasseFim}'";
}

/**
 * Se ambas as Datas estiv�rem v�lidas, faz um between entre elas 
 */
if (!empty($oGet->sClasseInicio) && !empty($oGet->sClasseFim)) {
  
  $sClasse = "{$oGet->sClasseInicio} at� {$oGet->sClasseFim}";
  $sWhere .= " and t64_codcla between '{$oGet->sClasseInicio}' and '{$oGet->sClasseFim}'"; 
}

/** ***********************************************************
 *  **************  FILTRO POR DEPARTAMENTOS  *****************
 *  *********************************************************** */

if (!empty($oGet->sDepartamentos)) {
	
	$oDaoDepartamento   = db_utils::getDao("db_depart");
	$sWhereDepartamento = " coddepto in ($oGet->sDepartamentos)";
	$sSqlDepartamento   = $oDaoDepartamento->sql_query_file(null,"descrdepto",null,"$sWhereDepartamento");
	$rsDepartamento     = $oDaoDepartamento->sql_record($sSqlDepartamento);
	$sDepartamento      = "";
	$sVirgula           = "";
	
	if ($oDaoDepartamento->numrows > 0) {
		
		for ($i = 0; $i < $oDaoDepartamento->numrows; $i++) {
			
			$sDepartamento .= "{$sVirgula}". db_utils::fieldsMemory($rsDepartamento, $i)->descrdepto;
			$sVirgula			  = ", ";	
		}
	}
  $sWhere       .= " and coddepto in ($oGet->sDepartamentos)";
}

/**
 * Campos utilizado na query
 */
$sCampos  = "distinct";
$sCampos .= " t52_bem            as codigo_bem, 			";
$sCampos .= " t52_ident          as placa, 		";
$sCampos .= " t52_descr          as bem_descricao,    "; 
$sCampos .= " t64_class          as classe, 				  "; 
$sCampos .= " t44_vidautil       as vida_util,        ";
$sCampos .= " t46_descricao      as tipo_depreciacao, ";
$sCampos .= " t58_valorresidual  as valor_residual,   ";
$sCampos .= " t52_valaqu         as valor_aquisicao,  ";
$sCampos .= " t58_valorcalculado as valor_depreciado, ";
$sCampos .= " t58_valoratual     as saldo, ";            
$sCampos .= " t57_mes            as mes,   ";
$sCampos .= " t57_ano            as ano,   ";
$sCampos .= " t57_processado ";

/**
 * Ordena��o da Query
 */
$sOrdem 						 = "t57_ano, t57_mes, t52_bem ";
$oDaoHistorico			 = db_utils::getDao("benshistoricocalculo");
$sSqlBensDepreciacao = $oDaoHistorico->sql_query_historico_depreciacao(null, $sCampos, $sOrdem, $sWhere);
$rsBensDepreciacao   = $oDaoHistorico->sql_record($sSqlBensDepreciacao);
$iNumrows            = $oDaoHistorico->numrows;

$aHistoricoDepreciacao       = array();
$aValoresDepreciado 				 = array();
$aValoresDepreciadoAcumulado = array();

for ($i = 0; $i < $iNumrows; $i++) {
  
  $oBenHistoricoDepreciacao = db_utils::fieldsMemory($rsBensDepreciacao, $i);
  
  $iIndexAnoMes = $oBenHistoricoDepreciacao->ano.$oBenHistoricoDepreciacao->mes;
  
  $oBemHistorico = new stdClass();
  $oBemHistorico->codigo_bem       = $oBenHistoricoDepreciacao->codigo_bem;
  $oBemHistorico->placa     		   = $oBenHistoricoDepreciacao->placa;
  $oBemHistorico->bem_descricao    = $oBenHistoricoDepreciacao->bem_descricao;
  $oBemHistorico->classe           = $oBenHistoricoDepreciacao->classe;
  $oBemHistorico->vida_util        = $oBenHistoricoDepreciacao->vida_util;
  $oBemHistorico->tipo_depreciacao = $oBenHistoricoDepreciacao->tipo_depreciacao;
  $oBemHistorico->valor_residual   = $oBenHistoricoDepreciacao->valor_residual;
  $oBemHistorico->valor_aquisicao  = $oBenHistoricoDepreciacao->valor_aquisicao;
  $oBemHistorico->valor_depreciado = $oBenHistoricoDepreciacao->valor_depreciado;
  $oBemHistorico->saldo            = $oBenHistoricoDepreciacao->saldo; 
  $oBemHistorico->ano              = $oBenHistoricoDepreciacao->ano;
  $oBemHistorico->mes_descricao    = db_mes($oBenHistoricoDepreciacao->mes, 2);
  $oBemHistorico->mes              = $oBenHistoricoDepreciacao->mes;
  
  $aHistoricoDepreciacao[$iIndexAnoMes][] = $oBemHistorico;
  
  /**
   * S� executa este calculo se a impress�o for do tipo acumulada.
   */
  if ($oGet->sImpressao == "T") {
  	
	  if (array_key_exists($oBemHistorico->codigo_bem, $aValoresDepreciadoAcumulado)) {
	
	    $nSaldo = $aValoresDepreciadoAcumulado[$oBemHistorico->codigo_bem]->saldo;
	  	$nSaldo = $nSaldo - $oBemHistorico->valor_depreciado;
	  	$aValoresDepreciadoAcumulado[$oBemHistorico->codigo_bem]->valor_depreciado += $oBemHistorico->valor_depreciado;
	  	$aValoresDepreciadoAcumulado[$oBemHistorico->codigo_bem]->saldo             = $nSaldo;
	  	
	  } else {
	  
	  	$aValoresDepreciadoAcumulado[$oBemHistorico->codigo_bem] = $oBemHistorico;
		}
  }
}

$nValorDepreciado   = 0;

if ($oGet->sImpressao == "S") { 

  foreach ($aHistoricoDepreciacao as $iIndice => $aBemHistorico) {

    if (!array_key_exists($iIndice, $aValoresDepreciado)) {
      $nValorDepreciado   = 0;
    }
    
    for ($i = 0; $i < count($aBemHistorico); $i++) {
      
      $nValorDepreciado += $aBemHistorico[$i]->valor_depreciado;
    }
    $oSomaValorDepreciado = new stdClass();
    /**
     * Peguei os dados do indice [0] pois este � igual em todos os index
     */
    $oSomaValorDepreciado->ano              = $aBemHistorico[0]->ano;
    $oSomaValorDepreciado->mes_descricao    = db_mes($aBemHistorico[0]->mes, 2);
    $oSomaValorDepreciado->mes              = $aBemHistorico[0]->mes;
    $oSomaValorDepreciado->total            = $nValorDepreciado;
    
    $aValoresDepreciado[$iIndice] = $oSomaValorDepreciado;
  }
}

$head1 = "Hist�rico de Bens Depreciado.";
$head2 = "Impress�o: {$sTipoImpressao}";
if (!empty($sPeriodo)) {
  $head3 = "Periodo de: {$sPeriodo}";
}
if (!empty($sClasse)) {
  $head4 = "Classifica��o de: {$sClasse}";
}
if (!empty($sDepartamento)) {
  $head5 = "Departamento: {$sDepartamento}";
}

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;

/**
 * Imprime o relat�rio de acordo com o Tipo de Impress�o selecionado:
 * S = Sint�tico
 * A = Anal�tico
 * T = Acumulado
 */

switch ($oGet->sImpressao) {
	
	case "S": 
		
		$nTotalValorDepreciado = 0;
		foreach ($aValoresDepreciado as $oPeriodoDepreciado) {
		
			if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
		
				setHeader($oPdf, $iHeigth, $oGet->sImpressao);
				$lPrimeiroLaco = false;
			}
		
			$oPdf->SetFont("arial", "", 7);
			$oPdf->Cell(50,  $iHeigth, "{$oPeriodoDepreciado->mes_descricao}", "TB", 0);
			$oPdf->Cell(50,  $iHeigth, "{$oPeriodoDepreciado->ano}", "TB", 0, "C");
			$oPdf->Cell(90, $iHeigth, $oPeriodoDepreciado->total, "TB", 1,"R");
			$nTotalValorDepreciado += $oPeriodoDepreciado->total;
		}
		$oPdf->SetFont("arial", "B", 7);
		$oPdf->Cell(100,  $iHeigth, "Total:",     											"TBR", 0, "R");
		$oPdf->Cell(90, $iHeigth, $nTotalValorDepreciado, "TB", 1,"R");
		break;
	case "A":  
		
		foreach ($aHistoricoDepreciacao as $iIndiceDepreciacao => $aHistorico) {
		
			$nValorDepreciado = 0;
			$sValorAquisicao  = 0;
			$sValorResidual   = 0;
			$sSaldo           = 0;
		
			if (!$lPrimeiroLaco) {
				setHeader($oPdf, $iHeigth, $oGet->sImpressao, false, $aHistorico[0]);
			}
			foreach ($aHistorico as $iIndiceHistorico => $oHistorico) {
		
				if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
		
					setHeader($oPdf, $iHeigth, $oGet->sImpressao, true, $aHistorico[0]);
					$lPrimeiroLaco = false;
				}
		
				$oPdf->SetFont("arial", "", 7);
				$oPdf->Cell(15,  $iHeigth, $oHistorico->placa,                         "TBR", 0, "C");
				$oPdf->Cell(45,  $iHeigth, substr($oHistorico->bem_descricao, 0, 30),           1, 0, "L");
				$oPdf->Cell(45,  $iHeigth, substr($oHistorico->tipo_depreciacao, 0, 30),        1, 0, "L");
				$oPdf->Cell(25,  $iHeigth, $oHistorico->classe,                                 1, 0, "C");
				$oPdf->Cell(27,  $iHeigth, $oHistorico->vida_util,                              1, 0, "C");
				$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_aquisicao, 'f'),      1, 0, "R");
				$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_residual, 'f'),       1, 0, "R");
				$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_depreciado, 'f'),     1, 0, "R");
				$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->saldo, 'f'), 					  "TBL", 1, "C");
		
				$sValorAquisicao  += $oHistorico->valor_aquisicao;
				$sValorResidual   += $oHistorico->valor_residual;
				$nValorDepreciado += $oHistorico->valor_depreciado;
				$sSaldo           += $oHistorico->saldo;
		
			}
			$oPdf->SetFont("arial", "B", 7);
			$oPdf->Cell(157,  $iHeigth, "Total:",     											"TBR", 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($sValorAquisicao, 'f'),      1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($sValorResidual, 'f'),       1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($nValorDepreciado, 'f'),     1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($sSaldo, 'f'), 				  "TBL", 1, "C");
		
		}
		break;
	case "T":  
		
		$nValorDepreciado = 0;
		$sValorAquisicao  = 0;
		$sValorResidual   = 0;
		$sSaldo           = 0;
		
		foreach ($aValoresDepreciadoAcumulado as $iIndiceHistorico => $oHistorico) {
		
			if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
		
				setHeader($oPdf, $iHeigth, $oGet->sImpressao, true, null);
				$lPrimeiroLaco = false;
			}
		
			$oPdf->SetFont("arial", "", 7);
			$oPdf->Cell(15,  $iHeigth, $oHistorico->placa,                         "TBR", 0, "C");
			$oPdf->Cell(45,  $iHeigth, substr($oHistorico->bem_descricao, 0, 30),           1, 0, "L");
			$oPdf->Cell(45,  $iHeigth, substr($oHistorico->tipo_depreciacao, 0, 30),        1, 0, "L");
			$oPdf->Cell(25,  $iHeigth, $oHistorico->classe,                                 1, 0, "C");
			$oPdf->Cell(27,  $iHeigth, $oHistorico->vida_util,                              1, 0, "C");
			$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_aquisicao, 'f'),      1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_residual, 'f'),       1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->valor_depreciado, 'f'),     1, 0, "R");
			$oPdf->Cell(30,  $iHeigth, db_formatar($oHistorico->saldo, 'f'), 					  "TBL", 1, "C");
		
			$sValorAquisicao  += $oHistorico->valor_aquisicao;
			$sValorResidual   += $oHistorico->valor_residual;
			$nValorDepreciado += $oHistorico->valor_depreciado;
			$sSaldo           += $oHistorico->saldo;
		
		}
		$oPdf->SetFont("arial", "B", 7);
		$oPdf->Cell(157,  $iHeigth, "Total:",     											"TBR", 0, "R");
		$oPdf->Cell(30,  $iHeigth, db_formatar($sValorAquisicao, 'f'),      1, 0, "R");
		$oPdf->Cell(30,  $iHeigth, db_formatar($sValorResidual, 'f'),       1, 0, "R");
		$oPdf->Cell(30,  $iHeigth, db_formatar($nValorDepreciado, 'f'),     1, 0, "R");
		$oPdf->Cell(30,  $iHeigth, db_formatar($sSaldo, 'f'), 				  "TBL", 1, "C");
		break;
}

/**
 * Insere o cabe�alho do relat�rio de acordo com o tipo: (Sint�tico, Anal�tico ou Acumulado)
 * @param object $oPdf
 * @param integer $iHeigth Altura da linha
 * @param strinf $sImpressao Tipo do relat�rio
 * @param boolean $lQuebraPagina Se � para quebra a p�gina
 * @param object $oBenHistorico 
 */
function setHeader($oPdf, $iHeigth, $sImpressao, $lQuebraPagina = true, $oBenHistorico = null) {

	$oPdf->setfont('arial', 'b', 9);
	$oPdf->setfillcolor(235);
	switch ($sImpressao) {
		
		case "S":
			
			$oPdf->AddPage();
			$oPdf->Cell(50,  $iHeigth, "M�s", "TBR", 0, "C", 1);
			$oPdf->Cell(50,  $iHeigth, "Ano", "LTB", 0, "C", 1);
			$oPdf->Cell(90,  $iHeigth, "Valor", "TBL", 1, "C", 1);
			break;
		case "A":
			
	    if ($lQuebraPagina) {
	    	$oPdf->AddPage("L");
	    } else {
	    	$oPdf->ln(5);
	    }
	    if (!empty($oBenHistorico)) {
	    	
	    	$sPeriodo = "{$oBenHistorico->mes_descricao} / {$oBenHistorico->ano}";
		    $oPdf->Cell(277, $iHeigth, "Periodo: {$sPeriodo}", "TB", 1, "L", 1);
	    }
	    $oPdf->Cell(15,  $iHeigth, "Placa",            "TBR", 0, "C", 1);
	    $oPdf->Cell(45,  $iHeigth, "Bem",                  1, 0, "C", 1);
	    $oPdf->Cell(45,  $iHeigth, "Tipo Deprecia��o",     1, 0, "C", 1);
	    $oPdf->Cell(25,  $iHeigth, "Classifica��o",        1, 0, "C", 1);
			$oPdf->Cell(27,  $iHeigth, "Vida �til",            1, 0, "C", 1);
	    $oPdf->Cell(30,  $iHeigth, "Vlr. Aquisi��o",       1, 0, "C", 1);
	    $oPdf->Cell(30,  $iHeigth, "Vlr. Residual",        1, 0, "C", 1);
	    $oPdf->Cell(30,  $iHeigth, "Vlr. Depreciado",      1, 0, "C", 1);
	    $oPdf->Cell(30,  $iHeigth, "Vlr. Depreci�vel",    "TBL", 1, "C", 1);
			break;
		case "T":
			
			$oPdf->AddPage("L");
			$oPdf->Cell(15,  $iHeigth, "Placa",            "TBR", 0, "C", 1);
			$oPdf->Cell(45,  $iHeigth, "Bem",                  1, 0, "C", 1);
			$oPdf->Cell(45,  $iHeigth, "Tipo Deprecia��o",     1, 0, "C", 1);
			$oPdf->Cell(25,  $iHeigth, "Classifica��o",        1, 0, "C", 1);
			$oPdf->Cell(27,  $iHeigth, "Vida �til",            1, 0, "C", 1);
			$oPdf->Cell(30,  $iHeigth, "Vlr. Aquisi��o",       1, 0, "C", 1);
			$oPdf->Cell(30,  $iHeigth, "Vlr. Residual",        1, 0, "C", 1);
			$oPdf->Cell(30,  $iHeigth, "Vlr. Depreciado",      1, 0, "C", 1);
			$oPdf->Cell(30,  $iHeigth, "Vlr. Depreci�vel",		 "TBL", 1, "C", 1);
			
			break;
	}
  
}

/**
 * 
 * Converte uma data do formato banco para formato de leitura brasileiro
 * @param unknown_type $sData
 */
function formataData($sData) {
  return implode("-", array_reverse(explode("/", $sData)));
}

$oPdf->Output();