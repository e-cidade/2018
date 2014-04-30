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
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {
  
  case 'getMensagemDepartamento':
    
    $oRetorno->mensagemcomprovante = '';
    if (!empty($oParam->iDepartamento)) {
      
      $oDaoMensagemComprovante = db_utils::getDao("farcomprovantetermicaconfig");
      $sSqlMensagem            = $oDaoMensagemComprovante->sql_query_file(null,
                                                                          "*",
                                                                         null,
                                                                         "fa57_coddepto = {$oParam->iDepartamento}"
                                                                         );
      $rsMensagemComprovante   = $oDaoMensagemComprovante->sql_record($sSqlMensagem);
      if ($oDaoMensagemComprovante->numrows > 0) {
        $oRetorno->mensagemcomprovante = urlencode(db_utils::fieldsMemory($rsMensagemComprovante, 0)->fa57_mensagem);
      }
    }
    break;
    
  case 'salvarMensagemDepartamento':
    
    db_inicio_transacao();
    try {
      
      $oDaoMensagemComprovante = db_utils::getDao("farcomprovantetermicaconfig");
      $oDaoMensagemComprovante->excluir(null, "fa57_coddepto = {$oParam->iDepartamento}");
      if ($oDaoMensagemComprovante->erro_status == 0) {
        throw new Exception($oDaoMensagemComprovante->erro_msg);  
      }
  
      if (trim($oParam->sMensagem) != "") {
        
        $oDaoMensagemComprovante->fa57_coddepto = $oParam->iDepartamento;
        $oDaoMensagemComprovante->fa57_mensagem = db_stdClass::normalizeStringJson($oParam->sMensagem);
        $oDaoMensagemComprovante->incluir(null);
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
}
echo $oJson->encode($oRetorno);
?>