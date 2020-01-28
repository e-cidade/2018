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
$rotulo = new rotulocampo();
$rotulo->label("pc10_numero");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_processa(){
  if (document.form1.pc10_numero.value!=""){
        location.href="com4_despsol002.php?pc10_numero="+document.form1.pc10_numero.value;
  }else{
    alert("Informe a Solicitação!!");
    document.form1.pc10_numero.focus();
  }
}
</script>
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <form method="post" name="form1" action="">
      <table>	
	<tr>
	  <td></td>
	  <td></td>
	  <td ></td>
	</tr>
	<tr>
	  <td title="<?=$Tpc10_numero?>">
	     <? db_ancora(@$Lpc10_numero,"js_pesquisa(true);",1); ?>
	  </td>
	  <td>
	     <?db_input("pc10_numero",15,$Ipc10_numero,true,"text",2,"onchange='js_pesquisa(false);'");?>
	  </td>
	   <td>	     
	   </td>
	   <tr>
	   <td colspan=3 align='center'>
	       <input type="button" value="Processar" onclick="js_processa();">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</form>
</body>
</html>
<script>
function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostra1|pc10_numero','Pesquisa',true);
  }else{
     if(document.form1.pc10_numero.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
     }
  }
}
function js_mostra(chave,erro){
  if(erro==true){    
    document.form1.pc10_numero.focus(); 
    document.form1.pc10_numero.value = ''; 
  }else{
    
  }
}
function js_mostra1(chave1){
  document.form1.pc10_numero.value = chave1;  
  db_iframe_solicita.hide();
}
</script>