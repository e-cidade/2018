<?php

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


function checkTablesVersioning($connection) {

  $sExecute = "
      CREATE TABLE public.database_version (
          version integer NOT NULL,
          created timestamp without time zone DEFAULT now() NOT NULL
      );

      ALTER TABLE ONLY public.database_version
          ADD CONSTRAINT database_version_pk PRIMARY KEY (version); ";

  if (!createTable($connection, 'public', 'database_version', $sExecute)) {
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

  if (!createTable($connection, 'public', 'database_version_sql', $sExecute)) {
    return false;
  }

  return true;
}


function loadScripts($connection, $root) {

  $sDirectoryScripts = $root . '/db';

  if (!is_dir($sDirectoryScripts)) {
    db_log("ERRO: Diretório {$sDirectoryScripts} não existe!\n", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
    return false;
  }

  if (!checkTablesVersioning($connection)) {
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

      if( !isset($aPathInfo['extension']) || ( isset($aPathInfo['extension']) && $aPathInfo['extension'] != 'sql' ) ) {
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


function upgradeDatabase($connection, $root) {

  loadScripts($connection, $root);

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

    db_log("AVISO: Aplicando script {$oScript->sql_name} da vers�o {$oScript->version} na ordem de execu��o {$oScript->ord}", $GLOBALS['sArquivoLog'], $GLOBALS['iParamLog']);
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
