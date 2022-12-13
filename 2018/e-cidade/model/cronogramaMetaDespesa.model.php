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


final class cronogramaMetaDespesa {

  const MENSAGENS = 'financeiro.orcamento.cronogramaMetaDespesa.';

  protected $iDespesa;

  protected $iPerspectiva;

  protected $aAnos = array();

  protected $oDespesa ;

  protected $iCodigoDespesa;

  protected $iNivel = null;

  protected $aInstituicoes = array();

  protected  $aListaDotacoesGrupo = array();

  /**
   * @var cronogramaFinanceiro
   */
  protected $oCronograma = null;

  public function __construct($oDespesa, $iNivel = null, cronogramaFinanceiro $oCronogramaFinanceiro) {

    $this->oCronograma = $oCronogramaFinanceiro;
    if ($iNivel == null) {

      $this->iDespesa       = $oDespesa->dotacao;
      $this->iPerspectiva   = $oDespesa->iPerspectiva;
      $this->iCodigoDespesa = $oDespesa->iSequencial;
      $this->oDespesa       = $oDespesa;

    } else {

      $this->iNivel = $iNivel;
      $this->iPerspectiva   = $oDespesa->iPerspectiva;
      $this->oDespesa       = $oDespesa;
      if ($iNivel != 99 && $iNivel != 9) {
        $this->iDespesa       = $oDespesa->codigo;
      }
    }
    $this->aListaDotacoesGrupo = explode(",", urldecode($oDespesa->lista_dotacoes_grupo));
    unset($this->oDespesa->lista_dotacoes_grupo);
  }

  public function calcularMetas() {

    $nValorTotalCalculado = 0;
    $nPontoPercentual     = 0;
    /**
     * Descobrimos o total projeto para a arrecadacao da receita no ano
     */
    $sSqlTotalReceita  =  "SELECT coalesce(sum(o127_valor),0) as valortotal";
    $sSqlTotalReceita .=  "  from cronogramametareceita";
    $sSqlTotalReceita .=  "       inner join cronogramaperspectivareceita on o127_cronogramaperspectivareceita = o126_sequencial";
    $sSqlTotalReceita .=  "       inner join orcreceita on o70_codrec = o126_codrec and o70_anousu = o126_anousu";
    $sSqlTotalReceita .=  " where o126_cronogramaperspectiva = {$this->iPerspectiva}";
    $sSqlTotalReceita .=  "   and o70_codigo                 = {$this->oDespesa->recurso}";
    $rsTotalReceita   = db_query($sSqlTotalReceita);
    $nTotalReceita    = db_utils::fieldsMemory($rsTotalReceita, 0)->valortotal;

    $nValorDespesa    = $this->oDespesa->valororcado;
    for ($iMes = 1; $iMes <= 12; $iMes++) {

      /**
       * Calculamos o total das receita no mes
       */
      $sSqlTotalReceitaMes  = "SELECT coalesce(sum(o127_valor),0) as valortotal";
      $sSqlTotalReceitaMes .= "  from cronogramametareceita";
      $sSqlTotalReceitaMes .= "       inner join cronogramaperspectivareceita on o127_cronogramaperspectivareceita = o126_sequencial";
      $sSqlTotalReceitaMes .= "       inner join orcreceita on o70_codrec = o126_codrec and o70_anousu = o126_anousu";
      $sSqlTotalReceitaMes .= " where o126_cronogramaperspectiva = {$this->iPerspectiva}";
      $sSqlTotalReceitaMes .= "   and o127_mes                   = {$iMes}";
      $sSqlTotalReceitaMes .= "   and o70_codigo                 = {$this->oDespesa->recurso}";

      $rsTotalReceitaMes   = db_query($sSqlTotalReceitaMes);
      if ($rsTotalReceitaMes) {

        $oMesBase         = db_utils::fieldsMemory($rsTotalReceitaMes, 0);
        $oMes             = new stdClass();
        $nPercentual      = 0;
        if ($oMesBase->valortotal  < 0) {

          $iCodigoErro           = $this->oDespesa->recurso;
          $oStdMensagem          = new stdClass();
          $oStdMensagem->recurso = $this->oDespesa->recurso;
          $oStdMensagem->mes     = db_mes($iMes);
          throw new Exception(_M(self::MENSAGENS . "erro_meta_arrecadacao_negativo", $oStdMensagem), $iCodigoErro);

        }

        if (abs($nTotalReceita > 0)) {
          $nPercentual     = round((($oMesBase->valortotal*100)/$nTotalReceita) ,2);
        }

        $oMes->valor = 0;
        if (abs($nPercentual) > 0) {
          $oMes->valor           = round(($nValorDespesa*$nPercentual)/100);
        }

        $oMes->sequencial      = null;
        $oMes->mes             = $iMes;
        $oMes->valordot        = $nValorDespesa;
        $oMes->dot             = $this->iDespesa;
        $oMes->percentual      = $nPercentual;
        $oMes->valormes        = $oMesBase->valortotal;
        $aGastoMes[$iMes]      = $oMes;

        $nValorTotalCalculado += $oMes->valor;
        $nPontoPercentual     += $nPercentual;

      }
    }
    /**
     * Fizemos o arredondamento , caso necessário em Dezembro;
     */
    if ((round($nPontoPercentual,2) < 100) || ($nValorTotalCalculado < $nValorDespesa)) {

      $nPercentualDiferenca       = (100 - $nPontoPercentual);
      $aGastoMes[12]->valor      += round(($nValorDespesa-$nValorTotalCalculado));
      $aGastoMes[12]->percentual += $nPercentualDiferenca;

    } else if ((round($nPontoPercentual,2) > 100) || ($nValorTotalCalculado > $nValorDespesa)) {

      $nPercentualDiferenca       = ($nPontoPercentual - 100);
      $aGastoMes[12]->valor      -= round($nValorTotalCalculado - $nValorDespesa);
      $aGastoMes[12]->percentual -= ($nPercentualDiferenca);

    }

    /**
     * Percorremos os meses  encontrados e persistimos na base
     */
    foreach($aGastoMes as $oMesMeta) {

      $nPercentual = $oMesMeta->percentual;
      if ($oMesMeta->valor == 0) {
        $nPercentual = 0;
      }

      $oDaoCronogramaMeta                                    =  db_utils::getDao("cronogramametadespesa");
      $oDaoCronogramaMeta->o131_cronogramaperspectivadespesa = $this->iCodigoDespesa;
      $oDaoCronogramaMeta->o131_mes                          = $oMesMeta->mes;
      $oDaoCronogramaMeta->o131_percentual                   = "".round($nPercentual,2)."";
      $oDaoCronogramaMeta->o131_valor                        = "".round($oMesMeta->valor)."";

      /**
       * Verificamos se já existe a meta cadastrada
       */
      $sWhere  = "o131_cronogramaperspectivadespesa = {$this->iCodigoDespesa} ";
      $sWhere .= " and o131_mes = {$oMesMeta->mes}";
      $sSqlVerificaMeta = $oDaoCronogramaMeta->sql_query_file(null,"o131_sequencial", null, $sWhere);
      $rsVerificaMeta   = $oDaoCronogramaMeta->sql_record($sSqlVerificaMeta);
      if ($oDaoCronogramaMeta->numrows > 1) {

        $oStdMensagem          = new stdClass();
        $oStdMensagem->despesa = $this->iDespesa;
        $oStdMensagem->mes     = db_mes($oMesMeta->mes);
        throw new Exception(_M(self::MENSAGENS . "erro_mais_de_uma_projecao", $oStdMensagem));

      } else if ($oDaoCronogramaMeta->numrows == 1) {

        $oDaoCronogramaMeta->o131_sequencial = db_utils::fieldsMemory($rsVerificaMeta, 0)->o131_sequencial;
        $oDaoCronogramaMeta->alterar($oDaoCronogramaMeta->o131_sequencial);

      } else {
        $oDaoCronogramaMeta->incluir(null);
      }
      if ($oDaoCronogramaMeta->erro_status == 0) {

        $oStdMensagem          = new stdClass();
        $oStdMensagem->despesa = $this->iDespesa;
        $oStdMensagem->mes     = db_mes($oMesMeta->mes);
        throw new Exception(_M(self::MENSAGENS . "erro_inclusao_meta_despesa", $oStdMensagem));

      }
    }
  }

  public function getMetas($sFiltros) {

    require_once("libs/db_liborcamento.php");
    $oSelDotacao = new cl_selorcdotacao();
    $oSelDotacao->setDados($sFiltros); // passa os parametros vindos da func_selorcdotacao_abas.php
    $this->aMeses       = array();
    $aDadosBases        = array();
    $oDaoCronogramaMeta = new cl_cronogramametadespesa;
    if ($this->iNivel  == null) {

      $sWhere = "o127_cronogramaperspectivadespesa={$this->oDespesa->iSequencial}";
      $sSqlDadosMeta      = $oDaoCronogramaMeta->sql_query_file(null,
        "*",
        "o127_mes",
        "");
    } else {

      $sInstituicoes   = implode(",", $this->getInstituicoes());
      $sWhere  = " o130_cronogramaperspectiva = {$this->iPerspectiva}";
      $sWhere .= " and o58_instit in ({$sInstituicoes})";
      $sWhere .= " and ".$oSelDotacao->getDados(false);
      $sWhere .= self::getFiltroByNivel($this->oDespesa, $this->iNivel);
      $sSqlDadosMeta     = $oDaoCronogramaMeta->sql_query(null,
        "sum(o131_valor) as valor, o131_mes",
        "o131_mes",
        $sWhere ." group by o131_mes"
      );

    }

    $nValorBaseParaCalculo = $this->oDespesa->valororcado;
    if ($this->oCronograma->getTipo() == cronogramaFinanceiro::TIPO_ACOMPANHAMENTO) {

      $sSqlValorBaseParaCalculo = $oDaoCronogramaMeta->sql_query_meta_despesa(null,
                                                                             "coalesce(sum(o131_valor), 0) as valor",
                                                                             null,
                                                                             $sWhere
                                                                            );
      $rsValorBaseParaCalculo = db_query($sSqlValorBaseParaCalculo);
      if ($rsValorBaseParaCalculo) {
        $nValorBaseParaCalculo = db_utils::fieldsMemory($rsValorBaseParaCalculo, 0)->valor;
      }
      $this->oDespesa->valororcado = $nValorBaseParaCalculo;
    }
    /**
     * Aplicamos um generate series, para garantir que teremos linhas  para todos os meses.
     */
    $sSqlDadosMetas  =  "select valor, meses as o131_mes ";
    $sSqlDadosMetas .=  "  from generate_series(1,12) meses";
    $sSqlDadosMetas .= "        left join ({$sSqlDadosMeta}) as dados on meses = dados.o131_mes";

    $rsDadosMeta = $oDaoCronogramaMeta->sql_record($sSqlDadosMetas);
    $iTotalMeses = $oDaoCronogramaMeta->numrows;
    for ($i = 0; $i < $iTotalMeses; $i++) {

      $oMesBase         = db_utils::fieldsMemory($rsDadosMeta, $i);
      $oMes             = new stdClass();
      if ($this->iNivel == null) {

        $oMes->valor      = $oMesBase->o131_valor;
        $oMes->sequencial = $oMesBase->o131_sequencial;
        $oMes->mes        = $oMesBase->o131_mes;
        $oMes->percentual = $oMesBase->o131_percentual;
        $oMes->valormedia = $nValorBaseParaCalculo;

      } else {

        $oMes->valor      = $oMesBase->valor;
        $oMes->sequencial = null;
        $oMes->mes        = $oMesBase->o131_mes;
        $oMes->percentual = 0;
        if ($this->oDespesa->valororcado > 0) {

          if ($nValorBaseParaCalculo == 0) {
            $oMes->percentual = 100;
          } else {
            $oMes->percentual = round(($oMesBase->valor * 100) / $nValorBaseParaCalculo, 2);
          }
        }
        $oMes->valormedia = $this->oDespesa->valororcado;

      }
      $this->aMeses[] = $oMes;
    }

    $aDadosBases = $this->aMeses;
    return $aDadosBases;
  }

  public function setValorMes($iMes, $nValor) {

    $nPercentual = 0;
    if (isset($this->aMeses[$iMes  -1])) {

      $nValorMedia = $this->oDespesa->valororcado;
      $nPercentual = round(($nValor*100)/$nValorMedia,2);
      $this->aMeses[$iMes-1]->valor = $nValor;

    }
    return $nPercentual;
  }

  public function getDespesa() {
    return $this->iDespesa;
  }

  public function getValorTotal() {
    return $this->oDespesa->valororcado;
  }

  public function save() {

    /**
     * percorremos todas os meses previstos e anos para a receita e salvamos
     */
    $oDaoMes        = new cl_cronogramametadespesa;

    $nValorTotalReestimado = 0;
    foreach ($this->aMeses as $oMes) {

      $nValorTotalReestimado += $oMes->valor;
    }
    $nValorAnterior     = $this->getValorPrevistoAnterior();
    $iTotalDotacoes     = count($this->aListaDotacoesGrupo);
    $nValorMediaDotacao = round($nValorTotalReestimado / $iTotalDotacoes);
    $nPercentualAjuste  = 100;
    if ($nValorAnterior != $nValorTotalReestimado && $nValorAnterior > 0) {
      $nPercentualAjuste = ($nValorTotalReestimado * 100) / $nValorAnterior;
    }

    foreach ($this->aMeses as $oMes) {

      /**
       * Percorremos as dotacoes cadastradas  conforme o nivel informado, e aplicamos o valor definido no nivel para cada dotacao
       */
      $sInstituicoes  = implode(",", $this->getInstituicoes());
      $sWhere         = " o130_cronogramaperspectiva = {$this->iPerspectiva}";
      $sWhere        .= " and o58_instit in ({$sInstituicoes})";
      $sWhere        .= self::getFiltroByNivel($this->oDespesa, $this->iNivel);

      $sSqlDotacoesNivel =  $oDaoMes->sql_query_metas(null,
                                                      "o131_valor , o58_coddot, o131_mes, o131_sequencial, o58_valor, o130_sequencial",
                                                      "o131_mes",
                                                      $sWhere,$oMes->mes
      );
      /**
       * garantimos os 12 meses para cada Dotacao
       */
      $rsDotacoesNivel = $oDaoMes->sql_record($sSqlDotacoesNivel);
      if ($oDaoMes->numrows > 0) {

        $nValorTotalMes       = $oMes->valor;
        $nValorAcumulado      = 0;
        $iTotalRegistrosNivel = $oDaoMes->numrows;
        for ($i = 0; $i <  $iTotalRegistrosNivel; $i++) {

          $oDotacao  = db_utils::fieldsMemory($rsDotacoesNivel, $i);

          /**
           *Calculamos o valor conforme o Mes e o tipo do cronograma
           */
          $nValorTotalDotacao = $oDotacao->o58_valor;
          if ($this->oCronograma->getTipo() == cronogramaFinanceiro::TIPO_ACOMPANHAMENTO ) {

            /**
             * O valor total do cronograma é zero, e criei valores, é utilizado o valor médio das dotações,
             * isto é, é calculado o valor total do nivel que está sendo modificado dividido pelo numero de dotações
             * do nivel.
             */
            if ($nValorTotalReestimado > 0 && $nValorAnterior == 0) {
              $nValorTotalDotacao = $nValorMediaDotacao;
            }

            /**
             * o valor apenas está sendo reestimado. aaplicamos o percentual de modificação sobre o valor.
             */
            if ($nValorTotalReestimado > 0 && $nValorAnterior > 0) {

              $nValorDotacao = $this->getValorPrevistoAnterior($oDotacao->o58_coddot);
            }

            /**
             * Calculamos apenas o % aplicado na diferença do valor de cada dotaco
             */
            $nValorTotalDotacao = round(($nValorTotalDotacao * $nPercentualAjuste) / 100);
          }

          $nValor           = round(($nValorTotalDotacao * $oMes->percentual / 100));
          $nValorAcumulado += $nValor;
          if (($i + 1) == $iTotalRegistrosNivel) {

            $nDiferenca = $nValorTotalMes - $nValorAcumulado;
            $nValor     += $nDiferenca;
          }
          $nPercentualMes = $oMes->percentual;
          if ($oMes->valor <= 0 || $nValor <= 0) {

            $nValor         = 0;
            $nPercentualMes = 0;
          }

          $oDaoMes->o131_percentual                   = "{$nPercentualMes}";
          $oDaoMes->o131_valor                        = "{$nValor}";
          $oDaoMes->o131_mes                          = "$oMes->mes";
          $oDaoMes->o131_sequencial                   = $oDotacao->o131_sequencial;
          $oDaoMes->o131_cronogramaperspectivadespesa = $oDotacao->o130_sequencial;
          if (empty($oDaoMes->o131_sequencial)) {
            $oDaoMes->incluir(null);
          } else {
            $oDaoMes->alterar($oDotacao->o131_sequencial);
          }
          if ($oDaoMes->erro_status == 0) {

            $oStdMensagem          = new stdClass();
            $oStdMensagem->despesa = $this->iDespesa;
            $oStdMensagem->msg     = $oDaoMes->erro_msg;
            throw new Exception(_M(self::MENSAGENS . "erro_salvar_meses", $oStdMensagem));
          }

        }
      }
    }
  }

  public function getInstituicoes() {
    return $this->aInstituicoes;
  }

  /**
   * @param array $aInstituicoes
   */
  public function setInstituicoes($aInstituicoes) {
    $this->aInstituicoes = $aInstituicoes;
  }

  public function getNivel() {
    return $this->iNivel;
  }

  /**
   * Retorna o filtro a ser utilizado de acordo com os parâmetros da despesa e seu nível
   *
   * @param stdClass $oStdDespesa
   * @param integer $iNivel
   * @return string
   */
  public static function getFiltroByNivel(stdClass $oStdDespesa, $iNivel) {

    $sWhere = "";
    switch ($iNivel) {

      case 1:

        $sWhere  .= "   and o58_orgao     = {$oStdDespesa->codigo}";
        break;

      case 2:

        $sWhere .= "   and o58_unidade   = {$oStdDespesa->codigo} and o58_orgao = {$oStdDespesa->o58_orgao}";
        break;

      case 3:

        $sWhere  .= "   and o58_funcao    = {$oStdDespesa->codigo}";
        break;

      case 4:

        $sWhere  .= "   and o58_subfuncao = {$oStdDespesa->codigo}";
        break;

      case 5:

        $sWhere    .= "   and o58_programa  = {$oStdDespesa->codigo}";
        break;

      case 6:

        $sWhere   .= "   and o58_projativ  = {$oStdDespesa->codigo}";
        break;

      case 7:

        $sWhere    .= "   and o58_codele  = {$oStdDespesa->codigo}";
        break;

      case 8:

        $sWhere   .= "   and o58_codigo   = {$oStdDespesa->codigo}";
        break;
      case 99:

        $sWhere .= "   and o58_orgao     = {$oStdDespesa->o58_orgao}";
        $sWhere .= "   and o58_unidade   = {$oStdDespesa->o58_unidade}";
        $sWhere .= "   and o58_funcao    = {$oStdDespesa->o58_funcao}";
        $sWhere .= "   and o58_subfuncao = {$oStdDespesa->o58_subfuncao}";
        $sWhere .= "   and o58_programa  = {$oStdDespesa->o58_programa}";
        $sWhere .= "   and o58_projativ  = {$oStdDespesa->o58_projativ}";
        $sWhere .= "   and o58_codele    = {$oStdDespesa->o58_codele}";
        $sWhere .= "   and o58_codigo    = {$oStdDespesa->o58_codigo}";
        break;

      case 9:

        $sWhere .= "   and o58_orgao     = {$oStdDespesa->o58_orgao}";
        $sWhere .= "   and o58_unidade   = {$oStdDespesa->o58_unidade}";
        $sWhere .= "   and o58_codigo    = {$oStdDespesa->o58_codigo}";
        $sWhere .= "   and o58_localizadorgastos  = {$oStdDespesa->o58_localizadorgastos}";
        break;
    }

    return $sWhere;
  }


  /**
   * @param \stdClass $oStdDespesa
   * @param integer   $iNivel
   * @param \DBDate   $oDataInicial
   * @param \DBDate   $oDataFinal
   *
   * @return \CronogramaInformacaoDespesa
   */
  public static function getInformacaoDespesa(stdClass $oStdDespesa, $iNivel, DBDate $oDataInicial = null, DBDate $oDataFinal = null) {

    $aWherePadrao = explode(" and ", self::getFiltroByNivel($oStdDespesa, $iNivel));
    unset($aWherePadrao[0]);

    /**
     * Configuração para a busca dos valores na dotação.
     */
    $aWherePagamento = $aWherePadrao;
    if (!empty($oDataInicial)) {
      $aWherePagamento[] = " c70_data >= '{$oDataInicial->getDate()}' ";
    }
    if (!empty($oDataFinal)) {
      $aWherePagamento[] = " c70_data <= '{$oDataFinal->getDate()}' ";
    }
    $aWherePagamento[] = " conhistdoc.c53_tipo in (30, 31)";

    $sCampos       = "coalesce(sum(case when c53_tipo = 31 then c70_valor * -1 else c70_valor end), 0) as valor_pago";
    $oDaoConlancam = new cl_conlancam();
    $sSqlPagamento = $oDaoConlancam->sql_query_despesa($sCampos, null, implode(" and ", $aWherePagamento));
    $rsValorPago   = db_query($sSqlPagamento);
    $nValorPago    = $rsValorPago ? db_utils::fieldsMemory($rsValorPago, 0)->valor_pago : 0;

    /**
     * Busca os valores de empenhos que possuem cota mensal cadastrada
     */
    $aWhereCotaMensal = $aWherePadrao;
    $aWhereCotaMensal[] = "empempenho.e60_anousu = {$oDataFinal->getAno()}";
    $aWhereCotaMensal[] = "empenhocotamensal.e05_mes = {$oDataFinal->getMes()}";
    $sCampos       = "coalesce(sum(e05_valor), 0) as valor_cota_mensal";
    $oDaoEmpenho   = new cl_empempenho();
    $sSqlValorCota = $oDaoEmpenho->sql_query_cota_mensal(null, $sCampos, null, implode(' and ', $aWhereCotaMensal));
    $rsValorCotaMensal = db_query($sSqlValorCota);
    $nValorCotaMensal  = $rsValorCotaMensal ? db_utils::fieldsMemory($rsValorCotaMensal, 0)->valor_cota_mensal : 0;


    /**
     * Valor previsto no primeiro cronograma de desembolso
     */
    $sCampos = "coalesce(sum(o131_valor), 0) as valor_previsto";
    $aWhereValorPrevisto  = $aWherePadrao;
    $aWhereValorPrevisto[] = "cronogramametadespesa.o131_mes      = {$oDataFinal->getMes()}";
    $aWhereValorPrevisto[] = "cronogramaperspectiva.o124_ano      = {$oDataFinal->getAno()}";
    $aWhereValorPrevisto[] = "cronogramaperspectiva.o124_situacao = ".cronogramaFinanceiro::SITUACAO_HOMOLOGADO;
    $aWhereValorPrevisto[] = "cronogramaperspectiva.o124_tipo = ".cronogramaFinanceiro::TIPO_CRONOGRAMA;
    $oDaoCronograma    = new cl_cronogramametadespesa();
    $sSqlValorPrevisto = $oDaoCronograma->sql_query_meta_despesa(null, $sCampos, null, implode(' and ', $aWhereValorPrevisto));
    $rsValorPrevisto   = db_query($sSqlValorPrevisto);
    $nValorPrevisto    = $rsValorPrevisto ? db_utils::fieldsMemory($rsValorPrevisto, 0)->valor_previsto : 0;

    /**
     * Setamos a informação em um ValueObject para retorno a tela
     */
    $oCronogramaValores = new CronogramaInformacaoDespesa();
    $oCronogramaValores->setValorPago($nValorPago);
    $oCronogramaValores->setValorCotaMensal($nValorCotaMensal);
    $oCronogramaValores->setValorPrevisto($nValorPrevisto);
    return $oCronogramaValores;
  }


  public function getValorPrevistoAnterior($iCodigoDotacao = null) {

    $oDaoCronogramaDespesa = new cl_cronogramametadespesa();
    $sWhere  = "o130_anousu = {$this->oCronograma->getAno()} ";
    $sWhere .= " and atual.o124_sequencial = {$this->iPerspectiva}";
    $sWhere  .= self::getFiltroByNivel($this->oDespesa, $this->iNivel);
    if (!empty($iCodigoDotacao)) {
      $sWhere .= " and o130_coddot = {$iCodigoDotacao} ";
    }

    $sCampos = "coalesce(sum(o131_valor), 0) as valor_anterior";
    $sSqlValorPrevistoAnterior = $oDaoCronogramaDespesa->sql_query_meta_despesa_anterior(null, $sCampos, null, $sWhere);
    if (!empty($iCodigoDotacao)) {
     // die($sSqlValorPrevistoAnterior);
    }
    $rsValorPrevistoAnterior   = db_query($sSqlValorPrevistoAnterior);
    return db_utils::fieldsMemory($rsValorPrevistoAnterior, 0)->valor_anterior;
  }

}