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
require("std/db_stdClass.php");
include("classes/db_matpedidoitem_classe.php");
include("classes/db_matpedido_classe.php");
include("classes/db_db_almox_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatpedidoitem = new cl_matpedidoitem;
$clmatpedido     = new cl_matpedido;
$cldb_almox     = new cl_db_almox;
$db_botao = true;
if(isset($incluir)){
   $sqlerro=false;
   db_inicio_transacao();
	 $result_mat = $clmatpedidoitem->sql_record($clmatpedidoitem->sql_query(null,'*',null,"m98_matpedido = $m97_sequencial and m98_matmater = $m98_matmater "));
	 if ($clmatpedidoitem->numrows>0){	 	 
	  		$erro_msg        = "Material ja incluido nesta solicitação!!";
		  	$m98_matmater    = "";
			$m60_descr       = "";
			$sqlerro         = true;
	}	
  if ($sqlerro==false){     
  	   $clmatpedidoitem->m98_matunid=$codunid;  	   
       $clmatpedidoitem->m98_matpedido=$m97_sequencial;       
       $clmatpedidoitem->incluir(null);              
       $erro_msg=$clmatpedidoitem->erro_msg;
       if ($clmatpedidoitem->erro_status==0){
            $sqlerro=true;
       }
  }

  db_fim_transacao($sqlerro);
}else if (isset($alterar)) {
  
  $sqlerro=false;
  db_inicio_transacao();
  $clmatpedidoitem->m98_matunid=$codunid;
  $clmatpedidoitem->alterar($m98_sequencial);
  $erro_msg=$clmatpedidoitem->erro_msg;
  if ($clmatpedidoitem->erro_status==0){
    $sqlerro=true;
  }
  if ($sqlerro==false){
    $m98_matmater="";
    $m98_obs="";
    $m98_quant="";
    $m60_descr="";
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();  
  $clmatpedidoitem->excluir($m98_sequencial);
  $erro_msg=$clmatpedidoitem->erro_msg;
  if ($clmatpedidoitem->erro_status==0){
    $sqlerro=true;
  }
  if ($sqlerro==false){
    $m98_matmater="";
    $m98_obs="";
    $m98_quant="";
    $m60_descr="";
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br></br>
    <center>
    <fieldset style="width:50%"><legend><b>Itens da Solicitação</b></legend>
	<?
	include("forms/db_frmmatpedidoitem.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
      db_msgbox($erro_msg);
    if($clmatpedidoitem->erro_campo!=""){
      echo "<script> parent.document.form1.".$clmatpedidoitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clmatpedidoitem->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_matpedidoitem.location.href='mat1_matpedidoitem001.php?m97_sequencial=".@$m97_sequencial."&m97_db_almox=".@$m97_db_almox."';\n
	 </script>";

  }
}  
?>