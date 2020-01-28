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

require_once("libs/db_conn.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass(); 
$oRetorno->status  = 1;

switch ($oParam->exec) {

  /*
   * Pesquisa status do sistema
   */
    
	
  case "verificarStatusSistema":

  	if (isset($DB_SELLER)) {
  		
	  	$sConexao = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE user=$DB_USUARIO port=$DB_PORTA password=$DB_SENHA");
	    if (!$sConexao) {
	      
	      $oRetorno->message = urlencode("Erro: Conexão Inválida! Contate Administrador do Sistema.");
	      exit;
	    }
	
	    $sSqlStatus   = " select fc_startsession(); ";
	    $sSqlStatus  .= " select codigo, db21_ativo from db_config where prefeitura = true; ";
	    $rsSqlStatus  = pg_query($sSqlStatus);    
	    $iSqlStatus   = pg_numrows($rsSqlStatus);
	    
	    if ( $iSqlStatus > 0 ) {
	    	$oSqlStatus = db_utils::fieldsMemory($rsSqlStatus,0);
	    	
	      //1=>"On line",2=>"Não permitir novos logs",3=>"Off line"
	    	switch ($oSqlStatus->db21_ativo) {
	    		
	    		case 1:
	
	    			$oRetorno->message = urlencode("Sistema está On-Line");
	    			$oRetorno->status  = 1;
	    		break;
	    		
	        case 2:
	
	          $oRetorno->message = urlencode("Sistema não permite novos logs! Contate Administrador do Sistema.");
	          $oRetorno->status  = 0;        	
	        break;
	
	        case 3:
	
	          $oRetorno->message = urlencode("Sistema está Off-Line! Contate Administrador do Sistema.");
	          $oRetorno->status  = 0;        	
	        break;        
	    	}
	    	
	    }
	    
	    pg_close($sConexao);    
	    break;
  	}
}

echo $oJson->encode($oRetorno);
?>