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
require_once("classes/db_avaliacao_classe.php");
require_once("classes/db_avaliacaotipo_classe.php");

db_postmemory($HTTP_POST_VARS);

$clavaliacao             = new cl_avaliacao;
$clavaliacaotipo         = new cl_avaliacaotipo;

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {
	
  $sqlerro = false;
  db_inicio_transacao();
  
  $clavaliacao->incluir($db101_sequencial);
  if ($clavaliacao->erro_status == 0) {
    $sqlerro = true;
  }
   
  $erro_msg = $clavaliacao->erro_msg; 
  db_fim_transacao($sqlerro);
  
  $db101_sequencial = $clavaliacao->db101_sequencial;
  $db_opcao         = 1;
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
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 120px;
              white-space: nowrap
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
        include("forms/db_frmavaliacao.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($incluir)) {
	
  if ($sqlerro == true) {
  	
    db_msgbox($erro_msg);
    if ($clavaliacao->erro_campo != "") {
    	
      echo "<script> document.form1.".$clavaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clavaliacao->erro_campo.".focus();</script>";
    }
  } else {
  	
    db_msgbox($erro_msg);
    db_redireciona("hab1_avaliacao005.php?liberaaba=true&chavepesquisa=$db101_sequencial");
  }
}
?>