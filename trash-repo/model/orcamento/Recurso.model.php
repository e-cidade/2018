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


class Recurso {
  
	/**
	 * Cуdigo do recurso.
	 *
	 * @var integer_type
	 */
  protected $iCodigoRecurso;
  
  /**
   * Descriзгo da finalidade do recurso.
   *
   * @var string_type
   */
  protected $sFinalidadeRecurso;
    
  /**
   * Tipo do recurso.
   *
   * @var integer_type
   */
  protected $iTipoRecurso;
  
  /**
   * Data limite do recurso.
   *
   * @var date_type
   */
  protected $sDataLimiteRecurso;
  
  protected $oDBEstruturaValor;

  private $lNovo = true;
  /**
   * Tipo do estrutural.
   *
   * @var string_type
   */
  protected $sTipoEstrutural = 'Recurso';
  
  function __construct($iCodigoRecurso='') {
    
    if (!empty($iCodigoRecurso)) {
      
	    $oDaoOrcTipoRec   = db_utils::getDao("orctiporec");
	    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
	    $sSqlOrcTipoRec   = $oDaoOrcTipoRec->sql_query(null, 
	                                                   'orctiporec.*',
	                                                    null,
	                                                    $sWhereOrcTipoRec
	                                                  );
	    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
	    if ($oDaoOrcTipoRec->numrows > 0) {
	    	
	      $this->lNovo  = false;
	      $oOrcTipoRec = db_utils::fieldsMemory($rsSqlOrcTipoRec, 0); 
	      $this->iCodigoRecurso         = $iCodigoRecurso;
	      $this->sDescricao             = $oOrcTipoRec->o15_descr;
	      $this->sEstrutural            = $oOrcTipoRec->o15_codtri;
	      $this->sFinalidadeRecurso     = $oOrcTipoRec->o15_finali;
	      $this->iTipoRecurso           = $oOrcTipoRec->o15_tipo;
	      $this->sDataLimiteRecurso     = $oOrcTipoRec->o15_datalimite;
	      $this->setEstruturaValor(new TribunalEstrutura($oOrcTipoRec->o15_db_estruturavalor));
	      //parent::__construct($oOrcTipoRec->o15_db_estruturavalor);                                                      
	    }
    }
    
    //$this->tipo = __CLASS__;
  }
  
  /**
   * Retorna o cуdigo do recurso.
   * 
   * @return $this->iCodigoRecurso
   */
  public function getCodigoRecurso() {

    return $this->iCodigoRecurso;
  }
  
  /**
   * Retorna o tipo de recurso diponivel.
   * 
   * @return $this->iTipoRecurso
   */
  public function getTipoRecurso() {

    return $this->iTipoRecurso;
  }
  
  /**
   * Retorna a data limite do recurso.
   * 
   * @return $this->sDataLimiteRecurso
   */
  public function getDataLimiteRecurso() {

    return $this->sDataLimiteRecurso;
  }
  
  /**
   * Retorna a finalidade do recurso.
   * 
   * @return $this->sFinalidadeRecurso
   */
  public function getFinalidadeRecurso() {

    return $this->sFinalidadeRecurso;
  }
  
  /**
   * Seta um novo cуdigo para o recurso.
   *
   * @param integer_type $iCodigoRecurso
   * @return Recurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {

    $this->iCodigoRecurso = $iCodigoRecurso;
    return $this;
  }
  
  /**
   * Seta um novo tipo para o recurso.
   *
   * @param integer_type $iTipoRecurso
   * @return Recurso
   */
  public function setTipoRecurso($iTipoRecurso) {

    $this->iTipoRecurso = $iTipoRecurso;
    return $this;
  }
  
  /**
   * Seta uma nova data de limite para o recurso.
   *
   * @param string_type $sDataLimiteRecurso
   * @return Recurso
   */
  public function setDataLimiteRecurso($sDataLimiteRecurso) {

    $this->sDataLimiteRecurso = $sDataLimiteRecurso;
    return $this;
  }
  
  /**
   * Seta uma nova finalidade para o recurso.
   *
   * @param string_type $sFinalidadeRecurso
   * @return Recurso
   */
  public function setFinalidadeRecurso($sFinalidadeRecurso) {

    $this->sFinalidadeRecurso = $sFinalidadeRecurso;
    return $this;
  }

  /**
   * Retorna o cуdigo da estrutura.
   * @param integer_type $iCodigoEstrutura
   * @return $iCodigoRecurso
   */
  static public function getCodigoByEstrutura($iCodigoEstrutura) {
    
    $iCodigoRecurso = null;
    $oDaoOrcTipoRec = db_utils::getDao("orctiporec");
    $sSqlOrcTipoRec = $oDaoOrcTipoRec->sql_query_file(null, 
                                                      'o15_codigo',
                                                      null,
                                                      "o15_db_estruturavalor = {$iCodigoEstrutura}"
                                                     );
                                                             
    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows > 0) {
      $iCodigoRecurso = db_utils::fieldsMemory($rsSqlOrcTipoRec, 0)->o15_codigo;                                                             
    }
    
    return $iCodigoRecurso;
  }
  
  /**
   * Mйtodo para salvar os registros na tabela orctiporec.
   *
   * @return Recurso
   */
  function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nгo existe transaзгo ativa.");
    }
  	
    $oDaoOrcTipoRec = db_utils::getDao("orctiporec");
    $oDaoOrcTipoRec->o15_descr             = $this->getEstruturaValor()->getDescricao();
    $oDaoOrcTipoRec->o15_codtri            = $this->getEstruturaValor()->getEstrutural();
    $oDaoOrcTipoRec->o15_finali            = $this->getFinalidadeRecurso();
    $oDaoOrcTipoRec->o15_tipo              = $this->getTipoRecurso();
    $oDaoOrcTipoRec->o15_datalimite        = $this->getDataLimiteRecurso();
    $oDaoOrcTipoRec->o15_db_estruturavalor = $this->getEstruturaValor()->getCodigo();
    
    $iCodigoRecurso   = (int)$this->getCodigoRecurso();
    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
    $sSqlOrcTipoRec   = $oDaoOrcTipoRec->sql_query(null, 
                                                   'orctiporec.*',
                                                    null,
                                                    $sWhereOrcTipoRec
                                                  );                                            
    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows > 0 && !$this->lNovo) {
        
      $oDaoOrcTipoRec->o15_codigo = $iCodigoRecurso;
      $oDaoOrcTipoRec->alterar($oDaoOrcTipoRec->o15_codigo);                                             
    } else {
    	
    	$oDaoOrcTipoRec->o15_codigo = $iCodigoRecurso;
      $oDaoOrcTipoRec->incluir($oDaoOrcTipoRec->o15_codigo);
      
      $this->setCodigoRecurso($oDaoOrcTipoRec->o15_codigo);
    }

    if ($oDaoOrcTipoRec->erro_status == 0) {
      throw new Exception($oDaoOrcTipoRec->erro_msg);
    }

    return $this;
  }
  
  /**
   * Mйtodo para remover um registro da tabela orctiporec.
   *
   * @param integer_type $iCodigoRecurso
   */  
  public function remover() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Nгo existe transaзгo ativa.");
    }
  	
    $iCodigoRecurso = $this->getCodigoRecurso();
    if (empty($iCodigoRecurso) && $iCodigoRecurso != 0) {
      throw new Exception("Cуdigo do recurso nгo informado!\\nExclusгo nгo efetuada.");
    }
    
    $oDaoOrcTipoRec   = db_utils::getDao("orctiporec");
    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
    $oDaoOrcTipoRec->excluir(null, $sWhereOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows_excluir == 0) {
      throw new Exception($oDaoOrcTipoRec->erro_msg);
    }
    
    $this->getEstruturaValor()->remover();
    //parent::remover();
  }  
  
  /**
   * Seta uma instancia de DBEstruturaValor
   * @param DBEstruturaValor
   */
  public function setEstruturaValor(DBEstruturaValor $oEstruturaValor) {
    
    $this->oDBEstruturaValor = $oEstruturaValor;
    return $this; 
  }
  
  /**
   * Retorna uma instancia de DBEstruturaValor
   * @return DBEstruturaValor
   */
  public function getEstruturaValor() {
    
    return $this->oDBEstruturaValor;
  }
  
}
?>