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
class RelatorioControleHodometro {

  /**
   * Nome do arquivo criado na emissão CSV.
   * @var string
   */
  private $sNomeArquivo;

  /**
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * @var integer[]
   */
  private $aVeiculos;

  /**
   * integer[]
   */
  private $aDias;

  /**
   * @var File
   */
  private $oArquivo;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * Objeto para controle da emissão PDF.
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Largura total disponível da página na emissão PDF.
   * @var number
   */
  private $iLargura;

  /**
   * Altura padrão da linha na emissão PDF.
   * @var number
   */
  private $iAltura;

  /**
   * Indica se deve quebrar a linha para cada veículo na emissão PDF.
   * @var boolean
   */
  private $lQuebrarLinha;

  /**
   * Indica se o número de colunas é impar na emissão PDF.
   * @var boolean
   */
  private $lColunasImpares;

  /**
   * Indica a multiplicidade da largura de cada coluna na emissão PDF.
   * @var boolean
   */
  private $nLarguraColuna;

  /**
   * Indica a multiplicidade da altura da coluna descrição na emissão PDF.
   * @var boolean
   */
  private $nAlturaDescricao;

  /**
   * Indica em qual coluna deve haver a quebra de linha na emissão PDF;
   * @var boolean
   */
  private $iColunaMeio;

  /**
   * @type int
   */
  private $iTotalDias;

  /**
   * @param DBDate         $oDataInicial
   * @param DBDate         $oDataFinal
   * @param Instituicao    $oInstituicao
   * @param DBDepartamento $oDepartamento
   */
  public function __construct(DBDate $oDataInicial, DBDate $oDataFinal, Instituicao $oInstituicao, DBDepartamento $oDepartamento) {

    $this->oDataInicial  = $oDataInicial;
    $this->oDataFinal    = $oDataFinal;
    $this->oInstituicao  = $oInstituicao;
    $this->oDepartamento = $oDepartamento;

    $this->iTotalDias = DBDate::getIntervaloEntreDatas($oDataInicial, $oDataFinal)->days;
  }

  /**
   * @param $aVeiculos
   */
  public function setVeiculos($aVeiculos) {
    $this->aVeiculos = $aVeiculos;
  }

  /**
   * @return File
   */
  public function getArquivo() {
    return $this->oArquivo;
  }

  /**
   * Gera o arquivo CSV com o relatório de controle de hodômetro, que pode ser acessado pelo atributo oArquivo.
   */
  public function emitirCsv() {

    $sPeriodo = $this->oDataInicial->getDate("d_m_Y") . "_a_" . $this->oDataFinal->getDate("d_m_Y") . "_";
    $this->sNomeArquivo  = "relatorio_controle_hodometro_" . $sPeriodo . time() . ".csv";

    $aLinhas = $this->getDados();

    $sDiretorioArquivo = "tmp/";
    if (file_exists($sDiretorioArquivo . $this->sNomeArquivo)) {
      unlink($sDiretorioArquivo . $this->sNomeArquivo);
    }
    $hArquivo = fopen($sDiretorioArquivo . $this->sNomeArquivo, 'w');

    $aLinha = $this->getCabecalhoTabela();
    $this->escreverNoArquivo($hArquivo, $aLinha);

    foreach ($aLinhas as $oLinha) {

      $aLinha   = array();
      $aLinha[] = "{$oLinha->modelo} / {$oLinha->placa}";
      $nValorAnterior = 0;
      foreach ($oLinha->dias as $nValor) {

        $sValor = "";
        if ($nValorAnterior < $nValor) {

          $sValor = db_formatar($nValor, 'f');
          $nValorAnterior = $nValor;
        }
        $aLinha[] = $sValor;
      }
      $this->escreverNoArquivo($hArquivo, $aLinha);
    }

    fclose($hArquivo);
    $this->oArquivo = new File($sDiretorioArquivo . $this->sNomeArquivo);
  }

  /**
   * Emite o relatório em PDF.
   * @throws Exception
   */
  public function emitirPdf() {

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $aLinhas = $this->getDados();
    $this->configuraPdf();

    /**
     * Imprime as linhas para cada veículo.
     */
    foreach ($aLinhas as $oLinha) {

      if ($this->oPdf->getAvailHeight() < $this->iAltura * $this->nAlturaDescricao + 8) {
        $this->adicionarPagina();
      }

      $iTamanhoLinha = $this->oPdf->getMultiCellHeight($this->iLargura * $this->nLarguraColuna * 2, ($this->iAltura * $this->nAlturaDescricao)/2, "{$oLinha->modelo} / {$oLinha->placa}");
      $iDivisor = 1;
      if ($iTamanhoLinha >= 8 && $this->iTotalDias > 15) {
        $iDivisor = 2;
      } else if ($iTamanhoLinha >= 4 && $this->iTotalDias <= 15) {
        $iDivisor = 2;
      }

      $nValorAnterior = 0;
      $this->oPdf->setBold(true);
      $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna * 2, ($this->iAltura * $this->nAlturaDescricao)/$iDivisor, "{$oLinha->modelo} / {$oLinha->placa}", 1);
      $this->oPdf->setBold(false);
      $iPosicaoX = $this->oPdf->GetX();
      foreach($oLinha->dias as $iDia => $nValor) {

        $sValor = "";
        if ($nValor != $nValorAnterior) {

          $sValor = trim(db_formatar($nValor, 'f'));
          $nValorAnterior = $nValor;
        }
        $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna, $this->iAltura, $sValor, 1, 'R');
        if ($this->lQuebrarLinha && $iDia == $this->iColunaMeio) {

          $this->oPdf->Ln();
          $this->oPdf->SetX($iPosicaoX);
        }
      }
      if ($this->lQuebrarLinha && $this->lColunasImpares) {
        $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna, $this->iAltura, "", 1);
      }
      $this->oPdf->Ln();
    }
    $this->oPdf->showPDF("ControleHodometro_" . time() . ".pdf");
  }

  /**
   * Configurações e preparação do objeto oPdf.
   */
  private function configuraPdf() {

    $this->lQuebrarLinha   = count($this->aDias) > 16;
    $this->lColunasImpares = count($this->aDias) % 2 != 0;

    $iPosicaoColunaMeio = (int) (count($this->aDias) / 2);
    $this->iColunaMeio  = $this->aDias[$iPosicaoColunaMeio];
    if (!$this->lColunasImpares) {
      $this->iColunaMeio--;
    }

    $this->nAlturaDescricao  = 1;
    $nNumeroColunas = count($this->aDias);
    if ($this->lQuebrarLinha) {

      $this->nAlturaDescricao++;
      $nNumeroColunas =  (int) ($nNumeroColunas / 2);
      if ($this->lColunasImpares) {
        $nNumeroColunas++;
      }
    }
    $this->nLarguraColuna = 1 / ($nNumeroColunas + 2);

    $sExercicio = $this->oDataFinal->getAno();
    $sPeriodo   = $this->oDataInicial->getDate(DBDate::DATA_PTBR) . " a " . $this->oDataFinal->getDate(DBDate::DATA_PTBR);

    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
    $this->iAltura  = 4;

    $this->oPdf->addHeaderDescription("RELATÓRIO DE CONTROLE DE HODÔMETRO");
    $this->oPdf->addHeaderDescription("PERÍODO: {$sPeriodo}");
    $this->oPdf->addHeaderDescription("EXERCÍCIO: {$sExercicio}");
    $this->oPdf->SetAutoPageBreak(true, 0);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetTopMargin(1);
    $this->oPdf->SetFillcolor(235);
    $this->adicionarPagina();
  }

  /**
   * Adiciona uma nova página para a emissão PDF já com o cabeçalho da tabela de dados.
   */
  private function adicionarPagina() {

    $this->oPdf->AddPage(PDFDocument::PRINT_LANDSCAPE);
    $this->oPdf->SetFontSize(5);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->escreverCabecalho();
  }

  /**
   * Escreve o cabeçalho da tabela de dados na emissão PDF.
   */
  private function escreverCabecalho() {

    $aCabecalho = $this->getCabecalhoTabela();
    $iPosicaoX  = $this->oPdf->GetX();
    foreach ($aCabecalho as $iDia => $oLinha) {
      $this->oPdf->setBold(true);

      if ($iDia == 0) {

        $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna * 2, $this->iAltura * $this->nAlturaDescricao, $oLinha, 1, 'C', true);
        $iPosicaoX = $this->oPdf->GetX();
        continue;
      }
      $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna, $this->iAltura, $oLinha, 1, 'C', true);
      if ($this->lQuebrarLinha && $iDia == $this->iColunaMeio) {

        $this->oPdf->Ln();
        $this->oPdf->SetX($iPosicaoX);
      }
    }
    if ($this->lQuebrarLinha && $this->lColunasImpares) {
      $this->oPdf->MultiCell($this->iLargura * $this->nLarguraColuna, $this->iAltura, "", 1, 'C', true);
    }
    $this->oPdf->Ln();
  }

  /**
   * Array contendo as colunas para o cabeçalho da tabela do relatório.
   * @return array
   */
  private function getCabecalhoTabela() {

    $sMesAno = "/" . $this->oDataFinal->getMes() . "/" . $this->oDataFinal->getAno();

    $aLinha    = array();
    $aLinha[0] = "VEÍCULOS";
    foreach ($this->aDias as $iDia) {

      $sDia          = str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $aLinha[$iDia] = $sDia . $sMesAno;
    }
    return $aLinha;
  }

  /**
   * Busca todos os dados para emissão do relatório.
   * @return array
   * @throws Exception
   */
  private function getDados() {

    $sHora        = "23:59";
    $iAno         = $this->oDataFinal->getAno();
    $iMes         = $this->oDataFinal->getMes();
    $sDataInicial = $this->oDataInicial->getDate();
    $sDataFinal   = $this->oDataFinal->getDate();
    $oDaoVeiculos = new cl_veiculos();

    $this->aDias = array();
    for ($iDia = $this->oDataInicial->getDia(); $iDia <= $this->oDataFinal->getDia(); $iDia++) {
      $this->aDias[] = $iDia;
    }

    $sCampos = " ve01_codigo as codigo, ve01_placa as placa, ve22_descr as modelo ";
    $sOrder  = " ve22_descr, ve01_placa ";
    $sWhere  = " ve01_veictipoabast = 1 ";
    $sGroup  = " ve01_codigo, ve01_placa, ve22_descr ";

    $sWhere .= " and (veicmanut.ve62_dtmanut between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicabast.ve70_dtabast between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicretirada.ve60_datasaida between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicdevolucao.ve61_datadevol between '{$sDataInicial}' and '{$sDataFinal}') ";
    $sWhere .= " and db_config.codigo = " . $this->oInstituicao->getCodigo();
    $sWhere .= " and (db_depart.coddepto = " . $this->oDepartamento->getCodigo();
    $sWhere .= " or veiccadcentraldepart.ve37_coddepto = " . $this->oDepartamento->getCodigo() . ")";

    if (!empty($this->aVeiculos)) {
      $sWhere .= " and ve01_codigo in (" . implode(", ", $this->aVeiculos) . ") ";
    }

    $sSql       = $oDaoVeiculos->sql_query_movimentos(null, $sCampos, $sOrder, $sWhere, $sGroup);
    $rsVeiculos = $oDaoVeiculos->sql_record($sSql);

    if ($rsVeiculos == false || $oDaoVeiculos->numrows < 1) {
      throw new Exception("Não foram encontrados veículos com movimentações para os filtros informados.");
    }

    $aVeiculos = array();
    for ($iVeiculo = 0; $iVeiculo < $oDaoVeiculos->numrows; $iVeiculo++) {

      $oVeiculo    = db_utils::fieldsMemory($rsVeiculos, $iVeiculo);
      $aVeiculos[$oVeiculo->codigo] = $oVeiculo;
    }

    foreach ($aVeiculos as $oVeiculo) {

      $oVeiculo->dias = array();
      foreach($this->aDias as $iDia) {

        $nUltimaMedida = 0;
        $sData         = "{$iAno}-{$iMes}-{$iDia}";
        $sSqlHodometro = $oDaoVeiculos->sql_query_ultimamedida($oVeiculo->codigo, $sData, $sHora);

        $rsHodometro = $oDaoVeiculos->sql_record($sSqlHodometro);
        if ($rsHodometro != false && $oDaoVeiculos->numrows > 0) {

          $oHodometro    = db_utils::fieldsMemory($rsHodometro, 0);
          $nUltimaMedida = $oHodometro->ultimamedida;
        }
        $oVeiculo->dias[$iDia] = $nUltimaMedida;
      }
    }
    return $aVeiculos;
  }

  /**
   * Escreve o array passado em uma linha do arquivo.
   * @param resource $rsArquivo
   * @param array $aLinha
   */
  private function escreverNoArquivo($rsArquivo, $aLinha) {
    fputcsv($rsArquivo, $aLinha, ';', '"');
  }
}
