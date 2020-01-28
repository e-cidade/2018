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

require_once(modification('classes/db_acordocomissao_classe.php'));
require_once(modification('classes/db_acordocomissaomembro_classe.php'));
require_once(modification("classes/db_acordotipo_classe.php"));
require_once(modification("classes/db_acordopenalidade_classe.php"));
require_once(modification("classes/db_acordogarantia_classe.php"));
require_once(modification("classes/db_acordoprogramacaofinanceira_classe.php"));
require_once(modification("classes/db_acordoposicaoperiodo_classe.php"));
require_once(modification("classes/db_acordoitemprevisao_classe.php"));
require_once(modification('model/AcordoComissao.model.php'));
require_once(modification('model/Acordo.model.php'));
require_once(modification('model/AcordoItem.model.php'));
require_once(modification('model/AcordoComissaoMembro.model.php'));
require_once(modification("model/AcordoPenalidade.model.php"));
require_once(modification("model/AcordoGarantia.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification('model/CgmBase.model.php'));
require_once(modification('model/CgmFisico.model.php'));
require_once(modification('model/CgmJuridico.model.php'));
require_once(modification("model/MaterialCompras.model.php"));
require_once(modification("model/AcordoPosicao.model.php"));
require_once(modification("model/AcordoDocumento.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/licitacao.model.php"));
require_once(modification("model/ProcessoCompras.model.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_app.utils.php'));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("std/DBTime.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("model/contrato/AcordoItemTipoCalculoFactory.model.php"));

db_app::import("configuracao.DBDepartamento");

$oJson = new services_json();

if (array_key_exists('json', $_POST)) {
    $oParam = $oJson->decode(str_replace("\\", '', $_POST['json']));
} else {
    $oParam = (object)filter_input_array(INPUT_POST);
    $get = (object)filter_input_array(INPUT_GET);

    if (isset($get->exec)) {
        $oParam->exec = $get->exec;
    }

    if ($_FILES) {
        $oParam->file = (object)array_pop($_FILES);
    }
}

$sCaminhoMensagens = 'patrimonial.contratos.con4_contratos.';
$oErro = new stdClass;

if (isset($oParam->descricao) && $oParam->descricao) {
    $oParam->descricao = str_replace("<contrabarra>", "\\", $oParam->descricao);
}

$oRetorno = new stdClass;
$oRetorno->status = 1;
$oRetorno->erro = false;
$oRetorno->message = 1;
$oRetorno->itens = array();

switch ($oParam->exec) {
    case 'getMembros':

        try {
            $oAcordo = new AcordoComissao($oParam->iAcordo);

            $oAcordoStd = new stdClass();
            $oAcordoStd->iCodigo = $oAcordo->getCodigo();
            $oAcordoStd->sDescricao = urlencode($oAcordo->getDescricao());
            $oAcordoStd->sObservacao = urlencode($oAcordo->getObservacao());
            $oAcordoStd->sDataInicial = $oAcordo->getDataInicial();
            $oAcordoStd->sDataFinal = $oAcordo->getDataInicial();
            $oAcordoStd->aMembros = array();

            foreach ($oAcordo->getMembros() as $oMembro) {
                $oStdMembro = new stdClass();

                $oStdMembro->iCodigo = $oMembro->getCodigo();
                $oStdMembro->iCodigoCgm = $oMembro->getCodigoCgm();
                $oStdMembro->sNome = urlencode($oMembro->getNome());
                $oStdMembro->iCodigoComissao = $oMembro->getCodigoComissao();
                $oStdMembro->iResponsabilidade = $oMembro->getResponsabilidade();
                $oStdMembro->sResponsabilidade = urlencode($oMembro->getDescricaoResponsabilidade());
                $oStdMembro->sDataInicio = null;
                $oStdMembro->sDataTermino = null;
                $oMembroStd->numeroAtoDesignacao = $oMembro->getNumeroAtoDesignacao();
                $oMembroStd->anoAtoDesignacao = $oMembro->getAnoAtoDesignacao();
                $oMembroStd->nomeArquivo = $oMembro->getNomeArquivo();
                $oMembroStd->arquivo = $oMembro->getArquivo();

                $oDataInicio = $oMembro->getDataInicio();
                if ($oDataInicio) {
                    $oStdMembro->sDataInicio = $oMembro->getDataInicio()->getDate(DBDate::DATA_PTBR);
                }

                $oDataTermino = $oMembro->getDataTermino();
                if ($oDataTermino) {
                    $oStdMembro->sDataTermino = $oMembro->getDataTermino()->getDate(DBDate::DATA_PTBR);
                }

                $oAcordoStd->aMembros[] = $oStdMembro;
            }

            $oRetorno->oAcordo = $oAcordoStd;
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'getAcordo':

        try {
            $oDaoAcordo = new cl_acordocomissao();
            $sSql = $oDaoAcordo->sql_query(null, "*", "", "ac08_sequencial={$oParam->ac08_sequencial}");
            $rsAcordo = $oDaoAcordo->sql_record($sSql);
            $oAcordo = db_utils::fieldsMemory($rsAcordo, 0, false, false, true);

            $oRetorno->oAcordo = $oAcordo;
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'carregaMembro':

        try {
            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);

            $oMembroStd = new stdClass();
            $oMembroStd->iCodigo = $oMembro->getCodigo();
            $oMembroStd->iCodigoCgm = $oMembro->getCodigoCgm();
            $oMembroStd->sNome = urlencode($oMembro->getNome());
            $oMembroStd->iCodigoComissao = $oMembro->getCodigoComissao();
            $oMembroStd->iResponsabilidade = $oMembro->getResponsabilidade();
            $oMembroStd->sDataInicio = null;
            $oMembroStd->sDataTermino = null;
            $oMembroStd->numeroAtoDesignacao = $oMembro->getNumeroAtoDesignacao();
            $oMembroStd->anoAtoDesignacao = $oMembro->getAnoAtoDesignacao();
            $oMembroStd->nomeArquivo = $oMembro->getNomeArquivo();
            $oMembroStd->arquivo = $oMembro->getArquivo();

            $oDataInicio = $oMembro->getDataInicio();
            if ($oDataInicio) {
                $oMembroStd->sDataInicio = $oDataInicio->getDate(DBDate::DATA_PTBR);
            }

            $oDataTermino = $oMembro->getDataTermino();
            if ($oDataTermino) {
                $oMembroStd->sDataTermino = $oDataTermino->getDate(DBDate::DATA_PTBR);
            }

            $oRetorno->sAcao = $oParam->sAcao;
            $oRetorno->oMembro = $oMembroStd;
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'alteraMembro':

        try {
            db_inicio_transacao();

            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);
            $oMembro->setCodigoCgm($oParam->iCodigoCgm);
            $oMembro->setResponsabilidade($oParam->iResponsabilidade);
            $oMembro->setNumeroAtoDesignacao($oParam->numeroAtoDesignacao);
            $oMembro->setAnoAtoDesignacao($oParam->anoAtoDesignacao);

            if (isset($oParam->file)) {
                $oMembro->setNomeArquivo($oParam->file);
                $oMembro->setArquivo($oParam->file);
            }

            if ($oParam->sDataInicio) {
                $oMembro->setDataInicio(new DBDate($oParam->sDataInicio));
            }

            if ($oParam->sDataTermino) {
                $oMembro->setDataTermino(new DBDate($oParam->sDataTermino));
            }

            $oMembro->save();
            db_fim_transacao(false);

            $oRetorno->message = urlencode('Membro alterado com sucesso.');
            $oRetorno->iCodigo = $oMembro->getCodigoComissao();
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'incluiMembro':

        try {
            db_inicio_transacao();

            $oAcordo = new AcordoComissao($oParam->iCodigoComissao);

            if (!$oAcordo->membroExists($oParam->iCodigoCgm)) {
                $oMembro = new AcordoComissaoMembro();
                $oMembro->setCodigoComissao($oParam->iCodigoComissao);
                $oMembro->setCodigoCgm($oParam->iCodigoCgm);
                $oMembro->setResponsabilidade($oParam->iResponsabilidade);
                $oMembro->setNumeroAtoDesignacao($oParam->numeroAtoDesignacao);
                $oMembro->setAnoAtoDesignacao($oParam->anoAtoDesignacao);

                if (isset($oParam->file)) {
                    $oMembro->setNomeArquivo($oParam->file);
                    $oMembro->setArquivo($oParam->file);
                }

                if ($oParam->sDataInicio) {
                    $oMembro->setDataInicio(new DBDate($oParam->sDataInicio));
                }

                if ($oParam->sDataTermino) {
                    $oMembro->setDataTermino(new DBDate($oParam->sDataTermino));
                }

                $oMembro->save();
                db_fim_transacao(false);

                $oRetorno->message = urlencode('Membro incluído com sucesso.');
                $oRetorno->iCodigo = $oMembro->getCodigoComissao();
            } else {
                $oRetorno->message = urlencode('Membro já está presente na comissão.');
                $oRetorno->iCodigo = $oParam->iCodigoComissao;
            }
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'exluiMembro':

        try {
            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);
            $oMembro->excluir();

            $oRetorno->message = urlencode('Membro excluído com sucesso.');
            $oRetorno->iCodigo = $oMembro->getCodigoComissao();
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }

        break;

    case "pesquisaTipoAcordo":

        $oRetorno->aTipoAcordo = array();
        $clacordotipo = new cl_acordotipo;
        $sCampos = 'acordotipo.ac04_sequencial as codigo, acordotipo.ac04_descricao as descricao';
        $sSqlTipoAcordo = $clacordotipo->sql_query(null, $sCampos);
        $rsSqlTipoAcordo = $clacordotipo->sql_record($sSqlTipoAcordo);
        $oRetorno->aTipoAcordo = db_utils::getCollectionByRecord($rsSqlTipoAcordo, false, false, true);
        break;

    case 'pesquisaAcordoPenalidade':

        try {
            $oAcordoPenalidade = new AcordoPenalidade($oParam->codigo);
            $oRetorno->aTiposContratos = array();
            $oRetorno->iCodigo = $oAcordoPenalidade->getCodigo();
            $oRetorno->sDescricao = urlencode($oAcordoPenalidade->getDescricao());
            $oRetorno->sObservacao = urlencode($oAcordoPenalidade->getObservacao());
            $oRetorno->sTextoPadrao = urlencode($oAcordoPenalidade->getTextoPadrao());
            $oRetorno->dtValidade = urlencode(db_formatar($oAcordoPenalidade->getDataLimite(), 'd'));
            $oRetorno->aTiposContratos = $oAcordoPenalidade->getTiposContratos();
        } catch (Exception $exception) {
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'salvarPenalidade':

        try {
            db_inicio_transacao();

            $dtValidade = implode('-', array_reverse(explode('/', $oParam->datalimite)));

            $oAcordoPenalidade = new AcordoPenalidade($oParam->codigo);
            $oAcordoPenalidade->setDescricao(addslashes(db_stdClass::normalizeStringJson($oParam->descricao)));
            $oAcordoPenalidade->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->observacao)));
            $oAcordoPenalidade->setTextoPadrao(addslashes(db_stdClass::normalizeStringJson($oParam->textopadrao)));
            $oAcordoPenalidade->setDataLimite($dtValidade);
            $oAcordoPenalidade->removeTipoContrato();

            foreach ($oParam->aTiposAcordos as $oTipoAcordo) {
                $oAcordoPenalidade->addTipoContrato($oTipoAcordo->iCodTipoAcordo);
            }

            $oAcordoPenalidade->save();

            db_fim_transacao(false);
        } catch (Exception $exception) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'excluirPenalidade':

        try {
            db_inicio_transacao();

            $oAcordoPenalidade = new AcordoPenalidade($oParam->codigo);
            $oAcordoPenalidade->excluir();

            db_fim_transacao(false);
        } catch (Exception $exception) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'pesquisaGarantia':

        try {
            $oAcordoGarantia = new AcordoGarantia($oParam->codigo);

            $oRetorno->oAcordoGarantia = new stdClass;
            $oRetorno->oAcordoGarantia->iCodigo = urlencode($oAcordoGarantia->getCodigo());
            $oRetorno->oAcordoGarantia->sDescricao = urlencode($oAcordoGarantia->getDescricao());
            $oRetorno->oAcordoGarantia->sObservacao = urlencode($oAcordoGarantia->getObservacao());
            $oRetorno->oAcordoGarantia->sTextoPadrao = urlencode($oAcordoGarantia->getTextoPadrao());
            $oRetorno->oAcordoGarantia->sDataLimite = implode('/',
                array_reverse(explode('-', $oAcordoGarantia->getDataLimite())));
            $oRetorno->oAcordoGarantia->aTiposContratos = $oAcordoGarantia->getTiposContratos();
        } catch (Exception $exception) {
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'salvarGarantia':

        try {
            db_inicio_transacao();

            $sLimite = implode("-", array_reverse(explode("/", $oParam->sDataLimite)));

            $oAcordoGarantia = new AcordoGarantia($oParam->iCodigo);
            $oAcordoGarantia->setDescricao(addslashes(db_stdClass::normalizeStringJson($oParam->sDescricao)));
            $oAcordoGarantia->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao)));
            $oAcordoGarantia->setTextoPadrao(addslashes(db_stdClass::normalizeStringJson($oParam->sTextoPadrao)));
            $oAcordoGarantia->setDataLimite($sLimite);
            $oAcordoGarantia->removeTipoContrato();

            foreach ($oParam->aTiposAcordos as $oTipoAcordo) {
                $oAcordoGarantia->addTipoContrato($oTipoAcordo->iCodTipoAcordo);
            }

            $oAcordoGarantia->save();

            db_fim_transacao(false);
        } catch (Exception $exception) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'excluiGarantia':

        try {
            db_inicio_transacao();

            $oAcordoGarantia = new AcordoGarantia($oParam->iCodigo);
            $oAcordoGarantia->excluir();

            db_fim_transacao(false);
        } catch (Exception $exception) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = urlencode(str_replace("\\n", "\n", $exception->getMessage()));
        }

        break;

    case 'getLicitacoesContratado':

        $aItens = licitacao::getLicitacoesByFornecedor($oParam->iContratado, true, db_getsession('DB_instit'));

        $aItensDevolver = array();
        foreach ($aItens as $oStdDadosLicitacao) {
            if ($oStdDadosLicitacao->l20_usaregistropreco == 't') {
                continue;
            }
            $aItensDevolver[] = $oStdDadosLicitacao;
        }

        /**
         * se o contrato esta preenchido, estamos em alteração
         * verificamos se para esse contrato ja foi incluido itens, de alguma outra licitação
         * se tiver, não mostramos outras licitações
         */
        if (isset($oParam->iContrato) && $oParam->iContrato) {
            $oDaoAcordoItem = new cl_acordoitem;
            $sSqlAcordoItem = $oDaoAcordoItem->sql_query(null, '*', null, "ac26_acordo = {$oParam->iContrato}");
            $rsAcordoItem = $oDaoAcordoItem->sql_record($sSqlAcordoItem);

            if ($oDaoAcordoItem->numrows > 0) {
                $aItensDevolver = '';
            }
        }

        $oRetorno->itens = $aItensDevolver;
        $oRetorno->itensSelecionados = array();

        if (isset($_SESSION['dadosSelecaoAcordo'])) {
            $oRetorno->itensSelecionados = $_SESSION['dadosSelecaoAcordo'];
        }
        break;


    case 'getProcessosContratado':

        $oItens = ProcessoCompras::getProcessosByFornecedor($oParam->iContratado, true);
        /**
         * se o contrato esta preenchido, estamos em alteração
         * verificamos se para esse contrato ja foi incluido itens, de alguma outra licitação
         * se tiver, não mostramos outras licitações
         */
        if (isset($oParam->iContrato) && $oParam->iContrato) {
            $oDaoAcordoItem = new cl_acordoitem;
            $sSqlAcordoItem = $oDaoAcordoItem->sql_query(null, '*', null, "ac26_acordo = {$oParam->iContrato}");
            $rsAcordoItem = $oDaoAcordoItem->sql_record($sSqlAcordoItem);
            if ($oDaoAcordoItem->numrows > 0) {
                $oItens = '';
            }
        }

        $oRetorno->itens = $oItens;
        $oRetorno->itensSelecionados = array();

        if (isset($_SESSION['dadosSelecaoAcordo'])) {
            $oRetorno->itensSelecionados = $_SESSION['dadosSelecaoAcordo'];
        }
        break;


    case 'setDadosSelecao':

        $_SESSION['dadosSelecaoAcordo'] = $oParam->itens;
        break;

    case 'getNumeroContrato':

        try {
            $oRetorno->numero = Acordo::getProximoNumeroContrato($oParam->iGrupo);
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'salvarContrato':

        try {
            db_inicio_transacao();

            $lAcordoValido = true;
            $sMessagemInvalido = '';
            /* Modification PADRS - valida base legal de contratacao do acordo */
            if ($oParam->contrato->iOrigem == 1 || $oParam->contrato->iOrigem == 2) {
                if (!isset($_SESSION['dadosSelecaoAcordo'])) {
                    $lAcordoValido = false;
                    $sMessagemInvalido = 'Acordo sem vinculo com licitação/Processo de compras';
                }
            } else {
                if (isset($_SESSION["dadosSelecaoAcordo"]) && trim(isset($_SESSION["dadosSelecaoAcordo"])) == "") {
                    $lAcordoValido = false;
                    $sMessagemInvalido = "Acordo sem vinculo com licitação/Processo de compras";
                }
            }
            if ($lAcordoValido) {
                $oParam->contrato->nValorContrato = str_replace(',', '.',
                    str_replace(".", "", $oParam->contrato->nValorContrato));

                $oContratado = CgmFactory::getInstanceByCgm($oParam->contrato->iContratado);
                $oContrato = new Acordo($oParam->contrato->iCodigo);
                if (empty($oParam->contrato->iCodigo)) {
                    $oContrato->setAno(db_getsession("DB_anousu"));
                }
                $oContrato->setDataAssinatura($oParam->contrato->dtAssinatura);
                $oContrato->setDataInicial($oParam->contrato->dtInicio);
                $oContrato->setDataFinal($oParam->contrato->dtTermino);
                $oContrato->setGrupo($oParam->contrato->iGrupo);
                if (empty($oParam->contrato->iCodigo)) {
                    $oContrato->setSituacao(1);
                }
                $oContrato->setInstit(db_getsession("DB_instit"));
                $oContrato->setLei($oParam->contrato->sLei);
                $oContrato->setNumero($oParam->contrato->iNumero);
                $oContrato->setOrigem($oParam->contrato->iOrigem);
                $oContrato->setObjeto(db_stdClass::normalizeStringJsonEscapeString($oParam->contrato->sObjeto));
                $oContrato->setResumoObjeto(db_stdClass::normalizeStringJsonEscapeString($oParam->contrato->sResumoObjeto));
                $oContrato->setDepartamento(db_getsession("DB_coddepto"));
                $oContrato->setQuantidadeRenovacao($oParam->contrato->iQtdRenovacao);
                $oContrato->setTipoRenovacao($oParam->contrato->iUnidRenovacao);
                $oContrato->setDepartamentoResponsavel($oParam->contrato->iDepartamentoResponsavel);
                $oContrato->setEmergencial($oParam->contrato->lEmergencial);
                $oContrato->setContratado($oContratado);
                if ($oParam->contrato->sProcesso === '') {
                    throw new BusinessException('O campo Número do Processo é de preenchimento obrigatório.');
                }
                $oContrato->setProcesso($oParam->contrato->sProcesso);
                $oContrato->setComissao(new AcordoComissao($oParam->contrato->iComissao));
                $oContrato->setPeriodoComercial($oParam->contrato->lPeriodoComercial);
                $oContrato->setCategoriaAcordo($oParam->contrato->iCategoriaAcordo);
                $oContrato->setTipoUnidadeTempoVigencia($oParam->contrato->iTipoUnidadeTempoVigencia);
                $oContrato->setQtdPeriodoVigencia($oParam->contrato->iQtdPeriodoVigencia);
                $oContrato->setClassificacao(new AcordoClassificacao($oParam->contrato->iClassificacao));
                $oContrato->setValorContrato($oParam->contrato->nValorContrato);
                $oContrato->setTipoInstrumento($oParam->contrato->iTipoInstrumento);
                $oContrato->setDependeOrdemInicio($oParam->contrato->lDependeOrdemInicio);
                $oContrato->save();

                /*
                 * verificamos se existe empenhos a serem vinculados na seção
                 */
                $oDaoEmpEmpenhoContrato = new cl_empempenhocontrato();
                $oDaoAcordoPosicao = new cl_acordoposicao();
                $oDaoAcordoItem = new cl_acordoitem();
                $oDaoAcordoEmpEmpitem = new cl_acordoempempitem();
                $oDaoEmpEmpitem = new cl_empempitem();
                $oDaoAcordoPosicaoPeriodo = new cl_acordoposicaoperiodo();
                $oDaoAcordoVigencia = new cl_acordovigencia();
                $oDaoAcordoItemPeriodo = new cl_acordoitemperiodo();
                $oDaoAcordo = new cl_acordo();
                $oDaAcordoItemPrevisao = new cl_acordoitemprevisao();
                $iContrato = $oContrato->getCodigoAcordo();

                $aSessaoEmpenhos = db_getsession("oEmpenhosSalvar", false);

                /*
                 * verificamos se a origem nao for empenhos
                 * devemos desfazer todos possiveis antigos vinculos
                 *  deletar acordoempempitem
                 *  deletar acordoitem
                 *  deletar acordoposicao
                 *  deletar empempenhocontrato
                 *  $oDaoEmpEmpenhoContrato = db_utils::getDao("empempenhocontrato");
                 *  $oDaoAcordoPosicao      = db_utils::getDao("acordoposicao");
                 *  $oDaoAcordoItem         = db_utils::getDao("acordoitem");
                 *  $oDaoAcordoEmpEmpitem   = db_utils::getDao("acordoempempitem");
                 *  $oDaoEmpEmpitem         = db_utils::getDao("empempitem");
                 *
                 *  buscamos possiveis vinculos existentes entre o contrato e empenhos
                 */
                if ($oParam->contrato->iOrigem != 6 && empty($oParam->contrato->iCodigo)) {
                    $sSqlEmpenhosVinculados = $oDaoEmpEmpenhoContrato->sql_query_file(null, "*", null,
                        "e100_acordo = {$iContrato}");
                    $rsEmpenhosVinculados = $oDaoEmpEmpenhoContrato->sql_record($sSqlEmpenhosVinculados);

                    if ($oDaoEmpEmpenhoContrato->numrows > 0) {
                        for ($iEmpEmpenhoContrato = 0; $iEmpEmpenhoContrato < $oDaoEmpEmpenhoContrato->numrows; $iEmpEmpenhoContrato++) {
                            $oValoresEmpEmpenhoContrato = db_utils::fieldsMemory($rsEmpenhosVinculados,
                                $iEmpEmpenhoContrato);

                            //trazemos os empempitem para deletar da acordoempempitem
                            $sSqlEmpEmpItem = $oDaoEmpEmpitem->sql_query_file(null, null, "e62_sequencial", null,
                                "e62_numemp = {$oDaoEmpEmpenhoContrato->e100_numemp}");
                            $rsEmpEmpItem = $oDaoEmpEmpitem->sql_record($sSqlEmpEmpItem);
                            if ($oDaoEmpEmpitem->numrows > 0) {
                                for ($iEmpEmpitem = 0; $iEmpEmpitem < $oDaoEmpEmpitem->numrows; $iEmpEmpitem++) {
                                    $oValorEmpEmpitem = db_utils::fieldsMemory($rsEmpEmpItem, $iEmpEmpitem);
                                    $oDaoAcordoEmpEmpitem->excluir(null,
                                        "ac44_empempitem = {$oValorEmpEmpitem->e62_sequencial}");
                                    if ($oDaoAcordoEmpEmpitem->erro_status == 0) {
                                        //throw new Exception(" [ 8 ] - ERRO - Desvinculando itens - " . $oDaoAcordoEmpEmpitem->erro_msg);
                                        $oErro->erro_msg = $oDaoAcordoEmpEmpitem->erro_msg;
                                        throw new Exception($sCaminhoMensagens . "acordo_empempitem_excluir", $oErro);
                                    }
                                }
                            }

                            /*
                             * trazemos os acordoposicao para deletar da acordoitem
                             * depois da acordoposicao
                             */
                            $sSqlAcordoPosicao = $oDaoAcordoPosicao->sql_query_file(null, "ac26_sequencial", null,
                                "ac26_acordo = {$iContrato}");
                            $rsAcordoPosicao = $oDaoAcordoPosicao->sql_record($sSqlAcordoPosicao);
                            if ($oDaoAcordoPosicao->numrows > 0) {
                                for ($iAcordoPosicao = 0; $iAcordoPosicao < $oDaoAcordoPosicao->numrows; $iAcordoPosicao++) {
                                    $oValorAcordoPosicao = db_utils::fieldsMemory($rsAcordoPosicao, $iAcordoPosicao);
                                    $oDaoAcordoItem->excluir(null,
                                        "ac20_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                                    if ($oDaoAcordoItem->erro_status == 0) {
                                        $oErro->erro_msg = $oDaoAcordoItem->erro_msg;
                                        throw new Exception(_M($sCaminhoMensagens . "acordo_item_excluir", $oErro));
                                    }

                                    $oDaoAcordoVigencia->excluir(null,
                                        "ac18_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                                    if ($oDaoAcordoVigencia->erro_status == 0) {
                                        $oErro->erro_msg = $oDaoAcordoVigencia->erro_msg;
                                        throw new Exception(_M($sCaminhoMensagens . "acordo_vigencia_excluir", $oErro));
                                    }

                                    $oDaoAcordoPosicaoPeriodo->excluir(null,
                                        "ac36_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                                    if ($oDaoAcordoPosicaoPeriodo->erro_status == 0) {
                                        $oErro->erro_msg = $oDaoAcordoPosicaoPeriodo->erro_msg;
                                        throw new Exception(_M($sCaminhoMensagens . "acordo_posicao_periodo_excluir",
                                            $oErro));
                                    }

                                    $oDaoAcordoPosicao->excluir($oValorAcordoPosicao->ac26_sequencial);
                                    if ($oDaoAcordoPosicao->erro_status == 0) {
                                        $oErro->erro_msg = $oDaoAcordoPosicao->erro_msg;
                                        throw new Exception(_M($sCaminhoMensagens . "acordo_posicao", $oErro));
                                    }
                                }
                            }
                        }

                        $oDaoEmpEmpenhoContrato->excluir(null, "e100_acordo = {$iContrato}");
                        if ($oDaoEmpEmpenhoContrato->erro_status == 0) {
                            $oErro->erro_msg = $oDaoEmpEmpenhoContrato->erro_msg;
                            throw new Exception(_M($sCaminhoMensagens . "empempenho_contrato_excluir", $oErro));
                        }
                    }
                }
                /* Modification PADRS - salvar base legal de contratacao do acordo */

                db_fim_transacao(false);
                $_SESSION["oContrato"] = $oContrato;
                $oRetorno->iCodigoContrato = $oContrato->getCodigoAcordo();
            } else {
                db_fim_transacao(true);
                $oRetorno->status = 2;
                $oRetorno->message = urlencode(str_replace("\\n", "\n", $sMessagemInvalido));
            }
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }

        break;

    case "getDadosAcordo" :

        try {
            unset($_SESSION["oContrato"]);
            $oContrato = new Acordo($oParam->iContrato);
            $iDepartamentoResponsavel = $oContrato->getDepartamentoResponsavel();
            $oDepartamento = new DBDepartamento($iDepartamentoResponsavel);
            $_SESSION["oContrato"] = $oContrato;
            $oDadosContrato = new stdClass();
            $oDadosContrato->iSequencial = $oContrato->getCodigoAcordo();
            $oDadosContrato->iOrigem = $oContrato->getOrigem();
            $oDadosContrato->iGrupo = $oContrato->getGrupo();
            $oDadosContrato->iNumero = $oContrato->getNumero();
            $oDadosContrato->iContratado = $oContrato->getContratado()->getCodigo();
            $oDadosContrato->sNomeContratado = urlencode($oContrato->getContratado()->getNome());
            $oDadosContrato->iDepartamentoResponsavel = $iDepartamentoResponsavel;
            $oDadosContrato->sNomeDepartamentoResponsavel = urlencode($oDepartamento->getNomeDepartamento());
            $oDadosContrato->dtInicio = $oContrato->getDataInicial();
            $oDadosContrato->dtTermino = $oContrato->getDataFinal();
            $oDadosContrato->dtAssinatura = $oContrato->getDataAssinatura();
            $oDadosContrato->sLei = $oContrato->getLei();
            $oDadosContrato->iComissao = $oContrato->getComissao()->getCodigo();
            $oDadosContrato->sNomeComissao = urlencode($oContrato->getComissao()->getDescricao());
            $oDadosContrato->sObjeto = urlencode($oContrato->getObjeto());
            $oDadosContrato->sResumoObjeto = urlencode($oContrato->getResumoObjeto());
            $oDadosContrato->sNumeroProcesso = urlencode($oContrato->getProcesso());
            $oDadosContrato->iNumeroRenovacao = $oContrato->getQuantidadeRenovacao();
            $oDadosContrato->iTipoRenovacao = $oContrato->getTipoRenovacao();
            $oDadosContrato->lPeriodoComercial = $oContrato->getPeriodoComercial() == "t" ? "true" : false;
            $oDadosContrato->iCategoriaAcordo = $oContrato->getCategoriaAcordo();
            $oDadosContrato->iTipoUnidadeTempoVigencia = $oContrato->getTipoUnidadeTempoVigencia();
            $oDadosContrato->iQtdPeriodoVigencia = $oContrato->getQtdPeriodoVigencia();
            $oDadosContrato->lEmergencial = $oContrato->isEmergencial();
            $oDadosContrato->iClassificacao = $oContrato->getClassificacao()->getCodigo();
            $oDadosContrato->nValorContrato = $oContrato->getValorContrato();
            $oDadosContrato->iTipoInstrumento = $oContrato->getTipoInstrumento();
            $oDadosContrato->lDependeOrdemInicio = $oContrato->getDependeOrdemInicio() === true ? 't' : 'f';

            /* Modification PADRS - obtem dados do vinculo do acordo */
 
            $oRetorno->contrato = $oDadosContrato;
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "getElementosMateriais" :

        $oMaterial = new MaterialCompras($oParam->iMaterial);
        $oRetorno->itens = $oMaterial->getElementos();
        break;

    case "adicionarItem" :

        try {
            db_inicio_transacao();
            $oItemContrato = new AcordoItem();
            $oContrato = $_SESSION["oContrato"];
            if (!$oContrato instanceof Acordo) {
                throw new Exception("Objeto do contrato não encontrado.");
            }
            $oPosicao = $oContrato->getUltimaPosicao();

            $oItemContrato->setCodigoPosicao($oPosicao->getCodigo());
            $oItemContrato->setElemento($oParam->material->iElemento);
            $oItemContrato->setQuantidade($oParam->material->nQuantidade);
            $oItemContrato->setValorUnitario($oParam->material->nValorUnitario);
            $oItemContrato->setUnidade($oParam->material->iUnidade);
            $oItemContrato->setResumo(addslashes(db_stdClass::normalizeStringJson($oParam->material->sResumo)));
            $oItemContrato->setMaterial(new MaterialCompras($oParam->material->iMaterial));
            $oItemContrato->setTipoControle($oParam->material->iTipoControle);
            $oItemContrato->setPeriodos($oParam->material->aPeriodo);
            $oItemContrato->setPeriodosExecucao($oContrato->getCodigoAcordo(), $oContrato->getPeriodoComercial());
            $oItemContrato->save();

            $oPosicao->adicionarItens($oItemContrato);


            $oContrato->atualizaValorContratoPorTotalItens();

            db_fim_transacao(false);
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "getItensAcordo" :

        try {
            if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
                $oContrato = $_SESSION["oContrato"];

                $oRetorno->iTipoContrato = $oContrato->getOrigem();
                $oPosicao = $oContrato->getUltimaPosicao();
                $oRetorno->iCodigoPosicao = $oPosicao->getCodigo();
                $aItens = $oPosicao->getItens();
                $aDadosSelecaoAcordo = array();
                if (!isset($_SESSION["dadosSelecaoAcordo"])) {
                    $_SESSION["dadosSelecaoAcordo"] = array();
                }

                foreach ($aItens as $oItemContrato) {
                    $oItem = new stdClass();
                    $oItem->codigo = $oItemContrato->getCodigo();
                    $oItem->material = $oItemContrato->getMaterial()->getDescricao();
                    $oItem->codigomaterial = $oItemContrato->getMaterial()->getMaterial();
                    $oItem->quantidade = $oItemContrato->getQuantidade();
                    $oItem->valorunitario = $oItemContrato->getValorUnitario();
                    $oItem->valortotal = $oItemContrato->getValorUnitario() * $oItemContrato->getQuantidade();
                    $oItem->quantidade = $oItemContrato->getQuantidade();
                    $oItem->elemento = $oItemContrato->getElemento();
                    $oItem->elementocodigo = $oItemContrato->getDesdobramento();
                    $oItem->elementodescricao = $oItemContrato->getDescricaoElemento();
                    $oItem->unidade = $oItemContrato->getUnidade();
                    $oItem->resumo = urlencode(str_replace("\\n", "\n", urldecode($oItemContrato->getResumo())));
                    $oItem->tipocontrole = $oItemContrato->getTipocontrole();

                    /**
                     * Percorremos os periodos do ITEM formatando eles para o formado brasileiro: DD/MM/YYYY
                     */
                    $aPeriodosDoItem = $oItemContrato->getPeriodosItem();
                    $aPeriodosFormatados = array();
                    foreach ($aPeriodosDoItem as $oPeriodo) {
                        $oStdPeriodo = new stdClass();
                        $oStdPeriodo->dtDataInicial = implode("/",
                            array_reverse(explode("-", $oPeriodo->dtDataInicial)));
                        $oStdPeriodo->dtDataFinal = implode("/", array_reverse(explode("-", $oPeriodo->dtDataFinal)));
                        $aPeriodosFormatados[] = $oStdPeriodo;
                    }
                    $oItem->aPeriodosItem = $aPeriodosFormatados;
                    $oItem->ordem = $oItemContrato->getOrdem();
                    $oItem->totaldotacoes = 0;
                    $oDadosOrigem = $oItemContrato->getOrigem();
                    if ($oDadosOrigem->tipo != 0) {
                        if (!in_array($oDadosOrigem->codigoorigem, $_SESSION["dadosSelecaoAcordo"])) {
                            $_SESSION["dadosSelecaoAcordo"][] = $oDadosOrigem->codigoorigem;
                        }
                    }
                    foreach ($oItemContrato->getDotacoes() as $oDotacao) {
                        $oItem->totaldotacoes += $oDotacao->valor;
                    }
                    if (isset($oParam->iCodigoItem)) {
                        if ($oParam->iCodigoItem == $oItemContrato->getCodigo()) {
                            $oRetorno->item = $oItem;
                        }
                    } else {
                        $oRetorno->itens[] = $oItem;
                    }
                }
            }
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "alterarItem" :

        try {
            if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
                $oContrato = $_SESSION["oContrato"];
                $oPosicao = $oContrato->getUltimaPosicao();
                $aItens = $oPosicao->getItens();

                $oRetorno->lAlterarDotacao = false;
                foreach ($aItens as $oItem) {
                    if ($oParam->material->iCodigo == $oItem->getCodigo()) {
                        $oItemContrato = $oItem;
                        break;
                    }
                }
                db_inicio_transacao();

                $oItemContrato->setCodigoPosicao($oPosicao->getCodigo())
                    ->setElemento($oParam->material->iElemento)
                    ->setQuantidade($oParam->material->nQuantidade)
                    ->setValorUnitario($oParam->material->nValorUnitario)
                    ->setUnidade($oParam->material->iUnidade)
                    ->setResumo(addslashes(db_stdClass::normalizeStringJson($oParam->material->sResumo)))
                    ->setTipoControle($oParam->material->iTipoControle)
                    ->setPeriodos($oParam->material->aPeriodo)
                    ->setPeriodosExecucao($oContrato->getCodigoAcordo(), $oContrato->getPeriodoComercial());
                $oItemContrato->setMaterial(new MaterialCompras($oParam->material->iMaterial));

                if (count($oItemContrato->getDotacoes()) == 1) {
                    $aDotacao = $oItemContrato->getDotacoes();
                    $aDotacao[0]->valor = $oItemContrato->getValorTotal();
                    $aDotacao[0]->quantidade = $oItemContrato->getQuantidade();
                } else {
                    if (count($oItemContrato->getDotacoes()) > 1) {
                        $oRetorno->lAlterarDotacao = true;
                    }
                }
                $oItemContrato->save();

                $oContrato->atualizaValorContratoPorTotalItens();

                db_fim_transacao(false);
            }
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
            db_fim_transacao(true);
        }

        break;

    case "getDotacoesItens" :

        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();
            $aItens = $oPosicao->getItens();
            foreach ($aItens as $oItem) {
                if ($oParam->iCodigoItem == $oItem->getCodigo()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }
            if (isset($oItemContrato)) {
                $oRetorno->dotacoes = $oItem->getDotacoes();
                $oRetorno->iElementoDotacao = $oItem->getDesdobramento();
            } else {
                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado não foi encontrado.");
            }
        }
        break;

    case "saveDotacaoItens" :

        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();
            $aItens = $oPosicao->getItens();
            foreach ($aItens as $oItem) {
                if ($oParam->iCodigoItem == $oItem->getCodigo()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }

            if (isset($oItemContrato)) {
                try {
                    db_inicio_transacao();
                    $oDotacao = new stdClass();
                    $oDotacao->ano = db_getsession("DB_anousu");
                    $oDotacao->valor = $oParam->nValor;
                    $oDotacao->dotacao = $oParam->iDotacao;
                    $oDotacao->quantidade = $oParam->nQuantidade;
                    $oItem->adicionarDotacoes($oDotacao);
                    $oRetorno->dotacoes = $oItem->getDotacoes();
                    $oItem->save();
                    $oRetorno->iElementoDotacao = $oItem->getDesdobramento();
                    db_fim_transacao(false);
                } catch (Exception $eErro) {
                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                    $oRetorno->dotacoes = $oItem->getDotacoes();
                }
            } else {
                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado não foi encontrado.");
            }
        }
        break;

    case "excluirDotacaoItens":

        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();
            $aItens = $oPosicao->getItens();
            foreach ($aItens as $oItem) {
                if ($oParam->iCodigoItem == $oItem->getCodigo()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }

            if (isset($oItemContrato)) {
                try {
                    db_inicio_transacao();
                    $oItemContrato->removerDotacao($oParam->iDotacao);
                    $oItemContrato->save();

                    $oContrato->atualizaValorContratoPorTotalItens();

                    db_fim_transacao(false);
                } catch (Exception $eErro) {
                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                }
            } else {
                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado não foi encontrado.");
            }
            $oRetorno->dotacoes = $oItem->getDotacoes();
        }

    case "getItensOrigem" :

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];

            $oDataInicialAcordo = new DBDate($oContrato->getDataInicial());
            $oRetorno->dtInicialAcordo = $oDataInicialAcordo->convertTo(DBDate::DATA_PTBR);

            $oDataFinalAcordo = new DBDate($oContrato->getDataFinal());
            $oRetorno->dtFinalAcordo = $oDataFinalAcordo->convertTo(DBDate::DATA_PTBR);
            $oContratado = $oContrato->getContratado();

            $oRetorno->lLiberaEdicaoQuantidade = false;
            if ($oContrato->getOrigem() == 2) {
                /**
                 * Valida se a modalidade da licitação é CHAMAMENTO PÚBLICO / CREDENCIAMENTO
                 */
                $aLicitacoes = $_SESSION["dadosSelecaoAcordo"];
                $oDaoLicitacao = new cl_liclicita();

                $sWhere = "     l20_codigo in (" . implode(', ', $aLicitacoes) . ") ";
                $sWhere .= " and l03_pctipocompratribunal = 54 ";
                $sSql = $oDaoLicitacao->sql_query_licitacao_encerramento(" distinct 1 ", $sWhere);
                $rs = db_query($sSql);

                $aFitros = array();
                $lValidaAcordo = true;

                if ($rs && pg_num_rows($rs) > 0) {
                    //Verifica se ha itens vinculados ao fornecedor onde ainda não existem contrato.

                    $sFiltro = " l21_codigo not in (select ac24_liclicitem ";
                    $sFiltro .= "                      from acordoliclicitem ";
                    $sFiltro .= "                      join acordoitem    on ac20_sequencial = acordoliclicitem.ac24_acordoitem ";
                    $sFiltro .= "                      join acordoposicao on ac26_sequencial = ac20_acordoposicao ";
                    $sFiltro .= "                      join acordo        on ac16_sequencial = ac26_acordo ";
                    $sFiltro .= "                     where ac24_liclicitem = l21_codigo ";
                    $sFiltro .= "                       and acordo.ac16_contratado = {$oContratado->getCodigo()}) ";

                    $oRetorno->lLiberaEdicaoQuantidade = true;
                    $aFitros[] = $sFiltro;
                    $lValidaAcordo = false;
                }
                $aItens = licitacao::getItensPorFornecedor($aLicitacoes, $oContratado->getCodigo(), $lValidaAcordo,
                    true, $aFitros);
            } else {
                $aItens = ProcessoCompras::getItensPorFornecedor($_SESSION["dadosSelecaoAcordo"],
                    $oContratado->getCodigo(), true);
            }
            $oRetorno->itens = $aItens;

            $_SESSION["aItensOrigem"] = $aItens;
        }
        break;

    // Busca todas as Licitacoes e itens por fornecedor, para importacao no acordo manual
    case "getLicitacaoItensPorFornecedor":

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];

            $oDataInicialAcordo = new DBDate($oContrato->getDataInicial());
            $oRetorno->dtInicialAcordo = $oDataInicialAcordo->convertTo(DBDate::DATA_PTBR);

            $oDataFinalAcordo = new DBDate($oContrato->getDataFinal());
            $oRetorno->dtFinalAcordo = $oDataFinalAcordo->convertTo(DBDate::DATA_PTBR);
            $oRetorno->licitacoes = array();

            if ($oContrato->getOrigem() == 3) {
                // Busca todas licitacoes que o contratante participa ou participou
                $aLicitacao = licitacao::getLicitacoesByFornecedor($oContrato->getContratado()->getCodigo());
                if (!empty($aLicitacao)) {
                    foreach ($aLicitacao as $oLicitacao) {
                        $aItens = licitacao::getItensPorFornecedor(array($oLicitacao->licitacao),
                            $oContrato->getContratado()->getCodigo(), true);
                        if (!empty($aItens)) {
                            $oLicitacao->itens = $aItens;
                            $oRetorno->licitacoes[] = $oLicitacao;
                        }
                    }
                }
            }
        }
        break;

    case "adicionarItensOrigem" :

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();

            if ($oContrato->getOrigem() == 2 || $oContrato->getOrigem() == 1) {
                try {
                    db_inicio_transacao();
                    $lErro = false;

                    $iVigenciaInicial = db_formatar($oContrato->getDataInicial(), 'd');
                    $iVigenciaFinal = db_formatar($oContrato->getDataFinal(), 'd');

                    foreach ($oParam->aLista as $iIndice => $oItem) {
                        $iExecucaoInicial = db_formatar($oItem->dtInicial, 'd');
                        $iExecucaoFinal = db_formatar($oItem->dtFinal, 'd');

                        if ($iExecucaoInicial > $iExecucaoFinal) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_item_maior_execucao", $oErro));
                        }

                        if ($iExecucaoInicial < $iVigenciaInicial) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_item_menor_vigencia", $oErro));
                        }

                        if ($iExecucaoFinal > $iVigenciaFinal) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_execucao_final_maior_vigencia",
                                $oErro));
                        }

                        if ($oContrato->getOrigem() == 2) {
                            $oPosicao->adicionarItemDeLicitacao($oItem->codigo, $oItem);
                        } else {
                            if ($oContrato->getOrigem() == 1) {
                                $oPosicao->adicionarItemDeProcesso($oItem->codigo, $oItem);
                            }
                        }
                    }

                    $oContrato->atualizaValorContratoPorTotalItens();
                    db_fim_transacao($lErro);
                } catch (Exception $eErro) {
                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                }
            }
        }
        break;

    case "adicionarLicitacaoItens" :
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();

            if ($oContrato->getOrigem() == 3) {
                try {
                    db_inicio_transacao();
                    $lErro = false;

                    $iVigenciaInicial = db_formatar($oContrato->getDataInicial(), 'd');
                    $iVigenciaFinal = db_formatar($oContrato->getDataFinal(), 'd');

                    foreach ($oParam->aLista as $iIndice => $oItem) {
                        $iExecucaoInicial = db_formatar($oItem->dtInicial, 'd');
                        $iExecucaoFinal = db_formatar($oItem->dtFinal, 'd');

                        if ($iExecucaoInicial > $iExecucaoFinal) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_item_maior_execucao", $oErro));
                        }

                        if ($iExecucaoInicial < $iVigenciaInicial) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_item_menor_vigencia", $oErro));
                        }

                        if ($iExecucaoFinal > $iVigenciaFinal) {
                            $oErro->codigomaterial = $oItem->codigomaterial;
                            throw new Exception(_M($sCaminhoMensagens . "periodo_execucao_final_maior_vigencia",
                                $oErro));
                        }

                        $oPosicao->adicionarItemDeLicitacao($oItem->codigo, $oItem, false);
                    }
                    $oContrato->atualizaValorContratoPorTotalItens();

                    db_fim_transacao($lErro);
                } catch (Exception $eErro) {
                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                }
            }
        }
        break;

    case "excluirFracionamento":

        if (isset($_SESSION["aItensOrigem"])) {
            foreach ($_SESSION["aItensOrigem"] as &$oItem) {
                if ($oItem->codigo == $oParam->iItem) {
                    $oItemOrigem = $oItem;
                    break;
                }
            }

            if (isset($oItemOrigem)) {
                if (isset($oItemOrigem->fracionamentos[$oParam->iFracionamento])) {
                    $oItemOrigem->valortotal += $oItemOrigem->fracionamentos[$oParam->iFracionamento]->valortotal;
                    array_splice($oItemOrigem->fracionamentos, $oParam->iFracionamento, 1);
                }
            }
            $oRetorno->itens = $_SESSION["aItensOrigem"];
        }
        break;

    case "excluirItem":

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
            $oContrato = $_SESSION["oContrato"];
            $oPosicao = $oContrato->getUltimaPosicao();
            try {
                db_inicio_transacao();

                $oPosicao->removerItem($oParam->material->iCodigo);

                $oContrato->atualizaValorContratoPorTotalItens();

                db_fim_transacao(false);
            } catch (Exception $eErro) {
                db_fim_transacao(true);
                $oRetorno->status = 2;
                $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
            }
        }
        break;

    case "getSaldoDotacao" :

        $oDotacao = new Dotacao($oParam->iDotacao, db_getsession("DB_anousu"));
        $oRetorno->saldofinal = $oDotacao->getSaldoFinal();
        break;

    case "getAcordoProgramacaFinanceira" :

        if (!empty($oParam->acordo)) {
            $oAcordo = new Acordo($oParam->acordo);
            $oRetorno->valortotal = $oAcordo->getValoresItens();

            $oAcordoProgramacaoFinanceira = new cl_acordoprogramacaofinanceira();
            $sWhere = "ac34_acordo = {$oParam->acordo}";
            $sSqlAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_query(null,
                "acordoprogramacaofinanceira.*",
                null, $sWhere);
            $rsAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_record($sSqlAcordoProgramacaoFinanceira);
            if ($oAcordoProgramacaoFinanceira->numrows > 0) {
                $oProgramacaoFinanceira = db_utils::fieldsMemory($rsAcordoProgramacaoFinanceira, 0);
                $oRetorno->programacaofinanceira = $oProgramacaoFinanceira->ac34_programacaofinanceira;
            } else {
                $oRetorno->programacaofinanceira = null;
            }
        }
        break;

    case "incluirAcordoProgramacaFinanceira" :

        try {
            db_inicio_transacao();

            $oAcordoProgramacaoFinanceira = new cl_acordoprogramacaofinanceira();
            $sWhere = "ac34_acordo = {$oParam->acordo} and ac34_programacaofinanceira = {$oParam->codigo}";
            $sSqlAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_query(null,
                "acordoprogramacaofinanceira.*",
                null, $sWhere);
            $rsAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_record($sSqlAcordoProgramacaoFinanceira);
            if ($oAcordoProgramacaoFinanceira->numrows == 0) {
                $oAcordoProgramacaoFinanceira->ac34_programacaofinanceira = $oParam->codigo;
                $oAcordoProgramacaoFinanceira->ac34_acordo = $oParam->acordo;
                $oAcordoProgramacaoFinanceira->incluir(null);
            }

            db_fim_transacao(false);
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;
    case "adicionarDocumento":

        $oAcordo = new Acordo($oParam->acordo);

        try {
            db_inicio_transacao();
            $oAcordo->adicionarDocumento(addslashes(db_stdClass::normalizeStringJson($oParam->descricao)),
                $oParam->arquivo);
            db_fim_transacao(false);
        } catch (Exception $oErro) {
            db_fim_transacao(true);
            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status = 2;
        }
        break;

    case "getDocumento":


        if (isset($oParam->acordo)) {
            $iCodigoAcordo = $oParam->acordo;
        } else {
            if (isset($oParam->ac16_sequencial)) {
                $iCodigoAcordo = $oParam->ac16_sequencial;
            }
        }

        $oAcordo = new Acordo($iCodigoAcordo);
        $aAcordoDocumento = $oAcordo->getDocumentos();
        $oRetorno->dados = array();

        for ($i = 0; $i < count($aAcordoDocumento); $i++) {
            if ($aAcordoDocumento[$i]->origemEvento()) {
                continue;
            }

            $oDocumentos = new stdClass();
            $oDocumentos->iCodigo = $aAcordoDocumento[$i]->getCodigo();
            $oDocumentos->iAcordo = $aAcordoDocumento[$i]->getCodigoAcordo();
            $oDocumentos->sDescricao = urlencode(utf8_encode($aAcordoDocumento[$i]->getDescricao()));
            $oRetorno->dados[] = $oDocumentos;
        }

        $oRetorno->detalhe = "documentos";
        break;
    case "excluirDocumento":

        $oAcordo = new Acordo($oParam->acordo);
        try {
            $oAcordo->removeDocumento($oParam->codigoDocumento);
        } catch (Exception $oErro) {
            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status = 2;
        }

        break;
    case "downloadDocumento":

        $oDocumento = new AcordoDocumento($oParam->iCodigoDocumento);
        db_inicio_transacao();

        // Abrindo o objeto no modo leitura "r" passando como parâmetro o OID.
        $sNomeArquivo = "tmp/{$oDocumento->getNomeArquivo()}";
        pg_lo_export($conn, $oDocumento->getArquivo(), $sNomeArquivo);
        db_fim_transacao(true);
        $oRetorno->nomearquivo = $sNomeArquivo;
        // Setando Cabeçalho do browser para interpretar que o binário que será carregado é de uma foto do tipo JPEG.
        break;

    case "buscaPeriodosItem":

        $oAcordoItem = new AcordoItem($oParam->iCodigoItem);

        $oRetorno->iCodigoItem = $oParam->iCodigoItem;
        $oRetorno->nomeItem = $oAcordoItem->getMaterial()->getDescricao();
        $oRetorno->periodos = $oAcordoItem->getPeriodosItem();

        break;

    /**
     * Exclui um acordo
     */
    case 'excluirAcordo':

        try {
            if (!isset($oParam->iAcordo) || empty($oParam->iAcordo)) {
                throw new ParameterException(_M($sCaminhoMensagens . 'acordo_nao_informado'));
            }

            db_inicio_transacao();

            $oAcordo = new Acordo($oParam->iAcordo);
            $oAcordo->remover();

            db_fim_transacao();

            $oRetorno->message = urlencode(_M($sCaminhoMensagens . 'acordo_excluido'));
        } catch (Exception $oErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }

        break;

    case 'download':

        $membro = new AcordoComissaoMembro($get->codigo);
        $nomeArquivo = $membro->getNomeArquivo();
        $caminho = 'tmp/' . $nomeArquivo;

        if (file_exists($caminho)) {
            unlink($caminho);
        }

        db_inicio_transacao();
        if (pg_lo_export($membro->getArquivo(), $caminho)) {
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/force-download');
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: attachment; filename="' . $nomeArquivo . '";');
            header('Content-Length: ' . filesize($caminho));
            readfile($caminho);
            exit;
        }
        db_fim_transacao(false);
        break;
}

echo $oJson->encode($oRetorno);
