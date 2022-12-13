<?
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

set_time_limit(7000000000);

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_app.utils.php'));
require_once(modification("libs/db_libtxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libpostgres.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_conrelinfo_classe.php"));
require_once(modification("classes/db_empresto_classe.php"));
require_once(modification("classes/db_conrelvalor_classe.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("model/linhaRelatorioContabil.model.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXIRREO");
db_app::import("contabilidade.relatorios.AnexoXIVRREO");
db_app::import("contabilidade.relatorios.AnexoXVILRF");

$classinatura  = new cl_assinatura;
$orcparamrel   = new cl_orcparamrel;
$clconrelinfo  = new cl_conrelinfo;
$clempresto    = new cl_empresto;
$clconrelvalor = new cl_conrelvalor;

$instituicao  = str_replace("-", ",", $db_selinstit);
$anousu       = db_getsession("DB_anousu");
$instit_rpps  ='';

/*
 * Definos quando é o ultimo Bimestre.
 * quando for o ultimo bimestre, a informações diferentes no relatorio.
 */
if ($emite_rec_desp==1||$emite_proj==1){
  // seleciona instituio do RPPS
  $sql         = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
  $resultinst  = db_query($sql);
  $xvirg       = '';
  for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $instit_rpps .= $xvirg.$codigo; // salva insituio
    $xvirg        = ', ';
  }
}
$oDaoPeriodo     = db_utils::getDao("periodo");

$sSqlPeriodo   = $oDaoPeriodo->sql_query($bimestre);
$oPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0);

$dados   = data_periodo($anousu, $oPeriodo->o114_sigla); // no dbforms/db_funcoes.php

$dt_ini  = $dados[0]; // data inicial do perodo
$dt_fin  = $dados[1]; // data final do perodo
$iCodigoPeriodo  = $oPeriodo->o114_sequencial;
$sSiglaPeriodo   = $oPeriodo->o114_sigla;
$sPeriodo        = $oPeriodo->o114_sigla;
$periodo         = $oPeriodo->o114_sigla;

$periodo_selecao = $dados["periodo"];

$anousu_ant = $anousu-1;
$xinstit    = split("-",$db_selinstit);
$resultinst = db_query("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
db_fieldsmemory($resultinst,0);
$lUltimoBimestre = false;
if ($sSiglaPeriodo == "2S"|| $sSiglaPeriodo == "6B") {

  $lUltimoBimestre = true;
  $oAnexoXI  = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_IX, $anousu, new Periodo($bimestre));
  $oAnexoXI->setInstituicoes($instituicao);

  $oAnexoXIII = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_X, $anousu, new Periodo($bimestre));
  $oAnexoXIII->setInstituicoes($instituicao);

  $oAnexoXIV  = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_XI, $anousu, new Periodo($bimestre));
  $oAnexoXIV->setInstituicoes($instituicao);

  $aDadosAnexoXI   = $oAnexoXI->getDadosSimplificado();
  $aDadosAnexoXIII = $oAnexoXIII->getDadosSimplificado();
  $aDadosAnexoXIV  = $oAnexoXIV->getDadosSimplificado();
}

if (!isset($arqinclude)){

  $txt1  = "MUNICÍPIO DE ".$munic;
  $txt2  = "DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $txt3  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

  $mes     = split("-",$dt_ini);
  $mesini  = strtoupper(db_mes($mes[1]));
  $txt4    = "JANEIRO";
  $mes     = split("-",$dt_fin);
  $mesfin  = strtoupper(db_mes($mes[1]));
  $txt4   .= " A $mesfin/$anousu";

  if ($sSiglaPeriodo != "1S" && $sSiglaPeriodo != "2S") {

    $txt4 .= " - ".strtoupper($periodo_selecao)." $mesini - $mesfin";

  }

}
$lInResumido        = true;
if (@$emite_balorc == 1 || @$emite_desp_funcsub==1) {

  $db_filtro         = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
  $rsReceitaResumido = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
  db_query("drop table work_Receita");
  $sele_work   = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  $rsDespesaResumido = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);
  db_query("drop table work_dotacao");
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BALANCO ORCAMENTARIO - RECEITAS/
if (@$emite_balorc==1) {

  $iCodigoRelatorio        = 79;
  $total_inicial           = 0;
  $total_atualizada_balorc = 0;
  $total_nobim             = 0;
  $total_atebim            = 0;
  $total_deficit           = 0;
  $total_saldo_ant         = 0;

  for ($iLinha = 1; $iLinha <= 42; $iLinha++) {

    $aLinhaBalanceteReceita[$iLinha]             = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
    $aLinhaBalanceteReceita[$iLinha]->parametros = $aLinhaBalanceteReceita[$iLinha]->getParametros($anousu);
    $aLinhaBalanceteReceita[$iLinha]->setPeriodo($iCodigoPeriodo);
    $aColunas  = $aLinhaBalanceteReceita[$iLinha]->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
    foreach ($aColunas as $oLinhaColuna) {

       $total_inicial           += @$oLinhaColuna->colunas[1]->o117_valor;
       $total_atualizada_balorc += @$oLinhaColuna->colunas[2]->o117_valor;
       $total_nobim             += @$oLinhaColuna->colunas[3]->o117_valor;
       $total_atebim            += @$oLinhaColuna->colunas[4]->o117_valor;

    }
  }

  /**
   * Inclusão para retornar os dados da linha 61
   */

  for ($iLinha = 61; $iLinha <= 65; $iLinha++) {

    $aLinhaBalanceteReceita[$iLinha]             = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
    $aLinhaBalanceteReceita[$iLinha]->parametros = $aLinhaBalanceteReceita[$iLinha]->getParametros($anousu);
    $aLinhaBalanceteReceita[$iLinha]->setPeriodo($iCodigoPeriodo);
    $aColunas                                    = $aLinhaBalanceteReceita[$iLinha]->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);

    foreach ($aColunas as $oLinhaColuna) {

      $total_inicial           += @$oLinhaColuna->colunas[1]->o117_valor;
      $total_atualizada_balorc += @$oLinhaColuna->colunas[2]->o117_valor;
      $total_nobim             += @$oLinhaColuna->colunas[3]->o117_valor;
      $total_atebim            += @$oLinhaColuna->colunas[4]->o117_valor;
    }
  }




  // RECEITAS
  for ($i = 0;$i < pg_num_rows($rsReceitaResumido); $i++) {

    $oReceitaBalancete = db_utils::fieldsmemory($rsReceitaResumido, $i);
    $estrutural = $oReceitaBalancete->o57_fonte;
    for ($iLinha = 1; $iLinha <= 42; $iLinha++) {

      $oParametro = $aLinhaBalanceteReceita[$iLinha]->parametros;
      foreach ($oParametro->contas as $oEstrutural) {

        $oVerificacao = $aLinhaBalanceteReceita[$iLinha]->match($oEstrutural ,$oParametro->orcamento,$oReceitaBalancete, 1);
        if ($oVerificacao->match) {

          if ($oVerificacao->exclusao) {

            $oReceitaBalancete->saldo_inicial          *= -1;
            $oReceitaBalancete->saldo_inicial_prevadic *= -1;
            $oReceitaBalancete->saldo_arrecadado           *= -1;
            $oReceitaBalancete->saldo_arrecadado_acumulado *= -1;
          }
          $total_inicial           += $oReceitaBalancete->saldo_inicial;
          $total_atualizada_balorc += $oReceitaBalancete->saldo_inicial_prevadic;
          $total_nobim             += $oReceitaBalancete->saldo_arrecadado;
          $total_atebim            += $oReceitaBalancete->saldo_arrecadado_acumulado;
        }
      }
    }
    /**
     * Inclusão para retornar os dados da linha 61
     */


    for ($iLinha = 61; $iLinha <= 65; $iLinha++) {
      $oParametro = $aLinhaBalanceteReceita[$iLinha]->parametros;


      foreach ($oParametro->contas as $oEstrutural) {

        $oVerificacao = $aLinhaBalanceteReceita[$iLinha]->match($oEstrutural ,$oParametro->orcamento,$oReceitaBalancete, 1);
        if ($oVerificacao->match) {

          if ($oVerificacao->exclusao) {

            $oReceitaBalancete->saldo_inicial              *= -1;
            $oReceitaBalancete->saldo_inicial_prevadic     *= -1;
            $oReceitaBalancete->saldo_arrecadado           *= -1;
            $oReceitaBalancete->saldo_arrecadado_acumulado *= -1;
          }

           $total_inicial           += $oReceitaBalancete->saldo_inicial;
          $total_atualizada_balorc += $oReceitaBalancete->saldo_inicial_prevadic;
          $total_nobim             += $oReceitaBalancete->saldo_arrecadado;
          $total_atebim            += $oReceitaBalancete->saldo_arrecadado_acumulado;
        }
      }
    }
  }


  for ($i = 43; $i <= 46; $i++) {

    $oLinha = new linhaRelatorioContabil($iCodigoRelatorio, $i);
    $oLinha->setPeriodo($iCodigoPeriodo);
    $aColunas  = $oLinha->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
    foreach ($aColunas as $oLinhaColuna) {

      if (isset($oLinhaColuna->colunas[1]->o117_valor)) {
        $total_inicial    += @$oLinhaColuna->colunas[1]->o117_valor;
      }
      if (isset($oLinhaColuna->colunas[2]->o117_valor)) {
        $total_atualizada_balorc += @$oLinhaColuna->colunas[2]->o117_valor;
      }
      if (isset($oLinhaColuna->colunas[3]->o117_valor)) {
        $total_nobim      += @$oLinhaColuna->colunas[3]->o117_valor;
      }
      if (isset($oLinhaColuna->colunas[4]->o117_valor)) {
        $total_atebim     += @$oLinhaColuna->colunas[4]->o117_valor;
      }
    }
  }

}

// BALANCO ORCAMENTARIO - DESPESAS
if (@$emite_balorc==1 || $emite_desp_funcsub==1) {

  $iCodigoRelatorio     = 79;
  $total_inicial_desp    = 0;
  $total_adicional       = 0;
  $total_atualizada_desp = 0;
  $total_emp_nobim       = 0;
  $total_emp_atebim      = 0;
  $total_liq_nobim       = 0;
  $total_liq_atebim      = 0;
  $total_rp_np_nobim     = 0;
  $total_rp_np_atebim    = 0;

  $total_inicial_desp_bal    = 0;
  $total_adicional_bal       = 0;
  $total_atualizada_desp_bal = 0;
  $total_atualizada_bal      = 0;
  $total_emp_nobim_bal       = 0;
  $total_emp_atebim_bal      = 0;
  $total_liq_nobim_bal       = 0;
  $total_liq_atebim_bal      = 0;
  $total_rp_np_nobim_bal     = 0;
  $total_rp_np_atebim_bal    = 0;
  $total_atualizada          = 0;
  for ($iLinha = 49; $iLinha <= 60; $iLinha++) {

    $aLinhaBalanceteDespesa[$iLinha]             = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
    $aLinhaBalanceteDespesa[$iLinha]->parametros = $aLinhaBalanceteDespesa[$iLinha]->getParametros($anousu);
    $aLinhaBalanceteDespesa[$iLinha]->setPeriodo($iCodigoPeriodo);
    $aColunas  = $aLinhaBalanceteDespesa[$iLinha]->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
    foreach ($aColunas as $oLinhaColuna) {

       $total_inicial_desp_bal     += $oLinhaColuna->colunas[1]->o117_valor;
       $total_adicional_bal        += $oLinhaColuna->colunas[2]->o117_valor;
       $total_emp_nobim_bal        += $oLinhaColuna->colunas[4]->o117_valor;
       $total_emp_atebim_bal       += $oLinhaColuna->colunas[5]->o117_valor;
       $total_liq_nobim_bal        += $oLinhaColuna->colunas[6]->o117_valor;
       $total_liq_atebim_bal       += $oLinhaColuna->colunas[7]->o117_valor;

    }


  }
  $total_atualizada_desp_bal += ($total_inicial_desp_bal+$total_adicional_bal);
  for ($i = 0; $i < pg_num_rows($rsDespesaResumido); $i++) {

    $oDespesaBalancete = db_utils::fieldsmemory($rsDespesaResumido, $i);
    for ($iLinha = 49; $iLinha <= 60; $iLinha++) {

      $oParametro = $aLinhaBalanceteDespesa[$iLinha]->parametros;
      foreach ($oParametro->contas as $oEstrutural) {

        $oVerificacao = $aLinhaBalanceteDespesa[$iLinha]->match($oEstrutural ,$oParametro->orcamento,$oDespesaBalancete, 2);
        if ($oVerificacao->match) {
          if ($oVerificacao->exclusao) {

            $oDespesaBalancete->dot_ini *= -1;
            $oDespesaBalancete->suplementado_acumulado *= -1;
            $oDespesaBalancete->reduzido_acumulado *= -1;
            $oDespesaBalancete->empenhado  *= -1;
            $oDespesaBalancete->anulado *= -1;
            $oDespesaBalancete->empenhado_acumulado *= -1;
            $oDespesaBalancete->anulado_acumulado *= -1;
            $oDespesaBalancete->liquidado *= -1;
            $oDespesaBalancete->liquidado_acumulado *= -1;
          }

         $total_inicial_desp += $oDespesaBalancete->dot_ini;
         $total_adicional    += ($oDespesaBalancete->suplementado_acumulado - $oDespesaBalancete->reduzido_acumulado);
         $total_emp_nobim    += $oDespesaBalancete->empenhado  - $oDespesaBalancete->anulado;
         $total_emp_atebim   += $oDespesaBalancete->empenhado_acumulado  - $oDespesaBalancete->anulado_acumulado;
         $total_liq_nobim    += $oDespesaBalancete->liquidado;
         $total_liq_atebim   += $oDespesaBalancete->liquidado_acumulado;

         $total_rp_np_nobim  += abs($oDespesaBalancete->empenhado - $oDespesaBalancete->anulado - $oDespesaBalancete->liquidado);
         $total_rp_np_atebim += abs($oDespesaBalancete->empenhado_acumulado - $oDespesaBalancete->anulado_acumulado
                                   - $oDespesaBalancete->liquidado_acumulado);
        }
      }
    }
  }
  $total_atualizada_desp_bal  += ($total_inicial_desp+$total_adicional);
  $total_inicial_desp_bal     += $total_inicial_desp;
  $total_atualizada_bal       += $total_atualizada;
  $total_adicional_bal        += $total_adicional;
  //$total_atualizada_desp_bal  += $total_atualizada_desp;
  $total_emp_nobim_bal        += $total_emp_nobim;
  $total_emp_atebim_bal       += $total_emp_atebim;
  $total_liq_nobim_bal        += $total_liq_nobim;
  $total_liq_atebim_bal       += $total_liq_atebim;
}
// FIM DAS DESPESAS POR FUNCAO/SUBFUNCAO ////////////////////////////////////////////////////////////////////////////////////

// RECEITA CORRENTE LIQUIDA - RCL
if ($emite_rcl==1 || $emite_ppp==1) {

  $dt = split('-',$dt_fin);  // mktime -- (mes,dia,ano)
  $dt_ini_ant = "{$anousu_ant}-01-01";
  $dt_fin_ant = "{$anousu_ant}-12-31";

  $total_rcl  = 0;

  $dtini      = "";
  $dtfin      = "";

  $sTodasInstit = null;
  $rsInstit =  db_query("select codigo from db_config");
  for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
    db_fieldsmemory($rsInstit, $xinstit);
    $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
  }
  if ($anousu >= 2009) {

    include_once(modification("classes/db_db_config_classe.php"));
    include_once(modification("classes/db_orcparamelemento_classe.php"));
    $iExercAnt  = (db_getsession('DB_anousu')-1);
    $sCodParRel = "81";
    duplicaReceitaaCorrenteLiquida($anousu, 81);
  }
  //ano corrente
  $nTotalRcl  = calcula_rcl2($anousu,"{$anousu}-01-01",$dt_fin,$sTodasInstit, false, 81);
 if (PostgreSQLUtils::isTableExists("work_plano")) {
    db_query("drop table work_plano");
  }
  //ano anterior
  $nTotalRcl += calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fin_ant,$sTodasInstit, false, 81,$dt_fin);
  if (PostgreSQLUtils::isTableExists("work_plano")) {
    db_query("drop table work_plano");
  }
  $total_rcl = $nTotalRcl;// + $total_rec;           // Total dos ultimos 12 meses
  //unset($arqinclude);
}
// FIM DA RECEITA CORRENTE LIQUIDA //////////////////////////////////////////////////////////////////////////////////////////

// RECEITAS/DESPESAS DO RPPS
if ($emite_rec_desp == 1 ) {

  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  if ($anousu < 2007){
    $executar = "con2_lrfrecdesprpps002.php";
  } else if($anousu ==  2008){
    $executar = "con2_lrfrecdesprpps002_2008.php";
  } else if ($anousu ==  2010 || $anousu = 2011) {

    $oGet                 = db_utils::postMemory($_GET);
    $periodo              = $iCodigoPeriodo;
    $dtDataInicial        = $dt_ini;
    $dtDataFinal          = $dt_fin;
    $total_desp_rp_np_bim = 0;
    $total_desp_rpnp_exe  = 0;

    include_once(modification("classes/db_db_config_classe.php"));
    $cldb_config  = new cl_db_config();
    $executar     = "con2_lrfrecdesprpps002_2010.php";
  }

  $periodo         = $iCodigoPeriodo;
  include(modification($executar));

  $total_rpps_rec_nobim    = $total_rec_bimestre;
  $total_rpps_rec_atebim   = $total_rec_exercicio;
  $total_rpps_desp_nobim   = $total_desp_bimestre;
  $total_rpps_desp_atebim  = $total_desp_exercicio;

  // RPs Nao Processados
  $total_rpps_rp_np_nobim  = $total_desp_rp_np_bim;
  $total_rpps_rp_np_atebim = $total_desp_rpnp_exe;

  // resultado
  $res_rpps_prev_nobim    = $total_rec_bimestre - $total_desp_bimestre;
  $res_rpps_prev_atebim   = $total_rec_exercicio - $total_desp_exercicio;
  unset($arqinclude);
}
// FIM DAS RECEITAS/DESPESAS DO RPPS ////////////////////////////////////////////////////////////////////////////////////////

// RESULTADOS NOMINAL/PRIMARIO
if ($emite_resultado==1){

  if (PostgreSQLUtils::isTableExists("work_receita")) {
    @db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   @db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    @db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    @db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    @db_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  $META_NOMINAL = 0;
  $dt_ini = $anousu.'-01-01';

  $periodo = $sSiglaPeriodo;

  include(modification("con2_lrfnominal002_2010.php"));

  $tot_bi  = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );
  $tot_ant = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);
  $META_NOMINAL = $aLinhaRelatorio[7]->valor;
  $total_nominal = $tot_bi - $tot_ant;

  unset($arqinclude);

  // RESULTADO PRIMARIO
  $arqinclude = true;

  $META_PRIMARIA = 0;
  $iAnoRelatorio = $anousu;
  if ($anousu == 2009) {
    $iAnoRelatorio = 2008;
  } else if ($anousu > 2010) {
    $iAnoRelatorio = 2010;
  } else {
    $iAnoRelatorio = db_getsession("DB_anousu");
  }

  include(modification("con2_lrfprimario002_$iAnoRelatorio.php"));
  $total_primario = $nResultadoPrimarioResumido;
  unset($arqinclude);
}
// FIM DOS RESULTADOS NOMINAL/PRIMARIO //////////////////////////////////////////////////////////////////////////////////////
if ($emite_rp == 1){
  $arqinclude = true;

  $iAnoRelatorio = $anousu;
  if ($iAnoRelatorio > 2010) {
    $iAnoRelatorio = 2010;
  }
  $db_filtro  = ' e60_instit in (' . str_replace('-',', ',$db_selinstit) . ')';

      include(modification("con2_lrfdemonstrativorp002_{$iAnoRelatorio}.php"));

      // Totais do Poder Executivo
      $total_proc_insc       = $tot_restos_pc_insc_ant_exec + $tot_restos_pc_inscritos_exec;
      $total_proc_canc       = $tot_restos_pc_cancelados_exec;
      $total_proc_pgto       = $tot_restos_pc_pagos_exec;
      $total_proc_a_pagar    = $tot_restos_pc_saldo_exec;

      $total_naoproc_insc    = $tot_restos_naopc_insc_ant_exec + $tot_restos_naopc_inscritos_exec;
      $total_naoproc_canc    = $tot_restos_naopc_cancelados_exec;
      $total_naoproc_pgto    = $tot_restos_naopc_pagos_exec;
      $total_naoproc_a_pagar = $tot_restos_naopc_saldo_exec;

      // Totais do Poder Legislativo
      $ltotal_proc_insc       = $tot_restos_pc_insc_ant_legal+$tot_restos_pc_inscritos_legal;
      $ltotal_proc_canc       = $tot_restos_pc_cancelados_legal;
      $ltotal_proc_pgto       = $tot_restos_pc_pagos_legal;
      $ltotal_proc_a_pagar    = $tot_restos_pc_saldo_legal;

      $ltotal_naoproc_insc    = $tot_restos_naopc_insc_ant_legal + $tot_restos_naopc_inscritos_legal;
      $ltotal_naoproc_canc    = $tot_restos_naopc_cancelados_legal;
      $ltotal_naoproc_pgto    = $tot_restos_naopc_pagos_legal;
      $ltotal_naoproc_a_pagar = $tot_restos_naopc_saldo_legal;

      // Total Geral RP Processado
      $tot_geral_proc_insc    = $total_proc_insc    + $ltotal_proc_insc;
      $tot_geral_proc_canc    = $total_proc_canc    + $ltotal_proc_canc;
      $tot_geral_proc_pgto    = $total_proc_pgto    + $ltotal_proc_pgto;
      $tot_geral_proc_a_pagar = $total_proc_a_pagar + $ltotal_proc_a_pagar;

      // Total Geral RP Nao Processado
      $tot_geral_naoproc_insc    = $total_naoproc_insc    + $ltotal_naoproc_insc;
      $tot_geral_naoproc_canc    = $total_naoproc_canc    + $ltotal_naoproc_canc;
      $tot_geral_naoproc_pgto    = $total_naoproc_pgto    + $ltotal_naoproc_pgto;
      $tot_geral_naoproc_a_pagar = $total_naoproc_a_pagar + $ltotal_naoproc_a_pagar;

      unset($arqinclude);
}
// FIM DE RESTOS A PAGAR ////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNDEB - MDE
if ($emite_mde==1) {
  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  if ($anousu == 2008) {
    include(modification("con2_lrfmdefundeb002_2008.php"));
  } else if ($anousu == 2009) {
    $periodo_selecionado = $sPeriodo;
    include(modification("con2_lrfmdefundeb002_2009.php"));
  } else if ($anousu >= 2010) {
    include(modification("con2_lrfmdefundeb002_2010.php"));
  }


  $periodo_selecionado = $iCodigoPeriodo;
  $periodo_selecionado = $periodo;
  $total_A = 0;
  $total_B = 0;
  $perc_A  = 0;
  $perc_B  = 0;
  $nPercentual60ParaSimplificado    = 0;
  $nPercentual25ParaSimplificado    = 0;
  $nSoma25ParaRelatorioSimplificado = 0;
  $nSoma60ParaRelatorioSimplificado = 0;

  @$total_A = @$nValorLinha38;
  @$perc_A  = @$nValorLinha39;

  $nTotal25AteBimestre = ( $aDespesas[7]['exercicio'] + $aDespesas[10]['exercicio'] ) - $nValorLinha37;
  $nTotal25Inscritas   =   $aDespesas[7]['inscritas'] + $aDespesas[10]['inscritas'];
  @$total_B            = @(($aDespesas[1]["exercicio"]-($nValorLinha16+$nValorLinha17)));
  $nTotal60AteBimestre = $total_B;

  $nTotal60Inscritas   = @($aValoresDespesas[2]["inscritas"] + $aValoresDespesas[3]["inscritas"]);
  @$perc_B  = $nValorMinimo60Fundeb ;

  unset($arqinclude);
}

// OPERACOES DE CREDITO E DESPESAS DE CAPITAL
if ($emite_oper==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }

  $total_oper     = 0;
  $saldo_oper     = 0;
  $total_despesa  = 0;
  $saldo_despesa  = 0;

  $m_operacoes[0] = $orcparamrel->sql_parametro('6','0');
  $m_operacoes[1] = $orcparamrel->sql_parametro('6','1');
  $m_operacoes[2] = $orcparamrel->sql_parametro('6','2');

  $sele_work      = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
      $result_oper    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
      @db_query("drop table work_receita");

      $sele_work      = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
      $result_desp    = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);
      @db_query("drop table work_dotacao");

      for($i = 0; $i < pg_numrows($result_oper); $i++){
      db_fieldsmemory($result_oper, $i);
      $estrutural = $o57_fonte;
      if (in_array($estrutural, $m_operacoes)){
      $total_oper += $saldo_arrecadado_acumulado;
      $saldo_oper += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      }

      for($i = 0; $i < pg_numrows($result_desp); $i++){
      db_fieldsmemory($result_desp,$i);
      $estrutural = $o58_elemento;
      if (substr($estrutural,0,3)=='334'){
        $total_despesa += $liquidado_acumulado;
        $saldo_despesa += $liquidado_acumulado - $dot_ini;
      }
      }
}
// PROJECAO ATUARIAL DO RPPS
if ($emite_proj==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }


  $total_rec          = 0;
  $total_rec_patronal = 0;
  $total_desp         = 0;
  $total_res_rpps     = 0;
  $total_rep_rpps     = 0;

  for ($linha=1;$linha<=28;$linha++){
    $m_receita[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha);
  }

  for ($linha=29;$linha<=39;$linha++){
    $m_despesa[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha);
    $m_despesa[$linha]['nivel']  = $orcparamrel->sql_nivel('42',$linha);
  }

  $db_filtro  = " o70_instit in (".$instit_rpps.")";

  // Exercicio Atual
  $result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
  @db_query("drop table work_receita");

  $sele_work = ' c61_instit in ('.$instit_rpps.')';

      // Exercicio Atual
      $result_res_rep = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$sele_work);
      @db_query("drop table work_pl");

      $db_filtro = "o58_instit in (".$instit_rpps.") ";

      // Exercicio Atual
      $result_despesa = db_dotacaosaldo(8,2,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
      @db_query("drop table work_dotacao");

      for ($i=0; $i < pg_numrows($result_rec); $i++){
      db_fieldsmemory($result_rec,$i);
      $estrutural = $o57_fonte;

      for ($linha=1;$linha < 15;$linha++){
      if (in_array($estrutural,$m_receita[$linha]['estrut'])){
      $total_rec += $saldo_arrecadado;
      }
      if (substr($estrutural,0,7)=="6121701"){
        $total_rec_patronal += $saldo_arrecadado;
      }
      }
      }

      for ($i=0; $i < pg_numrows($result_res_rep); $i++) {
        db_fieldsmemory($result_res_rep,$i);

        for ($linha=15;$linha<=28;$linha++){
          if (substr($estrutural,0,1)=="6"){ // RESULTADOS(6)
            if (in_array($estrutural,$m_receita[$linha]['estrut'])){
              $total_rec += $saldo_final;
            }
            if (substr($estrutural,0,6)=="612117"){
              $total_rep_rpps += $saldo_final;
            }
          }
        }
      }

      for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
        db_fieldsmemory($result_despesa, $i);

        for ($linha=29;$linha<=39;$linha++){
          $nivel        = $m_despesa[$linha]['nivel'];
          $estrutural   = $o58_elemento.'00';
          $estrutural   = substr($estrutural,0,$nivel);
          $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);

          if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
            $total_desp += $liquidado;
          }
        }
      }

      $total_res_rpps = $total_rec - $total_desp;
}
// RECEITA DA ALIENACAO DE ATIVOS E APLICACAO DOS RECURSOS
if ($emite_alienacao==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }


  $total_alien   = 0;
  $saldo_alien   = 0;
  $total_recurso = 0;
  $saldo_recurso = 0;

  $sele_work     = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
      $result_rec    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
      @db_query("drop table work_receita");

      for($i = 0; $i < pg_numrows($result_rec); $i++){
      db_fieldsmemory($result_rec, $i);
      $estrutural = $o57_fonte;
      if (substr($estrutural,0,3)=="422"){
      $total_alien += $saldo_arrecadado_acumulado;
      $saldo_alien += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      if (substr($estrutural,0,3)=="413"){
      $total_recurso += $saldo_arrecadado_acumulado;
      $saldo_recurso += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      }
      }
      // DESPESAS COM SAUDE
      if ($emite_saude == 1) {

        if (PostgreSQLUtils::isTableExists("work_receita")) {
          db_query("drop table work_receita");
        }
        if (PostgreSQLUtils::isTableExists("work_dotacao")) {
          db_query("drop table work_dotacao");
        }
        if (PostgreSQLUtils::isTableExists("work_pl")) {
          db_query("drop table work_pl");
        }
        if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
          db_query("drop table work_pl_estrut");
        }
        if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
          db_query("drop table work_pl_estrutmae");
        }

        $oDemonstrativoSaude = new relatorioContabil(124, false);
        $oAnexoXVI           = new AnexoXVILRF($anousu, 124,  $iCodigoPeriodo);
        $oAnexoXVI->setInstituicoes($instituicao);
        $aDadosSimplificado = $oAnexoXVI->getDadosSimplificado();
      }
// DESPESA DE PPP
if ($emite_ppp==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_dotacao")) {
   db_query("drop table work_dotacao");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }

  $periodo    = $iCodigoPeriodo;
  $arqinclude = true;
  require_once(modification("con2_lrfanexoxvii002_2010.php"));
  $nValorDespesaPPP = $aLinhasRelatorio[19]->valores[2];
  $nValorTotalPPP   = ($aLinhasRelatorio[19]->valores[2] / $nTotalRcl) * 100;

}
if ($emite_oper == 1) {

  if (PostgreSQLUtils::isTableExists("work_receita")) {
    db_query("drop table work_receita");
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) {
    db_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) {
    db_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) {
    db_query("drop table work_pl_estrutmae");
  }
  $lNaoGeraPDF              = true;

  require_once(modification("con2_lrfdemrecopcreddesp002.php"));

  unset($lNaoGeraPDF);

}
//////////////////////////////// Impresso do PDF /////////////////////////////////
unset($arqinclude);

if (!isset($arqinclude)){
  $head1  = "";
  $head2  = "";
  $head3  = "";
  $head4  = "";
  $head5  = "";
  $head6  = "";
  $head7  = "";
  $head8  = "";
  $head9  = "";

  $head2  = "$txt1";
  $head3  = "$txt2";
  $head4  = "$txt3";
  $head5  = "$txt4";

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','',7);
$alt     = 4;
$num_rel = 0;

$pdf->addpage();
$iFonteSimplificado = 6;
$pdf->setfont('arial','', $iFonteSimplificado);
$pdf->setX(10);
$pdf->cell(170,$alt,"LRF, Art. 48 - Anexo 14",'0',0,"L",0);
$pdf->cell(20,$alt,"R$ 1,00","B",1,"R",0);

//------------------------------------------------------------------
// BALANCO ORCAMENTARIO - RECEITAS
$n1 = 5;
$n2 = 10;
if (@$emite_balorc == 1) {
  $num_rel++;

  $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,"RECEITAS","R",0,"L",0);
  $pdf->cell(50,$alt,"","R",0,"L",0);
  $pdf->cell(50,$alt,"","",1,"L",0);

  $pdf->cell(90,$alt,espaco($n1).'Previsão Inicial','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_inicial,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Previsão Atualizada','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_atualizada_balorc,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Receitas Realizadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_nobim,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_atebim,'f'),'0',1,"R",0);
  // DEFICIT
  $total_rec_realizadas  =  $total_atebim;
  $total_desp_liquidadas =  $total_liq_atebim_bal;
  if ($lUltimoBimestre) {
    $total_desp_liquidadas += abs($total_rp_np_atebim);
  }

  $pos_deficit = $pdf->getY();
  $pdf->cell(90,$alt,espaco($n1).'Déficit Orçamentário','R',0,"L",0);

  if ($total_desp_liquidadas > $total_rec_realizadas){
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_desp_liquidadas-$total_rec_realizadas,"f"),'0',1,"R",0);
  }else{
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,'-','0',1,"C",0);
  }
  $total_saldo_ant = 0;
  $nSuperavitFinancPrevAtu  = 0;
  $nReaberturaCredPrevAtu   = 0;
  $nSuperavitFinancAteBim   = 0;
  $nReaberturaCredAteBim    = 0;

  $oLinhaRelatorio = new linhaRelatorioContabil(79, 47);
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $oLinha = $oLinhaRelatorio->getValoresSomadosColunas($instituicao, $anousu);
  foreach ($oLinha as $oColunas){

    $nSuperavitFinancPrevAtu += $oColunas->colunas[2]->o117_valor;
    $nSuperavitFinancAteBim  += $oColunas->colunas[4]->o117_valor;

  }
  $oLinhaRelatorio = new linhaRelatorioContabil(79, 48);
  $oLinha = $oLinhaRelatorio->getValoresSomadosColunas($instituicao, $anousu);
  foreach ($oLinha as $oColunas){

    $nReaberturaCredPrevAtu += $oColunas->colunas[2]->o117_valor;
    $nReaberturaCredAteBim  += $oColunas->colunas[4]->o117_valor;

  }
  $total_saldo_ant += $nSuperavitFinancAteBim + $nReaberturaCredAteBim;
  // SALDO ANTERIOR
  $pdf->cell(90,$alt,espaco($n1).'Saldos de Exercícios Anteriores(Utilizados para Créditos Adicionais)','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_saldo_ant,"f"),'0',1,"R",0);

  $emite_balorc_rec = 1;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BALANCO ORCAMENTARIO - DESPESAS
if (@$emite_balorc==1){
  $num_rel++;

  if ($emite_balorc_rec==0){
    $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO","BT",0,"C",0);
    $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
    $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
  }

  $pdf->cell(90,$alt,"DESPESAS","R",0,"L",0);
  $pdf->cell(50,$alt,"","R",0,"L",0);
  $pdf->cell(50,$alt,"","",1,"L",0);

  $pdf->cell(90,$alt,espaco($n1).'Dotacão Inicial','R',0,"L",0);
  //$pdf->cell(50,$alt,db_formatar($total_inicial_desp,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_inicial_desp_bal,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Créditos Adicionais','R',0,"L",0);
  //$pdf->cell(50,$alt,db_formatar($total_adicional,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_adicional_bal,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Dotação Atualizada','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_atualizada_desp_bal,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Despesas Empenhadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_nobim_bal,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_atebim_bal,'f'),'0',1,"R",0);
  if ($lUltimoBimestre) {

  	$nSomaDespesasExecutadas = ($total_liq_atebim_bal + $total_rp_np_atebim);
    $pdf->cell(90,$alt,espaco($n1).'Despesas Executadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim_bal,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($nSomaDespesasExecutadas, 'f'),'0',1,"R",0);

    $pdf->cell(90,$alt,espaco($n1).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim_bal,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim_bal,'f'),'0',1,"R",0);
    $pdf->cell(90,$alt,espaco($n1).'Inscritas em Restos a Pagar Não Processados','R',0,"L",0);
    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rp_np_atebim,'f'),'0',1,"R",0);

  } else {

    $pdf->cell(90,$alt,espaco($n1).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim_bal,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim_bal,'f'),'0',1,"R",0);

  }
  //   SUPERAVIT
  $pos_superavit = $pdf->getY();
  $pdf->cell(90,$alt,espaco($n1).'Superávit Orçamentário','R',0,"L",0);
  if ($total_desp_liquidadas < $total_rec_realizadas){
    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rec_realizadas-$total_desp_liquidadas,"f"),'0',1,"R",0);
  }else{
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,'-','0',1,"C",0);
  }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DESPESAS POR FUNCAO/SUBFUNCAO
if ($emite_desp_funcsub==1){
  $num_rel++;

  $pdf->cell(90,$alt,"DESPESAS POR FUNÇÃO/SUBFUNÇÃO","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,'Despesas Empenhadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_nobim,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_atebim,'f'),'0',1,"R",0);

  if ($lUltimoBimestre) {

  	$nSomaDespesasExecutadas = ($total_liq_atebim + $total_rp_np_atebim);
    $pdf->cell(90,$alt,'Despesas Executadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($nSomaDespesasExecutadas, 'f'),'0',1,"R",0);

    $pdf->cell(90,$alt,'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
    $pdf->cell(90,$alt,espaco($n1).'Inscritas em Restos a Pagar Não Processados','R',0,"L",0);

    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rp_np_atebim,'f'),'0',1,"R",0);

  } else {

    $pdf->cell(90,$alt,'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);

  }
}
// RECEITA CORRENTE LIQUIDA - RCL
if ($emite_rcl==1){
  $num_rel++;

  $pdf->cell(90,$alt,"RECEITA CORRENTE LÍQUIDA - RCL","BT",0,"C",0);
  $pdf->cell(50,$alt,"","BTR",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(140,$alt,'Receita Corrente Líquida', 'R',0,"L",0);
  $pdf->cell( 50,$alt,db_formatar($total_rcl,'f'),'0',1,"R",0);
}
// RECEITAS/DESPESAS DO RPPS
if ($emite_rec_desp==1){
  if ($num_rel > 0){
    $pdf->cell(190,$alt,"","TB",1,"C",0);
  }

  $num_rel++;

  $pdf->cell(90,$alt,"RECEITAS E DESPESAS DOS REGIMES DE PREVIDÊNCIA","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,'Regime Próprio de Previdência Social dos Servidores Públicos','R',0,"L",0);
  $pdf->cell(50,$alt,"",'R',0,"L",0);
  $pdf->cell(50,$alt,"",0,1,"L",0);
  $pdf->cell(90,$alt,'  Receitas Previdenciárias Realizadas(IV)','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_rpps_rec_nobim,"f"),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_rpps_rec_atebim,"f"),0,1,"R",0);
  if ($lUltimoBimestre){

    $pdf->cell(90,$alt,'  Despesas Previdenciárias Executadas(V)','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim+$total_rpps_rp_np_atebim,"f"),0,1,"R",0);

    $pdf->cell(90,$alt,'    Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim,"f"),0,1,"R",0);
    $pdf->cell(90,$alt,'    Inscritas em Restos a Pagar Não Processados','R',0,"L",0);
    $pdf->cell(50,$alt, "-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_rp_np_atebim,"f"),0,1,"R",0);
    $total_rpps_desp_atebim += $total_rpps_rp_np_atebim;
  } else {

    $pdf->cell(90,$alt,'  Despesas Previdenciárias Liquidadas(V)','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim,"f"),0,1,"R",0);

  }

  $pdf->cell(90,$alt,'  Resultado Previdenciário(VI) = (IV-V)','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($res_rpps_prev_nobim,"f"),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($res_rpps_prev_atebim,"f"),0,1,"R",0);
}
// RESULTADOS NOMINAL/PRIMARIO
if ($emite_resultado==1){
  $num_rel++;

  $pdf->cell(90,$alt,"","T","C",0);
  $pdf->cell(25,$alt,"Meta Fixada no","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Resultado Apurado","TRL",0,"C",0);
  $pdf->cell(50,$alt,"% em Relação à Meta","T",1,"C",0);
  $pdf->cell(90,$alt,"RESULTADOS NOMINAL E PRIMÁRIO","R",0,"C",0);
  $pdf->cell(25,$alt,"AMF da LDO","RL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"RL",0,"C",0);
  $pdf->cell(50,$alt,"",0,1,"C",0);
  $pdf->cell(90,$alt,"","B","C",0);
  $pdf->cell(25,$alt,"(a)","BRL",0,"C",0);
  $pdf->cell(25,$alt,"(b)","BRL",0,"C",0);
  $pdf->cell(50,$alt,"(b/a)","B",1,"C",0);
  $pdf->cell(90,$alt,"Resultado Nominal","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($META_NOMINAL,"f"),"BRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($total_nominal,"f"),"BRL",0,"R",0);
  if ($META_NOMINAL==0) {
    $perc_meta_nom = 0;
  }else{
    $perc_meta_nom = ($total_nominal/$META_NOMINAL)*100;
  }
  $pdf->cell(50,$alt,db_formatar($perc_meta_nom,"f"),"B",1,"R",0);
  $pdf->cell(90,$alt,"Resultado Primário","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($META_PRIMARIA,"f"),"BRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($total_primario,"f"),"BRL",0,"R",0);
  if ($META_PRIMARIA==0) {
    $perc_meta_prim = 0;
  }else{
    $perc_meta_prim = ($total_primario/$META_PRIMARIA)*100;
  }
  $pdf->cell(50,$alt,db_formatar($perc_meta_prim,"f"),0,1,"R",0);
}
// RESTOS A PAGAR
if ($emite_rp==1){
  $num_rel++;

  $pdf->cell(90,($alt*2),"RESTOS À PAGAR POR PODER E MINISTÉRIO PÚBLICO","TR",0,"C",0);
  $pdf->cell(25,$alt,"Inscrição","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Cancelamento","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Pagamento","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Saldo","TL",1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"","BRL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
  $pdf->cell(25,$alt,"a Pagar","BL",1,"C",0);

  $pdf->cell(90,$alt,espaco($n1)."RESTOS À PAGAR PROCESSADOS","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_insc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_canc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_pgto,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_a_pagar,"f"),"L",1,"R",0);

  $pdf->cell(90,$alt,espaco($n2)."Poder Executivo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($total_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($total_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($total_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar

  $pdf->cell(90,$alt,espaco($n2)."Poder Legislativo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar

  $pdf->cell(90,$alt,espaco($n1)."RESTOS À PAGAR NÃO-PROCESSADOS","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_insc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_canc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_pgto,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_a_pagar,"f"),"L",1,"R",0);

  $pdf->cell(90,$alt,espaco($n2)."Poder Executivo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($total_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($total_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($total_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar

  $pdf->cell(90,$alt,espaco($n2)."Poder Legislativo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar

  $pdf->cell(90,$alt,"TOTAL","TR",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_insc    + $tot_geral_naoproc_insc,"f"),"TRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_canc    + $tot_geral_naoproc_canc,"f"),"TRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_pgto    + $tot_geral_naoproc_pgto,"f"),"TR",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_a_pagar + $tot_geral_naoproc_a_pagar,"f"),"T",1,"R",0);
}
// MDE
$iFonteSimplificado = 6;
if ($emite_mde==1) {
  $num_rel++;

  $iFonteSimplificado = 6;
  $pdf->cell(90,$alt,"","TR",0,"L",0);
  $pdf->cell(25,$alt,"Valor apurado","TR",0,"C",0);
  $pdf->cell(75,$alt,"Limites Constitucionais Anuais","TB",1,"C",0);
  $pdf->cell(90,$alt,"DESPESAS COM AÇÕES TÍPICAS DE MDE","R",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"R",0,"C",0);
  $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
  $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],"T",1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"L",0);
  $pdf->cell(25,$alt,"","BR",0,"L",0);
  $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
  $pdf->cell(50,$alt,"","B",1,"C",0);
  $pdf->setfont('arial','',$iFonteSimplificado);
  $pdf->cell(90,$alt,"Mínimo Anual de 25% das Receitas de Impostos em MDE","R",0,"L",0);
  $pdf->setfont('arial','',($iFonteSimplificado));
  $pdf->cell(25,$alt,db_formatar($total_A,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,"25%","R",0,"C",0);
  $pdf->cell(50,$alt,db_formatar($perc_A,"f"),0,1,"R",0);
  if ($lUltimoBimestre) {

    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($nTotal25AteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);
    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","R",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($nTotal25Inscritas,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);

  }
  if ($lUltimoBimestre) {


    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"Mínimo Anual de 60% do FUNDEB na Rem. do Magistério com Educação Inf. e Ensino Fund.","R",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($total_B,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"60%","R",0,"C",0);
    $pdf->cell(50,$alt,db_formatar($perc_B,"f"),"",1,"R",0);
    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($nTotal60AteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);
    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","BR",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($nTotal60Inscritas,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"-","BR",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"C",0);

  } else {

    $pdf->setfont('arial','',$iFonteSimplificado);
    $pdf->cell(90,$alt,"Mínimo Anual de 60% do FUNDEB na Rem. do Magistério com Educação Inf. e Ensino Fund.","BR",0,"L",0);
    $pdf->setfont('arial','',($iFonteSimplificado));
    $pdf->cell(25,$alt,db_formatar($total_B,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"60%","BR",0,"C",0);
    $pdf->cell(50,$alt,db_formatar($perc_B,"f"),"B",1,"R",0);

  }
}

// OPERACOES DE CREDITO E DESPESAS DE CAPITAL
if ($emite_oper==1){
  $num_rel++;

  $pdf->setfont('arial','',$iFonteSimplificado);
  $pdf->cell(90,$alt,"RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL","TBR",0,"C",0);
  $pdf->cell(50,$alt,"Valor Apurado Até o ".$dados["periodo"],"TBR",0,"C",0);
  $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);


  $pdf->cell(90,$alt,"Receita de Operação de Crédito","R",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($aDadosAnexoXI->receitadeoperacoescredito->valorapurado,"f"),"R",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($aDadosAnexoXI->receitadeoperacoescredito->saldoarealizar,"f"),0,1,"R",0);


  $pdf->cell(90,$alt,"Despesa de Capital Líquida","BR",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($aDadosAnexoXI->despesasdecapitalliquida->valorapurado,"f"),"BR",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($aDadosAnexoXI->despesasdecapitalliquida->saldoarealizar,"f"),"B",1,"R",0);
}

// PROJECAO ATUARIAL DO RPPS
if ($emite_proj==1){

  if ($num_rel > 0){
    if ($num_rel > 7){
      $pdf->cell(190,($alt+2),"Continução na página 2",0,1,"L",0);
      $pdf->addpage();
      $pdf->cell(190,($alt+2),"Continução da página 1",0,1,"L",0);
    } else{
      $pdf->cell(190,$alt,"","TB",1,"C",0);
    }
  }

  $num_rel++;

  $nTotalExercProprioPrev    = ($aDadosAnexoXIII->receitasprevidenciarias->exercicio
                                + $aDadosAnexoXIII->despesasprevidenciarias->exercicio
                                + $aDadosAnexoXIII->resultadoprevidenciario->exercicio);

  $nTotalExercProprioPrev10  = ($aDadosAnexoXIII->receitasprevidenciarias->exercicio10
                                + $aDadosAnexoXIII->despesasprevidenciarias->exercicio10
                                + $aDadosAnexoXIII->resultadoprevidenciario->exercicio10);

  $nTotalExercProprioPrev20  = ($aDadosAnexoXIII->receitasprevidenciarias->exercicio20
                                + $aDadosAnexoXIII->despesasprevidenciarias->exercicio20
                                + $aDadosAnexoXIII->resultadoprevidenciario->exercicio20);

  $nTotalExercProprioPrev35  = ($aDadosAnexoXIII->receitasprevidenciarias->exercicio35
                                + $aDadosAnexoXIII->despesasprevidenciarias->exercicio35
                                + $aDadosAnexoXIII->resultadoprevidenciario->exercicio35);

  $pdf->cell(90,$alt,"PROJEÇÃO ATUARIAL DOS REGIMES DE PREVIDÊNCIA","TBR",0,"C",0);
  $pdf->cell(25,$alt,"Exercício"    ,"TBR",0,"C",0);
  $pdf->cell(25,$alt,"10º Exercício","TBR",0,"C",0);
  $pdf->cell(25,$alt,"20º Exercício","TBR",0,"C",0);
  $pdf->cell(25,$alt,"35º Exercício","TB" ,1,"C",0);

  $pdf->cell(90,$alt,"Regime Geral de Previdência Social"    ,"R",0,"L",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-", 0 ,1,"C",0);

  $pdf->cell(90,$alt,"Receitas Previdenciárias (I)"    ,"R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")  ,0,1,"R",0);

  $pdf->cell(90,$alt,"Despesas Previdenciárias (II)"     ,"R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar(0  ,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")  ,0,1,"R",0);

  $pdf->cell(90,$alt,"Resultado Previdenciário (III) = (I-II)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar(0  ,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar(0,"f")    ,0,1,"R",0);

  $pdf->cell(90,$alt,"Regime Próprio de Previdência dos Servidores ","R",0,"L",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-","R",0,"C",0);
  $pdf->cell(25,$alt,"-", 0 ,1,"C",0);

  $pdf->cell(90,$alt,"  Receitas Previdenciárias (IV)"    ,"R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->receitasprevidenciarias->exercicio,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->receitasprevidenciarias->exercicio10,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->receitasprevidenciarias->exercicio20,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->receitasprevidenciarias->exercicio35,"f")  ,0,1,"R",0);

  $pdf->cell(90,$alt,"  Despesas Previdenciárias (V)"     ,"R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->despesasprevidenciarias->exercicio,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->despesasprevidenciarias->exercicio10,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->despesasprevidenciarias->exercicio20,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->despesasprevidenciarias->exercicio35,"f")  ,0,1,"R",0);

  $pdf->cell(90,$alt,"  Resultado Previdenciário (VI) = (IV-V)" ,"R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->resultadoprevidenciario->exercicio ,"f")  ,"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->resultadoprevidenciario->exercicio10, "f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->resultadoprevidenciario->exercicio20, "f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($aDadosAnexoXIII->resultadoprevidenciario->exercicio35, "f"),0,1,"R",0);

}

if ( $emite_alienacao == 1 ) {

  $pdf->cell(190,$alt,"","TB",1,"C",0);

  $num_rel++;
  $pdf->cell(90,$alt,"RECEITA DA ALIENAÇÃO DE ATIVOS  E APLICAÇÃO DE RECURSOS","TBR",0,"C",0);

  $pdf->cell(50,$alt,"Valor Apurado Até o ".$dados["periodo"],"TBR",0,"C",0);
  $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);

  $pdf->cell(90,$alt,"Receita de Capital Resultante da Alienação de Ativos","TR",0,"L",0);
  $pdf->cell(50,$alt, db_formatar($aDadosAnexoXIV->receitadecapital->valorapurado, "f"),"TR",0,"R",0);
  $pdf->cell(50,$alt, db_formatar($aDadosAnexoXIV->receitadecapital->saldoarealizar, "f"),"TL",1,"R",0);

  $pdf->cell(90,$alt,"Aplicação dos Recursos da Alienação de Ativos","BR",0,"L",0);
  $pdf->cell(50,$alt, db_formatar($aDadosAnexoXIV->aplicacaodosrecursos->valorapurado,"f"),"BL",0,"R",0);
  $pdf->cell(50,$alt, db_formatar($aDadosAnexoXIV->aplicacaodosrecursos->saldoarealizar,"f"),"BL",1,"R",0);
  $pdf->cell(190,$alt,"","T",1,"L",0);
}

// DESPESAS COM SAUDE
if ($emite_saude==1){

  $num_rel++;
  $sBordaBottom = "B";
  if ($lUltimoBimestre) {
    $sBordaBottom = "";
  }


  //$aDadosSimplificado
  $total_saude            = $aDadosSimplificado->apurado_ate_bim;
  $perc_aplic_atebim      = $aDadosSimplificado->percent_ate_bim;
  $nTotalSaudeAteBimestre = $aDadosSimplificado->liquidadas;
  $nTotalSaudeInscritos   = $aDadosSimplificado->inscritos_rp_nroc;

  $pdf->cell(90,$alt,"","TR",0,"C",0);
  $pdf->cell(25,$alt,"Valor apurado","TR",0,"C",0);
  $pdf->cell(75,$alt,"Limite Constitucional Anual","TB",1,"C",0);
  $pdf->cell(90,$alt,"DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE","R",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"R",0,"C",0);
  $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
  $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],0,1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
  $pdf->cell(50,$alt,"","B",1,"C",0);
  $pdf->cell(90,$alt,"Despesas Próprias com Ações e Serviços Públicos de Saúde","{$sBordaBottom}R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_saude,"f"),"{$sBordaBottom}R",0,"R",0);
  $pdf->cell(25,$alt,"15%","{$sBordaBottom}R",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($perc_aplic_atebim,"f"),"{$sBordaBottom}",1,"R",0);
  if ($lUltimoBimestre) {

    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->cell(25,$alt,db_formatar($nTotalSaudeAteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-","0",1,"R",0);

    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","BR",0,"L",0);
    $pdf->cell(25,$alt,db_formatar($nTotalSaudeInscritos,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"-","BR",0,"C",0);
    $pdf->cell(50,$alt,"-","0",1,"R",0);
  }
}
// DESPESA DE PPP
if ($emite_ppp==1){

	$nTotalDespesaRCL = $clconrelinfo->getValorVariavel(501,db_getsession('DB_instit'));

  $num_rel++;

  $pdf->cell(90, $alt,"DESPESAS DE CARÁCTER CONTINUADO DERIVADAS DE PPP","TR",0,"C",0);
  $pdf->cell(100,$alt,"VALOR APURADO NO EXERCÍCIO CORRENTE"              ,"T",1,"C",0);

  $pdf->cell(90, $alt,"Total das Despesas/RCL (%)"                      ,"BR",0,"L",0);
  $pdf->cell(100,$alt,db_formatar($nValorTotalPPP,"f")                 ,"B",1,"R",0);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$pdf->cell(190,$alt,"","T",1,"L",0);

$oRelatorioContabil = new relatorioContabil(98);
$oRelatorioContabil->getNotaExplicativa($pdf,$iCodigoPeriodo);

$pdf->ln(10);

assinaturas($pdf,$classinatura,'LRF');

$pdf->Output();
?>