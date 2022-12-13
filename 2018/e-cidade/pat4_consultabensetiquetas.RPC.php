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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");


// die('dfaskjfgasdgkfjagshkjs');

//header("Content-type: application/xml; charset=ISO-8859-1");

$oJson             = new services_json();
//$oParam            = db_utils::postMemory($_POST);
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["dados"])));
//var_dump($oParam);
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = urlencode('Erro ao buscar informações');

if($oParam->exec == 'pesquisa'){
  $sWhere = "";	
	if(trim($oParam->t52_depart) != ""){
		if($sWhere == ""){
			$sWhere .= " t52_depart = ".$oParam->t52_depart;
		}else{
			$sWhere .= " and t52_depart = ".$oParam->t52_depart;
		}
	}
  if(trim($oParam->t30_codigo) != ""){
    if($sWhere == ""){
      $sWhere .= " t33_divisao = ".$oParam->t30_codigo;
    }else{
      $sWhere .= " and  t33_divisao = ".$oParam->t30_codigo;
    }
  }
  if(trim($oParam->t52_beminicial) != ""){
    if($sWhere == ""){
    	
    	if(trim($oParam->t52_beminicial) != "" && trim($oParam->t52_bemfinal) != ""){
    		if($sWhere == ""){
    	   $sWhere .= " t52_bem between ".$oParam->t52_beminicial." and ".$oParam->t52_bemfinal;
    		}else{
    		  $sWhere .= " and t52_bem between ".$oParam->t52_beminicial." and ".$oParam->t52_bemfinal;
    		}
    	}else if(trim($oParam->t52_beminicial) != ""){
    		if($sWhere == ""){
    		  $sWhere .= " t52_bem >= ".$oParam->t52_beminicial;
    		}else{
    			$sWhere .= " and t52_bem >= ".$oParam->t52_beminicial;
    		}
    	}else if(trim($oParam->t52_bemfinal) != ""){
    		if($sWhere == ""){
    		  $sWhere .= " t52_bem <= ".$oParam->t52_beminicial;
    		}else{
    			$sWhere .= " and t52_bem <= ".$oParam->t52_beminicial;
    		}
    	}
    	
      
    }else{
      $sWhere .= " and  t33_divisao = ".$oParam->t30_codigo;
    }
  }
  if(trim($oParam->t52_bemfinal) != ""){
    
  }
	
  $sQueryBensEtiqueta  = " select t52_bem,t52_descr,t52_ident ";
  $sQueryBensEtiqueta .= "   from bens ";
  $sQueryBensEtiqueta .= "        left join bensdiv on bensdiv.t33_bem = t52_bem ";
  $sQueryBensEtiqueta .= "  where ".$sWhere;
  $sQueryBensEtiqueta .= "  order by t52_descr "; 
  
  $rsQueryBensEtiqueta = db_query($sQueryBensEtiqueta);
  $iNumRows = pg_num_rows($rsQueryBensEtiqueta);
  if($iNumRows > 0){
  	$oRetorno->dados = db_utils::getColectionByRecord($rsQueryBensEtiqueta,false,false,true);
  	$oRetorno->status  = 0;
  	$oRetorno->message = '';
  }else{
  	$oRetorno->message = urlencode("Nenhum bem retornado para o(s) filtro(s) selecionado(s)!!! ");
  	$oRetorno->status  = 2;
  }
  
}else if(trim($oParam->exec) == 'imprimir'){
	
	$sqlErro = false;
	
	require_once("model/impressaoEtiquetaGateway.php");
	require_once("classes/db_bensplacaimpressa_classe.php");
  require_once("classes/db_bensetiquetaimpressa_classe.php");

  $clBensEtiquetaImpressa = new cl_bensetiquetaimpressa();
  $clBensPlacaImpressa    = new cl_bensplacaimpressa(); 
	$clEtiquetaGateway      = new impressaoEtiquetaGateway();
  
  /**
   * Imprimindo etiquetas dos bens selecionados 
   */
	$aBens = explode(',',$oParam->bens);
	$iNumBens = count($aBens);
	//die("InumBens = ".$iNumBens);
	try {
	  
	  $oModeloEtiqueta = $clEtiquetaGateway->getModelo();
	  for($iInd = 0; $iInd < $iNumBens; $iInd++){

	    $oModeloEtiqueta->setBem($aBens[$iInd]);
	    $oModeloEtiqueta->imprimirEtiqueta();
	    sleep(2);
	  }
	}catch (Exception $erro){
		
	  $msg = "usuário:\\n\\n ".$erro->getMessage()."\\n\\n";
		$oRetorno->message = urlencode($msg);
		$oRetorno->status  = 2;
		$sqlErro = true;  
	  //db_msgbox($msg);
	  //echo $erro->getMessage();
	}
	
	//die('aqiii');
  if(!$sqlErro){
  	
  	//die('aqiii');
	
		db_inicio_transacao();
    $iIdUsuario = db_getsession("DB_id_usuario");
		$sData      = date("Y-m-d",db_getsession("DB_datausu"));
		$sHora      = date("H:i");

		$sQueryBensEtiqueta  = " select t52_bem,
		                                t52_depart,
		                                t33_divisao,
		                                (select t41_codigo       
                                       from bensplaca
                                      where bensplaca.t41_bem = bens.t52_bem
                                      order by bensplaca.t41_placaseq desc 
                                      limit 1) as t41_codigo ";
	  $sQueryBensEtiqueta .= "   from bens ";
	  $sQueryBensEtiqueta .= "        left join bensdiv on bensdiv.t33_bem = t52_bem ";
	  $sQueryBensEtiqueta .= "  where t52_bem in (".$oParam->bens.")";
    
	  $rsQueryBensEtiqueta = db_query($sQueryBensEtiqueta);
	  $iNumRows = pg_num_rows($rsQueryBensEtiqueta);
	  
	  if($iNumRows > 0){
	  
			$clBensEtiquetaImpressa->t74_usuario = $iIdUsuario;
			$clBensEtiquetaImpressa->t74_data    = $sData;
			$clBensEtiquetaImpressa->t74_hora    = $sHora;
		
			$clBensEtiquetaImpressa->incluir(null);
			if($clBensEtiquetaImpressa->erro_status == "0"){
				
				$sqlErro = true;
				$msg = $clBensEtiquetaImpressa->erro_msg;
			}
			
			if(!$sqlErro){
				//echo "aqui bens placa impressa";
				$iBensEtiquetaImpressa = $clBensEtiquetaImpressa->t74_sequencial;
        if($iNumRows == 1){
        	$lLote = "false";
        }else{
        	$lLote = "true";
        }
        
				for($iInd = 0; $iInd < $iNumRows; $iInd++){
					
					$oBem = db_utils::fieldsMemory($rsQueryBensEtiqueta,$iInd);
					$clBensPlacaImpressa->t73_bensetiquetaimpressa = $iBensEtiquetaImpressa;
					$clBensPlacaImpressa->t73_bensplaca = $oBem->t41_codigo;
					$clBensPlacaImpressa->t73_coddepto  = $oBem->t52_depart;
					if(trim($oBem->t33_divisao) != ""){
					 $clBensPlacaImpressa->t73_departdiv = $oBem->t33_divisao;
					}
					$clBensPlacaImpressa->t73_tipoloteindividual = $lLote;
					$clBensPlacaImpressa->incluir(null);
					if ($clBensPlacaImpressa->erro_status == "0"){
						$sqlErro = true;
            $msg = $clBensPlacaImpressa->erro_msg;
					}
				}
				if(!$sqlErro){
				  $msg = "Deseja imprimir o relatório das etiquetas emitidas?";
				}
			}
	  }else{
	  	$msg = "usuário:\\n\\n Erro ao registrar etiquetas emitidas !!!\\n\\n";
      $oRetorno->message = urlencode($msg);
      $oRetorno->status  = 2;
      $sqlErro = true;  
	  }
	  
	  if (!$sqlErro) {
	  	
	  	
      $oRetorno->status          = 0;
      $oRetorno->icodigoetiqueta = $iBensEtiquetaImpressa;
	  	
	  }else{
	  	$oRetorno->status          = 2;
	  	$oRetorno->message = urlencode($msg);
	  }
	  
	  $oRetorno->message         = urlencode($msg);
		db_fim_transacao($sqlErro);
		
  }
}
//echo $oParam->exec;
echo $oJson->encode($oRetorno);
?>