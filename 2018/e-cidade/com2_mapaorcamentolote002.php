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
require_once "fpdf151/pdfnovo.php";
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";

$oGet = db_utils::postMemory($_GET);

if (empty($oGet->iOrcamento)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Código do orçamento inválido.');
}

if (empty($oGet->sJustificativa)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao passar o parametro da Justificativa.');
}

$oDaoPcorcamitem  = new cl_pcorcamitem();
$oDaoPcorcamforne = new cl_pcorcamforne();
$oDaoPcorcamval   = new cl_pcorcamval();
$oDaoPcorcamtroca = new cl_pcorcamtroca();

$sSqlFornecedores = $oDaoPcorcamforne->sql_query( null,
                                                  "*",
                                                  null,
                                                  "pc21_codorc = {$oGet->iOrcamento} limit 1" );

$rsFornecedores = $oDaoPcorcamforne->sql_record( $sSqlFornecedores );

if ($oDaoPcorcamforne->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum Fornecedor cadastrado para o Orçamento.');
}

$sSqlItens = $oDaoPcorcamitem->sql_query_pcmaterproc( null,
                                                      "distinct pc11_seq, pc22_orcamitem, pc01_descrmater, pc80_tipoprocesso, pc68_nome, pc68_sequencial, pc80_codproc, \n"
                                                      . "(select coalesce(sum(val.pc23_valor), 0) / case when count(val.*) > 0 then count(val.*) else 1 end             \n"
                                                      . "   from pcorcamval as val                                                                                      \n"
                                                      . "        inner join pcorcamjulg on pc24_orcamitem = pc23_orcamitem and pc24_orcamforne = pc23_orcamforne        \n"
                                                      . "  where val.pc23_orcamitem = pc22_orcamitem) as valor_medio_item                                               \n",
                                                      "pc68_sequencial, pc11_seq",
                                                      "pc22_codorc = {$oGet->iOrcamento}" );
$rsItens = $oDaoPcorcamitem->sql_record( $sSqlItens );

if (!$rsItens || !pg_num_rows($rsItens)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum Item encontrado para o Orçamento.');
}

$iCodigoProcesso = db_utils::fieldsMemory($rsItens, 0)->pc80_codproc;

$oPdf = new PDFNovo();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(200);

$oPdf->addHeader("Mapa das Propostas do Orçamento por Lote");
$oPdf->addHeader('');
$oPdf->addHeader("Orçamento: " . $oGet->iOrcamento);
$oPdf->addHeader("Processo de Compras: " . $iCodigoProcesso);

$oPdf->addPage();

$iLote = "";
$iLine = 4;
$iCorVencedor = 230;
$iFont = 7;

$oPdf->setfont('arial', '', $iFont);

$iPageNumberItem = $oPdf->PageNo();

for ($iRow = 0; $iRow < pg_num_rows($rsItens); $iRow++) {

  $oItem = db_utils::fieldsMemory($rsItens, $iRow);

  if ($oPdf->getAvailHeight() < $iLine) {
    $oPdf->addPage();
  }

  /**
   * Imprime os cabeçalhos no inicio de cada lote
   */
  if ($iLote != $oItem->pc68_sequencial || $iPageNumberItem != $oPdf->PageNo()) {

    if ($oPdf->getAvailHeight() < $iLine * 2) {
      $oPdf->addPage();
    }

    $sSqlValorMedio = $oDaoPcorcamval->sql_query_julg_lote( null,
                                                            null,
                                                            "sum(pc23_valor) / count(distinct pc24_orcamforne) as valor_medio",
                                                            null,
                                                            "pc20_codorc = {$oGet->iOrcamento} and pc68_sequencial = {$oItem->pc68_sequencial}" );
    $rsValorMedio   = $oDaoPcorcamval->sql_record($sSqlValorMedio);

    $nValorMedioLote = 0;
    if ($rsValorMedio && $oDaoPcorcamval->numrows > 0) {
      $nValorMedioLote = db_utils::fieldsMemory($rsValorMedio, 0)->valor_medio;
    }

    $nValorMedioLote = number_format($nValorMedioLote, 2, ',', '.');

    $oPdf->setfont('arial', 'b', 11);
    $oPdf->cell(157, $iLine, "Lote {$oItem->pc68_nome} - Valor Médio: R$ {$nValorMedioLote}", 0, 0, 'L', 0);
    $oPdf->setfont('arial', 'b', $iFont);

    $oPdf->cell(35, $iLine, "Valor Unitário Médio", 1, 0, 'C', 1);
    $oPdf->ln();

    $oPdf->setfont('arial', '');

    $iPageNumberItem = $oPdf->PageNo();
  }

  $iLote = $oItem->pc68_sequencial;

  $nValorMedioItem = number_format($oItem->valor_medio_item, 2, ',', '.');

  $oPdf->cell(157, $iLine, "{$oItem->pc11_seq} - {$oItem->pc01_descrmater}", 1);
  $oPdf->cell(35, $iLine, "R$ {$nValorMedioItem}", 1, 0, 'R');
  $oPdf->ln();

  /**
   * Imprime os fornecedores ao final de cada lote
   */
  if ($iRow == pg_num_rows($rsItens)-1 || db_utils::fieldsMemory($rsItens, $iRow+1)->pc68_sequencial != $iLote) {

    $sSqlFornecedores  = $oDaoPcorcamval->sql_query_julg_lote( null,
                                                             null,
                                                             "sum(pc23_valor) as valor_cotado, pc23_orcamforne, pc68_nome, pc24_pontuacao, z01_nome",
                                                             null,
                                                             "pc20_codorc = {$oGet->iOrcamento} and pc68_sequencial = {$oItem->pc68_sequencial}" );
    $sSqlFornecedores .= " group by pc23_orcamforne, pc68_nome, pc24_pontuacao, z01_nome order by pc24_pontuacao";
    $rsFornecedores    = $oDaoPcorcamval->sql_record( $sSqlFornecedores );

    if ($rsFornecedores && $oDaoPcorcamval->numrows > 0) {

      if ($oPdf->getAvailHeight() < $iLine * 2) {
        $oPdf->addPage();
      }

      $iPageNumberFornecedor = $oPdf->PageNo();

      for ($iRowFornecedor = 0; $iRowFornecedor < $oDaoPcorcamval->numrows; $iRowFornecedor++) {

        if ($oPdf->getAvailHeight() < $iLine) {
          $oPdf->addPage();
        }

        if ($iRowFornecedor == 0 || $iPageNumberFornecedor != $oPdf->PageNo()) {

          $oPdf->setfont('arial', 'b');

          $oPdf->ln();
          $oPdf->cell(157, $iLine, "Fornecedores", 1, 0, 'C', 1);
          $oPdf->cell(35, $iLine, "Valor Cotado do Lote", 1, 0, 'C', 1);
          $oPdf->ln();

          $oPdf->setfont('arial', '');

          $iPageNumberFornecedor = $oPdf->PageNo();
        }

        $oFornecedor = db_utils::fieldsMemory($rsFornecedores, $iRowFornecedor);

        $nValorCotado = number_format($oFornecedor->valor_cotado, 2, ',', '.');

        $oPdf->setfillcolor($iCorVencedor);

        if ($iRowFornecedor == 0) {
          $oPdf->setfont('arial', 'b');
        }

        $oPdf->cell(157, $iLine, $oFornecedor->z01_nome, 1, 0, 'L', ($iRowFornecedor == 0));
        $oPdf->cell(35, $iLine, "R$ {$nValorCotado}", 1, 0, 'R', ($iRowFornecedor == 0));
        $oPdf->ln();

        $oPdf->setfont('arial', '');
        $oPdf->setfillcolor(200);
      }

      /**
       * Imprime as trocas de fornecedores
       */
      if ($oGet->sJustificativa == "S") {

        $sSqlJustificativa  = $oDaoPcorcamtroca->sql_query( null,
                                                            "cgm.z01_nome as anterior, a.z01_nome as atual, pc25_motivo",
                                                            "pc25_codtroca desc",
                                                            "pc25_orcamitem = {$oItem->pc22_orcamitem}");
        $rsJustificativa    = $oDaoPcorcamtroca->sql_record($sSqlJustificativa);

        if ($rsJustificativa && $oDaoPcorcamtroca->numrows > 0) {

          if ($oPdf->getAvailHeight() < $iLine * 4) {
            $oPdf->addPage();
          }

          $iPageNumberJustificativa = $oPdf->PageNo();

          for ($iRowJustificativa = 0; $iRowJustificativa < $oDaoPcorcamtroca->numrows; $iRowJustificativa++) {

            if ($oPdf->getAvailHeight() < $iLine) {
              $oPdf->addPage();
            }

            if ($iRowJustificativa == 0 || $iPageNumberJustificativa != $oPdf->PageNo()) {

              $oPdf->setfont('arial', 'b');

              $oPdf->ln();
              $oPdf->cell(192, $iLine, "TROCA DE FORNECEDORES", 1, 1, 'C', 1);

              $oPdf->cell(64, $iLine, "Fornecedor Substituto", 1, 0, 'C', 1);
              $oPdf->cell(64, $iLine, "Fornecedor Substituído", 1, 0, 'C', 1);
              $oPdf->cell(64, $iLine, "Justificativa", 1, 1, 'C', 1);

              $oPdf->setfont('arial', '');

              $iPageNumberJustificativa = $oPdf->PageNo();
            }

            $oJustificativa = db_utils::fieldsMemory($rsJustificativa, $iRowJustificativa);

            $sAtual    = $oJustificativa->atual;
            $sAnterior = $oJustificativa->anterior;
            $sMotivo   = $oJustificativa->pc25_motivo;

            while ($oPdf->GetStringWidth($sAtual) > 63) {
              $sAtual = substr($sAtual, 0, strlen($sAtual)-1);
            }

            while ($oPdf->GetStringWidth($sAnterior) > 63) {
              $sAnterior = substr($sAnterior, 0, strlen($sAnterior)-1);
            }

            while ($oPdf->GetStringWidth($sMotivo) > 63) {
              $sMotivo = substr($sMotivo, 0, strlen($sMotivo)-1);
            }

            $oPdf->cell(64, $iLine, $sAtual, 1, 0, 'L');
            $oPdf->cell(64, $iLine, $sAnterior, 1, 0, 'L');
            $oPdf->cell(64, $iLine, $sMotivo, 1, 1, 'L');
          }
        }
      }

    }

    $oPdf->ln();
  }

}

$oPdf->setfillcolor($iCorVencedor);
$oPdf->setfont('arial', 'b');

$oPdf->cell(5, 4, "", 1, 0, 'L', 1);
$oPdf->cell(100, 4, "Ganhador do Lote", 0, 0, 'L');

Header('Content-disposition: inline; filename=mapaorcamento_lote_' . time() . '.pdf');
$oPdf->output();
