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

require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson    = new services_json();
$oRetorno = new stdClass();

$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$lErro = false;

switch ($oParam->sExec) {
	case 'getConstrucoes':
		
		try {
			
			$oRetorno->aConstrucao = array();
			
			$oDAOObras = db_utils::getDao('obras');
			$sSqlObras = $oDAOObras->sql_query_obras_construcoes($oParam->iCodigoObra);
			$rsObras   = $oDAOObras->sql_record($sSqlObras);
	
			if ($oDAOObras->numrows == 0) {
			  
				throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nenhuma_construcao_encontrada'));				
			} 
			
			$oRetorno->oConstrucao = db_utils::fieldsMemory($rsObras, 0, true, false, true); 
				
				
		} catch (Exception $oException) {
			
			$oRetorno->iStatus  = 2;
			
			$oRetorno->sMessage = $oException->getMessage();
			
		}
		
		break;
		
	case 'getObra':

		try {
			
			$oDAOObrasAlvara = db_utils::getDao('obrasalvara');
			$sSqlObrasAlvara = $oDAOObrasAlvara->sql_query_obrasalvara($oParam->iCodigoObra);
			$rsObrasAlvara   = $oDAOObrasAlvara->sql_record($sSqlObrasAlvara);
			
			if ($oDAOObrasAlvara->numrows == 0) {
				
				throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nenhum_registro_encontrado'));
				
			}
			
			$oRetorno->oObra = db_utils::fieldsMemory($rsObrasAlvara, 0, true, false, true);
			
		} catch (Exception $oException) {
			
			$oRetorno->iStatus = 2;
			
			$oRetorno->sMessage = $oException->getMessage();
			
		}

		break;
		
	case 'salvaObraAlvara' :
		
		try {
			
			db_inicio_transacao();
			
			$oDAOObrasAlvara 						 = db_utils::getDao('obrasalvara');
			$oDAOObrasAlvaraProtProcesso = db_utils::getDao('obrasalvaraprotprocesso');
			
			if($oParam->iCodigoObra != '') {
				$rsObrasAlvara   = $oDAOObrasAlvara->sql_record($oDAOObrasAlvara->sql_query_file($oParam->iCodigoObra));
			}
			
			$oDAOObrasAlvara->ob04_codobra          = $oParam->iCodigoObra;
			$oDAOObrasAlvara->ob04_alvara           = $oParam->iCodigoAlvara;
			$oDAOObrasAlvara->ob04_data             = $oParam->dDtAlvara;
			$oDAOObrasAlvara->ob04_processo         = urldecode($oParam->sProcesso);
			$oDAOObrasAlvara->ob04_titularprocesso  = urldecode($oParam->sTitularProcesso);
			$oDAOObrasAlvara->ob04_dtprocesso       = $oParam->dDtProcesso;
			$oDAOObrasAlvara->ob04_obsprocesso      = db_stdClass::normalizeStringJson($oParam->sObservacao);
			$oDAOObrasAlvara->ob04_dtvalidade       = $oParam->dDtValidade;
			
			if($oDAOObrasAlvara->numrows > 0) {
				
				$oDAOObrasAlvara->ob04_codobra = $oParam->iCodigoObra;
				$oDAOObrasAlvara->alterar($oParam->iCodigoObra);
				
				if($oDAOObrasAlvara->erro_status == '0') {
					
				  $lErro           = true;
				  $oParms          = new stdClass();
				  $oParms->msgErro = $oDAOObrasAlvara->erro_msg;
				  
					throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_alterar_alvara', $oParms));
				}
				
				$oDAOObrasAlvaraProtProcesso->excluir(null, "ob26_obrasalvara = {$oParam->iCodigoObra}");
				
				if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
				  
					$lErro           = true;
					$oParms          = new stdClass();
					$oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;
					
					throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_processo', $oParms));
				}
				
				if($oParam->lProcessoSistema and !empty($oParam->iCodigoProcesso)) {
				
					$oDAOObrasAlvaraProtProcesso->ob26_obrasalvara  = $oParam->iCodigoObra;
					$oDAOObrasAlvaraProtProcesso->ob26_protprocesso = $oParam->iCodigoProcesso;
					$oDAOObrasAlvaraProtProcesso->incluir(null);
						
					if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
					  
						$lErro           = true;
						$oParms          = new stdClass();
						$oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;
						
						throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_processo', $oParms));
					}
						
				}
					
			} else {
				
				$oDAOObrasAlvara->incluirAlvara(null);
				
				if($oDAOObrasAlvara->erro_status == '0') {
					
				  $lErro           = true;
				  $oParms          = new stdClass();
				  $oParms->msgErro = $oDAOObrasAlvara->erro_msg;
				  
					throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_alvara', $oParms));
				}
				
				if($oParam->lProcessoSistema and !empty($oParam->iCodigoProcesso)) {
				  
					$oDAOObrasAlvaraProtProcesso->ob26_obrasalvara  = $oParam->iCodigoObra;
					$oDAOObrasAlvaraProtProcesso->ob26_protprocesso = $oParam->iCodigoProcesso;
					$oDAOObrasAlvaraProtProcesso->incluir(null);
					
					if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
					  
						$lErro           = true;
						$oParms          = new stdClass();
						$oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;
						
						throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_processo_2', $oParms));
					}
					
				}
				
			}
			
			$oRetorno->sMessage = $oDAOObrasAlvara->erro_msg;
			
			db_fim_transacao($lErro);
			
		} catch (Exception $oException) {

			$oRetorno->iStatus  = 2;
				
			$oRetorno->sMessage = $oException->getMessage();
			
		}
		
		break;		
		
	case 'excluirObraAlvara' :
		
		try {
			
			$oDAOObrasAlvara             = db_utils::getDao('obrasalvara');
			$oDAOObrasAlvaraProtProcesso = db_utils::getDao('obrasalvaraprotprocesso');
			
			db_inicio_transacao();
			
			if ($oParam->iCodigoObra == '') {
				throw new Exception(_M('tributario.projetos.pro1_obrasalvara.informe_codigo_obra'));
			}
			
			$oDAOObrasAlvaraProtProcesso->excluir(null, "ob26_obrasalvara = {$oParam->iCodigoObra}"); 	
			
			if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
			  
			  $oParms          = new stdClass();
			  $oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;
				throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_processo_alvara', $oParms));
			}

			 $oDAOObrasAlvara->excluir($oParam->iCodigoObra);
			
			if ($oDAOObrasAlvara->erro_status == '0') {
			  
			  $oParms          = new stdClass();
			  $oParms->msgErro = $oDAOObrasAlvara->erro_msg;
				throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_alvara', $oParms));
			}
			
			
			$oRetorno->sMessage = $oDAOObrasAlvara->erro_msg;
			
			db_fim_transacao();
			
		} catch (Exception $oException) {
			
			$oRetorno->iStatus  = 2;
			
			$oRetorno->sMessage = $oException->getMessage();
			
		}
		
		break;
}
$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);
?>