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
  
  case "vincularReceita":
    
    $sLockFile = "/tmp/sigfisvinculoreceita.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    if (!file_exists('config/sigfis/vinculoreceita.xml')) {
      
      $oXmlWriter = new XMLWriter();
      $oXmlWriter->openMemory();
      $oXmlWriter->setIndent(true);
      $oXmlWriter->startDocument('1.0','ISO-859-1');
      $oXmlWriter->endDtd();
      $oXmlWriter->startElement('receitas');
      $oXmlWriter->startElement('receita');
      $oXmlWriter->writeAttribute("receitatce", $oParam->receitatce);
      $oXmlWriter->writeAttribute("receitaecidade", $oParam->receita);
      $oXmlWriter->endElement();
      $oXmlWriter->endElement();
      $strBuffer = $oXmlWriter->outputMemory();
      $rsXML     = fopen('config/sigfis/vinculoreceita.xml', 'w');
      fputs($rsXML, $strBuffer);
      fclose($rsXML);
    } else {

      $oDomXML = new DOMDocument();
      $oDomXML->preserveWhiteSpace = false;
      $oDomXML->formatOutput       = true;
      $oDomXML->load('config/sigfis/vinculoreceita.xml');
      $oNoReceitas   = $oDomXML->getElementsByTagName("receitas");
      $aReceitas     = $oDomXML->getElementsByTagName("receita");
      $lAchouReceita = false;
      
      foreach ($aReceitas as $oReceita) {
        
        $iCodigoTCE     = $oReceita->getAttribute('receitatce');
        $iCodigoReceita = $oReceita->getAttribute('receitaecidade');
        
        if ($iCodigoReceita == $oParam->receita) {
          
          $lAchouReceita = true;
          break;          
        }
      } 
      if ($lAchouReceita) {
        
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Receita já vinculada com receita do Sigfis");
      } else {
        
        $oReceita = $oDomXML->createElement("receita");
        $oReceita->setAttribute("receitatce", $oParam->receitatce);
        $oReceita->setAttribute("receitaecidade", $oParam->receita);
        $oNoReceitas->item(0)->appendChild($oReceita);
        $oDomXML->save('config/sigfis/vinculoreceita.xml');
      }
    }
    unlink($sLockFile);    
    break;
  case 'getVinculos' :
    
    $oDomXML = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false;
    $oDomXML->formatOutput       = true;
    $oDomXML->load('config/sigfis/vinculoreceita.xml');
    $oNoReceitas         = $oDomXML->getElementsByTagName('receitas');
    $aReceitas           = $oDomXML->getElementsByTagName('receita');
    $aReceitasVinculadas = array();
    
    $oDaoOrcFonte        = db_utils::getDao('orcfontes');
    
    foreach ($aReceitas as $oReceita) {
      
      $iCodigoTCE     = $oReceita->getAttribute('receitatce');
      $iCodigoReceita = $oReceita->getAttribute('receitaecidade');
      $iAnoUsu        = db_getsession("DB_anousu");
      $sSqlReceita    = $oDaoOrcFonte->sql_query_file($iCodigoReceita, $iAnoUsu);
      $rsReceita      = $oDaoOrcFonte->sql_record($sSqlReceita);
      
      if ($oDaoOrcFonte->numrows == 1) {
        
        $sDescricaoReceita = urlencode(db_utils::fieldsMemory($rsReceita, 0)->o57_descr);
        
        $oReceitaVinculada                = new stdClass();
        $oReceitaVinculada->descricao     = $sDescricaoReceita;
        $oReceitaVinculada->codigotce     = $iCodigoTCE;
        $oReceitaVinculada->codigoecidade = $iCodigoReceita;
        
        $aReceitasVinculadas[]            = $oReceitaVinculada; 
      }
    }
    unset($oDomXML);
    $oRetorno->receitavinculada = $aReceitasVinculadas;
    break;
    
  case 'removerVinculos':

    $sLockFile = "/tmp/sigfisvinculoreceita.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession('DB_id_usuario'));
    $oDomXML = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false; 
    $oDomXML->formatOutput       = true;
    $oDomXML->load("config/sigfis/vinculoreceita.xml");
    $oNoReceitas         = $oDomXML->getElementsByTagName('receitas');
    $aReceitas           = $oDomXML->getElementsByTagName('receita');
    $aReceitasVinculadas = array();
    $oDaoOrcFonte        = db_utils::getDao('orcfontes');
    
    foreach ($aReceitas as $oReceita) {
    
      $iCodigoReceita = $oReceita->getAttribute('receitaecidade');
      if (in_array($iCodigoReceita, $oParam->aReceitas)) {
        $oNoReceitas->item(0)->removeChild($oReceita);
      }
    }
    $oDomXML->save('config/sigfis/vinculoreceita.xml');
    unlink($sLockFile);
    break;
}

echo $oJson->encode($oRetorno);