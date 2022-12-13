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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam             = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

define('PAGAMENTO_DOC', '1');
define('PAGAMENTO_TED', '2');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvarFormaPagamento":

      foreach ($oParam->aFormasPagamento as $oStdDados) {

        if ($oStdDados->iFormaPagamento == 'NA') {
          continue;
        }

        if (!in_array($oStdDados->iFormaPagamento, array(PAGAMENTO_DOC, PAGAMENTO_TED))) {
          throw new ParameterException('A forma de pagamento informada � inv�lida.');
        }

        $oDaoFormaPagamento = new cl_empagemovformapagamento;
        $oDaoFormaPagamento->excluir(null, "e07_empagemov = {$oStdDados->iCodigoMovimento}");
        if ($oDaoFormaPagamento->erro_status == '0') {
          throw new DBException('N�o foi poss�vel salvar a forma de pagamento.');
        }

        $oDaoFormaPagamento->e07_formatransmissao = $oStdDados->iFormaPagamento;
        $oDaoFormaPagamento->e07_empagemov        = $oStdDados->iCodigoMovimento;
        $oDaoFormaPagamento->incluir(null);
        if ($oDaoFormaPagamento->erro_status == '0') {
          throw new DBException('N�o foi poss�vel salvar a forma de pagamento.');
        }

      }

      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $oErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
