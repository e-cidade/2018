<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


/**
 * Classe para conexao com o a base de dados
 * 
 * @author Gilton Guma <gilton@dbseller.com.br>
 *
 */
class Conexao {

  private static $oInstancia;
  private        $pConexao;
  const          COMMIT   = 1;
  const          ROLLBACK = 2;
  
  public function __construct() {
    
    require(realpath('libs/db_conn.php'));
    
    $sConexao       = "host={$DB_SERVIDOR} port={$DB_PORTA} dbname={$DB_BASE} user={$DB_USUARIO} password={$DB_SENHA}";
    $this->pConexao = pg_connect($sConexao);
  }

  /**
   * Retorna a instancia do Objeto
   */
  public static function getInstancia() {

    if (empty(Conexao::$oInstancia)) {
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
  public function query($sQuery) {
    
    return db_query($this->getConexao(), $sQuery);
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