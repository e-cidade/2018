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
 * Class VeiculoManutencaoItem
 */
class VeiculoManutencaoItem {

  /**
   * @type integer
   */
  private $iCodigo;

  /**
   * @type integer
   */
  private $iCodigoManutencao;

  /**
   * @type string
   */
  private $sDescricao;

  /**
   * @type float
   */
  private $nQuantidade;

  /**
   * @type float
   */
  private $nValorUnitario;

  /**
   * @type float
   */
  private $nValorTotal;

  /**
   * @type float
   */
  private $nValorTotalComDesconto;

  /**
   * @type integer
   */
  private $iCodigoUnidade;

  /**
   * @type UnidadeMaterial
   */
  private $oUnidade;

  /**
   * @type MaterialCompras
   */
  private $oMaterialCompras;

  /**
   * @type integer
   */
  private $iTipoItem;

  const TIPO_SERVICO_PECA = 1;
  const TIPO_SERVICO_MAO_DE_OBRA = 2;
  const TIPO_SERVICO_LAVAGEM = 3;

  public function __construct() {

  }


  /**
   * @param $iCodigo
   * @return VeiculoManutencaoItem
   * @throws BusinessException
   * @throws ParameterException
   */
  public static function getInstanciaPorCodigo($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException("Código do Item da Manutenção não informado.");
    }

    $oDaoManutencaoItem = new cl_veicmanutitem();
    $sSqlBuscaItem = $oDaoManutencaoItem->sql_query_file($iCodigo);
    $rsBuscaItem   = $oDaoManutencaoItem->sql_record($sSqlBuscaItem);
    if (!empty($oDaoManutencaoItem->erro_banco)) {
      throw new BusinessException("Item da manutenção com código {$iCodigo} não encontrado.");
    }

    $oStdDadosItem = db_utils::fieldsMemory($rsBuscaItem, 0);
    $oManutencaoItem = new VeiculoManutencaoItem();
    $oManutencaoItem->setCodigo($oStdDadosItem->ve63_codigo);
    $oManutencaoItem->setCodigoManutencao($oStdDadosItem->ve63_veicmanut);
    $oManutencaoItem->setDescricao($oStdDadosItem->ve63_descr);
    $oManutencaoItem->setQuantidade($oStdDadosItem->ve63_quant);
    $oManutencaoItem->setValorUnitario($oStdDadosItem->ve63_vlruni);
    $oManutencaoItem->setValorTotalComDesconto($oStdDadosItem->ve63_valortotalcomdesconto);
    $oManutencaoItem->setValorTotal(($oStdDadosItem->ve63_vlruni*$oStdDadosItem->ve63_quant));
    $oManutencaoItem->setCodigoUnidade($oStdDadosItem->ve63_unidade);
    $oManutencaoItem->setTipoItem($oStdDadosItem->ve63_tipoitem);
    unset($oStdDadosItem);
    return $oManutencaoItem;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer
   */
  public function getCodigoManutencao() {
    return $this->iCodigoManutencao;
  }

  /**
   * @param integer $iCodigoManutencao
   */
  public function setCodigoManutencao($iCodigoManutencao) {
    $this->iCodigoManutencao = $iCodigoManutencao;
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
  public function getValorUnitario() {
    return $this->nValorUnitario;
  }

  /**
   * @param float $nValorUnitario
   */
  public function setValorUnitario($nValorUnitario) {
    $this->nValorUnitario = $nValorUnitario;
  }

  /**
   * @return float
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * @return float
   */
  public function getValorTotalComDesconto() {
    return $this->nValorTotalComDesconto;
  }

  /**
   * @param float $nValorTotalComDesconto
   */
  public function setValorTotalComDesconto($nValorTotalComDesconto) {
    $this->nValorTotalComDesconto = $nValorTotalComDesconto;
  }

  /**
   * @return integer
   */
  public function getCodigoUnidade() {
    return $this->iCodigoUnidade;
  }

  /**
   * @param integer $iCodigoUnidade
   */
  public function setCodigoUnidade($iCodigoUnidade) {
    $this->iCodigoUnidade = $iCodigoUnidade;
  }

  /**
   * @return integer
   */
  public function getTipoItem() {
    return $this->iTipoItem;
  }

  /**
   * @param integer $iTipoItem
   */
  public function setTipoItem($iTipoItem) {
    $this->iTipoItem = $iTipoItem;
  }

  /**
   * @param UnidadeMaterial $oUnidade
   */
  public function setUnidadeMaterial(UnidadeMaterial $oUnidade) {
    $this->oUnidade = $oUnidade;
  }

  /**
   * @return UnidadeMaterial
   */
  public function getUnidadeMaterial() {

    if (empty($this->oUnidade) && !empty($this->iCodigoUnidade)) {
      $this->setUnidadeMaterial(UnidadeMaterialRepository::getByCodigo($this->iCodigoUnidade));
    }
    return $this->oUnidade;
  }

  /**
   * @param MaterialCompras $oMaterial
   */
  public function setMaterial(MaterialCompras $oMaterial) {
    $this->oMaterialCompras = $oMaterial;
  }

  /**
   * @return MaterialCompras
   * @throws DBException
   */
  public function getMaterial() {

    if (empty($this->oMaterialCompras)) {

      $oDaoMaterial = new cl_veicmanutitempcmater();
      $sSqlMaterial = $oDaoMaterial->sql_query(null, 've64_pcmater', null, 've64_veicmanutitem = '.$this->iCodigo);
      $rsBuscaMaterial = $oDaoMaterial->sql_record($sSqlMaterial);
      if ($oDaoMaterial->numrows == 1) {
        $this->setMaterial(MaterialComprasRepository::getByCodigo(db_utils::fieldsMemory($rsBuscaMaterial,0)->ve64_pcmater));
      }
    }
    return $this->oMaterialCompras;
  }
}