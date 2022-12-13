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

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:23px">
	  <tr> 
	    <td>
			  <fieldset>
			   <legend align="center">
			     <b>Remissão de Log</b>
			   </legend>
					<table>
					  <tr>
					    <td>
	 			        <?
					        db_ancora("<b>Código da Virada:</b>","js_pesquisaCodVirada()",1);
				        ?>
					    </td>
					    <td> 
								<?
								  db_input('codvirada',10,'',true,'text',1,"")
								?>
					    </td>
					  </tr>
				  </table>	
			  </fieldset>		
	  	</td>
	  </tr>
	  <tr>
	    <td align="center">
	      <input type="button" id="imprimir" value="Imprimir" onClick="js_imprimir()"/>
	    </td>
	  </tr>
	</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
	
	function js_imprimir(){
	
	  var iCodVirada = document.form1.codvirada.value;
	  
	  if ( iCodVirada.trim() == '' ) {
	    alert('Nenhum código de virada informado!');
	    return false;
	  }

	  jan = window.open('con4_viradadeano003.php?&virada='+iCodVirada,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	  
	}

	
	function js_pesquisaCodVirada(){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_virada','func_db_virada.php?funcao_js=parent.js_mostraCodVirada|c30_sequencial','Pesquisa Virada Anual',true);
	}

	function js_mostraCodVirada(chave1){
	  document.form1.codvirada.value = chave1;
	  db_iframe_db_virada.hide();
	}	
	
	
</script>