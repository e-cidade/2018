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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_procedarretipo_classe.php");

$clprocedarretipo = new cl_procedarretipo();

$oJson = new services_json();
// transforma o JSON javascript para um objeto em PHP
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

if (is_numeric(trim($oParam->option))) {
  $rsOption = $clprocedarretipo->sql_record($clprocedarretipo->sql_query_file(
	                                           null,
																						"v06_arretipo",
 																						 null, 
 																						"v06_proced = {$oParam->option}")
	    																		  );										 

  if ($clprocedarretipo->numrows == 1) {																				 																 				 
    $oResultOption = db_utils::fieldsMemory($rsOption, 0);
	  	$aResp = array('option'=>$oResultOption->v06_arretipo);
		echo $oJson->encode($aResp);
	} else {
		$aResp = array('option'=>'null');
		echo $oJson->encode($aResp);
	}
}

?>