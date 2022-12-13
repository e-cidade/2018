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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;

use \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\LinhaAnexoVII as Linha;

/**
 * Class AnexoVII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017
 */
class AnexoVII {

  /**
   * @var integer
   */
  const LINHA_EXCETO_INTRA = 1;

  /**
   * @var integer
   */
  const LINHA_INTRA = 2;

  /**
   * @var integer
   */
  const LINHA_TOTAL_GERAL = 3;

  /**
   * @var integer
   */
  const LINHA_TOTAL_INTRA = 4;

  /**
   * @var string
   */
  const MENSAGENS = 'financeiro.contabilidade.con2_emissaoAnexoVII.';

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var \Periodo
   */
  private $oPeriodo;


  /**
   * @var \Instituicao[]
   */
  private $aInstituicao;

  /**
   * Códigos das instituicoes separadas por vírgula
   * @var string
   */
  private $sCodigosInstituicoes;

  /**
   * @var \stdClass[]
   */
  private $aPoderLegislativo = array();

  /**
   * @var \stdClass[]
   */
  private $aPoderExecutivo = array();

  /**
   * @var \stdClass[]
   */
  private $aMinisterioPublico = array();

  /**
   * @var \stdClass[]
   */
  private $aPoderJudiciario = array();

  /**
   * @var LinhaAnexoVII[]
   */
  private $aLinhas;

  /**
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param \Periodo $oPeriodo
   */
  public function setPeriodo(\Periodo $oPeriodo) {
    $this->oPeriodo = $oPeriodo;
  }

  /**
   * @return \Periodo
   */
  public function getPeriodo() {
    return $this->oPeriodo;
  }


  /**
   * @param \Instituicao $oInstituicao
   */
  public function adicionarInstituicao(\Instituicao $oInstituicao) {

    $aInstituicoes = array();
    $this->aInstituicao[$oInstituicao->getCodigo()] = $oInstituicao;
    foreach ($this->aInstituicao as $iCodigo => $oInstituicao) {
      $aInstituicoes[] = $iCodigo;
    }
    $this->sCodigosInstituicoes = implode(', ', $aInstituicoes);
  }

  /**
   * Carrega os dados do relatório
   * @return LinhaAnexoVII[]
   */
  public function getDados() {

    if (empty($this->aLinhas)) {
      $this->processar();
    }
    return $this->aLinhas;
  }

  /**
   * @return string
   */
  public function getInstituicoes()
  {
    return $this->sCodigosInstituicoes;
  }


  /**
   * Contrói os valores de acordo com os dados informados na assinatura do método
   * @param boolean       $lIntraOrcamentario
   * @param LinhaAnexoVII $oLinha
   */
  private function construirValores($lIntraOrcamentario, LinhaAnexoVII &$oLinha) {

    $aPoderesParaPercorrer = array($this->aPoderExecutivo, $this->aPoderLegislativo, $this->aPoderJudiciario, $this->aMinisterioPublico);

    foreach ($aPoderesParaPercorrer as $iIndice => $aLinhaPoder) {

      /* Caso não exista valores lançados para o tipo de poder */
      if (count($aLinhaPoder) == 0) {
        continue;
      }

      foreach ($aLinhaPoder as $oStdPoder) {

        if ($oStdPoder->intra_orcamentaria != $lIntraOrcamentario) {
          continue;
        }

        $this->totalizarLinhaSintetica($oLinha, $oStdPoder);

        switch ($iIndice) {

          case LinhaAnexoVII::PODER_EXECUTIVO :

            $oLinhaAnalitica = new LinhaAnexoVII();
            $oLinhaAnalitica->setDescricao('PODER EXECUTIVO');
            $oLinhaAnalitica->setTipo(LinhaAnexoVII::PODER_EXECUTIVO);
            $this->construirLinha($oLinhaAnalitica, $oStdPoder);
            $oLinha->adicionarLinha($oLinhaAnalitica);
            break;

          case LinhaAnexoVII::PODER_LEGISLATIVO :

            $oLinhaAnalitica = new LinhaAnexoVII();
            $oLinhaAnalitica->setDescricao('PODER LEGISLATIVO');
            $oLinhaAnalitica->setTipo(LinhaAnexoVII::PODER_LEGISLATIVO);
            $this->construirLinha($oLinhaAnalitica, $oStdPoder);
            $oLinha->adicionarLinha($oLinhaAnalitica);
            break;

          case LinhaAnexoVII::PODER_JUDICIARIO:

            $oLinhaAnalitica = new LinhaAnexoVII();
            $oLinhaAnalitica->setDescricao('PODER JUDICIÁRIO');
            $oLinhaAnalitica->setTipo(LinhaAnexoVII::PODER_JUDICIARIO);

            $this->construirLinha($oLinhaAnalitica, $oStdPoder);
            $oLinha->adicionarLinha($oLinhaAnalitica);
            break;

          case LinhaAnexoVII::MINISTERIO_PUBLICO:

            $oLinhaAnalitica = new LinhaAnexoVII();
            $oLinhaAnalitica->setDescricao('MINISTÉRIO PÚBLICO');
            $oLinhaAnalitica->setTipo(LinhaAnexoVII::MINISTERIO_PUBLICO);
            $this->construirLinha($oLinhaAnalitica, $oStdPoder);
            $oLinha->adicionarLinha($oLinhaAnalitica);
            break;
        }
      }
    }
  }

  /**
   * Constrói os dados da linha de acordo com as informações repassadas
   * @param LinhaAnexoVII $oLinha
   * @param \stdClass     $oStdLinha
   */
  private function construirLinha(LinhaAnexoVII &$oLinha, \stdClass $oStdLinha) {

    $oLinha->setValorProcessadoEmExerciciosAnteriores($oStdLinha->inscricao_ant);
    $oLinha->setValorProcessadoNoExercicioAnterior($oStdLinha->valor_processado);
    $oLinha->setValorPagoProcessado($oStdLinha->vlrpag);
    $oLinha->setValorCanceladoProcessado($oStdLinha->vlranuliq);
    $oLinha->setSaldoProcessado($oStdLinha->rp_ex_ant_saldo);

    $oLinha->setValorNaoProcessadoEmExerciciosAnteriores($oStdLinha->valor_nao_processado_ant);
    $oLinha->setValorNaoProcessadoNoExercicioAnterior($oStdLinha->valor_nao_processado);
    $oLinha->setValorLiquidadoNaoProcessado($oStdLinha->vlrliq);
    $oLinha->setValorPagoNaoProcessado($oStdLinha->vlrpagnproc);
    $oLinha->setValorCanceladoNaoProcessado($oStdLinha->vlranuliqnaoproc);
    $oLinha->setSaldoNaoProcessado($oStdLinha->rp_n_proc_saldo);
    $oLinha->setSaldoTotal($oLinha->getSaldoProcessado() + $oLinha->getSaldoNaoProcessado());
  }

  /**
   * @param LinhaAnexoVII $oLinha
   * @param \stdClass     $oStdLinha
   */
  private function totalizarLinhaSintetica(LinhaAnexoVII &$oLinha, \stdClass $oStdLinha) {

    $oLinha->setValorProcessadoEmExerciciosAnteriores($oLinha->getValorProcessadoEmExerciciosAnteriores() + $oStdLinha->inscricao_ant);
    $oLinha->setValorProcessadoNoExercicioAnterior($oLinha->getValorProcessadoNoExercicioAnterior() + $oStdLinha->valor_processado);
    $oLinha->setValorPagoProcessado($oLinha->getValorPagoProcessado() + $oStdLinha->vlrpag);
    $oLinha->setValorCanceladoProcessado($oLinha->getValorCanceladoProcessado() + $oStdLinha->vlranuliq);
    $oLinha->setSaldoProcessado($oLinha->getSaldoProcessado() + $oStdLinha->rp_ex_ant_saldo);

    $oLinha->setValorNaoProcessadoEmExerciciosAnteriores($oLinha->getValorNaoProcessadoEmExerciciosAnteriores() + $oStdLinha->valor_nao_processado_ant);
    $oLinha->setValorNaoProcessadoNoExercicioAnterior($oLinha->getValorNaoProcessadoNoExercicioAnterior() + $oStdLinha->valor_nao_processado);
    $oLinha->setValorLiquidadoNaoProcessado($oLinha->getValorLiquidadoNaoProcessado() + $oStdLinha->vlrliq);
    $oLinha->setValorPagoNaoProcessado($oLinha->getValorPagoNaoProcessado() + $oStdLinha->vlrpagnproc);
    $oLinha->setValorCanceladoNaoProcessado($oLinha->getValorCanceladoNaoProcessado() + $oStdLinha->vlranuliqnaoproc);
    $oLinha->setSaldoNaoProcessado($oLinha->getSaldoNaoProcessado() + $oStdLinha->rp_n_proc_saldo);
    $oLinha->setSaldoTotal($oLinha->getSaldoProcessado() + $oLinha->getSaldoNaoProcessado());
  }

  /**
   * Separa as despesas pelo tipo de poder
   * @param resource $rsResource
   * @param bool $lIntraOrcamentaria
   *
   * @throws \BusinessException
   */
  private function agruparPorPoderes($rsResource, $lIntraOrcamentaria = true) {

    $iTotalRegistros = pg_num_rows($rsResource);
    for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

      $oStdRestos = \db_utils::fieldsMemory($rsResource, $iRow);
      if (empty($oStdRestos->tipo_instituicao)) {
        throw new \BusinessException(_M(self::MENSAGENS . 'tipo_instituicao_invalido'));
      }

      $sIndice = $lIntraOrcamentaria ? 'intra' : 'exceto-intra';
      $oStdRestos->intra_orcamentaria = $lIntraOrcamentaria;
      switch ($oStdRestos->tipo_instituicao) {

        case 2:

          if (empty($this->aPoderLegislativo[$sIndice])) {
            $this->aPoderLegislativo[$sIndice] = $oStdRestos;
          } else {
            $this->aPoderLegislativo[$sIndice] = $this->agruparValores($this->aPoderLegislativo[$sIndice], $oStdRestos);
          }
          break;

        case 13:

          if (empty($this->aPoderJudiciario[$sIndice])) {
            $this->aPoderJudiciario[$sIndice] = $oStdRestos;
          } else {
            $this->aPoderJudiciario[$sIndice] = $this->agruparValores($this->aPoderJudiciario[$sIndice], $oStdRestos);
          }
          break;

        case 101:

          if (empty($this->aMinisterioPublico[$sIndice])) {
            $this->aMinisterioPublico[$sIndice] = $oStdRestos;
          } else {
            $this->aMinisterioPublico[$sIndice] = $this->agruparValores($this->aMinisterioPublico[$sIndice], $oStdRestos);
          }
          break;

        default:

          if (empty($this->aPoderExecutivo[$sIndice])) {
            $this->aPoderExecutivo[$sIndice] = $oStdRestos;
          } else {
            $this->aPoderExecutivo[$sIndice] = $this->agruparValores($this->aPoderExecutivo[$sIndice], $oStdRestos);
          }
      }
    }
  }

  protected  function agruparValores($oStdLinhaBase, $oStdResto) {

    /**
     * Define um array com as propriedades disponíveis no objeto limpando as propriedades que não podem ser somadas
     */
    $aPropriedades = get_object_vars($oStdLinhaBase);
    unset($aPropriedades['intra_orcamentaria'], $aPropriedades['tipo_instituicao']);

    /**
     * Percorremos as linhas pulando as linhas diferentes do parâmetro informado e indice base
     */
    foreach ($aPropriedades as $sPropriedade => $sValor) {
      $oStdLinhaBase->{$sPropriedade} += $oStdResto->{$sPropriedade};
    }
    return $oStdLinhaBase;
  }

  /**
   * Processas as informações do relatório
   */
  private function processar() {


    $this->aPoderLegislativo  = array();
    $this->aPoderJudiciario   = array();
    $this->aMinisterioPublico = array();
    $this->aPoderExecutivo    = array();
    $this->aLinhas = array();

    $this->agruparPorPoderes($this->getRestosAPagarExcetoIntraOrcamentarios(), false);
    $this->agruparPorPoderes($this->getRestosAPagarIntraOrcamentarios(), true);

    $oLinhaExcetoIntra = new Linha();
    $oLinhaExcetoIntra->setDescricao('RESTOS A PAGAR (EXCETO INTRA-ORÇAMENTÁRIOS) (I)');
    $this->aLinhas[self::LINHA_EXCETO_INTRA] = $oLinhaExcetoIntra;
    $this->construirValores(false, $oLinhaExcetoIntra);

    $oLinhaIntra = new Linha();
    $oLinhaIntra->setDescricao('RESTOS A PAGAR (INTRA-ORÇAMENTÁRIOS) (II)');
    $this->aLinhas[self::LINHA_INTRA] = $oLinhaIntra;
    $this->construirValores(true, $oLinhaIntra);

    $oLinhaTotal = new Linha();
    $oLinhaTotal->setDescricao('TOTAL (III) = (I + II)');
    $this->aLinhas[self::LINHA_TOTAL_GERAL] = $oLinhaTotal;
    $oLinhaTotal->setValorProcessadoEmExerciciosAnteriores($oLinhaExcetoIntra->getValorProcessadoEmExerciciosAnteriores() + $oLinhaIntra->getValorProcessadoEmExerciciosAnteriores());
    $oLinhaTotal->setValorProcessadoNoExercicioAnterior($oLinhaExcetoIntra->getValorProcessadoNoExercicioAnterior() + $oLinhaIntra->getValorProcessadoNoExercicioAnterior());
    $oLinhaTotal->setValorPagoProcessado($oLinhaExcetoIntra->getValorPagoProcessado() + $oLinhaIntra->getValorPagoProcessado());
    $oLinhaTotal->setValorCanceladoProcessado($oLinhaExcetoIntra->getValorCanceladoProcessado() + $oLinhaIntra->getValorCanceladoProcessado());
    $oLinhaTotal->setSaldoProcessado($oLinhaExcetoIntra->getSaldoProcessado() + $oLinhaIntra->getSaldoProcessado());
    $oLinhaTotal->setValorNaoProcessadoEmExerciciosAnteriores($oLinhaExcetoIntra->getValorNaoProcessadoEmExerciciosAnteriores() + $oLinhaIntra->getValorNaoProcessadoEmExerciciosAnteriores());
    $oLinhaTotal->setValorNaoProcessadoNoExercicioAnterior($oLinhaExcetoIntra->getValorNaoProcessadoNoExercicioAnterior() + $oLinhaIntra->getValorNaoProcessadoNoExercicioAnterior());
    $oLinhaTotal->setValorLiquidadoNaoProcessado($oLinhaExcetoIntra->getValorLiquidadoNaoProcessado() + $oLinhaIntra->getValorLiquidadoNaoProcessado());
    $oLinhaTotal->setValorPagoNaoProcessado($oLinhaExcetoIntra->getValorPagoNaoProcessado() + $oLinhaIntra->getValorPagoNaoProcessado());
    $oLinhaTotal->setValorCanceladoNaoProcessado($oLinhaExcetoIntra->getValorCanceladoNaoProcessado() + $oLinhaIntra->getValorCanceladoNaoProcessado());
    $oLinhaTotal->setSaldoNaoProcessado($oLinhaExcetoIntra->getSaldoNaoProcessado() + $oLinhaIntra->getSaldoNaoProcessado());
    $oLinhaTotal->setSaldoTotal($oLinhaTotal->getSaldoProcessado() + $oLinhaTotal->getSaldoNaoProcessado());

    $oLinhaTotalIntra = clone $oLinhaIntra;
    $oLinhaTotalIntra->setDescricao('TOTAL');
    $this->aLinhas[self::LINHA_TOTAL_INTRA] = $oLinhaTotalIntra;
  }

  /**
   * @return bool|resource
   */
  private function getRestosAPagarIntraOrcamentarios() {
    return $this->queryBuilder("substr(o56_elemento,4,2) = '91'");
  }

  /**
   * @return bool|resource
   */
  private function getRestosAPagarExcetoIntraOrcamentarios() {
    return $this->queryBuilder("substr(o56_elemento,4,2) != '91'");
  }

  /**
   * Constrói e executa a busca conforme informado no parâmetro where
   * @param $sWhere
   * @return bool|resource
   * @throws \DBException
   * @throws \ParameterException
   */
  private function queryBuilder($sWhere) {

    if (empty($sWhere)) {
      throw new \ParameterException(_M(self::MENSAGENS . 'parameter_query_builder'));
    }

    $iDiaFinal = $this->oPeriodo->getDiaFinal();
    if ((int)$this->oPeriodo->getCodigo() === \Periodo::PRIMEIRO_BIMESTRE) {
      $iDiaFinal = cal_days_in_month(CAL_GREGORIAN, \DBDate::FEVEREIRO, $this->iAno);
    }

    $oDataInicial = new \DBDate("{$this->iAno}-01-01");
    $oDataFinal   = new \DBDate("{$this->iAno}-{$this->oPeriodo->getMesFinal()}-{$iDiaFinal}");
    $sInstituicoes = " empempenho.e60_instit in ({$this->sCodigosInstituicoes}) ";
    $oDaoRestos = new \cl_empresto();
    $sSqlBuscaRP = $oDaoRestos->sql_rp_novo($this->iAno, $sInstituicoes, $oDataInicial->getDate(), $oDataFinal->getDate());

    $sSQLPeriodo  = " select e60_instit,";
    $sSQLPeriodo .= "        nomeinst,";
    $sSQLPeriodo .= "        o58_orgao,";
    $sSQLPeriodo .= "        o40_descr,";
    $sSQLPeriodo .= "        db21_tipoinstit,";
    $sSQLPeriodo .= "        sum(case when e60_anousu < ({$this->iAno} - 1)";
    $sSQLPeriodo .= "              then e91_vlrliq - e91_vlrpag	";
    $sSQLPeriodo .= "            else 0 end ) as inscricao_ant,";
    $sSQLPeriodo .= "        sum(case when e60_anousu = ({$this->iAno} - 1) ";
    $sSQLPeriodo .= "              then e91_vlrliq - e91_vlrpag";
    $sSQLPeriodo .= "            else 0 end ) as  valor_processado,";
    $sSQLPeriodo .= "        sum(case when e60_anousu < ({$this->iAno} - 1)";
    $sSQLPeriodo .= "              then e91_vlremp - e91_vlranu - e91_vlrliq";
    $sSQLPeriodo .= "            else 0 end ) as  valor_nao_processado_ant,";
    $sSQLPeriodo .= "        sum(case when e60_anousu = ({$this->iAno} - 1)";
    $sSQLPeriodo .= "              then e91_vlremp - e91_vlranu - e91_vlrliq";
    $sSQLPeriodo .= "            else 0 end ) as  valor_nao_processado,";
    $sSQLPeriodo .= "        sum(coalesce(e91_vlremp,0)) as e91_vlremp,";
    $sSQLPeriodo .= "        sum(coalesce(e91_vlranu,0)) as e91_vlranu,";
    $sSQLPeriodo .= "        sum(coalesce(e91_vlrliq,0)) as e91_vlrliq,";
    $sSQLPeriodo .= "        sum(coalesce(e91_vlrpag,0)) as e91_vlrpag,";
    $sSQLPeriodo .= "        sum(coalesce(vlranu,0)) as vlranu,";
    $sSQLPeriodo .= "        sum(coalesce(vlranuliq,0)) as vlranuliq,";
    $sSQLPeriodo .= "        sum(coalesce(vlranuliqnaoproc,0)) as vlranuliqnaoproc,";
    $sSQLPeriodo .= "        sum(coalesce(vlrliq,0)) as vlrliq,";
    $sSQLPeriodo .= "        sum(coalesce(vlrpag,0)) as vlrpag,";
    $sSQLPeriodo .= "        sum(coalesce(vlrpagnproc,0)) as vlrpagnproc";
    $sSQLPeriodo .= "   from ({$sSqlBuscaRP}) as x";
    $sSQLPeriodo .= "  where {$sWhere} ";
    $sSQLPeriodo .= "  group by e60_instit,";
    $sSQLPeriodo .= "           nomeinst,";
    $sSQLPeriodo .= "           db21_tipoinstit,";
    $sSQLPeriodo .= "           o58_orgao,";
    $sSQLPeriodo .= "           o40_descr";

    $sSqlAgrupaResultado = "
      select db21_tipoinstit     as tipo_instituicao
             ,sum(inscricao_ant) as inscricao_ant
             ,sum(valor_processado)         as valor_processado
             ,sum(valor_nao_processado_ant) as valor_nao_processado_ant
             ,sum(valor_nao_processado)     as valor_nao_processado
             ,sum(e91_vlremp) as e91_vlremp
             ,sum(e91_vlranu) as e91_vlranu
             ,sum(e91_vlrliq) as e91_vlrliq
             ,sum(e91_vlrpag) as e91_vlrpag
             ,sum(vlranu)     as vlranu
             ,sum(vlranuliq)  as vlranuliq
             ,sum(vlranuliqnaoproc) as vlranuliqnaoproc
             ,sum(vlrliq) as vlrliq
             ,sum(vlrpag) as vlrpag
             ,sum(vlrpagnproc) as vlrpagnproc
             ,sum((inscricao_ant + valor_processado) - vlrpag - vlranuliq) as rp_ex_ant_saldo
             ,sum((valor_nao_processado + valor_nao_processado_ant) - vlranuliqnaoproc - vlrpagnproc) as rp_n_proc_saldo
             ,sum(((inscricao_ant + valor_processado) - vlrpag - vlranuliq) + ((valor_nao_processado + valor_nao_processado_ant) - vlranuliqnaoproc - vlrpagnproc)) as saldo_total
        from ($sSQLPeriodo) as x
       group by db21_tipoinstit order by db21_tipoinstit;
    ";

    $rsExecutaBusca = db_query($sSqlAgrupaResultado);
    if (!$rsExecutaBusca) {
      throw new \DBException(_M(self::MENSAGENS . 'erro_consulta_restos_a_pagar'));
    }

    return $rsExecutaBusca;
  }

  /**
   * Retorna os dados de forma simplificada para o relatório:
   * - Anexo XIV - Demonstrativo Simplificado do Relatório Resumido da Execução Orçamentária
   *
   * @return \stdClass[]
   * @throws \Exception
   */
  public function getDadosSimplificado() {

    $this->processar();
    $aLinhasPorPoder = array();

    foreach ($this->aLinhas as $iIndice => $oLinhaSintetica) {

      if (in_array($iIndice, array(3,4))) {
        continue;
      }

      foreach ($oLinhaSintetica->getLinhas() as $oLinhaAnalitica) {

        switch ($oLinhaAnalitica->getTipo()) {

          case LinhaAnexoVII::PODER_EXECUTIVO:
            $sDescricao = "Poder Executivo";
            break;

          case LinhaAnexoVII::PODER_LEGISLATIVO:
            $sDescricao = "Poder Legislativo";
            break;

          case LinhaAnexoVII::PODER_JUDICIARIO:
            $sDescricao = "Poder Judiciário";
            break;
          case LinhaAnexoVII::MINISTERIO_PUBLICO:
            $sDescricao = "Ministério Público";
            break;
          default:
            throw new \Exception("O tipo de linha {$oLinhaAnalitica->getTipo()} não foi encontrado.");
        }


        if ( empty($aLinhasPorPoder[$oLinhaAnalitica->getTipo()] )) {
          $aLinhasPorPoder[$oLinhaAnalitica->getTipo()] = $this->buildObject($sDescricao);
        }
        $this->buildLinha($aLinhasPorPoder[$oLinhaAnalitica->getTipo()], $oLinhaAnalitica);
      }
    }

    $aLinhaImpressao = array();
    $oStdProcessado = new \stdClass();
    $oStdProcessado->sDescricao           = 'RESTOS À PAGAR PROCESSADOS';
    $oStdProcessado->nProcessadoInscrito  = 0;
    $oStdProcessado->nProcessadoCancelado = 0;
    $oStdProcessado->nProcessadoPago      = 0;
    $oStdProcessado->nProcessadoPagar     = 0;

    $oStdNaoProcessado = new \stdClass();
    $oStdNaoProcessado->sDescricao              = 'RESTOS À PAGAR NÃO PROCESSADOS';
    $oStdNaoProcessado->nNaoProcessadoInscrito  = 0;
    $oStdNaoProcessado->nNaoProcessadoCancelado = 0;
    $oStdNaoProcessado->nNaoProcessadoPago      = 0;
    $oStdNaoProcessado->nNaoProcessadoPagar     = 0;
    foreach ($aLinhasPorPoder as $oStdLinha) {

      $oStdProcessado->nProcessadoInscrito  += $oStdLinha->nProcessadoInscrito;
      $oStdProcessado->nProcessadoCancelado += $oStdLinha->nProcessadoCancelado;
      $oStdProcessado->nProcessadoPago      += $oStdLinha->nProcessadoPago;
      $oStdProcessado->nProcessadoPagar     += $oStdLinha->nProcessadoPagar;

      $oStdNaoProcessado->nNaoProcessadoInscrito  += $oStdLinha->nNaoProcessadoInscrito;
      $oStdNaoProcessado->nNaoProcessadoCancelado += $oStdLinha->nNaoProcessadoCancelado;
      $oStdNaoProcessado->nNaoProcessadoPago      += $oStdLinha->nNaoProcessadoPago;
      $oStdNaoProcessado->nNaoProcessadoPagar     += $oStdLinha->nNaoProcessadoPagar;
    }

    $aLinhaImpressao['rp-processado']     = $oStdProcessado;
    $aLinhaImpressao['rp-nao-processado'] = $oStdNaoProcessado;
    $aLinhaImpressao['linhas'] = $aLinhasPorPoder;

    return $aLinhaImpressao;
  }

  /**
   * @param \stdClass     $oStdLinhaImpressao
   * @param LinhaAnexoVII $oLinhaAnalitica
   */
  private function buildLinha(&$oStdLinhaImpressao, $oLinhaAnalitica) {

    $oStdLinhaImpressao->nProcessadoInscrito     += $oLinhaAnalitica->getValorProcessadoEmExerciciosAnteriores() + $oLinhaAnalitica->getValorProcessadoNoExercicioAnterior();
    $oStdLinhaImpressao->nProcessadoCancelado    += $oLinhaAnalitica->getValorCanceladoProcessado();
    $oStdLinhaImpressao->nProcessadoPago         += $oLinhaAnalitica->getValorPagoProcessado();
    $oStdLinhaImpressao->nProcessadoPagar        += $oLinhaAnalitica->getSaldoProcessado();
    $oStdLinhaImpressao->nNaoProcessadoInscrito  += $oLinhaAnalitica->getValorNaoProcessadoEmExerciciosAnteriores() + $oLinhaAnalitica->getValorNaoProcessadoNoExercicioAnterior();
    $oStdLinhaImpressao->nNaoProcessadoCancelado += $oLinhaAnalitica->getValorCanceladoNaoProcessado();
    $oStdLinhaImpressao->nNaoProcessadoPago      += $oLinhaAnalitica->getValorPagoNaoProcessado();
    $oStdLinhaImpressao->nNaoProcessadoPagar     += $oLinhaAnalitica->getSaldoNaoProcessado();
  }

  /**
   * @param $sDescricao
   * @return \stdClass
   */
  private function buildObject($sDescricao) {

    $oStdLinha                          = new \stdClass();
    $oStdLinha->sDescricao              = $sDescricao;
    $oStdLinha->nProcessadoInscrito     = 0;
    $oStdLinha->nProcessadoCancelado    = 0;
    $oStdLinha->nProcessadoPago         = 0;
    $oStdLinha->nProcessadoPagar        = 0;
    $oStdLinha->nNaoProcessadoInscrito  = 0;
    $oStdLinha->nNaoProcessadoCancelado = 0;
    $oStdLinha->nNaoProcessadoPago      = 0;
    $oStdLinha->nNaoProcessadoPagar     = 0;
    return $oStdLinha;
  }
}
