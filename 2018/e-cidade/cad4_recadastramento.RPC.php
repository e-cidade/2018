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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Processamento;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Arquivo\Civitas\Civitas;

$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

$iAnoAtual = db_getsession("DB_anousu");

$iDepartamento = db_getsession("DB_coddepto");

const ARQUIVO_LOTES = 1;
const ARQUIVO_EDIFICACOES = 2;
const ARQUIVO_TESTADAS = 3;

try {

    db_inicio_transacao();
    $lErroTransacao = false;

    switch ($oParam->exec) {

        case "importar":

            if (empty($oParam->aArquivos)) {
                throw new ParameterException("Nenhum arquivo informado.");
            }

            if (empty($oParam->sDataArquivo)) {
                throw new ParameterException("Data do arquivo não informada.");
            }

            $oDataArquivo = new DBDate($oParam->sDataArquivo);
            $sDataNomeSchema = $oDataArquivo->getDia() . $oDataArquivo->getMes() . $oDataArquivo->getAno();
            $sNomeSchema = "importacao_" . $sDataNomeSchema;

            $aDescricaoArquivoImportado = array();
            $oCivitas = new Civitas($sNomeSchema);
            $oFiles = db_utils::postMemory($_FILES);

            foreach ($oParam->aArquivos as $oArquivo) {

                $sData = $oArquivo->sData;
                switch ($oArquivo->iTipoArquivo) {
                    case 1:

                        $sDestino = "tmp/" . $oFiles->arquivoLotes["name"];
                        move_uploaded_file($oFiles->arquivoLotes["tmp_name"], $sDestino);
                        $oCivitas->setArquivoLote($sDestino);
                        $aDescricaoArquivoImportado[] = "ARQUIVO DE LOTES";
                        break;

                    case 2:

                        $sDestino = "tmp/" . $oFiles->arquivoEdificacoes["name"];
                        move_uploaded_file($oFiles->arquivoEdificacoes["tmp_name"], $sDestino);
                        $oCivitas->setArquivoConstrucao($sDestino);
                        $aDescricaoArquivoImportado[] = "ARQUIVO DE EDIFICAÇÕES";
                        break;
                }
            }

            $oProcessamento = new Processamento($sNomeSchema);
            $oProcessamento->setArquivosImportados($aDescricaoArquivoImportado);
            $oProcessamento->setDataArquivo($oDataArquivo);
            $oProcessamento->processar();
            $oCivitas->processar();
            $oProcessamento->calcularIptu($oCivitas->getMatriculasImportadas(), $iAnoAtual);
            $oProcessamento->incluirMatriculasImportadas();
            $oRetorno->sMessage = "Arquivos importados com sucesso!";
            break;

        case 'buscarFiltros':

            $oDao = new cl_atualizacaoiptuschema();
            $rs = db_query($oDao->sql_query_file(null, "*", "j142_dataarquivo"));

            if (!$rs) {
                throw new Exception('Não foi possível buscar schemas.');
            }

            $aItens = array_map(function ($oItem) {
                $oDate = new DateTime($oItem->j142_dataarquivo);
                $oItem->sDescricao = "Importação - {$oDate->format('d/m/Y')}";
                return $oItem;
            }, db_utils::getCollectionByRecord($rs));

            $oRetorno->aSchemas = $aItens;
            break;

        case 'buscarSetores':

            if (empty($oParam->iSchema)) {
                throw new ParameterException('Importação não informada.');
            }

            $oDaoSchemaMatricula = new cl_atualizacaoiptuschemamatricula();
            $sCampos = "j30_codi, j30_descr";
            $sWhere = "j144_atualizacaoiptuschema = {$oParam->iSchema}";
            $sGroup = "j30_codi, j30_descr";
            $sOrder = "j30_descr";
            $sSqlSchemaMatricula = $oDaoSchemaMatricula->buscaSetoresQuadras($sCampos, $sWhere, $sGroup, $sOrder,
                $oParam->sSchema);
            $rsSchemaMatricula = db_query($sSqlSchemaMatricula);

            if (!$rsSchemaMatricula) {
                throw new DBException("Erro ao buscar os lotes das matrículas da importação.");
            }

            $oRetorno->aSetores = db_utils::getCollectionByRecord($rsSchemaMatricula);
            break;

        case 'buscarQuadras':

            if (empty($oParam->iSchema)) {
                throw new ParameterException('Importação não informada.');
            }

            if (empty($oParam->sSetor)) {
                throw new ParameterException('Setor não informado.');
            }

            $oDaoSchemaMatricula = new cl_atualizacaoiptuschemamatricula();
            $sCampos = "j34_quadra";
            $sWhere = "j144_atualizacaoiptuschema = {$oParam->iSchema} AND j34_setor = '{$oParam->sSetor}'";
            $sGroup = "j34_quadra";
            $sOrder = "j34_quadra";
            $sSqlSchemaMatricula = $oDaoSchemaMatricula->buscaSetoresQuadras($sCampos, $sWhere, $sGroup, $sOrder,
                $oParam->sSchema);
            $rsSchemaMatricula = db_query($sSqlSchemaMatricula);

            if (!$rsSchemaMatricula) {
                throw new DBException("Erro ao buscar os lotes das matrículas da importação.");
            }

            $oRetorno->aQuadras = db_utils::getCollectionByRecord($rsSchemaMatricula);
            break;

            break;

        case 'buscarMatriculas':

            db_fim_transacao(true);
            if (empty($oParam->sSchema)) {
                throw new Exception('Informe o schema.');
            }

            if (empty($oParam->iSetor)) {
                throw new Exception('Informe o setor.');
            }
            /**
             * MATRICULA_INCLUIDA   = 0;
             * MATRICULA_NOVA       = 1;
             * MATRICULA_ATUALIZADA = 2;
             * MATRICULA_REJEITADA  = 3;
             */
            $sBuscaValorEcidade = " coalesce((select sum(j21_valor) ";
            $sBuscaValorEcidade .= "    from cadastro.iptucalv ";
            $sBuscaValorEcidade .= "   where j21_anousu = {$iAnoAtual} and j21_matric = j144_matricula ), 0) as valor";

            $sBuscaValorCivita = "  coalesce((select sum(j21_valor) ";
            $sBuscaValorCivita .= "     from {$oParam->sSchema}.iptucalv ";
            $sBuscaValorCivita .= "    where j21_anousu = {$iAnoAtual} and j21_matric = j144_matricula ), 0) as valor_civita";

            $sSql = " select j144_matricula, z01_nome, j144_situacao, {$sBuscaValorEcidade}, {$sBuscaValorCivita}";
            $sSql .= "   from cadastro.atualizacaoiptuschema ";
            $sSql .= "   join cadastro.atualizacaoiptuschemamatricula on j144_atualizacaoiptuschema = j142_sequencial ";
            $sSql .= "                                               and j144_situacao in (0,1) ";
            $sSql .= "   join {$oParam->sSchema}.iptubase on j01_matric = j144_matricula ";
            $sSql .= "   join {$oParam->sSchema}.lote on j34_idbql = j01_idbql  ";
            $sSql .= "   left join {$oParam->sSchema}.cgm      on z01_numcgm = j01_numcgm ";
            $sSql .= "  where j142_schema = '{$oParam->sSchema}'";
            $sSql .= "    and {$oParam->sSchema}.lote.j34_setor = '{$oParam->iSetor}'";

            if (!empty($oParam->sQuadra)) {
                $sSql .= " and {$oParam->sSchema}.lote.j34_quadra = '{$oParam->sQuadra}'";
            }

            $sSql .= "  order by 2 ";
            $rs = db_query($sSql);

            if (!$rs) {
                throw new Exception('Erro ao buscar matrículas.');
            }
            $oRetorno->aMatriculas = db_utils::makeCollectionFromRecord($rs,
                function ($oDados) use ($oParam, $iAnoAtual) {

                    //*

                    db_inicio_transacao();
                    //Busca dados atualizados da matricula
                    $sSqlCalculo = "select fc_calculoiptu({$oDados->j144_matricula}::integer,{$iAnoAtual}::integer,true::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0','0'])";
                    $rsCalculo = db_query($sSqlCalculo);

                    /**
                     * forcar rollback
                     */

                    if (!$rsCalculo) {
                       db_fim_transacao(true);
                    }

                    $sCamposIptuCalv = " coalesce(sum(j21_valor),0) as j21_valor";
                    $sWhere = " j21_anousu = {$iAnoAtual} and j21_matric = {$oDados->j144_matricula} ";
                    db_fim_transacao(true);
                    $oDaoIptuCalv = new cl_iptucalv();
                    $sSqlIptuCalv = $oDaoIptuCalv->sql_query_file(null, $sCamposIptuCalv, null, $sWhere);

                    $rsIptuCalvAtualizado = db_query($sSqlIptuCalv);

                    if (!$rsIptuCalvAtualizado || pg_num_rows($rsIptuCalvAtualizado) == 0) {
                        throw new DBException("Erro ao buscar valores atualizados do IPTU da matrícula {$oDados->j144_matricula}.");
                    }

                    $oDados->valor = db_utils::fieldsMemory($rsIptuCalvAtualizado, 0)->j21_valor;

                    if ($oParam->iFiltro == 1 && $oDados->valor_civita <= $oDados->valor) {
                        return;
                    }

                    if ($oParam->iFiltro == 2 && $oDados->valor_civita >= $oDados->valor) {
                        return;
                    }

                    $oMatricula = new stdClass();
                    $oMatricula->iMatricula = $oDados->j144_matricula;
                    $oMatricula->sRazao = $oDados->z01_nome;
                    $oMatricula->nValorAtual = $oDados->valor;
                    $oMatricula->nValorNovo = $oDados->valor_civita;
                    $oMatricula->iSituacao = $oDados->j144_situacao;

                    return $oMatricula;
                });

            if (count($oRetorno->aMatriculas) == 0) {
                throw new Exception("Nenhuma matrícula encontrada para os filtros selecionados.");
            }
            $lErroTransacao = true;
            break;

        case 'rejeitar':

            if (!(is_array($oParam->aMatriculas)) || empty($oParam->aMatriculas)) {
                throw new \ParameterException('Nenhuma matrícula informada.');
            }
            $oProcessamento = new Processamento($oParam->sNomeImportacao);
            $oProcessamento->setCodigoSchema($oParam->iCodigoImportacao);

            foreach ($oParam->aMatriculas as $iCodigoMatricula) {
                $oProcessamento->rejeitarMatricula($iCodigoMatricula, $iAnoAtual);
            }

            $oRetorno->sMessage = "Matrículas rejeitadas.";
            break;

        case 'atualizar':

            if (!(is_array($oParam->aMatriculas)) || empty($oParam->aMatriculas)) {
                throw new \ParameterException('Nenhuma matrícula informada.');
            }


            $oProcessamento = new Processamento($oParam->sNomeImportacao);
            $oProcessamento->setCodigoSchema($oParam->iCodigoImportacao);

            foreach ($oParam->aMatriculas as $iCodigoMatricula) {
                $iCodigoMatricula = $oProcessamento->atualizarMatricula($iCodigoMatricula);
            }
            $sMensagemPadrao = "Matrículas atualizadas.";
            if (count($oParam->aMatriculas) == 1 && !empty($iCodigoMatricula)) {
                $sMensagemPadrao = "Matricula atualizada com sucesso. Para essa importação foi criado a matrícula {$iCodigoMatricula}.";
            }
            $oRetorno->sMessage = $sMensagemPadrao;

            break;
    }
    db_fim_transacao($lErroTransacao);
} catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);