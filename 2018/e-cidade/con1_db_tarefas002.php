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
include("classes/db_db_tarefas_classe.php");
include("classes/db_db_tarefasit_classe.php");
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cldb_tarefas = new cl_db_tarefas;
$cldb_tarefasit = new cl_db_tarefasit;
$cldb_usuarios = new cl_db_usuarios;

$db_opcao = 22;
$db_botao = false;

$db79_data = date("Y-m-d",db_getsession("DB_datausu"));
$db79_hora = db_hora();

if(isset($incluir)){
$sqlerro = false;
  db_inicio_transacao();
  
  $clbd_tarefas->db79_id_usuario = $db79_id_usuario;
  $cldb_tarefas->db79_data = $db79_data;  
  $cldb_tarefas->db79_hora = $db79_hora;
  
  $cldb_tarefas->incluir(null);
  if($cldb_tarefas->erro_status==0){
     $sqlerro=true;  
  }
  $erro_msg = $cldb_tarefas->erro_msg;
  db_fim_transacao();
}elseif(isset($alterar)){
  db_inicio_transacao();
     $sqlerro = false;
     $clbd_tarefas->db79_id_usuario = $db79_id_usuario;
     $cldb_tarefas->db79_data = $db79_data;  
     $cldb_tarefas->db79_hora = $db79_hora;
     
     $cldb_tarefas->alterar($db79_codigo);
     $erro_msg = $cldb_tarefas->erro_msg;
     if($cldb_tarefas->erro_status=="0"){
       $sqlerro=true;
     }
 db_fim_transacao($sqlerro); 
}elseif(isset($excluir)){
    $sqlerro=false;
    db_inicio_transacao();
    $clbd_tarefas->db79_codigo = $db79_codigo;
    $cldb_tarefas->db79_data = $db79_data;  
    $cldb_tarefas->db79_hora = $db79_hora;
    $cldb_tarefas->excluir($db79_codigo);
    $erro_msg = $cldb_tarefas->erro_msg;
    if($cldb_tarefas->erro_status=="0"){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
} 
else if(isset($chavepesquisa)){

   $db_opcao = 1;
   $db79_id_usuario = $chavepesquisa;
   $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
      $db_botao = true;
}elseif(isset($opcao) && empty($consultando)){
 $result = $cldb_tarefas->sql_record($cldb_tarefas->sql_query($db79_codigo)); 
 db_fieldsmemory($result,0);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_tarefas.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if( (isset($incluir))||(isset($alterar))||(isset($excluir)) ){
  if($cldb_tarefas->erro_status=="0"){
    $cldb_tarefas->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_tarefas->erro_campo!=""){
      echo "<script> document.form1.".$cldb_tarefas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_tarefas->erro_campo.".focus();</script>";
    } 
  }else{
    db_msgbox($erro_msg);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>