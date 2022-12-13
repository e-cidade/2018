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

/**
 * 
 * Para salvar o arquivo no banco deve-se passar
 * o codigo do arquivo da tabela bensmodeloetiqueta 
 * codigo = XXXX;
 * 
 * Para salvar o arquivo em uma pasta específica
 * passar o parametro sfilenamexml = 'tmp/xyx.xml'
 * 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once('model/dbModeloEtiqueta.model.php');
require_once("libs/JSON.php");
require_once("classes/db_bensmodeloetiqueta_classe.php");
//header("Content-type: application/xml; charset=ISO-8859-1");
$oJson = new services_json();
$oRetorno           =  new stdClass;
$oRetorno->status   = 1;
$oRetorno->message  = "";

$sXml         = "";
$iCodigo      = 0;
$sFileNameXml = "";
$sDescr = utf8_decode($_POST["sdescr"]);

if(isset($_POST["sxml"]) && trim($_POST["sxml"]) != ""){
  $sXml = db_stdClass::db_stripTagsJson($_POST["sxml"]);
  
}
if(isset($_POST["codigo"]) && trim($_POST["codigo"]) != ""){
  $iCodigo = $_POST["codigo"];
}
if(isset($_POST["sfilenamexml"]) && trim($_POST["sfilenamexml"]) != ""){
  $sFileNameXml = $_POST["sfilenamexml"];
}

//Salva arquivo xml no banco
if($iCodigo != 0 && $sFileNameXml == ""){

	$oModeloEtiqueta = new modeloEtiqueta($iCodigo);
	try{
		$sql_erro = false;
		db_inicio_transacao();
		$oRetorno->message = urlencode($oModeloEtiqueta->gravaArquivoXml($sXml,$sDescr));
		$oRetorno->status  = 1;
		
	}catch (Exception $erro){
		$sql_erro = true;
		$oRetorno->message  = $erro->getMessage();
		$oRetorno->status   = 0;
	}
	db_fim_transacao($sql_erro);
}else if($iCodigo == 0 && $sXml != "" && $sFileNameXml != ""){
//Grava arquivo xml no local especificado.

}

//$oRetorno->sxml     =  $oParam->sxml;
//$oRetorno->codigo   =  $oParam->codigo;
//$oRetorno->message  = "Arquivo recebido com sucesso !";
////echo $sXml = file_get_contents($oParam->sUrl);
//       
echo $oJson->encode($oRetorno);

?>