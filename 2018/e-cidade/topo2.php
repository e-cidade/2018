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
/*
pg_exec("update db_usuariosonline 
         set uol_inativo = ".time()."
		 where uol_id = ".db_getsession("DB_id_usuario")."
		 and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		 and uol_hora = ".db_getsession("DB_uol_hora")."
		 ") or die("Erro(26) atualizando db_usuariosonline");
$result = pg_exec("select uol_sol from db_usuariosonline 
                   where uol_id = ".db_getsession("DB_id_usuario")."
    		       and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		           and uol_hora = ".db_getsession("DB_uol_hora"));
$verf = "1";
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(($str = trim(pg_result($result,0,0))) != "") {// && $verf == "1"
  $str = split("#",$str);
  $verf = "2";
  echo "<script>
          parent.document.getElementById('Hid_usuario').value = '".$str[0]."'
		  parent.document.getElementById('Hhora').value = '".$str[1]."'		  
		  parent.document.getElementById('Husuario').value = '".$str[2]."'
		  parent.document.getElementById('msg_sol').innerHTML = '".$str[2]." Solicita Conversa'; 
          //parent.document.getElementById('sol').style.visibility = 'visible';
		  parent.js_abrir();
		  //parent.focus();
        </script>\n";
  pg_exec("update db_usuariosonline 
           set uol_sol = ' '
	  	   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		   and uol_hora = ".db_getsession("DB_uol_hora")."
		   ") or die("Erro(33) atualizando db_usuariosonline");
}
*/


//<!--body bgcolor=#CCCCCC onLoad="js_iniciar()--!">
//</body>

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_iniciar() {
  setTimeout("location.href = 'topo2.php?verf=<?=@$verf?>'",30000);
}
</script>
</head>
</html>