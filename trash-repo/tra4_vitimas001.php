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
include("classes/db_vitimas_acid_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($_GET);
$clvitimas_acid = new cl_vitimas_acid;
$db_opcao = 1;
$db_botao = false;
if(isset($incluir)){
  db_inicio_transacao();
  $clvitimas_acid->incluir($tr10_id);
  db_fim_transacao();
} else if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clvitimas_acid->alterar($tr10_id);
  db_fim_transacao();
} else if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clvitimas_acid->excluir($tr10_id);
  db_fim_transacao();  
}else if(isset($chavepesquisa) || isset($tr10_id)){
   $db_opcao = 2;
   if(@$chavepesquisa == ""){
    $chavepesquisa = $tr10_id;
   }
   $result = $clvitimas_acid->sql_record($clvitimas_acid->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
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
	include("forms/db_frmvitimas_acid.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clvitimas_acid->erro_status=="0"){
    $clvitimas_acid->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clvitimas_acid->erro_campo!=""){
      echo "<script> document.form1.".$clvitimas_acid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvitimas_acid->erro_campo.".focus();</script>";
    };
  }else{
  	$clvitimas_acid->pagina_retorno = "tra4_vitimas001.php?tr10_idacidente={$tr10_idacidente}";
    $clvitimas_acid->erro(true,true);
  };
};
?>