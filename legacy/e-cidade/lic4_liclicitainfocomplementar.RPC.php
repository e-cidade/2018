<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

use ECidade\Patrimonial\Licitacao\Modalidade\Fundamentacao\Factory as DeparaFactory;

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

define("MENSAGENS", "arquivo-de-mensagens.json");

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "buscarFundamentacaoPorModalidade":

      $oDaoCflicita = new cl_cflicita();
      $oSqlCflicita = $oDaoCflicita->sql_query_file($oParametros->iModalidade, "l03_pctipocompratribunal");
      $rsCflicita   = $oDaoCflicita->sql_record($oSqlCflicita);

      if ( !$rsCflicita ) {
        throw new \DBException("Erro ao buscar o Código Tribunal da Modalidade informada.");
      }

      $oCflicita  = db_utils::fieldsMemory($rsCflicita, 0);

      $oDeparaFactory = new DeparaFactory();
      $oModalidade    = $oDeparaFactory->getModalidadeDepara($oCflicita->l03_pctipocompratribunal);

      $oRetorno->aFundamentacoes = $oModalidade->getFundamentacoes();

      break;
  }

  db_fim_transacao(false);


} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);