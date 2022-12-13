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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

if(isset($HTTP_POST_VARS["cadastrar"])) {
  db_postmemory($HTTP_POST_VARS);
  if(isset($itens))
    if(($itens == "" && $itens1 == "") || $menu == "" || $modulos == "") {
      db_erro("Erro. Todos os campos deverão estar selecionados!");
    }
  if(@$itens == "")
    $itens = $itens1;
  $itens = db_strpos($itens,"##");
  $modulos = db_strpos($modulos,"##");
  $menu_old = $menu;
  if($menu == "TOPO")
    $menu = $modulos;
  else
    $menu = db_strpos($menu,"##");
  $result = db_query("select id_item from db_menu where id_item = $itens and modulo = $modulos");
  if(pg_numrows($result) > 0) {
    $result = db_query("select id_item_filho from db_menu where id_item_filho = $itens and modulo = $modulos");
	if(pg_numrows($result) > 0) {
	  db_erro("Erro. Um item principal, não pode ser filho de outro item");
	}
  }
  $result = db_query("select max(menusequencia) + 1 from db_menu where id_item = $menu");
  $Mseq = pg_result($result,0,0);
  $Mseq = $Mseq==""?"1":$Mseq;
  db_query("insert into db_menu values($menu,$itens,$Mseq,$modulos)") or die("Erro(12) inserindo em db_menus: ".pg_errormessage());

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_cadastrar() {
  var F = document.form1;
  if((F.itens.selectedIndex == -1 || F.itens1.selectedIndex == -1) && F.menu.selectedIndex == -1) {
    alert('Selecione algum item e algum menu...');
	return false;
  }
}
function js_desmarca(nome) {
  var obj = document.form1.elements[nome];
  if(obj.selectedIndex != -1) {
    var indice = obj.selectedIndex;    
	var cor = obj.options[indice].style.backgroundColor;
    obj.options[indice] = new Option(obj.options[indice].text,obj.options[indice].value);
    obj.options[indice].style.backgroundColor = cor;	
  }
}
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_lmp_status();js_trocacordeselect()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<table width="100%" height="" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
        <form name="form1" method="post">
          <table border="0" cellspacing="0" cellpadding="3">
		  <?
		  if(!isset($HTTP_POST_VARS["selecionar"]) && !isset($HTTP_POST_VARS["ambiente"])) {
		  ?>
		  <tr>
              <td><strong>Pesquisa:</strong><br> <input name="procura3" type="text" id="procura3" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="35"></td>		  
		  </tr>
		  <tr>
		   <td nowrap><strong>No M&oacute;dulo:</strong><br> 
		   <select onDblClick="document.form1.selecionar.click()" name="modulos" size="15" id="modulos" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                  <?
				if(db_getsession("DB_id_usuario") == "1") {
				  $result = db_query("select m.id_item,m.nome_modulo,m.descr_modulo 
				                   from db_modulos m		
                                                        inner join db_itensmenu i on i.id_item = m.id_item	
                                                   where i.libcliente is true					   
								   order by lower(m.nome_modulo)");

				} else {				
				  $result = db_query("select m.id_item,m.nome_modulo,m.descr_modulo 
				                   from db_modulos m
                                                        inner join db_itensmenu i on i.id_item = m.id_item	
								   inner join db_usermod u
								   on u.id_modulo = m.id_item
								   and u.id_usuario = ".db_getsession("DB_id_usuario")."
								   where u.id_instit = ".db_getsession("DB_instit")."
                                                                         and  i.libcliente is true					   
								   order by lower(m.nome_modulo)");
			    }
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  echo "<option value=\"".pg_result($result,$i,"id_item")."##".pg_result($result,$i,"descr_modulo")."||".pg_result($result,$i,"nome_modulo")."\">".pg_result($result,$i,"nome_modulo")."</option>\n";
				}
				?>
               </select>
		    </td>
		  </tr>
		  <tr>
		  <Td><input onClick="if(document.form1.modulos.selectedIndex == -1) { alert('Voce precisa selecionar um modulo!'); return false; }" name="selecionar" type="submit" id="selecionar" value="Selecionar"></Td>
           </tr> 
		   <?
		   } else {
		   ?>
		   <tr>
			 <Td align="center" colspan="3">
			   <strong>Módulo: </strong>
			   <? 
			   $aux = $HTTP_POST_VARS["modulos"];
			   echo substr(strstr($aux,"||"),2)."</strong><br>";
			   echo "<font style=\"font-size:10px\">(".substr($aux,strpos($aux,"##")+2,strpos($aux,"||")-3).")</font>";
			   ?>
			 </Td>
		   </tr>

			<tr style="display:none"> 
        <td colspan="3" align="center"><strong>Ambiente:</strong>

			    <input type="hidden" name="modulos" value="<?=$HTTP_POST_VARS["modulos"]?>">
			    <input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="1"?"checked":""):"checked" ?>> 
          <label for="web"><strong>Web</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="0"?"checked":""):"" ?>>
          <label for="caracter"><strong>Caracter</strong></label>

			  </td>
      </tr>

            <tr> 
              <td><strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.itens)" size="26"></td>
              <td><strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.itens1)" size="26"></td>
              <td><strong>Pesquisa:</strong><br> <input name="procura2" type="text" id="procura2" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.menu)" size="26"></td>
            </tr>
            <tr> 

              <td nowrap>
			   <strong>Itens:</strong><br> 
			   <select name="itens" style="width:350px" onClick="js_desmarca('itens1')" size="13" id="itens" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                  <?				  
				$result = db_query("select distinct i.id_item,i.descricao, i.help,i.funcao,lower(i.descricao) 
				                   from db_itensmenu i
						        left join db_modulos modu on modu.id_item = i.id_item
							left join db_menu    menm on menm.id_item_filho = i.id_item and menm.modulo = ".db_strpos($HTTP_POST_VARS["modulos"],"##")."
							left join db_menu    menu on menu.id_item_filho = i.id_item
								   where i.itemativo = '".(!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"])."' 
								         and modu.id_item is null
									 and menm.id_item_filho is null
									 and menu.id_item_filho is not null
                                                                         and i.libcliente is true
								   order by lower(i.descricao)");
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  echo "<option value=\"".pg_result($result,$i,"id_item")."##(".pg_result($result,$i,"id_item").") ".pg_result($result,$i,"help")." - ".pg_result($result,$i,"funcao")."\">".pg_result($result,$i,"descricao")." - ".pg_result($result,$i,"funcao")."</option>\n";
				}
				?>
                </select> 
			  </td>
              <td nowrap>
			   <strong>Itens não organizados:</strong><br> 
			   <select name="itens1" style="width:350px" onClick="js_desmarca('itens')" size="13" id="itens1" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                  <?				  
				$result = db_query("select distinct i.id_item,i.descricao, i.help ,i.funcao,lower(i.descricao) 
				                   from db_itensmenu i
						        left join db_modulos modu on modu.id_item = i.id_item
							left join db_menu    menm on menm.id_item_filho = i.id_item and menm.modulo = ".db_strpos($HTTP_POST_VARS["modulos"],"##")."
							left join db_menu    menu on menu.id_item_filho = i.id_item
								   where i.itemativo = '".(!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"])."' 
								         and modu.id_item is null
									 and menm.id_item_filho is null
									 and menu.id_item_filho is null
                                                                         and i.libcliente is true
								   order by lower(i.descricao),i.id_item,i.help,i.funcao");
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  echo "<option value=\"".pg_result($result,$i,"id_item")."##(".pg_result($result,$i,"id_item").") ".pg_result($result,$i,"descricao")." - ".pg_result($result,$i,"funcao")."\">".pg_result($result,$i,"descricao")." - ".pg_result($result,$i,"funcao")."</option>\n";
				}
				?>
                </select> 
			  </td>
              <td nowrap> <strong>Ficar&aacute; embaixo de:</strong><br> 
			  <select name="menu" style="width:350px" size="13" id="menu" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                  <option value="TOPO">Menu Principal</option>
                  <?
				$result = db_query("
				select distinct i.id_item,i.descricao, i.help, i.funcao,lower(i.descricao) 
				from db_itensmenu i
				inner join db_menu m
				on m.id_item_filho = i.id_item
				where i.itemativo = '".(!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"])."' 
				and m.modulo = ".db_strpos($HTTP_POST_VARS["modulos"],"##")."
				and i.id_item not in(select id_item from db_modulos) 

				order by lower(i.descricao),i.id_item,i.help,i.funcao");
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  $menun = pg_result($result,$i,"id_item")."##".pg_result($result,$i,"help")." - ".pg_result($result,$i,"funcao");
				  echo "<option value=\"".pg_result($result,$i,"id_item")."##(".pg_result($result,$i,"id_item").") ".pg_result($result,$i,"help")." - ".pg_result($result,$i,"funcao")."\"  ".(isset($menu)&&$menu_old==$menun?"selected":"")." >".pg_result($result,$i,"descricao")." - ".pg_result($result,$i,"funcao")."</option>\n";
				}
				?>
                </select>
		 </td>             
            </tr>
            <tr> 
              <td nowrap>&nbsp;</td>
              <td nowrap><input name="cadastrar" onClick="return js_cadastrar()" type="submit" id="cadastrar" value="Cadastrar"></td>
              <td nowrap>&nbsp;</td>			  
            </tr>
			<?
			}
			?>
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