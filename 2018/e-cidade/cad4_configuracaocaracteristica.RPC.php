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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define('MENSAGENS', 'tributario.cadastro.cad4_configuracaocaracteristica.');

try {
	
	switch ($oParametros->sExecucao) {
	
		case "getGruposCaracteristicasPit":
			
			$oDaoGrupoCaracteristica = new cl_grupocaracteristica;
			$sSqlGrupoCaracteristica = $oDaoGrupoCaracteristica->sql_query( null, "db139_sequencial, db139_descricao", "db139_descricao ASC", "db139_grupoutilizacao = 2");
			$rsGrupoCaracteristica   = db_query($sSqlGrupoCaracteristica);

			if(!$rsGrupoCaracteristica){
				throw new DBException(_M(MENSAGENS . "erro_busca_grupo_caracteristica"));
			}
			
			if (pg_num_rows($rsGrupoCaracteristica) == 0) {
				throw new BusinessException( _M(MENSAGENS . "nenhum_grupo_encontrado") );
			}
			
			$oDadosGrupoCaracteristica          = db_utils::getCollectionByRecord($rsGrupoCaracteristica, false, false, true);
			$oRetorno->oGruposCaracteristicaPit = $oDadosGrupoCaracteristica;
						
		break;
		
		case "getCaracteristicasPitPorGrupo":
		
			if (empty($oParametros->iGrupoCaracteristica)) {
				throw new BusinessException( _M(MENSAGENS . "nenhum_grupo_encontrado") );
			}
			
			$oDaoCaracteristica    = new cl_caracteristica();
			
			$sWhere 							 = "db140_grupocaracteristica = " . $oParametros->iGrupoCaracteristica;
			$sSqlCaracteristicaPit = $oDaoCaracteristica->sql_query( null, "db140_sequencial, db140_descricao", "db140_descricao ASC", $sWhere );
			
			$rsCaracteristicaPit   = db_query($sSqlCaracteristicaPit);
			
			if(!$rsCaracteristicaPit){
				throw new DBException( _M(MENSAGENS . "erro_busca_caracteristica_pit") );
			}
			
			if (pg_num_rows($rsCaracteristicaPit) == 0) {
				throw new BusinessException( _M(MENSAGENS . "nenhuma_caracteristica_encontrada") );
			}
			
			$oDadosCaracteristicaPit 			 = db_utils::getCollectionByRecord($rsCaracteristicaPit, false, false, true);
			$oRetorno->oCaracteristicasPit = $oDadosCaracteristicaPit;
						
		break;
		
		case "getCaracteristicasCadastro":
			
			if (empty($oParametros->iCaracteristicaPit)) {
				throw new ParameterException( _M(MENSAGENS . "caracteristica_pit_invalida") );
			}
						
			$sSqlCaracter  = "select j32_descr, j31_descr, j31_codigo,                                  ";
			$sSqlCaracter .= "(exists(                                                                  ";
			$sSqlCaracter .= "        select 1                                                          ";
			$sSqlCaracter .= "          from caractercaracteristica                                     ";
			$sSqlCaracter .= "         where db143_caracter = j31_codigo                                ";
			$sSqlCaracter .= "           and db143_caracteristica = {$oParametros->iCaracteristicaPit}  ";
			$sSqlCaracter .= ")) as lSelecionado                                                        ";
			$sSqlCaracter .= "  from caracter                                                           ";
			$sSqlCaracter .= " inner join cargrup on j31_grupo = j32_grupo                              ";
			$sSqlCaracter .= " order by 1,2;                                                            ";
			
			$rsCaracter = db_query($sSqlCaracter);
			
			if(!$rsCaracter){
				throw new DBException( _M(MENSAGENS . "erro_busca_caracter") );
			}
			
			if (pg_num_rows($rsCaracter) == 0) {
				throw new BusinessException( _M(MENSAGENS . "nenhum_caracter_encontrado") );
			}
			
			$oDadosCaracter = db_utils::getCollectionByRecord($rsCaracter, false, false, true);
			
			$oRetorno->oCaracteristicasCadastro = $oDadosCaracter;
		break;
		
		case "salvar":
				
			db_inicio_transacao();
				
			if (empty($oParametros->iCaracteristicaPit)) {
				throw new ParameterException( _M(MENSAGENS . "caracteristica_pit_invalida") );
			}
				
			$oDaoCaracterCaracteristica = new cl_caractercaracteristica();
				
			if ( !$oDaoCaracterCaracteristica->excluir( null, "db143_caracteristica = " . $oParametros->iCaracteristicaPit ) ) {
				throw new BusinessException(_M(MENSAGENS . "erro_exclusao_vinculacao"));
			}
				
			foreach ($oParametros->aCaracteristicas as $iCaracter) {
		
				$oDaoCaracterCaracteristica->db143_caracteristica = $oParametros->iCaracteristicaPit;
				$oDaoCaracterCaracteristica->db143_caracter       = $iCaracter;
				
				if (!$oDaoCaracterCaracteristica->incluir(null)) {
					throw new BusinessException( _M(MENSAGENS . "erro_inclusao_validacao") );
				}
			}
			
			db_fim_transacao();
				
			break;
	}
	
} catch (Exception $oErro) {

	db_fim_transacao(true);
	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);