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

if(isset($HTTP_POST_VARS["excluir"])) {
  $tam_vetor = sizeof($HTTP_POST_VARS);
  reset($HTTP_POST_VARS);
  for($i = 0;$i < $tam_vetor;$i++) {
//    if(strtoupper($HTTP_POST_VARS[key($HTTP_POST_VARS)]) != "EXCLUIR") {
    if(db_indexOf(key($HTTP_POST_VARS),"CHECK") > 0) {
      $aux = explode("#",$HTTP_POST_VARS[key($HTTP_POST_VARS)]);
	  pg_exec("delete from db_menu where id_item = ".$aux[0]." and id_item_filho = ".$aux[1]." and modulo = ".$aux[2]) or die("Erro(10) excluindo db_menus: ".pg_errormessage());
    }	  
    next($HTTP_POST_VARS);
  }

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<script language="JavaScript">
function js_excluir() {
  var ver = false;
  for(i = 0;i < document.form1.elements.length;i++) {
    if(document.form1.elements[i].checked == true) {
	  ver = true;
	  break;
	}
  }
  if(ver == false) {
    alert('Voce tem que selecionar algum iten para exclui-lo!');
	return ver;
  } else
    return ver;  
}
function js_marcaP1(tag,inp) {
return true;
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
/*
function js_marca(tag,inp) {
return true;
  var T = document.getElementById(tag);
  
  for(i = 0;i < T.childNodes.length;i++) {
    if(T.childNodes[i].nodeName == "INPUT")
	  if(T.childNodes[i].attributes['id'].nodeValue == inp) {
	    var indice = i+3;
	    break;
	  }
  }
  estado = document.getElementById(inp).checked;
//  document.form1.valores.value = "";
  for(i = indice;i < T.childNodes.length;i++) {
    if(T.childNodes[i].nodeName == "INPUT") {
	  document.getElementById(T.childNodes[i].attributes["id"].nodeValue).checked = estado;
	//  document.form1.valores.value += T.childNodes[i].attributes["value"].nodeValue + "|";
	  T.childNodes[i].attributes["disabled"].nodeValue = estado;
  	  T.childNodes[i+1].attributes["disabled"].nodeValue = estado;
    }
  }
  //    document.getElementById('dd').innerHTML += T.childNodes[i].nodeName + '<br>';  
}
*/
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
<br>
<table width="790" align='center' border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
      <form name="form1" method="post">
        <?
	        if(!isset($HTTP_POST_VARS["selecionar"]) && !isset($HTTP_POST_VARS["ambiente"])) {
	      ?>
        <table border="0" cellspacing="0" cellpadding="0">
        
          <tr> 
            <td> 
              <strong>Pesquisa:</strong><br> 
              <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="35"> 
            </td>
          </tr>

          <tr> 
            <td> 
              <strong>M&oacute;dulo:</strong><br> 
			        <select onDblClick="document.form1.selecionar.click()" name="modulos" size="18" onChange="js_msg_status(this.value.substr(this.value.search('##') + 2))" onBlur="js_lmp_status()">
                <?
			            if(db_getsession("DB_id_usuario") == "1") {
				          
                    $result = pg_exec("select m.id_item,m.nome_modulo,m.descr_modulo 
				                                 from db_modulos m								   
                                        inner join db_itensmenu i on m.id_item = i.id_item
                                        where libcliente is true
								                        order by lower(m.nome_modulo)");
				          } else {				

				            $result = pg_exec("select m.id_item,m.nome_modulo,m.descr_modulo 
				                                 from db_modulos m
                                        inner join db_itensmenu i on m.id_item = i.id_item
					                              inner join db_usermod u on u.id_modulo = m.id_item
								                          and u.id_usuario = ".db_getsession("DB_id_usuario")."
						                            where u.id_instit = ".db_getsession("DB_instit")." 
                                          and libcliente is true
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
            <td>
            <input onClick="if(document.form1.modulos.selectedIndex == -1) { alert('Voce precisa selecionar um modulo!'); return false; }" name="selecionar" type="submit" id="selecionar" value="Selecionar" />
            </td>
          </tr>

        </table>
        <?
	        } else {
	      ?>
        <table width="100%" border="1" cellspacing="0" cellpadding="5">

          <tr> 
            <td align="center" nowrap> <strong>M&oacute;dulo: </strong> 
              <? 
			          $aux = $HTTP_POST_VARS["modulos"];
			          echo substr(strstr($aux,"||"),2);
			        ?>
            </td>
          </tr>
          
          <tr style='display:none'> 
            <td align="center"><strong>Ambiente:</strong> <input type="hidden" name="modulos" value="<?=$HTTP_POST_VARS["modulos"]?>">	
              <input name="ambiente" type="radio" id="web" value="1" onClick="document.form1.submit()" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="1"?"checked":""):"checked" ?>> 
              <label for="web"><strong>Web</strong></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input type="radio" name="ambiente" id="caracter" onClick="document.form1.submit()" value="0" <? echo isset($HTTP_POST_VARS["ambiente"])?($HTTP_POST_VARS["ambiente"]=="0"?"checked":""):"" ?>> 
              <label for="caracter"><strong>Caracter</strong></label> 
            </td>
          </tr>

          <tr rowspan="4">
            <td>
              <table border="0" align="center" cellspacing="0" cellpadding="5">

                <tr> 
                  <td><input type="submit" name="excluir" value="Excluir" onClick="return js_excluir()"></td>
                  <td><input name='retornar' type='button' value='Retornar' onclick="location.href='con4_orgexcl.php'" /> </td>
                </tr> 

              </table>
            </td>
          </tr> 

        </table>       
        <?
          $ambiente = (!isset($HTTP_POST_VARS["ambiente"])?"1":$HTTP_POST_VARS["ambiente"]);
          include("libs/db_submenus.php");
        ?>
            
        <?
		      }		 
		    ?>
      </form>
      <?
       db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  	  ?>
    </td>
  </tr>

</table>
</body>
</html>