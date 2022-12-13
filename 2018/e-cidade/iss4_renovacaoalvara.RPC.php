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

require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/alvara/MovimentacaoAlvaraFactory.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {
	
	/**
	 * Efetua a renovaчуo do alvarс
	 */
	case "renovarAlvara" :
		
		 db_inicio_transacao();

		 try {

       $oAlvara         = new Alvara($oParam->q120_issalvara);
       $oRenovarAlvara  = $oAlvara->incluirMovimentacao( MovimentacaoAlvara::TIPO_RENOVACAO );
			 $oRenovarAlvara->setValidadeAlvara($oParam->q120_validadealvara);
			 $oRenovarAlvara->setDataMovimentacao($oParam->q120_dtmov);
       $oRenovarAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );

			 /**
			  * Valida se foi passado o codigo do processo
			  */
			 if ($oParam->p58_codproc != "") {
			   $oRenovarAlvara->setCodigoProcesso($oParam->p58_codproc);
			 }

			 $oRenovarAlvara->setObservacao($oParam->q120_obs);

			 foreach ($oParam->aDocumentos as $iDoc) {
         $oRenovarAlvara->getAlvara()->addDocumento($iDoc); 
			 }
			 
       $iRenovacoes                = count($oAlvara->getMovimentacoes(MovimentacaoAlvara::TIPO_RENOVACAO));
       $iRenovacoesCanceladas      = count($oAlvara->getMovimentacoes(MovimentacaoAlvara::TIPO_CANCELAMENTO_RENOVACAO));
			 $iQuantRenovacoesRealizadas = $iRenovacoes - $iRenovacoesCanceladas;

       $oDaoIsstipoalvara                          = db_utils::getDao('isstipoalvara');
       $iAlvara                                    = $oAlvara->getCodigo();
       $sWhereBuscaQuantidadePermitidaDeRenovacoes = " q123_sequencial = {$iAlvara} ";
       $sSqlBuscaQuantidadePermitidaDeRenovacoes   = $oDaoIsstipoalvara->sql_query_tipocomalvaravinculado( null, 
                                                                                                           "q98_quantrenovacao", 
                                                                                                           null,
                                                                                                           $sWhereBuscaQuantidadePermitidaDeRenovacoes );
       $rsBuscaQuantidadePermitidaDeRenovacoes     = $oDaoIsstipoalvara->sql_record($sSqlBuscaQuantidadePermitidaDeRenovacoes);
       $iQuantidadeRenovacoesPermitidas            = db_utils::fieldsMemory($rsBuscaQuantidadePermitidaDeRenovacoes, 0)->q98_quantrenovacao;

       if ( $iQuantRenovacoesRealizadas >= $iQuantidadeRenovacoesPermitidas ) {
         throw new BusinessException("O alvarс jс alcanчou o limite de renovaчѕes permitido.");
       }

			 $oRenovarAlvara->processar();
			 $oRetorno->message = urlencode("Renovaчуo de alvarс efetuada com sucesso.");

			 db_fim_transacao(false);
			 
		 } catch (ErrorException $eErro){

       $oRetorno->status  = 2;
       $oRetorno->message = urlencode($eErro->getMessage());
       db_fim_transacao(true);
     }

  break;
}

echo $oJson->encode($oRetorno);
?>