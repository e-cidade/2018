<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

require_once(modification("model/Acordo.model.php"));
require_once(modification("model/AcordoPosicao.model.php"));
require_once(modification("model/AcordoHomologacao.model.php"));
require_once(modification("model/AcordoAssinatura.model.php"));
require_once(modification("model/AcordoAnulacao.model.php"));
require_once(modification('model/AcordoComissao.model.php'));
require_once(modification('model/AcordoItem.model.php'));
require_once(modification('model/AcordoComissaoMembro.model.php'));
require_once(modification("model/AcordoPenalidade.model.php"));
require_once(modification("model/AcordoGarantia.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification('model/CgmBase.model.php'));
require_once(modification('model/CgmFisico.model.php'));
require_once(modification('model/CgmJuridico.model.php'));
require_once(modification('model/Dotacao.model.php'));
require_once(modification("model/MaterialCompras.model.php"));
require_once(modification("model/empenho/AutorizacaoEmpenho.model.php"));
require_once(modification("model/AcordoPosicao.model.php"));
require_once(modification("model/licitacao.model.php"));
require_once(modification("model/ProcessoCompras.model.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sessoes.php"));
$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno->status   = 1;
$oRetorno->message  = '';
$oRetorno->itens    = array();
if (isset($oParam->observacao)) {
	$sObservacao = utf8_decode($oParam->observacao);
}

switch($oParam->exec) {

  /*
   * Pesquisa as posicoes do acordo
   */
  case "getPosicoesAcordo":

    $lGeraAutorizacao = false;
    if (!empty($oParam->lGeracaoAutorizacao)) {
      $lGeraAutorizacao = true;
    }

     if (isset ($_SESSION["oContrato"])) {
       unset($_SESSION["oContrato"]);
     }

     $aTiposPosicoesIgnorar = array(
       AcordoPosicao::TIPO_VIGENCIA,
       AcordoPosicao::TIPO_ALTERACAO_DOTACAO,
       AcordoPosicao::TIPO_SUPRESSAO,
     );
     $oContrato              = new Acordo($oParam->iAcordo);
     $_SESSION["oContrato"]  = $oContrato;
     $aPosicoes              = $oContrato->getPosicoes();
     $oRetorno->posicoes     = array();
     $oRetorno->tipocontrato = $oContrato->getOrigem();
     foreach ($aPosicoes as $oPosicaoContrato) {

       $oPosicao        = new stdClass();
       $lOrigemEmpenho = false;
       if ($oContrato->getOrigem() == Acordo::ORIGEM_EMPENHO) {
         $lOrigemEmpenho = true;
       }

       if (in_array($oPosicaoContrato->getTipo(), $aTiposPosicoesIgnorar)) {
         continue;
       }
       $iTipoPosicao =  $oPosicaoContrato->getTipo();

       /**
        * Mostrará apenas as posições de tipo inclusão ou vigência, para acordos de origem empenho
        */
       if ($lGeraAutorizacao && $lOrigemEmpenho && ($iTipoPosicao == AcordoPosicao::TIPO_INCLUSAO || $iTipoPosicao == AcordoPosicao::TIPO_VIGENCIA) ) {
         continue;
       }

       $oPosicao->codigo         = $oPosicaoContrato->getCodigo();
       $oPosicao->data           = $oPosicaoContrato->getData();
       $oPosicao->tipo           = $oPosicaoContrato->getTipo();
       $oPosicao->numerocontrato = $oContrato->getGrupo()." - ".$oContrato->getNumero()."/".$oContrato->getAno();
       $oPosicao->descricaotipo  = urlencode($oPosicaoContrato->getDescricaoTipo());
       $oPosicao->numero         = (string)"".str_pad($oPosicaoContrato->getNumero(), "0", 7)."";
       $oPosicao->emergencial    = urlencode($oPosicaoContrato->isEmergencial()?"Sim":"Não");
       array_push($oRetorno->posicoes, $oPosicao);

     }

    break;

  case "getPosicaoItens":

    if (isset ($_SESSION["oContrato"])) {

      $oContrato = $_SESSION["oContrato"];
      $aItens    = array();
      foreach ($oContrato->getPosicoes() as $oPosicaoContrato) {

        if ($oPosicaoContrato->getCodigo() == $oParam->iPosicao) {

          foreach ($oPosicaoContrato->getItens() as $oItem) {

              $oItemRetorno                      = new stdClass();
              $oItemRetorno->codigo              = $oItem->getCodigo();
              $oItemRetorno->material            = $oItem->getMaterial()->getDescricao();
              $oItemRetorno->codigomaterial      = urlencode($oItem->getMaterial()->getMaterial());
              $oItemRetorno->elemento            = $oItem->getElemento();
              $oItemRetorno->desdobramento       = $oItem->getDesdobramento();
              $oItemRetorno->valorunitario       = $oItem->getValorUnitario();
              $oItemRetorno->valortotal          = $oItem->getValorTotal();
              $oItemRetorno->quantidade          = $oItem->getQuantidade();
              $oItemRetorno->lControlaQuantidade = $oItem->getControlaQuantidade();

              foreach ($oItem->getDotacoes() as $oDotacao) {

                $oDotacaoSaldo = new Dotacao($oDotacao->dotacao, $oDotacao->ano);
                $oDotacao->saldoexecutado = 0;;
                $oDotacao->valorexecutar  = 0;
                $oDotacao->saldodotacao   = $oDotacaoSaldo->getSaldoFinal();

              }
              $oItemRetorno->dotacoes       = $oItem->getDotacoes();
              $oItemRetorno->saldos         = $oItem->getSaldos();
              $oItemRetorno->servico        = $oItem->getMaterial()->isServico();
              $oRetorno->itens[]            = $oItemRetorno;
          }
          break;
        }
      }
    } else {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode('Inconsistencia na consulta pesquise novamente os dados do acordo');
    }
    break;

  case "processarAutorizacoes":

    $oContrato = $_SESSION["oContrato"];

    try {

      db_inicio_transacao();

      if ( !empty($oParam->dados->resumo) ) {
        $oParam->dados->resumo = db_stdClass::normalizeStringJsonEscapeString($oParam->dados->resumo);
      }

      if ( !empty($oParam->dados->pagamento) ) {
        $oParam->dados->pagamento = db_stdClass::normalizeStringJsonEscapeString($oParam->dados->pagamento);
      }

      foreach ($oParam->aItens as $iItem => $oItem) {

        $oAcordoItem     = new AcordoItem($oItem->codigo);
        $oOrigem         = $oAcordoItem->getOrigem();
        $oItemLicitacao  = new ItemLicitacao($oOrigem->codigo);
        $nValorTotalItem = $oAcordoItem->getValorTotal();

        /**
         * Valida a orgem do contrato
         * - se for licitação e a licitação for um chamamento público / credenciamento, o saldo deve ser controlado pelos
         *   intens da solicitação
         */
        if ( $oOrigem->tipo == 2 && $oItemLicitacao->getLicitacao()->isChamamentoPublicoComCredenciamento()) {

          $oSaldoExecutado  = $oItemLicitacao->saldoExecutadoEmContratos();
          $nValorTotalItem  = $oItemLicitacao->getItemSolicitacao()->getValorTotal();
          $nValorTotalItem -= $oSaldoExecutado->vlr_executado;
        }

        $nTotalExecutar = 0;
        foreach ($oItem->dotacoes as $iDotacoes => $oDotacoes) {
          $nTotalExecutar += $oDotacoes->valorexecutar;
        }

        if (round($nTotalExecutar, 2) > round($nValorTotalItem, 2)) {

          $nExecutar  = trim(db_formatar($nTotalExecutar , "f") );
          $nTotalItem = trim(db_formatar($nValorTotalItem , "f"));
          throw new BusinessException( " Valor a executar {$nExecutar} maior que o total do item {$nTotalItem}. " ) ;
        }
      }

      $oRetorno->itens  = $oContrato->processarAutorizacoes($oParam->aItens, $oParam->lProcessar, $oParam->dados);

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

   break;

   case "getAutorizacoesAcordo":

     $oContrato = new Acordo($oParam->iAcordo);
     $oRetorno->autorizacoes = $oContrato->getAutorizacoes();
     break;

   case "anularAutorizacoes":

     try {

       db_inicio_transacao();
       foreach ($oParam->aAutorizacoes as $iAutorizacao) {

         $oAutorizacao = new AutorizacaoEmpenho($iAutorizacao);
         $oContrato = new Acordo($oAutorizacao->getContrato());
         $oContrato->anularAutorizacao($iAutorizacao);
       }
       db_fim_transacao(false);
     } catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->status  = 2;
       $oRetorno->message = urlencode($eErro->getMessage());
     }

     break;

   case "salvarMovimentacaoEmpenhoManual":

     $oContrato = $_SESSION["oContrato"];
     $oUltimaPosicao = $oContrato->getUltimaPosicao();
     $oRetorno->iPosicao = $oUltimaPosicao->getCodigo();
     try {
       db_inicio_transacao();
       foreach ($oParam->aItens as $oItem) {

         $oItemContrato = $oUltimaPosicao->getItemByCodigo($oItem->codigo);
         $oItemContrato->baixarMovimentacaoManual(1,$oItem->quantidadeexecutada, $oItem->valorexecutado);
       }
       db_fim_transacao(false);
     } catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->status  = 2;
       $oRetorno->message = urlencode($eErro->getMessage());

     }
   break;

   case 'getDadosAcordo' :

     $oAcordo = new Acordo($oParam->iCodigoAcordo);
     $oRetorno->sResumoAcordo = urlencode($oAcordo->getObjeto());

   break;
}

echo JSON::create()->stringify($oRetorno);