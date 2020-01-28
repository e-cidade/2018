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
$clrotulo->label("k17_codigo");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_abre(){
  if(document.form1.k17_codigo.value == ""){
    document.form1.k17_codigo.style.backgroundColor='#99A9AE';
    document.form1.k17_codigo.focus();
    alert("Informe o código Slip.");
  }else{
    jan = window.open('cai1_slip003.php?numslip='+document.form1.k17_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    document.form1.k17_codigo.style.backgroundColor='';
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.k17_codigo.focus();" bgcolor="#cccccc">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
	<td width="360" height="18">&nbsp;</td>
	<td width="263">&nbsp;</td>
	<td width="25">&nbsp;</td>
	<td width="140">&nbsp;</td>
      </tr>
    </table>
<center>
<form name="form1" method="post">
			
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tk17_codigo?>"> <? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("k17_codigo",8,$Ik17_codigo,true,"text",4,"onchange='js_pesquisak17_codigo(false);'"); 
      ?>
    </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="relatorio" type="button" onclick='js_abre();'  value="Gerar relatório">
  </td>
  </tr>
  </table>
  </form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisak17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  }else{
     if(document.form1.k17_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?pesquisa_chave='+document.form1.k17_codigo.value+'&funcao_js=parent.js_mostraslip','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostraslip(chave,erro){
  if(erro==true){ 
    document.form1.k17_codigo.focus(); 
    document.form1.k17_codigo.value = ''; 
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}
//--------------------------------
</script>
</body>
</html>