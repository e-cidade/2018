<?php

//class rotulo extends RotuloBasica{

class RotuloXML extends RotuloBasica {

  private $sXml       = "";
  private $sArqName   = "";
  private $sTableName = "";
  private $oTabela    = Array();
  private $aCampos    = Array();
  private $oDomXml    = null;

  function RotuloXML($sTableName) {

    $this->oTabela = DDXMLFactory::getInstance($sTableName);
    $this->aCampos = $this->oTabela->getCampos();    

  }

  function rlabel($sNomeCampo = "") {

    foreach ( $this->aCampos as $oCampo ) {

      global ${"RL".$oCampo->name};
      ${"RL".$oCampo->name} = ucfirst(utf8_decode($oCampo->labelrel));
      if (isset($sNomeCampo) && trim($sNomeCampo) == $oCampo->name) {
        return true;
      }

    }
  }

  function label($sNomeCampo = "") {

    foreach ( $this->aCampos as $oCampo ) {
    
      $this->makePropertiesDDField($oCampo);
      
      if (isset($sNomeCampo) && trim($sNomeCampo) == $oCampo->name) {
        return true;
      }
    }
  }

  function tlabel($sNome = "") {
  
    global ${"L".$this->oTabela->name};
    ${"L".$this->oTabela->name} = "<strong>".utf8_decode($this->oTabela->label).":</strong>";

    global ${"T".$this->oTabela->name};
    ${"T".$this->oTabela->name} = utf8_decode($this->oTabela->description);

  }
}
