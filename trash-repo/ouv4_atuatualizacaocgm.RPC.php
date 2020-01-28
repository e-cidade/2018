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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("classes/db_cidadao_classe.php");

$clcidado = new cl_cidadao();

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["dados"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 0;
$oRetorno->message = "";

if ($oParam->acao == "pesquisar") {
		
		$sCampos	=	" ov02_sequencial, ";
		$sCampos .=	" ov02_seq,        ";
		$sCampos .=	" ov02_nome,       ";
		$sCampos .=	" ov16_descricao,  ";
		$sCampos .=	" (select ov03_numcgm ";
    $sCampos .=	"    from cidadaocgm  "; 
    $sCampos .=	"   where cidadaocgm.ov03_cidadao = cidadao.ov02_sequencial limit 1) as ov03_numcgm ";
		
		if ($oParam->ov02_sequencial != null && $oParam->ov02_sequencial != ""){
			$sWhere 	= "ov02_sequencial = ".$oParam->ov02_sequencial." and ov02_ativo is true and ov02_situacaocidadao = 2";	
		}else if ($oParam->dtfim != "" && $oParam->dtini != ""){
			$sWhere 	= "ov02_data between '".$oParam->dtini."' and '".$oParam->dtfim."' and ov02_ativo is true and ov02_situacaocidadao = 2";	
		}else{
			$sWhere 	= " ov02_ativo is true and ov02_situacaocidadao = 2  ";
		}
		//die ($clcidado->sql_query(null,null,$sCampos,'ov02_nome,ov02_sequencial',$sWhere));
		$rsQueryCidadao = $clcidado->sql_record($clcidado->sql_query(null,null,$sCampos,'ov02_nome,ov02_sequencial',$sWhere));
		
		if($clcidado->numrows > 0){
			$oRetorno->cidadoes = db_utils::getColectionByRecord($rsQueryCidadao,false,false,true);
			$oRetorno->status 	= 1;	
		}else{
			$oRetorno->status  = 1;
			$oRetorno->message = utf8_encode("Usuário:\\n\\n Nenhum Resultado para o filtro selecionado!\\n\\nAdministrador:\\n\\n");
		}
			
}else if ($oParam->acao == 'rejeitar'){
	$oRetorno->retorno = $oParam->retorno;
	$oRetorno->cidadoes = array();
	$lerro = false;
	db_inicio_transacao();
	
	if (trim($oParam->ov02_sequencial) != "" && $oParam->ov02_sequencial != null && trim($oParam->ov02_seq) != "" && $oParam->ov02_seq != null){
		
		//$clcidado->ov02_ativo 					= 'false';
		$clcidado->ov02_situacaocidadao = 3;
		
		$sWhere = "ov02_sequencial = ".$oParam->ov02_sequencial." and ov02_seq = ".$oParam->ov02_seq;
		$clcidado->alterar_where(null,null,$sWhere);
		
		if($clcidado->erro_status == '0'){
			$lerro = true;
			$oRetorno->message 	= utf8_encode($clcidado->erro_msg);
			$oRetorno->status 	= 0;
		}else{
			
			$oRetorno->status 	= 1;
			$oRetorno->message  = utf8_encode($clcidado->erro_msg);
			//$sCampos	=	"ov02_sequencial,ov02_seq,ov02_nome,ov16_descricao";
			$sCampos	=	" ov02_sequencial, ";
			$sCampos .=	" ov02_seq,        ";
			$sCampos .=	" ov02_nome,       ";
			$sCampos .=	" ov16_descricao,  ";
			$sCampos .=	" (select ov03_numcgm ";
    	$sCampos .=	"    from cidadaocgm  "; 
    	$sCampos .=	"   where cidadaocgm.ov03_cidadao = cidadao.ov02_sequencial limit 1) as ov03_numcgm ";

			if ($oParam->dtfim != "" && $oParam->dtini != ""){
				
				$sWhere 	= "ov02_data between '".$oParam->dtini."' and '".$oParam->dtfim."' and ov02_ativo is true";	
				//die($clcidado->sql_query(null,null,$sCampos,'ov02_nome',$sWhere));		
				$rsQueryCidadao = $clcidado->sql_record($clcidado->sql_query(null,null,$sCampos,'ov02_nome,ov02_sequencial',$sWhere));
			
				if($clcidado->numrows > 0){
					$oRetorno->cidadoes = db_utils::getColectionByRecord($rsQueryCidadao,false,false,true);
					$oRetorno->status 	= 1;	
				}
			}else{
				$sWhere 	= " ov02_ativo is true and ov02_situacaocidadao 2";	
				//die($clcidado->sql_query(null,null,$sCampos,'ov02_nome',$sWhere));		
				$rsQueryCidadao = $clcidado->sql_record($clcidado->sql_query(null,null,$sCampos,'ov02_nome,ov02_sequencial',$sWhere));
			
				if($clcidado->numrows > 0){
					$oRetorno->cidadoes = db_utils::getColectionByRecord($rsQueryCidadao,false,false,true);
					$oRetorno->status 	= 1;	
				}								
			}
		}
			
		
	}else{
		$oRetorno->status  = 0;
		$oRetorno->message = utf8_encode("Usuário:\\n\\n Falha ao Rejeitar o cadastro do cidadão!\\n\\nAdministrador:\\n\\n");
	}
	
	db_fim_transacao($lerro);
	
}else if ($oParam->acao == 'liberar'){
	//var_dump($oParam);
	$lerro = false;
	db_inicio_transacao();
	
	if (trim($oParam->ov02_sequencial) != "" && $oParam->ov02_sequencial != null && trim($oParam->ov02_seq) != "" && $oParam->ov02_seq != null){
		
		$oRetorno->retorno = $oParam->retorno;

		$clcidado->ov02_situacaocidadao = 4;
		
		$sWhere = "ov02_sequencial = ".$oParam->ov02_sequencial." and ov02_seq = ".$oParam->ov02_seq;
		$clcidado->alterar_where(null,null,$sWhere);
		
		if($clcidado->erro_status == '0'){
			$lerro = true;
			$oRetorno->message 	= utf8_encode($clcidado->erro_msg);
			$oRetorno->status 	= 0;
		}else{
			$oRetorno->cidadoes = array();
			$oRetorno->status 	= 1;
			$oRetorno->message  = utf8_encode($clcidado->erro_msg);
			//$sCampos	=	"ov02_sequencial,ov02_seq,ov02_nome,ov16_descricao";
			$sCampos	=	" ov02_sequencial, ";
			$sCampos .=	" ov02_seq,        ";
			$sCampos .=	" ov02_nome,       ";
			$sCampos .=	" ov16_descricao,  ";
			$sCampos .=	" (select ov03_numcgm ";
    	$sCampos .=	"    from cidadaocgm  "; 
    	$sCampos .=	"   where cidadaocgm.ov03_cidadao = cidadao.ov02_sequencial limit 1) as ov03_numcgm ";
			
    	if ($oParam->retorno == 1){
				$sWhere 	= " ov02_ativo is true and ov02_situacaocidadao = 2";
			//die($clcidado->sql_query(null,null,$sCampos,'ov02_nome',$sWhere));		
				$rsQueryCidadao = $clcidado->sql_record($clcidado->sql_query(null,null,$sCampos,'ov02_nome,ov02_sequencial',$sWhere));
			
				if($clcidado->numrows > 0){
					$oRetorno->cidadoes = db_utils::getColectionByRecord($rsQueryCidadao,false,false,true);
					$oRetorno->status 	= 1;
				
				}else {
					$oRetorno->status 	= 1;	
					$oRetorno->cidadoes = array();
				}
			}
		}
		
	}else{
		
		$oRetorno->status  = 0;
		$oRetorno->message = utf8_encode("Usuário:\\n\\n Falha ao Rejeitar o cadastro do cidadão!\\n\\nAdministrador:\\n\\n");
	
	}
	
	db_fim_transacao($lerro);
	
}

echo $oJson->encode($oRetorno);
exit();


?>