<?
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
db_query($pConexaoDestino, "SET application_name TO 'cleanup_debitos.php(main)'", $sArquivoLog);

/* Variavel para ser utilizada no controle de Erros */
$bErro = false;

error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set('error_log','log/php_error.log');

/* Parametros */
$iInstit                       = isset($argv[1])?(int)$argv[1]:1;
$iDiasManterDebitos            = isset($argv[2])?(int)$argv[2]:1; 

$sFileCustomSQL = basename($sNomeScript, ".php") . ".{$iInstit}.sql";

if (file_exists($sFileCustomSQL)) {
	db_log("Carregando particoes para realizar limpeza (instit = {$iInstit}, diasmanterdebitos = {$iDiasManterDebitos}) apartir do arquivo $sFileCustomSQL", $sArquivoLog);
	$sSql = file_get_contents($sFileCustomSQL);
	/* Ajusta parametros */
	$sSql = str_replace('{$iInstit}', $iInstit, $sSql);
	$sSql = str_replace('{$iDiasManterDebitos}', $iDiasManterDebitos, $sSql);
	db_log("Gerando log do script SQL customizado $sFileCustomSQL" . PHP_EOL . "<sql_custom>" . PHP_EOL . $sSql . PHP_EOL . "</sql_custom>", $sArquivoLog);
} else {
	db_log("Carregando particoes para realizar limpeza (instit = {$iInstit}, diasmanterdebitos = {$iDiasManterDebitos})", $sArquivoLog);
	$sSql  = "SELECT * FROM ( ";
	$sSql .= "  SELECT *, ";
	$sSql .= "         row_number() OVER (), ";
	$sSql .= "         count(*)     OVER () FROM ( ";
	$sSql .= "      SELECT k115_data AS data, ";
	$sSql .= "             k115_instit AS instit, ";
	$sSql .= "             'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit AS tabela ";
	$sSql .= "        FROM datadebitos ";
	$sSql .= "       WHERE k115_instit = {$iInstit} ";
	$sSql .= "         AND (current_date - k115_data + 1) >= {$iDiasManterDebitos} ";
	$sSql .= "         AND extract(day from k115_data)::integer <> fc_ultimodiames(extract(year from k115_data)::integer, extract(month from k115_data)::integer) ";
	$sSql .= "         AND EXISTS (SELECT 1 ";
	$sSql .= "                       FROM pg_class ";
	$sSql .= "                      WHERE relkind = 'r' ";
	$sSql .= "                        AND relname = 'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit) ";
	$sSql .= "       ORDER BY data) AS x ";
	$sSql .= "   ) AS y ";
	$sSql .= " WHERE row_number <> count ";
	$sSql .= "ORDER BY data DESC ";
}

$rsProcessamento = db_query($pConexaoDestino, $sSql, $sArquivoLog);
$iNumrows        = db_numrows($rsProcessamento, $sArquivoLog);

if ($iNumrows==0) {
	db_log("", $sArquivoLog);
	db_log("Nenhuma particao encontrada para execucao da limpeza ...", $sArquivoLog);
}

db_log("", $sArquivoLog);

for ($i=0; $i<$iNumrows; $i++) {
	$oParticao = db_utils::fieldsMemory($rsProcessamento, $i);

	$iPercPrincipal = round((($i+1)/$iNumrows)*100, 2);

	db_log("Processando limpeza ".($i+1)."/{$iNumrows} - particao {$oParticao->tabela}...", $sArquivoLog);

	/* Verifica se existe(m) LISTA(s) gerada(s) para essa particao */
	$sSqlVerifica  = "SELECT 1 ";
	$sSqlVerifica .= "  FROM lista ";
	$sSqlVerifica .= " WHERE k60_datadeb = '{$oParticao->data}' ";
	$sSqlVerifica .= "   AND k60_instit  = {$oParticao->instit} ";
	$sSqlVerifica .= " LIMIT 1 ";

	$rsVerifica = db_query($pConexaoDestino, $sSqlVerifica, $sArquivoLog);

	/* Inicia Transacao */
	$sSqlLimpa  = "BEGIN; " . PHP_EOL;

	$lDropPartition = (db_numrows($rsVerifica, $sArquivoLog) == 0);
	$sDropMessage   = "> nao existe(m) lista(s) gerada(s) para essa particao... REMOVENDO particao {$oParticao->tabela}...";

	if (db_numrows($rsVerifica, $sArquivoLog) > 0) {

		$sSqlListas  = "SELECT string_agg(k60_codigo::TEXT, ', ') AS listas, ";
		$sSqlListas .= "       date_part('month', age(current_date, '{$oParticao->data}')) + (date_part('year', age(current_date, '{$oParticao->data}')) * 12) AS meses";
		$sSqlListas .= "  FROM lista ";
		$sSqlListas .= " WHERE k60_datadeb = '{$oParticao->data}' ";
		$sSqlListas .= "   AND k60_instit  = {$oParticao->instit} ";

		$rsListas = db_query($pConexaoDestino, $sSqlListas, $sArquivoLog);
		$oLista = db_utils::fieldsMemory($rsListas, 0);

		if ($oLista->meses < 12) {
			db_log("> existe(m) a(s) seguinte(s) lista(s) gerada(s) para essa particao: {$oLista->listas} ... LIMPANDO particao {$oParticao->tabela}...", $sArquivoLog);

			$sTempTable1 = "w_limpa_{$oParticao->tabela}_1";
			$sTempTable2 = "w_limpa_{$oParticao->tabela}_2";

			/* Cria tabela Temporaria COM Numpres que permanecerao */
			$sSqlLimpa .= "CREATE TEMP TABLE {$sTempTable1} AS ";
			$sSqlLimpa .= "SELECT DISTINCT ";
			$sSqlLimpa .= "       k61_numpre, ";
			$sSqlLimpa .= "       k61_numpar ";
			$sSqlLimpa .= "  FROM lista ";
			$sSqlLimpa .= "       JOIN listadeb ON k61_codigo = k60_codigo ";
			$sSqlLimpa .= " WHERE k60_datadeb = '{$oParticao->data}' ";
			$sSqlLimpa .= "   AND k60_instit  = {$oParticao->instit}; " . PHP_EOL;

			/* Cria tabela Temporaria COM debitos que permanecerao */
			$sSqlLimpa .= "CREATE TEMP TABLE {$sTempTable2} AS ";
			$sSqlLimpa .= "SELECT {$oParticao->tabela}.* ";
			$sSqlLimpa .= "  FROM {$sTempTable1} ";
			$sSqlLimpa .= "       JOIN {$oParticao->tabela}  ON k22_numpre = k61_numpre ";
			$sSqlLimpa .= "                                 AND k22_numpar = k61_numpar; " . PHP_EOL;

			/* Reconstrucao da particao somente com debitos que permanecerao */
			$sSqlLimpa .= "TRUNCATE {$oParticao->tabela}; " . PHP_EOL;
			$sSqlLimpa .= "INSERT INTO {$oParticao->tabela} SELECT * FROM {$sTempTable2}; " . PHP_EOL;
			$sSqlLimpa .= "DROP TABLE {$sTempTable1}; " . PHP_EOL;
			$sSqlLimpa .= "DROP TABLE {$sTempTable2}; " . PHP_EOL;

			/* Atualizacao das estatisticas da particao que permanecerao */
			$sSqlLimpa .= "ANALYZE {$oParticao->tabela}; " . PHP_EOL;

		} else {
			$lDropPartition = true;
			$sDropMessage   = "> existe(m) a(s) seguinte(s) lista(s) há mais de 12 meses gerada(s) para essa particao: {$oLista->listas} ... REMOVENDO particao {$oParticao->tabela}...";
		}

	}

	if ($lDropPartition) {
		db_log($sDropMessage, $sArquivoLog);
		$sSqlLimpa .= "DROP TABLE IF EXISTS {$oParticao->tabela}; " . PHP_EOL;
	}

	$sSqlLimpa .= "DELETE ";
	$sSqlLimpa .= "  FROM datadebitos ";
	$sSqlLimpa .= " WHERE k115_data   = '{$oParticao->data}' ";
	$sSqlLimpa .= "   AND k115_instit = {$oParticao->instit}; " . PHP_EOL;

	$sSqlLimpa .= "ANALYZE datadebitos; " . PHP_EOL;

	/* Finaliza Transacao */
	$sSqlLimpa .= "COMMIT; " . PHP_EOL;

	db_query($pConexaoDestino, $sSqlLimpa, $sArquivoLog);
}

db_log("Finalizando limpeza ...", $sArquivoLog);
db_log("", $sArquivoLog);

/* Final do Script */
include("lib/db_final_script.php");

?>
