<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/oucvsgit
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

require_once(modification('libs/db_utils.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_stdlib.php'));

const FONT = 'Arial';
const HEIGHT = 4;

$relatorio = new stdClass;
$relatorio->filtros = (object)filter_input_array(INPUT_GET);
$head1 = 'INFRAÇÃO DE TRANSITO';
$head2 = 'RELATÓRIO DE MULTAS NÃO IMPORTADAS';

$arquivo = $_GET['arquivo'];
if (strpos($arquivo, 'tmp') === false || strpos($arquivo, 'json') === false) {
    throw new Exception('Arquivo de Multas não é válido');
}
$nomeArquivo = str_replace(array("tmp/multas_nao_cadastradas_", ".json"), '', $arquivo);
$data = json_decode(file_get_contents($_GET['arquivo']));

uasort($data, function ($corrente, $anterior) {
    return $corrente->codigo_infracao > $anterior->codigo_infracao;
});
$head3 = $nomeArquivo;
$pdf = new PDF;
$pdf->Open();
$pdf->SetAutoPageBreak(false);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();

cabecalho($pdf);

foreach ($data as $multa) {
    if ($pdf->getY() > ($pdf->h - 11)) {
        cabecalho($pdf);
    }

    $pdf->SetFont(FONT, '', 8);
    $pdf->Cell(50, HEIGHT, $multa->auto_infracao, 1, 0, 'C');
    $pdf->Cell(70, HEIGHT, $multa->codigo_infracao, 1, 1, 'C');
}

$pdf->Output();


/**
 * @param PDF $pdf
 */
function cabecalho(PDF $pdf)
{
    $pdf->AddPage();
    $pdf->SetFont(FONT, 'b', 8);
    $pdf->Cell(50, HEIGHT, 'Auto de Infração', 1, 0, 'C', 1);
    $pdf->Cell(70, HEIGHT, 'Código da Infração', 1, 1, 'C', 1);
}
