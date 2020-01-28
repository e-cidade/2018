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
include("classes/db_db_sysregrasacesso_classe.php");
include("classes/db_db_sysregrasacessocanc_classe.php");
include("classes/db_db_sysregrasacessousu_classe.php");
include("classes/db_db_sysregrasacessoip_classe.php");
$cldb_sysregrasacesso = new cl_db_sysregrasacesso;
  /*
$cldb_sysregrasacessocanc = new cl_db_sysregrasacessocanc;
$cldb_sysregrasacessousu = new cl_db_sysregrasacessousu;
$cldb_sysregrasacessoip = new cl_db_sysregrasacessoip;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_sysregrasacessocanc->db49_idacesso=$db46_idacesso;
  $cldb_sysregrasacessocanc->excluir($db46_idacesso);

  if($cldb_sysregrasacessocanc->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cldb_sysregrasacessocanc->erro_msg; 
  $cldb_sysregrasacessousu->db47_idacesso=$db46_idacesso;
  $cldb_sysregrasacessousu->excluir($db46_idacesso);

  if($cldb_sysregrasacessousu->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cldb_sysregrasacessousu->erro_msg; 
  $cldb_sysregrasacessoip->db48_idacesso=$db46_idacesso;
  $cldb_sysregrasacessoip->excluir($db46_idacesso);

  if($cldb_sysregrasacessoip->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cldb_sysregrasacessoip->erro_msg; 
  $cldb_sysregrasacesso->excluir($db46_idacesso);
  if($cldb_sysregrasacesso->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $cldb_sysregrasacesso->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cldb_sysregrasacesso->sql_record($cldb_sysregrasacesso->sql_query($chavepesquisa)); 
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
	include("forms/db_frmdb_sysregrasacesso.php");
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
    if($cldb_sysregrasacesso->erro_campo!=""){
      echo "<script> document.form1.".$cldb_sysregrasacesso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_sysregrasacesso->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='con1_db_sysregrasacesso003.php';
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
         parent.document.formaba.db_sysregrasacessocanc.disabled=false;
         top.corpo.iframe_db_sysregrasacessocanc.location.href='con1_db_sysregrasacessocanc001.php?db_opcaoal=33&db49_idacesso=".@$db46_idacesso."';
         parent.document.formaba.db_sysregrasacessousu.disabled=false;
         top.corpo.iframe_db_sysregrasacessousu.location.href='con1_db_sysregrasacessousu001.php?db_opcaoal=33&db47_idacesso=".@$db46_idacesso."';
         parent.document.formaba.db_sysregrasacessoip.disabled=false;
         top.corpo.iframe_db_sysregrasacessoip.location.href='con1_db_sysregrasacessoip001.php?db_opcaoal=33&db48_idacesso=".@$db46_idacesso."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_sysregrasacessocanc');";
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