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


/**
* Interface para o sistema de contas
* @author dbseller
* @name SubSistemaConta
* @package contabilidade
* @subpackage planoconta
*/

class SubSistemaConta {
	
	/**
	 * Cуdigo do sub-sistema da conta
	 *
	 * @var integer
	 */
	private $iCodigo;
	
	/**
	 * Descriзao do sub-sistema da conta
	 *
	 * @var string
	 */
	private $sDescricao;
	
	/**
	* Mйtodo construtor que carrega as caracterнsticas do sub-sistema de conta se o mesmo
	* for passado como parвmetro
	*
	* @param integer $iCodigo
	*/
	public function __construct($iCodigo = null) {
	  
	  if ($iCodigo != null) {
	    	
	    $oDaoConSistemaConta = db_utils::getDao('consistemaconta');
	    $sSqlBuscaSubSistema = $oDaoConSistemaConta->sql_query_file($iCodigo);
	    $rsBuscaSubSistema   = $oDaoConSistemaConta->sql_record($sSqlBuscaSubSistema);
	    $oSubSistemaConta    = db_utils::fieldsMemory($rsBuscaSubSistema, 0);
	    $this->setCodigo($oSubSistemaConta->c65_sequencial);
	    $this->setDescricao($oSubSistemaConta->c65_descricao);
	  }
	}
	
	
	/**
	 * Define o cуdigo do sub-sistema da conta
	 *
	 * @param integer $iCodigo
	 * @return instance
	 */
	public function setCodigo($iCodigo) {
		
		$this->iCodigo = $iCodigo;
		return $this;
	}
	
	/**
	 * Retorna o cуdigo do sub-sistema da conta
	 *
	 * @return integer
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}
	
	/**
	 * Define a descriзгo do sub-sistema da conta
	 *
	 * @param string $sDescricao
	 * @return instance
	 */
	public function setDescricao($sDescricao) {
		
		$this->sDescricao = $sDescricao;
		return $this;
	}
	
	/**
	 * Retorna o cуdigo do sub-sistema da conta
	 *
	 * @return string
	 */
	public function getDescricao() {
		return $this->sDescricao;
	}
	

}
?>