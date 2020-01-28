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

db_putsession('temp_file','temp');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  
if(isset($HTTP_POST_VARS["atualizarperm"])) {
  $modulo = $HTTP_POST_VARS["modulos"];
  $ambiente = $HTTP_POST_VARS["ambiente"];

  pg_exec("BEGIN");
  //primeiro delete os itens
  pg_exec("delete from ".db_getsession("temp_file")."
           where id_modulo = $modulo") or die("Excluir Itens: ".pg_errormessage());
  //inclui novamente os itens
  $tam_vetor = sizeof($HTTP_POST_VARS);
  reset($HTTP_POST_VARS);
  for($i = 0;$i < $tam_vetor;$i++) {
    if(db_indexOf(key($HTTP_POST_VARS),"CHECK") > 0) {
      pg_exec("insert into ".db_getsession("temp_file")." values($modulo,".$HTTP_POST_VARS[key($HTTP_POST_VARS)].")") or die("Erro(18) inserindo em db_permissao: ".pg_errormessage());
    }
    next($HTTP_POST_VARS);
  }
  pg_exec("COMMIT");  
  //
  unset($HTTP_POST_VARS['mod']);
  $selecionar = true;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
function js_marcaP1(tag,inp) {
  var tag = document.getElementById(tag);
  var inp = document.getElementById(inp);
   //marca o item principal
  for(var i = 0;i < tag.childNodes.length;i++) {    
    if(tag.childNodes[i].nodeName == "INPUT") {
	  tag.childNodes[i].checked = true;
	  break;
	}
  }
  //marca o item principal do submenu  
  var subm2 = inp;
  var wd = subm2.width;
  while(subm2 != null) {
    for(;;) {	
	  subm2 = subm2.previousSibling;
	  if(subm2 == null)	    
	    return true;	 
	  if(subm2.nodeName == "IMG")
	    break;
	}
	if(wd > subm2.width) {
	  subm2.nextSibling.checked = true;
	  wd = subm2.width;
	}
  }  
  return true;
}
function js_marcaP2(tag,inp) {
  var tag = document.getElementById(tag);
  var inp = document.getElementById(inp);
  //marca todo o submenu  
  var inp2 = inp;
  for(;;) {
    for(;;) {
	  inp2 = inp2.nextSibling;
	  if(inp2 == null)
	    return true;
	  if(inp2.nodeName == "IMG")
	    break;
	}
      if(inp2.width > inp.width) {
	    if(inp.nextSibling.checked == true) {
	      inp.nextSibling.checked = true;
	      inp2.nextSibling.checked = true;
	    } else {
	      inp.nextSibling.checked = false;
	      inp2.nextSibling.checked = false;
	    }
	  } else
	    break;	
  }
  return true;
}
function js_marca(tag,inp) {
  var tag = document.getElementById(tag);
  
  if(inp.checked == true)
    var ck = true;
  else
    var ck = false;
  for(var i = 0;i < tag.childNodes.length;i++) {    
	if(tag.childNodes[i].nodeName == "INPUT") {
	  tag.childNodes[i].checked = ck;	  
	}
  }
/*
  for(var i in tag)
    document.getElementById('dd').innerHTML += i + ' = ' + tag[i] + '<br>';
*/	
  return true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
    <form name="form1" method="post">
      <?
     if(!isset($HTTP_POST_VARS['mod']) && isset($selecionar)) {
	  ?>
      <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
      </tr>
	  <Tr>
	    <td> <strong>M&oacute;dulo:</strong><br> 
	  <select onDblClick="document.form1.mod.click()" name="modulos" size="18"  >
        <?
	    $result = pg_exec("select id_item,nome_modulo,descr_modulo 
	    from db_modulos 
	    order by lower(nome_modulo)");
	    $numrows = pg_numrows($result);
		for($i = 0;$i < $numrows;$i++) {
		   echo "<option value=\"".pg_result($result,$i,"id_item")."\">".pg_result($result,$i,"nome_modulo")."</option>\n";
		}  
		?>
        </select> 
	    </td>
	  </Tr>
	  <tr>
	    <td>
		<input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um módulo!'); return false; }" name="mod" type="submit" id="selecionar" value="Selecionar"></td>
	  </tr>
	  </table>
	  <?
	  } else if(isset($HTTP_POST_VARS["mod"])) {
		  $result = pg_exec("select nome_modulo,descr_modulo from db_modulos where id_item = ".$HTTP_POST_VARS["modulos"]);
	      $mod = pg_result($result,0,0);
	      $des = pg_result($result,0,1);
	  ?>
<table border="1" cellspacing="0" cellpadding="0">
<tr><td>
       <table border="0" cellspacing="0" cellpadding="0">
	     <tr>
		   <td>Módulo:</td>
		   <td nowrap><?=$mod?>&nbsp;&nbsp;<font style="font-size:10px">(<?=$des?>)</font></td>
		 </tr>
	  </table>
</td></tr>
<tr><td valign="top">
       <table border="0" cellspacing="0" cellpadding="0">
	     <tr>
		   <td><input type="submit" name="atualizarperm" value="Atualizar Permiss&otilde;es"></td>
		 </tr>
	  </table>
</td></tr>
<tr>
		  <tr>
		    <td align="center"><strong>Ambiente:</strong>
			<input name="modulos" type="hidden" value="<?=$HTTP_POST_VARS["modulos"]?>">
			<input name="mod" type="hidden" value="selecionar">
			 <input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="1"?"checked":""):"checked" ?>> 
             <label for="web"><strong>Web</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
             <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="0"?"checked":""):"" ?>>
             <label for="caracter"><strong>Caracter</strong></label>
			</td>
		  </tr>
<td valign="top">
      <table border="1" cellspacing="0" cellpadding="0">	  
         <tr> 
           <td> 
		   <? 
		   $ambiente = (!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"]);		  		   
		   	$wid = 15;
			$conta = 0;
			/***************/			
            function submenus($item,$id,$mod) {
			  global $conta;
			  global $wid;
			  global $ambiente;
			  global $HTTP_POST_VARS;
              $sub = pg_exec("select temp.id_item as perm ,m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo 
                              from db_menu m 
							       inner join db_itensmenu i on i.id_item = m.id_item_filho 
								   left outer join ".db_getsession("temp_file")."  temp on temp.id_modulo = $mod and temp.id_item = m.id_item_filho 
                              where m.modulo = $mod 
							  and m.id_item = $item 
							  and i.itemativo = $ambiente");			  
			  $numrows = pg_numrows($sub);
              if($numrows > 0) {
                for($x = 0;$x < $numrows;$x++) {                  
				  $valor = pg_result($sub,$x,"id_item_filho");
                  echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" ><input onClick=\"js_marcaP1('$id','Img".$conta."');js_marcaP2('$id','Img".$conta."')\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" ".(pg_result($sub,$x,"perm")==""?"":"checked").">
				  <label for=\"ID$valor\">".pg_result($sub,$x,"descricao")."</label><br>\n";
				  $wid += 15;
				  $conta++;
				  submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod);
				  $wid -= 15;
                }				                
              }
            }
			/**************/
		$SQL = "select temp.id_item as perm, i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
	                           from db_itensmenu i 
	                           inner join db_menu m 
	                           on m.id_item_filho = i.id_item 
							   left outer join ".db_getsession("temp_file")."  temp on temp.id_modulo = ".$HTTP_POST_VARS["modulos"]." and temp.id_item = m.id_item_filho 
	                           where m.modulo = ".$HTTP_POST_VARS["modulos"]."
							   and i.itemativo = $ambiente							   
							   and m.id_item = ".$HTTP_POST_VARS["modulos"];
            $result = pg_exec($SQL);			
            for($i = 0;$i < pg_numrows($result);$i++) {
			  $valor = pg_result($result,$i,"id_item_filho");
              echo "<td id=\"col$i\" valign=\"top\" nowrap>\n<input onclick=\"js_marca('col$i',this)\" type=\"checkbox\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" ".(pg_result($result,$i,"perm")==""?"":"checked").">
			  <label for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
              submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($HTTP_POST_VARS["modulos"],"##"));
			  echo "</td>\n";
            }	   
		   ?> 
		   </td>
         </tr>
       </table>
</td></tr>
</table>
	<?
	}
	?>	  
    </form>
  </center>
  </td></tr>
</table>
</body>
</html>