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
$relatorio->filtros = (object) filter_input_array(INPUT_GET);

try {

  if ( empty($relatorio->filtros->dtPagamentoInicial) ) {
    throw new Exception('Informe a data inicial de pagamento.');
  }

  if ( empty($relatorio->filtros->dtPagamentoFinal) ) {
    throw new Exception('Informe a data final de pagamento.');
  }

  $dataInicio = "to_date('". $relatorio->filtros->dtPagamentoInicial . "', 'DD/MM/YYYY')";
  $dataFim = "to_date('". $relatorio->filtros->dtPagamentoFinal . "', 'DD/MM/YYYY')";

  $where  = "i08_dtpagamento between {$dataInicio} and {$dataFim} ";
  $where .= 'and i08_duplicado is true ';
  $campos = "to_char(i08_dtpagamento, 'DD/MM/YYYY') as data_pagamento, i08_vlprefeitura, i08_nossonumero, i08_codigoinfracao";

  $dao = new cl_arquivoinfracaomulta;
  $sql = $dao->sql_query_file(null, $campos, 'i08_dtpagamento', $where);

  $rs = db_query($sql);

  if ( !$rs ) {
    throw new Exception('Não foi possível buscar os pagamentos de multas duplicadas.');
  }

  if ( pg_num_rows($rs) == 0 ) {
    throw new Exception('Não existe pagamentos de multas duplicadas no período informado.');
  }

  $relatorio->pagamentos = db_utils::getCollectionByRecord($rs);

} catch (\Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

$head1 = 'INFRAÇÃO DE TRANSITO';
$head2 = 'RELATÓRIO DE PAGAMENTOS DUPLICADOS';
$head4 = 'Data de Pagamento: ' . $relatorio->filtros->dtPagamentoInicial . ' até ' . $relatorio->filtros->dtPagamentoFinal;

$pdf = new PDF;
$pdf->Open();
$pdf->SetAutoPageBreak(false);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();

cabecalho($pdf);

foreach ($relatorio->pagamentos as $pagamento) {

  if ( $pdf->getY() > ( $pdf->h - 11 ) ) {
    cabecalho($pdf);
  }

  $valor = 'R$ '. trim(db_formatar($pagamento->i08_vlprefeitura, 'f'));

  $pdf->SetFont(FONT, '', 8);
  $pdf->Cell(48, HEIGHT, $pagamento->i08_codigoinfracao, 1, 0, 'C');
  $pdf->Cell(48, HEIGHT, $pagamento->data_pagamento, 1, 0, 'C');
  $pdf->Cell(48, HEIGHT, $valor, 1, 0, 'C');
  $pdf->Cell(48, HEIGHT, $pagamento->i08_nossonumero, 1, 1, 'C');
}

$pdf->Output();

function cabecalho(PDF $pdf)
{
  $pdf->AddPage();
  $pdf->SetFont(FONT, 'b', 8);
  $pdf->Cell(48, HEIGHT, 'Código da Infração', 1, 0, 'C', 1);
  $pdf->Cell(48, HEIGHT, 'Data de Pagamento', 1, 0, 'C', 1);
  $pdf->Cell(48, HEIGHT, 'Valor', 1, 0, 'C', 1);
  $pdf->Cell(48, HEIGHT, 'Nosso Número', 1, 1, 'C', 1);
}