<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 

class InscricaoPassivoOrcamentoItem {
  
  /**
   * Sequencial do Item
   * @var integer
   **/
  protected $iSequencial;
  
  /**
   * Cуdigo da Inscricao de Orcamento Passivo
   * @var integer
   **/
  protected $iInscricaoPassivo;
  
  /**
   * Objeto de Material de Compras
   * @var object
   **/
  protected $oMaterialCompras;
  
  /**
   * Quantidade do Item
   * @var integer
   **/
  protected $iQuantidade;
  
  /**
   * Valor unitario do Item
   * @var float
   **/
  protected $fValorUnitario;
  
  /**
   * Quantidade do Item
   * @var float
   **/
  protected $fValorTotal;
  
  /**
   * Observacao a respeito do item
   * @var string
   **/
  protected $sObservacao;
  
  public function __construct($iSequencial = null) {
    
  	$this->iSequencial = $iSequencial;
    if($iSequencial != null) {
  
      $oDAOInscricaoPassivoOrcamentoItem = db_utils::getDao("inscricaopassivoitem");     
      $sSQLInscricaoPassivoOrcamentoItem = $oDAOInscricaoPassivoOrcamentoItem->sql_query_file($iSequencial);
      $rsInscricaoPassivoOrcamentoItem   = $oDAOInscricaoPassivoOrcamentoItem->sql_record($sSQLInscricaoPassivoOrcamentoItem );
      
      if($oDAOInscricaoPassivoOrcamentoItem->numrows > 0) {
        
        //seta as propriedades do Item
        $oDAOInscricaoPassivoOrcamentoItem = db_utils::fieldsMemory($rsInscricaoPassivoOrcamentoItem,0);
        $this->iSequencial       = $iSequencial;
        $this->iInscricaoPassivo = $oDAOInscricaoPassivoOrcamentoItem->c38_inscricaopassivo;
        $this->iQuantidade       = $oDAOInscricaoPassivoOrcamentoItem->c38_quantidade;
        $this->fValorUnitario    = $oDAOInscricaoPassivoOrcamentoItem->c38_valorunitario;
        $this->fValorTotal       = $oDAOInscricaoPassivoOrcamentoItem->c38_valortotal;
        $this->sObservacao       = $oDAOInscricaoPassivoOrcamentoItem->c38_observacao;
        
        /**
         * Carrega Objeto referente a Material de compras
         * */
        $this->oMaterialCompras  = new MaterialCompras($oDAOInscricaoPassivoOrcamentoItem->c38_pcmater);
        unset($oDAOInscricaoPassivoOrcamentoItem);
      }
    }
    return true;
  }
 
  /**
   *  Metodo para salvar os dados do item de uma inscriзгo
   *  @return boolean true
   */
  public function salvar(){

  	$oDaoInscricaoPassivoOrcamento = db_utils::getDao("inscricaopassivoitem");
    $oDaoInscricaoPassivoOrcamento->c38_inscricaopassivo = $this->iInscricaoPassivo;
    $oDaoInscricaoPassivoOrcamento->c38_pcmater          = $this->oMaterialCompras->getMaterial();
    $oDaoInscricaoPassivoOrcamento->c38_quantidade       = $this->iQuantidade;
    $oDaoInscricaoPassivoOrcamento->c38_valorunitario    = $this->fValorUnitario;
    $oDaoInscricaoPassivoOrcamento->c38_valortotal       = $this->fValorTotal;
    $oDaoInscricaoPassivoOrcamento->c38_observacao       = $this->sObservacao;
    
    if($this->iSequencial == null){
	    $oDaoInscricaoPassivoOrcamento->incluir(null);
    } else {
    	
    	$oDaoInscricaoPassivoOrcamento->c38_sequencial = $this->iSequencial;
    	$oDaoInscricaoPassivoOrcamento->alterar();
    }
      
    if($oDaoInscricaoPassivoOrcamento->erro_status == 0){
      throw new Exception("Nгo foi possнvel salvar os dados da transaзгo.\n\n Erro tecnico :{$oDaoInscricaoPassivoOrcamento->erro_msg}");
    }
    return true;
  }
  
  /**
   *  Retorna o Codigo da Inscricao
   *  @return integer
   **/
  public function getInscricaoPassivo() {
  	return  $iInscricaoPassivo;
  }
  
  /**
   *  Seta o Codigo da Inscricao
   *  @param integer
   **/
  public function setInscricaoPassivo($iInscricaoPassivo) {
  	$this->iInscricaoPassivo =  $iInscricaoPassivo;
  }
    
  /**
   *  Retorna o objeto Material de Compras associado
   *  @return object
   **/
  public function getMaterialCompras() {
  	return $this->oMaterialCompras;
  }
  
  /**
   *  Seta o Material de compra
   *  @param object
   **/
  public function setMaterialCompras($oMaterialCompras) {
  	$this->oMaterialCompras = $oMaterialCompras;
  }
  
  /**
   *  Retorna a Quantidade do item
   *  @return integer
   **/
  public function getQuantidade(){
  	return $this->iQuantidade;
  }
  
  /**
   *  Seta a Quantidade deste item
   *  @param integer
   **/
  public function setQuantidade($iQuantidade){
  	$this->iQuantidade = $iQuantidade;
  }
  
  /**
   *  Retorna Valor Unitario do item
   *  @return float
   **/
  public function getValorUnitario(){
  	return $this->fValorUnitario;
  }
  
  /**
   *  Seta Valor Unitario do item
   *  @param float
   **/
  public function setValorUnitario($fValorUnitario) {
  	$this->fValorUnitario = $fValorUnitario;
  }
  
  /**
   *  Retorna Valor Total
   *  @return float
   **/
  public function getValorTotal() {
  	return $this->fValorTotal;
  }
  
  /**
   *  Seta Valor Total
   *  @param float
   **/
  public function setValorTotal($fValorTotal) {
  	$this->fValorTotal = $fValorTotal;
  }
  
  /**
   *  Retorna Observacao
   *  @return string
   **/
  public function getObservacao() {
  	return $this->sObservacao;
  }
  
  /**
   *  Seta Observacao
   *  @param string
   **/
  public function setObservacao($sObservacao) {
  	$this->sObservacao = $sObservacao;
  }
  
  /**
   *  Retorna Sequencial
   *  @return string
   **/
  public function getSequencial() {
    return $this->iSequencial;
  }
  
  /**
   *  Seta Sequencial
   *  @param string
   **/
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }
}
?>