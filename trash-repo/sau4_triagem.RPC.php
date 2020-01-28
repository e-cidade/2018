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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

	switch($oParam->exec) {
		
		/**
		 * Retorna os procedimentos de triagem configurados
		 * @return array $oRetorno->aProcedimentos
		 */
		case 'getProcedimentosConfigurados':
			
			$oRetorno->aProcedimentos   = array();
			$oDaoProcedimentoTriagem    = db_utils::getDao("parametroprocedimentotriagem");
			$sCamposProcedimentoTriagem = "s166_sau_procedimento, sd63_c_procedimento, sd63_c_nome";
			$sSqlProcedimentoTriagem    = $oDaoProcedimentoTriagem->sql_query(
				                                                               	null, 
				                                                               	$sCamposProcedimentoTriagem
				                                                               );
			$rsProcedimentoTriagem      = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
			$iTotalProcedimentoTriagem  = $oDaoProcedimentoTriagem->numrows;
			
			if ( $iTotalProcedimentoTriagem > 0 ) {
				
				for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {
					
					$oDadosProcedimentosTriagem                 = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador);
					$oRetornoProcedimentoTriagem                = new stdClass();
					$oRetornoProcedimentoTriagem->iCodigo       = $oDadosProcedimentosTriagem->s166_sau_procedimento;
					$oRetornoProcedimentoTriagem->iProcedimento = urlencode($oDadosProcedimentosTriagem->sd63_c_procedimento);
					$oRetornoProcedimentoTriagem->sDescricao    = urlencode($oDadosProcedimentosTriagem->sd63_c_nome);
					$oRetorno->aProcedimentos[]                 = $oRetornoProcedimentoTriagem;
					unset($oRetornoProcedimentoTriagem);
				}
			}
			break;
			
	  /**
	   * Salva os procedimentos de triagem configurados
	   * @param integer $oParam->iProcedimento
	   */
		case 'salvarProcedimentos':
			
			db_inicio_transacao();
			
			$oDaoProcedimentoTriagem   = db_utils::getDao("parametroprocedimentotriagem");
			$sWhereProcedimentoTriagem = "s166_sau_procedimento = {$oParam->iProcedimento}";
			$sSqlProcedimentoTriagem   = $oDaoProcedimentoTriagem->sql_query(
				                                                              	null, 
				                                                              	"s166_sequencial, sd63_c_nome",
				                                                              	null,
				                                                              	$sWhereProcedimentoTriagem
				                                                              );
			$rsProcedimentoTriagem     = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
			$oRetorno->message         = urlencode('Procedimento salvo com sucesso.');
			
			if ( $oDaoProcedimentoTriagem->numrows > 0 ) {
				
				$oDadosProcedimentosTriagem = db_utils::fieldsMemory($rsProcedimentoTriagem, 0);
				$oRetorno->status  = 2;
				$oRetorno->message = urlencode("Procedimento '{$oDadosProcedimentosTriagem->sd63_c_nome}' já esta cadastrado.");
				unset($oDadosProcedimentosTriagem);
			} else {
				
				$oDaoProcedimentoTriagem->s166_sau_procedimento = $oParam->iProcedimento;
				$oDaoProcedimentoTriagem->incluir(null);
				
				if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
					throw new DBException($oDaoProcedimentoTriagem->erro_msg);
				}
			}
			
			db_fim_transacao();
			break;
			
		/**
		 * Exclui um ou mais procedimentos de triagem
		 * @param array $oParam->aProcedimentos
		 */
		case 'excluirProcedimentos':
			
			if ( isset($oParam->aProcedimentos) && count($oParam->aProcedimentos) > 0 ) {

				db_inicio_transacao();
				$oRetorno->message = urlencode('Procedimento excluido com sucesso.');
				
				$oDaoProcedimentoTriagem   = db_utils::getDao("parametroprocedimentotriagem");
				$sWhereProcedimentoTriagem = "s166_sau_procedimento in (" . implode(", ", $oParam->aProcedimentos) . ")";
					
				$oDaoProcedimentoTriagem->excluir(null, $sWhereProcedimentoTriagem);
					
				if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
					throw new DBException($oDaoProcedimentoTriagem->erro_msg);
				}
				
				db_fim_transacao();
			}
			break;
	}
} catch (ParameterException $oErro) {

	db_fim_transacao(true);
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

	db_fim_transacao(true);
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

	db_fim_transacao(true);
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>