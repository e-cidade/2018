<?php
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

define( 'MSG_MANUTENCAOFAARESUMIDA', 'saude.ambulatorial.sau4_manutencaofaaresumidaRPC.' );

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMensagem     = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "excluirFaaLote":

      if ( empty($oParam->iCodigoLote) ) {
        throw new Exception( _M(MSG_MANUTENCAOFAARESUMIDA . "lote_nao_informado") );
      }
      if ( empty($oParam->iCodigoLoteFaa) ) {
        throw new Exception( _M(MSG_MANUTENCAOFAARESUMIDA . "faa_nao_informado") );
      }

      $oDaoLotePront      = new cl_sau_lotepront();
      $oDaoProntProced    = new cl_prontproced();
      $oDaoProntProcedCid = new cl_prontprocedcid();
      $oDaoLote           = new cl_sau_lote();
      $oDaoFechPront      = new cl_sau_fechapront();
      $oDaoFechamento     = new cl_sau_fechamento();

      $sCampos  = "  sd59_i_codigo     ";
      $sCampos .= " ,sd59_i_prontuario ";
      $sCampos .= " ,array_to_string(array_accum(sd29_i_codigo), ',' ) as procedimentos          ";
      $sCampos .= " ,array_to_string(array_accum(s135_i_codigo), ',' ) as cid_procedimentos      ";
      $sCampos .= " ,array_to_string(array_accum(sd98_i_codigo), ',' ) as procediemntos_fechados ";
      $sCampos .= " ,sd98_i_fechamento ";

      $sWhere   = "     sd59_i_lote   = {$oParam->iCodigoLote}    ";
      $sWhere  .= " and sd59_i_codigo = {$oParam->iCodigoLoteFaa} ";
      $sGroup   = " group by sd59_i_codigo, sd59_i_prontuario, sd98_i_fechamento ";

      $sSqlDadosFAA = $oDaoLotePront->sql_query_dados_faa(null, $sCampos, null, $sWhere . $sGroup);
      $rsDadosFAA   = db_query($sSqlDadosFAA);

      $oMsgErro = new stdClass();
      if ( !$rsDadosFAA )  {

        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(MSG_MANUTENCAOFAARESUMIDA . "erro_buscar_dados_faa", $oMsgErro) );
      }

      if ( pg_num_rows($rsDadosFAA) == 0 ) {
        throw new Exception( _M(MSG_MANUTENCAOFAARESUMIDA . "lote_sem_faa" ) );
      }

      $iFechamento = null;
      $iProntuario = null;
      foreach (db_utils::getCollectionByRecord($rsDadosFAA) as $iIndice => $oDados) {

        $iFechamento = $oDados->sd98_i_fechamento;
        $iProntuario = $oDados->sd59_i_prontuario;

        if ( !empty($oDados->cid_procedimentos) ) {

          $sWhereCid = " s135_i_codigo in ( {$oDados->cid_procedimentos} ) ";
          $oDaoProntProcedCid->excluir(null, $sWhereCid);
          if ( $oDaoProntProcedCid->erro_status == 0 ) {

            $oMsgErro->sErro = $oDaoProntProcedCid->erro_sql;
            throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_cid", $oMsgErro ) );
          }
        }

        if ( !empty($oDados->procediemntos_fechados) ) {

          $sWhereProcedimentoFechado = " sd98_i_codigo in ({$oDados->procediemntos_fechados}) ";
          $oDaoFechPront->excluir(null, $sWhereProcedimentoFechado);
          if ( $oDaoFechPront->erro_status == 0 ) {

            $oMsgErro->sErro = $oDaoFechPront->erro_sql;
            throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_procedimento_fechado", $oMsgErro ) );
          }
        }

        if ( !empty($oDados->procedimentos ) ){

          $sWhereProntProced = " sd29_i_codigo in ({$oDados->procedimentos}) ";
          $oDaoProntProced->excluir(null, $sWhereProntProced);
          if ( $oDaoProntProced->erro_status == 0 ) {

            $oMsgErro->sErro = $oDaoProntProced->erro_sql;
            throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_procedimento", $oMsgErro ) );
          }
        }
      }

      /**
       * Valida se fechamento ainda possui procedimentos, se não houver exclui o fechamento
       */
      if ( !empty($iFechamento) ) {

        $sWhereFechamento = " sd98_i_fechamento = {$iFechamento} ";
        $sSqlFechamento   = $oDaoFechPront->sql_query_file(null, " 1 ", null, $sWhereFechamento );
        $rsFechamento     = db_query($sSqlFechamento);
        if ($rsFechamento && pg_num_rows($rsFechamento) == 0 ) {

          $oDaoFechamento->excluir($iFechamento);
          if ( $oDaoFechamento->erro_status == 0 ) {

            $oMsgErro->sErro = $oDaoFechamento->erro_sql;
            throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_fechamento", $oMsgErro ) );
          }
        }
      }

      $oDaoLotePront->excluir($oParam->iCodigoLoteFaa);
      if ( $oDaoLotePront->erro_status == 0 ) {

        $oMsgErro->sErro = $oDaoLotePront->erro_sql;
        throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_faa_lote", $oMsgErro ) );
      }

      $oRetorno->lLoteExcluido = false;
      /**
       * Valida exclusão do lote
       */
      $sWhereLotePront = " sd59_i_lote = {$oParam->iCodigoLote} ";
      $sSqlLotePront   = $oDaoLotePront->sql_query_file(null, " 1 ", null, $sWhereLotePront);
      $rsLotePront     = db_query($sSqlLotePront);
      if ($rsLotePront && pg_num_rows($rsLotePront) == 0) {

        $oDaoLote->excluir($oParam->iCodigoLote);
        if ( $oDaoLote->erro_status == 0 ) {

          $oMsgErro->sErro = $oDaoLote->erro_sql;
          throw new Exception(  _M(MSG_MANUTENCAOFAARESUMIDA . "erro_excluir_lote", $oMsgErro ) );
        }
        $oRetorno->lLoteExcluido = true;
      }

      $oRetorno->sMensagem = urlencode( _M(MSG_MANUTENCAOFAARESUMIDA . "exclusao_faa_realizada_com_sucesso", $oMsgErro ) );

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);