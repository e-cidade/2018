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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_sanitario_classe.php");
include("classes/db_sanitarioinscr_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clsanitario = new cl_sanitario;
$clsanitarioinscr = new cl_sanitarioinscr;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $sqlerro=false;
  $clsanitario->y80_depto=db_getsession("DB_coddepto");
  $clsanitario->incluir($y80_codsani);
  if($clsanitario->erro_status==0){
	   $sqlerro=true;
	   $erro=$clsanitario->erro_msg;
  }
	
  if($q02_inscr!=""){
  	$clsanitarioinscr->y18_codsani = $clsanitario->y80_codsani ;
	$clsanitarioinscr->y18_inscr = $q02_inscr;
	$clsanitarioinscr->incluir($clsanitario->y80_codsani,$q02_inscr) ;
	if($clsanitarioinscr->erro_status==0){
	   $sqlerro=true;
	   $erro=$clsanitarioinscr->erro_msg;
	}
		 
  }
  
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmsanitario.php");
  ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($sqlerro==true){
    $clsanitario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsanitario->erro_campo!=""){
      echo "<script> document.form1.".$clsanitario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsanitario->erro_campo.".focus();</script>";
    };
  }else{
    $clsanitario->erro(true,false);
    echo "
         <script>
         function js_src(){
         parent.document.formaba.observacoes.disabled = false;
         parent.document.formaba.saniatividade.disabled = false;
         parent.document.formaba.resptecnico.disabled = false;
         parent.document.formaba.calculo.disabled = false;
         parent.iframe_sanitario.location.href='fis1_sanitario002.php?chavepesquisa=".$clsanitario->y80_codsani."';\n
         parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=".$clsanitario->y80_codsani."';\n
         parent.iframe_resptecnico.location.href='fis1_resptecnico001.php?y22_codsani=".$clsanitario->y80_codsani."';\n
         parent.iframe_calculo.location.href='fis1_sanicalc001.php?y80_codsani=".$clsanitario->y80_codsani."';\n
         parent.mo_camada('observacoes');
         }
         js_src();
         </script>
     ";
  };
};
?>