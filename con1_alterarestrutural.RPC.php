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
 
//con1_alterarestrutural.RPC.php
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
try {
	
  switch ($oParam->exec) {
  	
  	case "alterarEstrutural" :
  		
  		$iAnoUsu                 = db_getSession("DB_anousu");
  		$iInstit                 = db_getsession("DB_instit");
  		$sNovoEstrutural         = $oParam->sEstruturalNovo; // novo estrutural
  		$iCaracterNovoEstrutural = strlen($sNovoEstrutural); // total de caracter do novo estrutural
  		$aContasAlterar          = $oParam->aContasAlterar; // contas a serem alteradas
  		
  		db_inicio_transacao();
  		
  		foreach ($aContasAlterar as $iDadoAlterar => $oDadoAlterar) {
  			
	  		$oDaoConplanoOrcamento   = db_utils::getDao("conplanoorcamento");
	  		$oDaoOrcFontes           = db_utils::getDao("orcfontes");
	  		$oDaoOrcElemento         = db_utils::getDao("orcelemento");
  			
	  		//pegamos a parte que sera substituida, baseada no total de caracter do novo estrutural
  		  $sParteAlterada = substr($oDadoAlterar->c60_estrut, 0, $iCaracterNovoEstrutural);
  		  //montamos o novo estrutural com 15 digitos, os primeiros serao do NOVO e o Restante dele mesmo
  			$sAlterarPara   = $sNovoEstrutural . substr($oDadoAlterar->c60_estrut, $iCaracterNovoEstrutural, 15);
  			
  			$sConplanoOrcFontes = $sAlterarPara;
  			// cortamos para 13 digitos para a orcelemento
  			$sOrcElemento       = substr($sAlterarPara, 0, 13);
  			
  			$oDaoConplanoOrcamento->c60_codcon = $oDadoAlterar->c60_codcon;
  			$oDaoConplanoOrcamento->c60_estrut = $sConplanoOrcFontes;
  			
  			$oDaoOrcFontes->o57_codfon = $oDadoAlterar->c60_codcon;
  			$oDaoOrcFontes->o57_fonte  = $sConplanoOrcFontes;
  			
  			$oDaoOrcElemento->o56_codele   = $oDadoAlterar->c60_codcon;
  			$oDaoOrcElemento->o56_elemento = $sOrcElemento;
  			
  			
  			$oDaoConplanoOrcamento->alterar($oDadoAlterar->c60_codcon, null);
  			$oDaoOrcFontes        ->alterar($oDadoAlterar->c60_codcon, null);
  			$oDaoOrcElemento      ->alterar($oDadoAlterar->c60_codcon, null);
  			
  			
  			if ($oDaoConplanoOrcamento->erro_status == '0') {
  				throw new DBException($oDaoConplanoOrcamento->erro_msg);
  			}
  			if ($oDaoOrcFontes->erro_status == '0') {
  				throw new DBException($oDaoOrcFontes->erro_msg);
  			}
  			if ($oDaoOrcElemento->erro_status == '0') {
  				throw new DBException($oDaoConplanoOrcamento->erro_msg);
  			}
  			
  		}
  		
  		db_fim_transacao(false);
  		$oRetorno->sMessage   = _M("financeiro.contabilidade.con1_alterarestrutural001.alteracao_realizada");//"Alteração Realizada com sucesso";
  		
  	break;	
    
    case "getDadosConta":
    	
    	
    	$oDaoConplanoOrcamento = db_utils::getDao("conplanoorcamento");
    	$iAnoUsu               = db_getSession("DB_anousu");
    	$iInstit               = db_getsession("DB_instit");
    	$sEstrutural           = $oParam->iEstrutural;
    	
    	$sCampos  = "c60_codcon, "; 
    	$sCampos .= "c61_reduz , "; 
    	$sCampos .= "c60_anousu, "; 
    	$sCampos .= "c60_estrut, "; 
    	$sCampos .= "c60_descr   ";
    	
    	$sWhere  = "c60_anousu = {$iAnoUsu}         and ";
    	$sWhere .= "c60_estrut like '$sEstrutural%' and ";
    	$sWhere .= "(c61_instit is null or c61_instit = {$iInstit})";
    	
    	
    	$sSql     = $oDaoConplanoOrcamento->sql_query_geral(null,null, $sCampos ,"c60_estrut", $sWhere);
    	$rsContas = $oDaoConplanoOrcamento->sql_record($sSql);
    	
    	if ($oDaoConplanoOrcamento->numrows > 0) {
    		
        for ($iContas = 0; $iContas < $oDaoConplanoOrcamento->numrows; $iContas++) {

        	$oDadosContas  = db_utils::fieldsMemory($rsContas, $iContas);
        	
        	$oDadosRetorno = new stdClass();
        	$oDadosRetorno->iCodigo     = $oDadosContas->c60_codcon;
        	$oDadosRetorno->iReduzido   = $oDadosContas->c61_reduz;
        	$oDadosRetorno->sEstrutural = $oDadosContas->c60_estrut;
        	$oDadosRetorno->sDescricao  = urlencode($oDadosContas->c60_descr);
        	
        	$aDadosRetorno[] = $oDadosRetorno;
        	
        }
    		
    	}
    	$oRetorno->aDadosRetorno = $aDadosRetorno;
    	
    break;
    
    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
    
  }
  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (DBException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (ParameterException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
  
}catch (BusinessException $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
  echo $oJson->encode($oRetorno);
}

?>