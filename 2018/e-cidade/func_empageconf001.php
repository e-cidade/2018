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

include("classes/db_empageconf_classe.php");
include("classes/db_empageconfcanc_classe.php");

$clempageconf = new cl_empageconf;
$clempageconfcanc = new cl_empageconfcanc;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method="post">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <?=db_input('movs',10,'',true,'hidden',10);?>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table>
	<tr>
	   <td colspan='2' align='center'>
		<input name="fechar" type="button" id="pesquisar" value="Fechar" onclick='parent.db_iframe_anula.hide();'>
	   </td>  
	</tr>     
	<tr>
	  <td>
           <iframe name="canc"  src="func_empageconf001_iframe.php?e83_codtipo=<?=$e83_codtipo?>&e80_codage=<?=$e80_codage?>"  width="760" height="270" marginwidth="0" marginheight="0" frameborder="0">
              
           </iframe>
	  </td>
	</tr>
      </table>
    </center>
    </td>
  </tr>
</table>
</form
</body>
</html>
<?
if(isset($atualizar)){
  db_msgbox($erro_msg);
}
?>