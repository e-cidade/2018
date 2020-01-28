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

 require_once("libs/db_utils.php");
 require_once("libs/exceptions/ParameterException.php"); 
 require_once("libs/exceptions/FileException.php"); 
 require_once("libs/exceptions/DBException.php"); 
 require_once("model/configuracao/DBFileExplorer.model.php");


class DBDataBaseMigration {

  const DIRETORIO_SQL  = "db";

  private $pConnection;  

  /**
   * tableExists
   *
   * @param ponter $pConnection
   * @param string $sSchema
   * @param string $sTable
   * @static
   * @access public
   * @return boolean
   */
  public static function tableExists( $pConnection, $sSchema, $sTable ) {

    $sExistsTable  = "select 1                          ";
    $sExistsTable .= "  from information_schema.tables  ";
    $sExistsTable .= " where table_schema = '{$sSchema}' ";
    $sExistsTable .= "   and table_name   = '{$sTable}'  ";

    $rsExistsTable = @pg_query($pConnection, $sExistsTable);

    if ( !$rsExistsTable )  {
      throw new DBException(" Erro ao validar a Existencia da Tabela.");
    }
    return ( pg_num_rows($rsExistsTable) == 1 ); 
  }


  public static function createTable($pConnection, $sSchema, $sTable, $sDDL) {

    $lTableExists = DBDataBaseMigration::tableExists($pConnection, $sSchema, $sTable);

    if ( $lTableExists ) {
      return false;
    }

    $rsExecute = @pg_query($pConnection, $sDDL);

    if ( !$rsExecute ) {
      throw new DBException( "Erro ao Criar Tabela." . pg_last_error($pConnection) ); 
    }
    return true;
  }

  public static function checkTablesVersioning($connection) {

    $sExecute = " CREATE TABLE configuracoes.database_version (                            \n";
    $sExecute.= "     db142_versao      varchar   NOT NULL,                                \n";
    $sExecute.= "     db142_datacriacao timestamp without time zone DEFAULT now() NOT NULL \n";
    $sExecute.= " );                                                                       \n";
    $sExecute.= "                                                                          \n";
    $sExecute.= " ALTER TABLE ONLY configuracoes.database_version                          \n";
    $sExecute.= "   ADD CONSTRAINT database_version_pk PRIMARY KEY (db142_versao);         \n";

    if (!DBDataBaseMigration::createTable($connection, 'configuracoes', 'database_version', $sExecute)) {
      return false;
    }
    $sExecute = "   create table configuracoes.database_version_sql (                            \n"; 
    $sExecute.= "     db143_arquivo     character varying(100) not null,                         \n"; 
    $sExecute.= "     db143_versao      varchar                not null,                         \n"; 
    $sExecute.= "     db143_script      text                   not null,                         \n"; 
    $sExecute.= "     db143_tipo        varchar                not null,                         \n"; 
    $sExecute.= "     db143_executado   boolean                not null  default false           \n"; 
    $sExecute.= "   );                                                                           \n"; 
    $sExecute.= "                                                                                \n"; 
    $sExecute.= "   ALTER TABLE ONLY configuracoes.database_version_sql                          \n"; 
    $sExecute.= "     ADD CONSTRAINT database_version_sql_pk                                     \n"; 
    $sExecute.= "        PRIMARY KEY (db143_arquivo, db143_versao);                              \n"; 
    $sExecute.= "                                                                                \n"; 
    $sExecute.= "   CREATE INDEX database_version_sql_version_idx                                \n"; 
    $sExecute.= "             ON configuracoes.database_version_sql                              \n"; 
    $sExecute.= "          USING btree (db143_versao);                                           \n"; 
    $sExecute.= "                                                                                \n"; 
    $sExecute.= "   ALTER TABLE ONLY configuracoes.database_version_sql                          \n"; 
    $sExecute.= "     ADD CONSTRAINT database_version_sql_version_fk                             \n";
    $sExecute.= "        FOREIGN KEY (db143_versao) REFERENCES configuracoes.database_version(db142_versao); \n";

    if (!DBDataBaseMigration::createTable($connection, 'configuracoes', 'database_version_sql', $sExecute)) {
      return false;
    }
    return true;
  }

  /**
   * loadScripts
   *
   * @param mixed $connection
   * @static
   * @access public
   * @return void
   */
  public static function loadScripts($connection) {

    $sDirectoryScripts = DBDataBaseMigration::DIRETORIO_SQL;

    if ( !is_dir($sDirectoryScripts) ) {
      throw new FileException("ERRO: Diretório {$sDirectoryScripts} não existe!\n");
    }

    DBDataBaseMigration::checkTablesVersioning($connection);

    $sSqlLastVersion = "SELECT db142_versao FROM configuracoes.database_version ORDER BY db142_versao DESC LIMIT 1";
    $rLastVersion = @pg_query($connection, $sSqlLastVersion);

    if (!$rLastVersion) {
      throw new DBException("ERRO: Ao executar SQL $sSqlLastVersion ");
    }

    $sLastVersion = null;

    if (pg_num_rows($rLastVersion) > 0) {
      $sLastVersion = pg_result($rLastVersion, 0, 0);
    }

    $sDirectoryCheck = $sDirectoryScripts;
    $aDiretorios     = DBFileExplorer::listarDiretorio( $sDirectoryCheck, true, false );
    sort($aDiretorios);
    foreach ( $aDiretorios as $sDiretorio ) {

      $sVersao       = str_replace($sDirectoryScripts,'',$sDiretorio);
      $sVersao       = str_replace("/",'',$sVersao);
      $lExistePreDDL = is_file( "{$sDiretorio}/pre_{$sVersao}.sql" );
      $lExisteDDL    = is_file( "{$sDiretorio}/ddl_{$sVersao}.sql" );
      $aArquivosExecucao = array();  

      if ( $lExistePreDDL && filesize("{$sDiretorio}/pre_{$sVersao}.sql") > 0 ) {
        $aArquivosExecucao["pre"] = file_get_contents("{$sDiretorio}/pre_{$sVersao}.sql");  
      }

      if ( $lExisteDDL && filesize("{$sDiretorio}/ddl_{$sVersao}.sql") > 0 ) {
        $aArquivosExecucao["ddl"] = file_get_contents("{$sDiretorio}/ddl_{$sVersao}.sql");  
      }

      $sVersaoEscapada = pg_escape_string($sVersao);
      $sSqlExists      = "SELECT * FROM configuracoes.database_version WHERE db142_versao = '{$sVersaoEscapada}'";
      $rsExists        = @pg_query($connection, $sSqlExists);

      if (!$rsExists) {
        throw new DBException( "ERRO: Ao executar SQL $sSqlExists . " . pg_last_error() );
      }

      if ( pg_num_rows($rsExists) == 0 ) {

        //db_log("AVISO: Inserindo versÃ£o {$iLastVersion} do schema da base de dados", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
        $sInsert = "INSERT INTO configuracoes.database_version VALUES ('{$sVersaoEscapada}')";
        $rInsert = @pg_query($connection, $sInsert);

        if ( !$rInsert ) {
          throw new Exception("Erro ao Incluir Versão {$sVersao}" . pg_last_error($connection) ); 
        }
      }

      foreach ($aArquivosExecucao as $sTipo => $sConteudo ) {
        

        $sSqlExists      = "SELECT * FROM configuracoes.database_version_sql WHERE db143_versao = '{$sVersaoEscapada}' and db143_tipo = '$sTipo' and db143_executado is true;";
        $rsExists        = @pg_query($connection, $sSqlExists);

        if (!$rsExists) {
          throw new DBException( "ERRO: Ao executar SQL $sSqlExists " . pg_last_error() );
        }

        if ( pg_num_rows($rsExists) > 0 ) {
          continue;
        }




        $sArquivoEscapado    = pg_escape_string("{$sDiretorio}/{$sTipo}_{$sVersao}.sql");
        $sConteudoEscapado   = pg_escape_string($sConteudo);
        $rDelete             = @pg_query($connection, "delete from configuracoes.database_version_sql WHERE db143_versao = '{$sVersaoEscapada}' and db143_tipo = '$sTipo'");
        if ( !$rDelete ) {
          throw new Exception("Erro ao Remover script {$sVersao}" . pg_last_error($connection) ); 
        }
        $sInsertSql          = "INSERT INTO configuracoes.database_version_sql (db143_arquivo, db143_versao, db143_script, db143_tipo) ";
        $sInsertSql         .= "     VALUES ('{$sArquivoEscapado}', '{$sVersaoEscapada}', '{$sConteudoEscapado}','{$sTipo}')";
        $rInsertSql= @pg_query($connection, $sInsertSql);

        if ( !$rInsertSql ) {
          throw new Exception("Erro ao Incluir script {$sVersao}" . pg_last_error($connection) ); 
        }
      }
    }
  }


  public static function upgradeDatabase( $connection, $sTipo ) {

    DBDataBaseMigration::loadScripts( $connection );

    $sScriptsNotApplied = "select * from configuracoes.database_version_sql where db143_executado is false and db143_tipo = '$sTipo' order by db143_versao asc, db143_tipo desc  ";
    $rScriptsNotApplied = @pg_query($connection, $sScriptsNotApplied);

    if (!$rScriptsNotApplied) {
      throw new DBException( "ERRO: Ao executar SQL {$sScriptsNotApplied} " . pg_last_error() );
    }

    $iCount   = pg_num_rows($rScriptsNotApplied);

    for ( $x = 0; $x < $iCount; $x++ ) {

      $oScript = db_utils::fieldsMemory($rScriptsNotApplied, $x);
      $rExecute = @pg_query($connection, $oScript->db143_script); 

      if (!$rExecute) {
        throw new DBException( "ERRO: Ao executar SQL: $oScript->db143_script \n\nVersao:$oScript->db143_versao\nArquivo:$oScript->db143_arquivo " . pg_last_error(). "\n" );
      }

      $sUpdateApplied  = "  update configuracoes.database_version_sql                         \n";
      $sUpdateApplied .= "     set db143_executado = true                       \n";
      $sUpdateApplied .= "   where db143_arquivo   = '{$oScript->db143_arquivo}'\n";
      $sUpdateApplied .= "     and db143_versao    = '{$oScript->db143_versao}' \n"; 
      $sUpdateApplied .= "     and db143_tipo      = '{$oScript->db143_tipo}'   \n";   
      $rUpdateApplied  = @pg_query($connection, $sUpdateApplied);

      if (!$rUpdateApplied) {
        throw new DBException( "ERRO: Ao executar SQL {$sUpdateApplied} " . pg_last_error() );
      }
    }
    return true;
  }
}