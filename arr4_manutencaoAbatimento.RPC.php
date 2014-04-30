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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

db_app::import('exceptions.*');

$oDaoAbatimentoArreckey    = db_utils::getDao("abatimentoarreckey");
$oDaoArreckey              = db_utils::getDao("arreckey");
$oDaoAbatimentoDisbanco    = db_utils::getDao("abatimentodisbanco");
$oDaoAbatimentoRecibo      = db_utils::getDao("abatimentorecibo");
$oDaoArrecantPgtoParcial   = db_utils::getDao("arrecantpgtoparcial");
$oDaoAbatimento            = db_utils::getDao("abatimento");
$oDaoRecibo                = db_utils::getDao("recibo");

$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch ($oParam->exec) {
  
	case "getOrigensAbatimento":

		$oRetorno = new stdClass;
		
		$sSqlBuscaOrigens = "select distinct 
		                            arrecant.k00_numpre, 
		                            arrecant.k00_numpar, 
		                            arrecant.k00_receit, 
		                            tabrec.k02_descr,
		                            arrecant.k00_hist,
		                            histcalc.k01_descr,
		                            arrecant.k00_tipo,
		                            arretipo.k00_descr 
		                       from abatimentorecibo 
		                            inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal 
		                             left join arrecant     on arrecant.k00_numpre       = db_reciboweb.k99_numpre 
		                                                   and arrecant.k00_numpar       = db_reciboweb.k99_numpar
		                             left join tabrec       on tabrec.k02_codigo         = arrecant.k00_receit 
		                             left join histcalc     on histcalc.k01_codigo       = arrecant.k00_hist 
		                             left join arretipo     on arretipo.k00_tipo         = arrecant.k00_tipo                       
		                             left join arreckey     on arreckey.k00_numpre       = arrecant.k00_numpre 
		                                                   and arreckey.k00_numpar       = arrecant.k00_numpar  
		                      where abatimentorecibo.k127_abatimento = {$oParam->iAbatimento} 
		                      order by arrecant.k00_numpre, arrecant.k00_numpar, arrecant.k00_receit";
		$rsOrigensAbatimento = $oDaoAbatimentoArreckey->sql_record($sSqlBuscaOrigens);
		if ($oDaoAbatimentoArreckey->numrows == 0) {
			
			$oRetorno->lErro   = true;
			
		} else {

			$oRetorno->lErro   = false;
			$oRetorno->aOrigens = db_utils::getColectionByRecord($rsOrigensAbatimento);			
			
		}
		
		echo $oJson->encode($oRetorno);		
		
	break;

	case "alterarOrigensAbatimento":
		
		$aRetorno = array();
		$aRetorno["status"]  = 1;
		$aRetorno["message"] = urlencode("Processamento efetuado com sucesso");
		
		$aArreckey = array();
		
		try {
			
			db_inicio_transacao();
			
			/*
			 * A lógica deste processamento é simples
			 *
       *  1 - excluímos os dados da abatimentoarreckey anteriores   
			 *  2 - verificamos se os registros marcados possuem arreckey, caso contrário geramos arreckey.
			 *  3 - realizamos o vinculo entre o abatimento e o arreckey
			 */
			$oDaoAbatimentoArreckey->excluir(null, "k128_abatimento = {$oParam->iAbatimento}");
			if ($oDaoAbatimentoArreckey->erro_status == "0") {
				$sMsg  = "Erro ao excluir dados da abatimentoarreckey\\n";
				$sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}";
				$sMsg .= "Erro do banco  : ".pg_last_error();
				throw new Exception($sMsg);
			}
			
			foreach ($oParam->aOrigens as $oOrigens) {
				
				$sWhere  = " k00_numpre     = {$oOrigens->numpre}";
				$sWhere .= " and k00_numpar = {$oOrigens->numpar}";
				$sWhere .= " and k00_receit = {$oOrigens->receit}";
				$sWhere .= " and k00_hist   = {$oOrigens->hist}  ";
				$sWhere .= " and k00_tipo   = {$oOrigens->tipo}  ";
				$sSqlVerificaArreckey = $oDaoArreckey->sql_query_file(null,"k00_sequencial","",$sWhere);
				$rsVerificaArreckey   = $oDaoArreckey->sql_record($sSqlVerificaArreckey);
				if ($oDaoArreckey->numrows > 0) {
					$iArreckey = db_utils::fieldsMemory($rsVerificaArreckey, 0)->k00_sequencial;
				} else {
					
					$oDaoArreckey->k00_numpre = $oOrigens->numpre;
          $oDaoArreckey->k00_numpar = $oOrigens->numpar;
          $oDaoArreckey->k00_receit = $oOrigens->receit;
          $oDaoArreckey->k00_hist   = $oOrigens->hist;
          $oDaoArreckey->k00_tipo   = $oOrigens->tipo;
          $oDaoArreckey->incluir(null);
          $iArreckey = $oDaoArreckey->k00_sequencial;
          if ($oDaoArreckey->erro_status == "0") {
          	$sMsg = "Erro ao incluir registros na arreckey\\n";
				    $sMsg .= "Erro da classe : {$oDaoArreckey->erro_msg}";
				    $sMsg .= "Erro do banco  : ".pg_last_error();
          	throw new Exception($sMsg);
          }
          
				}
				
				$oDaoAbatimentoArreckey->k128_arreckey     = $iArreckey;
        $oDaoAbatimentoArreckey->k128_abatimento   = $oParam->iAbatimento;
        $oDaoAbatimentoArreckey->k128_valorabatido = $oOrigens->valor;
        $oDaoAbatimentoArreckey->k128_correcao     = "0";
        $oDaoAbatimentoArreckey->k128_juros        = "0";
        $oDaoAbatimentoArreckey->k128_multa        = "0";
        $oDaoAbatimentoArreckey->incluir(null);
        if ($oDaoAbatimentoArreckey->erro_status == "0") {
        	 $sMsg = "Erro ao incluir registros na abatimentoarreckey\\n";
        	 $sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}";
        	 $sMsg .= "Erro do banco  : ".pg_last_error();
        	 throw new Exception($sMsg);
        }
        
			}
			
			db_fim_transacao(false);
			
		} catch (Exception $oErro) {
			db_fim_transacao(true);
			
			$aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($eErro->getMessage()); 
		} 
		
		echo $oJson->encode($aRetorno);
		
	break;
	
	case "exluirCredito":
		
		$aRetorno = array();
		$aRetorno["status"]  = 1;
		$aRetorno["message"] = urlencode("Processamento efetuado com sucesso");
		
		try {
			
			db_inicio_transacao();
			
      $oDaoAbatimentoArreckey->excluir(null,"k128_abatimento = {$oParam->iAbatimento}");  
      if ($oDaoAbatimentoArreckey->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da abatimentoarreckey\\n";
      	$sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
      
      $oDaoAbatimentoDisbanco->excluir(null,"k132_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoDisbanco->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da abatimentodisbanco\\n";
      	$sMsg .= "Erro da classe : {$oDaoAbatimentoDisbanco->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
      
      $sWhere = "k00_numpre in (select k127_numprerecibo 
      		                        from abatimentorecibo 
                                 where k127_abatimento = {$oParam->iAbatimento} )";
      $oDaoRecibo->excluir(null,$sWhere);
      if ($oDaoRecibo->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da recibo\\n";
      	$sMsg .= "Erro da classe : {$oDaoRecibo->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
          
      $oDaoAbatimentoRecibo->excluir(null,"k127_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoRecibo->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da abatimentorecibo\\n";
      	$sMsg .= "Erro da classe : {$oDaoAbatimentoRecibo->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
      
      $oDaoArrecantPgtoParcial->excluir(null, "arrecantpgtoparcial.k00_abatimento = {$oParam->iAbatimento}");
      if ($oDaoArrecantPgtoParcial->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da abatimentopgtoparcial\\n";
      	$sMsg .= "Erro da classe : {$oDaoArrecantPgtoParcial->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
      
      $oDaoAbatimento->excluir($oParam->iAbatimento);
      if ($oDaoAbatimento->erro_status == "0") {
      	$sMsg  = "Erro ao excluir registros da abatimento\\n";
      	$sMsg .= "Erro da classe : {$oDaoAbatimento->erro_msg}";
      	$sMsg .= "Erro do banco  : ".pg_last_error();
      	throw new Exception($sMsg);
      }
      
      db_fim_transacao(false);
      
		} catch (Exception $oErro) {
			
			db_fim_transacao(true);
				
			$aRetorno["status"]  = 2;
			$aRetorno["message"] = urlencode($eErro->getMessage());
			
		}

		echo $oJson->encode($aRetorno);
		
	break;		
  
}
?>