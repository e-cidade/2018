<?
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
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");
include("libs/JSON.php");
include("dbagata/classes/core/AgataAPI.class");
require_once("model/dbGeradorRelatorio.model.php");
require_once("model/dbColunaRelatorio.php");
require_once("model/dbFiltroRelatorio.php");
require_once("model/dbOrdemRelatorio.model.php");
require_once("model/dbPropriedadeRelatorio.php");
require_once("model/dbVariaveisRelatorio.php");

ini_set("error_reporting","E_ALL & ~NOTICE");

$oGet  = db_utils::postMemory($_GET);
$oJson = new services_json();

if ( isset($_SESSION['objetoXML']) ) {

  $oXML = unserialize($_SESSION['objetoXML']);
  
	try {
	  $oXML->addConsulta();
	} catch (Exception $eException){
	  $sMsgErro = $eException->getMessage();
	  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
	}
	
	try {
	  $oXML->buildXML();
	} catch (Exception $eException){
    $sMsgErro = $eException->getMessage();
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");			
	}
	
  $oXML->converteAgt($oXML->getBuffer());
	        
  $sArquivo          = "geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".agt";
  $sCaminhoRelatorio = "tmp/".$sArquivo;
	$sFormatoSaida     = $oXML->getPropriedades()->getTipoSaida();
  $sOutputPath 			 = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".".$sFormatoSaida;
	      
  $rsRelatorioTemp   = fopen($sCaminhoRelatorio,"w");
	        
  fputs($rsRelatorioTemp ,$oXML->getBufferAgt());
  fclose($rsRelatorioTemp);

	$clagata = new cl_dbagata();
	
	$api = $clagata->api;
	
	$api->setReportPath($sCaminhoRelatorio);
	
	$xml = $api->getReport();
	
	$sNomeRelatorio  = $xml["Report"]["Properties"]["Title"];
	$api->setParameter('$head1',utf8_decode($sNomeRelatorio));
	
	$aOrdem = $oXML->getOrdem();
	
	if (!empty($aOrdem)) {
	  	
	  foreach ($aOrdem as $iInd1 => $aOrdem2){
	    foreach ($aOrdem2 as $iInd2 => $oOrdem ){
	      $aNomeOrdem[] = $oOrdem->getAlias();
	    }	
	  }
	
	  $sNomeOrdem = implode(", ",$aNomeOrdem);
	  $iLinha     = 2;
	
	  for($iIni=0; $iIni < strlen($sNomeOrdem); $i++ ){
		
	    $iFim = 52;
	  
	    if ($iLinha == 2) {
	  	  $sPrefix = "Ordem : ";
	  	  $iFim	-= 8; 
	    } else {
	  	  $sPrefix = "";
	    }
	    
	    $api->setParameter('$head'.$iLinha,$sPrefix.(substr($sNomeOrdem,$iIni,$iFim)));
	    $iLinha++;
	    $iIni += $iFim;
	    
	    if ($iLinha == 7) {
	  	  break;	
	    }
	  }
	}
	
	
	if (isset($oGet->variaveis)){
		
	  $aXMLVariaveis = $oXML->getVariaveis();
	  $aObjVariaveis = $oJson->decode(str_replace("\\","",$oGet->variaveis));
	  
	  foreach ( $aXMLVariaveis as $sIndXmlVar => $oXmlVariavel) {
		  foreach ( $aObjVariaveis as $iInd => $oVariavel) {
 	  	  if ( $oVariavel->sNome == $oXmlVariavel->getNome()) {
 	  	  	if ( $oXmlVariavel->getTipoDado() == 'date') {
 	  	  		$sValor = implode('-',array_reverse(explode('/',$oVariavel->sValor)));
 	  	  	} else {
 	  	  		$sValor = $oVariavel->sValor;
 	  	  	}
		  	  $api->setParameter($oVariavel->sNome,$sValor);
		  	}
		  }
	  }
	  
	}
	
	$api->setFormat($sFormatoSaida);
  $api->setOutputPath($sOutputPath);

	$ok = $api->generateReport();
		
	if(!$ok){
	  echo $api->getError();
	}else{ 
		db_redireciona($sOutputPath);
	}

} else {
	
	db_redireciona("db_erros.php?fechar=true&db_erro=Relatório não configurado!");
	
}