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

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

class AnexoXRREO extends RelatoriosLegaisBase implements AnexoRREO {

  /**
   * @type int
   */
  const CODIGO_RELATORIO = 106;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var int Altura da linha.
   */
  private $iAltura;

  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   *
   * @return array - Colecao de stdClass
   */
  public function getDados() {

    $aRetorno        = array();
    $oLinhaRelatorio = new linhaRelatorioContabil($this->iCodigoRelatorio, 1);
    $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);

    $aInstituicoes = explode(",", $this->getInstituicoes(false));
    foreach ($aInstituicoes as $iInstituicao) {

      $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunas(null,
                                                                   null,
                                                                   $iInstituicao,
                                                                   $this->iAnoUsu
      );

      foreach ($aValoresColunasLinhas as $oValor) {

        $iAno = $oValor->colunas[0]->o117_valor;
        if (!isset($aRetorno[$iAno])) {

          $aRetorno[$iAno]                          = new stdClass();
          $aRetorno[$iAno]->ano                     = $iAno;
          $aRetorno[$iAno]->receitasprevidenciarias = 0;
          $aRetorno[$iAno]->despesasprevidenciarias = 0;
          $aRetorno[$iAno]->resultadoprevidenciario = 0;
          $aRetorno[$iAno]->saldofinanceiro         = 0;
        }

        $aRetorno[$iAno]->receitasprevidenciarias += $oValor->colunas[1]->o117_valor;
        $aRetorno[$iAno]->despesasprevidenciarias += $oValor->colunas[2]->o117_valor;
        $aRetorno[$iAno]->resultadoprevidenciario += $aRetorno[$iAno]->receitasprevidenciarias
                                                     - $aRetorno[$iAno]->despesasprevidenciarias;
      }
    }

    /*
     * Ordena os Resultados no array sem perder indices
     * E Calcula Saldo Financeiro do exercicio anterior
     * com exercicio atual
     *
     */
    ksort($aRetorno);
    foreach ($aRetorno as $iAno => &$oRetorno) {

      $nValorAnterior = 0;
      if (isset($aRetorno[$iAno-1])) {
        $nValorAnterior = $aRetorno[$iAno-1]->saldofinanceiro;
      }
      $oRetorno->saldofinanceiro = $nValorAnterior + $aRetorno[$iAno]->resultadoprevidenciario;
    }
    return $aRetorno;
  }

  /**
   * Método que retorna para o anexo XVIII
   * as receitas,  despesas e   resultado para os exercicios os proximos 10 , 20 e 35 a frente
   * @return Objeto com os dados
   */
  public function getDadosSimplificado() {

    /*
     * inicia o metodo anterior, para receber os valores calculados
     */
    $oRetorno        = new stdClass();

    $oRetorno->receitasprevidenciarias                   = new stdClass();
    $oRetorno->receitasprevidenciarias->exercicio        = 0;
    $oRetorno->receitasprevidenciarias->exercicio10      = 0;
    $oRetorno->receitasprevidenciarias->exercicio20      = 0;
    $oRetorno->receitasprevidenciarias->exercicio35      = 0;

    $oRetorno->despesasprevidenciarias                   = new stdClass();
    $oRetorno->despesasprevidenciarias->exercicio        = 0;
    $oRetorno->despesasprevidenciarias->exercicio10      = 0;
    $oRetorno->despesasprevidenciarias->exercicio20      = 0;
    $oRetorno->despesasprevidenciarias->exercicio35      = 0;

    $oRetorno->resultadoprevidenciario                   = new stdClass();
    $oRetorno->resultadoprevidenciario->exercicio        = 0;
    $oRetorno->resultadoprevidenciario->exercicio10      = 0;
    $oRetorno->resultadoprevidenciario->exercicio20      = 0;
    $oRetorno->resultadoprevidenciario->exercicio35      = 0;


    $aLinhaRelatorio = $this->getDados();

    /*
     * Define as variaveis para o exercicio corrente
     * e 10,20,35 anos a frente do exercicio corrente
     */
    $iAno   = $this->iAnoUsu-1;
    $iAno10 = $iAno+10;
    $iAno20 = $iAno+20;
    $iAno35 = $iAno+35;

    // Valida se o ano corrente está na lista
    if (isset($aLinhaRelatorio[$iAno])) {

      $oRetorno->receitasprevidenciarias->exercicio        += $aLinhaRelatorio[$iAno]->receitasprevidenciarias;
      $oRetorno->despesasprevidenciarias->exercicio        += $aLinhaRelatorio[$iAno]->despesasprevidenciarias;
      $oRetorno->resultadoprevidenciario->exercicio        += $aLinhaRelatorio[$iAno]->resultadoprevidenciario;
    }

    // Testa se o ano corrente +10 esta cadastrado
    if (isset($aLinhaRelatorio[$iAno10])) {

      $oRetorno->receitasprevidenciarias->exercicio10        += $aLinhaRelatorio[$iAno10]->receitasprevidenciarias;
      $oRetorno->despesasprevidenciarias->exercicio10        += $aLinhaRelatorio[$iAno10]->despesasprevidenciarias;
      $oRetorno->resultadoprevidenciario->exercicio10        += $aLinhaRelatorio[$iAno10]->resultadoprevidenciario;
    }

    // Testa se o ano corrente +20 esta cadastrado
    if (isset($aLinhaRelatorio[$iAno20])) {

      $oRetorno->receitasprevidenciarias->exercicio20        += $aLinhaRelatorio[$iAno20]->receitasprevidenciarias;
      $oRetorno->despesasprevidenciarias->exercicio20        += $aLinhaRelatorio[$iAno20]->despesasprevidenciarias;
      $oRetorno->resultadoprevidenciario->exercicio20        += $aLinhaRelatorio[$iAno20]->resultadoprevidenciario;
    }

    // Testa se o ano corrente +35 esta cadastrado e assume os valores calculados no metodo anterior
    if (isset($aLinhaRelatorio[$iAno35])) {

      $oRetorno->receitasprevidenciarias->exercicio35        += $aLinhaRelatorio[$iAno35]->receitasprevidenciarias;
      $oRetorno->despesasprevidenciarias->exercicio35        += $aLinhaRelatorio[$iAno35]->despesasprevidenciarias;
      $oRetorno->resultadoprevidenciario->exercicio35        += $aLinhaRelatorio[$iAno35]->resultadoprevidenciario;
    }

    return $oRetorno;

  }

  public function emitir() {

    $aDadosRelatorio = $this->getDados();

    if (empty($aDadosRelatorio)) {
      throw new BusinessException('Não é possível emitir o relatório, pois não existem valores configurados na Edição Manual para as Instituições selecionadas.');
    }

    /*
     * Reinicia o array para pegar o ano de inicio
     * e o ano final do exercicio
     */
    reset($aDadosRelatorio);

    $sDescricaoInstituicao = "";
    $aListaInstituicoes    = $this->getInstituicoes(true);
    if (count($aListaInstituicoes) > 0) {

      $oInstituicao = current($aListaInstituicoes);
      $sDescricao   = $oInstituicao->getDescricao();
      $sUf          = $oInstituicao->getUf();
      if (count($aListaInstituicoes) > 1 || $oInstituicao->isPrefeitura() == "t") {
        $sDescricao = $oInstituicao->getMunicipio();
      }
      $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
    }

    $sDataInicial = mb_strtoupper(DBDate::getMesExtenso($this->getDataInicial()->getMes()));
    $sDataFinal   = mb_strtoupper(DBDate::getMesExtenso($this->getDataFinal()->getMes()));
    $iAnoPeriodo  = $this->getAno();
    $sDescricaoPeriodo = "{$sDataInicial} A {$sDataFinal} DE {$iAnoPeriodo}";

    $sFonte = "Arial";
    $this->oPdf = new PDFDocument();
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(false);
    $this->oPdf->SetFont($sFonte, "", 6);

    $this->oPdf->addHeaderDescription($sDescricaoInstituicao);

    if (count($aListaInstituicoes) == 1) {
      if (current($aListaInstituicoes)->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription(current($aListaInstituicoes)->getDescricao());
      }
    }

    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DA PROJEÇÃO ATUARIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES");
    $this->oPdf->addHeaderDescription("ORÇAMENTO DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("{$sDescricaoPeriodo}");

    $this->oPdf->AddPage();

    $this->iAltura = 4;

    $this->imprimirCabecalho(true);

    foreach ($aDadosRelatorio as $oLinhaRelatorio) {

      $this->oPdf->SetFont('arial', '', 6);
      $this->oPdf->Cell(23, $this->iAltura,             $oLinhaRelatorio->ano,                          "TRB", 0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->receitasprevidenciarias,"f"), "TRB", 0, "R", 0);
      $this->oPdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->despesasprevidenciarias,"f"), "TRB", 0, "R", 0);
      $this->oPdf->Cell(40, $this->iAltura, db_formatar($oLinhaRelatorio->resultadoprevidenciario,"f"), "TRB", 0, "R", 0);
      $this->oPdf->Cell(48, $this->iAltura, db_formatar($oLinhaRelatorio->saldofinanceiro,"f"),         "TLB", 1, "R", 0);

      $this->imprimirCabecalho(false);
      $this->imprimeInfoProxPagina(false);

    }

    $this->oPdf->ln();
    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo);

    if ($this->oPdf->getAvailHeight() < 35) {
      $this->oPdf->AddPage();
    }

    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);

    $this->oPdf->Output();
  }

  /**
   * Impime cabecalho do relatorio
   *
   * @param bool $lImprime
   */
  public function imprimirCabecalho($lImprime) {

    if ($this->oPdf->GetY() > $this->oPdf->h - 25 || $lImprime) {

      $this->oPdf->SetFont('arial', 'b', 6);
      if (!$lImprime) {

        $this->oPdf->AddPage("P");
        $this->imprimeInfoProxPagina(true);
      } else {

        $this->oPdf->SetFillColor("777");
        $this->oPdf->Cell(100, 5, "RREO - ANEXO 10 (LRF, art. 53, § 1º, inciso II )", "", 0, "L", 0);
        $this->oPdf->Cell(85, 5, "Em Reais",                                       "", 1, "R", 0);
      }
      /*
       * Cabeçalho a ser Repetido nas paginas
       */
      $this->oPdf->Cell(23, $this->iAltura, "",                                 "TR",  0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "RECEITAS",                         "TR",  0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "DESPESAS",                         "TR",  0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "RESULTADO",                        "TLR", 0, "C", 0);
      $this->oPdf->Cell(48, $this->iAltura, "SALDO FINANCEIRO",                 "TL",  1, "C", 0);

      $this->oPdf->Cell(23, $this->iAltura, "EXERCÍCIO",                        "R",   0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIAS",                  "LR",  0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "PREVIDENCIÁRIO",                   "LR",  0, "C", 0);
      $this->oPdf->Cell(48, $this->iAltura, "DO EXERCÍCIO",                     "L",   1, "C", 0);

      $this->oPdf->Cell(23, $this->iAltura, "",                                 "R",   0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "(a)",                              "L",   0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "(b)",                              "L",   0, "C", 0);
      $this->oPdf->Cell(40, $this->iAltura, "(c)=(a-b)",                        "L",   0, "C", 0);
      $this->oPdf->Cell(48, $this->iAltura, "(d)=('d' exercício anterior)+(c)", "L",   1, "C", 0);

    }
  }

  /**
   * Impime informacao da proxima pagina no relatorio
   *
   * @param bool $lImprime
   */
  public function imprimeInfoProxPagina($lImprime) {

    if ($this->oPdf->GetY() > $this->oPdf->h - 31 || $lImprime) {

      $this->oPdf->SetFont('arial', '', 6);
      if ($lImprime) {
        $this->oPdf->Cell(190, ($this->iAltura*2), 'Continuação ' . ($this->oPdf->PageNo()) . "/{nb}", 'T', 1, "R", 0);
      } else {

        $this->oPdf->Cell(190, ($this->iAltura*3), 'Continua na página ' . ($this->oPdf->PageNo() + 1) . "/{nb}", 'T', 1, "R", 0);
        $this->imprimirCabecalho(false);
      }
    }
  }
}
