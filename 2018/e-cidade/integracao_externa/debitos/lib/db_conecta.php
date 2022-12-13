<?php

include("db_inicio_script.php");

$sArqLog      = "log/01_TesteLogConexao.txt";
$sArqLogWork  = 'log/03_geraBaseAux_logVerificaWork.txt';
$sArqLogInfos = 'log/03_geraBaseAux_logInfo.txt';
$sArqLogErros = 'log/03_geraBaseAux_logErros.txt';

//
// Configuracoes para programas de Conversão
//

include(PATH_ECIDADE."/libs/db_conn.php");

$ConfigConexaoOrigem["host"]     = $DB_SERVIDOR; 
$ConfigConexaoOrigem["port"]     = $DB_PORTA;
$ConfigConexaoOrigem["dbname"]   = $DB_BASE;
$ConfigConexaoOrigem["user"]     = $DB_USUARIO;
$ConfigConexaoOrigem["password"] = $DB_SENHA;

$ConfigConexaoDestino["host"]     = $DB_SERVIDOR; 
$ConfigConexaoDestino["port"]     = $DB_PORTA;
$ConfigConexaoDestino["dbname"]   = $DB_BASE;
$ConfigConexaoDestino["user"]     = $DB_USUARIO;
$ConfigConexaoDestino["password"] = $DB_SENHA;

db_log("", $sArqLog);

$iAnoAtual   = date('Y');

$dDataConversao = date('Y-m-d');

db_log("Criando conexao(oes) com banco de dados", $sArquivoLog);
include("db_cria_conexoes.php");

/**
 * Inicia a sessao
 *
 */
$rsStartSession = db_query($pConexaoDestino, "select fc_startsession()", $sArquivoLog);

db_log("", $sArquivoLog);

?>
