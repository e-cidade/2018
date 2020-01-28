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

require_once 'libs/db_conn.php';
require_once 'libs/db_stdlib.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';
$oJson     = new services_json();
$sBaseName = $_POST["string"];
$aBases    = array();   
/*
 * Caso seja informado um servidor específico para pesquisa
 * 
 */
if (isset($servidor)) {
  $Con    = @pg_connect("host=".$DB_CONEXAO[$servidor]["SERVIDOR"]." dbname=template1 port=".$DB_CONEXAO[$servidor]["PORTA"]." user=".$DB_CONEXAO[$servidor]["USUARIO"]." password=".$DB_CONEXAO[$servidor]["SENHA"]);
  if ($Con) {
	  $sSql   = "select '".$DB_CONEXAO[$servidor]["SERVIDOR"].":'||'".$DB_CONEXAO[$servidor]["PORTA"].":'||'".base64_encode($DB_CONEXAO[$servidor]["USUARIO"]).":'||'".base64_encode($DB_CONEXAO[$servidor]["SENHA"]).":'||datname as cod,
	                      '".$DB_CONEXAO[$servidor]["SERVIDOR"].":'||'".$DB_CONEXAO[$servidor]["PORTA"].":'||datname as label
	                 FROM pg_database 
	                where datname ilike '%".$sBaseName."%' ORDER BY datname"; 
	  $rsBase = pg_query($Con,$sSql); 
	  if (pg_numrows($rsBase) > 0) {
	    for ($i=0; $i<pg_numrows($rsBase); $i++) {
	       $oBase = db_utils::fieldsMemory($rsBase, $i, false, false, true);  
	       $aBases[] = $oBase;   
	    }
	  }
	  pg_close($Con);
  }
/*
 * Caso não seja informado nenhum servidor específico
 * 
 */  
} else {
	for ($x=0; $x < count($DB_CONEXAO); $x++) {
	  $Con    = @pg_connect("host=".$DB_CONEXAO[$x]["SERVIDOR"]." dbname=template1 port=".$DB_CONEXAO[$x]["PORTA"]." user=".$DB_CONEXAO[$x]["USUARIO"]." password=".$DB_CONEXAO[$x]["SENHA"]);
	  if(!$Con){
	  	continue;
	  }
	  $sSql   = "select '".$DB_CONEXAO[$x]["SERVIDOR"].":'||'".$DB_CONEXAO[$x]["PORTA"].":'||'".base64_encode($DB_CONEXAO[$x]["USUARIO"]).":'||'".base64_encode($DB_CONEXAO[$x]["SENHA"]).":'||datname as cod,
	                    '".$DB_CONEXAO[$x]["SERVIDOR"].":'||'".$DB_CONEXAO[$x]["PORTA"].":'||datname as label
	               FROM pg_database 
	              where datname ilike '%".$sBaseName."%' ORDER BY datname"; 
	  $rsBase = pg_query($Con,$sSql); 
	  if (pg_numrows($rsBase) > 0) {
	     for ($i=0; $i<pg_numrows($rsBase); $i++) {
	         $oBase = db_utils::fieldsMemory($rsBase, $i, false, false, true);  
	         $aBases[] = $oBase;   
	     }
	  }
	  pg_close($Con);
	}
}

if (count($aBases) == 0 ) {
	//retorna erro devido a não encontrar nenhuma base de dados
	$oBases = array("cod"=>"0", "label"=>"Nenhuma base de dados encontrada!");
	$aBases[] = $oBases; 
}
echo $oJson->encode($aBases);
?>