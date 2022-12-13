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
 * Classe para manipula��o de Nome da Classe
 *
 * @package Pessoal
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */

class FaixaValorIRRF extends FaixaValor {


  /**
   * Representa o valor de Percentual da faixa do IRRF
   */
  protected $nValorPercentual;

  /**
   * Representa o valor de Dedu��o da faixa do IRRF
   */
  protected $nValorDeducao;


  /**
   * Define o valor do percentual de al�quota da faixa
   * @param Number
   */
  public function setPercentual ($nValorPercentual) {
    $this->nValorPercentual = (float)$nValorPercentual;
  }

  /**
   * Retorna o valor do percentual de al�quota da faixa
   * @return Number
   */
  public function getPercentual () {
    return $this->nValorPercentual;
  }

  /**
   * Define o valor da dedu��o do imposto da respectiva faixa
   * @param Number
   */
  public function setDeducao ($nValorDeducao) {
    $this->nValorDeducao = (float)$nValorDeducao;
  }

  /**
   * Retorna o valor da dedu��o do imposto da respectiva faixa
   * @return Number
   */
  public function getDeducao () {
    return $this->nValorDeducao;
  }
}
