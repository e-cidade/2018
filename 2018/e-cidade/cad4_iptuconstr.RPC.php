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
 * @fileoverview Controla Ações no cadastro de contrução da obra
 * @version   $Revision: 1.8 $
 * @revision  $Author: dbfabio.esteves $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_iptuconstrobrasconstr_classe.php");
require_once ("classes/db_obrasalvara_classe.php");
require_once ("classes/db_obrasconstr_classe.php");
require_once ("classes/db_obrasconstrcaracter_classe.php");

require_once ("model/cadastro/Imovel.model.php");

define('CONSTRUCAO_MODULO_CADASTRO',1);
define('CONSTRUCAO_MODULO_PROJETOS',2);

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
/**
 * Camada de Tentativas do RPC
 */
try {
  
  switch ($oParam->sExec) {
    
    case "getObrasComAlvara":
    
      $oDaoObrasAlvara           = new cl_obrasalvara();
      
      $sSqlDadosObra             = $oDaoObrasAlvara->sql_query_obrasCadastroImobiliario($oParam->iMatricula);
      $rsObrasAlvara             = db_query($sSqlDadosObra);
      
      if ( !$rsObrasAlvara ) {
        throw new Exception('Erro ao retornar dados da obra');
      }
      
      $oRetorno->aObrasAlvara      = array();
      
      if ( pg_num_rows($rsObrasAlvara) ) {
	      $oRetorno->aObrasAlvara    = db_utils::getCollectionByRecord($rsObrasAlvara, false, false,true );
      }
    break;
    
    case "getConstrucoesMatricula":
      
      $oImovel             = new Imovel($oParam->iMatricula);
      $aDadosConstrucoes   = $oImovel->getConstrucoes(true);

			if ( $oImovel->getDataBaixa() != '' ) {
				throw new Exception("Matricula baixada");
			}

			/**
			 * Verifica se matriculas sao do mesmo lote
			 */	 
      $lMesmoLote       = false;
			$oImovelAlteracao = new Imovel($oParam->iMatriculaParaAlteracao);

			if ( $oImovel->getCodigoLote() == $oImovelAlteracao->getCodigoLote() ) {
				$lMesmoLote = true;
			}

      if ( count($aDadosConstrucoes) == 0) {
        throw new Exception("Nenhuma construção encontrada para a matricula {$oParam->iMatricula}!");
      }
      
      $aRetornoConstrucoes = array();
      
      foreach ($aDadosConstrucoes as $oRegistro) {
        
        $oConstrucao = new stdClass();
        $oConstrucao->iCodigoConstrucao       = $oRegistro->getCodigoConstrucao();
        $oConstrucao->lPrincipal              = $oRegistro->isConstrucaoPrincipal();
        $oConstrucao->iAnoConstrucao          = $oRegistro->getAnoConstrucao();
        $oConstrucao->nAreaConstrucao         = $oRegistro->getArea();
        $oConstrucao->nAreaPrivada            = $oRegistro->getAreaPrivada();
        
        $oConstrucao->lMesmoLote              = $lMesmoLote;
        $oConstrucao->iPavimentos             = $oRegistro->getQuantidadePavimentos();
        $oConstrucao->iCodigoLogradouro       = $oRegistro->getCodigoRua();
        $oConstrucao->iNumeroLogradouro       = $oRegistro->getNumeroEndereco();
        $oConstrucao->sNomeLogradouro         = $oRegistro->getNomeRua();
        $oConstrucao->sComplementoLogradouro  = urlEncode($oRegistro->getComplementoEndereco());
        
        $oConstrucao->iCodigoOrigemConstrucao  = $oRegistro->getCodigoOrigemConstrucao();
        $oConstrucao->sObservacaoConstrucao    = urlEncode($oRegistro->getObservacaoConstrucao());
        
        $aRetornoConstrucoes[] = $oConstrucao;
         
      }
      
      $oRetorno->aConstrucoesMatricula = $aRetornoConstrucoes;
      
    break;  
     
    case "getCaracteristicasSelecao":
      
      $oDaoCarConstr           = db_utils::getDao("carconstr");
      $sSqlCaracteristicas     = $oDaoCarConstr->sql_querySelecaoCaracteristicas($oParam->iMatricula, $oParam->iCodigoConstrucao);      
      $rsCaracteristicas       = db_query($sSqlCaracteristicas);
       
      $oRetorno->aSelecionadas    = array();
      $oRetorno->aCaracteristicas = array();

      if ( !$rsCaracteristicas ) {
        throw new Exception('Erro ao retornar caracteristicas da construcao\n'.pg_last_error());
      }

      foreach ( db_utils::getCollectionByRecord($rsCaracteristicas,false,false,true) as $oDados) {

        $oRetornoGrupo                                  = new stdClass();
        $oRetornoGrupo->iCodigoGrupo                    = $oDados->j32_grupo;
        $oRetornoGrupo->sDescricaoGrupo                 = $oDados->j32_descr;
        $oRetorno->aCaracteristicas[$oDados->j32_grupo] = $oRetornoGrupo;

        $oRetornoSelecao = new stdClass();
        $oRetornoSelecao->iCodigoCaracteristica         = $oDados->j31_codigo;
        $oRetornoSelecao->sDescricaoCaracteristica      = $oDados->j31_descr;
        $oRetornoSelecao->lSelecionada                  = $oDados->selecionada == "t" ? true : false;
        $aCaracteristicas[$oDados->j32_grupo][]         = $oRetornoSelecao;
 
        if ( $oDados->selecionada == "t" ) {
          $oRetorno->aSelecionadas[] = $oDados->j31_codigo;
        }        
      }

      foreach ( $oRetorno->aCaracteristicas as $iGrupo => $oRetornoCaracteristica ) {

        $oCaracter          = $oRetorno->aCaracteristicas[$iGrupo];
        $oCaracter->aOpcoes = $aCaracteristicas[$iGrupo];
      }                             

    break;
    case "getCaracteristicasConstrucao": 
    	
      
      if ($oParam->iTipoConstrucao == CONSTRUCAO_MODULO_CADASTRO) {
        
        $oConstrucao = new Construcao($oParam->iMatricula, $oParam->iCodigoConstrucao);
        $oRetorno->aCaracteristicas = $oConstrucao->getCaracteristicasConstrucao();
        
      } else if ($oParam->iTipoConstrucao == CONSTRUCAO_MODULO_PROJETOS) {
        
    	  $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
                                 
    	  $sSqlCaracteristicas     = $oDaoObrasConstrCaracter->sql_query_file(null, 
    	                                                                      "ob34_caracter", 
    	                                                                      null, 
    	                                                                      "ob34_obrasconstr = {$oParam->iCodigoConstrucao}");
    	  $rsCaracteristicas       = db_query($sSqlCaracteristicas);
    	  
    	  if ( !$rsCaracteristicas ) {
    	  	throw new Exception('Erro ao retornar caracteristicas da construcao\n'.pg_last_error());
    	  }
    	  
    	  $oRetorno->aCaracteristicas  = array();
    	  
    	  if ( pg_num_rows($rsCaracteristicas) ) {
    	  	
    	  	$aCaracteristicas          = db_utils::getCollectionByRecord($rsCaracteristicas, false, false,true );
    	  	foreach ($aCaracteristicas as $oCaracteristica) {
    	  		$oRetorno->aCaracteristicas[] = $oCaracteristica->ob34_caracter;
    	  	}
    	  }
      }
    	break;
    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }


  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro) {
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}