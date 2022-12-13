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
 * Classe abstrata com os metodos
 */
abstract class DiarioDisciplina implements iDiarioDisciplina {

  /**
   * Codigo sequencial do diario
   * @var integer
   */
  protected $iCodigoDiario;

  /**
   * Colecao de periodos que utilizando proporcionalidade
   * @var array
   */
  protected $aPeriodosCalcularProporcionalidade = array();

    /**
   * Guarda o total de faltas abonadas
   * @var integer|null
   */
  protected $iTotalFaltasAbonadas = null;

  /**
   * Caso o calculo da frequencia seja global, usaremos o $nPercentualGlobal
   * @var number:null
   */
  protected $nPercentualGlobal = null;


  /**
   * Resultado final da disciplina
   * @var AvaliacaoResultadoFinal
   */
  protected $oResultadoFinal = null;

  /**
   * Retorna o codigo sequencial do diario
   * @return integer
   */
  public function getCodigoDiario() {
    return $this->iCodigoDiario;
  }

  /**
   * Guarda uma instância do Amparo
   * Com plugin do DiárioProgressaoParcial instalado pode ser uma instancia de AmparoProgressao
   * @var AmparoDisciplina|null
   */
  protected $oAmparo = null;


  /**
   * Controla se o diario foi reclassificado por baixa frequência
   * @var bool
   */
  protected $lReclassificadoBaixaFrequencia;

  /**
   * Retorna os aproveitamentos do periodo, pela ordem sequencial
   *
   * @param $iOrdem
   * @return AvaliacaoAproveitamento
   */
  public function getAvaliacoesPorOrdem($iOrdem) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {
      if ($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia() == $iOrdem) {
        return $oAvaliacao;
      }
    }
  }

  /**
   * Retorna os aproveitamentos do periodo
   *
   * @param  integer $iPeriodo
   * @return AvaliacaoAproveitamento
   */
  public function getAproveitamentosDoPeriodo($iPeriodo) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {
      if ($oAvaliacao->getElementoAvaliacao()->getCodigo() == $iPeriodo) {
        return $oAvaliacao;
      }
    }
  }

  /**
   * Retorna os resultados da disciplina
   * @return AvaliacaoAproveitamento[];
   */
  public function getResultados() {

    $aResultados = array();
    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {

        $aResultados[] = $oAvaliacao;
      }
    }
    return $aResultados;
  }

  /**
   * Retorna o último Resultado que gera Resultado Final
   * @return ResultadoAvaliacao
   */
  protected function getUltimoResultadoFinal() {

    $oUltimoResultado = null;

    foreach ( $this->getResultados() as $oResultado ) {

      if ( !$oResultado->getElementoAvaliacao()->geraResultadoFinal() ) {
        continue;
      }

      if ( is_null($oUltimoResultado) || $oUltimoResultado->getOrdemSequencia() < $oResultado->getElementoAvaliacao()->getOrdemSequencia() ) {
        $oUltimoResultado = $oResultado->getElementoAvaliacao();
      }
    }
    return $oUltimoResultado;
  }

  /**
   * Retorna todos Resultados que geram Resultado Final
   * @return ResultadoAvaliacao[]
   */
  protected function getElementosGeramResultadoFinal() {

    $aResultados = array();

    foreach ( $this->getResultados() as $oResultado ) {

      if ( !$oResultado->getElementoAvaliacao()->geraResultadoFinal() ) {
        continue;
      }

      $aResultados[] = $oResultado->getElementoAvaliacao();
    }

    return $aResultados;
  }


  /**
   * Retorna o elemento que gera o resultado final do procedimento
   * @return AvaliacaoAproveitamento
   */
  public function getElementoResultadoFinal() {

    $oElementoAvaliacaoFinal = null;
    foreach ($this->getResultados() as $oResultado) {

      if ($oResultado->getElementoAvaliacao()->geraResultadoFinal()) {

       $oElementoAvaliacaoFinal = $oResultado;
       break;
      }
    }
    return $oElementoAvaliacaoFinal;
  }


  /**
   * Retorna o total de faltas do diario;
   * Os periodos que  são levadas em conta são apenas os periodos somados no periodo
   * que gera a resultado final.
   */
  public function getTotalFaltas() {

    $iTotalFaltas              = 0;
    $oElementoResultadoFinal   = $this->getElementoResultadoFinal();
    $aAvaliacoesAproveitamento = $this->getPeriodosAvaliacaoProporcionalidade();

    if ( empty($aAvaliacoesAproveitamento) ) {
      $aAvaliacoesAproveitamento = $this->getAvaliacoes();
    }

    if ($oElementoResultadoFinal != null && $oElementoResultadoFinal->getElementoAvaliacao() != null) {

      $oResultadoFinal = $oElementoResultadoFinal->getElementoAvaliacao();
      /**
       * Percorremos todos os elementos que compoe o calculo da falta.
       */
      foreach ($oResultadoFinal->getElementosCalculoFaltas() as $oElementoFalta) {

        foreach ($aAvaliacoesAproveitamento as $oAvaliacao) {
          if ($oAvaliacao->isAmparado()) {
            continue;
          }
          if ($oElementoFalta->getOrdemSequencia() == $oAvaliacao->getOrdemSequencia()) {
            $iTotalFaltas += $oAvaliacao->getNumeroFaltas();
          }
        }
      }
    }
    return $iTotalFaltas;
  }

  /**
   * Retorna o total de faltas do periodo de avaliação informado
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao
   * @return integer                              total de faltas
   */
  public function getTotalFaltasPorPeriodo (PeriodoAvaliacao $oPeriodoAvaliacao) {

    $iTotalFaltasPeriodo = 0;
    $aAvaliacoes         = $this->getAvaliacoes();

    if ($aAvaliacoes != null) {

      /**
       * Percorremos todos os elementos que compoe o calculo da falta.
       */
      foreach ($aAvaliacoes as $oElementoFalta) {

        if ($oElementoFalta->getElementoAvaliacao() instanceof AvaliacaoPeriodica) {

          if ($oPeriodoAvaliacao->getCodigo() == $oElementoFalta->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo()) {

            $iTotalFaltasPeriodo += $oElementoFalta->getNumeroFaltas();
          }
        }
      }
    }
    return $iTotalFaltasPeriodo;
  }

  /**
   * Retorna o percentual de presenca do aluno quando o calculo for individual
   * @param stdClass $oDadosFrequencia Dados do calculo individual
   * @return float
   */
  protected function calculoDeFrequenciaIndividual() {

    $iTotalDeAulas         = $this->getTotalDeAulasParaCalculo();
    $nTotalFaltasSemAbono  = $this->getTotalFaltas() - $this->getTotalFaltasAbonadas();
    $iTotalAulasPresentes  = $iTotalDeAulas - $nTotalFaltasSemAbono;
    $nPercentualIndividual = "";

    if ($iTotalDeAulas > 0) {
      $nPercentualIndividual = ($iTotalAulasPresentes * 100) / $iTotalDeAulas;
    }
    return $nPercentualIndividual;
  }

  /**
   * Retorna o percentual de presenca do aluno quando o calculo for Global
   * @return float
   */
  protected function calculoDeFrequenciaGlobal() {

    if ($this->nPercentualGlobal == null) {

      $iTotalAulas     = 0;
      $iTotalFaltas    = 0;
      $iFaltasAbonadas = 0;

      foreach ($this->oDiario->getDisciplinas() as $oDisciplinaDiario) {

        $iTotalAulas     += $oDisciplinaDiario->getTotalDeAulasParaCalculo();
        $iTotalFaltas    += $oDisciplinaDiario->getTotalFaltas();
        $iFaltasAbonadas += $oDisciplinaDiario->getTotalFaltasAbonadas();
      }

      $nTotalFaltasSemAbono    = $iTotalFaltas - $iFaltasAbonadas;
      $iTotalAulasPresentes    = $iTotalAulas  - $nTotalFaltasSemAbono;
      $this->nPercentualGlobal = 0;
      if ($iTotalAulas > 0) {
        $this->nPercentualGlobal = ($iTotalAulasPresentes * 100) / $iTotalAulas;
      }
    }

    return $this->nPercentualGlobal;
  }

  /**
   * Retorna o total de faltas abonadas da disciplina/regencia;
   * @return integer
   */
  public function getTotalFaltasAbonadas() {

    $this->iTotalFaltasAbonadas = 0;
    $aAvaliacao                 = $this->getPeriodosAvaliacaoProporcionalidade();

    if( empty($aAvaliacao) ) {
      $aAvaliacao = $this->getAvaliacoes();
    }

    foreach ($aAvaliacao as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {
        continue;
      }

      $this->iTotalFaltasAbonadas += $oAvaliacao->getFaltasAbonadas();
    }

    return $this->iTotalFaltasAbonadas;
  }

  /**
   * Retorna as faltas abonadas no período de avaliação
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return int
   */
  public function getTotalFaltasAbonadasPorPeriodo( PeriodoAvaliacao $oPeriodoAvaliacao ) {

    $iTotalFaltasAbonadas = 0;
    $aAvaliacao           = $this->getAvaliacoes();

    foreach ($aAvaliacao as $oElementoFalta) {

      if ($oElementoFalta->getElementoAvaliacao() instanceof AvaliacaoPeriodica) {

        if ($oPeriodoAvaliacao->getCodigo() == $oElementoFalta->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo()) {

          $iTotalFaltasAbonadas += $oElementoFalta->getFaltasAbonadas();
        }
      }
    }

    return $iTotalFaltasAbonadas;
  }

  /**
   * Verifica se o diário possui aproveitamento lançado para algum período da disciplina
   * @return boolean
   */
  public function temAproveitamentoLancado() {

    $lTemAproveitamentoLancado = false;

    foreach( $this->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

      if( $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento() != '' ) {

        $lTemAproveitamentoLancado = true;
        break;
      }
    }

    return $lTemAproveitamentoLancado;
  }

  /**
   * Retorna o total de aulas Para realizar calculos de frequencia.
   * Verifica os periodos que estão sendo utilizados para avaliação do aluno
   * Este metodo verifica se o aluno esta utilizando proporcionalidade
   * Neste metodo,nao sao contabilizas aulas nos periodos em que o aluno está amparado.
   * @return integer
   */
  public function getTotalDeAulasParaCalculo() {

    $iTotalDeAulasDadas  = 0;
    $aPeriodosAvaliacoes = $this->getAvaliacoes();

    $this->aPeriodosCalcularProporcionalidade = $this->getPeriodosAvaliacaoProporcionalidade();

    if( !empty($this->aPeriodosCalcularProporcionalidade) ) {

      $aPeriodosAvaliacoes = $this->aPeriodosCalcularProporcionalidade;

      foreach ($this->aPeriodosCalcularProporcionalidade as $oAvaliacaoAproveitamento ) {

        if ($oAvaliacaoAproveitamento->isResultado()) {

          $iTotalDeAulasDadas = $this->getRegencia()->getTotalDeAulas();
          break;
        }
        $iTotalDeAulasDadas += $this->getRegencia()->getTotalDeAulasNoPeriodo( $oAvaliacaoAproveitamento->getElementoAvaliacao()->getPeriodoAvaliacao() );
      }
    } else {
      $iTotalDeAulasDadas = $this->getRegencia()->getTotalDeAulas();
    }

    $aPeriodosComAmparo = array();

    foreach ($aPeriodosAvaliacoes as $oAvaliacao) {

      if ($oAvaliacao->isAmparado() && !$oAvaliacao->getElementoAvaliacao()->isResultado()) {
        $aPeriodosComAmparo[] = $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao();
      }
    }

    foreach($aPeriodosComAmparo as $oPeriodoAvaliacao) {
      $iTotalDeAulasDadas -= $this->getRegencia()->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
    }

    return $iTotalDeAulasDadas;
  }

  /**
   * Verifica se o aluno foi reclassificado por baixa frequencia na disciplina .
   * @return boolean true para reclassificado , false para não -reclassificado
   */
  public function reclassificadoPorBaixaFrequencia() {

    $this->lReclassificadoBaixaFrequencia = false;

    $oResultadoFinal = $this->getResultadoFinal();
    if( !empty( $oResultadoFinal ) ) {

      $oAprovacaoConselho = $oResultadoFinal->getFormaAprovacaoConselho();

      if( $oAprovacaoConselho instanceof AprovacaoConselho &&
          $oAprovacaoConselho->getFormaAprovacao() == AprovacaoConselho::RECLASSIFICACAO_BAIXA_FREQUENCIA ) {
        $this->lReclassificadoBaixaFrequencia = true;
      }
    }

    return $this->lReclassificadoBaixaFrequencia;
  }

    /**
   * Adiciona amparo para os períodos informados
   * @param  array   $aPeriodosAvaliacao
   * @param  object  $oTipoAmparo             Pode ser uma instancia de Justificativa ou Convencao
   * @param  boolean $lAproveitaCargaHoraria  Gera aproveitamento da Carga Horaria
   * @throws BusinessException quando nao informado justificativa do amparo
   * @return void
   */
  public function salvarAmparo (array $aPeriodosAvaliacao, $oTipoAmparo, $lAproveitaCargaHoraria) {

    $oAmparo = $this->getAmparo();

    /**
     * Valida o tipo de amparo
     */
    if ($oTipoAmparo instanceof Justificativa) {
      $oAmparo->setJustificativa($oTipoAmparo);
    } else if ($oTipoAmparo instanceof Convencao) {
      $oAmparo->setConvencao($oTipoAmparo);
    } else {
      throw new BusinessException("Não foi informado o tipo de justificativa");
    }

    foreach ($aPeriodosAvaliacao as $oAvaliacaoAproveitamento) {
      $oAmparo->adicionarPeriodo($oAvaliacaoAproveitamento);
    }

    $oAmparo->setAproveitaCargaHoraria($lAproveitaCargaHoraria);
    $oAmparo->salvar();

    $this->oAmparo = $oAmparo;
  }

  /**
   * Remove o amparo da disciplina
   * @return void
   */
  public function removerAmparo () {

    $oAmparo = $this->getAmparo();
    $oAmparo->excluir();
    $this->oAmparo = null;
  }


  /**
   * Retornar qual avaliacao depende do resultado do periodo $oElementoAvaliacao
   * @param IElementoAvaliacao $oElementoAvaliacao elemento de avaliacao
   * @return AvaliacaoAproveitamento
   */
  public function getAvaliacaoDependentesDoPeriodo(IElementoAvaliacao $oElementoAvaliacao) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if (!$oAvaliacao->getElementoAvaliacao()->isResultado() &&
          $oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado() != "") {

        if ($oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado()->getOrdemSequencia() == $oElementoAvaliacao->getOrdemSequencia()) {

          return $oAvaliacao;
          break;
        }
      }
    }
  }

}
