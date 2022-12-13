<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
ini_set("display_errors", "on");
$oGet              = DB_utils::postMemory($_GET);
$iAnoUsu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $periodo;
$iCodigoRelatorio  = $oGet->codrel;
$sListaInstituicao = str_replace('-', ', ', $oGet->db_selinstit);
$iAlturaLinha      = 4;

$rsInstituicoes = db_query("select codigo, nomeinst, nomeinstabrev
                              from db_config
                             where codigo in (".str_ireplace('-', ',', $oGet->db_selinstit).") ");
$descr_inst = '';
$xvirg      = '';
$flag_abrev = false;
for ($xins = 0; $xins < pg_numrows($rsInstituicoes); $xins++) {

  db_fieldsmemory($rsInstituicoes, $xins);
  if (strlen(trim($nomeinstabrev)) > 0) {

    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }
  $xvirg = ', ';
}
if ($flag_abrev == false) {

  if (strlen($descr_inst) > 42) {
    $descr_inst = substr($descr_inst, 0, 150);
  }
}

try {

  $oBalancoDcasp = new BalancoOrcamentarioDcasp($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  $oBalancoDcasp->setInstituicoes($sListaInstituicao);
  $aLinhas       = $oBalancoDcasp->getDados();

} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
}

$oRelatorioContabil = new relatorioContabil($iCodigoRelatorio, false);

/**
 * Adiciona descri��o do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oRelatorioContabil->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {

  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "BALAN�O OR�AMENT�RIO";
$head4 = "EXERC�CIO ".$iAnoUsu;

if ($oGet->consolidado == 'true') {  
  $head5 = "INSTITUI��ES : CONSOLIDA��O GERAL";
} else {
  $head5 = "INSTITUI��ES : ".$descr_inst;
}

$head6 = "PER�ODO : ".$sDescricaoPeriodo;

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$lPrimeiraVoltaReceita = true;
$lPrimeiraVoltaDespesa = true;

$aLinhasComBordaEspecial = array("62" => 'TB',
                                 "70" => 'TB',
                                 "71" => 'TB',
                                 "73" => 'TB',
                                 "75" => 'B',
                                 "86" => 'TB',
                                 "94" => 'T',
                                 "95" => 'TB',
                                 "96" => 'TB'
                                );
foreach ($aLinhas as $oLinha) {

  $sBorda = '';
  if (isset($aLinhasComBordaEspecial[$oLinha->ordem])) {
    $sBorda .= $aLinhasComBordaEspecial[$oLinha->ordem];
  }
  if ($oLinha->ordem <= 75) {

    $nPrevisaoInicial    = trim(db_formatar($oLinha->previni, 'f'));
    $nPrevisaoAtualizada = db_formatar($oLinha->prevatu, 'f');
    $nReceitaRealizada   = db_formatar($oLinha->recrealiza, 'f');
    $nSaldo              = db_formatar($oLinha->saldo, 'f');

    if ($oLinha->ordem == 73) {
      $nPrevisaoInicial = '-';
    }
    if ($oLinha->ordem == 71 || $oLinha->ordem == 72 || $oLinha->ordem == 73) {
      $nSaldo = '-';
    }
    escreverCabecalhoReceita($oPdf, $lPrimeiraVoltaReceita, $iAlturaLinha);
    $oPdf->Cell(90, $iAlturaLinha, relatorioContabil::getIdentacao($oLinha->nivel).$oLinha->descricao, "R{$sBorda}");
    $oPdf->Cell(25, $iAlturaLinha, $nPrevisaoInicial, "R{$sBorda}", 0,  $nPrevisaoInicial != '-' ? "R" : "C");
    $oPdf->Cell(25, $iAlturaLinha, $nPrevisaoAtualizada, "R{$sBorda}", 0, 'R');
    $oPdf->Cell(25, $iAlturaLinha, $nReceitaRealizada, "R{$sBorda}", 0, 'R');
    $oPdf->Cell(25, $iAlturaLinha, $nSaldo, "L{$sBorda}", 1, $nSaldo != '-' ? "R" : "C");
    $lPrimeiraVoltaReceita = false;
  }

  if ($oLinha->ordem >= 76) {


    $nDotacaoInicial = db_formatar($oLinha->dotini, 'f');
    $nDotacaoAtualizada = db_formatar($oLinha->dotatu, 'f');
    $nDespesaEmpenhada = db_formatar($oLinha->despemp, 'f');
    $nDespesaLiquidada = db_formatar($oLinha->despliq, 'f');
    $nDespesaPaga = db_formatar($oLinha->desppag, 'f');
    $nSaldo = db_formatar($oLinha->saldo, 'f');

    if ($oLinha->ordem == 95) {
      $nDespesaLiquidada = '-';
      $nDespesaPaga = '-';
      $nSaldo = '-';
    }

    escreverCabecalhoDespesa($oPdf, $lPrimeiraVoltaDespesa, $iAlturaLinha);
    $oPdf->cell(70, $iAlturaLinha, relatorioContabil::getIdentacao($oLinha->nivel).$oLinha->descricao, "R{$sBorda}");
    $oPdf->cell(20, $iAlturaLinha, $nDotacaoInicial, "R{$sBorda}", 0, 'R');
    $oPdf->cell(20, $iAlturaLinha, $nDotacaoAtualizada, "R{$sBorda}", 0, 'R');
    $oPdf->cell(20, $iAlturaLinha, $nDespesaEmpenhada, "R{$sBorda}", 0, 'R');
    $oPdf->cell(20, $iAlturaLinha, $nDespesaLiquidada, "R{$sBorda}", 0, $nDespesaLiquidada !='-' ? "R" : "C");
    $oPdf->cell(20, $iAlturaLinha, $nDespesaPaga, "R{$sBorda}", 0, $nDespesaPaga !='-' ? "R" : "C");
    $oPdf->cell(20, $iAlturaLinha, $nSaldo, "L{$sBorda}", 1, $nSaldo !='-' ? "R" : "C");
    $lPrimeiraVoltaDespesa = false;
  }
}

if ($oPdf->GetY() > $oPdf->h - 30) {
  $oPdf->AddPage('P');
}

//Notas Explicativas
$oRelatorioContabil->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();


//Assinaturas
$oPdf->setfont('arial', '', 6);
$oRelatorioContabil->assinatura($oPdf, 'BG');

$oPdf->output();
function escreverCabecalhoReceita(PDF $oPdf, $lForcar = false, $iAlturaLinha) {

  if ($oPdf->getY() > $oPdf->h - 25 || $lForcar) {

    $oPdf->Line(10, $oPdf->getY(), 200, $oPdf->getY());
    $oPdf->AddPage();
    $iAlturaCabecalho = $oPdf->getY();
    $oPdf->Cell(90,  $iAlturaLinha * 2, 'RECEITAS OR�AMENT�RIAS', 'TRB', 0, 'C');
    $oPdf->MultiCell(25, $iAlturaLinha, "PREVIS�O\nINICIAL", "TBR", "C", 0);
    $oPdf->setxy(125, $iAlturaCabecalho);
    $oPdf->MultiCell(25, $iAlturaLinha, "PREVIS�O\nATUALIZADA (a)", "TBR", "C", 0);
    $oPdf->setxy(150, $iAlturaCabecalho);
    $oPdf->MultiCell(25, $iAlturaLinha, "RECEITAS\nREALIZADAS (b)", "TBR", "C", 0);
    $oPdf->setxy(175, $iAlturaCabecalho);
    $oPdf->MultiCell(25, $iAlturaLinha, "SALDO\nc = (b-a)", "TBL", "C", 0);
  }
}

function escreverCabecalhoDespesa(PDF $oPdf, $lForcar = false, $iAlturaLinha) {

  if ($oPdf->getY() > $oPdf->h - 25 || $lForcar) {

    if (!$lForcar) {

      $oPdf->Line(10, $oPdf->getY(), 200, $oPdf->getY());
      $oPdf->AddPage();
    }

    if ($lForcar) {
      $oPdf->ln();
    }
    $iAlturaCabecalho = $oPdf->getY();
    $oPdf->Cell(70,  $iAlturaLinha * 3, 'DESPESAS OR�AMENT�RIAS ', 'TRB', 0, 'C');
    $oPdf->MultiCell(20, $iAlturaLinha, "DOTA��O\nINICIAL\n(d)  ", "TBR", "C", 0);
    $oPdf->setxy(100, $iAlturaCabecalho);
    $oPdf->MultiCell(20, $iAlturaLinha, "DOTA��O\nATUALIZADA\n(e)", "TBR", "C", 0);
    $oPdf->setxy(120, $iAlturaCabecalho);
    $oPdf->MultiCell(20, $iAlturaLinha, "DESPESAS\nEMPENHADAS\n(f)", "TBR", "C", 0);
    $oPdf->setxy(140, $iAlturaCabecalho);
    $oPdf->MultiCell(20, $iAlturaLinha, "DESPESAS\nLIQUIDADAS\n(g)", "TBR", "C", 0);
    $oPdf->setxy(160, $iAlturaCabecalho);
    $oPdf->MultiCell(20, $iAlturaLinha, "DESPESAS\nPAGAS\n(h)", "TBR", "C", 0);
    $oPdf->setxy(180, $iAlturaCabecalho);
    $oPdf->MultiCell(20, $iAlturaLinha, "SALDO DA \nDOTA��O\n(i)=(e-f)", "TBL", "C", 0);

  }
}