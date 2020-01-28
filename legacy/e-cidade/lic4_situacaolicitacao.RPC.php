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
require_once(modification("model/licitacao.model.php"));

$oParam = JSON::create()->parse(str_replace("\\", "", $_POST['json']));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->erro    = false;
$oRetorno->message = "";

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'incluir':

      $oData = null;
      if (!empty($oParam->data)) {
        $oData = new DBDate($oParam->data);
      }

      $iCodigoLicitacao  = $oParam->iCodigoLicitacao;
      $iTipoSituacao     = $oParam->iTipoSituacao;
      $sObservacao       = '';
      if (isset($oParam->sObservacao)) {
        $sObservacao       = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      }
      $oLicitacao = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarSituacao($iTipoSituacao,$sObservacao, $oData);
      $oRetorno->message = "Situação salva com sucesso.";

      break;

    /**
     * Modifica apenas a observação da Licitação
     */
    case 'alterar':


      $iSituacaoSequencial = $oParam->iSituacaoSequencial;
      $iCodigoLicitacao    = $oParam->iCodigoLicitacao;
      $sObservacao         = '';

      if (isset($oParam->sObservacao)) {
        $sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      }

      $oLicitacao          = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarObservacaoSituacao($iSituacaoSequencial,$sObservacao);
      $oRetorno->message = "Motivo alterado com sucesso.";

      break;


    case "getDadosSituacaoLicitacao":

      $oDaoLicLicitaSituacao = db_utils::getDao('liclicitasituacao');
      $sSqlBuscaSituacao     = $oDaoLicLicitaSituacao->sql_query_file($oParam->iCodigoAlteracao);
      $rsBuscaSituacao       = $oDaoLicLicitaSituacao->sql_record($sSqlBuscaSituacao);
      if ($oDaoLicLicitaSituacao->numrows == 0) {
        throw new Exception("Situação da licitação não encontrada.");
      }

      $oDadoSituacao              = db_utils::fieldsMemory($rsBuscaSituacao, 0);
      $oLicitacao                 = new licitacao($oDadoSituacao->l11_liclicita);
      $oRetorno->l11_obs          = urlencode($oDadoSituacao->l11_obs);
      $oRetorno->l11_sequencial   = $oDadoSituacao->l11_sequencial;
      $oRetorno->iCodigoEdital    = $oLicitacao->getEdital();
      $oRetorno->iCodigoLicitacao = $oDadoSituacao->l11_liclicita;

      break;


    case "cancelar":

      $iCodigoLicitacao    = $oParam->iCodigoLicitacao;
      $sObservacao         = '';
      if (isset($sObservacao)) {
        $sObservacao       = $oParam->sObservacao;
      }
      $oLicitacao = new licitacao($iCodigoLicitacao);
      $oLicitacao->alterarSituacao(0,$sObservacao);
      $oRetorno->message = "Situação cancelada com sucesso.";
      break;

    case 'getDataAjudicacao':

      if (empty($oParam->iCodigoLicitacao)) {
        throw new ParameterException("Código da Licitação não informado.");
      }
      $oLicitacao = new licitacao($oParam->iCodigoLicitacao);
      $mData = $oLicitacao->getDataAjudicacao();
      if (!empty($mData)) {
        $mData = $mData->getDate();
      }
      $oRetorno->data = $mData;
      break;

    case 'getDataHomologacao':

      if (empty($oParam->iCodigoLicitacao)) {
        throw new ParameterException("Código da Licitação não informado.");
      }
      $oLicitacao = new licitacao($oParam->iCodigoLicitacao);
      $mData = $oLicitacao->getDataHomologacao();
      if (!empty($mData)) {
        $mData = $mData->getDate();
      }
      $oRetorno->data = $mData;
      break;

    case 'excluir':

      if (empty($oParam->iCodigoLicitacao)) {
        throw new ParameterException("Código da Licitação não informado.");
      }

      if (empty($oParam->iSituacao)) {
        throw new ParameterException("Código da Situação não informado.");
      }

      $oLicitacao = new licitacao($oParam->iCodigoLicitacao);

      $oDaoLicLicita = new cl_liclicita();
      $oDaoLicLicita->l20_codigo      = $oParam->iCodigoLicitacao;
      $oDaoLicLicita->l20_licsituacao = (string)SituacaoLicitacao::SITUACAO_JULGADA;

      if ($oParam->iSituacao == SituacaoLicitacao::SITUACAO_ADJUDICADA && $oLicitacao->getDataHomologacao()) {
        $oDaoLicLicita->l20_licsituacao = (string)SituacaoLicitacao::SITUACAO_HOMOLOGADA;
      }

      if ($oParam->iSituacao == SituacaoLicitacao::SITUACAO_HOMOLOGADA && $oLicitacao->getDataAjudicacao()) {
        $oDaoLicLicita->l20_licsituacao = (string)SituacaoLicitacao::SITUACAO_ADJUDICADA;
      }

      $oDaoLicLicita->alterar($oDaoLicLicita->l20_codigo);
      if ($oDaoLicLicita->erro_status == "0") {
        throw new Exception("Não foi possível alterar a situação da Licitação.");
      }

      $oDaoLicitacaoSituacao = new cl_liclicitasituacao();
      $oDaoLicitacaoSituacao->excluir(null, "l11_liclicita = {$oParam->iCodigoLicitacao} and l11_licsituacao = {$oParam->iSituacao}");
      if ($oDaoLicitacaoSituacao->erro_status == "0") {
        throw new Exception("Não foi possível excluir situação da Licitação.");
      }
      $oRetorno->message = "Situação excluída com sucesso.";
      break;

  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  $oRetorno->message = $eErro->getMessage();
  $oRetorno->status  = 2;
  $oRetorno->erro    = true;
  db_fim_transacao(true);
}

$oRetorno->message = urlencode(str_replace("\\n","\n",$oRetorno->message));
echo JSON::create()->stringify($oRetorno);