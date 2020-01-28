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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("model/DeclaracaoQuitacao.model.php");

$oJson        = new services_json();

$oParam       = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno     = new stdClass();

$oRetorno->status  = 1;

$oRetorno->message = '';

switch($oParam->exec) {
  
  case 'listaExercicios':
    
    $oDeclQuitacao = new DeclaracaoQuitacao();
    
    $oDeclQuitacao->setOrigem($oParam->origem);
    $oDeclQuitacao->setCodOrigem($oParam->codigo);
    
    if($oParam->origem == 1) {
      
      $oDeclQuitacao->setTipoCgm($oParam->regracgm == 1 ? true : false);
      
    }  
    
    if(count($oDeclQuitacao->getExerciciosDeclaracao()) > 0) {
      
      $oRetorno->exerc = $oDeclQuitacao->getExerciciosDeclaracao();
      
    } else {
            
      $oRetorno->status  = 0;

      $oRetorno->message = "Nenhum registro encontrado!";
    }
    
    echo $oJson->encode($oRetorno);
    
    break; //exerciciosLiberados
    
  case 'listaDeclaracoes':
    
    $oDeclQuitacao = new DeclaracaoQuitacao();
    
    $oDeclQuitacao->setCodOrigem($oParam->codigo);
     
    if(($oParam->origem == 'cgm') OR ($oParam->origem == 'somentecgm')) {
      
      $oDeclQuitacao->setOrigem(1);
      $oDeclQuitacao->setTipoCgm($oParam->origem == 'somentecgm' ? true : false);
      
    } elseif ($oParam->origem == 'matric') {
      
      $oDeclQuitacao->setOrigem(2);
      
    } elseif ($oParam->origem == 'inscr') {
      
      $oDeclQuitacao->setOrigem(3);
      
    }
    
    if(count($oDeclQuitacao->getDeclaracoesOrigem()) > 0) {
      
      $oRetorno->aDeclaracoes = $oDeclQuitacao->getDeclaracoesOrigem();
      
    } else {
      
      $oRetorno->status  = 0;

      $oRetorno->message = "Nenhum registro encontrado!";
      
    }
    
    $oRetorno->sOrigem = $oParam->origem;
    
    echo $oJson->encode($oRetorno);
    
    break;//getDeclaracoes
    
  case 'detalhesDeclaracao':

    try {
      
      $oDeclQuitacao = new DeclaracaoQuitacao($oParam->iCodDeclaracao);
    
	    $oRetorno->iCodDeclaracao = $oDeclQuitacao->getCodDeclaracao();
	    $oRetorno->iExercicio     = $oDeclQuitacao->getExercicio();
	    $oRetorno->sNomeCgm       = $oDeclQuitacao->getNomeCgm();
	    $oRetorno->sNomeOrigem    = $oDeclQuitacao->getNomeOrigem();
	    $oRetorno->iCodOrigem     = $oDeclQuitacao->getCodOrigem();
	    $oRetorno->dData          = $oDeclQuitacao->getDataDeclaracao();
	    $oRetorno->sUsuario       = $oDeclQuitacao->getUsuario();
	    $oRetorno->iSituacao      = $oDeclQuitacao->getSituacao();
	    $oRetorno->iAnoMesImpressao  = $oDeclQuitacao->getAnoMesImpressao();
	     
	    
	    $oRetorno->aDebitos       = $oDeclQuitacao->getDebitosDeclaracao();
    	
    } catch (Exception $e) {
      
      $oRetorno->status = 0;
      
      $oRetorno->message = 'Nenhum registro encontrado';
      
    }
    
    echo $oJson->encode($oRetorno);
    
    break; /*fim getDeclaracoesReg*/

    
  case 'anulaDeclaracao': 
    
    try {
      
      db_inicio_transacao();
      
      $oAnulacaoDeclaracaoQuitacao = db_utils::getDao('declaracaoquitacaocancelamento');
      
      $oAnulacaoDeclaracaoQuitacao->ar32_declaracaoquitacao = $oParam->declaracao;
      $oAnulacaoDeclaracaoQuitacao->ar32_id_usuario         = db_getsession('DB_id_usuario');
      $oAnulacaoDeclaracaoQuitacao->ar32_datacancelamento   = date("Y-m-d",db_getsession("DB_datausu"));
      $oAnulacaoDeclaracaoQuitacao->ar32_hora               = db_hora();
      $oAnulacaoDeclaracaoQuitacao->ar32_obs                = $oParam->observacao;
      $oAnulacaoDeclaracaoQuitacao->ar32_automatico         = 'False';
      
      $oAnulacaoDeclaracaoQuitacao->incluir(null);
      
      if($oAnulacaoDeclaracaoQuitacao->erro_status == '0') {
        throw new Exception();
      } 
        
      $oDeclaracaoQuitacao = db_utils::getDao('declaracaoquitacao');
      
      $oDeclaracaoQuitacao->ar30_sequencial = $oParam->declaracao;
      $oDeclaracaoQuitacao->ar30_situacao = 2;
      
      $oDeclaracaoQuitacao->alterar($oParam->declaracao);
      
      if($oDeclaracaoQuitacao->erro_status == '0') {       
        throw new Exception();
      }       
      
      db_fim_transacao();
  
    } catch (Exception $eErro) {
        
      $oRetorno->status = 0;
      
      $oRetorno->message = "Declaração de Quitacao numero {$oParam->declaracao} não anulada";;
      
      db_fim_transacao();
      
    }
    
    echo $oJson->encode($oRetorno);
    
    break;

}