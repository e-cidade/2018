<?php

class Conexao {

  private static $oInstancia;
  private        $pConexao;
  const   COMMIT   = 1;
  const   ROLLBACK = 2;
  
  protected function __construct() {

    $aConfiguracoes        = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $aConfiguracoes        = $aConfiguracoes->banco_dados;

    $sConexao              = "host={$aConfiguracoes['bd_servidor']} ";
    $sConexao             .= "port={$aConfiguracoes['bd_porta']}    ";
    $sConexao             .= "dbname={$aConfiguracoes['bd_nome']}   ";
    $sConexao             .= "user={$aConfiguracoes['bd_usuario']}  ";
    $sConexao             .= "password={$aConfiguracoes['bd_senha']}";
    $this->pConexao        = pg_connect($sConexao);
  }

  /**
   * Retorna a instancia do Objeto
   */
  public static function getInstancia() {

    if ( empty(Conexao::$oInstancia) ) {
      Conexao::$oInstancia = new Conexao();
    }
    return Conexao::$oInstancia;
  } 

  /**
   * Retorna a Conexao Instanciada
   */
  public function getConexao() {
    return $this->pConexao;
  }
/**
 * Executa Query
 * @param string $sQuery
 */
  public function query( $sQuery ) {
    
    return pg_query($this->getConexao(), $sQuery);
  }
  /**
   * Inicia transação
   * @throws Exception - Quando Houver Erro ao Iniciar a Transacao;
   * @return boolean
   */
  public function begin() {
    
    if ( !$this->query("BEGIN;") ) {
       throw new Exception( "Erro ao Iniciar Transação. \n " . pg_last_error( $this->getConexao() ) );
    }
    return true;
  }
  
  /**
   * Encerra transação
   * @param  $iTipoEncerramento Se vai salvar as alterações(COMMIT) ou não (ROLLBACK)
   * @throws Exception - Quando Houver Erro ao Iniciar a Transacao;
   * @return boolean
   */
  public function end( $iTipoEncerramento = Conexao::COMMIT ) {

    if ( !db_utils::inTransaction($this->getConexao()) ) {
      throw new Exception( "Não é possível encerrar a transação sem antes Iniciá-la.\n " . $this->getLastError() );
    } 
    
    if ( $iTipoEncerramento == Conexao::COMMIT ) {
      return $this->query("COMMIT;");
    }
    $this->query("ROLLBACK;");
  }
  /**
   * Cria um ponto de salvamento dentro da Transção
   * @param  string $sNomeSavePoint
   * @throws Exception
   * @return boolean
   */
  public function createSavePoint( $sNomeSavePoint ) {
    
    if ( !db_utils::inTransaction($this->getConexao()) ) {
      throw new Exception( "Não é possível criar savepoint sem Transação Ativa.\n " . $this->getLastError() );
    }
    
    if ( !$this->query("SAVEPOINT $sNomeSavePoint;") ) {
      throw new Exception( "Erro ao Iniciar Transação.\n " . $this->getLastError() );
    }
    return true;  
  }
  
  /**
   * Cria um ponto de salvamento dentro da Transção
   * @param unknown $sNomeSavePoint
   * @throws Exception
   * @return boolean
   */
  public function destroySavePoint( $sNomeSavePoint ) {
  
    if ( !$this->query("RELEASE SAVEPOINT $sNomeSavePoint;") ) {
      throw new Exception( "Erro ao Iniciar Transação. \n " . $this->getLastError() );
    }
    return true;
  } 
  
  /**
   * Desfaz alterações efetuadas dentro de um savepoint
   * @param  string $sNomeSavePoint
   * @throws Exception
   * @return boolean
   */
  public function returnToSavePoint( $sNomeSavePoint ) {
  
    if ( !$this->query("ROLLBACK TO SAVEPOINT $sNomeSavePoint;") ) {
      throw new Exception( "Erro ao Iniciar Transação. \n " . $this->getLastError() );
    }
    return true;
  } 
 
  
  /**
   * REtorna o Ultimo erro da Conexão 
   */
  public function getLastError() {
    return pg_last_error( $this->getConexao() ); 
  }
}
