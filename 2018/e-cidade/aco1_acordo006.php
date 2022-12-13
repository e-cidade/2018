<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_acordo_classe.php");
include("classes/db_acordoacordogarantia_classe.php");
include("classes/db_acordoacordopenalidade_classe.php");
include("classes/db_acordoitem_classe.php");
$clacordo = new cl_acordo;
  /*
$clacordoacordogarantia = new cl_acordoacordogarantia;
$clacordoacordopenalidade = new cl_acordoacordopenalidade;
$clacordoitem = new cl_acordoitem;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clacordoacordogarantia->ac12_sequencial=$ac16_sequencial;
  $clacordoacordogarantia->excluir($ac16_sequencial);

  if($clacordoacordogarantia->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clacordoacordogarantia->erro_msg; 
  $clacordoacordopenalidade->ac15_sequencial=$ac16_sequencial;
  $clacordoacordopenalidade->excluir($ac16_sequencial);

  if($clacordoacordopenalidade->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clacordoacordopenalidade->erro_msg; 
  $clacordoitem->ac20_sequencial=$ac16_sequencial;
  $clacordoitem->excluir($ac16_sequencial);

  if($clacordoitem->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clacordoitem->erro_msg; 
  $clacordo->excluir($ac16_sequencial);
  if($clacordo->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clacordo->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clacordo->sql_record($clacordo->sql_query($chavepesquisa)); 
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
	include("forms/db_frmacordo.php");
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
    if($clacordo->erro_campo!=""){
      echo "<script> document.form1.".$clacordo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacordo->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='aco1_acordo003.php';
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
         parent.document.formaba.acordoacordogarantia.disabled=false;
         top.corpo.iframe_acordoacordogarantia.location.href='aco1_acordoacordogarantia001.php?db_opcaoal=33&ac12_sequencial=".@$ac16_sequencial."';
         parent.document.formaba.acordoacordopenalidade.disabled=false;
         top.corpo.iframe_acordoacordopenalidade.location.href='aco1_acordoacordopenalidade001.php?db_opcaoal=33&ac15_sequencial=".@$ac16_sequencial."';
         parent.document.formaba.acordoitem.disabled=false;
         top.corpo.iframe_acordoitem.location.href='aco1_acordoitem001.php?db_opcaoal=33&ac20_sequencial=".@$ac16_sequencial."';
         parent.document.formaba.acordodocumento.disabled=false;
         top.corpo.iframe_acordodocumento.location.href='aco1_acordodocumento001.php?ac40_acordo=".@$chavepesquisa."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('acordoacordogarantia');";
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