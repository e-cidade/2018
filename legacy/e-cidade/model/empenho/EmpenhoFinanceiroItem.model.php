<?php
/**
 * Model para controle dos itens de um empenho financeiro
 * @author  Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.11 $
 */
class EmpenhoFinanceiroItem {

  /**
   * Sequencial do Item de Empenho
   * @var integer
   **/
  private $iSequencial;

  /**
   * Número do Empenho (sequencial)
   * @var integer
   **/
  private $iNumeroEmpenho;

  /**
   * Número do Sequencial do Item da autorização (e55_sequen)
   * @var integer
   **/
  private $iSequencialAutorizacaoItem;

  /**
   * Objeto MaterialCompras
   * @var MaterialCompras
   **/
  private $oItemMaterialCompras;

  /**
   * Qauntidade do Item
   * @var integer
   **/
  private $iQuantidade;

  /**
   * Descricao do Item
   * @var string
   **/
  private $sDescricao;

  /**
   * Código do Elemento
   * @var integer
   **/
  private $iCodigoElemento;

  /**
   * Valor Total
   * @var float
   **/
  private $nValorTotal;

  /**
   * Valor Unitario
   * @var float
   **/
  private $nValorUnitario;

  /**
   * Empenho Financeiro
   * @var EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * Material do almoxarifado
   * @var MaterialAlmoxarifado
   */
  private $oMaterialAlmoxarifado;

  /**
   * Constroi os dados de um item de um empenho financeiro
   * @param integer $iSequencial
   */
  public function __construct ($iSequencial = null) {

    $this->iSequencial = $iSequencial;

    if ($iSequencial != null) {

      $oDAOEmpenhoItem = db_utils::getDao("empempitem");
      $sSQLEmpenhoItem = $oDAOEmpenhoItem->sql_query_file(null, null, "*", null, "e62_sequencial = {$iSequencial}");
      $rsEmpenhoItem   = $oDAOEmpenhoItem->sql_record($sSQLEmpenhoItem);

      if ($oDAOEmpenhoItem->numrows > 0) {

        //seta as propriedades do Item
        $oDAOEmpenhoItem                  = db_utils::fieldsMemory($rsEmpenhoItem,0);
        $this->iNumeroEmpenho             = $oDAOEmpenhoItem->e62_numemp;
        $this->iQuantidade                = $oDAOEmpenhoItem->e62_quant;
        $this->nValorTotal                = $oDAOEmpenhoItem->e62_vltot;
        $this->sDescricao                 = $oDAOEmpenhoItem->e62_descr;
        $this->iCodigoElemento            = $oDAOEmpenhoItem->e62_codele;
        $this->nValorUnitario             = $oDAOEmpenhoItem->e62_vlrun;
        $this->iSequencial                = $iSequencial;
        $this->iSequencialAutorizacaoItem = $oDAOEmpenhoItem->e62_sequen;
        /**
         * Carrega Objeto referente a Material de come62_sequencial
         * */
        $this->oItemMaterialCompras  = new MaterialCompras($oDAOEmpenhoItem->e62_item);
        unset($oDAOEmpenhoItem);
      }
    }
    return true;
  }

  /**
   * Salva os dados do item de um emepnho
   * @throws Exception
   * @return boolean true
   */
  public function salvar () {

    $oDaoEmpenhoItem = db_utils::getDao("empempitem");
    $oDaoEmpenhoItem->e62_sequencial   = $this->iSequencial;
    $oDaoEmpenhoItem->e62_item         = $this->oItemMaterialCompras->getMaterial();
    $oDaoEmpenhoItem->e62_numemp       = $this->iNumeroEmpenho;
    $oDaoEmpenhoItem->e62_sequen       = $this->iSequencialAutorizacaoItem;
    $oDaoEmpenhoItem->e62_quant        = $this->iQuantidade;
    $oDaoEmpenhoItem->e62_descr        = $this->sDescricao;
    $oDaoEmpenhoItem->e62_codele       = $this->iCodigoElemento;
    $oDaoEmpenhoItem->e62_vlrun        = $this->nValorUnitario;
    $oDaoEmpenhoItem->e62_vltot        = $this->nValorTotal;
    $oDaoEmpenhoItem->incluir($this->iNumeroEmpenho, $this->iSequencialAutorizacaoItem);

    if ($oDaoEmpenhoItem->erro_status == 0) {
      throw new Exception("Não foi possível salvar os dados.\n\nErro tecnico :{$oDaoEmpenhoItem->erro_msg}");
    }
    return true;
  }

  /**
   *  Retorna o Codigo do Sequencial do Item
   *  @return integer
   **/
  public  function getSequencial() {
  	return $this->iSequencial;
  }

  /**
   *  Seta o código do Sequencial do Item
   *  @var integer
   **/
  public function setSequencial($iSequencial) {
  	$this->iSequencial = $iSequencial;
  }

  /**
   *  Retorna o código do item de autorização de empenho
   *  @return integer
   **/
  public function getSequencialAutorizacaoItem() {
  	return $this->iSequencialAutorizacaoItem;
  }

  /**
   *  Seta  o código do item de autorização de empenho
   *  @var integer
   **/
  public function setSequencialAutorizacaoItem($iSequencialAutorizacaoItem) {
  	$this->iSequencialAutorizacaoItem = $iSequencialAutorizacaoItem;
  }

  /**
   *  Retorna o Codigo do numero de empenho
   *  @return integer
   **/
  public function getNumeroEmpenho() {
  	return $this->iNumeroEmpenho;
  }

  /**
   *  Seta o Codigo do numero de empenho
   *  @var integer
   **/
  public function setNumeroEmpenho($iNumeroEmpenho) {
  	$this->iNumeroEmpenho = $iNumeroEmpenho;
  }

  /**
   *  Retorna o objeto Material de Compras
   *  @return MaterialCompras
   **/
  public function getItemMaterialCompras() {
  	return $this->oItemMaterialCompras;
  }

  /**
   *  seta o objeto Material de Compras
   *  @var MaterialCompras
   **/
  public function setItemMaterialCompras(MaterialCompras $oItemMaterialCompras) {
  	$this->oItemMaterialCompras = $oItemMaterialCompras;
  }

  /**
   *  Retorna Quantidade do item
   *  @return integer
   **/
  public function getQuantidade () {
  	return $this->iQuantidade;
  }

  /**
   *  Seta Quantidade do item
   *  @var integer
   **/
  public function setQuantidade ($iQuantidade) {
  	$this->iQuantidade = $iQuantidade;
  }

  /**
   *  Retorna Descricao do item
   *  @return string
   **/
  public function getDescricao() {
  	return $this->sDescricao;
  }

  /**
   *  Seta Descricao do item
   *  @var string
   **/
  public function setDescricao($sDescricao) {
  	$this->sDescricao = $sDescricao;
  }

  /**
   *  Retorna Codigo do Elemento
   *  @return integer
   **/
  public function getCodigoElemento() {
  	return $this->iCodigoElemento;
  }

  /**
   *  Seta Codigo do Elemento
   *  @var integer
   **/
  public function setCodigoElemento( $iCodigoElemento) {
  	$this->iCodigoElemento = $iCodigoElemento;
  }

  /**
   *  Retorna Valor Total do Empenho Item
   *  @return float
   **/
  public function getValorTotal() {
  	return $this->nValorTotal;
  }

  /**
   *  Seta Valor Total do Empenho Item
   *  @param float
   **/
  public function setValorTotal( $nvalorTotal) {
  	$this->nValorTotal = $nvalorTotal;
  }

  /**
   *  Retorna Valor Unitario
   *  @return float
   **/
  public  function getValorUnitario() {
  	return $this->nValorUnitario;
  }

  /**
   *  Seta Valor Total do Empenho Item
   * @var float
   **/
  public function setValorUnitario( $nvalorUnitario) {
  	$this->nValorUnitario = $nvalorUnitario;
  }

  /**
   * Retorna empenho financeiro
   * @return EmpenhoFinanceiro
   */
  public function getEmpenhoFinanceiro() {

    if (empty($this->oEmpenhoFinanceiro)) {
      $this->oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($this->iNumeroEmpenho);
    }
    return $this->oEmpenhoFinanceiro;
  }

  /**
   * @return MaterialAlmoxarifado
   * @throws Exception
   */
  public function getMaterialAlmoxarifado() {

    if (empty($this->oMaterialAlmoxarifado)) {

      $oDaoEmpempitem = new cl_empempitem();
      $sWhere  = ' m52_numemp = ' . $this->getNumeroEmpenho();
      $sWhere .= ' and m52_sequen = ' . $this->getSequencialAutorizacaoItem();
      $sSqlCodigo = $oDaoEmpempitem->sql_query_material_almoxarifado('m70_codmatmater', null, $sWhere);
      $rsCodigo = $oDaoEmpempitem->sql_record($sSqlCodigo);

      if ($oDaoEmpempitem->erro_status == '0') {
        throw new Exception("Erro ao buscar código do material do almoxarifado.");
      }

      $iMaterialAlmoxarifado = db_utils::fieldsMemory($rsCodigo, 0)->m70_codmatmater;
      $this->oMaterialAlmoxarifado = new MaterialAlmoxarifado($iMaterialAlmoxarifado);
    }

    return $this->oMaterialAlmoxarifado;
  }


  /**
   * Busca o valor de desconto de um item do empenho.
   *
   * @param integer $iSequencial
   * @param integer $iOrdemInclusao
   * @param integer $iNumeroEmpenho
   * @throws ParameterException|Exception
   * @return float
   */
  public static function getValorDeDescontoPorItem($iSequencial, $iOrdemInclusao = null, $iNumeroEmpenho = null) {

    if (empty($iSequencial) && empty($iOrdemInclusao) && empty($iNumeroEmpenho)) {
      throw new ParameterException("Nenhum parâmetro válido informado para buscar o valor do item anulado.");
    }

    $aWhere = array("empempitem.e62_sequencial = {$iSequencial}");
    if (!empty($iOrdemInclusao) && !empty($iNumeroEmpenho)) {
      $aWhere = array(
        "empempitem.e62_sequen = {$iOrdemInclusao}",
        "empempitem.e62_numemp = {$iNumeroEmpenho}",
      );
    }

    $oDaoDesconto      = new cl_pagordemdescontoempanulado();
    $sSqlBuscaDesconto = $oDaoDesconto->sql_query_itens_empenho("coalesce(sum(e37_vlranu),0) as valor_anulado", implode(' and ', $aWhere));
    $rsBuscaDesconto   = db_query($sSqlBuscaDesconto);
    if (!$rsBuscaDesconto) {
      throw new Exception("Não foi possível buscar os dados de anulação do item do empenho.");
    }
    $nValorRetorno = 0;
    if (pg_num_rows($rsBuscaDesconto) > 0) {
      $nValorRetorno = db_utils::fieldsMemory($rsBuscaDesconto, 0)->valor_anulado;
    }
    return $nValorRetorno;
  }
}
