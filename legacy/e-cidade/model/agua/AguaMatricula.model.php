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

class AguaMatricula {

  /**
   * @var integer
   */
  private $iMatricula;

  /**
   * @var integer
   */
  private $iCodigoRua;

  /**
   * @var integer
   */
  private $iCodigoBairro;

  /**
   * @var integer
   */
  private $iNumero;

  /**
   * @var integer
   */
  private $iCodigoProprietario;

  /**
   * @var CgmBase
   */
  private $oProprietario;

  /**
   * @var integer
   */
  private $iCodigoPromitente;

  /**
   * @var CgmBase
   */
  private $oPromitente;
  /**
   * @var integer
   */
  private $iCodigoDistrito;

  /**
   * @var integer
   */
  private $iCodigoZonaFiscal;

  /**
   * @var DBDate
   */
  private $oDataCadastro;

  /**
   * @var string
   */
  private $sOrientacao;

  /**
   * @var integer
   */
  private $iQuadra;

  /**
   * @var integer
   */
  private $iCodigoZonaEntrega;

  /**
   * @var integer
   */
  private $iCodigoRota;

  /**
   * @var string
   */
  private $sLetraLocalizacao;

  /**
   * @var integer
   */
  private $iQuantidadeEconomias;

  /**
   * @var boolean
   */
  private $lMultieconomias;

  /**
   * @var integer
   */
  private $iQuantidadePontos;

  /**
   * @var string
   */
  private $sObservacoes;

  /**
   * AguaBase constructor.
   * @param integer $iMatricula
   * @throws DBException
   */
  public function __construct($iMatricula = null) {

    $this->iMatricula = $iMatricula;

    if ($this->iMatricula) {
      $oDaoAguaBase = new cl_aguabase;
      $sSqlAguaBase = $oDaoAguaBase->sql_query_file($this->iMatricula);
      $rsAguaBase = $oDaoAguaBase->sql_record($sSqlAguaBase);

      if (!$rsAguaBase && $oDaoAguaBase->numrows == 0) {
        throw new DBException("Não foi possível encontrar as informações da Matrícula informada.");
      }

      $oAguaBase = db_utils::fieldsMemory($rsAguaBase, 0);

      $this->iNumero              = $oAguaBase->x01_numero;
      $this->iCodigoRua           = $oAguaBase->x01_codrua;
      $this->iCodigoRota          = $oAguaBase->x01_rota;
      $this->iCodigoBairro        = $oAguaBase->x01_codbairro;
      $this->iCodigoDistrito      = $oAguaBase->x01_distrito;
      $this->iCodigoPromitente    = $oAguaBase->x01_promit;
      $this->iQuantidadePontos    = $oAguaBase->x01_qtdponto;
      $this->iCodigoZonaFiscal    = $oAguaBase->x01_zona;
      $this->iCodigoZonaEntrega   = $oAguaBase->x01_entrega;
      $this->iCodigoProprietario  = $oAguaBase->x01_numcgm;
      $this->iQuantidadeEconomias = $oAguaBase->x01_qtdeconomia;
      $this->sObservacoes         = $oAguaBase->x01_obs;
      $this->sLetraLocalizacao    = $oAguaBase->x01_letra;
      $this->sOrientacao          = $oAguaBase->x01_orientacao;
      $this->iQuadra              = $oAguaBase->x01_quadra;
      $this->lMultieconomias      = $oAguaBase->x01_multiplicador == 't' ? true : false;

      if ($oAguaBase->x01_dtcadastro) {
        $this->oDataCadastro = new DBDate($oAguaBase->x01_dtcadastro);
      }
    }
  }

  /**
   * @return int|null
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaBase = new cl_aguabase;
    $oDaoAguaBase->x01_numero        = $this->iNumero;
    $oDaoAguaBase->x01_codrua        = $this->iCodigoRua;
    $oDaoAguaBase->x01_rota          = $this->iCodigoRota;
    $oDaoAguaBase->x01_codbairro     = $this->iCodigoBairro;
    $oDaoAguaBase->x01_distrito      = $this->iCodigoDistrito;
    $oDaoAguaBase->x01_promit        = $this->iCodigoPromitente;
    $oDaoAguaBase->x01_qtdponto      = $this->iQuantidadePontos;
    $oDaoAguaBase->x01_zona          = $this->iCodigoZonaFiscal;
    $oDaoAguaBase->x01_entrega       = $this->iCodigoZonaEntrega;
    $oDaoAguaBase->x01_numcgm        = $this->iCodigoProprietario;
    $oDaoAguaBase->x01_qtdeconomia   = $this->iQuantidadeEconomias;
    $oDaoAguaBase->x01_obs           = pg_escape_string($this->sObservacoes);
    $oDaoAguaBase->x01_letra         = pg_escape_string($this->sLetraLocalizacao);
    $oDaoAguaBase->x01_orientacao    = pg_escape_string($this->sOrientacao);
    $oDaoAguaBase->x01_quadra        = $this->iQuadra;
    $oDaoAguaBase->x01_multiplicador = $this->lMultieconomias == true ? 't' : 'f';

    if ($this->oDataCadastro) {
      $oDaoAguaBase->x01_dtcadastro = $this->oDataCadastro->getDate();
    }

    if ($this->iMatricula) {

      $oDaoAguaBase->x01_matric = $this->iMatricula;
      $oDaoAguaBase->alterar($this->iMatricula);
    }

    if (!$this->iMatricula) {

      $oDaoAguaBase->incluir(null);
      $this->iMatricula = $oDaoAguaBase->x01_matric;
    }

    if ($oDaoAguaBase->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações da Matrícula.");
    }

    return $this->iMatricula;
  }

  /**
   * @return int
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * @param int $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * @return int
   */
  public function getCodigoRua() {
    return $this->iCodigoRua;
  }

  /**
   * @param int $iCodigoRua
   */
  public function setCodigoRua($iCodigoRua) {
    $this->iCodigoRua = $iCodigoRua;
  }

  /**
   * @return int
   */
  public function getCodigoBairro() {
    return $this->iCodigoBairro;
  }

  /**
   * @param int $iCodigoBairro
   */
  public function setCodigoBairro($iCodigoBairro) {
    $this->iCodigoBairro = $iCodigoBairro;
  }

  /**
   * @return int
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * @param int $iNumero
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * @return int
   */
  public function getCodigoProprietario() {
    return $this->iCodigoProprietario;
  }

  /**
   * @param int $iCodigoProprietario
   */
  public function setCodigoProprietario($iCodigoProprietario) {
    $this->iCodigoProprietario = $iCodigoProprietario;
  }

  /**
   * @return CgmBase
   */
  public function getProprietario() {

    if (!$this->oProprietario) {
      $this->oProprietario = CgmRepository::getByCodigo($this->iCodigoProprietario);
    }

    return $this->oProprietario;
  }

  /**
   * @return int
   */
  public function getCodigoPromitente() {
    return $this->iCodigoPromitente;
  }

  /**
   * @param int $iCodigoPromitente
   */
  public function setCodigoPromitente($iCodigoPromitente) {
    $this->iCodigoPromitente = $iCodigoPromitente;
  }

  /**
   * @return CgmBase
   */
  public function getPromitente() {

    if (!$this->oPromitente) {
      $this->oPromitente = CgmRepository::getByCodigo($this->iCodigoPromitente);
    }

    return $this->oPromitente;
  }

  /**
   * @return int
   */
  public function getCodigoDistrito() {
    return $this->iCodigoDistrito;
  }

  /**
   * @param int $iCodigoDistrito
   */
  public function setCodigoDistrito($iCodigoDistrito) {
    $this->iCodigoDistrito = $iCodigoDistrito;
  }

  /**
   * @return int
   */
  public function getCodigoZonaFiscal() {
    return $this->iCodigoZonaFiscal;
  }

  /**
   * @param int $iCodigoZonaFiscal
   */
  public function setCodigoZonaFiscal($iCodigoZonaFiscal) {
    $this->iCodigoZonaFiscal = $iCodigoZonaFiscal;
  }

  /**
   * @return DBDate
   */
  public function getDataCadastro() {
    return $this->oDataCadastro;
  }

  /**
   * @param DBDate $oDataCadastro
   */
  public function setDataCadastro(DBDate $oDataCadastro) {
    $this->oDataCadastro = $oDataCadastro;
  }

  /**
   * @return string
   */
  public function getOrientacao() {
    return $this->sOrientacao;
  }

  /**
   * @param string $sOrientacao
   */
  public function setOrientacao($sOrientacao) {
    $this->sOrientacao = $sOrientacao;
  }

  /**
   * @return int
   */
  public function getQuadra() {
    return $this->iQuadra;
  }

  /**
   * @param int $iQuadra
   */
  public function setQuadra($iQuadra) {
    $this->iQuadra = $iQuadra;
  }

  /**
   * @return int
   */
  public function getCodigoZonaEntrega() {
    return $this->iCodigoZonaEntrega;
  }

  /**
   * @param int $iCodigoZonaEntrega
   */
  public function setCodigoZonaEntrega($iCodigoZonaEntrega) {
    $this->iCodigoZonaEntrega = $iCodigoZonaEntrega;
  }

  /**
   * @return int
   */
  public function getCodigoRota() {
    return $this->iCodigoRota;
  }

  /**
   * @param int $iCodigoRota
   */
  public function setCodigoRota($iCodigoRota) {
    $this->iCodigoRota = $iCodigoRota;
  }

  /**
   * @return string
   */
  public function getLetraLocalizacao() {
    return $this->sLetraLocalizacao;
  }

  /**
   * @param string $sLetraLocalizacao
   */
  public function setLetraLocalizacao($sLetraLocalizacao) {
    $this->sLetraLocalizacao = $sLetraLocalizacao;
  }

  /**
   * @return int
   */
  public function getQuantidadeEconomias() {
    return $this->iQuantidadeEconomias;
  }

  /**
   * @param int $iQuantidadeEconomias
   */
  public function setQuantidadeEconomias($iQuantidadeEconomias) {
    $this->iQuantidadeEconomias = $iQuantidadeEconomias;
  }

  /**
   * @return boolean
   */
  public function isMultieconomias() {
    return $this->lMultieconomias;
  }

  /**
   * @param boolean $lMultieconomias
   */
  public function setMultieconomias($lMultieconomias) {
    $this->lMultieconomias = (boolean) $lMultieconomias;
  }

  /**
   * @return int
   */
  public function getQuantidadePontos() {
    return $this->iQuantidadePontos;
  }

  /**
   * @param int $iQuantidadePontos
   */
  public function setQuantidadePontos($iQuantidadePontos) {
    $this->iQuantidadePontos = $iQuantidadePontos;
  }

  /**
   * @return string
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * @param string $sObservacoes
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }
}