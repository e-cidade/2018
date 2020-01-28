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

// Desabilita Compatibilidade com Zend Engine 1
ini_set("zend.ze1_compatibility_mode", false);

// Seta Nome do Script
$sNomeScript = basename(__FILE__);
// Inicio do Script
include("lib/db_inicio_script.php");
// Conexao com bases de Dados
include("lib/db_conecta.php");

// Arquivo para log de erros e possiveis anomalias durante o processamento
$sArqLogErros = 'log/03_geraBaseAux_logErros.txt';

// Inicia Sessao no Banco de Dados
db_query($pConexaoPrefeitura,"select fc_startsession()");    
db_query($pConexaoPrefeitura,"select fc_putsession('DB_instit',(select codigo from db_config where prefeitura is true))");

//
// Ação a ser executada dados = gerar dados ou schema = criar schema para gerar pg_dump
//
$sAction = $argv[1];
if (empty($sAction)) {
  die("\nFalta parametro indicando a acao a ser executada \n");  
}
// Arquivo XML com mapeamento das tabelas e campos
$sXmlFile = $argv[2];

// Nome do Schema que sera criado com as tabelas do arquivo xml
$sSchemaNameDestino = $argv[3];

if ( empty($sXmlFile) ) {
  $sXmlFile = "xml/schema.dbpref.xml";
}

if ( empty($sArqLogErros) ) {
  $sSchemaNameDestino = "dbpref_".date('Ymd');
}

// Verifica se existe o arquivo com mapeamento das tabelas 
if ( file_exists($sXmlFile) ) {
  $xml = new DOMdocument;
  $xml->load($sXmlFile);
  if( $xml->hasChildNodes() ) {

    if ($sAction == 'schema') {
      geraSchemaAux($xml,$pConexaoPrefeitura,$sSchemaNameDestino);
    } else if ($sAction == 'gerar') {
      geraDados($xml,$pConexaoPrefeitura,$sSchemaNameDestino);
    }

  } else {
    db_log('ERRO : Arquivo invalido (provavelmente nao possui nenhum nó de configuração de tabela).');
    exit;
  }
} else {
  db_log("ERRO : Arquivo nao encontrado no diretório({$sXmlFile}) especificado.");
  exit;
}

// Final do Script
include("lib/db_final_script.php");


//
//  F U N C O E S
// 

function geraSchemaAux($xmlNode, $pConexaoPrefeitura, $sSchemaNameDestino) {

  foreach($xmlNode->childNodes as $child) {

    // se o tipo de no for texto passa para o proximo
    if ( $child->nodeType == 3 ) {
      continue;      
    }

    $sSqlCreateSchema = "CREATE SCHEMA {$sSchemaNameDestino}";
    db_query($pConexaoPrefeitura,$sSqlCreateSchema);

    $tabelas = $child->getElementsByTagName("tabela");
    //
    // Percorrendo as tabelas 
    //
    foreach ( $tabelas as $noTabela ) {

      $sCondicao = $noTabela->getAttribute("condicao");      
      $sWhere    = "";
      if ($sCondicao != "" ) {
        $sWhere = " where {$sCondicao} ";
      }

      $sTableName      = $noTabela->getAttribute("name");
      $sSqlCreateTable = "CREATE TABLE {$sSchemaNameDestino}.{$sTableName} as select * from {$sTableName} {$sWhere}";

      db_log("Criando tabela {$sSchemaNameDestino}.{$sTableName}");
      db_query($pConexaoPrefeitura,$sSqlCreateTable);

    }

  }

}

function geraDados($xmlNode, $pConexaoPrefeitura, $sSchemaOrigem) {

  foreach($xmlNode->childNodes as $child) {

    // se o tipo de no for texto passa para o proximo
    if ( $child->nodeType == 3 ) {
      continue;      
    }

    $tabelas = $child->getElementsByTagName("tabela");
    //
    // Percorrendo as tabelas 
    //
    $aTabelasProcessadas = array();
    $aTabelas            = array();
    foreach ( $tabelas as $noTabela ) {

      $sCondicao  = $noTabela->getAttribute("condicao");
      $sTableName = $noTabela->getAttribute("name");
      $lDeletar   = ($noTabela->getAttribute("delete")=='true'?true:false);
      $sWhere     = "";
       
      /*$lTemDepencias = getDependencias($pConexaoPrefeitura,$sTableName,&$aTabelasProcessadas);
      if (!$lTemDepencias) {
        $aTabelasProcessadas[] = $sTableName;
      }
      $aTabelas[] = $sTableName;*/

      $aTabelasProcessadas[] = $sTableName;
      if ($lDeletar) {
        $aTabelasDeletar = $sTableName;
      }

    }

    $aTabelasInserir = array_unique( array_merge($aTabelasProcessadas,$aTabelas) ) ;
    $aTabelasDeletar = array_reverse( $aTabelasInserir );

    db_query($pConexaoPrefeitura,"BEGIN");    
    //
    // Deletando os registros
    //
    foreach ( $aTabelasDeletar as $sTabela ) {

      if(trim($sTabela) == ""){
        continue;        
      }

      alterTriggers($pConexaoPrefeitura,$sTabela,false); 
      $sSqlDelete = " delete from {$sTabela} ";
      db_log("Deletando dados tabela {$sTabela}");
      db_query($pConexaoPrefeitura,$sSqlDelete);

    }

    //
    // Inserindo os registros apartir do schema atualizado
    //
    foreach ( $aTabelasInserir as $sTabela ) {

      if(trim($sTabela) == ""){
        continue;        
      }

      db_log("Inserindo dados tabela {$sTabela} buscando da tabela {$sSchemaOrigem}.{$sTabela}");
      insereDados($pConexaoPrefeitura,$sTabela,$sSchemaOrigem,"" );

    }

    //
    // Habilitando as triggers
    //
    foreach ( $aTabelasInserir as $sTabela ) {

      if(trim($sTabela) == ""){
        continue;        
      }
      db_log("Habilitando as triggers para tabela public.{$sTabela}");
      alterTriggers($pConexaoPrefeitura,$sTabela,true); 

    }

    db_query($pConexaoPrefeitura,"COMMIT");

  }

}

function getDependencias($pConexaoPrefeitura,$sTableName,$aTabelasProcessadas) {

  $aDependencias     = array();
  $sSqlDependencias  = " select trim(tabela_pai.relname) as tabela                                        ";
  $sSqlDependencias .= "   from pg_constraint                                                             ";
  $sSqlDependencias .= "        left join pg_class tabela     on tabela.oid     = pg_constraint.conrelid  ";
  $sSqlDependencias .= "        left join pg_class tabela_pai on tabela_pai.oid = pg_constraint.confrelid ";
  $sSqlDependencias .= "  where trim(tabela.relname) = '{$sTableName}'                                    ";
  $sSqlDependencias .= "    and pg_constraint.contype = 'f'                                               ";
  $rsDependencias    = db_query($pConexaoPrefeitura,$sSqlDependencias);
  $iNumrows          = pg_num_rows($rsDependencias);

  for ($i = 0; $i < $iNumrows; $i++) {

    $oDependencia = db_utils::fieldsMemory($rsDependencias,$i);
    if (!in_array($oDependencia->tabela,$aTabelasProcessadas)) {
      $aDependencias[] = $oDependencia->tabela;    
      getDependencias($pConexaoPrefeitura,$oDependencia->tabela,&$aTabelasProcessadas);
    }

  }

  if (count($aDependencias) > 0) {
    $aTabelasProcessadas = array_merge($aTabelasProcessadas,$aDependencias);
    return true;
  }

  return false;

}

function alterTriggers($pConexaoPrefeitura,$sTableName,$enable) {
  
  $aTabelasDesabilitadas = array();
  
  if ($enable) {
    $sEnable = "enable";
  }else{
    $sEnable = "disable";
  }
    
  $sSqlTrigger  = "ALTER TABLE {$sTableName} {$sEnable} TRIGGER ALL";
  db_query($pConexaoPrefeitura,$sSqlTrigger);

  $aTabelasDesabilitadas[] = $sTableName;

  $sSqlDependencias  = "  select trim(tabela.relname) as tabela                                            ";
  $sSqlDependencias .= "    from pg_constraint                                                             ";
  $sSqlDependencias .= "         left join pg_class tabela     on tabela.oid     = pg_constraint.conrelid  ";
  $sSqlDependencias .= "         left join pg_class tabela_pai on tabela_pai.oid = pg_constraint.confrelid ";
  $sSqlDependencias .= "   where trim(tabela_pai.relname) = '{$sTableName}'                                ";
  $sSqlDependencias .= "     and pg_constraint.contype = 'f'                                               ";
  $rsDependencias    = db_query($pConexaoPrefeitura,$sSqlDependencias);
  $iNumrows          = pg_num_rows($rsDependencias);

  for ($i = 0; $i < $iNumrows; $i++) {

    $oDependencia = db_utils::fieldsMemory($rsDependencias,$i); 
    $sSqlTrigger  = "ALTER TABLE {$oDependencia->tabela} {$sEnable} TRIGGER ALL";
    db_query($pConexaoPrefeitura,$sSqlTrigger);  
    $aTabelasDesabilitadas[] = $oDependencia->tabela;

  }
  
}

function insereDados($pConexaoPrefeitura, $sTableName, $sSchemaOrigem, $sCondicao ) {

  if ($sCondicao != "" ) {
    $sWhere = " where {$sCondicao} ";
  }

  // Verificar Troca CGM
  if(trim(strtolower($sTableName)) == "recibopaga") {
    $sUpdate  = "UPDATE {$sSchemaOrigem}.{$sTableName} ";
    $sUpdate .= "   SET k00_numcgm = fc_cgmcorreto(k00_numcgm) ";
  }

  $sSqlInsert = "INSERT INTO {$sTableName} select * from {$sSchemaOrigem}.{$sTableName} {$sWhere}";

//db_log("{$sSqlInsert}");

  $rsInsert   = db_query($pConexaoPrefeitura,$sSqlInsert);
  if (pg_affected_rows($rsInsert) == 0 ) {
    db_log("NAO GEROU REGISTROS PARA A TABELA {$sTableName}");
  }

}

?>
