<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/libJsonJs.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
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
		  	<b>Escolha uma Vis�o:</b>
		  </legend>
		  <table align="center">
		    <tr>
		  	  <td>
				<?
				  
				  $sSqlView  = " select nomefuncao,nomefuncao "; 
				  $sSqlView .= "   from db_sysfuncoes 		  ";
				  $sSqlView .= "  where triggerfuncao = '2'	  ";
				  	
				  $rsConsultaView = pg_query($sSqlView) or die($sSqlView);
				  
				  db_selectrecord("selView",$rsConsultaView,true,1,"","","","","",1);
				  
				?>	
			  </td>
		    </tr>
		  </table>
		</fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
        <input name="voltar" type="button" value="Voltar" onClick="js_voltar();"/>      
      	<input name="enviar" type="button" value="Enviar" onClick="js_enviar();"/>
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
<script>
    
  function js_voltar(){
    location.href = 'sys4_escolhetiporel001.php';
  }

  function js_enviar(){
  
    var sUrl       = 'sys4_consultaviewRPC.php';
    var sQuery     = 'view='+document.form1.selView.value;
        sQuery    += '&tipo=incluirConsulta';
    var oAjax      = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: sQuery,
                                                 onComplete: js_retornoEnvio
                                               } 
                                        );   
  }
    

  function js_retornoEnvio(oAjax){
  
    var aRetorno = eval("("+oAjax.responseText+")");
    
    if ( aRetorno.erro ) {
      alert(aRetorno.msg.urlDecode());
    } else {
      document.location.href = 'sys4_confrelatorio001.php?lSql=false';
    }  
  }  
    
</script>