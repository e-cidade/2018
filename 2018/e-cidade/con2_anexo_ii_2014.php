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
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_app.utils.php");
define('LARGURA_PAGINA', 190);

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $oGet->periodo;
$iCodigoRelatorio  = $oGet->codrel;
$sInstituicoes    = $oGet->db_selinstit;

/*
 * Defino a descrições das instituições que estou imprimindo o relatório
 */
$aInstituicoes = explode("-", $sInstituicoes);
$aDescricoesInstituicoes = array();
foreach ($aInstituicoes as $iCodigoInstituicao) {

  $oInstituicao   = InstituicaoRepository::getInstituicaoByCodigo($iCodigoInstituicao);
  $aDescricoesInstituicoes[] = $oInstituicao->getDescricaoAbreviada();
}
$sInstituicoesSelecionadas = implode(", ", $aDescricoesInstituicoes);

$aDados = array();

try {

  $oAnexo  = new AnexoBalancoOrcamentarioDCASP($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  $oAnexo->setInstituicoes(implode(",", $aInstituicoes));
  $aDados = $oAnexo->getLinhasRelatorio();
  $aDados = $oAnexo->getDados();

} catch (Exception $eErro) {

  $sMensagem = $eErro->getMessage();
  echo $sMensagem;
  exit;
}
$oPeriodo = new Periodo($iCodigoPeriodo);
$head3 = "ANEXO 2 - DEMONSTRATIVO DE EXECUÇÃO DOS RESTOS A PAGAR PROCESSADOS E NÃO PROCESSADOS LIQUIDADOS";
$head4 = "EXERCÍCIO: {$iAnoUsu}";
$head5 = "PERÍODO : {$oPeriodo->getDescricao()}";

if ($oGet->consolidado == 'true') {  
  $head6 = "INSTITUIÇÕES : CONSOLIDAÇÃO GERAL";
} else {
  $head6 = "INSTITUIÇÕES : ".$sInstituicoesSelecionadas;
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

  $alturaLinha = 3;
  $nSaldoFinal = ($oDados->exanterior+$oDados->exanterior3112) - $oDados->pagos - $oDados->cancelados;

  $sDescricao                  = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  $nValoExercicioAnterior      = db_formatar($oDados->exanterior, 'f');
  $nValorExercicioAnterior3112 = db_formatar($oDados->exanterior3112, 'f');
  $nValorPagos                 = db_formatar($oDados->pagos, 'f');
  $nValorCancelado             = db_formatar($oDados->cancelados, 'f');
  $nValorSaldo                 = db_formatar($nSaldoFinal, 'f');
  
  $sBorda       = "R";
  $sUltimaBorda = "";
  if ($oDados->ordem == 9) {
    $sUltimaBorda = "T";
    $sBorda       = "TRB";
  }


  $oPdf->cell(larguraColuna(30), $alturaLinha, $sDescricao, $sBorda, 0, 'L');
  $oPdf->cell(larguraColuna(14), $alturaLinha, $nValoExercicioAnterior, $sBorda, 0, 'R');
  $oPdf->cell(larguraColuna(14), $alturaLinha, $nValorExercicioAnterior3112, $sBorda, 0, 'R');
  $oPdf->cell(larguraColuna(14), $alturaLinha, $nValorPagos, $sBorda, 0, 'R');
  $oPdf->cell(larguraColuna(14), $alturaLinha, $nValorCancelado, $sBorda, 0, 'R');
  $oPdf->cell(larguraColuna(14), $alturaLinha, $nValorSaldo, $sUltimaBorda, 1, 'R');

  }

$oPdf->line($oPdf->lMargin, $oPdf->getY(), LARGURA_PAGINA + $oPdf->lMargin, $oPdf->getY());

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oAnexo->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oAnexo->getRelatorioContabil()->assinatura($oPdf, 'BG');

$oPdf->output();

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial','', 5);
  $oPdf->cell(larguraColuna(30), 6, "RESTOS A PAGAR PROCESSADOS E", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(28), 3, "INSCRITOS", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "", 'T', 1, 'C');

  $oPdf->cell(larguraColuna(30), 6, "NÃO PROCESSADOS LIQUIDADOS", 'R', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "EM EXERCÍCIOS", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "EM 31 DE DEZEMBRO", 'TR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "PAGOS", 'R', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "CANCELADOS", 'R', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "SALDO", '', 1, 'C');
  
  $oPdf->cell(larguraColuna(30), 3, "", 'BR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "ANTERIORES (a)", 'BR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "DO EXERCÍCIO ANTERIOR (b)", 'BR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "(c)", 'BR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "(d)", 'BR', 0, 'C');
  $oPdf->cell(larguraColuna(14), 3, "(e)=(a+b-c-d)", 'B', 1, 'C');

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