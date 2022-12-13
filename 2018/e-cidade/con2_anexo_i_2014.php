<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
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
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");

$oGet             = db_utils::postMemory($_GET);
$iAnoUsu          = db_getsession("DB_anousu");
$iCodigoRelatorio = $oGet->codrel;
$iCodigoPeriodo   = $oGet->periodo;
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

$aLinhas = array();
try {


  $oAnexo  = new AnexoBalancoOrcamentarioDCASP($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  $oAnexo->setInstituicoes(implode(",", $aInstituicoes));
  $aLinhas = $oAnexo->getLinhasRelatorio();
  $aLinhas = $oAnexo->getDados();


} catch (Exception $eErro) {

  $sMensagem = $eErro->getMessage();
  echo $sMensagem;
  exit;

}


$head1 = "ANEXO I - DEMONSTRATIVO DE EXECUÇÃO DOS RESTOS A PAGAR NÃO PROCESSADOS";
$oPeriodo = new Periodo($iCodigoPeriodo);

$head3 = "Instituições: {$sInstituicoesSelecionadas}";
$head4 = "Período: {$oPeriodo->getDescricao()}";

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->addpage();

$iAlturaLinha = 4;

montarCabecalho($oPdf, $iAlturaLinha);

foreach ($aLinhas as $oStdLinha) {

  $iNivel = $oStdLinha->oLinhaRelatorio->getNivel();

  $sBorda = "R";
  $sBordaUltimaColuna = "";
  
  if ($oStdLinha->ordem == 9) {

    $sBorda = "TRB";
    $sBordaUltimaColuna = "TB";
  }

  $nSaldoFinal = ($oStdLinha->exanterior+$oStdLinha->exanterior3112) - $oStdLinha->liquidados - $oStdLinha->cancelados;

  $oPdf->cell(70, $iAlturaLinha, str_repeat(" ", $iNivel).$oStdLinha->descricao, $sBorda, 0, "L");
  $oPdf->cell(40, $iAlturaLinha, db_formatar($oStdLinha->exanterior, 'f'), $sBorda, 0, "R");
  $oPdf->cell(40, $iAlturaLinha, db_formatar($oStdLinha->exanterior3112, 'f'), $sBorda, 0, "R");
  $oPdf->cell(30, $iAlturaLinha, db_formatar($oStdLinha->liquidados, 'f'), $sBorda, 0, "R");
  $oPdf->cell(30, $iAlturaLinha, db_formatar($oStdLinha->pagos, 'f'), $sBorda, 0, "R");
  $oPdf->cell(35, $iAlturaLinha, db_formatar($oStdLinha->cancelados, 'f'), $sBorda, 0, "R");
  $oPdf->cell(30, $iAlturaLinha, db_formatar($nSaldoFinal, 'f'), $sBordaUltimaColuna, 1, "R"); 
}

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
 * Monta o cabeçalho do relatório
 * @param PDF $oPdf
 * @param $iAlturaLinha
 * @return bool
 */
function montarCabecalho(PDF $oPdf, $iAlturaLinha) {

  $iAlturaCabecalho = $oPdf->getY();
  $oPdf->MultiCell(70, $iAlturaLinha, "\n\nRESTOS À PAGAR\nNÃO PROCESSADOS\n\n", "TR", "C", 1);
  $oPdf->setXY(80, $iAlturaCabecalho);
  $oPdf->cell(80, $iAlturaLinha, "INSCRITOS", 1, 1, "C", 1);
  $oPdf->setXY(80, $iAlturaCabecalho+$iAlturaLinha);
  $oPdf->MultiCell(40, $iAlturaLinha, "\nEM EXERCÍCIOS\nANTERIORES\n\n", "TRL", "C", 1);
  $oPdf->setXY(120, $iAlturaCabecalho+$iAlturaLinha);
  $oPdf->MultiCell(40, $iAlturaLinha, "EM 31 DE\nDEZEMBRO DO\nEXERCICIO\nANTERIOR", "TRL", "C", 1);
  $oPdf->setXY(160, $iAlturaCabecalho);
  $oPdf->cell(30, $iAlturaLinha*5, "LIQUIDADOS", "TRL", 1, "C", 1);
  $oPdf->setXY(190, $iAlturaCabecalho);
  $oPdf->cell(30, $iAlturaLinha*5, "PAGOS", "TRL", 1, "C", 1);
  $oPdf->setXY(220, $iAlturaCabecalho);
  $oPdf->cell(35, $iAlturaLinha*5, "CANCELADOS", "TRL", 1, "C", 1);
  $oPdf->setXY(255, $iAlturaCabecalho);
  $oPdf->cell(30, $iAlturaLinha*5, "SALDO", "TL", 1, "C", 1);
  $oPdf->cell(70, $iAlturaLinha, ""   , "BR", 0, "C", 1);
  $oPdf->cell(40, $iAlturaLinha, "(a)", "LRB", 0, "C", 1);
  $oPdf->cell(40, $iAlturaLinha, "(b)", "LRB", 0, "C", 1);
  $oPdf->cell(30, $iAlturaLinha, "(c)", "LRB", 0, "C", 1);
  $oPdf->cell(30, $iAlturaLinha, "(d)", "LRB", 0, "C", 1);
  $oPdf->cell(35, $iAlturaLinha, "(e)", "LB", 0, "C", 1);
  $oPdf->cell(30, $iAlturaLinha, "(f)=(a+b-c-e)", "LB", 1, "C", 1);
  return true;
}