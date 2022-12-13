<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_saltes_classe.php");
require_once("model/contaTesouraria.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->aItens  = array();
$sWhere            = "";

switch ($oParam->exec) {

  case "reprocessarSaldo":
    
    if (isset($oParam->itenssel) && $oParam->itenssel != null) {
      $sWhere = "k13_conta in({$oParam->itenssel})";
    }
  
    $oSaltes    = new cl_saltes();
    $sSqlSaltes = $oSaltes->sql_query(null,"*",null,$sWhere);
    $rsSaltes   = $oSaltes->sql_record($sSqlSaltes);
    $iNumRows   = $oSaltes->numrows;

    db_inicio_transacao();
    $lErro  = false;
    for ($i = 0; $i < $iNumRows; $i++) {
      
    	$oConta         = db_utils::fieldsMemory($rsSaltes, $i);
      
      /*
       * verifica se a data de processamento e maior que a data da implantacao
       * se for anterior a data de processo recebe a data de implantacao  
       */
      $dtDataBase = implode("-", array_reverse(explode("/", $oParam->database)));
    	if ( $dtDataBase < $oConta->k13_dtimplantacao ){
      	$dtDataBase = $oConta->k13_dtimplantacao;
      }
    
      $sSqlSaldoData  = "select substr(fc_saltessaldo($oConta->k13_conta,";
      $sSqlSaldoData .= "                             '{$dtDataBase}', ";
      $sSqlSaldoData .= "                             '{$dtDataBase}', ";
      $sSqlSaldoData .= "                             null,".db_getsession("DB_instit")."), 41, 13) as saldo";
      $rsSaldoData    = db_query($sSqlSaldoData);
      $nSaldoSaltes   = db_utils::fieldsMemory($rsSaldoData,0)->saldo;
      $oSaltes->k13_conta  = $oConta->k13_conta;
      $oSaltes->k13_datvlr = $dtDataBase;
      $oSaltes->k13_vlratu = $nSaldoSaltes;
      $oSaltes->alterar($oConta->k13_conta);
      if ($oSaltes->erro_status == 0) {
        
        $lErro    = true;
        $sMsgErro = "Não foi possível atualizar o saldo conta {$oConta->k13_conta}\n{$oSaltes->erro_msg}";
        $oRetorno->message = urlencode($sMsgErro);
        $oRetorno->status  = 2;
                   
      }
    }
   
    db_fim_transacao($lErro);

    break;
    
    
  case "pesquisarSaldoContas":
    
  	$sWhere = null;
    if (isset($oParam->itenssel) && !empty($oParam->itenssel)) {
      $sWhere = "k13_conta in({$oParam->itenssel})";
    }
    $aItens  = contaTesouraria::getContasByFiltro($sWhere);
    
    foreach ($aItens as $oConta) {

    	$oContaRetorno  = new stdClass();
    	$oContaRetorno->k13_conta         = $oConta->getCodigoConta(); 
    	$oContaRetorno->k13_reduz         = $oConta->getCodigoReduzido();
    	$oContaRetorno->k13_descr         = urlencode($oConta->getDescricao());
    	$oContaRetorno->k13_saldo         = $oConta->getSaldoInicial();
    	$oContaRetorno->k13_ident         = $oConta->getIdentificacao();
    	$oContaRetorno->k13_vlratu        = $oConta->getValorAtualizado();
    	$oContaRetorno->k13_datvlr        = db_formatar($oConta->getDataAtualizacao(),"d");
    	$oContaRetorno->k13_limite        = $oConta->getDataLimite();
    	$oContaRetorno->k13_dtimplantacao = $oConta->getDataImplantacao();
    	
    	$oRetorno->aItens[] = $oContaRetorno;
    }
    
    break;
    
  case "implantarSaldo":
    
    $oSaldoContas = new contaTesouraria(null);
    try {
      
      db_inicio_transacao();
    
      foreach ($oParam->aSaldoContas as $oConta) {

      	$oContaTesouraria = new contaTesouraria($oConta->iNumconta);
      	$oContaTesouraria->implantarSaldo($oConta->dtData, $oConta->nSaldo); 
      }
      
      db_fim_transacao(false);  
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    
    break;
}

echo $oJson->encode($oRetorno);
?>