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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");
require_once('libs/db_libsys.php');
require_once("dbforms/db_funcoes.php");
require_once('std/db_stdClass.php');
require_once("std/DBDate.php");
require_once('dbagata/classes/core/AgataAPI.class');

db_app::import('exceptions.*');
db_app::import('inicial');

$oJson              = new services_json();
$oRetorno           = new stdClass();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

switch ($oParam->sExec) {
	
	case 'getIniciais' :
		
		$oDaoInicial  = db_utils::getDao('inicial');
		
		$iInstituicao = db_getsession('DB_instit');
		
		$iCodigoSituacao            = $oParam->sTipo == 'parcelamento' ? 4 : 8;
		
		$sSqlInicial 		 = $oDaoInicial->sql_queryIniciaisPeticao($iInstituicao, $iCodigoSituacao);
		$rsInicial   		 = $oDaoInicial->sql_record($sSqlInicial);
		
		if ($rsInicial and $oDaoInicial->numrows > 0) {
		
			$oRetorno->aIniciais = db_utils::getCollectionByRecord($rsInicial, false, false, true);
		
		} else {
			
			$oRetorno->iStatus  = 2;
			
			$oRetorno->sMessage = _M('tributario.juridico.jur4_gerapeticoes.nenhum_registro_encontrado');
			 
		}
		
		break;

	case 'salvarPeticoes' :
		
		$iTipoPeticao    = $oParam->sTipo == 'parcelamento' ? 1 : 2;
		
		db_app::import('juridico.Peticao');
		db_app::import('juridico.PeticaoEmissao');
		
		$oPeticaoEmissao = new PeticaoEmissao($iTipoPeticao);
		
		$aIniciasPeticao = explode(',', $oParam->sIniciais);
		
		$lErroBanco      = false;
		
		db_inicio_transacao();
		
		try {
		  
		
			for ($iInicialPeticao = 0; $iInicialPeticao < count($aIniciasPeticao); $iInicialPeticao++) {
				
				$oPeticao 			= new Peticao();
				
				$oPeticao->setInicial      (new inicial($aIniciasPeticao[$iInicialPeticao]));
				$oPeticao->setTipoPeticao  ($iTipoPeticao);
				$oPeticao->setDataPeticao  (new DBDate(date('Y-m-d',db_getsession('DB_datausu'))));
				$oPeticao->setHoraPeticao  (db_hora());
				$oPeticao->setCodigoUsuario(db_getsession('DB_id_usuario'));
				$oPeticao->setTexto				 ('null');
				$oPeticao->salvar();
				$oPeticaoEmissao->adicionarPeticao($oPeticao);
				unset($oPeticao);
				
			}

			$oRetorno->sArquivo = $oPeticaoEmissao->emitir();
			
		} catch (DBException $oException) {
			
			$oRetorno->sMessage = urlEncode($oException->getMessage());
			$oRetorno->iStatus  = 2;
			$lErroBanco         = true;
			
		} catch (BusinessException $oException) {
		  
		  $oRetorno->sMessage = urlEncode($oException->getMessage());
		  $oRetorno->iStatus  = 2;
		  $lErroBanco         = true;
		  
		} catch (ParameterException $oException) {
		  
		  $oRetorno->sMessage = urlEncode($oException->getMessage());
		  $oRetorno->iStatus  = 2;
		  $lErroBanco         = true;
		  
		}

		db_fim_transacao(true);
				
		break;
	
}

echo $oJson->encode($oRetorno);