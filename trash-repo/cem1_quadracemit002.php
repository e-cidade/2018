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
include("classes/db_quadracemit_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clquadracemit = new cl_quadracemit;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $result = $clquadracemit->sql_record($clquadracemit->sql_query("","cm22_i_codigo",""," cm22_i_cemiterio = $cm22_i_cemiterio and cm22_c_quadra = '$cm22_c_quadra' and cm22_i_codigo != $cm22_i_codigo"));
  if($clquadracemit->numrows == 0){
   db_inicio_transacao();
    $db_opcao = 2;
    $clquadracemit->alterar($cm22_i_codigo);
   db_fim_transacao();
  }else{
   db_msgbox('AVISO:\nQuadra cadastrada em duplicidade para o cemitério!\nAlteração não Efetuada!');
   unset($alterar);
  }
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $result = $clquadracemit->sql_record($clquadracemit->sql_query($chavepesquisa)); 
 db_fieldsmemory($result,0);
 $db_botao = true;
echo "<script>";
 echo " parent.document.formaba.a2.disabled=false; ";
 echo " top.corpo.iframe_a2.location.href='cem1_lotecemit001.php?tp=1&cm23_i_quadracemit=$cm22_i_codigo';";
 echo "</script>";
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
     include("forms/db_frmquadracemit.php");
     ?>
    </center>
     </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
 if($clquadracemit->erro_status=="0"){
  $clquadracemit->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clquadracemit->erro_campo!=""){
   echo "<script> document.form1.".$clquadracemit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clquadracemit->erro_campo.".focus();</script>";
  }
 }else{
  $clquadracemit->erro(true,false);
  echo "<script> parent.mo_camada('a2'); </script>";
 }
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>