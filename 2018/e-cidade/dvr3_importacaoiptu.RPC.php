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
define('MENSAGENS_DVR3_IMPORTACAOIPTU_RPC', 'tributario.diversos.dvr3_importacaoiptu.');
define('TIPO_DEBITO_PARCELAMENTO', 16);

use ECidade\Tributario\Divida\ImportacaoDividaAtiva;
use ECidade\Tributario\Divida\ImportacaoFactory;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sql.php"));

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->status = 1;
$oRetorno->message = '';

$lErro = false;
$sMsg = '';
$dtHoje = date("Y-m-d", db_getsession("DB_datausu"));

db_app::import('diversos.ImportacaoDiversos');
db_app::import('diversos.ImportacaoGeralDiversos');
db_app::import('exceptions.*');

switch ($oParam->sExec) {

    case 'getDebitosOrigem':

        $aNumpres = array();

        foreach ($oParam->aDebitos as $debito) {
            $aNumpres[$debito[0]] = $debito[0];
        }

        $aOrigens = array();

        foreach ($aNumpres as $numpre) {
            $parcelamento = verificarParcelamento($numpre);

            if (!empty($parcelamento)) {
                $aOrigens = array_merge($aOrigens, buscarOrigemParcelamento($parcelamento));
            }
        }

        if (empty($aOrigens)) {
            $aOrigens = $oParam->aDebitos;
        }

        $oRetorno->aDebitos = $aOrigens;

        break;
    case 'getDebitosImportados' :

        $oDaoDiverImporta = new cl_diverimporta();

        if ($oParam->iTipoPesquisa == 5) {

            $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_queryDebitosImportadosAlvara($oParam->iChavePesquisa);
        } else {

            $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_query_debitos_importados($oParam->iTipoPesquisa, $oParam->iChavePesquisa);
        }

        $rsDaoDiverImporta = $oDaoDiverImporta->sql_record($sSqlDaoDiverImporta);

        if ($oDaoDiverImporta->numrows > 0) {

            $oRetorno->aDebitos = db_utils::getCollectionByRecord($rsDaoDiverImporta, false, false, true);
            $oDaoProcdiver = new cl_procdiver();
        } else {

            $oRetorno->status = 2;
            $oRetorno->message = 'Nenhum registro encontrado.';
        }

        break;

    /**
     * Cancelamento de importação de diversos
     */
    case 'cancelaImportacao':

        try {

            db_inicio_transacao();

            $sMensagem = 'cancelamento_sucesso';

            foreach ($oParam->aCodigosImportacao as $iCodigoImportacao) {

                $oImportacaoDiversos = new ImportacaoDiversos($iCodigoImportacao);
                if ($oImportacaoDiversos->cancelar() == ImportacaoDiversos::CANCELAMENTO_PARCIAL) {
                    $sMensagem = "cancelamento_parcial";
                }
            }

            db_fim_transacao();
            $oRetorno->status = 1;
            $oRetorno->message = urlencode(_M('tributario.diversos.ImportacaoDiversos.' . $sMensagem));

        } catch (Exception $sException) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($sException->getMessage());
        }

        break;

    //lista debitos da matricula
    case 'getDebitos':

        try {

            $oRetorno->aProcdiver = array();
            $oRetorno->procedenciaDividaAtiva = array();
            $oRetorno->aDebitos = array();

            $oDaoDiverImporta = new cl_diverimporta();
            $origemDebito = !empty($oParam->origemDebito) ? $oParam->origemDebito : null;

            $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_query_cobranca_adm($oParam->iTipoPesquisa, $oParam->aChavePesquisa, $origemDebito);
            if (empty($origemDebito)) {

                if ($oParam->iTipoPesquisa == 5) {
                    $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_queryImportacaoAlvara($oParam->aChavePesquisa);
                } else {
                    $sSqlDaoDiverImporta = $oDaoDiverImporta->sql_query_importa_iptu($oParam->iTipoPesquisa, $oParam->aChavePesquisa);
                }
            }

            $rsDaoDiverImporta = $oDaoDiverImporta->sql_record($sSqlDaoDiverImporta);
            if ($oDaoDiverImporta->erro_status === '0' || $oDaoDiverImporta->numrows === 0) {
                throw new BusinessException('"Nenhum registro encontrado."');
            }

            $oRetorno->aDebitos = db_utils::getCollectionByRecord($rsDaoDiverImporta, false, false, true);

            if (empty($oParam->importaDividaAtiva)) {

                $oDaoProcdiver = new cl_procdiver();
                $sCamposProcDriver = "dv09_procdiver, dv09_descra, dv09_descr, dv09_tipo, dv09_receit as receita";
                $sWhereProcDriver = "(dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}') and dv09_instit = " . db_getsession("DB_instit");
                $sSqlProcedencia = $oDaoProcdiver->sql_query_file(null, $sCamposProcDriver, "dv09_procdiver,dv09_descra", $sWhereProcDriver);
                $rsBuscaProcedencia = $oDaoProcdiver->sql_record($sSqlProcedencia);
                if ($oDaoProcdiver->numrows > 0) {
                    $oRetorno->aProcdiver = db_utils::getCollectionByRecord($rsBuscaProcedencia, false, false, true);
                }
            }

            if (!empty($oParam->importaDividaAtiva) && $oParam->importaDividaAtiva === true) {

                $daoProcedencia = new cl_proced();
                $sCamposProcedencia = 'v03_codigo as codigo, v03_descr as descricao, v03_receit as receita';
                $sSqlProcedencia = $daoProcedencia->sql_query_file(null, $sCamposProcedencia, 'v03_codigo');
                $resBuscaProcedencia = db_query($sSqlProcedencia);
                if (!$resBuscaProcedencia || pg_num_rows($resBuscaProcedencia) === 0) {
                    throw new BusinessException("Não foi encontrada nenhuma procedência para importação de Dívida Ativa.");
                }
                $oRetorno->procedenciaDividaAtiva = db_utils::getCollectionByRecord($resBuscaProcedencia);
            }
        } catch (Exception $e) {

            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = $e->getMessage();
        }


        break;

    /**
     * Importação de diversos
     */
    case 'processarDebitos':

        $oDadosProcessamento = $oParam->oDadosProcessamento;

        $lDesativarAccount   = db_getsession("DB_desativar_account", false);

        try {

            db_putsession("DB_desativar_account", false);

            db_inicio_transacao();

            $oProcedenciaDiversos = new ImportacaoDiversos(null);

            /**
             * Tipo de pesquisa
             * 1 - CODIGO IMPORTACAO
             * 2 - MATRICULA
             * 3 - CGM
             * 4 - Debitos (vindos da CGF)
             * 5 - Inscrição
             */
            $sObservacao = "";
            $oProcedenciaDiversos->setTipoOrigem($oDadosProcessamento->iTipoPesquisa);
            $oProcedenciaDiversos->setCodigoOrigem($oDadosProcessamento->aChavePesquisa[0]);



            $sObservacao = $oDadosProcessamento->sObservacoes;

            foreach ($oDadosProcessamento->aDebitos as $oDebito) {

                $oProcedenciaDiversos->importarDiversos($oDebito->iCodigoProcedencia,
                    $oDebito->iNumpre,
                    $oDebito->iNumpar,
                    $oDebito->iReceita);
            }
            $oProcedenciaDiversos->processar($sObservacao);

            db_fim_transacao();


            $oRetorno->status = 1;
            $oRetorno->message = urlencode("Débitos processados com sucesso.");

        } catch (Exception $sException) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($sException->getMessage());
        }

        db_putsession("DB_desativar_account", $lDesativarAccount);

        break;

    /**
     * Busca Receitas
     */
    case 'getReceitasProcedencias':

        try{

            $oDaoProcdiver = new cl_procdiver();
            $oDaoArrecad = new cl_arrecad();

            $where = array();
            $where[] = "(dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}') and dv09_instit = ". db_getsession("DB_instit");
            if (!empty($oParam->tipoDebitoDestino)) {
                $where[] = "dv09_tipo = {$oParam->tipoDebitoDestino}";
            }

            $sSqlProcedencia = $oDaoProcdiver->sql_query_file(null,
                "dv09_procdiver as codigo, dv09_descra as descricao, dv09_receit as receita",
                "dv09_descra",
                implode(' and ', $where));

            if (!empty($oParam->importaDividaAtiva) && $oParam->importaDividaAtiva === true) {

                $daoProced = new cl_proced();
                $sCamposProcedencia = 'v03_codigo as codigo, v03_descr as descricao, v03_receit as receita';
                $sSqlProcedencia = $daoProced->sql_query_file(null, $sCamposProcedencia, 'v03_codigo');
            }

            $rsBuscaProcedencia = db_query($sSqlProcedencia);
            if (!$rsBuscaProcedencia) {

                $oMensagem = (object)array('sErro'=>pg_last_error());
                throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'erro_buscar_dados_procedencia',$oMensagem));
            }

            if (pg_num_rows($rsBuscaProcedencia) == 0) {
                throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'nenhuma_procedencia_encontrada'));
            }

            $buscarPorCadTipo = true;

            if ($oParam->tipoDebitoOrigem) {
                $oParam->iCadTipo = $oParam->tipoDebitoOrigem;
                $buscarPorCadTipo = false;
            }

            $buscarDebitosOrigem = false;

            if ($oParam->importaDividaAtiva === true) {

                $daoArretipo = new cl_arretipo();
                $sqlArretipo = $daoArretipo->sql_query($oParam->tipoDebitoOrigem, "cadtipo.k03_tipo as tipo");
                $rsArretipo  = $daoArretipo->sql_record($sqlArretipo);

                if (!$rsArretipo) {
                    throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . "tipo_origem_invalido"));
                }

                $oArretipo = db_utils::fieldsMemory($rsArretipo, 0);

                if ($oArretipo->tipo == TIPO_DEBITO_PARCELAMENTO) {

                    $buscarDebitosOrigem = true;
                    $oParam->iCadTipo = TIPO_DEBITO_PARCELAMENTO;
                }
            }

            $sSqlDebitos = $oDaoArrecad->sql_query_getReceitasTipo($oParam->iCadTipo, $buscarPorCadTipo);

            if ($buscarDebitosOrigem) {

                $daoArreold = new cl_arreold();
                $sSqlDebitos = $daoArreold->sql_query_getReceitasTipo();
            }

            $rsDAODebitos = db_query($sSqlDebitos);

            if (!$rsDAODebitos) {

                $oMensagem = (object)array('sErro'=>pg_last_error());
                throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'erro_buscar_dados_receitas',$oMensagem));
            }

            if (pg_num_rows($rsDAODebitos) == 0) {
                throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'nenhuma_receita_encontrada'));
            }

            $oRetorno->aReceitas     = db_utils::getCollectionByRecord($rsDAODebitos,   false, false, true);
            $oRetorno->aProcedencias = db_utils::getCollectionByRecord($rsBuscaProcedencia, false, false, true);

        } catch (Exception $oException) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oException->getMessage());
        }

        break;

    /**
     * Busca Receitas
     */
    case 'getImportacaoGeral':

        try{

            $oDaoDiverimporta = new cl_diverimporta();

            $sWhere  = '     exists (select 1 from diverimportareg where dv12_diverimporta = dv11_sequencial)';
            $sWhere .= ' and (extract(year from dv11_data) >= '.db_getsession('DB_anousu');
            $sWhere .= ' and dv11_instit = '. db_getsession("DB_instit") .')';

            $sSqlDiverimporta = $oDaoDiverimporta->sql_query_file(null,
                "dv11_sequencial, dv11_data, dv11_hora, dv11_tipo, dv11_obs",
                "dv11_sequencial",
                $sWhere);

            $rsDAODiverimporta = db_query($sSqlDiverimporta);

            if (!$rsDAODiverimporta) {

                $oMensagem = (object)array('sErro'=>pg_last_error());
                throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'erro_buscar_importacoes',$oMensagem));
            }

            if (pg_num_rows($rsDAODiverimporta) == 0) {
                throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'nenhuma_importacao_geral_encontrada'));
            }

            $oRetorno->aImportacoes = db_utils::getCollectionByRecord($rsDAODiverimporta, false, false, true);

        } catch (Exception $oException) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oException->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }

        break;

    /**
     * Importação geral de diversos
     */
    case 'importacaoGeralDiversos':

        try {

            $aDadosImportacao = $oParam->aDados;
            $iQuantidadeParcelas = $oParam->iQuantidadeParcelas;
            $sObservacoes = $oParam->sObservacoes;

            $oImportacao = new ImportacaoGeralDiversos(null);

            foreach ($aDadosImportacao as $aDadoImportacao){

                if(empty($aDadoImportacao->iCodigoProcedencia)){
                    continue;
                }
                $oData = $aDadoImportacao->iVencimento == '' ? null : new DBDate($aDadoImportacao->iVencimento);
                $oImportacao->adicionarReceita($aDadoImportacao->iCodigoReceita, $oData, new ProcedenciaDiversos($aDadoImportacao->iCodigoProcedencia));
            }

            if (!empty($iQuantidadeParcelas)) {
                $oImportacao->setQuantidadeParcelas($iQuantidadeParcelas);
            }

            $oImportacao->setObservacoes($sObservacoes);

            db_inicio_transacao();
            $oImportacao->processar(true);
            db_fim_transacao();

            $oRetorno->status = 1;
            $oRetorno->message = urlencode(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'sucesso_importacao_geral'));
            echo $oJson->encode($oRetorno);
            exit;
        } catch (Exception $oException) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oException->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }

        break;

    /**
     * Busca os tipos de débitos que um contribuinte possui e tipos de débitos de Diversos
     */
    case 'tiposDebitoParcial':

        try {

            if (empty($oParam->iChavePesquisa)) {
                throw new ParameterException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."codigo_pesquisa_nao_informado"));
            }

            if (empty($oParam->iTipoPesquisa)) {
                throw new ParameterException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."tipo_chave_nao_informado"));
            }

            /**
             * Busca os tipos de débitos de origem com base no contribuinte informado
             */
            $oDaoArrecad = new cl_arrecad();

            $aCampos = array(
                "distinct arretipo.k00_tipo",
                "arretipo.k00_descr",
                "arretipo.k03_tipo"
            );

            $aWhere = array("cadtipo.k03_tipo not in(5, 6, 7, 12, 13, 15, 16, 18)");
            $iTipoDebito = null;

            if ( $oParam->importaDividaAtiva ) {
                $aWhere = array("cadtipo.k03_tipo in(7, 16)");
                $iTipoDebito = 5;
            }

            $sSqlArrecad = $oDaoArrecad->sqlDebitosPorTipoContribuinte($aCampos, $aWhere, $oParam->iChavePesquisa, $oParam->iTipoPesquisa);
            $rsArrecad = db_query($sSqlArrecad);

            if (!$rsArrecad) {
                throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."erro_buscar_tipos_debito_origem"));
            }

            if (pg_num_rows($rsArrecad) == 0) {
                throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."nenhum_tipo_debito_origem_encontrado"));
            }

            /**
             * Guarda os tipos de débitos retornados, para envio a tela
             */
            $oRetorno->aOpcoesTipoOrigem = db_utils::getCollectionByRecord($rsArrecad, false, false, true);
            $oRetorno->aOpcoesTipoDestino = debitosExercicio($iTipoDebito);
        } catch(Exception $oException) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oException->getMessage());
        }

        break;

    /**
     * Busca os tipos de débitos, com valor, do exercício
     */
    case 'tiposDebitoGeral':

        try {

            $oDaoArrecad = new cl_arrecad();

            $aCampos = array(
                "distinct arretipo.k00_tipo",
                "arretipo.k00_descr"
            );

            $aWhere = array("cadtipo.k03_tipo not in(5, 6, 7, 12, 13, 15, 16, 18)");

            if ( $oParam->importaDividaAtiva ) {
                $aWhere = array("cadtipo.k03_tipo in(7, 16)");
            }

            $sSqlArrecad = $oDaoArrecad->sqlTiposDebitosGeral($aCampos, $aWhere);
            $rsArrecad = db_query($sSqlArrecad);

            if(!$rsArrecad) {
                throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'erro_buscar_tipos_debito_origem'));
            }

            if(pg_num_rows($rsArrecad) == 0) {
                throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . 'nenhum_tipo_debito_origem_encontrado'));
            }

            /**
             * Guarda os tipos de débitos retornados, para envio a tela
             */
            $iTipoDebito = !empty($oParam->importaDividaAtiva) && $oParam->importaDividaAtiva === true ? 5 : null;
            $oRetorno->aOpcoesTipoOrigem = db_utils::getCollectionByRecord($rsArrecad, false, false, true);
            $oRetorno->aOpcoesTipoDestino = debitosExercicio($iTipoDebito);
        } catch (Exception $oErro) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oException->getMessage());
        }

        break;

    case 'importacaoParcial':

        try {

            $oImportacao = new ImportacaoDiversosCobrancaAdministrativa();
            $oImportacao->setUnificarDebitos($oParam->lUnificarDebitos);
            $oImportacao->setObservacoes(db_stdClass::normalizeStringJsonEscapeString($oParam->oDadosProcessamento->sObservacoes));
            $oImportacao->setDebitos($oParam->oDadosProcessamento->aDebitos);

            if ($oParam->lUnificarDebitos === true) {
                $oImportacao->setOrdemDataVencimento($oParam->iTipoDataVencimento);
            }

            db_inicio_transacao();
            $oImportacao->importacaoParcial();
            db_fim_transacao();

            $oRetorno->status = 1;
            $oRetorno->erro = false;
            $oRetorno->message = urlencode(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."sucesso_importacao_geral"));
            echo $oJson->encode($oRetorno);
            exit;

        } catch (Exception $oException) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($oException->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }

        break;

    case 'importacaoGeralDiversosCobrancaAdministrativa':

        try {

            $oImportacao = new ImportacaoDiversosCobrancaAdministrativa();

            $oImportacao->setTipoDebitoOrigem($oParam->iTipoDebitoOrigem);
            $oImportacao->setObservacoes(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacoes));
            $oImportacao->setUnificarDebitos($oParam->lUnificarDebitos);

            foreach ($oParam->aDados as $aDadoImportacao) {
                $oImportacao->adicionarReceita($aDadoImportacao->iCodigoReceita, null, new ProcedenciaDiversos($aDadoImportacao->iCodigoProcedencia));
            }

            if ($oParam->lUnificarDebitos) {
                $oImportacao->setOrdemDataVencimento((int)$oParam->iTipoDataVencimento);
            }

            db_inicio_transacao();
            $oImportacao->importacaoGeral();
            db_fim_transacao();

            $oRetorno->erro = false;
            $oRetorno->message = urlencode(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."sucesso_importacao_geral"));
            echo $oJson->encode($oRetorno);
            exit;

        } catch (Exception $oException) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($oException->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }

        break;

    case 'importacaoGeralDividaParaDividaAtiva':


        try {

            if (empty($oParam->aDados) || count($oParam->aDados) === 0) {
                throw new ParameterException('Não foram informados os parâmetros necessários para execução da importação.');
            }

            db_inicio_transacao();
            $importacao = new ImportacaoDividaAtiva();
            $importacao->setTipoDebitoOrigem($oParam->iTipoDebitoOrigem);
            $importacao->setObservacoes(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacoes));
            $importacao->setUnificarDebitos($oParam->lUnificarDebitos);

            if ($oParam->processoSistema === true && !empty($oParam->codigoProcesso)) {
                $importacao->setProcessoProtocolo(new processoProtocolo($oParam->codigoProcesso));
            }

            if ($oParam->processoSistema === false) {

                $importacao->setProcesso((object)array(
                    'codigo' => $oParam->codigoProcesso,
                    'titular' => $oParam->titularProcesso,
                    'data' => $oParam->dataProcesso,
                ));
            }

            if ($oParam->lUnificarDebitos === true) {
                $importacao->setOrdemDataVencimento($oParam->iTipoDataVencimento);
            }

            foreach ($oParam->aDados as $stdDadosReceita){

                $procedencia = new ProcedenciaDivida($stdDadosReceita->iCodigoProcedencia);
                $importacao->adicionarReceitaProcedencia($stdDadosReceita->iCodigoReceita, $procedencia);
            }
            $importacao->importacaoGeral();

            db_fim_transacao(false);

            $oRetorno->message = urlencode(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."sucesso_importacao_geral"));

        } catch (Exception $e) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = true;
            $oRetorno->message = urlencode($e->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }

        break;

    case "importacaoParcialDividaAtiva":

        try {
            db_inicio_transacao();

            if (empty($oParam->oDadosProcessamento) || count($oParam->oDadosProcessamento->aDebitos) === 0) {
                throw new ParameterException('Não foram informados os parâmetros necessários para execução da importação.');
            }

            db_inicio_transacao();
            $importacao = new ImportacaoDividaAtiva();
            $importacao->setObservacoes(db_stdClass::normalizeStringJsonEscapeString($oParam->oDadosProcessamento->sObservacoes));
            $importacao->setUnificarDebitos($oParam->lUnificarDebitos);
            $importacao->setDebitos((array) $oParam->oDadosProcessamento);

            if ($oParam->processoSistema === true && !empty($oParam->codigoProcesso)) {
                $importacao->setProcessoProtocolo(new processoProtocolo($oParam->codigoProcesso));
            }

            if ($oParam->processoSistema === false) {

                $importacao->setProcesso((object)array(
                    'codigo' => $oParam->codigoProcesso,
                    'titular' => $oParam->titularProcesso,
                    'data' => $oParam->dataProcesso,
                ));
            }

            if ($oParam->lUnificarDebitos === true) {
                $importacao->setOrdemDataVencimento($oParam->iTipoDataVencimento);
            }

            $importacao->importacaoParcial();

            db_fim_transacao(false);

            $oRetorno->status = 1;
            $oRetorno->erro = false;
            $oRetorno->message = urlencode(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC."sucesso_importacao_geral"));
            echo $oJson->encode($oRetorno);
            exit;

        } catch (Exception $e) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = true;
            $oRetorno->message = urlencode($e->getMessage());
            echo $oJson->encode($oRetorno);
            exit;
        }


        break;
}

$oRetorno->erro = $oRetorno->status == 2;

echo JSON::create()->stringify($oRetorno);

/**
 * @param null $iTipo tipos de débitos
 * @return stdClass[]
 * @throws BusinessException
 * @throws DBException
 */
function debitosExercicio($iTipo = null) {

    $oDaoArretipo   = new cl_arretipo();

    if (is_null($iTipo)) {
        $iTipo = 7;
    }

    $sWhereArretipo = "k03_tipo = $iTipo and k00_instit = " . db_getsession("DB_instit");
    $sSqlArretipo   = $oDaoArretipo->sql_query_file(null, "k00_tipo, k00_descr", "k00_descr", $sWhereArretipo);
    $rsArretipo     = db_query($sSqlArretipo);

    if(!$rsArretipo) {
        throw new DBException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . "erro_buscar_tipos_debito_destino"));
    }

    if(pg_num_rows($rsArretipo) == 0) {
        throw new BusinessException(_M(MENSAGENS_DVR3_IMPORTACAOIPTU_RPC . "nenhum_tipo_debito_destino_encontrado"));
    }

    $aOpcoesTipoDestino = db_utils::getCollectionByRecord($rsArretipo, false, false, true);

    return $aOpcoesTipoDestino;
}

function verificarParcelamento($numpre)
{
    $oDaoTermo = new \cl_termo();
    $sWhere    = " v07_numpre = {$numpre} ";
    $sSqlTermo = $oDaoTermo->sql_query_file(
        null,
        "distinct v07_parcel as codigo_parcelamento",
        null,
        $sWhere );
    $rsTermo   = db_query($sSqlTermo);

    if (!$rsTermo) {
        throw new \DBException("Erro ao consultar dados do parcelamento.");
    }

    if ( pg_num_rows($rsTermo) > 0 ) {

        $oTermo = \db_utils::fieldsMemory($rsTermo, 0);
        return $oTermo->codigo_parcelamento;
    }

    return false;
}

function buscarOrigemParcelamento($parcelamento)
{
    $oDaoTermo  = new \cl_termo();
    $sSqlOrigem = $oDaoTermo->sql_query_origem_parcelamento($parcelamento);

    $sCampos = implode("," , array(
        "x.k00_numpre",
        "x.k00_numpre as numpre",
        "x.k00_numpar",
        "arretipo.k00_descr",
        "x.k00_receit",
        "tabrec.k02_descr",
        "x.k00_valor"
    ));

    $sSql  = "select $sCampos           ";
    $sSql .= "  from ($sSqlOrigem) as x ";
    $sSql .= "      inner join arretipo on arretipo.k00_tipo = x.k00_tipo ";
    $sSql .= "      inner join tabrec on tabrec.k02_codigo = x.k00_receit ";
    $sSql .= " where k00_valor > 0      ";
    $sSql .= " order by k00_numpre, k00_numpar ";

    $rsOrigem = db_query($sSql);

    if (!$rsOrigem) {
        throw new \DBException("Erro ao consultar os dados de origem do parcelamento.");
    }

    if (pg_num_rows($rsOrigem) == 0) {
        throw new \Exception("Não há débitos de origem para este parcelamento.");
    }

    $retorno = \db_utils::getCollectionByRecord($rsOrigem);

    $aNumpres = array();
    $retornoOrigem = array();

    foreach ($retorno as $debito) {
        $aNumpres[$debito->k00_numpre] = $debito->k00_numpre;
    }

    foreach ($aNumpres as $numpre) {
        $parcelamento = verificarParcelamento($numpre);

        if (!empty($parcelamento)) {
            $retornoOrigem = array_merge($retornoOrigem, buscarOrigemParcelamento($parcelamento));
        }
    }

    if (!empty($retornoOrigem)) {
        $retorno = $retornoOrigem;
    }

    foreach ($retorno as $indice => $dados){
        $retorno[$indice] = array_values( (array) $dados);
    }

    return $retorno;
}
