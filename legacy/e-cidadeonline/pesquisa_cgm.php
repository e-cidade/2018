<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include ("libs/db_conecta.php");
include ("libs/db_stdlib.php");
include ("libs/db_sql.php");
include("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oGet  = db_utils::postmemory($_GET);

$cnpj  = formataCpfCnpj($oGet->cnpj);

$sql= "select * from cgm where z01_cgccpf = '$cnpj'";
//die($sql);
$result=db_query($sql);
$linha=pg_num_rows($result);

if ($linha > 0) {
	db_fieldsmemory($result,0);
	echo " <script> 
          	parent.document.form1.nomerazao.value = '$z01_nome';
  	     </script>";
}
/*else{
   $sql2= "select q21_nome from issplanit where q21_cnpj =  '$cnpj'";
   //die($sql2);
	 $result2=db_query($sql2);
	 $linha2=pg_num_rows($result2);
		if ($linha2 > 0) {
			db_fieldsmemory($result2,0);
			echo " <script> 
		  	       parent.document.form1.nomerazao.value = '$q21_nome';
		  	     </script>";
		}
	 
}*/

function formataCpfCnpj($sCpfCnpj){

$cpfCnpj = str_replace(".", "", $sCpfCnpj);
$cpfCnpj = str_replace("/", "", $cpfCnpj);
$cpfCnpj = str_replace("-", "", $cpfCnpj);

return $cpfCnpj;
}
?>