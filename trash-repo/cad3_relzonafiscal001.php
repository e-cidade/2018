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
include("classes/db_zonasvalor_classe.php");
$clzonasvalor = new cl_zonasvalor;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){
  jan = window.open('cad3_relzonafiscal002.php?anousu='+document.form1.anousu.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <form name="form1" method="post" >
          <table width="41%" border="0" cellspacing="0" cellpadding="0">
<!--            <tr> 
              <td height="30"><strong>Exerc&iacute;cio:</strong></td>
              <td height="30"><input name="exercicio" type="text" id="exercicio" value="<?=db_getsession("DB_anousu")?>" size="4" maxlength="4"></td>
            </tr>
-->
     <tr>
        <td colspan=2><hr></td>

     </tr>
          <tr> 
              <td height="30">
	      
			<select name="anousu" id="anousu">
             <?
	     $result = $clzonasvalor->sql_record($clzonasvalor->sql_query_file("",""," distinct j51_anousu"));
	     for($i = 0;$i < $clzonasvalor->numrows;$i++){
	       db_fieldsmemory($result,$i);
	       echo "<option value=\"".($j51_anousu)."\">".($j51_anousu)."</option>\n";
	     }
	     ?>
              </select> 
	      
	     </td>
              <td height="30" align="center"><input name="emite" onClick="return js_relatorio()" type="button" id="emite" value="Emite Relat�rio"> 
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