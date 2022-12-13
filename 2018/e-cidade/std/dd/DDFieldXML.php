<?php

class DDFieldXML {
  
  private $oFieldFkXml = null;

  public function __construct(DOMNode $oDomNode){
    $this->oFieldXml = $oDomNode;
  }

  public function __get($sName){
    return $this->oFieldXml->getAttribute($sName);          
  }
  
}
