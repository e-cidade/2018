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
 * Value Object para a classe DiarioAvaliacaoDisciplina
 * @author dbseller
 * @package educacao
 * @subpackage avaliacao
 */
class DiarioAvaliacaoDisciplinaVO {

  /**
   * Codigo do diario
   * @var integer
   */
  private $iCodigoDiario;

  /**
   * Verifica se o diario esta encerrado
   * 'S' = true
   * 'N' = false
   * @var boolean
   */
  private $lEncerrado;

  /**
   * Instancia de Disciplina
   * @var Disciplina
   */
  private $oDisciplina;

  /**
   * Instancia da classe Regencia
   * @var Regencia
   */
  protected $oRegencia;


  /**
   * Define o codigo do Diario
   * @param integer $iCodigoDiario
   */
  public function setCodigoDiario($iCodigoDiario) {
     $this->iCodigoDiario = $iCodigoDiario;
  }

  /**
   * Retorna o codigo sequencial do diario
   * @return integer
   */
  public function getCodigoDiario() {
    return $this->iCodigoDiario;
  }

  /**
   * Retorna o codigo da regencia
   * @return Regencia
   */
  public function getRegencia() {
    return $this->oRegencia;
  }

  /**
   * Define o valor da Regencia
   * @param Regencia $oRegencia Regencia do diario de classe
   */
  public function setRegencia(Regencia $oRegencia) {
    $this->oRegencia = $oRegencia;
  }

  /**
   * Retorna o status de encerramento do diario
   * 'S' = true
   * 'N' = false
   *  @return boolean
   */
  public function isEncerrado() {
    return $this->lEncerrado;
  }

  /**
   * Atribui um status de encerramento do diario
   * 'S' = true
   * 'N' = false
   * @param boolean $lEncerrado
   * @throws ParameterException quando parâmetro nao for um boolean
   */
  public function setEncerrado($lEncerrado) {

    if (!is_bool($lEncerrado)) {
      throw new ParameterException('Parâmetro lEncerrado informado deve ser um boolean.');
    }
    $this->lEncerrado = $lEncerrado;
  }
}