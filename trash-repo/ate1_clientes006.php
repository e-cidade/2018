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
include("classes/db_clientes_classe.php");
include("classes/db_atendemail_classe.php");
include("classes/db_clientesmodulos_classe.php");

require_once("classes/db_clienteatributovalor_classe.php");
require_once("classes/db_clientescontato_classe.php");
require_once("classes/db_clientesprodutoscomercial_classe.php");

$clclientes        = new cl_clientes;
$clatendemail      = new cl_atendemail;
$clclientesmodulos = new cl_clientesmodulos;

$clClienteContato           = new cl_clientescontato();
$clClienteAtributoValor     = new cl_clienteatributovalor();
$clClienteProdutosComercial = new cl_clientesprodutoscomercial();

db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  
	$sqlerro=false;
  
	db_inicio_transacao();
  
  $clatendemail->at12_codcli=$at01_codcli;
  $clatendemail->excluir($at01_codcli);

  if($clatendemail->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clatendemail->erro_msg; 
  
  if (!$sqlerro) {
  	
	  $clclientesmodulos->at74_sequencial=$at01_codcli;
	  $clclientesmodulos->excluir($at01_codcli);
	
	  if($clclientesmodulos->erro_status==0){
	    $sqlerro=true;
	  }
	   
	  $erro_msg = $clclientesmodulos->erro_msg;
	  
  }

  if (!$sqlerro) {
  	
	  $clClienteContato->at92_cliente = $at01_codcli;
	  $clClienteContato->excluir(null,"at92_cliente={$at01_codcli}");
	  
	  if ( $clClienteContato->erro_status == 0 ) {
	  	$sqlerro = true;
	  }
	  
	  $erro_msg = $clClienteContato->erro_msg;
  	
  }

  if (!$sqlerro) {
  	
	  $clClienteProdutosComercial->at91_cliente = $at01_codcli;
	  $clClienteProdutosComercial->excluir(null,"at91_cliente={$at01_codcli}");
	  
	  if ( $clClienteProdutosComercial->erro_status == 0 ) {
	    $sqlerro = true;
	  }
	  
	  $erro_msg = $clClienteProdutosComercial->erro_msg;
  	
  }
  

  if (!$sqlerro) {

	  $clClienteAtributoValor->at94_cliente = $at01_codcli;
	  $clClienteAtributoValor->excluir(null,"at94_cliente={$at01_codcli}");
	  
	  if ( $clClienteAtributoValor->erro_status == 0 ) {
	    $sqlerro = true;
	  }
	  
	  $erro_msg = $clClienteAtributoValor->erro_msg;
  	
  }
  
  if (!$sqlerro) {
  	
	  $clclientes->excluir($at01_codcli);
	  
	  if($clclientes->erro_status==0){
	    $sqlerro=true;
	  }
	   
	  $erro_msg = $clclientes->erro_msg;
  	
  }
   
  db_fim_transacao($sqlerro);
  
  $db_opcao = 3;
  $db_botao = true;
  
} else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clclientes->sql_record($clclientes->sql_query($chavepesquisa)); 
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
<table align="center">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmclientes.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clclientes->erro_campo!=""){
      echo "<script> document.form1.".$clclientes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clclientes->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='ate1_clientes003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.atendemail.disabled=false;
         top.corpo.iframe_atendemail.location.href='ate1_atendemail001.php?db_opcaoal=33&at12_codcli=".@$at01_codcli."';
         parent.document.formaba.clientesmodulos.disabled=false;
         top.corpo.iframe_clientesmodulos.location.href='ate1_clientesmodulos001.php?db_opcaoal=33&at74_sequencial=".@$at01_codcli."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('atendemail');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>