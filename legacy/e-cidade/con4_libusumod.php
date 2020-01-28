<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

/*
Libera modulos para o usuário controlar as permissoes de outros usuarios
*/
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");


db_postmemory($_POST);
db_postmemory($_GET);

//parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($_GET["retorno"])){
  $retorno = @$_GET["retorno"];
}
if(isset($retorno)) {
  $aux = explode("_",$retorno); 
  $HTTP_POST_VARS["usuario"] = $aux[1];
  $HTTP_POST_VARS["selecionar"] = "eco";
  $instituicao = $aux[0];
  $usuario = $aux[1];
  $modulo = $aux[2];
}
if(isset($HTTP_POST_VARS["excluir"])) {
  db_query("delete from db_usermod where id_instit = $instit and id_usuario = $usuario and id_modulo = $modulos") or die("Erro(15) excluindo db_usermod: ".pg_errormessage());

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache($usuario);
}
if(isset($inserir)) {
  db_query("delete from db_usermod where id_instit = $instit and id_usuario = $usuario and id_modulo = $modulos") or die("Erro(15) excluindo db_usermod: ".pg_errormessage());
  db_query("insert into db_usermod values($instit,$usuario,$modulos)") or die("Erro(8) inserindo em db_usermod: ".pg_errormessage());
  $HTTP_POST_VARS["selecionar"] = "eco";

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache($usuario);
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</script>
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<br>
<br>
<table height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> <center>
        <?
	    if(isset($selecionar)) {
		?>
        <table border="1" cellspacing="0" cellpadding="5">
          <tr> 
            <td width='600' height='300'> 
            <iframe src='con4_libusumod002.php?usuario=<?=$usuario;?>' width='100%' height='100%' frameborder='0'>
            </iframe>
            </td>
          </tr>
        </table>
        <br><form name="form1" method="post">
        <table border="1" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="150"> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.instit)" size="25"> 
            </td>
            <td width="183"> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
          </tr>
          <tr> 
            <td><strong>Instituição:</strong></td>
            <td><strong>Módulos:</strong></td>
          </tr>
          <Tr> 
            <td valign="top"> <select name="instit" size="10">
                <?
		   if(isset($retorno)) {
			 $result = db_query("select distinct c.codigo,c.nomeinst,u.id_instit
                               from db_config c
                               left outer join db_usermod u							   
                               on u.id_instit = c.codigo
							   and u.id_usuario = ".$aux[1]);
           } else {
	         $result = db_query("select distinct c.codigo,c.nomeinst
                               from db_config c
                               inner join db_userinst u							   
                               on u.id_instit = c.codigo
							   and ( u.id_usuario = ".$usuario." or ".db_getsession("DB_id_usuario")." = 1)");
		  }
		    for($i = 0;$i < pg_numrows($result);$i++) {
		      echo "<option value=\"".pg_result($result,$i,"codigo")."\" ".($instituicao==pg_result($result,$i,"codigo")?"selected":"").">".pg_result($result,$i,"nomeinst")."</option>\n";
		    }
	      ?>
              </select> </td>
            <td valign="top"> <select name="modulos" size="10">
                <?
			if(isset($retorno)) {
			  $result = db_query("select m.id_item,m.nome_modulo,m.descr_modulo,u.id_modulo
			                     from db_modulos m
								 left outer join db_usermod u
								 on u.id_modulo = m.id_item
								 and u.id_usuario = ".$aux[1]."
								 order by lower(m.nome_modulo)");
			} else {
			  $result = db_query("select m.id_item,nome_modulo 
                                             from db_modulos m
                                                  inner join db_itensmenu i on i.id_item = m.id_item
                                             where libcliente is true
                                             order by lower(nome_modulo)");
			}
			  $numrows = pg_numrows($result);
			  for($i = 0;$i < $numrows;$i++) {
			    echo "<option value=\"".pg_result($result,$i,"id_item")."\" ".($modulo==pg_result($result,$i,"id_item")?"selected":"").">".pg_result($result,$i,"nome_modulo")."</option>\n";
			  }  
		  ?>
              </select> </td>
          </Tr>
          <Tr> 
            <td valign="top">
              <input type="hidden" name="usuario" value="<?=$HTTP_POST_VARS["usuario"]?>">
            </td>
            <td valign="top" nowrap>
              <input name="inserir" onClick="if(document.form1.instit.selectedIndex == -1 || document.form1.modulos.selectedIndex == -1) { alert('Voce precisa selecionar um modulo e uma instituição!'); return false; }" type="submit" value="Inserir" <? echo !isset($retorno)?"":"disabled" ?>> 
              <input type="submit" name="excluir" onClick="return confirm('Excluir permissão de módulo?')" value="Excluir" <? echo isset($retorno)?"":"disabled" ?>> 
            </td>
          </Tr>
        </table>
        <?
		} else {
	    ?>
        <form name="form1" method="post">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.usuario)" size="25"> 
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td valign="top"> <strong>Usuário:</strong><br> 
			  <select onDblClick="document.form1.selecionar.click()" name="usuario" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                  <?
			  $result = db_query("select id_usuario,nome,login from db_usuarios where usuext = 0 and usuarioativo = 1 order by lower(login)");
			  $numrows = pg_numrows($result);
			  for($i = 0;$i < $numrows;$i++) {
			    echo "<option value=\"".pg_result($result,$i,"id_usuario")."\">".pg_result($result,$i,"login")."</option>\n";
			  }  
		      ?>
                </select> </td>
            </tr>
            <tr> 
              <td><input onClick="if(document.form1.usuario.selectedIndex == -1) { alert('Primeiro voce deve selecionar um usuário!'); return false; }" name="selecionar" type="submit" id="selecionar" value="Selecionar"></td>
            </tr>
          </table>
          <?
		}
		?>
        </form>
      </center>
    </td>
  </tr>
</table>
</center>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>