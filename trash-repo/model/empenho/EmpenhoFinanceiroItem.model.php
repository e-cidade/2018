<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model para controle dos itens de um empenho financeiro
 * @author  Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.5 $
 */
class EmpenhoFinanceiroItem {

  /**
   * Sequencial do Item de Empenho
   * @var integer
   **/
  private $iSequencial;

  /**
   * Nъmero do Empenho (sequencial)
   * @var integer
   **/
  private $iNumeroEmpenho;

  /**
   * Nъmero do Sequencial do Item da autorizaзгo (e55_sequen)
   * @var integer
   **/
  private $iSequencialAutorizacaoItem;

  /**
   * Objeto MaterialCompras
   * @var MaterialCompras object
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
   * Cуdigo do Elemento
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
        $oDAOEmpenhoItem         = db_utils::fieldsMemory($rsEmpenhoItem,0);
        $this->iNumeroEmpenho    = $oDAOEmpenhoItem->e62_numemp;
        $this->iQuantidade       = $oDAOEmpenhoItem->e62_quant;
        $this->nValorTotal       = $oDAOEmpenhoItem->e62_vltot;
        $this->sDescricao        = $oDAOEmpenhoItem->e62_descr;
        $this->iCodigoElemento   = $oDAOEmpenhoItem->e62_codele;
        $this->nValorUnitario    = $oDAOEmpenhoItem->e62_vlrun;
        $this->iSequencial       = $iSequencial;
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
      throw new Exception("Nгo foi possнvel salvar os dados.\n\nErro tecnico :{$oDaoEmpenhoItem->erro_msg}");
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
   *  Seta o cуdigo do Sequencial do Item
   *  @var integer
   **/
  public function setSequencial($iSequencial) {
  	$this->iSequencial = $iSequencial;
  }

  /**
   *  Retorna o cуdigo do item de autorizaзгo de empenho
   *  @return integer
   **/
  public function getSequencialAutorizacaoItem() {
  	return $this->iSequencialAutorizacaoItem;
  }

  /**
   *  Seta  o cуdigo do item de autorizaзгo de empenho
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
   * @var numeric
   **/
  public function setValorUnitario( $nvalorUnitario) {
  	$this->nValorUnitario = $nvalorUnitario;
  }

}
?>