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


class AnexoVIIIManutencaoDesenvolvimentoEnsino extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 147;

  const LINHA_INICIO_RECEITAS_ENSINO         = 1;
  const LINHA_FIM_RECEITAS_ENSINO            = 42;

  const LINHA_INICIO_RECEITAS_ADICIONAIS     = 43;
  const LINHA_FIM_RECEITAS_ADICIONAIS        = 56;

  const LINHA_INICIO_RECEITAS_FUNDEB         = 57;
  const LINHA_FIM_RECEITAS_FUNDEB            = 68;

  const LINHA_INICIO_DESPESAS_FUNDEB         = 69;
  const LINHA_FIM_DESPESAS_FUNDEB            = 75;

  const LINHA_INICIO_DEDUCOES_FUNDEB         = 76;
  const LINHA_FIM_DEDUCOES_FUNDEB            = 82;

  const LINHA_INICIO_INDICADORES_FUNDEB      = 83;
  const LINHA_FIM_INDICADORES_FUNDEB         = 86;

  const LINHA_INICIO_CONTROLE_RECURSOS       = 87;
  const LINHA_FIM_CONTROLE_RECURSOS          = 88;

  const LINHA_INICIO_RECEITAS_MDE            = 89;
  const LINHA_FIM_RECEITAS_MDE               = 89;

  const LINHA_INICIO_DESPESAS_MDE            = 90;
  const LINHA_FIM_DESPESAS_MDE               = 104;

  const LINHA_INICIO_DEDUCOES_CONSTITUCIONAL = 105;
  const LINHA_FIM_DEDUCOES_CONSTITUCIONAL    = 114;

  const LINHA_INICIO_OUTRAS_DESPESAS         = 115;
  const LINHA_FIM_OUTRAS_DESPESAS            = 120;

  const LINHA_INICIO_RESTOS_PAGAR            = 121;
  const LINHA_FIM_RESTOS_PAGAR               = 123;

  const LINHA_INICIO_FLUXO_FINANCEIRO_FUNDEB = 124;
  const LINHA_FIM_FLUXO_FINANCEIRO_FUNDEB    = 130;

  /**
   * @var PDFDocument
   */
  private $oPdf;

    /**
   * Instância relatório AnexoVIII do RREO
   * @param int $iAnoUsu          Ano de emissão
   * @param int $iCodigoRelatorio Código do relatório.
   * @param int $iCodigoPeriodo   Período de emissão do relatório.
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oDataInicialAnterior = clone $this->getDataInicial();
    $this->oDataFinalAnterior   = clone $this->getDataFinal();

    $this->oDataInicialAnterior->modificarIntervalo('-1 year');
    $this->oDataFinalAnterior->modificarIntervalo('-1 year');
  }

  public function getDados() {

    parent::getDados();

    //Para bimestres entre 1 e 5, utiliza a coluna liquidado.
    $nValor83   = $this->aLinhasConsistencia[75]->liq_atebim;
    $nValor84   = $this->aLinhasConsistencia[69]->liq_atebim;
    $nValor85   = $this->aLinhasConsistencia[72]->liq_atebim;
    $nValor113a = $this->aLinhasConsistencia[90]->liq_atebim;
    $nValor113b = $this->aLinhasConsistencia[97]->liq_atebim;

    //No último bimestre utiliza a coluna empenhado.
    if ($this->ultimoPeriodo()) {

      $nValor83   = $this->aLinhasConsistencia[75]->emp_atebim;
      $nValor84   = $this->aLinhasConsistencia[69]->emp_atebim;
      $nValor85   = $this->aLinhasConsistencia[72]->emp_atebim;
      $nValor113a = $this->aLinhasConsistencia[90]->emp_atebim;
      $nValor113b = $this->aLinhasConsistencia[97]->emp_atebim;
    }

    //Linha 19.
    $this->aLinhasConsistencia[83]->valor = $nValor83 - $this->aLinhasConsistencia[82]->valor;

    //Linha 19.1
    $this->aLinhasConsistencia[84]->valor = $this->aLinhasConsistencia[64]->rec_atebim;
    if ($this->aLinhasConsistencia[84]->valor) {
      $this->aLinhasConsistencia[84]->valor = ($nValor84 - ($this->aLinhasConsistencia[77]->valor + $this->aLinhasConsistencia[80]->valor)) / ($this->aLinhasConsistencia[64]->rec_atebim) * 100;
    }

    //Linha 19.2
    $this->aLinhasConsistencia[85]->valor = $this->aLinhasConsistencia[64]->rec_atebim;
    if ($this->aLinhasConsistencia[85]->valor) {
      $this->aLinhasConsistencia[85]->valor = ($nValor85 - ($this->aLinhasConsistencia[78]->valor + $this->aLinhasConsistencia[81]->valor)) / ($this->aLinhasConsistencia[64]->rec_atebim) * 100;
    }

    //Linha 19.3
    $this->aLinhasConsistencia[86]->valor = abs(100 - $this->aLinhasConsistencia[84]->valor - $this->aLinhasConsistencia[85]->valor);

    //Linha 36
    $this->aLinhasConsistencia[111]->valor = $this->aLinhasConsistencia[121]->cancelados;

    //Linha 37
    $this->aLinhasConsistencia[112]->valor += $this->aLinhasConsistencia[111]->valor;

    //Linha 38 - 113 ((23 + 24) - (37)) 23 ->90, 24->97, 37->112
    $this->aLinhasConsistencia[113]->valor = (($nValor113a + $nValor113b) - $this->aLinhasConsistencia[112]->valor);

    //Linha 39 - 114 ((38) / (3) x 100) 38->113, 3->42
    $this->aLinhasConsistencia[114]->valor = $this->aLinhasConsistencia[42]->rec_atebim;
    if ($this->aLinhasConsistencia[114]->valor) {
      $this->aLinhasConsistencia[114]->valor = (($this->aLinhasConsistencia[113]->valor) / ($this->aLinhasConsistencia[42]->rec_atebim) * 100);
    }

    return $this->aLinhasConsistencia;
  }

  /**
   * Realiza a emição do relatório.
   */
  public function emitir() {

    $this->getDados();

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

    $oInstituicao = current($this->getInstituicoes(true));

    $this->oPdf->addHeaderDescription( "MUNICÍPIO DE {$oInstituicao->getMunicipio()} - {$oInstituicao->getUf()}" );
    $this->oPdf->addHeaderDescription( "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA" );
    $this->oPdf->addHeaderDescription( "DEMONSTRATIVO DE RECEITAS E DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE" );
    $this->oPdf->addHeaderDescription( "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL" );
    $this->oPdf->addHeaderDescription($this->getTituloPeriodo());

    $this->oPdf->open();
    $this->adicionarPagina();
    $this->oPdf->SetFillColor(232);
    $this->oPdf->SetAutoPageBreak(false, 8);
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->setFontSize(6);

    $this->escreverLinhas();

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());

    $this->oPdf->ln(10);

    $this->escreverAssinaturas();

    $this->oPdf->showPDF("RREO_Anexo_VIII_MDE_".time());
  }

  /**
   * Escreve aos cabeçalhos e linhas do relatório de acordo com o valor do $oLinha->ordem
   */
  private function escreverLinhas() {

    $lImprimeCabecalho = true;

    $this->oPdf->setBold(true);
    $this->oPdf->cell(($this->oPdf->getAvailWidth() / 2), 4, "RREO - Anexo 8 (LDB, art. 72)");
    $this->oPdf->cell(($this->oPdf->getAvailWidth()), 4, "R$ 1,00", 0, 1, 'R');
    $this->oPdf->setBold(false);

    foreach ($this->aLinhasConsistencia as $oLinha) {

      if ($this->oPdf->getAvailHeight() < 18) {

        $lImprimeCabecalho = true;

        $this->adicionarPagina();
      }

      if ($oLinha->totalizar) {
        $this->oPdf->setBold(true);
      }

      if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS_ENSINO) {

        if ($lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

         $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_ENSINO) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS_ADICIONAIS) {

        if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS_ADICIONAIS || $lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

         $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_ADICIONAIS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS_FUNDEB) {

        if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS_FUNDEB || $lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

         $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_FUNDEB) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DESPESAS_FUNDEB) {

        if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_FUNDEB || $lImprimeCabecalho) {

          $this->escreverCabecalhoDespesas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaDespesa($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_FUNDEB) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DEDUCOES_FUNDEB) {

        if ($oLinha->ordem == self::LINHA_INICIO_DEDUCOES_FUNDEB || $lImprimeCabecalho) {

          $this->escreverCabecalhoOutros($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaOutra($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DEDUCOES_FUNDEB) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_INDICADORES_FUNDEB) {

        if ($oLinha->ordem == self::LINHA_INICIO_INDICADORES_FUNDEB || $lImprimeCabecalho) {

          $this->escreverCabecalhoOutros($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaOutra($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_INDICADORES_FUNDEB) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_CONTROLE_RECURSOS) {

        if ($oLinha->ordem == self::LINHA_INICIO_CONTROLE_RECURSOS || $lImprimeCabecalho) {

          $this->escreverCabecalhoOutros($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaOutra($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_CONTROLE_RECURSOS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS_MDE) {

        if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS_MDE || $lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

         $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_MDE) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DESPESAS_MDE) {

        if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_MDE || $lImprimeCabecalho) {

          $this->escreverCabecalhoDespesas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaDespesa($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_MDE) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL) {

        if ($oLinha->ordem == self::LINHA_INICIO_DEDUCOES_CONSTITUCIONAL|| $lImprimeCabecalho) {

          $this->escreverCabecalhoOutros($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaOutra($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_OUTRAS_DESPESAS) {

        if ($oLinha->ordem == self::LINHA_INICIO_OUTRAS_DESPESAS|| $lImprimeCabecalho) {

          $this->escreverCabecalhoDespesas($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaDespesa($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_OUTRAS_DESPESAS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_RESTOS_PAGAR) {

        if ($oLinha->ordem == self::LINHA_INICIO_RESTOS_PAGAR || $lImprimeCabecalho) {

          $this->escreverCabecalhoRestosPagar();
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaRestosPagar($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RESTOS_PAGAR) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_FLUXO_FINANCEIRO_FUNDEB) {

        if ($oLinha->ordem == self::LINHA_INICIO_FLUXO_FINANCEIRO_FUNDEB || $lImprimeCabecalho) {

          $this->escreverCabecalhoOutros($oLinha->ordem);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaOutra($oLinha);
      }

      $this->oPdf->setBold(false);
    }
  }

  /**
   * Escreve o cabeçalho para as receitas.
   * @param $iLinha
   */
  private function escreverCabecalhoReceitas($iLinha) {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $sTitulo = "RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO";

    if (Check::between($iLinha, self::LINHA_INICIO_RECEITAS_ENSINO, self::LINHA_FIM_RECEITAS_ENSINO)) {
      $sTitulo = "RECEITA RESULTANTE DE IMPOSTOS (caput do art. 212 da Constituição)";

      $this->oPdf->setBold(true);
      $this->oPdf->setUnderline(true);
      $this->oPdf->cell($iLargura, 4, "RECEITAS DO ENSINO", 'TB', 1, 'C');
      $this->oPdf->setUnderline(false);

    } else if (Check::between($iLinha, self::LINHA_INICIO_RECEITAS_MDE, self::LINHA_FIM_RECEITAS_MDE)) {

      $this->oPdf->setBold(true);
      $this->oPdf->setUnderline(true);
      $this->oPdf->cell($iLargura, 4, "MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - DESPESAS CUSTEADAS COM A RECEITA RESULTANTE DE IMPOSTOS E RECURSOS DO FUNDEB", 'TB', 1, 'C');
      $this->oPdf->setUnderline(false);

      $sTitulo = "RECEITAS COM AÇÕES TÍPICAS DE MDE";
    } else if (Check::between($iLinha, self::LINHA_INICIO_RECEITAS_FUNDEB, self::LINHA_FIM_RECEITAS_FUNDEB)) {

      if ($iLinha == self::LINHA_INICIO_RECEITAS_FUNDEB) {

        $this->oPdf->setBold(true);
        $this->oPdf->setUnderline(true);
        $this->oPdf->cell($iLargura, 4, "FUNDEB", 'TB', 1, 'C');
        $this->oPdf->setUnderline(false);
      }
      $sTitulo = "RECEITAS DO FUNDEB";
    }

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.53, 8, $sTitulo, 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.13, 8, 'PREVISÃO INICIAL', 1, 0, 'C');
    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($iLargura * 0.13, 4, "PREVISÃO ATUALIZADA\n(a)", 1, 'C');

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.21, 4, 'RECEITAS REALIZADAS', 'TLB', 1, 'C');
    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.13, 4, "Até o Bimestre (b)", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.08, 4, "% (c) = (b/a)x100", 'TLB', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve as linhas de receita.
   * @param $oLinha
   */
  private function escreverLinhaReceita($oLinha) {

    $iLargura     = $this->oPdf->getAvailWidth();
    $nPorcentagem = $oLinha->prevatu != 0 ? abs(($oLinha->rec_atebim/$oLinha->prevatu)*100) : 0.00;
    $sBorda = ($oLinha->ordem == self::LINHA_FIM_RECEITAS_ENSINO || $oLinha->ordem == self::LINHA_FIM_RECEITAS_ADICIONAIS || $oLinha->ordem == self::LINHA_FIM_RECEITAS_FUNDEB) ? "TB" : '';

    $this->oPdf->cell($iLargura * 0.53, 4, (str_repeat(' ', $oLinha->nivel*2)) . $oLinha->descricao, $sBorda . "", 0, 'L');
    $this->oPdf->cell($iLargura * 0.13, 4, db_formatar($oLinha->previni , 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.13, 4, db_formatar($oLinha->prevatu , 'f'), $sBorda . 'L', 0, 'R');

    $this->oPdf->cell($iLargura * 0.13, 4, db_formatar($oLinha->rec_atebim , 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.08, 4, db_formatar($nPorcentagem, 'f'), $sBorda . 'L', 1, 'R');

    if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_FUNDEB) {

      $this->oPdf->setBold(false);

      $this->oPdf->Cell($iLargura, 3, "[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (12) > 0] = ACRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB", 'T', 1);
      $this->oPdf->Cell($iLargura, 3, "[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (12) < 0] = DECRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB", 0, 1);
    }
  }

  /**
   * Escreve cabeçalho para despesas.
   * @param $iLinha
   */
  private function escreverCabecalhoDespesas($iLinha) {

    $lBold          = $this->oPdf->getBold();
    $iLargura       = $this->oPdf->getAvailWidth();
    $iAltura        = 4;
    $nLarguraTitulo = $this->ultimoPeriodo() ? 0.34 : 0.44;

    $sTitulo = "DESPESAS DO FUNDEB";

    if (Check::between($iLinha, self::LINHA_INICIO_DESPESAS_MDE, self::LINHA_FIM_DESPESAS_MDE)) {
      $sTitulo = "DESPESAS COM AÇÕES TÍPICAS DE MDE";
    }

    $this->oPdf->setBold(true);

    if (Check::between($iLinha, self::LINHA_INICIO_OUTRAS_DESPESAS, self::LINHA_FIM_OUTRAS_DESPESAS)) {

      if ($iLinha == self::LINHA_INICIO_OUTRAS_DESPESAS) {
        $this->oPdf->setUnderline(true);
        $this->oPdf->cell($iLargura, $iAltura, "OUTRAS INFORMAÇÕES PARA CONTROLE", 'TB', 1, 'C');
        $this->oPdf->setUnderline(false);
      }

      $sTitulo = "OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO";
    }

    $iAlturaTitulo = $this->oPdf->getMultiCellHeight($iLargura * $nLarguraTitulo, $iAltura, $sTitulo);

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->Multicell($iLargura * $nLarguraTitulo, $iAltura * 2 + ($iAltura - $iAlturaTitulo), $sTitulo, 'TB', 'C');

    $this->oPdf->cell($iLargura * 0.1, $iAltura * 2, 'DOTAÇÃO INICIAL', 1, 0, 'C');
    $this->oPdf->Multicell($iLargura * 0.1, $iAltura, "DOTAÇÃO ATUALIZADA (d)", 1, 'C');

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.18, $iAltura, 'DESPESAS EMPENHADAS', 'TLB', 0, 'C');
    $this->oPdf->cell($iLargura * 0.18, $iAltura, 'DESPESAS LIQUIDADAS', 'TLB', !$this->ultimoPeriodo(), 'C');

    if ($this->ultimoPeriodo()) {

      $iPosicaoY = $this->oPdf->getY() + 4;

      $this->oPdf->setAutoNewLineMulticell(true);
      $this->oPdf->Multicell($iLargura * 0.1, ($iAltura * 2)/ 3, "INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS (i)", 'TLB', 'C');
      $this->oPdf->setAutoNewLineMulticell(false);

      $this->oPdf->setY($iPosicaoY);
    }

    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.1, $iAltura, "Até o Bimestre (e)", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.08, $iAltura, "% (f) = (e/d)x100", 'TB', 0, 'C');

    $this->oPdf->cell($iLargura * 0.1, $iAltura, "Até o Bimestre (g)", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.08, $iAltura, "% (h) = (g/d)x100", 'TB', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve linhas de despesa.
   * @param $oLinha
   */
  private function escreverLinhaDespesa($oLinha) {

    $iLargura          = $this->oPdf->getAvailWidth();
    $nLarguraDescricao = $this->ultimoPeriodo() ? 0.34 : 0.44;
    $nPorcentagemEmp   = $oLinha->dot_atual > 0 ? ($oLinha->emp_atebim/$oLinha->dot_atual)*100 : 0.00;
    $nPorcentagemLiq   = $oLinha->dot_atual > 0 ? ($oLinha->liq_atebim/$oLinha->dot_atual)*100 : 0.00;
    $sBorda            = ($oLinha->ordem == self::LINHA_FIM_DESPESAS_FUNDEB || $oLinha->ordem == self::LINHA_FIM_DESPESAS_MDE ||
      $oLinha->ordem == self::LINHA_FIM_OUTRAS_DESPESAS || $oLinha->ordem == (self::LINHA_FIM_OUTRAS_DESPESAS - 1)) ? "TB" : '';

    $sDescricao = (str_repeat(' ', $oLinha->nivel*2)) . $oLinha->descricao;
    $iAltura    = $this->oPdf->getMultiCellHeight($iLargura * $nLarguraDescricao, 4, $sDescricao);

    $this->oPdf->Multicell($iLargura * $nLarguraDescricao, 4, $sDescricao, $sBorda . '', 'L');

    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->dot_ini, 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->dot_atual , 'f'), $sBorda . 'L', 0, 'R');

    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->emp_atebim , 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.08, $iAltura, db_formatar($nPorcentagemEmp, 'f'), $sBorda . 'L', 0, 'R');

    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->liq_atebim , 'f'), $sBorda . 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.08, $iAltura, db_formatar($nPorcentagemLiq, 'f'), $sBorda . 'L', !$this->ultimoPeriodo(), 'R');

    if ($this->ultimoPeriodo()) {
      $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->rp_nproc, 'f'), $sBorda . 'L', 1, 'R');
    }
  }

  /**
   * Escreve o cabeçalho para as linhas de duas colunas (descricao e valor).
   * @param $iLinha
   */
  private function escreverCabecalhoOutros($iLinha) {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $sTitulo  = "DEDUÇÕES PARA FINS DO LIMITE DO FUNDEB";

    if (Check::between($iLinha, self::LINHA_INICIO_DEDUCOES_CONSTITUCIONAL, self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL)) {
      $sTitulo = "DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL";
    } else if (Check::between($iLinha, self::LINHA_INICIO_INDICADORES_FUNDEB, self::LINHA_FIM_INDICADORES_FUNDEB)) {
      $sTitulo = "INDICADORES DO FUNDEB";
    } else if (Check::between($iLinha, self::LINHA_INICIO_CONTROLE_RECURSOS, self::LINHA_FIM_CONTROLE_RECURSOS)) {
      $sTitulo = "CONTROLE DA UTILIZAÇÃO DE RECURSOS NO EXERCÍCIO SUBSEQUENTE";
    } else if (Check::between($iLinha, self::LINHA_INICIO_FLUXO_FINANCEIRO_FUNDEB, self::LINHA_FIM_FLUXO_FINANCEIRO_FUNDEB)) {
      $sTitulo = "FLUXO FINANCEIRO DOS RECURSOS DO FUNDEB";
    }

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.875, 8, $sTitulo, 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.125, 8, 'VALOR', 'TB', 1, 'C');
    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve as linhas com duas colunas (descricao e valor).
   * @param $oLinha
   */
  private function escreverLinhaOutra($oLinha) {

    $iLargura     = $this->oPdf->getAvailWidth();
    $sBorda       = ($oLinha->ordem == self::LINHA_FIM_DEDUCOES_FUNDEB || $oLinha->ordem == self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL ||
      $oLinha->ordem == (self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL - 1)|| $oLinha->ordem == (self::LINHA_FIM_DEDUCOES_CONSTITUCIONAL- 2)) ? "TB" : '';

    if ($oLinha->ordem == self::LINHA_FIM_FLUXO_FINANCEIRO_FUNDEB) {
      $sBorda .= 'B';
    }

    $this->oPdf->cell($iLargura * 0.875, 4, (str_repeat(' ', $oLinha->nivel * 2)) . $oLinha->descricao, $sBorda . '');
    $this->oPdf->cell($iLargura * 0.125, 4, db_formatar($oLinha->valor , 'f'), $sBorda . 'L', 1, 'R');
  }

  /**
   * Escreve o cabeçalho dos restos a pagar.
   */
  private function escreverCabecalhoRestosPagar() {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->MultiCell($iLargura * 0.76, 4, "RESTOS A PAGAR INCRISTOS COM DISPONIBILIDADE FINANCEIRA\nDE RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO", 'TB', 'C');

    $this->oPdf->Cell($iLargura * 0.12, 8, "SALDO ATÉ O BIMESTRE", "TBL", 0, 'C');
    $this->oPdf->Cell($iLargura * 0.12, 8, "CANCELADO EM ".$this->iAnoUsu." (j)", "TBL", 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve a linha dos restos a pagar.
   * @param $oLinha
   */
  private function escreverLinhaRestosPagar($oLinha) {

    $iLargura     = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($iLargura * 0.76, 4, (str_repeat(' ', $oLinha->nivel * 2)) . $oLinha->descricao);
    $this->oPdf->cell($iLargura * 0.12, 4, db_formatar($oLinha->saldo , 'f'), 'L', 0, 'R');
    $this->oPdf->cell($iLargura * 0.12, 4, db_formatar($oLinha->cancelados , 'f'), 'L', 1, 'R');
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
   * Escreve as assinaturas.
   */
  private function escreverAssinaturas() {

    $oAssinatura = new cl_assinatura();
    assinaturas($this->oPdf, $oAssinatura,'LRF');
    $this->oPdf->setAutoNewLineMulticell(true);
  }


  /**
   * Retorna os dados para o relatorio simplificado
   * @return stdClass
   */
  public function getDadosSimplificado() {

    $aDados = $this->getDados();

    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->nMinimoAtualMDEAteBimestre    = $aDados[113]->valor;
    $oDadosSimplificado->nPercentualAplicadoComMDE     = $aDados[114]->valor;
    $oDadosSimplificado->nMinimoAtualFUNDEBAteBimestre = $aDados[69]->liq_atebim - ($aDados[77]->valor + $aDados[80]->valor);
    $oDadosSimplificado->nPercentualAplicadoComFUNDEB  = $aDados[84]->valor;

    return $oDadosSimplificado;

  }
}