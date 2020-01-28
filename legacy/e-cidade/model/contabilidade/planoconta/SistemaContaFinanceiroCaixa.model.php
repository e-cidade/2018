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
* Classe responsável pelo comportamento do sistema de conta "Financeiro Caixa"
* @author dbseller
* @name SistemaContaFinanceiroCaixa
* @package contabilidade
* @subpackage planoconta
*/

require_once 'model/contabilidade/planoconta/interface/ISistemaConta.interface.php';

class SistemaContaFinanceiroCaixa implements ISistemaConta {
  
  private $iCodigo    = 5;
  private $sDescricao = 'FINANCEIRO - CAIXA'; 
	
	/**
	 * Método que define o comportamento do sistema de conta
	 *
	 * @param ContaPlano $oContaPlano
	 * @return boolean
	 */
	public function integrarDados(ContaPlano $oContaPlano) {
		return true;
	}
	
	/**
	 * @see ISistemaConta::excluirDadosIntegrados()
	 */
	public function excluirDadosIntegrados(ContaPlano $oContaPlano) {
	  return true;
	}
	
	/** Retorna o código do tipo Financeiro - Caixa
	* @see ISistemaConta::getCodigoSistemaConta()
	*/
	public function getCodigoSistemaConta() {
	
	  return $this->iCodigo;
	} 
	
  public function getDescricao() {
    return $this->sDescricao;
  }
}
?>