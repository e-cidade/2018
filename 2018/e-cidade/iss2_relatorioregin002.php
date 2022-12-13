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

require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('fpdf151/pdf.php'));
require_once(modification("libs/db_stdlib.php"));

use ECidade\Tributario\Integracao\JuntaComercial\Regin;

const FONT = 'Arial';
const HEIGHT = 4;

$relatorio = new stdClass;
$relatorio->acoes = array();
$relatorio->filtros = filter_input_array(INPUT_GET);
$relatorio->totalizadores = array(
  1 => 0,
  2 => 0
);
$relatorio->tipos = array(
  1 => 'Constituição',
  2 => 'Alteração',
  4 => 'Constituição'
);

try {

  $where = array();

  if ( $relatorio->filtros['data_inicial'] ) {
    $where[] = "q147_data >= TO_DATE('". $relatorio->filtros['data_inicial'] . "', 'DD/MM/YYYY')";
  }

  if ( $relatorio->filtros['data_final'] ) {
    $where[] = "q147_data <= TO_DATE('" . $relatorio->filtros['data_final'] . "', 'DD/MM/YYYY')";
  }

  $juntaComercialProtocoloDAO = new cl_juntacomercialprotocolo;

  $campos  = "q147_funcao as tipo, q147_protocolo as protocolo, q147_xml, to_char(q147_data, 'DD/MM/YYYY') as data, ";
  $campos .= " (select array_to_string(array_accum(q148_codevento || ' - ' || q148_evento), '|') as descricao ";
  $campos .= "    from juntacomercialprotocoloeventos ";
  $campos .= "   where q148_protocolo = q147_sequencial) as eventos ";

  $queryJuntaComercialProtocolo = $juntaComercialProtocoloDAO->sql_query_file(
    null,
    $campos,
    'q147_data',
    implode(' AND ', $where)
  );

  $resultadoJuntaComercialProtocolo = db_query($queryJuntaComercialProtocolo);
  if ( !$resultadoJuntaComercialProtocolo ) {
    throw new Exception('Erro ao buscar dados da junta comercial.');
  }

  if ( pg_num_rows($resultadoJuntaComercialProtocolo) == 0 ) {
    throw new Exception('Nenhum registro encontrado para os filtros informados.');
  }

  db_inicio_transacao();

  $caminho = ECIDADE_PATH . 'tmp/juntacomercial.xml';

  $relatorio->acoes = db_utils::makeCollectionFromRecord($resultadoJuntaComercialProtocolo, function ($dado) use ($relatorio, $caminho) {

    DBLargeObject::leitura($dado->q147_xml, $caminho);

    $xml = file_get_contents($caminho);

    $regin = new Regin($xml);

    $dadosProtocolo = $regin->getDadosGrupo(Regin::PROTOCOLO);

    if ( $relatorio->filtros['acao'] && $relatorio->filtros['acao'] != $dadosProtocolo->tipo_acao ) {
      return null;
    }

    $dadosEmpresa = $regin->getDadosGrupo(Regin::EMPRESA);

    $dado->inscricao = '';
    $dado->protocolo = $dadosEmpresa->protocolo;
    $dado->acao = $relatorio->tipos[$dadosProtocolo->tipo_acao];

    $where   = array(
      "q02_obs ilike '%{$dadosEmpresa->protocolo}%'",
      "z01_cgccpf = '" . $dadosEmpresa->cpfcnpj . "'"
    );

    $issBaseDAO = new cl_issbase;
    $queryIssBase = $issBaseDAO->sql_query(null, 'q02_inscr', null, implode(' and ', $where));
    $resultadoIssBase = db_query($queryIssBase);

    if ( !$resultadoIssBase ) {
      throw new Exception('Erro ao buscar inscrição do CNPJ: ' . $dadosEmpresa->cpfcnpj);
    }

    if ( pg_num_rows($resultadoIssBase) == 1 ) {
      $dado->inscricao = db_utils::fieldsMemory($resultadoIssBase, 0)->q02_inscr;
    }

    switch ( $dadosProtocolo->tipo_acao ) {
      case 4:
        $relatorio->totalizadores[1]++;
        break;
      default:
        $relatorio->totalizadores[$dadosProtocolo->tipo_acao]++;
    }

    $eventos = array();
    if ( strpos($dado->eventos, '|') )  {
      $eventos = explode('|', $dado->eventos);
    } elseif ( !empty($dado->eventos) ) {
      $eventos = array($dado->eventos);
    }

    $dado->eventos = $eventos;

    unlink($caminho);

    return $dado;
  });

  if ( empty($relatorio->acoes) ) {
    throw new Exception("Nenhum registro encontrado para os filtros informados.");
  }

  db_fim_transacao();

  $head1 = 'RELATÓRIO DE INTEGRAÇÃO DO REGIN';
  $head2 = 'Data Inicial: ' . ($relatorio->filtros['data_inicial'] ? $relatorio->filtros['data_inicial'] : ' - ');
  $head3 = 'Data Final: ' . ($relatorio->filtros['data_final'] ? $relatorio->filtros['data_final'] : ' - ');
  $head4 = "Ação: " . ($relatorio->filtros['acao'] ? $relatorio->tipos[$relatorio->filtros['acao']] : 'TODOS');

  $pdf = new PDF;
  $pdf->Open();
  $pdf->SetAutoPageBreak(false);
  $pdf->SetFillColor(220);
  $pdf->AliasNbPages();
  $pdf->AddPage();

  foreach ($relatorio->acoes as $acao) {

    $values = array_map(function($evento) use ($pdf) {

      return $pdf->NbLines(192, $evento);

    }, $acao->eventos);

    $linhas = array_sum($values);
    $height = ($linhas * HEIGHT) + (HEIGHT * 6);

    if ( $height > $pdf->getAvailHeight() ) {
      $pdf->AddPage();
    }

    $pdf->SetFont(FONT, 'b', 8);
    $pdf->Cell(49, HEIGHT, 'Protocolo', 1, 0, 'C');
    $pdf->Cell(47, HEIGHT, 'Inscrição', 1, 0, 'C');
    $pdf->Cell(47, HEIGHT, 'Data', 1, 0, 'C');
    $pdf->Cell(49, HEIGHT, 'Ação', 1, 1, 'C');

    $pdf->SetFont(FONT, '', 8);
    $pdf->Cell(49, HEIGHT, $acao->protocolo, 1, 0, 'L');
    $pdf->Cell(47, HEIGHT, $acao->inscricao, 1, 0, 'C');
    $pdf->Cell(47, HEIGHT, $acao->data, 1, 0, 'C');
    $pdf->Cell(49, HEIGHT, $acao->acao, 1, 1, 'L');

    if ( !empty($acao->eventos) ) {

      $pdf->SetFont(FONT, 'b', 8);
      $pdf->Cell(192, HEIGHT, 'Eventos', 'LR', 1, 'L');

      $pdf->SetFont(FONT, '', 8);
      foreach ($acao->eventos as $evento) {
        $pdf->MultiCell(192, HEIGHT, $evento, 'LR', 'J');
      }
    }

    $pdf->Cell(192, HEIGHT, '', 1, 1, 'L', true);
  }

  if ( ( $pdf->getY() + ( HEIGHT * 2 ) ) > ( $pdf->h - 10 ) ) {
    $pdf->AddPage();
  }

  $pdf->SetFont(FONT, 'b', 8);
  $pdf->Cell(49, HEIGHT, '', 0, 0, 'L');
  $pdf->Cell(47, HEIGHT, 'Totais:', 1, 0, 'L');

  $pdf->SetFont(FONT, '', 8);

  foreach ($relatorio->totalizadores as $key => $totalizador) {

    if ( empty($totalizador) ) {
      continue;
    }

    $pdf->Cell(48, HEIGHT, $relatorio->tipos[$key] . ": $totalizador ", 1, 0, 'L');
  }

  $pdf->Output();

} catch (\Exception $e) {

  db_fim_transacao(true);
  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}