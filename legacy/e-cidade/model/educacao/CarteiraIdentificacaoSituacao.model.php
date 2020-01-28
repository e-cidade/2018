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
 * Classe modelo para situacao da carteira de identificacao
 * @package   Educacao
 * @author    Robson Inacio - robson@dbseller.com.br 
 * @version   $Revision: 1.1 $ 
 */
class CarteiraIdentificacaoSituacao {

	private $iSequencialSituacao = null;
	private $sDescricao          = null;
	private $lAtivo              = false;
	
	/**
	 * Método construtor, informando o sequencial da tabela cartaoidentificacaosituacao a classe
	 * faz o load dos dados
	 *
	 * @param integer $iSequencialSituacao codigo sequencial da tabela cartaoidentificacaosituacao ed307_sequencial
	 */
  public function __construct($iSequencialSituacao=null){
  	
  	if ($iSequencialSituacao != null) {
  		
  	  $oDaoCartaoIdentificacaoSituacao = db_utils::getDao('cartaoidentificacaosituacao');
  	  $sSqlSituacao = $oDaoCartaoIdentificacaoSituacao->sql_query_file($iSequencialSituacao);
  	  $rsSituacao   = $oDaoCartaoIdentificacaoSituacao->sql_record($sSqlSituacao);
  	  if ($rsSituacao && $oDaoCartaoIdentificacaoSituacao->numrows > 0 ) {
  	  	
  	  	$oSituacao = db_utils::fieldsMemory($rsSituacao,0);  	  	  	  	
  	  	$this->iSequencialSituacao = $oSituacao->ed307_sequencial;
  	  	$this->sDescricao          = $oSituacao->ed307_descricao;
  	  	$this->lAtivo              = ($oSituacao->ed307_ativo=='t'?true:false);
  	  }else{
        return false;
      } 
  	}  	
  	return true;
  }
  /**
   * metodo get para propriedade sDescricao
   *
   * @return string
   */
  public function getDescricao(){
  	
  	return $this->sDescricao;  	
  }
  
  /**
   * metodo get para propriedade iSequencialSituacao
   *
   * @return integer
   */
  public function getCodigoSituacao(){
    
    return $this->iSequencialSituacao;
  }

  /**
   * metodo get para propriedade lAtivo
   *
   * @return boolean
   */
  public function isAtivo(){
    
    if ($this->lAtivo == null){ 
      return false;           
    }   
    return $this->lAtivo;   
  }
  

}