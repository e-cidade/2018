<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
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
   $db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clacordo->alterar($ac16_sequencial);
  if($clacordo->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clacordo->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)) {
   $db_opcao = 2;
   $db_botao = true;
   //$result = $clacordo->sql_record($clacordo->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
}
unset($_SESSION["oContrato"]);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, contratos.classe.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmacordo.php");
	?>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clacordo->erro_campo!=""){
      echo "<script> document.form1.".$clacordo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacordo->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera() {
      
         parent.document.formaba.acordo.disabled=false;
         parent.document.formaba.acordogarantia.disabled=false;
         top.corpo.iframe_acordogarantia.location.href='aco1_acordoacordogarantia001.php?ac12_acordo=".@$chavepesquisa."';
         parent.document.formaba.acordopenalidade.disabled=false;
         top.corpo.iframe_acordopenalidade.location.href='aco1_acordoacordopenalidade001.php?ac15_acordo=".@$chavepesquisa."';
         parent.document.formaba.acordoitem.disabled=false;
         top.corpo.iframe_acordoitem.location.href='aco1_acordoitem001.php?ac20_acordo=".@$chavepesquisa."';
         parent.document.formaba.acordodocumento.disabled=false;
         top.corpo.iframe_acordodocumento.location.href='aco1_acordodocumento001.php?ac40_acordo=".@$chavepesquisa."';
     ";
         if(isset($liberaaba)){
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
 
 //echo "<script>alert($('ac16_origem').disabled);</script>";
 
?>