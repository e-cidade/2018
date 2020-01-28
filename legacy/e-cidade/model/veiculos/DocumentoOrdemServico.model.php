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

require_once(modification('fpdf151/PDFDocument.php'));
require_once(modification('libs/db_libdocumento.php'));

/**
 * Class DocumentoOrdemServico
 */
class DocumentoOrdemServico {

  /**
   * @type VeiculoManutencao
   */
  private $oManutencao;

  /**
   * @type Instituicao
   */
  private $oInstituicao;

  /**
   * @type PDFDocument
   */
  private $oPdf;

  const ASSINATURA_PADRAO = 5020;

  public function __construct(VeiculoManutencao $oVeiculoManutencao, Instituicao $oInstituicao) {

    $this->oManutencao  = $oVeiculoManutencao;
    $this->oInstituicao = $oInstituicao;
  }


  public function emitir() {

    $this->oPdf = new PDFDocument("P");
    $this->oPdf->open();
    $this->oPdf->SetFontSize(7);
    $this->oPdf->addHeaderDescription($this->oInstituicao->getDescricao());
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("Ordem de Serviço: {$this->oManutencao->getNumero()}/{$this->oManutencao->getAno()}");
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("{$this->oInstituicao->getMunicipio()} - {$this->oInstituicao->getUf()}");
    $this->oPdf->addHeaderDescription($this->oManutencao->getDataInclusao()->getDate(DBDate::DATA_PTBR));
    $this->oPdf->addpage();
    $this->imprimeCabecalhoVeiculo();
    $this->imprimeInformacoesDaOrdem();
    $this->oPdf->ln(4);
    $this->imprimeItens();
    $this->imprimirAssinaturas();
    $this->oPdf->showPDF();
  }

  private function imprimeItens() {

    $this->imprimeCabecalhoItens();

    $aItens = $this->oManutencao->getItens();
    foreach ($aItens as $oItemManutencao) {

      if ($this->oPdf->getAvailHeight() <= 10) {
        $this->oPdf->addPage();
        $this->imprimeCabecalhoItens();
      }

      $sDescricao = $oItemManutencao->getDescricao();
      if ($oItemManutencao->getMaterial() instanceof MaterialCompras) {
        $sDescricao = urldecode($oItemManutencao->getMaterial()->getDescricao());
      }

      $nMulticellHeigth = $this->oPdf->getMultiCellHeight(85, 4, $sDescricao);

      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->multicell(85, 4, $sDescricao, 0, PDFDocument::ALIGN_LEFT);
      $this->oPdf->setAutoNewLineMulticell(true);
      $this->oPdf->cell(10, $nMulticellHeigth, $oItemManutencao->getUnidadeMaterial()->getAbreviatura(), 0, 0, PDFDocument::ALIGN_CENTER);
      $this->oPdf->cell(20, $nMulticellHeigth, $oItemManutencao->getQuantidade(), 0, 0, PDFDocument::ALIGN_CENTER);
      $this->oPdf->cell(20, $nMulticellHeigth, trim(db_formatar($oItemManutencao->getValorUnitario(), 'f')), 0, 0, PDFDocument::ALIGN_RIGHT);
      $this->oPdf->cell(25, $nMulticellHeigth, trim(db_formatar($oItemManutencao->getValorTotal(), 'f')), 0, 0, PDFDocument::ALIGN_RIGHT);
      $this->oPdf->cell(30, $nMulticellHeigth, trim(db_formatar($oItemManutencao->getValorTotalComDesconto(), 'f')), 0, 1, PDFDocument::ALIGN_RIGHT);
    }

    $oValorAtualizados = $this->oManutencao->getValoresAtualizados();

    $this->oPdf->ln(4);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(170, 4, "TOTAL EM PEÇAS: ", 0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(20, 4, trim(db_formatar($oValorAtualizados->getValorPecas(), 'f')), 0, 1, PDFDocument::ALIGN_RIGHT);

    $this->oPdf->ln(2);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(170, 4, "TOTAL EM MÃO DE OBRA: ", 0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(20, 4, trim(db_formatar($oValorAtualizados->getValorMaoDeObra(), 'f')), 0, 1, PDFDocument::ALIGN_RIGHT);

    $this->oPdf->ln(2);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(170, 4, "TOTAL EM LAVAGEM: ", 0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(20, 4, trim(db_formatar($oValorAtualizados->getValorLavagem(), 'f')), 0, 1, PDFDocument::ALIGN_RIGHT);

    $this->oPdf->ln(2);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(170, 4, "TOTAL GERAL: ", 0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(20, 4, trim(db_formatar($this->oManutencao->getValorTotalGeral(), 'f')), 0, 1, PDFDocument::ALIGN_RIGHT);

    if ($this->oPdf->getAvailHeight() < 30) {
      $this->oPdf->AddPage();
    }
  }

  private function imprimeCabecalhoItens() {

    $this->oPdf->setBold(true);
    $this->oPdf->cell(85, 4, 'DESCRIÇÃO',         0, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(10, 4, 'UNIDADE',           0, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(20, 4, 'QUANTIDADE',        0, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->cell(20, 4, 'VLR. UNITÁRIO',     0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(25, 4, 'VLR. TOTAL',        0, 0, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->cell(30, 4, 'TOTAL C/ DESCONTO', 0, 1, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->setBold(false);
  }

  private function imprimeCabecalhoVeiculo() {

    $this->oPdf->setBold(true);
    $this->oPdf->cell(190, 3, 'DADOS DO VEÍCULO', "B", 1, PDFDocument::ALIGN_LEFT);
    $this->oPdf->ln(2);
    $this->oPdf->cell(35, 4, 'VEÍCULO / PLACA:', 0, 0, PDFDocument::ALIGN_LEFT);
    $this->oPdf->setBold(false);
    $sVeiculoPlaca = "{$this->oManutencao->getVeiculo()->getModelo()} / {$this->oManutencao->getVeiculo()->getPlaca()}";
    $this->oPdf->cell(50, 4, $sVeiculoPlaca, 0, 0, PDFDocument::ALIGN_LEFT);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(20, 4, 'TOMBO:', 0, 0, PDFDocument::ALIGN_LEFT);
    $this->oPdf->setBold(false);
    $oBem = $this->oManutencao->getVeiculo()->getBem();
    $sPlaca = "";

    if ($oBem) {
      $sPlaca = $oBem->getIdentificacao();
    }
    $this->oPdf->cell(50, 4, $sPlaca, 0, 1, PDFDocument::ALIGN_LEFT);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(35, 4, 'MODELO / FABRICAÇÃO:', 0, 0, PDFDocument::ALIGN_LEFT);
    $this->oPdf->setBold(false);
    $sModeloFabricacao = "{$this->oManutencao->getVeiculo()->getAnoModelo()} / {$this->oManutencao->getVeiculo()->getAnoFabricacao()}";
    $this->oPdf->cell(50, 4, $sModeloFabricacao, 0, 0, PDFDocument::ALIGN_LEFT);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(20, 4, 'HODÔMETRO:', 0, 0, PDFDocument::ALIGN_LEFT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(50, 4, $this->oManutencao->getVeiculo()->getUltimaMedidaUso(), 0, 1, PDFDocument::ALIGN_LEFT);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(20, 4, 'MOTORISTA:', 0, 0, PDFDocument::ALIGN_LEFT);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(175, 4, $this->oManutencao->getMotorista()->getCGMMotorista()->getNome(), 0, 1, PDFDocument::ALIGN_LEFT);
  }


  private function imprimeInformacoesDaOrdem() {

    $this->oPdf->ln(4);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(190, 4, "ORDEM DE SERVIÇO", 'B', 1);
    $this->oPdf->setBold(false);

    $this->oPdf->ln(4);
    $sTextoAutorizacao = " a execução dos serviços a serem efetuados no veículo conforme orçamento anexo.";
    $this->oPdf->setBold(true);
    $this->oPdf->cell(14, 4, 'AUTORIZO', 0, 0);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(190, 4, $sTextoAutorizacao, 0, 1);

    $this->oPdf->setBold(true);
    $this->oPdf->ln(2);
    $this->oPdf->cell(45, 4, "VALOR TOTAL DO(S) SERVIÇO(S):", 0, 0);
    $this->oPdf->setBold(false);
    $nValorTotal = $this->oManutencao->getValorDePecas() + $this->oManutencao->getValorMaoDeObra();
    $this->oPdf->cell(155, 4, trim(db_formatar($nValorTotal, 'f')), 0, 1);

    $this->oPdf->setBold(true);
    $this->oPdf->ln(2);
    $this->oPdf->cell(15, 4, "EMPRESA:", 0, 0);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(175, 4, $this->oManutencao->getOficina()->getNome(), 0, 1);

    if ($this->oManutencao->getObservacao() != "") {

      $this->oPdf->setBold(true);
      $this->oPdf->ln(2);
      $this->oPdf->cell(20, 4, "OBSERVAÇÕES:", 0, 1);
      $this->oPdf->setBold(false);
      $this->oPdf->multicell(190, 4, $this->oManutencao->getObservacao(), 0, PDFDocument::ALIGN_JUSTIFY);
    }
  }

  private function imprimirAssinaturas() {

    $oAssinatura  = new libdocumento(self::ASSINATURA_PADRAO);
    $aAssinaturas = $oAssinatura->getDocParagrafos();
    $sAssinatura = "";
    foreach ($aAssinaturas as $oParagrafoAssinatura) {

      if (!empty($oParagrafoAssinatura->oParag->db02_texto) && $oParagrafoAssinatura->oParag->db04_ordem == $this->oInstituicao->getCodigo()) {
        $sAssinatura = $oParagrafoAssinatura->oParag->db02_texto;
      }
    }

    if (empty($sAssinatura)) {
      throw new BusinessException("Assinatura com código ".self::ASSINATURA_PADRAO." não localizada.");
    }
    eval($sAssinatura);
  }
}