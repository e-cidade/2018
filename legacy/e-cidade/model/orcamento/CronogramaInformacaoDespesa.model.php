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
 * Class CronogramaInformacaoDespesa
 * Value Object para as informações do cronograma de despesa
 */
class CronogramaInformacaoDespesa {

  /**
   * @type float
   */
  private $nValorPago = 0;

  /**
   * @type float
   */
  private $nValorCotaMensal = 0;

  /**
   * @type float
   */
  private $nValorPrevisto = 0;

  /**
   * @type float
   */
  private $nValorReestimado = null;

  public function __construct() {}

  /**
   * Valor Pago - Estornado
   * @param float $nValorPago
   */
  public function setValorPago($nValorPago) {
    $this->nValorPago = $nValorPago;
  }

  /**
   * @return float
   */
  public function getValorPago() {
    return $this->nValorPago;
  }

  /**
   * @param float $nValorCotaMensal
   */
  public function setValorCotaMensal($nValorCotaMensal) {
    $this->nValorCotaMensal = $nValorCotaMensal;
  }

  /**
   * @return float
   */
  public function getValorCotaMensal() {
    return $this->nValorCotaMensal;
  }

  /**
   * @param $nValorPrevisto
   */
  public function setValorPrevisto($nValorPrevisto) {
    $this->nValorPrevisto = $nValorPrevisto;
  }

  /**
   * @return float
   */
  public function getValorPrevisto() {
    return $this->nValorPrevisto;
  }

  /**
   * Retorna o valor previsto menos o pago
   * @return float
   */
  public function getDiferenca() {

    $nSubtrair = $this->getValorPrevisto();
    if ($this->getValorReestimado() !== null) {
      $nSubtrair = $this->getValorReestimado();
    }
    return ($this->getValorPago() - $nSubtrair);
  }

  /**
   * @param $nValorReestimado
   */
  public function setValorReestimado($nValorReestimado) {
    $this->nValorReestimado = $nValorReestimado;
  }

  /**
   * @return float
   */
  public function getValorReestimado() {
    return $this->nValorReestimado;
  }
}

