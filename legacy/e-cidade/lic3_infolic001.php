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
$clrotulo->label("pc10_numero");
$clrotulo->label("l20_codigo");
$clrotulo->label("pc80_codproc");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_abreconsulta(){
	if (document.form1.l20_codigo.value!=""){
		js_OpenJanelaIframe('top.corpo','db_iframe_infolic','lic3_infolic002.php?l20_codigo='+document.form1.l20_codigo.value,'Consulta Licitação',true);
	}else if(document.form1.pc80_codproc.value!=""){
		js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitaalt.php?pc80_codproc='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_abreconsulta2|l20_codigo','Pesquisa',true);
	}else if(document.form1.pc10_numero.value!=""){
		js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitaalt.php?pc10_numero='+document.form1.pc10_numero.value+'&funcao_js=parent.js_abreconsulta2|l20_codigo','Pesquisa',true);
	}
  document.form1.l20_codigo.value="";
}
function js_abreconsulta2(codigo){
	db_iframe_liclicita.hide();
	js_OpenJanelaIframe('top.corpo','db_iframe_infolic','lic3_infolic002.php?l20_codigo='+codigo,'Consulta Licitação',true);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
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
    <td  align="right" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licitação:',"js_pesquisa_liclicita(true);",1);?>
    </b> 
    </td>
    
    <td align="left" nowrap>
      <? db_input("l20_codigo",8,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
         ?></td>
         
  </tr>
    
  <tr> 
  <td align="right" nowrap title='$Tpc80_codproc'><?db_ancora(@$Lpc80_codproc,"js_pesquisa_pcproc(true);",1);?> </td>
  <td align='left' nowrap>
  <?
  db_input("pc80_codproc",8,$Ipc80_codproc,true,"text",4,"onchange='js_pesquisa_pcproc(false);'"); 
  ?>
    </td>
  </tr>
<tr>
    <td align="right" nowrap title='<?=$Tpc10_numero?>' > <?db_ancora(@$Lpc10_numero,"js_pesquisa_solicita(true);",1) ?> </td>

      <td align='left' nowrap>
  <?
      db_input("pc10_numero",8,$Ipc10_numero,true,"text",4,"onchange='js_pesquisa_solicita(false);'"); 
  ?>
       </td>
      </tr>
  
    
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="processar" type="button" onclick='js_abreconsulta();'  value="Processar">
    </td>
  </tr>
</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisa_solicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrasolicita1|pc10_numero','Pesquisa',true);
  }else{
     if(document.form1.pc10_numero.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostrasolicita','Pesquisa',false);
     }
  }
}
function js_mostrasolicita(chave,erro){
  if(erro==true){ 
    document.form1.pc10_numero.focus(); 
    document.form1.pc10_numero.value = ''; 
  }
}
function js_mostrasolicita1(chave1,chave2){
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
function js_mostrapcproc1(chave1,chave2){
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
}
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  document.form1.l20_codigo.value = chave; 
  if(erro==true){ 
    document.form1.l20_codigo.value = ''; 
    document.form1.l20_codigo.focus(); 
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;  
   db_iframe_liclicita.hide();
}
</script>
</body>
</html>