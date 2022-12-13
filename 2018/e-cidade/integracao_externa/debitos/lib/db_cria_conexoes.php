<?php

$sDataSourceDestino = "host={$ConfigConexaoDestino["host"]} 
                       dbname={$ConfigConexaoDestino["dbname"]} 
                       port={$ConfigConexaoDestino["port"]} 
                       user={$ConfigConexaoDestino["user"]} 
                       password={$ConfigConexaoDestino["password"]}";

if(!($pConexaoDestino = pg_connect($sDataSourceDestino))) {
  db_log("Erro ao conectar na  ($sDataSourceDestino)...", $sArquivoLog);
  die();
}

?>
