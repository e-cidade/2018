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
include("classes/db_lote_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cllote = new cl_lote;
$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j34_setor");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_quadra");
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="cad3_consultaitbi001.php">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0">
  <tr>
    <td colspan="2" align="center"><br><br>
      <strong>Consulta ITBI</strong><br><br>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj01_matric?>">
       <?
       db_ancora(@$Lj01_matric,"js_matric(true);",1);
       ?>
    </td>
    <td> 
<?
db_input('j01_matric',10,$Ij01_matric,true,'text',1," onChange='js_matric(false)'");
db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_setor?>">
       <?=$Lj34_setor?>
    </td>
    <td> 
<?
db_input('j34_setor',10,$Ij34_setor,true,'text',$db_opcao);
?>
     <?=$Lj34_quadra?>
<?
db_input('j34_quadra',10,$Ij34_quadra,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr> 
    <td nowrap title="<?=@$Tj14_nome?>"> 
       <?
       db_ancora(@$Lj14_nome,"js_ruas(true);",$db_opcao);
       ?>
    </td>
    <td nowrap> 
      <?
	db_input('j14_codigo',5,$Ij14_codigo,true,'text',$db_opcao," onChange='js_ruas(false)'");
      ?>
      <?
	db_input('j14_nome',40,$Ij14_nome,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> 
      <input name="consultar" type="button" value="Consultar" onClick="js_consultaitbi();">
    </td>
  </tr>  
<script>
function js_limpacampos(){
    document.form1.j01_matric.value = ''; 
    document.form1.z01_nome.value = ''; 
    document.form1.j34_setor.value = ''; 
    document.form1.j34_quadra.value = ''; 
    document.form1.j14_codigo.value = ''; 
    document.form1.j14_nome.value = ''; 
}
onLoad = js_limpacampos();
</script>
  </table>
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_consultaitbi(){
  if(document.form1.j01_matric.value == ""){
    if(document.form1.j34_setor.value == ""){
      alert('preencha a <?=$RLj01_matric?> ou o setor para efetuar a pesquisa');
    }else{
      document.form1.submit();
    }
  }else{
    document.form1.submit();
  }
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','cad3_consultaitbi003.php?j01_matric='+chave,'Pesquisa',true,30);
}
  function js_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubaseitbi.php?funcao_js=parent.js_mostramatric1|j01_matric|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubaseitbi.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostramatric','Pesquisa',false);
  }
}
function js_mostramatric(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
function js_mostramatric1(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_matric.hide();
}
function js_setor(){
  js_OpenJanelaIframe('','db_iframe_setor','func_setor.php?funcao_js=parent.js_mostrasetor|j30_codi','Pesquisa',true,30);
}
function js_mostrasetor(chave1,chave2){
  document.form1.j34_setor.value = chave1;
  db_iframe_setor.hide();
}
function js_ruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruasitbi.php?rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruasitbi.php?rural=1&funcao_js=parent.js_preenchepesquisa1&pesquisa_chave='+document.form1.j14_codigo.value,'Pesquisa',false);
  }
}
function js_preenchepesquisa(chave,chave1){
  document.form1.j14_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preenchepesquisa1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro == true){
    document.form1.j14_codigo.value = '';
    document.form1.j14_codigo.focus();
  }
  db_iframe_ruas.hide();
}
</script>
<?
if(isset($j01_matric) && $j01_matric != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_itbi','cad3_consultaitbi003.php?j01_matric=$j01_matric','Pesquisa',true,30);</script>";
}elseif(isset($j34_setor) && $j34_setor != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_consultaitbi','cad3_consultaitbi002.php?j34_setor=$j34_setor&j14_codigo=$j14_codigo&j34_quadra=$j34_quadra&funcao_js=parent.js_abreconsulta|j01_matric','Pesquisa',true,30);</script>";
}
?>