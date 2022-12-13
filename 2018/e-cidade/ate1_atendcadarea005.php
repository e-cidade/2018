<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_atendcadarea_classe.php");
include("classes/db_atendcadareamod_classe.php");
$clatendcadarea = new cl_atendcadarea;
  /*
$clatendcadareamod = new cl_atendcadareamod;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  
  
  if(isset($_FILES["at25_figura"]["name"]) && $_FILES["at25_figura"]["name"] != "" ){
    //echo "aquiii";
      
      $sName = $at25_descr.".jpg";
      $sName = str_replace(':','',$sName);
      $sName = str_replace(' ','',$sName);
      $sName = db_removeAcentuacao($sName);
          
      $path = "imagens/files/area/".$sName;
      //die($path);
      $lArquivo = move_uploaded_file($_FILES["at25_figura"]["tmp_name"],$path);
      if(!$lArquivo){
        $sqlerro = true;
        $clatendcadarea->erro_msg  = "usuário:\\n\\n Falha ao importar arquivo de imagem!\\n\\n";
        $clatendcadarea->erro_status = "0";
       }
      $clatendcadarea->at25_figura = $sName;
   }
  
  if(!$sqlerro){
	  $clatendcadarea->alterar($at26_sequencial);
	  if($clatendcadarea->erro_status==0){
	    $sqlerro=true;
	  }
  } 
  $erro_msg = $clatendcadarea->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();
  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   $result = $clatendcadarea->sql_record($clatendcadarea->sql_query($chavepesquisa)); 
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmatendcadarea.php");
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
    if($clatendcadarea->erro_campo!=""){
      echo "<script> document.form1.".$clatendcadarea->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatendcadarea->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.atendcadareamod.disabled=false;
         top.corpo.iframe_atendcadareamod.location.href='ate1_atendcadareamod001.php?at26_codarea=".@$at26_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('atendcadareamod');";
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