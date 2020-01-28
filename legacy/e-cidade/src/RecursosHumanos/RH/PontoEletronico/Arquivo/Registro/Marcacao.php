<?php
/**
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro;

use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Cabecalho as CabecalhoRegistro;

/**
 * Classe referente a regra de negócio das marcações do ponto eletrônico
 *
 * Class Marcacao
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Marcacao {

  /**
   * @var CabecalhoRegistro
   */
  private $oCabecalho;

  /**
   * @var \DBDate
   */
  private $oData;

  /**
   * @var \DBDate
   */
  private $oDataVinculo;

  /**
   * @var string
   */
  private $sHora = null;

  /**
   * @var string
   */
  private $sPIS;

  /**
   * @var \Servidor
   */
  private $oServidor;

  /**
   * @var null|int
   */
  private $iCodigo = null;

  /**
   * @var int
   */
  private $iMatricula = null;

  /**
   * @var bool
   */
  private $lManual = false;

  /**
   * @return null|int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return Cabecalho
   */
  public function getCabecalho() {
    return $this->oCabecalho;
  }

  /**
   * @return \DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * @return string
   */
  public function getPIS() {
    return $this->sPIS;
  }

  /**
   * @return \Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * @return \DBDate
   */
  public function getDataVinculo() {
    return $this->oDataVinculo;
  }

  /**
   * @return bool
   */
  public function isManual() {
    return $this->lManual;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param Cabecalho $oCabecalho
   */
  public function setCabecalho(CabecalhoRegistro $oCabecalho) {
    $this->oCabecalho = $oCabecalho;
  }

  /**
   * @param \DBDate $oData
   */
  public function setData(\DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * @param string $sPIS
   */
  public function setPIS($sPIS) {
    $this->sPIS = $sPIS;
  }

  /**
   * @param \Servidor $oServidor
   */
  public function setServidor(\Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * @param \DBDate $oDataVinculo
   */
  public function setDataVinculo(\DBDate $oDataVinculo) {
    $this->oDataVinculo = $oDataVinculo;
  }

  /**
   * Define a matrícula do servidor
   * @param integer
   */
  public function setMatricula ($matricula) {
    $this->iMatricula = $matricula;
  }

  /**
   * Retorna a matrícula do servidor
   * @return integer
   */
  public function getMatricula () {
    return $this->iMatricula;
  }

  /**
   * @param bool $lManual
   */
  public function setManual($lManual) {
    $this->lManual = $lManual;
  }
}