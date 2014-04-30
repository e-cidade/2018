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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_libpessoal.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$lErro                  = false;

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

switch ($oParametros->sExec) {

	case "processar":

		db_inicio_transacao();

		try {
			
			if (empty($oParametros->iAnoAfastamento) || $oParametros->iAnoAfastamento < 0) {
				throw new Exception('Ano de afastamento não informado ou inválido.');
			}
			
			if (empty($oParametros->iMesAfastamento) || $oParametros->iMesAfastamento < 0) {
				throw new Exception('Mês de afastamento não informado ou inválido.');
			}
				
			$oDaoRHVisaVale = db_utils::getDao('rhvisavale');
			$rsRhVisaVale   = $oDaoRHVisaVale->sql_record($oDaoRHVisaVale->sql_query_file());
				
			if($oDaoRHVisaVale->numrows == 0){
				throw new Exception('Erro: Não existe configuração na tabela rhvisavale.');
			}

			$oRHVisaVale = db_utils::fieldsMemory($rsRhVisaVale, 0);

			if (empty($oRHVisaVale->rh47_diasuteis)) {
				throw new Exception("Erro: Dias úteis não configurado na tabela rhvisavale.");
			}

			$oDaoRHVisaValeCad         = db_utils::getDao('rhvisavalecad');
			
			/**
			 * Caso seja informada uma seleção, verifica se ela é válida antes do processamento
			 */
			if (!empty($oParametros->iCodigoSelecao)) {
				
				$oDaoSelecao = db_utils::getDao('selecao');
				
				$rsSelecao   = $oDaoSelecao->sql_record($oDaoSelecao->sql_query_file($oParametros->iCodigoSelecao));
				
				$oSelecao    = db_utils::fieldsMemory($rsSelecao, 0);
				
				if($oDaoSelecao->numrows == 0 || empty($oSelecao->r44_where)){
					throw new Exception('Seleção não encontrada ou inválida.');
				}
				
				$sWhereRHVisaValeCadComSelecao  = "     rh49_anousu = {$oParametros->iAnoFolha} ";
				$sWhereRHVisaValeCadComSelecao .= " and rh49_mesusu = {$oParametros->iMesFolha} ";
				$sWhereRHVisaValeCadComSelecao .= " and {$oSelecao->r44_where}		  						";
				
				$sSqlRHVisaValeCad         = $oDaoRHVisaValeCad->sql_query(null, "rhvisavalecad.*", null, $sWhereRHVisaValeCadComSelecao);
				
				$rsRHVisaValeCadComSelecao = $oDaoRHVisaValeCad->sql_record($sSqlRHVisaValeCad);
				
				if (!$rsRHVisaValeCadComSelecao) {
					throw new Exception ('Consulta inválida. Verifique a seleção informada.');
				}
				
			}
			
			/**
			 * 1º Processamento sem seleção 
			 */
			
			$sWhereSemSelecao  = "rh49_anousu = {$oParametros->iAnoFolha} and rh49_mesusu = {$oParametros->iMesFolha}";
			
			$sSqlRHVisaValeCad = $oDaoRHVisaValeCad->sql_query_file(null, "*", null, $sWhereSemSelecao);
			
			$rsRhVisaValeCad   = $oDaoRHVisaValeCad->sql_record($sSqlRHVisaValeCad);
				
			if ($oDaoRHVisaValeCad->numrows == 0) {
				throw new Exception('Erro: Nenhum registro encontrado na tabela rhvisavalecad.');
			}

			foreach (db_utils::getCollectionByRecord($rsRhVisaValeCad) as $oRHVisaValeCad) {

				$sSqlDiasAfastados   = "select * from fc_calcula_afastamento($oRHVisaValeCad->rh49_regist,  ";
				$sSqlDiasAfastados  .= "                                     $oParametros->iAnoAfastamento, ";
				$sSqlDiasAfastados  .= "                                     $oParametros->iMesAfastamento) ";

				$rsDiasAfastados     = db_query($sSqlDiasAfastados);

        if ( !$rsDiasAfastados ) {
        	
        	$sMensagemErro  = "Usuário: \n\nErro ao calcular asfatamento para matrícula {$oRHVisaValeCad->rh49_regist}.";
        	$sMensagemErro .= "\n\nAdministrador: \n\n".pg_last_error();
          throw new Exception($sMensagemErro);
        }

				$oCalculoAfastamento = db_utils::fieldsMemory($rsDiasAfastados, 0);

				$iDiasAfastados      = $oCalculoAfastamento->ridiasmes    +
															 $oCalculoAfastamento->ridiasmesant +
															 $oCalculoAfastamento->ridiasferias +
															 $oCalculoAfastamento->ridiasafasta;

				$nValorMes           = (($oRHVisaValeCad->rh49_valor * ($oRHVisaValeCad->rh49_percdep/100)) / $oRHVisaVale->rh47_diasuteis * ($oRHVisaVale->rh47_diasuteis - $iDiasAfastados));

				if ($nValorMes < 0) {
					$nValorMes = 0;					
				}

				$oDaoRHVisaValeCad->rh49_diasafasta = "$iDiasAfastados";
				$oDaoRHVisaValeCad->rh49_valormes   = "$nValorMes";
				$oDaoRHVisaValeCad->rh49_codigo     = $oRHVisaValeCad->rh49_codigo;
				$oDaoRHVisaValeCad->alterar($oRHVisaValeCad->rh49_codigo);
				
				if ($oDaoRHVisaValeCad->erro_status == "0") {
					throw new Exception('Erro ao alterar dados da rhvisavalecad. ERRO: ' . $oDaoRHVisaValeCad->erro_msg);
				}

			}

			/**
			 * Procesamento caso haja seleção
			 */	
			
			if (empty($oParametros->iCodigoSelecao)) {
				
				db_fim_transacao();
				
				break;
				
			}

			foreach (db_utils::getCollectionByRecord($rsRHVisaValeCadComSelecao) as $oRHVisaValeCad) {
				
				$oDaoRHVisaValeCad->rh49_valormes   = "0";
				$oDaoRHVisaValeCad->rh49_codigo     = $oRHVisaValeCad->rh49_codigo;
				$oDaoRHVisaValeCad->alterar($oRHVisaValeCad->rh49_codigo);
				
				if ($oDaoRHVisaValeCad->erro_status == "0") {
					throw new Exception('Erro ao alterar dados da rhvisavalecad. ERRO: ' . $oDaoRHVisaValeCad->erro_msg);
				}
				
			}

		} catch (Exception $oErro) {
				
			$oRetorno->iStatus  = 2;
				
			$oRetorno->sMessage = urlencode($oErro->getMessage());
				
			$lErro              = true;
				
		}

		db_fim_transacao($lErro);
		
		break;

}

echo $oJson->encode($oRetorno);











?>