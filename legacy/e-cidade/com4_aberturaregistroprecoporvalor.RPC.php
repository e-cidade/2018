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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
define('MENSAGEM', 'patrimonial.compras.com4_aberturaregistroprecoporvalor.');

$oParametro = JSON::create()->parse(str_replace("\\", '', $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->mensagem = '';

try {

  switch ($oParametro->exec) {

    case 'salvarAbertura':

      db_inicio_transacao();

      $oDataInicial = new DBDate($oParametro->dtInicial);
      $oDataFinal   = new DBDate($oParametro->dtFinal);
      $oSolicita = new aberturaRegistroPreco($oParametro->iCodigo);
      $oSolicita->setLiberado($oParametro->lLiberado == 't' ? true : false);
      $oSolicita->setResumo(db_stdClass::normalizeStringJsonEscapeString($oParametro->sResumo));
      $oSolicita->setDataInicio($oDataInicial->getDate(DBDate::DATA_PTBR));
      $oSolicita->setDataTermino($oDataFinal->getDate(DBDate::DATA_PTBR));
      $oSolicita->setFormaDeControle(aberturaRegistroPreco::CONTROLA_VALOR);
      $oSolicita->save();
      $oRetorno->mensagem = _M(MENSAGEM.'abertura_salva');
      $oRetorno->iCodigoSolicitacao = $oSolicita->getCodigoSolicitacao();
      db_fim_transacao(false);

      break;

    case 'salvarItem':

      db_inicio_transacao();
      $oItemNovo = new itemSolicitacao($oParametro->iCodigoItemSolicitacao, $oParametro->iCodigoItem);
      $oItemNovo->setResumo(db_stdClass::normalizeStringJsonEscapeString($oParametro->sResumo));
      $oItemNovo->setJustificativa(db_stdClass::normalizeStringJsonEscapeString($oParametro->sJustificativa));
      $oItemNovo->setPagamento(db_stdClass::normalizeStringJsonEscapeString($oParametro->sPagamento));
      $oItemNovo->setPrazos(db_stdClass::normalizeStringJsonEscapeString($oParametro->sPrazo));
      $oItemNovo->setQuantidadeUnidade(1);
      $oItemNovo->setUnidade(1);
      $oItemNovo->setValorUnitario($oParametro->nValor);
      $oItemNovo->setValorTotal($oParametro->nValor);
      $oItemNovo->setQuantidade(1);
      $oItemNovo->save($oParametro->iCodigoSolicitacao);
      $oRetorno->mensagem = _M(MENSAGEM.'item_salvo');

      $oSolicita = new aberturaRegistroPreco($oParametro->iCodigoSolicitacao);
      $aItens = $oSolicita->getItens();
      $iCodigoSequencialItem = 1;
      foreach ($aItens as $oItem) {

        $oItem->setOrdem($iCodigoSequencialItem);
        $oItem->setUnidade(1);
        $oItem->setQuantidadeUnidade(1);
        $oItem->save();
        $iCodigoSequencialItem++;
      }
      db_fim_transacao(false);
      break;

    case 'getItens':

      $oSolicita = new aberturaRegistroPreco($oParametro->iCodigoSolicitacao);
      $aItens = $oSolicita->getItens();
      $lPossuiEstimativas = false;
      if ( $oSolicita->possuiEstimativaValida() ) {
        $lPossuiEstimativas = true;
      }
      $aItensRetorno = array();
      foreach ($aItens as $oItem) {

        $oStdItem = new stdClass();
        $oStdItem->codigo_item      = $oItem->getCodigoItemSolicitacao();
        $oStdItem->codigo_servico   = $oItem->getCodigoMaterial();
        $oStdItem->descricao        = $oItem->getDescricaoMaterial();
        $oStdItem->valor            = $oItem->getValorUnitario();
        $oStdItem->possuiEstimativa = $lPossuiEstimativas;
        $aItensRetorno[]            = $oStdItem;
      }
      $oRetorno->aItens = $aItensRetorno;
      break;

    case 'getInformacoesItem':

      $oItemNovo = new itemSolicitacao($oParametro->iCodigoItemSolicitacao);
      $oStdItem  = new stdClass();
      $oStdItem->codigo = $oParametro->iCodigoItemSolicitacao;
      $oStdItem->codigo_material = $oItemNovo->getCodigoMaterial();
      $oStdItem->descricao_material = $oItemNovo->getDescricaoMaterial();
      $oStdItem->valor = $oItemNovo->getValorUnitario();
      $oStdItem->resumo = $oItemNovo->getResumo();
      $oStdItem->justificativa = $oItemNovo->getJustificativa();
      $oStdItem->pagamento = $oItemNovo->getPagamento();
      $oStdItem->prazo = $oItemNovo->getPrazos();
      $oRetorno->oItem = $oStdItem;
      break;

    case 'excluirItem':

      db_inicio_transacao();
      $oSolicita = new aberturaRegistroPreco($oParametro->iCodigoSolicitacao);
      $oSolicita->removerItemVinculadoNoItemDaAbertura($oParametro->iCodigoItemSolicitacao);
      $oItemSolicitacao = new itemSolicitacao($oParametro->iCodigoItemSolicitacao);
      $oItemSolicitacao->remover();

      $aItens = $oSolicita->getItens();

      $iCodigoSequencialItem = 1;
      foreach ($aItens as $oItem) {

        $oItem->setOrdem($iCodigoSequencialItem);
        $oItem->setUnidade(1);
        $oItem->setQuantidadeUnidade(1);
        $oItem->save();
        $iCodigoSequencialItem++;
      }
      $oRetorno->mensagem = _M(MENSAGEM."item_excluido");
      db_fim_transacao(false);

      break;

    case 'getDadosSolicitacao':

      $oSolicitacao = new aberturaRegistroPreco($oParametro->iCodigoSolicitacao);
      $oDataInicial = new DBDate($oSolicitacao->getDataInicio());
      $oDataFinal   = new DBDate($oSolicitacao->getDataTermino());
      $oRetorno->oSolicitacao = new stdClass();
      $oRetorno->oSolicitacao->dtInicial = $oDataInicial->getDate(DBDate::DATA_PTBR);
      $oRetorno->oSolicitacao->dtFinal   = $oDataFinal->getDate(DBDate::DATA_PTBR);
      $oRetorno->oSolicitacao->lLiberado = $oSolicitacao->isLiberado();
      $oRetorno->oSolicitacao->sResumo   = urlencode($oSolicitacao->getResumo());
      break;

    default:
      throw new Exception("RPC não preparado para executar: {$oParametro->exec}.");


  }

} catch (Exception $eErro) {

  $oRetorno->mensagem = $eErro->getMessage();
  $oRetorno->erro = true;
  db_fim_transacao(true);
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);