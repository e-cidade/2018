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
 * Representa características do empenho.
 * Utilizado para definir a lista de classificação do credor.
 */
class AtributosEmpenho {

  /**
   * @var float
   */
  private $nValor;

  /**
   * @var ContaOrcamento
   */
  private $oContaOrcamento;

  /**
   * @var Recurso
   */
  private $oRecurso;

  /**
   * @var TipoCompra
   */
  private $oTipoCompra;

  /**
   * @var TipoPrestacaoConta
   */
  private $oTipoPrestacaoConta;

  /**
   * Código do elemento
   * @var integer
   */
  private $iElemento;

  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return Recurso
   */
  public function getRecurso() {
    return $this->oRecurso;
  }

  /**
   * @param Recurso $oRecurso
   */
  public function setRecurso(Recurso $oRecurso) {
    $this->oRecurso = $oRecurso;
  }

  /**
   * @return TipoCompra
   */
  public function getTipoCompra() {
    return $this->oTipoCompra;
  }

  /**
   * @param TipoCompra $oTipoCompra
   */
  public function setTipoCompra(TipoCompra $oTipoCompra) {
    $this->oTipoCompra = $oTipoCompra;
  }

  /**
   * @return ContaOrcamento
   */
  public function getContaOrcamento() {
    return $this->oContaOrcamento;
  }

  /**
   * @param ContaOrcamento $oContaOrcamento
   */
  public function setContaOrcamento(ContaOrcamento $oContaOrcamento) {
    $this->oContaOrcamento = $oContaOrcamento;
  }

  /**
   * @return TipoPrestacaoConta
   */
  public function getTipoPrestacaoConta() {
    return $this->oTipoPrestacaoConta;
  }

  /**
   * @param TipoPrestacaoConta $oTipoPrestacaoConta
   */
  public function setTipoPrestacaoConta(TipoPrestacaoConta $oTipoPrestacaoConta) {
    $this->oTipoPrestacaoConta = $oTipoPrestacaoConta;
  }

  /**
   * @return integer $iElemento
   */
  public function getElemento() {
    return $this->iElemento;
  }

  /**
   * @param integer $iElemento
   */
  public function setElemento($iElemento) {
    $this->iElemento = $iElemento;
  }

}