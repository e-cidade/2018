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
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/lancamentoContabil.model.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
db_app::import("orcamento.suplementacao.*");
db_app::import("Dotacao");
$aParametros         = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
$oParametroOrcamento = $aParametros[0];

$oGet              = db_utils::postMemory($_GET);
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();
switch ($oParam->exec) {
  
  case "getDadosDotacaoPPA":
   
    $oDaoPPADotacao   = db_utils::getDao("ppadotacao");
    $sSqlDadosDotacao = $oDaoPPADotacao->sql_query_estimativa(null,
                                                              "*",
                                                             null,
                                                             "o07_sequencial ={$oParam->iEstimativa}"
                                                             );
    $rsDadosDotacao = db_query($sSqlDadosDotacao);
    if (pg_num_rows($rsDadosDotacao) > 0) {
      $oRetorno->dadosdotacao = db_utils::fieldsMemory($rsDadosDotacao, 0, false, false, true);                                                           
    }
    break;
    
  case "getDadosReceitaPPA":
    
    $oDaoPPAReceita   = db_utils::getDao("ppaestimativareceita");
    $sSqlDadosReceita = $oDaoPPAReceita->sql_query_analitica(null,
                                                              "fc_estruturalreceitappa(o06_anousu,
                                                                                   o06_sequencial, 
                                                                                    c61_instit) as estrutural,
                                                               o06_sequencial,
                                                               c61_codigo,
                                                               o57_descr,
                                                               o15_descr",
                                                             null,
                                                             "o06_sequencial = {$oParam->iEstimativa} 
                                                             and c61_instit=".db_getsession("DB_instit")
                                                             );
    $rsDadosReceita = db_query($sSqlDadosReceita);
    if (pg_num_rows($rsDadosReceita) > 0) {
      $oRetorno->dadosreceita = db_utils::fieldsMemory($rsDadosReceita, 0, false, false, true);                                                           
    }
    break;
    case "getSuplementacoesProjeto":
      
      /**
       * pesquisa todas as suplementacoes do decreto/projeto
       */
      $oDaoSuplementacao  = db_utils::getDao("orcsuplem");
      $sSqlSuplementacoes = $oDaoSuplementacao->sql_query_sup(null, 
                                                              "o46_codsup",
                                                              null,
                                                              "o46_codlei={$oParam->iProjeto} 
                                                              and o49_codsup is null "
                                                              );
      $rsSuplementacoes = $oDaoSuplementacao->sql_record($sSqlSuplementacoes);
      $oRetorno->itens  = array();
      if ($oDaoSuplementacao->numrows > 0) {
        
        for ($i = 0; $i < $oDaoSuplementacao->numrows; $i++) {
          
          $iSuplementacao = db_utils::fieldsMemory($rsSuplementacoes, $i)->o46_codsup;
          $oSuplementacao = new Suplementacao($iSuplementacao);
          $oSup           = new stdClass();
          $oSup->codigo   = $oSuplementacao->getCodigo();
          $oSup->valorsuplementado   = $oSuplementacao->getvalorSuplementacao();
          $oSup->valorreduzido       = $oSuplementacao->getValorReducao();
          $oSup->tipo                = $oSuplementacao->getTipo();
          $oSup->descricaotipo       = urlencode($oSuplementacao->getDescricaoTipo());
          $oRetorno->itens[]         = $oSup;
          unset($oSuplementacao);
        }                                                                    
      }
      break;
      
    case 'processarSuplementacoes':
      
      try {

        db_inicio_transacao();
        foreach ($oParam->aSuplementacoes as $iSuplementacao) {
          $dDataProcessamento = implode("-", array_reverse(explode("/", $oParam->dataprocessamento)));
          $oSuplementacao = new Suplementacao($iSuplementacao);
          $oSuplementacao->processar($dDataProcessamento);
          
          if ($oParam->lFecharProcesso == true) {
	          $oDaoOrcProjLan = db_utils::getDao("orcprojlan");
	          $oDaoOrcProjLan->o51_codproj    = $oParam->iProjeto;
	          $oDaoOrcProjLan->o51_data       = $dDataProcessamento;
	          $oDaoOrcProjLan->o51_id_usuario = db_getsession('DB_id_usuario');
	          $oDaoOrcProjLan->incluir($oParam->iProjeto);
          }
          
        }
        db_fim_transacao(false);
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
      break;
}
echo $oJson->encode($oRetorno);
?>