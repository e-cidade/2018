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

$oParam = JSON::create()->parse(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->message  = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "confirmarGeracao":

      if (empty($oParam->sData)) {
        throw new Exception("Data de Geração não informada.");
      }

      $oDataAtual = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
      $oDataEncerramento = new DBDate($oParam->sData);
      $oInstituicao = new Instituicao( db_getsession("DB_instit") );

      $oEncerramento = new EncerramentoLicitacon($oDataAtual, $oInstituicao);
      $oEncerramento->encerrar($oDataEncerramento);

      break;

    default:

      throw new Exception("Nenhuma opção definida.");
      break;
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  $oRetorno->erro = true;
  $oRetorno->message = urlencode($e->getMessage());

  db_fim_transacao(true);
}

echo JSON::create()->stringify($oRetorno);