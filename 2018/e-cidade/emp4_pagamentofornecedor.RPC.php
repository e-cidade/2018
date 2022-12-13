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

require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
db_app::import('caixa.arquivos.*');
db_app::import('dbLayoutReader');
db_app::import('dbLayoutLinha');
db_app::import('agendaPagamento');
db_app::import('exceptions.*');


$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {
  
  case 'processarArquivo':
    
    db_inicio_transacao();
    try {
      
      $oProcessamentoTXT                   = new ProcessamentoPagamentoFornecedor($oParam->nomearquivo);
      $iCodigoRetorno                      = $oProcessamentoTXT->possuiRetornoProcessado();
      
      if (!$iCodigoRetorno) {
        
        $oArquivosGerados                    = $oProcessamentoTXT->processar();
        $aMovimentosDescartados              = $oProcessamentoTXT->getMovimentosDescartados();
        $oRetorno->aArquivosGerados          = $oArquivosGerados->aArquivos;
        $oRetorno->iTotalInconsistencias     = $oArquivosGerados->nInconsistencias;
        $oRetorno->aMovimentosNaoProcessados = $oArquivosGerados->aMovimentosNaoProcessados;
        $oRetorno->aMovimentosCancelados     = $oArquivosGerados->aMovimentosCancelados;
        
        $aRetornosDescartados = array();
        if (count($aMovimentosDescartados) > 0) { 
          
          foreach ($aMovimentosDescartados as $oDadoMovimento) {
            $aRetornosDescartados[] = "{$oDadoMovimento->iCodigoRetorno}/{$oDadoMovimento->codigo_movimento}";
          }
        }
        $oRetorno->aMovimentosDescartados = $aRetornosDescartados;
        $oRetorno->lArquivoProcessado     = false;
      } else {
      
      	$oRetorno->iRetornoProcessado = $iCodigoRetorno;
      	$oRetorno->lArquivoProcessado = true;
      }
      
      db_fim_transacao(false);
      
    } catch (Exception $eErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage()); 
      db_fim_transacao(true);
      
    } catch (BusinessException $eBusinessErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusinessErro->getMessage()); 
      db_fim_transacao(true);
    }
    break;
}
echo $oJson->encode($oRetorno);