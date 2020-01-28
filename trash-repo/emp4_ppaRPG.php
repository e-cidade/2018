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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
require_once("model/ppa.model.php");
include("libs/JSON.php");

$oGet              = db_utils::postMemory($_GET);
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();
if ($oParam->exec == "getParametros") {
   
  if ($oParam->params->fonte != "") {
    
    $sSqlParametros  = "select o03_descricao, ";
    $sSqlParametros .= "       o03_anoreferencia,";
    $sSqlParametros .= "       o03_anoorcamento,";
    $sSqlParametros .= "       o03_sequencial, ";
    $sSqlParametros .= "       o04_orccenarioeconomicoparam ";
    $sSqlParametros .= "  from orccenarioeconomicoparam ";
    $sSqlParametros .= "       left join orccenarioeconomicoconplano on o04_orccenarioeconomicoparam = o03_sequencial";
    $sSqlParametros .= "       and o04_conplano = '{$oParam->params->conplano}' and o04_anousu = o03_anoorcamento";
    $sSqlParametros .= " where o03_instit = ".db_getsession("DB_instit");
    $sSqlParametros .= " order by o03_anoreferencia,o03_sequencial";
    $rsParametros    = db_query($sSqlParametros);
    if ($rsParametros && pg_num_rows($rsParametros) > 0) {
      
      $oRetorno->status = 1;
      $oRetorno->itens  = db_utils::getColectionByRecord($rsParametros, false, false, true);  
        
    }
  } else {
    
    $oRetorno->status = 2;
    $oRetorno->message = "preencha a fonte";
    
  }
  echo $oJson->encode($oRetorno);  
} else if ($oParam->exec == "salvarParametros") {
  
  db_inicio_transacao();
  $oDaoParametrosReceita = db_utils::getDao("orccenarioeconomicoconplano");
  if ($oParam->iTipo == 1) {
    
    $oDaoOrcFonte = db_utils::getDao("orcfontes");
    $sSql = $oDaoOrcFonte->sql_query_previsao(null,
                                              null,
                                              "o57_codfon as conplano",
                                              null,
                                              "o57_anousu = ".db_getsession("DB_anousu")."
                                              and o57_fonte like '".ppa::criaContaMae($oParam->iEstrutural)."%'	"
                                              );
                                              
                                                       
  } else if ($oParam->iTipo == 2) {
    
    $oDaoOrcElemento = db_utils::getDao("orcelemento");
    $sSql = $oDaoOrcElemento->sql_query_conplano(null,
                                      null,
                                      "distinct c60_codcon as conplano",
                                      null,
                                      "o56_anousu=". db_getsession("DB_anousu")."
                                      and c60_estrut like '".ppa::criaContaMae($oParam->iEstrutural)."%'");
  }
  //die($sSql);
  $rsIncluir = db_query($sSql);
  if ($rsIncluir) {
    
    $aFontes = db_utils::getColectionByRecord($rsIncluir);
    foreach ($aFontes as $oFonte) { 
      
      $oDaoParametrosReceita->excluir(null, "o04_conplano  = {$oFonte->conplano}");
      if ($oDaoParametrosReceita->erro_status == 0) {
    
         $oRetorno->message  = urlencode("{$oDaoParametrosReceita->erro_status}");
         $oRetorno->status   = 2;
         exit;
      }
      if ($oRetorno->status != 2) {
         
        foreach ($oParam->aParametros as $oParametro) {
          
          $sSqlConplano  = "select 1 from conplano";
          $sSqlConplano .= " where c60_codcon = {$oFonte->conplano}";
          $sSqlConplano .= "   and c60_anousu = {$oParametro->o03_anoorcamento}";
          $rsConplano    = db_query($sSqlConplano) ;
          
          if ($rsConplano && pg_num_rows($rsConplano) > 0) {
          
            $oDaoParametrosReceita->o04_orccenarioeconomicoparam = $oParametro->o03_sequencial;      
            $oDaoParametrosReceita->o04_anousu                   = $oParametro->o03_anoorcamento;      
            $oDaoParametrosReceita->o04_conplano                 = $oFonte->conplano;
            $oDaoParametrosReceita->incluir(null);
            
            if ($oDaoParametrosReceita->erro_status == 0) {
    
              $oRetorno->message  = urlencode("{$oDaoParametrosReceita->erro_status}");
              $oRetorno->status   = 2;
              break;
            }
          }
        }
      }
    }
  }
  if ($oRetorno->status == 2) {
    db_fim_transacao(true);
  } else {
    db_fim_transacao(false);
  }
  echo $oJson->encode($oRetorno);  
} else if ($oParam->exec == "getParametros") {
   
 
   
  if ($oParam->params->fonte != "") {
    
    $sSqlParametros  = "select o03_descricao, ";
    $sSqlParametros .= "       o03_anoreferencia,";
    $sSqlParametros .= "       o03_anoorcamento,";
    $sSqlParametros .= "       o03_sequencial, ";
    $sSqlParametros .= "       o04_orccenarioeconomicoparam ";
    $sSqlParametros .= "  from orccenarioeconomicoparam ";
    $sSqlParametros .= "       left join orccenarioeconomicoconplano on o04_orccenarioeconomicoparam = o03_sequencial";
    $sSqlParametros .= "       and o04_conplano = '{$oParam->params->conplano}' and o04_anousu = o03_anoorcamento";
    $sSqlParametros .= " where o03_instit = ".db_getsession("DB_instit");
    $sSqlParametros .= " order by o03_anoreferencia,o03_sequencial";
    $rsParametros    = db_query($sSqlParametros);
    if ($rsParametros && pg_num_rows($rsParametros) > 0) {
      
      $oRetorno->status = 1;
      $oRetorno->itens  = db_utils::getColectionByRecord($rsParametros, false, false, true);  
        
    }
  } else {
    
    $oRetorno->status = 2;
    $oRetorno->message = "preencha a fonte";
    
  }
   
} else if ($oParam->exec == "ProcessaEstimativa") {
  
  db_inicio_transacao();
  try {
     
    $oPPA      = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    $iAnoIncio =  $oParam->iAnoInicio - 3;
    
    /**
     * Processamos a base de calculo.
     */
    for ($i = $iAnoIncio; $i < $oParam->iAnoInicio; $i++) {
       $oPPA->processaBaseCalculo($i);
    }
    /*
     * Processamos a estimativa
     */
    $oPPA->processarEstimativasGlobais($oParam->iAnoInicio, $oParam->iAnoFim); 
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode("Erro [".$eErro->getCode()."] ".$eErro->getMessage());
    db_fim_transacao(true);
    
  }
  echo $oJson->encode($oRetorno); 
} else if ($oParam->exec == "getQuadroEstimativa") {
  
   $oPPA                  = new ppa($oParam->iCodigoLei, $oParam->iTipo);
   try {

     $oRetorno->itens       = $oPPA->getQuadroEstimativas($oParam->estrutural);
     $oRetorno->status      = 1;
     $oRetorno->message     = "";
     
   } catch (Exception $eErro) {
  
     $oRetorno->status      = 2;
     $oRetorno->message     = urlencode("Erro[".$eErro->getCode()."] - " .$eErro->getMessage()); 
     
   }
   echo $oJson->encode($oRetorno);
    
} else if ($oParam->exec == "saveEstimativa") {
   
  $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo);
  try {
    
    $lContas = true;
    $oRetorno->valor = $oParam->nValor;
    $oRetorno->iAno  = $oParam->iAno;
    $oRetorno->nValorOriginal  = $oParam->nValorOriginal;
    db_inicio_transacao();
    if ($oParam->lDesdobrar) {
      
      $aDesdobramentos = $oPPA->getDesdobramentos($oPPA->criaContaMae($oParam->iEstrutural),$oParam->iAno);
      foreach ($aDesdobramentos as $oDesbramento) {
        
        $nValor = $oParam->nValor * ($oDesbramento->o60_perc/100);
        $oPPA->saveEstimativa($oDesbramento->o57_codfon, $oParam->iAno,$nValor, $oParam->iTipo);
        $oConta = new stdClass();
        $oConta->iEstrutural = $oDesbramento->o57_fonte;
        $oConta->valor       = $nValor;
        $oRetorno->itens[]   = $oConta;
        
      }
    } else {
        
      
      $oPPA->saveEstimativa($oParam->iCodCon, $oParam->iAno,$oParam->nValor, $oParam->iTipo);
      /**
       * Criamos um array, com os anos que devemos atualizar
       */
       $oRetorno->nValorOriginal  = $oParam->nValorOriginal;
       
     }
     while ($lContas != false) {
         
         $iConta = str_pad(db_le_mae_rec($oParam->iEstrutural),15,0,STR_PAD_RIGHT);
         $oConta  = new stdClass();
         $oConta->iEstrutural = $iConta;
         $oConta->valor       = 0;
         $oRetorno->itens[]   = $oConta;
         $oParam->iEstrutural = $iConta;
         if (db_le_mae_rec($iConta,true) == 1) {
           $lContas = false;
         }
       }
     db_fim_transacao(false);
    
  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "reprocessaEstimativa") {
  
  try {
    
    db_inicio_transacao();
    $oPPA          = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    $aRetornoItens = array();
    foreach ($oParam->aAnos as $iAno) {

      $aDesdobramentos      = $oPPA->getDesdobramentos($oPPA->criaContaMae($oParam->iEstrutural),$iAno);
      if (count($aDesdobramentos) > 0 && $oParam->iTipo == 1)  {
         
        foreach ($aDesdobramentos as $oDesbramento) {
          $aRetornoItens[] =  $oPPA->processarEstimativas($oDesbramento->o57_codfon, $iAno);
        }
      } else {
        
        $oValores        = new stdClass();
        $oValores->valor = $oPPA->processarEstimativas($oParam->iCodCon, $iAno);
        $oValores->ano   = $iAno;
        $aRetornoItens[] =  $oValores;
        
      }
    }
    
    $oRetorno->itens = $aRetornoItens; 
    $oRetorno->iEstrutural = $oParam->iEstrutural; 
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "calculaValorEstimativa") {
  
  try {
    
    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    for ($i = $oParam->iAno+1; $i <= $oParam->iAnoFinal; $i++) {
      
      $nValorParametro       = $oPPA->getAcrescimosEstimativa($oParam->iCodCon, $i);
      $oValorCorrigido       = new stdClass;
      $oValorCorrigido->iAno = $i;
      $oValorCorrigido->nValor = $oParam->nValor;
      if ($nValorParametro != 0) {
        $oValorCorrigido->nValor *= $nValorParametro;
      }
      $oParam->nValor          = round($oValorCorrigido->nValor);
      $oValorCorrigido->nValor = round($oValorCorrigido->nValor);
      $oRetorno->itens[]= $oValorCorrigido;
    }
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }
  echo $oJson->encode($oRetorno);
  
} else if ($oParam->exec == "adicionaEstimativaDespesa") {
  
  try {
    
    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    foreach ($oParam->aAnos as $oAnoDotacao) {
      
      $oDotacao                         = $_SESSION["dotacaoestimativa"];
      $oDotacao->iAno                   = $oAnoDotacao->iAno;
      $oDotacao->nValor                 = $oAnoDotacao->nValor;
      $oDotacao->o08_elemento           = $oParam->iElemento;
      $oDotacao->o08_recurso            = $oParam->iRecurso;
      $oDotacao->o08_localizadorgastos  = $oParam->iLocalizadorgasto;
      if ($oDotacao->nValor != 0){
        $oPPA->adicionarEstimativa($oDotacao);  
      }
    }
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
    
  }
  echo trim($oJson->encode($oRetorno));
} else if ($oParam->exec == "adicionaEstimativaReceita") {

  try {
    
    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    foreach ($oParam->aAnos as $oAnoReceita) {
      
      $oReceita           = new stdClass();
      $oReceita->iAno     = $oAnoReceita->iAno;
      $oReceita->nValor   = $oAnoReceita->nValor;
      $oReceita->iCodCon  = $oParam->iCodCon;
      if ($oReceita->nValor != 0){
        $oPPA->adicionarEstimativa($oReceita);  
      }
    }
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
    
  }
   echo trim($oJson->encode($oRetorno));
} else if ($oParam->exec == "reProcessaEstimativaGlobal") {
  
  db_inicio_transacao();
  try {
     
    $oPPA      = new ppa($oParam->iCodigoLei, $oParam->iTipo);
    $iAnoIncio =  $oParam->iAnoInicio - 3;
    /*
     * Processamos a estimativa
     */
    $oPPA->processarEstimativasGlobais($oParam->iAnoInicio, $oParam->iAnoFim); 
    db_fim_transacao(false);
    
  } catch (Exception $eErro) {
    
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode("Erro [".$eErro->getCode()."] ".$eErro->getMessage());
    db_fim_transacao(true);
    
  }
  echo $oJson->encode($oRetorno); 
}
?>