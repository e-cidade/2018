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
require_once("libs/JSON.php");  
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/std/DBPessoal.model.php");

$oJson                = new services_json();
$oParametros          = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';
$oHoje                = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
define('MENSAGENS', 'recursoshumanos.pessoal.pes2_consultaferias.');

try {

  switch ($oParametros->sExecucao) {
    
    /**
     * Busca periodos arquisitivos por Servidor
     */
    case 'getPeriodosPorServidor' :

      try{

        $iCodigoServidor      = $oParametros->iCodigoServidor;
        $aPeriodosAquisitivos = PeriodoAquisitivoFerias::getPeriodosPorServidor(new Servidor($iCodigoServidor));
      
        if( count($aPeriodosAquisitivos) == 0 ){
          throw new BusinessException(_M( MENSAGENS . 'nenhuma_periodo_encontrado'));
        }
      
        $oRetorno->aPeriodosAquisitivos = array();
        
        foreach ($aPeriodosAquisitivos as $oPeriodo){
          
          $oDadosRetorno                             = new stdClass();
          $oDadosRetorno->iCodigoPeriodoAquisitivo   = $oPeriodo->getCodigo();
          $oDadosRetorno->dDataInicial               = $oPeriodo->getDataInicial()->getDate(DBDate::DATA_PTBR);
          $oDadosRetorno->dDataFinal                 = $oPeriodo->getDataFinal()->getDate(DBDate::DATA_PTBR);
          $oDadosRetorno->iFaltas                    = $oPeriodo->getFaltasPeriodoAquisitivo();
          $oDadosRetorno->iDiasGozados               = $oPeriodo->getDiasGozados();
          $oDadosRetorno->iDiasAbono                 = $oPeriodo->getDiasAbonados();
          $oDadosRetorno->iSaldo                     = $oPeriodo->getSaldoDiasDireito();
          $oDadosRetorno->iSaldoAvo                  = DBPessoal::getQuantidadeAvos($oPeriodo->getDataInicial(),
                                                                                    $oPeriodo->getDataFinal());
         	$oDadosRetorno->lDireitoFerias 						 = $oPeriodo->hasDireitoFerias();
          $oDadosRetorno->sObservacao                = $oPeriodo->getObservacao();
          $oRetorno->aPeriodosAquisitivos[]          = $oDadosRetorno;          
        }
        
        echo $oJson->encode($oRetorno);
        exit;    
      } catch (Exception $oException) {
      
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($oException->getMessage());
        echo $oJson->encode($oRetorno);
        exit;
      }
      
    break;
    
    /**
     * Busca periodos de gozo por Servidor
     */
    case 'getPeriodosGozoServidor' :
    
    	require_once('model/pessoal/ferias/PeriodoGozoFerias.model.php');
    	
    	try{
    
        $iPeriodoAquisitivo	  = $oParametros->iCodigoPeriodoAquisitivo;
        $oPeriodoAquisitivo   = new PeriodoAquisitivoFerias($iPeriodoAquisitivo);
        $aPeriodosGozo 				= $oPeriodoAquisitivo->getPeriodosGozo();
        
    		if( count($aPeriodosGozo) == 0 ){
				  throw new BusinessException(_M( MENSAGENS . 'nenhuma_periodo_encontrado'));
			  }
    
        $oRetorno->aPeriodosGozo        = array();

        foreach ( $aPeriodosGozo as $oPeriodoGozo ) {

   		    $oDadosRetorno                     = new stdClass();
   		    $oDadosRetorno->iCodigoPeriodoGozo = $oPeriodoGozo->getCodigoPeriodo();
    	    $oDadosRetorno->iDiasAbono         = $oPeriodoGozo->getDiasAbono();
    	    $oDadosRetorno->iDiasGozados       = $oPeriodoGozo->getDiasGozo();
    	    $oDadosRetorno->dPeriodoInicial    = $oPeriodoGozo->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
    	    $oDadosRetorno->dPeriodoFinal      = $oPeriodoGozo->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);
    	    $oDadosRetorno->sAnoMes            = $oPeriodoGozo->getMesPagamento().'/'.$oPeriodoGozo->getAnoPagamento();
    	    $oDadosRetorno->sFolha             = $oPeriodoGozo->getTipoPonto() == 1 ? "Salário" : "Complementar";
    	    $oDadosRetorno->sPagaterco         = $oPeriodoGozo->isPagaTerco()       ? "Sim"     : "Não";
    	    $oRetorno->aPeriodosGozo[]         = $oDadosRetorno;
        }
    		echo $oJson->encode($oRetorno);
    		exit;
    	} catch (Exception $oException) {
    
    		$oRetorno->iStatus   = 2;
    		$oRetorno->sMensagem = urlencode($oException->getMessage());
    		echo $oJson->encode($oRetorno);
    		exit;
    	}
    
    	break;
    	
    	/**
    	 * Busca Rubricas por periodo de gozo
    	 */
    	case 'getPeriodosGozoRubricas' :
    	
    		require_once('model/pessoal/ferias/ComposicaoPontoFerias.model.php');
    		 
    		try{
    	
    			$oRetorno->aRegistrosPonto = array();
    			$iCodigoPeriodoGozo	       = $oParametros->iCodigoPeriodoGozo;
    			$oPeriodoGozo 			       = new PeriodoGozoFerias($iCodigoPeriodoGozo);
    			$aRegistrosPonto           = $oPeriodoGozo->getComposicao()->getRegistros();
    			
    			if( count($aRegistrosPonto) == 0 ){
    				throw new BusinessException(_M( MENSAGENS . 'nenhuma_rubrica_encontrada'));
    			}
    	
    			foreach ( $aRegistrosPonto as $oRegistroPonto ) {
    				
    				$oDadosRetorno                    = new stdClass();
    				$oDadosRetorno->sRubrica          = $oRegistroPonto->getRubrica()->getCodigo();
    				$oDadosRetorno->sDescricaoRubrica = urlencode($oRegistroPonto->getRubrica()->getDescricao());	
    				$oDadosRetorno->nQuantidade       = $oRegistroPonto->getQuantidade();
    				$oDadosRetorno->nValor            = $oRegistroPonto->getValor();
    				$oRetorno->aRegistrosPonto[]      = $oDadosRetorno;
    			}
    			
    			echo $oJson->encode($oRetorno);
    			exit;
    		} catch (Exception $oException) {
    	
    			$oRetorno->iStatus   = 2;
    			$oRetorno->sMensagem = urlencode($oException->getMessage());
    			echo $oJson->encode($oRetorno);
    			exit;
    		}
    	
    		break;
  }
  
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);