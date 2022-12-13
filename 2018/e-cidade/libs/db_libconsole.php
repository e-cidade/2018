<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


function db_troca_aspas( $string ){
  $string   = str_replace( "'", "'||chr(39)||'", $string ); //troca aspas simples por chr(39)
  return $string;
}

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
// 
function db_query($pConexao, $sSql, $sArquivoLog="", $lErroDie=true,$lIgnoreAll=false) {

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

  $rsRetorno = @pg_query($pConexao, $sSql);

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

function db_empty($sValor) {
  return (trim($sValor)=="" or $sValor==null);
}

function db_existe_relacao($pConexao, $sEsquema="public", $sRelacao, $sTipo="tabela") {

  // Mapeamento dos tipos de relações do PostgreSQL
  $aTiposRelacao["tabela"]    = "r"; // relkind r = ordinary table
  $aTiposRelacao["indice"]    = "i"; // relkind i = indice
  $aTiposRelacao["visao"]     = "v"; // relkind v = view
  $aTiposRelacao["sequencia"] = "S"; // relkind S = sequence
  $aTiposRelacao["tipo"]      = "c"; // relkind c = composite type 
  $aTiposRelacao["especial"]  = "s"; // relkind s = special
  $aTiposRelacao["toast"]     = "t"; // relkind t = toast table
  if(!is_resource($pConexao) or db_empty($sRelacao)) {
    return false;
  }

  // Select para buscar Relação
  $sSqlRelacao  = "SELECT relname ";
  $sSqlRelacao .= "  FROM pg_catalog.pg_class c";
  $sSqlRelacao .= "       INNER JOIN pg_catalog.pg_namespace n on n.oid = c.relnamespace ";
  $sSqlRelacao .= " WHERE n.nspname = '{$sEsquema}' ";
  $sSqlRelacao .= "   AND c.relname = '{$sRelacao}' ";
  $sSqlRelacao .= "   AND c.relkind = '{$aTiposRelacao[$sTipo]}' ";

  $rsRelacao = db_query($pConexao, $sSqlRelacao);
  return (db_numrows($rsRelacao)>0);
} 


function db_ddl_tabela($pConexao, $sTabela, $sEsquema="public",$pConexaoPesquisa=null) {

  $pConexaoDestino = $pConexao;

  if ( $pConexaoPesquisa != null ){

    $pConexao = $pConexaoPesquisa;
    
  }

  // Select para Listar Colunas de uma Tabela
  $sSqlCampos  = "  SELECT *                            ";
  $sSqlCampos .= "    FROM information_schema.columns   ";
  $sSqlCampos .= "   WHERE table_schema = '{$sEsquema}' ";
  $sSqlCampos .= "     AND table_name   = '{$sTabela}'  ";
  $sSqlCampos .= "ORDER BY ordinal_position             ";

  $sSqlCreateTable = "CREATE TABLE {$sEsquema}.{$sTabela} (";

  $rsCampos = db_query($pConexao, $sSqlCampos);

  $sVirgula = "";

  for($iCont=0; $iCont < db_numrows($rsCampos); $iCont++) {
    $oCampos = db_utils::fieldsmemory($rsCampos, $iCont);

    switch ($oCampos->udt_name) {
      // Trata campos tipo numeric
      case "numeric": 
        if(!db_empty($oCampos->numeric_precision) and !db_empty($oCampos->numeric_scale)) {
          $sTipo = "NUMERIC({$oCampos->numeric_precision}, {$oCampos->numeric_scale})";
        } else {
          $sTipo = "NUMERIC";
        }
        break;

      // Campos caracter
      case "char":
      case "varchar":
        $sTipo = strtoupper($oCampos->udt_name) . "({$oCampos->character_maximum_length})";
        break;

      // Default
      default:
        $sTipo = strtoupper($oCampos->udt_name);
        break;
    }

    $sSqlCreateTable .= $sVirgula . "\"{$oCampos->column_name}\" {$sTipo}";

    $sVirgula = ", ";
  }
  
  $sSqlCreateTable .= ");";

  db_query($pConexaoDestino,$sSqlCreateTable);

}


?>