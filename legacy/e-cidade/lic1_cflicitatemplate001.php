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
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_cflicitatemplate_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET); 

$clcflicita         = new cl_cflicita;
$clcflicitatemplate = new cl_cflicitatemplate;
$clliclicita        = new cl_liclicita;

$db_opcao = 22;
$db_botao = false;
$lErro    = false;

if ( isset($oGet->l35_cflicita) && trim($oGet->l35_cflicita) != '' ) {
	$l35_cflicita = $oGet->l35_cflicita; 
} else if ( isset($oPost->l35_cflicita) && trim($oPost->l35_cflicita) != '' ) {
  $l35_cflicita = $oPost->l35_cflicita;
}

if ( isset($oPost->incluir) || isset($oPost->excluir ) ) {
	
  db_inicio_transacao();

	if ( isset($oPost->incluir) ) {
	  $clcflicitatemplate->l35_cflicita             = $oPost->l35_cflicita;
	  $clcflicitatemplate->l35_db_documentotemplate = $oPost->l35_db_documentotemplate;	
    $clcflicitatemplate->incluir(null);
  }	else if ( isset($oPost->excluir) ) {
    $clcflicitatemplate->excluir($oPost->l35_sequencial);
  }
  
  $sErroMsg = $clcflicitatemplate->erro_msg;
  
  if ( $clcflicitatemplate->erro_status == 0 ) {
    $lErro = true;
  }
  
  db_fim_transacao($lErro);
  
} else if (isset($oPost->opcao)) {
  
  $rsModelo = $clcflicitatemplate->sql_record($clcflicitatemplate->sql_query($oPost->l35_sequencial));
  if ( $rsModelo  && $clcflicitatemplate->numrows > 0 ) {
    db_fieldsmemory($rsModelo,0);
  }
  
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
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmcflicitatemplate.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if( isset($oPost->incluir) || isset($oPost->excluir) ){
  db_msgbox($sErroMsg);
}
?>