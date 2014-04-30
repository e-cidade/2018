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
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("model/ProcessoCompras.model.php");
require_once("libs/JSON.php");
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {
  
  case 'getDadosProcessoCompras' :
    
    try {
      
      if (empty($oParam->iCodigoProcesso)) {
        throw new Exception('Código do precesso de compras não informado');
      }
      $oProcessoCompras = new ProcessoCompras($oParam->iCodigoProcesso);
      
      $oRetorno->iCodigoProcesso        = $oProcessoCompras->getCodigo();
      $oRetorno->iDepartamento          = $oProcessoCompras->getCodigoDepartamento();
      $oRetorno->sDescricaoDepartamento = urlencode($oProcessoCompras->getDescricaoDepartamento());
      $oRetorno->sResumo                = urlencode($oProcessoCompras->getResumo());
      $oRetorno->iSituacao              = (int)$oProcessoCompras->getSituacao();
      $oRetorno->dtEmissaoProcesso      = $oProcessoCompras->getDataEmissao();
    } catch (Exception $eErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
    
  case 'liberarProcessoCompras':
    
    try {
      
      if (empty($oParam->iCodigoProcesso)) {
        throw new Exception('Código do precesso de compras não informado');
      }
      db_inicio_transacao();
      $oProcessoCompras = new ProcessoCompras($oParam->iCodigoProcesso);
      $oProcessoCompras->setSituacao($oParam->iSituacao); 
      $oProcessoCompras->salvar();
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}
echo $oJson->encode($oRetorno);
?>