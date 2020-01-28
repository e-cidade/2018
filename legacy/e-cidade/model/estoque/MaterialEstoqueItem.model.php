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
 * Class MaterialEstoqueItem
 */
class MaterialEstoqueItem {

  /**
   * @type int
   */
  private $iCodigo;

  /**
   * @type int
   */
  private $iCodigoEstoque;

  /**
   * @type MaterialEstoqueAlmoxarifado
   */
  private $oEstoque;

  /**
   * @type DBDate
   */
  private $oData;

  /**
   * @type float
   */
  private $nQuantidade;

  /**
   * @type float
   */
  private $nValor;

  /**
   * @type float
   */
  private $nQuantidadeAtendida;

  /**
   * @type boolean
   */
  private $lServico;

  /**
   * @type MaterialEstoqueMovimentacao[]
   */
  private $aMovimentacoes = array();

  /**
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($this->iCodigo)) {
      return;
    }

    $oDaoMatEstoqueItem = new cl_matestoqueitem();
    $sSqlBuscaItem      = $oDaoMatEstoqueItem->sql_query_file($this->iCodigo);
    $rsBuscaItem        = $oDaoMatEstoqueItem->sql_record($sSqlBuscaItem);
    if ($oDaoMatEstoqueItem->erro_status == "0") {
      throw new BusinessException("Item do estoque {$this->iCodigo} não encontrado.");
    }
    $oStdItem = db_utils::fieldsMemory($rsBuscaItem, 0);
    $this->iCodigoEstoque      = $oStdItem->m71_codmatestoque;
    $this->oData               = new DBDate($oStdItem->m71_data);
    $this->nQuantidade         = $oStdItem->m71_quant;
    $this->nValor              = $oStdItem->m71_valor;
    $this->nQuantidadeAtendida = $oStdItem->m71_quantatend;
    $this->lServico            = $oStdItem->m71_servico == "t";
    unset($oStdItem, $oDaoMatEstoqueItem);
  }

  /**
   * @return bool
   * @throws Exception
   */
  public function salvar() {

    $oDaoEstoqueItem = new cl_matestoqueitem();
    $oDaoEstoqueItem->m71_codlanc       = $this->iCodigo;
    $oDaoEstoqueItem->m71_codmatestoque = $this->getEstoque()->getCodigo();
    $oDaoEstoqueItem->m71_data          = $this->getData()->getDate();
    $oDaoEstoqueItem->m71_quant         = $this->nQuantidade;
    $oDaoEstoqueItem->m71_valor         = $this->nValor;
    $oDaoEstoqueItem->m71_quantatend    = (string)$this->nQuantidadeAtendida;
    $oDaoEstoqueItem->m71_servico       = $this->lServico ? "true" : "false";
    if (empty($oDaoEstoqueItem->m71_codlanc)) {
      $oDaoEstoqueItem->incluir($oDaoEstoqueItem->m71_codlanc);
    } else {
      $oDaoEstoqueItem->alterar($oDaoEstoqueItem->m71_codlanc);
    }

    $this->iCodigo = $oDaoEstoqueItem->m71_codlanc;
    if ($oDaoEstoqueItem->erro_status == "0") {
      throw new Exception("Impossível incluir um novo item para o estoque." .$oDaoEstoqueItem->erro_msg);
    }
    return true;
  }

  /**
   * @param MaterialEstoqueItem         $oMaterialEstoqueItem
   * @param MaterialEstoqueMovimentacao $oMaterialMovimentacao
   * @param int                         $nQuantidade
   *
   * @return integer - Código do vinculo
   * @throws DBException
   */
  public static function vincularMovimentacaoComItem(MaterialEstoqueItem $oMaterialEstoqueItem, MaterialEstoqueMovimentacao $oMaterialMovimentacao, $nQuantidade = 0) {

    $oDaoMatEstoqueIniMEI = new cl_matestoqueinimei();
    $oDaoMatEstoqueIniMEI->m82_codigo         = null;
    $oDaoMatEstoqueIniMEI->m82_matestoqueini  = $oMaterialMovimentacao->getCodigo();
    $oDaoMatEstoqueIniMEI->m82_matestoqueitem = $oMaterialEstoqueItem->getCodigo();
    $oDaoMatEstoqueIniMEI->m82_quant          = (string)$nQuantidade;
    $oDaoMatEstoqueIniMEI->incluir($oDaoMatEstoqueIniMEI->m82_codigo);

    if ($oDaoMatEstoqueIniMEI->erro_status == "0") {
      throw new DBException("Não foi possível vincular a movimentação do estoque com o item.\n\n".pg_last_error());
    }

    return $oDaoMatEstoqueIniMEI->m82_codigo;
  }

  /**
   * @return MaterialEstoqueMovimentacao[]
   * @throws Exception
   */
  public function getMovimentacoes() {

    $this->aMovimentacoes  = array();
    $oDaoEstoqueIniMEI     = new cl_matestoqueinimei();
    $sSqlBuscaMovimentacao = $oDaoEstoqueIniMEI->sql_query_file(null, "m82_matestoqueini", "m82_codigo", "m82_matestoqueitem = {$this->iCodigo}");
    $rsBuscaMovimentacao   = $oDaoEstoqueIniMEI->sql_record($sSqlBuscaMovimentacao);
    if ($oDaoEstoqueIniMEI->erro_status == "0") {
      throw new Exception("Não foi localizado nenhuma movimentação para o item {$this->iCodigo}.");
    }

    for ($iRow = 0; $iRow < $oDaoEstoqueIniMEI->numrows; $iRow++) {

      $iCodigoMovimentacao    = db_utils::fieldsMemory($rsBuscaMovimentacao, $iRow)->m82_matestoqueini;
      $this->aMovimentacoes[] = new MaterialEstoqueMovimentacao($iCodigoMovimentacao);
    }
    return $this->aMovimentacoes;
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
  public function getCodigoEstoque() {
    return $this->iCodigoEstoque;
  }

  /**
   * @param int $iCodigoEstoque
   */
  public function setCodigoEstoque($iCodigoEstoque) {
    $this->iCodigoEstoque = $iCodigoEstoque;
  }

  /**
   * @return MaterialEstoqueAlmoxarifado
   */
  public function getEstoque() {

    if (!empty($this->iCodigoEstoque) && empty($this->oEstoque)) {
      $this->setEstoque(new MaterialEstoqueAlmoxarifado($this->iCodigoEstoque));
    }
    return $this->oEstoque;
  }

  /**
   * @param MaterialEstoqueAlmoxarifado $oEstoque
   */
  public function setEstoque(MaterialEstoqueAlmoxarifado $oEstoque) {
    $this->oEstoque = $oEstoque;
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
  public function setData($oData) {
    $this->oData = $oData;
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

  /**
   * @return float
   */
  public function getQuantidadeAtendida() {
    return $this->nQuantidadeAtendida;
  }

  /**
   * @param float $nQuantidadeAtendida
   */
  public function setQuantidadeAtendida($nQuantidadeAtendida) {
    $this->nQuantidadeAtendida = $nQuantidadeAtendida;
  }

  /**
   * @return boolean
   */
  public function servico() {
    return $this->lServico;
  }

  /**
   * @param boolean $servico
   */
  public function setServico($servico) {
    $this->lServico = $servico;
  }
}