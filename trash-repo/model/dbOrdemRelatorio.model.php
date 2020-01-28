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


require_once 'interfaces/GeradorRelatorio.interface.php';

final class dbOrdemRelatorio implements iGeradorRelatorio {
	
  public $iId      = null;
  public $sNome    = "";
  public $sAscDesc = "";
  public $sAlias   = "";
  
  
  /**
   * Método construtor da classe
   *
   * @param integer $iId
   * @param string  $sNome
   * @param string  $sAscDesc
   * 
   */
  
  public function __construct($iId="",$sNome="",$sAscDesc="",$sAlias=""){
  	
	  $this->setId($iId);  	
  	$this->setNome($sNome);
		$this->setAscDesc($sAscDesc);
		$this->setAlias($sAlias);
		
  }
  
  /**
   * Retorna ID do campo 
   *
   * @return integer
   */
  public function getId() {
    return $this->iId;
  }
  
  /**
   * Retorna o nome do campo
   *
   * @return string
   */
  public function getNome(){
  	
    if (db_utils::isUTF8($this->sNome)) {
      return utf8_decode($this->sNome);
    } else {
      return $this->sNome;
    }
    
  }
  
  /**
   * Retorna ascdesc do Campo
   *
   * @return string
   */
  public function getAscDesc() {
    return $this->sAscDesc;
  }
  
  public function getAlias(){
  	
   if (db_utils::isUTF8($this->sAlias)) {
      return utf8_decode($this->sAlias);
    } else {
      return $this->sAlias;
    }
      	
  }  

  /**
   * @param integer $iId
   */
  public function setId($iId) {
    $this->iId = $iId;
  }
  
  /**
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }  
  
  /**
   * @param string $sAscDesc
   */
  public function setAscDesc($sAscDesc) {
    $this->sAscDesc = $sAscDesc;
  }

  /**
   * @param string $sAlias
   */
  public function setAlias($sAlias) {
    $this->sAlias = $sAlias;
  }

  /**
   * Retorna estrutura XML das propriedades da classe
   *
   * @return unknown
   */
  
  public function toXml( XMLWriter $oXmlWriter) {
  	
  	$oXmlWriter->startElement('Ordem');

  	$oXmlWriter->writeAttribute('id'       ,utf8_encode($this->iId));
  	$oXmlWriter->writeAttribute('nome'     ,utf8_encode($this->sNome));
  	$oXmlWriter->writeAttribute('ascdesc'  ,utf8_encode($this->sAscDesc));
  	$oXmlWriter->writeAttribute('alias'    ,utf8_encode($this->sAlias));	
  	$oXmlWriter->endElement();  	
	
  	return true;
  	
  }

  
}


?>