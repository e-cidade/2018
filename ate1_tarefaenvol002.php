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
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefa_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltarefaenvol   = new cl_tarefaenvol;
$cltarefaenvol = new cl_tarefaenvol;
$cltarefa      = new cl_tarefa;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  $cltarefaenvol->at45_tarefa = $at45_tarefa;
  $cltarefaenvol->at45_usuario = $at45_usuario;
  $cltarefaenvol->at45_perc = $at45_perc;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($at45_perc == 0) {
    	$sqlerro  = true;
    	$erro_msg = "Porcentagem deve ser maior que zero!";
    	$cltarefaenvol->erro_campo = "at45_perc";
    }
	if($sqlerro==false) {
	    $cltarefaenvol->incluir($at45_sequencial);
	    $erro_msg = $cltarefaenvol->erro_msg;
	    if($cltarefaenvol->erro_status==0){
	      $sqlerro=true;
	    }
	}
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($at45_perc == 0) {
    	$sqlerro  = true;
    	$erro_msg = "Porcentagem deve ser maior que zero!";
    	$cltarefaenvol->erro_campo = "at45_perc";
    }
    if($sqlerro==false) {
	$cltarefaenvol->alterar($at45_sequencial);
	$erro_msg = $cltarefaenvol->erro_msg;
	if($cltarefaenvol->erro_status==0){
	  $sqlerro=true;
	} else {
	  $result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null, "at45_sequencial", null, "at45_tarefa=$at45_tarefa and at45_usuario=$at45_usuant"));
	  if ($cltarefaenvol->numrows > 0) {
		  db_fieldsmemory($result, 0);
		  $cltarefaenvol->at45_usuario    = $at45_usuario;
		  $cltarefaenvol->at45_perc       = $at45_perc;
		  $cltarefaenvol->at45_sequencial = $at45_sequencial;
		  $cltarefaenvol->alterar($at45_sequencial);
//		  echo "<script>top.corpo.iframe_tarefaenvol.location.href='ate1_tarefaenvol002.php?at45_tarefa=".@$at45_tarefa."'</script>";
	  }
	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cltarefaenvol->excluir($at45_sequencial);
    $erro_msg = $cltarefaenvol->erro_msg;
    if($cltarefaenvol->erro_status==0){
      $sqlerro=true;
    }
    else {
	  $cltarefaenvol->at45_tarefa  = $at45_tarefa;
  	  $cltarefaenvol->excluir(null,"at45_tarefa=$at45_tarefa and at45_usuario=$at45_usuario");
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query($at45_sequencial));
   if($result!=false && $cltarefaenvol->numrows>0){
     db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcontarefaenvol.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($cltarefaenvol->erro_campo!=""){
        echo "<script> document.form1.".$cltarefaenvol->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cltarefaenvol->erro_campo.".focus();</script>";
    }
}
?>