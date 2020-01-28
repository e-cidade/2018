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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_workflow_classe.php"));
require_once(modification("classes/db_workflowativ_classe.php"));
require_once(modification("classes/db_workflowtipoproc_classe.php"));
require_once(modification("classes/db_workflowativandpadrao_classe.php"));
require_once(modification("classes/db_workflowativdb_cadattdinamico_classe.php"));
require_once(modification("classes/db_andpadrao_classe.php"));

require_once(modification('model/DBAttDinamico.model.php'));
require_once(modification('model/DBAttDinamicoAtributo.model.php'));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clworkflow                      = new cl_workflow();
$clworkflowativ                  = new cl_workflowativ();
$clworkflowtipoproc              = new cl_workflowtipoproc();
$clworkflowativandpadrao         = new cl_workflowativandpadrao();
$clworkflowativdb_cadattdinamico = new cl_workflowativdb_cadattdinamico();
$clandpadrao                     = new cl_andpadrao();

$db_opcao                = 22;
$db_botao                = false;
$lSqlErro                = false;
$db117_db_cadattdinamico = '';

if (isset($oPost->incluir)) {
  
	db_inicio_transacao();
	
	if (!$lSqlErro) {
		
	  $clworkflowativ->db114_workflow   = $oPost->db112_sequencial;
	  $clworkflowativ->db114_descricao  = $oPost->db114_descricao;
	  $clworkflowativ->db114_ordem      = $oPost->db114_ordem;
	  $clworkflowativ->incluir(null);
	  
	  $db114_sequencial                 = $clworkflowativ->db114_sequencial;
	  
	  $erro_msg = $clworkflowativ->erro_msg;
	  if ($clworkflowativ->erro_status == 0) {
	    $lSqlErro = true;
	  }
	}
	
  if (!$lSqlErro) {
    
  	$clandpadrao->p53_codigo   = $oPost->p51_codigo;
    $clandpadrao->p53_coddepto = $oPost->p53_coddepto;
    $clandpadrao->p53_dias     = 1;
    $clandpadrao->p53_ordem    = $oPost->db114_ordem; 
    $clandpadrao->incluir($clandpadrao->p53_codigo, $clandpadrao->p53_ordem);
    
    $p53_codigo                = $clandpadrao->p53_codigo;
    $p53_ordem                 = $clandpadrao->p53_ordem;
    
    if ($clandpadrao->erro_status == 0) {
    	
      $lSqlErro = true;
      $erro_msg = $clandpadrao->erro_msg;
    }
  }
  
  if (!$lSqlErro) {
    
    $clworkflowativandpadrao->db115_workflowativ = $db114_sequencial;
    $clworkflowativandpadrao->db115_codigo       = $p53_codigo;
    $clworkflowativandpadrao->db115_ordem        = $p53_ordem;
    $clworkflowativandpadrao->incluir(null);
    if ($clworkflowativandpadrao->erro_status == 0) {
      
      $lSqlErro = true;
      $erro_msg = $clworkflowativandpadrao->erro_msg;
    }
  }
  
  db_fim_transacao($lSqlErro);
} else if (isset($oPost->alterar)) {
  
  db_inicio_transacao();
  
  if (!$lSqlErro) {
    
    $clworkflowativ->db114_sequencial = $oPost->db114_sequencial;
    $clworkflowativ->db114_descricao  = $oPost->db114_descricao;
    $clworkflowativ->db114_ordem      = $oPost->db114_ordem;
    $clworkflowativ->alterar($clworkflowativ->db114_sequencial);
    
    $erro_msg = $clworkflowativ->erro_msg;
    if ($clworkflowativ->erro_status == 0) {
      $lSqlErro = true;
    }
  }
  
  if (!$lSqlErro) {
    
    $clandpadrao->p53_codigo   = $oPost->p51_codigo;
    $clandpadrao->p53_coddepto = $oPost->p53_coddepto;
    $clandpadrao->p53_ordem    = $oPost->db114_ordem;
    $clandpadrao->alterar($clandpadrao->p53_codigo, $clandpadrao->p53_ordem);
    if ($clandpadrao->erro_status == 0) {
      
      $lSqlErro = true;
      $erro_msg = $clandpadrao->erro_msg;
    }
  }
  
  db_fim_transacao($lSqlErro);
} else if (isset($oPost->excluir)) {
  
  db_inicio_transacao();

  $sWhere                     = "workflowativandpadrao.db115_workflowativ = {$oPost->db114_sequencial}";
  $sCampos                    = "workflowativandpadrao.*";
  $sSqlWorkflowAtivAndPadrao  = $clworkflowativandpadrao->sql_query(null, $sCampos, null, $sWhere);
  $rsSqlWorkflowAtivAndPadrao = $clworkflowativandpadrao->sql_record($sSqlWorkflowAtivAndPadrao);
  if ($clworkflowativandpadrao->numrows > 0) {
    
    $oWorkflowAtivAndPadrao = db_utils::fieldsMemory($rsSqlWorkflowAtivAndPadrao, 0);
    
    if (!$lSqlErro) {
      
      $clworkflowativandpadrao->db115_sequencial = $oWorkflowAtivAndPadrao->db115_sequencial;
      $clworkflowativandpadrao->excluir($clworkflowativandpadrao->db115_sequencial);
      if ($clworkflowativandpadrao->erro_status == 0) {
        
        $lSqlErro = true;
        $erro_msg = $clworkflowativandpadrao->erro_msg;
      }
    }
    
    if (!$lSqlErro) {
      
      $clandpadrao->p53_codigo = $oWorkflowAtivAndPadrao->db115_codigo;
      $clandpadrao->p53_ordem  = $oWorkflowAtivAndPadrao->db115_ordem;
      $clandpadrao->excluir($clandpadrao->p53_codigo, $clandpadrao->p53_ordem);
      if ($clandpadrao->erro_status == 0) {
        
        $lSqlErro = true;
        $erro_msg = $clandpadrao->erro_msg;
      }
    }
    
    if (!$lSqlErro) {
      
      $sWhere                          = "workflowativdb_cadattdinamico.db117_workflowativ = {$oWorkflowAtivAndPadrao->db115_workflowativ}";
      $sCampos                         = "workflowativdb_cadattdinamico.*";
      $sSqlWorkflowAtivCadAttDinamico  = $clworkflowativdb_cadattdinamico->sql_query(null, $sCampos, "db117_sequencial", $sWhere);
      $rsSqlWorkflowAtivCadAttDinamico = $clworkflowativdb_cadattdinamico->sql_record($sSqlWorkflowAtivCadAttDinamico);
      if ($clworkflowativdb_cadattdinamico->numrows > 0) {
            
        $oWorkflowAtivCadAttDinamico = db_utils::fieldsMemory($rsSqlWorkflowAtivCadAttDinamico, 0);
        
        $clworkflowativdb_cadattdinamico->db117_sequencial = $oWorkflowAtivCadAttDinamico->db117_sequencial;
        $clworkflowativdb_cadattdinamico->excluir($clworkflowativdb_cadattdinamico->db117_sequencial);
        if ($clworkflowativdb_cadattdinamico->erro_status == 0) {
          
          $lSqlErro = true;
          $erro_msg = $clworkflowativdb_cadattdinamico->erro_msg;
        }
        
        if (!$lSqlErro) {
          
          try {
            
            $oDBAttDinamico = new DBAttDinamico($oWorkflowAtivCadAttDinamico->db117_db_cadattdinamico);
            $oDBAttDinamico->excluir();
          } catch (Exception $eException) {
            
            $lSqlErro = true;
            $erro_msg = $eException->getMessage();
          }
        }
      }
    }
    
    if (!$lSqlErro) {
      
      $clworkflowativ->db114_sequencial = $oWorkflowAtivAndPadrao->db115_workflowativ;
      $clworkflowativ->excluir($clworkflowativ->db114_sequencial);

      $erro_msg = $clworkflowativ->erro_msg;
      if ($clworkflowativ->erro_status == 0) {
        $lSqlErro = true;
      }
    }
  }
  
  db_fim_transacao($lSqlErro);
}

if (isset($oGet->db112_sequencial)) {
  
  $sWhere                = "workflow.db112_sequencial = {$oGet->db112_sequencial}";
  $sCampos               = "workflow.db112_sequencial, workflow.db112_descricao, ";
  $sCampos              .= "tipoproc.p51_codigo, tipoproc.p51_descr              ";
  $sSqlWorkflowTipoProc  = $clworkflowtipoproc->sql_query(null, $sCampos, null, $sWhere);
  $rsSqlWorkflowTipoProc = $clworkflowtipoproc->sql_record($sSqlWorkflowTipoProc); 
  if ($clworkflowtipoproc->numrows > 0) {
    db_fieldsmemory($rsSqlWorkflowTipoProc, 0);
  }
}

$sWhere               = "db114_workflow = {$db112_sequencial}";
$sSqlWorkflowAtiv     = $clworkflowativ->sql_query_file(null, "*", "db114_ordem", $sWhere);
$rsSqlWorkflowAtiv    = $clworkflowativ->sql_record($sSqlWorkflowAtiv);
$iNumRowsWorkflowAtiv = $clworkflowativ->numrows;

if (!isset($oPost->incluir) && !isset($oPost->alterar) && !isset($oPost->opcao)) {
  $db114_ordem        = ($iNumRowsWorkflowAtiv+1);
} else {
		
	if (isset($oPost->opcao)) {
	
	  $sWhere                     = "workflowativandpadrao.db115_workflowativ = {$oPost->db114_sequencial}";
	  $sCampos                    = "workflowativ.db114_sequencial, workflowativ.db114_ordem,                   ";
	  $sCampos                   .= "workflowativ.db114_descricao, andpadrao.p53_coddepto, db_depart.descrdepto ";
	  $sSqlWorkflowAtivAndPadrao  = $clworkflowativandpadrao->sql_query(null, $sCampos, null, $sWhere);
	  $rsSqlWorkflowAtivAndPadrao = $clworkflowativandpadrao->sql_record($sSqlWorkflowAtivAndPadrao);
	  if ($clworkflowativandpadrao->numrows > 0) {
	
	    $oWorkflowAtivAndPadrao = db_utils::fieldsMemory($rsSqlWorkflowAtivAndPadrao, 0);
	    $db114_sequencial = $oWorkflowAtivAndPadrao->db114_sequencial;
	    $db114_ordem      = $oWorkflowAtivAndPadrao->db114_ordem;
	    $db114_descricao  = $oWorkflowAtivAndPadrao->db114_descricao;
	    $p53_coddepto     = $oWorkflowAtivAndPadrao->p53_coddepto;
	    $descrdepto       = $oWorkflowAtivAndPadrao->descrdepto;
	  }
	} else {
		$db114_ordem = $clworkflowativ->db114_ordem;
	}
}

if (isset($db114_sequencial)) {
    
  $sWhere                          = "workflowativdb_cadattdinamico.db117_workflowativ = {$db114_sequencial}";
  $sCampos                         = "workflowativdb_cadattdinamico.db117_db_cadattdinamico";
  $sSqlWorkflowAtivCadAttDinamico  = $clworkflowativdb_cadattdinamico->sql_query(null, $sCampos, "db117_sequencial", $sWhere);
  $rsSqlWorkflowAtivCadAttDinamico = $clworkflowativdb_cadattdinamico->sql_record($sSqlWorkflowAtivCadAttDinamico);
  if ($clworkflowativdb_cadattdinamico->numrows > 0) {
        
    $oWorkflowAtivCadAttDinamico = db_utils::fieldsMemory($rsSqlWorkflowAtivCadAttDinamico, 0);
    $db117_db_cadattdinamico     = $oWorkflowAtivCadAttDinamico->db117_db_cadattdinamico;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js, widgets/windowAux.widget.js");
  db_app::load("widgets/dbcomboBox.widget.js, widgets/dbtextField.widget.js");
  db_app::load("classes/DBViewCadastroAtributoDinamico.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 80px;
  white-space: nowrap
}

#db112_descricao, #db114_descricao, #descrdepto, #p51_descr {
  width: 100%;
}

.marcaRetira {
   border-colappse: collapse;
   border-right: 1px inset black;
   border-bottom: 1px inset black;
   cursor: normal;
   font-family: Arial, Helvetica, sans-serif;
   font-size: 12px;
   background-color: #CCCDDD
}
 
.marcaSel {
   border-colappse: collapse;
   border-right: 1px inset black;
   border-bottom: 1px inset black;
   cursor: normal;
   font-family: Arial, Helvetica, sans-serif;
   font-size: 12px;
}

td.linhagrid {
  -moz-user-select: none;
  text-align: left;
}

#atividadesLancadas {
  height: 300px; 
  overflow: scroll; 
  overflow-x: hidden; 
  background-color: white;
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
        include(modification("forms/db_frmworkflowativ.php"));
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
  
	if (isset($erro_msg)) {
		
	  db_msgbox($erro_msg);
	  if ($clworkflowativ->erro_campo != "") {
	    
	    echo "<script> document.form1.".$clworkflowativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	    echo "<script> document.form1.".$clworkflowativ->erro_campo.".focus();</script>";
	  }
	}
}
?>