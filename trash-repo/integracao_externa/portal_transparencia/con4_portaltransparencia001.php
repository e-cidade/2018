<?php
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


/**
 * Alterado memória do PHP on the fly, para não estourar a carga dos dados do servidor
 */
ini_set("memory_limit", '-1');

$_SERVER['DB_acessado']   = 1;
$_SERVER['DB_datausu']    = time();
$_SERVER['DB_anousu']     = date('Y',time());
$_SERVER['DB_id_usuario'] = 1;
$_SERVER['DB_login']      = '';


if ($argc > 1 && $argv[1] == "debug") {
    $_SERVER['DB_traceLogAcount'] = true;
}

/**
 *  Número de bases antigas que o script irá manter automáticamente
 */
define('NUMERO_BASES_ANTIGAS', 3);

/**
 *  Nome do Schema gerado pelo script
 */
define('SSCHEMA', 'transparencia');
define('SBKPSCHEMA', "bpk_transparencia_".date("Ymd_His"));
/**
 *  A variável iParamLog define o tipo de log que deve ser gerado :
 *  0 - Imprime log na tela e no arquivo
 *  1 - Imprime log somente da tela
 *  2 - Imprime log somente no arquivo
 */
$iParamLog = 0;

/**
 * Exericio corrente
 */
$iExercicio = date('Y',time());
if ( $iParamLog == 1 ) {
    $sArquivoLog = null;
} else {
    $sArquivoLog = "log/processamento_transparencia".date("Ymd_His").".log";
}

require_once('libs/dbportal.constants.php');
require_once('libs/db_conecta.php');
require_once('libs/databaseVersioning.php');
require_once(DB_LIBS ."libs/db_stdlib.php");
require_once(DB_LIBS ."libs/db_utils.php");
require_once(DB_LIBS ."libs/db_sql.php");
require_once(DB_MODEL."model/dataManager.php");
require_once('util.php');
require_once('sql_queries.php');

/**
 *  Exercício que será utilizado como base para migração, ou seja serão consultados apenas
 *  dados apartir do exercício informado.
 */
$iExercicioBase = EXERCICIO_BASE;

/**
 * Adicionado verificação para configurar a constant caso o cliente use PCASP
 */
$lUsarPcasp         = false;
$sSqlConParametro   = " select c90_usapcasp ";
$sSqlConParametro  .= "   from contabilidade.conparametro ";
$rsConParametro     = consultaBD($sSqlConParametro);
$lParametroUsaPCASP = pg_result($rsConParametro, 0, 0);
$iAnoServidor       = date("Y");

$iAnoImplantacaoPCASP = 2013;

if ($lParametroUsaPCASP == 't') {

    $rsTipoInstituicao    = consultaBD($connOrigem, sql_tipo_instituicao());
    if (!is_bool($rsTipoInstituicao) && pg_num_rows($rsTipoInstituicao) == 1 && isset($iAnoServidor)) {

        $iTipoInstituicao = pg_result($rsTipoInstituicao, 0, "db21_tipoinstit");
        if ($iTipoInstituicao == 101 && $iAnoServidor >= 2012)/* { */

            $lUsarPcasp = true;

        else {

            /**
             * Ano de implacantacao do PCASP
             * - busca ano do arquivo config/pcasp.txt
             * - caso arquivo nao exista sera 2013
             */
            if ( file_exists(DB_LIBS . 'config/pcasp.txt') ) 
                $iAnoImplantacaoPCASP = trim(file_get_contents(DB_LIBS . 'config/pcasp.txt'));
            /**
             * Usar PCASP:
             * - Ano do servidor e ano de implantação do PCASP tem que ser maior ou igual a 2013
             */
            if ($iAnoServidor >= $iAnoImplantacaoPCASP && $iAnoImplantacaoPCASP >= 2013) 
                $lUsarPcasp = true;
        }
    }
    }

    define("USE_PCASP", $lUsarPcasp);
    define("ANO_IMPLANTACAO_PCASP", $iAnoImplantacaoPCASP); 
    define("ANO_ANTERIOR_IMPLANTACAO_PCASP", ANO_IMPLANTACAO_PCASP - 1); 

    unset($lUsarPcasp);
    unset($iAnoImplantacaoPCASP);

    if ( isset($argv[1])) {

        db_putsession("DB_traceLog", true);
        db_putsession("DB_login", "dbseller");
    }

    /**
     *  Caso o parâmetro seja passado como true então serão processados
     *  todas empresas cadastradas, caso contrário serão processadas apenas
     *  as empresas cadastradas ou alteradas no dia
     */

    $lErro      = false;
    $dtDataHoje = date("Y-m-d");
    $iAnoUsu    = date("Y");
    $sHoraHoje  = date('H:i');

    /**
     *  Inicia sessão e transação
     */
    db_query($connOrigem ,"select fc_startsession();");
    db_query($connDestino,"BEGIN;");

    /**
     *  Verifica se existem atualizações de base de dados
     *  e as aplica na mesma
     */


    try {


        // RENOMEIA DE SCHEMAS ANTIGOS ************************************************************************************//

        $sSqlConsultaSchemasAtual = "select distinct schema_name
            from information_schema.schemata
            where schema_name = '". SSCHEMA ."'";
        $rsSchemasAtual      = consultaBD($connDestino,$sSqlConsultaSchemasAtual);
        $iLinhasSchemasAtual = pg_num_rows($rsSchemasAtual);

        if ( $iLinhasSchemasAtual > 0 ) {

            $sSqlRenomeiaSchema = " ALTER SCHEMA " . SSCHEMA . " RENAME TO ". SBKPSCHEMA . " ";

            if ( !db_query($connDestino,$sSqlRenomeiaSchema))
                throw new Exception("ERRO-0: Erro ao renomear schema !".$sSqlRenomeiaSchema);
            
        }

        // CRIA NOVO SCHEMA ***********************************************************************************************//


        $sSqlCriaSchema = "CREATE SCHEMA " .  SSCHEMA . " ";

        if ( !db_query($connDestino,$sSqlCriaSchema) ) throw new Exception("Falha ao criar schema ". SSCHEMA . "!");
        


        // ****************************************************************************************************************//

        $sSqlAlteraSchemaAtual = "ALTER DATABASE \"".$ConfigConexaoDestino["dbname"]."\" SET search_path TO ". SSCHEMA ." ";

        if ( !db_query($connDestino,$sSqlAlteraSchemaAtual)) 
            throw new Exception("Falha ao alterar schema atual para ". SSCHEMA . "!");
        

        $sSqlDefineSchemaAtual = "SET search_path TO ". SSCHEMA . " ";

        if ( !db_query($connDestino,$sSqlDefineSchemaAtual) ) 
            throw new Exception("Falha ao definir schema atual para ". SSCHEMA . " !");
        


        $rsUpgradeDatabase = upgradeDatabase($connDestino,'.', SSCHEMA);

        if (!$rsUpgradeDatabase) 
            throw new Exception("Falha ao atualizar base de dados!");
        


        /**
         *  O script abaixo corrige possíveis erros de base na tabela conplano, sendo elas podendo ser originárias
         *  da tabela orcdotacao ou orcreceita
         */

        /**
         *  Consulta os registros da orcdotacao com possíveis erros de base
         */
        corrigeConplano($connOrigem, "orcdotacao", $iExercicio, ANO_IMPLANTACAO_PCASP);

        /**
         *  Consulta os registros da orcreceita com possíveis erros de base
         */
        corrigeConplano($connOrigem, "orcreceita", $iExercicio, ANO_IMPLANTACAO_PCASP);


        $oTBInstituicoes               = new tableDataManager($connDestino, 'instituicoes'                , 'id', true, 500);
        $oTBOrgaos                     = new tableDataManager($connDestino, 'orgaos'                      , 'id', true, 500);
        $oTBUnidades                   = new tableDataManager($connDestino, 'unidades'                    , 'id', true, 500);
        $oTBProjetos                   = new tableDataManager($connDestino, 'projetos'                    , 'id', true, 500);
        $oTBFuncoes                    = new tableDataManager($connDestino, 'funcoes'                     , 'id', true, 500);
        $oTBSubFuncoes                 = new tableDataManager($connDestino, 'subfuncoes'                  , 'id', true, 500);
        $oTBProgramas                  = new tableDataManager($connDestino, 'programas'                   , 'id', true, 500);
        $oTBRecursos                   = new tableDataManager($connDestino, 'recursos'                    , 'id', true, 500);
        $oTBPlanoContas                = new tableDataManager($connDestino, 'planocontas'                 , 'id', true, 500);
        $oTBReceitas                   = new tableDataManager($connDestino, 'receitas'                    , 'id', true, 500);
        $oTBReceitasMovimentacoes      = new tableDataManager($connDestino, 'receitas_movimentacoes'      , 'id', true, 500);
        $oTBDotacoes                   = new tableDataManager($connDestino, 'dotacoes'                    , 'id', true, 500);
        $oTBPessoas                    = new tableDataManager($connDestino, 'pessoas'                     , 'id', true, 500);
        $oTBEmpenhos                   = new tableDataManager($connDestino, 'empenhos'                    , 'id', true, 500);
        $oTBEmpenhosItens              = new tableDataManager($connDestino, 'empenhos_itens'              , 'id', true, 500);
        $oTBEmpenhosProcessos          = new tableDataManager($connDestino, 'empenhos_processos'          , 'id', true, 500);
        $oTBEmpenhosMovimentacoes      = new tableDataManager($connDestino, 'empenhos_movimentacoes'      , 'id', true, 500);
        $oTBEmpenhosMovimentacoesTipos = new tableDataManager($connDestino, 'empenhos_movimentacoes_tipos', 'id', true, 500);
        $oTBGlossarios                 = new tableDataManager($connDestino, 'glossarios'                  , 'id', true, 500);
        $oTBGlossariosTipos            = new tableDataManager($connDestino, 'glossarios_tipos'            , 'id', true, 500);
        $oTBServidores                 = new tableDataManager($connDestino, 'servidores'                  , ''  , true, 500);
        $oTBMovimentacoesServidores    = new tableDataManager($connDestino, 'servidor_movimentacoes'      , 'id', true, 500);
        $oTBFolhaPagamento             = new tableDataManager($connDestino, 'folha_pagamento'             , 'id', true, 500);
        $oTBAssentamentos              = new tableDataManager($connDestino, 'assentamentos'               , 'id', true, 500);


        /**
         *  Arrays utiliz ados  para  referenciar os respectivos códigos de origem aos IDs novos gerados.
         */
        $aListaInstit                  = array();
        $aListaOrgao                   = array();
        $aListaUnidade                 = array();
        $aListaProjeto                 = array();
        $aListaFuncao                  = array();
        $aListaSubFuncao               = array();
        $aListaPrograma                = array();
        $aListaRecurso                 = array();
        $aListaPlanoConta              = array();
        $aListaReceita                 = array();
        $aListaDotacao                 = array();
        $aListaEmpenhoMovimentacaoTipo = array();
        $aMatrizMovimentacaoServidor   = array();

        //Tabela de Importacoes
        configuraTabelaImportacao($sArquivoLog, $iParamLog, $dtDataHoje, $sHoraHoje, $connDestino);

        // INSTITUIÇÕES **************************************************************************************************//

        db_logTitulo(" IMPORTA INSTITUIÇÕES",$sArquivoLog,$iParamLog);
        $sSqlInstit  = consultaInstituicoes();
        $rsInstit     = consultaBD($connOrigem,$sSqlInstit);
        $iRowsInstit = pg_num_rows($rsInstit);

        if ( $iRowsInstit ==  0 ) throw new Exception('Nenhuma instituição encontrada!');

        db_logNumReg($iRowsInstit,$sArquivoLog,$iParamLog);
        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsInstit; $iInd++ ) {
            $oInstit = db_utils::fieldsMemory($rsInstit,$iInd);
            logProcessamento($iInd,$iRowsInstit,$iParamLog);
            $oTBInstituicoes->setByLineOfDBUtils($oInstit);
            insereRegistros($oTBInstituicoes);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBInstituicoes, PERSISTE);

        /**
         *  É consultado as instituições cadastradas na base de destino para que seja populado o array $aListaInstit
         *  com as instituições cadastradas sendo a variável indexada pelo código da instituição da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da instituição de origem.
         */
        $sSqlListaInstitDestino  = buscaTodosOsObjetosDaTabela("instituicoes");
        $rsListaInstitDestino    = consultaBD($connDestino,$sSqlListaInstitDestino);
        $iRowsListaInstitDestino = pg_num_rows($rsListaInstitDestino);

        if ( $iRowsListaInstitDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for($iInd=0; $iInd < $iRowsListaInstitDestino; $iInd++ ) {
            $oInstitDestino = db_utils::fieldsMemory($rsListaInstitDestino,$iInd);
            $aListaInstit[$oInstitDestino->codinstit] = $oInstitDestino->id;
        }
        // FIM INSTITUIÇÕES ***********************************************************************************************//

        // ORGÃOS *********************************************************************************************************//
        db_logTitulo(" IMPORTA ORGÃOS",$sArquivoLog,$iParamLog);

        $sSqlOrgao  = consultaOrgaos();
        $rsOrgao    = consultaBD($connOrigem,$sSqlOrgao);
        $iRowsOrgao = pg_num_rows($rsOrgao);

        if ( $iRowsOrgao ==  0 ) throw new Exception('Nenhum orgão encontrado!');

        db_logNumReg($iRowsOrgao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsOrgao; $iInd++ ) {
            $oOrgao = db_utils::fieldsMemory($rsOrgao,$iInd);
            logProcessamento($iInd,$iRowsOrgao,$iParamLog);
            $oOrgao->instituicao_id = $aListaInstit[$oOrgao->codinstit];
            $oTBOrgaos->setByLineOfDBUtils($oOrgao);
            insereRegistros($oTBOrgaos);

        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBOrgaos, PERSISTE);

        /**
         *  É consultado os orgãos cadastrados na base de destino para que seja populado o array $aListaOrgao
         *  com os orgãos cadastrados sendo a variável indexada pelo código do orgão da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código do orgão de origem.
         */
        $sSqlListaOrgaoDestino = buscaTodosOsObjetosDaTabela("orgaos");
        $rsListaOrgaoDestino    = consultaBD($connDestino,$sSqlListaOrgaoDestino);
        $iRowsListaOrgaoDestino = pg_num_rows($rsListaOrgaoDestino);

        if ( $iRowsListaOrgaoDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaOrgaoDestino; $iInd++ ) {
            $oOrgaoDestino = db_utils::fieldsMemory($rsListaOrgaoDestino,$iInd);
            $aListaOrgao[$oOrgaoDestino->codorgao][$oOrgaoDestino->exercicio] = $oOrgaoDestino->id;
        }
        // FIM ORGÃOS *****************************************************************************************************//

        // UNIDADES *******************************************************************************************************//


        db_logTitulo(" IMPORTA UNIDADES",$sArquivoLog,$iParamLog);

        $sSqlUnidade  = consultaUnidades();
        $rsUnidade    = consultaBD($connOrigem,$sSqlUnidade);
        $iRowsUnidade = pg_num_rows($rsUnidade);

        if ( $iRowsUnidade ==  0 ) throw new Exception('Nenhuma unidade encontrada!');

        db_logNumReg($iRowsUnidade,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsUnidade; $iInd++ ) {
            $oUnidade = db_utils::fieldsMemory($rsUnidade,$iInd);
            logProcessamento($iInd,$iRowsUnidade,$iParamLog);
            $oUnidade->instituicao_id = $aListaInstit[$oUnidade->codinstit];
            $oUnidade->orgao_id       = $aListaOrgao[$oUnidade->codorgao][$oUnidade->exercicio];
            $oTBUnidades->setByLineOfDBUtils($oUnidade);
            insereRegistros($oTBUnidades);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBUnidades, PERSISTE);

        /**
         *  É consultado as unidades cadastradas na base de destino para que seja populado o array $aListaUnidade
         *  com as unidades cadastradas sendo a variável indexada pelo código da unidade da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da unidade de origem.
         */
        $sSqlListaUnidadeDestino  = buscaTodosOsObjetosDaTabela("unidades");
        $rsListaUnidadeDestino    = consultaBD($connDestino,$sSqlListaUnidadeDestino);
        $iRowsListaUnidadeDestino = pg_num_rows($rsListaUnidadeDestino);

        if ( $iRowsListaUnidadeDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaUnidadeDestino; $iInd++ ){
            $oUnidadeDestino = db_utils::fieldsMemory($rsListaUnidadeDestino,$iInd);
            $aListaUnidade[$oUnidadeDestino->codunidade][$oUnidadeDestino->exercicio] = $oUnidadeDestino->id;
        }

        // FIM UNIDADES ***************************************************************************************************//

        // PROJETOS *******************************************************************************************************//

        db_logTitulo(" IMPORTA PROJETOS",$sArquivoLog,$iParamLog);

        $sSqlProjeto  = consultaProjetos();
        $rsProjeto    = consultaBD($connOrigem,$sSqlProjeto);
        $iRowsProjeto = pg_num_rows($rsProjeto);

        if ( $iRowsProjeto ==  0 ) throw new Exception('Nenhum projeto encontrado!');

        db_logNumReg($iRowsProjeto,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsProjeto; $iInd++ ) {
            $oProjeto = db_utils::fieldsMemory($rsProjeto,$iInd);
            logProcessamento($iInd,$iRowsProjeto,$iParamLog);
            $oProjeto->instituicao_id = $aListaInstit[$oProjeto->codinstit];
            $oTBProjetos->setByLineOfDBUtils($oProjeto);
            insereRegistros($oTBProjetos);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBProjetos, PERSISTE);

        /**
         *  É consultado os projetos cadastrados na base de destino para que seja populado o array $aListaProjeto
         *  com os projetos cadastrados sendo a variável indexada pelo código do projeto da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código do projeto de origem.
         */
        $sSqlListaProjetoDestino  = buscaTodosOsObjetosDaTabela("projetos");
        $rsListaProjetoDestino    = consultaBD($connDestino,$sSqlListaProjetoDestino);
        $iRowsListaProjetoDestino = pg_num_rows($rsListaProjetoDestino);

        if ( $iRowsListaProjetoDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaProjetoDestino; $iInd++ ) {
            $oProjetoDestino = db_utils::fieldsMemory($rsListaProjetoDestino,$iInd);
            $aListaProjeto[$oProjetoDestino->codprojeto][$oProjetoDestino->exercicio] = $oProjetoDestino->id;
        }

        // FIM PROJETOS ***************************************************************************************************//


        // FUNÇÕES ********************************************************************************************************//

        db_logTitulo(" IMPORTA FUNÇÕES",$sArquivoLog,$iParamLog);

        $sSqlFuncao  = consultaFuncoes();
        $rsFuncao    = consultaBD($connOrigem,$sSqlFuncao);
        $iRowsFuncao = pg_num_rows($rsFuncao);

        if ( $iRowsFuncao ==  0 ) throw new Exception('Nenhuma função encontrada!');

        db_logNumReg($iRowsFuncao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsFuncao; $iInd++ ) {
            $oFuncao = db_utils::fieldsMemory($rsFuncao,$iInd);
            logProcessamento($iInd,$iRowsFuncao,$iParamLog);
            $oTBFuncoes->setByLineOfDBUtils($oFuncao);
            insereRegistros($oTBFuncoes);
        }
        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBFuncoes, PERSISTE);

        /**
         *  É consultado as funções cadastradas na base de destino para que seja populado o array $aListaFuncao
         *  com as funções cadastradas sendo a variável indexada pelo código da função da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da função de origem.
         */
        $sSqlListaFuncaoDestino  = buscaTodosOsObjetosDaTabela("funcoes");
        $rsListaFuncaoDestino    = consultaBD($connDestino,$sSqlListaFuncaoDestino);
        $iRowsListaFuncaoDestino = pg_num_rows($rsListaFuncaoDestino);

        if ( $iRowsListaFuncaoDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaFuncaoDestino; $iInd++ ) {
            $oFuncaoDestino = db_utils::fieldsMemory($rsListaFuncaoDestino,$iInd);
            $aListaFuncao[$oFuncaoDestino->codfuncao] = $oFuncaoDestino->id;
        }
        // FIM FUNÇÕES ****************************************************************************************************//

        // SUBFUNÇÕES *****************************************************************************************************//

        db_logTitulo(" IMPORTA SUBFUNÇÕES",$sArquivoLog,$iParamLog);

        $sSqlSubFuncao  = consultaSubFuncoes();
        $rsSubFuncao    = consultaBD($connOrigem,$sSqlSubFuncao);
        $iRowsSubFuncao = pg_num_rows($rsSubFuncao);

        if ( $iRowsSubFuncao ==  0 ) throw new Exception('Nenhuma SubFunção encontrada!');

        db_logNumReg($iRowsSubFuncao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsSubFuncao; $iInd++ ) {
            $oSubFuncao = db_utils::fieldsMemory($rsSubFuncao,$iInd);
            logProcessamento($iInd,$iRowsSubFuncao,$iParamLog);
            $oTBSubFuncoes->setByLineOfDBUtils($oSubFuncao);
            insereRegistros($oTBSubFuncoes);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBSubFuncoes, PERSISTE);

        /**
         *  É consultado as subfunções cadastradas na base de destino para que seja populado o array $aListaSubFuncao
         *  com as subfunções cadastradas sendo a variável indexada pelo código da subfunção da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da subfunção de origem.
         */
        $sSqlListaSubFuncaoDestino  = buscaTodosOsObjetosDaTabela("subfuncoes");
        $rsListaSubFuncaoDestino    = consultaBD($connDestino,$sSqlListaSubFuncaoDestino);
        $iRowsListaSubFuncaoDestino = pg_num_rows($rsListaSubFuncaoDestino);

        if ( $iRowsListaSubFuncaoDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaSubFuncaoDestino; $iInd++ ) {
            $oSubFuncaoDestino = db_utils::fieldsMemory($rsListaSubFuncaoDestino,$iInd);
            $aListaSubFuncao[$oSubFuncaoDestino->codsubfuncao] = $oSubFuncaoDestino->id;
        }

        // FIM SUBFUNÇÕES *************************************************************************************************//

        // PROGRAMAS ******************************************************************************************************//

        db_logTitulo(" IMPORTA PROGRAMAS",$sArquivoLog,$iParamLog);

        $sSqlPrograma  = consultaProgramas();
        $rsPrograma    = consultaBD($connOrigem,$sSqlPrograma);
        $iRowsPrograma = pg_num_rows($rsPrograma);

        if ( $iRowsPrograma ==  0 ) throw new Exception('Nenhum programa encontrado!');

        db_logNumReg($iRowsPrograma,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsPrograma; $iInd++ ) {
            $oPrograma = db_utils::fieldsMemory($rsPrograma,$iInd);
            logProcessamento($iInd,$iRowsPrograma,$iParamLog);
            $oTBProgramas->setByLineOfDBUtils($oPrograma);
            insereRegistros($oTBProgramas);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBProgramas, PERSISTE);

        /**
         *  É consultado os programas cadastrados na base de destino para que seja populado o array $aListaPrograma
         *  com os programas cadastrados sendo a variável indexada pelo código do programa da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código do programa de origem.
         */
        $sSqlListaProgramaDestino  = buscaTodosOsObjetosDaTabela("programas");
        $rsListaProgramaDestino    = consultaBD($connDestino,$sSqlListaProgramaDestino);
        $iRowsListaProgramaDestino = pg_num_rows($rsListaProgramaDestino);

        if ( $iRowsListaProgramaDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaProgramaDestino; $iInd++ ) {
            $oProgramaDestino = db_utils::fieldsMemory($rsListaProgramaDestino,$iInd);
            $aListaPrograma[$oProgramaDestino->codprograma][$oProgramaDestino->exercicio] = $oProgramaDestino->id;
        }

        // FIM PROGRAMAS **************************************************************************************************//

        // RECURSOS *******************************************************************************************************//
        db_logTitulo(" IMPORTA RECURSOS",$sArquivoLog,$iParamLog);

        /**
         * Consulta Recursos na base de origem
         */
        $sSqlRecurso  = consultaRecursos();
        $rsRecurso    = consultaBD($connOrigem,$sSqlRecurso);
        $iRowsRecurso = pg_num_rows($rsRecurso);

        if ( $iRowsRecurso ==  0 ) throw new Exception('Nenhum recurso encontrado!');

        db_logNumReg($iRowsRecurso,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsRecurso; $iInd++ ) {
            $oRecurso = db_utils::fieldsMemory($rsRecurso,$iInd);
            logProcessamento($iInd,$iRowsRecurso,$iParamLog);
            $oTBRecursos->setByLineOfDBUtils($oRecurso);
            insereRegistros($oTBRecursos);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBRecursos, PERSISTE);

        /**
         *  É consultado os recursos cadastrados na base de destino para que seja populado o array $aListaRecurso
         *  com os recursos cadastrados sendo a variável indexada pelo código do recurso da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código do recurso de origem.
         */
        $sSqlListaRecursoDestino  = buscaTodosOsObjetosDaTabela("recursos");
        $rsListaRecursoDestino    = consultaBD($connDestino,$sSqlListaRecursoDestino);
        $iRowsListaRecursoDestino = pg_num_rows($rsListaRecursoDestino);

        if ( $iRowsListaRecursoDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaRecursoDestino; $iInd++ ) {
            $oRecursoDestino = db_utils::fieldsMemory($rsListaRecursoDestino,$iInd);
            $aListaRecurso[$oRecursoDestino->codrecurso] = $oRecursoDestino->id;
        }

        // FIM RECURSOS ***************************************************************************************************//

        // PLANOCONTAS ****************************************************************************************************//

        db_logTitulo(" IMPORTA PLANOCONTAS",$sArquivoLog,$iParamLog);

        if (USE_PCASP || file_exists(DB_LIBS . 'config/pcasp.txt')) {
            $sSqlPlanoConta  = consultaPlanoContasPCASP(ANO_ANTERIOR_IMPLANTACAO_PCASP);
        }else{
            $sSqlPlanoConta  = consultaPlanoContas();  
        }

        $rsPlanoConta    = consultaBD($connOrigem,$sSqlPlanoConta);
        $iRowsPlanoConta = pg_num_rows($rsPlanoConta);
        if ( $iRowsPlanoConta ==  0) throw new Exception('Nenhum recurso encontrado!');
        db_logNumReg($iRowsPlanoConta,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsPlanoConta; $iInd++ ) {
            $oPlanoConta = db_utils::fieldsMemory($rsPlanoConta,$iInd);
            logProcessamento($iInd,$iRowsPlanoConta,$iParamLog);
            $oTBPlanoContas->setByLineOfDBUtils($oPlanoConta);
            insereRegistros($oTBPlanoContas);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBPlanoContas, PERSISTE);

        /**
         *  É consultado os planocontas cadastrados na base de destino para que seja populado o array $aListaPlanoConta
         *  com os planocontas cadastrados sendo a variável indexada pelo código do planoconta da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código do planoconta de origem.
         */
        $sSqlListaPlanoContaDestino  = buscaTodosOsObjetosDaTabela("planocontas");
        $rsListaPlanoContaDestino    = consultaBD($connDestino,$sSqlListaPlanoContaDestino);
        $iRowsListaPlanoContaDestino = pg_num_rows($rsListaPlanoContaDestino);

        if ( $iRowsListaPlanoContaDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaPlanoContaDestino; $iInd++ ) {
            $oPlanoContaDestino = db_utils::fieldsMemory($rsListaPlanoContaDestino,$iInd);
            $aListaPlanoConta[$oPlanoContaDestino->codcon][$oPlanoContaDestino->exercicio] = $oPlanoContaDestino->id;
        }

        // FIM PLANOCONTAS ************************************************************************************************//


        // RECEITAS *******************************************************************************************************//

        db_logTitulo(" IMPORTA RECEITAS",$sArquivoLog,$iParamLog);

        $sSqlReceita  = consultaReceitas();
        $rsReceita    = consultaBD($connOrigem,$sSqlReceita);
        $iRowsReceita = pg_num_rows($rsReceita);

        if ( $iRowsReceita ==  0 ) throw new Exception('Nenhum recurso encontrado!');

        db_logNumReg($iRowsReceita,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsReceita; $iInd++ ) {

            $oReceita = db_utils::fieldsMemory($rsReceita,$iInd);
            logProcessamento($iInd,$iRowsReceita,$iParamLog);
            if ( !isset($aListaPlanoConta[$oReceita->codcon][$oReceita->exercicio]) ) {
                echo(print_r($oReceita)); echo "\n";
                throw new Exception("ERRO-0: Plano de Contas não encontrado CODCON: $oReceita->codcon  EXERCICIO: $oReceita->exercicio RECEITA: $oReceita->codreceita");
            }
            else {
                $oReceita->recurso_id     = $aListaRecurso[$oReceita->codrecurso];
                $oReceita->planoconta_id  = $aListaPlanoConta[$oReceita->codcon][$oReceita->exercicio];
                $oReceita->instituicao_id = $aListaInstit[$oReceita->codinstit];
                $oTBReceitas->setByLineOfDBUtils($oReceita);
                insereRegistros($oTBReceitas);
            }
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBReceitas, PERSISTE);

        /**
         *  É consultado as receitas cadastradas na base de destino para que seja populado o array $aListaReceita
         *  com as receitas cadastradas sendo a variável indexada pelo código do receita da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da receita de origem.
         */
        $sSqlListaReceitaDestino  = buscaTodosOsObjetosDaTabela("receitas");
        $rsListaReceitaDestino    = consultaBD($connDestino,$sSqlListaReceitaDestino);
        $iRowsListaReceitaDestino = pg_num_rows($rsListaReceitaDestino);

        if ( $iRowsListaReceitaDestino == 0 ) throw new Exception('Nenhum registro encontrado');

        for ( $iInd=0; $iInd < $iRowsListaReceitaDestino; $iInd++ ) {
            $oReceitaDestino = db_utils::fieldsMemory($rsListaReceitaDestino,$iInd);
            $aListaReceita[$oReceitaDestino->codreceita][$oReceitaDestino->exercicio] = $oReceitaDestino->id;
        }

        // FIM RECEITAS ***************************************************************************************************//

        // MOVIMENTAÇÕES RECEITAS *****************************************************************************************//

        db_logTitulo(" IMPORTA MOVIMENTAÇÕES DAS RECEITAS",$sArquivoLog,$iParamLog);
        $sSqlReceitaMovimentacao  = movimentacoesReceitas();

        /**
         * Consulta ReceitasMovimentacoes na base de origem
         */

        $sSqlReceitaMovimentacao .= consultaMovimentacoesReceitas();
        $rsReceitaMovimentacao    = consultaBD($connOrigem,$sSqlReceitaMovimentacao);
        $iRowsReceitaMovimentacao = pg_num_rows($rsReceitaMovimentacao);

        if ( $iRowsReceitaMovimentacao ==  0 ) throw new Exception('Nenhum recurso encontrado!');
        db_logNumReg($iRowsReceitaMovimentacao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsReceitaMovimentacao; $iInd++ ) {
            $oReceitaMovimentacao = db_utils::fieldsMemory($rsReceitaMovimentacao,$iInd);
            logProcessamento($iInd,$iRowsReceitaMovimentacao,$iParamLog);
            $sSqlReceitaSaldo = "EXECUTE stmt_receitasaldo({$oReceitaMovimentacao->exercicio}, {$oReceitaMovimentacao->codreceita})";
            $rsReceitaSaldo = consultaBD($connOrigem, $sSqlReceitaSaldo);
            $iRowsReceitaSaldo = pg_num_rows($rsReceitaSaldo);

            if ( $iRowsReceitaSaldo ==  0 ) {
                $oReceitaMovimentacao->valor_previsao_atualizada = 0;
            } else {
                $oReceitaMovimentacao->valor_previsao_atualizada = pg_result($rsReceitaSaldo, 0, 0);
            }

            $oReceitaMovimentacao->receita_id = $aListaReceita[$oReceitaMovimentacao->codreceita][$oReceitaMovimentacao->exercicio];
            $oTBReceitasMovimentacoes->setByLineOfDBUtils($oReceitaMovimentacao);
            insereRegistros($oTBReceitasMovimentacoes);
        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */
        insereRegistros($oTBReceitasMovimentacoes, PERSISTE);


        // ACERTA TABELA receitas_movimentacoes ***************************************************************************//

        $rsAcertaRecMov = consultaBD($connDestino,acerta_receitas_movimentacoes());

        if ( !$rsAcertaRecMov ) {
            throw new Exception("ERRO-0: Erro ao acertar tabela receitas_movimentacoes !");
        }

        // ****************************************************************************************************************//


        // FIM MOVIMENTAÇÕES RECEITAS *************************************************************************************//


        // DOTAÇÕES *******************************************************************************************************//

        db_logTitulo(" IMPORTA DOTAÇÕES",$sArquivoLog,$iParamLog);

        /**
         * Consulta Dotacaos na base de origem
         */
        $rsDotacao    = consultaBD($connOrigem,consulta_dotacao());
        $iRowsDotacao = pg_num_rows($rsDotacao);

        if ( $iRowsDotacao ==  0 ) throw new Exception('Nenhum recurso encontrado!');


        db_logNumReg($iRowsDotacao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsDotacao; $iInd++ ) {

            $oDotacao = db_utils::fieldsMemory($rsDotacao,$iInd);

            logProcessamento($iInd,$iRowsDotacao,$iParamLog);

            if ( !isset($aListaProjeto[$oDotacao->codprojeto][$oDotacao->exercicio]) ) {
                $sMsg  = "ERRO-0: Projeto não encontrado PROJETO: $oDotacao->codprojeto  EXERCICIO: $oDotacao->exercicio ";
                $sMsg .= "DOTAÇÃO: $oDotacao->coddotacao ";
                throw new Exception($sMsg);
            }

            if ( !isset($aListaPlanoConta[$oDotacao->codcon][$oDotacao->exercicio]) ) {
                $sMsg  = "ERRO-0: Plano de Conta não encontrado CODCON: $oDotacao->codcon EXERCICIO: $oDotacao->exercicio ";
                $sMsg .= "DOTAÇÃO: $oDotacao->coddotacao ";
                throw new Exception($sMsg);
            }

            $oDotacao->orgao_id       = $aListaOrgao[$oDotacao->codorgao][$oDotacao->exercicio];
            $oDotacao->unidade_id     = $aListaUnidade[$oDotacao->codunidade][$oDotacao->exercicio];
            $oDotacao->funcao_id      = $aListaFuncao[$oDotacao->codfuncao];
            $oDotacao->subfuncao_id   = $aListaSubFuncao[$oDotacao->codsubfuncao];
            $oDotacao->programa_id    = $aListaPrograma[$oDotacao->codprograma][$oDotacao->exercicio];
            $oDotacao->projeto_id     = $aListaProjeto[$oDotacao->codprojeto][$oDotacao->exercicio];
            $oDotacao->planoconta_id  = $aListaPlanoConta[$oDotacao->codcon][$oDotacao->exercicio];
            $oDotacao->recurso_id     = $aListaRecurso[$oDotacao->codrecurso];
            $oDotacao->instituicao_id = $aListaInstit[$oDotacao->codinstit];

            $oTBDotacoes->setByLineOfDBUtils($oDotacao);

            insereRegistros($oTBDotacoes);

        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */

        insereRegistros($oTBDotacoes, PERSISTE);

        /**
         *  É consultado as dotacoes cadastradas na base de destino para que seja populado o array $aListaDotacao
         *  com as dotacoes cadastradas sendo a variável indexada pelo código do receita da base de origem.
         *  Essa variável será utilizada por todo o fonte para identificar o código da dotacao de origem.
         */

        $rsListaDotacaoDestino    = consultaBD($connDestino,buscaTodosOsObjetosDaTabela("dotacoes"));
        $iRowsListaDotacaoDestino = pg_num_rows($rsListaDotacaoDestino);

        if ( $iRowsListaDotacaoDestino == 0 ) throw new Exception('Nenhum registro encontrado');


        for ( $iInd=0; $iInd < $iRowsListaDotacaoDestino; $iInd++ ) {
            $oDotacaoDestino = db_utils::fieldsMemory($rsListaDotacaoDestino,$iInd);
            $aListaDotacao[$oDotacaoDestino->coddotacao][$oDotacaoDestino->exercicio] = $oDotacaoDestino->id;
        }

        // FIM DOTAÇÕES ***************************************************************************************************//


        unset($aListaOrgao);
        unset($aListaUnidade);
        unset($aListaProjeto);
        unset($aListaFuncao);
        unset($aListaSubFuncao);
        unset($aListaPrograma);
        unset($aListaRecurso);
        unset($aListaReceita);


        // EMPENHOS *******************************************************************************************************//

        db_logTitulo(" IMPORTA EMPENHOS",$sArquivoLog,$iParamLog);


        /**
         * Consulta Empenhos na base de origem
         */
        $rsEmpenho    = consultaBD($connOrigem,consulta_empenhos($iExercicioBase));
        $iRowsEmpenho = pg_num_rows($rsEmpenho);

        if ( $iRowsEmpenho ==  0 ) throw new Exception('Nenhum recurso encontrado!');


        db_logNumReg($iRowsEmpenho,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsEmpenho; $iInd++ ) {

            $oEmpenho = db_utils::fieldsMemory($rsEmpenho,$iInd);

            logProcessamento($iInd,$iRowsEmpenho,$iParamLog);

            $rsPessoas   = consultaBD($connDestino,consulta_pessoas_codpessoa($oEmpenho->numcgm));

            if ( pg_num_rows($rsPessoas) > 0 ) {

                $iIdPessoa = db_utils::fieldsMemory($rsPessoas,0)->id;
            } else {

                $oTBPessoas->id        = '';
                $oTBPessoas->codpessoa = $oEmpenho->numcgm;
                $oTBPessoas->nome      = $oEmpenho->nome;
                $oTBPessoas->cpfcnpj   = $oEmpenho->cgccpf;

                insereRegistros($oTBPessoas, INSERE | PERSISTE);

                $iIdPessoa = $oTBPessoas->getLastPk();
            }

            if ( !isset($aListaDotacao[$oEmpenho->coddotacao][$oEmpenho->exercicio]) ) {
                $sMsg  = "ERRO-0: Dotação não encontrada DOTAÇÃO: $oEmpenho->coddotacao  EXERCICIO: $oEmpenho->exercicio ";
                $sMsg .= "NUMEMP  $oEmpenho->codempenho ";
                throw new Exception($sMsg);
            }

            if ( !isset($aListaPlanoConta[$oEmpenho->codcon][$oEmpenho->exercicio]) ) {
                $sMsg  = "ERRO-0: Plano de Conta não encontrado CODCON: $oEmpenho->codcon EXERCICIO: $oEmpenho->exercicio ";
                $sMsg .= "NUMEMP: $oEmpenho->codempenho ";
                throw new Exception($sMsg);
            }

            $sTipoCompra = "";

            if ( trim($oEmpenho->codautoriza) != '' ) {

                $rsLicita = consultaBD($connOrigem,consulta_tipo_compra($oEmpenho->codautoriza));

                if ( pg_num_rows($rsLicita) > 0 ) {

                    $oLicita = db_utils::fieldsMemory($rsLicita,0);

                    $aData       = explode("-",$oLicita->l20_dtpublic);
                    $iAnoLic     = $aData[0];
                    $sNumeroLic  = $oLicita->l20_numero."/".$iAnoLic;
                    $sTipoCompra = $oLicita->l03_descr." Numero Licitação : {$sNumeroLic}";

                }

            }

            if ( trim($sTipoCompra) == '' ) {

                $sTipoCompra = $oEmpenho->descrtipocompra;

                if ( trim($oEmpenho->numero_licitacao) != '' ) {
                    $sTipoCompra .= " Numero Licitação : {$oEmpenho->numero_licitacao}";
                }
            }


            $oEmpenho->pessoa_id      = $iIdPessoa;
            $oEmpenho->planoconta_id  = $aListaPlanoConta[$oEmpenho->codcon][$oEmpenho->exercicio];
            $oEmpenho->dotacao_id     = $aListaDotacao[$oEmpenho->coddotacao][$oEmpenho->exercicio];
            $oEmpenho->instituicao_id = $aListaInstit[$oEmpenho->codinstit];
            $oEmpenho->tipo_compra    = $sTipoCompra;

            $oTBEmpenhos->setByLineOfDBUtils($oEmpenho);

            insereRegistros($oTBEmpenhos);

        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */

        insereRegistros($oTBEmpenhos, PERSISTE);

        // FIM EMPENHOS ***************************************************************************************************//


        unset($aListaPlanoConta);
        unset($aListaDotacao);


        // ITENS EMPENHOS *************************************************************************************************//

        db_logTitulo(" IMPORTA ITENS EMPENHOS",$sArquivoLog,$iParamLog);


        $rsDadosEmpenhosDestino = consultaBD($connDestino,buscaTodosOsObjetosDaTabela("empenhos"));
        $iLinhasEmpenhosDestino = pg_num_rows($rsDadosEmpenhosDestino);

        db_logNumReg($iLinhasEmpenhosDestino,$sArquivoLog,$iParamLog);

        for ( $iInd=0; $iInd < $iLinhasEmpenhosDestino; $iInd++ ) {

            $oEmpenhoDestino = db_utils::fieldsMemory($rsDadosEmpenhosDestino,$iInd);

            logProcessamento($iInd,$iLinhasEmpenhosDestino,$iParamLog);

            $rsDadosItensEmpenho = consultaBD($connOrigem,consulta_itens_empenho($oEmpenhoDestino->codempenho));
            $iLinhasItensEmpenho = pg_num_rows($rsDadosItensEmpenho);

            if ( $iLinhasItensEmpenho > 0 ) {

                for ( $iIndItem=0; $iIndItem < $iLinhasItensEmpenho; $iIndItem++ ) {

                    $oItemEmpenho = db_utils::fieldsMemory($rsDadosItensEmpenho,$iIndItem);

                    if ( $oItemEmpenho->descricao == '' ) {
                        $oItemEmpenho->descricao = 'DESCRIÇÃO NÃO ESPECIFICADA';
                    }

                    $oItemEmpenho->empenho_id = $oEmpenhoDestino->id;
                    $oTBEmpenhosItens->setByLineOfDBUtils($oItemEmpenho);

                    insereRegistros($oTBEmpenhosItens);
                }

                insereRegistros($oTBEmpenhosItens, PERSISTE);

            }

            // Consulta Processos do Empenho
            $rsDadosProcessoEmpenho  = consultaBD($connOrigem,consulta_processos_empenho($oEmpenho->codempenho));
            $iLinhasProcessoEmpenho = pg_num_rows($rsDadosProcessoEmpenho);

            if ( $iLinhasProcessoEmpenho > 0 ) {

                for ( $iIndProcesso=0; $iIndProcesso < $iLinhasProcessoEmpenho; $iIndProcesso++ ) {

                    $oProcessoEmpenho = db_utils::fieldsMemory($rsDadosProcessoEmpenho,$iIndProcesso);

                    $oProcessoEmpenho->empenho_id = $oEmpenhoDestino->id;
                    $oTBEmpenhosProcessos->setByLineOfDBUtils($oProcessoEmpenho);

                    insereRegistros($oTBEmpenhosProcessos);
                }

                insereRegistros($oTBEmpenhosProcessos, PERSISTE);
            }

        }

        // FIM ITENS EMPENHOS *********************************************************************************************//


        // MOVIMENTACOES EMPENHOS *****************************************************************************************//

        db_logTitulo(" IMPORTA MOVIMENTACOES EMPENHOS",$sArquivoLog,$iParamLog);

        /**
         * Consulta EmpenhosMovimentacoes na base de origem
         */
        $rsEmpenhoMovimentacao    = consultaBD($connOrigem,consulta_empenhoMovimentacoes_origem($iExercicioBase));
        $iRowsEmpenhoMovimentacao = pg_num_rows($rsEmpenhoMovimentacao);

        if ( $iRowsEmpenhoMovimentacao ==  0 )  throw new Exception('Nenhuma movimentação encontrada!');


        db_logNumReg($iRowsEmpenhoMovimentacao,$sArquivoLog,$iParamLog);

        /**
         *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
         *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
         *  o método persist que insere fisicamente os registros na base de dados através do COPY.
         */
        for ( $iInd=0; $iInd < $iRowsEmpenhoMovimentacao; $iInd++ ) {

            $oEmpenhoMovimentacao = db_utils::fieldsMemory($rsEmpenhoMovimentacao,$iInd);

            logProcessamento($iInd,$iRowsEmpenhoMovimentacao,$iParamLog);


            if (!isset($aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo])) {

                $oTBEmpenhosMovimentacoesTipos->id        = '';
                $oTBEmpenhosMovimentacoesTipos->codtipo   = $oEmpenhoMovimentacao->codtipo;
                $oTBEmpenhosMovimentacoesTipos->codgrupo  = $oEmpenhoMovimentacao->codgrupo;
                $oTBEmpenhosMovimentacoesTipos->descricao = $oEmpenhoMovimentacao->descrtipo;

                insereRegistros($oTBEmpenhosMovimentacoesTipos, INSERE | PERSISTE);

                $aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo] = $oTBEmpenhosMovimentacoesTipos->getLastPk();

            }

            $rsEmpenhoDestino    = consultaBD($connDestino,consulta_empenho_destino($oEmpenhoMovimentacao->codempenho));

            if ( pg_num_rows($rsEmpenhoDestino) > 0 ) {
                $iIdEmpenho = db_utils::fieldsMemory($rsEmpenhoDestino,0)->id ;
            } else {
                throw new Exception("ERRO-0: Empenho não encontrado!$oEmpenhoMovimentacao->codempenho  ");
            }

            $oEmpenhoMovimentacao->empenho_id                   = $iIdEmpenho;
            $oEmpenhoMovimentacao->empenho_movimentacao_tipo_id = $aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo];

            $oTBEmpenhosMovimentacoes->setByLineOfDBUtils($oEmpenhoMovimentacao);

            insereRegistros($oTBEmpenhosMovimentacoes);

        }

        /**
         *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
         *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
         */

        insereRegistros($oTBEmpenhosMovimentacoes, PERSISTE);

        // FIM MOVIMENTAÇÕES EMPENHOS *************************************************************************************//

        // SERVIDORES *********************************** //

        db_logTitulo(" IMPORTA SERVIDORES", $sArquivoLog, $iParamLog);

        db_query($connOrigem, sql_servidores($iExercicioBase));

        db_query($connOrigem, "create index dados_servidor_ano_mes_matricula_in on dados_servidor (ano, mes, matricula) ");                                                                             

        db_query($connOrigem, "analyze dados_servidor ");
        $rsServidores = consultaBD($connOrigem, consulta_dados_cadastrais_servidor());

        if ( !$rsServidores ) throw new Exception("ERRO-1: Erro ao criar tabela temporaria dos servidores.!");


        $iRowsServidores = pg_num_rows($rsServidores);

        db_logNumReg($iRowsServidores, $sArquivoLog, $iParamLog);

        for ($iInd = 0; $iInd < $iRowsServidores; $iInd++ ) {

            $oServidorRow                 = db_utils::fieldsMemory($rsServidores, $iInd);
            $oServidorRow->instituicao_id = $aListaInstit[$oServidorRow->instituicao];

            $oTBServidores->setByLineOfDBUtils($oServidorRow, true);
            logProcessamento($iInd, $iRowsServidores, $iParamLog);
        }

        insereRegistros($oTBServidores, PERSISTE);

        // FIM SERVIDORES ***************************** //

        // IMPORTACAO MOVIMENTACOES SERVIDORES ******** //

        db_logTitulo(" IMPORTA MOVIMENTACOES DOS SERVIDORES", $sArquivoLog, $iParamLog);

        $rsServidoresMovimentacao  = db_query($connOrigem, consulta_movimentacao_servidor());

        if ( !$rsServidoresMovimentacao ) throw new Exception("ERRO-1: Erro ao buscar movimentacoes dos servidores.!");


        $iRowsServidores = pg_num_rows($rsServidoresMovimentacao);

        db_logNumReg($iRowsServidores, $sArquivoLog, $iParamLog);

        for ($iInd = 0; $iInd < $iRowsServidores; $iInd++) {

            $oMovimentacaoServidorRow = db_utils::fieldsMemory($rsServidoresMovimentacao, $iInd);

            $oTBMovimentacoesServidores->setByLineOfDBUtils($oMovimentacaoServidorRow, true);
            logProcessamento($iInd, $iRowsServidores, $iParamLog);
        }

        insereRegistros($oTBMovimentacoesServidores, PERSISTE);

        /**
         * Pega todas as movimentacoes dos servidores e monta uma matriz para pegar a movimentação correspondente
         * a competência. a matriz $aMatrizMovimentacaoServidor será usada ao inserir os dados financeiros.
         */

        $rsListaServidorMovimentacao     = consultaBD($connDestino, sql_matriz_servidor_movimentacao(constant('SSCHEMA')));
        $iRowsListaServidorMovimentacao  = pg_num_rows($rsListaServidorMovimentacao);

        for ( $iInd=0; $iInd < $iRowsListaServidorMovimentacao; $iInd++ ) {

            $oServidorMovimentacaoRow = db_utils::fieldsMemory($rsListaServidorMovimentacao, $iInd);
            $aMatrizMovimentacaoServidor[$oServidorMovimentacaoRow->ano][$oServidorMovimentacaoRow->mes]
                [$oServidorMovimentacaoRow->servidor_id] = $oServidorMovimentacaoRow->id;
        }

        // FIM IMPORTACAO MOVIMENTACOES SERVIDORES **** //

        // IMPORTACAO DADOS FINANCEIROS SERVIDOR ****** //

        db_logTitulo(" IMPORTA DADOS FINANCEIROS SERVIDOR", $sArquivoLog, $iParamLog);

        /**
         * CRIA TABELA COM OS TOTALILZADORES
         */

        $rsTempSomatorio = db_query(cria_tabela_totalizadores());

        if(!$rsTempSomatorio) throw new Exception("ERRO-1: Erro ao criar tabela somatorio!");


        $rsIndiceSomatorio    = db_query("create index somatorio_anousu_mesusu_regist_in on somatorio (anousu, mesusu, regist)");

        if(!$rsIndiceSomatorio) throw new Exception("ERRO-1: Erro ao criar indice somatorio_anousu_mesusu_regist_in!");


        $rsAnalizeSomatorio   = db_query("analyze somatorio");

        if(!$rsAnalizeSomatorio) throw new Exception("ERRO-1: Erro ao executar analyze!");


        $rsDadosServidores   = consultaBD($connOrigem, "select distinct ano, mes from dados_servidor");
        $iDadosServidores    = pg_num_rows($rsDadosServidores);

        for ($iServidor = 0; $iServidor < $iDadosServidores; $iServidor++) {

            $oDadosServidores = db_utils::fieldsMemory($rsDadosServidores, $iServidor);
            $mes = $oDadosServidores->mes;
            $ano = $oDadosServidores->ano;  
            $rsFolhaPagamento    = db_query($connOrigem, sql_folha_pagamento($ano, $mes));

            if ( !$rsFolhaPagamento ) throw new Exception("ERRO-1: Erro ao buscar dados de rubricas.!");

            $iRowsFolhaPagamento = pg_num_rows($rsFolhaPagamento);

            db_logNumReg($iRowsFolhaPagamento, $sArquivoLog, $iParamLog);

            for ($iInd = 0; $iInd < $iRowsFolhaPagamento; $iInd++) {

                $oFolhaPagamentoRow = db_utils::fieldsMemory($rsFolhaPagamento, $iInd);

                if ( !empty($aMatrizMovimentacaoServidor[$oFolhaPagamentoRow->ano][$oFolhaPagamentoRow->mes][$oFolhaPagamentoRow->matricula]) ) {

                    $oFolhaPagamentoRow->servidor_movimentacao_id = $aMatrizMovimentacaoServidor[$oFolhaPagamentoRow->ano]
                        [$oFolhaPagamentoRow->mes]
                        [$oFolhaPagamentoRow->matricula];

                    $oTBFolhaPagamento->setByLineOfDBUtils($oFolhaPagamentoRow, true);
                    logProcessamento($iInd, $iRowsFolhaPagamento, $iParamLog);
                } else {

                    $sMensagemSemMovimentacao = "Dados Financeiros: {$oFolhaPagamentoRow->matricula} - {$oFolhaPagamentoRow->ano}/{$oFolhaPagamentoRow->mes}  sem movimentações.";
                    db_log($sMensagemSemMovimentacao, $sArquivoLog, $iParamLog);
                }

            }

            insereRegistros($oTBFolhaPagamento, PERSISTE);

            // FIM IMPORTACAO DADOS FINANCEIROS SERVIDOR ** //

        }

        // IMPORTACAO RECURSOS HUMANOS SERVIDOR ******* //

        db_logTitulo(" IMPORTA DADOS RECURSOS HUMANOS SERVIDOR", $sArquivoLog, $iParamLog);

        $rsRecursosHumanos    = db_query($connOrigem, sql_recursos_humanos());

        if ( !$rsRecursosHumanos ) throw new Exception("ERRO-1: Erro ao buscar dados recursos humanos.!");


        $iRowsRecursosHumanos = pg_num_rows($rsRecursosHumanos);

        db_logNumReg($iRowsRecursosHumanos, $sArquivoLog, $iParamLog);

        for ($iInd = 0; $iInd < $iRowsRecursosHumanos; $iInd++) {

            $oRecursosHumanosRow = db_utils::fieldsMemory($rsRecursosHumanos, $iInd);

            $oTBAssentamentos->setByLineOfDBUtils($oRecursosHumanosRow, true);
            logProcessamento($iInd, $iRowsRecursosHumanos, $iParamLog);
        }

        insereRegistros($oTBAssentamentos, PERSISTE);

        // FIM IMPORTACAO RECURSOS HUMANOS ASSENTAMENTOS //

        // EXCLUSÃO DE SCHEMAS ANTIGOS ************************************************************************************//

        $rsSchemasAntigos      = consultaBD($connDestino,consulta_Schemas_antigos(constant('NUMERO_BASES_ANTIGAS')));
        $iLinhasSchemasAntigos = pg_num_rows($rsSchemasAntigos);

        for ($iInd=0; $iInd < $iLinhasSchemasAntigos; $iInd++ ) {

            $oSchemaAntigo = db_utils::fieldsMemory($rsSchemasAntigos,$iInd);

            $sSqlExcluiSchemaAntigo = " DROP SCHEMA {$oSchemaAntigo->schema_name} CASCADE ";

            if ( !db_query($connDestino,$sSqlExcluiSchemaAntigo) ) throw new Exception("ERRO-0: Erro ao excluir schema {$oSchemaAntigo->schema_name} !");
        }

        // FIM DA EXCLUSÃO DE SCHEMAS ANTIGOS *****************************************************************************//


        if ( $iLinhasSchemasAtual > 0 ) {

            // ACERTA TABELA empenhos_movimentacoes_exercicios ****************************************************************//


            $rsAcertaEmpMovExerc = db_query($connDestino,acerta_emp_mov_exer());

            if ( !$rsAcertaEmpMovExerc ) throw new Exception("ERRO-0: Erro ao acertar tabela empenhos_movimentacoes_exercicios !");


            // ****************************************************************************************************************//


            // ACERTA GLOSSARIOS TIPOS ****************************************************************************************//


            $sSqlGlossariosTipos    = " select *
                from ". SBKPSCHEMA . ".glossarios_tipos ";

            $rsDadosGlossariosTipos = db_query($connDestino,$sSqlGlossariosTipos);

            if ( !$rsDadosGlossariosTipos )  throw new Exception("ERRO-0: Erro ao consultar tabela glossarios_tipos !");        

            $iLinhasGlossariosTipos = pg_num_rows($rsDadosGlossariosTipos);

            for ( $iInd=0; $iInd < $iLinhasGlossariosTipos; $iInd++ ) {

                $oGloassariosTipos = db_utils::fieldsMemory($rsDadosGlossariosTipos,$iInd);

                $oTBGlossariosTipos->setByLineOfDBUtils($oGloassariosTipos);

                insereRegistros($oTBGlossariosTipos);

            }
            insereRegistros($oTBGlossariosTipos, PERSISTE);

            // ****************************************************************************************************************//

            // ACERTA GLOSSARIOS **********************************************************************************************//

            $sSqlGlossarios    = " select *
                from ". SBKPSCHEMA . ".glossarios ";

            $rsDadosGlossarios = db_query($connDestino,$sSqlGlossarios);

            if ( !$rsDadosGlossarios ) throw new Exception("ERRO-0: Erro ao consultar tabela glossarios !");


            $iLinhasGlossarios = pg_num_rows($rsDadosGlossarios);

            for ( $iInd=0; $iInd < $iLinhasGlossarios; $iInd++ ) {

                $oGloassarios = db_utils::fieldsMemory($rsDadosGlossarios,$iInd);

                $oTBGlossarios->setByLineOfDBUtils($oGloassarios);

                insereRegistros($oTBGlossarios);

            }

            insereRegistros($oTBGlossarios, PERSISTE);

            // ****************************************************************************************************************//

        }

    } catch (Exception $eException) {

        $lErro = true;
        db_log($eException->getMessage(),$sArquivoLog,$iParamLog);

    }

    if ( $lErro ) {

        db_query($connDestino,"ROLLBACK;");
        db_logTitulo(" FIM PROCESSAMENTO COM ERRO",$sArquivoLog,$iParamLog);
    } else {

        db_query($connDestino,"COMMIT;");
        db_logTitulo(" FIM PROCESSAMENTO ",$sArquivoLog,$iParamLog);
    }


    function db_log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

        $aDataHora    = getdate();
        $sQuebraAntes = $lQuebraAntes ? "\n" : "";

        if ($lLogDataHora) {
            $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes, $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog);
        } else {
            $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
        }

        // Se habilitado saida na tela...
        if ($iTipo == 0 or $iTipo == 1) {
            echo $sOutputLog;
        }

        // Se habilitado saida para arquivo...
        if ($iTipo == 0 or $iTipo == 2) {
            if (! empty($sArquivo)) {
                $fd = fopen($sArquivo, "a+");
                if ($fd) {
                    fwrite($fd, $sOutputLog);
                    fclose($fd);
                }
            }
        }

        return $aDataHora;

    }


    /**
     * Função que exibe na tela a quantidade de registros processados
     * e a quandidade de memória utilizada
     *
     * @param integer $iInd      Indice da linha que está sendo processada
     * @param integer $iTotalLinhas  Total de linhas a processar
     * @param integer $iParamLog     Caso seja passado true é exibido na tela
     */
    function logProcessamento($iInd,$iTotalLinhas,$iParamLog){

        $nPercentual = round((($iInd + 1) / $iTotalLinhas) * 100, 2);
        $nMemScript  = (float)round( (memory_get_usage()/1024 ) / 1024,2);
        $sMemScript  = $nMemScript ." Mb";
        $sMsg        = "".($iInd+1)." de {$iTotalLinhas} Processando ".str_pad($nPercentual,5,' ',STR_PAD_LEFT)." %"." Total de memoria utilizada : {$sMemScript} ";
        $sMsg        = str_pad($sMsg,100," ",STR_PAD_RIGHT);
        db_log($sMsg."\r",null,$iParamLog,true,false);

    }


    /**
     * Imprime o título do log
     *
     * @param string  $sTitulo
     * @param boolean $iParamLog  Caso seja passado true é exibido na tela
     */
    function db_logTitulo($sTitulo="",$sArquivoLog="",$iParamLog=0) {

        db_log("",$sArquivoLog,$iParamLog);
        db_log("//".str_pad($sTitulo,85,"-",STR_PAD_BOTH)."//",$sArquivoLog,$iParamLog);
        db_log("",$sArquivoLog,$iParamLog);
        db_log("",$sArquivoLog,$iParamLog);
    }

    function db_logNumReg($iLinhas,$sArquivoLog,$iParamLog){

        db_log("Total de Registros Encontrados : {$iLinhas}",$sArquivoLog,$iParamLog);
        db_log("\n",$sArquivoLog,1);
    }

    ?>
