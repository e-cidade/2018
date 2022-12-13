<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_emppresta_classe.php");
require_once("classes/db_empprestaitem_classe.php");
$clemppresta = new cl_emppresta;
  /*
$clempprestaitem = new cl_empprestaitem;
  */

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){

  $sqlerro=false;
  db_inicio_transacao();
  $clempprestaitem->e46_numemp=$e45_numemp;
  $clempprestaitem->excluir($e45_numemp);

  if($clempprestaitem->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clempprestaitem->erro_msg; 
  $clemppresta->excluir($e45_numemp);
  if($clemppresta->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clemppresta->erro_msg; 
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;

}else if(isset($chavepesquisa)){
   
  $db_opcao = 3;
  $db_botao = true;
  $result = $clemppresta->sql_record($clemppresta->sql_query($chavepesquisa)); 
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
	include("forms/db_frmemppresta.php");
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
    if($clemppresta->erro_campo!=""){
      echo "<script> document.form1.".$clemppresta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clemppresta->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='emp1_emppresta003.php';
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
         parent.document.formaba.empprestaitem.disabled=false;
         top.corpo.iframe_empprestaitem.location.href='emp1_empprestaitem001.php?db_opcaoal=33&e46_numemp=".@$e45_numemp."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('empprestaitem');";
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