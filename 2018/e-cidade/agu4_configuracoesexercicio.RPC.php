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

use ECidade\Tributario\Agua\Configuracao;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

$oParametros = JSON::create()->parse(stripslashes($_POST['json']));
$oRetorno = (object) array(
  'message' => '',
  'erro'    => false
);

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case "salvar":

      $oConfiguracao = Configuracao::create();
      $oConfiguracao->setCodigoCaracteristicaSemAgua((int)$oParametros->iSemAgua);
      $oConfiguracao->setCodigoCaracteristicaSemEsgoto((int)$oParametros->iSemEsgoto);
      $oConfiguracao->setCodigoTipoDebito((int)$oParametros->iTipoDebito);
      $oConfiguracao->salvar();

      $oRetorno->message = "Configurações salvas com sucesso.";

      break;

    case "carregar":


      $oConfiguracao = Configuracao::create();

      $oRetorno->iTipoDebito = null;
      $oRetorno->sTipoDebito = null;
      $oRetorno->iSemEsgoto = null;
      $oRetorno->sSemEsgoto = null;
      $oRetorno->iSemAgua = null;
      $oRetorno->sSemAgua = null;
      $oRetorno->iAno = null;


      if ($oConfiguracao->getTipoDebito()) {

        $oRetorno->iTipoDebito = $oConfiguracao->getTipoDebito()->getCodigo();
        $oRetorno->sTipoDebito = $oConfiguracao->getTipoDebito()->getDescricao();
      }

      if ($oConfiguracao->getCaracteristicaSemEsgoto()) {

        $oRetorno->iSemEsgoto = $oConfiguracao->getCaracteristicaSemEsgoto()->getCodigo();
        $oRetorno->sSemEsgoto = $oConfiguracao->getCaracteristicaSemEsgoto()->getDescricao();
      }

      if ($oConfiguracao->getCaracteristicaSemAgua()) {

        $oRetorno->iSemAgua = $oConfiguracao->getCaracteristicaSemAgua()->getCodigo();
        $oRetorno->sSemAgua = $oConfiguracao->getCaracteristicaSemAgua()->getDescricao();
      }

      $oRetorno->iAno = $oConfiguracao->getAno();

      break;

    default:

      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao($lErro = false);

} catch (Exception $oErro) {

  db_fim_transacao($lErro = true);

  $oRetorno->message = $oErro->getMessage();
  $oRetorno->erro = $lErro;
}

echo JSON::create()->stringify($oRetorno);
