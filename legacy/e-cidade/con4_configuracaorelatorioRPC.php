<?php
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
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("model/linhaColunaRelatorio.model.php");
require_once("model/relatorioContabil.model.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("libs/JSON.php");

$oGet              = db_utils::postMemory($_GET);

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();
switch ($oParam->exec) {
  
  case "salvarParametros":
    
    try {
      
      db_inicio_transacao();
      $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
      $oParam->filters->observacao = db_stdClass::db_stripTagsJson(($oParam->filters->observacao));
      $oLinhaRelatorio->salvarParametrosDefault($oParam->filters);
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 2;
        
    }
    break;
    
  case "getParametrosPadrao" :

    $oLinhaRelatorio          = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
    $oRetorno->filter         = $oLinhaRelatorio->getParametrosPadrao();
    $oRetorno->lDesdobraLinha = false;
    break;

  case "salvarParametrosUsuario":
    
    try {
      
      db_inicio_transacao();
      $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
      $oParam->filters->observacao = db_stdClass::db_stripTagsJson(($oParam->filters->observacao));
      $oLinhaRelatorio->salvarParametros($oParam->filters);
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 2;
        
    }
    break;
  
  case "getParametrosUsuario" :

    $oLinhaRelatorio          = $oLinhaRelatorio  = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
    $oRetorno->filter         = $oLinhaRelatorio->getParametrosOrcamentoUsuario();
    $oRetorno->iRelatorio     = $oParam->relatorio;
    $oRetorno->iLinha         = $oParam->linha;
    $oRetorno->lDesdobraLinha = $oLinhaRelatorio->desdobraLinha();
    break;

  case "getRelatoriosPorPeriodos" :

    $oDaoOrcparamrelPeriodo = db_utils::getDao("orcparamrelperiodos");
    $sSqlPeriodos           = $oDaoOrcparamrelPeriodo->sql_query_file(null,"o113_orcparamrel", null,
                                                                     "o113_periodo = {$oParam->iCodigoPeriodo}"
                                                                       );
    $rsPeriodos             = $oDaoOrcparamrelPeriodo->sql_record($sSqlPeriodos);
    $oRetorno->itens        = db_utils::getColectionByRecord($rsPeriodos);                                                                        
    break;
    
     case "salvarParametroRelatorioUsuario":
    
    try {
      
      db_inicio_transacao();
      $oLinhaRelatorio = new relatorioContabil($oParam->iRelatorio, false);
      $oLinhaRelatorio->salvarParametrosUsuario($oParam->filters, db_getsession("DB_id_usuario"));
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 2;
        
    }
    break;
    
    case "getParametrosRelatorioUsuario" :
      
      $oRelatorio               = new relatorioContabil($oParam->iRelatorio, false);
      $oRetorno->filter         = $oRelatorio->getParametrosUsuario(db_getsession("DB_id_usuario"));
      $oRetorno->iRelatorio     = $oParam->iRelatorio;
      break;
    
    case "excluirParametrosOrcamentoUsuario" :
      
      db_inicio_transacao();
      try {
        
        $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
        $oLinhaRelatorio->excluirFiltroUsuario(db_getsession("DB_anousu"));
        db_fim_transacao(false);
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
      break;
      
    case "importarParametros" :
      
      db_inicio_transacao();
      try {
        
        $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
        $oLinhaRelatorio->importarParametros(db_getsession("DB_anousu"));
        db_fim_transacao(false);
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    break;
    
    case "getVinculoSigap" :
      
	    $oLinhaRelatorio      = $oLinhaRelatorio  = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
      $oRetorno->filter     = $oLinhaRelatorio->getVinculoSigap();
	    $oRetorno->iRelatorio = $oParam->relatorio;
	    $oRetorno->iLinha     = $oParam->linha;
    break;
    
  case "salvarVinculoSigap":
    
    try {
      
      db_inicio_transacao();
      
      $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
      $oParam->filters->contasigap = db_stdClass::db_stripTagsJson(($oParam->filters->contasigap));
      $oParam->filters->descricao  = db_stdClass::db_stripTagsJson(($oParam->filters->descricao));
      $oParam->filters->estrutural = db_stdClass::db_stripTagsJson(($oParam->filters->estrutural));
      $oLinhaRelatorio->salvarVinculoSigap($oParam->filters);
      
      $oRetorno->iRelatorio = $oParam->relatorio;
      $oRetorno->iLinha     = $oParam->linha;
      
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->message = urlencode(str_replace("\\n","\n",$eErro->getMessage()));
      $oRetorno->status  = 2;
    }
    break;
    
    case "excluirVinculoSigap":
      
      try {
        
        db_inicio_transacao();
        
        $oLinhaRelatorio = new linhaRelatorioContabil($oParam->relatorio, $oParam->linha);
        $oLinhaRelatorio->excluirVinculoSigap(db_getsession("DB_anousu"));
        
        $oRetorno->iRelatorio = $oParam->relatorio;
        $oRetorno->iLinha     = $oParam->linha;
        
        db_fim_transacao(false);
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
      break;
}
echo $oJson->encode($oRetorno);