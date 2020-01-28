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
class RelatorioVeiculoManutencao {

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var integer Situa��o da manuten��o para filtro. Opcional.
   */
  private $iSituacao;

  /**
   * @var DBDate Data inicial do per�odo para emiss�o do relat�rio.
   */
  private $oDataInicial;

  /**
   * @var DBDate Data final do per�odo para emiss�o do relat�rio
   */
  private $oDataFinal;

  /**
   * @var array Ve�culos selecionados para filtro do relat�rio, opcional.
   */
  private $aVeiculo;

  /**
   * @var array Combust�veis selecionados para filtro do relat�rio.
   */
  private $aCombustivel;

  /**
   * @var integer[] C�digo dos ve�culos buscados para emiss�o do relat�rio.
   */
  private $aCodigoVeiculos;

  /**
   * @var stdClass[] Objetos com os dados dos ve�culos buscados para o relat�rio.
   */
  private $aDadosVeiculos;

  /**
   * @var stdClass[] Objetos com os dados dos combust�veis selecionados para filtro do relat�rio.
   */
  private $aDadosCombustivel;

  /**
   * @var integer Largura dispon�vel do relat�rio.
   */
  private $iLargura;

  /**
   * @var integer Altura da linha do relat�rio.
   */
  private $iAltura;

  /**
   * @var stdClass Objeto com as informa��es totalizadas do relat�rio.
   */
  private $oTotalizador;

  /**
   * C�digo da assinatura padr�o
   */
  const ASSINATURA_PADRAO = 5021;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * Construtor
   *
   * @param Instituicao    $oInstituicao
   * @param DBDepartamento $oDepartamento
   */
  public function __construct($oInstituicao, $oDepartamento) {

    $this->oInstituicao  = $oInstituicao;
    $this->oDepartamento = $oDepartamento;
    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
  }

  /**
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial($oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal($oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @param integer[] $aVeiculo
   */
  public function setVeiculos($aVeiculo) {
    $this->aVeiculo = $aVeiculo;
  }

  /**
   * @param integer[] $aCombustivel
   */
  public function setCombustiveis($aCombustivel) {
    $this->aCombustivel = $aCombustivel;
  }

  /**
   * Busca e processa todos os dados necess�rios para o relat�rio.
   */
  public function getDados() {

    $this->getCombustiveis();
    $this->getVeiculos();
    $this->getValoresAbastecimento();
    $this->getValoresManutencao();
    $this->getValoresHodometro();
    $this->processaVeiculos();
  }

  /**
   * Faz a emiss�o do relat�rio.
   */
  public function emitir() {

    $this->getDados();
    $this->configuraPdf();
    $this->adicionarPagina(true);

    foreach ($this->aDadosVeiculos as $oDadoVeiculo) {
      $this->escreverLinha($oDadoVeiculo);
    }

    $this->oPdf->setBold(true);
    $this->oPdf->SetFontSize(7);
    $this->escreverLinha($this->oTotalizador, true);
    $this->oPdf->SetFontSize(6);
    $this->oPdf->setBold(false);

    $this->escreverAssinaturas();

    $this->oPdf->showPDF("RelatorioManutencaoVeiculo_" . time());
  }

  /**
   * Configura��es e prepara��o do objeto oPdf.
   */
  private function configuraPdf() {

    $sExercicio = $this->oDataFinal->getAno();
    $sPeriodo   = $this->oDataInicial->getDate(DBDate::DATA_PTBR) . " a " . $this->oDataFinal->getDate(DBDate::DATA_PTBR);
    $sSituacao  = "TODOS";

    if (isset($this->iSituacao)) {

      switch ($this->iSituacao) {
        case VeiculoManutencao::SITUACAO_PENDENTE: $sSituacao = "PENDENTE"; break;
        case VeiculoManutencao::SITUACAO_REALIZADO: $sSituacao = "REALIZADO"; break;
      }
    }

    $this->iLargura = $this->oPdf->getAvailWidth();
    $this->iAltura  = 4;

    $this->oPdf->addHeaderDescription("RELAT�RIO DE MANUTEN��O DE VE�CULOS");
    $this->oPdf->addHeaderDescription("PER�ODO: {$sPeriodo}");
    $this->oPdf->addHeaderDescription("EXERC�CIO: {$sExercicio}");
    $this->oPdf->addHeaderDescription("SITUA��O: {$sSituacao}");
    $this->oPdf->SetAutoPageBreak(true, 0);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetTopMargin(1);
  }

  /**
   * Adiciona uma nova p�gina j� com cabe�alho da tabela de dados.
   *
   * @param bool $lEscreveTotalizacor Se deve escrever o totalizador de combust�veis.
   * @param bool $lEscreverCabecalho  Se deve escrever o cabe�alho da tabela de dados.
   */
  private function adicionarPagina($lEscreveTotalizacor = false, $lEscreverCabecalho = true) {

    $this->oPdf->AddPage();
    $this->oPdf->SetFontSize(6);
    $this->oPdf->setAutoNewLineMulticell(false);
    if ($lEscreveTotalizacor) {
      $this->escreveTotalizadorCombustivel();
    }
    if (!$lEscreverCabecalho) {
      return;
    }
    $this->escreverCabecalho();
  }

  /**
   * Escreve os totalizadores de combust�veis.
   */
  private function escreveTotalizadorCombustivel() {

    $lBold    = $this->oPdf->getBold();
    $nLargura = ($this->iLargura - 10 ) / count($this->aDadosCombustivel);
    $this->oPdf->setBold(true);

    foreach ($this->aDadosCombustivel as $oCombustivel) {
      $this->oPdf->MultiCell($nLargura, $this->iAltura, "VALOR TOTAL DE {$oCombustivel->descricao}", 0, 'C');
    }
    $this->oPdf->Ln();

    $this->oPdf->setBold(false);
    foreach ($this->aDadosCombustivel as $oCombustivel) {
      $this->oPdf->MultiCell($nLargura, $this->iAltura, "R$" . db_formatar($oCombustivel->total, 'f'), 0, 'C');
    }
    $this->oPdf->Ln();
    $this->oPdf->Ln($this->iAltura);

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve o cabe�alho da tabela do relat�rio.
   */
  private function escreverCabecalho() {

    $lBold = $this->oPdf->getBold();

    $iPosicaoX = $this->oPdf->GetX();
    $iPosicaoY = $this->oPdf->GetY();
    $this->oPdf->setBold(true);
    $this->oPdf->MultiCell($this->iLargura * 0.36, $this->iAltura, "VE�CULOS", 'TL', 'C');
    $this->oPdf->Ln();

    $this->oPdf->SetX($iPosicaoX);
    $this->oPdf->MultiCell($this->iLargura * 0.05, $this->iAltura, "PLACA", 'TBL', 'L');
    $this->oPdf->MultiCell($this->iLargura * 0.21, $this->iAltura, "MODELO", 'TBL', 'L');
    $this->oPdf->MultiCell($this->iLargura * 0.03, $this->iAltura, "ANO", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.07, $this->iAltura, "TOMBO", 'TBL', 'C');

    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->SetXY($iPosicaoX, $iPosicaoY);
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura * 2, "KM RODADO", 'TBL', 'C');

    $iPosicaoX = $this->oPdf->GetX();
    $iPosicaoY = $this->oPdf->GetY();
    $this->oPdf->MultiCell($this->iLargura * 0.16, $this->iAltura, "COMBUST�VEL", 'TL', 'C');
    $this->oPdf->Ln();

    $this->oPdf->SetX($iPosicaoX);
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, "LITROS", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, "VALOR R$", 'TBL', 'C');

    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->SetXY($iPosicaoX, $iPosicaoY);
    $this->oPdf->MultiCell($this->iLargura * 0.04, $this->iAltura * 2, "M�DIA", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura * 2, "LAVAGEM", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, "PE�AS E ACESS�RIOS", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura * 2, "M�O DE OBRA", 'TBL', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura * 2, "TOTAL", 1, 'C');
    $this->oPdf->Ln();

    $this->oPdf->setBold($lBold);
  }

  /**
   * Imprime as assinaturas
   */
  private function escreverAssinaturas() {

    if ($this->oPdf->getAvailHeight() < 32) {
      $this->adicionarPagina(false, false);
    }
    $this->oPdf->Ln($this->iAltura * 3);

    $oAssinatura  = new libdocumento(self::ASSINATURA_PADRAO);
    $aAssinaturas = $oAssinatura->getDocParagrafos();
    $sAssinatura  = "";
    foreach ($aAssinaturas as $oParagrafoAssinatura) {
      if (!empty($oParagrafoAssinatura->oParag->db02_texto) && $oParagrafoAssinatura->oParag->db04_ordem == $this->oInstituicao->getCodigo()) {
        $sAssinatura = $oParagrafoAssinatura->oParag->db02_texto;
      }
    }

    if (empty($sAssinatura)) {
      throw new BusinessException("Assinatura com c�digo " . self::ASSINATURA_PADRAO . " n�o localizada.");
    }

    eval($sAssinatura);
  }

  /**
   * @param stdClass $oLinha
   * @param bool     $lTotalizadora Se a linha � totalizadora
   */
  private function escreverLinha($oLinha, $lTotalizadora = false) {

    $iLinhaPlaca  = $lTotalizadora ? 7.2 : 1;
    if ($this->oPdf->getAvailHeight() < 12) {

      $lBold = $this->oPdf->getBold();
      $this->adicionarPagina();
      $this->oPdf->setBold($lBold);
    }

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.05 * $iLinhaPlaca, $this->iAltura, $oLinha->placa, 'LB', 'L');

    if (!$lTotalizadora) {

      $this->oPdf->MultiCell($this->iLargura * 0.21, $this->iAltura, $oLinha->modelo, 'LB', 'L');
      $this->oPdf->MultiCell($this->iLargura * 0.03, $this->iAltura, $oLinha->ano, 'LB', 'C');
      $this->oPdf->MultiCell($this->iLargura * 0.07, $this->iAltura, $oLinha->tombo, 'LB', 'C');
    }
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->hodometro, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->litros_abastecido, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->valor_abastecido, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.04, $this->iAltura, trim(db_formatar($oLinha->media, 'f')), 'LB', 'C');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->lavagem, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->pecas, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->mao_de_obra, 'f'), 'LB', 'R');
    $this->oPdf->MultiCell($this->iLargura * 0.08, $this->iAltura, db_formatar($oLinha->total, 'f'), 'RLB', 'R');
    $this->oPdf->Ln();
  }

  /**
   * Carrega os objetos referentes aos combust�veis selecionados para filtro.
   * @throws Exception
   */
  private function getCombustiveis() {

    $sCampos = " ve26_codigo as codigo, ve26_descr as descricao ";
    $sWhere  = " ve26_codigo in (" . implode(", ", $this->aCombustivel) . ")";

    $oDaoCombustivel = new cl_veiccadcomb();
    $sSqlCombustivel = $oDaoCombustivel->sql_query(null, $sCampos, null, $sWhere);
    $rsCombustiveis = $oDaoCombustivel->sql_record($sSqlCombustivel);
    if ($rsCombustiveis == false || $oDaoCombustivel->numrows == 0) {
      throw new Exception("Combust�veis n�o encontrados.");
    }

    $aDadosCombustivel = array();
    for ($iCombustivel = 0; $iCombustivel < $oDaoCombustivel->numrows; $iCombustivel++) {

      $oCombustivel        = db_utils::fieldsMemory($rsCombustiveis, $iCombustivel);
      $oCombustivel->total = 0;
      $aDadosCombustivel[$oCombustivel->codigo] = $oCombustivel;
    }
    $this->aDadosCombustivel = $aDadosCombustivel;
  }

  /**
   * Busca os dados dos ve�culos de acordo com os filtros aplicados no relat�rio.
   * Preenche os atributo aDadosVeiculos e aCodigoVeiculos.
   */
  private function getVeiculos() {

    $sDataInicial = $this->oDataInicial->getDate();
    $sDataFinal   = $this->oDataFinal->getDate();
    $oDaoVeiculo  = new cl_veiculos();

    $sCampos = " ve01_codigo, ve01_placa, ve01_anofab, ve22_descr, t52_ident, ve01_medidaini ";
    $sGroup  = " ve01_codigo, ve01_placa, ve01_anofab, ve22_descr, t52_ident, ve01_medidaini ";
    $sOrder  = " ve01_codigo ";

    $sWhere  = " (veicmanut.ve62_dtmanut between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicabast.ve70_dtabast between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicretirada.ve60_datasaida between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere .= " or veicdevolucao.ve61_datadevol between '{$sDataInicial}' and '{$sDataFinal}') ";
    $sWhere .= " and db_config.codigo = " . $this->oInstituicao->getCodigo();
    $sWhere .= " and db_depart.coddepto = " . $this->oDepartamento->getCodigo();

    if (!empty($this->aVeiculo)) {
      $sWhere .= " and ve01_codigo in (" . implode(", ", $this->aVeiculo) . ") ";
    }

    if (!empty($this->aCombustivel)) {

      $sWhere .= " and (ve06_veiccadcomb  in (" . implode(", ", $this->aCombustivel) . ") ";
      $sWhere .= " or ve70_veiculoscomb in (" . implode(", ", $this->aCombustivel) . ")) ";
    }

    if (!empty($this->iSituacao)) {
      $sWhere .= " and ve62_situacao = {$this->iSituacao} ";
    }

    $sSql = $oDaoVeiculo->sql_query_movimentos(null, $sCampos, $sOrder, $sWhere, $sGroup);

    $aVeiculos       = array();
    $aCodigoVeiculos = array();

    $rsVeiculos = $oDaoVeiculo->sql_record($sSql);
    if ($rsVeiculos == false || $oDaoVeiculo->numrows == 0) {
      throw new Exception("Nenhum ve�culo encontrado para os filtros informados.");
    }

    for ($iVeiculo = 0; $iVeiculo < $oDaoVeiculo->numrows; $iVeiculo++) {

      $oStdVeiculo = db_utils::fieldsMemory($rsVeiculos, $iVeiculo);
      $oVeiculo    = new stdClass();

      $oVeiculo->codigo            = $oStdVeiculo->ve01_codigo;
      $oVeiculo->placa             = $oStdVeiculo->ve01_placa;
      $oVeiculo->modelo            = $oStdVeiculo->ve22_descr;
      $oVeiculo->ano               = $oStdVeiculo->ve01_anofab;
      $oVeiculo->tombo             = $oStdVeiculo->t52_ident;
      $oVeiculo->medida_inicial    = $oStdVeiculo->ve01_medidaini;
      $oVeiculo->hodometro         = 0;
      $oVeiculo->litros_abastecido = 0;
      $oVeiculo->valor_abastecido  = 0;
      $oVeiculo->media             = 0;
      $oVeiculo->lavagem           = 0;
      $oVeiculo->pecas             = 0;
      $oVeiculo->mao_de_obra       = 0;
      $oVeiculo->total             = 0;
      $oVeiculo->tipo_combustivel  = 0;

      $aCodigoVeiculos[]            = $oVeiculo->codigo;
      $aVeiculos[$oVeiculo->codigo] = $oVeiculo;
    }

    $this->aDadosVeiculos  = $aVeiculos;
    $this->aCodigoVeiculos = $aCodigoVeiculos;
  }

  /**
   * Busca os valores de abastecimento para os ve�culos j� buscados pela getVeiculos.
   */
  private function getValoresAbastecimento() {

    $sDataInicial      = $this->oDataInicial->getDate();
    $sDataFinal        = $this->oDataFinal->getDate();
    $oDaoAbastecimento = new cl_veicabast();

    $sCampos  = " ve70_veiculos, sum(ve70_valor) as valor, sum(ve70_litros) as litros, ve70_veiculoscomb as tipo_combustivel ";
    $sWhere   = " ve70_dtabast BETWEEN '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere  .= " and ve70_veiculos in (" . implode(", ", $this->aCodigoVeiculos) . ") ";

    if (!empty($this->aCombustivel)) {
      $sWhere  .= " and ve70_veiculoscomb in (" . implode(", ", $this->aCombustivel) . ") ";
    }

    $sSqlAbastecimento  = $oDaoAbastecimento->sql_query_file(null, $sCampos, null, $sWhere);
    $sSqlAbastecimento .= " group by ve70_veiculos, tipo_combustivel order by ve70_veiculos ";
    $rsAbastecimento    = $oDaoAbastecimento->sql_record($sSqlAbastecimento);
    if ($rsAbastecimento == false || $oDaoAbastecimento->numrows == 0) {
      return;
    }

    for ($iAbastecimento = 0; $iAbastecimento < $oDaoAbastecimento->numrows; $iAbastecimento++) {

      $oAbastecimento = db_utils::fieldsMemory($rsAbastecimento, $iAbastecimento);
      $iVeiculo       = $oAbastecimento->ve70_veiculos;
      if (!isset($this->aDadosVeiculos[$iVeiculo])) {
        continue;
      }

      if (isset($this->aDadosCombustivel[$oAbastecimento->tipo_combustivel])) {
        $this->aDadosCombustivel[$oAbastecimento->tipo_combustivel]->total += $oAbastecimento->valor;
      }

      $this->aDadosVeiculos[$iVeiculo]->valor_abastecido  += $oAbastecimento->valor;
      $this->aDadosVeiculos[$iVeiculo]->litros_abastecido += $oAbastecimento->litros;
    }
  }

  /**
   * Busca as informa��es de manuten��es para os ve�culos j� buscados pela getVeiculos.
   */
  private function getValoresManutencao() {

    $sDataInicial   = $this->oDataInicial->getDate();
    $sDataFinal     = $this->oDataFinal->getDate();
    $oDaoManutencao = new cl_veicmanutitem();

    $sCampos  = " ve62_veiculos, ve63_tipoitem, sum(ve63_valortotalcomdesconto) as valor ";
    $sWhere   = " ve62_dtmanut BETWEEN '{$sDataInicial}' and '{$sDataFinal}' ";
    $sWhere  .= " and ve62_veiculos in (" . implode(", ", $this->aCodigoVeiculos) . ") ";

    if (!empty($this->iSituacao)) {
      $sWhere .= " and ve62_situacao = {$this->iSituacao} ";
    }

    $sSqlManutencao  = $oDaoManutencao->sql_query_ItensManutencao(null, $sCampos, null, $sWhere);
    $sSqlManutencao .= " group by ve62_veiculos, ve63_tipoitem order by ve62_veiculos";
    $rsManutencao   = $oDaoManutencao->sql_record($sSqlManutencao);
    if ($rsManutencao == false || $oDaoManutencao->numrows == 0) {
      return;
    }

    for ($iManutencao = 0; $iManutencao < $oDaoManutencao->numrows; $iManutencao++) {

      $oManutencao = db_utils::fieldsMemory($rsManutencao, $iManutencao);
      $iVeiculo    = $oManutencao->ve62_veiculos;
      if (!isset($this->aDadosVeiculos[$iVeiculo])) {
        continue;
      }

      if ($oManutencao->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_LAVAGEM) {
        $this->aDadosVeiculos[$iVeiculo]->lavagem = $oManutencao->valor;
      }

      if ($oManutencao->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_MAO_DE_OBRA) {
        $this->aDadosVeiculos[$iVeiculo]->mao_de_obra = $oManutencao->valor;
      }

      if ($oManutencao->ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_PECA) {
        $this->aDadosVeiculos[$iVeiculo]->pecas = $oManutencao->valor;
      }
    }
  }

  /**
   * Busca as informa��es de hod�metro para os ve�culos j� buscados pela getVeiculos.
   */
  private function getValoresHodometro() {

    $sHora = "23:59";
    $sDataInicial = $this->oDataInicial->getDate();
    $sDataFinal   = $this->oDataFinal->getDate();
    $oDaoVeiculos = new cl_veiculos();

    foreach ($this->aCodigoVeiculos as $iVeiculo) {

      $nUltimaMedidaInicial = 0;
      $nUltimaMedidaFinal   = 0;
      $sSqlHodometroInicial = $oDaoVeiculos->sql_query_ultimamedida($iVeiculo, $sDataInicial, $sHora);
      $sSqlHodometroFim     = $oDaoVeiculos->sql_query_ultimamedida($iVeiculo, $sDataFinal, $sHora);

      $rsHodometro = $oDaoVeiculos->sql_record($sSqlHodometroInicial);
      if ($rsHodometro != false && $oDaoVeiculos->numrows > 0) {

        $oHodometro = db_utils::fieldsMemory($rsHodometro, 0);
        $nUltimaMedidaInicial = $oHodometro->ultimamedida;
      }

      $rsHodometro = $oDaoVeiculos->sql_record($sSqlHodometroFim);
      if ($rsHodometro != false && $oDaoVeiculos->numrows > 0) {

        $oHodometro = db_utils::fieldsMemory($rsHodometro, 0);
        $nUltimaMedidaFinal = $oHodometro->ultimamedida;
      }

      if ($nUltimaMedidaInicial == 0 && $nUltimaMedidaFinal != 0) {
        $nUltimaMedidaInicial = $this->aDadosVeiculos[$iVeiculo]->medida_inicial;
      }

      $this->aDadosVeiculos[$iVeiculo]->hodometro = $nUltimaMedidaFinal - $nUltimaMedidaInicial;
    }
  }

  /**
   * Cria a linha totalizadora no atributo oTotalizador e faz os c�lculos de m�dia e total para cada linha.
   */
  private function processaVeiculos() {

    $oTotalizador = new stdClass();
    $oTotalizador->placa             = "TOTAL";
    $oTotalizador->modelo            = "";
    $oTotalizador->ano               = "";
    $oTotalizador->tombo             = "";
    $oTotalizador->hodometro         = 0;
    $oTotalizador->litros_abastecido = 0;
    $oTotalizador->valor_abastecido  = 0;
    $oTotalizador->media             = 0;
    $oTotalizador->lavagem           = 0;
    $oTotalizador->pecas             = 0;
    $oTotalizador->mao_de_obra       = 0;
    $oTotalizador->total             = 0;

    foreach ($this->aDadosVeiculos as $oVeiculo) {

      if ($oVeiculo->hodometro != 0) {
        $oVeiculo->media = $oVeiculo->valor_abastecido / $oVeiculo->hodometro;
      }

      $oVeiculo->total += $oVeiculo->valor_abastecido;
      $oVeiculo->total += $oVeiculo->lavagem;
      $oVeiculo->total += $oVeiculo->pecas;
      $oVeiculo->total += $oVeiculo->mao_de_obra;

      $oTotalizador->hodometro         += $oVeiculo->hodometro;
      $oTotalizador->litros_abastecido += $oVeiculo->litros_abastecido;
      $oTotalizador->valor_abastecido  += $oVeiculo->valor_abastecido;
      $oTotalizador->media             += $oVeiculo->media;
      $oTotalizador->lavagem           += $oVeiculo->lavagem;
      $oTotalizador->pecas             += $oVeiculo->pecas;
      $oTotalizador->mao_de_obra       += $oVeiculo->mao_de_obra;
      $oTotalizador->total             += $oVeiculo->total;
    }
    $this->oTotalizador = $oTotalizador;
  }
}