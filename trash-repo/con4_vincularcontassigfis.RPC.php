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

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {
  
  case 'vincularContas' :
    
    $sLockFile = "/tmp/sigfisvinculo.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    
    if (!file_exists('config/sigfis/vinculoplanoconta.xml')) {
      
      $oXmlWriter = new XMLWriter();
      $oXmlWriter->openMemory();
      $oXmlWriter->setIndent(true);
      $oXmlWriter->startDocument('1.0','ISO-8859-1');
      $oXmlWriter->endDtd();
      $oXmlWriter->startElement("contas");
      $oXmlWriter->startElement("conta");
      $oXmlWriter->writeAttribute("contatce", $oParam->contatce);
      $oXmlWriter->writeAttribute("contaplano", $oParam->contaplano);
      $oXmlWriter->writeAttribute("naturezasaldo", $oParam->origemsaldo);
      $oXmlWriter->endElement();
      $oXmlWriter->endElement();
      $strBuffer  = $oXmlWriter->outputMemory();
      $rsXMl      = fopen('config/sigfis/vinculoplanoconta.xml', 'w');
      fputs($rsXMl, $strBuffer);
      fclose($rsXMl); 
    } else {

      $oDomXml  = new DOMDocument();
      $oDomXml->preserveWhiteSpace = false; 
      $oDomXml->formatOutput       = true;
      $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
      $oPlano      = $oDomXml->getElementsByTagName("contas");
      $aContas     = $oDomXml->getElementsByTagName("conta");
      $lAchouConta = false;
      foreach ($aContas as $oConta) {
        
        $iCodigoTCE   = $oConta->getAttribute("contatce");
        $iCodigoConta = $oConta->getAttribute("contaplano");
        if ($iCodigoConta == $oParam->contaplano && $iCodigoTCE == $oParam->contatce) {

          $lAchouConta = true;
          break;
        }
      }
      if ($lAchouConta) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode('Conta já vinculadas.');
      } else {
        
        $oConta = $oDomXml->createElement("conta");
        $oConta->setAttribute('contatce', $oParam->contatce);
        $oConta->setAttribute('contaplano', $oParam->contaplano);
        $oConta->setAttribute('naturezasaldo', $oParam->origemsaldo);
        $oPlano->item(0)->appendChild($oConta);
        $oDomXml->save('config/sigfis/vinculoplanoconta.xml');
      }
    }
    unlink($sLockFile);
    break;
    
    case 'getVinculos':
    
      $sLockFile = "/tmp/sigfisvinculo.lock";
      if (file_exists($sLockFile)) {
        while(file_exists($sLockFile)) {
          if (!file_exists($sLockFile)) {
            break;
          }
        }
      }
      $rsFileLock = fopen($sLockFile, 'w');
      $oDomXml= new DOMDocument();
      $oDomXml->preserveWhiteSpace = false; 
      $oDomXml->formatOutput       = true;
      $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
      $oNoConta            = $oDomXml->getElementsByTagName("contas");
      $aContas             = $oDomXml->getElementsByTagName("conta");
      $aRecursosVinculados = array();
      $oDaoConplano        = db_utils::getDao("conplano");
      foreach ($aContas as $oConta) {
        
        $iCodigoTCE           = $oConta->getAttribute("contatce");
        $iCodigoConta         = $oConta->getAttribute("contaplano");
        $sSqlDescricaoConta   = $oDaoConplano->sql_query_file($iCodigoConta, db_getsession("DB_anousu"));
        $rsDescricaoConta     = $oDaoConplano->sql_record($sSqlDescricaoConta);
        if ($oDaoConplano->numrows == 1) {

          $oDadosConta       = db_utils::fieldsMemory($rsDescricaoConta, 0);
          $sDescricaoConta   = urlencode($oDadosConta->c60_descr);
          $sEstruturalConta  = urlencode($oDadosConta->c60_estrut);
          
          $oContaVinculado                = new stdClass();
          $oContaVinculado->descricaoconta= $sDescricaoConta;
          $oContaVinculado->estrutural    = $sEstruturalConta;
          $oContaVinculado->codigotce     = $iCodigoTCE;
          $oContaVinculado->codigoecidade = $iCodigoConta;
          $aContasVinculados[]            = $oContaVinculado;
        }
      }
      
      $oRetorno->contasvinculadas = $aContasVinculados;
      unlink($sLockFile);
    break;
    
    case 'removerVinculos':
    
    $sLockFile = "/tmp/sigfisvinculo.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    $oDomXml= new DOMDocument();
    $oDomXml->preserveWhiteSpace = false; 
    $oDomXml->formatOutput       = true;
    $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
    $oNoContas           = $oDomXml->getElementsByTagName("contas");
    $aContasRemover      = $oDomXml->getElementsByTagName("conta");
    $aNodesRemover       = array();
    $aContasVinculados   = array();
    foreach ($aContasRemover as $oConta) {
      
      $iCodigoConta       = $oConta->getAttribute("contaplano");
      if (in_array($iCodigoConta, $oParam->aContas)) {
        $aNodesRemover[] = $oConta;
      }
    }
    foreach ($aNodesRemover as $oNode) {
      $oNoContas->item(0)->removeChild($oNode);
    }
    $oDomXml->save('config/sigfis/vinculoplanoconta.xml');
    unlink($sLockFile);
    break;
    
}
echo $oJson->encode($oRetorno);
?>