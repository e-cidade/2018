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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pctipocompratribunal_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpctipocompra         = new cl_pctipocompra;
$clpctipocompratribunal = new cl_pctipocompratribunal;

$db_opcao = 33;
$db_botao = false;
$sqlerro  = false;
$iInstit  = db_getsession('DB_instit');

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir") {
	
  db_inicio_transacao();
  
  $db_opcao = 3;
  
  $clpctipocompra->excluir($pc50_codcom);
  $sMensagem = $clpctipocompra->erro_msg;
  if ($clpctipocompra->erro_status == 0) {
    $sqlerro = true;
  }
  
  db_fim_transacao($sqlerro);
  
} else if (isset($chavepesquisa)) {
	
  $db_opcao = 3;
  $db_botao = true;
  
  $result   = $clpctipocompra->sql_record($clpctipocompra->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmpctipocompra.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir") {
	
  if ($clpctipocompra->erro_status == "0") {
    db_msgbox($sMensagem);
  } else {
  	
    db_msgbox($sMensagem);
	  echo "
	    <script>
	      function js_db_tranca(){
	        parent.location.href='com1_pctipocompra003.php';
	      }\n
	      js_db_tranca();
	    </script>\n
	  ";
  }
}

if (isset($chavepesquisa)) {
	
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tipocompras.disabled=false;
         parent.document.formaba.faixavalores.disabled=false;
         top.corpo.iframe_faixavalores.location.href='com1_pctipocomprafaixavalor001.php?pc50_codcom=".@$pc50_codcom."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('faixavalores');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if ($db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>