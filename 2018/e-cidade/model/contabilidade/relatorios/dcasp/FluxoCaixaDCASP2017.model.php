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

class FluxoCaixaDCASP2017 extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 171;

  const QUADRO_PRINCIPAL_INICIAL           = 1;
  const QUADRO_PRINCIPAL_FINAL             = 31;
  const QUADRO_RECEITAS_DERIVADAS_INICIAL  = 32;
  const QUADRO_RECEITAS_DERIVADAS_FINAL    = 41;
  const QUADRO_TRANSFERENCIAS_INICIAL      = 42;
  const QUADRO_TRANSFERENCIAS_FINAL        = 57;
  const QUADRO_DESEMBOLSOS_PESSOAL_INICIAL = 58;
  const QUADRO_DESEMBOLSOS_PESSOAL_FINAL   = 86;
  const QUADRO_DIVIDA_INICIAL              = 87;
  const QUADRO_DIVIDA_FINAL                = 90;

  const QUADRO_PRINCIPAL      = 1;
  const QUADRO_RECEITAS       = 2;
  const QUADRO_TRANSFERENCIAS = 3;
  const QUADRO_DESEMBOLSOS    = 4;
  const QUADRO_DIVIDA         = 5;

  /**
   * Identifica quais linhas são totalizadoras.
   *
   * @var array
   */
  private $aLinhasTotalizadoras = array(9, 18, 29, 42, 49, 57, 86, 90);

  /**
   * Linhas que não devem exibir valor
   *
   * @var array
   */
  private $aLinhasSemValor = array(1, 10, 19, 42, 50);

  /**
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
   * Determina se deve buscar e exibir as informações do exercício anterior.
   * @var boolean
   */
  private $lExibirExercicioAnterior = true;

  /**
   * Linhas do Quadro Principal
   *
   * @var array
   */
  private $aQuadroPrincipal;

  /**
   * Linhas do Quadro Receitas Derivadas Originárias
   *
   * @var array
   */
  private $aQuadroReceitas;

  /**
   * Linhas do Quadro Transfêrencias Recebidas e Concedidas
   *
   * @var array
   */
  private $aQuadroTransferencias;

  /**
   * Linhas do Quadro Desembolso de Pessoal e Demais Despesas Por Função
   *
   * @var array
   */
  private $aQuadroDesembolsos;

  /**
   * Linhas do Quadro Juros e Encargos da Dívida
   *
   * @var array
   */
  private $aQuadroDivida;

  /**
   * Quadros que serão exibidos no relatório
   *
   * @var array
   */
  private $aRelatoriosExibir = array();

  /**
   * @param integer $iAnoUsu          Ano da emissão do relatório.
   * @param integer $iCodigoRelatorio Código do relatório cadastrado no sistema.
   * @param integer $iCodigoPeriodo   Código do período de emissão do relatório.
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
   * @param array $aQuadrosExibir Array de constantes identificando quais quadros do relatório devem ser exibidos.
   */
  public function setExibirQuadros($aQuadrosExibir) {
    $this->aRelatoriosExibir = $aQuadrosExibir;
  }

  /**
   *
   * @param boolean $lExibirExercicioAnterior
   */
  public function setExibirExercicioAnterior($lExibirExercicioAnterior) {
    $this->lExibirExercicioAnterior = $lExibirExercicioAnterior;
  }

  /**
   * Retorna os Dados para emissão do Relatório
   *
   * @return array
   */
  public function getDados() {

    /**
     * Busca as linhas e separa por tipo de balancete
     */
    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->processarTiposDeCalculo();

    /**
     * Monta o período anterior
     */
    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');
    $oDataFinalAnterior = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    /**
     * Calcula o valor das linhas do balancete da Receita
     */
    $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
    $rsBalanceteReceita = db_receitasaldo( 11, 1, 3, true,
                                           $sWhereReceita, $this->iAnoUsu,
                                           $this->getDataInicial()->getDate(),
                                           $this->getDataFinal()->getDate() );
    $this->limparEstruturaBalanceteReceita();

    $rsBalanceteReceitaAnterior = db_receitasaldo( 11, 1, 3, true,
                                                   $sWhereReceita, $this->iAnoUsu - 1,
                                                   $oDataInicialAnterior->getDate(),
                                                   $oDataFinalAnterior->getDate() );
    $this->limparEstruturaBalanceteReceita();

    foreach ($this->aLinhasProcessarReceita as $iLinha) {

      $oLinha = $this->aLinhasConsistencia[$iLinha];

      $aColunas = $this->processarColunasDaLinha($oLinha, 0);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteReceita,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_RECEITA );

      $aColunas = $this->processarColunasDaLinha($oLinha, 1);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteReceitaAnterior,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_RECEITA );
    }

    /**
     * Calcula o valor das linhas do balancete da despesa
     */
    $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
    $rsBalanceteDespesa = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                           $this->iAnoUsu,
                                           $this->getDataInicial()->getDate(),
                                           $this->getDataFinal()->getDate() );
    $this->limparEstruturaBalanceteDespesa();

    $rsBalanceteDespesaAnterior = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                                   $this->iAnoUsu - 1,
                                                   $oDataInicialAnterior->getDate(),
                                                   $oDataFinalAnterior->getDate() );
    $this->limparEstruturaBalanceteDespesa();

    foreach ($this->aLinhasProcessarDespesa as $iLinha) {

      $oLinha = $this->aLinhasConsistencia[$iLinha];

      $aColunas = $this->processarColunasDaLinha($oLinha, 0);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteDespesa,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_DESPESA );

      $aColunas = $this->processarColunasDaLinha($oLinha, 1);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteDespesaAnterior,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_DESPESA );
    }

    /**
     * Calcula o valor das linhas do balancete de verificação
     */
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

    $rsBalanceteVerificacaoAnterior = db_planocontassaldo_matriz( $this->iAnoUsu -1,
                                                                  $oDataInicialAnterior->getDate(),
                                                                  $oDataFinalAnterior->getDate(),
                                                                  false,
                                                                  $sWhereVerificacao,
                                                                  '',
                                                                  'true',
                                                                  'false' );
    $this->limparEstruturaBalanceteVerificacao();

    foreach ($this->aLinhasProcessarVerificacao as $iLinha ) {

      $oLinha = $this->aLinhasConsistencia[$iLinha];

      $aColunas = $this->processarColunasDaLinha($oLinha, 0);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteVerificacao,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO );

      $aColunas = $this->processarColunasDaLinha($oLinha, 1);
      RelatoriosLegaisBase::calcularValorDaLinha( $rsBalanceteVerificacaoAnterior,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO );
    }

    /**
     * Calcula o valor dos restos a pagar nas linhas da despesa
     */
    $oDaoRestosAPagar = new cl_empresto();
    $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";

    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo( $this->iAnoUsu,
                                                        $sWhereRestoPagar,
                                                        $this->getDataInicial()->getDate(),
                                                        $this->getDataFinal()->getDate() );

    $rsRestosPagar = db_query($sSqlRestosaPagar);

    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo( $this->iAnoUsu - 1,
                                                        $sWhereRestoPagar,
                                                        $oDataInicialAnterior->getDate(),
                                                        $oDataFinalAnterior->getDate() );

    $rsRestosPagarAnterior = db_query($sSqlRestosaPagar);

    foreach ($this->aLinhasProcessarDespesa as $iLinha) {

      $oLinha = $this->aLinhasConsistencia[$iLinha];

      $aColunas = $this->processarColunasDaLinha($oLinha, 0);
      $aColunas[0]->formula = '#vlrpag+#vlrpagnproc';
      RelatoriosLegaisBase::calcularValorDaLinha( $rsRestosPagar,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_RESTO );

      $aColunas = $this->processarColunasDaLinha($oLinha, 1);
      $aColunas[0]->formula = '#vlrpag+#vlrpagnproc';
      RelatoriosLegaisBase::calcularValorDaLinha( $rsRestosPagarAnterior,
                                                  $oLinha,
                                                  $aColunas,
                                                  RelatoriosLegaisBase::TIPO_CALCULO_RESTO );
    }

    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);

    /**
     * Recalcula o valor das linhas que vem de outros quadros
     */
    foreach (array(3, 4, 6, 7, 8) as $iLinha) {
      $oLinha = $this->processaFormulasLinha($this->aLinhasConsistencia, $iLinha);
    }

    /**
     * Recalcula novamente os totalizadores
     */
    $this->processaTotalizadores($this->aLinhasConsistencia);

    return $this->aLinhasConsistencia;
  }

  private function verificaTotalizadorFinal($iLinha) {
    return in_array($iLinha, $this->aLinhasTotalizadoras);
  }

  /**
   * Adiciona uma nova página, reinserindo o cabeçalho do relatório.
   *
   * @param string $sNomeQuadro Nome do quadro do relatório.
   * @param string $sNomeColuna Nome da columa de descrição do cabeçalho do quadro.
   */
  private function adicionarPagina($sNomeQuadro, $sNomeColuna) {

    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("FLUXO DE CAIXA");
    $this->oPdf->addHeaderDescription($sNomeQuadro);
    $this->oPdf->addHeaderDescription("EXERCÍCIO : {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PERÍODO : {$this->sDescricaoPeriodo}");
    $this->oPdf->AddPage();

    if ($sNomeColuna !== null) {
      $this->escreverCabecalhoQuadro($sNomeColuna);
    }
  }

  /**
   * Emite um quadro do relatório.
   *
   * @param string     $sNomeQuadro  Nome do quadro,
   * @param string     $sNomeColuna  Nome da coluna descrição,
   * @param stdClass[] $aDadosQuadro Linhas do quadro.
   */
  private function emitirQuadro($sNomeQuadro, $sNomeColuna, $aDadosQuadro) {

    /**
     * Se o quadro não foi processado
     */
    if (!$aDadosQuadro) return;

    $this->adicionarPagina($sNomeQuadro, $sNomeColuna);

    foreach ($aDadosQuadro as $oLinha) {

      if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
      }
      $this->escreverLinha($oLinha);
    }

    $this->escreverNotaExplicativa($sNomeQuadro, null);
    $this->escreveAssinatura($sNomeQuadro, null);
  }

  /**
   * Escreve as assinaturas do quadro do relatório.
   *
   * @param string $sNomeQuadro Nome do quadro.
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreveAssinatura($sNomeQuadro, $sNomeColuna) {

    if ($this->oPdf->getAvailHeight() < 45) {
      $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
    }

    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);
  }

  /**
   * Popula os atributos que serão utilizados no cabeçalho para não precisar processa-los a cada página.
   */
  private function preparaCabecalhos() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura                 = InstituicaoRepository::getInstituicaoPrefeitura();
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$oPrefeitura->getDescricao()} - CONSOLIDAÇÃO";
    } else {

      $oInstituicao                = current($aListaInstituicoes);
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$oInstituicao->getDescricao()}";
    }

    $this->sDescricaoPeriodo = $this->getDescricaoPeriodo();
  }

  /**
   * Escreve a nota explicativa para o quadro.
   *
   * @param string $sNomeQuadro Nome do quadro.
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreverNotaExplicativa($sNomeQuadro, $sNomeColuna) {

    $this->oPdf->Ln(2);
    if ($this->oPdf->getAvailWidth() < 10) {
      $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
    }

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
  }

  /**
   * Informa se um quadro do relatório deve ser exibido, de acordo com seu código.
   *
   * @param integer $iCodigo Código do quadro de acordo com as constantes desta classe.
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

    if($this->exibirQuadroRelatorio(self::QUADRO_PRINCIPAL)) {

      for ($iIndice = self::QUADRO_PRINCIPAL_INICIAL; $iIndice <= self::QUADRO_PRINCIPAL_FINAL; $iIndice++) {
        $this->aQuadroPrincipal[] = $this->processarLinha($iIndice, self::QUADRO_PRINCIPAL_FINAL);
      }
    }

    if($this->exibirQuadroRelatorio(self::QUADRO_RECEITAS)) {

      for ($iIndice = self::QUADRO_RECEITAS_DERIVADAS_INICIAL; $iIndice <= self::QUADRO_RECEITAS_DERIVADAS_FINAL; $iIndice++) {
        $this->aQuadroReceitas[] = $this->processarLinha($iIndice, self::QUADRO_RECEITAS_DERIVADAS_FINAL);
      }
    }

    if($this->exibirQuadroRelatorio(self::QUADRO_TRANSFERENCIAS)) {

      for ($iIndice = self::QUADRO_TRANSFERENCIAS_INICIAL; $iIndice <= self::QUADRO_TRANSFERENCIAS_FINAL; $iIndice++) {
        $this->aQuadroTransferencias[] = $this->processarLinha($iIndice, self::QUADRO_TRANSFERENCIAS_FINAL);
      }
    }

    if($this->exibirQuadroRelatorio(self::QUADRO_DESEMBOLSOS)) {

      for ($iIndice = self::QUADRO_DESEMBOLSOS_PESSOAL_INICIAL; $iIndice <= self::QUADRO_DESEMBOLSOS_PESSOAL_FINAL; $iIndice++) {
        $this->aQuadroDesembolsos[] = $this->processarLinha($iIndice, self::QUADRO_DESEMBOLSOS_PESSOAL_FINAL);
      }
    }

    if($this->exibirQuadroRelatorio(self::QUADRO_DIVIDA)) {

      for ($iIndice = self::QUADRO_DIVIDA_INICIAL; $iIndice <= self::QUADRO_DIVIDA_FINAL; $iIndice++) {
        $this->aQuadroDivida[] = $this->processarLinha($iIndice, self::QUADRO_DIVIDA_FINAL);
      }
    }

  }

  /**
   * Realizar as configurações iniciais do pdf.
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
   * Procura a descrição do período de acordo com o atributo iCodigoPeriodo
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
   * Escreve o cabeçalho do quadro.
   *
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreverCabecalhoQuadro($sNomeColuna) {

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, $sNomeColuna, 'TB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Atual", 'LTB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Anterior", 'LTB', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Prepara a linha para ser utilizada no relatório.
   *
   * @param integer $iLinha            Número da linha.
   * @param integer $iLinhaFinalQuadro Número da linha final do quadro.
   *
   * @return stdClass
   */
  private function processarLinha($iLinha, $iLinhaFinalQuadro) {

    $oLinha                    = $this->aDados[$iLinha];
    $oLinha->ultimaLinhaQuadro = $iLinha == $iLinhaFinalQuadro;
    $oLinha->totalizadorFinal  = $this->verificaTotalizadorFinal($iLinha);

    return $oLinha;
  }

  /**
   * Escreve uma linha do relatório.
   *
   * @param stdClass $oLinha Linha a ser escrita.
   */
  private function escreverLinha(stdClass $oLinha) {

    $nPorcentagemDescricao = 0.6;
    $sExercicioAnterior    = '-';
    $sExercicioAtual       = db_formatar($oLinha->vlrexatual, 'f');
    $sBorda                = '';
    $sDescricao            = str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao;

    if ($oLinha->totalizar) {
      $this->oPdf->setBold(true);
    }

    if ($this->lExibirExercicioAnterior) {
      $sExercicioAnterior = db_formatar($oLinha->vlrexanter, 'f');
    }

    if (in_array($oLinha->ordem, $this->aLinhasSemValor)) {

      $sExercicioAnterior = '';
      $sExercicioAtual    = '';
      $this->iAltura      += 4;
    }

    if ($oLinha->ultimaLinhaQuadro) {
      $sBorda = 'B';
    }

    if ($oLinha->totalizadorFinal) {
      $sBorda = "TB";
    }

    $this->oPdf->Cell($this->iLargura * $nPorcentagemDescricao, $this->iAltura, $sDescricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAtual, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercicioAnterior, 'L' . $sBorda, 1, 'R');

    if (in_array($oLinha->ordem, $this->aLinhasSemValor)) {
      $this->iAltura -= 4;
    }

    $this->oPdf->setBold(false);
  }

  /**
   * Busca e processa os dados necessários para os quadros do relatório.
   */
  private function getDadosQuadros() {

    $this->aDados = $this->getDados();
    $this->processarQuadros();
  }

  /**
   * Emite o relatório
   */
  public function emitir() {

    $this->preparaCabecalhos();
    $this->getDadosQuadros();
    $this->configurarPdf();

    $this->emitirQuadro("QUADRO PRINCIPAL", "", $this->aQuadroPrincipal);
    $this->emitirQuadro("QUADRO DE RECEITAS DERIVADAS E ORIGINÁRIAS", "", $this->aQuadroReceitas);
    $this->emitirQuadro("QUADRO DE TRANSFERÊNCIAS RECEBIDAS E CONCEDIDAS", "", $this->aQuadroTransferencias);
    $this->emitirQuadro("QUADRO DE DESEMBOLSOS DE PESSOAL E DEMAIS DESPESAS POR FUNÇÃO", "", $this->aQuadroDesembolsos);
    $this->emitirQuadro("QUADRO DE JUROS E ENCARGOS DA DÍVIDA", "", $this->aQuadroDivida);

    $this->oPdf->showPDF('fluxoCaixaDCASP_' . time());
  }

}
