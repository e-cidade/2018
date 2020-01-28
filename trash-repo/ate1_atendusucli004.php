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
include("classes/db_atendusucli_classe.php");
include("classes/db_db_usuarios_classe.php");
$clatendusucli = new cl_atendusucli;
$cldb_usuarios = new cl_db_usuarios;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
$sqlerro=false;
if(isset($incluir)){
  db_inicio_transacao();
  $clatendusucli->incluir($at80_codatendcli);
  if($clatendusucli->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clatendusucli->erro_msg; 
  db_fim_transacao($sqlerro);
  $at80_codatendcli= $clatendusucli->at80_codatendcli;
  $db_opcao = 1;
  $db_botao = true;
}else{
  $result_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession("DB_id_usuario"),"id_usuario as at80_id_usuario, nome"));
  if($cldb_usuarios->numrows > 0){
    db_fieldsmemory($result_usuario, 0);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    include("forms/db_frmatendusucli.php");
    ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","at80_id_usuario",true,1,"at80_id_usuario",true);
</script>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clatendusucli->erro_campo!=""){
      echo "<script> document.form1.".$clatendusucli->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatendusucli->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    db_redireciona("ate1_atendusucli005.php?liberaaba=true&chavepesquisa=$at80_codatendcli");
  }
}
?>