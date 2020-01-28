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
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("dbagata/classes/core/AgataAPI.class");
include("classes/db_db_relatorio_classe.php");
include("classes/db_db_geradorrelatoriotemplate_classe.php");
include("model/dbColunaRelatorio.php");
include("model/dbFiltroRelatorio.php");
include("model/dbVariaveisRelatorio.php");
include("model/dbGeradorRelatorio.model.php");
include("model/dbOrdemRelatorio.model.php");
include("model/dbPropriedadeRelatorio.php");
ini_set("error_reporting","E_ALL & ~NOTICE");


$oPost   		   			           = db_utils::postMemory($_POST);
$oJson   		   			           = new services_json();
$cldb_relatorio    			       = new cl_db_relatorio();
$cldb_geradorrelatoriotemplate = new cl_db_geradorrelatoriotemplate();

$lSqlErro = false;
$lErro    = false;

try {
  $oGeradorRelatorio = new dbGeradorRelatorio($oPost->iCodRelatorio);
} catch (Exception $eException){
	$lErro = true;
  $sRetorno = $eException->getMessage();
}

if ( !$lErro ) {

	// Consulta XML e o Tipo do relatório 
	$rsConsultaRelatorio = $cldb_relatorio->sql_record($cldb_relatorio->sql_query($oPost->iCodRelatorio,"db63_db_tiporelatorio,db63_xmlestruturarel"));
	
	
	if( $cldb_relatorio->numrows > 0 ){
		
	  $oRelatorio = db_utils::fieldsMemory($rsConsultaRelatorio,0);
	  
	  // Gera arquivo .agt e salva no tmp do dbportal
	  $sCaminhoRelatorio = $oGeradorRelatorio->geraArquivoAgt($oRelatorio->db63_xmlestruturarel);
	
	  
	  $clagata = new cl_dbagata();
	  $api = $clagata->api;
	  $api->setReportPath($sCaminhoRelatorio);
	  
	  
	  $oPropriedades = $oGeradorRelatorio->getPropriedades();
	  
	  /**
	  * Retorna o formato de saída do relatório
	  * Ex.: PDF, CSV, TXT
	  */
	  $sFormatoSaida = $oPropriedades->getTipoSaida();
	  if (!$sFormatoSaida) {
	  	$sFormatoSaida = "pdf";
	  }

	  $api->setParameter('$head1',$oPropriedades->getNome());
	  
	  $aOrdem 	  = $oGeradorRelatorio->getOrdem();
	  
	  if (!empty($aOrdem)) {
	  	
	    $aNomeOrdem = array();
	  
	    foreach ($aOrdem as $iInd1 => $aOrdem2){
	      foreach ($aOrdem2 as $iInd2 => $oOrdem ){
	        $aNomeOrdem[] = $oOrdem->getAlias();
	      }	
	    }
	
	    if (!empty($aNomeOrdem)) {
	  	
	      $sNomeOrdem = implode(", ",$aNomeOrdem);
	      $iLinha     = 2;
	
	      for($iIni=0; $iIni < strlen($sNomeOrdem); $i++ ){
		
	        $iFim = 52;
	  
	        if ($iLinha == 2) {
	  	      $sPrefix = "Ordem : ";
	  	      $iFim	  -= 8; 
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
	  }
	  
	  
	  if (isset($oPost->aParametros)){
	  	
	  	$aVariaveisGerador = $oGeradorRelatorio->getVariaveis();
	    $aObjVariaveis     = $oJson->decode(str_replace("\\","",$oPost->aParametros));
	        
	    foreach ( $aVariaveisGerador as $sIndVar => $oVariavelGerador) {
	      foreach ( $aObjVariaveis as $iInd => $oVariavel) {
	        if ( $oVariavel->sNome == $oVariavelGerador->getNome()) {
	          if ( $oVariavelGerador->getTipoDado() == 'date') {
	            $sValor = implode('-',array_reverse(explode('/',$oVariavel->sValor)));
	          } else {
	            $sValor = $oVariavel->sValor;
	          }
	          $api->setParameter($oVariavel->sNome,utf8_decode($sValor));
	        }
	      }
	    }
	      	
	  }
	  
	  
	  // Verifica o tipo de relatório 1-Relatório,  2-Documento Template e utiliza o método da API do Agata referente ao tipo 
	  if ( $oRelatorio->db63_db_tiporelatorio == 2 ) {
		
			/**
			* Sempre será pdf quando entrar aqui
			*/
	  	$sFormatoSaida = 'pdf';

	  	$rsConsultaTemplate = $cldb_geradorrelatoriotemplate->sql_record($cldb_geradorrelatoriotemplate->sql_query(null,"db15_documento",null, " db15_db_relatorio = {$oPost->iCodRelatorio}"));
	
	    if ($cldb_geradorrelatoriotemplate->numrows > 0) {
		  
	      $oArquivoSxw = db_utils::fieldsMemory($rsConsultaTemplate,0);
	     
	   	  db_inicio_transacao();
	   	 
	   	  $sArquivoSxw      = "docTamplate".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
	  	  $sCaminhoTemplate = "tmp/".$sArquivoSxw;
		  
	   	  $lGeraSxw 		= pg_lo_export($oArquivoSxw->db15_documento,$sCaminhoTemplate,$conn);
	   	     	 
	   	  if (!$lGeraSxw) {   	 	
	   	    $lSqlErro = true;
	   	    $lErro	  = true;   	 	
	   	    $sRetorno = "Erro ao gerar aquivo Sxw!";
	      }
	      
	      db_fim_transacao($lSqlErro);
	
	      $sCaminhoSalvoSxw = "tmp/docSalvoSxw".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
	      
	      $api->setOutputPath($sCaminhoSalvoSxw);
			
			  ob_start();
				
			  $ok = $api->parseOpenOffice($sCaminhoTemplate);
	
			  if (!$ok){
			  	
			  	$lErro    = true; 
		 	  	$sRetorno = $api->getError();
		 	  	
		 	  	ob_end_clean();
		 	  	
		 	  }else if ($sFormatoSaida == 'pdf'){
		 	  	
					ob_end_clean();
					
					if ($api->getRowNum() == 0){
			 		  $aRetorno = array("sMsg"=>urlencode("Nenhum registro encontrado!"),"erro"=>true);
					  echo $oJson->encode($aRetorno);
					  exit;			
					}
					
					$sNomeRelatorio   = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".pdf";
					$sComandoConverte = `bin/oo2pdf/oo2pdf.sh {$sCaminhoSalvoSxw} {$sNomeRelatorio}`;
					
					if (trim($sComandoConverte) != "") {
			 		  $aRetorno = array("sMsg"=>urlencode("Operação abortada, verifique as configurações do gerador de relatórios!"),"erro"=>true);
					  echo $oJson->encode($aRetorno);
					  exit;
					} else {
					  $sRetorno = $sNomeRelatorio;
					}
		  		
		  	}
	      
		  } else {
		    $lErro 	= true;
		  	$sRetorno = "Nenhum template cadastrado!";
		  }
	  	
	  } else {
	  	
	    $api->setFormat($sFormatoSaida);
	    $sNomeRelatorio   = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".".$sFormatoSaida;
	    $api->setOutputPath($sNomeRelatorio);
	  	
	  	ob_start();
	  	
	  	$ok = $api->generateReport();
		
	    if(!$ok){
	    	
	      ob_end_clean();
	    	
	      $lErro    = true; 	
	      $sRetorno = $api->getError();
	      
	    }else{
	    	 
	      ob_end_clean();
	      
	   	  if ($api->getRowNum() == 0){
		 		  $aRetorno = array("sMsg"=>urlencode("Nenhum registro encontrado!"),"erro"=>true);
				  echo $oJson->encode($aRetorno);
				  exit;			
		    }
		          
		    $sRetorno = $sNomeRelatorio;
	    }
	    
	  }
	
	} else {
	  $lErro 	= true;
	  $sRetorno = "Nenhum relatório emcontrado!";  	
	}
	
} 

$aRetorno = array("sMsg"=>urlencode($sRetorno),"erro"=>$lErro);
 
echo $oJson->encode($aRetorno);