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
require_once("libs/db_conn.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));

if (!isset($DB_PORTA_ALT)){
  $DB_PORTA_ALT = db_getsession('DB_porta');
}

$aRetorno                 = array();
$aRetornoBases            = array();
$aRetorno['lErro']        = true;
$aRetorno['aBases']       = null;
$aRetorno["lErroConexao"] = false;

$vetor_ip = split(":",$oParam->iIp);
if (count($vetor_ip) == 1){
  $aRetorno['iIpAnterior']  = $oParam->iIp;
}else{
  $aRetorno['iIpAnterior'] = $vetor_ip[0];
  $DB_PORTA_ALT = $vetor_ip[1];
}

// Conecta na Base de Dados 
$resConexaoOrigem = @pg_connect("host={$aRetorno['iIpAnterior']} dbname=template1 user=".$DB_USUARIO." port=".$DB_PORTA_ALT." password=".$DB_SENHA);

switch ($oParam->sExec) {
  
  case 'buscaBase':
    
      if ( !$resConexaoOrigem ) {
      $aRetorno["lErroConexao"] = true;
      break;      
    }
    
    $sSqlBases  = "   select datname                                        ";
    $sSqlBases .= "     from pg_database                                    ";
    $sSqlBases .= "    where substr(datname,1,6) != 'templa'                ";
    $sSqlBases .= "      and datname             != '{$oParam->sBaseAtual}' ";
    $sSqlBases .= "      and datname             != '{$DB_BASE}'            ";
    $sSqlBases .= " order by datname                                        ";
    
    $sExecutaSqlBase = db_query($resConexaoOrigem, $sSqlBases);
    
    for ( $i = 0; $i < pg_num_rows($sExecutaSqlBase); $i++ ) {
      
      $rsSqlBase         = pg_fetch_assoc($sExecutaSqlBase);
      $aRetornoBases[]   = $rsSqlBase['datname'];
      $aRetorno['lErro'] = false; 
    }
    
    $aRetorno['aBases'] = $aRetornoBases;
    
  break;
  
}

@pg_close($resConexaoOrigem);

echo $oJson->encode($aRetorno);
?>