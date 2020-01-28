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
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("model/empenho/AutorizacaoEmpenho.model.php");

$oParametros       = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case 'getDados':

      $iCodigo = (int) $oParametros->iCodigo;
      if (empty($iCodigo)) {
        throw new ParameterException('Código inválido ou não informado.');
      }

      $oListaClassificacaoCredor  = ListaClassificacaoCredorRepository::getPorCodigo($iCodigo);
      $oRetorno->iCodigo          = $iCodigo;
      $oRetorno->sDescricao       = $oListaClassificacaoCredor->getDescricao();
      $oRetorno->iDiasVencimento  = (int) $oListaClassificacaoCredor->getDiasVencimento();
      $oRetorno->iContagemDias    = (int) $oListaClassificacaoCredor->getContagemDias();
      $oRetorno->iOrdem           = (int) $oListaClassificacaoCredor->getOrdem();
      $oRetorno->nValorInicial    = (float) $oListaClassificacaoCredor->getValorInicial();
      $oRetorno->nValorFinal      = (float) $oListaClassificacaoCredor->getValorFinal();
      $oRetorno->lDispensa        = (boolean) $oListaClassificacaoCredor->dispensa();
      $oRetorno->aContas          = array();
      $oRetorno->aTiposCompra     = array();
      $oRetorno->aRecursos        = array();
      $oRetorno->aEventos         = array();

      if (empty($oRetorno->nValorInicial)) {
        $oRetorno->nValorInicial = '';
      }

      if (empty($oRetorno->nValorFinal)) {
        $oRetorno->nValorFinal = '';
      }

      foreach ($oListaClassificacaoCredor->getContas() as $oConta) {

        $oRetorno->aContas[] = (object) array(
          'iCodigo'    => (int) $oConta->getContaOrcamento()->getCodigo(),
          'sDescricao' => $oConta->getContaOrcamento()->getEstrutural() . ' - ' . $oConta->getContaOrcamento()->getDescricao(),
          'lExclusao'  => $oConta->contaExclusao(),
        );
      }

      foreach ($oListaClassificacaoCredor->getRecurso() as $oRecurso) {

        $oRetorno->aRecursos[] = (object) array(
          'iCodigo'    => (int) $oRecurso->getCodigo(),
          'sDescricao' => $oRecurso->getDescricao(),
        );
      }

      foreach ($oListaClassificacaoCredor->getTipoCompra() as $oTipoCompra) {

        $oRetorno->aTiposCompra[] = (object) array(
          'iCodigo'    => (int) $oTipoCompra->getCodigo(),
          'sDescricao' => $oTipoCompra->getDescricao(),
        );
      }

      foreach ($oListaClassificacaoCredor->getEvento() as $oEvento) {

        $oRetorno->aEventos[] = (object) array(
          'iCodigo'    => (int) $oEvento->getCodigo(),
          'sDescricao' => $oEvento->getDescricao(),
        );
      }

      break;

    case "salvar":

      $oRetorno->iCodigo = '';

      $oListaClassificacao = new ListaClassificacaoCredor;
      if (!empty($oParametros->iCodigo)) {

        $oListaClassificacao->setCodigo((int) $oParametros->iCodigo);
        $oRetorno->iCodigo  = $oListaClassificacao->getCodigo();
      }
      $oListaClassificacao->setDescricao($oParametros->sDescricao);
      if (isset($oParametros->iDiasVencimento)) {
        $oListaClassificacao->setDiasVencimento((int) $oParametros->iDiasVencimento);
      }
      if (isset($oParametros->iContagemDias)) {
        $oListaClassificacao->setContagemDias((int) $oParametros->iContagemDias);
      }
      if (!empty($oParametros->nValorInicial)) {
        $oListaClassificacao->setValorInicial((float) $oParametros->nValorInicial);
      }
      if (!empty($oParametros->nValorFinal)) {
        $oListaClassificacao->setValorFinal((float) $oParametros->nValorFinal);
      }
      if (!empty($oParametros->lDispensa)) {
        $oListaClassificacao->setDispensa($oParametros->lDispensa == "1");
      }

      if (!empty($oParametros->aElementosInclusao) || !empty($oParametros->aElementosExclusao)) {

        $oListaClassificacao->limparElemento();
        foreach ($oParametros->aElementosInclusao as $oStdElemento) {

          $oConta = new ClassificacaoCredorConta;
          $oConta->setContaOrcamento(new ContaOrcamento($oStdElemento->sCodigo, db_getsession('DB_anousu')));
          $oConta->setContaExclusao(false);

          $oListaClassificacao->adicionarConta($oConta);
        }

        foreach ($oParametros->aElementosExclusao as $oStdElemento) {

          $oConta = new ClassificacaoCredorConta;
          $oConta->setContaOrcamento(new ContaOrcamento($oStdElemento->sCodigo, db_getsession('DB_anousu')));
          $oConta->setContaExclusao(true);

          $oListaClassificacao->adicionarConta($oConta);
        }
      }

      if (!empty($oParametros->aRecursos)) {

        $oListaClassificacao->limparRecurso();
        foreach ($oParametros->aRecursos as $oStdRecurso) {
          $oListaClassificacao->adicionarRecurso(new Recurso($oStdRecurso->sCodigo));
        }
      }

      if (!empty($oParametros->aTiposCompra)) {

        $oListaClassificacao->limparTipoCompra();
        foreach ($oParametros->aTiposCompra as $oStdTipoCompra) {
          $oListaClassificacao->adicionarTipoCompra(new TipoCompra($oStdTipoCompra->sCodigo));
        }
      }

      if (!empty($oParametros->aEvento)) {

        $oListaClassificacao->limparEvento();
        foreach ($oParametros->aEvento as $oStdEvento) {
          $oListaClassificacao->adicionarEvento(new TipoPrestacaoConta($oStdEvento->sCodigo));
        }
      }

      $oListaClassificacao->salvar();
      $oRetorno->message = urlencode('Lista de Classificação de Credores salva com sucesso.');
      $oRetorno->iCodigo  = $oListaClassificacao->getCodigo();


      break;

    case 'excluir':

      $iCodigo = (int) $oParametros->iCodigo;
      if (empty($iCodigo)) {
        throw new ParameterException('Código inválido ou não informado.');
      }

      $oListaClassificacaoCredor  = ListaClassificacaoCredorRepository::getPorCodigo($iCodigo);
      $oListaClassificacaoCredor->setCodigo($iCodigo);
      $oListaClassificacaoCredor->excluir();
      $oRetorno->message = urlencode("Lista de Classificação de Credores excluída com sucesso.");

      break;

    case 'getListaPorAutorizacao':

      $oRetorno->iCodigoLista    = '';
      $oRetorno->sDescricaoLista = '';
      $oRetorno->lDispensa       = false;

      $oAutorizacaoEmpenho = new AutorizacaoEmpenho((int) $oParametros->iCodigo);
      $oAutorizacaoEmpenho->setTipoCompra((int) $oParametros->iTipoCompra);
      $oAutorizacaoEmpenho->setTipoPrestacaoConta(new TipoPrestacaoConta((int) $oParametros->iEvento));
      $oAtributosEmpenho = ListaClassificacaoCredorRepository::getAtributosAutorizacao($oAutorizacaoEmpenho);
      $oAtributosEmpenho->setElemento((int) $oParametros->iElemento);
      $oListaClassificacaoCredor = ListaClassificacaoCredorRepository::getPorAtributos($oAtributosEmpenho);

      if (!empty($oListaClassificacaoCredor)) {

        $oRetorno->iCodigoLista    = $oListaClassificacaoCredor->getCodigo();
        $oRetorno->sDescricaoLista = $oListaClassificacaoCredor->getDescricao();
        $oRetorno->lDispensa       = ListaClassificacaoCredorRepository::getPorCodigo($oRetorno->iCodigoLista)->dispensa();
      }

      break;
    default:
      throw new ParameterException('Método inválido.');

  }
  db_fim_transacao(false);

} catch (Exception $oException) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($oException->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);