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
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvar":

      if ($oParam->iCodigoAutorizacao == "") {
        $oParam->iCodigoAutorizacao = null;
      }

      $oAutorizacaoCirculacao = new VeiculoAutorizacaoCirculacao();
      $oAutorizacaoCirculacao->setCodigo($oParam->iCodigoAutorizacao);
      $oAutorizacaoCirculacao->setCodigoMotorista($oParam->iCodigoMotorista);
      $oAutorizacaoCirculacao->setCodigoVeiculo($oParam->iCodigoVeiculo);

      if (empty($oParam->iCodigoAutorizacao)) {

        $oAutorizacaoCirculacao->setCodigoInstituicao(db_getsession('DB_instit'));
        $oAutorizacaoCirculacao->setCodigoDepartamento(db_getsession('DB_coddepto'));
        $oAutorizacaoCirculacao->setDataEmissao(new DBDate(date("Y-m-d", db_getsession('DB_datausu'))));
      }

      $oAutorizacaoCirculacao->setDataInicial(new DBDate($oParam->sDataInicial));
      $oAutorizacaoCirculacao->setDataFinal(new DBDate($oParam->sDataFinal));
      $oAutorizacaoCirculacao->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao));

      $oAutorizacaoCirculacao->salvar();

      $oRetorno->iCodigoAutorizacao = $oAutorizacaoCirculacao->getCodigo();
      break;

    case "buscar":

      if ($oParam->iCodigoAutorizacao == "") {
        throw new Exception("O código da Autoricação de Circulação de Veículo não foi informado.");
      }

      $oAutorizacaoCirculacao = new VeiculoAutorizacaoCirculacao($oParam->iCodigoAutorizacao);

      $oRetorno->iMotorista   = $oAutorizacaoCirculacao->getMotorista()->getCodigo();
      $oRetorno->sMotorista   = urlencode($oAutorizacaoCirculacao->getMotorista()->getCGMMotorista()->getNomeCompleto());
      $oRetorno->iVeiculo     = $oAutorizacaoCirculacao->getVeiculo()->getCodigo();
      $oRetorno->sVeiculo     = urlencode($oAutorizacaoCirculacao->getVeiculo()->getPlaca());
      $oRetorno->sDataInicial = $oAutorizacaoCirculacao->getDataInicial()->getDate(DBDate::DATA_PTBR);
      $oRetorno->sDataFinal   = $oAutorizacaoCirculacao->getDataFinal()->getDate(DBDate::DATA_PTBR);
      $oRetorno->sObservacao  = urlencode($oAutorizacaoCirculacao->getObservacao());

      break;

    default:
      throw new Exception("Nenhuma opção definida.");
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
  db_fim_transacao(true);
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);
