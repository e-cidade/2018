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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");


$oJson        = new services_json();

$oParam       = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno     = new stdClass();

$oRetorno->status  = 1;

$oRetorno->message = '';

switch($oParam->exec) {
  case 'getParcelamento':

    $oDAOTermo = db_utils::getDao('termo');
    
    $rDAOTermo = $oDAOTermo->sql_record($oDAOTermo->sql_query_parcelas_termo($oParam->iCodParcelamento));
    
    $aParcel   = array();
    
    if($oDAOTermo->numrows > 0) {
      
      for($i = 0; $i < $oDAOTermo->numrows; $i++) {
        
        $aParcel[] = db_utils::fieldsMemory($rDAOTermo, $i);
        
      }
      
      $oRetorno->parcelas = $aParcel;
      
    } else {
      
      $oRetorno->status  = 0;
      
      $oRetorno->message = 'Nenhum Parcelamento Encontrado.';
      
    }
    
    echo $oJson->encode($oRetorno);
    
    break;
}