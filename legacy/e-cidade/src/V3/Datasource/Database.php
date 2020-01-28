<?php

namespace ECidade\V3\Datasource;

use \Exception;
use \ECidade\V3\Extension\Registry;

class Database {

  public static $oInstance;

  /**
   * Resource da conexão com o banco
   * @var resource
   */
  private $oConnection;

  /**
   * Base de dados
   * @var string
   */
  private $sBase = "";

  /**
   * Servidor do banco de dados
   * @var string
   */
  private $sServidor = "";

  /**
   * Porta da base de dados
   * @var string
   */
  private $sPorta = "";

  /**
   * Usuário do banco
   * @var string
   */
  private $sUsuario  = "";

  /**
   * Senha da base de dados
   * @var string
   */
  private $sSenha    = "";

  public function setBase($sBase) {
    $this->sBase = $sBase;
  }

  public function setServidor($sServidor) {
    $this->sServidor = $sServidor;
  }

  public function setPorta($sPorta) {
    $this->sPorta = $sPorta;
  }

  public function setUsuario($sUsuario) {
    $this->sUsuario = $sUsuario;
  }

  public function setSenha($sSenha) {
    $this->sSenha = $sSenha;
  }

  public function getBase() {
    return $this->sBase;
  }

  /**
   * Retorna o resource de conexão com o banco
   * @return resource
   */
  public function getResource() {
    return $this->oConnection;
  }

  /**
   * Conecta na base de dados
   * @throws Exception
   * @return resource
   */
  public function connect() {
    $this->oConnection = pg_connect("host={$this->sServidor} port={$this->sPorta} dbname={$this->sBase} user={$this->sUsuario} password={$this->sSenha}");

    if(!$this->oConnection){
      throw new Exception("Não foi possível conectar na base de dados.");
    }

    $this->setEncoding(Registry::get('app.config')->get('db.client_encoding'));

    return $this->oConnection;
  }

  /**
   * Desconecta da base de dados
   * @return boolean
   */
  public function disconnect() {

    if ($this->oConnection) {
      return pg_close($this->oConnection);
    }
  }

  /**
   * @param string $encoding
   * @return bool
   */
  public function setEncoding($encoding) {
    return pg_set_client_encoding($this->oConnection, $encoding);
  }

  /**
   * Executa uma query na base de dados
   * @throws Exception
   * @param  string $sQuery Query a ser executada
   * @return recordset
   */
  public function execute($sQuery) {

    $rsResultSet = @pg_query($this->oConnection, $sQuery);

    if ($rsResultSet === false) {
      throw new Exception( pg_last_error($this->oConnection) );
    }

    return $rsResultSet;
  }

  /**
   * Retorna um array de objetos com os registros do recordset
   * @param  recordset $rsRecordset
   * @return array
   */
  public function getCollectionByRecord( $rsRecordset ) {
    return pg_fetch_all($rsRecordset);
  }

  /**
   * Retorna um objeto do registro buscado
   * @param  recordset $resource
   * @param  integer $index
   * @return stdclass
   */
  public function fetchRow( $resource, $index ) {

    $oObject = pg_fetch_object($resource, $index);

    // Tratamento feito para não dar erro nos campos texto vazios que são not null
    foreach ($oObject as $sKey => &$mValue) {
      $mValue = trim($mValue);
    }

    return $oObject;
  }

  /**
   * Get Instance
   * @return Database
   */
  public static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = self::build();
    }

    return self::$oInstance;
  }

  /**
   * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
   * @return Database Retorna o getInstance
   */
  public static function init() {
    return self::getInstance();
  }

  private static function build() {

    $session = Registry::get('app.request')->session();

    $oDatabase = new Database();
    $oDatabase->setBase($session->get('DB_NBASE', $session->get('DB_base')));
    $oDatabase->setServidor($session->get('DB_servidor'));
    $oDatabase->setPorta($session->get('DB_porta'));
    $oDatabase->setUsuario($session->get('DB_user'));
    $oDatabase->setSenha($session->get('DB_senha'));

    $oDatabase->connect();

    // @todo - validar
    $oDatabase->execute("SELECT set_config('search_path', current_setting('search_path') || ',plugins', false);");

    return $oDatabase;
  }

  public function begin() {

    if (!$this->inTransation()) {
      $this->execute('BEGIN');
    }

  }

  public function commit() {

    if ($this->inTransation()) {
      $this->execute('COMMIT');
    }

  }

  public function rollback() {

    if ($this->inTransation()) {
      $this->execute('ROLLBACK');
    }

  }

  public function inTransation() {

    switch( pg_transaction_status( $this->oConnection ) ) {

      case PGSQL_TRANSACTION_ACTIVE:
      case PGSQL_TRANSACTION_INTRANS:
        return true;
      break;
    }

    return false;
  }

}
