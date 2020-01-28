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
require_once("model/contabilidade/relatorios/dcasp/BalancoFinanceiroDcasp.model.php");

define('LARGURA_PAGINA', 190);
define('COLUNA_ESQUERDA', 1);
define('COLUNA_DIREITA', 2);

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $oGet->periodo;
$iCodigoRelatorio  = $oGet->codrel;
$sListaInstituicao = str_replace('-', ', ', $oGet->db_selinstit);  

$rsDadosInstituicoes = db_query("select codigo, nomeinst, nomeinstabrev from db_config where codigo in ({$sListaInstituicao}) ");
$aCodigoInstituicoes = array();
$aDescricaoInstituicoes = array();

$aDadosInstituicao = db_utils::getCollectionByRecord($rsDadosInstituicoes);

foreach ( $aDadosInstituicao as $oDadosInstituicao ) {

  $aDescricaoInstituicoes[] = $oDadosInstituicao->nomeinst;
  $aCodigoInstituicoes[] = $oDadosInstituicao->codigo;
}

$sDescricaoInstituicoes = implode(', ' , $aDescricaoInstituicoes);
$sCodigosInstituicoes = implode(', ' , $aCodigoInstituicoes);

$oBalancoFinanceiro = new BalancoFinanceiroDcasp($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
$oBalancoFinanceiro->setInstituicoes($sCodigosInstituicoes);
$aDados             = $oBalancoFinanceiro->getDados();

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oBalancoFinanceiro->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "BALANÇO FINANCEIRO";
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

/**
 * Primeiras as 6 linhas do relatorio 
 */
for ($iIndice = 1; $iIndice <= 6; $iIndice++ ) {

  linha($oPdf, $aDados[$iIndice], COLUNA_ESQUERDA, $oGet);
  linha($oPdf, $aDados[$iIndice + 11], COLUNA_DIREITA, $oGet); 
}

linhasContas($oPdf, $aDados[6], $aDados[17]);

/**
 * Deducoes da receita orcamentaria
 */
linha($oPdf, $aDados[7], COLUNA_ESQUERDA, $oGet);
linha($oPdf, null, COLUNA_DIREITA, $oGet);

/**
 * Ultimas 3 linhas do relatorio 
 */
for ($iIndice = 8; $iIndice <= 10; $iIndice++ ) {

  linha($oPdf, $aDados[$iIndice], COLUNA_ESQUERDA, $oGet);
  linha($oPdf, $aDados[$iIndice + 10], COLUNA_DIREITA, $oGet); 
}

/**
 * Rodape com totalizadores
 */
rodape($oPdf, $aDados[11], $aDados[21], $oGet);

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oBalancoFinanceiro->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oBalancoFinanceiro->getRelatorioContabil()->assinatura($oPdf, 'BG');
$oPdf->output();

/**
 * Imprime linha do relatorio
 * 
 * @param  PDF $oPdf    
 * @param  StdClass $oDados 
 * @param  integer $iColuna 
 * @return void          
 */
function linha(PDF $oPdf, StdClass $oDados = null, $iColuna = COLUNA_ESQUERDA, $oGet = null) {

  $oPdf->setfont('arial', '', 6);
  
  $sDescricao          = null;
  $nValorAtual         = null;
  $nValorAnterior      = null;
  $sAlignValorAnterior = "R";
  
  if ( $iColuna == COLUNA_ESQUERDA ) {
    $oPdf->ln();
  }

  //var_dump($oGet->imprimirValorExercicioAnterior);
  
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

  // validamos se vai ser exibido o saldo anterior de acordo com o filtro na tela ($oGet->imprimirValorExercicioAnterior) 
  if ( isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior == 'false' ) {
    
    $nValorAnterior      = "-";
    $sAlignValorAnterior = "C";
  }
  
  if ($iColuna == COLUNA_DIREITA) {
  
    $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'L', 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'L', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'L', 0, $sAlignValorAnterior);
    return;
   }

  $oPdf->cell(larguraColuna(30), 3, $sDescricao, 'R', 0, 'L');
  $oPdf->cell(larguraColuna(10), 3, $nValorAtual, 'R', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nValorAnterior, 'R', 0, $sAlignValorAnterior);  
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
     
      $oDadosConta->nivel = $oDadosEsquerda->nivel + 2;      
      $aContasEsquerda[] = $oDadosConta;
    }
  }
 
  if ($oDadosDireita->desdobrar) {

    foreach ($oDadosDireita->contas as $iConta => $oDadosConta) {
     
      $oDadosConta->nivel = $oDadosDireita->nivel + 2;
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

    linha($oPdf, $oDadosEsquerda, COLUNA_ESQUERDA, $oGet);
    linha($oPdf, $oDadosDireita, COLUNA_DIREITA, $oGet);
  }

}

/**
 * Imprime rodape com totalizadores
 * 
 * @param  PDF $oPdf           
 * @param  StdClass $oDadosEsquerda 
 * @param  StdClass $oDadosDireita  
 * @return void
 */
function rodape(PDF $oPdf, StdClass $oDadosEsquerda, StdClass $oDadosDireita, $oGet = null) { 
  
  $sAlinhaTotalAnterior   = "R";
  $nTotalAnteriorEsquerda = trim(db_formatar($oDadosEsquerda->vlrexanter,'f'));
  $nTotalAnteriorDireita  = trim(db_formatar($oDadosDireita->vlrexanter,'f'));
  
  // validamos se vai ser exibido o saldo anterior de acordo com o filtro na tela ($oGet->imprimirValorExercicioAnterior)
  if ( isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior == 'false' ) {
  
    $nTotalAnteriorEsquerda = "-";
    $nTotalAnteriorDireita  = "-";
    $sAlinhaTotalAnterior   = "C";
  }
  
  
  $oPdf->ln();
  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(30), 3, $oDadosEsquerda->descricao, 'TRB', 0, 'L');
  $oPdf->cell(larguraColuna(10), 3, trim(db_formatar($oDadosEsquerda->vlrexatual,'f')), 'TRB', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nTotalAnteriorEsquerda, 'TRB', 0, $sAlinhaTotalAnterior);
  
  $oPdf->cell(larguraColuna(30), 3, $oDadosDireita->descricao, 'TRB', 0, 'L');
  $oPdf->cell(larguraColuna(10), 3, trim(db_formatar($oDadosDireita->vlrexatual,'f')), 'TRB', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nTotalAnteriorDireita, 'TLB', 0, $sAlinhaTotalAnterior);
  $oPdf->setfont('arial', '', 6);
}

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(50), 7, "INGRESSOS", 'TBR', 0, 'C');
  $oPdf->cell(larguraColuna(50), 7, "DISPÊNDIOS", 'TBL', 1, 'C');    
  
  $oPdf->cell(larguraColuna(30), 7, "ESPECIFICAÇÃO", 'TBR', 0, 'C');
  $oPdf->cell(larguraColuna(10), 7, "Exercício Atual", 1, 0, 'C');
  $oPdf->cell(larguraColuna(10), 7, "Exercício Anterior", 1, 0, 'C');
  
  $oPdf->cell(larguraColuna(30), 7, "ESPECIFICAÇÃO", 1, 0, 'C');
  $oPdf->cell(larguraColuna(10), 7, "Exercício Atual", 1, 0, 'C');
  $oPdf->cell(larguraColuna(10), 7, "Exercício Anterior", 'TBL', 0, 'C');  
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