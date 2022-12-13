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
include("classes/db_progavaladmin_classe.php");
include("classes/db_questaoaval_classe.php");
include("classes/db_opcaoquestao_classe.php");
include("classes/db_progconfig_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprogavaladmin = new cl_progavaladmin;
$clquestaoaval = new cl_questaoaval;
$clopcaoquestao = new cl_opcaoquestao;
$clprogconfig = new cl_progconfig;
$db_opcao = 1;
$db_botao = true;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
if(isset($incluir)){
 for($t=0;$t<$qtdlinha;$t++){
  $ed116_i_questaoaval = "ed116_i_questaoaval".$t;
  $ed116_i_opcaoquestao = "ed116_i_opcaoquestao".$t;
  db_inicio_transacao();
  $clprogavaladmin->ed116_i_questaoaval = $$ed116_i_questaoaval;
  $clprogavaladmin->ed116_i_opcaoquestao = $$ed116_i_opcaoquestao;
  $clprogavaladmin->ed116_i_usuario = db_getsession("DB_id_usuario");
  $clprogavaladmin->incluir(null);
  db_fim_transacao();
 }
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
   <fieldset style="width:95%"><legend><b>Inclusão de Avaliação Administrativa</b></legend>
    <?include("forms/db_frmprogavaladmin.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed116_i_progmatricula",true,1,"ed116_i_progmatricula",true);
</script>
<?
if(isset($incluir)){
 if($clprogavaladmin->erro_status=="0"){
  $clprogavaladmin->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprogavaladmin->erro_campo!=""){
   echo "<script> document.form1.".$clprogavaladmin->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprogavaladmin->erro_campo.".focus();</script>";
  }
 }else{
  $clprogavaladmin->erro(true,true);
 }
}
?>