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
include("classes/db_basempd_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clbasempd = new cl_basempd;
$db_opcao = 1;
$db_botao = true;
$result1 = $clbasempd->sql_record($clbasempd->sql_query("","ed35_i_disciplina as discjacad",""," ed35_i_base = $ed35_i_base"));
if($clbasempd->numrows>0){
 $sep = "";
 $disc_cad = "";
 for($c=0;$c<$clbasempd->numrows;$c++){
  db_fieldsmemory($result1,$c);
  $disc_cad .= $sep.$discjacad;
  $sep = ",";
 }
}else{
 $disc_cad = 0;
}
if(isset($incluir)){
 db_inicio_transacao();
 $clbasempd->incluir($ed35_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clbasempd->alterar($ed35_i_codigo);
  db_fim_transacao();
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clbasempd->excluir($ed35_i_codigo);
  db_fim_transacao();
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:90%"><legend><b>Disciplinas da Base Curricular <?=@$ed31_c_descr?></b></legend>
    <?include("forms/db_frmbasempd.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar)){
 if($clbasempd->erro_status=="0"){
  $clbasempd->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbasempd->erro_campo!=""){
   echo "<script> document.form1.".$clbasempd->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbasempd->erro_campo.".focus();</script>";
  };
 }else{
  $clbasempd->erro(true,true);
 };
};
if(isset($excluir)){
  if($clbasempd->erro_status=="0"){
    $clbasempd->erro(true,false);
  }else{
    $clbasempd->erro(true,true);
  };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clbasempd->pagina_retorno."'</script>";
}
?>