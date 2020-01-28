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

class AnexoIGSICONFI extends RelatoriosLegaisBase implements AnexoSICONFI {

  /**
   * @type int
   */
  const CODIGO_RELATORIO = 158;

  /**
   * @type string
   */
  const NOME_RELATORIO = "DCA Anexo I-G";

  /**
   * @type PDFDocument
   */
  private $oPdf;

  /**
   * @type File
   */
  private $oArquivoGerado;

  /**
   * Gera o relatório em um arquivo no formato informado por parâmetro.
   * @param string $sFormato
   * @return boolean true
   * @throws Exception
   */
  public function gerar($sFormato) {

    $this->prepararDados();
    switch ($sFormato) {

      case AnexoSICONFI::TIPO_PDF:
        $this->gerarPDF();
        break;

      case AnexoSICONFI::TIPO_CSV:
        $this->gerarCSV();
        break;

      default:
        throw new Exception("Formato {$sFormato} inválido.");
    }
    return $this->oArquivoGerado->getFilePath();
  }

  /**
   * Gera o relatório no formato CSV e salva o nome do arquivo gerado em um atributo.
   * @return void
   */
  public function gerarCSV() {

    $aRelatorioCSV = array(
      'Despesa Por Função',
      'RP Não Processado Pago',
      'RP Não Processado Cancelado',
      'RP Processado Pago',
      'RP Processado Cancelado'
    );

    $sNomeArquivo = str_replace(" ", '', "tmp/".self::NOME_RELATORIO.'.csv');
    $hArquivoCSV  = fopen($sNomeArquivo, 'w+');
    fputcsv($hArquivoCSV, $aRelatorioCSV, ';');
    foreach ($this->aLinhasConsistencia as $oStdLinha) {

      $sEspacamento = relatorioContabil::getIdentacao($oStdLinha->nivel);
      $aLinhaCSV = array(
        $sEspacamento . $oStdLinha->descricao,
        trim(db_formatar($oStdLinha->rp_naoproc_pago, 'f')),
        trim(db_formatar($oStdLinha->rp_naoproc_cancelado, 'f')),
        trim(db_formatar($oStdLinha->rp_proc_pago, 'f')),
        trim(db_formatar($oStdLinha->rp_proc_cancelado, 'f'))
      );
      fputcsv($hArquivoCSV, $aLinhaCSV, ';');
    }
    $this->oArquivoGerado = new File($sNomeArquivo);
  }

  /**
   * Gera o relatório no formato PDF e salva o nome do arquivo gerado em um atributo.
   * @return void
   */
  public function gerarPDF() {

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->oPdf->Open();
    $this->oPdf->addHeaderDescription("Balanço Orçamentário - Despesas por Função/Subfunção");
    $this->oPdf->addHeaderDescription('');
    $this->oPdf->addHeaderDescription("Instituições:");
    $aInstituicoes = $this->getInstituicoes(true);

    foreach ($aInstituicoes as $oInstituicao) {
      $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
    }

    $oPdf =& $this->oPdf;

    $this->oPdf->setHeader(function() use($oPdf) {

      $oPdf->setBold(true);
      $oPdf->SetFillColor(235);
      $oPdf->setAutoNewLineMulticell(false);
      $oPdf->MultiCell(100, 8, "Despesa Por Função", 1, PDFDocument::ALIGN_CENTER, 1);
      $nXMultiCell = $oPdf->getX();
      $oPdf->cell($oPdf->getAvailWidth(), 4, "Execução da Despesa", 1, 1, PDFDocument::ALIGN_CENTER, 1);

      $oPdf->setX($nXMultiCell);
      $oPdf->cell(45, 4, "RP Não Processado Pago", 1, 0, "C", 1);
      $oPdf->cell(45, 4, "RP Não Processado Cancelado", 1, 0, "C", 1);
      $oPdf->cell(45, 4, "RP Processado Pago", 1, 0, "C", 1);
      $oPdf->cell(42, 4, "RP Processado Cancelado", 1, 1, "C", 1);
      $oPdf->setBold(false);
    });

    $this->oPdf->AddPage();
    $this->oPdf->setFontSize(6);

    foreach ($this->aLinhasConsistencia as $oStdLinha) {

      if($oStdLinha->totalizar) {
        $this->oPdf->setBold(true);
      }
      $sEspacamento = relatorioContabil::getIdentacao($oStdLinha->nivel);
      $this->oPdf->cell(100, 4, $sEspacamento . $oStdLinha->descricao, "R", 0, "L");

      $nXMultiCell = $this->oPdf->getX();
      $this->oPdf->setX($nXMultiCell);

      $this->oPdf->cell(45, 4, db_formatar($oStdLinha->rp_naoproc_pago, 'f'),      'LR', 0, "R");
      $this->oPdf->cell(45, 4, db_formatar($oStdLinha->rp_naoproc_cancelado, 'f'), 'LR', 0, "R");
      $this->oPdf->cell(45, 4, db_formatar($oStdLinha->rp_proc_pago, 'f'),         'LR', 0, "R");
      $this->oPdf->cell(42, 4, db_formatar($oStdLinha->rp_proc_cancelado, 'f'),    'L' , 1, "R");
      $this->oPdf->setBold(false);
    }

    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "", 'T', 1, "R");

    $sNomeArquivo = str_replace(" ", '', self::NOME_RELATORIO);
    $this->oPdf->savePDF($sNomeArquivo);
    $this->oArquivoGerado = new File('tmp/'.$this->oPdf->getFileName().".pdf");
  }

  /**
   * @return bool
   * @throws Exception
   */
  private function prepararDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->processarTiposDeCalculo();
    $rsBuscaRestosExcetoIntraOrcamentario = $this->processarRestos("substr(o56_elemento, 4, 2) <> '91'");
    foreach ($this->aLinhasProcessarRestosPagar as $iLinha ) {

      if ($iLinha == 167) {
        continue;
      }
      $oLinha = $this->aLinhasConsistencia[$iLinha];
      $this->calcularValoresDaLinha($rsBuscaRestosExcetoIntraOrcamentario, $oLinha);
    }

    /**
     * Processa Valores Intra-Orçamentários
     */
    $rsBuscaRestosIntraOrcamentario = $this->processarRestos("substr(o56_elemento, 4, 2) = '91'");
    $oLinha = $this->aLinhasConsistencia[167];
    $this->calcularValoresDaLinha($rsBuscaRestosIntraOrcamentario, $oLinha);

    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);
    return true;
  }

  /**
   * @param resource $rsRestosAPagar
   * @param stdClass $oLinha
   */
  private function calcularValoresDaLinha($rsRestosAPagar, stdClass $oLinha) {

    $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
    RelatoriosLegaisBase::calcularValorDaLinha($rsRestosAPagar,
                                               $oLinha,
                                               $aColunasProcessar,
                                               RelatoriosLegaisBase::TIPO_CALCULO_RESTO);
  }

  /**
   * @param $sWhere
   * @return bool|resource
   * @throws Exception
   */
  private function processarRestos($sWhere) {

    $oDaoRestosAPagar  = new cl_empresto();
    $sSqlRestosaPagar  = $oDaoRestosAPagar->sql_rp_novo($this->iAnoUsu,
                                                        "e60_instit in ({$this->getInstituicoes()})",
                                                        $this->getDataInicial()->getDate(),
                                                        $this->getDataFinal()->getDate(),
                                                        null,
                                                        " where {$sWhere} ");
    $rsBuscaRestos = db_query($sSqlRestosaPagar);
    if (!$rsBuscaRestos) {
      throw new Exception("Ocorreu um erro ao buscar as informações dos empenhos de restos a pagar.");
    }
    return $rsBuscaRestos;
  }

  /**
   * @return File
   */
  public function getArquivo() {
    return $this->oArquivoGerado;
  }
}