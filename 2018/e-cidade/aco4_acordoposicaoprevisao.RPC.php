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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
db_app::import("AcordoPosicao");
db_app::import("AcordoItem");
db_app::import("MaterialCompras");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {


  case 'getQuadroPeriodos':
    $oPosicao            = new AcordoPosicao($oParam->iCodigoPosicao);
    $_SESSION["oAcordo"] = $oPosicao;
    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {

      $oPosicao = $_SESSION["oAcordo"];
      $oRetorno->quadro = $oPosicao->getQuadroPrevisao();
    }
    break;
  case 'unsetSession':

    unset($_SESSION["oAcordo"]);
    break;

  case 'alterarQuantidade' :

    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {

      $oPosicao  = $_SESSION["oAcordo"];
      $oItem     = $oPosicao->getItemByCodigo($oParam->iItem);
      $aPeriodos = $oItem->getPeriodos();
      try {

        $oItem->validarQuantidadePeriodo($oParam->iPeriodo, $oParam->nQuantidade);
        foreach ($aPeriodos as &$oPeriodo) {

          if ($oPeriodo->codigo == $oParam->iPeriodo) {

            $oPeriodo->quantidadeprevista = $oParam->nQuantidade;
            $oRetorno->oPrevisao          = $oPeriodo;
          }
        }
      }
      catch (Exception $eErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;

  case 'salvarPrevisao':
    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {

      try {

        db_inicio_transacao();
        $oPosicao  = $_SESSION["oAcordo"];
        $aItens    = $oPosicao->getItens();
        foreach ($aItens as $oItem) {
          $oItem->salvarPeriodos();
        }
        db_fim_transacao(false);
      }
      catch (Exception $eErro) {

        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;

  case "getDadosEmpenho":

    if ($oParam->codemp != '') {

      $sWhere = "e60_instit = " . db_getsession("DB_instit");
      if (strpos($oParam->codemp,"/")) {

        $aEmpenho = explode("/",$oParam->codemp);
        $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

      } else {
        $sWhere .= " and e60_codemp = '{$oParam->codemp}' and e60_anousu=".db_getsession("DB_anousu");
      }
      $oRetorno->status = 2;

      $oDaoEmpEmpenho   = db_utils::getDao("empempenho");
      $sSqlDadosEmpenho = $oDaoEmpEmpenho->sql_query_file(null, '*', null, $sWhere);
      $rsDadosEmpenho   = $oDaoEmpEmpenho->sql_record($sSqlDadosEmpenho);

      /**
       * Este numrows dever ser sempre == 1
       */
      if ($oDaoEmpEmpenho->numrows  == 1) {

        $oRetorno->status = 1;
        $oRetorno->oEmpenho = db_utils::fieldsMemory($rsDadosEmpenho, 0, false, false, true);
      }
    }
    break;

  case "salvarMovimentacaoEmpenhoManual":

    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {
      try {

        db_inicio_transacao();
        $oPosicao  = $_SESSION["oAcordo"];
        $oItem     = $oPosicao->getItemByCodigo($oParam->iItem);
        $oItem->baixarMovimentacaoManual($oParam->oPeriodo, 2, $oParam->quantidade, 0, true, $oParam->notafiscal, $oParam->numeroprocesso, $oParam->observacao);
        $oRetorno->execucoes = $oItem->getPeriodoByCodigo($oParam->oPeriodo->iPeriodo)->execucoes;
        $oRetorno->iPeriodo = $oParam->oPeriodo->iPeriodo;
        $oRetorno->iCodigoAcordoItem    = $oParam->iItem;
        $oRetorno->nQuantidadeExecutada = $oParam->quantidade;
        db_fim_transacao(false);
      }
      catch (Exception $eErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
        db_fim_transacao(true);
      }
    }
    break;

  case 'excluirExecucao' :

    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {

    try {

        db_inicio_transacao();
        $oPosicao          = $_SESSION["oAcordo"];
        $oItem             = $oPosicao->getItemByCodigo($oParam->iItem);
        $oRetorno->periodo = $oItem->excluirMovimentacaoManual($oParam->iPeriodo,
                                                                 $oParam->iExecucao);
        db_fim_transacao(false);
      }
      catch (Exception $eErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
        db_fim_transacao(true);
      }
    }
    break;

  case 'imprimirExecucao' :

    $oPosicao            = new AcordoPosicao($oParam->iCodigoPosicao);
    $_SESSION["oAcordo"] = $oPosicao;
    if ($_SESSION["oAcordo"] instanceof AcordoPosicao ) {

      $aDadosCSV      = array();
      $oPosicao       = $_SESSION["oAcordo"];
      $aItens         = $oPosicao->getItens();
      $csv_file       = fopen('/tmp/relatorioexecucao.csv', 'w');

      /**
       * Montamos o cabeçalho do arquivo csv
       */
      $aQuadroPevisao = $oPosicao->getQuadroPrevisao();
      $aPeriodos      = array("Ordem", "Descrição do item", "Unidade");

      foreach ($aQuadroPevisao->aPeriodos as $aPrevisao) {

      	for ($i = 0; $i < count($aPrevisao); $i++) {

      	  $aPeriodos[] = urldecode($aPrevisao->descricao)." - Previsto";
      	  $aPeriodos[] = urldecode($aPrevisao->descricao)." - Executado";
      	}
      }
      fputcsv($csv_file, $aPeriodos, ';');

      /**
       * montamos o CSV linha por linha
       */
      foreach ($aItens as $oItem) {

        $aLinha     = array();
      	$iItem      = $oItem->getCodigo();
      	$oInfosItem = new AcordoItem($iItem);
      	$aPeriodos  = $oInfosItem->getPeriodos();
        $aLinha[]   = $oItem->getOrdem();
      	$aLinha[]   = urldecode($oItem->getMaterial()->getDescricao());
      	$aLinha[]   = $oItem->getDescricaoUnidade();

      	for ($i = 0; $i < count($aPeriodos); $i++) {

      		$aLinha[] = $aPeriodos[$i]->quantidadeprevista;
          $aLinha[] = $aPeriodos[$i]->executado;
        }
      	fputcsv($csv_file, $aLinha, ';');
      }
      fclose($csv_file);
      $oRetorno->patharquivo ='/tmp/relatorioexecucao.csv';
    }
  	break;

  case 'buscaPeriodoParaAlteracao':

    /**
     * Buscamos a previsão selecionada pelo usuário e suas informações relevantes
     */
    $oDaoAcordoItemPrevisao = db_utils::getDao('acordoitemprevisao');
    $sCamposBuscaPrevisao   = " pc01_descrmater, ac37_datainicial, ac37_datafinal, ac36_descricao ";
    $sWhereBuscaPrevisao    = " ac37_acordoperiodo = {$oParam->iCodigoPeriodo} ";
    $sWhereBuscaPrevisao   .= " and ac20_sequencial = {$oParam->iCodigoItem} ";
    $sSqlBuscaPrevisao      = $oDaoAcordoItemPrevisao->sql_query(null, $sCamposBuscaPrevisao,
                                                                 null, $sWhereBuscaPrevisao);
    $rsBuscaPrevisao        = $oDaoAcordoItemPrevisao->sql_record($sSqlBuscaPrevisao);
    $oPrevisao              = db_utils::fieldsMemory($rsBuscaPrevisao, 0);

    /**
     * Configuramos o retorno
     */
    $oRetorno->iCodigoPeriodo    = $oParam->iCodigoPeriodo;
    $oRetorno->iCodigoItem       = $oParam->iCodigoItem;
    $oRetorno->sDescricaoPeriodo = $oPrevisao->ac36_descricao;
    $oRetorno->sDescricaoItem    = urlencode($oPrevisao->pc01_descrmater);
    $oRetorno->sDataInicial      = db_formatar($oPrevisao->ac37_datainicial, 'd');
    $oRetorno->sDataFinal        = db_formatar($oPrevisao->ac37_datafinal, 'd');
    $oRetorno->nQuantidadeTotal  = round($oParam->nQuantidadeTotal, 2);
    $oRetorno->nQuantidadeSaldo  = round($oParam->nQuantidadeSaldo, 2);
    break;

  case 'alteraPeriodo':

    db_inicio_transacao();
    try {

      /**
       * Convertemos as datas passadas para o script
       */
      $sDataInicial = implode('-', array_reverse(explode('/', $oParam->sDataInicial)));
      $sDataFinal   = implode('-', array_reverse(explode('/', $oParam->sDataFinal)));

      $oAcordoPosicao = new AcordoPosicao();
      $oAcordoPosicao->setCodigoAcordoPosicao($oParam->iCodigoAcordoPosicao);
      $iCodigoPeriodoDestino = $oAcordoPosicao->getCodigoPosicaoPeriodo($oParam->sDataInicial, $oParam->sDataFinal);

      if (!$iCodigoPeriodoDestino) {
        throw new Exception("O destino informado não faz parte da vigência do contrato.");
      }

      $oAcordoItem = new AcordoItem($oParam->iCodigoItem);
      $oAcordoItem->setDataInicial($oParam->sDataInicial);
      $oAcordoItem->setDataFinal($oParam->sDataFinal);
      $oAcordoItem->moverPrevisaoPeriodo($oParam->iCodigoPeriodo, $iCodigoPeriodoDestino);

      $oRetorno->message = urlencode("Previsão alterada com sucesso.");
      $oRetorno->status  = 1;
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
      db_fim_transacao(true);
    }

    break;
}
echo $oJson->encode($oRetorno);
?>
