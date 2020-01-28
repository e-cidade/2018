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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_procandam_classe.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_proctransferproc_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_proctransand_classe.php");
require_once("classes/db_arqandam_classe.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");
require_once("classes/db_arqproc_classe.php");

$oJson                  = new services_json;
$oRetorno               = new stdClass;
$clproctransfer         = new cl_proctransfer;
$clproctransferproc     = new cl_proctransferproc;
$clproctransand         = new cl_proctransand;
$clprotprocesso         = new cl_protprocesso;
$clarqandam             = new cl_arqandam; 
$clprocandam            = new cl_procandam;
$clarqproc              = new cl_arqproc;
$clouvidoriaatendimento = new cl_ouvidoriaatendimento;

$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->status  = 1;
$oRetorno->message = "";

switch($oParam->exec) {

  /*
   * DESARAQUIVA PROCESSOS VINCULADOS AO DEPARTAMENTO
   */
  case "Desarquivar":
      
    try {
        
      db_inicio_transacao();
      
      $sSqlArqProc  = $clarqproc->sql_query_consprocarquiv(null,null,"p67_codarquiv,p68_codproc,p67_coddepto",
                                                             "p68_codproc","p68_codproc = {$oParam->processo}");
      $rsSqlArqProc = $clarqproc->sql_record($sSqlArqProc); 
      
			$lSqlErro     = false;
			$dtAtual      = date('Y-m-d');
			if ($clarqproc->numrows > 0) {
			  	
			  $oArqProc = db_utils::fieldsMemory($rsSqlArqProc,0);
			  
			  /**
			   * INCLUI TRANFERÊNCIA
			   */
			  if (!$lSqlErro) {
			  		
				 	$clproctransfer->p62_coddepto    = $oArqProc->p67_coddepto;
				  $clproctransfer->p62_dttran      = $dtAtual;
				  $clproctransfer->p62_coddeptorec = $oArqProc->p67_coddepto;
				  $clproctransfer->p62_id_usorec   = db_getsession("DB_id_usuario");
				  $clproctransfer->p62_id_usuario  = db_getsession("DB_id_usuario");
				  $clproctransfer->p62_hora        = db_hora();
				  $clproctransfer->incluir(null);
				  if ($clproctransfer->erro_status == 0) {
				      
				    $lSqlErro = true;
				    throw new Exception($clproctransfer->erro_msg);
				  }
			  }  	
			    
			  /**
			   * INCLUI TRANFERÊNCIA PARA O PROCESSO
			   */
			  if (!$lSqlErro) {
			    
			   	$iCodTrans = $clproctransfer->p62_codtran;
			    $clproctransferproc->incluir($iCodTrans,$oParam->processo);
			    if ($clproctransferproc->erro_status == 0) {
			      	
			      $lSqlErro = true;
			      throw new Exception($clproctransferproc->erro_msg);
			    }
			  }
			    
			  /**
			   * INCLUI ANDAMENTO
			   */
			  if (!$lSqlErro) {
			    
			    $clprocandam->p61_despacho       =  "Processo Desarquivado";    
			    $clprocandam->p61_codproc        = $oArqProc->p68_codproc;    
			    $clprocandam->p61_dtandam        = $dtAtual;    
			    $clprocandam->p61_hora           = db_hora();    
			    $clprocandam->p61_id_usuario     = db_getsession("DB_id_usuario");
			    $clprocandam->p61_coddepto       = $oArqProc->p67_coddepto;
			    $clprocandam->p61_publico        =  "t";    
			    $clprocandam->incluir(null);
			    if ($clprocandam->erro_status == 0) {
			        
			      $lSqlErro = true;
			      throw new Exception($clprocandam->erro_msg);
			    }
			  }
			  
			  /**
			    * INCLUI O ANDAMENTO E O CÓDIGO DO ARQUIVAMENTO E DIZ SE É ARQUIVAMENTO OU DESARQUIVAMENTO
			    */
			  if (!$lSqlErro) {
			    
				  $iCodAndam                 = $clprocandam->p61_codandam;
				  $clarqandam->p69_codarquiv = $oArqProc->p67_codarquiv;
				  $clarqandam->p69_codandam  = $iCodAndam;
				  $clarqandam->p69_arquivado = 'false';
				  $clarqandam->incluir();
				  if ($clarqandam->erro_status == 0) {
			        
			      $lSqlErro = true;
			      throw new Exception($clarqandam->erro_msg);
				  }
			  }
			  
			  /**
			    * INCLUI A TRANFERÊNCIA E O ANDAMENTO DO PROCESSO
			    */
			  if (!$lSqlErro) {
			    
			    $iCodTrans                    = $clproctransfer->p62_codtran;
			    $iCodAndam                    = $clprocandam->p61_codandam;
			    $clproctransand->p64_codtran  = $iCodTrans;
			    $clproctransand->p64_codandam = $iCodAndam;
			    $clproctransand->incluir(); 
			    if ($clproctransand->erro_status == 0) {
			        
			      $lSqlErro = true;
			      throw new Exception($clproctransand->erro_msg);
			    }  
			  }
			  
			  /**
			    * ATUALIZA O CÓDIGO DO ANDAMENTO
			    */
			  if (!$lSqlErro) {
			    
			   	$iCodAndam                    = $clprocandam->p61_codandam;
			    $clprotprocesso->p58_codproc  = $oParam->processo;
			    $clprotprocesso->p58_codandam = $iCodAndam;
			    $clprotprocesso->p58_despacho = " ";  
			    $clprotprocesso->alterar($oParam->processo);
			    if ($clprotprocesso->erro_status == 0) {
			        
			      $lSqlErro = true;
			      throw new Exception($clprotprocesso->erro_msg);
			    }
			  }
			  
			  /**
			    * DELETA PROCESSO ARQUIVADO
			    */
			  if (!$lSqlErro) {
			    
			    $sWhere = "p68_codproc = {$oParam->processo}";
			    $clarqproc->excluir(null,null,$sWhere);
			    if ($clarqproc->erro_status == 0) {
			        
			      $lSqlErro = true;
			      throw new Exception($clarqproc->erro_msg);
			    }
			  }
			    
			  /**
			    * ALTERA SITUAÇÃO DO ANTENDIMENTO
			    */
			  if (!$lSqlErro) {
			    
			    $sWhere           = " ov09_protprocesso = {$oParam->processo} ";
			    $sSqlAtendimento  = $clouvidoriaatendimento->sql_query_proc(null,"distinct ov01_sequencial",null,$sWhere);
			    $rsAtendimento    = $clouvidoriaatendimento->sql_record($sSqlAtendimento);
			    $iNumRows         = $clouvidoriaatendimento->numrows;
			    
			    if ($iNumRows > 0) {
			    	
		        for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
		        
				      $oAtendimento = db_utils::fieldsMemory($rsAtendimento,$iInd);
		          
				      $clouvidoriaatendimento->ov01_sequencial                   = $oAtendimento->ov01_sequencial;
		          $clouvidoriaatendimento->ov01_situacaoouvidoriaatendimento = 1;
		          $clouvidoriaatendimento->alterar($oAtendimento->ov01_sequencial);
		          if ($clouvidoriaatendimento->erro_status == 0) {
		              
		            $lSqlErro = true;
		            throw new Exception($clouvidoriaatendimento->erro_msg);
		            break;
		          }
		        }
			    }
			  } 
			  
			  db_fim_transacao($lSqlErro);
			}
    } catch (Exception $eExeption){
        
      db_fim_transacao($lSqlErro);
      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode(str_replace("\\n","\n",$eExeption->getMessage()));
    }
      
    break;    
  
}

echo $oJson->encode($oRetorno);   
?>