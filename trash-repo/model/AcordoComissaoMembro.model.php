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
 * Enter description here...
 *@package contratos
 */
class AcordoComissaoMembro {
  
	/**
	 * Codigo do membro.
	 *
	 * @var Integer
	 */
	private $iCodigo;
	
	/**
   * Nome do membro. ()
   *
   * @var String
   */
	private $sNome;
		
	/**
	 * Codigo CGM do membro.
	 *
	 * @var Integer
	 */
	private $iCodigoCgm;	
	
	/**
	 * Codigo da comissao do membro.
	 *
	 * @var Integer
	 */
	private $iCodigoComissao;
	
	/**
	 * Codigo da responsabilidade do cliente;
	 *
	 * @var Integer
	 */
	private $iResponsabilidade;
	
  /**
   * 
   */
  function __construct($iCodigo = null) {
  	
  	if ($iCodigo) {
	  	$oDaoComissaoMembro = db_utils::getDao('acordocomissaomembro');
	    $sSqlMembro         = $oDaoComissaoMembro->sql_query(null, "*", "", " ac07_sequencial={$iCodigo}");
	    $rsCM               = $oDaoComissaoMembro->sql_record($sSqlMembro);
	    $oMembro            = db_utils::fieldsMemory($rsCM,0);
	    
	    $this->setCodigo($oMembro->ac07_sequencial);
	    $this->setNome($oMembro->z01_nome);
	    $this->setCodigoCgm($oMembro->ac07_numcgm);
	    $this->setCodigoComissao($oMembro->ac07_acordocomissao);
	    $this->setResponsabilidade($oMembro->ac07_tipomembro);
  	}    
  }
    
  function save() {

  	if (!db_utils::inTransaction()) {
  		
  		throw new Exception("Nуo existe transaчуo ativa com o bando de dados.");
  	}
  	$oDaoComissaoMembro = db_utils::getDao('acordocomissaomembro');
  	
  	$oDaoComissaoMembro->ac07_numcgm         = $this->getCodigoCgm();
  	$oDaoComissaoMembro->ac07_acordocomissao = $this->getCodigoComissao();
  	$oDaoComissaoMembro->ac07_tipomembro     = $this->getResponsabilidade();
  	
	  if ($this->getCodigo()) {
	  	
	  	$oDaoComissaoMembro->ac07_sequencial = $this->getCodigo();
	  	$oDaoComissaoMembro->alterar($this->getCodigo());	  		  	
	  		  	
	  } else {
	  	
	  	$oDaoComissaoMembro->incluir(null);	  	
	  	
	  }
	  
	  if ($oDaoComissaoMembro->erro_status == 0) {

	  	$sMensagem  = "Houve um erro ao salvar dados do membro da comissao.\n";
	  	$sMensagem .= "Erro:{$oDaoComissaoMembro->erro_msg}";
	  	throw new Exception($sMensagem);
	  }  
  }
  
  function excluir() {
    
  	$oDaoComissaoMembro = db_utils::getDao('acordocomissaomembro');
  	$oDaoComissaoMembro->excluir($this->getCodigo());
  	
    if ($oDaoComissaoMembro->erro_status == 0) {

      $sMensagem  = "Houve um erro ao excluir o membro da comissao.\n";
      $sMensagem .= "Erro:{$oDaoComissaoMembro->erro_msg}";
      throw new Exception($sMensagem);
    }
  	
  }
  
  /**
   * @return Integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return Integer
   */
  public function getCodigoCgm() {

    return $this->iCodigoCgm;
  }
  
  /**
   * @return Integer
   */
  public function getCodigoComissao() {

    return $this->iCodigoComissao;
  }
  
  /**
   * @return String
   */
  public function getNome() {

    return $this->sNome;
  }  
  
  /**
   * @return Integer
   */
  public function getResponsabilidade() {

    return $this->iResponsabilidade;
  }
  /**
   * @param Integer $iCodigo
   */
  public function setCodigo($iCodigo) {

    $this->iCodigo = $iCodigo;
  }
    
  /**
   * @param Integer $iCodigoCgm
   */
  public function setCodigoCgm($iCodigoCgm) {

    $this->iCodigoCgm = $iCodigoCgm;
  }
    
  /**
   * @param Integer $iCodigoComissao
   */
  public function setCodigoComissao($iCodigoComissao) {

    $this->iCodigoComissao = $iCodigoComissao;
  }
  
  /**
   * @param String $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }  
    
  /**
   * @param Integer $iResponsabilidade
   */
  public function setResponsabilidade($iResponsabilidade) {

    $this->iResponsabilidade = $iResponsabilidade;
  }
  
  /**
   * metodo para retornar a descriчуo da responsabilidade, dentro da comissуo
   * @return string
   */
  
  public function getDescricaoResponsabilidade() {
  	
  	$iResponsabilidade = $this->getResponsabilidade();
  	$sResponsabilidade = "";
  	
  	switch ($iResponsabilidade) {
  		
  		case 1 :
  			$sResponsabilidade = "Principal";
  		break;	
  		
  		
  		case 2 :
  			$sResponsabilidade = "Secundсrio";
  		break;

  		
  		case 3 :
  			$sResponsabilidade = "Suplente";
  		break;
  		
  		
  		case 4 :
  			$sResponsabilidade = "Fiscal";
  		break;
  		
  	}
  	
  	return $sResponsabilidade;
  }
  
}

?>