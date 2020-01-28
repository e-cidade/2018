<?
// final de execução
db_log("Inicio: ".HORA_INICIO_EXECUCAO, $sArquivoLog);
db_log("Final.: " . date( "H:i:s"), $sArquivoLog);

db_log("", $sArquivoLog);
db_log("*** FINAL Script ".$sNomeScript." ***", $sArquivoLog);
db_log("", $sArquivoLog);

db_log("\n\n");

?>
