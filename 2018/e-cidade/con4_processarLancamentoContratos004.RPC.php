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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("std/DBDate.php");

$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->message  = "";
$oRetorno->status   = 1;
$iAnoSessao         = db_getsession("DB_anousu");

switch ($oParam->exec) {

  case "processarLancamentosContabeisContratos":

    db_inicio_transacao();
    try {

      $sCampos  = "c70_codlan,";
      $sCampos .= "c70_data,";
      $sCampos .= "c70_valor,";
      $sCampos .= "c71_coddoc,";
      $sCampos .= "e60_numemp,";
      $sCampos .= "e60_anousu,";
      $sCampos .= "ac16_acordocategoria,";
      $sCampos .= "ac16_sequencial,";
      $sCampos .= "c53_tipo,";
      $sCampos .= "c53_descr,";
      $sCampos .= "c66_codnota";

      $aWhere   = array();
      $aWhere[] = "acordo.ac16_sequencial  = {$oParam->iCodigoAcordo}";
      $aWhere[] = "acordo.ac16_origem      = 6";
      $aWhere[] = "conlancamdoc.c71_coddoc not in(200, 31) ";
      $sOrderBy = " c53_tipo, c70_data, c70_codlan ";


      $oDaoBuscaEmpenho = db_utils::getDao('conlancamemp');
      $sSqlBuscaEmpenho = $oDaoBuscaEmpenho->sql_query_empenho_contrato(null , "distinct e60_numemp", null, implode(" and ",$aWhere));
      $rsBuscaEmpenho   = $oDaoBuscaEmpenho->sql_record($sSqlBuscaEmpenho);

      $aEmpenhosEncontrados = array();
      if ($oDaoBuscaEmpenho->erro_status == "0") {
        throw new BusinessException("Não foi possível localizar os empenhos vinculados ao contrato.");
      }
      $aEmpenhosEncontrados = db_utils::getCollectionByRecord($rsBuscaEmpenho);

      $iTotalRegistrosAlterados  = 0;
      foreach ($aEmpenhosEncontrados as $oStdCodigoEmpenho) {

        $oDaoConLancamEmp     = db_utils::getDao('conlancamemp');
        $sWherePorEmpenho     = implode(" and ", $aWhere);
        $sWherePorEmpenho    .= " and empempenho.e60_numemp = {$oStdCodigoEmpenho->e60_numemp} ";
        $sSqlBuscaContratos   = $oDaoConLancamEmp->sql_query_empenho_contrato(null , $sCampos, $sOrderBy, $sWherePorEmpenho);
        $rsAcordosEmpenho     = $oDaoConLancamEmp->sql_record($sSqlBuscaContratos);
        $iTotalAcordosEmpenho = $oDaoConLancamEmp->numrows;

        /**
         * Documentos executados
         */
        $aDocumentosExecutados     = array();

        /**
         * Percorremos o Result Set dos dados armazenando nos arrays os dados referente
         * ao tipo de documento e os próprios documentos executados
         */
        for ($iRowAcordo = 0; $iRowAcordo < $iTotalAcordosEmpenho; $iRowAcordo++) {

          $oStdDados = db_utils::fieldsMemory($rsAcordosEmpenho, $iRowAcordo);
          $aDocumentosExecutados[$iRowAcordo]     = $oStdDados->c71_coddoc;
          unset($oStdDados);
        }

        for ($iRowAcordo = 0; $iRowAcordo < $iTotalAcordosEmpenho; $iRowAcordo++) {

          $oStdDados = db_utils::fieldsMemory($rsAcordosEmpenho, $iRowAcordo);

          /*
           * Validação para que não seja executado o documento de 900 - Registro de Contrato para
           * empenhos que sejam RP. O lançamento de registro de contrato deve ser de acordo com o
           * valor da inscrição do RP
           */
          $oDataInicioExercicio = new DBDate("{$iAnoSessao}-01-01");
          $dtInicioExercicio    = $oDataInicioExercicio->getTimeStamp();
          $oDataLancamento      = new DBDate($oStdDados->c70_data);
          $dtDataLancamento     = $oDataLancamento->getTimeStamp();
          if ($oStdDados->e60_anousu < $iAnoSessao && ($oStdDados->c53_tipo == 10 || $oStdDados->c53_tipo == 20) && ($dtDataLancamento < $dtInicioExercicio)) {
            continue;
          }

          $iCodigoDocumentoExecutar = documentoParaExecutar($oStdDados, $aDocumentosExecutados);

          if ($iCodigoDocumentoExecutar === true) {
            continue;
          }

          $oAcordo = new Acordo($oStdDados->ac16_sequencial);
          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oStdDados->e60_numemp);
          $oEventoContabil           = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
          $aLancamentosCadastrados   = $oEventoContabil->getEventoContabilLancamento();
          $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
          $oLancamentoAuxiliarAcordo->setValorTotal($oStdDados->c70_valor);
          $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
          $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
          $oLancamentoAuxiliarAcordo->setHistorico($aLancamentosCadastrados[0]->getHistorico());
          $oLancamentoAuxiliarAcordo->setCodigoNotaLiquidacao($oStdDados->c66_codnota);


          $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
          $oContaCorrenteDetalhe->setAcordo($oAcordo);
          $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
          $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

          $oEventoContabil->executaLancamento($oLancamentoAuxiliarAcordo);

          $iTotalRegistrosAlterados++;
          unset($oLancamentoAuxiliarAcordo);
          unset($oEventoContabil);
        }
      }

      $sMsgRetorno  = "Procedimento executado com sucesso.\n\n";
      $sMsgRetorno .= "Total de registros alterados: {$iTotalRegistrosAlterados}.";
      $oRetorno->message = urlencode($sMsgRetorno);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->message  = urlencode($eErro->getMessage());
      $oRetorno->status   = 2;
      db_fim_transacao(true);
    }

    break;

  case "desprocessarLancamentosContabeisContratos":

    try {
      db_inicio_transacao();


      $sCampos  = "c70_codlan,";
      $sCampos .= "c70_data,";
      $sCampos .= "c70_valor,";
      $sCampos .= "c71_coddoc,";
      $sCampos .= "e60_numemp,";
      $sCampos .= "ac16_acordocategoria,";
      $sCampos .= "ac16_sequencial,";
      $sCampos .= "c53_tipo,";
      $sCampos .= "c53_descr,";
      $sCampos .= "c66_codnota";

      $oParam->sEmpenhos = implode(', ', $oParam->sEmpenhos);

      $aWhere   = array();
      $aWhere[] = "acordo.ac16_sequencial  = {$oParam->iCodigoAcordo}";
      $aWhere[] = "acordo.ac16_origem      = 6";
      $aWhere[] = "conhistdoc.c53_tipo in (900, 901, 11)";
      if (isset($oParam->sEmpenhos) && $oParam->sEmpenhos != "") {
        $aWhere[] = "empempenho.e60_numemp in ({$oParam->sEmpenhos})";
      }
      $sWhere = implode(" and ", $aWhere);

      $oDaoConLancamEmp     = db_utils::getDao('conlancamemp');
      $sSqlBuscaContratos   = $oDaoConLancamEmp->sql_query_empenho_contrato(null,
                                                                            $sCampos,
                                                                            "c53_tipo, c70_data, c70_codlan",
                                                                            $sWhere);
      $rsAcordosEmpenho     = $oDaoConLancamEmp->sql_record($sSqlBuscaContratos);
      $iTotalAcordosEmpenho = $oDaoConLancamEmp->numrows;

      $iTotalRegistrosAlterados  = 0;
      $aTipoDocumentosExecutados = array();
      $aDocumentosExecutados     = array();
      for ($iRowAcordo = 0; $iRowAcordo < $iTotalAcordosEmpenho; $iRowAcordo++) {

        $oStdDados = db_utils::fieldsMemory($rsAcordosEmpenho, $iRowAcordo);
        $aTipoDocumentosExecutados[$iRowAcordo] = $oStdDados->c53_tipo;
        $aDocumentosExecutados[$iRowAcordo]     = $oStdDados->c71_coddoc;
        unset($oStdDados);
      }

      for ($iRowAcordo = 0; $iRowAcordo < $iTotalAcordosEmpenho; $iRowAcordo++) {

        $oStdDados  = db_utils::fieldsMemory($rsAcordosEmpenho, $iRowAcordo);
        $lPularLancamento = false;
        switch ($oStdDados->c71_coddoc) {

          case 900:

            if ( !in_array(11, $aTipoDocumentosExecutados) && in_array(903, $aDocumentosExecutados) ) {
              $lPularLancamento = true;
            }

            $iCodigoDocumentoExecutar = 903;
            break;

          case 901:

            $sWhereNota            = "     conlancamdoc.c71_coddoc = 904";
            $sWhereNota           .= " and conlancamdoc.c71_codlan <> {$oStdDados->c70_codlan}";
            $sWhereNota           .= " and conlancamnota.c66_codnota = {$oStdDados->c66_codnota} ";
            $oDaoConLancamNota     = db_utils::getDao('conlancamnota');
            $sSqlBuscaNotaContrato = $oDaoConLancamNota->sql_query_contrato(null,
                                                                            null,
                                                                            "conlancamnota.*",
                                                                            null,
                                                                            $sWhereNota);

            $rsBuscaNotaContrato   = $oDaoConLancamNota->sql_record($sSqlBuscaNotaContrato);
            if ($oDaoConLancamNota->numrows > 0) {
              $lPularLancamento = true;
            }

            $iCodigoDocumentoExecutar = 904;
            unset($oDaoConLancamNota);

            break;
          default:
            $lPularLancamento = true;
        }

        if ($lPularLancamento) {
          continue;
        }

        $oAcordo = new Acordo($oStdDados->ac16_sequencial);
        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oStdDados->e60_numemp);
        $oEventoContabil           = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
        $aLancamentosCadastrados   = $oEventoContabil->getEventoContabilLancamento();
        $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
        $oLancamentoAuxiliarAcordo->setValorTotal($oStdDados->c70_valor);
        $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
        $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
        $oLancamentoAuxiliarAcordo->setHistorico($aLancamentosCadastrados[0]->getHistorico());
        $oLancamentoAuxiliarAcordo->setCodigoNotaLiquidacao($oStdDados->c66_codnota);

        $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
        $oContaCorrenteDetalhe->setAcordo($oAcordo);
        $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
        $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

        $oEventoContabil->executaLancamento($oLancamentoAuxiliarAcordo);
        $iTotalRegistrosAlterados++;
        unset($oLancamentoAuxiliarAcordo);
        unset($oEventoContabil);
      }

      $sMsgRetorno       = "Processamento concluído com sucesso.\n\n";
      $sMsgRetorno      .= "Total de registros alterados: {$iTotalRegistrosAlterados}";
      $oRetorno->message = urlencode($sMsgRetorno);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->message  = urlencode($eErro->getMessage());
      $oRetorno->status   = 2;
      db_fim_transacao(true);
    }

    break;
}

/**
 * Função que define se devemos pular o lançamento ou executar o documento por ela retornado
 * @param stdClass $oStdDados
 * @param array $aDocumentosExecutados
 * @return boolean|number
 */
function documentoParaExecutar($oStdDados, $aDocumentosExecutados) {

  $lPularLancamento = false;
  switch ($oStdDados->c53_tipo) {

    case 10: // EMPENHO

      if (in_array(900, $aDocumentosExecutados)) {
        $lPularLancamento = true;
      }
      $iCodigoDocumentoExecutar  = 900;
      break;

    case 11: // ESTORNO DE EMPENHO

      if (in_array(903, $aDocumentosExecutados)) {
        $lPularLancamento = true;
      }
      $iCodigoDocumentoExecutar  = 903;
      break;

    case 20: // LIQUIDAÇÃO

      $sWhereNota            = "     conlancamdoc.c71_coddoc = 901";
      $sWhereNota           .= " and conlancamdoc.c71_codlan <> {$oStdDados->c70_codlan}";
      $sWhereNota           .= " and conlancamnota.c66_codnota = {$oStdDados->c66_codnota} ";
      $oDaoConLancamNota     = db_utils::getDao('conlancamnota');
      $sSqlBuscaNotaContrato = $oDaoConLancamNota->sql_query_contrato(null,
                                                                      null,
                                                                      "conlancamnota.*",
                                                                      null,
                                                                      $sWhereNota);
      $rsBuscaNotaContrato   = $oDaoConLancamNota->sql_record($sSqlBuscaNotaContrato);
      if ($oDaoConLancamNota->numrows > 0) {
        $lPularLancamento = true;
      }
      $iCodigoDocumentoExecutar = 901;
      unset($oDaoConLancamNota);

      break;

    case 21: // ESTORNO LIQUIDAÇÃO

      $sWhereNota            = "     conlancamdoc.c71_coddoc = 904";
      $sWhereNota           .= " and conlancamdoc.c71_codlan <> {$oStdDados->c70_codlan}";
      $sWhereNota           .= " and conlancamnota.c66_codnota = {$oStdDados->c66_codnota} ";
      $oDaoConLancamNota     = db_utils::getDao('conlancamnota');
      $sSqlBuscaNotaContrato = $oDaoConLancamNota->sql_query_contrato(null,
          null,
          "conlancamnota.*",
          null,
          $sWhereNota);
      $rsBuscaNotaContrato   = $oDaoConLancamNota->sql_record($sSqlBuscaNotaContrato);
      if ($oDaoConLancamNota->numrows > 0) {
        $lPularLancamento = true;
      }
      $iCodigoDocumentoExecutar = 904;
      unset($rsBuscaNotaContrato);

      break;

    case 1000: // INSCRIÇÃO DE RP

      if (in_array(900, $aDocumentosExecutados)) {
        $lPularLancamento = true;
      }
      $iCodigoDocumentoExecutar = 900;
      break;

    default:

      $lPularLancamento= true;
      break;
  }

  if ($lPularLancamento) {
    return true;
  }

  return $iCodigoDocumentoExecutar;

}

echo $oJson->encode($oRetorno);