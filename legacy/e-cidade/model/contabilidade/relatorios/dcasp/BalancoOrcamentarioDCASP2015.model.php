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

final class BalancoOrcamentarioDCASP2015 extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO                      = 153;

  /**
   * C�digo dos quadros
   */
  const QUADRO_PRINCIPAL                      = 1;
  const QUADRO_RESTOS_NAO_PROCESSADOS         = 2;
  const QUADRO_RESTOS_PROCESSADOS             = 3;

  /**
   * Linhas de in�cio e fim de cada quadro
   */
  const QUADRO_PRINCIPAL_INICIAL              = 1;
  const QUADRO_PRINCIPAL_FINAL                = 51;

  const QUADRO_PRINCIPAL_RECEITAS_INICIO      = 1;
  const QUADRO_PRINCIPAL_RECEITAS_FIM         = 30;

  const QUADRO_PRINCIPAL_DESPESAS_INICIO      = 31;
  const QUADRO_PRINCIPAL_DESPESAS_FIM         = 51;

  const QUADRO_RESTOS_NAO_PROCESSADOS_INICIAL = 52;
  const QUADRO_RESTOS_NAO_PROCESSADOS_FINAL   = 60;

  const QUADRO_RESTOS_PROCESSADOS_INICIAL     = 61;
  const QUADRO_RESTOS_PROCESSADOS_FINAL       = 69;

  /**
   * Identifica quais linhas s�o totalizadoras.
   *
   * @var array
   */
  private $aLinhasTotalizadoras = array(94);

  /**
   * Linhas finais de cada quadro/se��o
   *
   * @var array
   */
  private $aLinhasFinais = array(30, 51, 60, 69);

  /**
   * Linhas que devem ficar em negrito
   *
   * @var array
   */
  private $aLinhasNegrito = array(
    1, 10, 16, 17, 18, 25, 27, 31, 35, 39, 40,
    41, 42, 49, 51, 52, 56, 60, 61, 65, 69
  );

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Nome da institui��o a ser exibida no relat�rio.
   *
   * @var string
   */
  private $sDescricaoInstituicao;

  /**
   * Nome do per�odo a ser exibido no relat�rio.
   *
   * @var string
   */
  private $sDescricaoPeriodo;

  /**
   * Linhas do Quadro Principal
   *
   * @var array
   */
  private $aQuadroPrincipal = array();

  /**
   * Linhas do Quadro Execu��o de Restos a Pagar N�o Processados
   *
   * @var array
   */
  private $aQuadroRestosNaoProcessados = array();

  /**
   * Linhas do Quadro Execu��o de Restos a Pagar Processados
   * e N�o Processados Liquidados
   *
   * @var array
   */
  private $aQuadroRestosProcessados = array();

  /**
   * Quadros que ser�o exibidos no relat�rio
   *
   * @var array
   */
  private $aRelatoriosExibir = array();

  /**
   * Largura da p�gina
   *
   * @var integer
   */
  private $iLargura;

  /**
   * Altura da p�gina
   *
   * @var integer
   */
  private $iAltura;

  /**
   * @param integer $iAnoUsu          Ano da emiss�o do relat�rio.
   * @param integer $iCodigoRelatorio C�digo do relat�rio cadastrado no sistema.
   * @param integer $iCodigoPeriodo   C�digo do per�odo de emiss�o do relat�rio.
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oPdf     = new PDFDocument();
    $this->iAltura  = 4;
    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
  }

  /**
   * Seta os quadros que devem ser exibidos de acordo com as constantes da classe.
   *
   * @param array $aQuadrosExibir Array de constantes identificando quais quadros do relat�rio devem ser exibidos.
   */
  public function setExibirQuadros($aQuadrosExibir) {
    $this->aRelatoriosExibir = $aQuadrosExibir;
  }

  /**
   * Adiciona uma nova p�gina, reinserindo o cabe�alho do relat�rio.
   */
  private function adicionarPagina($iQuadro, $lEscreverCabecalho = true) {

    $lNegrito = $this->oPdf->getBold();
    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("BALAN�O OR�AMENT�RIO");

    switch ($iQuadro) {

      case self::QUADRO_PRINCIPAL:

        $this->oPdf->addHeaderDescription("QUADRO PRINCIPAL");
        break;

      case self::QUADRO_RESTOS_NAO_PROCESSADOS:

        $this->oPdf->addHeaderDescription("QUADRO DA EXECU��O DE RESTOS A PAGAR N�O PROCESSADOS");
        break;

      case self::QUADRO_RESTOS_PROCESSADOS:

        $this->oPdf->addHeaderDescription("QUADRO DA EXECU��O DE RESTOS A PAGAR PROCESSADOS E N�O PROCESSADOS LIQUIDADOS");
        break;
    }

    $this->oPdf->addHeaderDescription("EXERC�CIO : {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PER�ODO : {$this->sDescricaoPeriodo}");
    $this->oPdf->AddPage();

    if ($lEscreverCabecalho) {
      $this->escreverCabecalhoQuadro($iQuadro);
    }
    $this->oPdf->setBold($lNegrito);
  }

  /**
   * Emite um quadro do relat�rio.
   */
  private function emitirQuadro($iQuadro, $aDados) {

    $this->adicionarPagina($iQuadro);

    foreach ($aDados as $oLinha) {

      $this->oLinha = $oLinha;
      if ($oLinha->ordem == self::QUADRO_PRINCIPAL_DESPESAS_INICIO) {
        $this->escreverCabecalhoQuadro($iQuadro);
      }
      if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina($iQuadro);
      }
      $this->escreverLinha($iQuadro, $oLinha);
    }

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
    $this->escreveAssinatura($iQuadro);
  }

  /**
   * Escreve as assinaturas do quadro do relat�rio.
   */
  private function escreveAssinatura($iQuadro) {

    if ($this->oPdf->getAvailHeight() < 45) {
      $this->adicionarPagina($iQuadro, false);
    }

    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);
  }

  /**
   * Popula os atributos que ser�o utilizados no cabe�alho.
   */
  private function prepararCabecalhos() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura                 = InstituicaoRepository::getInstituicaoPrefeitura();
      $this->sDescricaoInstituicao = "INSTITUI��O : {$oPrefeitura->getDescricao()} - {$oPrefeitura->getUf()} - CONSOLIDA��O";
    } else {

      $oInstituicao                = current($aListaInstituicoes);
      $this->sDescricaoInstituicao = "INSTITUI��O : {$oInstituicao->getDescricao()} - {$oInstituicao->getUf()}";
    }

    $this->sDescricaoPeriodo = $this->getDescricaoPeriodo();
  }

  /**
   * Informa se um quadro do relat�rio deve ser exibido, de acordo com seu c�digo.
   *
   * @param integer $iCodigo C�digo do quadro de acordo com as constantes desta classe.
   *
   * @return bool
   */
  private function exibirQuadroRelatorio($iCodigo) {
    return in_array($iCodigo, $this->aRelatoriosExibir);
  }

  /**
   * Popula os arrays de cada quadro caso deva exibi-los.
   */
  private function processarQuadros() {

    $this->aDados = $this->getDados();

    for ($iIndice = self::QUADRO_PRINCIPAL_INICIAL; $iIndice <= self::QUADRO_PRINCIPAL_FINAL; $iIndice++) {
      $this->aQuadroPrincipal[] = $this->processarLinha($iIndice, self::QUADRO_PRINCIPAL_FINAL);
    }

    for ($iIndice = self::QUADRO_RESTOS_NAO_PROCESSADOS_INICIAL; $iIndice <= self::QUADRO_RESTOS_NAO_PROCESSADOS_FINAL; $iIndice++) {
      $this->aQuadroRestosNaoProcessados[] = $this->processarLinha($iIndice, self::QUADRO_RESTOS_NAO_PROCESSADOS_FINAL);
    }

    for ($iIndice = self::QUADRO_RESTOS_PROCESSADOS_INICIAL; $iIndice <= self::QUADRO_RESTOS_PROCESSADOS_FINAL; $iIndice++) {
      $this->aQuadroRestosProcessados[] = $this->processarLinha($iIndice, self::QUADRO_RESTOS_PROCESSADOS_FINAL);
    }

  }

  /**
   * Realizar as configura��es iniciais do pdf.
   */
  private function configurarRelatorio() {

    $this->oPdf->SetLeftMargin(10);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(true);
    $this->oPdf->SetFillcolor(235);
    $this->oPdf->SetFont('arial', '', 6);
  }

  /**
   * Procura a descri��o do per�odo de acordo com o atributo iCodigoPeriodo
   *
   * @return string
   */
  private function getDescricaoPeriodo() {

    $sNomePeriodo = '';
    $aPeriodos    = $this->getPeriodos();
    foreach ($aPeriodos as $oPeriodo) {

      if ($oPeriodo->o114_sequencial == $this->iCodigoPeriodo) {

        $sNomePeriodo = $oPeriodo->o114_descricao;
        break;
      }
    }

    return $sNomePeriodo;
  }

  /**
   * Escreve o cabe�alho do quadro Principal
   */
  private function escreverCabecalhoPrincipal() {

    $sBorda = 'TB';

    if ($this->oLinha->ordem <= self::QUADRO_PRINCIPAL_RECEITAS_FIM) {

      /**
       * Receitas Or�ament�rias
       */
      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 2, "RECEITAS OR�AMENT�RIAS", $sBorda, 'L');
      $this->oPdf->MultiCell($this->iLargura * 0.125, $this->iAltura, "Previs�o Inicial\n(a)", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.125, $this->iAltura, "Previs�o Atualizada\n(b) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.125, $this->iAltura, "Receitas Realizadas\n(c) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.125, $this->iAltura, "Saldo\n(d) = (c - b) ", 'L' . $sBorda, 'C');
      $this->oPdf->Ln($this->iAltura * 2);
      $this->oPdf->setAutoNewLineMulticell(true);
    } else if ($this->valorNoIntervalo($this->oLinha->ordem, self::QUADRO_PRINCIPAL_DESPESAS_INICIO, self::QUADRO_PRINCIPAL_DESPESAS_FIM)) {

      if ($this->oLinha->ordem == self::QUADRO_PRINCIPAL_DESPESAS_INICIO) {
        $this->oPdf->Ln($this->iAltura);
      }

      /**
       * Despesas Or�ament�rias
       */
      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->MultiCell($this->iLargura * 0.40, $this->iAltura * 3, "DESPESAS OR�AMENT�RIAS", $sBorda, 'L');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura + 2, "Dota��o Inicial\n(e)", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "Dota��o Atualizada\n(f) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "Despesas Empenhadas\n(g) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "Despesas Liquidadas\n(h) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura + 2, "Despesas Pagas\n(i) ", 'L' . $sBorda, 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "Saldo da Dota��o\n(j) = (f - g)", 'L' . $sBorda, 'C');
      $this->oPdf->Ln($this->iAltura * 3);
      $this->oPdf->setAutoNewLineMulticell(true);
    }
  }

  /**
   * Escreve o cabe�alho do quadro Execu��o de Restos a Pagar N�o Processados
   */
  private function escreverCabecalhoRestosNaoProcessados() {

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, '', 'T', 0);
    $this->oPdf->Cell($this->iLargura * 0.266, $this->iAltura, "Inscritos", 'TL', 0, "C");
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, '', 'TL', 0);
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, '', 'TL', 0);
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, '', 'TL', 0);
    $this->oPdf->Cell($this->iLargura * 0.135, $this->iAltura, '', 'TL', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.20, $this->iAltura * 3, '', 'B', 'L');
    $this->oPdf->MultiCell($this->iLargura * 0.133, $this->iAltura, "Em Exerc�cios Anteriores\n(a)", 'TLB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.133, $this->iAltura, "Em 31 de Dezembro do Exerc�cio Anterior (b)", 'TLB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.133, $this->iAltura + 2, "Liquidados\n(c)", 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.133, $this->iAltura + 2, "Pagos\n(d)", 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.133, $this->iAltura + 2, "Cancelados\n(e)", 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.135, $this->iAltura + 2, "Saldo\n(f) = (a + b - d - e)", 'LB', 'C');
    $this->oPdf->Ln($this->iAltura * 3);
    $this->oPdf->setAutoNewLineMulticell(true);
  }

  /**
   * Escreve o cabe�alho do quadro Execu��o de Restos a Pagar Processados e N�o Processados Liquidados
   */
  private function escreverCabecalhoRestosProcessados() {

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, '', 'T', 0);
    $this->oPdf->Cell($this->iLargura * 0.28, $this->iAltura, "Inscritos", 'TL', 0, "C");
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, '', 'TL', 0);
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, '', 'TL', 0);
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, '', 'TL', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, '', 'B', 'L');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura, "Em Exerc�cios Anteriores\n(a)", 'TLB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura, "Em 31 de Dezembro do Exerc�cio Anterior\n(b)", 'TLB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura + 2, "Pagos\n(c)", 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura + 2, "Cancelados\n(d)", 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura + 2, "Saldo\n(e) = (a + b - c - d)", 'LB', 'C');
    $this->oPdf->Ln($this->iAltura * 3);
    $this->oPdf->setAutoNewLineMulticell(true);
  }

  /**
   * Escreve o cabe�alho do quadro.
   *
   */
  private function escreverCabecalhoQuadro($iQuadro) {

    $this->oPdf->setBold(true);

    switch ($iQuadro) {

      case self::QUADRO_PRINCIPAL:

        $this->escreverCabecalhoPrincipal();
        break;

      case self::QUADRO_RESTOS_NAO_PROCESSADOS:

        $this->escreverCabecalhoRestosNaoProcessados();
        break;

      case self::QUADRO_RESTOS_PROCESSADOS:

        $this->escreverCabecalhoRestosProcessados();
        break;

      default:
        throw new Exception("Quadro inv�lido.");
        break;
    }

    $this->oPdf->setBold(false);
  }

  /**
   * Prepara a linha para ser utilizada no relat�rio.
   *
   * @param integer $iLinha            N�mero da linha.
   * @param integer $iLinhaFinalQuadro N�mero da linha final do quadro.
   *
   * @return stdClass
   */
  private function processarLinha($iLinha, $iLinhaFinalQuadro) {

    $oLinha                    = $this->aDados[$iLinha];
    $oLinha->ultimaLinhaQuadro = in_array($iLinha, $this->aLinhasFinais);
    $oLinha->totalizadorFinal  = in_array($iLinha, $this->aLinhasTotalizadoras);

    return $oLinha;
  }

  private function valorNoIntervalo($iValor, $iInicio, $iFim) {
    return ($iValor >= $iInicio && $iValor <= $iFim);
  }

  /**
   * Escreve uma linha do quadro principal
   *
   * @param  stdClass $oLinha Linha a ser escrita.
   * @param  string   $sBorda
   */
  private function escreverLinhaQuadroPrincipal(stdClass $oLinha, $sBorda) {

    /**
     * Quebra a p�gina antes de imprimir as 3 �ltimas linhas do relat�rio,
     * para n�o deixar a assinatura em uma p�gina em branco.
     */
    if ($oLinha->ordem == (self::QUADRO_PRINCIPAL_FINAL - 2)) {
      $this->adicionarPagina(self::QUADRO_PRINCIPAL);
    }

    if ($oLinha->ordem <= self::QUADRO_PRINCIPAL_RECEITAS_FIM) {

      $nPrevisaoInicial    = db_formatar(round($oLinha->previni, 2), 'f');
      $nPrevisaoAtualizada = db_formatar(round($oLinha->prevatu, 2), 'f');
      $nReceitasRealizadas = db_formatar(round($oLinha->recrealiza, 2), 'f');
      $nSaldo              = db_formatar(round($oLinha->saldo, 2), 'f');

      if ($oLinha->ordem == 26) {

        $nPrevisaoInicial    = '-';
        $nPrevisaoAtualizada = '-';
        $nSaldo              = '-';

        /**
         * Se o saldo n�o � deficit�rio (despesa > receita) n�o demonstra o valor na linha
         */
        if ($oLinha->recrealiza == 0) {
          $nReceitasRealizadas = '-';
        }
      }

      /**
       * Demonstra somente Previs�o Atualizada e Receitas Realizadas
       */
      if (in_array($oLinha->ordem, array(29, 30))) {

        $nPrevisaoInicial    = '-';
        $nSaldo              = '-';
      }

      $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, $oLinha->descricao, $sBorda, 0, 'L');
      $this->oPdf->Cell($this->iLargura * 0.125, $this->iAltura, $nPrevisaoInicial, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.125, $this->iAltura, $nPrevisaoAtualizada, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.125, $this->iAltura, $nReceitasRealizadas, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.125, $this->iAltura, $nSaldo, 'L' . $sBorda, 1, 'R');
    } else if ($this->valorNoIntervalo($this->oLinha->ordem, self::QUADRO_PRINCIPAL_DESPESAS_INICIO, self::QUADRO_PRINCIPAL_DESPESAS_FIM)) {

      $nDotacaoInicial     = db_formatar(round($oLinha->dotini, 2), 'f');
      $nDotacaoAtualizada  = db_formatar(round($oLinha->dotatu, 2), 'f');
      $nDespesasEmpenhadas = db_formatar(round($oLinha->despemp, 2), 'f');
      $nDespesasLiquidadas = db_formatar(round($oLinha->despliq, 2), 'f');
      $nDespesasPagas      = db_formatar(round($oLinha->desppag, 2), 'f');
      $nSaldo              = db_formatar(round($oLinha->saldo, 2), 'f');

      if ($oLinha->ordem == 50) {

        $nDotacaoInicial     = '-';
        $nDotacaoAtualizada  = '-';
        $nDespesasLiquidadas = '-';
        $nDespesasPagas      = '-';
        $nSaldo              = '-';

        /**
         * Se o saldo n�o � superavit�rio (receita > despesa) n�o demonstra o valor na linha
         */
        if ($oLinha->despemp == 0) {
          $nDespesasEmpenhadas = '-';
        }
      }

      $this->oPdf->Cell($this->iLargura * 0.40, $this->iAltura, $oLinha->descricao, $sBorda, 0, 'L');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nDotacaoInicial, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nDotacaoAtualizada, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nDespesasEmpenhadas, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nDespesasLiquidadas, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nDespesasPagas, 'L' . $sBorda, 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, $nSaldo, 'L' . $sBorda, 1, 'R');
    }
  }

  /**
   * Escreve uma linha do quadro Execu��o de Restos a Pagar N�o Processados
   *
   * @param  stdClass $oLinha
   * @param  string   $sBorda
   */
  private function escreverLinhaRestosNaoProcessados(stdClass $oLinha, $sBorda) {

    $nExercicioAnterior     = db_formatar(round($oLinha->exanterior, 2), 'f');
    $nExercicioAnterior3112 = db_formatar(round($oLinha->exanterior3112, 2), 'f');
    $nLiquidados            = db_formatar(round($oLinha->liquidados, 2), 'f');
    $nPagos                 = db_formatar(round($oLinha->pagos, 2), 'f');
    $nCancelados            = db_formatar(round($oLinha->cancelados, 2), 'f');
    $nSaldo                 = db_formatar(round($oLinha->saldo, 2), 'f');

    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $oLinha->descricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, $nExercicioAnterior, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, $nExercicioAnterior3112, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, $nLiquidados, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, $nPagos, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.133, $this->iAltura, $nCancelados, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.135, $this->iAltura, $nSaldo, 'L' . $sBorda, 1, 'R');
  }

  /**
   * Escreve uma linha do quadro Execu��o de Restos a Pagar Processados e N�o Processados Liquidados
   *
   * @param  stdClass $oLinha
   * @param  string   $sBorda
   */
  private function escreverLinhaRestosProcessados(stdClass $oLinha, $sBorda) {

    $nExercicioAnterior     = db_formatar(round($oLinha->exanterior, 2), 'f');
    $nExercicioAnterior3112 = db_formatar(round($oLinha->exanterior3112, 2), 'f');
    $nPagos                 = db_formatar(round($oLinha->pagos, 2), 'f');
    $nCancelados            = db_formatar(round($oLinha->cancelados, 2), 'f');
    $nSaldo                 = db_formatar(round($oLinha->saldo, 2), 'f');

    $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $oLinha->descricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, $nExercicioAnterior, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, $nExercicioAnterior3112, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, $nPagos, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, $nCancelados, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, $nSaldo, 'L' . $sBorda, 1, 'R');
  }

  /**
   * Escreve uma linha do relat�rio.
   *
   * @param integer  $iQuadro C�digo do quadro.
   * @param stdClass $oLinha  Linha a ser escrita.
   */
  private function escreverLinha($iQuadro, stdClass $oLinha) {

    $oLinha->descricao = str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao;
    $sBorda = '';

    if (in_array($oLinha->ordem, $this->aLinhasNegrito)) {
      $this->oPdf->setBold(true);
    }
    if ($oLinha->totalizadorFinal) {
      $sBorda = 'TB';
    }
    if ($oLinha->ultimaLinhaQuadro) {
      $sBorda = 'B';
    }

    switch ($iQuadro) {

      case self::QUADRO_PRINCIPAL:

        $this->escreverLinhaQuadroPrincipal($oLinha, $sBorda);
        break;

      case self::QUADRO_RESTOS_NAO_PROCESSADOS:

        $this->escreverLinhaRestosNaoProcessados($oLinha, $sBorda);
        break;

      case self::QUADRO_RESTOS_PROCESSADOS:

        $this->escreverLinhaRestosProcessados($oLinha, $sBorda);
        break;

      default:

        throw new Exception("Quadro inv�lido.");
        break;
    }

    $this->oPdf->setBold(false);
  }

  /**
   * @see RelatoriosLegaisBase::getDados()
   * @return array
   */
  public function getDados() {

    $aLinhasConsistencia = parent::getDados();
    foreach (array(26, 50) as $iLinha) {
      $this->processaFormulasLinha($aLinhasConsistencia, $iLinha);
    }
    $this->processaTotalizadores($aLinhasConsistencia);

    return $aLinhasConsistencia;
  }

  /**
   * Emite o relat�rio
   */
  public function emitir() {

    $this->prepararCabecalhos();
    $this->processarQuadros();
    $this->configurarRelatorio();
    $this->oLinha = $this->aDados[1];

    if ($this->exibirQuadroRelatorio(self::QUADRO_PRINCIPAL)) {
      $this->emitirQuadro(self::QUADRO_PRINCIPAL, $this->aQuadroPrincipal);
    }

    if ($this->exibirQuadroRelatorio(self::QUADRO_RESTOS_NAO_PROCESSADOS)) {
      $this->emitirQuadro(self::QUADRO_RESTOS_NAO_PROCESSADOS, $this->aQuadroRestosNaoProcessados);
    }

    if ($this->exibirQuadroRelatorio(self::QUADRO_RESTOS_PROCESSADOS)) {
      $this->emitirQuadro(self::QUADRO_RESTOS_PROCESSADOS, $this->aQuadroRestosProcessados);
    }

    $this->oPdf->showPDF('balancoOrcamentarioDCASP2015_' . time());
  }

}
