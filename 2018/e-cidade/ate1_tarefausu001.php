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
include("libs/smtp.class.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefausu_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefa_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltarefausu   = new cl_tarefausu;
$cltarefaenvol = new cl_tarefaenvol;
$cltarefa      = new cl_tarefa;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
//  $cltarefausu->at42_sequencial = $at42_sequencial;
//  $cltarefausu->at42_tarefa = $at42_tarefa;
  $cltarefausu->at42_tarefa = $at42_tarefa;
  $cltarefausu->at42_usuario = $at42_usuario;
  $cltarefausu->at42_perc = $at42_perc;
}
//die("x: " . ($sqlerro==false?"false":"true"));
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($at42_perc == 0) {
    	$sqlerro  = true;
    	$erro_msg = "Porcentagem deve ser maior que zero!";
    	$cltarefausu->erro_campo = "at42_perc";
    }
	if($sqlerro==false) {
	    $cltarefausu->incluir($at42_sequencial);
	    $erro_msg = $cltarefausu->erro_msg;
	    if($cltarefausu->erro_status==0){
	      $sqlerro=true;
	    }
	    else {
	  	  $cltarefaenvol->at45_tarefa  = $cltarefausu->at42_tarefa;
	  	  $cltarefaenvol->at45_usuario = $cltarefausu->at42_usuario;
	  	  $cltarefaenvol->at45_perc    = $cltarefausu->at42_perc;
	  	  $cltarefaenvol->incluir(null);

// Envio de e-mail para envolvidos
  		  $rs_tarefa = $cltarefa->sql_record($cltarefa->sql_query_envol($cltarefausu->at42_tarefa,"at45_usuario,
                                                                                                   at40_responsavel,
                                                                                                   at40_descr,
                                                                                                   at40_diaini,
                                                                                                   at40_diafim,
                                                                                                   at40_previsao,
                                                                                                   at40_tipoprevisao,
                                                                                                   at40_prioridade,
                                                                                                   at40_obs",
                                                                        "at40_sequencial,at45_usuario","at45_tarefa=$cltarefausu->at42_tarefa"));
		  if($cltarefa->numrows > 0) {
			  $cldb_usuarios = new cl_db_usuarios;
			  	
		  	  for($i=0; $i < $cltarefa->numrows; $i++) {
				  db_fieldsmemory($rs_tarefa,$i);
			  	  $rs_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at45_usuario,"email,nome","id_usuario"));
				  if($cldb_usuarios->numrows > 0) {
					  db_fieldsmemory($rs_usuario,0);
					  if($at40_prioridade == 1) {
					  	  $prioridade = "Baixa";
					  }
					  else if($at40_prioridade == 2) {
					  	  $prioridade = "Media";
					  }
					  else if($at40_prioridade == 3) {
					  	  $prioridade = "Alta";
					  }
				  	  $rs_resp  = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at40_responsavel,"nome as nome_resp","id_usuario"));
					  if($cldb_usuarios->numrows > 0) {	
						  db_fieldsmemory($rs_resp,0);
												  
						  $mensagem = $nome . "<br>Você tem tarefas para fazer abaixo a descricao da sua tarefa (nao autorizada):<br>" .
						              "Responsavel:         " . $at40_responsavel . " - " . $nome_resp . "<br>".
						              "Descricao  :         " . $at40_descr       . "<br>".
						              "Data inicial:        " . db_formatar($at40_diaini,"d") . "  ".
						              "Data final prevista: " . db_formatar($at40_diafim,"d") . "<br>".
						              "Previsto em        : " . $at40_previsao    . "\\" . $at40_tipoprevisao . "<br>" .
						              "Prioridade         : " . $prioridade       . "<br>" .
						              "Obs.:                " . $at40_obs         . "<br>";  
	
//						  $envio = $cltarefa->enviar_email($email,"Tarefa ".$cltarefausu->at42_tarefa,$mensagem);
//						  if($envio == false) {
//						  	  db_msgbox("Erro ao enviar e-mail para " . $email);
//						  }

					  }
				  }
		  	  } 
		  }
// Fim do Envio de e-mail		  

	    }
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($at42_perc == 0) {
    	$sqlerro  = true;
    	$erro_msg = "Porcentagem deve ser maior que zero!";
    	$cltarefausu->erro_campo = "at42_perc";
    }
	if($sqlerro==false) {
	    $cltarefausu->alterar($at42_sequencial);
	    $erro_msg = $cltarefausu->erro_msg;
	    if($cltarefausu->erro_status==0){
	      $sqlerro=true;
	    }
	    else {
			$result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null, "at45_sequencial", null, "at45_tarefa=$at42_tarefa and at45_usuario=$at42_usuant"));
			if ($cltarefaenvol->numrows > 0) {
				db_fieldsmemory($result, 0);
				$cltarefaenvol->at45_usuario    = $at42_usuario;
				$cltarefaenvol->at45_perc       = $at42_perc;
				$cltarefaenvol->at45_sequencial = $at45_sequencial;
				$cltarefaenvol->alterar($at45_sequencial);
				echo "<script>top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at42_tarefa."'</script>";
			}
	    }
	}
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cltarefausu->excluir($at42_sequencial);
    $erro_msg = $cltarefausu->erro_msg;
    if($cltarefausu->erro_status==0){
      $sqlerro=true;
    }
    else {
	  $cltarefaenvol->at45_tarefa  = $at42_tarefa;
  	  $cltarefaenvol->excluir(null,"at45_tarefa=$at42_tarefa and at45_usuario=$at42_usuario");
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cltarefausu->sql_record($cltarefausu->sql_query($at42_sequencial));
   if($result!=false && $cltarefausu->numrows>0){
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
	include("forms/db_frmtarefausu.php");
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
    if($cltarefausu->erro_campo!=""){
        echo "<script> document.form1.".$cltarefausu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cltarefausu->erro_campo.".focus();</script>";
    }
}
?>