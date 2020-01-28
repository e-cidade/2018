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
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(!isset($gerar)) {
  $result = pg_exec("select corpofuncao from db_sysfuncoes where nomefuncao = '$funcao'");
  if(pg_numrows($result)==0){
     db_redireciona("sys1_funcoes001.php");
  }  
  db_fieldsmemory($result,0);
  db_postmemory($HTTP_POST_VARS);
  
  if(!($connx = pg_connect("host=".$maquina." dbname=".$base." port=".$porta." user=".$usuario." password=".$senha))) {
    echo "Erro(10) ao tentar conectar no servidor.";
    exit;
  }
  @pg_exec($connx,"drop function ".$funcao);
  $result = @pg_exec($connx,"$corpofuncao");
  if($result==false){
     echo "Erro ao criar a funcao: $funcao <br><br>".pg_errormessage();
	 exit;
  }
  pg_close($connx);
  db_redireciona("sys1_funcoes001.php");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
	<center>
	<form name="form1" method="post" action="sys1_funcoes002.php">
          <table width="100%">
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td align="right">Funcao:</td>
              <td>
                <?=$gerar?>
                <input name="funcao" type="hidden" id="funcao" value="<?=$gerar?>"></td>
            </tr>
            <tr> 
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td width="44%" align="right">M&aacute;quina:</td>
              <td width="56%"> <input name="maquina" type="text" id="maquina" value="<?=$DB_SERVIDOR?>" size="20"> 
              </td>
            </tr>
            <tr> 
              <td align="right">Base de Dados PostgreSql:</td>
              <td> <input name="base" type="text" id="base" value="<?=$DB_BASE?>" size="20"> 
              </td>
            </tr>
            <tr> 
              <td align="right">Porta:</td>
              <td><input name="porta" type="text"  value="<?=$DB_PORTA?>" size="5"></td>
            </tr>
            <tr> 
              <td height="21" align="right">Usu&aacute;rio:</td>
              <td><input name="usuario" type="text" value="<?=$DB_USUARIO?>" size="20"></td>
            </tr>
            <tr> 
              <td align="right">Senha:</td>
              <td><input name="senha" type="password"  value="<?=$DB_SENHA?>" size="20"></td>
            </tr>
            <tr align="center"> 
              <td colspan="2">
			    <input type="submit" name="processar"  value="Processar"> 
                <input type="button" name="cancelar"  onClick="document.location.href='sys1_funcoes001.php'" value="Cancelar"> 
              </td>
            </tr>
          </table>
		</form>
	</center>
    </td>
  </tr>
</table>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>