<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Periodo de Avaliacao
 * Ex:  1º BIMESTRE, 3º BIMESTRE, 2º BIMESTRE, 4º BIMESTRE, 1º TRI
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.1 $
 */
class PeriodoAvaliacao {
  
  /**
   * Codigo sequencial identificador do Periodo de Avaliacao
   * @var integer
   */
  private $iCodigo;
  /**
   * Descricao de Periodo de Avaliacao
   * Ex: 1º BIMESTRE
   * @var string
   */
  private $sDescricao;
  
  /**
   * Descricao abreviada de Periodo de Avaliacao
   * Ex.: 1º BIM
   * @var string
   */
  private $sDescricaoAbreviada;
  
  /**
   * Parametro usado para informar se devemos somar a carga horaria
   * @var boolean
   */
  private $lSomaCargaHoraria;
  
  /**
   * Parametro usado para informar se devemos controlar a frequencia
   * @var boolean
   */
  private $lControlaFrequencia;
  
  /**
   * Ordem de apresentacao no conjunto de PeriodoAvaliacao
   * @var integer
   */
  private $iOrdemPeriodo;
  
  public function __construct($iCodigoPeriodoAvaliacao = null) {
    
    if (!empty($iCodigoPeriodoAvaliacao)) {
      
      $oDaoPeriodoAvaliacao = db_utils::getDao('periodoavaliacao');
      $sSqlPeriodoAvaliacao = $oDaoPeriodoAvaliacao->sql_query_file($iCodigoPeriodoAvaliacao);
      $rsPeriodoAvaliacao   = $oDaoPeriodoAvaliacao->sql_record($sSqlPeriodoAvaliacao);
      
      if ($oDaoPeriodoAvaliacao->numrows > 0) {
        
        $oPeriodoAvaliacao = db_utils::fieldsMemory($rsPeriodoAvaliacao, 0);
        
        $this->iCodigo             = $oPeriodoAvaliacao->ed09_i_codigo;
        $this->sDescricao          = $oPeriodoAvaliacao->ed09_c_descr;
        $this->sDescricaoAbreviada = $oPeriodoAvaliacao->ed09_c_abrev;
        $this->lSomaCargaHoraria   = $oPeriodoAvaliacao->ed09_c_somach == 'S' ? true : false;
        $this->lControlaFrequencia = $oPeriodoAvaliacao->ed09_c_controlfreq == 'S' ? true : false; 
        $this->iOrdemPeriodo       = $oPeriodoAvaliacao->ed09_i_sequencia;
      }
    }
  }
  
  /**
   * Codigo sequencial identificador do Periodo de Avaliacao
   * @return integer
   */
  public function getCodigo() {
    
    return $this->iCodigo; 
  }
  
  /**
   * Seta uma descricao Descricao para o Periodo de Avaliacao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna uma descricao Descricao para o Periodo de Avaliacao
   * @return string
   */
  public function getDescricao() {
  
    return $this->sDescricao;
  }
  
  /**
   * Seta uma descricao Descricao abreviada para o Periodo de Avaliacao
   * @param string $sDescricaoAbreviada
   */
  public function setDescricaoAbreviada($sDescricaoAbreviada) {
  
    $this->sDescricaoAbreviada = $sDescricaoAbreviada;
  }
  
  /**
   * Retorna uma descricao Descricao abreviada para o Periodo de Avaliacao
   * @return string
   */
  public function getDescricaoAbreviada() {
  
    return $this->sDescricaoAbreviada;
  }
  
  /**
   * Atribui identificacao se tem ou nao que somar a carga horaria
   * @param boolean $lSomaCargaHoraria
   * @throws ParameterException
   */
  private function setSomaCargaHoraria($lSomaCargaHoraria) {

    if (!is_bool($lSomaCargaHoraria)) {
      throw new ParameterException('Parâmetro lSomaCargaHoraria deve ser um Boolean.');
    }
    $this->lSomaCargaHoraria = $lSomaCargaHoraria;
  }
  
  /**
   * Verifica se tem que somar a carga horaria
   * @return boolean
   */
  public function hasSomaCargaHoraria() {
    
    return $this->lSomaCargaHoraria;
  }
  
  /**
   * Atribui identificacao se tem ou nao que controlar a frequencia
   * @param boolean $lControlaFrequencia
   * @throws ParameterException
   */
  private function setControlaFrequencia($lControlaFrequencia) {
  
    if (!is_bool($lControlaFrequencia)) {
      throw new ParameterException('Parâmetro lControlaFrequencia deve ser um Boolean.');
    }
    $this->lControlaFrequencia = $lControlaFrequencia;
  }
  
  /**
   * Verifica se tem que controlar a Frequencia
   * @return boolean
   */
  public function hasControlaFrequencia() {
  
    return $this->lControlaFrequencia;
  }

  /**
   * Seta uma ordem de exibicao para o Periodo de Avaliacao
   * @param integer $iOrdemPeriodo
   */
  public function setOrdemPeriodo($iOrdemPeriodo) {
    
    $this->iOrdemPeriodo = $iOrdemPeriodo;
  }
  
  /**
   * Retorna uma ordem de exibicao para o Periodo de Avaliacao
   * @return integer
   */
  public function getOrdemPeriodo() {
    
    return $this->iOrdemPeriodo;
  }
  
}