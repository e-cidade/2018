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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");
require_once("model/contabilidade/lancamento/ReceitaFatoGerador.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarReconhecimentoReceitaFatoGerador.model.php");
require_once("model/contabilidade/EventoContabilLancamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");



db_app::import('contabilidade.*');
db_app::import('contabilidade.lancamento.*');
db_app::import("contabilidade.contacorrente.*");
db_app::import("configuracao.Instituicao");
db_app::import("CgmFactory");
db_app::import("contabilidade.planoconta.*");
require_once("std/db_stdClass.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));


$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = "";
$iAnousu            = db_getsession("DB_anousu");

switch ( $oParam->exec ) {

	/**
	 * Inclui a transação na contranslr
	 */
	case 'buscaDadosReceita':

		db_inicio_transacao();
		try {

		  $oDadosHistorico  = buscaDadosHistorico(9102);
      $oDadosOrcReceita = buscaDadosReceita($oParam->o70_codrec);
      $oDadosContaDebito = buscaDadoContaDebito(105, $oDadosOrcReceita->o70_codfon, 1, false);

	    $oRetorno->o57_codfon_debito  = $oDadosContaDebito->o57_codfon_debito;
	    $oRetorno->c57_descr_debito   = urlencode($oDadosContaDebito->c57_descr_debito);
	    $oRetorno->o57_codfon_credito = $oDadosContaDebito->o57_codfon_credito;
	    $oRetorno->c57_descr_credito  = urlencode($oDadosContaDebito->c57_descr_credito);
	    $oRetorno->o70_codrec         = $oDadosOrcReceita->o70_codrec;
	    $oRetorno->o70_valor          = $oDadosOrcReceita->o70_valor;
	    $oRetorno->c50_codhist        = $oDadosHistorico->c50_codhist;
	    $oRetorno->c50_descr          = urlencode($oDadosHistorico->c50_descr);

		  db_fim_transacao(false);

		} catch (Exception $eException) {

			$oRetorno->iStatus  = 2;
			$oRetorno->sMessage = urlencode($eException->getMessage());
			db_fim_transacao(true);
		}

	break;

	/**
	 * case para buscar dados para estorno
	 */

	case 'buscaDadosReceitaEstorno':

	  db_inicio_transacao();

	  try {

		  $oDadosHistorico   = buscaDadosHistorico(9102);
      $oDadosOrcReceita  = buscaDadosReceita($oParam->o70_codrec);
      $oDadosContaDebito = buscaDadoContaDebito(106, $oDadosOrcReceita->o70_codfon, 1, true);



	    $oRetorno->o57_codfon_debito  = $oDadosContaDebito->o57_codfon_debito;
	    $oRetorno->c57_descr_debito   = urlencode($oDadosContaDebito->c57_descr_debito);
	    $oRetorno->o57_codfon_credito = $oDadosContaDebito->o57_codfon_credito;
	    $oRetorno->c57_descr_credito  = urlencode($oDadosContaDebito->c57_descr_credito);
	    $oRetorno->o70_codrec         = $oDadosOrcReceita->o70_codrec;
	    $oRetorno->o70_valor          = $oDadosOrcReceita->o70_valor;
	    $oRetorno->c50_codhist        = $oDadosHistorico->c50_codhist;
	    $oRetorno->c50_descr          = urlencode($oDadosHistorico->c50_descr);
	    db_fim_transacao(false);

	  } catch (Exception $eException) {

	    $oRetorno->iStatus  = 2;
	    $oRetorno->sMessage = urlencode($eException->getMessage());
	    db_fim_transacao(true);
	  }

	break;


	/*
	 * case para salecho inclusão
	 */
	case 'salvarDados' :

	  db_inicio_transacao();
	  try {

        $oReceitaFatoGerador = new ReceitaFatoGerador();
        $oReceitaFatoGerador->setCodigoReceita( $oParam->o70_codrec );
        $oReceitaFatoGerador->setAno( $iAnousu );
        $oReceitaFatoGerador->setHistorico( $oParam->c50_codhist );
        $oReceitaFatoGerador->setValor( $oParam->valor_lancado );
        $oReceitaFatoGerador->setContaCredito($oParam->o57_codfon_credito);
        $oReceitaFatoGerador->setContaDebito($oParam->o57_codfon_debito);

        $sMotivo = addslashes(db_stdClass::normalizeStringJson($oParam->sMotivo));
        $oReceitaFatoGerador->salvar( $sMotivo );
        $oRetorno->sMessage = urlencode("Dados salvos com sucesso");
        db_fim_transacao(false);

	  } catch (BusinessException $eException) {

			$oRetorno->iStatus  = 2;
			$oRetorno->sMessage = urlencode($eException->getMessage());
			db_fim_transacao(true);
		}
	break;


	/*
	 * case para salecho inclusão
	*/
	case 'estornarReceitaFatoGerador' :

	  db_inicio_transacao();
	  try {

	    $oReceitaFatoGerador = new ReceitaFatoGerador();
	    $oReceitaFatoGerador->setSequencial($oParam->c81_sequencial);
      $oReceitaFatoGerador->setCodigoReceita( $oParam->o70_codrec );
      $oReceitaFatoGerador->setAno( $iAnousu );
      $oReceitaFatoGerador->setHistorico( $oParam->c50_codhist );
      $oReceitaFatoGerador->setValor( $oParam->valor_lancado );
      $oReceitaFatoGerador->setContaCredito($oParam->o57_codfon_credito);
      $oReceitaFatoGerador->setContaDebito($oParam->o57_codfon_debito);

      $sMotivo = addslashes(db_stdClass::normalizeStringJson($oParam->sMotivo));
	    $oReceitaFatoGerador->estornar( $sMotivo );
	    $oRetorno->sMessage = urlencode("Dados do estorno salvos com sucesso");
	    db_fim_transacao(false);

	  } catch (DBException $eException) {

	    $oRetorno->iStatus  = 2;
	    $oRetorno->sMessage = urlencode($eException->getMessage());
	    db_fim_transacao(true);
	  }
	  break;




}
echo $oJson->encode($oRetorno);

/**
 *  Busca dados do Histórico
 *
 */
function buscaDadosHistorico($iCodigoHistorico) {

	$oDaoHistorico = db_utils::getDao('conhist');
	$sSqlHistorico =  $oDaoHistorico->sql_query_file($iCodigoHistorico);
	$rsHistorico   =  $oDaoHistorico->sql_record($sSqlHistorico);
	$iHistorico    =  $oDaoHistorico->numrows;

	if ($iHistorico == 0) {
		throw new BusinessException('Histórico de lançamento '.$iCodigoHistorico.' não encontrado.');
	}

	$oDadosHistorico = db_utils::fieldsMemory($rsHistorico,0);
	return $oDadosHistorico;
}

/**
 *  Busca dados da Receita
 */
function buscaDadosReceita($iCodigoReceita) {

	$oDaoOrcReceita   = db_utils::getDao('orcreceita');
	$sWhereOrcReceita = "o70_codrec = {$iCodigoReceita} and o70_anousu = ".db_getsession("DB_anousu");
	$sSqlOrcReceita   =  $oDaoOrcReceita->sql_query_dados_receita(null, null, "*", null, $sWhereOrcReceita );
	$rsOrcReceita     =  $oDaoOrcReceita->sql_record($sSqlOrcReceita);
	$iOrcReceita      =  $oDaoOrcReceita->numrows;

	if ($iOrcReceita == 0) {
		throw new BusinessException('Não encontrado dados relativos a receita, verifique cadastro !!');
	}
	$oDadosReceita = db_utils::fieldsMemory($rsOrcReceita,0);
	return $oDadosReceita;
}

/**
 *  Busca a conta débito de acordo com Código do documento e conta crédito
 */
function buscaDadoContaDebito($iCodigoDocumento, $iCodigoContaCredito, $iOrdem = 1, $lEstorno = false) {

  $sMetodoBuscaConta = "getContaCredito";
  if ($lEstorno) {
    $sMetodoBuscaConta = "getContaDebito";
  }

  $oContaOrcamento           = new ContaOrcamento($iCodigoContaCredito, db_getsession("DB_anousu"));
  if ($oContaOrcamento->getPlanoContaPCASP() == "" ) {

    $sMensagemErro  = "Conta {$iCodigoContaCredito} / {$oContaOrcamento->getEstrutural()} / {$oContaOrcamento->getDescricao()} ";
    $sMensagemErro .= "sem vínculo com o PCASP.";
    throw new Exception($sMensagemErro);
  }
  $iCodigoContaReduzidaPCASP = $oContaOrcamento->getPlanoContaPCASP()->getReduzido();

	$oContaDebito = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));
	$aEventos     = $oContaDebito->getEventoContabilLancamento();

	/**
	 * Percorre cada evento Contabil lançamento
	 * buscando pela regra que contém a conta de crédito passada como parâmetro
	 */
	foreach ($aEventos as $oEvento) {

		if ($iOrdem != $oEvento->getOrdem()) {
			continue;
		}

		$aRegras = $oEvento->getRegrasLancamento();
		foreach ($aRegras as $oRegra) {

			if ($oRegra->$sMetodoBuscaConta() == $iCodigoContaReduzidaPCASP) {

				$oRegraSelecionada = $oRegra;
				break 2;

			}
		}
	}

	if (!isset($oRegraSelecionada)) {
		throw new Exception ("Contas crédito/débito não vinculadas com no PCASP.");
	}

	$iAnoUso = db_getsession("DB_anousu");
	$sWhereContaDebito   = "c61_reduz = {$oRegraSelecionada->getContaDebito()} and c61_anousu = {$iAnoUso}";
	$oDaoConPlanoDebito  = db_utils::getDao('conplano');
	$sSqlConPlanoDebito  = $oDaoConPlanoDebito->sql_query(null, null, "*", null, $sWhereContaDebito);
	$rsConPlanoDebito    = $oDaoConPlanoDebito->sql_record($sSqlConPlanoDebito);
	$iLinhasContaDebito  = $oDaoConPlanoDebito->numrows;

	if ($iLinhasContaDebito == 0) {
		throw new Exception("Não foi possível encontrar a descrição da conta débito.");
	}

	$sWhereContaCredito   = "c61_reduz = {$oRegraSelecionada->getContaCredito()} and c61_anousu = {$iAnoUso}";
	$oDaoConPlanoCredito  = db_utils::getDao('conplano');
	$sSqlConPlanoCredito  = $oDaoConPlanoCredito->sql_query(null, null, "*", null, $sWhereContaCredito);
	$rsConPlanoCredito    = $oDaoConPlanoCredito->sql_record($sSqlConPlanoCredito);
	$iLinhasContaCredito  = $oDaoConPlanoCredito->numrows;

	if ($iLinhasContaCredito == 0) {
		throw new Exception("Não foi possível encontrar a descrição da conta crédito.");
	}

	$oDadosContaDebito                         = db_utils::fieldsMemory($rsConPlanoDebito, 0);
	$oDadosContaCredito                        = db_utils::fieldsMemory($rsConPlanoCredito, 0);
	$oRetornoDebitoCredito                     = new stdClass();
	$oRetornoDebitoCredito->o57_codfon_debito  = $oDadosContaDebito->c61_reduz;
	$oRetornoDebitoCredito->c57_descr_debito   = $oDadosContaDebito->c60_descr;
	$oRetornoDebitoCredito->o57_codfon_credito = $oDadosContaCredito->c61_reduz;
	$oRetornoDebitoCredito->c57_descr_credito  = $oDadosContaCredito->c60_descr;
	return $oRetornoDebitoCredito;
}