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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('std/db_stdClass.php'));

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';
$oRetorno->erro     = false;

function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;

  }

 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

if ($oParam->exec == 'getCotasEspecialidades') {

  $oDaoSauCotas       = db_utils::getdao('sau_cotasagendamento');
  $oDaoUnidadeMedicos = db_utils::getdao('especmedico');
  $sCampos            = "distinct sd27_i_rhcbo as cod,rh70_descr as descr,rh70_estrutural as estrutural,";
  $sCampos           .="fc_totalCotasPrestEspecComp";
  $sCampos           .="($oParam->iUpsPrestadora,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp)";
  $sCampos           .=" as cotas,";
  $sCampos           .="fc_saldoCotasPrestEspecComp";
  $sCampos           .="($oParam->iUpsPrestadora,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp)";
  $sCampos           .= "as saldo, ";
  $sSubSqlWhere       = " s163_i_upsprestadora = ".$oParam->iUpsPrestadora;
  $sSubSqlWhere      .= " and ((s163_i_mescomp >  ".$oParam->iMescomp." and s163_i_anocomp = ".$oParam->iAnocomp.")";
  $sSubSqlWhere      .= " or (s163_i_anocomp > ".$oParam->iAnocomp.")) limit 1";
  $sSubSql            = $oDaoSauCotas->sql_query_cotas("","s163_i_codigo","",$sSubSqlWhere);
  $sCampos           .= "coalesce(($sSubSql),0) as proximo";
  $sWhere             = " sd04_i_unidade = $oParam->iUpsPrestadora ";
  $sWhere            .= " and fc_totalCotasPrestEspecComp";
  $sWhere            .= "($oParam->iUpsPrestadora,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp) > 0";
  $sSql               = $oDaoUnidadeMedicos->sql_query("",$sCampos,"",$sWhere);
  $rsResult           = $oDaoUnidadeMedicos->sql_record($sSql);
  $iLinhas            = $oDaoUnidadeMedicos->numrows;
  $iProximo           = 0;
  $aEspec             = array();
  for ($iInd=0; $iInd < $iLinhas; $iInd++) {

    $oEspecialidade    = db_utils::fieldsmemory($rsResult,$iInd);
    $oEspec            = new stdClass();
    $oEspec->iCodEspec = $oEspecialidade->cod;
    $oEspec->iEspec    = $oEspecialidade->estrutural;
    $oEspec->sNome     = urlencode($oEspecialidade->descr);
    $oEspec->iCotas    = $oEspecialidade->cotas;
    $oEspec->iSaldo    = $oEspecialidade->saldo;
    $iProximo          = $oEspecialidade->proximo;
    /* Selecionar proficionais da Unidade/Especialidade */
    $sCampos   = " distinct sd27_i_codigo as codigo, ";
    $sCampos  .= " a.z01_nome             as nome, ";
    $sCampos  .= "fc_totalCotasPrestEspecMedComp";
    $sCampos  .= "($oParam->iUpsPrestadora,rh70_estrutural,sd27_i_codigo,$oParam->iMescomp,$oParam->iAnocomp)";
    $sCampos  .= " as cotas,";
    $sCampos  .= "fc_saldoCotasPrestEspecMedComp";
    $sCampos  .= "($oParam->iUpsPrestadora,rh70_estrutural,sd27_i_codigo,$oParam->iMescomp,$oParam->iAnocomp)";
    $sCampos  .= " as saldo ";
    $sWhere    = " sd04_i_unidade = $oParam->iUpsPrestadora ";
    $sWhere   .= " and sd27_i_rhcbo = $oEspecialidade->cod ";
    $sSql      = $oDaoUnidadeMedicos->sql_query("", $sCampos, "", $sWhere);
    $rsResult2 = $oDaoUnidadeMedicos->sql_record($sSql);
    $aProf     = array();
    for ($iInd2=0; $iInd2 < $oDaoUnidadeMedicos->numrows; $iInd2++) {

      $oMedico              = db_utils::fieldsmemory($rsResult2, $iInd2);
      $oProf                = new stdClass();
      $oProf->iCodigo       = $oMedico->codigo;
      $oProf->sNome         = urlencode($oMedico->nome);
      $oProf->iCotas        = $oMedico->cotas;
      $oProf->iSaldo        = $oMedico->saldo;
      $aProf[count($aProf)] = $oProf;

    }
    $oEspec->aProf          = $aProf;
    $aEspec[count($aEspec)] = $oEspec;

  }
  if (count($aEspec) == 0) {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Nenhuma especialidade encontrada!';

  }
  $oRetorno->iProximo = $iProximo;
  $oRetorno->aEspec   = $aEspec;


}

if ($oParam->exec == 'getCotasUnidadesDuplicar') {

  $oDaoSauCotas  = db_utils::getdao('sau_cotasagendamento');
  $sCampos       = " distinct s163_i_codigo as codigo, ";
  $sCampos      .= " sau_cotasagendamento.s163_i_upssolicitante as ups_solicitante, ";
  $sCampos      .= " db_departsolic.descrdepto as ups_descr, ";
  $sCampos      .= " rhcbo.rh70_sequencial as cbo_sequencial, ";
  $sCampos      .= " rhcbo.rh70_estrutural as cbo_estrutural, ";
  $sCampos      .= " rhcbo.rh70_descr as cbo_descr, ";
  $sCampos      .= " sau_cotasagendamento.s163_i_quantidade as quant, ";
  $sCampos      .= "fc_totalCotasAnt";
  $sCampos      .= "($oParam->iUps,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp,$oParam->iMescompAlvo,$oParam->iAnocompAlvo) as cotas,";
  $sCampos      .= "fc_saldoCotasAnt";
  $sCampos      .= "($oParam->iUps,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp,$oParam->iMescompAlvo,$oParam->iAnocompAlvo) as saldo,";
  $sSubWhere     = " a.s163_i_rhcbo = rh70_sequencial and";
  $sSubWhere    .= " a.s163_i_upssolicitante = s163_i_upssolicitante and";
  $sSubWhere    .= " a.s163_i_upsprestadora = $oParam->iUps and";
  $sSubWhere    .= " a.s163_i_mescomp = $oParam->iMescompAlvo and";
  $sSubWhere    .= " a.s163_i_anocomp = $oParam->iAnocompAlvo limit 1 ";
  $sCampos      .= "(select a.s163_i_codigo from sau_cotasagendamento as a where $sSubWhere ) as proxima_cota";
  $swhere        = " s163_i_mescomp = ".$oParam->iMescomp;
  $swhere       .= " and s163_i_anocomp = ".$oParam->iAnocomp;
  $swhere       .= " and s163_i_quantidade > 0 ";
  $swhere       .= " and s163_i_upsprestadora = ".$oParam->iUps;
  $sSql          = $oDaoSauCotas->sql_query_cotas("", $sCampos, "", $swhere);
  $rsCotas       = $oDaoSauCotas->sql_record($sSql);
  $aEspec        = array();
  $aCotas        = array();
  $iLinhas       = $oDaoSauCotas->numrows;
  for ($iInd=0; $iInd < $iLinhas; $iInd++) {

    $oDados = db_utils::fieldsmemory($rsCotas, $iInd);
    $lValida = false;
    if ($iInd == 0) {
      $lValida = true;
    } else {
      if ($aEspec[count(@$aEspec)-1]->iEspecEst != $oDados->cbo_estrutural) {
        $lValida = true;
      }
    }
    if ($lValida) {

      $iTam              = count($aEspec);
      $oEspec            = new stdClass();
      $oEspec->iEspecEst = $oDados->cbo_estrutural;
      $oEspec->sEspecDes = urlencode($oDados->cbo_descr);
      $oEspec->iEspecSeq = $oDados->cbo_sequencial;
      $oEspec->iCotas    = $oDados->cotas;
      $oEspec->iSaldo    = $oDados->saldo;
      $oEspec->iMostra   = false;

      //Seleciona medicos que tem alguma cota lançada e calcula o saldo do mesmo
      $sCampos     = " distinct sd27_i_codigo as codigo, ";
      $sCampos    .= " z01_nome               as nome, ";
      $sCampos    .= "fc_totalCotasAntMed";
      $sCampos    .= "($oParam->iUps,rh70_estrutural,$oParam->iMescomp,$oParam->iAnocomp,$oParam->iMescompAlvo,";
      $sCampos    .= "$oParam->iAnocompAlvo,sd27_i_codigo)";
      $sCampos    .= " as cotas,";
      $sCampos    .= "fc_saldoCotasAntMed";
      $sCampos    .= "($oParam->iUps,rh70_estrutural,sd27_i_codigo,$oParam->iMescomp,$oParam->iAnocomp,";
      $sCampos    .= "$oParam->iMescompAlvo,$oParam->iAnocompAlvo)";
      $sCampos    .= " as saldo ";
      $sWhere      = " sd04_i_unidade = $oParam->iUps ";
      $sWhere     .= " and sd27_i_rhcbo = $oDados->cbo_sequencial ";
      $sSql        = $oDaoSauCotas->sql_query_cotas("", $sCampos, "", $sWhere);
      $rsResult    = $oDaoSauCotas->sql_record($sSql);
      $iLinhasProf = $oDaoSauCotas->numrows;
      $aProf       = array();
      for ($iX = 0; $iX < $iLinhasProf; $iX++) {

        $oDadosProf           = db_utils::fieldsmemory($rsResult, $iX);
        $oProf                = new stdClass();
        $oProf->iCodigo       = $oDadosProf->codigo;
        $oProf->sNome         = $oDadosProf->nome;
        $oProf->iCotas        = $oDadosProf->cotas;
        $oProf->iSaldo        = $oDadosProf->saldo;
        $aProf[count($aProf)] = $oProf;

      }
      $oEspec->aProf          = $aProf;
      $aEspec[count($aEspec)] = $oEspec;

    }

    $oCotas               = new stdClass();
    $oCotas->icodigo      = $oDados->codigo;
    $oCotas->sEspecDes    = urlencode($oDados->cbo_descr);
    $oCotas->sUndSoliCod  = $oDados->ups_solicitante;
    $oCotas->sUndSoliDes  = urlencode($oDados->ups_descr);
    $oCotas->iDistribuido = $oDados->quant;
    $oCotas->iEspecEst    = $oDados->cbo_estrutural;
    $oCotas->iEspecSeq    = $oDados->cbo_sequencial;
    if ($oDados->proxima_cota != '' && $oDados->proxima_cota != null) {
      $oCotas->iProxima = $oDados->proxima_cota;
    } else {
      $oCotas->iProxima = 0;
    }
    $oCotas->lMostra = false;
    //Seleciona medicos com o codigo da cota
    $sCampos     = " distinct sd27_i_codigo as codigo, ";
    $sCampos    .= " z01_nome        as nome, ";
    $sCampos    .= " s164_quantidade as distribuido, ";
    $sSubWhere   = " y.s163_i_rhcbo = rh70_sequencial and";
    $sSubWhere  .= " y.s163_i_upssolicitante = s163_i_upssolicitante and";
    $sSubWhere  .= " y.s163_i_upsprestadora = $oParam->iUps and ";
    $sSubWhere  .= " y.s163_i_mescomp = $oParam->iMescompAlvo and ";
    $sSubWhere  .= " y.s163_i_anocomp = $oParam->iAnocompAlvo and ";
    $sSubWhere  .= " x.s164_especmedico = sd27_i_codigo limit 1 ";
    $sInner      = " inner join sau_cotasagendamentoprofissional as x on s164_cotaagendamento = y.s163_i_codigo ";
    $sCampos    .= "(select x.s164_codigo from sau_cotasagendamento as y $sInner where $sSubWhere ) as proxima_cota";
    $sWhere      = " s163_i_upsprestadora     = $oParam->iUps ";
    $sWhere     .= " and s164_cotaagendamento = $oDados->codigo ";
    $sSql        = $oDaoSauCotas->sql_query_cotas("", $sCampos, "", $sWhere);
    $rsResult    = $oDaoSauCotas->sql_record($sSql);

    $iLinhasProf = $oDaoSauCotas->numrows;
    $aProf       = array();
    for ($iX = 0; $iX < $iLinhasProf; $iX++) {

      $oDados               = db_utils::fieldsmemory($rsResult, $iX);
      $oProf                = new stdClass();
      $oProf->iCodigo       = $oDados->codigo;
      $oProf->sNome         = $oDados->nome;
      $oProf->iDistribuido  = $oDados->distribuido;
      if ($oDados->proxima_cota != '' && $oDados->proxima_cota != null) {
        $oProf->iProxima = $oDados->proxima_cota;
      } else {
        $oProf->iProxima = 0;
      }
      $aProf[count($aProf)] = $oProf;

    }
    $oCotas->aProf           = $aProf;
    $aCotas[count($aCotas)]  = $oCotas;

  }
  if (count($aCotas) == 0) {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode('Nenhuma cota especificada para compatência anterior!');

  }
  $oRetorno->aEspec = $aEspec;
  $oRetorno->aCotas = $aCotas;

}

if ($oParam->exec == 'getCotasUnidades') {

  $dIni             = "$oParam->iAnocomp-$oParam->iMescomp-1";
  $dFim             = "$oParam->iAnocomp-$oParam->iMescomp-";
  $dFim            .= date("t", strtotime("$oParam->iAnocomp-$oParam->iMescomp-1"));
  $dAtual           = date('Y-m-d', db_getsession('DB_datausu'));
  $sHAtual          = date('H:i');
  $oDaoUnidades     = db_utils::getdao('unidades');
  $oDaoSauCotas     = db_utils::getdao('sau_cotasagendamento');
  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $sCampos          = "sd02_i_codigo as cod,descrdepto as descr,";

  if ($oParam->iProfissional != -1) {

    $sSubSqlWhere  = " s163_i_upsprestadora = $oParam->iUpsPrestadora ";
    $sSubSqlWhere .= " and s163_i_upssolicitante = unidade.sd02_i_codigo ";
    $sSubSqlWhere .= " and rh70_estrutural like '".$oParam->iEspecialidade."' ";
    $sSubSqlWhere .= " and s163_i_mescomp = $oParam->iMescomp ";
    $sSubSqlWhere .= " and s163_i_anocomp = $oParam->iAnocomp limit 1";
    $sSubSql       = $oDaoSauCotas->sql_query_cotas("", "s163_i_quantidade", "", $sSubSqlWhere);
    $sCampos      .= "coalesce(($sSubSql),0) as cotas,";

  }
  $sSubSqlWhere  = " s163_i_upsprestadora = $oParam->iUpsPrestadora ";
  $sSubSqlWhere .= " and s163_i_upssolicitante = unidade.sd02_i_codigo ";
  $sEspec        = " select cbo.rh70_estrutural from rhcbo as cbo where cbo.rh70_sequencial=$oParam->iEspecialidade ";
  $sSubSqlWhere .= " and rh70_estrutural like '".$oParam->iEspecialidade."' ";
  $sSubSqlWhere .= " and s163_i_mescomp = $oParam->iMescomp ";
  $sSubSqlWhere .= " and s163_i_anocomp = $oParam->iAnocomp ";
  $sCamposQaunt  = "s163_i_quantidade";
  if ($oParam->iProfissional != -1) {

    $sSubSqlWhere .= " and s164_especmedico = $oParam->iProfissional ";
    $sCamposQaunt  = "s164_quantidade";

  }
  $sSubSqlWhere .= " limit 1";

  $sSubSql  = $oDaoSauCotas->sql_query_cotas("", $sCamposQaunt, "", $sSubSqlWhere);
  $sCampos .= "coalesce(($sSubSql),0) as distribuido,";

  $sSubSqlWhere  = " sd27_i_rhcbo = $oParam->iCodEspec ";
  $sSubSqlWhere .= " and sd23_i_upssolicitante = unidade.sd02_i_codigo ";
  $sSubSqlWhere .= " and sd04_i_unidade = $oParam->iUpsPrestadora ";
  $sSubSqlWhere .= " and sd23_d_consulta between '$dIni' and '$dFim' ";
  $sSubSqlWhere .= " and not EXISTS ( select * from agendaconsultaanula where";
  $sSubSqlWhere .= " s114_i_agendaconsulta = sd23_i_codigo ) ";
  if ($oParam->iProfissional != -1) {
    $sSubSqlWhere     .= " and sd27_i_codigo = $oParam->iProfissional ";
  }
  $sSubSql       = $oDaoAgendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo)", "", $sSubSqlWhere);
  $sCampos      .= "coalesce(($sSubSql),0) as agendado,";
  $sSubSqlWhere  = " sd27_i_rhcbo = $oParam->iCodEspec ";
  $sSubSqlWhere .= " and sd23_i_upssolicitante = unidade.sd02_i_codigo ";
  $sSubSqlWhere .= " and sd04_i_unidade = $oParam->iUpsPrestadora ";
  $sSubSqlWhere .= " and sd23_d_consulta between '$dIni' and '".$dAtual."' ";
  $sSubSqlWhere .= " and not EXISTS ( select * from agendaconsultaanula where";
  $sSubSqlWhere .= " s114_i_agendaconsulta = sd23_i_codigo ) ";
  $sSubSqlWhere .= " and sd23_i_codigo in (select s102_i_agendamento from prontagendamento) ";
  if ($oParam->iProfissional != -1) {
    $sSubSqlWhere .= " and sd27_i_codigo = $oParam->iProfissional ";
  }
  $sSubSql           = $oDaoAgendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo)", "", $sSubSqlWhere);
  $sCampos          .= "coalesce(($sSubSql),0) as realizado,";

  $sSubSqlWhere      = " sd27_i_rhcbo = $oParam->iCodEspec ";
  $sSubSqlWhere     .= " and sd23_i_upssolicitante = unidade.sd02_i_codigo ";
  $sSubSqlWhere     .= " and sd04_i_unidade = $oParam->iUpsPrestadora ";
  $sSubSqlWhere     .= " and ((sd23_d_consulta >= '$dIni' and sd23_d_consulta < '".$dAtual."' )";
  $sSubSqlWhere     .= " or  (sd23_d_consulta = '$dAtual' and sd23_c_hora < '$sHAtual'))";
  $sSubSqlWhere     .= " and not exists ( select * from agendaconsultaanula where";
  $sSubSqlWhere     .= " s114_i_agendaconsulta = sd23_i_codigo ) ";
  $sSubSqlWhere     .= " and sd23_i_codigo not in (select s102_i_agendamento from prontagendamento) ";
  if ($oParam->iProfissional != -1) {
    $sSubSqlWhere     .= " and sd27_i_codigo = $oParam->iProfissional ";
  }
  $sSubSql           = $oDaoAgendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo)", "", $sSubSqlWhere);
  $sCampos          .= "coalesce(($sSubSql),0) as ausente";

  $sWhere            = " sd02_i_codigo <> $oParam->iUpsPrestadora ";
  $sOrder            = "sd02_i_codigo";
  $sSql              = "select $sCampos from unidades as unidade  ";
  $sSql             .= "  inner join db_depart on sd02_i_codigo = coddepto ";
  $sSql             .= " where $sWhere order by $sOrder;";

  $rsResult          = $oDaoUnidades->sql_record($sSql);
  $aSolicitantes     = array();
  for ($iInd=0; $iInd < $oDaoUnidades->numrows; $iInd++) {

    $oUnidade                             = db_utils::fieldsmemory($rsResult,$iInd);
    $oUnidadeCotas                        = new stdClass();
    $oUnidadeCotas->iCodigo               = $oUnidade->cod;
    $oUnidadeCotas->sNome                 = urlencode($oUnidade->descr);
    if ($oParam->iProfissional != -1) {
      $oUnidadeCotas->iCotas = $oUnidade->cotas;
    }
    $oUnidadeCotas->iDistribuido          = $oUnidade->distribuido;
    $oUnidadeCotas->iDistribuidoOld       = $oUnidade->distribuido;
    $oUnidadeCotas->iAgendado             = $oUnidade->agendado;
    $oUnidadeCotas->iRealizado            = $oUnidade->realizado;
    $oUnidadeCotas->iAusente              = $oUnidade->ausente;
    $oUnidadeCotas->lAlterado             = false;
    $aSolicitantes[count($aSolicitantes)] = $oUnidadeCotas;

  }
  if (count($aSolicitantes) == 0) {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Nenhuma unidade encontrada!';

  }
  $oRetorno->aSolicitantes = $aSolicitantes;

}

if ($oParam->exec == 'duplicarCotas') {

  $dIni              = "$oParam->iAnocomp-$oParam->iMescomp-1";
  $dFim              = "$oParam->iAnocomp-$oParam->iMescomp-";
  $dFim             .= date("t", strtotime("$oParam->iAnocomp-$oParam->iMescomp-1"));
  $dIniAlvo          = "$oParam->iAnocompAlvo-$oParam->iMescompAlvo-1";
  $dFimAlvo          = "$oParam->iAnocompAlvo-$oParam->iMescompAlvo-";
  $dFimAlvo         .= date("t", strtotime("$oParam->iAnocomp-$oParam->iMescompAlvo-1"));
  $oDaoSauCotas      = db_utils::getdao('sau_cotasagendamento');
  $oDaoSauCotasProf  = db_utils::getdao('sau_cotasagendamentoprofissional');
  $oDaoUndmedhorario = db_utils::getdao('undmedhorario');

  db_inicio_transacao();
  if (count($oParam->aEspec) == 0) {

    $oDaoSauCotas->erro_status = "0";
    $oDaoSauCotas->erro_msg    = "Erro - Nenhuma unidade informada!";

  } else {

    $sWhere   = " unidademedicos.sd04_i_unidade = ".$oParam->iUps;
    $sWhere  .= " AND especmedico.sd27_c_situacao = 'A' ";
    $sWhere  .= " and (undmedhorario.sd30_d_valinicial IS NOT NULL AND undmedhorario.sd30_d_valfinal IS NOT NULL) ";
    $sWhere  .= " and undmedhorario.sd30_d_valinicial = '$dIni' ";
    $sWhere  .= " and undmedhorario.sd30_d_valfinal   = '$dFim' ";
    $sSql     = $oDaoUndmedhorario->sql_query("", "undmedhorario.*", "", $sWhere);
    $rsResult = $oDaoUndmedhorario->sql_record($sSql);
    if ($oDaoUndmedhorario->numrows > 0) {

      $iTam = $oDaoUndmedhorario->numrows;
      for ($iInd=0; $iInd < $iTam; $iInd++) {

        $oGrades  = db_utils::fieldsmemory($rsResult,$iInd);
        $sWhere   = " sd30_i_undmed         = ".$oGrades->sd30_i_undmed;
        $sWhere  .= " and sd30_i_diasemana  = ".$oGrades->sd30_i_diasemana;
        $sWhere  .= " and sd30_c_horaini    = '".$oGrades->sd30_c_horaini."'";
        $sWhere  .= " and sd30_c_horafim    = '".$oGrades->sd30_c_horafim."'";
        $sWhere  .= " and sd30_d_valinicial = '".$dIniAlvo."'";
        $sWhere  .= " and sd30_d_valfinal   = '".$dFimAlvo."'";
        $sSql     = $oDaoUndmedhorario->sql_query_file("", "*", "", $sWhere);
        $rs       = $oDaoUndmedhorario->sql_record($sSql);
        if ($oDaoUndmedhorario->numrows == 0) {

          $oDaoUndmedhorario->erro_status       = "1";
          $oDaoUndmedhorario->sd30_i_codigo     = null;
          $oDaoUndmedhorario->sd30_i_undmed     = $oGrades->sd30_i_undmed;
          $oDaoUndmedhorario->sd30_i_diasemana  = $oGrades->sd30_i_diasemana;
          $oDaoUndmedhorario->sd30_c_horaini    = $oGrades->sd30_c_horaini;
          $oDaoUndmedhorario->sd30_c_horafim    = $oGrades->sd30_c_horafim;
          $oDaoUndmedhorario->sd30_i_fichas     = $oGrades->sd30_i_fichas;
          $oDaoUndmedhorario->sd30_i_reservas   = $oGrades->sd30_i_reservas;
          $oDaoUndmedhorario->sd30_i_turno      = $oGrades->sd30_i_turno;
          $oDaoUndmedhorario->sd30_c_tipograde  = $oGrades->sd30_c_tipograde;
          $oDaoUndmedhorario->sd30_i_tipoficha  = $oGrades->sd30_i_tipoficha;
          $oDaoUndmedhorario->sd30_d_valinicial = $dIniAlvo;
          $oDaoUndmedhorario->sd30_d_valfinal   = $dFimAlvo;
          $oDaoUndmedhorario->incluir(null);
          if ($oDaoUndmedhorario->erro_status == "0") {

            $oDaoSauCotas->erro_status = "0";
            $oDaoSauCotas->erro_msg    = $oDaoUndmedhorario->erro_msg;
            break;

          }

        }

      }

    } else {

      $oDaoSauCotas->erro_status = "0";
      $oDaoSauCotas->erro_msg    = "Nenhuma grade de horario para a prestadora ".$oParam->aUps."!";
      break;

    }
  }

  if ($oDaoSauCotas->erro_status != "0") {

    $iTamCotas = count($oParam->aCotas);
    for ($iInd = 0; $iInd < $iTamCotas; $iInd++) {

      $oDaoSauCotas->s163_i_upsprestadora  = $oParam->iUps;
      $oDaoSauCotas->s163_i_upssolicitante = $oParam->aCotas[$iInd]->sUndSoliCod;
      $oDaoSauCotas->s163_i_quantidade     = $oParam->aCotas[$iInd]->iDistribuido;
      $oDaoSauCotas->s163_i_rhcbo          = $oParam->aCotas[$iInd]->iEspecSeq;
      $oDaoSauCotas->s163_i_mescomp        = $oParam->iMescompAlvo;
      $oDaoSauCotas->s163_i_anocomp        = $oParam->iAnocompAlvo;
      //verifica se ja existe lançamento para esta cota
      $sWhere   = " s163_i_upsprestadora      = ".$oParam->iUps;
      $sWhere  .= " and s163_i_upssolicitante = ".$oParam->aCotas[$iInd]->sUndSoliCod;
      $sWhere  .= " and s163_i_rhcbo          = ".$oParam->aCotas[$iInd]->iEspecSeq;
      $sWhere  .= " and s163_i_mescomp        = ".$oParam->iMescompAlvo;
      $sWhere  .= " and s163_i_anocomp        = ".$oParam->iAnocompAlvo;
      $sSql     = $oDaoSauCotas->sql_query_file("", "s163_i_codigo", "", $sWhere);
      $rsResult = $oDaoSauCotas->sql_record($sSql);
      if ($oDaoSauCotas->numrows == 0) {

        $oDaoSauCotas->erro_status   = "1";
        $oDaoSauCotas->s163_i_codigo = null;
        $oDaoSauCotas->incluir(null);

      } else {

        $oDados                      = db_utils::fieldsmemory($rsResult, 0);
        $oDaoSauCotas->s163_i_codigo = $oDados->s163_i_codigo;
        $oDaoSauCotas->alterar($oDados->s163_i_codigo);

      }
      if ($oDaoSauCotas->erro_status == "0") {
        break;
      } else {

        $iTamCotasProf = count($oParam->aCotas[$iInd]->aProf);
        for ($iInd2 = 0; $iInd2 < $iTamCotasProf; $iInd2++) {

          $oDaoSauCotasProf->s164_especmedico     = $oParam->aCotas[$iInd]->aProf[$iInd2]->iCodigo;
          $oDaoSauCotasProf->s164_cotaagendamento = $oDaoSauCotas->s163_i_codigo;
          $oDaoSauCotasProf->s164_quantidade      = $oParam->aCotas[$iInd]->aProf[$iInd2]->iDistribuido;
          //Verifica se ja existe lançamento para está cota
          $sWhere   = " s164_especmedico         = ".$oParam->aCotas[$iInd]->aProf[$iInd2]->iCodigo;
          $sWhere  .= " and s164_cotaagendamento = ".$oDaoSauCotas->s163_i_codigo;
          $sSql     = $oDaoSauCotasProf->sql_query_file("", "s164_codigo", "", $sWhere);
          $rsResult = $oDaoSauCotasProf->sql_record($sSql);
          if ($oDaoSauCotasProf->numrows == 0) {

            $oDaoSauCotasProf->erro_status = "1";
            $oDaoSauCotasProf->s164_codigo = null;
            $oDaoSauCotasProf->incluir(null);

          } else {

            $oDados                        = db_utils::fieldsmemory($rsResult,0);
            $oDaoSauCotasProf->s164_codigo = $oDados->s164_codigo;
            $oDaoSauCotasProf->alterar($oDados->s164_codigo);

          }
          if ($oDaoSauCotasProf->erro_status == "0") {

            $oDaoSauCotas->erro_status = "0";
            $oDaoSauCotas->erro_msg    = $oDaoSauCotasProf->erro_msg;
            break 2;

          }

        }

      }

    }

  }

  if ($oDaoSauCotas->erro_status != "0") {

    /* Verificar se alguma especialidade ficou com saldo negativo */
    /* E calcula a quantidade disponivel para a compatencia alvo */
    $sEspec    = "";
    $sVirgula  = "";
    $iTamEspec = count($oParam->aEspec);
    for ($iX=0; $iX < $iTamEspec; $iX++) {

      $sSql  = "select fc_totalCotasPrestEspecComp";
      $sSql .= "($oParam->iUps,'".$oParam->aEspec[$iX]->iEspecEst."',$oParam->iMescompAlvo,$oParam->iAnocompAlvo) as cotas,";
      $sSql .= "fc_saldoCotasPrestEspecComp";
      $sSql .= "($oParam->iUps,'".$oParam->aEspec[$iX]->iEspecEst."',$oParam->iMescompAlvo,$oParam->iAnocompAlvo) as saldo ;";
      $rsResult = $oDaoSauCotas->sql_record($sSql);

      $oEspec = db_utils::fieldsmemory($rsResult,0);
      $aEspec[$iX][2] = $oEspec->cotas;
      $aEspec[$iX][3] = $oEspec->saldo;
      if ($oEspec->saldo < 0) {

        $oDaoSauCotas->erro_status = "2";
        $sEspec                    = $sVirgula.$oParam->aEspec[$iX][0]." - ".$oParam->aEspec[$iX][1];
        $sVirgula                  = ", ";

      }

    }
    $oRetorno->sEspec = $sEspec;

  }

  if ($oDaoSauCotas->erro_status == "0") {

    db_fim_transacao(true);
    $oRetorno->iStatus  = 0;

  } elseif($oDaoSauCotas->erro_status == "2") {

    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;

  } else {
    db_fim_transacao(false);
  }
  $oRetorno->sMessage = urlencode($oDaoSauCotas->erro_msg);

}
if ($oParam->exec == 'saveCotas') {

  $oDaoSauCotas             = new cl_sau_cotasagendamento();
  $oDaoUndmedhorario        = new cl_undmedhorario();
  $oDaoSauCotasProfissional = new cl_sau_cotasagendamentoprofissional();

  $iTotalUnidades          = count( $oParam->aUnidades );
  $aUnidadesCotasExcedidas = array();

  foreach( $oParam->aUnidades as $oDadosUnidade ) {

    if( isset($oDadosUnidade->iCotas) && $oDadosUnidade->iCotas == 0 ) {
      continue;
    }

    if($oDadosUnidade->iDistribuido < $oDadosUnidade->iDistribuidoOld) {

      $sCampos  = "( s163_i_quantidade - sum( s164_quantidade ) ) as cotas_disponiveis";
      $sWhere   = "     s163_i_upssolicitante = {$oDadosUnidade->iCodigo}";
      $sWhere  .= " AND s163_i_rhcbo          = {$oParam->iCodEspec}";
      $sWhere  .= " AND s163_i_mescomp        = {$oParam->iMescomp}";
      $sWhere  .= " AND s163_i_anocomp        = {$oParam->iAnocomp}";

      if($oParam->iProf <> -1) {
        $sWhere .= " AND s164_especmedico <> {$oParam->iProf}";
      }

      $sWhere            .= " GROUP BY s163_i_quantidade";
      $sSqlContadorCotas  = $oDaoSauCotasProfissional->sql_query_cotas_profissionais( null, $sCampos, null, $sWhere );
      $rsSqlContadorCotas = db_query( $sSqlContadorCotas );

      if( is_resource( $rsSqlContadorCotas ) && pg_num_rows( $rsSqlContadorCotas ) > 0 ) {

        $iCotasLiberadas = db_utils::fieldsMemory( $rsSqlContadorCotas, 0 )->cotas_disponiveis;

        if( ( $oDadosUnidade->iDistribuidoOld - $oDadosUnidade->iDistribuido ) > $iCotasLiberadas ) {

          $aUnidadesCotasExcedidas[]  = $oDadosUnidade->iCodigo;
          $aUnidadesCotasExcedidas[] .= " - " . db_stdClass::normalizeStringJsonEscapeString( $oDadosUnidade->sNome );
        }
      }
    }
  }

  if( count( $aUnidadesCotasExcedidas ) > 0 ) {

    $sMensagem  = "Distribuição não realizada. Os seguintes departamentos excederam o limite de cotas permitidas entre";
    $sMensagem .= " os profissionais:\n\n";
    $sUnidades  = implode( "\n", $aUnidadesCotasExcedidas );

    $oRetorno->sMessage = urlencode( $sMensagem . $sUnidades );
    $oRetorno->iStatus  = 0;

    echo $oJson->encode($oRetorno);

    return;
  }

  $dIni  = "$oParam->iAnocomp-$oParam->iMescomp-1";
  $dFim  = "$oParam->iAnocomp-$oParam->iMescomp-";
  $dFim .= date("t", strtotime("$oParam->iAnocomp-$oParam->iMescomp-1"));

  db_inicio_transacao();

  $sWhere   = "unidademedicos.sd04_i_unidade = ".$oParam->iUpsPrestadora;
  $sWhere  .= " AND rhcbo.rh70_estrutural LIKE '$oParam->iEspecialidade' ";
  $sWhere  .= " AND especmedico.sd27_c_situacao = 'A' ";
  $sWhere  .= " AND ((undmedhorario.sd30_d_valinicial IS NULL AND undmedhorario.sd30_d_valfinal IS NULL) ";
  $sWhere  .= " OR (undmedhorario.sd30_d_valinicial IS NULL ";
  $sWhere  .= " AND undmedhorario.sd30_d_valfinal IS NOT NULL ";
  $sWhere  .= " AND undmedhorario.sd30_d_valfinal >= '$dIni') ";
  $sWhere  .= " OR (undmedhorario.sd30_d_valfinal IS NULL ";
  $sWhere  .= " AND undmedhorario.sd30_d_valinicial IS NOT NULL ";
  $sWhere  .= " AND undmedhorario.sd30_d_valinicial <= '$dFim') ";
  $sWhere  .= " OR ((undmedhorario.sd30_d_valinicial IS NOT NULL AND undmedhorario.sd30_d_valfinal IS NOT NULL) ";
  $sWhere  .= " AND ('$dIni' BETWEEN undmedhorario.sd30_d_valinicial AND undmedhorario.sd30_d_valfinal ";
  $sWhere  .= " OR '$dFim' BETWEEN undmedhorario.sd30_d_valinicial AND undmedhorario.sd30_d_valfinal ";
  $sWhere  .= " OR  undmedhorario.sd30_d_valinicial BETWEEN '$dIni' AND '$dFim' ";
  $sWhere  .= " OR  undmedhorario.sd30_d_valfinal BETWEEN '$dIni' AND '$dFim'))) ";
  $sSql     = $oDaoUndmedhorario->sql_query("","undmedhorario.*","",$sWhere);

  $rsResult = $oDaoUndmedhorario->sql_record($sSql);

  if ($oDaoSauCotas->erro_status != "0") {

    $oDaoSauCotas->s163_i_upsprestadora = $oParam->iUpsPrestadora;
    $oDaoSauCotas->s163_i_rhcbo         = $oParam->iCodEspec;
    $oDaoSauCotas->s163_i_mescomp       = $oParam->iMescomp;
    $oDaoSauCotas->s163_i_anocomp       = $oParam->iAnocomp;
    $iTam                               = count($oParam->aUnidades);
    for ($iInd=0; $iInd < $iTam; $iInd++) {

      if ($oParam->aUnidades[$iInd]->lAlterado == true) {

        //Definição do where
        $sWhere  = " s163_i_upssolicitante    = ".$oParam->aUnidades[$iInd]->iCodigo;
        $sWhere .= " and s163_i_upsprestadora = ".$oParam->iUpsPrestadora;
        $sWhere .= " and s163_i_rhcbo         = ".$oParam->iCodEspec;
        $sWhere .= " and s163_i_mescomp       = ".$oParam->iMescomp;
        $sWhere .= " and s163_i_anocomp       = ".$oParam->iAnocomp;

        $sSql    = $oDaoSauCotas->sql_query_cotas("","s163_i_codigo","",$sWhere);
        $rsCotas = $oDaoSauCotas->sql_record($sSql);
        if ($oDaoSauCotas->numrows == 0) {

          $oDaoSauCotas->erro_status            = "1";
          $oDaoSauCotas->s163_i_upssolicitante  = $oParam->aUnidades[$iInd]->iCodigo;
          $oDaoSauCotas->s163_i_quantidade      = $oParam->aUnidades[$iInd]->iDistribuido;
          $oDaoSauCotas->s163_i_codigo          = null;
          $oDaoSauCotas->incluir(null);

          if ($oParam->iProf != -1) {

            //Inclui profissional
            $oDaoSauCotasProfissional->s164_especmedico     = $oDaoSauCotas->s163_i_codigo;
            $oDaoSauCotasProfissional->s164_cotaagendamento = $oCotas->s163_i_codigo;
            $oDaoSauCotasProfissional->s164_quantidade      = $oParam->aUnidades[$iInd]->iDistribuido;
            $oDaoSauCotasProfissional->s164_codigo          = null;
            $oDaoSauCotasProfissional->incluir(null);
            if ($oDaoSauCotasProfissional->erro_status == "0") {

              $oDaoSauCotas->erro_status = "0";
              $oDaoSauCotas->erro_msg    = $oDaoSauCotasProfissional->erro_msg;
            }
          }
        } else {

          $oCotas = db_utils::fieldsmemory($rsCotas, 0);
          if ($oParam->iProf == -1) {

            $oDaoSauCotas->s163_i_upssolicitante  = $oParam->aUnidades[$iInd]->iCodigo;
            $oDaoSauCotas->s163_i_quantidade      = $oParam->aUnidades[$iInd]->iDistribuido;
            $oDaoSauCotas->s163_i_codigo = $oCotas->s163_i_codigo;
            if ($oDaoSauCotas->s163_i_quantidade == 0) {
              $oDaoSauCotas->excluir($oCotas->s163_i_codigo);
            } else {
              $oDaoSauCotas->alterar($oCotas->s163_i_codigo);
            }
          } else {

            //verificar se existe lançamento para o medico
            $sWhere      = " s163_i_codigo = $oCotas->s163_i_codigo ";
            $sWhere     .= " and s164_especmedico = $oParam->iProf ";
            $sSql        = $oDaoSauCotas->sql_query_cotas("", "s164_codigo", "", $sWhere);
            $rsCotasProf = $oDaoSauCotasProfissional->sql_record($sSql);

            //Seta valores na classe
            $oDaoSauCotasProfissional->s164_especmedico     = $oParam->iProf;
            $oDaoSauCotasProfissional->s164_cotaagendamento = $oCotas->s163_i_codigo;
            $oDaoSauCotasProfissional->s164_quantidade      = $oParam->aUnidades[$iInd]->iDistribuido;

            if ($oDaoSauCotasProfissional->numrows > 0) {

              $oCotasProf                            = db_utils::fieldsmemory($rsCotasProf, 0);
              $oDaoSauCotasProfissional->s164_codigo = $oCotasProf->s164_codigo;
              if ($oDaoSauCotasProfissional->s164_quantidade == 0) {
                $oDaoSauCotasProfissional->excluir($oCotasProf->s164_codigo);
              } else {
                $oDaoSauCotasProfissional->alterar($oCotasProf->s164_codigo);
              }
              if ($oDaoSauCotasProfissional->erro_status == "0") {
                $oDaoSauCotas->erro_status = "0";
              }
              $oDaoSauCotas->erro_msg    = $oDaoSauCotasProfissional->erro_msg;
            } else {

              $oDaoSauCotasProfissional->erro_status = "1";
              $oDaoSauCotasProfissional->s164_codigo = null;
              $oDaoSauCotasProfissional->incluir(null);
              if ($oDaoSauCotasProfissional->erro_status == "0") {
                $oDaoSauCotas->erro_status = "0";
              }
              $oDaoSauCotas->erro_msg    = $oDaoSauCotasProfissional->erro_msg;
            }
          }
        }

        if ($oDaoSauCotas->erro_status == "0") {
          break;
        }

      }

    }
  }
  if ($oDaoSauCotas->erro_status == "0") {

    db_fim_transacao(true);
    $oRetorno->iStatus  = 0;

  } else {
    db_fim_transacao(false);
  }
  $oRetorno->sMessage = urlencode($oDaoSauCotas->erro_msg);

}

if ($oParam->exec == 'getUnidadesMedicos') {

  $oDaoUnidadeMedicos = db_utils::getdao('unidademedicos');

  if (!empty($oParam->sMedicos)) {
    $sWhere = " sd04_i_medico in ($oParam->sMedicos) ";
  } else {
    $sWhere = '';
  }

  $sSql               = $oDaoUnidadeMedicos->sql_query(null, ' distinct sd04_i_unidade, descrdepto ',
                                                       'sd04_i_unidade', $sWhere
                                                      );
  $rsUnidadeMedicos   = $oDaoUnidadeMedicos->sql_record($sSql);
  $iLinhas            = $oDaoUnidadeMedicos->numrows;
  if ($iLinhas > 0) {

    $oRetorno->aUnidades = array();
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

       $oDadosUnidadeMedicos                 = db_utils::fieldsmemory($rsUnidadeMedicos, $iCont);
       $oRetorno->aUnidades[$iCont]->iCodigo = $oDadosUnidadeMedicos->sd04_i_unidade;
       $oRetorno->aUnidades[$iCont]->sDescr  = urlencode($oDadosUnidadeMedicos->descrdepto);

    }

  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode('Nenhuma unidade encontrada.');

  }

} elseif ($oParam->exec == 'getCgsCns') {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

  $sSql             = $oDaoCgsCartaoSus->sql_query(null, 'z01_i_cgsund, z01_v_nome',
                                                   null, ' s115_c_cartaosus = \''.$oParam->iCns.'\''
                                                  );
  $rsCgsCartaoSus   = $oDaoCgsCartaoSus->sql_record($sSql);

  if ($oDaoCgsCartaoSus->numrows > 0) { // se encontrou o cgs

    $oDadosCgsCartaoSus     = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $oRetorno->z01_i_cgsund = $oDadosCgsCartaoSus->z01_i_cgsund;
    $oRetorno->z01_v_nome   = urlencode($oDadosCgsCartaoSus->z01_v_nome);

  } else {

    $oRetorno->z01_i_cgsund = '';
    $oRetorno->z01_v_nome   = '';

  }

} elseif ($oParam->exec == 'getUltimaTriagemAvulsa') {

  $oDaoSauTriagemAvulsa = db_utils::getdao('sau_triagemavulsa');

  $sCampos              = 's152_i_codigo, s152_i_pressaosistolica, s152_i_cintura, s152_n_peso, ';
  $sCampos             .= 's152_i_altura, s152_i_glicemia, s152_i_alimentacaoexameglicemia, s152_i_login, ';
  $sCampos             .= 'sd03_i_codigo, z01_nome, sd04_i_unidade, sd04_i_codigo, descrdepto, ';
  $sCampos             .= 's152_d_dataconsulta, s152_i_cgsund, z01_v_nome, s152_i_pressaodiastolica ';

  $sSql                 = $oDaoSauTriagemAvulsa->sql_query_grid(null, $sCampos, ' s152_i_codigo desc ',
                                                                ' s152_i_cgsund = '.$oParam->iCgs
                                                               );
  $rs                   = $oDaoSauTriagemAvulsa->sql_record($sSql);

  if ($oDaoSauTriagemAvulsa->numrows > 0) { // se encontrou uma triagem para o paciente

    $oRetorno->aTriagens = array();
    for ($iCont = 0; $iCont < $oDaoSauTriagemAvulsa->numrows; $iCont++) {

      $oDados                                                       = db_utils::fieldsmemory($rs, $iCont);
      $oRetorno->aTriagens[$iCont]->s152_i_codigo                   = $oDados->s152_i_codigo;
      $oRetorno->aTriagens[$iCont]->s152_i_pressaosistolica         = $oDados->s152_i_pressaosistolica;
      $oRetorno->aTriagens[$iCont]->s152_i_pressaodiastolica        = $oDados->s152_i_pressaodiastolica;
      $oRetorno->aTriagens[$iCont]->s152_i_cintura                  = $oDados->s152_i_cintura;
      $oRetorno->aTriagens[$iCont]->s152_n_peso                     = $oDados->s152_n_peso;
      $oRetorno->aTriagens[$iCont]->s152_i_altura                   = $oDados->s152_i_altura;
      $oRetorno->aTriagens[$iCont]->s152_i_glicemia                 = $oDados->s152_i_glicemia;
      $oRetorno->aTriagens[$iCont]->s152_i_alimentacaoexameglicemia = $oDados->s152_i_alimentacaoexameglicemia;
      $oRetorno->aTriagens[$iCont]->sd03_i_codigo                   = $oDados->sd03_i_codigo;
      $oRetorno->aTriagens[$iCont]->z01_nome                        = urlencode($oDados->z01_nome);
      $oRetorno->aTriagens[$iCont]->sd04_i_unidade                  = $oDados->sd04_i_unidade;
      $oRetorno->aTriagens[$iCont]->sd04_i_codigo                   = $oDados->sd04_i_codigo;
      $oRetorno->aTriagens[$iCont]->descrdepto                      = $oDados->descrdepto;
      $oRetorno->aTriagens[$iCont]->s152_d_dataconsulta             = urlencode($oDados->s152_d_dataconsulta);
      $oRetorno->aTriagens[$iCont]->s152_i_cgsund                   = $oDados->s152_i_cgsund;
      $oRetorno->aTriagens[$iCont]->z01_v_nome                      = urlencode($oDados->z01_v_nome);
      if ($oDados->s152_i_glicemia > 0 && $oDados->s152_i_alimentacaoexameglicemia != 0) {

        $oRetorno->aTriagens[$iCont]->sAlimentacao = urlencode($oDados->s152_i_alimentacaoexameglicemia == 1 ?
                                                               'Em jejum' : 'Pós prandial'
                                                              );

      } else {
        $oRetorno->aTriagens[$iCont]->sAlimentacao = '';
      }


      if ($iCont == 0) { // Última triagem lançada. Somente a última pode ser editada

        if (db_getsession('DB_id_usuario') == $oDados->s152_i_login) { // Somente o usuário que lançou pode editar
          $oRetorno->aTriagens[$iCont]->lEditar = 'true';
        } else {
          $oRetorno->aTriagens[$iCont]->lEditar = 'false';
        }

      } else {
        $oRetorno->aTriagens[$iCont]->lEditar = 'false';
      }

    }

  } else {

    $oRetorno->iStatus = 2;

  }

} elseif ($oParam->exec == 'verificaHipertensaoDiabetes') {

  $oDaoCgsFatorDeRisco = db_utils::getdao('cgsfatorderisco');

  // Verifica se possui hipertensão
  $sSql = $oDaoCgsFatorDeRisco->sql_query_fator_risco_farmacia(null, 's105_i_codigo', '',
                                                               ' s106_i_cgs = '.$oParam->iCgs.
                                                               " and fa44_i_codrisco in (1007)  "
                                                              );
  $oDaoCgsFatorDeRisco->sql_record($sSql);
  if ($oDaoCgsFatorDeRisco->numrows > 0) {
    $oRetorno->lHipertensao = 'true';
  } else {
    $oRetorno->lHipertensao = 'false';
  }

  // Verifica se possui Diabetes
  $sSql = $oDaoCgsFatorDeRisco->sql_query_fator_risco_farmacia(null, 's105_i_codigo', '',
                                                               ' s106_i_cgs = '.$oParam->iCgs.
                                                               " and fa44_i_codrisco in (1002, 1003)  "
                                                              );
  $oDaoCgsFatorDeRisco->sql_record($sSql);
  if ($oDaoCgsFatorDeRisco->numrows > 0) {
    $oRetorno->lDiabetes = 'true';
  } else {
    $oRetorno->lDiabetes = 'false';
  }

} elseif ($oParam->exec == 'getAcompanhamentos') {

  $oDaoFarCadAcompPacHiperdia = db_utils::getdao('far_cadacomppachiperdia');

  $sCampos                    = 's152_i_codigo, s152_i_pressaosistolica, s152_i_cintura, s152_n_peso, ';
  $sCampos                   .= 's152_i_altura, s152_i_glicemia, s152_i_alimentacaoexameglicemia, s152_i_login, ';
  $sCampos                   .= 'sd03_i_codigo, z01_nome, sd04_i_unidade, sd04_i_codigo, descrdepto, fa50_i_codigo, ';
  $sCampos                   .= 's152_d_dataconsulta, s152_i_cgsund, z01_v_nome, s152_i_pressaodiastolica ';

  $sSql                       = $oDaoFarCadAcompPacHiperdia->sql_query2(null, $sCampos, ' s152_i_codigo desc ',
                                                                        ' fa50_i_cgsund = '.$oParam->iCgs
                                                                       );
  $rs                         = $oDaoFarCadAcompPacHiperdia->sql_record($sSql);

  if ($oDaoFarCadAcompPacHiperdia->numrows > 0) { // se encontrou um cadastro / acompanhamento para o paciente

    $oRetorno->aAcompanhamentos = array();
    for ($iCont = 0; $iCont < $oDaoFarCadAcompPacHiperdia->numrows; $iCont++) {

      $oDados                                                              = db_utils::fieldsmemory($rs, $iCont);
      $oRetorno->aAcompanhamentos[$iCont]->fa50_i_codigo                   = $oDados->fa50_i_codigo;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_codigo                   = $oDados->s152_i_codigo;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_pressaosistolica         = $oDados->s152_i_pressaosistolica;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_pressaodiastolica        = $oDados->s152_i_pressaodiastolica;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_cintura                  = $oDados->s152_i_cintura;
      $oRetorno->aAcompanhamentos[$iCont]->s152_n_peso                     = $oDados->s152_n_peso;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_altura                   = $oDados->s152_i_altura;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_glicemia                 = $oDados->s152_i_glicemia;
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_alimentacaoexameglicemia = $oDados->s152_i_alimentacaoexameglicemia;
      $oRetorno->aAcompanhamentos[$iCont]->sd03_i_codigo                   = $oDados->sd03_i_codigo;
      $oRetorno->aAcompanhamentos[$iCont]->z01_nome                        = urlencode($oDados->z01_nome);
      $oRetorno->aAcompanhamentos[$iCont]->sd04_i_unidade                  = $oDados->sd04_i_unidade;
      $oRetorno->aAcompanhamentos[$iCont]->sd04_i_codigo                   = $oDados->sd04_i_codigo;
      $oRetorno->aAcompanhamentos[$iCont]->descrdepto                      = $oDados->descrdepto;
      $oRetorno->aAcompanhamentos[$iCont]->s152_d_dataconsulta             = urlencode($oDados->s152_d_dataconsulta);
      $oRetorno->aAcompanhamentos[$iCont]->s152_i_cgsund                   = $oDados->s152_i_cgsund;
      $oRetorno->aAcompanhamentos[$iCont]->z01_v_nome                      = urlencode($oDados->z01_v_nome);
      if ($oDados->s152_i_glicemia > 0 && $oDados->s152_i_alimentacaoexameglicemia != 0) {

        $oRetorno->aAcompanhamentos[$iCont]->sAlimentacao = urlencode($oDados->s152_i_alimentacaoexameglicemia == 1 ?
                                                                      'Em jejum' : 'Pós prandial'
                                                                     );

      } else {
        $oRetorno->aAcompanhamentos[$iCont]->sAlimentacao = '';
      }


      if ($iCont == 0) { // Última triagem lançada. Somente a última pode ser editada

        if (db_getsession('DB_id_usuario') == $oDados->s152_i_login) { // Somente o usuário que lançou pode editar
          $oRetorno->aAcompanhamentos[$iCont]->lEditar = 'true';
        } else {
          $oRetorno->aAcompanhamentos[$iCont]->lEditar = 'false';
        }

      } else {
        $oRetorno->aAcompanhamentos[$iCont]->lEditar = 'false';
      }

    }

  } else {

    $oRetorno->iStatus = 2;

  }

} elseif ($oParam->exec == 'getMedicamentosCadAcomp') {

  $oDaoFarMedicamentoCadAcomp = db_utils::getdao('far_medicamentocadacomp');

  $sSql                       = $oDaoFarMedicamentoCadAcomp->sql_query_file(null, '*', 'fa49_i_codigo',
                                                                            ' fa49_i_cadacomp = '.$oParam->iCadAcomp
                                                                           );
  $rs                         = $oDaoFarMedicamentoCadAcomp->sql_record($sSql);

  if ($oDaoFarMedicamentoCadAcomp->numrows > 0) { // se encontrou algum medicamento

    $oRetorno->aMedicamentos = array();
    for ($iCont = 0; $iCont < $oDaoFarMedicamentoCadAcomp->numrows; $iCont++) {

      $oDados                                              = db_utils::fieldsmemory($rs, $iCont);
      $oRetorno->aMedicamentos[$iCont]->fa49_i_medicamento = $oDados->fa49_i_medicamento;
      $oRetorno->aMedicamentos[$iCont]->fa49_n_quantidade  = $oDados->fa49_n_quantidade;

    }

  } else {

    $oRetorno->iStatus = 2;

  }

} elseif ($oParam->exec == 'getInfoCgs') {

  $oDaoCgsUnd       = db_utils::getdao('cgs_und');
  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

  $sSql             = $oDaoCgsUnd->sql_query($oParam->iCgs);
  $rsCgsUnd         = $oDaoCgsUnd->sql_record($sSql);

  $oRetorno->z01_i_cgsund = $oParam->iCgs;
  if ($oDaoCgsUnd->numrows > 0) { // se encontrou o cgs

    $oDadosCgsUnd           = db_utils::fieldsmemory($rsCgsUnd, 0);
    $oRetorno->z01_v_ender  = urlencode($oDadosCgsUnd->z01_v_ender);
    $oRetorno->z01_v_bairro = urlencode($oDadosCgsUnd->z01_v_bairro);
    $oRetorno->z01_v_munic  = urlencode($oDadosCgsUnd->z01_v_munic);
    $oRetorno->z01_v_cep    = urlencode($oDadosCgsUnd->z01_v_cep);
    $oRetorno->z01_v_uf     = urlencode($oDadosCgsUnd->z01_v_uf);
    $oRetorno->z01_v_email  = urlencode($oDadosCgsUnd->z01_v_email);
    $oRetorno->z01_v_telef  = urlencode($oDadosCgsUnd->z01_v_telef);
    $oRetorno->z01_v_telcel = urlencode($oDadosCgsUnd->z01_v_telcel);
    $oRetorno->z01_d_nasc   = $oDadosCgsUnd->z01_d_nasc;
    $oRetorno->z01_v_cgccpf = urlencode($oDadosCgsUnd->z01_v_cgccpf);
    $oRetorno->z01_v_ident  = urlencode($oDadosCgsUnd->z01_v_ident);
    $oRetorno->z01_v_mae    = urlencode($oDadosCgsUnd->z01_v_mae);
    $oRetorno->z01_v_pai    = urlencode($oDadosCgsUnd->z01_v_pai);
    $oRetorno->z01_v_nome   = urlencode($oDadosCgsUnd->z01_v_nome);
    $oRetorno->z01_i_estciv = urlencode($oDadosCgsUnd->z01_i_estciv);
    $oRetorno->z01_v_sexo   = urlencode($oDadosCgsUnd->z01_v_sexo);
    $oRetorno->z01_i_numero = urlencode($oDadosCgsUnd->z01_i_numero);
    $oRetorno->z01_v_compl  = urlencode($oDadosCgsUnd->z01_v_compl);

  } else {

    $oRetorno->z01_v_ender  = '';
    $oRetorno->z01_v_bairro = '';
    $oRetorno->z01_v_munic  = '';
    $oRetorno->z01_v_cep    = '';
    $oRetorno->z01_v_uf     = '';
    $oRetorno->z01_v_email  = '';
    $oRetorno->z01_v_telef  = '';
    $oRetorno->z01_v_telcel = '';
    $oRetorno->z01_d_nasc   = '';
    $oRetorno->z01_v_cgccpf = '';
    $oRetorno->z01_v_ident  = '';
    $oRetorno->z01_v_mae    = '';
    $oRetorno->z01_v_pai    = '';
    $oRetorno->z01_v_nome   = '';
    $oRetorno->z01_i_estciv = '';
    $oRetorno->z01_v_sexo   = '';
    $oRetorno->z01_i_numero = '';
    $oRetorno->z01_v_compl  = '';

  }

  /* pega o cartão sus */
  $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus, s115_c_tipo, s115_i_codigo ',
                                                 ' s115_c_tipo asc ', ' s115_i_cgs = '.$oParam->iCgs
                                                );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);

  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus         = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $oRetorno->s115_c_cartaosus = urlencode($oDadosCgsCartaoSus->s115_c_cartaosus);
    $oRetorno->s115_c_tipo      = urlencode($oDadosCgsCartaoSus->s115_c_tipo);
    $oRetorno->s115_i_codigo    = urlencode($oDadosCgsCartaoSus->s115_i_codigo);

  } else {

    $oRetorno->s115_c_cartaosus = '';
    $oRetorno->s115_c_tipo      = '';
    $oRetorno->s115_i_codigo    = '';

  }

} elseif ($oParam->exec == 'getTodosCnsCgs') {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

  /* pega os cartões sus */
  $sSql               = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus, s115_c_tipo, s115_i_codigo ',
                                                     ' s115_c_tipo asc ', ' s115_i_cgs = '.$oParam->iCgs
                                                    );
  $rsCgsCartaoSus     = $oDaoCgsCartaoSus->sql_record($sSql);

  $oRetorno->aCartoes = array();
  if ($oDaoCgsCartaoSus->numrows > 0) { // se o paciente tem um cartao sus

    for ($iCont = 0; $iCont < $oDaoCgsCartaoSus->numrows; $iCont++) {

      $oDadosCgsCartaoSus                           = db_utils::fieldsmemory($rsCgsCartaoSus, $iCont);
      $oRetorno->aCartoes[$iCont]->s115_c_cartaosus = urlencode($oDadosCgsCartaoSus->s115_c_cartaosus);
      $oRetorno->aCartoes[$iCont]->s115_c_tipo      = urlencode($oDadosCgsCartaoSus->s115_c_tipo);
      $oRetorno->aCartoes[$iCont]->s115_i_codigo    = urlencode($oDadosCgsCartaoSus->s115_i_codigo);

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum cartão SUS encontrado para este paciente.');

  }

} elseif ($oParam->exec == 'getAgendamentosCgs') {

  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $dDataAtual       = date('Y-m-d', db_getsession('DB_datausu'));

  $sSubAtendido     = 'select sd29_i_codigo from prontagendamento inner join prontproced ';
  $sSubAtendido    .= ' on s102_i_prontuario = sd29_i_prontuario where s102_i_agendamento = sd23_i_codigo ';

  $sSubAnulado      = ' select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo limit 1';

  $sSubAnulado2     = ' select * from agendaconsultaanula inner join db_usuarios as a on ';
  $sSubAnulado2    .= ' a.id_usuario = s114_i_login where s114_i_agendaconsulta = sd23_i_codigo limit 1 ';

  $sCampos          = " sd23_d_agendamento, id_usuario, login, ";
  $sCampos         .= " sd101_c_descr, sd23_d_consulta, sd23_c_hora, ";
  $sCampos         .= " sd03_i_codigo, z01_nome, ";
  $sCampos         .= " case when exists($sSubAtendido) then 'Atendido' ";
  $sCampos         .= "   else case when sd23_d_consulta >= '$dDataAtual' then 'Agendado'";
  $sCampos         .= "          else '' end";
  $sCampos         .= " end as situacao, ";
  $sCampos         .= " case when exists($sSubAnulado) then 'true' else 'false' end as anulado, ";
  $sCampos         .= " (select s114_d_data from  ($sSubAnulado) as tmp) as data_anulacao, ";
  $sCampos         .= " (select s114_v_motivo from  ($sSubAnulado) as tmp2) as motivo_anulacao, ";
  $sCampos         .= " (select login from  ($sSubAnulado2) as tmp3) as usuario_anulacao ";

  $sOrderBy         = ' sd23_d_consulta desc, sd23_c_hora desc ';

  $sSql             = $oDaoAgendamentos->sql_query_consulta_geral(null, $sCampos, $sOrderBy,
                                                                  ' sd23_i_numcgs = '.$oParam->iCgs
                                                                 );
  $rs               = $oDaoAgendamentos->sql_record($sSql);

  $oRetorno->aAgendamentos = array();
  if ($oDaoAgendamentos->numrows > 0) { // se o paciente possui agendamentos

    for ($iCont = 0; $iCont < $oDaoAgendamentos->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);

      /* Verifico qual a situação do agendamentos */
      $sSituacao = $oDados->anulado == 'true' ? 'Anulado' :
                                                 (empty($oDados->situacao) ? 'Não compareceu' :
                                                                             $oDados->situacao);

      /* Seto as variáveis para retorno */
      $oRetorno->aAgendamentos[$iCont]->sd23_d_agendamento = urlencode($oDados->sd23_d_agendamento);
      $oRetorno->aAgendamentos[$iCont]->id_usuario         = urlencode($oDados->id_usuario);
      $oRetorno->aAgendamentos[$iCont]->login              = urlencode($oDados->login);
      $oRetorno->aAgendamentos[$iCont]->sd101_c_descr      = urlencode($oDados->sd101_c_descr);
      $oRetorno->aAgendamentos[$iCont]->sd23_d_consulta    = urlencode($oDados->sd23_d_consulta);
      $oRetorno->aAgendamentos[$iCont]->sd23_c_hora        = urlencode($oDados->sd23_c_hora);
      $oRetorno->aAgendamentos[$iCont]->sd03_i_codigo      = urlencode($oDados->sd03_i_codigo);
      $oRetorno->aAgendamentos[$iCont]->z01_nome           = urlencode($oDados->z01_nome);
      $oRetorno->aAgendamentos[$iCont]->situacao           = urlencode($sSituacao);
      $oRetorno->aAgendamentos[$iCont]->data_anulacao      = urlencode($oDados->data_anulacao);
      $oRetorno->aAgendamentos[$iCont]->motivo_anulacao    = urlencode($oDados->motivo_anulacao);
      $oRetorno->aAgendamentos[$iCont]->usuario_anulacao   = urlencode($oDados->usuario_anulacao);

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum agendamento encontrado para este paciente.');

  }

} elseif ($oParam->exec == 'getProntuariosCgs') {

  $oDaoProntProced = db_utils::getdao('prontproced');

  $sCampos         = 'distinct sd24_i_codigo, s102_i_agendamento, sd29_d_data, sd29_c_hora, coddepto, ';
  $sCampos        .= 'descrdepto, sd03_i_codigo, z01_nome, rh70_estrutural, rh70_descr, ';
  $sCampos        .= 'sd29_i_usuario, login, sd29_d_cadastro, sd29_c_cadastro ';

  $sOrderBy        = 'sd29_d_data desc, sd29_c_hora desc, sd24_i_codigo desc';

  $sSql            = $oDaoProntProced->sql_query_consulta_geral(null, $sCampos, $sOrderBy,
                                                                 ' sd24_i_numcgs = '.$oParam->iCgs
                                                                );
  $rs              = $oDaoProntProced->sql_record($sSql);

  $oRetorno->aProntuarios = array();
  if ($oDaoProntProced->numrows > 0) { // se o paciente possui prontuarios

    for ($iCont = 0; $iCont < $oDaoProntProced->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);

      /* Seto as variáveis para retorno */
      $oRetorno->aProntuarios[$iCont]                     = new stdClass();
      $oRetorno->aProntuarios[$iCont]->sd24_i_codigo      = urlencode($oDados->sd24_i_codigo);
      $oRetorno->aProntuarios[$iCont]->s102_i_agendamento = urlencode($oDados->s102_i_agendamento);
      $oRetorno->aProntuarios[$iCont]->sd29_d_data        = urlencode($oDados->sd29_d_data);
      $oRetorno->aProntuarios[$iCont]->sd29_c_hora        = urlencode($oDados->sd29_c_hora);
      $oRetorno->aProntuarios[$iCont]->coddepto           = urlencode($oDados->coddepto);
      $oRetorno->aProntuarios[$iCont]->descrdepto         = urlencode($oDados->descrdepto);
      $oRetorno->aProntuarios[$iCont]->sd03_i_codigo      = urlencode($oDados->sd03_i_codigo);
      $oRetorno->aProntuarios[$iCont]->z01_nome           = urlencode($oDados->z01_nome);
      $oRetorno->aProntuarios[$iCont]->rh70_estrutural    = urlencode($oDados->rh70_estrutural);
      $oRetorno->aProntuarios[$iCont]->rh70_descr         = urlencode($oDados->rh70_descr);
      $oRetorno->aProntuarios[$iCont]->sd29_i_usuario     = urlencode($oDados->sd29_i_usuario);
      $oRetorno->aProntuarios[$iCont]->login              = urlencode($oDados->login);
      $oRetorno->aProntuarios[$iCont]->sd29_d_cadastro    = urlencode($oDados->sd29_d_cadastro);
      $oRetorno->aProntuarios[$iCont]->sd29_c_cadastro    = urlencode($oDados->sd29_c_cadastro);

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum prontuário encontrado para este paciente.');
    $oRetorno->erro     = true;
  }

} elseif ($oParam->exec == 'getExamesCgs') {

  $oDaoLabRequiItem = db_utils::getdao('lab_requiitem');

  $sCampos          = 'la21_d_data, la02_c_descr, la08_c_descr, la32_d_data, la31_d_data';

  $sOrderBy         = 'la21_d_data';

  $sSql             = $oDaoLabRequiItem->sql_query_consulta_geral(null, $sCampos, $sOrderBy,
                                                                  ' la22_i_cgs = '.$oParam->iCgs
                                                                 );
  $rs               = $oDaoLabRequiItem->sql_record($sSql);

  $oRetorno->aExames = array();
  if ($oDaoLabRequiItem->numrows > 0) { // se o paciente possui agendamentos

    for ($iCont = 0; $iCont < $oDaoLabRequiItem->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);

      /* Seto as variáveis para retorno */
      $oRetorno->aExames[$iCont]->la21_d_data  = urlencode($oDados->la21_d_data);
      $oRetorno->aExames[$iCont]->la02_c_descr = urlencode($oDados->la02_c_descr);
      $oRetorno->aExames[$iCont]->la08_c_descr = urlencode($oDados->la08_c_descr);
      $oRetorno->aExames[$iCont]->la32_d_data  = urlencode($oDados->la32_d_data);
      $oRetorno->aExames[$iCont]->la31_d_data  = urlencode($oDados->la31_d_data);

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum exame encontrado para este paciente.');

  }

} elseif ($oParam->exec == 'getInfoCgm') {

  $oDaoCgm = db_utils::getdao('cgm');

  $sSql    = $oDaoCgm->sql_query($oParam->iCgm);
  $rs      = $oDaoCgm->sql_record($sSql);

  $oRetorno->z01_numcgm = $oParam->iCgm;
  if ($oDaoCgm->numrows > 0) { // se encontrou o cgs

    $oDados                   = db_utils::fieldsmemory($rs, 0);
    $oRetorno->z01_ender      = urlencode($oDados->z01_ender);
    $oRetorno->z01_bairro     = urlencode($oDados->z01_bairro);
    $oRetorno->z01_munic      = urlencode($oDados->z01_munic);
    $oRetorno->z01_cep        = urlencode($oDados->z01_cep);
    $oRetorno->z01_uf         = urlencode($oDados->z01_uf);
    $oRetorno->z01_email      = urlencode($oDados->z01_email);
    $oRetorno->z01_telef      = urlencode($oDados->z01_telef);
    $oRetorno->z01_telcel     = urlencode($oDados->z01_telcel);
    $oRetorno->z01_nasc       = urlencode($oDados->z01_nasc);
    $oRetorno->z01_cgccpf     = urlencode($oDados->z01_cgccpf);
    $oRetorno->z01_ident      = urlencode($oDados->z01_ident);
    $oRetorno->z01_mae        = urlencode($oDados->z01_mae);
    $oRetorno->z01_pai        = urlencode($oDados->z01_pai);
    $oRetorno->z01_nome       = urlencode($oDados->z01_nome);
    $oRetorno->z01_nomecomple = urlencode($oDados->z01_nomecomple);
    $oRetorno->z01_estciv     = urlencode($oDados->z01_estciv);
    $oRetorno->z01_sexo       = urlencode($oDados->z01_sexo);
    $oRetorno->z01_numero     = urlencode($oDados->z01_numero);
    $oRetorno->z01_compl      = urlencode($oDados->z01_compl);
    $oRetorno->z01_cxpostal   = urlencode($oDados->z01_cxpostal);
    $oRetorno->z01_cadast     = urlencode($oDados->z01_cadast);
    $oRetorno->z01_ultalt     = urlencode($oDados->z01_ultalt);

  } else {

    $oRetorno->z01_ender    = '';
    $oRetorno->z01_bairro   = '';
    $oRetorno->z01_munic    = '';
    $oRetorno->z01_cep      = '';
    $oRetorno->z01_uf       = '';
    $oRetorno->z01_email    = '';
    $oRetorno->z01_telef    = '';
    $oRetorno->z01_telcel   = '';
    $oRetorno->z01_nasc     = '';
    $oRetorno->z01_cgccpf   = '';
    $oRetorno->z01_ident    = '';
    $oRetorno->z01_mae      = '';
    $oRetorno->z01_pai      = '';
    $oRetorno->z01_nome     = '';
    $oRetorno->z01_estciv   = '';
    $oRetorno->z01_sexo     = '';
    $oRetorno->z01_numero   = '';
    $oRetorno->z01_compl    = '';
    $oRetorno->z01_estciv   = '';
    $oRetorno->z01_cxpostal = '';
    $oRetorno->z01_cadast   = '';
    $oRetorno->z01_ultalt   = '';

  }

} elseif ($oParam->exec == 'verificaForaRede') {

  $oDaoSauMedicosForaRede = db_utils::getdao('sau_medicosforarede');

  $sSql                   = $oDaoSauMedicosForaRede->sql_query_file(null, 's154_i_codigo', '',
                                                                    ' s154_i_medico = '.$oParam->iMedico
                                                                   );
  $rs                     = $oDaoSauMedicosForaRede->sql_record($sSql);

  if ($oDaoSauMedicosForaRede->numrows > 0) { // Se o médico é um médico fora da rede

    $oRetorno->lForaRede = 'true';

  } else {

    $oRetorno->lForaRede = 'false';

  }

} elseif ($oParam->exec == 'lancarAusenciaProfissional') {

  $oDaoAusencias    = db_utils::getdao('ausencias');

  $aHorariosIni = explode(',', $oParam->sHorariosIni);
  $aHorariosFim = explode(',', $oParam->sHorariosFim);
  $iTam         = count($aHorariosIni);

  db_inicio_transacao();
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    $lAgendado = verificaAgendamentoHorario($aHorariosIni[$iCont], $aHorariosFim[$iCont],
                                            formataData($oParam->dIni), formataData($oParam->dFim),
                                            $oParam->iEspecMed
                                           );
    if ($lAgendado) {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode('Não foi possível lançar ausência para o profissional, pois o mesmo '.
                                      'possui agendamento no período de '.$aHorariosIni[$iCont].
                                      ' a '.$aHorariosFim[$iCont]
                                     );
      break;

    }

    $oDaoAusencias->sd06_d_data       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoAusencias->sd06_i_especmed   = $oParam->iEspecMed;
    $oDaoAusencias->sd06_d_inicio     = $oParam->dIni;
    $oDaoAusencias->sd06_d_fim        = $oParam->dFim;
    $oDaoAusencias->sd06_c_horainicio = $aHorariosIni[$iCont];
    $oDaoAusencias->sd06_c_horafim    = $aHorariosFim[$iCont];
    $oDaoAusencias->sd06_i_tipo       = $oParam->sd06_i_tipo;
    $oDaoAusencias->incluir(null);
    if ($oDaoAusencias->erro_status == '0') {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode($oDaoAusencias->erro_msg);

    }

  }

  if ($oRetorno->iStatus != 0) {
    $oRetorno->sMessage = urlencode($oDaoAusencias->erro_msg);
  }

  db_fim_transacao($oRetorno->iStatus == 0 ? true : false);

} elseif ($oParam->exec == 'getCbosProfissional') {

  $oDaoFarCbosProfissional = db_utils::getdao('far_cbosprofissional');

  $sSql                    = $oDaoFarCbosProfissional->sql_query_file(null, 'fa54_i_codigo, fa54_i_cbos ', '',
                                                                      'fa54_i_unidademedico = '.$oParam->iUndMed
                                                                     );
  $rs                      = $oDaoFarCbosProfissional->sql_record($sSql);

  if ($oDaoFarCbosProfissional->numrows > 0) {

    $oDados                  = db_utils::fieldsmemory($rs, 0);
    $oRetorno->fa54_i_codigo = $oDados->fa54_i_codigo;
    $oRetorno->fa54_i_cbos   = $oDados->fa54_i_cbos;

  } else {

    $oRetorno->iStatus       = 0;
    $oRetorno->fa54_i_codigo = '';
    $oRetorno->fa54_i_cbos   = '';

  }

} elseif ($oParam->exec == 'getProcedimentosAgendaProfissional') {

  $oDaoSauProcedMedAgendamento = db_utils::getdao('sau_procedmedagendamento');

  $sSql                        = $oDaoSauProcedMedAgendamento->sql_query(null, 's156_i_codigo, '.
                                                                         'sd63_c_procedimento, '.
                                                                         'sd63_c_nome ',
                                                                         's156_i_codigo asc',
                                                                         's156_i_especmed = '.
                                                                         $oParam->iEspecMed
                                                                        );
  $rs                          = $oDaoSauProcedMedAgendamento->sql_record($sSql);

  if ($oDaoSauProcedMedAgendamento->numrows > 0) {

    $oRetorno->aProcedimentos = db_utils::getCollectionByRecord($rs, false, false, true);

  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode('Nenhum procedimento encontrado.');

  }

} elseif ($oParam->exec == 'incluirProcedimentoAgendaProfissional') {

  $oDaoSauProcedMedAgendamento                      = db_utils::getdao('sau_procedmedagendamento');
  $oDaoSauProcedMedAgendamento->s156_i_especmed     = $oParam->iEspecMed;
  $oDaoSauProcedMedAgendamento->s156_i_procedimento = $oParam->iProcedimento;
  $oDaoSauProcedMedAgendamento->incluir(null);
  if ($oDaoSauProcedMedAgendamento->erro_status == '0') {
    $oRetorno->iStatus = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauProcedMedAgendamento->erro_msg);

} elseif ($oParam->exec == 'excluirProcedimentoAgendaProfissional') {

  $oDaoSauProcedMedAgendamento                = db_utils::getdao('sau_procedmedagendamento');
  $oDaoSauProcedMedAgendamento->s156_i_codigo = $oParam->iCodigo;
  $oDaoSauProcedMedAgendamento->excluir($oParam->iCodigo);
  if ($oDaoSauProcedMedAgendamento->erro_status == '0') {
    $oRetorno->iStatus = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauProcedMedAgendamento->erro_msg);

} elseif ($oParam->exec == 'getProcedimentosAgendaUnidade') {

  $oDaoSauProcedUnidadeAgendamento = db_utils::getdao('sau_procedunidadeagendamento');

  $sSql                            = $oDaoSauProcedUnidadeAgendamento->sql_query(null, 's157_i_codigo, '.
                                                                                 'sd63_c_procedimento, '.
                                                                                 'sd63_c_nome ',
                                                                                 's157_i_codigo asc',
                                                                                 's157_i_unidade = '.
                                                                                 $oParam->iUnidade
                                                                                );
  $rs                              = $oDaoSauProcedUnidadeAgendamento->sql_record($sSql);
  if ($oDaoSauProcedUnidadeAgendamento->numrows > 0) {

    $oRetorno->aProcedimentos = db_utils::getCollectionByRecord($rs, false, false, true);

  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode('Nenhum procedimento encontrado.');

  }

} elseif ($oParam->exec == 'incluirProcedimentoAgendaUnidade') {

  $oDaoSauProcedUnidadeAgendamento                      = db_utils::getdao('sau_procedunidadeagendamento');
  $oDaoSauProcedUnidadeAgendamento->s157_i_unidade      = $oParam->iUnidade;
  $oDaoSauProcedUnidadeAgendamento->s157_i_procedimento = $oParam->iProcedimento;
  $oDaoSauProcedUnidadeAgendamento->incluir(null);
  if ($oDaoSauProcedUnidadeAgendamento->erro_status == '0') {
    $oRetorno->iStatus = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauProcedUnidadeAgendamento->erro_msg);

} elseif ($oParam->exec == 'excluirProcedimentoAgendaUnidade') {

  $oDaoSauProcedUnidadeAgendamento                = db_utils::getdao('sau_procedunidadeagendamento');
  $oDaoSauProcedUnidadeAgendamento->s157_i_codigo = $oParam->iCodigo;
  $oDaoSauProcedUnidadeAgendamento->excluir($oParam->iCodigo);
  if ($oDaoSauProcedUnidadeAgendamento->erro_status == '0') {
    $oRetorno->iStatus = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauProcedUnidadeAgendamento->erro_msg);

} elseif ($oParam->exec == 'getInfoProfissional') {

  $oDaoMedicos = db_utils::getdao('medicos');
  $sSql        = $oDaoMedicos->sql_query_info_profissional(null, '*', '',
                                                           'sd03_i_codigo = '.$oParam->iProfissional
                                                          );
  $rs          = $oDaoMedicos->sql_record($sSql);

  if ($oDaoMedicos->numrows > 0) {
   $oRetorno->oProfissional = db_utils::fieldsmemory($rs, 0, false, false, true);
  } else {

    $oRetorno->iStatus       = 0;
    $oRetorno->sMessage      = urlencode('Profissional não encontrado.');

  }

} elseif ($oParam->exec == 'incluirReceitaMedica') {

  $oDaoSauReceitaMedica       = db_utils::getdao('sau_receitamedica');
  $oDaoSauReceitaProntuario   = db_utils::getdao('sau_receitaprontuario');
  $oDaoSauMedicamentosReceita = db_utils::getdao('sau_medicamentosreceita');

  db_inicio_transacao();

  /* Inclusão da receita médica */
  $oDaoSauReceitaMedica->s158_i_profissional = $oParam->s158_i_profissional;
  $oDaoSauReceitaMedica->s158_i_tiporeceita  = $oParam->s158_i_tiporeceita;
  $oDaoSauReceitaMedica->s158_d_validade     = formataData($oParam->s158_d_validade);
  $oDaoSauReceitaMedica->s158_t_prescricao   = $oParam->s158_t_prescricao;
  $oDaoSauReceitaMedica->s158_i_situacao     = 1; // 1 - Normal, 2 - Atendida, 3 - Anulada
  $oDaoSauReceitaMedica->s158_i_login        = db_getsession('DB_id_usuario');
  $oDaoSauReceitaMedica->s158_d_data         = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoSauReceitaMedica->s158_c_hora         = date('H:i');
  $oDaoSauReceitaMedica->incluir(null);

  if ($oDaoSauReceitaMedica->erro_status == '0') {

    $oRetorno->iStatus = 0;
    $oRetorno->sMessage = urlencode($oDaoSauReceitaMedica->erro_msg);

  }

  if ($oRetorno->iStatus != 0) {

    /* Inclusão dos medicamentos da receita médica */
    $aCodMed                                    = array();
    $oDaoSauMedicamentosReceita->s159_i_receita = $oDaoSauReceitaMedica->s158_i_codigo;
    $iTam                                       = count($oParam->aMedicamentos);
    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      $oDaoSauMedicamentosReceita->s159_i_formaadm = $oParam->aMedicamentos[$iCont]->s159_i_formaadm;
      $oDaoSauMedicamentosReceita->s159_i_medicamento = $oParam->aMedicamentos[$iCont]->s159_i_medicamento;
      $oDaoSauMedicamentosReceita->s159_n_quant = $oParam->aMedicamentos[$iCont]->s159_n_quant;
      $oDaoSauMedicamentosReceita->s159_t_posologia = $oParam->aMedicamentos[$iCont]->s159_t_posologia;
      $oDaoSauMedicamentosReceita->incluir(null);
      if ($oDaoSauMedicamentosReceita->erro_status == '0') {

       $oRetorno->iStatus  = 0;
       $oRetorno->sMessage = urlencode($oDaoSauMedicamentosReceita->erro_msg);
       break;

      }
      $aCodMed[$iCont] = $oDaoSauMedicamentosReceita->s159_i_codigo;

    }

  }

  if ($oRetorno->iStatus != 0) {

    /* Vinculação da receita a um prontuário (FAA) */
    $oDaoSauReceitaProntuario->s162_i_receita    = $oDaoSauReceitaMedica->s158_i_codigo;
    $oDaoSauReceitaProntuario->s162_i_prontuario = $oParam->s162_i_prontuario;
    $oDaoSauReceitaProntuario->incluir(null);
    if ($oDaoSauReceitaProntuario->erro_status == '0') {

     $oRetorno->iStatus  = 0;
     $oRetorno->sMessage = urlencode($oDaoSauReceitaProntuario->erro_msg);

    }

  }

  if ($oRetorno->iStatus != 0) {

    $oRetorno->sMessage      = urlencode($oDaoSauReceitaMedica->erro_msg);
    $oRetorno->s158_i_codigo = $oDaoSauReceitaMedica->s158_i_codigo;
    $oRetorno->aCodMed       = $aCodMed;

  }

  db_fim_transacao($oRetorno->iStatus == 0);

} elseif ($oParam->exec == 'alterarReceitaMedica') {

  $oDaoSauReceitaMedica       = db_utils::getdao('sau_receitamedica');
  $oDaoSauReceitaProntuario   = db_utils::getdao('sau_receitaprontuario');
  $oDaoSauMedicamentosReceita = db_utils::getdao('sau_medicamentosreceita');

  db_inicio_transacao();

  /* Alteração da receita médica */
  $oDaoSauReceitaMedica->s158_i_codigo       = $oParam->s158_i_codigo;
  $oDaoSauReceitaMedica->s158_i_profissional = $oParam->s158_i_profissional;
  $oDaoSauReceitaMedica->s158_i_tiporeceita  = $oParam->s158_i_tiporeceita;
  $oDaoSauReceitaMedica->s158_d_validade     = formataData($oParam->s158_d_validade);
  $oDaoSauReceitaMedica->s158_t_prescricao   = $oParam->s158_t_prescricao;
  $oDaoSauReceitaMedica->alterar($oParam->s158_i_codigo);

  if ($oDaoSauReceitaMedica->erro_status == '0') {

    $oRetorno->iStatus = 0;
    $oRetorno->sMessage = urlencode($oDaoSauReceitaMedica->erro_msg);

  }

  if ($oRetorno->iStatus != 0) {

    /* Exclusão dos medicamentos da receita médica */
    $oDaoSauMedicamentosReceita->excluir(null, ' s159_i_receita = '.$oDaoSauReceitaMedica->s158_i_codigo);
    if ($oDaoSauMedicamentosReceita->erro_status == '0') {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode($oDaoSauMedicamentosReceita->erro_msg);

    }

  }

  if ($oRetorno->iStatus != 0) {

    /* Inclusão dos medicamentos da receita médica */
    $aCodMed                                    = array();
    $oDaoSauMedicamentosReceita->s159_i_receita = $oDaoSauReceitaMedica->s158_i_codigo;
    $iTam                                       = count($oParam->aMedicamentos);
    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      $oDaoSauMedicamentosReceita->s159_i_formaadm = $oParam->aMedicamentos[$iCont]->s159_i_formaadm;
      $oDaoSauMedicamentosReceita->s159_i_medicamento = $oParam->aMedicamentos[$iCont]->s159_i_medicamento;
      $oDaoSauMedicamentosReceita->s159_n_quant = $oParam->aMedicamentos[$iCont]->s159_n_quant;
      $oDaoSauMedicamentosReceita->s159_t_posologia = $oParam->aMedicamentos[$iCont]->s159_t_posologia;
      $oDaoSauMedicamentosReceita->incluir(null);
      if ($oDaoSauMedicamentosReceita->erro_status == '0') {

       $oRetorno->iStatus  = 0;
       $oRetorno->sMessage = urlencode($oDaoSauMedicamentosReceita->erro_msg);
       break;

      }
      $aCodMed[$iCont] = $oDaoSauMedicamentosReceita->s159_i_codigo;

    }

  }

  if ($oRetorno->iStatus != 0) {

    $oRetorno->sMessage      = urlencode($oDaoSauReceitaMedica->erro_msg);
    $oRetorno->s158_i_codigo = $oDaoSauReceitaMedica->s158_i_codigo;
    $oRetorno->aCodMed       = $aCodMed;

  }

  db_fim_transacao($oRetorno->iStatus == 0);

} elseif ($oParam->exec == 'anularReceitaMedica') {

  $oDaoSauReceitaMedicaAnulada = db_utils::getdao('sau_receitamedicaanulada');

  /* Anulação da receita médica incluir na tabela sau_receitamedicaanulada. O campo s158_i_situacao
     da tabela sau_receitamedica é setado para 3 automaticamente via trigger
  */
  $oDaoSauReceitaMedicaAnulada->s161_i_receita = $oParam->s161_i_receita;
  $oDaoSauReceitaMedicaAnulada->s161_i_login   = db_getsession('DB_id_usuario');
  $oDaoSauReceitaMedicaAnulada->s161_d_data    = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoSauReceitaMedicaAnulada->s161_c_hora    = date('H:i');
  $oDaoSauReceitaMedicaAnulada->s161_c_motivo  = $oParam->s161_c_motivo;
  $oDaoSauReceitaMedicaAnulada->incluir(null);
  if ($oDaoSauReceitaMedicaAnulada->erro_status == '0') {
    $oRetorno->iStatus = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauReceitaMedicaAnulada->erro_msg);

} elseif ($oParam->exec == 'excluirMedicamentoReceita') {

  $oDaoSauMedicamentosReceita = db_utils::getdao('sau_medicamentosreceita');
  $oDaoSauMedicamentosReceita->excluir($oParam->s159_i_codigo);
  if ($oDaoSauMedicamentosReceita->erro_status == '0') {
    $oRetorno->iStatus  = 0;
  }
  $oRetorno->sMessage = urlencode($oDaoSauMedicamentosReceita->erro_msg);

} elseif ($oParam->exec == 'getReceitasFaa') {

  $oDaoSauReceitaMedica = db_utils::getdao('sau_receitamedica');

  $sSql                 = $oDaoSauReceitaMedica->sql_query_prontuario(null, '*', 's158_i_codigo desc',
                                                                      's162_i_prontuario = '.$oParam->iFaa
                                                                     );
  $rs                   = $oDaoSauReceitaMedica->sql_record($sSql);
  if ($oDaoSauReceitaMedica->numrows > 0) {
    $oRetorno->aReceitas = db_utils::getCollectionByRecord($rs, false, false, true);
  } else {
   $oRetorno->iStatus = 0;
  }

} elseif ($oParam->exec == 'getRemediosReceita') {

  $oDaoSauMedicamentosReceita = db_utils::getdao('sau_medicamentosreceita');

  $sCampos                    = 's159_i_codigo, s159_i_formaadm, s159_i_medicamento, ';
  $sCampos                   .= 'm60_descr, s159_n_quant, s160_c_descr, s159_t_posologia ';
  $sSql                       = $oDaoSauMedicamentosReceita->sql_query_receita(null, $sCampos, 's159_i_codigo',
                                                                               's159_i_receita = '.$oParam->iReceita
                                                                              );
  $rs                         = $oDaoSauMedicamentosReceita->sql_record($sSql);
  if ($oDaoSauMedicamentosReceita->numrows > 0) {
    $oRetorno->aMedicamentos = db_utils::getCollectionByRecord($rs, false, false, true);
  } else {
   $oRetorno->iStatus = 0;
  }

} elseif ($oParam->exec == 'getUnidadesSaude') {

  $oDaoUnidades = db_utils::getdao('unidades');
  $sSql         = $oDaoUnidades->sql_query("", "sd02_i_codigo as cod, descrdepto as desc", "sd02_i_codigo");
  $rsUnidades   = $oDaoUnidades->sql_record($sSql);
  for ($iInd = 0; $iInd < $oDaoUnidades->numrows; $iInd++) {

    $oDataUnidade                             = db_utils::fieldsmemory($rsUnidades,$iInd);
    $oRetorno->unidades[$iInd]->sd02_i_codigo = $oDataUnidade->cod;
    $oRetorno->unidades[$iInd]->descrdepto    = urlencode($oDataUnidade->desc);

  }
  if ($oDaoUnidades->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhuma unidade encontrada.');

  }

} elseif ($oParam->exec == 'getGrupos') {

  $oDaoGrupo = db_utils::getdao('sau_grupo');
  $sSql      = $oDaoGrupo->sql_query("",
                                     " distinct on (sd60_c_grupo) sd60_c_grupo||' - '||sd60_c_nome as nome, sd60_c_nome".
                                       ", sd60_i_anocomp, sd60_i_mescomp, sd60_i_codigo",
                                     "sd60_c_grupo, sd60_i_anocomp desc, sd60_i_mescomp desc"
                                    );
  $rsGrupo   = $oDaoGrupo->sql_record($sSql);
  for ($iInd = 0; $iInd < $oDaoGrupo->numrows; $iInd++) {

    $oDataGrupo                      = db_utils::fieldsmemory($rsGrupo, $iInd);
    $oRetorno->grupo[$iInd]->codigo  = $oDataGrupo->sd60_i_codigo;
    $oRetorno->grupo[$iInd]->nome    = urlencode($oDataGrupo->nome);

  }
  if ($oDaoGrupo->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum grupo encontrado.');

  }

} elseif ($oParam->exec == 'getSubGrupos') {

  $oDaoSubGrupo = db_utils::getdao('sau_subgrupo');
  $sSql         = $oDaoSubGrupo->sql_query("",
                                           "distinct on (sd61_c_subgrupo) sd61_c_subgrupo||' - '||sd61_c_nome as nome ".
                                            ", sd61_i_codigo, sd61_c_nome, sd61_i_anocomp, sd61_i_mescomp ",
                                           "sd61_c_subgrupo, sd61_i_anocomp desc, sd61_i_mescomp desc",
                                           " sd60_c_grupo = '".$oParam->grupo."'"
                                          );
  $rsSubGrupo   = $oDaoSubGrupo->sql_record($sSql);
  for ($iInd = 0; $iInd < $oDaoSubGrupo->numrows; $iInd++) {

    $oDataSubGrupo                      = db_utils::fieldsmemory($rsSubGrupo, $iInd);
    $oRetorno->subgrupo[$iInd]->codigo  = $oDataSubGrupo->sd61_i_codigo;
    $oRetorno->subgrupo[$iInd]->nome    = urlencode($oDataSubGrupo->nome);

  }
  if ($oDaoSubGrupo->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum grupo encontrado.');

  }

} elseif ($oParam->exec == 'getFormaOrganizacao') {

  $oDaoFormaOrganizacao = db_utils::getdao('sau_formaorganizacao');
  $sSql                 = $oDaoFormaOrganizacao->sql_query("",
                                                    "distinct on (sd62_c_formaorganizacao) sd62_c_nome, ".
                                                    " sd62_c_formaorganizacao||' - '||sd62_c_nome as nome ".
                                                    ", sd62_i_anocomp, sd62_i_mescomp, sd62_i_codigo",
                                                    "sd62_c_formaorganizacao, sd62_i_anocomp desc, sd62_i_mescomp desc",
                                                    " a.sd60_c_grupo = '".$oParam->grupo.
                                                    "' and sau_subgrupo.sd61_c_subgrupo = '".$oParam->subgrupo."'"
                                                   );
  $rsFormaOrganizacao   = $oDaoFormaOrganizacao->sql_record($sSql);
  for ($iInd = 0; $iInd < $oDaoFormaOrganizacao->numrows; $iInd++) {

    $oDataFormaOrganizacao                      = db_utils::fieldsmemory($rsFormaOrganizacao, $iInd);
    $oRetorno->formaorganizacao[$iInd]->codigo  = $oDataFormaOrganizacao->sd62_i_codigo;
    $oRetorno->formaorganizacao[$iInd]->nome    = urlencode($oDataFormaOrganizacao->nome);

  }
  if ($oDaoFormaOrganizacao->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhuma Forma de Organização encontrada.');

  }

} elseif ($oParam->exec == 'getProcedimentos') {

  $oDaoProcedimento = db_utils::getdao('sau_procedimento_ext');
  $sCampos          = "distinct on (sd63_c_procedimento) sd63_c_procedimento, sd63_i_codigo, sd63_c_nome";
  $sWhere           = "";
  $sOrdem           = " sd63_c_procedimento ";
  if (isset($oParam->sProcedimento) && $oParam->sProcedimento != '') {

    $sWhere  = " sd63_c_procedimento = '".$oParam->sProcedimento."' ";

  } elseif (isset($oParam->sFormaOrg) && $oParam->sFormaOrg != '') {

    $sWhere  = " sd63_c_procedimento like '";
    $sWhere .= $oParam->sGrupo.$oParam->sSubGrupo.$oParam->sFormaOrg;
    $sWhere .= "%' ";

  } elseif (isset($oParam->sSubGrupo) && $oParam->sSubGrupo != '') {

    $sWhere  = " sd63_c_procedimento like '";
    $sWhere .= $oParam->sGrupo.$oParam->sSubGrupo;
    $sWhere .= "%' ";

  } elseif (isset($oParam->sGrupo) && $oParam->sGrupo != '') {

    $sWhere  = " sd63_c_procedimento like '";
    $sWhere .= $oParam->sGrupo;
    $sWhere .= "%' ";

  }
  $sSql            = $oDaoProcedimento->sql_query_ext("", $sCampos, $sOrdem, $sWhere);
  $rsProcedimento  = $oDaoProcedimento->sql_record($sSql);
  for ($iInd = 0; $iInd < $oDaoProcedimento->numrows; $iInd++) {

    $oDataProcedimento                                  = db_utils::fieldsmemory($rsProcedimento, $iInd);
    $oRetorno->procedimento[$iInd]->sd63_i_codigo       = $oDataProcedimento->sd63_i_codigo;
    $oRetorno->procedimento[$iInd]->sd63_c_nome         = urlencode($oDataProcedimento->sd63_c_nome);
    $oRetorno->procedimento[$iInd]->sd63_c_procedimento = urlencode($oDataProcedimento->sd63_c_procedimento);

  }
  if ($oDaoProcedimento->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhuma Procedimentos encontrado.');

  }

} elseif ($oParam->exec == 'gerarTxtProcedimentos') {

  $pArquivo = fopen("tmp/".$oParam->sNomeArquivo, "w");
  if ($pArquivo) {

    fwrite($pArquivo, $oParam->sProcedimentos, strlen($oParam->sProcedimentos));
    fclose($pArquivo);
    $oRetorno->sNomeArquivo = $oParam->sNomeArquivo;

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum procedimento foi informado.');

  }

}

if ($oParam->exec == 'gerarFAATXT') {

  $oSauConfig = loadConfig("sau_config");
  if (isset($oParam->sChaveProntuarios)) {
    $lProntuario = true;
    $sChaveProntuarios = $oParam->sChaveProntuarios;
  } else {
    $lProntuario = false;
  }

  set_time_limit ( 0 );

  $clprontuarios      = db_utils::getdao('prontuarios_ext');
  $clagendamentos     = db_utils::getdao('agendamentos_ext');
  $clprontagendamento = db_utils::getdao('prontagendamento');
  $clsau_proccbo      = db_utils::getdao('sau_proccbo');
  $clprontproced      = db_utils::getdao('prontproced_ext');
  $clprontprofatend   = db_utils::getdao('prontprofatend_ext');

  if($lProntuario == false) {

    $sSql = "select unidades.*,
                  cgm.z01_nome as estabelecimento,
                  cgm.z01_ender as est_ender,
                  cgm.z01_bairro as est_bairro,
                  cgm.z01_munic as est_munic,
                  cgm.z01_uf as est_uf
           from unidades
             inner join db_depart   on db_depart.coddepto = unidades.sd02_i_codigo
             left join cgm         on cgm.z01_numcgm = unidades.sd02_i_numcgm
           where unidades.sd02_i_codigo = ".$oParam->iUnidade;
    $result_und = $clprontuarios->sql_record ($sSql);

    if ($clprontuarios->numrows == 0) {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode('Verifique se o código '.$oParam->iUnidade.' esta cadastrada como unidade.');
      echo $oJson->encode($oRetorno);
      exit;

    }
    $reemissao = false;
    if (isset($oParam->lAgendamentoFaa) && $oParam->lAgendamentoFaa == true) {

      $aVet = explode("/",$oParam->sd23_d_consulta);
      $iAno = $aVet[2];
      $iMes = $aVet[1];
      $iDia = $aVet[0];

      if (isset ( $oParam->iCodAgendamento )) {
        $sCodAgendamento = " and sd23_i_codigo in ($oParam->iCodAgendamento) ";
      }

      $sCampos  = " *, fc_totalagendado('$iAno/$iMes/$iDia', ".$oParam->iProfissional.", ".$oParam->iDiasemana.")";
      $sCampos .= " as total_agendado ";
      $sWhere   = " sd23_d_consulta = '$iAno-$iMes-$iDia' ".$sCodAgendamento ;
      $sWhere  .= " and not exists (select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo) ";
      $sWhere  .= " and sd27_i_codigo = ".$oParam->iProfissional;
      $sSql = $clagendamentos->sql_query_ext (null,
                                              $sCampos,
                                              "sd23_i_codigo",
                                              $sWhere);
      $rsAgendamento = $clagendamentos->sql_record ($sSql);
      $oAgendamento  = db_utils::fieldsMemory($rsAgendamento, 0);
      $aTotalAgenda  = explode (",", $oAgendamento->total_agendado);
      $iQtd          = $clagendamentos->numrows;
    }

    db_inicio_transacao ();

    // busca o primeiro setor da unidade  incluso para recepção
    $sSqlSetor  = " select min(sd91_codigo) as sd91_codigo from setorambulatorial ";
    $sSqlSetor .= " where sd91_unidades = {$oParam->iUnidade} and sd91_local = 1 ";

    $rsSetorUnidade = db_query($sSqlSetor);

    $lErroBuscarSetor = false;
    $sMsgErroSetor    = "Não foi encontrado um setor ambulatorial para esta unidade.\n";
    $sMsgErroSetor   .= "Cadastre um setor ambulatorial em:\n\tCadastro > Setor Ambulatorial para o Local: RECEPÇÃO";
    if ( !$rsSetorUnidade || pg_num_rows($rsSetorUnidade) == 0) {

      $lErroBuscarSetor   = true;
      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode( $sMsgErroSetor );
    }

    $iCodigoSetorAmbulatorial = null;
    if ( !$lErroBuscarSetor ) {

      $iCodigoSetorAmbulatorial = db_utils::fieldsMemory($rsSetorUnidade, 0)->sd91_codigo;

      if ( empty($iCodigoSetorAmbulatorial) ) {

        $lErroBuscarSetor   = true;
        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode( $sMsgErroSetor );
        echo $oJson->encode($oRetorno);
        exit;
      }
    }

    //linca agendamento com prontuario
    for ($i = 0; $i < $iQtd; $i++) {

      $oAgendamento  = db_utils::fieldsMemory($rsAgendamento, $i);
      if ($oAgendamento->s102_i_prontuario == 0 || $oAgendamento->s102_i_prontuario == null) {

        $sSql = $clprontagendamento->sql_query(null, "*", null, "s102_i_agendamento = ".$oAgendamento->sd23_i_codigo);
        $clprontagendamento->sql_record($sSql);
        if ($clprontagendamento->numrows == 0) {

          //Gerar número prontuário automático
          $sFcNumatend  = "select fc_numatend()";
          $rsFcNumatend = db_query($sFcNumatend);
          $aFcNumatend  = explode(",", pg_result($rsFcNumatend, 0, 0));

          $clprontuarios->sd24_i_ano              = trim($aFcNumatend[0]);
          $clprontuarios->sd24_i_mes              = trim($aFcNumatend[1]);
          $clprontuarios->sd24_i_seq              = trim($aFcNumatend[2]);
          $clprontuarios->sd24_i_login            = DB_getsession("DB_id_usuario");
          $clprontuarios->sd24_i_unidade          = $oParam->iUnidade;
          $clprontuarios->sd24_i_numcgs           = isset ( $oParam->lAgendamentoFaa ) ? $oAgendamento->sd23_i_numcgs : null;
          $clprontuarios->sd24_d_cadastro         = $oAgendamento->sd23_d_consulta;
          $clprontuarios->sd24_c_cadastro         = $oAgendamento->sd23_c_hora;
          $clprontuarios->sd24_setorambulatorial  = $iCodigoSetorAmbulatorial;

          $clprontuarios->incluir (null);
          if ($clprontuarios->erro_status == "0") {

            $oRetorno->iStatus  = 2;
            $oRetorno->sMessage = urlencode(" Prontuários: ".$clprontuarios->erro_msg);
            echo $oJson->encode($oRetorno);
            exit;
          }

          //linca agendamento com prontuario
          if (isset($oParam->lAgendamentoFaa) && $oParam->lAgendamentoFaa == true) {

            $clprontagendamento->s102_i_agendamento = $oAgendamento->sd23_i_codigo;
            $clprontagendamento->s102_i_prontuario  = $clprontuarios->sd24_i_codigo;
            $clprontagendamento->incluir (null);
            if ($clprontagendamento->numrows_incluir == 0) {

              $oRetorno->iStatus  = 2;
              $oRetorno->sMessage = urlencode("Prontuário Agendamento: ".$clprontagendamento->erro_msg);
            }
            //Profissional de Atendimento
            $clprontprofatend->s104_i_prontuario   = $clprontuarios->sd24_i_codigo;
            $clprontprofatend->s104_i_profissional = $oParam->iProfissional;
            $clprontprofatend->incluir (null);
            if( $clprontprofatend->numrows_incluir == 0 ){

              $oRetorno->iStatus  = 2;
              $oRetorno->sMessage = urlencode("Prontuário Prof. Atendimento: ".$clprontprofatend->erro_msg);
              echo $oJson->encode($oRetorno);
              exit;

            }
          }
          //prontproced
          if (isset ( $oAgendamento->s125_i_procedimento ) && ( int )$oAgendamento->s125_i_procedimento > 0) {

            $clprontproced->sd29_i_prontuario   = $clprontuarios->sd24_i_codigo;
            $clprontproced->sd29_i_procedimento = $oAgendamento->s125_i_procedimento;
            $clprontproced->sd29_i_profissional = $oParam->iProfissional;
            $clprontproced->sd29_d_data         = $oAgendamento->sd23_d_consulta;
            $clprontproced->sd29_c_hora         = $oAgendamento->sd23_c_hora;
            $clprontproced->sd29_i_usuario      = $oAgendamento->sd23_i_usuario;
            $clprontproced->sd29_sigilosa       = 'false';
            $clprontproced->incluir(null);
            if( $clprontproced->numrows_incluir == 0 ){

              $oRetorno->iStatus  = 2;
              $oRetorno->sMessage = urlencode("Prontproce: ".$clprontproced->erro_msg);
              echo $oJson->encode($oRetorno);
              exit;

            }
            //Digitada sim
            $clprontuarios->sd24_c_digitada = 'S';
            $clprontuarios->alterar($clprontuarios->sd24_i_codigo);
            if( $clprontuarios->numrows_alterar == 0 ){

              $oRetorno->iStatus  = 2;
              $oRetorno->sMessage = urlencode("Prontuários: ".$clprontuarios->erro_msg);
              echo $oJson->encode($oRetorno);
              exit;

            }

          }
          $sd24_i_codigo = $clprontuarios->sd24_i_codigo;
        }
      }
    }

    db_fim_transacao ();

    $sCampos  = " *, fc_totalagendado('$iAno/$iMes/$iDia', ".$oParam->iProfissional.", ".$oParam->iDiasemana.")";
    $sCampos .= " as total_agendado ";
    $sWhere   = " sd23_d_consulta = '$iAno-$iMes-$iDia' ".$sCodAgendamento ;
    $sWhere  .= " and not exists (select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo) ";
    $sWhere  .= " and sd27_i_codigo = ".$oParam->iProfissional;
    $clagendamentos->sql_query_ext (null,
                                    $sCampos,
                                    "sd23_i_codigo",
                                    $sWhere );
    $rsAgendamento = $clagendamentos->sql_record ($sSql);
    if ($clagendamentos->numrows == 0) {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode("Prontuario não encontrado!");
      echo $oJson->encode($oRetorno);
      exit;

    }
    $sChaveProntuarios = '';
    $sSep              = '';
    for ($i = 0; $i < $clagendamentos->numrows; $i++ ){

      $oAgendamento       = db_utils::fieldsMemory ($rsAgendamento, $i);
      $sChaveProntuarios .= $sSep.$oAgendamento->s102_i_prontuario;
      $sSep=",";

    }

  }

  if(isset($oParam->iModelo)){
    if($oParam->iModelo == 6 || $oParam->iModelo == 7){
      $oRetorno->iTipo = 2;
    } else {
      $oRetorno->iTipo = 1;
    }
  } else{
    if ($oSauConfig->s103_i_modelofaa == 6 || $oSauConfig->s103_i_modelofaa == 7) {
      $oRetorno->iTipo = 2;
    } else {
      $oRetorno->iTipo = 1;
    }
  }
  if ($oRetorno->iTipo == 2) {

    if(isset($oParam->iModelo)){
      $iModelo = $oParam->iModelo;
    } else {
      $iModelo = $oSauConfig->s103_i_modelofaa;
    }
    //seleciona o modelo de FAA
    if ($iModelo == 6) {
      $sModelo = "documentos/templates/txt/sau_modelo_faa.txt";
    } else {
      $sModelo = "documentos/templates/txt/sau_modelo_faa_bage.txt";
    }


    $dHoje      = date("Y-m-d",db_getsession("DB_datausu"));
    $iInstitui  = db_getsession("DB_instit");

    require_once(modification('model/DBProcessaTemplateTXT.model.php'));
    try {
      $oGerador = new DBProcessaTemplateTXT($sModelo);
    } catch (Exception $oExcecao) {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
      echo $oJson->encode($oRetorno);
      exit;

    }

    $aChaveProntuarios = explode(",", $sChaveProntuarios);
    $iTam = count($aChaveProntuarios);

    if ($iModelo == 6) {

      //Seleciona os dados da FAA
      $oProntuarios  = db_utils::getdao('prontuarios');
      $oProntproced  = db_utils::getdao('prontproced');

      /* Sub sql para obter os procedimentos (prontproced) */
      $sSubProc      = ' from prontproced as a ';
      $sSubProc     .= '   inner join sau_procedimento as b on b.sd63_i_codigo = a.sd29_i_procedimento ';
      $sSubProc     .= '     where a.sd29_i_prontuario = prontuarios.sd24_i_codigo ';
      $sSubProc     .= '       order by a.sd29_i_codigo limit 1 ';

      /* Sub sql para obter os profissionais (vindos da prontproced) */
      $sSubProf      = ' from prontproced as a ';
      $sSubProf     .= '   inner join sau_procedimento as b on b.sd63_i_codigo = a.sd29_i_procedimento ';
      $sSubProf     .= '   inner join especmedico as c on c.sd27_i_codigo = a.sd29_i_profissional ';
      $sSubProf     .= '   inner join unidademedicos as d on d.sd04_i_codigo = c.sd27_i_undmed ';
      $sSubProf     .= '   inner join medicos as e on e.sd03_i_codigo = d.sd04_i_medico ';
      $sSubProf     .= '   inner join cgm as f on f.z01_numcgm = e.sd03_i_cgm ';
      $sSubProf     .= '     where a.sd29_i_prontuario = prontuarios.sd24_i_codigo ';
      $sSubProf     .= '       order by a.sd29_i_codigo limit 1 ';

      /* Campos a serem buscados para emitir a FAA */
      $sCamposGeral  = " (select munic from db_config where codigo = $iInstitui) as municipio, ";
      $sCamposGeral .= " sd24_i_codigo      as nro_faa, ";
      $sCamposGeral .= " fc_formatadata(sd24_d_cadastro) as data_faa, ";
      $sCamposGeral .= " sd24_c_cadastro    as hora_faa, ";
      $sCamposGeral .= " sd02_i_codigo      as cod_ups, ";
      $sCamposGeral .= " descrdepto         as nome_ups, ";
      $sCamposGeral .= " cgm_und.z01_ender  as rua_rua, ";
      $sCamposGeral .= " cgm_und.z01_compl  as complemanto_end_ups, ";
      $sCamposGeral .= " cgm_und.z01_numero as nro_end_ups, ";
      $sCamposGeral .= " cgm_und.z01_bairro as bairro_ups, ";
      $sCamposGeral .= " cgm_und.z01_cep    as cep_ups, ";
      $sCamposGeral .= " sd02_c_siasus      as sia_sus, ";
      $sCamposGeral .= " nome               as login, ";
      $sCamposGeral .= " z01_i_cgsund       as nro_cgs, ";
      $sCamposGeral .= " z01_v_nome         as nome_cgs, ";
      $sCamposGeral .= " z01_v_ender        as rua_pac, ";
      $sCamposGeral .= " z01_i_numero       as nro_pac, ";
      $sCamposGeral .= " z01_v_compl        as complemento_end_pac, ";
      $sCamposGeral .= " z01_v_bairro       as bairro_pac, ";
      $sCamposGeral .= " z01_v_munic        as cidade_pac, ";
      $sCamposGeral .= " z01_v_cep          as cep_pac, ";
      $sCamposGeral .= " z01_v_mae          as pai_pac, ";
      $sCamposGeral .= " z01_v_pai          as mae_pac, ";
      $sCamposGeral .= " fc_formatadata(z01_d_nasc) as data_nasc_pac, ";
      $sCamposGeral .= " fc_idade_anomesdia(z01_d_nasc, '$dHoje') as idade_pac, ";
      $sCamposGeral .= " case when z01_v_sexo = 'M' then 'MASCULINO' when z01_v_sexo = 'F' then 'FEMININO' ";
      $sCamposGeral .= " else z01_v_sexo end as sexo_pac, ";
      $sCamposGeral .= " case when sd03_i_codigo is null then (select e.sd03_i_codigo $sSubProf)";
      $sCamposGeral .= "      else sd03_i_codigo end as cod_medico, ";
      $sCamposGeral .= " case when cgm_med.z01_nome is null then (select f.z01_nome $sSubProf)";
      $sCamposGeral .= "      else cgm_med.z01_nome end as nome_medico, ";
      $sCamposGeral .= " case when sd03_i_crm is null then (select e.sd03_i_crm $sSubProf) else sd03_i_codigo end as crm, ";
      $sCamposGeral .= " case when s144_c_descr <> '' then s144_c_descr else sd24_v_motivo end as motivo_atend, ";
      $sCamposGeral .= " sd24_t_diagnostico as diagnostico,";
      $sCamposGeral .= " sd24_i_tipo        as tipo_atend,";
      $sCamposGeral .= " fc_formatadata(sd23_d_consulta) as data_atend, ";
      $sCamposGeral .= " sd23_c_hora        as hora_atend ";

      $sCamposProc  = "sd63_c_procedimento as cod_proc,";
      $sCamposProc .= "sd63_c_nome         as nome_proc,";
      $sCamposProc .= "sd29_t_tratamento   as tratamento,";
      $sCamposProc .= "sd70_c_cid          as cod_cid,";
      $sCamposProc .= "sd70_c_nome         as nome_cid ";

    } else {

      /* Sub sql para obter os profissionais (vindos da prontproced) */
      $sSubProf      = ' from prontproced as a ';
      $sSubProf     .= '   inner join sau_procedimento as b on b.sd63_i_codigo = a.sd29_i_procedimento ';
      $sSubProf     .= '   inner join especmedico as c on c.sd27_i_codigo = a.sd29_i_profissional ';
      $sSubProf     .= '   inner join unidademedicos as d on d.sd04_i_codigo = c.sd27_i_undmed ';
      $sSubProf     .= '   inner join medicos as e on e.sd03_i_codigo = d.sd04_i_medico ';
      $sSubProf     .= '   inner join cgm as f on f.z01_numcgm = e.sd03_i_cgm ';
      $sSubProf     .= '     where a.sd29_i_prontuario = prontuarios.sd24_i_codigo ';
      $sSubProf     .= '       order by a.sd29_i_codigo limit 1 ';

      $sSubProced    = ' from prontproced ';
      $sSubProced   .= ' inner join sau_procedimento on sd29_i_procedimento = sd63_i_codigo ';
      $sSubProced   .= ' where sd29_i_prontuario = sd24_i_codigo limit 1';

      $sCamposGeral  = " (select munic from db_config where codigo = $iInstitui) as municipio, ";
      $sCamposGeral .= " sd24_i_codigo      as nro_faa, ";
      $sCamposGeral .= " fc_formatadata(sd24_d_cadastro) as data_faa, ";
      $sCamposGeral .= " sd24_c_cadastro    as hora_faa, ";
      $sCamposGeral .= " sd02_i_codigo      as cod_ups, ";
      $sCamposGeral .= " descrdepto         as nome_ups, ";
      $sCamposGeral .= " cgm_und.z01_ender  as rua_rua, ";
      $sCamposGeral .= " cgm_und.z01_compl  as complemanto_end_ups, ";
      $sCamposGeral .= " cgm_und.z01_numero as nro_end_ups, ";
      $sCamposGeral .= " cgm_und.z01_bairro as bairro_ups, ";
      $sCamposGeral .= " cgm_und.z01_cep    as cep_ups, ";
      $sCamposGeral .= " sd02_c_siasus      as sia_sus, ";
      $sCamposGeral .= " nome               as login, ";
      $sCamposGeral .= " z01_i_cgsund       as nro_cgs, ";
      $sCamposGeral .= " z01_v_nome         as nome_cgs, ";
      $sCamposGeral .= " z01_v_ender        as rua_pac, ";
      $sCamposGeral .= " z01_i_numero       as nro_pac, ";
      $sCamposGeral .= " z01_v_compl        as complemento_end_pac, ";
      $sCamposGeral .= " z01_v_bairro       as bairro_pac, ";
      $sCamposGeral .= " z01_v_munic        as cidade_pac, ";
      $sCamposGeral .= " z01_v_cep          as cep_pac, ";
      $sCamposGeral .= " z01_v_mae          as pai_pac, ";
      $sCamposGeral .= " z01_v_pai          as mae_pac, ";
      $sCamposGeral .= " z01_v_uf           as uf_pac, ";
      $sCamposGeral .= " fc_formatadata(z01_d_nasc) as data_nasc_pac, ";
      $sCamposGeral .= " fc_idade(z01_d_nasc,'$dHoje') as idade_pac, ";
      $sCamposGeral .= " case when z01_v_sexo = 'M' then 'MASCULINO' when z01_v_sexo = 'F' then 'FEMININO' ";
      $sCamposGeral .= " else z01_v_sexo end as sexo_pac, ";

      $sSubSql       = " from prontproced ";
      $sSubSql      .= "  inner join especmedico    as sa on sa.sd27_i_codigo   = sd29_i_profissional";
      $sSubSql      .= "  inner join rhcbo          as sb on sb.rh70_sequencial = sa.sd27_i_rhcbo ";
      $sSubSql      .= "  inner join unidademedicos as sc on sc.sd04_i_codigo   = sa.sd27_i_undmed ";
      $sSubSql      .= "  inner join medicos        as sd on sd.sd03_i_codigo   = sc.sd04_i_medico ";
      $sSubSql      .= "  inner join cgm            as se on se.z01_numcgm      = sd.sd03_i_cgm ";
      $sSubSql      .= "  where sd29_i_prontuario = sd24_i_codigo limit 1) ";

      $sCamposGeral .= " case when sd03_i_codigo is null then ( select sd.sd03_i_codigo $sSubSql else sd03_i_codigo end as cod_medico, ";
      $sCamposGeral .= " case when cgm_med.z01_nome is null then ( select se.z01_nome $sSubSql else cgm_med.z01_nome end as nome_medico, ";
      $sCamposGeral .= " case when sd03_i_crm is null then ( select sd.sd03_i_crm $sSubSql else sd03_i_codigo end as crm, ";
      $sCamposGeral .= " case when s144_c_descr <> '' then s144_c_descr else sd24_v_motivo end as motivo_atend, ";
      $sCamposGeral .= " sd24_t_diagnostico as diagnostico,";
      $sCamposGeral .= " sd24_i_tipo        as tipo_atend,";
      $sCamposGeral .= " fc_formatadata(sd23_d_consulta) as data_atend, ";
      $sCamposGeral .= " sd23_c_hora        as hora_atend, ";
      $sCamposGeral .= " (select sd63_c_procedimento $sSubProced) as faa_proc_cod, ";
      $sCamposGeral .= " (select sd63_c_nome $sSubProced)         as faa_proc_nome ";

    }
    for ($iInd = 0; $iInd < $iTam; $iInd++) {

      $sSql   = $clprontuarios->sql_query_faa($aChaveProntuarios[$iInd], $sCamposGeral);
      $rs     = $clprontuarios->sql_record($sSql);
      if ($clprontuarios->numrows == 0) {

        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode("Nenhum registro encontrado para o relatório.");
        echo $oJson->encode($oRetorno);
        exit;

      }
      $oGeral = db_utils::getCollectionByRecord($rs);
      $aDados = array($oGeral);

     if ($iModelo == 6) {

        $sSqlProc = $clprontproced->sql_query_faa("",
                                                 $sCamposProc,
                                                 "",
                                                 " sd29_i_prontuario = ".$aChaveProntuarios[$iInd]
                                                );
        $rsProc             = $clprontuarios->sql_record($sSqlProc);
        $oProcedimentos = db_utils::getCollectionByRecord($rsProc);
        $aDados[ ]      = $oProcedimentos;

      }
      if ($iModelo == 7) {

        $sCampos     = "sd63_c_procedimento         as proc_estrutural,";
        $sCampos    .= "sd63_c_nome                 as proc_nome,";
        $sCampos    .= "fc_formatadata(sd29_d_data) as proc_data,";
        $sCampos    .= "sd29_t_tratamento           as proc_tratamento,";
        $sCampos    .= "sd70_c_cid                  as cid_cod,";
        $sCampos    .= "sd70_c_nome                 as cid_nome";
        $sSqlProc    = $clprontproced->sql_query_ext(null,
                                                       $sCampos,
                                                       " sd29_d_data desc ",
                                                       " sd24_i_numcgs = ".$oGeral[0]->nro_cgs." and ".
                                                       " substr( sd63_c_procedimento, 1, 2 ) = '02' and ".
                                                       "sd29_i_prontuario != ".$aChaveProntuarios[$iInd]
                                                      );

        $rs          = $clprontproced->sql_record($sSqlProc);
        $iLinhasProc = $clprontproced->numrows;
        if ($iLinhasProc > 0) {
          $aDados[ ] = db_utils::getCollectionByRecord($rs);
        }else{
          $aDados[ ] = array();
        }

              $sSqlConsultas   = $clprontproced->sql_query_ext(null,
                                                           " sd63_c_procedimento        as proced_proc, ".
                                                           " sd63_c_nome                as proced_nome, ".
                                                           " m.z01_nome                 as proced_prof,".
                                                           " fc_formatadata(sd29_d_data) as proced_data",
                                                           " sd29_d_data desc limit 11",
                                                           " sd24_i_numcgs = ".$oGeral[0]->nro_cgs." and ".
                                                           " substr( sd63_c_procedimento, 1, 2 ) = '03' and ".
                                                           " sd29_i_prontuario != ".$aChaveProntuarios[$iInd]
                                                          );
        $rsProntprocedConsultas = $clprontproced->sql_record ($sSqlConsultas);
        $iLinhasConsultas = $clprontproced->numrows;
        if ($iLinhasConsultas > 0) {
          $aDados[ ] = db_utils::getCollectionByRecord($rsProntprocedConsultas);
        } else {
          $aDados[ ] = array();
        }

      }
      try {

        $oGerador->setDados($aDados);
        $oGerador->gerarArquivo();
        $aArquivos[]  = TiraAcento($oGerador->getArquivo(), false);

      } catch (Exception $oExcecao) {

        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
        echo $oJson->encode($oRetorno);
        exit;

      }

    }

    $sSessionNome = "FAA_".date("d-m-Y",db_getsession("DB_datausu"))."_".date("H:i:s");
    if (isset ($_SESSION [$sSessionNome])) {
      unset ($_SESSION [$sSessionNome]);
    }
    $_SESSION[$sSessionNome] = $aArquivos;
    $oRetorno->sSessionNome  = $sSessionNome;

  } else {

    $oRetorno->iTipo             = 1;
    $oRetorno->sArquivo          = modeloFaa($oSauConfig->s103_i_modelofaa);
    $oRetorno->sChaveProntuarios = $sChaveProntuarios;

  }

} elseif ($oParam->exec == 'getArquivoTXT') {
  if (isset ($_SESSION [$oParam->sSessionNome])) {

    $oRetorno->aArquivo = $_SESSION [$oParam->sSessionNome];

    //Lista de impressoras
    $oCfauntent       = db_utils::getdao('cfautent');
    $sCampos          = "k11_id, k11_ipimpcheque, k11_local";
    $sSql             = $oCfauntent->sql_query(null,$sCampos);
    $rs               = $oCfauntent->sql_record($sSql);
    $iTam             = $oCfauntent->numrows;
    $aImpressoraId    = array();
    $aImpressoraDescr = array();
    $iIpPadrao        = 0;
    for ($iInd = 0; $iInd < $iTam; $iInd++) {

      $oImpressora         = db_utils::fieldsmemory($rs, $iInd);
      $aImpressoraId[ ]    = $oImpressora->k11_id;
      $aImpressoraDescr[ ] = $oImpressora->k11_ipimpcheque.' - '.$oImpressora->k11_local;
      //verifica impressora padrão
      $iIp = $_SERVER['REMOTE_ADDR'];
      if ($iIp == $oImpressora->k11_ipimpcheque) {
        $iIpPadrao = $oImpressora->k11_id;
      }

    }
    $oRetorno->iIpPadrao        = $iIpPadrao;
    $oRetorno->aImpressoraId    = $aImpressoraId;
    $oRetorno->aImpressoraDescr = $aImpressoraDescr;

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));

  }
} elseif ($oParam->exec == 'imprimeArquivoTXT') {

  include(modification("model/impressaoTXT.model.php"));

  $iTam = count($_SESSION [$oParam->sSessionNome]);
  $sStr = '';
  for ($i=0; $i < $iTam; $i++) {
    $sStr .= $_SESSION [$oParam->sSessionNome][$i];
  }

  //selecionar o IP e a porta da impressoara padrão do sistema
  $oCfauntent  = db_utils::getdao('cfautent');
  $sCampos     = "k11_ipimpcheque, k11_portaimpcheque";
  $sWhere      = " k11_id = ".$oParam->idImpressora;
  $sSql        = $oCfauntent->sql_query(null,$sCampos,null,$sWhere);
  $rs          = $oCfauntent->sql_record($sSql);
  $oImpressora = db_utils::fieldsmemory($rs,0);
  $sIp          = $oImpressora->k11_ipimpcheque;
  $sPorta      = $oImpressora->k11_portaimpcheque;

  try{

    $oImpressao = new impressaoTXT($sIp, $sPorta);
    $oImpressao->imprimir($sStr);

  } catch (Exception $oExcecao) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
    echo $oJson->encode($oRetorno);
    exit;

  }
  $oRetorno->sMessage = urlencode("Arquivo enviado para impressão!");
} elseif ($oParam->exec == 'salvarArquivoTXT') {

  $iTam = count($_SESSION [$oParam->sSessionNome]);
  $sStr = '';
  for ($i=0; $i < $iTam; $i++) {
    $sStr .= $_SESSION [$oParam->sSessionNome][$i];
  }

  $sNomeArquivo = 'tmp/'.$oParam->sSessionNome.'.txt';
  $pArquivoDestino = fopen($sNomeArquivo, 'w+');
  if (!$pArquivoDestino) {

    $oRetorno->iStatus  = 2;
    $sErro = "Não foi possível salvar o arquivo em '$sNomeArquivo'. Verique se o caminho está correto.";
    $oRetorno->sMessage = urlencode(str_replace('"', '\"', sErro));
    echo $oJson->encode($oRetorno);
    exit;

  }

  $lRetorno = fwrite($pArquivoDestino, $sStr);
  fclose($pArquivoDestino);
  if ($lRetorno === false) {

    $oRetorno->iStatus  = 2;
    $sErro = "Não foi possível salvar o arquivo em '$sNomeArquivo'. Erro ao escrever no arquivo.";
    $oRetorno->sMessage = urlencode(str_replace('"', '\"', $sErro));

  } else {
    $oRetorno->sNomeArquivo = $sNomeArquivo;

  }

} elseif ($oParam->exec == 'gerarComprovanteTXT') {

  require_once(modification('model/DBProcessaTemplateTXT.model.php'));
  $dHoje     = date("Y-m-d",db_getsession("DB_datausu"));
  $iInstitui = db_getsession("DB_instit");
  $sModelo   = "documentos/templates/txt/sau_modelo_comprovante_agendamento.txt";
  try {
    $oGerador = new DBProcessaTemplateTXT($sModelo);
  } catch (Exception $oExcecao) {

    echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
    exit;

  }
  $oAgendamentos = db_utils::getdao('agendamentos');
  $ad23_i_codigo = explode(",",$oParam->sd23_i_codigo);
  $iTam          = count($ad23_i_codigo);
  $aArquivos     = array();
  $sCampos  = " (select munic from db_config where codigo = $iInstitui) as municipio, ";
  $sCampos .= " sd23_i_codigo      as nro_agendamento, ";
  $sCampos .= " fc_formatadata(sd23_d_agendamento) as data_agendamento, ";
  $sCampos .= " fc_formatadata(sd23_d_consulta) as data_atendimento, ";
  $sCampos .= " sd23_c_hora        as hora, ";
  $sCampos .= " ed32_c_descr       as dia_semana, ";
  $sCampos .= " sd23_i_turno       as turno, ";
  $sCampos .= " sd02_i_codigo      as cod_ups, ";
  $sCampos .= " descrdepto         as nome_ups, ";
  $sCampos .= " cgm_und.z01_ender  as rua_rua, ";
  $sCampos .= " cgm_und.z01_compl  as complemanto_end_ups, ";
  $sCampos .= " cgm_und.z01_numero as nro_end_ups, ";
  $sCampos .= " cgm_und.z01_bairro as bairro_ups, ";
  $sCampos .= " cgm_und.z01_cep    as cep_ups, ";
  $sCampos .= " sd02_c_siasus      as sia_sus, ";
  $sCampos .= " nome               as login, ";
  $sCampos .= " z01_i_cgsund       as nro_cgs, ";
  $sCampos .= " z01_v_nome         as nome_cgs, ";
  $sCampos .= " z01_v_ender        as rua_pac, ";
  $sCampos .= " z01_i_numero       as nro_pac, ";
  $sCampos .= " z01_v_compl        as complemento_end_pac, ";
  $sCampos .= " z01_v_bairro       as bairro_pac, ";
  $sCampos .= " z01_v_munic        as cidade_pac, ";
  $sCampos .= " z01_v_cep          as cep_pac, ";
  $sCampos .= " fc_formatadata(z01_d_nasc) as data_nasc_pac, ";
  $sCampos .= " fc_idade(z01_d_nasc,'$dHoje') as idade_pac, ";
  $sCampos .= " case when z01_v_sexo = 'M' then 'MASCULINO' when z01_v_sexo = 'F' then 'FEMININO' else z01_v_sexo end as sexo_pac, ";
  $sCampos .= " sd03_i_codigo      as cod_medico, ";
  $sCampos .= " cgm_med.z01_nome   as nome_medico, ";
  $sCampos .= " sd03_i_crm         as crm ";

  for ($iInd = 0; $iInd < $iTam; $iInd++) {

    $sSql      = $oAgendamentos->sql_query_comprovante($ad23_i_codigo[$iInd], $sCampos);
    $rs        = $oAgendamentos->sql_record($sSql);
    $aDados    = array();
    $aDados[0] = db_utils::getCollectionByRecord($rs);
    if ($oAgendamentos->numrows < 0) {

      $oRetorno->iStatus  = 2;
      $sErro = "Nenhum registro para o relatório.";
      $oRetorno->sMessage = urlencode(str_replace('"', '\"', sErro));
      echo $oJson->encode($oRetorno);
      exit;

    }

    try {

      $oGerador->setDados($aDados);
      $oGerador->gerarArquivo();
      $aArquivos[] = TiraAcento($oGerador->getArquivo(), false);

    } catch (Exception $oExcecao) {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
      echo $oJson->encode($oRetorno);
      exit;

    }
  }
  $sSessionNome = "COMPTXT_".$oParam->sd23_i_codigo;
  if (isset ($_SESSION [$sSessionNome])) {
    unset ($_SESSION [$sSessionNome]);
  }
  $_SESSION[$sSessionNome] = $aArquivos;
  $oRetorno->sSessionNome  = $sSessionNome;

}

echo $oJson->encode($oRetorno);