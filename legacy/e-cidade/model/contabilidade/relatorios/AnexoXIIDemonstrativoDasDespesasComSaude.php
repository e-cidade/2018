<?php

require_once "fpdf151/PDFDocument.php";
require_once "fpdf151/assinatura.php";

class AnexoXIIDemonstrativoDasDespesasComSaude extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 100005;

  const LINHA_INICIO_RECEITAS                     = 1;
  const LINHA_FIM_RECEITAS                        = 19;

  const LINHA_INICIO_RECEITAS_ADICIONAIS          = 20;
  const LINHA_FIM_RECEITAS_ADICIONAIS             = 28;

  const LINHA_INICIO_DESPESAS_POR_GRUPO           = 29;
  const LINHA_FIM_DESPESAS_POR_GRUPO              = 37;

  const LINHA_INICIO_DESPESAS_NAO_COMPUTADAS      = 38;
  const LINHA_FIM_DESPESAS_NAO_COMPUTADAS         = 49;

  const LINHA_PERCENTUAL_DE_APLICACAO_EM_ACOES    = 50;
  const LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO  = 51;

  const LINHA_INICIO_EXECUCAO_RESTOS              = 52;
  const LINHA_FIM_EXECUCAO_RESTOS                 = 54;

  const LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR      = 55;
  const LINHA_FIM_CONTROLE_RESTOS_A_PAGAR         = 57;

  const LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO = 58;
  const LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO    = 60;

  const LINHA_INICIO_DESPESAS_POR_SUBFUNCAO       = 61;
  const LINHA_FIM_DESPESAS_POR_SUBFUNCAO          = 68;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  private $aLinhasRestosPagar = array();

  /**
   * Realiza a emição do relatório.
   */
  public function emitir() {

    $this->getDados();

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

    $oInstituicao = current($this->getInstituicoes(true));

    $this->oPdf->addHeaderDescription( "MUNICÍPIO DE {$oInstituicao->getMunicipio()}" );
    $this->oPdf->addHeaderDescription( "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA" );
    $this->oPdf->addHeaderDescription( "DEMONSTRATIVO DAS RECEITAS E DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE" );
    $this->oPdf->addHeaderDescription( "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL" );
    $this->oPdf->addHeaderDescription($this->getTituloPeriodo());

    $this->oPdf->open();
    $this->adicionarPagina();
    $this->oPdf->SetFillColor(232);
    $this->oPdf->SetAutoPageBreak(false, 8);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->setFontSize(6);

    $this->escreverLinhas();

    $this->oPdf->setAutoNewLineMulticell(true);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());

    $this->escreverObservacoes();

    $this->oPdf->ln(10);

    $this->escreverAssinaturas();

    $this->oPdf->showPDF("anexo_viii_manutencao_desenvolvimento_ensino".time());
  }

  public function executarRestosPagar() {

    $oDaoRestosAPagar = new cl_empresto();

    $this->aLinhasRestosPagar = array();

    //Busca restos a pagar do ano atual até (ano atual - 4).
    for ($iAnoAtual = ($this->iAnoUsu - 1); $iAnoAtual >= ($this->iAnoUsu - 4); $iAnoAtual--) {

      $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restosPagarPorPeriodo( $this->iAnoUsu,
                                                                              "{$this->getDataInicial()->getAno()}-01-01",
                                                                              $this->getDataFinal()->getDate(),
                                                                              $this->getInstituicoes(),
                                                                              "*",
                                                                              "e60_anousu = " . ($iAnoAtual) );
      $rsRestosPagar = db_query($sSqlRestosaPagar);

      $oLinha = clone $this->aLinhasConsistencia[self::LINHA_INICIO_EXECUCAO_RESTOS];

      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

      RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar,
        $oLinha,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_RESTO
      );

      $oLinha->descricao .= " ".$iAnoAtual;

      $this->aLinhasRestosPagar[] = $oLinha;
    }

    $this->aLinhasConsistencia[self::LINHA_INICIO_EXECUCAO_RESTOS] = $this->aLinhasRestosPagar[0];

    //Busca restos a pagar anteriores a (ano atual -4)
    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restosPagarPorPeriodo( $this->iAnoUsu,
      "{$this->getDataInicial()->getAno()}-01-01",
      $this->getDataFinal()->getDate(),
      $this->getInstituicoes(),
      "*",
      "e60_anousu < " . ($this->iAnoUsu - 4) );

    $rsRestosPagar = db_query($sSqlRestosaPagar);

    $oLinha = $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS - 1];

    $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

    RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar,
      $oLinha,
      $aColunasProcessar,
      RelatoriosLegaisBase::TIPO_CALCULO_RESTO
    );

    $oLinha->descricao .= " anterior a ".($this->iAnoUsu - 4);

    $this->aLinhasRestosPagar[] = $oLinha;

    //Totaliza resultados na linha totalizadora da EXECUCAO DE RESTOS A PAGAR.
    foreach ($this->aLinhasRestosPagar as $oLinhaRestoPagar) {

      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->inscritos             += $oLinhaRestoPagar->inscritos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->cancelados_prescritos += $oLinhaRestoPagar->cancelados_prescritos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->pagos                 += $oLinhaRestoPagar->pagos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->a_pagar               += $oLinhaRestoPagar->a_pagar;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->parcela_limite        += $oLinhaRestoPagar->parcela_limite;
    }
    unset($this->aLinhasRestosPagar[count($this->aLinhasRestosPagar) - 1]);
    unset($this->aLinhasRestosPagar[0]);
  }

  private function escreverLinhas() {

    $lImprimeCabecalho = true;

    $this->oPdf->setBold(true);
    $this->oPdf->cell(($this->oPdf->getAvailWidth() / 2), 4, "RREO - ANEXO 12 (LC 141/2012, art. 35)");
    $this->oPdf->cell(($this->oPdf->getAvailWidth()), 4, "R$ 1,00", 0, 1, 'R');
    $this->oPdf->setBold(false);

    foreach($this->aLinhasConsistencia as $oLinha) {

      if ($this->oPdf->getAvailHeight() < 18) {

        $lImprimeCabecalho = true;

        $this->adicionarPagina();
      }

      if ($oLinha->totalizar) {
        $this->oPdf->setBold(true);
      }

      switch ($oLinha->ordem) {

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_RECEITAS, self::LINHA_FIM_RECEITAS)):

          if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS || $lImprimeCabecalho) {

            $this->escreverCabecalhoReceitas($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaReceita($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_RECEITAS) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_RECEITAS_ADICIONAIS, self::LINHA_FIM_RECEITAS_ADICIONAIS)):

          if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS_ADICIONAIS || $lImprimeCabecalho) {

            $this->escreverCabecalhoReceitas($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaReceita($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_ADICIONAIS) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_DESPESAS_POR_GRUPO, self::LINHA_FIM_DESPESAS_POR_GRUPO)):

          if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_POR_GRUPO || $lImprimeCabecalho) {

            $this->escreverCabecalhoDespesas($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaDespesa($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_POR_GRUPO) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_DESPESAS_NAO_COMPUTADAS, self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS)):

          if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_NAO_COMPUTADAS || $lImprimeCabecalho) {

            $this->escreverCabecalhoDespesas($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaDespesa($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_PERCENTUAL_DE_APLICACAO_EM_ACOES, self::LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO)):

          $this->escreverLinhaOutros($oLinha);

          if ($oLinha->ordem == self::LINHA_DIFERENCA_ENTRE_EXECUTADO_E_MINIMO) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_EXECUCAO_RESTOS, self::LINHA_FIM_EXECUCAO_RESTOS)):

          if ($oLinha->ordem == self::LINHA_INICIO_EXECUCAO_RESTOS|| $lImprimeCabecalho) {

            $this->escreverCabecalhoExecucaoRestosPagar($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaExecucaoRestosPagar($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR, self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR)):

          if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR|| $lImprimeCabecalho) {

            $this->escreverCabecalhoControle($oLinha->ordem);
            $lImprimeCabecalho = false;
          }

          $this->escreverLinhaControle($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO, self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO)):

          if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO|| $lImprimeCabecalho) {

            $this->escreverCabecalhoControle($oLinha->ordem);
            $lImprimeCabecalho = false;
          }

          $this->escreverLinhaControle($oLinha);

          if ($oLinha->ordem == self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO) {
            $this->adicionaQuebraDeLinha();
          }
          break;

        case (Check::between($oLinha->ordem, self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO, self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO)):

          if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO || $lImprimeCabecalho) {

            $this->escreverCabecalhoDespesas($oLinha->ordem);
            $lImprimeCabecalho = false;
          }
          $this->escreverLinhaDespesa($oLinha);
          break;

      }

      $this->oPdf->setBold(false);
    }
  }

  private function escreverCabecalhoReceitas($iLinha) {

    $lBold              = $this->oPdf->getBold();
    $iLarguraDisponivel = $this->oPdf->getAvailWidth();

    $sTitulo         = "RECEITAS PARA APURAÇÃO DA APLICAÇÃO EM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE";
    $sPrimeiraLetra  = 'a';
    $sSegundaLetra   = 'b';

    if (Check::between($iLinha, self::LINHA_INICIO_RECEITAS_ADICIONAIS, self::LINHA_FIM_RECEITAS_ADICIONAIS)) {

      $sTitulo        = "RECEITAS ADICIONAIS PARA FINANCIAMENTO DA SAÚDE";
      $sPrimeiraLetra = 'c';
      $sSegundaLetra  = 'd';
    }

    $this->oPdf->setBold(true);

    $this->oPdf->Cell($iLarguraDisponivel * 0.55, 8, $sTitulo, "TB", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 8, "PREVISÃO INICIAL", "TBL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 8, "PREVISÃO ATUALIZADA (".$sPrimeiraLetra.")", "TBL", 0, 'C');

    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 4, "RECEITAS REALIZADAS", "TBL", 1, 'C');
    $this->oPdf->SetX($iPosicaoX);

    $this->oPdf->Cell($iLarguraDisponivel * 0.1, 4, "Até o Bimestre (".$sSegundaLetra.")", "BL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.05, 4, "% (".$sSegundaLetra."/".$sPrimeiraLetra.") * 100", "TBL", 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  private function escreverLinhaReceita($oLinha) {

    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $nPorcentagem       = $oLinha->prevatu;

    if ($nPorcentagem) {
      $nPorcentagem = ($oLinha->rec_atebim / $oLinha->prevatu) * 100;
    }

    $this->oPdf->Cell($iLarguraDisponivel * 0.55, 4, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao);
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 4, db_formatar($oLinha->previni, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 4, db_formatar($oLinha->prevatu, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.1, 4, db_formatar($oLinha->rec_atebim, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.05, 4, db_formatar($nPorcentagem, 'f'), "L", 1, 'R');
  }

  private function escreverCabecalhoDespesas($iLinha) {

    $lBold              = $this->oPdf->getBold();
    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $iAltura            = 4;
    $nLarguraTitulo     = $this->ultimoPeriodo() ? 0.34 : 0.44;
    $sTitulo            = "DESPESAS COM SAÚDE";
    $aFormulas          = array(" (e)", " (f)", " (f/e) x 100", " (g)", " (g/e) x 100");

    if (Check::between($iLinha, self::LINHA_INICIO_DESPESAS_NAO_COMPUTADAS, self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS)) {

      $sTitulo = "DESPESAS COM SAÚDE NÃO COMPUTADAS PARA FINS DE APURAÇÃO DO PERCENTUAL MÍNIMO";
      $aFormulas = array("", " (h)", " (h/IVf) x 100", " (i)", " (i/IVg) x 100");
    }

    if (Check::between($iLinha, self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO, self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO)) {

      $sTitulo = "DESPESAS COM SAÚDE";
      $aFormulas = array("", " (l)", " (l/total l) x 100", " (m)", " (m/total m) x 100");
    }

    $nAltura                  = $this->oPdf->getMultiCellHeight($iLarguraDisponivel * $nLarguraTitulo, $iAltura, $sTitulo);
    $sTituloDotacaoAtualizada = "DOTAÇÃO ATUALIZADA\n".$aFormulas[0];
    $nAlturaDotacaoAtualizada = $this->oPdf->getMultiCellHeight($iLarguraDisponivel * 0.1, $iAltura, $sTituloDotacaoAtualizada);

    $this->oPdf->setBold(true);

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($iLarguraDisponivel * $nLarguraTitulo, ($iAltura * 2) + ($iAltura - $nAltura), $sTitulo, "TB", 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.1, $iAltura * 2, "DOTAÇÃO INICIAL", "TBL", 0, 'C');


    $this->oPdf->MultiCell($iLarguraDisponivel * 0.1, ($iAltura * 2) + ($iAltura - $nAlturaDotacaoAtualizada), $sTituloDotacaoAtualizada, "TBL", 'C');

    $iPosicaoX = $this->oPdf->GetX();
    $iPosicaoY = $this->oPdf->GetY() + 4;

    $this->oPdf->Cell($iLarguraDisponivel * 0.18, $iAltura, "DESPESAS EMPENHADAS", "TBL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.18, $iAltura, "DESPESAS LIQUIDADAS", "TBL", !$this->ultimoPeriodo(), 'C');

    if ($this->ultimoPeriodo()) {
      $this->oPdf->setAutoNewLineMulticell(true);
      $this->oPdf->MultiCell($iLarguraDisponivel * 0.1, $iAltura, "Inscritas em Restos a Pagar não Processados7", "TBL", 'C');
      $this->oPdf->setAutoNewLineMulticell(false);
    }

    $this->oPdf->SetXY($iPosicaoX, $iPosicaoY);

    $this->oPdf->Cell($iLarguraDisponivel * 0.1, $iAltura, "Até o Bimestre".$aFormulas[1], "BL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.08, $iAltura, "% ".$aFormulas[2], "BL", 0, 'C');

    $this->oPdf->Cell($iLarguraDisponivel * 0.1, $iAltura, "Até o Bimestre".$aFormulas[3], "BL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.08, $iAltura, "%".$aFormulas[4], "BL", 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  private function escreverLinhaDespesa($oLinha) {

    $iLargura       = $this->oPdf->getAvailWidth();
    $iAltura        = 4;
    $nLarguraTitulo = $this->ultimoPeriodo() ? 0.34 : 0.44;
    $sBorda         = "";

    $nEmpPorcentagem = $oLinha->dot_atual;
    $nLiqPorcentagem = $oLinha->dot_atual;

    if ($nEmpPorcentagem) {
      $nEmpPorcentagem = ($oLinha->emp_atebim / $oLinha->dot_atual) * 100;
    }

    if ($nLiqPorcentagem) {
      $nLiqPorcentagem = ($oLinha->liq_atebim / $oLinha->dot_atual) * 100;
    }

    if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO) {
      $sBorda = "B";
    }

    $nAltura = $this->oPdf->getMultiCellHeight($iLargura * $nLarguraTitulo, $iAltura, $oLinha->descricao);

    $this->oPdf->MultiCell($iLargura * $nLarguraTitulo, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao, "" . $sBorda);
    $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->dot_ini, 'f'), "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->dot_atual, 'f'), "L" . $sBorda, 0, 'R');

    $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->emp_atebim, 'f'), "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.08, $nAltura, db_formatar($nEmpPorcentagem, 'f'), "L" . $sBorda, 0, 'R');

    $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->liq_atebim, 'f'), "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.08, $nAltura, db_formatar($nLiqPorcentagem, 'f'), "L" . $sBorda, !$this->ultimoPeriodo(), 'R');

    if ($this->ultimoPeriodo()) {
      $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->rp_nproc, 'f'), "L" . $sBorda, 1, 'R');
    }
  }

  private function escreverCabecalhoExecucaoRestosPagar($iLinha) {

    $lBold              = $this->oPdf->getBold();
    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $iAltura            = 8;
    $sTitulo            = "EXECUÇÃO DE RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS COM DISPONIBILDADE DE CAIXA";

    $this->oPdf->setBold(true);

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($iLarguraDisponivel * 0.4, $iAltura, $sTitulo, "TB", 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.12, $iAltura, "INSCRITOS", "TBL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.12, $iAltura, "CANCELADOS/PRESCRITOS", "TBL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.12, $iAltura, "PAGOS", "TBL", 0, 'C');
    $this->oPdf->Cell($iLarguraDisponivel * 0.12, $iAltura, "A PAGAR", "TBL", 0, 'C');

    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->MultiCell($iLarguraDisponivel * 0.12, ($iAltura / 2), "PARCELA CONSIDERADA NO LIMITE", "TBL", 'C');
    $this->oPdf->setAutoNewLineMulticell(false);

    $this->oPdf->setBold($lBold);
  }

  private function escreverLinhaExecucaoRestosPagar($oLinha) {

    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $iAltura            = 4;
    $nLarguraValor      = 0.12;
    $nLarguraDescricao  = 0.4;

    if ($oLinha->ordem == self::LINHA_INICIO_EXECUCAO_RESTOS || $oLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS || ($oLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS - 1)) {

      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraDescricao, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao);
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->inscritos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->cancelados_prescritos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->pagos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->a_pagar, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->parcela_limite, 'f'), "L", 1, 'R');
    }

    if ($oLinha->ordem == self::LINHA_INICIO_EXECUCAO_RESTOS) {

      foreach ($this->aLinhasRestosPagar as $oLinhaRestoPagar) {

        if (!$oLinhaRestoPagar->inscritos && !$oLinhaRestoPagar->cancelados_prescritos && !$oLinhaRestoPagar->pagos &&
          !$oLinhaRestoPagar->a_pagar && !$oLinhaRestoPagar->parcela_limite) {
          continue;
        }

        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraDescricao, $iAltura, str_repeat(' ', $oLinhaRestoPagar->nivel * 2) . $oLinhaRestoPagar->descricao);
        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinhaRestoPagar->inscritos, 'f'), "L", 0, 'R');
        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinhaRestoPagar->cancelados_prescritos, 'f'), "L", 0, 'R');
        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinhaRestoPagar->pagos, 'f'), "L", 0, 'R');
        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinhaRestoPagar->a_pagar, 'f'), "L", 0, 'R');
        $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinhaRestoPagar->parcela_limite, 'f'), "L", 1, 'R');
      }
    }
  }

  private function escreverCabecalhoControle($iLinha) {

    $lBold      = $this->oPdf->getBold();
    $iLargura   = $this->oPdf->getAvailWidth();
    $iAltura    = 8;
    $sTitulo    = "CONTROLE DOS RESTOS A PAGAR CANCELADOS OU PRESCRITOS PARA FINS DE APLICAÇÃO DA DISPONIBILIDADE DE CAIXA CONFORME ARTIGO 24, § 1º e 2º";
    $sSubTitulo = "RESTOS A PAGAR CANCELADOS OU PRESCRITOS";
    $sLetra     = 'j';

    if (Check::between($iLinha, self::LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO, self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO)) {

      $sTitulo    = "CONTROLE DO VALOR REFERENTE AO PERCENTUAL MÍNIMO NÃO CUMPRIDO EM EXERCÍCIOS ANTERIORES PARA FINS DE APLICAÇÃO DOS RECURSOS VINCULADOS CONFORME ARTIGOS 25 E 26";
      $sSubTitulo = "LIMITE NÃO CUMPRIDO";
      $sLetra     = 'k';
    }

    $nAltura = $this->oPdf->getMultiCellHeight($iLargura * 0.64, $iAltura, $sTitulo);

    $this->oPdf->setBold(true);

    $this->oPdf->MultiCell($iLargura * 0.64, ($iAltura * 2) + ($iAltura - $nAltura), $sTitulo, "TB", 'C');

    $iPosicaoX = $this->oPdf->GetX();

    $this->oPdf->Cell($iLargura * 0.36, $iAltura, $sSubTitulo, "TBL", 1, 'C');

    $this->oPdf->SetX($iPosicaoX);

    $this->oPdf->Cell($iLargura * 0.12, $iAltura, "Saldo Inicial", "TBL", 0, 'C');
    $this->oPdf->MultiCell($iLargura * 0.12, ($iAltura / 2), "Despesas custeadas no exercício de referência (".$sLetra.")", "TBL", 'C');
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, "Saldo Final (Não Aplicado)", 'BL', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  private function escreverLinhaControle($oLinha) {
    $iLargura   = $this->oPdf->getAvailWidth();
    $iAltura    = 4;

    if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR) {

      foreach($oLinha->oLinhaRelatorio->getValoresColunas() as $oColunas) {

        foreach ($oColunas->colunas as $chave => $oColuna) {

          if ($chave == 0) {
            $this->oPdf->Cell($iLargura * 0.64, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao . " " . $oColuna->o117_valor);
          } else {
            $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oColuna->o117_valor, 'f'), "L", ($chave == 3), 'R');

          }
        }

        $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->saldo_inicial                += $oColunas->colunas[1]->o117_valor;
        $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->despesas_custeadas_exercicio += $oColunas->colunas[2]->o117_valor;
        $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->saldo_final                  += $oColunas->colunas[3]->o117_valor;
      }
    }

    $this->oPdf->Cell($iLargura * 0.64, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao);
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->saldo_inicial, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->despesas_custeadas_exercicio, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->saldo_final, 'f'), 'L', 1, 'R');

    if ($oLinha->ordem == (self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR - 1)) {

      $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->saldo_inicial                += $oLinha->saldo_inicial;
      $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->despesas_custeadas_exercicio += $oLinha->despesas_custeadas_exercicio;
      $this->aLinhasConsistencia[self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR]->saldo_final                  += $oLinha->saldo_final;
    }

  }

  private function escreverLinhaOutros($oLinha) {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);

    $nAltura = $this->oPdf->getMultiCellHeight($iLargura * 0.7, 4, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao);

    $this->oPdf->MultiCell($iLargura * 0.7, 4, $oLinha->descricao, "TB", 'L');
    $this->oPdf->cell($iLargura * 0.3, $nAltura, db_formatar($oLinha->valor, 'f'), "LTB", 1, 'R');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Adiciona uma nova página no relatório, inserindo cabeçalho e rodapé de continuação - quando for o caso.
   * @return void
   */
  private function adicionarPagina() {

    $lBold = $this->oPdf->getBold();

    $this->oPdf->setBold(true);

    if ($this->oPdf->getCurrentPage() > 0) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continua na página " . ($this->oPdf->PageNo() + 1) . "/{nb}", 'T', 0, 'R');
    }
    $this->oPdf->addPage();
    if ($this->oPdf->getCurrentPage() > 1) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continuação da página " . ($this->oPdf->PageNo() - 1) . "/{nb}", 'B', 1, 'R');
    }

    $this->oPdf->setBold($lBold);
  }

  /**
   * Verifica se é o último período.
   * @return bool
   */
  private function ultimoPeriodo() {
    return ($this->iCodigoPeriodo == 11);
  }

  /**
   * Adiciona uma quebra de linha, se tiver espaço.
   */
  private function adicionaQuebraDeLinha() {

    if ($this->oPdf->getAvailHeight() > 12) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 8, '', 'T', 1);
    }
  }

  private function escreverObservacoes() {

    $sNotaPadrao  = "";
    $sNotaPadrao .= "¹ Essa linha apresentará valor somente no Relatório Resumido da Execução Orçamentária do último bimestre do exercício.\n";
    $sNotaPadrao .= "² O valor apresentado na intercessão com a coluna \"i\" ou com a coluna \"h\" deverá ser o mesmo apresentado no \"total j\".\n";
    $sNotaPadrao .= "³ O valor apresentado na intercessão com a coluna \"i\" ou com a coluna \"h\" deverá ser o mesmo apresentado no \"total k\".\n";
    $sNotaPadrao .= "4 Limite anual mínimo a ser cumprido no encerramento do exercício. Deverá ser informado o limite estabelecido na Lei Orgânica";
    $sNotaPadrao .= " do Município quando o percentual nela estabelecido for superior ao fixado na LC nº 141/2012\n";
    $sNotaPadrao .= "5 Durante o exercício esse valor servirá para o monitoramento previsto no art. 23 da LC 141/2012\n";
    $sNotaPadrao .= "6 Nos cinco primeiros bimestres do exercício o acompanhamento será feito com base na despesa liquidada.";
    $sNotaPadrao .= " No último bimestre do exercício, o valor deverá corresponder ao total da despesa empenhada.\n";
    $sNotaPadrao .= "7 Essa coluna poderá ser apresentada somente no último bimestre\n";

    $iAltura = $this->oPdf->getMultiCellHeight($this->oPdf->getAvailWidth(), 3, $sNotaPadrao);

    if ($this->oPdf->getAvailHeight() < $iAltura) {
      $this->adicionarPagina();
    }

    $this->oPdf->MultiCell($this->oPdf->getAvailWidth(), 3, $sNotaPadrao, 0,"L");

    $this->oPdf->Ln();
  }

  private function escreverAssinaturas() {

    $nLargura = $this->oPdf->getAvailWidth() / 3;

    $oAssinatura = new cl_assinatura();
    $sPrefeito   = $oAssinatura->assinatura(1000);
    $sSecretario = $oAssinatura->assinatura(1002);
    $sContador   = $oAssinatura->assinatura(1005);

    $iAltura = $this->oPdf->getMultiCellHeight($nLargura, 4, $sPrefeito) +
      $this->oPdf->getMultiCellHeight($nLargura, 4, $sSecretario) +
      $this->oPdf->getMultiCellHeight($nLargura, 4, $sContador);

    if ($this->oPdf->getAvailHeight() < $iAltura) {
      $this->adicionarPagina();
      $this->adicionaQuebraDeLinha();
    }

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell(90, 4, $sPrefeito, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(90, 4, $sSecretario, 0 , PDFDocument::ALIGN_CENTER);
    $this->oPdf->MultiCell(90, 4, $sContador, 0, PDFDocument::ALIGN_CENTER);
    $this->oPdf->setAutoNewLineMulticell(true);
  }

}