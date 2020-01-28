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
class RelatorioLevantamentoPatrimonial {

  const SITUACAO_NAO_ENCONTRADO_TXT = 1;
  const SITUACAO_NAO_CADASTRADO     = 2;
  const SITUACAO_INCONSISTENTE      = 3;
  const SITUACAO_CONSISTENTE        = 4;
  const SITUACAO_BAIXADO_NO_TXT     = 5;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Departamento do levantamento patrimonial.
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * Situação selecionada para filtro de quadros.
   * @var integer
   */
  private $iSituacao = 0;

  /**
   * Coleção dos dados dos bens encontrados no departamento do levantamento mas não encontrados no levantamento.
   * @var stdClass[]
   */
  private $aNaoEncontradoTxt = array();

  /**
   * Coleção dos dados dos bens (placas) encontrados no levantamento mas não cadastrados no sistema.
   * @var stdClass[]
   */
  private $aNaoCadastrado = array();

  /**
   * Coleção de dados dos bens encontrados em departamentos diferentes do levantamento.
   * @var stdClass[]
   */
  private $aInconsistente = array();

  /**
   * Coleção de dados dos bens encontrados no departamento conforme levantamento.
   * @var stdClass[]
   */
  private $aConsistente = array();

  /**
   * Coleção de dados dos bens encontrados no txt e baixados no sistema.
   * @var stdClass[]
   */
  private $aBaixadoTxt  = array();

  /**
   * Largura total da página do relatório.
   * @var integer
   */
  private $iLargura;

  /**
   * Altura padrão das linhas do relatório.
   * @var integer
   */
  private $iAltura;

  /**
   * Data da importação do levantamento patrimonial.
   * @var DBDate
   */
  private $oDataImportacao;


  /**
   * @param DBDepartamento $oDepartamento
   */
  public function __construct(DBDepartamento $oDepartamento) {

    $this->oPdf          = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->iLargura      = $this->oPdf->getAvailWidth() - 10;
    $this->iAltura       = 4;
    $this->oDepartamento = $oDepartamento;
  }

  /**
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Realiza a emissão do relatório PDF do levantamento patrimonial.
   */
  public function emitir() {

    $this->getDados();
    $this->configurarPdf();

    $sDepartamento = $this->oDepartamento->getCodigo() . " - " .  $this->oDepartamento->getNomeDepartamento();

    $oQuadro = new stdClass();

    if ($this->iSituacao == 0 || $this->iSituacao == self::SITUACAO_NAO_CADASTRADO) {

      $oQuadro->titulo    = "NÃO CADASTRADO";
      $oQuadro->descricao = "Bem listado no TXT e não encontrado no sistema.";
      $this->escreverQuadro($oQuadro, $this->aNaoCadastrado);
    }


    if ($this->iSituacao == 0 || $this->iSituacao == self::SITUACAO_NAO_ENCONTRADO_TXT) {

      $oQuadro->titulo    = "NÃO ENCONTRADO NO TXT";
      $oQuadro->descricao = "Bem cadastrado no sistema no departamento {$sDepartamento} e não listado no TXT.";
      $this->escreverQuadro($oQuadro, $this->aNaoEncontradoTxt);
    }

    if ($this->iSituacao == 0 || $this->iSituacao == self::SITUACAO_INCONSISTENTE) {

      $oQuadro->titulo    = "INCONSISTENTE";
      $oQuadro->descricao = "Bem listado no TXT e não cadastrado no departamento {$sDepartamento}.";
      $this->escreverQuadro($oQuadro, $this->aInconsistente, true);
    }


    if ($this->iSituacao == 0 || $this->iSituacao == self::SITUACAO_CONSISTENTE) {

      $oQuadro->titulo    = "CONSISTENTE";
      $oQuadro->descricao = "Bem listado no TXT e cadastrado no sistema no departamento {$sDepartamento}.";
      $this->escreverQuadro($oQuadro, $this->aConsistente);
    }

    if ($this->iSituacao == 0 || $this->iSituacao == self::SITUACAO_BAIXADO_NO_TXT) {

      $oQuadro->titulo    = "BEM BAIXADO";
      $oQuadro->descricao = "Bem listado no TXT mas com baixa no sistema.";
      $this->escreverQuadro($oQuadro, $this->aBaixadoTxt);
    }

    $this->oPdf->showPDF("levantamento_patrimonial" . time() . ".pdf");
  }

  /**
   * Busca as informações dos bens do departamento selecionado, do levantamento de bens do departamento selecionado
   * e processa os dados separando-os nos arrays para cada quadro.
   * @throws Exception
   */
  private function getDados() {

    $iDepartamento    = $this->oDepartamento->getCodigo();
    $oDaoLevantamento = new cl_levantamentopatrimonialbens();
    $oDaoBens         = new cl_bens();

    $sCamposLevantamento = " p14_placa as placa, p13_departamento as codigo_departamento, p13_data as data";
    $sOrderLevantamento  = " p14_placa ";
    $sWhereLevantamento  = " p13_departamento = {$iDepartamento} ";
    $sSqlLevantamento = $oDaoLevantamento->sql_query(null, $sCamposLevantamento, $sOrderLevantamento, $sWhereLevantamento);
    $rsLevantamento   = $oDaoLevantamento->sql_record($sSqlLevantamento);

    if ($rsLevantamento == false || $oDaoLevantamento->numrows == 0) {
      throw new Exception("Não há Levantamento Patrimonial para o departamento informado.");
    }

    $aPlacasTxt      = array();
    $aLevantamento   = array();
    $sDataImportacao = "";
    for ($iLevantamento = 0; $iLevantamento < $oDaoLevantamento->numrows; $iLevantamento++) {

      $oLevantamento = db_utils::fieldsMemory($rsLevantamento, $iLevantamento);
      $oLevantamento->nenhum_registro = false;
      $oLevantamento->placa           = ltrim($oLevantamento->placa, '0');
      $sPlaca          = db_stdClass::normalizeStringJsonEscapeString($oLevantamento->placa);
      $aPlacasTxt[]    = "'{$sPlaca}'";
      $sDataImportacao = $oLevantamento->data;
      $aLevantamento[$oLevantamento->placa] = $oLevantamento;
    }

    $this->oDataImportacao = new DBDate($sDataImportacao);

    $sPlacasTxt   = implode(", ", $aPlacasTxt);
    $sCamposBens  = " t52_bem as codigo_bem, ltrim(t52_ident, '0') as placa, t52_descr as descricao, ";
    $sCamposBens .= " t52_depart as codigo_departamento, descrdepto as departamento, t55_codbem as bem_baixado ";
    $sOrderBens   = " placa ";
    $sWhereBens   = " t52_depart = {$iDepartamento} or t52_ident in ($sPlacasTxt) ";
    $sSqlBens     = $oDaoBens->sql_querybensdepto(null, $sCamposBens, $sOrderBens, $sWhereBens);
    $rsBens       = $oDaoBens->sql_record($sSqlBens);

    $aBens = array();
    if ($rsBens != false && $oDaoBens->numrows > 0) {

      for ($iBem = 0; $iBem < $oDaoBens->numrows; $iBem++) {

        $oBem = db_utils::fieldsMemory($rsBens, $iBem);
        $oBem->nenhum_registro = false;
        $aBens[$oBem->placa]   = $oBem;
      }
    }

    foreach ($aBens as $sPlaca => $oBem) {

      if (!isset($aLevantamento[$sPlaca])) {

        if ($oBem->bem_baixado == null) {
          $this->aNaoEncontradoTxt[$sPlaca] = $oBem;
        }
        continue;
      }

      if ($oBem->bem_baixado != null) {

        $this->aBaixadoTxt[$sPlaca] = $oBem;
        continue;
      }

      if ($oBem->codigo_departamento == $iDepartamento) {
        $this->aConsistente[$sPlaca] = $oBem;
        continue;
      }
      $oBem->departamento = $oBem->codigo_departamento . " - " . $oBem->departamento;
      $this->aInconsistente[$sPlaca] = $oBem;
    }

    foreach ($aLevantamento as $sPlaca => $oLevantamento) {

      if (isset($aBens[$sPlaca])) {
        continue;
      }
      $oLevantamento->descricao      = "NÃO CADASTRADO";
      $this->aNaoCadastrado[$sPlaca] = $oLevantamento;
    }

    $oLinha = new stdClass();
    $oLinha->placa           = "";
    $oLinha->descricao       = "NENHUM REGISTRO ENCONTRADO.";
    $oLinha->departamento    = "";
    $oLinha->nenhum_registro = true;

    if (empty($this->aInconsistente)) {
      $this->aInconsistente[] = $oLinha;
    }

    if (empty($this->aConsistente)) {
      $this->aConsistente[] = $oLinha;
    }

    if (empty($this->aNaoCadastrado)) {
      $this->aNaoCadastrado[] = $oLinha;
    }

    if (empty($this->aNaoEncontradoTxt)) {
      $this->aNaoEncontradoTxt[] = $oLinha;
    }

    if (empty($this->aBaixadoTxt)) {
      $this->aBaixadoTxt[] = $oLinha;
    }
  }

  /**
   * Realiza a configuração do Pdf.
   */
  private function configurarPdf() {

    $sSituacao = "SITUAÇÃO: ";
    switch ($this->iSituacao) {
      case self::SITUACAO_CONSISTENTE:
        $sSituacao .= "CONSISTENTE";
        break;
      case self::SITUACAO_INCONSISTENTE:
        $sSituacao .= "INCONSISTENTE";
        break;
      case self::SITUACAO_NAO_ENCONTRADO_TXT:
        $sSituacao .= "NÃO ENCONTRADO NO TXT";
        break;
      case self::SITUACAO_NAO_CADASTRADO:
        $sSituacao .= "NÃO CADASTRADO";
        break;
      case self::SITUACAO_BAIXADO_NO_TXT:
        $sSituacao .= "BEM BAIXADO";
        break;
      default:
        $sSituacao = "";
        break;
    }

    $sDataImportacao = $this->oDataImportacao->getDate(DBDate::DATA_PTBR);
    $sDepartamento   = $this->oDepartamento->getCodigo() . " - " . $this->oDepartamento->getNomeDepartamento();

    $this->oPdf->addHeaderDescription("LEVANTAMENTO PATRIMONIAL");
    $this->oPdf->addHeaderDescription("DEPARTAMENTO: {$sDepartamento}");
    $this->oPdf->addHeaderDescription("DATA DA IMPORTAÇÃO: {$sDataImportacao}");
    $this->oPdf->addHeaderDescription($sSituacao);

    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetTopMargin(1);
    $this->adicionarPagina();
  }

  /**
   * Escreve um dos quadros do relatório.
   * @param stdClass   $oQuadro       Objeto contendo título w descrição do quadro.
   * @param stdClass[] $aLinhas       Linhas contendo as informações dos bens para o quadro.
   * @param boolean    $lDepartamento Se deve exibir o departamento cadastrado do bem.
   */
  private function escreverQuadro($oQuadro, $aLinhas, $lDepartamento = false) {

    $sAlginDescricao   = 'L';
    $iQuebraLinha      = $lDepartamento ? 0   : 1;
    $nLarguraDescricao = $lDepartamento ? 0.53 : 0.78;

    if ($this->oPdf->getAvailHeight() < 4 * $this->iAltura) {
      $this->adicionarPagina();
    }

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura, $this->iAltura, $oQuadro->titulo, 1, 1, 'C');
    $this->oPdf->Cell($this->iLargura, $this->iAltura, $oQuadro->descricao, 1, 1, 'C');
    $this->oPdf->Cell($this->iLargura * 0.22, $this->iAltura, "Placa do Bem", 1, 0, 'C');
    $this->oPdf->Cell($this->iLargura * $nLarguraDescricao, $this->iAltura, "Descrição", 1, $iQuebraLinha, 'C');

    if ($lDepartamento) {
      $this->oPdf->Cell($this->iLargura * 0.25, $this->iAltura, "Departamento Cadastrado", 1, 1, 'C');
    }

    $this->oPdf->setBold(false);
    foreach($aLinhas as $oLinha) {

      if ($this->oPdf->getAvailHeight() < $this->iAltura) {

        $this->adicionarPagina();
        $this->escreverQuadro($oQuadro, array(), $lDepartamento);
      }

      if ($oLinha->nenhum_registro) {

        $sAlginDescricao   = 'C';
        $iQuebraLinha      = 1;
        $nLarguraDescricao = 1;
      }

      if (!$oLinha->nenhum_registro) {
        $this->oPdf->Cell($this->iLargura * 0.22, $this->iAltura, $oLinha->placa, 1);
      }
      $this->oPdf->Cell($this->iLargura * $nLarguraDescricao, $this->iAltura, $oLinha->descricao, 1, $iQuebraLinha, $sAlginDescricao);
      if ($lDepartamento && !$oLinha->nenhum_registro) {
        $this->oPdf->Cell($this->iLargura * 0.25, $this->iAltura, $oLinha->departamento, 1, 1, 'C');
      }
    }

    if (!empty($aLinhas)) {
      $this->oPdf->Ln($this->iAltura);
    }
  }

  /**
   * Adiciona uma nova página.
   */
  private function adicionarPagina() {

    $this->oPdf->AddPage(PDFDocument::PRINT_LANDSCAPE);
    $this->oPdf->SetFontSize(6);
    $this->oPdf->setAutoNewLineMulticell(false);
  }
}
