<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

class Database {

  /**
   * Resource da conexão com o banco
   *
   * @var resource
   */
  private $oConnection;

  /**
   * Base de dados
   *
   * @var string
   */
  private $sBase = "";

  /**
   * Servidor do banco de dados
   *
   * @var string
   */
  private $sServidor = "";

  /**
   * Porta da base de dados
   *
   * @var string
   */
  private $sPorta = "";

  /**
   * Usuário do banco
   *
   * @var string
   */
  private $sUsuario = "";

  /**
   * Senha da base de dados
   *
   * @var string
   */
  private $sSenha = "";

  /**
   * @var \Database
   */
  private static $oInstance;

  /**
   * Retorna uma conexão utilizando as variáveis da sessão
   *
   * @return \Database
   */
  public static function getInstance() {

    if (!self::$oInstance) {

      $oDatabase = new Database();
      $oDatabase->setServidor(db_getsession('DB_servidor'));
      $oDatabase->setPorta(db_getsession('DB_porta'));
      $oDatabase->setBase(db_getsession('DB_base'));
      $oDatabase->setUsuario(db_getsession('DB_user'));
      $oDatabase->setSenha(db_getsession('DB_senha'));
      $oDatabase->connect();

      self::$oInstance = $oDatabase;
    }

    return self::$oInstance;
  }

  /**
   * @param string $sBase
   */
  public function setBase($sBase) {
    $this->sBase = $sBase;
  }

  /**
   * @param string $sServidor
   */
  public function setServidor($sServidor) {
    $this->sServidor = $sServidor;
  }

  /**
   * @param string $sPorta
   */
  public function setPorta($sPorta) {
    $this->sPorta = $sPorta;
  }

  /**
   * @param string $sUsuario
   */
  public function setUsuario($sUsuario) {
    $this->sUsuario = $sUsuario;
  }

  /**
   * @param string $sSenha
   */
  public function setSenha($sSenha) {
    $this->sSenha = $sSenha;
  }

  /**
   * @return string
   */
  public function getBase() {
    return $this->sBase;
  }

  /**
   * Retorna o resource de conexão com o banco
   *
   * @return resource
   */
  public function getResource() {
    return $this->oConnection;
  }

  /**
   * Conecta na base de dados
   *
   * @throws Exception
   * @return resource
   */
  public function connect() {
    $this->oConnection = @pg_connect("host={$this->sServidor} port={$this->sPorta} dbname={$this->sBase} user={$this->sUsuario} password={$this->sSenha}");

    if(!$this->oConnection){
      throw new Exception("Não foi possível conectar na base de dados");
    }

    return $this->oConnection;
  }

  /**
   * Desconecta da base de dados
   *
   * @return boolean|null
   */
  public function disconnect() {

    if ($this->oConnection) {
      return pg_close($this->oConnection);
    }
  }

  /**
   * Executa uma query na base de dados
   *
   * @param  string $sQuery Query a ser executada
   * @throws Exception
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
  public static function getCollectionByRecord( $rsRecordset ) {

    $iINumRows = pg_num_rows($rsRecordset);
    $aDButils  = array();

    if ( $iINumRows > 0 ) {

      for ($iIndice = 0; $iIndice < $iINumRows; $iIndice++ ) {
        $aDButils[] = self::fetchRow($rsRecordset, $iIndice);
      }
    }

    return $aDButils;
  }

  /**
   * Retorna um objeto do registro buscado
   *
   * @param  recordset $resource
   * @param  integer $index
   * @return stdclass
   */
  public static function fetchRow($resource, $index) {

    $oObject = pg_fetch_object($resource, $index);

    // Tratamento feito para não dar erro nos campos texto vazios que são not null
    foreach ($oObject as $sKey => &$mValue) {
      $mValue = trim($mValue);
    }

    return $oObject;
  }

  /**
   * Retorna o número de linhas no recordset
   *
   * @param  resource $rsResultSet
   * @return integer
   */
  public function count($rsResultSet) {
    return pg_num_rows($rsResultSet);
  }

  /**
   * Retorna uma linha do recordset como um objeto
   *
   * @param  resource $rsResource
   * @param  integer  $iIndice
   * @return stdClass
   */
  public function fetchObject($rsResource, $iIndice) {
    return pg_fetch_object($rsResource, $iIndice);
  }

}
