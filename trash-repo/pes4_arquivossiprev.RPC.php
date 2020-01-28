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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("classes/db_rharquivossiprev_classe.php");
include("fpdf151/fpdf.php");

//db_app::import("pessoal.arquivos.siprev.ArquivoSiprevBase");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevServidor");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevDependentes");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevOrgao");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevCarreira");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevCargos");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevEscritorXML");
db_app::import("pessoal.arquivos.siprev.ArquivoSiprevEscritor");


$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";
$oRhArquivossiprev = new cl_rharquivossiprev();


switch($oParam->exec) {

  case 'Lista' :
    
    $oRetorno->dados = array(); 
    $sSqlRhArquivossiprev = $oRhArquivossiprev->sql_query("","*","rh94_sequencial ASC","");
    $rsRhArquivossiprev   = $oRhArquivossiprev->sql_record($sSqlRhArquivossiprev);
    //$aArquivos = db_utils::getColectionByRecord($rsRhArquivossiprev, false, false, true);
    $aArquivos = db_utils::getColectionByRecord($rsRhArquivossiprev);
    $oRetorno->dados = $aArquivos;  
    
  break;

  case 'Gerar' :
  	
  	if (!isset($_SESSION['erro_servidores'])) {
  		$_SESSION['erro_servidores'] = array(); 
  	}
  	if(!isset($_SESSION['erro_dependentes'])) {
  		$_SESSION['erro_dependentes'] = array();
  	}
  	
    $sArquivoGerado       = "SIPREV";
  	$iMesInicial          = $oParam->iMesinicial;
    $iAnoInicial          = $oParam->iAnoinicial;
    $iMesFinal            = $oParam->iMesfinal;
    $iAnoFinal            = $oParam->iAnofinal;
    $sArquivos            = $oParam->sListaArquivos; 
    $iUnidadeGestora      = $oParam->iUnidadeGestora; 
    $iTipoAto             = $oParam->iTipoAto;        
    $iNumeroAto           = $oParam->iNumeroAto;      
    $iAnoAto              = $oParam->iAnoAto;      
    $dDataAto             = $oParam->dDataAto;        
    $cRepresentante       = $oParam->cRepresentante;        


  	$oGeradorXML          = new ArquivoSiprevEscritorXML();
  	$otxtLogger           = fopen("tmp/SIPREV.log", "w");
  	$sSqlSipreveEscolhido = $oRhArquivossiprev->sql_query("","*","","rh94_sequencial in ({$sArquivos})");
  	$rsSipreveEscolhido   = $oRhArquivossiprev->sql_record($sSqlSipreveEscolhido);
  	$aClasses             = db_utils::getColectionByRecord($rsSipreveEscolhido);

    foreach ($aClasses as $iIndiceClasses => $sValorClasses) {
    	
    	$sClasse              = $sValorClasses->rh94_nomeclasse;
    	$oClasse              = new $sClasse;
    	$oClasse->setCompetenciaInicial($iAnoInicial, $iMesInicial);
    	$oClasse->setCompetenciaFinal($iAnoFinal, $iMesFinal);
      $oClasse->setUnidadeGestora($iUnidadeGestora);
      $oClasse->setTipoAto($iTipoAto);
      $oClasse->setNumeroAto($iNumeroAto);
      $oClasse->setAnoAto($iAnoAto);
      $oClasse->setDataAto($dDataAto);
      $oClasse->setRepresentante($cRepresentante);
    	$oClasse->setTXTLogger($otxtLogger);
    	try {
    		/*
    		 * Cria o XML para cada arquivo selecionado
    		 */
        $oGeradorXML->adicionarArquivo($oGeradorXML->criarArquivo($oClasse), $oClasse->getNomeArquivo());
    	} catch (Exception $eErro) {
          echo "Arquivo: {$oClasse->getNomeArquivo()} retornou com erro:{$eErro->getMessage()}";
      }  
       	
    }
    
    
   
    
      
      /*
       * Cria o zip  com os arquivos selecionados
       */
      $oGeradorXML->zip($sArquivoGerado);
      $oGeradorXML->adicionarArquivo("tmp/{$sArquivoGerado}.log", "{$sArquivoGerado}.log");
      $oGeradorXML->adicionarArquivo("tmp/{$sArquivoGerado}.zip", "{$sArquivoGerado}.zip");
      $oRetorno->itens  = $oGeradorXML->getListaArquivos();
      fclose($otxtLogger); 
    
    $sArquivosErroServidor   = "";
    $sArquivosErroDependente = "";
    if (count($_SESSION['erro_servidores']) > 0) {
    	
    	$sArquivosErroServidor = 1;
    }
    if (count($_SESSION['erro_dependentes']) > 0) {
      
      $sArquivosErroDependente = 2;
    }    
      
 /*     echo "<pre>";
      print_r($sArquivosErros);
      echo "</pre>";   
      die();   
      */
  	$oRetorno->dados = $aClasses;
  	$oRetorno->arquivos               = $sArquivos;
  	$oRetorno->arquivosErroServidor   = $sArquivosErroServidor;
  	$oRetorno->arquivosErroDependente = $sArquivosErroDependente;
  	 
      
  break;    
    
}
  
 
echo $oJson->encode($oRetorno);   



?>