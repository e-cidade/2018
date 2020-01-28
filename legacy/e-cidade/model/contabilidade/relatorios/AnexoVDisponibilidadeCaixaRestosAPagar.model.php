<?php

/**
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

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoVDisponibilidadeCaixaRestosAPagar
 */
class AnexoVDisponibilidadeCaixaRestosAPagar extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 155;

  const ATIVO = 1;
  const PASSIVO = 2;

  /**
   * @type stdClass[int]
   */
  private $aRecursos = array();

  /**
   * @type stdClass[]
   */
  private $aRecursosRPPS = array();

  /**
   * @type stdClass[]
   */
  private $aLinhasRelatorio;

  /**
   * Define se está buscando as informações do RPPS
   * @type bool
   */
  private $lModoRPPS = false;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  private $iColumnWidth = 27;

  public function getDados() {

    $this->aLinhasRelatorio = parent::getLinhasRelatorio();
    $this->carregarBalanceteVerificacaoCaixaBruta();
    $this->carregarBalanceteVerificacaoObrigacoesFinanceiras();
    $this->carregarRestosAPagar();
    $this->carregarBalanceteDespesa();
    $this->calcularEdicaoManual(true);
    $this->calcularEdicaoManual(false);
    $this->carregarEmpenhosIndisponibilidadeFinanceira();
    $this->processarRPPS();
  }


  /**
   * Carrega as informações da primeira coluna e prepara o objeto para a impressão posterior
   * @throws Exception
   */
  protected function carregarBalanceteVerificacaoCaixaBruta() {

    $rsSqlBalanceteVerificacao = $this->processarBalanceteDeVerificacao(1);
    if (!$rsSqlBalanceteVerificacao) {
      return true;
    }

    $iTotalRegistros           = pg_num_rows($rsSqlBalanceteVerificacao);

    for ($iRowBalancete = 0; $iRowBalancete < $iTotalRegistros; $iRowBalancete++) {

      $oStdBalancete = db_utils::fieldsMemory($rsSqlBalanceteVerificacao, $iRowBalancete);
      if ($oStdBalancete->c61_reduz == "0") {
        continue;
  }

      $oRecurso = RecursoRepository::getRecursoPorCodigo($oStdBalancete->c61_codigo);
      $nValor = $oStdBalancete->sinal_final == "D" ? $oStdBalancete->saldo_final : ($oStdBalancete->saldo_final * -1);
      $this->getLinhaPorRecurso($oRecurso)->valores->caixa_bruta += $nValor;
    }
  }

  /**
   * Processa os valores da coluna Obrigações Financeiras
   * @throws Exception
   */
  private function carregarBalanceteVerificacaoObrigacoesFinanceiras() {

    $rsSqlBalanceteVerificacao = $this->processarBalanceteDeVerificacao(2);
    if (!$rsSqlBalanceteVerificacao) {
      return true;
    }

    $iTotalRegistros           = pg_num_rows($rsSqlBalanceteVerificacao);

    for ($iRowBalancete = 0; $iRowBalancete < $iTotalRegistros; $iRowBalancete++) {

      $oStdBalancete = db_utils::fieldsMemory($rsSqlBalanceteVerificacao, $iRowBalancete);
      if ($oStdBalancete->c61_reduz == "0") {
        continue;
      }

      $iGrupoConta = substr($oStdBalancete->estrutural, 0, 1);
      if (($iGrupoConta == self::ATIVO && $oStdBalancete->sinal_final == 'C') || ($iGrupoConta == self::PASSIVO && $oStdBalancete->sinal_final == 'D')) {
        $oStdBalancete->saldo_final *= -1;
      }

      $oRecurso = RecursoRepository::getRecursoPorCodigo($oStdBalancete->c61_codigo);
      $this->getLinhaPorRecurso($oRecurso)->valores->demais_obrigacoes += $oStdBalancete->saldo_final;
    }
  }

  /**
   * Executa o balancete de verificação de acordo com a configuração do usuário
   * @param int $iIndice
   * @return bool|resource|string
   * @throws Exception
   */
  private function processarBalanceteDeVerificacao($iIndice = 1) {

    if (empty($this->aLinhasRelatorio[$iIndice]->parametros->contas)) {
      return false;
    }

    $aEstruturais= array();
    foreach ($this->aLinhasRelatorio[$iIndice]->parametros->contas as $oStdParametroConta) {

      $sEstrutural = substr($oStdParametroConta->estrutural, 0, $oStdParametroConta->nivel);
      $aEstruturais[] = "p.c60_estrut ilike '{$sEstrutural}%' ";
    }

    $sWhere  = "     c61_instit in ({$this->getInstituicoes()})";

    if (!empty($aEstruturais)) {
      $sWhere .= " and (".implode(' or ', $aEstruturais).")";
    }

    $this->limparEstruturaBalanceteVerificacao();
    $rsSqlBalanceteVerificacao =
      db_planocontassaldo_matriz(
        $this->iAnoUsu,
        $this->getDataInicial()->getDate(),
        $this->getDataFinal()->getDate(),
        false,
        $sWhere,
        '',
        'true',
        'false');

    if (!$rsSqlBalanceteVerificacao) {
      throw new Exception("Não foi possível executar o balancete de verificação.");
    }
    return $rsSqlBalanceteVerificacao;
  }

  /**
   * Carrega as colunas referentes a restos a pagar do relatório
   * @throws Exception
   */
  private function carregarRestosAPagar() {

    $oDaoRestosAPagar = new cl_empresto();
    $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo($this->iAnoUsu,
                                                       $sWhereRestoPagar,
                                                       $this->getDataInicial()->getDate(),
                                                       $this->getDataFinal()->getDate());
    $rsBuscaRestosAPagar = db_query($sSqlRestosaPagar);
    $iTotalRegistros     = pg_num_rows($rsBuscaRestosAPagar);
    if (!$rsBuscaRestosAPagar) {
      throw new Exception("Não foi possível verificar os empenhos de restos à pagar.");
      }

    for ($iRowRP = 0; $iRowRP < $iTotalRegistros; $iRowRP++) {

      $oStdRestos = db_utils::fieldsMemory($rsBuscaRestosAPagar, $iRowRP);
      $oRecurso   = RecursoRepository::getRecursoPorCodigo($oStdRestos->e91_recurso);
      $nValorPagoExercicioAnterior      = ValorRestoAPagar::saldoFinalLiquidado($oStdRestos);
      $nValorEmpenhadoExercicioAnterior = ValorRestoAPagar::saldoFinalALiquidar($oStdRestos);
      $this->getLinhaPorRecurso($oRecurso)->valores->rp_nao_pago_exercicio_anterior  += $nValorPagoExercicioAnterior;
      $this->getLinhaPorRecurso($oRecurso)->valores->rp_empenhado_exercicio_anterior += $nValorEmpenhadoExercicioAnterior;
    }
  }

  /**
   * @throws Exception
   */
  private function carregarBalanceteDespesa() {

    $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate());
    $iTotalRegistros = pg_num_rows($rsBalanceteDespesa);
    if (!$rsBalanceteDespesa) {
      throw new Exception("Não foram encontradas despesas para os filtros selecionados.");
    }

    for ($iRowDespesa = 0; $iRowDespesa < $iTotalRegistros; $iRowDespesa++) {

      $oStdDespesa = db_utils::fieldsMemory($rsBalanceteDespesa, $iRowDespesa);
      $oRecurso    = RecursoRepository::getRecursoPorCodigo($oStdDespesa->o58_codigo);

      $this->getLinhaPorRecurso($oRecurso)->valores->rp_nao_pago_exercicio_atual += ValorDespesa::aPagarLiquidado($oStdDespesa);
      $this->getLinhaPorRecurso($oRecurso)->valores->rp_empenhado_nao_liquidado  += ValorDespesa::aLiquidar($oStdDespesa);
    }
  }

  /**
   * Calcula as unicas duas colunas que possui edição manual
   * @param bool|true  $lCaixaBruta
   */
  private function calcularEdicaoManual($lCaixaBruta = true) {

    $sCampo  = 'caixa_bruta';
    $iIndice = 1;
    if (!$lCaixaBruta) {
      $sCampo = 'demais_obrigacoes';
      $iIndice = 2;
    }

    $aColunasManuais = $this->aLinhasRelatorio[$iIndice]->oLinhaRelatorio->getValoresColunas();
    foreach ($aColunasManuais as $oStdColuna) {

      $oRecurso = RecursoRepository::getRecursoPorCodigo($oStdColuna->colunas[0]->o117_valor);
      $this->getLinhaPorRecurso($oRecurso)->valores->{$sCampo} += $oStdColuna->colunas[1]->o117_valor;
    }
  }

  /**
   * Carrega os empenhos anulados no ano corrente e que sejam do tipo Indisponibilidade Financeira
   */
  private function carregarEmpenhosIndisponibilidadeFinanceira() {

    $aWhere = array(
      'empanulado.e94_empanuladotipo = 1',
      "empempenho.e60_instit in ({$this->getInstituicoes()})",
      "empanulado.e94_data >= '{$this->iAnoUsu}-01-01'",
    );

    $sWhere  = implode(' and ', $aWhere);
    $sWhere .= " group by o58_codigo ";
    $oDaoAnulacaoEmpenho = new cl_empanulado();
    $sSqlBuscaEmpenhos   = $oDaoAnulacaoEmpenho->sql_query_empenho("o58_codigo, sum(e94_valor) as valor_anulado", $sWhere);
    $rsBuscaEmpenhos     = $oDaoAnulacaoEmpenho->sql_record($sSqlBuscaEmpenhos);
    if ($rsBuscaEmpenhos === false || $oDaoAnulacaoEmpenho->numrows == 0) {
      return;
    }

    for ($iRowEmpenho = 0; $iRowEmpenho < $oDaoAnulacaoEmpenho->numrows; $iRowEmpenho++) {

      $oStdEmpenho = db_utils::fieldsMemory($rsBuscaEmpenhos, $iRowEmpenho);
      $oRecurso    = RecursoRepository::getRecursoPorCodigo($oStdEmpenho->o58_codigo);
      $this->getLinhaPorRecurso($oRecurso)->valores->rp_insuficiencia_financeira += $oStdEmpenho->valor_anulado;
    }
  }

  /**
   * Processa os valores do RPPS caso exista
   * @throws Exception
   */
  private function processarRPPS() {

    $oDaoConfig    = new cl_db_config();
    $sSqlBuscaRPPS = $oDaoConfig->sql_query_file(null, "codigo", null, "db21_tipoinstit = 5");
    $rsBuscaRPPS   = $oDaoConfig->sql_record($sSqlBuscaRPPS);
    if ($rsBuscaRPPS === false || $oDaoConfig->numrows == 0) {
      return;
    }

    $sInstituicoesSelecionadas = $this->getInstituicoes();
    $iCodigoInstituicao = db_utils::fieldsMemory($rsBuscaRPPS, 0)->codigo;

    $this->setInstituicoes($iCodigoInstituicao);
    $this->lModoRPPS = true;
    $this->carregarBalanceteVerificacaoCaixaBruta();
    $this->carregarBalanceteVerificacaoObrigacoesFinanceiras();
    $this->carregarRestosAPagar();
    $this->carregarBalanceteDespesa();
    $this->calcularEdicaoManual(true);
    $this->calcularEdicaoManual(false);
    $this->carregarEmpenhosIndisponibilidadeFinanceira();
    $this->setInstituicoes($sInstituicoesSelecionadas);
    $this->lModoRPPS = false;

    $oStdRecurso = new stdClass();
    $oStdRecurso->codigo    = 0;
    $oStdRecurso->descricao = "RPPS";
    $oStdRecurso->vinculado = '';
    $oStdRecurso->valores->caixa_bruta                     = 0;
    $oStdRecurso->valores->rp_nao_pago_exercicio_anterior  = 0;
    $oStdRecurso->valores->rp_nao_pago_exercicio_atual     = 0;
    $oStdRecurso->valores->rp_empenhado_exercicio_anterior = 0;
    $oStdRecurso->valores->demais_obrigacoes               = 0;
    $oStdRecurso->valores->rp_empenhado_nao_liquidado      = 0;
    $oStdRecurso->valores->rp_insuficiencia_financeira     = 0;

    foreach ($this->aRecursosRPPS as $aRecursos) {

      foreach ($aRecursos as $iCodigoRecurso => $oStdDadosRecurso) {

        $oStdRecurso->valores->caixa_bruta                     += $oStdDadosRecurso->valores->caixa_bruta;
        $oStdRecurso->valores->rp_nao_pago_exercicio_anterior  += $oStdDadosRecurso->valores->rp_nao_pago_exercicio_anterior;
        $oStdRecurso->valores->rp_nao_pago_exercicio_atual     += $oStdDadosRecurso->valores->rp_nao_pago_exercicio_atual;
        $oStdRecurso->valores->rp_empenhado_exercicio_anterior += $oStdDadosRecurso->valores->rp_empenhado_exercicio_anterior;
        $oStdRecurso->valores->demais_obrigacoes               += $oStdDadosRecurso->valores->demais_obrigacoes;
        $oStdRecurso->valores->rp_empenhado_nao_liquidado      += $oStdDadosRecurso->valores->rp_empenhado_nao_liquidado;
        $oStdRecurso->valores->rp_insuficiencia_financeira     += $oStdDadosRecurso->valores->rp_insuficiencia_financeira;
      }
    }
    $this->aRecursosRPPS = $oStdRecurso;
  }

  /**
   * Retorna a linha do recurso informado por parâmetro verificando se o objeto está impressão modo RPPS
   * @param Recurso $oRecurso
   * @return stdClass
   */
  private function getLinhaPorRecurso(Recurso $oRecurso) {

    $sRecurso = $this->lModoRPPS ? 'aRecursosRPPS' : 'aRecursos';
    if (empty($this->{$sRecurso}[$oRecurso->getTipoRecurso()][$oRecurso->getCodigo()])) {
      $this->{$sRecurso}[$oRecurso->getTipoRecurso()][$oRecurso->getCodigo()] = self::getLinhaRelatorio($oRecurso);
    }
    return $this->{$sRecurso}[$oRecurso->getTipoRecurso()][$oRecurso->getCodigo()];
  }

  /**
   * @param Recurso $oRecurso
   * @return stdClass
   */
  private static function getLinhaRelatorio(Recurso $oRecurso) {

    $oStdRecurso = new stdClass();
    $oStdRecurso->codigo    = $oRecurso->getCodigo();
    $oStdRecurso->descricao = $oRecurso->getDescricao();
    $oStdRecurso->vinculado = $oRecurso->getTipoRecurso() == Recurso::VINCULADO;
    $oStdRecurso->valores   = new stdClass();
    $oStdRecurso->valores->caixa_bruta                     = 0;
    $oStdRecurso->valores->rp_nao_pago_exercicio_anterior  = 0;
    $oStdRecurso->valores->rp_nao_pago_exercicio_atual     = 0;
    $oStdRecurso->valores->rp_empenhado_exercicio_anterior = 0;
    $oStdRecurso->valores->demais_obrigacoes               = 0;
    $oStdRecurso->valores->rp_empenhado_nao_liquidado      = 0;
    $oStdRecurso->valores->rp_insuficiencia_financeira     = 0;
    return $oStdRecurso;
  }



  public function emitir() {

    $this->getDados();

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->oPdf->Open();

    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();


    $aInstituicoes = explode(",", $this->getInstituicoes());

    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    } else {
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $this->oPdf->addHeaderDescription("RELATÓRIO DE GESTÃO FISCAL");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA E DOS RESTOS A PAGAR");
    $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("");

    $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$this->iAnoUsu}");

    $this->oPdf->setAutoPageBreak(false, 10);
    $this->oPdf->addPage();
    $this->oPdf->setFontSize(7);
    $this->oPdf->setFillColor(230);

    $this->escreverCabecalho(true);

    if (!isset($this->aRecursos[Recurso::LIVRE])) {
      $this->aRecursos[Recurso::LIVRE] = array();
    }

    if (!isset($this->aRecursos[Recurso::VINCULADO])) {
      $this->aRecursos[Recurso::VINCULADO] = array();
    }


    $this->ordenarRecurso(Recurso::LIVRE);
    $this->ordenarRecurso(Recurso::VINCULADO);
    $oTotalizadorVinculado = array_reduce($this->aRecursos[Recurso::VINCULADO], function($oAnterior, $oAtual) {

      foreach ($oAnterior as $sProp => $nValor) {
        $oAnterior->{$sProp} += $oAtual->valores->{$sProp};
      }

      return $oAnterior;
    }, (object) array(
      'caixa_bruta' => 0,
      'rp_nao_pago_exercicio_anterior' => 0,
      'rp_nao_pago_exercicio_atual' => 0,
      'rp_empenhado_exercicio_anterior' => 0,
      'demais_obrigacoes' => 0,
      'rp_empenhado_nao_liquidado' => 0,
      'rp_insuficiencia_financeira' => 0
    ));

    $oTotalizadorVinculado = (object) array(
        'codigo' => '',
        'descricao' => "TOTAL DOS RECURSOS VINCULADOS (I)",
        'valores' => $oTotalizadorVinculado
      );

    $oTotalizadorLivre = array_reduce($this->aRecursos[Recurso::LIVRE], function($oAnterior, $oAtual) {

      foreach ($oAnterior as $sProp => $nValor) {
        $oAnterior->{$sProp} += $oAtual->valores->{$sProp};
      }

      return $oAnterior;
    }, (object) array(
      'caixa_bruta' => 0,
      'rp_nao_pago_exercicio_anterior' => 0,
      'rp_nao_pago_exercicio_atual' => 0,
      'rp_empenhado_exercicio_anterior' => 0,
      'demais_obrigacoes' => 0,
      'rp_empenhado_nao_liquidado' => 0,
      'rp_insuficiencia_financeira' => 0
    ));

    $oTotalizadorLivre = (object) array(
        'codigo' => '',
        'descricao' => "TOTAL DOS RECURSOS NÃO VINCULADOS (II)",
        'valores' => $oTotalizadorLivre
      );

    $oTotal = (object) array(
        'codigo' => '',
        'descricao' => "TOTAL (III) = (I + II)",
        'valores' => (object) array(
            'caixa_bruta' => $oTotalizadorVinculado->valores->caixa_bruta + $oTotalizadorLivre->valores->caixa_bruta,
            'rp_nao_pago_exercicio_anterior' => $oTotalizadorVinculado->valores->rp_nao_pago_exercicio_anterior + $oTotalizadorLivre->valores->rp_nao_pago_exercicio_anterior,
            'rp_nao_pago_exercicio_atual' => $oTotalizadorVinculado->valores->rp_nao_pago_exercicio_atual + $oTotalizadorLivre->valores->rp_nao_pago_exercicio_atual,
            'rp_empenhado_exercicio_anterior' => $oTotalizadorVinculado->valores->rp_empenhado_exercicio_anterior + $oTotalizadorLivre->valores->rp_empenhado_exercicio_anterior,
            'demais_obrigacoes' => $oTotalizadorVinculado->valores->demais_obrigacoes + $oTotalizadorLivre->valores->demais_obrigacoes,
            'rp_empenhado_nao_liquidado' => $oTotalizadorVinculado->valores->rp_empenhado_nao_liquidado + $oTotalizadorLivre->valores->rp_empenhado_nao_liquidado,
            'rp_insuficiencia_financeira' => $oTotalizadorVinculado->valores->rp_insuficiencia_financeira + $oTotalizadorLivre->valores->rp_insuficiencia_financeira
          )
      );

    $this->escreverLinha($oTotalizadorVinculado, true, true);

    foreach($this->aRecursos[Recurso::VINCULADO] as $oRecurso) {
      $this->escreverLinha($oRecurso);
    }

    $this->escreverLinha($oTotalizadorLivre, true, true);

    foreach($this->aRecursos[Recurso::LIVRE] as $oRecurso) {
      $this->escreverLinha($oRecurso);
    }


    $this->escreverLinha($oTotal, true, true);

    if (!empty($this->aRecursosRPPS)) {

      $this->aRecursosRPPS->descricao = "REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES¹";
      $this->escreverLinha($this->aRecursosRPPS, true);
    }

    $this->oPdf->Ln(1);

    $iAlturaAssinatura = 26;
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->SetAutoPageBreak(true, 10);
    $this->notaExplicativa($this->oPdf, array($this, 'novaPagina'), $iAlturaAssinatura);
    $this->oPdf->SetAutoPageBreak(false, 10);
    $this->oPdf->setAutoNewLineMulticell(false);

    $this->oPdf->Ln(10);
    $this->getRelatorioContabil()->assinatura($this->oPdf, 'GF', false);

    $this->oPdf->showPDF("RGF_anexo_5_DisponibilidadeCaixa_Restos");
  }

  /**
   * Configura os recursos
   * @param $iTipoRecurso
   */
  private function ordenarRecurso($iTipoRecurso) {

    ksort($this->aRecursos[$iTipoRecurso]);
    foreach ($this->aRecursos[$iTipoRecurso] as $oStdRecurso) {

      $lExcluirRecursoZerado = true;
      $aPropriedades = get_object_vars($oStdRecurso->valores);
      foreach ($aPropriedades as $sPropriedade => $sValor) {

        if ($oStdRecurso->valores->{$sPropriedade} > 0) {
          $lExcluirRecursoZerado = false;
          break;
        }
      }

      if ($lExcluirRecursoZerado) {
        unset($this->aRecursos[$iTipoRecurso][$oStdRecurso->codigo]);
      }
    }
  }

  private function escreverCabecalho($lExibirDescritivo = false) {

    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);

    if ($lExibirDescritivo) {
      $this->oPdf->cell(150, 3, "RGF - ANEXO 5 (LRF, art. 55, Inciso III, alínea \"a\")");
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 3, "R$ 1,00", 0, 1, 'R');
    }

    $this->oPdf->cell($this->oPdf->getAvailWidth() - ($this->iColumnWidth*8), 30, "IDENTIFICAÇÃO DOS RECURSOS", 1, 0, 'C', 1);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\n\nDISPONIBILIDADE DE CAIXA BRUTA\n\n\n\n\n\n(a)", 1, 'C', 1);

    $iX = $this->oPdf->getX();
    $iY = $this->oPdf->getY();

    $this->oPdf->cell($this->iColumnWidth*4, 6, "OBRIGAÇÕES FINANCEIRAS", 1, 1, 'C', 1);
    $this->oPdf->setX($iX);

    $this->oPdf->setAutoNewLineMulticell(true);
    $iY2 = $this->oPdf->getY();
    $this->oPdf->multiCell($this->iColumnWidth*2, 3, "Restos a Pagar Liquidados\n e Não Pagos", 1, 'C', 1);
    $this->oPdf->setAutoNewLineMulticell(false);

    $this->oPdf->setX($iX);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\nDe Exercícios\nAnteriores\n\n\n(b)", 1, 'C', 1);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\nDo Exercício\n\n\n\n(c)", 1, 'C', 1);

    $this->oPdf->setXY($this->oPdf->getX(), $iY2);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\nRestos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores\n\n(d)", 1, 'C', 1);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\n\nDemais Obrigações Financeiras\n\n\n\n(e)", 1, 'C', 1);

    $this->oPdf->setXY($this->oPdf->getX(), $iY);

    $this->oPdf->multiCell($this->iColumnWidth, 3, "DISPONIBILIDADE DE CAIXA LÍQUIDA (ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO)\n\n(f) = (a-(b+c+d+e))", 1, 'C', 1);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\n\nRESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO\n\n\n\n\n", 1, 'C', 1);
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->multiCell($this->iColumnWidth, 3, "\n\nEMPENHOS NÃO LIQUIDADOS CANCELADOS (NÃO INSCRITOS POR INSUFICIÊNCIA FINANCEIRA)\n\n\n", 1, 'C', 1);
  }

  private function escreverLinha($oRecurso, $lTotalizador = false, $lBold = false) {

    $nWidthFirstColumn = $this->oPdf->getAvailWidth() - ($this->iColumnWidth*8);
    $sRecurso          = "  {$oRecurso->codigo} - {$oRecurso->descricao}";
    $sBorda            = '';

    if ($lTotalizador) {
      $sRecurso = $oRecurso->descricao;
      $sBorda   = "T:B";
    }
    $iMulticellHeight  = $this->oPdf->getMultiCellHeight($nWidthFirstColumn, 4, $sRecurso);

    if ($this->oPdf->getAvailHeight() < ($iMulticellHeight + 8)) {
      $this->novaPagina(true);
    }
    $this->oPdf->setBold($lBold);
    $this->oPdf->setAutoNewLineMulticell(false);

    $nDisponibilidadeLiquida = $oRecurso->valores->caixa_bruta - (
      $oRecurso->valores->rp_nao_pago_exercicio_anterior +
      $oRecurso->valores->rp_nao_pago_exercicio_atual +
      $oRecurso->valores->rp_empenhado_exercicio_anterior +
      $oRecurso->valores->demais_obrigacoes );

    $oRecurso = $this->formatarValor($oRecurso);
    $this->oPdf->multiCell($nWidthFirstColumn, 4, $sRecurso, "R:{$sBorda}", 'L');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->caixa_bruta, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->rp_nao_pago_exercicio_anterior, 'f'), "R:$sBorda", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->rp_nao_pago_exercicio_atual, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->rp_empenhado_exercicio_anterior, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->demais_obrigacoes, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($nDisponibilidadeLiquida, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->rp_empenhado_nao_liquidado, 'f'), "R:{$sBorda}", 0, 'R');
    $this->oPdf->cell($this->iColumnWidth, $iMulticellHeight, db_formatar($oRecurso->valores->rp_insuficiencia_financeira, 'f'), $sBorda, 1, 'R');
  }

  /**
   * @param $oRecurso
   * @return mixed
   */
  private function formatarValor($oRecurso) {

    $aVariaveisValor = get_object_vars($oRecurso->valores);
    foreach ($aVariaveisValor as $sPropriedade => $sValor) {
      if (trim(db_formatar($sValor, 'f')) == '-0,00') {
        $oRecurso->valores->{$sPropriedade} = 0;
      }
    }
    return $oRecurso;
  }

  /**
   * Adicona uma nova página, com cabeçalho e rodapé.
   *
   * @param bool $lEscreverCabecalho Se deve escrever o cabeçalho do relatório.
   */
  public function novaPagina($lEscreverCabecalho = false) {

    $iProximaPagina = $this->oPdf->PageNo() + 1;

    $this->oPdf->setBold(true);

    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 4, "Continua na Página {$iProximaPagina}/{nb}", 'T', 0, 'R');
    $this->oPdf->AddPage();
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 4, "Continuação {$this->oPdf->PageNo()}/{nb}", 0, 1, 'R');

    if ($lEscreverCabecalho) {
      $this->escreverCabecalho();
    }
  }
}
