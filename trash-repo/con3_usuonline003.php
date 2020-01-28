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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($HTTP_POST_VARS["sair"]) || @$sairfora == 1) {
  //usuario local
  //   uol_chat = uol_chat||'\n"."".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#"."Usuário ".db_getsession("DB_login")." saiu do chat',
  pg_exec("update db_usuariosonline set  
           uol_chat = ' ',
           uol_sol = ' '
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_hora = ".db_getsession("DB_uol_hora")."
		   ") or die("Erro(14) atualizando db_usuariosonline");
		   //		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
  //usuario remoto
  
  pg_exec("update db_usuariosonline set  
           uol_chat = uol_chat||'\n"."".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#"."Usuário ".db_getsession("DB_login")." saiu do chat',
   		   uol_sol = ' '
		   where uol_id = ".$id_usuario."
		   and uol_hora = ".$hora."
		   ") or die("Erro(21) atualizando db_usuariosonline");		   
		//		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
  ?>
  <script>
  top.window.close();
  </script>
  <?
  exit;
}

if(isset($HTTP_POST_VARS["texto"])) {
  //usuario local
  $HTTP_POST_VARS["texto"] = htmlspecialchars($HTTP_POST_VARS["texto"]);
  
  pg_exec("update db_usuariosonline set  
           uol_chat = uol_chat||'\n"."".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#".$HTTP_POST_VARS["texto"]."'
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_hora = ".db_getsession("DB_uol_hora")."
		   ") or die("Erro(45) atualizando db_usuariosonline");
		   //		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
  //usuario remoto
  pg_exec("update db_usuariosonline set  
           uol_chat = uol_chat||'\n"."".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#".$HTTP_POST_VARS["texto"]."'
		   where uol_id = ".$id_usuario."
		   and uol_hora = ".$hora."
		   ") or die("Erro(52) atualizando db_usuariosonline");  
		   //		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
} else {
  //usuario local
  /*
  pg_exec("update db_usuariosonline set  
           uol_chat = uol_chat||'\n".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#Solicita conversa'
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		   and uol_hora = ".db_getsession("DB_uol_hora")."
		   ") or die("Erro(70) atualizando db_usuariosonline");
  */
  //usuario remoto        
  if($verfusuario != 1)  {
//  uol_chat = uol_chat||'\n".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#Solicita conversa',
    pg_exec("update db_usuariosonline set  
             uol_chat = '\n".db_getsession("DB_id_usuario")."#".time()."#".db_getsession("DB_login")."#Solicita conversa',           
		     uol_sol = '".db_getsession("DB_id_usuario")."#".db_getsession("DB_uol_hora")."#".db_getsession("DB_login")."'
		     where uol_id = ".$id_usuario."		     
		     and uol_hora = ".$hora."
		     ") or die("Erro(77) atualizando db_usuariosonline");		   
			 //and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
  }
}
?>
<html>
<head>
<title>CHAT</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
Botao = '';
function js_submeter() {
  if(Botao == 'enviar') {
    if(document.form1.texto.value == "")
      return false;
    else
      return true;
  }
  return true;
}
</script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FBF8CD" onLoad="document.form1.texto.focus()">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	 	<form name="form1" method="post" onSubmit="return js_submeter()">
	    <table border="0" width="100%" cellpadding="5" cellspacing="0">
          <tr> 
            <td width="87%" nowrap> 
              <input type="submit" onClick="Botao='enviar'" name="enviar" value="Enviar">
			  &nbsp;<input type="text" name="texto" size="40" maxlength="200">
			</td>
			<td width="13%">&nbsp;</td>
          </tr>
          <tr> 
            <td align="right" valign="bottom">
			  <input type="submit" name="sair" value="Sair" onClick="Botao='sair';return confirm('Quer realmente sair do chat?')">
			</td>
            <td align="right" valign="bottom"><img src="imagens/dbchat_o.gif" width="64" height="51"></td>
          </tr>
        </table>
	</form>
	</td>
  </tr>
</table>
</body>
</html>