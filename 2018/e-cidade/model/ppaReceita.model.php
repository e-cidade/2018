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


class ppaReceita {

  private $iCodigoVersao = 0;

  private $oDadosLei = "";

  private $sInstituicoes = null;


  /**
   *
   */
  public function __construct($iCodigoVersao) {

    $this->iCodigoVersao = $iCodigoVersao;
    $oDaoPPaLei       = db_utils::getDao("ppaversao");
    $rsPPalei         = $oDaoPPaLei->sql_record($oDaoPPaLei->sql_query($this->iCodigoVersao));
    $this->oDadosLei  = db_utils::fieldsMemory($rsPPalei, 0);

  }

  /**
   * Processa a base de cálculo para os anos anteriores.
   * @param integer $iAno
   * @throws Exception
   * @return boolean
   */
  public function processaBaseCalculo($iAno) {

    require_once("libs/db_liborcamento.php");
    $dtDataInicial   = "{$iAno}-01-01";
    $dtDataFinal     = "{$iAno}-12-31";
    $lMediaPonderada = false;
    if ($iAno == db_getsession("DB_anousu")) {

      $mesAnterior       = date("m",db_getsession("DB_datausu"))-1;
      if ($mesAnterior == 0) {
        $mesAnterior = 1;
      }
      $ultimoDiaMesAtual = cal_days_in_month(CAL_GREGORIAN,$mesAnterior, $iAno);
      $dtDataFinal       = "{$iAno}-{$mesAnterior}-{$ultimoDiaMesAtual}";
      $lMediaPonderada   = true;

    }

    /**
     * Pesquisamos a mascara que está sendo usado na CP.
     */
    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
    $oParametroOrcamento = $aParametros[0];
    $oDaoEstrutura       = db_utils::getDao("db_estrutura");
    $sSqlDadosEstrutura  = $oDaoEstrutura->sql_query_file($oParametroOrcamento->o50_estruturacp);
    $rsDadosEstrutura    = $oDaoEstrutura->sql_record($sSqlDadosEstrutura);
    if ($oDaoEstrutura->numrows == 0) {
      throw new Exception("Estrutural da CP/CA não configurado para o ano {$iAno}.");
    }
    $oDadosEstrutura = db_utils::fieldsMemory($rsDadosEstrutura, 0);

    /**
     * Verificamos se a Existe uma CP/CA cadastrada com o Código igual a mascara (deverá ser NÃO SE APLICA)
     * caso nao existir, Devemos Obrigar o Usuário a Cadastrar.
     */
    $oDaoConcarpeculiar = db_utils::getDao("concarpeculiar");
    $sSqlConcarpeculiar = $oDaoConcarpeculiar->sql_query_file($oDadosEstrutura->db77_estrut);
    $rsConcarPeculiar   = $oDaoConcarpeculiar->sql_record($sSqlConcarpeculiar);
    if ($oDaoConcarpeculiar->numrows == 0) {

      $sMsg  = "É necessário incluir no cadastro uma CP/CA de código {$oDadosEstrutura->db77_estrut} ";
      $sMsg .= "com o título 'NÃO APLICÁVEL'. Para realizar o Cadastro Acesse:\n";
      $sMsg .= db_stdClass::getCaminhoMenu(8833).".";
      throw new Exception($sMsg);
    }
    require_once("libs/db_libpostgres.php");
    if (PostgreSQLUtils::isTableExists("work_receita")) {
      db_query("drop table work_receita");
    }
    $rsReceita = db_receitasaldo(11,1,3,true,
                                 "o70_instit = ".db_getsession("DB_instit"),
                                 $iAno,
                                 $dtDataInicial,
                                 $dtDataFinal,
                                 false,
                                 ' * ',
                                 false
                               );


    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaReceita = db_utils::getDao("ppaestimativareceita");
    $aReceitasAno              = db_utils::getCollectionByRecord($rsReceita);
    if (PostgreSQLUtils::isTableExists("work_receita")) {
      db_query("drop table work_receita");
    }
    foreach ($aReceitasAno as $oReceitaAno) {

      $sSql = "select o57_codfon
                 from  orcfontes
                where  o57_fonte = '{$oReceitaAno->o57_fonte}'
                  and  o57_anousu = {$iAno}";
      $rsCodfon = db_query($sSql);
      if (!$rsCodfon) {
        throw new Exception("Erro ao Processar receita {$oReceitaAno->o57_fonte}", 1);
      }

      if (pg_num_rows($rsCodfon) == 0) {
        throw new Exception("Não foi encontrado fonte de receita para o estrutural {$oReceitaAno->o57_fonte} no ano de {$iAno}.");
      }

      $iCodigoFonte  = db_utils::fieldsMemory($rsCodfon, 0)->o57_codfon;
      $nValorReceita = $oReceitaAno->saldo_arrecadado_acumulado;
      if ($lMediaPonderada) {
        $nValorReceita =  round((($oReceitaAno->saldo_arrecadado_acumulado/$mesAnterior)*12),2);
      }

      if ($oReceitaAno->o70_codrec == 0){
        $nValorReceita = 0;
      }

      /*
       * Verificamos se a Receita já foi processada.
       * caso seje, apenas atualizamos o seu valor.
       */
      $iConcarpeculiar = $oDadosEstrutura->db77_estrut;
     if (isset($oReceitaAno->o70_concarpeculiar)) {
        $iConcarpeculiar = $oReceitaAno->o70_concarpeculiar;
      }
      if ($iConcarpeculiar == '0') {
        $iConcarpeculiar = $oDadosEstrutura->db77_estrut;
      }
      $sSqlReceita =  $oDaoPppaEstimativaReceita->sql_query_analitica(null,
                                                                 "*",
                                                                 null,
                                                                "o06_codrec             = {$iCodigoFonte}
                                                                 and o06_anousu         = {$iAno}
                                                                 and o05_ppaversao      = {$this->iCodigoVersao}
                                                                 and o06_ppaversao      = {$this->iCodigoVersao}
                                                                 and o06_concarpeculiar = '{$iConcarpeculiar}'
                                                                 and c61_instit         = ".db_getsession("DB_instit")
                                                                );


      $rsReceita                             = $oDaoPppaEstimativaReceita->sql_record($sSqlReceita);
      if ($oDaoPppaEstimativaReceita->numrows > 0 ) {

        $oReceita = db_utils::fieldsMemory($rsReceita, 0);
        $oDaoPppaEstimativa->o05_sequencial  = $oReceita->o06_ppaestimativa;
        $oDaoPppaEstimativa->o05_valor       = "$nValorReceita";
        $oDaoPppaEstimativa->alterar($oReceita->o06_ppaestimativa);

        if ($oDaoPppaEstimativa->erro_status == 0 ){
           throw new Exception("Erro ao processar Receita {$oReceitaAno->o57_codfon} erro ao incluir estimativa.
           \n$oDaoPppaEstimativaReceita->erro_msg",2);
        }

      } else {

        $oDaoPppaEstimativa->o05_base          = "true";
        $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
        $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
        $oDaoPppaEstimativa->o05_valor         = "$nValorReceita";
        $oDaoPppaEstimativa->incluir(null);
        if ($oDaoPppaEstimativa->erro_status == 0 ) {

          $sMsgErro  = "Erro ao processar Receita {$oReceitaAno->o57_fonte} erro ao incluir estimativa.\n";
          $sMsgErro .= $oDaoPppaEstimativa->erro_msg;
          throw new Exception($sMsgErro,2);
        }

        $oDaoPppaEstimativaReceita->o06_anousu         = $iAno;
        $oDaoPppaEstimativaReceita->o06_ppaversao      = $this->iCodigoVersao;
        $oDaoPppaEstimativaReceita->o06_codrec         = $iCodigoFonte;
        $oDaoPppaEstimativaReceita->o06_concarpeculiar = "{$iConcarpeculiar}";
        $oDaoPppaEstimativaReceita->o06_ppaestimativa  = $oDaoPppaEstimativa->o05_sequencial;
        $oDaoPppaEstimativaReceita->incluir(null);
        if ($oDaoPppaEstimativaReceita->erro_status == 0) {

           throw new Exception("Erro ao processar Receita {$iCodigoFonte}-{$oReceitaAno->o57_fonte} erro ao incluir estimativa\n
           {$oDaoPppaEstimativaReceita->erro_msg}",3);
        }
      }
    }

    return true;
  }

  /**
   * Calcula a estimativa globl da receita
   *
   * @param integer $iAnoInicial ano inicial do processamento
   * @param integer $iAnoFinal ano final do processamento
   * @return ppaReceita
   */
  function processarEstimativasGlobais($iAnoInicial, $iAnoFinal, $lForcaCalculoAnterior = false) {

    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
    $oParametroOrcamento = $aParametros[0];

    $iInstituicaoSessao = db_getsession("DB_instit");

    /*
     * somamos todos as bases dos anos anteriores,
     * e comecamos a calcular as estimativas baseados na media dos valores encontrados
     */
    $oDaoPPAEstimativaReceita  = db_utils::getDao('ppaestimativareceita');
    $sCamposEstimativaGlobal   = "o57_codfon, sum(o05_valor)/".ppa::ANOS_PREVISAO_CALCULO." as vlrbase, o06_concarpeculiar";
    $sWhereEstimativasGlobal   = "       o05_ppaversao  = {$this->iCodigoVersao}  ";
    $sWhereEstimativasGlobal  .= "   and o05_base       = true                    ";
    $sWhereEstimativasGlobal  .= "   and o06_ppaversao  = {$this->iCodigoVersao}  ";
    $sWhereEstimativasGlobal  .= "   and c61_instit     = {$iInstituicaoSessao}   ";
    $sWhereEstimativasGlobal  .= " group by o57_codfon, o06_concarpeculiar        ";
    $sWhereEstimativasGlobal  .= " order by o57_codfon                            ";

    $sSqlBuscaEstimativaGlobal = $oDaoPPAEstimativaReceita->sql_query_estimativa_planoconta(null,
                                                                                            $sCamposEstimativaGlobal,
                                                                                            null,
                                                                                            $sWhereEstimativasGlobal);

    $rsBuscaEstimativaGlobal = $oDaoPPAEstimativaReceita->sql_record($sSqlBuscaEstimativaGlobal);
    if ($oDaoPPAEstimativaReceita->numrows == 0) {
      throw new Exception("Não foram encontradas bases de calculo.",1);
    }

    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaReceita = db_utils::getDao("ppaestimativareceita");
    $aValoresBases             = db_utils::getCollectionByRecord($rsBuscaEstimativaGlobal);
    $aValoresContaAnoAnterior  = array();
    foreach ($aValoresBases as $oValorBase) {

      $nValorAnterior = 0;
      $nValor         = 0;
      for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++) {

        /*
         * Verificamos se a receita existe no exercicio solicitado
         */
        if ($this->temReceitaNoAno($oValorBase->o57_codfon,$iAno)) {

          /*
           * Verificamos se a receita possui desdobramento no ano
           * caso isso exista, devemos calcular o valor conforme o valor total da receita,
           * e aplicar o percentual do desdobramento.
           */

          $sSqlDesdobramento  = " SELECT o57_fonte,o60_codfon,o60_perc";
          $sSqlDesdobramento .= "   from orcfontesdes ";
          $sSqlDesdobramento .= "        inner join  orcfontes on o57_codfon = o60_codfon";
          $sSqlDesdobramento .= "                             and o57_anousu = o60_anousu";
          $sSqlDesdobramento .= "  where o60_anousu = {$iAno} ";
          $sSqlDesdobramento .= "    and o60_codfon = {$oValorBase->o57_codfon}";
          $rsDesdobramento    = db_query($sSqlDesdobramento);
          if (pg_num_rows($rsDesdobramento) > 0) {

            $oDaoPPAReceita = db_utils::getDao("ppaestimativareceita");
            $oDesdobramento = db_utils::fieldsMemory($rsDesdobramento,0);
            $iReceita           = $this->criaContaMae(db_le_mae_rec($oDesdobramento->o57_fonte));
            $sSqlReceita  = " SELECT sum(o05_valor)/".ppa::ANOS_PREVISAO_CALCULO." as valorreceita";
            $sSqlReceita .= "   from  ppaestimativareceita ";
            $sSqlReceita .= "         inner join ppaestimativa on o06_ppaestimativa = o05_sequencial ";
            $sSqlReceita .= "         inner join orcfontes     on o06_codrec        = o57_codfon     ";
            $sSqlReceita .= "                                 and o57_anousu        = 2009     ";
            $sSqlReceita .= "      inner join conplanoreduz        on o06_codrec    = c61_codcon ";
            $sSqlReceita .= "                                     and c61_anousu    = 2009";
            $sSqlReceita .= "    where o05_ppaversao       = {$this->iCodigoVersao}                              ";
            $sSqlReceita .= "      and o05_base            = true                                             ";
            $sSqlReceita .= "      and o06_ppaversao       =   {$this->iCodigoVersao}";
            $sSqlReceita .= "      and o06_concarpeculiar  =   '{$oValorBase->o06_concarpeculiar}'  ";
            $sSqlReceita .= "      and o57_fonte  like '{$iReceita}%'                                ";
            $sSqlReceita .= "   and  c61_instit   = ".db_getsession("DB_instit");
            $rsValorMae     = db_query(analiseQueryPlanoOrcamento($sSqlReceita));
            $nValorContaMae = db_utils::fieldsMemory($rsValorMae, 0)->valorreceita;
            $nValorCalcular = @($nValorContaMae*$oDesdobramento->o60_perc)/100;
            if ($nValorCalcular > 0) {
               $nValor  = $nValorCalcular;
            }
          }
          if ($lForcaCalculoAnterior) {

            if ($iAno > $this->oDadosLei->o01_anoinicio) {

              $iAnoBase    = ($iAno-1);
              $sSqlEstimativa     =  $oDaoPppaEstimativaReceita->sql_query(null,
                                                                         "ppaestimativa.*",
                                                                          null,
                                                                          "o06_anousu            = {$iAnoBase}
                                                                          and o06_codrec         = {$oValorBase->o57_codfon}
                                                                          and o05_ppaversao      = {$this->iCodigoVersao}
                                                                          and o06_concarpeculiar = '{$oValorBase->o06_concarpeculiar}'
                                                                          and o06_ppaversao      = {$this->iCodigoVersao}"
                                                                          );
              $rsEstimativa       = $oDaoPppaEstimativaReceita->sql_record($sSqlEstimativa);
              if ($oDaoPppaEstimativaReceita->numrows > 0) {

                $oValor         = db_utils::fieldsMemory($rsEstimativa, 0);
                $nValorAnterior = $oValor->o05_valor;

              }
            }
          }
          $nValorParametro = ppa::getAcrescimosEstimativa($oValorBase->o57_codfon, $iAno);
          $nValor  = $nValorAnterior == 0?$oValorBase->vlrbase:$nValorAnterior;

          /**
           * Verifico o tipo de cálculo configurado para a conta e altero o valor com base na média de arrecadação
           * prevista para o exercicio atual
           */
          if ($iAno == $iAnoInicial) {

            $iTipoCalculo = ppa::getTipoCalculo($oValorBase->o57_codfon, $iAno);
            if ($iTipoCalculo == ppa::TIPO_CALCULO_EXERCICIO_ATUAL) {
              $nValor = $this->getValorReEstimativaExercicioAtual($oValorBase->o57_codfon, $iAno);
            }
          }


          if ($nValorParametro > 0) {
            $nValor *= $nValorParametro;
          }
          $aValoresContaAnoAnterior[$oValorBase->o57_codfon][$iAno] = $nValor;

          /*
           * Verificamos se a Receita já foi processada.
           * caso seje, apenas atualizamos o seu valor.
           */
          $sSqlReceita =  $oDaoPppaEstimativaReceita->sql_query_analitica(null,
                                                                     "*",
                                                                     null,
                                                                     "o06_codrec            = {$oValorBase->o57_codfon}
                                                                     and o06_anousu         = {$iAno}
                                                                     and o05_ppaversao      = {$this->iCodigoVersao}
                                                                     and o06_ppaversao      =  {$this->iCodigoVersao}
                                                                     and o06_concarpeculiar = '{$oValorBase->o06_concarpeculiar}'
                                                                     and c61_instit         = ".db_getsession("DB_instit")
                                                                     );
          $rsReceita = $oDaoPppaEstimativaReceita->sql_record($sSqlReceita);

          if ($oDaoPppaEstimativaReceita->numrows > 0 ) {

            $oReceita = db_utils::fieldsMemory($rsReceita, 0);
            $oDaoPppaEstimativa->o05_sequencial  = $oReceita->o06_ppaestimativa;
            $nValorSalvar = round($nValor);
            if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
              $nValorSalvar = round($nValor, 2);
            }
            $oDaoPppaEstimativa->o05_valor       = "{$nValorSalvar}";
            $oDaoPppaEstimativa->alterar($oReceita->o06_ppaestimativa);

            if ($oDaoPppaEstimativa->erro_status == 0 ){
               throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao alterar estimativa.\n{$oDaoPppaEstimativa->erro_msg}",2);
            }

          } else {

            $oDaoPppaEstimativa->o05_base          = "false";
            $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
            $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
            $nValorSalvar = round($nValor);
            if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
              $nValorSalvar = round($nValor, 2);
            }
            $oDaoPppaEstimativa->o05_valor       = "{$nValorSalvar}";
            $oDaoPppaEstimativa->incluir(null);
            if ($oDaoPppaEstimativa->erro_status == 0 ){
               throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao incluir estimativa.\n{$oDaoPppaEstimativa->erro_msg}",2);
            }

            $oDaoPppaEstimativaReceita->o06_anousu         = $iAno;
            $oDaoPppaEstimativaReceita->o06_codrec         = $oValorBase->o57_codfon;
            $oDaoPppaEstimativaReceita->o06_ppaversao      = $this->iCodigoVersao;
            $oDaoPppaEstimativaReceita->o06_concarpeculiar = "{$oValorBase->o06_concarpeculiar}";
            $oDaoPppaEstimativaReceita->o06_ppaestimativa  = $oDaoPppaEstimativa->o05_sequencial;
            $oDaoPppaEstimativaReceita->incluir(null);
            if ($oDaoPppaEstimativaReceita->erro_status == 0 ){
               throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao incluir estimativa.\n$oDaoPppaEstimativaReceita->erro_msg",3);
            }
          }
          $nValorAnterior = $nValor;
        }
      }
    }
    return $this;
  }

  /**
   * Calcula o acréscimo sobre a receita de acordo com o que foi configurado no parâmetro do CM
   * @param integer $iCodCon
   * @param integer $iAnoReferencia
   * @return number
   */
//   public function getAcrescimosEstimativa($iCodCon, $iAnoReferencia) {

//     $oDaoCenarioConplano = db_utils::getDao("orccenarioeconomicoconplano");
//     $nValorparametro     = 0;
//     $sSqlParametros      = $oDaoCenarioConplano->sql_query(null,
//                                                            "sum(o03_valorparam) as valorparametro",
//                                                            null,
//                                                            "o03_anoreferencia = {$iAnoReferencia}
//                                                            and o04_conplano   = {$iCodCon}
//                                                            and o03_instit     = ".db_getsession("DB_instit")
//                                                             );
//     $sSqlParametros   = analiseQueryPlanoOrcamento($sSqlParametros);
//     $rsParametros     = $oDaoCenarioConplano->sql_record($sSqlParametros);
//     if ($oDaoCenarioConplano->numrows > 0) {

//       $nValorParametro = db_utils::fieldsMemory($rsParametros, 0)->valorparametro;
//       $nValorparametro = 1+($nValorParametro/100);
//     }
//     return $nValorparametro;
//   }

  /**
   * Valida se existe receita no ano informado
   * @param integer $iCodFon
   * @param integer $iAnoRef
   * @return boolean
   */
  public function temReceitaNoAno($iCodFon, $iAnoRef) {

    $lRetorno  = false;
    $sSql      = "select o57_codfon ";
    $sSql     .= "  from orcfontes";
    $sSql     .= "       inner join  conplanoreduz on c61_anousu = o57_anousu";
    $sSql     .= "                                and c61_codcon = o57_codfon";
    $sSql     .= " where o57_codfon = {$iCodFon}";
    $sSql     .= "   and o57_anousu = {$iAnoRef}";
    $sSql     .= "   and c61_instit = ".db_getsession("DB_instit");
    $rsReceita = db_query(analiseQueryPlanoOrcamento($sSql));
    if ($rsReceita && pg_num_rows($rsReceita) > 0) {
      $lRetorno = true;
    }
    return $lRetorno;
  }

  /**
   * Retorna o quadro de estimativa das receitas
   *
   * @param integer $sEstrutural estrutural inicial
   * @param array   $aRecursos  recursos a serem filtrados
   * @return array
   */
  public function getQuadroEstimativas($sEstrutural = null, $aRecursos = null) {

    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
    $oParametroOrcamento = $aParametros[0];

    if (empty($this->sInstituicoes)) {
      $this->sInstituicoes = DB_getsession("DB_instit");
    }
    $oDaoPPalei = db_utils::getDao("ppaversao");
    $sSqlPPalei = $oDaoPPalei->sql_query($this->iCodigoVersao);
    $rsPpaLei   = $oDaoPPalei->sql_record($sSqlPPalei);
    if ($oDaoPPalei->numrows == 0) {
      throw new Exception("Nao foi encontrado dados da lei", 1);
    }
    $oParametroOrcamento = $aParametros[0];
    $oDaoEstrutura       = db_utils::getDao("db_estrutura");
    $sSqlDadosEstrutura  = $oDaoEstrutura->sql_query_file($oParametroOrcamento->o50_estruturacp);
    $rsDadosEstrutura    = $oDaoEstrutura->sql_record($sSqlDadosEstrutura);
    if ($oDaoEstrutura->numrows == 0) {
      throw new Exception("Estrutural da CP/CA não configurado para o ano {$iAno}.");
    }
    $oDadosEstrutura = db_utils::fieldsMemory($rsDadosEstrutura, 0);

    /**
     * Verificamos se a Existe uma CP/CA cadastrada com o Código igual a mascara (deverá ser NÃO SE APLICA)
     * caso nao existir, Devemos Obrigar o Usuário a Cadastrar.
     */
    $oDaoConcarpeculiar = db_utils::getDao("concarpeculiar");
    $sSqlConcarpeculiar = $oDaoConcarpeculiar->sql_query_file($oDadosEstrutura->db77_estrut);
    $rsConcarPeculiar   = $oDaoConcarpeculiar->sql_record($sSqlConcarpeculiar);
    if ($oDaoConcarpeculiar->numrows == 0) {

      $sMsg  = "É necessário incluir no cadastro uma CP/CA de código {$oDadosEstrutura->db77_estrut} ";
      $sMsg .= "com o título 'NÃO APLICÁVEL'. Para realizar o Cadastro Acesse:\n";
      $sMsg .= db_stdClass::getCaminhoMenu(8833).".";
      throw new Exception($sMsg);
    }
    $iCaracteristicaPadrao = $oDadosEstrutura->db77_estrut;
    $aQuadroEstimativa     = array();

    /*
     * Receitas
     */
    $oPpaLei        = db_utils::fieldsMemory($rsPpaLei, 0);
    $sWhere         = "o57_anousu  = {$oPpaLei->o01_anoinicio} and (c61_instit in({$this->sInstituicoes})
                                                                or c61_reduz is null)";
    $sWhereRec = "";
    if ($sEstrutural != "") {
       $sWhere .= " and o57_fonte ilike '{$sEstrutural}%'";
    }
    if (is_array($aRecursos) && count($aRecursos) > 0) {

      $sWhereRec = " and (c61_codigo in (".implode(", ",$aRecursos).") or c61_codigo is null)";
      $sWhere   .= $sWhereRec;

    }
    $iAnoBaseInicio = $oPpaLei->o01_anoinicio - ppa::ANOS_PREVISAO_CALCULO;
    $oDaoFonteDados = db_utils::getDao("orcfontes");

    $sSqlFonte      = " SELECT distinct o57_codfon,";
    $sSqlFonte     .= "        c60_estrut,";
    $sSqlFonte     .= "        c61_reduz,";
    $sSqlFonte     .= "        coalesce(o06_concarpeculiar, '000') as o70_concarpeculiar,";
    $sSqlFonte     .= "        o57_descr,";
    $sSqlFonte     .= "        o15_descr,";
    $sSqlFonte     .= "        c61_codigo,";
    $sSqlFonte     .= "        o60_codfon,";
    $sSqlFonte     .= "        fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao";
    $sSqlFonte     .= "   from orcfontes   ";
    $sSqlFonte     .= "        left join  ppaestimativareceita  on o57_anousu = o06_anousu ";
    $sSqlFonte     .= "                                        and o57_codfon = o06_codrec";
    $sSqlFonte     .= "        inner join conplano        on conplano.c60_codcon = orcfontes.o57_codfon";
    $sSqlFonte     .= "                                  and conplano.c60_anousu = orcfontes.o57_anousu";
    $sSqlFonte     .= "        left  join conplanoreduz   on conplanoreduz.c61_codcon = conplano.c60_codcon";
    $sSqlFonte     .= "                                  and conplanoreduz.c61_anousu = conplano.c60_anousu";
    $sSqlFonte     .= "                                  and conplanoreduz.c61_instit in(".$this->getInstituicoes().")";
    $sSqlFonte     .= "        left join orctiporec       on o15_codigo = c61_codigo";
    $sSqlFonte     .= "        left join orcfontesdes     on o60_codfon = o57_codfon";
    $sSqlFonte     .= "                                  and o60_anousu = o57_anousu";
    $sSqlFonte     .= "  where {$sWhere}";
    $sSqlFonte     .= "  order by c60_estrut";

    $rsFontes       = $oDaoFonteDados->sql_record(analiseQueryPlanoOrcamento($sSqlFonte));
    if ($oDaoFonteDados->numrows == 0) {
       throw new Exception("Não foi encontrado estimativas para  o estrutural informado.",2);
    }

    $aFontes         = db_utils::getCollectionByRecord($rsFontes);
    $oDaoPPAReceita  = db_utils::getDao("ppaestimativareceita");
    $aEstruturaisPai = array();
    $iIndex          = 0;
    /*
     * Percorremos os dados da receita e fizemos os calculos da projecao
     */
    foreach ($aFontes as $oFonteDados) {

        $oLinhaQuadro                    = new stdClass();
        $oLinhaQuadro->iCodigo           = $oFonteDados->o57_codfon;
        $oLinhaQuadro->sDescricao        = urlencode($oFonteDados->o57_descr);
        $oLinhaQuadro->iRecurso          = $oFonteDados->c61_codigo;
        $oLinhaQuadro->iEstrutural       = $oFonteDados->c60_estrut;
        $oLinhaQuadro->lDeducao          = $oFonteDados->deducao;
        $oLinhaQuadro->sDescricaoRecurso = urlencode($oFonteDados->o15_descr);
        $oLinhaQuadro->iReduz            = $oFonteDados->c61_reduz;
        $oLinhaQuadro->iConcarPeculiar   = "{$oFonteDados->o70_concarpeculiar}";
        if ($oFonteDados->o70_concarpeculiar == 0) {
          $oLinhaQuadro->iConcarPeculiar = $iCaracteristicaPadrao;
        }
        $oLinhaQuadro->aDesdobramentos   = array();
        $oLinhaQuadro->lDesdobra = $oFonteDados->o60_codfon != ""?true:false;
        if ($oFonteDados->o70_concarpeculiar != 0) {
          $oLinhaQuadro->lDesdobra = false;
        }
        /*
         * Caso o estrutural seja um desdobramento, acrescentamos ele na linha do quadro
         * que representa seu estrutural pai.
         */
        if ($oLinhaQuadro->lDesdobra) {

          $sEstruturalNivel = db_le_mae_rec($oFonteDados->c60_estrut);
          if (isset($aQuadroEstimativa[$aEstruturaisPai[$sEstruturalNivel]])) {
            $aQuadroEstimativa[$aEstruturaisPai[$sEstruturalNivel]]->aDesdobramentos[] = $oFonteDados->c60_estrut;
          }
        }

        /*
         * Montamos a base de calculo
        */
        $oLinhaQuadro->aBaseCalculo  = array();
        for ($iAno = $iAnoBaseInicio; $iAno < $oPpaLei->o01_anoinicio; $iAno++) {

          $oLinhaQuadro->aBaseCalculo["{$iAno}"] = 0;
          /**
           * calculamos o valor somente para as contas analiticas.
           * e somamos os valores para a contamae;
           */
          if ($oFonteDados->c61_reduz != "") {
            $nValorReceita = round($this->getValorAno($oFonteDados, $iAno, true,$sWhereRec),2);
          } else {
            $nValorReceita = round($this->getValorAno($oFonteDados, $iAno,
                                                      true,$sWhereRec,
                                                      $this->criaContaMae($oFonteDados->c60_estrut)),2);
          }
          $oLinhaQuadro->aBaseCalculo["{$iAno}"] = $nValorReceita;

        }

        if (count($oLinhaQuadro->aBaseCalculo) > 0) {

          $nValorCalculo = array_sum($oLinhaQuadro->aBaseCalculo) / count($oLinhaQuadro->aBaseCalculo);
          $oLinhaQuadro->nMediaBase = round($nValorCalculo, 2);

        } else {
          $oLinhaQuadro->nMediaBase = 0;
        }
        $oLinhaQuadro->aEstimativas  = array();
        /*
         * Valores das estimativas (intervalo de anos da lei do ppa)
         */
        for ($iAno = $oPpaLei->o01_anoinicio; $iAno <= $oPpaLei->o01_anofinal; $iAno++) {

          $oLinhaQuadro->aEstimativas["{$iAno}"] = 0;
          if ($oFonteDados->c61_reduz != "") {

            $sSqlReceita = $oDaoPPAReceita->sql_query_soma_receita_ano($iAno,
                                                                       $oFonteDados->o57_codfon,
                                                                       null,
                                                                       $this->iCodigoVersao,
                                                                       false,
                                                                       null,
                                                                       $this->getInstituicoes(),
                                                                       $oFonteDados->o70_concarpeculiar);

          } else {

            $sSqlReceita = $oDaoPPAReceita->sql_query_soma_receita_ano($iAno,
                                                                       null,
                                                                       $this->criaContaMae($oFonteDados->c60_estrut),
                                                                       $this->iCodigoVersao, false,$sWhereRec,
                                                                       $this->getInstituicoes(),
                                                                       $oFonteDados->o70_concarpeculiar);
          }

          $rsSoma      = $oDaoPPAReceita->sql_record($sSqlReceita);
          if ($oDaoPPAReceita->numrows > 0) {

            $nValorReceita = db_utils::fieldsMemory($rsSoma, 0)->valorreceita;
            $nValor = round($nValorReceita);
            if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
              $nValor = round($nValorReceita, 2);
            }
            $oLinhaQuadro->aEstimativas["{$iAno}"] = "{$nValor}";
          }
        }

        array_push($aQuadroEstimativa, $oLinhaQuadro);

        if ($oFonteDados->c61_reduz == "") {
          $aEstruturaisPai[$oFonteDados->c60_estrut] = $iIndex;
        }
        $iIndex++;

    }
    return $aQuadroEstimativa;
  }

  function saveEstimativas($iCodCon, $iAno, $nValor, $iTipo, $iConcarpeculiar=0) {

    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
    $oParametroOrcamento = $aParametros[0];
    /*
     * pesquisamos na ppaestiomativa |receita|despesa, conforme o tipo ,
     * para atualizarmos os valores da estimativa
     */
    $oDaoPPAEstimativa = db_utils::getDao("ppaestimativa");
    $nValorSalvar =  round($nValor);
    if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
      $nValorSalvar  = round($nValor, 2);
    }
    if ($iTipo == 1) {

      $oDaoPPAReceita = db_utils::getDao("ppaestimativareceita");
      $sWhere         = "o06_codrec = {$iCodCon} and o06_anousu = {$iAno} ";
      $sWhere        .= "and o05_ppaversao      = {$this->iCodigoVersao} ";
      $sWhere        .= "and o06_ppaversao      = {$this->iCodigoVersao} ";
      $sWhere        .= "and o06_concarpeculiar = '{$iConcarpeculiar}' ";
      $sSqlEstimativa = $oDaoPPAReceita->sql_query(null,"*",null,$sWhere);
      $rsEstimativa   = $oDaoPPAReceita->sql_record($sSqlEstimativa);
      if ($oDaoPPAReceita->numrows == 1) {

        $oEstimativa  = db_utils::fieldsMemory($rsEstimativa, 0);
        $oDaoPPAEstimativa->o05_sequencial = $oEstimativa->o05_sequencial;
        $oDaoPPAEstimativa->o05_valor      = "{$nValorSalvar}";
        $oDaoPPAEstimativa->alterar($oEstimativa->o05_sequencial);
        if ($oDaoPPAEstimativa->erro_status == 0) {
          throw new Exception("Não foi possível salvar estimativas!",2);
        }
      } else {

        $oReceita = new stdClass();
        $oReceita->nValor          = "{$nValorSalvar}";
        $oReceita->iAno            = $iAno;
        $oReceita->iCodCon         = $iCodCon;
        $oReceita->iConcarPeculiar = "$iConcarpeculiar";
        $this->adicionarEstimativa($oReceita);

      }
    }
  }
 /**
  * processa as estimativas do ano .
  *
  * @param integer $iCodCon codigo da conta no plano de contas
  * @param integer $iAno anoi a ser processado
  * @param integer $iConcarpeculiar caracteristica peculiar
  * @return float valor processado para a conta.
  */
  function processarEstimativas($iCodCon, $iAno, $iConcarpeculiar = 0) {

    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
     $oParametroOrcamento = $aParametros[0];
    /*
     * somamos todos as bases dos anos anteriores,
     * e comecamos a calcular as estimativas baseados na media dos valores encontrados
     */
    $oDaoPPalei = db_utils::getDao("ppaversao");
    $sSqlPPalei = $oDaoPPalei->sql_query($this->iCodigoVersao);
    $rsPpaLei   = $oDaoPPalei->sql_record($sSqlPPalei);
    $oPpaLei    = db_utils::fieldsMemory($rsPpaLei, 0);


    $sWhere         = "";
    if ($iCodCon != null) {
      $sWhere .= " and o06_codrec = {$iCodCon}";
    }
    $sSqlValorBase  = " SELECT o57_codfon,round(sum(o05_valor)/".ppa::ANOS_PREVISAO_CALCULO.", 2) as vlrbase";
    $sSqlValorBase .= "   from  ppaestimativareceita ";
    $sSqlValorBase .= "         inner join ppaestimativa on o06_ppaestimativa = o05_sequencial ";
    $sSqlValorBase .= "         inner join orcfontes     on o06_codrec        = o57_codfon     ";
    $sSqlValorBase .= "                                 and o57_anousu        = o06_anousu     ";
    $sSqlValorBase .= "      inner join conplano  on  o57_codfon = c60_codcon";
    $sSqlValorBase .= "                          and o57_anousu = c60_anousu";
    $sSqlValorBase .= "      inner join conplanoreduz  on  c60_codcon = c61_codcon";
    $sSqlValorBase .= "                          and c61_anousu = c60_anousu";
    $sSqlValorBase .= "    where o05_ppaversao      = {$this->iCodigoVersao}                              ";
    $sSqlValorBase .= "      and o06_ppaversao      = {$this->iCodigoVersao}                              ";
    $sSqlValorBase .= "      and o06_concarpeculiar = '{$iConcarpeculiar}'                             ";
    $sSqlValorBase .= "      and o05_base           = true  {$sWhere}                                  ";
    $sSqlValorBase .= "      and c61_instit         = ".db_getsession("DB_instit");
    $sSqlValorBase .= " group by o57_codfon order by o57_codfon                      ";

    $rsValores      = db_query(analiseQueryPlanoOrcamento($sSqlValorBase));
    if (!$rsValores || pg_num_rows($rsValores) == 0) {
      throw new Exception("Não foram encontradas bases de calculo.",1);
    }

    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaReceita = db_utils::getDao("ppaestimativareceita");
    $aValoresBases             = db_utils::getCollectionByRecord($rsValores);
    foreach ( $aValoresBases as $oValorBase) {

      $nValorAnterior = 0;
      $nValor         = 0;
      /*
       * Verificamos se a receita existe no exercicio solicitado
       */
      if ($this->temReceitaNoAno($oValorBase->o57_codfon,$iAno)) {

        /**
         * Verificamos se a receita ja foi estimada. caso sim, apenas alteramos seu valor
         */
        $iAnoBase    = ($iAno);
        if ($iAnoBase > $oPpaLei->o01_anoinicio) {

          $iAnoBase    = ($iAno-1);
          $sSqlEstimativa     =  $oDaoPppaEstimativaReceita->sql_query(null,
                                                                     "ppaestimativa.*",
                                                                      null,
                                                                      "o06_anousu            = {$iAnoBase}
                                                                      and o06_codrec         = {$oValorBase->o57_codfon}
                                                                      and o05_ppaversao      = {$this->iCodigoVersao}
                                                                      and o06_concarpeculiar = '{$iConcarpeculiar}'
                                                                      and o06_ppaversao      = {$this->iCodigoVersao}"
                                                                      );
          $rsEstimativa       = $oDaoPppaEstimativaReceita->sql_record($sSqlEstimativa);
          if ($oDaoPppaEstimativaReceita->numrows > 0) {

            $oValor = db_utils::fieldsMemory($rsEstimativa, 0);
            $nValor = $oValor->o05_valor;

          }
        }
        /*
         * Calculamos os acrescimos da base de calculo, conforme valores vinculados a conta
         */
        $nValorParametro = ppa::getAcrescimosEstimativa($oValorBase->o57_codfon, $iAno);
        /*
         * Verificamos se a receita possui desdobramento no ano
         * caso isso exista, devemos calcular o valor conforme o valor total da receita,
         * e aplicar o percentual do desdobramento.
         */

        $sSqlDesdobramento  = " SELECT o57_fonte,o60_codfon,o60_perc";
        $sSqlDesdobramento .= "   from orcfontesdes ";
        $sSqlDesdobramento .= "        inner join  orcfontes on o57_codfon = o60_codfon";
        $sSqlDesdobramento .= "                             and o57_anousu = o60_anousu";
        $sSqlDesdobramento .= "  where o60_anousu = {$iAno} ";
        $sSqlDesdobramento .= "    and o60_codfon = {$oValorBase->o57_codfon}";
        $rsDesdobramento    = db_query($sSqlDesdobramento);
        if (pg_num_rows($rsDesdobramento) > 0 &&  ($iAnoBase < $oPpaLei->o01_anoinicio)) {

          $oDaoPPAReceita = db_utils::getDao("ppaestimativareceita");
          $oDesdobramento = db_utils::fieldsMemory($rsDesdobramento,0);
          $iReceita       = $this->criaContaMae(db_le_mae_rec($oDesdobramento->o57_fonte));
          $sSqlReceita  = " SELECT sum(o05_valor)/".ppa::ANOS_PREVISAO_CALCULO." as valorreceita";
          $sSqlReceita .= "   from  ppaestimativareceita ";
          $sSqlReceita .= "         inner join ppaestimativa on o06_ppaestimativa = o05_sequencial ";
          $sSqlReceita .= "         inner join orcfontes     on o06_codrec        = o57_codfon     ";
          $sSqlReceita .= "                                 and o57_anousu        = 2009     ";
          $sSqlReceita .= "      inner join conplanoreduz        on o06_codrec    = c61_codcon ";
          $sSqlReceita .= "                                     and c61_anousu    = 2009";
          $sSqlReceita .= "    where o05_ppaversao = {$this->iCodigoVersao}                              ";
          $sSqlReceita .= "      and o06_ppaversao = {$this->iCodigoVersao}   ";
          $sSqlReceita .= "      and o06_concarpeculiar = '{$iConcarpeculiar}'                      ";
          $sSqlReceita .= "      and o05_base   = true                                             ";
          $sSqlReceita .= "      and o57_fonte  like '{$iReceita}%'                                    ";
          $sSqlReceita .= "   and  c61_instit   = ".db_getsession("DB_instit");
          $rsValorMae     = db_query(analiseQueryPlanoOrcamento($sSqlReceita));
          $nValorContaMae = db_utils::fieldsMemory($rsValorMae, 0)->valorreceita;
          $nValorCalcular = @($nValorContaMae*$oDesdobramento->o60_perc)/100;
          if ($nValorCalcular > 0) {
             $nValor  = $nValorCalcular;
          }
        }

        $iTipoCalculo = ppa::getTipoCalculo($oValorBase->o57_codfon, $iAno);
        if ($iTipoCalculo == 1 && $nValor == 0) {
          $nValor = $nValor;
        }

        if ($iTipoCalculo == ppa::TIPO_CALCULO_EXERCICIO_ATUAL && $oPpaLei->o01_anoinicio == $iAno) {
          $nValor = $this->getValorReEstimativaExercicioAtual($oValorBase->o57_codfon, $iAno);
        }

        if ($nValorParametro > 0) {
           $nValor = $nValor * $nValorParametro;
        }

        /**
         * Verificamos se a receita ja foi estimada. caso sim, apenas alteramos seu valor
         */
        $sSqlEstimativa     =  $oDaoPppaEstimativaReceita->sql_query(null,
                                                                     "ppaestimativa.*",
                                                                      null,
                                                                      "o06_anousu = {$iAno}
                                                                      and o06_codrec = {$oValorBase->o57_codfon}
                                                                      and o05_ppaversao = {$this->iCodigoVersao}
                                                                      and o06_concarpeculiar = '{$iConcarpeculiar}'
                                                                      and o06_ppaversao = {$this->iCodigoVersao}"
                                                                      );
         $rsEstimativa = $oDaoPppaEstimativaReceita->sql_record($sSqlEstimativa);
         $nValorSalvar = round($nValor);
         if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
           $nValorSalvar = round($nValor, 2);
         }
         if ($oDaoPppaEstimativaReceita->numrows > 0) {

           $oEstimativa                           = db_utils::fieldsMemory($rsEstimativa, 0);
           $oDaoPppaEstimativa->o05_sequencial    = $oEstimativa->o05_sequencial;
           $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
           $oDaoPppaEstimativa->alterar($oEstimativa->o05_sequencial);
           if ($oDaoPppaEstimativa->erro_status == 0 ){
              throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao incluir estimativa.",2);
           }
         } else {

           $oDaoPppaEstimativa->o05_base          = "false";
           $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
           $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
           $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
           $oDaoPppaEstimativa->incluir(null);
           if ($oDaoPppaEstimativa->erro_status == 0 ){
             throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao incluir estimativa.",2);
           }

           $oDaoPppaEstimativaReceita->o06_anousu         = $iAno;
           $oDaoPppaEstimativaReceita->o06_codrec         = $oValorBase->o57_codfon;
           $oDaoPppaEstimativaReceita->o06_ppaversao      = $this->iCodigoVersao;
           $oDaoPppaEstimativaReceita->o06_concarpeculiar = "{$iConcarpeculiar}";
           $oDaoPppaEstimativaReceita->o06_ppaestimativa  = $oDaoPppaEstimativa->o05_sequencial;
           $oDaoPppaEstimativaReceita->incluir(null);
           if ($oDaoPppaEstimativaReceita->erro_status == 0 ) {
             throw new Exception("Erro ao processar Receita {$oValorBase->o57_codfon} erro ao incluir estimativa.\n$oDaoPppaEstimativaReceita->erro_msg",3);
           }
         }
      }
    }
    return $nValor;
  }

  function criaContaMae($string) {

     return ppa::criaContaMae($string);
  }
  function getDesdobramentos($iEstrutural, $iAno) {


    $sSqlDesdobramentos  = " SELECT o57_codfon,o60_perc,o57_fonte  ";
    $sSqlDesdobramentos .= "   from orcfontesdes ";
    $sSqlDesdobramentos .= "        inner join orcfontes on o57_codfon = o60_codfon " ;
    $sSqlDesdobramentos .= "                            and o60_anousu = o57_anousu ";
    $sSqlDesdobramentos .= "  where o57_anousu = {$iAno} ";
    $sSqlDesdobramentos .= "    and o57_fonte like '{$iEstrutural}%'";
    $rsDesdobramentos    = db_query($sSqlDesdobramentos);
    $aDesdobramentos     = array();
    if ($rsDesdobramentos && pg_num_rows($rsDesdobramentos) > 0) {
      $aDesdobramentos = db_utils::getCollectionByRecord($rsDesdobramentos);
    }
    return $aDesdobramentos;
  }

  /**
   * Adiciona uma Estimativa manual ao ppa
   *
   * @param objetc $oEstimativa
   */
  function adicionarEstimativa($oEstimativa) {


    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"));
    }
    $oParametroOrcamento = $aParametros[0];
    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaReceita = db_utils::getDao("ppaestimativareceita");

    $sSqlReceita   = $oDaoPppaEstimativaReceita->sql_query(null,
                                                           "*",
                                                           null,
                                                           "o06_codrec={$oEstimativa->iCodCon}
                                                           and o06_anousu={$oEstimativa->iAno}
                                                           and o05_ppaversao = {$this->iCodigoVersao}
                                                           and o06_concarpeculiar =  '{$oEstimativa->iConcarPeculiar}'
                                                           and o06_ppaversao = {$this->iCodigoVersao}"
                                                           );
    $rsReceita  = $oDaoPppaEstimativaReceita->sql_record($sSqlReceita);
    if ($oDaoPppaEstimativaReceita->numrows > 0) {
      throw new Exception("Receita {$oEstimativa->iCodCon} já estimada para o ano {$oEstimativa->iAno}.");
    }
    /**
     * Caso a caracterista peculiar for diferente de "0" ,
     * devemos verificar se é uma conta de deducao.
     * apenas contas de deducao possui mais de uma carecteristica peculiar
     */
    if ($oEstimativa->iConcarPeculiar != 0) {

      $sSqlTipoConta  = "select fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao";
      $sSqlTipoConta .= "  from orcfontes ";
      $sSqlTipoConta .= " where o57_codfon = {$oEstimativa->iCodCon}";
      $sSqlTipoConta .= "   and o57_anousu = {$oEstimativa->iAno}";
      $rsTipoConta    = analiseQueryPlanoOrcamento(db_query($sSqlTipoConta), $oEstimativa->iAno);
      if (pg_num_rows($rsTipoConta) > 0) {

        $oTipoConta = db_utils::fieldsMemory($rsTipoConta, 0);
        if ($oTipoConta->deducao == "f") {

          throw new Exception("Erro ao processar Receita {$oEstimativa->iCodCon} erro ao incluir estimativa.
          \nApenas contas do grupo 9 pode ter caracteristica peculiar",3);

        }
      }
    }
    if ($oEstimativa->iAno < $this->oDadosLei->o01_anoinicio ) {
      $oDaoPppaEstimativa->o05_base          = "true";
    } else {
      $oDaoPppaEstimativa->o05_base          = "false";
    }
    $nValorSalvar = round($oEstimativa->nValor);
    if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
      $nValorSalvar = round($oEstimativa->nValor, 2);
    }
    $oDaoPppaEstimativa->o05_anoreferencia = $oEstimativa->iAno;
    $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
    $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
    $oDaoPppaEstimativa->incluir(null);
    if ($oDaoPppaEstimativa->erro_status == 0 ){
      throw new Exception("Erro ao processar Receita {$oEstimativa->iCodCon} erro ao incluir estimativa.",2);
    }

    $oDaoPppaEstimativaReceita->o06_anousu         = $oEstimativa->iAno;
    $oDaoPppaEstimativaReceita->o06_codrec         = $oEstimativa->iCodCon;
    $oDaoPppaEstimativaReceita->o06_ppaversao      = $this->iCodigoVersao;
    $oDaoPppaEstimativaReceita->o06_concarpeculiar = "$oEstimativa->iConcarPeculiar";
    $oDaoPppaEstimativaReceita->o06_ppaestimativa  = $oDaoPppaEstimativa->o05_sequencial;
    $oDaoPppaEstimativaReceita->incluir(null);
    if ($oDaoPppaEstimativaReceita->erro_status == 0 ){
      throw new Exception("Erro ao processar Receita {$oEstimativa->iCodCon} erro ao incluir estimativa {$oEstimativa->iConcarPeculiar}.
      \n$oDaoPppaEstimativaReceita->erro_msg\n",3);
    }
  }

  private function getValorAno($oLinhaQuadro, $iAno, $lBase,  $sFiltros='', $sEstrututal = null) {

    $nValor          = 0;
    $oDaoPPAReceita  = new cl_ppaestimativareceita();
    if ($this->getInstituicoes() == null || $this->getInstituicoes() == "") {

      $this->setInstituicoes(db_getsession("DB_instit"));
    }

    if ($sEstrututal != null) {

      $oLinhaQuadro->o57_codfon = null;
      $sSqlReceita  = "SELECT sum(o05_valor) as valorreceita ";
      $sSqlReceita .= " from ppaestimativa ";
      $sSqlReceita .= "      inner join ppaestimativareceita on o06_ppaestimativa = o05_sequencial ";
      $sSqlReceita .= "      inner join orcfontes            on o06_codrec        = o57_codfon ";
      $sSqlReceita .= "                                     and o57_anousu        = {$iAno}";
      $sSqlReceita .= "      inner join conplanoreduz        on o06_codrec        = c61_codcon ";
      $sSqlReceita .= "                                     and c61_anousu        = {$iAno}";
      $sSqlReceita .= " where o05_anoreferencia  = {$iAno} ";
      $sSqlReceita .= "   and o05_ppaversao      = {$this->iCodigoVersao} ";
      $sSqlReceita .= "   and o06_ppaversao      = {$this->iCodigoVersao}";
      //$sSqlReceita .= "   and o06_concarpeculiar = '{$oLinhaQuadro->o70_concarpeculiar}' ";
      $sSqlReceita .= "   and o57_fonte like '{$sEstrututal}%'";
      $sSqlReceita .= "   and c61_instit   in(".$this->getInstituicoes().")";

    } else {

      $sSqlReceita     = $oDaoPPAReceita->sql_query_soma_receita_ano($iAno,
                                                                    $oLinhaQuadro->o57_codfon,
                                                                    $sEstrututal,
                                                                    $this->iCodigoVersao,
                                                                    $lBase,
                                                                    $sFiltros,
                                                                    $this->getInstituicoes(),
                                                                    $oLinhaQuadro->o70_concarpeculiar);
    }
    $rsSoma          = $oDaoPPAReceita->sql_record(analiseQueryPlanoOrcamento($sSqlReceita, $iAno));
    if (!$rsSoma) {
      throw new Exception("Não foi possível calcular o valor das receitas. Contate o suporte.");
    }
    $oValor          = db_utils::fieldsMemory($rsSoma, 0);
    $nValor          = $oValor->valorreceita;
    return $nValor;
  }

  /**
   * define as instituições que serão usada para os calculos das projeções
   *
   * @param string $sInstituicoes lista das intituições separadas por ","
   */
  function setInstituicoes($sInstituicoes) {
    $this->sInstituicoes = str_replace("-",",",$sInstituicoes);
  }
  /**
   * retorna as instituições que foram definidas para o uso
   *
   * @return string
   */
  function getInstituicoes() {
    return $this->sInstituicoes;
  }

  /**
   * importa as projeções de receita da perspectiva passada como parametro, dos anos que as leis do ppa
   * possuem em comum
   *
   * @param integer $iPerspectiva codigo da perspectiva a ser importado as projeçoes.
   * @return ppaReceita
   */
  public function importarDadosPerspectiva($iPerspectiva) {

    /**
     * pesquisa o anos da perspectiva
     */
    $oDaoPPaVersao = new cl_ppaversao();
    $sSqlVersao    = $oDaoPPaVersao->sql_query($iPerspectiva);
    $rsVersao      = $oDaoPPaVersao->sql_record($sSqlVersao);
    if ($oDaoPPaVersao->numrows == 0) {

      $sErro  = "Erro [1] - Erro ao verificar existência da perspectiva ($iPerspectiva).\n";
      $sErro .= "Perspectiva não encontrada no sistema.";
      throw new Exception();
    }

    $oDadosLeiBase  = db_utils::fieldsMemory($rsVersao, 0);
    $aAnosVersao    = array();
    $aAnosImportar  = array();
    $aAnosBase      = array();
    $aAnosProcessar = array();
    /**
     * Criamos um array com os anos da lei que usamos como base
     */
    for ($iAno = $oDadosLeiBase->o01_anoinicio; $iAno <=  $oDadosLeiBase->o01_anofinal; $iAno++) {
      $aAnosBase[] = $iAno;
    }
    /**
     * array com os anos da lei atual
     */
    for ($iAno = $this->oDadosLei->o01_anoinicio; $iAno <= $this->oDadosLei->o01_anofinal; $iAno++) {
      $aAnosVersao[] = $iAno;
    }

    /**
     * Criamos o array com os anos iguais,
     */
    foreach ($aAnosVersao as $iAno) {

      if (in_array($iAno, $aAnosBase)) {
        $aAnosImportar[]  = $iAno;
      } else {
        $aAnosProcessar[]  = $iAno;
      }
    }

    /**
     * importamos todas as receitas da perspectica inicial para nova, aonde os anos são compativeis
     */
    /**
     * Consultamos todas as estimativas de receita
     */
    $oDaoPPAEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPPAEstimativareceita = db_utils::getDao("ppaestimativareceita");
    $sAnosMigrar              = implode(", ",$aAnosImportar);
    $sSqlEstimativasreceita   = $oDaoPPAEstimativareceita->sql_query_analitica(null,
                                                                   "ppaestimativareceita.*,
                                                                    ppaestimativa.*",
                                                                    null,
                                                                    "o05_ppaversao={$iPerspectiva}
                                                                     and o05_anoreferencia in ({$sAnosMigrar})
                                                                     and c61_instit = ".db_getsession("DB_instit")."
                                                                     and o05_base is false"
                                                                    );
    $rsEstimativasreceita     = $oDaoPPAEstimativareceita->sql_record($sSqlEstimativasreceita);
    $aItensEstimativasreceita = db_utils::getCollectionByRecord($rsEstimativasreceita);

    foreach ($aItensEstimativasreceita as $oEstimativa) {

      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $this->iCodigoVersao;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaNova->erro_status}", 2);
      }
      $oDaoPPAEstimativaReceitaNova = new cl_ppaestimativareceita;
      $oDaoPPAEstimativaReceitaNova->o06_anousu         = $oEstimativa->o06_anousu;
      $oDaoPPAEstimativaReceitaNova->o06_codrec         = $oEstimativa->o06_codrec;
      $oDaoPPAEstimativaReceitaNova->o06_ppaversao      = $this->iCodigoVersao;
      $oDaoPPAEstimativaReceitaNova->o06_ppaestimativa  = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaReceitaNova->o06_concarpeculiar = "{$oEstimativa->o06_concarpeculiar}";
      $oDaoPPAEstimativaReceitaNova->incluir(null);
      if ($oDaoPPAEstimativaReceitaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaReceitaNova->erro_status}", 2);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaReceitaNova);
    }

    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);
    /**
     * processamos os anos que nao sao iguais
     */
    asort($aAnosProcessar);
    if (isset($aAnosProcessar[0])) {

      $iAnoInicial = $aAnosProcessar[0];
      $iAnoFinal   = end($aAnosProcessar);
      $this->processarEstimativasGlobais($iAnoInicial, $iAnoFinal, true);
    }
    return $this;
  }


  /**
   * Método que busca os valores estimados para o ultimo ano fechado. Este método é utilizado quando o CM estiver
   * configurado para o tipo 2 - Pela reestimativa da receita no exercício atual
   * @param  integer $iCodigoConta
   * @throws Exception
   * @return number
   */
  public function getValorReEstimativaExercicioAtual($iCodigoConta, $iAno) {

    $iAnoBaseTipoCalculo = $iAno - 1;
    $sWhereEstimativa    = "     o05_ppaversao  = {$this->iCodigoVersao}    ";
    $sWhereEstimativa   .= " and o05_base       = true                      ";
    $sWhereEstimativa   .= " and o06_ppaversao  = {$this->iCodigoVersao}    ";
    $sWhereEstimativa   .= " and c61_instit     = ".db_getsession("DB_instit");
    $sWhereEstimativa   .= " and o06_codrec     = {$iCodigoConta} ";
    $sWhereEstimativa   .= " and o06_anousu     = {$iAnoBaseTipoCalculo}    ";

    $oDaoPPAEstimativaReceita = db_utils::getDao('ppaestimativareceita');
    $sSqlEstimativaAnterior   = $oDaoPPAEstimativaReceita->sql_query_estimativa_planoconta(null,
                                                                                           "o05_valor",
                                                                                           null,
                                                                                           $sWhereEstimativa);


    $rsEstimativaAnterior = $oDaoPPAEstimativaReceita->sql_record($sSqlEstimativaAnterior);
    $nValor = 0;
    if ($oDaoPPAEstimativaReceita->numrows > 0) {
	    $nValor = db_utils::fieldsMemory($rsEstimativaAnterior, 0)->o05_valor;
    }
    return $nValor;
  }
}
?>