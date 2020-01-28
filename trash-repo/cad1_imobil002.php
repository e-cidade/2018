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
include("classes/db_imobil_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$climobil = new cl_imobil;
$clcgm = new cl_cgm;
$db_opcao = 22;
$db_botao = false;
$sqlerro=false;
if(isset($incluir)){
  db_inicio_transacao();
  if($sqlerro==false){
  	$climobil->j44_numcgm=$j44_numcgm;    
  	$climobil->incluir($j44_matric);
  	$erro_msg=$climobil->erro_msg;
  	if ($climobil->erro_status==0){
  		$sqlerro=true;
  	}  
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  if($sqlerro==false){    	    
  	$climobil->excluir(null,"j44_numcgm=$j44_numcgm and j44_matric=$j44_matric_ant");
  	$erro_msg=$climobil->erro_msg;
  	if ($climobil->erro_status==0){
  		$sqlerro=true;
  	}
  	$db_opcao = 2;
  	$climobil->j44_numcgm=$j44_numcgm;    
  	$climobil->incluir($j44_matric);
  	$erro_msg=$climobil->erro_msg;
  	if ($climobil->erro_status==0){
  		$sqlerro=true;
  	}  
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  db_inicio_transacao();
  if($sqlerro==false){
  	$climobil->excluir(null,"j44_numcgm=$j44_numcgm and j44_matric=$j44_matric_ant");
  	$erro_msg=$climobil->erro_msg;
  	if ($climobil->erro_status==0){
  		$sqlerro=true;
  	}   
  }
  db_fim_transacao($sqlerro);
}else if(isset($opcao)){   
   $db_opcao = 2;   
   $result = $climobil->sql_record($climobil->sql_query(null,"cgm.z01_nome,a.z01_nome as dono",null,"j44_numcgm=$j44_numcgm and j44_matric=$j44_matric")); 
   db_fieldsmemory($result,0);
   $j44_matric_ant=$j44_matric;
   $db_botao = true;  
}else if(isset($chavepesquisa)){
	$db_opcao=1;
	$db_botao = true;
	$result_nome = $clcgm->sql_record($clcgm->sql_query_file($chavepesquisa,"z01_nome"));
	if ($clcgm->numrows>0){
		db_fieldsmemory($result_nome,0);
		$j44_numcgm=$chavepesquisa;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmimobil.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($climobil->erro_status=="0"){
    $climobil->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($climobil->erro_campo!=""){
      echo "<script> document.form1.".$climobil->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$climobil->erro_campo.".focus();</script>";
    }
  }else{
  	db_msgbox($erro_msg);
  	echo "<script>location.href='cad1_imobil002.php?chavepesquisa=$j44_numcgm';</script>";
  }
}
if($db_opcao==22){
  echo "<script>js_pesquisa();</script>";
}
?>