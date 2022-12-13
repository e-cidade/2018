<?php
/**
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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");


$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->lErro      = false;
$oRetorno->sMessage   = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getParametros":

      $oMensageriaLicenca = new MensageriaLicenca();

      try {
        $aDestinatarioMensagem = MensageriaLicencaUsuarioRepository::getColecaoMensageriaLicencaUsuario();
      } catch(Exception $oErro) {
        $aDestinatarioMensagem = array();
      }

      $aDiasRetorno             = array();
      $aDestinatarioRetorno     = array();
      $aDestinatariosJaInclusos = array();
      foreach ($aDestinatarioMensagem as $oMensageriaLicencaUsuario) {

        if ( !in_array($oMensageriaLicencaUsuario->getDias(), $aDiasRetorno) ) {
          $aDiasRetorno[] = $oMensageriaLicencaUsuario->getDias();
        }

        if ( !in_array($oMensageriaLicencaUsuario->getUsuario()->getIdUsuario(), $aDestinatariosJaInclusos )) {

          $aDestinatariosJaInclusos[]  = $oMensageriaLicencaUsuario->getUsuario()->getIdUsuario();
          $oStdUsuario                 = new stdClass();
          $oStdUsuario->iCodigoUsuario = $oMensageriaLicencaUsuario->getUsuario()->getIdUsuario();
          $oStdUsuario->sNomeUsuario   = urlencode($oMensageriaLicencaUsuario->getUsuario()->getNome());
          $aDestinatarioRetorno[]      = $oStdUsuario;
        }
      }

      $oRetorno->sAssunto  = urlencode($oMensageriaLicenca->getAssunto());
      $oRetorno->sMensagem = urlencode($oMensageriaLicenca->getMensagem());
      $oRetorno->aDias     = $aDiasRetorno;
      $oRetorno->aUsuarios = $aDestinatarioRetorno;

    break;

    case "salvarParametros":

      $aDiasTela         = $oParam->aDias; //array(20, 40, 50, 120, 365);
      $aUsuarioTela      = $oParam->aUsuarios; //array(1,2,3,4);

      $sAssunto  = addslashes(db_stdClass::normalizeStringJson($oParam->sAssunto));
      $sMensagem = addslashes(db_stdClass::normalizeStringJson($oParam->sMensagem));

      $oMensageriaLicenca = new MensageriaLicenca();
      $oMensageriaLicenca->setAssunto($sAssunto);
      $oMensageriaLicenca->setMensagem($sMensagem);

      $oProcessamento = new MensageriaLicencaProcessado();
      $oProcessamento->setMensageriaLicenca($oMensageriaLicenca);
      $oProcessamento->setDiasAviso($aDiasTela);
      foreach ($aUsuarioTela as $iCodigoUsuario) {
        $oProcessamento->adicionarUsuario(UsuarioSistemaRepository::getPorCodigo($iCodigoUsuario));
      }
      $oProcessamento->processarParametros();
      $oRetorno->sMessage = urlencode("Parâmetros salvos com sucesso.");

      /**
       * Cria arquivo xml com configuracoes de execucao diaria
       */
      $job = new Job();
      $job->setNome('MensageriaLicenca');
      $job->setCodigoUsuario(1);
      $job->setDescricao('Mensageria licenca');
      $job->setNomeClasse('MensageriaLicencaProcessamentoTask');
      $job->setTipoPeriodicidade(Agenda::PERIODICIDADE_DIARIA);
      $job->adicionarPeriodicidade('0600');
      $job->setCaminhoPrograma('model/meioambiente/mensageria/MensageriaLicencaProcessamentoTask.model.php');
      $job->salvar();

      db_fim_transacao(false);
    break;
  }

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->lErro    = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);