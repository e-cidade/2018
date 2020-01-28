<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/SimulaCalculoInscricao.model.php");

$oJson    = new services_json();
$oRetorno = new stdClass();

$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$lErro = false;

switch ($oParam->sExec) {
	
	case 'simular':
		
		$oDaoIsssimulacalculo 				   = db_utils::getDao('isssimulacalculo');
		$oDaoIsssimulacalculoatividade   = db_utils::getDao('isssimulacalculoatividade');
	  $oDaoIsssimulacalculoTipoCalculo = db_utils::getDao('isssimulacalculotipocalculo');
		db_inicio_transacao();
		
		try {

		  $oDaoIsssimulacalculo->q130_cadescrito    = ($oParam->iEscritorio == "")?"null":$oParam->iEscritorio;
			$oDaoIsssimulacalculo->q130_cnpjcpf       = $oParam->iCpfCpnj;
			$oDaoIsssimulacalculo->q130_razaosocial   = str_replace("x86","&",$oParam->sRazaoSocial);
			$oDaoIsssimulacalculo->q130_email         = $oParam->sEmail;
			$oDaoIsssimulacalculo->q130_logradouro    = $oParam->iCodigoLogradouro;
			$oDaoIsssimulacalculo->q130_bairro        = $oParam->iCodigoBairro;
			$oDaoIsssimulacalculo->q130_numero        = $oParam->iNumero;
			$oDaoIsssimulacalculo->q130_complemento   = $oParam->sComplemento;
			$oDaoIsssimulacalculo->q130_zona          = $oParam->iZona;
			$oDaoIsssimulacalculo->q130_empregados    = $oParam->iNumeroEmpregados;
			$oDaoIsssimulacalculo->q130_area          = $oParam->nArea;
			$oDaoIsssimulacalculo->q130_datainicio    = $oParam->dDataInicio;
			$oDaoIsssimulacalculo->q130_telefone      = $oParam->iTelefone;
			$oDaoIsssimulacalculo->q130_multiplicador = 1;
			$oDaoIsssimulacalculo->q130_datacalculo   =	date("Y-m-d", db_getsession("DB_datausu"));			
			$oDaoIsssimulacalculo->incluir(null);     
			if ($oDaoIsssimulacalculo->erro_status == "0") {
				throw new Exception("Erro ao incluir na tabela isssimulacalculo. ERRO: {$oDaoIsssimulacalculo->erro_msg}");
			}
			
			foreach ($oParam->aAtividades as $iSeq => $oAtividade) {

			  $oDaoIsssimulacalculoatividade->q131_atividade        = $oAtividade->iCodigoAtividade;
			  $oDaoIsssimulacalculoatividade->q131_issimulacalculo  = $oDaoIsssimulacalculo->q130_sequencial;
			  $oDaoIsssimulacalculoatividade->q131_principal        = ($oAtividade->lPrincipal == 1) ? 'true' : 'false';
			  $oDaoIsssimulacalculoatividade->q131_quantidade       = $oAtividade->iQuantidade;
			  $oDaoIsssimulacalculoatividade->q131_permanente       = 'true';
			  $oDaoIsssimulacalculoatividade->q131_seq              = $iSeq + 1;  
		    $oDaoIsssimulacalculoatividade->incluir(null);

			  if ($oDaoIsssimulacalculoatividade->erro_status == "0") {
			    throw new Exception("Erro ao incluir na tabela isssimulacalculoatividade. ERRO: {$oDaoIsssimulacalculoatividade->erro_msg}");
			  }
				
			}
			
			$oSimulaCalculoInscricao = new SimulaCalculoInscricao($oDaoIsssimulacalculo->q130_sequencial);
      $oCalculoSimulacao       = $oSimulaCalculoInscricao->processaSimulacao();
			if (isset($oCalculoSimulacao->lErro)) {
			  throw new Exception("Erro durante o processamento do calculo de Simulaчуo!\n\nErro: {$oCalculoSimulacao->sMsg}");
			}
			
			foreach ($oCalculoSimulacao as $oDadosCalculo) {
			    
			  $oDaoIsssimulacalculoTipoCalculo->q132_isssimulacalculo = $oDaoIsssimulacalculo->q130_sequencial;
			  $oDaoIsssimulacalculoTipoCalculo->q132_tipcalc          = $oDadosCalculo->iTipoCalculo;
			  $oDaoIsssimulacalculoTipoCalculo->q132_parcela          = $oDadosCalculo->iParcela;
			  $oDaoIsssimulacalculoTipoCalculo->q132_vencimento       = $oDadosCalculo->dVencimento;
			  $oDaoIsssimulacalculoTipoCalculo->q132_valor            = "$oDadosCalculo->nValor";
			  $oDaoIsssimulacalculoTipoCalculo->incluir(null);
			  if($oDaoIsssimulacalculoTipoCalculo->erro_status == "0") {
			    throw new Exception("Erro durante o armazenamento do calculo de Simulaчуo!\n\nErro: {$oDaoIsssimulacalculoTipoCalculo->erro_msg}");
			  }
			  
			}
			
      $oRetorno->iSimulacao = $oDaoIsssimulacalculo->q130_sequencial; 
      $oRetorno->oCalculo   = $oCalculoSimulacao;
      $oRetorno->sMessage   = "Calculo de Simulaчуo realizado com Sucesso!";
      
      db_fim_transacao(false);
			
		} catch (Exception $oException) {
			
			$oRetorno->sMessage = $oException->getMessage();
			db_fim_transacao(true);
			$oRetorno->iStatus  = 2;
		}
		
		break;
		
		
	case 'getNome':
		
		require_once('classes/db_cgm_classe.php');
		$oDaoCgm = new cl_cgm();
		$aRetornoDados = array();
		
		$iCgcCpf  = $oParam->z01_cgccpf;
		$sCampos  = "z01_nome, ";
		$sCampos .= "z01_email ";
		
		$sSqlDados = $oDaoCgm->sql_query_file(null, $sCampos, null, "z01_cgccpf = '{$iCgcCpf}' ");
		$rsDados   = $oDaoCgm->sql_record($sSqlDados);
		
    if ($oDaoCgm->numrows > 0) {
    	
    	$oDados    = db_utils::fieldsMemory($rsDados, 0);
    	
    	$oDdosCgm  = new stdClass();
    	$oDdosCgm->z01_nome  = $oDados->z01_nome;
    	$oDdosCgm->z01_email = $oDados->z01_email;
    	
    	$aRetornoDados[] = $oDdosCgm;
    	
  		$oRetorno->aDados = $aRetornoDados;
    	
    }	else {
    	
    	$oRetorno->iStatus  = 2;
    	$oRetorno->sMessage = "Cpf ou Cnpj, nao encontrado no sistema";
    }	
		

	break;	
	
}
$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);

?>