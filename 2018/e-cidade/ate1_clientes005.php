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
$clclientes = new cl_clientes;
  /*
$clatendemail = new cl_atendemail;
$clclientesmodulos = new cl_clientesmodulos;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clclientes->alterar($at01_codcli);
  if($clclientes->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clclientes->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
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
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clclientes->erro_campo!=""){
      echo "<script> document.form1.".$clclientes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clclientes->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
      
         var iCodCliente = '".@$at01_codcli."'; 
       
         parent.document.formaba.produtos.disabled        = false;
         parent.document.formaba.contatos.disabled        = false;
         parent.document.formaba.atributos.disabled       = false;
         parent.document.formaba.atendemail.disabled      = false;
         parent.document.formaba.clientesmodulos.disabled = false;
         
         top.corpo.iframe_produtos.location.href          = 'ate1_clientesprodutoscomercial001.php?at91_cliente='+iCodCliente;
         top.corpo.iframe_contatos.location.href          = 'ate1_clientescontato001.php?at92_cliente='+iCodCliente;
         top.corpo.iframe_atributos.location.href         = 'ate1_atributoscliente001.php?at94_cliente='+iCodCliente;         
         top.corpo.iframe_atendemail.location.href        = 'ate1_atendemail001.php?at12_codcli='+iCodCliente;
         top.corpo.iframe_clientesmodulos.location.href = 'ate1_clientesmodulos001.php?at74_codcli='+iCodCliente;
         
     ";
      if(isset($liberaaba)){
        echo "  parent.mo_camada('contatos');";
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