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


require_once('dbforms/db_funcoes.php');

require_once('libs/db_conn.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/JSON.php');
require_once('libs/db_utils.php');
require_once('libs/db_sql.php');

require_once("classes/db_issalvara_classe.php");
require_once("classes/db_issmovalvara_classe.php");
require_once("classes/db_issalvaradocumento_classe.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iSttus       = 1;
$oRetorno->sMessage      = '';

switch ($oParam->exec) {

  case 'getDocumentosAlvara':

    $oDaoDocumentosAlvara = new cl_issalvaradocumento;

    $sSqlDocumentos  = "select distinct caddocumento.*,                                                         ";
    $sSqlDocumentos .= "       case when db44_sequencial in (select q122_caddocumento                           ";
    $sSqlDocumentos .= "                                       from issalvaradocumento y                        ";
    $sSqlDocumentos .= "                                      where y.q122_issalvara = issalvara.q123_sequencial";
    $sSqlDocumentos .= "                                    )                                                   ";
    $sSqlDocumentos .= "            then true                                                                   ";
    $sSqlDocumentos .= "            else false                                                                  ";
    $sSqlDocumentos .= "        end as entregue                                                                 ";
    $sSqlDocumentos .= "   from issalvara                                                                       ";
    $sSqlDocumentos .= "        inner join tabativ                on q07_inscr = q123_inscr                     ";
    $sSqlDocumentos .= "        inner join issatividconfdocumento on q07_ativ = q119_ativid                     ";
    $sSqlDocumentos .= "        inner join caddocumento           on db44_sequencial = q119_caddocumento        ";
    $sSqlDocumentos .= "  where q123_sequencial = {$oParam->iCodigoAlvara};                                     ";

    $rsSqlDocumentos = db_query($sSqlDocumentos);

    $oRetorno->iTotalDocumentos = pg_num_rows($rsSqlDocumentos);

    if ($oRetorno->iTotalDocumentos > 0) {
      $oRetorno->aDocumentos = db_utils::getColectionByRecord($rsSqlDocumentos);
    }

    break;
}
$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);
?>