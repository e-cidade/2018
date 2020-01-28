<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

 require_once(modification("libs/db_utils.php"));
 require_once(modification("libs/exceptions/ParameterException.php"));
 require_once(modification("libs/exceptions/FileException.php"));
 require_once(modification("libs/exceptions/DBException.php"));
 require_once(modification("model/configuracao/DBFileExplorer.model.php"));


class DBDataBaseMigration {

  const DIRETORIO_SQL         = "db";
  const DIRETORIO_SQL_ACERTOS = "db/DML";

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
  public static function loadScripts($connection, $sDiretorioBase = '') {

    $sDirectoryScripts = DBDataBaseMigration::DIRETORIO_SQL;
    $sSql              = '';

    if ( $sDiretorioBase != '' ) {
      $sDiretorioScripts = $sDiretorioBase;
    }

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

      if($sDiretorio == DBDataBaseMigration::DIRETORIO_SQL_ACERTOS) {

        continue;
      }

      $sVersao           = str_replace($sDirectoryScripts,'',$sDiretorio);
      $sVersao           = str_replace("/",'',$sVersao);
      $lExistePreDDL     = is_file( "{$sDiretorio}/up/pre_{$sVersao}.sql" );
      $lExisteDDL        = is_file( "{$sDiretorio}/up/ddl_{$sVersao}.sql" );
      $aArquivosExecucao = array();

      if ( $lExistePreDDL && filesize("{$sDiretorio}/up/pre_{$sVersao}.sql") > 0 ) {
        $aArquivosExecucao["pre"] = file_get_contents("{$sDiretorio}/up/pre_{$sVersao}.sql");
      }

      if ( $lExisteDDL && filesize("{$sDiretorio}/up/ddl_{$sVersao}.sql") > 0 ) {
        $aArquivosExecucao["ddl"] = file_get_contents("{$sDiretorio}/up/ddl_{$sVersao}.sql");
      }

      $sVersaoEscapada = pg_escape_string($sVersao);
      $sSqlExists      = "SELECT * FROM configuracoes.database_version WHERE db142_versao = '{$sVersaoEscapada}'";
      $rsExists        = @pg_query($connection, $sSqlExists);

      if (!$rsExists) {
        throw new DBException( "ERRO: Ao executar SQL $sSqlExists . " . pg_last_error() );
      }

      if ( pg_num_rows($rsExists) == 0 ) {

        $sInsert = "INSERT INTO configuracoes.database_version VALUES ('{$sVersaoEscapada}');\n";
        $sSql   .= $sInsert;
        $rInsert = @pg_query($connection, $sInsert);

        if ( !$rInsert ) {
          throw new Exception("Erro ao Incluir Versão {$sVersao}" . pg_last_error($connection) );
        }
      }

      foreach ($aArquivosExecucao as $sTipo => $sConteudo ) {

        $sSqlExists      = "SELECT * FROM configuracoes.database_version_sql WHERE db143_versao = '{$sVersaoEscapada}' and db143_tipo = '$sTipo' and db143_executado is true;\n";
        $rsExists        = @pg_query($connection, $sSqlExists);

        if (!$rsExists) {
          throw new DBException( "ERRO: Ao executar SQL $sSqlExists " . pg_last_error() );
        }

        if ( pg_num_rows($rsExists) > 0 ) {
          continue;
        }

        $sArquivoEscapado    = pg_escape_string("{$sTipo}_{$sVersao}.sql");
        $sConteudoEscapado   = pg_escape_string($sConteudo);
        $sSqlEscapada        = "delete from configuracoes.database_version_sql WHERE db143_versao = '{$sVersaoEscapada}' and db143_tipo = '{$sTipo}';\n";
        $rDelete             = @pg_query($connection, $sSqlEscapada);
        $sSql               .= $sSqlEscapada;

        if ( !$rDelete ) {
          throw new Exception("Erro ao Remover script {$sVersao}" . pg_last_error($connection) );
        }
        $sInsertSql          = "INSERT INTO configuracoes.database_version_sql (db143_arquivo, db143_versao, db143_script, db143_tipo) ";
        $sInsertSql         .= "     VALUES ('{$sArquivoEscapado}', '{$sVersaoEscapada}', '{$sConteudoEscapado}','{$sTipo}');\n";
        $sSql               .= $sInsertSql;
        $rInsertSql= @pg_query($connection, $sInsertSql);
        if ( !$rInsertSql ) {
          throw new Exception("Erro ao Incluir script {$sVersao}" . pg_last_error($connection) );
        }
      }
    }
    return $sSql;
  }

  /**
   * Valida os acertos que ainda não tenham sido exeuctados
   * @param $connection
   * @return string
   * @throws DBException
   * @throws Exception
   */
  public static function loadDML( $connection, $sDiretorioBase = '' ) {

    $aArquivos          = array();
    $sSqlExecutado      = '';
    $sDiretorioAcertos  = DBDataBaseMigration::DIRETORIO_SQL_ACERTOS;

    if ( $sDiretorioBase != '' ) {
      $sDiretorioAcertos = $sDiretorioBase;
    }

    if( !is_dir( $sDiretorioAcertos ) ) {
      throw new Exception( "ERRO: Diretório {$sDiretorioAcertos} inexistente.\n" );
    }

    DBDataBaseMigration::checkTablesVersioning($connection);

    $aArquivos = scandir( $sDiretorioAcertos );

    foreach( $aArquivos as $sArquivo ) {

      $sCaminhoCompleto = $sDiretorioAcertos . "/" . $sArquivo;
      $sAcerto          = str_replace( '.sql', '', $sArquivo );

      if(    !is_file( $sCaminhoCompleto )
          || filesize( $sCaminhoCompleto ) == 0 ) {
        continue;
      }

      $sSqlValidacaoDatabaseVersion  = "select 1 ";
      $sSqlValidacaoDatabaseVersion .= "  from database_version ";
      $sSqlValidacaoDatabaseVersion .= " where db142_versao = 'dml'";
      $rsValidacaoDatabaseVersion    = pg_query( $sSqlValidacaoDatabaseVersion );

      if( !$rsValidacaoDatabaseVersion ) {
        throw new DBException( "Erro ao executar '{$sSqlValidacaoDatabaseVersion}':\n" . pg_last_error( $rsValidacaoDatabaseVersion ) );
      }

      if( pg_num_rows( $rsValidacaoDatabaseVersion ) == 0 ) {

        $sSqlInsereDatabaseVersion = "INSERT INTO database_version VALUES( 'dml' )";
        $rsInsereDatabaseVersion   = pg_query( $sSqlInsereDatabaseVersion );

        if( !$rsInsereDatabaseVersion ) {
          throw new DBException( "Erro ao executar '{$sSqlInsereDatabaseVersion}':\n" . pg_last_error( $rsInsereDatabaseVersion ) );
        }

      }

      $sSqlValidacaoDatabaseVersionSql  = "select 1 ";
      $sSqlValidacaoDatabaseVersionSql .= "  from database_version_sql ";
      $sSqlValidacaoDatabaseVersionSql .= " where db143_arquivo = '{$sArquivo}'";
      $rsValidacaoDatabaseVersionSql    = pg_query( $sSqlValidacaoDatabaseVersionSql );

      if( !$rsValidacaoDatabaseVersionSql ) {
        throw new DBException( "Erro ao executar '{$sSqlValidacaoDatabaseVersionSql}':\n" . pg_last_error( $rsValidacaoDatabaseVersionSql ) );
      }

      if( pg_num_rows( $rsValidacaoDatabaseVersionSql ) == 0 ) {

        $sConteudo                     = pg_escape_string( file_get_contents( $sCaminhoCompleto ) );
        $sSqlInsereDatabaseVersionSql  = "INSERT INTO database_version_sql ";
        $sSqlInsereDatabaseVersionSql .= "     VALUES ( '{$sArquivo}', 'dml', '{$sConteudo}', 'dml' )";
        $rsInsereDatabaseVersionSql    = pg_query( $sSqlInsereDatabaseVersionSql );

        if( !$rsInsereDatabaseVersionSql ) {
          throw new DBException( "Erro ao executar '{$rsInsereDatabaseVersionSql}':\n" . pg_last_error( $rsInsereDatabaseVersionSql ) );
        }

      }
    }

    return $sSqlExecutado;
  }

  public static function upgradeDatabase( $connection, $sTipo, $sDiretorioBase = '' ) {


    $sMetodo  = $sTipo != "dml" ? "loadScripts" : "loadDML";
    $sSql     = DBDataBaseMigration::$sMetodo( $connection, $sDiretorioBase );

    if ( trim($sSql) != '') {
      $sSql = "/* ATUALIZANDO database_version */ \n" . $sSql;
    }

    $sScriptsNotApplied  = "select * ";
    $sScriptsNotApplied .= "  from configuracoes.database_version_sql ";
    $sScriptsNotApplied .= " where db143_executado is false ";
    $sScriptsNotApplied .= "   and db143_tipo = '$sTipo' ";
    $sScriptsNotApplied .= " order by db143_versao asc, db143_tipo desc";
    $rScriptsNotApplied  = @pg_query($connection, $sScriptsNotApplied);

    if (!$rScriptsNotApplied) {
      throw new DBException( "ERRO: Ao executar SQL {$sScriptsNotApplied} " . pg_last_error() );
    }

    $iCount  = pg_num_rows($rScriptsNotApplied);
    $sSql   .= $iCount > 0 ? "/* RODANDO $sTipo */ \n" : "";

    for ( $x = 0; $x < $iCount; $x++ ) {

      $oScript = db_utils::fieldsMemory($rScriptsNotApplied, $x);
      $sSql   .= $oScript->db143_script . "\n";

      $sUpdateApplied  = "  update configuracoes.database_version_sql           \n";
      $sUpdateApplied .= "     set db143_executado = true                       \n";
      $sUpdateApplied .= "   where db143_arquivo   = '{$oScript->db143_arquivo}'\n";
      $sUpdateApplied .= "     and db143_versao    = '{$oScript->db143_versao}' \n";
      $sUpdateApplied .= "     and db143_tipo      = '{$oScript->db143_tipo}';  \n";
      $sSql           .= $sUpdateApplied;

      $rUpdateApplied  = @pg_query($connection, $sUpdateApplied);

      if (!$rUpdateApplied) {
        throw new DBException( "ERRO: Ao executar SQL {$sUpdateApplied} " . pg_last_error() );
      }
    }

    return $sSql;
  }


  public static function getPhinxWrapper() {

    $app = new \Phinx\Console\PhinxApplication();

    $config = array(
      'environment' => 'ecidade',
      'configuration' => 'phinx.php',
      'parser' => 'php'
    );

    $wrap = new \Phinx\Wrapper\TextWrapper($app, $config);

    return $wrap;
  }


}
?>
