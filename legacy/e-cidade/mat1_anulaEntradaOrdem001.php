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
require("libs/db_liborcamento.php");
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");

$clempempenho = new cl_empempenho;
$clcgm    = new cl_cgm;

$clrotulo = new rotulocampo;
$clcgm->rotulo->label();
$clempempenho->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("m51_codordem");
$clrotulo->label("e69_codnota");
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  if(document.form1.m51_codordem.value != ''){ 
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem_so_depto.php?pesquisa_chave='+document.form1.m51_codordem.value+'&funcao_js=parent.js_mostramatordemtesta','Pesquisa',false);
  }else if (document.form1.e69_codnota.value!=''){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnotaord','func_empnotaord.php?pesquisa_chave='+document.form1.e69_codnota.value+'&funcao_js=parent.js_mostraempnotatesta','Pesquisa',false);
  }
}
function js_mostramatordemtesta(chave,erro){
  if(erro==true){ 
     alert('Cod. Ordem não existe!!');
     document.form1.m51_codordem.value = ''; 
     document.form1.m51_codordem.focus(); 
  }else if (document.form1.e69_codnota.value==''){
     js_OpenJanelaIframe('top.corpo','db_iframe_empnotaord','func_empnotaord.php?chave_m72_codordem='+document.form1.m51_codordem.value+'&funcao_js=parent.js_manda|e69_codnota','Pesquisa',true);
  }else if (document.form1.e69_codnota.value!=''){
     js_OpenJanelaIframe('top.corpo','db_iframe_empnotaord','func_empnotaord.php?pesquisa_chave='+document.form1.e69_codnota.value+'&funcao_js=parent.js_mostraempnotatesta','Pesquisa',false);
  }
}
function js_mostraempnotatesta(chave,erro){
  if(erro==true){ 
    alert("Cod. Nota não existe!!");
    document.form1.e69_codnota.value = ''; 
    document.form1.e69_codnota.focus(); 
  }else{
    location.href='mat1_anulaEntradaOrdem002.php?e69_codnota='+document.form1.e69_codnota.value+'&m51_codordem='+document.form1.m51_codordem.value;                                
  }
}
function js_manda(chave){
  location.href='mat1_anulaEntradaOrdem002.php?e69_codnota='+chave+'&m51_codordem='+document.form1.m51_codordem.value;                                
}
function js_limpa(){
   location.href='mat1_anulaEntradaOrdem001.php'; 
}
  </script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" target="" action="">
<br>
<br>
<table>
  <tr>
     <td>
   <fieldset> 
<table border='0'>
<tr >
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tm51_codordem?>"><?db_ancora(@$Lm51_codordem,"js_pesquisa_matordem(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("m51_codordem",6,$Im51_codordem,true,"text",4,"onchange='js_pesquisa_matordem(false);'");
         ?></td>
  </tr>
  <tr >
    <td  align="left" nowrap title="<?=$Te69_codnota?>"><?db_ancora(@$Le69_codnota,"js_pesquisa_empnota(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("e69_codnota",6,$Ie69_codnota,true,"text",4,"onchange='js_pesquisa_empnota(false);'");
         ?></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="processar" type="button"   value="Processar" onclick='js_emite();' >
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">
  </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>

    <?
       db_input("m51_depto",100,0,true,"hidden",3);
    ?>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisa_matordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem_so_depto.php?funcao_js=parent.js_mostramatordem1|m51_codordem|m51_depto','Pesquisa',true);
  }else{
     if(document.form1.m51_codordem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem_so_depto.php?pesquisa_chave='+document.form1.m51_codordem.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.m51_codordem.value = ''; 
     }
  }
}
function js_mostramatordem(chave,erro){
  if(erro==true){ 
    document.form1.m51_codordem.value = ''; 
    document.form1.m51_codordem.focus(); 
  }
}
function js_mostramatordem1(chave1,chave2){
   document.form1.m51_codordem.value = chave1;  
   document.form1.m51_depto.value = chave2;
   db_iframe_matordem.hide();
}
//--------------------------------
function js_pesquisa_empnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnotaord.php?funcao_js=parent.js_mostraempnota1|e69_codnota','Pesquisa',true);
  }else{
     if(document.form1.e69_codnota.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnotaord.php?pesquisa_chave='+document.form1.e69_codnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }else{
       document.form1.e69_codnota.value = ''; 
     }
  }
}
function js_mostraempnota(chave,erro){
  if(erro==true){ 
    document.form1.e69_codnota.value = ''; 
    document.form1.e69_codnota.focus(); 
  }
}
function js_mostraempnota1(chave1,chave2){
   document.form1.e69_codnota.value = chave1;  
   db_iframe_empnota.hide();
}
//---------------------------------------------------------
</script>
</body>
</html>