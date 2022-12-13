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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("model/itemSolicitacao.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/licitacao.model.php"));
require_once(modification("classes/solicitacaocompras.model.php"));
require_once(modification("model/empenho/AutorizacaoEmpenho.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/ProcessoCompras.model.php"));
require_once(modification("classes/db_pcproc_classe.php"));


$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {

  case "getTipoLicitacao":

    $oDaoCfgLiclicita          = db_utils::getDao("cflicita");
    $sSqlTipoLicitacao         = $oDaoCfgLiclicita->sql_query_file(null,"l03_tipo, l03_descr", '', "l03_codcom = {$oParam->iTipoCompra}");
    $rsTipoLicitacao           = $oDaoCfgLiclicita->sql_record($sSqlTipoLicitacao);
    $oRetorno->aTiposLicitacao = array();

    if ($oDaoCfgLiclicita->numrows > 0) {

      for ($iTipoLicitacao = 0; $iTipoLicitacao < $oDaoCfgLiclicita->numrows; $iTipoLicitacao++) {
        $oRetorno->aTiposLicitacao[] = db_utils::fieldsMemory($rsTipoLicitacao, $iTipoLicitacao);
      }
    }

  break;

  /**
   * Busca os itens de uma solicitação de compra para que seja feita a geração de empenho
   */
  case "getItensParaAutorizacao":
    try {

      $oSolicitacao      = new solicitacaoCompra($oParam->iCodigo);
      $aItensSolicitacao = $oSolicitacao->getItensParaAutorizacao();
      $oRetorno->aItens  = array();
      foreach ($aItensSolicitacao as $oStdItem) {

        $oStdItem->fornecedor = urlencode($oStdItem->fornecedor);
        $oRetorno->aItens[] = $oStdItem;
      }

    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
  break;

  /**
   * Gera autorização de empenho para os itens selecionados
   */
  case "gerarAutorizacoes":

    try {

      /**
       * corrigimos as strings antes de salvarmos os dados
       */
      foreach ($oParam->aAutorizacoes as $oAutorizacao) {

        $oAutorizacao->destino           = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->destino))));
        $oAutorizacao->sContato          = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sContato))));
        $oAutorizacao->sOutrasCondicoes  = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->sOutrasCondicoes))));
        $oAutorizacao->condicaopagamento = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->condicaopagamento))));
        $oAutorizacao->prazoentrega      = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oAutorizacao->prazoentrega))));
        $oAutorizacao->resumo            = addslashes(db_stdClass::db_stripTagsJson(str_replace("<quebralinha>", "\n", addslashes(utf8_decode(urldecode($oAutorizacao->resumo))))));

        foreach ($oAutorizacao->itens as $oItem) {
          $oItem->observacao = addslashes(utf8_decode(db_stdClass::db_stripTagsJson(urldecode($oItem->observacao))));
        }
      }

      db_inicio_transacao();
      $oSolicitacaoCompra     = new solicitacaoCompra($oParam->iCodigo);
      $oRetorno->autorizacoes = $oSolicitacaoCompra->gerarAutorizacoes($oParam->aAutorizacoes);
      db_fim_transacao(false);

      $oRetorno->status  = 1;
      $oRetorno->message = urlencode("Autorização efetuada com sucesso.");

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }

  break;
}


echo $oJson->encode($oRetorno);
?>