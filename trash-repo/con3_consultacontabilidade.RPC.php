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
 
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMensagem = '';

try {

	switch ($oParam->exec) {
		
		/**
		 * lan�amento
		 * documento
		 * data
		 * valor
		 * 
		 * conlancam
		 * conlancamdoc
		 * conhistdoc
		 */
		case "getDadosLancamentosFiltrados" :

			if ( empty($oParam->iDocumento) ) {
				throw new Exception('C�digo do documento n�o informado');
			}

			
			$aWhere = array();
			
			$aWhere[] = "c53_coddoc = {$oParam->iDocumento}";

			if ( !empty($oParam->dtInicio) ) {      
				$aWhere[] = "c70_data >= '{$oParam->dtInicio}'";      
			}
			
			if ( !empty($oParam->dtInicio) ) {      
				$aWhere[] = "c70_data <= '{$oParam->dtFim}'";      
			}
			
			if ( !empty($oParam->nValorInicio) ) {      
				$aWhere[] = "c70_valor >= {$oParam->nValorInicio}";      
			}

			if ( !empty($oParam->nValorFim) ) {      
				$aWhere[] = "c70_valor <= {$oParam->nValorFim}";      
			}
			
			$sWhere  = implode(" and ",$aWhere);
			$oDaoConlamcamdoc = db_utils::getDao("conlancamdoc");
			$sSqlConlamcamdoc = $oDaoConlamcamdoc->sql_query(null, "*", " c71_codlan ", $sWhere);
			$rsComlancamdoc   = $oDaoConlamcamdoc->sql_record($sSqlConlamcamdoc);  

			if ($rsComlancamdoc && $oDaoConlamcamdoc->numrows == 0 ) {
				throw new Exception('N�o existe registros para estes filtros');
			}

			if ($oDaoConlamcamdoc->numrows > 1000) {
			  throw new Exception("Identificamos que h� muitos registros para o filtro selecionado. Por favor, refine sua busca.");
			}

			$aLancamentos = array();
			/**
			 * Percorre os lancamentos e monta array para consulta
			 */	 
			for ($iLancamento = 0 ; $iLancamento < $oDaoConlamcamdoc->numrows; $iLancamento++ ) {

				$oLancamentoRs  = db_utils::fieldsMemory($rsComlancamdoc, $iLancamento);	
				$oLancamentoStd = new stdClass();			

				$oLancamentoStd->sDocumento  = $oLancamentoRs->c53_coddoc ." - " . urlencode($oLancamentoRs->c53_descr);
				$oLancamentoStd->dtData			 = db_formatar($oLancamentoRs->c70_data, 'd');
				$oLancamentoStd->iLancamento = $oLancamentoRs->c70_codlan;
				$oLancamentoStd->nValor      = $oLancamentoRs->c70_valor;

				$aLancamentos[] = $oLancamentoStd;
			}
			$oRetorno->aLancamentos = $aLancamentos;
		break;

	}

	$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);

} catch (Exception $eErro){

	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);