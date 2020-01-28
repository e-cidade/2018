<?php 
abstract class RotuloBasica {

  public function makePropertiesDDField ($oCampo) {

    $aMapVariavelCampo = array( "I"  => utf8_decode($oCampo->aceitatipo),
                                "A"  => utf8_decode($oCampo->autocompl), // verificar
                                "U"  => utf8_decode($oCampo->null),
                                "G"  => utf8_decode($oCampo->uppercase),
                                "S"  => utf8_decode($oCampo->label),
                                "L"  => utf8_decode($oCampo->label),     // verificar
                                "LS" => utf8_decode($oCampo->label),
                                "T"  => utf8_decode($oCampo->description),
                                "M"  => utf8_decode($oCampo->size),
                                "N"  => utf8_decode($oCampo->null),      // verificar
                                "RL" => utf8_decode($oCampo->labelrel),
                                "TC" => utf8_decode($oCampo->datatype) );

    foreach ( $aMapVariavelCampo as $sPrefixvar => $sValor ) {
      

      global ${$sPrefixvar.$oCampo->name};
      ${$sPrefixvar.$oCampo->name} = $sValor;          
    }

    /// variavel para determinar o autocomplete
    if (${"A".$oCampo->name} == 'f') {
      ${"A".$oCampo->name} = "off";
    } else {
      ${"A".$oCampo->name} = "on";
    }

    /// variavel para colocar como label de campo
    ${"L".$oCampo->name} = "<strong>".${"L".$oCampo->name}.":</strong>";

    /// variavel para controle de campos nulos
    if (${"N".$oCampo->name} == "t"){
      ${"N".$oCampo->name} = "style=\"background-color:#E6E4F1\"";
    } else {
      ${"N".$oCampo->name} = "";
    }
  }
}
