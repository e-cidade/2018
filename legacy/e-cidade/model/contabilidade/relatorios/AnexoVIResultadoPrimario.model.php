<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoVIResultadoPrimario
 */
class AnexoVIResultadoPrimario extends RelatoriosLegaisBase {

  /**
   * @var DBDate Data inicial do período anterior.
   */
  private $oDataInicialAnterior;

  /**
   * @var DBDate Data final do período anterior.
   */
  private $oDataFinalAnterior;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  CONST CODIGO_RELATORIO = 146;

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

    /**
     * Tratamento para ano bisexto, pois o -1 year cai no dia 28 e não 29 quando o ano anterior é bisexto.
     */
    $this->oDataFinalAnterior->modificarIntervalo('+1 day');
    $this->oDataFinalAnterior->modificarIntervalo('-1 year');
    $this->oDataFinalAnterior->modificarIntervalo('-1 day');
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

    $this->limparEstruturaBalanceteDespesa();
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
      unset($aColunasProcessar[3]);

      if ($this->isUltimoPeriodo()) {
        unset($aColunasProcessar[5]);
      }

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
        $oLinha,
        $aColunasProcessar,
        RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
      );
    }


    /**
     * Calculamos o valor da Reserva de Contingência do RPPS
     */
    $this->aLinhasConsistencia[47]->dot_atual = 0;
    $aInstituicoesRPPS = $this->getInstituicoesRPPS();

    if (count($aInstituicoesRPPS)) {

      $sWhereDespesa                            = " o58_instit in(".implode(",", $aInstituicoesRPPS).")";
      $rsBalanceteDespesa                       = db_dotacaosaldo(8, 2, 2, true, $sWhereDespesa, $this->iAnoUsu, $this->oDataInicial->getDate(), $this->oDataFinal->getDate());
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                 $this->aLinhasConsistencia[47],
                                                 $this->processarColunasDaLinha($this->aLinhasConsistencia[47]),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
      );

      $this->limparEstruturaBalanceteDespesa();
    }

  }

  /**
   * Emite o documento
   */
  public function emitir() {


    $this->getDados();
    $this->oPdf = new PDFDocument("P");
    $this->oPdf->Open();
    $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

    $aInstituicoes = explode(",", $this->getInstituicoes());

    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }else {
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }

    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DO RESULTADO PRIMÁRIO");
    $this->oPdf->addHeaderDescription("ORÇAMENTO FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription($this->getTituloPeriodo());
    $this->oPdf->AddPage();
    $this->oPdf->SetFont('Arial', '', 6);
    $this->oPdf->Cell(150, 4, 'RREO - ANEXO 6 (LRF, art 53, inciso III)', 0, 0);
    $this->oPdf->Cell(40, 4, 'Em Reais', 0, 1, "R");
    $this->oPdf->SetFillColor(255);

    $this->escreverCabecalhoReceita($this->oPdf);
    $this->escreverReceitas();

    $this->escreverCabecalhoDespesas();
    $this->escreverDespesas();

    $this->escreverCabecalhoMetaFiscal();
    $this->escreverMetaFiscal();

    $this->oPdf->Ln(10);
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
    $this->oPdf->Ln(10);
    $this->imprimirAssinaturas();

    $this->oPdf->showPDF('RREO_Anexo_VI_ResultadoPrimario_' . time());
  }

  /**
   * Escreve o cabecalho do demonstrativo da receita
   */
  private function escreverCabecalhoReceita() {

    $this->oPdf->SetFont('Arial', '', 6);
    $this->oPdf->Cell(80, 8, 'RECEITAS PRIMÁRIAS', 'TBR', 0, 'C');
    $this->oPdf->Cell(40, 8, 'PREVISÃO ATUALIZADA', 1, 0, 'C');
    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->Cell(70, 4, 'RECEITAS REALIZADAS', 'TBL', 1, 'C');
    $this->oPdf->SetX($iPosicaoX);
    $this->oPdf->Cell(35, 4, 'Até o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(35, 4, 'Até o Bimestre/'.($this->iAnoUsu-1), 'TBL', 1, 'C');
  }

  /**
   * Escreve as linhas do demonstrativo das receitas
   */
  private function escreverReceitas() {

    $this->oPdf->SetFont('Arial', '', 5);
    foreach ($this->aLinhasConsistencia as $iLinha => $oLinha) {

      /**
       * Linha Cota-Parte do IPVA não deve ser apresentada, porém permanece configurada
       * Valores desta linha foram incrementados( via acerto ) na linha Outras Transferências Correntes, configurando
       * as contas nas configurações
       */
      if($iLinha == 17) {
        continue;
      }

      $sEstiloAdicionalLinha = $iLinha == 32 ? 'TB' : '';

      if ($iLinha > 32) {
        return;
      }
      $this->oPdf->Cell(80, 3, str_repeat(' ', $oLinha->nivel*2).$oLinha->descricao, "R{$sEstiloAdicionalLinha}", 0, 'L');
      $this->oPdf->Cell(40, 3, db_formatar($oLinha->prevatu, 'f'), "LR{$sEstiloAdicionalLinha}", 0, 'R');
      $this->oPdf->Cell(35, 3, db_formatar($oLinha->recatebim, 'f'), "LR{$sEstiloAdicionalLinha}", 0, 'R');
      $this->oPdf->Cell(35, 3, db_formatar($oLinha->recbiexant, 'f'), "L{$sEstiloAdicionalLinha}", 1, 'R');
    }
  }

  private function escreverCabecalhoDespesas() {

    $iSubtrairUltimoBimestreSemestre = 0;
    $iColunaExtra                    = 1;

    if ($this->isUltimoPeriodo()) {

      $iSubtrairUltimoBimestreSemestre = 15;
      $iColunaExtra                    = 0;
    }

    $this->oPdf->Ln();
    $this->oPdf->SetFont('Arial', '', 6);
    $this->oPdf->Cell(65, 8, 'DESPESAS PRIMÁRIAS', 'TBR', 0, 'C');
    $this->oPdf->Cell(25, 8, 'DOTAÇÃO ATUALIZADA', 'TBR', 0, 'C');
    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->Cell(50 - $iSubtrairUltimoBimestreSemestre, 4, 'DESPESAS EMPENHADAS', 1, 0, 'C');
    $this->oPdf->Cell(50 - $iSubtrairUltimoBimestreSemestre, 4, 'DESPESAS LIQUIDADAS', "TBL", $iColunaExtra, 'C');

    if($this->isUltimoPeriodo()) {

      $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre*2, 4, 'Inscritas em RP Não Proc.', "TBL", 1, 'C');
      $this->oPdf->SetFont('Arial', '', 4);
    }

    $this->oPdf->SetX($iPosicaoX);

    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'Até o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'Até o Bimestre/'.($this->iAnoUsu-1), 'TBL', 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'Até o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'Até o Bimestre/'.($this->iAnoUsu-1), 'TBL', $iColunaExtra, 'C');

    if ($this->isUltimoPeriodo()) {

      $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre, 4, 'Em ' . $this->iAnoUsu, 1, 0, 'C');
      $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre, 4, 'Em ' . ($this->iAnoUsu - 1), 'TBL', 1, 'C');
      $this->oPdf->SetFont('Arial', '', 6);
    }
  }

  private function escreverDespesas() {

    $iSubtrairUltimoBimestreSemestre = 0;
    $iColunaExtra                    = 1;

    if ($this->isUltimoPeriodo()) {

      $iSubtrairUltimoBimestreSemestre = 15;
      $iColunaExtra                    = 0;
    }

    $this->oPdf->SetFont('Arial', '', 5);
    foreach ($this->aLinhasConsistencia as $iLinha => $oLinha) {

      $sEstiloAdicionalLinha = $iLinha > 47 ? 'TB' : '';

      if (!Check::between($iLinha, 33, 50)) {
        continue;
      }

      $this->oPdf->Cell(65, 3, str_repeat(' ', $oLinha->nivel*2).$oLinha->descricao, "R{$sEstiloAdicionalLinha}", 0, 'L');

      if ($iLinha == 50) { //Não mostra o valor da dotação atualizada na última linha.
        $this->oPdf->Cell(25, 3, '-', "LR{$sEstiloAdicionalLinha}", 0, 'R');
      } else {
        $this->oPdf->Cell(25, 3, db_formatar($oLinha->dot_atual, 'f'), "LR{$sEstiloAdicionalLinha}", 0, 'R');
      }

      $sValorEmpenhado             = db_formatar($oLinha->emp_atebim, 'f');
      $sValorEmpenhadoAnterior     = db_formatar($oLinha->emp_atebimexant, 'f');
      $sValorLiquidado             = db_formatar($oLinha->liq_atebim, 'f');
      $sValorLiquidadoAnterior     = db_formatar($oLinha->liq_atebimexant, 'f');
      $sValorNaoProcessado         = 0;
      if (isset($oLinha->rp_nproc)) {
        $sValorNaoProcessado = $oLinha->rp_nproc;
      }

      $sValorNaoProcessado         = (db_formatar($sValorNaoProcessado, 'f'));
      $sValorNaoProcessadoAnterior = db_formatar($oLinha->rp_nprocexant, 'f');

      if (Check::between($iLinha, 46, 47)) {

        $sValorEmpenhado             = "-";
        $sValorEmpenhadoAnterior     = "-";
        $sValorLiquidado             = "-";
        $sValorLiquidadoAnterior     = "-";
        $sValorNaoProcessado         = "-";
        $sValorNaoProcessadoAnterior = "-";
      }

      $this->oPdf->Cell(25 - ($iSubtrairUltimoBimestreSemestre / 2), 3, $sValorEmpenhado, "LR{$sEstiloAdicionalLinha}", 0, 'R');
      $this->oPdf->Cell(25 - ($iSubtrairUltimoBimestreSemestre / 2), 3, $sValorEmpenhadoAnterior, "L{$sEstiloAdicionalLinha}", 0, 'R');
      $this->oPdf->Cell(25 - ($iSubtrairUltimoBimestreSemestre / 2), 3, $sValorLiquidado, "LR{$sEstiloAdicionalLinha}", 0, 'R');
      $this->oPdf->Cell(25 - ($iSubtrairUltimoBimestreSemestre / 2), 3, $sValorLiquidadoAnterior, "L{$sEstiloAdicionalLinha}", $iColunaExtra, 'R');

      if ($this->isUltimoPeriodo()) {

        if ($iLinha == 49) {

          $sValorNaoProcessado         = '-';
          $sValorNaoProcessadoAnterior = '-';
        }
        $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre, 3, $sValorNaoProcessado, "LR{$sEstiloAdicionalLinha}", 0, 'R');
        $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre, 3, $sValorNaoProcessadoAnterior, "L{$sEstiloAdicionalLinha}", 1, 'R');
      }

    }
  }

  /**
   * Imprime as assinaturas padrões
   * @return void
   */
  private function imprimirAssinaturas() {

    $this->oPdf->ln(10);
    $oAssinatura = new cl_assinatura();
    assinaturas($this->oPdf, $oAssinatura, 'LRF');
  }

  public function isUltimoPeriodo() {
   return $this->iCodigoPeriodo == 11;
  }


  protected function escreverCabecalhoMetaFiscal() {

    $this->oPdf->Ln();
    $this->oPdf->SetFont('Arial', '', 6);
    $this->oPdf->Cell(140, 4, 'DISCRIMINAÇÃO DA META FISCAL', 'TBR', 0, 'C');
    $this->oPdf->Cell(50, 4, 'VALOR CORRENTE', "TBL", 1, 'C');
  }

  protected function escreverMetaFiscal() {

    $this->oPdf->SetFont('Arial', '', 5);
    $this->oPdf->Cell(140, 3, $this->aLinhasConsistencia[51]->descricao, 'BR', 0, 'L');
    $this->oPdf->Cell(50, 3, db_formatar($this->aLinhasConsistencia[51]->valor, 'f'), "BL", 1, 'R');
  }

  /**
   * Retorna todas as instituicoes do RPPS
   * @return array
   */
  protected function getInstituicoesRPPS() {

    $aListaInstituicoesRPPS = array();
    $aInstituicoes = $this->getInstituicoes(true);
    foreach ($aInstituicoes as $oInstituicao) {
      if (in_array($oInstituicao->getTipo(), array(5,6))) {
        $aListaInstituicoesRPPS[] = $oInstituicao->getCodigo();
      }
    }
    return $aListaInstituicoesRPPS;
  }

  public function getDadosSimplificado() {

    $aDados = $this->getDados();
    $oDados = new stdClass();
    $oDados->nResultadoApuradoAteBimestre = $aDados[49]->liq_atebim;
    $oDados->nMetaFixada                  = $aDados[51]->valor;

    return $oDados;
  }

}
