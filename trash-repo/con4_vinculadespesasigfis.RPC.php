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
  case "vincularDespesa":

    /**
     * Cria um arquivo de lock para garantir que sómente uma pessoa inclua por vez
     */
    $sLockFile = "/tmp/sigfisvinculodespesa.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession("DB_id_usuario"));
    if (!file_exists('config/sigfis/vinculodespesa.xml')) {
      
      $oXmlWriter = new XMLWriter();
      $oXmlWriter->openMemory();
      $oXmlWriter->setIndent(true);
      $oXmlWriter->startDocument('1.0', 'ISO-859-1');
      $oXmlWriter->endDtd();
      $oXmlWriter->startElement('despesas');
      $oXmlWriter->startElement('despesa');
      $oXmlWriter->writeAttribute("despesatce", $oParam->despesatce);
      $oXmlWriter->writeAttribute("despesaecidade", $oParam->despesa);
      $oXmlWriter->endElement();
      $oXmlWriter->endElement();
      
      $strBuffer = $oXmlWriter->outputMemory();
      $rsXML     = fopen('config/sigfis/vinculodespesa.xml', 'w');
      
      fputs($rsXML, $strBuffer);
      fclose($rsXML);
    } else {
      
      $oDomXML = new DOMDocument();
      $oDomXML->preserveWhiteSpace = false;
      $oDomXML->formatOutput       = false;
      $oDomXML->load('config/sigfis/vinculodespesa.xml');
      $oNoDespesas   = $oDomXML->getElementsByTagName("despesas");
      $aDespesas     = $oDomXML->getElementsByTagName("despesa");
      $lAchouDespesa = false;

      foreach ($aDespesas as $oDespesa) {
        
        $iCodigoTCE     = $oDespesa->getAttribute('despesatce'); 
        $iCodigoDespesa = $oDespesa->getAttribute('despesaecidade');
        
        if ($iCodigoDespesa == $oParam->despesa) {
          
          $lAchouDespesa = true;
          break;
        }
      }
      
      if ($lAchouDespesa) {
        
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode("Despesa já vinculada com despesa do sigfis");
      } else {
        
        $oDespesa = $oDomXML->createElement("despesa");
        $oDespesa->setAttribute("despesatce", $oParam->despesatce);
        $oDespesa->setAttribute("despesaecidade", $oParam->despesa);
        $oNoDespesas->item(0)->appendChild($oDespesa);
        $oDomXML->save('config/sigfis/vinculodespesa.xml');
      }
    }
    unlink($sLockFile);
    break;
  case "getVinculos":
    
    $oDomXML = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false;
    $oDomXML->formatOutput       = true;
    $oDomXML->load('config/sigfis/vinculodespesa.xml');
    $oNoDespesas         = $oDomXML->getElementsByTagName('despesas');
    $aDespesas           = $oDomXML->getElementsByTagName('despesa');
    $aDespesasVinculadas = array();
    
    $oDaoOrcElemento     = db_utils::getDao('orcelemento');
    
    foreach ($aDespesas as $oDespesa) {
    
      $iCodigoTCE     = $oDespesa->getAttribute('despesatce');
      $iCodigoDespesa = $oDespesa->getAttribute('despesaecidade');
      $iAnoUsu        = db_getsession("DB_anousu");
      $sSqlDespesa    = $oDaoOrcElemento->sql_query_file($iCodigoDespesa, $iAnoUsu);
      $rsDespesa      = $oDaoOrcElemento->sql_record($sSqlDespesa);
    
      if ($oDaoOrcElemento->numrows == 1) {
    
        $sDescricaoDespesa = urlencode(db_utils::fieldsMemory($rsDespesa, 0)->o56_descr);
    
        $oDespesaVinculada                = new stdClass();
        $oDespesaVinculada->descricao     = $sDescricaoDespesa;
        $oDespesaVinculada->codigotce     = $iCodigoTCE;
        $oDespesaVinculada->codigoecidade = $iCodigoDespesa;
        $aDespesasVinculadas[]            = $oDespesaVinculada;
      }
    }
    unset($oDomXML);
    $oRetorno->despesavinculada = $aDespesasVinculadas;
    break;
  case "removerVinculos":
    
    $sLockFile = "/tmp/sigfisvinculodespesa.lock";
    if (file_exists($sLockFile)) {
      while(file_exists($sLockFile)) {
        if (!file_exists($sLockFile)) {
          break;
        }
      }
    }
    $rsFileLock = fopen($sLockFile, 'w');
    fputs($rsFileLock, db_getsession('DB_id_usuario'));
    $oDomXML                     = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false;
    $oDomXML->formatOutput       = true;
    $oDomXML->load("config/sigfis/vinculodespesa.xml");
    
    $oNoDespesas         = $oDomXML->getElementsByTagName('despesas');
    $aDespesas           = $oDomXML->getElementsByTagName('despesa');

    foreach ($aDespesas as $oDespesa) {
    
      $iCodigoDespesa = $oDespesa->getAttribute('despesaecidade');
      
      if (in_array($iCodigoDespesa, $oParam->aDespesas)) {
        $oNoDespesas->item(0)->removeChild($oDespesa);
      }
    }
    $oDomXML->save('config/sigfis/vinculodespesa.xml');
    unlink($sLockFile);
    break;
}

echo $oJson->encode($oRetorno);