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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
HabScrool = 0;
QualAtualizar = 'textarea';
function js_nis(at,nom) {
  var range = document.selection.createRange();
  range.execCommand(at);
  document.getElementById(nom).className = "esPressionado";
}
function js_fct(at,nom) {
  var range = document.selection.createRange();
  if(document.form1.elements[nom].value == "FP")
    range.execCommand("RemoveFormat");
  else {
    range.execCommand(at,true,document.form1.elements[nom].value);
  }
}
function js_fonteCor() {
  var range = document.selection.createRange();
  var texto = range.text;
  var fonte = document.getElementById("di").innerHTML;
  document.getElementById("di").innerHTML = fonte.replace(texto,texto.fontcolor(document.form1.cor.value));
  document.form1.saida_fonte.value = document.getElementById("di").innerHTML;
}
function js_createRange() {
  var range = document.selection.createRange();
  if(range.htmlText.indexOf("<U>") != -1 && range.htmlText.indexOf("</U>") != -1)
    document.getElementById("sublinhado").className = "esPressionado";
  else
    document.getElementById("sublinhado").className = "esDefault";
  if((range.htmlText.indexOf("<STRONG>") != -1 && range.htmlText.indexOf("</STRONG>") != -1) || (range.htmlText.indexOf("<B>") != -1 && range.htmlText.indexOf("</B>") != -1))
    document.getElementById("negrito").className = "esPressionado";
  else
    document.getElementById("negrito").className = "esDefault";	
  if(range.htmlText.indexOf("<EM>") != -1 && range.htmlText.indexOf("</EM>") != -1)
    document.getElementById("italico").className = "esPressionado";
  else
    document.getElementById("italico").className = "esDefault";
	alert(queryCommandText("fontsize"));	
	alert(queryCommandText("fontname"));	
	alert(queryCommandText("italic"));	
	alert(queryCommandText("bold"));		*/
//  alert(range.text);
//  alert(range.htmlText);
  
  alert(range.boundingHeight);
  alert(range.boundingWidth);
  alert(range.boundingTop);
  alert(range.boundingLeft);
  alert(range.offsetTop);
  alert(range.offsetLeft);

}
function js_submeter() {
  return true;
}
function js_iniciar() {
  //var novoEstado = !document.getElementById("di").isContentEditable;
  document.getElementById("di").contentEditable = true;
  document.getElementById("di").innerHTML = document.form1.saida_fonte.value;
}
function js_atualizar() {
  if(QualAtualizar == 'textarea')
    document.form1.saida_fonte.value = document.getElementById("di").innerHTML;
  else
    document.getElementById("di").innerHTML = document.form1.saida_fonte.value;
}

document.onmouseup = js_atualizar;
document.onkeyup = js_atualizar;

function js_mostrar_help() {
  if(document.getElementById("<?=$nome_help?>").style.visibility == "hidden") {
    MM_showHideLayers('<?=$nome_help?>','','show');
	document.form1.mostrar.value = "Ocultar Help";
  } else {
    MM_showHideLayers('<?=$nome_help?>','','hide');
	document.form1.mostrar.value = "Mostrar Help";	
  }
}
</script>
<STYLE TYPE="text/css">
.esDefault {
	border: 1px outset #999999;
}
.esPressionado {
	border: 1px inset #999999;
}
.tamFonte {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 8px;
	font-style: normal;
	font-weight: bold;
	text-decoration: none;
}
</STYLE>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
<br>
<!-- tabela de edição -->	
<table border="1" id="tabela" class="normal" width="50%">
<tr>
<td>
<div id="di" onMouseUp="js_createRange()" onFocus="QualAtualizar='textarea'"></div></td>
</tr>
</table>
<!-- fonte -->	
<form name="form1" method="post" onSubmit="return js_submeter()">
<table border="5" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="middle" nowrap bgcolor="#CCCCCC" style="border: thin outset #999999">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="21" valign="middle"> 
		    <img src="imagens/negrito.GIF" width="16" height="16" id="negrito" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('Bold','negrito');js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 			  
            <img src="imagens/italico.GIF" width="16" height="16" id="italico" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('Italic','italico');js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 			  
            <img src="imagens/sublinhado.GIF" width="16" height="16" id="sublinhado" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('Underline',this.id);js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 
            <img src="imagens/align_esquerda.JPG" width="16" height="16" id="align_esquerda" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('JustifyLeft',this.id);js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 
            <img src="imagens/centralizado.GIF" width="16" height="16" id="centralizado" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('JustifyCenter',this.id);js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 
            <img src="imagens/align_direita.GIF" width="16" height="16" id="align_direita" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('JustifyRight',this.id);js_atualizar()"> 
          </td>
          <td width="21" valign="middle"> 
            <img src="imagens/justificado.GIF" width="16" height="16" id="justificado" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('JustifyFull',this.id);js_atualizar()">
          </td>
          <td width="21" valign="middle"> 
			<img src="imagens/ident_esquerda.GIF" width="16" height="16" id="ident_esquerda" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('Outdent',this.id);js_atualizar()">
          </td>
          <td width="21" valign="middle"> 
			<img src="imagens/ident_direita.GIF" width="16" height="16" id="ident_direita" onMouseOver="this.className='esDefault'" onMouseOut="this.className=''" onClick="js_nis('Indent',this.id);js_atualizar()"> 
          </td>
          <td valign="middle">
			<select name="fonte" onChange="js_fct('FontName','fonte');js_atualizar()">
              <option value="FP">Fonte Padrão</option>
              <option value="Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
              <option value="Times New Roman, Times, serif">Times New Roman, Times, serif</option>
              <option value="Courier New, Courier, mono">Courier New, Courier, mono</option>
              <option value="Georgia, Times New Roman, Times, serif">Georgia, Times New Roman, Times, serif</option>
              <option value="Verdana, Arial, Helvetica, sans-serif">Verdana, Arial, Helvetica, sans-serif</option>
              <option value="Geneva, Arial, Helvetica, san-serif">Geneva, Arial, Helvetica, san-serif</option>
              </select>
		  </td>
          <td valign="middle">
			<select name="cor" onChange="js_fonteCor();js_atualizar()">
              <option value="FP">Cor Padrão</option>
              <option value="black" style="background-color:black;color:white">Preto</option>
              <option value="Blue" style="background-color:blue;color:white">Azul</option>
              <option value="yellow" style="background-color:yellow;color:white">Amarelo</option>
              <option value="green" style="background-color:green;color:white">Verde</option>
              <option value="red" style="background-color:red;color:white">Vermelho</option>
              <option value="aqua" style="background-color:aqua;color:white">Aqua</option>
              <option value="lime" style="background-color:lime;color:white">Lime</option>
              <option value="maroon" style="background-color:maroon;color:white">Marron</option>
              <option value="navy" style="background-color:navy;color:white">Navy</option>
              <option value="olive" style="background-color:olive;color:white">Oliva</option>
              <option value="purple" style="background-color:purple;color:white">Purple</option>
              <option value="silver" style="background-color:silver;color:white">Prata</option>
              <option value="teal" style="background-color:teal;color:white">teal</option>
            </select>
		  </td>
          <td valign="middle">
			<select name="tamanho" onChange="js_fct('FontSize','tamanho');js_atualizar()">
              <option value="FP">Sem</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="+1">+1</option>
              <option value="+2">+2</option>
              <option value="+3">+3</option>
              <option value="+4">+4</option>
              <option value="+5">+5</option>
              <option value="+6">+6</option>
              <option value="+7">+7</option>
              <option value="-1">-1</option>
              <option value="-2">-2</option>
              <option value="-3">-3</option>
              <option value="-4">-4</option>
              <option value="-5">-5</option>
              <option value="-6">-6</option>
              <option value="-7">-7</option>
            </select>
		  </td>
        </tr>
      </table> 
	</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">
	  <textarea name="saida_fonte" cols="90" rows="10" onFocus="QualAtualizar='div'">
        <?=@$texto_fonte?>
      </textarea>
	</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">
	  <input type="submit" name="salvar" value="Salvar">
      <input name="apagar_tudo" type="button" id="apagar_tudo2" value="Apagar Tudo" onClick="document.form1.saida_fonte.value = '';js_interpretar()"> 
    </td>
  </tr>   
</table>
</form>
	</td>
  </tr>
</table>

<?      
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


</body>
</html>