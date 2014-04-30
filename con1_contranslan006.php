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
include("classes/db_contranslan_classe.php");
include("classes/db_contranslr_classe.php");
include("classes/db_contrans_classe.php");
include("classes/db_conhistdoc_classe.php");



$clcontranslan = new cl_contranslan;
$clcontranslr = new cl_contranslr;
$clcontrans = new cl_contrans;
$clconhistdoc = new cl_conhistdoc;

$anousu    = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcontranslr->c47_seqtranslr=$c46_seqtranslan;
  $clcontranslr->excluir($c46_seqtranslan);

  if($clcontranslr->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcontranslr->erro_msg; 
  $clcontranslan->excluir($c46_seqtranslan);
  if($clcontranslan->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clcontranslan->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clcontranslan->sql_record($clcontranslan->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
}else if(isset($outro) && $outro==true){
  $c46_seqtrans    = '';
  $c46_seqtranslan = '';
  $c46_codhist     = '';
  $c46_obs         = '';
  $c50_descr       = '';
  $db_botao = false;
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
	include("forms/db_frmcontranslan.php");
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
    if($clcontranslan->erro_campo!=""){
      echo "<script> document.form1.".$clcontranslan->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcontranslan->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='con1_contranslan003.php';
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
         parent.document.formaba.contranslr.disabled=false;
         top.corpo.iframe_contranslr.location.href='con1_contranslr001.php?db_opcaoal=33&c47_seqtranslan=".@$c46_seqtranslan."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('contranslr');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
if(empty($c46_seqtrans) && empty($outro)){
  echo "<script>document.form1.seleciona.click();</script>";
}else if(isset($outro) && isset($c46_seqtrans) && $c46_seqtrans!=''){
  echo "<script>js_pesquisa();</script>";
}
?>