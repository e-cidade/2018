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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_habitformaavaliacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clhabitformaavaliacao = new cl_habitformaavaliacao;

if (isset($oGet->chavepesquisa)) {
  
  $sSqlFormaAvaliacao = $clhabitformaavaliacao->sql_query($oGet->chavepesquisa);
  $rsFormaAvaliacao   = $clhabitformaavaliacao->sql_record($sSqlFormaAvaliacao); 
  db_fieldsmemory($rsFormaAvaliacao, 0);
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
<style>
.fieldsetinterno {
            border:0px;
            border-top:2px groove white;
            margin-top:10px;
}

td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 90px;
              white-space: nowrap
}

#ht02_descricao {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmhabformaavaliacao.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oGet->chavepesquisa)) {
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.formaavaliacao.disabled=false;
         parent.document.formaba.formaavaliacaousuario.disabled=false;
         top.corpo.iframe_formaavaliacaousuario.location.href='hab1_formaavaliacaousuario001.php?ht07_sequencial=".@$ht07_sequencial."';
     ";
  
  echo"}\n
    js_db_libera();
  </script>\n
 ";
} else {  
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>