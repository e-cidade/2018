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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_solicita_classe.php");
$clpcorcam = new cl_pcorcam;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcproc = new cl_pcproc;
$clsolicita = new cl_solicita;
$clpcorcam->rotulo->label();
$clpcproc->rotulo->label();
$clsolicita->rotulo->label();
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_mostrapcorcamento(orcam){
	document.form1.pc20_codorc.value = orcam;
	db_iframe_pcorcam.hide();
}
var time;
function js_pc10(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?numero='+document.form1.pc10_numero.value+'&sol=true&rel=true&funcao_js=parent.js_mostrapcorcamento|pc20_codorc','Pesquisa',true);
  clearInterval(time);
}
function js_pc80(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?numero='+document.form1.pc80_codproc.value+'&sol=false&rel=true&funcao_js=parent.js_mostrapcorcamento|pc20_codorc','Pesquisa',true);
  clearInterval(time);
}
function js_abre(){
  if(document.form1.pc20_codorc.value == ""){
  	if(document.form1.pc10_numero.value != ""){
  		time = setInterval(js_pc10,10);
  	}else if(document.form1.pc80_codproc.value != ""){
  		time = setInterval(js_pc80,10);
  	}else{
      document.form1.pc20_codorc.focus();
      alert("Informe o código do orçamento");
  	}
  }else{
  	qry = "?orcam="+document.form1.pc20_codorc.value;
    jan = window.open('com2_relmotivo002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	  jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.pc20_codorc.focus();" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="">
<table border='0'>
  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr> 
    <td align="left" nowrap title="<?=$Tpc20_codorc?>">
      <? db_ancora(@$Lpc20_codorc,"js_pesquisa_pcorcam(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
      db_input("pc20_codorc",8,$Ipc20_codorc,true,"text",4,"onchange='js_pesquisa_pcorcam(false);'"); 
      ?>
    </td>
  </tr>
  <tr> 
    <td align="left" nowrap title="<?=$Tpc10_numero?>">
      <? db_ancora(@$Lpc10_numero,"js_pesquisa_solicita(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
      db_input("pc10_numero",8,$Ipc10_numero,true,"text",4,"onchange='js_pesquisa_solicita(false);'"); 
      ?>
    </td>
  </tr>
  <tr> 
    <td align="left" nowrap title="<?=$Tpc80_codproc?>">
      <? db_ancora(@$Lpc80_codproc,"js_pesquisa_pcproc(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
      db_input("pc80_codproc",8,$Ipc80_codproc,true,"text",4,"onchange='js_pesquisa_pcproc(false);'"); 
      ?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="lancar" type="button" onclick='js_abre();'  value="Enviar dados">
    </td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_pcorcam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamjulg','func_pcorcamjulg.php?funcao_js=parent.js_mostrapcorcam1|pc20_codorc','Pesquisa',true);
  }else{
     if(document.form1.pc20_codorc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcorcamjulf','func_pcorcamjulg.php?pesquisa_chave='+document.form1.pc20_codorc.value+'&funcao_js=parent.js_mostrapcorcam','Pesquisa',false);
     }
  }
}
function js_mostrapcorcam(chave,erro){
  if(erro==true){ 
    document.form1.pc20_codorc.focus(); 
    document.form1.pc20_codorc.value = ''; 
  }
}
function js_mostrapcorcam1(chave1){
  document.form1.pc20_codorc.value = chave1;
  db_iframe_pcorcamjulg.hide();
}


function js_pesquisa_solicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?passar=true&funcao_js=parent.js_mostrasolicita1|pc10_numero','Pesquisa',true);
  }else{
     if(document.form1.pc10_numero.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?passar=true&pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostrasolicita','Pesquisa',false);
     }
  }
}
function js_mostrasolicita(chave,erro){
  if(erro==true){ 
    document.form1.pc10_numero.focus(); 
    document.form1.pc10_numero.value = '';
  }
}
function js_mostrasolicita1(chave1){
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
}


function js_pesquisa_pcproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
  }else{
     if(document.form1.pc80_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
     }
  }
}
function js_mostrapcproc(chave,erro){
  if(erro==true){ 
    document.form1.pc80_codproc.focus(); 
    document.form1.pc80_codproc.value = '';
  }
}
function js_mostrapcproc1(chave1){
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
}
</script>
</body>
</html>