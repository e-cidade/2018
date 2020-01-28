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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017;
use ECidade\Financeiro\Contabilidade\Calculo\Despesa;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\ProcessamentoRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;

use Exception;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017
 */
class AnexoI extends ProcessamentoRelatorioLegal {

  /**
   * Código Padrão do Relatório
   * @var integer
   */
  const CODIGO_RELATORIO = 166;

  /**
   * Oficial, Retrato
   * @var integer
   */
  const MODELO_OFICIAL             = 1;

  /**
   * Detalhamento Mensal, Paisagem
   * @var integer
   */
  const MODELO_DETALHAMENTO_MENSAL = 2;

  /**
   * Modelo que vai ser impresso
   * @var int
   */
  private $iModelo;

  /**
   * Data de Inicio do período de 12 meses do relatório
   * @var \DBDate
   */
  private $oDataInicio;

  /**
   * @var ReceitaCorrenteLiquida
   */
  private $oRCL;

  /**
   * Array usado no Modelo de Detalhamento Mensal
   * Contêm o Mês/Ano
   * @var array
   */
  private $aIntervaloMeses = array();

  /**
   * Variavel para das linhas do pdf
   * @var array
   */
  private $aTamanhoCelulas = array(

    self::MODELO_DETALHAMENTO_MENSAL => array (
      'iLinha'      => 283,
      'iWDescricao' => 41,
      'iWMes'       => 17,
      'iWTotais'    => 19,
    ),
    self::MODELO_OFICIAL => array (
      'iLinha'      => 190,
      'iWDescricao' => 130,
      'iWTotais'    => 30,
    )
  );

  /**
   * Se esta selecionado para emitir todo exercício. (ano cheio de jan a dez)
   * @var boolean
   */
  private $lDoExercicio = false;


  /**
   * Valores encontrados para a despesa separados por mês:
   * Ex: array(
   *  linha_1 => (01/2016 => <valor>, 02/2016 => <valor>, [...])
   * )
   * @var array
   */
  private $aValoresDespesaPorLinhaMes = array();

  /**
   * [__construct description]
   * @param  integer       $iAno          ano da sessão
   * @param  \Periodo      $oPeriodo      Periodo dos relatório contabeis
   * @param  \Instituicao[] $aInstituicoes
   * @param  integer       $iModelo       Modelo do layout 1 - Oficial | 2 - Detalhamento Mensal
   */
  public function __construct($iAno, $oPeriodo, $aInstituicoes, $iModelo)  {

    $this->iModelo = $iModelo;
    parent::__construct($iAno, $oPeriodo, self::CODIGO_RELATORIO, $aInstituicoes);

    $this->lDoExercicio = in_array($this->oPeriodo->getCodigo(), array(13, 16, 28));
    $this->calculaDataInicial();

    $aInstituicoesRCL = \InstituicaoRepository::getInstituicoes();
    $this->oRCL       = new ReceitaCorrenteLiquida($iAno, $aInstituicoesRCL);
  }


  /**
   * @return int
   */
  public function getModelo() {
    return $this->iModelo;
  }

  /**
   * @return \DBDate
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * Calcula data inicial de acordo com o período informado
   * @return \DBDate
   */
  private function calculaDataInicial() {

    if (!is_null($this->oDataInicio)) {
      return $this->oDataInicio;
    }

    // 2º SEMESTRE, 3º QUADRIMESTRE, DEZEMBRO
    if ( $this->lDoExercicio ) {

      $this->oDataInicio = new \DBDate("{$this->iAno}-01-01");
      return $this->oDataInicio;
    }

    $oDataFinal  = \Periodo::dataFinalPeriodo($this->oPeriodo->getCodigo(), $this->iAno);
    $aDataFinal  = explode('-', $oDataFinal->getDate());
    $mMesInicial = ( (int) $aDataFinal[1] ) + 1;
    $mMesInicial = str_pad($mMesInicial, 2, 0, STR_PAD_LEFT);

    $iAnoDataInicial   = $this->iAno - 1;
    $this->oDataInicio = new \DBDate("{$iAnoDataInicial}-{$mMesInicial}-01");

    return $this->oDataInicio;
  }

  /**
   * @return array
   */
  private function getMesesAbrangente() {

    if ( empty($this->aIntervaloMeses) ) {

      $aIntervaloMeses = \DBDate::getMesesNoIntervalo( $this->oDataInicio, $this->oDataFinal, false) ;
      foreach ($aIntervaloMeses as $iAno => $aMeses) {

        foreach ($aMeses as $iMes) {

          $this->aIntervaloMeses[$iMes] = \DBDate::getMesAbreviado($iMes) . '/' . $iAno;
        }
      }
    }

    return $this->aIntervaloMeses;
  }

  /**
   * Inicializa um array com as linhas e as competencias (mes/ano) presente no relatório
   */
  private function inicializaValoresDespesaPorLinhaMes() {

    foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {

      list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
      for( $iLinha = 1; $iLinha < 11; $iLinha ++) {
        $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] = 0;
      }
    }
  }

  /**
   * @return array
   */
  public function getDados() {

    if ( empty($this->aLinhasConsistencia) ) {

      parent::getDados();
      $this->processarCalculoPorMeses();
    }
    return $this->aLinhasConsistencia;
  }

  /**
   * Processa os desdobramentos configurados pelo usuário
   * @param \DBDate $oDataInicial
   * @param \DBDate $oDataFinal
   */
  private function processarDesdobramentosPorDatas(\DBDate $oDataInicial, \DBDate $oDataFinal) {

    foreach ($this->aLinhasProcessarDespesa as $iLinha) {

      $oStdLinha = $this->aLinhasConsistencia[$iLinha];
      foreach ($oStdLinha->parametros->contas as $oStdConta) {

        if ($oStdConta->nivel <= 7) {
          continue;
        }

        $oEstrutural = new Estrutural($oStdConta->estrutural);
        $oDespesa    = new Despesa($this->aInstituicoes);
        $oDespesa->setDataInicial($oDataInicial);
        $oDespesa->setDataFinal($oDataFinal);
        $aDocumentos = array(
          'not in' => array(
            Documento::LIQUIDACAO_RP,
            Documento::ESTORNO_LIQUIDACAO_RP,
            Documento::LIQUIDACAO_RP_ESTOQUE_PATRIMONIO,
            Documento::ESTORNO_LIQUIDACAO_RP_ESTOQUE_PATRIMONIO
          )
        );
        $oValores = $oDespesa->getValorLiquidadoPorElementoDoOrcamento($oEstrutural, $aDocumentos);

        $nValorLiquidado = $oValores->getValorInclusaoMenosEstorno();
        if ($oStdConta->exclusao) {
          $nValorLiquidado *= -1;
        }
        $this->aLinhasConsistencia[$oStdLinha->ordem]->{$oStdLinha->colunas[1]->o115_nomecoluna} = ($oStdLinha->liquidado_ultimo_ano + $nValorLiquidado);
      }
    }
  }

  /**
   * Processa os valores do balancete dos últimos 12 meses a partir do período informado
   */
  private function processarCalculoPorMeses() {

    $this->inicializaValoresDespesaPorLinhaMes();

    /**
     * limpa os valores calculados no metodo getDados()
     */
    foreach ($this->aLinhasConsistencia as $oStdLinha) {

      $oStdLinha->liquidado_ultimo_ano = 0;
      if ( !$this->lDoExercicio ) {
        $oStdLinha->rp_nao_processado = 0;
      }
    }

    foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {

      list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
      $iUltimoDiaMes       = cal_days_in_month(CAL_GREGORIAN, $iMes, $iAno);
      $oDataInicialPeriodo = new \DBDate("01/{$iMes}/{$iAno}");
      $oDataFinalPeriodo   = new \DBDate("{$iUltimoDiaMes}/{$iMes}/{$iAno}");

      $sWhereDespesa      = " o58_instit in({$this->getInstituicoes()})";
      $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
        $iAno,
        $oDataInicialPeriodo,
        $oDataFinalPeriodo
      );

      foreach ($this->aLinhasProcessarDespesa as $iLinha ) {

        $oLinha            = $this->aLinhasConsistencia[$iLinha];
        $nValorAnterior    = $oLinha->liquidado_ultimo_ano;
        $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(1));
        \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
          $oLinha,
          $aColunasProcessar,
          \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
        );

        if ( self::MODELO_DETALHAMENTO_MENSAL == $this->iModelo ) {
          $this->processarDesdobramentosPorDatas($oDataInicialPeriodo, $oDataFinalPeriodo);
        }

        $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] = $this->aLinhasConsistencia[$iLinha]->liquidado_ultimo_ano - $nValorAnterior;
        $this->limparEstruturaBalanceteDespesa();
      }
    }


    if ( !$this->lDoExercicio ) {
      $this->calcularRestosAPagarDoExercicioAnterior();
    }

    $this->calculaValorManual();
    $this->processaTotalizadores($this->aLinhasConsistencia);
  }

  /**
   * Calcula os Restos a Pagar do exercício anterior
   * Valor do RP = RP não processados - Anulação de RP não processado no execício atual
   * Ex.: Valor do RP =  (RP não processados 2016) - (Anulação de RP não processado 2017)
   */
  private function calcularRestosAPagarDoExercicioAnterior() {

    $aLinhasCalcular = array(2, 3, 4, 6, 7, 8, 9);

    $oStdColunaProcessarPeriodoAnterior            = new \stdClass();
    $oStdColunaProcessarPeriodoAnterior->nome      = "rp_nao_processado";
    $oStdColunaProcessarPeriodoAnterior->formula   = "#e91_vlremp-#e91_vlranu-#e91_vlrliq";
    $oStdColunaProcessarPeriodoAnterior->analisada = false;

    $aColunasProcessarAnterior = array($oStdColunaProcessarPeriodoAnterior);

    $oStdColunaProcessarPeriodoAtual            = new \stdClass();
    $oStdColunaProcessarPeriodoAtual->nome      = "rp_nao_processado";
    $oStdColunaProcessarPeriodoAtual->formula   = "#vlranuliqnaoproc";
    $oStdColunaProcessarPeriodoAtual->analisada = false;

    $aColunasProcessarPeriodoAtual = array($oStdColunaProcessarPeriodoAtual);

    $iAnoCalculo                = $this->iAnoUsu;
    $sDataInicioPeriodoAnterior = $this->oDataInicio->getDate();
    $sDataFimPeriodoAnterior    = "{$this->oDataInicio->getAno()}-12-31";

    $sDataInicioPeriodoAtual    = "$this->iAnoUsu-01-01";
    $sDataFimPeriodoAtual       = $this->oPeriodo->dataFinalPeriodo($this->oPeriodo->getCodigo(), $this->iAnoUsu);


    $oDaoRestosAPagar         = new \cl_empresto();
    $sInstituicoes            = " e60_instit in({$this->getInstituicoes()})";
    $sSqlRestosaPagarAnterior = $oDaoRestosAPagar->sql_rp_novo($iAnoCalculo, $sInstituicoes, $sDataInicioPeriodoAnterior, $sDataFimPeriodoAnterior);
    $sSqlRestosaPagarAtual    = $oDaoRestosAPagar->sql_rp_novo($iAnoCalculo, $sInstituicoes, $sDataInicioPeriodoAtual, $sDataFimPeriodoAtual);

    $rsRestosPagarAnterior   = db_query($sSqlRestosaPagarAnterior);
    if (!$rsRestosPagarAnterior) {
      throw new Exception("Ocorreu um erro ao consultar os restos a pagar do ano anterior.");
    }

    $rsRestosPagarAtual      = db_query($sSqlRestosaPagarAtual);
    if (!$rsRestosPagarAtual) {
      throw new Exception("Ocorreu um erro ao consultar os restos a pagar do ano anterior.");
    }

    foreach ($this->aLinhasConsistencia as $oStdLinha) {

      if (!in_array($oStdLinha->ordem, $aLinhasCalcular) ) {
        continue;
      }

      // calcula o valor do RP do periodo anterior
      \RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagarAnterior, $oStdLinha, $aColunasProcessarAnterior,
        \RelatoriosLegaisBase::TIPO_CALCULO_RESTO);

      // armazena o valor dos Restos a Pagar não processados
      $nValorRestosPagar = $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado;

      // zera a variável para calcular os Restos a Pagar ANULADOS não processados
      $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado = 0;
      // calcula o valor dos Restos a Pagar ANULADOS não processados
      \RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagarAtual, $oStdLinha, $aColunasProcessarPeriodoAtual,
        \RelatoriosLegaisBase::TIPO_CALCULO_RESTO);

      $nValorAnulado = $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado;

      $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado = $nValorRestosPagar - $nValorAnulado;

      foreach ($oStdLinha->parametros->contas as $oStdConta) {

        if ($oStdConta->nivel <= 7) {
          continue;
        }

        $oEstrutural = new Estrutural($oStdConta->estrutural);
        $oDespesa    = new Despesa($this->aInstituicoes);
        $oDespesa->setDataInicial($this->oDataInicio);
        $oDespesa->setDataFinal(new \DBDate("{$this->oDataInicio->getAno()}-12-31"));
        $oValorInscritoRP = $oDespesa->getValorInscritoEmRestosAPagarNaoProcessados($oEstrutural, array('in' => array(1007)));

        $oDespesa->setDataInicial($this->oDataInicial);
        $oDespesa->setDataFinal($this->oDataFinal);
        $oValorAnuladoRP  = $oDespesa->getValorAnuladoPorElementoDoOrcamento($oEstrutural, array('in' => array(32)));

        $nValor = $oValorInscritoRP->getValorInclusao() - $oValorAnuladoRP->getValorEstorno();
        if ($oStdConta->exclusao) {
          $nValor *= -1;
        }
        $this->aLinhasConsistencia[$oStdLinha->ordem]->rp_nao_processado += $nValor;
      }

    }
  }

  /**
   * Calcula o valor que foi informado manualmente da:
   *  - liquidação: sempre
   *  - restos a pagar: somente quando não é do exercício
   *
   * Calcula o valor da liquidação que foi informado manualmente
   */
  private function calculaValorManual() {

    foreach ($this->aLinhasProcessarDespesa as $iLinha ) {

      $aLinhasManuais = $this->aLinhasConsistencia[$iLinha]->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
      foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {

        list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);
        foreach($aLinhasManuais as $oLinhaManual) {

          if ( $oLinhaManual->colunas[0]->o117_valor == $sCompetencia ) {

            $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] += $oLinhaManual->colunas[1]->o117_valor;
            // Atualiza o totalizador da coluna liquidado
            $this->aLinhasConsistencia[$iLinha]->liquidado_ultimo_ano += $oLinhaManual->colunas[1]->o117_valor;

            // Calcula o valor manual pq o valor do RP é calculado na mão quando período engloba mais de um exercício
            if ( !$this->lDoExercicio ) {
              // echo "Linha: {$oLinhaManual->ordem} --- rp_nao_processado: {$this->aLinhasConsistencia[$iLinha]->rp_nao_processado} += vlr manual : {$oLinhaManual->colunas[2]->o117_valor} <br>";
              $this->aLinhasConsistencia[$iLinha]->rp_nao_processado += $oLinhaManual->colunas[2]->o117_valor;
            }
          }
        }
      }
    }
  }

  /**
   * Processa os dados para emissao do relatorio em modelo Oficial
   */
  private function processarOficial() {

    $this->getDados();
    $this->processarDesdobramentosPorDatas($this->oDataInicio, $this->getDataFinal());
    $this->calculaReceitaCorrenteLiquida();
    $this->processarFormasDasLinhas(array(1, 5, 10, 14));
  }

  /**
   * Processa os dados para emissão do relatório em modelo Detalhamento Mensal
   */
  private function processarDetalhamentoMensal() {

    $this->getDados();

    $aLinhasTotalizadorasSoma = array (
      1  => array(2, 3, 4),
      5  => array(6, 7, 8, 9)
    );

    $aLinhasTotalizadorasSub = array (
      10 => array(1, 5)
    );

    foreach ($aLinhasTotalizadorasSoma as $iLinhaTotalizadora => $aLinhasSomar) {

      foreach ($this->aValoresDespesaPorLinhaMes as $iLinha => $aValoreMesAno) {

        foreach ($aValoreMesAno as $sMesAno => $sValor) {

          if ( in_array($iLinha, $aLinhasSomar) ) {
            $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] += $sValor;
          }
        }
      }
    }

    foreach ($aLinhasTotalizadorasSub as $iLinhaTotalizadora => $aLinhasSubtrair) {

      foreach ($this->aValoresDespesaPorLinhaMes as $iLinha => $aValoreMesAno) {

        foreach ($aValoreMesAno as $sMesAno => $sValor) {

          if ( in_array($iLinha, $aLinhasSubtrair) ) {
            if($aLinhasSubtrair[0] == $iLinha){

              $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] += $sValor;
            } else {
              $this->aValoresDespesaPorLinhaMes[$iLinhaTotalizadora][$sMesAno] -= $sValor;
            }
          }
        }
      }
    }

    $this->processarFormasDasLinhas(array(14));
    $this->calculaReceitaCorrenteLiquida();
  }

  /**
   * Calcula os valores do quadro Apuração do Cumprimento do Limite Legal
   */
  public function calculaReceitaCorrenteLiquida() {

    $oTipoInstituicao = $this->getTipoInstituicao();
    $iLimiteMaximo    = 0;

    if ( $oTipoInstituicao->lTemPrefeitura || $oTipoInstituicao->lTipoRPPS) {
      $iLimiteMaximo = 54;
    }


    if ( $oTipoInstituicao->lTemCamara) {
      $iLimiteMaximo = 6;

      if (($oTipoInstituicao->lTemPrefeitura && $oTipoInstituicao->lTemCamara) || ($oTipoInstituicao->lTipoRPPS && $oTipoInstituicao->lTemCamara)) {
        $iLimiteMaximo = 60;
      }
    }

    if ($oTipoInstituicao->lTemTribunalContas) {
      $iLimiteMaximo = 1.04;
    }

    if ($oTipoInstituicao->lTemMinisterio) {
      $iLimiteMaximo = 2;
    }

    if ($oTipoInstituicao->lTemTribunalJustica) {
      $iLimiteMaximo = 6;
    }

    $nValorRCL   = $this->aLinhasConsistencia[11]->valor;
    $aCalculoRCL = $this->oRCL->calcularRCLPorPeriodo($this->oDataInicio, $this->getDataFinal());
    foreach ($aCalculoRCL as $iAno =>  $aMesesCalculados) {
      $nValorRCL += array_sum($aMesesCalculados);
    }

    // 11  RECEITA CORRENTE LÍQUIDA - RCL (IV)
    $this->aLinhasConsistencia[11]->valor      = $nValorRCL;
    $this->aLinhasConsistencia[11]->percentual = ' - ';

    // 12  (-) Transferências obrigatórias da União relativas às emendas individuais (V) (§ 13, art. 166 da CF)
    $this->aLinhasConsistencia[12]->percentual = ($this->aLinhasConsistencia[12]->valor / $nValorRCL) * 100;

    // 13  = RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)
    $this->aLinhasConsistencia[13]->valor      = $this->aLinhasConsistencia[11]->valor - $this->aLinhasConsistencia[12]->valor;
    $this->aLinhasConsistencia[13]->percentual = ' - ';

    // 14  DESPESA TOTAL COM PESSOAL - DTP (VII) = (III a + III b)
    $this->aLinhasConsistencia[14]->percentual = ($this->aLinhasConsistencia[14]->valor / $this->aLinhasConsistencia[11]->valor) * 100;

    /**
     * Valores das linhas 15,16 e 17 devem ser calculados em cima da linha 13 = RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)
     */
    $nValorRCLCalcular = $this->aLinhasConsistencia[13]->valor;

    /**
     * Calcula os percentual para as colunas 16 e 17
     */
    $nLimitePrudencial   = round(($iLimiteMaximo * 0.95), 2);
    $nLimiteMaximoAlerta = round(($iLimiteMaximo * 0.90), 2);

    $nValorLimiteMaximo     = ($nValorRCLCalcular * $iLimiteMaximo)       / 100;
    $nValorLimitePrudencial = ($nValorRCLCalcular * $nLimitePrudencial)   / 100;
    $nValorLimiteAlerta     = ($nValorRCLCalcular * $nLimiteMaximoAlerta) / 100;

    // 15  LIMITE MÁXIMO (VIII) (incisos I, II e III, art. 20 da LRF)
    $this->aLinhasConsistencia[15]->valor      = $nValorLimiteMaximo;
    $this->aLinhasConsistencia[15]->percentual = $iLimiteMaximo;
    // 16  LIMITE PRUDENCIAL (IX) = (0,95 x VIII) (parágrafo único do art. 22 da LRF)
    $this->aLinhasConsistencia[16]->valor      = $nValorLimitePrudencial;
    $this->aLinhasConsistencia[16]->percentual = $nLimitePrudencial;
    // 17  LIMITE DE ALERTA (X) = (0,90 x VIII) (inciso II do §1º do art. 59 da LRF)
    $this->aLinhasConsistencia[17]->valor      = $nValorLimiteAlerta;
    $this->aLinhasConsistencia[17]->percentual = $nLimiteMaximoAlerta;
  }

  /**
   * Retorna um array com as linhas processadas para impressão
   * @return Linha[]
   */
  public function getDadosProcessados() {

    if ( $this->iModelo == self::MODELO_OFICIAL ) {
      return $this->getDadosOficial();
    }

    return $this->getDadosDetalhamentoMensal();
  }

  /**
   * Retorna as linhas no modelo oficial
   * As linhas podem conter chamadas de metodos
   * @return Linha[]
   */
  private function getDadosOficial() {

    $this->processarOficial();

    $oLinha = new Linha();
    $oLinha->informaMetodo("cabecalhoQuadroUmOficial");
    $this->aLinhasProcessadas[] = $oLinha;
    foreach ($this->aLinhasConsistencia as $oLinhaRelatorio ) {

      $sNivel     = str_repeat(' ', $oLinhaRelatorio->nivel * 2);
      $sDescricao = "{$sNivel} {$oLinhaRelatorio->descricao}";
      if ($oLinhaRelatorio->ordem < 11 ) {

        $aBordas = array('R', 'LR', 'L');
        if ( $oLinhaRelatorio->ordem == 10 ) {
          $aBordas = array('TBR', 'TBLR', 'TBL');
        }

        $this->adicionaLinhaModeloOficial(
          $sDescricao,
          $oLinhaRelatorio->liquidado_ultimo_ano,
          $oLinhaRelatorio->rp_nao_processado,
          $aBordas
        );
      }

      if ( $oLinhaRelatorio->ordem == 11  ) {

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadroDois");
        $this->aLinhasProcessadas[] = $oLinha;
      }

      if ($oLinhaRelatorio->ordem >= 11 ) {

        $iFill   = 0;
        if ( $oLinhaRelatorio->ordem == 14) {
          $iFill = 1;
        }
        $this->adicionaLinhaModeloOficial(
          $sDescricao,
          $oLinhaRelatorio->valor,
          $oLinhaRelatorio->percentual,
          array('TBR', 1, 'TBL'),
          $iFill
        );
      }
    }

    $oLinha = new Linha();
    $oLinha->informaMetodo("notaExplicativaPdf");
    $this->aLinhasProcessadas[] = $oLinha;

    return $this->aLinhasProcessadas;
  }

  /**
   * Imprime cabeçalho da:  DESPESA COM PESSOAL
   * @param  \PDFDocument $oPdf
   */
  public function cabecalhoQuadroUmOficial(\PDFDocument $oPdf) {

    $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];

    $oPdf->SetFont("Arial", "", 6);
    $oPdf->Cell(180, 4, 'RGF - ANEXO 1 (LRF, art. 55, inciso I, alínea "a")');
    $oPdf->Cell(10,  4, 'R$ 1,00', 0, 1 );

    $iWDescricao = $aConfiguracoes['iWDescricao'];
    $iWTotais    = $aConfiguracoes['iWTotais'];
    $iTotais     = $iWTotais * 2;

    $oPdf->SetFont("Arial", "B", 7);
    $oPdf->Cell($iWDescricao, 4, ''                   , 'TR' , 0, '' , 1);
    $oPdf->Cell($iTotais,     4, 'DESPESAS EXECUTADAS', 'TL', 1, 'C', 1);

    $oPdf->SetFont("Arial", "B", 5);
    $oPdf->Cell($iWDescricao, 4, ''                  , 'R' , 0,  '', 1 );
    $oPdf->Cell($iTotais,     4, '(Últimos 12 Meses)', 'LB', 1, 'C', 1 );

    $oPdf->SetFont("Arial", "B", 7);
    $oPdf->Cell($iWDescricao, 4, 'DESPESA COM PESSOAL' , 'R'  , 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, 'LIQUIDADAS'          , 'TLR', 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, 'INSCRITAS EM'        , 'TL' , 1, 'C', 1 );

    $oPdf->Cell($iWDescricao, 4, ''              , 'R' , 0,  '', 1);
    $oPdf->Cell($iWTotais,    4, ''              , 'LR', 0, 'C', 1);
    $oPdf->Cell($iWTotais,    4, 'RESTOS A PAGAR', 'L' , 1, 'C', 1);

    $oPdf->Cell($iWDescricao, 4, ''                , 'R' , 0,  '', 1 );
    $oPdf->Cell($iWTotais,    4, ''                , 'LR', 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, 'NÃO PROCESSADOS¹', 'L' , 1, 'C', 1 );

    $oPdf->Cell($iWDescricao, 4, ''    , 'BR' , 0,  '', 1 );
    $oPdf->Cell($iWTotais,    4, '(a)' , 'BLR', 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, '(b)' , 'BL' , 1, 'C', 1 );

    $oPdf->SetFont("Arial", "", 7);
  }

  /**
   * Imprime cabeçalho da: APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL
   * @param  \PDFDocument $oPdf
   */
  public function cabecalhoQuadroDois(\PDFDocument $oPdf) {

    $oPdf->ln();
    $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];
    $iWDescricao    = $aConfiguracoes['iWDescricao'];
    $iWTotais       = $aConfiguracoes['iWTotais'];

    if ( $this->iModelo == self::MODELO_DETALHAMENTO_MENSAL ) {

      $oPdf->addPage();
      $iWDescricao = 206;
      $iWTotais    = 38.5;
    }

    $oPdf->SetFont("Arial", "B", 7);
    $oPdf->Cell($iWDescricao, 4, 'APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL', 'TBR', 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, 'VALOR'       ,                            1    , 0, 'C', 1 );
    $oPdf->Cell($iWTotais,    4, '% SOBRE RCL' ,                            'TBL', 1, 'C', 1 );
    $oPdf->SetFont("Arial", "", 7);
  }

  /**
   * Adiciona uma linha no modelo oficial
   * @param  string  $sDescricao
   * @param  float  $mValor1
   * @param  float  $mValor2
   * @param  array  $aBordas
   * @param  integer $iFill
   */
  private function adicionaLinhaModeloOficial($sDescricao, $mValor1, $mValor2, $aBordas, $iFill = 0) {

    $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_OFICIAL];
    $iWDescricao    = $aConfiguracoes['iWDescricao'];
    $iWTotais       = $aConfiguracoes['iWTotais'];

    if ( $this->iModelo == self::MODELO_DETALHAMENTO_MENSAL ) {

      $iWDescricao = 206;
      $iWTotais    = 38.5;
    }

    $mValor1 = $this->formataValor($mValor1);
    if ( $mValor2 !== ' - ') {
      $mValor2 = $this->formataValor($mValor2);
    }

    $oLinha = new Linha();
    $oLinha->addColuna($iWDescricao, $sDescricao, $aBordas[0], 0, 'L',  $iFill);
    $oLinha->addColuna($iWTotais,    $mValor1,    $aBordas[1], 0, 'R',  $iFill);
    $oLinha->addColuna($iWTotais,    $mValor2,    $aBordas[2], 1, 'R',  $iFill);
    $this->aLinhasProcessadas[] = $oLinha;
  }

  /**
   * Imprime o cabeçalho do quadro 1 no Modelo Detalhamento Mensal
   * @param  \PDFDocument $oPdf
   */
  public function cabecalhoQuadroUmDetalhado(\PDFDocument $oPdf) {

    $aConfiguracoes =  $this->aTamanhoCelulas[self::MODELO_DETALHAMENTO_MENSAL];
    $oPdf->SetFont("Arial", "", 6);
    $oPdf->Cell(271, 4, 'RGF - ANEXO 1 (LRF, art. 55, inciso I, alínea "a")');
    $oPdf->Cell(12,  4, 'R$ 1,00', 0, 1 );

    $aMeses = $this->getMesesAbrangente();

    $iLinha           = $aConfiguracoes['iLinha'];
    $iWDescricao      = $aConfiguracoes['iWDescricao'];
    $iWTotais         = $aConfiguracoes['iWTotais'];
    $iWLinhaCabecalho = $iLinha - $iWDescricao;

    $sTotalUltimosMeses = "TOTAL (ÚLTIMOS 12 MESES)\n\n(a)";
    $sTotalRestosPagar  = "INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS\n\n(b)";
    $oPdf->SetFont("Arial", "", 6);
    $iLinhas            = $oPdf->getMultiCellHeight($aConfiguracoes['iWTotais'], 4, $sTotalRestosPagar);
    $iAlturaCelula      = ($iLinhas)+4;
    $iAlturaCelulaMes   = ($iLinhas)-4;

    $iEixoX = $iWDescricao + 7; // 7 = left margim

    $oPdf->SetFont("Arial", "B", 7);
    $oPdf->Cell($iWDescricao, $iAlturaCelula, "DESPESA COM PESSOAL",           "TBR", 0, 'C');
    $oPdf->Cell($iWLinhaCabecalho, 4, "DESPESAS EXECUTADAS (Últimos 12 meses)", "TBL", 1, 'C');
    $oPdf->setX($iEixoX);
    $iEixoY = $oPdf->getY();
    $oPdf->Cell(($iWLinhaCabecalho - $aConfiguracoes['iWTotais']), 4, "LIQUIDADAS", "1", 1, 'C');
    $oPdf->SetFont("Arial", "", 6);

    $oPdf->setX($iEixoX);
    foreach ($aMeses as $sMesAno) {

      $oPdf->Cell($aConfiguracoes['iWMes'], $iAlturaCelulaMes, $sMesAno, 1, 0, 'C');
      $iEixoX += $aConfiguracoes['iWMes'];
    }
    $oPdf->MultiCell($aConfiguracoes['iWTotais'], 4, $sTotalUltimosMeses, 1, 'C');

    $oPdf->setXY($iEixoX + $aConfiguracoes['iWTotais'], $iEixoY);
    $oPdf->MultiCell($aConfiguracoes['iWTotais'], 4, $sTotalRestosPagar, 'TBL', 'C');

    $oPdf->SetFont("Arial", "", 6);
  }

  /**
   * Monta as linhas que devem ser impressas no relatório
   * @return Linha[]
   */
  private function getDadosDetalhamentoMensal() {

    $this->processarDetalhamentoMensal();
    $oLinha = new Linha();
    $oLinha->informaMetodo("cabecalhoQuadroUmDetalhado");
    $this->aLinhasProcessadas[] = $oLinha;

    foreach ($this->aLinhasConsistencia as $oLinhaRelatorio) {

      if ($oLinhaRelatorio->ordem < 11 ) {
        $this->adicionaLinhaModeloDetalhado($oLinhaRelatorio);
      }

      if ( $oLinhaRelatorio->ordem == 11  ) {

        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoQuadroDois");
        $this->aLinhasProcessadas[] = $oLinha;
      }

      if ($oLinhaRelatorio->ordem >= 11 ) {

        $iFill   = 0;
        if ( $oLinhaRelatorio->ordem == 14) {
          $iFill = 1;
        }
        $this->adicionaLinhaModeloOficial(
          $oLinhaRelatorio->descricao,
          $oLinhaRelatorio->valor,
          $oLinhaRelatorio->percentual,
          array('TBR', 1, 'TBL'),
          $iFill
        );
      }
    }

    $oLinha = new Linha();
    $oLinha->informaMetodo("notaExplicativaPdf");
    $this->aLinhasProcessadas[] = $oLinha;

    return $this->aLinhasProcessadas;
  }

  /**
   * @param $oLinhaRelatorio
   */
  private function adicionaLinhaModeloDetalhado($oLinhaRelatorio) {

    $sNivel     = str_repeat(' ', $oLinhaRelatorio->nivel * 2);
    $sDescricao = "{$sNivel} {$oLinhaRelatorio->descricao}";

    $aBordas = array('R', 'LR', 'L');
    $lBold   = false;
    if ( $oLinhaRelatorio->ordem == 10) {
      $aBordas = array('TBR', '1', 'TBL');
    }

    if ( in_array($oLinhaRelatorio->ordem, array(1,5,10)) ) {
      $lBold = true;
    }

    $aConfiguracoes = $this->aTamanhoCelulas[self::MODELO_DETALHAMENTO_MENSAL];
    $iWDescricao    = $aConfiguracoes['iWDescricao'];
    $iWMes          = $aConfiguracoes['iWMes'];
    $iWTotais       = $aConfiguracoes['iWTotais'];

    $nLiquidado       = $this->formataValor($oLinhaRelatorio->liquidado_ultimo_ano);
    $nRPNaoProcessado = $this->formataValor($oLinhaRelatorio->rp_nao_processado);

    $oLinha = new Linha();
    $oLinha->multicell(true)->bold($lBold)->alturaLinha(5);
    $oLinha->addColuna($iWDescricao, $sDescricao, $aBordas[0], 0, 'L', 0, 4);

    $aCompetencia = $this->aValoresDespesaPorLinhaMes[$oLinhaRelatorio->ordem];
    foreach ( $aCompetencia as $nValor ) {
      $oLinha->addColuna($iWMes, db_formatar($nValor, 'f'), $aBordas[1], 0, 'R', 0, 4);
    }
    $oLinha->addColuna($iWTotais, $nLiquidado      , $aBordas[1], 0, 'R', 0, 4);
    $oLinha->addColuna($iWTotais, $nRPNaoProcessado, $aBordas[2], 1, 'R', 0, 4);
    $this->aLinhasProcessadas[] = $oLinha;
  }

  /**
   * Finaliza terceito quadro e Imprime a nota explicativa
   * @paran \PDFDocument $oPdf
   */
  public function notaExplicativaPdf( \PDFDocument $oPdf ) {

    $oPdf->line($oPdf->getX(), $oPdf->getY(), 200, $oPdf->getY());
    $oPdf->ln(1);
    $this->notaExplicativa( $oPdf, array($oPdf, 'addPage'), 20 );

    $oPdf->ln($oPdf->getAvailHeight() - 10);
    $oDaoAssinatura = new \cl_assinatura();
    assinaturas($oPdf, $oDaoAssinatura, 'GF');
  }

  /**
   * Retorna os tipos de instituiçoes cadastrados no sistema
   * @return \stdClass
   */
  public function getTipoInstituicao() {

    $oTiposInstituicao                      = new \stdClass();
    $oTiposInstituicao->lTemPrefeitura      = false;
    $oTiposInstituicao->lTemCamara          = false;
    $oTiposInstituicao->lTipoRPPS           = false;
    $oTiposInstituicao->lTemMinisterio      = false;
    $oTiposInstituicao->lTemTribunalJustica = false;
    $oTiposInstituicao->lTemTribunalContas  = false;
    $oTiposInstituicao->iCodigoCliente      = null;

    foreach ($this->aInstituicoes as $oInstituicao) {

      $oTiposInstituicao->iCodigoCliente = $oInstituicao->getCodigoCliente();

      switch ($oInstituicao->getTipo()) {
        case \Instituicao::TIPO_PREFEITURA:
          $oTiposInstituicao->lTemPrefeitura = true;
          break;

        case \Instituicao::TIPO_CAMARA:
          $oTiposInstituicao->lTemCamara = true;
          break;

        case \Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA:
        case \Instituicao::TIPO_AUTARQUIA_EXCETO_RPPS:
        case \Instituicao::TIPO_AUTARQUIA_RPPS:
          $oTiposInstituicao->lTipoRPPS = true;
          break;

        case \Instituicao::TIPO_MINISTERIO_PUBLICO_ESTADUAL:
          $oTiposInstituicao->lTemMinisterio = true;
          break;

        case \Instituicao::TIPO_TRIBUNAL_DE_JUSTICA:
          $oTiposInstituicao->lTemTribunalJustica = true;
          break;

        case \Instituicao::TIPO_TRIBUNAL_DE_CONTAS_ESTADO:
          $oTiposInstituicao->lTemTribunalContas = true;
          break;
      }
    }

    return $oTiposInstituicao;
  }


  /**
   * Dados preparados para serem emitidos no Anexo VI - Simplificado
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    $this->processarOficial();

    $oStdAnexo = new \stdClass();
    $oStdAnexo->total_despesa_pessoal      = round($this->aLinhasConsistencia[14]->valor, 2);
    $oStdAnexo->percentual_despesa_pessoal = round($this->aLinhasConsistencia[14]->percentual, 2);

    $oStdAnexo->total_limite_maximo      = round($this->aLinhasConsistencia[15]->valor, 2);
    $oStdAnexo->percentual_limite_maximo = round($this->aLinhasConsistencia[15]->percentual, 2);

    $oStdAnexo->total_limite_prudencial      = round($this->aLinhasConsistencia[16]->valor, 2);
    $oStdAnexo->percentual_limite_prudencial = round($this->aLinhasConsistencia[16]->percentual, 2);

    return $oStdAnexo;
  }

  /*
   * valida se vai retornar -0,00 e formata para 0,00
   * @return \string
   */
  private function formataValor($sValor){

    $sValor = round($sValor, 2);
    $sValor = db_formatar($sValor, 'f');
    return $sValor;
  }
}
