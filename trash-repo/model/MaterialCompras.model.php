<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Controle de materias do compras
 * @package compras
 */
class MaterialCompras {
  
  /**
   * cуdigo do material;
   *
   * @var integer
   */
  protected $iMaterial;
  
  /**
   * Descriзгo do material
   *
   * @var ustring
   */
  protected $sDescricao;
  
  /**
   * Verifica se o material й um serviзo 
   *
   * @var bool
   */
  protected $lServico;
  
  /**
   * Elementos vinculados ao material
   *
   * @var array
   */
  protected $aElementos = array();
  
  /**
   * 
   */
  function __construct($iMaterial = null) {
    
    if (!empty($iMaterial)) {
      
      $this->iMaterial = $iMaterial;
      $oDaoPcmater     = db_utils::getDao("pcmater");
      $sSqlMaterial    = $oDaoPcmater->sql_query_file($iMaterial);
      $rsMaterial      = $oDaoPcmater->sql_record($sSqlMaterial);
      if ($oDaoPcmater->numrows > 0)    {
        
        $oMaterial = db_utils::fieldsMemory($rsMaterial, 0, false, false, true);
        $this->sDescricao = $oMaterial->pc01_descrmater;
        $this->lServico   = $oMaterial->pc01_servico== 't'?true:false;
        
      } else {
        $this->iMaterial = null;
      }
    }
    
    
  }
  /**
   * @return integer
   */
  public function getMaterial() {

    return $this->iMaterial;
  }
  
  /**
   * @return ustring
   */
  public function getDescricao() {

    return $this->sDescricao;
  }
  
  /**
   * @param ustring $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  
  public function getElementos() {
    
    if (count($this->aElementos) == 0) {
      
      require_once("classes/db_pcmaterele_classe.php");
      $oDaoMaterialElemento  = new cl_pcmaterele;
      $sSqlElementos         = "SELECT o56_codele as codigoelemento,";
      $sSqlElementos        .= "       o56_elemento as elemento,";
      $sSqlElementos        .= "       o56_descr   as descricao";
      $sSqlElementos        .= "  from pcmaterele ";
      $sSqlElementos        .= "       inner join orcelemento on pc07_codele = o56_codele ";
      $sSqlElementos        .= "                             and o56_anousu = ".db_getsession("DB_anousu");
      $sSqlElementos        .= " where pc07_codmater = {$this->iMaterial}";
      $rsElementos           = db_query($sSqlElementos);
      $this->aElementos = db_utils::getColectionByRecord($rsElementos, false, false, true);
      
    }
    return $this->aElementos;
  }
  
  /**
   * verifica se o material й um serviзo;
   *
   * @return boolean
   */
  public function isServico() {

    return $this->lServico;
  }
}

?>