<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oPost = db_utils::postmemory($_POST);
$oJson  = new services_json();
$oPars = $oJson->decode(str_replace("\\","",$oPost->json));
if ($oPars->method == "getOrgaos"){
   
  $sWhere  = " o40_anousu     = ".db_getSession("DB_anousu");
  $sWhere .= " and o41_instit = {$oPars->iInstit}"; 
  $oOrcUnidade = db_utils::getDao("orcunidade");  
  $rsUnidade   = $oOrcUnidade->sql_record($oOrcUnidade->sql_query(
                                             null,
                                             null,
                                             null,
                                             "distinct o40_orgao,o40_descr"
                                             ,"o40_descr"
                                             ,$sWhere)
                                            );
  $aOrgaos = array();                                          
  if ($oOrcUnidade->numrows > 0){                                         
    
    for ($iInd = 0; $iInd < $oOrcUnidade->numrows; $iInd++){
       $aOrgaos[] = db_utils::fieldsMemory($rsUnidade, $iInd,false,false,true);  
    }
  }
  echo $oJson->encode($aOrgaos);  
}else if ($oPars->method == "getUnidades"){
  
  $sWhere  = " o41_anousu     = ".db_getSession("DB_anousu");
  $sWhere .= " and o41_orgao  = {$oPars->iOrgao}"; 
  $oOrcUnidade = db_utils::getDao("orcunidade");  
  $rsUnidade   = $oOrcUnidade->sql_record($oOrcUnidade->sql_query(
                                             null,
                                             null,
                                             null,
                                             "o41_unidade,o41_descr"
                                            ,"o41_descr"
                                             ,$sWhere)
                                            );
                                        
  $aOrgaos = array();                                          
  if ($oOrcUnidade->numrows > 0){                                         
    
    for ($iInd = 0; $iInd < $oOrcUnidade->numrows; $iInd++){
       $aOrgaos[] = db_utils::fieldsMemory($rsUnidade, $iInd,false,false,true);  
    }
  }
  echo $oJson->encode($aOrgaos);  
  
}

?>