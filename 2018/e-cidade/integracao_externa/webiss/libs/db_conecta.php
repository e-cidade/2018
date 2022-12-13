<?

/**
 *  Configuraчуo do Programa de Conversуo
 */

 $ConfigINI = parse_ini_file("db_config.ini");

/**
 *  Configuraчуo base Prefeitura
 */
 $ConfigConexaoPrefeitura["host"]     = $ConfigINI["ConPrefeitura_host"];
 $ConfigConexaoPrefeitura["port"]     = $ConfigINI["ConPrefeitura_port"];
 $ConfigConexaoPrefeitura["dbname"]   = $ConfigINI["ConPrefeitura_dbname"];
 $ConfigConexaoPrefeitura["user"]     = $ConfigINI["ConPrefeitura_user"];
 $ConfigConexaoPrefeitura["password"] = $ConfigINI["ConPrefeitura_password"];

/**
 *  Configuraчуo base Destino
 */ 
 $ConfigConexaoDestino["host"]     = $ConfigINI["ConDestino_host"];
 $ConfigConexaoDestino["port"]     = $ConfigINI["ConDestino_port"];
 $ConfigConexaoDestino["dbname"]   = $ConfigINI["ConDestino_dbname"];
 $ConfigConexaoDestino["user"]     = $ConfigINI["ConDestino_user"];
 $ConfigConexaoDestino["password"] = $ConfigINI["ConDestino_password"];


/**
 *  Conexуo com base de destino
 */
 $sDataSourceDestino = "host={$ConfigConexaoDestino["host"]} 
 	                      dbname={$ConfigConexaoDestino["dbname"]} 
	                      port={$ConfigConexaoDestino["port"]} 
	                      user={$ConfigConexaoDestino["user"]} 
	                      password={$ConfigConexaoDestino["password"]}";

 $sDataSourceDestinoLog  = " host={$ConfigConexaoDestino["host"]} ";
 $sDataSourceDestinoLog .= " dbname={$ConfigConexaoDestino["dbname"]} ";
 $sDataSourceDestinoLog .= " port={$ConfigConexaoDestino["port"]} ";
                                             
 db_log("- BASE PARA IMPORTACAO  Destino: $sDataSourceDestinoLog", $sArquivoLog,$iParamLog);

 if (! ($connDestino = pg_connect($sDataSourceDestino))) {
 	
   db_log("Erro ao conectar no Destino... ($sDataSourceDestinoLog)", $sArquivoLog,$iParamLog);
   die();
 }

/**
 *  Conexуo com base de dados da Prefeitura
 */
 $sDataSourcePrefeitura = "host={$ConfigConexaoPrefeitura["host"]} 
                           dbname={$ConfigConexaoPrefeitura["dbname"]} 
                           port={$ConfigConexaoPrefeitura["port"]} 
                           user={$ConfigConexaoPrefeitura["user"]} 
                           password={$ConfigConexaoPrefeitura["password"]}";

 $sDataSourcePrefeituraLog  = " host={$ConfigConexaoPrefeitura["host"]} ";
 $sDataSourcePrefeituraLog .= " dbname={$ConfigConexaoPrefeitura["dbname"]} ";
 $sDataSourcePrefeituraLog .= " port={$ConfigConexaoPrefeitura["port"]} ";
                           
 db_log("- BASE PARA IMPORTACAO Prefeitura: $sDataSourcePrefeituraLog", $sArquivoLog,$iParamLog);
 
 if (! ($connOrigem = @pg_connect($sDataSourcePrefeitura))) {
 	
   db_log("Erro ao conectar no DBPortal ($sDataSourcePrefeituraLog)...", $sArquivoLog,$iParamLog);
   die();
 }

 db_log("", $sArquivoLog,$iParamLog);
?>