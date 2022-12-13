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
 * Calculo avaliacao por SOMA
 * Retorna um somatorio das avaliacoes
 *
 * +--------------------------------- ALTERAÇÃO NO CALCULO A PARTIR DA RELEASE 53 -----------------------------------+
 * | Quando estamos calculando um Resultado onde a Forma de Obtenção é uma SOMA e o parâmetro da proporcionalidade   |
 * | esta "desligado", devemos apresentar a avaliação que o aluno realmente obteve, mais para fins de calculo de     |
 * | aproveitamento, o devemos considerar sempre a nota proporcional. Isso é feito sempre após processar o Resultado |
 * | Exemplo:                                                                                                        |
 * |   $oFormaObtencaoSoma = new FormaObtencaoSoma();                                                                |
 * |   $oFormaObtencaoSoma->setResultadoAvaliacao($ResultadoAvaliacao);                                              |
 * |   $oValor = $oFormaObtencaoSoma->calcularResultado( $DiarioAvaliacaoDisciplina->getAvaliacoes(), $iAno );       |
 * |   $oAproveitamentoResultado->setAproveitamentoReal( $oValor );                                                  |
 * |                                                                                                                 |
 * | Para mais detalhes, ver DiarioAvaliacaoDisciplina->salvarDadosResultado                                         |
 * +-----------------------------------------------------------------------------------------------------------------+
 *
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.36 $
 */
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/avaliacao/FormaObtencao.model.php"));
class FormaObtencaoSoma extends FormaObtencao implements IFormaObtencao {

  /**
   * Define as notas que ira ser usaddo no calculo
   * Deverá ser instancias de AvaliacaoAproveitamento
   * @see IFormaObtencao::processarResultado()
   * @param array   $aAproveitamentos
   * @param integer $iAno
   * @return int|string|ValorAproveitamentoNota
   */
  public function processarResultado( $aAproveitamentos, $iAno ) {

    $mAproveitamento                     = $this->calcularResultado( $aAproveitamentos, $iAno );
    $aNotasPeriodos                      = $this->getElementosParaCalculo( $aAproveitamentos, $iAno );
    $oDiarioDisciplina                   = $aAproveitamentos[0]->getDiarioAvaliacaoDisciplina();
    $aElementos                          = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
    $aPeriodosAvaliacaoProporcionalidade = $oDiarioDisciplina->getPeriodosAvaliacaoProporcionalidade();

    if ( $this->getResultadoAvaliacao()->utilizaProporcionalidade() &&
         !empty($aPeriodosAvaliacaoProporcionalidade) > 0 &&
         !$oDiarioDisciplina->proporcionalidadeComAmparoTotal()) {

      $mAproveitamento = $this->calcularProporcionalidade($aElementos, $aPeriodosAvaliacaoProporcionalidade);
    }

    /**
     * Quando há amparo para um dos Elementos que compõe o resultado final, devemos recalcular o valor do
     * aproveitamento, aplicando o calculo da proporcionalida.
     */
    $oAmparo = $oDiarioDisciplina->getAmparo();
    if ( !is_null($oDiarioDisciplina->getAmparo()) != null && empty($aPeriodosAvaliacaoProporcionalidade) ) {

      $lCalcularProporcionalidade = false;
      foreach ($oAmparo->getPeriodosAmparados(true) as $oAvaliacaoAmparada) {

        if ( isset($aElementos[$oAvaliacaoAmparada->getOrdemSequencia()]) ) {
          $lCalcularProporcionalidade = true;
          break;
        }
      }
      if ( $lCalcularProporcionalidade ) {
        $mAproveitamento = $this->calculaNotaComAmparo($oDiarioDisciplina->getAvaliacoes(), $iAno);
      }
    }

    $mAproveitamento = ArredondamentoNota::arredondar( $mAproveitamento, $iAno );

    /**
     * Devolvemos as notas Originais
     */
    $this->acertaNotasSubstituidasParaCalculo($aNotasPeriodos);
    $mAproveitamento = new ValorAproveitamentoNota($mAproveitamento);
    return $mAproveitamento;
  }

  /**
   * Calcula a nota projetada
   * @param AvaliacaoAproveitamento[] $aElementosAvaliacoes
   * @return string
   */
  public function calcularNotaProjetada(array $aElementosAvaliacoes) {

    $nMinimoAprovacao = $this->oResultadoAvaliacao->getAproveitamentoMinimo();
    $nSomaElementos   = 0;
    foreach ($aElementosAvaliacoes as $oElementoAvaliacao) {
      $nSomaElementos += $oElementoAvaliacao->getValorAproveitamento()->getAproveitamento();
    }

    $nNotaProjetada = $nMinimoAprovacao - $nSomaElementos;

    return $nNotaProjetada < 0 ? '' : $nNotaProjetada;
  }

  /**
   * Calcula a proporcionalidade da avaliação do aluno
   * @param  AvaliacaoAproveitamento[] $aElementos
   * @return float
   */
  private function calcularProporcionalidade( $aElementosResultado, $aElementos ) {

    $nSomaAvaliacao  = 0;
    $nSomaMaiorValor = 0;
    $lCalcularNota   = false;

    $lTemNota = false;

    foreach( $aElementos as $oElemento ) {

      if( $oElemento->isAmparado() ) {
        continue;
      }

      if ( $oElemento->getValorAproveitamento()->getAproveitamento() !== ''  ) {
        $lTemNota = true;
      }
    }

    if ( !$lTemNota ) {
      return '';
    }

    foreach( $aElementos as $oElemento ) {

      $oElementoResultado        = $aElementosResultado[$oElemento->getOrdemSequencia()];
      $lValidaPeriodoObrigatorio = $oElementoResultado->isObrigatorio();

      if( $oElemento->isAmparado() ) {
        continue;
      }

      if( $lValidaPeriodoObrigatorio && $oElemento->getValorAproveitamento()->getAproveitamento() === '' ) {

        $nSomaAvaliacao = '';
        break;
      }

      // Só deve realizar calculo, se ao menos um período, possue avaliação lançada
      if ( $oElemento->getValorAproveitamento()->getAproveitamento() !== '' ) {
        $lCalcularNota    = true;
      }
      $nSomaAvaliacao  += $oElemento->getValorAproveitamento()->getAproveitamento();
      $nSomaMaiorValor += $oElemento->getElementoAvaliacao()->getFormaDeAvaliacao()->getMaiorValor();

    }

    $nValorCalculado               = $lCalcularNota ? ($nSomaAvaliacao * 100) / $nSomaMaiorValor : '';
    $nMaiorValorResultadoAvaliacao = $this->oResultadoAvaliacao->getFormaDeAvaliacao()->getMaiorValor();



    return ($nMaiorValorResultadoAvaliacao * $nValorCalculado) / 100;
  }

  /**
   * Percorre todos os elementos de avaliação, e quando o período estiver amparado, utiliza o mínimo para aprovação, no
   * cálculo do aproveitamento
   * @param  array $aElementosAvaliacao
   * @return float
   */
  public function calculaNotaComAmparo( $aElementosAvaliacao, $iAno ) {

    $nAproveitamento              = null;
    $aPeriodosValidos             = array();
    $aNotasPeriodos               = $this->getElementosParaCalculo( $aElementosAvaliacao, $iAno );
    $aElementos                   = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();

    $aPeriodoAvaliacaoAlternativa = array();

    foreach ($aNotasPeriodos as $oNotaDoAproveitamento ) {

      if( $oNotaDoAproveitamento->getDiarioAvaliacaoDisciplina()->hasAvaliacaoAlternativa() ) {

        $oAvaliacaoAlternativa = $oNotaDoAproveitamento->getDiarioAvaliacaoDisciplina()->getAvaliacaoAlternativa();

        foreach( $oAvaliacaoAlternativa->getConfiguracao() as $oConfiguracao ) {

          if(    empty( $oConfiguracao->sFormaAvaliacao )
              && !in_array( $oConfiguracao->iOrdemPeriodo, $aPeriodoAvaliacaoAlternativa ) ) {
            $aPeriodoAvaliacaoAlternativa[] = $oConfiguracao->iOrdemPeriodo;
          }
        }
      }

      foreach ($aElementosAvaliacao as $oElementoAvaliacao) {

        if ($oNotaDoAproveitamento->getOrdemSequencia() == $oElementoAvaliacao->getOrdemSequencia() ) {

          if(    $oElementoAvaliacao->isAmparado()
              && !in_array( $oNotaDoAproveitamento->getOrdemSequencia(), $aPeriodoAvaliacaoAlternativa ) ) {
            continue;
          }

          $aPeriodosValidos[ $oNotaDoAproveitamento->getOrdemSequencia() ] = $oNotaDoAproveitamento;
        }
      }
    }

    $nSomaAvaliacao  = null;
    $nSomaMaiorValor = 0;

    foreach ($aPeriodosValidos as $oAproveitamento) {

      $oElemento              = $aElementos[$oAproveitamento->getOrdemSequencia()];
      $nAproveitamentoPeriodo = $oAproveitamento->getValorAproveitamento()->getAproveitamento();

      if (    !$this->isCalculoNotaParcial()
           && $oElemento->isObrigatorio()
           && $nAproveitamentoPeriodo === ""
           && !in_array( $oAproveitamento->getOrdemSequencia(), $aPeriodoAvaliacaoAlternativa ) ) {
        return '';
      }

      if ( $oAproveitamento->getValorAproveitamento()->getAproveitamento() !== '' ) {
        $nSomaAvaliacao  += $oAproveitamento->getValorAproveitamento()->getAproveitamento();
      }
      $nSomaMaiorValor += $oAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getMaiorValor();
    }

    if ( ( empty($nSomaAvaliacao) && empty($nSomaMaiorValor) )
        || is_null($nSomaAvaliacao)
       ) {
      return '';
    }
    $nValorCalculado = $nSomaAvaliacao !== '' ? ($nSomaAvaliacao * 100) / $nSomaMaiorValor : '';

    $nMaiorValorResultadoAvaliacao = $this->oResultadoAvaliacao->getFormaDeAvaliacao()->getMaiorValor();

    return ($nMaiorValorResultadoAvaliacao * $nValorCalculado) / 100;
  }

  /**
   * Calcula o resultado do Aproveitamento sem que seja aplicado as regras da proporcionalidade ou do amparo com
   * proporcionalidade.
   *
   * @param array $aAproveitamentos
   * @param integer $iAno
   * @return int
   */
  public function calcularResultado( $aAproveitamentos, $iAno ) {

    $mAproveitamento   = '';
    $aNotasPeriodos    = $this->getElementosParaCalculo( $aAproveitamentos, $iAno );
    $aElementos        = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
    $oDiarioDisciplina = $aAproveitamentos[0]->getDiarioAvaliacaoDisciplina();

    /**
     * Percorremos as avaliacoes que possuem valor
     * e somamos todas as avaliacoes
     */
    foreach ($aNotasPeriodos as $oNotaDoAproveitamento) {

      if ($oNotaDoAproveitamento->isAmparado()) {
        continue;
      }

      $oElemento                 = $aElementos[$oNotaDoAproveitamento->getOrdemSequencia()];
      $lValidaPeriodoObrigatorio = $oElemento->isObrigatorio();

      $oDiarioAvaliacaoDisciplina = $oNotaDoAproveitamento->getDiarioAvaliacaoDisciplina();
      if (!is_null($oDiarioAvaliacaoDisciplina)) {

        $oResultadoFinal            = $oDiarioAvaliacaoDisciplina->getResultadoFinal();
        $lUtilizaProporcionalidade  = false;

        if( $oResultadoFinal->getResultadoAvaliacao() != '' ) {
          $lUtilizaProporcionalidade  = $oResultadoFinal->getResultadoAvaliacao()->utilizaProporcionalidade();
        }

        /**
         * Quando o calculo do resultado final utiliza proporcionalidade devemos ignorar a obrigatoriedade do lançamento
         * de avaliação para o periodo de avaliação que não esta configurado a proporcionalidade
         */
        if ( $lUtilizaProporcionalidade ) {

          $aPeriodosProporcionais = $oNotaDoAproveitamento->getDiarioAvaliacaoDisciplina()->getOrdemPeriodosAplicaProporcionalidade();
          if (count($aPeriodosProporcionais) > 0
               && !in_array($oElemento->getOrdem(), $aPeriodosProporcionais)
               && $oElemento->isObrigatorio() ) {
            $lValidaPeriodoObrigatorio = false;
          }
        }
      }

      $nAproveitamentoPeriodo = $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento();

      if (!$this->isCalculoNotaParcial() && $lValidaPeriodoObrigatorio && $nAproveitamentoPeriodo === "") {

        $mAproveitamento = '';
        $iTotalPeriodos  = 0;
        break;
      }

      if ($oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento() !== "") {
        $mAproveitamento += $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento();
      }
    }

    return $mAproveitamento;
  }
}