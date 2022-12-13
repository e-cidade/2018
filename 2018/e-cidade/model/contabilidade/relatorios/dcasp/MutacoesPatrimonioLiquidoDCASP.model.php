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

class MutacoesPatrimonioLiquidoDCASP extends RelatoriosLegaisBase {

  /**
   * C�digo da configura��o do relat�rio
   */
  const CODIGO_RELATORIO = 161;

  /**
   * Linha de saldos iniciais
   */
  const LINHA_SALDOS_INICIAIS = 1;

  /**
   * Linha de saldos finais
   */
  const LINHA_SALDOS_FINAIS = 10;

  /**
   *
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Largura m�xima das c�lulas
   *
   * @var integer
   */
  private $iLargura;

  /**
   * Altura padr�o das c�lulas
   *
   * @var integer
   */
  private $iAltura;

  /**
   * @type string
   */
  private $sDescricaoInstituicao;

  /**
   * @type $string
   */
  private $sDescricaoPeriodo;

  /**
   *
   * @param integer $iAno              Ano de emiss�o do relat�rio
   * @param integer $iCodigoRelatorio  C�digo do relat�rio
   * @param integer $iCodigoPeriodo    Codigo do per�odo de emiss�o do relat�rio
   */
  function __construct($iAno, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAno, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oPdf     = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->iAltura  = 4;
    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
  }

  /**
   * Popula os atributos que ser�o utilizados no cabe�alho para n�o precisar processa-los a cada p�gina.
   */
  private function preparaCabecalhos() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura                 = InstituicaoRepository::getInstituicaoPrefeitura();
      $this->sDescricaoInstituicao = "INSTITUI��O: {$oPrefeitura->getDescricao()} - CONSOLIDA��O";
    } else {

      $oInstituicao                = current($aListaInstituicoes);
      $this->sDescricaoInstituicao = "INSTITUI��O: {$oInstituicao->getDescricao()}";
    }

    $this->sDescricaoPeriodo = $this->getPeriodo()->getDescricao();
  }

  /**
   * Configura��o inicial da inst�ncia PDFDocument para emiss�o do relat�rio
   */
  private function configurarRelatorio() {

    $this->oPdf->Open();
    $this->oPdf->SetLeftMargin(10);
    $this->oPdf->SetAutoPageBreak(true, 10);
    $this->oPdf->SetFillcolor(235);
    $this->oPdf->SetFont('arial', '', 6);

    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("DEMONSTRA��O DAS MUTA��ES DO PATRIM�NIO L�QUIDO");
    $this->oPdf->addHeaderDescription("EXERC�CIO: {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PER�ODO: {$this->sDescricaoPeriodo}");

    $this->oPdf->AddPage();
  }

  /**
   * L�gica para escrever as linhas
   */
  private function escreverLinha(stdClass $oStdLinha) {

    $nTotalHorizontal = $oStdLinha->capital_social
                      + $oStdLinha->afac
                      + $oStdLinha->reserva_capital
                      + $oStdLinha->avaliacao_patrimonial
                      + $oStdLinha->reserva_lucros
                      + $oStdLinha->demais_reservas
                      + $oStdLinha->resultados_acumulados
                      + $oStdLinha->acoes_cotas_tesouraria;

    $this->oPdf->setAutoNewLineMulticell(false);

    $sIdentacao = str_repeat(" ", 3);
    if ($oStdLinha->ordem == self::LINHA_SALDOS_INICIAIS || $oStdLinha->ordem == self::LINHA_SALDOS_FINAIS) {
      $sIdentacao = "";
      $this->oPdf->setBold(true);
    }

    $this->oPdf->MultiCell($this->iLargura * 0.18, $this->iAltura, $sIdentacao.$oStdLinha->descricao, 'BR', 'L');

    $this->oPdf->MultiCell($this->iLargura * 0.11, $this->iAltura, db_formatar($oStdLinha->capital_social, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.12, $this->iAltura, db_formatar($oStdLinha->afac, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oStdLinha->reserva_capital, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.09, $this->iAltura, db_formatar($oStdLinha->avaliacao_patrimonial, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oStdLinha->reserva_lucros, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oStdLinha->demais_reservas, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oStdLinha->resultados_acumulados, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oStdLinha->acoes_cotas_tesouraria, 'f'), 'BR', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, db_formatar($nTotalHorizontal, 'f'), 'B', 'R');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->Ln($this->iAltura);

    if ($oStdLinha->ordem == self::LINHA_SALDOS_INICIAIS || $oStdLinha->ordem == self::LINHA_SALDOS_FINAIS) {
      $this->oPdf->setBold(false);
    }
  }

  /**
   * Escreve as assinaturas do quadro do relat�rio.
   *
   */
  private function escreveAssinatura() {

    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);
  }

  public function emitir() {

    $aDados = $this->getDados();

    $this->preparaCabecalhos();
    $this->configurarRelatorio();
    $iAlturaCabecalho = 3.2;

    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.18, $iAlturaCabecalho * 2, 'Especifica��o', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.11, $iAlturaCabecalho * 2, 'Pat. Social / Capital Social', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.12, $iAlturaCabecalho, 'Adiantamento para Futuro Aumento de Capital (AFAC)', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $iAlturaCabecalho * 2, 'Reserva de Capital', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.09, $iAlturaCabecalho, 'Ajustes de Avalia��o Patrimonial', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $iAlturaCabecalho * 2, 'Reservas de Lucros', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $iAlturaCabecalho * 2, 'Demais Reservas', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $iAlturaCabecalho, 'Resultados Acumulados', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $iAlturaCabecalho, 'A��es / Cotas em Tesouraria', 'TBR', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $iAlturaCabecalho * 2, 'TOTAL', 'TB', 'C');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->ln($iAlturaCabecalho * 2);
    $this->oPdf->setBold(false);

    foreach ($aDados as $oStdLinha) {
      $this->escreverLinha($oStdLinha);
    }

    $this->oPdf->Ln($this->iAltura);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
    $this->escreveAssinatura();

    $this->oPdf->showPDF("mutacoesPatrimonioLiquidoDCASP_" . time());
  }

}