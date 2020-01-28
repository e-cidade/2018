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
include("classes/db_editalrua_classe.php");
include("classes/db_editalserv_classe.php");
include("classes/db_contlot_classe.php");
include("classes/db_iptubase_classe.php");
$cleditalserv= new cl_editalserv;
$cleditalrua = new cl_editalrua;
$clcontlot = new cl_contlot;
$cliptubase = new cl_iptubase;
$clrotulo = new rotulocampo;
$clrotulo->label("d01_codedi");
$clrotulo->label("d02_contri");
$clrotulo->label("d04_tipos");
$clrotulo->label("d03_descr");
$clrotulo->label("d02_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("d40_codigo");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_consultar(){
  var obj=document.form1;
  var matric=new Number(obj.j01_matric.value);     
  if(!isNaN(matric) &&  matric!=""){
    document.form1.action="con3_conscontri002.php";
    document.form1.submit();
    return true;
  }
  var rua=new Number(obj.d02_codigo.value);     
  if(!isNaN(rua) &&  rua!=""){
    document.form1.action="con3_conscontri003.php";
    document.form1.submit();
    return true;
  }
  var tipos=new Number(obj.d04_tipos.value);     
  if(!isNaN(tipos) &&  tipos!=""){
    document.form1.action="con3_conscontri004.php";
    document.form1.submit();
    return true;
  }
  var edi=new Number(obj.d01_codedi.value);     
  if(!isNaN(edi) &&  edi!=""){
    document.form1.action="con3_conscontri005.php";
    document.form1.submit();
    return true;
  }
  var contri=new Number(obj.d02_contri.value);     
  if(!isNaN(contri) && contri!=""){
    document.form1.action="con3_conscontri006.php";
    document.form1.submit();
    return true;
  }
  var lista=new Number(obj.d40_codigo.value);     
  if(!isNaN(lista) &&  lista!=""){
    document.form1.action="con3_conscontri007.php";
    document.form1.submit();
    return true;
  }
}  
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.j01_matric.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<br>
    <center>
    <form name="form1" method="post" action="">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td>     
<?
db_ancora($Lj01_matric,' js_matri(true); ',1);
?>
        </td>
        <td>
<?
  db_input('j01_matric',6,0,true,'text',1,"onchange='js_matri(false)'");
  db_input('z01_nome',40,0,true,'text',3,"","z01_nome_matric");
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Td02_codigo?>">
        <?
        db_ancora(@$Ld02_codigo,"js_pesquisad02_codigo(true);",$db_opcao);
        ?>
        </td>
        <td> 
<?
db_input('d02_codigo',6,$Id02_codigo,true,'text',$db_opcao," onchange='js_pesquisad02_codigo(false);'");
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Td04_tipos?>">
         <?
         db_ancora(@$Ld04_tipos,"js_pesquisad04_tipos(true);",$db_opcao);
          ?>
        </td>
        <td> 
<?
db_input('d04_tipos',6,$Id04_tipos,true,'text',$db_opcao," onchange='js_pesquisad04_tipos(false);'");
db_input('d03_descr',40,$Id03_descr,true,'text',3,'');
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Td01_codedi?>">
        <?
          db_ancora(@$Ld01_codedi,"js_edi(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('d01_codedi',6,$Id01_codedi,true,'text',$db_opcao," onchange='js_edi(false);'");
         ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Td02_contri?>">
        <?
          db_ancora(@$Ld02_contri,"js_contri(true);",$db_opcao);
        ?>
        </td>
        <td> 
        <?
          db_input('d02_contri',6,$Id02_contri,true,'text',$db_opcao," onchange='js_contri(false);'");
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Td40_codigo?>">
	<?
         db_ancora(@$Ld40_codigo,"js_lista(true);",$db_opcao);
        ?>	 
        </td>
        <td> 
  <?
  db_input('d40_codigo',6,$Id40_codigo,true,'text',$db_opcao,"onchange='js_lista(false);'")
  ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"   height="25" align="center">
  <?
  $consultar="Consultar";
  db_input('consultar',6,0,true,'button',$db_opcao,"onClick='js_consultar();'")
  ?>
	</td>
      </tr>
    </table>
    </form>
    </center>
    </td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?funcao_js=parent.js_mostracontri1|d02_contri','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    alert("Contribuição inválida.");
    document.form1.d02_contri.focus(); 
  }  
}
function js_mostracontri1(chave1){
  document.form1.d02_contri.value = chave1;
  db_iframe.hide();
}
function js_edi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_edital.php?funcao_js=parent.js_mostraedi1|d01_codedi','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_edital.php?pesquisa_chave='+document.form1.d01_codedi.value+'&funcao_js=parent.js_mostraedi','Pesquisa',false);
  }
}
function js_mostraedi(chave,erro){
  if(erro==true){ 
    alert("Edital inválido.");
    document.form1.d01_codedi.focus(); 
  }  
}
function js_mostraedi1(chave1){
  document.form1.d01_codedi.value = chave1;
  db_iframe.hide();
}
function js_pesquisad02_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_ruas.php?funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_ruas.php?pesquisa_chave='+document.form1.d02_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.d02_codigo.focus(); 
    document.form1.d02_codigo.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.d02_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisad04_tipos(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editaltipo.php?funcao_js=parent.js_mostraeditaltipo1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editaltipo.php?pesquisa_chave='+document.form1.d04_tipos.value+'&funcao_js=parent.js_mostraeditaltipo','Pesquisa',false);
  }
}
function js_mostraeditaltipo(chave,erro){
  document.form1.d03_descr.value = chave; 
  if(erro==true){ 
    document.form1.d04_tipos.focus(); 
    document.form1.d04_tipos.value = ''; 
  }
}
function js_mostraeditaltipo1(chave1,chave2){
  document.form1.d04_tipos.value = chave1;
  document.form1.d03_descr.value = chave2;
  db_iframe.hide();
}
function js_lista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?funcao_js=parent.js_mostraproj1|d40_codigo','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?pesquisa_chave='+document.form1.d40_codigo.value+'&funcao_js=parent.js_mostraproj','Pesquisa',false);
  }
}
function js_mostraproj(chave,erro){
  if(erro==true){ 
    alert("Lista inválida.");
    document.form1.d40_codigo.focus(); 
  }  
}
function js_mostraproj1(chave1){
  document.form1.d40_codigo.value = chave1;
  db_iframe.hide();
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?funcao_js=parent.js_mostra|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nome_matric.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome_matric.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
</script>
<?
if(isset($rua) && $rua=="false"){
  db_msgbox("Não há contribuição de melhoria para a rua informada.");
}
if(isset($matric) && $matric=="false"){
  db_msgbox("Não há contribuição de melhoria para a matricula informada.");
}
if(isset($tipos) && $tipos=="false"){
  db_msgbox("Não há contribuição de melhoria para o tipo de serviço informado.");
}
if(isset($edital) && $edital=="false"){
  db_msgbox("Não há contribuição de melhoria para o edital informado.");
}
if(isset($contri) && $contri=="false"){
  db_msgbox("Não existe esta contribuição de melhoria.");
}
if(isset($lista) && $lista=="false"){
  db_msgbox("Não há contribuição de melhoria para a lista informada.");
}
?>