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

$clrotulo = new rotulocampo;
$clrotulo->label("cm33_sequencial");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
  <center>
    <table style="padding-top:23px;" align="center">
      <tr>
        <td>
			    <fieldset>
			      <legend>
			        <b>Emite Certidão de Isenção</b>
			      </legend>
				    <table>
				      <tr>
				        <td>
				         <?
				           db_ancora('<b>Código Isenção</b>',"js_pesquisaIsencao(true);",1);
				         ?>
				        </td>
				        <td> 
				         <?
				           db_input('codigo',10,$Icm33_sequencial,true,'text',1,"");
				         ?>
				        </td>
				      </tr>
						  <tr>
						    <td colspan="2" align="center"> 
						      <input name="emite" id="emite" type="button" value="Emitir" onclick="js_emite();" >
						    </td>
						  </tr>
						</table>
					</fieldset>
			  </td>
			</tr>
	  </table>	 	
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaIsencao(mostra){
 js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_sepultamentoisencao.php?funcao_js=parent.js_mostraisencao1|cm33_sequencial','Pesquisa',true);
}

function js_mostraisencao1(chave1,chave2){
  document.form1.codigo.value    = chave1;
  db_iframe_isencao.hide();
}

function js_emite(){
  
  var iCodIsen = document.form1.codigo.value;
  var sQuery   = '?codigoisencao='+iCodIsen;
  
  if ( iCodIsen == '' ) {
    alert('Nenhum código de isenção informado!');
    return false;
  }
    
  jan = window.open('cem2_emitecertisen002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
	
}
</script>