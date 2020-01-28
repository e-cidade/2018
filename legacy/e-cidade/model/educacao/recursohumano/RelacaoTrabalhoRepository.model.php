<?php

/**
 * Repositoy para as Relações de Trabalho
 * @package   Educacao
 * @author    André Mello - andre.mello@dbseller.com.br
 */
class RelacaoTrabalhoRepository {

  const MENSAGEM_RELACAOTRABALHOREPOSITORY = "educacao.escola.RelacaoTrabalhoRepository.";

  private $aRelacoesTrabalho = array();

  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna a instância do Repositorio
   * @return RelacaoTrabalhoRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new RelacaoTrabalhoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna instância de Relação de Trabalho pelo código informado
   * @param  integer $iRelacaoTrabalho Código da Relação de Trabalho
   * @return RelacaoTrabalho
   */
  public static function getRelacaoTrabalhoByCodigo( $iRelacaoTrabalho ) {

    if (!array_key_exists($iRelacaoTrabalho, RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho)) {
      RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho[$iRelacaoTrabalho] = new RelacaoTrabalho($iRelacaoTrabalho);
    }

    return RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho[$iRelacaoTrabalho];
  }

  /**
   * Retorna as Relações de Trabalho de um Profissional da Escola
   * @param  ProfissionalEscola $oProfissionalEscola
   * @return RelacaoTrabalho[]
   */
  public static function getRelacaoTrabalhoByProfissionalEscola( ProfissionalEscola $oProfissionalEscola ) {

    $oDaoRelacaoTrabalho   = new cl_relacaotrabalho();
    $sWhereRelacaoTrabalho = " ed23_i_rechumanoescola = {$oProfissionalEscola->getCodigo()}";
    $sSqlRelacaoTrabalho   = $oDaoRelacaoTrabalho->sql_query_file( null, "ed23_i_codigo", null, $sWhereRelacaoTrabalho );
    $rsRelacaoTrabalho     = db_query( $sSqlRelacaoTrabalho );

    $oErro = new stdClass();
    if ( !$rsRelacaoTrabalho ) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGEM_RELACAOTRABALHOREPOSITORY . "erro_buscar_relacao_trabalho", $oErro ) );
    }

    $iLinhas           = pg_num_rows($rsRelacaoTrabalho);
    $aRelacoesTrabalho = array();

    if ( $iLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $iRelacaoTrabalho    = db_utils::fieldsMemory($rsRelacaoTrabalho, $iContador)->ed23_i_codigo;
        $aRelacoesTrabalho[] = RelacaoTrabalhoRepository::getRelacaoTrabalhoByCodigo( $iRelacaoTrabalho );
      }
    }

    return $aRelacoesTrabalho;
  }

  /**
   * Retorna as Relações de Trabalho de um Profissional da Escola
   * @param  AtividadeProfissionalEscola $oFuncao
   * @return RelacaoTrabalho[]
   */
  public static function getByFuncaoExercida( AtividadeProfissionalEscola $oFuncao ) {

    $sWhereRelacaoTrabalho = " ed03_i_rechumanoativ = {$oFuncao->getCodigo()}";
    
    $oDaoRelacao   = new cl_rechumanorelacao();
    $sSqlRelacao   = $oDaoRelacao->sql_query_file( null, "ed03_i_relacaotrabalho", null, $sWhereRelacaoTrabalho );
    $rsRelacao     = db_query( $sSqlRelacao );

    $oErro = new stdClass();
    if ( !$rsRelacao ) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGEM_RELACAOTRABALHOREPOSITORY . "erro_buscar_relacao_trabalho", $oErro ) );
    }

    $iLinhas           = pg_num_rows($rsRelacao);
    $aRelacoesTrabalho = array();

    if ( $iLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $iRelacaoTrabalho    = db_utils::fieldsMemory($rsRelacao, $iContador)->ed03_i_relacaotrabalho;
        $aRelacoesTrabalho[] = RelacaoTrabalhoRepository::getRelacaoTrabalhoByCodigo( $iRelacaoTrabalho );
      }
    }
    return $aRelacoesTrabalho;
  }

  
}