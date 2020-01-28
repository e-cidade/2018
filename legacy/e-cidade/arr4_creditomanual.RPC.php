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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/arrecadacao/Credito.model.php"));
require_once(modification("model/arrecadacao/CreditoManual.model.php"));
require_once(modification("model/arrecadacao/RegraCompensacao.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("libs/exceptions/FileException.php"));
require_once(modification("model/recibo.model.php"));
require_once(modification("model/processoProtocolo.model.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$lErro                  = false;

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

switch ($oParam->sExec) {

  case "novoCredito":

    /**
     * Salva os dados no banco
     */

    db_inicio_transacao();

    try {

      if (!empty($oParam->vinculo->codigo_inscricao) && !empty($oParam->vinculo->codigo_matricula)) {
        throw new ParameterException("Você não pode vincular o crédito em uma matricula e inscrição ao mesmo tempo.");
      }

      $oCreditoManual = new CreditoManual();
      $oCreditoManual->adicionarRegra     (new RegraCompensacao($oParam->iCodigoRegraCompensacao));
      $oCreditoManual->setDataLancamento  (new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
      $oCreditoManual->setHora            (date('H:i'));
      $oCreditoManual->setUsuario         (db_getsession('DB_id_usuario'));
      $oCreditoManual->setInstituicao     (db_getsession('DB_instit'));
      $oCreditoManual->setValor           ($oParam->fValor);
      $oCreditoManual->setObservacao      (db_stdClass::normalizeStringJson($oParam->sObservacao));
      $oCreditoManual->setPercentual      (100);
      if (!empty($oParam->iCodigoCgm)) {
        $oCreditoManual->setCgm             (CgmFactory::getInstanceByCgm($oParam->iCodigoCgm));
      }
      $oCreditoManual->setCodigoInscricao($oParam->vinculo->codigo_inscricao);
      $oCreditoManual->setCodigoMatricula($oParam->vinculo->codigo_matricula);



      if (!empty($oParam->lProcessoSistema)) {

        $oCreditoManual->setProcessoSistema ($oParam->lProcessoSistema == 'S' ? true : false);

        if ($oCreditoManual->isProcessoSistema()) {

        	$oCreditoManual->setProcessoProtocolo(new processoProtocolo($oParam->iCodigoProcessoSistema));

        } else {

        	$oCreditoManual->setNumeroProcessoExterno     ($oParam->sNumeroProcessoExterno);
        	$oCreditoManual->setNomeTitularProcessoExterno(db_stdClass::normalizeStringJson($oParam->sNomeTitularProcessoExterno));

        	if (!empty($oParam->dDataProcessoExterno)) {
        	  $oCreditoManual->setDataProcessoExterno       (new DBDate($oParam->dDataProcessoExterno));
        	}

        }

      }

      if (!empty($oParam->dDataExpiracao)) {

      	$oCreditoManual->setDataExpiracao (new DBDate($oParam->dDataExpiracao));
      }

      $oCreditoManual->salvar();

    } catch (Exception $oErro) {  //Exception

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode($oErro->getMessage());
      $lErro              = true;
    }

    db_fim_transacao($lErro);

    break;

  case 'calculaDataExpiracao':

    $oRetorno->dDataExpiracao = '';

    $oRegraCompensacao        = new RegraCompensacao($oParam->iCodigoRegraCompensacao);

    if ($oParam->dDataLancamento != '') {
      $oRegraCompensacao->setDataLancamento(new DBDate($oParam->dDataLancamento));
    }


    if ($oRegraCompensacao->getDataValidade() instanceof DBDate) {

      $oRetorno->dDataExpiracao = $oRegraCompensacao->getDataValidade()->getDate(DBDate::DATA_PTBR);
    }

    break;

}

echo $oJson->encode($oRetorno);