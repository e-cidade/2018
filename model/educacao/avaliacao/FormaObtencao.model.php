<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * @version $Revision: 1.6 $
 */
abstract class FormaObtencao {

  /**
   * array com os elementos da avaliacao
   * @var array iElementoAvaliacao
   */
  protected $aElementoAvaliacao;

  /**
   * Resultado de avaliacao que sera calculada
   * @var integer
   */
  protected $oResultadoAvaliacao;

  /**
   * Valor do Aproveitamento no resultado
   * @var mixed
   */
  protected $mAproveitamento;


  protected $lCalculoNotaParcial = false;

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
   * @param array $aAproveitamentos
   */
  protected function getElementosParaCalculo($aAproveitamentos) {

    $aNotasPeriodo = array();
    /**
     * Verificamos qual o Elemento de avaliacao esta sendo utilizado na forma de obtencao
     */
    foreach ($this->getResultadoAvaliacao()->getElementosComposicaoResultado() as $oElementoAvaliacao) {

      if ($oElementoAvaliacao->getElementoAvaliacao()->isResultado()
          && $oElementoAvaliacao->getOrdem() < $this->getResultadoAvaliacao()->getOrdemSequencia()) {

        $oAproveitamento = new AvaliacaoAproveitamento();
        $oAproveitamento->setElementoAvaliacao($oElementoAvaliacao->getElementoAvaliacao());

        $oValor = $oElementoAvaliacao->getElementoAvaliacao()->getResultado($aAproveitamentos,
                                                                            $this->isCalculoNotaParcial()
                                                                           );
        
        if ( $oValor != "" ) {
          
          $oAproveitamento->setValorAproveitamento($oValor);
          $aAproveitamentos[] = $oAproveitamento;
        }
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
    return $aNotasPeriodo;
  }

  /**
   * o Tipo de avaliacao do resultado
   * @return ValorAproveitamentoNota | ValorAproveitamentoNivel | ValorAproveitamentoParecer
   */
   public function getTipoValorAproveitamento(FormaAvaliacao $oFormaAvaliacao) {

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
}