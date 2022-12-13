<?php 
/**
 * Classe criada para ajustar o valor de adiantamento da pensãoi quando for necessário cálculo de IRRF
 * Consiste basicamente em zerar o valor do ponto para calcular a pensão de forma correta e depois retornar
 * este valor para o recalculo do IRRF 
 * 
 * @package    Pessoal
 * @subpackage Calculo Financeiro
 * @author     Rafael Nery <rafael.nery@dbseller.com.br> 
 */
abstract class AjusteAdiantamentoPensao {


  private static $aValoresDescontoAdiantamentoPensao = array();

  private static $lAtivado = false;

  /**
   * Habilita a Rotina
   *
   * @static
   * @access private
   * @return void
   */
  public static function enable() {
    self::$lAtivado = true;
  }

  /**
   * Desabilita o Ajuste
   *
   * @static
   * @access private
   * @return void
   */
  private static function disable() {
    self::$lAtivado = false;
  }
  /**
   * Persiste os valores em memória para que sejam recriados após recalculo de pensao
   *
   * @access public
   * @return void
   */
  public static function gravarValores() {

    if (!self::$lAtivado) {
      return true;
    }

    $oDaoFolha13o = new cl_pontof13;
    $sSql         = $oDaoFolha13o->sql_query_file(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), null, 'R980', 'r34_regist,r34_valor');
    $rsSqlDados   = db_query($sSql);
    foreach ( db_utils::getCollectionByRecord($rsSqlDados) as $oDados ) {
      self::$aValoresDescontoAdiantamentoPensao[$oDados->r34_regist] = $oDados->r34_valor;
    }
   
    return true;
  }

  /**
   * Remove todas as rubricas R980 da base para que não interfira nos calculos de pensão do adiandamento de
   * 13º salário
   *
   * @static
   * @access public
   * @return void
   */
  public static function limparValores() {
   
    if (!self::$lAtivado) {
      return true;
    }
    $oDaoFolha13o             = new cl_pontof13;
    $oDaoFolha13o->r34_anousu = DBPessoal::getAnoFolha();
    $oDaoFolha13o->r34_mesusu = DBPessoal::getMesFolha();
    $oDaoFolha13o->r34_rubric = 'R980';
    $oDaoFolha13o->r34_valor  = "0";
    $oDaoFolha13o->alterar(DBPessoal::getAnoFolha(),DBPessoal::getMesFolha(), null, 'R980'); 
    return; 
  }

  /**
   * Retorna o valor do ponto de 13º para que o cálculo de IRRF seja feito de forma correta
   *
   * @param mixed $iMatricula
   * @static
   * @access public
   * @return void
   */
  public static function retornarValor($iMatricula = null ) {

    if (!self::$lAtivado) {
      return true;
    }

    if ( is_null($iMatricula) ) {

      foreach ( self::$aValoresDescontoAdiantamentoPensao as $sMatricula => $nValor ) {
        self::retornarValor($sMatricula);
      }
    }


    if ( !array_key_exists($iMatricula, self::$aValoresDescontoAdiantamentoPensao) ) {
      return;
    }
   
    $oDaoFolha13o             = new cl_pontof13;
    $oDaoFolha13o->r34_anousu = DBPessoal::getAnoFolha();
    $oDaoFolha13o->r34_mesusu = DBPessoal::getMesFolha();
    $oDaoFolha13o->r34_regist = $iMatricula;
    $oDaoFolha13o->r34_rubric = 'R980';
    $oDaoFolha13o->r34_valor  = self::$aValoresDescontoAdiantamentoPensao[$iMatricula];
    $oDaoFolha13o->alterar(DBPessoal::getAnoFolha(),DBPessoal::getMesFolha(), $iMatricula, 'R980'); 
    unset(self::$aValoresDescontoAdiantamentoPensao[$iMatricula]);
    return; 
  }



}
