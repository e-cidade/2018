<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('std/db_stdClass.php'));
require_once(modification('classes/db_protprocesso_classe.php'));
require_once(modification('classes/db_proctransfer_classe.php'));
require_once(modification('classes/db_proctransferproc_classe.php'));
require_once(modification('classes/db_procandam_classe.php'));
require_once(modification('classes/db_proctransand_classe.php'));
require_once(modification('classes/db_processoouvidoriaprorrogacao_classe.php'));
require_once(modification('classes/db_calend_classe.php'));
require_once(modification('model/processoOuvidoria.model.php'));

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\MensageriaProcesso;

$oPost = db_utils::postMemory($_POST);
$oJson = new services_json();
$lErro = false;
$sMsgErro = '';

$clProtProcesso = new cl_protprocesso();
$clProcTransfer = new cl_proctransfer();
$clProcTransferProc = new cl_proctransferproc();
$clProcTransferProcValida = new cl_proctransferproc();
$clProcAndam = new cl_procandam();
$clProcTransAnd = new cl_proctransand();
$clProcessoOuvidoriaProrrogacao = new cl_processoouvidoriaprorrogacao();
$clCalend = new cl_calend();
$oProcessoOuvidoria = new processoOuvidoria();

switch ($oPost->sMethod) {
    case 'validaDeptoInicial':

        $aListaProcesso = $oJson->decode(str_replace("\\", "", $oPost->aListaProcesso));
        $aListaDiferenca = array();
        foreach ($aListaProcesso as $iInd => $iCodProcesso) {
            $rsAndPadrao = $clProtProcesso->sql_record($clProtProcesso->sql_query_andpadrao($iCodProcesso, "*",
                "p53_ordem desc"));
            $iLinhasAndPadrao = $clProtProcesso->numrows;
            $lTemDepto = false;
            if ($iLinhasAndPadrao > 0) {
                for ($iInd = 0; $iInd < $iLinhasAndPadrao; $iInd++) {
                    $oAndPadrao = db_utils::fieldsMemory($rsAndPadrao, $iInd);
                    if ($oAndPadrao->p53_ordem != 1 && $oAndPadrao->p53_coddepto == $oPost->iCodDeptoRec) {
                        $lTemDepto = true;
                    }
                    if ($oAndPadrao->p53_ordem == 1 && $oAndPadrao->p53_coddepto != $oPost->iCodDeptoRec) {
                        $oAndPadrao->lTemDepto = $lTemDepto;
                        $aListaDiferenca[] = $oAndPadrao;
                    }
                }
            }
        }
        $aRetorno = array("aListaDiferenca" => $aListaDiferenca);
        break;

    case 'incluirTramite':

        $aObjProcessos = $oJson->decode(str_replace("\\", "", $oPost->aObjProcesso));

        db_inicio_transacao();

        $clProcTransfer->p62_hora = db_hora();
        $clProcTransfer->p62_dttran = date('Y-m-d', db_getsession('DB_datausu'));
        $clProcTransfer->p62_id_usuario = db_getsession("DB_id_usuario");
        $clProcTransfer->p62_coddepto = db_getsession("DB_coddepto");
        $clProcTransfer->p62_id_usorec = $oPost->iIdUsuarioRec;
        $clProcTransfer->p62_coddeptorec = $oPost->iCodDeptoRec;
        $clProcTransfer->incluir(null);
        if ($clProcTransfer->erro_status == 0) {
            $lErro = true;
            $sMsgErro = $clProcTransfer->erro_msg;
        }

        if (!$lErro) {
            foreach ($aObjProcessos as $iIndObj => $oProcesso) {
                // Verifica se já existe andamento inicial para não acontecer duplicidade.
                $sSqlProcTransferProc = $clProcTransferProcValida->sql_query_file(null, $oProcesso->iCodProc, '*', null,
                    null);
                $clProcTransferProcValida->sql_record($sSqlProcTransferProc);

                if ($clProcTransferProcValida->numrows == 0) {
                    $clProcTransferProc->p63_codproc = $oProcesso->iCodProc;
                    $clProcTransferProc->p63_codtran = $clProcTransfer->p62_codtran;
                    $clProcTransferProc->incluir($clProcTransfer->p62_codtran, $oProcesso->iCodProc);

                    if ($clProcTransferProc->erro_status == 0) {
                        $lErro = true;
                        $sMsgErro = $clProcTransferProc->erro_msg;
                        break;
                    }
                }

                // Caso seja um processo de Ouvidoria
                if ($oPost->iGrupo == 2) {
                    /**
                     * Inclui Previsão de Entrega (processoouvidoriaprorrogacao)
                     */
                    $rsAndPadrao = $clProtProcesso->sql_record($clProtProcesso->sql_query_andpadrao($oProcesso->iCodProc,
                        "*", "p53_ordem"));
                    $iLinhasAndPadrao = $clProtProcesso->numrows;
                    $dtDataIni = strtotime("+1 day", db_getsession('DB_datausu'));
                    $lSegueAndamento = true;
                    for ($iInd = 0; $iInd < $iLinhasAndPadrao; $iInd++) {
                        $oAndPadrao = db_utils::fieldsMemory($rsAndPadrao, $iInd);
                        if ($oPost->iCodDeptoRec != $oAndPadrao->p53_coddepto && $oAndPadrao->p53_ordem == 1) {
                            if ($oProcesso->lSegue) {
                                $iDeptoContinua = $oPost->iCodDeptoRec;
                                $lSegueAndamento = false;
                            } else {
                                $iDias = $oProcesso->iDias;
                                $iSomaDia = 1;
                                $lFeriado = true;
                                $dtDataFim = $dtDataIni;
                                while ($lFeriado) {
                                    $rsConsultaFeriado = $clCalend->sql_record($clCalend->sql_query_file(date('Y-m-d',
                                        $dtDataFim)));
                                    if ($clCalend->numrows > 0) {
                                        if ($dtDataIni == $dtDataFim) {
                                            $dtDataIni = strtotime("+1 day", $dtDataIni);
                                        }
                                        $dtDataFim = strtotime("+1 day", $dtDataFim);
                                        ++$iDias;
                                    } else {
                                        if ($iSomaDia >= $iDias) {
                                            $lFeriado = false;
                                        } else {
                                            $dtDataFim = strtotime("+1 day", $dtDataFim);
                                        }
                                    }
                                    ++$iSomaDia;
                                }
                                $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $oProcesso->iCodProc;
                                $clProcessoOuvidoriaProrrogacao->ov15_coddepto = $oPost->iCodDeptoRec;
                                $clProcessoOuvidoriaProrrogacao->ov15_dtini = date('Y-m-d', $dtDataIni);
                                $clProcessoOuvidoriaProrrogacao->ov15_dtfim = date('Y-m-d', $dtDataFim);
                                $clProcessoOuvidoriaProrrogacao->ov15_motivo = '';
                                $clProcessoOuvidoriaProrrogacao->ov15_ativo = 'true';
                                $clProcessoOuvidoriaProrrogacao->incluir(null);
                                if ($clProcessoOuvidoriaProrrogacao->erro_status == 0) {
                                    $lErro = true;
                                    $sMsgErro = $clProcessoOuvidoriaProrrogacao->erro_msg;
                                    break;
                                }
                                $dtDataIni = strtotime("+1 day", $dtDataFim);
                            }
                        }

                        if (!$lSegueAndamento) {
                            if ($oAndPadrao->p53_coddepto == $oPost->iCodDeptoRec) {
                                $iDias = $oProcesso->iDias;
                                $lSegueAndamento = true;
                            } else {
                                continue;
                            }
                        } else {
                            $iDias = $oAndPadrao->p53_dias;
                        }
                        $iSomaDia = 1;
                        $lFeriado = true;
                        $dtDataFim = $dtDataIni;
                        while ($lFeriado) {
                            $rsConsultaFeriado = $clCalend->sql_record($clCalend->sql_query_file(date('Y-m-d',
                                $dtDataFim)));
                            if ($clCalend->numrows > 0) {
                                if ($dtDataIni == $dtDataFim) {
                                    $dtDataIni = strtotime("+1 day", $dtDataIni);
                                }
                                $dtDataFim = strtotime("+1 day", $dtDataFim);
                                ++$iDias;
                            } else {
                                if ($iSomaDia >= $iDias) {
                                    $lFeriado = false;
                                } else {
                                    $dtDataFim = strtotime("+1 day", $dtDataFim);
                                }
                            }
                            ++$iSomaDia;
                        }
                        $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $oProcesso->iCodProc;
                        $clProcessoOuvidoriaProrrogacao->ov15_coddepto = $oAndPadrao->p53_coddepto;
                        $clProcessoOuvidoriaProrrogacao->ov15_dtini = date('Y-m-d', $dtDataIni);
                        $clProcessoOuvidoriaProrrogacao->ov15_dtfim = date('Y-m-d', $dtDataFim);
                        $clProcessoOuvidoriaProrrogacao->ov15_motivo = '';
                        $clProcessoOuvidoriaProrrogacao->ov15_ativo = 'true';
                        $clProcessoOuvidoriaProrrogacao->incluir(null);

                        if ($clProcessoOuvidoriaProrrogacao->erro_status == 0) {
                            $lErro = true;
                            $sMsgErro = $clProcessoOuvidoriaProrrogacao->erro_msg;
                            break;
                        }
                        $dtDataIni = strtotime("+1 day", $dtDataFim);
                    }
                }
            }
        }

        foreach ($aObjProcessos as $oProcesso) {
            if ($oPost->iIdUsuarioRec && $oPost->iIdUsuarioRec != db_getsession('DB_id_usuario')) {
                MensageriaProcesso::enviar($oProcesso->iCodProc);
            }
        }

        db_fim_transacao($lErro);
        if (!$lErro) {
            $sMsgErro = 'Tramite feito com sucesso!';
            $iCodTran = $clProcTransfer->p62_codtran;
        } else {
            $iCodTran = '';
        }
        $aRetorno = array(
            "lErro" => $lErro,
            "sMsg" => urlencode($sMsgErro),
            "iCodTran" => $iCodTran
        );
        break;

    case 'incluirTransferencia':
        $aObjProcessos = $oJson->decode(str_replace("\\", "", $oPost->aObjProcesso));
        db_inicio_transacao();
        $clProcTransfer->p62_hora = db_hora();
        $clProcTransfer->p62_dttran = date('Y-m-d', db_getsession('DB_datausu'));
        $clProcTransfer->p62_id_usuario = db_getsession("DB_id_usuario");
        $clProcTransfer->p62_coddepto = db_getsession("DB_coddepto");
        $clProcTransfer->p62_id_usorec = $oPost->iIdUsuarioRec;
        $clProcTransfer->p62_coddeptorec = $oPost->iCodDeptoRec;
        $clProcTransfer->incluir(null);

        if ($clProcTransfer->erro_status == 0) {
            $lErro = true;
            $sMsgErro = $clProcTransfer->erro_msg;
        }
        if (!$lErro) {
            foreach ($aObjProcessos as $iIndObj => $oProcesso) {
                $oProcessoProtocolo = new processoProtocolo($oProcesso->iCodProc);

                if ($oProcessoProtocolo->ultimaTransferenciaPendente() !== null) {
                    $sMsgErro = "Processo {$oProcessoProtocolo->getNumeroProcesso()}/{$oProcessoProtocolo->getAnoProcesso()} já possui uma transferência em aberto.";
                    $lErro = true;
                    break;
                }

                $clProcTransferProc->p63_codproc = $oProcesso->iCodProc;
                $clProcTransferProc->p63_codtran = $clProcTransfer->p62_codtran;
                $clProcTransferProc->incluir($clProcTransfer->p62_codtran, $oProcesso->iCodProc);
                if ($clProcTransferProc->erro_status == 0) {
                    $lErro = true;
                    $sMsgErro = $clProcTransferProc->erro_msg;
                    break;
                }
                // Caso seja um processo de Ouvidoria
                if ($oPost->iGrupo == 2) {
                    if ($oProcesso->lNovoDepto == 'true') {
                        try {
                            $oProcessoOuvidoria->incluiNovoDeptoProrrogacao($oProcesso->iCodProc,
                                $oPost->iCodDeptoRec, '', '',
                                $oProcesso->iDias,
                                ($oProcesso->lSegue == 'true' ? true : false));
                        } catch (Exception $eException) {
                            $lErro = true;
                            $sMsgErro = $eException->getMessage();
                        }
                    }
                }

                if ($oPost->iIdUsuarioRec && $oPost->iIdUsuarioRec != db_getsession('DB_id_usuario')) {
                    MensageriaProcesso::enviar($oProcesso->iCodProc);
                }
            }
        }
        db_fim_transacao($lErro);
        if (!$lErro) {
            $sMsgErro = 'Transferencia feita com sucesso!';
            $iCodTran = $clProcTransfer->p62_codtran;
        } else {
            $iCodTran = '';
        }
        $aRetorno = array(
            "lErro" => $lErro,
            "sMsg" => urlencode($sMsgErro),
            "iCodTran" => $iCodTran
        );
        break;

    case 'validaProximoDepto':
        $aListaProcesso = $oJson->decode(str_replace("\\", "", $oPost->aListaProcesso));
        $aListaDiferenca = array();
        foreach ($aListaProcesso as $iInd => $iCodProcesso) {
            $rsDadosProcesso = $clProtProcesso->sql_record($clProtProcesso->sql_query($iCodProcesso));
            $oDadosProcesso = db_utils::fieldsMemory($rsDadosProcesso, 0, false, false, true);
            $iProximoDepto = $oProcessoOuvidoria->getProximoDepto($iCodProcesso);
            if ($iProximoDepto != $oPost->iCodDeptoRec) {
                $sWherePrazo = "     ov15_protprocesso = {$iCodProcesso} ";
                $sWherePrazo .= " and ov15_ativo is true                  ";
                $sSqlPrazoPrevisto = $clProcessoOuvidoriaProrrogacao->sql_query(null, "*", "ov15_dtfim", $sWherePrazo);
                $rsPrazoPrevisto = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlPrazoPrevisto);
                $iLinhasPrazoPrevisto = $clProcessoOuvidoriaProrrogacao->numrows;
                $lTemDepto = false;
                $lValidaDepto = false;
                for ($iIndPrazo = 0; $iIndPrazo < $iLinhasPrazoPrevisto; $iIndPrazo++) {
                    $oPrazo = db_utils::fieldsMemory($rsPrazoPrevisto, $iIndPrazo);
                    if ($iProximoDepto == $oPrazo->ov15_coddepto) {
                        $lValidaDepto = true;
                    }
                    if ($lValidaDepto) {
                        if ($oPrazo->ov15_coddepto == $oPost->iCodDeptoRec) {
                            $lTemDepto = true;
                        }
                    }
                }
                $oDadosProcesso->lTemDepto = $lTemDepto;
                $aListaDiferenca[] = $oDadosProcesso;
            }
        }
        $aRetorno = array("aListaDiferenca" => $aListaDiferenca);
        break;

    /**
     * Busca as informações para jogar na grid.
     */
    case 'buscaProcessos':

        /**
         * Busca na sessão dos dados necessários para a query de busca. E verificamos se um campo foi informado para
         * ordenar o resultado, em caso negativo ordenamos pelo campo padrão.
         */

        $iInstituicao = db_getsession("DB_instit");
        $iDepartamento = db_getsession("DB_coddepto");
        $iIdUsuario = db_getsession("DB_id_usuario");
        $sOrdem = (isset($oPost->sOrdem)) ? $oPost->sOrdem : " p58_codproc ";
        /**
         * Declaração da parte comumentre os dois tipos de WHERE
         */
        $sWhereProcessos = " and not exists (select * ";
        $sWhereProcessos .= "                   from processosapensados ";
        $sWhereProcessos .= "                  where p30_procapensado = x.p58_codproc limit 1) ";

        /**
         * Rodamos a query e tratamos o retorno da mesma criando um collection com o mesmo.
         */
        $oDaoProtProcesso = db_utils::getDao('protprocesso');

        $sSqlBuscaProcessos = $oDaoProtProcesso->sql_query_processosemtramit($oPost->iGrupo, $sOrdem, $sWhereProcessos);
        $rsBuscaProcessos = $oDaoProtProcesso->sql_record($sSqlBuscaProcessos);

        $aProcessosEncontrados = array();
        for ($iIndiceProcessos = 0; $iIndiceProcessos < $oDaoProtProcesso->numrows; $iIndiceProcessos++) {
            $oProcessoEncontrado = db_utils::fieldsMemory($rsBuscaProcessos, $iIndiceProcessos);
            $lLimite = "false";
            if (isset($oProcessoEncontrado->limite) && trim($oProcessoEncontrado->limite) == "") {
                $lLimite = "false";
            } else {
                if ($oProcessoEncontrado->limite <= date("Y-m-d", db_getsession("DB_datausu"))) {
                    $lLimite = "true";
                }
            }
            $oProcesso = new stdClass();
            $oProcesso->p58_codproc = $oProcessoEncontrado->p58_codproc;
            $oProcesso->z01_nome = urlencode($oProcessoEncontrado->z01_nome);
            $oProcesso->p51_descr = urlencode($oProcessoEncontrado->p51_descr);
            $oProcesso->p58_id_usuario = $oProcessoEncontrado->p58_id_usuario;
            $oProcesso->p58_codandam = $oProcessoEncontrado->p58_codandam;
            $oProcesso->ov01_numero = $oProcessoEncontrado->ov01_numero;
            $oProcesso->ov01_anousu = $oProcessoEncontrado->ov01_anousu;
            $oProcesso->coddepto = $oProcessoEncontrado->coddepto;
            $oProcesso->descrdepto = urlencode($oProcessoEncontrado->descrdepto);
            $oProcesso->limite = $oProcessoEncontrado->limite;
            $oProcesso->limiteBloqueado = $lLimite;
            $oProcesso->p58_ano = $oProcessoEncontrado->p58_ano;
            $oProcesso->processoProtocolo = $oProcessoEncontrado->processoprotocolo;
            $aProcessosEncontrados[] = $oProcesso;
        }
        $aRetorno->aProcessosEncontrados = $aProcessosEncontrados;
        break;
}

echo $oJson->encode($aRetorno);
