<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("model/contabilidade/relatorios/dcasp/BalancoPatrimonialDcasp.model.php");

define('LARGURA_PAGINA', 190);

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $oGet->periodo;
$iCodigoRelatorio  = $oGet->codrel;
$sListaInstituicao = str_replace('-', ', ', $oGet->db_selinstit);  

$rsDadosInstituicoes    = db_query("select codigo, nomeinst, nomeinstabrev from db_config where codigo in ({$sListaInstituicao}) ");
$aCodigoInstituicoes    = array();
$aDescricaoInstituicoes = array();

$aDadosInstituicao = db_utils::getCollectionByRecord($rsDadosInstituicoes);

foreach ( $aDadosInstituicao as $oDadosInstituicao ) {

  $aDescricaoInstituicoes[] = $oDadosInstituicao->nomeinst;
  $aCodigoInstituicoes[]    = $oDadosInstituicao->codigo;
}

$sDescricaoInstituicoes = implode(', ' , $aDescricaoInstituicoes);
$sCodigosInstituicoes   = implode(', ' , $aCodigoInstituicoes);

$oBalancoPatrimonial = new BalancoPatrimonialDcasp($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
$oBalancoPatrimonial->setInstituicoes($sCodigosInstituicoes);
$aDados             = $oBalancoPatrimonial->getDados();

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oBalancoPatrimonial->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "BALANÇO PATRIMONIAL";
$head4 = "EXERCÍCIO: {$iAnoUsu}";
$head5 = "PERÍODO : ".$sDescricaoPeriodo;

if ($oGet->consolidado == 'true') {  
  $head6 = "INSTITUIÇÕES : CONSOLIDAÇÃO GERAL";
} else {
  $head6 = "INSTITUIÇÕES : ".$sDescricaoInstituicoes;
}

$oPdf = new PDF();
$oPdf->SetLeftMargin(10);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->Addpage();

$oPdf->setfont('arial', '', 6);

cabecalho($oPdf);

$iPosicaoLinha = $oPdf->GetY();

/**
 * Imprime linhas do relatorio 
 */
for ($iIndice = 1; $iIndice <= 40; $iIndice++ ) {
  linha($oPdf, $aDados[$iIndice]);
}

linha($oPdf, $aDados[41], true, true);
$oPdf->SetLeftMargin(LARGURA_PAGINA/2 + $oPdf->lMargin);
$oPdf->setY($iPosicaoLinha + 4);

for ($iIndice = 42; $iIndice <= 57; $iIndice++ ) {
  linha($oPdf, $aDados[$iIndice], false, true, true);
}

linha($oPdf, $aDados[58], true, true, true);

$oPdf->ln();
$oPdf->cell(larguraColuna(50), 3, 'PATRIMÔNIO LÍQUIDO', 'TB', 1, 'C');

cabecalho($oPdf, true);

for ($iIndice = 59; $iIndice <= 69; $iIndice++ ) { 
  linha($oPdf, $aDados[$iIndice], false, true, true);
}

$oPdf->setY($oPdf->getY() - 1);
for ($iIndice = 0; $iIndice <= 7; $iIndice++) {  
  linha($oPdf, null, false, true, true);
}

linha($oPdf, $aDados[70], true, true, true);
linha($oPdf, $aDados[71], true, true, true);

$oPdf->SetLeftMargin(10);
$oPdf->ln(6);
$iPosicaoLinha = $oPdf->getY();

linha($oPdf, $aDados[72], true);
linha($oPdf, $aDados[73], true);

$oPdf->SetLeftMargin(LARGURA_PAGINA/2 + $oPdf->lMargin);
$oPdf->setY($iPosicaoLinha);

linha($oPdf, $aDados[74],true, true, true);
linha($oPdf, $aDados[75],true, true, true);

$oPdf->SetLeftMargin(10);
$oPdf->ln();

$oPdf->cell(larguraColuna(80), 3, $aDados[76]->descricao, 'TBR', 0, 'L');
$oPdf->cell(larguraColuna(10), 3, trim(db_formatar($aDados[76]->vlrexatual,'f')), 'BL', 0, 'R');
$oPdf->cell(larguraColuna(10), 3, trim(db_formatar($aDados[76]->vlrexanter,'f')), 'BL', 0, 'R');  

$oPdf->ln(9);
cabecalho($oPdf);
$oPdf->ln();

$oPdf->cell(larguraColuna(50), 3, $aDados[77]->descricao, 'TBR', 0, 'C');
$oPdf->cell(larguraColuna(50), 3, $aDados[79]->descricao, 'TBL', 0, 'C');

linhasContas($oPdf, $aDados[77], $aDados[79]);

linha($oPdf, $aDados[78], true);
linha($oPdf, $aDados[80], true, false, true);

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oBalancoPatrimonial->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oBalancoPatrimonial->getRelatorioContabil()->assinatura($oPdf, 'BG');

$oPdf->output();



function getParametrosEnvioExibeAnoAnterior() {
  
  $oGet              = db_utils::postMemory($_GET);
  $lExibeAnoAnterior = true;
  
  if (isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior == 'false' ){
    $lExibeAnoAnterior = false;
  }
  return $lExibeAnoAnterior;
}


/**
 * Imprime linha do relatorio
 * 
 * @param  PDF $oPdf    
 * @param  StdClass $oDados 
 * @param  integer $iColuna 
 * @return void          
 */
function linha(PDF $oPdf, StdClass $oDados = null, $lTotal = false,  $lQuebrarLinha = true, $lDireita = false) {

  $oPdf->setfont('arial', '', 6);
  
  $sDescricao         = null;
  $nValorAtual        = null;
  $nValorAnterior     = null;
  $sAlinhaAnoAnterior = "R";

  if ( $lQuebrarLinha ) {
    $oPdf->ln();    
  }

  if ( !empty($oDados) ) {

    if ( isset($oDados->vlrexatual) ) {            
      $nValorAtual = trim(db_formatar($oDados->vlrexatual, 'f'));
    }

    if ( isset($oDados->vlrexanter) ) {            
      $nValorAnterior = trim(db_formatar($oDados->vlrexanter, 'f'));
    }

    if ( isset($oDados->totalizar) && $oDados->totalizar ) {
      $oPdf->setfont('arial', 'b', 6);    
    }
    
    $sDescricao = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  }
   
  if (!getParametrosEnvioExibeAnoAnterior()) {
  
    $nValorAnterior     = "-";
    $sAlinhaAnoAnterior = "C";
  }  
 
  if ($lTotal && $lDireita) {

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'TLB', 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'TLB', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'TBL', 0, $sAlinhaAnoAnterior);
    return;
  }

  if ($lTotal) {

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'TBR', 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'TBR', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'TBR', 0, $sAlinhaAnoAnterior);
    return;
  }

 if ($lDireita) {

    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 0, 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'L', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'L', 0, $sAlinhaAnoAnterior);
    return;    
  }

  $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'R', 0, 'L');
  $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'R', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'R', 0, $sAlinhaAnoAnterior);
}

/**
 * Imprime as linhas com contas 
 * 
 * @param  PDF $oPdf
 * @param  StdClass $oDadosEsquerda 
 * @param  StdClass $oDadosDireita  
 * @return void                
 */
function linhasContas(PDF $oPdf, StdClass $oDadosEsquerda, StdClass $oDadosDireita) {
  
  /**
   * Arrays com as contas de cada coluna
   */
  $aContasEsquerda = array();
  $aContasDireita  = array();

  if ($oDadosEsquerda->desdobrar) {

    foreach ($oDadosEsquerda->contas as $iConta => $oDadosConta) {   
      $oDadosConta->nivel = 0; 
      $aContasEsquerda[] = $oDadosConta;
    }
  }
 
  if ($oDadosDireita->desdobrar) {

    foreach ($oDadosDireita->contas as $iConta => $oDadosConta) {
      $oDadosConta->nivel = 0;
      $aContasDireita[] = $oDadosConta;
    }
  }

  $iTotalLinhas = max(count($aContasEsquerda), count($aContasDireita));

  /**
   * Monta as linhas   
   */
  for ($iIndice = 0; $iIndice < $iTotalLinhas ; $iIndice++) { 

    /**
     * Quebra de pagina
     */
    if ($oPdf->GetY() > $oPdf->h - 30) {

      $oPdf->line($oPdf->lMargin, $oPdf->getY() + 3, LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY() + 3);
      $oPdf->AddPage();
      cabecalho($oPdf);
    }

    $oDadosEsquerda = null;
    $oDadosDireita  = null;

    /**
     * Verifica se linha atual tem conta na coluna da esquerda
     */
    if (!empty($aContasEsquerda[$iIndice])) {
      $oDadosEsquerda = $aContasEsquerda[$iIndice];
    }

    /**
     * Verifica se linha atual tem conta na coluna da direita
     */
    if (!empty($aContasDireita[$iIndice])) {
      $oDadosDireita = $aContasDireita[$iIndice];
    }

    linha($oPdf, $oDadosEsquerda, false, true);
    linha($oPdf, $oDadosDireita, false, false, true);
  }

}

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf, $lMetade = false) {

  $oPdf->setfont('arial', 'b', 6);

  if ($lMetade == true){
  
    $oPdf->cell(larguraColuna(30), 7, "ESPECIFICAÇÃO", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Atual", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Anterior", 'TB', 0, 'C');  
  } else {
  
    $oPdf->cell(larguraColuna(30), 7, "ESPECIFICAÇÃO", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Atual", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Anterior", 'TBR', 0, 'C');
    
    $oPdf->cell(larguraColuna(30), 7, "ESPECIFICAÇÃO", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Atual", 'TBR', 0, 'C');
    $oPdf->cell(larguraColuna(10), 7, "Exercício Anterior", 'TB', 0, 'C');  
  }

  $oPdf->setfont('arial', '', 6);
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