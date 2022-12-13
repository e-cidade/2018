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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->sExec) {
  
  case 'getDadosHidrometro' :
    
    $oDaoAguaHidroMatric    = db_utils::getDao('aguahidromatric');
    
    $sSqlDaoAguaHidroMatric = $oDaoAguaHidroMatric->sql_query_diametromarca(null,
                                                                            "x04_codhidrometro,
                                                                             x04_nrohidro,
                                                                             x04_qtddigito,
                                                                             x03_nomemarca,
                                                                             x15_diametro,
                                                                             x04_dtinst,
                                                                             x04_leitinicial,
                                                                             x04_observacao",
                                                                            "",
                                                                            "x04_matric = ".$oParam->iMatric);

    $rsDaoAguaHidroMatric   = $oDaoAguaHidroMatric->sql_record($sSqlDaoAguaHidroMatric);
    
    if ($oDaoAguaHidroMatric->numrows > 0) {
    
      $oRetorno->aDadosHidrometro = db_utils::getCollectionByRecord($rsDaoAguaHidroMatric,false,false,true);

      $oDaoAguaHidroTroca         = db_utils::getDao('aguahidrotroca');
          
      $sSqlDaoAguaHidroTroca      = $oDaoAguaHidroTroca->sql_query_file($oRetorno->aDadosHidrometro[0]->x04_codhidrometro);
      
      $rsDaoAguaHidroTroca        = $oDaoAguaHidroTroca->sql_record($sSqlDaoAguaHidroTroca);      
      
      if($oDaoAguaHidroTroca->numrows > 0){
      	
        $oRetorno->status  = 2;
        $oRetorno->message = utf8_encode('Matrícula sem hidrômetro.');
        
      }
    
    } else {
    
      $oRetorno->status  = 3;
      $oRetorno->message = utf8_encode('Matrícula sem hidrômetro.');
      
    }
    
    break;

}

echo $oJson->encode($oRetorno);
?>