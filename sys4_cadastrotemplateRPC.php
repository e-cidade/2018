<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
require("libs/db_utils.php");

require_once("classes/db_db_relatorio_classe.php");
require_once("model/dbPropriedadeRelatorio.php");	
require_once("model/dbFiltroRelatorio.php");
require_once("model/dbColunaRelatorio.php");
require_once("model/dbOrdemRelatorio.model.php");
require_once("model/dbVariaveisRelatorio.php");
require_once("model/dbGeradorRelatorio.model.php");
require_once("classes/db_db_geradorrelatoriotemplate_classe.php");

$cldb_geradorrelatoriotemplate = new cl_db_geradorrelatoriotemplate();

  $oPost  = db_utils::postMemory($_POST);
  $oJson  = new services_json();

	
  if ($oPost->sAcao == "consultaTemplate") {

   $cldb_relatorio = new cl_db_relatorio();
   
   $rsConsultaTemplate = $cldb_geradorrelatoriotemplate->sql_record($cldb_geradorrelatoriotemplate->sql_query(null,"db15_sequencial as coddocumento,db15_db_relatorio as codrelatorio",null, " db15_db_relatorio = {$oPost->iCodRelatorio}"));
  	
   if ( $cldb_geradorrelatoriotemplate->numrows > 0 ) {
  	 $aRetornaDocumento = db_utils::getColectionByRecord($rsConsultaTemplate,false,false,true);
   } else { 
	   $aRetornaDocumento = "";        
   }	

   $aRetorno = array("templates"=>$aRetornaDocumento);
   
   echo  $oJson->encode($aRetorno);
   
	
  } else if ($oPost->sAcao == "consultaVariaveis") {  
	
  	
  	
	$oGeradorRelatorio = new dbGeradorRelatorio($oPost->iCodRelatorio);  	
	
  	$aObjVariaveis = $oGeradorRelatorio->getVariaveis();
	$aVariaveis    = array();	
  	
	foreach ($aObjVariaveis as $sNome => $oVariavel){
	    
	  $oRetornoVariavel = new stdClass();
	  $oRetornoVariavel->sNome  = $oVariavel->getNome();
	  $oRetornoVariavel->sLabel = $oVariavel->getLabel();
	  $oRetornoVariavel->sValor = $oVariavel->getValor();
	  
	  $aVariaveis[] = $oRetornoVariavel;
	    
	}
	
	
	echo $oJson->encode($aVariaveis);

	
	
  } else if ($oPost->sAcao == "imprimirDocumento") {

  	
   $cldb_relatorio = new cl_db_relatorio();
   
   $rsConsultaArquivo = $cldb_geradorrelatoriotemplate->sql_record($cldb_geradorrelatoriotemplate->sql_query(null,"db15_documento,db15_db_relatorio",null, " db15_db_relatorio = {$oPost->iCodRelatorio}"));
	
   if ($cldb_geradorrelatoriotemplate->numrows > 0) {
   	
     $oArquivo = db_utils::fieldsMemory($rsConsultaArquivo,0);
     
   	 $lSqlErro = false;
   	 db_inicio_transacao();
   	 
   	 $sArquivoSxw   = "docTamplate".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
	 $sNomeTemplate = "tmp/".$sArquivoSxw;
   	    	 
   	 $lErro = pg_lo_export($oArquivo->db15_documento,$sNomeTemplate,$conn);   	 
   	 if (!$lErro) {   	 	
   	   $lSqlErro = true;   	 	
   	 }

     $rsConsultaXml = $cldb_relatorio->sql_record($cldb_relatorio->sql_query_file($oArquivo->db15_db_relatorio,"db63_xmlestruturarel as xml"));

     if ($cldb_relatorio->numrows > 0){
     	
		$oDBXml = db_utils::fieldsMemory($rsConsultaXml,0);
		
		$oXml = new dbGeradorRelatorio(); 
		
		$oXml->converteAgt($oDBXml->xml);
		
		$sArquivoAgt = "geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".agt";
	    $sCaminhoAgt = "tmp/".$sArquivoAgt;
		
     	$rsAgtTemp   = fopen($sCaminhoAgt,"w");
     	fputs($rsAgtTemp ,$oXml->getBufferAgt());
	    fclose($rsAgtTemp);
		
      	include("libs/db_libsys.php");
	 	include("dbagata/classes/core/AgataAPI.class");
	
	 	ini_set("error_reporting","E_ALL & ~NOTICE");
	
		$clagata = new cl_dbagata();
		
		$api = $clagata->api;
		$api->setReportPath($sCaminhoAgt);
		
		$sCaminhoSalvoSxw = "tmp/docSalvoSxw".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
		
		$api->setOutputPath($sCaminhoSalvoSxw);
		
		ob_start();
		
		$ok = $api->parseOpenOffice($sNomeTemplate);

		if (!$ok){
			
 		  echo $api->getError();
 		  
		}else{
		  ob_end_clean();
		  $sNomeRelatorio   = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".pdf";
		  $sComandoConverte = "bin/oo2pdf/oo2pdf.sh {$sCaminhoSalvoSxw} {$sNomeRelatorio}";
 		  $sRetorno = `$sComandoConverte`;
		}
		
		
     }
   	 
   	 db_fim_transacao($lSqlErro);
     
   	 echo $oJson->encode($sNomeRelatorio); 
   	 
   }else{
   	
   	echo " Sem documentos cadastrados!";
   	
   }	
	
  }
?>