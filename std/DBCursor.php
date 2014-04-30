<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Classe que implementa cursor do banco de dados
 * @author    Rafael Serpa Nery - rafael.nery@dbseller.com.br
 * @package   Configuração
 * @revision  $Author: dbrafael.nery $
 * @version   $Revision: 1.1 $
 */
class DBCursor{

  /**
   * Nome da Tabela
   * @var string
   */
  public  $sTableName = null;

  /**
   * SQL a ser executado no banco de dados
   * @var string
   */
  private $sSql       = null;

  /**
   * Quantidade de Registros que serão retornados
   * @var integer
   */
  private $iNumRows   = 0;

  /**
   * Contrutor da classe
   * @param string $sTableName - nome da Tabela que será executado o sql
   * @param string $sSql       - sql que será executado no banco de dados
   * @throws Exception
   */
  public  function __construct($sTableName, $sSql = null){

    $this->sTableName = $sTableName;

    db_inicio_transacao();

    if ( trim($sSql) != '' ) {
      $this->setSql($sSql);
    }

    $sDeclareCursor = "declare cur_$sTableName cursor for ".$this->getSql();
    $rsCursor       = db_query($sDeclareCursor);

    if (!$rsCursor){
      throw new Exception("Erro ao criar cursor para tabela {$this->sTableName}");
    }

  }

  /**
   * Destrutor da Classe
   * ao encerrrar a bloco de transação do banco
   */
  public  function __destruct(){
    self::terminateExecution();
  }

  /**
   * Encerra Transação com o banco
   */
  private function terminateExecution () {
    db_query("rollback;");
  }

  /**
   * Retorna os registros enquanto existirem, quando não houver mais retorna false
   * @return result | bool(false)
   */
  public  function getRecordSet(){

    $sSql  =  "fetch {$iLimit} from cur_{$this->sTableName} ";
    $rsSQL =  db_query($this->pConexao,$sSql);
     
    if ( $rsSQL ) {

      if( pg_num_rows($rsSQL) > 0 ) {
        return $rsSQL;
      } else {
        self::terminateExecution();
        return false;
      }
    } else {
      throw new Exception("Erro retornar dados do SQL");
    }
  }

  /**
   * Caso estiver setado sql no contrustor da classe retorna o valor passado
   * caso contrario busca sql no tmp do dbportal
   * @throws Exception
   * @return string
   */
  private function getSql(){

    if (empty($this->sSql)) {

      $sFileName = "tmp/{$this->sTableName}.sql";

      if (!file_exists($sFileName)) {
        throw new Exception("Arquivo {$sFileName} nao existe !");
      }

      $this->sSql = file_get_contents($sFileName);
    }
    return $this->sSql;
  }

  /**
   * Define a string sql a ser executada
   * @param string $sSql
   */
  private function setSql($sSql){
    $this->sSql = $sSql;
  }

  /**
   * Define o numero de registros a serem retornados a cada fetch do banco de dados
   * @param integer $iNumRows
   */
  public  function setNumRows($iNumRows){
    $this->iNumRows = $iNumRows;
  }
}