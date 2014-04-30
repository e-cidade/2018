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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson      = new Services_JSON();

$oParametro = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno   = new stdClass();

$oRetorno->status  = 1;

$oRetorno->message = '';

switch ($oParametro->sExec) {
	case 'getQuadraSetor' :
		
		$oDAOLoteloc = db_utils::getDao('loteloc');
		
		$sCampo = "distinct j06_quadraloc";
		$sOrdem = "j06_quadraloc";
		$sWhere = "j05_codigoproprio = '{$oParametro->iCodSetor}'";
	  $sSql   = $oDAOLoteloc->sql_query(null, $sCampo, $sOrdem, $sWhere); 

		$rsDAOLoteloc = $oDAOLoteloc->sql_record($sSql);
		
		if($oDAOLoteloc->numrows > 0) {
			
			$oQuadras = db_utils::getColectionByRecord($rsDAOLoteloc);
			
			$oRetorno->oQuadras = $oQuadras;
			
		} else {
			
			$oRetorno->status  = 2;
			
			$oRetorno->message = 'Nenhum registro encontrado.';
			
		}
		
		echo $oJson->encode($oRetorno);
		
		break;
		
	case 'getLote':
		
	  $oDAOLoteloc = db_utils::getDao('loteloc');

	  $sCampo  = "distinct j06_lote";
	  $sOrdem  = "         j06_lote";
	  
	  $sWhere  = "    j05_codigoproprio = '{$oParametro->iSetor}' ";
	  $sWhere .= "and j06_quadraloc     = '{$oParametro->sQuadra}'";  
	  $sSql    = $oDAOLoteloc->sql_query(null, $sCampo, $sOrdem, $sWhere);
	  
		$rsDAOLoteloc = $oDAOLoteloc->sql_record($sSql);
		
		if($oDAOLoteloc->numrows > 0) {
			
			$oRetorno->oLotes = db_utils::getColectionByRecord($rsDAOLoteloc);
			
		} else {
			
			$oRetorno->status  = 2;
			
			$oRetorno->message = 'Nenhum registro encontrado.';
			
		}
		
		echo $oJson->encode($oRetorno);
		
		break;
		
	case 'getSetor':
		
		$oDAOSetorloc = db_utils::getDao('setorloc');
		
		$rsSetorloc   = $oDAOSetorloc->sql_record($oDAOSetorloc->sql_query_file(null, 
																																						"*",
																																						"j05_descr",
																																						null));
																																						
    if($oDAOSetorloc->numrows > 0) {
    	
    	$oRetorno->oSetorloc = db_utils::getColectionByRecord($rsSetorloc);
    	
    } else {
    	
    	$oRetorno->status  = 2;
    	
    	$oRetorno->message = "Nenhum registro encontrado";
    }
		
    echo $oJson->encode($oRetorno);
    
		break;			
				
}