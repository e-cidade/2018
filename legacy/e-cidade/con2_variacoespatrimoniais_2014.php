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
require_once("model/contabilidade/relatorios/dcasp/VariacaoPatrimonialDCASP.model.php");

define('LARGURA_PAGINA', 190);

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

$oVariacoesPatrimoniais = new VariacaoPatrimonialDCASP($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
$oVariacoesPatrimoniais->setInstituicoes($sCodigosInstituicoes);
$aDados             = $oVariacoesPatrimoniais->getDados();

$sDescricaoPeriodo = "";
$aPeriodos         = $oVariacoesPatrimoniais->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "VARIAÇÕES PATRIMONIAIS";
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

foreach ($aDados as $iIndice => $oDados) {

  $sDescricao           = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  $nValorAtual          = trim(db_formatar($oDados->vlrexatual,'f'));
  $nValorAnterior       = trim(db_formatar($oDados->vlrexanter,'f'));
  $sAlinhaSaldoAnterior = "R";
  
  if ( isset($oGet->imprimirValorExercicioAnterior) && $oGet->imprimirValorExercicioAnterior == 'false' ) {
    
    $nValorAnterior       = "-";
    $sAlinhaSaldoAnterior = "C";
  }
  
  
  $oPdf->setfont('arial', '', 6); 

  /**
   * Quebra de pagina
   */
  if ($oPdf->GetY() > $oPdf->h - 30) {

    $oPdf->line($oPdf->lMargin, $oPdf->getY(), LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY());
    $oPdf->AddPage();
    cabecalho($oPdf);
  }

  if ($oDados->totalizar) {

    $oPdf->setfont('arial', 'b', 6);

    if ($iIndice == 1 || $iIndice == 40) {

      $oPdf->cell(larguraColuna(64), 7, $sDescricao, 'TRB', 0, 'C');
      $oPdf->cell(larguraColuna(18), 7, $nValorAtual, 1, 0, 'R');
      $oPdf->cell(larguraColuna(18), 7, $nValorAnterior, 'TLB', 1, $sAlinhaSaldoAnterior);
      continue;      
    }

    if ($iIndice == 90) {

      $oPdf->cell(larguraColuna(64), 3, $sDescricao, 'TRB', 0, 'L');
      $oPdf->cell(larguraColuna(18), 3, $nValorAtual, 1, 0, 'R');
      $oPdf->cell(larguraColuna(18), 3, $nValorAnterior, 'TLB', 1, $sAlinhaSaldoAnterior);

      $oPdf->cell(larguraColuna(64), 7, 'VARIAÇÕES PATRIMONIAIS QUALITATIVAS (decorrentes da execução orçamentária)', 'TBR', 0, 'C'); 
            
      $oPdf->cell(larguraColuna(18), 7, "Exercício Atual", 'TBL', 0, 'C');
      $oPdf->cell(larguraColuna(18), 7, "Exercício Anterior", 'TBL', 1, 'C'); 
      continue;
    }

    $oPdf->setfont('arial', 'b', 6);
    $oPdf->cell(larguraColuna(64), 3, $sDescricao, 'R', 0, 'L');
    $oPdf->cell(larguraColuna(18), 3, $nValorAtual, 0, 0, 'R');
    $oPdf->cell(larguraColuna(18), 3, $nValorAnterior, 'L', 1, $sAlinhaSaldoAnterior);
    continue;
  } 

  $oPdf->cell(larguraColuna(64), 3, $sDescricao, 'R', 0, 'L');
  $oPdf->cell(larguraColuna(18), 3, $nValorAtual, 'R', 0, 'R');
  $oPdf->cell(larguraColuna(18), 3, $nValorAnterior, 'L', 1, $sAlinhaSaldoAnterior);

  if ( $iIndice == 94 ) {
    $oPdf->line($oPdf->lMargin, $oPdf->getY(), LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY());
  }

}

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oVariacoesPatrimoniais->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oVariacoesPatrimoniais->getRelatorioContabil()->assinatura($oPdf, 'BG');
$oPdf->output();

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(100), 7, "VARIAÇÕES PATRIMONIAIS QUANTITATIVAS", 'TB', 1, 'C');
  $oPdf->cell(larguraColuna(64), 7, "", 'TBR', 0, 'C');    
  $oPdf->cell(larguraColuna(18), 7, "Exercício Atual", 'BL', 0, 'C');
  $oPdf->cell(larguraColuna(18), 7, "Exercício Anterior", 'BL', 1, 'C');
 
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