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

class AnexoIDSICONFI extends RelatoriosLegaisBase implements AnexoSICONFI {

  /**
   * @type int
   */
  const CODIGO_RELATORIO = 156;

  const NOME_RELATORIO = "DCA Anexo I-D";

  /**
   * Ano para a emissão do relatório.
   * @var
   */
  protected $iAno;

  /**
   * @var int
   */
  protected $iMargem;

  /**
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * BalancoOrcamentarioSiconfi constructor.
   *
   * @param integer $iAnoUsu
   * @param int     $iCodigoRelatorio
   * @param int     $iCodigoPeriodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->iAno     = $iAnoUsu;
    $this->oPdf     = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->iAltura  = 4;
    $this->iMargem  = 10;
    $this->iLargura = $this->oPdf->getAvailWidth() - $this->iMargem;
  }

  /**
   * @param string $sTipo Tipo de arquivo a ser gerado.
   *
   * @return string Nome do arquivo gerado.
   * @throws Exception
   */
  public function gerar($sTipo) {

    switch ($sTipo) {

      case AnexoSICONFI::TIPO_CSV:
        $this->gerarCSV();
        break;
      case AnexoSICONFI::TIPO_PDF:
        $this->gerarPDF();
        break;
      default:
        throw new Exception("Opção inválida.");
        break;
    }
    return $this->sNomeArquivo;
  }

  /**
   * Gera relatório no formato PDF.
   */
  public function gerarPDF() {

    $aDados = $this->prepararDados();

    $oInstituicao = InstituicaoRepository::getInstituicaoPrefeitura();

    $aInstituicoes = $this->getInstituicoes(true);
    if (count($aInstituicoes) == 1) {
      $oInstituicao = current($aInstituicoes);
    }

    $this->oPdf->Open();
    $this->oPdf->addHeaderDescription($oInstituicao->getDescricao() . " - " . $oInstituicao->getUf());
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("Balanço Orçamentário (DCA) - Despesas Orçamentárias");
    $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");

    $this->novaPagina();

    foreach ($aDados as $oLinha) {
      $this->escreveLinhaPDF($oLinha, end($aDados) == $oLinha);
    }

    $this->sNomeArquivo = $this->oPdf->savePDF("SICONFI_anexo_id_BalancoOrcamentario");
  }

  /**
   * Gera relatório no formato CSV.
   */
  public function gerarCSV() {

    $aDados = $this->prepararDados();

    $sNomeArquivo = "tmp/SICONFI_anexo_id_BalancoOrcamentario.csv";
    if (file_exists($sNomeArquivo)) {
      unlink($sNomeArquivo);
    }

    $hArquivo = fopen($sNomeArquivo, "w+");
    if ($hArquivo === false) {
      throw new Exception("Não foi possível gerar o arquivo.");
    }

    $aColunas = array("DESPESAS ORÇAMENTÁRIAS", "DESPESAS EMPENHADAS", "DESPESAS LIQUIDADAS", "DESPESAS PAGAS", "INSCRIÇÃO DE RP NÃO PROCESSADOS", "INSCRIÇÃO DE RP PROCESSADOS");
    $this->escreverLinhaCSV($hArquivo, $aColunas);

    foreach ($aDados as $oLinha) {

      $sDescricao          = relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
      $nDespesasEmpenhadas = trim(db_formatar($oLinha->colunas->despesas_empenhadas, 'f'));
      $nDespesasLiquidadas = trim(db_formatar($oLinha->colunas->despesas_liquidadas, 'f'));
      $nDespesasPagas      = trim(db_formatar($oLinha->colunas->despesas_pagas, 'f'));
      $nRPNaoProcessado    = trim(db_formatar($oLinha->colunas->rp_nao_processado, 'f'));
      $nRPProcessado       = trim(db_formatar($oLinha->colunas->rp_processado, 'f'));

      $aLinha =  array($sDescricao, $nDespesasEmpenhadas, $nDespesasLiquidadas, $nDespesasPagas, $nRPNaoProcessado, $nRPProcessado);
      $this->escreverLinhaCSV($hArquivo, $aLinha);
    }

    fclose($hArquivo);
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * @param stdClass $oLinha
   */
  private function escreveLinhaPDF(stdClass $oLinha, $lUltimaLinha = false) {

    $lBold = $this->oPdf->getBold();

    $sBorda = '';
    if ($lUltimaLinha) {
      $sBorda = 'B';
    }
    $sDescricao          = relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
    $nDespesasEmpenhadas = db_formatar($oLinha->colunas->despesas_empenhadas, 'f');
    $nDespesasLiquidadas = db_formatar($oLinha->colunas->despesas_liquidadas, 'f');
    $nDespesasPagas      = db_formatar($oLinha->colunas->despesas_pagas, 'f');
    $nRPNaoProcessado    = db_formatar($oLinha->colunas->rp_nao_processado, 'f');
    $nRPProcessado       = db_formatar($oLinha->colunas->rp_processado, 'f');

    $nAltura = $this->oPdf->getMultiCellHeight($this->iLargura * 0.5, $this->iAltura, $sDescricao);

    if ($this->oPdf->getAvailHeight() < $nAltura) {
      $this->novaPagina();
    }

    if ($oLinha->totalizadora) {
      $this->oPdf->setBold(true);
    }

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.5, $this->iAltura, $sDescricao, $sBorda, 'L');
    $this->oPdf->setAutoNewLineMulticell(true);

    $this->oPdf->Cell($this->iLargura * 0.10, $nAltura, $nDespesasEmpenhadas, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $nAltura, $nDespesasLiquidadas, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $nAltura, $nDespesasPagas,      'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $nAltura, $nRPNaoProcessado,    'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $nAltura, $nRPProcessado,       'L' . $sBorda, 1, 'R');

    $this->oPdf->setBold($lBold);
  }

  /**
   * @param $hArquivo
   * @param $aLinha
   *
   * @throws Exception
   */
  private function escreverLinhaCSV($hArquivo, $aLinha) {

    $lEscreveu = fputcsv($hArquivo, $aLinha, ";");
    if ($lEscreveu === false) {
      throw new Exception("Não foi possível escrever no arquivo.");
    }
  }

  /**
   * Adiciona nova página.
   */
  public function novaPagina() {

    $this->oPdf->setAutoPageBreak(false, $this->iMargem);
    $this->oPdf->addPage();
    $this->oPdf->setFontSize(7);

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->setBold(true);

    $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 4, "DESPESAS ORÇAMENTÁRIAS", 'TLRB', 'C');
    $iPosicaoX = $this->oPdf->GetX();

    $this->oPdf->MultiCell($this->iLargura * 0.50, $this->iAltura * 2, "EXECUÇÃO DA DESPESA", 'TLRB', 'C');
    $this->oPdf->Ln($this->iAltura * 2);

    $this->oPdf->SetX($iPosicaoX);

    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "DESPESAS EMPENHADAS", 'TLRB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "DESPESAS LIQUIDADAS", 'TLRB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura * 2, "DESPESAS PAGAS", 'TLRB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "INSCRIÇÃO DE RP\nNÃO PROCESSADOS", 'TLRB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.10, $this->iAltura, "INSCRIÇÃO DE RP\nPROCESSADOS", 'TLRB', 'C');
    $this->oPdf->Ln($this->iAltura * 2);
    $this->oPdf->setBold(false);
    $this->oPdf->setAutoNewLineMulticell(true);

  }

  /**
   * @return stdClass[]
   */
  private function prepararDados() {

    $aLinhasRelatorio = parent::getDados();
    $aDadosRelatorio  = array();

    foreach ($aLinhasRelatorio as $oStdLinhaRelatorio) {

      $oStdLinha = new stdClass();
      $oStdLinha->descricao    = $oStdLinhaRelatorio->descricao;
      $oStdLinha->nivel        = $oStdLinhaRelatorio->nivel;
      $oStdLinha->totalizadora = $oStdLinhaRelatorio->totalizar;
      $oStdLinha->ordem        = $oStdLinhaRelatorio->ordem;
      $oStdLinha->colunas      = new stdClass;
      $oStdLinha->colunas->despesas_empenhadas = $oStdLinhaRelatorio->despemp;
      $oStdLinha->colunas->despesas_liquidadas = $oStdLinhaRelatorio->despliq;
      $oStdLinha->colunas->despesas_pagas      = $oStdLinhaRelatorio->pago;
      $oStdLinha->colunas->rp_nao_processado   = $oStdLinhaRelatorio->inscricao;
      $oStdLinha->colunas->rp_processado       = $oStdLinhaRelatorio->rp_apagar;

      $aDadosRelatorio[$oStdLinhaRelatorio->ordem] = $oStdLinha;
    }
    return $aDadosRelatorio;
  }
}
