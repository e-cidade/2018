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
 * Model para de Selecao
 *
 * @package pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class Selecao {

	/**
	 * Código seleção
	 * @var Integer
	 */
	private $iCodigo;
	
	/**
	 * Descrição da seleção
	 * @var String
	 */
	private $sDescricao;

  /**
   * Where da seleção
   * @var String
   */
	private $sWhere;

	/**
	 * Construtor da classe
	 * 
	 * @param Integer $iCodigo
	 */
	public function __construct($iCodigo = null, $iInstituicao = null) {

		if ( empty($iCodigo) ) {
			return;
		}

		if(empty($iInstituicao)) {
			$iInstituicao = db_getsession("DB_instit");
		}
		
    try {

	    $oDaoSelecao    = new cl_selecao;
	    $sSqlSelecao    = $oDaoSelecao->sql_query($iCodigo, $iInstituicao);
	    $rsSelecao      = db_query($sSqlSelecao);

	    if(!$rsSelecao) {
	      throw new DBException("Ocorreu um erro ao buscar a seleção.");
	    }

	    if(pg_num_rows($rsSelecao) > 0) {

	      $oStdSelecao = db_utils::fieldsMemory($rsSelecao, 0);

				$this->setCodigo($iCodigo);
				$this->setDescricao($oStdSelecao->r44_descr);
				$this->setWhere($oStdSelecao->r44_where);

	    }
	    
	  } catch (Exception $e) {
	    $sErro = $e->getMessage();
	  }
	}

	/**
	 * Retorna o código do Selecao
	 * @return number
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Define o código do Selecao
	 * @param integer $iCodigo
	 */
	public function setCodigo($iCodigo) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna o Tipo de Selecao da  de Selecaos
	 * @return Descricao
	 */
	public function getDescricao() {
		return $this->sDescricao;
	}

	/**
	 * Define o Tipo de Selecao para a 
	 * @param Descricao $sDescricao
	 */
	public function setDescricao ($sDescricao) {
		$this->sDescricao = $sDescricao;
	}

	/**
	 * Retorna a Where das selecões
	 * @return Where
	 */
	public function getWhere() {
		return $this->sWhere;
	}

	/**
	 * Define a Where para as Selecões
	 * @param Where $sWhere
	 */
	public function setWhere ($sWhere) {
		$this->sWhere = $sWhere;
	}

	/**
	 * Persist na base a  de Selecao
	 * @return mixed true | String mensagem de erro
	 */
	public function persist() {}

	/**
	 * Transforma o objeto em um formato JSON
	 * @return JSON
	 */
  public function toJSON() {}
}