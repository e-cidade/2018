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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;

/**
 * Class LinhaAnexoVII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017
 */
class LinhaAnexoVII {

  const PODER_EXECUTIVO    = 0;
  const PODER_LEGISLATIVO  = 1;
  const PODER_JUDICIARIO   = 2;
  const MINISTERIO_PUBLICO = 3;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var float
   */
  private $nProcessadoExerciciosAnteriores = 0;

  /**
   * @var float
   */
  private $nProcessadoExercicioAnterior = 0;

  /**
   * @var float
   */
  private $nProcessadoPago = 0;

  /**
   * @var float
   */
  private $nProcessadoCancelado = 0;

  /**
   * @var float
   */
  private $nProcessadoSaldo = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoExerciciosAnteriores = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoExercicioAnterior = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoLiquidado = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoPago = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoCancelado = 0;

  /**
   * @var float
   */
  private $nNaoProcessadoSaldo = 0;

  /**
   * @var float
   */
  private $nSaldoTotal = 0;

  /**
   * @var LinhaAnexoVII[]
   */
  private $aLinhas = array();

  /**
   * Tipo do poder
   * @var integer
   */
  private $iTipo;

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return float
   */
  public function getValorProcessadoEmExerciciosAnteriores() {
    return $this->nProcessadoExerciciosAnteriores;
  }

  /**
   * @param float $nProcessadoExerciciosAnteriores
   */
  public function setValorProcessadoEmExerciciosAnteriores($nProcessadoExerciciosAnteriores) {
    $this->nProcessadoExerciciosAnteriores = $nProcessadoExerciciosAnteriores;
  }

  /**
   * @return float
   */
  public function getValorProcessadoNoExercicioAnterior() {
    return $this->nProcessadoExercicioAnterior;
  }

  /**
   * @param float $nProcessadoExercicioAnterior
   */
  public function setValorProcessadoNoExercicioAnterior($nProcessadoExercicioAnterior) {
    $this->nProcessadoExercicioAnterior = $nProcessadoExercicioAnterior;
  }

  /**
   * @return float
   */
  public function getValorPagoProcessado() {
    return $this->nProcessadoPago;
  }

  /**
   * @param float $nProcessadoPago
   */
  public function setValorPagoProcessado($nProcessadoPago) {
    $this->nProcessadoPago = $nProcessadoPago;
  }

  /**
   * @return float
   */
  public function getValorCanceladoProcessado() {
    return $this->nProcessadoCancelado;
  }

  /**
   * @param float $nProcessadoCancelado
   */
  public function setValorCanceladoProcessado($nProcessadoCancelado) {
    $this->nProcessadoCancelado = $nProcessadoCancelado;
  }

  /**
   * @return float
   */
  public function getSaldoProcessado() {
    return $this->nProcessadoSaldo;
  }

  /**
   * @param float $nProcessadoSaldo
   */
  public function setSaldoProcessado($nProcessadoSaldo) {
    $this->nProcessadoSaldo = $nProcessadoSaldo;
  }

  /**
   * @return float
   */
  public function getValorNaoProcessadoEmExerciciosAnteriores() {
    return $this->nNaoProcessadoExerciciosAnteriores;
  }

  /**
   * @param float $nNaoProcessadoExerciciosAnteriores
   */
  public function setValorNaoProcessadoEmExerciciosAnteriores($nNaoProcessadoExerciciosAnteriores) {
    $this->nNaoProcessadoExerciciosAnteriores = $nNaoProcessadoExerciciosAnteriores;
  }

  /**
   * @return float
   */
  public function getValorNaoProcessadoNoExercicioAnterior() {
    return $this->nNaoProcessadoExercicioAnterior;
  }

  /**
   * @param float $nNaoProcessadoExercicioAnterior
   */
  public function setValorNaoProcessadoNoExercicioAnterior($nNaoProcessadoExercicioAnterior) {
    $this->nNaoProcessadoExercicioAnterior = $nNaoProcessadoExercicioAnterior;
  }

  /**
   * @return float
   */
  public function getValorLiquidadoNaoProcessado() {
    return $this->nNaoProcessadoLiquidado;
  }

  /**
   * @param float $nNaoProcessadoLiquidado
   */
  public function setValorLiquidadoNaoProcessado($nNaoProcessadoLiquidado) {
    $this->nNaoProcessadoLiquidado = $nNaoProcessadoLiquidado;
  }

  /**
   * @return float
   */
  public function getValorPagoNaoProcessado() {
    return $this->nNaoProcessadoPago;
  }

  /**
   * @param float $nNaoProcessadoPago
   */
  public function setValorPagoNaoProcessado($nNaoProcessadoPago) {
    $this->nNaoProcessadoPago = $nNaoProcessadoPago;
  }

  /**
   * @return float
   */
  public function getValorCanceladoNaoProcessado() {
    return $this->nNaoProcessadoCancelado;
  }

  /**
   * @param float $nNaoProcessadoCancelado
   */
  public function setValorCanceladoNaoProcessado($nNaoProcessadoCancelado) {
    $this->nNaoProcessadoCancelado = $nNaoProcessadoCancelado;
  }

  /**
   * @return float
   */
  public function getSaldoNaoProcessado() {
    return $this->nNaoProcessadoSaldo;
  }

  /**
   * @param float $nNaoProcessadoSaldo
   */
  public function setSaldoNaoProcessado($nNaoProcessadoSaldo) {
    $this->nNaoProcessadoSaldo = $nNaoProcessadoSaldo;
  }

  /**
   * @param $nSaldoTotal
   */
  public function setSaldoTotal($nSaldoTotal) {
    $this->nSaldoTotal = $nSaldoTotal;
  }

  /**
   * @return float
   */
  public function getSaldoTotal() {
    return $this->nSaldoTotal;
  }

  /**
   * @param LinhaAnexoVII $oLinha
   */
  public function adicionarLinha(LinhaAnexoVII $oLinha) {
    $this->aLinhas[] = $oLinha;
  }

  /**
   * @return LinhaAnexoVII[]
   */
  public function getLinhas() {
    return $this->aLinhas;
  }

  /**
   * Define o tipo de poder da linha
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * @return int
   */
  public function getTipo() {
    return $this->iTipo;
  }
}


