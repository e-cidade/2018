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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/JSON.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$objJSON = new Services_JSON();
$oPost   = db_utils::postMemory($_POST);

$oJson   = $objJSON->decode(str_replace("\\","",$oPost->json));

$sSqlParcelas = "select 0 as k00_numpar union select distinct k00_numpar from arrecad where k00_numpre = {$oJson->inumpre} order by k00_numpar";
$rsParcelas = pg_query($sSqlParcelas);
$iNumRows   = pg_num_rows($rsParcelas);
$aParcelas  = array();

if($iNumRows > 1){
	
	for ($i = 0; $i < $iNumRows; $i++) {
	
	  $oParcelas = db_utils::fieldsMemory($rsParcelas,$i,false,false,true);
	  $aParcelas[] = $oParcelas;
	  
	}
	
	$sRetorno = $objJSON->encode($aParcelas);
}else{
	
	$sRetorno = $objJSON->encode($aParcelas);
}
echo $sRetorno;

?>