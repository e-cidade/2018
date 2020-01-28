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


class pontoFolha {
  
	
  function __construct() {

  }

  
  /**
   * Valida o objeto passado por par�metro de acordo com o cadastro das rubricas
   *
   * @param  array $aObjDadosPonto
   * @return array
   */
  public function validaCamposObj($aObjDadosPonto=null){

    if ( empty($aObjDadosPonto) ) {
      throw new Exception("Valida��o abortada, dados n�o informados!");      
    }
  	
	  $oDaoRhrubricas = db_utils::getDao("rhrubricas");

	  $aRetornoObj = array();
	  
    foreach ( $aObjDadosPonto as $iInd => $oDadosPonto ) {
      
      $sCamposRubricaFormula  = "rh27_form   as formula, ";
      $sCamposRubricaFormula .= "rh27_limdat as limdata  "; 
      
      $sWhereRubricaFormula   = "     rh27_instit = ".db_getsession("DB_instit");
      $sWhereRubricaFormula  .= " and rh27_rubric = '{$oDadosPonto->r90_rubric}'";
      
      $sSqlRubricaFormula     = $oDaoRhrubricas->sql_query_file(null,null,$sCamposRubricaFormula,null,$sWhereRubricaFormula);
      $rsRubricaFormula       = $oDaoRhrubricas->sql_record($sSqlRubricaFormula);
      
      if ( $oDaoRhrubricas->numrows > 0 ){
        
        $oRubricaFormula = db_utils::fieldsMemory($rsRubricaFormula,0);
        
        if( $oRubricaFormula->limdata == "t" ){
          if(isset($oDadosPonto->r90_datlim) && $oDadosPonto->r90_datlim == ""){
            throw new Exception("Ano/M�s n�o informado");
          }
        } else if ( $oRubricaFormula->limdata == "f" ){
          $oDadosPonto->r90_datlim = "";
        } else if (trim($oRubricaFormula->formula) != "") {
          if( $oDadosPonto->r90_quant == 0){
            throw new Exception("Quantidade n�o informada");
          }
        } else {
          if( $oDadosPonto->r90_valor == 0 ){
            throw new Exception("Valor n�o informado");
          }
        }
      } else {
        throw new Exception("Rubrica {$oDadosPonto->r90_rubric} n�o encontrada. Verifique.");
      }
      
      $aRetornoObj[] = $oDadosPonto; 
      
    }
    	  
    return $aRetornoObj;
    
  }
  
  
  /**
   * Inclus�o do ponto de acordo com os par�metros informados :
   * fx  = Ponto Fixo;
   * fs  = Ponto de Sal�rio
   * fa  = Ponto de Adiantamento
   * fe  = Ponto de F�rias
   * fr  = Ponto de Rescis�o
   * f13 = Ponto de 13�
   * com = Ponto Complementar
   * 
   * @param string $sTipoPonto
   * @param array  $aObjDadosPonto
   */
  public function incluiRubricaPonto($sTipoPonto='',$aObjDadosPonto=null){
  	
     
    if ( !db_utils::inTransaction() ){
      throw new Exception("Inclus�o abortada, nenhuma transa��o encontrada!");
    }
  	
  	if ( $sTipoPonto == '' ) {
      throw new Exception("Inclus�o abortada, tipo de ponto n�o informado!");
  	}
  	
  	if ( empty($aObjDadosPonto) ) {
      throw new Exception("Inclus�o abortada, dados n�o informados!");  		
  	}
  	
	  
	  $oDaoPontoFx    = db_utils::getDao("pontofx");
	  $oDaoPontoFs    = db_utils::getDao("pontofs");
	  $oDaoPontoFa    = db_utils::getDao("pontofa");
	  $oDaoPontoFe    = db_utils::getDao("pontofe");
	  $oDaoPontoFr    = db_utils::getDao("pontofr");
	  $oDaoPontoF13   = db_utils::getDao("pontof13");
	  $oDaoPontoCom   = db_utils::getDao("pontocom");

	  try {
	    $aObjPonto = $this->validaCamposObj($aObjDadosPonto);
	  } catch ( Exception $eException ){
      throw new Exception($eException->getMessage());	  	  
	  }
	  
    foreach ( $aObjPonto as $iInd => $oDadosPonto ) {
    	
      // Ponto Fixo
		    
      if ( $sTipoPonto == "fx" ) {
		      
	      $oDaoPontoFx->r90_anousu = $oDadosPonto->r90_anousu;
		    $oDaoPontoFx->r90_mesusu = $oDadosPonto->r90_mesusu;
		    $oDaoPontoFx->r90_regist = $oDadosPonto->r90_regist;
		    $oDaoPontoFx->r90_rubric = $oDadosPonto->r90_rubric;
		    $oDaoPontoFx->r90_valor  = db_formatar($oDadosPonto->r90_valor,'p');
		    $oDaoPontoFx->r90_quant  = "{$oDadosPonto->r90_quant}";
		    $oDaoPontoFx->r90_lotac  = $oDadosPonto->r90_lotac;    
		    $oDaoPontoFx->r90_datlim = $oDadosPonto->r90_datlim;
		    $oDaoPontoFx->r90_instit = db_getsession("DB_instit");
		    
		    $oDaoPontoFx->incluir( $oDadosPonto->r90_anousu,
		                           $oDadosPonto->r90_mesusu,
		                           $oDadosPonto->r90_regist,
		                           $oDadosPonto->r90_rubric);
		                           
		      
        if( $oDaoPontoFx->erro_status == '0' ){
		      throw new Exception($oDaoPontoFx->erro_msg);        
	      }

		      
		    
	   // Ponto de Sal�rio
		    
     } else if ( $sTipoPonto == "fs" ) {
		
	     $oDaoPontoFs->r10_anousu = $oDadosPonto->r90_anousu;
	     $oDaoPontoFs->r10_mesusu = $oDadosPonto->r90_mesusu;
	     $oDaoPontoFs->r10_regist = $oDadosPonto->r90_regist;
	     $oDaoPontoFs->r10_rubric = $oDadosPonto->r90_rubric;
		   $oDaoPontoFs->r10_valor  = db_formatar($oDadosPonto->r90_valor,'p');
		   $oDaoPontoFs->r10_quant  = $oDadosPonto->r90_quant;
		   $oDaoPontoFs->r10_lotac  = $oDadosPonto->r90_lotac;    
		   $oDaoPontoFs->r10_datlim = $oDadosPonto->r90_datlim;
		   $oDaoPontoFs->r10_instit = db_getsession("DB_instit");
		   
		   $oDaoPontoFs->incluir($oDadosPonto->r90_anousu,
		                         $oDadosPonto->r90_mesusu,
		                         $oDadosPonto->r90_regist,
		                         $oDadosPonto->r90_rubric);
		                           
       if ( $oDaoPontoFs->erro_status == '0' ){
		     throw new Exception($oDaoPontoFs->erro_msg);
	     }
		      
		      
	    // Ponto de Adiantamento
		    
	    } else if ($sTipoPonto == "fa" ) {
		      
	      $oDaoPontoFa->r21_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoFa->r21_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoFa->r21_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoFa->r21_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoFa->r21_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoFa->r21_quant  = $oDadosPonto->r90_quant;
	      $oDaoPontoFa->r21_lotac  = $oDadosPonto->r90_lotac;    
	      $oDaoPontoFa->r21_instit = db_getsession("DB_instit");
	      
	      $oDaoPontoFa->incluir($oDadosPonto->r90_anousu,
	                            $oDadosPonto->r90_mesusu,
	                            $oDadosPonto->r90_regist,
	                            $oDadosPonto->r90_rubric);
		                            
	      if( $oDaoPontoFa->erro_status == '0' ){
          throw new Exception($oDaoPontoFa->erro_msg);
	      }
		      
		      
	    // Ponto de F�rias
		    
	    } else if ( $sTipoPonto == "fe" ){
		      
	      $oDaoPontoFe->r29_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoFe->r29_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoFe->r29_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoFe->r29_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoFe->r29_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoFe->r29_quant  = $oDadosPonto->r90_quant;
	      $oDaoPontoFe->r29_lotac  = $oDadosPonto->r90_lotac;
	      $oDaoPontoFe->r29_media  = "0";
	      $oDaoPontoFe->r29_calc   = "0";
	      $oDaoPontoFe->r29_tpp    = $oDadosPonto->r29_tpp;
	      $oDaoPontoFe->r29_instit = db_getsession("DB_instit");
		      
	      $oDaoPontoFe->incluir($oDadosPonto->r90_anousu,
	                            $oDadosPonto->r90_mesusu,
	                            $oDadosPonto->r90_regist,
	                            $oDadosPonto->r90_rubric,
	                            $oDadosPonto->r29_tpp);
		                            
	      if( $oDaoPontoFe->erro_status == '0' ){
          throw new Exception($oDaoPontoFe->erro_msg);
	      }
		      
		      
      // Ponto de Rescis�o
 	 	    
 	    } else if ( $sTipoPonto == "fr" ){
		      
	      $oDaoPontoFr->r19_anousu = $oDadosPonto->r90_anousu;
		    $oDaoPontoFr->r19_mesusu = $oDadosPonto->r90_mesusu;
		    $oDaoPontoFr->r19_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoFr->r19_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoFr->r19_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoFr->r19_quant  = $oDadosPonto->r90_quant;
	      $oDaoPontoFr->r19_lotac  = $oDadosPonto->r90_lotac;
	      $oDaoPontoFr->r19_tpp    = $oDadosPonto->r29_tpp;
	      $oDaoPontoFr->r19_instit = db_getsession("DB_instit");
		      
	      $oDaoPontoFr->incluir($oDadosPonto->r90_anousu,
	                            $oDadosPonto->r90_mesusu,
	                            $oDadosPonto->r90_regist,
	                            $oDadosPonto->r90_rubric,
	                            $oDadosPonto->r29_tpp);
		                            
	      if( $oDaoPontoFr->erro_status == '0' ){
          throw new Exception($oDaoPontoFr->erro_msg);
	      }

		      
	    // Ponto de 13�
		    
	    } else if( $sTipoPonto == "f13" ){
		      
	      $oDaoPontoF13->r34_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoF13->r34_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoF13->r34_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoF13->r34_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoF13->r34_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoF13->r34_quant  = $oDadosPonto->r90_quant;
	      $oDaoPontoF13->r34_lotac  = $oDadosPonto->r90_lotac;
	      $oDaoPontoF13->r34_media  = "0";
	      $oDaoPontoF13->r34_calc   = "0";
	      $oDaoPontoF13->r34_instit = db_getsession("DB_instit");
		      
	      $oDaoPontoF13->incluir($oDadosPonto->r90_anousu,
	                             $oDadosPonto->r90_mesusu,
	                             $oDadosPonto->r90_regist,
	                             $oDadosPonto->r90_rubric);
		                             
	      if ( $oDaoPontoF13->erro_status == '0' ){
		      throw new Exception($oDaoPontoF13->erro_msg);
		    }
		      
		      
	    // Ponto Complementar
		    
	    } else if( $sTipoPonto == "com" ){
		      
	      $oDaoPontoCom->r47_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoCom->r47_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoCom->r47_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoCom->r47_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoCom->r47_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoCom->r47_quant  = $oDadosPonto->r90_quant;
	      $oDaoPontoCom->r47_lotac  = $oDadosPonto->r90_lotac;
	      $oDaoPontoCom->r47_instit = db_getsession("DB_instit");
		      
	      $oDaoPontoCom->incluir($oDadosPonto->r90_anousu,
	                             $oDadosPonto->r90_mesusu,
	                             $oDadosPonto->r90_regist,
	                             $oDadosPonto->r90_rubric);
		                             
	      if ( $oDaoPontoCom->erro_status == '0' ){
		      throw new Exception($oDaoPontoCom->erro_msg); 
	      }
	    }
	  }
  
  }

  
  /**
   * Altera��o do ponto de acordo com os par�metros informados :
   * fx  = Ponto Fixo;
   * fs  = Ponto de Sal�rio
   * fa  = Ponto de Adiantamento
   * fe  = Ponto de F�rias
   * fr  = Ponto de Rescis�o
   * f13 = Ponto de 13�
   * com = Ponto Complementar
   * 
   * @param string $sTipoPonto
   * @param array  $aObjDadosPonto
   */
  public function alteraRubricaPonto($sTipoPonto='',$aObjDadosPonto=null,$lSomaValores=false){

    if ( !db_utils::inTransaction() ){
      throw new Exception("Altera��o abortada, nenhuma transa��o encontrada!");
    }  	
  	
    if ( $sTipoPonto == '' ) {
      throw new Exception("Altera��o abortada, tipo de ponto n�o informado!");
    }
    
    if ( empty($aObjDadosPonto) ) {
      throw new Exception("Altera��o abortada, dados n�o informados!");      
    }
    
    
    $oDaoPontoFx    = db_utils::getDao("pontofx");
    $oDaoPontoFs    = db_utils::getDao("pontofs");
    $oDaoPontoFa    = db_utils::getDao("pontofa");
    $oDaoPontoFe    = db_utils::getDao("pontofe");
    $oDaoPontoFr    = db_utils::getDao("pontofr");
    $oDaoPontoF13   = db_utils::getDao("pontof13");
    $oDaoPontoCom   = db_utils::getDao("pontocom");

    
    try {
      $aObjPonto = $this->validaCamposObj($aObjDadosPonto);
    } catch ( Exception $eException ){
      throw new Exception($eException->getMessage());       
    }
      
    foreach ( $aObjPonto as $iInd => $oDadosPonto ) {
      
     	if ( $lSomaValores ) {
      		
     	  if( $sTipoPonto == "fx" ){
	        $sSqlSomaValores = $oDaoPontoFx->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"r90_quant as quant,r90_valor as valor");
	      } else if ( $sTipoPonto == "fs" ){
	        $sSqlSomaValores = $oDaoPontoFs->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"r10_quant as quant,r10_valor as valor");
	      } else if($sTipoPonto   == "fa" ){
	        $sSqlSomaValores = $oDaoPontoFa->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"r21_quant as quant,r21_valor as valor");
	      } else if($sTipoPonto   == "fe" ){
	        $sSqlSomaValores = $oDaoPontoFe->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,$oDadosPonto->r29_tpp,"r29_quant as quant,r29_valor as valor");
	      } else if($sTipoPonto   == "fr" ){
	        $sSqlSomaValores = $oDaoPontoFr->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,$oDadosPonto->r29_tpp,"r19_quant as quant,r19_valor as valor");
	      } else if($sTipoPonto   == "f13"){
	        $sSqlSomaValores = $oDaoPontoF13->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"r34_quant as quant,r34_valor as valor");
	      } else if($sTipoPonto   == "com"){
	        $sSqlSomaValores = $oDaoPontoCom->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"r47_quant as quant,r47_valor as valor");
	      }
		      
	      $rsSomaValores = db_query($sSqlSomaValores);
	      
	      if ( $rsSomaValores ) {
		      	
	      	$iLinhasSomaValores = pg_num_rows($rsSomaValores);
	      	
	      	if ( $iLinhasSomaValores > 0 ) {
	      		$oValorExistente = db_utils::fieldsMemory($rsSomaValores,0);
	      		$iQuantTotal = $oDadosPonto->r90_quant + $oValorExistente->quant;
	      		$nValorTotal = $oDadosPonto->r90_valor + $oValorExistente->valor;
	      	} else {
            $iQuantTotal = $oDadosPonto->r90_quant;
            $nValorTotal = $oDadosPonto->r90_valor;		      		
	      	}
		      	
	      } else {
	      	throw new Exception('Erro na consulta de valores existentes!');
	      }
		      
     	} else {
      		
         $iQuantTotal = $oDadosPonto->r90_quant;
         $nValorTotal = $oDadosPonto->r90_valor;
                      
     	}
      	
      	
      // Ponto Fixo
        
      if ( $sTipoPonto == "fx" ) {
          
        $oDaoPontoFx->r90_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoFx->r90_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoFx->r90_regist = $oDadosPonto->r90_regist;
        $oDaoPontoFx->r90_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoFx->r90_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoFx->r90_quant  = $iQuantTotal;
        $oDaoPontoFx->r90_lotac  = $oDadosPonto->r90_lotac;    
        $oDaoPontoFx->r90_datlim = $oDadosPonto->r90_datlim;
        $oDaoPontoFx->r90_instit = db_getsession("DB_instit");
         
        $oDaoPontoFx->alterar( $oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                 
        if ( $oDaoPontoFx->erro_status == '0' ){
          throw new Exception($oDaoPontoFx->erro_msg);
        }

          
        
      // Ponto de Sal�rio
        
      } else if ( $sTipoPonto == "fs" ) {
    
        $oDaoPontoFs->r10_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoFs->r10_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoFs->r10_regist = $oDadosPonto->r90_regist;
        $oDaoPontoFs->r10_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoFs->r10_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoFs->r10_quant  = $iQuantTotal;
        $oDaoPontoFs->r10_lotac  = $oDadosPonto->r90_lotac;    
        $oDaoPontoFs->r10_datlim = $oDadosPonto->r90_datlim;
        $oDaoPontoFs->r10_instit = db_getsession("DB_instit");
          
        $oDaoPontoFs->alterar($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric);
                                
        if ( $oDaoPontoFs->erro_status == '0' ){
          throw new Exception($oDaoPontoFs->erro_msg);
        }
          
          
      // Ponto de Adiantamento
        
      } else if ($sTipoPonto == "fa" ) {
          
        $oDaoPontoFa->r21_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoFa->r21_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoFa->r21_regist = $oDadosPonto->r90_regist;
        $oDaoPontoFa->r21_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoFa->r21_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoFa->r21_quant  = $iQuantTotal;
        $oDaoPontoFa->r21_lotac  = $oDadosPonto->r90_lotac;    
        $oDaoPontoFa->r21_instit = db_getsession("DB_instit");
          
        $oDaoPontoFa->alterar($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric);
          
        if ( $oDaoPontoFa->erro_status == '0' ){
          throw new Exception($oDaoPontoFa->erro_msg);
        }
          
          
      // Ponto de F�rias
        
      } else if ( $sTipoPonto == "fe" ){
          
        $oDaoPontoFe->r29_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoFe->r29_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoFe->r29_regist = $oDadosPonto->r90_regist;
        $oDaoPontoFe->r29_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoFe->r29_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoFe->r29_quant  = $iQuantTotal;
        $oDaoPontoFe->r29_lotac  = $oDadosPonto->r90_lotac;
        $oDaoPontoFe->r29_media  = "0";
        $oDaoPontoFe->r29_calc   = "0";
        $oDaoPontoFe->r29_tpp    = $oDadosPonto->r29_tpp;
        $oDaoPontoFe->r29_instit = db_getsession("DB_instit");
          
        $oDaoPontoFe->alterar($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric,
                              $oDadosPonto->r29_tpp);
                               
        if ( $oDaoPontoFe->erro_status == '0' ){
        	throw new Exception($oDaoPontoFe->erro_msg);
        }
          
          
      // Ponto de Rescis�o
        
      } else if ( $sTipoPonto == "fr" ){
          
        $oDaoPontoFr->r19_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoFr->r19_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoFr->r19_regist = $oDadosPonto->r90_regist;
        $oDaoPontoFr->r19_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoFr->r19_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoFr->r19_quant  = $iQuantTotal;
        $oDaoPontoFr->r19_lotac  = $oDadosPonto->r90_lotac;
        $oDaoPontoFr->r19_tpp    = $oDadosPonto->r29_tpp;
        $oDaoPontoFr->r19_instit = db_getsession("DB_instit");
          
        $oDaoPontoFr->alterar($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric,
                              $oDadosPonto->r29_tpp);
                                
        if ( $oDaoPontoFr->erro_status == '0' ){
        	throw new Exception($oDaoPontoFr->erro_msg);
        }

          
      // Ponto de 13�
        
      } else if( $sTipoPonto == "f13" ){
          
        $oDaoPontoF13->r34_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoF13->r34_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoF13->r34_regist = $oDadosPonto->r90_regist;
        $oDaoPontoF13->r34_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoF13->r34_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoF13->r34_quant  = $iQuantTotal;
        $oDaoPontoF13->r34_lotac  = $oDadosPonto->r90_lotac;
        $oDaoPontoF13->r34_media  = "0";
        $oDaoPontoF13->r34_calc   = "0";
        $oDaoPontoF13->r34_instit = db_getsession("DB_instit");
          
        $oDaoPontoF13->alterar($oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                
        if ( $oDaoPontoF13->erro_status == '0' ){
        	throw new Exception($oDaoPontoF13->erro_msg);
        }
          
          
      // Ponto Complementar
        
      } else if( $sTipoPonto == "com" ){
          
        $oDaoPontoCom->r47_anousu = $oDadosPonto->r90_anousu;
        $oDaoPontoCom->r47_mesusu = $oDadosPonto->r90_mesusu;
        $oDaoPontoCom->r47_regist = $oDadosPonto->r90_regist;
        $oDaoPontoCom->r47_rubric = $oDadosPonto->r90_rubric;
        $oDaoPontoCom->r47_valor  = db_formatar($nValorTotal,'p');
        $oDaoPontoCom->r47_quant  = $iQuantTotal;
        $oDaoPontoCom->r47_lotac  = $oDadosPonto->r90_lotac;
        $oDaoPontoCom->r47_instit = db_getsession("DB_instit");
          
        $oDaoPontoCom->alterar($oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                 
        if ( $oDaoPontoCom->erro_status == '0' ){
          throw new Exception($oDaoPontoCom->erro_msg);
        }
      }
    }
    
  }

  
  /**
   * Exclus�o do ponto de acordo com os par�metros informados :
   * fx  = Ponto Fixo;
   * fs  = Ponto de Sal�rio
   * fa  = Ponto de Adiantamento
   * fe  = Ponto de F�rias
   * fr  = Ponto de Rescis�o
   * f13 = Ponto de 13�
   * com = Ponto Complementar
   * 
   * @param string $sTipoPonto
   * @param array  $aObjDadosPonto
   */
  public function excluiRubricaPonto($sTipoPonto='',$aObjDadosPonto=null){

    if ( !db_utils::inTransaction() ){
      throw new Exception("Exclus�o abortada, nenhuma transa��o encontrada!");
    }     	
  	
    if ( $sTipoPonto == '' ) {
      throw new Exception("Exclus�o abortada, tipo de ponto n�o informado!");
    }
    
    if ( empty($aObjDadosPonto) ) {
      throw new Exception("Exclus�o abortada, dados n�o informados!");      
    }
    
    
    $oDaoPontoFx    = db_utils::getDao("pontofx");
    $oDaoPontoFs    = db_utils::getDao("pontofs");
    $oDaoPontoFa    = db_utils::getDao("pontofa");
    $oDaoPontoFe    = db_utils::getDao("pontofe");
    $oDaoPontoFr    = db_utils::getDao("pontofr");
    $oDaoPontoF13   = db_utils::getDao("pontof13");
    $oDaoPontoCom   = db_utils::getDao("pontocom");

    foreach ( $aObjDadosPonto as $iInd => $oDadosPonto ) {
      
      // Ponto Fixo
        
      if ( $sTipoPonto == "fx" ) {
          
        $oDaoPontoFx->excluir( $oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                 
        if ( $oDaoPontoFx->erro_status == '0' ){
          throw new Exception($oDaoPontoFx->erro_msg);
        }

          
        
      // Ponto de Sal�rio
      
      } else if ( $sTipoPonto == "fs" ) {
    
        $oDaoPontoFs->excluir($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric);
                                
        if ( $oDaoPontoFs->erro_status == '0' ){
          throw new Exception($oDaoPontoFs->erro_msg);  
        }
          
          
      // Ponto de Adiantamento
        
      } else if ($sTipoPonto == "fa" ) {
          
        $oDaoPontoFa->exlcuir($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric);
                                
        if ( $oDaoPontoFa->erro_status == '0' ){
          throw new Exception($oDaoPontoFa->erro_msg);
        }
          
          
      // Ponto de F�rias
        
      } else if ( $sTipoPonto == "fe" ){
         
        $oDaoPontoFe->excluir($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric,
                              $oDadosPonto->r29_tpp);
                                
        if( $oDaoPontoFe->erro_status == '0' ){
          throw new Exception($oDaoPontoFe->erro_msg);
        }
          
          
      // Ponto de Rescis�o
        
      } else if ( $sTipoPonto == "fr" ){
          
        $oDaoPontoFr->excluir($oDadosPonto->r90_anousu,
                              $oDadosPonto->r90_mesusu,
                              $oDadosPonto->r90_regist,
                              $oDadosPonto->r90_rubric,
                              $oDadosPonto->r29_tpp);
                                
        if( $oDaoPontoFr->erro_status == 0 ){
        	throw new Exception($oDaoPontoFr->erro_msg);
        }

          
      // Ponto de 13�
        
      } else if( $sTipoPonto == "f13" ){
          
        	
        $oDaoPontoF13->excluir($oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                
        if( $oDaoPontoF13->erro_status == 0 ){
        	throw new Exception($oDaoPontoF13->erro_msg);
        }
          
          
      // Ponto Complementar
        
      } else if( $sTipoPonto == "com" ){
          
        $oDaoPontoCom->excluir($oDadosPonto->r90_anousu,
                               $oDadosPonto->r90_mesusu,
                               $oDadosPonto->r90_regist,
                               $oDadosPonto->r90_rubric);
                                 
        if( $oDaoPontoCom->erro_status == 0 ){
        	throw new Exception($oDaoPontoCom->erro_msg);
        }
      }
    }
  }  
  
  
  /**
   * Retorna todas as rubricas cadastradas apartir das informa��es passadas por par�metro
   *
   * @param string  $sTipoPonto
   * @param integer $iMatric
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iInstit
   * @param string  $sRubric
   * @param string  $sTpp
   * @return array
   */
  public function getRubricasPonto($sTipoPonto='',$iMatric='',$iAnoFolha='',$iMesFolha='',$iInstit='',$sRubric='',$sTpp=''){
  	
  	if ( trim($iMatric) == '' ) {
      throw new Exception("Consulta de rubricas cadastradas abortada, matr�cula n�o informada!");
  	}
    if ( trim($iAnoFolha) == '' ) {
      $iAnoFolha = db_anofolha();
    }
    if ( trim($iMesFolha) == '' ) {
      $iMesFolha = db_mesfolha();
    } 
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }     
  	
    $oDaoPontoFx    = db_utils::getDao("pontofx");
    $oDaoPontoFs    = db_utils::getDao("pontofs");
    $oDaoPontoFa    = db_utils::getDao("pontofa");
    $oDaoPontoFe    = db_utils::getDao("pontofe");
    $oDaoPontoFr    = db_utils::getDao("pontofr");
    $oDaoPontoF13   = db_utils::getDao("pontof13");
    $oDaoPontoCom   = db_utils::getDao("pontocom");
     
    $aListaRubricas = array();
    
    if($sTipoPonto == "fx"){
      $sSigla      = "r90_";
      $sCampoExtra = ", r90_datlim as r90_datlim ";
    } else if($sTipoPonto == "fs"){
      $sSigla      = "r10_";
      $sCampoExtra = ", r10_datlim as r90_datlim ";
    } else if($sTipoPonto == "fa"){
      $sSigla      = "r21_";
      $sCampoExtra = "";
    } else if($sTipoPonto == "fe"){
      $sSigla      = "r29_";
      $sCampoExtra = ", r29_tpp";
    } else if($sTipoPonto == "fr"){
      $sSigla      = "r19_";
      $sCampoExtra = ", r19_tpp as r29_tpp";
    } else if($sTipoPonto == "f13"){
      $sSigla      = "r34_";
      $sCampoExtra = "";
    } else if($sTipoPonto == "com"){
      $sSigla      = "r47_";
      $sCampoExtra = "";
    }
    
    $sCampos  = "{$sSigla}anousu as r90_anousu, ";  
    $sCampos .= "{$sSigla}mesusu as r90_mesusu, "; 
    $sCampos .= "{$sSigla}regist as r90_regist, "; 
    $sCampos .= "{$sSigla}rubric as r90_rubric  ";
    $sCampos .= "{$sCampoExtra},                "; 
    $sCampos .= "rh27_descr,                    ";
    $sCampos .= "rh27_limdat,                   ";  
    $sCampos .= "rh27_tipo,                     ";
    $sCampos .= "{$sSigla}quant  as r90_quant,  "; 
    $sCampos .= "{$sSigla}valor  as r90_valor   ";
              
    $sOrderBy  = "{$sSigla}regist, "; 
    $sOrderBy .= "{$sSigla}rubric  ";
    
    if( $sTipoPonto == "fx" ){
      $sSqlPonto = $oDaoPontoFx->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sCampos,$sOrderBy);
    } else if ( $sTipoPonto == "fs" ){
      $sSqlPonto = $oDaoPontoFs->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sCampos,$sOrderBy);
    } else if($sTipoPonto   == "fa" ){
      $sSqlPonto = $oDaoPontoFa->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sCampos,$sOrderBy);
    } else if($sTipoPonto   == "fe" ){
      $sSqlPonto = $oDaoPontoFe->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sTpp,$sCampos,$sOrderBy);
    } else if($sTipoPonto   == "fr" ){
      $sSqlPonto = $oDaoPontoFr->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sTpp,$sCampos,$sOrderBy);
    } else if($sTipoPonto   == "f13"){
      $sSqlPonto = $oDaoPontoF13->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sCampos,$sOrderBy);
    } else if($sTipoPonto   == "com"){
      $sSqlPonto = $oDaoPontoCom->sql_query_seleciona($iAnoFolha,$iMesFolha,$iMatric,$sRubric,$sCampos,$sOrderBy);
    }
    
    $rsPonto = db_query($sSqlPonto);
    
    if ($rsPonto) {
      
      $iLinhasPonto = pg_num_rows($rsPonto);
      
      if ( $iLinhasPonto > 0 ) {
      	
      	for ( $iInd=0; $iInd < $iLinhasPonto; $iInd++ ) {
      		
      		$oRubrica = db_utils::fieldsMemory($rsPonto,$iInd);
      		
//      		$oRetornoRubrica = new stdClass();
//      		$oRetornoRubrica->iAnoUsu      = $oRubrica->ano; 
//      		$oRetornoRubrica->iMesUsu      = $oRubrica->mes;
//      		$oRetornoRubrica->iMatric      = $oRubrica->regist;
//      		$oRetornoRubrica->sRubric      = $oRubrica->rubric;
//      		$oRetornoRubrica->sDescrRubric = $oRubrica->descrrubric;
//      		$oRetornoRubrica->nQuant       = $oRubrica->quant;
//      		$oRetornoRubrica->nValor       = $oRubrica->valor;
//      		
//      		if ( $sTipoPonto == 'fx' || $sTipoPonto == 'fs' ) {
//      		  $oRetornoRubrica->r90_datlim = $oRubrica->r90_datlim;
//      		}
//          $aListaRubricas[] = $oRetornoRubrica;		

      		$aListaRubricas[] = $oRubrica;
      		
      	}
      }
      
    } else {
      throw new Exception('Erro na cosulta das rubricas!');
    }
  	
    return $aListaRubricas;
    
  }
  
  
  /**
   * Verifica se j� existe registros na tabela do ponto de acordo com os par�metros informados :
   * fx  = Ponto Fixo;
   * fs  = Ponto de Sal�rio
   * fa  = Ponto de Adiantamento
   * fe  = Ponto de F�rias
   * fr  = Ponto de Rescis�o
   * f13 = Ponto de 13�
   * com = Ponto Complementar
   * 
   * @param string $sTipoPonto
   * @param array  $aObjDadosPonto
   * @return bool
   */
  public function verificaRubrica($sTipoPonto='',$aObjDadosPonto=null){
  	
    if ( $sTipoPonto == '' ) {
      throw new Exception("Valida��o abortada, tipo de ponto n�o informado!");
    }
    
    if ( empty($aObjDadosPonto) ) {
      throw new Exception("Valida��o abortada, dados n�o informados!");      
    }
    
    $oDaoPontoFx    = db_utils::getDao("pontofx");
    $oDaoPontoFs    = db_utils::getDao("pontofs");
    $oDaoPontoFa    = db_utils::getDao("pontofa");
    $oDaoPontoFe    = db_utils::getDao("pontofe");
    $oDaoPontoFr    = db_utils::getDao("pontofr");
    $oDaoPontoF13   = db_utils::getDao("pontof13");
    $oDaoPontoCom   = db_utils::getDao("pontocom");
    
    foreach ( $aObjDadosPonto as $iInd => $oDadosPonto ) {
		  if( $sTipoPonto == "fx" ){
		    $sSqlPonto = $oDaoPontoFx->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"*");
		  } else if ( $sTipoPonto == "fs" ){
		    $sSqlPonto = $oDaoPontoFs->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"*");
		  } else if($sTipoPonto   == "fa" ){
		    $sSqlPonto = $oDaoPontoFa->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"*");
		  } else if($sTipoPonto   == "fe" ){
		    $sSqlPonto = $oDaoPontoFe->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,$oDadosPonto->r29_tpp,"*");
		  } else if($sTipoPonto   == "fr" ){
		    $sSqlPonto = $oDaoPontoFr->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,$oDadosPonto->r29_tpp,"*");
		  } else if($sTipoPonto   == "f13"){
		    $sSqlPonto = $oDaoPontoF13->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"*");
		  } else if($sTipoPonto   == "com"){
		    $sSqlPonto = $oDaoPontoCom->sql_query_seleciona($oDadosPonto->r90_anousu,$oDadosPonto->r90_mesusu,$oDadosPonto->r90_regist,$oDadosPonto->r90_rubric,"*");
		  }
    }
    
	  $rsPonto = db_query($sSqlPonto);
	  
	  if ( $rsPonto) {
	    
	    $iLinhasPonto = pg_num_rows($rsPonto);
	    
	    if ( $iLinhasPonto > 0 ) {
	      return true;
	    } else {
	    	return false;
	    }
	    
	  } else {
	  	throw new Exception('Erro na valida��o da rubricas!');
	  }
  	
  }
  
  
  /**
   * Repassa valores definidos do ponto fixo para o sal�rio e vice-versa
   *
   * @param string $sTipoPonto
   * @param array  $aObjDadosPonto
   * @param date   $dtDataAdmissao
   */
  public function repassarValoresFixoSalario($sTipoPonto='',$aObjDadosPonto=null,$dtDataAdmissao=''){
  	
  	
    if ( !db_utils::inTransaction() ){
      throw new Exception("Repasse abortado, nenhuma transa��o encontrada!");
    }         	
  	
    if ( $sTipoPonto == '' ) {
      throw new Exception("Repasse abortado, tipo de ponto n�o informado!");
    }
    
    if ( empty($aObjDadosPonto) ) {
      throw new Exception("Repasse abortado, dados n�o informados!");      
    }
    
    if ( $sTipoPonto == 'fx') {
      $sTipoPontoDestino = 'fs';
    } else if ( $sTipoPonto == 'fs') {
      $sTipoPontoDestino = 'fx';
    } else {  
      throw new Exception("Repasse abortado, tipo do ponto informado difere de Sal�rio e Fixo!");
    }
    
    
    $oDaoRhrubricas = db_utils::getDao("rhrubricas");
    $oDaoPontoFx    = db_utils::getDao("pontofx");
    $oDaoPontoFs    = db_utils::getDao("pontofs");

    try {
      $aObjPonto = $this->validaCamposObj($aObjDadosPonto);
    } catch ( Exception $eException ){
      throw new Exception($eException->getMessage());       
    }
      
    foreach ( $aObjPonto as $iInd => $oDadosPonto ) {
    
	    try {
	    	$aDadosPonto   = array($oDadosPonto);
	      $lExiteValores = $this->verificaRubrica($sTipoPontoDestino,$aDadosPonto);
	    } catch ( Exception $eException ){
	      throw new Exception($eException->getMessage());       
	    }
	    
	    if ( $lExiteValores ) {
	      $sMetodo = 'alterar'; 
	    } else {
	      $sMetodo = 'incluir';
	    }    	 
    	
	    if ( $sTipoPontoDestino == 'fx') {
	      
	    	$oDaoPontoFx->r90_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoFx->r90_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoFx->r90_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoFx->r90_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoFx->r90_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoFx->r90_quant  = db_formatar($oDadosPonto->r90_quant,'p');
	      $oDaoPontoFx->r90_lotac  = $oDadosPonto->r90_lotac;    
	      $oDaoPontoFx->r90_datlim = $oDadosPonto->r90_datlim;
	      $oDaoPontoFx->r90_instit = db_getsession("DB_instit");
	         
	      $oDaoPontoFx->$sMetodo($oDadosPonto->r90_anousu,
	                             $oDadosPonto->r90_mesusu,
	                             $oDadosPonto->r90_regist,
	                             $oDadosPonto->r90_rubric);
	                                 
	      if ( $oDaoPontoFx->erro_status == '0' ){
	        throw new Exception($oDaoPontoFx->erro_msg);
	      }    	

	      
	    } else if ( $sTipoPontoDestino == 'fs' ) {
	    	
	    	
		    $sWhereRubricaFormula   = "     rh27_instit = ".db_getsession("DB_instit");
		    $sWhereRubricaFormula  .= " and rh27_rubric = '{$oDadosPonto->r90_rubric}'";
		      
		    $sSqlRubricaFormula     = $oDaoRhrubricas->sql_query_file(null,null,'rh27_propq',null,$sWhereRubricaFormula);
		    $rsRubricaFormula       = $oDaoRhrubricas->sql_record($sSqlRubricaFormula);    
	    	
		    if ( $rsRubricaFormula ) {
		    	$oRubricaFormula = db_utils::fieldsMemory($rsRubricaFormula,0);
		    } else {
		    	throw new Exception("Repasse abortado, Erro na consultada da rubrica!");
		    }
		    
	      if ( $oRubricaFormula->rh27_propq == 't' ) {
	      	
	      	if ( trim($dtDataAdmissao) == '') {
	      		throw new Exception("Repasse abortado, Data de Admiss�o n�o informada!");
	      	}
	      	
	      	list($iAnoAdm,$iMesAdm,$iDiaAdm) = split("-",$dtDataAdmissao);
	      	--$iDiaAdm;
	      	
	        if( $iMesAdm == db_mesfolha() && $iAnoAdm == db_anofolha() ){
	          
	        	// S� proporcionalizar na admissao se a rubrica estiver assim definida
	          
	          $iDiaRecebeMens = 30;
	          $iDiasPagar     = $iDiaRecebeMens - $iDiaAdm;
	
	          if( trim($oDadosPonto->r90_quant) != '' ){
	            $oDadosPonto->r90_quant = ($oDadosPonto->r90_quant / 30) * $iDiasPagar;
	          } else {
	          	$oDadosPonto->r90_quant = 0;
	          }
	          if( trim($oDadosPonto->r90_valor) != '' ){
	            $oDadosPonto->r90_valor = ($oDadosPonto->r90_valor / 30) * $iDiasPagar;
	          } else {
	          	$oDadosPonto->r90_valor = 0;
	          }
	        }
	      }
	    	
	    	
	      $oDaoPontoFs->r10_anousu = $oDadosPonto->r90_anousu;
	      $oDaoPontoFs->r10_mesusu = $oDadosPonto->r90_mesusu;
	      $oDaoPontoFs->r10_regist = $oDadosPonto->r90_regist;
	      $oDaoPontoFs->r10_rubric = $oDadosPonto->r90_rubric;
	      $oDaoPontoFs->r10_valor  = db_formatar($oDadosPonto->r90_valor,'p');
	      $oDaoPontoFs->r10_quant  = db_formatar($oDadosPonto->r90_quant,'p');
	      $oDaoPontoFs->r10_lotac  = $oDadosPonto->r90_lotac;    
	      $oDaoPontoFs->r10_datlim = $oDadosPonto->r90_datlim;
	      $oDaoPontoFs->r10_instit = db_getsession("DB_instit");
	          
	      $oDaoPontoFs->$sMetodo($oDadosPonto->r90_anousu,
	                             $oDadosPonto->r90_mesusu,
	                             $oDadosPonto->r90_regist,
	                             $oDadosPonto->r90_rubric);
	                                
	      if( $oDaoPontoFs->erro_status == "0" ){
	      	throw new Exception($oDaoPontoFs->erro_msg);
	      }    	
	      
	    } else {
	    	throw new Exception("Repasse abortado, tipo de ponto informado difere de sal�rio e fixo!");
	    }
    }
    
  }
  
  
  /**
   * Retorna as rubricas de acordo com as sele��es configuradas
   *
   * @param  integer $iMatric
   * @return array
   */
  public function getRubricasAutomaticas($iMatric='',$iInstit=''){
   
  	if ( trim($iMatric) == '' ) {
      throw new Exception("Consulta de rubricas de autom�ticas abortada,nenhuma matr�cula informada!");  		
  	}
  	
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');     
    }  	
  	
  	$oDaoSelecaoPonto         = db_utils::getDao("selecaoponto");
  	$oDaoSelecaoPontoRubricas = db_utils::getDao("selecaopontorubricas");
  	
  	$aListaRubricas     = array();
  	
  	$sWhereSelecoes     = "r44_instit = {$iInstit}";
  	$sSqlSelecoes       = $oDaoSelecaoPonto->sql_query(null,"r72_sequencial,r44_where",null,$sWhereSelecoes);
  	$rsConsultaSelecoes = $oDaoSelecaoPonto->sql_record($sSqlSelecoes); 
  	
  	if ( $rsConsultaSelecoes ) {
  		
  		$iLinhasSelecoes = $oDaoSelecaoPonto->numrows;
  		
  		if ( $iLinhasSelecoes > 0 ) {

  			for ( $iInd=0; $iInd < $iLinhasSelecoes; $iInd++ ) {
  				
  			  $oSelecao = db_utils::fieldsMemory($rsConsultaSelecoes,$iInd);
  			  
					$sSqlVerificaSelecao  = " select *                                                          ";
					$sSqlVerificaSelecao .= "   from rhpessoal                                                  ";
					$sSqlVerificaSelecao .= "        inner join cgm             on rh01_numcgm = z01_numcgm     ";
					$sSqlVerificaSelecao .= "        inner join rhpessoalmov    on rh01_regist = rh02_regist    ";
					$sSqlVerificaSelecao .= "                                  and rh02_anousu = ".db_anofolha();
					$sSqlVerificaSelecao .= "                                  and rh02_mesusu = ".db_mesfolha();
					$sSqlVerificaSelecao .= "        left  join rhpeslocaltrab  on rh02_seqpes = rh56_seqpes    ";
					$sSqlVerificaSelecao .= "                                  and rh56_princ  = true           ";
					$sSqlVerificaSelecao .= "        left join rhlocaltrab      on rh56_localtrab = rh55_codigo ";
					$sSqlVerificaSelecao .= "        inner join rhlota          on r70_codigo  = rh02_lota      ";
					$sSqlVerificaSelecao .= "        left  join rhlotaexe       on rh26_codigo = r70_codigo     ";
					$sSqlVerificaSelecao .= "                                  and rh26_anousu = rh02_anousu    ";
					$sSqlVerificaSelecao .= "        left  join orcorgao        on o40_anousu  = rh26_anousu    ";
					$sSqlVerificaSelecao .= "                                  and o40_orgao   = rh26_orgao     ";
					$sSqlVerificaSelecao .= "        inner join rhregime        on rh02_codreg = rh30_codreg    ";
					$sSqlVerificaSelecao .= "                                  and rh30_instit = rh02_instit    ";
					$sSqlVerificaSelecao .= "        left  join rhpesbanco      on rh44_seqpes = rh02_seqpes    ";
					$sSqlVerificaSelecao .= "        left join rhpespadrao      on rh03_seqpes = rh02_seqpes    ";
  			  $sSqlVerificaSelecao .= "  where rh01_regist = {$iMatric}                                   ";
  			  
  			  if ( trim($oSelecao->r44_where) != '') {
  			    $sSqlVerificaSelecao .= "  and {$oSelecao->r44_where}                                     ";
  			  }
          
  			  $rsVerificaSelecao = db_query($sSqlVerificaSelecao);

  			  if ( $rsVerificaSelecao ) {

  			  	$iLinhasVerificaSelecao = pg_num_rows($rsVerificaSelecao);
  			  	
  			  	if ( $iLinhasVerificaSelecao > 0 ) {
  			  		
  			  		$sCamposRubricas      = "r73_rubric, ";
  			  		$sCamposRubricas     .= "r73_instit, ";
  			  		$sCamposRubricas     .= "r73_tipo,   ";
  			  		$sCamposRubricas     .= "r73_valor   ";
  			  		$sWhereRubricas       = "r73_selecaoponto = {$oSelecao->r72_sequencial}";
  			  		$sSqlConsultaRubricas = $oDaoSelecaoPontoRubricas->sql_query_file(null,$sCamposRubricas,null,$sWhereRubricas);
  			  		$rsConsultaRubricas   = db_query($sSqlConsultaRubricas);
  			  		
  			  		
  			  		if ( $rsConsultaRubricas ) {
  			  			
  			  			$iLinhasRubricas = pg_num_rows($rsConsultaRubricas);
  			  			 
  			  			for ( $iIndRubricas=0; $iIndRubricas < $iLinhasRubricas; $iIndRubricas++ ) {
  			  				$oRubrica = db_utils::fieldsMemory($rsConsultaRubricas,$iIndRubricas);
  			  				$aListaRubricas[] = $oRubrica;
  			  			}
  			  			 
  			  		} else {
                throw new Exception("Consulta de rubricas de autom�ticas abortada, erro na consulta das rubricas!");  			  			
  			  		}
  			  	}
  			  } else {
            throw new Exception("Consulta de rubricas de autom�ticas abortada, erro no teste das consultas!");  			  	
  			  }
  			}
  		}
  	} else {
  	  throw new Exception("Consulta de rubricas de autom�ticas abortada, erro na consulta de sele��es!");
  	}

  	return $aListaRubricas;
  	
  }
  
  
  /**
   * Inclui rubricas autom�ticas e repassa valores conforme par�metros informados
   *
   * @param string  $sTipoPonto
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iMatric
   * @param integer $iLotac
   * @param integer $iInstit
   * @param array   $aDadosSelecaoPonto
   * @param boolean $lRepasse
   * @param date    $dtDataAdmissao
   */
  public function incluiRubricasAutomaticas($sTipoPonto='',$iAnoFolha='',$iMesFolha='',$iMatric='',$iLotac='',$iInstit='',$aDadosSelecaoPonto='',$lRepasse=false,$dtDataAdmissao=''){
  	
    if ( trim($sTipoPonto) == '' ) {
    	throw new Exception('Inclus�o de rubricas autom�ticas abortada, tipo de ponto n�o informado!');
    }
    if ( trim($iMatric) == '' ) {
      throw new Exception('Inclus�o de rubricas autom�ticas abortada, matr�cula n�o informada!');
    }
    if ( trim($iLotac) == '' ) {
      throw new Exception('Inclus�o de rubricas autom�ticas abortada, lota��o n�o informada!');
    }         	
    if ( empty($aDadosSelecaoPonto) ) {
      throw new Exception('Inclus�o de rubricas autom�ticas abortada, dados do ponto n�o informado!');
    }   
    if ( trim($iAnoFolha) == '' ) {
      $iAnoFolha = db_anofolha();
    }   
    if ( trim($iMesFolha) == '' ) {
      $iMesFolha = db_mesfolha();
    }       
    if ( trim($iInstit) == '' ) {
      $iInstit   = db_getsession('DB_instit');
    }           
  	
    if ( $lRepasse ) {
	    if ( trim($dtDataAdmissao) == '' ) {
	      throw new Exception('Inclus�o de rubricas autom�ticas abortada, data de amiss�o para repasse n�o informada!');
	    }               	
    }
    
  	$oDaoRHPessoalMov = db_utils::getDao("rhpessoalmov");
    $aListaRubricas   = array();
  	
    foreach ( $aDadosSelecaoPonto as $iInd => $oRubricaAutomatica ){
    
	    $oDadosRubrica = new stdClass();
	    
	    $oDadosRubrica->r90_anousu = $iAnoFolha; 
	    $oDadosRubrica->r90_mesusu = $iMesFolha;
	    $oDadosRubrica->r90_regist = $iMatric;
	    $oDadosRubrica->r90_lotac  = $iLotac;
	    $oDadosRubrica->r90_instit = $iInstit;
	    $oDadosRubrica->r90_rubric = $oRubricaAutomatica->r73_rubric;
	
	    if ( $oRubricaAutomatica->r73_tipo == 1 ) {
	    	
	      $oDadosRubrica->r90_valor = $oRubricaAutomatica->r73_valor;
	      $oDadosRubrica->r90_quant = '0';
	      
	    } else if ( $oRubricaAutomatica->r73_tipo == 2 ) {
	    	
	      $oDadosRubrica->r90_quant = $oRubricaAutomatica->r73_valor;
	      $oDadosRubrica->r90_valor = '0';
	      
	    } else if ( $oRubricaAutomatica->r73_tipo == 3 ){
	    	
	      $sWhereHrsMen  = "     rh02_regist = {$iMatric}"; 
	      $sWhereHrsMen .= " and rh02_anousu = {$iAnoFolha}"; 
	      $sWhereHrsMen .= " and rh02_mesusu = {$iMesFolha}";
	      $sSqlHrsMen    = $oDaoRHPessoalMov->sql_query_file(null,null,'rh02_hrsmen',null,$sWhereHrsMen);
	      $rsHrsMen      = $oDaoRHPessoalMov->sql_record($sSqlHrsMen);	    	
	      
	      if ( $oDaoRHPessoalMov->numrows > 0 ) {
	      	
	      	$oHorasMen = db_utils::fieldsMemory($rsHrsMen,0);
	        $oDadosRubrica->r90_quant = $oHorasMen->rh02_hrsmen;
	        $oDadosRubrica->r90_valor = '0';	      	
	      	
	      } else {
   	      throw new Exception("Nenhum registro encontrado para o ano {$iAnoFolha} e m�s {$iMesFolha}, verifique movimenta��es!");
	      }
	      
	    } else {
        throw new Exception("Inclus�o de rubricas autom�ticas abortada. Tipo inv�lido!");	    	
	    }
	
	    if ( $sTipoPonto == 'fx' || $sTipoPonto == 'fs' ) {
	      $oDadosRubrica->r90_datlim = '';
	    }
	
	    $aDadosRubrica = array($oDadosRubrica);
	    
      try {
        $lExisteValores = $this->verificaRubrica($sTipoPonto,$aDadosRubrica);
      } catch (Exception $eException) {
        throw new Exception($eException->getMessage());
      }	    
	    
	    if ( $lExisteValores ) {
			  try {
			  	$this->alteraRubricaPonto($sTipoPonto,$aDadosRubrica);
			  } catch (Exception $eException) {
			  	throw new Exception($eException->getMessage());
			  }
	    } else {
        try {
          $this->incluiRubricaPonto($sTipoPonto,$aDadosRubrica);
        } catch (Exception $eException) {
          throw new Exception($eException->getMessage());
        }	    	
	    }
	    
	   $aListaRubricas[] = $oDadosRubrica; 
	   
	  }
  	
    if ( $lRepasse ) {
    
	    if ( !empty($aListaRubricas) ) {
	      try {
	        $this->repassarValoresFixoSalario($sTipoPonto,$aListaRubricas,$dtDataAdmissao);
	      } catch ( Exception $eException ){
	        throw new Exception($eException->getMessage());
	      }
	    }
	    
    }
         
  }	  
  
  
}

?>