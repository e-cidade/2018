<?php

/**
* Repositório da tabela de valores de previdencia.
*/
class TabelaValoresPrevidenciaRepository extends TabelaValoresRepository {
 
  public static function getInstancia() {

    if (!DBRegistry::get("TabelaValoresPrevidenciaRepository")){
      DBRegistry::add("TabelaValoresPrevidenciaRepository", new static());
    }
    return DBRegistry::get("TabelaValoresPrevidenciaRepository");
  }

  public function getMaker($oTabela) {

    return function($oDadosTabela) use ($oTabela) {

      $oFaixa = new FaixaValorPrevidencia();
      $oFaixa->setInicio($oDadosTabela->r33_inic);
      $oFaixa->setFim($oDadosTabela->r33_fim);
      $oFaixa->setPercentual($oDadosTabela->r33_perc);
      $oFaixa->setTetoInativos($oDadosTabela->r33_tinati);
      return $oTabela->addFaixa($oFaixa);
    };
  }
  
  protected function getQuery($iCodigo) {

    $oDaoPrevidencia    = new cl_inssirf();
    $sWherePrevidencia  = 'r33_anousu     = ' . DBPessoal::getAnoFolha();
    $sWherePrevidencia .= 'and r33_mesusu = ' . DBPessoal::getMesFolha();
    $sWherePrevidencia .= 'and r33_instit = ' . db_getsession('DB_instit');
    $sWherePrevidencia .= 'and r33_codtab = ' . $iCodigo;
    $sSqlPrevidencia    = $oDaoPrevidencia->sql_query_file(null, null, "r33_inic, r33_fim, r33_perc, r33_tinati", "random()", $sWherePrevidencia);
    
    return $sSqlPrevidencia;
  }

  protected function getTabela($iCodigo) {
    return new TabelaValoresPrevidencia($iCodigo);
  }
}