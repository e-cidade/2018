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
require_once("libs/db_utils.php");  
require_once("libs/db_app.utils.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("model/contabilidade/relatorios/dcasp/FluxoCaixaDCASP.model.php");

define('LARGURA_PAGINA', 190);

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $oGet->periodo;
$iCodigoRelatorio  = $oGet->codrel;
$sListaInstituicao = str_replace('-', ', ', $oGet->db_selinstit);  

$rsDadosInstituicoes    = db_query("select codigo, nomeinst, nomeinstabrev from db_config where codigo in ({$sListaInstituicao}) ");
$aCodigoInstituicoes    = array();
$aDescricaoInstituicoes = array();

function getParametrosEnvioExibeAnoAnterior() {

  $oGet              = db_utils::postMemory($_GET);
  $lExibeAnoAnterior = true;

  if (isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior == 'false' ){
    $lExibeAnoAnterior = false;
  }
  return $lExibeAnoAnterior;
}

$aDadosInstituicao = db_utils::getCollectionByRecord($rsDadosInstituicoes);

foreach ( $aDadosInstituicao as $oDadosInstituicao ) {

  $aDescricaoInstituicoes[] = $oDadosInstituicao->nomeinst;
  $aCodigoInstituicoes[] = $oDadosInstituicao->codigo;
}

$sDescricaoInstituicoes = implode(', ' , $aDescricaoInstituicoes);
$sCodigosInstituicoes = implode(', ' , $aCodigoInstituicoes);

$oFluxoCaixa = new FluxoCaixaDCASP($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
$oFluxoCaixa->setInstituicoes($sCodigosInstituicoes);
$aDados             = $oFluxoCaixa->getDados();

$sDescricaoPeriodo = "";
$aPeriodos         = $oFluxoCaixa->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "DEMOSTRAÇÃO DOS FLUXOS DE CAIXA";
$head4 = "EXERCÍCIO: {$iAnoUsu}";
$head5 = "PERÍODO : ".$sDescricaoPeriodo;

if ($oGet->consolidado == 'true') {  
  $head6 = "INSTITUIÇÕES : CONSOLIDAÇÃO GERAL";
} else {
  $head6 = "INSTITUIÇÕES : ".$sDescricaoInstituicoes;
}


$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->Addpage();

$oPdf->setfont('arial', '', 6);

cabecalho($oPdf);
linhaTitulo($oPdf, "FLUXOS DE CAIXA DAS ATIVIDADES DAS OPERAÇÕES");

foreach ($aDados as $iIndice => $oDados) {

  if ($iIndice == 32) {
    linhaTitulo($oPdf, "FLUXOS DE CAIXA DAS ATIVIDADES DE INVESTIMENTO");
  }

  if ($iIndice == 39) {
    linhaTitulo($oPdf, "FLUXOS DE CAIXA DAS ATIVIDADES DE FINANCIAMENTO");
  }

  if ($iIndice == 44) {
    linhaTitulo($oPdf, "APURAÇÃO DO FLUXO DE CAIXA DO PERÍODO");
  }

  $lTotais = false;
  
  if ($iIndice >= 44) {
    $lTotais = true;
  }

  linha($oPdf, $oDados, $lTotais);

  if ($iIndice == 20) {
    linhasFuncoes($oPdf, $oDados);
  }

}

$oPdf->line($oPdf->lMargin, $oPdf->getY(), LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY());

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oFluxoCaixa->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oFluxoCaixa->getRelatorioContabil()->assinatura($oPdf, 'BG');
$oPdf->output();

function linhasFuncoes(PDF $oPdf, StdClass $oDados) {  

  foreach ( $oDados->funcao as $oDadosFuncao ) {

    $nValorAtual    = !empty($oDadosFuncao->vlrexatual) ? $oDadosFuncao->vlrexatual : 0;
    $nValorAnterior = !empty($oDadosFuncao->vlrexanter) ? $oDadosFuncao->vlrexanter : 0;

    if ( $nValorAtual == 0 && $nValorAnterior == 0 ) {
      continue;
    }

    $oDadosFuncao->vlrexatual = $nValorAtual;
    $oDadosFuncao->vlrexanter = $nValorAnterior;
    $oDadosFuncao->descricao  = $oDadosFuncao->nome;  
    $oDadosFuncao->nivel      = $oDados->nivel + 2;  

    linha($oPdf, $oDadosFuncao);
  }

}

function linhaTitulo(PDF $oPdf, $sTitulo, $lCompleta = false) {
  
  $oPdf->setfont('arial', 'b', 6);

  if ($lCompleta) {
    $oPdf->cell(larguraColuna(100), 4, $sTitulo, 1, 0, 'C');
  } else {
    
    $oPdf->cell(larguraColuna(64), 4, $sTitulo, 'R', 0, 'L');    
    $oPdf->cell(larguraColuna(18), 4, null, 'R', 0, 'R');
    $oPdf->cell(larguraColuna(18), 4, null, null, 0, 'R');
  }

  $oPdf->setfont('arial', '', 6);
  $oPdf->ln();
}

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(64), 7, "", 'TBR', 0, 'C');    
  $oPdf->cell(larguraColuna(18), 7, "Exercício Atual", 'TRB', 0, 'C');
  $oPdf->cell(larguraColuna(18), 7, "Exercício Anterior", 'TB', 1, 'C'); 
  $oPdf->setfont('arial', '', 6);
}

function linha(PDF $oPdf, stdclass $oDados, $lTotais = false) {

  /**
   * Quebra de pagina
   */
  if ($oPdf->GetY() > $oPdf->h - 30) {

    $oPdf->line($oPdf->lMargin, $oPdf->getY(), LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY());
    $oPdf->AddPage();
    cabecalho($oPdf);
  }
    
  $oPdf->setfont('arial', '', 6);

  if (isset($oDados->totalizar) && $oDados->totalizar) {
    $oPdf->setfont('arial', 'b', 6);
  }

  $nValorAtual        = trim(db_formatar($oDados->vlrexatual,'f'));
  $nValorAnterior     = trim(db_formatar($oDados->vlrexanter,'f'));
  $sDescricao         = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  $sAlinhaAnoAnterior = "R";

  if (!getParametrosEnvioExibeAnoAnterior()) {
  
    $nValorAnterior     = "-";
    $sAlinhaAnoAnterior = "C";
  }
  
  
  if ( $lTotais ) {
    
    $oPdf->cell(larguraColuna(64), 3, $sDescricao, 'RTB', 0, 'L');
    $oPdf->cell(larguraColuna(18), 3, $nValorAtual, 'RTB', 0, 'R');
    $oPdf->cell(larguraColuna(18), 3, $nValorAnterior, 'TB', 1, $sAlinhaAnoAnterior);
    return true;
  }

  $oPdf->cell(larguraColuna(64), 3, $sDescricao, 'R', 0, 'L');
  $oPdf->cell(larguraColuna(18), 3, $nValorAtual, 'R', 0, 'R');
  $oPdf->cell(larguraColuna(18), 3, $nValorAnterior, null, 1, $sAlinhaAnoAnterior);
}

/**
 * Largura da coluna 
 * 
 * @param string $sTipo 
 * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha   
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {   

  if ( $nPorcentagem == 0 ) {
    return LARGURA_PAGINA;
  }

  return round($nPorcentagem / 100 * LARGURA_PAGINA, 2);
}