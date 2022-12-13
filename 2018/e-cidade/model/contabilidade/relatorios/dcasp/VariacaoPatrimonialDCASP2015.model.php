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

class VariacaoPatrimonialDCASP2015 extends RelatoriosLegaisBase {

  /**
   * C�digo do relat�rio.
   */
  const CODIGO_RELATORIO = 154;

  /**
   * Tipo de relat�rio Anal�tico
   */
  const TIPO_ANALITICO = "A";

  /**
   * Tipo de relat�rio Sint�tico
   */
  const TIPO_SINTETICO = "S";

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var bool Se deve imprimir informa��es do exerc�cio anterior.
   */
  private $lImprimirExercicioAnterior = false;

  /**
   * @var string Tipo de impress�o (Sint�tico ou Anal�tico).
   */
  private $sTipo;

  /**
   * @var string Nome da institui��o que � utilizado no cabe�alho do relat�rio.
   */
  private $sDescricaoInstituicao;

  /**
   * @var string Nome do per�odo que � utilizado no cabe�alho do relat�rio.
   */
  private $sDescricaoPeriodo;

  /**
   * @var integer Largura total da p�gina do relat�rio.
   */
  private $iLargura;

  /**
   * @var integer Altura padr�o de uam linha do relat�rio.
   */
  private $iAltura;

  /**
   * @var array Linhas que devem ter bordas.
   */
  private $aBordas = array(43, 97, 98);

  /**
   * @var array Linhas que devem ter quebra de linha ap�s.
   */
  private $aQuebraLinha = array(43);


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
   * @param boolean $lImprimirExercicioAnterior
   */
  public function setImprimirExercicioAnterior($lImprimirExercicioAnterior) {
    $this->lImprimirExercicioAnterior = $lImprimirExercicioAnterior;
  }

  /**
   * @param string $sTipoImpressao
   */
  public function setTipo($sTipoImpressao) {
    $this->sTipo = $sTipoImpressao;
  }

  public function getDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->processarTiposDeCalculo();

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');
    $oDataFinalAnterior = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    /**
     * Executa o balancete de verifica��o
     */
    if (!empty($this->aLinhasProcessarVerificacao)) {

      $sWhereVerificacao      = " c61_instit in({$this->getInstituicoes()})";
      $rsBalanceteVerificacao = db_planocontassaldo_matriz( $this->iAnoUsu,
                                                            $this->getDataInicial()->getDate(),
                                                            $this->getDataFinal()->getDate(),
                                                            false,
                                                            $sWhereVerificacao,
                                                            '',
                                                            'true',
                                                            'false' );
      $this->limparEstruturaBalanceteVerificacao();

      if ($this->lImprimirExercicioAnterior) {

        $rsBalanceteVerificacaoAnterior = db_planocontassaldo_matriz( $this->iAnoUsu -1,
                                                                      $oDataInicialAnterior->getDate(),
                                                                      $oDataFinalAnterior->getDate(),
                                                                      false,
                                                                      $sWhereVerificacao,
                                                                      '',
                                                                      'true',
                                                                      'false' );
        $this->limparEstruturaBalanceteVerificacao();
      }

      foreach ($this->aLinhasProcessarVerificacao as $iLinha ) {

        $oLinha = $this->aLinhasConsistencia[$iLinha];

        $aColunas = $this->getColunasPorLinha($oLinha, array(0));
        RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteVerificacao,
                                                    $oLinha,
                                                    $aColunas,
                                                    RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO );

        if ($this->lImprimirExercicioAnterior) {

          $aColunas = $this->getColunasPorLinha($oLinha, array(1));
          RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteVerificacaoAnterior,
                                                      $oLinha,
                                                      $aColunas,
                                                      RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO );
        }
      }
    }

    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);

    return $this->aLinhasConsistencia;
  }

  /**
   * Procura a descri��o do per�odo de acordo com o atributo iCodigoPeriodo
   *
   * @return string
   */
  private function getDescricaoPeriodo() {

    $sNomePeriodo = "";
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
   * Popula os atributos que ser�o utilizados no cabe�alho.
   */
  private function preparaCabecalho() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura                 = InstituicaoRepository::getInstituicaoPrefeitura();
      $this->sDescricaoInstituicao = "INSTITUI��O : {$oPrefeitura->getDescricao()} - {$oPrefeitura->getUF()} - CONSOLIDA��O";
    } else {

      $oInstituicao                = current($aListaInstituicoes);
      $this->sDescricaoInstituicao = "INSTITUI��O : {$oInstituicao->getDescricao()} - {$oInstituicao->getUF()}";
    }

    $this->sDescricaoPeriodo = $this->getDescricaoPeriodo();
  }

  /**
   * Configura formata��o do relat�rio.
   */
  private function configurarPdf() {

    $this->oPdf->SetLeftMargin(10);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(true);
    $this->oPdf->SetFillcolor(235);
    $this->oPdf->SetFont('arial', '', 6);
  }

  /**
   * Adiciona uma nova p�gina, reinserindo o cabe�alho do relat�rio.
   */
  private function adicionarPagina() {

    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("DEMONSTRA��O DAS VARIA��ES PATRIMONIAIS");
    $this->oPdf->addHeaderDescription("EXERC�CIO : {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PER�ODO : {$this->sDescricaoPeriodo}");
    $this->oPdf->addHeaderDescription("TIPO : " . ($this->sTipo == self::TIPO_ANALITICO ? "ANAL�TICO" : "SINT�TICO"));
    $this->oPdf->AddPage();
    $this->escreverCabecalho();
  }

  /**
   * Escreve o cabe�alho da se��o.
   */
  private function escreverCabecalho() {

    if ($this->oPdf->getAvailHeight() < 18) {
      $this->adicionarPagina();
      return;
    }

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, "", 'TB');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exerc�cio Atual", 'LTB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exerc�cio Anterior", 'LTB', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Escreve uma linha do relat�rio.
   * @param stdClass $oLinha Objeto com as informa��es da linha.
   */
  private function escreverLinha($oLinha) {

    $sBorda = '';
    $sAlign = 'C';
    $lBold  = $this->oPdf->getBold();
    if (($this->sTipo == self::TIPO_SINTETICO && $oLinha->nivel == 1) || ($this->sTipo == self::TIPO_ANALITICO && $oLinha->totalizar)) {
      $this->oPdf->setBold(true);
    }

    if (in_array($oLinha->ordem, $this->aBordas)) {
      $sBorda = 'TB';
    }

    $sValorExercicioAnterior = "-";
    if ($this->lImprimirExercicioAnterior) {

      $sAlign = 'R';
      $sValorExercicioAnterior = db_formatar($oLinha->vlrexanter, 'f');
    }

    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, str_repeat(' ', $oLinha->nivel * 2 - 2) . $oLinha->descricao, $sBorda);
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, db_formatar($oLinha->vlrexatual, 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sValorExercicioAnterior, $sBorda . 'L', 1, $sAlign);

    $this->oPdf->setBold($lBold);
  }

  /**
   * Realiza a emiss�o do relat�rio.
   */
  public function emitir() {

    $aDados = $this->getDados();
    $this->preparaCabecalho();
    $this->configurarPdf();
    $this->adicionarPagina();

    foreach ($aDados as $oLinha) {

      //Se for sint�tico, pula as linhas n�o totalizadoras.
      if ($this->sTipo == self::TIPO_SINTETICO && $oLinha->totalizar != 1) {
        continue;
      }

      if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina();
      }

      $this->escreverLinha($oLinha);

      //Quebra para linhas espec�ficas.
      if (in_array($oLinha->ordem, $this->aQuebraLinha)) {

        $this->oPdf->Cell($this->iLargura * 0.6, $this->iAltura, "");
        $this->oPdf->Cell($this->iLargura * 0.2, $this->iAltura, "", 'L');
        $this->oPdf->Cell($this->iLargura * 0.2, $this->iAltura, "", 'L', 1);
      }
    }

    //Escreve notas explicativas e assinaturas.
    $this->oPdf->Ln($this->iAltura);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo);
    $this->oPdf->Ln($this->iAltura);
    $this->getRelatorioContabil()->assinatura($this->oPdf, 'BG');

    $this->oPdf->showPDF('variacaoPatrimonialDCASP_' . time());
  }
}
