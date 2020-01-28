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

  
/**
 * Gera os dados necess�rios para a cria��o dos empenhos da folha 
 */
class dadosEmpenhoFolhaRescisao {
  
  function __construct() {

  }

  /**
   * Gera os dados para empenho apartir da rubricas < R950 ( Sal�rio ) 
   *
   * @param string  $sSigla    Tipo de Folha ( Ex: Sal�rio, F�rias, Complementar, etc. ) 
   * @param integer $iAnoUsu   Exerc�cio da folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   */
  public function geraDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null){
  	
  	$sMsgErro = 'Gera��o de empenhos abortada';
  	
  	if ( !db_utils::inTransaction() ){
  		throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
  	}

  	require_once("libs/db_sql.php");
  	
  	if ( trim($sSigla) == '' ) {
  		throw new Exception("{$sMsgErro}, sigla n�o informada!");
  	}
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }  	
  	if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);
  	
    
    try {
	    $lLiberada = $this->isLiberada($sSigla,
	                                   1,
	                                   $iAnoUsu,
	                                   $iMesUsu,
	                                   $aListaRescisoes); 
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}, {$eException->getMessage()}");
	  }    
    
	  if ( $lLiberada ) {
	  	throw new Exception("{$sMsgErro}, empenho liberado!");
	  }
    
    $oDaorhEmpenhoFolha             = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica      = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica = db_utils::getDao('rhempenhofolharhemprubrica');
  	$clgeradorsql                   = new cl_gera_sql_folha;
  	
  	
    $clgeradorsql->inicio_rh = false;
    $clgeradorsql->usar_pes  = true;
	  $clgeradorsql->usar_doc  = true;
	  $clgeradorsql->usar_cgm  = true;
	  $clgeradorsql->usar_atv  = true;
	  $clgeradorsql->usar_rel  = true;
	  $clgeradorsql->usar_lot  = true;

	  
	  
    /**
     *  Consulta todos registros inferiores a R950 apartir dos par�metros informados 
     */
    $sCampos  = " distinct rh02_regist as regist,";
    $sCampos .= "          rh30_vinculo       as vinculo, ";
    $sCampos .= "          rh02_seqpes, ";
    $sCampos .= "          rh02_lota          as lotacao, ";
    $sCampos .= "          r70_concarpeculiar as caract,  ";
    $sCampos .= "          rh23_codele        as elemento ";

    
    $sWhereGerador  = "     rh23_codele is not null   ";
    $sWhereGerador .= " and {$sSigla}_pd != 3         ";
    $sWhereGerador .= " and rh02_seqpes in ({$sListaRescisoes}) ";


    
		$sSqlGerador    = $clgeradorsql->gerador_sql( $sSigla,
		  			                                     $iAnoUsu,
						                                     $iMesUsu,
						                                     "",
						                                     "",
						                                     $sCampos,
																                 "rh02_lota,rh23_codele",
						                                     $sWhereGerador,
						                                     $iInstit);
		$rsGerador = db_query($sSqlGerador);				                                     
			
		if ( $rsGerador ) {
				
			$iLinhasGerador = pg_num_rows($rsGerador);
				
			if ( $iLinhasGerador > 0 ) {
					
				for ( $iInd=0; $iInd < $iLinhasGerador; $iInd++ ) {
						
					$oGerador = db_utils::fieldsMemory($rsGerador,$iInd);
					$iCaract  = $oGerador->caract;
          
					try {
						
						$oEstrututal = $this->getEstrututal(db_getsession('DB_anousu'),$oGerador->lotacao,$oGerador->vinculo,$oGerador->elemento);
						
						$iOrgao     = $oEstrututal->iOrgao; 
						$iUnidade   = $oEstrututal->iUnidade;
						$iProjAtiv  = $oEstrututal->iProjAtiv;
						$iFuncao    = $oEstrututal->iFuncao;
						$iSubFuncao = $oEstrututal->iSubFuncao;
						$iPrograma  = $oEstrututal->iPrograma;
						$iRecurso   = $oEstrututal->iRecurso;
						$iElemento  = $oEstrututal->iElemento;
						$iDotacao   = $oEstrututal->iDotacao;
						
					} catch (Exception $eException){
						throw new Exception("{$sMsgErro},\n".$eException->getMessage());
					}
					
					
					/**
					 *  Verifica se j� existe registros cadastrados na rhempenhofolha 
					 */
					$sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento} ";
					$sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}  ";
					$sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}    ";
					$sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv} ";
					if(!empty($iFuncao)) {
					  $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
					}
				  if(!empty($iSubFuncao)) {
				  	$sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
				  }
				  if(!empty($iPrograma)) {
				  	$sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
					}
					$sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}  ";
					$sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}   ";
					$sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}   ";
					$sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$sSigla}'  ";
					$sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}' ";
					$sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 1            ";
          $sWhereEmpenhoFolha .= " and rh72_tabprev        = 0            ";
          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = {$oGerador->rh02_seqpes}  ";
					
          if ( trim($iDotacao) !== '' ){
					  $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
					} else {
						$sWhereEmpenhoFolha .= " and rh72_coddot = 0        ";
					}
					 
					$sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"*",null,$sWhereEmpenhoFolha);
          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha);

          
          if ($oDaorhEmpenhoFolha->numrows > 0 ) {

           /**
            *  Caso exista ent�o utiliza o sequencial para a inclus�o dos registros filhos
            */
            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
          	$iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
          
          } else {

          	/**
          	 *  Caso n�o exista ent�o � inserido um registro novo
          	 */
            $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
            $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
            $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
            $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
            $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
            $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;
            $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;
            $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;            
            $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
            $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
            $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
            $oDaorhEmpenhoFolha->rh72_siglaarq       = $sSigla;
            $oDaorhEmpenhoFolha->rh72_tipoempenho    = 1;
            $oDaorhEmpenhoFolha->rh72_tabprev        = '0';
            $oDaorhEmpenhoFolha->rh72_seqcompl       = "{$oGerador->rh02_seqpes}";
            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
            $oDaorhEmpenhoFolha->incluir(null);
            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
              throw new Exception("{$oDaorhEmpenhoFolha->erro_msg}");
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          	
          $iCodEmpenhoFolhaGeral = $iCodEmpenhoFolha;
          
          
          /**
           *  Consulta todas rubr�cas inferiores a R950 apartir dos par�metros informados
           *  Obs: Esse SQL foi d�vidido em duas partes para o melhor desempenho na consulta pois inicialmente 
           *  � feita uma s�rie de vali��es para achar o estrutural que s�o em comum entre todas rubricas  
           */
	  	    $sCamposRubricas  = "{$sSigla}_rubric as rubric,      ";
		      $sCamposRubricas .= "{$sSigla}_regist as regist,      ";
			 	  $sCamposRubricas .= "rh02_seqpes      as pessoalmov,  ";
				  $sCamposRubricas .= "{$sSigla}_pd     as pd,          ";
				  $sCamposRubricas .= "{$sSigla}_quant  as quant,       ";
				  $sCamposRubricas .= "{$sSigla}_valor  as valor,       ";
				  $sCamposRubricas .= "rh23_codele      as codelemento, ";
				  $sCamposRubricas .= "{$sSigla}_anousu as anousu,      ";
				  $sCamposRubricas .= "{$sSigla}_mesusu as mesusu,      ";
          $sCamposRubricas .= "r70_concarpeculiar as caract     ";
				
				  $sWhereGeradorRubricas  = "     rh23_codele  = {$oGerador->elemento} ";
				  $sWhereGeradorRubricas .= " and rh30_vinculo = '{$oGerador->vinculo}'";
				  $sWhereGeradorRubricas .= " and rh02_lota    = {$oGerador->lotacao}  ";
          $sWhereGeradorRubricas .= " and {$sSigla}_pd != 3                    ";				  
          $sWhereGeradorRubricas .= " and r70_concarpeculiar = '{$oGerador->caract}' ";
          $sWhereGeradorRubricas .= " and r20_regist = {$oGerador->regist} ";
				  $sSqlGeradorRubricas    = $clgeradorsql->gerador_sql( $sSigla,
				  		                                                  $iAnoUsu,
					  	                                                  $iMesUsu,
							                                                  "",
							                                                  "",
							                                                  $sCamposRubricas,
							                                                  "{$sSigla}_regist",
							                                                  $sWhereGeradorRubricas,
							                                                  $iInstit);
                                                            
          $rsGeradorRubricas      = db_query($sSqlGeradorRubricas);
          
          if ( $rsGeradorRubricas ) {
          	
	          $iLinhasGeradorRubricas = pg_num_rows($rsGeradorRubricas);
	            
	          if ( $iLinhasGeradorRubricas > 0  ) {
	            	
	          	for ( $iIndRubrica=0; $iIndRubrica < $iLinhasGeradorRubricas; $iIndRubrica++ ){

                $oRubrica  = db_utils::fieldsMemory($rsGeradorRubricas,$iIndRubrica);
					      $iCaract   = $oRubrica->caract;
	           	  $aExcecoes = array();
                $iCodEmpenhoFolha = $iCodEmpenhoFolhaGeral;
	         
				        try {
				          $aExcecoes = $this->getExcessoesEmpenhoFolha($oRubrica->rubric,$iAnoUsu,$iInstit);
				        } catch (Exception $eException){
				          throw new Exception($eException->getMessage());
				        }

				        if ( !empty($aExcecoes) ) {
    
				        	/**
				        	 * Caso exista exce��o ent�o ser� consultado se j� existe algum registro cadastrado 
				        	 * na tabela rhempenhofolha para esse novo estrutural
				        	 */
				        	
	  	            $iOrgao     = $aExcecoes[0]->rh74_orgao;
			            $iUnidade   = $aExcecoes[0]->rh74_unidade;
			            $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
			            $iFuncao    = $aExcecoes[0]->rh74_funcao;
			            $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
			            $iPrograma  = $aExcecoes[0]->rh74_programa;
			            $iRecurso   = $aExcecoes[0]->rh74_recurso;
			            $iCaract    = $aExcecoes[0]->rh74_concarpeculiar;

			            $iDotacao  = $this->getDotacaoByFiltro($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElemento, $iAnoUsu, $iFuncao, $iSubFuncao, $iPrograma);  
			            
                  $sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento} ";
				          $sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}  ";
				          $sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}    ";
				          $sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv} ";
				        	if(!empty($iFuncao)) {
					          $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
					        }
				          if(!empty($iSubFuncao)) {
				          	$sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
				          }
				          if(!empty($iPrograma)) {
				          	$sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
					        }				          
				          $sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}  ";
				          $sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}   ";
				          $sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}   ";
				          $sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$sSigla}'  ";
                  $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}' ";				          
                  $sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 1            ";
				          $sWhereEmpenhoFolha .= " and rh72_tabprev        = 0            ";
				          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = {$oGerador->rh02_seqpes}";
				          
				          if ( trim($iDotacao) !== '' ){
				            $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
				          } else {
				            $sWhereEmpenhoFolha .= " and rh72_coddot = 0 ";
				          }	                
	                
				          $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
				          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha); 
				          
				          if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
				            
				           /**
				            *  Caso exista ent�o utiliza o sequencial para a inclus�o do registro filho
				            */				          	
				            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
				            $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
				            
				          } else {
				            
			             /**
			              *  Caso n�o exista ent�o � inserido um registro novo na rhempenhofolha
			              */				          	
				            $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
				            $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
				            $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
				            $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
				            $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
				            $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;  
				            $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;
				            $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;
				            $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
				            $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
				            $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
				            $oDaorhEmpenhoFolha->rh72_siglaarq       = $sSigla;
		                $oDaorhEmpenhoFolha->rh72_tipoempenho    = 1;
		                $oDaorhEmpenhoFolha->rh72_tabprev        = '0';
  	                $oDaorhEmpenhoFolha->rh72_seqcompl       = "{$oGerador->rh02_seqpes}";				            
				            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
				                                
				            $oDaorhEmpenhoFolha->incluir(null);
				            
				            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
				              throw new Exception($oDaorhEmpenhoFolha->erro_msg);
				            }
				
				            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
				            
				          }
			          }            		
	            		
			          /**
			           *  Insere registro na rhempenhofolharubrica
			           */
								$oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oRubrica->rubric; 
								$oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oRubrica->pessoalmov;
								$oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
								$oDaorhEmpenhoFolhaRubrica->rh73_valor           = $oRubrica->valor;
								$oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oRubrica->pd;
								$oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 1;
									
								$oDaorhEmpenhoFolhaRubrica->incluir(null);
									
								if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
									throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
								}
								
								/**
								 *  Insere registro na tabela de liga��o entre rhempenhofolha e rhempenhofolharubrica
								 */
                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                								
	          	  if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
                  throw new Exception($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
                }                
								
	           	}
	           	
	          } else {
	           	throw new Exception("{$sMsgErro}, nenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
	          }
    		  } else {
  		      throw new Exception("{$sMsgErro}, Erro na consulta de rubricas");
    	   	}
				}	
			} else {
				throw new Exception("{$sMsgErro}, nenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
			}
		} else {
			throw new Exception("{$sMsgErro},Erro na consulta!");
		}
   
		/*
		 * Gera pagamentos extra
		 */
    try {
     $this->geraPagamentoExtra($sSigla,$iAnoUsu,$iMesUsu,$iInstit, $aListaRescisoes);     
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }		
		
    /**
     * Gera reten��es 
     */
		try {
      $this->geraRetencoesEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);			
		} catch (Exception $eException){
			throw new Exception($eException->getMessage());
		}

		/**
		 * Gera devolu��o de empenho
		 */
    try {
     $this->geraDevolucoesEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);     
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }		
		
	}
	
  
	/**
	 * Verifica se exitem excess�es cadastradas para a rubrica
	 *
	 * @param string  $sRubric Rubrica
	 * @param integer $iAnoUsu Exerc�cio
	 * @param integer $iInstit Institui��o
	 * @return array 
	 */
  public function getExcessoesEmpenhoFolha($sRubric='',$iAnoUsu='',$iInstit=''){
  	
  	if ( trim($sRubric) == '' ) {
  		throw new Exception('Rubrica n�o informada!');
  	}

    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }
  	
    if ( trim($iInstit) == '' ) {
    	$iInstit = db_getsession('DB_instit');
    }
    
  	
  	$oDaorhEmpenhoFolhaExcecaoRubrica = db_utils::getDao('rhempenhofolhaexcecaorubrica');
  	
  	$sWhereExcecao  = "     rh74_rubric = '{$sRubric}'";
  	$sWhereExcecao .= " and rh74_anousu = {$iAnoUsu}  ";
  	$sWhereExcecao .= " and rh74_instit = {$iInstit}  ";
  	
  	$sSqlExcecao    = $oDaorhEmpenhoFolhaExcecaoRubrica->sql_query_file(null,"*",null,$sWhereExcecao);
  	$rsExcecao      = $oDaorhEmpenhoFolhaExcecaoRubrica->sql_record($sSqlExcecao);

  	
  	
  	if ( $oDaorhEmpenhoFolhaExcecaoRubrica->numrows > 0  ) {
  	  $aExcecoes = db_utils::getColectionByRecord($rsExcecao);
  	} else {
  		$aExcecoes = array();
  	}
  	
  	return $aExcecoes;
  	  	
  }

  
  /**
   * Retorna os registros referente ao dados para empenhos da folha ( rubrica < R950 )
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * @return array
   */
  public function getDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes = null ){
  	
    $sMsgErro = 'Consulta de empenhos da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    $sListaRescisoes = implode(",", $aListaRescisoes);
    $sWhereEmpenhosFolha  = "     rh72_siglaarq = '{$sSigla}'    ";
    $sWhereEmpenhosFolha .= " and rh72_anousu   = {$iAnoUsu}     ";
    $sWhereEmpenhosFolha .= " and rh72_mesusu   = {$iMesUsu}     ";
    $sWhereEmpenhosFolha .= " and rh73_instit   = {$iInstit}     ";
    $sWhereEmpenhosFolha .= " and rh72_tipoempenho = 1           ";
    $sWhereEmpenhosFolha .= " and rh73_seqpes in  ({$sListaRescisoes})";
    
    $sSqlEmpenhosFolha = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                "distinct rhempenhofolha.*",
			                                                                null,
			                                                                $sWhereEmpenhosFolha);
    $rsEmpenhosFolha   = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolha);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
  	  $aEmpenhosFolha = db_utils::getColectionByRecord($rsEmpenhosFolha);
    } else {
    	$aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }

  
  /**
   * Retorna as rubricas referente ao dados para empenhos da folha ( rubrica < R950 )
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * @return array 
   */
  public function getRubricasEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes){
    
    $sMsgErro = 'Consulta de empenhos da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolhaRubrica  = "     rh72_siglaarq    = '{$sSigla}' ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_anousu      = {$iAnoUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_mesusu      = {$iMesUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_instit      = {$iInstit}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_tipoempenho = 1           ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_seqpes in ({$sListaRescisoes}) ";

    
    $sCamposRubricas             = "rhempenhofolharubrica.*,";
    $sCamposRubricas            .= "rhempenhofolhaempenho.* ";
    
    $sSqlEmpenhosFolhaRubrica    = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                          $sCamposRubricas,
			                                                                          null,
			                                                                          $sWhereEmpenhosFolhaRubrica);

    $rsEmpenhosFolhaRubrica      = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolhaRubrica);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolhaRubrica = db_utils::getColectionByRecord($rsEmpenhosFolhaRubrica);
    } else {
      $aEmpenhosFolhaRubrica = array();
    }
    
    return $aEmpenhosFolhaRubrica;
    
  }
    
  
 /**
  * Retorna as rubricas de slip referente ao dados para empenhos da folha
  *
  * @param string  $sSigla    Tipo de Folha
  * @param integer $iAnoUsu   Exerc�cio da Folha
  * @param integer $iMesUsu   M�s da Folha
  * @param integer $iInstit   Institui��o
  * @param string  $sSemestre Semestre ( Caso seja folha complementar )
  * @return array  
  */ 
  public function getRubricasSlipFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='', $aListaRescisoes = null) {
    
    $sMsgErro = 'Consulta de slips da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolhaRubrica  = "     rh79_siglaarq    = '{$sSigla}' ";
    $sWhereEmpenhosFolhaRubrica .= " and rh79_anousu      = {$iAnoUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh79_mesusu      = {$iMesUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_instit      = {$iInstit}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_seqpes in ({$sListaRescisoes}) ";
    $sSqlEmpenhosFolhaRubrica    = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                          "*",
			                                                                          null,
			                                                                          $sWhereEmpenhosFolhaRubrica);
			                                                                          
    $rsEmpenhosFolhaRubrica      = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolhaRubrica);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolhaRubrica = db_utils::getColectionByRecord($rsEmpenhosFolhaRubrica);
    } else {
      $aEmpenhosFolhaRubrica = array();
    }
    
    return $aEmpenhosFolhaRubrica;
    
  }  
  
  
  /**
   * Retorna as rubricas de devolu��o referente ao dados para empenhos da folha 
   *
   * @param string  $sSigla        Tipo de Folha
   * @param integer $iAnoUsu       Exerc�cio da Folha
   * @param integer $iMesUsu       M�s da Folha
   * @param integer $iInstit       Institui��o
   * @param string  $sSemestre     Semestre ( Caso seja folha complementar )
   * @param integer $iTipoRubrica  Tipo de Rubrica
   * @param boolean $lRetornaSql   Par�metro que define se o retorno ser� o SQL ou o recordset
   * @return string/array
   */
  public function getRubricasDevolucaoFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null,
                                            $iTipoRubrica="",$lRetornaSql=false){
    
    $sMsgErro = 'Consulta das rubricas de devolu��o abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);
    $oDaorhDevolucaoFolha = db_utils::getDao('rhdevolucaofolha');
    
    $sWhereDevolucaoFolha  = "     rh69_siglaarq = '{$sSigla}' ";
    $sWhereDevolucaoFolha .= " and rh69_anousu   = {$iAnoUsu}  ";
    $sWhereDevolucaoFolha .= " and rh69_mesusu   = {$iMesUsu}  ";
    $sWhereDevolucaoFolha .= " and rh73_instit   = {$iInstit}  ";
    $sWhereDevolucaoFolha .= " and rh73_seqpes   in({$sListaRescisoes}) ";
    
    $sSqlDevolucaoFolha = $oDaorhDevolucaoFolha->sql_query_rubricas( null,
                                                                     "*",
                                                                     null,
                                                                     $sWhereDevolucaoFolha);

    $rsDevolucaoFolha   = $oDaorhDevolucaoFolha->sql_record($sSqlDevolucaoFolha);
    
    if ( $oDaorhDevolucaoFolha->numrows > 0 ) {
      $aDevolucaoFolha = db_utils::getColectionByRecord($rsDevolucaoFolha);
    } else {
      $aDevolucaoFolha = array();
    }
    
    if ( $lRetornaSql ) {
      return $sSqlDevolucaoFolha;
    } else {
	    return $aDevolucaoFolha;
    }
    
  }  
  
  
  /**
   * Exclui os dados para empenhos da folha juntamente com os de slip e devolu��o
   * 
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * 
   */
  public function excluiDadosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes= null){
  	
    $sMsgErro = 'Exclus�o dados da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);	
  	
    try {
      
	    $this->excluiDadosDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);   
	    $this->excluiDadosSlipFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);                                                                                                                
	    $this->excluiDadosEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
    } catch (Exception $eException){
      throw new Exception("{$sMsgErro},\n".$eException->getMessage());    	
    }
    	    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function excluiDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null){
    
    $sMsgErro = 'Exclus�o de empenhos da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);  
    
    $oDaorhEmpenhoFolha                = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica    = db_utils::getDao('rhempenhofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');

    /**
     *  Cria um array contendo os registros da rhempenhofolha
     */
    try {
    	$aListaEmpenhos = $this->getDadosEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
    } catch (Exception $eException){
    	throw new Exception($eException->getMessage());
    }
    
    /**
     *  Cria um array contendo os registros da rhempenhofolharubrica
     */    
    try {
      $aListaRubricas = $this->getRubricasEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }    
    
    if ( count($aListaEmpenhos) > 0  ) {
    	 
	    $aListaCodEmpenho = array();
	    
	    foreach ( $aListaEmpenhos as $iInd => $oEmpenhoFolha ) {
	      $aListaCodEmpenho[] = $oEmpenhoFolha->rh72_sequencial;
	    }
	    
	    $aListaCodEmpenho = array_unique($aListaCodEmpenho);
	    $sListaEmpenhos   = implode(',',$aListaCodEmpenho);
	    
	    /**
	     *  Exclui inicialmente os registros de reten��o
	     */
	    $sSqlExcluiRetencoes  = " delete from rhempenhofolharubricaretencao                                                "; 
	    $sSqlExcluiRetencoes .= "  where rh78_rhempenhofolharubrica in ( select rh81_rhempenhofolharubrica                 ";
	    $sSqlExcluiRetencoes .= "                                          from rhempenhofolharhemprubrica                 ";
	    $sSqlExcluiRetencoes .= "                                         where rh81_rhempenhofolha in ({$sListaEmpenhos}))";
	    $rsExcluiRetencoes    = db_query($sSqlExcluiRetencoes);

	    if ( !$rsExcluiRetencoes ) {
	    	throw new Exception("{$sMsgErro},\n".pg_last_error());
	    }
	    
	    /**
	     *  Exclui os registros da tabela de liga��o entre rhempenhofolha e rhempenhofolharubrica 
	     */
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }      
      
      /**
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      if ( count($aListaRubricas) > 0 ) {
        foreach ( $aListaRubricas as $iIndRubrica => $oEmpenhoRubrica ){
        	
          if ( trim($oEmpenhoRubrica->rh76_sequencial) != '' ) {
            throw new Exception("{$sMsgErro}, \nRegistros j� empenhados ($oEmpenhoRubrica->rh76_sequencial)!");
          }
          
          $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoRubrica->rh73_sequencial);
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
            throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
          }                       
        }
      }      
      
      /**
       * Verifica se existe registros na rhempenhofolha sem rubricas ( rhempenhofolharubrica )
       */
	    $sWhereEmpenhos  = "     rh72_sequencial in ({$sListaEmpenhos}) ";
	    $sWhereEmpenhos .= " and rh73_sequencial is null                ";
	    
	    $sSqlEmpenhos    = $oDaorhEmpenhoFolha->sql_query_rubricas(null,"rh72_sequencial",null,$sWhereEmpenhos);
	    
	    $rsEmpenhosFolha = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhos);
	    $iLinhasEmpenhos = $oDaorhEmpenhoFolha->numrows;
	    
	    
	    /**
	     *  Caso exista ent�o s�o deletados os registros
	     */
	    if ( $iLinhasEmpenhos > 0 ) {
	    	
	    	for ( $iInd=0; $iInd < $iLinhasEmpenhos; $iInd++ ) {
	    		
	    		$oEmpenhoFolha = db_utils::fieldsMemory($rsEmpenhosFolha,$iInd);
	    		
	    		/**
	    		 * Excluimos a reserva de saldo, caso Exista
	    		 */
		    	$oDaoReserva             = db_utils::getDao("orcreserva");
		    	$oDaoReservaSaldoEmpenho = db_utils::getDao("orcreservarhempenhofolha");
		    	$sWhere                  = "o120_rhempenhofolha = {$oEmpenhoFolha->rh72_sequencial}";
		    	$sSqlDadosReserva        = $oDaoReservaSaldoEmpenho->sql_query_file(null, "o120_orcreserva", null, $sWhere);
		    	$rsDadosEmpenho          = $oDaoReservaSaldoEmpenho->sql_record($sSqlDadosReserva);
		    	if ($oDaoReservaSaldoEmpenho->numrows > 0) {
		    		
		    		$oReserva = db_utils::fielsMemory($rsDadosEmpenho, 0);
		    		$oDaoReserva->excluir($oReserva->o120_orcreserva);
		    		if ($oDaoReserva->erro_status == 0) {
		    			throw new Exception("Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReserva->erro_msg}");
		    		}
			      $oDaoReservaSaldoEmpenho->excluir(null, "o120_orcreserva = {$oReserva->o120_orcreserva}");
		      	if ($oDaoReservaSaldoEmpenho->erro_status == 0) {
		    			throw new Exception("Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReservaSaldoEmpenho->erro_msg}");
		    		}
		    	}
		      $oDaorhEmpenhoFolha->excluir($oEmpenhoFolha->rh72_sequencial);
		      
		      if ( $oDaorhEmpenhoFolha->erro_status == "0") {
		        throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolha->erro_msg}");
		      }      
	    	}
	    }
    }
  }
  
  
  /**
   * Exclui os slips referentes aos dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function excluiDadosSlipFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null){
    
    $sMsgErro = 'Exclus�o de slips da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);  
       
    $oDaorhSlipFolha                   = db_utils::getDao('rhslipfolha');
    $oDaorhSlipFolhaSlip               = db_utils::getDao('rhslipfolhaslip');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhSlipFolhaRhEmpRubrica       = db_utils::getDao('rhslipfolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    
    
    /**
     *  Cria um array contendo as rubricas de slip
     */
    try {
      $aDadosRubricas = $this->getRubricasSlipFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }    
    
    if ( count($aDadosRubricas) > 0  ) {
       
      $aListaSlip = array();
      
      foreach ( $aDadosRubricas as $iInd => $oSlipFolha ) {
        $aListaSlip[] = $oSlipFolha->rh79_sequencial;
      }
      
      $aListaSlip = array_unique($aListaSlip);
      $sListaSlip = implode(',',$aListaSlip);
      
      
      /**
       *  Exclui inicialmente as rubricas de reten��o
       */
      $sSqlExcluiRetencoes  = " delete from rhempenhofolharubricaretencao                                         "; 
      $sSqlExcluiRetencoes .= "  where rh78_rhempenhofolharubrica in ( select rh80_rhempenhofolharubrica          ";
      $sSqlExcluiRetencoes .= "                                          from rhslipfolharhemprubrica             ";
      $sSqlExcluiRetencoes .= "                                         where rh80_rhslipfolha in ({$sListaSlip}))";
      $rsExcluiRetencoes    = db_query($sSqlExcluiRetencoes);

      if ( !$rsExcluiRetencoes ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }
      
      /**
       *  Exclui os registros da tabela de liga��o entre  rhslipfolha e rhempenhofolharubrica
       */
      $sSqlExcluiSlipRub  = " delete from rhslipfolharhemprubrica        ";
      $sSqlExcluiSlipRub .= "  where rh80_rhslipfolha in ({$sListaSlip}) ";
      $rsExcluiSlipRub    = db_query($sSqlExcluiSlipRub);

      if ( !$rsExcluiSlipRub ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }      
      
      /*
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      foreach ( $aDadosRubricas as $iInd => $oSlipFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oSlipFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
        }        
      }      	
      
      
      $oDaorhSlipFolhaSlip->excluir(null, " rh82_rhslipfolha in ({$sListaSlip})");
      if ($oDaorhSlipFolhaSlip->erro_status == "0") {
      	throw new Exception("{$sMsgErro},\n{$oDaorhSlipFolhaSlip->erro_msg}");
      }
      
      /**
       *  Verifica se existem registros na rhslipfolha sem rubricas ( rhempenhofolharubrica )
       */
      $sWhereSlip  = "     rh79_sequencial in ({$sListaSlip}) ";
      $sWhereSlip .= " and rh73_sequencial is null            ";
      
      $sSqlSlip    = $oDaorhSlipFolha->sql_query_rubricas(null,"rh79_sequencial",null,$sWhereSlip);
      $rsSlipFolha = $oDaorhSlipFolha->sql_record($sSqlSlip);
      $iLinhasSlip = $oDaorhSlipFolha->numrows;
      
      /**
       * Caso exista ent�o s�o deletados os registros
       */
      if ( $iLinhasSlip > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasSlip; $iInd++ ) {
          
          $oRHSlipFolha= db_utils::fieldsMemory($rsSlipFolha,$iInd);
          $oDaorhSlipFolha->excluir($oRHSlipFolha->rh79_sequencial);
          
          if ( $oDaorhSlipFolha->erro_status == "0") {
            throw new Exception("{$sMsgErro},\n{$oDaorhSlipFolha->erro_msg}");
          }      
        }
      }
    }
  }

  
  /**
   * Exclui as devolu��es referentes aos dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */  
  public function excluiDadosDevolucaoFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null){
    
    $sMsgErro = 'Exclus�o de devolu��es da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    $sListaRescisoes = implode(",", $aListaRescisoes);  
    
    $oDaorhDevolucaoFolha              = db_utils::getDao('rhdevolucaofolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhDevolucaoFolhaRhEmpRubrica  = db_utils::getDao('rhdevolucaofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');    
    
    /**
     *  Cria um array contendo as rubricas de devolu��o 
     */
    try {
      $aDadosRubricas = $this->getRubricasDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }    
    
    if ( count($aDadosRubricas) > 0  ) {
       
      $aListaDevolucao = array();
      
      foreach ( $aDadosRubricas as $iInd => $oDevolucaoFolha ) {
        $aListaDevolucao[] = $oDevolucaoFolha->rh69_sequencial;
      }
      
      $aListaDevolucao = array_unique($aListaDevolucao);
      $sListaDevolucao = implode(',',$aListaDevolucao);

      /**
       *  Exclui as rubricas de reten��o
       */
      $sSqlExcluiRetencoes  = " delete from rhempenhofolharubricaretencao                                                 "; 
      $sSqlExcluiRetencoes .= "  where rh78_rhempenhofolharubrica in ( select rh87_rhempenhofolharubrica                  ";
      $sSqlExcluiRetencoes .= "                                          from rhdevolucaofolharhemprubrica                ";
      $sSqlExcluiRetencoes .= "                                         where rh87_devolucaofolha in ({$sListaDevolucao}))";
      $rsExcluiRetencoes    = db_query($sSqlExcluiRetencoes);

      if ( !$rsExcluiRetencoes ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }
      
      /**
       *  Exclui os registros da tabela de liga��o entre rhdevolucaofolha e rhempenhofolharubrica  
       */
      $oDaorhDevolucaoFolhaRhEmpRubrica->excluir(null,"rh87_devolucaofolha in ({$sListaDevolucao})");
      
      if ( $oDaorhDevolucaoFolhaRhEmpRubrica->erro_status == 0 ) {
        throw new Exception("{$sMsgErro},\n{$oDaorhDevolucaoFolhaRhEmpRubrica->erro_msg}");
      }
      
      
      /**
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      foreach ( $aDadosRubricas as $iInd => $oDevolucaoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oDevolucaoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
        }        
      }       
      
      /**
       *  Verifica se existe registros na rhdevolucaofolha sem rubricas ( rhempenhofolharubrica ) 
       */
      $sWhereDevolucao  = "     rh69_sequencial in ({$sListaDevolucao}) ";
      $sWhereDevolucao .= " and rh73_sequencial is null                 ";
      
      $sSqlDevolucao    = $oDaorhDevolucaoFolha->sql_query_rubricas(null,"rh69_sequencial",null,$sWhereDevolucao);
      $rsDevolucaoFolha = $oDaorhDevolucaoFolha->sql_record($sSqlDevolucao);
      $iLinhasDevolucao = $oDaorhDevolucaoFolha->numrows;
      
      /**
       *  Caso exista ent�o s�o deletados os registros 
       */
      if ( $iLinhasDevolucao > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
          
          $oRHDevolucaoFolha= db_utils::fieldsMemory($rsDevolucaoFolha,$iInd);
          
          $oDaorhDevolucaoFolha->excluir($oRHDevolucaoFolha->rh69_sequencial);
          
          if ( $oDaorhDevolucaoFolha->erro_status == "0") {
            throw new Exception("{$sMsgErro},\n{$oDaorhDevolucaoFolha->erro_msg}");
          }      
        }
      }
    }
  }  
  
  
  /**
   * Gera os dados de reten��es para os empenhos da folha 
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */  
  public function geraRetencoesEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null) {
  	
  	$sMsgErro = 'Gera��o de reten��es abortada';
  	
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);        	
    $oDaoCfPess                        = db_utils::getDao('cfpess');
    $oDaoPensaoRetencao                = db_utils::getDao('pensaoretencao');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    $oDaorhEmpenhoFolhaRhEmpRubrica    = db_utils::getDao('rhempenhofolharhemprubrica');
    $oDaorhSlipFolhaRhEmpRubrica       = db_utils::getDao('rhslipfolharhemprubrica');
    
    /**
     *  Consulta a rubrica de pens�o alimenticia 
     */
    $rsRubricPensao = $oDaoCfPess->sql_record($oDaoCfPess->sql_query_file($iAnoUsu,$iMesUsu,$iInstit,"r11_palime"));
    
    if ( $oDaoCfPess->numrows > 0 ) {
    	
    	$oRubricPensao = db_utils::fieldsMemory($rsRubricPensao,0);
    	
    	$sRubricPensao       = $oRubricPensao->r11_palime; // Pens�o Aliment�cia
    	$sRubricPensaoFerias = $sRubricPensao + 2000;      // Pens�o F�rias 
    	$sRubricPensao13     = $sRubricPensao + 4000;      // Pens�o 13�
    	
    }
    
    /**
     *  Consulta os dados j� gerados para empenhos
     */
    $sSqlDadosEmp  = " select distinct rh73_seqpes     as seqpes,                                         ";
    $sSqlDadosEmp .= "                 rh72_sequencial as rhempenho,                                      ";
    $sSqlDadosEmp .= "                 round(sum(case when rh73_pd=2  then rh73_valor*-1 else rh73_valor end),2) as valor                                  ";  
    $sSqlDadosEmp .= "            from rhempenhofolharubrica                                              ";
    $sSqlDadosEmp .= "                 inner join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial     ";
    $sSqlDadosEmp .= "                 inner join rhempenhofolha             on rh72_sequencial            = rh81_rhempenhofolha ";  
    $sSqlDadosEmp .= "           where rh72_anousu      = {$iAnoUsu}                                      ";
    $sSqlDadosEmp .= "             and rh72_mesusu      = {$iMesUsu}                                      ";
    $sSqlDadosEmp .= "             and rh72_siglaarq    = '{$sSigla}'                                     ";
    $sSqlDadosEmp .= "             and rh72_tipoempenho = 1                                               ";
    $sSqlDadosEmp .= "             and rh72_tabprev     = 0                                               ";
    $sSqlDadosEmp .= "             and rh73_seqpes      in({$sListaRescisoes})                                  ";
    $sSqlDadosEmp .= "             and rh73_instit      = '{$iInstit}'                                    ";
    $sSqlDadosEmp .= "        group by rh73_seqpes,                                                       ";
    $sSqlDadosEmp .= "                 rh72_sequencial                                                    ";
    $sSqlDadosEmp .= "        having   round(sum(case when rh73_pd=2  then rh73_valor*-1 else rh73_valor end),2)> 0";
    $sSqlDadosEmp .= "        order by rh73_seqpes                                                        ";
    
    $rsDadosEmp    = db_query($sSqlDadosEmp);

    if ( $rsDadosEmp ) {
    	
    	$iLinhasDadosEmp = pg_num_rows($rsDadosEmp);
    	
    	if ( $iLinhasDadosEmp > 0 ) {
    		
    		for ( $iInd=0; $iInd < $iLinhasDadosEmp; $iInd++ ) {
    		  
    			$oDadosEmp = db_utils::fieldsMemory($rsDadosEmp,$iInd);
   				
          /**
   				 * Calcula total gerado por servidor 
   				 */
    			if ( isset($aDadosEmp[$oDadosEmp->seqpes]['total']) ) {
   				  $aDadosEmp[$oDadosEmp->seqpes]['total'] += $oDadosEmp->valor;
   				} else {
   					$aDadosEmp[$oDadosEmp->seqpes]['total']  = $oDadosEmp->valor;
   				}
   				
    		}
    	}
    }

    /**
     *  Consulta os regitros j� gerados de slip 
     */
    $sSqlDadosSlip  = " select distinct rh73_seqpes     as seqpes,                                                          ";
    $sSqlDadosSlip .= "                 rh79_sequencial as rhslip,                                                          ";
    $sSqlDadosSlip .= "                 round(sum(rh73_valor),2) as valor                                                   ";  
    $sSqlDadosSlip .= "            from rhempenhofolharubrica                                                               ";
    $sSqlDadosSlip .= "                 inner join rhslipfolharhemprubrica on rh80_rhempenhofolharubrica = rh73_sequencial  ";
    $sSqlDadosSlip .= "                 inner join rhslipfolha             on rh79_sequencial            = rh80_rhslipfolha ";  
    $sSqlDadosSlip .= "           where rh79_anousu      = {$iAnoUsu}                                                       ";
    $sSqlDadosSlip .= "             and rh79_mesusu      = {$iMesUsu}                                                       ";
    $sSqlDadosSlip .= "             and rh79_siglaarq    = '{$sSigla}'                                                      ";
    $sSqlDadosSlip .= "             and rh79_tipoempenho = 1                                                                ";
    $sSqlDadosSlip .= "             and rh79_tabprev     = 0                                                                ";
    $sSqlDadosSlip .= "             and rh73_seqpes      in ({$sListaRescisoes})                                            ";
    $sSqlDadosSlip .= "             and rh73_instit      = '{$iInstit}'                                                     ";
    $sSqlDadosSlip .= "        group by rh73_seqpes,                                                                        ";
    $sSqlDadosSlip .= "                 rh79_sequencial                                                                     ";
    $sSqlDadosSlip .= "        order by rh73_seqpes                                                                         ";

    $rsDadosSlip    = db_query($sSqlDadosSlip);

    if ( $rsDadosSlip ) {
      
    	$iLinhasDadosSlip = pg_num_rows($rsDadosSlip);
    	
      if ( $iLinhasDadosSlip > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasDadosSlip; $iInd++ ){
          
        	$oDadosSlip = db_utils::fieldsMemory($rsDadosSlip,$iInd);
          /**
           * Calcula total gerado por servidor
           */
          if ( isset($aDadosEmp[$oDadosSlip->seqpes]['total']) ) {
            $aDadosEmp[$oDadosSlip->seqpes]['total'] += $oDadosSlip->valor;
          } else {
            $aDadosEmp[$oDadosSlip->seqpes]['total']  = $oDadosSlip->valor;
          }
          
        }
      }
    }

    for ( $iInd=0; $iInd < $iLinhasDadosEmp; $iInd++ ) {
      
      $oDadosEmp = db_utils::fieldsMemory($rsDadosEmp,$iInd);
      $aDadosEmp[$oDadosEmp->seqpes]['rhempenho'][$oDadosEmp->rhempenho]['valor'] = $oDadosEmp->valor;
      /**
       * Calcula o percentual do valor do empenho sobre o total
       */
      $aDadosEmp[$oDadosEmp->seqpes]['rhempenho'][$oDadosEmp->rhempenho]['perc']  = ($oDadosEmp->valor*100)/$aDadosEmp[$oDadosEmp->seqpes]['total'];
      
    }
    
    for ( $iInd=0; $iInd < $iLinhasDadosSlip; $iInd++ ){

      $oDadosSlip = db_utils::fieldsMemory($rsDadosSlip,$iInd);
      $aDadosEmp[$oDadosSlip->seqpes]['rhslip'][$oDadosSlip->rhslip]['valor'] = $oDadosSlip->valor;
      /**
       * Calcula o percentual do valor do slip sobre o total
       */
      $aDadosEmp[$oDadosSlip->seqpes]['rhslip'][$oDadosSlip->rhslip]['perc']  = ($oDadosSlip->valor*100)/$aDadosEmp[$oDadosSlip->seqpes]['total'];
      
    }    
    
    switch ($sSigla) {
      case "r48":
        $sTabela      = "gerfcom";
        $sCampoPensao = "r52_valcom";
      break;
      case "r14":
        $sTabela      = "gerfsal";
        $sCampoPensao = "r52_valor";
      break;
      case "r35":
        $sTabela      = "gerfs13";
        $sCampoPensao = "r52_val13";
      break;
      case "r22":
        $sTabela      = "gerfadi";
        $sCampoPensao = "r52_valor";
      break;
      case "r20":
        $sTabela      = "gerfres";
        $sCampoPensao = "r52_valres";
      break;                    
    }    

    /**
     * Cosulta os registros de reten��o
     */
    $sSqlDadosRetencao  = " select distinct rh02_seqpes          as seqpes,                             ";
    $sSqlDadosRetencao .= "                 rh02_regist          as regist,                             ";
    $sSqlDadosRetencao .= "                 {$sSigla}_rubric     as rubric,                             ";
    $sSqlDadosRetencao .= "                 {$sSigla}_pd         as pd,                                 ";
    $sSqlDadosRetencao .= "                 rh75_retencaotiporec as retencao,                           ";
    $sSqlDadosRetencao .= "                 {$sSigla}_valor      as valor                               ";
    $sSqlDadosRetencao .= "   from {$sTabela}                                                           ";
    $sSqlDadosRetencao .= "        inner join rhpessoalmov     on rh02_anousu    = {$sSigla}_anousu     ";
    $sSqlDadosRetencao .= "                                   and rh02_mesusu    = {$sSigla}_mesusu     ";
    $sSqlDadosRetencao .= "                                   and rh02_regist    = {$sSigla}_regist     ";
    $sSqlDadosRetencao .= "        inner join rhrubretencao    on rh75_rubric    = {$sSigla}_rubric     ";
    $sSqlDadosRetencao .= "                                   and rh75_instit    = {$sSigla}_instit     ";
    $sSqlDadosRetencao .= "        inner join retencaotiporec  on e21_sequencial = rh75_retencaotiporec ";
    $sSqlDadosRetencao .= "  where e21_retencaotiporecgrupo = 2                                         ";
    $sSqlDadosRetencao .= "    and {$sSigla}_anousu      = {$iAnoUsu}                                   ";
    $sSqlDadosRetencao .= "    and {$sSigla}_mesusu      = {$iMesUsu}                                   ";
    $sSqlDadosRetencao .= "    and rh02_instit           = {$iInstit}                                   ";
    $rsDadosRetencao = db_query($sSqlDadosRetencao);
    
    if ( $rsDadosRetencao ) {
      
      $iLinhasDadosRetencao = pg_num_rows($rsDadosRetencao);
      
      if ( $iLinhasDadosRetencao > 0  ) {
        
        for ( $iInd=0; $iInd < $iLinhasDadosRetencao; $iInd++ ){
          
        	$oDadosRetencao = db_utils::fieldsMemory($rsDadosRetencao,$iInd);
          
          if ( isset($aDadosEmp[$oDadosRetencao->seqpes]) ) {

            $nValorTotal = 0;
         	  $nValor      = $oDadosRetencao->valor;
            
            if ( isset($aDadosEmp[$oDadosRetencao->seqpes]['rhslip'])){
              $lSlip = true;         	  
            } else {
              $lSlip = false;
            }
         	  
            
            /**
             *  Define as reten��es sobre as rubricas de empenhos
             */
          	if ( isset($aDadosEmp[$oDadosRetencao->seqpes]['rhempenho'] ) ) {

          		$iUltimoReg = end(array_keys($aDadosEmp[$oDadosRetencao->seqpes]['rhempenho']));
          		
	            foreach( $aDadosEmp[$oDadosRetencao->seqpes]['rhempenho'] as $iCodrhEmpenho => $aRhEmpenho ){
	          	
	            	$nPerc  = $aRhEmpenho['perc'];
	            	
	            	/**
	            	 * Verifica se a rubrica � pens�o
	            	 */
	              if ( $oDadosRetencao->rubric == $sRubricPensao ||
	                   $oDadosRetencao->rubric == $sRubricPensaoFerias || 
	                   $oDadosRetencao->rubric == $sRubricPensao13  )  {
	            
	                /**
	                 * Verifica se existe pens�o cadastrado para o servidor
	                 */   	
			            $sWherePensaoRetencao   = "     r52_anousu = {$iAnoUsu}                ";
			            $sWherePensaoRetencao  .= " and r52_mesusu = {$iMesUsu}                ";
			            $sWherePensaoRetencao  .= " and r52_regist = {$oDadosRetencao->regist} ";
	
			            $sSqlPensaoRetencao     = $oDaoPensaoRetencao->sql_query_dados(null,"*",null,$sWherePensaoRetencao);
			            
			            $rsPensaoRetencao       = $oDaoPensaoRetencao->sql_record($sSqlPensaoRetencao);
			            $iLinhasPensaoRetencao  = $oDaoPensaoRetencao->numrows;
			            
			            if ( $iLinhasPensaoRetencao > 0 ) {
			            	
			              for ( $iIndX=0; $iIndX < $iLinhasPensaoRetencao; $iIndX++ ) {
			              	
			                $oPensaoRetencao = db_utils::fieldsMemory($rsPensaoRetencao,$iIndX);
			                
			                /**
			                 *  Define o valor da pens�o apartir do tipo de pens�o ( Aliment�cia, F�rias ou 13� )
			                 */
			                if ( $oDadosRetencao->rubric == $sRubricPensao ) {
	                      $nValorPensao = $oPensaoRetencao->$sCampoPensao;
			                } else if ( $oDadosRetencao->rubric == $sRubricPensaoFerias ) {
			                	$nValorPensao = $oPensaoRetencao->r52_valfer;
			                } else if ( $oDadosRetencao->rubric == $sRubricPensao13 ) {
			                	$nValorPensao = $oPensaoRetencao->r52_val13;
			                }

			                /**
			                 *  Calcula o valor da reten��o sobre o percentual achado ateriormente
			                 */
		                  $nValorRetencao  = db_formatar($nValorPensao*($nPerc/100),'p');
		                  $nValorTotal    += $nValorRetencao;
		                   
		                  /**
		                   *  Caso seja o �ltimo registro ent�o o valor da reten�ao fica como o restante
		                   */
		                  if ( $iUltimoReg == $iCodrhEmpenho && $iIndX == ($iLinhasPensaoRetencao-1) && !$lSlip ) {
		                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
		                  }

		                  /**
		                   *  Inclui a rubrica de reten��o 
		                   */
	                    $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
			                $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
			                $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
			                $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
			                $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
			                $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
			                $oDaorhEmpenhoFolhaRubrica->incluir(null);
			                    
			                if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
			                  throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
			                }		                
			                
			                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
			                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodrhEmpenho;
			                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
			                                
			                if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
			                  throw new Exception($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
			                }               		                
			                
			                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
			                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPensaoRetencao->rh77_retencaotiporec;
			                $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
			                
			                if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
			                  throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
			                }
			              }
			            }
			            
			          } else {
	
			          	/**
			          	 *  Calcula o valor da reten��o apartir do percentual achado anteriormente
			          	 */
                  $nValorRetencao  = db_formatar($oDadosRetencao->valor*($nPerc/100),'p');
	                $nValorTotal    += $nValorRetencao;
	                
                  /**
                   *  Caso seja o �ltimo registro ent�o o valor da reten�ao fica como o restante
                   */	                
		              if ( $iUltimoReg == $iCodrhEmpenho  && !$lSlip ) {
		                 $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
		              }
		              
		              /**
		               *  Inclui a rubrica de reten��o
		               */
		              $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
		              $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
		              $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
		              $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
		              $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
		              $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
		                  
		              $oDaorhEmpenhoFolhaRubrica->incluir(null);
		                  
		              if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
		                throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
		              }   		          	
		              
	                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodrhEmpenho;
	                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
	                                    
	                if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
	                  throw new Exception($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
	                }                                   
		              
		              $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
		              $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDadosRetencao->retencao;
		              $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
		              
		              if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
		                throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
		              }
			          }
	            }
            }
            
            /**
             *  Define a reten��o sobre as rubricas de slip
             */
            if ( isset($aDadosEmp[$oDadosRetencao->seqpes]['rhslip']) ) {
            	
              $iUltimoReg  = end(array_keys($aDadosEmp[$oDadosRetencao->seqpes]['rhslip']));
            	
	            foreach( $aDadosEmp[$oDadosRetencao->seqpes]['rhslip'] as $iCodrhSlip => $aRHSlip ){
	                         
	              $nPerc  = $aRHSlip['perc'];
	              
                /**
                 * Verifica se a rubrica � pens�o
                 */	              
	              if ( $oDadosRetencao->rubric == $sRubricPensao ||
	                   $oDadosRetencao->rubric == $sRubricPensaoFerias || 
	                   $oDadosRetencao->rubric == $sRubricPensao13  )  {
	            
	                $sWherePensaoRetencao   = "     r52_anousu = {$iAnoUsu}                ";
	                $sWherePensaoRetencao  .= " and r52_mesusu = {$iMesUsu}                ";
	                $sWherePensaoRetencao  .= " and r52_regist = {$oDadosRetencao->regist} ";
	
	                $sSqlPensaoRetencao     = $oDaoPensaoRetencao->sql_query_dados(null,"*",null,$sWherePensaoRetencao);
	                
	                $rsPensaoRetencao       = $oDaoPensaoRetencao->sql_record($sSqlPensaoRetencao);
	                $iLinhasPensaoRetencao  = $oDaoPensaoRetencao->numrows;
	                
	                if ( $iLinhasPensaoRetencao > 0 ) {
	                  
	                  for ( $iIndX=0; $iIndX < $iLinhasPensaoRetencao; $iIndX++ ) {
	                    
	                    $oPensaoRetencao = db_utils::fieldsMemory($rsPensaoRetencao,$iIndX);
	                    
	                    /**
	                     * Verifica se existe pens�o cadastrado para o servidor
	                     */	                    
	                    if ( $oDadosRetencao->rubric == $sRubricPensao ) {
	                      $nValorPensao = $oPensaoRetencao->$sCampoPensao;
	                    } else if ( $oDadosRetencao->rubric == $sRubricPensaoFerias ) {
	                      $nValorPensao = $oPensaoRetencao->r52_valfer;
	                    } else if ( $oDadosRetencao->rubric == $sRubricPensao13 ) {
	                      $nValorPensao = $oPensaoRetencao->r52_val13;
	                    }
	                    
                      /**
                       *  Calcula o valor da reten��o sobre o percentual achado ateriormente
                       */	                    
		                  $nValorRetencao  = db_formatar($nValorPensao*($nPerc/100),'p');
		                  $nValorTotal    += $nValorRetencao;
		                  
                      /**
                       *  Caso seja o �ltimo registro ent�o o valor da reten�ao fica como o restante
                       */		                  
                      if ( $iUltimoReg == $iCodrhSlip && $iIndX == ($iLinhasPensaoRetencao-1) ) {		
		                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);               
		                  }                   

		                  /**
		                   *  Inclui a rubrica de reten��o
		                   */		                  
	                    $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
	                    $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
	                    $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
	                    $oDaorhEmpenhoFolhaRubrica->incluir(null);
	                        
	                    if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
	                      throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
	                    }                   
	                    
	                    $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                    $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodrhSlip;
	                    $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
	                                    
	                    if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
	                      throw new Exception($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
	                    }                                   
	                    
	                    $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
	                    $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPensaoRetencao->rh77_retencaotiporec;
	                    $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
	                    
	                    if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
	                      throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
	                    }
	                  }
	                }
	                
	              } else {

                  /**
                   *  Calcula o valor da reten��o apartir do percentual achado anteriormente
                   */	              	
                  $nValorRetencao  = db_formatar($oDadosRetencao->valor*($nPerc/100),'p');
                  $nValorTotal    += $nValorRetencao;
                  
                  /**
                   *  Caso seja o �ltimo registro ent�o o valor da reten�ao fica como o restante
                   */                     
                  if ( $iUltimoReg == $iCodrhSlip ) {
                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
                  }	 
                                   
                  /**
                   *  Inclui a rubrica de reten��o
                   */	                  
	                $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
	                $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
	                $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
	                $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
	                $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
	                $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
	                    
	                $oDaorhEmpenhoFolhaRubrica->incluir(null);
	                    
	                if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
	                  throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
	                }                   
	                
	                $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodrhSlip;
	                $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
	                                   
	                if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
	                  throw new Exception($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
	                }                               
	                
	                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
	                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDadosRetencao->retencao;
	                $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
	                
	                if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
	                  throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
	                }
	                                            
	              }
	            }            
            }
          } 
        }
      }
    }
  }
  
  
  /**
   * Gera dados de pagamento extra
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha 
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   */
  public function geraPagamentoExtra($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null){
  	
  	
    $sMsgErro = 'Gera��o de pagamento extra abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);    	
    $oDaorhLotaVinc                    = db_utils::getDao('rhlotavinc');
    $oDaorhSlipFolha                   = db_utils::getDao('rhslipfolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhSlipFolhaRhEmpRubrica       = db_utils::getDao('rhslipfolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    
    switch ($sSigla) {
      case "r48":
        $sTabela      = "gerfcom";
      break;
      case "r14":
        $sTabela      = "gerfsal";
      break;
      case "r35":
        $sTabela      = "gerfs13";
      break;
      case "r22":
        $sTabela      = "gerfadi";
      break;
      case "r20":
        $sTabela      = "gerfres";
      break;                    
    }    

    /**
     *  Consulta as rubricas referente a pagamento extra
     */
    $sSqlPagExtra  = " select {$sSigla}_rubric     as rubric,                                                                   ";
    $sSqlPagExtra .= "        rh02_seqpes          as pessoalmov,                                                               ";
    $sSqlPagExtra .= "        {$sSigla}_pd         as pd,                                                                       ";
    $sSqlPagExtra .= "        {$sSigla}_valor      as valor,                                                                    ";
    $sSqlPagExtra .= "        rh30_vinculo         as vinculo,                                                                  ";
    $sSqlPagExtra .= "        rh02_lota            as lotacao,                                                                  ";
    $sSqlPagExtra .= "        r70_concarpeculiar   as caract,                                                                   ";
    $sSqlPagExtra .= "        rh75_retencaotiporec as retencao                                                                  ";
    $sSqlPagExtra .= "   from {$sTabela}                                                                                        ";
    $sSqlPagExtra .= "        inner join rhpessoalmov    on rhpessoalmov.rh02_anousu       = {$sTabela}.{$sSigla}_anousu        ";
    $sSqlPagExtra .= "                                  and rhpessoalmov.rh02_mesusu       = {$sTabela}.{$sSigla}_mesusu        ";
    $sSqlPagExtra .= "                                  and rhpessoalmov.rh02_regist       = {$sTabela}.{$sSigla}_regist        ";
    $sSqlPagExtra .= "        inner join rhlota          on rhlota.r70_codigo              = rhpessoalmov.rh02_lota             ";
    $sSqlPagExtra .= "        inner join rhrubretencao   on rhrubretencao.rh75_rubric      = {$sTabela}.{$sSigla}_rubric        "; 
    $sSqlPagExtra .= "                                  and rhrubretencao.rh75_instit      = {$sTabela}.{$sSigla}_instit        ";
    $sSqlPagExtra .= "        inner join retencaotiporec on retencaotiporec.e21_sequencial = rhrubretencao.rh75_retencaotiporec ";
    $sSqlPagExtra .= "        inner join rhregime        on rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg           ";
    $sSqlPagExtra .= "                                  and rhregime.rh30_instit           = rhpessoalmov.rh02_instit           ";
    $sSqlPagExtra .= "  where e21_retencaotiporecgrupo = 3                                                                      ";
    $sSqlPagExtra .= "    and {$sSigla}_anousu = {$iAnoUsu}                                                                     ";
    $sSqlPagExtra .= "    and {$sSigla}_mesusu = {$iMesUsu}                                                                     ";
    $sSqlPagExtra .= "    and {$sSigla}_instit = {$iInstit}                                                                     ";
   	$sSqlPagExtra .= "  and rh02_seqpes        in({$sListaRescisoes})                                                           ";
    $rsPagExtra = db_query($sSqlPagExtra);
    
    if ( $rsPagExtra ) {
    	
    	$iLinhasPagExtra = pg_num_rows($rsPagExtra);
    	
    	if ( $iLinhasPagExtra > 0 ) {
    		
    		for ( $iInd=0; $iInd < $iLinhasPagExtra; $iInd++ ) {
    			
    			$oPagExtra = db_utils::fieldsMemory($rsPagExtra,$iInd);
    			$iCaract   = $oPagExtra->caract;
    			
    			/**
    			 * Consulta recurso apartir da lota��o e v�nculo achado no consulta anterior
    			 */
			    $sCamposrhLotaVinc = " rh25_recurso as recurso   ";
			          
			    $sWhererhLotaVinc  = "     rh25_codigo  = {$oPagExtra->lotacao}   "; 
			    $sWhererhLotaVinc .= " and rh25_vinculo = '{$oPagExtra->vinculo}' "; 
			    $sWhererhLotaVinc .= " and rh25_anousu  = {$iAnoUsu}              ";
			          
			    $sSqlrhLotaVinc = $oDaorhLotaVinc->sql_query_file(null,$sCamposrhLotaVinc,null,$sWhererhLotaVinc);
			    $rsProjAtivRec  = $oDaorhLotaVinc->sql_record($sSqlrhLotaVinc);
			          
			    
			    if ( $oDaorhLotaVinc->numrows == 0 ){
			      throw new Exception("Verifique recurso e projeto atividade da lota��o {$oPagExtra->lotacao}");
			    } else {
			      $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
			      $iRecurso = $oProjAtivRec->recurso;
			    }         			
    			
			    /**
			     *  Verifica se j� existe  registros na rhslipfolha apartir dos dados encontrados
			     */
          $sWhereSlipFolha  = "     rh79_recurso        = {$iRecurso}    ";
          $sWhereSlipFolha .= " and rh79_anousu         = {$iAnoUsu}     ";
          $sWhereSlipFolha .= " and rh79_mesusu         = {$iMesUsu}     ";
          $sWhereSlipFolha .= " and rh79_siglaarq       = '{$sSigla}'    ";
          $sWhereSlipFolha .= " and rh79_concarpeculiar = '{$iCaract}'   ";
          $sWhereSlipFolha .= " and rh79_tipoempenho    = 1              ";
          $sWhereSlipFolha .= " and rh79_tabprev        = 0              ";
          $sWhereSlipFolha .= " and rh79_seqcompl       = '{$oPagExtra->pessoalmov}' ";
          
          $sSqlSlipFolha    = $oDaorhSlipFolha->sql_query_file(null,"rh79_sequencial",null,$sWhereSlipFolha);
          $rsSlipFolha      = $oDaorhSlipFolha->sql_record($sSqlSlipFolha);
           
          /**
           *  Caso exista ent�o � utilizado o mesmo sequencial para inclus�o das rubricas, 
           *  caso contr�io � inserido un registro novo
           */
          if ( $oDaorhSlipFolha->numrows > 0 ) {
                          
            $oRhSlipFolha  = db_utils::fieldsMemory($rsSlipFolha,0);
            $iCodSlipFolha = $oRhSlipFolha->rh79_sequencial;
          
          } else {
            
            $oDaorhSlipFolha->rh79_recurso        = $iRecurso;
            $oDaorhSlipFolha->rh79_anousu         = $iAnoUsu;
            $oDaorhSlipFolha->rh79_mesusu         = $iMesUsu;
            $oDaorhSlipFolha->rh79_siglaarq       = $sSigla;
            $oDaorhSlipFolha->rh79_tipoempenho    = 1;
            $oDaorhSlipFolha->rh79_tabprev        = '0';
            $oDaorhSlipFolha->rh79_concarpeculiar = $iCaract;
            $oDaorhSlipFolha->rh79_seqcompl       = "{$oPagExtra->pessoalmov}";
            
            $oDaorhSlipFolha->incluir(null);
            
            if ( $oDaorhSlipFolha->erro_status == '0' ) {
               throw new Exception($oDaorhSlipFolha->erro_msg);
            }

            $iCodSlipFolha = $oDaorhSlipFolha->rh79_sequencial; 
            
          }
               
          /**
           *  Inclus�o das rubricas 
           */
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oPagExtra->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oPagExtra->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = $oPagExtra->valor;
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oPagExtra->pd;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 3;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodSlipFolha;
          $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
          }

          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPagExtra->retencao;
          $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
                      
          if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
          }          
    		}
    	}
    	
    } 	

  }
  
  
  /**
   * Gera dados referentes a devolu��es de empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o 
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function geraDevolucoesEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null) {
    
    $sMsgErro = 'Gera��o de devolu��es da folha';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }    
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);     
    
    $oDaorhLotaVinc                    = db_utils::getDao('rhlotavinc');
    $oDaorhDevolucaoFolha              = db_utils::getDao('rhdevolucaofolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhDevolucaoFolhaRhEmpRubrica  = db_utils::getDao('rhdevolucaofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    
    switch ($sSigla) {
      case "r48":
        $sTabela      = "gerfcom";
      break;
      case "r14":
        $sTabela      = "gerfsal";
      break;
      case "r35":
        $sTabela      = "gerfs13";
      break;
      case "r22":
        $sTabela      = "gerfadi";
      break;
      case "r20":
        $sTabela      = "gerfres";
      break;                    
    }    

    /**
     *  Consulta todas rubricas de devolu��o
     */
    $sSqlDevolucao  = " select {$sSigla}_rubric     as rubric,                                                                   ";
    $sSqlDevolucao .= "        rh02_seqpes          as pessoalmov,                                                               ";
    $sSqlDevolucao .= "        {$sSigla}_pd         as pd,                                                                       ";
    $sSqlDevolucao .= "        {$sSigla}_valor      as valor,                                                                    ";
    $sSqlDevolucao .= "        rh30_vinculo         as vinculo,                                                                  ";
    $sSqlDevolucao .= "        rh02_lota            as lotacao,                                                                  ";
    $sSqlDevolucao .= "        r70_concarpeculiar   as caract,                                                                   ";
    $sSqlDevolucao .= "        rh75_retencaotiporec as retencao                                                                  ";
    $sSqlDevolucao .= "   from {$sTabela}                                                                                        ";
    $sSqlDevolucao .= "        inner join rhpessoalmov    on rhpessoalmov.rh02_anousu       = {$sTabela}.{$sSigla}_anousu        ";
    $sSqlDevolucao .= "                                  and rhpessoalmov.rh02_mesusu       = {$sTabela}.{$sSigla}_mesusu        ";
    $sSqlDevolucao .= "                                  and rhpessoalmov.rh02_regist       = {$sTabela}.{$sSigla}_regist        ";
    $sSqlDevolucao .= "        inner join rhlota          on rhlota.r70_codigo              = rhpessoalmov.rh02_lota             ";
    $sSqlDevolucao .= "        inner join rhrubretencao   on rhrubretencao.rh75_rubric      = {$sTabela}.{$sSigla}_rubric        "; 
    $sSqlDevolucao .= "                                  and rhrubretencao.rh75_instit      = {$sTabela}.{$sSigla}_instit        ";
    $sSqlDevolucao .= "        inner join retencaotiporec on retencaotiporec.e21_sequencial = rhrubretencao.rh75_retencaotiporec ";
    $sSqlDevolucao .= "        inner join rhregime        on rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg           ";
    $sSqlDevolucao .= "                                  and rhregime.rh30_instit           = rhpessoalmov.rh02_instit           ";
    $sSqlDevolucao .= "  where e21_retencaotiporecgrupo = 4                                                                      ";
    $sSqlDevolucao .= "    and {$sSigla}_anousu = {$iAnoUsu}                                                                     ";
    $sSqlDevolucao .= "    and {$sSigla}_mesusu = {$iMesUsu}                                                                     ";
    $sSqlDevolucao .= "    and {$sSigla}_instit = {$iInstit}                                                                     ";
    $sSqlDevolucao .= "  and rh02_seqpes        in({$sListaRescisoes})                                                           ";
    
    $rsDevolucao = db_query($sSqlDevolucao);
    
    if ( $rsDevolucao ) {
      
      $iLinhasDevolucao = pg_num_rows($rsDevolucao);
      
      if ( $iLinhasDevolucao > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
          
          $oDevolucao = db_utils::fieldsMemory($rsDevolucao,$iInd);
          $iCaract   = $oDevolucao->caract;
          
          /**
           * Consulta recurso apartir da lota��o e v�nculo achado no consulta anterior
           */          
          $sCamposrhLotaVinc = " rh25_recurso as recurso   ";
                
          $sWhererhLotaVinc  = "     rh25_codigo  = {$oDevolucao->lotacao}   "; 
          $sWhererhLotaVinc .= " and rh25_vinculo = '{$oDevolucao->vinculo}' "; 
          $sWhererhLotaVinc .= " and rh25_anousu  = {$iAnoUsu}              ";
                
          $sSqlrhLotaVinc = $oDaorhLotaVinc->sql_query_file(null,$sCamposrhLotaVinc,null,$sWhererhLotaVinc);
          $rsProjAtivRec  = $oDaorhLotaVinc->sql_record($sSqlrhLotaVinc);
                
          if ( $oDaorhLotaVinc->numrows == 0 ){
            throw new Exception("Verifique recurso e projeto atividade da lota��o {$oDevolucao->lotacao}");
          } else {
            $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
            $iRecurso = $oProjAtivRec->recurso;
          }               
          
          /**
           *  Verifica se j� existe registro na rhdevolucaofolha apartir dos dados encontrado
           */
          $sWhereDevolucaoFolha  = "     rh69_recurso        = {$iRecurso}    ";
          $sWhereDevolucaoFolha .= " and rh69_anousu         = {$iAnoUsu}     ";
          $sWhereDevolucaoFolha .= " and rh69_mesusu         = {$iMesUsu}     ";
          $sWhereDevolucaoFolha .= " and rh69_siglaarq       = '{$sSigla}'    ";
          $sWhereDevolucaoFolha .= " and rh69_concarpeculiar = '{$iCaract}'   ";
          $sWhereDevolucaoFolha .= " and rh69_tipoempenho    = 1              ";
          $sWhereDevolucaoFolha .= " and rh69_tabprev        = 0              ";
          $sWhereDevolucaoFolha .= " and rh69_seqcompl       = {$oPagExtra->rh02_seqpes} ";
          
          $sSqlDevolucaoFolha    = $oDaorhDevolucaoFolha->sql_query_file(null,"rh69_sequencial",null,$sWhereDevolucaoFolha);
          $rsDevolucaoFolha      = $oDaorhDevolucaoFolha->sql_record($sSqlDevolucaoFolha);
           
          /**
           *  Caso exista ent�o � utilizado o mesmo sequencial para inclus�o das rubricas, 
           *  caso contr�io � inserido un registro novo
           */          
          if ( $oDaorhDevolucaoFolha->numrows > 0 ) {
                          
            $oRhDevolucaoFolha  = db_utils::fieldsMemory($rsDevolucaoFolha,0);
            $iCodDevolucaoFolha = $oRhDevolucaoFolha->rh69_sequencial;
          
          } else {
            
            $oDaorhDevolucaoFolha->rh69_recurso        = $iRecurso;
            $oDaorhDevolucaoFolha->rh69_anousu         = $iAnoUsu;
            $oDaorhDevolucaoFolha->rh69_mesusu         = $iMesUsu;
            $oDaorhDevolucaoFolha->rh69_siglaarq       = $sSigla;
            $oDaorhDevolucaoFolha->rh69_tipoempenho    = 1;
            $oDaorhDevolucaoFolha->rh69_tabprev        = '0';
            $oDaorhDevolucaoFolha->rh69_concarpeculiar = "{$iCaract}";
            $oDaorhDevolucaoFolha->rh69_seqcompl       = "{$oPagExtra->rh02_seqpes}";
            
            $oDaorhDevolucaoFolha->incluir(null);
            
            if ( $oDaorhDevolucaoFolha->erro_status == '0' ) {
               throw new Exception($oDaorhDevolucaoFolha->erro_msg);
            }

            $iCodDevolucaoFolha = $oDaorhDevolucaoFolha->rh69_sequencial; 
            
          }
               
          /**
           * Incluis�o das rubricas
           */          
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDevolucao->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDevolucao->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = $oDevolucao->valor;
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDevolucao->pd;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 4;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhDevolucaoFolhaRhEmpRubrica->rh87_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhDevolucaoFolhaRhEmpRubrica->rh87_devolucaofolha        = $iCodDevolucaoFolha;
          $oDaorhDevolucaoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhDevolucaoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhDevolucaoFolhaRhEmpRubrica->erro_msg);
          }

          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDevolucao->retencao;
          $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
                      
          if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
          }          
        }
      }
    }   
  }  
  
  
  /**
   * Consulta o estrutural apartir da lota��o  
   *
   * @param  integer $iAnoUsu
   * @param  integer $iLotacao
   * @param  string  $sVinculo
   * @param  integer $iElemento
   * @return object 
   */
  public function getEstrututal($iAnoUsu,$iLotacao,$sVinculo,$iElemento){

  	if ( trim($iAnoUsu) == '' ) {
  		throw new Exception('Exerc�cio n�o informado!');
  	}
    if ( trim($iLotacao) == '' ) {
      throw new Exception('Lota��o n�o informada!');
    }
    if ( trim($sVinculo) == '' ) {
      throw new Exception('Vinculo n�o informado!');
    }
    if ( trim($iElemento) == '' ) {
      throw new Exception('Elemento n�o informado!');
    }
  	
    $oDaoOrcParametro               = db_utils::getDao('orcparametro');
    $oDaoOrcElemento                = db_utils::getDao('orcelemento');
    $oDaoOrcDotacao                 = db_utils::getDao('orcdotacao');
    $oDaorhLotaExe                  = db_utils::getDao('rhlotaexe');
    $oDaorhLotaVinc                 = db_utils::getDao('rhlotavinc');
    $oDaorhLotaVincEle              = db_utils::getDao('rhlotavincele');
    $oDaorhLotaVincRec              = db_utils::getDao('rhlotavincrec');
    $oDaorhLotaVincAtiv             = db_utils::getDao('rhlotavincativ');
    
    $sSqlParametro = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
    $rsParametro   = $oDaoOrcParametro->sql_record($sSqlParametro);
     
    if ( $oDaoOrcParametro->numrows > 0 ) {
      $oParametro = db_utils::fieldsMemory($rsParametro,0);
    } else { 
      throw new Exception("Configure os par�metros do or�amento para o ano {$iAnoUsu}!");
    }      
    
    /**
     *  Consulta Org�o e Unidade
     */
    $sCamposrhLotaExe  = " rh26_orgao   as orgao, ";
    $sCamposrhLotaExe .= " rh26_unidade as unidade";
    $sSqlrhLotaExe     = $oDaorhLotaExe->sql_query_file($iAnoUsu,$iLotacao,$sCamposrhLotaExe);
    $rsOrgUnid         = $oDaorhLotaExe->sql_record($sSqlrhLotaExe);

          
    if ( $oDaorhLotaExe->numrows == 0 ) {
      throw new Exception("{$sMsgErro}, verifique �rg�o e unidade da lota��o {$iLotacao}");
    } else {
      $oOrgUnid = db_utils::fieldsMemory($rsOrgUnid,0);
      $iOrgao   = $oOrgUnid->orgao;
      $iUnidade = $oOrgUnid->unidade;
    }           
            
    /**
     *  Consulta Recurso e Proj. Ativ. 
     */
    $sCamposrhLotaVinc  = "rh25_codlotavinc as lotavinc, ";
    $sCamposrhLotaVinc .= "rh25_projativ    as projativ, ";
    $sCamposrhLotaVinc .= "rh25_funcao      as funcao,   ";
    $sCamposrhLotaVinc .= "rh25_subfuncao   as subfuncao,";
    $sCamposrhLotaVinc .= "rh25_programa    as programa, ";
    $sCamposrhLotaVinc .= "rh25_recurso     as recurso   ";
          
    $sWhererhLotaVinc   = "     rh25_codigo  = {$iLotacao}   "; 
    $sWhererhLotaVinc  .= " and rh25_vinculo = '{$sVinculo}' "; 
    $sWhererhLotaVinc  .= " and rh25_anousu  = {$iAnoUsu}    ";
          
    $sSqlrhLotaVinc = $oDaorhLotaVinc->sql_query_file(null,$sCamposrhLotaVinc,null,$sWhererhLotaVinc);
    $rsProjAtivRec  = $oDaorhLotaVinc->sql_record($sSqlrhLotaVinc);
          
    if ( $oDaorhLotaVinc->numrows == 0 ){
      throw new Exception("Verifique recurso e projeto atividade da lota��o {$iLotacao}");
    } else {
      $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
    }           
          
    /**
     *   Caso exista algum registro na tabela rhlotavincele em que o
     *    rh28_codlotavinc = $oGerador->lotavinc e rh28_codele = $oGerador->elemento
     *     - Se forem diferentes:
     *        O elemento a ser gravado na tabela rhempenhofolha sera o $oGerador->elemento
     *        O projeto atividade a ser gravado na tabela rhempenhofolha ser� o $oProjAtivRec->projativ
     *     - Se forem iguais:
     *        O elemento a ser gravado na tabela rhempenhofolhafolha ser� o $oDadosNovos->elementonovo
     *
     *   Caso exista algum registro na tabela rhlotavincativ em que o 
     *     rh28_codlotavinc = $oProjAtivRec->lotavinc e rh28_codelenov = $oDadosNovos->elementonovo
     *     - Se tiver algum registro, o projeto atividade a ser gravado na tabela rhempenhofolha 
     *        ser� $oNovoProjAtiv->projativnovo
     *     - Caso contrario, o projeto atividade a ser gravado na tabela rhempenhofolha 
     *        ser� $oProjAtivRec->projativ
     *    
     *   Caso exista algum registro na tabela rhlotavincrec em que o
     *     rh43_codlotavinc = $oProjAtivRec->lotavinc e rh43_codelenov = $oDadosNovos->elementonovo
     *     - Se tiver algum registro, o recurso a ser gravado na tabela rhempenhofolha
     *        ser� $oNovoRecurso->recursonovo
     *     - Caso contr�rio, o recurso a ser gravado na tabela rhempenhofolha
     *        ser� $oProjAtivRec->recurso        
     */
          
    $sCamposrhLotaVincEle = "rh28_codelenov as elementonovo";
    $sSqlrhLotaVincEle    = $oDaorhLotaVincEle->sql_query_file($oProjAtivRec->lotavinc,$iElemento,$sCamposrhLotaVincEle);
    $rsTestaNovos         = $oDaorhLotaVincEle->sql_record($sSqlrhLotaVincEle);
           
    $iProjAtiv  = $oProjAtivRec->projativ;
    $iFuncao    = $oProjAtivRec->funcao;
    $iSubFuncao = $oProjAtivRec->subfuncao;
    $iPrograma  = $oProjAtivRec->programa;
    $iRecurso   = $oProjAtivRec->recurso;          
          
    if ( $oDaorhLotaVincEle->numrows > 0 ){
            
      $oDadosNovos = db_utils::fieldsMemory($rsTestaNovos,0);
      $iElemento   = $oDadosNovos->elementonovo;
            
      $sCamposNovoProjAtiv  = " rh39_projativ  as projativnovo,  ";
      $sCamposNovoProjAtiv .= " rh39_funcao    as funcaonovo,    ";
      $sCamposNovoProjAtiv .= " rh39_subfuncao as subfuncaonovo, ";
      $sCamposNovoProjAtiv .= " rh39_programa  as programanovo   ";
      $sWhereNovoProjAtiv  = "     rh39_codlotavinc = {$oProjAtivRec->lotavinc}    "; 
      $sWhereNovoProjAtiv .= " and rh39_codelenov   = {$oDadosNovos->elementonovo} ";
      $sWhereNovoProjAtiv .= " and rh39_anousu      = {$iAnoUsu}                   ";
              
      $sSqlNovoProjAtiv    = $oDaorhLotaVincAtiv->sql_query_file(null,null,$sCamposNovoProjAtiv,null,$sWhereNovoProjAtiv);
      $rsNovoProjAtiv      = $oDaorhLotaVincAtiv->sql_record($sSqlNovoProjAtiv);
              
      if ( $oDaorhLotaVincAtiv->numrows > 0 ) {
        $oNovoProjAtiv = db_utils::fieldsMemory($rsNovoProjAtiv,0);
        $iProjAtiv      = $oNovoProjAtiv->projativnovo;
        $iFuncao        = $oNovoProjAtiv->funcaonovo;
        $iSubFuncao     = $oNovoProjAtiv->subfuncaonovo;
        $iPrograma      = $oNovoProjAtiv->programanovo;
      }

      $sCamposNovoRecurso = "rh43_recurso as recursonovo ";
      $sWhereNovoRecurso  = "    rh43_codlotavinc = {$oProjAtivRec->lotavinc}    ";
      $sWhereNovoRecurso .= "and rh43_codelenov   = {$oDadosNovos->elementonovo} ";
              
      $sSqlNovoRecurso  = $oDaorhLotaVincRec->sql_query_file(null,null,$sCamposNovoRecurso,null,$sWhereNovoRecurso);
      $rsNovoRecurso = $oDaorhLotaVincRec->sql_record($sSqlNovoRecurso);
            
      if ( $oDaorhLotaVincRec->numrows > 0 ) {
        $oNovoRecurso = db_utils::fieldsMemory($rsNovoRecurso,0);
        $iRecurso     = $oNovoRecurso->recursonovo;
      }
              
    }
            
            
    if ( $oParametro->o50_subelem == "f" ) {
              
      $sCamposElemento = "substr(o56_elemento,1,7)||'000000' as elemento";
      $sWhereElemento  = "     o56_codele = {$iElemento} "; 
      $sWhereElemento .= " and o56_anousu = {$iAnoUsu}   ";
             
      $sSqlElemento = $oDaoOrcElemento->sql_query_file(null,null,$sCamposElemento,null,$sWhereElemento);
      $rsElemento   = $oDaoOrcElemento->sql_record($sSqlElemento);
             
      if ( $oDaoOrcElemento->numrows > 0 ) {
        $oElemento   = db_utils::fieldsMemory($rsElemento,0);
        $sWhereParam = " and o56_elemento='{$oElemento->elemento}' ";
      }
             
    } else {
      $sWhereParam = " and o58_codele = {$iElemento}";
    }            

    /**
     *  Consulta Dota��o
     */          
    $sCamposDotacao = "o58_coddot as dotacao";
         
    $sWhereDotacao  = "     o58_anousu   = {$iAnoUsu}     "; 
    $sWhereDotacao .= " and o58_orgao    = {$iOrgao}      ";
    $sWhereDotacao .= " and o58_unidade  = {$iUnidade}    ";
    $sWhereDotacao .= " and o58_projativ = {$iProjAtiv}   ";
    
    if(!empty($iFuncao)){
      $sWhereDotacao .= " and o58_funcao = {$iFuncao}   ";
    }
    if(!empty($iSubFuncao)){
      $sWhereDotacao .= " and o58_subfuncao = {$iSubFuncao}   ";
    }
    if(!empty($iPrograma)){
      $sWhereDotacao .= " and o58_programa = {$iPrograma}   ";
    }
    
    $sWhereDotacao .= " and o58_codigo   = {$iRecurso}    ";
    $sWhereDotacao .= $sWhereParam;
         
    $sSqlDotacao    = $oDaoOrcDotacao->sql_query_ele(null,null,$sCamposDotacao,null,$sWhereDotacao);
    $rsDotacao      = $oDaoOrcDotacao->sql_record($sSqlDotacao);
          
    if ( $oDaoOrcDotacao->numrows > 0 ) {
      $oDotacao = db_utils::fieldsMemory($rsDotacao,0);
      $iDotacao = $oDotacao->dotacao;
    } else {
      $iDotacao = '';
    }

    $oEstrutural = new stdClass();
    
    $oEstrutural->iOrgao     = $iOrgao; 
    $oEstrutural->iUnidade   = $iUnidade;
    $oEstrutural->iProjAtiv  = $iProjAtiv;
    $oEstrutural->iFuncao    = $iFuncao;              
    $oEstrutural->iSubfuncao = $iSubFuncao;              
    $oEstrutural->iPrograma  = $iPrograma;    
    $oEstrutural->iRecurso   = $iRecurso;
    $oEstrutural->iElemento  = $iElemento;
    $oEstrutural->iDotacao   = $iDotacao;

    return $oEstrutural;
    
    
  }
  
  
  /**
   * Gera dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   */
  public function geraDadosEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Gera��o de empenhos FGTS abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    
    
    $oDaoOrcParametro               = db_utils::getDao('orcparametro');
    $oDaoOrcElemento                = db_utils::getDao('orcelemento');
    $oDaoOrcDotacao                 = db_utils::getDao('orcdotacao');
    $oDaorhLotaExe                  = db_utils::getDao('rhlotaexe');
    $oDaorhLotaVinc                 = db_utils::getDao('rhlotavinc');
    $oDaorhLotaVincEle              = db_utils::getDao('rhlotavincele');
    $oDaorhLotaVincRec              = db_utils::getDao('rhlotavincrec');
    $oDaorhLotaVincAtiv             = db_utils::getDao('rhlotavincativ');
    $oDaorhEmpenhoFolha             = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica      = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica = db_utils::getDao('rhempenhofolharhemprubrica');
    $clgeradorsql                   = new cl_gera_sql_folha;
    
    $clgeradorsql->inicio_rh= false;
    $clgeradorsql->usar_pes = true;
    $clgeradorsql->usar_doc = true;
    $clgeradorsql->usar_cgm = true;
    $clgeradorsql->usar_atv = true;
    $clgeradorsql->usar_rel = true;
    $clgeradorsql->usar_lot = true;
    
    $sSqlParametro = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
    $rsParametro   = $oDaoOrcParametro->sql_record($sSqlParametro);
     
    if ( $oDaoOrcParametro->numrows > 0 ) {
      $oParametro = db_utils::fieldsMemory($rsParametro,0);
    } else { 
      throw new Exception("{$sMsgErro}, configure os par�metros do or�amento para o ano {$iAnoUsu}!");
    }  

    /**
     *  Retorna as siglas referente aos tipos de folha
     */
    try {
    	$aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( Exception $eException ){
    	throw new Exception($eException->getMessage());
    }

    
    $sSqlGerador = '';
    
    
    foreach ( $aSiglas as $iInd => $sSigla ){

      try {
	      $lLiberada = $this->isLiberada($sSigla,
	                                     3,
	                                     $iAnoUsu,
	                                     $iMesUsu);
	    } catch ( Exception $eException ){
	      throw new Exception("{$sMsgErro}, {$eException->getMessage()}");
	    }    
	    
	    if ( $lLiberada ) {
	      throw new Exception("{$sMsgErro}, empenho liberado!");
	    }    	
    
	    $sCampos  = "distinct rh30_vinculo     as vinculo,     ";
	    $sCampos .= "         rh02_lota        as lotacao,     ";
	    $sCampos .= "         rh23_codele      as elemento,    ";
      $sCampos .= "         {$sSigla}_rubric as rubric,      ";
      $sCampos .= "         {$sSigla}_regist as regist,      ";
      $sCampos .= "         rh02_seqpes      as pessoalmov,  ";
      $sCampos .= "         {$sSigla}_pd     as pd,          ";
      $sCampos .= "         {$sSigla}_quant  as quant,       ";
      $sCampos .= "         {$sSigla}_valor  as valor,       ";
      $sCampos .= "         {$sSigla}_anousu as anousu,      ";
      $sCampos .= "         {$sSigla}_mesusu as mesusu,      ";	    
      $sCampos .= "         {$sSigla}_semest as semestre,    ";
      $sCampos .= "         '{$sSigla}'        as sigla,     ";
      $sCampos .= "         r70_concarpeculiar as caract     ";
      
	
	    $sWhereGerador  = "     rh23_codele is not null   ";
	    $sWhereGerador .= " and {$sSigla}_rubric = 'R991' ";
	    
	    if ( trim($sSqlGerador) != '' ) {
	    	$sSqlGerador .= ' union all ';
	    }
	    
	    $sSqlGerador   .= $clgeradorsql->gerador_sql( $sSigla,
	                                                  $iAnoUsu,
	                                                  $iMesUsu,
	                                                  "",
	                                                  "",
	                                                  $sCampos,
	                                                  "",
	                                                  $sWhereGerador,
	                                                  $iInstit);
    }
     
    $rsGerador = db_query($sSqlGerador);                                             
      
    if ( $rsGerador ) {
        
      $iLinhasGerador = pg_num_rows($rsGerador);
        
      if ( $iLinhasGerador > 0 ) {
          
        for ( $iInd=0; $iInd < $iLinhasGerador; $iInd++ ) {
            
          $oGerador = db_utils::fieldsMemory($rsGerador,$iInd);
          $iCaract  = $oGerador->caract;
          
          try {
            
            $oEstrututal = $this->getEstrututal(db_getsession('DB_anousu'),$oGerador->lotacao,$oGerador->vinculo,$oGerador->elemento);
            
            $iOrgao     = $oEstrututal->iOrgao; 
            $iUnidade   = $oEstrututal->iUnidade;
            $iProjAtiv  = $oEstrututal->iProjAtiv;
		        $iFuncao    = $oEstrututal->iFuncao;
		        $iSubFuncao = $oEstrututal->iSubFuncao;
		        $iPrograma  = $oEstrututal->iPrograma;    
            $iRecurso   = $oEstrututal->iRecurso;
            $iElemento  = $oEstrututal->iElemento;
            $iDotacao   = $oEstrututal->iDotacao;
            
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro},\n".$eException->getMessage());
          }          
          
          $sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento}            ";
          $sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}             ";
          $sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}               ";
          $sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv}            ";
          if(!empty($iFuncao)) {
  			    $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
		      }
		      if(!empty($iSubFuncao)) {
			      $sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
		      }
		      if(!empty($iPrograma)) {
			      $sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
		      }          
          $sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}             ";
          $sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}              ";
          $sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}              ";
          $sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$oGerador->sigla}'    ";
          $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'            ";
          $sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 3                       ";
          $sWhereEmpenhoFolha .= " and rh72_tabprev        = 0                       ";
          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = '{$oGerador->semestre}' ";
          
          if ( trim($iDotacao) !== '' ){
            $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
          } else {
            $sWhereEmpenhoFolha .= " and rh72_coddot = 0            ";
          }
          
          $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha);
           
          if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
                          
            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
            $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
          
          } else {
            
            $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
            $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
            $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
            $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
            $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
            $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;              
            $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;              
            $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;
            $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
            $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
            $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
            $oDaorhEmpenhoFolha->rh72_siglaarq       = $oGerador->sigla;
            $oDaorhEmpenhoFolha->rh72_tipoempenho    = 3;
            $oDaorhEmpenhoFolha->rh72_tabprev        = '0';
            $oDaorhEmpenhoFolha->rh72_seqcompl       = $oGerador->semestre;
            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
            
            $oDaorhEmpenhoFolha->incluir(null);
            
            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
               throw new Exception($oDaorhEmpenhoFolha->erro_msg);
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          
          try {
            $aExcecoes = $this->getExcessoesEmpenhoFolha($oGerador->rubric,$iAnoUsu,$iInstit);
          } catch (Exception $eException){
            throw new Exception($eException->getMessage());
          }

          if ( !empty($aExcecoes) ) {

            $iOrgao     = $aExcecoes[0]->rh74_orgao;
            $iUnidade   = $aExcecoes[0]->rh74_unidade;
            $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
		        $iFuncao    = $aExcecoes[0]->rh74_funcao;
		        $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
		        $iPrograma  = $aExcecoes[0]->rh74_programa;    
            $iRecurso   = $aExcecoes[0]->rh74_recurso;
            $iCaract    = "{$aExcecoes[0]->rh74_concarpeculiar}";
            
            $iDotacao  = $this->getDotacaoByFiltro($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElemento, $iAnoUsu, $iFuncao, $iSubFuncao, $iPrograma);
                  
            $sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento}            ";
            $sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}             ";
            $sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}               ";
            $sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv}            ";
          	if(!empty($iFuncao)) {
			        $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
			      }
			      if(!empty($iSubFuncao)) {
			        $sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
			      }
			      if(!empty($iPrograma)) {
			        $sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
			      }            
            $sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}             ";
            $sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}              ";
            $sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}              ";
            $sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$oGerador->sigla}'    ";
            $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'            ";            
            $sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 3                       ";
	          $sWhereEmpenhoFolha .= " and rh72_tabprev        = 0                       ";
	          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = '{$oGerador->semestre}' ";
                  
            if ( trim($iDotacao) !== '' ){
              $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
            } else {
              $sWhereEmpenhoFolha .= " and rh72_coddot = 0        ";
            }                 
                  
            $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
            $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha); 
                  
            if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
                   
              $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
              $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
                    
            } else {
                    
              $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
              $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
              $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
              $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
              $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
              $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;              
              $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;              
              $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;              
              $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
              $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
              $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
              $oDaorhEmpenhoFolha->rh72_siglaarq       = $oGerador->sigla;
	            $oDaorhEmpenhoFolha->rh72_tipoempenho    = 3;
	            $oDaorhEmpenhoFolha->rh72_tabprev        = '0';
	            $oDaorhEmpenhoFolha->rh72_seqcompl       = $oGerador->semestre;
	            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";              
                    
              $oDaorhEmpenhoFolha->incluir(null);
                    
              if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
                throw new Exception($oDaorhEmpenhoFolha->erro_msg);
              }
              $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            }
          }
               
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oGerador->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oGerador->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar(($oGerador->valor * 0.08),'p');
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = 1;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 1;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
          $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
          }                    
          
        } 
        
      } else {
        throw new Exception("{$sMsgErro}, nenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
      }
    } else {
      throw new Exception("{$sMsgErro}, Erro na consulta!");
    }
    
  }
  
  
  /**
   * Retorna os dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   * @return array
   */  
  public function getRubricasEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Consulta de empenhos FGTS abortada';
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, Tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    try {
    	$aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( Exception $eException ) {
    	throw new Exception($eException->getMessage());
    }
    
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolha  = "     rh73_rubric      = 'R991'     ";
    $sWhereEmpenhosFolha .= " and rh72_anousu      = {$iAnoUsu} ";
    $sWhereEmpenhosFolha .= " and rh72_mesusu      = {$iMesUsu} ";
    $sWhereEmpenhosFolha .= " and rh73_instit      = {$iInstit} ";
    $sWhereEmpenhosFolha .= " and rh72_tipoempenho = 3          ";
    $sWhereEmpenhosFolha .= " and rh72_tabprev     = 0          ";
    $sWhereEmpenhosFolha .= " and rh72_siglaarq in ('".implode("','",$aSiglas)."')";
    
    $sSqlEmpenhosFolha = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                "*",
			                                                                null,
			                                                                $sWhereEmpenhosFolha);
                                                                
    $rsEmpenhosFolha   = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolha);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolha = db_utils::getColectionByRecord($rsEmpenhosFolha);
    } else {
      $aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu   Exerc�cio da Folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   Institui��o
   */
  public function excluiDadosEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Exclus�o de empenhos FGTS abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    $oDaorhEmpenhoFolha        = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    try {
      $aDadosEmpenhos = $this->getRubricasEmpenhosFGTS($sTipo,$iAnoUsu,$iMesUsu,$iInstit);
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }

    
    if ( count($aDadosEmpenhos) > 0  ) {
       
      $aListaEmpenhos = array();
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $aListaEmpenhos[] = $oEmpenhoFolha->rh72_sequencial;
        if ( trim($oEmpenhoFolha->rh76_sequencial) != '' ) {
          throw new Exception("{$sMsgErro}, \nRegistros j� empenhados!");
        }
      }
      
      $aListaEmpenhos = array_unique($aListaEmpenhos);
      $sListaEmpenhos = implode(',',$aListaEmpenhos);
      
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }      
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
        }                       
      }
      
      $sWhereEmpenhos  = "     rh72_sequencial in ({$sListaEmpenhos}) ";
      $sWhereEmpenhos .= " and rh73_sequencial is null                ";
      
      $sSqlEmpenhos    = $oDaorhEmpenhoFolha->sql_query_rubricas(null,"rh72_sequencial",null,$sWhereEmpenhos);
      
      $rsEmpenhosFolha = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhos);
      $iLinhasEmpenhos = $oDaorhEmpenhoFolha->numrows;
      
      
      if ( $iLinhasEmpenhos > 0 ) {
        for ( $iInd=0; $iInd < $iLinhasEmpenhos; $iInd++ ) {
        	$oEmpenhoFolha = db_utils::fieldsMemory($rsEmpenhosFolha,$iInd);
          $oDaorhEmpenhoFolha->excluir($oEmpenhoFolha->rh72_sequencial);
          if ( $oDaorhEmpenhoFolha->erro_status == "0") {
            throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolha->erro_msg}");
          }
        }
      }
    }
    
  }  
  
  
  /**
   * Retorna um array contendo as siglas referente ao tipo de folha dependendo do tipo
   * passado por par�metro 
   *
   * @param string $sTipo Tipo ( m = Mensal ou d = 13� )
   * @return array
   */
	public function getSiglasFGTS($sTipo=''){
		
		if ( trim($sTipo) == '') {
			throw new Exception("Tipo de folha n�o informado!");
		}
		
    if ( $sTipo == 'm') {
      $aSiglas = array('r14',
                       'r48',
                       'r20');
    } else if( $sTipo == 'd') {
      $aSiglas = array('r35');
    } else {
    	throw new Exception("Tipo de folha difere de mensal e 13�!");
    }
    
    return $aSiglas;
    
	}
	
	
  /**
   * Gera dados para empenhos da folha referente a Previd�ncia ( rubricas = R992 )
   *
   * @param string  $sTipo      Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu    Exerc�cio da Folha
   * @param integer $iMesUsu    M�s da Folha
   * @param string  $sListaPrev Lista de Previd�ncias 
   * @param integer $iInstit    Institui��o
   */	
  public function geraDadosEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
    
    $sMsgErro = 'Gera��o de empenhos da previd�ncia abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($sListaPrev) == '' ) {
      throw new Exception("{$sMsgErro}, previd�ncia n�o informada!");
    }    
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    
    
    $oDaoOrcParametro               = db_utils::getDao('orcparametro');
    $oDaoOrcElemento                = db_utils::getDao('orcelemento');
    $oDaoOrcDotacao                 = db_utils::getDao('orcdotacao');
    $oDaorhLotaExe                  = db_utils::getDao('rhlotaexe');
    $oDaorhLotaVinc                 = db_utils::getDao('rhlotavinc');
    $oDaorhLotaVincEle              = db_utils::getDao('rhlotavincele');
    $oDaorhLotaVincRec              = db_utils::getDao('rhlotavincrec');
    $oDaorhLotaVincAtiv             = db_utils::getDao('rhlotavincativ');
    $oDaorhEmpenhoFolha             = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica      = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica = db_utils::getDao('rhempenhofolharhemprubrica');
    $clgeradorsql                   = new cl_gera_sql_folha;
    
    $clgeradorsql->inicio_rh= false;
    $clgeradorsql->usar_pes = true;
    $clgeradorsql->usar_doc = true;
    $clgeradorsql->usar_cgm = true;
    $clgeradorsql->usar_atv = true;
    $clgeradorsql->usar_rel = true;
    $clgeradorsql->usar_lot = true;
    
    
    $sSqlParametro = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
    $rsParametro   = $oDaoOrcParametro->sql_record($sSqlParametro);
     
    if ( $oDaoOrcParametro->numrows > 0 ) {
      $oParametro = db_utils::fieldsMemory($rsParametro,0);
    } else { 
      throw new Exception("{$sMsgErro}, configure os par�metros do or�amento para o ano {$iAnoUsu}!");
    }  
    
    try {
      $aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( Exception $eException ){
      throw new Exception($eException->getMessage());
    }

    
    $sSqlGerador = '';
    
    foreach ( $aSiglas as $iInd => $sSigla ){    
    
      try {
	      $lLiberada = $this->isLiberada($sSigla,
	                                     2,
	                                     $iAnoUsu,
	                                     $iMesUsu); 
	    } catch ( Exception $eException ){
	      throw new Exception("{$sMsgErro}, {$eException->getMessage()}");
	    }    
	    
	    if ( $lLiberada ) {
	      throw new Exception("{$sMsgErro}, empenho liberado!");
	    }    	
    	
      $sCampos  = "distinct rh30_vinculo     as vinculo,     ";
      $sCampos .= "         rh02_lota        as lotacao,     ";
      $sCampos .= "         rh23_codele      as elemento,    ";
      $sCampos .= "         {$sSigla}_rubric as rubric,      ";
      $sCampos .= "         {$sSigla}_regist as regist,      ";
      $sCampos .= "         rh02_seqpes      as pessoalmov,  ";
      $sCampos .= "         {$sSigla}_pd     as pd,          ";
      $sCampos .= "         {$sSigla}_quant  as quant,       ";
      $sCampos .= "         {$sSigla}_valor  as valor,       ";
      $sCampos .= "         {$sSigla}_anousu as anousu,      ";
      $sCampos .= "         {$sSigla}_mesusu as mesusu,      ";     
      $sCampos .= "         {$sSigla}_semest as semestre,    ";
      $sCampos .= "         '{$sSigla}'      as sigla,       ";
      $sCampos .= "         rh02_tbprev      as previdencia, ";
      $sCampos .= "         r70_concarpeculiar as caract     ";
      

      $sWhereGerador  = "     rh23_codele is not null        ";
      $sWhereGerador .= " and {$sSigla}_rubric = 'R992'      ";
      $sWhereGerador .= " and rh02_tbprev in ({$sListaPrev}) ";
      
      
      if ( trim($sSqlGerador) != '' ) {
        $sSqlGerador .= ' union all ';
      }
      
      $sSqlGerador   .= $clgeradorsql->gerador_sql( $sSigla,
                                                    $iAnoUsu,
                                                    $iMesUsu,
                                                    "",
                                                    "",
                                                    $sCampos,
                                                    "",
                                                    $sWhereGerador,
                                                    $iInstit);
    }
    
    
    $rsGerador = db_query($sSqlGerador);                                             
      
    if ( $rsGerador ) {
        
      $iLinhasGerador = pg_num_rows($rsGerador);
      
      if ( $iLinhasGerador > 0 ) {
          
        for ( $iInd=0; $iInd < $iLinhasGerador; $iInd++ ) {
            
          $oGerador = db_utils::fieldsMemory($rsGerador,$iInd);
          $iCaract  = $oGerador->caract;
          
          try {
            
            $oEstrututal = $this->getEstrututal(db_getsession('DB_anousu'),$oGerador->lotacao,$oGerador->vinculo,$oGerador->elemento);
            
            $iOrgao     = $oEstrututal->iOrgao; 
            $iUnidade   = $oEstrututal->iUnidade;
            $iProjAtiv  = $oEstrututal->iProjAtiv;
            $iFuncao    = $oEstrututal->iFuncao;
            $iSubFuncao = $oEstrututal->iSubFuncao;
            $iPrograma  = $oEstrututal->iPrograma;
            $iRecurso   = $oEstrututal->iRecurso;
            $iElemento  = $oEstrututal->iElemento;
            $iDotacao   = $oEstrututal->iDotacao;
            
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro},\n".$eException->getMessage());
          }           
          
          $sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento}             ";
          $sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}              ";
          $sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}                ";
          $sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv}             ";
        	if(!empty($iFuncao)) {
					  $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
					}
				  if(!empty($iSubFuncao)) {
				  	$sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
				  }
				  if(!empty($iPrograma)) {
				  	$sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
					}          
          $sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}              ";
          $sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}               ";
          $sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}               ";
          $sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$oGerador->sigla}'     ";
          $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'              ";
          $sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 2                        ";
          $sWhereEmpenhoFolha .= " and rh72_tabprev        = {$oGerador->previdencia} ";
          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = '{$oGerador->semestre}'  ";
          
          if ( trim($iDotacao) !== '' ){
            $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
          } else {
            $sWhereEmpenhoFolha .= " and rh72_coddot = 0        ";
          }
          
          $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha);
           
          if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
                          
            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
            $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
          
          } else {
            
            $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
            $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
            $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
            $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
            $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
            $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;              
            $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;              
            $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;            
            $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
            $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
            $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
            $oDaorhEmpenhoFolha->rh72_siglaarq       = $oGerador->sigla;
            $oDaorhEmpenhoFolha->rh72_tipoempenho    = 2;
            $oDaorhEmpenhoFolha->rh72_tabprev        = $oGerador->previdencia;
            $oDaorhEmpenhoFolha->rh72_seqcompl       = $oGerador->semestre;
            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
            
            $oDaorhEmpenhoFolha->incluir(null);
            
            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
               throw new Exception($oDaorhEmpenhoFolha->erro_msg);
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          
          try {
            $aExcecoes = $this->getExcessoesEmpenhoFolha($oGerador->rubric,$iAnoUsu,$iInstit);
          } catch (Exception $eException){
            throw new Exception($eException->getMessage());
          }

          if ( !empty($aExcecoes) ) {

            $iOrgao     = $aExcecoes[0]->rh74_orgao;
            $iUnidade   = $aExcecoes[0]->rh74_unidade;
            $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
            $iFuncao    = $aExcecoes[0]->rh74_funcao;
            $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
            $iPrograma  = $aExcecoes[0]->rh74_programa;
            $iRecurso   = $aExcecoes[0]->rh74_recurso;
            $iCaract    = "{$aExcecoes[0]->rh74_concarpeculiar}";
            
            $iDotacao  = $this->getDotacaoByFiltro($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElement, $iAnousu, $iFuncao, $iSubFuncao, $iPrograma);
                  
            $sWhereEmpenhoFolha  = "     rh72_codele         = {$iElemento}             ";
            $sWhereEmpenhoFolha .= " and rh72_unidade        = {$iUnidade}              ";
            $sWhereEmpenhoFolha .= " and rh72_orgao          = {$iOrgao}                ";
            $sWhereEmpenhoFolha .= " and rh72_projativ       = {$iProjAtiv}             ";
          	if(!empty($iFuncao)) {
					    $sWhereEmpenhoFolha .= " and rh72_funcao       = {$iFuncao}   ";
					  }
				    if(!empty($iSubFuncao)) {
				    	$sWhereEmpenhoFolha .= " and rh72_subfuncao    = {$iSubFuncao}";
				    }
				    if(!empty($iPrograma)) {
				    	$sWhereEmpenhoFolha .= " and rh72_programa     = {$iPrograma} ";					  
					  }            
            $sWhereEmpenhoFolha .= " and rh72_recurso        = {$iRecurso}              ";
            $sWhereEmpenhoFolha .= " and rh72_anousu         = {$iAnoUsu}               ";
            $sWhereEmpenhoFolha .= " and rh72_mesusu         = {$iMesUsu}               ";
            $sWhereEmpenhoFolha .= " and rh72_siglaarq       = '{$oGerador->sigla}'     ";
            $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'             ";            
            $sWhereEmpenhoFolha .= " and rh72_tipoempenho    = 2                        ";
            $sWhereEmpenhoFolha .= " and rh72_tabprev        = {$oGerador->previdencia} ";
            $sWhereEmpenhoFolha .= " and rh72_seqcompl       = '{$oGerador->semestre}'  ";
                  
            if ( trim($iDotacao) !== '' ){
              $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
            } else {
              $sWhereEmpenhoFolha .= " and rh72_coddot = 0       ";
            }                 
                  
            $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
            $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha); 
                  
            if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
                   
              $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
              $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
                    
            } else {
                    
              $oDaorhEmpenhoFolha->rh72_coddot         = $iDotacao;
              $oDaorhEmpenhoFolha->rh72_codele         = $iElemento;
              $oDaorhEmpenhoFolha->rh72_unidade        = $iUnidade;
              $oDaorhEmpenhoFolha->rh72_orgao          = $iOrgao;
              $oDaorhEmpenhoFolha->rh72_projativ       = $iProjAtiv;
              $oDaorhEmpenhoFolha->rh72_funcao         = $iFuncao;              
              $oDaorhEmpenhoFolha->rh72_subfuncao      = $iSubFuncao;              
              $oDaorhEmpenhoFolha->rh72_programa       = $iPrograma;              
              $oDaorhEmpenhoFolha->rh72_recurso        = $iRecurso;
              $oDaorhEmpenhoFolha->rh72_anousu         = $iAnoUsu;
              $oDaorhEmpenhoFolha->rh72_mesusu         = $iMesUsu;
              $oDaorhEmpenhoFolha->rh72_siglaarq       = $oGerador->sigla;
              $oDaorhEmpenhoFolha->rh72_tipoempenho    = 2;
              $oDaorhEmpenhoFolha->rh72_tabprev        = $oGerador->previdencia;
              $oDaorhEmpenhoFolha->rh72_seqcompl       = $oGerador->semestre;
              $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";              
                    
              $oDaorhEmpenhoFolha->incluir(null);
                    
              if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
                throw new Exception($oDaorhEmpenhoFolha->erro_msg);
              }
              $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            }
          }

          $sSqlPercPatronal  = " select distinct r33_ppatro     ";
          $sSqlPercPatronal .= "   from inssirf                 ";
          $sSqlPercPatronal .= "  where r33_anousu = {$iAnoUsu} ";
          $sSqlPercPatronal .= "    and r33_mesusu = {$iMesUsu} ";
          $sSqlPercPatronal .= "    and r33_codtab = ".($oGerador->previdencia+2);
          
          $rsPercPatronal    = db_query($sSqlPercPatronal);
          $oPercPatronal     = db_utils::fieldsMemory($rsPercPatronal,0);
          $nPercPatronal     = $oPercPatronal->r33_ppatro/100;
          
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oGerador->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oGerador->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar(($oGerador->valor * $nPercPatronal),'p');
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = 1;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 1;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
                        
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
          $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new Exception($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
          }          
          
        } 
        
      } else {
        throw new Exception("{$sMsgErro}, nenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
      }
    } else {
      throw new Exception("{$sMsgErro},Erro na consulta!");
    }
    
  }


  /**
   * Retorna os dados para empenhos da folha referente a Previ�ncia ( rubricas = R992 )
   *
   * @param string  $sTipo       Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu     Exerc�cio da Folha
   * @param integer $iMesUsu     M�s da Folha
   * @param string  $sListaPrev  Lista de Previ�ncia 
   * @param integer $iInstit     Institui��o
   * @return array
   */  
  public function getRubricasEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
  	
    $sMsgErro = 'Consulta de empenhos da previd�ncia abortada';
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, Tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($sListaPrev) == '' ) {
      throw new Exception("{$sMsgErro}, previd�ncia n�o informada!");
    }        
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    try {
      $aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( Exception $eException ) {
      throw new Exception($eException->getMessage());
    }
    
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolha  = "     rh73_rubric      = 'R992'       ";
    $sWhereEmpenhosFolha .= " and rh72_anousu      = {$iAnoUsu}   ";
    $sWhereEmpenhosFolha .= " and rh72_mesusu      = {$iMesUsu}   ";
    $sWhereEmpenhosFolha .= " and rh73_instit      = {$iInstit}   ";
    $sWhereEmpenhosFolha .= " and rh72_tipoempenho = 2            ";
    $sWhereEmpenhosFolha .= " and rh72_tabprev in ({$sListaPrev}) ";
    $sWhereEmpenhosFolha .= " and rh72_siglaarq in ('".implode("','",$aSiglas)."')";
    
    $sSqlEmpenhosFolha    = $oDaorhEmpenhoFolhaRubrica->sql_query_dados(null,
			                                                                  "*",
			                                                                  null,
			                                                                  $sWhereEmpenhosFolha);

    $rsEmpenhosFolha      = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolha);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolha = db_utils::getColectionByRecord($rsEmpenhosFolha);
    } else {
      $aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha referente a Previ�ncia ( rubricas = R992 )
   *
   * @param string  $sTipo       Tipo de Folha ( m = Mensal ou d = 13� )
   * @param integer $iAnoUsu     Exerc�cio da Folha
   * @param integer $iMesUsu     M�s da Folha
   * @param string  $sListaPrev  Lista de Previ�ncia 
   * @param integer $iInstit     Institui��o
   * @return array
   */    
  public function excluiDadosEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
    
    $sMsgErro = 'Exclus�o de empenhos da previd�ncia abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }
    
    if ( trim($sTipo) == '' ) {
      throw new Exception("{$sMsgErro}, tipo de folha n�o informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($sListaPrev) == '' ) {
      throw new Exception("{$sMsgErro}, previd�ncia n�o informada!");
    }            
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    $oDaorhEmpenhoFolha        = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    try {
      $aDadosEmpenhos = $this->getRubricasEmpenhosPrev($sTipo,$iAnoUsu,$iMesUsu,$sListaPrev,$iInstit);
    } catch (Exception $eException){
      throw new Exception($eException->getMessage());
    }

    if ( count($aDadosEmpenhos) > 0  ) {
       
      $aListaEmpenhos = array();
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $aListaEmpenhos[] = $oEmpenhoFolha->rh72_sequencial;
        if ( trim($oEmpenhoFolha->rh76_sequencial) != '' ) {
          throw new Exception("{$sMsgErro}, \nRegistros j� empenhados!");
        }
      }
      
      $aListaEmpenhos = array_unique($aListaEmpenhos);
      $sListaEmpenhos = implode(',',$aListaEmpenhos);
      
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new Exception("{$sMsgErro},\n".pg_last_error());
      }      
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
        }                       
      }
      
      $sWhereEmpenhos  = "     rh72_sequencial in ({$sListaEmpenhos}) ";
      $sWhereEmpenhos .= " and rh73_sequencial is null                ";
      
      $sSqlEmpenhos    = $oDaorhEmpenhoFolha->sql_query_rubricas(null,"rh72_sequencial",null,$sWhereEmpenhos);
      
      $rsEmpenhosFolha = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhos);
      $iLinhasEmpenhos = $oDaorhEmpenhoFolha->numrows;
      
      
      if ( $iLinhasEmpenhos > 0 ) {
        for ( $iInd=0; $iInd < $iLinhasEmpenhos; $iInd++ ) {
          $oEmpenhoFolha = db_utils::fieldsMemory($rsEmpenhosFolha,$iInd);
          $oDaorhEmpenhoFolha->excluir($oEmpenhoFolha->rh72_sequencial);
          if ( $oDaorhEmpenhoFolha->erro_status == "0") {
            throw new Exception("{$sMsgErro},\n{$oDaorhEmpenhoFolha->erro_msg}");
          }
        }
      }
    }    
    
  }  

  
  /**
   * Verifica se os dados gerados j� est�o liberados
   *
   * @param string  $sSigla    Tipo de Folha
   * @param string  $sTipoEmp  Tipo de Empenho ( Sal�rio, Previd�ncia ou FGTS )  
   * @param integer $iAnoUsu   Exerc�cio da Folha 
   * @param integer $iMesUsu   M�s da Folha
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   * @return boolean
   */
  public function isLiberada($sSigla='',$sTipoEmp='',$iAnoUsu='',$iMesUsu='',$aListaRescisoes = null) {
  	
  	$sMsgErro = " Consulta de libera��o abortada";
  	
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($sTipoEmp) == '' ) {
      throw new Exception("{$sMsgErro}, tipo n�o informado!");
    }  
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    $sListaRescisoes = implode(",", $aListaRescisoes); 	
  	
    /*
     * Caso exista resgistros na tabela rhempenhofolhaconfirma ent�o est� liberado 
     */
  	$oDaorhEmpenhoFolhaConfirma = db_utils::getDao('rhempenhofolhaconfirma');

		$sWhereLiberacao  = "     rh83_anousu       = {$iAnoUsu}   ";
		$sWhereLiberacao .= " and rh83_mesusu       = {$iMesUsu}   ";
		$sWhereLiberacao .= " and rh83_siglaarq     = '{$sSigla}'  ";
		$sWhereLiberacao .= " and rh83_complementar in({$sListaRescisoes})";
		$sWhereLiberacao .= " and rh83_tipoempenho  = {$sTipoEmp}  ";
		$sWhereLiberacao .= " and rh83_instit       = ".db_getsession("DB_instit");
		
		$sSqlLiberacao    = $oDaorhEmpenhoFolhaConfirma->sql_query_file(null,"*",null,$sWhereLiberacao);
  	$rsLiberacao      = $oDaorhEmpenhoFolhaConfirma->sql_record($sSqlLiberacao);
  	
  	if ( $oDaorhEmpenhoFolhaConfirma->numrows > 0 ) {
  		return true;
  	} else {
  		return false;
  	}
  	
  	
  }
	
  
  /**
   * Consulta a dota��o apartir do estrutural passado por par�metro
   *
   * @param integer $iOrgao    Org�o
   * @param integer $iUnidade  Unidade
   * @param integer $iProjAtiv Projeto Atividade
   * @param integer $iRecurso  Recurso
   * @param integer $iElemento Elemento
   * @param integer $iAnoUsu   Exerc�cio
   * @return integer C�digo da Dota��o
   */
  function getDotacaoByFiltro ($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElemento, $iAnoUsu, $iFuncao = null, $iSubFuncao = null, $iPrograma = null) {
    
    
    $oDaoOrcParametro = db_utils::getDao('orcparametro');
    $oDaoOrcElemento  = db_utils::getDao('orcelemento');
    $sSqlParametro    = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
    $rsParametro      = $oDaoOrcParametro->sql_record($sSqlParametro);
       
    if ( $oDaoOrcParametro->numrows > 0 ) {
      $oParametro = db_utils::fieldsMemory($rsParametro,0);
    } else { 
      throw new Exception("Configure os par�metros do or�amento para o ano {$iAnoUsu}!");
    }      
    if ( $oParametro->o50_subelem == "f" ) {
                
      $sCamposElemento = "substr(o56_elemento,1,7)||'000000' as elemento";
      $sWhereElemento  = "     o56_codele = {$iElemento} "; 
      $sWhereElemento .= " and o56_anousu = {$iAnoUsu}   ";
               
      $sSqlElemento = $oDaoOrcElemento->sql_query_file(null,null,$sCamposElemento,null,$sWhereElemento);
      $rsElemento   = $oDaoOrcElemento->sql_record($sSqlElemento);
               
      if ( $oDaoOrcElemento->numrows > 0 ) {
  
        $oElemento   = db_utils::fieldsMemory($rsElemento,0);
        $sWhereParam = " and o56_elemento='{$oElemento->elemento}'";
        
      }
               
    } else {
      $sWhereParam = " and o58_codele = {$iElemento}";
    }
    
    $sSql  = "select distinct o58_coddot";
    $sSql .= "  from orcdotacao  "; 
    $sSql .= "       inner join orcelemento on o56_codele = o58_codele";
    $sSql .= "                             and o56_anousu = o58_anousu";
    $sSql .= " where o58_orgao    = {$iOrgao}   "; 
    $sSql .= "   and o58_unidade  = {$iUnidade} "; 
    $sSql .= "   and o58_projativ = {$iProjAtiv} ";
    
    if (!empty($iFuncao)) {
      $sSql .= " and o58_funcao = {$iFuncao}";
    } 
    if (!empty($iSubFuncao)) {
      $sSql .= " and o58_subfuncao = {$iSubFuncao}";
    }
    if (!empty($iPrograma)) {
      $sSql .= " and o58_programa = {$iPrograma}";
    }
    
    $sSql .= "   {$sWhereParam}"; 
    $sSql .= "   and o58_codigo      = {$iRecurso}";
    $sSql .= "   and o58_anousu      = {$iAnoUsu}";
    $sSql .= "   and o58_instit      = ".db_getsession("DB_instit");
    $rsDotacoes = db_query($sSql);
    $iCodDot = null;
    if (pg_num_rows($rsDotacoes) > 0) {
      $iCodDot = db_utils::fieldsMemory($rsDotacoes, 0)->o58_coddot;
    }
    return $iCodDot;
  }
  
  
  /**
   * Gera planilha apartir dos dados para empenho 
   * @param string  $sSigla    Tipo de Folha ( Ex: Sal�rio, F�rias, Complementar, etc. ) 
   * @param integer $iAnoUsu   Exerc�cio da folha
   * @param integer $iMesUsu   M�s da Folha
   * @param integer $iInstit   institui��o
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )  
   * @return array Lista de Planilhas Geradas
   */
  public function geraPlanilhaGeral($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null) {
  	
    $sMsgErro = 'Gera��o da planilha dos empenhos abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);   	
    
    require_once("classes/db_rhslipfolha_classe.php");
    $clrhSlipFolha = new cl_rhslipfolha();
    
    require_once("model/slipFolha.model.php");
    $oSlipFolha = new slipFolha();
    
    $aPlanilha    = array();
    
	  $sWhereSlip   = "     rh79_siglaarq    = '{$sSigla}'             "; 
	  $sWhereSlip  .= " and rh79_anousu      = {$iAnoUsu}              ";
	  $sWhereSlip  .= " and rh79_mesusu      = {$iMesUsu}              ";
	  $sWhereSlip  .= " and rh73_instit      = ".db_getsession('DB_instit');
	  $sWhereSlip  .= " and rh73_tiporubrica = 3                       ";
	  $sWhereSlip  .= " and rh73_seqpes      in ({$sListaRescisoes})   ";
	  $sWhereSlip  .= " and rh82_sequencial is not null                ";
	  
	  $rsSlips        = $clrhSlipFolha->sql_record($clrhSlipFolha->sql_query_slip(null,"k17_codigo",null,$sWhereSlip));
	  $aObjListaSlips = db_utils::getColectionByRecord($rsSlips);
	  
	  if ( $clrhSlipFolha->numrows > 0 ) {
	  	
	  	foreach ($aObjListaSlips as $oSlip ) {
	  		$aListaSlip[] = $oSlip->k17_codigo;
	  	}
	  	
	  	try {
	  	  $iPlanilhaSlip = $oSlipFolha->geraPlanilhaSlip(implode(",",$aListaSlip));
	  	} catch (Exception $eException) {
	  		$sMsgErro = $eException->getMessage();
	  		$lErro    = true;
	  	}
	  	
	    if ( trim($iPlanilhaSlip) != '' ) {
        $aPlanilha[] = $iPlanilhaSlip;
      }
      
	  }
	  
	  try {
	  	$iPlanilhaDevolucao = $this->geraPlanilhaDevolucao($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes);
	  } catch (Exception $eException) {
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");	  	
	  }
	  
	  if ( trim($iPlanilhaDevolucao) != '' ) {
	    $aPlanilha[] = $iPlanilhaDevolucao;
	  }

	  return $aPlanilha;

  }
  
  
  /**
   * Gera planilha de devolu��o apartir dos dados para empenho 
   * @param string  $sSigla     Tipo de Folha  
   * @param integer $iAnoUsu    Exerc�cio da folha
   * @param integer $iMesUsu    M�s da Folha
   * @param integer $iInstit    institui��o
   * @param string  $sSemestre  Semestre ( Caso seja folha complementar )  
   * @return integer C�digo da Planilhas Geradas
   */  
  public function geraPlanilhaDevolucao($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$aListaRescisoes=null ){
    
    $sMsgErro     = 'Gera��o das Planilhas de Devolu��o abortada';
    $iCodPlanilha = '';
    
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transa��o encontrada!");
    }     

    if ( trim($sSigla) == '' ) {
      throw new Exception("{$sMsgErro}, sigla n�o informada!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }  
    $sListaRescisoes = implode(",", $aListaRescisoes);   
    
    $oDaoPlaCaixa     = db_utils::getDao('placaixa');
    $oDaoPlaCaixaRec  = db_utils::getDao('placaixarec');
    
    try {
      $sSqlRubricasDevolucao = $this->getRubricasDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$aListaRescisoes,null,true);
    } catch (Exception $eException){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
    }    
    
    $sSqlRubricas  = " select rh73_rubric          as rubric,                                 ";
    $sSqlRubricas .= "        rh69_recurso         as recurso,                                ";
    $sSqlRubricas .= "        rh78_retencaotiporec as retencao,                               ";
    $sSqlRubricas .= "        e21_receita          as receita,                                ";
    $sSqlRubricas .= "        rh41_conta           as contacredito,                           ";
    $sSqlRubricas .= "        case                                                            ";
    $sSqlRubricas .= "          when e48_cgm is null then ( select numcgm                     ";
    $sSqlRubricas .= "                                        from db_config                  "; 
    $sSqlRubricas .= "                                       where codigo = ".db_getsession('DB_instit').")"; 
    $sSqlRubricas .= "          else e48_cgm                                                  ";
    $sSqlRubricas .= "        end as numcgm,                                                  ";
    $sSqlRubricas .= "        sum(rh73_valor)      as valor                                   ";
    $sSqlRubricas .= "   from ( {$sSqlRubricasDevolucao} ) as x                               ";
    $sSqlRubricas .= " group by rubric,                                                       ";
    $sSqlRubricas .= "          retencao,                                                     ";
    $sSqlRubricas .= "          receita,                                                      ";
    $sSqlRubricas .= "          contacredito,                                                 ";
    $sSqlRubricas .= "          numcgm,                                                       ";
    $sSqlRubricas .= "          recurso                                                       ";
    $sSqlRubricas .= " order by rubric,                                                       ";
    $sSqlRubricas .= "          recurso                                                       ";    

    $rsDevolucao      = db_query($sSqlRubricas);
    $iLinhasDevolucao = pg_num_rows($rsDevolucao);
    
    if ( $iLinhasDevolucao > 0) {
    	 
      $oDaoPlaCaixa->k80_data   = date('Y-m-d',db_getsession("DB_datausu"));  
      $oDaoPlaCaixa->k80_instit = $iInstit;
      $oDaoPlaCaixa->incluir(null);
      
      if ( $oDaoPlaCaixa->erro_status == '0' ) {
        throw new Exception($sMsgErro."\n".$oDaoPlaCaixa->erro_msg);
      }       
      
      $iCodPlanilha = $oDaoPlaCaixa->k80_codpla;
    	
      
      for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
        
      	$oDevolucao = db_utils::fieldsMemory($rsDevolucao,$iInd);
	    
        /**
         * Buscamos se a conta credito possui uma conta extra-or�amentaria vinculada
         * caso possua, devemos usar essa conta como conta pagadora
         */
        $oDaoSaltesExtra  = db_utils::getDao("saltesextra");
        $sSqlContaextra   = $oDaoSaltesExtra->sql_query_extra(null,
	                                                            "k109_contaextra",
	                                                             null,
	                                                            "k109_saltes = {$oDevolucao->contacredito}");
  
        $rsContaExtra = $oDaoSaltesExtra->sql_record($sSqlContaextra);
        
        if ($oDaoSaltesExtra->numrows > 0) {
          $oDevolucao->contacredito = db_utils::fieldsmemory($rsContaExtra, 0)->k109_contaextra; 
        }
        
        $oDaoPlaCaixaRec->k81_codpla     = $iCodPlanilha;
        $oDaoPlaCaixaRec->k81_conta      = $oDevolucao->contacredito; 
        $oDaoPlaCaixaRec->k81_receita    = $oDevolucao->receita;
        $oDaoPlaCaixaRec->k81_valor      = $oDevolucao->valor;
        $oDaoPlaCaixaRec->k81_codigo     = $oDevolucao->recurso;
        $oDaoPlaCaixaRec->k81_numcgm     = $oDevolucao->numcgm;
        $oDaoPlaCaixaRec->k81_datareceb  = date('Y-m-d',db_getsession("DB_datausu"));
        $oDaoPlaCaixaRec->k81_origem     = 1;
        $oDaoPlaCaixaRec->k81_obs        = '';
        $oDaoPlaCaixaRec->k81_concarpeculiar = "000";
        $oDaoPlaCaixaRec->incluir(null); 
    
        if ( $oDaoPlaCaixaRec->erro_status == '0' ) {
          throw new Exception($sMsgErro."\n".$oDaoPlaCaixaRec->erro_msg);
        }
               
	    }
    }

    return $iCodPlanilha; 
    
  }   
  
  /**
   * retorna todas as rescisoes que nao foram empenhadas
   *
   * @param integer $iMesUsu mes base
   * @param integer $iAnoUsu ano base
   * @return array
   */
  public function getRescisoesNaoEmpenhadas($iMesUsu, $iAnoUsu) {
  	
		$iInstit = db_getsession('DB_instit');

    $sSqlrescisoes  = "SELECT rh01_regist as matricula, ";
    $sSqlrescisoes .= "       rh02_seqpes as seqpes,";
    $sSqlrescisoes .= "       rh05_recis as datarescisao,";
    $sSqlrescisoes .= "       z01_nome as nome ";
    $sSqlrescisoes .= "  From rhpesrescisao ";
    $sSqlrescisoes .= "       inner join rhpessoalmov on rh05_seqpes = rh02_seqpes ";
    $sSqlrescisoes .= "       inner join rhpessoal    on rh01_regist = rh02_regist ";
    $sSqlrescisoes .= "       inner join cgm on z01_numcgm = rh01_numcgm ";
    $sSqlrescisoes .= " where rh02_mesusu = {$iMesUsu} ";
    $sSqlrescisoes .= "   and rh02_anousu = {$iAnoUsu} ";
    $sSqlrescisoes .= "   and rh02_instit = {$iInstit} ";
    $sSqlrescisoes .= "   and rh05_empenhado is false  ";
    $rsRescisoes    = db_query($sSqlrescisoes);
    $aRescisoes     = array();
    if (pg_num_rows($rsRescisoes) > 0) {
      $aRescisoes = db_utils::getColectionByRecord($rsRescisoes, false, false, true);
    }
    return $aRescisoes;
  }

 /**
  * Retorna as rescisoes Empenhadas
  *
  * @param unknown_type $iMesUsu
  * @param unknown_type $iAnoUsu
  * @return unknown
  */ 
 public function getRescisoesEmpenhadas($iMesUsu, $iAnoUsu) {

    $sSqlrescisoes  = "SELECT distinct rh01_regist as matricula, ";
    $sSqlrescisoes .= "       rh02_seqpes as seqpes,";
    $sSqlrescisoes .= "       rh05_recis as datarescisao,";
    $sSqlrescisoes .= "       z01_nome as nome ";
    $sSqlrescisoes .= "  From rhpesrescisao ";
    $sSqlrescisoes .= "       inner join rhpessoalmov on rh05_seqpes = rh02_seqpes ";
    $sSqlrescisoes .= "       inner join rhpessoal    on rh01_regist = rh02_regist ";
    $sSqlrescisoes .= "       inner join cgm on z01_numcgm = rh01_numcgm ";
    $sSqlrescisoes .= "       inner join rhempenhofolharubrica      on rh73_seqpes         = rh02_seqpes";
    $sSqlrescisoes .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial";
    $sSqlrescisoes .= "       inner join rhempenhofolha on rh72_sequencial = rh81_rhempenhofolha ";
    $sSqlrescisoes .= "       inner join rhempenhofolhaempenho on rh76_rhempenhofolha = rh72_sequencial  ";
    $sSqlrescisoes .= " where rh02_mesusu = {$iMesUsu} ";
    $sSqlrescisoes .= "   and rh02_anousu = {$iAnoUsu} ";
    $sSqlrescisoes .= "   and rh05_empenhado is true  ";
    $rsRescisoes    = db_query($sSqlrescisoes);
    $aRescisoes     = array();
    if (pg_num_rows($rsRescisoes) > 0) {
      $aRescisoes = db_utils::getColectionByRecord($rsRescisoes, false, false, true);
    }
    return $aRescisoes;
  }
  
 /* Retorna as rescisoes que possuem slips a serem gerados no m�s
  *
  * @param unknown_type $iMesUsu
  * @param unknown_type $iAnoUsu
  * @return unknown
  */ 
 public function getRescisoesSlips($iMesUsu, $iAnoUsu, $lEmitidos = false) {

    $sSqlrescisoes  = "SELECT distinct rh01_regist as matricula, ";
    $sSqlrescisoes .= "       rh02_seqpes as seqpes,";
    $sSqlrescisoes .= "       rh05_recis as datarescisao,";
    $sSqlrescisoes .= "       z01_nome as nome ";
    $sSqlrescisoes .= "  From rhpesrescisao ";
    $sSqlrescisoes .= "       inner join rhpessoalmov on rh05_seqpes = rh02_seqpes ";
    $sSqlrescisoes .= "       inner join rhpessoal    on rh01_regist = rh02_regist ";
    $sSqlrescisoes .= "       inner join cgm on z01_numcgm = rh01_numcgm ";
    $sSqlrescisoes .= "       inner join rhempenhofolharubrica      on rh73_seqpes         = rh02_seqpes";
    $sSqlrescisoes .= "       left join rhslipfolharhemprubrica on rh80_rhempenhofolharubrica = rh73_sequencial";
    $sSqlrescisoes .= "       left join rhslipfolha on rh79_sequencial = rh80_rhslipfolha ";
    $sSqlrescisoes .= "       left  join rhslipfolhaslip on rh82_rhslipfolha = rh79_sequencial  ";
    $sSqlrescisoes .= " where rh79_mesusu = {$iMesUsu} ";
    $sSqlrescisoes .= "   and rh79_anousu = {$iAnoUsu} ";
    $sSqlrescisoes .= "   and rh79_siglaarq = 'r20'";
    if ($lEmitidos) {
      $sSqlrescisoes .= " and rh82_slip is not null";
    }
    $rsRescisoes    = db_query($sSqlrescisoes);
    $aRescisoes     = array();
    if (pg_num_rows($rsRescisoes) > 0) {
      $aRescisoes = db_utils::getColectionByRecord($rsRescisoes, false, false, true);
    }
    return $aRescisoes;
  }
  
 /* Retorna as rescisoes que possuem slips a serem gerados no m�s
  *
  * @param unknown_type $iMesUsu
  * @param unknown_type $iAnoUsu
  * @return unknown
  */ 
 public function getRescisoesPlanilhas($iMesUsu, $iAnoUsu) {

    $sSqlrescisoes  = "SELECT distinct rh01_regist as matricula, ";
    $sSqlrescisoes .= "       rh02_seqpes as seqpes,";
    $sSqlrescisoes .= "       rh05_recis as datarescisao,";
    $sSqlrescisoes .= "       z01_nome as nome ";
    $sSqlrescisoes .= "  From rhpesrescisao ";
    $sSqlrescisoes .= "       inner join rhpessoalmov on rh05_seqpes = rh02_seqpes ";
    $sSqlrescisoes .= "       inner join rhpessoal    on rh01_regist = rh02_regist ";
    $sSqlrescisoes .= "       inner join cgm on z01_numcgm = rh01_numcgm ";
    $sSqlrescisoes .= "       inner join rhempenhofolharubrica      on rh73_seqpes         = rh02_seqpes";
    $sSqlrescisoes .= "       inner join rhslipfolharhemprubrica on rh80_rhempenhofolharubrica = rh73_sequencial";
    $sSqlrescisoes .= "       inner join rhslipfolha on rh79_sequencial = rh80_rhslipfolha ";
    $sSqlrescisoes .= "       left  join rhslipfolhaslip on rh82_rhslipfolha = rh79_sequencial  ";
    $sSqlrescisoes .= " where rh79_mesusu = {$iMesUsu} ";
    $sSqlrescisoes .= "   and rh79_anousu = {$iAnoUsu} ";
    $sSqlrescisoes .= "   and rh79_siglaarq = 'r20'";
    $sSqlrescisoes .= "   and rh73_tiporubrica = 3";
    $rsRescisoes    = db_query($sSqlrescisoes);
    $aRescisoes     = array();
    if (pg_num_rows($rsRescisoes) > 0) {
      $aRescisoes = db_utils::getColectionByRecord($rsRescisoes, false, false, true);
    }
    return $aRescisoes;
  }
  
  
}

?>