<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_libdicionario.php");
include("classes/db_edu_parametros_classe.php");
include("classes/db_db_depart_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cledu_parametros = new cl_edu_parametros;
$cldb_depart = new cl_db_depart;
$db_botao = true;
$escola=  db_getsession("DB_coddepto");
$result = $cledu_parametros->sql_record($cledu_parametros->sql_query("","*","","ed233_i_escola = $escola"));
if($cledu_parametros->numrows!=0){

 db_fieldsmemory($result,0);
 $db_opcao = 2;

}else{
 $db_opcao= 1;
}
if ( isset( $incluir ) ) {
  
  db_inicio_transacao();
  $cledu_parametros->ed233_c_decimais = 'N';
  $db_opcao = 1;
  $cledu_parametros->incluir($ed233_i_codigo);
  db_fim_transacao();
}
if ( isset($alterar) ) {
  
  db_inicio_transacao();
  $db_opcao = 2;
  $cledu_parametros->ed233_c_decimais = isset($ed233_c_decimais) && !empty($ed233_c_decimais)? $ed233_c_decimais : 'N';
  $cledu_parametros->alterar($ed233_i_codigo);
  
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <br>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <?include("forms/db_frmedu_parametros.php");?>
  </td>
 </tr>
</table>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
js_tabulacaoforms("form1","ed233_i_escola",true,1,"ed233_i_escola",true);
</script>
<?
if(isset($incluir)){
  if($cledu_parametros->erro_status=="0"){
    $cledu_parametros->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cledu_parametros->erro_campo!=""){
      echo "<script> document.form1.".$cledu_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cledu_parametros->erro_campo.".focus();</script>";
    }
  }else{
    $cledu_parametros->erro(true,true);
  }
}
if(isset($alterar)){
 if($cledu_parametros->erro_status=="0"){
  $cledu_parametros->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cledu_parametros->erro_campo!=""){
   echo "<script> document.form1.".$cledu_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cledu_parametros->erro_campo.".focus();</script>";
  };
 }else{
  $cledu_parametros->erro(true,false);
  db_redireciona("edu1_edu_parametros001.php");
 }
}
?>