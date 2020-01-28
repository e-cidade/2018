<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_workflow_classe.php"));
require_once(modification("classes/db_workflowtipoproc_classe.php"));
require_once(modification("classes/db_tipoproc_classe.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clworkflow         = new cl_workflow();
$clworkflowtipoproc = new cl_workflowtipoproc();
$cltipoproc         = new cl_tipoproc();

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
$iInstiti = db_getsession('DB_instit');

$grupo = 3;

if (!empty($_GET['grupo'])) {
  $grupo = $_GET['grupo'];  
}


if (isset($oPost->incluir)) {
  
  db_inicio_transacao();

  if (!$lSqlErro) {
    
    $cltipoproc->p51_descr         = $oPost->p51_descr;
    $cltipoproc->p51_dtlimite      = '';
    $cltipoproc->p51_identificado  = 'false';
    $cltipoproc->p51_instit        = $iInstiti;
    $cltipoproc->p51_tipoprocgrupo = $grupo; 
    $cltipoproc->incluir(null);
    
    $p51_codigo = $cltipoproc->p51_codigo;
    
    if ($cltipoproc->erro_status == 0) {
      
      $lSqlErro = true;
      $erro_msg = $cltipoproc->erro_msg; 
    }
  }
  
  if (!$lSqlErro) {
  	
    $clworkflow->db112_sequencial = $oPost->db112_sequencial;
    $clworkflow->incluir($clworkflow->db112_sequencial);
    
    $db112_sequencial = $clworkflow->db112_sequencial;
    
    $erro_msg = $clworkflow->erro_msg; 
    if ($clworkflow->erro_status == 0) {
      $lSqlErro = true;
    }
  }
  
  if (!$lSqlErro) {
    
    $clworkflowtipoproc->db116_tipoproc = $p51_codigo;
    $clworkflowtipoproc->db116_workflow = $db112_sequencial;
  	$clworkflowtipoproc->incluir(null);
  	
    if ($clworkflowtipoproc->erro_status == 0) {
    	
      $lSqlErro = true;
      $erro_msg = $clworkflowtipoproc->erro_msg; 
    }
  }
   
  db_fim_transacao($lSqlErro);
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
        include(modification("forms/db_frmworkflow.php"));
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->incluir)) {
  
  db_msgbox($erro_msg);
  if ($lSqlErro == true) {
    
    if ($clworkflow->erro_campo != "") {
      
      echo "<script> document.form1.".$clworkflow->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clworkflow->erro_campo.".focus();</script>";
    }
  } else {
  	
  	if (isset($db112_sequencial)) {
      db_redireciona("hab1_workflow005.php?liberaaba=true&chavepesquisa={$db112_sequencial}");
  	}
  }
}
?>