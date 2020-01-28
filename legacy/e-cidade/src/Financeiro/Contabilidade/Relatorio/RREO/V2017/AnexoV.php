<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoV as Emissao;

class AnexoV extends \RelatoriosLegaisBase {

  /**
   * C�digo Padr�o do Relat�rio
   * @var integer
   */
  const CODIGO_RELATORIO = 162;

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
   * Retorna um array contendo as linhas do relat�rio j� processadas.
   * @return \stdClass[]
   */
  public function getLinhas() {

    if (count($this->aLinhasConsistencia) == 0) {
      $this->processar();
    }
    return $this->aLinhasConsistencia;
  }

  /**
   * Processa a busca e c�lculo necess�rios para emiss�o do relat�rio
   */
  private function processar() {

    $this->getDados();

    /*
     * Processa o Balancete de Verifica��o para o exerc�cio do ano anterior ao selecionado para emiss�o
     */
    $iExercicioAnterior            = ($this->iAnoUsu - 1);
    $oDataInicialExercicioAnterior = new \DBDate("{$iExercicioAnterior}-01-01");
    $oDataFinalExercicioAnterior   = new \DBDate("{$iExercicioAnterior}-12-31");
    $this->processarBalanceteVerificacaoParaColunaPorData(0, $oDataInicialExercicioAnterior, $oDataFinalExercicioAnterior);
    $this->processarBalanceteReceitaParaColunaPorData(0, $oDataInicialExercicioAnterior, $oDataFinalExercicioAnterior);

    /**
     * Processa Balancete de Verifica��o para a coluna referente ao bimestre anterior
     * Quando o per�odo impresso for 1� bimestre o bimestre anterior ser� o ano anterior
     */
    if ($this->iCodigoPeriodo == 6) {

      $oDataInicialBimestreAnterior = $oDataInicialExercicioAnterior;
      $oDataFinalBimestreAnterior   = $oDataFinalExercicioAnterior;
    } else {

      $iMesBimestreAnterior = ($this->getPeriodo()->getMesFinal() - 2);
      $iUltimoDiaMes        = cal_days_in_month(CAL_GREGORIAN, $iMesBimestreAnterior, $this->getDataInicial()->getAno());
      $oDataInicialBimestreAnterior = $this->getDataInicial();
      $oDataFinalBimestreAnterior   = new \DBDate("{$this->getDataInicial()->getAno()}-{$iMesBimestreAnterior}-{$iUltimoDiaMes}");
    }

    $this->processarBalanceteVerificacaoParaColunaPorData(1, $oDataInicialBimestreAnterior, $oDataFinalBimestreAnterior);
    $this->processarBalanceteReceitaParaColunaPorData(1, $oDataInicialBimestreAnterior, $oDataFinalBimestreAnterior);

    /**
     * Recalcula os totalizadores
     */
    $this->processaTotalizadores($this->aLinhasConsistencia);
    $this->processarFormulaDaLinha(11);
    $this->arredondarValores();
  }



  /**
   * Processa o balancete de receita para as datas informadas no par�metro
   * @param integer $iColuna
   * @param \DBDate $oDataInicial
   * @param \DBDate $oDataFinal
   */
  private function processarBalanceteReceitaParaColunaPorData($iColuna, \DBDate $oDataInicial, \DBDate $oDataFinal) {

    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          "o70_instit in ({$this->getInstituicoes()})", $oDataInicial->getAno(),
                                          $oDataInicial->getDate(),
                                          $oDataFinal->getDate());

    foreach ($this->aLinhasProcessarReceita as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->getColunasPorLinha($oLinha, array($iColuna));
      $sNomeColunaLimpar = $aColunasProcessar[0]->nome;
      $oLinha->{$sNomeColunaLimpar} = 0;

      \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 \RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
      );
      $this->processaValorManualPorLinhaEColuna($oLinha->ordem, $iColuna);
    }
    $this->limparEstruturaBalanceteReceita();
  }

  /**
   * Retorna os dados para a emiss�o no Anexo XVIII (Relat�rio Resumido)
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    $this->processar();

    $oStdValorResumido = new \stdClass();
    $oStdValorResumido->nTotalNominal = $this->aLinhasConsistencia[11]->saldo_bimestre_anterior;
    $oStdValorResumido->nMetaNominal  = $this->aLinhasConsistencia[12]->valor_corrente;
    $oStdValorResumido->nPercentualNominal = 0;
    if (abs($oStdValorResumido->nMetaNominal) > 0) {
      $oStdValorResumido->nPercentualNominal = round((($oStdValorResumido->nTotalNominal / $oStdValorResumido->nMetaNominal) * 100 ), 2);
    }
    return $oStdValorResumido;
  }
}