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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));
require_once (modification("classes/solicitacaocompras.model.php"));

/**
 *
 * @todo  criar fonte de mensagem
 */


$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getItens":

      if ( empty($oParam->iLicitacao) ) {
        throw new ParameterException("Licitação não foi informada");
      }

      $oLicitacao = new \licitacao($oParam->iLicitacao);
      $aItens     = $oLicitacao->getItens();

      $oRetorno->iLicitacao   = $oParam->iLicitacao;
      $oRetorno->iProcesso    = null;
      $oRetorno->iSolicitacao = null;
      $oRetorno->aItens       = array();
      foreach ($aItens as $oItemLicitacao) {

        $oItemSolicitacao = $oItemLicitacao->getItemSolicitacao();

        $oRetorno->iProcesso    = $oItemLicitacao->getProcessoCompra();
        $oRetorno->iSolicitacao = $oItemSolicitacao->getCodigoSolicitacao();

        $oStdItem                   = new \stdClass();
        $oStdItem->iId              = $oItemSolicitacao->getOrdem() . '#' . $oItemSolicitacao->getCodigoMaterial();
        $oStdItem->iMaterial        = $oItemSolicitacao->getCodigoMaterial();
        $oStdItem->sMaterial        = urldecode($oItemSolicitacao->getDescricaoMaterial());
        $oStdItem->iQuantidade      = $oItemSolicitacao->getQuantidade();
        $oStdItem->sResumo          = urldecode($oItemSolicitacao->getResumo());
        $oStdItem->iUnidade         = $oItemSolicitacao->getUnidade();
        $oStdItem->iQtdUnidade      = $oItemSolicitacao->getQuantidadeUnidade();
        $oStdItem->iItemProcesso    = $oItemLicitacao->getItemProcessoCompras();
        $oStdItem->iItemSolicitacao = $oItemSolicitacao->getCodigoItemSolicitacao();
        $oStdItem->iItemLicitacao   = $oItemLicitacao->getCodigo();

        $oRetorno->aItens[] = $oStdItem;
      }

      break;

    /**
     * @todo  implementar
     */
    case "removerItem":

      $oItemSolicitacao    = new \itemSolicitacao($oParam->iItemSolicitacao);
      $oItemProcessoCompra = new \ItemProcessoCompra($oParam->iItemProcesso);
      $oItemLicitacao      = new \ItemLicitacao();

      $oProcessoCompra     = new \ProcessoCompras($oParam->iProcessoCompra);
      $oSolicitacaoCompra  = new \solicitacaoCompra($oParam->iSolicitacao);


      $oItemLicitacao->remover($oParam->iItemLicitacao);
      $oItemProcessoCompra->setCodigo($oParam->iItemProcesso);
      $oItemProcessoCompra->excluir();
      $oItemSolicitacao->remover();

      if ( count($oProcessoCompra->getItens()) == 0) {
        $oProcessoCompra->remover();
      }

      if ( !$oSolicitacaoCompra->hasItem() ) {
        $oSolicitacaoCompra->remover();
      }
      $oRetorno->sMessage = "Item {$oParam->sMaterial} removido com sucesso.";
      break;

    /**
     * @todo  implementar
     */
    case "salvarItens":

      if ( empty($oParam->iLicitacao) ) {
        throw new ParameterException("Licitação não foi informada");
      }

      $oParam->iLicitacao;

      $oSolicitacao    = new \solicitacaoCompra($oParam->iSolicitacao);
      $oProcessoCompra = new \ProcessoCompras($oParam->iProcessoCompra);
      if ( empty($oParam->iSolicitacao) ) {

        $oSolicitacao->setTipo(SolicitacaoTipo::AUTOMATICO);
        $oSolicitacao->setUsuario( UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario")) );
        $oSolicitacao->setDepartamento(db_getsession("DB_coddepto"));
        $oSolicitacao->setData( new DBDate(date("Y-m-d", db_getsession("DB_datausu")) ));
        $oSolicitacao->setCodigoInstituicao(db_getsession("DB_instit"));
        $oSolicitacao->salvar();

        $oParam->iSolicitacao = $oSolicitacao->getCodigo();
      }

      if ( empty($oParam->iProcessoCompra)) {

        $oProcessoCompra->setCodigoDepartamento(db_getsession("DB_coddepto"));
        $oProcessoCompra->setDataEmissao(date("Y-m-d", db_getsession("DB_datausu")));
        $oProcessoCompra->setUsuario(db_getsession("DB_id_usuario"));
        $oProcessoCompra->setSituacao(ProcessoCompras::AUTORIZADO);
        $oProcessoCompra->setTipoProcesso(ProcessoCompras::TIPO_ITEM);
      }

      $iSequencia  = 1;
      $aItensNovos = array();
      foreach ($oParam->aItens as $oDadosItem) {

        $oItem = new \itemSolicitacao($oDadosItem->iItemSolicitacao);
        $oItem->setQuantidade($oDadosItem->iQuantidade);
        $oItem->setResumo($oDadosItem->sResumo);
        $oItem->setCodigoMaterial($oDadosItem->iMaterial);
        $oItem->setOrdem($iSequencia);
        $oItem->setUnidade($oDadosItem->iUnidade);
        $oItem->setQuantidadeUnidade($oDadosItem->iQtdUnidade);
        $oItem->save($oSolicitacao->getCodigo());
        $oDadosItem->iItemSolicitacao = $oItem->getCodigoItemSolicitacao();

        /**
         * @todo revisar se o item de solicitacao ja existe, caso exista não precisa fazer adicionar novamente
         */
        $oItemProcesso = new \ItemProcessoCompra();
        $oItemProcesso->setCodigo($oDadosItem->iItemProcesso);
        $oItemProcesso->setItemSolicitacao($oItem);

        $oProcessoCompra->adicionarItem($oItemProcesso);
        $iSequencia ++;

        if ( empty($oDadosItem->iItemLicitacao) ) {
          $aItensNovos[] = $oDadosItem;
        }
      }

      $oProcessoCompra->salvar();
      foreach ($aItensNovos as $oDadosItem) {

        foreach ($oProcessoCompra->getItens() as $oItemProcesso) {

          if ( $oItemProcesso->getItemSolicitacao()->getCodigoItemSolicitacao() == $oDadosItem->iItemSolicitacao ) {

            $oItemLicitacao = new \ItemLicitacao();
            $oItemLicitacao->setItemProcessoCompras($oItemProcesso->getCodigo());
            $oItemLicitacao->setCodigoLicitacao($oParam->iLicitacao);
            $oItemLicitacao->salvar();
          }
        }
      }

      if ( in_array($oParam->iTipoJulgamento, array(licitacao::TIPO_JULGAMENTO_POR_ITEM, licitacao::TIPO_JULGAMENTO_GLOBAL)) ) {

        $oLicitacao = new \licitacao($oParam->iLicitacao);
        $aItens     = $oLicitacao->getItens();
        foreach ($aItens as $oItem) {

          $oDao = new \cl_liclicitemlote();
          $sSql = $oDao->sql_query_file(null, "1", null, "l04_liclicitem = {$oItem->getCodigo()}");
          $rsItemLote = db_query($sSql);

          if ( $rsItemLote && pg_num_rows($rsItemLote) > 0) {
            continue;
          }


          $oDao->l04_liclicitem = $oItem->getCodigo();
          $oDao->l04_descricao  = "GLOBAL";
          if ($oParam->iTipoJulgamento == licitacao::TIPO_JULGAMENTO_POR_ITEM) {
            $oDao->l04_descricao = "LOTE_AUTOITEM_" .$oItem->getCodigo();
          }

          $oDao->incluir(null);
        }
      }

      $oRetorno->sMessage = "Item(ns) cadastrado(s) com sucesso.";
      break;
  }

  db_fim_transacao();


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);
