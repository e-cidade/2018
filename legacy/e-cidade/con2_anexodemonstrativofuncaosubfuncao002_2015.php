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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

require_once modification("fpdf151/PDFDocument.php");
require_once modification("fpdf151/assinatura.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("model/relatorioContabil.model.php");

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

$oGet         = db_utils::postMemory($_GET);
$sTipoPeriodo = $oGet->sPeriodo;
$sPeriodo     = $oGet->sBimestre;

if ($oGet->sPeriodo == 'Mensal') {
  $sPeriodo = $oGet->sMes;
}

$dtAnousu      = db_getsession("DB_anousu");
$sInstituicao  = db_getsession("DB_instit");
$xinstit       = explode("-",$oGet->sOrigem);

$rsInstituicao = db_query("select munic, uf from db_config where codigo in (".str_replace('-',', ',$oGet->sOrigem).") ");

$oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();

$sDescricaoInstituicao  = DemonstrativoFiscal::getEnteFederativo($oPrefeitura);

if(trim($xinstit[0])){

  if(count($xinstit) == 1){

    $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($xinstit[0]);
    $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
  }
}else{

  $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($instit);
  $sDescricaoInstituicao = DemonstrativoFiscal::getEnteFederativo($oInstituicao);
}

$oAssinatura            = new cl_assinatura;
$sNivela                = substr(@$nivel,0,1);
$sele_work              = ' o58_instit in ('.str_replace('-',', ',$oGet->sOrigem).')';

$sSqlOrgaos  = "select distinct o41_orgao                                                                         ";
$sSqlOrgaos .= "   from orcunidade                                                                                ";
$sSqlOrgaos .= "     inner join orcorgao on                                                                       ";
$sSqlOrgaos .= "                         o41_orgao = o40_orgao and o40_anousu = {$dtAnousu}                       ";
$sSqlOrgaos .= "     where o41_anousu = {$dtAnousu} and o41_instit  in (".str_replace('-', ',', $oGet->sOrigem).")";

$res_orgaos     = @db_query($sSqlOrgaos);
$iNumeroLinhas  = @pg_numrows($res_orgaos);
$sOrgao         = "";
$sSeparador     = "";

if ($iNumeroLinhas != false){

  for($i = 0; $i < $iNumeroLinhas; $i++){

    $oOrgao    = db_utils::fieldsmemory($res_orgaos,$i);
    $sOrgao   .= $sSeparador."pai_".$oOrgao->o41_orgao;
    $sSeparador = "-";
  }
}
db_query("begin");
db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");

$sCampos = split("-",$sOrgao);

for($i = 0;$i < sizeof($sCampos);$i++){

  $sConcatenaWhere = '';
  $sVirgula        = '';
  $sCamposSplit    = split("_",$sCampos[$i]);

  for($j = 0;$j < sizeof($sCamposSplit);$j++){

    if($j > 0){

      $sConcatenaWhere .= $sVirgula.$sCamposSplit[$j];
      $sVirgula         = ', ';
    }
  }

  switch($sNivela) {

    case 1 :{

      $sConcatenaWhere .= ",0,0,0,0,0,0,0";
      break;
    }

    case 2 :{

      $sConcatenaWhere .= ",0,0,0,0,0,0";
      break;
    }

    case 3 :{

      $sConcatenaWhere .= ",0,0,0,0,0";
      break;
    }

    case 4 :{

      $sConcatenaWhere .= ",0,0,0,0";
      break;
    }
    case 5 :{

      $sConcatenaWhere .= ",0,0,0";
      break;
    }
    case 6 :{

      $sConcatenaWhere .= ",0,0";
      break;
    }
    case 7 :{

      $sConcatenaWhere .= ",0";
      break;
    }
  }
  db_query("insert into t values($sConcatenaWhere)");
}

db_query("commit");
$oDaoPeriodo    = db_utils::getDao("periodo");

if ($oGet->sPeriodo == "Mensal") {

  $iCodigoPeriodo = $sMes;
  $sTipoPeriodo   = "Mês";
}

if ($oGet->sPeriodo == "Bimestral") {

  $iCodigoPeriodo = $sBimestre;
  $sTipoPeriodo   = "Bimestre";
}

$sSqlPeriodo   = $oDaoPeriodo->sql_query($iCodigoPeriodo);
$sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
$sData         = $sSiglaPeriodo;
$sDadosPeriodo = data_periodo($dtAnousu,$sData);

$sPeriodoImpressao        = strtoupper($sDadosPeriodo["periodo"]);
$sPeriodoInicioImpressao  = split("-",$sDadosPeriodo[0]);
$sPeriodoFimImpressao     = split("-",$sDadosPeriodo[1]);

$sMesInicial = strtoupper(db_mes($sPeriodoInicioImpressao[1]));
$sMesFim     = strtoupper(db_mes($sPeriodoFimImpressao[1]));
$sDataInicio = $sDadosPeriodo[0];
$sDataFim    = $sDadosPeriodo[1];

// $grupoini = 1;
// $grupofin = 3;

$xtipo  = "BALANÇO";
$origem = "B";

$aHeaders = array();

$aHeaders[] = $sDescricaoInstituicao;

if (count($xinstit) == 1) {
  $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($xinstit[0]);

  if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
    $aHeaders[] = $oInstituicao->getDescricao();
  }
}

$aHeaders[] = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$aHeaders[] = "DEMONSTRATIVO DA EXECUÇÃO DAS DESPESAS POR FUNÇÃO/SUBFUNÇÃO";
$aHeaders[] = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

if ( $sTipoPeriodo   == "Bimestre") {

  $aHeaders[] = "JANEIRO A ".$sMesFim."/".$dtAnousu." - ".$sPeriodoImpressao." ".$sMesInicial."-".$sMesFim;
} else {

  $aHeaders[] = "PERÍODO : ".$sMesFim."/".$dtAnousu;
}


/**
 * Cria StdClass com as propriedades necessárias já setadas
 * @return stdClass
 */
function inicializarDespesa() {

  $oTipoDespesa = new stdClass();
  $oTipoDespesa->nTotalDotacaoInicial            = 0;
  $oTipoDespesa->nTotalDotacaoAtualizada         = 0;
  $oTipoDespesa->nTotalDespesasEmpenhadas        = 0;
  $oTipoDespesa->nTotalDespesasEmpenhadasPeriodo = 0;
  $oTipoDespesa->nSaldoC                         = 0;
  $oTipoDespesa->nTotalDespesasLiquidadas        = 0;
  $oTipoDespesa->nTotalDespesasLiquidadasPeriodo = 0;
  $oTipoDespesa->nSaldoE                         = 0;
  $oTipoDespesa->nInscritos                      = 0;
  $oTipoDespesa->nPercentualB                    = 0;
  $oTipoDespesa->nPercentualD                    = 0;

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
 * @return StdClass
 */
function contabilizarDespesa ($oTipoDespesa,$oStdSubfuncao,$sFuncao,$sSubfuncao,$sDescricaoFuncao,$sDescricaoSubFuncao) {

  /**
   * Verifica se o Tipo de despesa já foi setado
   * true  = realiza somatório, computando os valores da nova despesa
   * false = atribui os valores para totalização e instancia array das despesas, para serem classificadas
   */
  if (!isset($oTipoDespesa)) {

    $oTipoDespesa           = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes = array();

  } else {

    $oTipoDespesa->nTotalDotacaoInicial            += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->nTotalDotacaoAtualizada         += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->nTotalDespesasEmpenhadas        += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->nSaldoC                         += $oStdSubfuncao->nSaldoC;
    $oTipoDespesa->nTotalDespesasLiquidadas        += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->nSaldoE                         += $oStdSubfuncao->nSaldoE;
    $oTipoDespesa->nPercentualB                    += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->nPercentualD                    += $oStdSubfuncao->nPercentualD;
    $oTipoDespesa->nInscritos                      += $oStdSubfuncao->nInscritos;
  }

  /**
   * Verifica se o tipo da função é nova, dentro do Tipo
   * Caso seja novo, adiciona o novo tipo e computa os valores da despesa, nos totalizadores
   * Caso contrário, apenas atualiza os totalizadores
   */
  if (!isset($oTipoDespesa->aFuncoes[$sFuncao])) {

    $oTipoDespesa->aFuncoes[$sFuncao] = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->sDescricao  = $sDescricaoFuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes = array();

  } else {

    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDotacaoInicial            += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDotacaoAtualizada         += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasEmpenhadas        += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->nSaldoC                         += $oStdSubfuncao->nSaldoC;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasLiquidadas        += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->nSaldoE                         += $oStdSubfuncao->nSaldoE;
    $oTipoDespesa->aFuncoes[$sFuncao]->nPercentualB                    += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->aFuncoes[$sFuncao]->nPercentualD                    += $oStdSubfuncao->nPercentualD;
    $oTipoDespesa->aFuncoes[$sFuncao]->nInscritos                      += $oStdSubfuncao->nInscritos ;

  }

  /**
   * Verifica se o tipo da subfunção é nova, dentro da função
   * Caso seja novo, adiciona o novo tipo e computa os valores da despesa, nos totalizadores
   * Caso contrário, apenas atualiza os totalizadores da subfunção
   */
  if (!isset($oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]))  {

    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]             = clone $oStdSubfuncao;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->sDescricao = $sDescricaoSubFuncao;

  } else {

    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDotacaoInicial            += $oStdSubfuncao->nTotalDotacaoInicial;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDotacaoAtualizada         += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasEmpenhadas        += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nSaldoC                         += $oStdSubfuncao->nSaldoC;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasLiquidadas        += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nSaldoE                         += $oStdSubfuncao->nSaldoE;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nPercentualB                    += $oStdSubfuncao->nPercentualB;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nPercentualD                    += $oStdSubfuncao->nPercentualD;
    $oTipoDespesa->aFuncoes[$sFuncao]->aSubfuncoes[$sSubfuncao]->nInscritos                      += $oStdSubfuncao->nInscritos ;
  }

  return $oTipoDespesa;
}



/**
 * Constroi array de despesas, de acordo com SQL repassado
 * Agrupa cada uma delas, dentro de um tipo, função e subfunção
 * @param  string   $sSQL      //SQL para busca das despesas
 * @return StdClass $oRetorno  //agrupamento de despesas
 * */

function construirArraysDespesas($sSql, $lDiferenciaTipo = true) {

  $rsSubfuncoes      = db_query($sSql);
  $iNumeroResultados = pg_num_rows($rsSubfuncoes);

  $oRPPS          = inicializarDespesa();
  $oContingencia  = inicializarDespesa();
  $oOutras        = inicializarDespesa();

  $oRetorno                                  = new stdClass();
  $oRetorno->nTotalDotacaoInicial            = 0;
  $oRetorno->nTotalDotacaoAtualizada         = 0;
  $oRetorno->nTotalDespesasEmpenhadas        = 0;
  $oRetorno->nTotalDespesasEmpenhadasPeriodo = 0;
  $oRetorno->nPercentualB                    = 0;
  $oRetorno->nSaldoC                         = 0;
  $oRetorno->nTotalDespesasLiquidadas        = 0;
  $oRetorno->nTotalDespesasLiquidadasPeriodo = 0;
  $oRetorno->nPercentualD                    = 0;
  $oRetorno->nSaldoE                         = 0;
  $oRetorno->nInscritos                      = 0;

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

    $oStdSubfuncao = new StdClass();
    $oStdSubfuncao->nTotalDotacaoInicial            = $oDespesa->dot_ini_p;
    $oStdSubfuncao->nTotalDotacaoAtualizada         = $oDespesa->dot_ini_p + $oDespesa->suplementado_p - $oDespesa->reduzir_p;
    $oStdSubfuncao->nTotalDespesasEmpenhadas        = $oDespesa->empenhado_p - $oDespesa->anulado_p;
    $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo = $oDespesa->empenhado_acumulado_p - $oDespesa->anulado_acumulado_p;
    $oStdSubfuncao->nSaldoC                         = $oStdSubfuncao->nTotalDotacaoAtualizada - $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oStdSubfuncao->nTotalDespesasLiquidadas        = $oDespesa->liquidado_p;
    $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo = $oDespesa->liquidado_acumulado_p;
    $oStdSubfuncao->nSaldoE                         = $oStdSubfuncao->nTotalDotacaoAtualizada - $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oStdSubfuncao->nInscritos                      = abs($oDespesa->empenhado_acumulado_p-$oDespesa->anulado_acumulado_p-$oDespesa->liquidado_acumulado_p);

    $oStdSubfuncao->nPercentualB                    = 0;
    $oStdSubfuncao->nPercentualD                    = 0;

    $sTipo = substr($oDespesa->o58_elemento, 0, 2);

    /**
     * Validacao do tipo de instituicao. caso a dotacao esteja vinculada ao tipo 5/6 é RPPS, o que altera
     * a variavel $sTipo para 37.
     */
    $sSqlBuscaDotacaoTipo  = " select 1                                                    ";
    $sSqlBuscaDotacaoTipo .= "    from orcdotacao                                          ";
    $sSqlBuscaDotacaoTipo .= "         inner join orcelemento   on o58_anousu = o56_anousu ";
    $sSqlBuscaDotacaoTipo .= "                                 and o58_codele = o56_codele ";
    $sSqlBuscaDotacaoTipo .= "         inner join db_config on codigo = o58_instit         ";
    $sSqlBuscaDotacaoTipo .= "   where o58_anousu = {$iAnoUsoFuncao}                       ";
    $sSqlBuscaDotacaoTipo .= "     and substr(o56_elemento,1,2) = '39'                     ";
    $sSqlBuscaDotacaoTipo .= "     and db21_tipoinstit in (5,6)                            ";
    $sSqlBuscaDotacaoTipo .= "     and o58_coddot = {$oDespesa->o58_coddot}                ";

    $rsBuscaDotacaoTipo   = db_query($sSqlBuscaDotacaoTipo);
    $iLinhasEncontradas   = pg_num_rows($rsBuscaDotacaoTipo);

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
        $oRPPS          = contabilizarDespesa($oRPPS,$oStdSubfuncao,$sFuncao,$sSubfuncao,$sDescricaoFuncao,$sDescricaoSubFuncao);
        break;
      }

      case '39':{

        $sTipoDescricao = 'Contingencia';
        $oContingencia  = contabilizarDespesa($oContingencia,$oStdSubfuncao,$sFuncao,$sSubfuncao,$sDescricaoFuncao,$sDescricaoSubFuncao);
        break;
      }

      default:{

        $sTipoDescricao = 'Outras';
        $oOutras        = contabilizarDespesa($oOutras,$oStdSubfuncao,$sFuncao,$sSubfuncao,$sDescricaoFuncao,$sDescricaoSubFuncao);
        break;
      }
    }

    $oRetorno->nTotalDotacaoInicial            += $oStdSubfuncao->nTotalDotacaoInicial;
    $oRetorno->nTotalDotacaoAtualizada         += $oStdSubfuncao->nTotalDotacaoAtualizada;
    $oRetorno->nTotalDespesasEmpenhadas        += $oStdSubfuncao->nTotalDespesasEmpenhadas;
    $oRetorno->nTotalDespesasEmpenhadasPeriodo += $oStdSubfuncao->nTotalDespesasEmpenhadasPeriodo;
    $oRetorno->nSaldoC                         += $oStdSubfuncao->nSaldoC;
    $oRetorno->nTotalDespesasLiquidadas        += $oStdSubfuncao->nTotalDespesasLiquidadas;
    $oRetorno->nTotalDespesasLiquidadasPeriodo += $oStdSubfuncao->nTotalDespesasLiquidadasPeriodo;
    $oRetorno->nSaldoE                         += $oStdSubfuncao->nSaldoE;
    $oRetorno->nInscritos                      += $oStdSubfuncao->nInscritos;
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

  $nTotalB = $oDespesas->nTotalEmpenhado;
  $nTotalD = $oDespesas->nTotalLiquidado;

  if ($nTotalB) {
    $oDespesas->nPercentualB = ($oDespesas->nTotalDespesasEmpenhadasPeriodo / $nTotalB) * 100;
  }

  if ($nTotalD) {
    $oDespesas->nPercentualD = ($oDespesas->nTotalDespesasLiquidadasPeriodo / $nTotalD) * 100;
  }

  if (isset($oDespesas->oOutras)) {

    foreach ($oDespesas->oOutras->aFuncoes as $oFuncao){

      if ($nTotalB) {
        $oFuncao->nPercentualB = ($oFuncao->nTotalDespesasEmpenhadasPeriodo / $nTotalB) * 100;
      }

      if ($nTotalD) {
        $oFuncao->nPercentualD = ($oFuncao->nTotalDespesasLiquidadasPeriodo / $nTotalD) * 100;
      }

      foreach ($oFuncao->aSubfuncoes as $oSubfuncao) {

        if ($nTotalB) {
          $oSubfuncao->nPercentualB = ($oSubfuncao->nTotalDespesasEmpenhadasPeriodo / $nTotalB) * 100;
        }

        if ($nTotalD){
          $oSubfuncao->nPercentualD = ($oSubfuncao->nTotalDespesasLiquidadasPeriodo / $nTotalD) * 100;
        }

      }
    }
  }

  if (isset($oDespesas->oRPPS)) {

    if ($nTotalB) {
      $oFuncao->oRPPS["nPercentualB"] = ($oDespesas->oRPPS->nTotalDespesasEmpenhadasPeriodo / $nTotalB) * 100;
    }

    if ($nTotalD){
      $oFuncao->oRPPS["nPercentualD"] = ($oDespesas->oRPPS->nTotalDespesasLiquidadasPeriodo / $nTotalD) * 100;
    }
  }

  if (isset($oDespesas->oContingencia)) {

    if ($nTotalB) {
      $oFuncao->oContingencia["nPercentualB"] = ($oDespesas->oContingencia->nTotalDespesasEmpenhadasPeriodo / $nTotalB) * 100;
    }

    if ($nTotalD) {
      $oFuncao->oContingencia["nPercentualD"] = ($oDespesas->oContingencia->nTotalDespesasLiquidadasPeriodo / $nTotalD) * 100;
    }
  }
}

/**
 * Funções para impressão do pdf
 */
function imprimirCabecalho(&$oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha,$sTipoFonte ='', $lDescricaoPorcentagem = false) {

  $oPdf->setFontSize(5);
  $oPdf->setBold(true);
  $nWidth = $oPdf->getAvailWidth();
  $oPdf->setAutoNewLineMulticell(false);

  $nFirstColumn = 0.28;
  if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {
    $nFirstColumn = 0.2;
  }

  $oPdf->cell($nWidth*$nFirstColumn, $iAlturaLinha*3, "FUNÇÃO/SUBFUNÇÃO", "TRB", 0, 'C');
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "DOTAÇÃO\nINICIAL\n ", "TBR", 'C');
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "DOTAÇÃO\nATUALIZADA\n(a)", "TB", 'C');

  $iX = $oPdf->getX();
  $iY = $oPdf->getY();

  $oPdf->cell($nWidth*0.2, $iAlturaLinha, "DESPESAS EMPENHADAS", "TBLR", 1, 'C');
  $oPdf->setX($iX);
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "No {$sTipoPeriodo}\n ", "BL", 'C');
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "Até o {$sTipoPeriodo}\n(b)", "BL", 'C');
  
  if ($lDescricaoPorcentagem) {

    $oPdf->multiCell($nWidth*0.04, $iAlturaLinha, "%\n(b/ III b)", "BLR", 'C');
  } else {

    $oPdf->multiCell($nWidth*0.04, $iAlturaLinha, "%\n(b/total b)", "BLR", 'C');
  }

  $oPdf->setXY($oPdf->getX(), $iY);

  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "SALDO\n \n(c) = (a-b)", "BRT", 'C');

  $iX = $oPdf->getX();
  $iY = $oPdf->getY();

  $oPdf->cell($nWidth*0.2, $iAlturaLinha, "DESPESAS LIQUIDADAS", "TBR", 1, 'C');
  $oPdf->setX($iX);
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "No {$sTipoPeriodo}\n ", "B", 'C');
  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "Até o {$sTipoPeriodo}\n(d)", "BL", 'C');

  if ($lDescricaoPorcentagem) {

    $oPdf->multiCell($nWidth*0.04, $iAlturaLinha, "%\n(d/ III d)", "BLR", 'C');
  } else {

    $oPdf->multiCell($nWidth*0.04, $iAlturaLinha, "%\n(d/total d)", "BLR", 'C');
  }

  $oPdf->setXY($oPdf->getX(), $iY);

  if (!($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11")) {
    $oPdf->setAutoNewLineMulticell(true);
  }

  $oPdf->multiCell($nWidth*0.08, $iAlturaLinha, "SALDO\n \n(e) = (a-d)", "BT", 'C');

  if ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11") {
    $oPdf->setAutoNewLineMulticell(true);
    $oPdf->multiCell($nWidth*0.08, $iAlturaLinha-1, "INSCRITAS EM\nRESTOS A PAGAR\nNÃO PROCESSADOS\n(f)", "BLT", 'C');
  }

  $oPdf->setBold(false);
}


function imprimirCabecalhoPaginasInternas(&$oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, $sTipoFonte ='', $lPorcentagem = false) {

  if($oPdf->gety() > $oPdf->h-35) {

    $oPdf->cell($oPdf->getAvailWidth(),$iAlturaLinha,'Continua na Página '.($oPdf->pageNo()+1)."/{nb}","TB",1,"R",0);
    $oPdf->addpage();
    $oPdf->ln(2);
    $oPdf->cell($oPdf->getAvailWidth(),$iAlturaLinha,'Continuação '.($oPdf->pageNo()-1)."/{nb}","B",1,"R",0);

    $oPdf->setBold(true);
    $oPdf->cell($oPdf->getAvailWidth()*0.5,$iAlturaLinha,'RREO - Anexo 2 (LRF, Art. 52, inciso II, alínea "c")',"B",0,"L",0);
    $oPdf->cell($oPdf->getAvailWidth(),$iAlturaLinha,'R$ 1,00',"B",1,"R",0);
    $oPdf->setBold(false);

    imprimirCabecalho($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha,$sTipoFonte, $lPorcentagem);
    $oPdf->setfont('arial',$sTipoFonte,5);
  }
}


function imprimeLinha(&$oPdf, $oDespesas, $sPeriodo, $sTipoPeriodo, $iAlturaLinha, $sDescricao = null, $sNivel = null, $sTipoFonte ='', $lPorcentagem = false) {

  $lImprimeInscritos = ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11");

  imprimirCabecalhoPaginasInternas($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha,$sTipoFonte, $lPorcentagem);

  if (!isset($sDescricao)) {
    $sDescricao = $oDespesas->sDescricao;
  }

  $nWidth = $oPdf->getAvailWidth();
  $nFirstColumn = 0.28;

  if ($lImprimeInscritos) {
    $nFirstColumn = 0.2;
  }

  $sDescricao = "{$sNivel}{$sDescricao}";

  while ($oPdf->GetStringWidth($sDescricao) > $nWidth*$nFirstColumn) {
    $sDescricao = substr($sDescricao, 0, strlen($sDescricao)-1);
  }

  $oPdf->cell($nWidth*$nFirstColumn, $iAlturaLinha, $sDescricao, "R", 0, "L", 0);
  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDotacaoInicial, 'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDotacaoAtualizada,'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDespesasEmpenhadas, 'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDespesasEmpenhadasPeriodo,'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.04, $iAlturaLinha, db_formatar($oDespesas->nPercentualB, 'f'), "LR", 0, "R");

  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nSaldoC, 'f'), "LR", 0, "R");

  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDespesasLiquidadas, 'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nTotalDespesasLiquidadasPeriodo,'f'), "LR", 0, "R");
  $oPdf->cell($nWidth*0.04, $iAlturaLinha, db_formatar($oDespesas->nPercentualD, 'f'), "LR", 0, "R");

  $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nSaldoE, 'f'), "L", !$lImprimeInscritos, "R");

  if ($lImprimeInscritos) {
    $oPdf->cell($nWidth*0.08, $iAlturaLinha, db_formatar($oDespesas->nInscritos,'f'),"L",1,"R");
  }
}



/**
 * Imprime um agrupamento de despesas, a partir de um tipo
 * imprime todos os subníveis em sequência
 * @param Pdf          $oPdf
 * @param StdClass     $oDespesas     //agrupamento de despesas
 * @param string       $sPeriodo      //descrição do periodo
 * @param string       $sTipoPeriodo  //tipo do periodo (bimestre ou mês)
 * @param integer      $iAlturaLinha  //altura da linha, na impressão
 */
function imprimeGrupoDespesas(&$oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha, $lDescricaoPorcentagem=false) {

  if(!isset($oDespesas->aFuncoes)) {
   return;
  }

  $sNivelSubfuncao = "  ";

  foreach ($oDespesas->aFuncoes as $oFuncao) {

    imprimirCabecalhoPaginasInternas($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha, '', $lDescricaoPorcentagem);
    $oPdf->SetFont('arial', 'b', 5);
    imprimeLinha($oPdf,$oFuncao,$sPeriodo,$sTipoPeriodo,$iAlturaLinha);
    $oPdf->SetFont('arial', '', 5);

    foreach($oFuncao->aSubfuncoes as $oSubfuncao) {

      imprimirCabecalhoPaginasInternas($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha, '', $lDescricaoPorcentagem);
      $oPdf->SetFont('arial', '', 5);
      imprimeLinha($oPdf,$oSubfuncao,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,null,$sNivelSubfuncao);

    }
  }
}

/**
 *   SQL para buscar todas Despesas exceto intra-orçamentárias
 */

$sWherePadrao = " and substr(o56_elemento,4,2) != '91' ";
$sDotacao     = db_dotacaosaldo(8, 2 , 4  ,true ,$sele_work." {$sWherePadrao}",$dtAnousu,$sDataInicio,$sDataFim,8,0,true,1,false);

$sSql  = "   select                                                                          ";
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
$sSql .= "   from ($sDotacao) as x                                                           ";
$sSql .= "     group by                                                                      ";
$sSql .= "       o58_subfuncao,o53_descr,o58_funcao,o52_descr,o58_elemento,o58_coddot        ";
$sSql .= "     order by                                                                      ";
$sSql .= "       o58_funcao,                                                                 ";
$sSql .= "       o58_subfuncao                                                               ";

$lDiferenciaTipo = true;
$oDespesasExtra  = construirArraysDespesas($sSql,$lDiferenciaTipo);

/**
 * Despesas Intra-Orçamentárias
 */

$sSqlIntraOrcamentaria = db_dotacaosaldo(8, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) = '91'",$dtAnousu,$sDataInicio,$sDataFim,8,0,true,1,false);
$sSql  = " select                                                                           ";
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

$lDiferenciaTipo = false;
$oDespesasIntra  = construirArraysDespesas($sSql, $lDiferenciaTipo);

$oDespesasExtra->nTotalEmpenhado = $oDespesasExtra->nTotalDespesasEmpenhadasPeriodo + $oDespesasIntra->nTotalDespesasEmpenhadasPeriodo;
$oDespesasExtra->nTotalLiquidado = $oDespesasExtra->nTotalDespesasLiquidadasPeriodo + $oDespesasIntra->nTotalDespesasLiquidadasPeriodo;

$oDespesasIntra->nTotalEmpenhado = $oDespesasIntra->nTotalDespesasEmpenhadasPeriodo;
$oDespesasIntra->nTotalLiquidado = $oDespesasIntra->nTotalDespesasLiquidadasPeriodo;

$oDespesasIntra->nPercentualB = ($oDespesasIntra->nTotalEmpenhado/$oDespesasExtra->nTotalEmpenhado) * 100;
$oDespesasIntra->nPercentualD = ($oDespesasIntra->nTotalLiquidado/$oDespesasExtra->nTotalLiquidado) * 100;

/**
 *
 * Definições do PDF
 * inicio do algoritmo para impressão
 */
calculaPercetual($oDespesasExtra);

$oPdf = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);

foreach ($aHeaders as $sHeader) {
  $oPdf->addHeaderDescription($sHeader);
}

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 4;

//primeiro cabeçalho
$oPdf->AddPage();
$oPdf->setFontSize(5);
$oPdf->setBold(true);

$oPdf->cell($oPdf->getAvailWidth()*0.5, $iAlturaLinha, 'RREO - Anexo 2 (LRF, Art. 52, inciso II, alínea "c")', '', 0, "L", 0);
$oPdf->cell($oPdf->getAvailWidth(), $iAlturaLinha, 'R$ 1,00', '', 1, "R", 0);
imprimirCabecalho($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha);

//Imprime Despesas Exceto Intra-Orçamentarias, que não sejam RPPS e de Contingência
$oDespesas = $oDespesasExtra;
$oPdf->SetFont('arial', 'b', 5);
$sDescricao = "DESPESAS (EXCETO INTRA-ORÇAMENTARIAS (I)";
//totalizador das despesas
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao,null,'b');

//Totas as Despesas que não sejam Reserva RPPS e Contingência
$oDespesas  = $oDespesas->oOutras;
imprimeGrupoDespesas($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha);

//Imprime Reserva Contingência
$oPdf->SetFont('arial', 'b', 5);
$oDespesas  = $oDespesasExtra->oContingencia;
$sDescricao = "RESERVA DE CONTINGÊNCIA";
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao,null,'b');
$oPdf->SetFont('arial', '', 5);
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao, "  ");

//Imprime Reserva RPPS
$oPdf->SetFont('arial', 'b', 5);
$oDespesas  = $oDespesasExtra->oRPPS;
$sDescricao = "RESERVA DO RPPS";
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao,null,'b');
$oPdf->SetFont('arial', '', 5);
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao, "  ");

/**
 * Despesas Intra-Orçamentárias
 */

// $sSqlIntraOrcamentaria = db_dotacaosaldo(8, 2 , 4  ,true ,$sele_work." and substr(o56_elemento,4,2) = '91'",$dtAnousu,$sDataInicio,$sDataFim,8,0,true,1,false);
// $sSql  = " select                                                                           ";
// $sSql .= "     o58_coddot,                                                                  ";
// $sSql .= "     o58_funcao,                                                                  ";
// $sSql .= "     o52_descr,                                                                   ";
// $sSql .= "     o58_subfuncao,                                                               ";
// $sSql .= "     o53_descr,                                                                   ";
// $sSql .= "     o58_elemento,                                                                ";
// $sSql .= "     sum(dot_ini) as dot_ini_p,                                                   ";
// $sSql .= "     sum(suplementado_acumulado) as suplementado_p,                               ";
// $sSql .= "     sum(reduzido_acumulado) as reduzir_p,                                        ";
// $sSql .= "     sum(empenhado) as empenhado_p,                                               ";
// $sSql .= "     sum(anulado) as anulado_p,                                                   ";
// $sSql .= "     sum(empenhado_acumulado) as empenhado_acumulado_p,                           ";
// $sSql .= "     sum(anulado_acumulado) as anulado_acumulado_p,                               ";
// $sSql .= "     sum(liquidado) as liquidado_p,                                               ";
// $sSql .= "     sum(liquidado_acumulado) as liquidado_acumulado_p,                           ";
// $sSql .= "     sum(empenhado_acumulado-anulado_acumulado-liquidado_acumulado) as inscrito_p ";
// $sSql .= "  from ($sSqlIntraOrcamentaria) as x                                              ";
// $sSql .= "  group by                                                                        ";
// $sSql .= "      o58_subfuncao, o53_descr, o58_funcao, o52_descr, o58_elemento, o58_coddot   ";
// $sSql .= "  order by                                                                        ";
// $sSql .= "     o58_funcao,                                                                  ";
// $sSql .= "     o58_subfuncao                                                                ";


$iAltura           = 4;
$lImprimeInscritos = ($sTipoPeriodo == 'Bimestre' && $sPeriodo == "11");
$nWidth            = $oPdf->getAvailWidth();

$nFirstColumn = 0.28;

if ($lImprimeInscritos) {
  $nFirstColumn = 0.2;
}

// $lDiferenciaTipo = false;
// $oDespesasIntra  = construirArraysDespesas($sSql, $lDiferenciaTipo);

//Imprime Despesas Exceto Intra-Orçamentarias, que não sejam RPPS e de Contingência
$oDespesas = $oDespesasIntra;
$oPdf->SetFont('arial', 'b', 5);
$sDescricao = "DESPESAS (INTRA-ORÇAMENTÁRIAS)(II)";
//totalizador das despesas
imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao,null,'b');

$nTotalDotacaoInicial             = $oDespesasExtra->nTotalDotacaoInicial            + $oDespesasIntra->nTotalDotacaoInicial;
$nTotalDotacaoAtualizada          = $oDespesasExtra->nTotalDotacaoAtualizada         + $oDespesasIntra->nTotalDotacaoAtualizada;
$nTotalDespesasEmpenhadas         = $oDespesasExtra->nTotalDespesasEmpenhadas        + $oDespesasIntra->nTotalDespesasEmpenhadas;
$nTotalDespesasEmpenhadasPeriodo  = $oDespesasExtra->nTotalDespesasEmpenhadasPeriodo + $oDespesasIntra->nTotalDespesasEmpenhadasPeriodo;
$nTotalSaldoC                     = $oDespesasExtra->nSaldoC                         + $oDespesasIntra->nSaldoC;
$nTotalDespesasLiquidadas         = $oDespesasExtra->nTotalDespesasLiquidadas        + $oDespesasIntra->nTotalDespesasLiquidadas;
$nTotalDespesasLiquidadasPeriodo  = $oDespesasExtra->nTotalDespesasLiquidadasPeriodo + $oDespesasIntra->nTotalDespesasLiquidadasPeriodo;
$nTotalSaldoE                     = $oDespesasExtra->nSaldoE                         + $oDespesasIntra->nSaldoE;
$nInscritos                       = $oDespesasExtra->nInscritos                      + $oDespesasIntra->nInscritos;

imprimirCabecalhoPaginasInternas($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha);
$oPdf->setBold(true);
$oPdf->cell($nWidth*$nFirstColumn, $iAltura, "TOTAL (III) = (I + II)","RTB",0,"L");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDotacaoInicial, 'f'),"LRTB",0,"R");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDotacaoAtualizada, 'f'),"LRTB",0,"R");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDespesasEmpenhadas       , 'f'),"LRTB",0,"R");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDespesasEmpenhadasPeriodo, 'f') ,"LRTB",0,"R");
$oPdf->cell($nWidth*0.04, $iAltura, '-' ,"LRTB",0,"C");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalSaldoC, 'f') ,"LRTB",0,"R");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDespesasLiquidadas       , 'f'),"LRTB",0,"R");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalDespesasLiquidadasPeriodo, 'f'),"LRTB",0,"R");
$oPdf->cell($nWidth*0.04, $iAltura, '-' ,"LRTB",0,"C");
$oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nTotalSaldoE, 'f') ,"LTB",!$lImprimeInscritos,"R");

if ($lImprimeInscritos) {
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nInscritos,'f'),"LTB",1,"R");
}

/**
 * Imprime o quadro das intra-orçamentárias
 */

if (isset($oDespesas->oOutras->aFuncoes) && !empty($oDespesas->oOutras->aFuncoes)) {

  // Armazena o valor correto
  $nTotalEmpenhadoTmp = $oDespesas->nTotalDespesasEmpenhadasPeriodo;
  $nTotalLiquidadoTmp = $oDespesas->nTotalDespesasLiquidadasPeriodo;
  // Altera o valor para recalcular as porcentagens conforme o manual %(b/ III b) e %(d/ III d)
  $oDespesas->nTotalEmpenhado = $nTotalDespesasEmpenhadasPeriodo;
  $oDespesas->nTotalLiquidado = $nTotalDespesasLiquidadasPeriodo;
  calculaPercetual($oDespesas);

  // Retorna o valor 
  $oDespesas->nTotalEmpenhado = $nTotalEmpenhadoTmp;
  $oDespesas->nTotalLiquidado = $nTotalLiquidadoTmp;
  
  $oPdf->ln();
  imprimirCabecalho($oPdf, $sTipoPeriodo, $sPeriodo, $iAlturaLinha, '', 1);

  $oPdf->SetFont('arial', 'b', 5);
  $sDescricao = "DESPESAS (INTRA-ORÇAMENTÁRIAS)(II)";
  //totalizador das despesas
  imprimeLinha($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha,$sDescricao,null,'b', 1);

  //Totas as Despesas que não sejam Reserva RPPS e Contingência
  $oDespesas  = $oDespesas->oOutras;
  $oPdf->SetFont('arial', 'b', 5);
  imprimeGrupoDespesas($oPdf,$oDespesas,$sPeriodo,$sTipoPeriodo,$iAlturaLinha, 1);

  imprimirCabecalhoPaginasInternas($oPdf,$sTipoPeriodo,$sPeriodo,$iAlturaLinha, '', 1);

  $oPdf->setBold(true);
  $oPdf->cell($nWidth*$nFirstColumn, $iAltura, "TOTAL","RTB",0,"L");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDotacaoInicial, 'f'),"LRTB",0,"R");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDotacaoAtualizada, 'f'),"LRTB",0,"R");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDespesasEmpenhadas, 'f'),"LRTB",0,"R");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDespesasEmpenhadasPeriodo, 'f') ,"LRTB",0,"R");
  $oPdf->cell($nWidth*0.04, $iAltura, '-' ,"LRTB",0,"C");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nSaldoC, 'f') ,"LRTB",0,"R");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDespesasLiquidadas, 'f'),"LRTB",0,"R");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nTotalDespesasLiquidadasPeriodo, 'f'),"LRTB",0,"R");
  $oPdf->cell($nWidth*0.04, $iAltura, '-' ,"LRTB",0,"C");
  $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($oDespesasIntra->nSaldoE, 'f') ,"LTB",!$lImprimeInscritos, "R");

  if ($lImprimeInscritos) {
    $oPdf->cell($nWidth*0.08, $iAltura, db_formatar($nInscritos,'f'),"LTB",1,"R");
  }

  $oPdf->ln(1);
}

$oRelatorio  = new relatorioContabil(96, false);
$oRelatorio->getNotaExplicativa($oPdf, $iCodigoPeriodo, 185);
$oPdf->ln(5);

if($oPdf->gety() > $oPdf->h-35) {

  $oPdf->cell($oPdf->getAvailWidth(),$iAlturaLinha,'Continua na Página '.($oPdf->pageNo()+1)."/{nb}","T",1,"R",0);
  $oPdf->cell($oPdf->getAvailWidth(),$iAlturaLinha,'',"T",1,"L",0);
  $oPdf->addpage();
  $oPdf->ln(30);
}

assinaturas($oPdf,$oAssinatura,'LRF',false, false);
$oPdf->showPDF("RREO_Anexo_II_DemonstrativoFuncaoSubfuncao_" . time());
