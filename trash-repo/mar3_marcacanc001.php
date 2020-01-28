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
include("classes/db_cancmarca_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1[0].focus();">
<br>
<form name="form1" method="post">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" align="center">
 <tr>
  <td valign="top">
   <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
     <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
     <td nowrap align="center">
      <strong>&nbsp;&nbsp;&nbsp;
      Escolha a opção:
	  <?
       $tipo_ordem = array(""=>"-- Selecione --","todas"=>"Todas","prop"=>"Por Proprietário");
       db_select("escolha",$tipo_ordem,true,2,"onchange=\"js_opcao(this.value)\"");
	  ?><br>
      <span name="proprietario" id="proprietario" style="visibility:hidden">
      &nbsp;&nbsp;&nbsp;
      <?
      db_ancora("Cgm do Proprietário","js_pesquisama01_i_cgm(true);",1);
      db_input('z01_numcgm',10,'',true,'text',1," onchange='js_pesquisama01_i_cgm(false);'");
      db_input('z01_nome',40,'',true,'text',3,'');
      ?>
      </strong>
      </span>
      <br>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
     </td>
    </tr>
   </table>
   <table width="100%">
    <tr height="350">
     <td align="center" colspan="3">
      <iframe src="mar3_marcacanc002.php" name="framemarca" id="framemarca" width="100%" height="100%"></iframe>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisa(){
 if(document.form1.escolha.value==""){
  alert("Selecione alguma opção!");
 }else if(document.form1.escolha.value=="todas"){
  framemarca.location.href="mar3_marcacanc002.php?cgm";
 }else{
  if(document.form1.z01_numcgm.value==""){
   alert("Escolha um CGM para pesquisa!");
   document.form1.z01_numcgm.style.backgroundColor="#99A9AE";
   document.form1.z01_numcgm.focus();
  }else{
   framemarca.location.href="mar3_marcacanc002.php?cgm="+document.form1.z01_numcgm.value;
  }
 }
}
function js_opcao(valor){
 if(valor=="prop"){
  framemarca.location.href="mar3_marcacanc002.php";
  document.getElementById('proprietario').style.visibility = 'visible';
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = "";
  document.form1.z01_numcgm.focus();
 }else{
  framemarca.location.href="mar3_marcacanc002.php";
  document.getElementById('proprietario').style.visibility = 'hidden';
 }
}
function js_pesquisama01_i_cgm(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_conscanc.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa de Marcas Canceladas',true);
 }else{
  if(document.form1.z01_numcgm.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_conscanc.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa de Marcas Canceladas',false);
  }else{
   document.form1.z01_nome.value = '';
  }
 }
}
function js_mostracgm(chave1,chave2,erro){
 document.form1.z01_numcgm.value = chave1;
 document.form1.z01_nome.value = chave2;
 if(erro==true){
  document.form1.z01_numcgm.value = '';
  document.form1.z01_numcgm.focus();
 }
 framemarca.location.href="mar3_marcacanc002.php";
}
function js_mostracgm1(chave1,chave2){
 document.form1.z01_numcgm.value = chave1;
 document.form1.z01_nome.value = chave2;
 db_iframe_cgm.hide();
 framemarca.location.href="mar3_marcacanc002.php";
}
</script>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>