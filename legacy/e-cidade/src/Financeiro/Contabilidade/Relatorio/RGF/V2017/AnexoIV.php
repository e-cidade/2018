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
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;

/**
 * Class AnexoIV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017
 */
class AnexoIV extends ProcessamentoRelatorioLegal {

  /**
   * Código Padrão do Relatório
   * @var integer
   */
  const CODIGO_RELATORIO = 168;

  /**
   * @todo verificar se todos relatórios do RGF utilizam RCL e mover para ProcessamentoRelatorioLegal
   * @var ReceitaCorrenteLiquida
   */
  private $oRCL;

  /**
   * AnexoIV constructor.
   * @param int $iAno
   * @param \Periodo $oPeriodo
   */
  public function __construct($iAno, \Periodo $oPeriodo)  {

    $aInstituicoes = \InstituicaoRepository::getInstituicoes();
    parent::__construct($iAno, $oPeriodo, self::CODIGO_RELATORIO, $aInstituicoes);

    $this->oRCL = new ReceitaCorrenteLiquida($iAno, $aInstituicoes);
  }

  /**
   * Processa as informações do relatorio
   */
  public function processar() {

    if ( empty($this->aLinhas) ) {

      $aPeriodosReferencia = array(
        \Periodo::TERCEIRO_QUADRIMESTRE => \Periodo::SEGUNDO_QUADRIMESTRE,
        \Periodo::SEGUNDO_QUADRIMESTRE => \Periodo::PRIMEIRO_QUADRIMESTRE,
        \Periodo::SEGUNDO_SEMESTRE => \Periodo::PRIMEIRO_SEMESTRE,
      );

      $this->aLinhasConsistencia = $this->getDados();

      /*
       * Verificamos se precisa executar o balancete de verificação para o outro perido
       */
      if (!empty($aPeriodosReferencia[$this->oPeriodo->getCodigo()])) {

        $oPeriodoReferencia = new \Periodo($aPeriodosReferencia[$this->oPeriodo->getCodigo()]);
        $this->processarBalanceteVerificacaoParaColunaPorData(
          0,
          $this->oDataInicial,
          $oPeriodoReferencia->getDataFinal($this->iAno)
        );
      }

      /*
       * Executamos novamente as fórmulas das linhas totalizadoras para atualizar os valores..
       */
      $this->aLinhasConsistencia = $this->getDados();
      $aLinhasProcessarFormula = array(1,4,5,11,17,20,25);
      foreach ($aLinhasProcessarFormula as $iLinha) {
        $this->processarFormulaDaLinha($iLinha);
      }
    }

    $this->processaLinhasQuadro2();
  }

  /**
   * Calcula o valor das linhas:
   *   18 - RECEITA CORRENTE LÍQUIDA - RCL
   *   19 - OPERAÇÕES VEDADAS (II)
   *   20 - TOTAL CONSIDERADO PARA FINS DA APURAÇÃO DO CUMPRIMENTO DO LIMITE (III)= (Ia + II)
   *   21 - LIMITE GERAL DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO INTERNAS E EXTERNAS
   *   24 - LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL PARA AS OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA
   */
  private function processaLinhasQuadro2() {

    $nRCL = $this->oRCL->somaRCLPeriodo($this->oPeriodo->getCodigo());
    $nRCL                                      += $this->aLinhasConsistencia[18]->valor;
    $this->aLinhasConsistencia[18]->valor      = $nRCL;
    $this->aLinhasConsistencia[18]->percentual = '-';

    $this->aLinhasConsistencia[19]->percentual = round((( $this->aLinhasConsistencia[19]->valor / $nRCL ) * 100), 2);
    $this->aLinhasConsistencia[20]->percentual = round((( $this->aLinhasConsistencia[20]->valor / $nRCL ) * 100), 2);

    $this->aLinhasConsistencia[21]->valor      = round(($this->aLinhasConsistencia[18]->valor * 0.16), 2);
    $this->aLinhasConsistencia[21]->percentual = 16;

    $this->aLinhasConsistencia[22]->valor      = round(($this->aLinhasConsistencia[18]->valor * 14.4) / 100, 2);
    $this->aLinhasConsistencia[22]->percentual = 14.4;

    $this->aLinhasConsistencia[23]->valor      += $this->aLinhasConsistencia[8]->ateperiodo + $this->aLinhasConsistencia[14]->ateperiodo;
    $this->aLinhasConsistencia[23]->percentual = round( (($this->aLinhasConsistencia[23]->valor) / $nRCL) * 100, 2);

    $this->aLinhasConsistencia[24]->valor      = round($this->aLinhasConsistencia[18]->valor * 0.07, 2);
    $this->aLinhasConsistencia[24]->percentual = 7;
  }

  /**
   * Imprime o cabeçaho do primeiro quadro
   * @paran \PDFDocument $oPdf
   * @paran string       $sTitulo
   * @paran boolean      $lParagrafoOficial
   */
  public function cabecalhoQuadro1( \PDFDocument $oPdf, $sTitulo = 'OPERAÇÕES DE CRÉDITO', $lParagrafoOficial = true) {

    $sPeriodo = substr(ucwords(strtolower($this->oPeriodo->getDescricao())),3);

    $oPdf->setFont('Arial', null, 7);
    if ( $lParagrafoOficial ) {

      $oPdf->Cell(180, 4, 'RGF - ANEXO 4 (LRF, art. 55, inciso I, alínea "d" e inciso III alínea "c")');
      $oPdf->Cell(10,  4, 'R$ 1,00', 0, 1 );
    }

    $oPdf->setBold(true);

    $oPdf->Cell(102, 4, '',                'TR' , 0, 'C', 1);
    $oPdf->Cell(88,  4, 'VALOR REALIZADO', 'TBL', 1, 'C', 1);
    $oPdf->setUnderline(true);
    $oPdf->Cell(102, 4, $sTitulo         ,   'R', 0, 'C', 1);

    $oPdf->setBold(false);
    $oPdf->setUnderline(false);

    $oPdf->Cell(44,  4, "No {$sPeriodo}"   ,'TLR', 0, 'C', 1);
    $oPdf->Cell(44,  4, "Até o {$sPeriodo}", 'TL', 1, 'C', 1);
    $oPdf->Cell(102, 4, ''                 , 'RB', 0, 'C', 1);
    $oPdf->Cell(44,  4, 'de Referência'    ,'BLR', 0, 'C', 1);
    $oPdf->Cell(44,  4, 'de Referência (a)', 'BL', 1, 'C', 1);

    $oPdf->SetFont("Arial", "", 8);
  }

  /**
   * Imprime o cabeçaho do segundo quadro
   * @param \PDFDocument $oPdf
   */
  public function cabecalhoQuadro2( \PDFDocument $oPdf ) {

    $oPdf->setFont('Arial', null, 7);
    $oPdf->ln();
    $oPdf->setBold(true);
    $oPdf->setUnderline(true);
    $oPdf->Cell(130, 4, 'APURAÇÃO DO CUMPRIMENTO DOS LIMITES', 'TBR', 0, 'C', 1);
    $oPdf->setUnderline(false);
    $oPdf->setBold(false);
    $oPdf->Cell(30,  4, 'VALOR',                                   1, 0, 'C', 1);
    $oPdf->Cell(30,  4, '% SOBRE A RCL',                       'TBL', 1, 'C', 1);
  }

  /**
   * Imprime o cabeçaho do terceiro quadro
   * @param \PDFDocument $oPdf
   */
  public function cabecalhoQuadro3( \PDFDocument $oPdf ) {

    $sTitulo = 'OUTRAS OPERAÇÕES QUE INTEGRAM A DÍVIDA CONSOLIDADA';
    $oPdf->ln();
    $this->cabecalhoQuadro1($oPdf, $sTitulo, false);
  }

  /**
   * Finaliza terceito quadro e Imprime a nota explicativa
   * @param \PDFDocument $oPdf
   */
  public function notaExplicativaPdf( \PDFDocument $oPdf ) {

    $oPdf->line($oPdf->getX(), $oPdf->getY(), 200, $oPdf->getY());
    $oPdf->ln(2);
    $this->notaExplicativa( $oPdf, array($oPdf, 'addPage'), 20 );

    $oPdf->ln($oPdf->getAvailHeight() - 10);
    $oDaoAssinatura = new \cl_assinatura();
    assinaturas($oPdf, $oDaoAssinatura, 'GF');
  }

  /**
   * Retorna um array com os dados processados para impressão
   * @return Linha[]
   */
  public function getDadosProcessados() {

    $this->processar();

    $oLinha = new Linha();
    $oLinha->informaMetodo("cabecalhoQuadro1");
    $this->aLinhasProcessadas[] = $oLinha;

    foreach($this->aLinhasConsistencia as $oLinhaRelatorio ) {

      if ($oLinhaRelatorio->ordem < 18 ) {
        $this->adicionaLinhaQuadro1($oLinhaRelatorio);
      }

      if ($oLinhaRelatorio->ordem == 18) {

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadro2");
        $this->aLinhasProcessadas[] = $oLinha;
      }

      if ( $oLinhaRelatorio->ordem >= 18 && $oLinhaRelatorio->ordem < 25 ) {
        $this->adicionaLinhaQuadro2($oLinhaRelatorio);
      }
      if ($oLinhaRelatorio->ordem == 25) {

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadro3");
        $this->aLinhasProcessadas[] = $oLinha;
      }

      if ( $oLinhaRelatorio->ordem >= 25 ) {
        $this->adicionaLinhaQuadro3($oLinhaRelatorio);
      }
    }

    $oLinha = new Linha();
    $oLinha->informaMetodo("notaExplicativaPdf");
    $this->aLinhasProcessadas[] = $oLinha;

    return $this->aLinhasProcessadas;
  }

  /**
   * Adiciona as linhas do primeiro quadro
   * @param \stdClass $oLinhaRelatorio
   */
  private function adicionaLinhaQuadro1($oLinhaRelatorio) {

    $sNivel           = str_repeat(' ', $oLinhaRelatorio->nivel * 2);
    $nValorPeriodo    = db_formatar($oLinhaRelatorio->noperiodo, 'f');
    $nValorAtePeriodo = db_formatar($oLinhaRelatorio->ateperiodo, 'f');

    $oLinha = new Linha();
    // linha 17 é um totalizador
    if ($oLinhaRelatorio->ordem == 17) {

      $oLinha->bold(true);
      $oLinha->addColuna(102, "{$sNivel} {$oLinhaRelatorio->descricao}", 'TBR', 0, 'L', 1);
      $oLinha->addColuna(44,  $nValorPeriodo,                                1, 0, 'R', 1);
      $oLinha->addColuna(44,  $nValorAtePeriodo,                         'TBL', 1, 'R', 1);
    } else {

      $oLinha->addColuna(102, "{$sNivel} {$oLinhaRelatorio->descricao}", 'R', 0, 'L');
      $oLinha->addColuna(44,  $nValorPeriodo,                           'LR', 0, 'R');
      $oLinha->addColuna(44,  $nValorAtePeriodo,                         'L', 1, 'R');
    }
    $this->aLinhasProcessadas[] = $oLinha;
  }

  /**
   * Adiciona as linhas do segundo quadro
   * @param \stdClass $oLinhaRelatorio
   */
  private function adicionaLinhaQuadro2($oLinhaRelatorio) {

    $nValorPercentual = $oLinhaRelatorio->percentual;
    $nValorPercentual = $nValorPercentual !== '-' ? db_formatar($nValorPercentual, 'f') : '-';
    $nValor           = db_formatar($oLinhaRelatorio->valor, 'f');

    $oLinha = new Linha();
    $oLinha->addColuna(130, $oLinhaRelatorio->descricao,  'TBR', 0, 'L')->multicell(true);
    $oLinha->addColuna(30,  $nValor,                          1, 0, 'R');
    $oLinha->addColuna(30,  $nValorPercentual,            'TBL', 1, 'R');
    $this->aLinhasProcessadas[] = $oLinha;
  }

  /**
   * Adiciona as linhas do terceiro quadro
   * @param \stdClass $oLinhaRelatorio
   */
  private function adicionaLinhaQuadro3($oLinhaRelatorio) {
    $this->adicionaLinhaQuadro1($oLinhaRelatorio);
  }

  /**
   * Retorna os dados do AnexoIV para emissão no relatório Simplificado (Anexo VI)
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    /*
     * Carrega as informações que usaremos abaixo
     */
    $this->processar();

    $oStdOperacaoCredito = new \stdClass();
    $nValorReceitaCorrenteLiquida     = $this->aLinhasConsistencia[18]->valor;
    $nPercentualTotalOperacoesCredito = (($this->aLinhasConsistencia[17]->ateperiodo / $nValorReceitaCorrenteLiquida) * 100);
    $oStdOperacaoCredito->total_operacoes_credito = $this->aLinhasConsistencia[17]->ateperiodo;
    $oStdOperacaoCredito->perc_operacoes_credito  = round($nPercentualTotalOperacoesCredito, 2);

    $oStdOperacaoCredito->total_antecipacao_receita_orcamentaria = $this->aLinhasConsistencia[23]->valor;
    $oStdOperacaoCredito->perc_antecipacao_receita_orcamentaria  = $this->aLinhasConsistencia[23]->percentual;

    $oStdOperacaoCredito->total_credito_interna_externa = $this->aLinhasConsistencia[21]->valor;
    $oStdOperacaoCredito->perc_credito_interna_externa  = $this->aLinhasConsistencia[21]->percentual;

    $oStdOperacaoCredito->total_credito_interna_receita_orcamentaria = $this->aLinhasConsistencia[24]->valor;
    $oStdOperacaoCredito->perc_credito_interna_receita_orcamentaria  = $this->aLinhasConsistencia[24]->percentual;

    return $oStdOperacaoCredito;
  }
}
