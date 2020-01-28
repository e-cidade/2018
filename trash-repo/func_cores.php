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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
?>
<html>
<title></title>
<body bgcolor=#CCCCCC bgcolor="#cccccc">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center" valign="middle"> 
    <td height="22">
      <font face="arial" size="2"><strong>Escolha a cor desejada</strong></font>
    </td>
  </tr>  
  <tr align="center" valign="middle"> 
    <td height="22"> 
     <table width="25" border="4" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          <script language="JavaScript">
  	  var hex = new Array(6)
	  // assign non-dithered descriptors
	  hex[0] = "FF"
          hex[1] = "CC"
	  hex[2] = "99"
	  hex[3] = "66"
	  hex[4] = "33"
	  hex[5] = "00"
          // accept triplet string and display as background color
	  function display(triplet) {
        	// set color as background color
		document.form1.cor.value = '#' + triplet
		// display the color hexadecimal triplet
		document.bgColor = '#' + triplet
	  }
	  // draw a single table cell based on all descriptors
	  function drawCell(red, green, blue) {
         	// open cell with specified hexadecimal triplet background color
		document.write('<TD BGCOLOR="#' + red + green + blue + '">')
		// open a hypertext link with javascript: scheme to call display function
         	document.write('<A HREF="javascript:display(\'' + (red + green + blue) + '\')">')
		// print transparent image (use any height and width)
		document.write('<IMG SRC="place.gif" BORDER=0 HEIGHT=12 WIDTH=12>')
		// close link tag
		document.write('</A>')
		// close table cell
		document.write('</TD>')
	  }
	  // draw table row based on red and blue descriptors
	  function drawRow(red, blue) {
    	// open table row
		document.write('<TR>')
		// loop through all non-dithered color descripters as green hex
         	for (var i = 0; i < 6; ++i) {
  		drawCell(red, hex[i], blue)
		}
	// close current table row
		document.write('</TR>')
          }
	  // draw table for one of six color cube panels
	  function drawTable(blue) {
        	// open table (one of six cube panels)
        	document.write('<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>')
		// loop through all non-dithered color descripters as red hex
		for (var i = 0; i < 6; ++i) {
  		drawRow(hex[i], blue)
		}
	// close current table
		document.write('</TABLE>')	
	  }
        // draw all cube panels inside table cells
	  function drawCube() {
    	// open table
		document.write('<TABLE CELLPADDING=5 CELLSPACING=0 BORDER=1><TR>')
		// loop through all non-dithered color descripters as blue hex
        	for (var i = 0; i < 6; ++i) {
 		// open table cell with white background color
		document.write('<TD BGCOLOR="#FFFFFF">')
		// call function to create cube panel with hex[i] blue hex
		drawTable(hex[i])
        // close current table cell
        	document.write('</TD>')
        	}
	// close table row and table
		document.write('</TR></TABLE>')
          }
          // call function to begin execution
	  drawCube()
	  // -->
      </script>
    </td>
  </tr>
</table>
<form name="form1" method="post" action=""> 
<tr align="center" valign="middle">
    <td height="30">
      <input type="text" value="" size="9" name="cor">
    </td>
  </tr>  
<tr align="center" valign="middle">
    <td height="30">
      <input type="submit" value="Atualizar" name="enviar" onClick="parent.db_iframecor.hide();" >
    </td>
  </tr>  
</form>
</td>
</tr>
</table>
</body>
</html>
<?
if(isset($enviar)){
  echo "<script>".$funcao_js."('$cor');</script>";
}
?>