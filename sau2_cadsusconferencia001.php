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
include("classes/db_sau_cadsus_classe.php");
db_postmemory($HTTP_POST_VARS);
$clsau_cadsus = new cl_sau_cadsus();
$clrotulo = new rotulocampo;
$clrotulo->label("s136_i_codigo");
$clrotulo->label("s136_i_user");
$clrotulo->label("s136_d_data");
$clrotulo->label("nome");
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="630" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
<form name="form1" method="post" action="">
<center>
<br><br>
<fieldset style="width: 50%"><legend><b>Conferencia de Importação</b></legend>
<table border="0">
  <tr>
     <td><b>Data Inicial:</b></td>
     <td>
     <?db_inputdata("dataini","","","","","",1); ?>
     </td>
  </tr>
  <tr>
     <td><b>Data Final:</b></td>
     <td>
        <?db_inputdata("datafim","","","","","",1); ?>
     </td>
  </tr>
  <tr>
    <td nowrap title="Importação">
      <?db_ancora(@$Ls136_i_codigo,"js_pesquisas136_i_codigo(true);",$db_opcao);?>
    </td>
    <td>
    <?
db_input('s136_i_codigo',10,$Is136_i_codigo,true,'text',$db_opcao," onchange='js_pesquisas136_i_codigo(false);'");
db_input('s136_d_data',10,@$Is136_d_data,true,'text',3,'');
    ?>
    </td>
  </tr>
  <tr>
     <td>
       <?db_ancora(@$Ls136_i_user,"js_pesquisas136_i_user(true);",$db_opcao);?>
     </td>
     <td>
     <?
db_input('s136_i_user',10,$Is136_i_codigo,true,'text',$db_opcao," onchange='js_pesquisas136_i_user(false);'");
db_input('nome',30,@$Inome,true,'text',3,'');
     ?>
     </td>
  </tr>
  <tr>
      <td>
         <b>Tipo de Registro:</b>
      </td>
      <td>   
          <select name="tipo" value="0" id="tipo" style="height:18px;font-size:10px;">
             <option value="0">Todos</option>
             <option value="1">Consistente</option>
             <option value="2">Incinsistente</option>
          </select>
      </td>
  </tr>
  <tr>
   <td>
    <input type="button" value="Emitir" name="start" onclick="valida()">
   </td>
  </tr>
 </table>
 </fieldset>
</form>
<script language="javascript">
 function valida(){
  sep=where='';
  if(document.form1.s136_i_codigo.value != ""){
     where+=sep+'codigo='+document.form1.s136_i_codigo.value;
     sep='&';
  }
  if(document.form1.dataini.value != ""){
     where+=sep+'dataini='+document.form1.dataini.value;
     sep='&';
  }
  if(document.form1.datafim.value != ""){
     where+=sep+'datafim='+document.form1.datafim.value;
     sep='&';
  }
  if(document.form1.s136_i_user.value != ""){
     where+=sep+'user='+document.form1.s136_i_user.value;
     sep='&';
  }
  if(document.form1.tipo.value != ""){
     where+=sep+'tipo='+document.form1.tipo.value;
     sep='&';
  }
  jan = window.open('sau2_cadsusconferencia002.php?'+where,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
 function js_pesquisas136_i_codigo(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadsus','func_sau_cadsus.php?funcao_js=parent.js_mostracadsus1|s136_i_codigo|s136_d_data','Pesquisa',true);
 }else{
  if(document.form1.s136_i_codigo.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_cadsus','func_sau_cadsus.php?pesquisa_chave='+document.form1.s136_i_codigo.value+'&funcao_js=parent.js_mostracadsus','Pesquisa',false);
  }else{
   document.form1.s136_i_codigo.value = '';
  }
 }
}
function js_mostracadsus(chave,erro){
 document.form1.s136_d_data.value = chave;
 if(erro==true){
  document.form1.s136_i_codigo.focus();
  document.form1.s136_i_codigo.value = '';
 }
}
function js_mostracadsus1(chave1,chave2){
 document.form1.s136_i_codigo.value = chave1;
 vet=chave2.split('-');
 dat=vet[2]+'/'+vet[1]+'/'+vet[0];
 document.form1.s136_d_data.value = dat;
 db_iframe_cadsus.hide();
}
function js_pesquisas136_i_user(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_user','func_db_usuarios.php?funcao_js=parent.js_mostrauser1|id_usuario|nome','Pesquisa',true);
 }else{
  if(document.form1.s136_i_user.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_user','func_sau_usuarios.php?pesquisa_chave='+document.form1.s136_i_user.value+'&funcao_js=parent.js_mostrauser','Pesquisa',false);
  }else{
   document.form1.s136_i_user.value = '';
  }
 }
}
function js_mostrauser(chave,erro){
 document.form1.nome.value = chave;
 if(erro==true){
  document.form1.s136_i_user.focus();
  document.form1.s136_i_user.value = '';
 }
}
function js_mostrauser1(chave1,chave2){
 document.form1.s136_i_user.value = chave1;
 document.form1.nome.value = chave2;
 db_iframe_user.hide();
}
</script>
    </center>
	</td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>