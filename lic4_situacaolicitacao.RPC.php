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
 
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("model/licitacao.model.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
   
db_inicio_transacao();

switch ($oParam->exec) {

  case 'incluir':
    
    try {
      
      $iCodigoEdital     = $oParam->iCodigoEdital;
      $iCodigoLicitacao  = $oParam->iCodigoLicitacao;
      $iTipoSituacao     = $oParam->iTipoSituacao;
      $sObservacao       = '';
      
      
      if (isset($sObservacao)) {
        $sObservacao       = db_stdClass::normalizeStringJson($oParam->sObservacao);
      }
      
      $oLicitacao = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarSituacao($iTipoSituacao,$sObservacao);
      $oRetorno->message = urlencode("Situaчуo salva com sucesso.");
      db_fim_transacao(false);
    
    }catch (Exception $eErro) {
      	
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }
    break;
  
  /**
   * Modifica apenas a observaчуo da Licitaчуo
   */
  case 'alterar':
    
    try {
      
      $iSituacaoSequencial = $oParam->iSituacaoSequencial;
      $iCodigoLicitacao    = $oParam->iCodigoLicitacao;
      $sObservacao         = '';
      
      if (isset($sObservacao)) {
        $sObservacao = db_stdClass::normalizeStringJson($oParam->sObservacao);
      }
      
      $oLicitacao          = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarObservacaoSituacao($iSituacaoSequencial,$sObservacao);
      $oRetorno->message = urlencode("Motivo alterado com sucesso.");
      
    }catch (Exception $eErro) {
         
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 1;
      db_fim_transacao(true);
    }
    break;
    
    
  
  case "getDadosSituacaoLicitacao":
    
    try {
      
      $oDaoLicLicitaSituacao = db_utils::getDao('liclicitasituacao');
      $sSqlBuscaSituacao     = $oDaoLicLicitaSituacao->sql_query_file($oParam->iCodigoAlteracao);
      $rsBuscaSituacao       = $oDaoLicLicitaSituacao->sql_record($sSqlBuscaSituacao);
      if ($oDaoLicLicitaSituacao->numrows == 0) {
        throw new Exception("Situaчуo da licitaчуo nуo encontrada.");
      }

      $oDadoSituacao              = db_utils::fieldsMemory($rsBuscaSituacao, 0);
      $oLicitacao                 = new licitacao($oDadoSituacao->l11_liclicita);
      $oRetorno->l11_obs          = urlencode($oDadoSituacao->l11_obs);
      $oRetorno->l11_sequencial   = $oDadoSituacao->l11_sequencial;
      $oRetorno->iCodigoEdital    = $oLicitacao->getEdital();
      $oRetorno->iCodigoLicitacao = $oDadoSituacao->l11_liclicita;
      
    } catch (Exception $eErro) {
      
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 1;
      db_fim_transacao(true);
      
    }   
    
  break;
  
  
  case "cancelar":
  
    try {
      
      $iCodigoLicitacao    = $oParam->iCodigoLicitacao;
      $sObservacao         = '';
      
      
      if (isset($sObservacao)) {
        $sObservacao       = $oParam->sObservacao;
      }
      
      $oLicitacao = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarSituacao(0,$sObservacao);
      $oRetorno->message = urlencode("Situaчуo cancelada com sucesso.");
  
    } catch (Exception $eErro) {
  
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 1;
      db_fim_transacao(true);
    }
  
    break;
    
  
  
  
}
db_fim_transacao(false);

echo $oJson->encode($oRetorno);
?>