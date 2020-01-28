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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_AbreJanelaRelatorio() { 
    if ( document.form1.processar.value != '' ) {
  	   jan = window.open('dvr4_parclote004.php?loteam='+document.form1.loteam.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	   jan.moveTo(0,0);
    }
}
function js_AbreJanelaRelatorio1() { 
    if ( document.form1.lista.value != '' ) {
  	   jan = window.open('dvr4_parclote005.php?loteam='+document.form1.loteam.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	   jan.moveTo(0,0);
    }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
       <form name="form1" method="post" action="dvr4_parclote004.php" >
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr align="center"> 
            <td height="84" colspan="3"><strong>EMISS&Atilde;O PARCELAMENTOS DE 
              LOTEAMENTOS</strong></td>
          </tr>
          <tr> 
            <td width="47%" height="35" align="right"><strong>Loteamento:</strong></td>
            <td width="2%">&nbsp;</td>
            <td width="51%">
			   <select name="loteam" id="select5">
                <option value="38" selected>38 - SOL NASCENTE </option>
                <option value="45">45 - POPULAR POR DO SOL</option>
              </select> </td>
          </tr>
          <tr> 
            <td height="64" align="right">&nbsp; </td>
            <td>&nbsp;</td>
            <td>&nbsp; </td>
          </tr>
          <tr align="center"> 
            <td height="64" colspan="3">
			   <input name="processar" type="submit" id="processar" value="Carn&ecirc;s" onClick="js_AbreJanelaRelatorio()"> 
              <input name="lista" type="submit" id="lista" value="   Lista   " onClick="js_AbreJanelaRelatorio1()"> 
            </td>
          </tr>
        </table>
	  </td>
        </form>
   </tr>
</table>
<?

  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>

</body>
</html>