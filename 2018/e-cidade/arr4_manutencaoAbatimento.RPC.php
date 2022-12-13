<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once modification("libs/JSON.php");

$oDaoAbatimentoArreckey         = new cl_abatimentoarreckey();
$oDaoArreckey                   = new cl_arreckey();
$oDaoArrePaga                   = new cl_arrepaga();
$oDaoArreCad                    = new cl_arrecad();
$oDaoTabRec                     = new cl_tabrec();
$oDaoArreCant                   = new cl_arrecant();
$oDaoArreIdRet                  = new cl_arreidret();
$oDaoDisBanco                   = new cl_disbanco();
$oDaoDisRec                     = new cl_disrec();
$oDaoAbatimentoDisbanco         = new cl_abatimentodisbanco();
$oDaoAbatimentoRecibo           = new cl_abatimentorecibo();
$oDaoArrecantPgtoParcial        = new cl_arrecantpgtoparcial();
$oDaoAbatimentoRegraCompensacao = new cl_abatimentoregracompensacao();
$oDaoAbatimentoProtProcesso     = new cl_abatimentoprotprocesso();
$oDaoAbatimento                 = new cl_abatimento();
$oDaoRecibo                     = new cl_recibo();

$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno       = new stdClass;

/**
 * Verificamos se o abatimento possui registro de utilizacao (abatimentoutilizacao)
 * caso exista, nao poderá ser excluído
 * @todo - a rotina de devolução e compensação deve ser corrigida antes deste trecho ser removido
 */
if( $oParam->exec == 'exluirPagamentoParcial' || $oParam->exec == 'exluirCredito' ){

  $oDaoAbatimentoUtilizacao         = new cl_abatimentoutilizacao;
  $sSqlVerificaAbatimentoUtilizacao = $oDaoAbatimentoUtilizacao->sql_query_file( null, "*", null, "k157_abatimento = {$oParam->iAbatimento}" );
  $rsAbatimentoUtilizacao           = db_query( $sSqlVerificaAbatimentoUtilizacao );

  try {

    if( !$rsAbatimentoUtilizacao ){
      throw new Exception("Erro ao consultar utilização do abatimento");
    }

    if( pg_num_rows($rsAbatimentoUtilizacao) != 0 ){
      throw new Exception("Abatimento {$oParam->iAbatimento} não pode ser excluído pois já foi utilizado parcial ou integralmente.");
    }

  } catch (Exception $oErro) {

    $aRetorno["status"]  = 2;
    $aRetorno["message"] = urlencode($oErro->getMessage());
    echo $oJson->encode($aRetorno);
    exit;
  }
}

switch ($oParam->exec) {

  case "getOrigensAbatimento":

    $sSqlBuscaOrigens = "select distinct
                                arrecant.k00_numpre,
                                arrecant.k00_numpar,
                                arrecant.k00_receit,
                                tabrec.k02_descr,
                                arrecant.k00_hist,
                                histcalc.k01_descr,
                                arrecant.k00_tipo,
                                arretipo.k00_descr
                           from abatimentorecibo
                                inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal
                                 left join arrecant     on arrecant.k00_numpre       = db_reciboweb.k99_numpre
                                                       and arrecant.k00_numpar       = db_reciboweb.k99_numpar
                                 left join tabrec       on tabrec.k02_codigo         = arrecant.k00_receit
                                 left join histcalc     on histcalc.k01_codigo       = arrecant.k00_hist
                                 left join arretipo     on arretipo.k00_tipo         = arrecant.k00_tipo
                                 left join arreckey     on arreckey.k00_numpre       = arrecant.k00_numpre
                                                       and arreckey.k00_numpar       = arrecant.k00_numpar
                          where abatimentorecibo.k127_abatimento = {$oParam->iAbatimento}
                       order by arrecant.k00_numpre, arrecant.k00_numpar, arrecant.k00_receit";

    $rsOrigensAbatimento = $oDaoAbatimentoArreckey->sql_record($sSqlBuscaOrigens);

    if ($oDaoAbatimentoArreckey->numrows == 0) {

      $oRetorno->lErro   = true;

    } else {

      $oRetorno->lErro   = false;
      $oRetorno->aOrigens = db_utils::getCollectionByRecord($rsOrigensAbatimento);
    }

    echo $oJson->encode($oRetorno);

    break;


  case "getDadosPortadorCredito":

    $iCodigoInstituicao = (integer) db_getsession('DB_instit');
    $sSqlOrigens = "
      select cgm.z01_nome, cgm.z01_numcgm
        from abatimento
          inner join abatimentorecibo   on abatimentorecibo.k127_abatimento   = abatimento.k125_sequencial
          inner join arrenumcgm         on arrenumcgm.k00_numpre              = abatimentorecibo.k127_numprerecibo
          inner join cgm                on cgm.z01_numcgm                     = arrenumcgm.k00_numcgm
      where k125_tipoabatimento = 3
        and k125_instit         = {$iCodigoInstituicao}
        and k125_sequencial     = {$oParam->iAbatimento}
      limit 1
    ";

    $rsPortadorAbatimento = $oDaoAbatimentoArreckey->sql_record($sSqlOrigens);

    if ($oDaoAbatimentoArreckey->numrows == 0) {
      $oRetorno->lErro   = true;
    } else {

      $oPortador = db_utils::getCollectionByRecord($rsPortadorAbatimento);
      $oRetorno->oDadosPortador = $oPortador[0];
    }

    echo $oJson->encode($oRetorno);

    break;

  case "alterarOrigensAbatimento":

    $aRetorno = array();
    $aRetorno["status"]  = 1;
    $aRetorno["message"] = urlencode("Processamento efetuado com sucesso");

    $aArreckey = array();

    try {

      db_inicio_transacao();

      /*
       * A lógica deste processamento é simples
       *
       *  1 - excluímos os dados da abatimentoarreckey anteriores
       *  2 - verificamos se os registros marcados possuem arreckey, caso contrário geramos arreckey.
       *  3 - realizamos o vinculo entre o abatimento e o arreckey
       */
      $oDaoAbatimentoArreckey->excluir(null, "k128_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoArreckey->erro_status == "0") {
        $sMsg  = "Erro ao excluir dados da abatimentoarreckey\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      foreach ($oParam->aOrigens as $oOrigens) {

        $sWhere  = " k00_numpre     = {$oOrigens->numpre}";
        $sWhere .= " and k00_numpar = {$oOrigens->numpar}";
        $sWhere .= " and k00_receit = {$oOrigens->receit}";
        $sWhere .= " and k00_hist   = {$oOrigens->hist}  ";
        $sWhere .= " and k00_tipo   = {$oOrigens->tipo}  ";
        $sSqlVerificaArreckey = $oDaoArreckey->sql_query_file(null,"k00_sequencial","",$sWhere);
        $rsVerificaArreckey   = $oDaoArreckey->sql_record($sSqlVerificaArreckey);
        if ($oDaoArreckey->numrows > 0) {
          $iArreckey = db_utils::fieldsMemory($rsVerificaArreckey, 0)->k00_sequencial;
        } else {

          $oDaoArreckey->k00_numpre = $oOrigens->numpre;
          $oDaoArreckey->k00_numpar = $oOrigens->numpar;
          $oDaoArreckey->k00_receit = $oOrigens->receit;
          $oDaoArreckey->k00_hist   = $oOrigens->hist;
          $oDaoArreckey->k00_tipo   = $oOrigens->tipo;
          $oDaoArreckey->incluir(null);
          $iArreckey = $oDaoArreckey->k00_sequencial;
          if ($oDaoArreckey->erro_status == "0") {
            $sMsg = "Erro ao incluir registros na arreckey\n";
            $sMsg .= "Erro da classe : {$oDaoArreckey->erro_msg}\n";
            $sMsg .= "Erro do banco  : ".pg_last_error();
            throw new Exception($sMsg);
          }

        }

        $oDaoAbatimentoArreckey->k128_arreckey     = $iArreckey;
        $oDaoAbatimentoArreckey->k128_abatimento   = $oParam->iAbatimento;
        $oDaoAbatimentoArreckey->k128_valorabatido = $oOrigens->valor;
        $oDaoAbatimentoArreckey->k128_correcao     = "0";
        $oDaoAbatimentoArreckey->k128_juros        = "0";
        $oDaoAbatimentoArreckey->k128_multa        = "0";
        $oDaoAbatimentoArreckey->incluir(null);
        if ($oDaoAbatimentoArreckey->erro_status == "0") {
          $sMsg = "Erro ao incluir registros na abatimentoarreckey\n";
          $sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }

      }

      db_fim_transacao(false);

    } catch (Exception $oErro) {
      db_fim_transacao(true);

      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($oErro->getMessage());
    }

    echo $oJson->encode($aRetorno);

  break;

  case "exluirCredito":

    $aRetorno = array();
    $aRetorno["status"]  = 1;
    $aRetorno["message"] = urlencode("Processamento efetuado com sucesso");

    try {

      db_inicio_transacao();

      $oDaoAbatimentoArreckey->excluir(null,"k128_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoArreckey->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentoarreckey\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoDisbanco->excluir(null,"k132_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoDisbanco->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentodisbanco\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoDisbanco->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $sWhere = "k00_numpre in (select k127_numprerecibo
                                  from abatimentorecibo
                                 where k127_abatimento = {$oParam->iAbatimento} )";
      $oDaoRecibo->excluir(null,$sWhere);
      if ($oDaoRecibo->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da recibo\n";
        $sMsg .= "Erro da classe : {$oDaoRecibo->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoRecibo->excluir(null,"k127_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoRecibo->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentorecibo\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoRecibo->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoArrecantPgtoParcial->excluir(null, "arrecantpgtoparcial.k00_abatimento = {$oParam->iAbatimento}");
      if ($oDaoArrecantPgtoParcial->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentopgtoparcial\n";
        $sMsg .= "Erro da classe : {$oDaoArrecantPgtoParcial->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoRegraCompensacao->excluir(null, "k156_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoRegraCompensacao->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimento\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoRegraCompensacao->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoProtProcesso->excluir(null, "k159_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoProtProcesso->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimento processo\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoProtProcesso->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimento->excluir($oParam->iAbatimento);
      if ($oDaoAbatimento->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimento\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimento->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      db_fim_transacao(false);

    } catch (Exception $oErro) {

      db_fim_transacao(true);

      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($oErro->getMessage());

    }

    echo $oJson->encode($aRetorno);

    break;

  /**
   * Realiza a devolução total/parcial do valor do crédito
   */
  case "pagamentoCredito":

    $aRetorno = array(
      "erro" => false,
      "message" => urlencode("Processamento efetuado com sucesso")
    );

    try {

      db_inicio_transacao();

      $oCreditoCompensacao = new CreditoCompensacao((integer) $oParam->iAbatimento);
      $oCreditoCompensacao->setValorCompensacao((float) $oParam->nValor);
      $oCreditoCompensacao->setCgm((integer) $oParam->iCgm);
      $oCreditoCompensacao->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->txtObservacao));
      $oCreditoCompensacao->setDataCompensacao(new DBDate(date('Y-m-d')));
      $oCreditoCompensacao->realizarDevolucao();

      db_fim_transacao();

    } catch (Exception $oException) {

      db_fim_transacao(true);
      $aRetorno = array(
        "erro" => true,
        "message" => urlencode($oException->getMessage())
      );
    }

    echo $oJson->encode($aRetorno);

    break;

  case "getCreditoCorrigido":

    $aRetorno = array(
      "erro" => false,
      "message" => false,
    );

    try {

      $oCredito = new CreditoCompensacao((integer) $oParam->iCodigoCredito);
      $aRetorno["valor_corrigido"] = $oCredito->getValorDisponivelCorrigido();

    } catch (Exception $oException) {

      $aRetorno = array(
        "erro" => true,
        "message" => $oException->getMessage()
      );
    }

    echo $oJson->encode($aRetorno);
    break;

  case "compensacaoCredito":

    $aRetorno = array();
    $aRetorno["erro"]  = false;
    $aRetorno["message"] = urlencode("Processamento efetuado com sucesso");

    try {

      db_inicio_transacao();

      if (count($oParam->aDebitos) == 0) {
        throw new ParameterException("Nenhum débito foi informado.");
      }

      if ($oParam->nValor <= 0) {
        throw new ParameterException("O valor deve ser informado.");
      }

      $oCreditoCompensacao = new CreditoCompensacao((integer) $oParam->iAbatimento);
      $oCreditoCompensacao->setRegraCompensacao((integer) $oParam->iRegraCompensacao);
      $oCreditoCompensacao->setCgm((integer) $oParam->iCgm);
      $oCreditoCompensacao->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->txtObservacao));
      $oCreditoCompensacao->setValorCompensacao((float) $oParam->nValor);

      foreach ($oParam->aDebitos as $oDebito) {
        $oCreditoCompensacao->addDebito($oDebito);
      }

      $oCreditoCompensacao->realizarCompensacao();

      db_fim_transacao();

    } catch (Exception $oErro) {

      db_fim_transacao(true);

      $aRetorno["erro"]  = true;
      $aRetorno["message"] = urlencode($oErro->getMessage());

    }

    echo $oJson->encode($aRetorno);

    break;


  case "exluirPagamentoParcial":

    $aRetorno = array();
    $aRetorno["status"]  = 1;
    $aRetorno["message"] = urlencode("Processamento efetuado com sucesso");

    try {

      db_inicio_transacao();

      /*
       * Buscamos os dados do abatimento que serão utilizados no momento de tornar o pagamento parcial em normal
       */
      $sCampos = "arrecad.*,
                  abatimentoarreckey.k128_valorabatido+abatimentoarreckey.k128_correcao+abatimentoarreckey.k128_juros+abatimentoarreckey.k128_multa as total_abatimento,
                  abatimentorecibo.k127_numprerecibo,
                  abatimentodisbanco.k132_idret,
                  arrepagarecibo.k00_conta,
                  arrepagarecibo.k00_dtpaga";
      $sOrdem  = "arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit";
      $sWhere  = "k128_abatimento = {$oParam->iAbatimento}";
      $rsDadosPagtoParcial   = $oDaoAbatimentoArreckey->sql_record($oDaoAbatimentoArreckey->sql_query_buscaAbatimento($sCampos, $sOrdem, $sWhere));
      $iLinhasAbatimentos    = $oDaoAbatimentoArreckey->numrows;
      $oDadosPagtoParcial    = db_utils::getCollectionByRecord($rsDadosPagtoParcial);

      /*
       * Inicio exclusão do abatimento
       */
      $oDaoAbatimentoArreckey->excluir(null,"k128_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoArreckey->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentoarreckey\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoArreckey->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoDisbanco->excluir(null,"k132_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoDisbanco->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentodisbanco\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoDisbanco->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $sWhere = "k00_numpre in (select k127_numprerecibo
                                  from abatimentorecibo
                                 where k127_abatimento = {$oParam->iAbatimento} )";
      $oDaoRecibo->excluir(null,$sWhere);
      if ($oDaoRecibo->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da recibo\n";
        $sMsg .= "Erro da classe : {$oDaoRecibo->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoRecibo->excluir(null,"k127_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoRecibo->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentorecibo\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoRecibo->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoArrecantPgtoParcial->excluir(null, "arrecantpgtoparcial.k00_abatimento = {$oParam->iAbatimento}");
      if ($oDaoArrecantPgtoParcial->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimentopgtoparcial\n";
        $sMsg .= "Erro da classe : {$oDaoArrecantPgtoParcial->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $sWhere = "idret in (select k132_idret
                             from abatimentodisbanco
                            where k132_abatimento = {$oParam->iAbatimento}) ";
      $oDaoArreIdRet->excluir(null, null, $sWhere);
      if ($oDaoArreIdRet->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros do pagamento da arreidret\n";
        $sMsg .= "Erro da classe : {$oDaoArreIdRet->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimentoProtProcesso->excluir(null, "k159_abatimento = {$oParam->iAbatimento}");
      if ($oDaoAbatimentoProtProcesso->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimento processo\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimentoProtProcesso->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }

      $oDaoAbatimento->excluir($oParam->iAbatimento);
      if ($oDaoAbatimento->erro_status == "0") {
        $sMsg  = "Erro ao excluir registros da abatimento\n";
        $sMsg .= "Erro da classe : {$oDaoAbatimento->erro_msg}\n";
        $sMsg .= "Erro do banco  : ".pg_last_error();
        throw new Exception($sMsg);
      }
      /*
       * FIM da exclusão do abatimento
       */

      foreach ($oDadosPagtoParcial as $oDados) {


        /*
         *
         * Validações
         *
         */

        /*
         * O débito deve possuir parcelas em aberto no arrecad
         */
        if ($oDados->k00_numpar == "") {
          $sMsg  = "Operação não pode ser realizada!\n\n";
          $sMsg .= "Não foi encontrada a parcela {$oDados->k00_numpar} em aberto para o numpre {$oDados->k00_numpre}.\n";
          $sMsg .= "Verifique se houve pagamento do valor restante ao pagamento parcial, neste caso, o mesmo deve ser estornado.\n";
          $sMsg .= "Lembrando que deve ser lançado crédito manualmente para os valores que foram estornados.\n";
          throw new Exception($sMsg);
        }

        /*
         * Não pode haver mais de um pagamento parcial para o mesmo numpre e parcela
         */
        $sWhere  = " arreckey.k00_numpre = {$oDados->k00_numpre} ";
        $sWhere .= " and arreckey.k00_numpar = {$oDados->k00_numpar} ";
        $sWhere .= " and arreckey.k00_receit = {$oDados->k00_receit} ";
        $sWhere .= " and abatimentoarreckey.k128_abatimento <> {$oParam->iAbatimento} ";
        $sWhere .= " and k125_tipoabatimento = 1";
        $sSqlAbatimentos = $oDaoAbatimentoArreckey->sql_query(null, "array_to_string(array_accum(distinct k128_abatimento),',') as abatimentos", null, $sWhere);
        $rsAbatimentos   = $oDaoAbatimentoArreckey->sql_record($sSqlAbatimentos);
        $sAbatimentos    = db_utils::fieldsMemory($rsAbatimentos, 0)->abatimentos;
        if ($sAbatimentos <> "") {
          $sMsg  = "Operação não pode ser realizada!\n\n";
          $sMsg .= "Foram encontrados outros abatimentos para o mesmo numpre e parcela.\n";
          $sMsg .= "Abatimentos: ".$sAbatimentos;
          throw new Exception($sMsg);
        }

        /*
         * @todo Verificar se é necessária validação da autenticação da baixa de banco
         *
         * Verificamos se o pagamento foi autenticado
         *
         $sSqlDisRec  = "select 1                                                      ";
         $sSqlDisRec .= "  from disrec                                                 ";
         $sSqlDisRec .= "       inner join corcla on corcla.k12_codcla = disrec.codcla ";
         $sSqlDisRec .= " where disrec.idret = {$oDados->k132_idret}                   ";
         $sSqlDisRec .= " limit 1                                                      ";
         $rsDadosDisRec = $oDaoDisRec->sql_record($sSqlDisRec);
         if ($oDaoDisRec->numrows >= 1) {
          $sMsg  = "Operação não pode ser realizada!\n\n";
          $sMsg .= "A baixa bancária foi autenticada!\n";
          $sMsg .= "Para prosseguir com a operação deve ser anulada a autentificação da baixa bancária";
          throw new Exception($sMsg);
         }
        */

        /*
         * Fim das Validações
         *
         */


        /*
         * Exclusão do pagamento do recibo gerado para o pgto parcial
         */
        $sWhere = "k00_numpre = {$oDados->k127_numprerecibo}";
        $oDaoArrePaga->excluir(null, $sWhere);
        if ($oDaoArrePaga->erro_status == "0") {
          $sMsg  = "Erro ao excluir registros do pagamento do recibo\n";
          $sMsg .= "Erro da classe : {$oDaoArrePaga->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }

        /*
         * Inserimos na arrecant os dados dos numpres que constam com pagto. parcial ajustando o valor
         * para o valor do pagamento
         */
        $oDaoArreCant->k00_numpre = $oDados->k00_numpre      ;
        $oDaoArreCant->k00_numpar = $oDados->k00_numpar      ;
        $oDaoArreCant->k00_numcgm = $oDados->k00_numcgm      ;
        $oDaoArreCant->k00_dtoper = $oDados->k00_dtoper      ;
        $oDaoArreCant->k00_receit = $oDados->k00_receit      ;
        $oDaoArreCant->k00_hist   = $oDados->k00_hist        ;
        $oDaoArreCant->k00_valor  = $oDados->total_abatimento;
        $oDaoArreCant->k00_dtvenc = $oDados->k00_dtvenc      ;
        $oDaoArreCant->k00_numtot = $oDados->k00_numtot      ;
        $oDaoArreCant->k00_numdig = $oDados->k00_numdig      ;
        $oDaoArreCant->k00_tipo   = $oDados->k00_tipo        ;
        $oDaoArreCant->k00_tipojm = $oDados->k00_tipojm      ;
        $oDaoArreCant->incluir();
        if ($oDaoArreCant->erro_status == "0") {
          $sMsg  = "Erro ao incluir registros do pagamento na arrecant\n";
          $sMsg .= "Erro da classe : {$oDaoArreCant->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }


        /*
         * Inserimos na arrepaga os dados dos numpres que constam com pagto. parcial ajustando o valor
         * para o valor do pagamento
         */
        $oDaoArrePaga->k00_numcgm = $oDados->k00_numcgm      ;
        $oDaoArrePaga->k00_dtoper = $oDados->k00_dtoper      ;
        $oDaoArrePaga->k00_receit = $oDados->k00_receit      ;
        $oDaoArrePaga->k00_hist   = $oDados->k00_hist        ;
        $oDaoArrePaga->k00_valor  = $oDados->total_abatimento;
        $oDaoArrePaga->k00_dtvenc = $oDados->k00_dtvenc      ;
        $oDaoArrePaga->k00_numpre = $oDados->k00_numpre      ;
        $oDaoArrePaga->k00_numpar = $oDados->k00_numpar      ;
        $oDaoArrePaga->k00_numtot = $oDados->k00_numtot      ;
        $oDaoArrePaga->k00_numdig = $oDados->k00_numdig      ;
        $oDaoArrePaga->k00_conta  = $oDados->k00_conta       ;
        $oDaoArrePaga->k00_dtpaga = $oDados->k00_dtpaga      ;
        $oDaoArrePaga->incluir();
        if ($oDaoArrePaga->erro_status == "0") {
          $sMsg  = "Erro ao incluir registros do pagamento na arrepaga\n";
          $sMsg .= "Erro da classe : {$oDaoArrePaga->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }

        /*
         * Alteramos na disbanco o numpre que foi realizado o pagamento
         * Quando é realizado um pagto parcial, é gerado um recibo avulso, a disbanco é alterada para o numpre deste recibo e este é pago.
         */
        $oDaoDisBanco->idret      = $oDados->k132_idret;
        $oDaoDisBanco->k00_numpre = $oDados->k00_numpre;
        if ($oDados->k00_numtot == $oDados->k00_numpar && $iLinhasAbatimentos > 1) {
          $oDaoDisBanco->k00_numpar = "0";
        } else {
          $oDaoDisBanco->k00_numpar = $oDados->k00_numpar;
        }
        $oDaoDisBanco->alterar($oDados->k132_idret);
        if ($oDaoDisBanco->erro_status == "0") {
          $sMsg  = "Erro ao alterar registros do pagamento na disbanco\n";
          $sMsg .= "Erro da classe : {$oDaoDisBanco->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }

        /*
         * Inserimos os dados na arreidret
         * Numpre e parcela com o idret da baixa de banco
         */
        $oDaoArreIdRet->sql_record($oDaoArreIdRet->sql_query_file($oDados->k00_numpre, $oDados->k00_numpar));
        if ( $oDaoArreIdRet->numrows == 0 ) {
          $oDaoArreIdRet->k00_numpre = $oDados->k00_numpre;
          $oDaoArreIdRet->k00_numpar = $oDados->k00_numpar;
          $oDaoArreIdRet->idret      = $oDados->k132_idret;
          $oDaoArreIdRet->k00_instit = db_getsession("DB_instit");
          $oDaoArreIdRet->incluir($oDados->k00_numpre,$oDados->k00_numpar);
          if ($oDaoArreIdRet->erro_status == "0") {
            $sMsg  = "Erro ao incluir registros do pagamento na arreidret\n";
            $sMsg .= "Erro da classe : {$oDaoArreIdRet->erro_msg}\n";
            $sMsg .= "Erro do banco  : ".pg_last_error();
            throw new Exception($sMsg);
          }
        }

        /*
         * Excluimos os dados dos numpres que ficaram na arrecad
         */
        $sWhere = "k00_numpre = {$oDados->k00_numpre} and k00_numpar = {$oDados->k00_numpar}";
        $oDaoArreCad->excluir(null, $sWhere);
        if ($oDaoArreCad->erro_status == "0") {
          $sMsg  = "Erro ao excluir registros do pagamento parcial da arrecad\n";
          $sMsg .= "Erro da classe : {$oDaoArreCad->erro_msg}\n";
          $sMsg .= "Erro do banco  : ".pg_last_error();
          throw new Exception($sMsg);
        }

      }

      db_fim_transacao(false);

    } catch (Exception $oErro) {

      db_fim_transacao(true);

      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($oErro->getMessage());

    }

    echo $oJson->encode($aRetorno);

    break;

}
