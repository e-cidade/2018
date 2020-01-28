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
include("classes/db_protprocesso_classe.php");
include("classes/db_processoouvidoriaprorrogacao_classe.php");

$clprotprocesso = new cl_protprocesso();
$clprocessoouvidoriaprorrogacao = new cl_processoouvidoriaprorrogacao();
$clprocessoouvidoriaprorrogacao_alteracao = new cl_processoouvidoriaprorrogacao();

$oJson             = new services_json();
//$oParam            = $oJson->decode(str_replace("\\","",$_POST["dados"]));
//var_dump($_POST);
$oPost = str_replace("\\\\n","/n",$_POST["dados"]);
$oParam            = $oJson->decode(str_replace("\\","",$oPost));
$oRetorno          = new stdClass;
$oRetorno->status  = 0;
$oRetorno->message = "";
//echo utf8_decode(str_replace("/n","\n", $oParam->ov15_motivo));
//var_dump($oParam);
//die();
if ($oParam->acao == "pesquisar") {
	
	$sCampos  = "p58_codproc,p58_id_usuario,p58_coddepto,p58_dtproc,p58_hora,p58_codigo,p58_requer,p58_numcgm,a.descrdepto as nomedepto";
	$sCampos .= ",nome as nomeusuario, p51_descr as nomeprocesso, z01_nome as nometitular";
	//die($clprotprocesso->sql_query_sql_query_andam_ouvidoria($oParam->chave,$sCampos));	
	$rsProcesso = $clprotprocesso->sql_record($clprotprocesso->sql_query_andam_ouvidoria($oParam->chave,$sCampos));
	
	if($clprotprocesso->numrows > 0){
		
		$oRetorno->status = 1;
		
		$oRetorno->processo = db_utils::getColectionByRecord($rsProcesso,false,false,true);
		
		$sWhere 	= "ov15_protprocesso = ".$oRetorno->processo[0]->p58_codproc." and ov15_ativo is true";
		$sCampos  = "ov15_coddepto,depart.descrdepto,ov15_dtini,ov15_dtfim,ov15_sequencial,";
		$sCampos .= "abs(cast(ov15_dtfim as date)-cast(ov15_dtini as date)) as difdatas";
		
		$sQueryPocessoProrrogacao = "select *,
       															(diferenca_periodo-feriados) as difdatas,
       															'n' as alterado 
  																from ( select ov15_coddepto,
  																							db_depart.descrdepto,
  																							ov15_dtini,
  																							ov15_dtfim,
  																							ov15_sequencial, 
												                				(ov15_dtfim-ov15_dtini) as diferenca_periodo, 
												                				(select count(k13_data) 
                   																	from calend 
                  																where k13_data between ov15_dtini and ov15_dtfim
                																) as feriados,
                																p61_coddepto
           																		from processoouvidoriaprorrogacao
           																		inner join db_depart  on  db_depart.coddepto = processoouvidoriaprorrogacao.ov15_coddepto
																		          inner join protprocesso  on  protprocesso.p58_codproc = processoouvidoriaprorrogacao.ov15_protprocesso
																		          inner join db_config  on  db_config.codigo = db_depart.instit
																		          inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm
																		          inner join db_config  as a on   a.codigo = protprocesso.p58_instit
																		          inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario
																		          inner join db_depart as depart on depart.coddepto = protprocesso.p58_coddepto
																		          inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo
																		          left  join procandam on protprocesso.p58_codandam = procandam.p61_codandam
																		          where $sWhere
       																	) as x order by ov15_dtfim;";
		
		$sQueryPocesso = $clprocessoouvidoriaprorrogacao->sql_query_ouvidoria(null,$sCampos,'ov15_dtini',$sWhere);
		//die($sQueryPocesso);
//		$rsProcOuvidoriaProrrogacao = $clprocessoouvidoriaprorrogacao->sql_record($sQueryPocesso);
		$rsProcOuvidoriaProrrogacao = $clprocessoouvidoriaprorrogacao->sql_record($sQueryPocessoProrrogacao);
		
		if ($clprocessoouvidoriaprorrogacao->numrows > 0){
			$oRetorno->andamentos = db_utils::getColectionByRecord($rsProcOuvidoriaProrrogacao,false,false,true);
		}else{
			$oRetorno->andamentos = array();
		}
		
	}else {
		$oRetorno->status 	=	 0;
		$oRetorno->message = urlencode("\\n\\nUsuário:\\n\\nTramite inicial não definido para o processo: ".$oParam->chave."\n\nAdministrador:\\n\\n");
	}
	
}else if ($oParam->acao == 'validaDatas'){
	
	$oRetorno->linhas = array();
	
	//require_once("classes/db_calend_classe.php");
	
	$iNumRows = count($oParam->linhas);
	$linha		= $oParam->linha;
	//aqui apenas copia os elemetos que não serão alterados
	if($linha>0){
		for($iInd = 0; $iInd < $linha; $iInd++){
			$oLinha = new stdClass();
			$oLinha->ov15_dtini				=	formataData($oParam->linhas[$iInd]->dtini,'b');
			$oLinha->ov15_dtfim				=	formataData($oParam->linhas[$iInd]->dtfim,'b');
			$oLinha->ov15_sequencial 	= $oParam->linhas[$iInd]->ov15_sequencial;
			$oLinha->ov15_coddepto 		= $oParam->linhas[$iInd]->ov15_coddepto;
			$oLinha->descrdepto 			= $oParam->linhas[$iInd]->descrdepto;
			$oLinha->difdatas		 			= $oParam->linhas[$iInd]->difdatas;
			$oLinha->alterado		 			= $oParam->linhas[$iInd]->alterado;
			$oLinha->p61_coddepto			= $oParam->linhas[$iInd]->p61_coddepto;
			$oRetorno->linhas[$iInd] 	= $oLinha;
		}
		 
	}
	
	for ($iInd = $linha; $iInd < $iNumRows; $iInd++){
		
		$oLinha = new stdClass();
		
		//1ºpasso verificar se a data inicial não é um feriado
		$dtini = formataData($oParam->linhas[$iInd]->dtini,'b');
		$dtfim = formataData($oParam->linhas[$iInd]->dtfim,'b');
		
		$oLinha->ov15_dtini = verificaFeriado($dtini,false);
		
		$dtini = explode('-',$oLinha->ov15_dtini);
		$dtfim = explode('-',$dtfim);
		$mktime_dtini = mktime(0,0,0,$dtini[1],$dtini[2],$dtini[0]);
		$mktime_dtfim = mktime(0,0,0,$dtfim[1],$dtfim[2],$dtfim[0]);
		if(($mktime_dtfim - $mktime_dtini) <= 0){
			$dias = 0;
			$oParam->linhas[$iInd]->dtfim = $oParam->linhas[$iInd]->dtini;
		}else{
			$dias = ($mktime_dtfim - $mktime_dtini)/86400;
			$dias = ceil($dias);	
		}
				
		$oLinha->ov15_dtfim = formataData($oParam->linhas[$iInd]->dtfim,'b');
		$oLinha->ov15_dtfim = verificaFeriado($oLinha->ov15_dtfim,false);
		//Aqui verifica se a diferença entre a data ini e fim é a que existia se não for empurra a dt fim mais a frente
		while ($dias < $oParam->linhas[$iInd]->difdatas){
			$oLinha->ov15_dtfim = verificaFeriado(date('Y-m-d',strtotime("+1 day",$mktime_dtfim)),false);
			$dtfim = explode('-',$oLinha->ov15_dtfim);
			$mktime_dtfim = mktime(0,0,0,$dtfim[1],$dtfim[2],$dtfim[0]);			
			$dias = ($mktime_dtfim - $mktime_dtini)/86400 ;
			$dias = ceil($dias);
		}
		
		$oLinha->ov15_sequencial 	= $oParam->linhas[$iInd]->ov15_sequencial;
		$oLinha->ov15_coddepto 		= $oParam->linhas[$iInd]->ov15_coddepto;
		$oLinha->descrdepto 			= $oParam->linhas[$iInd]->descrdepto;
		$oLinha->difdatas		 			= $oParam->linhas[$iInd]->difdatas;
		$oLinha->alterado		 			= 's';
		$oLinha->p61_coddepto			= $oParam->linhas[$iInd]->p61_coddepto;
		$oRetorno->linhas[$iInd] 	= $oLinha;
			
		//Aqui verifica se a data fim é menor que a data ini da proxima linha
		
		if($iInd+1 < $iNumRows){
			$oParam->linhas[$iInd+1]->dtini = date('Y-m-d',strtotime("+1 day",$mktime_dtfim));
			/*
			$dtini_prox = formataData($oParam->linhas[$iInd+1]->dtini,'b');	
			$dtini_prox = explode('-',$dtini_prox);
			$mktime_dtini_prox = mktime(0,0,0,$dtini_prox[1],$dtini_prox[2],$dtini_prox[0]);
			if(($mktime_dtini_prox - $mktime_dtfim) <= 0){
				$iInd1 = $iInd + 1;
				$oParam->linhas[$iInd1]->dtini = date('Y-m-d',strtotime("+1 day",$mktime_dtfim));
			} 
			*/
		}
		
	}
		
	$oRetorno->status 	=	 1;
	
}else if ($oParam->acao == 'alterar'){
	
	$lerro = false;
	db_inicio_transacao();
	
	$iNumRows = count($oParam->linhas);
	
	for($iInd = 0; $iInd < $iNumRows; $iInd++){
				
		if(!$lerro){			
			$clprocessoouvidoriaprorrogacao_alteracao->ov15_ativo 		 = 'false';
			$clprocessoouvidoriaprorrogacao_alteracao->ov15_sequencial = $oParam->linhas[$iInd]->ov15_sequencial;			
			$clprocessoouvidoriaprorrogacao_alteracao->alterar($oParam->linhas[$iInd]->ov15_sequencial);			
			if($clprocessoouvidoriaprorrogacao_alteracao->erro_status == '0'){
				$lerro = true;
				$oRetorno->message = utf8_encode($clprocessoouvidoriaprorrogacao_alteracao->erro_msg);
			}
		}else{
			break;		
		}
		
		if(!$lerro){			
			$clprocessoouvidoriaprorrogacao->ov15_ativo 				= 't';
			$clprocessoouvidoriaprorrogacao->ov15_coddepto			=	$oParam->linhas[$iInd]->ov15_coddepto;
			$clprocessoouvidoriaprorrogacao->ov15_dtfim					= formataData($oParam->linhas[$iInd]->ov15_dtfim,'b');			
			$clprocessoouvidoriaprorrogacao->ov15_dtini					= formataData($oParam->linhas[$iInd]->ov15_dtini,'b');			
			$clprocessoouvidoriaprorrogacao->ov15_motivo				= utf8_decode(str_replace("/n","\n", $oParam->ov15_motivo));			
			$clprocessoouvidoriaprorrogacao->ov15_protprocesso	= $oParam->ov15_protprocesso;
			$clprocessoouvidoriaprorrogacao->incluir(null);			
			if($clprocessoouvidoriaprorrogacao->erro_status == '0'){			
				$lerro = true;
				$oRetorno->message = utf8_encode($clprocessoouvidoriaprorrogacao->erro_msg);
			}
		}else{
			break;		
		}			
	}
	
	if(!$lerro){
		$oRetorno->status 	= 1;
		$oRetorno->message 	= utf8_encode('Usuário:\\n\\n Atualização efetuada com sucesso!\\n\\nAdministrador:\\n\\n');
	}
	
	db_fim_transacao($lerro);
	
}

echo $oJson->encode($oRetorno);
exit();

/*>>>>>>>>>>>>>>>>>>>>>>>>>Funções<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/

function formataData($data,$tipo){
	if($tipo == 'b'){
		 return implode('-',array_reverse(explode('/',$data)));
	}else{
		return implode('/',array_reverse(explode('-',$data)));
	}
}

/**
 * Função para verificar se uma data esta cadastrada como feriado
 * se $retorno = true retorna true em caso de ser feriado senao retorna a proria data
 * informada, se for false retorna o proximo dia util 
 *
 * @param data no formato 'yyyy-mm-dd' $data
 * @param true ou false $retorno
 * @param objeto que instanciuou a classe $oCalend
 * @return true ou data 
 */
function verificaFeriado($data,$retorno){
	
	
	$clCalend = db_utils::getDao('calend');
	
	if($retorno){
		$rsConsultaFeriado = $clCalend->sql_record($clCalend->sql_query_file($data));
		if 	($clCalend->numrows > 0) {
			return true;
		}
		return $data;
	}else{
		$lSentinela = true;
		while ($lSentinela){
			//echo $clCalend->sql_query_file($data)."\n";
			$rsConsultaFeriado = $clCalend->sql_record($clCalend->sql_query_file($data));
			if 	($clCalend->numrows > 0) {
				$aData = explode("-",$data);
				$data  = mktime(0,0,0,$aData[1],$aData[2],$aData[0]);
				//echo $aData[2].'-'.$aData[1].'-'.$aData[0]."\n";
				$data  = date('Y-m-d',strtotime("+1 day",$data));
			}else{
				$lSentinela = false;
				return $data;
				
			}		
		}
	}
	
}
?>