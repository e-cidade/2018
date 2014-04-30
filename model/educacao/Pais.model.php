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
 * Classe para controle das informações de um País
 * @author     Fabio Esteves <fabio.esteves@dbseller.com.br>
 * @package    educacao
 */
class Pais {
	
	/**
	 * Código do país
	 * @var integer
	 */
	protected $iCodigo;
	
	/**
	 * Nome do país
	 * @var string
	 */
	protected $sDescricao;
	
	/**
	 * Abreviatura do nome do país
	 * @var string
	 */
	protected $sAbreviatura;
	
	/**
	 * Construtor da classe. Recebe o código do país como parametro. Caso seja diferente de null, busca os outros dados
	 * @param integer $iCodigo
	 */
	public function __construct( $iCodigo = null ) {
		
		if ( !empty($iCodigo) ) {
			
			$oDaoPais = db_utils::getDao("pais");
			$sSqlPais = $oDaoPais->sql_query_file($iCodigo);
			$rsPais   = $oDaoPais->sql_record($sSqlPais);
			
			if ( $oDaoPais->numrows > 0 ) {
				
				$oDadosPais         = db_utils::fieldsMemory($rsPais, 0);
				$this->iCodigo      = $oDadosPais->ed228_i_codigo;
				$this->sDescricao   = $oDadosPais->ed228_c_descr;
				$this->sAbreviatura = $oDadosPais->ed228_c_abrev;
			}
		}
	}

	/**
	 * Retorna o código do pais
	 * @return integer
	 */
	public function getCodigo() {
	  return $this->iCodigo;
	}

	/**
	 * Seta o código do país
	 * @param integer $iCodigo
	 */
	public function setCodigo($iCodigo) {
	  $this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna o nome do país
	 * @return string
	 */
	public function getDescricao() {
	  return $this->sDescricao;
	}

	/**
	 * Seta o nome do país
	 * @param string $sDescricao
	 */
	public function setDescricao($sDescricao) {
	  $this->sDescricao = $sDescricao;
	}

	/**
	 * Retorna a abreviatura do nome do país
	 * @return string
	 */
	public function getAbreviatura() {
	  return $this->sAbreviatura;
	}

	/**
	 * Seta a abreviatura do nome do país
	 * @param string $sAbreviatura
	 */
	public function setAbreviatura($sAbreviatura) {
	  $this->sAbreviatura = $sAbreviatura;
	}
}
?>