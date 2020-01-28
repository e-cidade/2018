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
require_once ("libs/JSON.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/patrimonio/Inventario.model.php");
require_once("model/patrimonio/InventarioBem.model.php");
require_once("model/patrimonio/TransferenciaBens.model.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once ("model/patrimonio/BemCedente.model.php");
require_once("model/configuracao/DBDepartamento.model.php");
require_once("model/configuracao/DBDivisaoDepartamento.model.php");
require_once("model/CgmFactory.model.php");
require_once("classes/db_bensdepreciacao_classe.php");
require_once ("std/db_stdClass.php");
require_once("model/patrimonio/depreciacao/CalculoBem.model.php");
require_once("std/DBNumber.php");

db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");


$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

try {

  switch ($oParam->exec) {

  	case "getItensIventario" :

  		$oInventario = new Inventario($oParam->iCodigoInventario);
  		$lEstorno    = true;

  		if( $oParam->lEstorno == 'false' ) {
  			$lEstorno = false;
  		}

  		$aDadosEscrituracaoInventario = $oInventario->getDadosEscrituracaoContabil($lEstorno);
  		$aRetorno 									  = array();

  		foreach ( $aDadosEscrituracaoInventario as $oDadosEscrituracao ) {

  			$oDadosRetorno									 = new stdClass();
  			$oDadosRetorno->sClassificacao   = $oDadosEscrituracao->iCodigoConta." - ". urlencode($oDadosEscrituracao->sDescricao);
  			$oDadosRetorno->nSaldoContabil   = db_formatar($oDadosEscrituracao->nSaldoAnterior,    'f');
  			$oDadosRetorno->nReavaliacao     = db_formatar($oDadosEscrituracao->nValorReavaliacao, 'f');
  			$oDadosRetorno->nAjuste          = db_formatar($oDadosEscrituracao->nValorReajuste,    'f');
  			$oDadosRetorno->nValorLancamento = db_formatar($oDadosEscrituracao->nValorLancamento,  'f');
  			$oDadosRetorno->sEvento          = $oDadosEscrituracao->iCodigoDocumento." - ".urlencode($oDadosEscrituracao->sDocumento);

  			$aRetorno[] = $oDadosRetorno;
  		}

  		$oRetorno->aItensInventario = $aRetorno;

  	break;

  	case "processar" :

  		db_inicio_transacao();
  		$oInventario = new Inventario($oParam->iCodigoInventario);
  		$oInventario->processarLancamento(addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao)));
  		db_fim_transacao(false);

  		$oRetorno->sMessage = "Inventário processado com sucesso";

		break;

		case "estornar" :

			db_inicio_transacao();
			$oInventario = new Inventario($oParam->iCodigoInventario);
			$oInventario->desprocessarLancamento(addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao)));
			db_fim_transacao(false);

			$oRetorno->sMessage = "Inventário desprocessado com sucesso";

		break;

    case "validarIntegracaoContabilidade" :

      $oDataImplantacao         = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
      $oInstituicao             = new Instituicao(db_getsession('DB_instit'));
      $lIntegracaoContabilidade = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao);

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

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;

  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true); //@todo mudar para false

} catch (BusinessException $eBusinessErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eBusinessErro->getMessage());
  db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);