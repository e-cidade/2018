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
require_once(modification("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php"));
require_once(modification("fpdf151/PDFDocument.php"));

/**
 * Class AnexoIBalancoOrcamentario
 */
class AnexoIBalancoOrcamentario extends RelatoriosLegaisBase  {

  /**
   * Código do Relatório cadastrado no e-cidade
   * @type integer
   */
  const CODIGO_RELATORIO = 145;

  /**
   * @type PDFDocument
   */
  private $oPdf;

  /**
   * @type stdClass[]
   */
  private $aDespesas = array();

  /**
   * @type stdClass[]
   */
  private $aDespesasIntra = array();

  /**
   * @type stdClass[]
   */
  private $aReceitas = array();

  /**
   * @type stdClass[]
   */
  private $aReceitasIntra = array();

  /**
   * Linha Inicial referente a Receita
   * @type integer
   */
  const LINHA_RECEITA_INICIAL = 1;

  /**
   * Linha Inicial referente a Receita
   * @type integer
   */
  const LINHA_RECEITA_FINAL   = 76;

  /**
   * Linha Inicial referente a Receita Intra
   * @type integer
   */
  const LINHA_RECEITA_INTRA_INICIAL = 100;

  /**
   * Linha Inicial referente a Receita Intra
   * @type integer
   */
  const LINHA_RECEITA_INTRA_FINAL = 160;

  /**
   * Linha Inicial da Despesa
   * @type integer
   */
  const LINHA_DESPESA_INICIAL = 77;

  /**
   * Linha Final da Despesa
   * @type integer
   */
  const LINHA_DESPESA_FINAL = 99;

  /**
   * Linha que começa a despesa intra
   * @type integer
   */
  const LINHA_DESPESA_INTRA_INICIAL = 161;

  /**
   * Linha que termina a despesa intra
   * @type integer
   */
  const LINHA_DESPESA_INTRA_FINAL = 169;

  /**
   * Linhas que são totalizadoras no relatório
   * @type array
   */
  private static $aLinhasTotalizadoras = array(63, 71, 72, 73, 89, 97, 98, 99);

  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->setDataInicial($this->getDataInicialPeriodo());
  }

  /**
   * @return void
   */
  public function emitir() {

    $this->getDados();

    $this->processar();
    $this->processarExerciciosAnteriores();

    $this->oPdf = new PDFDocument("L");
    $this->oPdf->Open();
    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
    $this->oPdf->addHeaderDescription("MUNICÍPIO DE ".$oPrefeitura->getMunicipio() . " - " . $oPrefeitura->getUf());
    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("BALANÇO ORÇAMENTÁRIO");
    $this->oPdf->addHeaderDescription("ORÇAMENTO FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("");

    $this->oPdf->addHeaderDescription($this->getTituloPeriodo());


    $this->adicionarPagina();
    $this->imprimirCabecalhoReceita();
    $this->imprimirReceita(false);
    $this->adicionarPagina();
    $this->imprimirCabecalhoDespesa();
    $this->imprimirDespesa();
    $this->adicionarPagina();
    $this->imprimirCabecalhoReceita(true);
    $this->imprimirReceita(true);
    $this->adicionarPagina();
    $this->imprimirCabecalhoDespesa(true);
    $this->imprimirDespesa(true);
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 2, '', "T", 1);

    $this->oPdf->Ln(10);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
    $this->oPdf->Ln(10);
    $this->imprimirAssinaturas();

    $this->oPdf->showPDF('RREO_Anexo_I_BalancoOrcamentario');
  }

  /**
   * Carrega os dados para emissão do relatório
   * @return array
   */
  public function getDados() {

    parent::getDados();
    $aReservaRPPS        = array();
    $aReservaContigencia = array();
    $aInstituicao        = $this->getInstituicoes(true);
    foreach ($aInstituicao as $oInstituicao) {

      if (in_array($oInstituicao->getTipo(), array(5 , 6))) {
        $aReservaRPPS[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
      } else {
        $aReservaContigencia[$oInstituicao->getCodigo()] = $oInstituicao->getCodigo();
      }
    }

    /*
     * Limpa os valores das linhas 86 e 87
     */
    foreach ($this->aLinhasConsistencia[86]->colunas as $oStdColuna) {

      $this->aLinhasConsistencia[86]->{$oStdColuna->o115_nomecoluna} = 0;
      $this->aLinhasConsistencia[87]->{$oStdColuna->o115_nomecoluna} = 0;
    }

    /**
     * RESERVA DE CONTINGENTE
     */
    $oLinhaContingencia = $this->aLinhasConsistencia[86];
    if (count($aReservaContigencia) > 0) {

      $sWhereDespesa      = " o58_instit in (".implode(',', $aReservaContigencia).")";
      $rsBalanceteDespesa = db_dotacaosaldo(
        8,2,2, true, $sWhereDespesa,
        $this->iAnoUsu,
        $this->getDataInicial()->getDate(),
        $this->getDataFinal()->getDate()
      );

      $aColunasProcessar  = $this->processarColunasDaLinha($oLinhaContingencia);
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
        $oLinhaContingencia,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
      );
    }

    /**
     * RESERVA DE RPPS
     */
    $oLinhaRPPS = $this->aLinhasConsistencia[87];
    if (count($aReservaRPPS) > 0) {

      $sWhereDespesa      = " o58_instit in (".implode(',', $aReservaRPPS).")";
      $rsBalanceteDespesa = db_dotacaosaldo(
        8,2,2, true, $sWhereDespesa,
        $this->iAnoUsu,
        $this->getDataInicial()->getDate(),
        $this->getDataFinal()->getDate()
      );

      $aColunasProcessar = $this->processarColunasDaLinha($oLinhaRPPS);
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
        $oLinhaRPPS,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
      );
    }

    /**
     * Soma os valores encontrados para as linhas 86 e 87
     */
    foreach ($this->aLinhasConsistencia[77]->colunas as $oStdColuna) {

      $this->aLinhasConsistencia[77]->{$oStdColuna->o115_nomecoluna} += $oLinhaContingencia->{$oStdColuna->o115_nomecoluna};
      $this->aLinhasConsistencia[77]->{$oStdColuna->o115_nomecoluna} += $oLinhaRPPS->{$oStdColuna->o115_nomecoluna};
      $this->aLinhasConsistencia[89]->{$oStdColuna->o115_nomecoluna} += $oLinhaContingencia->{$oStdColuna->o115_nomecoluna};
      $this->aLinhasConsistencia[89]->{$oStdColuna->o115_nomecoluna} += $oLinhaRPPS->{$oStdColuna->o115_nomecoluna};

      $this->aLinhasConsistencia[97]->{$oStdColuna->o115_nomecoluna} = $this->aLinhasConsistencia[89]->{$oStdColuna->o115_nomecoluna} + $this->aLinhasConsistencia[90]->{$oStdColuna->o115_nomecoluna};
      $this->aLinhasConsistencia[99]->{$oStdColuna->o115_nomecoluna} = $this->aLinhasConsistencia[97]->{$oStdColuna->o115_nomecoluna} + $this->aLinhasConsistencia[98]->{$oStdColuna->o115_nomecoluna};
    }

    /**
     * Calcula a linha do Deficit -- Linha 72
     */
    if ($this->aLinhasConsistencia[97]->liq_atebim > $this->aLinhasConsistencia[71]->recatebim) {

      $this->aLinhasConsistencia[72]->recatebim = abs($this->aLinhasConsistencia[97]->liq_atebim-$this->aLinhasConsistencia[71]->recatebim);
      $this->aLinhasConsistencia[73]->recatebim += $this->aLinhasConsistencia[72]->recatebim;
    }

    return $this->aLinhasConsistencia;
  }

  /**
   * Adiciona uma nova página no relatório
   * @return void
   */
  private function adicionarPagina() {

    $this->oPdf->SetFontSize(5);

    if ($this->oPdf->getCurrentPage() > 0) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continua na página " . ($this->oPdf->PageNo() + 1) . "/{nb}", 'T', 0, 'R');
    }
    $this->oPdf->addPage();
    if ($this->oPdf->getCurrentPage() != 1) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continuação da página " . ($this->oPdf->PageNo() - 1) . "/{nb}", 'B', 1, 'R');
    }

    $this->oPdf->setBold(true);
    $this->oPdf->Cell(100, 3, 'RREO - Anexo 1 (LRF, Art. 52, inciso I, alíneas "a" e "b" do inciso II e §1º)', 0, 0);
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 3, 'R$ 1,00', 0 , 1, PDFDocument::ALIGN_RIGHT);
    $this->oPdf->SetFontSize(6);
    $this->oPdf->setBold(false);
  }


  /**
   * Percorre as linhas do relatório ajustando as propriedades para imprimir posteriormente
   * @return void
   */
  private function processar() {

    foreach ($this->aLinhasConsistencia as $iOrdem => $oStdDadosLinha) {

      if (Check::between($iOrdem, self::LINHA_RECEITA_INICIAL, self::LINHA_RECEITA_FINAL)) {
        $this->adicionarReceita($oStdDadosLinha, false);
      } else if (Check::between($iOrdem, self::LINHA_DESPESA_INICIAL, self::LINHA_DESPESA_FINAL)) {
        $this->adicionarDespesa($oStdDadosLinha, false);
      } else if (Check::between($iOrdem, self::LINHA_RECEITA_INTRA_INICIAL, self::LINHA_RECEITA_INTRA_FINAL)) {
        $this->adicionarReceita($oStdDadosLinha, true);
      } else if (Check::between($iOrdem, self::LINHA_DESPESA_INTRA_INICIAL, self::LINHA_DESPESA_INTRA_FINAL)) {
        $this->adicionarDespesa($oStdDadosLinha, true);
      }
    }
  }

  /**
   * Processa totalizadores do Superavit / Créditos Adicionais
   * @throws \Exception
   * @return void
   */
  private function processarExerciciosAnteriores() {

    $aWhereSuperavit = array(
      "o46_tiposup in (1008, 1003)",
      "o49_data between '{$this->iAnoUsu}-01-01' and '{$this->getDataFinal()->getDate()}'",
      "o46_instit in ({$this->getInstituicoes()})"
    );
    $oDaoOrcSuplem      = new cl_orcsuplem();
    $sSqlBuscaSuperavit = $oDaoOrcSuplem->sql_query_suplementacoes(null, "coalesce(sum(o47_valor), 0) as total", null, implode(" and ", $aWhereSuperavit));
    $rsBuscaSuperavit   = db_query($sSqlBuscaSuperavit);
    if (!$rsBuscaSuperavit) {
      throw new Exception("Ocorreu um erro na busca dos valores de suplementação da coluna SUPERAVIT.");
    }


    $aWhereCreditos = array(
      "o46_tiposup in (1012, 1013)",
      "o49_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
      "o46_instit in ({$this->getInstituicoes()})"
    );
    $sSqlBuscaCreditos = $oDaoOrcSuplem->sql_query_suplementacoes(null, "coalesce(sum(o47_valor), 0) as total", null, implode(" and ", $aWhereCreditos));
    $rsBuscaCreditos   = db_query($sSqlBuscaCreditos);
    if (!$rsBuscaCreditos) {
      throw new Exception("Ocorreu um erro na busca dos valores de suplementação da coluna CRÉDITOS ADICIONAIS.");
    }

    $nValorSuperavit = db_utils::fieldsMemory($rsBuscaSuperavit, 0)->total;
    $nValorCreditos  = db_utils::fieldsMemory($rsBuscaCreditos, 0)->total;

    $this->aReceitas[75]->previsao_atualizada   += $nValorSuperavit;
    $this->aReceitas[75]->receita_ate_bimestre  += $nValorSuperavit;
    $this->aReceitas[76]->previsao_atualizada   += $nValorCreditos;
    $this->aReceitas[76]->receita_ate_bimestre  += $nValorCreditos;
    $this->aReceitas[74]->previsao_atualizada   += ($this->aReceitas[75]->previsao_atualizada+$this->aReceitas[76]->previsao_atualizada);
    $this->aReceitas[74]->receita_ate_bimestre  += ($this->aReceitas[75]->receita_ate_bimestre+$this->aReceitas[76]->receita_ate_bimestre);

    $this->aReceitas[75]->previsao_atualizada  = trim(db_formatar($this->aReceitas[75]->previsao_atualizada, 'f'));
    $this->aReceitas[75]->receita_ate_bimestre = trim(db_formatar($this->aReceitas[75]->receita_ate_bimestre, 'f'));
    $this->aReceitas[76]->previsao_atualizada  = trim(db_formatar($this->aReceitas[76]->previsao_atualizada, 'f'));
    $this->aReceitas[76]->receita_ate_bimestre = trim(db_formatar($this->aReceitas[76]->receita_ate_bimestre, 'f'));
    $this->aReceitas[74]->previsao_atualizada  = trim(db_formatar($this->aReceitas[74]->previsao_atualizada, 'f'));
    $this->aReceitas[74]->receita_ate_bimestre = trim(db_formatar($this->aReceitas[74]->receita_ate_bimestre, 'f'));
  }

  /**
   * @param stdClass $oStdDadosLinha
   * @param boolean $lReceitaIntraOrcamentaria
   * @return void
   */
  private function adicionarReceita(stdClass $oStdDadosLinha, $lReceitaIntraOrcamentaria = false) {

    $oStdRetorno                        = new stdClass();
    $oStdRetorno->ordem                 = $oStdDadosLinha->oLinhaRelatorio->getOrdem();
    $oStdRetorno->totalizar             = $oStdDadosLinha->totalizar;
    $oStdRetorno->descricao             = $oStdDadosLinha->descricao;
    $oStdRetorno->nivel                 = $oStdDadosLinha->nivel;
    $oStdRetorno->previsao_inicial      = trim(db_formatar($oStdDadosLinha->previni, 'f'));
    $oStdRetorno->previsao_atualizada   = trim(db_formatar($oStdDadosLinha->prevatu, 'f'));
    $oStdRetorno->receita_no_bimestre   = trim(db_formatar($oStdDadosLinha->recnobim, 'f'));
    $oStdRetorno->receita_ate_bimestre  = trim(db_formatar($oStdDadosLinha->recatebim, 'f'));

    $nPorcentagemNoBimestre  = 0;
    $nPorcentagemAteBimestre = 0;
    if ($oStdDadosLinha->prevatu > 0 && $oStdDadosLinha->recnobim > 0) {
      $nPorcentagemNoBimestre = (($oStdDadosLinha->recnobim / $oStdDadosLinha->prevatu)*100);
    }

    if ($oStdDadosLinha->prevatu > 0 && $oStdDadosLinha->recatebim > 0) {
      $nPorcentagemAteBimestre = (($oStdDadosLinha->recatebim / $oStdDadosLinha->prevatu)*100);
    }

    $oStdRetorno->porcento_no_bimestre  = trim(db_formatar($nPorcentagemNoBimestre , 'f'));
    $oStdRetorno->porcento_ate_bimestre = trim(db_formatar($nPorcentagemAteBimestre, 'f'));
    $oStdRetorno->saldo                 = trim(db_formatar($oStdDadosLinha->prevatu-$oStdDadosLinha->recatebim, 'f'));

    if ($oStdRetorno->ordem == 72) {

      $oStdRetorno->previsao_inicial      = '-';
      $oStdRetorno->previsao_atualizada   = '-';
      $oStdRetorno->receita_no_bimestre   = '-';
      $oStdRetorno->porcento_no_bimestre  = '-';
      $oStdRetorno->porcento_ate_bimestre = '-';
      $oStdRetorno->saldo                 = '-';
    }

    if (in_array($oStdRetorno->ordem, array(74, 75, 76))) {

      $oStdRetorno->previsao_inicial      = '-';
      $oStdRetorno->receita_no_bimestre   = '-';
      $oStdRetorno->porcento_no_bimestre  = '-';
      $oStdRetorno->porcento_ate_bimestre = '-';
      $oStdRetorno->saldo                 = '-';
    }

    if (!$lReceitaIntraOrcamentaria) {
      $this->aReceitas[$oStdRetorno->ordem] = $oStdRetorno;
    } else {
      $this->aReceitasIntra[$oStdRetorno->ordem] = $oStdRetorno;
    }
  }


  /**
   * @param \stdClass $oStdDadosLinha
   * @param bool      $lReceitaIntraOrcamentaria
   * @return void
   */
  private function adicionarDespesa(stdClass $oStdDadosLinha, $lReceitaIntraOrcamentaria = false) {

    $oStdRetorno                                 = new stdClass();
    $oStdRetorno->totalizar                      = $oStdDadosLinha->totalizar;
    $oStdRetorno->nivel                          = $oStdDadosLinha->nivel;
    $oStdRetorno->ordem                          = $oStdDadosLinha->ordem;
    $oStdRetorno->descricao                      = $oStdDadosLinha->descricao;
    $oStdRetorno->dotacao_inicial                = trim(db_formatar($oStdDadosLinha->dot_ini, 'f'));
    $oStdRetorno->dotacao_atualizada             = trim(db_formatar($oStdDadosLinha->dot_atual, 'f'));
    $oStdRetorno->empenhadas_no_bimestre         = trim(db_formatar($oStdDadosLinha->empenhado, 'f'));
    $oStdRetorno->empenhadas_ate_bimestre        = trim(db_formatar($oStdDadosLinha->emp_atebim, 'f'));
    $oStdRetorno->despesas_liquidas_no_bimestre  = trim(db_formatar($oStdDadosLinha->liquidado, 'f'));
    $oStdRetorno->despesas_liquidas_ate_bimestre = trim(db_formatar($oStdDadosLinha->liq_atebim, 'f'));
    $oStdRetorno->pagas_ate_bimestre             = trim(db_formatar($oStdDadosLinha->pago, 'f'));
    $oStdRetorno->saldo_empenhado                = trim(db_formatar($oStdDadosLinha->dot_atual-$oStdDadosLinha->emp_atebim, 'f'));
    $oStdRetorno->saldo_liquidado                = trim(db_formatar($oStdDadosLinha->dot_atual-$oStdDadosLinha->liq_atebim, 'f'));
    $oStdRetorno->rp_apagar                      = 0;

    if ($oStdRetorno->ordem == 98) {

      $oStdRetorno->dotacao_inicial               = "-";
      $oStdRetorno->dotacao_atualizada            = "-";
      $oStdRetorno->empenhadas_no_bimestre        = "-";
      $oStdRetorno->despesas_liquidas_no_bimestre = "-";
      $oStdRetorno->pagas_ate_bimestre            = "-";
      $oStdRetorno->empenhadas_ate_bimestre       = "-";
      $oStdRetorno->rp_apagar                     = "-";
      $oStdRetorno->saldo_empenhado               = "-";
      $oStdRetorno->saldo_liquidado               = "-";
    }

    /* Sexto Bimestre busca os dados de RP */
    if ($this->iCodigoPeriodo == 11) {
      $oStdRetorno->rp_apagar = trim(db_formatar(abs($oStdDadosLinha->rp_apagar), 'f'));
    }

    if (!$lReceitaIntraOrcamentaria) {
      $this->aDespesas[$oStdRetorno->ordem] = $oStdRetorno;
    } else {
      $this->aDespesasIntra[$oStdRetorno->ordem] = $oStdRetorno;
    }
  }

  /**
   * Imprime o cabeçalho da despesa
   * @param boolean $lDespesaIntra
   * @return void
   */
  private function imprimirCabecalhoDespesa($lDespesaIntra = false) {

    $iLarguraColunaDespesa        = 45;
    $iLarguraColunaDotacaoInicial = 30;
    $iLarguraColunaDotacaoAtual   = 30;
    if ($this->iCodigoPeriodo != 11) {

      $iLarguraColunaDespesa        += 10;
      $iLarguraColunaDotacaoInicial += 10;
      $iLarguraColunaDotacaoAtual   += 10;
    }

    $sDescricao = $lDespesaIntra ? "DESPESAS INTRA-ORÇAMENTÁRIAS" : "DESPESAS";

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($iLarguraColunaDespesa, 15, $sDescricao, "TB", 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($iLarguraColunaDotacaoInicial, 5, "DOTAÇÃO\nINICIAL\n(d)", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell($iLarguraColunaDotacaoAtual, 5, "DOTAÇÃO\nATUALIZADA\n(e)", 1, PDFDocument::ALIGN_CENTER);

    $nPosicaoX = $this->oPdf->GetX();
    $nPosicaoY = $this->oPdf->GetY();

    $this->oPdf->Cell(40, 4, 'DESPESAS EMPENHADAS', 1, 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetX($nPosicaoX);
    $this->oPdf->MultiCell(20, 11, "NO BIMESTRE", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(20, 3.7, "ATÉ O\n BIMESTRE\n(f)", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetXY($nPosicaoX + 40, $nPosicaoY);
    $this->oPdf->MultiCell(20, 7.5, "SALDO\n(g) = (e-f)", 1, PDFDocument::ALIGN_CENTER);

    $nPosicaoX = $this->oPdf->GetX();
    $nPosicaoY = $this->oPdf->GetY();

    $this->oPdf->Cell(40, 4, 'DESPESAS LIQUIDADAS', 1, 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetX($nPosicaoX);
    $this->oPdf->MultiCell(20, 11, "NO BIMESTRE", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(20, 3.7, "ATÉ O\n BIMESTRE\n(h)", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetXY($nPosicaoX + 40, $nPosicaoY);
    $this->oPdf->MultiCell(20, 7.5, "SALDO\n(i) = (e-h)", 1, PDFDocument::ALIGN_CENTER);

    $this->oPdf->setAutoNewLineMulticell(false);


    if( $this->iCodigoPeriodo != 11 ){
      $this->oPdf->setAutoNewLineMulticell(true);
    }
    $this->oPdf->MultiCell(20, 3, "DESPESAS\nPAGAS ATÉ\nO\nBIMESTRE\n(j)", "TB", PDFDocument::ALIGN_CENTER);

    if( $this->iCodigoPeriodo == 11 ){
      $this->oPdf->setAutoNewLineMulticell(true);
      $this->oPdf->MultiCell(30, 5, "INSCRITAS EM RESTOS\nA PAGAR\nNÃO PROCESSADOS", "TBL", PDFDocument::ALIGN_CENTER);
    }
    $this->oPdf->setBold(false);
  }


  /**
   * Imprime as linhas da despesa
   * @param bool $lReceitaIntra
   * @return void
   */
  private function imprimirDespesa($lReceitaIntra = false) {

    $iLarguraColunaDespesa        = 45;
    $iLarguraColunaDotacaoInicial = 30;
    $iLarguraColunaDotacaoAtual   = 30;
    if ($this->iCodigoPeriodo != 11) {

      $iLarguraColunaDespesa        += 10;
      $iLarguraColunaDotacaoInicial += 10;
      $iLarguraColunaDotacaoAtual   += 10;
    }

    $this->iCodigoPeriodo == 11 ? $lSextoBimestre = true : $lSextoBimestre = false ;
    $aDespesas = $lReceitaIntra ? $this->aDespesasIntra : $this->aDespesas;
    foreach ($aDespesas as $iOrdem => $oStdDespesa) {

      if ($this->oPdf->getAvailHeight() <= 10) {

        $this->adicionarPagina();
        $this->imprimirCabecalhoDespesa($lReceitaIntra);
      }

      if ($oStdDespesa->totalizar) {
        $this->oPdf->setBold(true);
      }

      $sBorda = $this->getBordaLinha($oStdDespesa->ordem);
      $oStdDespesa->descricao = relatorioContabil::getIdentacao($oStdDespesa->nivel).$oStdDespesa->descricao;
      $nHeightMultiCell       = $this->oPdf->getMultiCellHeight($iLarguraColunaDespesa, 4, $oStdDespesa->descricao);
      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->MultiCell($iLarguraColunaDespesa,   4, $oStdDespesa->descricao, "R{$sBorda}", PDFDocument::ALIGN_LEFT);
      $this->oPdf->Cell($iLarguraColunaDotacaoInicial, $nHeightMultiCell, $oStdDespesa->dotacao_inicial,    "R{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->dotacao_inicial));
      $this->oPdf->Cell($iLarguraColunaDotacaoAtual,   $nHeightMultiCell, $oStdDespesa->dotacao_atualizada, "R{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->dotacao_atualizada));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->empenhadas_no_bimestre,         "R{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->empenhadas_no_bimestre));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->empenhadas_ate_bimestre,        "R{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->empenhadas_ate_bimestre));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->saldo_empenhado,                "R{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->saldo_empenhado));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->despesas_liquidas_no_bimestre,  "L{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->despesas_liquidas_no_bimestre));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->despesas_liquidas_ate_bimestre, "L{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->despesas_liquidas_ate_bimestre));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->saldo_liquidado,                "L{$sBorda}", 0, $this->getAlinhamento($oStdDespesa->saldo_liquidado));
      $this->oPdf->Cell(20, $nHeightMultiCell, $oStdDespesa->pagas_ate_bimestre,             "L{$sBorda}", !$lSextoBimestre, $this->getAlinhamento($oStdDespesa->pagas_ate_bimestre));
      if ($lSextoBimestre) {
        $this->oPdf->Cell(30, $nHeightMultiCell, $oStdDespesa->rp_apagar, "L{$sBorda}", 1, $this->getAlinhamento($oStdDespesa->rp_apagar));
      }
      $this->oPdf->setBold(false);
      $this->oPdf->setAutoNewLineMulticell(true);
    }
  }

  /**
   * Imprime o cabeçalho das receitas (I)
   * @param boolean $lReceitaIntra
   * @return void
   */
  private function imprimirCabecalhoReceita($lReceitaIntra = false) {

    $sDescricao = $lReceitaIntra ? "RECEITAS INTRA-ORÇAMENTÁRIAS" : "RECEITAS";

    $this->oPdf->setBold(true);
    $this->oPdf->SetFontSize(6);
    $this->oPdf->Cell(100, 10, $sDescricao, "TB", 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->Cell(30, 10, 'PREVISÃO INICIAL', 1, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(35, 5, "PREVISÃO ATUALIZADA\n(a)", 1, PDFDocument::ALIGN_CENTER);
    $nPosicaoX = $this->oPdf->GetX();
    $nPosicaoY = $this->oPdf->GetY();
    $this->oPdf->Cell(90, 4, 'RECEITAS REALIZADAS', "TBR", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetX($nPosicaoX);
    $this->oPdf->MultiCell(30, 3, "No Bimestre\n(b)",    1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(15, 3, "%\n(b/a)",            1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(30, 3, "Até o Bimestre\n(c)", 1, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(15, 3, "%\n(c/a)",           "TB", PDFDocument::ALIGN_CENTER);
    $this->oPdf->SetXY($this->oPdf->GetX(), $nPosicaoY);
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell(20, 5, "SALDO\n(a-c)",        "LTB", PDFDocument::ALIGN_CENTER);
    $this->oPdf->setBold(false);
  }

  /**
   * Imprime as linhas de receita
   * @param bool $lReceitaIntra
   * @return void
   */
  private function imprimirReceita($lReceitaIntra = false) {

    $aReceitas = $lReceitaIntra ? $this->aReceitasIntra : $this->aReceitas;
    foreach ($aReceitas as $oStdLinha) {

      if ($this->oPdf->getAvailHeight() <= 10) {

        $this->adicionarPagina();
        $this->imprimirCabecalhoReceita($lReceitaIntra);
      }

      if ($oStdLinha->totalizar) {
        $this->oPdf->setBold(true);
      }

      $sBorda     = $this->getBordaLinha($oStdLinha->ordem);
      $sDescricao = relatorioContabil::getIdentacao($oStdLinha->nivel).$oStdLinha->descricao;
      $this->oPdf->setAutoNewLineMulticell(false);
      $this->oPdf->MultiCell(100,4, $sDescricao, "R{$sBorda}", PDFDocument::ALIGN_LEFT);
      $iHeight = $this->oPdf->getMultiCellHeight(100, 4, $sDescricao);
      $this->oPdf->Cell(30, $iHeight, $oStdLinha->previsao_inicial   ,   "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->previsao_inicial));
      $this->oPdf->Cell(35, $iHeight, $oStdLinha->previsao_atualizada,   "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->previsao_atualizada));
      $this->oPdf->Cell(30, $iHeight, $oStdLinha->receita_no_bimestre,   "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->receita_no_bimestre));
      $this->oPdf->Cell(15, $iHeight, $oStdLinha->porcento_no_bimestre,  "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->porcento_no_bimestre));
      $this->oPdf->Cell(30, $iHeight, $oStdLinha->receita_ate_bimestre,  "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->receita_ate_bimestre));
      $this->oPdf->Cell(15, $iHeight, $oStdLinha->porcento_ate_bimestre, "LR{$sBorda}", 0, $this->getAlinhamento($oStdLinha->porcento_ate_bimestre));
      $this->oPdf->Cell(20, $iHeight, $oStdLinha->saldo,                 "L{$sBorda}",  1, $this->getAlinhamento($oStdLinha->saldo));
      $this->oPdf->setBold(false);
      $this->oPdf->setAutoNewLineMulticell(true);
    }
  }

  /**
   * Imprime as assinaturas padrões
   * @return void
   */
  private function imprimirAssinaturas() {

    $this->oPdf->ln(10);
    $oAssinatura = new cl_assinatura();
    assinaturas($this->oPdf, $oAssinatura,'LRF');
  }


  /**
   * Retorna onde deve ser alinhado o campo
   * @param $sValor
   * @return string
   */
  private function getAlinhamento($sValor) {
    return $sValor == '-' ? PDFDocument::ALIGN_CENTER : PDFDocument::ALIGN_RIGHT;
  }

  /**
   * Retorna as bordas top e botton para a linha, caso seja preciso
   * @param integer $iLinha
   * @return string
   */
  private function getBordaLinha($iLinha) {
    return in_array($iLinha,  array(63, 71, 72, 73, 89, 97, 98, 99)) ?  "TB" : "";
  }


  public function getDadosSimplificado() {

    $aDados = $this->getDados();

    $oDados                             = new stdClass();
    $oDados->nPrevisaoInicial           = $aDados[71]->previni;
    $oDados->nPrevisaoAtualizada        = $aDados[71]->prevatu;
    $oDados->nReceitasRealizadas        = $aDados[71]->recatebim;
    $oDados->nDeficitOrcamentario       = $aDados[72]->recatebim;

    $this->processar();
    $this->processarExerciciosAnteriores();

    $oDados->nSaldoExerciciosAnteriores = str_replace(",",".", str_replace(".", "", $this->aReceitas[74]->receita_ate_bimestre));
    $oDados->nDotacaoInicial            = $aDados[97]->dot_ini;
    $oDados->nDotacaoAtualizada         = $aDados[97]->dot_atual;
    $oDados->nCreditoAdicional          = $oDados->nDotacaoAtualizada - $oDados->nDotacaoInicial;
    $oDados->nEmpenhadas                = $aDados[97]->emp_atebim;
    $oDados->nLiquidadas                = $aDados[97]->liq_atebim;
    $oDados->nPagas                     = $aDados[97]->pago;
    $oDados->nSuperavitOrcamentario     = $aDados[98]->liq_atebim;
    return $oDados;
  }
}
