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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\Tributario\Agua\DebitoConta\DebitoContaFactory;

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  "message" => '',
  "erro" => false
);

$oFactory = new DebitoContaFactory();
$oService = $oFactory->build();

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "carregar":

      $oPedido = $oService->carregar((int) $oParam->iCodigo);

      $oBanco = db_utils::getRowFromDao(new cl_bancos, array($oPedido->getBanco()));

      $oRetorno->oPedido = (object) array(
        'iContrato' => $oPedido->getContrato() ? $oPedido->getContrato()->getCodigo() : $oPedido->getEconomia()->getContrato()->getCodigo(),
        'sContratoDescricao' => $oPedido->getContrato() ? $oPedido->getContrato()->getCgm()->getNome() : $oPedido->getEconomia()->getContrato()->getCgm()->getNome(),
        'iEconomia' => $oPedido->getEconomia() ? $oPedido->getEconomia()->getCodigo() : null,
        'sEconomiaDescricao' => $oPedido->getEconomia() ? $oPedido->getEconomia()->getContrato()->getCgm()->getNome() : null,
        'iBanco' => $oBanco->codbco,
        'sBancoDescricao' => $oBanco->nomebco,
        'iAgencia' => $oPedido->getAgencia(),
        'iConta' => $oPedido->getConta(),
        'iStatus' => $oPedido->getStatus(),
        'iIdEmpresa' => $oPedido->getIdEmpresa()
      );

      break;

    case "salvar":

      $oPedido = $oService->salvar($oParam);
      $oRetorno->iCodigo = $oPedido->getCodigo();
      $oRetorno->message = "Débito em conta salvo com sucesso.";

      break;

    default:
      throw new Exception("Opção é inválida.");

  }

  db_fim_transacao();
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->message = $oErro->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
