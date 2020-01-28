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
include("classes/db_tarefaproced_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefausu_classe.php");
include ("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_tarefa_agenda_classe.php");
include("classes/db_tarefaanexos_classe.php");
include("classes/db_tarefasyscadproced_classe.php");
include("classes/db_tarefa_lanc_classe.php");
$cltarefa         = new cl_tarefa;
$cltarefamodulo   = new cl_tarefamodulo;
$cltarefaproced   = new cl_tarefaproced;
$cltarefasituacao = new cl_tarefasituacao;
$cltarefaitem     = new cl_tarefaitem;
$cltarefaenvol    = new cl_tarefaenvol;
$cltarefausu      = new cl_tarefausu;
$cltarefamotivo   = new cl_tarefamotivo;
$cltarefaclientes = new cl_tarefaclientes;
$cltarefa_agenda  = new cl_tarefa_agenda;
$cltarefaanexos   = new cl_tarefaanexos;
$cltarefasyscadproced = new cl_tarefasyscadproced;
$cltarefa_lanc    = new cl_tarefa_lanc;

db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  
  $cltarefaanexos->at25_tarefa=$at40_sequencial;
  $cltarefaanexos->excluir(null,"at25_tarefa=$at40_sequencial");
  
    if($cltarefaanexos->erro_status==0){
    	  $erro_msg = $cltarefaanexos->erro_msg;
      $sqlerro=true;
  }
  
  $cltarefamodulo->at49_tarefa  = $at40_sequencial;
  $cltarefamodulo->excluir(null,"at49_tarefa=$at40_sequencial");

  $cltarefaproced->at41_tarefa  = $at40_sequencial;
  $cltarefaproced->excluir(null,null,"at41_tarefa=$at40_sequencial");

  $cltarefasituacao->at47_tarefa  = $at40_sequencial;
  $cltarefasituacao->excluir(null,"at47_tarefa=$at40_sequencial");

  $cltarefaitem->at44_tarefa      = $at40_sequencial;
  $cltarefaitem->excluir($at40_sequencial);

  $cltarefaenvol->at45_tarefa     = $at40_sequencial;
  $cltarefaenvol->excluir(null,"at45_tarefa=$at40_sequencial");

  $cltarefamotivo->at55_tarefa    = $at40_sequencial;
  $cltarefamotivo->excluir(null,"at55_tarefa=$at40_sequencial");

  $cltarefaclientes->at70_tarefa    = $at40_sequencial;
  $cltarefaclientes->excluir(null,"at70_tarefa=$at40_sequencial");

  $cltarefausu->at42_tarefa       = $at40_sequencial;
  $cltarefausu->excluir(null,"at42_tarefa=$at40_sequencial");

  $cltarefasyscadproced->at37_tarefa = $at40_sequencial;
  $cltarefasyscadproced->excluir(null,"at37_tarefa=$at40_sequencial");
  
  $cltarefa_lanc->at36_tarefa = $at40_sequencial;
  $cltarefa_lanc->excluir(null,"at36_tarefa=$at40_sequencial");
  
  if($cltarefausu->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cltarefausu->erro_msg; 
  
  if($sqlerro == false) {
  	  $cltarefa_agenda->excluir(null,"at13_tarefa=$at40_sequencial");
  	  
  	  if($cltarefa_agenda->erro_status==0) {
  	  	$sqlerro  = true;
  	  	$erro_msg = $cltarefa_agenda->erro_msg;
  	  }
  }
  
  $cltarefa->excluir($at40_sequencial);
  if($cltarefa->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cltarefa->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cltarefa->sql_record($cltarefa->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null,"*",null,"at49_tarefa=$chavepesquisa")); 
   db_fieldsmemory($result,0);
   $result = $cltarefaproced->sql_record($cltarefaproced->sql_query(null,null,"*",null,"at41_tarefa=$chavepesquisa")); 
   db_fieldsmemory($result,0);
   if(isset($at05_seq) and $at05_seq != "") {
	   $result = $cltarefaitem->sql_record($cltarefaitem->sql_query(null,"*",null,"at44_tarefa=$chavepesquisa")); 
   	   db_fieldsmemory($result,0);
   }
   $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null,"*",null,"at47_tarefa=$chavepesquisa")); 
   db_fieldsmemory($result,0);
   
   $result = $cltarefamotivo->sql_record($cltarefamotivo->sql_query(null,"at55_motivo","at55_tarefa","at55_tarefa=$chavepesquisa"));
   if ($cltarefamotivo->numrows > 0) {
	    db_fieldsmemory($result, 0);
		$at54_sequencial = $at55_motivo;
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
	include("forms/db_frmtarefa.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltarefa->erro_campo!=""){
      echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='ate1_tarefa003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         // parent.document.formaba.tarefausu.disabled=false;
         top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?db_opcaoal=33&at42_tarefa=".@$at40_sequencial."';
     ";
         if(isset($liberaaba)){
//           echo "  parent.mo_camada('tarefausu');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>