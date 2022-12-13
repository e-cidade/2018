<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
 * Class ArquivoConsignadoManualParcela
 */
class ArquivoConsignadoManualParcela {

  private $codigo;

  private $valor = 0;

  private $valorDescontado = 0;

  private $motivo = 0;

  private $processado = false;

  /**
   * @var DBCompetencia
   */
  private $competencia;

  /**
   * @var Rubrica
   */
  private $rubrica;

  /**
   * @var Servidor
   */
  private $servidor;

  private $parcela;

  /**
   * @var integer
   */
  private $totalParcela;

  /**
   * @var integer
   */
  private $codigoMovimentoServidor;


  /**
   * @var integer
   */
  private $codigoConsignado;

  /**
   * @var integer
   */
  private $codigoMovimentoRubrica;

  /**
   * @return mixed
   */
  public function getCodigo() {

    return $this->codigo;
  }

  /**
   * @param mixed $codigo
   */
  public function setCodigo($codigo) {

    $this->codigo = $codigo;
  }

  /**
   * @return int
   */
  public function getValor() {

    return $this->valor;
  }

  /**
   * @param int $valor
   */
  public function setValor($valor) {

    $this->valor = $valor;
  }

  /**
   * @return int
   */
  public function getValorDescontado() {

    return $this->valorDescontado;
  }

  /**
   * @param int $valorDescontado
   */
  public function setValorDescontado($valorDescontado) {

    $this->valorDescontado = $valorDescontado;
  }

  /**
   * @return int
   */
  public function getMotivo() {

    return $this->motivo;
  }

  /**
   * @param int $motivo
   */
  public function setMotivo($motivo) {

    $this->motivo = $motivo;
  }

  /**
   * @return boolean
   */
  public function isProcessado() {

    return $this->processado;
  }

  /**
   * @param boolean $processado
   */
  public function setProcessado($processado) {

    $this->processado = $processado;
  }

  /**
   * @return Rubrica
   */
  public function getRubrica() {

    return $this->rubrica;
  }

  /**
   * @param Rubrica $rubrica
   */
  public function setRubrica(Rubrica $rubrica) {

    $this->rubrica = $rubrica;
  }

  /**
   * @return Servidor
   */
  public function getServidor() {

    return $this->servidor;
  }

  /**
   * @param Servidor $servidor
   */
  public function setServidor(Servidor $servidor) {

    $this->servidor = $servidor;
  }

  /**
   * @return mixed
   */
  public function getParcela() {

    return $this->parcela;
  }

  /**
   * @param mixed $parcela
   */
  public function setParcela($parcela) {

    $this->parcela = $parcela;
  }

  /**
   * @return mixed
   */
  public function getTotalDeParcelas() {

    return $this->totalParcela;
  }

  /**
   * @param mixed $total
   */
  public function setTotalDeParcelas($total) {

    $this->totalParcela = $total;
  }

  /**
   * @return \DBCompetencia
   */
  public function getCompetencia() {

    return $this->competencia;
  }

  /**
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia($competencia) {

    $this->competencia = $competencia;
  }

  /**
   * @return int
   */
  public function getCodigoMovimentoServidor() {

    return $this->codigoMovimentoServidor;
  }

  /**
   * @param int $codigoMovimentoServidor
   */
  public function setCodigoMovimentoServidor($codigoMovimentoServidor) {
    $this->codigoMovimentoServidor = $codigoMovimentoServidor;
  }

  /**
   * @return int
   */
  public function getCodigoMovimentoRubrica() {
    return $this->codigoMovimentoRubrica;
  }

  /**
   * @param int $codigoMovimentoRubrica
   */
  public function setCodigoMovimentoRubrica($codigoMovimentoRubrica) {
    $this->codigoMovimentoRubrica = $codigoMovimentoRubrica;
  }

  /**
   * @return int
   */
  public function getCodigoConsignado() {
    return $this->codigoConsignado;
  }

  /**
   * @param int $codigoConsignado
   */
  public function setCodigoConsignado($codigoConsignado) {
    $this->codigoConsignado = $codigoConsignado;
  }

  /**
   * @return \ArquivoConsignadoManual
   */
  public function getConsignado() {
    return ArquivoConsignadoManualRepository::getByCodigo($this->codigoConsignado);
  }

}