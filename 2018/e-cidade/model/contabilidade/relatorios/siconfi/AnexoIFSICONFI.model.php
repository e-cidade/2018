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

class AnexoIFSICONFI extends RelatoriosLegaisBase implements AnexoSICONFI {

  const CODIGO_RELATORIO = 157;
  const NOME_RELATORIO = "DCA Anexo I-F";

  /**
   * @var array
   */
  private $aLinhasFormatadas = array();

  /**
   * Gera o relatório em um arquivo no formato informado por parâmetro.
   *
   * @param string $sFormato Formado do arquivo a ser gerado.
   * @return string Nome do arquivo gerado.
   */
  public function gerar($sFormato) {

    switch ($sFormato) {

      case AnexoSICONFI::TIPO_CSV:
        return $this->gerarCSV();
        break;

      case AnexoSICONFI::TIPO_PDF:
        return $this->gerarPDF();
        break;

      default:
        throw new Exception("Opção inválida.");
        break;
    }
  }

  public function getDados() {
    parent::getDados();

    foreach ($this->aLinhasConsistencia as $oStdLinhaRelatorio) {

      $oStdLinha = new stdClass();
      $oStdLinha->descricao    = $oStdLinhaRelatorio->descricao;
      $oStdLinha->nivel        = $oStdLinhaRelatorio->nivel;
      $oStdLinha->totalizadora = $oStdLinhaRelatorio->totalizar;
      $oStdLinha->ordem        = $oStdLinhaRelatorio->ordem;
      $oStdLinha->colunas      = (object) array(
          'pagos_nao_proc' => $oStdLinhaRelatorio->rp_naoproc_pago,
          'anulados_nao_proc' => $oStdLinhaRelatorio->rp_naoproc_cancelado,
          'pagos_proc' => $oStdLinhaRelatorio->rp_proc_pago,
          'anulados_proc' => $oStdLinhaRelatorio->rp_proc_cancelado
        );

      $this->aLinhasFormatadas[$oStdLinha->ordem] = $oStdLinha;
    }

    return $this->aLinhasFormatadas;
  }

  /**
   * Gera o relatório no formato CSV e salva o nome do arquivo gerado em um atributo.
   * @return String Nome do arquivo
   */
  public function gerarCSV() {

    $this->getDados();

    $sNomeArquivo = "tmp/SICONFI_anexo_if_BalancoOrcamentario.csv";

    if (file_exists($sNomeArquivo)) {
      unlink($sNomeArquivo);
    }

    if (($hArquivo = fopen($sNomeArquivo, "w+")) === false)  {
      throw new Exception("Não foi possível gerar o arquivo.");
    }

    $aColunas = array( "DESPESAS ORÇAMENTÁRIAS",
                       "RESTOS A PAGAR NÃO PROCESSADOS PAGOS",
                       "RESTOS A PAGAR NÃO PROCESSADOS CANCELADOS",
                       "RESTOS A PAGAR PROCESSADOS PAGOS",
                       "RESTOS A PAGAR PROCESSADOS CANCELADOS" );

    if (fputcsv($hArquivo, $aColunas, ";") === false) {
      throw new Exception("Não foi possível escrever no arquivo.");
    }

    foreach ($this->aLinhasFormatadas as $oLinha) {

      $aLinha = array(
          relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao,
          trim(db_formatar($oLinha->colunas->pagos_nao_proc, 'f')),
          trim(db_formatar($oLinha->colunas->anulados_nao_proc, 'f')),
          trim(db_formatar($oLinha->colunas->pagos_proc, 'f')),
          trim(db_formatar($oLinha->colunas->anulados_proc, 'f'))
        );

      if (fputcsv($hArquivo, $aLinha, ";") === false) {
        throw new Exception("Não foi possível escrever no arquivo.");
      }
    }

    fclose($hArquivo);
    return $sNomeArquivo;
  }

  /**
   * Gera o relatório no formato PDF e salva o nome do arquivo gerado em um atributo.
   * @return String Nome do arquivo
   */
  public function gerarPDF() {

    $this->getDados();

    $oInstituicao = InstituicaoRepository::getInstituicaoPrefeitura();

    $aInstituicoes = $this->getInstituicoes(true);
    if (count($aInstituicoes) == 1) {
      $oInstituicao = current($aInstituicoes);
    }

    $oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $oPdf->open();
    $oPdf->addHeaderDescription($oInstituicao->getDescricao() . " - " . $oInstituicao->getUf());
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription("BALANÇO ORÇAMENTÁRIO (DCA) - EXECUÇÃO DOS RESTOS A PAGAR");
    $oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");
    $oPdf->setAutoPageBreak(true, 10);
    $oPdf->setFontSize(7);

    $oPdf->setHeader(function() use($oPdf) {

      $oPdf->setBold(true);
      $oPdf->setAutoNewLineMulticell(false);

      $oPdf->cell($oPdf->getAvailWidth()-160, 16, "DESPESAS ORÇAMENTÁRIAS", 1, 0, 'C');
      $iX = $oPdf->getX();
      $oPdf->cell($oPdf->getAvailWidth(), 8, "EXECUÇÃO DA DESPESA", 1, 1, 'C');
      $oPdf->setX($iX);
      $oPdf->multiCell(40, 4, "RESTOS A PAGAR NÃO PROCESSADOS PAGOS", 1, 'C');
      $oPdf->multiCell(40, 4, "RESTOS A PAGAR NÃO PROCESSADOS CANCELADOS", 1, 'C');
      $oPdf->multiCell(40, 4, "RESTOS A PAGAR PROCESSADOS PAGOS", 1, 'C');
      $oPdf->setAutoNewLineMulticell(true);
      $oPdf->multiCell(40, 4, "RESTOS A PAGAR PROCESSADOS CANCELADOS", 1, 'C');
    });

    $oPdf->addPage();

    foreach($this->aLinhasFormatadas as $oLinha) {

      $oPdf->setBold($oLinha->totalizadora);

      $sDescricao   = relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao;
      $iWidthColumn = $oPdf->getAvailWidth()-160;
      $iHeightConta = $oPdf->getMultiCellHeight($iWidthColumn, 4, $sDescricao);

      if ($oPdf->getAvailHeight()-$iHeightConta <= 0) {
        $oPdf->addPage();
      }

      $oPdf->setAutoNewLineMulticell(false);
      $oPdf->multiCell($iWidthColumn, 4, $sDescricao, 'R', 'L');
      $oPdf->cell(40, $iHeightConta, trim(db_formatar($oLinha->colunas->pagos_nao_proc, 'f')), 'R', 0, 'R');
      $oPdf->cell(40, $iHeightConta, trim(db_formatar($oLinha->colunas->anulados_nao_proc, 'f')), 'R', 0, 'R');
      $oPdf->cell(40, $iHeightConta, trim(db_formatar($oLinha->colunas->pagos_proc, 'f')), 'R', 0, 'R');
      $oPdf->cell(40, $iHeightConta, trim(db_formatar($oLinha->colunas->anulados_proc, 'f')), 0, 1, 'R');
    }

    $oPdf->line($oPdf->getX(), $oPdf->getY(), $oPdf->getX()+$oPdf->getAvailWidth(), $oPdf->getY());

    return $oPdf->savePDF("SICONFI_anexo_if_BalancoOrcamentario");
  }
}
