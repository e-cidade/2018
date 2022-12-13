<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("classes/db_caddocumentoatributo_classe.php"));
require_once(modification("classes/db_cgmdocumento_classe.php"));
require_once(modification("classes/db_documento_classe.php"));
require_once(modification("classes/db_caddocumento_classe.php"));
require_once(modification("classes/db_caddocumentoatributovalor_classe.php"));
require_once(modification("classes/db_db_sysarqcamp_classe.php"));

require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/Documento.model.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMsg      = '';

// Se viér o parâmetro consulta por Json, adiciona no retorno
if (isset($oParam->sConsulta)) {
  $oRetorno->sConsulta = $oParam->sConsulta;
}
$oDocumento          = new Documento();

try {
	
	/**
	 * 
	 *  Retorna um array com os atributos de um documento apartir do código do cadastro de documento
	 * 
	 */
  if ($oParam->sMethod == 'carregaFormDocumento' ) {

  	$oRetorno->aAtributos = Documento::getAtributosByCadDocumento($oParam->iCodCadDocumento);
  	
  /**
   * 
   *  Salva um documento e retorna o código do documento gerado 
   * 
   */	
  } else if ($oParam->sMethod == 'salvaDocumento') {

  	db_inicio_transacao();
  	
    try {
	  	$iCodDocumento = $oDocumento->incluirDocumento($oParam->aAtributos);
    } catch (Exception $eException) {
    	throw new Exception($eException->getMessage());
    }
    
    db_fim_transacao(false);
  	      
    $oRetorno->sMsg          = urlencode('Documento incluído com sucesso!');
    $oRetorno->iCodDocumento = $iCodDocumento;
    
    
  /**
   * 
   *  Altera os valores do atributo de um documento
   * 
   */  
  } else if ($oParam->sMethod == 'alteraDocumento') {

  	db_inicio_transacao();
  	
    try {
      $oDocumento->alterarDocumento($oParam->iCodDocumento,$oParam->aAtributos);
    } catch (Exception $eException) {
      throw new Exception($eException->getMessage());
    }

    db_fim_transacao(false);
    
    $oRetorno->sMsg = urlencode("Documento alterado com sucesso!");

    
  /*
   * 
   *  Retorna tods os atributos de um documento e seus valores já cadastrados,  
   *  utilizado para visualizar os dados na rotina de alteração de documento 
   * 
   */  
  } else if ($oParam->sMethod == 'loadDocumento') {

    $iCodDocumento     = $oParam->iCodDocumento;
    $oDaoAtributoValor = new cl_caddocumentoatributovalor();
    
    $sSqlAtributoValor = $oDaoAtributoValor->sql_query_file(null, "*", "", "db43_documento={$iCodDocumento}");
    $rsAtributoValor   = $oDaoAtributoValor->sql_record($sSqlAtributoValor);
    $aValores          = db_utils::getCollectionByRecord($rsAtributoValor, false, false, true);

    $oRetorno->aAtributos    = Documento::getAtributosByDocumento($iCodDocumento);
    $oRetorno->aValores      = $aValores;
    $oRetorno->iCodDocumento = $oParam->iCodDocumento;
    
  	
	}
	
} catch (Exception $eException) {

	if (db_utils::inTransaction()) {
		db_fim_transacao(true);
	}
	
  $oRetorno->iStatus  = 2;
  $oRetorno->sMsg     = urlencode(str_replace("\\n", "\n", $eException->getMessage()));	
	
}


echo $oJson->encode($oRetorno);