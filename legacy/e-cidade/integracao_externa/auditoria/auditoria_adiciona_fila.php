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

/* Conexao com base - seta $pConexao */
include("lib/db_conecta.php");

/* Variavel para ser utilizada no controle de Erros */
$bErro = false;

error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set('error_log','log/php_error.log');

$sSql = "SELECT * FROM pg_stat_activity WHERE state = 'active' AND query ~ 'fc_auditoria_adiciona' AND pid <> pg_backend_pid()";
$rsCheckRunning = db_query($pConexao, $sSql, $sArquivoLog);

if (db_numrows($rsCheckRunning) > 0) {
	db_log("AVISO: Abortando pois já existe outro processo executando essa operação ...", $sArquivoLog);
	db_log("", $sArquivoLog);
} else {
	/* A cada hora cheia (minuto=0) rodamos a PL para adicionar "eventuais perdidos" na fila */
	if ((int)date('i') == 0) {
		db_log("Adicionando TODOS registros da db_acount* para fila de migracao", $sArquivoLog);
		$sSql  = "SELECT fc_auditoria_adiciona_todos_acount_fila() ";
	} else {
		db_log("Adicionando registros da db_acount* para fila de migracao", $sArquivoLog);
		$sSql  = "SELECT fc_auditoria_adiciona_acount_fila() ";
	}
	db_query($pConexao, $sSql, $sArquivoLog);
}

echo "";

/* Final do Script */
include("lib/db_final_script.php");

?>
