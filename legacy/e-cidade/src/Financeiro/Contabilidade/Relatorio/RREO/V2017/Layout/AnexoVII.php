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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout;

use \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII as Relatorio;
use \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\LinhaAnexoVII;
use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoVII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoVII {

  /**
   * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII
   */
  private $oAnexo;

  /**
   * @var \PDFDocument
   */
  private $oPdf;

  /**
   * Altura da célula
   * @type integer
   */
  const ALTURA = 4;

  /**
   * @param Relatorio $oAnexo
   */
  public function setAnexo(\ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII $oAnexo) {
    $this->oAnexo = $oAnexo;
  }

  /**
   * Emite o relatório PDF
   */
  public function emitir() {

    $this->oAnexo->getDados();
    $this->oPdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
    $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
    $sMesInicio  = mb_strtoupper(\DBDate::getMesExtenso($this->oAnexo->getPeriodo()->getMesInicial()));
    $sMesFim     = mb_strtoupper(\DBDate::getMesExtenso($this->oAnexo->getPeriodo()->getMesFinal()));


    $aInstituicoes = explode(",", $this->oAnexo->getInstituicoes());

    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }else{
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DOS RESTOS A PAGAR POR PODER E ÓRGÃO');
    $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
    $this->oPdf->addHeaderDescription('JANEIRO A ' . $sMesFim . '/' . $this->oAnexo->getAno() . ' - BIMESTRE ' . $sMesInicio . '-' . $sMesFim);
    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->oPdf->setFontSize(5);
    $this->oPdf->setBold(true);
    $nTamanhosCelulas = ($this->oPdf->getAvailWidth() / 2);
    $this->oPdf->cell($nTamanhosCelulas, self::ALTURA, 'RREO ANEXO 7 (LRF, art. 53, inciso V)', 0, 0);
    $this->oPdf->cell($nTamanhosCelulas, self::ALTURA, 'R$ 1,00', 0, 1, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);

    $this->cabecalho();
    $this->imprimir();

    $oRelatorio   = new \relatorioContabil(97, false);
    $oRelatorio->notaExplicativa($this->oPdf, $this->oAnexo->getPeriodo()->getCodigo(), $this->oPdf->getAvailWidth());

    $this->oPdf->ln($this->oPdf->getAvailHeight() - 20);
    assinaturas($this->oPdf, new \cl_assinatura, 'LRF');

    $this->oPdf->showPDF('RREO_AnexoVII_v2017_'.time());

  }

  /**
   * Escreve as informações encontradas no relatório
   */
  private function imprimir() {

    foreach ($this->oAnexo->getDados() as $iIndice => $oLinha) {

      switch ($iIndice) {

        case Relatorio::LINHA_EXCETO_INTRA :

          $this->imprimeLinha($oLinha, 0, true);
          foreach ($oLinha->getLinhas() as $oLinhaAnalitica) {
            $this->imprimeLinha($oLinhaAnalitica, 4, false);
          }
          break;

        case Relatorio::LINHA_INTRA :
          $this->imprimeLinha($oLinha, 0, true);
          break;

        case Relatorio::LINHA_TOTAL_GERAL :

          $this->imprimeLinha($oLinha, 0, true, true);
          $this->oPdf->ln(self::ALTURA);

          $aLinhaIntra = $this->oAnexo->getDados();
          $oLinhaIntra = $aLinhaIntra[Relatorio::LINHA_INTRA];
          $aLinhasPoderes = $oLinhaIntra->getLinhas();
          if (count($aLinhasPoderes) > 0) {

            $this->cabecalho();
            $this->imprimeLinha($oLinhaIntra, 0, true);
            foreach ($oLinhaIntra->getLinhas() as $oLinhaIntra) {
              $this->imprimeLinha($oLinhaIntra, 4, false);
            }
            $this->imprimeLinha($aLinhaIntra[Relatorio::LINHA_TOTAL_INTRA], 0, true, true);
          }
          break;
      }
    }
  }

  /**
   * @param LinhaAnexoVII $oLinha
   * @param int           $iNivel
   * @param bool          $lNegrito
   * @param bool          $lTotalizar
   */
  private function imprimeLinha(LinhaAnexoVII $oLinha, $iNivel = 0, $lNegrito = false, $lTotalizar = false) {

    $sBordaInicio = 'R';
    $sBordaFim    = 'L';
    if ($lTotalizar) {
      $sBordaInicio = 'TRB';
      $sBordaFim   = 'LTB';
    }

    $this->oPdf->setBold($lNegrito);
    $sNivel = str_repeat(' ', $iNivel * 2);
    $this->oPdf->cell(60, self::ALTURA, $sNivel . $oLinha->getDescricao(), $sBordaInicio, 0);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorProcessadoEmExerciciosAnteriores(), 'f'),    $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorProcessadoNoExercicioAnterior(), 'f'),       $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorPagoProcessado(), 'f'),                      $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorCanceladoProcessado(), 'f'),                 $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(19, self::ALTURA, db_formatar($oLinha->getSaldoProcessado(), 'f'),                          $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorNaoProcessadoEmExerciciosAnteriores(), 'f'), $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorNaoProcessadoNoExercicioAnterior(), 'f'),    $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorLiquidadoNaoProcessado(), 'f'),              $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorPagoNaoProcessado(), 'f'),                   $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getValorCanceladoNaoProcessado(), 'f'),              $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getSaldoNaoProcessado(), 'f'),                       $sBordaFim, 0, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(18, self::ALTURA, db_formatar($oLinha->getSaldoTotal(), 'f'),                               $sBordaFim, 1, \PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
  }


  /**
   * Imprime o cabeçalho padrão para o relatório
   */
  private function cabecalho() {

    $iAltura = $this->oPdf->getY();
    $iWidthPoder = 60;
    $this->oPdf->cell($iWidthPoder, 20, 'PODER / ÓRGÃO', "TRB", 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->setXY(($iWidthPoder+10), $iAltura);
    $this->oPdf->cell(91, self::ALTURA, 'RESTOS A PAGAR PROCESSADOS E NÃO PROCESSADOS LIQUIDADOS EM EXERCÍCIOS ANTERIORES', 1, 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(126, self::ALTURA, 'RESTOS A PAGAR NÃO PROCESSADOS', 'TLB', 1, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->setX($iWidthPoder+10);
    $this->oPdf->cell(36, self::ALTURA, 'Inscritos', 1, 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(19, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(36, self::ALTURA, 'Inscritos', 1, 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(18, self::ALTURA, '', "LR", 0);
    $this->oPdf->cell(18, self::ALTURA, '', "TL", 1);

    $this->oPdf->setX($iWidthPoder+10);
    $iAlturaCelula = $this->oPdf->getY();
    $nHeight = $this->oPdf->getMultiCellHeight(18, self::ALTURA - 1, 'Em Exercícios Anteriores');
    $this->oPdf->MultiCell(18, $nHeight, 'Em Exercícios Anteriores', 1, 'C');

    $this->oPdf->setXY($iWidthPoder+28, $iAlturaCelula);
    $sDescricao = "Em 31 de Dezembro de " . ($this->oAnexo->getAno()-1);
    $nHeight = $this->oPdf->getMultiCellHeight(18, 3, $sDescricao);
    $this->oPdf->MultiCell(18, $nHeight, $sDescricao, 1, 'C');

    $this->oPdf->setXY($iWidthPoder+46, $iAlturaCelula);
    $this->oPdf->cell(18, 12, 'Pagos', 'LRB', 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(18, 12, 'Cancelados', 'LRB', 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(19, 6, "Saldo\n(a)", 'LRB', \PDFDocument::ALIGN_CENTER);

    $this->oPdf->setXY($iWidthPoder+101, $iAlturaCelula);
    $nHeight = $this->oPdf->getMultiCellHeight(18, self::ALTURA - 1, 'Em Exercícios Anteriores');
    $this->oPdf->MultiCell(18, $nHeight, 'Em Exercícios Anteriores', 1, 'C');

    $this->oPdf->setXY($iWidthPoder+119, $iAlturaCelula);
    $sDescricao = "Em 31 de Dezembro de " . ($this->oAnexo->getAno()-1);
    $nHeight = $this->oPdf->getMultiCellHeight(18, 3, $sDescricao);
    $this->oPdf->MultiCell(18, $nHeight, $sDescricao, 1, 'C');

    $this->oPdf->setXY($iWidthPoder+137, $iAlturaCelula);
    $this->oPdf->cell(18, 12, 'Liquidados', "LRB", 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(18, 12, 'Pagos',      "LRB", 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(18, 12, 'Cancelados', "LRB", 0, \PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(18, 6, "Saldo\n(b)", "BLR", \PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetXY($iWidthPoder+209, $iAlturaCelula);
    $this->oPdf->MultiCell(18, 6, "Saldo Total\n(a) + (b)", "BL", \PDFDocument::ALIGN_CENTER);
  }
}
