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

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoV;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");

$get = db_utils::postMemory($_GET);
$anoSessao = db_getsession('DB_anousu');

function formatValue($value)
{
    return number_format($value, 2, ',', '.');
}

try {
    $get->instituicoes = preg_replace('/[^\,0-9]/', '', $get->instituicoes);
    $get->periodo      = intval($get->periodo);

    if (empty($get->periodo)) {
        throw new Exception("Período não informado.");
    }

    if (empty($get->instituicoes)) {
        throw new Exception("Instituição não informada.");
    }

    $instituicoes = array();
    foreach (explode(',', $get->instituicoes) as $codigoInstituicao) {
        $instituicoes[] = InstituicaoRepository::getInstituicaoByCodigo($codigoInstituicao);
    }

    $anexo = new AnexoV($anoSessao, $get->periodo);
    $anexo->setInstituicoes($get->instituicoes);
    $dados = $anexo->getDados();

    $pdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $pdf->Open();

    $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();

    if (count($instituicoes) == 1) {
        $oInstituicao = reset($instituicoes);

        $pdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

        if ($oInstituicao->getTipo() != Instituicao::TIPO_PREFEITURA) {
            $pdf->addHeaderDescription($oInstituicao->getDescricao());
        }
    } else {
        $pdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $pdf->addHeaderDescription("RELATÓRIO DE GESTÃO FISCAL");
    $pdf->addHeaderDescription("DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA E DOS RESTOS A PAGAR");
    $pdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $pdf->addHeaderDescription("");

    $pdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$anoSessao}");

    $pdf->open();

    $pdf->setFillColor(235);

    $pdf->addPage();
    $pdf->setFontSize(7);

    $xInicial = 10;
    $yInicial = 40;

    $iLarguraAtual = $xInicial;
    $iAlturaAtual = $yInicial;

    $pdf->Rect($xInicial, $yInicial, 58, 25, 'DF'); //IDENTIFICAÇÃO DOS RECURSOS

    $iLarguraAtual += 58;
    $pdf->Rect($iLarguraAtual, $iAlturaAtual, 20, 25, 'DF'); //DISPONIBILIDADE DE CAIXA BRUTA

    $pdf->Rect($iLarguraAtual + 20, 48, 25, 17, 'DF'); //De Exercícios Anteriores
    $pdf->Rect($iLarguraAtual + 45, 48, 25, 17, 'DF'); //Do Exercício

    $iLarguraAtual += 20;
    $pdf->Rect($iLarguraAtual, $iAlturaAtual, 100, 4, 'DF'); //OBRIGAÇÕES FINANCEIRAS
    $iAlturaAtual += 4;
    $pdf->Rect($iLarguraAtual, $iAlturaAtual, 50, 4, 'DF'); //Restos a Pagar Liquidados e Não Pagos

    $iLarguraAtual += 50;
    $pdf->Rect($iLarguraAtual, $iAlturaAtual, 25, 21, 'DF'); //Restos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores
    $iLarguraAtual += 25;
    $pdf->Rect($iLarguraAtual, $iAlturaAtual, 25, 21, 'DF'); //Demais Obrigações Fianceiras

    $iLarguraAtual += 25;
    $pdf->Rect($iLarguraAtual, $yInicial, 20, 25, 'DF'); //INSUFICIÊNCIA FINANCEIRA VERIFICADA NO CONSÓRCIO PÚBLICO

    $iLarguraAtual += 20;
    $pdf->Rect($iLarguraAtual, $yInicial, 29, 25, 'DF'); //DISPONIBILIDADE DE CAIXA LÍQUIDA (ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO)

    $iLarguraAtual += 29;
    $pdf->Rect($iLarguraAtual, $yInicial, 20, 25, 'DF'); //RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO

    $iLarguraAtual += 20;
    $pdf->Rect($iLarguraAtual, $yInicial, 29, 25, 'DF'); //RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO

    $pdf->cell(0, 2, "", "", 1, "");
    $pdf->setFontSize(6);
    $pdf->cell(142, 3, "RGF - ANEXO 5 (LRF, Art. 55, Inciso III, alínea 'a')", "", 0, "L");
    $pdf->cell(135, 3, "R$ 1,00", "", 1, "R");

    $yInicial = $pdf->getY();
    $xInicial = $pdf->getX();

    $pdf->setBold(1);
    $pdf->setFontSize(5);

    $iLarguraLinha = 58;
    $iLarguraAtual = $xInicial;

    $pdf->multicell($iLarguraLinha, 4, 'IDENTIFICAÇÃO DOS RECURSOS', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 20;
    $pdf->multicell($iLarguraLinha, 4, 'DISPONIBILIDADE DE CAIXA BRUTA', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 100;
    $pdf->multicell($iLarguraLinha, 4, 'OBRIGAÇÕES FINANCEIRAS', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 20;
    $pdf->multicell($iLarguraLinha, 4, 'INSUFICIÊNCIA FINANCEIRA VERIFICADA NO CONSÓRCIO PÚBLICO', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 29;
    $pdf->multicell($iLarguraLinha, 4, 'DISPONIBILIDADE DE CAIXA LÍQUIDA (ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO) ¹', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 20;
    $pdf->multicell($iLarguraLinha, 4, 'RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 29;
    $pdf->multicell($iLarguraLinha, 4, 'EMPENHOS NÃO LIQUIDADOS CANCELADOS (NÃO INSCRITOS POR INSUFICIÊNCIA FINANCEIRA)', '', 'C');

    /**
     * 2º linha
     */
    $yInicial = 44;
    $iLarguraAtual = $pdf->getX() + 78;
    $iLarguraLinha = 50;
    $pdf->SetY($yInicial);
    $pdf->SetX($pdf->getX() + 78);
    $pdf->multicell($iLarguraLinha, 4, 'Restos a Pagar Liquidados e Não Pagos ', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $iLarguraLinha = 25;
    $pdf->multicell($iLarguraLinha, 4, 'Restos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $pdf->multicell($iLarguraLinha, 4, 'Demais Obrigações Financeiras', '', 'C');

    $yInicial = 48;
    $iLarguraAtual = $pdf->getX() + 78;
    $iLarguraLinha = 25;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $pdf->multicell($iLarguraLinha, 4, 'De Exercícios Anteriores', '', 'C');
    $iLarguraAtual += $iLarguraLinha;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $pdf->multicell($iLarguraLinha, 4, 'Do Exercício', '', 'C');

    $yInicial = 60;
    $iLarguraAtual = $pdf->getX() + 58;
    $pdf->SetY($yInicial);
    $pdf->SetX($iLarguraAtual);

    $pdf->Cell(20, 4, '(a) ', '', 0, 'C');
    $pdf->Cell(25, 4, '(b) ', '', 0, 'C');
    $pdf->Cell(25, 4, '(c) ', '', 0, 'C');
    $pdf->Cell(25, 4, '(d) ', '', 0, 'C');
    $pdf->Cell(25, 4, '(e) ', '', 0, 'C');
    $pdf->Cell(20, 4, '(f) ', '', 0, 'C');
    $pdf->Cell(29, 4, '(g) = (a - (b + c + d + e) - f)', '', 1, 'C');

    $yInicial = 65;
    $pdf->setBold(0);
    $pdf->SetY($yInicial);
    foreach ($dados as $item) {

        $pdf->SetFont('Arial', '', 5);
        if ($item->totalizar) {
            $pdf->SetFont('Arial', 'B', 6);
        }

        $descricao = relatorioContabil::getIdentacao($item->nivel) . $item->descricao;

        $iLinhas = $pdf->NbLines(58, $descricao);
        $yAntes = $pdf->getY();

        $iAlturaLinha = 5 * $iLinhas;

        $pdf->multicell(58, 5, $descricao , 1);
        $pdf->setY($yAntes);
        $pdf->setX($pdf->getX() + 58);

        $pdf->cell(20, $iAlturaLinha, formatValue($item->disp_caixa), 1, 0, 'R');
        $pdf->cell(25, $iAlturaLinha, formatValue($item->exanterior), 1, 0, 'R');
        $pdf->cell(25, $iAlturaLinha, formatValue($item->vlrexatual), 1, 0, 'R');
        $pdf->cell(25, $iAlturaLinha, formatValue($item->rp_nprocexant), 1, 0, 'R');
        $pdf->cell(25, $iAlturaLinha, formatValue($item->financeira), 1, 0, 'R');
        $pdf->cell(20, $iAlturaLinha, formatValue($item->insuficiencia_financeira), 1, 0, 'R');
        $pdf->cell(29, $iAlturaLinha, formatValue($item->disp_caixa_liquida), 1, 0, 'R');
        $pdf->cell(20, $iAlturaLinha, formatValue($item->rp_empenhado_nao_processado), 1, 0, 'R');
        $pdf->cell(29, $iAlturaLinha, formatValue($item->empenho_nao_liquidado_cancelado), 1, 1, 'R');

    }

    $pdf->Ln(2);
    $anexo->notaExplicativa( $pdf, array($pdf, 'addPage'), 20 );

    $pdf->ln($pdf->getAvailHeight() - 10);
    $oDaoAssinatura = new \cl_assinatura();
    assinaturas($pdf, $oDaoAssinatura, 'GF');

    $pdf->showPDF("RGF_anexo_5_DisponibilidadeCaixa_Restos_2017");

} catch (Exception $e) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$e->getMessage()}");
}