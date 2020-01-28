<?php 

/**
 * Classe para interpretar formulas baseadas em SQL
 */
abstract class DBFormula {

  /**
   * Fórmulas que ficarão em memória, que sejam utilizadas
   */
  protected $aFormulas          = array();
  protected $aFormulasEstaticas = array();

  const REGEX_PARSE  = "|\[(.*)\]|U";

  /**
   * Interpreta a fórmula
   *
   * @param String $sFormula
   */
  public function parse($sFormula) {

    if ( empty($this->aFormulas) ) {

      DBFormula::carregarFormulas($this);

    }

    preg_match_all(DBFormula::REGEX_PARSE, $sFormula, $aResposta, PREG_PATTERN_ORDER);

    if (count($aResposta[0]) == 0) {
      return $sFormula;
    }

    $aResposta = array_combine($aResposta[1], $aResposta[0]);

    if (!$aResposta) {
      return $sFormula;
    }

    if( count($aResposta) == 0 || count($this->aFormulas) == 0) {
      return $sFormula;
    }

    $aResposta = array_intersect_key($aResposta, $this->aFormulas);

    if (count($aResposta) == 0) {

      /**
       * Retorna quando não consegue mais achar nenhuma formula conhecida
       */
      if(preg_match_all(DBFormula::REGEX_PARSE, $sFormula, $aFormulasRestantes) == 0) {
        return $sFormula;
      } else {
        /**
         * Se ainda há fórmulas que são desconhecidas, deve retornar erro para nao ocorrer erro de SQL
         */
        throw new BusinessException("Há fórmulas desconhecidas. Verifique.");
      }
    }

    /**
     * Percorre as ocorrencias conhecidas e as substitui
     */
    foreach ($aResposta as $sNome => $sVariavel) {

      $sValor   = $this->aFormulas[$sNome] === "" ? "" : " (" . $this->aFormulas[$sNome] . ") ";

      $sFormula = str_replace($sVariavel, $sValor, $sFormula);
      $sFormula = str_replace(";", "", $sFormula);
    }
  
    return $this->parse($sFormula);
  }

  /**
   * carregarFormulas
   */
  private static function carregarFormulas( DBFormula $oFormula ) {

    $oDaoDBFormulas = new cl_db_formulas();
    $sSql           = $oDaoDBFormulas->sql_query_file(null, "db148_formula, db148_nome", "", "db148_ambiente is false");
    $rsSql          = db_query($sSql);

    if ( !$rsSql ) {
      throw new DBException("Erro ao buscar as fórmulas cadastradas.");
    }

    foreach (db_utils::getCollectionByRecord($rsSql) as $oDados) {
      $oFormula->aFormulas[$oDados->db148_nome] = $oDados->db148_formula;      
    }
 
    $oFormula->aFormulas = array_merge($oFormula->aFormulas, $oFormula->aFormulasEstaticas);
    return;
  }

  /**
   * Adiciona ao ambiente, variáveis com valores definidos
   *
   * @param mixed $sVariavel
   * @param mixed $sFormula
   */
  public function adicionar($sVariavel, $sFormula){
    $this->aFormulasEstaticas[$sVariavel] = $sFormula;
  }


}

