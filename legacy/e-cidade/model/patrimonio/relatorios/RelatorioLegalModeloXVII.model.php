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

class RelatorioLegalModeloXVII {

  /**
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var integer
   */
  private $iMes;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var string
   */
  private $sConsideracoes;

  /**
   * @param DBDepartamento $oDepartamento
   * @param integer $iMes
   * @param integer $iAno
   */
  public function __construct(DBDepartamento $oDepartamento, $iMes, $iAno) {

    $this->oDepartamento = $oDepartamento;
    $this->iMes = $iMes;
    $this->iAno = $iAno;
  }

  /**
   * @return string
   */
  public function getConsideracoes() {
    return $this->sConsideracoes;
  }

  /**
   * @param string $sConsideracoes
   */
  public function setConsideracoes($sConsideracoes) {
    $this->sConsideracoes = $sConsideracoes;
  }

  /**
   * Tamanho das colunas
   * @var array
   */
  private $aCells = array(
      0.12,
      0.1,
      0.4,
      0.1
    );

  /**
   * Gera o relatório
   */
  public function gerar() {

    $this->oPdf = new PDFDocument( PDFDocument::PRINT_LANDSCAPE );
    $this->oPdf->disableHeaderDefault();
    $this->oPdf->disableFooterDefault();
    $this->oPdf->SetAutoPageBreak(false, 8);
    $this->oPdf->open();
    $this->oPdf->addPage();

    $this->oPdf->setFontFamily('Arial');
    $this->oPdf->setFontSize(8);

    $this->escreveHeader();
    $this->escreveCabecalhoTabela();
    $this->escreveItens();

    $this->oPdf->showPDF("relatoriolegal_modelo17_" . time());
  }

  /**
   * Escreve o header do relatório
   */
  private function escreveHeader() {

    $this->oPdf->setFontSize(10);
    $this->oPdf->setBold(true);

    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($iLargura, 4, "MODELO 17", 0, 1, 'C');
    $this->oPdf->cell($iLargura, 4, '', 'R:T:L', 1);
    $this->oPdf->cell($iLargura, 5, "BENS PATRIMONIAIS", 'R:L', 1, 'C');

    $this->oPdf->setBold(false);
    $this->oPdf->cell($iLargura, 4, "TERMO DE BAIXA DEFINITIVA", 'R:L', 1, 'C');
    $this->oPdf->cell($iLargura, 6, '', 'R:B:L', 1);

    $this->oPdf->setFontSize(8);
    $this->oPdf->cell($iLargura*0.4, 5, "Órgão / Entidade", 'R:L', 0, 'L');
    $this->oPdf->cell($iLargura*0.3, 5, "Município", 'R:L', 0, 'L');
    $this->oPdf->cell($iLargura*0.3, 5, "Unidade de Controle", 'R:L', 1, 'L');

    $this->oPdf->cell($iLargura*0.4, 6, "  " . $this->oDepartamento->getInstituicao()->getDescricao(), 'R:B:L', 0, 'L');
    $this->oPdf->cell($iLargura*0.3, 6, "  " . $this->oDepartamento->getInstituicao()->getMunicipio(), 'R:B:L', 0, 'L');
    $this->oPdf->cell($iLargura*0.3, 6, "  " . $this->oDepartamento->getCodigo() . " - " . $this->oDepartamento->getNomeDepartamento(), 'R:B:L', 1, 'L');

    $this->oPdf->cell($iLargura, 4, '', 'R:L', 1);

    $sCompetencia = "Mês de " . DBDate::getMesExtenso($this->iMes) . " de " . $this->iAno;
    $this->oPdf->cell($iLargura, 4, $sCompetencia, 'L:R', 1, 'C');
    $this->oPdf->cell($iLargura, 2, '', 'R:L', 1);

    $nAltura = $this->oPdf->getMultiCellHeight($iLargura*0.9, 4, "Tendo em vista: " . $this->sConsideracoes);

    $this->oPdf->cell($iLargura*0.05, $nAltura, '', 'L', 0);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->multicell($iLargura*0.9, 4, "Tendo em vista: " . $this->sConsideracoes, 0, 'L');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->cell($iLargura*0.05, $nAltura, '', 'R', 1);

    $this->oPdf->cell($iLargura, 1, '', 'R:L', 1);
    $this->oPdf->cell($iLargura*0.05, 4, '', 'L', 0);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, 'Os bens abaixo relacionados foram definitivamente baixados do patrimônio.', 'R', 1);
    $this->oPdf->cell($iLargura, 1, '', 'R:B:L', 1);
  }

  private function escreveCabecalhoTabela() {

    $this->oPdf->setFontSize(9);
    $this->oPdf->setBold(true);

    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->multicell($iLargura*$this->aCells[0], 4, "Código de \nClassificação", 'T:B:L', 'C');
    $this->oPdf->cell($iLargura*$this->aCells[1], 8, "Nº de Inventário", 'T:B:L', 0, 'C');
    $this->oPdf->cell($iLargura*$this->aCells[2], 8, "Características de Identificação", 'T:B:L', 0, 'C');
    $this->oPdf->cell($iLargura*$this->aCells[3], 8, "Quantidade", 1, 0, 'C');

    $iLargura = $this->oPdf->getAvailWidth();

    $iX = $this->oPdf->getX();
    $this->oPdf->cell($iLargura, 4, "Valores", 'T:B:R', 1, 'C');
    $this->oPdf->setX($iX);

    $this->oPdf->cell($iLargura*0.5, 4, "Unitários", 'B:R', 0, 'C');
    $this->oPdf->cell($iLargura*0.5, 4, "Globais", 'B:R', 1, 'C');
  }

  private function escreveItens() {

    $oDaoBensBaixa = new cl_bensbaix();
    $sSqlBens = $oDaoBensBaixa->sql_query_relatorio_legal( null,
                                                           "t52_codcla, t52_descr, t52_valaqu, t52_ident",
                                                           "t52_ident",
                                                           "extract(month from t55_baixa) = {$this->iMes} and extract(year from t55_baixa) = {$this->iAno}"
                                                           . " and t52_depart = {$this->oDepartamento->getCodigo()}" );
    $rsBens   = $oDaoBensBaixa->sql_record( $sSqlBens );

    if ($oDaoBensBaixa->numrows < 1) {
      throw new Exception("Não existem dados para os filtros informados.");
    }

    $iLarguraPagina  = $this->oPdf->getAvailWidth();
    $iLarguraValores = $iLarguraPagina*(1-array_sum($this->aCells));


    for ($iRow = 0; $iRow < $oDaoBensBaixa->numrows; $iRow++) {

      $oBem = db_utils::fieldsMemory($rsBens, $iRow);

      $nAlturaLinha = $this->oPdf->getMultiCellHeight($iLarguraPagina*$this->aCells[2], 4, $oBem->t52_descr);

      if ($nAlturaLinha+20 > $this->oPdf->getAvailHeight()) {
        $this->adicionarPagina();
      }

      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->setBold(false);
      $this->oPdf->setFontSize(8);

      $this->oPdf->cell($iLarguraPagina*$this->aCells[0], $nAlturaLinha, $oBem->t52_codcla, 'L:R', 0, 'C');
      $this->oPdf->cell($iLarguraPagina*$this->aCells[1], $nAlturaLinha, $oBem->t52_ident, 'L:R', 0, 'C');
      $this->oPdf->multicell($iLarguraPagina*$this->aCells[2], 4, $oBem->t52_descr, "L:R", 'L');
      $this->oPdf->cell($iLarguraPagina*$this->aCells[3], $nAlturaLinha, '1', 'R:L', 0, 'C');

      $this->oPdf->cell($iLarguraValores*0.5, $nAlturaLinha, db_formatar($oBem->t52_valaqu, 'f'), 'R:L', 0, 'R');
      $this->oPdf->cell($iLarguraValores*0.5, $nAlturaLinha, db_formatar($oBem->t52_valaqu, 'f'), 'R:L', 1, 'R');
    }

    $this->oPdf->setAutoNewLineMulticell(true);

    $this->escreveAssinatura();
  }

  private function adicionarPagina() {

    $this->escreveAssinatura();

    $this->oPdf->addPage();
    $this->escreveCabecalhoTabela();
  }

  private function escreveAssinatura() {

    $iAvailHeight = $this->oPdf->getAvailHeight();
    if ($iAvailHeight > 20) {

      $iLarguraPagina  = $this->oPdf->getAvailWidth();
      $iLarguraValores = $iLarguraPagina*(1-array_sum($this->aCells));
      $nAlturaLinha    = $iAvailHeight-20;

      $this->oPdf->cell($iLarguraPagina*$this->aCells[0], $nAlturaLinha, '', 'L:B:R', 0);
      $this->oPdf->cell($iLarguraPagina*$this->aCells[1], $nAlturaLinha, '', 'L:B:R', 0);
      $this->oPdf->cell($iLarguraPagina*$this->aCells[2], $nAlturaLinha, '', 'L:B:R', 0);
      $this->oPdf->cell($iLarguraPagina*$this->aCells[3], $nAlturaLinha, '', 'R:B:L', 0);

      $this->oPdf->cell($iLarguraValores*0.5, $nAlturaLinha, '', 'R:B:L', 0);
      $this->oPdf->cell($iLarguraValores*0.5, $nAlturaLinha, '', 'R:B:L', 1);

      $this->oPdf->ln(2);
    }

    $oLibDocumento = new libdocumento(5019);
    $aParagrafos   = $oLibDocumento->getDocParagrafos();

    if (isset($aParagrafos[1])) {
      eval($aParagrafos[1]->oParag->db02_texto);
    }
  }
}