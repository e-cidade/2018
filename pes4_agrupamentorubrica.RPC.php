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
require_once("libs/db_libcontabilidade.php");
require_once("libs/JSON.php");

require_once("std/db_stdClass.php");
require_once("std/DBNumber.php");

require_once("dbforms/db_funcoes.php");

$oJson             = new services_json();
$oParametros       = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$oDaoAgrupamentoRubrica        = db_utils::getDao('agrupamentorubrica');
$oDaoAgrupamentoRubricaRubrica = db_utils::getDao('agrupamentorubricarubrica');
$iInstituicao                  = db_getsession('DB_instit');

try {
  
  switch ($oParametros->exec) {

  	case 'getRubricas' :
  		
  		$iCodigoAgrupamento  = "{$oParametros->iAgrupamentoRubrica}";
  		
  		$sSqlAgrupamentoRubrica = $oDaoAgrupamentoRubrica->sql_queryRubricasAgrupamento('*', $iCodigoAgrupamento, 'rh27_rubric');
  		$rsAgrupamentoRubrica   = $oDaoAgrupamentoRubrica->sql_record($sSqlAgrupamentoRubrica);
  		
  		if ( $oDaoAgrupamentoRubrica->numrows == 0 ) {
  			throw new Exception('Grupo das rubricas de rescisão não encontrado.');
  		}
  		
  		$aRubricas           = array();
  		$iAgrupamentoRubrica = $oDaoAgrupamentoRubrica->numrows;
  		
  		for ($iIndice = 0; $iIndice < $iAgrupamentoRubrica; $iIndice++ ) {
  		
  			$oAgrupamentoRubrica = db_utils::fieldsMemory($rsAgrupamentoRubrica, $iIndice);
  		
  			if ( empty($oAgrupamentoRubrica->rh27_rubric) ) {
  				continue;
  			}
  		
  			$oStdRubrica             					= new StdClass();
  			$oStdRubrica->iCodigoAgrupamento  = $oAgrupamentoRubrica->rh114_sequencial;  			
  			$oStdRubrica->sRubrica   					= $oAgrupamentoRubrica->rh27_rubric;
  			$oStdRubrica->sDescricao 					= urlencode($oAgrupamentoRubrica->rh27_descr);
  			
  			$aRubricas[] = $oStdRubrica;
  		}
  		
  		$oRetorno->aRubricas = $aRubricas;
  		
  	break;
  	
    /**
     * Busca os dados do agrupamento de rubrica
     */   
    case 'getDadosAgrupamentoRubrica' : 
      
      $sWhere  = "rh113_sequencial = {$oParametros->iAgrupamentoRubrica}";
      $sCampos = 'rh27_rubric, rh27_descr, rh113_tipo, rh113_codigo, rh113_descricao';

      $sSqlAgrupamentoRubrica = $oDaoAgrupamentoRubrica->sql_query_agrupamento($sCampos, $sWhere); 
      $rsAgrupamentoRubrica   = $oDaoAgrupamentoRubrica->sql_record($sSqlAgrupamentoRubrica);

      if ( $oDaoAgrupamentoRubrica->numrows == 0 ) {
        throw new Exception('Grupo das rubricas de rescisão não encontrado.');
      }

      $aRubricas           = array();
      $iAgrupamentoRubrica = $oDaoAgrupamentoRubrica->numrows; 

      for ($iIndice = 0; $iIndice < $iAgrupamentoRubrica; $iIndice++ ) {
      
        $oAgrupamentoRubrica = db_utils::fieldsMemory($rsAgrupamentoRubrica, $iIndice);

        if ( empty($oAgrupamentoRubrica->rh27_rubric) ) {
          continue;
        }

        $oStdRubrica             = new StdClass();
        $oStdRubrica->sRubrica   = $oAgrupamentoRubrica->rh27_rubric; 
        $oStdRubrica->sDescricao = $oAgrupamentoRubrica->rh27_descr;

        $aRubricas[] = $oStdRubrica;
      }

      $oRetorno->iTipo     = $oAgrupamentoRubrica->rh113_tipo;
      $oRetorno->nCodigo   = $oAgrupamentoRubrica->rh113_codigo;
      $oRetorno->aRubricas = $aRubricas;

    break;
  
    /**
     * Altera agrupamento de rubrica
     */   
    case 'alterar' :

    	db_inicio_transacao();
			
    	if($oParametros->iAgrupamentoRubrica != null){
    	
	      $iAgrupamentoRubrica = $oParametros->iAgrupamentoRubrica;
	
	      validacaoRubricas($oParametros->aRubricas, $iInstituicao, $iAgrupamentoRubrica);
	
	      /**
	       * Erro ao alterar grupo de rubrica
	       */   
	      if ( $oDaoAgrupamentoRubrica->erro_status == '0' ) {
	        throw new Exception($oDaoAgrupamentoRubrica->erro_msg);
	      }
	
	      /**
	       * Remove todas as rubricas do grupo para depois incluir as rubricas lancadas na grid
	       */   
	      $oDaoAgrupamentoRubricaRubrica->excluir(null, "rh114_agrupamentorubrica = {$iAgrupamentoRubrica}");
	
	      if ( $oDaoAgrupamentoRubricaRubrica->erro_status == '0' ) {
	        throw new Exception('Erro ao alterar vinculo de agrupamento de rescisão com rubricas.\n'.$oDaoAgrupamentoRubricaRubrica->erro_msg);
	      }
	
	      /**
	       * Pesquisa vinculo do grupo com rubricas
	       */   
	      $sWhereVinculosCadastrados = "rh114_agrupamentorubrica = {$iAgrupamentoRubrica}";
	      $sSqlVinculosCadastrados   = $oDaoAgrupamentoRubricaRubrica->sql_query_file(null, "rh114_rubrica", null, $sWhereVinculosCadastrados);
	      $rsVinculosCadastrados     = $oDaoAgrupamentoRubricaRubrica->sql_record($sSqlVinculosCadastrados);
	      $aVinculosCadastrados      = array();
	
	      if ( $oDaoAgrupamentoRubricaRubrica->numrows > 0 ) {
	
	        $iTotalGrupoRubrica = $oDaoAgrupamentoRubricaRubrica->numrows;
	
	        for ( $iIndice = 0; $iIndice < $iTotalGrupoRubrica; $iIndice++ ) {
	
	          $oVinculosCadastrados = db_utils::fieldsMemory($rsVinculosCadastrados, $iIndice);
	          $aVinculosCadastrados[] = $oVinculosCadastrados->rh114_rubrica;
	        }
	      }
	
	      
	      /**
	       * Percorre as rubricas da grid e verifica se ja nao tem cadastrado
	       */   
	      foreach( $oParametros->aRubricas as $sRubrica ) {
	      	
	        /**
	         * Rubrica ja cadastrada para o grupo
	         */   
	        if ( in_array($sRubrica, $aVinculosCadastrados) ) {
	          continue;
	        } 
	        
	        $oDaoAgrupamentoRubricaRubrica->rh114_agrupamentorubrica = $iAgrupamentoRubrica;
	        $oDaoAgrupamentoRubricaRubrica->rh114_rubrica            = $sRubrica;
	        $oDaoAgrupamentoRubricaRubrica->rh114_instituicao        = $iInstituicao;
	
	        $oDaoAgrupamentoRubricaRubrica->incluir(null);
	        
	        if ( $oDaoAgrupamentoRubricaRubrica->erro_status == '0' ) {
	        	throw new Exception('Erro ao alterar vinculo de agrupamento de rescisão com rubricas.\n'.$oDaoAgrupamentoRubricaRubrica->erro_msg);
	        }
	      }	      
	
	      $oRetorno->sMensagem = 'Rubrica(s) vinculada(s) ao grupo.';
    	}

      db_fim_transacao(false);

    break;

    default :
      throw new Exception('Nenhum parametro informado.');
    break;

  }
  
} catch (Exception $eErro) {
  
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();

  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);

/**
 * Verifica se já existe as rubricas no grupo.
 */
function validacaoRubricas($aRubricas, $iInstituicao, $iAgrupamentoRubrica = null) {

  $oDaoAgrupamentoRubricaRubrica = db_utils::getDao('agrupamentorubricarubrica');

  $sRubricas = implode("','", $aRubricas);
  $sWhere    = "rh114_rubrica in ('{$sRubricas}') and rh114_instituicao = $iInstituicao";

  if ( !empty($iAgrupamentoRubrica) ) {
    $sWhere .= " and rh114_agrupamentorubrica <> {$iAgrupamentoRubrica}";
  }

  $sSqlAgrupamentoRubricaRubrica = $oDaoAgrupamentoRubricaRubrica->sql_query_file(null, "rh114_rubrica", null, $sWhere);
  $rsAgrupamentoRubricaRubrica   = $oDaoAgrupamentoRubricaRubrica->sql_record($sSqlAgrupamentoRubricaRubrica);

  if ( $oDaoAgrupamentoRubricaRubrica->numrows > 0 ) {

    $aDadosRubricas = db_utils::getCollectionByRecord($rsAgrupamentoRubricaRubrica);

    $sMensagemErro   = '';
    $aCodigoRubricas = array();

    
    foreach ($aDadosRubricas as $oRubrica){
    	
      $aCodigoRubricas[] = $oRubrica->rh114_rubrica;
    }

    sort($aCodigoRubricas);

    $sMensagemErro = implode(', ', array_splice($aCodigoRubricas, 0, 10));
    
    $sPossuiMaisRubricas = '';
    if (count($aCodigoRubricas) > 10) {
    	$sPossuiMaisRubricas = ', e outras, ';
    }

    throw new Exception("A(s) rubrica(s) {$sMensagemErro}{$sPossuiMaisRubricas} já está(ão) em um grupo");
  }

}