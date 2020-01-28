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
 * Class RetiradaVeiculo
 */
class RetiradaVeiculo {

  /**
   * @type integer
   */
  protected $iCodigo;

  /**
   * @type float
   */
  protected $nMedidaRetirada;

  /**
   * @type DBDate
   */
  protected $dtRetirada;

  /**
   * @type string
   */
  protected $tHoraRetirada;

  /**
   * @type string
   */
  protected $sDestino;

  /**
   * @type integer
   */
  protected $iCodigoDepartamento;

  /**
   * @type integer
   */
  protected $iCodigoUsuario;

  /**
   * @type DBDate
   */
  protected $dtDataInclusao;

  /**
   * @type string
   */
  protected $tHoraInclusao;

  /**
   * @type integer
   */
  protected $iCodigoMotorista;

  /**
   * @type VeiculoMotorista
   */
  protected $oMotorista;

  public function __construct() {

  }

  public static function getInstanciaPorCodigo($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException("Código da retirada não informado.");
    }

    $oDaoVeicRetirada  = new cl_veicretirada();
    $sSqlBuscaRetirada = $oDaoVeicRetirada->sql_query_file($iCodigo);
    $rsBuscaRetirada   = $oDaoVeicRetirada->sql_record($sSqlBuscaRetirada);
    if (!empty($oDaoVeicRetirada->erro_banco)) {
      throw new DBException("Não foi possível buscar a retirada com código {$iCodigo}.");
    }

    $oStdRetirada = db_utils::fieldsMemory($rsBuscaRetirada, 0);
    $oRetirada = new RetiradaVeiculo();
    $oRetirada->setCodigo($iCodigo);
    $oRetirada->setCodigoMotorista($oStdRetirada->ve60_veicmotoristas);
    unset($oStdRetirada);
    return $oRetirada;
  }

  /**
   * @return DBDate
   */
  public function getDataInclusao() {
    return $this->dtDataInclusao;
  }

  /**
   * @return DBDate
   */
  public function getDataRetirada() {
    return $this->dtRetirada;
  }

  /**
   * @param DBDate $dtRetirada
   */
  public function setDataRetirada(DBDate $dtRetirada) {
    $this->dtRetirada = $dtRetirada;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Departamento da Retirada
   * @return integer
   */
  public function getCodigoDepartamento() {
    return $this->iCodigoDepartamento;
  }

  /**
   * @param $iCodigo
   */
  public function setCodigoMotorista($iCodigo) {
    $this->iCodigoMotorista = $iCodigo;
  }

  /**
   * @param VeiculoMotorista $oMotorista
   */
  public function setMotorista(VeiculoMotorista $oMotorista) {
    $this->oMotorista = $oMotorista;
  }

  /**
   * @return VeiculoMotorista
   * @throws DBException
   * @throws ParameterException
   */
  public function getMotorista() {

    if (empty($this->oMotorista) && !empty($this->iCodigoMotorista)) {
      $this->setMotorista(VeiculoMotorista::getInstanciaPorCodigo($this->iCodigoMotorista));
    }
    return $this->oMotorista;
  }

  /**
   * @return float
   */
  public function getMedidaRetirada() {
    return $this->nMedidaRetirada;
  }

  /**
   * @param float $nMedidaRetirada
   */
  public function setMedidaRetirada($nMedidaRetirada) {
    $this->nMedidaRetirada = $nMedidaRetirada;
  }

  /**
   * @return string
   */
  public function getDestino() {
    return $this->sDestino;
  }

  /**
   * @param string $sDestino
   */
  public function setDestino($sDestino) {
    $this->sDestino = $sDestino;
  }

  /**
   * @return string
   */
  public function getHoraRetirada() {
    return $this->tHoraRetirada;
  }

  /**
   * @param string $tHoraRetirada
   */
  public function setHoraRetirada($tHoraRetirada) {
    $this->tHoraRetirada = $tHoraRetirada;
  }
}