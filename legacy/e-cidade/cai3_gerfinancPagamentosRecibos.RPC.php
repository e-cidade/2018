<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson    = new services_json();
$oRetorno = new stdClass();

$oRetorno->erro = false;
$oRetorno->sMensagem = '';

$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));

define("MENSAGENS", "arquivo-de-mensagens.json");

try {

  db_inicio_transacao();

  switch($oParametros->sExecucao) {

    case "getReciboPago":

      $aReciboPago = array();
      $aReciboPago = getReciboPago($oParametros);
      $oDados      = new stdClass();

      $oDados->iNivel  = $oParametros->iProximoNivel;
      $oDados->aLinhas = $aReciboPago;

      $oRetorno->oDados = DBString::utf8_encode_all($oDados);
      break;

    case "getReciboPagoCodigoArrecadacao":

      $aReciboPago = array();
      $aReciboPago = getReciboPagoCodigoArrecadacao( $oParametros->iCodigo );

      $oDados = new stdClass();

      $oDados->iNivel  = $oParametros->iProximoNivel;
      $oDados->aLinhas = $aReciboPago;

      $oRetorno->oDados = DBString::utf8_encode_all($oDados);
      break;

    case "imprimirDados":

      $aReciboPago = array();

      if ( $oParametros->iNivel == RelatorioPagamentoRecibo::NIVEL_UM ) {
        $aReciboPago = getReciboPago($oParametros);
      }

      if ( $oParametros->iNivel == RelatorioPagamentoRecibo::NIVEL_DOIS ) {
        $aReciboPago = getReciboPagoCodigoArrecadacao( $oParametros->iCodigo );
      }

      $oRelatorio = new RelatorioPagamentoRecibo;
      $oRelatorio->setNivel($oParametros->iNivel);
      $oRelatorio->setDados($aReciboPago);

      $oRetorno->sArquivo = $oRelatorio->montarRelatorio($oParametros);
      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);

/**
 * Função responsável por fazer a consulta de dados por Código de Arrecadação
 *
 * @param  integer $iCodigoArrecadacao
 * @return array
 */
function getReciboPagoCodigoArrecadacao( $iCodigoArrecadacao ) {

  $oReciboPago = new ReciboPago;

  $oReciboPago->setCodigoArrecadacao($iCodigoArrecadacao);
  return $oReciboPago->getReciboPagoCodigoArrecadacao();
}

/**
 * Função responsável por fazer a consuolta utilizando os filtros do formulário
 *
 * @param   integer $oParametros  Filtros do formulário
 * @return  array
 */
function getReciboPago( $oParametros ) {

  $oReciboPago = new ReciboPago;

  if(!empty($oParametros->sDataInicio)){

    $oDataInicio = DateTime::createFromFormat('d/m/Y', $oParametros->sDataInicio);
    $oReciboPago->setDataInicio($oDataInicio);
  }

  if (!empty($oParametros->sDataFim)) {

    $oDataFim = DateTime::createFromFormat('d/m/Y', $oParametros->sDataFim);
    $oReciboPago->setDataFim($oDataFim);
  }

  $oReciboPago->setCodigoArrecadacao($oParametros->iCodigoArrecadacao);
  $oReciboPago->setTipoDebito($oParametros->iTipoDebito);

  $aReciboPago = array();

  if(!empty($oParametros->iCgm)){
    $aReciboPago = $oReciboPago->getReciboPagoCgm($oParametros->iCgm);
  }elseif(!empty($oParametros->iMatric)){
    $aReciboPago = $oReciboPago->getReciboPagoMatric($oParametros->iMatric);
  }elseif(!empty($oParametros->iInscr)){
    $aReciboPago = $oReciboPago->getReciboPagoInscr($oParametros->iInscr);
  }

  return $aReciboPago;
}