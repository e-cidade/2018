<?php

/**
 * Repositorio de Tabela de Valores
 */
class TabelaValoresRepository {

  protected static $oInstance;

  protected function __construct() {}
  protected function __clone() {}

  /**
  *	Retorna a instancia
  *
  *  @return TabelaValores $oTabalaValores
  */
  public static function getInstancia() {

    if (!DBRegistry::get("TabelaValoresRepository")){
      DBRegistry::add("TabelaValoresRepository", new static());
    }

    return DBRegistry::get("TabelaValoresRepository");
  }

  protected function getQuery($iCodigoTabela) {
  
    $oDaoFaixaValoresIRRF   = new cl_faixavaloresirrf();
    $sWhereFaixaValoresIRRF = " db149_sequencial = " . $iCodigoTabela;
    $sSqlFaixaValoresIRRF   = $oDaoFaixaValoresIRRF->sql_query(null, "*", null, $sWhereFaixaValoresIRRF);
    return $sSqlFaixaValoresIRRF;
  }

  /**
   *
   *  @param  TabelaValores $oTabela [description]
   *  @return [type]                 [description]
   */
  public function make (TabelaValores $oTabelaIRRF) {

    $iCodigoTabela          = $oTabelaIRRF->getCodigo();
    $rsFaixaValoresIRRF     = db_query($this->getQuery($iCodigoTabela));

    if(!$rsFaixaValoresIRRF) {
      throw new DBException("Ocorreu um erro ao recuperar a tabela de IRRF.");
    }

    db_utils::makeCollectionFromRecord($rsFaixaValoresIRRF, $this->getMaker($oTabelaIRRF));

    static::getInstancia()->add($oTabelaIRRF);
    return $oTabelaIRRF;
  }

  public function add(TabelaValores $oTabela) {

    $sChave     = "TabelaValoresRepository:" . $oTabela->getCodigo();
    return DBRegistry::add($sChave, $oTabela);
  }

  public static function getByCodigo($iCodigo) {

    $sChave     = "TabelaValoresRepository:" . $iCodigo;
    $oInstancia = DBRegistry::get($sChave);

    if (!$oInstancia) {
      static::getInstancia()->make( static::getInstancia()->getTabela($iCodigo) );
    }

    return DBRegistry::get($sChave);
  }

  public function getMaker($oTabelaIRRF) {
    return function($oDadosTabela) use ($oTabelaIRRF) {

      $oFaixa = new FaixaValor();
      $oFaixa->setInicio($oDadosTabela->db150_inicio);
      $oFaixa->setFim($oDadosTabela->db150_final);
      return $oTabelaIRRF->addFaixa($oFaixa);
    };
  }

  protected function getTabela($iCodigo) {
    return new TabelaIRRF($iCodigo);
  }
}
