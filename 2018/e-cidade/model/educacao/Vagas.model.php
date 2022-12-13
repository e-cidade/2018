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
 * Informacoes de vagas
 * @package   Educacao
 * @author    Eduardo Sirangelo - eduardo.sirangelo@gmail.com
 * @version   $Revision: 1.3 $
 */
class Vagas {

  /**
   * Codigo da vaga
   * @var integer
   */
  private $iCodigo;
  /**
   * Codigo da fase
   * @var integer
   */
  private $iFase;
  /**
   * Codigo da escola
   * @var integer
   */
  private $iEscola;
  /**
   * Codigo do ensino
   * @var integer
   */
  private $iEnsino;
  /**
   * Codigo da serie
   * @var integer
   */
  private $iSerie;
  /**
   * Codigo do turno
   * @var integer
   */
  private $iTurno;
  /**
   * Numero de vagas
   * @var integer
   */
  private $iNumVagas;
  /**
   * Saldo de vagas
   * @var integer
   */
  private $iSaldoVagas;

  public function __construct($iCodigo = null) {

    if ( !empty($iCodigo)) {

      $oDaoVaga = db_utils::getDao('vagas');
      $sSqlVaga = $oDaoVaga->sql_query_file($iCodigo);
      $rsVaga   = $oDaoVaga->sql_record($sSqlVaga);

      if ($oDaoVaga->numrows > 0) {

        $oDadosVaga        = db_utils::fieldsMemory($rsVaga, 0);

        $this->iCodigo      = $oDadosVaga->mo10_codigo;
        $this->iFase        = $oDadosVaga->mo10_fase;
        $this->iEscola      = $oDadosVaga->mo10_escola;
        $this->iEnsino      = $oDadosVaga->mo10_ensino;
        $this->iSerie       = $oDadosVaga->mo10_serie;
        $this->iTurno       = $oDadosVaga->mo10_turno;
        $this->iNumVagas    = $oDadosVaga->mo10_numvagas;
        $this->iSaldoVagas  = $oDadosVaga->mo10_saldovagas;
      }
    }
  }

  /**
   * Seta o codigo da vaga
   * @param integer
   */
  public function setCodigoVaga ($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o codigo da vaga
   * @return integer
   */
  public function getCodigoVaga () {
    return $this->iCodigo;
  }

  /**
   * Seta a fase
   * @param integer
   */
  public function setFase ($iFase) {
    $this->iFase = $iFase;
  }

  /**
   * Retorna a fase
   * @return integer
   */
  public function getFase () {
    return $this->iFase;
  }

  /**
   * Seta a escola
   * @param integer
   */
  public function setEscola ($iEscola) {
    $this->iEscola = $iEscola;
  }

  /**
   * Retorna a escola
   * @return integer
   */
  public function getEscola () {
    return $this->iEscola;
  }

  /**
   * Seta o ensino
   * @param integer
   */
  public function setEnsino ($iEnsino) {
    $this->iEnsino = $iEnsino;
  }

  /**
   * Retorna o ensino
   * @return integer
   */
  public function getEnsino () {
    return $this->iEnsino;
  }

  /**
   * Seta a serie
   * @param integer
   */
  public function setSerie ($iSerie) {
    $this->iSerie = $iSerie;
  }

  /**
   * Retorna a serie
   * @return integer
   */
  public function getSerie () {
    return $this->iSerie;
  }

  /**
   * Seta o turno
   * @param integer
   */
  public function setTurno ($iTurno) {
    $this->iTurno = $iTurno;
  }

  /**
   * Retorna o turno
   * @return integer
   */
  public function getTurno () {
    return $this->iTurno;
  }

  /**
   * Seta o numero de vagas
   * @param integer
   */
  public function setNumVagas ($iNumVagas) {
    $this->iNumVagas = $iNumVagas;
  }

  /**
   * Retorna o numero de vagas
   * @return integer
   */
  public function getNumVagas () {
    return $this->iNumVagas;
  }

  /**
   * Seta o saldo de vagas
   * @param integer
   */
  public function setSaldoVagas ($iSaldoVagas) {
    $this->iSaldoVagas = $iSaldoVagas;
  }

  /**
   * Retorna o saldo de vagas
   * @return integer
   */
  public function getSaldoVagas () {
    return $this->iSaldoVagas;
  }

  public function salvar() {

    $oDaoVaga = db_utils::getDao('vagas');

    $oDaoVaga->mo10_codigo     = $this->iCodigo;
    $oDaoVaga->mo10_fase       = $this->iFase;
    $oDaoVaga->mo10_escola     = $this->iEscola;
    $oDaoVaga->mo10_ensino     = $this->iEnsino;
    $oDaoVaga->mo10_serie      = $this->iSerie;
    $oDaoVaga->mo10_turno      = $this->iTurno;
    $oDaoVaga->mo10_numvagas   = $this->iNumVagas;
    $oDaoVaga->mo10_saldovagas = $this->iSaldoVagas;

    if ( empty($this->iCodigo)) {
      $oDaoVaga->incluir(null);
    } else {
      $oDaoVaga->alterar($this->iCodigo);
    }

    if ( $oDaoVaga->erro_status == "0" ) {
      throw new DBException("Erro ao salvar dados de Vagas");
    }

    $this->setCodigoVaga($oDaoVaga->mo10_codigo);

    return true;
  }
}