<?
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

require_once ("libs/db_stdlib.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

include("classes/db_sanitario_classe.php");

$clSaniCgm  = new cl_sanitario;

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 'ok';

switch ($oParam->exec) {
  
	case "getDadosCgm": 
    
		$sSqlSaniCgm  = $clSaniCgm->sql_query_file("","y80_codsani","","y80_numcgm = ".$oParam->iNumCgm);
    
    $rsSqlSaniCgm = $clSaniCgm->sql_record($sSqlSaniCgm);
    
    if ($clSaniCgm->numrows > 0) {
    	$sSaniCgm = db_utils::fieldsMemory($rsSqlSaniCgm,0)->y80_codsani;
      
      if (!empty($sSaniCgm)) {
      		
        $oRetorno->status  = 2;
        $oRetorno->message = "Alvará Sanitário {$sSaniCgm} já incluído para este CGM. Deseja continuar?";
      }
      
    }
    
  break;
  
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);

?>