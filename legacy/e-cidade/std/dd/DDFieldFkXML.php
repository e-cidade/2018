<?php

class DDFieldFkXML {
  
  private $oFieldFkXml = null;

  public function __construct(DOMNode $oDomNode){
    $this->oFieldFkXml = $oDomNode;
  }

  public function __get($sName){
    return $this->oFieldFkXml->getAttribute($sName);          
  }
  
}
