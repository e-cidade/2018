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
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("model/empenhoFolha.model.php");
include("libs/db_liborcamento.php");
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();
if ($oParam->exec == "anularEmpenho") {
  
  /**
   * selecionamos todos os empenhos do tipo que tenham empenho gerados e anulamos
   */
  $sSqlEmpenhos   = "SELECT rh72_sequencial, ";
  $sSqlEmpenhos  .= "       rh72_coddot, ";
  $sSqlEmpenhos  .= "       rh72_codele, ";
  $sSqlEmpenhos  .= "       rh72_unidade, ";
  $sSqlEmpenhos  .= "       rh72_orgao, ";
  $sSqlEmpenhos  .= "       rh72_projativ, ";
  $sSqlEmpenhos  .= "       rh72_anousu, ";
  $sSqlEmpenhos  .= "       rh72_mesusu, ";
  $sSqlEmpenhos  .= "       rh72_recurso, ";
  $sSqlEmpenhos  .= "       rh72_siglaarq,";
  $sSqlEmpenhos  .= "       round(sum(rh73_valor), 2) as valorretencao ";
  $sSqlEmpenhos  .= "  from rhempenhofolha "; 
  $sSqlEmpenhos  .= "       inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial "; 
  $sSqlEmpenhos  .= "       inner join rhempenhofolharubrica  on rh73_sequencial     = rh81_rhempenhofolharubrica ";
  $sSqlEmpenhos  .= "       inner join rhpessoalmov           on rh73_seqpes     = rh02_seqpes  ";
  $sSqlEmpenhos  .= "                                        and rh73_instit     = rh02_instit ";
  $sSqlEmpenhos  .= "       inner join  rhempenhofolhaempenho on rh72_sequencial = rh76_rhempenhofolha ";
  $sSqlEmpenhos  .= "     and rh72_tipoempenho = {$oParam->iTipo}";
  $sSqlEmpenhos  .= "     and rh73_tiporubrica = 1";
  $sSqlEmpenhos  .= "     and rh73_instit = " . db_getsession("DB_instit");
  $sSqlEmpenhos  .= "     and rh72_anousu      = {$oParam->iAnoFolha}"; 
  $sSqlEmpenhos  .= "     and rh72_mesusu      = {$oParam->iMesFolha}"; 
  $sSqlEmpenhos  .= "     and rh72_siglaarq    = '{$oParam->sSigla}'";
  if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
    
    $sListaRescisoes = implode(",", $oParam->aRescisoes);
    $sSqlEmpenhos .= " and rh73_seqpes in({$sListaRescisoes})"; 
  }
  $sSqlEmpenhos  .= "   group by rh72_sequencial,  ";
  $sSqlEmpenhos  .= "            rh72_coddot,  ";
  $sSqlEmpenhos  .= "            rh72_codele, ";
  $sSqlEmpenhos  .= "            rh72_unidade, ";
  $sSqlEmpenhos  .= "            rh72_orgao, ";
  $sSqlEmpenhos  .= "            rh72_projativ, ";
  $sSqlEmpenhos  .= "            rh72_mesusu, ";
  $sSqlEmpenhos  .= "            rh72_anousu, ";
  $sSqlEmpenhos  .= "            rh72_recurso, ";
  $sSqlEmpenhos  .= "            rh72_siglaarq";
  $rsDadosEmpenho     = db_query($sSqlEmpenhos);
  $aEmpenhos          = db_utils::getColectionByRecord($rsDadosEmpenho);
  if (count($aEmpenhos) == 0) {
   
    $oRetorno->status  = 2;    
    $oRetorno->message = "Não foram encontrados empenhos gerados";
        
  } else {
    
    try {

      db_inicio_transacao();
      foreach ($aEmpenhos as $oEmpenho) {
        
        $oEmpenhoFolha = new empenhoFolha($oEmpenho->rh72_sequencial);
        $oEmpenhoFolha->estornarEmpenho();
        
      }
      /**
       * Marcamos as rescisoes como não Empenhadas
       */
      if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
     
        foreach ($oParam->aRescisoes as $iRescisao) {
        
          $oDaoPesRescisao = db_utils::getDao("rhpesrescisao");
          $oDaoPesRescisao->rh05_empenhado = "false";
          $oDaoPesRescisao->rh05_seqpes    = $iRescisao;
          $oDaoPesRescisao->alterar($iRescisao);
        }
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      
    }
  }
}
echo $oJson->encode($oRetorno);
?>