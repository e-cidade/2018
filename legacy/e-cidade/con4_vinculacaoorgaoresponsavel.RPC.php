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
	
	case 'vincularOrgaos':
		
    $sLockFile = "/tmp/vinculaorgaoresponsavel.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    
    if (!file_exists('config/sigfis/vinculaorgaoresponsavel.xml')) {
      
      $oXmlWriter = new XMLWriter();
      $oXmlWriter->openMemory();
      $oXmlWriter->setIndent(true);
      $oXmlWriter->startDocument('1.0','ISO-8859-1');
      $oXmlWriter->endDtd();
      $oXmlWriter->startElement("orgaos");
      $oXmlWriter->startElement("orgao");
      $oXmlWriter->writeAttribute("codigoorgao", $oParam->iCodigoOrgao);
      $oXmlWriter->writeAttribute("cpfresponsavel", $oParam->iCpfResponsavel);
      $oXmlWriter->writeAttribute("tipogestaocreditos", $oParam->iTipoGestaoCreditos);
      $oXmlWriter->writeAttribute("datainiciogestao", $oParam->sDataInicioGestao);
      $oXmlWriter->writeAttribute("tipoordenador", $oParam->iTipoOrdenador);
      $oXmlWriter->endElement();
      $oXmlWriter->endElement();
      $strBuffer  = $oXmlWriter->outputMemory();
      $rsXMl      = fopen('config/sigfis/vinculaorgaoresponsavel.xml', 'w');
      fputs($rsXMl, $strBuffer);
      fclose($rsXMl);
    } else {
    	
    	$oDomXml  = new DOMDocument();
      $oDomXml->preserveWhiteSpace = false; 
      $oDomXml->formatOutput       = true;
      $oDomXml->load('config/sigfis/vinculaorgaoresponsavel.xml');
      $oNoOrgao    = $oDomXml->getElementsByTagName("orgaos");
      $aOrgaos     = $oDomXml->getElementsByTagName("orgao");
      $lAchouOrgao = false;
      foreach ($aOrgaos as $oOrgaoAtual) {
        
        $iCodigoOrgao = $oOrgaoAtual->getAttribute("codigoorgao");
        if ($iCodigoOrgao == $oParam->iCodigoOrgao) {

        	$oOrgaoAtual->setAttribute("codigoorgao", $oParam->iCodigoOrgao);
        	$oOrgaoAtual->setAttribute("cpfresponsavel", $oParam->iCpfResponsavel);
        	$oOrgaoAtual->setAttribute("tipogestaocreditos", $oParam->iTipoGestaoCreditos);
        	$oOrgaoAtual->setAttribute("datainiciogestao", $oParam->sDataInicioGestao);
        	$oOrgaoAtual->setAttribute("tipoordenador", $oParam->iTipoOrdenador);
        	$oDomXml->save('config/sigfis/vinculaorgaoresponsavel.xml');
        	$lAchouOrgao = true;
          break;
        }
      }
      if ($lAchouOrgao) {
       
        $oRetorno->status  = 1;
        $oRetorno->message = urlencode('Vinculo atualizado com sucesso.');
      } else {
        
        $oOrgao = $oDomXml->createElement("orgao");
        $oOrgao->setAttribute("codigoorgao", $oParam->iCodigoOrgao);
        $oOrgao->setAttribute("cpfresponsavel", $oParam->iCpfResponsavel);
        $oOrgao->setAttribute("tipogestaocreditos", $oParam->iTipoGestaoCreditos);
        $oOrgao->setAttribute("datainiciogestao", $oParam->sDataInicioGestao);
        $oOrgao->setAttribute("tipoordenador", $oParam->iTipoOrdenador);
        $oNoOrgao->item(0)->appendChild($oOrgao);
        $oDomXml->save('config/sigfis/vinculaorgaoresponsavel.xml');
        $oRetorno->status = 1;
        $oRetorno->message = urlencode('Vinculo criado com sucesso!');
      }
    }
    unlink($sLockFile);
  break;
    
	case 'consultaVinculosOrgao':
		
		$oDomXml  = new DOMDocument();
    $oDomXml->preserveWhiteSpace = false; 
    $oDomXml->formatOutput       = true;
    $oDomXml->load('config/sigfis/vinculaorgaoresponsavel.xml');
    $oDadosXML = $oDomXml->getElementsByTagName('orgao');
    foreach ($oDadosXML as $oElemento){
    	if ($oElemento->getAttribute('codigoorgao') == $oParam->iCodigoOrgao) {
    	  $oRetorno->dadosRecuperados = array(
    	                                  'iCpfResponsavel'     => $oElemento->getAttribute('cpfresponsavel'),
    	                                  'iTipoGestaoCreditos' => $oElemento->getAttribute('tipogestaocreditos'),
    	                                  'sDataInicioGestao'   => $oElemento->getAttribute('datainiciogestao'),
    	                                  'iTipoOrdenador'      => $oElemento->getAttribute('tipoordenador')
    	                                );
    	  $oRetorno->status = 3;
    	  break;
    	}
    }
	break;	
}
echo $oJson->encode($oRetorno);
?>