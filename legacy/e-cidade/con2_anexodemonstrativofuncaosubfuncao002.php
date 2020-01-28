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

require_once modification("fpdf151/pdf.php");
require_once modification("fpdf151/assinatura.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("model/relatorioContabil.model.php");

/**
 * Imprime o novo modelo para os anos acima de 2015
 */
if (db_getsession("DB_anousu") >= 2015) {
  header("location: con2_anexodemonstrativofuncaosubfuncao002_2015.php?{$_SERVER['QUERY_STRING']}");

  return;
}

$oGet         = db_utils::postMemory($_GET);
$sTipoPeriodo = $oGet->sPeriodo;
$sPeriodo     = $oGet->sBimestre;

if ($oGet->sPeriodo == 'Mensal') {
  $sPeriodo = $oGet->sMes;
}

$dtAnousu     = db_getsession("DB_anousu");
$sInstituicao = db_getsession("DB_instit");
$xinstit      = split("-", $oGet->sOrigem);

$rsInstituicao = db_query("select munic from db_config where codigo in (" . str_replace('-', ', ', $oGet->sOrigem)
                          . ") ");

$oTeste = db_utils::fieldsmemory($rsInstituicao, 0);

$sDescricaoInstituicao = "MUNICÍPIO DE " . $oTeste->munic;
$oAssinatura           = new cl_assinatura;
$sNivela               = substr(@$nivel, 0, 1);
$sele_work             = ' o58_instit in (' . str_replace('-', ', ', $oGet->sOrigem) . ')';

$sSqlOrgaos = "select distinct o41_orgao                                                                         ";
$sSqlOrgaos .= "   from orcunidade                                                                                ";
$sSqlOrgaos .= "     inner join orcorgao on                                                                       ";
$sSqlOrgaos .= "                         o41_orgao = o40_orgao and o40_anousu = {$dtAnousu}                       ";
$sSqlOrgaos .= "     where o41_anousu = {$dtAnousu} and o41_instit  in (" . str_replace('-', ',', $oGet->sOrigem) . ")";

$res_orgaos    = @db_query($sSqlOrgaos);
$iNumeroLinhas = @pg_numrows($res_orgaos);
$sOrgao        = "";
$sSeparador    = "";

if ($iNumeroLinhas != false) {

  for ($i = 0; $i < $iNumeroLinhas; $i++) {

    $oOrgao = db_utils::fieldsmemory($res_orgaos, $i);
    $sOrgao .= $sSeparador . "pai_" . $oOrgao->o41_orgao;
    $sSeparador = "-";
  }
}
db_query("begin");
db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");

$sCampos = split("-", $sOrgao);

for ($i = 0; $i < sizeof($sCampos); $i++) {

  $sConcatenaWhere = '';
  $sVirgula        = '';
  $sCamposSplit    = split("_", $sCampos[$i]);

  for ($j = 0; $j < sizeof($sCamposSplit); $j++) {

    if ($j > 0) {

      $sConcatenaWhere .= $sVirgula . $sCamposSplit[$j];
      $sVirgula = ', ';
    }
  }

  switch ($sNivela) {

    case 1 : {

      $sConcatenaWhere .= ",0,0,0,0,0,0,0";
      break;
    }

    case 2 : {

      $sConcatenaWhere .= ",0,0,0,0,0,0";
      break;
    }

    case 3 : {

      $sConcatenaWhere .= ",0,0,0,0,0";
      break;
    }

    case 4 : {

      $sConcatenaWhere .= ",0,0,0,0";
      break;
    }
    case 5 : {

      $sConcatenaWhere .= ",0,0,0";
      break;
    }
    case 6 : {

      $sConcatenaWhere .= ",0,0";
      break;
    }
    case 7 : {

      $sConcatenaWhere .= ",0";
      break;
    }
  }
  db_query("insert into t values($sConcatenaWhere)");
}

db_query("commit");
$oDaoPeriodo = db_utils::getDao("periodo");

if ($oGet->sPeriodo == "Mensal") {

  $iCodigoPeriodo = $sMes;
  $sTipoPeriodo   = "Mês";
}

if ($oGet->sPeriodo == "Bimestral") {

  $iCodigoPeriodo = $sBimestre;
  $sTipoPeriodo   = "Bimestre";
}

$sSqlPeriodo   = $oDaoPeriodo->sql_query($iCodigoPeriodo);
$sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo), 0)->o114_sigla;
$sData         = $sSiglaPeriodo;
$sDadosPeriodo = data_periodo($dtAnousu, $sData);

$sPeriodoImpressao       = strtoupper($sDadosPeriodo["periodo"]);
$sPeriodoInicioImpressao = split("-", $sDadosPeriodo[0]);
$sPeriodoFimImpressao    = split("-", $sDadosPeriodo[1]);

$sMesInicial = strtoupper(db_mes($sPeriodoInicioImpressao[1]));
$sMesFim     = strtoupper(db_mes($sPeriodoFimImpressao[1]));
$sDataInicio = $sDadosPeriodo[0];
$sDataFim    = $sDadosPeriodo[1];

// $grupoini = 1;
// $grupofin = 3;

$xtipo  = "BALANÇO";
$origem = "B";
$head2  = $sDescricaoInstituicao;
$head3  = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4  = "DEMONSTRATIVO DA EXECUÇÃO DAS DESPESAS POR FUNÇÃO/SUBFUNÇÃO";
$head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

if ($sTipoPeriodo == "Bimestre") {

  $head6 = "JANEIRO A " . $sMesFim . "/" . $dtAnousu . " - " . $sPeriodoImpressao . " " . $sMesInicial . "-" . $sMesFim;
} else {

  $head6 = "PERÍODO : " . $sMesFim . "/" . $dtAnousu;
}


/**
 * Cria StdClass com as propriedades necessárias já setadas
 * @return stdClass
 */
function inicializarDespesa() {

  $oTipoDespesa                                  = new stdClass();
  $oTipoDespesa->nTotalDotacaoInicial            = 0;
  $oTipoDespesa->nTotalDotacaoAtualizada         = 0;
  $oTipoDespesa->nTotalDespesasEmpenhadas        = 0;
  $oTipoDespesa->nTotalDespesasEmpenhadasPeriodo = 0;
  $oTipoDespesa->nTotalDespesasLiquidadas        = 0;
  $oTipoDespesa->nTotalDespesasLiquidadasPeriodo = 0;
  $oTipoDespesa->nInscritos                      = 0;
  $oTipoDespesa->nPercentualA                    = 0;
  $oTipoDespesa->nPercentualB                    = 0;
  $oTipoDespesa->nTotalLiquidar                  = 0;

  return $oTipoDespesa;
}


/**
 * Adiciona uma despesa, classificando-a corretamente dentro de um grupo de despesa
 *  Para cada desepsa adicionada, o algoritmo associa esta a:
 *  1)um Tipo (RPPS, Contingência, Outras)
 *  2)uma função
 *  3)uma subfunção
 *
 * Totaliza os valores para cada "Tipo", "Função" e "Subfunção"
 * Além disso, soma o valor de todos os tipos presentes
 *
 * @param  stdClass $oTipoDespesa
 * @param  stdClass $oStdSubfuncao
 * @param  string   $sFuncao             //código da função
 * @param  string   $sSubfuncao          //código da subfunção
 * @param  string   $sDescricaoFuncao    //descrição da função
 * @param  string   $sDescricaoSubFuncao //descrição da subfunção
 *
 * @return StdClass
 */
function contabilizarDespesa($oTipoDespesa,
                             $oStdSubfuncao,
                             $sFuncao,
                             $sSubfuncao,
                             $sDescricaoFuncao,
                             $sDescricaoSubFuncao) {

  /**
   * Verifica se o Tipo de despesa já foi setado
   * true  = realiza somatório, computando os valores da nova despesa
   * false = atribui os valores para totalização e instancia array das despesas, para serem classificadas
   */
  if (!isset($oTipoDespesa)) {

    $oTipoDespesa           = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes = array();

  } else {

    $oTipoDespesa->nTotalDotacaoInicial += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->nTotalDotacaoAtualizada += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->nTotalDespesasEmpenhadas += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->nTotalDespesasLiquidadas += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->nPercentualA += $oStdSubfuncao->nPercentualA;
    $oTipoDespesa->nPercentualB += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->nInscritos += $oStdSubfuncao->nInscritos;
    $oTipoDespesa->nTotalLiquidar += $oStdSubfuncao->nTotalLiquidar;

  }

  /**
   * Verifica se o tipo da função é nova, dentro do Tipo
   * Caso seja novo, adiciona o novo tipo e computa os valores da despesa, nos totalizadores
   * Caso contrário, apenas atualiza os totalizadores
   */
  if (!isset($oTipoDespesa->aFuncoes[$sFuncao])) {

    $oTipoDespesa->aFuncoes[$sFuncao]              = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->sDescricao  = $sDescricaoFuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes = array();

  } else {

    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDotacaoInicial += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDotacaoAtualizada += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasEmpenhadas += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasLiquidadas += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->nPercentualA += $oStdSubfuncao->nPercentualA;
    $oTipoDespesa->aFuncoes[$sFuncao]->nPercentualB += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->aFuncoes[$sFuncao]->nInscritos += $oStdSubfuncao->nInscritos;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalLiquidar += $oStdSubfuncao->nTotalLiquidar;

  }

  /**
   * Verifica se o tipo da subfunção é nova, dentro da função
   * Caso seja novo, adiciona o novo tipo e computa os valores da despesa, nos totalizadores
   * Caso contrário, apenas atualiza os totalizadores da subfunção
   */
  if (!isset($oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao])) {

    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]             = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->sDescricao = $sDescricaoSubFuncao;

  } else {

    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDotacaoInicial += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDotacaoAtualizada += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasEmpenhadas += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasLiquidadas += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nPercentualA += $oStdSubfuncao->nPercentualA;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nPercentualB += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nInscritos += $oStdSubfuncao->nInscritos;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalLiquidar += $oStdSubfuncao->nTotalLiquidar;
  }

  return $oTipoDespesa;
}


/**
 * Constroi array de despesas, de acordo com SQL repassado
 * Agrupa cada uma delas, dentro de um tipo, função e subfunção
 *
 * @param  string $sSQL //SQL para busca das despesas
 *
 * @return StdClass $oRetorno  //agrupamento de despesas
 * */

function construirArraysDespesas($sSql, $lDiferenciaTipo = true) {

  $rsSubfuncoes      = db_query($sSql);
  $iNumeroResultados = pg_num_rows($rsSubfuncoes);

  $oRPPS         = inicializarDespesa();
  $oContingencia = inicializarDespesa();
  $oOutras       = inicializarDespesa();

  $oRetorno                                  = new stdClass();
  $oRetorno->nTotalDotacaoInicial            = 0;
  $oRetorno->nTotalDotacaoAtualizada         = 0;
  $oRetorno->nTotalDespesasEmpenhadas        = 0;
  $oRetorno->nTotalDespesasEmpenhadasPeriodo = 0;
  $oRetorno->nTotalDespesasLiquidadas        = 0;
  $oRetorno->nTotalDespesasLiquidadasPeriodo = 0;
  $oRetorno->nInscritos                      = 0;
  $oRetorno->nPercentualA                    = 0;
  $oRetorno->nPercentualB                    = 0;
  $oRetorno->nTotalLiquidar                  = 0;

  /**
   * "For" que itera sobre o resultset das despesas
   *  Faz as agrupações de acordo com Tipo, função e subfunção
   */
  $iAnoUsoFuncao = db_getsession("DB_anousu");

  for ($i = 0; $i < $iNumeroResultados; $i++) {

    $oDespesa            = db_utils::fieldsMemory($rsSubfuncoes, $i);
    $sFuncao             = $oDespesa->o58_funcao;
    $sSubfuncao          = $oDespesa->o58_subfuncao;
    $sDescricaoFuncao    = $oDespesa->o52_descr;
    $sDescricaoSubFuncao = $oDespesa->o53_descr;

    $oStdSubfuncao->nTotalDotacaoInicial            = $oDespesa->dot_ini_p;
    $oStdSubfuncao->nTotalDotacaoAtualizada         = $oDespesa->dot_ini_p + $oDespesa->suplementado_p
                                                      - $oDespesa->reduzir_p;
    $oStdSubfuncao->nTotalDespesasEmpenhadas        = $oDespesa->empenhado_p - $oDespesa->anulado_p;
    $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo = $oDespesa->empenhado_acumulado_p - $oDespesa->anulado_acumulado_p;
    $oStdSubfuncao->nTotalDespesasLiquidadas        = $oDespesa->liquidado_p;
    $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo = $oDespesa->liquidado_acumulado_p;
    $oStdSubfuncao->nInscritos                      = abs($oDespesa->inscrito_p);

    $oStdSubfuncao->nPercentualA = 1;
    $oStdSubfuncao->nPercentualB = 1;

    $oStdSubfuncao->nTotalLiquidar = ($oStdSubfuncao->nTotalDotacaoAtualizada
                                      - $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo);

    $sTipo = substr($oDespesa->o58_elemento, 0, 2);

    /**
     * Validacao do tipo de instituicao. caso a dotacao esteja vinculada ao tipo 5/6 é RPPS, o que altera
     * a variavel $sTipo para 37.
     */
    $sSqlBuscaDotacaoTipo = " select 1                                                    ";
    $sSqlBuscaDotacaoTipo .= "    from orcdotacao                                          ";
    $sSqlBuscaDotacaoTipo .= "         inner join orcelemento   on o58_anousu = o56_anousu ";
    $sSqlBuscaDotacaoTipo .= "                                 and o58_codele = o56_codele ";
    $sSqlBuscaDotacaoTipo .= "         inner join db_config on codigo = o58_instit         ";
    $sSqlBuscaDotacaoTipo .= "   where o58_anousu = {$iAnoUsoFuncao}                       ";
    $sSqlBuscaDotacaoTipo .= "     and substr(o56_elemento,1,2) = '39'                     ";
    $sSqlBuscaDotacaoTipo .= "     and db21_tipoinstit in (5,6)                            ";
    $sSqlBuscaDotacaoTipo .= "     and o58_coddot = {$oDespesa->o58_coddot}                ";

    $rsBuscaDotacaoTipo = db_query($sSqlBuscaDotacaoTipo);
    $iLinhasEncontradas = pg_num_rows($rsBuscaDotacaoTipo);

    if ($iLinhasEncontradas > 0) {
      $sTipo = '37';
    }

    if ($lDiferenciaTipo == false) {
      $sTipo = '';
    }


    /**
     * Verifica qual tipo a despesa está associada
     */
    switch ($sTipo) {

      case '37' : {

        $sTipoDescricao = 'RPPS';
        $oRPPS          = contabilizarDespesa($oRPPS,
                                              $oStdSubfuncao,
                                              $sFuncao,
                                              $sSubfuncao,
                                              $sDescricaoFuncao,
                                              $sDescricaoSubFuncao);
        break;
      }

      case '39': {

        $sTipoDescricao = 'Contingencia';
        $oContingencia  = contabilizarDespesa($oContingencia,
                                              $oStdSubfuncao,
                                              $sFuncao,
                                              $sSubfuncao,
                                              $sDescricaoFuncao,
                                              $sDescricaoSubFuncao);
        break;
      }

      default: {

        $sTipoDescricao = 'Outras';
        $oOutras        = contabilizarDespesa($oOutras,
                                              $oStdSubfuncao,
                                              $sFuncao,
                                              $sSubfuncao,
                                              $sDescricaoFuncao,
                                              $sDescricaoSubFuncao);
        break;
      }
    }

    $oRetorno->nTotalDotacaoInicial += $oStdSubfuncao->nTotalDotacaoInicial;
    $oRetorno->nTotalDotacaoAtualizada += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oRetorno->nTotalDespesasEmpenhadas += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oRetorno->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oRetorno->nTotalDespesasLiquidadas += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oRetorno->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oRetorno->nInscritos += $oStdSubfuncao->nInscritos;
    $oRetorno->nPercentualA += $oStdSubfuncao->nPercentualA;
    $oRetorno->nPercentualB += $oStdSubfuncao->nPercentualB;
    $oRetorno->nTotalLiquidar += $oStdSubfuncao->nTotalLiquidar;
  }
  $oRetorno->oRPPS         = $oRPPS;
  $oRetorno->oContingencia = $oContingencia;
  $oRetorno->oOutras       = $oOutras;

  return $oRetorno;

}


/**
 * Calcula percentual de um agrupamento de despesas
 * Para despesas do tipo RPPS e Contingência:
 * calcula o percentual apenas totalizador
 *
 * Para o restante, calcula percentual individual de grupo e subgrupo
 *
 */
function calculaPercetual($oDespesas) {

  $nValorA = $oDespesas->nTotalDotacaoAtualizada;
  $nValorB = $oDespesas->nTotalDespesasLiquidadasPeriodo;

  if ($nValorB != 0) {
    $oDespesas->nPercentualA = ($nValorB / $nValorB) * 100;
  }

  if ($nValorA) {
    $oDespesas->nPercentualB = ($nValorB / $nValorA) * 100;
  }
  $nTotalB = $oDespesas->nTotalDespesasLiquidadasPeriodo;

  if (isset($oDespesas->oOutras)) {

    foreach ($oDespesas->oOutras->aFuncoes as $oFuncao) {

      $nValorA = $oFuncao->nTotalDotacaoAtualizada;
      $nValorB = $oFuncao->nTotalDespesasLiquidadasPeriodo;

      if ($nTotalB != 0) {
        $oFuncao->nPercentualA = ($nValorB / $nTotalB) * 100;
      }
      if ($nValorA) {
        $oFuncao->nPercentualB = ($nValorB / $nValorA) * 100;
      }

      foreach ($oFuncao->aSubfuncoes as $oSubfuncao) {

        $nValorA = $oSubfuncao->nTotalDotacaoAtualizada;
        $nValorB = $oSubfuncao->nTotalDespesasLiquidadasPeriodo;

        if ($nTotalB != 0) {
          $oSubfuncao->nPercentualA = ($nValorB / $nTotalB) * 100;
        }
        if ($nValorA) {
          $oSubfuncao->nPercentualB = ($nValorB / $nValorA) * 100;
        }

      }
    }
  }

  if (isset($oDespesas->oRPPS)) {

    $nValorA = $oDespesas->oRPPS->nTotalDotacaoAtualizada;
    $nValorB = $oDespesas->oRPPS->nTotalDespesasLiquidadasPeriodo;
    if ($nTotalB != 0) {
      $oFuncao->oRPPS->nPercentualA = ($nValorB / $nTotalB) * 100;
    }
    if ($nValorA) {
      $oFuncao->oRPPS->nPercentualB = ($nValorB / $nValorA) * 100;
    }
  }

  if (isset($oDespesas->oContingencia)) {

    $nValorA = $oDespesas->oContingencia->nTotalDotacaoAtualizada;
    $nValorB = $oDespesas->oContingencia->nTotalDespesasLiquidadasPeriodo;
    if ($nTotalB != 0) {
      $oFuncao->oContingencia->nPercentualA = ($nValorB / $nTotalB) * 100;
    }
    if ($nValorA) {
      $oFuncao->oContingencia->nPercentualB = ($nValorB / $nValorA) * 100;
    }
  }
}

/**
 * Funções para impressão do pdf
 */


function imprimirCabecalho(&$oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, $sTipoFonte = '') {

  if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {

    $oPdf->cell(45, $iAlturaLinha, "", 0, 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "DOTAÇÃO", "LR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "DOTAÇÃO", "LR", 0, "C", 0);
    $oPdf->cell(32, $iAlturaLinha, "DESPESAS EMPENHADAS", "LR", 0, "C", 0);
    $oPdf->cell(68, $iAlturaLinha, "DESPESAS LIQUIDADAS", "LR", 0, "C", 0);
    $oPdf->cell(13, $iAlturaLinha, "SALDO A", 0, 1, "C", 0);

    $oPdf->cell(45, $iAlturaLinha, "FUNÇÃO/SUBFUNÇÃO", 0, 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "INICIAL", "LR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "ATUALIZADA", "LR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "No", "TLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "Até o", "TLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "No", "TLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "Até o", "TLR", 0, "C", 0);
    $oPdf->cell(18, $iAlturaLinha, "INSCRITAS EM ", "TLR", 0, "C", 0);
    $oPdf->cell(10, $iAlturaLinha, "%((b+c)/", "TLR", 0, "C", 0);
    $oPdf->cell(8, $iAlturaLinha, "%", "TLR", 0, "C", 0);
    $oPdf->cell(13, $iAlturaLinha, "LIQUIDAR", 0, 1, "C", 0);

    $oPdf->cell(45, $iAlturaLinha, "", "BR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "", "BLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "(a)", "BLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(16, $iAlturaLinha, "{$sTipoPeriodo}(b)", "BLR", 0, "C", 0);
    $oPdf->cell(13, $iAlturaLinha, "RP NP (c)", "BLR", 0, "C", 0);

    $oPdf->setfont('arial', '', 5);
    $oPdf->cell(10, $iAlturaLinha, "total (b+c))", "BLR", 0, "C", 0);
    $oPdf->setfont('arial', '', 5);
    $oPdf->cell(8, $iAlturaLinha, "(b+c/a)", "BLR", 0, "C", 0);
    $oPdf->cell(13, $iAlturaLinha, "(a-(b+c))", "B", 1, "C", 0);

  } else {

    $oPdf->cell(45, $iAlturaLinha, "", 0, 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "DOTAÇÃO", "LR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "DOTAÇÃO", "LR", 0, "C", 0);
    $oPdf->cell(40, $iAlturaLinha, "DESPESAS EMPENHADAS", "LR", 0, "C", 0);
    $oPdf->cell(50, $iAlturaLinha, "DESPESAS LIQUIDADAS", "LR", 0, "C", 0);
    $oPdf->cell(15, $iAlturaLinha, "SALDO A", 0, 1, "C", 0);

    $oPdf->cell(45, $iAlturaLinha, "FUNÇÃO/SUBFUNÇÃO", 0, 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "INICIAL", "LR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "ATUALIZADA", "LR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "No", "TLR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "Até o", "TLR", 0, "C", 0);
    $oPdf->cell(17, $iAlturaLinha, "No", "TLR", 0, "C", 0);
    $oPdf->cell(17, $iAlturaLinha, "Até o", "TLR", 0, "C", 0);
    $oPdf->cell(8, $iAlturaLinha, "%", "TLR", 0, "C", 0);
    $oPdf->cell(8, $iAlturaLinha, "%", "TLR", 0, "C", 0);
    $oPdf->cell(15, $iAlturaLinha, "LIQUIDAR", 0, 1, "C", 0);
    $oPdf->cell(45, $iAlturaLinha, "", "BR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "", "BLR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "(a)", "BLR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(20, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(17, $iAlturaLinha, "{$sTipoPeriodo}", "BLR", 0, "C", 0);
    $oPdf->cell(17, $iAlturaLinha, "{$sTipoPeriodo}(b)", "BLR", 0, "C", 0);
    $oPdf->setfont('arial', '', 5);
    $oPdf->cell(8, $iAlturaLinha, "(b/total b)", "BLR", 0, "C", 0);
    $oPdf->setfont('arial', '', 5);
    $oPdf->cell(8, $iAlturaLinha, "(b/a)", "BLR", 0, "C", 0);
    $oPdf->cell(15, $iAlturaLinha, "(a-b)", "B", 1, "C", 0);
  }
  $oPdf->setfont('arial', $sTipoFonte, 5);
}


function imprimirCabecalhoPaginasInternas(&$oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, $sTipoFonte = '') {

  if ($oPdf->gety() > $oPdf->h - 35) {

    $oPdf->cell(190, $iAlturaLinha, 'Continua na Página ' . ($oPdf->pageNo() + 1) . "/{nb}", "T", 1, "R", 0);
    $oPdf->cell(190, $iAlturaLinha, '', "T", 1, "L", 0);
    $oPdf->addpage();
    $oPdf->ln(2);
    $oPdf->cell(190, $iAlturaLinha, 'Continuação ' . ($oPdf->pageNo() - 1) . "/{nb}", "B", 1, "R", 0);
    $oPdf->cell(1, $iAlturaLinha, 'RREO - Anexo II (LRF, Art. 52, inciso II, alínea "c")', "B", 0, "L", 0);
    $oPdf->cell(190, $iAlturaLinha, 'R$ 1,00', "B", 1, "R", 0);

    imprimirCabecalho($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, $sTipoFonte);
    $oPdf->setfont('arial', $sTipoFonte, 5);
  }
}


function imprimeLinha(&$oPdf,
                      $oDespesas,
                      $sPeriodo,
                      $sTipoPeriodo,
                      $iAlturaLinha,
                      $sDescricao = null,
                      $sNivel = null,
                      $sTipoFonte = '') {

  $iColunaFonte               = 5;
  $iColunaDotacaoInicial      = 20;
  $iColunaDotacaoAtualizado   = 20;
  $iColunaEmpenhadoNoPeriodo  = 20;
  $iColunaEmpenhadoAtePeriodo = 20;
  $iColunaLiquidadoNoPeriodo  = 17;
  $iColunaLiquidadoAtePeriodo = 17;
  $iColunaInscritos           = 0;
  $iColunaTotalSobreB         = 8;
  $iColunaTotalBSobreA        = 8;
  $iColunaSaldoLiquidar       = 15;
  $lImprimeInscritos          = false;

  //haverá mais uma coluna, caso bimestre seja último do ano
  if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {

    $lImprimeInscritos = true;
    //echo "bimestre";
    $iColunaDotacaoInicial      = 16;
    $iColunaDotacaoAtualizado   = 16;
    $iColunaEmpenhadoNoPeriodo  = 16;
    $iColunaEmpenhadoAtePeriodo = 16;
    $iColunaLiquidadoNoPeriodo  = 16;
    $iColunaLiquidadoAtePeriodo = 16;
    $iColunaTotalSobreB         = 10;
    $iColunaInscritos           = 18;
    $iColunaSaldoLiquidar       = 13;

  }
  imprimirCabecalhoPaginasInternas($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, $sTipoFonte);

  if (!isset($sDescricao)) {
    $sDescricao = $oDespesas->sDescricao;
  }

  $sDescricao = substr("{$sNivel}{$sDescricao}", 0, 35);

  $oPdf->cell(45, $iAlturaLinha, $sDescricao, "R", 0, "L", 0);
  $oPdf->cell($iColunaDotacaoInicial,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDotacaoInicial, 'f'),
              "LR",
              0,
              "R",
              0);
  $oPdf->cell($iColunaDotacaoAtualizado,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDotacaoAtualizada, 'f'),
              "LR",
              0,
              "R",
              0);
  $oPdf->cell($iColunaEmpenhadoNoPeriodo,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDespesasEmpenhadas, 'f'),
              "LR",
              0,
              "R",
              0);
  $oPdf->cell($iColunaEmpenhadoAtePeriodo,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDespesasEmpenhadasPeriodo, 'f'),
              "LR",
              0,
              "R",
              0);
  $oPdf->cell($iColunaLiquidadoNoPeriodo,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDespesasLiquidadas, 'f'),
              "LR",
              0,
              "R",
              0);
  $oPdf->cell($iColunaLiquidadoAtePeriodo,
              $iAlturaLinha,
              db_formatar($oDespesas->nTotalDespesasLiquidadasPeriodo, 'f'),
              "LR",
              0,
              "R",
              0);

  if ($lImprimeInscritos) {
    $oPdf->cell($iColunaInscritos, $iAlturaLinha, db_formatar($oDespesas->nInscritos, 'f'), "LR", 0, "R", 0);
  }

  $oPdf->cell($iColunaTotalSobreB, $iAlturaLinha, db_formatar($oDespesas->nPercentualA, 'f'), "LR", 0, "R", 0);
  $oPdf->cell($iColunaTotalBSobreA, $iAlturaLinha, db_formatar($oDespesas->nPercentualB, 'f'), "LR", 0, "R", 0);
  $oPdf->cell($iColunaSaldoLiquidar, $iAlturaLinha, db_formatar($oDespesas->nTotalLiquidar, 'f'), 0, 1, "R", 0);
}


/**
 * Imprime um agrupamento de despesas, a partir de um tipo
 * imprime todos os subníveis em sequência
 *
 * @param Pdf      $oPdf
 * @param StdClass $oDespesas    //agrupamento de despesas
 * @param string   $sPeriodo     //descrição do periodo
 * @param string   $sTipoPeriodo //tipo do periodo (bimestre ou mês)
 * @param integer  $iAlturaLinha //altura da linha, na impressão
 */
function imprimeGrupoDespesas(&$oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha) {

  if (!isset($oDespesas->aFuncoes)) {
    return;
  }

  $sNivelSubfuncao = "  ";

  foreach ($oDespesas->aFuncoes as $oFuncao) {

    imprimirCabecalhoPaginasInternas($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha);
    $oPdf->SetFont('arial', 'b', 5);
    imprimeLinha($oPdf, $oFuncao, $sPeriodo, $sTipoPeriodo, $iAlturaLinha);
    $oPdf->SetFont('arial', '', 5);

    foreach ($oFuncao->aSubfuncoes as $oSubfuncao) {

      imprimirCabecalhoPaginasInternas($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha);
      $oPdf->SetFont('arial', '', 5);
      imprimeLinha($oPdf, $oSubfuncao, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, null, $sNivelSubfuncao);

    }
  }
}

/**
 *   SQL para buscar todas Despesas exceto intra-orçamentárias
 */

$sWherePadrao = " and substr(o56_elemento,4,2) != '91' ";
$sDotacao     = db_dotacaosaldo(8,
                                2,
                                5,
                                true,
                                $sele_work . " {$sWherePadrao}",
                                $dtAnousu,
                                $sDataInicio,
                                $sDataFim,
                                8,
                                0,
                                true,
                                1,
                                false);

$sSql = "   select                                                                          ";
$sSql .= "     o58_coddot,                                                                   ";
$sSql .= "     o58_funcao,                                                                   ";
$sSql .= "     o52_descr,                                                                    ";
$sSql .= "     o58_subfuncao,                                                                ";
$sSql .= "     o53_descr,                                                                    ";
$sSql .= "     o58_elemento,                                                                 ";
$sSql .= "     sum(dot_ini) as dot_ini_p,                                                    ";
$sSql .= "     sum(suplementado_acumulado) as suplementado_p,                                ";
$sSql .= "     sum(reduzido_acumulado) as reduzir_p,                                         ";
$sSql .= "     sum(empenhado) as empenhado_p,                                                ";
$sSql .= "     sum(anulado) as anulado_p,                                                    ";
$sSql .= "     sum(empenhado_acumulado) as empenhado_acumulado_p,                            ";
$sSql .= "     sum(anulado_acumulado) as anulado_acumulado_p,                                ";
$sSql .= "     sum(liquidado) as liquidado_p,                                                ";
$sSql .= "     sum(liquidado_acumulado) as liquidado_acumulado_p,                            ";
$sSql .= "     sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p  ";
$sSql .= "   from ($sDotacao) as x                                                         ";
$sSql .= "     group by                                                                      ";
$sSql .= "       o58_subfuncao,o53_descr,o58_funcao,o52_descr,o58_elemento,o58_coddot        ";
$sSql .= "     order by                                                                      ";
$sSql .= "       o58_funcao,                                                                 ";
$sSql .= "       o58_subfuncao                                                               ";

$lDiferenciaTipo = true;
$oDespesasExtra  = construirArraysDespesas($sSql, $lDiferenciaTipo);

/**
 *
 * Definições do PDF
 * inicio do algoritmo para impressão
 */

calculaPercetual($oDespesasExtra);

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 4;

//primeiro cabeçalho
$oPdf->AddPage();
$oPdf->SetFont('arial', 'b', 5);
$oPdf->cell(1, $iAlturaLinha, 'RREO - Anexo II (LRF, Art. 52, inciso II, alínea "c")', "B", 0, "L", 0);
$oPdf->cell(190, $iAlturaLinha, 'R$ 1,00', "B", 1, "R", 0);
imprimirCabecalho($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha);

//Imprime Despesas Exceto Intra-Orçamentarias, que não sejam RPPS e de Contingência
$oDespesas = $oDespesasExtra;
$oPdf->SetFont('arial', 'b', 5);
$sDescricao = "DESPESAS (EXCETO INTRA-ORÇAMENTARIA (I)";
//totalizador das despesas
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, null, 'b');

//Totas as Despesas que não sejam Reserva RPPS e Contingência
$oDespesas = $oDespesas->oOutras;
imprimeGrupoDespesas($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha);

//Imprime Reserva Contingência
$oPdf->SetFont('arial', 'b', 5);
$oDespesas  = $oDespesasExtra->oContingencia;
$sDescricao = "RESERVA DE CONTINGÊNCIA";
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, null, 'b');
$oPdf->SetFont('arial', '', 5);
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, "  ");

//Imprime Reserva RPPS
$oPdf->SetFont('arial', 'b', 5);
$oDespesas  = $oDespesasExtra->oRPPS;
$sDescricao = "RESERVA DO RPPS";
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, null, 'b');
$oPdf->SetFont('arial', '', 5);
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, "  ");

/**
 * Despesas Intra-Orçamentárias
 */

$sSqlIntraOrcamentaria = db_dotacaosaldo(8,
                                         2,
                                         4,
                                         true,
                                         $sele_work . " and substr(o56_elemento,4,2) = '91'",
                                         $dtAnousu,
                                         $sDataInicio,
                                         $sDataFim,
                                         8,
                                         0,
                                         true,
                                         1,
                                         false);
$sSql                  = " select                                                                           ";
$sSql .= "     o58_coddot,                                                                  ";
$sSql .= "     o58_funcao,                                                                  ";
$sSql .= "     o52_descr,                                                                   ";
$sSql .= "     o58_subfuncao,                                                               ";
$sSql .= "     o53_descr,                                                                   ";
$sSql .= "     o58_elemento,                                                                ";
$sSql .= "     sum(dot_ini) as dot_ini_p,                                                   ";
$sSql .= "     sum(suplementado_acumulado) as suplementado_p,                               ";
$sSql .= "     sum(reduzido_acumulado) as reduzir_p,                                        ";
$sSql .= "     sum(empenhado) as empenhado_p,                                               ";
$sSql .= "     sum(anulado) as anulado_p,                                                   ";
$sSql .= "     sum(empenhado_acumulado) as empenhado_acumulado_p,                           ";
$sSql .= "     sum(anulado_acumulado) as anulado_acumulado_p,                               ";
$sSql .= "     sum(liquidado) as liquidado_p,                                               ";
$sSql .= "     sum(liquidado_acumulado) as liquidado_acumulado_p,                           ";
$sSql .= "     sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p ";
$sSql .= "  from ($sSqlIntraOrcamentaria) as x                                              ";
$sSql .= "  group by                                                                        ";
$sSql .= "      o58_subfuncao, o53_descr, o58_funcao, o52_descr, o58_elemento, o58_coddot   ";
$sSql .= "  order by                                                                        ";
$sSql .= "     o58_funcao,                                                                  ";
$sSql .= "     o58_subfuncao                                                                ";


$iAltura                    = 4;
$iColunaFonte               = 5;
$iColunaDotacaoInicial      = 20;
$iColunaDotacaoAtualizado   = 20;
$iColunaEmpenhadoNoPeriodo  = 20;
$iColunaEmpenhadoAtePeriodo = 20;
$iColunaLiquidadoNoPeriodo  = 17;
$iColunaLiquidadoAtePeriodo = 17;
$iColunaInscritos           = 0;
$iColunaTotalSobreB         = 8;
$iColunaTotalBSobreA        = 8;
$iColunaSaldoLiquidar       = 15;
$lImprimeInscritos          = false;

//haverá mais uma coluna, caso bimestre seja último do ano
if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {

  $lImprimeInscritos          = true;
  $iColunaDotacaoInicial      = 16;
  $iColunaDotacaoAtualizado   = 16;
  $iColunaEmpenhadoNoPeriodo  = 16;
  $iColunaEmpenhadoAtePeriodo = 16;
  $iColunaLiquidadoNoPeriodo  = 16;
  $iColunaLiquidadoAtePeriodo = 16;
  $iColunaTotalSobreB         = 10;
  $iColunaInscritos           = 18;
  $iColunaSaldoLiquidar       = 13;
}

$lDiferenciaTipo = false;
$oDespesasIntra  = construirArraysDespesas($sSql, $lDiferenciaTipo);

//Imprime Despesas Exceto Intra-Orçamentarias, que não sejam RPPS e de Contingência
$oDespesas = $oDespesasIntra;
$oPdf->SetFont('arial', 'b', 5);
$sDescricao = "DESPESAS (INTRA-ORÇAMENTÁRIAS)(II)";
//totalizador das despesas
imprimeLinha($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao, null, 'b');


//Totas as Despesas que não sejam Reserva RPPS e Contingência
$oDespesas = $oDespesas->oOutras;
$oPdf->SetFont('arial', 'b', 5);
imprimeGrupoDespesas($oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, null, 'b');


$nTotalDotacaoInicial            = $oDespesasExtra->nTotalDotacaoInicial + $oDespesasIntra->nTotalDotacaoInicial;
$nTotalDotacaoAtualizada         = $oDespesasExtra->nTotalDotacaoAtualizada + $oDespesasIntra->nTotalDotacaoAtualizada;
$nTotalDespesasEmpenhadas        = $oDespesasExtra->nTotalDespesasEmpenhadas
                                   + $oDespesasIntra->nTotalDespesasEmpenhadas;
$nTotalDespesasEmpenhadasPeriodo = $oDespesasExtra->nTotalDespesasEmpenhadasPeriodo
                                   + $oDespesasIntra->nTotalDespesasEmpenhadasPeriodo;
$nTotalDespesasLiquidadas        = $oDespesasExtra->nTotalDespesasLiquidadas
                                   + $oDespesasIntra->nTotalDespesasLiquidadas;
$nTotalDespesasLiquidadasPeriodo = $oDespesasExtra->nTotalDespesasLiquidadasPeriodo
                                   + $oDespesasIntra->nTotalDespesasLiquidadasPeriodo;
$nInscritos                      = $oDespesasExtra->nInscritos + $oDespesasIntra->nInscritos;
$nTotalLiquidar                  = $oDespesasExtra->nTotalLiquidar + $oDespesasIntra->nTotalLiquidar;

imprimirCabecalhoPaginasInternas($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha);
$oPdf->setfont('arial', 'B', 5);
$oPdf->cell(45, $iAltura, "TOTAL (III) = (I + II)", "RTB", 0, "L", 0);
$oPdf->cell($iColunaDotacaoInicial, $iAltura, db_formatar($nTotalDotacaoInicial, 'f'), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaDotacaoAtualizado, $iAltura, db_formatar($nTotalDotacaoAtualizada, 'f'), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaEmpenhadoNoPeriodo, $iAltura, db_formatar($nTotalDespesasEmpenhadas, 'f'), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaEmpenhadoAtePeriodo,
            $iAltura,
            db_formatar($nTotalDespesasEmpenhadasPeriodo, 'f'),
            "LRTB",
            0,
            "R",
            0);
$oPdf->cell($iColunaLiquidadoNoPeriodo, $iAltura, db_formatar($nTotalDespesasLiquidadas, 'f'), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaLiquidadoAtePeriodo,
            $iAltura,
            db_formatar($nTotalDespesasLiquidadasPeriodo, 'f'),
            "LRTB",
            0,
            "R",
            0);

if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {
  $oPdf->cell($iColunaInscritos, $iAltura, db_formatar($nInscritos, 'f'), "LRTB", 0, "R", 0);
}

$nValorA      = $nTotalDotacaoAtualizada;
$nValorB      = $nTotalDespesasLiquidadasPeriodo;
$nPercentualA = 0;
$nPercentualB = 0;

if ($nValorB != 0) {
  $nPercentualA = ($nValorB / $nValorB) * 100;
}
if ($nValorA) {
  $nPercentualB = ($nValorB / $nValorA) * 100;
}

$oPdf->cell($iColunaTotalSobreB, $iAltura, db_formatar($nPercentualA, 'f'), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaTotalBSobreA, $iAltura, db_formatar($nPercentualB, "f"), "LRTB", 0, "R", 0);
$oPdf->cell($iColunaSaldoLiquidar, $iAltura, db_formatar($nTotalLiquidar, 'f'), "LTB", 1, "R", 0);

$oRelatorio = new relatorioContabil(96, false);
$oRelatorio->getNotaExplicativa($oPdf, $iCodigoPeriodo, 185);
$oPdf->ln(5);

if ($oPdf->gety() > $oPdf->h - 35) {

  $oPdf->cell(190, $iAlturaLinha, 'Continua na Página ' . ($oPdf->pageNo() + 1) . "/{nb}", "T", 1, "R", 0);
  $oPdf->cell(190, $iAlturaLinha, '', "T", 1, "L", 0);
  $oPdf->addpage();
  $oPdf->ln(30);
}

assinaturas($oPdf, $oAssinatura, 'LRF', false, false);
$oPdf->Output();
