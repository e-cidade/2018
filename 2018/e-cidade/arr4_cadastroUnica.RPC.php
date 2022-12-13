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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_recibounicageracao_classe.php");

$oDaoReciboUnicaGeracao = new cl_recibounicageracao();
$oJson                  = new services_json();

$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->status       = 1;
$oRetorno->message      = '';

$aDadosRetorno          = array();
try {
	switch ($oParam->exec) {
		
		case "getExercicios":
			
			$sSqlExercicios   = $oDaoReciboUnicaGeracao->sql_query_debitosExercicios($oParam->sTipoPesquisa, $oParam->sChavePesquisa, $oParam->iCadTipoDebito);
			$rsExercicios     = $oDaoReciboUnicaGeracao->sql_record($sSqlExercicios);
			
			if($rsExercicios &&  pg_num_rows($rsExercicios) > 0){
				$aDadosRetorno  = db_utils::getColectionByRecord($rsExercicios, false, false, true);
			}
			
			
		break;
		case "getTiposDebito":
				
			$sSqlTiposDebitos = $oDaoReciboUnicaGeracao->sql_query_pesquisa($oParam->sTipoPesquisa, $oParam->sChavePesquisa);
			$rsTiposDebitos   = $oDaoReciboUnicaGeracao->sql_record($sSqlTiposDebitos);
				
			if($rsTiposDebitos &&  pg_num_rows($rsTiposDebitos) > 0){
				$aDadosRetorno  = db_utils::getColectionByRecord($rsTiposDebitos, false, false, true);
			}
				
				
			break;
		case "getDebitos":
		  
			$sSqlDebitos   = $oDaoReciboUnicaGeracao->sql_query_debitosExercicios($oParam->sTipoPesquisa, $oParam->sChavePesquisa, $oParam->iCadTipoDebito,false,$oParam->iExercicio);
			$rsDebitos     = $oDaoReciboUnicaGeracao->sql_record($sSqlDebitos);
				
			if($rsDebitos &&  pg_num_rows($rsDebitos) > 0){
			  $aDadosRetorno  = db_utils::getColectionByRecord($rsDebitos, false, false, true);
			}
					
		break;
		default:
		  throw new ErrorException("Nenhuma Op��o Definida");
	  break;
	}

} catch (ErrorException $eErro){
	$oRetorno->status  = 2;
	$oRetorno->msg     = urlencode($eErro->getMessage());
}
$oRetorno->aDados = $aDadosRetorno;
echo $oJson->encode($oRetorno);