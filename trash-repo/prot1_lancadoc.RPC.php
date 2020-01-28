<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_caddocumentoatributovalor_classe.php");
require_once("classes/db_cgmdocumento_classe.php");
require_once("classes/db_db_sysarqcamp_classe.php");

require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("model/Documento.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = '';

$clCgmDocumento  = new cl_cgmdocumento();    
$oDocumento      = new Documento();


try {
	
	
	/**
	 * 
	 *  Retorna todos documentos de um CGM
	 *  
	 */
	if ( $oParam->sMethod == 'getDocumentosByCgm' ) {

    $oRetorno->aDocumentos = array();     
    $iNumCgm = $oParam->iNumCgm;
        
    $sSqlCgmDocumento = $clCgmDocumento->sql_query_file(null, "*", "", "z06_numcgm={$iNumCgm}");
    $rsCgmDocumento   = $clCgmDocumento->sql_record($sSqlCgmDocumento);
    $aCgmDocumento    = db_utils::getColectionByRecord($rsCgmDocumento);
       
    $aDocumentos = array();

    foreach ( $aCgmDocumento as $cgmDocumento ) {

    	$clCadAtributoValor   = new cl_caddocumentoatributovalor();
      $sSqlCadAtributoValor = $clCadAtributoValor->sql_query(null,"distinct(caddocumento.db44_sequencial), 
                                                                   caddocumento.db44_descricao",
                                                                  null,
                                                                  "db43_documento={$cgmDocumento->z06_documento}");
      
      $rsCadAtributoValor    = $clCadAtributoValor->sql_record($sSqlCadAtributoValor);
      $clCadAtributoValor    = db_utils::fieldsMemory($rsCadAtributoValor,0);
          
      $clCadAtributoValor->db44_descricao  = urlencode($clCadAtributoValor->db44_descricao);
      $clCadAtributoValor->db58_sequencial = $cgmDocumento->z06_documento; 
      $oRetorno->aDocumentos[] = $clCadAtributoValor;
      
    }		
  	
    
    
  /*
   * 
   * Inclui na tabela de ligação dos documentos apartir do código do documento informado
   * 
   */	
  } else if ( $oParam->sMethod == 'incluirDocumento' ) {

  	db_inicio_transacao();
  	
    $clCgmDocumento->z06_documento = $oParam->iCodDocumento;
   	$clCgmDocumento->z06_numcgm    = $oParam->iNumCgm;
   	$clCgmDocumento->incluir(null);

   	if ($clCgmDocumento->erro_status == 0 ) {
   		throw new Exception($clCgmDocumento->erro_msg); 
   	}
    
    db_fim_transacao(false);
  	      
    $oRetorno->sMsg = urlencode('Documento incluído com sucesso!');
    

  /**
   * 
   * Exclusão do documento
   * 
   */
  } else if ( $oParam->sMethod == 'excluiDocumento' ) {

    
    db_inicio_transacao();

    $oDaoCgmDocumento  = db_utils::getDao('cgmdocumento');
    $oDaoCgmDocumento->z06_documento = $oParam->iCodDocumento;
    $oDaoCgmDocumento->excluir(null,"z06_documento = {$oParam->iCodDocumento}");
      
    if ($oDaoCgmDocumento->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoCgmDocumento->erro_msg);         
    }
      
    try {
      $oDocumento->excluirDocumento($oParam->iCodDocumento);
    } catch (Exception $eException) {
      throw new Exception($eException->getMessage());
    }
    
    db_fim_transacao(false);
  
    $oRetorno->sMsg = urlencode('Exclusão feita com sucesso!');    
    
	}
	
	
} catch (Exception $eException) {

	if ( db_utils::inTransaction() ) {
		db_fim_transacao(true);
	}
	
  $oRetorno->iStatus  = 2;
  $oRetorno->sMsg     = urlencode(str_replace("\\n","\n",$eException->getMessage()));	
	
}


echo $oJson->encode($oRetorno);   
?>