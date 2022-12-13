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

/**
 * @author $Author: dbmauricio $
 * @version $Revision: 1.6 $
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

define("MENSAGENS", "patrimonial.compras.com4_processocompra.");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecutar) {

    case "getDadosProcesso":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;

      if (empty($iSequencialProcessoCompra)) {
        throw new BusinessException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oRetorno->lLicitacao = false;
      $oRetorno->lOrcamento = false;

      /**
       * Verifica se o processo de compra possui algum item na licitação
       */
      $oDAOLiclicitem  = new cl_liclicitem();
      $sQueryLicitacao = $oDAOLiclicitem->sql_query(null, "*", null, "pc80_codproc = {$iSequencialProcessoCompra} limit 1");

      $oDAOLiclicitem->sql_record($sQueryLicitacao);
      if ($oDAOLiclicitem->numrows > 0) {
        $oRetorno->lLicitacao = true;
      }

      /**
       * Verifica se o processo de compras possui algum item em um orçamento
       */
      $oDaoOrcamentoItem = new cl_pcorcamitemproc();
      $sQueryOrcamento   = $oDaoOrcamentoItem->sql_query(null, null, "*", null, "pc80_codproc = {$iSequencialProcessoCompra} limit 1");

      $oDaoOrcamentoItem->sql_record($sQueryOrcamento);
      if ($oDaoOrcamentoItem->numrows > 0) {
        $oRetorno->lOrcamento = true;
      }

      $oProcessoCompra = new ProcessoCompras($iSequencialProcessoCompra);
      $oRetorno->pc80_codproc = $oProcessoCompra->getCodigo();
      $oRetorno->pc80_data =  $oProcessoCompra->getDataEmissao();
      $oRetorno->id_usuario = $oProcessoCompra->getUsuario();
      $oRetorno->nome = urlencode($oProcessoCompra->getNomeUsuario());
      $oRetorno->coddepto = $oProcessoCompra->getCodigoDepartamento();
      $oRetorno->descrdepto = urlencode($oProcessoCompra->getDescricaoDepartamento());
      $oRetorno->pc80_resumo = urlencode($oProcessoCompra->getResumo());
      $oRetorno->pc80_tipoprocesso = $oProcessoCompra->getTipoProcesso();

      $aLotes = array();
      foreach ($oProcessoCompra->getLotes() as $oLotesProcessoCompra) {
        $aLotes[$oLotesProcessoCompra->getCodigo()] = $oLotesProcessoCompra->getNome();
      }
      $oRetorno->aLotes = $aLotes;

      break;

    case "getItens":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;

      if (empty($iSequencialProcessoCompra)) {
        throw new  BusinessException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oProcessoCompra = new ProcessoCompras($iSequencialProcessoCompra);

      $aItens          = array();
      foreach ($oProcessoCompra->getItens() as $oItemProcessoCompra) {

        $iUnidadeMedida      = $oItemProcessoCompra->getItemSolicitacao()->getUnidade();
        $oLoteProcessoCompra = $oItemProcessoCompra->getLote();

        $aItem = array(
          'codigo_item'        => $oItemProcessoCompra->getCodigo(),
          'solicitacao'        => $oItemProcessoCompra->getItemSolicitacao()->getCodigoSolicitacao(),
          'sequencial'         => $oItemProcessoCompra->getItemSolicitacao()->getOrdem(),
          'codigo_material'    => $oItemProcessoCompra->getItemSolicitacao()->getCodigoMaterial(),
          'descricao_material' => $oItemProcessoCompra->getItemSolicitacao()->getDescricaoMaterial(),
          'unidade'            => ($iUnidadeMedida ? itemSolicitacao::getDescricaoUnidade($iUnidadeMedida) : ''),
          'quantidade'         => $oItemProcessoCompra->getItemSolicitacao()->getQuantidade(),
          'vlr_unitario'       => $oItemProcessoCompra->getItemSolicitacao()->getValorUnitario(),
          'vlr_total'          => $oItemProcessoCompra->getItemSolicitacao()->getValorTotal(),
          'lote'               => (!empty($oLoteProcessoCompra) ? $oLoteProcessoCompra->getCodigo() : '')
        );
        $aItens[] = $aItem;
      }

      $oRetorno->aItens = $aItens;

      break;

    case "salvar":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;
      $aLotes                    = $oParam->aItens;
      $sResumo                   = db_stdClass::normalizeStringJsonEscapeString($oParam->sResumo);

      if (empty($iSequencialProcessoCompra)) {
        throw new DBException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oProcessoCompra = new ProcessoCompras($iSequencialProcessoCompra);
      $oProcessoCompra->setResumo($sResumo);

      foreach ($aLotes as $oStdLote) {

        $oLote = $oProcessoCompra->getLotePorCodigo($oStdLote->lote);
        if (empty($oLote)) {
          throw new BusinessException(_M(MENSAGENS.'lote_nao_encontrado'));
        }
        $oLote->removerItens();
        foreach ($oStdLote->itens as $iCodigoItem) {

          $oItem = $oProcessoCompra->getItemPorCodigo($iCodigoItem);
          if (empty($oItem)) {
            throw new BusinessException(_M(MENSAGENS.'item_nao_encontrado'));
          }
          $oLote->adicionarItem($oItem);
        }
      }
      $oProcessoCompra->salvar();
      break;

    case "excluir":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;

      if (empty($iSequencialProcessoCompra)) {
        throw new  BusinessException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oProcessoCompra = new ProcessoCompras($iSequencialProcessoCompra);
      $oProcessoCompra->remover();

      break;

    case "incluirLote":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;
      $sNomeLote                 = db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricao);

      if (empty($iSequencialProcessoCompra)) {
        throw new  BusinessException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oProcessoCompra     = new ProcessoCompras($iSequencialProcessoCompra);
      $oLoteProcessoCompra = $oProcessoCompra->adicionarLote($sNomeLote);
      $oLoteProcessoCompra->salvar();

      $oRetorno->iCodigoLote = $oLoteProcessoCompra->getCodigo();

      break;

    case "removerLote":

      $iSequencialProcessoCompra = $oParam->iProcessoCompra;
      $iCodigoLote               = $oParam->iCodigoLote;

      if (empty($iSequencialProcessoCompra)) {
        throw new  BusinessException(_M(MENSAGENS . "nao_informado_processo_compra"));
      }

      $oProcessoCompra = new ProcessoCompras($iSequencialProcessoCompra);
      $oLoteProcesso   = $oProcessoCompra->getLotePorCodigo($iCodigoLote);

      if ($oLoteProcesso) {
        $oLoteProcesso->remover();
      }

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);