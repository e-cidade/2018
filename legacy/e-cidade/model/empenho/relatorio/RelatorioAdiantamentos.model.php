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

require_once "fpdf151/PDFDocument.php";
require_once "fpdf151/PDFTable.php";

class RelatorioAdiantamentos {

  const MODELO_ADIANTAMENTOS_CONCEDIDOS = 2;
  const MODELO_SUBVENCOES_AUXILIOS = 3;

  /**
   * @var DBDate
   */
  private $oDataInicio;

  /**
   * @var DBDate
   */
  private $oDataFim;

  /**
   * @var DBDate
   */
  private $oDataRemessa;

  /**
   * @var PDFTable
   */
  private $oPdf;

  /**
   * @var integer
   */
  private $iModelo = null;

  /**
   * @var Instituicao
   */
  private $oInstituicao = null;

  /**
   * @var integer
   */
  private $iExercicio = null;

  /**
   * Seta a Data da remessa
   * @param DBDate $oDataRemessa
   */
  public function setDataRemessa(DBDate $oDataRemessa) {
    $this->oDataRemessa = $oDataRemessa;
  }

  /**
   * Seta o Exercício
   * @param integer $iExercicio
   */
  public function setExercicio($iExercicio) {
    $this->iExercicio = $iExercicio;
  }

  /**
   * @param DBDate  $oDataInicio
   * @param DBDate  $oDataFim
   * @param integer $iModelo
   */
  public function __construct(DBDate $oDataInicio, DBDate $oDataFim, Instituicao $oInstituicao, $iModelo) {

    if ($iModelo != self::MODELO_SUBVENCOES_AUXILIOS && $iModelo != self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
      throw new Exception("Modelo informado é inválido.");
    }

    $this->oDataInicio  = $oDataInicio;
    $this->oDataFim     = $oDataFim;
    $this->oInstituicao = $oInstituicao;
    $this->iModelo      = $iModelo;
  }

  public function gerar() {

    $this->oPdf = new PDFTable();

    $oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $oPdf->disableHeaderDefault();
    $oPdf->disableFooterDefault();
    $oPdf->SetFillColor(255);
    $oPdf->open();
    $oPdf->SetAutoPageBreak(false, 25);
    $oPdf->setFileName("modelo_{$this->iModelo}_" . time());

    $oInstituicao =& $this->oInstituicao;
    $iExercicio   =& $this->iExercicio;
    $iModelo      =& $this->iModelo;

    /**
     * Seta o header do relatório
     */
    $fHeader = function($oPdf) use($oInstituicao, $iExercicio, $iModelo) {

      $sTitulo = ($iModelo == RelatorioAdiantamentos::MODELO_ADIANTAMENTOS_CONCEDIDOS ? "DOS ADIANTAMENTOS CONCEDIDOS" : "DAS SUBVENÇÕES E AUXÍLIOS");

      $oPdf->setBold(true);
      $oPdf->setFontSize(9);
      $oPdf->cell($oPdf->getAvailWidth(), 5, "MODELO {$iModelo}", 0, 1, PDFDocument::ALIGN_CENTER);

      $oPdf->setFontSize(10);
      $oPdf->cell($oPdf->getAvailWidth(), 13, "DEMONSTRATIVO {$sTitulo}", 1, 1, PDFDocument::ALIGN_CENTER);

      $oPdf->setBold(false);
      $oPdf->setFontSize(8);

      $iWidth = $oPdf->getAvailWidth();
      $oPdf->cell($iWidth*0.45, 5, "Órgão / Entidade / Fundo", "R:L", 0);
      $oPdf->cell($iWidth*0.30, 5, "Município", "R:L", 0);
      $oPdf->cell($iWidth*0.25, 5, "Exercício", "R:L", 1);

      $oPdf->setBold(true);
      $oPdf->cell($iWidth*0.45, 5, $oInstituicao->getDescricao(), "R:L:B", 0);
      $oPdf->cell($iWidth*0.30, 5, $oInstituicao->getMunicipio(), "R:L:B", 0);
      $oPdf->cell($iWidth*0.25, 5, $iExercicio, "R:L:B", 1);
      $oPdf->cell($iWidth, 4, '', "R:L:B", 1);

      /**
       * Imprime o header da tabela
       */
      $oPdf->setFontSize(7);

      if ($iModelo == RelatorioAdiantamentos::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $oPdf->cell($iWidth*0.27, 12, "RESPONSÁVEL", "R:L:B", 0, PDFDocument::ALIGN_CENTER);
      } else {
        $oPdf->cell($iWidth*0.36, 12, "ENTIDADE BENEFICIADA", "R:L:B", 0, PDFDocument::ALIGN_CENTER);
      }

      $oPdf->setAutoNewLineMulticell(false);

      $iX = $oPdf->getX();
      $oPdf->cell($iWidth*0.27, 4, "CONCESSÃO", "B:R", 1, PDFDocument::ALIGN_CENTER);
      $oPdf->setX($iX);

      $oPdf->multiCell(($iWidth*0.27)/3, 4, "VALOR\nCONCEDIDO R$", "B:R", PDFDocument::ALIGN_CENTER);
      $oPdf->cell(($iWidth*0.27)/3, 8, "PROCESSO Nº", "B:R", 0, PDFDocument::ALIGN_CENTER);

      if ($iModelo == RelatorioAdiantamentos::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $oPdf->cell(($iWidth*0.27)/3, 8, "DATA", "B:R", 0, PDFDocument::ALIGN_CENTER);
      } else {
        $oPdf->multiCell(($iWidth*0.27)/3, 4, "DATA DO\nPAGAMENTO", "B:R", PDFDocument::ALIGN_CENTER);
      }

      $iX = $oPdf->getX();
      $oPdf->setY($oPdf->getY() - 4);
      $oPdf->setX($iX);

      if ($iModelo == RelatorioAdiantamentos::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $oPdf->multiCell($iWidth*0.09, 6, "DATA LIMITE\nPARA APLICAÇÃO", "B:R", PDFDocument::ALIGN_CENTER);
      }

      $iX = $oPdf->getX();
      $oPdf->cell($iWidth*0.18, 4, "COMPROVAÇÃO", "B:R", 1, PDFDocument::ALIGN_CENTER);
      $oPdf->setX($iX);

      $oPdf->cell(($iWidth*0.18)/2, 8, "PROCESSO Nº", "B:R", 0, PDFDocument::ALIGN_CENTER);
      $oPdf->cell(($iWidth*0.18)/2, 8, "DATA", "B:R", 0, PDFDocument::ALIGN_CENTER);

      $iX = $oPdf->getX();
      $oPdf->setY($oPdf->getY() - 4);
      $oPdf->setX($iX);

      $oPdf->multiCell($iWidth*0.09, 6, "DATA DA\nAPROVAÇÃO", "B:R", PDFDocument::ALIGN_CENTER);

      if ($iModelo == RelatorioAdiantamentos::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $oPdf->cell($iWidth*0.10, 12, "OBSERVAÇÕES", "B:R", 1, PDFDocument::ALIGN_CENTER);
      } else {

        $oPdf->setAutoNewLineMulticell(true);
        $oPdf->multiCell($iWidth*0.10, 4, "DATA DA\nREMESSA DA\nP.C AO TCE-RJ", "B:R", PDFDocument::ALIGN_CENTER);
      }

      $oPdf->setBold(false);
    };

    $oPdf->setHeader($fHeader);

    /**
     * Imprime o footer do relatório
     */
    $fFooter = function($oPdf) {

      $iAvailHeight = $oPdf->getAvailHeight()+25;
      if ($iAvailHeight > 25) {
        $oPdf->setY($oPdf->getY() + $iAvailHeight - 25);
      }

      $oLibDocumento = new libdocumento(5016);
      $aParagrafos   = $oLibDocumento->getDocParagrafos();

      if (isset($aParagrafos[1])) {
        eval($aParagrafos[1]->oParag->db02_texto);
      }
    };

    $oPdf->setFooter($fFooter);

    /**
     * Seta os Tamanhos das colunas
     */
    $iWidth = $oPdf->getAvailWidth();

    $aTamanhos = array();
    $aTamanhos[] = ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS ? 27 : 36);
    $aTamanhos[] = 9;
    $aTamanhos[] = 9;
    $aTamanhos[] = 9;

    if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
      $aTamanhos[] = 9;
    }

    $aTamanhos[] = 9;
    $aTamanhos[] = 9;
    $aTamanhos[] = 9;
    $aTamanhos[] = 10;

    $this->oPdf->setPercentWidth(true);
    $this->oPdf->setColumnsWidth($aTamanhos);

    /**
     * Seta os Alinhamentos
     */
    $aAlinhamentos = array();
    $aAlinhamentos[] = PDFDocument::ALIGN_LEFT;
    $aAlinhamentos[] = PDFDocument::ALIGN_RIGHT;
    $aAlinhamentos[] = PDFDocument::ALIGN_LEFT;
    $aAlinhamentos[] = PDFDocument::ALIGN_CENTER;

    if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
      $aAlinhamentos[] = PDFDocument::ALIGN_CENTER;
    }

    $aAlinhamentos[] = PDFDocument::ALIGN_LEFT;
    $aAlinhamentos[] = PDFDocument::ALIGN_CENTER;
    $aAlinhamentos[] = PDFDocument::ALIGN_CENTER;

    if ($iModelo == self::MODELO_SUBVENCOES_AUXILIOS) {
      $aAlinhamentos[] = PDFDocument::ALIGN_CENTER;
    } else {
      $aAlinhamentos[] = PDFDocument::ALIGN_LEFT;
    }

    $this->oPdf->setColumnsAlign($aAlinhamentos);

    /**
     * Seta as colunas que terão Multicell
     */
    $aMulticell = array(0);

    if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
      $aMulticell[] = 8;
    }

    $this->oPdf->setMulticellColumns($aMulticell);

    /**
     * Formatação
     */
    $this->oPdf->addFormatting(1, PDFTable::FORMAT_NUMERIC);
    $this->oPdf->addFormatting(3, PDFTable::FORMAT_DATE);

    if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {

      $this->oPdf->addFormatting(4, PDFTable::FORMAT_DATE);
      $this->oPdf->addFormatting(7, PDFTable::FORMAT_DATE);
    } else {
      $this->oPdf->addFormatting(5, PDFTable::FORMAT_DATE);
    }

    $this->oPdf->addFormatting(6, PDFTable::FORMAT_DATE);

    /**
     * Imprime os dados
     */
    $rsDados = $this->getDados();
    for ($iRow = 0; $iRow < pg_num_rows($rsDados); $iRow++) {

      $oDados      = db_utils::fieldsMemory($rsDados, $iRow);
      $aDadosLinha = array();

      if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $aDadosLinha[] = "Nome: {$oDados->z01_nome}\nMatrícula: " . db_formatar($oDados->z01_cgccpf, 'cpf');
      } else {
        $aDadosLinha[] = $oDados->z01_nome;
      }

      $aDadosLinha[] = $oDados->valor;
      $aDadosLinha[] = $oDados->processo_autorizacao;
      $aDadosLinha[] = $oDados->data_pagamento;

      if ($iModelo == self::MODELO_ADIANTAMENTOS_CONCEDIDOS) {
        $aDadosLinha[] = $oDados->data_aplicacao;
      }

      $aDadosLinha[] = $oDados->processo_prestacao;
      $aDadosLinha[] = $oDados->e45_acerta;
      $aDadosLinha[] = $oDados->e45_conferido;

      if ($iModelo == self::MODELO_SUBVENCOES_AUXILIOS) {
        $aDadosLinha[] = $this->oDataRemessa->getDate(DBDate::DATA_PTBR);
      } else {
        $aDadosLinha[] = substr($oDados->e45_obs, 0, 100);
      }

      $this->oPdf->addLineInformation($aDadosLinha);
    }

    $this->oPdf->printOut($oPdf);
  }

  /**
   * @return bool|resource
   * @throws \Exception
   */
  private function getDados() {

    $oDaoEmppresta = new cl_emppresta();

    $sCampos  = "z01_nome, z01_cgccpf, e53_valor as valor, e150_numeroprocesso as processo_autorizacao, c80_data as data_pagamento, ";
    $sCampos .= "e45_datalimiteaplicacao as data_aplicacao, e45_processoadministrativo as processo_prestacao, e45_acerta, e45_conferido, e45_obs";

    $sWhere  = "e71_anulado is false and e44_naturezaevento = {$this->iModelo} and e60_instit = {$this->oInstituicao->getCodigo()}";
    $sWhere .= " and e45_conferido between '{$this->oDataInicio->getDate()}' and '{$this->oDataFim->getDate()}'";
    $sWhere .= " and c53_tipo = 30 ";

    $sSql    = $oDaoEmppresta->sql_query_nota_empenho(null, $sCampos, 'c80_data', $sWhere);
    $rsDados = $oDaoEmppresta->sql_record($sSql);

    if ($oDaoEmppresta->erro_status == "0") {
      throw new Exception("Erro ao buscar os dados.");
    }
    return $rsDados;
  }
}