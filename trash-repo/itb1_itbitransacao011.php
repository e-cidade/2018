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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_itbitransacao_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$clitbitransacao = new cl_itbitransacao;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;

if(isset($oPost->incluir)){
	
  db_inicio_transacao();
  
  $clitbitransacao->it04_descr      = $oPost->it04_descr;
  $clitbitransacao->it04_desconto   = (!isset($oPost->it04_desconto) || trim($oPost->it04_desconto) == ""?"0":$oPost->it04_desconto);
  
  if (isset($oPost->it04_datalimite_dia) && trim($oPost->it04_datalimite_dia) != ""){ 
    $clitbitransacao->it04_datalimite = $oPost->it04_datalimite_ano."-".$oPost->it04_datalimite_mes."-".$oPost->it04_datalimite_dia;
  } else {
  	$clitbitransacao->it04_datalimite = null;
  }
  
  $clitbitransacao->it04_obs	    = $oPost->it04_obs;
  $clitbitransacao->incluir($oPost->it04_codigo);

  if ( $clitbitransacao->erro_status == 0){
  	$lSqlErro = true;
  }

  $sErroMsg = $clitbitransacao->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
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
	include("forms/db_frmitbitransacao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","it04_descr",true,1,"it04_descr",true);
</script>
<?

if(isset($oPost->incluir)){
	
  if ($lSqlErro){
  	
    $clitbitransacao->erro(true,false);
    $db_botao=true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if($clitbitransacao->erro_campo!=""){
      echo "<script> document.form1.".$clitbitransacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbitransacao->erro_campo.".focus();</script> ";
    } 
    
  }else{
  	
    db_msgbox($sErroMsg);
    
    echo " <script> 																							  	      ";
    echo "   parent.iframe_formapgto.location.href='itb1_itbitransacao111.php?codTransacao={$clitbitransacao->it04_codigo}';	  ";
	echo "   parent.document.formaba.formapgto.disabled = false; 												  	   	  ";
	echo "   parent.mo_camada('formapgto');																	   		   	  ";
    echo "   parent.iframe_dados.location.href='itb1_itbitransacao012.php?chavepesquisa={$clitbitransacao->it04_codigo}'; ";
	echo " </script>												  										  	          ";
	
	
  }
}




?>