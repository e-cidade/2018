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
include("classes/db_rhcadcalend_classe.php");
include("classes/db_calendf_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrhcadcalend = new cl_rhcadcalend;
$clcalendf = new cl_calendf;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($incluir) || isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 2;
  $db_botao = true;
  $sqlerro = false;
  if(isset($alterar)){
    $clrhcadcalend->alterar($rh53_calend);
    if($clrhcadcalend->erro_status == "0"){
      $sqlerro = true;
      $erro_msg = $clrhcadcalend->erro_msg;
    }
  }
  if($sqlerro == false){
    $r62_data = $r62_data_ano."-".$r62_data_mes."-".$r62_data_dia;
    if(isset($incluir)){
      $clcalendf->incluir($rh53_calend,$r62_data);
    }else if(isset($excluir)){
      $clcalendf->excluir($rh53_calend,$r62_data);
    }
    $erro_msg = $clcalendf->erro_msg;
    if($clcalendf->erro_status == "0"){
      $sqlerro = true;
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa) || isset($opcao)){
  if(isset($opcao)){
    $r62_data = split("/",$r62_data);
    $r62_data = $r62_data[2]."-".$r62_data[1]."-".$r62_data[0];
    $chavepesquisa = $r62_calend;
    $result_data = $clcalendf->sql_record($clcalendf->sql_query_file($chavepesquisa, $r62_data));
    if($clcalendf->numrows > 0){
      db_fieldsmemory($result_data, 0);
    }
  }
  $db_opcao = 2;
  $result = $clrhcadcalend->sql_record($clrhcadcalend->sql_query(null,"*",null,"rh53_calend = $chavepesquisa and rh53_instit = ".db_getsession("DB_instit"))); 
  db_fieldsmemory($result,0);
  $db_botao = true;
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include("forms/db_frmrhcadcalend.php");
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
if(isset($alterar) || isset($incluir) || isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($clrhcadcalend->erro_campo!=""){
      echo "<script> document.form1.".$clrhcadcalend->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhcadcalend->erro_campo.".focus();</script>";
    }else if($clcalendf->erro_campo!=""){
      echo "<script> document.form1.".$clcalendf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcalendf->erro_campo.".focus();</script>";
    }
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","rh53_descr",true,1,"rh53_descr",true);
</script>