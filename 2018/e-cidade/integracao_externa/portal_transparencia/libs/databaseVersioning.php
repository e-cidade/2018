<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


function createTable($connection, $schema, $table, $ddl) {

  $sExistsTable = "SELECT EXISTS(SELECT 1 FROM information_schema.tables WHERE table_schema = '{$schema}' AND table_name = '{$table}')";
  $rExistsTable = db_query($connection, $sExistsTable);

  if (!$rExistsTable) {
    db_log("ERRO: Ao executar SQL $sExistsTable " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    return false;
  }

  if (pg_result($rExistsTable, 0, 0) == 'f') {
    $rExecute = db_query($connection, $ddl);
    if (!$rExecute) {
      db_log("ERRO: Ao executar SQL $ddl " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
      return false;
    }
  }

  return true;
}


function checkTablesVersioning($connection,$sSchema='public') {

  $sExecute = "
      CREATE TABLE {$sSchema}.database_version (
          version integer NOT NULL,
          created timestamp without time zone DEFAULT now() NOT NULL
      );

      ALTER TABLE ONLY {$sSchema}.database_version
          ADD CONSTRAINT database_version_pk PRIMARY KEY (version); ";

  if (!createTable($connection, $sSchema, 'database_version', $sExecute)) {
    return false;
  }

  $sExecute = "
      CREATE TABLE database_version_sql (
          sql_name character varying(100) NOT NULL,
          version integer NOT NULL,
          ord integer DEFAULT 1 NOT NULL,
          script text NOT NULL,
          applied boolean DEFAULT false NOT NULL
      );

      ALTER TABLE ONLY database_version_sql
          ADD CONSTRAINT database_version_sql_pk PRIMARY KEY (sql_name, version);

      CREATE INDEX database_version_sql_version_idx ON database_version_sql USING btree (version);

      ALTER TABLE ONLY database_version_sql
          ADD CONSTRAINT database_version_sql_version_fk FOREIGN KEY (version) REFERENCES database_version(version);";

  if (!createTable($connection, $sSchema, 'database_version_sql', $sExecute)) {
    return false;
  }

  return true;
}


function loadScripts($connection, $root, $sSchema='') {

  $sDirectoryScripts = $root . '/db';

  if (!is_dir($sDirectoryScripts)) {
    db_log("ERRO: Diret�rio {$sDirectoryScripts} não existe!\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    return false;
  }

  if (!checkTablesVersioning($connection,$sSchema)) {
    return false;
  }

  $sLastVersion = "SELECT version FROM database_version ORDER BY version DESC LIMIT 1";
  $rLastVersion = db_query($connection, $sLastVersion);

  if (!$rLastVersion) {
    db_log("ERRO: Ao executar SQL $sLastVersion " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    return false;
  }

  $iLastVersion = 0;
  if (pg_num_rows($rLastVersion) > 0) {
    $iLastVersion = pg_result($rLastVersion, 0, 0);
  }

  $sDirectoryCheck = $sDirectoryScripts . '/'.$iLastVersion;
  
  while( is_dir($sDirectoryCheck) ) {
    db_log("AVISO: Verificando Scripts em {$sDirectoryCheck}", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);

    $aFiles = scandir($sDirectoryCheck);

    $sSqlExists = "SELECT * FROM database_version WHERE version = {$iLastVersion}";
    $rExists = db_query($connection, $sSqlExists);

    if (!$rLastVersion) {
      db_log("ERRO: Ao executar SQL $sLastVersion " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
      return false;
    }

    $iOrd = 0;
    if(pg_num_rows($rExists) == 0) {
      db_log("AVISO: Inserindo versão {$iLastVersion} do schema da base de dados", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
      $sInsert = "INSERT INTO database_version(version) VALUES ({$iLastVersion})";

      $rInsert = db_query($connection, $sInsert);
    } else {
      $sLastOrder = "SELECT coalesce(max(ord),0) FROM database_version_sql WHERE version = {$iLastVersion}";
      $rLastOrder = db_query($connection, $sLastOrder);
    
      $iOrd = pg_result($rLastOrder, 0, 0);
    }

    foreach($aFiles as $sFile) {
      if($sFile == '.' or $sFile == '..') {
        continue;
      }
      $aPathInfo = pathinfo($sDirectoryCheck.'/'.$sFile);

      if($aPathInfo['extension'] != 'sql') {
        continue;
      }

      $sSqlExists = "SELECT * FROM database_version_sql WHERE sql_name = '{$sFile}' AND version = {$iLastVersion}";
      $rExists = db_query($connection, $sSqlExists);

      if(pg_num_rows($rExists) == 0) {
        $iOrd++;
        db_log("AVISO: Carregando script {$sFile} da versão {$iLastVersion} na ordem de execução {$iOrd}", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
        $sScriptContent = file_get_contents($sDirectoryCheck.'/'.$sFile);
        $sScriptContent = pg_escape_string($sScriptContent);

        $sInsert  = "INSERT INTO database_version_sql (sql_name, version, ord, script) ";
        $sInsert .= "VALUES ('{$sFile}', {$iLastVersion}, {$iOrd}, '{$sScriptContent}')";

        $rInsert = db_query($connection, $sInsert);
      }
    }

    $iLastVersion++;
    $sDirectoryCheck = $sDirectoryScripts . '/'.$iLastVersion;
  }
}


function upgradeDatabase($connection, $root, $sSchema='') {

  loadScripts($connection, $root,$sSchema);

  db_log("AVISO: Verificando Scripts a serem aplicados na base de dados ... ", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);

  $sScriptsNotApplied = "SELECT * FROM database_version_sql WHERE applied IS FALSE ORDER BY version, ord";
  $rScriptsNotApplied = db_query($connection, $sScriptsNotApplied);
  
  if (!$rScriptsNotApplied) {
    db_log("ERRO: Ao executar SQL {$sScriptsNotApplied} " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    return false;
  }

  $iCount   = pg_num_rows($rScriptsNotApplied);
  $lApplied = false;

  db_log("AVISO: Existe(m) {$iCount} Script(s) para ser(em) aplicado(s) na base de dados ... ", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
  for($x = 0; $x < $iCount; $x++) {
    $oScript = db_utils::fieldsMemory($rScriptsNotApplied, $x);

    db_log("AVISO: Aplicando script {$oScript->sql_name} da versão {$oScript->version} na ordem de execução {$oScript->ord}", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    $rExecute = db_query($connection, $oScript->script); 
    
    if (!$rExecute) {
      db_log("ERRO: Ao executar SQL {$oScript->script} " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
      return false;
    }

    $sUpdateApplied  = "UPDATE database_version_sql ";
    $sUpdateApplied .= "   SET applied = TRUE ";
    $sUpdateApplied .= " WHERE sql_name = '{$oScript->sql_name}' ";
    $sUpdateApplied .= "   AND version  = {$oScript->version} ";
    $sUpdateApplied .= "   AND ord      = {$oScript->ord} ";

    $rUpdateApplied = db_query($connection, $sUpdateApplied);

    if (!$rUpdateApplied) {
      db_log("ERRO: Ao executar SQL {$sUpdateApplied} " . pg_last_error() . "\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
      return false;
    }

    $lApplied = true;

  }

  if ($lApplied) {
    db_log("AVISO: Foi(ram) aplicado(s) {$iCount} script(s) na base de dados ...", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
  } else {
    db_log("AVISO: Nenhum script foi aplicado na base de dados ...", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
  }

  return true;
}

?>