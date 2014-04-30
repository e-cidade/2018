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
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");

$oJson              = new services_json();
$oRetorno           = new stdClass();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$lErro = false;

switch ($oParam->sExec) {
  
  case 'salvarMovimentacoes':
        
    db_app::import('inicial');
    
    db_inicio_transacao();
    
    try {
      
      for ($iIndice = 0; $iIndice < count($oParam->aIniciais); $iIndice++) {
        
        $oInicial = new inicial($oParam->aIniciais[$iIndice]);
        
        $oInicial->adicionarMovimentacao($oParam->iCodigoSituacao, db_stdClass::normalizeStringJson($oParam->sObservacoes));
        
        $oInicial->salvar();
        
      }
      
    } catch (DBException $oDBException) {
      
      $oRetorno->iStatus  = 2;
      
      $oRetorno->sMessage = urlEncode($oDBException->getMessage());

      $lErro              = true;
      
    }
    
    db_fim_transacao($lErro);
    
    break;
    
  case 'alterarMovimentacoes' :
    
    db_app::import('inicial');
    
    db_inicio_transacao();
    
    try {
    
      for ($iIndice = 0; $iIndice < count($oParam->aIniciais); $iIndice++) {
    
        $oInicial = new inicial($oParam->aIniciais[$iIndice]);
    
        $oInicial->alterarMovimentacao($oInicial->getCodigoMovimentacao(), $oParam->iCodigoSituacao, db_stdClass::normalizeStringJson($oParam->sObservacoes));
    
        $oInicial->salvar();
    
      }
    
    } catch (DBException $oDBException) {
    
      $oRetorno->iStatus  = 2;
    
      $oRetorno->sMessage = urlencode($oDBException->getMessage());
    
      $lErro              = true;
    
    }
    
    db_fim_transacao($lErro);
    
    break;
    
  case 'excluirMovimentacoes' :
    
    db_app::import('inicial');
    
    db_inicio_transacao();
    
    try {
    
      for ($iIndice = 0; $iIndice < count($oParam->aIniciais); $iIndice++) {
    
        $oInicial = new inicial($oParam->aIniciais[$iIndice]);
    
        $oInicial->excluirMovimentacao($oInicial->getCodigoMovimentacao());
    
        $oInicial->salvar();
    
      }
    
    } catch (DBException $oDBException) {
    
      $oRetorno->iStatus  = 2;
    
      $oRetorno->sMessage = urlencode($oDBException->getMessage());
    
      $lErro              = true;
    
    }
    
    db_fim_transacao(true);

    break;
  
  case 'getIniciais' :
    
    if (isset($oParam->iCodigoInicial) and ($oParam->iCodigoInicial != '')) {
      
      db_app::import('inicial');
      
      $oInicial                                = new inicial($oParam->iCodigoInicial);
      $oDadosIniciais                          = new stdClass();
      $oDadosIniciais->iNumeroInicial          = $oInicial->getCodigoInicial();
      $oDadosIniciais->dDataInicial            = db_formatar($oInicial->getData(), 'd');
      $oDadosIniciais->iSituacao               = $oInicial->getSituacao() == 1 ? 'Ativa' : 'Anulada';
      
      if ($oInicial->hasProcessoForo()) {
        $oDadosIniciais->sProcessoForo         =  $oInicial->getProcessoForo()->getNumeroProcesso();
      } else {
        $oDadosIniciais->sProcessoForo         = '';
      }
      
      $oDadosIniciais->sObservacaoMovimentacao = urlencode($oInicial->getUltimaMovimentacao()->sObservacao);
      $oRetorno->aIniciais                     = array($oDadosIniciais);
      
    } else if (isset($oParam->iCodigoProcessoForo) and ($oParam->iCodigoProcessoForo != '')) {
      
      db_app::import('juridico.ProcessoForo');
      
      $oProcessoForo = new ProcessoForo($oParam->iCodigoProcessoForo);
      
      foreach ( $oProcessoForo->getIniciais() as $oInicial ) {
        
        $oDadosIniciais                          = new stdClass();
        $oDadosIniciais->iNumeroInicial          = $oInicial->getCodigoInicial();
        $oDadosIniciais->dDataInicial            = db_formatar($oInicial->getData(), 'd');
        $oDadosIniciais->iSituacao               = $oInicial->getSituacao() == 1 ? 'Ativa' : 'Anulada';
        $oDadosIniciais->sProcessoForo           = $oProcessoForo->getNumeroProcesso();
        $oDadosIniciais->iCodigoMovimentacao     = $oInicial->getUltimaMovimentacao()->iCodigoMovimentacao;
        $oDadosIniciais->sObservacaoMovimentacao = urlencode($oInicial->getUltimaMovimentacao()->sObservacao);
        $oRetorno->aIniciais[]                   = $oDadosIniciais;
      }

    }
    
    break;
}
echo $oJson->encode($oRetorno);