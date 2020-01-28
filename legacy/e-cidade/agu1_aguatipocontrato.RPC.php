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

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  "mensagem" => '',
  "erro" => false
);

try {
  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Salvar Tipo de Contrato
     */
    case 'salvar':

      $oTipoContrato = new AguaTipoContrato();
      $oTipoContrato->setDescricao($oParam->sDescricao);

      if (!empty($oParam->iCodigo)) {
        $oTipoContrato->setCodigo((integer) $oParam->iCodigo);
      }

      $oTipoContrato->salvar();

      $oRetorno->oTipoContrato = (object) array(
        'iCodigo' => $oTipoContrato->getCodigo(),
        'sDescricao' => $oTipoContrato->getDescricao()
      );

      $oRetorno->mensagem = 'Tipo de Contrato salvo com sucesso.';
      break;

    /**
     * Carregar Tipo de Contrato
     */
    case 'carregar':

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oTipoContrato = new AguaTipoContrato((integer) $oParam->iCodigo);

      $oRetorno->oTipoContrato = (object) array(
        'iCodigo' => $oTipoContrato->getCodigo(),
        'sDescricao' => $oTipoContrato->getDescricao()
      );
      break;

    /**
     * Excluir Tipo de Contrato
     */
    case 'excluir':

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código do Tipo de Contrato não informado.');
      }

      $oTipoContrato = new AguaTipoContrato((integer) $oParam->iCodigo);
      $oTipoContrato->excluir();

      $oRetorno->mensagem = 'Tipo de Contrato excluído com sucesso.';
      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao();
} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->mensagem = $exception->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
