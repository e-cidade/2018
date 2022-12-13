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
include("dbforms/db_funcoes.php");
include("classes/db_selecaoponto_classe.php");
include("classes/db_selecaopontorubricas_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clselecaoponto         = new cl_selecaoponto();
$clselecaopontorubricas = new cl_selecaopontorubricas();

$db_opcao = 33;
$db_botao = false;

if( isset($oPost->excluir) ){

  $lSqlErro = false;
  
  db_inicio_transacao();
  
  $clselecaopontorubricas->excluir(null," r73_selecaoponto = {$oPost->r72_sequencial} ");
  
  if($clselecaopontorubricas->erro_status==0){
    $lSqlErro = true;
  }
  
  $sErroMsg = $clselecaopontorubricas->erro_msg;
  
  
  if (!$lSqlErro) {
  
	  $clselecaoponto->r72_sequencial = $oPost->r72_sequencial;
	  $clselecaoponto->excluir($oPost->r72_sequencial);
	  
	  if($clselecaoponto->erro_status==0){
	    $lSqlErro = true;
	  }
	   
	  $sErroMsg = $clselecaoponto->erro_msg;
	  
  }
   
  
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 3;
  $db_botao = true;
  
} else if ( isset($oGet->chavepesquisa) ){
  
   $db_opcao       = 3;
   $db_botao       = true;
   
   $rsSelecaoPonto = $clselecaoponto->sql_record($clselecaoponto->sql_query($oGet->chavepesquisa)); 
   db_fieldsmemory($rsSelecaoPonto,0);
   
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
<table align="center" style="padding-top:15px;"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
			<?
	  	   include("forms/db_frmselecaoponto.php");
			?>
  	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($oPost->excluir)){
	
  db_msgbox($sErroMsg);
  
  if ($lSqlErro) {
  	
    if($clselecaoponto->erro_campo!=""){
      echo "<script> document.form1.".$clselecaoponto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clselecaoponto->erro_campo.".focus();</script>";
    }
    
  } else {
  	
    echo "
				  <script>
				    function js_db_tranca(){
				      parent.location.href='pes1_selecaoponto003.php';
				    }\n
				    js_db_tranca();
				  </script>\n
				";
  }
}
if(isset($oGet->chavepesquisa)){
	
 echo "
		   <script>
		     parent.document.formaba.selecaopontorubricas.disabled=false;
		     top.corpo.iframe_selecaopontorubricas.location.href='pes1_selecaopontorubricas001.php?db_opcaoal=33&r73_sequencial=".@$r72_sequencial."';
		   </script>\n
		 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>