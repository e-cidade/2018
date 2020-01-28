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
include("classes/db_bens_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_departdiv_classe.php");
include ("classes/db_histbemdiv_classe.php");
include ("classes/db_bensdiv_classe.php");
$cldepartdiv = new cl_departdiv;
$clbens = new cl_bens;
$clbensimoveis = new cl_bensimoveis;
$clbensmater = new cl_bensmater;
$clhistbemdiv = new cl_histbemdiv;
$clbensdiv = new cl_bensdiv;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if ($sqlerro == false) {
	$result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t52_bem));
	if ($clbensdiv->numrows>0){
		$clbensdiv->excluir($t52_bem);
		if($clbensdiv->erro_status==0){
			$sqlerro=true;
			$erro_msg=$clbensdiv->erro_msg;
		} 
	 }
  }  
  $clbensimoveis->t54_codbem=$t52_bem;
  $clbensimoveis->excluir($t52_bem);

  if($clbensimoveis->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clbensimoveis->erro_msg; 
  $clbensmater->t53_codbem=$t52_bem;
  $clbensmater->excluir($t52_bem);

  if($clbensmater->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clbensmater->erro_msg; 
  $clbens->excluir($t52_bem);
  if($clbens->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clbens->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clbens->sql_record($clbens->sql_query($chavepesquisa)); 
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
	include("forms/db_frmbens.php");
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
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pat1_bens003.php';
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
         parent.document.formaba.bensimoveis.disabled=false;
         top.corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?db_opcaoal=33&t54_codbem=".@$t52_bem."';
         parent.document.formaba.bensmater.disabled=false;
         top.corpo.iframe_bensmater.location.href='pat1_bensmater001.php?db_opcaoal=33&t53_codbem=".@$t52_bem."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('bensimoveis');";
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