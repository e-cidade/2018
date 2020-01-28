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

/**
 * Item
 *
 * @package patrimonio
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class Item {

  /**
   * Código do item
   *
   * @var integer
   * @access private
   */
  private $iCodigo;

  /**
   * Nome do item
   *
   * @var string
   * @access private
   */
  private $sNome;

  /**
   * Grupo do item
   *
   * @var MaterialGrupo
   * @access private
   */
  private $oMaterialGrupo;
  
  /**
   * unidade do item
   * @var object
   */
  private $oUnidade;
  
  /**
   * unidade do item
   * @var integer
   */
  private $iUnidade;

  /**
   * Construtor do item
   *
   * @param integer $iCodigo
   * @access public
   */
  public function __construct($iCodigo = null) {

    /**
     * Código do item não informado
     */
    if (empty($iCodigo)) {
      return false;
    }

    $oDaoMatmater = new cl_matmater();
    $sSqlItem = $oDaoMatmater->sql_query_file($iCodigo);
    $rsItem = $oDaoMatmater->sql_record($sSqlItem);

    if ($oDaoMatmater->erro_status == '0') {
      throw new Exception("Não foi possível localizar o item pelo código: $iCodigo");
    }

    $oDadosItem = db_utils::fieldsMemory($rsItem, 0);

    $this->iCodigo  = $iCodigo;
    $this->sNome    = $oDadosItem->m60_descr;
    $this->iUnidade = $oDadosItem->m60_codmatunid;
  }

  /**
   * Retorna o codigo do item
   *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o grupo do item
   *
   * @access public
   * @return MaterialGrupo
   */
  public function getGrupo() {

    if ( !empty($this->oMaterialGrupo) ) {
      return $this->oMaterialGrupo;
    }

    $oDaoMaterialestoquegrupo = new cl_materialestoquegrupo();
    $sCampos   = 'm65_sequencial';
    $sWhere    = 'm60_codmater = ' . $this->getCodigo();
    $sSqlGrupo = $oDaoMaterialestoquegrupo->sql_query_grupoitem(null, $sCampos, null, $sWhere);
    $rsGrupo   =  $oDaoMaterialestoquegrupo->sql_record($sSqlGrupo);
    if ($oDaoMaterialestoquegrupo->erro_status == '0') {
      throw new Exception($oDaoMaterialestoquegrupo->erro_msg);
    }

    $oDadosGrupo = db_utils::fieldsMemory($rsGrupo, 0);
    $this->oMaterialGrupo = new MaterialGrupo($oDadosGrupo->m65_sequencial);

    return $this->oMaterialGrupo;
  }

  /**
   * Retorna o nome do item
   *
   * @access public
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * define a unidade do item
   * @param integer $iCodigoUnidade
   */
  public function setUnidade( $iCodigoUnidade ){
    $this->iUnidade = $iCodigoUnidade;
  }
  /**
   * retorna dados da unidade
   * @return object ItemUnidade
   */
  public function getUnidade(){
    
    if ( !empty($this->oUnidade) ) {
      return $this->oUnidade;
    }    
    $oItemUnidade   = new ItemUnidade($this->iUnidade);
    $this->oUnidade = $oItemUnidade;
    return $this->oUnidade;
  }
  
}