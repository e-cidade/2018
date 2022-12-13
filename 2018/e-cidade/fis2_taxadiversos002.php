<?php
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("fpdf151/pdf.php");

$oGet    = \db_utils::postMemory($_GET);
$aStatus = array('0' => 'Calculado/Não Calculado', '1' => 'Calculado', '2' => 'Não Calculado');
$aGrupos = $oGet->grupo == 'T' ? buscaTodosGrupos() : array(GrupoTaxaDiversosRepository::getInstanciaPorCodigo($oGet->grupo));
$aGruposInvalidos = array();

$oConfig                     = new stdClass();
$oConfig->iAltura            = 4;
$oConfig->iTamanhoMaximo     = 192;
$oConfig->iEspacoInicial     = 2;
$oConfig->iEspacoCampos      = 59;
$oConfig->iEspacoDataInicio  = 38;
$oConfig->iEspacoDataFim     = 32;
$oConfig->iEspacoDataCalculo = 45;
$oConfig->iEspacoValor       = 132;
$oConfig->iNatureza          = $oGet->natureza;
$oConfig->iStatus            = $oGet->status;

try {

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->SetFillColor(235);

  /**
   * Percorre a coleção de grupos. Para cada grupo, uma nova página é iniciada
   */
  foreach($aGrupos as $oGrupo) {

    if(!validaImpressaoGrupo($oConfig, $oGrupo)) {
      $aGruposInvalidos[] = $oGrupo->getCodigo();
      continue;
    }
    
    $head1 = "Grupo: {$oGrupo->getDescricao()}";
    $head2 = "Status: {$aStatus[$oGet->status]}";

    $oPdf->AddPage();
    imprimeConteudo($oPdf, $oConfig, $oGrupo);
  }

  if(count($aGrupos) == count($aGruposInvalidos)) {
    throw new Exception("Não há lançamentos para o filtro selecionado.");
  }

  $oPdf->Output();

  
} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}

/**
 * Responsável por imprimir as informações da Natureza
 *
 * @param PDF $oPdf
 * @param stdClass $oConfig
 * @param GrupoTaxaDiversos $oGrupo
 */
function imprimeConteudo(PDF $oPdf, $oConfig, GrupoTaxaDiversos $oGrupo) {

  $nValorTotalGrupo = 0;

  foreach($oGrupo->getNaturezas() as $oNatureza) {

    /**
     * Validação para verificar se apenas uma determinada Natureza foi selecionada
     */
    if($oConfig->iNatureza != 'T' && $oConfig->iNatureza != $oNatureza->getCodigo()) {
      continue;
    }

    /**
     * Atualiza a coleção dos lançamentos, para em seguida percorrê-los e imprimir os dados agrupados
     */
    $aLancamentos = organizaLancamentos($oNatureza->getLancamentos(), $oConfig);

    if(count($aLancamentos) == 0) {
      continue;
    }

    $nValorTotalNatureza = 0;
    $sNatureza           = "{$oNatureza->getNatureza()}";

    $oPdf->SetFont('arial', 'b', 9);
    $oPdf->MultiCell($oConfig->iTamanhoMaximo, $oConfig->iAltura, $sNatureza, 'B');

    $iLarguraContribuinte = $oConfig->iTamanhoMaximo - $oConfig->iEspacoInicial;

    foreach($aLancamentos as $iCodigo => $oLancamento) {

      $sContrinbuinte  = $oLancamento->lCgm ? 'CGM: ' : 'Inscrição Municipal: ';
      $sContrinbuinte .= "{$iCodigo} - {$oLancamento->sCgm}";

      $oPdf->SetFont('arial', '', 8);
      $oPdf->Cell($oConfig->iEspacoInicial, $oConfig->iAltura, '', '', 0);
      $oPdf->Cell($iLarguraContribuinte,    $oConfig->iAltura, $sContrinbuinte, 'T', 1, '', 1);

      /**
       * Percorre as taxas lançadas para o CGM na Natureza
       */
      foreach($oLancamento->aTaxas as $oTaxa) {

        $oPdf->SetFont('arial', '', 8);

        $oPdf->Cell($oConfig->iEspacoInicial * 2, $oConfig->iAltura, '',                  '',  0);
        $oPdf->Cell($oConfig->iEspacoCampos,      $oConfig->iAltura, $oTaxa->sUnidade,    'B', 0);
        $oPdf->Cell($oConfig->iEspacoCampos,      $oConfig->iAltura, $oTaxa->sPeriodo,    'B', 0);
        $oPdf->Cell($oConfig->iEspacoDataInicio,  $oConfig->iAltura, $oTaxa->sDataInicio, 'B', 0);
        $oPdf->Cell($oConfig->iEspacoDataFim,     $oConfig->iAltura, $oTaxa->sDataFim,    'B', 1);

        foreach($oTaxa->aDebitosLancados as $oDebito) {

          $oPdf->Cell($oConfig->iEspacoInicial * 4, $oConfig->iAltura, '',                           '', 0);
          $oPdf->Cell($oConfig->iEspacoDataCalculo, $oConfig->iAltura, "- {$oDebito->sDataCalculo}", '', 0);
          $oPdf->Cell($oConfig->iEspacoValor,       $oConfig->iAltura, $oDebito->sValor,             '', 1);

          $nValorTotalNatureza = $nValorTotalNatureza + $oDebito->nValor;
        }

        $oPdf->Ln();
      }
    }

    if($oConfig->iStatus != 2) {

      $nValorTotalGrupo = $nValorTotalGrupo + $nValorTotalNatureza;

      /**
       * Imprime o valor total dos débitos dentro da mesma Natureza
       */
      $sValorTotal = "Valor Total Calculado: R$ " . number_format($nValorTotalNatureza, 2, ',', '.');

      $oPdf->SetFont('arial', 'b', 9);
      $oPdf->Cell($oConfig->iTamanhoMaximo, $oConfig->iAltura, $sValorTotal, 'TB', 1, 'R', 1);
    }

    $oPdf->Ln(12);
  }

  $oPdf->Ln(-8);

  if($oConfig->iStatus != 2) {

    /**
     * Imprime o valor total dos débitos do mesmo Grupo
     */
    $sValorTotalGrupo = "Grupo {$oGrupo->getDescricao()} - Valor Total Calculado: R$ " . number_format($nValorTotalGrupo, 2, ',', '.');

    $oPdf->SetFont('arial', 'b', 9);
    $oPdf->Cell($oConfig->iTamanhoMaximo, $oConfig->iAltura, $sValorTotalGrupo, 'TB', 1, 'R', 1);
  }
}

/**
 * Busca todos os grupos cadastrados
 *
 * @return GrupoTaxaDiversos[]
 * @throws DBException
 */
function buscaTodosGrupos() {

  $oDaoGrupos = new cl_grupotaxadiversos();
  $sSqlGrupos = $oDaoGrupos->sql_query_file(null, 'y118_sequencial');
  $rsGrupos   = db_query($sSqlGrupos);

  if(!$rsGrupos) {
    throw new DBException('Erro ao buscar os grupos de taxas.');
  }

  return \db_utils::makeCollectionFromRecord($rsGrupos, function($oRetorno) {
    return GrupoTaxaDiversosRepository::getInstanciaPorCodigo($oRetorno->y118_sequencial);
  });
}

/**
 * Organiza os lançamentos, agrupando taxas por CGM
 *
 * @param LancamentoTaxaDiversos[] $aLancamentos
 * @param stdClass $oConfig
 * @return array
 */
function organizaLancamentos($aLancamentos, $oConfig) {

  $aDadosLancamentos = array();

  foreach($aLancamentos as $oLancamento) {

    /**
     * Validação para imprimir somente quem possui lançamento calculado
     */
    if($oConfig->iStatus == 1 && $oLancamento->getDataUltimoCalculoGeral() == null) {
      continue;
    }

    /**
     * Validação para imprimir somente quem não possui lançamento calculado
     */
    if($oConfig->iStatus == 2 && $oLancamento->getDataUltimoCalculoGeral() != null) {
      continue;
    }

    $iCodigo = $oLancamento->getInscricaoMunicipal() != null ? $oLancamento->getInscricaoMunicipal() : $oLancamento->getCGM()->getCodigo();
    $lCGM    = $oLancamento->getInscricaoMunicipal() == null;

    if(!array_key_exists($iCodigo, $aDadosLancamentos)) {

      $aDadosLancamentos[$iCodigo]         = new stdClass();
      $aDadosLancamentos[$iCodigo]->sCgm   = $oLancamento->getCGM()->getNome();
      $aDadosLancamentos[$iCodigo]->lCgm   = $lCGM;
      $aDadosLancamentos[$iCodigo]->aTaxas = array();
    }

    $sUnidade = LancamentoTaxaDiversos::getDescricaoUnidade($oLancamento->getNaturezaTaxa()->getUnidade());
    $aPeriodo = array('D' => 'Dias', 'M' => 'Meses', 'A' => 'Meses');

    if($oLancamento->getPeriodo() == 1) {
      $aPeriodo = array('D' => 'Dia', 'M' => 'Mês', 'A' => 'Mês');
    }

    $sPeriodo    = $aPeriodo[$oLancamento->getNaturezaTaxa()->getTipoPeriodo()];
    $sDataInicio = $oLancamento->getDataInicio()  != null ? $oLancamento->getDataInicio()->getDate(DBDate::DATA_PTBR)  : '';
    $sDataFim    = $oLancamento->getDataFim()     != null ? $oLancamento->getDataFim()->getDate(DBDate::DATA_PTBR)     : '';

    $oTaxas                   = new stdClass();
    $oTaxas->sUnidade         = "Unidade: {$oLancamento->getUnidade()} / {$sUnidade}";
    $oTaxas->sPeriodo         = "Período: {$oLancamento->getPeriodo()} {$sPeriodo}";
    $oTaxas->sDataInicio      = "Data de Início: {$sDataInicio}";
    $oTaxas->sDataFim         = "Data de Fim: {$sDataFim}";
    $oTaxas->aDebitosLancados = array();

    /**
     * Organiza os débitos lançados para uma mesma taxa
     */
    foreach($oLancamento->getDebitosLancados() as $oDebito) {

      $oDadosDebitos               = new stdClass();
      $oDadosDebitos->sDataCalculo = "Data do Cálculo: ";
      $oDadosDebitos->sValor       = "Valor: Não Calculado";
      $oDadosDebitos->nValor       = 0;

      if(!empty($oDebito->sDataCalculo)) {

        $oDataCalculo = new DBDate($oDebito->sDataCalculo);

        $oDadosDebitos->sDataCalculo  .= $oDataCalculo->getDate(DBDate::DATA_PTBR);
        $oDadosDebitos->sValor         = "Valor: R$ " . number_format($oDebito->sValor, 2, ',', '.');
        $oDadosDebitos->nValor         = $oDebito->sValor;
      }

      $oTaxas->aDebitosLancados[] = $oDadosDebitos;
    }

    $aDadosLancamentos[$iCodigo]->aTaxas[] = $oTaxas;
  }

  return $aDadosLancamentos;
}

/**
 * Verifica se existentes lançamentos e consequentemente naturezas a serem impressas para um grupo
 *
 * @param $oConfig
 * @param GrupoTaxaDiversos $oGrupo
 * @return bool
 */
function validaImpressaoGrupo($oConfig, GrupoTaxaDiversos $oGrupo) {

  $lTemNatureza = false;

  foreach($oGrupo->getNaturezas() as $oNatureza) {

    /**
     * Validação para verificar se apenas uma determinada Natureza foi selecionada
     */
    if ($oConfig->iNatureza != 'T' && $oConfig->iNatureza != $oNatureza->getCodigo()) {
      continue;
    }

    /**
     * Atualiza a coleção dos lançamentos, para em seguida percorrê-los e imprimir os dados agrupados
     */
    $aLancamentos = organizaLancamentos($oNatureza->getLancamentos(), $oConfig);

    if (count($aLancamentos) == 0) {
      continue;
    }

    $lTemNatureza = true;
  }

  return $lTemNatureza;
}