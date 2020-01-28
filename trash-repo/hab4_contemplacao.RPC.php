<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitcandidato_classe.php");
require_once("classes/db_workflowativexec_classe.php");
require_once("classes/db_workflowativexecucaoatributovalor_classe.php");
require_once("classes/db_proctransferworkflowativexec_classe.php");

require_once("model/habitacao/CandidatoHabitacao.model.php");
require_once("model/habitacao/InscricaoHabitacao.model.php");
require_once("model/habitacao/InteresseHabitacao.model.php");
require_once("model/habitacao/InteresseProgramaHabitacao.model.php");
require_once("model/processoProtocolo.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");


$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = '';


try {
  
  
  if ( $oParam->sMethod == 'consultarAtividades' ) {
    
    $sWhere       = "";
    $sDataInicial = implode("-", array_reverse(explode("/",$oParam->sDataInicial)));
    $sDataFinal   = implode("-", array_reverse(explode("/",$oParam->sDataFinal)));
     
    if ($oParam->sDataInicial != '' || $oParam->sDataFinal != '') {
        
      if (trim($oParam->sDataInicial) != '') {
        $sWhere .= " and p62_dttran >= '{$sDataInicial}'::date ";
      }
        
      if (trim($oParam->sDataInicial) != '') {
        $sWhere .= " and p62_dttran <= '{$sDataFinal}'::date ";
      }        
    }      
      
    $sSqlAtividades = " select * 
                          from habitinscricao
                               inner join habitcandidatointeresseprograma on habitcandidatointeresseprograma.ht13_sequencial = habitinscricao.ht15_habitcandidatointeresseprograma
                               inner join habitcandidatointeresse         on habitcandidatointeresse.ht20_sequencial         = habitcandidatointeresseprograma.ht13_habitcandidatointeresse
                               inner join habitcandidato                  on habitcandidato.ht10_sequencial                  = habitcandidatointeresse.ht20_habitcandidato
                               inner join habitprograma                   on habitprograma.ht01_sequencial                   = habitcandidatointeresseprograma.ht13_habitprograma
                               inner join workflow                        on workflow.db112_sequencial                       = habitprograma.ht01_workflow                   
                               inner join cgm                             on cgm.z01_numcgm                                  = habitcandidato.ht10_numcgm                  
                               inner join protprocesso                    on protprocesso.p58_codproc                        = habitcandidatointeresseprograma.ht13_codproc 
                               inner join tipoproc                        on tipoproc.p51_codigo                             = protprocesso.p58_codigo   
                               inner join procandam                       on procandam.p61_codandam                          = protprocesso.p58_codandam
                               inner join proctransand                    on proctransand.p64_codandam                       = procandam.p61_codandam
                               inner join proctransfer                    on proctransfer.p62_codtran                        = proctransand.p64_codtran
                               left  join arqproc                         on arqproc.p68_codproc                             = protprocesso.p58_codproc    
                         where procandam.p61_coddepto = ".db_getsession('DB_coddepto')."
                           and tipoproc.p51_tipoprocgrupo = 3
                           and habitcandidatointeresse.ht20_ativo is true
                           and arqproc.p68_codarquiv is null
                           {$sWhere}
                           and not exists ( select 1 
                                              from proctransferproc
                                                   inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran
                                                   left  join proctransand on proctransand.p64_codtran = proctransfer.p62_codtran
                                             where proctransand.p64_codandam is null
                                               and proctransferproc.p63_codproc = protprocesso.p58_codproc ) ";
      
    $rsAtividades = db_query($sSqlAtividades);
    $aAtividades  = db_utils::getColectionByRecord($rsAtividades,false,false,true);
    $aRetornoAtividades = array();
    
    foreach ($aAtividades as $oAtividade) {

      $oProcesso = new processoProtocolo($oAtividade->p58_codproc);
        
      $sSqlWorkFlowAtividade = " select workflowativ.db114_sequencial,
                                        workflowativ.db114_descricao,
                                        workflowativdb_cadattdinamico.db117_db_cadattdinamico
                                   from workflowativ 
                                        left join workflowativdb_cadattdinamico on workflowativdb_cadattdinamico.db117_workflowativ = workflowativ.db114_sequencial
                                  where workflowativ.db114_workflow = {$oAtividade->db112_sequencial} 
                                    and workflowativ.db114_ordem    = {$oProcesso->getPosicaoAtualAndamentoPadrao()}";
                                                    
      $rsWorkFlowAtividade   = db_query($sSqlWorkFlowAtividade);
        
      if (pg_num_rows($rsWorkFlowAtividade) > 0) {
        $oWorkFlowAtividade  = db_utils::fieldsMemory($rsWorkFlowAtividade,0);
      } else {
        throw new Exception('Atividade não cadastrada!');
      }

      $oDadosAtividades  = new stdClass(); 
      $oDadosAtividades->iInscricao         = $oAtividade->ht15_sequencial;
      $oDadosAtividades->iCodProcesso       = $oAtividade->p58_codproc;
      $oDadosAtividades->iCgm               = $oAtividade->ht10_numcgm;
      $oDadosAtividades->sNome              = $oAtividade->z01_nome;
      $oDadosAtividades->sDescrPrograma     = $oAtividade->ht01_descricao;
      $oDadosAtividades->sData              = db_formatar($oAtividade->p62_dttran,'d');
      $oDadosAtividades->iCodInteresse      = $oAtividade->ht20_sequencial;
      $oDadosAtividades->sCodWorkFlowAtiv   = $oWorkFlowAtividade->db114_sequencial;
      $oDadosAtividades->sDescrWorkFlowAtiv = urlencode($oWorkFlowAtividade->db114_descricao);
      $oDadosAtividades->iGrupoAtributos    = $oWorkFlowAtividade->db117_db_cadattdinamico;
        
      $aRetornoAtividades[] = $oDadosAtividades;
    }
    
    $oRetorno->aAtividades = $aRetornoAtividades;
    
  } else if ( $oParam->sMethod == 'salvarAtividade' ) {

    
    $iWorkFlowAtiv         = $oParam->iWorkFlowAtiv;
    $sObs                  = $oParam->sObs;
    $lConcluido            = $oParam->lConcluido; 
    $iCodProcesso          = $oParam->iCodProcesso;
    $iCgm                  = $oParam->iCgm;
    $iCodInteresse         = $oParam->iCodInteresse;
    $iGrupoValorAtributo   = $oParam->iGrupoValorAtributo;    
    

    db_inicio_transacao();
    
		@$GLOBALS["HTTP_POST_VARS"]["db113_concluido"] = $lConcluido;
    
    $clWorkFlowAtivExec = new cl_workflowativexec();
    $clWorkFlowAtivExec->db113_workflowativ = $iWorkFlowAtiv;
    $clWorkFlowAtivExec->db113_id_usuario   = db_getsession('DB_id_usuario');
    $clWorkFlowAtivExec->db113_dtexecucao   = date('Y-m-d',db_getsession('DB_datausu'));
    $clWorkFlowAtivExec->db113_obs          = $sObs;
    $clWorkFlowAtivExec->db113_concluido    = $lConcluido;
    $clWorkFlowAtivExec->incluir(null);
    
    
    if ($clWorkFlowAtivExec->erro_status == 0) {
      throw new Exception($clWorkFlowAtivExec->erro_msg);
    }

    if (trim($iGrupoValorAtributo) != '') {
      
      $clWorkFlowAtivExecAtributoValor = new cl_workflowativexecucaoatributovalor();
      $clWorkFlowAtivExecAtributoValor->db111_workflowativexec         = $clWorkFlowAtivExec->db113_sequencial;
      $clWorkFlowAtivExecAtributoValor->db111_cadattdinamicovalorgrupo = $iGrupoValorAtributo;
      $clWorkFlowAtivExecAtributoValor->incluir(null); 
      
      if ($clWorkFlowAtivExecAtributoValor->erro_status == 0) {
        throw new Exception($clWorkFlowAtivExecAtributoValor->erro_msg);
      }
    }
    
    if ($lConcluido == 't') {
      
      $oProcesso = new processoProtocolo($iCodProcesso);
      
      if ($oProcesso->getProximoDeptoAndamentoPadrao()) {

      	$iCodTransferencia = $oProcesso->transferirPorAndamentoPadrao();
	      $iProximoDepto     = $oProcesso->getProximoDeptoAndamentoPadrao();
	      $iCodRecebimento   = $oProcesso->receber($iCodTransferencia,$iProximoDepto,'0',$sObs);
      	
	      $clProcTransferWorkFlowAtivExec = new cl_proctransferworkflowativexec();
	      $clProcTransferWorkFlowAtivExec->p46_proctransfer     = $iCodTransferencia;
	      $clProcTransferWorkFlowAtivExec->p46_workflowativexec = $clWorkFlowAtivExec->db113_sequencial;
	      $clProcTransferWorkFlowAtivExec->incluir(null);
	      
	      if ($clProcTransferWorkFlowAtivExec->erro_status == 0) {
	        throw new Exception($clProcTransferWorkFlowAtivExec->erro_msg);
	      }      
      } else {
      	
        $oCandidato = new CandidatoHabitacao($iCgm);
      
	      foreach ($oCandidato->getInteresse() as $oInteresse) {
	        if ($oInteresse->getCodigo() == $iCodInteresse && $oInteresse->isAtivo()) {
	          $oInteresse->cancelar("Arquivamento conforme : {$sObs}");
	        }
	      }      	
      }
      
    } else {
      
      $oCandidato = new CandidatoHabitacao($iCgm);
      
      foreach ($oCandidato->getInteresse() as $oInteresse) {
        if ($oInteresse->getCodigo() == $iCodInteresse && $oInteresse->isAtivo()) {
          $iGrupoPrograma = $oInteresse->getGrupoPrograma();
          $oInteresse->cancelar();
        }
      }
      
      $oCandidato->addInteresseGrupo($iGrupoPrograma);
    }
    
    db_fim_transacao(false);
    
    $oRetorno->sMsg = urlencode("Avidade executada com sucesso!");
    
  } 
  
} catch (Exception $eException) {

  if (db_utils::inTransaction()) {
    db_fim_transacao(true);
  }
  
  $oRetorno->iStatus = 2;
  $oRetorno->sMsg    = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
}

echo $oJson->encode($oRetorno);   

?>