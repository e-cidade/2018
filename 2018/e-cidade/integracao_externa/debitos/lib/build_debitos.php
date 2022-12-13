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


function build_debitos($oDebito, $aConfigConexao, $sArquivoLog, $sNomeScript, $sDebitosGera, $sData, $iInstit, $sSufixo) {

	$sDataSource = "host={$aConfigConexao["host"]} 
					dbname={$aConfigConexao["dbname"]} 
					port={$aConfigConexao["port"]} 
					user={$aConfigConexao["user"]} 
					password={$aConfigConexao["password"]}";

	if(!($pConexao = pg_connect($sDataSource))) {
		db_log("Erro ao conectar na  ($sDataSource)...", $sArquivoLog);
		return;
	}

	$sSql = "SELECT fc_startsession();SET synchronous_commit TO off;SET application_name TO 'build_debitos_thread.php(build_debitos)';";
	db_query($pConexao, $sSql, $sArquivoLog);

	$bErro = false;

	$sSql   = "SELECT pg_try_advisory_lock({$oDebito->sequencial}) AS bloqueou ";
	$rsLock = db_query($pConexao, $sSql, $sArquivoLog);
	$oLock  = db_utils::fieldsMemory($rsLock, 0);

	if ($oLock->bloqueou == 'f') {
		unset($rsLock);
		unset($oLock);
		return;
	}
	unset($rsLock);
	unset($oLock);

	$sSql  = "UPDATE {$sDebitosGera} ";
	$sSql .= "   SET inicio = clock_timestamp(), ";
	$sSql .= "       status = 'INICIADO PID='||cast(pg_backend_pid() as text) ";
	$sSql .= " WHERE sequencial = {$oDebito->sequencial}";
	db_query($pConexao, $sSql, $sArquivoLog);

	/* Abre transacao na base de destino pois a geracão DEVE ser transacional */
	db_query($pConexao,"begin;", $sArquivoLog);

	$sTempName = "w_debitos_{$sSufixo}_{$oDebito->sequencial}";

	db_query($pConexao, "
		DROP TABLE IF EXISTS {$sTempName};
		CREATE UNLOGGED TABLE {$sTempName} (
		  LIKE caixa.debitos
		) WITH (
		  autovacuum_enabled = false,
		  toast.autovacuum_enabled = false
		);", $sArquivoLog);

	$sSqlArrecad  = "SELECT fc_calcula(k00_numpre, k00_numpar, k00_receit, '{$sData}', '{$sData}', 10000, '{$sTempName}') ";
	$sSqlArrecad .= "  FROM (SELECT DISTINCT ";
	$sSqlArrecad .= "               k00_numpre, ";
	$sSqlArrecad .= "               k00_numpar, ";
	$sSqlArrecad .= "               k00_receit ";
	$sSqlArrecad .= "          FROM arrecad ";
	$sSqlArrecad .= "               NATURAL JOIN caixa.arreinstit ";
	$sSqlArrecad .= "         WHERE k00_instit = {$iInstit} "; 
	$sSqlArrecad .= "           AND k00_numpre BETWEEN {$oDebito->numpre_ini} AND {$oDebito->numpre_fim}) AS numpres";

	$rsArrecad   = db_query($pConexao, $sSqlArrecad, $sArquivoLog);

	/* Finaliza Transacao na base Destino */
	if ($bErro) {
		$sOperFim = "ROLLBACK";
	} else {
		$sOperFim = "COMMIT";
	}

	if (db_query($pConexao, "{$sOperFim};", $sArquivoLog)) {
		db_query($pConexao, "BEGIN;", $sArquivoLog);

		/* Remove trava (lock) lógico */
		$sSql = "SELECT pg_advisory_unlock({$oDebito->sequencial})";
		db_query($pConexao, $sSql, $sArquivoLog);

		$sSql  = "UPDATE {$sDebitosGera} ";
		$sSql .= "   SET status = 'FINALIZADO', ";
		$sSql .= "       registros_processados = coalesce((select count(*) from {$sTempName}), 0), ";
		$sSql .= "       fim = clock_timestamp(), ";
		$sSql .= "       observacoes = 'LOTE PROCESSADO PELO SCRIPT {$sNomeScript}' ";
		$sSql .= " WHERE sequencial = {$oDebito->sequencial}";
		db_query($pConexao, $sSql, $sArquivoLog);               

		db_query($pConexao, "COMMIT;", $sArquivoLog);
	}

	pg_close($pConexao);

	return;
}


function build_debitos_processa($pConexao, $sArquivoLog, $sDebitosGera, $iInstit, $iBlocosGera) {

	db_log("Criando tabela {$sDebitosGera} ...", $sArquivoLog);
	$sSqlDebitosGera = "
		SELECT fc_putsession('blocos_gera_debitos', '{$iBlocosGera}');

		SET random_page_cost TO 2;
		SET seq_page_cost    TO 1;

		SELECT fc_putsession('min_numpre', min(k00_numpre)::text),
		       fc_putsession('max_numpre', max(k00_numpre)::text),
		       fc_putsession('count_numpre', (max(k00_numpre)-min(k00_numpre)+1)::text)
		  FROM arreinstit
		       NATURAL JOIN arrecad 
		 WHERE k00_instit = {$iInstit};

		BEGIN;

		CREATE TABLE {$sDebitosGera} (
			sequencial            INTEGER  , 
			numpre_ini            INTEGER  , 
			numpre_fim            INTEGER  , 
			registros_processados INTEGER  , 
			status                TEXT     , 
			inicio                TIMESTAMP, 
			fim                   TIMESTAMP, 
			observacoes           TEXT     
		);";

	db_query($pConexao, $sSqlDebitosGera, $sArquivoLog);

	$oDebitosGera = new tableDataManager($pConexao, "{$sDebitosGera}", null, true, 500);

	db_log("Carregando NUMPRES para popular a tabela {$sDebitosGera} ...", $sArquivoLog);
	$sSqlDebitosGera = "
		SELECT minimo + (soma * id) - soma + (case when id=1 then 0 else 1 end)                      AS numpre_ini,
		       case when (minimo + (soma * id)) > maximo then maximo else (minimo + (soma * id)) end AS numpre_fim
		FROM (
		  SELECT (SELECT fc_getsession('min_numpre')::integer) AS minimo,
		         (SELECT fc_getsession('max_numpre')::integer) AS maximo,
		         id,
		         fc_getsession('blocos_gera_debitos')::float8 AS soma
		    FROM generate_series(1, ceil( (fc_getsession('max_numpre')::integer - 
		                                   fc_getsession('min_numpre')::integer + 1) / fc_getsession('blocos_gera_debitos')::float8 )::integer) AS id 
		) AS x;";

	$rsDebitosProcessa = db_query($pConexao, $sSqlDebitosGera, $sArquivoLog);
	$iNumpreCount = db_numrows($rsDebitosProcessa, $sArquivoLog);

	echo "\n";
	for($i=0; $i<$iNumpreCount; $i++) {
		$iPerc = round((($i+1)/$iNumpreCount)*100, 2);

		$oDebitosProcessa = db_utils::fieldsMemory($rsDebitosProcessa, $i);

		db_log("> gerando {$sDebitosGera} - ".($i+1)." de {$iNumpreCount} - {$iPerc}% (memoria atual=".db_uso_memoria()." pico=".db_uso_memoria(1).")              \r",
			$sArquivoLog, 1, true, false);

		$sSql = "
			SELECT k00_numpre 
			  FROM arreinstit 
			       NATURAL JOIN arrecad 
			 WHERE k00_instit = {$iInstit} 
			   AND k00_numpre BETWEEN {$oDebitosProcessa->numpre_ini} AND {$oDebitosProcessa->numpre_fim}
			 LIMIT 1";

		$rsVerifica = db_query($pConexao, $sSql, $sArquivoLog);
		$iRows = db_numrows($rsVerifica, $sArquivoLog);

		if ($iRows > 0) {
			$oDebitosGera->sequencial            = 0;
			$oDebitosGera->numpre_ini            = $oDebitosProcessa->numpre_ini;
			$oDebitosGera->numpre_fim            = $oDebitosProcessa->numpre_fim;
			$oDebitosGera->registros_processados = 0;
			$oDebitosGera->status                = 'NAO INICIADO';
			$oDebitosGera->inicio                = null;
			$oDebitosGera->fim                   = null;
			$oDebitosGera->observacoes           = null;
			$oDebitosGera->insertValue();
		}
	}

	$oDebitosGera->persist();

	$sSqlDebitosGera = "
		CREATE SEQUENCE {$sDebitosGera}_sequencial_seq;
		UPDATE {$sDebitosGera}
		   SET sequencial = nextval('{$sDebitosGera}_sequencial_seq'); 

		ALTER SEQUENCE {$sDebitosGera}_sequencial_seq
		  OWNED BY {$sDebitosGera}.sequencial;
  
		COMMIT;";
	db_query($pConexao, $sSqlDebitosGera, $sArquivoLog);

	db_log("Tabela {$sDebitosGera} gerada...", $sArquivoLog);

	return;
}


function waitForThreads($iMaxThreads, &$aThreads, $sArquivoLog) {

	do {
		/* verifica status das threads */
		for($w=0; $w < $iMaxThreads; $w++) {
			if( isset($aThreads[$w]) and !$aThreads[$w][0]->isAlive() ) {
				unset($aThreads[$w]);
			}
		}
	} while(count($aThreads) == $iMaxThreads);

}

function waitForFinishThreads($iMaxThreads, &$aThreads, $sArquivoLog) {

	do {
		/* verifica status das threads */
		for($w=0; $w < $iMaxThreads; $w++) {
			if( isset($aThreads[$w]) and !$aThreads[$w][0]->isAlive() ) {
				unset($aThreads[$w]);
			}
		}
	} while(count($aThreads) > 0);

}


function finish_build_debitos($aConfigConexao, $sArquivoLog, $sSufixo, $sDebitosGera, $sDebitosName, $sData, $iInstit, $iLimit = null) {

	$sDataSource = "host={$aConfigConexao["host"]} 
					dbname={$aConfigConexao["dbname"]} 
					port={$aConfigConexao["port"]} 
					user={$aConfigConexao["user"]} 
					password={$aConfigConexao["password"]}";

	if(!($pConexao = pg_connect($sDataSource))) {
		db_log("Erro ao conectar na  ($sDataSource)...", $sArquivoLog);
		return;
	}

	$sSql = "SELECT fc_startsession();SET synchronous_commit TO off;SET application_name TO 'build_debitos_thread.php(finish_build_debitos)';";
	db_query($pConexao, $sSql, $sArquivoLog);

	$sSql  = "SELECT sequencial ";
	$sSql .= "  FROM {$sDebitosGera} ";
	$sSql .= " WHERE status = 'FINALIZADO' ";
	$sSql .= " ORDER BY sequencial ";

	if( !is_null($iLimit) ) {
		$sSql .= " LIMIT {$iLimit} ";
	} else {
		echo "\n";
	}

	$rsProcessamento = db_query($pConexao, $sSql, $sArquivoLog);
	$iNumrows        = db_numrows($rsProcessamento, $sArquivoLog);

	for ($i=0; $i<$iNumrows; $i++) {
		$oDebito = db_utils::fieldsMemory($rsProcessamento, $i);

		if (is_null($iLimit)) {
			$iPercPrincipal = round((($i+1)/$iNumrows)*100, 2);
			db_log("> finalizando geracao da debitos {$iPercPrincipal}% (memoria atual=".db_uso_memoria()." pico=".db_uso_memoria(1).")      \r", $sArquivoLog, 1, true, false);
		}

		$sTempName = "w_debitos_{$sSufixo}_{$oDebito->sequencial}";

		$sSql = "
			BEGIN;
				DELETE FROM {$sDebitosGera} WHERE sequencial = {$oDebito->sequencial};
				INSERT INTO {$sDebitosName} SELECT * FROM ${sTempName};
				DROP TABLE {$sTempName};
			COMMIT;
			";

		db_query($pConexao, $sSql, $sArquivoLog);
	}

	if (is_null($iLimit)) {
		$sPrefixConstraint = explode('.', $sDebitosName);
		$sPrefixConstraint = $sPrefixConstraint[1];
		$sSql = "
			BEGIN;
				INSERT INTO datadebitos (k115_sequencial, k115_data, k115_instit)
					VALUES (nextval('datadebitos_k115_sequencial_seq'), '{$sData}', {$iInstit}); 
				ALTER TABLE {$sDebitosName}
					ADD CONSTRAINT {$sPrefixConstraint}_data_instit_ck
					CHECK (k22_data = '{$sData}' AND k22_instit = {$iInstit});
				ALTER TABLE {$sDebitosName} INHERIT debitos;
				DROP TABLE {$sDebitosGera};
			COMMIT;";
		db_query($pConexao, $sSql, $sArquivoLog);
	}

	pg_close($pConexao);
}

function analyze_table($aConfigConexao, $sTable, $sArquivoLog) {

	$sDataSource = "host={$aConfigConexao["host"]} 
					dbname={$aConfigConexao["dbname"]} 
					port={$aConfigConexao["port"]} 
					user={$aConfigConexao["user"]} 
					password={$aConfigConexao["password"]}";

	if(!($pConexao = pg_connect($sDataSource))) {
		db_log("Erro ao conectar na  ($sDataSource)...", $sArquivoLog);
		return;
	}

	$sSql = "SET application_name TO 'build_debitos_thread.php(analyze_debitos)';";
	db_query($pConexao, $sSql, $sArquivoLog);

	db_log("Atualizando estatisticas da tabela {$sTable} ...", $sArquivoLog);
	db_query($pConexao, "ANALYZE {$sTable};", $sArquivoLog);

	pg_close($pConexao);
}

?>
