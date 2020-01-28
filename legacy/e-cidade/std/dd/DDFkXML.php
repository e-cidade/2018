<?php

class DDFkXML {
  
  private $oFkXml = null;

  public function __construct(DOMNode $oDomNode){
    $this->oFkXml = $oDomNode;
  }

  public function __get($sName){
    return $this->oFkXml->getAttribute($sName);          
  }

  public function getFields() {  	
  	$aFields = array();
  	foreach ( $this->oFkXml->getElementsByTagName("fieldfk") as $oFieldFk ) {
  	  $aFields[] = new DDFieldFkXML($oFieldFk);
  	}
  	return $aFields;  	
  }
}
