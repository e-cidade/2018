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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMensagem = '';

try {

	switch($oParam->sAcao) {
	
	  case 'BuscaInstituicoes' :
	    
	    /**
	     * Realiza a busca das instituições
	     */
	    $oDaoInstituicao     = db_utils::getDao('rhpessoal');
	    $sCampos             = " codigo, nomeinst, db21_ativo";
	    $sQueryInstituicoes  = $oDaoInstituicao->sql_queryInsntituicoesServidoresVinculo ($oParam->iMesCompetencia, $oParam->iAnoCompetencia);
	    $rsQueryInstituicoes = db_query($sQueryInstituicoes);
	    
	    if (!$rsQueryInstituicoes) {
	      throw new DBException("Erro ao buscar os dados sobre as instituições");
	    }
	    
	    $aInstituicoes = db_utils::getColectionByRecord($rsQueryInstituicoes, false, false, true);
	    
	    /**
	     * Percorre todas as instituições separando por ativas e inativas
	     */
	    foreach ($aInstituicoes as $oInstituicao) {
	      
	      if ($oInstituicao->ativo == 't') {
	        $oRetorno->aInstituicaoesAtivas[$oInstituicao->codigo]    = $oInstituicao;
	      } else {
	        $oRetorno->aInstituicaoesInativas[$oInstituicao->codigo]  = $oInstituicao;
	      }
	    }
	    
	 	break;
	    
	  case 'processar' :
	  	
	  	require_once('model/pessoal/calculoatuarial/cnm/CalculoAtuarialCNM.model.php');
	  	require_once('model/pessoal/ServidorRepository.model.php');
	  	
	  	$iAnoFolha             = $oParam->iAno;
	  	$iMesFolha             = $oParam->iMes;
	  	$aAssentamentos        = $oParam->aAssentamentos;
	  	$aCargoProfessores     = $oParam->aCargoProfessores; 
	  	$aInstituicoesAtivos   = $oParam->aInstituicoesAtivos;
	  	$aInstituicoesInativos = $oParam->aInstituicoesInativos;
	  	$aArquivos             = $oParam->aArquivos;
	  	
	    $oCalculoAtuarialCNM   = new CalculoAtuarialCNM();
	    $oCalculoAtuarialCNM->setAnoFolha($iAnoFolha);
	    $oCalculoAtuarialCNM->setMesFolha($iMesFolha);
	    $oCalculoAtuarialCNM->setAssentamentos($aAssentamentos);
	    $oCalculoAtuarialCNM->setCargosProfessores($aCargoProfessores);
	    $oCalculoAtuarialCNM->setInstituicoesAtivos($aInstituicoesAtivos);
	    $oCalculoAtuarialCNM->setInstituicoesInativos($aInstituicoesInativos);
	    $oCalculoAtuarialCNM->setTiposArquivos($aArquivos);
	    
	    $oRetorno->aArquivos = $oCalculoAtuarialCNM->processar();
	    
	    foreach ($oRetorno->aArquivos as $iArquivo => $sCaminhoArquivo) {
	    	$oRetorno->aArquivos[$iArquivo] = urlencode($sCaminhoArquivo);
	    }   
	    
	  break;
	
	}

} catch (Exception $oExeption) {
	
	$oRetorno->iStatus  = 2;
	$oRetorno->sMensagem = urlencode($oExeption->getMessage());
}

echo $oJson->encode($oRetorno);