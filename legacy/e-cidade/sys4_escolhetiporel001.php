<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>
	function js_envia(){
	  
	  var objElemento = document.getElementsByTagName('input');
	  
	  for (var iInd=0; iInd < objElemento.length; iInd++ ) {
	    if ( objElemento[iInd].type == 'radio' && objElemento[iInd].checked == true ) {
	       if ( objElemento[iInd].value == 'view' ) {
		       location.href = 'sys4_escolheview001.php';
	       } else {
           location.href = 'sys4_configurasql001.php';
	       }
	    }
	  }
	  
	}
	
	function js_voltar(){
    location.href = 'sys4_geradorrelatorio001.php';
	}
	
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25" >&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <form name="form1" method="post" action="">
  <table style="padding-top:15px;">
    <tr> 
      <td> 
		<fieldset>
		  <legend align="center">
		  	<b>Montar Relatório :</b>
		  </legend>
		  <table>
		    <tr>
		  	  <td>
             <b>A partir de um SQL:</b>
	   		  </td>
	   		  <td>
	   		    <input type="radio" name="tiporel" value="sql" checked/>
	   		  </td>
		    </tr>
        <tr>
          <td>
             <b>A partir de uma Visão:</b>
          </td>
          <td>
            <input type="radio" name="tiporel" value="view"/>
          </td>
        </tr>		    
		  </table>
		</fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
        <input name="voltar" type="button" value="Voltar" onClick="js_voltar();"/>
      	<input name="enviar" type="button" value="Enviar" onClick="js_envia();"/>
      </td>
    </tr>
  </table>
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
