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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));
require_once(modification("model/contabilidade/planoconta/interface/ISistemaConta.interface.php"));

require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
require_once(modification("model/retencaoNota.model.php"));
require_once(modification("model/ordemCompra.model.php"));

require_once(modification("model/contabilidade/planoconta/SistemaContaCompensado.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroBanco.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroCaixa.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroExtraOrcamentaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiro.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaPatrimonial.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaOrcamentario.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaNaoAplicado.model.php"));

require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));

require_once(modification(Modification::getFile("classes/empenho.php")));
require_once(modification("model/CgmFactory.model.php"));

require_once(modification("model/Dotacao.model.php"));

$objEmpenho       = new empenho();
$post             = db_utils::postmemory($_POST);
$json             = new services_json();
$objJson          = $json->decode(str_replace("\\","",$_POST["json"]));
$method           = $objJson->method;
$oAgendaPagamento = new agendaPagamento();
$item             = 0; //se deve trazer as notas, ou os itens do empenho.
$aCompetencias    = array();
$objEmpenho->setEmpenho($objJson->iEmpenho);
$objEmpenho->setEncode(true);

if (isset($objJson->aCompetencias)) {

    foreach ($objJson->aCompetencias as $aCompetencia) {
        $aCompetencias[$aCompetencia->iCodigoNota] = $aCompetencia;
    }
}

$dtDataSessao = db_getsession("DB_datausu");

switch ($objJson->method) {

    case "getEmpenhos":

        $lValidaNotasEmpenho = false;

        $lEmpenhoRestoAPagar = false;

        $oDaoPatriInst   = new cl_cfpatriinstituicao();
        $sWherePatriInst = " t59_instituicao = " . db_getsession('DB_instit');
        $sSqlPatriInst   = $oDaoPatriInst->sql_query_file(null, "t59_dataimplanatacaodepreciacao", null, $sWherePatriInst);
        $rsPatriInst     = $oDaoPatriInst->sql_record($sSqlPatriInst);

        if ($oDaoPatriInst->numrows > 0) {

            $dtImplantacao = db_utils::fieldsMemory($rsPatriInst, 0)->t59_dataimplanatacaodepreciacao;
            if (!empty($dtImplantacao)) {
                $lValidaNotasEmpenho = true;
            }
        }

        $oDaoEmpNota      = new cl_empnota();
        $sWhereBuscaNotas = " e69_numemp = {$objJson->iEmpenho} ";
        $sSqlBuscaNotas   = $oDaoEmpNota->sql_query_elemento_patrimonio(null, "empnota.* ", null, $sWhereBuscaNotas);
        $rsBuscaNotas     = $oDaoEmpNota->sql_record($sSqlBuscaNotas);
        $aBuscaNotas      = db_utils::getCollectionByRecord($rsBuscaNotas);
        $aNotasEmpenho    = array();
        $oGrupoElemento   = new stdClass();

        $oDataAtual   = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
        $oInstituicao = new Instituicao(db_getsession("DB_instit"));
        $lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);

        if (count($aBuscaNotas) > 0 && $lValidaNotasEmpenho) {

            foreach ($aBuscaNotas as $oNota) {

                $oDaoEmpNotaItemBensPendente       = new cl_empnotaitembenspendente();
                $sWhereBuscaItensForaDoPatrimonio  = "     e69_codnota = {$oNota->e69_codnota} ";
                $sWhereBuscaItensForaDoPatrimonio .= " and not exists (select 1 from bensempnotaitem where e136_empnotaitem = e137_empnotaitem) ";
                $sSqlBuscaItensForaDoPatrimonio    = $oDaoEmpNotaItemBensPendente->sql_query_nota("*", $sWhereBuscaItensForaDoPatrimonio);
                $rsBuscaItensForaDoPatrimonio      = $oDaoEmpNotaItemBensPendente->sql_record($sSqlBuscaItensForaDoPatrimonio);
                if ($oDaoEmpNotaItemBensPendente->numrows > 0) {
                    $aNotasEmpenho[] = $oNota->e69_codnota;
                }
            }
        }

        $oDaoEmpResto     = new cl_empresto();
        $sSqlEmpresto = $oDaoEmpResto->sql_query("", "", "*", "", "e91_numemp = {$objJson->iEmpenho} AND e91_anousu = {$oDataAtual->getAno()}");
        $rsEmpresto   = db_query($sSqlEmpresto);

        if (!$rsEmpresto) {
            throw new Exception("Não foi possível buscar o status do empenho {$objJson->iEmpenho}.");
        }

        if (pg_num_rows($rsEmpresto) > 0) {
            $lEmpenhoRestoAPagar = true;
        }

        $objEmpenho->operacao = $objJson->operacao;
        if (isset($objJson->itens)) {
            $item = 1;
        }
        $objEmpenho->setEmpenho($objJson->iEmpenho);


        $oEmpenhoFinanceiro  = new EmpenhoFinanceiro($objJson->iEmpenho);
        $lMostrarCompetencia = false;
        $iCodigoContrato     = $oEmpenhoFinanceiro->getCodigoContrato();

        if (!empty($iCodigoContrato)) {

            $oContrato = AcordoRepository::getByCodigo($iCodigoContrato);
            $oRegimeCompetenciaRepository = new RegimeCompetencia();
            $oRegime                      = $oRegimeCompetenciaRepository->getByAcordo($oContrato);
            $lMostrarCompetencia          = !empty($oRegime) && !$oRegime->isDespesaAntecipada();
        }

        if (count($aNotasEmpenho) > 0 && $lValidaNotasEmpenho) {

            $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item, $aNotasEmpenho));
            $oGrupoElemento->iGrupo   = "";
            $oGrupoElemento->sGrupo   = "";
            $oEmpenho->oGrupoElemento = $oGrupoElemento;
            $oEmpenho->lPossuiIntegracaoPatrimonial = $lPossuiIntegracaoPatrimonial;
            $oEmpenho->contratoPossuiRegimeCompetencia = $lMostrarCompetencia;
            $oEmpenho->lEmpenhoRestoAPagar = $lEmpenhoRestoAPagar;
            echo $json->encode($oEmpenho);

        } else {

            $oEmpenho             = json_decode($objEmpenho->empenho2Json('',$item));
            $oGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($oEmpenho->e64_codele, db_getsession("DB_anousu"));

            $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenho->e60_numemp);
            if ($oGrupoContaOrcamento && !$oEmpenhoFinanceiro->isEmpenhoPassivo()) {

                $iGrupo     = $oGrupoContaOrcamento->getCodigo();
                $sDescricao = $oGrupoContaOrcamento->getDescricao();

                /**
                 * Caso o empennho seja dos grupos abaixo, nao devemos permitir a liquidacao
                 * do mesmo atraves da rotina de liquidacao sem ordem de compra
                 */
                if ($iGrupo != "") {

                    if (in_array($iGrupo, array(7, 8, 9))) {

                        $sDataImplantacaoDepreciacao = BemDepreciacao::retornaDataImplantacaoDepreciacao(db_getsession('DB_instit'));
                        $oDataImplantacaoDepreciacao = $sDataImplantacaoDepreciacao ? new DBDate($sDataImplantacaoDepreciacao) : null;
                        $oDataEmissaoEmpenho         = new DBDate($oEmpenhoFinanceiro->getDataEmissao());

                        /**
                         * Se a Depreciação estiver implantada e a data de emissão do empenho é inferior a data de implantação da Depreciação
                         * devemos permitir liquidar sem ordem de compra, pois não haverá Nota Pendente.
                         */
                        if ($oDataImplantacaoDepreciacao && $oDataEmissaoEmpenho->getTimestamp() < $oDataImplantacaoDepreciacao->getTimestamp()) {
                            $oEmpenho->lAnteriorImplantacaoDepreciacao = true;
                        }

                        $oGrupoElemento->iGrupo   = $iGrupo;
                        $oGrupoElemento->sGrupo   = urlencode($sDescricao);
                        $oEmpenho->oGrupoElemento = $oGrupoElemento;
                        $oEmpenho->lPossuiIntegracaoPatrimonial = $lPossuiIntegracaoPatrimonial;
                        $oEmpenho->contratoPossuiRegimeCompetencia = $lMostrarCompetencia;
                        $oEmpenho->lEmpenhoRestoAPagar = $lEmpenhoRestoAPagar;
                        echo $json->encode($oEmpenho);


                    } else {

                        //echo $objEmpenho->empenho2Json('',$item);
                        $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item));
                        $oGrupoElemento->iGrupo   = "";
                        $oGrupoElemento->sGrupo   = "";
                        $oEmpenho->oGrupoElemento = $oGrupoElemento;
                        $oEmpenho->lPossuiIntegracaoPatrimonial = $lPossuiIntegracaoPatrimonial;
                        $oEmpenho->contratoPossuiRegimeCompetencia = $lMostrarCompetencia;
                        $oEmpenho->lEmpenhoRestoAPagar = $lEmpenhoRestoAPagar;
                        echo $json->encode($oEmpenho);
                    }
                }
            } else {

                // echo $objEmpenho->empenho2Json('',$item);
                $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item));
                $oGrupoElemento->iGrupo   = "";
                $oGrupoElemento->sGrupo   = "";
                $oEmpenho->oGrupoElemento = $oGrupoElemento;
                $oEmpenho->lPossuiIntegracaoPatrimonial = $lPossuiIntegracaoPatrimonial;
                $oEmpenho->contratoPossuiRegimeCompetencia = $lMostrarCompetencia;
                $oEmpenho->lEmpenhoRestoAPagar = $lEmpenhoRestoAPagar;
                echo $json->encode($oEmpenho);
            }

        }
        break;

    case "liquidarAjax":

        if ( isset($objJson->z01_credor) && !empty($objJson->z01_credor) ) {
            $objEmpenho->setCredor($objJson->z01_credor);
        }

        if (!empty($objJson->competencia)) {
            $objEmpenho->setCompetenciaLiquidacao($objJson->competencia);
        }

        try {
            /* [Extensão] ContratosPADRS: Verifica Tipo Instrumento Contratual */

            $dtDataSessao = date("Y-m-d", $dtDataSessao);
            $oDaoConlancamEmp    = new cl_conlancamemp();
            $sWhereEmpenho       = "     conlancamemp.c75_numemp = {$objJson->iEmpenho} ";
            $sWhereEmpenho      .= " and conhistdoc.c53_tipo     = 200 ";
            $sWhereEmpenho      .= " and conlancam.c70_data      > '{$dtDataSessao}' ";
            $sSqlBuscaDocumentos = $oDaoConlancamEmp->sql_query_documentos(null, "conhistdoc.*", 1, $sWhereEmpenho);
            $rsBuscaDocumentos   = $oDaoConlancamEmp->sql_record($sSqlBuscaDocumentos);

            if ( $oDaoConlancamEmp->numrows > 0) {
                throw new Exception("Não é possível realizar o lançamento contábil com data anterior a data dos lançamentos de controle de liquidação.");
            }

            $sHistorico      = db_stdClass::normalizeStringJsonEscapeString($objJson->historico);//addslashes(stripslashes(utf8_decode()))
            $oRetorno        = $objEmpenho->liquidarAjax($objJson->iEmpenho,$objJson->notas, $sHistorico);
            $oDadosRetorno   = $json->decode(str_replace("\\","", $oRetorno ));
            if ($oRetorno !== false) {

                if ($oDadosRetorno->erro == 1) {

                    //caso procedimento com sucesso  vincula o processo administrativo
                    $sProcessoAdministrativo = addslashes(db_stdClass::normalizeStringJson($objJson->e03_numeroprocesso));

                    if (!empty($sProcessoAdministrativo)) {

                        $aOrdensGeradas = explode(",", $oDadosRetorno->sOrdensGeradas);

                        foreach ($aOrdensGeradas as $iIndOrdensGeradas => $iOrdem) {

                            $oDaoPagordemProcesso                     = new cl_pagordemprocesso();
                            $oDaoPagordemProcesso->e03_numeroprocesso = $sProcessoAdministrativo;
                            $oDaoPagordemProcesso->e03_pagordem       = $iOrdem;
                            $oDaoPagordemProcesso->incluir(null);
                            if ($oDaoPagordemProcesso->erro_status == 0) {
                                throw new Exception($oDaoPagordemProcesso->erro_msg);
                            }
                        }

                    }

                    echo $oRetorno;
                }

                /**[Extensao OrdenadorDespesa] inclusao_ordenador_1*/
            }


            if ($objEmpenho->lSqlErro && !empty($objEmpenho->sMsgErro)) {
                throw new Exception($objEmpenho->sMsgErro);
            }

        } catch (Exception $eErro) {

            $oRetorno = $json->encode(array("sMensagem" =>urlencode($eErro->getMessage()), "lErro" => true));
            echo $oRetorno;
        }

        break;

    case "geraOC":

        $oDadosNota = new stdClass;
        try {

            if (empty($objJson->iEmpenho)) {
                throw new ParameterException("O empenho não foi informado.");
            }

            if (isset($objJson->e69_localrecebimento)) {
                $objJson->e69_localrecebimento = db_stdClass::normalizeStringJsonEscapeString($objJson->e69_localrecebimento);
            }

            $oEmpenho = new EmpenhoFinanceiro($objJson->iEmpenho);

            if (empty($objJson->e69_nota)) {
                $objJson->e69_nota = "S/N";
            }

            $oDataNota        = empty($objJson->e69_dtnota)       ? null : new DBDate($objJson->e69_dtnota);
            $oDataRecebimento = empty($objJson->e69_dtrecebe)     ? null : new DBDate($objJson->e69_dtrecebe);
            $oDataVencimento  = empty($objJson->e69_dtvencimento) ? null : new DBDate($objJson->e69_dtvencimento);
            $oListaClassificacaoCredor = $oEmpenho->getListaClassificacaoCredor();
            if (!empty($oListaClassificacaoCredor)) {

                $oListaClassificacaoCredor->validarParametros($objJson->e69_dtnota,
                  $objJson->e69_dtrecebe,
                  $objJson->e69_dtvencimento,
                  $objJson->e69_localrecebimento);
                $oListaClassificacaoCredor->validarDatas($oDataNota, $oDataRecebimento, $oDataVencimento);
            }

            $oDadosNota->e69_dtrecebe         = $oDataRecebimento->getDate();
            $oDadosNota->e69_dtvencimento     = $oDataVencimento ? $oDataVencimento->getDate() : null;
            $oDadosNota->e69_localrecebimento = !empty($objJson->e69_localrecebimento) ? $objJson->e69_localrecebimento : null;

            $z01_credor =  $objJson->z01_credor;
            $sHistorico = db_stdClass::normalizeStringJsonEscapeString($objJson->historico);
            $objEmpenho->setEmpenho($objJson->iEmpenho);
            $objEmpenho->setCredor($z01_credor);
            $objEmpenho->setDadosNota($oDadosNota);

            /* [Extensão] ContratosPADRS: Verifica Tipo Instrumento Contratual */

            if (!empty($objJson->competencia)) {
                $objEmpenho->setCompetenciaLiquidacao($objJson->competencia);
            }

            /**
             * Pode ser que o método gerarOrdemCompra retorne false ou um JSON
             */
            $oRetorno = $objEmpenho->gerarOrdemCompra($objJson->e69_nota,
              $objJson->valorTotal,
              $objJson->notas,
              true,
              $objJson->e69_dtnota,
              $sHistorico,
              true,
              $objJson->oInfoNota);

            if ($oRetorno !== false) {


                //caso procedimento com sucesso  vincula o processo administrativo
                $sProcessoAdministrativo = addslashes(stripslashes(utf8_decode($objJson->e03_numeroprocesso)));
                $oDadosRetorno           = $json->decode(str_replace("\\","", $oRetorno ));

                if ($oDadosRetorno->erro != 2) {

                    if (!empty($sProcessoAdministrativo)) {

                        $oDaoPagordemProcesso    = new cl_pagordemprocesso();
                        $oDaoPagordemProcesso->e03_numeroprocesso = $sProcessoAdministrativo;
                        $oDaoPagordemProcesso->e03_pagordem       = $oDadosRetorno->e50_codord;
                        $oDaoPagordemProcesso->incluir(null);
                        if ($oDaoPagordemProcesso->erro_status == 0) {
                            throw new Exception($oDaoPagordemProcesso->erro_msg);
                        }

                    }
                    /**[Extensao OrdenadorDespesa] inclusao_ordenador_2*/

                    /**[Extensao ContratosPADRS] nota liquidacao */
                }

                echo $oRetorno;
            } else {

                $retorno = array("erro" => 2, "mensagem" => urlencode($objEmpenho->sMsgErro), "e50_codord" => null);
                echo $json->encode($retorno);
            }
        } catch (Exception $oErro) {

            $retorno = array(
              'erro'       => 2,
              'mensagem'   => urlencode($oErro->getMessage()),
              'e50_codord' => null
            );
            echo $json->encode($retorno);
            break;
        }
        break;

    case "anularEmpenho":

        $objEmpenho->setRecriarSaldo($objJson->lRecriarReserva);
        $objEmpenho->anularEmpenho($objJson->itensAnulados,
          $objJson->nValor,
          $objJson->sMotivo,
          $objJson->aSolicitacoes,
          $objJson->iTipoAnulacao);
        if ($objEmpenho->lSqlErro) {

            $nMensagem = urlencode($objEmpenho->sErroMsg);
            $iStatus   = 2;
        } else {

            $nMensagem = '';
            $iStatus   = 1;
        }
        echo $json->encode(array("mensagem" => $nMensagem, "status" => $iStatus));
        break;

    case "getDadosRP":

        if ($objEmpenho->getDadosRP($objJson->iTipoRP)){
            echo $json->encode($objEmpenho->dadosEmpenho);
        }else{
            echo $json->encode(array("status" => 2 ,"sMensagem" => urlencode($objEmpenho->sErroMsg)));
        }
        break;

    case "estornarRP":

        try {

            db_inicio_transacao();
            $objEmpenho->estornarRP($objJson->iTipo,
              $objJson->aNotas,
              $objJson->sValorEstornar,
              db_stdClass::normalizeStringJsonEscapeString($objJson->sMotivo),
              $objJson->aItens,
              $objJson->tipoAnulacao);
            db_fim_transacao(false);
            $iStatus   = 1;
            $sMensagem = "Empenho estornado com sucesso";

        }
        catch (Exception $e){

            $iStatus   = 2;
            $sMensagem = urlencode($e->getMessage());
            db_fim_transacao(true);
        }
        echo $json->encode(array("sMensagem" => $sMensagem, "iStatus" => $iStatus));
        break;

    case "getDadosRP":

        if ($objEmpenho->getDados($objJson->iEmpenho)) {

            $rsNotas  = $objEmpenho->getNotas($objJson->iEmpenho);
            if ($rsNotas) {

                for ($iNotas = 0; $iNotas <  $objEmpenho->iNumRowsNotas; $iNotas++ ) {

                    $oNota                            = db_utils::fieldsMemory($rsNotas, $iNotas);
                    $oNota->temMovimentoConfigurado   = false;
                    $oNota->temRetencao               = false;
                    $oNota->VlrRetencao               = 0;
                    /**
                     * Pesquisamos se existem algum movimento para essa nota.
                     */
                    $sWhereIni  =  "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
                    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
                    $sJoin      = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
                    $sJoin     .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
                    $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin,false,false) ;
                    if (count($aMOvimentos) > 0) {

                        $oNota->temMovimentoConfigurado   = true;
                    }

                    //Verifica se a nota possui retenções lançadas
                    $oRetencao = new retencaoNota($oNota->e69_codnota);
                    if ( $oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {

                        $oNota->temRetencao   = true;
                        $oNota->VlrRetencao   = $oRetencao->getValorRetencao($oNota->e50_codord);
                    }
                    $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
                }
            }

            echo $json->encode($objEmpenho->dadosEmpenho);

        } else {
            echo $json->encode(array("status" => 2 ,"sMensagem" => urlencode($objEmpenho->sErroMsg)));
        }
        break;

    case "getDadosNotas" :

        if ($objEmpenho->getDados($objJson->iEmpenho)) {

            $rsNotas  = $objEmpenho->getNotas($objJson->iEmpenho);
            if ($rsNotas) {

                for ($iNotas = 0; $iNotas <  $objEmpenho->iNumRowsNotas; $iNotas++ ) {

                    $oNota                            = db_utils::fieldsMemory($rsNotas, $iNotas);
                    $oNota->temMovimentoConfigurado   = false;
                    $oNota->temRetencao               = false;
                    $oNota->VlrRetencao               = 0;

                    if (!isset($oNota->e50_codord) || empty($oNota->e50_codord)) {
                        continue;
                    }

                    /**
                     * Pesquisamos se existem algum movimento para essa nota.
                     */
                    $sWhereIni  =  "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
                    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
                    $sJoin      = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
                    $sJoin     .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
                    $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin,false,false) ;
                    if (count($aMOvimentos) > 0) {

                        $oNota->temMovimentoConfigurado   = true;
                    }

                    //Verifica se a nota possui retenções lançadas
                    $oRetencao = new retencaoNota($oNota->e69_codnota);
                    if ( $oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {
                        $oNota->temRetencao   = true;
                        $oNota->VlrRetencao   = $oRetencao->getValorRetencao($oNota->e50_codord);
                    }

                    $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
                }
            }
        }

        echo JSON::create()->stringify($objEmpenho->dadosEmpenho);
        break;

    case "getItensNota":

        /**
         * Busca os ITENS da nota
         */
        $oDadosRetorno                     = new stdClass();
        $oDadosRetorno->lPossuiOrdemCompra = false;
        $objEmpenho->setEncode(true);
        $aItens        = $objEmpenho->getItensNota($objJson->iCodNota);

        if ( !$aItens ) {

            $oDadosRetorno->status    = 1;
            $oDadosRetorno->sMensagem = "Não foi possível recuperar os itens da nota!";
        } else {

            $oDaoEmpNotaOrd   = new cl_empnotaord();
            $sWhereEmpNotaOrd = "m72_codnota = {$objJson->iCodNota} AND m51_tipo = 1";
            $sSqlEmpNotaOrd   = $oDaoEmpNotaOrd->sql_matordem(null, null, '1', null, $sWhereEmpNotaOrd);
            $rsEmpNotaOrd     = db_query($sSqlEmpNotaOrd);

            if($rsEmpNotaOrd && pg_num_rows($rsEmpNotaOrd) > 0) {

                /**
                 * Valida se o empenho possui lançado o doc 210, bloqueando lançamento de desconto
                 */
                $oDaoConLancamDoc   = new cl_conlancamdoc();
                $sWhereConLancamDoc = "c75_numemp = {$objJson->iEmpenho} AND c71_coddoc = 210";
                $sSqlConLancamDoc   = $oDaoConLancamDoc->sql_queryEmpenhoRP(null, '1', null, $sWhereConLancamDoc);
                $rsConLancamDoc     = db_query($sSqlConLancamDoc);

                if($rsConLancamDoc && pg_num_rows($rsConLancamDoc) > 0) {

                    $oDadosRetorno->lPossuiOrdemCompra  = true;
                    $oDadosRetorno->sMensagem           = "Este empenho possui Ordem de Compra. Não será possível realizar";
                    $oDadosRetorno->sMensagem          .= " o procedimento, devendo realizar o estorno da liquidação total.";
                    $oDadosRetorno->sMensagem           = DBString::urlencode_all($oDadosRetorno->sMensagem);
                }
            }

            $oDadosRetorno->status   = 2;
            $oDadosRetorno->iCodNota = $objJson->iCodNota;
            $oDadosRetorno->iEmpenho = $objJson->iEmpenho;
            $oDadosRetorno->aItens   = $aItens;
        }

        echo $json->encode($oDadosRetorno);
        break;

    default:

        if (!empty($objJson->competencia)) {
            $objEmpenho->setCompetenciaLiquidacao($objJson->competencia);
        }

        /* [Extensão] ContratosPADRS: Verifica Tipo Instrumento Contratual */

        echo $objEmpenho->$method($objJson->iEmpenho,$objJson->notas, $objJson->historico, true, $aCompetencias);
        break;
}
