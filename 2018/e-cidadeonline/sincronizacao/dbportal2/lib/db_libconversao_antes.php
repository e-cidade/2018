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
    $result6 = db_query($sql6);
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
    $result6 = db_query($sql6);
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
         $res_db_usuarios = db_query( $conn1, $str_sql ) or die ( "FALHA: $str_sql \n" );
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
function db_query($pConexao, $sSql, $sArquivoLog="", $lErroDie=true) {

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

  if(!$rsRetorno) {
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
  //

  db_query($pConexao, "ANALYZE {$sTabela};", $sArquivoLog);

  $sSql = "select reltuples from pg_class where relname = '{$sTabela}' and relkind = 'r'";

  return pg_result(db_query($pConexao, $sSql, $sArquivoLog), 0, "reltuples");

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
    return false;
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
  


  db_log($sSqlCreateTable);
  db_query($pConexao, $sSqlCreateTable);
  db_log($sSqlCreateIndexOri);
  db_query($pConexao, $sSqlCreateIndexOri);
  db_log($sSqlCreateIndexDst);
  db_query($pConexao, $sSqlCreateIndexDst);
}

function db_gera_work_conversao($pConexaoOri,$pConexaoDes, $sTabela, $sTabelaWork="", $aCamposChave, $sWhere="", $iTipoforma=1, $iSequencia) {
  /* 
  $pConexaoOri  - Conexão de Origem
  $pConexaoDes  - Conexãoi de destino
  $sTabela      - Nome da Tabela Envolvida
  $sTabelaWork  - Nome da Tabela Auxiliar (se não informada o padrão é work_nometabela)
  $sWhere       - Copndição para o Where do Select que busca os dados.
  $aCamposChave - Array com o Nome dos Campos da Chave envolvida
  $iTipoforma   - Forma de inclusão no campo destino... 1- soma 10000 , 2- pega sequencial
  $iSequencia   - se o $iTipoforma for =2... precisa indicar a sequencia da tabela.
   */
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
  // pega campo do array e concatena com virgula para usar no select
  for($iCont=0; $iCont<count($aCamposChave); $iCont++) {
    $sCampo .= $sVirgula.$aCamposChave[$iCont];
    $sVirgula = ", ";
  }
  if($sWhere!= ""){
     $sWhere1 = "Where $sWhere";
  }
  $sSqlDados = "select $sCampo from $sTabela $sWhere1 ";
  $rsDados = db_query($pConexaoOri, $sSqlDados, $sArquivoLog);
  $iNumRowsDados = db_numrows($rsDados);
  if($iNumRowsDados>0){
    //verifica campos da tabelawork para o insert
    $sSqlCampo = "select * from {$sTabelaWork} limit 1";
    $rsCampo = db_query($pConexaoOri, $sSqlCampo, $sArquivoLog);
    $sSqlInsert = "INSERT INTO {$sTabelaWork} (";
    $sVirgula = "";
    
    // pega nome dos campo da tabelaorigem para usar no insert
    $iContaCamposOri = pg_num_fields($rsDados);
    for($iCampo=0; $iCampo<$iContaCamposOri; $iCampo++) {
      $sNomeCampoOri[$iCampo]  = pg_field_name($rsDados, $iCampo);
    }
    // pega campo da tabelawork e concatena com virgula para usar no insert
    $iContaCampos  = pg_num_fields($rsCampo);
    for($iCont=0; $iCont<$iContaCampos; $iCont++) {
      $sNomeCampo  = pg_field_name($rsCampo, $iCont);
      $sSqlInsert .= $sVirgula . "\"{$sNomeCampo}\"";
      $sVirgula    = ", ";
    }
    // for no numero de dados encontrados na tabela origem
    for($iDados=0; $iDados<$iNumRowsDados; $iDados++) {
      $nPercentual = round((($iDados+1)/$iNumRowsDados) * 100, 2);
      db_log("\rProcessando {$sTabela} {$nPercentual}%...", null, 1, false, false);
      $oDados       = db_utils::fieldsmemory($rsDados,$iDados);
      $sVirgula     = " ";
      $sDadosInclui = "";
      // for nos campos origem
      for($iCampo2=0; $iCampo2<$iContaCamposOri; $iCampo2++) {
        if($iTipoforma==1){
          // Tipo = 1 soma 10000
          $sDadoOrig     = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadoDest     = $oDados->$sNomeCampoOri[$iCampo2] + 10000 ;
          $sDadosInclui .=$sVirgula.$sDadoOrig.",".$sDadoDest;
          $sVirgula = ", ";
        }else{
          // Tipo = 2 pega o sequencial do destino
          $sSqlSeq         = "select nextval('$iSequencia')";
          $rsSeq           = db_query($pConexaoDes, $sSqlSeq, $sArquivoLog);
          $iContaCamposSeq = pg_num_fields($rsSeq);
          $sNomeCampoSeq   = pg_field_name($rsSeq, 0);
          $oSeq            = db_utils::fieldsmemory($rsSeq,$iCampo2);
          $sDadoOrig       = $oDados->$sNomeCampoOri[$iCampo2] ;
          $sDadoDest       =  $oSeq->$sNomeCampoSeq;
          $sDadosInclui .=$sVirgula.$sDadoOrig.",".$sDadoDest;
          $sVirgula        = ", ";
    
        }
        $sIncluiDados = "$sSqlInsert ) VALUES ({$sDadosInclui})";
      
      
      }
      db_query($pConexaoOri, $sIncluiDados, $sArquivoLog);
      //db_log(" inclui =  {$sIncluiDados}", $sArquivoLog, 0, true, true);
      
      
    }
  }
}

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

  $sqlTrocaValores  = " select {$aParametrosCampo[2]}                  ";
  $sqlTrocaValores .= "   from {$aParametrosCampo[0]}                  ";
  $sqlTrocaValores .= "  where {$aParametrosCampo[1]} = {$sValorCampo} ";

  $rsTrocaValores   = db_query($pConexaoOrigem, $sqlTrocaValores, null);

  if (pg_num_rows($rsTrocaValores) == 0 || $rsTrocaValores == false ) {

    return false;

//    arrumar depois
//    db_log("Problema com a tabela {$aParametrosCampo[0]} sql : {$sqlTrocaValores} ", $sArquivoLog);
//    exit;
  
  }
  
  $oTrocaValores    = db_utils::fieldsmemory($rsTrocaValores, 0);
     
  $sValorCampo      = $oTrocaValores->$aParametrosCampo[2];

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

  $iOffSet = 0;
  $iNumRowsTabela = db_numrows_table($pConexaoOrigem, $sTabela, $sArquivoInfos);

  $iNumRowsTabela = 100; 

  db_log("\n",null,1,false,false );

  while(true) {

    $sMsgProgresso = "\r ".str_pad("Processando tabela ".strtoupper($sTabela),50," ", STR_PAD_RIGHT ). str_pad( round( (($iOffSet/$iNumRowsTabela)*100),2),3," ",STR_PAD_RIGHT)."%";
    db_log($sMsgProgresso,null,1,false,false );

    if ( $iOffSet == 100 ) {

      break;
      
    }

    $sSql = "select * from {$sTabela} offset {$iOffSet} limit 1";

    unset($rsTabela);

    $rsTabela = db_query($pConexaoOrigem, $sSql, $sArquivoErros);

    if(db_numrows($rsTabela)==0) {
      break;
    }

    $sSqlInsert = "";
    $sSqlCampos = "";
    $sSqlValues = "";

    $sVirgula = "";

    $iContaCampos = pg_num_fields($rsTabela);

    for($iCont=0; $iCont<$iContaCampos; $iCont++) {

      $sTipoCampo   = pg_field_type($rsTabela, $iCont);
      $sValorCampo  = stripslashes(pg_result($rsTabela, $iCont));

      if( ( $sTipoCampo=="date" || $sTipoCampo=="oid" ) && empty($sValorCampo)) {

        $sValor = "null";

      } else if ( ( $sTipoCampo=="int2"    ||
                    $sTipoCampo=="int4"    ||
                    $sTipoCampo=="int8"    ||
                    $sTipoCampo=="numeric" ||
                    $sTipoCampo=="float4"  ||
                    $sTipoCampo=="float8" )  && empty($sValorCampo) ) {

        $sValor = 0;

      } else {

        $sValor = "'".addslashes($sValorCampo)."'";

      }

      // pesquisa no array pelo campo, para saber se o mesmo tem uma work
      $bTemWork =  array_key_exists(pg_field_name($rsTabela, $iCont),$aCamposTrocar);
    
      if ( !empty($sFuncaoCallBack) && $bTemWork == true ) {

        if( function_exists($sFuncaoCallBack) ) {

          call_user_func( $sFuncaoCallBack, $pConexaoOrigem, &$sValor, $aCamposTrocar[pg_field_name($rsTabela, $iCont)] );

        }

      } 
            
      $aCampos[trim(pg_field_name($rsTabela, $iCont))] = $sValor; 

    }
    
    $sSqlCampos = implode(",",array_keys($aCampos));
    $sSqlValues = implode(",",$aCampos);

    $sSqlInsert = "INSERT INTO {$sTabela} ({$sSqlCampos}) VALUES ({$sSqlValues});";

    db_query( $pConexaoDestino, $sSqlInsert, $sArquivoErros );

    $iOffSet++;

  }

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

?>