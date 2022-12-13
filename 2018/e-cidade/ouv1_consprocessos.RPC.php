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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["dados"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 0;
$oRetorno->message = "";

//var_dump($oParam);
//exit;

if($oParam->acao == 'pesquisar'){
	/*
  echo "<pre>";
  print_r($oParam->processo);
  echo "</pre>";
  */
	if($oParam->processo[0]->tipo == 0) {
		
		$coddepto	 =  $oParam->processo[0]->p58_coddepto;
		$datausu	 = 	date('Y-m-d',db_getsession('DB_datausu'));
		
		$sWhere = "";
		
		if($oParam->processo[0]->dtinicial != "" && $oParam->processo[0]->dtfim != ""){
			$sWhere = " and p58_dtproc between '".$oParam->processo[0]->dtinicial."' and '".$oParam->processo[0]->dtfim."'"; 
		}
		
		
		$sQueryProcessos 	= "select distinct 				                     ";
    $sQueryProcessos .= "  				p58_codproc,	                     ";
    $sQueryProcessos .= "   			p58_codigo, 	                     ";  
    $sQueryProcessos .= "         p51_descr,                         ";
    $sQueryProcessos .= "   			p.p58_requer,                      ";
    $sQueryProcessos .= "   			p.p58_dtproc,                      ";
    $sQueryProcessos .= "         ( select coddepto||'-'||descrdepto ";
		$sQueryProcessos .= "             from db_depart                 ";
		if( $coddepto != 0 ) {
	  	$sQueryProcessos .= "          where coddepto = {$coddepto}                       ";
		} else {
  		$sQueryProcessos .= "          where coddepto = fc_deptoatualprocesso(p58_codproc)";  		
		}
		$sQueryProcessos .= "         ) as deptoatual,                                                                                ";
		$sQueryProcessos .= "         case                                                                                            ";   
		$sQueryProcessos .= "           when exists  ( select 1                                                                       ";
		$sQueryProcessos .= "                            from proctransferproc                                                        ";  
		$sQueryProcessos .= "                       left join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran "; 
		$sQueryProcessos .= "                           where p63_codproc     = p58_codproc  ";
		$sQueryProcessos .= "                             and p64_codtran is null limit 1  ) then null "; 
    $sQueryProcessos .= "           else p61_dtandam ";
		$sQueryProcessos .= "         end as p61_dtandam, ";
    $sQueryProcessos .= "      		( select max( ov15_dtfim ) 																							 ";
    $sQueryProcessos .= "   					from processoouvidoriaprorrogacao            											   ";
    $sQueryProcessos .= "   			 	 where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc  ";
    $sQueryProcessos .= "        			 and processoouvidoriaprorrogacao.ov15_ativo is true 				         ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= "  					 and processoouvidoriaprorrogacao.ov15_coddepto  = $coddepto 			                   ";
    } else {
    	$sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";
    }
    $sQueryProcessos .= "   			) as ov15_dtfim,																												 ";
    $sQueryProcessos .= "         cast('".date('Y-m-d',db_getsession('DB_datausu'))."' as date ) - 
                                  ( select max( ov15_dtfim )                                               ";
    $sQueryProcessos .= "             from processoouvidoriaprorrogacao                                    ";
    $sQueryProcessos .= "            where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc  ";
    $sQueryProcessos .= "              and processoouvidoriaprorrogacao.ov15_ativo is true                 ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = $coddepto                        ";
    } else {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";
    }
    $sQueryProcessos .= "          ) as diasatraso                                                                                 ";    
  	$sQueryProcessos .= "			from processoouvidoria 																												                       ";
    $sQueryProcessos .= "     inner join protprocesso p   on p.p58_codproc                = processoouvidoria.ov09_protprocesso    ";
    $sQueryProcessos .= "     inner join tipoproc         on tipoproc.p51_codigo          = p.p58_codigo                           ";
    $sQueryProcessos .= "     left  join procandam        on procandam.p61_codandam       = p.p58_codandam                         ";  	
    $sQueryProcessos .= "	 	 where p51_tipoprocgrupo = 2                                                                           "; 
    /**
     * filtro pelo codigo do atendimento/processo Codigo ouvidoria = ov09_ouvidoriaatendimento
     * Código processo = p58_codproc
     */   	 
    if (trim($oParam->processo[0]->p58_codigo != '')) {
    	$sQueryProcessos .= " and p58_codigo                                  = {$oParam->processo[0]->p58_codigo} ";
    }
    if (trim($oParam->processo[0]->iCodigoAtendimento != '')) {
    	$sQueryProcessos .= " and processoouvidoria.ov09_ouvidoriaatendimento = {$oParam->processo[0]->iCodigoAtendimento} ";
    }
    if (trim($oParam->processo[0]->iNumeroProcesso != '')) {
    	$sQueryProcessos .= " and processoouvidoria.ov09_protprocesso         = {$oParam->processo[0]->iNumeroProcesso} ";
    }
		$sQueryProcessos .= " and (( exists (select 1 					                                                                       ";  
    $sQueryProcessos .=	"  			           from proctransferproc 			                                                             ";                                   
    $sQueryProcessos .=	" 				              inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .=	" 				              left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .=	"				          where p63_codproc = p58_codproc                                                          ";
		
		if ( $coddepto != 0 ) {
		  $sQueryProcessos .=	"				and p62_coddeptorec = $coddepto 	  ";
		} 

		$sQueryProcessos .=	"    		and p64_codtran is null limit 1 ) 	  ";
		$sQueryProcessos .=	"				or (                                  ";
		
		if ( $coddepto != 0 ) {
      $sQueryProcessos .= "               p61_coddepto    = $coddepto ";
		} else {
			$sQueryProcessos .= "               p61_coddepto is not null    ";
		}
  	
		$sQueryProcessos .=	"								and not exists( select *                                                                                ";
		$sQueryProcessos .=	"		  														from proctransferproc 																																";                                		
		$sQueryProcessos .=	"                                   	inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran";
		$sQueryProcessos .=	"		    	             			 		left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran 		  ";
		$sQueryProcessos .=	"				       				 					where p63_codproc  = p58_codproc 																											  ";
	 	
		if ( $coddepto != 0 ) {			
		  $sQueryProcessos .=	"					                      and p62_coddepto = $coddepto ";
	 	}			

	 	$sQueryProcessos .=	"															    and p64_codtran is null limit 1 ) 																 "; 
		$sQueryProcessos .=	"            )	    					                                                                       ";
		$sQueryProcessos .=	"			)  											                                                                       ";
	  $sQueryProcessos .=	"  or (   p58_codandam = 0  	                                                                       ";				
		$sQueryProcessos .=	"		and exists ( select 1 		                                                                       ";
		$sQueryProcessos .=	"									from proctransferproc          																										 ";
    $sQueryProcessos .=	"									inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .=	"											where p63_codproc = p58_codproc                                                ";
		
		if ( $coddepto != 0 ) {
	    $sQueryProcessos .=	"											and p62_coddeptorec = $coddepto ";
		}
    
		//$sQueryProcessos .=	"											limit 1 ) ";    										
		$sQueryProcessos .= "                             ) ";
 		$sQueryProcessos .=	"		 )													";								
		$sQueryProcessos .=	"	) 														";   
		
		if (trim($oParam->processo[0]->p58_codigo) != '') {
	  	$sQueryProcessos .=	" and p58_codigo = ".$oParam->processo[0]->p58_codigo;
		}
		
		$sQueryProcessos .=	$sWhere;

		
		$rsQueryProcessos	= pg_query($sQueryProcessos);
		if (pg_num_rows($rsQueryProcessos) > 0) {
			
			$oRetorno->processos = db_utils::getColectionByRecord($rsQueryProcessos, false, false, true);
			$oRetorno->status = 1;
		} else {
			
			$oRetorno->status = 0;
			$oRetorno->message = utf8_encode("Usuário:\\n\\n Nenhum processo encontrado para o departamento selecionado!\\n\\nAdministrador:\\n\\n");
		}
		
} else if ($oParam->processo[0]->tipo == 1) {
	
		//Aqui verifica todos que estao em execução

		$sQueryProcessos    = "select distinct p58_codproc, p58_codigo, p58_requer, p58_dtproc, p61_dtandam, max(ov15_dtfim) as ov15_dtfim, p51_descr,   ";
		$sQueryProcessos   .= "       cast('".date('Y-m-d',db_getsession('DB_datausu'))."' as date) - p61_dtandam as diasatraso,";
    $sQueryProcessos   .= "       coddepto||'-'||descrdepto as deptoatual                                                   ";
		$sQueryProcessos   .= "	 from procandam as pa                                                                           ";
    $sQueryProcessos   .= "       inner join db_depart                           on db_depart.coddepto    = pa.p61_coddepto ";
		$sQueryProcessos   .= "       inner join protprocesso as pp 							 	 on pa.p61_codandam 			= pp.p58_codandam ";
		$sQueryProcessos   .= "  	      																						and pa.p61_codproc			  = pp.p58_codproc  ";
		/**
     * inner join com processoouvidoria
     */
		$sQueryProcessos   .= "       LEFT join processoouvidoria     on processoouvidoria.ov09_protprocesso = pp.p58_codproc  ";
		$sQueryProcessos   .= "       inner join tipoproc as tp 						 			 	 on tp.p51_codigo  		    = pp.p58_codigo   ";
		$sQueryProcessos   .= "       inner join processoouvidoriaprorrogacao as pop on pop.ov15_protprocesso = pp.p58_codproc  "; 
		$sQueryProcessos   .= "				           																		and pop.ov15_ativo is true                  ";
		$sQueryProcessos   .= "										           										  	and pop.ov15_coddepto			= pa.p61_coddepto ";
		$sQueryProcessos   .= " where tp.p51_tipoprocgrupo = 2 ";
		
		/**
     * filtro pelo codigo do atendimento/processo Codigo ouvidoria = ov09_ouvidoriaatendimento
     * Código processo = p58_codproc
     */     
		if (trim($oParam->processo[0]->p58_codigo)) {
			$sQueryProcessos .= " and protprocesso.p58_codigo                     = {$oParam->processo[0]->p58_codigo} ";
		}
		if (trim($oParam->processo[0]->iCodigoAtendimento)) {
			$sQueryProcessos .= " and processoouvidoria.ov09_ouvidoriaatendimento = {$oParam->processo[0]->iCodigoAtendimento} ";
		}
		if (trim($oParam->processo[0]->iNumeroProcesso)) {
			$sQueryProcessos .= " and processoouvidoria.ov09_protprocesso         = {$oParam->processo[0]->iNumeroProcesso} ";
		}
		if ( $oParam->processo[0]->p58_coddepto != 0 ) {
		  $sQueryProcessos   .= "	and pa.p61_coddepto = ".$oParam->processo[0]->p58_coddepto;
		}
		
		if ( trim($oParam->processo[0]->p58_codigo) != '' ) {
		  $sQueryProcessos   .= "  and pp.p58_codigo = ".$oParam->processo[0]->p58_codigo;
		}
		
		$sQueryProcessos   .= "  and not exists( select 1 ";
		$sQueryProcessos   .= "                 from proctransferproc ";
		$sQueryProcessos   .= "           	            left join proctransand on p64_codtran = p63_codtran ";  
		$sQueryProcessos   .= "                  where p63_codproc = p58_codproc "; 
		$sQueryProcessos   .= "                    and p64_codtran is null limit 1 )";

		if($oParam->processo[0]->dtinicial != "" && $oParam->processo[0]->dtfim != ""){
			$sQueryProcessos .= " and pp.p58_dtproc between '".$oParam->processo[0]->dtinicial."' and '".$oParam->processo[0]->dtfim."'";
		}
		
		$sQueryProcessos .= " group by p58_codproc, p58_codigo, p58_requer, p58_dtproc, p61_dtandam, p51_descr, diasatraso, deptoatual"; 
		
		$rsQueryProcessos	= pg_query($sQueryProcessos);
		if (pg_num_rows($rsQueryProcessos)>0) {
			
			$oRetorno->processos = db_utils::getColectionByRecord($rsQueryProcessos, false, false, true);
			$oRetorno->status    = 1;
		} else {
			
			$oRetorno->status = 0;
			$oRetorno->message = utf8_encode("Usuário:\\n\\n Nenhum processo em andamento para o departamento selecionado!\\n\\nAdministrador:\\n\\n");
		}
				
} else if($oParam->processo[0]->tipo == 2) {
		
		$coddepto	 =  $oParam->processo[0]->p58_coddepto;
		$datausu	 = 	date('Y-m-d',db_getsession('DB_datausu'));
		
		$sWhere = "";
		
		if($oParam->processo[0]->dtinicial != "" && $oParam->processo[0]->dtfim != ""){
			$sWhere = " and p58_dtproc between '".$oParam->processo[0]->dtinicial."' and '".$oParam->processo[0]->dtfim."'";  
		}
		
		$sQueryProcessos 	= "select distinct ";
    $sQueryProcessos .= "  				p58_codproc,   ";
    $sQueryProcessos .= "   			p58_codigo,    ";  
    $sQueryProcessos .= "         p51_descr,     ";
    $sQueryProcessos .= "   			p.p58_requer , ";
    $sQueryProcessos .= "   			p.p58_dtproc , ";
    $sQueryProcessos .= "         ( select coddepto||'-'||descrdepto ";
    $sQueryProcessos .= "             from db_depart                 ";
    if( $coddepto != 0 ) {
      $sQueryProcessos .= "          where coddepto = {$coddepto}    ";
    } else {
      $sQueryProcessos .= "          where coddepto = fc_deptoatualprocesso(p58_codproc)";      
    }
    $sQueryProcessos .= "         ) as deptoatual,                   ";
    $sQueryProcessos .= "         case                               ";   
    $sQueryProcessos .= "           when exists  ( select 1 ";
    $sQueryProcessos .= "                            from proctransferproc ";  
    $sQueryProcessos .= "                                 left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran "; 
    $sQueryProcessos .= "                           where p63_codproc     = p58_codproc  ";
    $sQueryProcessos .= "                             and p64_codtran is null limit 1  ) then null "; 
    $sQueryProcessos .= "           else p61_dtandam ";
    $sQueryProcessos .= "         end as p61_dtandam, ";
    $sQueryProcessos .= "         ( select max( ov15_dtfim )                                               ";
    $sQueryProcessos .= "             from processoouvidoriaprorrogacao                                    ";
    $sQueryProcessos .= "            where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc  ";
    $sQueryProcessos .= "              and processoouvidoriaprorrogacao.ov15_ativo is true                 ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = $coddepto         ";
    } else {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";
    }
    $sQueryProcessos .= "         ) as ov15_dtfim,                                                         ";
    $sQueryProcessos .= "         cast('".date('Y-m-d',db_getsession('DB_datausu'))."' as date ) - 
                                  ( select max( ov15_dtfim )                                               ";
    $sQueryProcessos .= "             from processoouvidoriaprorrogacao                                    ";
    $sQueryProcessos .= "            where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc  ";
    $sQueryProcessos .= "              and processoouvidoriaprorrogacao.ov15_ativo is true                 ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = $coddepto         ";
    } else {
      $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";
    }
    $sQueryProcessos .= "          ) as diasatraso  ";
  	$sQueryProcessos .= "			from processoouvidoria ";
    $sQueryProcessos .= "   	inner join protprocesso p   on p.p58_codproc          			= processoouvidoria.ov09_protprocesso ";
    $sQueryProcessos .= "   	inner join tipoproc         on tipoproc.p51_codigo    			= p.p58_codigo ";
    $sQueryProcessos .= "   	left  join procandam        on procandam.p61_codandam 			= p.p58_codandam ";
		$sQueryProcessos .= "	 		where p51_tipoprocgrupo = 2  ";
		$sQueryProcessos .= " and (( exists (select 1 ";  
    $sQueryProcessos .= "				from proctransferproc ";                                   
    $sQueryProcessos .= " 			inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
    $sQueryProcessos .= "  			left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
    $sQueryProcessos .= "			where p63_codproc     = p58_codproc                  ";
    
    if ( $coddepto != 0 ) {    
      $sQueryProcessos .= "				and p62_coddeptorec = $coddepto ";
    }
    
    $sQueryProcessos .= "				and p64_codtran is null limit 1 )     ";       	       					                 
		$sQueryProcessos .= " 			or (                                  ";
		
		if ( $coddepto != 0 ) {
      $sQueryProcessos .= "		            p61_coddepto = $coddepto    ";
    } else {
      $sQueryProcessos .= "               p61_coddepto is not null    ";
    }
        
    $sQueryProcessos .= "							and not exists( select *        ";
		$sQueryProcessos .= "																from proctransferproc ";                                		
		$sQueryProcessos .= "                              	inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .= "		    	             			 		left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .= "				       				 					where p63_codproc  = p58_codproc    ";
    
		if ( $coddepto != 0 ) {		
		  $sQueryProcessos .= "						                      and p62_coddepto = $coddepto 			";
    }
		
    $sQueryProcessos .= "															    and p64_codtran is null limit 1 ) "; 
    $sQueryProcessos .= "				  )	    								 ";
		$sQueryProcessos .= "			)       									 ";
	  $sQueryProcessos .= "	or (   p58_codandam = 0  		 ";			
		$sQueryProcessos .= "						and exists ( select 1 "; 
		$sQueryProcessos .= "											from proctransferproc ";          
    $sQueryProcessos .= "  										inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
		$sQueryProcessos .= "											where p63_codproc = p58_codproc  ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= " 										and p62_coddeptorec = $coddepto ";
    }
    $sQueryProcessos .= "					limit 1 )    										 ";
 		$sQueryProcessos .= " 	)									 ";
		$sQueryProcessos .= " )    ";
		
		if ( trim($oParam->processo[0]->p58_codigo) != '' ) {
		  $sQueryProcessos .= " and p58_codigo = ".$oParam->processo[0]->p58_codigo; 
		}
		
		$sQueryProcessos .= "							and ( select max( ov15_dtfim ) "; 
    $sQueryProcessos .= "       							from processoouvidoriaprorrogacao	";
    $sQueryProcessos .= "      							  where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc ";
    $sQueryProcessos .= "        							and processoouvidoriaprorrogacao.ov15_ativo is true ";
    if ( $coddepto != 0 ) {
      $sQueryProcessos .= "        					  and processoouvidoriaprorrogacao.ov15_coddepto = $coddepto ) < '$datausu' ";
    } else {
    	$sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc) ) < '$datausu'";
    }        
    
    
    if (trim($oParam->processo[0]->p58_codigo) != '') {
      $sQueryProcessos .= " and p58_codigo                 = {$oParam->processo[0]->p58_codigo}      ";
    }
    if (trim($oParam->processo[0]->iCodigoAtendimento) != '') {
    	$sQueryProcessos .= " and processoouvidoria.ov09_ouvidoriaatendimento = {$oParam->processo[0]->iCodigoAtendimento} ";
    }
    if (trim($oParam->processo[0]->iNumeroProcesso) != '') {
    	$sQueryProcessos .= " and processoouvidoria.ov09_protprocesso         = {$oParam->processo[0]->iNumeroProcesso} ";
    }
    //
    $sQueryProcessos .=												$sWhere;
		
   	$rsQueryProcessos	= pg_query($sQueryProcessos);
		
		if(pg_num_rows($rsQueryProcessos)>0){
			$oRetorno->processos = db_utils::getColectionByRecord($rsQueryProcessos,false,false,true);
			$oRetorno->status = 1;
		}else{
			$oRetorno->status = 0;
			$oRetorno->message = utf8_encode("Usuário:\\n\\n Nenhum processo em atrazo para o departamento selecionado!\\n\\nAdministrador:\\n\\n");
		}

	}
	
}
echo $oJson->encode($oRetorno);
exit();
?>