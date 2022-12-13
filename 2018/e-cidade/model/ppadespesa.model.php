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


class ppaDespesa {

  private $iCodigoVersao = 0;
  private $sInstituicoes = null;
  /**
   *
   */
  function __construct($iCodigoVersao) {

    $this->iCodigoVersao = $iCodigoVersao;
    $oDaoPPaLei       = db_utils::getDao("ppaversao");
    $rsPPalei         = $oDaoPPaLei->sql_record($oDaoPPaLei->sql_query($this->iCodigoVersao));
    $this->oDadosLei  = db_utils::fieldsMemory($rsPPalei, 0);
  }

  function processaBaseCalculo($iAno) {

    require_once(modification("libs/db_liborcamento.php"));
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

    require_once(modification("classes/db_ppadotacao_classe.php"));
    $rsDespesa = $sqlprinc = db_dotacaosaldo(8,
                                             2,
                                             4,true,
                                             "o58_instit = ".db_getsession("DB_instit"),
                                             $iAno,
                                             $dtDataInicial,
                                             $dtDataFinal,
                                             8, 0, false
                                             );

    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $aDespesasAno              = db_utils::getCollectionByRecord($rsDespesa);
    foreach ($aDespesasAno as $oDespesaAno) {


      /**
       * Incluimos a dotacao para o ppa
       */
      $sSqlElemento  = "select o56_codele  ";
      $sSqlElemento .= "  from orcelemento ";
      $sSqlElemento .= " where o56_elemento = '{$oDespesaAno->o58_elemento}'	";
      $sSqlElemento .= "   and o56_anousu   = {$iAno}";
      $rsElemento    = db_query($sSqlElemento);
      if (!$rsElemento) {
         throw new Exception("Erro ao processar estimativa {$oDespesaAno->o58_elemento}\nElemento não encontrado.",1);
      }

      $nValorDespesa = $oDespesaAno->liquidado_acumulado;
      if ($lMediaPonderada) {
        $nValorDespesa =  "".round((($oDespesaAno->liquidado_acumulado/$mesAnterior)*12),5)."";
      }
      /*
       * Verificamos se a receita nao existe no ano
       */
      $oPPADotacao = new cl_ppadotacao();
      $sSqlPpaDotacao  = "select o05_sequencial ";
      $sSqlPpaDotacao .= "  from ppadotacaoorcdotacao";
      $sSqlPpaDotacao .= "       inner join ppadotacao           on o08_sequencial = o19_ppadotacao";
      $sSqlPpaDotacao .= "       inner join ppaestimativadespesa on o08_sequencial = o07_coddot";
      $sSqlPpaDotacao .= "       inner join ppaestimativa        on o05_sequencial = o07_ppaestimativa";
      $sSqlPpaDotacao .= " where o19_coddot = {$oDespesaAno->o58_coddot}";
      $sSqlPpaDotacao .= "   and o05_anoreferencia = {$iAno}";
      $sSqlPpaDotacao .= "   and o05_ppaversao     = {$this->iCodigoVersao}";
      $sSqlPpaDotacao .= "   and o08_ppaversao     = {$this->iCodigoVersao}";
      $rsPPADotacao    = $oPPADotacao->sql_record($sSqlPpaDotacao);
      if ($oPPADotacao->numrows > 0) {

        $oDotacao = db_utils::fieldsMemory($rsPPADotacao, 0);
        $oDaoPppaEstimativa->o05_sequencial    = $oDotacao->o05_sequencial;
        $oDaoPppaEstimativa->o05_valor         = "$nValorDespesa";
        $oDaoPppaEstimativa->alterar($oDotacao->o05_sequencial);
        if ($oDaoPppaEstimativa->erro_status == 0 ){
          throw new Exception("Erro ao processar estimativa {$oDespesaAno->o58_elemento}.",2);
        }

      } else {

        /**
         * consultamos o localizador de gastos da dotacao
         * e característica peculiar
         */
        $iLocalizador          = 0;
        $sSqlVerificaDotacao  = "select o58_localizadorgastos, o58_concarpeculiar";
        $sSqlVerificaDotacao .= "  from orcdotacao ";
        $sSqlVerificaDotacao .= " where o58_coddot = {$oDespesaAno->o58_coddot}";
        $sSqlVerificaDotacao .= "  and o58_anousu = {$iAno}";
        $rsVerificaDotacao    = db_query($sSqlVerificaDotacao);
        if (pg_num_rows($rsVerificaDotacao) == 1) {
          $iLocalizador            = db_utils::fieldsMemory($rsVerificaDotacao, 0)->o58_localizadorgastos;
          $sCaracteristicaPeculiar = db_utils::fieldsMemory($rsVerificaDotacao, 0)->o58_concarpeculiar;
        }
        $oPPADotacao = new cl_ppadotacao();
        $oPPADotacao->o08_ano               = $iAno;
        $oPPADotacao->o08_orgao             = $oDespesaAno->o58_orgao;
        $oPPADotacao->o08_unidade           = $oDespesaAno->o58_unidade;
        $oPPADotacao->o08_funcao            = $oDespesaAno->o58_funcao;
        $oPPADotacao->o08_subfuncao         = $oDespesaAno->o58_subfuncao;
        $oPPADotacao->o08_programa          = $oDespesaAno->o58_programa;
        $oPPADotacao->o08_projativ          = $oDespesaAno->o58_projativ;
        $oPPADotacao->o08_concarpeculiar    = $sCaracteristicaPeculiar;
        $oPPADotacao->o08_elemento          = db_utils::fieldsMemory($rsElemento,0)->o56_codele;
        $oPPADotacao->o08_recurso           = $oDespesaAno->o58_codigo;
        $oPPADotacao->o08_localizadorgastos = "{$iLocalizador}";
        $oPPADotacao->o08_ppaversao         = $this->iCodigoVersao;
        $oPPADotacao->o08_instit            = db_getsession("DB_instit");
        $oPPADotacao->incluir(null);
        if ($oPPADotacao->erro_status == 0) {

           $sErroMessage  = "Erro ao processar estimativa!\n";
           $sErroMessage .= "Erro Técnico: {$oPPADotacao->erro_msg}";
           throw new Exception($sErroMessage, 4);
        }

        $oDaoPPADotacaoOrc  = db_utils::getDao("ppadotacaoorcdotacao");
        $oDaoPPADotacaoOrc->o19_coddot     = $oDespesaAno->o58_coddot;
        $oDaoPPADotacaoOrc->o19_anousu     = $iAno;
        $oDaoPPADotacaoOrc->o19_ppadotacao = $oPPADotacao->o08_sequencial;
        $oDaoPPADotacaoOrc->incluir(null);
        if ($oDaoPPADotacaoOrc->erro_status == 0) {
          throw new Exception("Erro ao processar estimativa {$oDespesaAno->o58_elemento}\n{$oDaoPPADotacaoOrc->erro_msg}",5);
        }

        $oDaoPppaEstimativa->o05_base          = "true";
        $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
        $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
        $oDaoPppaEstimativa->o05_valor         = "$nValorDespesa";
        $oDaoPppaEstimativa->incluir(null);
        if ($oDaoPppaEstimativa->erro_status == 0 ){
           throw new Exception("Erro ao processar despesa {$oDespesaAno->o58_elemento} erro ao incluir estimativa",3);
        }

        $oDaoPppaEstimativaDespesa->o07_anousu        = $iAno;
        $oDaoPppaEstimativaDespesa->o07_coddot        = $oPPADotacao->o08_sequencial;
        $oDaoPppaEstimativaDespesa->o07_ppaestimativa = $oDaoPppaEstimativa->o05_sequencial;
        $oDaoPppaEstimativaDespesa->incluir(null);
        if ($oDaoPppaEstimativa->erro_status == 0 ){
           throw new Exception("Erro ao processar Despesa {$oDespesaAno->o58_elemento} erro ao incluir estimativa",6);
        }
      }
    }
    return true;
  }
  /**
   * Calcula a estimativa global da despesa
   *
   * @param integer $iAnoInicial
   * @param integer $iAnoFinal
   * @param integer $iCodRec
   * @return boolean
   */
  function processarEstimativasGlobais($iAnoInicial, $iAnoFinal, $lForcaCalculoAnterior = false) {

    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"),7);
    }
    $oParametroOrcamento = $aParametros[0];
    /*
     * somamos todos as bases dos anos anteriores,
     * e comecamos a calcular as estimativas baseados na media dos valores encontrados
     */
    $sWhere         = "";
    $sSqlValorBase  = " select o19_coddot, o19_anousu, b.*,";
    $sSqlValorBase .= "  (select round(sum(o05_valor)/ " . ppa::ANOS_PREVISAO_CALCULO . " ,2) ";
    $sSqlValorBase .= "     from ppadotacao dot ";
    $sSqlValorBase .= "          inner join ppaestimativadespesa on dot.o08_sequencial    = o07_coddot";
    $sSqlValorBase .= "          inner join ppaestimativa        on o07_ppaestimativa = o05_sequencial";
    $sSqlValorBase .= "    where dot.o08_programa         = b.o08_programa ";
    $sSqlValorBase .= "      and dot.o08_funcao           = b.o08_funcao";
    $sSqlValorBase .= "      and dot.o08_subfuncao        = b.o08_subfuncao";
    $sSqlValorBase .= "      and dot.o08_orgao            = b.o08_orgao ";
    $sSqlValorBase .= "      and dot.o08_projativ         = b.o08_projativ";
    $sSqlValorBase .= "      and dot.o08_unidade          = b.o08_unidade";
    $sSqlValorBase .= "      and dot.o08_elemento         = b.o08_elemento";
    $sSqlValorBase .= "      and dot.o08_concarpeculiar   = b.o08_concarpeculiar";
    $sSqlValorBase .= "      and dot.o08_localizadorgastos= b.o08_localizadorgastos";
    $sSqlValorBase .= "      and dot.o08_recurso          = b.o08_recurso";
    $sSqlValorBase .= "      and o05_ppaversao            = {$this->iCodigoVersao}";
    $sSqlValorBase .= "      and dot.o08_ppaversao        = {$this->iCodigoVersao}";
    $sSqlValorBase .= "      and o05_base is true ) as vlrbase";
    $sSqlValorBase .= "  from ppadotacaoorcdotacao";
    $sSqlValorBase .= "       inner join ppadotacao  b on o19_ppadotacao = o08_sequencial";
    $sSqlValorBase .= " where o19_anousu = ".($this->oDadosLei->o01_anoinicio - 1);
    $sSqlValorBase .= "   and o08_instit = ".db_getsession("DB_instit");
    $sSqlValorBase .= "   and o08_ppaversao = {$this->iCodigoVersao}";
    $sSqlValorBase .= " order by o19_coddot";
    $rsValores      = db_query($sSqlValorBase);
    if (!$rsValores || pg_num_rows($rsValores) == 0) {
      throw new Exception("Não foram encontradas bases de calculo.",8);
    }

    $oPPADotacao               = db_utils::getDao("ppadotacao");
    $oPPADotacaoOrcDotacao     = db_utils::getDao("ppadotacaoorcdotacao");
    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $aValoresBases             = db_utils::getCollectionByRecord($rsValores);

    foreach ( $aValoresBases as $oValorBase) {

      $nValorAnterior = 0;
      $nValor         = 0;
      for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++) {

        /*
        * Calculamos os acrescimos da base de calculo, conforme valores vinculados a conta
        */
        if ($lForcaCalculoAnterior) {

          if ($iAno > $this->oDadosLei->o01_anoinicio) {

             $iAnoBase    = ($iAno-1);

            $iAnoBase           = ($iAno-1);
            $sSqlEstimativa     =  $oDaoPppaEstimativaDespesa->sql_query_conplano(null,
                                                              "ppaestimativa.*,o08_elemento",
                                                               null,
                                                              "o07_anousu                = {$iAnoBase}
                                                               and o05_ppaversao         = {$this->iCodigoVersao}
                                                               and o08_orgao             = {$oValorBase->o08_orgao}
                                                               and o08_unidade           = {$oValorBase->o08_unidade}
                                                               and o08_funcao            = {$oValorBase->o08_funcao}
                                                               and o08_subfuncao         = {$oValorBase->o08_subfuncao}
                                                               and o08_programa          = {$oValorBase->o08_programa}
                                                               and o08_concarpeculiar    = '{$oValorBase->o08_concarpeculiar}'
                                                               and o08_projativ          = {$oValorBase->o08_projativ}
                                                               and o08_recurso           = {$oValorBase->o08_recurso}
                                                               and o08_elemento          = {$oValorBase->o08_elemento}
                                                               and o08_localizadorgastos = {$oValorBase->o08_localizadorgastos}
                                                               and o08_instit            = {$oValorBase->o08_instit}"
                                                                        );
            $rsEstimativa       = $oDaoPppaEstimativaDespesa->sql_record($sSqlEstimativa);
            if ($oDaoPppaEstimativaDespesa->numrows > 0) {

              $oValor         = db_utils::fieldsMemory($rsEstimativa, 0);
              $nValorAnterior = $oValor->o05_valor;

            }
          }
        }

        $nValorParametro = ppa::getAcrescimosEstimativa($oValorBase->o08_elemento, $iAno);
        $nValor  = $nValorAnterior == 0?$oValorBase->vlrbase:$nValorAnterior;

        /**
         * Verificamos se o tipo de cálculo é pela a média encontrada para o ano em exercício.
         */
        if ($iAnoInicial == $iAno) {

          $iTipoCalculoReceita = ppa::getTipoCalculo($oValorBase->o08_elemento, $iAno);
          if ($iTipoCalculoReceita === ppa::TIPO_CALCULO_EXERCICIO_ATUAL) {

            $nValorRetorno = $this->getValorReEstimativaExercicioAtual($oValorBase, $iAno);
            if ($nValorRetorno != 0) {
              $nValor = $nValorRetorno;
            }
          }
        }

        if ($nValorParametro > 0) {
           $nValor *= $nValorParametro;
        }
        $oPPADotacao = new cl_ppadotacao();
        $sSqlPpaDotacao  = "select o05_sequencial                                                       ";
        $sSqlPpaDotacao .= "  from ppadotacaoorcdotacao                                                 ";
        $sSqlPpaDotacao .= "       inner join ppadotacao           on o08_sequencial = o19_ppadotacao   ";
        $sSqlPpaDotacao .= "       inner join ppaestimativadespesa on o08_sequencial = o07_coddot       ";
        $sSqlPpaDotacao .= "       inner join ppaestimativa        on o05_sequencial = o07_ppaestimativa";
        $sSqlPpaDotacao .= " where o19_coddot            = {$oValorBase->o19_coddot}										";
        $sSqlPpaDotacao .= "   and o08_instit            = ".db_getsession("DB_instit")                  ;
        $sSqlPpaDotacao .= "   and o05_anoreferencia     = {$iAno}                                      ";
        $sSqlPpaDotacao .= "   and o08_ano               = {$iAno}                                      ";
        $sSqlPpaDotacao .= "   and o05_ppaversao         = {$this->iCodigoVersao}                       ";
        $sSqlPpaDotacao .= "   and o08_ppaversao         = {$this->iCodigoVersao}                       ";
        $sSqlPpaDotacao .= "   and o05_ppaversao         = {$this->iCodigoVersao}                       ";
        $sSqlPpaDotacao .= "   and o08_orgao             = {$oValorBase->o08_orgao}                     ";
        $sSqlPpaDotacao .= "   and o08_unidade           = {$oValorBase->o08_unidade}                   ";
        $sSqlPpaDotacao .= "   and o08_funcao            = {$oValorBase->o08_funcao}                    ";
        $sSqlPpaDotacao .= "   and o08_subfuncao         = {$oValorBase->o08_subfuncao}                 ";
        $sSqlPpaDotacao .= "   and o08_programa          = {$oValorBase->o08_programa}                  ";
        $sSqlPpaDotacao .= "   and o08_concarpeculiar    = '{$oValorBase->o08_concarpeculiar}'          ";
        $sSqlPpaDotacao .= "   and o08_projativ          = {$oValorBase->o08_projativ}                  ";
        $sSqlPpaDotacao .= "   and o08_recurso           = {$oValorBase->o08_recurso}                   ";
        $sSqlPpaDotacao .= "   and o08_elemento          = {$oValorBase->o08_elemento}                  ";
        $sSqlPpaDotacao .= "   and o08_localizadorgastos = {$oValorBase->o08_localizadorgastos}         ";
        $sSqlPpaDotacao .= "   and o08_instit            = {$oValorBase->o08_instit}                    ";

        $rsPPADotacao    = $oPPADotacao->sql_record($sSqlPpaDotacao);

        $nValorSalvar = round($nValor);
        if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
           $nValorSalvar = round($nValor, 2);
        }
        if ($oPPADotacao->numrows > 1) {

          $sMsg  = "Erro ao processar despesa {$oValorBase->o08_elemento}({$oValorBase->o19_coddot}/{$iAno}).\n";
          $sMsg .= "Dotacao para o ppa está está vinculada a mais de uma dotacao ({$oPPADotacao->numrows} dotações).\n";
          $sMsg .= "Procedimento cancelado.\nEntrar em contato com suporte tecnico.";
          throw new Exception($sMsg,9);
        }
        if ($oPPADotacao->numrows == 1) {

          $oDotacao                           = db_utils::fieldsMemory($rsPPADotacao, 0);
          $oDaoPppaEstimativa->o05_sequencial = $oDotacao->o05_sequencial;
          $oDaoPppaEstimativa->o05_valor      = "{$nValorSalvar}";
          $oDaoPppaEstimativa->alterar($oDotacao->o05_sequencial);
          if ($oDaoPppaEstimativa->erro_status == 0 ){
            throw new Exception("Erro ao processar despesa {$oValorBase->o08_elemento} erro ao incluir estimativa.",10);
          }

        } else {

          $oPPADotacao->o08_ano               = $iAno;
          $oPPADotacao->o08_orgao             = $oValorBase->o08_orgao;
          $oPPADotacao->o08_unidade           = $oValorBase->o08_unidade;
          $oPPADotacao->o08_funcao            = $oValorBase->o08_funcao;
          $oPPADotacao->o08_subfuncao         = $oValorBase->o08_subfuncao;
          $oPPADotacao->o08_programa          = $oValorBase->o08_programa;
          $oPPADotacao->o08_projativ          = $oValorBase->o08_projativ;
          $oPPADotacao->o08_elemento          = $oValorBase->o08_elemento;
          $oPPADotacao->o08_recurso           = $oValorBase->o08_recurso;
          $oPPADotacao->o08_concarpeculiar    = $oValorBase->o08_concarpeculiar;
          $oPPADotacao->o08_ppaversao         = $this->iCodigoVersao;
          $oPPADotacao->o08_localizadorgastos = "{$oValorBase->o08_localizadorgastos}";
          $oPPADotacao->o08_instit            = db_getsession("DB_instit");
          $oPPADotacao->incluir(null);
          if ($oPPADotacao->erro_status == 0) {

            $sErroMessage  = "Erro ao processar estimativa com elemento {$oValorBase->o08_elemento}.\n";
            $sErroMessage .= "Erro Técnico: {$oPPADotacao->erro_msg}";
            throw new Exception($sErroMessage, 11);
          }

          $oDaoPPADotacaoOrc  = db_utils::getDao("ppadotacaoorcdotacao");
          $oDaoPPADotacaoOrc->o19_coddot     = $oValorBase->o19_coddot;
          $oDaoPPADotacaoOrc->o19_anousu     = $oValorBase->o19_anousu;
          $oDaoPPADotacaoOrc->o19_ppadotacao = $oPPADotacao->o08_sequencial;
          $oDaoPPADotacaoOrc->incluir(null);
          if ($oDaoPPADotacaoOrc->erro_status == 0 ) {
             throw new Exception("Erro ao processar estimativa {$oValorBase->o08_elemento}\n$oDaoPPADotacaoOrc->erro_msg	",12);
          }

          $oDaoPppaEstimativa->o05_base          = "false";
          $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
          $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
          $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
          $oDaoPppaEstimativa->incluir(null);
          if ($oDaoPppaEstimativa->erro_status == 0 ){
             throw new Exception("Erro ao processar Despesa {$oValorBase->o08_elemento} erro ao incluir estimativa.\n$oDaoPppaEstimativa->erro_msg",13);
          }

          $oDaoPppaEstimativaDespesa->o07_anousu        = $iAno;
          $oDaoPppaEstimativaDespesa->o07_coddot        = $oPPADotacao->o08_sequencial;
          $oDaoPppaEstimativaDespesa->o07_ppaestimativa = $oDaoPppaEstimativa->o05_sequencial;
          $oDaoPppaEstimativaDespesa->incluir(null);
          if ($oDaoPppaEstimativaDespesa->erro_status == 0 ){
             throw new Exception("Erro ao processar Despesa {$oValorBase->o18_codele} erro ao incluir estimativa.\n$oDaoPppaEstimativaDespesa->erro_msg",14);
          }

        }
        $nValorAnterior = $nValor;
      }
    }
    return true;
  }

  function temReceitaNoAno($iCodFon, $iAnoRef) {

    $lRetorno  = false;
    $sSql      = "select o57_codfon ";
    $sSql     .= "  from orcfontes";
    $sSql     .= " where o57_codfon = {$iCodFon}";
    $sSql     .= "   and o57_anousu = {$iAnoRef}";
    $rsReceita = db_query($sSql);
    if ($rsReceita && pg_num_rows($rsReceita) > 0) {
      $lRetorno = true;
    }
    return $lRetorno;
  }

  function getQuadroEstimativas($sEstrutural = null, $iNivel = null) {


    $sListaCampos = "distinct fc_estruturaldotacaoppa(o08_ano,o08_sequencial) as c60_estrut,
                              1 as c61_reduz,
                              o08_orgao,
                              fc_finddotacaoppa(".($this->oDadosLei->o01_anoinicio-1).",
                                                o08_sequencial) as o19_coddot,
                              o08_unidade,
                              o08_funcao,
                              o08_subfuncao,
                              o08_concarpeculiar,
                              o08_programa,
                              o08_projativ,
                              o08_elemento,
                              o08_localizadorgastos,
                              o08_recurso";
    $sOrderBy     = "o08_orgao,
                     o08_unidade,
                     o08_funcao,
                     o08_subfuncao,
                     o08_programa,
                     o08_projativ,
                     o08_elemento,
                     o08_localizadorgastos,
                     o08_recurso,
                     o08_concarpeculiar";

    if ($iNivel != null) {

      $oCampos      = $this->getCamposPorNivel($iNivel);
      $sListaCampos = " distinct ".$oCampos->sCampos;
      $sOrderBy     = $oCampos->sOrder;

    }

    $oDaoPPalei   = db_utils::getDao("ppaversao");
    $sSqlPPalei   = $oDaoPPalei->sql_query($this->iCodigoVersao);
    $rsPpaLei     = $oDaoPPalei->sql_record(analiseQueryPlanoOrcamento($sSqlPPalei));
    if ($oDaoPPalei->numrows == 0) {
      throw new Exception("Nao foi encontrado dados da lei", 15);
    }
    $aQuadroEstimativa = array();

    /*
     * Despesas
     */
    $oPpaLei  = db_utils::fieldsMemory($rsPpaLei, 0);
    $sWhere   = "o08_ano  >= ".($oPpaLei->o01_anoinicio-1);

    if ($iNivel != null) {
      $sWhere   = "o08_ano  >= ".($oPpaLei->o01_anoinicio-1);
    }
    if ($sEstrutural != "") {
      $sWhere .= " and o56_elemento like '{$sEstrutural}%'";
    }
   if ($this->getInstituicoes() != null) {
       $sWhere .= " and o08_instit in(".$this->getInstituicoes().")";
    }
    $sWhere        .= " and o05_ppaversao = {$this->iCodigoVersao}";
    $sWhere        .= " and o08_ppaversao = {$this->iCodigoVersao}";
    $iAnoBaseInicio = $oPpaLei->o01_anoinicio - ppa::ANOS_PREVISAO_CALCULO;
    $oDaoFonteDados = db_utils::getDao("ppaestimativadespesa");
    $sSqlFonte      = $oDaoFonteDados->sql_query_conplano(null,
                                                          $sListaCampos,
                                                          $sOrderBy,$sWhere);
    $rsFontes       = $oDaoFonteDados->sql_record($sSqlFonte);
    if ($oDaoFonteDados->numrows == 0) {
       throw new Exception("Não foi encontrado estimativas para  o estrutural informado!",16);
    }

    $aFontes         = db_utils::getCollectionByRecord($rsFontes);
    $aEstruturaisPai = array();
    $iIndex          = 0;
    foreach ($aFontes as $oFonteDados) {

      $oLinhaQuadro                  = new stdClass();
      $oLinhaQuadro->iCodigo         = $oFonteDados->o19_coddot;
      if ($oFonteDados->o19_coddot == "") {
        $oLinhaQuadro->iCodigo = "";
      }

      /**
       * excessão quanto foi solicitado o quadro por nivel, e o nivel for 2 - unidade
       * devemos trazer junto com o codigo o codigo do orgao.
       */
      if ($iNivel != null && $iNivel == 2) {
        $oLinhaQuadro->iCodigo         = $oFonteDados->o08_orgao.".".$oFonteDados->o19_coddot;
      } else if ($iNivel != null && $iNivel == 7) {
        $oLinhaQuadro->iCodigo         = $oFonteDados->o56_elemento;
      }
      $aElementos = explode(".",$oFonteDados->c60_estrut);
      $oLinhaQuadro->sDescricao      = "";
      $oLinhaQuadro->iRecurso        = @$oFonteDados->o08_recurso;
      $oLinhaQuadro->iEstrutural     = $oFonteDados->c60_estrut;
      $oLinhaQuadro->lDeducao        = "f";
      $oLinhaQuadro->subfuncao       = 0;
      $oLinhaQuadro->iConcarPeculiar = 0;
      $oLinhaQuadro->funcao          = 0;
      if (isset($oFonteDados->o08_subfuncao)) {
        $oLinhaQuadro->subfuncao       = $oFonteDados->o08_subfuncao;
      }
      if (isset($oFonteDados->o08_funcao)) {
        $oLinhaQuadro->funcao = $oFonteDados->o08_funcao;
      }
      $oLinhaQuadro->iReduz = 1;
      if ($iNivel == null) {
        $oLinhaQuadro->iElemento = @$aElementos[6];
      } else {
      	$oLinhaQuadro->iElemento = @$oFonteDados->o56_elemento;
      }
      $oLinhaQuadro->aDesdobramentos   = array();
      $oLinhaQuadro->lDesdobra         = false;
      $oLinhaQuadro->aBaseCalculo      = array();
      $oLinhaQuadro->aCodigoEstimativa = array();
      for ($iAno = $iAnoBaseInicio; $iAno < $oPpaLei->o01_anoinicio; $iAno++) {

        $oLinhaQuadro->aBaseCalculo["{$iAno}"] = 0;

        /**
         * calculamos o valor somente para as contas analiticas.
         * e somamos os valores para a contamae;
         */
        $oValorReceita = $this->getValorAno($oFonteDados, $iAno, $iNivel);
        $oLinhaQuadro->aBaseCalculo["{$iAno}"]      = $oValorReceita->valor;
        $oLinhaQuadro->aCodigoEstimativa["{$iAno}"] = $oValorReceita->ppaestimativa;
      }

      if (count($oLinhaQuadro->aBaseCalculo) > 0) {
        $oLinhaQuadro->nMediaBase = "".round(@array_sum($oLinhaQuadro->aBaseCalculo)
                                   /count($oLinhaQuadro->aBaseCalculo),2)."";
      } else {
        $oLinhaQuadro->nMediaBase = 0;
      }
      $oLinhaQuadro->aEstimativas  = array();
      for ($iAno = $oPpaLei->o01_anoinicio; $iAno <= $oPpaLei->o01_anofinal; $iAno++) {

        $oValorReceita = $this->getValorAno($oFonteDados, $iAno, $iNivel);
        $oLinhaQuadro->aEstimativas["{$iAno}"]      = $oValorReceita->valor;
        $oLinhaQuadro->aCodigoEstimativa["{$iAno}"] = $oValorReceita->ppaestimativa;
      }
      array_push($aQuadroEstimativa, $oLinhaQuadro);
      $iIndex++;
    }
    return $aQuadroEstimativa;
  }

  function saveEstimativas($iCodCon, $iAno, $nValor, $iTipo ) {


    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"),17);
    }
    $oParametroOrcamento = $aParametros[0];
    /*
     * pesquisamos na ppaestiomativa |receita|despesa, conforme o tipo ,
     * para atualizarmos os valores da estimativa
     */
    $oDaoPPAEstimativa = db_utils::getDao("ppaestimativa");
    $oDaoPPADespesa = db_utils::getDao("ppaestimativadespesa");
    $sWhere         = "o05_sequencial = {$iCodCon} and o05_anoreferencia = {$iAno} and o05_ppaversao = {$this->iCodigoVersao}";
    $sWhere         .= " and o08_instit = ".db_getsession("DB_instit")." /*and o08_ano = {$iAno}*/ and o08_ppaversao = {$this->iCodigoVersao}";
    $sSqlEstimativa = $oDaoPPADespesa->sql_query_conplano(null,"*",null,$sWhere);
    $rsEstimativa   = $oDaoPPADespesa->sql_record(analiseQueryPlanoOrcamento($sSqlEstimativa));

    if ($oDaoPPADespesa->numrows == 1) {

      $nValorSalvar = round($nValor);
      if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
        $nValorSalvar = round($nValor, 2);
      }
      $oEstimativa  = db_utils::fieldsMemory($rsEstimativa, 0);
      $oDaoPPAEstimativa->o05_sequencial = $oEstimativa->o05_sequencial;
      $oDaoPPAEstimativa->o05_valor      = "{$nValorSalvar}";
      $oDaoPPAEstimativa->alterar($oEstimativa->o05_sequencial);
      if ($oDaoPPAEstimativa->erro_status == 0) {
        throw new Exception("Não foi possível salvar estimativas!",18);
      }
    }
  }

  public function processarEstimativas($iCodCon, $iAno) {


    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"),19);
    }
    $oParametroOrcamento = $aParametros[0];

    /*
     * somamos todos as bases dos anos anteriores,
     * e comecamos a calcular as estimativas baseados na media dos valores encontrados
     */
    $oDaoPPalei = db_utils::getDao("ppaversao");
    $sSqlPPalei = $oDaoPPalei->sql_query($this->iCodigoVersao);
    $rsPpaLei   = $oDaoPPalei->sql_record($sSqlPPalei);
    $oPpaLei        = db_utils::fieldsMemory($rsPpaLei, 0);


    $sWhere         = "";
    if ($iCodCon != null) {
      $sWhere .= " and o19_coddot = {$iCodCon}";
    }
    $sSqlValorBase  = " select o19_coddot, o19_anousu, b.*,";
    $sSqlValorBase .= "  (select round(sum(o05_valor)/ " . ppa::ANOS_PREVISAO_CALCULO . " ,2) ";
    $sSqlValorBase .= "     from ppadotacao dot ";
    $sSqlValorBase .= "          inner join ppaestimativadespesa on dot.o08_sequencial    = o07_coddot";
    $sSqlValorBase .= "          inner join ppaestimativa        on o07_ppaestimativa = o05_sequencial";
    $sSqlValorBase .= "    where dot.o08_programa           = b.o08_programa ";
    $sSqlValorBase .= "      and dot.o08_funcao             = b.o08_funcao";
    $sSqlValorBase .= "      and dot.o08_subfuncao          = b.o08_subfuncao";
    $sSqlValorBase .= "      and dot.o08_orgao              = b.o08_orgao ";
    $sSqlValorBase .= "      and dot.o08_projativ           = b.o08_projativ";
    $sSqlValorBase .= "      and dot.o08_unidade            = b.o08_unidade";
    $sSqlValorBase .= "      and dot.o08_elemento           = b.o08_elemento";
    $sSqlValorBase .= "      and dot.o08_localizadorgastos  = b.o08_localizadorgastos";
    $sSqlValorBase .= "      and dot.o08_concarpeculiar     = b.o08_concarpeculiar";
    $sSqlValorBase .= "      and dot.o08_recurso            = b.o08_recurso";
    $sSqlValorBase .= "      and o05_ppaversao              = {$this->iCodigoVersao}";
    $sSqlValorBase .= "      and o08_ppaversao              = {$this->iCodigoVersao}";
    $sSqlValorBase .= "      and o05_base is true) as vlrbase";
    $sSqlValorBase .= "  from ppadotacaoorcdotacao";
    $sSqlValorBase .= "       inner join ppadotacao  b on o19_ppadotacao = o08_sequencial";
    $sSqlValorBase .= " where o08_ano = ".($oPpaLei->o01_anoinicio - 1)." $sWhere";
    $rsValores      = db_query($sSqlValorBase);
    if (!$rsValores || pg_num_rows($rsValores) == 0) {
      throw new Exception("Não foram encontradas bases de calculo.",20);
    }
    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $oPPADotacao               = db_utils::getDao("ppadotacao");
    $oDaoPPADotacaoOrc         = db_utils::getDao("ppadotacaoorcdotacao");
    $aValoresBases             = db_utils::getCollectionByRecord($rsValores);
    foreach ( $aValoresBases as $oValorBase) {

      $nValorAnterior = 0;
      $nValor         = 0;
      /**
       * Verificamos se a Despesa ja foi estimada. caso sim, apenas alteramos seu valor
       */
      $iAnoBase    = ($iAno);
      if ($iAnoBase > $oPpaLei->o01_anoinicio) {

        $iAnoBase           = ($iAno-1);
        $sSqlEstimativa     =  $oDaoPppaEstimativaDespesa->sql_query_conplano(null,
                                                                    "ppaestimativa.*,o08_elemento",
                                                                     null,
                                                                    "o07_anousu     = {$iAnoBase}
                                                                     and o19_coddot = {$oValorBase->o19_coddot}
                                                                     and o05_ppaversao = {$this->iCodigoVersao}
                                                                     and o08_ppaversao = {$this->iCodigoVersao}"
                                                                    );

        $rsEstimativa       = $oDaoPppaEstimativaDespesa->sql_record($sSqlEstimativa);
        if ($oDaoPppaEstimativaDespesa->numrows > 0) {

          $oValor = db_utils::fieldsMemory($rsEstimativa, 0);
          $nValor = $oValor->o05_valor;

        }
      }
      /*
       * Calculamos os acrescimos da base de calculo, conforme valores vinculados a conta
       */
      $nValorParametro = ppa::getAcrescimosEstimativa($oValorBase->o08_elemento, $iAno);

      $nValor  = $nValor == 0?$oValorBase->vlrbase:$nValor;
      /**
       * Verificamos se o tipo de cálculo é pela a média encontrada para o ano em exercício.
       */
      if ($iAnoBase == $iAno) {

        $iTipoCalculoReceita = ppa::getTipoCalculo($oValorBase->o08_elemento, $iAno);
        if ($iTipoCalculoReceita === ppa::TIPO_CALCULO_EXERCICIO_ATUAL) {

          $nValorRetorno = $this->getValorReEstimativaExercicioAtual($oValorBase, $iAno);
          if ($nValorRetorno != 0) {
            $nValor = $nValorRetorno;
          }
        }
      }

      if ($nValorParametro > 0) {
        $nValor = $nValor * $nValorParametro;
      }
      $nValorSalvar = round($nValor);
      if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
        $nValorSalvar = round($nValor, 2);
      }
      /**
       * Verificamos se a Despesa ja foi estimada. caso sim, apenas alteramos seu valor
       * caso nao existir, incluimos a despesa;
       */
      $sSqlEstimativa   =  $oDaoPppaEstimativaDespesa->sql_query_conplano(null,
                                                                   "ppaestimativa.*",
                                                                    null,
                                                                    "o07_anousu     = {$iAno}
                                                                     and o19_coddot = {$oValorBase->o19_coddot}
                                                                     and o05_ppaversao = {$this->iCodigoVersao}
                                                                     and o08_ppaversao = {$this->iCodigoVersao}"
                                                                   );
      $rsEstimativa      = $oDaoPppaEstimativaDespesa->sql_record($sSqlEstimativa);
      if ($oDaoPppaEstimativaDespesa->numrows > 0) {

        $oEstimativa                           = db_utils::fieldsMemory($rsEstimativa, 0);
        $oDaoPppaEstimativa->o05_sequencial    = $oEstimativa->o05_sequencial;
        $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
        $oDaoPppaEstimativa->alterar($oEstimativa->o05_sequencial);
        if ($oDaoPppaEstimativa->erro_status == 0 ){
          throw new Exception("Erro ao processar Despesa {$oValorBase->o19_coddot} erro ao incluir estimativa.",21);
        }
      } else {

        $oPPADotacao->o08_ano               = $iAno;
        $oPPADotacao->o08_orgao             = $oValorBase->o08_orgao;
        $oPPADotacao->o08_unidade           = $oValorBase->o08_unidade;
        $oPPADotacao->o08_funcao            = $oValorBase->o08_funcao;
        $oPPADotacao->o08_subfuncao         = $oValorBase->o08_subfuncao;
        $oPPADotacao->o08_programa          = $oValorBase->o08_programa;
        $oPPADotacao->o08_projativ          = $oValorBase->o08_projativ;
        $oPPADotacao->o08_elemento          = $oValorBase->o08_elemento;
        $oPPADotacao->o08_recurso           = $oValorBase->o08_recurso;
        $oPPADotacao->o08_ppaversao         = $this->iCodigoVersao;
        $oPPADotacao->o08_localizadorgastos = "{$oValorBase->o08_localizadorgastos}";
        $oPPADotacao->o08_concarpeculiar    = $oValorBase->o08_concarpeculiar;
        $oPPADotacao->o08_instit            = db_getsession("DB_instit");
        $oPPADotacao->incluir(null);
        if ($oPPADotacao->erro_status == 0) {

           $sErroMessage  = "Erro ao processar estimativa com elemento {$oValorBase->o08_elemento}.\n";
           $sErroMessage .= "Erro Técnico: {$oPPADotacao->erro_msg}";
           throw new Exception($sErroMessage, 22);
        }

        $oDaoPPADotacaoOrc  = db_utils::getDao("ppadotacaoorcdotacao");
        $oDaoPPADotacaoOrc->o19_coddot     = $oValorBase->o19_coddot;
        $oDaoPPADotacaoOrc->o19_anousu     = $oValorBase->o19_anousu;
        $oDaoPPADotacaoOrc->o19_ppadotacao = $oPPADotacao->o08_sequencial;
        $oDaoPPADotacaoOrc->incluir(null);
        if ($oDaoPPADotacaoOrc->erro_status == 0 ) {
           throw new Exception("Erro ao processar estimativa {$oValorBase->o08_elemento}\n$oDaoPPADotacaoOrc->erro_msg  ",23);
        }

        $oDaoPppaEstimativa->o05_base          = "false";
        $oDaoPppaEstimativa->o05_anoreferencia = $iAno;
        $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
        $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
        $oDaoPppaEstimativa->incluir(null);
        if ($oDaoPppaEstimativa->erro_status == 0 ){
           throw new Exception("Erro ao processar Despesa {$oValorBase->o08_elemento} erro ao incluir estimativa.\n$oDaoPppaEstimativa->erro_msg",24);
        }

        $oDaoPppaEstimativaDespesa->o07_anousu        = $iAno;
        $oDaoPppaEstimativaDespesa->o07_coddot        = $oPPADotacao->o08_sequencial;
        $oDaoPppaEstimativaDespesa->o07_ppaestimativa = $oDaoPppaEstimativa->o05_sequencial;
        $oDaoPppaEstimativaDespesa->incluir(null);
        if ($oDaoPppaEstimativaDespesa->erro_status == 0 ){
           throw new Exception("Erro ao processar Despesa {$oValorBase->o18_codele} erro ao incluir estimativa.\n$oDaoPppaEstimativaDespesa->erro_msg",25);
        }
      }
    }
    return $nValor;
  }

  function criaContaMae($string) {

    $stringnova = "";
    $string = strrev($string);
    for ($i = 0;  $i < strlen($string);$i++) {

      $stringnova =  substr($string, $i,1);
      if ($stringnova != '0') {

        $string = (substr(strrev($string),0,strlen($string)-$i));
        break;
      }

      }
    return $string;
  }
  function getDesdobramentos($iEstrutural, $iAno) {
    return array();
  }

  /**
   * Calcula o valor da estimativa de cada ano
   *
   * @param object $oLinhaQuadro objeto com os valores da dotacao
   * @param integer $iAno ano
   * @param integer $iNivel nive de agrupamento
   * @return float
   */
  function getValorAno($oLinhaQuadro, $iAno, $iNivel = null) {

    $sSql     = "select ";
    $sSql    .= ($iNivel == null) ? "o05_sequencial as codigo_estimativa, " : '';
    $sSql    .= "       coalesce(sum(o05_valor),0) as valor";
    $sSql    .= "  from ppaestimativa ";
    $sSql    .= "       inner join ppaestimativadespesa on o05_sequencial = o07_ppaestimativa ";
    $sSql    .= "       inner join ppadotacao           on o08_sequencial = o07_coddot        ";
    $sSql    .= " where o05_anoreferencia    = {$iAno}";
    $sSql    .= "   and o05_ppaversao = {$this->iCodigoVersao}";
    $sSql    .= "   and o08_ppaversao = {$this->iCodigoVersao}";
    if ($this->getInstituicoes() != null) {
      $sSql .= " and o08_instit in(".$this->getInstituicoes().")";
    }
    if ($iNivel == null) {

      $sSql    .= "   and o08_orgao     = {$oLinhaQuadro->o08_orgao}";
      $sSql    .= "   and o08_unidade   = {$oLinhaQuadro->o08_unidade}";
      $sSql    .= "   and o08_funcao    = {$oLinhaQuadro->o08_funcao}";
      $sSql    .= "   and o08_subfuncao = {$oLinhaQuadro->o08_subfuncao}";
      $sSql    .= "   and o08_programa  = {$oLinhaQuadro->o08_programa}";
      $sSql    .= "   and o08_elemento  = {$oLinhaQuadro->o08_elemento}";
      $sSql    .= "   and o08_recurso   = {$oLinhaQuadro->o08_recurso}";
      $sSql    .= "   and o08_projativ  = {$oLinhaQuadro->o08_projativ}";
      $sSql    .= "   and o08_localizadorgastos   = {$oLinhaQuadro->o08_localizadorgastos}";

    }
    /**
     * ATENCAO :
     * A Propriedade o19_coddot leva sempre o valor = igual ao campo dp nivel.
     * ex.:  nivel = 1 oLinhaQuadro->o19_coddot VAI ser o valor do campo o08_orgao;
     * esse campo é retorno via db_utils,
     */
    switch ($iNivel) {

      case 1:

        $sSql  .= "   and o08_orgao     = {$oLinhaQuadro->o19_coddot}";
        break;

      case 2:

        $sSql .= "   and o08_unidade   = {$oLinhaQuadro->o19_coddot} and o08_orgao = {$oLinhaQuadro->o08_orgao}";
        break;

      case 3:

        $sSql  .= "   and o08_funcao    = {$oLinhaQuadro->o19_coddot}";
        break;

      case 4:

        $sSql  .= "   and o08_subfuncao = {$oLinhaQuadro->o19_coddot}";
        break;

      case 5:

        $sSql    .= "   and o08_programa  = {$oLinhaQuadro->o19_coddot}";
        break;

      case 6:

        $sSql   .= "   and o08_projativ  = {$oLinhaQuadro->o19_coddot}";
        break;

      case 7:

         $sSql    .= "   and o08_elemento  = {$oLinhaQuadro->o19_coddot}";
        break;

      case 8:

        $sSql   .= "   and o08_recurso   = {$oLinhaQuadro->o19_coddot}";
        break;

    }

    if ($iNivel == null) {
      $sSql .= " group by o05_sequencial ";
    }

    $rsValor  = db_query($sSql);

    $oStdRetorno = (object) array(
        'valor' => 0,
        'ppaestimativa' => null
      );

    if ($rsValor) {

      $oValor = db_utils::fieldsMemory($rsValor, 0);
      $oStdRetorno->valor         = $oValor->valor;
      $oStdRetorno->ppaestimativa = property_exists($oValor, 'codigo_estimativa') ? $oValor->codigo_estimativa : null;
    }

    return $oStdRetorno;
  }
  /**
   * Adiciona uma estimativa de despesa ao ppa
   *
   * @param object $oAcao
   */
  function adicionarEstimativa($oAcao) {


    $aParametros = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
    if (count($aParametros) == 0) {
      throw new Exception("Parametros do orçamento não encontrados para o ano ".db_getsession("DB_anousu"),26);
    }
    $oParametroOrcamento = $aParametros[0];
    /*
     * Incluimos a dotacao
     */
    $oPPADotacao = db_utils::getDao("ppadotacao");
    $oDaoPppaEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPppaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    /**
     * Verificamos se já na existe uma dotacao já cadastrada para os valores informados
     */
    $sWhere        = "     o08_ano               = {$oAcao->iAno}";
    $sWhere       .= " and o08_orgao             = {$oAcao->o08_orgao}";
    $sWhere       .= " and o08_unidade           = {$oAcao->o08_unidade}";
    $sWhere       .= " and o08_funcao            = {$oAcao->o08_funcao}";
    $sWhere       .= " and o08_subfuncao         = {$oAcao->o08_subfuncao}";
    $sWhere       .= " and o08_programa          = {$oAcao->o08_programa}";
    $sWhere       .= " and o08_projativ          = {$oAcao->o08_projativ}";
    $sWhere       .= " and o08_elemento          = {$oAcao->o08_elemento}";
    $sWhere       .= " and o08_recurso           = {$oAcao->o08_recurso}";
    $sWhere       .= " and o08_localizadorgastos = {$oAcao->o08_localizadorgastos}";
    $sWhere       .= " and o08_ppaversao         = {$this->iCodigoVersao}";
    $sWhere       .= " and o08_instit            = ".db_getsession("DB_instit");
    $sWhere       .= " and o08_concarpeculiar    = '{$oAcao->o08_concarpeculiar}'";
    $sSqlDotacao   = $oPPADotacao->sql_query_file(null,"*", null, $sWhere);
    $rsDotacao     = $oPPADotacao->sql_record($sSqlDotacao);
    if ($oPPADotacao->numrows > 0) {

      $sErroMsg  = "Erro ao processar a estimativa com os dados informados.\n";
      $sErroMsg .= "Estimativa já cadastrada para o ano {$oAcao->iAno}.";
      throw new Exception($sErroMsg, 27);

    }
    $oPPADotacao->o08_ano               = $oAcao->iAno;
    $oPPADotacao->o08_orgao             = $oAcao->o08_orgao;
    $oPPADotacao->o08_unidade           = $oAcao->o08_unidade;
    $oPPADotacao->o08_funcao            = $oAcao->o08_funcao;
    $oPPADotacao->o08_subfuncao         = $oAcao->o08_subfuncao;
    $oPPADotacao->o08_programa          = $oAcao->o08_programa;
    $oPPADotacao->o08_projativ          = $oAcao->o08_projativ;
    $oPPADotacao->o08_elemento          = $oAcao->o08_elemento;
    $oPPADotacao->o08_recurso           = $oAcao->o08_recurso;
    $oPPADotacao->o08_localizadorgastos = $oAcao->o08_localizadorgastos;
    $oPPADotacao->o08_ppaversao         = $this->iCodigoVersao;
    $oPPADotacao->o08_instit            = db_getsession("DB_instit");
    $oPPADotacao->o08_concarpeculiar    = $oAcao->o08_concarpeculiar;
    $oPPADotacao->incluir(null);

    if ($oPPADotacao->erro_status == 0) {

      $sStringErro = "\n";
      $aErros = explode("\n",pg_last_error());
      if (isset($aErros[1])) {
        $sStringErro  .= str_replace("orc","",$aErros[1],$aErros[1]);
      }
      throw new Exception("Erro ao processar estimativa {$oAcao->o08_elemento} {$sStringErro}",28);
    }
    $nValorSalvar = round($oAcao->nValor);
    if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
      $nValorSalvar = round($oAcao->nValor, 2);
    }
    $oDaoPppaEstimativa->o05_base          = "false";
    $oDaoPppaEstimativa->o05_anoreferencia = $oAcao->iAno;
    $oDaoPppaEstimativa->o05_ppaversao     = $this->iCodigoVersao;
    $oDaoPppaEstimativa->o05_valor         = "{$nValorSalvar}";
    $oDaoPppaEstimativa->incluir(null);
    if ($oDaoPppaEstimativa->erro_status == 0 ){
      throw new Exception("Erro ao processar Receita {$oAcao->o08_elemento} erro ao incluir estimativa",29);
    }

    $oDaoPppaEstimativaDespesa->o07_anousu        = $oAcao->iAno;
    $oDaoPppaEstimativaDespesa->o07_coddot        = $oPPADotacao->o08_sequencial;
    $oDaoPppaEstimativaDespesa->o07_ppaestimativa = $oDaoPppaEstimativa->o05_sequencial;
    $oDaoPppaEstimativaDespesa->incluir(null);
    if ($oDaoPppaEstimativaDespesa->erro_status == 0 ){
      throw new Exception("Erro ao processar Receita {$oValorBase->o18_codele} erro ao incluir estimativa.",30);
    }
  }

  /**
   * Retorna a lista de campos conforme o nivel escolhido
   *
   * @param integer $iNivel
   * @return object
   */
  function getCamposPorNivel($iNivel) {

    $oFields = new stdClass;

    switch ($iNivel) {

      case 1:

        $oFields->sCampos = "
          o08_orgao as o19_coddot,
          (
            select
              trim(o40_descr)
            from
              orcorgao
            where
              orcorgao.o40_orgao = ppadotacao.o08_orgao
            order by
              orcorgao.o40_anousu desc
            limit 1
          ) as c60_estrut";
        $oFields->sOrder  = "1, 2";
        break;

      case 2:

        $oFields->sCampos = "o08_orgao, o08_unidade as o19_coddot,
        (
          select
            trim(o41_descr)
          from
            orcunidade
          where
            orcunidade.o41_orgao       = ppadotacao.o08_orgao
            and orcunidade.o41_unidade = ppadotacao.o08_unidade
          order by
            orcunidade.o41_anousu desc
          limit 1
        ) as c60_estrut";
        $oFields->sOrder  = "1,2,3";
        break;

      case 3:

        $oFields->sCampos = "o08_funcao as o19_coddot,trim(o52_descr) as c60_estrut";
        $oFields->sOrder  = "o08_funcao, 2";
        break;

      case 4:

        $oFields->sCampos = "o08_subfuncao as o19_coddot, trim(o53_descr) as c60_estrut";
        $oFields->sOrder = "o08_subfuncao, 2";
        break;

      case 5:

        $oFields->sCampos = "o08_programa as o19_coddot, trim(o54_descr) as c60_estrut";
        $oFields->sOrder  = "o08_programa, 2";
        break;

      case 6:

        $oFields->sCampos = "o08_projativ as o19_coddot, trim(o55_descr)  as c60_estrut";
        $oFields->sOrder  = "1, 2";
        break;

      case 7:

        $oFields->sCampos = "o56_elemento,o08_elemento as o19_coddot, o56_descr as c60_estrut";
        $oFields->sOrder = "o56_elemento, o56_descr";
        break;

      case 8:

        $oFields->sCampos = "o08_recurso as o19_coddot, o15_descr as c60_estrut";
        $oFields->sOrder  = "o08_recurso,o15_descr";
        break;

    }
    return $oFields;
  }

  function setInstituicoes($sInstituicoes) {
    $this->sInstituicoes = str_replace("-",",",$sInstituicoes);
  }

  function getInstituicoes() {

    if (empty($this->sInstituicoes)) {
      $this->sInstituicoes = db_getsession("DB_instit");
    }
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
    $oDaoPPaVersao = db_utils::getDao("ppaversao");
    $sSqlVersao    = $oDaoPPaVersao->sql_query($iPerspectiva);
    $rsVersao      = $oDaoPPaVersao->sql_record($sSqlVersao);

    if ($oDaoPPaVersao->numrows == 0) {

      $sErro  = "Erro [1] - Erro ao verificar existência da perspectiva ($iPerspectiva).\n";
      $sErro .= "Perspectiva não encontrada no sistema.";
      throw new Exception($sErro,31);
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
    $sAnosMigrar              = implode(", ",$aAnosImportar);
    /**
     * Pesquisamos todas as estimativas de despesa
     */
    $oDaoPPAEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $sSqlEstimativasDespesa   = $oDaoPPAEstimativaDespesa->sql_query_dotacao(null,
                                                                   "ppaestimativadespesa.*,
                                                                    ppaestimativa.*,
                                                                    ppadotacao.*,
                                                                    ppadotacaoorcdotacao.*",
                                                                    null,
                                                                    "o05_ppaversao={$iPerspectiva}
                                                                    and o05_anoreferencia in($sAnosMigrar)
                                                                    and o08_instit = ".db_getsession("DB_instit")
                                                                    ." and o05_base is false"
                                                                    );
    $rsEstimativasDespesa     = $oDaoPPAEstimativaDespesa->sql_record(analiseQueryPlanoOrcamento($sSqlEstimativasDespesa));
    $aItensEstimativasDespesa = db_utils::getCollectionByRecord($rsEstimativasDespesa);
    $oPPADotacao = db_utils::getDao("ppadotacao");
    foreach ($aItensEstimativasDespesa as $oEstimativa) {

      $oPPADotacao->o08_ano               = $oEstimativa->o08_ano;
      $oPPADotacao->o08_elemento          = $oEstimativa->o08_elemento;
      $oPPADotacao->o08_funcao            = $oEstimativa->o08_funcao;
      $oPPADotacao->o08_instit            = $oEstimativa->o08_instit;
      $oPPADotacao->o08_localizadorgastos = $oEstimativa->o08_localizadorgastos;
      $oPPADotacao->o08_orgao             = $oEstimativa->o08_orgao;
      $oPPADotacao->o08_ppaversao         = $this->iCodigoVersao;
      $oPPADotacao->o08_programa          = $oEstimativa->o08_programa;
      $oPPADotacao->o08_projativ          = $oEstimativa->o08_projativ;
      $oPPADotacao->o08_recurso           = $oEstimativa->o08_recurso;
      $oPPADotacao->o08_subfuncao         = $oEstimativa->o08_subfuncao;
      $oPPADotacao->o08_unidade           = $oEstimativa->o08_unidade;
      $oPPADotacao->o08_concarpeculiar    = $oEstimativa->o08_concarpeculiar;
      $oPPADotacao->incluir(null);
      if ($oPPADotacao->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oPPADotacao->erro_msg}", 32);
      }
      if ($oEstimativa->o19_coddot != "") {

        $oDaoPPaORcdotacao                 = db_utils::getDao("ppadotacaoorcdotacao");
        $oDaoPPaORcdotacao->o19_anousu     = $oEstimativa->o19_anousu;
        $oDaoPPaORcdotacao->o19_coddot     = $oEstimativa->o19_coddot;
        $oDaoPPaORcdotacao->o19_ppadotacao = $oPPADotacao->o08_sequencial;
        $oDaoPPaORcdotacao->incluir(null);
        if ($oDaoPPaORcdotacao->erro_status == 0) {
          throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPaORcdotacao->erro_msg}", 33);
        }
      } else {

        $sSqlVerificaDotacao  = "select o58_coddot ";
        $sSqlVerificaDotacao .= "  from orcdotacao ";
        $sSqlVerificaDotacao .= " where o58_anousu            = ".($this->oDadosLei->o01_anoinicio - 1);
        $sSqlVerificaDotacao .= "   and o58_orgao             = {$oEstimativa->o08_orgao}";
        $sSqlVerificaDotacao .= "   and o58_unidade           = {$oEstimativa->o08_unidade}";
        $sSqlVerificaDotacao .= "   and o58_funcao            = {$oEstimativa->o08_funcao}";
        $sSqlVerificaDotacao .= "   and o58_subfuncao         = {$oEstimativa->o08_subfuncao}";
        $sSqlVerificaDotacao .= "   and o58_programa          = {$oEstimativa->o08_programa}";
        $sSqlVerificaDotacao .= "   and o58_codele            = {$oEstimativa->o08_elemento}";
        $sSqlVerificaDotacao .= "   and o58_codigo            = {$oEstimativa->o08_recurso}";
        $sSqlVerificaDotacao .= "   and o58_projativ          = {$oEstimativa->o08_projativ}";
        $sSqlVerificaDotacao .= "   and o58_concarpeculiar    = '{$oEstimativa->o08_concarpeculiar}'";
        $sSqlVerificaDotacao .= "   and o58_localizadorgastos = {$oEstimativa->o08_localizadorgastos}";
        $sSqlVerificaDotacao .= "   and o58_instit            = {$oEstimativa->o08_instit}";
        $rsVerificaDotacao   = db_query($sSqlVerificaDotacao);
        if (pg_num_rows($rsVerificaDotacao) > 0) {

          $oDaoPPaORcdotacao                 = db_utils::getDao("ppadotacaoorcdotacao");
          $oDaoPPaORcdotacao->o19_anousu     = ($this->oDadosLei->o01_anoinicio - 1);
          $oDaoPPaORcdotacao->o19_coddot     = db_utils::fieldsMemory($rsVerificaDotacao, 0)->o58_coddot;
          $oDaoPPaORcdotacao->o19_ppadotacao = $oPPADotacao->o08_sequencial;
          $oDaoPPaORcdotacao->incluir(null);
          if ($oDaoPPaORcdotacao->erro_status == 0) {
            throw new Exception("Erro ao salvar nova versão!\n{$oDaoPPaORcdotacao->erro_msg}", 34);
          }
        }
      }

      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $this->iCodigoVersao;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaNova->erro_msg}", 35);
      }
      $oDaoPPAEstimativaDespesaNova = new cl_ppaestimativaDespesa;
      $oDaoPPAEstimativaDespesaNova->o07_anousu        = $oEstimativa->o07_anousu;
      $oDaoPPAEstimativaDespesaNova->o07_coddot        = $oPPADotacao->o08_sequencial;
      $oDaoPPAEstimativaDespesaNova->o07_ppaestimativa = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaDespesaNova->incluir(null);
      if ($oDaoPPAEstimativaDespesaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaDespesaNova->erro_msg}", 36);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaDespesaNova);
    }
    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);
    /**
     * processamos os anos que nao sao iguais
     */
    asort($aAnosProcessar);

    if (count($aAnosProcessar) > 1) {

      $iAnoInicial = $aAnosProcessar[0];
      $iAnoFinal   = end($aAnosProcessar);
      $this->processarEstimativasGlobais($iAnoInicial, $iAnoFinal, true);
    }
    return $this;
  }

  /**
   * Método que busca o valores reestimado para o ano corrente.
   * @param integer $iSequencialPPADotacao
   * @param integer $iAno
   * @throws Exception
   * @return float
   */
  public function getValorReEstimativaExercicioAtual($oValorBase, $iAno) {

    $iAnoBaseCalculo = $iAno - 1;
    $oDaoPPADotacao  = db_utils::getDao('ppadotacao');

    $sWhereDotacao  = "     ppaestimativa.o05_ppaversao = {$this->iCodigoVersao}";
    $sWhereDotacao .= " and o05_base is true                                       ";
    $sWhereDotacao .= " and ppadotacao.o08_ppaversao    = {$this->iCodigoVersao}";
    $sWhereDotacao .= " and o07_anousu                  = {$iAnoBaseCalculo}                     ";
    $sWhereDotacao .= " and o05_ppaversao               = {$this->iCodigoVersao}                 ";
    $sWhereDotacao .= " and o08_orgao                   = {$oValorBase->o08_orgao}               ";
    $sWhereDotacao .= " and o08_unidade                 = {$oValorBase->o08_unidade}             ";
    $sWhereDotacao .= " and o08_funcao                  = {$oValorBase->o08_funcao}              ";
    $sWhereDotacao .= " and o08_subfuncao               = {$oValorBase->o08_subfuncao}           ";
    $sWhereDotacao .= " and o08_programa                = {$oValorBase->o08_programa}            ";
    $sWhereDotacao .= " and o08_concarpeculiar          = '{$oValorBase->o08_concarpeculiar}'    ";
    $sWhereDotacao .= " and o08_projativ                = {$oValorBase->o08_projativ}            ";
    $sWhereDotacao .= " and o08_recurso                 = {$oValorBase->o08_recurso}             ";
    $sWhereDotacao .= " and o08_elemento                = {$oValorBase->o08_elemento}            ";
    $sWhereDotacao .= " and o08_localizadorgastos       = {$oValorBase->o08_localizadorgastos}   ";
    $sWhereDotacao .= " and o08_instit                  = {$oValorBase->o08_instit}              ";

    $sSqlBuscaValor  = $oDaoPPADotacao->sql_query_despesa_ppa(null, 'o05_valor', null, $sWhereDotacao);
    $rsBuscaValor    = $oDaoPPADotacao->sql_record($sSqlBuscaValor);
    if ($oDaoPPADotacao->numrows == 0) {
      throw new Exception("Não foi possível localizar valor para a dotação no ano de {$iAnoBaseCalculo}.");
    }
    return db_utils::fieldsMemory($rsBuscaValor, 0)->o05_valor;
  }
}
