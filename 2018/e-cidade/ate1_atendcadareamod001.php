<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_atendcadareamod_classe.php");
include("classes/db_atendcadarea_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clatendcadareamod = new cl_atendcadareamod;
$clatendcadarea = new cl_atendcadarea;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clatendcadareamod->at26_sequencia = $at26_sequencia;
$clatendcadareamod->at26_codarea = $at26_codarea;
$clatendcadareamod->at26_id_item = $at26_id_item;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    
    $rsQueryModuloArea = $clatendcadareamod->sql_record($clatendcadareamod->sql_query(null,"*",null,"at26_id_item = ".$at26_id_item));
    if(pg_num_rows($rsQueryModuloArea) > 0) {
    	db_fieldsmemory($rsQueryModuloArea,0);
    	$sqlerro = true;
    	$erro_msg = "usuário:\\n\\n Módulo já cadastrado na área { $at25_descr }!!!\\n\\n ";
    	$erro_msg .= "Inclusão Não efetuada verifique !!!\\n\\n Administrador:\\n\\n";
    	$clatendcadareamod->erro_campo = "at26_id_item";
    	    	
    }
    if(!$sqlerro){
	    $clatendcadareamod->incluir($at26_sequencia);
	    $erro_msg = $clatendcadareamod->erro_msg;
	    if($clatendcadareamod->erro_status==0){
	      $sqlerro=true;
	    }
    }
    db_fim_transacao($sqlerro);

    /**
     * Limpa o cache dos menus
     */
    DBMenu::limpaCache();
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clatendcadareamod->alterar($at26_sequencia);
    $erro_msg = $clatendcadareamod->erro_msg;
    if($clatendcadareamod->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);

    /**
     * Limpa o cache dos menus
     */
    DBMenu::limpaCache();
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clatendcadareamod->excluir($at26_sequencia);
    $erro_msg = $clatendcadareamod->erro_msg;
    if($clatendcadareamod->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
    
    /**
     * Limpa o cache dos menus
     */
    DBMenu::limpaCache();
  }
}else if(isset($opcao)){
   $result = $clatendcadareamod->sql_record($clatendcadareamod->sql_query($at26_sequencia));
   if($result!=false && $clatendcadareamod->numrows>0){
     db_fieldsmemory($result,0);
   }
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
	include("forms/db_frmatendcadareamod.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clatendcadareamod->erro_campo!=""){
        echo "<script> document.form1.".$clatendcadareamod->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clatendcadareamod->erro_campo.".focus();</script>";
    }
}
?>