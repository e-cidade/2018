<?php

class DDTabelaXML {

	private $sXml         = "";
  private $sArqName     = "";
  private $sTableName   = "";
  private $sTableSchema = "";
  private $oTabela      = null;
  private $oDomXml      = null;
  public  $aCampos      = Array();
  public  $aFks         = Array();

  public function __construct($sTableName) {

  	global $sNiveis;

   	// echo "Criando estancia para {$sTableName} <br> ";
    $this->sArqName   = strtolower( ECIDADE_PATH . "dd/tabelas/{$sTableName}.dd.xml");
    if (!file_exists($this->sArqName)){
      throw (new Exception("Arquivo ".strtolower( ECIDADE_PATH . "dd/tabelas/{$sTableName}.dd.xml")." não encontrado."));
    }

    preg_match("/^(?:(?P<schema>.*)\.)?(?P<table>.*)$/", $sTableName, $aTableName);

    $this->sTableSchema = $aTableName["schema"];
    $this->sTableName   = $aTableName["table"];

    $this->oDomXml    = new DomDocument();
    $this->oDomXml->load($this->sArqName);

    $aTabelas = $this->oDomXml->getElementsByTagName("table");
    foreach ( $aTabelas as $oTabela ) {
      $this->oTabela = $oTabela;
    }

    $aCamposXML = $this->oDomXml->getElementsByTagName("field");
    foreach ( $aCamposXML as $oCampo ) {
      $this->aCampos[] = new DDCampoXML( $oCampo );
    }

    $aFkXML = $this->oDomXml->getElementsByTagName("foreignkey");
    foreach ( $aFkXML as $oFk ) {
      $this->aFks[] = new DDFkXML( $oFk );
    }

  }

  public function __get($sName){
    return $this->oTabela->getAttribute($sName);
  }

  /**
   * @return DDCampoXML[]
   */
  public function getCampos() {
  	return $this->aCampos;
  }

  /**
   * @return stdClass[]
   */
  public function getFieldsPk() {

  	$aFieldsPk = array();
  	foreach ($this->aCampos as $oCampo ) {
  		if ($oCampo->isPk()) {
    		$aFieldsPk[] = $oCampo;
  		}
  	}
    return $aFieldsPk;
  }

  /**
   * @return DDFkXML[]
   */
  public function getFks() {
    return $this->aFks;
  }

  /**
   * @return string
   */
  public function getTableName() {
    return $this->sTableName;
  }
}
