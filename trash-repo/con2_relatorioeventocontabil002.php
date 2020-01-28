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
require_once("libs/db_sql.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_libtxt.php");
require_once("dbforms/db_funcoes.php");
require_once("model/contabilidade/EventoContabil.model.php");
require_once("model/contabilidade/EventoContabilLancamento.model.php");
require_once("model/contabilidade/RegraLancamentoContabil.model.php");

$oGet = db_utils::postMemory($_GET);
if (!isset($oGet->iCodigoDocumento) || empty($oGet->iCodigoDocumento)) {
	
	db_redireciona("db_erros.php?fechar=true&db_erro=Código do Documento não informado.");
	exit;
}
$oDaoConhistdoc     = db_utils::getDao('conhistdoc');
$sSqlBuscaDescricao = $oDaoConhistdoc->sql_query_file($oGet->iCodigoDocumento);
$rsBuscaDescricao   = $oDaoConhistdoc->sql_record($sSqlBuscaDescricao);
$oDadoDocumento     = db_utils::fieldsMemory($rsBuscaDescricao, 0);

$oGet->iAno = db_getsession("DB_anousu");

$oEventoContabil    = new EventoContabil($oGet->iCodigoDocumento, $oGet->iAno);
$aLancamentosEvento = $oEventoContabil->getEventoContabilLancamento();
$aDadosImprimir     = array();

//var_dump($aLancamentosEvento); exit;

foreach ($aLancamentosEvento as $iIndiceLancamento => $oLancamento) {

	/*
	 * Dados do Lancamento que serão impressos no relatório
	 */
	$oStdClassLancamento 											 = new stdClass();
	$oStdClassLancamento->iCodigoLancamento    = $oLancamento->getSequencialLancamento();
	$oStdClassLancamento->iOrdem               = $oLancamento->getOrdem();
	$oStdClassLancamento->sDescricaoLancamento = $oLancamento->getDescricao();
	$oStdClassLancamento->aRegrasLancamento    = array();
	$aRegrasLancamento                         = $oLancamento->getRegrasLancamento();
	foreach ($aRegrasLancamento as $iIndiceRegra => $oRegra) {
		
		/*
		 * Utilizo a função para buscar os dados estruturais e descricao da conta reduzida
		 */
		$oReduzidoDebito                   = getInformacaoReduzidos($oRegra->getContaDebito());
		$oReduzidoCredito                  = getInformacaoReduzidos($oRegra->getContaCredito());
    if (! $oReduzidoCredito || ! $oReduzidoDebito ) {
    	continue;
    }
		/*
		 *	Crio um objeto com os dados da regra que serão impressos no relatório 
		 */
		$oStdClassRegra                           = new stdClass();
		$oStdClassRegra->iReduzidoDebito          = $oRegra->getContaDebito();
		$oStdClassRegra->sEstruturalDebito        = $oReduzidoDebito->sEstrutural;
		$oStdClassRegra->sDescricaoDebito         = $oReduzidoDebito->sDescricao;
		$oStdClassRegra->iReduzidoCredito         = $oRegra->getContaCredito();
		$oStdClassRegra->sEstruturalCredito       = $oReduzidoCredito->sEstrutural;
		$oStdClassRegra->sDescricaoCredito        = $oReduzidoCredito->sDescricao;	
		$oStdClassLancamento->aRegrasLancamento[] = $oStdClassRegra;
	}
	
	if (!empty($oGet->iCodigoLancamento) && ($oLancamento->getSequencialLancamento() == $oGet->iCodigoLancamento)) {
		
		$aDadosImprimir[] = $oStdClassLancamento;
		break;
	} else if (empty($oGet->iCodigoLancamento)){
		$aDadosImprimir[] = $oStdClassLancamento;
	}
}

// echo ("<pre>".print_r($aDadosImprimir, 1)."</pre>");exit;
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','',8);
$oPdf->SetAutoPageBreak(false);
$iAltura = 4;
$head2    = "Relatório de Eventos Contábeis";
$head3    = "Ano: {$oGet->iAno}";
$head4    = "Documento: {$oGet->iCodigoDocumento} - {$oDadoDocumento->c53_descr}";
$lEscreveHeader = true;

showTituloRelatorio($oPdf, $iAltura, $oGet->iCodigoDocumento, $oDadoDocumento->c53_descr);

$iLancamentoAnterior = $aDadosImprimir[0]->iCodigoLancamento;

foreach ($aDadosImprimir as $iIndice => $oEvento) {

	if ($oPdf->GetY() > $oPdf->h - 30 || $lEscreveHeader) {

		showTituloLancamento($oPdf, $iAltura, $oEvento->iOrdem, $oEvento->sDescricaoLancamento);
		showHeaderDados($oPdf, $iAltura);
		$lEscreveHeader = false;
	}
	
	if ($iLancamentoAnterior != $oEvento->iCodigoLancamento) {
		
		showTituloLancamento($oPdf, $iAltura, $oEvento->iOrdem, $oEvento->sDescricaoLancamento);
		showHeaderDados($oPdf, $iAltura);
	}
	
	foreach ($oEvento->aRegrasLancamento as $iIndiceRegra => $oRegra) {
		
  	if ($oPdf->GetY() > $oPdf->h - 20) {
		
			showTituloLancamento($oPdf, $iAltura, $oEvento->iOrdem, $oEvento->sDescricaoLancamento);
			showHeaderDados($oPdf, $iAltura);
		}
		
		$oPdf->cell(20, $iAltura, $oRegra->iReduzidoDebito,   0, 0, "C", 0);
		$oPdf->cell(30, $iAltura, $oRegra->sEstruturalDebito, 0, 0, "C", 0);
		$oPdf->cell(90, $iAltura, $oRegra->sDescricaoDebito,  0, 0, "L", 0);
		
		$oPdf->cell(20, $iAltura, $oRegra->iReduzidoCredito,   0, 0, "C", 0);
		$oPdf->cell(30, $iAltura, $oRegra->sEstruturalCredito, 0, 0, "C", 0);
		$oPdf->cell(90, $iAltura, $oRegra->sDescricaoCredito,  0, 1, "L", 0);
	}
	$iLancamentoAnterior = $oEvento->iCodigoLancamento;
}


function getInformacaoReduzidos($iCodigoReduzido) {
	
	$oDaoConplano    = db_utils::getDao('conplanoreduz');
	$sWhereReduzido  = "c61_reduz = {$iCodigoReduzido}";
	$sSqlBuscaDados  = $oDaoConplano->sql_query(null, null, 'c60_estrut, c60_descr', null, $sWhereReduzido);
	$rsBuscaReduzido = $oDaoConplano->sql_record($sSqlBuscaDados);
	if ($oDaoConplano->numrows == 0) {
		return false;
	}
	$oDadoReduzido   = db_utils::fieldsMemory($rsBuscaReduzido, 0);
	
	$oStdClassLancamento   					  = new stdClass();
	$oStdClassLancamento->sEstrutural = $oDadoReduzido->c60_estrut;
	$oStdClassLancamento->sDescricao  = $oDadoReduzido->c60_descr;
	return $oStdClassLancamento;
}

function showTituloRelatorio($oPdf, $iAltura, $iCodigoDocumento, $iDescricaoDocumento) {
	
	$oPdf->addPage("L");
	$oPdf->setfont('arial', 'b', 8);
	$oPdf->MultiCell(280, $iAltura, "Documento: {$iCodigoDocumento} - $iDescricaoDocumento", 1, "L", 1);
}

function showTituloLancamento($oPdf, $iAltura, $iOrdem, $sDescricao) {
	
	if ($oPdf->GetY() > $oPdf->h - 30) {
		$oPdf->addPage("L");
	}
	
	$oPdf->setfont('arial', 'b', 8);
	$oPdf->MultiCell(280, $iAltura, "Lançamento: {$iOrdem} - $sDescricao", 1, "L", 1);
}

function showHeaderDados($oPdf, $iAltura) {
	
	$oPdf->setfont('arial', 'b', 8);
	$oPdf->cell(140, $iAltura, "Conta Débito" , 1, 0, "C", 1);
	$oPdf->cell(140, $iAltura, "Conta Crédito", 1, 1, "C", 1);
	
	$oPdf->cell(20, $iAltura, "Reduzido",   1, 0, "C", 1);
	$oPdf->cell(30, $iAltura, "Estrutural", 1, 0, "C", 1);
	$oPdf->cell(90, $iAltura, "Descrição",  1, 0, "C", 1);
	
	$oPdf->cell(20, $iAltura, "Reduzido",   1, 0, "C", 1);
	$oPdf->cell(30, $iAltura, "Estrutural", 1, 0, "C", 1);
	$oPdf->cell(90, $iAltura, "Descrição",  1, 1, "C", 1);
	$oPdf->setfont('arial', '', 8);
	
}
$oPdf->Output();
?>