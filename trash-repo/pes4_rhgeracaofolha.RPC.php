<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_folha_classe.php");
require_once("classes/db_selecao_classe.php");
require_once("classes/db_gerfsal_classe.php");
require_once("classes/db_gerfadi_classe.php");
require_once("classes/db_gerffer_classe.php");
require_once("classes/db_gerfres_classe.php");
require_once("classes/db_gerfs13_classe.php");
require_once("classes/db_gerfcom_classe.php");
require_once("classes/db_gerffx_classe.php");
require_once("classes/db_rhgeracaofolha_classe.php");
require_once("classes/db_rhgeracaofolhatipo_classe.php");
require_once("classes/db_rhgeracaofolhareg_classe.php");
require_once("classes/db_rhsuspensaopag_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("libs/db_sql.php");

$clrhgeracaofolha     = new cl_rhgeracaofolha();
$clrhgeracaofolhatipo = new cl_rhgeracaofolhatipo();
$clrhgeracaofolhareg  = new cl_rhgeracaofolhareg();
$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = 1;
$oRetorno->message    = '';

switch ($oParam->exec) {
  
  case "getServidores":
  	
    $sSqlServidores         = $clrhgeracaofolha->sqlGeracaoFolha($oParam);
  	$rsServidores           = $clrhgeracaofolha->sql_record($sSqlServidores);
    $aServidores            = db_utils::getColectionByRecord($rsServidores, false, false, true);    
    $oRetorno->aServidores  = $aServidores;
  break;
  
  case "geraFolha":
  	
    $oDadosGeracaoFolha     = $oParam->oDados;
    $erro = false;
    db_inicio_transacao();
    
    $clrhgeracaofolha->rh102_descricao  = $oDadosGeracaoFolha->rh102_descricao; 
    $clrhgeracaofolha->rh102_usuario    = db_getsession('DB_id_usuario'); 
    $clrhgeracaofolha->rh102_dtproc     = date('Y-m-d',db_getsession('DB_datausu'));; 
    $clrhgeracaofolha->rh102_ativo      = 't'; 
    $clrhgeracaofolha->rh102_mesusu     = $oDadosGeracaoFolha->mesfolha; 
    $clrhgeracaofolha->rh102_anousu     = $oDadosGeracaoFolha->anofolha; 
    $clrhgeracaofolha->rh102_instit     = db_getsession('DB_instit'); 
    $clrhgeracaofolha->incluir("");
    
    if($clrhgeracaofolha->erro_status == "1"){
    	
    	$sSqlServidores    = $clrhgeracaofolha->sqlGeracaoFolha($oParam, $oParam->aDadosServidores);
    	$rsServidores      = $clrhgeracaofolha->sql_record($sSqlServidores);
    	$aServidores       = db_utils::getColectionByRecord($rsServidores, false, false, true);
    	
      
      foreach ($aServidores as $oDados){
      	
	      if($oDados->liquido > $oDados->valor_recebido && $oDados->liquido > 0){
	        /**
	         * Incluindo dado na tabela rhgeracaofolhareg
	         */
	      	 $clrhgeracaofolhareg->rh104_sequencial     = null;
	         $clrhgeracaofolhareg->rh104_seqpes         = $oDados->rh02_seqpes;
	         $clrhgeracaofolhareg->rh104_instit         = db_getsession('DB_instit');
	         $clrhgeracaofolhareg->rh104_rhgeracaofolha = $clrhgeracaofolha->rh102_sequencial;
	         $clrhgeracaofolhareg->rh104_vlrsalario     = $oDados->f010;
	         $clrhgeracaofolhareg->rh104_vlrliquido     = $oDados->liquido - $oDados->valor_recebido;
	         $clrhgeracaofolhareg->rh104_vlrprovento    = $oDados->proven;
	         $clrhgeracaofolhareg->rh104_vlrdesconto    = $oDados->descon;
	         $clrhgeracaofolhareg->incluir("");
	         
	         if($clrhgeracaofolhareg->erro_status == "1"){
	         	
  		       /**
  		        * Incluindo dados na tabela rhgeracaofolhareg
  		        */
  	         $clrhgeracaofolhatipo->rh103_sequencial        = null;
	           $clrhgeracaofolhatipo->rh103_rhgeracaofolhareg = $clrhgeracaofolhareg->rh104_sequencial;
	           $clrhgeracaofolhatipo->rh103_tipofolha         = $oDados->tipo_folha;
	           
	           if(isset($oDadosGeracaoFolha->complementares)){
	             $iCodigoComplementar = $oDadosGeracaoFolha->complementares;
	           } else {
	             $iCodigoComplementar = "0";
	           }
	           $clrhgeracaofolhatipo->rh103_complementar      =  $iCodigoComplementar;
		         $clrhgeracaofolhatipo->incluir("");
		         
		         if($clrhgeracaofolhatipo->erro_status == "1"){
		           $oRetorno->message = "Incluido com sucesso";
		         } else {
		          
		           $oRetorno->status  = 2;
		           $oRetorno->message = urlencode($clrhgeracaofolhatipo->erro_msg);   
		           $erro              = true;    
	           }
	         } else {
	        	
	           $oRetorno->status  = 2;
	           $oRetorno->message = urlencode($clrhgeracaofolhareg->erro_msg);   
	           $erro              = true;  	
           }
        }
      }
    } else {
    	$erro              = true;
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($clrhgeracaofolha->erro_msg);
    }
    db_fim_transacao($erro);
    	
  break;
  
  
  /**
   * Verifica existencia de lanчamento escrituraчуo de fщrias ou dщcimo terceiro
   */
  case "verificaExistenciaLancamentoFeriasDecimoTerceiro":
    
    $iAno                   = db_getsession("DB_anousu");
    $iMes                   = date("m", db_getsession("DB_datausu"));
    $iInstituicao           = db_getsession("DB_instit");
    $oDaoEscrituraProvisao  = db_utils::getDao('escrituraprovisao');

    $sWhere  = "     c102_instit = {$iInstituicao}";
    $sWhere .= " and c102_processado is true";
    $sWhere .= " and c102_ano = {$iAno} and c102_mes >= {$iMes}";
    
    
    $sSqlBuscaEscrituraProvisao = $oDaoEscrituraProvisao->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlBuscaEscrituraProvisao);
    
    if ($oDaoEscrituraProvisao->numrows > 0) {
      
      $oLancamento = db_utils::fieldsMemory($rsBuscaEscrituraProvisao, 0);
      $sTipo       = $oLancamento->c102_tipoprovisao == "2" ? "Fщrias" : "Dщcimo Terceiro";
      
      $oRetorno->status  = 2;
      $sMensagem  = "Existem lanчamentos para {$sTipo}\n";
      $sMensagem .= "Para executar novo processamento da rotina, os lanчamentos da escrituraчуo devem ser estornados";
      $oRetorno->message = urlencode($sMensagem);
    }
  break;
  	  
}
echo $oJson->encode($oRetorno);
?>