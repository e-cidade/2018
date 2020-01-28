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

require_once("model/issqn/AlvaraMovimentacao.model.php");

/**
 * @deprecated
 * @see model/issqn/alvara/CancelamentoAlvara.model.php
 */
class AlvaraCancelamento extends AlvaraMovimentacao {

	/**
	 * Método construtor. Seta qual o alvará será alterado
	 * @param integer $iCodigoAlvara código do alavará que será cancelado
	 */
	function __construct($iCodigoAlvara) {
		parent::__construct($iCodigoAlvara);
	}
	
	function getUltimaMovimentacao() {
		
		try {
			
			$aMovimentacoes       = parent::getUltimaMovimentacao();
			$aGuardaMovimentacoes = array("0");
			foreach ($aMovimentacoes as $oMovimentacao) {
			  $aGuardaMovimentacoes [$oMovimentacao->q120_sequencial] = array(
			                                                              "q121_descr"           =>$oMovimentacao->q121_descr, 
			                                                              "q120_dtmov"           =>$oMovimentacao->q120_dtmov,
			                                                              "q120_sequencial"      =>$oMovimentacao->q120_sequencial,
			                                                              "q120_isstipomovalvara"=>$oMovimentacao->q120_isstipomovalvara
			                                                            );   
			}
			
			
			if (count($aGuardaMovimentacoes) == 0) {
				throw new Exception('Alvará sem movimentações.');
			} else {
				return max($aGuardaMovimentacoes);
			}
		} catch (ErrorException $eErro){
	    throw new ErrorException($eErro->getMessage());
		}
	}
		
	/**
	 * Efetua o cancelamento gerando um novo registro identico ao movimento
	 * anterior ao que está sendo cancelado
	 */
	function cancelaUltimaMovimentacao() {
		parent::salvar();
	}
	
}
?>