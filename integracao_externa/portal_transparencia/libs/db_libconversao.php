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


function db_endereco($endtrocar) {
  $er = ',[ ][0-9]*[ ]';
  $texto = $endtrocar;
  global $numero;
  global $ender;
  global $compl;
  if(ereg($er,$texto,$matriz)) {
    $numero = trim(substr($matriz[0],2,9));
    $xender = split($matriz[0],$texto);
    $ender  = $xender[0];
    $compl  = trim(substr($xender[1],1,20));
  } else {
    $ender  = substr($arquivo[$i],135,40);
    $numero = 0;
    $compl  = "";
  }

}

function db_testaduplo($nome,$endereco,$numero,$cidade,$uf,$cpf){

  // cgc cnpj

  $_achou = false;

  if (strlen(ltrim($nome)) == 0) return 0;

  if (strlen($cpf) > 0) {

    $sql6 = "select z01_numcgm from cgm where z01_cgccpf = '$cpf'";
    $result6 = pg_exec($sql6);
    if ($result6 == false) {
      echo "sql executado: $sql6\n";
      return 9999999999;
    }

    if ($result6 != false) {
      if (pg_numrows($result6) > 0) {
	 $_achou = true;
      }
    }
  }

  if ($_achou == false) {
  
    // nome, endereco, numero, cidade, uf...
   
    $sql6 = "select z01_numcgm from cgm where z01_nome = '$nome' and z01_ender = '$endereco' and z01_numero = $numero and z01_munic = '$cidade' and z01_uf = '$uf'";
    $result6 = pg_exec($sql6);
    if ($result6 == false) {
      echo "sql executado: $sql6\n";
      return 9999999999;
    }
//echo "nome: $nome - " . pg_numrows($result6) . "\n";
    if (pg_numrows($result6) > 0) {
       $_achou = true;
    }

  }

  if ($_achou == false) {
    return 0;
  } else {
    return pg_result($result6,0);
  }

}

function db_troca_aspas( $string ){

  $string   = str_replace( "'", "'||chr(39)||'", $string ); //troca aspas simples por chr(39)
  return $string;
}

function db_busca_usuario( $conn1, $str_login ) {
         //db_usuarios - login
//         if ($str_login == "paulinho")	$str_login = 'paulo';
//         if ($str_login == "sam30")     $str_login = 'dbseller';
//         if ($str_login == "jaque") 	$str_login = 'jaqueline';
//         if ($str_login == "ione")	$str_login = 'yone';
//         if ($str_login == "denise") 	$str_login = 'denisetissot';
//         if ($str_login == "atend2") 	$str_login = 'sac';
//         if ($str_login == "atender") 	$str_login = 'sac';
//         if ($str_login == "atender1")  $str_login = 'sac';
//         if ($str_login == "drma")  	$str_login = 'sac';
//         if ($str_login == "drmw")  	$str_login = 'sac';
	 
         $str_sql = "select id_usuario from DB_USUARIOS
                      where login = '" . trim($str_login) . "'";
         $res_db_usuarios = pg_exec( $conn1, $str_sql ) or die ( "FALHA: $str_sql \n" );
         $int_linhas = pg_num_rows( $res_db_usuarios );
         if( $int_linhas == 0 )
             $id_usuario = 1;
         else{
             $row = pg_fetch_row( $res_db_usuarios );
	     $id_usuario = $row[0]; 
         }
  return $id_usuario;
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



//
//  Funcao para converter uma tabela de uma base para outra
//
//

function db_converte_tabela($pConexaoOrigem, $pConexaoDestino, $sArquivoLog, $sTabela, $sFuncaoCallBack="", $aCamposPk=array(), $iForma=0, $aOpcoes=array()) {
  //

  if(!is_resource($pConexaoOrigem)) {
    db_log("Conexao de Origem Invalida", $sArquivoLog);
    return false;
  }

  if(!is_resource($pConexaoDestino)) {
    db_log("Conexao de Destino Invalida", $sArquivoLog);
    return false;
  }

  $iOffSet = 0;

  $iNumRowsTabela = db_numrows_table($pConexaoOrigem, $sTabela, $sArquivoLog);

  while(true) {

    $sSql = "select * from {$sTabela} offset {$iOffSet} limit 1";

    $rsTabela = db_query($pConexaoOrigem, $sSql, $sArquivoLog);

    if(db_numrows($rsTabela)==0) {
      break;
    }

    $sSqlInsert = "INSERT INTO {$sTabela} (";
    $sSqlValues = ") VALUES (";

    $sVirgula = "";

    $iContaCampos = pg_num_fields($rsTabela);
    for($iCont=0; $iCont<$iContaCampos; $iCont++) {

      $sNomeCampo  = pg_field_name($rsTabela, $iCont);
      $sTipoCampo  = pg_field_type($rsTabela, $iCont);
      $sValorCampo = stripslashes(pg_result($rsTabela, $iCont));
     
      $sSqlInsert .= $sVirgula . "\"{$sNomeCampo}\"";

      if($sTipoCampo=="date" and empty($sValorCampo)) {
        $sSqlValues .= $sVirgula . "null";
      } else {
        $sSqlValues .= $sVirgula . "'" . addslashes($sValorCampo) ."'";
      }

      $sVirgula = ", ";
    }
    
    if(!empty($sFuncaoCallBack)) {
      if(function_exists($sFuncaoCallBack)) {
        call_user_func($sFuncaoCallBack, $iOffSet, $iNumRowsTabela);
      }
    }

    $sSql = $sSqlInsert . $sSqlValues . ");";

    db_query($pConexaoDestino, $sSql, $sArquivoLog);

    $iOffSet++;
  }

  return true;
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




function db_cria_work_conversao($pConexao, $sEsquema, $sTabela, $sTabelaWork, $aCamposChave, $sArquivoLog) {
 
  if(!is_resource($pConexao) or db_empty($sTabela)) {
    return false;
  }
  $sTabelaWork = db_empty($sTabelaWork)?"work_{$sTabela}":$sTabelaWork;
  $sEsquema    = db_empty($sEsquema)?"public":$sEsquema;
  
  if(db_existe_relacao($pConexao, $sEsquema, $sTabelaWork, "tabela")) {
    db_log("Ja tem a tabela {$sTabelaWork}", $sArquivoLog, 0, true, true);
    $sDrop = "DROP TABLE {$sTabelaWork}";
    db_query($pConexao, $sDrop);
    db_log($sDrop, $sArquivoLog, 0, true, true);
    //return false;
  } 

  $sSqlCreateTable    = "CREATE TABLE \"{$sEsquema}\".\"{$sTabelaWork}\" (";
  $sSqlCreateIndexOri = "CREATE INDEX \"{$sTabelaWork}_ori_in\" ON \"{$sEsquema}\".\"{$sTabelaWork}\"(";
  $sSqlCreateIndexDst = "CREATE INDEX \"{$sTabelaWork}_dst_in\" ON \"{$sEsquema}\".\"{$sTabelaWork}\"(";

  $sVirgula="";

  for($iCont=0; $iCont<count($aCamposChave); $iCont++) {
    $sSqlFieldType  = "SELECT udt_name                                 ";
    $sSqlFieldType .= "  FROM information_schema.columns               ";
    $sSqlFieldType .= " WHERE table_schema = '{$sEsquema}'             ";
    $sSqlFieldType .= "   AND table_name   = '{$sTabela}'              ";
    $sSqlFieldType .= "   AND column_name  = '{$aCamposChave[$iCont]}' ";

    $rsFieldType = db_query($pConexao, $sSqlFieldType, $sArquivoLog);
    $oFieldType = db_utils::fieldsmemory($rsFieldType, 0);

    $sSqlCreateTable    .= $sVirgula."\"{$aCamposChave[$iCont]}_ori\" {$oFieldType->udt_name}";
    $sSqlCreateIndexOri .= $sVirgula."\"{$aCamposChave[$iCont]}_ori\""; 
    $sSqlCreateIndexDst .= $sVirgula."\"{$aCamposChave[$iCont]}_dst\""; 
    $sVirgula = ", ";
    $sSqlCreateTable .= $sVirgula."\"{$aCamposChave[$iCont]}_dst\" {$oFieldType->udt_name}";
  }

  $sSqlCreateTable    .= ");";
  $sSqlCreateIndexOri .= ");";
  $sSqlCreateIndexDst .= ");";
  


  db_log($sSqlCreateTable, $sArquivoLog, 0, true, true);
  db_query($pConexao, $sSqlCreateTable);
  /*
  db_log($sSqlCreateIndexOri, $sArquivoLog, 0, true, true);
  db_query($pConexao, $sSqlCreateIndexOri);
  db_log($sSqlCreateIndexDst, $sArquivoLog, 0, true, true);
  db_query($pConexao, $sSqlCreateIndexDst);
  */
}

function db_gera_work_conversao($pConexaoOri,$pConexaoDes, $sTabela, $sTabelaWork="", $aCamposChave, $iTipoforma=1,$iSomaOuSeq ,$iSeq) {
  /* 
  $pConexaoOri  - Conexão de Origem
  $pConexaoDes  - Conexão de destino
  $sTabela      - Nome da Tabela Envolvida
  $sTabelaWork  - Nome da Tabela Auxiliar (se não informada o padrão é work_nometabela)
  $aCamposChave - Array com o Nome dos Campos da Chave envolvida
  $iTipoforma   - Forma de inclusão no campo destino... 1- soma 10000 , 2- pega sequencial
  $iSomaOuSeq   - se o $iTipoforma for =2... precisa indicar a sequencia da tabela.
   */

db_cria_work_conversao($pConexaoOri, null,$sTabela, null, $aCamposChave, $sArquivoLog);
db_log("\n \n ", null, 1, false, false);
  if(!is_resource($pConexaoOri) or db_empty($sTabela)) {
    db_log("Problema com conexao origem  ", $sArquivoLog, 0, true, true);
    return false;
  }
  if($iTipoforma==2){
    if(!is_resource($pConexaoDes) or db_empty($sTabela)) {
      db_log("Problema com conexao destino ", $sArquivoLog, 0, true, true);
      return false;
    }
  }
  $sTabelaWork = db_empty($sTabelaWork)?"work_{$sTabela}":$sTabelaWork;
  $sCampo   = "";
  $sVirgula = "";
  $iSequencial = 0;
  // pega campo do array e concatena com virgula para usar no select

  for($iCont=0; $iCont<count($aCamposChave); $iCont++) {
    $sCampo .= $sVirgula.$aCamposChave[$iCont];
    $sVirgula = ", ";
    
  }
 
  $sSqlDados = "select $sCampo from $sTabela $sWhere1  ";
  //echo "\n{$sSqlDados}\n";
  //die();
  $rsDados = db_query($pConexaoOri, $sSqlDados, $sArquivoLog);
  
  $iNumRowsDados = db_numrows($rsDados);
  if($iNumRowsDados>0){
    // pega nome dos campo da tabelaorigem para usar no insert
    $iContaCamposOri = pg_num_fields($rsDados);
    for($iCampo=0; $iCampo<$iContaCamposOri; $iCampo++) {
      $sNomeCampoOri[$iCampo]  = pg_field_name($rsDados, $iCampo);
    }
    // for no numero de dados encontrados na tabela origem
    //db_log("\n", null, 1, false, false);
    pg_query($pConexaoOri, "copy $sTabelaWork from stdin");
    for($iDados=0; $iDados<$iNumRowsDados; $iDados++) {
      $nPercentual = round((($iDados+1)/$iNumRowsDados) * 100, 2);
      db_log("\rProcessando {$sTabela} {$nPercentual}% .... registro ".($iDados+1)." de {$iNumRowsDados}             ", null, 1, false, false);
      $oDados       = db_utils::fieldsmemory($rsDados,$iDados);
      $sVirgula     = " ";
      $sDadosInclui = "";
      // for nos campos origem
      for($iCampo2=0; $iCampo2<$iContaCamposOri; $iCampo2++) {
        //db_log("tipo =  {$iTipoforma}", null, 1, true, true);
        if($iTipoforma==1){
          // Tipo = 1 soma $iSomaOuSeq
          $sDadoOrig     = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadoDest     = $oDados->$sNomeCampoOri[$iCampo2] + $iSomaOuSeq ;
          $sDadosInclui .=$sVirgula.$sDadoOrig."\t".$sDadoDest;
          $sVirgula = "\t";
        }elseif($iTipoforma==2){
          // Tipo = 2 pega o sequencial do destino
          if($iSequencial == 0){
            $sSqlSeq         = "select nextval('$iSomaOuSeq')";
            $rsSeq           = db_query($pConexaoDes, $sSqlSeq, $sArquivoLog);
            $iContaCamposSeq = pg_num_fields($rsSeq);
            $sNomeCampoSeq   = pg_field_name($rsSeq, 0);
            $oSeq            = db_utils::fieldsmemory($rsSeq,$iCampo2);
            $iSequencial     = $oSeq->$sNomeCampoSeq;
          }else{
            $iSequencial++;
          }
          $sDadoOrig       = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadoDest       =  $iSequencial;
          $sDadosInclui .=$sVirgula.$sDadoOrig."\t".$sDadoDest;
          $sVirgula        = "\t";
    
        }if($iTipoforma==3){
          //duplica
          $sDadoOrig     = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadoDest     = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadosInclui .= $sVirgula.$sDadoOrig."\t".$sDadoDest;
          $sVirgula = "\t";
        }
        $sIncluiDados = $sDadosInclui."\n";
      
      
      }
     if(!pg_put_line($pConexaoOri, $sIncluiDados)) {
        db_log("erro linha {$iCont} copy =  {$sIncluiDados}", $sArquivoLog, 0, true, true);
        die();
      }
     // db_log("\n", null, 1, false, false);
      //db_log("linha inicio =  {$sIncluiDados} ", $sArquivoLog, 0, true, true);
      
      
    }
    pg_put_line($pConexaoOri, "\\.\n"); // Finaliza o Copy
    pg_end_copy($pConexaoOri);
    if($iSeq!=""){
      //insere na work_sequencia
      $sIncluiWork_seq = "insert into work_sequencia (nome, valor) values('".$iSeq."',".$sDadoDest.")";
      //db_log("\n $sIncluiWork_seq ", null, 1, false, false);
      db_query($pConexaoOri, $sIncluiWork_seq);
    }
    db_log("\n", null, 1, false, false);
  }

}// termina funcao gera




/**
* Funcao para pesquisar e alterar o valor do campo
*
* @param  $pConexaoOrigem            resource Conexao de origem, onde serao buscados os para substituicao
* @param  $sValorCampo               string   Valor do campo a ser modificado(valor passado por referencia 
* @param  $aCamposTrocar             mixed    Array associativo com os parametros do campo a ser pesquisado e alterado
*
* @return void                                Sem retorno a variavel $sValorCampo e passada por referencia para seu processamento
*
* @author Robson Inacio 
*
*/

function dbTrocaValores($pConexaoOrigem,$sValorCampo,$aParametrosCampo,$sArquivoInfos='',$sArquivoErros=''){

  if (!is_resource($pConexaoOrigem)) {
    db_log( "ERRO : Conexao de Origem Invalida", $sArquivoErros);
    return false;
  }              

  if ( !db_existe_relacao($pConexaoOrigem, "public", $aParametrosCampo[0] ) ){
    db_log( "ERRO : TABELA AUXILIAR : {$aParametrosCampo[0]} NAO ENCONTRADA NA BASE ORIGEM \n",$sArquivoErros );
    exit;
  }
  
  if ( stripslashes( $sValorCampo ) == "''" ){

    db_log( "ERRO : VALOR DO CAMPO ORIGEM NAO ENCONTRADO : TABELA WORK - {$aParametrosCampo[0]} CAMPO - {$aParametrosCampo[1]} VALOR - {$sValorCampo} \n",$sArquivoErros );
    exit;

  }

  $sqlTrocaValores  = " execute select_{$aParametrosCampo[0]}({$sValorCampo});";

  $rsTrocaValores   = db_query($pConexaoOrigem, $sqlTrocaValores, null);

  if (pg_num_rows($rsTrocaValores) == 0 || $rsTrocaValores == false ) {
    
    db_log("Problema com a tabela {$aParametrosCampo[0]} sql : {$sqlTrocaValores} ", $sArquivoInfos,2);
//    exit;
    return false;
  
  }
  
  $oTrocaValores    = db_utils::fieldsmemory($rsTrocaValores, 0);
     
  $sValorCampo      = $oTrocaValores->$aParametrosCampo[2];

  unset($rsTrocaValores);

}

/**
* Funcao para converter uma tabela da base origem para base auxiliar
*
* @param  $pConexaoOrigem            resource Conexao de origem, onde serao buscados os dados para migracao
* @param  $pConexaoDestino           resource Conexao de destino, onde serao inseridos os dados lidos
* @param  $sTabela                   string   Tabela a ser convertida 
* @param  $aCamposTrocar             mixed    Array associativo com os campos a serem pespquisados e substituidos 
* @param  $sFuncaoCallBack           string   Funcao a ser executada para pesquisa e substituicao dos campos origem e destino 
* @param  $sArquivoLog               string   Nome do arquivo para log 
*
* @return $lTabelaProcessada         boolean  Se a tabela foi processada com sucesso
*
* @author Fabrizio Mello 
*
*/

function dbConverteTabela( $pConexaoOrigem, $pConexaoDestino,  $sTabela, $aCamposTrocar=array(), $sFuncaoCallBack="",$sArquivoInfos='',$sArquivoErros='' ) {

	if (!is_resource($pConexaoOrigem)) {
		db_log("ERRO : Conexao de Origem Invalida", $sArquivoErros);
		return false;
	}

	if (!is_resource($pConexaoDestino)) {
		db_log("ERRO : Conexao de Destino Invalida", $sArquivoErros);
		return false;
	}

  //
  // Cria e aloca em memoria insert para a tabela atual
  //

  //dbPreparaInsert($pConexaoOrigem, $pConexaoDestino, $sTabela, $sArquivoLog);

  $iOffSet = 0;
  $iNumRowsTabela = db_numrows_table($pConexaoOrigem, $sTabela, $sArquivoInfos);

 // $iNumRowsTabela = 2000; 

  db_log("\n",null,1,false,false );

//  $sSql = " select * from {$sTabela} ";

  unset($rsTabela);
   
  $rsTabela = dbSelectTabela($pConexaoOrigem,$sTabela,$aCamposTrocar,$sArquivoInfos,$sArquivoErros);

  $iNumRowsTabela = db_numrows($rsTabela);

  if(db_numrows($rsTabela)==0) {
    db_log("Nao encontrado registros para a tabela : ".strtoupper($sTabela));
    return false;
  }

  if ( db_existe_relacao($pConexaoDestino, "public", $sTabela) ){
  
    db_query($pConexaoDestino,"DROP TABLE {$sTabela} " ); 

  }
    
  // Cria tabela na base aux
  db_ddl_tabela($pConexaoDestino, $sTabela,'public',$pConexaoOrigem);
  
  pg_query($pConexaoDestino, "copy {$sTabela} from stdin");  

  db_log("Inicio Processamento Tabela {$sTabela}...\n", $sArquivoInfos, true, true);

  for ($iTabela = 0 ; $iTabela < $iNumRowsTabela ; $iTabela++) {

    $sMsgProgresso = "\r ".str_pad("Processando tabela ".strtoupper($sTabela),50," ", STR_PAD_RIGHT ). str_pad( round( (( ($iTabela+1) /$iNumRowsTabela)*100),2),3," ",STR_PAD_RIGHT)."%"." -- Processando -- ".($iTabela+1)."  de -- $iNumRowsTabela " ;
    db_log($sMsgProgresso,null,1,false,false );

    $sSqlInsert = "";
    $sSqlCampos = "";
    $sSqlValues = "";

    $sVirgula = "";

    $aLinha = pg_fetch_row($rsTabela, $iTabela);
  
    for ($x=0; $x < count($aLinha); $x++) {

      $sTipo = pg_field_type($rsTabela, $x);

      if(in_array($sTipo, array("text", "bpchar", "varchar"))) {
        $aLinha[$x] = str_replace("\n", '\n', $aLinha[$x]);
        $aLinha[$x] = str_replace("\r", '\r', $aLinha[$x]);
        $aLinha[$x] = str_replace("\t", '\t', $aLinha[$x]);
        $aLinha[$x] = pg_escape_string($aLinha[$x]);
      }

      if(is_null($aLinha[$x]) or (trim($aLinha[$x])=="" and in_array($sTipo, array("int2", "int4", "int8", "float4", "float8"))  )) {
        $aLinha[$x] = '\N';
      }

    }
  
    $sLinha  = implode("\t", $aLinha);
    $sLinha .= "\n";
  
    if (!pg_put_line($pConexaoDestino, $sLinha)) {

      db_log( "ERRO : linha {$iCont} - {$sLinha}" );
      exit;

    }

  }

  pg_put_line($pConexaoDestino, "\\.\n"); // Finaliza o Copy
  pg_end_copy($pConexaoDestino);

  db_log("Fim Processamento Tabela {$sTabela}...\n", $sArquivoInfos, true, true);

  return true;

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

/**
* Funcao para criar indices em cada campo de uma determinada tabela
*
* @param  $pConexao                  resource Conexao da base onde estao as tabelas para criacao dos indices
* @param  $sTabela                   string   Tabela a ser criado os indices  
* @param  $sArquivoLog               string   Nome do arquivo para log 
*
* @return $lTabelaProcessada         boolean  Se a tabela foi processada com sucesso
*
* @author Robson Inacio 
*
*/

function dbCriaIndicesWork($pConexao, $sTabela, $sArquivoLog) {
 
  if(!is_resource($pConexao) or db_empty($sTabela)) {
    return false;
  }
  
  if(!db_existe_relacao($pConexao, 'public', $sTabela, "tabela")) {
    db_log("ERRO : Tabela {$sTabela} nao encontrada na base de dados ", $sArquivoLog, 0, true, true);
    return false;
  } 

  $sSql     = "select * from {$sTabela} limit 1";
  $rsTabela = db_query($pConexao, $sSql);

  $iContaCampos = pg_num_fields($rsTabela);

  for($iCont=0; $iCont<$iContaCampos; $iCont++) {

    $sSqlDropIndex = " DROP INDEX ".pg_field_name($rsTabela, $iCont)."_in ;"; 

    $sSqlCreateIndex  = " CREATE INDEX ".pg_field_name($rsTabela, $iCont)."_in ";
    $sSqlCreateIndex .= "     ON {$sTabela} (".pg_field_name($rsTabela, $iCont)."); ";

    db_query($pConexao, $sSqlDropIndex, $sArquivoLog,false,true);
    db_query($pConexao, $sSqlCreateIndex, $sArquivoLog);

  }

}

/**
* Funcao para criar prepared statement(consulta preparada em memoria) tabela work
*
* @param  $pConexao                  resource Conexao da base onde estao as tabelas para criacao dos indices
* @param  $sTabela                   string   Tabela a ser criado a consulta
* @param  $sArquivoLog               string   Nome do arquivo para log 
*
* @return $lTabelaProcessada         boolean  Se a tabela foi processada com sucesso
*
* @author Robson Inacio 
*
*/

function dbPreparaConsulta($pConexao, $sTabelaWork, $sArquivoLog) {
 
  if(!is_resource($pConexao) or db_empty($sTabelaWork)) {
    return false;
  }
  
  if(!db_existe_relacao($pConexao, 'public', $sTabelaWork, "tabela")) {
    db_log("ERRO : Tabela {$sTabelaWork} nao encontrada na base de dados ", $sArquivoLog, 0, true, true);
    return false;
  } 

  $sSql     = "select * from {$sTabelaWork} limit 1";
  $rsTabelaWork = db_query($pConexao, $sSql);

  $iContaCampos = pg_num_fields($rsTabelaWork);

  for($iCont=0; $iCont<$iContaCampos; $iCont++) {

    $sNomeCampo = trim(pg_field_name($rsTabelaWork, $iCont));
    
    if ( substr($sNomeCampo,-4) == '_ori' ) {

      $sSqlDeallocate = "DEALLOCATE prepare select_{$sTabelaWork}"; // tira a consulta da memoria

      $sCampodst         = substr($sNomeCampo,0,(strlen($sNomeCampo)-4))."_dst";
      $sSqlCreatePrepare = "prepare select_{$sTabelaWork}(integer) as select {$sCampodst} from {$sTabelaWork} where {$sNomeCampo} = $1; ";
      
    }

    db_query( $pConexao, $sSqlDeallocate, $sArquivoLog, false, true );

    db_query( $pConexao, $sSqlCreatePrepare, $sArquivoLog );

  }

}


/**
* Funcao para criar prepared statement(para insert) tabela normal
*
* @param  $pConexao                  resource Conexao da base onde estao as tabela
* @param  $sTabela                   string   Tabela a ser criado a consulta
* @param  $sArquivoLog               string   Nome do arquivo para log 
*
* @return $lTabelaProcessada         boolean  Se a tabela foi processada com sucesso
*
* @author Robson Inacio 
*
*/

function dbPreparaInsert($pConexaoOrigem, $pConexaoDestino, $sTabela, $sArquivoLog) {
 
  if(!is_resource($pConexaoOrigem) or db_empty($sTabela)) {
    return false;
  }
  
  if(!db_existe_relacao($pConexaoOrigem, 'public', $sTabela, "tabela")) {
    db_log("ERRO : Tabela {$sTabela} nao encontrada na base de dados ", $sArquivoLog, 0, true, true);
    return false;
  } 

  $sSql     = "select * from {$sTabela} limit 1";
  $rsTabela = db_query($pConexaoOrigem, $sSql);

  $iContaCampos = pg_num_fields($rsTabela);

  $aCampos  = array();
  $aTipos   = array();
  $aValores = array();

  for($iCont=0; $iCont<$iContaCampos; $iCont++) {

    $sSqlFieldType  = " SELECT column_name as nome_campo,    ";
    $sSqlFieldType .= "        udt_name as tipo_campo        ";
    $sSqlFieldType .= "   FROM information_schema.columns    ";
    $sSqlFieldType .= "  WHERE table_schema = 'public'  ";
    $sSqlFieldType .= "    AND table_name   = '{$sTabela}'   ";
    $sSqlFieldType .= "    AND column_name  = '".trim(pg_field_name($rsTabela, $iCont))."' ";
    $rsFieldType    = db_query($pConexaoOrigem, $sSqlFieldType, $sArquivoLog);
    $oFieldType     = db_utils::fieldsmemory($rsFieldType, 0);
    
    array_push($aCampos,  $oFieldType->nome_campo);
    array_push($aTipos,   $oFieldType->tipo_campo);
    array_push($aValores, '$'.($iCont+1));

  }
  
  $sTipos   = implode(",",$aTipos);
  $sValores = implode(",",$aValores);
  $sCampos  = implode(",",$aCampos);

  $sSqlDeallocate = "DEALLOCATE prepare insert_{$sTabela}   ";

  $sSqlPrepareInsert  = " PREPARE insert_{$sTabela}({$sTipos}) as ";
  $sSqlPrepareInsert .= "         INSERT INTO {$sTabela}          ";
  $sSqlPrepareInsert .= "                     ({$sCampos})        "; 
  $sSqlPrepareInsert .= "              VALUES ({$sValores})       ";

  db_query( $pConexaoDestino, $sSqlDeallocate, $sArquivoLog, false, true );

  db_query( $pConexaoDestino, $sSqlPrepareInsert, $sArquivoLog );

}

/**
* Funcao para criar prepared statement(para insert) tabela normal
*
* @param  $pConexao                  resource Conexao da base onde estao as tabela
* @param  $sTabela                   string   Tabela a ser criado a consulta
* @param  $sArquivoLog               string   Nome do arquivo para log 
*
* @return $lTabelaProcessada         boolean  Se a tabela foi processada com sucesso
*
* @author Robson Inacio 
*
*/

function dbSelectTabela($pConexaoOrigem,$sTabela,$aCamposTrocar,$sArquivoInfos='',$sArquivoErros=''){

  if (!is_resource($pConexaoOrigem)) {
    db_log( "ERRO : Conexao de Origem Invalida", $sArquivoErros);
    return false;
  }              
  
  if ( stripslashes( $sValorCampo ) == "''" ){

    db_log( "ERRO : VALOR DO CAMPO ORIGEM NAO ENCONTRADO : TABELA WORK - {$aParametrosCampo[0]} CAMPO - {$aParametrosCampo[1]} VALOR - {$sValorCampo} \n",$sArquivoErros );
    exit;

  }

  $sSql     = "select * from {$sTabela} limit 1";
  $rsTabela = db_query($pConexaoOrigem, $sSql);

  $iContaCampos = pg_num_fields($rsTabela);

  $aCampos = array();
  $aJoins  = array();

  $lTemWork = false;

  // for para montar os campos
  for($iCont=0; $iCont<$iContaCampos; $iCont++) {

    $sSqlFieldType  = " SELECT column_name as nome_campo     ";
    $sSqlFieldType .= "   FROM information_schema.columns    ";
    $sSqlFieldType .= "  WHERE table_schema = 'public'  ";
    $sSqlFieldType .= "    AND table_name   = '{$sTabela}'   ";
    $sSqlFieldType .= "    AND column_name  = '".trim(pg_field_name($rsTabela, $iCont))."' ";
    $rsFieldType    = db_query($pConexaoOrigem, $sSqlFieldType, $sArquivoLog);
    $oFieldType     = db_utils::fieldsmemory($rsFieldType, 0);
    
    $lTemWork       =  array_key_exists($oFieldType->nome_campo,$aCamposTrocar);

    if ($lTemWork){

      $aParametrosCampo = $aCamposTrocar[$oFieldType->nome_campo];

      $sCampo = " {$aParametrosCampo[0]}_$iCont.{$aParametrosCampo[2]} as {$oFieldType->nome_campo} ";
      $sJoins = " left join {$aParametrosCampo[0]} {$aParametrosCampo[0]}_$iCont on {$aParametrosCampo[0]}_$iCont.{$aParametrosCampo[1]} = {$sTabela}.{$oFieldType->nome_campo} ";

      array_push( $aJoins, $sJoins );
      
    }else{   
      
      $sCampo = $oFieldType->nome_campo;

    }
    
    array_push($aCampos,  $sCampo);

  }

  $sCampos  = implode(",",$aCampos);

  if (count($aJoins) > 0 ){
    $sJoins   = implode(" ",$aJoins);
  }

  $sSql     = " select $sCampos from {$sTabela} {$sJoins} ";

  //echo $sSql." \n ";
  
  $rsTabela = db_query($pConexaoOrigem, $sSql, null);

  if (pg_num_rows($rsTabela) == 0 || $rsTabela == false ) {
    
    db_log("Problema com a tabela {$aParametrosCampo[0]} sql : {$sqlTrocaValores} ", $sArquivoInfos,2);
    return false;
  
  }
  
  return $rsTabela;
     

}

?>