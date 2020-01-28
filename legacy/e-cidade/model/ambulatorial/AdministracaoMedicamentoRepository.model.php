<?php

/**
 * Classe repository para classes AdministracaoMedicamento
 *
 * @author
 * @package
 */
class AdministracaoMedicamentoRepository {

  /**
   * Collection de AdministracaoMedicamento
   *
   * @var array
   */
  private $aItens = array();
  /**
   * Instancia da classe
   *
   * @var AdministracaoMedicamentoRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do AdministracaoMedicamento pelo Codigo
   *
   * @param integer $iCodigo Codigo do AdministracaoMedicamento
   * @return AdministracaoMedicamento
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AdministracaoMedicamentoRepository::getInstance()->aItens)) {
      AdministracaoMedicamentoRepository::getInstance()->aItens[$iCodigo] = new AdministracaoMedicamento($iCodigo);
    }
    return AdministracaoMedicamentoRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return AdministracaoMedicamentoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AdministracaoMedicamentoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de AdministracaoMedicamento ao repositorio
   *
   * @param AdministracaoMedicamento $oAdministracaoMedicamento Instancia de AdministracaoMedicamento
   * @return boolean
   */
  public static function adicionarAdministracaoMedicamento(AdministracaoMedicamento $oAdministracaoMedicamento) {

    if (!array_key_exists($oAdministracaoMedicamento->getCodigo(), AdministracaoMedicamentoRepository::getInstance()->aItens)) {
      AdministracaoMedicamentoRepository::getInstance()->aItens[$oAdministracaoMedicamento->getCodigo()] = $oAdministracaoMedicamento;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param AdministracaoMedicamento $oAdministracaoMedicamento
   * @return boolean
   */
  public static function remover(AdministracaoMedicamento $oAdministracaoMedicamento) {
    /**
     *
     */
    if (array_key_exists($oAdministracaoMedicamento->getCodigo(), AdministracaoMedicamentoRepository::getInstance()->aItens)) {
      unset(AdministracaoMedicamentoRepository::getInstance()->aItens[$oAdministracaoMedicamento->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalAdministracaoMedicamento() {
    return count(AdministracaoMedicamentoRepository::getInstance()->aItens);
  }

  /**
   * Retorna todas as Administrações de Medicamentos realizados pela FAA
   *
   * @param Prontuario $oFaa
   * @return AdministracaoMedicamento[]
   * @throws Exception
   */
  public static function getAdministracoesDaFaa(Prontuario $oFaa) {

    $oDaoFaa            = new cl_prontuarioadministracaomedicamento();
    $sWhereProntuario   = "sd106_prontuario = {$oFaa->getCodigo()}";
    $sOrder             = "sd105_data, sd105_hora, fa01_c_nomegenerico";
    $sCampos            = "sd106_administracaomedicamento";
    $sSqlAdministracoes = $oDaoFaa->sql_query_administracao(null, $sCampos, $sOrder, $sWhereProntuario);

    $rsAdministracoesMedicamento = db_query($sSqlAdministracoes);
    if (!$rsAdministracoesMedicamento) {
      throw new Exception("Erro ao consultar dados da administração");
    }
    $aAdministracoesNAFaa = array();
    $iTotalLinhas = pg_num_rows($rsAdministracoesMedicamento);
    for ($iAdministracao = 0; $iAdministracao < $iTotalLinhas; $iAdministracao++) {

      $iCodigoAdministracao   = db_utils::fieldsMemory($rsAdministracoesMedicamento, $iAdministracao)->sd106_administracaomedicamento;
      $aAdministracoesNAFaa[] = AdministracaoMedicamentoRepository::getByCodigo($iCodigoAdministracao);
    }
    return $aAdministracoesNAFaa;
  }
}