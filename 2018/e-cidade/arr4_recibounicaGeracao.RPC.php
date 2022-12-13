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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oDaoReciboUnica        = db_utils::getDao('recibounica');
$oDaoReciboUnicaGeracao = db_utils::getDao('recibounicageracao');

$oJson                  = new services_json();

$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->status       = 1;
$oRetorno->message      = '';

$aDadosRetorno          = array();

try {
	switch ($oParam->exec) {

		case "processaDados":

		  $aNumpres = array();

		  if($oParam->oDados->sChavePesquisa == null) {
		    $sTipoGeracao  = "G";
		    $sSqlNumpres   = $oDaoReciboUnicaGeracao->sql_query_pesquisa($oParam->oDados->sTipoPesquisa,
		                                                                 $oParam->oDados->sChavePesquisa,
		                                                                 true,
		                                                                 $oParam->oDados->iCadTipoDebito);

        if( !empty($oParam->oDados->aExercicio) ){

          $sExerciciosSelecionados = implode(', ', $oParam->oDados->aExercicio);

          $sWhere = " and extract(year from arrecad.k00_dtoper) in ({$sExerciciosSelecionados})";

          if ( $oParam->oDados->sTipoPesquisa == "M" ) {
            $sWhere = " and j20_anousu in ({$sExerciciosSelecionados})";
          }

          $sSqlNumpres .= $sWhere;
        }

		    $rsNumpres     = $oDaoReciboUnicaGeracao->sql_record($sSqlNumpres);
		    if($rsNumpres && pg_num_rows($rsNumpres)) {

		      $aRowsNumpres = db_utils::getCollectionByRecord($rsNumpres);
		      foreach($aRowsNumpres as $oNumpre) {
		        $aNumpres[] = $oNumpre->k00_numpre;
		      }
		    }
		  } else {

		    $sTipoGeracao  = "I";
		    $aNumpres = $oParam->oDados->aNumpres;
		  }
		  $dtLancamento = implode("-",array_reverse(explode("/",$oParam->oDados->dtLancamento)));
		  $dtVencimento = implode("-",array_reverse(explode("/",$oParam->oDados->dtVencimento)));

      $sObservacao = addslashes(db_stdClass::normalizeStringJsonEscapeString($oParam->oDados->sObservacoes));

 		  db_inicio_transacao();
		  try {

		  /**
		   * inserindo dados da recibounica geração
		   */
  		  $oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
  		  $oDaoReciboUnicaGeracao->ar40_dtoperacao         = $dtLancamento;
  		  $oDaoReciboUnicaGeracao->ar40_dtvencimento       = $dtVencimento;
  		  $oDaoReciboUnicaGeracao->ar40_percentualdesconto = $oParam->oDados->nPercentual;
  		  $oDaoReciboUnicaGeracao->ar40_tipogeracao        = $sTipoGeracao;
  		  $oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
  		  $oDaoReciboUnicaGeracao->ar40_observacao         = $sObservacao;
  		  $oDaoReciboUnicaGeracao->incluir(null);

  		  if($oDaoReciboUnicaGeracao->erro_status == 0) {
  		    throw new Exception($oDaoReciboUnicaGeracao->erro_msg);
  		  } else {

  		    foreach ($aNumpres as $iNumpre) {
  		      /**
  		       * Incluindo dados na recibunica
  		       */
    		    $oDaoReciboUnica->k00_numpre             = $iNumpre;
    		    $oDaoReciboUnica->k00_dtvenc             = $dtVencimento;
    		    $oDaoReciboUnica->k00_dtoper             = $dtLancamento;
    		    $oDaoReciboUnica->k00_percdes            = $oParam->oDados->nPercentual;
    		    $oDaoReciboUnica->k00_tipoger            = $sTipoGeracao;
    		    $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
    		    $oDaoReciboUnica->incluir(null);

    		    if ($oDaoReciboUnica->erro_status == 0) {
    		      throw new Exception($oDaoReciboUnica->erro_msg);
    		    }
  		    }
  		  }
  		  db_fim_transacao(false);
		  } catch(Exception $eErroBanco) {

		    db_fim_transacao(true);
		    throw new ErrorException("Erro no Base de Dados: \n"+ $eErroBanco->getMessage());
		  }

		  $oRetorno->msg = $oDaoReciboUnicaGeracao->erro_msg;
		break;

		case "prorrogar":

		  $iCodGeracao           = $oParam->iCodGeracao;
		  $dtVencimento          = implode("-", array_reverse(explode("/",$oParam->dtVencimento)));
		  $dtLancamento          = implode("-", array_reverse(explode("/",$oParam->dtLancamento)));
		  $iPercDesconto         = $oParam->iPercDesconto;
		  $sObs                  = $oParam->sObs;

		  $oDaoReciboUnicaGeracao->ar40_sequencial         = $iCodGeracao;
		  $oDaoReciboUnicaGeracao->ar40_dtvencimento       = $dtVencimento;
		  $oDaoReciboUnicaGeracao->ar40_dtoperacao         = $dtLancamento;
		  $oDaoReciboUnicaGeracao->ar40_percentualdesconto = $iPercDesconto;
		  $oDaoReciboUnicaGeracao->ar40_observacao         = $sObs;
		  $oDaoReciboUnicaGeracao->alterar($oDaoReciboUnicaGeracao->ar40_sequencial);

		  if($oDaoReciboUnicaGeracao->erro_status == 0) {
		    throw new ErrorException($oDaoReciboUnicaGeracao->erro_msg);
		  }

		  $sSqlAtualizaReciboUnica = $oDaoReciboUnica->sql_query_file(null, "k00_sequencial", null, "k00_recibounicageracao = {$iCodGeracao}");
      $rsAtualizaReciboUnica   = $oDaoReciboUnica->sql_record($sSqlAtualizaReciboUnica);

      if($oDaoReciboUnica->numrows > 0) {

      	$aDadosAtualizaReciboUnica = db_utils::getCollectionByRecord($rsAtualizaReciboUnica);

      	foreach ($aDadosAtualizaReciboUnica as $iIndAtualiza => $oValorAtualiza) {

		      $oDaoReciboUnica->k00_sequencial         = $oValorAtualiza->k00_sequencial;
      		$oDaoReciboUnica->k00_dtvenc             = $dtVencimento;
		      $oDaoReciboUnica->k00_dtoper             = $dtLancamento;
		      $oDaoReciboUnica->k00_percdes            = $iPercDesconto;
		      $oDaoReciboUnica->alterar($oDaoReciboUnica->k00_sequencial);

		      if ($oDaoReciboUnica->erro_status == 0) {
		        throw new Exception($oDaoReciboUnica->erro_msg);
		      }
      	}

      }
		  $oRetorno->prorrogacao = "Processamento Realizado";
		break;

		default:
		  throw new ErrorException("Nenhuma Opção Definida");
	  break;
	}

} catch (ErrorException $eErro) {

	$oRetorno->status = 2;
	$oRetorno->msg    = $eErro->getMessage();
}

$oRetorno->msg    = urlencode($oRetorno->msg);
$oRetorno->aDados = $aDadosRetorno;

echo $oJson->encode($oRetorno);