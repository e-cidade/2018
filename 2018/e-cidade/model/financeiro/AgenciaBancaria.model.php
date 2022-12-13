<?php
/**
 * Representa uma agencia de um banco
 * Class AgenciaBancaria
 */
class AgenciaBancaria {

  /**
   * @var integer
   */
  protected $iCodigo;

  /**
   * Numero da Agencia
   * @var string
   */
  protected $sNumero;

  /**
   * Digito verificador da Agencia
   * @var string
   */
  protected $sDigito;

  /**
   * Endereco da Agencia
   * @var endereco
   */
  protected $oEndereco;

  /**
   * Banco da Agencia
   * @var string
   */
  protected $sBanco;

  /**
   * Instancia a agência Bancaria atravez d codigo
   *
   * @param integer $iCodigo codigo da agencia
   * @throws Exception
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoAgencia = new cl_bancoagencia();
      $sSqlAgencia = $oDaoAgencia->sql_query_endereco($iCodigo);
      $rsAgencia   = db_query($sSqlAgencia);
      if (!$rsAgencia || pg_num_rows($rsAgencia) == 0) {
        throw new Exception('Agência não Cadastrada');
      }

      $oDadosAgencia = db_utils::fieldsMemory($rsAgencia, 0);
      $this->setNumero($oDadosAgencia->db89_codagencia);
      $this->setDigito($oDadosAgencia->db89_digito);
      if (!empty($oDadosAgencia->db92_endereco)) {
        $this->oEndereco = new endereco($oDadosAgencia->db92_endereco);
      }
      $this->setBanco(new Banco($oDadosAgencia->db89_db_bancos));
    }
  }

  /**
   * Define o Endereco da agencia
   * @param mixed $oEndereco
   */
  public function setEndereco(endereco $oEndereco) {
    $this->oEndereco = $oEndereco;
  }

  /**
   * Endereco da Agencia
   * @return endereco
   */
  public function getEndereco() {
    return $this->oEndereco;
  }

  /**
   * @param mixed $sBanco
   */
  public function setBanco($sBanco) {
    $this->sBanco = $sBanco;
  }

  /**
   * Retorna o Banco da Agencia
   * @return Banco
   */
  public function getBanco() {
    return $this->sBanco;
  }

  /**
   * Retorna o digito da agencia
   * @param mixed $sDigito
   */
  public function setDigito($sDigito) {
    $this->sDigito = $sDigito;
  }

  /**
   * @return mixed
   */
  public function getDigito() {
    return $this->sDigito;
  }

  /**
   * Retorna o número da Agencia
   * @param mixed $sNumero
   */
  public function setNumero($sNumero) {
    $this->sNumero = $sNumero;
  }

  /**
   * Define o numero da Agencia
   * @return mixed
   */
  public function getNumero() {
    return $this->sNumero;
  }
} 