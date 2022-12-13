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

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;
$iInstiti = db_getsession('DB_instit');

if (isset($oPost->alterar)) {
  
  db_inicio_transacao();

  if (!$lSqlErro) {
    
	  $sCampos               = "tipoproc.p51_codigo";
	  $sWhere                = "workflow.db112_sequencial = {$oPost->db112_sequencial}";
	  $sSqlWorkflowTipoProc  = $clworkflowtipoproc->sql_query(null, $sCampos, null, $sWhere);
	  $rsSqlWorkflowTipoProc = $clworkflowtipoproc->sql_record($sSqlWorkflowTipoProc); 
	  if ($clworkflowtipoproc->numrows > 0) {
	  		    
	  	$oTipoProc = db_utils::fieldsMemory($rsSqlWorkflowTipoProc, 0);
	  	
	  	$cltipoproc->p51_codigo = $oTipoProc->p51_codigo;
	    $cltipoproc->p51_descr  = "{$oPost->p51_descr}";
	    $cltipoproc->alterar($cltipoproc->p51_codigo);
	    
	    if ($cltipoproc->erro_status == 0) {
	      
	      $lSqlErro = true;
	      $erro_msg = $cltipoproc->erro_msg; 
	    }
	  } else {
	  	
      $lSqlErro = true;
      $erro_msg = 'Nenhum registro cadastrado no processo de protocolo!'; 
	  }
  }
  
  if (!$lSqlErro) {
    
    $clworkflow->db112_sequencial = $oPost->db112_sequencial;
    $clworkflow->db112_descricao  = "{$oPost->db112_descricao}";
    $clworkflow->alterar($clworkflow->db112_sequencial);
    
    $db112_sequencial = $clworkflow->db112_sequencial;
    
    $erro_msg = $clworkflow->erro_msg; 
    if ($clworkflow->erro_status == 0) {  	
      $lSqlErro = true;
    }
  }
  
  db_fim_transacao($lSqlErro);
	
  $db_opcao = 2;
  $db_botao = true;
} else if (isset($oGet->chavepesquisa)) {
  
  $db_opcao = 2;
  $db_botao = true;
  
  $sCampos               = "workflow.db112_sequencial, workflow.db112_descricao, tipoproc.p51_descr";
  $sWhere                = "workflow.db112_sequencial = {$oGet->chavepesquisa}";
  $sSqlWorkflowTipoProc  = $clworkflowtipoproc->sql_query(null, $sCampos, null, $sWhere);
  $rsSqlWorkflowTipoProc = $clworkflowtipoproc->sql_record($sSqlWorkflowTipoProc); 
  if ($clworkflowtipoproc->numrows > 0) {
    db_fieldsmemory($rsSqlWorkflowTipoProc, 0);
  }
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
if (isset($oPost->alterar)) {
  
  db_msgbox($erro_msg);
  if ($lSqlErro == true) {
    
    if ($clworkflow->erro_campo != "") {
      
      echo "<script> document.form1.".$clworkflow->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clworkflow->erro_campo.".focus();</script>";
    }
  }
}

if (isset($oGet->chavepesquisa)) {

  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.workflow.disabled=false;
         parent.document.formaba.workflowativ.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_workflowativ.location.href='hab1_workflowativ001.php?db112_sequencial={$oGet->chavepesquisa}';
     ";
  
  if (isset($oGet->liberaaba)) {
    echo "  parent.mo_camada('workflowativ');";
  }
  
  echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>