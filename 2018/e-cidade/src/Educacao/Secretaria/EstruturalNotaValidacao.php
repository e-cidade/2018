<?php

namespace ECidade\Educacao\Secretaria;

/**
 * Realiza as validações para inclusão do estrutural da nota
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class EstruturalNotaValidacao {

  /**
   * Valida se é possível incluir uma configuração para nota na secretaria de educação
   *
   * @param  integer $iAno Ano para o qual o estrutural foi configurado
   * @throws \Exception
   * @return boolean
   */
  static public function permiteInclusaoEstruturaNotaSecretaria( $iAno ) {

    $sWhere = " ed139_ativo is true and ed139_ano = {$iAno} ";
    return self::validarConfiguracaoNota(new \cl_avaliacaoestruturanotapadrao(), $sWhere);
  }

  /**
   * Não permite alterar a configuração da nota se houver no mesmo ano outra configuração ativa.
   *
   * @param  integer $iCodigo codigo PK
   * @param  integer $iAno    Ano para o qual o estrutural foi configurado
   * @param  boolean $lAtivo  situação do parâmetro atual
   * @return boolean
   */
  static public function permiteAlteracaoEstruturaNotaSecretaria($iCodigo, $iAno, $lAtivo) {

    // se estiver desativando a configuração
    if ( !$lAtivo ) {
      return true;
    }

    $sWhere = " ed139_ativo is true and ed139_ano = {$iAno} and ed139_sequencial <> {$iCodigo}";
    return self::validarConfiguracaoNota(new \cl_avaliacaoestruturanotapadrao(), $sWhere);
  }

  /**
   * Valida se é possível incluir uma configuração para nota na escola de educação
   *
   * @param  integer     $iEscola
   * @param  integer     $iAno     Ano para o qual o estrutural foi configurado
   * @throws \Exception
   * @return boolean
   */
  static public function permiteInclusaoEstruturaNotaEscola( $iEscola, $iAno ) {

    $sWhere = " ed315_ativo is true and ed315_escola = {$iEscola} and ed315_ano = {$iAno} ";
    return self::validarConfiguracaoNota(new \cl_avaliacaoestruturanota(), $sWhere);
  }


  /**
   * Não permite alterar a configuração da nota se houver no mesmo ano outra configuração ativa na escola.
   *
   * @param  integer $iCodigo codigo PK
   * @param  integer $iAno    Ano para o qual o estrutural foi configurado
   * @param  boolean $lAtivo  situação do parâmetro atual
   * @return boolean
   */
  static public function permiteAlteracaoEstruturaNotaEscola($iCodigo, $iAno, $lAtivo) {

    // se estiver desativando a configuração
    if ( !$lAtivo ) {
      return true;
    }

    $sWhere = " ed315_ativo is true and ed315_ano = {$iAno} and ed315_sequencial <> {$iCodigo}";
    return self::validarConfiguracaoNota(new \cl_avaliacaoestruturanota(), $sWhere);
  }

  /**
   * Executa a configuração de acordo com o módulo que esta realizando a manutenção nos parâmetros
   *
   * @param  Object  $oDao   instância da classe  cl_avaliacaoestruturanotapadrao ou cl_avaliacaoestruturanota
   * @param  string  $sWhere
   * @return boolean
   */
  private static function validarConfiguracaoNota($oDao, $sWhere ) {

    $sSql = $oDao->sql_query_file(null, "1", null, $sWhere);
    $rs   = db_query($sSql);

    if ( !$rs ) {
      throw new \Exception("Não foi possível validar configuração da nota.");
    }

    if (pg_num_rows($rs) > 0) {
      return false;
    }

    return true;
  }


  /**
   * Verifica se há diferença entre as configurações
   * - true  são diferentes
   * - false são iguais
   *
   * @param  SecretariaEstruturalNota $oSecretariaEstruturalNota
   * @param  EscolaEstruturalNota     $oEscolaEstruturalNota
   * @return boolean
   */
  static public function isDifirente(\SecretariaEstruturalNota $oSecretariaEstruturalNota, \EscolaEstruturalNota $oEscolaEstruturalNota) {

    if ( $oSecretariaEstruturalNota->deveArredondarMedia() != $oEscolaEstruturalNota->deveArredondarMedia() ) {
      return true;
    }

    if ( $oSecretariaEstruturalNota->getEstrutural()->getCodigo() != $oEscolaEstruturalNota->getEstrutural()->getCodigo() ) {
      return true;
    }

    $oRegraSecretaria = $oSecretariaEstruturalNota->getRegraArredondamento();
    $oRegraEscola     = $oEscolaEstruturalNota->getRegraArredondamento();

    if ( (!is_null($oRegraSecretaria) && is_null($oRegraEscola)) || (is_null($oRegraSecretaria) && !is_null($oRegraEscola)) ) {
      return true;
    }

    if ( (!is_null($oRegraSecretaria) && !is_null($oRegraEscola)) &&
         $oRegraSecretaria->getCodigo() != $oRegraEscola->getCodigo()) {
      return true;
    }

    return false;
  }
}
