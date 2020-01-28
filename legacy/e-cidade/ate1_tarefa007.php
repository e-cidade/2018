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
include("dbforms/db_funcoes.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefamodulo_classe.php");
include("classes/db_tarefaprojeto_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefausu_classe.php");
//include("classes/db_atenditem_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefaenvol_classe.php");
$cltarefa         = new cl_tarefa;
$cltarefamodulo   = new cl_tarefamodulo;
$cltarefaprojeto  = new cl_tarefaprojeto;
$cltarefasituacao = new cl_tarefasituacao;
//$clatenditem      = new cl_atenditem;
$cltarefaitem     = new cl_tarefaitem;
$cltarefausu      = new cl_tarefausu;
$cltarefaenvol    = new cl_tarefaenvol;
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 11;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cltarefa->incluir($at40_sequencial);
  if($cltarefa->erro_status==0){
    $sqlerro=true;
  } 
  else {
	$cltarefamodulo->at49_modulo = $at49_modulo;
  	$cltarefamodulo->at49_tarefa = $cltarefa->at40_sequencial;
  	$cltarefamodulo->incluir(null);

  	$cltarefaprojeto->incluir($cltarefa->at40_sequencial,$at41_projeto);

  	$cltarefasituacao->at47_situacao = $at47_situacao;
  	$cltarefasituacao->at47_tarefa   = $cltarefa->at40_sequencial;
  	$cltarefasituacao->incluir(null);

  	$cltarefaitem->at44_atenditem = $at05_seq;
  	$cltarefaitem->incluir($cltarefa->at40_sequencial);
  	
  	$cltarefausu->at42_tarefa  = $cltarefa->at40_sequencial;
  	$cltarefausu->at42_usuario = $cltarefa->at40_responsavel;
  	$cltarefausu->at42_perc    = $cltarefa->at40_progresso;
  	$cltarefausu->incluir(null);
  	
  	$cltarefaenvol->at45_tarefa  = $cltarefa->at40_sequencial;
  	$cltarefaenvol->at45_usuario = $cltarefa->at40_responsavel;
  	$cltarefaenvol->at45_perc    = $cltarefa->at40_progresso;
  	$cltarefaenvol->incluir(null);
  }
  $erro_msg = $cltarefa->erro_msg; 
  db_fim_transacao($sqlerro);
   $at40_sequencial= $cltarefa->at40_sequencial;
   $db_opcao = 1;
   $db_botao = true;
}
if(isset($at40_sequencial) and $at40_sequencial != "") {
	$result = $cltarefa->sql_record($cltarefa->sql_query($at40_sequencial));
	db_fieldsmemory($result,0);

	$result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null,"*","at49_tarefa","at49_tarefa = $at40_sequencial"));
	db_fieldsmemory($result,0);

	$result = $cltarefaprojeto->sql_record($cltarefaprojeto->sql_query($at40_sequencial,null,"*","at41_tarefa","at41_tarefa = $at40_sequencial"));
	db_fieldsmemory($result,0);

	$result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null,"*","at47_tarefa","at47_tarefa = $at40_sequencial"));
	db_fieldsmemory($result,0);
	
	$db_opcao = 1;
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
	include("forms/db_frmcontarefa.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltarefa->erro_campo!=""){
      echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("ate1_tarefa005.php?liberaaba=true&chavepesquisa=$at40_sequencial");
  }
}
if(isset($db_opcao)&&$db_opcao == 11) {
	echo "<script> js_pesquisa_tarefa(); </script>";
}
?>