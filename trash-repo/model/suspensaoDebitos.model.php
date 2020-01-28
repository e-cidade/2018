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


class suspensaoDebitos {
  
  private $iCodSuspensao = null;	
	
	
  /**
   * 
   */
  function __construct() {
  	
  }
  
  function incluirSuspensao($iProcesso="",$iSituacao=1,$sObs="",$dtData="",$sHora="",$iInstit="",$iIdUsuario="") {
	 
  	if(trim($iProcesso) == ""){
  	  throw new Exception("Suspens�o cancelada, processo n�o informado!");
  	}
    if(trim($iInstit) == ""){
  	  $iInstit = db_getsession('DB_instit'); 
  	}
    if(trim($iIdUsuario) == ""){
  	  $iIdUsuario = db_getsession('DB_id_usuario');
  	}
   	if(trim($dtData) == ""){
  	  $dtData = date("Y-m-d",db_getsession('DB_datausu'));
  	}
    if(trim($sHora) == ""){
      $sHora = db_hora();	
  	}
  	
    $oDaoSuspensao = db_utils::getDao("suspensao");
    
    $oDaoSuspensao->ar18_procjur   = $iProcesso; 
    $oDaoSuspensao->ar18_instit    = $iInstit;
  	$oDaoSuspensao->ar18_usuario   = $iIdUsuario;
  	$oDaoSuspensao->ar18_data      = $dtData;
  	$oDaoSuspensao->ar18_hora      = $sHora;
  	$oDaoSuspensao->ar18_obs       = $sObs;
  	$oDaoSuspensao->ar18_situacao  = $iSituacao;
  	
  	$oDaoSuspensao->incluir(null);
  
    if ( $oDaoSuspensao->erro_status == 0 ) {
  	  throw new Exception($oDaoSuspensao->erro_msg);
  	}
  	
  	$this->iCodSuspensao = $oDaoSuspensao->ar18_sequencial;
    
  }  
  
  function reativarDebito($iCodSuspensao,$lGeraArrehist=true){
	  	
  	if(trim($iCodSuspensao) == ""){
  	  throw new Exception(" Opera��o cancelada, c�digo da suspens�o inv�lido!");
  	}
  	
  	$oDaoSuspensao = db_utils::getDao("suspensao");
  	$oDaoArresusp  = db_utils::getDao("arresusp");
  	$oDaoArrecad   = db_utils::getDao("arrecad");
  	$oDaoArrehist  = db_utils::getDao("arrehist");
  	
  	$rsDadosArresusp = $oDaoArresusp->sql_record($oDaoArresusp->sql_query(null,"*",null," k00_suspensao = {$iCodSuspensao}"));
  	$iLinhasArresusp = $oDaoArresusp->numrows;
  	
  	if ( $iLinhasArresusp > 0 ) {

  	  for ($i=0; $i < $iLinhasArresusp; $i++) {
  	  	
  	  	$oArresusp = db_utils::fieldsMemory($rsDadosArresusp,$i); 
  	  	
		$oDaoArrecad->k00_numpre = $oArresusp->k00_numpre; 
		$oDaoArrecad->k00_numpar = $oArresusp->k00_numpar;  
		$oDaoArrecad->k00_numcgm = $oArresusp->k00_numcgm;  
		$oDaoArrecad->k00_dtoper = $oArresusp->k00_dtoper;    
		$oDaoArrecad->k00_receit = $oArresusp->k00_receit;  
		$oDaoArrecad->k00_hist	 = $oArresusp->k00_hist;      
		$oDaoArrecad->k00_valor  = $oArresusp->k00_valor;    
		$oDaoArrecad->k00_dtvenc = $oArresusp->k00_dtvenc;    
		$oDaoArrecad->k00_numtot = $oArresusp->k00_numtot;  
		$oDaoArrecad->k00_numdig = $oArresusp->k00_numdig;  
		$oDaoArrecad->k00_tipo	 = $oArresusp->k00_tipo;     
		$oDaoArrecad->k00_tipojm = $oArresusp->k00_tipojm;  	  	 
  	  	
		$oDaoArrecad->incluir();
  	  	
		if ( $oDaoArrecad->erro_status == 0 ) {
	  	  throw new Exception($oDaoArrecad->erro_msg);			
		}
		
		if ( $lGeraArrehist ) {
			
		  $oDaoArrehist->k00_numpre     = $oArresusp->k00_numpre;  
  	  	  $oDaoArrehist->k00_numpar     = $oArresusp->k00_numpar;
  	  	  $oDaoArrehist->k00_hist       = $oArresusp->k00_hist;
  	  	  $oDaoArrehist->k00_dtoper	    = date("Y-m-d",db_getsession('DB_datausu'));
  	  	  $oDaoArrehist->k00_hora	 	= db_hora();
  	  	  $oDaoArrehist->k00_id_usuario = db_getsession('DB_id_usuario');
  	  	  $oDaoArrehist->k00_histtxt    = "D�BITO REATIVADO ( Suspens�o:{$iCodSuspensao} )";
  	  	  $oDaoArrehist->k00_limithist  = null;
  	  	
  	  	  $oDaoArrehist->incluir(null);
  	  	
  	      if ( $oDaoArrehist->erro_status == 0 ) {
  	  	    throw new Exception($oDaoArrehist->erro_msg);  	  		
  	      }
  	      
		}
  	  }
  	}
  	
  }
  
  function cancelarDebitoSuspensao($iCodSuspensao){

  	if(trim($iCodSuspensao) == ""){
  	  throw new Exception(" Opera��o cancelada, c�digo da suspens�o inv�lido!");
  	}

  	require_once("libs/db_sql.php");
  	require_once("model/cancelamentoDebitos.model.php");
  	
  	$oDaoArresusp        	= db_utils::getDao("arresusp");
  	$oDaoSuspensaoFinaliza 	= db_utils::getDao("suspensaofinaliza");
  	$oDaoCancDebitosSusp   	= db_utils::getDao("cancdebitossusp");
  	$oCancelamentoDebitos   = new cancelamentoDebitos();
  	
  	
  	$rsDadosArresusp = $oDaoArresusp->sql_record($oDaoArresusp->sql_query(null,"*",null," k00_suspensao = {$iCodSuspensao}"));
  	$iLinhasArresusp = $oDaoArresusp->numrows;
  	
  	if ( $iLinhasArresusp > 0 ) {
	  for ($iInd=0; $iInd < $iLinhasArresusp; $iInd++) {
	  	$oArresusp = db_utils::fieldsMemory($rsDadosArresusp, $iInd);	
  		$aDebitos[$iInd]['Numpre' ] = $oArresusp->k00_numpre; 
  		$aDebitos[$iInd]['Numpar' ] = $oArresusp->k00_numpar;
  		$aDebitos[$iInd]['Receita'] = $oArresusp->k00_receit;
	  }	
  	}
  	
  	try {
  	  $this->reativarDebito($iCodSuspensao,false);
  	} catch (Exception  $eExeption) {
  	  throw new Exception($eExeption->getMessage());	
  	}

  	try {
  	  $oCancelamentoDebitos->setArreHistTXT("CANCELAMENTO DE D�BITO ( Suspens�o:{$iCodSuspensao} )");	
  	  $oCancelamentoDebitos->geraCancelamento($aDebitos);
  	} catch (Exception $eExeption){
  	  throw new Exception($eExeption->getMessage());	  
  	}
  	
	$rsSuspensaoFinaliza = $oDaoSuspensaoFinaliza->sql_record($oDaoSuspensaoFinaliza->sql_query_file(null,"*",null," ar19_suspensao = {$iCodSuspensao} "));	
  	$oSuspensaoFinaliza  = db_utils::fieldsMemory($rsSuspensaoFinaliza,0);
	
  	$oDaoCancDebitosSusp->ar21_suspensaofinaliza = $oSuspensaoFinaliza->ar19_sequencial;
  	$oDaoCancDebitosSusp->ar21_cancdebitos 		 = $oCancelamentoDebitos->getCodCancDebitos();
  	$oDaoCancDebitosSusp->incluir(null);

  	if ( $oDaoCancDebitosSusp->erro_status == 0 ) {
  	  throw new Exception($oDaoCancDebitosSusp->erro_msg);  	  		
  	}  	
  	
  	
  }
  
  
  function suspendeDebito($aDadosDebitos=null){

    if(empty($aDadosDebitos)){
  	  throw new Exception("Suspens�o cancelada, Dados do d�bito n�o informado!");
  	}  	
    if(!db_utils::inTransaction()){
  	  throw new Exception("N�o foi poss�vel encontrar uma transa��o v�lida.\nOpera��o cancelada");
  	}
  	
	require_once("libs/db_sql.php");
  	
  	$oDaoArresusp = db_utils::getDao("arresusp");
  	$oDaoArrehist = db_utils::getDao("arrehist");
  	$oDaoArrecad  = db_utils::getDao("arrecad");
	$sCamposWhere = "";
	$sOperador	  = "";
  	$aDebitos     = array();
  	
  	foreach ( $aDadosDebitos as $oDadosDebito ) {
	  $aDebitos[$oDadosDebito->iNumpre][$oDadosDebito->iNumpar][] = $oDadosDebito->iReceit;
  	}

    foreach ( $aDebitos as $iNumpre => $aNumpar){
  	  $sCamposWhere   .= " {$sOperador} ( arrecad.k00_numpre = {$iNumpre} ";
  	  $sOperadorNumpar = " and ( ";
  	  foreach ( $aNumpar as $iNumpar => $aReceit ){
  	    $sCamposWhere    .= "  {$sOperadorNumpar} ( arrecad.k00_numpar = {$iNumpar} ";
  	    if ( $aReceit[0] != 0 ) {
  	      $sOperadorReceit = "and ( ";
  	      foreach ( $aReceit as $iReceit ){
            $sCamposWhere   .= " {$sOperadorReceit} arrecad.k00_receit = {$iReceit} ";
  	        $sOperadorReceit = " or ";
  	      }
  	      $sCamposWhere   .= " ) ";
  	    }
  	    $sCamposWhere   .= " ) ";
  	    $sOperadorNumpar = " or ";  	    
  	  }
  	  $sCamposWhere .= " )) ";
  	  $sOperador    = " or ";
    }      
    
    
  	$sSqlVerificaDebito  = " select arrecad.*,	 												  	   ";
  	$sSqlVerificaDebito .= " 	    case 															   ";
  	$sSqlVerificaDebito .= " 		  when certdiv.v14_certid is not null then certdiv.v14_certid 	   ";
  	$sSqlVerificaDebito .= " 		  	else case													   ";
  	$sSqlVerificaDebito .= "		      when certter.v14_certid is not null then certter.v14_certid  ";  
  	$sSqlVerificaDebito .= "	     	  else null					  								   ";
  	$sSqlVerificaDebito .= "   			end															   ";
  	$sSqlVerificaDebito .= "  		end as v14_certid												   ";  
	$sSqlVerificaDebito .= "   from arrecad   														   ";
	$sSqlVerificaDebito .= " 	    left join divida   		on divida.v01_numpre  = arrecad.k00_numpre ";
	$sSqlVerificaDebito .= " 	    				 	   and divida.v01_numpar  = arrecad.k00_numpar ";
	$sSqlVerificaDebito .= "   	    left join certdiv  	 	on divida.v01_coddiv  = certdiv.v14_coddiv ";
	$sSqlVerificaDebito .= " 	    left join termo    	 	on termo.v07_numpre   = arrecad.k00_numpre ";
	$sSqlVerificaDebito .= " 	    left join certter 		on certter.v14_parcel = termo.v07_parcel   ";
	$sSqlVerificaDebito .= "  where {$sCamposWhere}									 	  			   ";

	$rsVerificaDebito    = pg_query($sSqlVerificaDebito); 
  	$iLinhaArrecad 		 = pg_num_rows($rsVerificaDebito);
  	
  	for ($iInd=0; $iInd < $iLinhaArrecad; $iInd++) {
  	  $oArrecad = db_utils::fieldsMemory($rsVerificaDebito,$iInd);
  	  if ( trim($oArrecad->v14_certid) != "" ) {
  	  	$this->cancelaCertidao($oArrecad->v14_certid,$oArrecad->k00_numpre);
  	  }
  	}
  	
	$rsVerificaDebito    = pg_query($sSqlVerificaDebito); 
  	$iLinhaArrecad 		 = pg_num_rows($rsVerificaDebito);
  	
  	if ( $iLinhaArrecad > 0 ) {
	  		
  	  for ($iInd=0; $iInd < $iLinhaArrecad; $iInd++) {
  	  	
  	    $oArrecad = db_utils::fieldsMemory($rsVerificaDebito,$iInd);
  	    		
  	  	$oDaoArresusp->k00_suspensao = $this->iCodSuspensao; 
  	  	$oDaoArresusp->k00_numpre    = $oArrecad->k00_numpre;   
  	  	$oDaoArresusp->k00_numpar    = $oArrecad->k00_numpar;   
  	  	$oDaoArresusp->k00_numcgm    = $oArrecad->k00_numcgm;    
  	  	$oDaoArresusp->k00_dtoper    = $oArrecad->k00_dtoper;    
  	  	$oDaoArresusp->k00_receit    = $oArrecad->k00_receit;    
  	  	$oDaoArresusp->k00_hist      = $oArrecad->k00_hist;      
  	  	$oDaoArresusp->k00_valor	 = $oArrecad->k00_valor;	 
  	  	$oDaoArresusp->k00_dtvenc	 = $oArrecad->k00_dtvenc;	 
  	  	$oDaoArresusp->k00_numtot	 = $oArrecad->k00_numtot;	 
  	  	$oDaoArresusp->k00_numdig  	 = $oArrecad->k00_numdig;  	 
  	  	$oDaoArresusp->k00_tipo 	 = $oArrecad->k00_tipo; 	 
  	  	$oDaoArresusp->k00_tipojm    = $oArrecad->k00_tipojm;
  	  	
  	  	$rsCalcDebitos = debitos_numpre($oArrecad->k00_numpre,0,$oArrecad->k00_tipo, db_getsession("DB_datausu"),db_getsession('DB_anousu'),$oArrecad->k00_numpar);
  	  	$oCalcDebitos  = db_utils::fieldsMemory($rsCalcDebitos,0);
  	  	
  	  	$oDaoArresusp->k00_vlrcor  	 = $oCalcDebitos->vlrcor;  	 
  	  	$oDaoArresusp->k00_vlrjur 	 = $oCalcDebitos->vlrjuros; 	 
  	  	$oDaoArresusp->k00_vlrmul    = $oCalcDebitos->vlrmulta;
  	  	$oDaoArresusp->k00_vlrdes    = $oCalcDebitos->vlrdesconto;
  	  	
  	  	$oDaoArresusp->incluir(null);
		
  	  	if ( $oDaoArresusp->erro_status == 0 ) {
  	  	  throw new Exception($oDaoArresusp->erro_msg);  	  		
  	  	}

  	  	$oDaoArrehist->k00_numpre     = $oArrecad->k00_numpre;  
  	  	$oDaoArrehist->k00_numpar 	  = $oArrecad->k00_numpar;
  	  	$oDaoArrehist->k00_hist   	  = $oArrecad->k00_hist;
  	  	$oDaoArrehist->k00_dtoper	  = date("Y-m-d",db_getsession('DB_datausu'));
  	  	$oDaoArrehist->k00_hora		  = db_hora();
  	  	$oDaoArrehist->k00_id_usuario = db_getsession('DB_id_usuario');
  	  	$oDaoArrehist->k00_histtxt    = "SUSPENS�O DE D�BITO ( Suspens�o:{$this->iCodSuspensao} ) ";
  	  	$oDaoArrehist->k00_limithist  = null;
  	  	
  	  	$oDaoArrehist->incluir(null);
  	  	
  	    if ( $oDaoArrehist->erro_status == 0 ) {
  	  	  throw new Exception($oDaoArrehist->erro_msg);  	  		
  	  	}  	  	
  	  	
	  }
	  
  	  $oDaoArrecad->excluir(null,$sCamposWhere);
  	  	
  	  if ( $oDaoArrecad->erro_status == 0 ) {
  	    throw new Exception($oDaoArrecad->erro_msg);  	  		
  	  }	  
	  
 	}
 	  	
  }

  function finalizaSuspensao($iCodSuspensao="",$sObs="",$sStatusDebito=""){
   	
    if(trim($iCodSuspensao) == "" || $iCodSuspensao == null ){
  	  throw new Exception("Finaliza��o de suspens�o abortada, c�digo de suspens�o inv�lido");
  	}
  	
    if(trim($sStatusDebito) == "" || $sStatusDebito == null ){
  	  throw new Exception("Finaliza��o de suspens�o abortada, status do d�bito inv�lido");
  	}
  	  	
  	$oDaoSuspensao		    = db_utils::getDao("suspensao");
  	$oDaoSuspensaoFinaliza  = db_utils::getDao("suspensaofinaliza");

  	
    if ( $sStatusDebito == "r" ) {
      $iCodTipoDebito = 1; 	
  	} else if ( $sStatusDebito == "c" ) {
  	  $iCodTipoDebito = 2;
  	}  	
  	
  	$oDaoSuspensaoFinaliza->ar19_suspensao  = $iCodSuspensao;
  	$oDaoSuspensaoFinaliza->ar19_id_usuario	= db_getsession('DB_id_usuario');
  	$oDaoSuspensaoFinaliza->ar19_tipo	    = $iCodTipoDebito;
  	$oDaoSuspensaoFinaliza->ar19_data	  	= date("Y-m-d",db_getsession('DB_datausu'));
  	$oDaoSuspensaoFinaliza->ar19_hora		= db_hora();
  	$oDaoSuspensaoFinaliza->ar19_obs	    = $sObs;  
  	$oDaoSuspensaoFinaliza->incluir(null);
  	
    if ( $oDaoSuspensaoFinaliza->erro_status == 0 ) {
  	  throw new Exception($oDaoSuspensaoFinaliza->erro_msg);  	  		
  	}  	

    if ( $sStatusDebito == "r" ) {
	  $this->reativarDebito($iCodSuspensao);
  	} else if ( $sStatusDebito == "c" ) {
	  $this->cancelarDebitoSuspensao($iCodSuspensao);
  	}  	  	
  	
    $oDaoSuspensao->ar18_sequencial = $iCodSuspensao;
  	$oDaoSuspensao->ar18_situacao   = 2;
  	$oDaoSuspensao->alterar($iCodSuspensao);
  	
  	if ( $oDaoSuspensao->erro_status == 0 ) {
  	  throw new Exception($oDaoSuspensao->erro_msg);  	  		
  	}
  	
  }
  
  
  
  
  function cancelaCertidao($iCertid,$iNumpre){
  	
    if(trim($iCertid) == "" || $iCertid == null ){
  	  throw new Exception("Cancelamento de certid�o abortada, certid�o n�o informada!");
  	}

  	if(trim($iNumpre) == "" || $iNumpre == null ){
  	  throw new Exception("Cancelamento de certid�o abortada, numpre n�o informada!");
  	}  	
  	
  	require_once("model/inicial.model.php");
  	$oIncial = new inicial();
  	
	$oDaoArrecad  	   = db_utils::getDao("arrecad");
	$oDaoCertid  	   = db_utils::getDao("certid");
	$oDaoAcertid  	   = db_utils::getDao("acertid");
	$oDaoAcertdiv  	   = db_utils::getDao("acertdiv");
	$oDaoAcertter  	   = db_utils::getDao("acertter");
	$oDaoCertdiv  	   = db_utils::getDao("certdiv");
	$oDaoCertter  	   = db_utils::getDao("certter");
	$oDaoArreforo      = db_utils::getDao("arreforo");
	$oDaoInicialNumpre = db_utils::getDao("inicialnumpre");
	

	
  	$sSqlTipoCertid  = " select distinct arrecad.k00_numpre,	 								 	";
  	$sSqlTipoCertid .= "		certdiv.v14_coddiv,		 									  	    ";
  	$sSqlTipoCertid .= " 	    certter.v14_parcel,											 	    ";  
  	$sSqlTipoCertid .= " 	    case															    ";
  	$sSqlTipoCertid .= " 	       when d.v51_inicial is not null then d.v51_inicial	  		    ";
  	$sSqlTipoCertid .= " 	       else case													    ";
  	$sSqlTipoCertid .= " 	   			  when t.v51_inicial is not null then t.v51_inicial  	    ";
  	$sSqlTipoCertid .= " 	   			  else null												    ";
  	$sSqlTipoCertid .= " 	   			end														    ";
  	$sSqlTipoCertid .= " 	   	end	as v51_inicial												    ";  	
	$sSqlTipoCertid .= "   from arrecad   														    ";
	$sSqlTipoCertid .= " 	    left join divida   		on divida.v01_numpre  = arrecad.k00_numpre  ";
	$sSqlTipoCertid .= " 	    				 	   and divida.v01_numpar  = arrecad.k00_numpar  ";
	$sSqlTipoCertid .= "   	    left join certdiv  	 	on divida.v01_coddiv  = certdiv.v14_coddiv  ";
	$sSqlTipoCertid .= " 	    left join termo    	 	on termo.v07_numpre   = arrecad.k00_numpre  ";
	$sSqlTipoCertid .= " 	    left join certter 		on certter.v14_parcel = termo.v07_parcel    ";
	$sSqlTipoCertid .= " 	    left join inicialcert d on d.v51_certidao	   = certdiv.v14_certid ";
	$sSqlTipoCertid .= " 	    left join inicialcert t on t.v51_certidao      = certter.v14_certid ";	
	$sSqlTipoCertid .= "  where ( certdiv.v14_certid = {$iCertid}					 	  		    ";
	$sSqlTipoCertid .= "     or   certter.v14_certid = {$iCertid} )						  		    ";

	$rsTipoCertid  = pg_query($sSqlTipoCertid);
	$iLinhasCertid = pg_num_rows($rsTipoCertid);
	  			
	if ( $iLinhasCertid > 0 ) {

	  $lParcial = false;
		
	  for ($iIndTestaCert=0; $iIndTestaCert < $iLinhasCertid; $iIndTestaCert++) {
	  		
	    $oTestaCertid = db_utils::fieldsMemory($rsTipoCertid,$iIndTestaCert);
	    
	    if ( trim($oTestaCertid->v51_inicial) != "" &&  $oTestaCertid->k00_numpre == $iNumpre ) {
	      try {	
	        $oIncial->excluiCertidaoInicial($iCertid,$oTestaCertid->v51_inicial);
	      } catch (Exception $eException){
	      	throw new Exception($eException->getMessage());
	      }
	    }
	    
	    if ( $oTestaCertid->k00_numpre != $iNumpre ) {
	      $lParcial = true; 	
	    }
	    
	  }	

	  $oDaoAcertid->v15_certid  = $iCertid;
 	  $oDaoAcertid->v15_data    = date('Y-m-d',db_getsession("DB_datausu"));
 	  $oDaoAcertid->v15_hora    = db_hora();
 	  $oDaoAcertid->v15_usuario = db_getsession("DB_id_usuario");
 	  $oDaoAcertid->v15_instit  = db_getsession("DB_instit");
 	  $oDaoAcertid->v15_parcial = ($lParcial?'1':'0');
 	  		
 	  $oDaoAcertid->incluir(null);
 	    
	  if ($oDaoAcertid->erro_status==0){
	   	throw new Exception($oDaoAcertid->erro_msg);
      }
 	  
	  for ($iIndCert=0; $iIndCert < $iLinhasCertid; $iIndCert++) {
	  	
	  	$oCertid = db_utils::fieldsMemory($rsTipoCertid,$iIndCert);

	  	if ( trim($oCertid->v14_coddiv) != "" ) {

	  	  $rsDadosDiv 	   = $oDaoCertdiv->sql_record($oDaoCertdiv->sql_query_deb(null,null,"distinct V01_numpre,V01_numpar,v01_coddiv",""," v14_coddiv = $oCertid->v14_coddiv and v01_instit = ".db_getsession('DB_instit')." and v13_instit=".db_getsession('DB_instit')." and v14_certid = $iCertid and v01_numpre = $iNumpre"));
	      $iLinhasDadosDiv = $oDaoCertdiv->numrows;
	        
	      if ( $iLinhasDadosDiv > 0 ){
	      	
	        for($i=0; $i<$iLinhasDadosDiv; $i++){
	        	
	          $oDadosDiv = db_utils::fieldsMemory($rsDadosDiv,$i);
	          	
	          $rsDadosArreforo = $oDaoArreforo->sql_record($oDaoArreforo->sql_query_file(null,"distinct k00_numpre,k00_numpar,k00_tipo",null,"k00_certidao=$iCertid and k00_numpre= {$oDadosDiv->v01_numpre} and k00_numpar= {$oDadosDiv->v01_numpar}"));
			  	          
	          if ($oDaoArreforo->numrows > 0 ){
	            $oDadosArreforo = db_utils::fieldsMemory($rsDadosArreforo,0);
	          } else {
				throw new Exception("Nao existem registros desta certidao na tabela arreforo! Contate suporte!");	          	
	          }
	          
	          $oDaoArrecad->k00_tipo = $oDadosArreforo->k00_tipo;
	          $oDaoArrecad->alterar_arrecad("k00_numpre = {$oDadosArreforo->k00_numpre} and k00_numpar={$oDadosArreforo->k00_numpar}");
	          
	          if ($oDaoArrecad->erro_status == 0 ){
	         	throw new Exception($oDaoArrecad->erro_msg);
              }

              $oDaoArreforo->excluir(null,"k00_certidao=$iCertid and k00_numpre={$oDadosArreforo->k00_numpre} and k00_numpar={$oDadosArreforo->k00_numpar}");
	          if ($oDaoArreforo->erro_status==0){
	            throw new Exception($oDaoArreforo>erro_msg);		
	          }

	          $rsDadosCertdiv = $oDaoCertdiv->sql_record($oDaoCertdiv->sql_query_file($iCertid,$oDadosDiv->v01_coddiv));

	          for ( $z=0; $z<$oDaoCertdiv->numrows; $z++ ){
	          	
	            $oDadosCertdiv  = db_utils::fieldsMemory($rsDadosCertdiv,$z);
	            
	            $oDaoAcertdiv->v14_certid	  = $oDadosCertdiv->v14_certid;
	            $oDaoAcertdiv->v14_coddiv	  = $oDadosCertdiv->v14_coddiv;
	            $oDaoAcertdiv->v14_vlrcor	  = $oDadosCertdiv->v14_vlrcor;
	            $oDaoAcertdiv->v14_vlrhis	  = $oDadosCertdiv->v14_vlrhis;
	            $oDaoAcertdiv->v14_vlrjur	  = $oDadosCertdiv->v14_vlrjur;
	            $oDaoAcertdiv->v14_vlrmul	  = $oDadosCertdiv->v14_vlrmul;
	            $oDaoAcertdiv->v14_codacertid = $oDaoAcertid->v15_codigo;
	            $oDaoAcertdiv->incluir($oDadosCertdiv->v14_certid,$oDadosCertdiv->v14_coddiv);


	            if ($oDaoAcertdiv->erro_status==0){
	              throw new Exception($oDaoAcertdiv->erro_msg);	            	
	            }
	            
	          }

	          $oDaoCertdiv->v14_certid = $iCertid;
	          $oDaoCertdiv->v14_coddiv = $oDadosDiv->v01_coddiv;
	          $oDaoCertdiv->excluir($iCertid,$oDadosDiv->v01_coddiv);

	          if ($oDaoCertdiv->erro_status==0){
	             throw new Exception($oDaoCertdiv>erro_msg);
	          }
	        }
	      }
	      
	    } else if (trim($oCertid->v14_parcel) != "") {
	
	      $rsDadosTermo 	 = $oDaoCertter->sql_record($oDaoCertter->sql_query_deb(null,null,"distinct v07_numpre,v07_parcel","","v14_certid=$iCertid and v14_parcel=$oCertid->v14_parcel and v07_numpre=$iNumpre"));
	      $iLinhasDadosTermo = pg_num_rows($rsDadosTermo);

	      if ( $iLinhasDadosTermo > 0 ){
	      	
	        for( $i=0; $i < $iLinhasDadosTermo ; $i++ ){
	         
	          $oDadosTermo = db_utils::fieldsMemory($rsDadosTermo,$i);
	          
	          $rsDadosArreforo = $oDaoArreforo->sql_record($oDaoArreforo->sql_query_file(null,"distinct k00_numpre,k00_numpar,k00_tipo",null,"k00_certidao= $iCertid  and k00_numpre=$oDadosTermo->v07_numpre "));

	          if ($oDaoArreforo->numrows > 0 ){
	            $oDadosArreforo = db_utils::fieldsMemory($rsDadosArreforo,0);
	          } else {
				throw new Exception("Nao existem registros desta certidao na tabela arreforo! Contate suporte!");	          	
	          }
	          
	          $oDaoArrecad->k00_tipo=$oDadosArreforo->k00_tipo;
	          $oDaoArrecad->alterar_arrecad("k00_numpre = {$oDadosArreforo->k00_numpre}");
	          
	          if ($oDaoArrecad->erro_status==0){
	         	throw new Exception($oDaoArrecad->erro_msg);
              }

              $oDaoArreforo->excluir(null,"k00_certidao=$iCertid and k00_numpre = {$oDadosArreforo->k00_numpre}");
	          if ($oDaoArreforo->erro_status==0){
	            throw new Exception($oDaoArreforo>erro_msg);		
	          }

	          $rsCertter = $oDaoCertter->sql_record($oDaoCertter->sql_query_file($iCertid,$oDadosTermo->v07_parcel));
	          
	          for( $z=0; $z < $oDaoCertter->numrows; $z++){
	          	
	            $oCertter = db_utils::fieldsMemory($rsCertter,$z);
	            
	            $oDaoAcertter->v14_certid     = $oCertter->v14_certid;
	            $oDaoAcertter->v14_parcel     = $oCertter->v14_parcel;
	            $oDaoAcertter->v14_vlrcor     = $oCertter->v14_vlrcor;
	            $oDaoAcertter->v14_vlrhis     = $oCertter->v14_vlrhis;
	            $oDaoAcertter->v14_vlrjur     = $oCertter->v14_vlrjur;
	            $oDaoAcertter->v14_vlrmul     = $oCertter->v14_vlrmul;
	            $oDaoAcertter->v14_codacertid = $oDaoAcertid->v15_codigo;
	            
	            $oDaoAcertter->incluir($iCertid,$oCertter->v14_parcel);
	            if ($oDaoAcertter->erro_status==0){
	               throw new Exception($oDaoAcertter->erro_msg);
	            }
	          }		      
	
	          $oDaoCertter->v14_certid = $iCertid;
	          $oDaoCertter->v14_parcel = $oDadosTermo->v07_parcel;
	          $oDaoCertter->excluir($iCertid,$oDadosTermo->v07_parcel);
	          
	          if ($oDaoCertter->erro_status==0){
	            throw new Exception($oDaoCertter->erro_msg);
	          }
	        }
	      }
	    }
	  }	
	}
	
    if (!$lParcial) {
      $oDaoCertid->excluir($iCertid);
      if ($oDaoCertid->erro_status==0){
        throw new Exception($oDaoCertid->erro_msg);
      }	
    }
	
  }
  
  
}

?>