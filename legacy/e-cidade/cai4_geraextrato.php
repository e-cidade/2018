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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_extrato_classe.php"));
require_once(modification("classes/db_extratosaldo_classe.php"));
require_once(modification("classes/db_extratolinha_classe.php"));
require_once(modification("classes/db_bancoshistmov_classe.php"));

require_once(modification("classes/db_contabancaria_classe.php"));

require_once(modification("libs/JSON.php"));

/* classe para manutencao do arquivo */
require_once(modification("classes/extratoCnab240.php"));

db_postmemory($_GET);

$objJSON = new Services_JSON();
$clextrato = new cl_extrato();
$clextratosaldo = new cl_extratosaldo();
$clextratolinha = new cl_extratolinha();
$clbancoshistmov = new cl_bancoshistmov();
$clcontabancaria = new cl_contabancaria();
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
</body>
</html>
<?php
if (isset($arqname) && $arqname != "") {
    $erromsg = "";
    $linha = "";
    $arquivo = "";
    $contaatual = 0;
    $sqlerro = false;
    $lPrimeiro = true;
    $aExtratoSaldoArray = array();

    $clextratocnab240 = new cl_extratoCnab240($arqname);

    if ($clextratocnab240->erroOper) {
        db_msgbox($clextratocnab240->erromsg);
        fechaJanela();
    }

    $iTotalLinhasConta = $clextratocnab240->getTotalLinhas();
    $aContasEncontradas = array();

    for ($iInd = 0; $iInd < $iTotalLinhasConta; $iInd++) {
        if ($clextratocnab240->isHeaderLote($iInd) && $clextratocnab240->isValido($iInd)) {
            if (!$clextratocnab240->isExtrato($iInd)) {
                continue;
            }

            if ($clextratocnab240->isHeaderLote($iInd) and !$clextratocnab240->isTrailerLote($iInd + 1)) {
                if ($clextratocnab240->getConta($iInd) != false) {
                    $aContasEncontradas[] = $clextratocnab240->getConta($iInd);
                }
            }
        }
    }

    if (count($aContasEncontradas) == 0) {
        db_msgbox("Não há movimentação para as contas bancárias no arquivo.");
        fechaJanela();
        exit;
    }

    $sContasEncontradas = implode(',', $aContasEncontradas);
    $sCampos = "distinct db83_identificador as cnpj";
    $sWhere = "db83_sequencial in({$sContasEncontradas})";
    $sSqlContaBancaria = $clcontabancaria->sql_query_file(null, $sCampos, null, $sWhere);
    $rsSqlContaBancaria = $clcontabancaria->sql_record($sSqlContaBancaria);
    $iNumRowsContaBancaria = $clcontabancaria->numrows;

    if ($iNumRowsContaBancaria > 0) {
        $oContaBancaria = db_utils::fieldsMemory($rsSqlContaBancaria, 0);

        // retirado o bloqueio, pois ocorre que cliente recebem no mesmo txt dados de contas de CNPJs diferentes
        // (principalmente no caso de fundos de saude e assistencia social por exemplo...)
        if ($iNumRowsContaBancaria > 1 and false) {
            db_msgbox("Mais de um CNPJ encontrado para as contas informadas no arquivo!");
            fechaJanela();
            exit;
        }
    } else {
        db_msgbox("CNPJ não encontrado para as contas informadas no arquivo!");
        fechaJanela();
        exit;
    }

    $sGetDtarq = $clextratocnab240->getDtarq();
    $iGetCodbco = $clextratocnab240->getCodbco();

    // @todo - caso o banco for itau nao executar essa validacao
    $sWhere = "     (k85_dtarq       = '{$sGetDtarq}'             ";
    $sWhere .= " and k85_codbco       = {$iGetCodbco}              ";
    $sWhere .= " and k85_cnpj         = '{$oContaBancaria->cnpj}'  ";
    $sWhere .= " and k85_tipoinclusao = 1)                         ";
    $sSqlVerificaDataConta = $clextrato->sql_query_file(null, "*", null, $sWhere);
    $rsVerificaDataConta = $clextrato->sql_record($sSqlVerificaDataConta);
    $iNumRowsVerificaDataConta = $clextrato->numrows;

    /*
     * Banco ITAU poderá importar mais de um arquivo na mesma data.
     */
    $iCodigoExtrato = null;
    if ($iNumRowsVerificaDataConta > 0) {
        $oStdDadosExtrato = db_utils::fieldsMemory($rsVerificaDataConta, 0);
        $iCodigoExtrato = $oStdDadosExtrato->k85_sequencial;
        $clextrato->k85_codbco = $oStdDadosExtrato->k85_codbco;
        $clextrato->k85_dtproc = $oStdDadosExtrato->k85_dtproc;
        $clextrato->k85_dtarq = $oStdDadosExtrato->k85_dtarq;
        $clextrato->k85_convenio = $oStdDadosExtrato->k85_convenio;
        $clextrato->k85_seqarq = $oStdDadosExtrato->k85_seqarq;
        $clextrato->k85_nomearq = $oStdDadosExtrato->k85_nomearq;
        $clextrato->k85_conteudo = $oStdDadosExtrato->k85_conteudo;
        $clextrato->k85_tipoinclusao = $oStdDadosExtrato->k85_tipoinclusao;
        $clextrato->k85_cnpj = $oStdDadosExtrato->k85_cnpj;

        if ($iGetCodbco != 341) {
            db_msgbox("Já existe extrato importado para essa data!");
            fechaJanela();
            exit;
        }
    }

    $iTotalLinhasExcecoes = $clextratocnab240->getTotalLinhas();
    $aHistoricosNaoEcontrados = array();
    $clbancoshistmovexcecao = db_utils::getDao("bancoshistmovexcecao");
    $rsHistoricosNaoEncontrados = db_query(
        $clbancoshistmovexcecao->sql_query(null, " distinct k66_historico, k66_codbco")
    );
    $aDadosHistoricosNaoEncontrdos = db_utils::getCollectionByRecord($rsHistoricosNaoEncontrados);

    $rsHistoricosBancos = db_query($clbancoshistmov->sql_query_file(null, "k66_historico, k66_codbco"));

    if ($rsHistoricosBancos && pg_num_rows($rsHistoricosBancos) > 0) {
        $aDadosHistoricos = db_utils::getCollectionByRecord($rsHistoricosBancos);

        for ($iExcecoes = 0; $iExcecoes < $iTotalLinhasExcecoes; $iExcecoes++) {
            if ($clextratocnab240->isDetalhe($iExcecoes) && $clextratocnab240->isValido($iExcecoes)) {
                $iBanco = (int)$clextratocnab240->getCodbco();
                $iBancoHistmov = (int)$clextratocnab240->getBancoHistmov($iExcecoes);
                $sBancoHistmovDescr = $clextratocnab240->getBancoHistmovDescr($iExcecoes);
                $lEncontrouHistorico = false;

                foreach ($aDadosHistoricos as $oDadosHistorico) {
                    if ($oDadosHistorico->k66_historico == $iBancoHistmov && $oDadosHistorico->k66_codbco == $iBanco) {
                        $lEncontrouHistorico = true;
                    }
                }

                if (!$lEncontrouHistorico) {
                    $aHistoricosNaoEcontrados[$iBancoHistmov] = $sBancoHistmovDescr;
                }
            }
        }

        if (count($aHistoricosNaoEcontrados) > 0) {
            relatorioInconsistenciasHistoricos($aHistoricosNaoEcontrados, "", "", "");
            fechaJanela();
            exit;
        }
    }

    db_inicio_transacao();

    $totalLinhas = $clextratocnab240->getTotalLinhas();

    $retorno = "";
    $intcontador = 0;
    $incluiuLinha = false;
    $aContasNaoEncontradas = array();
    $aContasNaoProcessadas = array();

    db_criatermometro('termometro');

    for ($i = 0; $i < $totalLinhas; $i++) {
        db_atutermometro($i, $totalLinhas, 'termometro');

        if (!$clextratocnab240->isHeaderArquivo($i) && $i == 0) {
            db_msgbox("A primeira linha do arquivo não é um header de arquivo válido !");
            $sqlerro = true;
        }

        /* (no caso de ser um arquivo com varios header de arquivo) verifica se ja foi incluido algum para mesma data e banco
            para nao incluir denovo e sim gerar os movimentos do extrato todos ligados ao extrato que foi incluido */
        $sGetDtarq = $clextratocnab240->getDtarq();
        $iGetCodbco = $clextratocnab240->getCodbco();

        $sWhere = " k85_dtarq      = '{$sGetDtarq}' ";
        $sWhere .= " and k85_codbco = {$iGetCodbco}  ";
        $sSqlVerificaDataContaExistente = $clextrato->sql_query_file(null, "k85_sequencial", null, $sWhere);
        $rsVerificaDataContaExistente = $clextrato->sql_record($sSqlVerificaDataContaExistente);
        $iNumRowsVerificaDataContaExistente = $clextrato->numrows;

        if (($iNumRowsVerificaDataContaExistente > 0 && !$lPrimeiro) || $iGetCodbco == 341) {
            $oExtrato = db_utils::fieldsMemory($rsVerificaDataContaExistente, 0);
            $clextrato->k85_sequencial = $oExtrato->k85_sequencial;

            $erromsg = "Processamento concluido com sucesso!";
        }

        if ($clextratocnab240->isHeaderArquivo($i) && $clextratocnab240->isValido($i) && $iNumRowsVerificaDataConta == 0 && $lPrimeiro) {
            /* inclusão do extrato */

            $clextrato->k85_sequencial = $iCodigoExtrato;
            if (empty($iCodigoExtrato)) {
                $clextrato->k85_codbco = $clextratocnab240->getCodbco();
                $clextrato->k85_dtproc = date('Y-m-d', db_getsession('DB_datausu'));
                $clextrato->k85_dtarq = $clextratocnab240->getDtarq();
                $clextrato->k85_convenio = $clextratocnab240->getConvenio();
                $clextrato->k85_seqarq = $clextratocnab240->getSeqarq();
                $clextrato->k85_nomearq = $clextratocnab240->getNomeArquivo();
                $clextrato->k85_conteudo = $clextratocnab240->getArquivo();
                $clextrato->k85_tipoinclusao = 1;
                $clextrato->k85_cnpj = $oContaBancaria->cnpj;
                $clextrato->incluir(null);
            }

            $lPrimeiro = false;

            $erromsg = $clextrato->erro_msg;
            if ($clextrato->erro_status == 0) {
                $sqlerro = true;
                $erromsg = "Extrato - " . $clextrato->erro_msg;
                break;
            }
        } else {
            if ($clextratocnab240->isHeaderLote($i) && $clextratocnab240->isTrailerLote($i + 1)) {
                continue;
            } else {
                if ($clextratocnab240->isHeaderLote($i) && $clextratocnab240->isValido($i)) {
                    if (!$clextratocnab240->isExtrato($i)) {
                        continue;
                    }

                    $clextratolinha->k86_lote = $clextratocnab240->getLote($i); // lote
                    $clextratolinha->k86_loteseq = $clextratocnab240->getLoteseq($i); // sequencial do lote

                    if ($clextratocnab240->getConta($i) != false) {
                        $clextratolinha->k86_contabancaria = $clextratocnab240->getConta($i); // numero da conta corrente
                        $contaatual = $clextratocnab240->getConta($i);
                    } else {
                        $intcontador++;

                        $aPesquisaConta = array(
                          "linha"     => $i,
                          "agencia"   => $clextratocnab240->getAgencia($i),
                          "dvagencia" => $clextratocnab240->getDvAgencia($i),
                          "conta"     => $clextratocnab240->getCc($i),
                          "dvconta"   => $clextratocnab240->getDvCc($i)
                        );

                        if (!in_array($aPesquisaConta, $aContasNaoEncontradas)) {
                            $aContasNaoEncontradas[] = $aPesquisaConta;
                        }

                        continue;
                    }

                    $incluiuLinha = false;
                } else {
                    if ($clextratocnab240->isDetalhe($i) && $clextratocnab240->isValido($i) && $contaatual != '') {
                        $iBancoHistmov = (int)$clextratocnab240->getBancoHistmov($i);
                        $lEncontrouHistorico = false;

                        foreach ($aDadosHistoricosNaoEncontrdos as $oDadosHistoricos) {
                            if ($oDadosHistoricos->k66_historico == $iBancoHistmov && $oDadosHistoricos->k66_codbco == (int)$iGetCodbco) {
                                $lEncontrouHistorico = true;
                                break;
                            }
                        }

                        if ($lEncontrouHistorico !== false) {
                            continue;
                        }

                        $clextratolinha->k86_extrato = $clextrato->k85_sequencial;
                        $sqlCodmovimento = $clbancoshistmov->sql_query_file(
                            null,
                            "k66_sequencial",
                            null,
                            "    k66_historico = " . ( int )$clextratocnab240->getBancoHistmov($i) . " and k66_codbco    = " . ( int )$clextrato->k85_codbco
                        );

                        $rsCodmovimento = $clbancoshistmov->sql_record($sqlCodmovimento);

                        if ($clbancoshistmov->numrows > 0) {
                            db_fieldsmemory($rsCodmovimento, 0);
                        } else {
                            db_msgbox("Movimento bancário não encontrado no cadastro. Movimento : " . (int)$clextratocnab240->getBancoHistmov($i) . "-" . (int)$clextratocnab240->getHistLancamento($i));
                            fechaJanela();
                            exit;
                        }

                        if ($clextratocnab240->getConta($i) != false) {
                            $clextratolinha->k86_contabancaria = $clextratocnab240->getConta($i); // numero da conta corrente
                            $contaatual = $clextratocnab240->getConta($i);
                        } else {
                            $intcontador++;
                            $aPesquisaConta = array(
                              "linha"     => $i,
                              "agencia"   => $clextratocnab240->getAgencia($i),
                              "dvagencia" => $clextratocnab240->getDvAgencia($i),
                              "conta"     => $clextratocnab240->getCc($i),
                              "dvconta"   => $clextratocnab240->getDvCc($i)
                            );

                            $sHash = $i . $clextratocnab240->getAgencia($i) . $clextratocnab240->getDvAgencia($i) . $clextratocnab240->getCc($i) . $clextratocnab240->getDvCc($i);

                            if (!in_array($sHash, $aContasNaoProcessadas)) {
                                $aContasNaoEncontradas[] = $aPesquisaConta;
                                $aContasNaoProcessadas[] = $sHash;
                            }

                            continue;
                        }

                        $clextratolinha->k86_bancohistmov = $k66_sequencial; //addslashes($clextratocnab240->getBancoHistmov($i)); // linha
                        $clextratolinha->k86_data = $clextratocnab240->getDataLancamento($i); // linha
                        $clextratolinha->k86_valor = $clextratocnab240->getValorLancamento($i); // linha
                        $clextratolinha->k86_tipo = $clextratocnab240->getTipoLancamento($i); // linha
                        $clextratolinha->k86_historico = $clextratocnab240->getHistLancamento($i); // linha
                        $clextratolinha->k86_documento = $clextratocnab240->getDocumentoLancamento($i); // linha
                        $clextratolinha->incluir(null);

                        if ($clextratolinha->erro_status == 0) {
                            $sqlerro = true;
                            $erromsg = "Extratolinha - " . $clextratolinha->erro_msg;
                            break;
                        }

                        if ($clextratocnab240->getTipoLancamento($i) == 'C') {
                            @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valorcredito'] += $clextratocnab240->getValorLancamento($i);
                            @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valordebito'] += 0;
                            @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_saldofinal'] += $clextratocnab240->getValorLancamento($i);
                        } else {
                            if ($clextratocnab240->getTipoLancamento($i) == 'D') {
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valorcredito'] += 0;
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valordebito'] += $clextratocnab240->getValorLancamento($i);
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_saldofinal'] -= $clextratocnab240->getValorLancamento($i);
                            } else {
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valorcredito'] += 0;
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_valordebito'] += 0;
                                @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_saldofinal'] += $clextratocnab240->getValorLancamento($i);
                            }
                        }

                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_limite'] += 0;
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_extrato'] = $clextrato->k85_sequencial;
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_dtsaldofinal'] = $clextratocnab240->getDataSaldo($i);
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_posicao'] = 'F';
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_situacao'] = 'D';
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_saldobloqueado'] += 0;
                        @$aExtratoSaldoArray[$clextratolinha->k86_contabancaria][$clextratolinha->k86_data]['k97_qtdregistros']++;

                        $incluiuLinha = true;
                    } else {
                        continue;
                    }
                }
            }
        }
    }

    if ((!isset($aExtratoSaldoArray) || count($aExtratoSaldoArray) == 0)
      && count($aContasNaoEncontradas) == 0 && !$sqlerro
    ) {
        db_msgbox("Arquivo inválido, não foi encontrado nenhuma linha com formato válido, processamento cancelado!");
        fechaJanela();
        exit;
    }

    if (!$sqlerro) {
        foreach ($aExtratoSaldoArray as $iConta => $aLinhas) {
            foreach ($aLinhas as $sData => $aColunas) {
                $sSqlSaldoAnt  = "select coalesce(k97_saldofinal,0) as k97_saldofinal ";
                $sSqlSaldoAnt .= "           from extratosaldo ";
                $sSqlSaldoAnt .= "          where k97_contabancaria = {$iConta} ";
                $sSqlSaldoAnt .= "            and k97_dtsaldofinal < '{$sData}' ";
                $sSqlSaldoAnt .= "          order by k97_dtsaldofinal desc limit 1 ";
                $rsSaldoAnt = db_query($sSqlSaldoAnt);
                $oSaldoAnterior = db_utils::fieldsMemory($rsSaldoAnt, 0);

                $clextratosaldo->k97_contabancaria = $iConta;
                $clextratosaldo->k97_dtsaldofinal = $sData;
                $clextratosaldo->k97_extrato = $aColunas['k97_extrato'];
                $clextratosaldo->k97_valorcredito = $aColunas['k97_valorcredito'];
                $clextratosaldo->k97_valordebito = $aColunas['k97_valordebito'];
                $clextratosaldo->k97_qtdregistros = $aColunas['k97_qtdregistros'];
                $clextratosaldo->k97_posicao = $aColunas['k97_posicao'];
                $clextratosaldo->k97_situacao = $aColunas['k97_situacao'];
                $clextratosaldo->k97_saldobloqueado = $aColunas['k97_saldobloqueado'];
                $clextratosaldo->k97_saldofinal = ($oSaldoAnterior->k97_saldofinal + $aColunas['k97_saldofinal']);
                $clextratosaldo->k97_limite = $aColunas['k97_limite'];
                $clextratosaldo->incluir(null);

                if ($clextratosaldo->erro_status == 0) {
                    $sqlerro = true;
                    $erromsg = "extrato saldo - " . $clextratosaldo->erro_msg;
                    break;
                }

                /*
                 * Recriando saldo do dia e dos dias posteriores da conta
                 */
                $clextratosaldo->recriarSaldoGeral($iConta, $sData);

                if ($clextratosaldo->erro_status == 0) {
                    $sqlerro = true;
                    $erromsg = "Recriando saldo - " . $clextratosaldo->erro_msg;
                    break;
                }
            }
        }
    }

    if (count($aContasNaoEncontradas) > 0) {
        $erromsg = "Processo não concluido. A carga do arquivo encontrou inconsistências no cadastro das contas. \\nVerifique relatorio de erros e corrija os dados das contas acessando o menu Contabilidade > Cadastros > Plano de Contas > Alteração.";
        $sqlerro = true;
    }

    db_fim_transacao($sqlerro);
    db_msgbox($erromsg);

    if ($sqlerro == true && count($aContasNaoEncontradas) > 0) {
        db_putsession("aContas", $aContasNaoEncontradas);
        echo "<script> ";
        echo "  var dDataArq  = '{$clextratocnab240->dtarq}'; ";
        echo "  var sNomeArq  = '{$arqname}'; ";
        echo "  var iCodBanco = '{$clextratocnab240->codbco}'; ";
        echo "  jan = window.open('cai4_relinconsextrato002.php?dataarq='+dDataArq+'&nomearq='+sNomeArq+'&codbanco='+iCodBanco,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '); ";
        echo "  jan.moveTo(0,0); ";
        echo "</script> ";
    }

    fechaJanela();
}

function fechaJanela()
{

    echo "<script> parent.db_iframe_carga.hide(); </script>";
    exit();
}

function relatorioInconsistenciasHistoricos(array $aHistoricosNaoEcontrados, $sDtArquivo, $sArqName, $codbco)
{

    db_putsession("aHistoricosNaoEncontrados", $aHistoricosNaoEcontrados);
    echo "<script> ";
    echo "  var dDataArq  = '{$sDtArquivo}'; ";
    echo "  var sNomeArq  = '{$sArqName}'; ";
    echo "  var iCodBanco = '{$codbco}'; ";
    echo "  jan = window.open('cai4_relinconshistoricoextrato002.php?dataarq='+dDataArq+'&nomearq='+sNomeArq+'&codbanco='+iCodBanco,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '); ";
    echo "  jan.moveTo(0,0); ";
    echo "</script> ";
}
