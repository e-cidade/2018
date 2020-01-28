<?php
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

session_start();
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
//include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = array();
switch ($oParam->exec) {
  
  case 'getReceitasByPeriodo' :
    
    
    $sDataInicial   = "{$oParam->iAno}-{$oParam->iMes}-01"; 
    $sDataFinal     = "{$oParam->iAno}-{$oParam->iMes}-".cal_days_in_month(CAL_GREGORIAN , $oParam->iMes, $oParam->iAno);
    $iMesCorrente   = date("m", db_getsession("DB_datausu"));
    $iAnoCorrente   = date("Y", db_getsession("DB_datausu"));
    $iDiaCorrente   = date("d", db_getsession("DB_datausu"));
    if ($iMesCorrente == $oParam->iMes && $iAnoCorrente == $oParam->iAno) {
      $sDataFinal     = date("Y-m-d", mktime(0,0,0,$oParam->iMes, $iDiaCorrente-2, $oParam->iAno));
                                                             
    }
    $rsReceitaSaldo = db_receitasaldo(11,1,3, true, "o70_instit = ".db_getsession("DB_instit") ,
                                      $oParam->iAno, $sDataInicial, $sDataFinal
                                     );
    $oRetorno->itens = db_utils::getCollectionByRecord($rsReceitaSaldo, true, false, true);
    break;
    
    case 'getDespesasByPeriodo' :
    
    
    $sWhere         = " o58_instit = ".db_getsession("DB_instit");
    if ($oParam->iOrgao != '') {
       $sWhere .= " and o58_orgao = {$oParam->iOrgao}";
    }
    $sDataInicial   = "{$oParam->iAno}-{$oParam->iMes}-01"; 
    $sDataFinal     = "{$oParam->iAno}-{$oParam->iMes}-".cal_days_in_month(CAL_GREGORIAN , $oParam->iMes, $oParam->iAno);
    $iMesCorrente   = date("m", db_getsession("DB_datausu"));
    $iAnoCorrente   = date("Y", db_getsession("DB_datausu"));
    $iDiaCorrente   = date("d", db_getsession("DB_datausu"));
    if ($iMesCorrente == $oParam->iMes && $iAnoCorrente == $oParam->iAno) {
      $sDataFinal     = date("Y-m-d", mktime(0,0,0,$oParam->iMes, $iDiaCorrente-2, $oParam->iAno));
                                                             
    }
    $rsDotacaoSaldo = db_dotacaosaldo(8, 1, 4, true, $sWhere,
                                      $oParam->iAno, $sDataInicial, $sDataFinal, 8, 0, false
                                     );
    $oRetorno->itens = db_utils::getCollectionByRecord($rsDotacaoSaldo, false, false, true);
    break;
  
  case "getOrgaosByAno" :
  
    $sSqlOrgaos  = "select distinct o40_orgao, o40_descr";
    $sSqlOrgaos .= "  from orcorgao ";
    $sSqlOrgaos .= " where o40_anousu = {$oParam->iAno}";
    $sSqlOrgaos .= "   and o40_instit = ".db_getsession("DB_instit");
    $sSqlOrgaos .= " order by o40_orgao";
    $rsOrgaos        = db_query($sSqlOrgaos);
    $oRetorno->itens = db_utils::getCollectionByRecord($rsOrgaos, false, false, true);
    break;
}
echo $oJson->encode($oRetorno);
?>