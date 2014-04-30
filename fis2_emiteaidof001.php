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
db_postmemory($HTTP_POST_VARS);


$clrotulo = new rotulocampo;
$clrotulo->label("y08_codigo");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  if (document.form1.y08_codigo.value!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe_aidof','func_aidof.php?pesquisa_chave='+document.form1.y08_codigo.value+'&funcao_js=parent.js_testacod','Pesquisa',false);
  }else{
  alert("Campo vazio!!");
  document.form1.y08_codigo.focus();
  }
}
function js_testacod(cod,erro){
  if (erro==true){
    alert("Cod. Inválido!!");
    document.form1.y08_codigo.value="";
    document.form1.y08_codigo.focus();
  }else{
    jan = window.open('fis2_emiteaidof002.php?codaidof='+document.form1.y08_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Ty08_codigo?>">
    <?db_ancora(@$Ly08_codigo,"js_pesquisa_aidof(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("y08_codigo",6,$Iy08_codigo,true,"text",4,"onchange='js_pesquisa_aidof(false);'");
         ?></td>
  </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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

function js_pesquisa_aidof(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aidof','func_aidof.php?funcao_js=parent.js_mostraaidof1|y08_codigo','Pesquisa',true);
  }else{
     if(document.form1.y08_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aidof','func_aidof.php?pesquisa_chave='+document.form1.y08_codigo.value+'&funcao_js=parent.js_mostraaidof','Pesquisa',false);
     }else{
       document.form1.y08_codigo.value = ''; 
     }
  }
}
function js_mostraaidof(chave,erro){
  document.form1.y08_codigo.value = chave; 
  if(erro==true){ 
    document.form1.y08_codigo.value = ''; 
    document.form1.y08_codigo.focus(); 
  }
}
function js_mostraaidof1(chave1){
   document.form1.y08_codigo.value = chave1;  
   db_iframe_aidof.hide();
}
</script>
<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>