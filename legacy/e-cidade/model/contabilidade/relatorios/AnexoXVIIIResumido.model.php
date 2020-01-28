<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoI    as FactoryAnexoI;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoVIII as FactoryAnexoVIII;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIV;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoXVIIIResumido
 */
class AnexoXVIIIResumido extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 98;

  /**
   * Balan�o Or�ament�rio
   */
  const EMITIR_BALANCO_ORCAMENTARIO = 'emite_balorc';

  /**
   * Receita/Despesa do RPPS
   */
  const EMITIR_DESPESAS_RECEITAS_RPPS = 'emite_receitas_despesas_rpps';

  /**
   * Despesas por Fun��o/SubFun��o
   */
  const EMITIR_DESPESA_FUNCAO_SUBFUNCAO = 'emite_desp_funcsub';

  /**
   * Receita Corrente L�quida
   */
  const EMITIR_RECEITA_CORRENTE_LIQUIDA = 'emite_rcl';

  /**
   * Restos a Pagar
   */
  const EMITIR_RESTOS_A_PAGAR = 'emite_rp';

  /**
   * Resultado Nominal/Prim�rio
   */
  const EMITIR_RESULTADO_NOMINAL_PRIMARIO = 'emite_resultado';

  /**
   * Despesas com MDE
   */
  const EMITIR_DESPESAS_MDE = 'emite_mde';

  /**
   * Despesas com Sa�de
   */
  const EMITIR_DESPESAS_SAUDE = 'emite_saude';

  /**
   * Opera��es de Cr�dito e Despesas de Capital
   */
  const EMITIR_OPERACAO_DE_CREDITO = 'emite_oper';

  /**
   * Proje��o Atuarial dos Regimes de Previd�ncia
   */
  const EMITIR_PROJECAO_ATUARIAL_RPPS = 'emite_proj';

  /**
   * Receita de Aliena��o de Ativos / Aplica��o dos Recursos
   */
  const EMITIR_ALIENACAO_ATIVOS  = 'emite_alienacao';

  /**
   * Despesas de Car�ter Continuado Derivadas de PPP
   */
  const EMITIR_PPP = 'emite_ppp';

  /**
   * Codigo referente ao SEXTO BIMESTRE
   */
  const CODIGO_SEXTO_BIMESTRE = 11;

  /**
   * @var stdClass Dados do Balan�o Or�ament�rio
   */
  private $oDadosBalancoOrcamentario;

  private $lRclCalculada = false;

  /**
   * @var bool Valor da Receita Corrente L�quida
   */
  private $nValorReceitaCorrenteLiquida = false;

  /**
   * @var PdfDocument
   */
  private $oPdf;

  private $sInstituicoes = null;

  /**
   * @var array Lista de relat�rios que devem ser emitidos
   */
  private $aRelatoriosEmitir = array();

  /**
   * Retorna os dados do Balanco Orcamentario
   * @return stdClass
   */
  private function getDadosBalancoOrcamentario() {

    if (empty($this->oDadosBalancoOrcamentario)) {

      $oBalancoOrcamentario = FactoryAnexoI::getInstance($this->iAnoUsu, $this->oPeriodo);
      $oBalancoOrcamentario->setDataInicial($oBalancoOrcamentario->getDataInicialPeriodo());
      $oBalancoOrcamentario->setInstituicoes($this->sInstituicoes);
      $this->oDadosBalancoOrcamentario = $oBalancoOrcamentario->getDadosSimplificado();
    }

    return $this->oDadosBalancoOrcamentario;
  }

  public function setExibirRelatorios($aRelatorios) {
    $this->aRelatoriosEmitir = $aRelatorios;
  }

  public function exibirRelatorio($sNomeRelatorio) {
    return !empty($this->aRelatoriosEmitir[$sNomeRelatorio]);
  }

  public function emitir() {

    $this->oPdf = new PDFDocument();
    $this->oPdf->Open();
    $this->oPdf->SetAutoPageBreak(false);
    $this->sInstituicoes = $this->sListaInstit;
    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

    $aInstituicoes = explode(",", $this->getInstituicoes());

    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }else {
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $this->oPdf->addHeaderDescription("RELAT�RIO RESUMIDO DA EXECU��O OR�AMENT�RIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO SIMPLIFICADO DO RELAT�RIO RESUMIDO DA EXECU��O OR�AMENT�RIA");
    $this->oPdf->addHeaderDescription("OR�AMENTO FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("");
    
    $sNomeMesInicial = mb_strtoupper(db_mes($this->oPeriodo->getMesInicial()));
    $sNomeMesFinal   = mb_strtoupper(db_mes($this->oPeriodo->getMesFinal()));    
    $this->oPdf->addHeaderDescription("JANEIRO � {$sNomeMesFinal} DE {$this->iAnoUsu} - BIMESTRE {$sNomeMesInicial} - {$sNomeMesFinal}");
    
    $this->adicionarPagina();

    $this->emitirBalancoOrcamentario();
    $this->emitirDemostrativoDespesaPorFuncaoSubfuncao();
    $this->emitirReceitaCorrenteLiquida();
    $this->emitirRegimeDePrevidencia();
    $this->emitirResultadosNominalPrimario();
    $this->emitirRestosPagar();
    $this->emitirDespesasComEnsino();
    $this->emitirOperacoesCreditoDespesasCapital();
    $this->emitirProjecaoAtuarialRPPS();

    if ($this->oPdf->getAvailHeight() < 48) {
      $this->adicionarPagina();
    }
    $this->emitirAlienacaoAtivosAplicacaoRecursos();

    if ($this->oPdf->getAvailHeight() < 48) {
      $this->adicionarPagina();
    }
    $this->emitirImpostosReceitasSaude();

    if ($this->oPdf->getAvailHeight() < 48) {
      $this->adicionarPagina();
    }
    $this->emiteDespesasDePPP();

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
    $this->oPdf->Ln(8);
    $this->escreverAssinaturas();

    $this->oPdf->showPDF('RREO_Anexo_XIV_Resumido_' . time());

  }

  /**
   * Emite os dados do balancete Orcament�rio
   */
  private function emitirBalancoOrcamentario() {

    if (!$this->exibirRelatorio("emite_balorc")) {
      return false;
    }
    $this->getDadosBalancoOrcamentario();

    $this->oPdf->Cell(120, 3, "BALAN�O OR�AMENT�RIO", 'TBR', 0, 'C');
    $this->oPdf->Cell(70, 3, "At� o Bimestre", 'TBL', 1, 'C');

    $this->oPdf->Cell(120, 3, "RECEITAS", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, "", 'L', 1, '');
    $this->oPdf->Cell(120, 3, "    Previs�o Inicial", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nPrevisaoInicial,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Previs�o Atualizada", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nPrevisaoAtualizada,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Receitas Realizada", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nReceitasRealizadas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    D�ficit Or�ament�rio", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nDeficitOrcamentario,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Saldos de Exerc�cios Anteriores (Utilizados para Cr�ditos Adicionais)", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nSaldoExerciciosAnteriores,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "DESPESAS", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, "", 'L', 1, '');
    $this->oPdf->Cell(120, 3, "    Dota��o Inicial", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nDotacaoInicial,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Cr�ditos Adicionais", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nCreditoAdicional,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Dota��o Atualizada", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nDotacaoAtualizada,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Despesas Empenhadas", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nEmpenhadas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Despesas Liquidadas", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nLiquidadas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Despesas Pagas", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nPagas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Super�vit Or�ament�rio", 'BR', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->oDadosBalancoOrcamentario->nSuperavitOrcamentario,'f'), 'BL', 1, 'R');
    $this->oPdf->ln();

  }

  /**
   * Retorna os dados das Despesas por Fun��o/Subfun��o
   *
   * @return stdClass
   */
  private function getDadosDespesasPorFuncaoSubfuncao() {

    $oAnexo = new AnexoIBalancoOrcamentario($this->iAnoUsu, AnexoIBalancoOrcamentario::CODIGO_RELATORIO, $this->iCodigoPeriodo);
    $oAnexo->setInstituicoes($this->sListaInstit);
    return $oAnexo->getDadosSimplificado();
  }

  /**
   * Emite o demonstrativo de Funcao/subfuncao
   */
  private function emitirDemostrativoDespesaPorFuncaoSubfuncao() {

    if (!$this->exibirRelatorio(self::EMITIR_DESPESA_FUNCAO_SUBFUNCAO)) {
      return false;
    }

    $oDadosDemonstrativo = $this->getDadosDespesasPorFuncaoSubfuncao();

    $this->oPdf->Cell(120, 3, "DESPESAS POR FUN��O/SUBFUN��O", 'TBR', 0, 'C');
    $this->oPdf->Cell(70, 3, "At� o Bimestre", 'TBL', 1, 'C');
    $this->oPdf->Cell(120, 3, "    Despesas Empenhadas", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($oDadosDemonstrativo->nEmpenhadas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Despesas Liquidadas", 'BR', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($oDadosDemonstrativo->nLiquidadas,'f'), 'BL', 1, 'R');
    $this->oPdf->ln();

  }

  /**
   * Emite os valores da Receita Corrente L�quida
   */
  private function emitirReceitaCorrenteLiquida() {

    if (!$this->exibirRelatorio(self::EMITIR_RECEITA_CORRENTE_LIQUIDA)) {
      return false;
    }

    $this->oPdf->Cell(120, 3, "RECEITA CORRENTE L�QUIDA - RCL", 'TBR', 0, 'C');
    $this->oPdf->Cell(70, 3, "At� o Bimestre", 'TBL', 1, 'C');
    $this->oPdf->Cell(120, 3, "    Receita Corrente L�quida", 'BR', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($this->getValorReceitaCorrenteLiquida(),'f'), 'BL', 1, 'R');
    $this->oPdf->ln();

  }

  /**
   * Retorna o valor da Receita Corrente L�quida (RCL)
   *
   * @TODO Refatorar busca pelas institui��es
   */
  private function getValorReceitaCorrenteLiquida() {

    if (!$this->lRclCalculada) {

      $sTodasInstit = null;
      $rsInstit     = db_query("select codigo from db_config");
      if (!$rsInstit) {
        throw new Exception("Ocorreu um erro ao buscar as institui��es cadastradas.");
      }
      for ($xinstit = 0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {

        $codigo = db_utils::fieldsMemory($rsInstit, $xinstit)->codigo;
        $sTodasInstit .= $codigo . ($xinstit == pg_num_rows($rsInstit) - 1 ? "" : ",");
      }

      duplicaReceitaaCorrenteLiquida($this->iAnoUsu, 81);

      $nTotalRcl = calcula_rcl2($this->iAnoUsu, $this->getDataInicial()->getDate(), $this->getDataFinal()->getDate(), $sTodasInstit, false, 81);
      /**
       * Calculamos os valores do ano anterior da RCL
       */
      $iAnoAnterior = $this->iAnoUsu - 1;
      $nTotalRcl    += calcula_rcl2($iAnoAnterior, "{$iAnoAnterior}-01-01", "{$iAnoAnterior}-12-31", $sTodasInstit, false, 81, $this->getDataFinal()->getDate());

      $this->nValorReceitaCorrenteLiquida =  $nTotalRcl;
      $this->lRclCalculada = true;
    }
    return $this->nValorReceitaCorrenteLiquida;
  }

  /**
   * Retorna os dados do demonstrativo RPPS
   *
   * @return stdClass
   */
  private function getDadosRegimeDePrevidencia() {

    $oAnexo = AnexoIV::getInstance($this->iAnoUsu, $this->oPeriodo);
    return $oAnexo->getDadosSimplificado();
  }

  /**
   * Emite dos dados do demonstrativo do Regime de previdencia
   */
  private function emitirRegimeDePrevidencia() {

    if (!$this->exibirRelatorio(self::EMITIR_DESPESAS_RECEITAS_RPPS)) {
      return false;
    }

    $oDadosRegimePrevidencia = $this->getDadosRegimeDePrevidencia();
    $sDescricao = 'Despesas Previdenci�rias Liquidadas(V)';
    $nValor = $oDadosRegimePrevidencia->nDespesasLiquidadas;
    if ($this->getPeriodo()->getCodigo() == self::CODIGO_SEXTO_BIMESTRE) {

      $sDescricao = 'Despesas Previdenci�rias Empenhadas(V)';
      $nValor = $oDadosRegimePrevidencia->nDespesasEmpenhadas;
    }

    $this->oPdf->Cell(120, 3, "RECEITAS E DESPESAS DOS REGIMES DE PREVID�NCIA", 'TBR', 0, 'C');
    $this->oPdf->Cell(70, 3, "At� o Bimestre", 'TBL', 1, 'C');

    $this->oPdf->Cell(120, 3, "Regime Geral de Previd�ncia Social", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, "-", 'L', 1, 'C');
    $this->oPdf->Cell(120, 3, "    Receitas Previdenci�rias Realizadas(I)", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar('0.0','f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Despesas Previdenci�rias Liquidadas(II)", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar('0.0','f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Resultado Previdenci�rio (III) = (I - II)", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar('0.0','f'), 'L', 1, 'R');

    $this->oPdf->Cell(120, 3, "Regime Pr�prio de Previd�ncia dos Servidores", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, "-", 'L', 1, 'C');
    $this->oPdf->Cell(120, 3, "    Receitas Previdenci�rias Realizadas(IV)", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($oDadosRegimePrevidencia->nReceitasRealizadas,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    {$sDescricao}", 'R', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar($nValor,'f'), 'L', 1, 'R');
    $this->oPdf->Cell(120, 3, "    Resultado Previdenci�rio (VI) = (IV - V)", 'BR', 0, 'L');
    $this->oPdf->Cell(70, 3, db_formatar(($oDadosRegimePrevidencia->nReceitasRealizadas - $nValor),'f'), 'BL', 1, 'R');

    $this->oPdf->ln();

  }

  /**
   * Emite os dados do demonstrativo do Resultado Nominal Prim�rio
   */
  private function emitirResultadosNominalPrimario() {

    if (!$this->exibirRelatorio(self::EMITIR_RESULTADO_NOMINAL_PRIMARIO)) {
      return false;
    }

    $oDadosResultadoPrimario = $this->getDadosResultadoPrimario();
    $oDadosResultadoNominal  = $this->getDadosResultadoNominal();

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(100, 12, "RESULTADOS NOMINAL E PRIM�RIO", 'TBR', 'C');
    $this->oPdf->MultiCell(30, 3, "Meta Fixada no\nAnexo de Metas\nFiscais da LDO\n(a)", 'TBL', 'C');
    $this->oPdf->MultiCell(30, 3, "\nResultado Apurado\nAt� o Bimestre\n(b)", 'TBLR', 'C');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell(30, 3, "\n% em Rela��o � Meta\n(b/a)\n ", 'TBL', 'C');
    $this->oPdf->Cell(100, 3, 'Resultado Nominal' , 'R', 0, 'L');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoNominal->nMetaNominal, 'f') , 'L', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoNominal->nTotalNominal, 'f') , 'L', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoNominal->nPercentualNominal, 'f') , 'L', 1, 'R');
    $this->oPdf->Cell(100, 3, 'Resultado Prim�rio' , 'BR', 0, 'L');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoPrimario->nMetaFixada, 'f') , 'BR', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoPrimario->nResultadoApuradoAteBimestre, 'f') , 'BR', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDadosResultadoPrimario->nPercentualPrimario, 'f') , 'BL', 1, 'R');
    $this->oPdf->ln();
  }

  /** ch
   * Retorna os dados do demonstrativo de Resultado Prim�rio
   */
  private function getDadosResultadoPrimario() {

    $oAnexoVIResultadoPrimario = new AnexoVIResultadoPrimario($this->iAnoUsu, AnexoVIResultadoPrimario::CODIGO_RELATORIO, $this->iCodigoPeriodo);
    $oAnexoVIResultadoPrimario->setInstituicoes($this->sInstituicoes);
    $oDadosResultadoPrimario = $oAnexoVIResultadoPrimario->getDadosSimplificado();
    $oDadosResultadoPrimario->nPercentualPrimario = 0;
    if ($oDadosResultadoPrimario->nMetaFixada > 0) {

      $oDadosResultadoPrimario->nPercentualPrimario = round( ( ($oDadosResultadoPrimario->nResultadoApuradoAteBimestre /
          $oDadosResultadoPrimario->nMetaFixada) * 100 ) , 2 );
    }
    return $oDadosResultadoPrimario;
  }

  /**
   * Retorna os dados do resultado nomimal
   */
  private function getDadosResultadoNominal() {

    $oDadosResultado = new stdClass();
    if ($this->iAnoUsu <= 2016) {

      $arqinclude = true;
      /**
       * Carregamos os proprio documento do relatorio, pois nao temos uma classe para processamento do mesmo
       */
      $iCodigoPeriodo = $this->iCodigoPeriodo;
      $periodo        = $this->iCodigoPeriodo;
      $anousu         = $this->iAnoUsu;
      $dt_ini         = $anousu.'-01-01';
      $munic          = '';
      $dt_fin         = $this->getDataFinal()->getDate();
      require_once(modification("con2_lrfnominal002_2010.php"));
      /**
       * Variaveis da executacao do require o relatorio.....
       */

      $nTotalBimestre = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );
      $nTotalAnterior = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);
      $nMetaNomimal   = $aLinhaRelatorio[7]->valor;
      $nTotalNominal  = $nTotalBimestre - $nTotalAnterior;


      $oDadosResultado->nMetaNominal       = $nMetaNomimal;
      $oDadosResultado->nTotalNominal      = $nTotalNominal;
      $oDadosResultado->nPercentualNominal = 0;

      if (abs($nMetaNomimal) > 0) {
        $oDadosResultado->nPercentualNominal = round(  ( ($nTotalNominal / $nMetaNomimal) *100 ), 2);
      }
    } else {

      $oAnexoV = new AnexoV($this->iAnoUsu, AnexoV::CODIGO_RELATORIO, $this->iCodigoPeriodo);
      $aInstituicoesSelecionadas = explode(',', $this->sListaInstit );

      /*
       * Quando selecionado a op��o Resultado Nominal/Prim�rio no anexo XIV, a intitui��o RPPS deve ser
       * desconsiderada ao calcular o Resultado Nominal
       */
      foreach ($aInstituicoesSelecionadas as $key => $value) {

        $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($value);
        $aInstituicoes[] = 0;

        if ($oInstituicao->getTipo() != \Instituicao::TIPO_AUTARQUIA_RPPS) {
          $aInstituicoes[] = $value;
        }
      }

      $oAnexoV->setInstituicoes(implode(',', $aInstituicoes));

      $oDadosResultado = $oAnexoV->getDadosSimplificado();
    }
    return $oDadosResultado;
  }

  /**
   * Escreve os dados do demonstrativo de restos a pagar
   */
  private function emitirRestosPagar() {

    if (!$this->exibirRelatorio(self::EMITIR_RESTOS_A_PAGAR)) {
      return false;
    }

    $oDadosResto = $this->getDadosRestosPagar();
    if ($this->iAnoUsu >= 2017 )  {
      $this->imprimirRestosPagar($oDadosResto);
    } else {

     /**
      * ******************************************************
      *         Impress�o dos dados Referentes ao ano <= 2016
      * ******************************************************
      */
      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->MultiCell(70, 6, "RESTOS A PAGAR POR PODER E MINIST�RIO P�BLICO", 'TBR', 'C');
      $this->oPdf->MultiCell(30, 6, "Inscri��o", 'TBL', 'C');
      $this->oPdf->MultiCell(30, 3, "Cancelamento\nAt� o Bimestre", 'TBLR', 'C');
      $this->oPdf->MultiCell(30, 3, "Pagamento\nAt� o Bimestre", 'TBLR', 'C');
      $this->oPdf->setAutoNewLineMulticell(true);
      $this->oPdf->MultiCell(30, 3, "Saldo\na Pagar", 'TBL', 'C');

      $this->oPdf->cell(70, 3, "RESTOS A PAGAR PROCESSADOS", 'R', 'C');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalProcessado->nTotalInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalProcessado->nTotalCancelado, 'f'), 'L',0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalProcessado->nTotalPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalProcessado->nTotalPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "    Poder Executivo", 'R', 0, 'L');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalProcessadoInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalProcessadoCancelado, 'f'), 'L', 0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalProcessadoPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalProcessadoPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "    Poder Legislativo", 'R', 0, 'L');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalProcessadoInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalProcessadoCancelado, 'f'), 'L',0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalProcessadoPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalProcessadoPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "RESTOS A PAGAR N�O PROCESSADOS", 'R', 'C');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalNaoProcessado->nTotalInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalNaoProcessado->nTotalCancelado, 'f'), 'L',0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalNaoProcessado->nTotalPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalNaoProcessado->nTotalPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "    Poder Executivo", 'R', 0, 'L');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalNaoProcessadoInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalNaoProcessadoCancelado, 'f'), 'L', 0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalNaoProcessadoPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oExecutivo->nTotalNaoProcessadoPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "    Poder Legislativo", 'R', 0, 'L');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalNaoProcessadoInscrito, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalNaoProcessadoCancelado, 'f'), 'L',0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalNaoProcessadoPago, 'f'), 'L', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oLegislativo->nTotalNaoProcessadoPagar, 'f'), 'L', 1, 'R');

      $this->oPdf->cell(70, 3, "TOTAL", 'RTB', 0, 'l');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalGeral->nInscrito, 'f'), 'TBL', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalGeral->nCancelado, 'f'), 'TBL',0,  'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalGeral->nPago, 'f'), 'TBL', 0, 'R');
      $this->oPdf->cell(30, 3, db_formatar($oDadosResto->oTotalGeral->nPagar, 'f'), 'TBL', 1, 'R');
      $this->oPdf->ln();
    }

  }

  /**
   * Restos a pagar por poder
   */
  private function getDadosRestosPagar() {

    if ($this->iAnoUsu >= 2017 )  {

      $aCodigoInstituicoes = explode(',', $this->sInstituicoes);

      $oAnexoVII = new AnexoVII();
      $oAnexoVII->setAno($this->iAnoUsu);
      $oAnexoVII->setPeriodo($this->oPeriodo);
      foreach ($aCodigoInstituicoes as $iCodigo) {
        $oAnexoVII->adicionarInstituicao(InstituicaoRepository::getInstituicaoByCodigo($iCodigo));
      }

      $oDados = $oAnexoVII->getDadosSimplificado();

      return $oDados;
    }

    /**
     * ******************************************************
     *         Busca dos dados Referentes ao ano <= 2016
     * ******************************************************
     */
    $arqinclude = true;
    /**
     * Carregamos os proprio documento do relatorio, pois nao temos uma classe para processamento do mesmo
     */
    $iCodigoPeriodo = $this->iCodigoPeriodo;
    $periodo        = $this->iCodigoPeriodo;
    $anousu         = $this->iAnoUsu;
    $dt_ini         = $anousu.'-01-01';
    $munic          = '';
    $dt_fin         = $this->getDataFinal()->getDate();
    $db_filtro      = " e60_instit in ({$this->sInstituicoes})";
    require_once(modification('con2_lrfdemonstrativorp002_2010.php'));

    $tot_restos_pc_insc_ant_exec  = 0;
    $tot_restos_pc_inscritos_exec = 0;
    $tot_restos_pc_cancelados_exec = 0;
    $tot_restos_pc_pagos_exec = 0;
    $tot_restos_pc_saldo_exec = 0;
    $tot_restos_naopc_insc_ant_exec = 0;
    $tot_restos_naopc_inscritos_exec = 0;
    $tot_restos_naopc_cancelados_exec = 0;
    $tot_restos_naopc_pagos_exec = 0;
    $tot_restos_naopc_saldo_exec = 0;

    $tot_restos_pc_insc_ant_legal      = 0;
    $tot_restos_pc_inscritos_legal     = 0;
    $tot_restos_pc_cancelados_legal    = 0;
    $tot_restos_pc_pagos_legal         = 0;
    $tot_restos_pc_saldo_legal         = 0;
    $tot_restos_naopc_insc_ant_legal   = 0;
    $tot_restos_naopc_inscritos_legal  = 0;
    $tot_restos_naopc_cancelados_legal = 0;
    $tot_restos_naopc_pagos_legal      = 0;
    $tot_restos_naopc_saldo_legal      = 0;


    $aArrays = array($aTotInstit , $aTotInstitIntra);

    foreach ($aArrays as $aArrayValor) {

      foreach ($aArrayValor as $iCodigoInstituicao => $aValor) {

        $oInstituicao    = InstituicaoRepository::getInstituicaoByCodigo($iCodigoInstituicao);
        $tipoInstituicao = $oInstituicao->getTipo();
        // Usado no simplificado dos RESTOS A PAGAR /////////////////////////////////////////////////////////////////////////////////
        if ($tipoInstituicao == 1 || $tipoInstituicao != 2) {    // Totais do PODER EXECUTIVO e RPPS

          $tot_restos_pc_insc_ant_exec      += abs($aValor[0]);
          $tot_restos_pc_inscritos_exec     += abs($aValor[1]);
          $tot_restos_pc_cancelados_exec    += abs($aValor[2]);
          $tot_restos_pc_pagos_exec         += abs($aValor[3]);
          $tot_restos_pc_saldo_exec         += abs($aValor[4]);
          $tot_restos_naopc_insc_ant_exec   += abs($aValor[5]);
          $tot_restos_naopc_inscritos_exec  += abs($aValor[6]);
          $tot_restos_naopc_cancelados_exec += abs($aValor[7]);
          $tot_restos_naopc_pagos_exec      += abs($aValor[8]);
          $tot_restos_naopc_saldo_exec      += abs($aValor[9]);
        }

        if ($tipoInstituicao == 2) {    // Totais do PODER LEGISLATIVO

          $tot_restos_pc_insc_ant_legal      += abs($aValor[0]);
          $tot_restos_pc_inscritos_legal     += abs($aValor[1]);
          $tot_restos_pc_cancelados_legal    += abs($aValor[2]);
          $tot_restos_pc_pagos_legal         += abs($aValor[3]);
          $tot_restos_pc_saldo_legal         += abs($aValor[4]);
          $tot_restos_naopc_insc_ant_legal   += abs($aValor[5]);
          $tot_restos_naopc_inscritos_legal  += abs($aValor[6]);
          $tot_restos_naopc_cancelados_legal += abs($aValor[7]);
          $tot_restos_naopc_pagos_legal      += abs($aValor[8]);
          $tot_restos_naopc_saldo_legal      += abs($aValor[9]);
        }

      }
    }

    // Executivo ##
    $oDados                                           = new stdClass();
    $oDados->oExecutivo                               = new stdClass();
    $oDados->oExecutivo->nTotalProcessadoInscrito     = $tot_restos_pc_insc_ant_exec + $tot_restos_pc_inscritos_exec;
    $oDados->oExecutivo->nTotalProcessadoCancelado    = $tot_restos_pc_cancelados_exec;
    $oDados->oExecutivo->nTotalProcessadoPago         = $tot_restos_pc_pagos_exec;
    $oDados->oExecutivo->nTotalProcessadoPagar        = $tot_restos_pc_saldo_exec;
    // N�o processado
    $oDados->oExecutivo->nTotalNaoProcessadoInscrito  = $tot_restos_naopc_insc_ant_exec + $tot_restos_naopc_inscritos_exec;
    $oDados->oExecutivo->nTotalNaoProcessadoCancelado = $tot_restos_naopc_cancelados_exec;
    $oDados->oExecutivo->nTotalNaoProcessadoPago      = $tot_restos_naopc_pagos_exec;
    $oDados->oExecutivo->nTotalNaoProcessadoPagar     = $tot_restos_naopc_saldo_exec;

    // Legislativo ##
    $oDados->oLegislativo                               = new stdClass();
    $oDados->oLegislativo->nTotalProcessadoInscrito     = $tot_restos_pc_insc_ant_legal+$tot_restos_pc_inscritos_legal;
    $oDados->oLegislativo->nTotalProcessadoPagar        = $tot_restos_pc_saldo_legal;
    $oDados->oLegislativo->nTotalProcessadoCancelado    = $tot_restos_pc_cancelados_legal;
    $oDados->oLegislativo->nTotalProcessadoPago         = $tot_restos_pc_pagos_legal;
    // N�o processado
    $oDados->oLegislativo->nTotalNaoProcessadoInscrito  = $tot_restos_naopc_insc_ant_legal + $tot_restos_naopc_inscritos_legal;
    $oDados->oLegislativo->nTotalNaoProcessadoCancelado = $tot_restos_naopc_cancelados_legal;
    $oDados->oLegislativo->nTotalNaoProcessadoPago      = $tot_restos_naopc_pagos_legal;
    $oDados->oLegislativo->nTotalNaoProcessadoPagar     = $tot_restos_naopc_saldo_legal;

    // Total processado ##
    $oDados->oTotalProcessado                  = new stdClass;
    $oDados->oTotalProcessado->nTotalInscrito  = $oDados->oExecutivo->nTotalProcessadoInscrito + $oDados->oLegislativo->nTotalProcessadoInscrito;
    $oDados->oTotalProcessado->nTotalCancelado = $oDados->oExecutivo->nTotalProcessadoCancelado + $oDados->oLegislativo->nTotalProcessadoCancelado;
    $oDados->oTotalProcessado->nTotalPago      = $oDados->oExecutivo->nTotalProcessadoPago + $oDados->oLegislativo->nTotalProcessadoPago;
    $oDados->oTotalProcessado->nTotalPagar     = $oDados->oExecutivo->nTotalProcessadoPagar + $oDados->oLegislativo->nTotalProcessadoPagar;

    // Total n�o processado ##
    $oDados->oTotalNaoProcessado                  = new stdClass;
    $oDados->oTotalNaoProcessado->nTotalInscrito  = $oDados->oExecutivo->nTotalNaoProcessadoInscrito + $oDados->oLegislativo->nTotalNaoProcessadoInscrito;
    $oDados->oTotalNaoProcessado->nTotalCancelado = $oDados->oExecutivo->nTotalNaoProcessadoCancelado + $oDados->oLegislativo->nTotalNaoProcessadoCancelado;
    $oDados->oTotalNaoProcessado->nTotalPago      = $oDados->oExecutivo->nTotalNaoProcessadoPago + $oDados->oLegislativo->nTotalNaoProcessadoPago;
    $oDados->oTotalNaoProcessado->nTotalPagar     = $oDados->oExecutivo->nTotalNaoProcessadoPagar + $oDados->oLegislativo->nTotalNaoProcessadoPagar;

    $oDados->oTotalGeral             = new stdClass();
    $oDados->oTotalGeral->nInscrito  = $oDados->oTotalNaoProcessado->nTotalInscrito + $oDados->oTotalProcessado->nTotalInscrito;
    $oDados->oTotalGeral->nCancelado = $oDados->oTotalNaoProcessado->nTotalCancelado + $oDados->oTotalProcessado->nTotalCancelado;
    $oDados->oTotalGeral->nPago      = $oDados->oTotalNaoProcessado->nTotalPago + $oDados->oTotalProcessado->nTotalPago;
    $oDados->oTotalGeral->nPagar     = $oDados->oTotalNaoProcessado->nTotalPagar + $oDados->oTotalProcessado->nTotalPagar;

    return $oDados;
  }

  /**
   * Retorna os dados do anexo VIII
   * @return stdClass
   */
  private function getDadosDespesasComEnsino() {

    $oAnexoMDE    = FactoryAnexoVIII::getInstance($this->iAnoUsu, $this->oPeriodo);
    $oInstituicao = InstituicaoRepository::getInstituicaoPrefeitura();
    $oAnexoMDE->setInstituicoes($oInstituicao->getCodigo());
    return $oAnexoMDE->getDadosSimplificado();
  }

  /**
   * Realiza a impressao dos dados de MDE
   */
  private function emitirDespesasComEnsino() {

    if (!$this->exibirRelatorio(self::EMITIR_DESPESAS_MDE)) {
      return false;
    }

    $oDados = $this->getDadosDespesasComEnsino();
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(90, 6, "DESPESAS COM MANUTEN��O E DESENVOLVIMENTO DO ENSINO", 'TBR', 'C');
    $this->oPdf->MultiCell(30, 6, "Valor Apurado at� o Bimestre", 'TBL', 'C');
    $this->oPdf->Cell(70, 3, "Limite Constitucionais Anuais", 'TBL', 1, 'C');
    $this->oPdf->SetX(130);
    $this->oPdf->Cell(35, 3, "% M�nimo a Aplicar no Exerc�cio", 'BLR', 0, 'C');
    $this->oPdf->Cell(35, 3, "% Aplicado At� o Bimestre", 'BL', 1, 'C');

    $this->oPdf->MultiCell(90, 3, "M�nimo Anual de 25% das Receitas de Impostos em  MDE", 'R', 'L');
    $this->oPdf->MultiCell(30, 3, db_formatar($oDados->nMinimoAtualMDEAteBimestre, 'f'), 'L', 'R');
    $this->oPdf->MultiCell(35, 3, db_formatar(25, 'f'), 'L', 'R');
    $this->oPdf->MultiCell(35, 3, db_formatar($oDados->nPercentualAplicadoComMDE, 'f'), 'L', 'R');
    $this->oPdf->Ln();

    $this->oPdf->MultiCell(90, 3, "M�nimo Anual de 60% do FUNDEB na Remunera��o do Magist�rio com Educa��o Infantil e Ensino Fundamental", 'BR', 'L');
    $this->oPdf->MultiCell(30, 6, db_formatar($oDados->nMinimoAtualFUNDEBAteBimestre, 'f'), 'BL', 'R');
    $this->oPdf->MultiCell(35, 6, db_formatar(60, 'f'), 'BL', 'R');
    $this->oPdf->MultiCell(35, 6, db_formatar($oDados->nPercentualAplicadoComFUNDEB, 'f'), 'BL', 'R');
    $this->oPdf->ln();
    $this->oPdf->ln(3);
    $this->oPdf->setAutoNewLineMulticell(true);

  }

  /**
   * Emite o quadro das opera��es de cr�dito e despesas de capital
   */
  private function emitirOperacoesCreditoDespesasCapital() {

    if (!$this->exibirRelatorio(self::EMITIR_OPERACAO_DE_CREDITO)) {
      return false;
    }

    $oRelatorio = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_IX, $this->iAnoUsu, new Periodo($this->iCodigoPeriodo));
    $oRelatorio->setInstituicoes($this->sInstituicoes);
    $aDados = $oRelatorio->getDadosSimplificado();

    $this->oPdf->cell(90, 3, "RECEITAS DE OPERA��ES DE CR�DITO E DESPESAS DE CAPITAL", "TBR", 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth()/2, 3, "Valor Apurado at� o Bimestre", "TBR", 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 3, "Saldo n�o Realizado", "TB", 1, 'C');

    $this->oPdf->cell(90, 3, $aDados[0]->sDescricao, "R", 0);
    $this->oPdf->cell($this->oPdf->getAvailWidth()/2, 3, trim(db_formatar($aDados[0]->nReceitasRealizadas, 'f')), "R", 0, 'R');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 3, trim(db_formatar($aDados[0]->nSaldoNaoRealizado, 'f')), "", 1, 'R');

    $this->oPdf->cell(90, 3, $aDados[1]->sDescricao, "BR", 0);
    $this->oPdf->cell($this->oPdf->getAvailWidth()/2, 3, trim(db_formatar($aDados[1]->nDespesasEmpenhadas, 'f')), "BR", 0, 'R');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 3, trim(db_formatar($aDados[1]->nSaldoNaoExecutado, 'f')), "B", 1, 'R');
    $this->oPdf->ln();
  }

  /**
   * Emite os dados do Demonstrativo das Despesas com Sa�de
   */
  private function emitirImpostosReceitasSaude() {

    if (!$this->exibirRelatorio(self::EMITIR_DESPESAS_SAUDE)) {
      return false;
    }

    $oDados = $this->getDadosImpostosReceitasSaude();

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(90, 6, "DESPESAS COM A��ES E SERVI�OS P�BLICOS DE SA�DE", 'TBR', 'C');
    $this->oPdf->MultiCell(30, 6, "Valor Apurado at� o Bimestre", 'TBL', 'C');
    $this->oPdf->Cell(70, 3, "Limite Constitucionais Anuais", 'TBL', 1, 'C');
    $this->oPdf->SetX(130);
    $this->oPdf->Cell(35, 3, "% M�nimo a Aplicar no Exerc�cio", 'BLR', 0, 'C');
    $this->oPdf->Cell(35, 3, "% Aplicado At� o Bimestre", 'BL', 1, 'C');
    $this->oPdf->Cell(90, 3, "Despesas com A��es e Servi�os P�blicos de Sa�de executadas com recursos de impostos", 'TBR', 0,  'L');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->nTotalDespesasSaudeComImpostos, 'f'), 'BL', 0, 'R');
    $this->oPdf->Cell(35, 3, db_formatar(15, 'f'), 'BL', 0, 'R');
    $this->oPdf->Cell(35, 3, db_formatar($oDados->nPercentualDespesasSaudeComImpostos, 'f'), 'BL', 1, 'R');
    $this->oPdf->Ln();
    $this->oPdf->setAutoNewLineMulticell(true);

  }

  /**
   * Retorna os dados do Demonstrativo das Despesas com Sa�de
   *
   * @return stdClass
   */
  private function getDadosImpostosReceitasSaude() {
    $oAnexoXII = new AnexoXIIDemonstrativoDasDespesasComSaude($this->iAnoUsu,
      AnexoXIIDemonstrativoDasDespesasComSaude::CODIGO_RELATORIO,
      $this->iCodigoPeriodo
    );
    $oAnexoXII->setInstituicoes($this->sInstituicoes);
    $oDadosSimplificado = $oAnexoXII->getDadosSimplificado();

    return $oDadosSimplificado;

  }

  /**
   * Emite Parcerias P�blico Privadas (PPP)
   */
  private function emiteDespesasDePPP() {

    if (!$this->exibirRelatorio(self::EMITIR_PPP)) {
      return false;
    }

    $nValorDespesasPPP = $this->getValorDespesasDePPP();

    $this->oPdf->Cell(140, 3, "DESPESAS DE CAR�TER CONTINUADO DERIVADAS DE PPP", 'TBR', 0, 'C');
    $this->oPdf->Cell(50, 3,  "Valor Apurado no Exerc�cio Corrente", 'TBL', 1, 'C');
    $this->oPdf->Cell(140, 3, "Total das Despesas / RCL (%)", 'BR', 0, 'L');
    $this->oPdf->Cell(50, 3,  db_formatar($nValorDespesasPPP, 'f'), 'BL', 1, 'R');
    $this->oPdf->Ln();

  }

  /**
   * Retorna o valor de Despesa de PPP
   * @return float
   */
  private function getValorDespesasDePPP() {

    $periodo     = $this->iCodigoPeriodo;
    $arqinclude  = true;
    $anousu      = $this->iAnoUsu;
    $lInResumido = true;
    require_once(modification("con2_lrfanexoxvii002_2010.php"));
    $nTotalRcl      = $this->getValorReceitaCorrenteLiquida();
    $nValorTotalPPP = ($aLinhasRelatorio[19]->valores[2] / $nTotalRcl) * 100;

    return $nValorTotalPPP;
  }

  /**
   * Escreve as assinaturas.
   */
  private function escreverAssinaturas() {

    $oAssinatura = new cl_assinatura();

    assinaturas($this->oPdf, $oAssinatura,'LRF');
    $this->oPdf->setAutoNewLineMulticell(true);
  }

  /**
   * Adiciona uma nova p�gina no relat�rio
   * @return void
   */
  private function adicionarPagina() {

    $this->oPdf->SetFontSize(5);

    if ($this->oPdf->getCurrentPage() > 0) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continua na p�gina " . ($this->oPdf->PageNo() + 1) . "/{nb}", 'T', 0, 'R');
    }
    $this->oPdf->addPage();
    if ($this->oPdf->getCurrentPage() != 1) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continua��o da p�gina " . ($this->oPdf->PageNo() - 1) . "/{nb}", 'B', 1, 'R');
    }

    $this->oPdf->setBold(true);
    $this->oPdf->Cell(100, 3, 'RREO - Anexo 14 (LRF, Art. 48)', 0, 0);
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 3, 'Em Reais', 0 , 1, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->SetFontSize(6);
    $this->oPdf->setBold(false);
  }

  private function emitirAlienacaoAtivosAplicacaoRecursos() {

    if (!$this->exibirRelatorio(self::EMITIR_ALIENACAO_ATIVOS)) {
      return false;
    }

    $oRelatorio = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_XI, $this->iAnoUsu, new Periodo($this->iCodigoPeriodo));
    $oRelatorio->setInstituicoes($this->sInstituicoes);
    $aDados = $oRelatorio->getDadosSimplificado();

    $this->oPdf->Cell(90, 3, 'RECEITA DA ALIENA��O DE ATIVOS E APLICA��O DOS RECURSOS', 'TBR', 0, 'C');
    $this->oPdf->Cell($this->oPdf->getAvailWidth() / 2, 3, 'Valor Apurado At� o Bimestre', 'TBR', 0, 'C');
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 3, 'Saldo a Realizar', 'TB', 1, 'C');

    $this->oPdf->Cell(90, 3, $aDados[0]->sDescricao, 'R', 0);
    $this->oPdf->Cell($this->oPdf->getAvailWidth() / 2, 3, trim(db_formatar($aDados[0]->nAteBimestre, 'f')), 'R', 0, 'R');
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 3, trim(db_formatar($aDados[0]->nSaldoRealizar, 'f')), '', 1, 'R');

    $this->oPdf->Cell(90, 3, $aDados[1]->sDescricao, 'BR', 0);
    $this->oPdf->Cell($this->oPdf->getAvailWidth() / 2, 3, trim(db_formatar($aDados[1]->nAteBimestre, 'f')), 'BR', 0, 'R');
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 3, trim(db_formatar($aDados[1]->nSaldoRealizar, 'f')), 'B', 1, 'R');

    $this->oPdf->Ln();
  }

  private function emitirProjecaoAtuarialRPPS() {

    if (!$this->exibirRelatorio(self::EMITIR_PROJECAO_ATUARIAL_RPPS)) {
      return false;
    }

    $oRelatorio = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_X, $this->iAnoUsu, $this->getPeriodo());
    $oRelatorio->setInstituicoes($this->sInstituicoes);
    $oDados = $oRelatorio->getDadosSimplificado();

    $this->oPdf->Cell(70, 6, "PROJE��O ATUARIAL DOS REGIMES DE PREVID�NCIA", 'RTB', 0, 'C');
    $this->oPdf->Cell(30, 6, "Exerc�cio", 'RTB', 0, 'C');
    $this->oPdf->Cell(30, 6, "10� Exerc�cio", 'RTB', 0, 'C');
    $this->oPdf->Cell(30, 6, "20� Exerc�cio", 'RTB', 0, 'C');
    $this->oPdf->Cell(30, 6, "35� Exerc�cio", 'TB' , 1, 'C');

    $this->oPdf->Cell(70, 3, "Regime Geral de Previd�ncia Social", 'R');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 0, 1, 'C');

    $this->oPdf->Cell(70, 3, "    Receitas Previdenci�rias (I)", 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 0, 1, 'R');

    $this->oPdf->Cell(70, 3, "    Despesas Previdenci�rias (II)", 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 0, 1, 'R');

    $this->oPdf->Cell(70, 3, "    Resultado Previdenci�rio (III) = (I - II)", 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar('0.0', 'f'), 0, 1, 'R');

    $this->oPdf->Cell(70, 3, "Regime Pr�prio de Previd�ncia dos Servidores", 'R');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 'R', 0, 'C');
    $this->oPdf->Cell(30, 3, "-", 0, 1, 'C');

    $this->oPdf->Cell(70, 3, "    Receitas Previdenci�rias (IV)", 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->receitasprevidenciarias->exercicio, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->receitasprevidenciarias->exercicio10, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->receitasprevidenciarias->exercicio20, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->receitasprevidenciarias->exercicio35, 'f'), 0, 1, 'R');

    $this->oPdf->Cell(70, 3, "    Despesas Previdenci�rias (V)", 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->despesasprevidenciarias->exercicio, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->despesasprevidenciarias->exercicio10, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->despesasprevidenciarias->exercicio20, 'f'), 'R', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->despesasprevidenciarias->exercicio35, 'f'), 0, 1, 'R');

    $this->oPdf->Cell(70, 3, "    Resultado Previdenci�rio (VI) = (IV - V)", 'RB');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->resultadoprevidenciario->exercicio, 'f'), 'RB', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->resultadoprevidenciario->exercicio10, 'f'), 'RB', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->resultadoprevidenciario->exercicio20, 'f'), 'RB', 0, 'R');
    $this->oPdf->Cell(30, 3, db_formatar($oDados->resultadoprevidenciario->exercicio35, 'f'), 'B', 1, 'R');
    $this->oPdf->Ln();

  }

  /**
   * Imprime o resumo do Anexo VII (Restos a pagar) com a estrutura dos dados de >= 2017
   * @param \stdClass[] $aStdDados
   */
  public function imprimirRestosPagar(array $aStdDados) {

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(70, 6, "RESTOS A PAGAR POR PODER E MINIST�RIO P�BLICO", 'TBR', 'C');
    $this->oPdf->MultiCell(30, 6, "Inscri��o", 'TBL', 'C');
    $this->oPdf->MultiCell(30, 3, "Cancelamento\nAt� o Bimestre", 'TBLR', 'C');
    $this->oPdf->MultiCell(30, 3, "Pagamento\nAt� o Bimestre", 'TBLR', 'C');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell(30, 3, "Saldo\na Pagar", 'TBL', 'C');

    $nTotalInscrito  = 0;
    $nTotalCancelado = 0;
    $nTotalPago      = 0;
    $nTotalAPagar    = 0;
    foreach ($aStdDados as $sIndice => $oStdLinha) {

      if ($sIndice == 'linhas') {
        continue;
      }

      if ($sIndice == 'rp-processado') {

        $this->oPdf->cell(70, 4, $oStdLinha->sDescricao, 'R', 0, 'l');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nProcessadoInscrito, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nProcessadoCancelado, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nProcessadoPago, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nProcessadoPagar, 'f'), 0, 1, 'R');

        foreach ($aStdDados['linhas'] as $oStdPoder) {

          $this->oPdf->cell(70, 4, '    ' . $oStdPoder->sDescricao, 'R', 0, 'l');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nProcessadoInscrito, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nProcessadoCancelado, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nProcessadoPago, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nProcessadoPagar, 'f'), 0, 1, 'R');
        }

        $nTotalInscrito  += $oStdLinha->nProcessadoInscrito;
        $nTotalCancelado += $oStdLinha->nProcessadoCancelado;
        $nTotalPago      += $oStdLinha->nProcessadoPago;
        $nTotalAPagar    += $oStdLinha->nProcessadoPagar;

      } else {

        $this->oPdf->cell(70, 4, $oStdLinha->sDescricao, 'R', 0, 'l');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nNaoProcessadoInscrito, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nNaoProcessadoCancelado, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nNaoProcessadoPago, 'f'), 'R', 0, 'R');
        $this->oPdf->cell(30, 4, db_formatar($oStdLinha->nNaoProcessadoPagar, 'f'), 0, 1, 'R');

        foreach ($aStdDados['linhas'] as $oStdPoder) {

          $this->oPdf->cell(70, 4, '    ' . $oStdPoder->sDescricao, 'R', 0, 'l');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nNaoProcessadoInscrito, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nNaoProcessadoCancelado, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nNaoProcessadoPago, 'f'), 'R', 0, 'R');
          $this->oPdf->cell(30, 4, db_formatar($oStdPoder->nNaoProcessadoPagar, 'f'), 0, 1, 'R');
        }

        $nTotalInscrito  += $oStdLinha->nNaoProcessadoInscrito;
        $nTotalCancelado += $oStdLinha->nNaoProcessadoCancelado;
        $nTotalPago      += $oStdLinha->nNaoProcessadoPago;
        $nTotalAPagar    += $oStdLinha->nNaoProcessadoPagar;
      }

    }
    $this->oPdf->cell(70, 4, 'TOTAL', 'TBR', 0, 'l');
    $this->oPdf->cell(30, 4, db_formatar($nTotalInscrito, 'f'), 'TBR', 0, 'R');
    $this->oPdf->cell(30, 4, db_formatar($nTotalCancelado, 'f'), 'TBR', 0, 'R');
    $this->oPdf->cell(30, 4, db_formatar($nTotalPago, 'f'), 'TBR', 0, 'R');
    $this->oPdf->cell(30, 4, db_formatar($nTotalAPagar, 'f'), 'TB', 1, 'R');
    $this->oPdf->ln(2);
  }
}
