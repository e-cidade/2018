<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_strings.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");

$oPost    = db_utils::postMemory($_POST);
$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';

require_once('classes/db_protprocesso_classe.php');
$clProtProcesso = new cl_protprocesso();

require_once('classes/db_proctransfer_classe.php');
$clProcTransfer = new cl_proctransfer();

require_once('classes/db_proctransferproc_classe.php');
$clProcTransferProc = new cl_proctransferproc();

require_once('classes/db_procandamint_classe.php');
$clProcAndamInt = new cl_procandamint();

require_once('classes/db_processoouvidoriaprorrogacao_classe.php');
$clProcessoOuvidoriaProrrogacao = new cl_processoouvidoriaprorrogacao();

require_once('classes/db_calend_classe.php');
$clCalend = new cl_calend();

require_once('model/processoOuvidoria.model.php');
$oProcessoOuvidoria = new processoOuvidoria();


if ( $oPost->sMethod == 'consultaProcessos') {
 
	$aListaProcesso  = array(); 
	
	$sCampoProcesso  = " distinct p58_codproc,                               ";
	$sCampoProcesso .= " ov01_numero || '/' || ov01_anousu as ov01_anousu,   ";
	$sCampoProcesso .= " p58_requer,                                         ";
	$sCampoProcesso .= " p58_codigo||' - '||p51_descr    as p58_codigo,      ";
	$sCampoProcesso .= " case                                                "; 
  $sCampoProcesso .= "   when p58_codandam = 0         then p58_coddepto||' - '||d.descrdepto        ";
  $sCampoProcesso .= "   when p61_codandam is null     then p62_coddepto||' - '||b.descrdepto        ";
  $sCampoProcesso .= "   when p61_codandam is not null then p61_coddepto||' - '||db_depart.descrdepto"; 
  $sCampoProcesso .= " end as p61_coddepto,                                ";
  $sCampoProcesso .= " case                                                "; 
  $sCampoProcesso .= "   when p58_codandam = 0         then c.login        ";
  $sCampoProcesso .= "   when p61_codandam is not null then db_usuarios.login"; 
  $sCampoProcesso .= "   when p61_codandam is null     then a.login        ";
  $sCampoProcesso .= " end as login,                                       ";  
  $sCampoProcesso .= " case                                                "; 
  $sCampoProcesso .= "   when exists (select 1                                                                                     ";  
  $sCampoProcesso .= "                  from proctransferproc                                                                      ";                                   
  $sCampoProcesso .= "                       inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran    ";
  $sCampoProcesso .= "                       left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran    ";
  $sCampoProcesso .= "                 where p63_codproc = p58_codproc                                                             ";
  $sCampoProcesso .= "                   and p62_coddeptorec = {$oPost->iDepto}                                                    ";
  $sCampoProcesso .= "                  and (    p62_id_usorec = 0                                                                 "; 
  $sCampoProcesso .= "                        or p62_id_usorec = {$oPost->iUsuario}                                                ";
  $sCampoProcesso .= "                      )                                                                                      ";  
  $sCampoProcesso .= "                  and p64_codtran is null limit 1 ) then 'false'                                             ";
  $sCampoProcesso .= "   else case                                                                                                 ";
  $sCampoProcesso .= "          when p61_codandam is not null then 'true' else 'false'                                             ";
  $sCampoProcesso .= "        end ";
  $sCampoProcesso .= " end as recebido,                                    ";
  $sCampoProcesso .= " p58_despacho                                        ";
  
  
	$sWhereProcesso  = "     p51_tipoprocgrupo = 2                           ";
  $sWhereProcesso .= " and ( ( exists (select 1                                                                                    ";  
  $sWhereProcesso .= "                   from proctransferproc                                                                     ";                                   
  $sWhereProcesso .= "                        inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran   ";
  $sWhereProcesso .= "                        left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran   ";
  $sWhereProcesso .= "                  where p63_codproc = p58_codproc                                                            ";
  $sWhereProcesso .= "                    and p62_coddeptorec = {$oPost->iDepto}                                                   ";
  $sWhereProcesso .= "                   and (    p62_id_usorec = 0                                                                "; 
  $sWhereProcesso .= "                         or p62_id_usorec = {$oPost->iUsuario}                                               ";
  $sWhereProcesso .= "                       )                                                                                     ";  
  $sWhereProcesso .= "                    and p64_codtran is null limit 1 )                                                        ";
  $sWhereProcesso .= "       or (                                                                                                  ";
  $sWhereProcesso .= "               p61_coddepto    = {$oPost->iDepto}                                                            ";
  $sWhereProcesso .= "               and not exists( select *                                                                      ";
  $sWhereProcesso .= "                                 from proctransferproc                                                       ";                                    
  $sWhereProcesso .= "                                     inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran  ";
  $sWhereProcesso .= "                               left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran    ";
  $sWhereProcesso .= "                               where p63_codproc  = p58_codproc                                              ";
  $sWhereProcesso .= "                                 and p64_codtran is null limit 1 )                                           "; 
  $sWhereProcesso .= "            )                ";
  $sWhereProcesso .= "     )                       ";
  $sWhereProcesso .= "  or (   p58_codandam = 0    ";        
  $sWhereProcesso .= "   and exists ( select 1     ";
  $sWhereProcesso .= "                 from proctransferproc                                                                       ";
  $sWhereProcesso .= "                 inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran          ";
  $sWhereProcesso .= "                     where p63_codproc = p58_codproc                                                         ";
  $sWhereProcesso .= "                     and p62_coddeptorec = {$oPost->iDepto}                                                  ";
  $sWhereProcesso .= "                     and (    p62_id_usorec = 0                                                              "; 
  $sWhereProcesso .= "                           or p62_id_usorec = {$oPost->iUsuario}                                             ";
  $sWhereProcesso .= "                         )                                                                                   ";  
  $sWhereProcesso .= "                     limit 1 ) ";                        
  $sWhereProcesso .= "    )                          ";                
  $sWhereProcesso .= " )                             ";
  
  
	if ( trim($oPost->iProcIni) != '' ) {
	  $sWhereProcesso .= " and p58_codproc >= {$oPost->iProcIni}  ";
	}
  if ( trim($oPost->iProcFin) != '' ) {
    $sWhereProcesso .= " and p58_codproc <= {$oPost->iProcFin}  ";
  }	
  if (trim($oPost->sAtendimento) != '') {
  	
    $sAtendimento = db_stdClass::normalizeStringJson($oPost->sAtendimento);
    /*
     * Adicionado validação para controlar se a string chegou com barra (/) ou sem ela.
     * Caso a função strpos encontre a "/", é feito explode, do contrário utilizamos os
     * valores padrao
     */
    $iAnoUsu      = db_getsession("DB_anousu");
    $iAtendimento = $sAtendimento;
    if (strpos($sAtendimento, "/")) {
	  	list ($iAtendimento, $iAnoUsu) = explode('/', $sAtendimento);
    }
 		$sWhereProcesso .= " and ov01_numero = {$iAtendimento} and ov01_anousu = {$iAnoUsu} ";
  }
  if ( trim($oPost->dtDataIni) != '' ) {
    $sWhereProcesso .= " and p58_dtproc >= '".implode('-',array_reverse(explode('/',$oPost->dtDataIni)))."'";
  }
  if ( trim($oPost->dtDataFin) != '' ) {
    $sWhereProcesso .= " and p58_dtproc <= '".implode('-',array_reverse(explode('/',$oPost->dtDataFin)))."'";
  }  
  if ( trim($oPost->iProcTipo) != '' ) {
    $sWhereProcesso .= " and p58_codigo = {$oPost->iProcTipo}  ";
  }    
  
  $sSqlProcesso = $clProtProcesso->sql_query_transand(null,$sCampoProcesso,"p58_codproc",$sWhereProcesso);
  $rsProcessos  = $clProtProcesso->sql_record($sSqlProcesso);

	if ( $clProtProcesso->numrows > 0 ) {
		$aListaProcesso = db_utils::getColectionByRecord($rsProcessos,false,false,true);
	} else {
		$sMsgErro = 'Nenhum registro encontrado!';
		$lErro    = true;
	}
	
  $aRetorno = array("lErro"          =>$lErro,
	                  "sMsg"           =>$sMsgErro,
                    "aListaProcessos"=>$aListaProcesso);

  echo $oJson->encode($aRetorno);
  
  
  
} else if ( $oPost->sMethod == 'consultaDespachos') {

	$aListaDespachos  = array();
  $sSqlDespachos    = $clProcTransferProc->sql_query_andam(null,$oPost->iCodProcesso,"*","p62_codtran,p61_dtandam,p61_hora");
  
  $rsDespachos      = $clProcTransferProc->sql_record($sSqlDespachos);
  $iLinhasDespachos = $clProcTransferProc->numrows;
  
  if ( $clProcTransferProc->numrows > 0 ) {
  	
    for ( $iInd=0; $iInd < $iLinhasDespachos; $iInd++ ) {

     	$oDespacho = db_utils::fieldsMemory($rsDespachos,$iInd);
     	
     	$oRetornoDespacho = new stdClass(); 
    	$oRetornoDespacho->data       = $oDespacho->p61_dtandam;
    	$oRetornoDespacho->hora       = $oDespacho->p61_hora;
    	$oRetornoDespacho->descrdepto = urlencode($oDespacho->descrdepto);
    	$oRetornoDespacho->nome       = urlencode($oDespacho->login);
    	$aDespacho                    = db_strings::quebraLinha($oDespacho->p61_despacho,70);
    	$oRetornoDespacho->despacho   = urlencode(implode("<br>",$aDespacho)); 
    	$aListaDespachos[]            = $oRetornoDespacho;

    	$sSqlDespachosInterno    = $clProcAndamInt->sql_query(null,"*","p78_sequencial","p78_codandam = {$oDespacho->p61_codandam}");
     	$rsDespachosInternos     = $clProcAndamInt->sql_record($sSqlDespachosInterno);
      $iLinhasDespachosInterno = $clProcAndamInt->numrows;
      
      for ( $iIndInt=0; $iIndInt < $iLinhasDespachosInterno; $iIndInt++ ) {
      	
        $oDespachoInterno = db_utils::fieldsMemory($rsDespachosInternos,$iIndInt);
	      $oRetornoDespacho = new stdClass(); 
	      $oRetornoDespacho->data       = $oDespachoInterno->p78_data;
	      $oRetornoDespacho->hora       = $oDespachoInterno->p78_hora;
	      $oRetornoDespacho->descrdepto = $oDespachoInterno->descrdepto;
	      $oRetornoDespacho->nome       = $oDespachoInterno->login;
	      $oRetornoDespacho->despacho   = $oDespachoInterno->p78_despacho;
	      $aListaDespachos[]            = $oRetornoDespacho;
	      
      }
    }
    
  } else {
    $sMsgErro = 'Nenhum registro encontrado!';
    $lErro    = true;
  }
  
  $aRetorno = array("lErro"          =>$lErro,
                    "sMsg"           =>$sMsgErro,
                    "aListaDespachos"=>$aListaDespachos);

  echo $oJson->encode($aRetorno);
  
  
  
  
} else if ( $oPost->sMethod == 'incluirDespacho') {
  
	
  $oProcesso = $oJson->decode(str_replace("\\","",$oPost->oProcesso));
  $iCodTran  = '';
  
	db_inicio_transacao();
	
  try {
    $oProcessoOuvidoria->incluirDespachoInterno($oProcesso->iCodProc,
	                                              utf8_decode($oPost->sDespacho),
	                                              db_getsession('DB_id_usuario'),
	                                              db_getsession('DB_coddepto'));
	} catch (Exception $eException) {
	  $lErro    = true;
	  $sMsgErro = $eException->getMessage();
	}
	  
	if ( !$lErro ) {
	  $sMsgErro = 'Despacho concluído com sucesso!';
	}  	
	
  if ( $oPost->sTipo == 't' ) {
  	
  	try {
	  	$iCodTran = $oProcessoOuvidoria->incluirTransferencia($oProcesso->iCodProc,
	  	                                                      $oPost->iCodDeptoRec,
	  	                                                      $oPost->iUsuarioRec,
							  	                                          db_getsession('DB_id_usuario'),
							  	                                          db_getsession('DB_coddepto'));
  	} catch (Exception $eException) {
      $lErro    = true;
      $sMsgErro = $eException->getMessage();
  	}
  	
  	
  	if ( $oPost->lNovoDepto == 'true') {
  		
	    try {
	      
	    	$oProcessoOuvidoria->incluiNovoDeptoProrrogacao($oProcesso->iCodProc,
				                                                $oPost->iCodDeptoRec,
				                                                utf8_decode($oProcesso->sMotivo),
				                                                '',
				                                                $oProcesso->iDias,
				                                                ($oProcesso->lSegue=='true'?true:false));
	    	
	    } catch (Exception $eException) {
	      $lErro    = true;
	      $sMsgErro = $eException->getMessage();
	    }
	    
  	}
  	
    
    if ( !$lErro ) {
      $sMsgErro = 'Transferencia feita com sucesso!';
    }       
    
  }
   	
  db_fim_transacao($lErro);
		
	
  if ( !$lErro ) {
    $aRetorno = array("lErro"   =>false,
                      "sMsg"    =>urlencode($sMsgErro),
                      "iCodTran"=>$iCodTran);
  } else {
    $aRetorno = array("lErro"   =>true,
                      "sMsg"    =>urlencode($sMsgErro));
  }
  

  echo $oJson->encode($aRetorno);
  
} if ( $oPost->sMethod == 'validaDepto' ) {
	

	$iOrdemProrrogacao = $oProcessoOuvidoria->getPosicaoAtualProrrogacao($oPost->iCodProcesso);
  	
  $sWherePrazoPrevisto  = "     ov15_protprocesso = {$oPost->iCodProcesso}  ";
  $sWherePrazoPrevisto .= " and ov15_ativo is true                          ";
  $sSqlPrazoPrevisto    = $clProcessoOuvidoriaProrrogacao->sql_query_file(null,"*","ov15_dtfim",$sWherePrazoPrevisto);
  $rsPrazoPrevisto      = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlPrazoPrevisto);
  $iLinhasPrazoPrevisto = $clProcessoOuvidoriaProrrogacao->numrows;
  $lTemDepto            = false;
  $lDiferente           = false;
    
  if ( $iLinhasPrazoPrevisto > 0 ) {
    for ( $iInd=0; $iInd < $iLinhasPrazoPrevisto; $iInd++ ) {
      
    	$oPrazoPrevisto = db_utils::fieldsMemory($rsPrazoPrevisto,$iInd);

    	if ( $lDiferente ) {
	      if ( $oPrazoPrevisto->ov15_coddepto == $oPost->iCodDeptoRec ) {
	        $lTemDepto = true;          
		    }
    	}
    	
    	if ( ($iInd+1) == ($iOrdemProrrogacao+1) ) {
	      if ( $oPrazoPrevisto->ov15_coddepto != $oPost->iCodDeptoRec ) {
	        $lDiferente = true;                     
	      }
    	}
    		
    }
  }

  $aRetorno = array("lTemDepto" =>$lTemDepto,
                    "lDiferenca"=>$lDiferente);

  echo $oJson->encode($aRetorno);	
	
}
  
?>