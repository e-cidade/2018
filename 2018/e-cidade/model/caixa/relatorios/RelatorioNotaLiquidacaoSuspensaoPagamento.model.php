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


class RelatorioNotaLiquidacaoSuspensaoPagamento {

  /**
   * @const string
   */
  const CAMINHO_MENSAGENS = 'financeiro.caixa.RelatorioNotaLiquidacaoSuspensaoPagamento.';

  /**
   * @const integer
   */
  const SITUACAO_TODAS = 1;

  /**
   * @const integer
   */
  const SITUACAO_SUSPENSAS = 2;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var EmpenhoFinanceiro
   */
  private $oEmpenho;

  /**
   * @var CgmFisico|CgmJuridico
   */
  private $oCredor;

  /**
   * @var ListaClassificacaoCredor[]
   */
  private $aListaClassificacao = array();

  /**
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * @var integer
   */
  private $iSituacao = self::SITUACAO_TODAS;

  /**
   * @var stdClass[]
   */
  private $aNotasImpressao = array();

  /**
   * RelatorioNotaLiquidacaoSuspensaoPagamento constructor.
   */
  public function __construct() {

  }

  public function emitir() {

    $this->prepararInformacoes();
    $this->oPdf = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
    $this->oPdf->SetFontSize(8);
    $this->oPdf->SetFillColor(235);
    $this->oPdf->Open();
    $this->oPdf->addHeaderDescription("Relatório de Notas de Liquidação Suspensas");
    $this->oPdf->addHeaderDescription("");
    if (!empty($this->oEmpenho)) {
      $this->oPdf->addHeaderDescription("Empenho: {$this->oEmpenho->getCodigo()}/{$this->oEmpenho->getAno()}");
    }
    if (!empty($this->oCredor)) {
      $this->oPdf->addHeaderDescription("Credor: {$this->oCredor->getNome()}");
    }
    if (!empty($this->oDataInicial)) {
      $this->oPdf->addHeaderDescription("Data Inicial: {$this->oDataInicial->getDate(DBDate::DATA_PTBR)}");
    }
    if (!empty($this->oDataFinal)) {
      $this->oPdf->addHeaderDescription("Data Final: {$this->oDataFinal->getDate(DBDate::DATA_PTBR)}");
    }

    $sSituacao = $this->iSituacao == self::SITUACAO_TODAS ? 'Todas' : 'Suspensas';
    $this->oPdf->addHeaderDescription("Situação: {$sSituacao}");
    $this->oPdf->AddPage();

    $this->emitirCabecalhoLista($this->aNotasImpressao[0]->lista_classificacao);
    $iCodigoClassificacao = $this->aNotasImpressao[0]->codigo_lista_classificacao;
    foreach ($this->aNotasImpressao as $oStdNota) {

      if ($this->oPdf->getAvailHeight() < 30) {
        $this->oPdf->AddPage();
      }

      if ($oStdNota->codigo_lista_classificacao != $iCodigoClassificacao) {

        $iCodigoClassificacao = $oStdNota->codigo_lista_classificacao;
        $this->oPdf->Ln(5);
        $this->emitirCabecalhoLista($oStdNota->lista_classificacao);
        $this->emitirCabecalho();
      } else {
        $this->emitirCabecalho();
      }

      $this->oPdf->Cell(25, 4, "{$oStdNota->codigo_empenho}/{$oStdNota->ano_empenho}", 0, 0, PDFDocument::ALIGN_CENTER);
      $this->oPdf->Cell(20, 4, $oStdNota->sequencial_nota, 0, 0, PDFDocument::ALIGN_CENTER);
      $this->oPdf->Cell(30, 4, $oStdNota->numero_nota, 0, 0, PDFDocument::ALIGN_CENTER);
      $this->oPdf->Cell(90, 4, $oStdNota->nome_fornecedor, 0, 0, "C");
      $this->oPdf->Cell(25, 4, db_formatar($oStdNota->valor_nota, 'f'), 0, 1, PDFDocument::ALIGN_RIGHT);

      $this->oPdf->setBold(true);
      $this->oPdf->Cell(30, 4, 'Data da Suspensão', 0, 0, "C", 1);
      $this->oPdf->Cell(160, 4, 'Justificativa da Suspensão', 0, 1, "C", 1);
      $this->oPdf->setBold(false);

      $oDataSuspensao = new DBDate($oStdNota->data_suspensao);
      $nTamanhoJustificativa = $this->oPdf->getMultiCellHeight(160, 4, $oStdNota->justificativa_suspensao);
      $this->oPdf->Cell(30, $nTamanhoJustificativa, $oDataSuspensao->getDate(DBDate::DATA_PTBR), 0, 0, "C");
      $this->oPdf->MultiCell(160, 4, $oStdNota->justificativa_suspensao);

      if ($this->iSituacao == self::SITUACAO_TODAS && !empty($oStdNota->data_retorno)) {

        $this->oPdf->setBold(true);
        $this->oPdf->Cell(30, 4, 'Data da Liberação', 0, 0, "C", 1);
        $this->oPdf->Cell(160, 4, 'Justificativa da Liberação', 0, 1, "C", 1);
        $this->oPdf->setBold(false);
        $oDataLiberacao = new DBDate($oStdNota->data_retorno);
        $nTamanhoJustificativa = $this->oPdf->getMultiCellHeight(160, 4, $oStdNota->justificativa_retorno);
        $this->oPdf->Cell(30, $nTamanhoJustificativa, $oDataLiberacao->getDate(DBDate::DATA_PTBR), 0, 0, "C");
        $this->oPdf->MultiCell(160, 4, $oStdNota->justificativa_retorno);
      }
    }

    $this->oPdf->showPDF();
  }

  /**
   * Imprime o cabeçalho com os dados de empenho/nota
   */
  private function emitirCabecalho() {

    $this->oPdf->setBold(true);
    $this->oPdf->Cell(25, 4, 'Empenho', 'T', 0, "C", true);
    $this->oPdf->Cell(20, 4, 'Seq. da Nota', 'T', 0, "C", true);
    $this->oPdf->Cell(30, 4, 'Número da N.F.' , 'T', 0, "C", true);
    $this->oPdf->Cell(90, 4, 'Credor', 'T', 0, "C", true);
    $this->oPdf->Cell(25, 4, 'Valor', 'T', 1, "C", true);
    $this->oPdf->setBold(false);
  }

  /**
   * Emite a linha com adescrição da lista
   * @param $sDescricaoLista
   */
  private function emitirCabecalhoLista($sDescricaoLista) {

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 4, "Lista de Classificação: {$sDescricaoLista}", 1, 1, PDFDocument::ALIGN_LEFT, true);
    $this->oPdf->setBold(false);
  }

  /**
   * Método responsável por preparar as informações que serão impressas no relatório
   *
   * @throws BusinessException
   * @throws DBException
   */
  private function prepararInformacoes() {

    $sCampos = implode(',',
                       array(
                         'e69_codnota                 as sequencial_nota',
                         'e69_numero                  as numero_nota',
                         'e70_valor                   as valor_nota',
                         'e60_numemp                  as sequencial_empenho',
                         'z01_nome                    as nome_fornecedor',
                         'e60_codemp                  as codigo_empenho',
                         'e60_anousu                  as ano_empenho',
                         'cc30_descricao              as lista_classificacao',
                         'cc36_datasuspensao          as data_suspensao',
                         'cc36_justificativasuspensao as justificativa_suspensao',
                         'cc36_justificativaretorno   as justificativa_retorno',
                         'cc36_dataretorno            as data_retorno',
                         'cc30_codigo                 as codigo_lista_classificacao'
                       ));

    $aWhere = array();
    if (!empty($this->oEmpenho)) {
      $aWhere[] = "e60_numemp = {$this->oEmpenho->getNumero()}";
    }

    if (!empty($this->oCredor)) {
      $aWhere[] = "e60_numcgm = {$this->oCredor->getCodigo()}";
    }

    if (!empty($this->oDataInicial)) {
      $aWhere[] = "cc36_datasuspensao >= '{$this->oDataInicial->getDate(DBDate::DATA_EN)}'";
    }

    if (!empty($this->oDataFinal)) {
      $aWhere[] = "cc36_datasuspensao <= '{$this->oDataFinal->getDate(DBDate::DATA_EN)}'";
    }

    if (!empty($this->aListaClassificacao)) {

      $aListasSelecionadas = array();
      foreach ($this->aListaClassificacao as $oListaClassificacao) {
        $aListasSelecionadas[] = $oListaClassificacao->getCodigo();
      }
      $aWhere[] = "cc30_codigo in (".implode(',', $aListasSelecionadas).")";
    }

    if ($this->iSituacao == self::SITUACAO_SUSPENSAS) {
      $aWhere[] = "cc36_dataretorno is null";
    }

    $sOrdem = "cc30_codigo, e60_numemp, e69_codnota";

    $oDaoSuspensao  = new cl_empnotasuspensao();
    $sSqlBuscaNotas = $oDaoSuspensao->sql_query_relatorio_suspensao($sCampos, implode (' and ', $aWhere), $sOrdem);
    $rsBuscaNotas   = db_query($sSqlBuscaNotas);
    if (!$rsBuscaNotas) {
      throw new DBException(_M(self::CAMINHO_MENSAGENS . 'erro_busca_notas_suspensas'));
    }

    $iTotalRegistros = pg_num_rows($rsBuscaNotas);
    if ($iTotalRegistros == 0) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGENS . 'notas_nao_encontradas'));
    }

    $this->aNotasImpressao = db_utils::getCollectionByRecord($rsBuscaNotas);
  }


  /**
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenho = $oEmpenho;
  }

  /**
   * @param CgmBase $oCredor
   */
  public function setCredor(CgmBase $oCredor) {
    $this->oCredor = $oCredor;
  }

  /**
   * @param ListaClassificacaoCredor $oListaClassificacao
   */
  public function adicionarListaClassificacao(ListaClassificacaoCredor $oListaClassificacao) {
    $this->aListaClassificacao[$oListaClassificacao->getCodigo()] = $oListaClassificacao;
  }

  /**
   * @param $iSituacao
   * @throws ParameterException
   */
  public function setSituacao($iSituacao) {

    if (empty($iSituacao)) {
      throw new ParameterException(_M(self::CAMINHO_MENSAGENS . "situacao_nao_informada"));
    }
    $this->iSituacao = $iSituacao;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }
}