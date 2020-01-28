<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;


class AnexoI extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal {

  /**
   * Código Padrão do Relatório
   * @var integer
   */
  const CODIGO_RELATORIO = 163;

  /**
   * @var \Instituicao[]
   */
  private $aInstituicoesReservaContigente = array();

  /**
   * @var \Instituicao[]
   */
  private $aInstituicoesReservaRPPS = array();


  /**
   * AnexoV constructor.
   *
   * @param int $iAnoUsu
   * @param int $iCodigoRelatorio
   * @param int $iCodigoPeriodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * Retorna um array contendo as linhas do relatório já processadas.
   * @return \stdClass[]
   */
  public function getLinhas() {

    if (count($this->aLinhasConsistencia) == 0) {
      $this->processar();
    }

    return $this->aLinhasConsistencia;
  }

  /**
   * Processa a busca e cálculo necessários para emissão do relatório
   */
  private function processar() {

    $aInstituicao = $this->getInstituicoes(true);

    foreach ($aInstituicao as $oInstituicao) {

      if (in_array($oInstituicao->getTipo(), array(\Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA , \Instituicao::TIPO_AUTARQUIA_RPPS))) {
        $this->aInstituicoesReservaRPPS[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
      } else {
        $this->aInstituicoesReservaContigente[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
      }
    }

    $this->getDados();
    $this->calcularSuplementacao();
    $this->calcularReservaContingente();
    $this->calcularReservaRPPS();

    $this->processaTotalizadores($this->aLinhasConsistencia);
    $aLinhasProcessar = array(1, 2, 64, 65, 66, 73, 75, 76, 90, 91, 99, 101);
    foreach ($aLinhasProcessar as $linha) {
      $this->processarFormulaDaLinha($linha);
    }
    $this->calcularSuperavitDeficit();
  }

  /**
   * Processa os valores para a instituição que são do tipo Reserva de Contingente
   */
  private function calcularReservaContingente() {

    $oLinhaContingencia = $this->aLinhasConsistencia[89];
    foreach ($this->aLinhasConsistencia[89]->colunas as $oStdColuna) {
      $this->aLinhasConsistencia[89]->{$oStdColuna->o115_nomecoluna} = 0;
    }
    if (count($this->aInstituicoesReservaContigente) > 0) {

      $sWhereDespesa      = " o58_instit in (" . implode(',', $this->aInstituicoesReservaContigente) . ")";
      $rsBalanceteDespesa = db_dotacaosaldo(8, 2, 2, true, $sWhereDespesa, $this->iAnoUsu, $this->getDataInicial()->getDate(), $this->getDataFinal()->getDate());

      $aColunasProcessar = $this->getColunasPorLinha($oLinhaContingencia);
      \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa, $oLinhaContingencia, $aColunasProcessar, \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);
    }

    /**
     * Soma o valor encontrado para a linha na linha totalizdora do quadro
     */
    foreach ($this->aLinhasConsistencia[89]->colunas as $oStdColuna) {
      $this->aLinhasConsistencia[80]->{$oStdColuna->o115_nomecoluna} += $this->aLinhasConsistencia[89]->{$oStdColuna->o115_nomecoluna};
    }

    $this->processarFormulaDaLinha(80);
  }


  /**
   * Processa os valores para a instituição que são do tipo RPPS
   */
  private function calcularReservaRPPS() {

    $iLinhaRPPS = 102;

    foreach ($this->aLinhasConsistencia[$iLinhaRPPS]->colunas as $oStdColuna) {
      $this->aLinhasConsistencia[$iLinhaRPPS]->{$oStdColuna->o115_nomecoluna} = 0;
    }

    $oLinhaRPPS = $this->aLinhasConsistencia[$iLinhaRPPS];
    if (count($this->aInstituicoesReservaRPPS) > 0) {

      $sWhereDespesa      = " o58_instit in (".implode(',', $this->aInstituicoesReservaRPPS).")";
      $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,$this->iAnoUsu, $this->getDataInicial()->getDate(), $this->getDataFinal()->getDate());

      $aColunasProcessar = $this->getColunasPorLinha($oLinhaRPPS);
      \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa, $oLinhaRPPS, $aColunasProcessar, \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);
    }
    foreach ($oLinhaRPPS->colunas as $i => $value) {
      $this->processaValorManualPorLinhaEColuna($iLinhaRPPS, $i);
    }
  }


  /**
   * Ajusta os valores referente as linhas de Créditos Adicionais / Suplementação
   * @throws \Exception
   */
  private function calcularSuplementacao() {

    $aWhereSuperavit = array(
      "o46_tiposup in (1008, 1003)",
      "o49_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
      "o46_instit in ({$this->getInstituicoes()})"
    );
    $oDaoOrcSuplem      = new \cl_orcsuplem();
    $sSqlBuscaSuperavit = $oDaoOrcSuplem->sql_query_suplementacoes(null, "coalesce(sum(o47_valor), 0) as total", null, implode(" and ", $aWhereSuperavit));
    $rsBuscaSuperavit   = db_query($sSqlBuscaSuperavit);
    if (!$rsBuscaSuperavit) {
      throw new \Exception("Ocorreu um erro na busca dos valores de suplementação da coluna SUPERAVIT.");
    }


    $aWhereCreditos = array(
      "o46_tiposup in (1012, 1013)",
      "o49_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
      "o46_instit in ({$this->getInstituicoes()})"
    );
    $sSqlBuscaCreditos = $oDaoOrcSuplem->sql_query_suplementacoes(null, "coalesce(sum(o47_valor), 0) as total", null, implode(" and ", $aWhereCreditos));
    $rsBuscaCreditos   = db_query($sSqlBuscaCreditos);
    if (!$rsBuscaCreditos) {
      throw new \Exception("Ocorreu um erro na busca dos valores de suplementação da coluna CRÉDITOS ADICIONAIS.");
    }

    $nValorSuperavit = \db_utils::fieldsMemory($rsBuscaSuperavit, 0)->total;
    $nValorCreditos  = \db_utils::fieldsMemory($rsBuscaCreditos, 0)->total;

    $this->aLinhasConsistencia[78]->prevatu   += $nValorSuperavit;
    $this->aLinhasConsistencia[78]->recatebim += $nValorSuperavit;
    $this->aLinhasConsistencia[79]->prevatu   += $nValorCreditos;
    $this->aLinhasConsistencia[79]->recatebim += $nValorCreditos;

  }

  // Funcao verifica se ouve superavit ou deficit
  protected function calcularSuperavitDeficit(){

    // linha 73 é do quadro de receitas
    // linha 99 é do quadro de despesas
    // linha 74 representa o deficit
    // linha 100 representa o superavit
    $this->aLinhasConsistencia[100]->dotini           = '-';
    $this->aLinhasConsistencia[100]->dotatu           = '-';
    $this->aLinhasConsistencia[100]->empenhado_nobim  = '-';
    $this->aLinhasConsistencia[100]->empenhado_atebim = '-';
    $this->aLinhasConsistencia[100]->liquidado_nobim  = '-';
    $this->aLinhasConsistencia[100]->liquidado_atebim = 0;
    $this->aLinhasConsistencia[100]->desppag          = '-';
    $this->aLinhasConsistencia[100]->rp_apagar        = '-';
    $this->aLinhasConsistencia[74]->previni           = '-';
    $this->aLinhasConsistencia[74]->prevatu           = '-';
    $this->aLinhasConsistencia[74]->recatebim         = 0;
    $this->aLinhasConsistencia[74]->recnobim          = '-';

    $this->aLinhasConsistencia[74]->recatebim = 0;
    $this->aLinhasConsistencia[100]->liquidado_atebim  = 0;
    /**
     * Déficit
     */
    $nCalculoSuperavitDeficit = abs(($this->aLinhasConsistencia[99]->liquidado_atebim - $this->aLinhasConsistencia[73]->recatebim));
    if($this->aLinhasConsistencia[73]->recatebim < $this->aLinhasConsistencia[99]->liquidado_atebim){

      $this->aLinhasConsistencia[74]->recatebim  = $nCalculoSuperavitDeficit;
      $this->aLinhasConsistencia[75]->recatebim += $nCalculoSuperavitDeficit;
    } else {
      $this->aLinhasConsistencia[100]->liquidado_atebim  = $nCalculoSuperavitDeficit;
      $this->aLinhasConsistencia[101]->liquidado_atebim += $nCalculoSuperavitDeficit;
    }
  }


  /**
   * Retorna os dados para Demonstrativo Simplificado do Relatório Resumido da Execução Orçamentária
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    $this->processar();

    $oDados                             = new \stdClass();
    $oDados->nPrevisaoInicial           = $this->aLinhasConsistencia[73]->previni;
    $oDados->nPrevisaoAtualizada        = $this->aLinhasConsistencia[73]->prevatu;
    $oDados->nReceitasRealizadas        = $this->aLinhasConsistencia[73]->recatebim;
    $oDados->nDeficitOrcamentario       = $this->aLinhasConsistencia[74]->recatebim;
    $oDados->nSaldoExerciciosAnteriores = $this->aLinhasConsistencia[76]->recatebim;
    $oDados->nDotacaoInicial            = $this->aLinhasConsistencia[101]->dotini;
    $oDados->nDotacaoAtualizada         = $this->aLinhasConsistencia[101]->dotatu;
    $oDados->nCreditoAdicional          = $oDados->nDotacaoAtualizada - $oDados->nDotacaoInicial;
    $oDados->nEmpenhadas                = $this->aLinhasConsistencia[101]->empenhado_atebim;
    $oDados->nLiquidadas                = $this->aLinhasConsistencia[99]->liquidado_atebim;
    $oDados->nPagas                     = $this->aLinhasConsistencia[101]->desppag;
    $oDados->nSuperavitOrcamentario     = $this->aLinhasConsistencia[100]->liquidado_atebim;

    return $oDados;
  }
}
