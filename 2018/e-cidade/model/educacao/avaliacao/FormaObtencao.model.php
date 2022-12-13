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
 * Especializacao dos dados para calcular o resultado de uma Avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *         Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.20 $
 */
abstract class FormaObtencao {

  /**
   * array com os elementos da avaliacao
   * @var array iElementoAvaliacao
   */
  protected $aElementoAvaliacao;

  /**
   * Resultado de avaliacao que sera calculada
   * @var ResultadoAvaliacao
   */
  protected $oResultadoAvaliacao;

  /**
   * Valor do Aproveitamento no resultado
   * @var mixed
   */
  protected $mAproveitamento;


  protected $lCalculoNotaParcial = false;

  /**
   * Lista de Aproveitamentos que Foram Substituidos
   * @var []
   */
  protected $aListaAproveitamentosSubstituir = array();

  /**
   * Define qual resultado está sendo calculado
   * @param ResultadoAvaliacao $oResultadoAvaliacao
   */
  public function setResultadoAvaliacao(ResultadoAvaliacao $oResultadoAvaliacao) {
    $this->oResultadoAvaliacao = $oResultadoAvaliacao;
  }

  /**
   * Retorna qual resultado está sendo calculado
   * @return ResultadoAvaliacao
   */
  public function getResultadoAvaliacao() {
    return $this->oResultadoAvaliacao;
  }


  /**
   * Retorna os periodos de avaliacao utilizados para o calculo da forma de obtencao.
   *
   * @param AvaliacaoAproveitamento[] $aAproveitamentos
   * @return array
   */
  protected function getElementosParaCalculo( $aAproveitamentos, $iAno ) {

    $aNotasPeriodo              = array();
    $oPeriodoRecuperacao        = null;
    $oDiarioAvaliacaoDisciplina = $aAproveitamentos[0]->getDiarioAvaliacaoDisciplina();

    /* PLUGIN DIARIO_PROGRESSAO - chamada getElementosParaCalculoProgressaoParcial- NÃO APAGAR ESTE COMENTÁRIO*/

    if( !is_array( DBRegistry::get( $this->getResultadoAvaliacao()->getCodigo() ) ) ) {
      DBRegistry::add( $this->getResultadoAvaliacao()->getCodigo(), $this->getResultadoAvaliacao()->getElementosComposicaoResultado() );
    }

    $aElementos = DBRegistry::get( $this->getResultadoAvaliacao()->getCodigo() );

    /**
     * Verificamos qual o Elemento de avaliacao esta sendo utilizado na forma de obtencao
     */
    foreach ($aElementos as $oElementoAvaliacao) {

      $oPeriodoAvaliacao = $oElementoAvaliacao->getElementoAvaliacao();
      if ($oPeriodoAvaliacao->isResultado()
          && $oElementoAvaliacao->getOrdem() < $this->getResultadoAvaliacao()->getOrdemSequencia()) {

        $oAproveitamento = new AvaliacaoAproveitamento();
        $oAproveitamento->setElementoAvaliacao($oPeriodoAvaliacao);
        $oAproveitamento->setDiarioAvaliacaoDisciplina($oDiarioAvaliacaoDisciplina);

        $oValor = $oPeriodoAvaliacao->getResultado($aAproveitamentos, $this->isCalculoNotaParcial(), $iAno );

        if ( $oValor != "" ) {

          if ( $oPeriodoAvaliacao instanceof ResultadoAvaliacao ) {

            $mNotaReal = DiarioAvaliacaoDisciplina::calcularResultadoReal( $oPeriodoAvaliacao, $oDiarioAvaliacaoDisciplina->getDiario(), $aAproveitamentos, $iAno);
            if ( !is_null( $mNotaReal ) ) {
              $oValor->setAproveitamentoReal( $mNotaReal );
            }
          }

          $oAproveitamento->setValorAproveitamento($oValor);
          $aAproveitamentos[] = $oAproveitamento;
        }
      }


      if (!$oPeriodoAvaliacao->isResultado() && $oPeriodoAvaliacao->temJulgamentoMenorNota()) {
        $oPeriodoRecuperacao = $oPeriodoAvaliacao;
      }

      /**
       * Iteramos sobre todos aproveitamentos
       */
      foreach ($aAproveitamentos as $oAproveitamento) {

        if ($oAproveitamento->getOrdemSequencia() == $oElementoAvaliacao->getElementoAvaliacao()->getOrdemSequencia()) {

          /**
           * Validamos se estamos calculando um ResultadoAvaliacao
           * Se sim, devemos calcular a nota da avaliacao dos resultados anteriores
           */
          $aNotasPeriodo[$oAproveitamento->getOrdemSequencia()] = $oAproveitamento;
        }
      }
    }

    /**
     * Validamos se existe a troca de Nota
     */
    if (!empty($oPeriodoRecuperacao)) {
      $aNotasPeriodo = $this->aplicarJulgamentoMenorNotaDoPeriodo($oPeriodoRecuperacao, $aNotasPeriodo);
    }
    return $aNotasPeriodo;
  }

  /**
   * o Tipo de avaliacao do resultado
   *
   * @param FormaAvaliacao $oFormaAvaliacao
   * @return ValorAproveitamentoNota | ValorAproveitamentoNivel | ValorAproveitamentoParecer
   */
   public static function getTipoValorAproveitamento(FormaAvaliacao $oFormaAvaliacao) {

    switch ($oFormaAvaliacao->getTipo()) {

      case 'NOTA':

        return new ValorAproveitamentoNota();
        break;

      case 'NIVEL':

        return new ValorAproveitamentoNivel();
        break;

      case 'PARECER':

        return new ValorAproveitamentoParecer();
        break;
    }
  }

  /**
   * Define o valor do aproveitamento atribuito ou quando temos um resultado final alterado;
   * pode ser uma nota/conceito/parecer
   * @param mixed $mAproveitamento
   */
  public function setAproveitamento($mAproveitamento) {
    $this->mAproveitamento = $mAproveitamento;
  }

  /**
   * Verifica se o calculo é feito como caculo parcial
   * @return boolean
   */
  public function isCalculoNotaParcial() {
    return $this->lCalculoNotaParcial;
  }

  /**
   * Define se o calculo deve ser feito como nota parcial
   * @param boolean $lCalculoNotaParcial
   */
  public function setCalculoNotaParcial($lCalculoNotaParcial) {
    $this->lCalculoNotaParcial = $lCalculoNotaParcial;
  }

  /**
   * Calcula a nota projetada
   * @param AvaliacaoAproveitamento[] $aAvaliacaoAproveitamento
   * @return string
   */
  public function calcularNotaProjetada(array $aAvaliacaoAproveitamento) {
    return '';
  }

  /**
   * Realiza as trocas de notas que foram substiruidas para Calculo
   * @param AvaliacaoAproveitamento[] $aNotasPeriodos
   */
  protected function acertaNotasSubstituidasParaCalculo(array $aNotasPeriodos) {

    foreach ($aNotasPeriodos as $oAvaliacao) {

      if (isset($this->aListaAproveitamentosSubstituir[$oAvaliacao->getOrdemSequencia()])) {
        $oAvaliacao->setValorAproveitamento($this->aListaAproveitamentosSubstituir[$oAvaliacao->getOrdemSequencia()]->getValorAproveitamento());
      }
    }
  }

  /**
   * Aplicamos o Julgamento da Menor nota, caso o Perido Informado seja Nota
   *
   * @param AvaliacaoPeriodica $oPeriodoRecuperacao
   * @return bool
   */
  protected function aplicarJulgamentoMenorNotaDoPeriodo(AvaliacaoPeriodica $oPeriodoRecuperacao = null, array $aNotasPeriodo) {

    if (empty($oPeriodoRecuperacao)) {
      return $aNotasPeriodo;
    }

    if (!isset($aNotasPeriodo[$oPeriodoRecuperacao->getOrdemSequencia()])) {
      return $aNotasPeriodo;
    }

    $oResultadoVinculado = $oPeriodoRecuperacao->getElementoAvaliacaoVinculado();
    if (empty($oResultadoVinculado)) {
      return $aNotasPeriodo;
    }

    /**
     * validamos se o elemento vinculado é avaliado por nota. caso nao sejá não deverá ser julgado a menor nota.
     */
    if ($oResultadoVinculado->getFormaDeAvaliacao()->getTipo() != "NOTA") {
      return $aNotasPeriodo;
    }
    $oAvaliacaoRecuperacao           = $aNotasPeriodo[$oPeriodoRecuperacao->getOrdemSequencia()];
    $nNotaAvaliacao                  = $oAvaliacaoRecuperacao->getValorAproveitamento()->getAproveitamento();
    $aElementosVinculadosNoResultado = $oResultadoVinculado->getElementosComposicaoResultado();

    $sMenorNota    = null;
    $oMenorPeriodo = null;

    /**
     * Verificamos a menor nota dos periodos que compoem o resultado da recuperacao
     */
    foreach ($aElementosVinculadosNoResultado as $oElemento) {

      $oNota = isset($aNotasPeriodo[$oElemento->getOrdem()]) ? $aNotasPeriodo[$oElemento->getOrdem()] : null;

      if (empty($oNota)) {
        continue;
      }

      $sNota = $oNota->getValorAproveitamento()->getAproveitamento();
      if ($sMenorNota == null || ($sNota < $sMenorNota)) {

        $sMenorNota    = $sNota;
        $oMenorPeriodo = $oElemento->getElementoAvaliacao();
      }
    }

    if ($nNotaAvaliacao > $sMenorNota && $oMenorPeriodo != null) {

      /**
       * Adicionamos o periodo substituido a lista de periodos substiuidos, para posteriormente podermos
       * retorna a nota original do aluno.
       * a notas substituida é apenas utilizada para fins de calculo
       */

      $this->aListaAproveitamentosSubstituir[$oMenorPeriodo->getOrdemSequencia()] = $aNotasPeriodo[$oMenorPeriodo->getOrdemSequencia()];

      $oValorAproveitamento = new ValorAproveitamentoNota();
      $oValorAproveitamento->setAproveitamento($nNotaAvaliacao);

      /**
       * Clonamos o Aproveitamento, para nao alteramos a nota original do aluno
       */
      $oPeriodoAlterado   = clone $aNotasPeriodo[$oMenorPeriodo->getOrdemSequencia()];

      $oPeriodoAlterado->setValorAproveitamento($oValorAproveitamento);
      $aNotasPeriodo[$oMenorPeriodo->getOrdemSequencia()] = $oPeriodoAlterado;
    }
    unset($aNotasPeriodo[$oPeriodoRecuperacao->getOrdemSequencia()]);
    return $aNotasPeriodo;
  }

  /* PLUGIN DIARIO_PROGRESSAO - Método getElementosParaCalculoProgressaoParcial- NÃO APAGAR ESTE COMENTÁRIO*/
}