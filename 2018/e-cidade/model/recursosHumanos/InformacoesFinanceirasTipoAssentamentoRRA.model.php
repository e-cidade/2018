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
 * Classe com as configurações de informações financeiras
 *
 * @author   Renan Pigato Silva renan.silva@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbrenan.silva $
 * @version  $Revision: 1.3 $
 */

class InformacoesFinanceirasTipoAssentamentoRRA {

  /**
   * Sequencial da configuração financeira do tipo de assentamento de RRA
   *
   * @var $iSequencial
   */
  private $iSequencial;

  /**
   * Tipo de assentamento a ser configurado
   *
   * @var $oTipoAssentamento
   */
  private $oTipoAssentamento;

  /**
   * Rubrica de Provento
   *
   * @var $oRubricaProvento
   */
  private $oRubricaProvento;

  /**
   * Rubrica de Irrf
   *
   * @var $oRubricaIrrf
   */
  private $oRubricaIrrf;

  /**
   * Rubrica de Previdencia
   *
   * @var $oRubricaPrevidencia
   */
  private $oRubricaPrevidencia;

  /**
   * Rubrica de Pensao
   *
   * @var $oRubricaPensao
   */
  private $oRubricaPensao;

  /**
   * Rubrica de Parcela de Dedução
   *
   * @var $oRubricaParcelaDeducao
   */
  private $oRubricaParcelaDeducao;

  /**
   * Rubrica de Moléstia Grave
   *
   * @var $oRubricaMolestia
   */
  private $oRubricaMolestia;

  /**
   * Construtor da classe
   *
   * @param no params
   */
  public function  __construct() {
  }


  /**
   * Define o Sequencial da configuração financeira do tipo de assentamento de RRA
   *
   * @param $iSequencial
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }

  /**
   * Define o Tipo de Assentamento a ser configurado
   *
   * @param $oTipoAssentamento
   */
  public function setTipoAssentamento(TipoAssentamento $oTipoAssentamento) {
    $this->oTipoAssentamento = $oTipoAssentamento;
  }

  /**
   * Define a Rubrica de Provento
   *
   * @param $oRubricaProvento
   */
  public function setRubricaProvento(Rubrica $oRubricaProvento) {
    $this->oRubricaProvento = $oRubricaProvento;
  }

  /**
   * Define a Rubrica de Irrf
   *
   * @param $oRubricaIrrf
   */
  public function setRubricaIrrf(Rubrica $oRubricaIrrf) {
    $this->oRubricaIrrf = $oRubricaIrrf;
  }

  /**
   * Define a Rubrica de Previdencia
   *
   * @param $oRubricaPrevidencia
   */
  public function setRubricaPrevidencia(Rubrica $oRubricaPrevidencia) {
    $this->oRubricaPrevidencia = $oRubricaPrevidencia;
  }

  /**
   * Define a Rubrica de Pensao
   *
   * @param $oRubricaPensao
   */
  public function setRubricaPensao(Rubrica $oRubricaPensao) {
    $this->oRubricaPensao = $oRubricaPensao;
  }

  /**
   * Define a Rubrica de Parcela de Deducao
   *
   * @param  Rubrica  $oRubricaParcelaDeducao
   */
  public function setRubricaParcelaDeducao(Rubrica $oRubricaParcelaDeducao) {
    $this->oRubricaParcelaDeducao = $oRubricaParcelaDeducao;
  }

  /**
   * Define  a Rubrica de Moléstia grave
   * @param Rubrica
   */
  public function setRubricaMolestia ($oRubricaMolestia) {
    $this->oRubricaMolestia = $oRubricaMolestia;
  }

  /**
   * Retorna o Sequencial da configuração financeira do tipo de assentamento
   *
   * @return Integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Retorna o Tipo de Assentamento configurado
   *
   * @return TipoAssentamento
   */
  public function getTipoAssentamento() {
    return $this->oTipoAssentamento;
  }

  /**
   * Retorna a Rubrica de Provento
   *
   * @return $oRubricaProvento
   */
  public function getRubricaProvento() {
    return $this->oRubricaProvento;
  }

  /**
   * Retorna a Rubrica de Irrf
   *
   * @return $oRubricaIrrf
   */
  public function getRubricaIrrf() {
    return $this->oRubricaIrrf;
  }

  /**
   * Retorna a Rubrica de Previdencia
   *
   * @return $oRubricaPrevidencia
   */
  public function getRubricaPrevidencia() {
    return $this->oRubricaPrevidencia;
  }

  /**
   * Retorna a Rubrica de Pensao
   *
   * @return $oRubricaPensao
   */
  public function getRubricaPensao() {
    return $this->oRubricaPensao;
  }

  /**
   * Retorna a Rubrica de Encargo
   *
   * @return  $oRubricaEncargo
   */
  public function getRubricaParcelaDeducao() {
    return $this->oRubricaParcelaDeducao;
  }
  
  /**
   * Retorna  a Rubrica de Moléstia grave
   * @return Rubrica
   */
  public function getRubricaMolestia () {
    return $this->oRubricaMolestia; 
  }  
}
