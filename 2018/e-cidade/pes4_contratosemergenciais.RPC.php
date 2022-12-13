<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");  

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->erro     = false;
$oRetorno->sMessage = '';
define('MENSAGEM', 'recursoshumanos.pessoal.pes4_contratosemergenciaisRPC.');

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {
    /**
     * Busca as Renovações de contrato emergenciais que o servidor 
     * informado no parametro iMatricula possui.
     */
    case "buscarRenovacoesContratosEmergenciais":

      if (empty($oParam->iMatricula)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'matricula_nao_informada'));
      }

      $oDaoContratoEmergencialRenovacao   = new cl_rhcontratoemergencialrenovacao();
      $sWhereContratoEmergencialRenovacao = "rh163_matricula = {$oParam->iMatricula}";
      $sSqlContratoEmergencialRenovacao   = $oDaoContratoEmergencialRenovacao->sql_query(null, "*", "rh164_datafim desc", $sWhereContratoEmergencialRenovacao);
      $rsContratoEmergencialRenovacao     = db_query($sSqlContratoEmergencialRenovacao);

      if (!$rsContratoEmergencialRenovacao) {
        $oRetorno->erro     = true;
        throw new DBException(_M(MENSAGEM . 'erro_buscar_contratos'));
      }

      $aRenovacoes = Array();

      for ($iRenovacao = 0; $iRenovacao < pg_num_rows($rsContratoEmergencialRenovacao); $iRenovacao++) {
        $oRenovacao   = new stdClass();
        $oRenovacao->iMatricula           = $oParam->iMatricula;
        $oRenovacao->iSequencialContrato  = db_utils::fieldsMemory($rsContratoEmergencialRenovacao,$iRenovacao)->rh164_contratoemergencial;
        $oRenovacao->iSequencialRenovacao = db_utils::fieldsMemory($rsContratoEmergencialRenovacao,$iRenovacao)->rh164_sequencial;
        $oRenovacao->sDescricao           = urlencode(db_utils::fieldsMemory($rsContratoEmergencialRenovacao,$iRenovacao)->rh164_descricao);
        $oRenovacao->sDataInicio          = db_utils::fieldsMemory($rsContratoEmergencialRenovacao,$iRenovacao)->rh164_datainicio;
        $oRenovacao->sDataFim             = db_utils::fieldsMemory($rsContratoEmergencialRenovacao,$iRenovacao)->rh164_datafim;

        $oRenovacao->sDataInicio          = new DBDate($oRenovacao->sDataInicio);
        $oRenovacao->sDataFim             = new DBDate($oRenovacao->sDataFim);

        $oRenovacao->sDataInicio          = $oRenovacao->sDataInicio->convertTo(DBDate::DATA_PTBR);
        $oRenovacao->sDataFim             = $oRenovacao->sDataFim->convertTo(DBDate::DATA_PTBR);

        $aRenovacoes[] = $oRenovacao;
      }

      $oRetorno->aRenovacoes = $aRenovacoes;
          
    break;

    case "alterarRenovacaoContratoEmergencial": 

      if (empty($oParam->iRenovacao)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'sequencial_renovacao_nao_informada'));
      }

      if (empty($oParam->sDataFimAtual)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_atual_nao_informada'));
      }

      if (empty($oParam->sDataFimNova)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_nova_nao_informada'));
      }

      /**
       * Verifica se a competencia atual é maior que o vencimento da última renovação, 
       * se for não é permitido alterar.
       */
      $aDataAtual = split('-', $oParam->sDataFimAtual);
      $iAnoAtual  = $aDataAtual[0];
      $iMesAtual  = $aDataAtual[1];

      $oCompetenciaAtual     = DBPessoal::getCompetenciaFolha();
      $oCompetenciaRenovacao = new DBCompetencia($iAnoAtual, $iMesAtual);

      if ($oCompetenciaAtual->comparar($oCompetenciaRenovacao, DBCompetencia::COMPARACAO_MAIOR)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'alteracao_nao_permitida'));
      }

      /**
       * Validamos se a nova data não é menor que a 
       * competencia atual, se fgor não é permitido alterar
       */
      $aDataNova = split('-', $oParam->sDataFimNova);
      $iAnoNova  = $aDataNova[0];
      $iMesNova  = $aDataNova[1];

      $oCompetenciaNovaRenovacao = new DBCompetencia($iAnoNova, $iMesNova);

      if ($oCompetenciaAtual->comparar($oCompetenciaNovaRenovacao, DBCompetencia::COMPARACAO_MAIOR)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'alteracao_nao_permitida_nova_data_maior'));
      }      
      
      /**
       * Efetua a renocação do contrato emergencial.
       */
      $oDaoContratoEmergencialRenovacao = new cl_rhcontratoemergencialrenovacao();
      $oDaoContratoEmergencialRenovacao->rh164_sequencial = $oParam->iRenovacao;
      $oDaoContratoEmergencialRenovacao->rh164_datafim    = $oParam->sDataFimNova;
      $oDaoContratoEmergencialRenovacao->alterar($oParam->iRenovacao);

      if ($oDaoContratoEmergencialRenovacao->erro_status == '0') {
        $oRetorno->erro     = true;
        throw new DBException(_M(MENSAGEM . 'erro_alterar_contratos'));
      }

      $oRetorno->sMessage = urlencode(_M(MENSAGEM . 'alteracao_sucesso'));

    break;

    case "incluirRenovacaoContratoEmergencial": 

      if (empty($oParam->sDataUltimaRenovacao)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_ultima_renovacao_nao_informada'));
      }

      if (empty($oParam->iContrato)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'numero_contrato_nao_informada'));
      }

      if (empty($oParam->sDataInicio)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_inicio_nao_informada'));
      }

      if (empty($oParam->sDataFim)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_fim_nao_informada'));
      }

      /**
       * Verifica se a competencia atual é maior que o vencimento da última renovação, 
       * se for não é permitido incluir.
       */
      $aDataUltimaRenovacao = split('-', $oParam->sDataUltimaRenovacao);
      $iAnoUltimaRenovacao  = $aDataUltimaRenovacao[0];
      $iMesUltimaRenovacao  = $aDataUltimaRenovacao[1];
      $oUltimaRenovacao     = new DBCompetencia($iAnoUltimaRenovacao, $iMesUltimaRenovacao);

      $aDataNovaRenovacao = split('-', $oParam->sDataFim);
      $iAnoNovaRenovacao  = $aDataNovaRenovacao[0];
      $iMesNovaRenovacao  = $aDataNovaRenovacao[1];
      $oNovaRenovacao     = new DBCompetencia($iAnoNovaRenovacao, $iMesNovaRenovacao);

      if ($oUltimaRenovacao->comparar($oNovaRenovacao, DBCompetencia::COMPARACAO_MAIOR)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'nova_renovacao_menor_ultima'));
      }

      /**
       * Validamos se a nova data não é menor que a 
       * competencia atual, se fgor não é permitido incluir
       */
      $aDataNova = split('-', $oParam->sDataFim);
      $iAnoNova  = $aDataNova[0];
      $iMesNova  = $aDataNova[1];

      $oCompetenciaAtual         = DBPessoal::getCompetenciaFolha();
      $oCompetenciaNovaRenovacao = new DBCompetencia($iAnoNova, $iMesNova);

      if ($oCompetenciaAtual->comparar($oCompetenciaNovaRenovacao, DBCompetencia::COMPARACAO_MAIOR)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'alteracao_nao_permitida_nova_data_maior'));
      }      
      
      $oDaoContratoEmergencialRenovacao = new cl_rhcontratoemergencialrenovacao();
      $oDaoContratoEmergencialRenovacao->rh164_sequencial          = null;
      $oDaoContratoEmergencialRenovacao->rh164_contratoemergencial = $oParam->iContrato;
      $oDaoContratoEmergencialRenovacao->rh164_descricao           = 'Renovação';
      $oDaoContratoEmergencialRenovacao->rh164_datainicio          = $oParam->sDataInicio;
      $oDaoContratoEmergencialRenovacao->rh164_datafim             = $oParam->sDataFim;
      $oDaoContratoEmergencialRenovacao->incluir(null);

      if ($oDaoContratoEmergencialRenovacao->erro_status == '0') {
        $oRetorno->erro     = true;
        throw new DBException(_M(MENSAGEM . 'erro_incluir_renovacao'));
      }

      $oRetorno->sMessage = urlencode(_M(MENSAGEM . 'inclusao_sucesso'));

    break;
    case "excluirRenovacaoContratoEmergencial": 

      if (empty($oParam->iRenovacao)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'sequencial_renovacao_nao_informada'));
      }

      if (empty($oParam->sDataFimAtual)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'data_atual_nao_informada'));
      }

      /**
       * Verifica se a competencia atual é maior que o vencimento da última renovação, 
       * se for não é permitido excluir.
       */
      $aDataAtual = split('-', $oParam->sDataFimAtual);
      $iAnoAtual  = $aDataAtual[0];
      $iMesAtual  = $aDataAtual[1];

      $oCompetenciaAtual     = DBPessoal::getCompetenciaFolha();
      $oCompetenciaRenovacao = new DBCompetencia($iAnoAtual, $iMesAtual);

      if ($oCompetenciaAtual->comparar($oCompetenciaRenovacao, DBCompetencia::COMPARACAO_MAIOR)) {
        $oRetorno->erro     = true;
        throw new  BusinessException(_M(MENSAGEM . 'alteracao_nao_permitida'));
      }

      $oDaoContratoEmergencialRenovacao = new cl_rhcontratoemergencialrenovacao();
      $oDaoContratoEmergencialRenovacao->excluir($oParam->iRenovacao);

      if ($oDaoContratoEmergencialRenovacao->erro_status == '0') {
        $oRetorno->erro     = true;
        throw new DBException(_M(MENSAGEM . 'erro_excluir_renovacao'));
      }

      $oRetorno->sMessage = urlencode(_M(MENSAGEM . 'exclusao_sucesso'));

    break;
  }
  
  db_fim_transacao(false);

} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

