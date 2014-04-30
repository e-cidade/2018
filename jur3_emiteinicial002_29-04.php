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
include("classes/db_iptubase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_advog_classe.php");
include("classes/db_inicial_classe.php");
include("classes/db_inicialcert_classe.php");
include("classes/db_inicialmov_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$botao=1;
$db_opcao=1;
$verificachave=true;
$veinclu=false;





$clinicial = new cl_inicial;
$clinicialcert = new cl_inicialcert;
$clinicialmov = new cl_inicialmov;
$cladvog = new cl_advog;
$cladvog->rotulo->label();
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");



$clrotulo = new rotulocampo;
$clrotulo->label("v56_codsit");
$clrotulo->label("v52_descr");
$clrotulo->label("v54_codlocal");
$clrotulo->label("v54_descr");
$clrotulo->label("v50_data");
$clrotulo->label("v50_advog");
$clrotulo->label("v50_inicial");

  if(isset($v13_certid) && $v13_certid!="" && $veinclu==false){
    db_msgbox("Não existe!");
  }
  ?>

  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  <!--
  td {
	  font-family: Arial, Helvetica, sans-serif;
	  font-size: 12px;
  }
  input {
	  font-family: Arial, Helvetica, sans-serif;
	  font-size: 12px;
	  height: 17px;
	  border: 1px solid #999999;
  }
  -->
  </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr><td align="center" valign="top">
<form name="form1" method="post" action="jur3_emiteinicial021.php">
  <table>
    <tr height=""> 
      <td valign="top" colspan="2" align="center">
      <b> Consulta Inicial</b>
      <td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_inicial?>">
       <?
       db_ancora(@$Lv50_inicial,"js_pesquisav50_inicial(true);",1);
       ?>
    </td>
    <td> 
<?
  db_input('v50_inicial',8,$Iv50_inicial,true,'text',1," onchange='js_pesquisav50_inicial(false);'")
  ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tv56_codsit?>">
	   <?
	 db_ancora(@$Lv56_codsit,"js_pesquisav56_codsit(true);","1");
	 ?>
      </td>
      <td> 
  <?
  db_input('v56_codsit',6,$Iv56_codsit,true,'text',"1","onchange='js_pesquisav56_codsit(false);'")
  ?>
	 <?
  db_input('v52_descr',40,$Iv52_descr,true,'text',3,'')
	 ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tv50_data?>">
	 <?=@$Lv50_data?>
      </td>
      <td> 
  <?
  db_inputdata('dataini',"","","",true,'text',$db_opcao,"")
  ?>
        &nbsp;<b>até<b>
  <?
  db_inputdata('datafim',"","","",true,'text',$db_opcao,"")
  ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tv50_advog?>">
	 <?
	 db_ancora(@$Lv50_advog,"js_pesquisav50_advog(true);",$db_opcao);
	 ?>
      </td>
      <td> 
  <?
  db_input('v50_advog',6,$Iv50_advog,true,'text',$db_opcao,"onchange='js_pesquisav50_advog(false);'")
  ?>
	 <?
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
	 ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tv54_codlocal?>">
	 <?
	 db_ancora(@$Lv54_codlocal,"js_pesquisav54_codlocal(true);",$db_opcao);
	 ?>
      </td>
      <td> 
  <?
  db_input('v54_codlocal',6,$Iv54_codlocal,true,'text',$db_opcao,"onchange='js_pesquisav54_codlocal(false);'")
  ?>
	 <?
  db_input('v54_descr',40,$Iv54_descr,true,'text',3,'')
	 ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"> 
         <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" onclick="return js_veri();">
         <input name="testa" type="hidden" id="testa" value="true">
      </td>
    </tr>
    </table>
  <form>
    </td>
    </tr>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>
  <script>
function js_veri(){
  if(document.form1.testa.value=="false"){
    return false;  
  }  
 return true; 
}
function js_pesquisav50_inicial(mostra){
  document.form1.testa.value="false";
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial(chave,erro){
  if(erro==true){
    alert("Inicial Inválida! Verifique.");
    document.form1.v50_inicial.focus(); 
    document.form1.v50_inicial.value=""; 
    document.form1.testa.value="true";
  }else{
    document.form1.testa.value="true";
  }  
}
function js_mostrainicial1(chave1){
    location.href="jur3_emiteinicial021.php?auto=true&pesquisar=true&v50_inicial="+chave1;
}
  function js_pesquisav56_codsit(mostra){
    if(mostra==true){
      db_iframe.jan.location.href = 'func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1';
      db_iframe.mostraMsg();
      db_iframe.show();
      db_iframe.focus();
    }else{
      db_iframe.jan.location.href = 'func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao';
    }
  }
  function js_mostrasituacao(chave,erro){
    document.form1.v52_descr.value = chave; 
    if(erro==true){ 
      document.form1.v56_codsit.focus(); 
      document.form1.v56_codsit.value = ''; 
    }
  }
  function js_mostrasituacao1(chave1,chave2){
    document.form1.v56_codsit.value = chave1;
    document.form1.v52_descr.value = chave2;
    db_iframe.hide();
  }
function js_pesquisav54_codlocal(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_localiza.php?funcao_js=parent.js_mostralocaliza1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_localiza.php?pesquisa_chave='+document.form1.v54_codlocal.value+'&funcao_js=parent.js_mostralocaliza';
  }
}
function js_mostralocaliza(chave,erro){
  document.form1.v54_descr.value = chave; 
  if(erro==true){ 
    document.form1.v54_descr.focus(); 
    document.form1.v54_descr.value = ''; 
  }
}
function js_mostralocaliza1(chave1,chave2){
  document.form1.v54_codlocal.value = chave1;
  document.form1.v54_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav50_advog(mostra){
  if(mostra==true){
      db_iframe.jan.location.href = 'func_advog.php?funcao_js=parent.js_mostracgm1|0|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_advog.php?pesquisa_chave='+document.form1.v50_advog.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.v50_advog.focus(); 
    document.form1.v50_advog.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.v50_advog.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
</script>
  <?
  $func_iframe = new janela('db_iframe','');
  $func_iframe->posX=1;
  $func_iframe->posY=20;
  $func_iframe->largura=780;
  $func_iframe->altura=430;
  $func_iframe->titulo='Pesquisa';
  $func_iframe->iniciarVisivel = false;
  $func_iframe->mostrar();
  if(isset($invalido)){
    db_msgbox("Não existe!");
    
  }
  ?>