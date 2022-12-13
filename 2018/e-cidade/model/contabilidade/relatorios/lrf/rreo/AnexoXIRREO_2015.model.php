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

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

class AnexoXIRREO_2015 extends RelatoriosLegaisBase implements AnexoRREO {

  /**
   * @var integer
   */
  const CODIGO_RELATORIO = 160;

  /**
   *
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Altura das linhas
   *
   * @var integer
   */
  private $iAltura;

  /**
   * Largura disponível
   *
   * @var integer
   */
  private $iLargura;

  /**
   * Linhas do relatório
   *
   * @var array
   */
  private $aLinhas;

  const LINHA_INICIO_RECEITAS  = 1;
  const LINHA_FIM_RECEITAS     = 3;
  const LINHA_INICIO_DESPESAS  = 4;
  const LINHA_FIM_DESPESAS     = 11;
  const LINHA_SALDO_FINANCEIRO = 12;

  /**
   *
   * @param integer $iAnoUsu
   * @param integer $iCodigoRelatorio
   * @param integer $iCodigoPeriodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * Quadro de receitas
   */
  private function escreverReceitas() {

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'RECEITAS', 'TBR', "C", 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "PREVISÃO ATUALIZADA\n(a)", 'TBRL', "C", 1);
    $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 1.5, "RECEITAS REALIZADAS\n(b)", 'TBRL', "C", 1);
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "SALDO A REALIZAR\n(c) = (a - b)", 'TBL', 'C', 1);

    $this->oPdf->setBold(false);
    for ($iLinha = self::LINHA_INICIO_RECEITAS; $iLinha <= self::LINHA_FIM_RECEITAS; $iLinha++) {

      $oLinha = $this->aLinhas[$iLinha];

      $sDescricao = relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
      $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $sDescricao, 'BR', 0, "L", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->prevatu, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, db_formatar($oLinha->recatebim, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
    }
    $this->oPdf->ln(4);
  }

  /**
   * Quadro de despesas
   */
  private function escreverDespesas() {

    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'DESPESAS', 'TBR', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "DOTAÇÃO ATUALIZADA\n(d)", 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 3, "DESPESAS EMPENHADAS", 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 3, 'DESPESAS LIQUIDADAS', 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "DESPESAS\nPAGAS\n(e)", 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 0.75, "DESPESAS\nINSCRITAS EM RESTOS A PAGAR\nNÃO PROCESSADOS", 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "PAGAMENTO DE RESTOS A PAGAR\n(f)", 'TBRL', 'C', 1);
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "SALDO A PAGAR \n(g) = (d - e)", 'TBL', 'C', 1);

    $this->oPdf->setBold(false);
    for ($iLinha = self::LINHA_INICIO_DESPESAS; $iLinha <= self::LINHA_FIM_DESPESAS; $iLinha++) {

      $oLinha = $this->aLinhas[$iLinha];
      $sDescricao = relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
      $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $sDescricao, 'BR', 0, "L", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->dot_atual, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->despemp, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->despliq, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->desppag, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->insc_rp_np, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->rp_apagar, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
    }
    $this->oPdf->ln(4);
  }

  /**
   * Quadro Saldo Financeiro a Aplicar
   */
  private function escreverSaldoFinanceiro() {

    $iAnoAnterior = $this->iAnoUsu - 1;
    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.30, $this->iAltura * 3, 'SALDO FINANCEIRO A APLICAR', 'TBR', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "{$iAnoAnterior}\n(h)", 'TBRL', 'C', 1);
    $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 1.5, "{$this->iAnoUsu}\n(i) = (Ib - (IIe+ IIf))", 'TBRL', 'C', 1);
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 1.5, "SALDO ATUAL\n(j) = (IIIh + IIIi)", 'TBL', 'C', 1);

    $oLinha = $this->aLinhas[self::LINHA_SALDO_FINANCEIRO];
    $this->oPdf->setBold(false);
    $this->oPdf->Cell($this->iLargura * 0.30, $this->iAltura, $oLinha->descricao, 'TBR', 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->vlrexanter, 'f'), 'TBRL', 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, db_formatar($oLinha->vlrexatual, 'f'), 'TBRL', 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'TBL', 1, 'R');
    $this->oPdf->ln(4);
  }

  /**
   * Emissão do relatório
   */
  public function emitir() {

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->iAltura = 4;
    $this->iLargura = $this->iLargura = $this->oPdf->getAvailWidth() - 10;

    $this->aLinhas = $this->getDados();
    $aListaInstituicoes = $this->getInstituicoes(true);
    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura           = InstituicaoRepository::getInstituicaoPrefeitura();
      $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oPrefeitura) . " - CONSOLIDAÇÃO";
    } else {

      $oInstituicao          = current($aListaInstituicoes);
      $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
    }

    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(false);
    $this->oPdf->SetFillColor(235);
    $this->oPdf->addHeaderDescription($sDescricaoInstituicao);

    if (count($aListaInstituicoes) == 1) {
      $oInstituicao = current($aListaInstituicoes);

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }

    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DA RECEITA DE ALIENAÇÃO DE ATIVOS E APLICAÇÃO DOS RECURSOS");
    $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");
    $this->oPdf->AddPage();
    $this->oPdf->SetFont('arial', 'b', 5);

    $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'RREO - ANEXO 11 (LRF, art. 53, § 1º, inciso III)', 'B', 0, "L", 0);
    $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'Em Reais', 'B', 1, "R", 0);

    $this->escreverReceitas();
    $this->escreverDespesas();
    $this->escreverSaldoFinanceiro();

    /**
     * Assinaturas e nota explicativa
     */
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->setBold(false);
    $iAlturaAssinatura = 26;

    $this->oPdf->SetAutoPageBreak(true, 10);
    $this->notaExplicativa($this->oPdf, array($this->oPdf, 'AddPage'), $iAlturaAssinatura);
    $this->oPdf->SetAutoPageBreak(false, 10);
    $this->oRelatorioLegal->assinatura($this->oPdf, 'LRF', false);

    $this->oPdf->showPDF("AnexoXIRREO_" . time());
  }

  /**
   * Retorna um array contendo um stdClass com as informaçoes para o relatorio simplificado
   * @return stdClass[]
   */
  public function getDadosSimplificado() {

    $this->getDados();

    $oStdReceita = new stdClass();
    $oStdReceita->sDescricao     = "Receita de Capital Resultante da Alienação de Ativos";
    $oStdReceita->nAteBimestre   = $this->aLinhasConsistencia[1]->recatebim;
    $oStdReceita->nSaldoRealizar = $this->aLinhasConsistencia[1]->saldo;

    $oStdDespesa = new stdClass();
    $oStdDespesa->sDescricao     = "Aplicação dos Recursos da Alienação de Ativos";
    $oStdDespesa->nAteBimestre   = $this->aLinhasConsistencia[4]->despemp;
    $oStdDespesa->nSaldoRealizar = $this->aLinhasConsistencia[4]->saldo;
    return array($oStdReceita, $oStdDespesa);
  }
}
