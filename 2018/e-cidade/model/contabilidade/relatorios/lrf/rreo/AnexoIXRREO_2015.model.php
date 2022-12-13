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

/**
 * Demonstrativo das Receitas de Operações de Crédito e Despesas de Capital
 */
class AnexoIXRREO_2015 extends RelatoriosLegaisBase implements AnexoRREO {

  /**
   * @var integer
   */
  const CODIGO_RELATORIO = 159;

  /**
   *
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Largura da página
   *
   * @var integer
   */
  private $iLargura;

  /**
   * Altura das linhas
   *
   * @var integer
   */
  private $iAltura;

  /**
   *
   * @var array
   */
  private $aLinhas;

  const LINHA_RECEITAS        = 1;
  const LINHA_INICIO_DESPESAS = 2;
  const LINHA_FIM_DESPESAS    = 5;
  const LINHA_REGRA_OURO      = 6;

  /**
   *
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
    $this->oPdf     = new PDFDocument;
    $this->iAltura  = 3;
    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
  }

  private function configurarRelatorio() {

    $aListaInstituicoes = $this->getInstituicoes(true);
    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura           = InstituicaoRepository::getInstituicaoPrefeitura();
      $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oPrefeitura) . " - CONSOLIDAÇÃO";
    } else {

      $oInstituicao          = current($aListaInstituicoes);
      $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
    }
    $sDescricaoPeriodo = $this->getPeriodo()->getDescricao();

    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(false);
    $this->oPdf->SetFillColor(235);
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription($sDescricaoInstituicao);

    if (count($aListaInstituicoes) == 1) {

      if (current($aListaInstituicoes)->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription(current($aListaInstituicoes)->getDescricao());
      }
    }

    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DAS RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL");
    $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");
    $this->oPdf->AddPage("P");
    $this->oPdf->SetFont('arial', 'b', 5);
  }

  private function escreverReceitas() {

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.26, $this->iAltura * 3, 'RECEITAS', 'TBR', "C");
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 1.5, "PREVISÃO ATUALIZADA\n(a)", 'TBRL', "C");
    $this->oPdf->MultiCell($this->iLargura * 0.45, $this->iAltura * 1.5, "RECEITAS REALIZADAS\n(b)", 'TBRL', "C");
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 1.5, "SALDO NÃO REALIZADO\n(c) = (a - b)", 'TBL', 'C');

    $this->oPdf->setBold(false);
    $oLinha = $this->aLinhas[self::LINHA_RECEITAS];
    $this->oPdf->Cell($this->iLargura * 0.26, $this->iAltura, $oLinha->descricao, 'BR', 0, "L", 0);
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->prevatu, 'f'), 'BRL', 0, "R", 0);
    $this->oPdf->Cell($this->iLargura * 0.45, $this->iAltura, db_formatar($oLinha->recrealiza, 'f'), 'BRL', 0, "R", 0);
    $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
    $this->oPdf->ln(4);
  }

  private function escreverDespesas() {

    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.27, $this->iAltura * 3, 'DESPESAS', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.13, $this->iAltura * 1.5, "DOTAÇÃO ATUALIZADA\n(d)", 'TBRL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 1.5, "DESPESAS EMPENHADAS\n(e)", 'TBRL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.13, $this->iAltura * 3, 'DESPESAS LIQUIDADAS', 'TBRL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.18, $this->iAltura, "DESPESAS\nINSCRITAS EM RESTOS A PAGAR\nNÃO PROCESSADOS", 'TBRL', 'C');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 1.5, "SALDO NÃO EXECUTADO \n (f) = (d - e)", 'TBL', 'C');

    $this->oPdf->setBold(false);
    for ($iLinha = self::LINHA_INICIO_DESPESAS; $iLinha <= self::LINHA_FIM_DESPESAS; $iLinha++) {

      $oLinha = $this->aLinhas[$iLinha];
      $this->oPdf->Cell($this->iLargura * 0.27, $this->iAltura, $oLinha->descricao, 'BR', 0, "L", 0);
      $this->oPdf->Cell($this->iLargura * 0.13, $this->iAltura, db_formatar($oLinha->dot_atual, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->empenhado, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.13, $this->iAltura, db_formatar($oLinha->liquidado, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.18, $this->iAltura, db_formatar($oLinha->rp_nproc, 'f'), 'BRL', 0, "R", 0);
      $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
    }
    $this->oPdf->ln(4);
  }

  private function escreverRegraOuro() {

    $oLinha = $this->aLinhas[self::LINHA_REGRA_OURO];
    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.26, $this->iAltura * 1.5, $oLinha->descricao, 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 3, '(a - d)', 'TBRL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.14, $this->iAltura * 3, '(b - e)', 'TBRL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.13, $this->iAltura * 3, '-', 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.18, $this->iAltura * 3, '-', 'TBL', 'C');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($this->iLargura * 0.15, $this->iAltura * 3, '(c - f)', 'TBL', 'C');

    $this->oPdf->setBold(false);
    $this->oPdf->Cell($this->iLargura * 0.26, $this->iAltura, '', 'BR', 0, "L", 0);
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->dotatu, 'f'), 'BRL', 0, "R", 0);
    $this->oPdf->Cell($this->iLargura * 0.14, $this->iAltura, db_formatar($oLinha->despemp, 'f'), 'BRL', 0, "R", 0);
    $this->oPdf->Cell($this->iLargura * 0.13, $this->iAltura, '-', 'BRL', 0, 'C', 0);
    $this->oPdf->Cell($this->iLargura * 0.18, $this->iAltura, '-', 'BRL', 0, 'C', 0);
    $this->oPdf->Cell($this->iLargura * 0.15, $this->iAltura, db_formatar($oLinha->saldo, 'f'), 'BL', 1, "R", 0);
    $this->oPdf->ln(2);
  }

  /**
   * Emissão do relatório
   */
  public function emitir() {

    $this->aLinhas = $this->getDados();
    $this->configurarRelatorio();

    $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'RREO - ANEXO 9 (LRF, art.53, § 1o, inciso I)', 'B', 0, "L", 0);
    $this->oPdf->Cell($this->iLargura * 0.50, $this->iAltura, 'Em Reais', 'B', 1, "R", 0);

    $this->escreverReceitas();
    $this->escreverDespesas();
    $this->escreverRegraOuro();

    /**
     * Assinaturas e nota explicativa
     */
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->setBold(false);
    $iAlturaAssinatura = 26;
    $this->notaExplicativa($this->oPdf, array($this->oPdf, 'AddPage'), $iAlturaAssinatura);
    $this->oRelatorioLegal->assinatura($this->oPdf, 'LRF', false);

    $this->oPdf->showPDF("AnexoIXRREO_" . time());
  }

  /**
   * Retorna os dados para utilização no Demonstrativo Simplificado do RREO.
   *
   * @return stdClass
   */
  public function getDadosSimplificado() {

    $aLinhas = $this->getDados();

    $oReceitaOperacoesCredito = new stdClass;
    $oReceitaOperacoesCredito->nReceitasRealizadas = $aLinhas[self::LINHA_RECEITAS]->recrealiza;
    $oReceitaOperacoesCredito->nSaldoNaoRealizado  = $aLinhas[self::LINHA_RECEITAS]->saldo;
    $oReceitaOperacoesCredito->sDescricao          = 'Receita de Operação de Crédito';

    $oDespesaCapitalLiquida = new stdClass;
    $oDespesaCapitalLiquida->nDespesasEmpenhadas = $aLinhas[self::LINHA_FIM_DESPESAS]->empenhado;
    $oDespesaCapitalLiquida->nSaldoNaoExecutado  = $aLinhas[self::LINHA_FIM_DESPESAS]->saldo;
    $oDespesaCapitalLiquida->sDescricao          = 'Despesa de Capital Líquida';

    return array($oReceitaOperacoesCredito, $oDespesaCapitalLiquida);
  }

}
