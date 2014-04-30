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
$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$rotulo = new rotulocampo();
$rotulo->label("m60_codmater");
$rotulo->label("m60_descr");
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
function js_consulta(){ 
   if (document.form1.m60_codmater.value==""){   	
     alert('Informe um Material!!Campo vazio!!');
     document.form1.m60_codmater.focus();
   }else{
   	 js_OpenJanelaIframe('top.corpo','db_iframe','mat3_matconsulta002.php?codmater='+document.form1.m60_codmater.value,'Consulta Material',true);
   }
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.m60_codmater.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <table cellspacing = 0>
        <form method="post" action="" name="form1">
	<tr>
	  <td nowrap title="<?=@$Tm60_codmater?>">
	     <?
	     db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",1);
	     ?>
	  </td>
	  <td colspan="3"> 
	     <?
      db_input('m60_codmater',10,$Im60_codmater,true,'text',1," onchange='js_pesquisam60_codmater(false);'")
	     ?>
	     <?
      db_input('m60_descr',40,$Im60_descr,true,'text',3,'')
	     ?>
	  </td>
	</tr>
          <tr>
	    <td nowrap >
	    </td>
	    <td>
	    </td>      
	  </tr>  
	  <tr>
	    <td colspan=2 style="text-align:center">
	      <input type="button" name="db_opcao" value="Consultar" onclick="js_consulta();" >
	      <input type="reset" value="Limpar">
	    </td>
	  </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisam60_codmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
     if(document.form1.m60_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
     }else{
       document.form1.m60_descr.value = ''; 
     }
  }
}
function js_mostramatmater(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m60_codmater.focus(); 
    document.form1.m60_codmater.value = ''; 
  }
}
function js_mostramatmater1(chave1,chave2){
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
}
</script>