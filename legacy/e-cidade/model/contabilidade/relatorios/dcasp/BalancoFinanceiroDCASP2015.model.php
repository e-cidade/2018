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

/**
 * Class BalancoFinanceiroDCASP2015
 */
class BalancoFinanceiroDCASP2015 extends RelatoriosLegaisBase {

  /**
   * @type int
   */
  const CODIGO_RELATORIO        = 152;

  const LINHA_INICIO_DISPENDIOS = 24;

  const LINHA_FINAL_DISPENDIOS  = 46;

  const TIPO_ANALITICO = "A";

  const TIPO_SINTETICO = "S";

  /**
   *
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Nome da instituição a ser exibida no relatório.
   *
   * @var string
   */
  private $sDescricaoInstituicao;

  /**
   * Nome do período a ser exibido no relatório.
   *
   * @var string
   */
  private $sDescricaoPeriodo;

  /**
   * Determina se deve exibir as informações do exercício anterior.
   *
   * @var boolean
   */
  private $lExibirExercicioAnterior = true;

  /**
   * Linhas finais de cada seção. Utilizado somente para formatar a linha
   *
   * @var array
   */
  private $aLinhasFinais = array(23, 46);

    /**
   * Linhas que podem ter Recursos, caso o relatório seja emitido
   * como analítico
   *
   * @var array
   */
  private $aLinhasComRecurso = array(4, 5, 6, 7, 8, 9, 27, 28, 29, 30, 31, 32);

  /**
   * Tipo de impressão (Analítico ou Sintético)
   * Utilizar as constantes TIPO_ANALITICO e TIPO_SINTETICO
   *
   * @var string
   */
  private $sTipo;

  /**
   * @var integer
   */
  private $iAltura;

  /**
   * @var integer
   */
  private $iLargura;

  /**
   * @param int $iAnoUsu
   * @param int $iCodigoRelatorio
   * @param int $iCodigoPeriodo
   * @see RelatoriosLegaisBase
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oPdf = new PDFDocument();
    $this->iAltura  = 4;
    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
  }

  /**
   *
   * @param boolean $lExibirExercicioAnterior
   */
  public function setExibirExercicioAnterior($lExibirExercicioAnterior) {
    $this->lExibirExercicioAnterior = $lExibirExercicioAnterior;
  }

  /**
   * @param string $sTipo
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  }

  public function getDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->processarTiposDeCalculo();

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');
    $oDataFinalAnterior = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    if ($this->sTipo == self::TIPO_ANALITICO) {

      foreach ($this->aLinhasComRecurso as $iLinha) {

        foreach($this->aLinhasConsistencia[$iLinha]->colunas as $oColuna) {

          $oColuna->agrupar = (object) array(
              'nome' => 'recursos',
              'campo' => ($iLinha <= 9 ? 'o70_codigo' : 'o58_codigo'),
              'descricao' => 'o15_descr'
            );
        }
      }
    }

    /**
     * Executa o Balancete da receita
     */
    if (!empty($this->aLinhasProcessarReceita)) {

      $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
      $rsBalanceteReceita = db_receitasaldo( 11, 1, 3, true,
                                             $sWhereReceita, $this->iAnoUsu,
                                             $this->getDataInicial()->getDate(),
                                             $this->getDataFinal()->getDate() );
      $this->limparEstruturaBalanceteReceita();

      if ($this->lExibirExercicioAnterior) {

        $rsBalanceteReceitaAnterior = db_receitasaldo( 11, 1, 3, true,
                                                   $sWhereReceita, $this->iAnoUsu - 1,
                                                   $oDataInicialAnterior->getDate(),
                                                   $oDataFinalAnterior->getDate() );
        $this->limparEstruturaBalanceteReceita();
      }

      foreach ($this->aLinhasProcessarReceita as $iLinha) {

        $oLinha = $this->aLinhasConsistencia[$iLinha];

        $aColunas = $this->getColunasPorLinha($oLinha, array(0));
        RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteReceita,
                                                    $oLinha,
                                                    $aColunas,
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RECEITA );

        if ($this->lExibirExercicioAnterior) {

          $aColunas           = $this->getColunasPorLinha($oLinha, array(1));
          $oLinha->vlrexanter = 0;

          RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteReceitaAnterior,
                                                      $oLinha,
                                                      $aColunas,
                                                      RelatoriosLegaisBase::TIPO_CALCULO_RECEITA );
        }
      }
    }

    /**
     * Executa o Balancete da despesa
     */
    if (!empty($this->aLinhasProcessarDespesa)) {

      $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
      $rsBalanceteDespesa = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                             $this->iAnoUsu,
                                             $this->getDataInicial()->getDate(),
                                             $this->getDataFinal()->getDate() );
      $this->limparEstruturaBalanceteDespesa();

      if ($this->lExibirExercicioAnterior) {

        $rsBalanceteDespesaAnterior = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                                       $this->iAnoUsu - 1,
                                                       $oDataInicialAnterior->getDate(),
                                                       $oDataFinalAnterior->getDate() );
        $this->limparEstruturaBalanceteDespesa();
      }

      foreach ($this->aLinhasProcessarDespesa as $iLinha) {

        $oLinha = $this->aLinhasConsistencia[$iLinha];

        $aColunas = $this->getColunasPorLinha($oLinha, array(0));
        RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteDespesa,
                                                    $oLinha,
                                                    $aColunas,
                                                    RelatoriosLegaisBase::TIPO_CALCULO_DESPESA );

        if ($this->lExibirExercicioAnterior) {

          $aColunas = $this->getColunasPorLinha($oLinha, array(1));
          RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteDespesaAnterior,
                                                      $oLinha,
                                                      $aColunas,
                                                      RelatoriosLegaisBase::TIPO_CALCULO_DESPESA );
        }
      }
    }

    /**
     * Executa o balancete de verificação
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

      if ($this->lExibirExercicioAnterior) {

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

        if ($this->lExibirExercicioAnterior) {

          $aColunas = $this->getColunasPorLinha($oLinha, array(1));
          RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteVerificacaoAnterior,
                                                      $oLinha,
                                                      $aColunas,
                                                      RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO );
        }
      }
    }


    /**
     * Executa os restos a pagar
     */
    if (!empty($this->aLinhasProcessarRestosPagar)) {

      $oDaoRestosAPagar = new cl_empresto();
      $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";

      $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo( $this->iAnoUsu,
                                                          $sWhereRestoPagar,
                                                          $this->getDataInicial()->getDate(),
                                                          $this->getDataFinal()->getDate() );

      $rsRestosPagar = db_query($sSqlRestosaPagar);
      if ($this->lExibirExercicioAnterior) {

        $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo( $this->iAnoUsu - 1,
                                                            $sWhereRestoPagar,
                                                            $oDataInicialAnterior->getDate(),
                                                            $oDataFinalAnterior->getDate() );

        $rsRestosPagarAnterior = db_query($sSqlRestosaPagar);
      }

      foreach ($this->aLinhasProcessarRestosPagar as $iLinha) {

        $oLinha = $this->aLinhasConsistencia[$iLinha];

        $aColunas = $this->getColunasPorLinha($oLinha, array(0));
        RelatoriosLegaisBase::calcularValorDaLinha( $rsRestosPagar,
                                                    $oLinha,
                                                    $aColunas,
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RESTO );

        if ($this->lExibirExercicioAnterior) {

          $aColunas = $this->getColunasPorLinha($oLinha, array(1));
          RelatoriosLegaisBase::calcularValorDaLinha( $rsRestosPagarAnterior,
                                                      $oLinha,
                                                      $aColunas,
                                                      RelatoriosLegaisBase::TIPO_CALCULO_RESTO );
        }
      }
    }

    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);

    /**
     * Remove os recursos não informados
     */
    if ($this->sTipo == self::TIPO_ANALITICO) {

      foreach ($this->aLinhasComRecurso as $iLinha) {

        if (isset($this->aLinhasConsistencia[$iLinha]->recursos[0])) {

          unset($this->aLinhasConsistencia[$iLinha]->recursos[0]);
        }
      }
    }

    return $this->aLinhasConsistencia;
  }

  /**
   * Adiciona uma nova página, reinserindo o cabeçalho do relatório.
   *
   * @param string  $sNomeSecao
   * @param boolean $lEscreveCabecalho
   */
  private function adicionarPagina($sNomeSecao = null, $lEscreveCabecalho = true) {

    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("BALANÇO FINANCEIRO");
    $this->oPdf->addHeaderDescription("EXERCÍCIO : {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PERÍODO : {$this->sDescricaoPeriodo}");
    $this->oPdf->addHeaderDescription("TIPO : " . ($this->sTipo == self::TIPO_ANALITICO ? "ANALÍTICO" : "SINTÉTICO"));
    $this->oPdf->AddPage();

    if ($lEscreveCabecalho === true) {
      $this->escreverCabecalho($sNomeSecao);
    }
  }

  /**
   * Escreve as assinaturas do relatório.
   *
   */
  private function escreveAssinatura() {

    if ($this->oPdf->getAvailHeight() < 45) {
      $this->adicionarPagina(null, false);
    }

    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);
  }

  /**
   * Popula os atributos que serão utilizados no cabeçalho.
   */
  private function preparaCabecalho() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
    $sDescricao  = "{$oPrefeitura->getDescricao()} - {$oPrefeitura->getUf()}";
    if (count($aListaInstituicoes) > 1) {
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$sDescricao} - CONSOLIDAÇÃO";
    } else {

      $oInstituicao = current($aListaInstituicoes);
      $sDescricao   = "{$oInstituicao->getDescricao()} - {$oPrefeitura->getUf()}";
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$sDescricao}";
    }

    $this->sDescricaoPeriodo = $this->getPeriodo()->getDescricao();
  }

  /**
   * Escreve a nota explicativa.
   *
   */
  private function escreverNotaExplicativa() {

    $this->oPdf->Ln(2);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
  }

  /**
   * Configura formatação do relatório.
   *
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
   * Escreve o cabeçalho da seção.
   *
   * @param string $sNomeSecao
   */
  private function escreverCabecalho($sNomeSecao = null) {

    if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina($sNomeSecao);
        return;
    }

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, $sNomeSecao, 'TB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Atual", 'LTB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Anterior", 'LTB', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Escreve uma linha do relatório.
   *
   * @param stdClass $oLinha Linha a ser escrita.
   */
  private function escreverLinha(stdClass $oLinha) {

    $sExercicioAnterior    = '-';
    $sExercicioAtual       = db_formatar($oLinha->vlrexatual, 'f');
    $sBorda                = '';
    $sDescricao            = str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao;

    /**
     * Se é linha totalizadora
     */
    if ($oLinha->totalizar) {
      $this->oPdf->setBold(true);
    }

    /**
     * Se deve exibir valor do exercício anterior
     */
    if ($this->lExibirExercicioAnterior) {
      $sExercicioAnterior = db_formatar($oLinha->vlrexanter, 'f');
    }

    if (in_array($oLinha->ordem, $this->aLinhasFinais)) {

      $sBorda = 'TB';
      $this->iAltura += 2;
    }

    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, $sDescricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAtual, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAnterior, 'L' . $sBorda, 1, 'R');

    if (in_array($oLinha->ordem, $this->aLinhasFinais)) {

      $this->iAltura -= 2;
      if ($oLinha->ordem != self::LINHA_FINAL_DISPENDIOS) {
        $this->oPdf->Ln($this->iAltura);
      }
    }

    $this->oPdf->setBold(false);
  }

  /**
   * Escreve uma linha de Recurso
   *
   * @param  stdClass $oLinha
   * @param  stdClass $oRecurso
   */
  private function escreverLinhaRecurso(stdClass $oLinha, stdClass $oRecurso) {

    $nValorRecursoAtual = property_exists($oRecurso, 'vlrexatual') ? $oRecurso->vlrexatual : 0;
    $sExercicioAnterior = '-';
    $sExercicioAtual    = db_formatar($nValorRecursoAtual, 'f');
    $sBorda             = '';
    $sDescricao         = str_repeat(' ', ($oLinha->nivel * 2) + 2) . $oRecurso->nome;

    /**
     * Se deve exibir valor do exercício anterior
     */
    $nValorRecursoAnterior = 0;
    if ($this->lExibirExercicioAnterior) {

      $nValorRecursoAnterior = property_exists($oRecurso, 'vlrexanter') ? $oRecurso->vlrexanter : 0;
      $sExercicioAnterior    = db_formatar($nValorRecursoAnterior, 'f');
    }

    if ($nValorRecursoAtual <= 0 && $nValorRecursoAnterior <= 0) {
      return;
    }

    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, $sDescricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAtual, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAnterior, 'L' . $sBorda, 1, 'R');
  }

  /**
   * Emite o relatório.
   *
   * @return void
   */
  public function emitir() {

    $aDados = $this->getDados();
    $sNomeSecao = "INGRESSOS";

    $this->preparaCabecalho();
    $this->configurarPdf();
    $this->adicionarPagina($sNomeSecao);

    foreach ($aDados as $oLinha) {

      if ($oLinha->ordem == self::LINHA_INICIO_DISPENDIOS) {

        $sNomeSecao = "DISPÊNDIOS";
        $this->escreverCabecalho($sNomeSecao);
      }

      if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina($sNomeSecao);
      }

      if ($oLinha->ordem == self::LINHA_FINAL_DISPENDIOS - 1 && $this->oPdf->getAvailHeight() < 58) {
        $this->adicionarPagina($sNomeSecao);
      }

      $this->escreverLinha($oLinha);

      if (isset($oLinha->recursos) && count($oLinha->recursos) > 0 &&
          in_array($oLinha->ordem, $this->aLinhasComRecurso)) {

        foreach ($oLinha->recursos as $oRecurso) {

          if ($this->oPdf->getAvailHeight() < 18) {
            $this->adicionarPagina($sNomeSecao);
          }

          $this->escreverLinhaRecurso($oLinha, $oRecurso);
        }
      }
    }

    $this->escreverNotaExplicativa();
    $this->escreveAssinatura();
    $this->oPdf->showPDF('BalancoFinanceiroDCASP_' . time());
  }

  /**
   * @return array
   */
  public function getLinhasObrigaRecurso() {

    $aRecursosObrigatorios = $this->aLinhasComRecurso;
    $aRecursosObrigatorios[] = 2;
    $aRecursosObrigatorios[] = 25;
    return $aRecursosObrigatorios;
  }
}
