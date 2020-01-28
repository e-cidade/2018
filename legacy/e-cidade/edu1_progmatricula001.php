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
include("classes/db_proginterrompe_classe.php");
include("classes/db_progmatricula_classe.php");
include("classes/db_progconfig_classe.php");
include("classes/db_progantig_classe.php");
include("classes/db_progconvocacao_classe.php");
include("classes/db_progconvocacaores_classe.php");
include("classes/db_progavaladmin_classe.php");
include("classes/db_progavalpedag_classe.php");
include("classes/db_progconhec_classe.php");
include("classes/db_proglicencamatr_classe.php");
include("classes/db_convocacao_classe.php");
include("classes/db_opcaoquestao_classe.php");
include("classes/db_progclasse_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clproginterrompe = new cl_proginterrompe;
$clprogmatricula = new cl_progmatricula;
$clprogconfig = new cl_progconfig;
$clprogantig = new cl_progantig;
$clprogconvocacao = new cl_progconvocacao;
$clprogconvocacaores = new cl_progconvocacaores;
$clprogavaladmin = new cl_progavaladmin;
$clprogavalpedag = new cl_progavalpedag;
$clprogconhec = new cl_progconhec;
$clproglicencamatr = new cl_proglicencamatr;
$clconvocacao = new cl_convocacao;
$clopcaoquestao = new cl_opcaoquestao;
$clprogclasse = new cl_progclasse;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
if(isset($incluir)){
 $result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","ed112_i_codigo as nada",""," ed112_i_rhpessoal = $ed112_i_rhpessoal AND ed112_c_situacao = 'A'"));
 if($clprogmatricula->numrows>0){
  db_fieldsmemory($result,0);
  db_msgbox("Matrícula já incluída na Progressão!");
  db_redireciona("edu1_progmatricula002.php?chavepesquisa=$nada");
 }else{
  db_inicio_transacao();
  $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
  $clprogmatricula->ed112_c_situacao = "A";
  $clprogmatricula->incluir($ed112_i_codigo);
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
   <fieldset style="width:95%"><legend><b>Inclusão de Matrícula na Progressão</b></legend>
    <?include("forms/db_frmprogmatricula.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed112_i_rhpessoal",true,1,"ed112_i_rhpessoal",true);
</script>
<?
if(isset($incluir)){
 if($clprogmatricula->erro_status=="0"){
  $clprogmatricula->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprogmatricula->erro_campo!=""){
   echo "<script> document.form1.".$clprogmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprogmatricula->erro_campo.".focus();</script>";
  }
 }else{
  $clprogmatricula->erro(true,true);
 }
}
?>