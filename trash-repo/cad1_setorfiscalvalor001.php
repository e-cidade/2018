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
include("classes/db_setorfiscalvalor_classe.php");
include("classes/db_setorfiscal_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsetorfiscalvalor = new cl_setorfiscalvalor;
$clsetorfiscal = new cl_setorfiscal;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clsetorfiscalvalor->j82_codigo = $j82_codigo;
$clsetorfiscalvalor->j82_setorfiscal = $j82_setorfiscal;
$clsetorfiscalvalor->j82_anousu = $j82_anousu;
$clsetorfiscalvalor->j82_valorterreno = $j82_valorterreno;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clsetorfiscalvalor->incluir($j82_codigo);
    $erro_msg = $clsetorfiscalvalor->erro_msg;
    if($clsetorfiscalvalor->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clsetorfiscalvalor->j82_codigo = $j82_codigo;
    $clsetorfiscalvalor->alterar($j82_codigo);
    $erro_msg = $clsetorfiscalvalor->erro_msg;
    if($clsetorfiscalvalor->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();    
    $clsetorfiscalvalor->j82_codigo = $j82_codigo;
    $clsetorfiscalvalor->excluir($j82_codigo);
    $erro_msg = $clsetorfiscalvalor->erro_msg;
    if($clsetorfiscalvalor->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clsetorfiscalvalor->sql_record($clsetorfiscalvalor->sql_query($j82_codigo));
   if($result!=false && $clsetorfiscalvalor->numrows>0){
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmsetorfiscalvalor.php");
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
    if($clsetorfiscalvalor->erro_campo!=""){
        echo "<script> document.form1.".$clsetorfiscalvalor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clsetorfiscalvalor->erro_campo.".focus();</script>";
    }
}
?>