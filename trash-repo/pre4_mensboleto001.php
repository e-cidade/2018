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

if(isset($HTTP_POST_VARS["salvar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("BEGIN");
  pg_exec("UPDATE db_confmensagem SET mens = '$obs1',alinhamento = 'posx1=$posx1&posy1=$posy1&tam1=$tam1' where cod = 'obsboleto1'");
  pg_exec("UPDATE db_confmensagem SET mens = '$obs2',alinhamento = 'posx2=$posx2&posy2=$posy2&tam2=$tam2' where cod = 'obsboleto2'");
  pg_exec("UPDATE db_confmensagem SET mens = '$obs3',alinhamento = 'posx3=$posx3&posy3=$posy3&tam3=$tam3' where cod = 'obsboleto3'");
  pg_exec("UPDATE db_confmensagem SET mens = '$obs4',alinhamento = 'posx4=$posx4&posy4=$posy4&tam4=$tam4' where cod = 'obsboleto4'");                       
  pg_exec("COMMIT");
}
$result = pg_exec("select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
$obs1 = @pg_result($result,0,0);
parse_str(@pg_result($result,0,1));
$obs2 = @pg_result($result,1,0);
parse_str(@pg_result($result,1,1));
$obs3 = @pg_result($result,2,0);
parse_str(@pg_result($result,2,1));
$obs4 = @pg_result($result,3,0);
parse_str(@pg_result($result,3,1));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.leg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
  <form name="form1" method="post">
    <table width="75%" border="0" cellpadding="0" cellspacing="3">
      <tr>
        <td height="30" nowrap>&nbsp;</td>
        <td height="30" valign="bottom" class="leg">mensagem:</td>
        <td height="30" valign="bottom" class="leg">posx:</td>
        <td height="30" valign="bottom" class="leg">posy:</td>
        <td height="30" valign="bottom" class="leg">tam:</td>
      </tr>
      <tr> 
        <td width="6%" height="30" nowrap><strong>Obs 1:</strong></td>
        <td width="31%" height="30"><input name="obs1" type="text" value="<?=@$obs1?>" size="60"></td>
        <td width="23%" height="30" class="leg"><input name="posx1" type="text" value="<?=@$posx1?>" size="3" maxlength="3"></td>
        <td width="20%" height="30" class="leg"><input name="posy1" type="text" value="<?=@$posy1?>" size="3" maxlength="3"></td>
        <td width="20%" height="30" class="leg"><input name="tam1" type="text" value="<?=@$tam1?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="30" nowrap><strong>Obs 2:</strong></td>
        <td height="30"><input name="obs2" type="text" value="<?=@$obs2?>" size="60"></td>
        <td height="30" class="leg"><input name="posx2" type="text" value="<?=@$posx2?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="posy2" type="text" value="<?=@$posy2?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="tam2" type="text" value="<?=@$tam2?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="30" nowrap><strong>Obs 3:</strong></td>
        <td height="30"><input name="obs3" type="text" value="<?=@$obs3?>" size="60"></td>
        <td height="30" class="leg"><input name="posx3" type="text" value="<?=@$posx3?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="posy3" type="text" value="<?=@$posy3?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="tam3" type="text" value="<?=@$tam3?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="30" nowrap><strong>Obs 4:</strong></td>
        <td height="30"><input name="obs4" type="text" value="<?=@$obs4?>" size="60"></td>
        <td height="30" class="leg"><input name="posx4" type="text" value="<?=@$posx4?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="posy4" type="text" value="<?=@$posy4?>" size="3" maxlength="3"></td>
        <td height="30" class="leg"><input name="tam4" type="text" value="<?=@$tam4?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="30" nowrap>&nbsp;</td>
        <td height="30"><input name="salvar" type="submit" id="salvar" value="Salvar">
        </td>
        <td height="30" class="leg">&nbsp;</td>
        <td height="30" class="leg">&nbsp;</td>
        <td height="30" class="leg">&nbsp;</td>
      </tr>
    </table>
  </form>
</center>

	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
</body>
</html>