<?php
class DDXMLFactory {
  
  private static $aInstancias = Array ();
  //echo   
  private function __construct() {}
  
  // O método singleton 
  static public function getInstance($sTableName) {
    if (! isset ( self::$aInstancias[$sTableName] )) {
      self::$aInstancias[$sTableName] = new DDTabelaXML($sTableName);
    }
    return self::$aInstancias[$sTableName];
  }

}

