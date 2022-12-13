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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
$censo_escola = db_getsession("DB_coddepto");
$censo_nomeescola = db_getsession("DB_nomedepto");
$clcalendario = new cl_calendario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset style="width:95%"><legend><b>Relatório Censo Escolar</b></legend>
<table border="0" align="left">
 <tr>
  <td nowrap>
   <b>Escola:</b>
  </td>
  <td>
   <?db_input('censo_escola',10,@$censo_escola,true,'text',3,"")?>
   <?db_input('censo_nomeescola',40,@$censo_nomeescola,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td>
   <b>Selecione o Ano:</b><br>
  </td>
  <td>
   <?
   $result= $clcalendario->sql_record($clcalendario->sql_query_calturma("","ed52_i_ano,ed52_i_ano","ed52_i_ano DESC"," ed38_i_escola = $censo_escola"));
   if($clcalendario->numrows==0){
    $x = array(' '=>'NENHUM REGISTRO');
    db_select('censo_ano',$x,true,1,"");
   }else{
    db_selectrecord("censo_ano",$result,"","","","censo_ano","","  ","",1);
   }
   ?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <br>
   <b>Informe os códigos dos ensinos:</b>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <?db_ancora("<b>Ensino Infantil Creche</b>","js_pesquisaed29_i_ensino(true,1);",@$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino1',10,"",true,'text',@$db_opcao1,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <?db_ancora("<b>Ensino Infantil Pré-Escola</b>","js_pesquisaed29_i_ensino(true,2);",@$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino2',10,"",true,'text',@$db_opcao1,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <?db_ancora("<b>Ensino Fundamental 8 anos</b>","js_pesquisaed29_i_ensino(true,3);",@$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino3',10,"",true,'text',@$db_opcao1,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <?db_ancora("<b>Ensino Fundamental 9 anos</b>","js_pesquisaed29_i_ensino(true,4);",@$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino4',10,"",true,'text',@$db_opcao1,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <?db_ancora("<b>Ensino Fundamental - EJA</b>","js_pesquisaed29_i_ensino(true,5);",@$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino5',10,"",true,'text',@$db_opcao1,"")?>
  </td>
 </tr>
 <tr>
  <td valign='bottom' colspan="2">
   <input type="button" name="processar" value="Processar" onclick="js_processar();">
  </td>
 </tr>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisaed29_i_ensino(mostra,numero){
 if(numero==1){
  funcao1 = "js_mostraensino1";
  campo1 = document.form1.ed29_i_ensino1.value;
 }else if(numero==2){
  funcao1 = "js_mostraensino2";
  campo1 = document.form1.ed29_i_ensino2.value;
 }else if(numero==3){
  funcao1 = "js_mostraensino3";
  campo1 = document.form1.ed29_i_ensino3.value;
 }else if(numero==4){
  funcao1 = "js_mostraensino4";
  campo1 = document.form1.ed29_i_ensino4.value;
 }else if(numero==5){
  funcao1 = "js_mostraensino5";
  campo1 = document.form1.ed29_i_ensino5.value;
 }
 js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?funcao_js=parent.'+funcao1+'|ed10_i_codigo','Pesquisa de Ensinos',true);
}
function js_mostraensino1(chave1,chave2){
 document.form1.ed29_i_ensino1.value = chave1;
 db_iframe_ensino.hide();
}
function js_mostraensino2(chave1,chave2){
 document.form1.ed29_i_ensino2.value = chave1;
 db_iframe_ensino.hide();
}
function js_mostraensino3(chave1,chave2){
 document.form1.ed29_i_ensino3.value = chave1;
 db_iframe_ensino.hide();
}
function js_mostraensino4(chave1,chave2){
 document.form1.ed29_i_ensino4.value = chave1;
 db_iframe_ensino.hide();
}
function js_mostraensino5(chave1,chave2){
 document.form1.ed29_i_ensino5.value = chave1;
 db_iframe_ensino.hide();
}
function js_processar(){
 if(document.form1.censo_ano.value==" "){
  alert("Informe o ano!");
 }else if(document.form1.ed29_i_ensino1.value=="" || document.form1.ed29_i_ensino2.value=="" || document.form1.ed29_i_ensino3.value=="" || document.form1.ed29_i_ensino4.value==""){
  alert("Informe os códigos de todos ensinos!");
 }else{
  jan = window.open('edu2_censoescolar002.php?censo_ano='+document.form1.censo_ano.value+'&censo_escola='+document.form1.censo_escola.value+'&ensino_inf_cre='+document.form1.ed29_i_ensino1.value+'&ensino_inf_pre='+document.form1.ed29_i_ensino2.value+'&ensino_fun_oito='+document.form1.ed29_i_ensino3.value+'&ensino_fun_nove='+document.form1.ed29_i_ensino4.value+'&ensino_fun_eja='+document.form1.ed29_i_ensino5.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
}
</script>