<?php
class DDCampoXML {

  private $oCampoXml    = null;
  public  $oSequenceXml = null;

  /**
   * DE/Para dos tipos de dado
   * @var array
   */
  private static $aDataType = array(
    'char'    => 'varchar',
    'varchar' => 'varchar',
    'text'    => 'varchar',
    'date'    => 'date',
    'int4'    => 'integer',
    'int8'    => 'integer',
    'float4'  => 'numeric',
    'float8'  => 'numeric'
  );

  public function __construct(DOMNode $oDomNode){

    $this->oCampoXml = $oDomNode;
    $aSequenceXML    = $this->oCampoXml->getElementsByTagName("sequence");

    foreach ( $aSequenceXML as $oSequence ) {

      $this->oSequenceXml = new DDSequenceXML( $oSequence );
      break;
    }
  }

  public function __get($sName){
    return $this->oCampoXml->getAttribute($sName);
  }

  /**
   * Retorna o tipo de dado do campo
   * @return mixed|null
   */
  public function getDataType() {

    preg_match("/varchar|char|numeric/", $this->conteudo, $aTipoDadoEncontrado);
    $sTipoDeConteudo = $this->conteudo;
    if (!empty($sTipoDado[0])) {
      $sTipoDeConteudo = $sTipoDado[0];
    }
    return !empty(self::$aDataType[$sTipoDeConteudo]) ? self::$aDataType[$sTipoDeConteudo] : null;
  }

  public function getSequence() {
    if (empty($this->oSequenceXml)) {
      return false;
    }
    return $this->oSequenceXml;
  }

  public function isPk() {
    if ($this->ispk == 't') {
      return  true;
    }
    return false;
  }

}
