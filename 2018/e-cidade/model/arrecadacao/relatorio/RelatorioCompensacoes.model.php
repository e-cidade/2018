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

class RelatorioCompensacoes {

  /** @var PDFDocument */
  private $oPdf;

  /** @var integer */
  private $iLarguraPagina;

  /** @var integer */
  private $iAlturaLinha;

  /* @var DBDate */
  private $oDataInicial;

  /* @var DBDate */
  private $oDataFinal;

  /* @var integer */
  private $iCgm;

  /* @var integer */
  private $iTipoDebito;

  /**
   * @param int $iCgm
   */
  public function setCgm($iCgm) {
    $this->iCgm = $iCgm;
  }

  /**
   * @return int
   */
  public function getCgm() {
    return $this->iCgm;
  }

  /**
   * @param int $iTipoDebito
   */
  public function setTipoDebito($iTipoDebito) {
    $this->iTipoDebito = $iTipoDebito;
  }

  /**
   * @return int
   */
  public function getTipoDebito() {
    return $this->iTipoDebito;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  private function processarDados() {

    $rsDados = $this->getDados();

    $aCompensacoes = array();
    $iQuantidadeRegistros = pg_num_rows($rsDados);
    for ($iRegistro = 0; $iRegistro < $iQuantidadeRegistros; $iRegistro++) {

      $oStdRegistro = db_utils::fieldsMemory($rsDados, $iRegistro);
      $oStdPessoa = (object) array(
        'nome' => $oStdRegistro->nome_cgm,
        'cgm'  => $oStdRegistro->numero_cgm,
      );
      $oCompensacao = (object) array(
        'numpre'           => $oStdRegistro->origem,
        'receita'          => $oStdRegistro->receita_debito,
        'descricao'        => $oStdRegistro->receita_debito_descricao,
        'valor'            => $oStdRegistro->valor_compensado,
        'receita_credito'  => $oStdRegistro->receita_credito,
        'tipo_debito'      => $oStdRegistro->tipo_debito,
        'data_compensacao' => $oStdRegistro->data_compensacao,
      );

      if (isset($aCompensacoes[$oStdRegistro->compensacao])) {

        $aCompensacoes[$oStdRegistro->compensacao]->aPessoas[$oStdRegistro->numero_cgm]      = $oStdPessoa;
        $aCompensacoes[$oStdRegistro->compensacao]->aReceitas[$oStdRegistro->receita_debito] = $oCompensacao;
      } else {

        $oStdCompensacao = new stdClass;
        $oStdCompensacao->aPessoas  = array($oStdRegistro->numero_cgm => $oStdPessoa);
        $oStdCompensacao->aReceitas = array($oStdRegistro->receita_debito => $oCompensacao);
        $aCompensacoes[$oStdRegistro->compensacao] = $oStdCompensacao;
      }
    }

    return $aCompensacoes;
  }

  /**
   * @return bool|resource
   * @throws DBException
   */
  private function getDados() {

    $oDaoAbatimentoUtilizacao = new cl_abatimento();

    $sCampos = implode(',', array(
      "k170_utilizacao    as compensacao",
      "z01_numcgm         as numero_cgm",
      "recibo.k00_receit  as receita_credito",
      "k170_receit        as receita_debito",
      "k157_data          as data_compensacao",
      "z01_nome           as nome_cgm",
      "k170_valor         as valor_compensado",
      "recibo.k00_numpre  as origem",
      "k02_descr          as receita_debito_descricao",
      "arretipo.k00_descr as tipo_debito"
    ));

    $aWhere = array(
      "k157_tipoutilizacao = '" . CreditoCompensacao::TIPO_UTILIZACAO_COMPENSACAO . "'"
    );

    if ($this->getCgm()) {
      $aWhere[] = "arrenumcgm.k00_numcgm = {$this->getCgm()}";
    }

    if ($this->getTipoDebito()) {
      $aWhere[] = "abatimentoutilizacaodestino.k170_tipo = {$this->getTipoDebito()}";
    }

    if ($this->getDataInicial()) {
      $aWhere[] = "abatimentoutilizacao.k157_data >= '{$this->getDataInicial()->getDate()}'";
    }

    if ($this->getDataFinal()) {
      $aWhere[] = "abatimentoutilizacao.k157_data <= '{$this->getDataFinal()->getDate()}'";
    }

    $sWhere = implode(' and ', $aWhere);
    $sOrder = 'recibo.k00_numpre, recibo.k00_receit, k157_data';

    $sSqlUtilizacoes = $oDaoAbatimentoUtilizacao->sql_query_utilizacao($sCampos, $sWhere, $sOrder);
    $rsUtilizacoes   = db_query($sSqlUtilizacoes);

    if (!$rsUtilizacoes) {
      throw new DBException("Não foi possível encontrar as compensações.");
    }

    return $rsUtilizacoes;
  }

  /**
   * Configurar Emissão e Filtros no Cabeçalho do Relatório
   */
  private function configurar() {

    $this->oPdf->Open();
    $this->oPdf->addHeaderDescription("COMPENSAÇÕES");
    $this->oPdf->addHeaderDescription("");

    if ($this->getDataInicial() && $this->getDataFinal()) {
      $this->oPdf->addHeaderDescription(
        "PERÍODO: {$this->getDataInicial()->getDate(DBDate::DATA_PTBR)}" .
        " ATÉ {$this->getDataFinal()->getDate(DBDate::DATA_PTBR)}"
      );
    }

    if ($this->getDataInicial() && !$this->getDataFinal()) {
      $this->oPdf->addHeaderDescription("PERÍODO: {$this->getDataInicial()->getDate(DBDate::DATA_PTBR)}");
    }

    if (!$this->getDataInicial() && $this->getDataFinal()) {
      $this->oPdf->addHeaderDescription("PERÍODO: {$this->getDataFinal()->getDate(DBDate::DATA_PTBR)}");
    }

    if ($this->getTipoDebito()) {

      $oDaoArreTipo = new cl_arretipo();
      $rsArretipo = db_query($oDaoArreTipo->sql_query_file($this->getTipoDebito()));

      if (!$rsArretipo) {
        throw new DBException("Não foi possível encontrar as informações do Tipo de Débito.");
      }

      $oTipoDebito = db_utils::fieldsMemory($rsArretipo, 0);

      $this->oPdf->addHeaderDescription("TIPO DE DÉBITO: {$this->getTipoDebito()} - {$oTipoDebito->k00_descr}");
    }

    if ($this->getCgm()) {
      $this->oPdf->addHeaderDescription("CGM: {$this->getCgm()}");
    }

    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->SetFillColor(235);
    $this->oPdf->setFontFamily("arial");
    $this->oPdf->SetFontSize(6);
    $this->oPdf->AddPage();

    $this->iAlturaLinha   = 4;
    $this->iLarguraPagina = $this->oPdf->getAvailWidth();
  }

  /**
   * Escreve o cabeçalho das compensações
   */
  private function escreveCabecalhoCompensacao() {

    $this->oPdf->SetFillColor(235);
    $this->oPdf->Cell($this->iLarguraPagina * 0.10, $this->iAlturaLinha, 'NUMPRE', 'TBLR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.23, $this->iAlturaLinha, 'RECEITA', 'TBR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.22, $this->iAlturaLinha, 'TIPO DE DÉBITO', 'TBR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, 'DATA', 'TBR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, 'RECEITA CRÉDITO', 'TBR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, 'VALOR', 'TBR', 1, 'C', 1);
    $this->oPdf->SetFillColor(255);
  }

  /**
   * Escreve o cabeçalho dos CGMs
   */
  private function escreveCabecalhoCgm() {

    $this->oPdf->SetFillColor(235);
    $this->oPdf->Cell($this->iLarguraPagina * 0.10, $this->iAlturaLinha, 'CGM', 'TBLR', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.90, $this->iAlturaLinha, 'NOME', 'TBR', 1, 'C', 1);
    $this->oPdf->SetFillColor(255);
  }

  /**
   * Escreve registros de compensações
   *
   * @param stdClass $oStdReceita
   */
  private function escreveRegistroCompensacao($oStdReceita) {

    $this->oPdf->Cell($this->iLarguraPagina * 0.10, $this->iAlturaLinha, $oStdReceita->numpre, 'BLR', 0, 'C');
    $this->oPdf->Cell($this->iLarguraPagina * 0.23, $this->iAlturaLinha, $oStdReceita->receita . ' - ' . $oStdReceita->descricao, 'BR', 0, 'L');
    $this->oPdf->Cell($this->iLarguraPagina * 0.22, $this->iAlturaLinha, $oStdReceita->tipo_debito, 'BR', 0, 'L');
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, db_formatar($oStdReceita->data_compensacao, 'd'), 'BR', 0, 'C');
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, $oStdReceita->receita_credito, 'BR', 0, 'C');
    $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, db_formatar($oStdReceita->valor, 'f'), 'BR', 1, 'R');
  }

  /**
   * Escreve registros de CGMs
   *
   * @param  stdClass $oStdPessoa
   */
  private function escreveRegistroCgm($oStdPessoa) {

    $this->oPdf->Cell($this->iLarguraPagina * 0.10, $this->iAlturaLinha, $oStdPessoa->cgm, 'BLR', 0, 'C');
    $this->oPdf->Cell($this->iLarguraPagina * 0.90, $this->iAlturaLinha, $oStdPessoa->nome, 'BR', 1, 'C');
  }

  /**
   * Emitor Relatório
   */
  public function emitir() {

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
    $this->configurar();

    $aDados = $this->processarDados();
    foreach ($aDados as $oStdCompensacao) {

      if ($this->oPdf->getAvailHeight() < 20) {
        $this->oPdf->AddPage();
      }

      $this->escreveCabecalhoCompensacao();
      foreach ($oStdCompensacao->aReceitas as $oMaOe) {
        $this->escreveRegistroCompensacao($oMaOe);
      }

      $this->escreveCabecalhoCgm();
      foreach ($oStdCompensacao->aPessoas as $oStdPessoa) {
        $this->escreveRegistroCgm($oStdPessoa);
      }

      $this->oPdf->ln(4);
    }

    $this->oPdf->showPDF("RelatorioCompensacoes_" . time());
  }
}