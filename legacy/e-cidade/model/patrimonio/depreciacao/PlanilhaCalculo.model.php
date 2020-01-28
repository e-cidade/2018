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

 /**
  * Classe para controle dos calculos mensais de depreciação/Reavaliação dos Bens
  * @author DBseller
  * @package patrimonio
  * @subpackage depreciacao
  * @version $Revision: 1.29 $
  *
  */
class PlanilhaCalculo {

  /**
   * Código da planilha de calculo
   * @var integer
   */
  protected $iPlanilha;

  /**
   * Coleção de cálculos realizados na planilha
   * @var array
   */
  protected $aCalculos = array();

  /**
   * Mês do cálculo
   * @var integer
   */
  protected $iMes;

  /**
   * Ano do cálculo
   * @var integer
   */
  protected $iAno;

  /**
   * data de inclusão do cálculo
   * @var string
   */
  protected $dtDataCalculo;

  /**
   * Código do usuário que realizadou a inclusão da planilha
   * @var integer
   */
  protected $iUsuario;

  /**
   * Instituição que gerou a planilha
   * @var integer
   */
  protected $iInstituicao;

  /**
   * Tipo do calculo da planilha 1  - Depreciação 2 - Reavaliação
   * @var integer
   */
  protected $iTipoCalculo;

  /**
   * planilha foi processada
   * @var boolean
   */
  protected $lProcessado;

  /**
   * Tipo do processamento da planilha. 1 Automatico 2 - Manual
   * @var integer
   */
  protected $iTipoProcessamento;

  /**
   * Planilha está ativa
   * @var Boolean
   */
  protected $lAtivo;

  /**
   * Data do inicio da depreciação
   * @var string
   */
  protected $dtInicioDepreciacao;

  /**
   * Ano que não possui algum mês processado
   * @var integer
   */
  protected $iAnoNaoProcessado;

  /**
   * Identifica se a rotina de processamento é retroativa
   * @var boolean
   */
  protected $lProcessamentoRetroativo = false;

  /**
   * String com os códigos dos bem a ser processado retroativamente.
   * Ex.: 1,2,3
   * @var string
   */
  protected $sCodigosBensProcessamentoRetroativo;

  /**
   * Carrega dos dados da planilha, caso  código da planilha existir no sistema
   */
  function __construct($iPlanilha = null) {

    if (!empty($iPlanilha)) {

      $oDaoBensHistoricoCalculo = new cl_benshistoricocalculo();
      $sSqlDadosPlanilha        = $oDaoBensHistoricoCalculo->sql_query_file($iPlanilha);
      $rsDadosPlanilha          = $oDaoBensHistoricoCalculo->sql_record($sSqlDadosPlanilha);
      if ($oDaoBensHistoricoCalculo->numrows) {

        $oDadosPlanilha  = db_utils::fieldsmemory($rsDadosPlanilha, 0);
        $this->iPlanilha = $iPlanilha;
        $this->setAno($oDadosPlanilha->t57_ano);
        $this->setAtivo($oDadosPlanilha->t57_ativo == "t" ? true : false);
        $this->setProcessado($oDadosPlanilha->t57_processado == "t" ? true : false);
        $this->setDataCalculo($oDadosPlanilha->t57_datacalculo);
        $this->setInstituicao($oDadosPlanilha->t57_instituicao);
        $this->setMes($oDadosPlanilha->t57_mes);
        $this->setTipoCalculo($oDadosPlanilha->t57_tipocalculo);
        $this->setTipoProcessamento($oDadosPlanilha->t57_tipoprocessamento);
        $this->setUsuario($oDadosPlanilha->t57_usuario);
        unset($oDadosPlanilha);
        unset($rsDadosPlanilha);
      }
      unset($oDaoBensHistoricoCalculo);
    }
  }
  /**
   * Retorna todos os calculos realizados pela planilha
   * @return CalculoBem[]
   */
  public function getCalculos() {

    if (count($this->aCalculos) == 0 && !empty($this->iPlanilha)) {

      $oDaoBensCalculo = new cl_benshistoricocalculobem();
      $sWhereCalculos  = "t58_benshistoricocalculo = {$this->iPlanilha}";
      $sSqlBensCalculo = $oDaoBensCalculo->sql_query(null, "benshistoricocalculobem.*, bens.*, bensdepreciacao.*", "t58_sequencial", $sWhereCalculos);
      $rsBensCaculo    = $oDaoBensCalculo->sql_record($sSqlBensCalculo);

      if ($oDaoBensCalculo->numrows > 0) {

        for ($iCalculo = 0; $iCalculo < $oDaoBensCalculo->numrows; $iCalculo++) {

          $oDadosCalculo = db_utils::fieldsMemory($rsBensCaculo, $iCalculo);

          $oCalculo = new CalculoBem();
          $oCalculo->setHistoricoCalculo($oDadosCalculo->t58_benshistoricocalculo);
          $oCalculo->setPercentualDepreciado($oDadosCalculo->t58_percentualdepreciado);
          $oCalculo->setSequencial($oDadosCalculo->t58_sequencial);
          $oCalculo->setTipoDepreciacao($oDadosCalculo->t58_benstipodepreciacao);
          $oCalculo->setValorAnterior($oDadosCalculo->t58_valoranterior);
          $oCalculo->setValorAtual($oDadosCalculo->t58_valoratual);
          $oCalculo->setValorCalculado($oDadosCalculo->t58_valorcalculado);
          $oCalculo->setValorResidual($oDadosCalculo->t58_valorresidual);
          $oCalculo->setValorResidualAnterior($oDadosCalculo->t58_valorresidualanterior);

          $oBem = new Bem();
          $oBem->setCodigoBem($oDadosCalculo->t52_bem);
          $oBem->setCodigoBemDepreciacao($oDadosCalculo->t44_sequencial);
          $oBem->setTipoDepreciacao( BemTipoDepreciacaoRepository::getPorCodigo($oDadosCalculo->t44_benstipodepreciacao) );
          $oBem->setTipoAquisicao( BemTipoAquisicaoRepository::getPorCodigo($oDadosCalculo->t44_benstipoaquisicao) );
          $oBem->setClassificacao(BemClassificacaoRepository::getPorCodigo($oDadosCalculo->t52_codcla));
          $oBem->setVidaUtil($oDadosCalculo->t44_vidautil);
          $oBem->setValorAquisicao($oDadosCalculo->t52_valaqu);
          $oBem->setValorResidual($oDadosCalculo->t44_valorresidual);
          $oBem->setValorDepreciavel($oDadosCalculo->t44_valoratual);
          $oBem->setVidaUtil($oDadosCalculo->t44_vidautil);
          $oBem->setDescricao($oDadosCalculo->t52_descr);

          $oCalculo->setBem($oBem);

          array_push($this->aCalculos, $oCalculo);
        }
      }

      unset($oDaoBensCalculo);
      unset($rsBensCaculo);
    }
    return $this->aCalculos;
  }

  /**
   * Retorna a data em que a planilha foi calculada
   * @return string
   */
  public function getDataCalculo() {
    return $this->dtDataCalculo;
  }

  /**
   * Define a data de calculo da planilha
   * @param string $dtDataCalculo
   */
  public function setDataCalculo($dtDataCalculo) {
    $this->dtDataCalculo = $dtDataCalculo;
  }

  /**
   * retorna o ano de processamento da planilha
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Define o ano de processamento da planilha
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna a instituição da planilha
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Define a instituição da planilha
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna o mes de processamento da planilha
   * @return integer
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * Define o mes de processamento da planilha
   * @param integer $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * Retorna o codigo gerado para a planilha
   * @return integer
   */
  public function getPlanilha() {
    return $this->iPlanilha;
  }

  /**
   * Retorna o tipo de calculo que será realizado pela planilha
   * @return integer
   */
  public function getTipoCalculo() {
    return $this->iTipoCalculo;
  }

  /**
   * Define o tipo de calculo realizado pela planilha.
   * Os tipos de cálculos aceitos são: 1 - Depreciação, 2 - Reavaliação
   * @param integer $iTipoCalculo
   */
  public function setTipoCalculo($iTipoCalculo) {
    $this->iTipoCalculo = $iTipoCalculo;
  }

  /**
   * Retorna o tipo de processamento da planilha
   * @return integer
   */
  public function getTipoProcessamento() {
    return $this->iTipoProcessamento;
  }

  /**
   * Define o tipo de processamento na planilha.
   * Os tipos de processamento válidos são: 1 Automatico, 2 manual.
   * Esse método interfere como a planilha é processada.
   * @param integer $iTipoProcessamento
   */
  public function setTipoProcessamento($iTipoProcessamento) {
    $this->iTipoProcessamento = $iTipoProcessamento;
  }

  /**
   * Retorna o usuário que processou a planilha
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }

  /**
   * Define o usuário que processou a planilha
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Retorna se a planilha está ativa ou nao
   * @return boolean
   */
  public function isAtiva() {
    return $this->lAtivo;
  }
  /**
   * Define se a planilha está ativa
   * @param Boolean $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Retorna se a planilha está processada
   * @return  boolean $lProcessado
   */
  public function isProcessado() {
    return $this->lProcessado;
  }

  /**
   * define se a planilha está processada
   * @return  boolean $lProcessado
   */
  protected function setProcessado($lProcessado) {
    $this->lProcessado = $lProcessado;
  }

  /**
   * Retorna a variável em que a depreciação foi implementada
   * @return string
   */
  public function getDataInicioDepreciacao() {
    return $this->dtInicioDepreciacao;
  }

  /**
   * Retorna o ano que a depreciação teve meses não calculado
   * @return integer
   */
  public function getAnoNaoProcessado() {
    return $this->iAnoNaoProcessado;
  }

  /**
   * Adiciona um calculo a planilha.
   * @param CalculoBem $oCalculoBem Calculo do Bem
   */
  public function adicionarCalculo(CalculoBem $oCalculoBem) {

    /**
     * verificamos se a já existe um calculo para o bem.
     * apenas pode existir um calculo por bem dentro de uma planilha
     */
    foreach ($this->aCalculos as $oCalculo) {

      if ($oCalculo->getBem()->getCodigoBem() == $oCalculoBem->getBem()->getCodigoBem()) {

        $oParms = new stdClass();
        $oParms->sDescricao = $oCalculoBem->getBem()->getDescricao();
        throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.calculo_ja_adicionado', $oParms));
        //throw new Exception("Já existe um calculo adicionado para o bem {$oCalculoBem->getBem()->getDescricao()}");
      }
    }
    array_push($this->aCalculos, $oCalculoBem);
  }

  /**
   * Retorna uma coleção de bens, que podem ser processados pela planilha
   * Esse método retorna os bens conforme o tipo de calculo e tipo de processamento da planilha.
   * Caso o tipo de calculo da planilha for 1 - Depreciacao. o método levara em conta o tipo de processamento
   * da planilha:
   * Quanto for 1 - Automatico, apenas retornara os bens que possuirem as depreciações do tipo 1, 2, 3 e as
   * depreciaçoes cadastradas pelo usuário.
   * Quando for 2 - Manual, apenas retornara bens do tipo de depreciaçao 5 - Manual.
   * @throws Exception
   * @return Array Coleção de Bem
   */
  public function getBensPorTipoDeProcessamento() {

    if (empty($this->iTipoCalculo)) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.informe_calculo'));
    }

    if (empty($this->iTipoProcessamento)) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.informe_processamento'));
    }
    $iUltimoDiaCompetencia = cal_days_in_month(CAL_GREGORIAN, $this->getMes(), $this->getAno());
    $dtReferenciaMes       = "{$this->getAno()}-{$this->getMes()}-{$iUltimoDiaCompetencia}";
    $iInstituicao          = db_getsession("DB_instit");

    $sWhere  = " t52_instit = {$iInstituicao}";
    $sWhere .= " and (t55_baixa is null or t55_baixa >= cast('{$dtReferenciaMes}' as date))";
    $sWhere .= " and (t52_dtaqu <= cast('{$dtReferenciaMes}' as date)";
    $sWhere .= " or exists (select 1
                              from inventariobem
                                   inner join inventario on inventario.t75_sequencial = inventariobem.t77_inventario
                             where inventariobem.t77_bens       = t52_bem
                               and inventario.t75_situacao      = 3
                               and inventario.t75_dataabertura <= cast('{$dtReferenciaMes}' as date)))";

    if ($this->isProcessamentoRetroativo()) {
      $sWhere .= " and t44_bens in ({$this->getCodigoBensProcessamentoRetroativo()})";
    }

    /**
     * processamendo de depreciacao
     */
    if ($this->iTipoCalculo == 1) {

      $sWhere .= " and t44_valoratual > 0 ";
      if ($this->iTipoProcessamento == 1) {
        $sWhere .= " and t44_benstipodepreciacao not in (4,5) ";
      } else {
        $sWhere .= " and t44_benstipodepreciacao in (5) ";
      }
    }
    $aListaBens          = array();
    $oDaoBensDepreciacao = db_utils::getDao("bensdepreciacao");
    $sSqlBens            = $oDaoBensDepreciacao->sql_query_bem(null, "distinct bens.*, bensdepreciacao.*", "1", $sWhere);
    $rsBens              = $oDaoBensDepreciacao->sql_record($sSqlBens);

    if ($oDaoBensDepreciacao->numrows > 0) {

      for ($iBem = 0; $iBem < $oDaoBensDepreciacao->numrows; $iBem++) {

        $oDadosBem = db_utils::fieldsMemory($rsBens, $iBem);

        $oBem = new Bem();
        $oBem->setCodigoBem($oDadosBem->t52_bem);
        $oBem->setCodigoBemDepreciacao($oDadosBem->t44_sequencial);
        $oBem->setTipoDepreciacao( BemTipoDepreciacaoRepository::getPorCodigo($oDadosBem->t44_benstipodepreciacao) );
        $oBem->setTipoAquisicao( BemTipoAquisicaoRepository::getPorCodigo($oDadosBem->t44_benstipoaquisicao) );
        $oBem->setVidaUtil($oDadosBem->t44_vidautil);
        $oBem->setValorAquisicao($oDadosBem->t52_valaqu);
        $oBem->setValorResidual($oDadosBem->t44_valorresidual);
        $oBem->setValorDepreciavel($oDadosBem->t44_valoratual);
        $oBem->setVidaUtil($oDadosBem->t44_vidautil);
        $oBem->setDescricao($oDadosBem->t52_descr);

        array_push($aListaBens, $oBem);
      }
    }

    return $aListaBens;
  }

  /**
   * Retorna o mes disponivel para processamento da planilha.
   * @param integer $iTipoCalculo Tipo do calculo que ser verificado
   * @throws Exception
   * @return integer retorna po mes de processamento. caso nao possua nenhum mes disponivel para processamento,
   * retorna 0;
   */
  public function getMesDisponivelParaProcessamento($iTipoCalculo) {

    $iAnoSessao            = $this->getAno();
    $iInstituicao          = db_getsession("DB_instit");
    $iMesParaProcessamento = 0;
    $oDaoHistoricoCalculo  = db_utils::getDao("benshistoricocalculo");
    /**
     * @todo Modificar o parametro para os parametros da instituicao do patrimonio
     */
    if (!$this->hasParametroDepreciacaoHabilitado()) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.depreciacao_nao_configurada_para_instituicao'));
    }

    $dtInicioDepreciacao = $this->getDataInicioDepreciacao();
    $aDataImplantacao    = explode("-", $dtInicioDepreciacao);
    $iMesImplantacao     = $aDataImplantacao[1];
    $iAnoImplantacao     = $aDataImplantacao[0];

    $sWhere  = "     t57_tipocalculo       = {$iTipoCalculo} ";
    $sWhere .= " and t57_tipoprocessamento = {$this->iTipoProcessamento} ";
    $sWhere .= " and t57_processado  is true ";
    $sWhere .= " and t57_ativo       is true ";
    $sWhere .= " and t57_ano         = {$iAnoSessao} ";
    $sWhere .= " and t57_instituicao = {$iInstituicao}";

    /**
     * Pesquisamos se existe algum mes processado
     * Caso já exista algum mes, devemos retornar o proximo mes.
     */
    $sSqlCalculosProcessadas = $oDaoHistoricoCalculo->sql_query_file(null,"max(t57_mes) as mes", null, $sWhere);
    $rsCalculosProcessados   = $oDaoHistoricoCalculo->sql_record($sSqlCalculosProcessadas);
    $iMesParaProcessamento   = $iMesImplantacao;

    if ($oDaoHistoricoCalculo->numrows > 0) {

      $iMesParaProcessamento = db_utils::fieldsMemory($rsCalculosProcessados, 0)->mes;

      if (empty($iMesParaProcessamento)) {

        $iMesParaProcessamento += 1;
        if ($iAnoImplantacao == $iAnoSessao) {
          $iMesParaProcessamento = $iMesImplantacao;
        }

        /**
         * Processando tipo de depreciação manual
         * Caso não haja depreciações processadas, buscar o primeiro mês que haja bem adquirido
         */
        if ($this->iTipoProcessamento == 2) {

          $oDaoBens = db_utils::getDao("bens");
          $sWhere   = " t44_benstipodepreciacao = 5";
          $sOrder   = " t52_dtaqu asc limit 1";
          $sSqlMes  = $oDaoBens->sql_query_tipodepreciacao(null, "t52_dtaqu as data", $sOrder, $sWhere);
          $rsMes    = $oDaoBens->sql_record($sSqlMes);

          if($oDaoBens->numrows == 0) {
            throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.nao_existem_bens_para_depreciacao'));
          }

          $dtAquisicaoBem        = db_utils::fieldsMemory($rsMes, 0)->data;
          $aDataAquisicao        = explode("-",$dtAquisicaoBem);
          $iMesParaProcessamento = $aDataAquisicao[1];

          if($iAnoSessao < $aDataAquisicao[0]) {

            $oParms = new stdClass();
            $oParms-> iAnoSessao = $iAnoSessao;
            $oParms->aDataAquisicao = $aDataAquisicao;
            throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.sem_bens_para_depreciacao_no_ano', $oParms));
            //throw new Exception("Não existem bens para serem depreciados no ano {$iAnoSessao}.\n O primeiro bem adquirido foi cadastrado em {$aDataAquisicao[0]} ");
          }

          /**
           * Caso o ano da sessão for maior que o ano de aquisição, setamos o mês para janeiro do ano corrente
           */
          if ($iAnoSessao > $aDataAquisicao[0]) {
            $iMesParaProcessamento = 1;
          }
        }


      } else {
        $iMesParaProcessamento += 1;
      }

      if ($iMesParaProcessamento > 12) {
        $iMesParaProcessamento = 0;
      }


    }
    return $iMesParaProcessamento;
  }


  /**
   * Processa os dados do calculo de Depreciação
   */
  public function processarCalculo() {

    $iProximoMesProcessar = $this->getMesDisponivelParaProcessamento($this->getTipoCalculo());

    if ($this->getMes() != $iProximoMesProcessar && !$this->isProcessamentoRetroativo()) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.mes_invalido'));
    }
    /**
		 * Quando for processamento automatico.
     */
    if ($this->getTipoProcessamento() == 1) {

      /**
       * Tratamos o processamento da depreciacao
       */
      if ($this->getTipoCalculo() == 1) {

        /*
         * caso nao existam bens a serem depreciados em um determinado mes/ano retornamos false
         */
        $aBensParaProcessar = $this->getBensPorTipoDeProcessamento();
        if (count($aBensParaProcessar) == 0) {
          return false;
        }

        foreach ($aBensParaProcessar as $oBem) {

          $oCalculoBem = new CalculoBem();
          $oCalculoBem->setBem($oBem);
          $oCalculoBem->calcular();
          $this->adicionarCalculo($oCalculoBem);
        }
      }

    } else {

      /**
       * Processamento manual
       */
      if (count($this->aCalculos) == 0) {
        throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_sem_calculos_informados'));
      }
      /**
       * Apenas percorremos os calculos adicionados e validamos o do calculo (não pode ser menor que zero.)
       */
      foreach ($this->aCalculos as $oCalculo) {

        if ($oCalculo->getValorAtual() < 0) {

          $oParms = new stdClass();
          $oParms->sDescricao = $oCalculo->getBem()->getDescricao();
          throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.calculo_valor_inconsistente', $oParms));
          //throw new Exception("Calculo para o bem {$oCalculo->getBem()->getDescricao()} com valor inconsistente.");
        }
      }
    }
    $this->setProcessado(true);
    $this->setAtivo(true);
    return true;
  }

  /**
   * persiste os dados da planilha do banco de dados
   *
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.nao_existe_transacao'));
    }
    if (count($this->getCalculos()) == 0) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_sem_calculos'));
    }

    if (empty($this->iMes)) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_sem_mes_de_processamento'));
    }

    if (empty($this->iAno)) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_sem_ano_de_processamento'));
    }
    $oDaoBensHistoricoCalculo                        = db_utils::getDao("benshistoricocalculo");
    $oDaoBensHistoricoCalculo->t57_ano               = $this->getAno();
    $oDaoBensHistoricoCalculo->t57_mes               = $this->getMes();
    $oDaoBensHistoricoCalculo->t57_ativo             = $this->isAtiva()?"true":"false";
    $oDaoBensHistoricoCalculo->t57_datacalculo       = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoBensHistoricoCalculo->t57_instituicao       = db_getsession("DB_instit");
    $oDaoBensHistoricoCalculo->t57_processado        = $this->isProcessado()?"true":"false";
    $oDaoBensHistoricoCalculo->t57_tipocalculo       = $this->getTipoCalculo();
    $oDaoBensHistoricoCalculo->t57_tipoprocessamento = $this->getTipoProcessamento();
    $oDaoBensHistoricoCalculo->t57_usuario           = db_getsession("DB_id_usuario");
    if (empty($this->iPlanilha)) {

      $oDaoBensHistoricoCalculo->incluir(null);
      $this->iPlanilha = $oDaoBensHistoricoCalculo->t57_sequencial;

      /**
       * Salvamos os dados dos calculos na planilha
       */
      $aCalculos = $this->getCalculos();
      foreach ($aCalculos as $oCalculo) {
        $oCalculo->salvar($this->iPlanilha);
      }
    } else {

      $oDaoBensHistoricoCalculo->t57_sequencial = $this->iPlanilha;
      $oDaoBensHistoricoCalculo->alterar($this->iPlanilha);
    }

    if ($oDaoBensHistoricoCalculo->erro_status == 0) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_sem_ano_de_processamento',$oDaoBensHistoricoCalculo));
    }
  }

  /**
   * Cancela a planilha
   * Gera uma nova planilha, com a situação de processamento = false,
   * os demais dados são os mesmos da planilha atual.
   * os calculos sao invertidos.
   * o valor atual dos calculos da planilha nova, passa a ser o valor anterior dos
   * calculos da planilha que está sendo cancelada.
   */
  public function cancelar() {

    if (empty($this->iPlanilha)) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_nao_salva'));
    }

    if (!$this->isProcessado()) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_nao_cancelada'));
    }
   if (!$this->isAtiva()) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.planilha_ja_cancelada'));
    }
    /**
     * verificamos se existe algum mes posterior processado
     */
    $iProximoMesProcessar = $this->getMesDisponivelParaProcessamento($this->getTipoCalculo());
    $iMesPlanilha         = $this->getMes()+1;
    if ($iMesPlanilha > 12) {
      $iMesPlanilha = 0;
    }
    if ($iProximoMesProcessar > $iMesPlanilha) {
      throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.processamentos_anteriores_planilha_nao_cancelada'));
    }

    /**
     * Criamos uma nova planilha, com nao sendo processada
     */
    $oNovaPlanilha = clone $this;
    $oNovaPlanilha->setProcessado(false);
    $oNovaPlanilha->setAtivo(true);
    $aCalculos = $this->getCalculos();
    /**
     * Invertemos o valor de cada calculo na nova planilha.
     *
     */
    foreach ($aCalculos as $oCalculoAnterior) {

      $oNovoCalculo = new CalculoBem();
      $oNovoCalculo->setBem($oCalculoAnterior->getBem());
      $oNovoCalculo->setPercentualDepreciado($oCalculoAnterior->getPercentualDepreciado());
      $oNovoCalculo->setTipoDepreciacao($oCalculoAnterior->getTipoDepreciacao());
      $oNovoCalculo->setValorAnterior($oCalculoAnterior->getValorAtual());
      $oNovoCalculo->setValorAtual($oCalculoAnterior->getValorAnterior());
      $oNovoCalculo->setValorCalculado($oCalculoAnterior->getValorCalculado());
      $oNovoCalculo->setValorResidual($oCalculoAnterior->getValorResidual());
      $oNovaPlanilha->adicionarCalculo($oNovoCalculo);
    }

    $this->setAtivo(false);
    $oNovaPlanilha->salvar();
    $this->salvar();
  }

  /**
   * Clona o dados da Planilha.
   * usando no método cancelar.
   */
  protected function __clone() {

    $this->iPlanilha = null;
    $this->aCalculos = array();
  }

  /**
   * Método valida se existe parâmetro configurado para a instituição atual
   * @return mixed
   */
  public function hasParametroDepreciacaoHabilitado() {

    $iInstituicao              = db_getsession("DB_instit");
    $iAnoSessao                = db_getsession("DB_anousu");
    $sWhereConfiguracao        = "t59_instituicao = {$iInstituicao}";
    $oDaoConfiguracao          = db_utils::getDao("cfpatriinstituicao");
    $sSqlConfiguracao          = $oDaoConfiguracao->sql_query_file(null,
                                                                   "t59_dataimplanatacaodepreciacao",
                                                                   null,
                                                                   $sWhereConfiguracao);
    $rsConfiguracao            = $oDaoConfiguracao->sql_record($sSqlConfiguracao);
    $this->dtInicioDepreciacao = db_utils::fieldsMemory($rsConfiguracao, 0)->t59_dataimplanatacaodepreciacao;
    if (!empty($this->dtInicioDepreciacao)) {
      return true;
    }
    return false;
  }

  /**
   * Valida se o parâmetro que implementa a depreciação está setado.
   * Caso esteja, é executado um FOR percorrendo todos os anos entre o ano da implementação e o ano
   * corrente validando se existe depreciação processada para todos meses.
   * @throws Exception
   * @return boolean
   */
  public function validaAnosProcessados() {

    if ($this->hasParametroDepreciacaoHabilitado()) {

      $dtParametro          = $this->getDataInicioDepreciacao();
      $aDataParametro       = explode("-", $dtParametro);
      $iAnoImplantacao      = $aDataParametro[0];
      $iMesImplantacao      = $aDataParametro[1];
      $iAnoSessao           = db_getsession("DB_anousu");
      $oDaoHistoricoCalculo = db_utils::getDao("benshistoricocalculo");
      for ($iAno = $iAnoImplantacao; $iAno < $iAnoSessao; $iAno++) {

        $sSqlBuscaPorAno = $oDaoHistoricoCalculo->sql_query_file(null, "max(t57_mes) as ultimomes",
                                                                 null,
                                                                "t57_ano = {$iAno}");
        $rsBuscaPorAno   = $oDaoHistoricoCalculo->sql_record($sSqlBuscaPorAno);
        $iUltimoMes      = db_utils::fieldsMemory($rsBuscaPorAno, 0)->ultimomes;
        if ($iUltimoMes != 12) {

          $oParms = new stdClass();
          $oParms->iAno = $iAno;
          throw new Exception(_M('patrimonial.patrimonio.PlanilhaCalculo.ano_possui_meses_nao_processados', $oParms));
        }
      }
    }
    return true;
  }

  public function ativaProcessamentoRetroativo() {
    $this->lProcessamentoRetroativo = true;
  }

  public function isProcessamentoRetroativo() {

    return $this->lProcessamentoRetroativo;
  }

  public function setCodigoBensProcessamentoRetroativo($sCodigosBensProcessamentoRetroativo) {
    $this->sCodigosBensProcessamentoRetroativo = $sCodigosBensProcessamentoRetroativo;
  }

  public function getCodigoBensProcessamentoRetroativo() {
    return $this->sCodigosBensProcessamentoRetroativo;
  }

}
?>