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
include("classes/db_sau_atendprestund_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clsau_atendprestund = new cl_sau_atendprestund;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $clsau_atendprestund->incluir($sd48_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
 $db_opcao = 2;
 db_inicio_transacao();
 $clsau_atendprestund->alterar($sd48_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clsau_atendprestund->excluir($sd48_i_codigo);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Atendimento Prestado</b></legend>
    <?include("forms/db_frmsau_atendprestund.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd48_i_unidade",true,1,"sd48_i_unidade",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
 if($clsau_atendprestund->erro_status=="0"){
  $clsau_atendprestund->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clsau_atendprestund->erro_campo!=""){
   echo "<script> document.form1.".$clsau_atendprestund->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clsau_atendprestund->erro_campo.".focus();</script>";
  }
 }else{
  $clsau_atendprestund->erro(true,false);
  db_redireciona("sau1_sau_atendprestund001.php?sd48_i_unidade=$sd48_i_unidade&descrdepto=$descrdepto");
 }
}
if(isset($excluir)){
 if($clsau_atendprestund->erro_status=="0"){
  $clsau_atendprestund->erro(true,false);
 }else{
  $clsau_atendprestund->erro(true,false);
  db_redireciona("sau1_sau_atendprestund001.php?sd48_i_unidade=$sd48_i_unidade&descrdepto=$descrdepto");
 }
}
if(isset($cancelar)){
 db_redireciona("sau1_sau_atendprestund001.php?sd48_i_unidade=$sd48_i_unidade&descrdepto=$descrdepto");
}

?>