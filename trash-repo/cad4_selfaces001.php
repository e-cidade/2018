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
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("j67_naogeracarne");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>

<form name="form1" method="post" action="">
<center>
<br>
<table  align="center"  border="0">
	
  <tr>
    <td nowrap title="<?//=@$Tj67_naogeracarne?>">
       <b>       <?//=@$Lj67_naogeracarne?></b>
    </td>
    <td> 
<?
db_input('j67_naogeracarne',10,$Ij67_naogeracarne,true,'hidden',3,"")
?>
    </td>
  </tr>
	
  
  <tr align="center" >
    <td colspan=2  align="center">
      <input name="enviar" type="button"  id="db_opcao" value="Enviar" onclick="parag.js_atualizar();" >
    </td>
     
  </tr>
  <tr align="center" > 
    <td colspan="2" align="center" >
       <iframe id="parag"  frameborder="0" name="parag"   leftmargin="0" topmargin="0" src="cad4_selfaces002.php?j67_naogeracarne=<?=@$j67_naogeracarne?>" height="400" width="900">
       </iframe> 
    </td>  
  </tr>
  </table>
  </center>
</form>

    </center>
	</td>
  </tr>
</table>

</body>
</html>
<script>
function js_conclui(){
	parent.db_iframe_selface.hide();
	parent.document.form1.submit();
}

</script>