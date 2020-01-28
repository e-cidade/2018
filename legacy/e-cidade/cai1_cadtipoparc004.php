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
include("classes/db_cadtipoparc_classe.php");
include("classes/db_cadtipoparcdeb_classe.php");
include("classes/db_tipoparc_classe.php");
$clcadtipoparc = new cl_cadtipoparc;
  /*
$clcadtipoparcdeb = new cl_cadtipoparcdeb;
$cltipoparc = new cl_tipoparc;
  */
$clcadtipoparc -> k40_instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  $sqlordem    = "select coalesce( max(k40_ordem),0)+1 as ordem from cadtipoparc";
  $resultordem = pg_query($sqlordem);
  db_fieldsmemory($resultordem,0);
  
  db_inicio_transacao();
  $clcadtipoparc->k40_ordem  = $ordem;
  $clcadtipoparc->k40_instit = db_getsession("DB_instit");
  $clcadtipoparc->k40_db_documento = $db03_docum;
  $clcadtipoparc->incluir($k40_codigo);
  if($clcadtipoparc->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcadtipoparc->erro_msg; 
  db_fim_transacao($sqlerro);
  $k40_codigo= $clcadtipoparc->k40_codigo;
  $db_opcao = 1;
  $db_botao = true;
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
	include("forms/db_frmcadtipoparc.php");
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
    if($clcadtipoparc->erro_campo!=""){
      echo "<script> document.form1.".$clcadtipoparc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadtipoparc->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("cai1_cadtipoparc005.php?liberaaba=true&chavepesquisa=$k40_codigo");
  }
}
?>