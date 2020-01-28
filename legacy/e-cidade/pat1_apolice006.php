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
include("dbforms/db_funcoes.php");
include("classes/db_apolice_classe.php");
include("classes/db_apolitem_classe.php");
$clapolice = new cl_apolice;
$clapolitem = new cl_apolitem;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clapolitem->t82_codapo=$t81_codapo;
    $clapolitem->excluir($t81_codapo);
    if($clapolitem->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clapolitem->erro_msg; 
  }
  if($sqlerro==false){
    $clapolice->excluir($t81_codapo);
    if($clapolice->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clapolice->erro_msg; 
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $result = $clapolice->sql_record($clapolice->sql_query($chavepesquisa)); 
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
	include("forms/db_frmapolice.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clapolice->erro_campo!=""){
      echo "<script> document.form1.".$clapolice->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clapolice->erro_campo.".focus();</script>";
    };
  }else{
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pat1_apolice003.php';
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
         parent.document.formaba.apolitem.disabled=false;
         parent.iframe_apolitem.location.href='pat1_apolitem001.php?db_opcaoal=33&t82_codapo=".@$t81_codapo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('apolitem');";
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