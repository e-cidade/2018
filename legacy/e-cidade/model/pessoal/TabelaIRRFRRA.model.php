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
 * Classe com a tabela de alíquotas e percentuais utilizadas para cálculo do IRRF do RRA
 *
 * @package Pessoal
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.4 $
 */
 class TabelaIRRFRRA extends TabelaValores {

 	/**
 	 * NM se refere ao Número de meses do rra a ser pago para cálculo do TabelaIRRF
 	 */
 	private $nNM = null;

 	/**
 	 * Faixas da tabela do IRRF multiplicadas pelo NM
 	 */
 	private $aFaixasMultiplicadas = array();

 	function __construct($iCodigoTabela = null) {
 		parent::__construct($iCodigoTabela);
 	}

 	/**
 	 * Define o NM
 	 * @param Number
 	 */
 	public function setNM ($nNM) {
 	  $this->nNM = $nNM;
 	}

 	/**
 	 * Retorna o NM
 	 * @return Number
 	 */
 	public function getNM () {
 	  return $this->nNM;
 	}

 	/**
 	 * Função que multiplica as faixas da tabela pelo nm para o cálculo correto do IRRF do RRA
 	 */
 	public function multiplicarNM() {

 		if(is_null($this->nNM)) {
 			throw new BusinessException("Número de meses do RRA não está definido.");
 		}

 		if(count($this->getFaixas()) > 0) {

			$this->aFaixasMultiplicadas = array();

 			foreach ($this->getFaixas() as $oFaixa) {

 				$oFaixa->setInicio($oFaixa->getInicio()   * $this->nNM);
 				$oFaixa->setFim($oFaixa->getFim()         * $this->nNM);
				$oFaixa->setDeducao($oFaixa->getDeducao() * $this->nNM);
        $oFaixa->setPercentual($oFaixa->getPercentual());
 			}
 		}
    return $this;
 	}
}
