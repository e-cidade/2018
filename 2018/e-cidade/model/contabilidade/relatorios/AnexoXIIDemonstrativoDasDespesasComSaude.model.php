<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

class AnexoXIIDemonstrativoDasDespesasComSaude extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 149;

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

  const LINHA_RESTOS_PAGAR_INSCRITOS_INDEVIDAMENTE = 45;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Array contendo somentes os restos a pagar cadastrados para $this->iAnoUsu dos anos ($this->iAnoUsu - 2) até ($this->iAnoUsu - 4).
   * @var array
   */
  private $aLinhasRestosPagar = array();

  /**
   * Realiza a emição do relatório.
   */
  public function emitir() {

    $this->getDados();

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

    $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();

    $this->oPdf->addHeaderDescription( DemonstrativoFiscal::getEnteFederativo($oInstituicao) );

    if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
      $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
    }

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

    $this->oPdf->ln(10);

    $this->escreverAssinaturas();

    $this->oPdf->showPDF("RREO_Anexo_XII_ImpostosDespesasSaude_".time());
  }

  /**
   * Executa os restos a pagar, buscando os cadastrados no $this->iAnoUsu dos 4 últimos anos e o somatório dos anteriores
   * aos 4 últimos anos.
   */
  public function executarRestosPagar() {

    $oDaoRestosAPagar         = new cl_empresto();
    $this->aLinhasRestosPagar = array();
    $aConfiguracaoManual      = array();

    //Pega os restos a pargar configurados manualmente.
    foreach ($this->aLinhasConsistencia[self::LINHA_INICIO_EXECUCAO_RESTOS]->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu) as $oConfiguracaoManual) {

      $iAnoDeste = $oConfiguracaoManual->colunas[0]->o117_valor;
      $oLinhaAux = new stdClass();

      $oLinhaAux->inscritos              = $oConfiguracaoManual->colunas[1]->o117_valor;
      $oLinhaAux->cancelados_prescritos  = $oConfiguracaoManual->colunas[2]->o117_valor;
      $oLinhaAux->pagos                  = $oConfiguracaoManual->colunas[3]->o117_valor;
      $oLinhaAux->a_pagar                = $oConfiguracaoManual->colunas[4]->o117_valor;
      $oLinhaAux->parcela_limite         = $oConfiguracaoManual->colunas[5]->o117_valor;

      //Agrupa se já tiver este ano.
      if (isset($aConfiguracaoManual[$iAnoDeste])) {

        $aConfiguracaoManual[$iAnoDeste]->inscritos              += $oLinhaAux->inscritos;
        $aConfiguracaoManual[$iAnoDeste]->cancelados_prescritos  += $oLinhaAux->cancelados_prescritos;
        $aConfiguracaoManual[$iAnoDeste]->pagos                  += $oLinhaAux->pagos;
        $aConfiguracaoManual[$iAnoDeste]->a_pagar                += $oLinhaAux->a_pagar;
        $aConfiguracaoManual[$iAnoDeste]->parcela_limite         += $oLinhaAux->parcela_limite;
        continue;
      }

      $aConfiguracaoManual[$iAnoDeste] = $oLinhaAux;
    }

    $iAnoAnterior = ($this->iAnoUsu - 1);
    //Busca restos a pagar do ano atual até (ano atual - 5).
    //Busca restos a pagar do ano atual até (ano atual - 5).
    for ($iAnoAtual = $iAnoAnterior; $iAnoAtual >= ($this->iAnoUsu - 5); $iAnoAtual--) {

      $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restosPagarPorPeriodo( $this->iAnoUsu,
                                                                              "{$this->getDataInicial()->getAno()}-01-01",
                                                                              $this->getDataFinal()->getDate(),
                                                                              $this->getInstituicoes(),
                                                                              "*",
                                                                              "e60_anousu = " . ($iAnoAtual) );
      $rsRestosPagar = db_query($sSqlRestosaPagar);

      $oLinha = clone $this->aLinhasConsistencia[self::LINHA_INICIO_EXECUCAO_RESTOS];

      $oLinha->inscritos             = 0;
      $oLinha->cancelados_prescritos = 0;
      $oLinha->pagos                 = 0;
      $oLinha->a_pagar               = 0;
      $oLinha->parcela_limite        = 0;

      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

      RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar,
        $oLinha,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_RESTO
      );

      $oLinha->descricao .= " " . ($iAnoAtual + 1);

      if (isset($aConfiguracaoManual[$iAnoAtual])) {

        $oLinha->inscritos              += $aConfiguracaoManual[$iAnoAtual]->inscritos;
        $oLinha->cancelados_prescritos  += $aConfiguracaoManual[$iAnoAtual]->cancelados_prescritos;
        $oLinha->pagos                  += $aConfiguracaoManual[$iAnoAtual]->pagos;
        $oLinha->a_pagar                += $aConfiguracaoManual[$iAnoAtual]->a_pagar;
        $oLinha->parcela_limite         += $aConfiguracaoManual[$iAnoAtual]->parcela_limite;

        unset($aConfiguracaoManual[$iAnoAtual]);
      }

      $this->aLinhasRestosPagar[$iAnoAtual] = $oLinha;
    }

    foreach ($aConfiguracaoManual as $iAno => $oLinha) {

      $oNovaLinha = clone $this->aLinhasConsistencia[self::LINHA_INICIO_EXECUCAO_RESTOS];

      $oNovaLinha->descricao             .= " {$iAno}";
      $oNovaLinha->inscritos              = $oLinha->inscritos;
      $oNovaLinha->cancelados_prescritos  = $oLinha->cancelados_prescritos;
      $oNovaLinha->pagos                  = $oLinha->pagos;
      $oNovaLinha->a_pagar                = $oLinha->a_pagar;
      $oNovaLinha->parcela_limite         = $oLinha->parcela_limite;

      $this->aLinhasRestosPagar[$iAno] = $oNovaLinha;
    }

    //Ordena e reverte
    sort($this->aLinhasRestosPagar);
    $this->aLinhasRestosPagar = array_reverse($this->aLinhasRestosPagar);

    //Busca restos a pagar anteriores a (ano atual -5)
    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restosPagarPorPeriodo( $this->iAnoUsu,
      "{$this->getDataInicial()->getAno()}-01-01",
      $this->getDataFinal()->getDate(),
      $this->getInstituicoes(),
      "*",
      "e60_anousu < " . ($this->iAnoUsu - 5) );

    $rsRestosPagar = db_query($sSqlRestosaPagar);

    //Atribui a última linha (não totalizadora) dos restos a pagar, pois também deverá ser exibido obrigatoriamente.
    $oLinha = $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS - 1];

    $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

    RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar,
      $oLinha,
      $aColunasProcessar,
      RelatoriosLegaisBase::TIPO_CALCULO_RESTO
    );

    $oLinha->descricao .= " anterioriores a ".($this->iAnoUsu - 5);

    $this->aLinhasRestosPagar[] = $oLinha;

    //Totaliza resultados na linha totalizadora da EXECUCAO DE RESTOS A PAGAR.
    foreach ($this->aLinhasRestosPagar as $oLinhaRestoPagar) {

      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->inscritos             += $oLinhaRestoPagar->inscritos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->cancelados_prescritos += $oLinhaRestoPagar->cancelados_prescritos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->pagos                 += $oLinhaRestoPagar->pagos;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->a_pagar               += $oLinhaRestoPagar->a_pagar;
      $this->aLinhasConsistencia[self::LINHA_FIM_EXECUCAO_RESTOS]->parcela_limite        += $oLinhaRestoPagar->parcela_limite;
    }
    //Remove primeiro e último item pois serão pegos diretamente da linha e exibidos obrigatorieamente. Os que continuam neste array só aparecerão se tiverem valores.
    unset($this->aLinhasRestosPagar[count($this->aLinhasRestosPagar) - 1]);
  }

  /**
   * Escreve cada linha de cabeçalho ou valor do relatório.
   */
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

  /**
   * Escreve cabeçalho para receita.
   * @param $iLinha
   */
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

  /**
   * Escreve linha para receita.
   * @param $oLinha
   */
  private function escreverLinhaReceita($oLinha) {

    $sBorda = $oLinha->ordem == self::LINHA_FIM_RECEITAS || $oLinha->ordem == self::LINHA_FIM_RECEITAS_ADICIONAIS ? 'T' : '';

    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $nPorcentagem       = $oLinha->prevatu;

    if ($nPorcentagem) {
      $nPorcentagem = ($oLinha->rec_atebim / $oLinha->prevatu) * 100;
    }

    $this->oPdf->Cell($iLarguraDisponivel * 0.55, 4, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao, $sBorda);
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 4, db_formatar($oLinha->previni, 'f'), "{$sBorda}L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.15, 4, db_formatar($oLinha->prevatu, 'f'), "{$sBorda}L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.1, 4, db_formatar($oLinha->rec_atebim, 'f'), "{$sBorda}L", 0, 'R');
    $this->oPdf->Cell($iLarguraDisponivel * 0.05, 4, db_formatar($nPorcentagem, 'f'), "{$sBorda}L", 1, 'R');
  }

  /**
   * Escreve o cabeçalho para despesas.
   * @param $iLinha
   */
  private function escreverCabecalhoDespesas($iLinha) {

    $lBold              = $this->oPdf->getBold();
    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $iAltura            = 4;
    $nLarguraTitulo     = $this->ultimoPeriodo() ? 0.34 : 0.44;
    $sTitulo            = "DESPESAS COM SAÚDE (Por grupo de natureza da despesa)";
    $aFormulas          = array(" (e)", " (f)", " (f/e) x 100", " (g)", " (g/e) x 100");

    if (Check::between($iLinha, self::LINHA_INICIO_DESPESAS_NAO_COMPUTADAS, self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS)) {

      $sTitulo = "DESPESAS COM SAÚDE NÃO COMPUTADAS PARA FINS DE APURAÇÃO DO PERCENTUAL MÍNIMO";
      $aFormulas = array("", " (h)", " (h/IVf) x 100", " (i)", " (i/IVg) x 100");
    }

    if (Check::between($iLinha, self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO, self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO)) {

      $sTitulo = "DESPESAS COM SAÚDE (Por subfunção)";
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

  /**
   * Escreve as linhas de despesa.
   * @param $oLinha
   */
  private function escreverLinhaDespesa($oLinha) {

    $iLargura       = $this->oPdf->getAvailWidth();
    $iAltura        = 4;
    $nLarguraTitulo = $this->ultimoPeriodo() ? 0.34 : 0.44;
    $sBorda         = "";

    $nEmpPorcentagem = $oLinha->dot_atual;
    $nLiqPorcentagem = $oLinha->dot_atual;

    if (Check::between($oLinha->ordem, self::LINHA_INICIO_DESPESAS_NAO_COMPUTADAS, self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS)) {
      $nEmpPorcentagem = $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS_POR_GRUPO]->emp_atebim;
      $nLiqPorcentagem = $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS_POR_GRUPO]->liq_atebim;
    }

    if (Check::between($oLinha->ordem, self::LINHA_INICIO_DESPESAS_POR_SUBFUNCAO, self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO)) {
      $nEmpPorcentagem = $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO]->emp_atebim;
      $nLiqPorcentagem = $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO]->liq_atebim;
    }

    if ($nEmpPorcentagem) {
      $nEmpPorcentagem = ($oLinha->emp_atebim / $nEmpPorcentagem) * 100;
    }

    if ($nLiqPorcentagem) {
      $nLiqPorcentagem = ($oLinha->liq_atebim / $nLiqPorcentagem) * 100;
    }

    if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_POR_GRUPO) {
      $sBorda = "T";
    }

    if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_NAO_COMPUTADAS - 1 || $oLinha->ordem == self::LINHA_FIM_DESPESAS_POR_SUBFUNCAO) {
      $sBorda = "TB";
    }

    $sDescricao      = str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao;
    $nDotIni         = db_formatar($oLinha->dot_ini, 'f');
    $nDotAtu         = db_formatar($oLinha->dot_atual, 'f');
    $nEmpAteBim      = db_formatar($oLinha->emp_atebim, 'f');
    $nLiqAteBim      = db_formatar($oLinha->liq_atebim, 'f');
    $nEmpPorcentagem = db_formatar($nEmpPorcentagem, 'f');
    $nLiqPorcentagem = db_formatar($nLiqPorcentagem, 'f');

    if ($oLinha->ordem == self::LINHA_RESTOS_PAGAR_INSCRITOS_INDEVIDAMENTE && !$this->ultimoPeriodo()) {
      $nDotIni         = '-';
      $nDotAtu         = '-';
      $nEmpAteBim      = '-';
      $nLiqAteBim      = '-';
      $nEmpPorcentagem = '-';
      $nLiqPorcentagem = '-';
    }

    $nAltura = $this->oPdf->getMultiCellHeight($iLargura * $nLarguraTitulo, $iAltura, $oLinha->descricao);

    $this->oPdf->MultiCell($iLargura * $nLarguraTitulo, $iAltura, $sDescricao, "" . $sBorda);
    $this->oPdf->Cell($iLargura * 0.1, $nAltura, $nDotIni, "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.1, $nAltura, $nDotAtu, "L" . $sBorda, 0, 'R');

    $this->oPdf->Cell($iLargura * 0.1, $nAltura, $nEmpAteBim, "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.08, $nAltura, $nEmpPorcentagem, "L" . $sBorda, 0, 'R');

    $this->oPdf->Cell($iLargura * 0.1, $nAltura, $nLiqAteBim, "L" . $sBorda, 0, 'R');
    $this->oPdf->Cell($iLargura * 0.08, $nAltura, $nLiqPorcentagem, "L" . $sBorda, !$this->ultimoPeriodo(), 'R');

    if ($this->ultimoPeriodo()) {
      $this->oPdf->Cell($iLargura * 0.1, $nAltura, db_formatar($oLinha->rp_nproc, 'f'), "L" . $sBorda, 1, 'R');
    }
  }

  /**
   * Escreve cabeçalho da Execução dos Restos a Pagar.
   * @param $iLinha
   */
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

  /**
   * Escreve a linha para Execucao dos Restos a Pagar.
   * @param $oLinha
   */
  private function escreverLinhaExecucaoRestosPagar($oLinha) {

    $iLarguraDisponivel = $this->oPdf->getAvailWidth();
    $iAltura            = 4;
    $nLarguraValor      = 0.12;
    $nLarguraDescricao  = 0.4;

    //Impri as originais quando for a final (anterior a $this->iAnoUsu - 4) e totalizadora.
    if ($oLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS || ($oLinha->ordem == self::LINHA_FIM_EXECUCAO_RESTOS - 1)) {

      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraDescricao, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao);
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->inscritos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->cancelados_prescritos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->pagos, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->a_pagar, 'f'), "L", 0, 'R');
      $this->oPdf->Cell($iLarguraDisponivel * $nLarguraValor, $iAltura, db_formatar($oLinha->parcela_limite, 'f'), "L", 1, 'R');
    }

    //Exibi as linhas manuais ao no lugar da primeira.
    if ($oLinha->ordem == self::LINHA_INICIO_EXECUCAO_RESTOS) {

      foreach ($this->aLinhasRestosPagar as $iChave => $oLinhaRestoPagar) {

        if ($this->oPdf->getAvailHeight() < ($iAltura + 4)) {
          $this->adicionarPagina();
          $this->escreverCabecalhoExecucaoRestosPagar($oLinha->ordem);
        }

        if ((!$oLinhaRestoPagar->inscritos && !$oLinhaRestoPagar->cancelados_prescritos && !$oLinhaRestoPagar->pagos &&
          !$oLinhaRestoPagar->a_pagar && !$oLinhaRestoPagar->parcela_limite) && $iChave != 0) {
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

  /**
   * Escreve cabeçalho para as tabelas Controle.
   * @param $iLinha
   */
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

  /**
   * Escreve a linha para tabelas Controle
   * @param $oLinha
   */
  private function escreverLinhaControle($oLinha) {

    $iLargura            = $this->oPdf->getAvailWidth();
    $iAltura             = 4;
    $iColunaTotalizadora = -1;

    //Imprimi sublinhas no lugar da primeira linha de controle, (se houver).
    if (($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR || $oLinha->ordem == self::LINHA_INICIO_CONTROLE_MINIMO_NAO_CUMPRIDO)
          && count($oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu))) {

      //Array para organizar por ano.
      $aConfiguracaoManual = array();
      foreach($oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu) as $oColunas) {

        $iAnoDeste = $oColunas->colunas[0]->o117_valor;
        $aConfiguracaoManual[$iAnoDeste] = $oColunas;
      }

      //Ordena reversamente pelo ano (chave)
      ksort($aConfiguracaoManual);
      $aConfiguracaoManual = array_reverse($aConfiguracaoManual);

      foreach($aConfiguracaoManual as $oColunas) {

        foreach ($oColunas->colunas as $iChave => $oColuna) {

          if ($iChave == 0) {//Título
            $this->oPdf->Cell($iLargura * 0.64, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao . " " . $oColuna->o117_valor);
          } else {//Valores
            $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oColuna->o117_valor, 'f'), "L", ($iChave == 3), 'R');

          }
        }

        //Indica em qual linha deverá totalizar.
        if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR) {
          $iColunaTotalizadora = self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR;
        } else {
          $iColunaTotalizadora = self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO;
        }

        //Totaliza linhas.
        $this->aLinhasConsistencia[$iColunaTotalizadora]->saldo_inicial                += $oColunas->colunas[1]->o117_valor;
        $this->aLinhasConsistencia[$iColunaTotalizadora]->despesas_custeadas_exercicio += $oColunas->colunas[2]->o117_valor;
        $this->aLinhasConsistencia[$iColunaTotalizadora]->saldo_final                  += $oColunas->colunas[3]->o117_valor;
      }
      return;
    }

    //Chega aqui se for outra linha (não primeira).
    $iAno = ($this->iAnoUsu - 1);

    if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RESTOS_A_PAGAR) {
      $iAno = $this->iAnoUsu;
    }

    if ($oLinha->ordem == (self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO - 1)) {
      $iAno = $this->iAnoUsu - 5;
    }

    if ($oLinha->ordem == (self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR - 1)) {
      $iAno = $this->iAnoUsu - 4;
    }

    if ($oLinha->ordem == self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR || $oLinha->ordem == self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO) {
      $iAno = '';
    }

    //Linhas normais.
    $this->oPdf->Cell($iLargura * 0.64, $iAltura, str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao. ' ' . ($iAno));
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->saldo_inicial, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->despesas_custeadas_exercicio, 'f'), "L", 0, 'R');
    $this->oPdf->Cell($iLargura * 0.12, $iAltura, db_formatar($oLinha->saldo_final, 'f'), 'L', 1, 'R');

    //Diz em qual linha deverá totalizar.
    if ($oLinha->ordem == (self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR - 1)) {
      $iColunaTotalizadora = self::LINHA_FIM_CONTROLE_RESTOS_A_PAGAR;
    }
    if ($oLinha->ordem == (self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO - 1)) {
      $iColunaTotalizadora = self::LINHA_FIM_CONTROLE_MINIMO_NAO_CUMPRIDO;
    }

    //Totaliza, se for o caso.
    if ($iColunaTotalizadora != -1) {
      $this->aLinhasConsistencia[$iColunaTotalizadora]->saldo_inicial                += $oLinha->saldo_inicial;
      $this->aLinhasConsistencia[$iColunaTotalizadora]->despesas_custeadas_exercicio += $oLinha->despesas_custeadas_exercicio;
      $this->aLinhasConsistencia[$iColunaTotalizadora]->saldo_final                  += $oLinha->saldo_final;
    }
  }

  /**
   * Escreve os dados para tabelas com somente duas colunas (descricao e valor).
   * @param $oLinha
   */
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


  /**
   * Escreve assinaturas.
   */
  private function escreverAssinaturas() {

    $nLargura = $this->oPdf->getAvailWidth() / 3;

    $oAssinatura = new cl_assinatura();
    assinaturas($this->oPdf, $oAssinatura,'LRF');
    $this->oPdf->setAutoNewLineMulticell(true);
  }

  /**
   * Retorna os dados do relatorio simplificado
   * @return stdClass
   */
  public function getDadosSimplificado() {

    $aDados = $this->getDados();

    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->nTotalDespesasSaudeComImpostos      = $aDados[49]->liq_atebim;
    $oDadosSimplificado->nPercentualDespesasSaudeComImpostos = $aDados[50]->valor;

    return $oDadosSimplificado;
  }
}
