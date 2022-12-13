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
require_once(modification("std/db_stdClass.php"));
require_once(modification("model/aberturaRegistroPreco.model.php"));
require_once(modification("model/estimativaRegistroPreco.model.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch ($oParam->exec) {

  case "salvarAbertura":

    try {

      db_inicio_transacao();

      if (isset($_SESSION["oSolicita"]) && $_SESSION["oSolicita"] instanceof aberturaRegistroPreco) {
        $oSolicita = $_SESSION["oSolicita"];
      } else {
        $oSolicita = new aberturaRegistroPreco();
      }

      $oSolicita->setLiberado($oParam->liberado);
      $oSolicita->setResumo(db_stdClass::normalizeStringJsonEscapeString($oParam->resumo));
      $oSolicita->setDataInicio($oParam->datainicio);
      $oSolicita->setDataTermino($oParam->datatermino);
      $oSolicita->save();
      $oRetorno->iCodigoSolicita = $oSolicita->getCodigoSolicitacao();
      $_SESSION["oSolicita"] = $oSolicita;
      $aitens = $oSolicita->getItens();

      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem        = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem     = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade        = $oItem->getQuantidade();
        $oItemRetono->automatico        = $oItem->isAutomatico();
        $oItemRetono->resumo            = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo             = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade           = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade));
        $oItemRetono->indice            = $iIndice;
        $oRetorno->itens[] = $oItemRetono;
      }
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;

    }
    break;

  case "salvarEstimativa":

    try {


      if (isset($_SESSION["oSolicita"]) && $_SESSION["oSolicita"] instanceof estimativaRegistroPreco) {
        $oEstimativa = $_SESSION["oSolicita"];
      } else {

        $oEstimativa = new estimativaRegistroPreco();
        $oEstimativa->setCodigoAbertura($oParam->iAbertura);
      }

      $iCodigoSolicitacao = $oEstimativa->getCodigoSolicitacao();
      if (empty($iCodigoSolicitacao)) {
          $oSolicita   = new aberturaRegistroPreco($oParam->iAbertura);
          $aVerificaEstimativas = $oSolicita->getEstimativas(db_getsession("DB_coddepto"));

          foreach ($aVerificaEstimativas as $estimativa) {
              if (!$estimativa->isAnulada()) {
              throw new BusinessException("Departamento já possui estimativa lançada para a Abertura de Registo de preço {$oParam->iAbertura}");
              }
          }
      }

      db_inicio_transacao();

      $oEstimativa->setResumo(utf8_decode(db_stdClass::db_stripTagsJson($oParam->resumo)));
      $oEstimativa->setAlterado(true);
      $oEstimativa->save();
      $oRetorno->iCodigoSolicita = $oEstimativa->getCodigoSolicitacao();

      $aitens = $oEstimativa->getItens();

      if (count($aitens) == 0) {
        throw new BusinessException("A Abertura do Registro de Preço {$oParam->iAbertura} não possui nenhum item vinculado.");
      }

      $_SESSION["oSolicita"] = $oEstimativa;

      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem    = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade    = $oItem->getQuantidade();
        $oItemRetono->automatico    = $oItem->isAutomatico();
        $oItemRetono->resumo        = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade       = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade)));
        $oItemRetono->indice        = $iIndice;
        $oRetorno->itens[] = $oItemRetono;

      }
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;

    }

    break;

  case "adicionarItemAbertura" :

    try {

      db_inicio_transacao();
      $oSolicita =  $_SESSION["oSolicita"];

      if (empty($oSolicita) || !method_exists($oSolicita, 'getTipoSolicitacao')) {
        throw new Exception("Ocorreu um erro ao carregar o objeto da Solicitação de Compras.");
      }

      if ($oSolicita->getTipoSolicitacao() == 3) {
        $oItemNovo = new  itemSolicitacao(null, $oParam->iCodigoItem);
      } else if ($oSolicita->getTipoSolicitacao() == 4) {

        $oItemNovo = new  ItemEstimativa(null, $oParam->iCodigoItem);
        $oItemNovo->setQuantidade($oParam->quantidade);
      }

      $oItemNovo->setResumo(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sResumo)));
      $oItemNovo->setJustificativa(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sJustificativa)));
      $oItemNovo->setPagamento(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sPgto)));
      $oItemNovo->setPrazos(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sPrazo)));
      $oItemNovo->setUnidade($oParam->iUnidade);
      $oItemNovo->setQuantidadeUnidade($oParam->nQuantUnidade);
      $oSolicita->addItem($oItemNovo);
      $lTemEstimativa = false;


      if ($oSolicita instanceof aberturaRegistroPreco) {
        $lTemEstimativa = $oSolicita->hasEstimativas();
      }

      $aitens = $oSolicita->getItens();

      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem        = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem     = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade        = $oItem->getQuantidade();
        $oItemRetono->automatico        = $oItem->isAutomatico();
        $oItemRetono->resumo            = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo             = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade           = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade));
        $oItemRetono->indice            = $iIndice;
        $oItemRetono->temestimativa     = $lTemEstimativa;

        $oRetorno->itens[] = $oItemRetono;

      }
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

    break;
  case "salvarItensAbertura":

    try {

      db_inicio_transacao();
      $oSolicita =  $_SESSION["oSolicita"];

      if (!is_object($oSolicita)) {
        throw new Exception("Erro ao salvar dados. Procedimento abortado.");
      }
      if ($oSolicita instanceof estimativaRegistroPreco) {
        $oSolicita->setAlterado(true);
      }

      if ($oSolicita instanceof stdClass) {
        throw new Exception("Não foi possível carregar a Solicitação de Compras.");
      }

      $oSolicita->save();

      if ($oSolicita instanceof estimativaRegistroPreco) {

        $nTotalQuantidades = 0;
        foreach ($oSolicita->getItens() as $oItemEstimativa) {
          $nTotalQuantidades += $oItemEstimativa->getQuantidade();
        }

        if (empty($nTotalQuantidades)) {
          throw new BusinessException("É preciso informar quantidade em pelo menos um dos itens.");
        }
      }

      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));
    }
    break;

  case "clearSession":

    unset($_SESSION["oSolicita"]);
    break;

  case "pesquisarAbertura":

    try {

      $lTemEstimativa = false;
      switch ($oParam->tipo) {

        case 3:

          $oSolicita             = new aberturaRegistroPreco($oParam->iSolicitacao);
          $_SESSION["oSolicita"] = $oSolicita;
          if (count($oSolicita->getEstimativas()) > 0) {
            $lTemEstimativa = true;
          }
          break;

        case 4:

          $oSolicita             = new estimativaRegistroPreco($oParam->iSolicitacao);
          $_SESSION["oSolicita"] = $oSolicita;
          $oRetorno->lCorreto    = $oSolicita->isAlterado();

          break;
      }

      $aitens = $oSolicita->getItens();
      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem        = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem     = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade        = $oItem->getQuantidade();
        $oItemRetono->automatico        = $oItem->isAutomatico();
        $oItemRetono->resumo            = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo             = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade           = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade)));
        $oItemRetono->indice            = $iIndice;
        $oItemRetono->temestimativa     = $lTemEstimativa;
        $oRetorno->itens[] = $oItemRetono;

      }
      switch ($oSolicita->getTipoSolicitacao()) {


        case 3 :

          $oRetorno->datainicio  = db_formatar($oSolicita->getDataInicio(),"d");
          $oRetorno->datatermino = db_formatar($oSolicita->getDataTermino(),"d");
          $oRetorno->liberado    = $oSolicita->isLiberado();
          break;

        case 4:

          $oRetorno->datasolicitacao = db_formatar($oSolicita->getDataSolicitacao(), "d");
          $oRetorno->codigoabertura  = $oSolicita->getCodigoAbertura();
          break;
      }

      $oRetorno->resumo      = urlencode(str_replace("\\n", "\n",urldecode($oSolicita->getResumo())));
      $oRetorno->solicitacao = $oSolicita->getCodigoSolicitacao();


    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;

  case "excluirItens":

    try {

      db_inicio_transacao();
      $oSolicita = $_SESSION["oSolicita"];
      $oSolicita->removerItem($oParam->iItemRemover);
      db_fim_transacao(false);
      $lTemEstimativa = false;
      $aitens = $oSolicita->getItens();
      if ($oSolicita instanceof aberturaRegistroPreco) {
        if ($oSolicita->hasEstimativas()) {
          $lTemEstimativa = true;
        }
      }
      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem        = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem     = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade        = $oItem->getQuantidade();
        $oItemRetono->automatico        = $oItem->isAutomatico();
        $oItemRetono->resumo            = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo             = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade           = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade)));
        $oItemRetono->indice            = $iIndice;
        $oItemRetono->temestimativa     = $lTemEstimativa;
        $oRetorno->itens[] = $oItemRetono;

      }
    } catch (Exception $eErro) {


      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      $aitens = $oSolicita->getItens();
    }
    break;
  case "salvarItensValor" :

    try {

      db_inicio_transacao();
      if (empty($_SESSION["oSolicita"]) || !is_object($_SESSION["oSolicita"])) {
        throw new Exception("Erro ao salvar dados. Procedimento abortado.");
      }

      $oSolicita = $_SESSION["oSolicita"];
      $aitens = $oSolicita->getItens();
      if (isset($aitens[$oParam->iIndice])) {
        $aitens[$oParam->iIndice]->setQuantidade($oParam->quantidade);
      }
      db_fim_transacao(true);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $eErro->getMessage();
    }
    break;

  case "alterarItem" :

    try {

      db_inicio_transacao();
      $oSolicita =  $_SESSION["oSolicita"];
      $aItens    = $oSolicita->getItens();
      $oItem     = $aItens[$oParam->iIndice];
      $oItem->setResumo(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sResumo)));
      $oItem->setJustificativa(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sJustificativa)));
      $oItem->setPagamento(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sPgto)));
      $oItem->setPrazos(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sPrazo)));
      $oItem->save();
      $aitens         = $oSolicita->getItens();
      $lTemEstimativa = false;
      if ($oSolicita instanceof aberturaRegistroPreco) {

        if (count($oSolicita->getEstimativas()) > 0) {
          $lTemEstimativa = true;
        }
      }
      foreach ($aitens as $iIndice => $oItem) {

        $oItemRetono = new stdClass;
        $oItemRetono->codigoitem        = $oItem->getCodigoMaterial();
        $oItemRetono->descricaoitem     = $oItem->getDescricaoMaterial();
        $oItemRetono->quantidade        = $oItem->getQuantidade();
        $oItemRetono->automatico        = $oItem->isAutomatico();
        $oItemRetono->resumo            = urlencode(str_replace("\\n", "\n",urldecode($oItem->getResumo())));
        $oItemRetono->justificativa     = urlencode(str_replace("\\n", "\n",urldecode($oItem->getJustificativa())));
        $oItemRetono->prazo             = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPrazos())));
        $oItemRetono->pagamento         = urlencode(str_replace("\\n", "\n",urldecode($oItem->getPagamento())));
        $oItemRetono->unidade           = $oItem->getUnidade();
        $oItemRetono->unidade_descricao = urlencode(urlencode(itemSolicitacao::getDescricaoUnidade($oItemRetono->unidade)));
        $oItemRetono->indice            = $iIndice;
        $oItemRetono->temestimativa     = $lTemEstimativa;
        $oRetorno->itens[] = $oItemRetono;

      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }

    break;

  case "getUltimosOrcamentos":

    require_once(modification("model/itemSolicitacao.model.php"));

    $oRetorno->itens   = itemSolicitacao::getUltimosOrcamentos($oParam->iMaterial,
                                                               $oParam->aUnidades,
                                                               $oParam->iFornecedor,
                                                               $oParam->dtInicial,
                                                               $oParam->dtFinal
    );
    $oRetorno->media    = itemSolicitacao::calculaMediaPrecoOrcamentos($oRetorno->itens);
    $oRetorno->unidades = itemSolicitacao::getUnidadesMaterial($oParam->iMaterial);
    break;

  case 'pesquisarEstimativaDepartamento':

    if (isset($oParam->iSolicitacao)) {

      $oSolicita   = new aberturaRegistroPreco($oParam->iSolicitacao);
      $oEstimativa = $oSolicita->getEstimativaPorDepartamento(db_getsession("DB_coddepto"));
      if ($oEstimativa instanceof estimativaRegistroPreco) {

        if (!$oEstimativa->isAnulada()) {

          $sMessage          = "Departamento já possui estimativa lançada para a ";
          $sMessage         .= "Abertura de Registo de preço {$oParam->iSolicitacao}.\n";
          $sMessage         .= "Dados da estimativa:\n";
          $sMessage         .= "Número:{$oEstimativa->getCodigoSolicitacao()}.\n";
          $sMessage         .= "Data Cadastro:".db_formatar($oEstimativa->getDataSolicitacao(), "d").".";
          $oRetorno->status  = 2;
          $oRetorno->message = urlencode($sMessage);
        }
      }
    }
    break;

  case 'anularSolicitacao':

    try {

      db_inicio_transacao();

      $oSolicitacaoCompras = new solicitacaoCompra($oParam->iCodigoSolicitacao);
      $oSolicitacaoCompras->anular(
        db_stdClass::normalizeStringJsonEscapeString($oParam->sMotivo),
        db_stdClass::normalizeStringJsonEscapeString($oParam->sProcessoAdministrativo)
      );

      db_fim_transacao(false);
      $oRetorno->erro = false;
      $oRetorno->mensagem = urlencode(_M('patrimonial.compras.com4_anularsolicitacaocompras001.solicitacao_anulada'));

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->mensagem = urlencode($eErro->getMessage());
      $oRetorno->erro = true;
    }

    break;
}

echo $oJson->encode($oRetorno);
