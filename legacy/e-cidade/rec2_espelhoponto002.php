<?php
/**
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
include_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/pdf.php"));

use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;

$oParametros = \db_utils::postMemory(array_merge($_GET, $_POST));
$aMatriculas = explode(',', $oParametros->aMatriculas);
$iCodigoSelecao = !empty($oParametros->iCodigoSelecao) ? $oParametros->iCodigoSelecao : null;
$lMostraObservacoes = $oParametros->lMostraObservacoes ? $oParametros->lMostraObservacoes : false;
$lEmiteTodosAfastamentos = !empty($oParametros->iEmiteTodosAfastamentos) && $oParametros->iEmiteTodosAfastamentos == 1;
$limiteMatriculasInconsistentes = 50;

if (empty($oParametros->sDataInicio)) {
    throw new ParameterException("Informe a data início.");
}

if (empty($oParametros->sDataFim)) {
    throw new ParameterException("Informe a data fim.");
}

if (empty($aMatriculas)) {
    if (empty($iCodigoSelecao)) {
        throw new ParameterException("Informe uma seleção ou uma ou mais matrículas para emissão do espelho ponto.");
    }
}

if (!empty($iCodigoSelecao)) {
    $aMatriculas = array_keys(\ServidorRepository::getServidoresBySelecao(
        DBPessoal::getAnoFolha(),
        DBPessoal::getMesFolha(),
        $iCodigoSelecao
    ));
}

try {
    $oPeriodoRepository = new PeriodoRepository(null, null, true);
    $aPeriodos = $oPeriodoRepository->getPeriodosEntreDatas(new DBDate($oParametros->sDataInicio),
      new DBDate($oParametros->sDataFim));

    foreach ($aPeriodos as $oPeriodo) {
        $aDatasEfetividade = \DBDate::getDatasNoIntervalo($oPeriodo->getDataInicio(), $oPeriodo->getDataFim());
        $aDatasProcessar = array();
        $aDatasProcessarJustificativas = array();

        foreach (\DBDate::getDatasNoIntervalo($oPeriodo->getDataInicio(), $oPeriodo->getDataFim()) as $oDataProcessar) {
            $aDatasProcessar[] = $oDataProcessar->getDate();
            $aDatasProcessarJustificativas[] = (object)array('data' => $oDataProcessar->getDate());
        }
    }

    $aServidores = array();

    if (empty($aMatriculas)) {
        throw new BusinessException("Não há servidores para esta selecão.");
    }

    $matriculasInconsistentes = array();
    foreach ($aMatriculas as $iMatricula) {
        $sTipo = '';

        /**
         * Cria marcações caso não exista e vincula justificativas e afastamentos
         */
        try {
            ProcessamentoPontoEletronico::criarMarcacoesNasDatas($iMatricula, $aDatasProcessarJustificativas);
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '3': // Lotação não configurada
                    $sTipo = 'lotacao';
                    $matriculasInconsistentes['mensagens'][] = $e->getMessage();
                    break;
                case '4': // Não possuí escala configurada
                    $sTipo = 'escala';
                    break;
                case '5': // Não possuí escala na data
                    $sTipo = 'configuracaoescala';
                    break;
            }

            $matriculasInconsistentes[$sTipo][] = $iMatricula;
        }

        if (isset($matriculasInconsistentes[$sTipo]) && count($matriculasInconsistentes[$sTipo]) > $limiteMatriculasInconsistentes) {
            break;
        }
    }

    if (!empty($matriculasInconsistentes['escala']) || !empty($matriculasInconsistentes['configuracaoescala']) || !empty($matriculasInconsistentes['lotacao'])) {
        $mensagemMatriculasInconsistentes = '';

        if (!empty($matriculasInconsistentes['escala'])) {
            $mensagemMatriculasInconsistentes = "Não há escala configurada para a(s) seguinte(s) matrícula(s): ";

            if (count($matriculasInconsistentes['escala']) > 1) {
                if (count($matriculasInconsistentes['escala']) > $limiteMatriculasInconsistentes) {
                    $mensagemMatriculasInconsistentes = "Verifique se há escala configurada para as matrículas/seleção informada(s)";
                } else {
                    $mensagemMatriculasInconsistentes .= implode(', ', $matriculasInconsistentes['escala']);
                }
            } else {
                $mensagemMatriculasInconsistentes .= $matriculasInconsistentes['escala'][0];
            }

            $mensagemMatriculasInconsistentes .= ".\nPara configurar acesse: RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários.\n\n";
        }

        if (!empty($matriculasInconsistentes['configuracaoescala'])) {
            if (count($matriculasInconsistentes['configuracaoescala']) > $limiteMatriculasInconsistentes) {
                $mensagemMatriculasInconsistentes .= "Verifique se há escala configurada para as matrículas/seleção informada(s) neste período";
            } else {
                $mensagemMatriculasInconsistentes .= "Não há escala configurada no período para a(s) seguinte(s) matrícula(s): ";
            }

            if (count($matriculasInconsistentes['configuracaoescala']) > 1) {
                $mensagemMatriculasInconsistentes .= implode(', ', $matriculasInconsistentes['configuracaoescala']);
            } else {
                $mensagemMatriculasInconsistentes .= $matriculasInconsistentes['configuracaoescala'][0];
            }

            $mensagemMatriculasInconsistentes .= ".\nPara configurar acesse: RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários. \n\n";
        }

        if (!empty($matriculasInconsistentes['lotacao'])) {
            if (count($matriculasInconsistentes['lotacao']) > $limiteMatriculasInconsistentes) {
                $mensagemMatriculasInconsistentes .= "Verifique se a(s) lotação(ões) das matrículas/seleção informada contém configuração do ponto eletrônico";
            } else {
                $mensagemMatriculasInconsistentes .= "A(s) lotação(ões) da(s) seguinte(s) matrícula(s) não contém configuração do ponto eletrônico: ";
            }

            if (count($matriculasInconsistentes['lotacao']) > 1) {
                $mensagemMatriculasInconsistentes .= implode(', ', $matriculasInconsistentes['lotacao']);
            } else {
                $mensagemMatriculasInconsistentes .= $matriculasInconsistentes['lotacao'][0];
            }

            $mensagemMatriculasInconsistentes .= ".\nPara configurar acesse: RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação. \n\n";
        }

        throw new Exception($mensagemMatriculasInconsistentes);
    }

    foreach ($aPeriodos as $oPeriodo) {

        /**
         * Processa cálculo de horas extras e faltas
         */
        ProcessamentoPontoEletronico::processarMatriculas($aMatriculas, $oPeriodo, $aDatasProcessar);
    }

    foreach ($aMatriculas as $iMatricula) {
        $oServidor = \ServidorRepository::getInstanciaByCodigo($iMatricula);
        $oEspelho = new EspelhoPonto($oServidor, $aPeriodos, InstituicaoRepository::getInstituicaoSessao());
        $oEspelho->calcularTotalizadores();
        $aServidores[] = $oEspelho->retornaDados();
    }

    $sDataInicio = implode('/', array_reverse(explode('-', $oParametros->sDataInicio)));
    $sDataFim = implode('/', array_reverse(explode('-', $oParametros->sDataFim)));

    $head2 = "ESPELHO PONTO";
    $head3 = "Período: {$sDataInicio} - {$sDataFim}";
    escreverPDF($aServidores, $lMostraObservacoes, $lEmiteTodosAfastamentos);
} catch (Exception $e) {
    db_redireciona('db_erros.php?db_erro=' . urlencode($e->getMessage()));
}

function escreverPDF($aServidores, $lMostraObservacoes, $lEmiteTodosAfastamentos)
{

    global $head5, $head6;

    $pdf = new \Pdf();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetFillColor(230);
    $pdf->SetAutoPageBreak(false);

    $oLabel = new \stdClass;
    $oLabel->data_dia = "Data";
    $oLabel->jornada = "Código";
    $oLabel->entrada1 = "Entrada 1";
    $oLabel->saida1 = "Saída 1";
    $oLabel->entrada2 = "Entrada 2";
    $oLabel->saida2 = "Saída 2";
    $oLabel->entrada3 = "Entrada 3";
    $oLabel->saida3 = "Saída 3";
    $oLabel->normais = "Trabalho";
    $oLabel->faltas = "Faltas";
    $oLabel->ext50 = "Ext 50";
    $oLabel->ext75 = "Ext 75";
    $oLabel->ext100 = "Ext 100";
    $oLabel->adicional = "Adic Not.";

    foreach ($aServidores as $servidor) {
        $lQuebraPaginaObservacoes = false;
        $iLimiteObservacoesHorarios = 6;
        $iLimiteCarateresObservacoes = 120;
        $dadosServidor = $servidor['dados'];

        $head5 = "Servidor: {$dadosServidor->nome}";
        $head6 = "Matricula: {$dadosServidor->matricula} - Admissão: {$dadosServidor->admissao}";

        $pdf->AddPage();
        $pdf->setFontSize(8);

        $sHorasJornada = '';
        $aJornadasServidor = $servidor['aHorasJornada'];
        $iLimiteObservacoesHorarios -= count($aJornadasServidor);
        $contadorHorasJornada = 0;

        $pdf->Cell(26, 5, 'Horários:', 'TL', 0, "R");

        $lMostrarLegendaAfastamentos = false;
        foreach ($aJornadasServidor as $iCodigo => $jornada) {
            if (!$jornada->diaTrabalhado) {
                continue;
            }

            $sHorasJornada = $iCodigo . ' - ';

            foreach ($jornada->horas as $oHora) {
                $sHorasJornada .= ' ' . $oHora->oHora->format('H:i');
            }

            if ($contadorHorasJornada > 0) {
                $pdf->Cell(26, 5, '', 'TL', 0, "R");
            }

            $pdf->Cell(169, 5, $sHorasJornada, 'RT', 1, "L");

            $contadorHorasJornada++;
        }

        escreverGrade($pdf, $oLabel, true);

        $aDatasServidor = $servidor['datas'];

        foreach ($aDatasServidor as $indDatas => $oData) {
            if ((!!preg_match('/^\d{1,2}\/(\d{1,2})\/\d{1,4}$/', $oData->data, $aMes)) !== true) {
                throw new BusinessException("Não foi possível identificar o mês.");
            }
            $mesAtual = $aMes[1];

            $oData->jornada = $oData->oJornada->codigo;
            $oData->entrada1 = $oData->oJornada->tipo_descricao;
            $oData->saida1 = $oData->oJornada->tipo_descricao;
            $oData->entrada2 = $oData->oJornada->tipo_descricao;
            $oData->saida2 = $oData->oJornada->tipo_descricao;
            $oData->entrada3 = $oData->oJornada->tipo_descricao;
            $oData->saida3 = $oData->oJornada->tipo_descricao;

            if (!$oData->oJornada->dsr_folga || $oData->lTemMarcacoes) {
                $oEntrada1 = $oData->aMarcacoes[0]->oEntrada;
                $oSaida1 = $oData->aMarcacoes[0]->oSaida;
                $oEntrada2 = $oData->aMarcacoes[1]->oEntrada;
                $oSaida2 = $oData->aMarcacoes[1]->oSaida;
                $oEntrada3 = $oData->aMarcacoes[2]->oEntrada;
                $oSaida3 = $oData->aMarcacoes[2]->oSaida;

                $oData->entrada1 = montarMarcacao($oEntrada1, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);
                $oData->saida1 = montarMarcacao($oSaida1, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);
                $oData->entrada2 = montarMarcacao($oEntrada2, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);
                $oData->saida2 = montarMarcacao($oSaida2, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);
                $oData->entrada3 = montarMarcacao($oEntrada3, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);
                $oData->saida3 = montarMarcacao($oSaida3, $lEmiteTodosAfastamentos, $oData->afastamento,
                  $lMostrarLegendaAfastamentos);

                for ($iIndMarcacoes = 0; $iIndMarcacoes < count($oData->aMarcacoes); $iIndMarcacoes++) {
                    $oMarcacao = $oData->aMarcacoes[$iIndMarcacoes];

                    if ($oMarcacao->oEntrada->manual) {
                        switch ($iIndMarcacoes) {
                            case 0:
                                $oData->entrada1 .= ' *';
                                break;
                            case 1:
                                $oData->entrada2 .= ' *';
                                break;
                            case 2:
                                $oData->entrada3 .= ' *';
                                break;
                        }
                    }

                    if ($oMarcacao->oSaida->manual) {
                        switch ($iIndMarcacoes) {
                            case 0:
                                $oData->saida1 .= ' *';
                                break;
                            case 1:
                                $oData->saida2 .= ' *';
                                break;
                            case 2:
                                $oData->saida3 .= ' *';
                                break;
                        }
                    }
                }
            }

            if ($oData->lFeriado) {
                $oData->entrada1 = 'FERIADO';
                $oData->saida1 = 'FERIADO';
                $oData->entrada2 = 'FERIADO';
                $oData->saida2 = 'FERIADO';
                $oData->entrada3 = 'FERIADO';
                $oData->saida3 = 'FERIADO';
            }

            if ($indDatas > 0) {
                $sTotal = empty($servidor['observacoes']) ? 42 : 31;

                if (!($indDatas % $sTotal)) {
                    $pdf->AddPage();
                }
            }

            escreverGrade($pdf, $oData);
        }

        $pdf->Cell(117, 5, 'Totais:', 0, 0, "R");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasNormais']), 0, 0, "C");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasFaltas']), 0, 0, "C");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasExt50']), 0, 0, "C");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasExt75']), 0, 0, "C");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasExt100']), 0, 0, "C");
        $pdf->Cell(13, 5, somarHora($servidor['nTotalHorasAdicional']), 0, 1, "C");

        $pdf->setFontSize(18);
        $pdf->Cell(3, 7, '*', 0, 0, "C");
        $pdf->setFontSize(8);
        $pdf->Cell(190, 5, 'Alterado manualmente', 0, 1, "L");
        if ($lMostrarLegendaAfastamentos) {
            $pdf->setFontSize(10);
            $pdf->Cell(3, 5, '+', 0, 0, "C");
            $pdf->setFontSize(8);
            $pdf->Cell(190, 5, 'Existe mais de uma ocorrência de Afastamento/Justificativas.', 0, 1, "L");
        }
        if ($lMostraObservacoes) {
            $aObservacoesServidor = $servidor['observacoes'];

            if (count($aObservacoesServidor) > 0) {
                $pdf->Cell(26, 5, 'Justificativas:', 0, 0, "L");
                $pdf->Cell(169, 5, '', 0, 1, "R");
            }

            for ($iObsServidor = 0; $iObsServidor < count($aObservacoesServidor); $iObsServidor++) {
                $sObservacao = $aObservacoesServidor[$iObsServidor];

                if ($iLimiteObservacoesHorarios <= 0 || $iObsServidor >= $iLimiteObservacoesHorarios) {
                    $lQuebraPaginaObservacoes = true;
                    break;
                }

                if (strlen($sObservacao) > $iLimiteCarateresObservacoes) {
                    $sObservacao = substr($sObservacao, 0, $iLimiteCarateresObservacoes);
                    $sObservacao .= '...';
                }

                $pdf->Cell(195, 5, "  {$sObservacao}", 0, 1, "L");
            }

            $aObservacoesServidor = array_slice($aObservacoesServidor, $iObsServidor);
        }

        escreverAssinaturas($pdf, $dadosServidor->nome, $dadosServidor->supervisor);

        if ($lMostraObservacoes) {
            if ($lQuebraPaginaObservacoes) {
                $pdf->AddPage();
            }

            if (count($aObservacoesServidor) > 0) {
                $pdf->Cell(26, 5, 'Justificativas:', 0, 0, "L");
                $pdf->Cell(169, 5, '', 0, 1, "R");
            }

            foreach ($aObservacoesServidor as $sObservacao) {
                if (strlen($sObservacao) > $iLimiteCarateresObservacoes) {
                    $sObservacao = substr($sObservacao, 0, $iLimiteCarateresObservacoes);
                    $sObservacao .= '...';
                }

                $pdf->Cell(195, 5, "  {$sObservacao}", 0, 1, "L");
            }

            if (count($aObservacoesServidor) > 0) {
                escreverAssinaturas($pdf, $dadosServidor->nome, $dadosServidor->supervisor);
            }
        }
    }

    $pdf->Output();
}

function escreverGrade(PDF $pdf, $dados, $lHeader = false)
{

    if ($lHeader) {
        $pdf->Bold();
        $pdf->SetFontSize(6.5);
    }

    $colunas = (array)$dados;
    $iMaximoDeLinhas = 5;
    $aColunasNaoContar = array(
      'afastamento',
      'oJornada',
      'data_dia',
      'aMarcacoes',
      'oPeriodoEfetividade',
      'data',
      'possuiEvento',
      'dadosEvento'
    );
    foreach ($colunas as $campo => $coluna) {
        if (in_array($campo, $aColunasNaoContar)) {
            continue;
        }

        $iAlturaLinha = $pdf->NbLines(13, trim($coluna)) * 5;
        if ($iAlturaLinha > $iMaximoDeLinhas) {
            $iMaximoDeLinhas = $iAlturaLinha;
        }
    }
    $alturaAtual = $pdf->getY();
    $pdf->Multicell(26, 5, $dados->data_dia, 'TLR', "L");
    $pdf->SetXY(36, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->jornada, 'TLR', "C");
    $pdf->SetXY(49, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->entrada1, 'TLR', "C");
    $pdf->SetXY(62, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->saida1, 'TLR', "C");
    $pdf->SetXY(75, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->entrada2, 'TLR', "C");
    $pdf->SetXY(88, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->saida2, 'TLR', "C");
    $pdf->SetXY(101, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->entrada3, 'TLR', "C");
    $pdf->SetXY(114, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->saida3, 'TLR', "C");
    $pdf->SetXY(127, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->normais, 'TLR', "C");
    $pdf->SetXY(140, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->faltas, 'TLR', "C");
    $pdf->SetXY(153, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->ext50, 'TLR', "C");
    $pdf->SetXY(166, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->ext75, 'TLR', "C");
    $pdf->SetXY(179, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->ext100, 'TLR', "C");
    $pdf->SetXY(192, $alturaAtual);
    $pdf->Multicell(13, 5, $dados->adicional, 'TLR', "C");
    if ($iMaximoDeLinhas > 5) {
        $pdf->SetY($alturaAtual + $iMaximoDeLinhas);
    }
    /**
     * fechamos as linhas das celulas*
     */
    $posicaoInicioLinha = 10;

    foreach (range(1, 15) as $coluna) {
        $tamanho = 13;
        if ($coluna == 1) {
            $tamanho = 26;
        }
        $pdf->line($posicaoInicioLinha, $alturaAtual, $posicaoInicioLinha, $pdf->GetY());
        $posicaoInicioLinha += $tamanho;
    }

    $pdf->line(10, $pdf->GetY(), 205, $pdf->GetY());
    if ($lHeader) {
        $pdf->EndBold();
        $pdf->SetFontSize(8);
    }
}

function somarHora($horarios)
{

    $nTotalMinutos = 0;
    foreach ($horarios as $horario) {
        if (is_null($horario) || $horario == '') {
            continue;
        }

        list($iHora, $iMinute) = explode(':', $horario);
        $nTotalMinutos += $iHora * 60;
        $nTotalMinutos += $iMinute;
    }

    $iHoras = floor($nTotalMinutos / 60);
    $nTotalMinutos -= $iHoras * 60;

    return sprintf('%02d:%02d', $iHoras, $nTotalMinutos);
}

function escreverAssinaturas($pdf, $nomeServidor, $nomeSupervisor)
{

    $pdf->Cell(65, 18, '', 'B', 0, "C");
    $pdf->Cell(65, 18, '', 0, 0, "C");
    $pdf->Cell(65, 18, '', 'B', 1, "C");
    $pdf->Cell(65, 7, $nomeServidor, 0, 0, "C");
    $pdf->Cell(65, 7, '', 0, 0, "C");
    $pdf->Cell(65, 7, $nomeSupervisor, 0, 1, "C");
}

/**
 * Monta a string da Marcacao
 * @param $marcacao
 * @param $mostrarAfastamento
 * @return string
 */
function montarMarcacao($marcacao, $mostrarAfastamento, $afastamento, &$mostrarLegenda)
{

    $aDados = array();
    $string = '';
    $iTotalAfastamento = 0;
    if ($afastamento->isAfastado) {
        $aDados[] = $afastamento->abreviacao;
        $iTotalAfastamento++;
    }
    if (!is_null($marcacao->oJustificativa)) {
        $aDados[] = $marcacao->oJustificativa->abreviacao;
        $iTotalAfastamento++;
    }
    $aDados[] = $marcacao->hora;
    $string = $aDados[0];
    if ($mostrarAfastamento) {
        $string = implode("\n", $aDados);
    }
    if ($iTotalAfastamento > 1 && !$mostrarAfastamento) {
        $string .= "+";
        $mostrarLegenda = true;
    }

    return $string;
}
