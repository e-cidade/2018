<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoVIResultadoPrimario
 */
class AnexoVIResultadoPrimario extends RelatoriosLegaisBase {

  /**
   * @var DBDate Data inicial do per�odo anterior.
   */
  private $oDataInicialAnterior;

  /**
   * @var DBDate Data final do per�odo anterior.
   */
  private $oDataFinalAnterior;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  CONST CODIGO_RELATORIO = 146;

  /**
   * Inst�ncia relat�rio AnexoVI do RREO
   * @param int $iAnoUsu          Ano de emiss�o
   * @param int $iCodigoRelatorio C�digo do relat�rio.
   * @param int $iCodigoPeriodo   Per�odo de emiss�o do relat�rio.
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oDataInicialAnterior = clone $this->getDataInicial();
    $this->oDataFinalAnterior   = clone $this->getDataFinal();

    $this->oDataInicialAnterior->modificarIntervalo('-1 year');

    /**
     * Tratamento para ano bisexto, pois o -1 year cai no dia 28 e n�o 29 quando o ano anterior � bisexto.
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
       * para n�o sobrepor os valores.
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
       * para n�o sobrepor os valores.
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
     * Calculamos o valor da Reserva de Conting�ncia do RPPS
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

    $this->oPdf->addHeaderDescription("RELAT�RIO RESUMIDO DA EXECU��O OR�AMENT�RIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DO RESULTADO PRIM�RIO");
    $this->oPdf->addHeaderDescription("OR�AMENTO FISCAL E DA SEGURIDADE SOCIAL");
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
    $this->oPdf->Cell(80, 8, 'RECEITAS PRIM�RIAS', 'TBR', 0, 'C');
    $this->oPdf->Cell(40, 8, 'PREVIS�O ATUALIZADA', 1, 0, 'C');
    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->Cell(70, 4, 'RECEITAS REALIZADAS', 'TBL', 1, 'C');
    $this->oPdf->SetX($iPosicaoX);
    $this->oPdf->Cell(35, 4, 'At� o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(35, 4, 'At� o Bimestre/'.($this->iAnoUsu-1), 'TBL', 1, 'C');
  }

  /**
   * Escreve as linhas do demonstrativo das receitas
   */
  private function escreverReceitas() {

    $this->oPdf->SetFont('Arial', '', 5);
    foreach ($this->aLinhasConsistencia as $iLinha => $oLinha) {

      /**
       * Linha Cota-Parte do IPVA n�o deve ser apresentada, por�m permanece configurada
       * Valores desta linha foram incrementados( via acerto ) na linha Outras Transfer�ncias Correntes, configurando
       * as contas nas configura��es
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
    $this->oPdf->Cell(65, 8, 'DESPESAS PRIM�RIAS', 'TBR', 0, 'C');
    $this->oPdf->Cell(25, 8, 'DOTA��O ATUALIZADA', 'TBR', 0, 'C');
    $iPosicaoX = $this->oPdf->GetX();
    $this->oPdf->Cell(50 - $iSubtrairUltimoBimestreSemestre, 4, 'DESPESAS EMPENHADAS', 1, 0, 'C');
    $this->oPdf->Cell(50 - $iSubtrairUltimoBimestreSemestre, 4, 'DESPESAS LIQUIDADAS', "TBL", $iColunaExtra, 'C');

    if($this->isUltimoPeriodo()) {

      $this->oPdf->Cell($iSubtrairUltimoBimestreSemestre*2, 4, 'Inscritas em RP N�o Proc.', "TBL", 1, 'C');
      $this->oPdf->SetFont('Arial', '', 4);
    }

    $this->oPdf->SetX($iPosicaoX);

    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'At� o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'At� o Bimestre/'.($this->iAnoUsu-1), 'TBL', 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'At� o Bimestre/'.$this->iAnoUsu, 1, 0, 'C');
    $this->oPdf->Cell(25-($iSubtrairUltimoBimestreSemestre/2), 4, 'At� o Bimestre/'.($this->iAnoUsu-1), 'TBL', $iColunaExtra, 'C');

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

      if ($iLinha == 50) { //N�o mostra o valor da dota��o atualizada na �ltima linha.
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
   * Imprime as assinaturas padr�es
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
    $this->oPdf->Cell(140, 4, 'DISCRIMINA��O DA META FISCAL', 'TBR', 0, 'C');
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
