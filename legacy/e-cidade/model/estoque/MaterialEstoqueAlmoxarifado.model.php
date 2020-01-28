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
class MaterialEstoqueAlmoxarifado {

  /**
   * @type integer
   */
  private $iCodigo;

  /**
   * @type integer
   */
  private $iCodigoMaterial;

  /**
   * @type MaterialAlmoxarifado;
   */
  private $oMaterial;

  /**
   * @type integer
   */
  private $iCodigoDepartamento;

  /**
   * @type DBDepartamento
   */
  private $oDepartamento;

  /**
   * @type float
   */
  private $nQuantidade;

  /**
   * @type float
   */
  private $nValor;

  /**
   * @type MaterialEstoqueItem[]
   */
  private $aEstoqueItem = array();

  /**
   * @param null $iCodigo
   *
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($this->iCodigo)) {
      return;
    }

    $oDaoEstoque      = new cl_matestoque();
    $sSqlBuscaEstoque = $oDaoEstoque->sql_query_file($this->iCodigo);
    $rsBuscaEstoque   = $oDaoEstoque->sql_record($sSqlBuscaEstoque);
    if ($oDaoEstoque->erro_status == "0") {
      throw new Exception("Não foi encontrado estoque com o código {$this->iCodigo}.");
    }

    $oStdEstoque = db_utils::fieldsMemory($rsBuscaEstoque, 0);
    $this->iCodigoDepartamento = $oStdEstoque->m70_coddepto;
    $this->iCodigoMaterial     = $oStdEstoque->m70_codmatmater;
    $this->nQuantidade         = $oStdEstoque->m70_quant;
    $this->nValor              = $oStdEstoque->m70_valor;
    unset($oStdEstoque, $oDaoEstoque);
  }

  /**
   * @return bool
   * @throws Exception
   */
  public function salvar() {

    $oDaoEstoque = new cl_matestoque();
    $oDaoEstoque->m70_codigo      = $this->iCodigo;
    $oDaoEstoque->m70_codmatmater = $this->getMaterial()->getCodigo();
    $oDaoEstoque->m70_coddepto    = $this->getDepartamento()->getCodigo();
    $oDaoEstoque->m70_quant       = $this->nQuantidade;
    $oDaoEstoque->m70_valor       = $this->nValor;
    if (empty($oDaoEstoque->m70_codigo)) {
      $oDaoEstoque->incluir($oDaoEstoque->m70_codigo);
    } else {
      $oDaoEstoque->alterar($oDaoEstoque->m70_codigo);
    }
    $this->iCodigo = $oDaoEstoque->m70_codigo;
    if ($oDaoEstoque->erro_status == "0") {
      throw new Exception("Impossível incluir o estoque para o material {$this->oMaterial->getDescricao()}.");
    }
    return true;
  }

  /**
   * @return MaterialEstoqueItem[]
   * @throws Exception
   */
  public function getItens() {

    $this->aEstoqueItem = array();
    $oDaoEstoqueItem = new cl_matestoqueitem();
    $sSqlEstoqueItem = $oDaoEstoqueItem->sql_query_file(null, "m71_codlanc", null, "m71_codmatestoque = {$this->iCodigo}");
    $rsEstoqueItem   = $oDaoEstoqueItem->sql_record($sSqlEstoqueItem);
    if ($oDaoEstoqueItem->erro_status == "0") {
      throw new Exception("Nenhum item vinculado com o estoque {$this->iCodigo}.");
    }

    for ($iRow = 0; $iRow < $oDaoEstoqueItem->numrows; $iRow++) {

      $iCodigo = db_utils::fieldsMemory($rsEstoqueItem, $iRow)->m71_codlanc;
      $this->aEstoqueItem[] = new MaterialEstoqueItem($iCodigo);
    }
    return $this->aEstoqueItem;
  }

  /**
   * Retorna o Item mais antigo com saldo disponível
   *
   * @return MaterialEstoqueItem
   */
  public function getItemComSaldo() {

    $oDaoEstoqueItem = new cl_matestoqueitem();

    $sSqlEstoqueItem = $oDaoEstoqueItem->sql_query_file( null,
                                                         "m71_codlanc",
                                                         "m71_codlanc",
                                                         "m71_codmatestoque = {$this->iCodigo} and m71_quant > m71_quantatend" );
    $rsEstoqueItem   = $oDaoEstoqueItem->sql_record("{$sSqlEstoqueItem} limit 1");

    if (empty($rsEstoqueItem)) {
      throw new Exception("Erro ao buscar entrada do estoque com saldo.");
    }

    if ($oDaoEstoqueItem->numrows > 0) {
      return new MaterialEstoqueItem( db_utils::fieldsMemory($rsEstoqueItem, 0)->m71_codlanc );
    }

    return null;
  }

  /**
   * @param MaterialAlmoxarifado $oMaterial
   * @param DBDepartamento       $oDepartamento
   *
   * @return MaterialEstoqueAlmoxarifado
   */
  public static function getEstoquePorMaterialDepartamento(MaterialAlmoxarifado $oMaterial, DBDepartamento $oDepartamento) {

    $sWhere = "m70_codmatmater = {$oMaterial->getCodigo()} and m70_coddepto = {$oDepartamento->getCodigo()}";
    $oDaoEstoque = new cl_matestoque();
    $sSqlEstoque = $oDaoEstoque->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaEstoque = $oDaoEstoque->sql_record($sSqlEstoque);
    $oEstoque = new MaterialEstoqueAlmoxarifado(null);
    $oEstoque->setMaterial($oMaterial);
    $oEstoque->setDepartamento($oDepartamento);
    $oEstoque->setQuantidade(0);
    $oEstoque->setValor(0);
    if ($oDaoEstoque->numrows > 0) {

      $oStdEstoque = db_utils::fieldsMemory($rsBuscaEstoque, 0);
      $oEstoque->setCodigo($oStdEstoque->m70_codigo);
      $oEstoque->setCodigoMaterial($oStdEstoque->m70_codmatmater);
      $oEstoque->setCodigoDepartamento($oStdEstoque->m70_coddepto);
      $oEstoque->setQuantidade($oStdEstoque->m70_quant);
      $oEstoque->setValor($oStdEstoque->m70_valor);
      unset($oStdEstoque, $oDaoEstoque);
    } else {
      $oEstoque->salvar();
    }
    return $oEstoque;
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
  public function getCodigoMaterial() {
    return $this->iCodigoMaterial;
  }

  /**
   * @param int $iCodigoMaterial
   */
  public function setCodigoMaterial($iCodigoMaterial) {
    $this->iCodigoMaterial = $iCodigoMaterial;
  }

  /**
   * @return MaterialAlmoxarifado
   */
  public function getMaterial() {

    if (!empty($this->iCodigoMaterial) && empty($this->oMaterial)) {
      $this->setMaterial(new MaterialAlmoxarifado($this->iCodigoMaterial));
    }
    return $this->oMaterial;
  }

  /**
   * @param MaterialAlmoxarifado $oMaterial
   */
  public function setMaterial(MaterialAlmoxarifado $oMaterial) {
    $this->oMaterial = $oMaterial;
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

    if (empty($this->oDepartamento) && !empty($this->iCodigoDepartamento)) {
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
   * @return float
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * @param float $nQuantidade
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
}
