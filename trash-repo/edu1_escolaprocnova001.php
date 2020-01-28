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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
include_once ("libs/db_sessoes.php");
include_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
include_once ("classes/db_escolaproc_classe.php");
include_once ("classes/db_censouf_classe.php");
include_once ("classes/db_censomunic_classe.php");
include_once ("classes/db_censodistrito_classe.php");
include_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");

db_postmemory($HTTP_POST_VARS);
$clescolaproc    = new cl_escolaproc;
$clcensouf 			 = new cl_censouf;
$clcensomunic    = new cl_censomunic;
$clcensodistrito = new cl_censodistrito;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
 db_inicio_transacao();

 if (trim($ed82_pais) != 10) {
 		
 	$clescolaproc->ed82_i_censouf       = "null";
 	$clescolaproc->ed82_i_censomunic    = "null";
 	$clescolaproc->ed82_i_censodistrito = "null";
 }
 
 $clescolaproc->incluir($ed82_i_codigo);
 db_fim_transacao();
}

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  
  if (trim($ed82_pais) != 10) {
  	 
  	$clescolaproc->ed82_i_censouf       = "null";
  	$clescolaproc->ed82_i_censomunic    = "null";
  	$clescolaproc->ed82_i_censodistrito = "null";
  }
  
  $clescolaproc->alterar($ed82_i_codigo);
  db_fim_transacao();
}

if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clescolaproc->excluir($ed82_i_codigo);
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Cadastro de Escolas de Procedência de Alunos</b></legend>
    <?include("forms/db_frmescolaprocnova.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed82_c_nome",true,1,"ed82_c_nome",true);
</script>
<?
if(isset($incluir)){
 if($clescolaproc->erro_status=="0"){
  $clescolaproc->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clescolaproc->erro_campo!=""){
   echo "<script> document.form1.".$clescolaproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescolaproc->erro_campo.".focus();</script>";
  }
 }else{
  $clescolaproc->erro(true,true);
 }
}



if(isset($alterar)){
 if($clescolaproc->erro_status=="0"){
  $clescolaproc->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clescolaproc->erro_campo!=""){
   echo "<script> document.form1.".$clescolaproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescolaproc->erro_campo.".focus();</script>";
  }
 }else{
  $clescolaproc->erro(true,true);
 }
}
if(isset($excluir)){
 if($clescolaproc->erro_status=="0"){
  $clescolaproc->erro(true,false);
 }else{
  $clescolaproc->erro(true,true);
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clescolaproc->pagina_retorno."'</script>";
}
?>