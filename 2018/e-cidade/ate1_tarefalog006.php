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
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltarefalog         = new cl_tarefalog;
$cltarefalogsituacao = new cl_tarefalogsituacao;
$cldb_usuarios       = new cl_db_usuarios;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  $sqlerro  = false;
  db_inicio_transacao();
  $cltarefalogsituacao->at48_tarefalog = $at43_sequencial;
  $cltarefalogsituacao->excluir(null,"at48_tarefalog=$at43_sequencial");

  if($cltarefalogsituacao->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cltarefalogsituacao->erro_msg; 

  $cltarefalog->excluir($at43_sequencial);
  if($cltarefalog->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cltarefalog->erro_msg; 

  db_fim_transacao($sqlerro);
  
  $db_opcao = 3;
  $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
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
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltarefalog->erro_campo!=""){
      echo "<script> document.form1.".$cltarefalog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefalog->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='ate1_tarefalog003.php';
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
         // parent.document.formaba.tarefalog.disabled=false;
         top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand001.php?db_opcaoal=33&at40_sequencial=".@$at40_sequencial."&at43_usuario=".@$at43_usuario."';
     ";
         if(isset($liberaaba)){
//           echo "  parent.mo_camada('tarefalog');";
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