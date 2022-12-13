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


class DBFormCache {
  
  private $sPath;
  private $sFileName;
  private $aFields;
  private $iIdUser;
  private $oJson;
  
  public function __construct() {
    
    $this->oJson = new services_json();
  }
  public function save() {
    
    $this->sPath   = $this->getPath(); 
    $sPath         = $this->sPath."/".$this->fileNameToCache();
    $sObjectJson   = $this->getObjectJson();
    
    $this->delete(); 
    $fCache        = fopen($sPath, "w");
    fwrite($fCache, $sObjectJson);
    chmod($sPath, 0777);
    fclose($fCache);
  }
  
  public function delete() {
    
    $this->sPath   = $this->getPath();
    $sPath         = $this->sPath."/".$this->fileNameToCache();
    
    if (file_exists($sPath)) {
      unlink($sPath);
    }
  }
  
  public function load() {
    
    $this->sPath   = $this->getPath();
    $sPath         = $this->sPath."/".$this->fileNameToCache();
    $sObjectJson   = "" ;
    if (file_exists($sPath)) {
      $sObjectJson   = file_get_contents ($sPath, FILE_TEXT);
    }
    return $this->oJson->decode($sObjectJson);
    
  }
  
  protected function getPath() {
    
    $sPath = '';
    if ($_SERVER['DOCUMENT_ROOT']) {
      $sPath = "{$_SERVER['DOCUMENT_ROOT']}";
    }
    $sPath .= dirname($_SERVER['PHP_SELF']);
    $sPath .= "/cache";

    if (defined('ECIDADE_PATH')) {
      $sPath = ECIDADE_PATH . 'cache';
    }

    if (!file_exists($sPath)) {
      
      mkdir($sPath, 0777);
      chmod($sPath, 0777);
    }
    $sPath .= '/forms';
    
    if (!file_exists($sPath)) {
      
      mkdir($sPath, 0777);
      chmod($sPath, 0777);
    }
    $sPath .= "/{$this->iIdUser}";
    
    if (!file_exists($sPath)) {
      
      mkdir($sPath, 0777);
      chmod($sPath, 0777);
    }
    
    return $sPath;
  }
  
  private function fileNameToCache() {
  
    $aNomeArquivo  = explode(".", $this->sFileName);
    return $aNomeArquivo[0].".json";
  }

  private function getObjectJson() {
    
    $oObjeto       = new stdClass();
    $oObjeto->url  = $this->sFileName;
    $oObjeto->fields = $this->aFields;
    
    return $this->oJson->encode($oObjeto);
  }
  
  public function setFileName($sFileName) {
    
    $this->sFileName = $sFileName;
  }
  
  public function getFileName() {
    
    return $this->sFileName;
  }
  
  public function setFields($aFields) {
    
    $this->aFields = $aFields;
  }
  
  public function getFields() {
    
    return $this->aFields;
  }
  
  public function setIdUser ($iIdUser) {
    
    $this->iIdUser = $iIdUser;
  }
  
  public function getIdUser () {
  
    return $this->iIdUser;
  }
}
