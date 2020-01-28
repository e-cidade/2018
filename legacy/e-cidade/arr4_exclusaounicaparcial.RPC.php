<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

define("MENSAGENS", "tributario.arrecadacao.arr4_exclusaounicaparcial.");

$oJson              = new services_json(0, true);
$oParametros        = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  $oCotaUnica = new CotaUnica;

  switch ($oParametros->sExecucao) {

    case "getDadosUnica":

      $oDataHoje             = new DBDate(date("Y-m-d",db_getsession("DB_datausu")));
      $iIntervaloDataInicial = 0;
      $iIntervaloDataFim     = 0;

      if(!empty($oParametros->sDataVencimentoInicial)){

        $oDataVencimentoInicial = new DBDate($oParametros->sDataVencimentoInicial);
        $iIntervaloDataInicial  = DBDate::calculaIntervaloEntreDatas($oDataVencimentoInicial, $oDataHoje, "d");
      }

      if(!empty($oParametros->sDataVencimentoFinal)){

        $oDataVencimentoFinal = new DBDate($oParametros->sDataVencimentoFinal);
        $iIntervaloDataFim    = DBDate::calculaIntervaloEntreDatas($oDataVencimentoFinal, $oDataHoje, "d");
      }

      if($iIntervaloDataInicial < 0 || $iIntervaloDataFim < 0){
        throw new Exception(_M(MENSAGENS."erro_data_valor"));
      }

      $oCotaUnica->setCgm($oParametros->iCgm);
      $oCotaUnica->setMatricula($oParametros->iMatricula);
      $oCotaUnica->setInscricao($oParametros->iInscricao);
      $oCotaUnica->setDataOperacaoInicial($oParametros->sDataOperacaoInicial);
      $oCotaUnica->setDataOperacaoFinal($oParametros->sDataOperacaoFinal);
      $oCotaUnica->setDataVencimentoInicial($oParametros->sDataVencimentoInicial);
      $oCotaUnica->setDataVencimentoFinal($oParametros->sDataVencimentoFinal);
      $oCotaUnica->setPercentualDesconto($oParametros->nPercentualDesconto);
      $oCotaUnica->setObservacao($oParametros->sObservacao);

      $oRetorno->aCotaUnicaParcial = $oCotaUnica->getUnicaParcial();

      break;

    case "excluirUnica":

      if(empty($oParametros->aCodigoUnica) || !is_array($oParametros->aCodigoUnica)){
        throw new Exception(_M( MENSAGENS . "erro_codigo_unica"));
      }

      $lRetornoExclusaoUnica = $oCotaUnica->excluirUnicaParcial($oParametros->aCodigoUnica);
      if($lRetornoExclusaoUnica){
        $oRetorno->sMessage = urlencode(_M( MENSAGENS . "exclusao_sucesso"));
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