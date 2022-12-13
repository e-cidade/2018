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
 * Class AguaTipoIsencao
 */
class AguaTipoIsencao {

  const TIPO_NORMAL   = 0;
  const TIPO_IMUNE    = 1;
  const TIPO_DESCONTO = 2;
  const TIPO_IDADE    = 3;

  /**
   * @var integer Código
   */
  private $iCodigo;

  /**
   * @var string Descrição
   */
  private $sDescricao;

  /**
   * @var integer
   */
  private $iTipo;

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
   * @return int
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param int $iTipo
   * @throws ParameterException
   */
  public function setTipo($iTipo) {

    $aTipos = array(
      self::TIPO_IMUNE,
      self::TIPO_NORMAL,
      self::TIPO_DESCONTO,
      self::TIPO_IDADE,
    );

    if (!in_array($iTipo, $aTipos)) {
      throw new ParameterException("Tipo informado é inválido.");
    }

    $this->iTipo = $iTipo;
  }

  /**
   * AguaTipoIsencao constructor.
   * @param integer|null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = (integer) $iCodigo;
    if (!$this->iCodigo) {
      return;
    }

    $oDaoAguaIsencaoTipo = new cl_aguaisencaotipo();
    $sSqlAguaIsencaoTipo = $oDaoAguaIsencaoTipo->sql_query_file($this->getCodigo());
    $rsAguaIsencaoTipo = db_query($sSqlAguaIsencaoTipo);

    if (!$rsAguaIsencaoTipo || pg_num_rows($rsAguaIsencaoTipo) == 0) {
      throw new DBException("Não foi possível encontrar as informações do Tipo de Isenção.");
    }

    $oAguaTipoIsencao = db_utils::fieldsMemory($rsAguaIsencaoTipo, 0);

    $this->setDescricao($oAguaTipoIsencao->x29_descr);
    $this->setTipo($oAguaTipoIsencao->x29_tipo);
  }

  /**
   * @return int
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaIsencaoTipo = new cl_aguaisencaotipo();
    $oDaoAguaIsencaoTipo->x29_codisencaotipo = $this->getCodigo();
    $oDaoAguaIsencaoTipo->x29_descr = $this->getDescricao();
    $oDaoAguaIsencaoTipo->x29_tipo = $this->getTipo();

    if (!$this->getCodigo()) {
      $oDaoAguaIsencaoTipo->incluir(null);
    } else {
      $oDaoAguaIsencaoTipo->alterar($this->getCodigo());
    }

    if ($oDaoAguaIsencaoTipo->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Tipo de Isenção.");
    }

    $this->setCodigo($oDaoAguaIsencaoTipo->x29_codisencaotipo);

    return $this->getCodigo();
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (!$this->getCodigo()) {
      throw new ParameterException("Código do Tipo de Isenção não informado.");
    }

    $oDaoAguaIsencaoTipo = new cl_aguaisencaotipo();
    $oDaoAguaIsencaoTipo->excluir($this->getCodigo());

    if ($oDaoAguaIsencaoTipo->erro_status == '0') {
      throw new DBException("Não foi possível excluir as informações do Tipo de Isenção.");
    }

    return true;
  }
}
