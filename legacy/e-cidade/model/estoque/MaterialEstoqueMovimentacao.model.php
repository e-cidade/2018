<?php
/**
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
 * Class MaterialEstoqueMovimentacao
 */
class MaterialEstoqueMovimentacao {

  /**
   * @type integer
   */
  private $iCodigo;

  /**
   * @type integer
   */
  private $iCodigoUsuario;

  /**
   * @type UsuarioSistema
   */
  private $oUsuario;

  /**
   * @type DBDate
   */
  private $oData;

  /**
   * @type string
   */
  private $sObservacao;

  /**
   * @type integer
   */
  private $iCodigoTipoMovimento;

  /**
   * @type TipoMovimentacaoEstoque
   */
  private $oMovimento;

  /**
   * @type integer
   */
  private $iCodigoDepartamento;

  /**
   * @type DBDepartamento
   */
  private $oDepartamento;

  /**
   * @type string
   */
  private $sHora;

  /**
   * @param null $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($this->iCodigo)) {
      return;
    }

    $oDaoMatEstoqueINI = new cl_matestoqueini();
    $sSqlBuscaMovimento = $oDaoMatEstoqueINI->sql_query_file($this->iCodigo);
    $rsBuscaMovimento   = $oDaoMatEstoqueINI->sql_record($sSqlBuscaMovimento);
    if ($oDaoMatEstoqueINI->erro_status == "0") {
      throw new Exception("Movimentação com código {$this->iCodigo} não localizado.");
    }

    $oStdMovimento = db_utils::fieldsMemory($rsBuscaMovimento, 0);
    $this->iCodigoUsuario       = $oStdMovimento->m80_login;
    $this->oData                = new DBDate($oStdMovimento->m80_data);
    $this->sObservacao          = $oStdMovimento->m80_obs;
    $this->iCodigoTipoMovimento = $oStdMovimento->m80_codtipo;
    $this->iCodigoDepartamento  = $oStdMovimento->m80_coddepto;
    $this->sHora                = $oStdMovimento->m80_hora;
    unset($oStdMovimento, $oDaoMatEstoqueINI);
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoMatEstoqueINI = new cl_matestoqueini();
    $oDaoMatEstoqueINI->m80_codigo   = $this->iCodigo;
    $oDaoMatEstoqueINI->m80_login    = $this->getUsuario()->getCodigo();
    $oDaoMatEstoqueINI->m80_data     = $this->getData()->getDate();
    $oDaoMatEstoqueINI->m80_obs      = $this->getObservacao();
    $oDaoMatEstoqueINI->m80_codtipo  = $this->getMovimento()->getCodigo();
    $oDaoMatEstoqueINI->m80_coddepto = $this->getDepartamento()->getCodigo();
    $oDaoMatEstoqueINI->m80_hora     = $this->getHora();

    if (empty($oDaoMatEstoqueINI->m80_codigo)) {
      $oDaoMatEstoqueINI->incluir($oDaoMatEstoqueINI->m80_codigo);
    } else {
      $oDaoMatEstoqueINI->alterar($oDaoMatEstoqueINI->m80_codigo);
    }
    $this->iCodigo = $oDaoMatEstoqueINI->m80_codigo;

    if ($oDaoMatEstoqueINI->erro_status == "0") {
      throw new BusinessException("Impossível incluir a movimentação para o material.");
    }
    return true;
  }

  /**
   * Anula a movimentação atual, vinculando com a movimentação de anulação passada
   *
   * @param  MaterialEstoqueMovimentacao $oEstoqueMovimentacao
   * @throws Exception
   * @return boolean
   */
  public function anularMovimentacao(MaterialEstoqueMovimentacao $oEstoqueMovimentacao) {

    $oDaoMatestoqueinil  = new cl_matestoqueinil();
    $oDaoMatestoqueinill = new cl_matestoqueinill();

    $oDaoMatestoqueinil->m86_matestoqueini = $this->getCodigo();
    $oDaoMatestoqueinil->incluir(null);

    if ($oDaoMatestoqueinil->erro_status == 0) {
      throw new Exception("Erro ao anular a movimentação de entrada de ordem de compra.");
    }

    $oDaoMatestoqueinill->m87_matestoqueini = $oEstoqueMovimentacao->getCodigo();
    $oDaoMatestoqueinill->incluir($oDaoMatestoqueinil->m86_codigo);

    if ($oDaoMatestoqueinill->erro_status == 0) {
      throw new Exception("Erro ao anular a movimentação de entrada de ordem de compra.");
    }

    return true;
  }

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
   * @return int
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param int $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (!empty($this->iCodigoUsuario) && empty($this->oUsuario)) {
      $this->setUsuario(UsuarioSistemaRepository::getPorCodigo($this->iCodigoUsuario));
    }
    return $this->oUsuario;
  }

  /**
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @param DBDate $oData
   */
  public function setData(DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * @return int
   */
  public function getCodigoTipoMovimento() {
    return $this->iCodigoTipoMovimento;
  }

  /**
   * @param int $iCodigoTipoMovimento
   */
  public function setCodigoTipoMovimento($iCodigoTipoMovimento) {
    $this->iCodigoTipoMovimento = $iCodigoTipoMovimento;
  }

  /**
   * @return TipoMovimentacaoEstoque
   */
  public function getMovimento() {

    if (empty($this->oMovimento) && !empty($this->iCodigoTipoMovimento)) {
      $this->setMovimento(new TipoMovimentacaoEstoque($this->iCodigoTipoMovimento));
    }
    return $this->oMovimento;
  }

  /**
   * @param TipoMovimentacaoEstoque $oMovimento
   */
  public function setMovimento(TipoMovimentacaoEstoque $oMovimento) {
    $this->oMovimento = $oMovimento;
  }

  /**
   * @return int
   */
  public function getCodigoDepartamento() {
    return $this->iCodigoDepartamento;
  }

  /**
   * @param int $iCodigoDepartamento
   */
  public function setCodigoDepartamento($iCodigoDepartamento) {
    $this->iCodigoDepartamento = $iCodigoDepartamento;
  }

  /**
   * @return DBDepartamento
   */
  public function getDepartamento() {

    if (!empty($this->iCodigoDepartamento) && empty($this->oDepartamento)) {
      $this->setDepartamento(DBDepartamentoRepository::getDBDepartamentoByCodigo($this->iCodigoDepartamento));
    }
    return $this->oDepartamento;
  }

  /**
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento) {
    $this->oDepartamento = $oDepartamento;
  }

  /**
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }
}