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
include("classes/db_transfmarca_classe.php");
include("classes/db_cgm_classe.php");
$cltransfmarca = new cl_transfmarca;
$cltransfmarca->rotulo->label('ma02_i_marca');
$clcgm = new cl_cgm;
$clcgm->rotulo->label('z01_numcgm');
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
<br>
<form name="form1" method="post">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
     <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
     <td nowrap><b>Escolha o período:</b></td>
     <td nowrap>&nbsp;&nbsp;&nbsp;<b>Opção:</b></td>
    </tr>
    <tr>
     <td>
      De:
      <?db_inputdata('data_ini',@$ma01_d_data_dia,@$ma01_d_data_mes,@$ma01_d_data_ano,true,'text',1,"")?>
      Até:
      <?db_inputdata('data_fim',@$ma01_d_data_dia,@$ma01_d_data_mes,@$ma01_d_data_ano,true,'text',1,"")?>
     </td>
     <td>
      &nbsp;&nbsp;&nbsp;
      <?
      $tipo = array("Todas"=>"Todas","Por Marca"=>"Por Marca","Por CGM"=>"Por CGM");
      db_select("escolha",$tipo,true,2,"onchange=\"js_opcao(this.value)\"");
      ?>
     </td>
    </tr>
     <td colspan="2" align="center">
      <span name="marcas" id="marcas" style="visibility:hidden">
       <?db_ancora(@$Lma02_i_marca,"js_pesquisama02_i_marca(true);",1);?>
       <?db_input('ma02_i_marca',10,'',true,'text',1," onchange='js_pesquisama02_i_marca(false);'")?>
       <?db_input('z01_nome1',40,'',true,'text',3,'')?>
      </span><br>
      <span name="prop" id="prop" style="visibility:hidden">
       <?db_ancora(@$Lz01_numcgm,"js_pesquisama01_i_cgm(true);",1);?>
       <?db_input('z01_numcgm',10,'',true,'text',1," onchange='js_pesquisama01_i_cgm(false);'")?>
       <?db_input('z01_nome',40,'',true,'text',3,'')?>
      </span>
     </td>
    </tr>
    <tr>
     <td align="center" colspan="2"><br>
      <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();">
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisa(){
 elementos = document.form1.elements.length-5;
 branco = "";
 for(i=0;i<elementos;i++){
  if(document.form1[i].value==""){
   alert("Campo não informado");
   document.form1[i].focus();
   document.form1[i].style.backgroundColor="#99A9AE";
   branco = "sim";
   break;
  }
 }
 if(branco==""){
  if(document.form1.escolha.value=="Todas"){
    jan = window.open('mar2_transferencia002.php?escolha='+document.form1.escolha.value+'&data_ini='+document.form1.data_ini_ano.value+'-'+document.form1.data_ini_mes.value+'-'+document.form1.data_ini_dia.value+'&data_fim='+document.form1.data_fim_ano.value+'-'+document.form1.data_fim_mes.value+'-'+document.form1.data_fim_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }else if(document.form1.escolha.value=="Por Marca"){
    if(document.form1.ma02_i_marca.value==""){
     alert("Selecione uma marca para pesquisa");
     document.form1.ma02_i_marca.focus();
     document.form1.ma02_i_marca.style.backgroundColor="#99A9AE";
    }else{
     jan = window.open('mar2_transferencia002.php?marca='+document.form1.ma02_i_marca.value+'&escolha='+document.form1.escolha.value+'&data_ini='+document.form1.data_ini_ano.value+'-'+document.form1.data_ini_mes.value+'-'+document.form1.data_ini_dia.value+'&data_fim='+document.form1.data_fim_ano.value+'-'+document.form1.data_fim_mes.value+'-'+document.form1.data_fim_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
    }
  }else{
    if(document.form1.z01_numcgm.value==""){
     alert("Selecione uma CGM para pesquisa");
     document.form1.z01_numcgm.focus();
     document.form1.z01_numcgm.style.backgroundColor="#99A9AE";
    }else{
     jan = window.open('mar2_transferencia002.php?cgm='+document.form1.z01_numcgm.value+'&escolha='+document.form1.escolha.value+'&data_ini='+document.form1.data_ini_ano.value+'-'+document.form1.data_ini_mes.value+'-'+document.form1.data_ini_dia.value+'&data_fim='+document.form1.data_fim_ano.value+'-'+document.form1.data_fim_mes.value+'-'+document.form1.data_fim_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
    }
  }
 }
}
function js_pesquisama02_i_marca(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_constransf.php?marca&funcao_js=parent.js_mostramarca1|ma02_i_marca|z01_nome','Pesquisa',true);
 }else{
  if(document.form1.ma02_i_marca.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_constransf.php?marca&pesquisa_chave='+document.form1.ma02_i_marca.value+'&funcao_js=parent.js_mostramarca','Pesquisa',false);
  }else{
   document.form1.ma02_i_marca.value = '';
  }
 }
}
function js_mostramarca(chave,erro){
 document.form1.z01_nome1.value = chave;
 if(erro==true){
  document.form1.ma02_i_marca.focus();
  document.form1.ma02_i_marca.value = '';
 }
}
function js_mostramarca1(chave1,chave2){
 document.form1.ma02_i_marca.value = chave1;
 document.form1.z01_nome1.value = chave2;
 db_iframe_marca.hide();
}
function js_pesquisama01_i_cgm(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_constransf.php?cgm&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
 }else{
  if(document.form1.z01_numcgm.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_constransf.php?cgm&pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }else{
   document.form1.z01_numcgm.value = '';
  }
 }
}
function js_mostracgm(chave,erro){
 document.form1.z01_nome.value = chave;
 if(erro==true){
  document.form1.z01_numcgm.focus();
  document.form1.z01_numcgm.value = '';
 }
}
function js_mostracgm1(chave1,chave2){
 document.form1.z01_numcgm.value = chave1;
 document.form1.z01_nome.value = chave2;
 db_iframe_marca.hide();
}
function js_opcao(valor){
 if(valor=="Por Marca"){
  document.getElementById('marcas').style.visibility = 'visible';
  document.getElementById('prop').style.visibility = 'hidden';
  document.form1.ma02_i_marca.value = "";
  document.form1.z01_nome1.value = "";
  document.form1.ma02_i_marca.focus();
 }else if(valor=="Por CGM"){
  document.getElementById('prop').style.visibility = 'visible';
  document.getElementById('marcas').style.visibility = 'hidden';
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = "";
  document.form1.ma01_i_cgm.focus();
 }else{
  document.getElementById('marcas').style.visibility = 'hidden';
  document.getElementById('prop').style.visibility = 'hidden';
 }
}
</script>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>