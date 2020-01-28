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

//ac4_acordoinclusao.rpc.php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/ParameterException.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");
require_once("model/Acordo.model.php");
require_once("model/AcordoComissao.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/AcordoComissaoMembro.model.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$oErro                  = new stdClass();
$aDadosRetorno          = array();
$sCaminhoMensagens = "patrimonial.contratos.ac4_acordoinclusao.";
try {

  switch ($oParam->exec) {

    case "getFormasControle" :

      $aFormasControle = array();
      $aFormasControle = getValoresPadroesCampo('ac20_tipocontrole');

      foreach ($aFormasControle as $iFormasControle => $oFormasControle) {

         $oDadosFormaControle = new stdClass();
         $oDadosFormaControle->iValor     = $iFormasControle;
         $oDadosFormaControle->sDescricao = urlencode($oFormasControle);
         $oDadosFormaControle->iD         = $oParam->iD;
         $aDadosRetorno[] = $oDadosFormaControle;
      }
      $oRetorno->aDadosRetorno = $aDadosRetorno;
    break;


    case 'vincularEmpenhos' :

      db_inicio_transacao();
      $iOrigem                = $oParam->iOrigem;                     ;
      $sEmpenhos              = implode(", ", $oParam->sListaEmpenhos);
      $iNumCgm                = $oParam->iNumCgm;                     ;
      $iAcordo                = $oParam->iAcordo;

      $aEmpenhosVincular      = $oParam->sListaEmpenhos;//array();
      $aEmpenhosVinculados    = array();

      $oDaoAcordoEmpEmpitem     = db_utils::getDao("acordoempempitem");
      $oDaoAcordo               = db_utils::getDao("acordo");
      $oDaAcordoItemPrevisao    = db_utils::getDao("acordoitemprevisao");
      $oDaoAcordoItemPeriodo    = db_utils::getDao("acordoitemperiodo");
      $oDaoAcordoItem           = db_utils::getDao("acordoitem");

      // verificamos os empenho que estao vinculados com a lista que esta vindo
      // para ver quais devemos desvincular.
      $sCampos = "e100_sequencial, e100_numemp";
      $sWhere  = "    e100_acordo = {$iAcordo}  ";
      if ($sEmpenhos != '') {

        $sWhere .= "and e100_numemp not in ({$sEmpenhos}) ";
      }

      $oDaoDesvincular         = new cl_empempenhocontrato();
      $sSqlEmpenhosDesvincular = $oDaoDesvincular->sql_query_file(null, $sCampos, null, $sWhere);
      $rsEmpenhosDesvincular   = $oDaoDesvincular->sql_record($sSqlEmpenhosDesvincular);

      for ($iRowDesvincular = 0; $iRowDesvincular < $oDaoDesvincular->numrows; $iRowDesvincular++) {

        $oDadosDesvincular = db_utils::fieldsMemory($rsEmpenhosDesvincular, $iRowDesvincular);
        if (USE_PCASP) {

          $sWhereEmpenho        = "c75_numemp = {$oDadosDesvincular->e100_numemp} and c53_coddoc in (900, 901)";
          $oDaoConlancamEmp     = new cl_conlancamemp();
          $sSqlBuscaLancamentos = $oDaoConlancamEmp->sql_query_documentos(null, "count(*) total_inclusao", null, $sWhereEmpenho);
          $rsBuscaLancamentos   = $oDaoConlancamEmp->sql_record($sSqlBuscaLancamentos);
          $iTotalInclusao       = db_utils::fieldsMemory($rsBuscaLancamentos, 0)->total_inclusao;
          unset($oDaoConlancamEmp);

          $sWhereEmpenho        = "c75_numemp = {$oDadosDesvincular->e100_numemp} and c53_coddoc in (903, 904)";
          $oDaoConlancamEmp     = new cl_conlancamemp();
          $sSqlBuscaLancamentos = $oDaoConlancamEmp->sql_query_documentos(null, "count(*) total_estorno", null, $sWhereEmpenho);
          $rsBuscaLancamentos   = $oDaoConlancamEmp->sql_record($sSqlBuscaLancamentos);
          $iTotalEstorno       = db_utils::fieldsMemory($rsBuscaLancamentos, 0)->total_estorno;
          unset($oDaoConlancamEmp);

          if ( $iTotalInclusao != $iTotalEstorno ) {
            throw new Exception(_M("{$sCaminhoMensagens}desvincular_empenho_com_lancamento"), null);
          }
        }

        $sCamposDesvincular  = "ac26_sequencial as acordoposicao, ";
        $sCamposDesvincular .= "ac20_sequencial as acordoitem     ";
        $sWhereDesvincular   = "ac16_sequencial = {$iAcordo} and e100_numemp = {$oDadosDesvincular->e100_numemp}";
        $sSqlDesvincularEmpenhos = $oDaoAcordo->sql_queryItensEmpenhoContrato(null, $sCamposDesvincular, null, $sWhereDesvincular);
        $rsDesvincularEmpenhos   = $oDaoAcordo->sql_record($sSqlDesvincularEmpenhos);

        if ($oDaoAcordo->numrows > 0) {

          $aDesvincular = db_utils::getCollectionByRecord($rsDesvincularEmpenhos);

          // percorremos os registros da AcordoEmpEmpitem trazidos no select
          foreach ($aDesvincular as $iDesvincular => $oDesvincular) {

            if (!empty($oDesvincular->acordoitem)) {

              $oDaAcordoItemPrevisao->excluir(null, "ac37_acordoitem = {$oDesvincular->acordoitem}");
              if ($oDaAcordoItemPrevisao->erro_status == "0") {

                $oErro->erro_msg = $oDaAcordoItemPrevisao->erro_msg;
                throw new Exception(_M($sCaminhoMensagens."acordo_item_previsao_excluir", $oErro));
              }
              // primeiro excluimos da acordoempempitem
              $oDaoAcordoEmpEmpitem->excluir(null,"ac44_acordoitem = {$oDesvincular->acordoitem} " );
              if ($oDaoAcordoEmpEmpitem->erro_status == "0") {

                $oErro->erro_msg = $oDaoAcordoEmpEmpitem->erro_msg;
                throw new Exception(_M($sCaminhoMensagens."acordo_empempitem_excluir", $oErro));
              }
              // excluimos da AcordoItemPeriodo
              $oDaoAcordoItemPeriodo->excluir(null, "ac41_acordoitem = {$oDesvincular->acordoitem}");
              if ($oDaoAcordoItemPeriodo->erro_status == "0") {

                $oErro->erro_msg = $oDaoAcordoItemPeriodo->erro_msg;
                throw new Exception(_M($sCaminhoMensagens."acordo_item_periodo_excluir", $oErro));
              }
              // excluimos da acordoitem
              $oDaoAcordoItem->excluir(null, "ac20_sequencial = {$oDesvincular->acordoitem}");
              if ($oDaoAcordoItem->erro_status == "0") {

                $oErro->erro_msg = $oDaoAcordoItem->erro_msg;
                throw new Exception(_M($sCaminhoMensagens."acordo_item_excluir", $oErro));
              }
            }
          }
        }

        $oDaoEmpEmpenhoContrato = new cl_empempenhocontrato();
        $oDaoEmpEmpenhoContrato->excluir (null, "e100_numemp = {$oDadosDesvincular->e100_numemp} and e100_acordo = {$iAcordo} ");
        if ($oDaoEmpEmpenhoContrato->erro_status == "0") {

          $oErro->erro_msg = $oDaoEmpEmpenhoContrato->erro_msg;
          throw new Exception(_M($sCaminhoMensagens."empempenho_contrato_excluir", $oErro));
        }
      }
      // percorremos os empenhos selecionados,
      // verificamos se ele ja nao está no vinculo

      /**
       *   aqui devemos tambem a cada passada dos empenhos acumular o valor total
       *   depois compara esse valor total de empenhos, com o valor cadastrado no acordo ac16_valor
       *   o total dos empenhos nao podera exceder esse valor.
       */

      $oAcordo             = AcordoRepository::getByCodigo($iAcordo);
      $nValorAcordo        = $oAcordo->getValorContrato();
      $nValorTotalEmpenhos = 0;

      foreach ($aEmpenhosVincular as $oEmpenhosVincular) {

        $oDaoEmpenhoContrato  = new cl_empempenhocontrato();
        $sSqlVerificaVinculo  = $oDaoEmpenhoContrato->sql_query_file(null, "*", null, "e100_acordo = {$iAcordo} and e100_numemp = {$oEmpenhosVincular} ");
        $rsVerificaVinculados = $oDaoEmpenhoContrato->sql_record($sSqlVerificaVinculo);

        if ($oDaoEmpenhoContrato->numrows == 0) {

          $oDaoEmpenhoContrato->e100_numemp = $oEmpenhosVincular;
          $oDaoEmpenhoContrato->e100_acordo = $iAcordo;
          $oDaoEmpenhoContrato->incluir(null);
          if ($oDaoEmpenhoContrato->erro_status == 0) {

            $oErro->erro_msg = $oDaoEmpenhoContrato->erro_msg;
            throw new Exception(_M($sCaminhoMensagens."erro_vincular_empenho_contrato", $oErro));
          }
        }

        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenhosVincular);
        $nValorTotalEmpenhos += $oEmpenhoFinanceiro->getValorEmpenho();
      }
      // como dito comparamos os 2 valores
      if ( round($nValorTotalEmpenhos, 2) > round($nValorAcordo, 2)) {
        throw new Exception(_M($sCaminhoMensagens."valor_total_empenhos_maior_valor_acordo"));
      }

      db_fim_transacao(false);

      $oRetorno->sMessage = _M($sCaminhoMensagens.'empenho_vinculado_com_sucesso');

    break;

    case "getEmpenhosVinculadosAcordo":

      $oDaoEmpEmpenhoContrato = db_utils::getDao("empempenhocontrato");
      $iAcordo                = $oParam->iAcordo;

      $sWhereContrato   = " empempenhocontrato.e100_acordo = {$oParam->iAcordo}";
      $sOrderContrato   = " empempenho.e60_numemp";
      $sCamposContrato  = " e60_numemp,";
      $sCamposContrato .= " e60_codemp,";
      $sCamposContrato .= " e60_anousu,";
      $sCamposContrato .= " e60_emiss,";
      $sCamposContrato .= " e60_vlremp,";
      $sCamposContrato .= " e60_resumo,";
      $sCamposContrato .= " 'true' as lVinculado";
      $sSqlContrato     = $oDaoEmpEmpenhoContrato->sql_query_empenhos_contrato(null, $sCamposContrato, $sOrderContrato, $sWhereContrato);

      $rsContrato   = $oDaoEmpEmpenhoContrato->sql_record($sSqlContrato);
      if ($oDaoEmpEmpenhoContrato->numrows > 0) {

        for ($iEmpenho = 0; $iEmpenho < $oDaoEmpEmpenhoContrato->numrows; $iEmpenho++) {

          $oEmpenho      = db_utils::fieldsMemory($rsContrato, $iEmpenho);
          $oDadosRetorno = new stdClass();
          $oDadosRetorno->e60_numemp = $oEmpenho->e60_numemp;
          $oDadosRetorno->e60_codemp = $oEmpenho->e60_codemp;
          $oDadosRetorno->e60_anousu = $oEmpenho->e60_anousu;
          $oDadosRetorno->e60_emiss  = db_formatar($oEmpenho->e60_emiss, "d") ;
          $oDadosRetorno->e60_vlremp = db_formatar($oEmpenho->e60_vlremp, "f");
          $oDadosRetorno->e60_resumo = urlencode($oEmpenho->e60_resumo);
          $oDadosRetorno->lVinculado = $oEmpenho->lvinculado;
          $aDadosRetorno[] = $oDadosRetorno;
        }
      }

      if (!isset($oParam->lImplantacaoContatos)) {


        $aEmpenhosVinculadosSessao = db_getsession("oEmpenhosSalvar", false);
        if (isset($aEmpenhosVinculadosSessao->aIncluir) && count($aEmpenhosVinculadosSessao->aIncluir) > 0) {

          $oDaoEmpenho        = db_utils::getDao('empempenho');
          $sSequencialEmpenho = implode(", ", $aEmpenhosVinculadosSessao->aIncluir);
          $sSqlEmpenhoSessao  = $oDaoEmpenho->sql_query_empenhocontrato(null,
                                                                       "{$sCamposContrato}",
                                                                       $sOrderContrato,
                                                                       "e60_numemp in ({$sSequencialEmpenho})");
          $rsEmpenhoSessao = $oDaoEmpenho->sql_record($sSqlEmpenhoSessao);
          for ($iTotalEmpenho = 0; $iTotalEmpenho < $oDaoEmpenho->numrows; $iTotalEmpenho++) {

          $oEmpenho      = db_utils::fieldsMemory($rsEmpenhoSessao, $iTotalEmpenho);
          $oDadosRetorno = new stdClass();
          $oDadosRetorno->e60_numemp = $oEmpenho->e60_numemp;
          $oDadosRetorno->e60_codemp = $oEmpenho->e60_codemp;
          $oDadosRetorno->e60_anousu = $oEmpenho->e60_anousu;
          $oDadosRetorno->e60_emiss  = db_formatar($oEmpenho->e60_emiss, "d") ;
          $oDadosRetorno->e60_vlremp = db_formatar($oEmpenho->e60_vlremp, "f");
          $oDadosRetorno->e60_resumo = urlencode($oEmpenho->e60_resumo);
          $oDadosRetorno->lVinculado = $oEmpenho->lvinculado;
          $aDadosRetorno[] = $oDadosRetorno;
          }
        }
      }

      $oRetorno->aDadosRetorno = $aDadosRetorno;
      break;


    case "getEmpenhos":

      $oDaoEmpenho             = db_utils::getDao("empempenho");
      $oDaoEmpEmpenhoContrato  = db_utils::getDao("empempenhocontrato");
      $iNumCgm                 = $oParam->iNumCgm;
      $iCodigoEmpenho          = $oParam->iCodigoEmpenho;
      $iNumeroEmpenho          = $oParam->iNumeroEmpenho;
      $dtInicial               = implode("-", array_reverse(explode("/",$oParam->dtInicial)));
      $dtFinal                 = implode("-", array_reverse(explode("/",$oParam->dtFinal)))  ;
      $iAcordo                 = $oParam->iAcordo;
      $sListaEmpenhosVinculado = "";

      /**
       * Busca todos empenhos vinculados ao contrato
       */
      $oDaoEmpEmpenhoContrato = db_utils::getDao("empempenhocontrato");
      $iAcordo                = $oParam->iAcordo;

      $sWhereContrato   = " empempenhocontrato.e100_acordo = {$oParam->iAcordo}";
      $sOrderContrato   = " empempenho.e60_numemp";
      $sCamposContrato  = " e60_numemp,";
      $sCamposContrato .= " e60_codemp,";
      $sCamposContrato .= " e60_anousu,";
      $sCamposContrato .= " e60_emiss,";
      $sCamposContrato .= " e60_vlremp,";
      $sCamposContrato .= " e60_resumo,";
      $sCamposContrato .= " 'true' as lVinculado";
      $sSqlContrato     = $oDaoEmpEmpenhoContrato->sql_query_empenhos_contrato(null, $sCamposContrato, $sOrderContrato, $sWhereContrato);
      $rsContrato       = $oDaoEmpEmpenhoContrato->sql_record($sSqlContrato);

      if ($oDaoEmpEmpenhoContrato->numrows > 0) {

        for ($iEmpenho = 0; $iEmpenho < $oDaoEmpEmpenhoContrato->numrows; $iEmpenho++) {

          $oEmpenho      = db_utils::fieldsMemory($rsContrato, $iEmpenho);
          $oDadosRetorno = new stdClass();
          $oDadosRetorno->e60_numemp = $oEmpenho->e60_numemp;
          $oDadosRetorno->e60_codemp = $oEmpenho->e60_codemp;
          $oDadosRetorno->e60_anousu = $oEmpenho->e60_anousu;
          $oDadosRetorno->e60_emiss  = db_formatar($oEmpenho->e60_emiss, "d") ;
          $oDadosRetorno->e60_vlremp = db_formatar($oEmpenho->e60_vlremp, "f");
          $oDadosRetorno->e60_resumo = urlencode($oEmpenho->e60_resumo);
          $oDadosRetorno->lVinculado = $oEmpenho->lvinculado;
          $aDadosRetorno[] = $oDadosRetorno;
        }
      }

      if (!isset($oParam->lImplantacaoContatos)) {

        $aEmpenhosVinculadosSessao = db_getsession("oEmpenhosSalvar", false);
        if (isset($aEmpenhosVinculadosSessao->aIncluir) && count($aEmpenhosVinculadosSessao->aIncluir) > 0) {

          $oDaoEmpenho         = db_utils::getDao('empempenho');
          $sSequencialEmpenho  = implode(", ", $aEmpenhosVinculadosSessao->aIncluir);
          $sWhere              = "e60_numemp in ({$sSequencialEmpenho})";
          $sSqlEmpenhoSessao   = $oDaoEmpenho->sql_query_empenhocontrato(null,"{$sCamposContrato}",$sOrderContrato, $sWhere);
          $rsEmpenhoSessao = $oDaoEmpenho->sql_record($sSqlEmpenhoSessao);

          for ($iTotalEmpenho = 0; $iTotalEmpenho < $oDaoEmpenho->numrows; $iTotalEmpenho++) {

            $oEmpenho      = db_utils::fieldsMemory($rsEmpenhoSessao, $iTotalEmpenho);
            $oDadosRetorno = new stdClass();
            $oDadosRetorno->e60_numemp = $oEmpenho->e60_numemp;
            $oDadosRetorno->e60_codemp = $oEmpenho->e60_codemp;
            $oDadosRetorno->e60_anousu = $oEmpenho->e60_anousu;
            $oDadosRetorno->e60_emiss  = db_formatar($oEmpenho->e60_emiss, "d") ;
            $oDadosRetorno->e60_vlremp = db_formatar($oEmpenho->e60_vlremp, "f");
            $oDadosRetorno->e60_resumo = urlencode($oEmpenho->e60_resumo);
            $oDadosRetorno->lVinculado = $oEmpenho->lvinculado;
            $aDadosRetorno[] = $oDadosRetorno;
            }
          }
        }
      $sWhere  = "e60_numcgm = {$iNumCgm} ";

      /*
       * aqui verificamos se os empenhos selecionados para o acordo a ser alterado
       * ja nao esta vinculado com outro acordo, para que nao venha na lista.
       */
      if ($iAcordo != null || $iAcordo != '') {

        $sCamposVinculados  = " ";
        $sCamposVinculados .= "array_to_string(array_accum( distinct e100_numemp),',') as empenhosvinculados";
        $sSqlVinculados     = $oDaoEmpEmpenhoContrato->sql_query_file(null, $sCamposVinculados, null, null);
        $rsVinculados = $oDaoEmpEmpenhoContrato->sql_record($sSqlVinculados);

        if ($oDaoEmpEmpenhoContrato->numrows > 0) {


          $sListaEmpenhosVinculado = db_utils::fieldsMemory($rsVinculados, 0)->empenhosvinculados;

          if ($sListaEmpenhosVinculado != "") {

           $sWhere .= "and e60_numemp not in ($sListaEmpenhosVinculado) ";
          }
        }
      }  else {

        $sWhere .= " and e60_numemp not in (select e100_numemp from empempenhocontrato) ";
      }

      if (isset($iCodigoEmpenho) && $iCodigoEmpenho != null) {

        // verificamos empenho ja vinculados ao contrato, paraque seja exibido como vinculado
        if ($iAcordo != null || $iAcordo != '') {

          $sCampoJaVinculado = "array_to_string(array_accum( distinct e100_numemp),',') as e100_numemp";
          $sSqlJaVinculados  = $oDaoEmpEmpenhoContrato->sql_query_file(null, $sCampoJaVinculado, null, "e100_acordo = {$iAcordo}");
          $rsJaVinculados    = $oDaoEmpEmpenhoContrato->sql_record($sSqlJaVinculados);
          $sVinculados       = db_utils::fieldsMemory($rsJaVinculados, 0)->e100_numemp;
          $sVinculados       = $sVinculados . (!empty($sVinculados) ? ", " : '' ) . $iCodigoEmpenho;
        } else {
          $sVinculados = $iCodigoEmpenho;
        }

        $sWhere .= " and e60_numemp in ($sVinculados)";
      }

      if (isset($iNumeroEmpenho) && $iNumeroEmpenho != null) {

        // verificamos empenho ja vinculados ao contrato, paraque seja exibido como vinculado
        if ($iAcordo != null || $iAcordo != '') {

          $sCampoJaVinculado = "array_to_string(array_accum( distinct e60_codemp),',') as e60_codemp";
          $sCampoJaVinculado = "e60_codemp";
          $sSqlJaVinculados  = $oDaoEmpEmpenhoContrato->sql_query_empenhos_contrato(null, $sCampoJaVinculado, null, null);

          $rsJaVinculados    = $oDaoEmpEmpenhoContrato->sql_record($sSqlJaVinculados);

          $aVinculados   = db_utils::getCollectionByRecord($rsJaVinculados);
          $sVinculados   = "$iNumeroEmpenho::varchar";
          $aJaVinculados = array($sVinculados);

          foreach ($aVinculados as $oVinculados) {
            $aJaVinculados[] = $oVinculados->e60_codemp. "::varchar";
          }

          $sVinculados = implode($aJaVinculados, ",");
          $sWhere .= "and e60_codemp in ({$sVinculados}) ";
        } else {

          $sVinculados   = "$iNumeroEmpenho::varchar";//$sVinculados = "";
          $sWhere .= "and e60_codemp = '{$iNumeroEmpenho}' ";
        }

      }

      if ((isset($dtInicial) &&  $dtInicial != null) && (isset($dtFinal) &&  $dtFinal != null)) {
        $sWhere .= "and e60_emiss between '{$dtInicial}' and '{$dtFinal}' ";
      }

      if ((isset($dtInicial) &&  $dtInicial != null) && (!isset($dtFinal) ||  $dtFinal == null)) {

        $dtFinal = date("Y-m-d", db_getsession("DB_datausu"));
        $sWhere .= "and e60_emiss between  '{$dtInicial}' and '{$dtFinal}' ";
      }

      if ((isset($dtFinal) &&  $dtFinal != null) && (!isset($dtInicial) ||  $dtInicial == null)) {

        $dtInicial = "1900-01-01";
        $sWhere .= "and e60_emiss between  '{$dtInicial}' and '{$dtFinal}' ";
      }

      $sCamposEmpenhos  = " distinct e60_numemp,";
      $sCamposEmpenhos .= " e60_codemp,";
      $sCamposEmpenhos .= " e60_emiss,";
      $sCamposEmpenhos .= " e60_vlremp,";
      $sCamposEmpenhos .= " e60_resumo,";
      $sCamposEmpenhos .= " case";
      $sCamposEmpenhos .= "   when e100_numemp is null";
      $sCamposEmpenhos .= "     then 'false'";
      $sCamposEmpenhos .= "   else 'true'";
      $sCamposEmpenhos .= " end   as  lVinculado";
      $sOrderEmpenhos   = " lVinculado desc, e60_numemp asc";

      $sSqlEmpenho = $oDaoEmpenho->sql_query_empenhocontrato(null, "{$sCamposEmpenhos}", $sOrderEmpenhos, $sWhere);
      $rsEmpenho   = $oDaoEmpenho->sql_record($sSqlEmpenho);

      if ($oDaoEmpenho->numrows == 0) {
        throw new BusinessException(_M($sCaminhoMensagens."nenhum_empenho_encontrado"));
      }

      for ($iTotalEmpenho = 0; $iTotalEmpenho < $oDaoEmpenho->numrows; $iTotalEmpenho++) {

        $oEmpenho      = db_utils::fieldsMemory($rsEmpenho, $iTotalEmpenho);
        $oDadosRetorno = new stdClass();
        $oDadosRetorno->e60_numemp = $oEmpenho->e60_numemp;
        $oDadosRetorno->e60_codemp = $oEmpenho->e60_codemp;
        $oDadosRetorno->e60_emiss  = db_formatar($oEmpenho->e60_emiss, "d") ;
        $oDadosRetorno->e60_vlremp = db_formatar($oEmpenho->e60_vlremp, "f");
        $oDadosRetorno->e60_resumo = urlencode($oEmpenho->e60_resumo);
        $oDadosRetorno->lVinculado = $oEmpenho->lvinculado;
        $aDadosRetorno[] = $oDadosRetorno;
      }
      $oRetorno->aDadosRetorno = $aDadosRetorno;

    break;

    case "getItensEmpenhosAindaNaoVinculados":

      /**
       * Pegar todos os itens de empenho, dos empenhos vinculados ao acordo x
       * Buscar todos itens de empenho que não estão na acordoitem ainda
       */

      $iAcordo             = $oParam->iAcordo;
      $sWhere              = "e100_acordo = {$iAcordo}";
      $sWhere             .= " and ac44_empempitem is null";

      $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
      $sSqlEmpenhoItens    = $oDaoEmpenhoContrato->sql_query_itensContratoEmpenho( null, "*", null, $sWhere);

      $rsEmpenhoItens      = $oDaoEmpenhoContrato->sql_record($sSqlEmpenhoItens);
      $aItem               = null;

      if($oDaoEmpenhoContrato->numrows > 0) {

        $aItem = array();

        for($iItem = 0; $iItem < $oDaoEmpenhoContrato->numrows; $iItem++) {

          $oItem                     = db_utils::fieldsMemory($rsEmpenhoItens, $iItem);

          $oStdItem                  = new stdClass();
          $oStdItem->iEmpenho        = $oItem->e60_codemp;
          $oStdItem->iEmpenhoItem    = $oItem->e62_sequencial;
          $oStdItem->iCodigoMaterial = $oItem->pc01_codmater;
          $oStdItem->sDescricao      = urlencode(substr($oItem->pc01_descrmater, 0, 45));
          $aItem[] = $oStdItem;
        }
      }
      $oRetorno->aItensEmpenhos = $aItem;

    break;

    case "verificaTipoAcordo":

      $oDAOAcordo = db_utils::getDao("acordo");
      $sSQLAcordo = $oDAOAcordo->sql_query_file($oParam->iAcordo);
      $rsAcordo   = $oDAOAcordo->sql_record($sSQLAcordo);

      if ($oDAOAcordo->numrows != 1) {
        throw new Execption(_M($sCaminhoMensagens."verifica_tipo_acordo"));
      }
      $oRetorno->iTipoAcordo = db_utils::fieldsMemory($rsAcordo, 0)->ac16_origem;
    break;

    case "salvaVinculoItensEmpenho":

      db_inicio_transacao();

      $oContrato = new Acordo($oParam->iAcordo);
      $oPosicao  = $oContrato->getUltimaPosicao();

      $dtDataInicialPosicao = db_formatar($oContrato->getDataInicial(), "d");
      $dtDataFinalPosicao   = db_formatar($oContrato->getDataFinal(), "d");

      /**
       * Inclui itens de empenho ainda não vinculados ao acordo
       */
      foreach ($oParam->aItens as $oStdItem) {

        if (db_formatar($oStdItem->dtInicial, "d") < $dtDataInicialPosicao) {

          $oErro->iCodigoMaterial = $oStdItem->iCodigoMaterial;
          $oErro->iEmpenho        = $oStdItem->iEmpenho;
          throw new Exception(_M($sCaminhoMensagens."erro_data_inicial" , $oErro));
        }

        if (db_formatar($oStdItem->dtFinal, "d") > $dtDataFinalPosicao) {

          $oErro->iCodigoMaterial = $oStdItem->iCodigoMaterial;
          $oErro->iEmpenho        = $oStdItem->iEmpenho;
          throw new Exception(_M($sCaminhoMensagens."erro_data_final" , $oErro));
        }

        if (db_formatar($oStdItem->dtInicial, "d") > db_formatar($oStdItem->dtFinal, "d")) {

          $oErro->iCodigoMaterial = $oStdItem->iCodigoMaterial;
          $oErro->iEmpenho        = $oStdItem->iEmpenho;
          throw new Exception(_M($sCaminhoMensagens."erro_data_final_material" , $oErro));
        }
        $oPosicao->adicionarItemDeEmpenho($oStdItem->iEmpenhoItem, $oStdItem);
      }
      db_fim_transacao(false);

    break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);

}catch (DBException $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (ParameterException $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (BusinessException $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>