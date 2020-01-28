<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson        = new services_json();
$oParam       = $oJson->decode((str_replace("\\","",$_POST["json"])));

$iAnoFolha    = db_anofolha();
$iMesFolha    = db_mesfolha();
$iMaxSemestre = maxSemestre($iAnoFolha, $iMesFolha);

$oRetorno     = new stdClass();
$oRetorno->status         = 1;
$oRetorno->message        = '';
$oRetorno->aListaServidor = array();
switch ($oParam->exec) {
  
  case "clearSession":
    
  	/**
  	 * Destroi os registros gravados em sessão na checagem do cgm.
  	 */
    unset($_SESSION["oChecagemCGM"]);
    break;
	
  case "getServidoresVinculados":
    
  	/**
  	 * Consulta os servidores que estão vinculados a um responsável.
  	 */
    $oDaoRhResponsavelRegist   = db_utils::getDao("rhresponsavelregist");
    $sWhereRhResponsavelRegist = "rh108_rhresponsavel = {$oParam->iCodigoResponsavel}";
  	$sSqlRhResponsavelRegist   = $oDaoRhResponsavelRegist->sql_query(null, 
  	                                                                 "rh108_regist, cgm.z01_nome", 
  	                                                                 "rh108_sequencial", 
  	                                                                 $sWhereRhResponsavelRegist);
  	$rsSqlRhResponsavelRegist  = $oDaoRhResponsavelRegist->sql_record($sSqlRhResponsavelRegist);
    $oRetorno->aListaServidor  = db_utils::getColectionByRecord($rsSqlRhResponsavelRegist);  
    break;
    
  case "processar":
    
  	/**
  	 * Processa os dados conforme o tipo de folha selecionado no filtro.
  	 */
  	switch ($oParam->iTipoFolha) {
  		
  		case 1:
  			
  			/**
  			 * Processa os dados da tabela gerfsal tipo de folha (Salário).
  			 */
        try {
        	
	        db_inicio_transacao();

	        $oDaoGerFSal     = db_utils::getDao("gerfsal");
          $oDaoGerFCom     = db_utils::getDao("gerfcom");
	            
	        $sWhereGerFSal   = " gerfsal.r14_anousu = {$iAnoFolha}            ";
	        $sWhereGerFSal  .= " and gerfsal.r14_mesusu = {$iMesFolha}        ";
	        $sWhereGerFSal  .= " and rhresponsavelregist.rh108_status is true ";
	        if (isset($oParam->aMatriculas) && !empty($oParam->aMatriculas)) {
	          $sWhereGerFSal .= " and gerfsal.r14_regist in({$oParam->aMatriculas}) "; 
	        }
	        
	        $sSqlGerFSal     = $oDaoGerFSal->sql_query_servincsal(null, null, null, null, 
	                                                              "gerfsal.*", null, $sWhereGerFSal);                                                       
	        $rsSqlGerFSal    = $oDaoGerFSal->sql_record($sSqlGerFSal);
	        $iNumRowsGerFSal = $oDaoGerFSal->numrows;
	        
	        /**
	         * Inclui um novo gerfcom pra cada gerfsal porém vamos sempre gravar no campo r48_semest 
	         * o maior valor para o ano da folha somado de 1. (max(r48_semest)+1).
	         */
	        for ($iInd = 0; $iInd < $iNumRowsGerFSal; $iInd++) {
	          
	          $oGerFSal = db_utils::fieldsMemory($rsSqlGerFSal, $iInd);
	          
            $oDaoGerFCom->r48_anousu = $oGerFSal->r14_anousu;
            $oDaoGerFCom->r48_mesusu = $oGerFSal->r14_mesusu;
            $oDaoGerFCom->r48_regist = $oGerFSal->r14_regist;
            $oDaoGerFCom->r48_rubric = $oGerFSal->r14_rubric;
            $oDaoGerFCom->r48_valor  = $oGerFSal->r14_valor;
            $oDaoGerFCom->r48_pd     = $oGerFSal->r14_pd;
            $oDaoGerFCom->r48_quant  = $oGerFSal->r14_quant;
            $oDaoGerFCom->r48_lotac  = $oGerFSal->r14_lotac;
            $oDaoGerFCom->r48_semest = $iMaxSemestre;
            $oDaoGerFCom->r48_instit = $oGerFSal->r14_instit;
            $oDaoGerFCom->incluir($oDaoGerFCom->r48_anousu, $oDaoGerFCom->r48_mesusu, 
                                  $oDaoGerFCom->r48_regist, $oDaoGerFCom->r48_rubric);
            if ($oDaoGerFCom->erro_status == 0) {
            	throw new Exception($oDaoGerFCom->erro_msg);
            }
            
		        $oDaoGerFSal->excluir($oGerFSal->r14_anousu, $oGerFSal->r14_mesusu, 
		                              $oGerFSal->r14_regist, $oGerFSal->r14_rubric);
	          if ($oDaoGerFSal->erro_status == 0) {
	            throw new Exception($oDaoGerFSal->erro_msg);
	          }
	        }

	        db_fim_transacao(false);
        } catch (Exception $oErro) {
        	
        	db_fim_transacao(true);
        	$oRetorno->status  = 2;
          $oRetorno->message = urlencode(str_replace("\\n", "\n", $oErro->getMessage()));
        }
  			break;
  			
  		case 5:
  			
  	    /**
         * Processa os dados da tabela gerfs13 tipo de folha (13º Salário).
         */
        try {
          
          db_inicio_transacao();

          $oDaoGerFS13     = db_utils::getDao("gerfs13");
          $oDaoGerFCom     = db_utils::getDao("gerfcom");
              
          $sWhereGerFS13   = " gerfs13.r35_anousu = {$iAnoFolha}            ";
          $sWhereGerFS13  .= " and gerfs13.r35_mesusu = {$iMesFolha}        ";
          $sWhereGerFS13  .= " and rhresponsavelregist.rh108_status is true ";
          if (isset($oParam->aMatriculas) && !empty($oParam->aMatriculas)) {
            $sWhereGerFS13 .= " and gerfs13.r35_regist in({$oParam->aMatriculas}) "; 
          }
          
          $sSqlGerFS13     = $oDaoGerFS13->sql_query_servincsal13(null, null, null, null, 
                                                                  "gerfs13.*", null, $sWhereGerFS13);

          $rsSqlGerFS13    = $oDaoGerFS13->sql_record($sSqlGerFS13);
          $iNumRowsGerFS13 = $oDaoGerFS13->numrows;

          /**
           * Inclui um novo gerfcom pra cada gerfs13 porém vamos sempre gravar no campo r48_semest 
           * o maior valor para o ano da folha somado de 1. (max(r48_semest)+1).
           */
          for ($iInd = 0; $iInd < $iNumRowsGerFS13; $iInd++) {
            
            $oGerFS13 = db_utils::fieldsMemory($rsSqlGerFS13, $iInd);
            
            $oDaoGerFCom->r48_anousu = $oGerFS13->r35_anousu;
            $oDaoGerFCom->r48_mesusu = $oGerFS13->r35_mesusu;
            $oDaoGerFCom->r48_regist = $oGerFS13->r35_regist;
            $oDaoGerFCom->r48_rubric = $oGerFS13->r35_rubric;
            $oDaoGerFCom->r48_valor  = $oGerFS13->r35_valor;
            $oDaoGerFCom->r48_pd     = $oGerFS13->r35_pd;
            $oDaoGerFCom->r48_quant  = $oGerFS13->r35_quant;
            $oDaoGerFCom->r48_lotac  = $oGerFS13->r35_lotac;
            $oDaoGerFCom->r48_semest = $iMaxSemestre;
            $oDaoGerFCom->r48_instit = $oGerFS13->r35_instit;
            $oDaoGerFCom->incluir($oDaoGerFCom->r48_anousu, $oDaoGerFCom->r48_mesusu, 
                                  $oDaoGerFCom->r48_regist, $oDaoGerFCom->r48_rubric);
            if ($oDaoGerFCom->erro_status == 0) {
              throw new Exception($oDaoGerFCom->erro_msg);
            }
            
            $oDaoGerFS13->excluir($oGerFS13->r35_anousu, $oGerFS13->r35_mesusu, 
                                  $oGerFS13->r35_regist, $oGerFS13->r35_rubric);
            if ($oDaoGerFS13->erro_status == 0) {
              throw new Exception($oDaoGerFS13->erro_msg);
            }
          }
          
          db_fim_transacao(false);
        } catch (Exception $oErro) {
          
          db_fim_transacao(true);
          $oRetorno->status  = 2;
          $oRetorno->message = urlencode(str_replace("\\n", "\n", $oErro->getMessage()));
        }
  			break;
  	}
    break; 
}

/**
 * Retorna o maior valor para o ano da folha somado de 1.
 *
 * @param integer_type $iAnoFolha
 * @param integer_type $iMesFolha
 * @return $iMaxSemestre
 */
function maxSemestre($iAnoFolha, $iMesFolha) {
	
	$oDaoGerFCom     = db_utils::getDao("gerfcom");
	$sSqlGerFCom     = $oDaoGerFCom->sql_query_file($iAnoFolha, $iMesFolha, null, null, 
	                                                "(max(r48_semest)+1) as r48_semest", null, "");
	$rsSqlGerFCom    = $oDaoGerFCom->sql_record($sSqlGerFCom);
	$iNumRowsGerFCom = $oDaoGerFCom->numrows;
	$iMaxSemestre    = 1;
	
	if ($iNumRowsGerFCom > 0) {
		
		$oGerFCom     = db_utils::fieldsMemory($rsSqlGerFCom, 0);
		$iMaxSemestre = $oGerFCom->r48_semest;
		if (empty($iMaxSemestre)) {
			$iMaxSemestre = 1;
		}
	}
  
	return $iMaxSemestre;
}

echo $oJson->encode($oRetorno);