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

define('LARGURA_PAGINA', 190);
define('MENSAGENS', 'patrimonial.contratos.con4_mapaexecucao002.');

try {

  $oDaoAcordo = new cl_acordo();
  $oGet = db_utils::postMemory($_GET);

  if (empty($oGet->acordo)) {
    throw new Exception("Acordo não informado.");
  }

  $iAcordo = $oGet->acordo;

  $sCampos  = "ac16_sequencial, ac16_numeroacordo, ac16_anousu, ac16_numeroprocesso, ac16_datainicio, ac16_datafim, ";
  $sCampos .= "ac29_notafiscal, ac29_valor, descrdepto, e60_numemp, e60_codemp, e60_anousu, e60_vlremp, ac16_valor";

  $sSqlMapaExecucao = $oDaoAcordo->sql_queryDadosMapaExecucao($iAcordo, $sCampos); 
  $rsMapaExecucao = $oDaoAcordo->sql_record($sSqlMapaExecucao);

  if ($oDaoAcordo->erro_status == '0') {
    throw new Exception(_M(MENSAGENS . 'erro_buscar_dados_acordo'));
  }

  $aDadosMapaExecucao = db_utils::getCollectionByRecord($rsMapaExecucao);

  if (empty($aDadosMapaExecucao)) {
    throw new Exception(_M(MENSAGENS . 'acordo_sem_execucao'));
  }

  $oDados = new stdClass();

  foreach ($aDadosMapaExecucao as $oDadosMapaExecucao) {

    $oDados->iAcordo         = $oDadosMapaExecucao->ac16_sequencial;
    $oDados->sAcordo         = $oDadosMapaExecucao->ac16_numeroacordo . '/' . $oDadosMapaExecucao->ac16_anousu;
    $oDados->sVigencia       = db_formatar($oDadosMapaExecucao->ac16_datainicio, 'd') . ' a ' . db_formatar($oDadosMapaExecucao->ac16_datafim, 'd');
    $oDados->sDepartamento   = $oDadosMapaExecucao->descrdepto;
    $oDados->sNumeroProcesso = $oDadosMapaExecucao->ac16_numeroprocesso;
    $oDados->nValor          = $oDadosMapaExecucao->ac16_valor;
    $oDados->nSaldo          = $oDadosMapaExecucao->ac16_valor;

    $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->sNumero = $oDadosMapaExecucao->e60_codemp . '/' . $oDadosMapaExecucao->e60_anousu;
    $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->nValor = $oDadosMapaExecucao->e60_vlremp;

    if (empty($oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->nSaldo)) {
      $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->nSaldo = $oDadosMapaExecucao->e60_vlremp;
    }

    if (empty($oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->aNotas[$oDadosMapaExecucao->ac29_notafiscal])) {
      $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->aNotas[$oDadosMapaExecucao->ac29_notafiscal] = 0;
    }
    
    $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->aNotas[$oDadosMapaExecucao->ac29_notafiscal] += $oDadosMapaExecucao->ac29_valor;
    $oDados->aEmpenhos[$oDadosMapaExecucao->e60_numemp]->nSaldo -= $oDadosMapaExecucao->ac29_valor;
  } 

  /**
   * Percorre os empenhos do acordo e calcula saldo contratual
   */
  foreach ($oDados->aEmpenhos as $oDadosEmpenho) {
    $oDados->nSaldo -= $oDadosEmpenho->nValor;
  }

  $head2 = "Mapa de execução";
  $head3 = "Departamento:  " . $oDados->sDepartamento;

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->SetAutoPageBreak(true);
  $oPdf->setfillcolor(235);
  $oPdf->Addpage();

  coluna('Contrato', $oDados->sAcordo, 9, 30, false, "L");
  coluna('Código', $oDados->iAcordo, 7, 30, false, "L");
  $oPdf->ln();

  coluna('Vigência', $oDados->sVigencia, 9, 30, false, "L");
  coluna('Valor Contratual', 'R$ ' . trim(db_formatar($oDados->nValor, 'f')) , 15, 30, false, "L");
  $oPdf->ln();

  coluna('Processo', $oDados->sNumeroProcesso, 9, 30, false, "L");
  coluna('Saldo Contratual', 'R$ ' . trim(db_formatar($oDados->nSaldo, 'f')), 15, 30, false, "L");
  $oPdf->ln();

  foreach ($oDados->aEmpenhos as $oDadosEmpenho) {
  
    empenho($oDadosEmpenho->sNumero, $oDadosEmpenho->nValor, $oDadosEmpenho->nSaldo);
    $lPreencherLinhaNota = false;

    foreach ($oDadosEmpenho->aNotas as $sNota => $nValorNota) {

      $lPreencherLinhaNota = !$lPreencherLinhaNota;
      nota($sNota, $nValorNota, !$lPreencherLinhaNota);
    }
  }

  $oPdf->output();

} catch (Exception $oErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro=" . urlEncode($oErro->getMessage()));
}    

function empenho($sEmpenho, $nValor, $nSaldo) {

  $oPdf = $GLOBALS['oPdf'];
  $oPdf->ln();
  $oPdf->setfillcolor(220);

  coluna('Empenho', $sEmpenho, 10, 20, true);
  coluna('Valor Empenho', 'R$ ' . trim(db_formatar($nValor, 'f')), 10, 20, true);
  coluna('Saldo Empenho', 'R$ ' . trim(db_formatar($nSaldo, 'f')), 10, 30, true);

  $oPdf->ln();
}

function nota($sDescricao, $nValor, $lPreencher = false) {

  if (empty($sDescricao)) {
    $sDescricao = 'Não informada';
  } else {
    $sDescricao = utf8_decode($sDescricao);
  }

  $oPdf = $GLOBALS['oPdf'];
  $oPdf->setfillcolor(240);

  coluna('Nota Fiscal', $sDescricao, 13, 50, $lPreencher);
  coluna('Valor Nota', 'R$ ' . trim(db_formatar($nValor, 'f')), 10, 27, $lPreencher);

  $oPdf->SetLeftMargin(10);
  $oPdf->ln();
}


function coluna($sDescricao, $sValor, $iLarguraDescricao = 30, $iLarguraValor = 15, $lPreencher = false, $sAlinhamento = "R") {

  $oPdf = $GLOBALS['oPdf'];

  if ($oPdf->h <= $oPdf->getY() + 20) {
    $oPdf->Addpage();
  }

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell(larguraColuna($iLarguraDescricao), 5, $sDescricao . ':', null, 0, $sAlinhamento, $lPreencher);
  $oPdf->setfont('arial', '', 0); 
  $oPdf->cell(larguraColuna($iLarguraValor), 5, $sValor, null, 0, 'L', $lPreencher);
}

/**
 * Largura da coluna 
 * 
 * @param string $sTipo 
 * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha   
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {   

  if ($nPorcentagem == 0) {
    return LARGURA_PAGINA;
  }

  return round($nPorcentagem / 100 * LARGURA_PAGINA, 2);
} 
