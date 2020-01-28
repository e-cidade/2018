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
 * 
 * INTEGRAÇÃO TJ RJ VIA WEBSERVICE
 * Fonte Incluido no Arquivo cai3_emitecarne.RPC.php
 *
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */
db_inicio_transacao();
try {

	/**
	 * Percorre os Recibos que tenham custas emitidas e vinculadas a um processo do foro
	 */
	db_app::import('juridico.ClienteWebServiceTribunalJusticaRJ');
	$oClienteWebService = new ClienteWebServiceTribunalJusticaRJ();

	foreach ( $aRecibosComCustasEmitidos as $oValidacaoTJ ) {

		try {

			$oClienteWebService->setRecibo($oValidacaoTJ->oRecibo);
			$aRetornoWebService = $oClienteWebService->validarEmissaoProcessoForo();
			$lInconsistencia    = false;

			foreach ( $aRetornoWebService as $sCodigoInconsistencia => $sDescricaoInconsistencia ) {

				if ( $sCodigoInconsistencia != '0' ) {
					$lInconsistencia = true;
				}
			}

			if ($lInconsistencia) {
				/**
				 * Utilizado codigo de exceção para ser tratada diferente.
				 */
				throw new Exception("Cancelado pois validação com WebService retornou os seguintes erros: \n\n".implode(",\n", $aRetornoWebService).".", 99);
			}
		} catch (Exception $eErroWebService) {


			$sInconsistenciaWebService = "Erro ao Validar Recibo via WebService \n\n".$eErroWebService->getMessage();
			$oValidacaoTJ->oRecibo->cancelar($sInconsistenciaWebService);
			throw new Exception($sInconsistenciaWebService, $eErroWebService->getCode());
		}

	}
	db_fim_transacao(false);
} catch (Exception $eErro) {

	if ($eErro->getCode() == 99 ) {
		db_fim_transacao(false);
	} else {
		db_fim_transacao(true);
	}

	$oRetorno->status  = 2;
	$oRetorno->message = $eErro->getMessage();
}