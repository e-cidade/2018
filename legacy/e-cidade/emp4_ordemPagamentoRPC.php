<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_empagenotasordem_classe.php");
require_once(Modification::getFile('model/agendaPagamento.model.php'));

$oDaoEmpAge = new cl_pagordem;
$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
if ($oParam->exec == "consultarNotas") {

  $oAgenda = new agendaPagamento();
  $oAgenda->setUrlEncode(true);
  $sWhere     = " (round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
  //$sWhere     = " (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0 ";
  $sWhere     .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
  $sWhere     .= " and k12_data is null";
  $sWhere     .= " and e97_codmov is null";
  $sWhere     .= " and e81_cancelado is null";
  $sWhere     .= " and e60_instit = ".db_getsession("DB_instit");
  $sWhereSlip  = " k17_situacao in(1,3)  and e81_cancelado is null";
  $sWhereSlip .= " and extract(year from k17_data ) = ".db_getsession("DB_anousu");

  if ($oParam->iOrdemIni != '' && $oParam->iOrdemFim == "") {
    $sWhere     .= " and e50_codord = {$oParam->iOrdemIni}";

  } else if ($oParam->iOrdemIni != '' && $oParam->iOrdemFim != "") {

    $sWhere     .= " and e50_codord between  {$oParam->iOrdemIni} and {$oParam->iOrdemFim}";

  }

  if ($oParam->dtDataIni != "" && $oParam->dtDataFim == "") {

    $sWhere     .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->dtDataIni)))."'";
    $sWhereSlip .= " and k17_data = '".implode("-",array_reverse(explode("/",$oParam->dtDataIni)))."'";

  } else if ($oParam->dtDataIni != "" && $oParam->dtDataFim != "") {

    $dtDataIni   = implode("-",array_reverse(explode("/",$oParam->dtDataIni)));
    $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->dtDataFim)));
    $sWhere     .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";
    $sWhereSlip .= " and k17_data between '{$dtDataIni}' and '{$dtDataFim}'";

  } else if ($oParam->dtDataIni == "" && $oParam->dtDataFim != "") {

    $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->dtDataFim)));
    $sWhere     .= " and e50_data <= '{$dtDataFim}'";
    $sWhereSlip .= " and k17_data <= '{$dtDataFim}'";
  }

  //Filtro para Empenho
  if ($oParam->iCodEmp!= '') {

    if (strpos($oParam->iCodEmp,"/")) {

      $aEmpenho = explode("/",$oParam->iCodEmp);
      $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

    } else {
      $sWhere .= " and e60_codemp = '{$oParam->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
    }

  }

  //filtro para filtrar por credor
  if ($oParam->iNumCgm != '') {

    $sWhere     .= " and (e60_numcgm = {$oParam->iNumCgm})";
    $sWhereSlip .= " and (k17_numcgm = {$oParam->iNumCgm})";

  }
  if ($oParam->iSlipInicial != "" && $oParam->iSlipFim == "") {
    $sWhereSlip .= " and s.k17_codigo = {$oParam->iSlipInicial} ";
  } else if ($oParam->iSlipInicial != "" && $oParam->iSlipFim != "") {
    $sWhereSlip .= " and s.k17_codigo between {$oParam->iSlipInicial}  and {$oParam->iSlipFim}";
  } else if ($oParam->iSlipInicial == "" && $oParam->iSlipFim != "") {
    $sWhereSlip .= " and s.k17_codigo < {$oParam->iSlipFim}";
  }

  if ($oParam->iOPauxiliar != "") {

    if ($oParam->iTipoRetorno == 1) {

      $sWhere     .= " and e42_sequencial = {$oParam->iOPauxiliar}";
      $sWhereSlip .= " and e42_sequencial = {$oParam->iOPauxiliar}";
    } else if ($oParam->iTipoRetorno == 2) {

      $sWhere     .=  " and e43_empagemov is null ";
      $sWhereSlip .=  " and e43_empagemov is null ";

    } else {

      $sWhere     .= " and (e42_sequencial = {$oParam->iOPauxiliar} or e43_empagemov is null )";
      $sWhereSlip .= " and (e42_sequencial = {$oParam->iOPauxiliar} or e43_empagemov is null )";

    }
  }

  if ($oParam->iRecurso != "") {

    $sWhere     .= " and o15_codigo = {$oParam->iRecurso}";
    $sWhereSlip .= " and ctapag.c61_codigo = {$oParam->iRecurso}";

  }

  /* [Extensão] Filtro da Despesa */

  if ($oParam->iTipoConsulta == 1) {
    $sWhereSlip = "false";
  } else if ($oParam->iTipoConsulta == 2) {
    $sWhere= "false";
  }

  $sJoin  = " left join empagenotasordem on e81_codmov  = e43_empagemov     ";
  $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
  //$aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,false,true);
  $oAgenda->setOrdemConsultas("e50_codord");
  $aOrdensAgenda = $oAgenda->getMovimentosCheque($sWhere, $sWhereSlip, $sJoin);
  if (count($aOrdensAgenda) > 0) {

    $oRetono->status   = 1;
    $oRetono->message  = 1;
    $oRetono->itens    = $aOrdensAgenda;
    echo $oJson->encode($oRetono);

  } else {

    $oRetono->status           = 2;
    $oRetono->mensagem         = "";
    echo $oJson->encode($oRetono);

  }

  if ($oDaoEmpAge->numrows > 0) {
    echo $oJson->encode(db_utils::getCollectionByRecord($rsNotas, false,false,true));
  } else {
    echo " ";
  }

} else if ($oParam->exec == "lancarOrdem") {

  //Incluimos a Ordem
  $lSqlErro          = false;
  $oRetorno          = new stdClass;
  $oRetorno->status  = 1;
  $oRetorno->message = "";
  $oRetorno->dtAutoriza  = $oParam->e42_dtpaga;
  $oAgendaPagamento  = new agendaPagamento();
  try {

    db_inicio_transacao();
    if ($oParam->e42_sequencial == "") {
      $oParam->e42_sequencial = null;
    }
    $iCodigoAutoriza      = $oAgendaPagamento->autorizarPagamento(
      $oParam->e42_dtpaga,
      $oParam->aNotas,
      $oParam->iTipoOperacao,
      $oParam->e42_sequencial
    );
    $oRetorno->iCodAgenda = $iCodigoAutoriza;
    db_fim_transacao(false);

  }
  catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->message = urlencode($eErro->getMessage());
    $oRetorno->status  = 2;

  }

  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "cancelarOrdem") {

  $lSqlErro          = false;
  $oRetorno          = new stdClass;
  $oRetorno->status  = 1;
  $oRetorno->message = "";
  $oRetorno->dtAutoriza  = $oParam->e42_dtpaga;
  $oRetorno->iCodAgenda  = $oParam->e42_sequencial;
  $oAgendaPagamento  = new agendaPagamento();
  db_inicio_transacao();
  try {

    foreach ($oParam->aNotas as $oNota) {

      if ($oParam->iTipoOperacao == 1) {
        $sWhere = "e82_codord = {$oNota->iCodNota}";
      } else {
        $sWhere = "e89_codigo = {$oNota->iCodNota}";
      }
      $sSqlMovimento = "select e81_codmov, e81_valor
                            from empagemov
                                 left join empord on e82_codmov   = e81_codmov
                                 left join empageslip on e89_codmov   = e81_codmov
                                 left join empagepag on e81_codmov = e85_codmov
                                 left join empagemovforma on e97_codmov = e81_codmov
                                 left join empagenotasordem on e43_empagemov = e81_codmov
                            where {$sWhere}
                              and e97_codmov is null
                              and e85_codmov is null
                              and e43_empagemov is NULL
                              and e81_cancelado is null
                               order by e81_codmov";
      $rsMovimento = db_query($sSqlMovimento);
      $iNovoMovimento = "";
      if (pg_num_rows($rsMovimento) > 0) {

        $oMovimento = db_utils::fieldsMemory($rsMovimento, 0);
        $iNovoMovimento               = $oMovimento->e81_codmov;
        $oDaoEmpAgeMov                = db_utils::getDao("empagemov");
        $oDaoEmpAgeMov->e81_valor     = $oMovimento->e81_valor + $oNota->nValor;
        $oDaoEmpAgeMov->e81_codmov    = $oMovimento->e81_codmov;
        $oDaoEmpAgeMov->alterar($oMovimento->e81_codmov);
        $iNovoMovimento = $oMovimento->e81_codmov;

      }

      $oNovoMovimento = new stdClass();
      $oDaoEmpageMov = db_utils::getDao("empagemov");
      $sSqlMovimento = $oDaoEmpageMov->sql_query_file($oNota->iCodMov);
      $rsMovimento   = $oDaoEmpageMov->sql_record($sSqlMovimento);
      $oMovimento    = db_utils::fieldsMemory($rsMovimento, 0);
      $oAgendaPagamento = new agendaPagamento();
      $oNovoMovimento->iCodTipo = null;
      $oNovoMovimento->iNumEmp  = $oMovimento->e81_numemp;
      $oNovoMovimento->nValor   = $oNota->nValor;
      if ($oParam->iTipoOperacao == 1) {
        $oNovoMovimento->iCodNota = $oNota->iCodNota;
      } else {
        $oNovoMovimento->iCodigoSlip = $oNota->iCodNota;
      }
      $iNovoMovimento     = $oAgendaPagamento->addMovimentoAgenda($oParam->iTipoOperacao, $oNovoMovimento);


      $oDaoOP  = db_utils::getDao("empagenotasordem");
      $oDaoOP->excluir(null,"e43_empagemov = {$oNota->iCodMov}");
      $oDaoEmpAgeMov                = db_utils::getDao("empagemov");

      /*
       * T . 43939
       * Vinculação das retenções com os movimentos
       *
       * Primeiro seleciona os registros com o mesmo codigo do movimento
       * para cada registro retornado , atualiza o campo e27_empagemov, com o novo codigo;
       *
       */
      $oDaoRetencaoempagemov        = db_utils::getDao("retencaoempagemov");
      $sSqlRetencaoempagemov        = $oDaoRetencaoempagemov->sql_query_file("", "*", "", "e27_empagemov= {$oNota->iCodMov}");
      $rsRetencaoempagemov          = $oDaoRetencaoempagemov->sql_record($sSqlRetencaoempagemov);
      $iTotalLinhaRetencaoempagemov = pg_num_rows($rsRetencaoempagemov);
      for($iLinhaRetencaoempagemov  = 0; $iLinhaRetencaoempagemov < $iTotalLinhaRetencaoempagemov; $iLinhaRetencaoempagemov++) {

        $oResultRetencaoempagemov = db_utils::fieldsMemory($rsRetencaoempagemov, $iLinhaRetencaoempagemov);
        $e27_sequencial                        = $oResultRetencaoempagemov->e27_sequencial;
        $oDaoRetencaoempagemov->e27_sequencial = $e27_sequencial;
        $oDaoRetencaoempagemov->e27_empagemov  = $iNovoMovimento;

        $oDaoRetencaoempagemov->alterar($e27_sequencial);
        if ($oDaoRetencaoempagemov->erro_status == 0) {

          $sqlerro   = true;
          $erro_msg .= $oDaoRetencaoempagemov->erro_msg;
        }
      }

      $oDaoEmpAgeMov->e81_cancelado = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoEmpAgeMov->e81_codmov    = $oNota->iCodMov;
      $oDaoEmpAgeMov->alterar($oNota->iCodMov);

    }
    db_fim_transacao(false);
  } catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->message = urlencode($eErro->getMessage());
    $oRetorno->status  = 2;
  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "incluirOP") {

  $oRetorno          = new stdClass;
  $oRetorno->status  = 1;
  $oRetorno->message = "";
  /*
   * incluimos uma nova op auxiliar
   */
  $oDaoOPAuxiliar =  db_utils::getDao("empageordem");
  $oDaoOPAuxiliar->e42_dtpagamento = implode("-", array_reverse(explode("/", $oParam->e42_dtpagamento)));
  $oDaoOPAuxiliar->incluir(null);
  if ($oDaoOPAuxiliar->erro_status == "0") {

    $oRetorno->status  = 2;
    $oRetorno->message =  urlencode(str_replace("\\n","\n", $oDaoOPAuxiliar->erro_msg));

  } else {
    $oRetorno->iCodigoOPaxiliar = $oDaoOPAuxiliar->e42_sequencial;
  }
  //echo pg_last_error();
  //Não deu erro
  if($oRetorno->status  != 2 && isset($oParam->z01_numcgm) && trim($oParam->z01_numcgm) != ''){

    $oDaoEmpAgeOrdemAuxiliar =  db_utils::getDao("empageordemcgm");
    $oDaoEmpAgeOrdemAuxiliar->e94_empageordem = $oRetorno->iCodigoOPaxiliar;
    $oDaoEmpAgeOrdemAuxiliar->e94_numcgm      = $oParam->z01_numcgm;
    $sHistorico = db_stdClass::db_stripTagsJson(utf8_decode($oParam->historico));
    $oDaoEmpAgeOrdemAuxiliar->e94_historico   = addslashes($sHistorico);
    $oDaoEmpAgeOrdemAuxiliar->incluir(null);
    if ($oDaoEmpAgeOrdemAuxiliar->erro_status == "0") {

      $oRetorno->status  = 2;
      $oRetorno->message =  urlencode(str_replace("\\n","\n", $oDaoEmpAgeOrdemAuxiliar->erro_msg));

    }

  }

  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "alterarData") {

  $oRetorno          = new stdClass;
  $oRetorno->status  = 1;
  $oRetorno->message = "";
  /**
   * Alteramos a data
   */
  $oDaoOPAuxiliar =  db_utils::getDao("empageordem");
  $oDaoOPAuxiliar->e42_dtpagamento = implode("-", array_reverse(explode("/", $oParam->e42_dtpagamento)));
  $oDaoOPAuxiliar->e42_sequencial  = $oParam->e42_sequencial;
  $oDaoOPAuxiliar->alterar($oParam->e42_sequencial);
  if ($oDaoOPAuxiliar->erro_status == "0") {

    $oRetorno->status  = 2;
    $oRetorno->message =  urlencode(str_replace("\\n","\n", $oDaoOPAuxiliar->erro_msg));

  } else {
    $oRetorno->iCodigoOPaxiliar = $oDaoOPAuxiliar->e42_sequencial;
  }

  if($oRetorno->status  != 2 && trim($oParam->historico) != ""){

    $oDaoEmpAgeOrdemAuxiliar =  db_utils::getDao("empageordemcgm");
    //$oDaoEmpAgeOrdemAuxiliar->e94_empageordem = $oRetorno->iCodigoOPaxiliar;
    //$oDaoEmpAgeOrdemAuxiliar->e94_numcgm      = $oParam->z01_numcgm;
    $sHistorico = db_stdClass::db_stripTagsJson(utf8_decode($oParam->historico));
    $oDaoEmpAgeOrdemAuxiliar->e94_historico   = addslashes($sHistorico);
    $oDaoEmpAgeOrdemAuxiliar->e94_sequencial  = $oParam->e94_sequencial;
    $oDaoEmpAgeOrdemAuxiliar->alterar($oParam->e94_sequencial);
    echo pg_last_error();
    if ($oDaoEmpAgeOrdemAuxiliar->erro_status == "0") {

      $oRetorno->status  = 2;
      $oRetorno->message =  urlencode(str_replace("\\n","\n", $oDaoEmpAgeOrdemAuxiliar->erro_msg));

    }

  }

  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "consOpAuxCredor") {

  $oDaoEmpAgeOrdemAuxiliar =  db_utils::getDao("empageordemcgm");
  $sSql = $oDaoEmpAgeOrdemAuxiliar->sql_query(null,'*',null," e94_empageordem = ".$oParam->e42_sequencial);
  $rsSql = $oDaoEmpAgeOrdemAuxiliar->sql_record($sSql);

  if ($oDaoEmpAgeOrdemAuxiliar->erro_status == "0") {
    $oRetorno->status  = 2;
    $oRetorno->message =  urlencode(str_replace("\\n","\n", $oDaoEmpAgeOrdemAuxiliar->erro_msg));
  }else{
    $oRetorno->dados =  db_utils::getCollectionByRecord($rsSql,false,false,true);
  }
  echo $oJson->encode($oRetorno);
}
?>