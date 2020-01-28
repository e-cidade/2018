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
  
  case 'vincularRecursos' :
    
    $sLockFile = "/tmp/sigfisvinculorecurso.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    
    if (!file_exists('config/sigfis/vinculorecursos.xml')) {
      
      $oXmlWriter = new XMLWriter();
      $oXmlWriter->openMemory();
      $oXmlWriter->setIndent(true);
      $oXmlWriter->startDocument('1.0','ISO-8859-1');
      $oXmlWriter->endDtd();
      $oXmlWriter->startElement("recursos");
      $oXmlWriter->startElement("recurso");
      $oXmlWriter->writeAttribute("recursotce",     $oParam->recursotce);
      $oXmlWriter->writeAttribute("recursoecidade", $oParam->recurso);
      $oXmlWriter->endElement();
      $oXmlWriter->endElement();
      $strBuffer  = $oXmlWriter->outputMemory();
      $rsXMl      = fopen('config/sigfis/vinculorecursos.xml', 'w');
      fputs($rsXMl, $strBuffer);
      fclose($rsXMl); 
    } else {
      
      $oDomXml  = new DOMDocument();
      $oDomXml->preserveWhiteSpace = false; 
      $oDomXml->formatOutput       = true;
      $oDomXml->load('config/sigfis/vinculorecursos.xml');
      $oNoRecursos   = $oDomXml->getElementsByTagName("recursos");
      $aRecursos     = $oDomXml->getElementsByTagName("recurso");
      $lAchouRecurso = false;
      foreach ($aRecursos as $oRecurso) {
        
        $iCodigoTCE     = $oRecurso->getAttribute("recursotce");
        $iCodigoRecurso = $oRecurso->getAttribute("recursoecidade");
        if ($iCodigoRecurso == $oParam->recurso) {

          $lAchouRecurso= true;
          break;
        }
      }
      if ($lAchouRecurso) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode('Recurso já vinculado com recurso do sigfis.');
      } else {
        
        $oRecurso = $oDomXml->createElement("recurso");
        $oRecurso->setAttribute('recursotce', $oParam->recursotce);
        $oRecurso->setAttribute('recursoecidade', $oParam->recurso);
        $oNoRecursos->item(0)->appendChild($oRecurso);
        $oDomXml->save('config/sigfis/vinculorecursos.xml');
      }
    }
    unlink($sLockFile);
    break;

  case 'getVinculos':
    
      $oDomXml= new DOMDocument();
      $oDomXml->preserveWhiteSpace = false; 
      $oDomXml->formatOutput       = true;
      $oDomXml->load('config/sigfis/vinculorecursos.xml');
      $oNoRecursos         = $oDomXml->getElementsByTagName("recursos");
      $aRecursos           = $oDomXml->getElementsByTagName("recurso");
      $aRecursosVinculados = array();
      $oDaoOrctipoRec      = db_utils::getDao("orctiporec");
      foreach ($aRecursos as $oRecurso) {
        
        $iCodigoTCE           = $oRecurso->getAttribute("recursotce");
        $iCodigoRecurso       = $oRecurso->getAttribute("recursoecidade");
        $sSqlDescricaoRecurso = $oDaoOrctipoRec->sql_query_file($iCodigoRecurso);
        $rsDescricaoRecurso   = $oDaoOrctipoRec->sql_record($sSqlDescricaoRecurso);
        if ($oDaoOrctipoRec->numrows == 1) {

          $sDescricaoRecurso = urlencode(db_utils::fieldsMemory($rsDescricaoRecurso, 0)->o15_descr);
          
          $oRecursoVinculado                = new stdClass();
          $oRecursoVinculado->descricao     = $sDescricaoRecurso;
          $oRecursoVinculado->codigotce     = $iCodigoTCE;
          $oRecursoVinculado->codigoecidade = $iCodigoRecurso;
          $aRecursosVinculados[]            = $oRecursoVinculado;
        }
      }
      $oRetorno->recursosvinculados = $aRecursosVinculados;
    break;
    
  case 'removerVinculos':
    
    $sLockFile = "/tmp/sigfisvinculorecurso.lock";
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
    $oDomXml->load('config/sigfis/vinculorecursos.xml');
    $oNoRecursos         = $oDomXml->getElementsByTagName("recursos");
    $aRecursos           = $oDomXml->getElementsByTagName("recurso");
    $aRecursosVinculados = array();
    $oDaoOrctipoRec      = db_utils::getDao("orctiporec");
    foreach ($aRecursos as $oRecurso) {
      
      $iCodigoRecurso       = $oRecurso->getAttribute("recursoecidade");
      if (in_array($iCodigoRecurso, $oParam->aRecursos)) {
        
        $oNoRecursos->item(0)->removeChild($oRecurso);
      }
    }
    $oDomXml->save('config/sigfis/vinculorecursos.xml');
    unlink($sLockFile);
    break;
}
echo $oJson->encode($oRetorno);
?>