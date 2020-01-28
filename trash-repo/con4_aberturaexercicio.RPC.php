<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/JSON.php");

require_once("std/db_stdClass.php");
require_once("std/DBNumber.php");
require_once("dbforms/db_funcoes.php");

require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

db_app::import("exceptions.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");
db_app::import("recursosHumanos.RefactorProvisaoFerias");
db_app::import("orcamento.*");
db_app::import("Dotacao");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iInstituicao = db_getsession("DB_instit");
$iAnoSessao   = db_getsession("DB_anousu");

try {

  switch ($oParam->exec) {

  	/**
  	 * Case para pegar os valores previsto para o ano, da orcreceita e orcdotacao
  	 */
  	case 'getDadosOrcamento' :

  		$oRetorno->nValorDotacao = Dotacao::getValorPrevistoNoAno($iAnoSessao,$iInstituicao);
  		$oRetorno->nValorReceita = ReceitaContabil::getValorPrevistoAno($iAnoSessao, $iInstituicao);
  		$oRetorno->iAnoSessao    = $iAnoSessao;

  		$oDaoAberturaexercicioorcamento = db_utils::getDao("aberturaexercicioorcamento");
  		$sWhere                         = "     c104_instit     = {$iInstituicao} ";
  		$sWhere                        .= " and c104_ano        = {$iAnoSessao}      ";
  		$sWhere                        .= " and c104_processado = '{$oParam->lProcessados}' ";
  		$sSqlAberturaexercicioorcamento = $oDaoAberturaexercicioorcamento->sql_query_file(null, "1", null, $sWhere);
  		$rsAberturaexercicioorcamento   = $oDaoAberturaexercicioorcamento->sql_record($sSqlAberturaexercicioorcamento);

  		$oRetorno->lBloquearTela        = false;
  		if ($oDaoAberturaexercicioorcamento->numrows > 0) {
  			$oRetorno->lBloquearTela = true;
  		}


  	break;

  	/**
  	 * Case para gerar lancamento contabil para valores previsto para o ano
  	 * Um lancamento para receita e outro pra despesa
  	 *
  	 * @todo criar funcao para usar tanto no processar quanto no desprocessar
  	 */
  	case 'processar' :

  	  db_inicio_transacao();

  	  $oDaoAberturaexercicioorcamento = db_utils::getDao("aberturaexercicioorcamento");
  	  $sWhere                         = "c104_instit = {$iInstituicao} and c104_ano = {$iAnoSessao}";
  	  $sSqlAberturaexercicioorcamento = $oDaoAberturaexercicioorcamento->sql_query_file(null, "*", null, $sWhere);
  	  $rsAberturaexercicioorcamento   = $oDaoAberturaexercicioorcamento->sql_record($sSqlAberturaexercicioorcamento);

  	  $oDaoAberturaexercicioorcamento->c104_usuario    = db_getsession("DB_id_usuario");
      $oDaoAberturaexercicioorcamento->c104_instit     = db_getsession("DB_instit");
      $oDaoAberturaexercicioorcamento->c104_ano        = $iAnoSessao;
      $oDaoAberturaexercicioorcamento->c104_data       = date('Y-m-d', db_getsession("DB_datausu"));
      $oDaoAberturaexercicioorcamento->c104_processado = "true";

      if ( $oDaoAberturaexercicioorcamento->numrows > 0 ) {

        $oAberturaexercicioorcamento = db_utils::fieldsMemory($rsAberturaexercicioorcamento, 0);

        /**
         * Caso já exista um processamento para o período, não poderá haver novo lançamento sem haver um estorno
         */
    	  if ( $oAberturaexercicioorcamento->c104_processado == "t" ) {
    	  	throw new Exception("Não é possível processar novamente lançamentos da abertura de exercícios do ano {$iAnoSessao}");
    	  }

        $oDaoAberturaexercicioorcamento->c104_sequencial	= $oAberturaexercicioorcamento->c104_sequencial;
        $oDaoAberturaexercicioorcamento->alterar($oAberturaexercicioorcamento->c104_sequencial);

      } else {
        $oDaoAberturaexercicioorcamento->incluir(null);
      }

      if ($oDaoAberturaexercicioorcamento->erro_status == "0") {
        throw new BusinessException("Erro técnico: Não foi possível realizar lançamentos !");
      }

      $iSequencialAberturaExercicio = $oDaoAberturaexercicioorcamento->c104_sequencial;
      $sObservacao                  = $oParam->sObservacao;

      /**
	     * Receita - Lancamento contabil para abertura de exercicio
	     */
      $iTipoDocumento = 2003;
  	  $nValorReceita  = ReceitaContabil::getValorPrevistoAno( $iAnoSessao, $iInstituicao );

      if ($nValorReceita > 0) {

		    executaLancamento($iTipoDocumento, $nValorReceita, $iSequencialAberturaExercicio, $sObservacao );

      }

	    /**
	     * Despesa - Lancamento contabil para abertura de exercicio
	     */
	    $iTipoDocumento = 2001;
  	  $nValorDespesa  = Dotacao::getValorPrevistoNoAno( $iAnoSessao, $iInstituicao );

  	  if ($nValorDespesa > 0) {
  	  	executaLancamento($iTipoDocumento, $nValorDespesa, $iSequencialAberturaExercicio, $sObservacao );
  	  }

  	  $oRetorno->message = 'Lançamentos processados com sucesso.';

  	  db_fim_transacao(false);

  	break;

  	/**
  	 * Case para estornar um lancamento contabil realizado previamente
  	 * Um lancamento para receita e outro pra despesa
  	 *
  	 * @todo criar funcao para usar tanto no processar quanto no desprocessar
  	 */
  	case 'desprocessar' :

  	  db_inicio_transacao();

  		$oDaoAberturaexercicioorcamento = db_utils::getDao("aberturaexercicioorcamento");
  		$sWhere                         = "c104_instit = {$iInstituicao} and c104_ano = {$iAnoSessao} and c104_processado = 't'";
  		$sSqlAberturaexercicioorcamento = $oDaoAberturaexercicioorcamento->sql_query_file(null, "*", null, $sWhere);
  		$rsAberturaexercicioorcamento   = $oDaoAberturaexercicioorcamento->sql_record($sSqlAberturaexercicioorcamento);

  		if ($oDaoAberturaexercicioorcamento->numrows == "0") {
  			throw new Exception("Não há lançamentos para abertura de exercicio para desprocessamento");
  		}

  		$oDaoAberturaexercicioorcamento->c104_usuario    = db_getsession("DB_id_usuario");
  		$oDaoAberturaexercicioorcamento->c104_instit     = db_getsession("DB_instit");
  		$oDaoAberturaexercicioorcamento->c104_ano        = $iAnoSessao;
  		$oDaoAberturaexercicioorcamento->c104_data       = date('Y-m-d', db_getsession("DB_datausu"));
  		$oDaoAberturaexercicioorcamento->c104_processado = "false";

  		$oAberturaexercicioorcamento 								      = db_utils::fieldsMemory($rsAberturaexercicioorcamento, 0);
  		$oDaoAberturaexercicioorcamento->c104_sequencial	= $oAberturaexercicioorcamento->c104_sequencial;
  		$oDaoAberturaexercicioorcamento->alterar($oAberturaexercicioorcamento->c104_sequencial);

  		if ($oDaoAberturaexercicioorcamento->erro_status == "0") {
  			throw new BusinessException("Erro técnico: Não foi possível estornar o lançamento da depreciação!");
  		}

  		$iSequencialAberturaExercicio = $oDaoAberturaexercicioorcamento->c104_sequencial;
  		$sObservacao                  = $oParam->sObservacao;

  		/**
  		 * Receita - Lancamento contabil para abertura de exercicio
  		 */
  		$iTipoDocumento = 2004;
  		$nValorReceita  = ReceitaContabil::getValorPrevistoAno($iAnoSessao, $iInstituicao);

  		if ($nValorReceita > 0) {
    		executaLancamento($iTipoDocumento, $nValorReceita, $iSequencialAberturaExercicio, $sObservacao);
  		}

  		/**
  		 * Despesa - Lancamento contabil para abertura de exercicio
  		 */
  		$iTipoDocumento = 2002;
  		$nValorDespesa  = Dotacao::getValorPrevistoNoAno($iAnoSessao, $iInstituicao);

  		if ($nValorDespesa > 0 ) {
    		executaLancamento($iTipoDocumento, $nValorDespesa, $iSequencialAberturaExercicio, $sObservacao);
  		}

  		$oRetorno->message = 'Lançamentos desprocessados com sucesso.';

  		db_fim_transacao(false); // @todo

  	break;

  }

} catch (BusinessException $oErro){

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

} catch (ParameterException $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

} catch (DBException $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();

} catch (Exception $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlEncode($oRetorno->message);

echo $oJson->encode($oRetorno);

/**
 * Executa lancamento
 * @param integer $iTipoDocumento
 * @param float   $nValorLancamento
 * @param integer $iSequencialAberturaExercicio
 * @param string  $sObservacao
 */
function executaLancamento($iCodigoDocumento, $nValorLancamento, $iSequencialAberturaExercicio, $sObservacao) {

	/**
	 * Descobre o codigo do documento pelo tipo
	 */
	$oEventoContabil  = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));
	$aLancamentos     = $oEventoContabil->getEventoContabilLancamento();
	$iCodigoHistorico = $aLancamentos[0]->getHistorico();

	unset($oDocumentoContabil);
	unset($aLancamentos);

	$oLancamentoAuxiliarAberturaExercicio = new LancamentoAuxiliarAberturaExercicioOrcamento();
	$oLancamentoAuxiliarAberturaExercicio->setObservacaoHistorico($sObservacao);
	$oLancamentoAuxiliarAberturaExercicio->setValorTotal($nValorLancamento);
	$oLancamentoAuxiliarAberturaExercicio->setHistorico($iCodigoHistorico);
	$oLancamentoAuxiliarAberturaExercicio->setAberturaExercicioOrcamento($iSequencialAberturaExercicio);
	$oEventoContabil->executaLancamento($oLancamentoAuxiliarAberturaExercicio);

	return true;
}