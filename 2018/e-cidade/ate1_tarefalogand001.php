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

define("TAREFA", true);

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltarefa            = new cl_tarefa;
$cltarefalog         = new cl_tarefalog;
$cltarefalogsituacao = new cl_tarefalogsituacao;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  $cltarefalog->at43_tarefa  = $at43_tarefa;
  $cltarefalog->at43_usuario = $at43_usuario;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cltarefalog->incluir($at43_sequencial);
    $erro_msg = $cltarefalog->erro_msg;
    if($cltarefalog->erro_status==0){
      $sqlerro=true;
    }
    else {
  	  $cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
      $cltarefalogsituacao->at48_situacao  = $at48_situacao;
      $cltarefalogsituacao->incluir(null);
	  if($cltarefalogsituacao->erro_status!=0) {
		  $cltarefa->at40_progresso  = $cltarefalog->at43_progresso;
		  $cltarefa->at40_sequencial = $cltarefalog->at43_tarefa;
		  $cltarefa->alterar($cltarefalog->at43_tarefa);
	      if($cltarefa->erro_status!=0) {
			  echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."'</script>";
	      }
	      else {
		      $sqlerro  = true;
	          $erro_msg = $cltarefa->erro_msg;
	      }
	  }
	  else {
	      $sqlerro  = true;
	      $erro_msg = $cltarefalogsituacao->erro_msg;
	  }	
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro == false) {	
  	  db_inicio_transacao();
	  $cltarefalog->alterar($at43_sequencial);

  	  $erro_msg = $cltarefalog->erro_msg;
	  if($cltarefalog->erro_status == 0) {
		  $sqlerro = true;
	  }
  
  	  if($sqlerro == false) {
	      $result = $cltarefalogsituacao->sql_record($cltarefalogsituacao->sql_query(null,"at48_sequencial",null,"at48_tarefalog=$cltarefalog->at43_sequencial"));
   	      if($cltarefalogsituacao->numrows > 0) {
	          db_fieldsmemory($result,0);

	          $cltarefalogsituacao->at48_sequencial = $at48_sequencial;
	  	      $cltarefalogsituacao->at48_tarefalog  = $cltarefalog->at43_sequencial;
      	      $cltarefalogsituacao->at48_situacao   = $at48_situacao;
              $cltarefalogsituacao->alterar($at48_sequencial);

		      if($cltarefalogsituacao->erro_status == 0) {
			      $erro_msg = $cltarefalogsituacao->erro_msg;
			      $sqlerro = true;
		      }
		      else {
				  echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."'</script>";
		      }
          }
  	  } 
  }
  else {
	  echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."'</script>";
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
  
    $cltarefalogsituacao->at48_tarefalog = $at43_sequencial;
    $cltarefalogsituacao->excluir(null,"at48_tarefalog=$at43_sequencial");

    if($cltarefalogsituacao->erro_status==0){
        $sqlerro=true;
	    $erro_msg = $cltarefalogsituacao->erro_msg; 
    } 

    $cltarefalog->excluir($at43_sequencial);
    if($cltarefalog->erro_status==0){
        $sqlerro=true;
	    $erro_msg = $cltarefalog->erro_msg; 
    }
    else {
	  echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."'</script>";
    } 
  	
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cltarefalog->sql_record($cltarefalog->sql_query($at43_sequencial));
   if($result!=false && $cltarefalog->numrows>0){
     db_fieldsmemory($result,0);
   }
   
   $result = $cltarefalogsituacao->sql_record($cltarefalogsituacao->sql_query(null,"*",null,"at48_tarefalog=$at43_sequencial"));
   if($cltarefalogsituacao->numrows > 0) {
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
	include("forms/db_frmtarefalogand.php");
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
    if($cltarefalog->erro_campo!=""){
        echo "<script> document.form1.".$cltarefalog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cltarefalog->erro_campo.".focus();</script>";
    }
}
?>