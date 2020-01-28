<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

/**
 * Relatório Ficha de Controle de Manutenção de Veículo
 */
class RelatorioFichaControleVeiculo {

  /**
   *
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Largura disponível
   *
   * @var integer
   */
  private $iLargura;

  /**
   * Altura das linhas
   *
   * @var integer
   */
  private $iAltura;

  /**
   * Código do veículo
   *
   * @var integer
   */
  private $iVeiculo;

  /**
   * Situação da manutenção (Realizada ou Pendente)
   *
   * @var integer
   */
  private $iSituacao;

  /**
   * Data inicial do período
   *
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * Data final do período
   *
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * Instituição da sessão do usuário
   *
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Código da assinatura padrão na configuração
   */
  const ASSINATURA_PADRAO = 5023;

  /**
   *
   * @param integer $iVeiculo
   * @param DBDate  $oDataInicial
   * @param DBDate  $oDataFinal
   */
  public function __construct($iVeiculo, DBDate $oDataInicial, DBDate $oDataFinal) {

    $this->oPdf         = new PDFDocument('L');
    $this->iLargura     = $this->oPdf->getAvailWidth() - 10;
    $this->iAltura      = 4;
    $this->iVeiculo     = $iVeiculo;
    $this->oDataInicial = $oDataInicial;
    $this->oDataFinal   = $oDataFinal;
    $this->oInstituicao = new Instituicao(db_getsession('DB_instit'));
  }

  /**
   * Define o filtro por situação
   *
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna os dados do relatório
   *
   * @return stdClass[]
   */
  private function getDados() {

    $aWhere   = array();
    $aWhere[] = "ve62_veiculos = {$this->iVeiculo}";
    $aWhere[] = "ve62_dtmanut between '{$this->oDataInicial->getDate()}' and '{$this->oDataFinal->getDate()}'";
    if (!empty($this->iSituacao)) {
      $aWhere[] = "ve62_situacao = {$this->iSituacao}";
    }

    $aManutencoes       = array();
    $oDaoItemManutencao = new cl_veicmanut;
    $sCampos            = "to_char(ve63_datanota, 'DD/MM/YYYY') as ve63_datanota, veicmanut.*, veicmanutitem.*";
    $sOrder             = "ve62_anousu, ve62_numero, ve63_codigo";
    $sSqlManutencoes    = $oDaoItemManutencao->sql_query_sem_itens($sCampos, $sOrder, implode(' AND ', $aWhere));
    $rsManutencoes      = $oDaoItemManutencao->sql_record($sSqlManutencoes);

    if ($oDaoItemManutencao->numrows > 0) {
      $aManutencoes = db_utils::getCollectionByRecord($rsManutencoes);
    }

    return $aManutencoes;
  }

  /**
   * Configura o layout do relatório
   */
  private function configurarRelatorio() {

    $sPeriodo = $this->oDataInicial->getDate(DBDate::DATA_PTBR) . ' a ' . $this->oDataFinal->getDate(DBDate::DATA_PTBR);
    $oVeiculo = new Veiculo($this->iVeiculo);
    $sVeiculo = "{$oVeiculo->getPlaca()} / {$oVeiculo->getModelo()}";
    $sAno     = "{$oVeiculo->getAnoModelo()} / {$oVeiculo->getAnoFabricacao()}";
    $sTombo   = null;
    if ($oVeiculo->getBem() !== false) {
      $sTombo = $oVeiculo->getBem()->getIdentificacao();
    }

    $this->oPdf->addHeaderDescription("FICHA DE CONTROLE DE MANUTENÇÃO DE VEÍCULO");
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("PERÍODO:  {$sPeriodo}");
    $this->oPdf->addHeaderDescription("VEÍCULO:  {$sVeiculo}");
    $this->oPdf->addHeaderDescription("MODELO/FABRICAÇÃO:  {$sAno}");
    $this->oPdf->addHeaderDescription("TOMBO:  {$sTombo}");
    $this->oPdf->SetAutoPageBreak(true, 0);
    $this->oPdf->AliasNbPages();

    $this->oPdf->Open();
    $this->oPdf->SetFontSize(7);
    $this->oPdf->AddPage();
  }

  /**
   * Escreve o cabeçalho das linhas
   */
  private function escreverCabecalho() {

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, 'OS', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, 'DATA NF', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.07, $this->iAltura, 'HODÔMETRO', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.25, $this->iAltura, 'DESCRIÇÃO', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.09, $this->iAltura, 'PRÓXIMA TROCA', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.07, $this->iAltura, 'QUANTIDADE', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, 'VALOR UNITÁRIO', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.08, $this->iAltura, 'VALOR PEÇAS', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.12, $this->iAltura, 'VALOR MÃO DE OBRA', 'TR', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, 'VALOR LAVAGEM', 'T', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Retorna verdadeiro se o próximo registro pertence a uma manutenção diferente ou é o último registro.
   *
   * @param  array   $aDados
   * @param  integer $iIndice
   * @return boolean
   */
  private function verificaImprimirTotalizador($aDados, $iIndice) {
    return (isset($aDados[$iIndice + 1]) && $aDados[$iIndice]->ve62_codigo != $aDados[$iIndice + 1]->ve62_codigo) || $aDados[$iIndice] === end($aDados);
  }

  /**
   * Escreve a assinatura do relatório
   */
  private function escreverAssinaturas() {

    $oAssinatura  = new libdocumento(self::ASSINATURA_PADRAO);
    $aAssinaturas = $oAssinatura->getDocParagrafos();
    $sAssinatura  = '';
    foreach ($aAssinaturas as $oParagrafoAssinatura) {

      if (!empty($oParagrafoAssinatura->oParag->db02_texto) && $oParagrafoAssinatura->oParag->db04_ordem == $this->oInstituicao->getCodigo()) {
        $sAssinatura = $oParagrafoAssinatura->oParag->db02_texto;
      }
    }

    if (empty($sAssinatura)) {
      throw new BusinessException("Assinatura com código " . self::ASSINATURA_PADRAO . " não localizada.");
    }

    eval($sAssinatura);
  }

  /**
   * Faz a emissão do relatório
   */
  public function emitir() {

    $aDados = $this->getDados();

    if (empty($aDados)) {
      throw new Exception("Nenhum registro encontrado.");
    }

    $this->configurarRelatorio();
    $this->escreverCabecalho();

    /**
     * Total da manutenção atual
     */
    $nTotalPecas   = 0;
    $nTotalMaoObra = 0;
    $nTotalLavagem = 0;

    /**
     * Total geral
     */
    $nTotalGeralPecas   = 0;
    $nTotalGeralMaoObra = 0;
    $nTotalGeralLavagem = 0;

    /**
     * Utilizado para controlar colunas exibidas somente na primeira linha
     * de cada manutenção
     */
    $lPrimeiraLinha = true;

    for ($iIndice = 0; $iIndice < count($aDados); $iIndice++) {

      /**
       * Verifica se tem espaço antes de escrever a linha
       */
      if ($this->oPdf->getAvailHeight() < 30) {

        $this->oPdf->AddPage();
        $this->escreverCabecalho();
      }

      $nValorPecas   = ($aDados[$iIndice]->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_PECA)        ? $aDados[$iIndice]->ve63_valortotalcomdesconto : 0;
      $nValorMaoObra = ($aDados[$iIndice]->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_MAO_DE_OBRA) ? $aDados[$iIndice]->ve63_valortotalcomdesconto : 0;
      $nValorLavagem = ($aDados[$iIndice]->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_LAVAGEM)     ? $aDados[$iIndice]->ve63_valortotalcomdesconto : 0;

      $nTotalPecas   += $nValorPecas;
      $nTotalMaoObra += $nValorMaoObra;
      $nTotalLavagem += $nValorLavagem;

      $nTotalGeralPecas   += $nValorPecas;
      $nTotalGeralMaoObra += $nValorMaoObra;
      $nTotalGeralLavagem += $nValorLavagem;

      $sNumeroOs     = $aDados[$iIndice]->ve62_numero . '/' . $aDados[$iIndice]->ve62_anousu;
      $sBorda        = $lPrimeiraLinha ? 'TB' : 'B';
      $sProximaTroca = !empty($aDados[$iIndice]->ve63_proximatroca) ? db_formatar($aDados[$iIndice]->ve63_proximatroca, 'f') : '';

      $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, $lPrimeiraLinha ? $sNumeroOs : '', $sBorda . 'R', 0, 'C');
      $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, $aDados[$iIndice]->ve63_datanota, $sBorda . 'R', 0, 'C');
      $this->oPdf->Cell($this->iLargura * 0.07, $this->iAltura, $lPrimeiraLinha ? db_formatar($aDados[$iIndice]->ve62_medida, 'f') : '', $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.25, $this->iAltura, $aDados[$iIndice]->ve63_descr, $sBorda . 'R', 0, 'L');
      $this->oPdf->Cell($this->iLargura * 0.09, $this->iAltura, $sProximaTroca, $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.07, $this->iAltura, db_formatar($aDados[$iIndice]->ve63_quant, 'f'), $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($aDados[$iIndice]->ve63_vlruni, 'f'), $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.08, $this->iAltura, db_formatar($nValorPecas, 'f'), $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.12, $this->iAltura, db_formatar($nValorMaoObra, 'f'), $sBorda . 'R', 0, 'R');
      $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($nValorLavagem, 'f'), $sBorda, 1, 'R');

      $lPrimeiraLinha = false;

      /**
       * Escreve totalizador da manutenção atual
       */
      if ($this->verificaImprimirTotalizador($aDados, $iIndice)) {

        $this->oPdf->Ln($this->iAltura / 4);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, "", 0, 0, 'C');
        $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, "TOTAL", 0, 0, 'R');
        $this->oPdf->Cell($this->iLargura * 0.08, $this->iAltura, db_formatar($nTotalPecas, 'f'), 0, 0, 'R');
        $this->oPdf->Cell($this->iLargura * 0.12, $this->iAltura, db_formatar($nTotalMaoObra, 'f'), 0, 0, 'R');
        $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($nTotalLavagem, 'f'), 0, 1  , 'R');
        $this->oPdf->setBold(false);
        $this->oPdf->Ln($this->iAltura / 2);

        $lPrimeiraLinha = true;
        $nTotalPecas    = 0;
        $nTotalMaoObra  = 0;
        $nTotalLavagem  = 0;
      }
    }

    /**
     * Verifica se tem espaço antes de imprimir Total Geral e assinatura
     */
    if ($this->oPdf->getAvailHeight() < 40) {
      $this->oPdf->AddPage();
    }

    $this->oPdf->SetFontSize(8);
    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, "", 0, 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, "TOTAL GERAL", 0, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.08, $this->iAltura, db_formatar($nTotalGeralPecas, 'f'), 0, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.12, $this->iAltura, db_formatar($nTotalGeralMaoObra, 'f'), 0, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.10, $this->iAltura, db_formatar($nTotalGeralLavagem, 'f'), 0, 1  , 'R');
    $this->oPdf->setBold(false);
    $this->oPdf->Ln($this->iAltura * 4);
    $this->escreverAssinaturas();

    $this->oPdf->showPDF("RelatorioFichaControleVeiculo_" . date('YmdHi', time()));
  }

}
