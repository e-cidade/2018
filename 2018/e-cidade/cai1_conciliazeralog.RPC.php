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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

include("classes/db_conciliapendcorrente_classe.php");
include("classes/db_conciliapendextrato_classe.php");
include("classes/db_conciliacor_classe.php");
include("classes/db_conciliaextrato_classe.php");
include("classes/db_conciliaitem_classe.php");
include("classes/db_concilia_classe.php");
include("classes/db_conciliazeralog_classe.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro = false; 

switch($oParam->exec) {

  case 'exclusao' :
  	
  	try {
  		
  	  /*
  	   * Script zera os dados da conciliaчуo apartir da conta bancaria e de uma data inicial  	  
  	   */
	    db_inicio_transacao();
	    
	    /*
	     * Set de Objetos
	     */
	    $sDataProcessamento    = implode('-',array_reverse(explode('/',$oParam->data)));
      $oConciliaPendCorrente = new cl_conciliapendcorrente();
      $oConciliaPendExtrato  = new cl_conciliapendextrato();
      $oConciliaCor          = new cl_conciliacor();
      $oConciliaExtrato      = new cl_conciliaextrato();
      $oConciliaItem         = new cl_conciliaitem();
      $oConciliaExcluir      = new cl_concilia();
      
      /*
       * Validamos a data de encerramento da contabilidade, nуo podendo ser alterada nenhuma conciliaчуo anterior a esta data
      */
      $sSqlValidaDataConciliacao  = "select max(c99_data) ";
      $sSqlValidaDataConciliacao .= "  from condataconf ";
      $sSqlValidaDataConciliacao .= " where c99_instit = ".db_getsession("DB_instit");
      $sSqlValidaDataConciliacao .= "having max(c99_data) >= '{$sDataProcessamento}'";
      $rsValidaDataConciliacao   = db_query($sSqlValidaDataConciliacao);
      if ($rsValidaDataConciliacao && pg_num_rows($rsValidaDataConciliacao) > 0) {
      	throw new Exception("Operaчуo nуo permitida!\n\nData da conciliaчуo menor que a data do encerramento da contabilidade para a instituicao!");
      }    
      
	    $rsConcilia = $oConciliaExcluir->sql_record($oConciliaExcluir->sql_query_file("",
		  					                                                                    "k68_sequencial",
		  					                                                                    "",
		  					                                                                    "k68_contabancaria 
		  					                                                                     in ({$oParam->db83_sequencial}) 
		  																																							 and k68_data >= '{$sDataProcessamento}'
		  																																							"));
		  /*
		   * Verifica se houve resultados
		   * Se nуo tiver mata a operaчуo e define a variavel mensagem como erro
		   */
		  if ($oConciliaExcluir->numrows == "0"){
		  	throw new Exception("Nenhum registro selecionado!");
		  }
      
		  	/*
		  	 * Transforma o recordset em um array de objetos
		  	 * Percorre o array excluindo registros das tabelas:
		  	 *   conciliapendcorrente, conciliapendextrato, conciliacor, conciliaextrato, conciliaitem e concilia
		  	 */
		    $aConcilia = db_utils::getColectionByRecord($rsConcilia);
		    
		    foreach ($aConcilia as $oConcilia){
	    
	        $oConciliaPendCorrente->excluir("","k89_concilia = {$oConcilia->k68_sequencial}"); 
	        
	        if ($oConciliaPendCorrente->erro_status == "0"){
	        	throw new Exception($oConciliaPendCorrente->erro_msg."\n Linha 81");
	        }
	        
	        $oConciliaPendExtrato->excluir("","k88_concilia = {$oConcilia->k68_sequencial}");      
	        if ($oConciliaPendExtrato->erro_status == "0"){
	          throw new Exception($oConciliaPendExtrato->erro_msg."\n Linha 91");
	        } 
	        
	        /*
	         * Seleciona registros da conciliaitens que tem ligaчуo com a concilia
	         * Transforma o recordset em um array de objetos 
	         * Percorre o array excluindo registros das tabelas:conciliacor, conciliaextrato e conciliaitem 
	         */
	        $rsConciliaItem = $oConciliaItem->sql_record($oConciliaItem->sql_query_file("","k83_sequencial","","
	                                                                                    k83_concilia = 
	                                                                                    {$oConcilia->k68_sequencial}
	                                                                                    "));
	        $aConciliaItem  = db_utils::getColectionByRecord($rsConciliaItem);
	        
	        foreach ($aConciliaItem as $oConciliaItemLaco) {
	        
		        $oConciliaCor->excluir("","k84_conciliaitem = {$oConciliaItemLaco->k83_sequencial} ");       
		        if ($oConciliaCor->erro_status == "0"){
		          throw new Exception($oConciliaCor->erro_msg."\n Linha 111");
		        }
	    
		        $oConciliaExtrato->excluir("","k87_conciliaitem = {$oConciliaItemLaco->k83_sequencial} ");      
		        if ($oConciliaExtrato->erro_status == "0"){
		          throw new Exception($oConciliaExtrato->erro_msg."\n Linha 121");
		        } 
	        
		        $oConciliaItem->excluir($oConciliaItemLaco->k83_sequencial);       
		        if ($oConciliaItem->erro_status == "0"){
		        	throw new Exception($oConciliaItem->erro_msg."\n Linha 131");
		        }
		        
		      }
	        
	        /*
	         * Exclui o registro da tabela consilia que foi selecionado
	         */
	        $oConciliaExcluir->excluir($oConcilia->k68_sequencial);       
	        if ($oConciliaExcluir->erro_status == "0"){
	        	throw new Exception($oConciliaExcluir->erro_msg."\n Linha 146");
	        }
	    
		    }
		    
		    $oZeraLog = new cl_conciliazeralog();	
		    $oZeraLog->k123_data       = date("Y-m-d",db_getsession("DB_datausu")); 
		    $oZeraLog->k123_hora       = db_hora(); 
		    $oZeraLog->k123_id_usuario = db_getsession("DB_id_usuario"); 
		    $oZeraLog->k123_obs        = $oParam->obs; 
		    $oZeraLog->k132_filtros    = $oParam->db83_sequencial."#".$sDataProcessamento; 
		    $oZeraLog->incluir(null);	
	    	if ($oZeraLog->erro_status == "0"){
          throw new Exception($oZeraLog->erro_msg."\n Linha 182");
	    	}

	    db_fim_transacao();
	    
  	} catch ( Exception $oErro) {
  		
  		db_fim_transacao(true);
  		
  		$oRetorno->message = urlencode($oErro->getMessage());
  		$oRetorno->status  = 2;
  		
  	}  
	  
  break;

    
}
  
echo $oJson->encode($oRetorno);   
?>