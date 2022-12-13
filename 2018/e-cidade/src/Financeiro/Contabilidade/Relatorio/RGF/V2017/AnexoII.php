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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\ProcessamentoRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Coluna;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;

/**
 * Class AnexoII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017
 */
class AnexoII extends ProcessamentoRelatorioLegal {

  /**
   * Código Padrão do Relatório
   * @var integer
   */
  const CODIGO_RELATORIO = 167;

  /**
   * @todo verificar se todos relatórios do RGF utilizam RCL e mover para ProcessamentoRelatorioLegal
   * @var ReceitaCorrenteLiquida
   */
  private $oRCL;

  /**
   * Váriaveis de controle do pdf
   */
  private $iLarguraColunaSaldoExercicio    = 84;
  private $iLarguraColunaSaldoPeriodo      = 0;
  private $iLarguraColunaDescricao         = 78;
  private $iLarguraColunaExercicioAnterior = 28;

  private $aTipoBorda = array(
    1 => array("R", "LR", "L"),
    2 => array("TBR", "1", "TBL")
  );

  private $aColunaRecalcularPeriodo = array (
    12 => 1,
    13 => 2,
    14 => 1,
    15 => 2,
    16 => 3,
  );


  /**
   * Mapeia os períodos do RCL que devem ser calculado de acordo com o Período selecionado   *
   */
  private $aPeriodoCalcular = array(
    12 => array(12),
    13 => array(12, 13),
    14 => array(14),
    15 => array(14, 15),
    16 => array(14, 15, 16),
  );

  private $oDataInicio;


  /**
   * Número de colunas no saldo do exercício, Quadrimestre = 3 e Semestre = 2
   * @var integer
   */
  private $iNumeroDePeriodos = 3;

  public function __construct($iAno, $oPeriodo)  {

    $this->oDataInicio = new \DBDate("$iAno-01-01");

    $aInstituicoes = \InstituicaoRepository::getInstituicoes();

    $aInstituicoes = array_filter($aInstituicoes, function($oInstiuicao) {
      return ($oInstiuicao->getTipo() != 5 && $oInstiuicao->getTipo() != 6);
    });

    parent::__construct($iAno, $oPeriodo, self::CODIGO_RELATORIO, $aInstituicoes);

    $this->oRCL = new ReceitaCorrenteLiquida($iAno, null);

    if ( in_array($oPeriodo->getCodigo(), array(12, 13)) )  {
      $this->iNumeroDePeriodos = 2;
    }

    $this->iLarguraColunaSaldoPeriodo = $this->iLarguraColunaSaldoExercicio / $this->iNumeroDePeriodos;
  }

  /**
   * Processa as informações do relatorio
   */
  public function processar() {

    if ( empty($this->aLinhas) ) {
      $this->aLinhas = $this->getDados();
    }

    foreach ($this->aLinhasConsistencia as $iIndice => $oLinha) {

      $this->aLinhasConsistencia[$iIndice]->primeiro_periodo = 0;
      $this->aLinhasConsistencia[$iIndice]->segundo_periodo  = 0;
      $this->aLinhasConsistencia[$iIndice]->terceiro_periodo = 0;
    }

    $nRCLExercicioAnterior = array_sum($this->oRCL->calcularRCLAnterior());
    $this->aLinhas[26]->saldo_exercicio_anterior = $nRCLExercicioAnterior;

    foreach ($this->aPeriodoCalcular[$this->oPeriodo->getCodigo()] as $iPeriodo) {

      $iColuna    = $this->aColunaRecalcularPeriodo[$iPeriodo];
      $oDataFinal = \Periodo::dataFinalPeriodo($iPeriodo, $this->iAno);

      $this->processarBalanceteVerificacaoParaColunaPorData($iColuna, $this->oDataInicio, $oDataFinal);

      /**
       * Calcula a RCL para o período
       */

      $nValorRCL = $this->oRCL->somaRCLPeriodo($iPeriodo);

      switch ($iColuna) {
        case 1:
          $this->aLinhasConsistencia[26]->primeiro_periodo = $nValorRCL;
          break;
        case 2:
          $this->aLinhasConsistencia[26]->segundo_periodo = $nValorRCL;
          break;
        case 3:
          $this->aLinhasConsistencia[26]->terceiro_periodo = $nValorRCL;
          break;
      }
    }

    $aLinhaProcessarManual = array (11, 8, 4, 3, 1, 21, 20, 25, 27, 28, 29, 30);

    foreach ($aLinhaProcessarManual as $key => $aLinha) {
      $this->processarFormulaDaLinha($aLinha);
    }

    $this->aLinhas = $this->getDados();
  }

  /**
   * Imprime o cabeçaho do primeiro quadro
   * @paran \PDFDocument   $oPdf
   * @paran string  $sTitulo
   * @paran boolean $lParagrafoOficial
   */
  public function cabecalhoQuadro1( \PDFDocument $oPdf, $sTitulo = 'DÍVIDA CONSOLIDADA', $lParagrafoOficial = true) {

    $sPeriodo = $this->oPeriodo->getDescricao();
    if ( $lParagrafoOficial ) {

      $oPdf->SetFont("Arial", "", 6);
      $oPdf->Cell(180, 4, 'RGF - ANEXO 2 (LRF, art. 55, inciso I, alínea "b")');
      $oPdf->Cell(10,  4, 'R$ 1,00', 0, 1 );
    }

    $oPdf->SetFont("Arial", "b", 6);
    $oPdf->Cell($this->iLarguraColunaDescricao,          4, ""                                 , 'RT'  , 0, 'C', 1);
    $oPdf->Cell($this->iLarguraColunaExercicioAnterior,  4, "SALDO DO"                         , 'TLR' , 0, 'C', 1);
    $oPdf->Cell($this->iLarguraColunaSaldoExercicio,     4, "SALDO DO EXERCÍCIO {$this->iAno}" , 'TBL' , 1, 'C', 1);

    $oPdf->Cell($this->iLarguraColunaDescricao,         4, $sTitulo            , 'RB'  , 0, 'C', 1);
    $oPdf->Cell($this->iLarguraColunaExercicioAnterior, 4, "EXERCÍCIO ANTERIOR", 'LRB', 0, 'C', 1);

    $sPeriodo = $this->iNumeroDePeriodos == 2 ? "Semestre" : "Quadrimestre";
    for( $i = 1; $i <= $this->iNumeroDePeriodos; $i++ ) {

      $sBorda = "1";
      $iLn    = 0;
      if($i == $this->iNumeroDePeriodos) {

        $sBorda = "LTB";
        $iLn    = 1;
      }
      $oPdf->Cell($this->iLarguraColunaSaldoPeriodo,  4, "Até o {$i}º {$sPeriodo}" , $sBorda , $iLn, 'C', 1);
    }

    $oPdf->SetFont("Arial", "", 6);
  }

  public function cabecalhoQuadro2 ( \PDFDocument $oPdf) {

    $sTitulo = 'OUTROS VALORES NÃO INTEGRANTES DA DC';
    $oPdf->ln();
    $this->cabecalhoQuadro1($oPdf, $sTitulo, false);
  }

  /**
   * Finaliza terceito quadro e Imprime a nota explicativa
   * @paran \PDFDocument $oPdf
   */
  public function notaExplicativaPdf( \PDFDocument $oPdf ) {

    $oPdf->line($oPdf->getX(), $oPdf->getY(), 200, $oPdf->getY());
    $oPdf->ln(1);
    $this->notaExplicativa( $oPdf, array($oPdf, 'addPage'), 20 );

    $oPdf->ln($oPdf->getAvailHeight() - 10);
    $oDaoAssinatura = new \cl_assinatura();
    assinaturas($oPdf, $oDaoAssinatura, 'GF');
  }

  /**
   * Retorna um array com as linhas processadas para impressão
   * @return Linha[]
   */
  public function getDadosProcessados() {

    $this->processar();

    $oLinha = new Linha();
    $oLinha->informaMetodo("cabecalhoQuadro1");
    $this->aLinhasProcessadas[] = $oLinha;

    foreach($this->aLinhas as $oLinhaRelatorio ) {

      if ($oLinhaRelatorio->ordem <= 30 ) {
        $this->adicionalinhasQuadro1($oLinhaRelatorio);
      }

      if ($oLinhaRelatorio->ordem == 31) {

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadro2");
        $this->aLinhasProcessadas[] = $oLinha;
      }
      if ( $oLinhaRelatorio->ordem >= 31 ) {
        $this->adicionalinhasQuadro1($oLinhaRelatorio);
      }
    }
    $oLinha = new Linha();
    $oLinha->informaMetodo("notaExplicativaPdf");
    $this->aLinhasProcessadas[] = $oLinha;
    return $this->aLinhasProcessadas;
  }

 /**
  * Identifica a linha e redireciona para função de calculo. Linhas tratadas
  *   25 - DÍVIDA CONSOLIDADA LÍQUIDA² (DCL) (III) = (I - II)
  *   26 - RECEITA CORRENTE LÍQUIDA - RCL
  *   27 - % da DC sobre a RCL (I/RCL)
  *   28 - % da DCL sobre a RCL (III/RCL)
  *   29 - LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL - <%>
  *   30 - LIMITE DE ALERTA (inciso III do § 1º do art. 59 da LRF) - <%>
  *
  * @param stdClass $oLinhaRelatorio
  */
  private function calculaLinhasQuadro1($oLinhaRelatorio){

    $sNivel                  = str_repeat(' ', $oLinhaRelatorio->nivel * 2);
    $sDescricao              = "{$sNivel} {$oLinhaRelatorio->descricao}";
    $nSaldoExercicioAnterior = $oLinhaRelatorio->saldo_exercicio_anterior;
    $aSaldoPeriodo           = $this->getSaldoDoExercicio($oLinhaRelatorio);

    switch ( $oLinhaRelatorio->ordem ) {

      case 25 :
      case 28 :
        $this->adicionaLinha($sDescricao, $nSaldoExercicioAnterior, $aSaldoPeriodo, 1, 2);
        break;
      case 26 :
      case 27 :
      case 29 :
      case 30 :
        $this->adicionaLinha($sDescricao, $nSaldoExercicioAnterior, $aSaldoPeriodo, 0, 2);
        break;
    }
  }

  /**
   * Retorna um array com os valores do saldo do exercício de acordo com o pereíodo informado
   * @param  stdClass $oLinha
   * @return array
   */
  private function getSaldoDoExercicio($oLinha) {

    $aSaldoPeriodo = array();

    if ( in_array($this->oPeriodo->getCodigo(), array(12, 13) ) ) {

      $aSaldoPeriodo[] = $oLinha->primeiro_periodo;
      $aSaldoPeriodo[] = $oLinha->segundo_periodo;
    } else {
      $aSaldoPeriodo[] = $oLinha->primeiro_periodo;
      $aSaldoPeriodo[] = $oLinha->segundo_periodo;
      $aSaldoPeriodo[] = $oLinha->terceiro_periodo;
    }

    return $aSaldoPeriodo;
  }

  /**
   * Formata e adiciona uma linha processada
   * @param  stdClass $oLinhaRelatorio
   */
  private function adicionalinhasQuadro1($oLinhaRelatorio) {

    if ( $oLinhaRelatorio->ordem == 21 ) {

      if($oLinhaRelatorio->saldo_exercicio_anterior < 0) {
        $this->aLinhas[34]->saldo_exercicio_anterior += abs($oLinhaRelatorio->saldo_exercicio_anterior);
        $oLinhaRelatorio->saldo_exercicio_anterior   = 0;
      }

      if($oLinhaRelatorio->primeiro_periodo < 0) {
        $this->aLinhas[34]->primeiro_periodo += abs($oLinhaRelatorio->primeiro_periodo);
        $oLinhaRelatorio->primeiro_periodo   = 0;
      }

      if($oLinhaRelatorio->segundo_periodo < 0) {
        $this->aLinhas[34]->segundo_periodo += abs($oLinhaRelatorio->segundo_periodo);
        $oLinhaRelatorio->segundo_periodo   = 0;
      }

      if($oLinhaRelatorio->terceiro_periodo < 0) {
        $this->aLinhas[34]->terceiro_periodo += abs($oLinhaRelatorio->terceiro_periodo);
        $oLinhaRelatorio->terceiro_periodo   = 0;
      }
    }

    if ( $oLinhaRelatorio->ordem >= 25 && $oLinhaRelatorio->ordem <= 30 ) {

      $this->calculaLinhasQuadro1($oLinhaRelatorio);
      return true;
    }
    $sNivel                  = str_repeat(' ', $oLinhaRelatorio->nivel * 2);

    $sDescricao              = "{$sNivel} {$oLinhaRelatorio->descricao}";
    $nSaldoExercicioAnterior = $oLinhaRelatorio->saldo_exercicio_anterior;
    $aSaldoPeriodo           = $this->getSaldoDoExercicio($oLinhaRelatorio);

    $this->adicionaLinha($sDescricao, $nSaldoExercicioAnterior, $aSaldoPeriodo, 0, 1);
    return true;
  }


  /**
   * [adicionaLinha description]
   * @param  [type]  $sDescricao              [description]
   * @param  float   $nSaldoExercicioAnterior [description]
   * @param  array   $aSaldoPeriodo           [description]
   * @param  integer $iFill                   [description]
   * @param  integer $iTipoBorda              [description]
   */
  private function adicionaLinha($sDescricao, $nSaldoExercicioAnterior = null, $aSaldoPeriodo, $iFill = 0, $iTipoBorda = 1) {

    $nSaldoExercicioAnterior = db_formatar($nSaldoExercicioAnterior, 'f');

    $oLinha = new Linha();
    $oLinha->addColuna($this->iLarguraColunaDescricao, "{$sDescricao}", $this->aTipoBorda[$iTipoBorda][0], 0, 'L', $iFill);
    $oLinha->addColuna($this->iLarguraColunaExercicioAnterior, "{$nSaldoExercicioAnterior}", $this->aTipoBorda[$iTipoBorda][1], 0, 'R', $iFill);

    foreach ( $aSaldoPeriodo as $i => $nValor ) {

      $sBorda = $this->aTipoBorda[$iTipoBorda][1];
      $iLn    = 0;
      if($i+1 == $this->iNumeroDePeriodos) {

        $sBorda = $this->aTipoBorda[$iTipoBorda][2];
        $iLn    = 1;
      }

      $nValor = db_formatar($nValor, 'f');
      $oLinha->addColuna($this->iLarguraColunaSaldoPeriodo, "$nValor", $sBorda, $iLn, 'R',  $iFill);
    }

    $this->aLinhasProcessadas[] = $oLinha;
  }

  /**
  * Retorna os dados do AnexoII para emissão no relatório Simplificado (Anexo II)
  * @return \stdClass
  */
  public function getDadosSimplificado() {

    /*
     * Carrega as informações que usaremos abaixo
     */
    $this->processar();

    $oStdDivida = new \stdClass();
    $oStdDivida->nTotalDividaII       = 0;
    $oStdDivida->nPercentualRCL       = 0;
    $oStdDivida->nLimiteSenadoAnexoII = 0;

    switch ($this->oPeriodo->getCodigo()) {
      case \Periodo::PRIMEIRO_SEMESTRE:
      case \Periodo::PRIMEIRO_QUADRIMESTRE:
        $oStdDivida->nTotalDividaII       = $this->aLinhasConsistencia[25]->primeiro_periodo;
        $oStdDivida->nPercentualRCL       = $this->aLinhasConsistencia[28]->primeiro_periodo;
        $oStdDivida->nLimiteSenadoAnexoII = $this->aLinhasConsistencia[29]->primeiro_periodo;
      break;
      case \Periodo::SEGUNDO_SEMESTRE:
      case \Periodo::SEGUNDO_QUADRIMESTRE:
        $oStdDivida->nTotalDividaII       = $this->aLinhasConsistencia[25]->segundo_periodo;
        $oStdDivida->nPercentualRCL       = $this->aLinhasConsistencia[28]->segundo_periodo;
        $oStdDivida->nLimiteSenadoAnexoII = $this->aLinhasConsistencia[29]->segundo_periodo;
      break;
      case \Periodo::TERCEIRO_QUADRIMESTRE:
        $oStdDivida->nTotalDividaII       = $this->aLinhasConsistencia[25]->terceiro_periodo;
        $oStdDivida->nPercentualRCL       = $this->aLinhasConsistencia[28]->terceiro_periodo;
        $oStdDivida->nLimiteSenadoAnexoII = $this->aLinhasConsistencia[29]->terceiro_periodo;
      break;
    }

    return $oStdDivida;
  }

}
