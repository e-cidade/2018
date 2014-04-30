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
require_once("libs/JSON.php");  
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/std/DBPessoal.model.php");

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno    = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMensagem = '';

$oJson  = new services_json();
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_ferias.');

$oRetorno = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMensagem = '';

try {

  switch ($oParametros->sExecucao) {

    /**
     * Cadastra um periodo de gozo
     */
    case 'cadastrarPeriodoGozo' :
      
      require_once('model/pessoal/ferias/PeriodoGozoFerias.model.php');

      db_inicio_transacao();

      $oPeriodoGozo = new PeriodoGozoFerias();
      $oPeriodoGozo->setCodigoFerias($oParametros->iCodigoFerias);
      $oPeriodoGozo->setDiasGozo($oParametros->iDiasGozo);
      $oPeriodoGozo->setDiasAbono($oParametros->iDiasAbono);
      $oPeriodoGozo->setPeriodoInicial( new DBDate($oParametros->sDataPeriodoInicial) ) ;
      $oPeriodoGozo->setPeriodoFinal( new DBDate($oParametros->sDataPeriodoFinal) );
      $oPeriodoGozo->setObservacao(urldecode($oParametros->sObservacao));
      $oPeriodoGozo->setAnoPagamento($oParametros->iAnoPagamento);
      $oPeriodoGozo->setMesPagamento($oParametros->iMesPagamento);
      $oPeriodoGozo->setPagaTerco($oParametros->lPagaTerco == 'true');
      $oPeriodoGozo->setTipoPonto($oParametros->iTipoPonto);
      $oPeriodoGozo->setSituacao(PeriodoGozoFerias::SITUACAO_AGENDADO);

      /**
       * Periodo especifico inicial 
       */
      if ( !empty($oParametros->sPeriodoEspecificoInicial) ) {
        $oPeriodoGozo->setPeriodoEspecificoInicial(new DBDate($oParametros->sPeriodoEspecificoInicial));
      }

      /**
       * Periodo especifico final 
       */
      if ( !empty($oParametros->sPeriodoEspecificoFinal) ) {
        $oPeriodoGozo->setPeriodoEspecificoFinal(new DBDate($oParametros->sPeriodoEspecificoFinal));
      }

      $oPeriodoGozo->salvar();

      $oRetorno->sMensagem = _M(PeriodoGozoFerias::MENSAGENS . 'incluir'); 

      db_fim_transacao(); 

    break;

    /**
     * Processar escalas de ferias 
     * Lanca no ponto todos os periodos aquisitivos cadastrados
     */
    case 'processarEscalaFerias' :

      db_inicio_transacao(); 

      /**
       * Arary com os periodos do servidor
       * @var array
       */
      $aPeriodosServidor = array();

      db_inicio_transacao(); 

      /**
       * Percorre parametro com peridos de gozo, instancia eles e adiciona em um
       * array separando por servidor
       */
      foreach($oParametros->aPeriodosGozo as $iPeriodoGozo) {
        
        $oPeriodoGozo = new PeriodoGozoFerias($iPeriodoGozo);
        $oPeriodoGozo->setSituacao(PeriodoGozoFerias::SITUACAO_GERADO_PONTO);
        $oPeriodoGozo->salvar();

        $iMatricula = $oPeriodoGozo->getPeriodoAquisitivo()->getServidor()->getMatricula();
        $aPeriodosServidor[$iMatricula][] = $oPeriodoGozo;
      }

      /**
       * percorre os peridos de gozo de cada servidor e monta composicao do ponto
       */
      foreach ($aPeriodosServidor as $iMatricula => $aPeriodos ) {

        $oServidor              = ServidorRepository::getInstanciaByCodigo($iMatricula, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
        $oPontoFerias           = $oServidor->getPonto(Ponto::FERIAS);
        $oComposicaoPontoFerias = $oPontoFerias->getComposicao();

        foreach ( $aPeriodos as $oPeriodoGozo ) {
          $oComposicaoPontoFerias->adicionarPeriodoGozo( $oPeriodoGozo );
        }

        /**
         * Gera registros composicao do ponto(rhferiasperiodopontofe) 
         */
        $oComposicaoPontoFerias->gerarRegistrosPonto();

        /**
         * Retorna a soma da composicao do ponto(rhferiasperiodopontofe) 
         */
        $aRegistrosPonto = $oComposicaoPontoFerias->getRegistros();

        /**
         * Adiciona o total da composicao nos registros do ponto
         */
        foreach ($aRegistrosPonto as $oRegistroPontoFerias ) {
          $oPontoFerias->adicionarRegistro($oRegistroPontoFerias);
        }

        $oPontoFerias->gerar();
      }

      db_fim_transacao(); 
      $oRetorno->sMensagem = _M(PeriodoGozoFerias::MENSAGENS . 'processamento_realizado_sucesso');

    break;

    /**
     * Cancela o Período de gozo
     * Remove do ponto todos os periodos cadastrados
     */
    case 'cancelarPeriodoGozo' :

      /**
       * Array com servidores para regerar ponto
       * apos remover periodo de gozo
       */
      $aServidoresRegerarPonto = array();

      db_inicio_transacao();

      /**
       * Percorre os periodos de gozo
       * - Altera situacao do perido para cadastrado 
       * - Remove composicao do ponto(rhferiasperiodopontofe)
       */
      foreach ($oParametros->aPeriodosGozo as $iPeriodoGozo) {

        $oPeriodoGozo  = new PeriodoGozoFerias($iPeriodoGozo);
        $oPeriodoGozo->cancelar();

        $oServidor     = $oPeriodoGozo->getPeriodoAquisitivo()->getServidor();
        $iMatricula    = $oServidor->getMatricula();

        $aServidoresRegerarPonto[$iMatricula] = $oServidor;
      }

      /**
       * Regera o ponto de ferias dos servidores que tiveram periodo cancelado 
       */
      foreach( $aServidoresRegerarPonto as $oServidor ) {

        $oPontoFerias           = $oServidor->getPonto(Ponto::FERIAS);
        $oComposicaoPontoFerias = $oPontoFerias->getComposicao();

        /**
         * Busca os registros da composicao do ponto(rhferiasperiodopontofe)
         */
        $aRegistrosPonto = $oComposicaoPontoFerias->getRegistros();

        /**
         * Adiciona registros ao ponto
         */
        foreach ($aRegistrosPonto as $oRegistroPontoFerias ) {
          $oPontoFerias->adicionarRegistro($oRegistroPontoFerias);
        }

        $oPontoFerias->gerar();

        /**
         * Limpa o calculo de ferias do servidor 
         * para competencia atual
         */
        $oCalculoFerias = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_FERIAS);
        $oCalculoFerias->limpar();
      }

      db_fim_transacao();
      $oRetorno->sMensagem = _M(PeriodoGozoFerias::MENSAGENS . 'cancelamento_realizado_sucesso' );

    break;
    
    /**
     * Busca um periodo de gozo.
     * Traz todos os periodos cadastrados para a situação e a competência informada
     */
    case 'BuscaPeriodosGozo':

      $iSituacaoPeriodo = PeriodoGozoFerias::SITUACAO_AGENDADO;

      if ( $oParametros->sAcao == 'cancelar' ) {
        $iSituacaoPeriodo = PeriodoGozoFerias::SITUACAO_GERADO_PONTO;
      }
      
      $oDaoRhFerias = new cl_rhferias;
      $sSqlRhFerias = $oDaoRhFerias->sql_query_periodos_aquisitivos_competencia($oParametros->iAnoCompetencia, 
                                                                                $oParametros->iMesCompetencia,
                                                                                $iSituacaoPeriodo);
      $rsRhFerias = db_query($sSqlRhFerias);

      /**
       * Erro na query de pesquisa 
       */
      if ( !$rsRhFerias ) {

        $sMensagemErro = MENSAGENS . 'erro_buscar_periodo_gozo';
        $oCamposErro   = (object) array('sErroBanco' => pg_last_error());

        throw new BusinessException(_M($sMensagemErro, $oCamposErro));
      }

      /**
       * Nenhum registro encontrado para esta competencia
       */
      if ( pg_num_rows($rsRhFerias) == 0 ) {
        throw new BusinessException(_M(MENSAGENS . 'busca_periodo_gozo_pela_competencia'));
      }

      $oPeriodosAquisitivos = db_utils::getCollectionByRecord($rsRhFerias, true);
      $aResultado           = array();
      $aRetorno             = array();
      
      foreach ( $oPeriodosAquisitivos as $oDados ) {
        
        $oData                                                           = new DBDate($oDados->rh110_datainicial);
        $aResultado[$oDados->rh109_regist][$oData->getTimeStamp()]       = $oDados;
        $aChavesResultado[$oDados->rh109_regist][$oData->getTimeStamp()] = $oData->getTimeStamp();
      }
      ksort($aResultado);
      
      foreach ($aResultado as $iMatricula => $aDadosRetorno ) {
        
        $lCancelamento = $iSituacaoPeriodo != PeriodoGozoFerias::SITUACAO_AGENDADO;
        $aChave        = $aChavesResultado[$iMatricula];
        $iChave        = $lCancelamento ? max($aChave) : min($aChave);
        $aRetorno[]    = $aDadosRetorno[$iChave];
      }
      
      $oRetorno->oPeriodosGozo = $aRetorno;

    break;

    case 'excluirPeriodoGozo':
      
      require_once('model/pessoal/ferias/PeriodoGozoFerias.model.php');

      db_inicio_transacao();

      $iCodigoPeriodoGozo = $oParametros->iCodigo;

      if (!$iCodigoPeriodoGozo) {
        throw new ParameterException(_M( MENSAGENS . 'periodo_gozo_nao_informado'));
      }

      $oPeriodoGozo = new PeriodoGozoFerias($iCodigoPeriodoGozo);
      $oPeriodoGozo->excluir();
      
      db_fim_transacao();

      $oRetorno->sMensagem = _M(PeriodoGozoFerias::MENSAGENS . 'excluir' );

    break;

    case 'getDiasDireito':
  
	  	require_once('model/pessoal/ferias/PeriodoGozoFerias.model.php');
	  	$iFaltas                = $oParametros->iFaltas;
	  	$oServidor              = new Servidor($oParametros->iMatricula); 
	  	$oRetorno->iDiasDireito = PeriodoAquisitivoFerias::calculaDiasDireito($oServidor, $iFaltas);
  	break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
  }

} catch (Exception $oErro) {
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);