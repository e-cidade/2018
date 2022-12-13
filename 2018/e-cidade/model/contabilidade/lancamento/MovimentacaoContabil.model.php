<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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