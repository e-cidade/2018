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

/**
 ********************************** LEIAME *************************************
 *
 * EXECUÇAO:
 *     php FrontIntegracaoExterna.php --executable=integracao_externa/portal_transparencia/con4_portaltransparencia001.php --dir integracao_externa/portal_transparencia
 *
 *******************************************************************************
 */

/**
 * Alterado memória do PHP on the fly, para não estourar a carga dos dados do servidor
 */
if(defined('ECIDADE_PATH')){
  require_once(ECIDADE_PATH . "libs/db_autoload.php");
} else {
  die("Diretorio invalido\n");
}

ini_set("memory_limit", '-1');

$HTTP_SESSION_VARS['DB_acessado']       = 1;
$HTTP_SESSION_VARS['DB_datausu']        = time();
$HTTP_SESSION_VARS['DB_anousu']         = date('Y',time());
$HTTP_SESSION_VARS['DB_id_usuario']     = 1;
$HTTP_SESSION_VARS['DB_login']          = '';
$HTTP_SESSION_VARS['DB_traceLogAcount'] = false;

$iAnoAtual = date('Y',time());

/**
 * compatibilidade de versao entre 2 e 3 do ecidade.
 */
if (!function_exists('modification')) {
  function modification($path) {
    return $path;
  }
}

/**
 *  Número de bases antigas que o script irá manter automáticamente
 */
$iNroBasesAntigas = 2;

/**
 *  Nome do Schema gerado pelo script
 */
$sSchema    = "transparencia";
$sBkpSchema = "bkp_transparencia_".date("Ymd_His");

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

  $sCaminho = realpath(dirname(__FILE__));
  if ( defined('ECIDADE_PATH') ) {
    $sCaminho = ECIDADE_PATH . 'integracao_externa' . DS . 'portal_transparencia';
  }

  $sArquivoLink = $sCaminho.'/log/portaldatransparencia.log';
  $sArquivoLog  = $sCaminho.'/log/processamento_transparencia'.date("Ymd_His").'.log';

  if (file_exists($sArquivoLink)) {
    unlink($sArquivoLink);
  }

  symlink($sArquivoLog, $sArquivoLink);

}

/**
 * Controle de fatal da carga
 */
register_shutdown_function(function() use ($sArquivoLog, $iParamLog) {

  $error = error_get_last();

  $e_fatal = (E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

  if (!empty($error) && ( $error['type'] & $e_fatal) )  {
    db_logTitulo(" FIM PROCESSAMENTO COM ERRO",$sArquivoLog,$iParamLog);
  }

});

require_once('libs/dbportal.constants.php');
require_once('libs/db_conecta.php');
require_once('libs/databaseVersioning.php');

$sCaminhoScript = getcwd();
chdir('../../');

require_once(modification("model/configuracao/TraceLog.model.php"));
require_once(modification("model/integracao/transparencia/IntegracaoPortalTransparencia.model.php"));
require_once(modification("model/integracao/transparencia/IntegracaoLicitacao.model.php"));
require_once(modification("model/integracao/transparencia/IntegracaoContrato.model.php"));
require_once(modification("std/label/rotulo.php"));
require_once(modification("std/label/RotuloDB.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("model/dataManager.php"));

chdir($sCaminhoScript);

/**
 *  Exercício que será utilizado como base para migração, ou seja serão consultados apenas
 *  dados apartir do exercício informado.
 */
$iExercicioBase = EXERCICIO_BASE;
$aIntegracoesRealizar = array();
if (defined("INTEGRACOES_TRANSPARENCIA")) {
  $aIntegracoesRealizar = explode(",", INTEGRACOES_TRANSPARENCIA);
}

/**
 * Adicionado verificação para configurar a constant caso o cliente use PCASP
 */
$lUsarPcasp         = false;
$sSqlConParametro   = " select c90_usapcasp ";
$sSqlConParametro  .= "   from contabilidade.conparametro ";
$rsConParametro     = db_query($sSqlConParametro);
$lParametroUsaPCASP = pg_result($rsConParametro, 0, 0);
$iAnoServidor       = date("Y");

$iAnoImplantacaoPCASP = 2013;

if ($lParametroUsaPCASP == 't') {

  $sSqlTipoInstituicao  = "select db21_tipoinstit ";
  $sSqlTipoInstituicao .= "  from configuracoes.db_config ";
  $sSqlTipoInstituicao .= " where codigo = (select db_config.codigo from configuracoes.db_config where db_config.prefeitura is true)";
  $rsTipoInstituicao    = db_query($connOrigem, $sSqlTipoInstituicao);
  if (pg_num_rows($rsTipoInstituicao) == 1 && isset($iAnoServidor)) {

    $iTipoInstituicao = pg_result($rsTipoInstituicao, 0, "db21_tipoinstit");
    if ($iTipoInstituicao == 101) {

      if ($iAnoServidor >= 2012) {
        $lUsarPcasp = true;
      }

    } else {

      /**
       * Ano de implacantacao do PCASP
       * - busca ano do arquivo config/pcasp.txt
       * - caso arquivo nao exista sera 2013
       */
      if ( file_exists(DB_LIBS . 'config/pcasp.txt') ) {
        $iAnoImplantacaoPCASP = trim(file_get_contents(DB_LIBS . 'config/pcasp.txt'));
      }

      /**
       * Usar PCASP:
       * - Ano do servidor e ano de implantação do PCASP tem que ser maior ou igual a 2013
       */
      if ($iAnoServidor >= $iAnoImplantacaoPCASP && $iAnoImplantacaoPCASP >= 2013) {
        $lUsarPcasp = true;
      }
    }
  }
}

$iAnoAnteriorImplantacaoPCASP = $iAnoImplantacaoPCASP - 1;

define("USE_PCASP", $lUsarPcasp);
define("ANO_IMPLANTACAO_PCASP", $iAnoImplantacaoPCASP);
define("ANO_ANTERIOR_IMPLANTACAO_PCASP", $iAnoAnteriorImplantacaoPCASP);

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
                                  where schema_name = '{$sSchema}' ";
  $rsSchemasAtual      = db_query($connDestino,$sSqlConsultaSchemasAtual);
  $iLinhasSchemasAtual = pg_num_rows($rsSchemasAtual);

  if ( $iLinhasSchemasAtual > 0 ) {

    $sSqlRenomeiaSchema = " ALTER SCHEMA {$sSchema} RENAME TO {$sBkpSchema} ";

    if ( !db_query($connDestino,$sSqlRenomeiaSchema)) {
      throw new Exception("ERRO-0: Erro ao renomear schema !".$sSqlRenomeiaSchema);
    }
  }

  // CRIA NOVO SCHEMA ***********************************************************************************************//


  $sSqlCriaSchema = "CREATE SCHEMA {$sSchema} ";

  if ( !db_query($connDestino,$sSqlCriaSchema) ) {
    throw new Exception("Falha ao criar schema {$sSchema} !");
  }


  // ****************************************************************************************************************//

  $sSqlAlteraSchemaAtual = "ALTER DATABASE \"".$ConfigConexaoDestino["dbname"]."\" SET search_path TO {$sSchema} ";

  if ( !db_query($connDestino,$sSqlAlteraSchemaAtual)) {
    throw new Exception("Falha ao alterar schema atual para {$sSchema} !");
  }

  $sSqlDefineSchemaAtual = "SET search_path TO {$sSchema} ";

  if ( !db_query($connDestino,$sSqlDefineSchemaAtual) ) {
    throw new Exception("Falha ao definir schema atual para {$sSchema} !");
  }


  $rsUpgradeDatabase = upgradeDatabase($connDestino,'.',$sSchema);

  if (!$rsUpgradeDatabase) {
    throw new Exception("Falha ao atualizar base de dados!");
  }


  /**
   *  O script abaixo corrige possíveis erros de base na tabela conplano, sendo elas podendo ser originárias
   *  da tabela orcdotacao ou orcreceita
   */
  $sSqlCorrigeConplano = " select distinct
                                  o58_codele,
                                  o58_anousu
                             from orcdotacao
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o58_codele
                                                 and c60_anousu = o58_anousu ) ";

  $rsCorrigeConplano      = db_query($connOrigem,$sSqlCorrigeConplano);
  $iLinhasCorrigeConplano = pg_num_rows($rsCorrigeConplano);


  for ( $iInd=0; $iInd < $iLinhasCorrigeConplano; $iInd++ ) {

    $oConplano = db_utils::fieldsMemory($rsCorrigeConplano,$iInd);

    $sSqlOrcElemento = " select *
                           from orcelemento
                          where o56_codele  = {$oConplano->o58_codele}
                            and o56_anousu >= {$oConplano->o58_anousu}
                       order by o56_anousu asc ";

    $rsOrcElemento      = db_query($connOrigem,$sSqlOrcElemento);
    $iLinhasOrcElemento = pg_num_rows($rsOrcElemento);


    /**
     *  Caso exista registros na orcelemento, então será inserido um registro na conplano com base nesse registro
     *  caso contrário será procurado na conplano algum registro da mesma conta em outro exercício.
     */
    if ( $iLinhasOrcElemento > 0 ) {

      $oOrcElemento = db_utils::fieldsMemory($rsOrcElemento,0);

      $sTabelaPlano = "conplano";
      if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
        $sTabelaPlano = "conplanoorcamento";
      }
      $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcElemento->o56_codele},
                                                     {$oConplano->o58_anousu},
                                                     '{$oOrcElemento->o56_elemento}',
                                                     '{$oOrcElemento->o56_descr}',
                                                     '{$oOrcElemento->o56_finali}',
                                                     1,
                                                     1
                                                   )";

      /**
       * Condicao comentada pois caso a tabela de destino contenha os dados, a rotina eh abortada
       */
      /*
      if (!db_query($connOrigem,$sSqlInsereConplano)) {
        throw new Exception("ERRO-0: 1 - Falha ao inserir na conplano $sSqlInsereConplano");
      }
      */

    } else {

      $sSqlConplano = "select *
                         from conplano
                        where c60_codcon = {$oConplano->o58_codele}";

      $rsConplano      = db_query($connOrigem,$sSqlConplano);
      $iLinhasConplano = pg_num_rows($rsConplano);

      if ($iLinhasConplano > 0) {

        $oConplanoOrigem    = db_utils::fieldsMemory($rsConplano,0);
        $sTabelaPlano = "conplano";
        if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
          $sTabelaPlano = "conplanoorcamento";
        }
        $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                       c60_anousu,
                                                       c60_estrut,
                                                       c60_descr,
                                                       c60_finali,
                                                       c60_codsis,
                                                       c60_codcla
                                                     ) values (
                                                       {$oConplanoOrigem->c60_codcon},
                                                       {$oConplano->o58_anousu},
                                                       '{$oConplanoOrigem->c60_estrut}',
                                                       '{$oConplanoOrigem->c60_descr}',
                                                       '{$oConplanoOrigem->c60_finali}',
                                                       {$oConplanoOrigem->c60_codsis},
                                                       {$oConplanoOrigem->c60_codcla}
                                                     )";

        /**
         * Condicao comentada pois caso a tabela de destino contenha os dados, a rotina eh abortada
         */
        /*
        if (!db_query($connOrigem,$sSqlInsereConplano)) {
          throw new Exception("ERRO-0: 2 - Falha ao inserir na conplano $sSqlInsereConplano");
        }
        */

      } else {
        throw new Exception("ERRO-0: Erro na correção da tabela conplano ");
      }
    }
  }


  /**
   *  Consulta os registros da orcreceita com possíveis erros de base
   */
  $sSqlCorrigeConplano = " select distinct
                                  o70_anousu,
                                  o70_codfon
                             from orcreceita
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o70_codfon
                                                 and c60_anousu = o70_anousu ) ";

  $rsCorrigeConplano      = db_query($connOrigem,$sSqlCorrigeConplano);
  $iLinhasCorrigeConplano = pg_num_rows($rsCorrigeConplano);


  for ( $iInd=0; $iInd < $iLinhasCorrigeConplano; $iInd++ ) {

    $oConplano = db_utils::fieldsMemory($rsCorrigeConplano,$iInd);



    $sSqlOrcFontes = " select *
                           from orcfontes
                          where o57_codfon  = {$oConplano->o70_codfon}
                            and o57_anousu >= {$oConplano->o70_anousu}
                       order by o57_anousu asc ";

    $rsOrcFontes      = db_query($connOrigem,$sSqlOrcFontes);
    $iLinhasOrcFontes = pg_num_rows($rsOrcFontes);


    /**
     *  Caso exista registros na orcfontes, então será inserido um registro na conplano com base nesse registro
     *  caso contrário será procurado na conplano algum registro da mesma conta em outro exercício.
     */

    if ( $iLinhasOrcFontes > 0 ) {

      $oOrcFontes = db_utils::fieldsMemory($rsOrcFontes,0);
      $sTabelaPlano = "conplano";
      if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
        $sTabelaPlano = "conplanoorcamento";
      }
      $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcFontes->o57_codfon},
                                                     {$oConplano->o70_anousu},
                                                     '{$oOrcFontes->o57_fonte}',
                                                     '{$oOrcFontes->o57_descr}',
                                                     '{$oOrcFontes->o57_finali}',
                                                     1,
                                                     1
                                                   )";

      /**
       * Condicao comentada pois caso a tabela de destino contenha os dados, a rotina eh abortada
       */
      /*
      if (!db_query($connOrigem,$sSqlInsereConplano)) {
        throw new Exception("ERRO-0: 3 - Falha ao inserir na conplano $sSqlInsereConplano");
      }
      */

    } else {

      $sSqlConplano = "select *
                         from conplano
                        where c60_codcon = {$oConplano->o70_codfon}";

      $rsConplano      = db_query($connOrigem,$sSqlConplano);
      $iLinhasConplano = pg_num_rows($rsConplano);

      if ($iLinhasConplano > 0 ) {

        $sTabelaPlano = "conplano";
        if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
          $sTabelaPlano = "conplanoorcamento";
        }
        $oConplanoOrigem    = db_utils::fieldsMemory($rsConplano,0);

        $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                       c60_anousu,
                                                       c60_estrut,
                                                       c60_descr,
                                                       c60_finali,
                                                       c60_codsis,
                                                       c60_codcla
                                                     ) values (
                                                       {$oConplanoOrigem->c60_codcon},
                                                       {$oConplano->o70_anousu},
                                                       '{$oConplanoOrigem->c60_estrut}',
                                                       '{$oConplanoOrigem->c60_descr}',
                                                       '{$oConplanoOrigem->c60_finali}',
                                                       {$oConplanoOrigem->c60_codsis},
                                                       {$oConplanoOrigem->c60_codcla}
                                                     )";

        /**
         * Condicao comentada pois caso a tabela de destino contenha os dados, a rotina eh abortada
         */
        /*
        if (!db_query($connOrigem,$sSqlInsereConplano)) {
          throw new Exception("ERRO-0: 4 - Falha ao inserir na conplano $sSqlInsereConplano");
        }
        */
      } else {
        throw new Exception("ERRO-0: 1 - Erro na correção da tabela conplano ");
      }
    }
  }


  $oTBInstituicoes                    = new tableDataManager($connDestino, 'instituicoes'                         , 'id', true, 500);
  $oTBOrgaos                          = new tableDataManager($connDestino, 'orgaos'                               , 'id', true, 500);
  $oTBUnidades                        = new tableDataManager($connDestino, 'unidades'                             , 'id', true, 500);
  $oTBProjetos                        = new tableDataManager($connDestino, 'projetos'                             , 'id', true, 500);
  $oTBFuncoes                         = new tableDataManager($connDestino, 'funcoes'                              , 'id', true, 500);
  $oTBSubFuncoes                      = new tableDataManager($connDestino, 'subfuncoes'                           , 'id', true, 500);
  $oTBProgramas                       = new tableDataManager($connDestino, 'programas'                            , 'id', true, 500);
  $oTBRecursos                        = new tableDataManager($connDestino, 'recursos'                             , 'id', true, 500);
  $oTBPlanoContas                     = new tableDataManager($connDestino, 'planocontas'                          , 'id', true, 500);
  $oTBReceitas                        = new tableDataManager($connDestino, 'receitas'                             , 'id', true, 500);
  $oTBReceitasMovimentacoes           = new tableDataManager($connDestino, 'receitas_movimentacoes'               , 'id', true, 500);
  $oTBDotacoes                        = new tableDataManager($connDestino, 'dotacoes'                             , 'id', true, 500);
  $oTBPessoas                         = new tableDataManager($connDestino, 'pessoas'                              , 'id', true, 500);
  $oTBEmpenhos                        = new tableDataManager($connDestino, 'empenhos'                             , 'id', true, 500);
  $oTBEmpenhosItens                   = new tableDataManager($connDestino, 'empenhos_itens'                       , 'id', true, 500);
  $oTBEmpenhosProcessos               = new tableDataManager($connDestino, 'empenhos_processos'                   , 'id', true, 500);
  $oTBEmpenhosMovimentacoes           = new tableDataManager($connDestino, 'empenhos_movimentacoes'               , 'id', true, 500);
  $oTBEmpenhosMovimentacoesTipos      = new tableDataManager($connDestino, 'empenhos_movimentacoes_tipos'         , 'id', true, 500);
  $oTBGlossarios                      = new tableDataManager($connDestino, 'glossarios'                           , 'id', true, 500);
  $oTBGlossariosTipos                 = new tableDataManager($connDestino, 'glossarios_tipos'                     , 'id', true, 500);
  $oTBServidores                      = new tableDataManager($connDestino, 'servidores'                           , ''  , true, 500);
  $oTBMovimentacoesServidores         = new tableDataManager($connDestino, 'servidor_movimentacoes'               , 'id', true, 500);
  $oTBFolhaPagamento                  = new tableDataManager($connDestino, 'folha_pagamento'                      , 'id', true, 500);
  $oTBAssentamentos                   = new tableDataManager($connDestino, 'assentamentos'                        , 'id', true, 500);
  $oTBClassificacaoCredor             = new tableDataManager($connDestino, 'classificacao_credores'               , 'id', true, 500);
  $oTBClassificacaoCredorMovimentacao = new tableDataManager($connDestino, 'classificacao_credores_movimentacoes' , 'id', true, 500);
  $oTBBens                            = new tableDataManager($connDestino, 'bens'                                 , 'id', true, 500);
  $oTBBemTipo                         = new tableDataManager($connDestino, 'bem_tipos'                            , 'id', true, 500);
  $oTBBemAquisicaoTipo                = new tableDataManager($connDestino, 'bem_aquisicao_tipos'                  , 'id', true, 500);
  $oTBDepartamento                    = new tableDataManager($connDestino, 'departamentos'                        , 'id', true, 500);
  $oTBDivisaoDepartamento             = new tableDataManager($connDestino, 'departamento_divisoes'                , 'id', true, 500);
  $oTBBemTipoDepreciacao              = new tableDataManager($connDestino, 'bem_tipo_depreciacoes'                , 'id', true, 500);
  $oTBBemClassificacao                = new tableDataManager($connDestino, 'bem_classificacoes'                   , 'id', true, 500);
  $oTBVeiculo                         = new tableDataManager($connDestino, 'veiculos'                             , 'id', true, 500);
  $oTBMarca                           = new tableDataManager($connDestino, 'marcas'                               , 'id', true, 500);
  $oTBModelo                          = new tableDataManager($connDestino, 'modelos'                              , 'id', true, 500);
  $oTBVeiculoTipo                     = new tableDataManager($connDestino, 'veiculo_tipos'                        , 'id', true, 500);
  $oTBVeiculoUtilizacao               = new tableDataManager($connDestino, 'veiculo_utilizacoes'                  , 'id', true, 500);

  /**
   *  Arrays utilizados  para  referenciar os respectivos códigos de origem aos IDs novos gerados.
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


  // TABELA DE IMPORTÇOES *******************************************************************************************//

  db_logTitulo(" CONFIGURA TABELA DE IMPORTAÇÃO",$sArquivoLog,$iParamLog);

  $sSqlInsereImportacoes = " INSERT INTO importacoes (data,hora)
                                              VALUES ('{$dtDataHoje}',
                                                      '$sHoraHoje') ";

  $rsInsereImportacoes   = db_query($connDestino,$sSqlInsereImportacoes);

  if ( !$rsInsereImportacoes ) {
    throw new Exception("ERRO-0: Erro ao inserir tabela de importações!");
  }

  // FIM TABELA DE IMPORTÇOES ***************************************************************************************//

  // INSTITUIÇÕES **************************************************************************************************//

  db_logTitulo(" IMPORTA INSTITUIÇÕES",$sArquivoLog,$iParamLog);

  /**
   * Consulta Instituições na base de origem
   */
  $sSqlInstit  = " select db_config.codigo   as codinstit, ";
  $sSqlInstit .= "        db_config.nomeinst as descricao  ";
  $sSqlInstit .= "   from db_config                        ";

  $rsInstit     = db_query($connOrigem,$sSqlInstit);
  $iRowsInstit = pg_num_rows($rsInstit);

  if ( $iRowsInstit ==  0 ) {
    throw new Exception('Nenhuma instituição encontrada!');
  }

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

    try {
      $oTBInstituicoes->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBInstituicoes->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado as instituições cadastradas na base de destino para que seja populado o array $aListaInstit
   *  com as instituições cadastradas sendo a variável indexada pelo código da instituição da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código da instituição de origem.
   */
  $sSqlListaInstitDestino  = " select *           ";
  $sSqlListaInstitDestino .= "  from instituicoes ";

  $rsListaInstitDestino    = db_query($connDestino,$sSqlListaInstitDestino);
  $iRowsListaInstitDestino = pg_num_rows($rsListaInstitDestino);

  if ( $iRowsListaInstitDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }


  for ( $iInd=0; $iInd < $iRowsListaInstitDestino; $iInd++ ) {

    $oInstitDestino = db_utils::fieldsMemory($rsListaInstitDestino,$iInd);
    $aListaInstit[$oInstitDestino->codinstit] = $oInstitDestino->id;

  }

  // FIM INSTITUIÇÕES ***********************************************************************************************//

  // ORGÃOS *********************************************************************************************************//


  db_logTitulo(" IMPORTA ORGÃOS",$sArquivoLog,$iParamLog);

  /**
   * Consulta Orgãos na base de origem
   */
  $sSqlOrgao  = " select o40_instit as codinstit,        ";
  $sSqlOrgao .= "        o40_orgao  as codorgao,         ";
  $sSqlOrgao .= "        o40_descr  as descricao,        ";
  $sSqlOrgao .= "        o40_anousu as exercicio         ";
  $sSqlOrgao .= "   from orcorgao                        ";

  $rsOrgao    = db_query($connOrigem,$sSqlOrgao);
  $iRowsOrgao = pg_num_rows($rsOrgao);

  if ( $iRowsOrgao ==  0 ) {
    throw new Exception('Nenhum orgão encontrado!');
  }

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

    try {
      $oTBOrgaos->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBOrgaos->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }



  /**
   *  É consultado os orgãos cadastrados na base de destino para que seja populado o array $aListaOrgao
   *  com os orgãos cadastrados sendo a variável indexada pelo código do orgão da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código do orgão de origem.
   */
  $sSqlListaOrgaoDestino  = " select *      ";
  $sSqlListaOrgaoDestino .= "   from orgaos ";

  $rsListaOrgaoDestino    = db_query($connDestino,$sSqlListaOrgaoDestino);
  $iRowsListaOrgaoDestino = pg_num_rows($rsListaOrgaoDestino);

  if ( $iRowsListaOrgaoDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaOrgaoDestino; $iInd++ ) {

    $oOrgaoDestino = db_utils::fieldsMemory($rsListaOrgaoDestino,$iInd);
    $aListaOrgao[$oOrgaoDestino->codorgao][$oOrgaoDestino->exercicio] = $oOrgaoDestino->id;

  }

  // FIM ORGÃOS *****************************************************************************************************//


  // UNIDADES *******************************************************************************************************//


  db_logTitulo(" IMPORTA UNIDADES",$sArquivoLog,$iParamLog);

  /**
   * Consulta Unidades na base de origem
   */
  $sSqlUnidade  = " select o41_instit  as codinstit,       ";
  $sSqlUnidade .= "        o41_orgao   as codorgao,        ";
  $sSqlUnidade .= "        o41_unidade as codunidade,      ";
  $sSqlUnidade .= "        o41_descr   as descricao,       ";
  $sSqlUnidade .= "        o41_anousu  as exercicio        ";
  $sSqlUnidade .= "   from orcunidade                      ";

  $rsUnidade    = db_query($connOrigem,$sSqlUnidade);
  $iRowsUnidade = pg_num_rows($rsUnidade);

  if ( $iRowsUnidade ==  0 ) {
    throw new Exception('Nenhuma unidade encontrada!');
  }

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

    try {
      $oTBUnidades->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBUnidades->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }

  /**
   *  É consultado as unidades cadastradas na base de destino para que seja populado o array $aListaUnidade
   *  com as unidades cadastradas sendo a variável indexada pelo código da unidade da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código da unidade de origem.
   */
  $sSqlListaUnidadeDestino  = " select *        ";
  $sSqlListaUnidadeDestino .= "   from unidades ";

  $rsListaUnidadeDestino    = db_query($connDestino,$sSqlListaUnidadeDestino);
  $iRowsListaUnidadeDestino = pg_num_rows($rsListaUnidadeDestino);

  if ( $iRowsListaUnidadeDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaUnidadeDestino; $iInd++ ) {

    $oUnidadeDestino = db_utils::fieldsMemory($rsListaUnidadeDestino,$iInd);
    $aListaUnidade[$oUnidadeDestino->codunidade][$oUnidadeDestino->exercicio] = $oUnidadeDestino->id;

  }

  // FIM UNIDADES ***************************************************************************************************//



  // PROJETOS *******************************************************************************************************//

  db_logTitulo(" IMPORTA PROJETOS",$sArquivoLog,$iParamLog);

  /**
   * Consulta Projetos na base de origem
   */
  $sSqlProjeto  = " select o55_instit   as codinstit,      ";
  $sSqlProjeto .= "        o55_tipo     as tipo,           ";
  $sSqlProjeto .= "        o55_projativ as codprojeto,     ";
  $sSqlProjeto .= "        o55_descr    as descricao,      ";
  $sSqlProjeto .= "        o55_anousu   as exercicio       ";
  $sSqlProjeto .= "   from orcprojativ                     ";

  $rsProjeto    = db_query($connOrigem,$sSqlProjeto);
  $iRowsProjeto = pg_num_rows($rsProjeto);

  if ( $iRowsProjeto ==  0 ) {
    throw new Exception('Nenhum projeto encontrado!');
  }

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

    try {
      $oTBProjetos->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBProjetos->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado os projetos cadastrados na base de destino para que seja populado o array $aListaProjeto
   *  com os projetos cadastrados sendo a variável indexada pelo código do projeto da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código do projeto de origem.
   */
  $sSqlListaProjetoDestino  = " select *        ";
  $sSqlListaProjetoDestino .= "   from projetos ";

  $rsListaProjetoDestino    = db_query($connDestino,$sSqlListaProjetoDestino);
  $iRowsListaProjetoDestino = pg_num_rows($rsListaProjetoDestino);

  if ( $iRowsListaProjetoDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaProjetoDestino; $iInd++ ) {

    $oProjetoDestino = db_utils::fieldsMemory($rsListaProjetoDestino,$iInd);
    $aListaProjeto[$oProjetoDestino->codprojeto][$oProjetoDestino->exercicio] = $oProjetoDestino->id;

  }

  // FIM PROJETOS ***************************************************************************************************//


  // FUNÇÕES ********************************************************************************************************//

  db_logTitulo(" IMPORTA FUNÇÕES",$sArquivoLog,$iParamLog);

  /**
   * Consulta Funções na base de origem
   */
  $sSqlFuncao  = " select o52_funcao as codfuncao, ";
  $sSqlFuncao .= "        o52_descr  as descricao  ";
  $sSqlFuncao .= "   from orcfuncao                ";

  $rsFuncao    = db_query($connOrigem,$sSqlFuncao);
  $iRowsFuncao = pg_num_rows($rsFuncao);

  if ( $iRowsFuncao ==  0 ) {
    throw new Exception('Nenhuma função encontrada!');
  }

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

    try {
      $oTBFuncoes->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBFuncoes->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado as funções cadastradas na base de destino para que seja populado o array $aListaFuncao
   *  com as funções cadastradas sendo a variável indexada pelo código da função da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código da função de origem.
   */
  $sSqlListaFuncaoDestino  = " select *        ";
  $sSqlListaFuncaoDestino .= "   from funcoes  ";

  $rsListaFuncaoDestino    = db_query($connDestino,$sSqlListaFuncaoDestino);
  $iRowsListaFuncaoDestino = pg_num_rows($rsListaFuncaoDestino);

  if ( $iRowsListaFuncaoDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaFuncaoDestino; $iInd++ ) {

    $oFuncaoDestino = db_utils::fieldsMemory($rsListaFuncaoDestino,$iInd);
    $aListaFuncao[$oFuncaoDestino->codfuncao] = $oFuncaoDestino->id;

  }

  // FIM FUNÇÕES ****************************************************************************************************//



  // SUBFUNÇÕES *****************************************************************************************************//

  db_logTitulo(" IMPORTA SUBFUNÇÕES",$sArquivoLog,$iParamLog);

  /**
   * Consulta Funções na base de origem
   */
  $sSqlSubFuncao  = " select o53_subfuncao as codsubfuncao, ";
  $sSqlSubFuncao .= "        o53_descr     as descricao     ";
  $sSqlSubFuncao .= "   from orcsubfuncao                   ";

  $rsSubFuncao    = db_query($connOrigem,$sSqlSubFuncao);
  $iRowsSubFuncao = pg_num_rows($rsSubFuncao);

  if ( $iRowsSubFuncao ==  0 ) {
    throw new Exception('Nenhuma SubFunção encontrada!');
  }

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

    try {
      $oTBSubFuncoes->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBSubFuncoes->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado as subfunções cadastradas na base de destino para que seja populado o array $aListaSubFuncao
   *  com as subfunções cadastradas sendo a variável indexada pelo código da subfunção da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código da subfunção de origem.
   */
  $sSqlListaSubFuncaoDestino  = " select *           ";
  $sSqlListaSubFuncaoDestino .= "   from subfuncoes  ";

  $rsListaSubFuncaoDestino    = db_query($connDestino,$sSqlListaSubFuncaoDestino);
  $iRowsListaSubFuncaoDestino = pg_num_rows($rsListaSubFuncaoDestino);

  if ( $iRowsListaSubFuncaoDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaSubFuncaoDestino; $iInd++ ) {

    $oSubFuncaoDestino = db_utils::fieldsMemory($rsListaSubFuncaoDestino,$iInd);
    $aListaSubFuncao[$oSubFuncaoDestino->codsubfuncao] = $oSubFuncaoDestino->id;

  }

  // FIM SUBFUNÇÕES *************************************************************************************************//




  // PROGRAMAS ******************************************************************************************************//

  db_logTitulo(" IMPORTA PROGRAMAS",$sArquivoLog,$iParamLog);

  /**
   * Consulta Programas na base de origem
   */
  $sSqlPrograma  = " select o54_programa as codprograma,    ";
  $sSqlPrograma .= "        o54_descr    as descricao,      ";
  $sSqlPrograma .= "        o54_anousu    as exercicio      ";
  $sSqlPrograma .= "   from orcprograma                     ";

  $rsPrograma    = db_query($connOrigem,$sSqlPrograma);
  $iRowsPrograma = pg_num_rows($rsPrograma);

  if ( $iRowsPrograma ==  0 ) {
    throw new Exception('Nenhum programa encontrado!');
  }

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

    try {
      $oTBProgramas->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBProgramas->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado os programas cadastrados na base de destino para que seja populado o array $aListaPrograma
   *  com os programas cadastrados sendo a variável indexada pelo código do programa da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código do programa de origem.
   */
  $sSqlListaProgramaDestino  = " select *         ";
  $sSqlListaProgramaDestino .= "   from programas ";

  $rsListaProgramaDestino    = db_query($connDestino,$sSqlListaProgramaDestino);
  $iRowsListaProgramaDestino = pg_num_rows($rsListaProgramaDestino);

  if ( $iRowsListaProgramaDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

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
  $sSqlRecurso  = " select o15_codigo as codrecurso, ";
  $sSqlRecurso .= "        o15_descr  as descricao   ";
  $sSqlRecurso .= "   from orctiporec                ";

  $rsRecurso    = db_query($connOrigem,$sSqlRecurso);
  $iRowsRecurso = pg_num_rows($rsRecurso);

  if ( $iRowsRecurso ==  0 ) {
    throw new Exception('Nenhum recurso encontrado!');
  }

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

    try {
      $oTBRecursos->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBRecursos->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado os recursos cadastrados na base de destino para que seja populado o array $aListaRecurso
   *  com os recursos cadastrados sendo a variável indexada pelo código do recurso da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código do recurso de origem.
   */
  $sSqlListaRecursoDestino  = " select *         ";
  $sSqlListaRecursoDestino .= "   from recursos ";

  $rsListaRecursoDestino    = db_query($connDestino,$sSqlListaRecursoDestino);
  $iRowsListaRecursoDestino = pg_num_rows($rsListaRecursoDestino);

  if ( $iRowsListaRecursoDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaRecursoDestino; $iInd++ ) {

    $oRecursoDestino = db_utils::fieldsMemory($rsListaRecursoDestino,$iInd);
    $aListaRecurso[$oRecursoDestino->codrecurso] = $oRecursoDestino->id;
  }

  // FIM RECURSOS ***************************************************************************************************//



  // PLANOCONTAS ****************************************************************************************************//

  db_logTitulo(" IMPORTA PLANOCONTAS",$sArquivoLog,$iParamLog);

  $sSqlPlanoConta  = " select conplano.c60_codcon as codcon,     ";
  $sSqlPlanoConta .= "        conplano.c60_estrut as estrutural, ";
  $sSqlPlanoConta .= "        conplano.c60_descr  as descricao,  ";
  $sSqlPlanoConta .= "        conplano.c60_anousu as exercicio   ";
  $sSqlPlanoConta .= "   from conplano                           ";

  if (USE_PCASP || file_exists(DB_LIBS . 'config/pcasp.txt')) {

    $sSqlPlanoConta  = "   select distinct codcon,                                                                ";
    $sSqlPlanoConta .= "        estrutural,                                                                       ";
    $sSqlPlanoConta .= "        descricao,                                                                        ";
    $sSqlPlanoConta .= "        exercicio                                                                         ";
    $sSqlPlanoConta .= "   from (                                                                                 ";
    $sSqlPlanoConta .= "                                                                                          ";
    $sSqlPlanoConta .= " select conplano.c60_codcon as codcon,                                                    ";
    $sSqlPlanoConta .= "        conplano.c60_estrut as estrutural,                                                ";
    $sSqlPlanoConta .= "        conplano.c60_descr  as descricao,                                                 ";
    $sSqlPlanoConta .= "        conplano.c60_anousu as exercicio                                                  ";
    $sSqlPlanoConta .= "   from conplano where c60_anousu <= " . ANO_ANTERIOR_IMPLANTACAO_PCASP . " union         ";
    $sSqlPlanoConta .= " select conplanoorcamento.c60_codcon as codcon,                                           ";
    $sSqlPlanoConta .= "        conplanoorcamento.c60_estrut as estrutural,                                       ";
    $sSqlPlanoConta .= "        conplanoorcamento.c60_descr  as descricao,                                        ";
    $sSqlPlanoConta .= "        conplanoorcamento.c60_anousu as exercicio                                         ";
    $sSqlPlanoConta .= "   from conplanoorcamento where c60_anousu > " . ANO_ANTERIOR_IMPLANTACAO_PCASP . ") as x ";

  }

  $rsPlanoConta    = db_query($connOrigem,$sSqlPlanoConta);
  $iRowsPlanoConta = pg_num_rows($rsPlanoConta);

  if ( $iRowsPlanoConta ==  0 ) {
    throw new Exception('Nenhum recurso encontrado!');
  }

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

    try {
      $oTBPlanoContas->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

  }

  /**
   *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
   *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
   */
  try {
    $oTBPlanoContas->persist();
  } catch ( Exception $eException ) {
    throw new Exception("ERRO-0: {$eException->getMessage()}");
  }


  /**
   *  É consultado os planocontas cadastrados na base de destino para que seja populado o array $aListaPlanoConta
   *  com os planocontas cadastrados sendo a variável indexada pelo código do planoconta da base de origem.
   *  Essa variável será utilizada por todo o fonte para identificar o código do planoconta de origem.
   */
  $sSqlListaPlanoContaDestino  = " select *           ";
  $sSqlListaPlanoContaDestino .= "   from planocontas ";

  $rsListaPlanoContaDestino    = db_query($connDestino,$sSqlListaPlanoContaDestino);
  $iRowsListaPlanoContaDestino = pg_num_rows($rsListaPlanoContaDestino);

  if ( $iRowsListaPlanoContaDestino == 0 ) {
    throw new Exception('Nenhum registro encontrado');
  }

  for ( $iInd=0; $iInd < $iRowsListaPlanoContaDestino; $iInd++ ) {

    $oPlanoContaDestino = db_utils::fieldsMemory($rsListaPlanoContaDestino,$iInd);
    $aListaPlanoConta[$oPlanoContaDestino->codcon][$oPlanoContaDestino->exercicio] = $oPlanoContaDestino->id;
  }

  // FIM PLANOCONTAS ************************************************************************************************//


  // RECEITAS *******************************************************************************************************//

  db_logTitulo(" IMPORTA RECEITAS",$sArquivoLog,$iParamLog);

  if (IMPORTAR_RECEITAS) {
    /**
     * Consulta Receitas na base de origem
     */
    $sSqlReceita  = " select o70_codrec as codreceita,       ";
    $sSqlReceita .= "        o70_codfon as codcon,           ";
    $sSqlReceita .= "        o70_anousu as exercicio,        ";
    $sSqlReceita .= "        o70_codigo as codrecurso,       ";
    $sSqlReceita .= "        o70_instit as codinstit,        ";
    $sSqlReceita .= "        o70_valor  as previsaoinicial   ";
    $sSqlReceita .= "   from orcreceita                      ";
    $sSqlReceita .= "   where o70_anousu >= {$iExercicioBase}";

    $rsReceita    = db_query($connOrigem,$sSqlReceita);
    $iRowsReceita = pg_num_rows($rsReceita);

    if ( $iRowsReceita ==  0 ) {
      throw new Exception('Nenhum recurso encontrado!');
    }

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
        throw new Exception("ERRO-0: Plano de Contas não encontrado CODCON: $oReceita->codcon  EXERCICIO: $oReceita->exercicio RECEITA: $oReceita->codreceita");
      }

      $oReceita->recurso_id     = $aListaRecurso[$oReceita->codrecurso];
      $oReceita->planoconta_id  = $aListaPlanoConta[$oReceita->codcon][$oReceita->exercicio];
      $oReceita->instituicao_id = $aListaInstit[$oReceita->codinstit];

      $oTBReceitas->setByLineOfDBUtils($oReceita);

      try {
        $oTBReceitas->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }

    }

    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBReceitas->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }


    /**
     *  É consultado as receitas cadastradas na base de destino para que seja populado o array $aListaReceita
     *  com as receitas cadastradas sendo a variável indexada pelo código do receita da base de origem.
     *  Essa variável será utilizada por todo o fonte para identificar o código da receita de origem.
     */
    $sSqlListaReceitaDestino  = " select *        ";
    $sSqlListaReceitaDestino .= "   from receitas ";

    $rsListaReceitaDestino    = db_query($connDestino,$sSqlListaReceitaDestino);
    $iRowsListaReceitaDestino = pg_num_rows($rsListaReceitaDestino);

    if ( $iRowsListaReceitaDestino == 0 ) {
      throw new Exception('Nenhum registro encontrado');
    }

    for ( $iInd=0; $iInd < $iRowsListaReceitaDestino; $iInd++ ) {

      $oReceitaDestino = db_utils::fieldsMemory($rsListaReceitaDestino,$iInd);
      $aListaReceita[$oReceitaDestino->codreceita][$oReceitaDestino->exercicio] = $oReceitaDestino->id;
    }

    // FIM RECEITAS ***************************************************************************************************//


    // MOVIMENTAÇÕES RECEITAS *****************************************************************************************//

    db_logTitulo(" IMPORTA MOVIMENTAÇÕES DAS RECEITAS",$sArquivoLog,$iParamLog);

    /**
     * Consulta Preparada para execução da função fc_receitasaldo na base de origem
     */

    $sSqlReceitaMovimentacao  = " prepare stmt_receitasaldo(integer, integer) as ";
    $sSqlReceitaMovimentacao .= " select cast(                                                                            ";
    $sSqlReceitaMovimentacao .= "           substr(                                                                          ";
    $sSqlReceitaMovimentacao .= "           fc_receitasaldo($1,                                                              ";
    $sSqlReceitaMovimentacao .= "                           $2,                                                              ";
    $sSqlReceitaMovimentacao .= "                           3,                                                               ";
    $sSqlReceitaMovimentacao .= "                           current_date,                                                    ";
    $sSqlReceitaMovimentacao .= "                           current_date),41,13) as numeric(15,2));                          ";

    /**
     * Consulta ReceitasMovimentacoes na base de origem
     */

    $sRegraArrecadacaoReceita  = " (case when o70_anousu < 2013 or (substr(o57_fonte , 1, 1) = '4') ";
    $sRegraArrecadacaoReceita .= "       then (case when c57_sequencial = 100 then c70_valor";
    $sRegraArrecadacaoReceita .= "                  when c57_sequencial = 101 then c70_valor * -1";
    $sRegraArrecadacaoReceita .= "                  else 0 end) ";
    $sRegraArrecadacaoReceita .= "       when o70_anousu >= 2013 then ";
    $sRegraArrecadacaoReceita .= "        (case when substr(o57_fonte, 1, 1) = '9' then  ";
    $sRegraArrecadacaoReceita .= "            (case  when c57_sequencial = 100 then c70_valor * -1";
    $sRegraArrecadacaoReceita .= "                   when c57_sequencial = 101 then c70_valor ";
    $sRegraArrecadacaoReceita .= "              end)";
    $sRegraArrecadacaoReceita .= "         end)";
    $sRegraArrecadacaoReceita .= "    end) ";

    $sSqlReceitaMovimentacao .= " select o70_codrec as codreceita,                                                           ";
    $sSqlReceitaMovimentacao .= "        o70_anousu as exercicio,                                                            ";
    $sSqlReceitaMovimentacao .= "        c70_data   as data,                                                                 ";
    $sSqlReceitaMovimentacao .= "        coalesce(sum( {$sRegraArrecadacaoReceita}), 0.0) as valor,                                         ";

    $sSqlReceitaMovimentacao .= "        coalesce(sum(case                                                                            ";
    $sSqlReceitaMovimentacao .= "            when c57_sequencial = 110  then c70_valor                                       ";
    $sSqlReceitaMovimentacao .= "            when c57_sequencial = 111 then (c70_valor * -1)                                 ";
    $sSqlReceitaMovimentacao .= "            else 0                                                                          ";
    $sSqlReceitaMovimentacao .= "            end ), 0.0) as previsaoadicional,                                                     ";

    $sSqlReceitaMovimentacao .= "        coalesce(sum(case                                                                            ";
    $sSqlReceitaMovimentacao .= "            when c57_sequencial = 58   then c70_valor                                       ";
    $sSqlReceitaMovimentacao .= "            when c57_sequencial = 104 then (c70_valor * -1)                                 ";
    $sSqlReceitaMovimentacao .= "            else 0                                                                          ";
    $sSqlReceitaMovimentacao .= "            end ), 0.0) as previsao_atualizada                                                    ";

    $sSqlReceitaMovimentacao .= "  from orcreceita                                                                           ";
    $sSqlReceitaMovimentacao .= "       inner join conlancamrec   on conlancamrec.c74_codrec = orcreceita.o70_codrec         ";
    $sSqlReceitaMovimentacao .= "                                and conlancamrec.c74_anousu = orcreceita.o70_anousu         ";
    $sSqlReceitaMovimentacao .= "       inner join orcfontes      on orcreceita.o70_codfon   = orcfontes.o57_codfon          ";
    $sSqlReceitaMovimentacao .= "                                and orcreceita.o70_anousu   = orcfontes.o57_anousu          ";
    $sSqlReceitaMovimentacao .= "       inner join conlancam      on conlancam.c70_codlan    = conlancamrec.c74_codlan       ";
    $sSqlReceitaMovimentacao .= "       inner join conlancamdoc   on conlancamdoc.c71_codlan = conlancam.c70_codlan          ";
    $sSqlReceitaMovimentacao .= "       inner join conhistdoc     on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc         ";
    $sSqlReceitaMovimentacao .= "       inner join conhistdoctipo on conhistdoc.c53_tipo     = conhistdoctipo.c57_sequencial ";
    $sSqlReceitaMovimentacao .= "   where o70_anousu >= {$iExercicioBase}";
    $sSqlReceitaMovimentacao .= " group by o70_codrec,o70_anousu,c70_data                                                    ";

    $rsReceitaMovimentacao    = db_query($connOrigem,$sSqlReceitaMovimentacao);
    $iRowsReceitaMovimentacao = pg_num_rows($rsReceitaMovimentacao);

    if ( $iRowsReceitaMovimentacao ==  0 ) {
      throw new Exception('Nenhum recurso encontrado!');
    }

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
      $rsReceitaSaldo = db_query($connOrigem, $sSqlReceitaSaldo);
      $iRowsReceitaSaldo = pg_num_rows($rsReceitaSaldo);

      if ( $iRowsReceitaSaldo ==  0 ) {
        $oReceitaMovimentacao->valor_previsao_atualizada = 0;
      } else {
        $oReceitaMovimentacao->valor_previsao_atualizada = pg_result($rsReceitaSaldo, 0, 0);
      }

      $oReceitaMovimentacao->receita_id = $aListaReceita[$oReceitaMovimentacao->codreceita][$oReceitaMovimentacao->exercicio];

      $oTBReceitasMovimentacoes->setByLineOfDBUtils($oReceitaMovimentacao);

      try {
        $oTBReceitasMovimentacoes->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }

    }

    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBReceitasMovimentacoes->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }


    // ACERTA TABELA receitas_movimentacoes ***************************************************************************//


    $sSqlAcertaRecMov = " UPDATE receitas_movimentacoes SET valor = ( valor * -1 )
                           WHERE receita_id in ( select distinct receitas.id
                                                   from receitas
                                                        inner join planocontas on planocontas.id = receitas.planoconta_id
                                                  where planocontas.estrutural like '9%'
                                                       or planocontas.estrutural like '49%')";

    $rsAcertaRecMov = db_query($connDestino,$sSqlAcertaRecMov);

    if ( !$rsAcertaRecMov ) {
      throw new Exception("ERRO-0: Erro ao acertar tabela receitas_movimentacoes !");
    }
  }
  // ****************************************************************************************************************//
  // FIM MOVIMENTAÇÕES RECEITAS *************************************************************************************//


  // DOTAÇÕES *******************************************************************************************************//


  db_logTitulo(" IMPORTA DOTAÇÕES",$sArquivoLog,$iParamLog);

  if (IMPORTAR_DOTACAO) {
    /**
     * Consulta Dotacaos na base de origem
     */
    $sSqlDotacao = " select o58_coddot    as coddotacao,    ";
    $sSqlDotacao .= "        o58_orgao     as codorgao,      ";
    $sSqlDotacao .= "        o58_unidade   as codunidade,    ";
    $sSqlDotacao .= "        o58_funcao    as codfuncao,     ";
    $sSqlDotacao .= "        o58_subfuncao as codsubfuncao,  ";
    $sSqlDotacao .= "        o58_programa  as codprograma,   ";
    $sSqlDotacao .= "        o58_projativ  as codprojeto,    ";
    $sSqlDotacao .= "        o58_codigo    as codrecurso,    ";
    $sSqlDotacao .= "        o58_instit    as codinstit,     ";
    $sSqlDotacao .= "        o58_anousu    as exercicio,     ";
    $sSqlDotacao .= "        o58_codigo    as recurso,       ";
    $sSqlDotacao .= "        o58_codele    as codcon         ";
    $sSqlDotacao .= "   from orcdotacao                      ";

    $rsDotacao    = db_query($connOrigem, $sSqlDotacao);
    $iRowsDotacao = pg_num_rows($rsDotacao);

    if ($iRowsDotacao == 0) {
      throw new Exception('Nenhum recurso encontrado!');
    }

    db_logNumReg($iRowsDotacao, $sArquivoLog, $iParamLog);

    /**
     *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
     *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
     *  o método persist que insere fisicamente os registros na base de dados através do COPY.
     */
    for ($iInd = 0; $iInd < $iRowsDotacao; $iInd++) {

      $oDotacao = db_utils::fieldsMemory($rsDotacao, $iInd);

      logProcessamento($iInd, $iRowsDotacao, $iParamLog);

      if (!isset($aListaProjeto[$oDotacao->codprojeto][$oDotacao->exercicio])) {
        $sMsg = "ERRO-0: Projeto não encontrado PROJETO: $oDotacao->codprojeto  EXERCICIO: $oDotacao->exercicio ";
        $sMsg .= "DOTAÇÃO: $oDotacao->coddotacao ";
        throw new Exception($sMsg);
      }

      if (!isset($aListaPlanoConta[$oDotacao->codcon][$oDotacao->exercicio])) {
        $sMsg = "ERRO-0: Plano de Conta não encontrado CODCON: $oDotacao->codcon EXERCICIO: $oDotacao->exercicio ";
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

      try {
        $oTBDotacoes->insertValue();
      } catch (Exception $eException) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
    }

    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBDotacoes->persist();
    } catch (Exception $eException) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    /**
     *  É consultado as dotacoes cadastradas na base de destino para que seja populado o array $aListaDotacao
     *  com as dotacoes cadastradas sendo a variável indexada pelo código do receita da base de origem.
     *  Essa variável será utilizada por todo o fonte para identificar o código da dotacao de origem.
     */
    $sSqlListaDotacaoDestino = " select *        ";
    $sSqlListaDotacaoDestino .= "   from dotacoes ";

    $rsListaDotacaoDestino    = db_query($connDestino, $sSqlListaDotacaoDestino);
    $iRowsListaDotacaoDestino = pg_num_rows($rsListaDotacaoDestino);

    if ($iRowsListaDotacaoDestino == 0) {
      throw new Exception('Nenhum registro encontrado');
    }

    for ($iInd = 0; $iInd < $iRowsListaDotacaoDestino; $iInd++) {

      $oDotacaoDestino                                                          = db_utils::fieldsMemory($rsListaDotacaoDestino, $iInd);
      $aListaDotacao[$oDotacaoDestino->coddotacao][$oDotacaoDestino->exercicio] = $oDotacaoDestino->id;
    }
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

  // CLASSIFICAÇÃO DE CREDORES***************************************************************************************//
  db_logTitulo(" IMPORTA CLASSIFICAÇÃO CREDORES",$sArquivoLog,$iParamLog);

  /**
   * Consulta Classificação de Credores na base de origem
   */
  $sSqlClassificacaoCredor  = " select distinct cc30_codigo as codigo, ";
  $sSqlClassificacaoCredor .= "   cc30_descricao as descricao          ";
  $sSqlClassificacaoCredor .= " from classificacaocredores  order by cc30_codigo ";

  $rsClassificacaoCredor    = db_query($connOrigem,$sSqlClassificacaoCredor);
  $iRowsClassificaocaoCredor = pg_num_rows($rsClassificacaoCredor);

  db_logNumReg($iRowsClassificaocaoCredor,$sArquivoLog,$iParamLog);

  if ( $iRowsClassificaocaoCredor ==  0 ) {

    throw new Exception('Nenhuma classificacao de credor encontrada!');
  } else {

    /**
     *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
     *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
     *  o método persist que insere fisicamente os registros na base de dados através do COPY.
     */
    for ( $iInd=0; $iInd < $iRowsClassificaocaoCredor; $iInd++ ) {

      $oClassificacaoCredor = db_utils::fieldsMemory($rsClassificacaoCredor,$iInd);

      $oTBClassificacaoCredor->setByLineOfDBUtils($oClassificacaoCredor);

      logProcessamento($iInd,$iRowsClassificaocaoCredor,$iParamLog);

      try {

        $oTBClassificacaoCredor->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
    }

    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBClassificacaoCredor->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
  }
  // FIM CLASSIFICAÇÃO DE CREDORES***********************************************************************************//

  // EMPENHOS *******************************************************************************************************//
  db_logTitulo(" IMPORTA EMPENHOS",$sArquivoLog,$iParamLog);

  if (IMPORTAR_EMPENHO) {

    /**
     * Consulta Empenhos na base de origem
     */
    $sSqlEmpenho = " select distinct e60_numemp as codempenho,                                                      ";
    $sSqlEmpenho .= "        e60_codemp as codigo,                                                                   ";
    $sSqlEmpenho .= "        e60_anousu as exercicio,                                                                ";
    $sSqlEmpenho .= "        e60_instit as codinstit,                                                                ";
    $sSqlEmpenho .= "        e60_emiss  as dataemissao,                                                              ";
    $sSqlEmpenho .= "        e60_vencim as datavencimento,                                                           ";
    $sSqlEmpenho .= "        e60_coddot as coddotacao,                                                               ";
    $sSqlEmpenho .= "        e60_vlremp as valor,                                                                    ";
    $sSqlEmpenho .= "        e60_vlrpag as valor_pago,                                                               ";
    $sSqlEmpenho .= "        e60_vlrliq as valor_liquidado,                                                          ";
    $sSqlEmpenho .= "        e60_vlranu as valor_anulado,                                                            ";
    $sSqlEmpenho .= "        e60_resumo as resumo,                                                                   ";
    $sSqlEmpenho .= "        z01_numcgm as numcgm,                                                                   ";
    $sSqlEmpenho .= "        coalesce(nullif(trim(z01_cgccpf),''),'0') as cgccpf,                                    ";
    $sSqlEmpenho .= "        case                                                                                    ";
    $sSqlEmpenho .= "           when c60_codcon is not null then c60_codcon                                          ";
    $sSqlEmpenho .= "           else o58_codele                                                                      ";
    $sSqlEmpenho .= "        end as codcon,                                                                          ";
    $sSqlEmpenho .= "        z01_nome    as nome,                                                                    ";
    $sSqlEmpenho .= "        e61_autori  as codautoriza,                                                             ";
    $sSqlEmpenho .= "        e60_numerol as numero_licitacao,                                                        ";
    $sSqlEmpenho .= "        cc31_justificativa as justificativa,                                                        ";
    $sSqlEmpenho .= "        cc31_classificacaocredores as classificacaocredores_codigo,                                                        ";
    $sSqlEmpenho .= "        pc50_descr  as descrtipocompra                                                          ";
    $sSqlEmpenho .= "   from empempenho                                                                              ";
    $sSqlEmpenho .= "        inner join cgm          on cgm.z01_numcgm           = empempenho.e60_numcgm             ";
    $sSqlEmpenho .= "        inner join orcdotacao   on orcdotacao.o58_coddot    = empempenho.e60_coddot             ";
    $sSqlEmpenho .= "        inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom             ";
    $sSqlEmpenho .= "                               and orcdotacao.o58_anousu    = empempenho.e60_anousu             ";
    $sSqlEmpenho .= "        left join classificacaocredoresempenho on
                                classificacaocredoresempenho.cc31_sequencial = (
                                select
                                  classificacaocredoresempenho.cc31_sequencial
                                from
                                  classificacaocredoresempenho
                                where
                                  classificacaocredoresempenho.cc31_empempenho = empempenho.e60_numemp
                                LIMIT 1 )
                          ";
    $sSqlEmpenho .= "        left join (  select distinct on (e.e64_numemp) e.e64_numemp, e.e64_codele               ";
    $sSqlEmpenho .= "                       from empelemento e                                                       ";
    $sSqlEmpenho .= "                   order by e.e64_numemp, e.e64_codele ) as x                                   ";
    $sSqlEmpenho .= "                                on x.e64_numemp           = empempenho.e60_numemp               ";
    $sSqlEmpenho .= "        left join conplano      on conplano.c60_codcon    = x.e64_codele                        ";
    $sSqlEmpenho .= "                               and conplano.c60_anousu    = empempenho.e60_anousu               ";
    $sSqlEmpenho .= "        left join empempaut     on empempaut.e61_numemp   = empempenho.e60_numemp               ";
    $sSqlEmpenho .= "  where exists (  select 1                                                                      ";
    $sSqlEmpenho .= "                    from conlancamemp                                                           ";
    $sSqlEmpenho .= "                         inner join conlancam on conlancam.c70_codlan = conlancamemp.c75_codlan ";
    $sSqlEmpenho .= "                   where c75_numemp = e60_numemp                                                ";
    $sSqlEmpenho .= "                     and c70_data >= '{$iExercicioBase}-01-01'::date )                          ";
    $sSqlEmpenho .= "    and exists (  select 1                                                                      ";
    $sSqlEmpenho .= "                    from empempitem                                                             ";
    $sSqlEmpenho .= "                   where empempitem.e62_numemp = empempenho.e60_numemp )                        ";

    $rsEmpenho    = db_query($connOrigem, $sSqlEmpenho);
    $iRowsEmpenho = pg_num_rows($rsEmpenho);

    if ($iRowsEmpenho == 0) {
      throw new Exception('Nenhum recurso encontrado!');
    }

    db_logNumReg($iRowsEmpenho, $sArquivoLog, $iParamLog);

    /**
     *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
     *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
     *  o método persist que insere fisicamente os registros na base de dados através do COPY.
     */
    for ($iInd = 0; $iInd < $iRowsEmpenho; $iInd++) {

      $oEmpenho = db_utils::fieldsMemory($rsEmpenho, $iInd);

      logProcessamento($iInd, $iRowsEmpenho, $iParamLog);

      $sSqlPessoas = "
      select
        id
      from
        pessoas
      where
        codpessoa = {$oEmpenho->numcgm}
    ";

      $rsPessoas = db_query($connDestino, $sSqlPessoas);

      if (pg_num_rows($rsPessoas) > 0) {

        $iIdPessoa = db_utils::fieldsMemory($rsPessoas, 0)->id;
      } else {

        $oTBPessoas->id        = '';
        $oTBPessoas->codpessoa = $oEmpenho->numcgm;
        $oTBPessoas->nome      = $oEmpenho->nome;
        $oTBPessoas->cpfcnpj   = $oEmpenho->cgccpf;

        try {
          $oTBPessoas->insertValue();
          $oTBPessoas->persist();
        } catch (Exception $eException) {
          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }

        $iIdPessoa = $oTBPessoas->getLastPk();
      }

      if (!isset($aListaDotacao[$oEmpenho->coddotacao][$oEmpenho->exercicio])) {
        $sMsg = "ERRO-0: Dotação não encontrada DOTAÇÃO: $oEmpenho->coddotacao  EXERCICIO: $oEmpenho->exercicio ";
        $sMsg .= "NUMEMP  $oEmpenho->codempenho ";
        throw new Exception($sMsg);
      }

      if (!isset($aListaPlanoConta[$oEmpenho->codcon][$oEmpenho->exercicio])) {
        $sMsg = "ERRO-0: Plano de Conta não encontrado CODCON: $oEmpenho->codcon EXERCICIO: $oEmpenho->exercicio ";
        $sMsg .= "NUMEMP: $oEmpenho->codempenho ";
        throw new Exception($sMsg);
      }

      $sTipoCompra = "";

      if (trim($oEmpenho->codautoriza) != '') {

        $sSqlTipoCompra = " select * ";
        $sSqlTipoCompra .= "   from empautitem ";
        $sSqlTipoCompra .= "        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen          ";
        $sSqlTipoCompra .= "                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori          ";
        $sSqlTipoCompra .= "        inner join liclicitem           on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_sequen";
        $sSqlTipoCompra .= "        inner join liclicita            on liclicitem.l21_codliclicita     = liclicita.l20_codigo           ";
        $sSqlTipoCompra .= "        inner join cflicita             on liclicita.l20_codtipocom        = cflicita.l03_codigo            ";
        $sSqlTipoCompra .= "        inner join empautoriza          on empautoriza.e54_autori          = empautitem.e55_autori          ";
        $sSqlTipoCompra .= "  where empautitem.e55_autori = {$oEmpenho->codautoriza} ";

        $rsLicita = db_query($connOrigem, $sSqlTipoCompra);

        if (pg_num_rows($rsLicita) > 0) {

          $oLicita = db_utils::fieldsMemory($rsLicita, 0);

          $aData       = explode("-", $oLicita->l20_dtpublic);
          $iAnoLic     = $aData[0];
          $sNumeroLic  = $oLicita->l20_numero . "/" . $iAnoLic;
          $sTipoCompra = $oLicita->l03_descr . " Numero Licitação : {$sNumeroLic}";
        }
      }

      if (trim($sTipoCompra) == '') {

        $sTipoCompra = $oEmpenho->descrtipocompra;

        if (trim($oEmpenho->numero_licitacao) != '') {
          $sTipoCompra .= " Numero Licitação : {$oEmpenho->numero_licitacao}";
        }
      }

      $oEmpenho->pessoa_id      = $iIdPessoa;
      $oEmpenho->planoconta_id  = $aListaPlanoConta[$oEmpenho->codcon][$oEmpenho->exercicio];
      $oEmpenho->dotacao_id     = $aListaDotacao[$oEmpenho->coddotacao][$oEmpenho->exercicio];
      $oEmpenho->instituicao_id = $aListaInstit[$oEmpenho->codinstit];
      $oEmpenho->tipo_compra    = $sTipoCompra;
      $oTBEmpenhos->setByLineOfDBUtils($oEmpenho);

      try {
        $oTBEmpenhos->insertValue();
      } catch (Exception $eException) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
    }
    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBEmpenhos->persist();
    } catch (Exception $eException) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
    // FIM EMPENHOS ***************************************************************************************************//
    // ITENS EMPENHOS *************************************************************************************************//
    db_logTitulo(" IMPORTA ITENS EMPENHOS", $sArquivoLog, $iParamLog);

    $sSqlEmpenhosDestino = " select *        ";
    $sSqlEmpenhosDestino .= "   from empenhos ";

    $rsDadosEmpenhosDestino = db_query($connDestino, $sSqlEmpenhosDestino);
    $iLinhasEmpenhosDestino = pg_num_rows($rsDadosEmpenhosDestino);

    db_logNumReg($iLinhasEmpenhosDestino, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iLinhasEmpenhosDestino; $iInd++) {

      $oEmpenhoDestino = db_utils::fieldsMemory($rsDadosEmpenhosDestino, $iInd);
      logProcessamento($iInd, $iLinhasEmpenhosDestino, $iParamLog);

      $sSqlItensEmpenho = " select trim(replace(pc01_descrmater, '\r\n', ' ')) as descricao,     ";
      $sSqlItensEmpenho .= "        e62_quant                                   as quantidade,    ";
      $sSqlItensEmpenho .= "        e62_vlrun                                   as valor_unitario,";
      $sSqlItensEmpenho .= "        e62_vltot                                   as valor_total    ";
      $sSqlItensEmpenho .= "   from empempitem                                                    ";
      $sSqlItensEmpenho .= "        inner join pcmater on pc01_codmater = e62_item                ";
      $sSqlItensEmpenho .= "  where e62_numemp = {$oEmpenhoDestino->codempenho}                   ";

      $rsDadosItensEmpenho = db_query($connOrigem, $sSqlItensEmpenho);
      $iLinhasItensEmpenho = pg_num_rows($rsDadosItensEmpenho);

      if ($iLinhasItensEmpenho > 0) {

        for ($iIndItem = 0; $iIndItem < $iLinhasItensEmpenho; $iIndItem++) {

          $oItemEmpenho = db_utils::fieldsMemory($rsDadosItensEmpenho, $iIndItem);

          if ($oItemEmpenho->descricao == '') {

            $oItemEmpenho->descricao = 'DESCRIÇÃO NÃO ESPECIFICADA';
          }

          $oItemEmpenho->empenho_id = $oEmpenhoDestino->id;
          $oTBEmpenhosItens->setByLineOfDBUtils($oItemEmpenho);

          try {

            $oTBEmpenhosItens->insertValue();
          } catch (Exception $eException) {

            throw new Exception("ERRO-0: {$eException->getMessage()}");
          }
        }

        try {

          $oTBEmpenhosItens->persist();
        } catch (Exception $eException) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
      }

      // Consulta Processos do Empenho
      $sSqlProcessoEmpenho = " select pc81_codproc as processo
                                  from empempaut
                                       inner join empautitem           on e55_autori = e61_autori
                                       inner join empautitempcprocitem on e73_autori = e55_autori
                                                                      and e73_sequen = e55_sequen
                                       inner join pcprocitem           on pc81_codprocitem = e73_pcprocitem
                                 where e61_numemp = {$oEmpenhoDestino->codempenho} ";

      $rsDadosProcessoEmpenho = db_query($connOrigem, $sSqlProcessoEmpenho);
      $iLinhasProcessoEmpenho = pg_num_rows($rsDadosProcessoEmpenho);

      if ($iLinhasProcessoEmpenho > 0) {

        for ($iIndProcesso = 0; $iIndProcesso < $iLinhasProcessoEmpenho; $iIndProcesso++) {

          $oProcessoEmpenho             = db_utils::fieldsMemory($rsDadosProcessoEmpenho, $iIndProcesso);
          $oProcessoEmpenho->empenho_id = $oEmpenhoDestino->id;
          $oTBEmpenhosProcessos->setByLineOfDBUtils($oProcessoEmpenho);

          try {

            $oTBEmpenhosProcessos->insertValue();
          } catch (Exception $eException) {

            throw new Exception("ERRO-0: {$eException->getMessage()}");
          }
        }

        try {

          $oTBEmpenhosProcessos->persist();
        } catch (Exception $eException) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
      }
    }
    // FIM ITENS EMPENHOS *********************************************************************************************//
    // MOVIMENTACOES EMPENHOS *****************************************************************************************//
    db_logTitulo(" IMPORTA MOVIMENTACOES EMPENHOS", $sArquivoLog, $iParamLog);

    /**
     * Consulta EmpenhosMovimentacoes na base de origem
     */
    $sSqlEmpenhoMovimentacao = " select conhistdoc.c53_coddoc   as codtipo,                                               ";
    $sSqlEmpenhoMovimentacao .= "        conhistdoc.c53_tipo     as codgrupo,                                              ";
    $sSqlEmpenhoMovimentacao .= "        conhistdoc.c53_descr    as descrtipo,                                             ";
    $sSqlEmpenhoMovimentacao .= "        conlancamemp.c75_numemp as codempenho,                                            ";
    $sSqlEmpenhoMovimentacao .= "        c70_data                as data,                                                  ";
    $sSqlEmpenhoMovimentacao .= "        c70_valor               as valor,                                                 ";

    $sSqlEmpenhoMovimentacao .= "        e69_dtvencimento as datavencimento,                                                 ";

    // $sSqlEmpenhoMovimentacao .= "        trim(e09_justificativa) as justificativa,                                                 ";
    $sSqlEmpenhoMovimentacao .= "        trim(e69_localrecebimento) as localrecebimento,                                                 ";

    $sSqlEmpenhoMovimentacao .= "        e50_codord as op, ";
    $sSqlEmpenhoMovimentacao .= "        e69_numero as nota, ";
    $sSqlEmpenhoMovimentacao .= "        c72_complem             as historico                                              ";
    $sSqlEmpenhoMovimentacao .= "   from conlancamemp                                                                      ";
    $sSqlEmpenhoMovimentacao .= "        inner join conlancam      on conlancam.c70_codlan      = conlancamemp.c75_codlan  ";
    $sSqlEmpenhoMovimentacao .= "        inner join conlancamdoc   on conlancamdoc.c71_codlan   = conlancamemp.c75_codlan  ";
    $sSqlEmpenhoMovimentacao .= "        inner join conhistdoc     on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc  ";
    $sSqlEmpenhoMovimentacao .= "        left  join conlancamcompl on conlancamcompl.c72_codlan = conlancamemp.c75_codlan  ";
    $sSqlEmpenhoMovimentacao .= "        left join conlancamord           on conlancamord.c80_codlan              = conlancam.c70_codlan      ";
    $sSqlEmpenhoMovimentacao .= "        left join conlancamnota          on conlancamnota.c66_codlan             = conlancam.c70_codlan      ";
    $sSqlEmpenhoMovimentacao .= "        left join empnota                on empnota.e69_codnota                  = conlancamnota.c66_codnota ";
    $sSqlEmpenhoMovimentacao .= "        left join pagordem               on pagordem.e50_codord                  = conlancamord.c80_codord   ";
    $sSqlEmpenhoMovimentacao .= "   where c70_data >= '{$iExercicioBase}-01-01'::date                                       ";
    $sSqlEmpenhoMovimentacao .= "    and exists ( select * from empempitem where empempitem.e62_numemp = conlancamemp.c75_numemp )";

    $rsEmpenhoMovimentacao    = db_query($connOrigem, $sSqlEmpenhoMovimentacao);
    $iRowsEmpenhoMovimentacao = pg_num_rows($rsEmpenhoMovimentacao);

    if ($iRowsEmpenhoMovimentacao == 0) {

      throw new Exception('Nenhuma movimentação encontrada!');
    }

    db_logNumReg($iRowsEmpenhoMovimentacao, $sArquivoLog, $iParamLog);

    /**
     *  Insere os registros na base de destino através do método insertValue da classe TableDataManager que quando
     *  atinge o número determinado de registros ( informado na assinatura da classe ) é executado automáticamente
     *  o método persist que insere fisicamente os registros na base de dados através do COPY.
     */
    for ($iInd = 0; $iInd < $iRowsEmpenhoMovimentacao; $iInd++) {

      $oEmpenhoMovimentacao = db_utils::fieldsMemory($rsEmpenhoMovimentacao, $iInd);

      logProcessamento($iInd, $iRowsEmpenhoMovimentacao, $iParamLog);

      if (!isset($aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo])) {

        $oTBEmpenhosMovimentacoesTipos->id        = '';
        $oTBEmpenhosMovimentacoesTipos->codtipo   = $oEmpenhoMovimentacao->codtipo;
        $oTBEmpenhosMovimentacoesTipos->codgrupo  = $oEmpenhoMovimentacao->codgrupo;
        $oTBEmpenhosMovimentacoesTipos->descricao = $oEmpenhoMovimentacao->descrtipo;

        try {

          $oTBEmpenhosMovimentacoesTipos->insertValue();
          $oTBEmpenhosMovimentacoesTipos->persist();
        } catch (Exception $eException) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }

        $aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo] = $oTBEmpenhosMovimentacoesTipos->getLastPk();
      }

      $sSqlEmpenhosDestino = "select id
                              from empenhos
                             where codempenho = {$oEmpenhoMovimentacao->codempenho} ";

      $rsEmpenhoDestino = db_query($connDestino, $sSqlEmpenhosDestino);

      if (pg_num_rows($rsEmpenhoDestino) > 0) {

        $iIdEmpenho = db_utils::fieldsMemory($rsEmpenhoDestino, 0)->id;

        $oEmpenhoMovimentacao->empenho_id                   = $iIdEmpenho;
        $oEmpenhoMovimentacao->empenho_movimentacao_tipo_id = $aListaEmpenhoMovimentacaoTipo[$oEmpenhoMovimentacao->codtipo];

        $oTBEmpenhosMovimentacoes->setByLineOfDBUtils($oEmpenhoMovimentacao);

        try {

          $oTBEmpenhosMovimentacoes->insertValue();

          if (!empty($oEmpenhoMovimentacao->op)) {

            // Verifica e Insere a Justificativa, caso exista
            $sSqlEmpenhoMovimentacaoJustificativa = "select trim(e09_justificativa) as justificativa";
            $sSqlEmpenhoMovimentacaoJustificativa .= "  from conlancamemp                                                                      ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join conlancam              on conlancam.c70_codlan                 = conlancamemp.c75_codlan   ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join conlancamnota          on conlancamnota.c66_codlan             = conlancam.c70_codlan      ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join conlancamord           on conlancamord.c80_codlan              = conlancam.c70_codlan      ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join pagordem               on pagordem.e50_codord                  = conlancamord.c80_codord   ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join empnota                on empnota.e69_codnota                  = conlancamnota.c66_codnota ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "     inner join empagemovjustificativa on empagemovjustificativa.e09_codnota   = empnota.e69_codnota       ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "  where ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "    conlancamemp.c75_numemp = {$oEmpenhoMovimentacao->codempenho}                                     ";
            $sSqlEmpenhoMovimentacaoJustificativa .= "    and pagordem.e50_codord = '{$oEmpenhoMovimentacao->op}'                                    ";

            $rsEmpenhoMovimentacaoJustificativa    = db_query($connOrigem, $sSqlEmpenhoMovimentacaoJustificativa);
            $iRowsEmpenhoMovimentacaoJustificativa = pg_num_rows($rsEmpenhoMovimentacaoJustificativa);

            if ($iRowsEmpenhoMovimentacaoJustificativa != 0) {

              for ($iIndJustificativa = 0; $iIndJustificativa < $iRowsEmpenhoMovimentacaoJustificativa; $iIndJustificativa++) {

                $oEmpenhoMovimentacaoJustificativa = db_utils::fieldsMemory($rsEmpenhoMovimentacaoJustificativa, $iIndJustificativa);
                $iIdMovJustificativa               = $oTBEmpenhosMovimentacoes->getLastPk();

                $oTBClassificacaoCredorMovimentacao->id                        = '';
                $oTBClassificacaoCredorMovimentacao->justificativa             = $oEmpenhoMovimentacaoJustificativa->justificativa;
                $oTBClassificacaoCredorMovimentacao->empenhos_movimentacoes_id = $iIdMovJustificativa;

                try {

                  $oTBClassificacaoCredorMovimentacao->insertValue();
                } catch (Exception $eException) {

                  throw new Exception("ERRO-0: {$eException->getMessage()}");
                }
              }
            }
          }
        } catch (Exception $eException) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
      } else {

        throw new Exception("ERRO-0: Empenho não encontrado!$oEmpenhoMovimentacao->codempenho  ");
      }
    }

    /**
     *  Após o loop é executado manualmente o método persist para que sejam inserido os registros restantes
     *  ( mesmo que não tenha atingido o número máximo do bloco de registros )
     */
    try {
      $oTBEmpenhosMovimentacoes->persist();
    } catch (Exception $eException) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
  }
  // FIM MOVIMENTAÇÕES EMPENHOS *************************************************************************************//

  unset($aListaPlanoConta);
  unset($aListaDotacao);

  // SERVIDORES *********************************** //
  db_logTitulo(" IMPORTA SERVIDORES", $sArquivoLog, $iParamLog);

  if (IMPORTAR_SERVIDORES) {

    $sSqlServidores  = "  create temp table dados_servidor as                                                                          ";
    $sSqlServidores .= "  select rh02_anousu as ano,                                                                                   ";
    $sSqlServidores .= "       rh02_mesusu as mes,                                                                                     ";
    $sSqlServidores .= "       rh02_salari as salario_base,                                                                            ";
    $sSqlServidores .= "       rh01_regist as matricula,                                                                               ";
    $sSqlServidores .= "       z01_nome    as nome,                                                                                    ";
    $sSqlServidores .= "       z01_cgccpf  as cpf,                                                                                     ";
    $sSqlServidores .= "       rh37_descr  as cargo,                                                                                   ";
    $sSqlServidores .= "       r70_descr   as lotacao,                                                                                 ";
    $sSqlServidores .= "       rh30_descr  as vinculo,                                                                                 ";
    $sSqlServidores .= "       rh01_admiss as admissao,                                                                                ";
    $sSqlServidores .= "       (case when (rh02_anousu||trim(to_char(rh02_mesusu,'00')))::integer <= (fc_anofolha(codigo)||trim(to_char(fc_mesfolha(codigo),'00')))::integer";
    $sSqlServidores .= "             then rh05_recis else null end) as rescisao,                                                       ";
    $sSqlServidores .= "       codigo      as instituicao,                                                                             ";
    $sSqlServidores .= "       rh01_instit as instit_servidor                                                                          ";
    $sSqlServidores .= "  from rhpessoal                                                                                               ";
    $sSqlServidores .= "       inner join rhpessoalmov on rh02_regist = rh01_regist                                                    ";
    $sSqlServidores .= "       inner join rhfuncao     on rh37_funcao = rh02_funcao                                                    ";
    $sSqlServidores .= "                              and rh37_instit = rh02_instit                                                    ";
    $sSqlServidores .= "       inner join rhlota       on r70_codigo  = rh02_lota                                                      ";
    $sSqlServidores .= "                              and r70_instit  = rh02_instit                                                    ";
    $sSqlServidores .= "       inner join cgm          on z01_numcgm  = rh01_numcgm                                                    ";
    $sSqlServidores .= "       inner join rhregime     on rh02_codreg = rh30_codreg                                                    ";
    $sSqlServidores .= "                              and rh02_instit = rh30_instit                                                    ";
    $sSqlServidores .= "       inner join db_config    on codigo      = rh02_instit                                                    ";
    $sSqlServidores .= "       left join rhpesrescisao on rh05_seqpes = rh02_seqpes                                                    ";
    $sSqlServidores .= " where rh02_anousu >= {$iExercicioBase} ";
    $sSqlServidores .= "   and rh02_anousu||trim(to_char(rh02_mesusu,'00')) not in ((select max(r11_anousu||trim(to_char(r11_mesusu,'00'))) \n";
    $sSqlServidores .= "                                                              from cfpess                                          \n";
    $sSqlServidores .= "                                                             where r11_instit = (select db_config.codigo from configuracoes.db_config where db_config.prefeitura is true) \n";
    $sSqlServidores .= "                                                             group by r11_instit                                   \n";
    $sSqlServidores .= "                                                             limit 1))                                              \n";
    $sSqlServidores .= " order by rh02_anousu, rh02_mesusu, rh01_regist           ";

    db_query($connOrigem, $sSqlServidores);

    $sSqlCreateIndex = "create index dados_servidor_ano_mes_matricula_in on dados_servidor (ano, mes, matricula) ";
    db_query($connOrigem, $sSqlCreateIndex);

    $sSqlAnalyse = "analyze dados_servidor ";
    db_query($connOrigem, $sSqlAnalyse);


    $sSqlDadosCadastraisServidor  = " select matricula as id,                         ";
    $sSqlDadosCadastraisServidor .= "        nome,                                    ";
    $sSqlDadosCadastraisServidor .= "        cpf,                                     ";
    $sSqlDadosCadastraisServidor .= "        instit_servidor as instituicao,              ";
    $sSqlDadosCadastraisServidor .= "        admissao,                                ";
    $sSqlDadosCadastraisServidor .= "        max(rescisao) as rescisao                ";
    $sSqlDadosCadastraisServidor .= "   from dados_servidor                           ";
    $sSqlDadosCadastraisServidor .= "   group by id, nome, cpf, instit_servidor, admissao ";

    $rsServidores                 = db_query($connOrigem, $sSqlDadosCadastraisServidor);

    if ( !$rsServidores ) {
      throw new Exception("ERRO-1: Erro ao criar tabela temporaria dos servidores.!");
    }

    $iRowsServidores = pg_num_rows($rsServidores);

    db_logNumReg($iRowsServidores, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsServidores; $iInd++ ) {

      $oServidorRow                 = db_utils::fieldsMemory($rsServidores, $iInd);
      $oServidorRow->instituicao_id = $aListaInstit[$oServidorRow->instituicao];

      $oTBServidores->setByLineOfDBUtils($oServidorRow, true);
      logProcessamento($iInd, $iRowsServidores, $iParamLog);
    }

    try {
      $oTBServidores->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    // FIM SERVIDORES ***************************** //

    // IMPORTACAO MOVIMENTACOES SERVIDORES ******** //
    db_logTitulo(" IMPORTA MOVIMENTACOES DOS SERVIDORES", $sArquivoLog, $iParamLog);

    $sSqlMovimentacaoServidor  = " select matricula as servidor_id,                                         ";
    $sSqlMovimentacaoServidor .= "        ano,                                                              ";
    $sSqlMovimentacaoServidor .= "        mes,                                                              ";
    $sSqlMovimentacaoServidor .= "        cargo,                                                            ";
    $sSqlMovimentacaoServidor .= "        lotacao,                                                          ";
    $sSqlMovimentacaoServidor .= "        vinculo,                                                          ";
    $sSqlMovimentacaoServidor .= "        salario_base                                                      ";
    $sSqlMovimentacaoServidor .= "    from dados_servidor                                                   ";
    $sSqlMovimentacaoServidor .= "    group by servidor_id, ano, mes, cargo, lotacao, vinculo, salario_base ";

    $rsServidoresMovimentacao  = db_query($connOrigem, $sSqlMovimentacaoServidor);

    if ( !$rsServidoresMovimentacao ) {
      throw new Exception("ERRO-1: Erro ao buscar movimentacoes dos servidores.!");
    }

    $iRowsServidores = pg_num_rows($rsServidoresMovimentacao);

    db_logNumReg($iRowsServidores, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsServidores; $iInd++) {

      $oMovimentacaoServidorRow = db_utils::fieldsMemory($rsServidoresMovimentacao, $iInd);

      $oTBMovimentacoesServidores->setByLineOfDBUtils($oMovimentacaoServidorRow, true);
      logProcessamento($iInd, $iRowsServidores, $iParamLog);
    }

    try {
      $oTBMovimentacoesServidores->persist();
    } catch (Exception $e) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    /**
     * Pega todas as movimentacoes dos servidores e monta uma matriz para pegar a movimentação correspondente
     * a competência. a matriz $aMatrizMovimentacaoServidor será usada ao inserir os dados financeiros.
     */
    $sSqlMatrizServidorMovimentacao  = " select id, servidor_id, mes, ano         ";
    $sSqlMatrizServidorMovimentacao .= "   from {$sSchema}.servidor_movimentacoes ";

    $rsListaServidorMovimentacao     = db_query($connDestino, $sSqlMatrizServidorMovimentacao);
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
     * CRIA TABELA COM OS TOTALIZADORES
     */
    $sSqlTempTableSomatorio  = " create temp table somatorio as                                                                                                                ";
    $sSqlTempTableSomatorio .= "      select r14_anousu as anousu,                                                                                                                ";
    $sSqlTempTableSomatorio .= "              r14_mesusu as mesusu,                                                                                                               ";
    $sSqlTempTableSomatorio .= "              r14_regist as regist,                                                                                                               ";
    $sSqlTempTableSomatorio .= "              'Z888'::char(4) as rubrica,                                                                                                         ";
    $sSqlTempTableSomatorio .= "              sum(r14_valor)  as valor,                                                                                                           ";
    $sSqlTempTableSomatorio .= "              0               as quantidade,                                                                                                      ";
    $sSqlTempTableSomatorio .= "              'base'          as tiporubrica,                                                                                                     ";
    $sSqlTempTableSomatorio .= "              'salario'       as tipofolha,                                                                                                       ";
    $sSqlTempTableSomatorio .= "              r14_instit      as instit                                                                                                           ";
    $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r14_pd     = 2                                                                                                                      ";
    $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r14_anousu, r14_mesusu, r14_regist, 'Z999'::char(4) as r14_rubric, sum(r14_valor) as r14_valor,0,'base', 'salario', r14_instit       ";
    $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r14_pd = 1                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r14_anousu, r14_mesusu, r14_regist, 'Z777'::char(4) as r14_rubric, sum(r14_valor) as r14_valor,0,'base', 'salario', r14_instit       ";
    $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r14_rubric between 'R901' and 'R915'                                                                                                ";
    $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z888'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r48_pd = 2                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z999'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r48_pd = 1                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z777'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r48_rubric between 'R901' and 'R915'                                                                                                ";
    $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z888'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
    $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r35_pd = 2                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z999'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
    $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r35_pd = 1                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z777'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
    $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r35_rubric between 'R901' and 'R915'                                                                                                ";
    $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z888'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
    $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r20_pd = 2                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z999'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
    $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r20_pd = 1                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z777'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
    $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r20_rubric between 'R901' and 'R915'                                                                                                ";
    $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "                                                                                                                                                  ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z888'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r22_pd = 2                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z999'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r22_pd = 1                                                                                                                          ";
    $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
    $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
    $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z777'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
    $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
    $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
    $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
    $sSqlTempTableSomatorio .= "        where r22_rubric between 'R901' and 'R915'                                                                                                ";
    $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
    $rsTempSomatorio = db_query($sSqlTempTableSomatorio);

    if(!$rsTempSomatorio){
      throw new Exception("ERRO-1: Erro ao criar tabela somatorio!");
    }

    $sSqlIndiceSomatorio  = "create index somatorio_anousu_mesusu_regist_in on somatorio (anousu, mesusu, regist)";
    $rsIndiceSomatorio    = db_query($sSqlIndiceSomatorio);

    if(!$rsIndiceSomatorio){
      throw new Exception("ERRO-1: Erro ao criar indice somatorio_anousu_mesusu_regist_in!");
    }

    $sSqlAnalizeSomatorio = "analyze somatorio";
    $rsAnalizeSomatorio   = db_query($sSqlAnalizeSomatorio);

    if(!$rsAnalizeSomatorio){
      throw new Exception("ERRO-1: Erro ao executar analyze!");
    }

    $sSqlDadosServidores = "select distinct ano, mes from dados_servidor";
    $rsDadosServidores   = db_query($connOrigem, $sSqlDadosServidores);
    $iDadosServidores    = pg_num_rows($rsDadosServidores);

    for ($iServidor = 0; $iServidor < $iDadosServidores; $iServidor++) {

      $oDadosServidores = db_utils::fieldsMemory($rsDadosServidores, $iServidor);
      $mes = $oDadosServidores->mes;
      $ano = $oDadosServidores->ano;

      $sSqlFolhaPagamento  = "   select ano,                                                                                                                                    ";
      $sSqlFolhaPagamento .= "          mes,                                                                                                                                    ";
      $sSqlFolhaPagamento .= "          matricula,                                                                                                                              ";
      $sSqlFolhaPagamento .= "          rubrica,                                                                                                                                ";
      $sSqlFolhaPagamento .= "          case when rh27_descr is not null then rh27_descr                                                                                        ";
      $sSqlFolhaPagamento .= "               when rubrica = 'Z999' then 'Total Bruto'                                                                                           ";
      $sSqlFolhaPagamento .= "               when rubrica = 'Z888' then 'Total Descontos'                                                                                       ";
      $sSqlFolhaPagamento .= "               when rubrica = 'Z777' then 'Descontos Obrigatórios'                                                                                ";
      $sSqlFolhaPagamento .= "          end as descr_rubrica,                                                                                                                   ";
      $sSqlFolhaPagamento .= "          valor,                                                                                                                                  ";
      $sSqlFolhaPagamento .= "          quantidade,                                                                                                                             ";
      $sSqlFolhaPagamento .= "          tiporubrica,                                                                                                                            ";
      $sSqlFolhaPagamento .= "          tipofolha,                                                                                                                              ";
      $sSqlFolhaPagamento .= "          instit                                                                                                                                  ";
      $sSqlFolhaPagamento .= "     from (                                                                                                                                       ";
      $sSqlFolhaPagamento .= "      select r14_anousu as ano,r14_mesusu as mes,r14_regist as matricula,r14_rubric as rubrica, r14_valor as valor, r14_quant as quantidade,      ";
      $sSqlFolhaPagamento .= "        case r14_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end as tiporubrica, 'salario' as tipofolha, r14_instit as instit    ";
      $sSqlFolhaPagamento .= "        from gerfsal                                                                                                                              ";
      $sSqlFolhaPagamento .= "             inner join dados_servidor on matricula = r14_regist                                                                                  ";
      $sSqlFolhaPagamento .= "                                      and ano       = r14_anousu                                                                                  ";
      $sSqlFolhaPagamento .= "                                      and mes       = r14_mesusu                                                                                  ";
      $sSqlFolhaPagamento .= "      where r14_mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and r14_anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
      $sSqlFolhaPagamento .= "       select r48_anousu,r48_mesusu,r48_regist,r48_rubric, r48_valor, r48_quant,                                                                  ";
      $sSqlFolhaPagamento .= "         case r48_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'complementar', r48_instit                                    ";
      $sSqlFolhaPagamento .= "         from gerfcom                                                                                                                             ";
      $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and ano       = r48_anousu                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and mes       = r48_mesusu                                                                                 ";
      $sSqlFolhaPagamento .= "      where r48_mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and r48_anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
      $sSqlFolhaPagamento .= "       select r35_anousu,r35_mesusu,r35_regist,r35_rubric, r35_valor, r35_quant,                                                                  ";
      $sSqlFolhaPagamento .= "         case r35_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, '13salario', r35_instit                                       ";
      $sSqlFolhaPagamento .= "         from gerfs13                                                                                                                             ";
      $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and ano       = r35_anousu                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and mes       = r35_mesusu                                                                                 ";
      $sSqlFolhaPagamento .= "      where r35_mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and r35_anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
      $sSqlFolhaPagamento .= "       select r20_anousu,r20_mesusu,r20_regist,r20_rubric, r20_valor, r20_quant,                                                                  ";
      $sSqlFolhaPagamento .= "         case r20_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'rescisao', r20_instit                                        ";
      $sSqlFolhaPagamento .= "         from gerfres                                                                                                                             ";
      $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and ano       = r20_anousu                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and mes       = r20_mesusu                                                                                 ";
      $sSqlFolhaPagamento .= "      where r20_mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and r20_anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
      $sSqlFolhaPagamento .= "        select r22_anousu,r22_mesusu,r22_regist,r22_rubric, r22_valor, r22_quant,                                                                 ";
      $sSqlFolhaPagamento .= "         case r22_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'adiantamento', r22_instit                                    ";
      $sSqlFolhaPagamento .= "         from gerfadi                                                                                                                             ";
      $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and ano       = r22_anousu                                                                                 ";
      $sSqlFolhaPagamento .= "                                       and mes       = r22_mesusu                                                                                 ";
      $sSqlFolhaPagamento .= "      where r22_mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and r22_anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
      $sSqlFolhaPagamento .= "       select anousu, mesusu, regist, rubrica, valor, quantidade, tiporubrica, tipofolha, instit                                                  ";
      $sSqlFolhaPagamento .= "         from somatorio                                                                                                                           ";
      $sSqlFolhaPagamento .= "      where mesusu = {$mes}                                                                                                                   ";
      $sSqlFolhaPagamento .= "        and anousu = {$ano}                                                                                                                   ";
      $sSqlFolhaPagamento .= "     ) as x                                                                                                                                       ";
      $sSqlFolhaPagamento .= "  left join rhrubricas on rubrica = rh27_rubric and instit = rh27_instit                                                                          ";
      $sSqlFolhaPagamento .= "  order by ano,mes,matricula,tipofolha, tiporubrica desc, rubrica;                                                                                ";

      $rsFolhaPagamento    = db_query($connOrigem, $sSqlFolhaPagamento);

      if ( !$rsFolhaPagamento ) {
        throw new Exception("ERRO-1: Erro ao buscar dados de rubricas.!");
      }

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

      try {
        $oTBFolhaPagamento->persist();
      } catch (Exception $e) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }

      // FIM IMPORTACAO DADOS FINANCEIROS SERVIDOR ** //

    }

    // IMPORTACAO RECURSOS HUMANOS SERVIDOR ******* //
    db_logTitulo(" IMPORTA DADOS RECURSOS HUMANOS SERVIDOR", $sArquivoLog, $iParamLog);

    $sSqlRecursosHumanos  = " select h16_regist as servidor_id,                         ";
    $sSqlRecursosHumanos .= "        h12_assent,                                        ";
    $sSqlRecursosHumanos .= "        h12_descr as descricao,                            ";
    $sSqlRecursosHumanos .= "        h16_nrport as numero_portaria,                     ";
    $sSqlRecursosHumanos .= "        h16_atofic as ato_oficial,                         ";
    $sSqlRecursosHumanos .= "        h16_dtconc data_concessao,                         ";
    $sSqlRecursosHumanos .= "        h16_dtterm as data_termino,                        ";
    $sSqlRecursosHumanos .= "        h16_quant as quantidade_dias,                      ";
    $sSqlRecursosHumanos .= "        h16_histor as historico                            ";
    $sSqlRecursosHumanos .= "   from assenta                                            ";
    $sSqlRecursosHumanos .= "      inner join tipoasse       on h12_codigo = h16_assent ";
    $sSqlRecursosHumanos .= "  where exists (select 1                                   ";
    $sSqlRecursosHumanos .= "                  from dados_servidor                      ";
    $sSqlRecursosHumanos .= "                 where matricula = h16_regist              ";
    $sSqlRecursosHumanos .= "               )                                           ";

    $rsRecursosHumanos    = db_query($connOrigem, $sSqlRecursosHumanos);

    if ( !$rsRecursosHumanos ) {
      throw new Exception("ERRO-1: Erro ao buscar dados recursos humanos.!");
    }

    $iRowsRecursosHumanos = pg_num_rows($rsRecursosHumanos);

    db_logNumReg($iRowsRecursosHumanos, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsRecursosHumanos; $iInd++) {

      $oRecursosHumanosRow = db_utils::fieldsMemory($rsRecursosHumanos, $iInd);
      $oTBAssentamentos->setByLineOfDBUtils($oRecursosHumanosRow, true);
      logProcessamento($iInd, $iRowsRecursosHumanos, $iParamLog);
    }

    try {
      $oTBAssentamentos->persist();
    } catch (Exception $e) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
  }
  // FIM IMPORTACAO RECURSOS HUMANOS ASSENTAMENTOS //

  if (IMPORTAR_PATRIMONIO_VEICULOS) {
    // IMPORTACAO DE PATRIMONIOS //
    // IMPORTA CLASSIFICACOES DO PATRIMONIO
    db_logTitulo(" IMPORTA CLASSIFICACOES DO PATRIMONIO", $sArquivoLog, $iParamLog);

    $sSqlBemClassificacao = "
      select
        t64_codcla as codigo,
        t64_descr as descricao
      from
        clabens;
    ";

    $rsBemClassificacao    = db_query($connOrigem, $sSqlBemClassificacao);
    $iRowsBemClassificacao = pg_num_rows($rsBemClassificacao);

    if ($iRowsBemClassificacao == 0) {

      throw new Exception('Nenhuma classificacao encontrada!');
    }

    $aBemClassificacao = array();
    db_logNumReg($iRowsBemClassificacao, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsBemClassificacao; $iInd++) {

      logProcessamento($iInd, $iRowsBemClassificacao, $iParamLog);

      $oBemClassificacao     = db_utils::fieldsMemory($rsBemClassificacao, $iInd);
      $oBemClassificacao->id = '';
      $oTBBemClassificacao->setByLineOfDBUtils($oBemClassificacao);

      try {

        $oTBBemClassificacao->insertValue();
        $oTBBemClassificacao->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aBemClassificacao[$oBemClassificacao->codigo] = $oTBBemClassificacao->getLastPk();
    }
    // FIM IMPORTA CLASSIFICACOES DO PATRIMONIO

    // IMPORTA TIPOS DE PATRIMONIO
    db_logTitulo(" IMPORTA TIPOS DE PATRIMONIO", $sArquivoLog, $iParamLog);

    $sSqlBemTipo = "
      select
        t24_sequencial as codbemtipo,
        t24_descricao as descricao
      from
        bemtipos;
    ";

    $rsBemTipo    = db_query($connOrigem, $sSqlBemTipo);
    $iRowsBemTipo = pg_num_rows($rsBemTipo);

    if ($iRowsBemTipo == 0) {

      throw new Exception('Nenhum tipo de bem encontrado!');
    }

    $aBemTipo = array();
    db_logNumReg($iRowsBemTipo, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsBemTipo; $iInd++) {

      logProcessamento($iInd, $iRowsBemTipo, $iParamLog);

      $oBemTipo     = db_utils::fieldsMemory($rsBemTipo, $iInd);
      $oBemTipo->id = '';

      $oTBBemTipo->setByLineOfDBUtils($oBemTipo);

      try {

        $oTBBemTipo->insertValue();
        $oTBBemTipo->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aBemTipo[$oBemTipo->codbemtipo] = $oTBBemTipo->getLastPk();
    }
    // FIM IMPORTA TIPOS DE PATRIMONIO

    // IMPORTA TIPOS DE AQUISICAO DE PATRIMONIO
    db_logTitulo(" IMPORTA TIPOS DE AQUISICAO DE PATRIMONIO", $sArquivoLog, $iParamLog);

    $sSqlBemAquisicaoTipo = "
      select
        t45_sequencial as codbemaquisicaotipo,
        t45_descricao as descricao
      from
        benstipoaquisicao;
    ";

    $rsBemAquisicaoTipo    = db_query($connOrigem, $sSqlBemAquisicaoTipo);
    $iRowsBemAquisicaoTipo = pg_num_rows($rsBemAquisicaoTipo);

    if ($iRowsBemTipo == 0) {

      throw new Exception('Nenhum tipo de aquisicao de bem encontrado!');
    }

    $aBemAquisicaoTipo = array();
    db_logNumReg($iRowsBemAquisicaoTipo, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsBemAquisicaoTipo; $iInd++) {

      logProcessamento($iInd, $iRowsBemAquisicaoTipo, $iParamLog);

      $oBemAquisicaoTipo     = db_utils::fieldsMemory($rsBemAquisicaoTipo, $iInd);
      $oBemAquisicaoTipo->id = '';
      $oTBBemAquisicaoTipo->setByLineOfDBUtils($oBemAquisicaoTipo);

      try {

        $oTBBemAquisicaoTipo->insertValue();
        $oTBBemAquisicaoTipo->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aBemAquisicaoTipo[$oBemAquisicaoTipo->codbemaquisicaotipo] = $oTBBemAquisicaoTipo->getLastPk();
    }
    // FIM IMPORTA TIPOS DE AQUISICAO DE PATRIMONIO

    // IMPORTA DEPARTAMENTOS DO ORGAO
    db_logTitulo(" IMPORTA DEPARTAMENTOS DO ORGAO", $sArquivoLog, $iParamLog);

    $sSqlDepartamento = "
      select
        coddepto as codigo_departamento,
        descrdepto as descricao
      from
        db_depart;
    ";

    $rsDepartamento    = db_query($connOrigem, $sSqlDepartamento);
    $iRowsDepartamento = pg_num_rows($rsDepartamento);

    if ($iRowsDepartamento == 0) {

      throw new Exception('Nenhum departamento encontrado!');
    }

    $aDepartamento = array();
    db_logNumReg($iRowsDepartamento, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsDepartamento; $iInd++) {

      logProcessamento($iInd, $iRowsDepartamento, $iParamLog);

      $oDepartamento     = db_utils::fieldsMemory($rsDepartamento, $iInd);
      $oDepartamento->id = '';
      $oTBDepartamento->setByLineOfDBUtils($oDepartamento);

      try {

        $oTBDepartamento->insertValue();
        $oTBDepartamento->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aDepartamento[$oDepartamento->codigo_departamento] = $oTBDepartamento->getLastPk();
    }
    // FIM IMPORTA TIPOS DE AQUISICAO DE PATRIMONIO

    // IMPORTA DIVISAO DEPARTAMENTOS DO ORGAO
    db_logTitulo(" IMPORTA DIVISAO DE DEPARTAMENTOS DO ORGAO", $sArquivoLog, $iParamLog);

    $sSqlDivisaoDepartamento = "
      select
        t30_codigo as codigo_divisao,
        t30_descr as descricao
      from
        departdiv;
    ";

    $rsDivisaoDepartamento    = db_query($connOrigem, $sSqlDivisaoDepartamento);
    $iRowsDivisaoDepartamento = pg_num_rows($rsDivisaoDepartamento);

    if ($iRowsDivisaoDepartamento == 0) {

      throw new Exception('Nenhuma divisao encontrado!');
    }

    $aDivisaoDepartamento = array();
    db_logNumReg($iRowsDivisaoDepartamento, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsDivisaoDepartamento; $iInd++) {

      logProcessamento($iInd, $iRowsDivisaoDepartamento, $iParamLog);

      $oDivisaoDepartamento     = db_utils::fieldsMemory($rsDivisaoDepartamento, $iInd);
      $oDivisaoDepartamento->id = '';
      $oTBDivisaoDepartamento->setByLineOfDBUtils($oDivisaoDepartamento);

      try {

        $oTBDivisaoDepartamento->insertValue();
        $oTBDivisaoDepartamento->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aDivisaoDepartamento[$oDivisaoDepartamento->codigo_divisao] = $oTBDivisaoDepartamento->getLastPk();
    }
    // FIM IMPORTA TIPOS DE AQUISICAO DE PATRIMONIO

    // IMPORTA TIPOS DE DEPRECIACAO DO PATRIMONIO
    db_logTitulo(" IMPORTA TIPO DE DEPRECIACAO DO PATRIMONIO", $sArquivoLog, $iParamLog);

    $sSqlBemTipoDepreciacao = "
      select
        t46_sequencial as codigo_tipo_depreciacao,
        t46_descricao as descricao
      from
        benstipodepreciacao;
    ";

    $rsBemTipoDepreciacao    = db_query($connOrigem, $sSqlBemTipoDepreciacao);
    $iRowsBemTipoDepreciacao = pg_num_rows($rsBemTipoDepreciacao);

    if ($iRowsBemTipoDepreciacao == 0) {

      throw new Exception('Nenhuma tipo de depreciacao encontrado!');
    }

    $aBemTipoDepreciacao = array();
    db_logNumReg($iRowsBemTipoDepreciacao, $sArquivoLog, $iParamLog);

    for ($iInd = 0; $iInd < $iRowsBemTipoDepreciacao; $iInd++) {

      logProcessamento($iInd, $iRowsBemTipoDepreciacao, $iParamLog);

      $oBemTipoDepreciacao     = db_utils::fieldsMemory($rsBemTipoDepreciacao, $iInd);
      $oBemTipoDepreciacao->id = '';
      $oTBBemTipoDepreciacao->setByLineOfDBUtils($oBemTipoDepreciacao);

      try {

        $oTBBemTipoDepreciacao->insertValue();
        $oTBBemTipoDepreciacao->persist();
      } catch (Exception $eException) {

        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }
      $aBemTipoDepreciacao[$oBemTipoDepreciacao->codigo_tipo_depreciacao] = $oTBBemTipoDepreciacao->getLastPk();
    }
    // FIM IMPORTA TIPOS DE DEPRECIACAO DO PATRIMONIO

    db_logTitulo(" IMPORTA DADOS DO PATRIMONIO", $sArquivoLog, $iParamLog);

    $sSqlInstituicao = "
    select id,
           codinstit
      from instituicoes;
  ";

    $rsInstituicoes    = db_query($connDestino, $sSqlInstituicao);
    $iRowsInstituicoes = pg_num_rows($rsInstituicoes);
    if ($iRowsInstituicoes == 0) {
      throw new Exception('Nenhuma instituicao encontrada!');
    }

    $aInstituicoes = array();
    for ($iInd = 0; $iInd < $iRowsInstituicoes; $iInd++) {
      $oInstituicao                            = db_utils::fieldsMemory($rsInstituicoes, $iInd);
      $aInstituicoes[$oInstituicao->codinstit] = $oInstituicao->id;
    }

    $sSqlPatrimonio = "
    select distinct
      t52_bem    as codigo,
      t52_instit as instituicao,
      t52_descr  as descricao,
      round(t52_valaqu,2) as valor_aquisicao,
      t52_dtaqu as data_aquisicao,
      cast(regexp_replace(coalesce(nullif(trim(t52_ident),''), '0') , '[^0-9.,-]' , '', 'g') as numeric) as placa,
      t52_depart as codigo_departamento,
      descrdepto as departamento_descricao,
      t52_depart as departamento_codigo,
      t52_numcgm as numcgm,
      z01_nome   as cgm_nome,
      z01_cgccpf as cpfcnpj,
      t52_obs    as observacao,
      --t64_class  as classificacao,
      t52_codcla  as classificacao_bem,
      t64_descr  as classificacao_descricao,
      t64_bemtipos as codbemtipo,
      t33_divisao,
      EXTRACT(YEAR FROM t52_dtaqu) as exercicio,
      --t86_anousu as exercicio,
      departdiv.t30_codigo as codigo_divisao,
      (
        select count(*)
          from bensplaca
               inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
         where t41_bem = t52_bem
      ) as totaletiquetas
    from bens 
         inner join cgm       on cgm.z01_numcgm = bens.t52_numcgm
         inner join db_depart on db_depart.coddepto = bens.t52_depart
         inner join clabens   on clabens.t64_codcla = bens.t52_codcla
         inner join clabensconplano on clabensconplano.t86_clabens = clabens.t64_codcla
                                   and clabensconplano.t86_anousu >= " . $iAnoAtual . "
         inner join conplano  on conplano.c60_codcon  = clabensconplano.t86_conplano
                               and conplano.c60_anousu >= " . $iAnoAtual . "
         left  join bensdiv     on bensdiv.t33_bem = bens.t52_bem
         left  join departdiv   on  departdiv.t30_codigo = bensdiv.t33_divisao
                               and t30_depto  = db_depart.coddepto
         left  join histbem     on histbem.t56_codbem   = bens.t52_bem
                               and histbem.t56_depart = bens.t52_depart
         left  join situabens  on situabens.t70_situac = histbem.t56_situac
         inner join bensmarca  on bensmarca.t65_sequencial  = bens.t52_bensmarca
         inner join bensmodelo on bensmodelo.t66_sequencial = bens.t52_bensmodelo
         inner join bensmedida on bensmedida.t67_sequencial = bens.t52_bensmedida
         left  join bensbaix on bensbaix.t55_codbem = bens.t52_bem
    order by t52_depart
  ";

    $rsPatrimonio    = db_query($connOrigem, $sSqlPatrimonio);
    $iRowsPatrimonio = pg_num_rows($rsPatrimonio);
    if ($iRowsPatrimonio > 0) {

      db_logNumReg($iRowsPatrimonio, $sArquivoLog, $iParamLog);

      for ($iInd = 0; $iInd < $iRowsPatrimonio; $iInd++) {

        $oPatrimonio = db_utils::fieldsMemory($rsPatrimonio, $iInd);

        $oPatrimonio->bem_tipo_id             = $aBemTipo[$oPatrimonio->codbemtipo];
        $oPatrimonio->departamento_id         = $aDepartamento[$oPatrimonio->codigo_departamento];
        $oPatrimonio->bem_tipo_depreciacao_id = 0;

        $oPatrimonio->departamento_divisao_id = 0;
        if (!empty($oPatrimonio->codigo_divisao)) {

          $oPatrimonio->departamento_divisao_id = $aDivisaoDepartamento[$oPatrimonio->codigo_divisao];
        }
        logProcessamento($iInd, $iRowsPatrimonio, $iParamLog);

        // Verifica CGM do Fornecedor
        $sSqlPessoas = "
      select id
        from pessoas
       where codpessoa = {$oPatrimonio->numcgm} ";

        $rsPessoas = db_query($connDestino, $sSqlPessoas);

        if (pg_num_rows($rsPessoas) > 0) {
          $oPatrimonio->pessoa_id = db_utils::fieldsMemory($rsPessoas, 0)->id;
        } else {

          $oTBPessoas->id        = '';
          $oTBPessoas->codpessoa = $oPatrimonio->numcgm;
          $oTBPessoas->nome      = $oPatrimonio->cgm_nome;
          $oTBPessoas->cpfcnpj   = $oPatrimonio->cpfcnpj;

          try {

            $oTBPessoas->insertValue();
            $oTBPessoas->persist();
          } catch (Exception $eException) {

            throw new Exception("ERRO-0: {$eException->getMessage()}");
          }

          $oPatrimonio->pessoa_id = $oTBPessoas->getLastPk();
        }

        $sSqlOrgao = "
      select orcorgao.o40_orgao as orgao,
             orcorgao.o40_descr as orgao_descricao,
             orcunidade.o41_unidade as unidade,
             orcunidade.o41_descr as unidade_descricao
        from db_departorg
             inner join orcorgao on orcorgao.o40_anousu = db_departorg.db01_anousu 
                                and orcorgao.o40_orgao = db_departorg.db01_orgao
        inner join orcunidade on orcunidade.o41_unidade = db_departorg.db01_unidade
                             and orcunidade.o41_orgao   = orcorgao.o40_orgao 
      where db_departorg.db01_anousu = {$iAnoAtual}
        and db_departorg.db01_coddepto = {$oPatrimonio->codigo_departamento}
      limit 1";

        $rsOrgao = db_query($connOrigem, $sSqlOrgao);
        if (!empty($rsOrgao) && pg_num_rows($rsOrgao) > 0) {

          $oOrgao = db_utils::fieldsMemory($rsOrgao, 0);

          $sSqlOrgaoDestino = "
        select id
          from orgaos
         where codorgao = {$oOrgao->orgao}
           and exercicio = {$iAnoAtual}
         limit 1
      ";

          $rsOrgaoDestino = db_query($connDestino, $sSqlOrgaoDestino);

          if (!empty($rsOrgaoDestino) && pg_num_rows($rsOrgaoDestino) > 0) {

            $oOrgaoDestino         = db_utils::fieldsMemory($rsOrgaoDestino, 0);
            $oPatrimonio->orgao_id = $oOrgaoDestino->id;

            $sSqlOrgaoUnidade = "
          select id
            from unidades
           where orgao_id   = {$oOrgaoDestino->id} 
             and codunidade = {$oOrgao->unidade} limit 1
        ";

            $rsOrgaoUnidade = db_query($connDestino, $sSqlOrgaoUnidade);
            if (!empty($rsOrgaoUnidade) && pg_num_rows($rsOrgaoUnidade) > 0) {

              $oOrgaoUnidade           = db_utils::fieldsMemory($rsOrgaoUnidade, 0);
              $oPatrimonio->unidade_id = $oOrgaoUnidade->id;
            }
          }

          $sSqlDepreciacao = "
        select t44_benstipodepreciacao as tipo_depreciacao,
               t44_benstipoaquisicao as tipo_aquisicao,
               t44_vidautil as vidautil,
               t44_valoratual as depreciacao,
               t44_valorresidual as valor_residual,
               (t44_valorresidual + t44_valoratual) as valor_atual,
               t46_sequencial as codigo_tipo_depreciacao
          from bensdepreciacao
               inner join benstipodepreciacao on benstipodepreciacao.t46_sequencial = bensdepreciacao.t44_benstipodepreciacao
         where bensdepreciacao.t44_bens = {$oPatrimonio->codigo};
      ";

          $rsDepreciacao = db_query($connOrigem, $sSqlDepreciacao);

          if (!empty($rsDepreciacao) && pg_num_rows($rsDepreciacao) > 0) {

            $oDepreciacao                         = db_utils::fieldsMemory($rsDepreciacao, 0);
            $oPatrimonio->bem_tipo_depreciacao_id = $aBemTipoDepreciacao[$oDepreciacao->codigo_tipo_depreciacao];
            $oPatrimonio->tipo_depreciacao        = $oDepreciacao->tipo_depreciacao;
            $oPatrimonio->valor_depreciavel       = $oDepreciacao->depreciacao;
            $oPatrimonio->valor_atual             = $oDepreciacao->valor_atual;
            $oPatrimonio->valor_residual          = $oDepreciacao->valor_residual;
          } else {

            $oPatrimonio->bem_tipo_depreciacao_id = 0;
            $oPatrimonio->tipo_depreciacao        = 0;
            $oPatrimonio->valor_depreciavel       = 0;
            $oPatrimonio->valor_atual             = 0;
            $oPatrimonio->valor_residual          = 0;
            $oPatrimonio->oDepreciacao            = 0;
          }

          $oPatrimonio->bem_aquisicao_tipo_id = 0;
          if (!empty($aBemAquisicaoTipo[$oDepreciacao->tipo_aquisicao])) {
            $oPatrimonio->bem_aquisicao_tipo_id = $aBemAquisicaoTipo[$oDepreciacao->tipo_aquisicao];
          }

          $oPatrimonio->bem_classificacao_id = $aBemClassificacao[$oPatrimonio->classificacao_bem];

          $oPatrimonio->baixa = "Em uso";
          $sSqlBaixa          = "
        select *
          from bensbaix
         where bensbaix.t55_codbem = {$oPatrimonio->codigo};
      ";

          $rsBaixa = db_query($connOrigem, $sSqlBaixa);

          if (!empty($rsBaixa) && pg_num_rows($rsBaixa) > 0) {

            $oBaixa = db_utils::fieldsMemory($rsBaixa, 0);

            if (pg_num_rows($rsBaixa) > 1) {

              $oPatrimonio->baixa = "Baixado";
            }
          }
          $oPatrimonio->situacao = $oPatrimonio->baixa;

          $oPatrimonio->id             = '';
          $oPatrimonio->instituicao_id = $aInstituicoes[$oPatrimonio->instituicao];

          $oTBBens->setByLineOfDBUtils($oPatrimonio);

          try {
            $oTBBens->insertValue();
          } catch (Exception $eException) {
            throw new Exception("ERRO-0: {$eException->getMessage()}");
          }
        }
      }

      try {
        $oTBBens->persist();
      } catch (Exception $eErro) {
        throw new Exception("ERRO-0: {$eErro->getMessage()}");
      }
    } // fim condicao lPularImportacaoPatrimonio


    $iExercicioBase = EXERCICIO_BASE;
    // FIM IMPORTACAO DE PATRIMONIOS //

    // IMPORTACAO DE VEICULOS     //

    // IMPORTA TIPOS DE UTILIZACAO DE VEICULOS
    db_logTitulo(" IMPORTA TIPOS DE UTILIZACAO DE VEICULOS", $sArquivoLog, $iParamLog);

    $sSqlVeiculoUtilizacao = "
        select
          ve14_sequencial as codigo,
          ve14_descr as descricao
        from
          veiccadutilizacao;
      ";

    $rsVeiculoUtilizacao    = db_query($connOrigem, $sSqlVeiculoUtilizacao);
    $iRowsVeiculoUtilizacao = pg_num_rows($rsVeiculoUtilizacao);

    if ( $iRowsVeiculoUtilizacao ==  0 ) {
      db_Log('Nenhuma tipo de utilizacao de veiculo encontrado!', $sArquivoLog, $iParamLog);
    }

    $sSqlTemVeiculos     = "select count(*) as resultado from veiculos";
    $rsTemVeiculos       = db_query($sSqlTemVeiculos);
    $iQuantidadeVeiculos = 0;
    if ($rsTemVeiculos && pg_num_rows($rsTemVeiculos) === 1) {
      $iQuantidadeVeiculos = (int) db_utils::fieldsMemory($rsTemVeiculos, 0)->resultado;
    }

    $lPularImportacaoVeiculos = false;
    if ($iQuantidadeVeiculos === 0) {

      db_Log('Nenhuma veiculo encontrado. Pulando importacao de veiculos.', $sArquivoLog, $iParamLog);
      $lPularImportacaoVeiculos = true;
    }

    if (!$lPularImportacaoVeiculos) {
      $aVeiculoUtilizacao = array();
      db_logNumReg($iRowsVeiculoUtilizacao,$sArquivoLog,$iParamLog);

      for ( $iInd=0; $iInd < $iRowsVeiculoUtilizacao; $iInd++ ) {

        logProcessamento($iInd,$iRowsVeiculoUtilizacao,$iParamLog);

        $oVeiculoUtilizacao = db_utils::fieldsMemory($rsVeiculoUtilizacao, $iInd);

        $oTBVeiculoUtilizacao->setByLineOfDBUtils($oVeiculoUtilizacao);

        try {

          $oTBVeiculoUtilizacao->insertValue();
          $oTBVeiculoUtilizacao->persist();
        } catch ( Exception $eException ) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
        $aVeiculoUtilizacao[$oVeiculoUtilizacao->codigo] = $oTBVeiculoUtilizacao->getLastPk();
      }
      // FIM IMPORTA TIPOS DE UTILIZACAO DE VEICULOS


      // IMPORTA TIPOS DE VEICULOS
      db_logTitulo(" IMPORTA TIPOS DE VEICULOS", $sArquivoLog, $iParamLog);

      $sSqlVeiculoTipo = "
        select
          ve20_codigo as id,
          ve20_descr as descricao
        from
          veiccadtipo;
      ";

      $rsVeiculoTipo    = db_query($connOrigem, $sSqlVeiculoTipo);
      $iRowsVeiculoTipo = pg_num_rows($rsVeiculoTipo);

      if ( $iRowsVeiculoTipo ==  0 ) {

        throw new Exception('Nenhuma tipo de veiculo encontrado!');
      }

      $aVeiculoTipo = array();
      db_logNumReg($iRowsVeiculoTipo,$sArquivoLog,$iParamLog);

      for ( $iInd=0; $iInd < $iRowsVeiculoTipo; $iInd++ ) {

        logProcessamento($iInd,$iRowsVeiculoTipo,$iParamLog);

        $oVeiculoTipo = db_utils::fieldsMemory($rsVeiculoTipo, $iInd);

        $oTBVeiculoTipo->setByLineOfDBUtils($oVeiculoTipo);

        try {

          $oTBVeiculoTipo->insertValue();
          $oTBVeiculoTipo->persist();
        } catch ( Exception $eException ) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
        $aVeiculoTipo[$oVeiculoTipo->id] = $oTBVeiculoTipo->getLastPk();
      }
      // FIM IMPORTA TIPOS DE VEICULOS

      // IMPORTA MARCAS DE VEICULOS
      db_logTitulo(" IMPORTA MARCAS DOS VEICULOS", $sArquivoLog, $iParamLog);

      $sSqlVeiculoMarca = "
        select
          ve21_codigo as codigo,
          ve21_descr as descricao
        from
          veiccadmarca;
      ";

      $rsVeiculoMarca    = db_query($connOrigem, $sSqlVeiculoMarca);
      $iRowsVeiculoMarca = pg_num_rows($rsVeiculoMarca);

      if ( $iRowsVeiculoMarca ==  0 ) {

        throw new Exception('Nenhuma marca de veiculo encontrada!');
      }

      $aVeiculoMarca = array();
      db_logNumReg($iRowsVeiculoMarca,$sArquivoLog,$iParamLog);

      for ( $iInd=0; $iInd < $iRowsVeiculoMarca; $iInd++ ) {

        logProcessamento($iInd,$iRowsVeiculoMarca,$iParamLog);

        $oVeiculoMarca = db_utils::fieldsMemory($rsVeiculoMarca,$iInd);
        $oVeiculoMarca->id = '';

        $oTBMarca->setByLineOfDBUtils($oVeiculoMarca);

        try {

          $oTBMarca->insertValue();
          $oTBMarca->persist();
        } catch ( Exception $eException ) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
        $aVeiculoMarca[$oVeiculoMarca->codigo] = $oTBMarca->getLastPk();
      }
      // FIM IMPORTA MARCAS DE VEICULOS

      // IMPORTA MODELOS DE VEICULOS
      db_logTitulo(" IMPORTA MODELO DOS VEICULOS", $sArquivoLog, $iParamLog);

      $sSqlVeiculoModelo = "
        select
          ve22_codigo as codigo,
          ve22_descr as descricao
        from
          veiccadmodelo;
      ";

      $rsVeiculoModelo    = db_query($connOrigem, $sSqlVeiculoModelo);
      $iRowsVeiculoModelo = pg_num_rows($rsVeiculoModelo);

      if ( $iRowsVeiculoModelo ==  0 ) {

        throw new Exception('Nenhum modelo de veiculo encontrado!');
      }

      $aVeiculoModelo = array();
      db_logNumReg($iRowsVeiculoModelo,$sArquivoLog,$iParamLog);

      for ( $iInd=0; $iInd < $iRowsVeiculoModelo; $iInd++ ) {

        logProcessamento($iInd,$iRowsVeiculoModelo,$iParamLog);

        $oVeiculoModelo = db_utils::fieldsMemory($rsVeiculoModelo,$iInd);
        $oVeiculoModelo->id = '';

        $oTBModelo->setByLineOfDBUtils($oVeiculoModelo);

        try {

          $oTBModelo->insertValue();
          $oTBModelo->persist();
        } catch ( Exception $eException ) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
        $aVeiculoModelo[$oVeiculoModelo->codigo] = $oTBModelo->getLastPk();
      }
      // FIM IMPORTA MODELOS DE VEICULOS

      // INICIO IMPORTA VEICULOS
      db_logTitulo(" IMPORTA VEICULOS", $sArquivoLog, $iParamLog);

      $sSqlVeiculo = "
        select distinct
          ve01_codigo     as codigo,
          ve36_sequencial as codigo_central,
          descrdepto      as central,
          coddepto        as departamento,
          instit          as instituicao,
          ve01_placa      as placa,
            ve20_codigo     as veiculo_tipo,
          ve20_descr      as descr_tipo,
          ve22_descr      as descr_modelo,
          ve22_codigo     as modelo,
          ve21_descr      as descr_marca,
          ve21_codigo     as marca,
          ve01_anofab     as ano_fabricacao,
          ve01_anomod     as ano_modelo,
          ve23_descr      as cor,
          ve01_dtaquis as data_aquisicao,
          ve32_descr as categoria,
          ve25_descr as procedencia,
          (ve01_quantpotencia || '/' || ve31_descr) as potencia,
          ve30_descr as tipocnh,
          ve01_ranavam    as renavam,
          ve26_descr as combustivel,
          ve15_veiccadutilizacao as utilizacao,
          EXTRACT(YEAR FROM ve01_dtaquis) as exercicio,
          case
          when (select ve04_veiculo from veicbaixa where ve04_veiculo = veiccentral.ve40_veiculos) is not null
          then 'Baixado'
          else 'Em uso'
          end as situacao
        from veiccadcentral
          inner join db_depart     on db_depart.coddepto              = veiccadcentral.ve36_coddepto
          inner join veiccentral   on veiccentral.ve40_veiccadcentral = veiccadcentral.ve36_sequencial
          inner join veiculos      on veiculos.ve01_codigo            = veiccentral.ve40_veiculos
          inner join veiccadmodelo on veiccadmodelo.ve22_codigo       = veiculos.ve01_veiccadmodelo
          inner join veiccadtipo   on veiccadtipo.ve20_codigo         = veiculos.ve01_veiccadtipo
          inner join veiccadmarca  on veiccadmarca.ve21_codigo        = veiculos.ve01_veiccadmarca
          inner join veicutilizacao  on veicutilizacao.ve15_veiculos        = veiculos.ve01_codigo
          inner join veiccadutilizacao on veiccadutilizacao.ve14_sequencial = veicutilizacao.ve15_veiccadutilizacao
          left join veiccadcor on veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor
          left join veiccadproced on veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced
          left join veiccadpotencia on veiccadpotencia.ve31_codigo   = veiculos.ve01_veiccadpotencia
          left join veiccadcategcnh on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh
          left join veiccadcateg  on veiccadcateg.ve32_codigo = veiculos.ve01_veiccadcateg
          left join veicabast on veicabast.ve70_veiculos = veiculos.ve01_codigo
          left join veiccadcomb on veiccadcomb.ve26_codigo = veicabast.ve70_veiculoscomb
      ";

      $rsVeiculo    = db_query($connOrigem, $sSqlVeiculo);
      $iRowsVeiculo = pg_num_rows($rsVeiculo);

      if ( $iRowsVeiculo ==  0 ) {
        db_logTitulo(" Nenhum veiculo encontrado!", $sArquivoLog, $iParamLog);
      }

      $aVeiculo = array();
      db_logNumReg($iRowsVeiculo,$sArquivoLog,$iParamLog);

      for ( $iInd=0; $iInd < $iRowsVeiculo; $iInd++ ) {

        logProcessamento($iInd,$iRowsVeiculo,$iParamLog);

        $oVeiculo = db_utils::fieldsMemory($rsVeiculo, $iInd);

        $oVeiculo->instituicao_id  = $aInstituicoes[$oVeiculo->instituicao];
        $oVeiculo->departamento_id = $aDepartamento[$oVeiculo->departamento];
        $oVeiculo->marca_id        = $aVeiculoMarca[$oVeiculo->marca];
        $oVeiculo->modelo_id       = $aVeiculoModelo[$oVeiculo->modelo];
        $oVeiculo->veiculo_tipo_id = $aVeiculoTipo[$oVeiculo->veiculo_tipo];

        if(!empty($aVeiculoUtilizacao[$oVeiculo->utilizacao])){

          $oVeiculo->veiculo_utilizacao_id = $aVeiculoUtilizacao[$oVeiculo->utilizacao];
        } else {

          $oVeiculo->veiculo_utilizacao_id = 0;
        }

        $oTBVeiculo->setByLineOfDBUtils($oVeiculo);

        try {

          $oTBVeiculo->insertValue();
          $oTBVeiculo->persist();
        } catch ( Exception $eException ) {

          throw new Exception("ERRO-0: {$eException->getMessage()}");
        }
      }
      // FIM IMPORTA VEICULOS
    } // fim condicao $lPularImportacaoVeiculos
    // FIM IMPORTACAO VEICULOS
  }

  /**
   * Importacao das Licitacoes
   */
  $oIntegracaoPortalTransparencia = new IntegracaoPortalTransparencia();
  $oIntegracaoPortalTransparencia->setConexaoDestino($connDestino);
  $oIntegracaoPortalTransparencia->setConexaoOrigem($connOrigem);
  $oIntegracaoPortalTransparencia->setAnoInicioIntegracao($iExercicioBase);
  $oIntegracaoPortalTransparencia->setArquivoLog($sArquivoLog);
  $oIntegracaoPortalTransparencia->setParamLog($iParamLog);

  foreach ($aIntegracoesRealizar as $sIntegracao) {

    if (trim($sIntegracao) == ''){continue;}
    $oIntegracaoPortalTransparencia->adicionarIntegracao(new $sIntegracao);
  }

  db_query($connOrigem, "begin");
  $oIntegracaoPortalTransparencia->executar();
  db_query($connOrigem, "commit");

  /**
   * Exclui os large objects vinculados ao schema de backup
   */

  $aLargeObjectsToRemove = array( 'licitacoes_documentos' => 'documento',
                                  'acordo_documentos' => 'arquivo' );

  if ( $iLinhasSchemasAtual > 0 ) {

    foreach ($aLargeObjectsToRemove as $sTabela => $sNomeCampoOid) {

      $rsFilesToRemove = db_query($connDestino, "select {$sNomeCampoOid} from {$sBkpSchema}.{$sTabela}");

      if (!pg_num_rows($rsFilesToRemove)) {
        continue;
      }

      for ($iIndice = 0; $iIndice < pg_num_rows($rsFilesToRemove); $iIndice++) {

        $oRow            = db_utils::fieldsMemory($rsFilesToRemove, $iIndice);
        $sSqlBuscaObjeto = "select pg_largeobject.loid
                              from pg_largeobject
                             where pg_largeobject.loid = {$oRow->{$sNomeCampoOid}} ";
        $rsBuscaObjeto   = pg_query($connDestino, $sSqlBuscaObjeto);

        if (pg_num_rows($rsBuscaObjeto) > 0) {

          $lUnlink = pg_lo_unlink($connDestino, $oRow->{$sNomeCampoOid});
          if (!$lUnlink) {
            continue;
          }
        }
      }
    }
  }

  // EXCLUSÃO DE SCHEMAS ANTIGOS ************************************************************************************//

  $sSqlConsultaSchemasAntigos = "select distinct schema_name
                                   from information_schema.schemata
                                  where schema_name like 'bkp_transparencia_%'
                                  order by schema_name desc
                                 offset {$iNroBasesAntigas} ";

  $rsSchemasAntigos      = db_query($connDestino,$sSqlConsultaSchemasAntigos);
  $iLinhasSchemasAntigos = pg_num_rows($rsSchemasAntigos);

  for ($iInd=0; $iInd < $iLinhasSchemasAntigos; $iInd++ ) {

    $oSchemaAntigo = db_utils::fieldsMemory($rsSchemasAntigos,$iInd);

    $sSqlExcluiSchemaAntigo = " DROP SCHEMA {$oSchemaAntigo->schema_name} CASCADE ";

    if ( !db_query($connDestino,$sSqlExcluiSchemaAntigo) ) {
      throw new Exception("ERRO-0: Erro ao excluir schema {$oSchemaAntigo->schema_name} !");
    }

  }

  // FIM DA EXCLUSÃO DE SCHEMAS ANTIGOS *****************************************************************************//


  if ( $iLinhasSchemasAtual > 0 ) {

    // ACERTA TABELA empenhos_movimentacoes_exercicios ****************************************************************//


    $sSqlAcertaEmpMovExerc = " INSERT INTO empenhos_movimentacoes_exercicios (empenho_id,exercicio)
                                select distinct empenho_id,
                                       extract( year from data) as exercicio
                                  from empenhos_movimentacoes ";

    $rsAcertaEmpMovExerc = db_query($connDestino,$sSqlAcertaEmpMovExerc);

    if ( !$rsAcertaEmpMovExerc ) {
      throw new Exception("ERRO-0: Erro ao acertar tabela empenhos_movimentacoes_exercicios !");
    }

    // ****************************************************************************************************************//


    // ACERTA GLOSSARIOS TIPOS ****************************************************************************************//


    $sSqlGlossariosTipos    = " select *
                                  from {$sBkpSchema}.glossarios_tipos ";

    $rsDadosGlossariosTipos = db_query($connDestino,$sSqlGlossariosTipos);

    if ( !$rsDadosGlossariosTipos ) {
      throw new Exception("ERRO-0: Erro ao consultar tabela glossarios_tipos !");
    }

    $iLinhasGlossariosTipos = pg_num_rows($rsDadosGlossariosTipos);

    for ( $iInd=0; $iInd < $iLinhasGlossariosTipos; $iInd++ ) {

      $oGloassariosTipos = db_utils::fieldsMemory($rsDadosGlossariosTipos,$iInd);

      $oTBGlossariosTipos->setByLineOfDBUtils($oGloassariosTipos);

      try {
        $oTBGlossariosTipos->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }

    }

    try {
      $oTBGlossariosTipos->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    // ****************************************************************************************************************//

    // ACERTA GLOSSARIOS **********************************************************************************************//

    $sSqlGlossarios    = " select *
                                  from {$sBkpSchema}.glossarios ";

    $rsDadosGlossarios = db_query($connDestino,$sSqlGlossarios);

    if ( !$rsDadosGlossarios ) {
      throw new Exception("ERRO-0: Erro ao consultar tabela glossarios !");
    }

    $iLinhasGlossarios = pg_num_rows($rsDadosGlossarios);

    for ( $iInd=0; $iInd < $iLinhasGlossarios; $iInd++ ) {

      $oGloassarios = db_utils::fieldsMemory($rsDadosGlossarios,$iInd);

      $oTBGlossarios->setByLineOfDBUtils($oGloassarios);

      try {
        $oTBGlossarios->insertValue();
      } catch ( Exception $eException ) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
      }

    }

    try {
      $oTBGlossarios->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

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
function logProcessamento($iInd,$iTotalLinhas,$iParamLog) {

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

function db_logNumReg($iLinhas,$sArquivoLog,$iParamLog) {

  db_log("Total de Registros Encontrados : {$iLinhas}",$sArquivoLog,$iParamLog);
  db_log("\n",$sArquivoLog,1);
}
