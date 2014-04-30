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
require_once("classes/db_rhresponsavel_classe.php");
require_once("classes/db_cgm_classe.php");

$oPost           = db_utils::postMemory($_POST);
$oGet            = db_utils::postMemory($_GET);

$clrhresponsavel = new cl_rhresponsavel();
$clcgm           = new cl_cgm();

$db_opcao        = 1;
$db_botao        = true;
$lSqlErro        = false;

if (isset($oPost->incluir)) {
  
  db_inicio_transacao();
  
  if (!$lSqlErro) {
  	
  	$clrhresponsavel->rh107_numcgm = $oPost->rh107_numcgm;
  	$clrhresponsavel->rh107_nome   = $oPost->rh107_nome;
  	$clrhresponsavel->incluir(null);
  	$sMsgUsuario = $clrhresponsavel->erro_msg;
  	if ($clrhresponsavel->erro_status == 0) {
  		$lSqlErro = true;
  	}
  }
  
  db_fim_transacao($lSqlErro);

  $rh107_sequencial = $clrhresponsavel->rh107_sequencial;
  $db_botao         = true;
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
    #rh107_nome, #z01_nome {
      width: 100%;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
	<table border="0" align="center" cellspacing="0" cellpadding="0">
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td valign="top" bgcolor="#CCCCCC"> 
	    <center>
	      <?
	        include("forms/db_frmrhresponsavel.php");
	      ?>
	    </center>
	  </td>
	  </tr>
	</table>
</body>
<?
if (isset($oPost->incluir)) {
  
  if ($lSqlErro) {
    
    db_msgbox($sMsgUsuario);
    if ($clrhresponsavel->erro_campo != "") {
      
      echo "<script> document.form1.".$clrhresponsavel->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhresponsavel->erro_campo.".focus();</script>";
    }
  } else {
    
    db_msgbox($sMsgUsuario);
    db_redireciona("pes4_rhresponsavel005.php?liberaaba=true&chavepesquisa={$rh107_sequencial}");
  }
}
?>
</html>