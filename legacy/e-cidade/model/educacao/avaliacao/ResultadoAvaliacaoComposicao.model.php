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
 * Resultado da Avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.5 $
 */
class ResultadoAvaliacaoComposicao {

  /**
   * Elemento de avaliacao
   * @var ResultadoAvaliacao || AvaliacaoPeriodica
   */
  private $oElementoAvaliacao;

  /**
   * Define se periodo de avaliação sera obrigatorio ou não no calculo do resultado
   * @var boolean
   */
  private $lObrigatorio;

  /**
   * Valor minimo para aprovacao
   * @var integer
   */
  private $iMinimoAprovacao;

  /**
   * Peso da nota para a avaliacao
   * @var integer
   */
  private $iPeso;

  /**
   * Ordem de execucao
   * @var integer
   */
  private $iOrdemSequencial;

  public function __construct() {

  }

  /**
   * Elemento de avaliacao
   * @param ResultadoAvaliacao || AvaliacaoPeriodica  $oElementoAvaliacao
   */
  public function setElementoAvaliacao(IElementoAvaliacao $oElementoAvaliacao) {

    $this->oElementoAvaliacao = $oElementoAvaliacao;
  }

  /**
   * Elemento de avaliacao
   * @return ResultadoAvaliacao|AvaliacaoPeriodica  $oElementoAvaliacao
   */
  public function getElementoAvaliacao() {

    return $this->oElementoAvaliacao;
  }

  /**
   * Define se periodo de avaliação sera obrigatorio ou não no calculo do resultado
   * @param boolean $lObrigatorio
   */
  public function setObrigatorio($lObrigatorio) {

    if (!is_bool($lObrigatorio)) {
      throw new ParameterException("Parâmetro lObrigatorio deve ser um boolean");
    }
    $this->lObrigatorio = $lObrigatorio;
  }

  /**
   * Verifica se periodo de avaliação sera obrigatorio ou não no calculo do resultado
   * @return boolean
   */
  public function isObrigatorio() {

    return $this->lObrigatorio;
  }

  /**
   * Define o valor minimo para aprovacao
   * @param integer $iMinimoAprovacao
   */
  public function setMinimoAprovacao($iMinimoAprovacao) {

    $this->iMinimoAprovacao = $iMinimoAprovacao;
  }

  /**
   * Retorna o valor minimo para aprovacao
   * @return integer
   */
  public function getMinimoAprovacao() {

    return $this->iMinimoAprovacao;
  }

  /**
   * Define o peso da nota para a avaliacao
   * @param integer $iPeso
   */
  public function setPeso($iPeso) {

    $this->iPeso = $iPeso;
  }

  /**
   * Retorna o peso da nota para a avaliacao
   * @return integer
   */
  public function getPeso() {

    return $this->iPeso;
  }

  /**
   * Define a ordem de execucao
   * @param integer $iOrdemSequencial
   */
  public function setOrdem($iOrdemSequencial) {

    $this->iOrdemSequencial = $iOrdemSequencial;
  }

  /**
   * Retorna a ordem de execucao
   * @return integer
   */
  public function getOrdem() {

    return $this->iOrdemSequencial;
  }
}