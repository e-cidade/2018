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
include("classes/db_ruas_classe.php");
include("classes/db_cargrup_classe.php");
include("classes/db_caracter_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$clruas = new cl_ruas;
$clcaracter = new cl_caracter;
$clcargrup = new cl_cargrup;
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("j14_codigo");
$clrotulo->label("j32_grupo");

if(empty($sqlerro) || $sqlerro=false){
  $load="onLoad='document.form1.j14_codigo.focus();'";
}else{
  $load="";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?=$load?> >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<script>
function js_pesquisar(){
  obj=document.form1;
  if(obj.j14_codigo==""){
    alert("Selecione a rua!")
    return false;
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_carruas','cad4_carruas002.php?j14_codigo='+obj.j14_codigo.value+'&j32_grupo='+obj.j32_grupo.value,'Pesquisa',true);
}
function js_fechar(){
   db_iframe_carruas.hide();
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    
    <td align="center"> 
<table border="0">
  <tr>   
    <td title="<?=$Tj14_nome?>" >
    <?
     db_ancora($Lj14_nome,' js_ruas(true); ',1);
    ?>
    </td>    
    <td title="<?=$Tj14_nome?>" colspan="4">
    <?
     db_input('j14_codigo',5,$Ij14_codigo,true,'text',1,"onchange='js_ruas(false)'");
     db_input('j14_nome',50,$Ij14_nome,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td title="<?=$Tj32_grupo?>">
      <?=$Lj32_grupo?>
    </td>  
    <td title="<?=$Tj32_grupo?>">
    <?
     $result05=$clcargrup->sql_record($clcargrup->sql_query_file("","j32_grupo,j32_descr","","j32_tipo='F'"));
     db_selectrecord("j32_grupo",$result05,true,$db_opcao,"","","","","js_trocar(this);");
    ?> 
    </td>  
  </tr>  
  <tr>
    <td colspan="3" align="center">
       <input name="pesquisar" type="button" id="db_opcao" value="Pesquisar" onclick="js_pesquisar();" >
    </td> 
  </tr>
</table>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_ruas(mostra){
  var rua=document.form1.j14_codigo.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostrarua|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    if(rua!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+rua+'&funcao_js=parent.js_mostrarua1','Pesquisa',false);
    }else{
      document.form1.j14_codigo.value="";
      document.form1.submit();  
    }
  }
}
function js_mostrarua(chave1,chave2){
  document.form1.j14_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.submit(); 
  db_iframe_ruas.hide();
}
function js_mostrarua1(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j14_codigo.focus(); 
    document.form1.j14_codigo.value = ''; 
  }else{
    document.form1.submit();
  }
}
</script>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>