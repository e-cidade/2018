<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/JSON.php"));

require_once(modification("std/db_stdClass.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBNumber.php"));

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

db_app::import("exceptions.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.*");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");
db_app::import("recursosHumanos.RefactorProvisaoFerias");


$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iInstituicaoSessao = db_getsession("DB_instit");
$iAnoSessao         = db_getsession("DB_anousu");

try {
	
  switch ($oParam->exec) {
  
  	/**
  	 * Retorna mes disponivel de provisao para calculo
  	 */
  	
    case 'getMesesProvisaoDisponivel':
  
      try {
  
      	$lProcessado = $oParam->lProcessado == 'true' ? true : false;
        $oDadosProvisao = RefactorProvisaoFerias::getCompentenciaDisponivelEscrituracao($lProcessado, $oParam->sTipoProvisao, $iAnoSessao, $iInstituicaoSessao);
        
        $oRetorno->iMesDisponivel = $oDadosProvisao->iMes;
        $oRetorno->iAnoDisponivel = $oDadosProvisao->iAno;
  
      } catch (Exception $eException) {
  
        $oRetorno->status  = 2;
        $oRetorno->message = str_replace("\n", "\\n", urlencode($eException->getMessage()));
        
      } catch (BusinessException $eBusinessException) {
  
        $oRetorno->status  = 2;
        $oRetorno->message = str_replace("\n", "\\n", urlencode($eBusinessException->getMessage()));
      }
  
    break;

    /**
     * Estornar provisoes
     */
    case 'estornar' : 
    	
			db_inicio_transacao();
			
    	/**
    	 * Como o documeto é visivel na tela. Nao ha necessidade de chamar:
    	 * SingletonRegraDocumentoContabil::getDocumento($iTipoDocumento)
    	 * como eh feito nos outros programas
    	 */
    	$oLancamentoAuxiliar = null;
    	
    	if ($oParam->iCodigoDocumento != 301 && $oParam->iCodigoDocumento != 303) {
    		
    		$sMsgErro  = 'O documento informado não é um documento de estorno provisão de férias / 13º salário.\n.';
    		$sMsgErro .= "Código do documento informado: {$oParam->iCodigoDocumento}";
    		throw new BusinessException($sMsgErro);
    	}
    	
    	$oLancamentoAuxiliar = buscaLancamentoAuxiliar($oParam->iCodigoDocumento);
    	processarLancamentosContabeis($oLancamentoAuxiliar, $oParam);

			db_fim_transacao(false); 
    	
    break;

    /**
     * Processar provisoes
     */
    case 'processar':

			db_inicio_transacao();

      /**
    	 * Como o documeto é visivel na tela. Nao ha necessidade de chamar:
    	 * SingletonRegraDocumentoContabil::getDocumento($iTipoDocumento)
    	 * como eh feito nos outros programas
    	 */
    	$oLancamentoAuxiliar = null;
    	 
    	if ($oParam->iCodigoDocumento != 300 && $oParam->iCodigoDocumento != 302) {
    	
    		$sMsgErro  = 'O documento informado não é um documento de provisão de férias / 13º salário.\n.';
    		$sMsgErro .= "Código do documento informado: {$oParam->iCodigoDocumento}";
    		throw new BusinessException($sMsgErro);
    	}
    	 
    	$oLancamentoAuxiliar = buscaLancamentoAuxiliar($oParam->iCodigoDocumento);
    	processarLancamentosContabeis($oLancamentoAuxiliar, $oParam);

			db_fim_transacao(false); 

    break;
    	
    /**
     * Retorna contas de credito e debito
     */
    case "getContasCreditoDebito":
    
      /**
       * Buscamos os dados da conta credito e debito
       */
      $oEventoContabil    = new EventoContabil($oParam->iCodigoDocumento, $iAnoSessao);
      $aLancamentos       = $oEventoContabil->getEventoContabilLancamento();
			if (empty($aLancamentos) || !isset($aLancamentos[0])) {

        $sErro  = "Houve um problema ao verificar as contas crédito/débito para o documento {$oParam->iCodigoDocumento} ";
        $sErro .= "no exercício {$iAnoSessao}. Verifique o cadastro de documentos.";
        throw new BusinessException($sErro);
      }

      $aRegraLancamento = $aLancamentos[0]->getRegrasLancamento();

      if (empty($aRegraLancamento) || !isset($aRegraLancamento[0])) {

				$sErro  = "Houve um problema ao verificar as contas crédito/débito para o documento {$oParam->iCodigoDocumento} ";
				$sErro .= "no exercício {$iAnoSessao}. Verifique o cadastro de documentos.";
				throw new BusinessException($sErro);
			}

			$oPlanoContaDebito  = new ContaPlanoPCASP(null, $iAnoSessao, $aRegraLancamento[0]->getContaDebito());
			$oPlanoContaCredito = new ContaPlanoPCASP(null, $iAnoSessao, $aRegraLancamento[0]->getContaCredito());

			$oRetorno->iContaDebito  = $aRegraLancamento[0]->getContaDebito();
			$oRetorno->sContaDebito  = urlencode($oPlanoContaDebito->getDescricao());
			$oRetorno->iContaCredito = $aRegraLancamento[0]->getContaCredito();
			$oRetorno->sContaCredito = urlencode($oPlanoContaCredito->getDescricao());

      
    break;
			
    /**
     * retorna dados da provisao
     */
    case 'getDadosProvisao' :
    
    	switch ($oParam->iCodigoDocumento) {
    		 
    		/**
    		 * Processar ferias
    		 */
    		case '300':
    		case '301':
    
    			$sClasse    = "gerfprovfer";
    			$sSigla     = "r93";
    		break;
    
    			/**
    			 * Processar 13º
    			 */
    		case '302':
    		case '303':
    
    			$sClasse    = "gerfprovs13";
    			$sSigla     = "r94";

    		break;
    
    	}
    
    	$iDocumento 		 = (int) $oParam->iCodigoDocumento;
    	$iMes            = $oParam->iMes;
    	$iAno            = $oParam->iAno;
    	$iUltimoDiaMes   = cal_days_in_month(1, $iMes, $iAno);
    
    	/**
    	 *  Concatena dia, mes e ano para formar data final e data inicial para calculo da provisao
    	*/
    	$dtDataInicial   = "{$iAno}-01-1";
    	$dtDataFinal     = "{$iAno}-{$iMes}-{$iUltimoDiaMes}";
    
    	/**
    	 * Procura conta cretido
    	 */
    	$oEventoContabil 					 = new EventoContabil($iDocumento, $oParam->iAno);
    	$oEventoContabilLancamemto = $oEventoContabil->getEventoContabilLancamento();
    	$oRegra										 = $oEventoContabilLancamemto[0]->getRegrasLancamento();
    	$iContaCredito             = $oRegra[0]->getContaCredito();
    	$sWhere                    = "c61_reduz = {$iContaCredito}";

    	/**
    	 * Verifica se é um estorno, para que busque o valor do lançamento a ser estornado
    	 */
    	if ($oParam->lEstorno == 'true') {
    		
    		/**
    		 * Verifica qual tabela deve usar para verificar o valor 
    		 */
    		$sTabela         = "conlancamprovisaodecimoterceiro"; 
    		$sSigla          = "c100";
    		$iTipoLancamento = 1;
    		
    		if ($oParam->sTipoLancamento == 'provisaoFerias') {
    			
    			$sTabela         = "conlancamprovisaoferias";
    			$sSigla          = "c101";
    			$iTipoLancamento = 2;
    		}
    		
    		$oDaoEscrituraProvisao = db_utils::getDao($sTabela);
				$iInstituicaoSessao    = db_getsession("DB_instit");
				
				$sCampos = " c70_valor ";
				
				$sWhere  = " c102_ano       		   = {$oParam->iAno}       ";
				$sWhere .= " and c102_mes          = {$oParam->iMes}       ";
				$sWhere .= " and c102_instit       = {$iInstituicaoSessao} ";
				$sWhere .= " and c102_tipoprovisao = {$iTipoLancamento}    ";
				
				$sSqlEscrituraProvisao = $oDaoEscrituraProvisao->sql_query(null, $sCampos, null, $sWhere);
				$rsEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlEscrituraProvisao);
				
				if ( $oDaoEscrituraProvisao->numrows == 0 ) {					
					throw new Exception('Erro ao buscar os dados do lançamento para estorno.');
				}
				
				$oEscrituraProvisao = db_utils::fieldsMemory($rsEscrituraProvisao, 0);
				
				/**
				 * Campos nao utilizados no caso de estorno
				 */
				$oRetorno->nSaldoAnterior   = 0;
				$oRetorno->nValorProvisao		= 0;
				
				$oRetorno->nValorLancamento = $oEscrituraProvisao->c70_valor;
				

    		break;
    	}
    	
    	/**
    	 * Busca saldo alterio pela reduzido/conta
    	 */
    	$rsPlanoContaDados = db_planocontassaldo_matriz($oParam->iAno, $dtDataInicial, $dtDataFinal, false, $sWhere);
    	$iTotalContas      = pg_num_rows($rsPlanoContaDados);
    
    	for($iConta = 0; $iConta < $iTotalContas; $iConta++) {
    
    		$oDadosConta = db_utils::fieldsMemory($rsPlanoContaDados, $iConta);
    
    		if ($oDadosConta->c61_reduz == $iContaCredito) {
    			break;
    		}
    		unset($oDadosConta);
    	}
    
    	if (!isset($oDadosConta)){
    		throw new Exception("Erro técnico: Conta para lançamento da Provisão não encontrada!");
    	}    		
    	
    	/**
    	 * Procura valor da provisao
    	 */
    	$oDaoProvisao = db_utils::getDao($sClasse);
    	$sCampos  = "round(coalesce(sum(case                                         ";
    	$sCampos .= "               when {$sSigla}_pd = 1                            ";
    	$sCampos .= "                 then {$sSigla}_valor                           ";
    	$sCampos .= "               when {$sSigla}_pd = 2 then {$sSigla}_valor*(-1)  ";
    	$sCampos .= " 				 end), 0), 2) as valor_provisao					               ";
    
    	$sWhere   = "  {$sSigla}_mesusu = {$iMes}             ";
    	$sWhere  .= "   AND {$sSigla}_anousu = {$iAno}        ";
    	$sWhere  .= "   AND {$sSigla}_instit = {$iInstituicaoSessao}";
    
			/**
			 * 300/301 - processa/estorna ferias   
			 */
			if ($oParam->iCodigoDocumento == 300 || $oParam->iCodigoDocumento == 301) {
    	  $sSql = $oDaoProvisao->sql_query_file(null, null, null, null, null, $sCampos, null, $sWhere);
    	} else {
    	  $sSql = $oDaoProvisao->sql_query_file(null, null, null, null, $sCampos, null, $sWhere);
    	}
    	
    	$rsProvisao = $oDaoProvisao->sql_record($sSql);
    
    	if ( $oDaoProvisao->numrows == 0 ) {
    		throw new DBException('Erro ao buscar valor da provisão.');
    	}
    
    	$oDadosProvisao = db_utils::fieldsMemory($rsProvisao, 0);
    
    	/**
    	 * Valor da provisao da folha
    	*/
    	$nValorProvisao = $oDadosProvisao->valor_provisao;
    	
    
    	/**
    	 * Valor do saldo anterior
    	 */
    	$nSaldoAnterior 	= $oDadosConta->saldo_final;
    	$nValorLancamento = $nValorProvisao - $nSaldoAnterior;
    
    	$oRetorno->sConta 					= "{$oDadosConta->c61_reduz} - {$oDadosConta->c60_descr}";
    	$oRetorno->nSaldoAnterior   = $nSaldoAnterior;
    	$oRetorno->nValorProvisao		= $nValorProvisao;
    	$oRetorno->nValorLancamento = $nValorLancamento;
    
    break;
  }
  
} catch (BusinessException $eErro){
	
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($eErro->getMessage());

	db_fim_transacao(true);

} catch (ParameterException $eErro) {
	
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($eErro->getMessage());

} catch (DBException $eErro) {
	
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($eErro->getMessage());

	db_fim_transacao(true);

} catch (Exception $eErro) {
	
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($eErro->getMessage());

	db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);

//@todo Criar model para métodos presentes neste rpc

/**
 * Verifica o documento recebido e retorna um objeto do tipo ILancamentoAuxiliar
 * @param integer $iCodigoDocumento
 * @throws BusinessException
 * @return LancamentoAuxiliarProvisaoFerias || LancamentoAuxiliarProvisaoDecimoTerceiro
 */
function buscaLancamentoAuxiliar($iCodigoDocumento) {
	
	switch ($iCodigoDocumento) {
	
		case 300: // PROVISAO DE FERIAS 
		case 301: // ESTORNO DE PROVISAO DE FERIAS
			
			$oLancamentoAuxiliar = new LancamentoAuxiliarProvisaoFerias();
		break;
		
		case 302: // PROVISAO DE 13º SALARIO
		case 303: // ESTORNO DE PROVISAO DE 13º SALARIO
			
			$oLancamentoAuxiliar = new LancamentoAuxiliarProvisaoDecimoTerceiro();
		break;
		
		default:
				
			$sMsgErro  = 'O documento informado não é um de provisão de férias / 13º salário.\n';
			$sMsgErro .= "Código do documento informado: {$oParam->iCodigoDocumento}";
			throw new BusinessException($sMsgErro);
	}
	
	return $oLancamentoAuxiliar;
}

/**
 * Seta os valores para efetuar os lancamentos no objeto $oLancamentoAuxiliar e executa os 
 * lancamentos contabeis 
 * @param ILancamentoAuxiliar $oLancamentoAuxiliar
 * @param object $oParam
 * @throws BusinessException
 */
function processarLancamentosContabeis($oLancamentoAuxiliar, $oParam) {

	$iEscrituraProvisao = buscaEscrituraProvisao($oParam);
	
	$oLancamentoAuxiliar->setAno($oParam->iAno);
	$oLancamentoAuxiliar->setMes($oParam->iMes);
	$oLancamentoAuxiliar->setValorTotal($oParam->nValor);
  $oLancamentoAuxiliar->setCodigoEscrituraProvisao($iEscrituraProvisao);
	$oLancamentoAuxiliar->setObservacaoHistorico(db_stdClass::normalizeStringJson($oParam->sObservacao));
	 
	/**
	 * Executamos os lançamentos contábeis
	 */
	$oEventoContabil = new EventoContabil($oParam->iCodigoDocumento, db_getsession("DB_anousu"));
	 
	/**
	 * Buscamos o Historico do Evento
	 */
	$aLancamentos = $oEventoContabil->getEventoContabilLancamento();
	
	if (count($aLancamentos) == 0) {
	
		$sMsgErro  = "Nenhum lancamento encontrado para o documento {$oParam->iCodigoDocumento} ";
		$sMsgErro .= "- " .$oEventoContabil->getDescricaoDocumento();
		throw new BusinessException($sMsgErro);
	}
	
	$iCodigoHistorico = $aLancamentos[0]->getHistorico();

	$oLancamentoAuxiliar->setHistorico($iCodigoHistorico);
	$oEventoContabil->executaLancamento($oLancamentoAuxiliar);
}

function buscaEscrituraProvisao($oParam) {
	
	switch ($oParam->iCodigoDocumento) {
	
		case 300: // PROVISAO DE FERIAS 
		case 301: // ESTORNO DE PROVISAO DE FERIAS			
			$iTipoLancamento = 2;
		break;
		
		case 302: // PROVISAO DE 13º SALARIO
		case 303: // ESTORNO DE PROVISAO DE 13º SALARIO
			$iTipoLancamento = 1;
		break;
	}

	$sProcessado = 't';

	if (  $oParam->exec == 'estornar' ) {
		$sProcessado = 'f';
	}

	$oDaoEscrituraProvisao = db_utils::getDao('escrituraprovisao');
	$iInstituicaoSessao    = db_getsession("DB_instit");
	
	$sCampos = "c102_sequencial, c102_processado, c102_tipoprovisao";
	$sWhere  = " c102_ano              = {$oParam->iAno}       ";
	$sWhere .= " and c102_mes          = {$oParam->iMes}       ";
	$sWhere .= " and c102_instit       = {$iInstituicaoSessao} ";
	$sWhere .= " and c102_tipoprovisao = {$iTipoLancamento}    ";
	$sSqlEscrituraProvisao = $oDaoEscrituraProvisao->sql_query_file(null, $sCampos, null, $sWhere);
	$rsEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlEscrituraProvisao);
	
	$GLOBALS["HTTP_POST_VARS"]["c102_processado"] = $sProcessado;
	
	$oDaoEscrituraProvisao->c102_usuario      = db_getsession('DB_id_usuario');
	$oDaoEscrituraProvisao->c102_instit       = $iInstituicaoSessao;
	$oDaoEscrituraProvisao->c102_mes          = $oParam->iMes;
	$oDaoEscrituraProvisao->c102_ano          = $oParam->iAno;
	$oDaoEscrituraProvisao->c102_data         = date('Y-m-d', db_getsession('DB_datausu'));
	$oDaoEscrituraProvisao->c102_tipoprovisao = $iTipoLancamento;
	$oDaoEscrituraProvisao->c102_processado   = "$sProcessado"; 

	if ( $oDaoEscrituraProvisao->numrows > 1 ) {

		$sMensagem = "Erro ao buscar dados da escritura de provisão";
		$sMensagem = "\nMais de um registro encontrado.";
		throw new DBException($sMensagemErro);
	}
	
	/**
	 * Altera/inclui escrituraprovisao 
	 * - Encontrou registro para o periodo
	 */	 
	if ( $oDaoEscrituraProvisao->numrows == 1 ) {

    $oEscrituraProvisao = db_utils::fieldsMemory($rsEscrituraProvisao, 0);

		if ( $oEscrituraProvisao->c102_processado == $sProcessado ) {
			throw new Exception("Não é possivel {$oParam->exec} novamente.");
		}

		$oDaoEscrituraProvisao->c102_sequencial = $oEscrituraProvisao->c102_sequencial;
		$oDaoEscrituraProvisao->alterar($oEscrituraProvisao->c102_sequencial);

	} else {
		$oDaoEscrituraProvisao->incluir(null);
	}

	if ( $oDaoEscrituraProvisao->erro_status == "0" ) {
		throw new DBException('Erro ao incluir/alterar cabeçalho da escritura de provisão.');
	}

	return $oDaoEscrituraProvisao->c102_sequencial;
}