<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


$sWhere               = isset($argv[1])?$argv[1]:"1=1";
$sLimit               = isset($argv[2])?$argv[2]:"1";
$lDeleteAfter         = isset($argv[3])?$argv[3]:0;
$lForceCheckPartition = isset($argv[4])?$argv[4]:0;

/*

  O que precisa ser feito para migrar o db_acount/db_acountkey para db_auditoria de forma incremental

  1) Após atualizada versão e com estrutura do "db_auditoria" na base de dados criar "partições" (somente tabelas)
  2) Criar tabela de controle da migração "db_auditoria_migracao"
  3) Processar a migração em Lotes (registros da "db_auditoria_migracao")
  4) 

*/


db_log("Carregando lotes a serem processados (where=\"{$sWhere}\" limit={$sLimit})", $sArquivoLog);
$sSql  = "SELECT * ";
$sSql .= "  FROM configuracoes.db_auditoria_migracao ";
$sSql .= " WHERE status = 'NAO INICIADO' ";
$sSql .= "   AND {$sWhere} ";
$sSql .= " ORDER BY sequencial ";
$sSql .= " LIMIT {$sLimit} ";

$rsMigracao = db_query($pConexao, $sSql, $sArquivoLog);
$iNumrows   = db_numrows($rsMigracao, $sArquivoLog);

if ($iNumrows==0) {
  db_log("Nenhum lote encontrado ...", $sArquivoLog, 1, true, true);
}

$aParticoes  = array();
$aLogsAcessa = array();

$oLogsAcessa = new tableDataManager($pConexao, "db_logsacessa", null, true, 1);


for ($i=0; $i<$iNumrows; $i++) {
  $oMigracao = db_utils::fieldsMemory($rsMigracao, $i);

  $sSql   = "SELECT (pg_try_advisory_lock({$oMigracao->sequencial}) IS TRUE) as bloqueou ";
  $rsLock = db_query($pConexao, $sSql, $sArquivoLog);
  $oLock  = db_utils::fieldsMemory($rsLock, 0);

  unset($rsLock);
  if ($oLock->bloqueou <> 't') {
    db_log("Não consegui obter bloqueio lógico do db_auditoria_migracao.sequencial = {$oMigracao->sequencial}", $sArquivoLog, 1, true, true);
    unset($oLock);
    continue;
  }

  $sSql  = "UPDATE db_auditoria_migracao ";
  $sSql .= "   SET inicio = clock_timestamp(), ";
  $sSql .= "       status = 'INICIADO PID='||cast(pg_backend_pid() as text) ";
  $sSql .= " WHERE sequencial = {$oMigracao->sequencial}";
  db_query($pConexao, $sSql, $sArquivoLog);
  
  db_log("Abrindo cursor...", $sArquivoLog);
  
  /* Abre transacao na base de destino pois a conversão DEVE ser transacional */
  db_query($pConexao,"begin;", $sArquivoLog);

  $iPercPrincipal = round((($i+1)/$iNumrows)*100, 2);

  $sSqlAcount = db_sql_blocos_acount_cursor($oMigracao->id_acount_ini, $oMigracao->id_acount_fim, (!$lDeleteAfter?'_migra':''));

  $iCount = ($oMigracao->id_acount_fim - $oMigracao->id_acount_ini) + 1;

  $sDeclareCursor = "DECLARE cur_acount CURSOR FOR {$sSqlAcount}";
  $rsCursor       = db_query($pConexao, $sDeclareCursor, $sArquivoLog);

  echo "\n";
  $iRegProc = 0;
  for ($w=0; $w<$iCount; $w++) {
    $iPercAcount = round((($w+1)/$iCount)*100, 2);
    $sSql = "FETCH cur_acount";
    $rsAcount = db_query($pConexao, $sSql, $sArquivoLog);
    
    db_log("processando lote {$oMigracao->sequencial} - ".($i+1)." de {$iNumrows} - {$iPercPrincipal}%    acount ".($w+1)." de {$iCount} - {$iPercAcount}% (memoria atual=".db_uso_memoria()." pico=".db_uso_memoria(1).")              \r", $sArquivoLog, 1, true, false);
    
    $iRows = db_numrows($rsAcount, $sArquivoLog);

    if($iRows > 0) {
      $oAcount = db_utils::fieldsMemory($rsAcount, 0);
  
      $sParticao = $oAcount->particao . "_" . $oAcount->instit;
  
      if (!isset($aParticoes[$sParticao])) {
        if ($lDeleteAfter or $lForceCheckPartition) {
          $sSql  = "SELECT configuracoes.fc_auditoria_particao_cria ( ";
          $sSql .= "     'configuracoes', ";
          $sSql .= "     'db_auditoria', ";
          $sSql .= "     'configuracoes', ";
          $sSql .= "     '{$sParticao}', ";
          $sSql .= "     'datahora_sessao BETWEEN \'{$oAcount->datahora_ini}\' AND \'{$oAcount->datahora_fim}\' AND instit = {$oAcount->instit}' ";
          $sSql .= "   ); ";
          db_query($pConexao, $sSql, $sArquivoLog);
        }
        $aParticoes[$sParticao] = new tableDataManager($pConexao, $sParticao, null, true, 1000);
      }

      $oAcount->pkey_valor            = db_format_copy($oAcount->pkey_valor);
      $oAcount->mudancas_valor_antigo = db_format_copy($oAcount->mudancas_valor_antigo);
      $oAcount->mudancas_valor_novo   = db_format_copy($oAcount->mudancas_valor_novo);

      $aParticoes[$sParticao]->sequencial        = $oAcount->id_acount;
      $aParticoes[$sParticao]->esquema           = $oAcount->esquema;
      $aParticoes[$sParticao]->tabela            = $oAcount->tabela;
      $aParticoes[$sParticao]->operacao          = $oAcount->operacao;
      $aParticoes[$sParticao]->transacao         = $oAcount->transacao;
      $aParticoes[$sParticao]->datahora_sessao   = $oAcount->datahora_sessao;
      $aParticoes[$sParticao]->datahora_servidor = $oAcount->datahora_servidor;
      $aParticoes[$sParticao]->tempo             = "00:00:00";
      $aParticoes[$sParticao]->usuario           = $oAcount->login;
      $aParticoes[$sParticao]->chave             = "(\"{$oAcount->pkey_nome_campo}\", \"{$oAcount->pkey_valor}\")";
      $aParticoes[$sParticao]->mudancas          = "(\"{$oAcount->mudancas_nome_campo}\",\"{$oAcount->mudancas_valor_antigo}\",\"{$oAcount->mudancas_valor_novo}\")";

      if (empty($oAcount->codsequen)) {
        if (!isset($aLogsAcessa[$oAcount->instit]
                              [$oAcount->login]
                              [$oAcount->datahora_sessao]
                              ["classes/db_{$oAcount->tabela}_classe.php"]
                              [$oAcount->id_modulo]
                              [$oAcount->id_item])) {
          
          $rNextLogsAcessa = db_query($pConexao, "SELECT nextval('configuracoes.db_logsacessa_codsequen_seq')");
          $oNextLogsAcessa = db_utils::fieldsMemory($rNextLogsAcessa, 0);

          $oLogsAcessa->codsequen = $oNextLogsAcessa->nextval;
          $oLogsAcessa->ip = "127.0.0.1";
          $oLogsAcessa->data = substr($oAcount->datahora_sessao, 00, 10);
          $oLogsAcessa->hora = substr($oAcount->datahora_sessao, 11, 08);
          $oLogsAcessa->arquivo = "classes/db_{$oAcount->tabela}_classe.php";
          $oLogsAcessa->obs = "LogsAcessa Automatico Migracao";
          $oLogsAcessa->id_usuario = $oAcount->id_usuario;
          $oLogsAcessa->id_modulo  = $oAcount->id_modulo;
          $oLogsAcessa->id_item    = $oAcount->id_item;
          $oLogsAcessa->coddepto   = null;
          $oLogsAcessa->instit     = $oAcount->instit;
          $oLogsAcessa->insertValue();

          $aLogsAcessa[$oAcount->instit]
                      [$oAcount->login]
                      [$oAcount->datahora_sessao]
                      ["classes/db_{$oAcount->tabela}_classe.php"]
                      [$oAcount->id_modulo]
                      [$oAcount->id_item] = $oNextLogsAcessa->nextval;

          unset($oNextLogsAcessa);
          unset($rNextLogsAcessa);
        }

        $aParticoes[$sParticao]->logsacessa = $aLogsAcessa[$oAcount->instit]
                                                          [$oAcount->login]
                                                          [$oAcount->datahora_sessao]
                                                          ["classes/db_{$oAcount->tabela}_classe.php"]
                                                          [$oAcount->id_modulo]
                                                          [$oAcount->id_item];

      } else {
        $aParticoes[$sParticao]->logsacessa        = $oAcount->codsequen;
      }
      $aParticoes[$sParticao]->instit            = $oAcount->instit;

      $aParticoes[$sParticao]->insertValue();
      
      $iRegProc++;
      unset($oAcount);
    } else {
      $w = $iCount;
    }

    unset($rsAcount);
  }

  db_log("processando lote {$oMigracao->sequencial} - ".($i+1)." de {$iNumrows} - {$iPercPrincipal}%    acount {$iCount} de {$iCount} - 100% (memoria atual=".db_uso_memoria()." pico=".db_uso_memoria(1).")              \r", $sArquivoLog, 1, true, false);

  foreach($aParticoes as $oParticao) {
    $oParticao->persist();
  }

  db_query($pConexao, "CLOSE cur_acount", $sArquivoLog);

  /* Finaliza Transacao na base Destino */
  if ($bErro) {
    $sOperFim = "ROLLBACK";
  }else{
    $sOperFim = "COMMIT";
  }

  db_log("Finalizando transacao [{$sOperFim}]", $sArquivoLog, 1, true, true);

  if (db_query($pConexao, "{$sOperFim};", $sArquivoLog)) {

    db_query($pConexao, "BEGIN;", $sArquivoLog);

    $sSql  = "UPDATE db_auditoria_migracao ";
    $sSql .= "   SET status = 'FINALIZADO', ";
    $sSql .= "       registros_processados = {$iRegProc}, ";
    $sSql .= "       fim = clock_timestamp(), ";
    if($iRegProc==0) {
      $sSql .= "       observacoes = 'NENHUM REGISTRO ENCONTRADO PARA SER MIGRADO PELO SCRIPT {$sNomeScript}' ";
    } else {
      $sSql .= "       observacoes = 'LOTE MIGRADO PELO SCRIPT {$sNomeScript}' ";
    }
    $sSql .= " WHERE sequencial = {$oMigracao->sequencial}";
    db_query($pConexao, $sSql, $sArquivoLog);               

    if ($lDeleteAfter) {
      db_log("Limpando db_acount, db_acountkey e db_acount acesso ({$oMigracao->id_acount_ini} a {$oMigracao->id_acount_fim})", $sArquivoLog, 1, true, true);
      $sSql  = "DELETE FROM ONLY configuracoes.db_acount       WHERE id_acount BETWEEN {$oMigracao->id_acount_ini} AND {$oMigracao->id_acount_fim};";
      $sSql .= "DELETE FROM ONLY configuracoes.db_acountkey    WHERE id_acount BETWEEN {$oMigracao->id_acount_ini} AND {$oMigracao->id_acount_fim};";
      $sSql .= "DELETE FROM ONLY configuracoes.db_acountacesso WHERE id_acount BETWEEN {$oMigracao->id_acount_ini} AND {$oMigracao->id_acount_fim};";
      db_query($pConexao, $sSql, $sArquivoLog);
    }
  
    /* Remove trava (lock) lógico */
    $sSql = "SELECT pg_advisory_unlock({$oMigracao->sequencial})";
    db_query($pConexao, $sSql, $sArquivoLog);

    db_query($pConexao, "COMMIT;", $sArquivoLog);
  }


  unset($oMigracao);
}

echo "";


/* Final do Script */
include("lib/db_final_script.php");

?>