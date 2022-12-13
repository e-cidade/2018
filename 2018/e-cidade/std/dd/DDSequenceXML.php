<?php

class DDSequenceXML {
  
  private $oSequenceXml = null;

  public function __construct(DOMNode $oDomNode){
    $this->oSequenceXml = $oDomNode;
  }
  
  public function __get($sName){    
    return $this->oSequenceXml->getAttribute($sName);          
  }
  
}
