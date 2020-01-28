<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("model/itemSolicitacao.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/AutorizacaoEmpenho.model.php"));
require_once(modification("classes/solicitacaocompras.model.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

switch($oParam->exec) {

  case "pesquisarSolicitacoes":
    
    $sWhere = " pc10_instit = ".db_getsession("DB_instit");
    if (isset($oParam->filtros->iLicitacao) && !empty($oParam->filtros->iLicitacao)) {
      $sWhere .= " and l20_codigo = {$oParam->filtros->iLicitacao}";
    }
    if ((isset($oParam->filtros->iNumeroSolicitacaoInicial) && !empty($oParam->filtros->iNumeroSolicitacaoInicial)) &&
         (isset($oParam->filtros->iNumeroSolicitacaoFinal) && !empty($oParam->filtros->iNumeroSolicitacaoFinal))) {
        
      $sBetween = "between {$oParam->filtros->iNumeroSolicitacaoInicial} and {$oParam->filtros->iNumeroSolicitacaoFinal}";
      $sWhere  .= " and pc10_numero {$sBetween}";
      
    } else if (isset($oParam->filtros->iNumeroSolicitacaoInicial) && !empty($oParam->filtros->iNumeroSolicitacaoInicial)) {
      $sWhere  .= " and pc10_numero = {$oParam->filtros->iNumeroSolicitacaoInicial}";
    }

    if ((isset($oParam->filtros->dtDataSolicitacaoInicial) && !empty($oParam->filtros->dtDataSolicitacaoInicial)) && 
        (isset($oParam->filtros->dtDataSolicitacaoFinal) && !empty($oParam->filtros->dtDataSolicitacaoFinal))) {
      
      $sDataInicial = implode("-", array_reverse(explode("/", $oParam->filtros->dtDataSolicitacaoInicial)));
      $sDataFinal   = implode("-", array_reverse(explode("/", $oParam->filtros->dtDataSolicitacaoFinal)));
      $sWhere  .= " and pc10_data between '{$sDataInicial}' and '{$sDataFinal}'";
    } else if (isset($oParam->filtros->dtDataSolicitacaoInicial) && !empty($oParam->filtros->dtDataSolicitacaoInicial)) {
      
      $sDataInicial = implode("-", array_reverse(explode("/", $oParam->filtros->dtDataSolicitacaoInicial)));
      $sWhere  .= " and pc10_data =  '{$sDataInicial}'";
    }
    if (isset($oParam->filtros->aSolicitacoes) && count($oParam->filtros->aSolicitacoes) > 0) {
      $sWhere  .= " and pc10_numero in(".implode(", ", $oParam->filtros->aSolicitacoes).")";
    }
    $sWhere .= " and pc10_solicitacaotipo in (1, 2, 5)"; 
    $sWhere .= " group by pc10_numero,  pc10_data, pc10_resumo, pc10_solicitacaotipo";

    $oDaoSolicita     = db_utils::getDao("solicita");
    
    $sCamposSolicita  = " distinct             ";
    $sCamposSolicita .= "pc10_numero,          ";          
    $sCamposSolicita .= "pc10_data,            ";      
    $sCamposSolicita .= "pc10_resumo,          ";       
    $sCamposSolicita .= "pc10_solicitacaotipo, ";
    $sCamposSolicita .= "array_to_string(array_accum(distinct pc13_coddot||'/'||pc13_anousu),', ')  as pc13_coddot";
   
    $sSqlDadosSolicitacao = $oDaoSolicita->sql_query_licitacao_dotacao(null, $sCamposSolicita, null, $sWhere);
    $rsDadosSolicitacao   = $oDaoSolicita->sql_record($sSqlDadosSolicitacao);
    
    $aSolicitacoes        =  db_utils::getCollectionByRecord($rsDadosSolicitacao, false, false, false);
    $aDadosSolicitacao    = array();
    $lIemSemDotacao       = 0;
    
    foreach ($aSolicitacoes as $iIndSolicitacoes => $oValorSolicitacoes){
    
      $oDados                 = new stdClass();
      
      /*
       * verificamos se a solicitacao possui algum item sem dotacao
       */
      $lIemSemDotacao = itemSolicitacao::verificaItemSolicitacaoSemDotacao($oValorSolicitacoes->pc10_numero);
      
      $sResumo                = $oValorSolicitacoes->pc10_resumo;
      $oDados->solicitacao    = $oValorSolicitacoes->pc10_numero;
      $oDados->dtEmis         = db_formatar($oValorSolicitacoes->pc10_data, "d");
      $oDados->dotacoes       = $oValorSolicitacoes->pc13_coddot;  
      $oDados->resumo         = urlencode(substr($oValorSolicitacoes->pc10_resumo, 0, 100));
      $oDados->lIemSemDotacao = $lIemSemDotacao;
      $aDadosSolicitacao[] = $oDados;  
    }
    
    $oRetorno->aSolicitacoes = $aDadosSolicitacao;   
    
    break;
    
  case "getDotacoes": 

    $aDotacoesItens       = array();
    $oDadosSolicitacao    = db_utils::getDao("solicitem");
    $whereItensDotacao    = "pc10_numero = {$oParam->iCodigoSolicitacao}";
    $sCamposItensDotacao  = "pc13_anousu,     ";
    $sCamposItensDotacao .= "pc13_coddot,     ";
    $sCamposItensDotacao .= "pc13_valor,      ";
    $sCamposItensDotacao .= "pc13_quant,      ";
    $sCamposItensDotacao .= "pc13_sequencial, ";
    $sCamposItensDotacao .= "pc01_codmater,   ";
    $sCamposItensDotacao .= "pc11_codigo,     ";
    $sCamposItensDotacao .= "pc01_descrmater, ";
    $sCamposItensDotacao .= "o56_elemento,    ";
    $sCamposItensDotacao .= "pc11_seq         ";

    $sSqlItensDotacao = $oDadosSolicitacao->sql_query_pcmater_dotacao(null, 
                                                                      $sCamposItensDotacao,
                                                                      "pc13_coddot, pc11_seq, pc01_codmater", 
                                                                      $whereItensDotacao
                                                                      );
    
    $rsItensDotacao   = $oDadosSolicitacao->sql_record($sSqlItensDotacao);
    
    if ($oDadosSolicitacao->numrows == 0) {
      
      $oRetorno->message = "Não existe itens para esta solicitação.";
      $oRetorno->status  = 2;
    } else {
      
      $iNumRows = $oDadosSolicitacao->numrows;
      for ($i = 0; $i < $iNumRows; $i++) {
        
        $oItensDotacao  = db_utils::fieldsMemory($rsItensDotacao, $i, false, false, true);
        $iCodigoDotacao = "d".$oItensDotacao->pc13_coddot.$oItensDotacao->pc13_anousu;
        if (!isset($aDotacoesItens[$iCodigoDotacao])) {
          
          $sElemento = substr($oItensDotacao->o56_elemento, 0, 7);
          
          $oDotacao              = new stdClass();
          $oDotacao->iDotacao    = $oItensDotacao->pc13_coddot;
          $oDotacao->iAnoDotacao = $oItensDotacao->pc13_anousu;
          $oDotacao->sElemento   = "$sElemento";  
          $oDotacao->aItens      = array();
          $oDotacao->lAutorizado = "false";
          
          if (AutorizacaoEmpenho::verificaItemAutorizado($oItensDotacao->pc11_codigo,
                                                         $oItensDotacao->pc13_coddot,
                                                         $oParam->iCodigoSolicitacao) ) {
            
            $oDotacao->lAutorizado = 'true';
          } 
          
          $aDotacoesItens[$iCodigoDotacao] = $oDotacao;
        } else {
          $oDotacao = $aDotacoesItens[$iCodigoDotacao];
        }
        
        /*
         * enquanto percorre os itens, 
         * verificamos se eles possuem autorização
         * se possuir não será exibido
         */
        
        if ( !AutorizacaoEmpenho::verificaItemAutorizado($oItensDotacao->pc11_codigo, 
                                                         $oItensDotacao->pc13_coddot, 
                                                         $oParam->iCodigoSolicitacao) ) {
          /*
           * caso o elemento seja vazio, significa que a solicitação nao tem dotação
          * logo, buscamos o elemento baseado no item
          */
          if ($sElemento == '') {
          
            $oItemSolicitacao = new itemSolicitacao($oItensDotacao->pc11_codigo);
            $sElemento = substr($oItemSolicitacao->getDesdobramento(), 0, 7);
            
          }
          
          $oItem                     = new stdClass();
          $oItem->iItem              = $oItensDotacao->pc01_codmater;
          $oItem->iOrdem             = $oItensDotacao->pc11_seq;
          $oItem->sNomeItem          = $oItensDotacao->pc01_descrmater;
          $oItem->iDotacao           = $oItensDotacao->pc13_coddot;
          $oItem->nValor             = $oItensDotacao->pc13_valor;
          $oItem->nQuantidade        = $oItensDotacao->pc13_quant;
          $oItem->iAnoDotacao        = $oItensDotacao->pc13_anousu;
          $oItem->iDotacaoSequencial = $oItensDotacao->pc13_sequencial;
          $oItem->iCodigoItem        = $oItensDotacao->pc11_codigo;
          $oItem->lAlterado          = false;
          $oItem->sElemento          = $sElemento;
          $oDotacao->aItens[]        = $oItem;
        } 
      }
        
    }
    
    $oRetorno->aDotacoes  = $aDotacoesItens;
    
    $oRetorno->iAnoSessao = db_getsession("DB_anousu");
    break;
    
  case "alteraDotacoes":

    try {

      db_inicio_transacao();
      
      
      $iSolicitacao = $oParam->iCodigoSolicitacao;
      foreach ($oParam->aItens as $oItem) {
         
          $oItemSolicitacao = new itemSolicitacao($oItem->iCodigoItem);
          $oItemSolicitacao->alterarDotacao($oItem->iCodigoDotacaoItem, $oItem->iCodigoDotacao, $oItem->iAnoDotacao);
      }
      
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
    
}
echo $oJson->encode($oRetorno);