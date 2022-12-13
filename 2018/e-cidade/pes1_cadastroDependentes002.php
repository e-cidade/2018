<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_rhdepend_classe.php");
include("classes/db_rhpessoal_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet  = db_utils::postMemory($_GET);


db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clrhdepend = new cl_rhdepend;
$clrhpessoal = new cl_rhpessoal;

$rh31_regist = $oGet->iMatricula;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrhdepend->incluir($rh31_codigo);
  if($clrhdepend->erro_status=="0"){
    $erro_msg = $clrhdepend->erro_msg; 
    $sqlerro = true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrhdepend->alterar($rh31_codigo);
  if($clrhdepend->erro_status=="0"){
    $erro_msg = $clrhdepend->erro_msg; 
    $sqlerro = true;
    $opcao = "alterar";
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrhdepend->excluir($rh31_codigo);
  if($clrhdepend->erro_status=="0"){
    $erro_msg = $clrhdepend->erro_msg; 
    $sqlerro = true;
    $opcao = "excluir";
  }
  db_fim_transacao($sqlerro);
}

if((isset($alterar) || isset($excluir) || isset($incluir)) && $sqlerro == false){
  unset($opcao);
  $rh31_codigo = "";
  $rh31_nome   = "";
  $rh31_dtnasc = "";
  $rh31_dtnasc_dia = "";
  $rh31_dtnasc_mes = "";
  $rh31_dtnasc_ano = "";
  $rh31_gparen = "";
  $rh31_depend = "";
  $rh31_irf    = "";
  $rh31_especi = "";
}

if(isset($opcao)){
  if($opcao=="alterar"){
    $db_opcao = 2;
  }else{
    $db_opcao = 3;
  }
  // echo("<BR><BR>".$clrhdepend->sql_query_cgm($rh31_codigo,"rh31_codigo,rh31_regist,z01_nome,rh31_nome,rh31_dtnasc,rh31_gparen,rh31_depend,rh31_irf,rh31_especi"));
  $result_dados = $clrhdepend->sql_record($clrhdepend->sql_query_cgm($rh31_codigo,"rh31_codigo,rh31_regist,z01_nome,rh31_nome,rh31_dtnasc,rh31_gparen,rh31_depend,rh31_irf,rh31_especi"));
  if($clrhdepend->numrows > 0){
    db_fieldsmemory($result_dados,0);
  }
}else if(isset($rh31_regist) && trim($rh31_regist)!="" ){
  $result_nome = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh31_regist,"z01_nome"));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_nome,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmrhdepend.php");
  ?>
    </center>
  </td>
  </tr>
</table>
<?
/*
if (!$_GET["vmenu"]){
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
           db_getsession("DB_anousu"),db_getsession("DB_instit"));
}   
*/        
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
    echo "<script> document.form1.".$clrhdepend->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clrhdepend->erro_campo.".focus();</script>";
  }
};
?>