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
include("classes/db_custocriteriorateio_classe.php");
include("classes/db_custoplanoanaliticacriteriorateio_classe.php");
include("classes/db_custocriteriopcmater_classe.php");
include("classes/db_custocriteriorateiobens_classe.php");
$clcustocriteriorateio = new cl_custocriteriorateio;
  /*
$clcustoplanoanaliticacriteriorateio = new cl_custoplanoanaliticacriteriorateio;
$clcustocriteriopcmater = new cl_custocriteriopcmater;
$clcustocriteriorateiobens = new cl_custocriteriorateiobens;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcustoplanoanaliticacriteriorateio->cc07_sequencial=$cc08_sequencial;
  $clcustoplanoanaliticacriteriorateio->excluir($cc08_sequencial);

  if($clcustoplanoanaliticacriteriorateio->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcustoplanoanaliticacriteriorateio->erro_msg; 
  $clcustocriteriopcmater->cc10_sequencial=$cc08_sequencial;
  $clcustocriteriopcmater->excluir($cc08_sequencial);

  if($clcustocriteriopcmater->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcustocriteriopcmater->erro_msg; 
  $clcustocriteriorateiobens->cc06_sequencial=$cc08_sequencial;
  $clcustocriteriorateiobens->excluir($cc08_sequencial);

  if($clcustocriteriorateiobens->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcustocriteriorateiobens->erro_msg; 
  $clcustocriteriorateio->excluir($cc08_sequencial);
  if($clcustocriteriorateio->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcustocriteriorateio->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clcustocriteriorateio->sql_record($clcustocriteriorateio->sql_query($chavepesquisa)); 
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcustocriteriorateio.php");
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
    if($clcustocriteriorateio->erro_campo!=""){
      echo "<script> document.form1.".$clcustocriteriorateio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcustocriteriorateio->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='Cus1_custocriteriorateio003.php';
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
         parent.document.formaba.custoplanoanaliticacriteriorateio.disabled=false;
         top.corpo.iframe_custoplanoanaliticacriteriorateio.location.href='Cus1_custoplanoanaliticacriteriorateio001.php?db_opcaoal=33&cc07_sequencial=".@$cc08_sequencial."';
         parent.document.formaba.custocriteriopcmater.disabled=false;
         top.corpo.iframe_custocriteriopcmater.location.href='Cus1_custocriteriopcmater001.php?db_opcaoal=33&cc10_sequencial=".@$cc08_sequencial."';
         parent.document.formaba.custocriteriorateiobens.disabled=false;
         top.corpo.iframe_custocriteriorateiobens.location.href='Cus1_custocriteriorateiobens001.php?db_opcaoal=33&cc06_sequencial=".@$cc08_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('custoplanoanaliticacriteriorateio');";
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