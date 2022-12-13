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
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;
try {
  switch ($oParam->exec) {


    case 'getItens':

      $oAcordo       = AcordoRepository::getByCodigo($oParam->iCodigoAcordo);
      $aItensAcordo  = $oAcordo->getItens();
      $aItensRetorno = array();

      $oRetorno->percentual_executado = 0;
      $nValorTotalContrato            = 0;
      $nValorTotalExecutado           = 0;
      foreach ($aItensAcordo as $oItem) {

        $oStdItem                     = new stdClass();
        $oStdItem->codigo             = $oItem->getCodigo();
        $oStdItem->descricao          = $oItem->getMaterial()->getDescricao();
        $oStdItem->unidade            = urlencode($oItem->getDescricaoUnidade());
        $oStdItem->quantidade         = $oItem->getQuantidade();
        $oStdItem->valor              = $oItem->getValorTotal();
        $oStdItem->ordem              = $oItem->getOrdem();
        $oStdItem->servico            = $oItem->getMaterial()->isServico();
        $oStdItem->resumo             = urlencode(str_replace('\\\n', "<br>", $oItem->getResumo()));
        $oStdItem->elemento           = '';
        $oStdItem->descricao_elemento = '';

        $aElemento = $oItem->getMaterial()->getElementos();
        if (count($aElemento) > 0) {

          $oStdItem->elemento           = $aElemento[0]->elemento;
          $oStdItem->descricao_elemento = $aElemento[0]->descricao;
        }

        $aItensRetorno[]      = $oStdItem;
        $nValorTotalContrato  += $oItem->getValorTotal();
        $nValorTotalExecutado += $oItem->getValorExecutado();
      }
      if ($nValorTotalContrato > 0) {
        $oRetorno->percentual_executado = round((($nValorTotalExecutado * 100) / $nValorTotalContrato), 2);
      }
      $oRetorno->itens = $aItensRetorno;
      break;

    case 'salvarExecucao':

      db_inicio_transacao();
      $oItem     = new AcordoItem($oParam->codigo_item);
      $oExecucao = new AcordoItemExecucao();
      $oExecucao->setCodigo($oParam->codigo_execucao);
      $oExecucao->setQuantidade($oParam->quantidade);
      $oExecucao->setValor($oParam->valor);
      $oExecucao->setItem($oItem);
      $oExecucao->setDataInicial(new DBDate($oParam->data_inicial));
      $oExecucao->setDataFinal(new DBDate($oParam->data_final));
      $oExecucao->setNotaFiscal(db_stdClass::normalizeStringJsonEscapeString($oParam->nota_fiscal));
      $oExecucao->setProcesso(db_stdClass::normalizeStringJsonEscapeString($oParam->processo));

      if (!$oItem->temSaldoParaExecucaoDosValores($oExecucao)) {

        $sItem = urldecode($oItem->getMaterial()->getDescricao());
        throw new BusinessException("O item {$sItem} não possui saldo para execução dos valores informados.");
      }

      $oExecucao->salvar();
      $oRetorno->codigo_execucao = $oExecucao->getCodigo();
      db_fim_transacao(false);
    break;


    case 'excluirExecucao':

      db_inicio_transacao();
      $oExecucao = new AcordoItemExecucao();
      $oExecucao->setCodigo($oParam->codigo_execucao);
      $oExecucao->remover();
      db_fim_transacao(false);
      break;

    case 'getExecucoesPorItem':

      $oItem      = new AcordoItem($oParam->codigo_item);
      $oRetorno->servico = $oItem->getMaterial()->isServico();
      $aExecucoes = $oItem->getExecucoes();

      $oRetorno->execucoes = array();
      $oRetorno->percentual_executado = $oItem->getPercentualExecutado();
      foreach ($aExecucoes as $oExecucao) {

        $oStdExecucao                  = new stdClass();
        $oStdExecucao->codigo_execucao = $oExecucao->getCodigo();
        $oStdExecucao->data_inicial    = $oExecucao->getDataInicial()->getDate();
        $oStdExecucao->data_final      = $oExecucao->getDataFinal()->getDate();
        $oStdExecucao->quantidade      = $oExecucao->getQuantidade();
        $oStdExecucao->valor           = $oExecucao->getValor();
        $oStdExecucao->nota_fiscal     = urlencode($oExecucao->getNotaFiscal());
        $oStdExecucao->processo        = urlencode($oExecucao->getProcesso());
        $oRetorno->execucoes[]         = $oStdExecucao;
      }
      break;

  }
} catch (BusinessException $oBussinesException) {

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->mensagem = urlencode($oBussinesException->getMessage());

} catch (ParameterException $oParameterException) {

  db_fim_transacao(true);
  $oRetorno->erro = true;
} catch (Exception $oParameterException) {

  db_fim_transacao(true);
  $oRetorno->error = true;

}
echo $oJson->encode($oRetorno);