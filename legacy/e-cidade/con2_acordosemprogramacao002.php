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
require_once(modification("fpdf151/FpdfMultiCellBorder.php"));
require_once(modification("libs/db_utils.php"));

const VIGENTE = 1;
const PERIODO = 3;


$acordosDepartamento = array();

try {
    $where = array(
        'not exists (select 1 from acordoprogramacaofinanceira where ac34_acordo = ac16_sequencial)',
        "db_config.codigo = " . db_getsession("DB_instit")
    );

    if ($_GET['tipoVigencia'] == VIGENTE) {
        $where[] = "'". date('Y-m-d') ."' between ac16_datainicio and ac16_datafim ";
    }

    if ($_GET['tipoVigencia'] == PERIODO) {
        $head3 = "Vigência: ";

        if (!empty($_GET['dtInicio'])) {
            $where[] = "ac16_datainicio >= to_date('".$_GET['dtInicio']."', 'DD/MM/YYYY')";
            $head3 .= $_GET['dtInicio'];
        }

        if (!empty($_GET['dtFim'])) {
            $where[] = "ac16_datafim <= to_date('".$_GET['dtFim']."', 'DD/MM/YYYY') ";
            $head3 .= ' até '. $_GET['dtFim'];
        }

        if (empty($_GET['dtInicio']) && empty($_GET['dtFim'])) {
            $head3 = "Vigência: Todos";
        }
    }

    $campos = array(
        'ac16_anousu',
        'ac16_numero',
        'ac16_resumoobjeto as resumo',
        'ac16_origem',
        'ac16_valor as valor',
        'ac16_datainicio as inicio',
        'ac16_datafim as fim',
        'coddepto',
        'descrdepto',
    );

    $ordem = 'inicio, fim, ac16_anousu, ac16_numero';
    $daoAcordo = new cl_acordo;
    $sqlAcordo = $daoAcordo->sql_query(null, implode(', ', $campos), $ordem, implode(' and ', $where));
    $rsAcordos = db_query($sqlAcordo);

    if (!$rsAcordos) {
        throw new Exception("Erro ao buscar acordos");
    }

    $listaAcordos = db_utils::makeCollectionFromRecord($rsAcordos, function ($dado) {

        $dado->inicio = db_formatar($dado->inicio, 'd');
        $dado->fim = db_formatar($dado->fim, 'd');
        $dado->numero = "{$dado->ac16_numero}/{$dado->ac16_anousu}";
        $dado->valor = db_formatar($dado->valor, 'f');
        $dado->descrdepto = "{$dado->coddepto} - {$dado->descrdepto}";
        return $dado;
    });

    foreach ($listaAcordos as $acordoDepartamento) {
        if (!array_key_exists($acordoDepartamento->coddepto, $acordosDepartamento)) {
            $departamento = new stdClass();
            $departamento->departamento = $acordoDepartamento->descrdepto;
            $departamento->acordos = array();
            $acordosDepartamento[$acordoDepartamento->coddepto] = $departamento;
        }

        $acordosDepartamento[$acordoDepartamento->coddepto]->acordos[] = $acordoDepartamento;
    }

    if (count($acordosDepartamento) == 0) {
        throw new Exception("Não existe Contratos sem Programação de Competência para o filtro informado.");
    }
} catch (Exception $e) {
    $sMsg = urlencode($e->getMessage());
    db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsg);
}

$head1 = 'CONTRATOS SEM PROGRAMAÇÃO DE COMPETÊNCIA';
switch ($_GET['tipoVigencia']) {
    case 1:
        $head2 = "Contratos: Vigentes";
        break;
    case 3:
        $head2 = "Contratos: Período";
        // $head3 = "Vigência: ";
        break;
    default:
        $head2 = "Contratos: Todos";
        break;
}

$pdf = new FpdfMultiCellBorder();

$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(false, 10);
$pdf->mostrarRodape(true);
$pdf->mostrarTotalDePaginas(true);
$pdf->exibeHeader(true);
$pdf->SetFillColor(220);
$pdf->AddPage();

foreach ($acordosDepartamento as $departamento) {
    $quebraPagina = ($pdf->getY() >= ($pdf->h - 16));
    cabecalhoDepartamento($pdf, $departamento->departamento, $quebraPagina);
    $imprimeCabecalho = true;

    foreach ($departamento->acordos as $acordo) {
        $quebraPagina = ($pdf->getY() >= ($pdf->h - 16));

        if ($imprimeCabecalho || ($pdf->getY() >= ($pdf->h - 16))) {
            $imprimeCabecalho = false;
            cabecalhoAcordo($pdf, $quebraPagina);
        }
        $pdf->Cell(28, 4, $acordo->numero, 1, 0, 'L');
        $pdf->Cell(96, 4, $acordo->resumo, 1, 0, 'L');
        $pdf->Cell(39, 4, $acordo->inicio . ' até ' . $acordo->fim, 1, 0, 'C');
        $pdf->Cell(29, 4, $acordo->valor, 1, 1, 'R');
    }
    $pdf->ln();
}

$pdf->Output();

function cabecalhoDepartamento(FpdfMultiCellBorder $pdf, $sDepartamento, $quebraPagina = false)
{
    if ($quebraPagina) {
        $pdf->AddPage();
    }
    $pdf->SetFont('arial', 'B', 9);
    $pdf->Cell(192, 5, "Departamento: {$sDepartamento}", 0, 1, 'L');
    $pdf->SetFont('arial', '', 8);
}

function cabecalhoAcordo(FpdfMultiCellBorder $pdf, $quebraPagina = false)
{
    if ($quebraPagina) {
        $pdf->AddPage();
    }

    $pdf->SetFont('arial', 'B', 8);
    $pdf->Cell(28, 4, "Número", 1, 0, 'C', 1);
    $pdf->Cell(96, 4, "Resumo", 1, 0, 'C', 1);
    $pdf->Cell(39, 4, "Vigência", 1, 0, 'C', 1);
    $pdf->Cell(29, 4, "Valor", 1, 1, 'C', 1);
    $pdf->SetFont('arial', '', 8);
}
