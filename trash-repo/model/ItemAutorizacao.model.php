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


class ItemAutorizacao {

  protected $iElemento;
   
  protected $nValorUnitario;
   
  protected $oMaterial;
  
  protected $sResumo;
   
  protected $iAutorizacao;
   
  protected $nQuantidade;
   
  protected $nSequencia;
  
  protected $isSave=false;

  function __construct($iAutorizacao, $iSequencia) {
    
    $this->iAutorizacao = $iAutorizacao;
    $this->nSequencia   = $iSequencia;
  }
  /**
   * @return unknown
   */
  public function getAutorizacao() {

    return $this->iAutorizacao;
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getElemento() {

    return $this->iElemento;
  }
  
  /**
   * @param unknown_type $iElemento
   */
  public function setElemento($iElemento) {

    $this->iElemento = $iElemento;
  }
  
  /**
   * @return unknown
   */
  public function getQuantidade() {

    return $this->nQuantidade;
  }
  
  /**
   * @param unknown_type $nQuantidade
   */
  public function setQuantidade($nQuantidade) {

    $this->nQuantidade = $nQuantidade;
  }
  
  /**
   * @return unknown
   */
  public function getSequencia() {

    return $this->nSequencia;
  }
  
  /**
   * @param unknown_type $nSequencia
   */
  public function setSequencia($nSequencia) {

    $this->nSequencia = $nSequencia;
  }
  
  /**
   * @return unknown
   */
  public function getValorUnitario() {

    return $this->nValorUnitario;
  }
  
  /**
   * @param unknown_type $nValorUnitario
   */
  public function setValorUnitario($nValorUnitario) {

    $this->nValorUnitario = $nValorUnitario;
  }
  
  /**
   * @return MaterialCompras
   */
  public function getMaterial() {

    return $this->oMaterial;
  }
  
  /**
   * @param unknown_type $oMaterial
   */
  public function setMaterial(MaterialCompras $oMaterial) {

    $this->oMaterial = $oMaterial;
  }
  
  /**
   * @return unknown
   */
  public function getResumo() {

    return $this->sResumo;
  }
  
  /**
   * @param string $sResumo
   */
  public function setResumo($sResumo) {
    $this->sResumo = $sResumo;
  }

  function save() {
    
    $oDaoEmpautItem = db_utils::getDao("empautitem");
    $oDaoEmpautItem->e55_autori = $this->iAutorizacao;
    $oDaoEmpautItem->e55_codele = $this->getElemento();
    $oDaoEmpautItem->e55_item   = $this->getMaterial()->getMaterial();
    $oDaoEmpautItem->e55_quant  = $this->getQuantidade();
    $oDaoEmpautItem->e55_vlrun  = $this->getValorUnitario();
    $oDaoEmpautItem->e55_vltot  = $this->getValorUnitario() * $this->getQuantidade();
    $oDaoEmpautItem->e55_descr  = $this->getResumo();
    $oDaoEmpautItem->e55_sequen = $this->nSequencia;
    $oDaoEmpautItem->e55_servicoquantidade = 'true';
    
    if (!$this->isSave) {

      $oDaoEmpautItem->incluir($this->iAutorizacao, $this->nSequencia);
      $this->isSave = true;
    } else {
      $oDaoEmpautItem->alterar($this->iAutorizacao, $this->nSequencia);
    }
    
    if ($oDaoEmpautItem->erro_status == 0) { 
      throw new Exception("[ 1 ] - Erro ao salvar item da autorizacao de empenho.\n{$oDaoEmpautItem->erro_msg}");
    }
  }
  
  public function isSaved() {
    return $this->isSave;
  }
}