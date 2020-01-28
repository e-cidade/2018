<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Representação de um RRA
 *
 * @package Pessoal
 * @revision $Author: dbiuri $
 * @version  $Revision: 1.4 $
 */

class RRA {

	/**
	 * @access private
	 * @var    Assentamento
	 */
	private $oAssentamento;

	/**
	 * @access private
	 * @var    LancamentoRRA[]
	 */
	private $aLancamentos = array();

	/**
	 * @access private
	 * @var Number
	 */
	private $nTotalLancado;

	/**
	 * @access private
	 * @var Number
	 */
	private $nTotalEncargosLancado;

  /**
   * Construtor da classe
   *
   * @param Assentamento
   */
	public function __construct($oAssentamento = null) {

		if($oAssentamento instanceof AssentamentoRRA) {
			$this->oAssentamento = $oAssentamento;
		} elseif(!empty($oAssentamento)) {
			throw new ParameterException("Informe um assentamento de RRA.");
		}
	}

	/**
	 * Define o Assentamento que gerou o RRA
	 * @param Assentamento
	 */
	public function setAssentamento ($oAssentamento) {
	  $this->oAssentamento = $oAssentamento;
	}
	
	/**
	 * Retorna o Assentamento que gerou o RRA
	 * @return Assentamento
	 */
	public function getAssentamento () {
	  return $this->oAssentamento; 
	}
	
	/**
	 * Define a coleção de lançamentos do RRA
	 * @param Array
	 */
	public function setLancamentos ($aLancamentos) {
	  $this->aLancamentos = $aLancamentos;
	}
	
	/**
	 * Retorna a coleção de lançamentos do RRA
	 * @return LancamentoRRA[]
	 */
	public function getLancamentos () {
	  return $this->aLancamentos; 
	}

	/**
	 * Adiciona um Lançamento de RRA à coleção de lançamentos
	 * @param LancamentoRRA
	 */
	public function adicionarLancamento (LancamentoRRA $oLancamentoRRA) {

		if($this->validarLancamento($oLancamentoRRA)) {

	  	$this->aLancamentos[$oLancamentoRRA->getCodigo()] = $oLancamentoRRA;
	  	$this->nTotalLancado         += $oLancamentoRRA->getValorLancado();
	  	$this->nTotalEncargosLancado += $oLancamentoRRA->getValorEncargos();
		}
	}

	/**
	 * Valida um lançamento antes de adicioná-lo, se o lançamento
	 * for maior que o total não deve permitir
	 * @param LancamentoRRA
	 */
	public function validarLancamento (LancamentoRRA $oLancamentoRRA) {

		if($oLancamentoRRA->getValorLancado() > $this->getSaldo()) {
			throw new BusinessException("Valor lançado para parcela ultrapassa total do RRA.");
		}

		if($oLancamentoRRA->getValorEncargos() > $this->getSaldoEncargos()) {
			throw new BusinessException("Valor lançado de encargos para parcela ultrapassa total dos encargos do RRA.");
		}

		return true;
	}

	/**
   * Carrega os lançamentos de RRA
   *
   * @access public
   */
  public function carregarLancamentos() {
    
    $this->aLancamentos = LancamentoRRARepository::getInstanciasByAssentamento($this->oAssentamento);

    if(count($this->aLancamentos) > 0) {

    	foreach ($this->aLancamentos as $oLancamento) {
    		
    		$this->nTotalLancado         += $oLancamento->getValorLancado();
    		$this->nTotalEncargosLancado += $oLancamento->getValorEncargos();
    	}
    }
  }

	/**
	 * Retorna a quantidade de lançamentos do RRA
	 * @return Integer
	 */
	public function getTotalLancamentos () {
	  return count($this->aLancamentos); 
	}

	/**
	 * Define o total já lançado para o RRA
	 * @param Number
	 */
	public function setTotalLancado ($nValorLancado) {
	  $this->nTotalLancado = $nValorLancado;
	}
	
	/**
	 * Retorna o total já lançado para o RRA
	 * @return Number
	 */
	public function getTotalLancado () {
	  return $this->nTotalLancado; 
	}
	
	/**
	 * Define o total dos encargos já lançados para o RRA
	 * @param Number
	 */
	public function setTotalEncargosLancado ($nValorEncargosLancado) {
	  $this->nTotalEncargosLancado = $nValorEncargosLancado;
	}
	
	/**
	 * Retorna o total dos encargos já lançados para o RRA
	 * @return Number
	 */
	public function getTotalEncargosLancado () {
	  return $this->nTotalEncargosLancado; 
	}

	/**
	 * Retorna o saldo do RRA para lançar pagamentos
	 * @return Number
	 */
	public function getSaldo () {
	  return $this->oAssentamento->getValorTotalDevido() - $this->nTotalLancado;
	}
	
	/**
	 * Retorna o saldo dos encargos RRA para lançar pagamentos
	 * @return Number
	 */
	public function getSaldoEncargos () {
	  return $this->oAssentamento->getValorDosEncargosJudiciais() - $this->nTotalEncargosLancado; 
	}
}