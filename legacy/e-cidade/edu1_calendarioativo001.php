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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcalendario = new cl_calendario;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
 $db_opcao = 2;
 $db_botao = true;
 db_inicio_transacao();
 $clcalendario->alterar($ed52_i_codigo);
 db_fim_transacao();
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $result = $clcalendario->sql_record($clcalendario->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;
}
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed52_c_passivo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Ativar / Desativar Calendário</b></legend>
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td nowrap title="<?=@$Ted57_i_calendario?>">
       <?=@$Led57_i_calendario?>
      </td>
      <td>
       <?db_input('ed52_i_codigo',15,@$Ied52_i_codigo,true,'text',3,"")?>
       <?db_input('ed52_c_descr',20,@$Ied52_c_descr,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted52_c_passivo?>">
       <?=@$Led52_c_passivo?>
      </td>
      <td>
       <?
       $x = array('N'=>'NÃO','S'=>'SIM');
       db_select('ed52_c_passivo',$x,true,$db_opcao,"");
       ?>
      </td>
     </tr>
     <tr>
      <td colspan="2" align="center">
       <br>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
       <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisar();">
      </td>
     </tr>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisar(){
 js_OpenJanelaIframe('','db_iframe_calendario','func_calendarioativo.php?funcao_js=parent.js_preenchepesquisa|ed52_i_codigo','Pesquisa Calendários',true);
}
function js_preenchepesquisa(chave){
 db_iframe_calendario.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>
<?
if(isset($alterar)){
 if($clcalendario->erro_status=="0"){
  $clcalendario->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcalendario->erro_campo!=""){
   echo "<script> document.form1.".$clcalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcalendario->erro_campo.".focus();</script>";
  };
 }else{
  $clcalendario->erro(true,true);
 }
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>