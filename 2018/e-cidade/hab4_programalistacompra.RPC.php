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
require("libs/db_conecta.php");
require("libs/db_sessoes.php");

require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_habitprogramalistacompra_classe.php");
require_once("classes/db_habitprogramalistacompraitem_classe.php");

$clHabitProgramaListaCompra     = new cl_habitprogramalistacompra();
$clHabitProgramaListaCompraItem = new cl_habitprogramalistacompraitem();

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = "";

try {
	
	
	if ( $oParam->sMethod == "consultaListas" ) {
		
		if ( $oParam->iCodPrograma == '' ) {
			throw new Exception("Cуdigo do programa nгo informado!");		
		}
		
		$sWhereListas = "ht17_habitprograma = {$oParam->iCodPrograma}";
		$sSqlListas   = $clHabitProgramaListaCompra->sql_query(null,"*","ht17_sequencial asc",$sWhereListas);
		$rsListas     = $clHabitProgramaListaCompra->sql_record($sSqlListas);
		
		$oRetorno->aDadosListas = db_utils::getColectionByRecord($rsListas,false,false,true); 
		
		
	} else if ( $oParam->sMethod == "incluirLista" ) {
		
		
	  if ( $oParam->iCodPrograma == '' ) {
      throw new Exception("Cуdigo do programa nгo informado!");   
    }
      		    
    $clHabitProgramaListaCompra->ht17_habitprograma  = $oParam->iCodPrograma; 
    $clHabitProgramaListaCompra->ht17_descricao      = utf8_decode($oParam->sDescricao);
    $clHabitProgramaListaCompra->ht17_formaavaliacao = $oParam->iFormaAvaliacao;
    $clHabitProgramaListaCompra->ht17_datalimite     = implode('-',array_reverse(explode('/',$oParam->dtDataLimite)));
		
    $clHabitProgramaListaCompra->incluir(null);
    
		if ( $clHabitProgramaListaCompra->erro_status == "0" ) {
			throw new Exception($clHabitProgramaListaCompra->erro_msg);
		}

		$oRetorno->sMsg = "Inclusгo feita com sucesso!";
		
  } else if ( $oParam->sMethod == "alterarLista" ) {
    
    
    if ( $oParam->iCodPrograma == '' ) {
      throw new Exception("Cуdigo do programa nгo informado!");   
    }
              
    $clHabitProgramaListaCompra->ht17_sequencial     = $oParam->iCodLista;
    $clHabitProgramaListaCompra->ht17_habitprograma  = $oParam->iCodPrograma; 
    $clHabitProgramaListaCompra->ht17_descricao      = utf8_decode($oParam->sDescricao);
    $clHabitProgramaListaCompra->ht17_formaavaliacao = $oParam->iFormaAvaliacao;
    $clHabitProgramaListaCompra->ht17_datalimite     = implode('-',array_reverse(explode('/',$oParam->dtDataLimite)));
    
    $clHabitProgramaListaCompra->alterar($oParam->iCodLista);
    
    if ( $clHabitProgramaListaCompra->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompra->erro_msg);
    }
    
    $oRetorno->sMsg = "Alteraзгo feita com sucesso!";

  } else if ( $oParam->sMethod == "excluirLista" ) {
    
    
    if ( $oParam->iCodLista == '' ) {
      throw new Exception("Lista nгo informada!");   
    }
    
    $clHabitProgramaListaCompraItem->excluir(null," ht18_habitprogramalistacompra = {$oParam->iCodLista}");
    
    if ( $clHabitProgramaListaCompraItem->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompraItem->erro_msg);
    }   
    
    
    $clHabitProgramaListaCompra->ht17_sequencial = $oParam->iCodLista; 
    $clHabitProgramaListaCompra->excluir($oParam->iCodLista);
    
    if ( $clHabitProgramaListaCompra->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompra->erro_msg);
    }

    $oRetorno->sMsg = "Exclusгo feita com sucesso!";
    
  } else if ( $oParam->sMethod == "consultaItensLista" ) {    

  	
    if ( $oParam->iCodLista == '' ) {
      throw new Exception("Lista nгo informada!");   
    }
    
    $sWhereItemLista = "ht18_habitprogramalistacompra= {$oParam->iCodLista}";
    $sSqlItemLista   = $clHabitProgramaListaCompraItem->sql_query(null,"*","ht18_sequencial",$sWhereItemLista);
    $rsItemLista     = $clHabitProgramaListaCompraItem->sql_record($sSqlItemLista);
    
    $oRetorno->aDadosItensLista = db_utils::getColectionByRecord($rsItemLista,false,false,true);   	

    
 } else if ( $oParam->sMethod == "incluirItemLista" ) {
    
    
    if ( $oParam->iCodLista == '' ) {
      throw new Exception("Lista nгo informada!");   
    }
              
    $clHabitProgramaListaCompraItem->ht18_habitprogramalistacompra = $oParam->iCodLista;
    $clHabitProgramaListaCompraItem->ht18_matunid                  = $oParam->iCodUnidade;
    $clHabitProgramaListaCompraItem->ht18_pcmater                  = $oParam->iCodMaterial;
    $clHabitProgramaListaCompraItem->ht18_quantidade               = $oParam->iQuantidade;
    
    $clHabitProgramaListaCompraItem->incluir(null);
    
    if ( $clHabitProgramaListaCompraItem->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompraItem->erro_msg);
    }    

    $oRetorno->sMsg = "Inclusгo feita com sucesso!";
    
  } else if ( $oParam->sMethod == "alterarItemLista" ) {
    
    
    if ( $oParam->iCodLista == '' ) {
      throw new Exception("Lista nгo informada!");   
    }
              
    $clHabitProgramaListaCompraItem->ht18_sequencial               = $oParam->iSeqItem;
    $clHabitProgramaListaCompraItem->ht18_habitprogramalistacompra = $oParam->iCodLista;
    $clHabitProgramaListaCompraItem->ht18_matunid                  = $oParam->iCodUnidade;
    $clHabitProgramaListaCompraItem->ht18_pcmater                  = $oParam->iCodMaterial;
    $clHabitProgramaListaCompraItem->ht18_quantidade               = $oParam->iQuantidade;
    
    $clHabitProgramaListaCompraItem->alterar($oParam->iSeqItem);
    
    if ( $clHabitProgramaListaCompraItem->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompraItem->erro_msg);
    }    

    $oRetorno->sMsg = "Alteraзгo feita com sucesso!";
    
  } else if ( $oParam->sMethod == "excluirItemLista" ) {
    
    if ( $oParam->iSeqItem == '' ) {
      throw new Exception("Item nгo nгo informado!");   
    }  	
  	
    $clHabitProgramaListaCompraItem->ht18_sequencial = $oParam->iSeqItem;
    $clHabitProgramaListaCompraItem->excluir($oParam->iSeqItem);
    
    if ( $clHabitProgramaListaCompraItem->erro_status == "0" ) {
      throw new Exception($clHabitProgramaListaCompraItem->erro_msg);
    }  	
    
    $oRetorno->sMsg = "Exclusгo feita com sucesso!";
    
	}
	
} catch ( Exception $eException ) {

	$oRetorno->iStatus = 2;
	$oRetorno->sMsg    = str_replace("\\n","\n",$eException->getMessage());
	
}

$oRetorno->sMsg = urlencode($oRetorno->sMsg);
echo $oJson->encode($oRetorno);

?>