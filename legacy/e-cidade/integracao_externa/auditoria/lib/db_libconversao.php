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

//
// Funcao para dar Echo dos Logs - retorna o TimeStamp
//
// Tipos: 0 = Saida Tela e Arquivo
//        1 = Saida Somente Tela
//        2 = Saida Somente Arquivo
//
//
function db_log($sLog="", $sArquivo="", $iTipo=0, $lLogDataHora=true, $lQuebraAntes=true) {
  //
  $aDataHora = getdate();

  $sQuebraAntes = $lQuebraAntes?"\n":"";


  if($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes,
                          $aDataHora["mday"], $aDataHora["mon"], $aDataHora["year"],
                          $aDataHora["hours"], $aDataHora["minutes"], $aDataHora["seconds"],
                          $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }


  // Se habilitado saida na tela...
  if($iTipo==0 or $iTipo==1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo...
  if($iTipo==0 or $iTipo==2) {
    if(!empty($sArquivo)) {
      $fd=fopen($sArquivo, "a+");
      if($fd) { 
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
      //system("echo '$sOutputLog' >> $sArquivo");
    }
  }

  return $aDataHora;
}

//
// Funcao para executar uma query no PostgreSQL (com tratamento de erros e geracao de Log)
// TipoConexao - 1 = postgres    2 = firebird 
function db_query($pConexao, $sSql, $sArquivoLog="", $lErroDie=true,$lIgnoreAll=false,$sTipoConexao=1 ) {

  if(!is_resource($pConexao)) {
    db_log("ERRO: db_query - Conexao Invalida", $sArquivoLog);
    if($lErroDie) {
      die();
    } 
    return false;
  }

  if(empty($sSql) or is_null($sSql)) {
    db_log("ERRO: db_query - Sql vazio", $sArquivoLog);
    if($lErroDie) {
      die();
    } 
    return false;
  } 

  if($sTipoConexao == 1){
    $rsRetorno = @pg_query($pConexao, $sSql);
  }elseif($sTipoConexao == 2){
    $rsRetorno = @ibase_query($pConexao, $sSql);
  }

  if(!$rsRetorno && !$lIgnoreAll) {
    $sBackTrace = var_export(debug_backtrace(), true);
    db_log("ERRO: db_query - DEBUG BACKTRACE:\n$sBackTrace", $sArquivoLog);
    db_log("ERRO: PostgreSQL (last)   - ".pg_last_error($pConexao)."\n", $sArquivoLog);
    if($lErroDie) {
      die();
    } 
  }

  return $rsRetorno;
}


function db_numrows($rsResultSet, $sArquivoLog="") {

  if(!$rsResultSet) {
    return 0;
  }
  $iNumRows = @pg_num_rows($rsResultSet);

  $sErro = pg_result_error($rsResultSet);

  if(!empty($sErro)) {
    db_log("ERRO: db_numrows - ".$sErro, $sArquivoLog);
    return -1;
  }

  return $iNumRows;
}


function db_numrows_table($pConexao, $sTabela, $sArquivoLog="") {
  
  //db_query($pConexao, "ANALYZE {$sTabela};", $sArquivoLog);
  //$sSql = "select reltuples from pg_class where relname = '{$sTabela}' and relkind = 'r'";

  $sSql = " select count(*) as total_linhas from {$sTabela} ";

  return pg_result(db_query($pConexao, $sSql, $sArquivoLog), 0, "total_linhas");

}

function db_sqlformat($variavel=null) {
  if ((is_string($variavel) && $variavel <> 'null') || trim($variavel) == '') {
    return "'".$variavel."'";
  } else if (is_bool($variavel)) {
    if ($variavel == true ) {
      return "'t'";
    } else if ($variavel == false ) {
      return "'f'";
    }
  } else {
    return $variavel;
  }
}


function db_sql_blocos_acount($iIdIni=0, $iIdFim=0) {

  /* Definicao do SQL na base de Origem para Carga dos Dados */
  $sArquivoSQL = "sql/01_select_blocos_acount.sql";

  if (file_exists($sArquivoSQL)) {
    $sSql = file_get_contents($sArquivoSQL);
  } else {
    db_log("ERRO: arquivo $sArquivoSQL não encontrado!");
    /* Final do Script */
    include("db_final_script.php");
    exit(1);
  }

  $sSql = str_replace('{$acount_ini}', $iIdIni, $sSql);
  $sSql = str_replace('{$acount_fim}', $iIdFim, $sSql);

  return $sSql;
}

function db_sql_blocos_acount_cursor($oMigracao, $sSufixo='') {

  /* Definicao do SQL na base de Origem para Carga dos Dados */
  $sArquivoSQL = "sql/01_select_blocos_acount_cursor.sql";

  if (file_exists($sArquivoSQL)) {
    $sSql = file_get_contents($sArquivoSQL);
  } else {
    db_log("ERRO: arquivo $sArquivoSQL não encontrado!");
    /* Final do Script */
    include("db_final_script.php");
    exit(1);
  }

  $sSql = str_replace('{$acount_ini}',   $oMigracao->id_acount_ini, $sSql);
  $sSql = str_replace('{$acount_fim}',   $oMigracao->id_acount_fim, $sSql);
  $sSql = str_replace('{$datahora_ini}', $oMigracao->datahora_ini, $sSql);
  $sSql = str_replace('{$datahora_fim}', $oMigracao->datahora_fim, $sSql);
  $sSql = str_replace('{$data_ini}',     $oMigracao->data_ini, $sSql);
  $sSql = str_replace('{$data_fim}',     $oMigracao->data_fim, $sSql);
  $sSql = str_replace('{$instit}',       $oMigracao->instit, $sSql);
  $sSql = str_replace('{$sufixo}',       $sSufixo, $sSql);

  return $sSql;
}

function db_format_copy($value) {

  $return = str_replace('"', '""',        $value);
  $return = str_replace('\\', '\\\\\\\\', $return);
  $return = str_replace("\n", '\n',       $return);
  $return = str_replace("\r", '\r',       $return);
  $return = str_replace("\t", '\t',       $return);
  
  return $return;
}


function _size_pretty($size) {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

// 0 = Atual 1 = Pico de Memoria
function db_uso_memoria($lTipo=0) {
  return _size_pretty(($lTipo==0?memory_get_usage(true):memory_get_peak_usage(true)));
}

?>
