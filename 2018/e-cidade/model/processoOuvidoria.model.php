<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


class processoOuvidoria {
  
  /**
   * 
   */
  function __construct() {

  }
  
  
  public function incluirTransferencia($iCodProc='',$iCodDeptoRec='',$iIdUsuarioRec='',$iIdUsuario='',$iCodDepto=''){

  	$sMsgErro = 'Transferência de processo abortada';
  	    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }
  	
  	if ( trim($iCodProc) == '' ) {
  		throw new Exception("{$sMsgErro}, nenhum processo informado!");
  	}
  	
    if ( trim($iCodDeptoRec) == '' ) {
      throw new Exception("{$sMsgErro}, departamento de recebimento não informado!");
    }
             	
    if ( trim($iIdUsuarioRec) == '' ) {
      $iIdUsuarioRec = 0; 
    }
  	
    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto = db_getsession('DB_coddepto');
    }       
  	
  	$clProcTransfer     = db_utils::getDao('proctransfer');
  	$clProcTransferProc = db_utils::getDao('proctransferproc');
  	
	  $clProcTransfer->p62_hora        = db_hora();
	  $clProcTransfer->p62_dttran      = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcTransfer->p62_id_usuario  = $iIdUsuario;
	  $clProcTransfer->p62_coddepto    = $iCodDepto;
	  $clProcTransfer->p62_id_usorec   = $iIdUsuarioRec;
	  $clProcTransfer->p62_coddeptorec = $iCodDeptoRec;
	  $clProcTransfer->incluir(null);
  	
	  if ( $clProcTransfer->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransfer->erro_msg}");
	  }
	  
    $clProcTransferProc->p63_codproc = $iCodProc;
    $clProcTransferProc->p63_codtran = $clProcTransfer->p62_codtran;
    $clProcTransferProc->incluir($clProcTransfer->p62_codtran,$iCodProc);
      
    if ( $clProcTransferProc->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransferProc->erro_msg}");
    }
	  
    return $clProcTransfer->p62_codtran;
    
  }
  
 
  public function incluirRecebimento($iCodProc='',$sDespacho='',$iCodTran='',$iIdUsuario='',$iCodDepto='',
                                     $lAlteraProcesso=true) {
  	
    $sMsgErro = 'Recebimento de processo abortado';
        
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }  	
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }    
    
    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto = db_getsession('DB_coddepto');
    }

    
    $clProtProcesso = db_utils::getDao('protprocesso');
    $clProcAndam    = db_utils::getDao('procandam');
    $clProcTransAnd = db_utils::getDao('proctransand');
    
    if ( trim($iCodTran) == '') {
	    $sWhereProcesso  = "     p58_codproc = {$iCodProc}               ";  
		  $sWhereProcesso .= " and p61_codandam is null                    ";     
		  $sWhereProcesso .= " and ((  p62_coddeptorec = {$iCodDepto}      ";
		  $sWhereProcesso .= "       and (    p62_id_usorec = 0            "; 
		  $sWhereProcesso .= "            or p62_id_usorec = {$iIdUsuario} ";
		  $sWhereProcesso .= "          )                                  ";
		  $sWhereProcesso .= "     )                                       ";
		  $sWhereProcesso .= "  or p58_codandam = 0   )                    ";
		  $sSqlqueryTransferencia = $clProtProcesso->sql_query_despachos(null, 
		                                                                 "p58_publico,p63_codtran", 
		                                                                  null, 
		                                                                  $sWhereProcesso);
	    $rsDadosProc = $clProtProcesso->sql_record($sSqlqueryTransferencia);
	    
	    if ( $clProtProcesso->numrows > 0 ) {
	      
	    	$oDadosProc = db_utils::fieldsMemory($rsDadosProc,0);
	    	$iCodTran   = $oDadosProc->p63_codtran; 
	    } else {
	    	throw new Exception("{$sMsgErro}, Nenhum transferência encontrada!");
	    }
	    
    } else {
    	$rsDadosProc = $clProtProcesso->sql_record($clProtProcesso->sql_query_file($iCodProc,"p58_publico"));  
		  $oDadosProc  = db_utils::fieldsMemory($rsDadosProc,0);        
    }
	  
	  $lPublico    = ($oDadosProc->p58_publico=='f'?"false":"true");
	
	  $clProcAndam->p61_publico    = $lPublico;
	  $clProcAndam->p61_codproc    = $iCodProc;
	  $clProcAndam->p61_dtandam    = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcAndam->p61_despacho   = $sDespacho;
	  $clProcAndam->p61_hora       = db_hora();
	  $clProcAndam->p61_id_usuario = $iIdUsuario;
	  $clProcAndam->p61_coddepto   = $iCodDepto;
	  $clProcAndam->incluir(null);
	          
	  if ($clProcAndam->erro_status == 0) {
      throw new Exception("{$sMsgErro}\n{$clProcAndam->erro_msg}");	  	
	  }
	           
    $clProcTransAnd->p64_codtran  = $iCodTran;
	  $clProcTransAnd->p64_codandam = $clProcAndam->p61_codandam;
	  $clProcTransAnd->incluir();
	  
	  if ($clProcTransAnd->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcTransAnd->erro_msg}");	  	
	  }
	
	  if ( $lAlteraProcesso ) {
	    
	    $clProtProcesso->p58_codproc  = $iCodProc;
	    $clProtProcesso->p58_codandam = $clProcAndam->p61_codandam;
	    $clProtProcesso->alterar($iCodProc);
	    
	    if ( $clProtProcesso->erro_status == 0 ) {
	      throw new Exception("{$sMsgErro}\n{$clProtProcesso->erro_msg}");    	
	    }
	  }
	  
	  return $clProcAndam->p61_codandam; 
	  
  }
  
  
  public function incluirDespachoInterno( $iCodProc='',$sDespacho,$iIdUsuario='',$iCodDepto='' ){

  	$sMsgErro = 'Inclusão de despacho interno abortada';
        
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }   
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }    
    
    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto  = db_getsession('DB_coddepto');
    }  	
  	
    try {
      $iCodTran = $this->incluirTransferencia($iCodProc,$iCodDepto,$iIdUsuario,$iIdUsuario,$iCodDepto);
    } catch (Exception $eException) {
    	throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");  
    }

    try {
      $iCodAndam = $this->incluirRecebimento($iCodProc,$sDespacho,$iCodTran,$iIdUsuario,$iCodDepto,false);
    } catch (Exception $eException) {
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");  
    }
        
  }

  
  
  function alteraPrazoPrevisto( $iCodProc='',$iCodDepto='',$sMotivo='',$iOrdem='',$lNovoDepto=false,$iDias='',$lSegueSequencia=false,$dtDataInicial=''){

    $sMsgErro = 'Alteração de prazo previsto abortada';
        
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }   
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }    
    
    if ( trim($iCodDepto) == '' ) {
      throw new Exception("{$sMsgErro}, departamento não informado!");
    }

    if ( trim($iOrdem) == '' ) {
    	try {
        $iOrdem = $this->getPosicaoAtualProrrogacao($iCodProc);
    	} catch (Exception $eException) {
    		throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");  
    	}
    	
    }            
    
    if ( trim($dtDataInicial) == '' ) {
    	$dtDataIni = db_getsession('DB_datausu');
    }
  	
    if ( $lNovoDepto && ( trim($iDias) == '' || $iDias == 0 ) ) {
      throw new Exception("{$sMsgErro}, número de dias para o departamento inválido!");    	 
    }
    
  	$clProcessoOuvidoriaProrrogacao = db_utils::getDao('processoouvidoriaprorrogacao');
  	$clCalend                       = db_utils::getDao('calend');
  	
    $sWhereProrrogacao  = "     ov15_protprocesso = {$iCodProc} ";
    $sWhereProrrogacao .= " and ov15_ativo is true              ";
        
    $sSqlProrrogacao    = $clProcessoOuvidoriaProrrogacao->sql_query(null,"*","ov15_dtfim",$sWhereProrrogacao);
    $rsProrrogacao      = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlProrrogacao);
    $iNroProrrogacao    = pg_num_rows($rsProrrogacao);

    $dtDataIni   = $dtDataInicial; 
    $dtDataFim   = $dtDataIni;
    $lProcessado = false;
        
    for ( $iInd=0; $iInd < $iNroProrrogacao; $iInd++ ) {
          
      $oProrrogacao = db_utils::fieldsMemory($rsProrrogacao,$iInd);

      $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $oProrrogacao->ov15_protprocesso;
      $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $oProrrogacao->ov15_coddepto;
      $clProcessoOuvidoriaProrrogacao->ov15_dtini        = $oProrrogacao->ov15_dtini;
      $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = $oProrrogacao->ov15_dtfim;
      $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $oProrrogacao->ov15_motivo;      

//      echo "Ordem ".($iInd+1)." == ".$iOrdem."\n";

      if ( ($iInd+1) == $iOrdem && date('Y-m-d',$dtDataInicial) < $oProrrogacao->ov15_dtfim ) {
            
        $clProcessoOuvidoriaProrrogacao->ov15_sequencial = $oProrrogacao->ov15_sequencial;
        $clProcessoOuvidoriaProrrogacao->ov15_ativo      = 'false';
        $clProcessoOuvidoriaProrrogacao->alterar($oProrrogacao->ov15_sequencial);
    
//        echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial Desativa 1\n";
        
        if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");          	
        }           
            
        $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $iCodProc;
        $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $oProrrogacao->ov15_coddepto;
        $clProcessoOuvidoriaProrrogacao->ov15_ativo        = 'true';
        $clProcessoOuvidoriaProrrogacao->ov15_dtini        = $oProrrogacao->ov15_dtini;
        $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = date('Y-m-d',$dtDataInicial);
        $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $sMotivo;
        $clProcessoOuvidoriaProrrogacao->incluir(null);
        
//        echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial DataIni:{$clProcessoOuvidoriaProrrogacao->ov15_dtini} DataFin:{$clProcessoOuvidoriaProrrogacao->ov15_dtfim}\n";
        
        if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");
        }
        
        $dtDataIni = strtotime('+1 day',$dtDataInicial);
         
      }
      
//      echo "Ordem ".($iInd+1)." > ".$iOrdem."\n";
                
      if ( ($iInd+1) > $iOrdem ) {
            
        $clProcessoOuvidoriaProrrogacao->ov15_sequencial = $oProrrogacao->ov15_sequencial;
        $clProcessoOuvidoriaProrrogacao->ov15_ativo      = 'false';
        $clProcessoOuvidoriaProrrogacao->alterar($oProrrogacao->ov15_sequencial);
            
//        echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial Desativa \n";        
        
        if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");
        }

        if ( (!$lSegueSequencia || ($lSegueSequencia && $iCodDepto == $oProrrogacao->ov15_coddepto)) && !$lProcessado && $lNovoDepto ) {
            
          $dtDataFim = db_stdClass::getIntervaloDiasUteis($dtDataIni,$iDias);
              
          $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $iCodProc;
          $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $iCodDepto;
          $clProcessoOuvidoriaProrrogacao->ov15_ativo        = 'true';
          $clProcessoOuvidoriaProrrogacao->ov15_dtini        = date('Y-m-d',$dtDataIni);
          $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = date('Y-m-d',$dtDataFim);
          $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $sMotivo;
          $clProcessoOuvidoriaProrrogacao->incluir(null);

//          echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial DataIni:{$clProcessoOuvidoriaProrrogacao->ov15_dtini} DataFin:{$clProcessoOuvidoriaProrrogacao->ov15_dtfim} - Novo Depto \n";
          
          if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");            	
          }             

          $lProcessado = true;
          if ( $lSegueSequencia ) {
            continue;
          }
              
        }

        if ( ( $lProcessado && $lNovoDepto ) || ( !$lProcessado && !$lNovoDepto ) ) {
            
        	$aDataPrevIni = explode('-',$oProrrogacao->ov15_dtini);
          $aDataPrevFin = explode('-',$oProrrogacao->ov15_dtfim);
          $iDataPrevIni = mktime(0,0,0,$aDataPrevIni[1],$aDataPrevIni[2],$aDataPrevIni[0]); 
          $iDataPrevFin = mktime(0,0,0,$aDataPrevFin[1],$aDataPrevFin[2],$aDataPrevFin[0]);
          $iDiasDif     = ceil(($iDataPrevFin-$iDataPrevIni)/86400)+1;
          
          $sWhereCalend  = "k13_data between '{$oProrrogacao->ov15_dtini}' and '{$oProrrogacao->ov15_dtfim}'";
          $rsDiasFeriado = $clCalend->sql_record($clCalend->sql_query(null,"count(*)",null,$sWhereCalend)); 
          $oDiasFeriado  = db_utils::fieldsMemory($rsDiasFeriado,0);
          $iDiasDif     -= $oDiasFeriado->count;           
          
//          echo "Dias: ".$iDiasDif."\n";
          
          $dtDataIni = strtotime('+1 day',$dtDataFim);
          $dtDataFim = db_stdClass::getIntervaloDiasUteis($dtDataIni,$iDiasDif);
            
          $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $iCodProc;
          $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $oProrrogacao->ov15_coddepto;
          $clProcessoOuvidoriaProrrogacao->ov15_ativo        = 'true';
          $clProcessoOuvidoriaProrrogacao->ov15_dtini        = date('Y-m-d',$dtDataIni);
          $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = date('Y-m-d',$dtDataFim);
          $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $sMotivo;
          $clProcessoOuvidoriaProrrogacao->incluir(null);
          
//          echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial DataIni:{$clProcessoOuvidoriaProrrogacao->ov15_dtini} DataFin:{$clProcessoOuvidoriaProrrogacao->ov15_dtfim}\n";
          
          if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");            	
          }

        }
          
      }
      
    }  	
    
  }

  function incluiNovoDeptoProrrogacao( $iCodProc='',$iCodDepto='',$sMotivo='',$iOrdem='',$iDias='',$lSegueSequencia=false){

    $sMsgErro = 'Alteração de prazo previsto abortada';
        
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }   
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }    
    
    if ( trim($iCodDepto) == '' ) {
      throw new Exception("{$sMsgErro}, departamento não informado!");
    }

    if ( trim($iOrdem) == '' ) {
      try {
        $iOrdem = $this->getPosicaoAtualProrrogacao($iCodProc);
      } catch (Exception $eException) {
        throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");  
      }
    }            
    
    if ( trim($iDias) == '' || $iDias == 0 ) {
      throw new Exception("{$sMsgErro}, número de dias para o departamento inválido!");      
    }
    
    $clProcessoOuvidoriaProrrogacao = db_utils::getDao('processoouvidoriaprorrogacao');
    $clCalend                       = db_utils::getDao('calend');
    
    $sWhereProrrogacao  = "     ov15_protprocesso = {$iCodProc} ";
    $sWhereProrrogacao .= " and ov15_ativo is true              ";
        
    $sSqlProrrogacao    = $clProcessoOuvidoriaProrrogacao->sql_query(null,"*","ov15_dtfim",$sWhereProrrogacao);
    $rsProrrogacao      = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlProrrogacao);
    $iNroProrrogacao    = pg_num_rows($rsProrrogacao);

    $lProcessado = false;
        
    for ( $iInd=0; $iInd < $iNroProrrogacao; $iInd++ ) {
          
      $oProrrogacao = db_utils::fieldsMemory($rsProrrogacao,$iInd);

      $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $oProrrogacao->ov15_protprocesso;
      $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $oProrrogacao->ov15_coddepto;
      $clProcessoOuvidoriaProrrogacao->ov15_dtini        = $oProrrogacao->ov15_dtini;
      $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = $oProrrogacao->ov15_dtfim;
      $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $oProrrogacao->ov15_motivo;      
                
      if ( ($iInd+1) > $iOrdem ) {
            
        $clProcessoOuvidoriaProrrogacao->ov15_sequencial = $oProrrogacao->ov15_sequencial;
        $clProcessoOuvidoriaProrrogacao->ov15_ativo      = 'false';
        $clProcessoOuvidoriaProrrogacao->alterar($oProrrogacao->ov15_sequencial);
            
//        echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial Desativa \n";        
        
        if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");
        }

        if ( (!$lSegueSequencia || ($lSegueSequencia && $iCodDepto == $oProrrogacao->ov15_coddepto)) && !$lProcessado ) {
            
          $aDataIni  = explode('-',$oProrrogacao->ov15_dtini);
          $dtDataIni = mktime(0,0,0,$aDataIni[1],$aDataIni[2],$aDataIni[0]);
          $dtDataFim = db_stdClass::getIntervaloDiasUteis($dtDataIni,$iDias);
              
          $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $iCodProc;
          $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $iCodDepto;
          $clProcessoOuvidoriaProrrogacao->ov15_ativo        = 'true';
          $clProcessoOuvidoriaProrrogacao->ov15_dtini        = date('Y-m-d',$dtDataIni);
          $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = date('Y-m-d',$dtDataFim);
          $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $sMotivo;
          $clProcessoOuvidoriaProrrogacao->incluir(null);

//          echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial DataIni:{$clProcessoOuvidoriaProrrogacao->ov15_dtini} DataFin:{$clProcessoOuvidoriaProrrogacao->ov15_dtfim} - Novo Depto \n";
          
          if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");              
          }             

          $lProcessado = true;
          
          if ( $lSegueSequencia ) {
            continue;
          }
              
        }

        if ( $lProcessado ) {
            
          $aDataPrevIni = explode('-',$oProrrogacao->ov15_dtini);
          $aDataPrevFin = explode('-',$oProrrogacao->ov15_dtfim);
          $iDataPrevIni = mktime(0,0,0,$aDataPrevIni[1],$aDataPrevIni[2],$aDataPrevIni[0]); 
          $iDataPrevFin = mktime(0,0,0,$aDataPrevFin[1],$aDataPrevFin[2],$aDataPrevFin[0]);
          $iDiasDif     = ceil(($iDataPrevFin-$iDataPrevIni)/86400)+1;
          
          $sWhereCalend  = "k13_data between '{$oProrrogacao->ov15_dtini}' and '{$oProrrogacao->ov15_dtfim}'";
          $rsDiasFeriado = $clCalend->sql_record($clCalend->sql_query(null,"count(*)",null,$sWhereCalend)); 
          $oDiasFeriado  = db_utils::fieldsMemory($rsDiasFeriado,0);
          $iDiasDif     -= $oDiasFeriado->count;           
          
//          echo "Dias: ".$iDiasDif."\n";
          
          $dtDataIni = strtotime('+1 day',$dtDataFim);
          $dtDataFim = db_stdClass::getIntervaloDiasUteis($dtDataIni,$iDiasDif);
            
          $clProcessoOuvidoriaProrrogacao->ov15_protprocesso = $iCodProc;
          $clProcessoOuvidoriaProrrogacao->ov15_coddepto     = $oProrrogacao->ov15_coddepto;
          $clProcessoOuvidoriaProrrogacao->ov15_ativo        = 'true';
          $clProcessoOuvidoriaProrrogacao->ov15_dtini        = date('Y-m-d',$dtDataIni);
          $clProcessoOuvidoriaProrrogacao->ov15_dtfim        = date('Y-m-d',$dtDataFim);
          $clProcessoOuvidoriaProrrogacao->ov15_motivo       = $sMotivo;
          $clProcessoOuvidoriaProrrogacao->incluir(null);
          
//          echo "Depto: $clProcessoOuvidoriaProrrogacao->ov15_coddepto Sequencial: $clProcessoOuvidoriaProrrogacao->ov15_sequencial DataIni:{$clProcessoOuvidoriaProrrogacao->ov15_dtini} DataFin:{$clProcessoOuvidoriaProrrogacao->ov15_dtfim}\n";
          
          if ( $clProcessoOuvidoriaProrrogacao->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$clProcessoOuvidoriaProrrogacao->erro_msg}");              
          }

        }
          
      }
      
    }   
    
  }  
  
  
  
  
  function getProximoDepto($iCodProc='',$iDeptoAtual=''){

  	$sMsgErro = 'Consulta de departamento abortada';
  	
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }      	

    if ( trim($iDeptoAtual) == '' ) {
      $iDeptoAtual = db_getsession('DB_coddepto');    
    }         	
    
  	$clProcessoOuvidoriaProrrogacao = db_utils::getDao('processoouvidoriaprorrogacao');
    
  	try {
  	  $iOrdemProrrogacao = $this->getPosicaoAtualProrrogacao($iCodProc);
  	} catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");  		
  	}
  	
  	$sWherePrazo  = "     ov15_protprocesso = {$iCodProc} ";
    $sWherePrazo .= " and ov15_ativo is true              ";
  	
    $sSqlPrazoPrevisto    = $clProcessoOuvidoriaProrrogacao->sql_query_file(null,"ov15_coddepto","ov15_dtfim",$sWherePrazo);
    $rsPrazoPrevisto      = $clProcessoOuvidoriaProrrogacao->sql_record($sSqlPrazoPrevisto);
    $iLinhasPrazoPrevisto = $clProcessoOuvidoriaProrrogacao->numrows;

    $iCodDepto     = '';
    $lProximoDepto = false;

    if ( $iLinhasPrazoPrevisto > 0 ) {
      for ( $iInd=0; $iInd < $iLinhasPrazoPrevisto; $iInd++ ) {
         $oPrazo = db_utils::fieldsMemory($rsPrazoPrevisto,$iInd);
         if ( $lProximoDepto ) {
           $iCodDepto = $oPrazo->ov15_coddepto;
           break;
         }         
         if ( ($iInd+1) == $iOrdemProrrogacao ) {
           $lProximoDepto = true;
         }     
       }
     }
  	
    return $iCodDepto; 
     
  }
  
  function getPosicaoAtualProrrogacao($iCodProc=''){

    $sMsgErro = 'Consulta da posição atual na prorrogação abortada';
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }       
    
    $clProcTransferProc = db_utils::getDao('proctransferproc');

    $iNroRegDepto  = 0;
    $sWhereAndam   = "     p63_codproc   = {$iCodProc}     ";
    $sWhereAndam  .= " and p62_coddepto != p62_coddeptorec ";

    $rsDadosAndam  = $clProcTransferProc->sql_record($clProcTransferProc->sql_query_andam(null,null,"count(*)",null,$sWhereAndam));
    
    if ( $clProcTransferProc->numrows > 0 ) {
      $oDadosAndam  = db_utils::fieldsMemory($rsDadosAndam,0);
      $iNroRegDepto = $oDadosAndam->count;
    }
    
    return $iNroRegDepto;
    
  }  
  
  
  
  function arquivarProcesso( $iCodProc='',$sHistorico='',$iIdUsuario='',$iCodDepto='' ){

  	$sMsgErro = 'Arquivamento de processo abortado';
        
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }   
    
    if ( trim($iCodProc) == '' ) {
      throw new Exception("{$sMsgErro}, nenhum processo informado!");
    }

    if ( trim($iIdUsuario) == '' ) {
      $iIdUsuario = db_getsession('DB_id_usuario');
    }

    if ( trim($iCodDepto) == '' ) {
      $iCodDepto  = db_getsession('DB_coddepto');
    }    
    
    $clOuvidoriaAtendimento = db_utils::getDao('ouvidoriaatendimento'); 
    $clProcArquiv           = db_utils::getDao('procarquiv');
    $clArqAndam             = db_utils::getDao('arqandam');
    $clArqProc              = db_utils::getDao('arqproc');

	  $clProcArquiv->p67_id_usuario = $iIdUsuario;
	  $clProcArquiv->p67_coddepto   = $iCodDepto;
	  $clProcArquiv->p67_codproc    = $iCodProc;
	  $clProcArquiv->p67_dtarq      = date('Y-m-d',db_getsession('DB_datausu'));
	  $clProcArquiv->p67_historico  = $sHistorico;
	  $clProcArquiv->incluir(null);
	  
	  if ( $clProcArquiv->erro_status == 0 ) {
      throw new Exception("{$sMsgErro}\n{$clProcArquiv->erro_msg}");	  	
	  }
	  
	  $clArqProc->p68_codarquiv = $clProcArquiv->p67_codarquiv;
	  $clArqProc->p68_codproc   = $iCodProc;
	  $clArqProc->incluir($clProcArquiv->p67_codarquiv,$iCodProc);
	   
	  if ( $clArqProc->erro_status == 0 ) {
	    throw new Exception("{$sMsgErro}\n{$clArqProc->erro_msg}");
	  } 
	  
	  try {
      $iCodTran  = $this->incluirTransferencia($iCodProc,$iCodDepto,$iIdUsuario,$iIdUsuario,$iCodDepto);
      $iCodAndam = $this->incluirRecebimento($iCodProc,$sHistorico,$iCodTran,$iIdUsuario,$iCodDepto,false);
	  } catch (Exception $eException) {
	  	throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }
	

	  $clArqAndam->p69_codarquiv = $clProcArquiv->p67_codarquiv;
	  $clArqAndam->p69_codandam  = $iCodAndam;
	  $clArqAndam->p69_arquivado = 'true';
	  $clArqAndam->incluir(null);
	  
	  if ( $clArqAndam->erro_status == 0 ){
	    throw new Exception("{$sMsgErro}\n{$clArqAndam->erro_msg}");
	  }

	  $sWhereAtendimento = " ov09_protprocesso = {$iCodProc} ";
	  $sSqlAtendimento   = $clOuvidoriaAtendimento->sql_query_proc(null,"distinct ov01_sequencial",null,$sWhereAtendimento);
	  $rsAtendimento     = $clOuvidoriaAtendimento->sql_record($sSqlAtendimento);
	  $iNroAtendimento   = $clOuvidoriaAtendimento->numrows;
	  
	  if ( $iNroAtendimento > 0 ) {
	  	for ( $iInd=0; $iInd < $iNroAtendimento; $iInd++ ) {
		  	$oAtendimento = db_utils::fieldsMemory($rsAtendimento,$iInd);
		  	$clOuvidoriaAtendimento->ov01_sequencial = $oAtendimento->ov01_sequencial;
		  	$clOuvidoriaAtendimento->ov01_situacaoouvidoriaatendimento = 3;
		  	$clOuvidoriaAtendimento->alterar($oAtendimento->ov01_sequencial);
		  	if ( $clOuvidoriaAtendimento->erro_status == 0 ) {
		  		throw new Exception("{$sMsgErro}\n{$clOuvidoriaAtendimento->erro_msg}");
		  	}
	  	}
	  }
	  
  }
  
}

?>