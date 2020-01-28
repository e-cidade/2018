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
include("classes/db_db_versaocpd_classe.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaocpdarq_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);
$cldb_versaocpd    = new cl_db_versaocpd;
$cldb_versaocpdarq = new cl_db_versaocpdarq;
$cldb_versao       = new cl_db_versao;
$resultversao = $cldb_versao->sql_record($cldb_versao->sql_query_file($db33_codver));
db_fieldsmemory($resultversao,0);
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
//$cldb_versaocpd->db33_codcpd = $db33_codcpd;
//$cldb_versaocpd->db33_codver = $db33_codver;
//$cldb_versaocpd->db33_obs = $db33_obs;
//$cldb_versaocpd->db33_obscpd = $db33_obscpd;
//$cldb_versaocpd->db33_data = $db33_data;
}
if(isset($incluir)){
  if($sqlerro==false){
    $cldb_versaocpd->db33_codver = $db30_codver;
    db_inicio_transacao();
    $cldb_versaocpd->incluir($db33_codcpd);
    $erro_msg = $cldb_versaocpd->erro_msg;
    if($cldb_versaocpd->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_versaocpd->alterar($db33_codcpd);
    $erro_msg = $cldb_versaocpd->erro_msg;
    if($cldb_versaocpd->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_versaocpd->excluir($db33_codcpd);
    $erro_msg = $cldb_versaocpd->erro_msg;
    if($cldb_versaocpd->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cldb_versaocpd->sql_record($cldb_versaocpd->sql_query($db33_codcpd));
   if($result!=false && $cldb_versaocpd->numrows>0){
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_versaocpd.php");
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
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clpagordemrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpagordemrec->erro_campo.".focus();</script>";
    }
}
?>