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
include("classes/db_imobil_classe.php");
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$db_opcaom=1;
$db_opcaon=3;
$climobil = new cl_imobil;
$climobil->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_checa(){
  if(document.form1.j44_matric.value==""){
    alert("Informe a matrícula!");
    return false;
  }
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.j44_matric.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="cad4_imobil002.php">
  <tr>
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td nowrap title="<?=@$Tj44_matric?>">
<?
  db_ancora(@$Lj44_matric,"js_pesquisaj44_matric(true);",$db_opcao);
?>
        </td>
        <td> 
<?
  db_input('j44_matric',4,$Ij44_matric,true,'text',$db_opcaom," onchange='js_pesquisaj44_matric(false);'");
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"","z01_nomematri");
?>
        </td>
      </tr>
      </table>
      <input name="entrar" type="submit" id="entrar" value="Entrar" onclick=" return js_checa()" >
      </center>
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaj44_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j44_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j44_matric.focus(); 
    document.form1.j44_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j44_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
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
  echo "<script>alert('Numero de matrícula inválido!')</script>";
}
if(isset($excluir)){
  if($climobil->erro_status=="0"){
    $climobil->erro(true,false);
    if($climobil->erro_campo!=""){
      echo "<script> document.form1.".$climobil->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$climobil->erro_campo.".focus();</script>";
    }
  }else{
    $climobil->erro(true,false);
    db_redireciona("cad4_imobil001.php");
  }
}
if(isset($atualizar)){
  if($climobil->erro_status=="0"){
    $climobil->erro(true,false);
    if($climobil->erro_campo!=""){
      echo "<script> document.form1.".$climobil->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$climobil->erro_campo.".focus();</script>";
    }
  }else{
    $climobil->erro(true,false);
    db_redireciona("cad4_imobil001.php");
  }
}
?>