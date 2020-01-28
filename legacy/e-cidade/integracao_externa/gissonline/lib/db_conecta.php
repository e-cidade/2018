<?
//
// Configuracoes para programas de Conversão
//

$ConfigINI = parse_ini_file("db_config.ini");

db_log("", $sArqLog);

// DBPORTAL Prefeitura
$ConfigConexaoPrefeitura ["host"]     = $ConfigINI ["ConPrefeitura_host"];
$ConfigConexaoPrefeitura ["port"]     = $ConfigINI ["ConPrefeitura_port"];
$ConfigConexaoPrefeitura ["dbname"]   = $ConfigINI ["ConPrefeitura_dbname"];
$ConfigConexaoPrefeitura ["user"]     = $ConfigINI ["ConPrefeitura_user"];
$ConfigConexaoPrefeitura ["password"] = $ConfigINI ["ConPrefeitura_password"];

// DBPORTAL Giss
$ConfigConexaoGiss ["host"]     = $ConfigINI ["ConGiss_host"];
$ConfigConexaoGiss ["port"]     = $ConfigINI ["ConGiss_port"];
$ConfigConexaoGiss ["dbname"]   = $ConfigINI ["ConGiss_dbname"];
$ConfigConexaoGiss ["user"]     = $ConfigINI ["ConGiss_user"];
$ConfigConexaoGiss ["password"] = $ConfigINI ["ConGiss_password"];

//
// Conexao com a base de dados do gissonline
//
$sDataSourceGiss = "host={$ConfigConexaoGiss["host"]} 
                    dbname={$ConfigConexaoGiss["dbname"]} 
                    port={$ConfigConexaoGiss["port"]} 
                    user={$ConfigConexaoGiss["user"]} 
                    password={$ConfigConexaoGiss["password"]}";

db_log("- BASE PARA IMPORTACAO       Giss: $sDataSourceGiss", $sArquivoLog);

if (! ($conn2 = pg_connect($sDataSourceGiss))) {
  db_log("Erro ao conectar no Giss... ($sDataSourceGiss)", $sArqLog);
  die();
}

//
// Conexao com a base de dados da prefeitura
//
$sDataSourcePrefeitura = "host={$ConfigConexaoPrefeitura["host"]} 
                          dbname={$ConfigConexaoPrefeitura["dbname"]} 
                          port={$ConfigConexaoPrefeitura["port"]} 
                          user={$ConfigConexaoPrefeitura["user"]} 
                          password={$ConfigConexaoPrefeitura["password"]}";

db_log("- BASE PARA IMPORTACAO Prefeitura: $sDataSourcePrefeitura", $sArquivoLog);

if (! ($conn = pg_connect($sDataSourcePrefeitura))) {
  db_log("Erro ao conectar no DBPortal ($sDataSourcePrefeitura)...", $sArquivoLog);
  die();
}

db_log("", $sArqLog);

?>
