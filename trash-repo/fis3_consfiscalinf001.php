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
include("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y30_codnoti");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_consulta(){
  if (document.form1.y30_codnoti.value==""){
    alert('Informe uma  Notificação!!Campo vazio!!');
    document.form1.y30_codnoti.focus();
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','fis3_consfiscalinf002.php?codauto='+document.form1.y30_codnoti.value,'Consulta Notificação',true);
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
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
      <form name="form1" method="post" action="">
      <table border="0">
	<tr>   
	  <br><br>
	  <td title="<?=@$Ty30_codnoti?>" >
	  <?
	   db_ancora(@$Ly30_codnoti,' js_noti(true); ',1);
	  ?>
	  </td>    
	  <td title="<?=@$Ty30_codnoti?>" colspan="4">
	  <?
	   db_input('y30_codnoti',5,@$Iy30_codnoti,true,'text',1,"onchange='js_noti(false)'");
	   db_input('z01_nome',50,0,true,'text',3);
	  ?>
	  </td>
	</tr>
	<tr>
	<td colspan=2 align=center >
	     <input type=button name='processar'  value='Processar' onclick='js_consulta();' >
	</td>
	</tr>
       </table>
       </center>
       </form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_noti(mostra){
  var noti=document.form1.y30_codnoti.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_noti','func_fiscalalt.php?funcao_js=parent.js_mostranoti|y30_codnoti|z01_nome','Pesquisa',true);
  }else{
    if(noti!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_noti','func_fiscalalt.php?pesquisa_chave='+noti+'&funcao_js=parent.js_mostranoti1','Pesquisa',false);
    }else{
      document.form1.z01_nome.value="";
      document.form1.submit();  
    }
  }
}
function js_mostranoti(chave1,chave2){
  document.form1.y30_codnoti.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_noti.hide();
  document.form1.submit(); 
}
function js_mostranoti1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y30_codnoti.focus(); 
    document.form1.y30_codnoti.value = ''; 
  }else{
    document.form1.submit();
  }
}
</script>