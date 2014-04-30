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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("model/agendaPagamento.model.php");

$oGet     = db_utils::postMemory($_GET);
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
switch ($oParam->exec) {
  
  case "getNotas" :
    
    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sWhere  = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
    $sWhere .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
    $sWhere .= " and e97_codforma = 1";
    $sWhere .= " and k12_data is null";
    $sWhere .= " and e60_instit = ".db_getsession("DB_instit");
    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere .= " and e50_codord = {$oParam->params[0]->iOrdemIni}";
    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
      $sWhere .= " and e50_codord between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
    }
    
    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
      $sWhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {
      
      $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";
       
    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {
      
       $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
       $sWhere    .= " and e50_data <= '{$dtDataFim}'";
    }
    
    //Filtro para Empenho
    if ($oParam->params[0]->iCodEmp!= '') {
      
      if (strpos($oParam->params[0]->iCodEmp,"/")) {
        
        $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
        $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";
        
      } else {
        $sWhere .= " and e60_codemp = '{$oParam->params[0]->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
      }
      
    }
     
    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {
      $sWhere .= " and (e60_numcgm = {$oParam->params[0]->iNumCgm})";
    }
    
    $sJoin  = " left join empagenotasordem on e81_codmov  = e43_empagemov     ";
    $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
    $aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,true,false);
    if (count($aOrdensAgenda) > 0) {
      
      $oRetono->status           = 1;
      $oRetono->mensagem         = 1;
      $oRetono->aNotasLiquidacao = $aOrdensAgenda;
      echo $oJson->encode($oRetono);
      
    } else {
      
      $oRetono->status           = 2;
      $oRetono->mensagem         = "";
      echo $oJson->encode($oRetono);
      
    }
    break;
}