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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification('model/agendaPagamento.model.php'));
require_once(modification("model/impressaoCheque.model.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetono = new stdClass();
switch($oParam->exec) {

  case "getOrdens" :

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sWhere     = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
    $sWhere    .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
    $sWhere    .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    $sWhere    .= " and e97_codforma = 2";
    $sWhere    .= " and k12_data is null";
    $sWhere    .= " and e81_cancelado is null";
    $sWhere    .= " and e60_instit = ".db_getsession("DB_instit");
    $sWhereSlip = "k17_situacao in(1,3) and e97_codforma = 2 and e81_cancelado is null";

    /* [Extensão] Filtro da Despesa */

    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere     .= " and e50_codord = {$oParam->params[0]->iOrdemIni}";

    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {

      $sWhere     .= " and e50_codord between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";

    }

    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {

      $sWhere     .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
      $sWhereSlip .= " and k17_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";

    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataIni   = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere     .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";
      $sWhereSlip .= " and k17_data between '{$dtDataIni}' and '{$dtDataFim}'";

    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

       $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
       $sWhere     .= " and e50_data <= '{$dtDataFim}'";
       $sWhereSlip .= " and k17_data <= '{$dtDataFim}'";
    }

    //Filtro para Empenho
    if ($oParam->params[0]->iCodEmp!= '') {

      if (strpos($oParam->params[0]->iCodEmp,"/")) {

        $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
        $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

      } else {
        $sWhere .= " and e60_codemp = '{$oParam->params[0]->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
      }

    }

    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {

      $sWhere     .= " and (e60_numcgm = {$oParam->params[0]->iNumCgm})";
      $sWhereSlip .= " and (k17_numcgm = {$oParam->params[0]->iNumCgm})";

    }
    if ($oParam->params[0]->iCodigoSlip != "" && $oParam->params[0]->iCodigoSlipFim == "") {
       $sWhereSlip .= " and s.k17_codigo = {$oParam->params[0]->iCodigoSlip} ";
    } else if ($oParam->params[0]->iCodigoSlip != "" && $oParam->params[0]->iCodigoSlipFim != "") {
       $sWhereSlip .= " and s.k17_codigo between {$oParam->params[0]->iCodigoSlip}  and {$oParam->params[0]->iCodigoSlipFim}";
    } else if ($oParam->params[0]->iCodigoSlip == "" && $oParam->params[0]->iCodigoSlipFim != "") {
       $sWhereSlip .= " and s.k17_codigo < {$oParam->params[0]->iCodigoSlipFim}";
    }

    if ($oParam->params[0]->iOPauxiliar != "") {

    $sWhere .= " and e42_sequencial = {$oParam->params[0]->iOPauxiliar}";
    }
    if ($oParam->params[0]->sDtAut != "") {

      $sDtAut   = implode("-", array_reverse(explode("/", $oParam->params[0]->sDtAut)));
      $sWhere .= " and e42_dtpagamento = '{$sDtAut}'";

    }

   if ($oParam->params[0]->iRecurso != "") {

      $sWhere     .= " and o15_codigo = {$oParam->params[0]->iRecurso}";
      $sWhereSlip .= " and ctapag.c61_codigo = {$oParam->params[0]->iRecurso}";

    }
    if ($oParam->params[0]->iTipoConsulta == 1) {
      $sWhereSlip = "false";
    } else if ($oParam->params[0]->iTipoConsulta == 2) {
      $sWhere= "false";
    }
    if ($oParam->params[0]->iCodigoConta != 0) {

      $sWhereSlip .= " and e85_codtipo = {$oParam->params[0]->iCodigoConta}";
      $sWhere     .= " and e85_codtipo = {$oParam->params[0]->iCodigoConta}";

    }
    $sJoin  = " left join empagenotasordem on e81_codmov  = e43_empagemov     ";
    $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
    //$aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,false,true);
    $aOrdensAgenda = $oAgenda->getMovimentosCheque($sWhere, $sWhereSlip, $sJoin);
    echo pg_last_error();
    if (count($aOrdensAgenda) > 0) {

      $oRetono->status           = 1;
      $oRetono->mensagem         = 1;
      $oRetono->aNotasLiquidacao = $aOrdensAgenda;
      echo $oJson->encode($oRetono);

    } else {

      $oRetono->status           = 2;
      $oRetono->mensagem         = "";
      echo $oJson->encode($oRetono);

    }
    break;

  case "getVersoCheque":

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sVersoCheque = $oAgenda->getVersoCheque($oParam->params[0]->aMovimentos);
    echo urldecode($sVersoCheque);
    break;

  case "getSaldos":

    $oAgenda        = new agendaPagamento();
    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
    $oAgenda->setUrlEncode(true);
    $oRetorno                = new stdClass;
    $oRetorno->status        = 1;
    $oRetorno->oSaldoTes     = $oAgenda->getSaldoConta($oParam->params[0]->iCodTipo, $oParam->params[0]->dtBase);
    echo pg_last_error();
    $rsConta                 = $oDaoEmpAgeTipo->sql_record($oDaoEmpAgeTipo->sql_query_file($oParam->params[0]->iCodTipo));
    $oRetorno->iCheque       = $oDaoEmpAgeTipo->getMaxCheque(db_utils::fieldsMemory($rsConta,0)->e83_conta);
    echo $oJson->encode($oRetorno);
    break;

  case "emitirCheque" :

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    try {

      db_inicio_transacao();
      $aCheques = $oAgenda->emiteCheque($oParam->params[0]->aNotasLiquidacao,
                            $oParam->params[0]->dtData,
                            $oParam->params[0]->sCredor,
                            $oParam->params[0]->aTotCheques
                           );

      //echo ("<pre>".print_r($aCheques, 1)."</pre>"); die();

      db_fim_transacao(false);
      $sMessage     = "cheques emitidos com sucesso";
      $iStatus      = 1;
      $aInfoCheques = $aCheques;
    }
    catch (Exception $eErro){

      db_fim_transacao(true);
      $sMessage     = $eErro->getMessage();
      $iStatus      = 2;
      $aInfoCheques = null;
    }

    echo $oJson->encode(array("status" => $iStatus, "message"=>urlencode($sMessage), "aInfoCheques"=> $aInfoCheques));
    break;

  case "imprimirFrenteCheque" :

    try {

      $oDaoCfAutent       = db_utils::getDao("cfautent");
      $sSqlTipoImpressora = $oDaoCfAutent->sql_query_file(null,"k11_tipoimpcheque,
                                                               k11_portaimpcheque,
                                                               k11_tipautent,
                                                               k11_tesoureiro as tesoureiro",
                                                               "",
                                                               "k11_ipterm='".db_getsession("DB_ip")."'
                                                               and k11_instit=".db_getsession("DB_instit"));
      $rsTipoImpressora = $oDaoCfAutent->sql_record($sSqlTipoImpressora);
      if ($oDaoCfAutent->numrows == 0) {
        throw new Exception("Não há informações sobre impressoras cadastradas!");
      }
      $oDadosImpressora = db_utils::fieldsMemory($rsTipoImpressora, 0);
      $oDaoEmpAgeTipo   = db_utils::getDao("empagetipo");

      if ($oDadosImpressora->k11_tipautent == "2") {

        echo $oJson->encode(array("status"=> 1 ,"message"=> null));
        exit;
      }
      $sSqlCodigoBanco  = $oDaoEmpAgeTipo->sql_query_conplanoconta($oParam->params[0]->iCodTipo);
      $rsCodigoBanco    = $oDaoEmpAgeTipo->sql_record($sSqlCodigoBanco);
      $iCodigoBanco     = db_utils::fieldsMemory($rsCodigoBanco, 0)->c63_banco;

      $oDaoConfig       = db_utils::getDao("db_config");
      $rsPref           = $oDaoConfig->sql_record($oDaoConfig->sql_query_file(db_getsession("DB_instit"),
                                                                             "pref as prefeito,munic as municipio"));
      $oPref            = db_utils::fieldsMemory($rsPref, 0);
      $oImpressaoCheque = new impressaoCheque($oDadosImpressora->k11_tipoimpcheque);
      $oImpressaoCheque->setIp(db_getsession("DB_ip"));
      $oImpressaoCheque->setPorta($oDadosImpressora->k11_portaimpcheque);
      $oImpressaoCheque->setdtDataImpressao($oParam->params[0]->dtData);
      $oImpressaoCheque->setnValor($oParam->params[0]->nValor);
      $oImpressaoCheque->setSMunicipio($oPref->municipio);
      $oImpressaoCheque->setsCodBanco($iCodigoBanco);
      $oImpressaoCheque->setNomePrefeito($oPref->prefeito);
      $oImpressaoCheque->setNomeTesoureiro($oDadosImpressora->tesoureiro);
      $oImpressaoCheque->setSCredor(urldecode($oParam->params[0]->sCredor));
      $oImpressaoCheque->montaImpressao();
      $oImpressaoCheque->imprimir();
      echo $oJson->encode(array("status"=> 1 ,"message"=> null));

    } catch (Exception $eErro) {
      echo $oJson->encode(array("status"=>2,"message"=> urlencode($eErro->getMessage())));
    }
    break;

  case "emitirVersoCheque" :

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    //echo $oParam->params[0]->sStringVerso;
    $sStringVerso       = str_replace('/n',"\n",urlDecode($oParam->params[0]->sStringVerso));
    $oDaoConfig         = db_utils::getDao("db_config");
    $rsPref             = $oDaoConfig->sql_record($oDaoConfig->sql_query_file(db_getsession("DB_instit"),
                                                                             "pref as prefeito,munic as municipio"));
    $oPref              = db_utils::fieldsMemory($rsPref, 0);
    $oDaoCfAutent       = db_utils::getDao("cfautent");
    $sSqlTipoImpressora = $oDaoCfAutent->sql_query_file(null, "k11_tipoimpcheque,
                                                             k11_portaimpcheque,
                                                             k11_tesoureiro as tesoureiro",
                                                             "",
                                                             "k11_ipterm='".db_getsession("DB_ip")."'
                                                             and k11_instit=".db_getsession("DB_instit"));
    $rsTipoImpressora = $oDaoCfAutent->sql_record($sSqlTipoImpressora);

    if ($oDaoCfAutent->numrows == 0) {
      throw new Exception("Não há informações sobre impressoras cadastradas!");
    }

    $oDadosImpressora = db_utils::fieldsMemory($rsTipoImpressora, 0);
    try {

      $sListaOps        = "";
      $iOPAtual         = null;


      if ($oParam->params[0]->lImprimirComplemento) {

        $oDaoEmpAgeTipo   = db_utils::getDao("empagetipo");
        $sSqlCodigoBanco  = $oDaoEmpAgeTipo->sql_query_conplanoconta($oParam->params[0]->aMovimentos[0]->iCodTipo);
        $rsCodigoBanco    = $oDaoEmpAgeTipo->sql_record($sSqlCodigoBanco);
        $oBanco           = db_utils::fieldsMemory($rsCodigoBanco, 0);
        $sListaOps        = " OP:";
        $sVirgula         = "";

        foreach ($oParam->params[0]->aMovimentos as $oMovimento) {

          if ($oMovimento->iCodOrdem != $iOPAtual) {

            if ( isset($oMovimento->iCodOrdem) && trim($oMovimento->iCodOrdem) != '' )  {

              if ($sVirgula == "") {
                $sListaOps .= $oMovimento->iCodOrdem;
                $sVirgula   = ", ";
              } else {
                $sListaOps .= $sVirgula.$oMovimento->iCodOrdem;
              }
            }
          }
        }
//        "ENDOSSO"

        $sConta   = $oBanco->c63_conta;
        if (trim($oBanco->c63_dvconta) != "") {
          $sConta .= "-{$oBanco->c63_dvconta}";
        }
        $sStringVerso .= "\n\n Cheque: {$oParam->params[0]->iCheque} ";
        $sStringVerso .= " Banco: {$oBanco->c63_banco} cta:{$sConta}";
        $sStringVerso .= " Reduz {$oBanco->c61_reduz} - {$sListaOps}";

      }

      $oImpressao = new impressao();
      $oImpressao->setIp(db_getsession("DB_ip"));
      $sStringVerso  = str_replace("\r","",$sStringVerso);

      if ($oDadosImpressora->k11_tipoimpcheque == 5) {

        $aImpressaoParts = explode("\n", $sStringVerso);
        if (strtoupper($oPref->municipio) != "SAPIRANGA") {
          $sStringVerso  =  chr(27).chr(119).'1';
        }
        for($i =0; $i< count($aImpressaoParts); $i++){

          if(trim($aImpressaoParts[$i]) != "") {

            $sStringVerso .= "";
            $sStringVerso .= "       ".trim($aImpressaoParts[$i]).chr(10).chr(13);
            $sStringVerso .= chr(10).chr(13);

          }
        }
        if (strtoupper($oPref->municipio ) != "SAPIRANGA") {
          $sStringVerso  .=  chr(27).chr(119).'0';
        }
        //echo $sStringVerso;
      } else if ( $oDadosImpressora->k11_tipoimpcheque == 8 ) {

        $sStringVerso  = "";

        $aImpressaoParts = explode("\n", $sStringVerso);

        for($i =0; $i< count($aImpressaoParts); $i++){
          if(trim($aImpressaoParts[$i]) != "") {

            $sStringVerso .= str_repeat(chr(0),10).trim($aImpressaoParts[$i]).chr(10);

          }
        }

        $sStringVerso .= str_repeat(chr(0),10).str_replace('/n',"\n",urlDecode($oParam->params[0]->sStringVerso)).chr(10);
        $sStringVerso .= str_repeat(chr(0),10)." Cheque: {$oParam->params[0]->iCheque} "                         .chr(10);
        $sStringVerso .= str_repeat(chr(0),10)." Banco: {$oBanco->c63_banco} cta:{$sConta}"                      .chr(10);
        $sStringVerso .= str_repeat(chr(0),10)." Reduz {$oBanco->c61_reduz} - {$sListaOps} "                     .chr(10);
        $sStringVerso .= chr(12);

      } else {
        $sStringVerso .= "\n";
      }
      $oImpressao->setPorta($oDadosImpressora->k11_portaimpcheque);
      $oImpressao->imprimir($sStringVerso);
      echo $oJson->encode(array("status"=>1,"message"=>""));


    }

    catch (Exception $eErro) {
      echo $oJson->encode(array("status"=>2,"message"=> urlencode($eErro->getMessage())));
    }
    break;

  case "cancelarCheques":

    $oRetorno  = new stdClass;
    $oRetorno->status  = 1;
    $oRetorno->message = "";
    $oAgenda = new agendaPagamento();
    try {


      //echo ("<pre>".print_r($oParam->aCheques, 1)."</pre>"); die();

      db_inicio_transacao();
      foreach ($oParam->aCheques as $oCheque) {
        $oAgenda->cancelarCheque($oCheque->iCodMov);
      }
      db_fim_transacao(false);

    }

    catch (Exception $eErro){

      $oRetorno->status  = 2;
      $oRetorno->message = $eErro->getMessage();
      db_fim_transacao(true);
    }

    echo $oJson->encode($oRetorno);
    break;
}
?>