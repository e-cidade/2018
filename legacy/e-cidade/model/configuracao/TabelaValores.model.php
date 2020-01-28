<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
 * Classe com a tabela de valores
 *
 * @package Configuracao
 * @version  $Id: TabelaValores.model.php,v 1.2 2016/01/28 13:29:55 dbrenan Exp $
 */
class TabelaValores {

  /**
 	 * Código da tabela de IRRF
 	 */
 	protected $iCodigoTabela;

 	/**
 	 * Faixas da tabela do IRRF
 	 */
 	protected $aFaixas = array();

 	public function __construct($iCodigoTabela = null) {
    $this->setCodigo($iCodigoTabela);
  }

 	/**
 	 * Define o Código da tabela de IRRF
 	 *
 	 * @param Number
 	 */
 	public function setCodigo ($iCodigoTabela) {
 	  $this->iCodigoTabela = $iCodigoTabela;
 	}

 	/**
 	 * Retorna o Código da tabela de IRRF
 	 *
 	 * @return Number
 	 */
 	public function getCodigo () {
 	  return $this->iCodigoTabela;
 	}

 	/**
 	 * Define as faixas da tabela
 	 *
 	 * @param FaixaIRRF[]
 	 */
 	public function setFaixas ($aFaixas) {
 	  $this->aFaixas = $aFaixas;
 	}

 	/**
 	 * Retorna as faixas da tabela
 	 *
 	 * @return FaixaIRRF[]
 	 */
 	public function getFaixas () {
 	  return $this->aFaixas;
 	}


  public function addFaixa ($oFaixa) {
    $this->aFaixas[] = $oFaixa;
    $this->ordenarFaixas();
  }


  public function getFaixaPeloValor($nValor) {

    foreach ($this->aFaixas as $oFaixa) {

      if (DBNumber::overlaps($nValor, $oFaixa->getInicio(), $oFaixa->getFim())){      
        return $oFaixa;
      }
    }

    throw new BusinessException("Valor({$nValor}) não se aplica a nenhuma faixa.");
  }

  protected function ordenarFaixas(){

    usort($this->aFaixas, function($oFaixa1, $oFaixa2) {

      if ($oFaixa1->getInicio() == $oFaixa2->getInicio()) {
        return 0;
      }

      if ($oFaixa1->getInicio() < $oFaixa2->getInicio()) {
        return -1;
      }

      return 1; // Caso faixa2 seja menor que 1
    });
  }


  public function getPrimeiraFaixa($lInicial = true) {
    return $this->aFaixas[0];
  }

  /**
   * @TODO
   */
  public function getUltimaFaixa($lInicial = true){
    return $this->aFaixas[count($this->aFaixas) - 1];
  }
}
