<?php

namespace ECidade\Tributario\Agua\Calculo;

use AguaContrato;
use Abatimento;
use DBException;

class Recalculo {

  /**
   * @var AguaContrato
   */
  private $oContrato;

  /**
   * @var integer
   */
  private $iMes;

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var array
   */
  private $aDebitos;

  /**
   * @var array
   */
  private $aDebitosDetalhados;

  /**
   * @param AguaContrato $oContrato
   * @param integer $iMes
   * @param integer $iAno
   */
  public function __construct(AguaContrato $oContrato, $iMes, $iAno) {

    $this->oContrato = $oContrato;
    $this->iMes = $iMes;
    $this->iAno = $iAno;
  }

  /**
   * @param $rsResultado
   * @return array
   */
  private function getResultados($rsResultado) {

    if (pg_num_rows($rsResultado) > 0) {
      return pg_fetch_all_columns($rsResultado);
    }

    return array();
  }

  /**
   * Retorna todos os débitos do contrato, para o mês e ano de referência.
   *
   * @return array
   * @throws DBException
   */
  public function getDebitos() {

    if (!$this->aDebitos) {

      $sWhere = implode(' and ', array(
        "x22_aguacontrato = {$this->oContrato->getCodigo()}",
        "x22_exerc = {$this->iAno}",
        "x22_mes = {$this->iMes}",
        "x22_numpre is not null",
      ));

      $sSql = "select x22_numpre from aguacalc where {$sWhere}";
      $rsResultado = db_query($sSql);

      if (!$rsResultado) {
        throw new DBException('Não foi possível buscar os débitos do contrato.');
      }

      $this->aDebitos = $this->getResultados($rsResultado);
    }

    return $this->aDebitos;
  }

  /**
   * Caso existam débitos para o contrato no mês/ano de referência, então trata-se de um recálculo.
   *
   * @return boolean
   */
  public function isRecalculo() {

    $aDebitos = $this->getDebitos();
    return !empty($aDebitos);
  }

  /**
   * Retorna débitos com cancelamento processado.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getCancelamentos() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(', ', $this->getDebitos());
    $sJoin = implode(' ', array(
      'inner join cancdebitosreg on k21_codigo = k20_codigo',
      'inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia',
    ));

    $sSql = "select distinct k21_numpre from cancdebitos {$sJoin} where k21_numpre in ({$sDebitos})";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por cancelamentos de débitos.');
    }

    return $this->getResultados($rsResultado);
  }

  /**
   * Retorna débitos totalmente pagos.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getPagamentos() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(', ', $this->getDebitos());
    $sSql = "select distinct k00_numpre from arrepaga where k00_numpre in ({$sDebitos})";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por pagamentos de débitos.');
    }

    return $this->getResultados($rsResultado);
  }

  /**
   * Retorna débitos com pagamento parcial.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getPagamentosParciais() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(', ', $this->getDebitos());
    $sJoin = implode(' ', array(
      'inner join abatimentoarreckey on k128_arreckey = k00_sequencial',
      'inner join abatimento on k128_abatimento = k125_sequencial',
      'inner join tipoabatimento on k126_sequencial = k125_tipoabatimento',
    ));

    $sWhere = implode(' and ', array(
      'tipoabatimento.k126_sequencial = ' . Abatimento::TIPO_PAGAMENTO_PARCIAL,
      "arreckey.k00_numpre in ({$sDebitos})",
    ));

    $sSql = "select distinct arreckey.k00_numpre from arreckey {$sJoin} where {$sWhere}";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por pagamentos parciais de débitos.');
    }

    return $this->getResultados($rsResultado);
  }

  /**
   * Retorna débitos com suspensão não finalizada.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getSuspensoes() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(',', $this->getDebitos());
    $sJoin = implode(' ', array(
      'inner join suspensao on arresusp.k00_suspensao = suspensao.ar18_sequencial',
      'left join suspensaofinaliza on ar19_suspensao = ar18_sequencial',
    ));

    $sWhere = implode(' and ', array(
      "k00_numpre in ({$sDebitos})",
      "suspensaofinaliza.ar19_sequencial is null",
    ));

    $sSql = "select distinct k00_numpre as debito_suspenso from arresusp {$sJoin} where {$sWhere}";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por suspensões de débitos.');
    }

    return $this->getResultados($rsResultado);
  }

  /**
   * Retorna todos os detalhes dos débitos do contrato, para o mês e ano de referência.
   *
   * @return array
   * @throws DBException
   */
  public function getDebitosDetalhados() {

    if (!$this->aDebitosDetalhados) {

      $sWhere = implode(' and ', array(
        "x22_aguacontrato = {$this->oContrato->getCodigo()}",
        "x22_exerc = {$this->iAno}",
        "x22_mes = {$this->iMes}",
        'x22_numpre is not null',
      ));

      $sJoin = "inner join arrecad on arrecad.k00_numpre = aguacalc.x22_numpre";

      $sSql = " select arrecad.* from aguacalc {$sJoin} where {$sWhere}";
      $rsResultado = db_query($sSql);

      if (!$rsResultado) {
        throw new DBException('Não foi possível buscar os débitos do contrato.');
      }

      $this->aDebitosDetalhados = pg_fetch_all($rsResultado);
    }

    return $this->aDebitosDetalhados;

  }

  /**
   * Retorna débitos com desconto de abatimento.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getDescontos() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(', ', $this->getDebitos());
    $sWhere = implode(' and ', array(
      'tipoabatimento.k126_sequencial = ' . Abatimento::TIPO_DESCONTO,
      "arreckey.k00_numpre in ({$sDebitos})",
    ));

    $sJoin = implode(' ', array(
      'inner join abatimentoarreckey on k128_arreckey = k00_sequencial',
      'inner join abatimento on k128_abatimento = k125_sequencial',
      'inner join tipoabatimento on k126_sequencial = k125_tipoabatimento',
    ));

    $sSql = "select distinct arreckey.k00_numpre from arreckey {$sJoin} where {$sWhere}";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por descontos de abatimento de débitos.');
    }

    return $this->getResultados($rsResultado);
  }

  /**
   * Retorna débitos com compensacao de credito.
   *
   * @return array|bool
   * @throws DBException
   */
  public function getCompensacoes() {

    if (!$this->getDebitos()) {
      return false;
    }

    $sDebitos = implode(', ', $this->getDebitos());

    $sJoin = implode(' ', array(
      'inner join abatimentoarreckey on k128_arreckey = k00_sequencial',
      'inner join abatimento on k128_abatimento = k125_sequencial',
      'inner join tipoabatimento on k126_sequencial = k125_tipoabatimento',
    ));

    $sWhere = implode(' and ', array(
      'tipoabatimento.k126_sequencial = ' . Abatimento::TIPO_COMPENSACAO,
      "arreckey.k00_numpre in ({$sDebitos})",
    ));

    $sSql = "select distinct arreckey.k00_numpre from arreckey {$sJoin} where {$sWhere}";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar por descontos de abatimento de débitos.');
    }

    return $this->getResultados($rsResultado);
  }
}
