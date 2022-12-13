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

/* Seta Nome do Script para ser utilizado nos logs */
$sNomeScript = basename(__FILE__);

/* Conexao com base - seta $pConexaoDestino */
include("lib/db_conecta.php");

/* Seta o 'application_name' da conexao com o PostgreSQL */
db_query($pConexaoDestino, "SET application_name TO 'build_debitos_thread.php(main)'", $sArquivoLog);

/* Lib de construcao da debitos */
include("lib/build_debitos.php");
include(PATH_ECIDADE."/std/Thread.php");

/* Variavel para ser utilizada no controle de Erros */
$bErro = false;

error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set('error_log','log/php_error.log');

/* Parametros */
$sData         = isset($argv[1])?$argv[1]:date('Y-m-d');
$iInstit       = isset($argv[2])?$argv[2]:1;

$sSufixo       = str_replace("-", "", $sData)."_{$iInstit}";
$sDebitosName  = "caixa.debitos_{$sSufixo}";
$sDebitosGera  = "caixa.debitos_{$sSufixo}_processa";

$sWhere        = isset($argv[3])?$argv[3]:"1=1";
$iMaxThreads   = isset($argv[4])?$argv[4]:2;
$lForceRebuild = isset($argv[6])?$argv[6]:false;


// Procedimento para verificar geracao cancelada para essa DATA e INSTITUICAO
db_log("Verificando existencia da tabela {$sDebitosGera} ...", $sArquivoLog);
$sSqlVerifica  = "SELECT 1 ";
$sSqlVerifica .= "  FROM pg_class r ";
$sSqlVerifica .= "       JOIN pg_namespace n ON n.oid = r.relnamespace ";
$sSqlVerifica .= " WHERE r.relname = fc_parse_relation('{$sDebitosGera}') ";
$sSqlVerifica .= "   AND n.nspname = fc_parse_schema('{$sDebitosGera}') ";

$rsVerifica = db_query($pConexaoDestino, $sSqlVerifica, $sArquivoLog);

if( db_numrows($rsVerifica, $sArquivoLog) > 0 ) {

	if ($lForceRebuild) {
		db_log("Tabela {$sDebitosGera} já existe ... removendo ...", $sArquivoLog);

		$sSqlDrop = "SELECT fc_executa_ddl('DROP TABLE IF EXISTS w_debitos_{$sSufixo}_'||sequencial||';') FROM {$sDebitosGera};";
		db_query($pConexaoDestino, $sSqlDrop, $sArquivoLog);

		db_query($pConexaoDestino, "DROP TABLE IF EXISTS {$sDebitosGera}", $sArquivoLog);
		db_query($pConexaoDestino, "DROP TABLE IF EXISTS {$sDebitosName}", $sArquivoLog);
		db_query($pConexaoDestino, "DELETE FROM datadebitos WHERE k115_data = '{$sData}' AND k115_instit = {$iInstit}", $sArquivoLog);
	} else {
		db_log("Abortando geracao pois existe outra instancia gerando a debitos para data $sData e instituicao $iInstit ...", $sArquivoLog);
		/* Final do Script */
		include("lib/db_final_script.php");
		exit(1);
	}

}

db_log("Verificando se ja existe geracao da debitos para data $sData e instituicao $iInstit ...", $sArquivoLog);
$sSqlVerifica  = "SELECT 1 ";
$sSqlVerifica .= "  FROM datadebitos ";
$sSqlVerifica .= " WHERE k115_data   = '{$sData}' ";
$sSqlVerifica .= "   AND k115_instit = {$iInstit} ";

$rsVerifica = db_query($pConexaoDestino, $sSqlVerifica, $sArquivoLog);

$lAbortar = false;

if( db_numrows($rsVerifica, $sArquivoLog) > 0 ) {

	if (!$lForceRebuild) {
		db_log("Abortando geracao pois ja existe para data $sData e instituicao $iInstit ...", $sArquivoLog);
		$lAbortar = true;
	} else {
		db_log("Geracao da nova debitos forcada... limpando geracao ja existe para data $sData e instituicao $iInstit ...", $sArquivoLog);
		$sSqlDrop  = "
			DELETE FROM datadebitos WHERE k115_data = '{$sData}' AND k115_instit = {$iInstit};
			DROP TABLE IF EXISTS {$sDebitosName};
			DROP TABLE IF EXISTS {$sDebitosGera};
			";
		db_query($pConexaoDestino, $sSqlDrop, $sArquivoLog);
	}

} else {

	/* Se existir particao SEM datadebitos ENTAO GERACAO PARCIAL - ABORTAR GERACAO */
	$sSqlVerifica  = "SELECT 1 ";
	$sSqlVerifica .= "  FROM pg_class r ";
	$sSqlVerifica .= "       JOIN pg_namespace n ON n.oid = r.relnamespace ";
	$sSqlVerifica .= " WHERE r.relkind = 'r' ";
	$sSqlVerifica .= "   AND r.relname = fc_parse_relation('{$sDebitosName}') ";
	$sSqlVerifica .= "   AND n.nspname = fc_parse_schema('{$sDebitosName}') ";

	$rsVerifica = db_query($pConexaoDestino, $sSqlVerifica, $sArquivoLog);

	if( db_numrows($rsVerifica, $sArquivoLog) > 0 ) {
		db_log("Abortando geracao pois existe DEBITOS PARCIAL (com Listas) para data $sData e instituicao $iInstit ...", $sArquivoLog);
		$lAbortar = true;
	} else {
		db_log("Nao existe geracao da debitos para data $sData e instituicao $iInstit ...", $sArquivoLog);
	}
}

if ($lAbortar) {
	echo "\n";

	/* Final do Script */
	include("lib/db_final_script.php");
	exit(1);
}

build_debitos_processa($pConexaoDestino, $sArquivoLog, $sDebitosGera, $iInstit, 1000);

db_log("Verificando existencia da tabela {$sDebitosName} ...", $sArquivoLog);
$sSqlVerifica  = "SELECT 1 ";
$sSqlVerifica .= "  FROM pg_class r ";
$sSqlVerifica .= "       JOIN pg_namespace n ON n.oid = r.relnamespace ";
$sSqlVerifica .= " WHERE r.relkind = 'r' ";
$sSqlVerifica .= "   AND r.relname = fc_parse_relation('{$sDebitosName}') ";
$sSqlVerifica .= "   AND n.nspname = fc_parse_schema('{$sDebitosName}') ";

$rsVerifica = db_query($pConexaoDestino, $sSqlVerifica, $sArquivoLog);

$lCriouParticao = false;
if( db_numrows($rsVerifica, $sArquivoLog) == 0 ) {
	db_log("Criando particao {$sDebitosName} da tabela debitos ...", $sArquivoLog);
	db_query($pConexaoDestino, 
		"CREATE TABLE IF NOT EXISTS {$sDebitosName} (
			LIKE caixa.debitos INCLUDING DEFAULTS
		) WITH (
			autovacuum_enabled = false,
			toast.autovacuum_enabled = false
		);", $sArquivoLog);

	db_log("Criando indices da tabela debitos na particao {$sDebitosName} ...", $sArquivoLog);
	db_query($pConexaoDestino, "SELECT fc_clone_table_indexes('caixa.debitos', '{$sDebitosName}', null);", $sArquivoLog);

	db_log("Criando constraints da tabela debitos na particao {$sDebitosName} ...", $sArquivoLog);
	db_query($pConexaoDestino, "SELECT fc_clone_table_constraints('caixa.debitos', '{$sDebitosName}');", $sArquivoLog);

	$lCriouParticao = true;
} else {
	db_log("Tabela {$sDebitosName} já existe ...", $sArquivoLog);
}

db_log("Carregando lotes a serem processados (data=\"{$sData}\" instit=\"{$iInstit}\" where=\"{$sWhere}\" threads=\"{$iMaxThreads}\")", $sArquivoLog);
$sSql  = "SELECT * ";
$sSql .= "  FROM {$sDebitosGera} ";
$sSql .= " WHERE status = 'NAO INICIADO' ";
$sSql .= "   AND {$sWhere} ";
$sSql .= " ORDER BY sequencial ";

$rsProcessamento = db_query($pConexaoDestino, $sSql, $sArquivoLog);
$iNumrows        = db_numrows($rsProcessamento, $sArquivoLog);

if ($iNumrows==0) {
	db_log("Nenhum lote encontrado ...", $sArquivoLog, 1, true, true);
	if ($lCriouParticao) {
		db_query($pConexaoDestino, "DROP TABLE IF EXISTS {$sDebitosName}", $sArquivoLog);
	}
}

echo "\n";

$aThreads = array();

$oThreadFinish = new Thread('finish_build_debitos');

for ($i=0; $i<$iNumrows; $i++) {
	$oDebito = db_utils::fieldsMemory($rsProcessamento, $i);

	$iPercPrincipal = round((($i+1)/$iNumrows)*100, 2);

	db_log("> processando debitos {$iPercPrincipal}% (memoria atual=".db_uso_memoria()." pico=".db_uso_memoria(1).")      \r", $sArquivoLog, 1, true, false);

	$iCountThreads = count($aThreads);

	if ($iCountThreads < $iMaxThreads) {
		$oThread = new Thread('build_debitos');
		$oThread->start($oDebito, $ConfigConexaoDestino, $sArquivoLog, $sNomeScript, $sDebitosGera, $sData, $iInstit, $sSufixo);

		for ($w=0; $w < $iMaxThreads; $w++) {
			if (!isset($aThreads[$w])) {
				$aThreads[$w][0] = $oThread;
				$aThreads[$w][1] = $oDebito->sequencial;
				break;
			}
		}
	}

	if (!$oThreadFinish->isAlive()) {
		unset($oThreadFinish);
		$oThreadFinish = new Thread('finish_build_debitos');
		$oThreadFinish->start($ConfigConexaoDestino, $sArquivoLog, $sSufixo, $sDebitosGera, $sDebitosName, $sData, $iInstit, round($iMaxThreads/2));
	}
	waitForThreads($iMaxThreads, $aThreads, $sArquivoLog);
}

waitForFinishThreads($iMaxThreads, $aThreads, $sArquivoLog);

while($oThreadFinish->isAlive()) {
	// waiting...
}

finish_build_debitos($ConfigConexaoDestino, $sArquivoLog, $sSufixo, $sDebitosGera, $sDebitosName, $sData, $iInstit, null);

analyze_table($ConfigConexaoDestino, $sDebitosName, $sArquivoLog);

db_log("");

/* Final do Script */
include("lib/db_final_script.php");
?>
