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


class LancamentoAuxiliarFactory {

  /**
   * Metodo que irá decidir qual 
   * tipo de lançamento auxiliar irá construir, retornando um lançamento auxiliar preenchido 
   * 
   * @param integer $iDocumento
   * @param integer $iLancamento
   * @return LancamentoAuxiliar   
   */
	public static function getInstance($iDocumento, $iLancamento) {
		
		switch ($iDocumento) {
			
			case "80":
			case "81":	
				return LancamentoAuxiliarInscricao::getInstance($iLancamento);
			break;

			case "120":
      case "130":
      case "140":
      case "150":
      case "151":
      case "160":
      case "161":
          return LancamentoAuxiliarSlip::getInstance($iLancamento);
      break;  
      
      case "210" :
      case "211" :
        return LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado::getInstance($iLancamento);
      break;

			case "414":
			case "415":
			case "90" :
			case "91" :
			case "92" :
				return LancamentoAuxiliarEmpenho::getInstance($iLancamento);
			break;

		  case "508":
		  case "509":
		  case "510":
		  case "511":
		  case "513":
		  case "514":
		  	return LancamentoAuxiliarReconhecimentoContabil::getInstance($iLancamento);
		  break;	
			
			case "700":
			case "701":
			case "703":
				return LancamentoAuxiliarBem::getInstance($iLancamento);
			break;	
			
			case "900":
			case "901":
			case "903":
			case "904":
				return LancamentoAuxiliarAcordo::getInstance($iLancamento);
			break;	
			
		}
		
	}

}