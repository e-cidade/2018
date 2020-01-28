<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_modcarnepadrao_classe.php");
require_once("classes/db_modcarneexcessao_classe.php");
require_once("classes/db_modcarnepadraotipo_classe.php");
require_once("classes/db_modcarnepadraolayouttxt_classe.php");
require_once("classes/db_modcarnepadraocadmodcarne_classe.php");

db_postmemory($HTTP_POST_VARS);

$clmodcarnepadrao 			     = new cl_modcarnepadrao;
$clmodcarneexcessao   		   = new cl_modcarneexcessao;
$clmodcarnepadraotipo		     = new cl_modcarnepadraotipo;
$clmodcarnepadraolayouttxt   = new cl_modcarnepadraolayouttxt;
$clmodcarnepadraocadmodcarne = new cl_modcarnepadraocadmodcarne;
  

$db_opcao = 33;
$db_botao = false;

if(isset($excluir)){

  $sqlerro=false;
  db_inicio_transacao();
  
  $rsConsultaTipo = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query_func($k48_sequencial));
  $oTipo		  = db_utils::fieldsMemory($rsConsultaTipo,0); 
  
  
  if (!empty($oTipo->m01_sequencial)) {
	$clmodcarnepadraocadmodcarne->excluir($oTipo->m01_sequencial);
	if ($clmodcarnepadraocadmodcarne->erro_status == 0) {
	  $sqlerro = true;		  	
	}
	$erro_msg = $clmodcarnepadraocadmodcarne->erro_msg;
	
  } else if(!empty($oTipo->m02_sequencial)) {
	$clmodcarnepadraolayouttxt->excluir($oTipo->m02_sequencial);
	if ($clmodcarnepadraolayouttxt->erro_status == 0) {  	
	  $sqlerro = true;	
	}
	$erro_msg = $clmodcarnepadraolayouttxt->erro_msg;
  }
  
  $clmodcarnepadraotipo->excluir(null," k49_modcarnepadrao = {$k48_sequencial}");
  if($clmodcarnepadraotipo->erro_status == 0){
    $sqlerro=true;
  } 
  $erro_msg = $clmodcarnepadraotipo->erro_msg;

  $clmodcarneexcessao->excluir(null," k36_modcarnepadrao = {$k48_sequencial}");
  if($clmodcarneexcessao->erro_status == 0){
    $sqlerro=true;
  } 
  $erro_msg = $clmodcarneexcessao->erro_msg;  
  
  
  $clmodcarnepadrao->excluir($k48_sequencial);
  if($clmodcarnepadrao->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clmodcarnepadrao->erro_msg; 
  db_fim_transacao($sqlerro);
  
  $db_opcao = 3;
  $db_botao = true;
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query_func($chavepesquisa)); 
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
<table width="790" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmodcarnepadrao.php");
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
    if($clmodcarnepadrao->erro_campo!=""){
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='cai1_modcarnepadrao003.php';
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
         parent.document.formaba.modcarnepadraotipo.disabled = false;
         parent.document.formaba.modcarneexcessao.disabled   = false;
         top.corpo.iframe_modcarnepadraotipo.location.href='cai1_modcarnepadraotipo001.php?k49_modcarnepadrao=".@$k48_sequencial."';
         top.corpo.iframe_modcarneexcessao.location.href='cai1_modcarneexcessao001.php?k36_modcarnepadrao=".@$k48_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('modcarnepadraotipo');";
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