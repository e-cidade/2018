<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao;

class ParametrosLotacao {

  private $iCodigoSequencial;
  private $iTolerancia;
  private $sHoraExtra50;
  private $sHoraExtra75;
  private $sHoraExtra100;
  private $iCodigoLotacao;
  private $oSupervisor;

  function __construct ($iCodigoSequencial = null, $iTolerancia = null, $sHoraExtra50 = null, $sHoraExtra75 = null, $sHoraExtra100 = null, $iCodigoLotacao = null, $oSupervisor = null) {

    $this->iCodigoSequencial = $iCodigoSequencial;
    $this->iTolerancia       = $iTolerancia;
    $this->sHoraExtra50      = $sHoraExtra50;
    $this->sHoraExtra75      = $sHoraExtra75;
    $this->sHoraExtra100     = $sHoraExtra100;
    $this->iCodigoLotacao    = $iCodigoLotacao;
    $this->oSupervisor       = $oSupervisor;
  }

  public function setCodigo($iCodigoSequencial) {
    $this->iCodigoSequencial = $iCodigoSequencial;
  }

  public function getCodigo() {
    return $this->iCodigoSequencial;
  }

  public function setTolerancia($iTolerancia) {
    $this->iTolerancia = $iTolerancia;
  }

  public function getTolerancia() {
    return $this->iTolerancia;
  }

  public function setHoraExtra50($sHoraExtra50) {
    $this->sHoraExtra50 = $sHoraExtra50;
  }

  public function getHoraExtra50() {
    return $this->sHoraExtra50;
  }

  public function setHoraExtra75($sHoraExtra75) {
    $this->sHoraExtra75 = $sHoraExtra75;
  }

  public function getHoraExtra75() {
    return $this->sHoraExtra75;
  }

  public function setHoraExtra100($sHoraExtra100) {
    $this->sHoraExtra100 = $sHoraExtra100;
  }

  public function getHoraExtra100() {
    return $this->sHoraExtra100;
  }

  public function setCodigoLotacao($iCodigoLotacao) {
    $this->iCodigoLotacao = $iCodigoLotacao;
  }

  public function getCodigoLotacao() {
    return $this->iCodigoLotacao;
  }

  public function setSupervisor($oSupervisor) {
    $this->oSupervisor = $oSupervisor;
  }

  public function getSupervisor() {
    return $this->oSupervisor;    
  }
}
