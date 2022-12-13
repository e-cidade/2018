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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");

$oGet          = db_utils::postMemory($_GET);
$iAnousu       = db_getsession("DB_anousu");
$iInstituicao  = db_getsession("DB_instit");

/**
 * Busca descrição do Município da Instituição
 */
$rsInstituicao          = db_query("select munic from db_config where codigo = {$iInstituicao} ");
$oInstituicao           = db_utils::fieldsmemory($rsInstituicao,0);
$sDescricaoInstituicao  = "MUNICÍPIO DE " . $oInstituicao->munic;

$aWhere = array();
$aWhere [] = "t64_instit = {$iInstituicao}";

/**
 * Verifica Parâmetros passados por URL para que realize os filtros na pesquisa
 */
if(!empty($oGet->iClassificacaoInicial)) {
	$aWhere[] = " t64_class >= '{$oGet->iClassificacaoInicial}'";
}

if(!empty($oGet->iClassificacaoFinal)) {
	$aWhere[] = " t64_class <= '{$oGet->iClassificacaoFinal}'";
}

$sWhere   = implode(" and ", $aWhere);
$sCampos  = "  t64_class, c60_estrut, c60_descr, t52_bem, t52_descr, t52_valaqu, ";
$sCampos .= " t86_conplano, t64_descr, t30_descr, t44_valorresidual";

$sOrder   = " t64_class, t52_descr";

$oDaoBens = db_utils::getDao("bens");
$sSqlBens = $oDaoBens->sql_query_bensContas(null, $sCampos, $sOrder, $sWhere);
$rsBens   = $oDaoBens->sql_record($sSqlBens);

if ($oDaoBens->numrows == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado, verifique as datas e tente novamente');   
}

/**
 *  Cria um array separando por objetos Conta (StdClass)
 *  Cada objeto conta terá assosciada um valor total e um array de Bens
 */
$aContas = array();

for($iBem = 0; $iBem < $oDaoBens->numrows; $iBem++) {
	
	$oLinha  = db_utils::fieldsMemory($rsBens, $iBem);
	$oStdBem = new stdClass();
	
	$oStdBem->sEstruturalClassificacao = $oLinha->t64_class;
	$oStdBem->sDescricaoClassificacao  = $oLinha->t64_descr;
	
	$oStdBem->iContaClassificacao      = $oLinha->t86_conplano;
	$oStdBem->sEstruturalConta         = $oLinha->c60_estrut;
	$oStdBem->sDescricaoConta          = $oLinha->c60_descr;
	
	$oStdBem->iCodigoBem               = $oLinha->t52_bem;
	$oStdBem->sDescricaoBem            = $oLinha->t52_descr;
	$oStdBem->nValorBem                = $oLinha->t52_valaqu;
	$oStdBem->nValorResidual           = $oLinha->t44_valorresidual;
	$oStdBem->sDepartamento            = $oLinha->t30_descr;
	
	/**
	 * Se for escolhido a opção Valor contábil após a última reavaliação
	 * Deverá buscar na tabela benshistoricocalculobem
	 */
	if($oGet->iValorContabil == 2) {
		
		$oDaoBensHistoricoCalculoBens = db_utils::getDao("benshistoricocalculobem");
		$sCampos                      = "t58_valoratual";
		$sWhere                       = " t57_tipocalculo = 2 and t57_ano = {$iAnousu} and t58_bens = {$oStdBem->iCodigoBem}";
		$sOrder                       = " t58_sequencial desc limit 1";
		
		$sSqlValorBem                 = $oDaoBensHistoricoCalculoBens->sql_query_calculo(null, $sCampos, $sOrder, $sWhere);
		$rsValorBem                   = $oDaoBensHistoricoCalculoBens->sql_record($sSqlValorBem);
		
		if($oDaoBensHistoricoCalculoBens->numrows > 0) {
			$oStdBem->nValorBem = db_utils::fieldsMemory($rsValorBem, 0)->t58_valoratual;
		}
	}

	/**
	 * Classifica os bens e agrupa por contas
	 */
	if (isset($aContas[$oStdBem->iContaClassificacao])) {
		
		$aContas[$oStdBem->iContaClassificacao]->nValorContabilAcumulado +=  $oStdBem->nValorBem;
		
	} else {
		
		$oStdClassificacao = new stdClass();
		$oStdClassificacao->sEstruturalConta			      = $oStdBem->sEstruturalConta;
		$oStdClassificacao->sDescricaoConta 				    = $oStdBem->sDescricaoConta;
		$oStdClassificacao->nValorContabilAcumulado     = $oStdBem->nValorBem;
		$aContas[$oStdBem->iContaClassificacao] = $oStdClassificacao;
	}
	
	$aContas[$oStdBem->iContaClassificacao]->aBens[] = $oStdBem;
	
}

$head2 =  $sDescricaoInstituicao;
$head3 = "RELATÓRIO CONTÁBIL DE CONFERÊNCIA DE BENS";

/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 4;
$oPdf->SetFont('arial', '', 5);

/**
 * Imprime os dados de cada conta
 */
foreach($aContas as $iCodigoConta => $oConta) {
	
	$oPdf->SetFont('arial', 'b', 5);
	
	$oPdf->cell(190,$iAlturaLinha,"Conta: {$oConta->sEstruturalConta} - {$oConta->sDescricaoConta}","TLRB",1,"L",1);
	
	/**
	 * Se for um relatório Analítico, imprime todos os bens vinculados a conta
	 */
	if ($oGet->iModeloRelatorio == 1) { 
		imprimeBens($oConta->aBens, $oPdf, $iAlturaLinha);
	}
	
	$oPdf->SetFont('arial', 'b', 5);
	$oPdf->cell(240,$iAlturaLinha, "VALOR TOTAL:", "TB", 0, "R", 1);
	$oPdf->cell(40, $iAlturaLinha, db_formatar($oConta->nValorContabilAcumulado, "f"), "TB", 1, "C", 1);
	$oPdf->SetFont('arial', '', 5);
	$oPdf->ln(2);
}

/**
 * Imprime todos os bens vinculados a determinada conta 
 * @param array 	$aBens
 * @param PDF 		$oPdf
 * @param integer $iAlturaLinha
 */
function imprimeBens($aBens, $oPdf, $iAlturaLinha) {
	
	imprimirCabecalho($oPdf, $iAlturaLinha, true);
	
	foreach($aBens as $oBem) {
		
	  if($oPdf->gety() > $oPdf->h-15) {
	    imprimirCabecalho($oPdf, $iAlturaLinha, true);
	  }
	  
		$oPdf->setfont('arial','',5);
		
		$oPdf->cell(65,  $iAlturaLinha, $oBem->sEstruturalClassificacao ."-". $oBem->sDescricaoClassificacao, "TBR", 0, "L", 0); //@todo substrdescr
		$oPdf->cell(20,  $iAlturaLinha, $oBem->iCodigoBem, "TBR", 0, "C", 0);
		$oPdf->cell(105, $iAlturaLinha, substr($oBem->sDescricaoBem, 0 , 30), "TBR", 0, "L", 0);
		$oPdf->cell(50,  $iAlturaLinha, substr($oBem->sDepartamento, 0 , 30), "TBR", 0, "L", 0);
		$oPdf->cell(20,  $iAlturaLinha, db_formatar($oBem->nValorBem, 'f'), "TBR", 0, "R", 0);
		$oPdf->cell(20,  $iAlturaLinha, db_formatar($oBem->nValorResidual, 'f'), "TBL", 1, "R", 0);
	}
}


/**
 * 
 * Imprime cabecalho da conta
 * @param PDF 		$oPdf
 * @param integer $iAlturaLinha
 * @param boolean $lInicio
 */
function imprimirCabecalho(&$oPdf, $iAlturaLinha, $lInicio = false) {

  $oPdf->AddPage();
	if($oPdf->gety() > $oPdf->h-15 || $lInicio) {

		if (!$lInicio) {
			$oPdf->cell(190,$iAlturaLinha,'Continua na Página '.($oPdf->pageNo()+1)."/{nb}","T",1,"R",0);
			$oPdf->addpage();
			$oPdf->ln(2);
			$oPdf->cell(190,$iAlturaLinha,'Continuação '.($oPdf->pageNo()-1)."/{nb}","B",1,"R",0);
		}
		
		$oPdf->setfont('arial','b',5);
		$oPdf->cell(65, $iAlturaLinha,"Classificação", "TBR",0,"C",0);
		$oPdf->cell(20, $iAlturaLinha,"Código", "TBR",0,"C",0);
		$oPdf->cell(105,$iAlturaLinha,"Descrição", "TBR",0,"C", 0);
		$oPdf->cell(50, $iAlturaLinha,"Departamento", "TBR",0,"C", 0);
		$oPdf->cell(20, $iAlturaLinha,"Valor Contábil", "TBR",0,"C", 0);
		$oPdf->cell(20, $iAlturaLinha,"Valor Residual", "TBL",1,"C", 0);
		
	}
}

$oPdf->Output();
?>