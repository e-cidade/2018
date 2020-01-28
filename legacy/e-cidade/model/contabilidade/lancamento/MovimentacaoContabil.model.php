<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Class MovimentacaoContabil
 */
class MovimentacaoContabil {

  /**
   * Conta Contabil
   * @var integer;
   */
  private $iConta;

  /**
   * Saldo anterior da cona
   * @var float
   */
  private $nSaldoAnterior;

  /**
   * Valor a credito da conta
   * @var float
   */
  private $nValorCredito;

  /**
   * Valor a Debito
   * @var Float
   */
  private $nValorDebito;

  /**
   * Saldo final de conta
   * @var float
   */
  private $nSaldoFinal;

  /**
   * Tipo do saldo Final (D = Debito C = Credito)
   * @var string
   *
   */
  private $nTipoSaldo;

  /**
   * @return float
   */
  public function getSaldoAnterior() {
    return $this->nSaldoAnterior;
  }

  /**
   * @param float $nSaldoAnterior
   */
  public function setSaldoAnterior($nSaldoAnterior) {
    $this->nSaldoAnterior = $nSaldoAnterior;
  }

  /**
   * @return float
   */
  public function getSaldoFinal() {
    return $this->nSaldoFinal;
  }

  /**
   * @param float $nSaldoFinal
   */
  public function setSaldoFinal($nSaldoFinal) {
    $this->nSaldoFinal = $nSaldoFinal;
  }

  /**
   * @return string
   */
  public function getTipoSaldo() {
    return $this->nTipoSaldo;
  }

  /**
   * @param string $nTipoSaldo
   */
  public function setTipoSaldo($nTipoSaldo) {
    $this->nTipoSaldo = $nTipoSaldo;
  }

  /**
   * @return float
   */
  public function getValorCredito() {
    return $this->nValorCredito;
  }

  /**
   * @param float $nValorCredito
   */
  public function setValorCredito($nValorCredito) {
    $this->nValorCredito = $nValorCredito;
  }

  /**
   * @return Float
   */
  public function getValorDebito() {
    return $this->nValorDebito;
  }

  /**
   * @param Float $nValorDebito
   */
  public function setValorDebito($nValorDebito) {
    $this->nValorDebito = $nValorDebito;
  }

  /**
   * Retorna o codigo da conta
   * @return integer
   */
  public function getConta() {
    return $this->iConta;
  }

  /**
   * @param integer $iConta
   */
  public function setConta($iConta) {
    $this->iConta = $iConta;
  }
}