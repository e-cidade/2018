<?php

$HTTP_SERVER_VARS['HTTP_HOST']      = '';
$HTTP_SERVER_VARS['PHP_SELF']       = '';
$HTTP_SERVER_VARS["HTTP_REFERER"]   = '';
$HTTP_POST_VARS                     = array();
$HTTP_GET_VARS                      = array();

define("PATH_IMPORTACAO", "integracao_externa/recadastramento_imobiliario/");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioLogradouros.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisBic.php");
require_once(PATH_IMPORTACAO . "libs/Conexao.model.php");
require_once(PATH_IMPORTACAO . "libs/BarraProgressoCli.php");

require_once("model/dataManager.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("std/DBDate.php");
require_once("libs/JSON.php");

db_app::import("configuracao.DBLog");
db_app::import("configuracao.DBLogTXT");

define("DB_BIBLIOT", PATH_IMPORTACAO);
define("FPDF_FONTPATH", "font/");



try {
  
 pg_query(Conexao::getInstancia()->getConexao(), "BEGIN");
 pg_query(Conexao::getInstancia()->getConexao(), "SELECT FC_STARTSESSION()");
 
 $oRecadastroImobiliarioLogradouros = new RecadastroImobiliarioLogradouros(PATH_IMPORTACAO . $argv[1]);
 $oRecadastroImobiliarioLogradouros->carregarArquivo();
 $oRecadastroImobiliarioLogradouros->processarImportacao();

 $sSql            = "SELECT j01_matric from iptubase;";
 $rsSqlMatriculas = pg_query( Conexao::getInstancia()->getConexao(), $sSql );
 $aTotalRegistros = db_utils::getCollectionByRecord($rsSqlMatriculas);
 $iTotalRegistros = count($aTotalRegistros);
 $oBarraProgresso = new BarraProgressoCli($iTotalRegistros);
 echo "\n";
 echo "Processamento BIC's: \n";

 foreach (db_utils::getCollectionByRecord($rsSqlMatriculas) as $oResultado) {

   $oBarraProgresso->atualizar();
    
   //echo "Processando BIC da Matricula: $oResultado->j01_matric";
   $oProcessamentoBIC = new RecadastroImobiliarioImoveisBic($oResultado->j01_matric);
   $oProcessamentoBIC->processar();
}
 
 pg_query(Conexao::getInstancia()->getConexao(), "COMMIT;");
 echo "\n";
} catch( Exception $eErro ) {

  pg_query(Conexao::getInstancia()->getConexao(), "ROLLBACK");
  echo "Erro ao Processar".$eErro->getMessage();
}
