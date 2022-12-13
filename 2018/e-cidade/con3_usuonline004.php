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

session_start();
include("libs/db_stdlib.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($HTTP_POST_VARS["sair"])) {
  $fp = fopen("chat/".$arquivo,"r+");
  $str = "\n"."2#".time()."#".$usuario."#"."CONVERSA ENCERRADA";
  fseek($fp,0,SEEK_END);
  fputs($fp,$str,strlen($str));
  fclose($fp);  
  shell_exec("rm -f chat/".$arquivo);
  ?>
  <script>
  window.opener.document.getElementById('sol').style.visibility = 'hidden';
  window.close();
  </script>
  <?
  exit;
}


if(isset($HTTP_POST_VARS["texto"])) {
  $fp = fopen("chat/".$arquivo,"r+");
  $str = "\n"."2#".time()."#".$usuario."#".$HTTP_POST_VARS["texto"];
  fseek($fp,0,SEEK_END);
  fputs($fp,$str,strlen($str));
  fclose($fp);
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
<table width="97%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="border: 1px solid #000000">
	<iframe frameborder="0" name="chat" src="con3_usuonline013.php?arquivo=<?=$arquivo?>" height="400" width="380"></iframe>
	</td>
  </tr>
  <tr>
    <td>
	<form name="form1" method="post" onSubmit="return js_submeter()">
	    <table border="0" width="100%" cellpadding="0" cellspacing="0">
          <tr> 
            <td height="35" nowrap> &nbsp; 
              <input type="submit" onClick="Botao='enviar'" name="enviar" value="Enviar">&nbsp;&nbsp;<input type="text" name="texto" size="40" maxlength="200">
			</td>
			<td>&nbsp;</td>
          </tr>
          <tr> 
            <td height="50" align="right" valign="bottom">
			  <input type="submit" name="sair" value="Sair" onClick="Botao='sair';return confirm('Quer realmente sair do chat?')">
			</td>
            <td width="86" align="right" valign="bottom"><img src="imagens/dbchat_o.gif" width="64" height="51"></td>
          </tr>
        </table>
	</form>
	</td>
  </tr>
</table>
</body>
</html>