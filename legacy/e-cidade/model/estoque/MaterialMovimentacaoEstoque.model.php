<?php
/*
 *   E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
 *              www.dbseller.com.br
 *             e-cidade@dbseller.com.br
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
 *                licenca/licenca_pt.txt
 */

class MaterialMovimentacaoEstoque {

  /**
   * Código da Movimentação (matestoqueinimei)
   * @type integer
   */
  private $iCodigoMovimentacao;

  /**
   * Código do item gerado no estoque (matestoqueitem)
   * @type integer
   */
  private $iCodigoItemEstoque;

  /**
   * Código com os dados da movimentação gerada para o item (matestoqueini)
   * @type integer
   */
  private $iCodigoItemMovimentacao;

  /**
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * @var integer
   */
  private $iCodigoTipo;

  /**
   * @var integer
   */
  private $iCodigoDepartamento;

  /**
   * @var integer
   */
  private $iCodigoMaterial;

  /**
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * @var TipoMovimentacaoEstoque
   */
  private $oTipo;

  /**
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * @var MaterialAlmoxarifado
   */
  private $oMaterial;

  /**
   * @var float
   */
  private $nQuantidadeEstoque;

  /**
   * @var float
   */
  private $nQuantidadeAtendida;

  /**
   * @var float
   */
  private $nValor;

  /**
   * @var DBDate
   */
  private $oData;

  /**
   * @var string
   */
  private $sHora;

  /**
   * @var string
   */
  private $sObservacao;

  /**
   * @var boolean
   */
  private $lServico;

  /**
   * @TODO implementar carregamento dos dados no objeto
   */
  public function __construct($iCodigo = null) {

    $this->iCodigoMovimentacao = $iCodigo;
    if (empty($this->iCodigoMovimentacao)) {
      return;
    }

  }

  /**
   * Salva o movimento
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Não há uma transação ativa com o banco de dados.");
    }

    $oDaoMatestoque       = new cl_matestoque;
    $oDaoMatestoqueItem   = new cl_matestoqueitem;
    $oDaoMatestoqueIniMei = new cl_matestoqueinimei;
    $oDaoMatestoqueIni    = new cl_matestoqueini;

    /**
     * Verifica se já existe estoque para o material no departamento
     */
    $sWhereEstoque = "m70_codmatmater = {$this->iCodigoMaterial} and m70_coddepto = {$this->iCodigoDepartamento}";
    $sSqlEstoque   = $oDaoMatestoque->sql_query_file(null, 'm70_codigo', null, $sWhereEstoque);
    $rsEstoque     = $oDaoMatestoque->sql_record($sSqlEstoque);
    if ($oDaoMatestoque->numrows == 0) {

      $oDaoMatestoque->m70_codmatmater = $this->iCodigoMaterial;
      $oDaoMatestoque->m70_coddepto    = $this->iCodigoDepartamento;
      $oDaoMatestoque->m70_quant       = (string) $this->nQuantidadeEstoque;
      $oDaoMatestoque->m70_valor       = $this->nValor;
      $oDaoMatestoque->incluir(null);

      $iCodigoMatestoque = $oDaoMatestoque->m70_codigo;
    } else {
      $iCodigoMatestoque = db_utils::fieldsMemory($rsEstoque, 0)->m70_codigo;
    }

    $oDaoMatestoqueIni->m80_login    = $this->iCodigoUsuario;
    $oDaoMatestoqueIni->m80_data     = $this->oData->getDate();
    $oDaoMatestoqueIni->m80_hora     = $this->sHora;
    $oDaoMatestoqueIni->m80_obs      = $this->sObservacao;
    $oDaoMatestoqueIni->m80_codtipo  = $this->iCodigoTipo;
    $oDaoMatestoqueIni->m80_coddepto = $this->iCodigoDepartamento;
    $oDaoMatestoqueIni->incluir(null);

    $oDaoMatestoqueItem->m71_codmatestoque = $iCodigoMatestoque;
    $oDaoMatestoqueItem->m71_data          = $this->oData->getDate();
    $oDaoMatestoqueItem->m71_valor         = $this->nValor;
    $oDaoMatestoqueItem->m71_servico       = $this->lServico;
    $oDaoMatestoqueItem->m71_quantatend    = (string) 0;
    $oDaoMatestoqueItem->m71_quant         = (string) $this->nQuantidadeEstoque;
    $oDaoMatestoqueItem->incluir(null);

    $oDaoMatestoqueIniMei->m82_matestoqueini  = $oDaoMatestoqueIni->m80_codigo;
    $oDaoMatestoqueIniMei->m82_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
    $oDaoMatestoqueIniMei->m82_quant          = (string) $this->nQuantidadeEstoque;
    $oDaoMatestoqueIniMei->incluir(null);

    $this->iCodigoItemEstoque      = $oDaoMatestoqueItem->m71_codlanc;
    $this->iCodigoItemMovimentacao = $oDaoMatestoqueIni->m80_codigo;
    $this->iCodigoMovimentacao     = $oDaoMatestoqueIniMei->m82_codigo;
  }

  /**
   * @return int|null
   */
  public function getCodigo() {
    return $this->iCodigoMovimentacao;
  }

  /**
   * @return int
   */
  public function getCodigoItemMovimentacao() {
    return $this->iCodigoItemMovimentacao;
  }

  /**
   * @return int
   */
  public function getCodigoItemEstoque() {
    return $this->iCodigoItemEstoque;
  }

  /**
   *
   * @return integer
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   *
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (!empty($iCodigoUsuario) && empty($this->oUsuario)) {
      $this->oUsuario = new UsuarioSistema($iCodigoUsuario);
    }

    return $this->oUsuario;
  }

  /**
   *
   * @return integer
   */
  public function getCodigoTipo() {
    return $this->iCodigoTipo;
  }

  /**
   *
   * @return TipoMovimentacaoEstoque
   */
  public function getTipo() {

    if (!empty($this->iCodigoTipo) && empty($this->oTipo)) {
      $this->oTipo = new TipoMovimentacaoEstoque($this->iCodigoTipo);
    }

    return $this->oTipo;
  }

  /**
   *
   * @return integer
   */
  public function getCodigoDepartamento() {
    return $this->iCodigoDepartamento;
  }

  /**
   *
   * @return DBDepartamento
   */
  public function getDepartamento() {

    if (!empty($this->iCodigoDepartamento) && empty($this->oDepartamento)) {
      $this->oDepartamento = new DBDepartamento($this->iCodigoDepartamento);
    }

    return $this->oDepartamento;
  }

  /**
   *
   * @return integer
   */
  public function getCodigoMaterial() {
    return $this->iCodigoMaterial;
  }

  /**
   *
   * @return MaterialAlmoxarifado
   */
  public function getMaterial() {

    if (!empty($this->iCodigoMaterial) && empty($this->oMaterial)) {
      $this->oMaterial = new MaterialAlmoxarifado($this->iCodigoMaterial);
    }

    return $this->oMaterial;
  }

  /**
   *
   * @return float
   */
  public function getQuantidadeEstoque() {
    return $this->nQuantidadeEstoque;
  }

  /**
   *
   * @return float
   */
  public function getQuantidadeAtendida() {
    return $this->nQuantidadeAtendida;
  }

  /**
   *
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   *
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   *
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   *
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   *
   * @return boolean
   */
  public function getServico() {
    return $this->lServico;
  }

  /**
   *
   * @param integer $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   *
   * @param integer $iCodigoTipo
   */
  public function setCodigoTipo($iCodigoTipo) {
    $this->iCodigoTipo = $iCodigoTipo;
  }

  /**
   *
   * @param integer $iCodigoDepartamento
   */
  public function setCodigoDepartamento($iCodigoDepartamento) {
    $this->iCodigoDepartamento = $iCodigoDepartamento;
  }

  /**
   *
   * @param integer $iCodigoMaterial
   */
  public function setCodigoMaterial($iCodigoMaterial) {
    $this->iCodigoMaterial = $iCodigoMaterial;
  }

  /**
   *
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {

    $this->iCodigoUsuario = $oUsuario->getCodigo();
    $this->oUsuario       = $oUsuario;
  }

  /**
   *
   * @param TipoMovimentacaoEstoque $oTipo
   */
  public function setTipo(TipoMovimentacaoEstoque $oTipo) {

    $this->iCodigoTipo = $oTipo->getCodigo();
    $this->oTipo       = $oTipo;
  }

  /**
   *
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento) {

    $this->iCodigoDepartamento = $oDepartamento->getCodigo();
    $this->oDepartamento       = $oDepartamento;
  }

  /**
   *
   * @param MaterialAlmoxarifado $oMaterial
   */
  public function setMaterial(MaterialAlmoxarifado $oMaterial) {

    $this->iCodigoMaterial = $oMaterial->getCodigo();
    $this->oMaterial       = $oMaterial;
  }

  /**
   *
   * @param float $nQuantidadeEstoque
   */
  public function setQuantidadeEstoque($nQuantidadeEstoque) {
    $this->nQuantidadeEstoque = $nQuantidadeEstoque;
  }

  /**
   *
   * @param float $nQuantidadeAtendida
   */
  public function setQuantidadeAtendida($nQuantidadeAtendida) {
    $this->nQuantidadeAtendida = $nQuantidadeAtendida;
  }

  /**
   *
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   *
   * @param DBDate $oData
   */
  public function setData(DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   *
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   *
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   *
   * @param boolean $lServico
   */
  public function setServico($lServico) {
    $this->lServico = $lServico;
  }

}
