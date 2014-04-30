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
require_once("libs/db_libcontabilidade.php");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/ParameterException.php");
require_once("dbforms/db_funcoes.php");
require_once("model/patrimonio/Inventario.model.php");
require_once("model/patrimonio/InventarioBem.model.php");
require_once("model/patrimonio/TransferenciaBens.model.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/configuracao/DBDepartamento.model.php");
require_once("model/configuracao/DBDivisaoDepartamento.model.php");
require_once("model/CgmFactory.model.php");
require_once("classes/db_bensdepreciacao_classe.php");
require_once("std/db_stdClass.php");
require_once("model/patrimonio/depreciacao/CalculoBem.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once("std/DBNumber.php");
require_once("model/contabilidade/ParametroIntegracaoPatrimonial.model.php");

db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");


$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->message = '';

$oParam->sObservacao = 'Lançamento automático de depreciação';

$oDataImplantacao         = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
$oInstituicao             = new Instituicao(db_getsession('DB_instit'));
$lIntegracaoContabilidade = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao);


try {

  switch ($oParam->exec) {

    case 'getUltimaCompetencia':

      $lEstorno         = ($oParam->lEstorno == 'true' ? true : false);
      $oRetorno->oDados = BemDepreciacao::getCompetenciaDisponivel(db_getsession("DB_instit"), $lEstorno);

    break;

    case 'processar' :

    	db_inicio_transacao();

      if ( !$lIntegracaoContabilidade ) {
        throw new Exception("Integração com financeiro não habilitadada.");
      }

      $aDadosRetorno        = array();
			$oBemDepreciacao      = new BemDepreciacao($oParam->iAno, $oParam->iMes, $oParam->iInstituicao);
			$oParam->sObservacao .= " da competência {$oParam->iMes}/{$oParam->iAno}";
			
			
			$oBemDepreciacao->processarLancamentos($oParam->sObservacao);
			$oRetorno->message = urlencode("Processamento realizado com sucesso.");
			db_fim_transacao(false);

		break;

		case 'estornar' :

		  db_inicio_transacao();
			$aDadosRetorno        = array();
			$oBemDepreciacao      = new BemDepreciacao($oParam->iAno, $oParam->iMes, $oParam->iInstituicao);
			$oParam->sObservacao .= " da competência {$oParam->iMes}/{$oParam->iAno}";

      if ( !$lIntegracaoContabilidade ) {
        throw new Exception("Integração com financeiro não habilitadada.");
      }
			
			$oBemDepreciacao->estornarLancamentos($oParam->sObservacao);
			$oRetorno->message = urlencode("Processamento realizado com sucesso.");
			db_fim_transacao(false);

		break;

		case "getDadosSintetico":

			$aDadosRetorno   = array();
			$oBemDepreciacao = new BemDepreciacao($oParam->iAno, $oParam->iMes, db_getsession('DB_instit'));
			$aDadosSintetico = $oBemDepreciacao->getDadosSintetico();

			$sDocumentoExecutar = "604 - Depreciação";
			if (isset($oParam->lEstorno)) {
				$sDocumentoExecutar = "605 - Estorno de Depreciação";
			}
			$sDocumentoExecutar = urlencode($sDocumentoExecutar);
			foreach ($aDadosSintetico as $oStdSintetico) {

				$oStdDadosRetorno = new stdClass();
				$oStdDadosRetorno->iCodigoConta    = $oStdSintetico->iPlanoConta;
				$oStdDadosRetorno->sDescricaoConta = urlencode($oStdSintetico->sDescricaoConta);
				$oStdDadosRetorno->nValorTotal     = db_formatar($oStdSintetico->nValorDepreciacao, 'f');
				$oStdDadosRetorno->sDocumento      = $sDocumentoExecutar;
				$aDadosRetorno[] = $oStdDadosRetorno;
			}

			$oRetorno->aDadosDepreciacao = $aDadosRetorno;

		break;

    case "validarIntegracaoContabilidade" :

      /**
       * Nao efetua lancamento quando nao possuir integracao com contabilidade     
       */
      if ( !$lIntegracaoContabilidade ) {
        throw new BusinessException("A integração com a Contabilidade não está habilitada.");
      }

    break;  

    default:
      throw new ParameterException("Nenhuma Opção Definida - Contate Suporte");
    break;
    
  }

} catch (Exception $eErro){

	$oRetorno->iStatus   = 2;
	$oRetorno->message = urlencode($eErro->getMessage());
} catch (BusinessException $eErro) {

  $oRetorno->iStatus   = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
} catch (ParameterException $eErro) {

  $oRetorno->iStatus   = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);