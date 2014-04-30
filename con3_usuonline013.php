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

include("libs/db_stdlib.php");
include("libs/db_conecta.php");
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
encerrar = 0;
function js_iniciar() {
  if(encerrar != 1)
    setTimeout("location.reload()",3000);
}
function vaiprabaixo() {
  window.scrollBy(0,1000000);
}
</script>
<style type="text/css">
<!--
font {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
</head>

<body bgcolor=#CCCCCC onLoad="js_iniciar()">
<?
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
/*
set_time_limit(0);
$result = pg_exec("select uol_chat from db_usuariosonline
                   where uol_id = ".db_getsession("DB_id_usuario")."
  	               and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		           and uol_hora = ".db_getsession("DB_uol_hora")."");
$linhas = split("\n",pg_result($result,0,0));
$numLinhas = sizeof($linhas);
$aux = split("#",$linhas[1]);
$verHora1 = $aux[1];
*/
//while(1) {
  $result = pg_exec("select uol_chat from db_usuariosonline
                     where uol_id = ".db_getsession("DB_id_usuario")."	  	             
		             and uol_hora = ".db_getsession("DB_uol_hora")."");					 
//and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 					 
  $linhas = split("\n",pg_result($result,0,0));
  $numLinhas = sizeof($linhas);
  //for($i = ($numLinhas <= 10?1:($numLinhas - 10));$i < $numLinhas;$i++) {
  for($i = 1;$i < $numLinhas;$i++) {
    $aux = split("#",$linhas[$i]);
  //  $verHora2 = $aux[1];
 //   if($verHora2 > $verHora1) {
//	  $verHora1 = $verHora2;
	  if(db_indexOf(pg_result($result,0,0),"saio do chat") > 0) {
        pg_exec("update db_usuariosonline set  
           uol_chat = ' ',
           uol_sol = ' '
           where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_hora = ".db_getsession("DB_uol_hora")
		   ) or die("Erro(63) atualizando db_usuariosonline");
//	  	   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 		   
        break; 
      }
      if($aux[0] == db_getsession("DB_id_usuario"))
        $cor = "red";
      else
        $cor = "blue";
      echo "<font style=\"color:".$cor.";font-family:Arial, Helvetica, sans-serif;font-size:11px\"><strong style=\"color:black\">".date("H:i:s",$aux[1])."&nbsp;&nbsp;(".$aux[2]."):</strong> ".$aux[3]."</font><script> vaiprabaixo(); </script><br>\n";
//	}
  }
//  flush();
//  sleep(2);
//}
?>
</body>
</html>