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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_orcparamseq_classe.php");

$oGet          = db_utils::postMemory($_GET);
$orcparamrel   = new cl_orcparamrel;
$classinatura  = new cl_assinatura;
$clempresto    = new cl_empresto;
$clorcparamseq = new cl_orcparamseq;
$iCodigoPeriodo= $oGet->periodo;

define ('LARGURA_PAGINA',279);

$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=N�o existe Institui��o RPPS.');
} else {
  $oInstit  = db_utils::fieldsMemory($rsInstit,0);
}

$oBalancoOrcamentario = new BalancoOrcamentarioRPPS(db_getsession("DB_anousu"), 125, $oGet->periodo); 
$aDados               = $oBalancoOrcamentario->getDados();

$head2 =  $oInstit->nomeinst;
$head3 = "BALANCO OR�AMENT�RIO DO REGIME PR�PRIO DE PREVID�NCIA SOCIAL";

if ($oGet->periodo == 17) {

  $head4 = "JANEIRO DE ".db_getsession("DB_anousu");

} else {

  $oDaoPeriodo = new cl_periodo();
  $sSqlPeriodo = $oDaoPeriodo->sql_query_file($oGet->periodo);
  $rsPeriodo   = $oDaoPeriodo->sql_record($sSqlPeriodo);

  if (!$rsPeriodo) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Per�odo informado n�o cadastrado no sistema.');
  }

  $oPeriodo =db_utils::fieldsMemory($rsPeriodo, 0);
  $head4 = "JANEIRO A ".strtoupper($oPeriodo->o114_descricao." DE ".db_getsession("DB_anousu"));
}

$oPdf = new PDF('L');
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',7);
$oPdf->addpage();

$oPdf->setfont('arial', '', 6);

cabecalho($oPdf);

$iPosicaoLinha = $oPdf->GetY();
$lDireita = false;
$aLinhasFinais = array(17, 18, 19, 50, 51, 52);

foreach ($aDados as $iIndice => $oDados) {

  if ($iIndice == 17) {

    for ($iIndiceEspaco = 1; $iIndiceEspaco <= 14; $iIndiceEspaco++) {
      linha($oPdf);
    }
    
  }

  if (in_array($iIndice,$aLinhasFinais)) {

    linha($oPdf, $oDados, true, true, $lDireita);
    continue;
  }

  if ($iIndice == 20) {

    $oPdf->SetLeftMargin(LARGURA_PAGINA/2 + $oPdf->lMargin);
    $oPdf->setY($iPosicaoLinha + 1);
    $lDireita = true;
  }

  linha($oPdf, $oDados, false, true, $lDireita);
}


$oPdf->SetLeftMargin(10);

/** 
 * Notas Explicativas
 */
$oPdf->ln();
$oBalancoOrcamentario->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();

/** 
 * Assinaturas
 */
$oBalancoOrcamentario->getRelatorioContabil()->assinatura($oPdf, 'BG');

$oPdf->output();

/**
 * Imprime linha do relatorio
 * 
 * @param  PDF $oPdf    
 * @param  StdClass $oDados 
 * @param  integer $iColuna 
 * @return void          
 */
function linha(PDF $oPdf, StdClass $oDados = null, $lTotal = false, $lPreencher = false, $lDireita = false) {

  $oPdf->setfont('arial', '', 6);
  
  $sDescricao      = null;
  $nValorPrevisao  = null;
  $nValorExecucao  = null;
  $nValorDiferenca = null;
  $nValorFixacao = null;
  
  $oPdf->ln();

  if ( !empty($oDados) ) {

    if ( isset($oDados->previsao) ) {         
      $nValorPrevisao = trim(db_formatar($oDados->previsao, 'f'));
    }

    if ( isset($oDados->execucao) ) {         
      $nValorExecucao = trim(db_formatar($oDados->execucao, 'f'));
    }

    if ( isset($oDados->diferenca) ) {         
      $nValorDiferenca = trim(db_formatar($oDados->diferenca, 'f'));
    }

    if ( isset($oDados->fixacao) ) {         
      $nValorFixacao = trim(db_formatar($oDados->fixacao, 'f'));
    }
   
    if ( isset($oDados->totalizar) && $oDados->totalizar ) {
      $oPdf->setfont('arial', 'b', 6);    
    }
    
    $sDescricao = relatorioContabil::getIdentacao($oDados->nivel) . $oDados->descricao;
  }

  if ($lTotal && $lDireita) {
  
    $oPdf->cell(larguraColuna(20), 3, $sDescricao, 1, 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorFixacao, 1, 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorExecucao, 1, 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorDiferenca, 'TBL', 0, 'R');
    return;
  }

  if ($lTotal) {   
    
    $oPdf->cell(larguraColuna(20), 3, $sDescricao, 'TRB', 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorPrevisao, 1, 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorExecucao, 1, 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorDiferenca, 1, 0, 'R');
    return;
  }

  if ($lDireita) {

    $oPdf->cell(larguraColuna(20), 3, $sDescricao, 0, 0, 'L');
    $oPdf->cell(larguraColuna(10), 3, $nValorFixacao, 'L', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorExecucao, 'L', 0, 'R');
    $oPdf->cell(larguraColuna(10), 3, $nValorDiferenca, 'L', 0, 'R');
    return;    
  }

  $oPdf->cell(larguraColuna(20), 3, $sDescricao, 0, 0, 'L');
  $oPdf->cell(larguraColuna(10), 3, $nValorPrevisao, 'LR', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nValorExecucao, 'LR', 0, 'R');
  $oPdf->cell(larguraColuna(10), 3, $nValorDiferenca, 'LR', 0, 'R');
}

/**
 * Imprime cabecalho do relatorio
 * 
 * @param  PDF $oPdf [description]
 * @return void
 */
function cabecalho(PDF $oPdf) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(larguraColuna(100),4,"ART. 103 DA LEI 4.320/1964.","T",1,"L");

  $oPdf->cell(larguraColuna(50), 4, "RECEITAS", 'TRB', 0, 'C');
  $oPdf->cell(larguraColuna(50), 4, "DESPESAS", 'TLB', 1, 'C');
  
  $oPdf->cell(larguraColuna(20), 4, "T�TULO", 'TRB', 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "PREVIS�O", 1, 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "EXECU��O", 1, 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "DIFEREN�A", 1, 0, 'C');
  
  $oPdf->cell(larguraColuna(20), 4, "T�TULO", 'TLB', 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "FIXA��O", 'TLB', 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "EXECU��O", 'TLB', 0, 'C');
  $oPdf->cell(larguraColuna(10), 4, "DIFEREN�A", 'TLB', 0, 'C');
  
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