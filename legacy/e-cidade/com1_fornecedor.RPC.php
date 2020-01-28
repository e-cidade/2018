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

$oParam            = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass();
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Verifica se o CGM passado é um fornecedor
     */
    case "verificaFornecedor":

      $iCgm = (int) $oParam->iCgm;

      if (empty($oParam->iCgm)) {
        throw new Exception("Código CGM não informado.");
      }

      $oDaoFornecedor = new cl_pcforne();
      $sSqlFornecedor = $oDaoFornecedor->sql_query_file($iCgm);
      $rsFornecedor   = $oDaoFornecedor->sql_record("{$sSqlFornecedor} limit 1");

      $oRetorno->lFornecedor = false;

      if ($rsFornecedor && $oDaoFornecedor->numrows > 0) {
        $oRetorno->lFornecedor = true;
      }

      break;

  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($e->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);