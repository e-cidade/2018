<?php
/**
 *  E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                              www.dbseller.com.br
 *                              e-cidade@dbseller.com.br
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
require_once(modification('fpdf151/PDFDocument.php'));

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\Gestor;
use ECidade\Patrimonial\Protocolo\Repositorio\ProcessoRepositorio;


$oGet = db_utils::postMemory($_GET);
$oProcessoRepositorio = new ProcessoRepositorio();
$oDataAtual = new DBDate(date('Y-m-d'));

/**
 * @var int
 */
const TODOS_DEPARTAMENTOS = 0;
/**
 * @var int
 */
const TODOS_DEPARTAMENTOS_SELECIONADOS = 1;
/**
 * @var int
 */
const MENOS_DEPARTAMENTOS_SELECIONADOS = 2;

try {
    $aDepartamentosSelecionados = explode(',', $oGet->aDepartamentosSelecionados);

    switch ($oGet->tipoEmissao) {
        case TODOS_DEPARTAMENTOS:

            $aDepartamentosSelecionados = departamentosPorInstituicao();
            break;

        case MENOS_DEPARTAMENTOS_SELECIONADOS:

            $oUsuario = new Gestor(db_getsession('DB_id_usuario'));

            if ($oUsuario->ehGestorPrincipal()) {
                $aDepartamentosGestor = departamentosPorInstituicao();
                $aDepartamentosSelecionados = array_diff($aDepartamentosGestor, $aDepartamentosSelecionados);
                break;
            }

            $sWhere = 'p103_db_usuarios = ' . db_getsession('DB_id_usuario') . ' AND ';
            $sWhere .= 'p103_db_depart NOT IN (' . $oGet->aDepartamentosSelecionados . ')';

            $oDaoGestaoDepartamentoProcesso = new cl_gestaodepartamentoprocesso();
            $sSql = $oDaoGestaoDepartamentoProcesso->sql_query_file(null, 'p103_db_depart', null, $sWhere);
            $rsDepartamento = db_query($sSql);

            if (!$rsDepartamento) {
                throw new DBException('Erro ao buscar departamentos da instituição.');
            }

            if (pg_num_rows($rsDepartamento) == 0) {
                throw new BusinessException('Nenhum departamento encontrado para os filtros selecionados.');
            }

            $aDepartamentosSelecionados = db_utils::makeCollectionFromRecord(/**
             * @param $oDados
             * @return mixed
             */
                $rsDepartamento, function ($oDados) {
                return $oDados->p103_db_depart;
            });
            break;
    }

    $oPdf = new PDFDocument();
    $oPdf->Open();
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('Relatório de movimentação de processos');

    $oPdf->AddPage();
    $oPdf->SetFillColor(225);
    $oPdf->SetAutoPageBreak(false);

    $aFiltros = array();

    $aFiltros[] = array('NOW()', '>', 'ultima_data + (SELECT p101_diasprazo FROM mensageriaprocesso LIMIT 1)');

    if ($oGet->dataInicial) {
        $oDataInicial = new DBDate($oGet->dataInicial);
        $aFiltros[] = array('ultima_data', '>=', "'{$oDataInicial->getDate()}'");
    }

    if ($oGet->dataFinal) {
        $oDataFinal = new DBDate($oGet->dataFinal);
        $aFiltros[] = array('ultima_data', '<=', "'{$oDataFinal->getDate()}'");
    }

    $aDepartamentosNaoEncontrados = $oGet->detalhamento
        ? imprimirComDetalhamento($oPdf, $oDataAtual, $aDepartamentosSelecionados, $aFiltros)
        : imprimirSemDetalhamento($oPdf, $aDepartamentosSelecionados, $aFiltros);

    if (count($aDepartamentosSelecionados) == count($aDepartamentosNaoEncontrados)) {
        throw new Exception('Não foi encontrado nenhum processo vencido para os filtros informados.');
    }
} catch (Exception $oErro) {
    db_redireciona("db_erros.php?db_erro={$oErro->getMessage()}");
}

if ($aDepartamentosNaoEncontrados && $oGet->tipoEmissao == TODOS_DEPARTAMENTOS_SELECIONADOS) {
    if (($oPdf->GetY()) > 285) {
        $oPdf->AddPage();
    }

    $oPdf->SetFont('Arial', 'b', 7);
    $oPdf->Cell(192, 4, 'Departamento(s) sem nenhum processo vencido:', 'TB', 1, 'L', 1);

    foreach ($aDepartamentosNaoEncontrados as $oDepartamento) {
        if (($oPdf->GetY()) > 285) {
            $oPdf->AddPage();
        }

        $oPdf->SetFont('Arial', '', 7);
        $oPdf->Cell(192, 4, "{$oDepartamento->getCodigo()} - {$oDepartamento->getNomeDepartamento()}", 'TB', 1, 'L');
    }
}

$oPdf->showPDF();

/**
 * @param PDFDocument $oPdf
 * @param DBDepartamento $oDepartamento
 */
function imprimeCabecalho(PDFDocument $oPdf, DBDepartamento $oDepartamento)
{
    $oPdf->SetFont('Arial', 'b', 7);
    $iCodigoDepartamento = $oDepartamento->getCodigo();

    $sQuery = "
        SELECT ARRAY_TO_JSON(ARRAY_AGG(DISTINCT db_usuarios.nome)) AS responsaveis
        FROM gestaodepartamentoprocesso
        LEFT JOIN db_usuarios ON id_usuario = p103_db_usuarios 
        WHERE p103_db_depart = {$iCodigoDepartamento}
    ";

    $rsResponsaveis = db_query($sQuery);

    if (!$rsResponsaveis) {
        throw new Exception('Não foi possível buscar os resposáveis do departamento {$iCodigoDepartamento }.');
    }

    $sDepartamento = "Departamento: {$oDepartamento->getCodigo()} - {$oDepartamento->getNomeDepartamento()}";
    $oPdf->Cell(192, 4, $sDepartamento, 'TB', 1, 'L', 1);

    $sResponsaveis = db_utils::fieldsMemory($rsResponsaveis, 0)->responsaveis;
    $sResponsaveisDepartamento = 'Responsáveis: ';
    $sResponsaveisDepartamento .= $sResponsaveis ? implode(', ', json_decode($sResponsaveis)) : 'Nenhum';

    $oPdf->MultiCell(192, 4, $sResponsaveisDepartamento, 'TB', 'L', 1);
    $oPdf->Cell(26, 4, 'Número do Processo', 'TBR', 0, 'C');
    $oPdf->Cell(48, 4, 'Usuário', 1, 0, 'C');
    $oPdf->Cell(70, 4, 'Assunto', 1, 0, 'C');
    $oPdf->Cell(30, 4, 'Data de Movimentação', 1, 0, 'C');
    $oPdf->Cell(18, 4, 'Total de Dias', 'TBL', 1, 'C');

    $oPdf->SetFont('Arial', '', 7);
}

/**
 * @return array
 * @throws BusinessException
 * @throws DBException
 */
function departamentosPorInstituicao()
{
    $oDAODepartamento = new cl_db_depart();
    $iInstituicao = db_getsession('DB_instit');
    $sSQL = $oDAODepartamento->sql_query_file(null, 'coddepto', null, "instit = {$iInstituicao}");
    $rsDepartamento = db_query($sSQL);

    if (!$rsDepartamento) {
        throw new DBException('Erro ao buscar departamentos da instituição.');
    }

    $iLinhas = pg_num_rows($rsDepartamento);

    if ($iLinhas == 0) {
        throw new BusinessException('Nenhum departamento encontrado para a instituição.');
    }

    return db_utils::makeCollectionFromRecord($rsDepartamento, function ($oDados) {
        return $oDados->coddepto;
    });
}

/**
 * @param PDFDocument $oPdf
 * @param DBDate $oDataAtual
 * @param $aDepartamentosSelecionados
 * @param $aFiltros
 * @return array
 * @throws DBException
 */
function imprimirComDetalhamento(PDFDocument $oPdf, DBDate $oDataAtual, $aDepartamentosSelecionados, $aFiltros)
{
    $aDepartamentosNaoEncontrados = array();

    ProcessoRepositorio::chunkByDepartamento('ultimas_movimentacoes_processos_vencidos', $aDepartamentosSelecionados,
        function ($aProcessos, $oDepartamento) use ($oPdf, $oDataAtual, &$aDepartamentosNaoEncontrados) {
            if ($aProcessos) {
                imprimeCabecalho($oPdf, $oDepartamento);

                foreach ($aProcessos as $oDadosProcesso) {
                    $iQuantidadeLinhasAssunto = $oPdf->NbLines(70, $oDadosProcesso->assunto);
                    $iAlturaLinha = $iQuantidadeLinhasAssunto * 4;

                    if (($oPdf->GetY() + $iAlturaLinha) > 270) {
                        $oPdf->AddPage();
                        imprimeCabecalho($oPdf, $oDepartamento);
                    }

                    $oPdf->Cell(26, $iAlturaLinha, $oDadosProcesso->numero_processo . "/". $oDadosProcesso->ano_processo, 'TBR', 0, 'C');
                    $oPdf->Cell(48, $iAlturaLinha, $oDadosProcesso->login, 1, 0, 'C');

                    $iPosicaoX = $oPdf->GetX();
                    $iPosicaoY = $oPdf->GetY();
                    $oPdf->MultiCell(70, 4, $oDadosProcesso->assunto, 1, 'L');
                    $oPdf->SetXY($iPosicaoX + 70, $iPosicaoY);


                    $oUltimaData = new DBDate($oDadosProcesso->ultima_data);
                    $oPdf->Cell(30, $iAlturaLinha, $oUltimaData->getDate(DBDate::DATA_PTBR), 1, 0, 'C');

                    $iDiferenca = $oDataAtual->getTimeStamp() - $oUltimaData->getTimeStamp();
                    $iTotalDias = (int)floor($iDiferenca / (60 * 60 * 24));
                    $oPdf->Cell(18, $iAlturaLinha, $iTotalDias, 'TBL', 1, 'C');
                }


                $oPdf->SetFont('Arial', 'b', 7);
                $oPdf->Cell(192, 4, 'Total de processos deste departamento: ' . count($aProcessos), 'TB', 1, 'L', 1);

                $oPdf->Ln(8);
            } else {
                $aDepartamentosNaoEncontrados[] = $oDepartamento;
            }
        }, array('*'), $aFiltros, array('descricao_departamento'));

    return $aDepartamentosNaoEncontrados;
}

/**
 * @param PDFDocument $oPdf
 * @param $aDepartamentosSelecionados
 * @param $aFiltros
 * @return array
 * @throws DBException
 */
function imprimirSemDetalhamento(PDFDocument $oPdf, $aDepartamentosSelecionados, $aFiltros)
{
    $aDepartamentosNaoEncontrados = array();

    $aDepartamentos = ProcessoRepositorio::totalVencidosPorDepartamento($aDepartamentosSelecionados, $aFiltros);

    if ($aDepartamentos) {
        $oPdf->SetFont('Arial', 'b', 7);
        $oPdf->Cell(84, 4, 'Departamento', 'TBR', 0, 'C', 1);
        $oPdf->Cell(84, 4, 'Responsável', 'TB', 0, 'C', 1);
        $oPdf->Cell(24, 4, 'Total', 'TB', 1, 'C', 1);

        foreach ($aDepartamentos as $oDadosDepartamento) {

            $sResponsaveis = $oDadosDepartamento->responsaveis;

            if (!empty($sResponsaveis)) {
                $sResponsaveis = implode(', ', json_decode($oDadosDepartamento->responsaveis));
            }

            $iLinhasResponsaveis = $oPdf->NbLines(84, $sResponsaveis);
            $iAlturaLinha = $iLinhasResponsaveis * 4;

            if (($oPdf->GetY() + $iAlturaLinha) > 270) {
                $oPdf->AddPage();
                $oPdf->SetFont('Arial', 'b', 7);
                $oPdf->Cell(84, 4, 'Departamento', 'TBR', 0, 'C', 1);
                $oPdf->Cell(84, 4, 'Responsável', 'TB', 0, 'C', 1);
                $oPdf->Cell(24, 4, 'Total', 'TB', 1, 'C', 1);
            }

            $oPdf->SetFont('Arial', '', 7);
            $sDepartamento = "{$oDadosDepartamento->codigo_departamento} - {$oDadosDepartamento->descricao_departamento}";
            
            $oPdf->Cell(84, $iAlturaLinha, substr($sDepartamento, 0, 50), 'TBR', 0, 'L');

            $iPosicaoX = $oPdf->GetX();
            $iPosicaoY = $oPdf->GetY();
            $oPdf->MultiCell(84, 4, $sResponsaveis, 1, 'L');
            $oPdf->SetXY($iPosicaoX + 84, $iPosicaoY);
            $oPdf->Cell(24, $iAlturaLinha, $oDadosDepartamento->total, 'TB', 1, 'C');

            $aDepartamentosNaoEncontrados[] = $oDadosDepartamento->codigo_departamento;
        }
    }

    $aDepartamentosNaoEncontrados = array_diff($aDepartamentosSelecionados, $aDepartamentosNaoEncontrados);

    return array_map(function ($iDepartamentosNaoEncontrado) {
        return new DBDepartamento($iDepartamentosNaoEncontrado);
    }, $aDepartamentosNaoEncontrados);
}
