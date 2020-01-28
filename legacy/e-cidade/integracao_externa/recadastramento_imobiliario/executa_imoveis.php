<?php
// Declarando variáveis necessárias para que a inclusão das bibliotecas não retorne mensagens.
$HTTP_SERVER_VARS['HTTP_HOST']      = '';
$HTTP_SERVER_VARS['PHP_SELF']       = '';
$HTTP_SERVER_VARS["HTTP_REFERER"]   = '';
$HTTP_POST_VARS                     = array();
$HTTP_GET_VARS                      = array();

define("PATH_IMPORTACAO", "integracao_externa/recadastramento_imobiliario/");

require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisArquivo.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisStrategy.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisExclusao.php");
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveisInclusao.php"); 
require_once(PATH_IMPORTACAO . "libs/Conexao.model.php");
require_once(PATH_IMPORTACAO . "libs/caracteristicas_imovel.php");
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

  Conexao::getInstancia()->query("BEGIN");
  Conexao::getInstancia()->query("SELECT fc_startsession();");
  Conexao::getInstancia()->query("CREATE TEMP TABLE w_testadanumero as select * from testadanumero limit 1;");
  Conexao::getInstancia()->query("CREATE TEMP TABLE w_testada       as select * from testada       limit 1;");
  
  if (!file_exists(PATH_IMPORTACAO . $argv[1])) {
    throw new Exception('Arquivo n�o encontrado.');
  }

  $oRecadastroImobiliarioImoveisArquivo = new RecadastroImobiliarioImoveisArquivo(PATH_IMPORTACAO . $argv[1]);

  if ( $oRecadastroImobiliarioImoveisArquivo->processar() ) {
    Conexao::getInstancia()->query("COMMIT");
  }
} catch( Exception $eErro ) {

  print_r($eErro) . "\n" ;

  Conexao::getInstancia()->query("ROLLBACK");

}

