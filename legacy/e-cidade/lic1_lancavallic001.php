<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_pcorcam_classe.php"));
$clpcorcam = new cl_pcorcam;
$clpcorcam->rotulo->label();
$clrotulo=new rotulocampo;
$clrotulo->label("l20_codigo");
db_postmemory($HTTP_POST_VARS);
$db_botao = true;
$action = 'lic1_orcamlancval001.php';
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
  if(document.form1.l20_codigo.value == ""){
    document.form1.l20_codigo.focus();
    alert("Informe o código da Licitção");
  }else{
    document.form1.submit();    
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.l20_codigo.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="<?=($action)?>">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
    </b> 
    </td>
    
    <td align="left" nowrap>
      <? db_input("l20_codigo",6,$Il20_codigo,true,"text",4,"onchange='js_pesquisa_liclicita(false);'");
          $lic='true'; 
	     db_input('lic',6,0,true,'hidden',3);
         ?></td>
  </tr>
  <!--
  <tr> 
    <td  align="left" nowrap title="<?=$Tpc20_codorc?>"> <? db_ancora(@$Lpc20_codorc,"js_pesquisa_pcorcam(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("pc20_codorc",8,@$Ipc20_codorc,true,"text",4,"onchange='js_pesquisa_pcorcam(false);'");
         $lic='true'; 
	     db_input('lic',6,0,true,'hidden',3);
      ?>
    </td>
  </tr>
  -->
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="lancar" type="button" onclick='js_abre();'  value="Enviar dados"<?=($db_botao == true?"disabled":"")?>>
    </td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?lCredenciamento&tipo=1&funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?lCredenciamento&tipo=1&pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){ 
    document.form1.lancar.disabled  = true;
    alert("Licitacao ja julgada,revogada ou com autorizacao ativa.");
    document.form1.l20_codigo.value = ''; 
    document.form1.l20_codigo.focus(); 
  }else{
   
    document.form1.l20_codigo.value = chave; 
    document.form1.lancar.disabled  = false;
	
	}
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;  
   document.form1.lancar.disabled  = false;
   db_iframe_liclicita.hide();
   js_abre();
}
//--------------------------------
function js_pesquisa_pcorcam(mostra){
  qry = "";
  <?
  echo "qry='&lic=true';";
  ?>
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?funcao_js=parent.js_mostrapcorcam1|pc20_codorc'+qry,'Pesquisa',true);
  }else{
     if(document.form1.pc20_codorc.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?pesquisa_chave='+document.form1.pc20_codorc.value+'&funcao_js=parent.js_mostrapcorcam'+qry,'Pesquisa',false);
     }
  }
}
function js_mostrapcorcam(chave,erro){
  if(erro==true){ 
    document.form1.pc20_codorc.focus(); 
    document.form1.pc20_codorc.value = ''; 
  }
}
function js_mostrapcorcam1(chave1,chave2){
  document.form1.pc20_codorc.value = chave1;
  db_iframe_pcorcam.hide();
}

js_pesquisa_liclicita(true);
//--------------------------------
</script>
</body>
</html>