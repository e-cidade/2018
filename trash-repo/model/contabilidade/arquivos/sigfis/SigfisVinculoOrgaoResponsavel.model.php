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
 * Classe Singleton para verificaчуo de vinculo de Orgуos e Responsсveis
 * @author vincius.silva@dbseller.combr
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisVinculoOrgaoResponsavel {
	
	static $oInstance;
	
	protected $aListaOrgaos = array();
	
	/**
	 * mщtodo construtor
	 *
	 */
	protected function __construct() {
		
		$oDomXml = new DOMDocument();
		$oDomXml->load('config/sigfis/vinculaorgaoresponsavel.xml');
		$aOrgaos = $oDomXml->getElementsByTagName('orgao');
		foreach ($aOrgaos as $oOrgao) {
			
			$oOrgaoRetornoLinha                     = new stdClass();
			$oOrgaoRetornoLinha->codigoorgao        = $oOrgao->getAttribute('codigoorgao');
			$oOrgaoRetornoLinha->cpfresponsavel     = $oOrgao->getAttribute('cpfresponsavel');
			$oOrgaoRetornoLinha->tipogestaocreditos = $oOrgao->getAttribute('tipogestaocreditos');
			$oOrgaoRetornoLinha->datainiciogestao   = $oOrgao->getAttribute('datainiciogestao');
			$oOrgaoRetornoLinha->tipoordenador      = $oOrgao->getAttribute('tipoordenador');
		  $this->aListaOrgaos[]                   = $oOrgaoRetornoLinha;
		}
	}
	
	/**
	 * Retorna a instancia da classe
	 * @return SigfisVinculoOrgaoResponsavel
	 */
	protected function getInstance() {
		
		if (self::$oInstance == null) {
			self::$oInstance = new SigfisVinculoOrgaoResponsavel(); 
		}
		return self::$oInstance;
	}
	
	/**
	 * Verifica se o Orgуo passado no parametro estс incluso no array de orgуos do xml
	 * @param integer $iCodigoOrgao
	 * @return retorna objeto do orgуo, ou false se ele nуo existir no array de orgуos
	 */
	public function getVinculoOrgaoResponsavel($iCodigoOrgao) {
		
		$aOrgaos  = self::getInstance()->aListaOrgaos;
		$mRetorno = false;
		foreach ($aOrgaos as $oOrgao) {
			
			if ($oOrgao->codigoorgao == $iCodigoOrgao) {
				
				$mRetorno = $oOrgao;
				break;
			}
		}
		return $mRetorno;
	}
}
?>