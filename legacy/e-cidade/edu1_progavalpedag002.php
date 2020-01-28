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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_progavalpedag_classe.php");
include("classes/db_questaoaval_classe.php");
include("classes/db_opcaoquestao_classe.php");
include("classes/db_progconfig_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprogavalpedag = new cl_progavalpedag;
$clquestaoaval = new cl_questaoaval;
$clopcaoquestao = new cl_opcaoquestao;
$clprogconfig = new cl_progconfig;
$db_opcao = 22;
$db_opcao1 = 3;
$db_botao = false;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
if(isset($alterar)){
 $db_opcao = 2;
 $db_botao = true;
 for($t=0;$t<$qtdlinha;$t++){
  $ed117_i_questaoaval = "ed117_i_questaoaval".$t;
  $ed117_i_opcaoquestao = "ed117_i_opcaoquestao".$t;
  $ed117_i_codigo = "ed117_i_codigo".$t;
  db_inicio_transacao();
  $clprogavalpedag->ed117_i_questaoaval = $$ed117_i_questaoaval;
  $clprogavalpedag->ed117_i_opcaoquestao = $$ed117_i_opcaoquestao;
  $clprogavalpedag->ed117_i_usuario = db_getsession("DB_id_usuario");
  $clprogavalpedag->ed117_i_codigo = $$ed117_i_codigo;
  $clprogavalpedag->alterar($$ed117_i_codigo);
  db_fim_transacao();
 }
}elseif(isset($ed112_i_rhpessoal)){
 $db_opcao = 2;
 $ed117_c_tipo = trim($ed117_c_tipo)=="AVALIAÇÃO"?"A":"U";
 $result = $clprogavalpedag->sql_record($clprogavalpedag->sql_query("","*",""," ed112_i_rhpessoal = $ed112_i_rhpessoal AND ed117_i_ano = $ed117_i_ano AND ed117_c_tipo = '$ed117_c_tipo'"));
 db_fieldsmemory($result,0);
 $db_botao = true;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Avaliação Pedagógica</b></legend>
    <?include("forms/db_frmprogavalpedag2.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
if(isset($alterar)){
 if($clprogavalpedag->erro_status=="0"){
  $clprogavalpedag->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprogavalpedag->erro_campo!=""){
   echo "<script> document.form1.".$clprogavalpedag->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprogavalpedag->erro_campo.".focus();</script>";
  }
 }else{
  $clprogavalpedag->erro(true,true);
 }
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","ed117_i_progmatricula",true,1,"ed117_i_progmatricula",true);
</script>