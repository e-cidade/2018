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
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_workflowativ_classe.php");
require_once("classes/db_workflowativandpadrao_classe.php");
require_once("classes/db_workflowativdb_cadattdinamico_classe.php");
require_once("classes/db_andpadrao_classe.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass(); 
$oRetorno->status = 1;

$clworkflowativ                  = new cl_workflowativ();
$clworkflowativandpadrao         = new cl_workflowativandpadrao();
$clworkflowativdb_cadattdinamico = new cl_workflowativdb_cadattdinamico();
$clandpadrao                     = new cl_andpadrao();

switch ($oParam->exec) {
	
  case "verificaAtividadesLancadas":
    
      $sWhere                        = "workflowativ.db114_workflow = {$oParam->codworkflow}";
      $sOrderBy                      = "workflowativ.db114_ordem";
      $sCampos                       = "db114_sequencial, db114_descricao, db114_ordem, p53_codigo, p53_coddepto, p53_dias";
      $sSqlWorkflowAtivAndPadrao     = $clworkflowativandpadrao->sql_query(null, $sCampos, $sOrderBy, $sWhere);
      $rsSqlWorkflowAtivAndPadrao    = $clworkflowativandpadrao->sql_record($sSqlWorkflowAtivAndPadrao);
      $oRetorno->aAtividadesLancadas = db_utils::getColectionByRecord($rsSqlWorkflowAtivAndPadrao, false, false, true);
      break;
      
  case "atualizarOrdemAtividades":
    
	    $lSqlErro = false;
	    
	    db_inicio_transacao();
	    
      if (!$lSqlErro) {
          
        $sWhere = "db115_codigo = {$oParam->iCodAndPadrao}";
        $clworkflowativandpadrao->excluir(null, $sWhere);
        if ($clworkflowativandpadrao->erro_status == 0) {
                
          $lSqlErro          = true;
          $oRetorno->message = urlencode(str_replace("\\n", "\n", $clworkflowativandpadrao->erro_msg)); 
          $oRetorno->status  = 2;
        }
      }
            
      if (!$lSqlErro) {
              
        $clandpadrao->p53_codigo = $oParam->iCodAndPadrao;
        $clandpadrao->excluir($clandpadrao->p53_codigo, null);
        if ($clandpadrao->erro_status == 0) {
                
          $lSqlErro          = true;
          $oRetorno->message = urlencode(str_replace("\\n", "\n", $clandpadrao->erro_msg)); 
          $oRetorno->status  = 2;
        }
      }
	    
	    foreach ($oParam->aAtividadesLancadas as $oAtividadesLancadas) {
	
				if (!$lSqlErro) {
			    
			    $clworkflowativ->db114_sequencial = $oAtividadesLancadas->iCodAtividade;
			    $clworkflowativ->db114_ordem      = $oAtividadesLancadas->iOrdemNova;
			    $clworkflowativ->alterar($clworkflowativ->db114_sequencial);
			    
			    if ($clworkflowativ->erro_status == 0) {
			    	
			      $lSqlErro          = true;
			      $oRetorno->message = urlencode(str_replace("\\n", "\n", $clworkflowativ->erro_msg)); 
			      $oRetorno->status  = 2;
			    }
			  }

        if (!$lSqlErro) {
              
          $clandpadrao->p53_codigo   = $oParam->iCodAndPadrao;
          $clandpadrao->p53_coddepto = $oAtividadesLancadas->iCodDepto;
          $clandpadrao->p53_dias     = 1;
          $clandpadrao->p53_ordem    = $oAtividadesLancadas->iOrdemNova;
          $clandpadrao->incluir($clandpadrao->p53_codigo, $clandpadrao->p53_ordem);
          if ($clandpadrao->erro_status == 0) {
                
            $lSqlErro          = true;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $clandpadrao->erro_msg)); 
            $oRetorno->status  = 2;
          }
        }
        
	      if (!$lSqlErro) {
              
          $clworkflowativandpadrao->db115_workflowativ = $oAtividadesLancadas->iCodAtividade;
          $clworkflowativandpadrao->db115_codigo       = $oParam->iCodAndPadrao;
          $clworkflowativandpadrao->db115_ordem        = $oAtividadesLancadas->iOrdemNova;
          $clworkflowativandpadrao->incluir(null);
          if ($clworkflowativandpadrao->erro_status == 0) {
                
            $lSqlErro          = true;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $clworkflowativandpadrao->erro_msg)); 
            $oRetorno->status  = 2;
          }
        }
	    }
			  
	    db_fim_transacao($lSqlErro);
      break;
      
  case "salvarRelacaoLancaAtributos":
    
  	  $lSqlErro = false;
  	
  	  db_inicio_transacao();  
  	  
      $sWhere                          = "workflowativdb_cadattdinamico.db117_db_cadattdinamico = {$oParam->codworkflowativ}";
      $sCampos                         = "workflowativdb_cadattdinamico.db117_db_cadattdinamico";
      $sSqlWorkflowAtivCadAttDinamico  = $clworkflowativdb_cadattdinamico->sql_query(null, $sCampos, "db117_sequencial", $sWhere);
      $rsSqlWorkflowAtivCadAttDinamico = $clworkflowativdb_cadattdinamico->sql_record($sSqlWorkflowAtivCadAttDinamico);
      if ($clworkflowativdb_cadattdinamico->numrows == 0) {
              
        $clworkflowativdb_cadattdinamico->db117_workflowativ      = $oParam->codworkflowativ;
        $clworkflowativdb_cadattdinamico->db117_db_cadattdinamico = $oParam->codattdinamico;
        $clworkflowativdb_cadattdinamico->incluir(null);
        if ($clworkflowativdb_cadattdinamico->erro_status == 0) {
            
        	$lSqlErro          = true;
          $oRetorno->message = urlencode(str_replace("\\n", "\n", $clworkflowativdb_cadattdinamico->erro_msg)); 
          $oRetorno->status  = 2;
        }
      }
      
      db_fim_transacao($lSqlErro);
      break;
}

echo $oJson->encode($oRetorno);
?>