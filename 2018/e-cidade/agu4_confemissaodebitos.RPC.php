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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

use ECidade\Tributario\Agua\EmissaoCarnes\ConfiguracaoDebitos;

$oParam   = JSON::create()->parse(stripslashes($_POST['json']));
$oRetorno = (object) array(
  'message' => '',
  'erro'    => false
);

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvar":

      $oConfiguracao = new ConfiguracaoDebitos();

      if ($oParam->iCgm) {
        $oConfiguracao->setCgm(CgmFactory::getInstanceByCgm((int) $oParam->iCgm));
      }

      if ($oParam->iContrato) {
        $oConfiguracao->setContrato(new AguaContrato((int) $oParam->iContrato));
      }

      if ($oParam->iEconomia) {

        $oEconomia = new AguaContratoEconomia();
        $oEconomia->carregar((int) $oParam->iEconomia);

        $oConfiguracao->setEconomia($oEconomia);
      }

      $oConfiguracao->salvar();

      $oRetorno->message = "Configuração salva com sucesso.";

      break;

    case "carregar":

      $oRetorno->configuracao = null;

      $oConfiguracao = new ConfiguracaoDebitos;

      if ($oConfiguracao->carregar((int) $oParam->iCgm)) {

        $oRetorno->configuracao = new stdClass;

        $oCgmContrato = CgmFactory::getInstanceByCgm($oConfiguracao->getContrato()->getCodigoCgm());

        $oRetorno->configuracao->iContratoCodigo    = $oConfiguracao->getContrato()->getCodigo();
        $oRetorno->configuracao->sContratoDescricao = $oCgmContrato->getNome();

        $oRetorno->configuracao->iEconomiaCodigo    = null;
        $oRetorno->configuracao->sEconomiaDescricao = null;

        if ($oConfiguracao->getEconomia()) {

          $oCgmEconomia = CgmFactory::getInstanceByCgm($oConfiguracao->getEconomia()->getCodigoCgm());

          $oRetorno->configuracao->iEconomiaCodigo    = $oConfiguracao->getEconomia()->getCodigo();
          $oRetorno->configuracao->sEconomiaDescricao = $oCgmEconomia->getNome();
        }
      }

      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao();
} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->message = $exception->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
