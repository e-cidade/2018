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
include("libs/smtp.class.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltarefalog         = new cl_tarefalog;
$cltarefalogsituacao = new cl_tarefalogsituacao;
$cldb_usuarios       = new cl_db_usuarios;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro = false;	
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
      } 
  }
  
  if($sqlerro==false) {
      if(isset($enviar)&&$enviar!="") {
	  	  if($cltarefalog->at43_avisar==3||$cltarefalog->at43_avisar==2) {
	  		  $rs_tarefa = $cltarefa->sql_record($cltarefa->sql_query_envol($cltarefalog->at43_tarefa,"at45_usuario,
	                                                                                                   at40_responsavel,
	                                                                                                   at40_descr,
	                                                                                                   at40_diaini,
	                                                                                                   at40_diafim,
	                                                                                                   at40_previsao,
	                                                                                                   at40_tipoprevisao,
	                                                                                                   at40_prioridade,
	                                                                                                   at40_obs",
	                                                                        "at40_sequencial,at45_usuario","at45_tarefa=$cltarefalog->at43_tarefa"));
			  if($cltarefa->numrows > 0) {
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
						  db_fieldsmemory($rs_resp,0);
						  
						  $mensagem = $nome . "\n     Você tem tarefas para fazer abaixo a descricao da sua tarefa:\n" .
						              "Responsavel:         " . $at40_responsavel . " - " . $nome_resp . "\n".
						              "Descricao  :         " . $at40_descr       . "\n".
						              "Data inicial:        " . db_formatar($at40_diaini,"d") . "  ".
						              "Data final prevista: " . db_formatar($at40_diafim,"d") . "\n".
						              "Previsto em        : " . $at40_previsao    . "\\" . $at40_tipoprevisao . "\n" .
						              "Prioridade         : " . $prioridade       . "\n" .
						              "Obs.:                " . $at40_obs         . "\n";  
	
						  $envio = $cltarefalog->enviar_email($email,"Tarefa ".$cltarefalog->at43_tarefa,$mensagem);
						  if($envio == false) {
						  	  db_msgbox("Erro ao enviar e-mail para " . $email);
						  }
					  }
			  	  } 
			  }
	  	  } 
      }
  }
  
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;

   $result = $cltarefalog->sql_record($cltarefalog->sql_query($chavepesquisa));
   if($cltarefalog->numrows > 0) { 
   	   db_fieldsmemory($result,0);
   }
   
   $result = $cltarefalogsituacao->sql_record($cltarefalogsituacao->sql_query(null,"*",null,"at48_tarefalog=$chavepesquisa"));
   if($cltarefalogsituacao->numrows > 0) {
	   db_fieldsmemory($result,0);
   } 
   
   $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at43_usuario,"nome","id_usuario"));
   if($cldb_usuarios->numrows > 0) {
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
	include("forms/db_frmtarefalog.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($cltarefalog->erro_campo != "") {
			echo "<script> document.form1.".$cltarefalog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$cltarefalog->erro_campo.".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
		echo "<script>top.corpo.iframe_tarefaand.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."'</script>";
	}
/*	
  if($cltarefalog->erro_status=="0"){
    $cltarefalog->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltarefalog->erro_campo!=""){
      echo "<script> document.form1.".$cltarefalog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefalog->erro_campo.".focus();</script>";
    };
  }else{
    $cltarefalog->erro(true,true);
  };
*/  
};
if (isset ($chavepesquisa)) {
	echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tarefalog.disabled=false;
         parent.document.formaba.tarefaclientes.disabled=false;
         parent.document.formaba.tarefausu.disabled=false;
         top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?at43_tarefa=".@$at43_tarefa."&at43_usuario=".@$at43_usuario."';
         top.corpo.iframe_tarefaclientes.location.href='ate1_tarefaclientes001.php?at70_tarefa=".@$at43_tarefa."';
         top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at43_tarefa."'";
	if (isset ($liberaaba)) {
		echo "  parent.mo_camada('tarefalog');";
	}
	echo "}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>