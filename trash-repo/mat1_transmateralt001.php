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
include("classes/db_transmater_classe.php");
include("classes/db_matmater_classe.php");
include("classes/db_pcmater_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cltransmater = new cl_transmater;
$clmatmater   = new cl_matmater;
$clpcmater    = new cl_pcmater;

$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cltransmater->m63_codpcmater=$m63_codpcmater;
  $cltransmater->m63_codmatmater=$m60_codmater;
  $cltransmater->incluir();
  $erro_msg=$cltransmater->erro_msg;
  if ($cltransmater->erro_status==0){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $cltransmater->m63_codmatmater=$m60_codmater;
  $cltransmater->alterar_where(null,"m63_codmatmater=$m60_codmater");
  $erro_msg=$cltransmater->erro_msg;
  if ($cltransmater->erro_status==0){
    $sqlerro=true;
  }
  if ($sqlerro==false){
    $m63_codpcmater  = "";
    $pc01_descrmater = "";
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cltransmater->excluir(null,"m63_codmatmater=$m60_codmater and m63_codpcmater=$m63_codpcmater");
  $erro_msg=$cltransmater->erro_msg;
  if ($cltransmater->erro_status==0){
    $sqlerro=true;
  }
  if ($sqlerro==false){
    $m63_codpcmater="";
  }
  db_fim_transacao($sqlerro);
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
	include("forms/db_frmtransmateralt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
    $cltransmater->erro(true,false);
    if($cltransmater->erro_campo!=""){
      echo "<script> parent.document.form1.".$cltransmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$cltransmater->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_transmater.location.href='mat1_transmateralt001.php?m60_codmater=".@$m60_codmater."';\n
	 </script>";

  }
}  
?>