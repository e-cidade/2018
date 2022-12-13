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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_acordogrupodocumento_classe.php");
require_once("classes/db_acordogrupo_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clacordogrupodocumento = new cl_acordogrupodocumento;
$clacordogrupo          = new cl_acordogrupo;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

$sSqlAcordoGrupo  = $clacordogrupo->sql_query($ac02_sequencial);
$rsSqlAcordoGrupo = $clacordogrupo->sql_record($sSqlAcordoGrupo);
if ($clacordogrupo->numrows > 0) {
	
	$oAcordoGrupo   = db_utils::fieldsMemory($rsSqlAcordoGrupo,0);
	$ac02_descricao = $oAcordoGrupo->ac02_descricao;
}

if (isset($incluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clacordogrupodocumento->ac06_acordogrupo       = $ac02_sequencial;
    $clacordogrupodocumento->ac06_documentotemplate = $ac06_documentotemplate;
    $clacordogrupodocumento->ac06_tipodocumento     = $ac06_tipodocumento;
    $clacordogrupodocumento->incluir(null);
    $erro_msg = $clacordogrupodocumento->erro_msg;
    if ($clacordogrupodocumento->erro_status == 0) {
      $sqlerro=true;
    }
    
    $ac06_sequencial = $clacordogrupodocumento->ac06_sequencial;    
    $db82_descricao  = "";
    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    
    $clacordogrupodocumento->ac06_acordogrupo       = $ac02_sequencial;
    $clacordogrupodocumento->ac06_documentotemplate = $ac06_documentotemplate;
    $clacordogrupodocumento->ac06_tipodocumento     = $ac06_tipodocumento;
    $clacordogrupodocumento->alterar($ac06_sequencial);
    $erro_msg = $clacordogrupodocumento->erro_msg;
    if ($clacordogrupodocumento->erro_status == 0) {
      $sqlerro=true;
    }
    
    $ac06_sequencial = $clacordogrupodocumento->ac06_sequencial;
    $db82_descricao  = "";
    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    $clacordogrupodocumento->excluir($ac06_sequencial);
    $erro_msg = $clacordogrupodocumento->erro_msg;
    if ($clacordogrupodocumento->erro_status == 0) {
      $sqlerro=true;
    }
    
    $db82_descricao  = "";
    db_fim_transacao($sqlerro);
  }
} else if (isset($opcao)) {
	
   $result         = $clacordogrupodocumento->sql_record($clacordogrupodocumento->sql_query($ac06_sequencial));
   if ($result != false && $clacordogrupodocumento->numrows > 0) {
     db_fieldsmemory($result,0);
   }
} else if (isset($novo) && $novo == true) {
	$db82_descricao  = "";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table border="0" align="center" cellspacing="0" cellpadding="0" width="530">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
		  <?
		    include("forms/db_frmacordogrupodocumento.php");
		  ?>
    </center>
  </td>
  </tr>
</table>
</body>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
	
    db_msgbox($erro_msg);
    if ($clacordogrupodocumento->erro_campo != "") {
    	
        echo "<script> document.form1.".$clacordogrupodocumento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clacordogrupodocumento->erro_campo.".focus();</script>";
    }
}
?>
</html>