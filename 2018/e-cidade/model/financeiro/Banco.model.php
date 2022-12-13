<?php

/**
 * Representa um banco
 * Class Banco
 */
class Banco {


  private $sCodigo;

  private $sNome;

  /**
   * Instancia um novo Banco
   * @throws
   * @param $sCodigo
   */
  public function __construct($sCodigo) {

    if (!empty($sCodigo)) {

      $oDaoBanco = new cl_db_bancos();
      $sSqlDadosBanco = $oDaoBanco->sql_query_file($sCodigo);
      $rsDadosBanco   = $oDaoBanco->sql_record($sSqlDadosBanco);
      if (!$rsDadosBanco || $oDaoBanco->numrows == 0) {
        throw new Exception('Banco não Cadastrado');
      }

      $oDadosBanco   = db_utils::fieldsMemory($rsDadosBanco, 0);
      $this->sCodigo = $sCodigo;
      $this->sNome   = $oDadosBanco->db90_descr;
    }
  }

  /**
   * @return mixed
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * @return mixed
   */
  public function getNome() {
    return $this->sNome;
  }



} 