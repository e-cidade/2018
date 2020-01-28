<?php

namespace ECidade\Tributario\Agua\Repository;

use BusinessException;
use cl_aguacalc;
use cl_aguacalcval;
use DateTime;
use DBException;
use ECidade\Tributario\Agua\Entity\Calculo\Calculo as CalculoEntity;
use ECidade\Tributario\Agua\Entity\Calculo\Valor as ValorEntity;

class Calculo {

  /**
   * @var cl_aguacalc
   */
  private $oDaoCalculo;

  /**
   * @var cl_aguacalcval
   */
  private $oDaoValores;

  /**
   * @param cl_aguacalc $oDaoCalculo
   */
  public function setDaoCalculo(cl_aguacalc $oDaoCalculo) {
    $this->oDaoCalculo = $oDaoCalculo;
  }

  /**
   * @param cl_aguacalcval $oDaoValores
   */
  public function setDaoValores(cl_aguacalcval $oDaoValores) {
    $this->oDaoValores = $oDaoValores;
  }

  /**
   * @return cl_aguacalc
   */
  public function getDaoCalculo() {

    if (!$this->oDaoCalculo) {
      $this->oDaoCalculo = new cl_aguacalc();
    }
    return clone $this->oDaoCalculo;
  }

  /**
   * @return cl_aguacalcval
   */
  public function getDaoValores() {

    if (!$this->oDaoValores) {
      $this->oDaoValores = new cl_aguacalcval();
    }
    return clone $this->oDaoValores;
  }

  /**
   * Encontra os valores do cálculo a partir do código informado.
   *
   * @param integer $iCodigo
   *
   * @return ValorEntity[]
   * @throws DBException
   */
  public function findValores($iCodigo) {

    $sSqlLinhasValores = $this->getDaoValores()->sql_query_file(null, null, "*", null, "x23_codcalc = {$iCodigo}");
    $rsLinhasValores = db_query($sSqlLinhasValores);
    if (!$rsLinhasValores) {
      throw new DBException('Não foi possível consultar os valores.');
    }

    $iQuantidadeValores = pg_num_rows($rsLinhasValores);
    $aValores = array();
    for ($iValor = 0; $iValor < $iQuantidadeValores; $iValor++) {

      $oLinhaValor = pg_fetch_object($rsLinhasValores, $iValor);

      $oValor = new ValorEntity;
      $oValor->setCodigoCalculo($oLinhaValor->x23_codcalc);
      $oValor->setCodigoTipoConsumo($oLinhaValor->x23_codconsumotipo);
      $oValor->setValor($oLinhaValor->x23_valor);

      $aValores[] = $oValor;
    }

    return $aValores;
  }

  /**
   * @param resource $rsLinhaCalculo
   * @param int $iLinha
   *
   * @return CalculoEntity
   */
  private function hydrate($rsLinhaCalculo, $iLinha = 0) {

    $oLinhaCalculo = pg_fetch_object($rsLinhaCalculo, $iLinha);

    $oCalculo = new CalculoEntity;
    $oCalculo->setCodigo($oLinhaCalculo->x22_codcalc);
    $oCalculo->setCodigoConsumo($oLinhaCalculo->x22_codconsumo);
    $oCalculo->setCodigoMatricula($oLinhaCalculo->x22_matric);
    $oCalculo->setCodigoContrato($oLinhaCalculo->x22_aguacontrato);
    $oCalculo->setCodigoEconomia($oLinhaCalculo->x22_aguacontratoeconomia);
    $oCalculo->setCodigoProcessamento($oLinhaCalculo->x22_numpre);
    $oCalculo->setCodigoUsuario($oLinhaCalculo->x22_usuario);
    $oCalculo->setExercicio($oLinhaCalculo->x22_exerc);
    $oCalculo->setMes($oLinhaCalculo->x22_mes);
    $oCalculo->setArea($oLinhaCalculo->x22_area);
    $oCalculo->setManual($oLinhaCalculo->x22_manual);
    $oCalculo->setTipo($oLinhaCalculo->x22_tipo);
    $oCalculo->setHora($oLinhaCalculo->x22_hora);
    $oCalculo->setData(new DateTime($oLinhaCalculo->x22_data));

    return $oCalculo;
  }

  /**
   * Busca a entidade a partir dos filtros, ordenação, limite e offset informados.
   *
   * @param array $aWhere
   * @param array $sOrder
   * @param integer $iLimit
   * @param integer $iOffset
   *
   * @return CalculoEntity[]
   * @throws DBException
   */
  public function findBy(array $aWhere, $sOrder = null, $iLimit = null, $iOffset = null) {

    $sWhere = implode(' and ', $aWhere);
    $sLimit = $iLimit ? " limit {$iLimit} " : null;
    $sOffset = $iOffset ? " offset {$iOffset} " : null;

    $sSqlLinhaCalculo = $this->getDaoCalculo()->sql_query_file(null, "*", $sOrder, $sWhere)  . $sLimit . $sOffset;
    $rsLinhaCalculo = db_query($sSqlLinhaCalculo);
    if (!$rsLinhaCalculo) {
      throw new DBException('Não foi possível fazer a consulta.');
    }

    $iQuantidadeResultados = pg_num_rows($rsLinhaCalculo);
    $aResultados = array();
    for ($iLinha = 0; $iLinha < $iQuantidadeResultados; $iLinha++) {
      $aResultados[] = $this->hydrate($rsLinhaCalculo, $iLinha);
    }

    return $aResultados;
  }

  /**
   * Busca a primeira entidade a partir dos filtros, ordenação e offset informados.
   *
   * @param array $aWhere
   * @param string $sOrder
   * @param integer $iOffset
   *
   * @return CalculoEntity|null
   */
  public function findOneBy(array $aWhere, $sOrder = null, $iOffset = null) {

    $aCalculos = $this->findBy($aWhere, $sOrder, $iLimit = 1, $iOffset);
    if ($aCalculos) {
      return current($aCalculos);
    }

    return null;
  }

  /**
   * Busca a entidade a partir do código informado.
   *
   * @param integer $iCodigo
   *
   * @return CalculoEntity
   * @throws \Exception
   */
  public function find($iCodigo) {

    $aResults = $this->findBy(array("x22_codcalc = {$iCodigo}"), null, 1);
    if ($aResults) {
      return current($aResults);
    }

    return null;
  }

  /**
   * Apaga a entidade.
   *
   * @param integer $iCodigo
   *
   * @return boolean
   * @throws DBException
   */
  public function delete($iCodigo) {

    $oDaoValores = $this->getDaoValores();
    $oDaoValores->excluir($iCodigo);
    if ($oDaoValores->erro_status == '0') {
      throw new DBException('Não foi possível apagar os valores do cálculo.');
    }

    $oDaoCalculo = $this->getDaoCalculo();
    $oDaoCalculo->excluir($iCodigo);
    if ($oDaoCalculo->erro_status == '0') {
      throw new DBException('Não foi possível apagar o cálculo.');
    }

    return true;
  }

  /**
   * Persiste a entidade.
   *
   * @param CalculoEntity $oCalculo
   *
   * @return CalculoEntity
   * @throws BusinessException
   * @throws DBException
   */
  public function save(CalculoEntity $oCalculo) {

    $oDaoCalculo = $this->getDaoCalculo();
    $oDaoCalculo->x22_codcalc = $oCalculo->getCodigo();
    $oDaoCalculo->x22_exerc = $oCalculo->getExercicio();
    $oDaoCalculo->x22_mes = $oCalculo->getMes();
    $oDaoCalculo->x22_area = $oCalculo->getArea();
    $oDaoCalculo->x22_manual = $oCalculo->getManual();
    $oDaoCalculo->x22_tipo = $oCalculo->getTipo();
    $oDaoCalculo->x22_hora = $oCalculo->getHora();
    $oDaoCalculo->x22_data = $oCalculo->getData()->format('Y-m-d');

    $oDaoCalculo->x22_numpre = "null";
    if ($oCalculo->getCodigoRecibo()) {
      $oDaoCalculo->x22_numpre = $oCalculo->getCodigoRecibo();
    }

    $oDaoCalculo->x22_aguacontrato = "null";
    if ($oCalculo->getCodigoContrato()) {
      $oDaoCalculo->x22_aguacontrato = $oCalculo->getCodigoContrato();
    }

    $oDaoCalculo->x22_aguacontratoeconomia = "null";
    if ($oCalculo->getCodigoEconomia()) {
      $oDaoCalculo->x22_aguacontratoeconomia = $oCalculo->getCodigoEconomia();
    }

    $oDaoCalculo->x22_codconsumo = "null";
    if ($oCalculo->getCodigoConsumo()) {
      $oDaoCalculo->x22_codconsumo = $oCalculo->getCodigoConsumo();
    }

    $oDaoCalculo->x22_matric = "null";
    if ($oCalculo->getCodigoMatricula()) {
      $oDaoCalculo->x22_matric = $oCalculo->getCodigoMatricula();
    }

    $oDaoCalculo->x22_usuario = "null";
    if ($oCalculo->getCodigoUsuario()) {
      $oDaoCalculo->x22_usuario = $oCalculo->getCodigoUsuario();
    }

    if ($oCalculo->getCodigo()) {
      $oDaoCalculo->alterar($oCalculo->getCodigo());
    } else {

      $oDaoCalculo->incluir(null);
      $oCalculo->setCodigo($oDaoCalculo->x22_codcalc);
    }

    if ($oDaoCalculo->erro_status == '0') {
      throw new DBException('Não foi possível salvar o cálculo.');
    }

    if (!$oCalculo->getValores()) {
      throw new BusinessException('Nenhum valor foi informado para o cálculo.');
    }

    $oDaoValores = $this->getDaoValores();
    $oDaoValores->excluir($oCalculo->getCodigo());
    if ($oDaoValores->erro_status == '0') {
      throw new DBException('Não foi possível salvar os valores do cálculo.');
    }

    foreach ($oCalculo->getValores() as $oValor) {

      $oDaoValores = $this->getDaoValores();
      $oDaoValores->x23_codcalc = $oDaoCalculo->x22_codcalc;
      $oDaoValores->x23_codconsumotipo = $oValor->getCodigoTipoConsumo();
      $oDaoValores->x23_valor = $oValor->getValor();
      $oDaoValores->incluir($oDaoCalculo->x22_codcalc, $oValor->getCodigoTipoConsumo());
      if ($oDaoValores->erro_status == '0') {
        throw new DBException('Não foi possível salvar os valores do cálculo.');
      }
    }

    return $oCalculo;
  }
}
