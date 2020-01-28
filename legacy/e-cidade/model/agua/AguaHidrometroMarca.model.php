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

class AguaHidrometroMarca {

  /**
   * @var integer Código
   */
  private $iCodigo;

  /**
   * @var string Nome
   */
  private $sNome;

  /**
   * @var string Sigla
   */
  private $sSigla;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * @return string
   */
  public function getSigla() {
    return $this->sSigla;
  }

  /**
   * @param string $sSigla
   */
  public function setSigla($sSigla) {
    $this->sSigla = $sSigla;
  }

  /**
   * AguaHidrometroMarca constructor.
   * @param null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if ($this->iCodigo === null) {
      return;
    }

    $oDaoAguaHidroMarca = new cl_aguahidromarca();
    $sSqlAguaHidroMarca = $oDaoAguaHidroMarca->sql_query_file($this->getCodigo());
    $rsAguaHidroMarca   = db_query($sSqlAguaHidroMarca);

    if (!$rsAguaHidroMarca || pg_num_rows($rsAguaHidroMarca) == 0) {
      throw new DBException("Não foi possível encontrar a Marca.");
    }

    $oHidrometroMarca = db_utils::fieldsMemory($rsAguaHidroMarca, 0);

    $this->sNome  = $oHidrometroMarca->x03_nomemarca;
    $this->sSigla = $oHidrometroMarca->x03_sigla;
  }

  /**
   * @return int
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaHidroMarca = new cl_aguahidromarca();
    $oDaoAguaHidroMarca->x03_codmarca  = $this->getCodigo();
    $oDaoAguaHidroMarca->x03_nomemarca = $this->getNome();
    $oDaoAguaHidroMarca->x03_sigla     = $this->getSigla();

    if ($this->getCodigo() != null) {
      $oDaoAguaHidroMarca->alterar($this->getCodigo());
    }

    if ($this->getCodigo() === null) {
      $oDaoAguaHidroMarca->incluir(null);
    }

    if ($oDaoAguaHidroMarca->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações da Marca.");
    }

    $this->setCodigo($oDaoAguaHidroMarca->x03_codmarca);

    return $this->iCodigo;
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if ($this->getCodigo() === null) {
      throw new ParameterException("Código da Marca não informado.");
    }

    $oDaoAguaHidroMarca = new cl_aguahidromarca();
    $oDaoAguaHidroMarca->excluir($this->getCodigo());

    if ($oDaoAguaHidroMarca->erro_status == '0') {
      throw new DBException("Não foi possível excluir a Marca.");
    }

    return true;
  }
}