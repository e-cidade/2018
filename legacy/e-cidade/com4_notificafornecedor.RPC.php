<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("libs/smtp.class.php");
require_once("libs/db_libdocumento.php");
require_once("dbforms/db_funcoes.php");

require_once("model/CgmBase.model.php");
require_once("model/fornecedor.model.php");
require_once("model/CgmFactory.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass(); 
$oRetorno->iStatus = 1;

$sMessage     = "";
$aDebitos     = array();
$iInstituicao = db_getsession('DB_instit');
$iAnoUsu      = db_getsession('DB_anousu');
$oDaoPcParam  = db_utils::getDao('pcparam');

/**
 * Verifica os parametros de configuraчуo padrуo do modulo
 */
$sCampos      = "pc30_fornecdeb, pc30_diasdebitosvencidos, pc30_notificaemail, "; 
$sCampos     .= "pc30_notificacarta, pc30_permitirgerarnotifdebitos            ";
$sSqlPcParam  = $oDaoPcParam->sql_query_file($iInstituicao, $sCampos, null, '');
$rsSqlPcParam = $oDaoPcParam->sql_record($sSqlPcParam);
        
$oPcPram      = db_utils::fieldsMemory($rsSqlPcParam, 0);

switch ($oParam->sExecucao) {
	
  case "processaNotificacao":

	    try {
	
	    	$oRetorno->lFormaNotifEmail = false;
        $oRetorno->lFormaNotifCarta = false;
	      if (isset($oPcPram->pc30_fornecdeb) && !empty($oPcPram->pc30_fornecdeb)) {
	      	
          db_inicio_transacao(true);
        
          $oFornecedor                       = new fornecedor($oParam->iNumCgm);
	        $aDebitos                          = $oFornecedor->getCgm()->getDebitosEmAberto($oPcPram->pc30_diasdebitosvencidos);	        
	        $iCodigoNotificaBloqueioFornecedor = $oFornecedor->notificar($oParam->lGerarNotificacaoDebito, 
	                                                                     $oParam->iOrigem, 
	                                                                     $oParam->iCodigoNotificaBloqueioFornecedor, 
	                                                                     $aDebitos);
	        /**
	         * Envia um e-mail de notificaчуo para o fornecedor
	         */
	        if ($oParam->lFormaNotifEmail) {
	
	          require_once("libs/db_conn.php");
	
	          $sCampos  = "pc86_sequencial, pc86_data, pc86_hora,         ";
	          $sCampos .= "notificacaonotificafornecedor.pc87_notificacao,";
	          $sCampos .= "cgm.z01_numcgm, cgm.z01_nome, cgm.z01_ender,   "; 
	          $sCampos .= "cgm.z01_numero, cgm.z01_compl, cgm.z01_bairro, ";
	          $sCampos .= "cgm.z01_munic,  cgm.z01_cep, cgm.z01_cgccpf,   ";
	          $sCampos .= "cgm.z01_email as emaildestinatario,            ";
	          $sCampos .= "db_config.munic as municipio,                  ";
	          $sCampos .= "db_config.email as emailremetente,             ";
	          $sCampos .= "coddepto, descrdepto                           ";
	          $sWhere   = "pc86_sequencial = {$iCodigoNotificaBloqueioFornecedor}";
	          
	          /**
	           * Verifica as notificaчѕes de debito ja processadas para o fornecedor
	           */
            $oDaoNotificaBloqueioFornecedor  = db_utils::getDao("notificabloqueiofornecedor");
            $sSqlNotificaBloqueioFornecedor  = $oDaoNotificaBloqueioFornecedor->sql_debitos_notificados(null, $sCampos, null, $sWhere);                                                                     
	          $rsSqlNotificaBloqueioFornecedor = $oDaoNotificaBloqueioFornecedor->sql_record($sSqlNotificaBloqueioFornecedor); 
	          if ($oDaoNotificaBloqueioFornecedor->numrows > 0) {
	          	
	            $oNotificaBloqueioFornecedor = db_utils::fieldsMemory($rsSqlNotificaBloqueioFornecedor, 0);
	            
	            if (empty($oNotificaBloqueioFornecedor->emailremetente)) {
	            	
	            	$sMensagem  = "E-mail do remetente nуo encontrado!";
	            	$sMensagem .= " \nVerifique parametros de configuraчуo.";
	            	throw new Exception($sMensagem);
	            }
	            
	            if (empty($oNotificaBloqueioFornecedor->emaildestinatario)) {
	            	
	            	$sMensagem  = "E-mail do destinatсrio nуo encontrado!";
	            	$sMensagem .= " \nVerifique o CGM: {$oNotificaBloqueioFornecedor->z01_numcgm}.";
	            	throw new Exception($sMensagem);
	            }
	            
	            $sEmailRemetente     = "{$oNotificaBloqueioFornecedor->emailremetente}";
	            $sEmailDestinatario  = "{$oNotificaBloqueioFornecedor->emaildestinatario}";
	            $sAssunto            = "Notificaчуo de Dщbitos Fornecedor";
	  
	            /**
	             * Variaveis disponiveis na libdocumento
	             */
	            $oLibDocumento                  = new libdocumento(5003);          
	            $oLibDocumento->municipio       = $oNotificaBloqueioFornecedor->municipio;
	            $oLibDocumento->pc86_sequencial = $oNotificaBloqueioFornecedor->pc86_sequencial;
	            $oLibDocumento->z01_numcgm      = $oNotificaBloqueioFornecedor->z01_numcgm;
	            $oLibDocumento->z01_nome        = $oNotificaBloqueioFornecedor->z01_nome;
	            $oLibDocumento->z01_ender       = $oNotificaBloqueioFornecedor->z01_ender;
	            $oLibDocumento->z01_numero      = $oNotificaBloqueioFornecedor->z01_numero;
	            $oLibDocumento->z01_compl       = $oNotificaBloqueioFornecedor->z01_compl;
	            $oLibDocumento->z01_bairro      = $oNotificaBloqueioFornecedor->z01_bairro;
	            $oLibDocumento->z01_cep         = $oNotificaBloqueioFornecedor->z01_cep;
	            $oLibDocumento->z01_cgccpf      = $oNotificaBloqueioFornecedor->z01_cgccpf;
	            $oLibDocumento->pc86_data       = db_formatar($oNotificaBloqueioFornecedor->pc86_data, 'd');
	            $oLibDocumento->pc86_hora       = $oNotificaBloqueioFornecedor->pc86_hora;
	            $oLibDocumento->coddepto        = $oNotificaBloqueioFornecedor->coddepto;
	            $oLibDocumento->descrdepto      = $oNotificaBloqueioFornecedor->descrdepto;
	            
	            $lPossuiValor = false;
	            if (!empty($oNotificaBloqueioFornecedor->pc87_notificacao)) {
	            	
		            /**
	               * Calcula o valor total dos debitos do fornecedor
	               */
	              $oDaoNotidebitosReg  = db_utils::getDao("notidebitosreg");
	              $sCampos             = "round(sum(k43_vlrcor), 2) as totalvlrcor,  ";
	              $sCampos            .= "round(sum(k43_vlrjur), 2) as totalvlrjur,  ";
	              $sCampos            .= "round(sum(k43_vlrmul), 2) as totalvlrmul,  ";
	              $sCampos            .= "round(sum(k43_vlrdes), 2) as totalvlrdes   ";
	              $sWhere              = "notificacao.k50_notifica = {$oNotificaBloqueioFornecedor->pc87_notificacao}";
	              $sSqlNotidebitosReg  = $oDaoNotidebitosReg->sql_query(null, $sCampos, null, $sWhere);
	              $rsSqlNotidebitosReg = $oDaoNotidebitosReg->sql_record($sSqlNotidebitosReg); 
	              if ($oDaoNotidebitosReg->numrows > 0) {
	                
	                $oNotidebitosReg = db_utils::fieldsMemory($rsSqlNotidebitosReg, 0);
	                
	                /**
	                 * Variaveis do total de debitos disponiveis na libdocumento
	                 */
	                if (!empty($oNotidebitosReg->totalvlrcor)) {
	                	$lPossuiValor = true;
                  }
                  
	                if (!empty($oNotidebitosReg->totalvlrjur)) {
	                	$lPossuiValor = true;
                  }
                  
	                if (!empty($oNotidebitosReg->totalvlrmul)) {
	                	$lPossuiValor = true;
                  }
                  
	                if (!empty($oNotidebitosReg->totalvlrdes)) {
	                	$lPossuiValor = true;
                  }
                  
                  $oLibDocumento->totalvlrcor = db_formatar($oNotidebitosReg->totalvlrcor, 'f');
                  $oLibDocumento->totalvlrjur = db_formatar($oNotidebitosReg->totalvlrjur, 'f');
                  $oLibDocumento->totalvlrmul = db_formatar($oNotidebitosReg->totalvlrmul, 'f');
                  $oLibDocumento->totalvlrdes = db_formatar($oNotidebitosReg->totalvlrdes, 'f');
	              }
	            }
	  
	            $sCorpoEmail = '';
	            $aParagrafos = $oLibDocumento->getDocParagrafos();
	            
	            foreach ($aParagrafos as $oParagrafo) {
	              
	              if (trim($oParagrafo->oParag->db02_descr) == 'LISTA_DEBITOS' 
	                  && $oPcPram->pc30_permitirgerarnotifdebitos == 'f' 
	                  && !$lPossuiValor) {
	                continue;
	              }
	              
	              $sCorpoEmail .= nl2br($oLibDocumento->replaceText($oParagrafo->oParag->db02_texto));
	            }
	            
	            $oSmtp       = new Smtp();
	            $oSmtp->html = true;
	            
	            /**
	             * Envia o e-mail de notificaчуo
	             */
	            $lEnvio = $oSmtp->Send($sEmailDestinatario, $sEmailRemetente, $sAssunto, $sCorpoEmail);
	            if (!$lEnvio) {
	              
	              $sMessage  = "Erro ao enviar e-mail para {$sEmailDestinatario}.";
	              $sMessage .= "\nVerificar configuraчѕes de e-mail.";
	              throw new Exception($sMessage);
	            }
	  
	            $sMessage = "Usuсrio: \n\n E-mail de notificaчуo enviado com sucesso.";
	            $oRetorno->lFormaNotifEmail = true;
	          }
	        }
	        
	        if ($oParam->lFormaNotifCarta) {
	          $oRetorno->lFormaNotifCarta = true;
	        }
	        
	        $oRetorno->iCodigoNotificaBloqueioFornecedor = $iCodigoNotificaBloqueioFornecedor;
	        $oRetorno->iParamFornecDeb                   = $oPcPram->pc30_fornecdeb;
	        $oRetorno->lGerarNotificacaoDebito           = $oParam->lGerarNotificacaoDebito;
	        $oRetorno->sMessage                          = urlencode($sMessage);
	        
	        db_fim_transacao(false);
	      }
	    } catch (Exception $eErro) {
	      
	      $oRetorno->iStatus          = 2;
	      $oRetorno->lFormaNotifEmail = false;
	      $oRetorno->lFormaNotifCarta = false;
	      $oRetorno->sMessage         = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
	      db_fim_transacao(true);
	    }
	    break;
	    
  case "debitosEmAberto":
  	
  	  /**
  	   * Verifica os debitos em aberto do fornecedor
  	   */  
      $oFornecedor = new fornecedor($oParam->iNumCgm);
      $aDebitos    = $oFornecedor->getCgm()->getDebitosEmAberto($oPcPram->pc30_diasdebitosvencidos);

      $oRetorno->iNumCgm                  = $oParam->iNumCgm;
      $oRetorno->lParamGerarNotifDebitos  = ($oPcPram->pc30_permitirgerarnotifdebitos=='t'?true:false);
      $oRetorno->iParamFornecDeb          = $oPcPram->pc30_fornecdeb;
      $oRetorno->iDebitosEmAberto         = count($aDebitos);
      
      /**
       * 
       * Verifica o tipo de liberaчуo que o servidor estс fazendo e verifica se respectivo CGM informado
       * estс na tabela de Liberaчуo de Fornecedor (liberafornecedor).
       * 
       * Caso esteja, transforma o dщbito do respectivo em 0 para permitir a geraчуo de empenho, solicitacao, processo.
       * 
       */
      if ( $oParam->sLiberacao == "A" ) {
        
        $oFornecedor->verificaBloqueioAutorizacaoEmpenho(null);
        if ( $oFornecedor->getStatusBloqueio() == 1 ) {
          $oRetorno->iDebitosEmAberto = 0;
        }
      } else if ( $oParam->sLiberacao == "S" ) {
        
        $oFornecedor->verificaBloqueioSolicitacao(null);
        if ( $oFornecedor->getStatusBloqueio() == 1 ) {
          $oRetorno->iDebitosEmAberto = 0;
        }
      } else if ( $oParam->sLiberacao == "P" ) {
        
        $oFornecedor->verificaBloqueioProcessoCompra(null);
        if ( $oFornecedor->getStatusBloqueio() == 1 ) {
          $oRetorno->iDebitosEmAberto = 0;
        }
      }
      
      /**
       * Verifica os parametros de configuraчуo da forma de envio da notificaчуo
       */
      $aFormaNotificacao = array();
      if ($oPcPram->pc30_notificaemail == 't') {
      	$aFormaNotificacao[] = 'Email';
      }
      
      if ($oPcPram->pc30_notificacarta == 't') {
      	$aFormaNotificacao[] = 'Carta';
      }
      
      $oRetorno->aFormaNotificacao = $aFormaNotificacao;
      
      break;
      
  case "verificaEmailFornecedor":
    
      /**
       * Verifica permissѕes de menu do fornecedor
       */
  	  $lPermissaoFornecedorItem1 = db_permissaomenu($iAnoUsu, 4, 1387);
  	  $lPermissaoFornecedorItem2 = db_permissaomenu($iAnoUsu, 4, 8451);  	  
  	  if ($lPermissaoFornecedorItem1 == 'true' && $lPermissaoFornecedorItem2 == 'true') {
        $oRetorno->lPermissaoMenu = true;
  	  } else {
  	  	$oRetorno->lPermissaoMenu = false;
  	  }
  	        
  	  $oRetorno->sEmailFornecedor = '';
  	  
      /**
       * Verifica email do fornecedor
       */
  	  $oCgm     = db_utils::getDao("cgm");
  	  $sSqlCgm  = $oCgm->sql_query_file($oParam->iNumCgm, "cgm.z01_email", null, "");
  	  $rsSqlCgm = $oCgm->sql_record($sSqlCgm);
  	  if ($oCgm->numrows > 0) {
  	  	
  	  	$oCgmFornecedor = db_utils::fieldsMemory($rsSqlCgm, 0);
  	  	$oRetorno->sEmailFornecedor = $oCgmFornecedor->z01_email;
  	  }
      break;
      
  case "salvarEmailFornecedor":
    
      /**
       * Salva email do fornecedor
       */
  	  try {
  	  	
  	  	db_inicio_transacao(true);
  	  	
  	  	$oCgm             = db_utils::getDao("cgm");
  	  	
        $oCgm->z01_numcgm = $oParam->iNumCgm;
        $oCgm->z01_email  = $oParam->sEmailFornecedor;
        $oCgm->alterar($oCgm->z01_numcgm);
        if ($oCgm->erro_status == 0) {
        	throw new Exception($oCgm->erro_msg);
        }
        
        db_fim_transacao(false);
  	  } catch (Exception $eErro) {
  	  	
        $oRetorno->iStatus          = 2;
        $oRetorno->sMessage         = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        db_fim_transacao(true);
  	  }
      break;
}

echo $oJson->encode($oRetorno);
?>