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
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("libs/exceptions/ParameterException.php");
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';


switch ($oParam->exec) {

  case 'salvarConfiguracao':

    try {

      db_inicio_transacao();
      if ($oParam->db141_ativo == "t" && (trim($oParam->db141_webserviceuri) == "" || trim($oParam->db141_webservicelocation) == "")) {

        $sMsgErro  = "Vocъ optou por ativar o GED (Gerenciador Eletrєnico de Documentos), neste caso щ necessсrio ";
        $sMsgErro .= "informar as configuraчѕes de URI e Location do webservice.";
        throw new BusinessException($sMsgErro);
      }

      $oDaoConfiguracaoGed = db_utils::getDao("configuracaoged");
      $oDaoConfiguracaoGed->db141_sequencial         = $oParam->db141_sequencial;
      $oDaoConfiguracaoGed->db141_ativo              = $oParam->db141_ativo == "t" ? "true" : "false";
      $oDaoConfiguracaoGed->db141_webserviceuri      = $oParam->db141_webserviceuri;
      $oDaoConfiguracaoGed->db141_webservicelocation = $oParam->db141_webservicelocation;
      $oDaoConfiguracaoGed->alterar($oDaoConfiguracaoGed->db141_sequencial);
      if ($oDaoConfiguracaoGed->erro_status == "0") {
        throw new BusinessException("Nуo foi possэvel alterar o parтmetro.");
      }

      $oRetorno->message = urlencode("Configuraчуo salva com sucesso.");

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case "getConfiguracao":

    $oDaoConfiguracaoGed = db_utils::getDao("configuracaoged");
    $sSqlBuscaConfiguracao = $oDaoConfiguracaoGed->sql_query_file();
    $rsBuscaConfiguracao = $oDaoConfiguracaoGed->sql_record($sSqlBuscaConfiguracao);

    $oStdConfiguracao = db_utils::fieldsMemory($rsBuscaConfiguracao, 0);
    $oRetorno->db141_sequencial         = $oStdConfiguracao->db141_sequencial;
    $oRetorno->db141_ativo              = $oStdConfiguracao->db141_ativo;
    $oRetorno->db141_webserviceuri      = urlencode($oStdConfiguracao->db141_webserviceuri);
    $oRetorno->db141_webservicelocation = urlencode($oStdConfiguracao->db141_webservicelocation);

    break;
}
echo $oJson->encode($oRetorno);
?>