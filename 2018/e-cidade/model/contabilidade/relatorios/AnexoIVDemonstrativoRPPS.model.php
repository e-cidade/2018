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

require_once modification("fpdf151/PDFDocument.php");
require_once modification("fpdf151/assinatura.php");

class AnexoIVDemonstrativoRPPS extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 148;

  const LINHA_INICIO_RECEITAS = 1;
  const LINHA_FIM_RECEITAS    = 26;

  const LINHA_INICIO_DESPESAS = 27;
  const LINHA_FIM_DESPESAS    = 45;

  const LINHA_INICIO_APORTES = 46;
  const LINHA_FIM_APORTES    = 54;

  const LINHA_RESERVA_ORCAMENTARIA = 55;

  const LINHA_INICIO_BENS = 56;
  const LINHA_FIM_BENS    = 59;

  const LINHA_INICIO_RECEITAS_INTRA = 60;
  const LINHA_FIM_RECEITAS_INTRA    = 80;

  const LINHA_INICIO_DESPESAS_INTRA = 81;
  const LINHA_FIM_DESPESAS_INTRA    = 84;

  /**
   * @var DBDate
   */
  private $oDataInicialAnterior;

  /**
   * @var DBDate
   */
  private $oDataFinalAnterior;

  /**
   * @var PDFDocument
   */
  private $oPdf;

    /**
   * Instância relatório AnexoVI do RREO
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
    $aInstituicoes = InstituicaoRepository::getInstituicoesPorTipo( array(5,6) );

    $aCodigos = array_map(function(\Instituicao $oInstiuicao) {
      return $oInstiuicao->getCodigo();
    }, $aInstituicoes);

    $sInstituicoes = implode(',', $aCodigos);

    if (empty($sInstituicoes)) {
      $sInstituicoes = "0";
    }
    $this->setInstituicoes($sInstituicoes);
  }

  public function emitir() {

    $this->getDados();

    $oInstituicao = InstituicaoRepository::getInstituicaoPrefeitura();

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

    $this->oPdf->addHeaderDescription( "MUNICÍPIO DE {$oInstituicao->getMunicipio()} - {$oInstituicao->getUf()}" );
    $this->oPdf->addHeaderDescription( "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA" );
    $this->oPdf->addHeaderDescription( "DEMONSTRATIVO DE RECEITAS E DESPESAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DOS SERVIDORES" );
    $this->oPdf->addHeaderDescription( "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL" );
    $this->oPdf->addHeaderDescription( $this->getTituloPeriodo() );

    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->oPdf->SetFillColor(232);
    $this->oPdf->SetAutoPageBreak(false, 8);
    $this->oPdf->setFontSize(6);

    $this->escreverLinhas();

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());

    $this->oPdf->ln(10);

    $oAssinatura = new cl_assinatura();
    assinaturas($this->oPdf, $oAssinatura,'LRF');
    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->showPDF("RREO_Anexo_IV_DemonstrativoRPPS_" . time());
  }

  private function escreverLinhas() {

    $lImprimeCabecalho = true;

    $this->oPdf->setBold(true);
    $this->oPdf->cell(($this->oPdf->getAvailWidth() / 2), 4, 'RREO - Anexo 4 (LRF, Art. 53, inciso II)');
    $this->oPdf->cell(($this->oPdf->getAvailWidth()), 4, 'R$ 1,00', 0, 1, 'R');
    $this->oPdf->setBold(false);

    foreach ($this->aLinhasConsistencia as $oLinha) {

      if ($this->oPdf->getAvailHeight() < 18) {

        $lImprimeCabecalho = true;

        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continua na página ".($this->oPdf->PageNo() + 1)."/{nb}", 'T', 0, 'R');
        $this->oPdf->addPage();
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Continuação da página ".($this->oPdf->PageNo() - 1)."/{nb}", 'B', 1, 'R');
      }

      if ($oLinha->totalizar) {
        $this->oPdf->setBold(true);
      }

      if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS) {

        if ($lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas();
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DESPESAS) {

        if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS || $lImprimeCabecalho) {

          $this->escreverCabecalhoDespesas();
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaDespesa($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DESPESAS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_APORTES) {

        if ($oLinha->ordem == self::LINHA_INICIO_APORTES || $lImprimeCabecalho) {

          $this->escreverCabecalhoAportesRPPS();
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaAporteRPPS($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_APORTES) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem == self::LINHA_RESERVA_ORCAMENTARIA) {

        $this->escreverCabecalhoReservaOrcamentaria();
        $this->escreverLinhaReservaOrcamentaria($oLinha);

        $this->adicionaQuebraDeLinha();
        $lImprimeCabecalho = false;

      } else if ($oLinha->ordem <= self::LINHA_FIM_BENS) {

        if ($oLinha->ordem == self::LINHA_INICIO_BENS || $lImprimeCabecalho) {

          $this->escreverCabecalhoBensDireitos();
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaBensDireito($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_BENS) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_RECEITAS_INTRA) {

        if ($oLinha->ordem == self::LINHA_INICIO_RECEITAS_INTRA || $lImprimeCabecalho) {

          $this->escreverCabecalhoReceitas(true);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaReceita($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_RECEITAS_INTRA) {
          $this->adicionaQuebraDeLinha();
        }
      } else if ($oLinha->ordem <= self::LINHA_FIM_DESPESAS_INTRA) {

        if ($oLinha->ordem == self::LINHA_INICIO_DESPESAS_INTRA || $lImprimeCabecalho) {

          $this->escreverCabecalhoDespesas(true);
          $lImprimeCabecalho = false;
        }

        $this->escreverLinhaDespesa($oLinha);

        if ($oLinha->ordem == self::LINHA_FIM_DESPESAS_INTRA) {
          $this->adicionaQuebraDeLinha();
        }
      }

      $this->oPdf->setBold(false);
    }
  }

  private function adicionaQuebraDeLinha() {

    if ($this->oPdf->getAvailHeight() > 12) {
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 8, '', 'T', 1);
    }
  }

  /**
   * Escreve o cabecalho das receitas e receitas intra
   * @param  boolean $lIntra
   */
  private function escreverCabecalhoReceitas($lIntra = false) {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 8, (!$lIntra ? 'RECEITAS' : 'RECEITAS INTRA-ORÇAMENTÁRIAS - RPPS'), 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.15, 8, 'PREVISÃO INICIAL', 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.15, 8, 'PREVISÃO ATUALIZADA', 1, 0, 'C');

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.3, 4, 'RECEITAS REALIZADAS', 'TLB', 1, 'C');
    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.15, 4, 'Até o Bimestre / '.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.15, 4, 'Até o Bimestre / '.($this->iAnoUsu - 1), 'TLB', 1, 'C');

    $this->oPdf->setbold($lBold);
  }

  /**
   * Escreve a Linha das Receitas e receitas
   * @param  $oLinha
   */
  private function escreverLinhaReceita($oLinha) {

    $iLargura = $this->oPdf->getAvailWidth();

    $sBorda = (($oLinha->ordem == self::LINHA_FIM_RECEITAS || $oLinha->ordem == self::LINHA_FIM_RECEITAS_INTRA) ? "TB" : '');

    $this->oPdf->cell($iLargura * 0.4, 4, (str_repeat(' ', $oLinha->nivel*2)) . $oLinha->descricao, $sBorda . 'R', 0, 'L');
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oLinha->previni , 'f'), $sBorda . 'R', 0, 'R');
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oLinha->prevatu , 'f'), $sBorda . 'R', 0, 'R');

    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oLinha->rec_atebim , 'f'), $sBorda . 'R', 0, 'R');
    $this->oPdf->cell($iLargura * 0.15, 4, db_formatar($oLinha->recbiexant , 'f'), $sBorda . '', 1, 'R');
  }

  /**
   * Escreve o cabecalho das despesas e despesas intra
   * @param  boolean $lIntra
   */
  private function escreverCabecalhoDespesas($lIntra = false) {

    $lBold = $this->oPdf->getBold();

    $iExercicioAnterior = $this->iAnoUsu-1;
    $iLargura           = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * ($this->ultimoPeriodo() ? 0.2 : 0.4), 12, (!$lIntra ? "DESPESAS" : 'DESPESAS INTRA-ORÇAMENTÁRIAS - RPPS'), 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.1, 12, "DOTAÇÃO INICIAL", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.1, 12, "DOTAÇÃO ATUALIZADA", 1, 0, 'C');

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.2, 8, "DESPESAS EMPENHADAS", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.2, 8, "DESPESAS LIQUIDADAS", 'TLB', !$this->ultimoPeriodo(), 'C');

    if ($this->ultimoPeriodo()) {
      $this->oPdf->Multicell($iLargura * 0.2, 4, "INCRITAS EM RESTOS A\nPAGAR NÃO PROCESSADOS", 'TLB', 'C');
    }

    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->iAnoUsu}", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$iExercicioAnterior}", 1, 0, 'C');

    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$this->iAnoUsu}", 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.1, 4, "Até o Bimestre / {$iExercicioAnterior}", 'TLB', !$this->ultimoPeriodo(), 'C');

    if ($this->ultimoPeriodo()) {

      $this->oPdf->cell($iLargura * 0.1, 4, "Em {$this->iAnoUsu}", 1, 0, 'C');
      $this->oPdf->cell($iLargura * 0.1, 4, "Em {$iExercicioAnterior}", 'TLB', 1, 'C');
    }

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve a linha das despesas
   * @param  $oLinha
   */
  private function escreverLinhaDespesa($oLinha) {

    $iLargura         = $this->oPdf->getAvailWidth();
    $iLarguraDesricao = $iLargura * ($this->ultimoPeriodo() ? 0.2 : 0.4);
    $iAltura          = $this->oPdf->getMultiCellHeight($iLarguraDesricao, 4, str_repeat(' ', $oLinha->nivel*2) . $oLinha->descricao);

    $sBorda       = ((Check::between($oLinha->ordem, (self::LINHA_FIM_DESPESAS-1), self::LINHA_FIM_DESPESAS) || $oLinha->ordem == self::LINHA_FIM_DESPESAS_INTRA) ? 'TB' : '');
    $sAlinhamento = 'R';

    $sValorLiqAteBim      = db_formatar($oLinha->liq_atebim, 'f');
    $sValorEmpAteBim      = db_formatar($oLinha->emp_atebim, 'f');
    $sValorEmpAteBimExAnt = db_formatar($oLinha->emp_atebimexant, 'f');

    if ($this->ultimoPeriodo()) {

      $sValorRpNProc        = db_formatar(abs($oLinha->rp_nproc), 'f');
      $sValorRpNProcExAnt   = db_formatar(abs($oLinha->rp_nprocexant), 'f');
    }

    if ($oLinha->ordem == self::LINHA_FIM_DESPESAS) {

      if ($oLinha->liq_atebim < 0) {
        $sValorLiqAteBim = '('.trim($sValorLiqAteBim).')';
      }

      $sAlinhamento         = 'C';
      $sValorEmpAteBim      = '-';
      $sValorEmpAteBimExAnt = '-';

      if ($this->ultimoPeriodo()) {

        $sValorRpNProc        = '-';
        $sValorRpNProcExAnt   = '-';
      }
    }

    $this->oPdf->setAutoNewLineMulticell(false);

    $this->oPdf->multiCell($iLarguraDesricao, 4, str_repeat(' ', $oLinha->nivel*2) . $oLinha->descricao, $sBorda . 'R', 'L');
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->dot_ini ,'f'), $sBorda . 'R', 0, 'R');
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->dot_atual ,'f'),$sBorda .'R' , 0, 'R');

    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorEmpAteBim, $sBorda . 'R', 0, $sAlinhamento);
    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorEmpAteBimExAnt, $sBorda . 'R', 0, $sAlinhamento);

    $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorLiqAteBim, $sBorda . 'R', 0, 'R');
    $this->oPdf->cell($iLargura * 0.1, $iAltura, db_formatar($oLinha->liq_atebimexant,'f'), $sBorda . '', !$this->ultimoPeriodo(), 'R');

    if ($this->ultimoPeriodo()) {

      $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorRpNProc, $sBorda . 'RL', 0, $sAlinhamento);
      $this->oPdf->cell($iLargura * 0.1, $iAltura, $sValorRpNProcExAnt, $sBorda . '', 1, $sAlinhamento);
    }

    $this->oPdf->setAutoNewLineMulticell(true);
  }

  /**
   * Escreve o Cabeçalho dos Aportes do RPPS
   */
  private function escreverCabecalhoAportesRPPS() {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 4, "APORTES DE RECURSOS PARA O REGIME PRÓPRIO DE PREVIDÊNCIA DO SERVIDOR", 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.6, 4, "APORTES REALIZADOS", 'TB', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve a Linha dos Aportes do RPPS
   * @param  $oLinha
   */
  private function escreverLinhaAporteRPPS($oLinha) {

    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($iLargura * 0.4, 4, str_repeat(' ', $oLinha->nivel*2) . $oLinha->descricao, 'R');
    $this->oPdf->cell($iLargura * 0.6, 4, db_formatar($oLinha->valor, 'f'), '', 1, 'R');
  }

  /**
   * Escreve o cabecalho da Reserva Orçamentária
   */
  private function escreverCabecalhoReservaOrcamentaria() {

    $lBold    = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 4, "RESERVA ORÇAMENTÁRIA DO RPPS", 'TBR', 0, 'C');
    $this->oPdf->cell($iLargura * 0.6, 4, "PREVISÃO ORÇAMENTÁRIA", 'TB', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve as linhas da reserva orçamentária
   * @param  $oLinha
   */
  private function escreverLinhaReservaOrcamentaria($oLinha) {

    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($iLargura * 0.4, 4, str_repeat(' ', $oLinha->nivel*2) . $oLinha->descricao, 'R');
    $this->oPdf->cell($iLargura * 0.6, 4, db_formatar($oLinha->previsao, 'f'), '', 1, 'R');
  }

  /**
   * Escreve o Cabeçalho dos bens
   */
  private function escreverCabecalhoBensDireitos() {

    $lBold = $this->oPdf->getBold();
    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->setBold(true);
    $this->oPdf->cell($iLargura * 0.4, 8, "BENS E DIREITOS DO RPPS", 'TBR', 0, 'C');

    $iPosicaoX = $this->oPdf->getX();

    $this->oPdf->cell($iLargura * 0.6, 4, "PERÍODO DE REFERÊNCIA", 'TB', 1, 'C');

    $this->oPdf->setX($iPosicaoX);

    $this->oPdf->cell($iLargura * 0.3, 4, $this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->cell($iLargura * 0.3, 4, ($this->iAnoUsu - 1), 'TB', 1, 'C');

    $this->oPdf->setBold($lBold);
  }

  /**
   * Escreve as linhas dos bens
   * @param  $oLinha
   */
  private function escreverLinhaBensDireito($oLinha) {

    $iLargura = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($iLargura * 0.4, 4, $oLinha->descricao, 'R');

    $this->oPdf->cell($iLargura * 0.3, 4, db_formatar($oLinha->saldo, 'f'), 'R', 0, 'R');
    $this->oPdf->cell($iLargura * 0.3, 4, db_formatar($oLinha->sd_ex_ant, 'f'), '', 1, 'R');
  }

  /**
   * @return boolean
   */
  private function ultimoPeriodo() {
    return ($this->iCodigoPeriodo == 11);
  }

  /**
   * Executa o balancete da receita do ano atual e do ano anterior.
   */
  protected function executarBalanceteDaReceita() {

    parent::executarBalanceteDaReceita();

    $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita, $this->iAnoUsu -1,
                                          $this->oDataInicialAnterior->getDate(),
                                          $this->oDataFinalAnterior->getDate()
    );

    foreach ($this->aLinhasProcessarReceita as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

      $oLinha->recbiexant = 0;

      /**
       * Remove as colunas utilizadas no processamento anterior (prevatu, recatebim)
       * para não sobrepor os valores.
       */
      unset($aColunasProcessar[0]);
      unset($aColunasProcessar[1]);
      unset($aColunasProcessar[2]);

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
        $oLinha,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
      );
    }
    $this->limparEstruturaBalanceteReceita();

  }

  /**
   * Executa o balancete da despesa do ano atual e do ano anterior.
   */
  protected function executarBalanceteDespesa() {

    parent::executarBalanceteDespesa();


    $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu -1,
                                          $this->oDataInicialAnterior->getDate(),
                                          $this->oDataFinalAnterior->getDate()
    );

    foreach ($this->aLinhasProcessarDespesa as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

      $oLinha->liq_atebimexant = 0;
      $oLinha->emp_atebimexant = 0;
      $oLinha->rp_nprocexant   = 0;

      /**
       * Remove as colunas utilizadas no processamento anterior (dot_ini, emp_atebim, liq_atebim)
       * para não sobrepor os valores.
       */
      unset($aColunasProcessar[0]);
      unset($aColunasProcessar[1]);
      unset($aColunasProcessar[2]);
      unset($aColunasProcessar[4]);

      if ($this->ultimoPeriodo()) {
        unset($aColunasProcessar[6]);
      }

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
        $oLinha,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
      );
    }
    $this->limparEstruturaBalanceteDespesa();
  }

  /**
   * Executa o Balancete de Verificação
   */
  protected function executarBalanceteVerificacao() {

    parent::executarBalanceteVerificacao();

    $sWhereVerificacao      = " c61_instit in({$this->getInstituicoes()})";
    $rsBalanceteVerificacao =  db_planocontassaldo_matriz($this->iAnoUsu - 1,
                                                          $this->oDataInicialAnterior->getDate(),
                                                          ($this->iAnoUsu - 1)."-12-31",
                                                          false,
                                                          $sWhereVerificacao,
                                                          '',
                                                          'true',
                                                          'false'
                                                         );

    foreach ($this->aLinhasProcessarVerificacao as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->processarColunasDaLinha($oLinha);

      $oLinha->sd_ex_ant = 0;

      unset($aColunasProcessar[0]);

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                 $oLinha,
                                                 $aColunasProcessar,
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );
    }
    $this->limparEstruturaBalanceteVerificacao();
  }

  public function getDados() {
    parent::getDados();

    //Linha 25 recebe valores da linha 60.
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS - 1]->previni     = $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->previni;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS - 1]->prevatu     = $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->prevatu;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS - 1]->rec_atebim  = $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->rec_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS - 1]->recbiexant  = $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->recbiexant;

    //Soma valores da linha 25 .
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->previni         += $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->previni;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->prevatu         += $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->prevatu;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->rec_atebim      += $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->rec_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->recbiexant      += $this->aLinhasConsistencia[self::LINHA_INICIO_RECEITAS_INTRA]->recbiexant;


    //Linha 43 recebe valores da linha 76.
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->dot_ini         = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->dot_ini;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->dot_atual       = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->dot_atual;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->emp_atebim      = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->emp_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->emp_atebimexant = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->emp_atebimexant;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->liq_atebim      = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->liq_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->liq_atebimexant = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->liq_atebimexant;

    if ($this->ultimoPeriodo()) {
      $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->rp_nproc      = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->rp_nproc;
      $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 2]->rp_nprocexant = $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->rp_nprocexant;
    }

    //Soma valores da linha 43 .
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->dot_ini         += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->dot_ini;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->dot_atual       += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->dot_atual;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->emp_atebim      += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->emp_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->emp_atebimexant += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->emp_atebimexant;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->liq_atebim      += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->liq_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->liq_atebimexant += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->liq_atebimexant;

    if ($this->ultimoPeriodo()) {
      $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->rp_nproc       += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->rp_nproc;
      $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->rp_nprocexant  += $this->aLinhasConsistencia[self::LINHA_INICIO_DESPESAS_INTRA]->rp_nprocexant;
    }

    //Soma Linha 45
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->dot_ini         = $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->previni    - $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->dot_ini;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->dot_atual       = $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->prevatu    - $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->dot_atual;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->liq_atebim      = $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->rec_atebim - $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->liq_atebim;
    $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->liq_atebimexant = $this->aLinhasConsistencia[self::LINHA_FIM_RECEITAS]->recbiexant - $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS - 1]->liq_atebimexant;

    if ($this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->liq_atebim > 0) {
      $this->aLinhasConsistencia[self::LINHA_RESERVA_ORCAMENTARIA]->previsao += $this->aLinhasConsistencia[self::LINHA_FIM_DESPESAS]->liq_atebim;
    }
    return $this->aLinhasConsistencia;
  }

  public function getDadosSimplificado() {

    $aDadosSimplificado = $this->getDados();
    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->nReceitasRealizadas = $aDadosSimplificado[26]->rec_atebim;
    $oDadosSimplificado->nDespesasLiquidadas = $aDadosSimplificado[44]->liq_atebim;
    $oDadosSimplificado->nDespesasEmpenhadas = $aDadosSimplificado[44]->emp_atebim;

    return $oDadosSimplificado;
  }
}