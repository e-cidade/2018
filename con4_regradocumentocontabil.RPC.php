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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");

db_app::import("contabilidade.*");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {
	
	/**
	 * Salva os dados da regra em um documento
	 */
	case "salvarRegra":
		
		db_inicio_transacao();
		try {
			
			$oDocumentoContabilRegra = new DocumentoContabilRegra($oParam->c92_sequencial);
			$oDocumentoContabilRegra->setCodigoDocumento($oParam->c92_conhistdoc);
			$oDocumentoContabilRegra->setAno(db_getsession('DB_anousu'));
			$oDocumentoContabilRegra->setDescricao($oParam->c92_descricao);
			$oDocumentoContabilRegra->setRegra(addslashes($oParam->c92_regra));
			$oDocumentoContabilRegra->salvar();
			
			$oRetorno->message = "Regra salva com sucesso.";
			$oRetorno->iCodigoRegra = $oDocumentoContabilRegra->getCodigo();
			db_fim_transacao(false);
		} catch (Exception $eErro) {
			
			$oRetorno->message = urlencode($eErro->getMessage());
			$oRetorno->status  = 2;
			db_fim_transacao(true);
		}
	break;
	
	
	/**
	 * Verifica se existe regra para o documento selecionado pelo usuário
	 * Caso exista, retorna as propriedades dessa regra
	 */
	case "getRegra":
		
		try {
			
			$oRetorno->c92_descricao  = "";
			$oRetorno->c92_regra      = "";
			$oRetorno->c92_sequencial = "";
			
			$oDaoConHistDoc = db_utils::getDao('conhistdoc');
			$sSqlBuscaTipoDocumento = $oDaoConHistDoc->sql_query_file(null, "c53_tipo", null, "c53_coddoc = {$oParam->iCodigoDocumento}");
			$rsBuscaTipoDocumento   = $oDaoConHistDoc->sql_record($sSqlBuscaTipoDocumento);
			if ($oDaoConHistDoc->erro_status == "0") {
			  throw new Exception("Ocorreu um erro ao executar o SQL que retorna as variáveis para configuração da regra.");
			}
			
			$iCodigoTipoDocumento = db_utils::fieldsMemory($rsBuscaTipoDocumento, 0)->c53_tipo;
			
			/*
			 * Percorremos o conjunto de regras e verificamos se ela já está cadastrada
			 * no sistema. Caso esteja, retorna a descrição e regra
			 */
			$oDocumentoContabil = new DocumentoContabil($iCodigoTipoDocumento);
			$aArrayRegras       = $oDocumentoContabil->getConjuntoRegra()->getRegras();
			foreach ($aArrayRegras as $iIndice => $oDocumentoContabilRegra) {
				
				if ($oDocumentoContabilRegra->getCodigoDocumento() == $oParam->iCodigoDocumento) {
					
					$oRetorno->c92_descricao  = urlencode($oDocumentoContabilRegra->getDescricao());
					$oRetorno->c92_regra      = $oDocumentoContabilRegra->getRegra();
					$oRetorno->c92_sequencial = $oDocumentoContabilRegra->getCodigo();
					break;
				}
			}
			
		} catch (Exception $eErro) {
			
			$oRetorno->message = $eErro->getMessage();
			$oRetorno->status  = 2;
		}
		
	break;
	
	/**
	 * Exclui a regra de um documento
	 */
	case "excluirRegra":
		
		
		db_inicio_transacao();
		try {
			
			$oDocumentoContabilRegra = new DocumentoContabilRegra($oParam->c92_sequencial);
			$oDocumentoContabilRegra->excluir();
			
			$oRetorno->message = urlencode("Regra excluída com sucesso.");
			db_fim_transacao(false);
		} catch (Exception $eErro) {
			
			$oRetorno->message = urlencode($eErro->getMessage());
			$oRetorno->status  = 2;
			db_fim_transacao(true);
		}
	break;
		
	
	/**
	 * Retorna as variaveis cadastradas para um tipo de documento
	 */
	case "getVariavel":
		
		try {
		  
		  $oDaoConHistDoc = db_utils::getDao('conhistdoc');
		  $sSqlBuscaTipoDocumento = $oDaoConHistDoc->sql_query_file(null, "c53_tipo", null, "c53_coddoc = {$oParam->iCodigoDocumento}");
		  $rsBuscaTipoDocumento   = $oDaoConHistDoc->sql_record($sSqlBuscaTipoDocumento);
		  if ($oDaoConHistDoc->erro_status == "0") {
		  	throw new Exception("Ocorreu um erro ao executar o SQL que retorna as variáveis para configuração da regra.");
		  }
		  	
		  $iCodigoTipoDocumento = db_utils::fieldsMemory($rsBuscaTipoDocumento, 0)->c53_tipo;
			
			$oDocumentoContabil = new DocumentoContabil($iCodigoTipoDocumento);
			$aVariaveis         = array();
			$aDocumentoVariavel = $oDocumentoContabil->getInformacoesVariaveis();
			
			foreach ($aDocumentoVariavel as $oVariavel) {
			  $oVariavel->c93_descricao = urlencode($oVariavel->c93_descricao);
				$aVariaveis[] = $oVariavel;
			}
			$oRetorno->aVariavel = $aVariaveis;
			
		}	catch (Exception $eErro) {
			
			$oRetorno->message = urlencode($eErro->getMessage());
			$oRetorno->status  = 2;
		}	
  break;
	
  
  /**
   * Valida o SQL digitado pelo usuário
   */
	case "validaSQL":
		
		try {
			
			/*
			 * Criei um array de variável que estão bloqueadas para impedir que o
			 * usuário execute comandos como update delete e insert na base de dados
			 */
			$aComandosImpedidos = array("update", "delete", "insert");
			$sSqlUsuario        = strtolower($oParam->c92_regra);
			
			foreach ($aComandosImpedidos as $sComando) {
				
				if (strpos($sSqlUsuario, $sComando)) {
					throw new Exception("Não pode ser utilizado o compando '{$sComando}'.");
				}
			}
		  
			/*
			 * Adicionado um ARROBA (@) para esconder mensagem de erro. Isso porque poderá ocorrer um erro de sintaxe na configuração
			 * do SQL e isso fará com que a mensagem de erro não retorne ao usuário para que ele possa corrigir, com o @ nós bloqueamos
			 * esta quebra e retornamos em uma mensagem 'amigavel' com o pg_last_error 
			 */
			$rsValidaQuery = @db_query($sSqlUsuario);
			if (!$rsValidaQuery) {
				throw new Exception("Erro ao executar SQL.\n\n".pg_last_error());
			}
			$oRetorno->message = "SQL configurado corretamente.";
			
		} catch (Exception $eErro) {

			$oRetorno->message = urlencode($eErro->getMessage());
			$oRetorno->status  = 2;
		}
	break;
}
echo $oJson->encode($oRetorno);