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
include("classes/db_cgs_cartaosus_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
db_postmemory($HTTP_POST_VARS);
$clcgs_cartaosus = new cl_cgs_cartaosus;
$db_opcao = 1;
$db_botao = true;
//altera exclui inicio
if(isset($opcao)){
 /////comeca classe alterar excluir
 $campos = "";
 $result1 = $clcgs_cartaosus->sql_record($clcgs_cartaosus->sql_query("","*",""," s115_i_codigo = $s115_i_codigo"));
 if($clcgs_cartaosus->numrows>0){
  db_fieldsmemory($result1,0);
 }
 if( $opcao == "alterar"){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  if( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
   $db_opcao = 3;
   $db_botao1 = true;
  }else{
   if(isset($alterar)){
    $db_opcao = 2;
    $db_botao1 = true;
   }
  }
 }
}
if(isset($incluir)){
  db_inicio_transacao();
  $clcgs_cartaosus->incluir($s115_i_codigo);
  db_fim_transacao();
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clcgs_cartaosus->alterar($s115_i_codigo);
  db_fim_transacao();
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clcgs_cartaosus->excluir($s115_i_codigo);
  db_fim_transacao();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("webseller.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
    <fieldset style="width: 75%"><legend><b>Cartão SUS</b></legend>
	<?
	include("forms/db_frmcgs_cartaosus.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","s115_i_cgs",true,1,"s115_i_cgs",true);
</script>
<?
if((isset($incluir))||(isset($alterar))||(isset($excluir))){
	if($clcgs_cartaosus->erro_status == "0"){
        $clcgs_cartaosus->erro(true,false);
	    $db_botao=true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if($clcgs_cartaosus->erro_campo!=""){
            echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".focus();</script>";
        }
	}else{
        $clcgs_cartaosus->erro(true,false);
        db_redireciona("sau1_cgs_cartaosus001.php?s115_i_cgs=$s115_i_cgs&z01_v_nome=$z01_v_nome");
   }
}
?>