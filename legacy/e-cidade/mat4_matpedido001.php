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
include("classes/db_matpedido_classe.php");
include("classes/db_matpedidoitem_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matestoqueini_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatpedido = new cl_matpedido;
$clmatpedidoitem = new cl_matpedidoitem;
$cldb_depart = new cl_db_depart;
$cldb_dbalmox = new cl_db_almox;
$cldb_depusu = new cl_db_depusu;
$cldb_usuarios = new cl_db_usuarios;
$db_opcao = 1;
$opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;

  $sqlalmox = $cldb_dbalmox->sql_query_file($m97_db_almox, "*");
  $resalmox = $cldb_dbalmox->sql_record($sqlalmox);
  if($cldb_dbalmox->numrows==0) {
    $sqlerro=true;
    $erro_msg="Departamento $coddepto não é um Almoxarifado!";
  }

  if($sqlerro==false) {   
    $clmatpedido->m97_db_almox = $m97_db_almox;
    $clmatpedido->m97_origem=5;
    $clmatpedido->incluir($m97_sequencial);
    $erro_msg=$clmatpedido->erro_msg;
    if ($clmatpedido->erro_status==0){
      $sqlerro=true;
    }
    $codigo=$clmatpedido->m97_sequencial;
  }

  db_fim_transacao($sqlerro);
}else{
  $m97_data_dia=date('d',db_getsession("DB_datausu"));
  $m97_data_mes=date('m',db_getsession("DB_datausu"));
  $m97_data_ano=date('Y',db_getsession("DB_datausu"));
  $m97_coddepto=db_getsession("DB_coddepto");
  $result_depto=$cldb_depart->sql_record($cldb_depart->sql_query_file($m97_coddepto,'descrdepto'));
  if ($cldb_depart->numrows!=0){
    db_fieldsmemory($result_depto,0);
  }
  $m97_login=db_getsession("DB_id_usuario");
  $result_login=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($m97_login,'nome'));
  if ($cldb_usuarios->numrows!=0){
    db_fieldsmemory($result_login,0);
  }
  $m97_hora=db_hora();
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
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:50%"><legend><b>Solicitação de Transferência</b></legend>
	<?
	include("forms/db_frmmatpedido.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clmatpedido->erro_status=="0"){
    $clmatpedido->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clmatpedido->erro_campo!=""){
      echo "<script> document.form1.".$clmatpedido->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatpedido->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_matpedido.location.href='mat4_matpedido002.php?chavepesquisa=".@$codigo."';\n
	 </script>";
  }
}
?>