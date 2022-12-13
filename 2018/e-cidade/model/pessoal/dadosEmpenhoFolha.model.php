<?php

/**
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

/**
 * Gera os dados necessários para a criação dos empenhos da folha 
 */
class dadosEmpenhoFolha {
  
  function __construct() {}

  /**
   * Gera os dados para empenho apartir da rubricas < R950 ( Salário ) 
   *
   * @param string  $sSigla    Tipo de Folha ( Ex: Salário, Férias, Complementar, etc. ) 
   * @param integer $iAnoUsu   Exercício da folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   */
  public function geraDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
  	
  	$sMsgErro = 'Geração de empenhos abortada';
  	
  	if ( !db_utils::inTransaction() ){
  		throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
  	}

  	require_once("libs/db_sql.php");
  	
  	if ( trim($sSigla) == '' ) {
  		throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
  	
    
    try {
	    $lLiberada = $this->isLiberada($sSigla,
	                                   1,
	                                   $iAnoUsu,
	                                   $iMesUsu,
	                                   $sSemestre); 
	  } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }    
    
	  if ( $lLiberada ) {
	  	throw new BusinessException("{$sMsgErro}\nempenho liberado!");
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
     *  Consulta todos registros inferiores a R950 apartir dos parâmetros informados 
     */
    $sCampos  = " distinct rh30_vinculo       as vinculo, ";
    $sCampos .= "          rh02_lota          as lotacao, ";
    $sCampos .= "          r70_concarpeculiar as caract,  ";
    $sCampos .= "          rh23_codele        as elemento ";

    
    /**
     * Modifica a estrutura da cláusula WHERE para aceitar a estrutura da suplementar. 
     */  
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && 
        dadosEmpenhoFolha::verificarPermissaoFolha($sSigla)) {
      
      $sWhereGerador      = "     rh23_codele is not null     ";
      $sWhereSuplementar  = " and rh143_tipoevento != 3       ";
      $sWhereSuplementar .= " and rh141_codigo = {$sSemestre} ";
      
    } else {
      
      $sWhereGerador     = "     rh23_codele is not null   ";
      $sWhereGerador    .= " and {$sSigla}_pd != 3         ";
      
      if ( $sSigla == 'r48' ) {
        $sWhereGerador .= " and r48_semest = {$sSemestre} ";
      }
      
      $sWhereSuplementar = "";      
    }
    
    /**
     * Query representa os dados da estrutura do empenho da folha.
     */
		$sSqlGerador    = $clgeradorsql->gerador_sql( $sSigla,
		  			                                     $iAnoUsu,
						                                     $iMesUsu,
						                                     "",
						                                     "",
						                                     $sCampos,
																                 "rh02_lota,rh23_codele",
						                                     $sWhereGerador,
						                                     $iInstit,
                                                 $sWhereSuplementar);
    

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
						
					} catch ( DBException $eException ){
            throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( BusinessException $eException ){
            throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( ParameterException $eException ){
            throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( FileException $eException ){
            throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	        }
					
					
					/**
					 *  Verifica se já existe registros cadastrados na rhempenhofolha 
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
          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = $sSemestre   ";
					
          if ( trim($iDotacao) !== '' ){
					  $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
					} else {
						$sWhereEmpenhoFolha .= " and rh72_coddot = 0        ";
					}
					 
					$sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"*",null,$sWhereEmpenhoFolha);
          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha);

          
          if ($oDaorhEmpenhoFolha->numrows > 0 ) {

           /**
            *  Caso exista então utiliza o sequencial para a inclusão dos registros filhos
            */
            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
          	$iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
          
          } else {

          	/**
          	 *  Caso não exista então é inserido um registro novo
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
            $oDaorhEmpenhoFolha->rh72_seqcompl       = $sSemestre;
            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
            $oDaorhEmpenhoFolha->incluir(null);
            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
              throw new DBException("{$oDaorhEmpenhoFolha->erro_msg}");
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          	
          $iCodEmpenhoFolhaGeral = $iCodEmpenhoFolha;
          
          
          /**
           *  Consulta todas rubrícas inferiores a R950 apartir dos parâmetros informados
           *  Obs: Esse SQL foi dívidido em duas partes para o melhor desempenho na consulta pois inicialmente 
           *  é feita uma série de valições para achar o estrutural que são em comum entre todas rubricas  
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
				
          /**
           * Modifica a estrutura da cláusula WHERE para aceitar a estrutura da suplementar. 
           */ 
          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && 
              dadosEmpenhoFolha::verificarPermissaoFolha($sSigla)) {
      
            $sWhereGeradorRubricas  = "     rh23_codele  = {$oGerador->elemento} ";
				    $sWhereGeradorRubricas .= " and rh30_vinculo = '{$oGerador->vinculo}'";
				    $sWhereGeradorRubricas .= " and rh02_lota    = {$oGerador->lotacao}  ";		  
            $sWhereGeradorRubricas .= " and r70_concarpeculiar = '{$oGerador->caract}' ";
            
            $sWhereSuplementar     .= " and rh143_tipoevento != 3       ";
            $sWhereSuplementar     .= " and rh141_codigo = {$sSemestre} ";
            
          } else {
            
            $sWhereGeradorRubricas  = "     rh23_codele  = {$oGerador->elemento} ";
				    $sWhereGeradorRubricas .= " and rh30_vinculo = '{$oGerador->vinculo}'";
				    $sWhereGeradorRubricas .= " and rh02_lota    = {$oGerador->lotacao}  ";
            $sWhereGeradorRubricas .= " and {$sSigla}_pd != 3                    ";				  
            $sWhereGeradorRubricas .= " and r70_concarpeculiar = '{$oGerador->caract}' ";
  
				    if ( $sSigla == 'r48' ) {
              $sWhereGeradorRubricas .= " and r48_semest = {$sSemestre}         ";
            }
            
            $sWhereSuplementar = "";
          }
          
          /**
           * Query representa os dados da rubrica.
           */
				  $sSqlGeradorRubricas    = $clgeradorsql->gerador_sql( $sSigla,
				  		                                                  $iAnoUsu,
					  	                                                  $iMesUsu,
							                                                  "",
							                                                  "",
							                                                  $sCamposRubricas,
							                                                  "{$sSigla}_regist",
							                                                  $sWhereGeradorRubricas,
							                                                  $iInstit,
                                                                $sWhereSuplementar);
                                                                

          
                                                                
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
				          $aExcecoes = $this->getExcessoesEmpenhoFolha($sSigla, $oRubrica->rubric,$iAnoUsu,$iInstit);
				        }  catch ( DBException $eException ){
                  throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	              } catch ( BusinessException $eException ){
                  throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	              } catch ( ParameterException $eException ){
                  throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	              } catch ( FileException $eException ){
                  throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	              } catch ( Exception $eException ){
                  throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	              }

				        if ( !empty($aExcecoes) ) {
    
				        	/**
				        	 * Caso exista exceção então será consultado se já existe algum registro cadastrado 
				        	 * na tabela rhempenhofolha para esse novo estrutural
				        	 */
				        	
	  	            if (!empty($aExcecoes[0]->rh74_orgao)) { 
                    $iOrgao     = $aExcecoes[0]->rh74_orgao;
                  }
                  if (!empty($aExcecoes[0]->rh74_unidade)) {
                    $iUnidade   = $aExcecoes[0]->rh74_unidade;  
                  }
                  if (!empty($aExcecoes[0]->rh74_projativ)) {
                    $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
                  }
                  if (!empty($aExcecoes[0]->rh74_funcao)) {
                    $iFuncao    = $aExcecoes[0]->rh74_funcao;
                  }
                  if (!empty($aExcecoes[0]->rh74_subfuncao)) {
                    $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
                  }
                  if (!empty($aExcecoes[0]->rh74_programa)) {
                    $iPrograma  = $aExcecoes[0]->rh74_programa;
                  }
                  if (!empty($aExcecoes[0]->rh74_recurso)) {
                    $iRecurso   = $aExcecoes[0]->rh74_recurso;
                  }
                  if (!empty($aExcecoes[0]->rh74_concarpeculiar)) {
                    $iCaract    = "{$aExcecoes[0]->rh74_concarpeculiar}";
                  }
                  if(!empty($aExcecoes[0]->rh74_codele)) {
                    $iElemento  = $aExcecoes[0]->rh74_codele;
                  }

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
				          $sWhereEmpenhoFolha .= " and rh72_seqcompl       = $sSemestre   ";
				          
				          if ( trim($iDotacao) !== '' ){
				            $sWhereEmpenhoFolha .= " and rh72_coddot = {$iDotacao}  ";
				          } else {
				            $sWhereEmpenhoFolha .= " and rh72_coddot = 0 ";
				          }	                
	                
				          $sSqlEmpenhoFolha   = $oDaorhEmpenhoFolha->sql_query_file(null,"rh72_sequencial",null,$sWhereEmpenhoFolha);
				          $rsEmpenhoFolha     = $oDaorhEmpenhoFolha->sql_record($sSqlEmpenhoFolha); 
				          
				          if ( $oDaorhEmpenhoFolha->numrows > 0 ) {
				            
				           /**
				            *  Caso exista então utiliza o sequencial para a inclusão do registro filho
				            */				          	
				            $oRhEmpenhoFolha  = db_utils::fieldsMemory($rsEmpenhoFolha,0);
				            $iCodEmpenhoFolha = $oRhEmpenhoFolha->rh72_sequencial;
				            
				          } else {
				            
			             /**
			              *  Caso não exista então é inserido um registro novo na rhempenhofolha
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
  	                $oDaorhEmpenhoFolha->rh72_seqcompl       = $sSemestre;				            
				            $oDaorhEmpenhoFolha->rh72_concarpeculiar = "{$iCaract}";
				                                
				            $oDaorhEmpenhoFolha->incluir(null);
				            
				            if ( $oDaorhEmpenhoFolha->erro_status == '0' ) {
				              throw new DBException($oDaorhEmpenhoFolha->erro_msg);
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
									throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
								}
                  
								/**
								 *  Insere registro na tabela de ligação entre rhempenhofolha e rhempenhofolharubrica
								 */
                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                								
	          	  if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
                  throw new DBException($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
                }                
								
	           	}
	           	
	          } else {
	           	throw new DBException("{$sMsgErro}\nnenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
	          }
    		  } else {
  		      throw new DBException("{$sMsgErro}\nErro na consulta de rubricas");
    	   	}
				}	
			} else {
				throw new DBException("{$sMsgErro}\nnenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
			}
		} else {
			throw new DBException("{$sMsgErro}\nErro na consulta!");
		}
    
		/*
		 * Gera pagamentos extra
		 */
    try {
      $this->geraPagamentoExtra($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);     
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }	
		
    /**
     * Gera retenções 
     */
		try {
      $this->geraRetencoesEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);			
		} catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }
    
    /**
		 * Gera devolução de empenho
		 */
    try {
      $this->geraDevolucoesEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);     
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }	
		
	}
	
  
	/**
	 * Verifica se exitem excessões cadastradas para a rubrica
	 *
	 * @param string  $sRubric Rubrica
	 * @param integer $iAnoUsu Exercício
	 * @param integer $iInstit Instituição
	 * @return array 
	 */
  public function getExcessoesEmpenhoFolha($sSigla='', $sRubric='',$iAnoUsu='',$iInstit=''){
  	
    if ( trim($sSigla) == '') {
      throw new ParameterException("Tipo de folha não informado!");
    }

  	if ( trim($sRubric) == '' ) {
  		throw new ParameterException('Rubrica não informada!');
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
    $iTipoFolha = 0;
    
    /**
     * O número do tipo da folha é inalterável, mesmo que
     * que o número seja diferente da tabela rhfolhatipo.
     */
    switch ($sSigla) {
      case 'r14': //Salário
        $iTipoFolha = 1;
      break;
      case 'r48': //Complementar
        $iTipoFolha = 2;
      break;
      case 'r35': // 13º Salário
        $iTipoFolha = 4;
      break;
      case 'r20': //Rescisao
        $iTipoFolha = 3;
      break;
      case 'r22': // Adiantamento
        $iTipoFolha = 5;
      break;
      case 'sup': //Suplementar
        $iTipoFolha = 6;
    }

    $sWhereExcecao .= " and (rh74_tipofolha = {$iTipoFolha} or rh74_tipofolha = 0)";
  	$sSqlExcecao    = $oDaorhEmpenhoFolhaExcecaoRubrica->sql_query_file(null,"*","rh74_tipofolha desc",$sWhereExcecao);
  	$rsExcecao      = $oDaorhEmpenhoFolhaExcecaoRubrica->sql_record($sSqlExcecao);
  	
  	if ( $oDaorhEmpenhoFolhaExcecaoRubrica->numrows > 0  ) {
  	  $aExcecoes = db_utils::getCollectionByRecord($rsExcecao);
  	} else {
  		$aExcecoes = array();
  	}
  	
  	return $aExcecoes;
  }

  
  /**
   * Retorna os registros referente ao dados para empenhos da folha ( rubrica < R950 )
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * @return array
   */
  public function getDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
  	
    $sMsgErro = 'Consulta de empenhos da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolha  = "     rh72_siglaarq = '{$sSigla}'    ";
    $sWhereEmpenhosFolha .= " and rh72_anousu   = {$iAnoUsu}     ";
    $sWhereEmpenhosFolha .= " and rh72_mesusu   = {$iMesUsu}     ";
    $sWhereEmpenhosFolha .= " and rh73_instit   = {$iInstit}     ";
    $sWhereEmpenhosFolha .= " and rh72_tipoempenho = 1           ";
    
    if ( $sSigla == 'r48' || $sSigla == 'sup') {
    	$sWhereEmpenhosFolha .= " and rh72_seqcompl = {$sSemestre} ";
    }
    
    $sSqlEmpenhosFolha = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                "distinct rhempenhofolha.*",
			                                                                null,
			                                                                $sWhereEmpenhosFolha);
    $rsEmpenhosFolha   = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolha);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
  	  $aEmpenhosFolha = db_utils::getCollectionByRecord($rsEmpenhosFolha);
    } else {
    	$aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }

  
  /**
   * Retorna as rubricas referente ao dados para empenhos da folha ( rubrica < R950 )
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * @return array 
   */
  public function getRubricasEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Consulta de empenhos da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolhaRubrica  = "     rh72_siglaarq    = '{$sSigla}' ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_anousu      = {$iAnoUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_mesusu      = {$iMesUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_instit      = {$iInstit}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh72_tipoempenho = 1           ";
    
    if ( $sSigla == 'r48' || $sSigla == 'sup') {
      $sWhereEmpenhosFolhaRubrica .= " and rh72_seqcompl = {$sSemestre} ";
    }
    
    $sCamposRubricas             = "rhempenhofolharubrica.*,";
    $sCamposRubricas            .= "rhempenhofolhaempenho.* ";
    
    $sSqlEmpenhosFolhaRubrica    = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                          $sCamposRubricas,
			                                                                          null,
			                                                                          $sWhereEmpenhosFolhaRubrica);

    $rsEmpenhosFolhaRubrica      = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolhaRubrica);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolhaRubrica = db_utils::getCollectionByRecord($rsEmpenhosFolhaRubrica);
    } else {
      $aEmpenhosFolhaRubrica = array();
    }
    
    return $aEmpenhosFolhaRubrica;
    
  }
    
  
 /**
  * Retorna as rubricas de slip referente ao dados para empenhos da folha
  *
  * @param string  $sSigla    Tipo de Folha
  * @param integer $iAnoUsu   Exercício da Folha
  * @param integer $iMesUsu   Mês da Folha
  * @param integer $iInstit   Instituição
  * @param string  $sSemestre Semestre ( Caso seja folha complementar )
  * @return array  
  */ 
  public function getRubricasSlipFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Consulta de slips da folha abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    $sWhereEmpenhosFolhaRubrica  = "     rh79_siglaarq    = '{$sSigla}' ";
    $sWhereEmpenhosFolhaRubrica .= " and rh79_anousu      = {$iAnoUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh79_mesusu      = {$iMesUsu}  ";
    $sWhereEmpenhosFolhaRubrica .= " and rh73_instit      = {$iInstit}  ";
    
    if ( $sSigla == 'r48' || $sSigla == 'sup') {
      $sWhereEmpenhosFolhaRubrica .= " and rh79_seqcompl = {$sSemestre} ";
    }
    
    $sSqlEmpenhosFolhaRubrica    = $oDaorhEmpenhoFolhaRubrica->sql_query_dados( null,
			                                                                          "*",
			                                                                          null,
			                                                                          $sWhereEmpenhosFolhaRubrica);
			                                                                          
    $rsEmpenhosFolhaRubrica      = $oDaorhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhosFolhaRubrica);
    
    if ( $oDaorhEmpenhoFolhaRubrica->numrows > 0 ) {
      $aEmpenhosFolhaRubrica = db_utils::getCollectionByRecord($rsEmpenhosFolhaRubrica);
    } else {
      $aEmpenhosFolhaRubrica = array();
    }
    
    return $aEmpenhosFolhaRubrica;
    
  }  
  
  
  /**
   * Retorna as rubricas de devolução referente ao dados para empenhos da folha 
   *
   * @param string  $sSigla        Tipo de Folha
   * @param integer $iAnoUsu       Exercício da Folha
   * @param integer $iMesUsu       Mês da Folha
   * @param integer $iInstit       Instituição
   * @param string  $sSemestre     Semestre ( Caso seja folha complementar )
   * @param integer $iTipoRubrica  Tipo de Rubrica
   * @param boolean $lRetornaSql   Parâmetro que define se o retorno será o SQL ou o recordset
   * @return string/array
   */
  public function getRubricasDevolucaoFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre='',$iTipoRubrica="",$lRetornaSql=false){
    
    $sMsgErro = 'Consulta das rubricas de devolução abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhDevolucaoFolha = db_utils::getDao('rhdevolucaofolha');
    
    $sWhereDevolucaoFolha  = "     rh69_siglaarq = '{$sSigla}' ";
    $sWhereDevolucaoFolha .= " and rh69_anousu   = {$iAnoUsu}  ";
    $sWhereDevolucaoFolha .= " and rh69_mesusu   = {$iMesUsu}  ";
    $sWhereDevolucaoFolha .= " and rh73_instit   = {$iInstit}  ";
    
    if ( $sSigla == 'r48' || $sSigla == 'sup' ) {
      $sWhereDevolucaoFolha .= " and rh69_seqcompl = {$sSemestre} ";
    }
    
    $sSqlDevolucaoFolha = $oDaorhDevolucaoFolha->sql_query_rubricas( null,
                                                                     "*",
                                                                     null,
                                                                     $sWhereDevolucaoFolha);

    $rsDevolucaoFolha   = $oDaorhDevolucaoFolha->sql_record($sSqlDevolucaoFolha);
    
    if ( $oDaorhDevolucaoFolha->numrows > 0 ) {
      $aDevolucaoFolha = db_utils::getCollectionByRecord($rsDevolucaoFolha);
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
   * Exclui os dados para empenhos da folha juntamente com os de slip e devolução
   * 
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   * 
   */
  public function excluiDadosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
  	
    $sMsgErro = 'Exclusão dados da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }  	
  	
    try {
	    $this->excluiDadosDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);   
	    $this->excluiDadosSlipFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);                                                                                                                
	    $this->excluiDadosPlanilhaFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
	    $this->excluiDadosEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }
    	    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function excluiDadosEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Exclusão de empenhos da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhEmpenhoFolha                = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica    = db_utils::getDao('rhempenhofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');

    /**
     *  Cria um array contendo os registros da rhempenhofolha
     */
    try {
    	$aListaEmpenhos = $this->getDadosEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }
    
    /**
     *  Cria um array contendo os registros da rhempenhofolharubrica
     */    
    try {
      $aListaRubricas = $this->getRubricasEmpenhosFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }   
    
    if ( count($aListaEmpenhos) > 0  ) {
    	 
	    $aListaCodEmpenho = array();
	    
	    foreach ( $aListaEmpenhos as $iInd => $oEmpenhoFolha ) {
	      $aListaCodEmpenho[] = $oEmpenhoFolha->rh72_sequencial;
	    }
	    
	    $aListaCodEmpenho = array_unique($aListaCodEmpenho);
	    $sListaEmpenhos   = implode(',',$aListaCodEmpenho);
	    
	    /**
	     *  Exclui inicialmente os registros de retenção
	     */
	    $sSqlExcluiRetencoes  = " delete from rhempenhofolharubricaretencao                                                "; 
	    $sSqlExcluiRetencoes .= "  where rh78_rhempenhofolharubrica in ( select rh81_rhempenhofolharubrica                 ";
	    $sSqlExcluiRetencoes .= "                                          from rhempenhofolharhemprubrica                 ";
	    $sSqlExcluiRetencoes .= "                                         where rh81_rhempenhofolha in ({$sListaEmpenhos}))";
	    $rsExcluiRetencoes    = db_query($sSqlExcluiRetencoes);

	    if ( !$rsExcluiRetencoes ) {
	    	throw new DBException("1. {$sMsgErro}\n\n".pg_last_error());
	    }
	    
	    /**
	     *  Exclui os registros da tabela de ligação entre rhempenhofolha e rhempenhofolharubrica 
	     */
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new DBException("2. {$sMsgErro}\n\n".pg_last_error());
      }      
      
      /**
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      if ( count($aListaRubricas) > 0 ) {
        foreach ( $aListaRubricas as $iIndRubrica => $oEmpenhoRubrica ){
        	
          if ( trim($oEmpenhoRubrica->rh76_sequencial) != '' ) {
            throw new BusinessException("3. {$sMsgErro}\n\nRegistros já empenhados!");
          }
          
          $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoRubrica->rh73_sequencial);
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
            throw new DBException("4. {$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
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
	     *  Caso exista então são deletados os registros
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
		    	$sSqlDadosReserva        = $oDaoReservaSaldoEmpenho->sql_query_file(null, "o120_sequencial, o120_orcreserva", null, $sWhere);
		    	$rsDadosEmpenho          = $oDaoReservaSaldoEmpenho->sql_record($sSqlDadosReserva);
		    	if ($oDaoReservaSaldoEmpenho->numrows > 0) {

		    		$oReserva = db_utils::fieldsMemory($rsDadosEmpenho, 0);
            $oDaoReservaSaldoEmpenho->excluir($oReserva->o120_sequencial);
            if ($oDaoReservaSaldoEmpenho->erro_status == "0") {
              throw new DBException("5. Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReservaSaldoEmpenho->erro_msg}");
            }
            
		    		$oDaoReserva->excluir($oReserva->o120_orcreserva);
		    		if ($oDaoReserva->erro_status == "0") {
		    			throw new DBException("5. Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReserva->erro_msg}");
		    		}
			      $oDaoReservaSaldoEmpenho->excluir(null, "o120_orcreserva = {$oReserva->o120_orcreserva}");
		      	if ($oDaoReservaSaldoEmpenho->erro_status == 0) {
		    			throw new DBException("6. Erro ao Excluir Reservas de saldo do Empenho.\n{$oDaoReservaSaldoEmpenho->erro_msg}");
		    		}
		    	}
		      $oDaorhEmpenhoFolha->excluir($oEmpenhoFolha->rh72_sequencial);
		      
		      if ( $oDaorhEmpenhoFolha->erro_status == "0") {
		        throw new DBException("7. {$sMsgErro}\n\n{$oDaorhEmpenhoFolha->erro_msg}");
		      }      
	    	}
	    }
    }
  }
  
  
  /**
   * Exclui os slips referentes aos dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function excluiDadosSlipFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Exclusão de slips da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    
    $oDaorhSlipFolha                   = db_utils::getDao('rhslipfolha');
    $oDaorhSlipFolhaSlip               = db_utils::getDao('rhslipfolhaslip');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhSlipFolhaRhEmpRubrica       = db_utils::getDao('rhslipfolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaPlanilha = db_utils::getDao('rhempenhofolharubricaplanilha');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    
    
    /**
     *  Cria um array contendo as rubricas de slip
     */
    try {
      $aDadosRubricas = $this->getRubricasSlipFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }   
    
    if ( count($aDadosRubricas) > 0  ) {
       
      $aListaSlip = array();
      
      foreach ( $aDadosRubricas as $iInd => $oSlipFolha ) {
        $aListaSlip[] = $oSlipFolha->rh79_sequencial;
      }
      
      $aListaSlip = array_unique($aListaSlip);
      $sListaSlip = implode(',',$aListaSlip);
      
      
      /**
       *  Exclui inicialmente as rubricas de retenção
       */
      $sWhere = " rh78_rhempenhofolharubrica in (select rh80_rhempenhofolharubrica          ";
      $sWhere .= "                                 from rhslipfolharhemprubrica             ";             
      $sWhere .= "                                where rh80_rhslipfolha in ({$sListaSlip}))";
      $oDaorhEmpenhoFolhaRubricaRetencao->excluir(null,$sWhere);
      if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == "0" ) {
        throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubricaRetencao->erro_msg}");
      }
      
      /**
       *  Exclui os registros da tabela de ligação entre  rhslipfolha e rhempenhofolharubrica
       */
      $oDaorhSlipFolhaRhEmpRubrica->excluir(null, "rh80_rhslipfolha in ({$sListaSlip})");
      if ($oDaorhSlipFolhaRhEmpRubrica->erro_status == "0") {
        throw new DBException("{$sMsgErro}\n\n{$oDaorhSlipFolhaRhEmpRubrica->erro_msg}");
      }      
      
      /*
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      foreach ( $aDadosRubricas as $iInd => $oSlipFolha ) {
        
         //excluimos da rhempenhofolharubricaplanilha
         $oDaorhEmpenhoFolhaRubricaPlanilha->excluir(null, "rh111_rhempenhofolharubrica = {$oSlipFolha->rh73_sequencial}");
         if ($oDaorhEmpenhoFolhaRubricaPlanilha->erro_status == "0") {        
           throw new DBException("{$sMsgErro}\n\n$oDaorhEmpenhoFolhaRubricaPlanilha->erro_msg}");
         }
        
        
        $oDaorhEmpenhoFolhaRubrica->excluir($oSlipFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
        }        
      }      	
      
      
      $oDaorhSlipFolhaSlip->excluir(null, " rh82_rhslipfolha in ({$sListaSlip})");
      if ($oDaorhSlipFolhaSlip->erro_status == "0") {
      	throw new DBException("{$sMsgErro}\n\n{$oDaorhSlipFolhaSlip->erro_msg}");
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
       * Caso exista então são deletados os registros
       */
      if ( $iLinhasSlip > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasSlip; $iInd++ ) {
          
          $oRHSlipFolha= db_utils::fieldsMemory($rsSlipFolha,$iInd);
          $oDaorhSlipFolha->excluir($oRHSlipFolha->rh79_sequencial);
          
          if ( $oDaorhSlipFolha->erro_status == "0") {
            throw new DBException("{$sMsgErro}\n\n{$oDaorhSlipFolha->erro_msg}");
          }      
        }
      }
    }
  }
  
  public function excluiDadosPlanilhaFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
      
    $sMsgErro = 'Exclusão de planilha da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRhEmpRubrica    = db_utils::getDao('rhempenhofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    $oDaorhEmpenhoFolhaRubricaPlanilha = db_utils::getDao('rhempenhofolharubricaplanilha');
    
    
    $oDaoRhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
      
    
    $sWhereRubricas  = " rh73_tiporubrica = 2 ";
    $sWhereRubricas .= " and ( rh72_siglaarq = '{$sSigla}' or rh79_siglaarq = '{$sSigla}' ) "; 
	  $sWhereRubricas .= " and ( ( rh72_anousu = {$iAnoUsu} and rh72_mesusu = {$iMesUsu} ) or ( rh79_anousu = {$iAnoUsu} and rh79_mesusu = {$iMesUsu} ) )  ";
	  $sWhereRubricas .= " and rh73_instit = {$iInstit}   ";
	  $sWhereRubricas .= " and ( rh72_seqcompl = {$sSemestre} or rh79_seqcompl = {$sSemestre} )";
	  $sWhereRubricas .= " and rh111_sequencial is not null        ";
	      
    $sSqlEmpenhos    = $oDaoRhEmpenhoFolhaRubrica->sql_query_rhempenhofolharubricas(null, "distinct rh78_sequencial, rh73_sequencial ", null, $sWhereRubricas);
    $rsEmpenhos      = $oDaoRhEmpenhoFolhaRubrica->sql_record($sSqlEmpenhos);
    $iLinhasPlanilha = $oDaoRhEmpenhoFolhaRubrica->numrows;
    if ( $iLinhasPlanilha > 0 ) {   

      for ($iInd = 0; $iInd < $oDaoRhEmpenhoFolhaRubrica->numrows; $iInd++) {
         
         $oDadosPlanilha = db_utils::fieldsMemory($rsEmpenhos,$iInd); 
         $oDaorhEmpenhoFolhaRubricaRetencao->excluir($oDadosPlanilha->rh78_sequencial);
         if ($oDaorhEmpenhoFolhaRubricaRetencao->erro_status == "0") {        
           throw new DBException("{$sMsgErro}\n\n$oDaorhEmpenhoFolhaRubricaRetencao->erro_msg}");
         }
         
         //excluimos da rhempenhofolharubricaplanilha
         $oDaorhEmpenhoFolhaRubricaPlanilha->excluir(null, "rh111_rhempenhofolharubrica = {$oDadosPlanilha->rh73_sequencial}");
         if ($oDaorhEmpenhoFolhaRubricaPlanilha->erro_status == "0") {        
           throw new DBException("{$sMsgErro}\n\n$oDaorhEmpenhoFolhaRubricaPlanilha->erro_msg}");
         }
         
         $oDaorhEmpenhoFolhaRhEmpRubrica->excluir(null, "rh81_rhempenhofolharubrica = {$oDadosPlanilha->rh73_sequencial}");
         if ($oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == "0") {
           throw new DBException("{$sMsgErro}\n\n$oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg}");
         }
         
         //excluimos da rhempenhofolharubrica
         $oDaorhEmpenhoFolhaRubrica->excluir($oDadosPlanilha->rh73_sequencial);
         if ($oDaorhEmpenhoFolhaRubrica->erro_status == "0") {        
           throw new DBException("{$sMsgErro}\n\n$oDaorhEmpenhoFolhaRubrica->erro_msg}");
         }
      }
    }
  }
  
  /**
   * Exclui as devoluções referentes aos dados para empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */  
  public function excluiDadosDevolucaoFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Exclusão de devoluções da folha abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    } 
    
    $oDaorhDevolucaoFolha              = db_utils::getDao('rhdevolucaofolha');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhDevolucaoFolhaRhEmpRubrica  = db_utils::getDao('rhdevolucaofolharhemprubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');    
    
    /**
     *  Cria um array contendo as rubricas de devolução 
     */
    try {
      $aDadosRubricas = $this->getRubricasDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }   
    
    if ( count($aDadosRubricas) > 0  ) {
       
      $aListaDevolucao = array();
      
      foreach ( $aDadosRubricas as $iInd => $oDevolucaoFolha ) {
        $aListaDevolucao[] = $oDevolucaoFolha->rh69_sequencial;
      }
      
      $aListaDevolucao = array_unique($aListaDevolucao);
      $sListaDevolucao = implode(',',$aListaDevolucao);

      /**
       *  Exclui as rubricas de retenção
       */
      $sSqlExcluiRetencoes  = " delete from rhempenhofolharubricaretencao                                                 "; 
      $sSqlExcluiRetencoes .= "  where rh78_rhempenhofolharubrica in ( select rh87_rhempenhofolharubrica                  ";
      $sSqlExcluiRetencoes .= "                                          from rhdevolucaofolharhemprubrica                ";
      $sSqlExcluiRetencoes .= "                                         where rh87_devolucaofolha in ({$sListaDevolucao}))";
      $rsExcluiRetencoes    = db_query($sSqlExcluiRetencoes);

      if ( !$rsExcluiRetencoes ) {
        throw new DBException("{$sMsgErro}\n\n".pg_last_error());
      }
      
      /**
       *  Exclui os registros da tabela de ligação entre rhdevolucaofolha e rhempenhofolharubrica  
       */
      $oDaorhDevolucaoFolhaRhEmpRubrica->excluir(null,"rh87_devolucaofolha in ({$sListaDevolucao})");
      
      if ( $oDaorhDevolucaoFolhaRhEmpRubrica->erro_status == 0 ) {
        throw new DBException("{$sMsgErro}\n\n{$oDaorhDevolucaoFolhaRhEmpRubrica->erro_msg}");
      }
      
      
      /**
       *  Exclui as rubricas ( rhempenhofolharubrica )
       */
      foreach ( $aDadosRubricas as $iInd => $oDevolucaoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oDevolucaoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
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
       *  Caso exista então são deletados os registros 
       */
      if ( $iLinhasDevolucao > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
          
          $oRHDevolucaoFolha= db_utils::fieldsMemory($rsDevolucaoFolha,$iInd);
          
          $oDaorhDevolucaoFolha->excluir($oRHDevolucaoFolha->rh69_sequencial);
          
          if ( $oDaorhDevolucaoFolha->erro_status == "0") {
            throw new DBException("{$sMsgErro}\n\n{$oDaorhDevolucaoFolha->erro_msg}");
          }      
        }
      }
    }
  }  
  
  
  /**
   * Gera os dados de retenções para os empenhos da folha 
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */  
  public function geraRetencoesEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre='') {
  	
  	$sMsgErro = 'Geração de retenções abortada';
  	
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }
    
    $oDaoCfPess                        = db_utils::getDao('cfpess');
    $oDaoPensaoRetencao                = db_utils::getDao('pensaoretencao');
    $oDaorhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaorhEmpenhoFolhaRubricaRetencao = db_utils::getDao('rhempenhofolharubricaretencao');
    $oDaorhEmpenhoFolhaRhEmpRubrica    = db_utils::getDao('rhempenhofolharhemprubrica');
    $oDaorhSlipFolhaRhEmpRubrica       = db_utils::getDao('rhslipfolharhemprubrica');
    
    /**
     *  Consulta a rubrica de pensão alimenticia 
     */
    $rsRubricPensao = $oDaoCfPess->sql_record($oDaoCfPess->sql_query_file($iAnoUsu,$iMesUsu,$iInstit,"r11_palime"));
    
    if ( $oDaoCfPess->numrows > 0 ) {
    	
    	$oRubricPensao = db_utils::fieldsMemory($rsRubricPensao,0);
    	
    	$sRubricPensao       = $oRubricPensao->r11_palime; // Pensão Alimentícia
    	$sRubricPensaoFerias = $sRubricPensao + 2000;      // Pensão Férias 
    	$sRubricPensao13     = $sRubricPensao + 4000;      // Pensão 13º
    	
    }
    
    /**
     *  Consulta os dados já gerados para empenhos
     */
    $sSqlDadosEmp  = " select distinct rh73_seqpes     as seqpes,                                         ";
    $sSqlDadosEmp .= "                 rh72_sequencial as rhempenho,                                      ";
    $sSqlDadosEmp .= "                 round(sum(case when rh73_pd = 2 then rh73_valor*-1 else rh73_valor end),2) as valor                                  ";  
    $sSqlDadosEmp .= "            from rhempenhofolharubrica                                              ";
    $sSqlDadosEmp .= "                 inner join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial     ";
    $sSqlDadosEmp .= "                 inner join rhempenhofolha             on rh72_sequencial            = rh81_rhempenhofolha ";  
    $sSqlDadosEmp .= "           where rh72_anousu      = {$iAnoUsu}                                      ";
    $sSqlDadosEmp .= "             and rh72_mesusu      = {$iMesUsu}                                      ";
    $sSqlDadosEmp .= "             and rh72_siglaarq    = '{$sSigla}'                                     ";
    $sSqlDadosEmp .= "             and rh72_tipoempenho = 1                                               ";
    $sSqlDadosEmp .= "             and rh72_tabprev     = 0                                               ";
    $sSqlDadosEmp .= "             and rh72_seqcompl    = '{$sSemestre}'                                  ";
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
     *  Consulta os regitros já gerados de slip 
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
    $sSqlDadosSlip .= "             and rh79_seqcompl    = '{$sSemestre}'                                                   ";
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
      case "sup":
        $sTabela      = "gerfsal";
        $sCampoPensao = "r52_valorsuplementar";
    }    
    
    $sTabelaGenerica  = $sTabela;
    $sSiglaGenerica   = $sSigla; 
    
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() &&
        dadosEmpenhoFolha::verificarPermissaoFolha($sSigla)) {
      
      if (FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR == FolhaPagamento::getTipoFolhaBySigla($sSigla)) {
        $sSiglaGenerica = 'r14';
      }
      
      $iTipoFolha      = FolhaPagamento::getTipoFolhaBySigla($sSigla);
      $sSqlSuplementar = " (
                              SELECT rh141_instit     AS {$sSiglaGenerica}_instit,
                                     rh141_mesusu     AS {$sSiglaGenerica}_mesusu,
                                     rh141_anousu     AS {$sSiglaGenerica}_anousu,
                                     rh143_valor      AS {$sSiglaGenerica}_valor,
                                     rh143_rubrica    AS {$sSiglaGenerica}_rubric,
                                     rh143_quantidade AS {$sSiglaGenerica}_quant,
                                     rh143_tipoevento AS {$sSiglaGenerica}_pd,
                                     rh143_regist     AS {$sSiglaGenerica}_regist,
                                     rh141_codigo     AS {$sSiglaGenerica}_semest  
                               FROM rhfolhapagamento
                                 INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento
                              WHERE rh141_tipofolha = {$iTipoFolha}
                                AND rh141_anousu    = {$iAnoUsu}
                                AND rh141_mesusu    = {$iMesUsu}
                                AND rh141_instit    = {$iInstit}
                             ) AS {$sTabela} ";
                         
      $sTabelaGenerica = $sSqlSuplementar;                 
    }
    
    /**
     * Cosulta os registros de retenção
     */
    $sSqlDadosRetencao  = " select distinct rh02_seqpes          as seqpes,                                 ";
    $sSqlDadosRetencao .= "                 rh02_regist          as regist,                                 ";
    $sSqlDadosRetencao .= "                 {$sSiglaGenerica}_rubric     as rubric,                         ";
    $sSqlDadosRetencao .= "                 {$sSiglaGenerica}_pd         as pd,                             ";
    $sSqlDadosRetencao .= "                 rh75_retencaotiporec as retencao,                               ";
    $sSqlDadosRetencao .= "                 {$sSiglaGenerica}_valor      as valor                           ";
    $sSqlDadosRetencao .= "   from {$sTabelaGenerica}                                                       ";
    $sSqlDadosRetencao .= "        inner join rhpessoalmov     on rh02_anousu    = {$sSiglaGenerica}_anousu ";
    $sSqlDadosRetencao .= "                                   and rh02_mesusu    = {$sSiglaGenerica}_mesusu ";
    $sSqlDadosRetencao .= "                                   and rh02_regist    = {$sSiglaGenerica}_regist ";
    $sSqlDadosRetencao .= "        inner join rhrubretencao    on rh75_rubric    = {$sSiglaGenerica}_rubric ";
    $sSqlDadosRetencao .= "                                   and rh75_instit    = {$sSiglaGenerica}_instit ";
    $sSqlDadosRetencao .= "        inner join retencaotiporec  on e21_sequencial = rh75_retencaotiporec     ";
    $sSqlDadosRetencao .= "  where e21_retencaotiporecgrupo = 2                                             ";
    $sSqlDadosRetencao .= "    and {$sSiglaGenerica}_anousu      = {$iAnoUsu}                               ";
    $sSqlDadosRetencao .= "    and {$sSiglaGenerica}_mesusu      = {$iMesUsu}                               ";
    $sSqlDadosRetencao .= "    and rh02_instit           = {$iInstit}                                       ";
    
    if ( $sSigla == 'r48' ) {
    	$sSqlDadosRetencao .= "  and {$sSiglaGenerica}_semest = {$sSemestre}                                  ";
    }
      
    if ( $sSigla == 'sup' ) {
      $sSqlDadosRetencao .= "  and r14_semest               = {$sSemestre}                                  ";
    }
    
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
             *  Define as retenções sobre as rubricas de empenhos
             */
          	if ( isset($aDadosEmp[$oDadosRetencao->seqpes]['rhempenho'] ) ) {

          		$iUltimoReg = end(array_keys($aDadosEmp[$oDadosRetencao->seqpes]['rhempenho']));
          		
	            foreach( $aDadosEmp[$oDadosRetencao->seqpes]['rhempenho'] as $iCodrhEmpenho => $aRhEmpenho ){
	          	
	            	$nPerc  = $aRhEmpenho['perc'];
	            	
	            	/**
	            	 * Verifica se a rubrica é pensão
	            	 */
	              if ( $oDadosRetencao->rubric == $sRubricPensao ||
	                   $oDadosRetencao->rubric == $sRubricPensaoFerias || 
	                   $oDadosRetencao->rubric == $sRubricPensao13  )  {
	            
	                /**
	                 * Verifica se existe pensão cadastrado para o servidor
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
			                 *  Define o valor da pensão apartir do tipo de pensão ( Alimentícia, Férias ou 13º )
			                 */
			                if ( $oDadosRetencao->rubric == $sRubricPensao ) {
	                      $nValorPensao = $oPensaoRetencao->$sCampoPensao;
			                } else if ( $oDadosRetencao->rubric == $sRubricPensaoFerias ) {
			                	$nValorPensao = $oPensaoRetencao->r52_valfer;
			                } else if ( $oDadosRetencao->rubric == $sRubricPensao13 ) {
			                	$nValorPensao = $oPensaoRetencao->r52_val13;
			                }

			                /**
			                 *  Calcula o valor da retenção sobre o percentual achado ateriormente
			                 */
		                  $nValorRetencao  = db_formatar($nValorPensao*($nPerc/100),'p');
		                  $nValorTotal    += $nValorRetencao;
		                   
		                  /**
		                   *  Caso seja o último registro então o valor da retençao fica como o restante
		                   */
		                  if ( $iUltimoReg == $iCodrhEmpenho && $iIndX == ($iLinhasPensaoRetencao-1) && !$lSlip ) {
		                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
		                  }

		                  /**
		                   *  Inclui a rubrica de retenção 
		                   */
	                    $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
			                $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
			                $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
			                $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
			                $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
			                $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
			                $oDaorhEmpenhoFolhaRubrica->incluir(null);
			                    
			                if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
			                  throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
			                }		                
			                
			                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
			                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodrhEmpenho;
			                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
			                                
			                if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
			                  throw new DBException($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
			                }               		                
			                
			                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
			                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPensaoRetencao->rh77_retencaotiporec;
			                $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
			                
			                if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
			                  throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
			                }
			              }
			            }
			            
			          } else {
	
			          	/**
			          	 *  Calcula o valor da retenção apartir do percentual achado anteriormente
			          	 */
                  $nValorRetencao  = db_formatar($oDadosRetencao->valor*($nPerc/100),'p');
	                $nValorTotal    += $nValorRetencao;
	                
                  /**
                   *  Caso seja o último registro então o valor da retençao fica como o restante
                   */	                
		              if ( $iUltimoReg == $iCodrhEmpenho  && !$lSlip ) {
		                 $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
		              }
		              
		              /**
		               *  Inclui a rubrica de retenção
		               */
		              $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
		              $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
		              $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
		              $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
		              $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
		              $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
		                  
		              $oDaorhEmpenhoFolhaRubrica->incluir(null);
		                  
		              if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
		                throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
		              }   		          	
		              
	                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodrhEmpenho;
	                $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
	                                    
	                if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
	                  throw new DBException($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
	                }                                   
		              
		              $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
		              $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDadosRetencao->retencao;
		              $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
		              
		              if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
		                throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
		              }
			          }
	            }
            }
            
            /**
             *  Define a retenção sobre as rubricas de slip
             */
            if ( isset($aDadosEmp[$oDadosRetencao->seqpes]['rhslip']) ) {
            	
              $iUltimoReg  = end(array_keys($aDadosEmp[$oDadosRetencao->seqpes]['rhslip']));
            	
	            foreach( $aDadosEmp[$oDadosRetencao->seqpes]['rhslip'] as $iCodrhSlip => $aRHSlip ){
	                         
	              $nPerc  = $aRHSlip['perc'];
	              
                /**
                 * Verifica se a rubrica é pensão
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
	                     * Verifica se existe pensão cadastrado para o servidor
	                     */	                    
	                    if ( $oDadosRetencao->rubric == $sRubricPensao ) {
	                      $nValorPensao = $oPensaoRetencao->$sCampoPensao;
	                    } else if ( $oDadosRetencao->rubric == $sRubricPensaoFerias ) {
	                      $nValorPensao = $oPensaoRetencao->r52_valfer;
	                    } else if ( $oDadosRetencao->rubric == $sRubricPensao13 ) {
	                      $nValorPensao = $oPensaoRetencao->r52_val13;
	                    }
	                    
                      /**
                       *  Calcula o valor da retenção sobre o percentual achado ateriormente
                       */	                    
		                  $nValorRetencao  = db_formatar($nValorPensao*($nPerc/100),'p');
		                  $nValorTotal    += $nValorRetencao;
		                  
                      /**
                       *  Caso seja o último registro então o valor da retençao fica como o restante
                       */		                  
                      if ( $iUltimoReg == $iCodrhSlip && $iIndX == ($iLinhasPensaoRetencao-1) ) {		
		                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);               
		                  }                   

		                  /**
		                   *  Inclui a rubrica de retenção
		                   */		                  
	                    $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
	                    $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
	                    $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
	                    $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
	                    $oDaorhEmpenhoFolhaRubrica->incluir(null);
	                        
	                    if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
	                      throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
	                    }                   
	                    
	                    $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                    $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodrhSlip;
	                    $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
	                                    
	                    if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
	                      throw new DBException($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
	                    }                                   
	                    
	                    $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
	                    $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPensaoRetencao->rh77_retencaotiporec;
	                    $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
	                    
	                    if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
	                      throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
	                    }
	                  }
	                }
	                
	              } else {

                  /**
                   *  Calcula o valor da retenção apartir do percentual achado anteriormente
                   */	              	
                  $nValorRetencao  = db_formatar($oDadosRetencao->valor*($nPerc/100),'p');
                  $nValorTotal    += $nValorRetencao;
                  
                  /**
                   *  Caso seja o último registro então o valor da retençao fica como o restante
                   */                     
                  if ( $iUltimoReg == $iCodrhSlip ) {
                     $nValorRetencao = $nValor - ($nValorTotal-$nValorRetencao);
                  }	 
                                   
                  /**
                   *  Inclui a rubrica de retenção
                   */	                  
	                $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDadosRetencao->rubric; 
	                $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDadosRetencao->seqpes;
	                $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
	                $oDaorhEmpenhoFolhaRubrica->rh73_valor           = db_formatar($nValorRetencao,'p');
	                $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDadosRetencao->pd;
	                $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 2;
	                    
	                $oDaorhEmpenhoFolhaRubrica->incluir(null);
	                    
	                if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
	                  throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
	                }                   
	                
	                $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
	                $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodrhSlip;
	                $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
	                                   
	                if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
	                  throw new DBException($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
	                }                               
	                
	                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
	                $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDadosRetencao->retencao;
	                $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
	                
	                if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
	                  throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
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
   * @param integer $iAnoUsu   Exercício da Folha 
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )
   */
  public function geraPagamentoExtra($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
  	
  	
    $sMsgErro = 'Geração de pagamento extra abortada';
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }  	

    
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
      case "sup":
        $sTabela      = "gerfsal";
      break;
    }
    
    $sTabelaGenerica  = $sTabela;
    $sSiglaGenerica   = $sSigla;
    
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() &&
        dadosEmpenhoFolha::verificarPermissaoFolha($sSigla)) {
      
      if (FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR == FolhaPagamento::getTipoFolhaBySigla($sSigla)) {
        $sSiglaGenerica = 'r14';
      }
      
      $iTipoFolha      = FolhaPagamento::getTipoFolhaBySigla($sSigla);
      $sSqlSuplementar = " (
                            SELECT rh141_instit     AS {$sSiglaGenerica}_instit,
                                   rh141_mesusu     AS {$sSiglaGenerica}_mesusu,
                                   rh141_anousu     AS {$sSiglaGenerica}_anousu,
                                   rh143_valor      AS {$sSiglaGenerica}_valor,
                                   rh143_rubrica    AS {$sSiglaGenerica}_rubric,
                                   rh143_quantidade AS {$sSiglaGenerica}_quant,
                                   rh143_tipoevento AS {$sSiglaGenerica}_pd,
                                   rh143_regist     AS {$sSiglaGenerica}_regist,
                                   rh141_codigo     AS {$sSiglaGenerica}_semest
                             FROM rhfolhapagamento
                               INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento
                            WHERE rh141_tipofolha = {$iTipoFolha}
                              AND rh141_anousu    = {$iAnoUsu}
                              AND rh141_mesusu    = {$iMesUsu}
                              AND rh141_instit    = {$iInstit}
                           ) AS {$sTabela} ";
                       
      $sTabelaGenerica = $sSqlSuplementar;                    
    }
    
    /**
     *  Consulta as rubricas referente a pagamento extra
     */
    $sSqlPagExtra  = " select {$sSiglaGenerica}_rubric     as rubric,                                                            ";
    $sSqlPagExtra .= "        rh02_seqpes          as pessoalmov,                                                                ";
    $sSqlPagExtra .= "        {$sSiglaGenerica}_pd         as pd,                                                                ";
    $sSqlPagExtra .= "        {$sSiglaGenerica}_valor      as valor,                                                             ";
    $sSqlPagExtra .= "        rh30_vinculo         as vinculo,                                                                   ";
    $sSqlPagExtra .= "        rh02_lota            as lotacao,                                                                   ";
    $sSqlPagExtra .= "        r70_concarpeculiar   as caract,                                                                    ";
    $sSqlPagExtra .= "        rh75_retencaotiporec as retencao                                                                   ";
    $sSqlPagExtra .= "   from {$sTabelaGenerica}                                                                                 ";
    $sSqlPagExtra .= "        inner join rhpessoalmov    on rhpessoalmov.rh02_anousu       = {$sTabela}.{$sSiglaGenerica}_anousu ";
    $sSqlPagExtra .= "                                  and rhpessoalmov.rh02_mesusu       = {$sTabela}.{$sSiglaGenerica}_mesusu ";
    $sSqlPagExtra .= "                                  and rhpessoalmov.rh02_regist       = {$sTabela}.{$sSiglaGenerica}_regist ";
    $sSqlPagExtra .= "        inner join rhlota          on rhlota.r70_codigo              = rhpessoalmov.rh02_lota              ";
    $sSqlPagExtra .= "        inner join rhrubretencao   on rhrubretencao.rh75_rubric      = {$sTabela}.{$sSiglaGenerica}_rubric "; 
    $sSqlPagExtra .= "                                  and rhrubretencao.rh75_instit      = {$sTabela}.{$sSiglaGenerica}_instit ";
    $sSqlPagExtra .= "        inner join retencaotiporec on retencaotiporec.e21_sequencial = rhrubretencao.rh75_retencaotiporec  ";
    $sSqlPagExtra .= "        inner join rhregime        on rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg            ";
    $sSqlPagExtra .= "                                  and rhregime.rh30_instit           = rhpessoalmov.rh02_instit            ";
    $sSqlPagExtra .= "  where e21_retencaotiporecgrupo = 3                                                                       ";
    $sSqlPagExtra .= "    and {$sSiglaGenerica}_anousu = {$iAnoUsu}                                                              ";
    $sSqlPagExtra .= "    and {$sSiglaGenerica}_mesusu = {$iMesUsu}                                                              ";
    $sSqlPagExtra .= "    and {$sSiglaGenerica}_instit = {$iInstit}                                                              ";
    
    
    if ( $sSigla == 'r48') {
    	$sSqlPagExtra .= "  and r48_semest       = {$sSemestre}                                                                    ";
    }
      
    if ( $sSigla == 'sup' ) {
      $sSqlPagExtra .= "  and r14_semest       = {$sSemestre}                                                                    ";
    }
    
    $rsPagExtra = db_query($sSqlPagExtra);
    
    if ( $rsPagExtra ) {
    	
    	$iLinhasPagExtra = pg_num_rows($rsPagExtra);
    	
    	if ( $iLinhasPagExtra > 0 ) {
    		
    		for ( $iInd=0; $iInd < $iLinhasPagExtra; $iInd++ ) {
    			
    			$oPagExtra = db_utils::fieldsMemory($rsPagExtra,$iInd);
    			$iCaract   = $oPagExtra->caract;
    			
    			/**
    			 * Consulta recurso apartir da lotação e vínculo achado no consulta anterior
    			 */
			    $sCamposrhLotaVinc = " rh25_recurso as recurso   ";
			          
			    $sWhererhLotaVinc  = "     rh25_codigo  = {$oPagExtra->lotacao}   "; 
			    $sWhererhLotaVinc .= " and rh25_vinculo = '{$oPagExtra->vinculo}' "; 
			    $sWhererhLotaVinc .= " and rh25_anousu  = {$iAnoUsu}              ";
			          
			    $sSqlrhLotaVinc = $oDaorhLotaVinc->sql_query_file(null,$sCamposrhLotaVinc,null,$sWhererhLotaVinc);
			    $rsProjAtivRec  = $oDaorhLotaVinc->sql_record($sSqlrhLotaVinc);
			          
			    
			    if ( $oDaorhLotaVinc->numrows == 0 ){
			      throw new BusinessException("Verifique recurso e projeto atividade da lotação {$oPagExtra->lotacao}");
			    } else {
			      $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
			      $iRecurso = $oProjAtivRec->recurso;
			    }         			
    			
			    /**
			     *  Verifica se já existe  registros na rhslipfolha apartir dos dados encontrados
			     */
          $sWhereSlipFolha  = "     rh79_recurso        = {$iRecurso}    ";
          $sWhereSlipFolha .= " and rh79_anousu         = {$iAnoUsu}     ";
          $sWhereSlipFolha .= " and rh79_mesusu         = {$iMesUsu}     ";
          $sWhereSlipFolha .= " and rh79_siglaarq       = '{$sSigla}'    ";
          $sWhereSlipFolha .= " and rh79_concarpeculiar = '{$iCaract}'   ";
          $sWhereSlipFolha .= " and rh79_tipoempenho    = 1              ";
          $sWhereSlipFolha .= " and rh79_tabprev        = 0              ";
          $sWhereSlipFolha .= " and rh79_seqcompl       = '{$sSemestre}' ";
          
          $sSqlSlipFolha    = $oDaorhSlipFolha->sql_query_file(null,"rh79_sequencial",null,$sWhereSlipFolha);
          $rsSlipFolha      = $oDaorhSlipFolha->sql_record($sSqlSlipFolha);
           
          /**
           *  Caso exista então é utilizado o mesmo sequencial para inclusão das rubricas, 
           *  caso contráio é inserido un registro novo
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
            $oDaorhSlipFolha->rh79_concarpeculiar = "{$iCaract}";
            $oDaorhSlipFolha->rh79_seqcompl       = $sSemestre;
            
            $oDaorhSlipFolha->incluir(null);
            
            if ( $oDaorhSlipFolha->erro_status == '0' ) {
               throw new DBException($oDaorhSlipFolha->erro_msg);
            }

            $iCodSlipFolha = $oDaorhSlipFolha->rh79_sequencial; 
            
          }
               
          /**
           *  Inclusão das rubricas 
           */
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oPagExtra->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oPagExtra->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = $oPagExtra->valor;
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oPagExtra->pd;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 3;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhSlipFolhaRhEmpRubrica->rh80_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhSlipFolhaRhEmpRubrica->rh80_rhslipfolha           = $iCodSlipFolha;
          $oDaorhSlipFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhSlipFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhSlipFolhaRhEmpRubrica->erro_msg);
          }

          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oPagExtra->retencao;
          $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
                      
          if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
          }          
    		}
    	}
    	
    } 	

  }
  
  
  /**
   * Gera dados referentes a devoluções de empenhos da folha
   *
   * @param string  $sSigla    Tipo de Folha
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição 
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   */
  public function geraDevolucoesEmpenhosFolha($sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre=''){
    
    $sMsgErro = 'Geração de devoluções da folha';
    
    if ( !db_utils::inTransaction() ){
      throw new ParameterException("{$sMsgErro}\nnenhuma transação encontrada!");
    }    
    
    if ( trim($sSigla) == '' ) {
      throw new DBException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }   
    
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
      case "sup":
        $sTabela      = "gerfsal";
      break;
    }    

    $sTabelaGenerica  = $sTabela; 
    $sSiglaGenerica   = $sSigla;
    
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() &&
        dadosEmpenhoFolha::verificarPermissaoFolha($sSigla)) {
           
      if (FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR == FolhaPagamento::getTipoFolhaBySigla($sSigla)) {
        $sSiglaGenerica = 'r14';
      }
      
      $iTipoFolha      = FolhaPagamento::getTipoFolhaBySigla($sSigla);        
      $sSqlSuplementar = " (
                            SELECT rh141_instit     AS {$sSiglaGenerica}_instit,
                                   rh141_mesusu     AS {$sSiglaGenerica}_mesusu,
                                   rh141_anousu     AS {$sSiglaGenerica}_anousu,
                                   rh143_valor      AS {$sSiglaGenerica}_valor,
                                   rh143_rubrica    AS {$sSiglaGenerica}_rubric,
                                   rh143_quantidade AS {$sSiglaGenerica}_quant,
                                   rh143_tipoevento AS {$sSiglaGenerica}_pd,
                                   rh143_regist     AS {$sSiglaGenerica}_regist,
                                   rh141_codigo     AS {$sSiglaGenerica}_semest
                             FROM rhfolhapagamento
                               INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento
                            WHERE rh141_tipofolha = {$iTipoFolha}
                              AND rh141_anousu    = {$iAnoUsu}
                              AND rh141_mesusu    = {$iMesUsu}
                              AND rh141_instit    = {$iInstit}
                           ) AS {$sTabela} ";
                           
      $sTabelaGenerica = $sSqlSuplementar;                 
    }
    
    /**
     *  Consulta todas rubricas de devolução
     */
    $sSqlDevolucao  = " select {$sSiglaGenerica}_rubric     as rubric,                                                            ";
    $sSqlDevolucao .= "        rh02_seqpes          as pessoalmov,                                                                ";
    $sSqlDevolucao .= "        {$sSiglaGenerica}_pd         as pd,                                                                ";
    $sSqlDevolucao .= "        {$sSiglaGenerica}_valor      as valor,                                                             ";
    $sSqlDevolucao .= "        rh30_vinculo         as vinculo,                                                                   ";
    $sSqlDevolucao .= "        rh02_lota            as lotacao,                                                                   ";
    $sSqlDevolucao .= "        r70_concarpeculiar   as caract,                                                                    ";
    $sSqlDevolucao .= "        rh75_retencaotiporec as retencao                                                                   ";
    $sSqlDevolucao .= "   from {$sTabela}                                                                                         ";
    $sSqlDevolucao .= "        inner join rhpessoalmov    on rhpessoalmov.rh02_anousu       = {$sTabela}.{$sSiglaGenerica}_anousu ";
    $sSqlDevolucao .= "                                  and rhpessoalmov.rh02_mesusu       = {$sTabela}.{$sSiglaGenerica}_mesusu ";
    $sSqlDevolucao .= "                                  and rhpessoalmov.rh02_regist       = {$sTabela}.{$sSiglaGenerica}_regist ";
    $sSqlDevolucao .= "        inner join rhlota          on rhlota.r70_codigo              = rhpessoalmov.rh02_lota              ";
    $sSqlDevolucao .= "        inner join rhrubretencao   on rhrubretencao.rh75_rubric      = {$sTabela}.{$sSiglaGenerica}_rubric "; 
    $sSqlDevolucao .= "                                  and rhrubretencao.rh75_instit      = {$sTabela}.{$sSiglaGenerica}_instit ";
    $sSqlDevolucao .= "        inner join retencaotiporec on retencaotiporec.e21_sequencial = rhrubretencao.rh75_retencaotiporec  ";
    $sSqlDevolucao .= "        inner join rhregime        on rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg            ";
    $sSqlDevolucao .= "                                  and rhregime.rh30_instit           = rhpessoalmov.rh02_instit            ";
    $sSqlDevolucao .= "  where e21_retencaotiporecgrupo = 4                                                                       ";
    $sSqlDevolucao .= "    and {$sSiglaGenerica}_anousu = {$iAnoUsu}                                                              ";
    $sSqlDevolucao .= "    and {$sSiglaGenerica}_mesusu = {$iMesUsu}                                                              ";
    $sSqlDevolucao .= "    and {$sSiglaGenerica}_instit = {$iInstit}                                                              ";
    
    if ( $sSigla == 'r48') {
      $sSqlDevolucao .= "  and r48_semest       = {$sSemestre}                                                                   ";
    }
      
    if ( $sSigla == 'sup' ) {
      $sSqlDevolucao .= "  and r14_semest       = {$sSemestre}                                                                   ";
    }
    
    $rsDevolucao = db_query($sSqlDevolucao);
    
    if ( $rsDevolucao ) {
      
      $iLinhasDevolucao = pg_num_rows($rsDevolucao);
      
      if ( $iLinhasDevolucao > 0 ) {
        
        for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
          
          $oDevolucao = db_utils::fieldsMemory($rsDevolucao,$iInd);
          $iCaract   = $oDevolucao->caract;
          
          /**
           * Consulta recurso apartir da lotação e vínculo achado no consulta anterior
           */          
          $sCamposrhLotaVinc = " rh25_recurso as recurso   ";
                
          $sWhererhLotaVinc  = "     rh25_codigo  = {$oDevolucao->lotacao}   "; 
          $sWhererhLotaVinc .= " and rh25_vinculo = '{$oDevolucao->vinculo}' "; 
          $sWhererhLotaVinc .= " and rh25_anousu  = {$iAnoUsu}              ";
                
          $sSqlrhLotaVinc = $oDaorhLotaVinc->sql_query_file(null,$sCamposrhLotaVinc,null,$sWhererhLotaVinc);
          $rsProjAtivRec  = $oDaorhLotaVinc->sql_record($sSqlrhLotaVinc);
                
          if ( $oDaorhLotaVinc->numrows == 0 ){
            throw new BusinessException("Verifique recurso e projeto atividade da lotação {$oDevolucao->lotacao}");
          } else {
            $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
            $iRecurso = $oProjAtivRec->recurso;
          }               
          
          /**
           *  Verifica se já existe registro na rhdevolucaofolha apartir dos dados encontrado
           */
          $sWhereDevolucaoFolha  = "     rh69_recurso        = {$iRecurso}    ";
          $sWhereDevolucaoFolha .= " and rh69_anousu         = {$iAnoUsu}     ";
          $sWhereDevolucaoFolha .= " and rh69_mesusu         = {$iMesUsu}     ";
          $sWhereDevolucaoFolha .= " and rh69_siglaarq       = '{$sSigla}'    ";
          $sWhereDevolucaoFolha .= " and rh69_concarpeculiar = '{$iCaract}'   ";
          $sWhereDevolucaoFolha .= " and rh69_tipoempenho    = 1              ";
          $sWhereDevolucaoFolha .= " and rh69_tabprev        = 0              ";
          $sWhereDevolucaoFolha .= " and rh69_seqcompl       = '{$sSemestre}' ";
          
          $sSqlDevolucaoFolha    = $oDaorhDevolucaoFolha->sql_query_file(null,"rh69_sequencial",null,$sWhereDevolucaoFolha);
          $rsDevolucaoFolha      = $oDaorhDevolucaoFolha->sql_record($sSqlDevolucaoFolha);
           
          /**
           *  Caso exista então é utilizado o mesmo sequencial para inclusão das rubricas, 
           *  caso contráio é inserido un registro novo
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
            $oDaorhDevolucaoFolha->rh69_seqcompl       = $sSemestre;
            
            $oDaorhDevolucaoFolha->incluir(null);
            
            if ( $oDaorhDevolucaoFolha->erro_status == '0' ) {
               throw new DBException($oDaorhDevolucaoFolha->erro_msg);
            }

            $iCodDevolucaoFolha = $oDaorhDevolucaoFolha->rh69_sequencial; 
            
          }
               
          /**
           * Incluisão das rubricas
           */          
          $oDaorhEmpenhoFolhaRubrica->rh73_rubric          = $oDevolucao->rubric; 
          $oDaorhEmpenhoFolhaRubrica->rh73_seqpes          = $oDevolucao->pessoalmov;
          $oDaorhEmpenhoFolhaRubrica->rh73_instit          = $iInstit;
          $oDaorhEmpenhoFolhaRubrica->rh73_valor           = $oDevolucao->valor;
          $oDaorhEmpenhoFolhaRubrica->rh73_pd              = $oDevolucao->pd;
          $oDaorhEmpenhoFolhaRubrica->rh73_tiporubrica     = 4;
                  
          $oDaorhEmpenhoFolhaRubrica->incluir(null);
                  
          if ( $oDaorhEmpenhoFolhaRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhDevolucaoFolhaRhEmpRubrica->rh87_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhDevolucaoFolhaRhEmpRubrica->rh87_devolucaofolha        = $iCodDevolucaoFolha;
          $oDaorhDevolucaoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhDevolucaoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhDevolucaoFolhaRhEmpRubrica->erro_msg);
          }

          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial;
          $oDaorhEmpenhoFolhaRubricaRetencao->rh78_retencaotiporec       = $oDevolucao->retencao;
          $oDaorhEmpenhoFolhaRubricaRetencao->incluir(null);
                      
          if ( $oDaorhEmpenhoFolhaRubricaRetencao->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRubricaRetencao->erro_msg);
          }          
        }
      }
    }   
  }  
  
  
  /**
   * Consulta o estrutural apartir da lotação  
   *
   * @param  integer $iAnoUsu
   * @param  integer $iLotacao
   * @param  string  $sVinculo
   * @param  integer $iElemento
   * @return object 
   */
  public function getEstrututal($iAnoUsu,$iLotacao,$sVinculo,$iElemento){

  	if ( trim($iAnoUsu) == '' ) {
  		throw new ParameterException('Exercício não informado!');
  	}
    if ( trim($iLotacao) == '' ) {
      throw new ParameterException('Lotação não informada!');
    }
    if ( trim($sVinculo) == '' ) {
      throw new ParameterException('Vinculo não informado!');
    }
    if ( trim($iElemento) == '' ) {
      throw new ParameterException('Elemento não informado!');
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
      throw new BusinessException("Configure os parâmetros do orçamento para o ano {$iAnoUsu}!");
    }      
    
    /**
     *  Consulta Orgão e Unidade
     */
    $sCamposrhLotaExe  = " rh26_orgao   as orgao, ";
    $sCamposrhLotaExe .= " rh26_unidade as unidade";
    $sSqlrhLotaExe     = $oDaorhLotaExe->sql_query_file($iAnoUsu,$iLotacao,$sCamposrhLotaExe);
    $rsOrgUnid         = $oDaorhLotaExe->sql_record($sSqlrhLotaExe);

          
    if ( $oDaorhLotaExe->numrows == 0 ) {
      throw new BusinessException("{$sMsgErro}\nverifique órgão e unidade da lotação {$iLotacao}");
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
      throw new BusinessException("Verifique recurso e projeto atividade da lotação {$iLotacao}");
    } else {
      $oProjAtivRec =  db_utils::fieldsMemory($rsProjAtivRec,0);
    }           
          
    /**
     *   Caso exista algum registro na tabela rhlotavincele em que o
     *    rh28_codlotavinc = $oGerador->lotavinc e rh28_codele = $oGerador->elemento
     *     - Se forem diferentes:
     *        O elemento a ser gravado na tabela rhempenhofolha sera o $oGerador->elemento
     *        O projeto atividade a ser gravado na tabela rhempenhofolha será o $oProjAtivRec->projativ
     *     - Se forem iguais:
     *        O elemento a ser gravado na tabela rhempenhofolhafolha será o $oDadosNovos->elementonovo
     *
     *   Caso exista algum registro na tabela rhlotavincativ em que o 
     *     rh28_codlotavinc = $oProjAtivRec->lotavinc e rh28_codelenov = $oDadosNovos->elementonovo
     *     - Se tiver algum registro, o projeto atividade a ser gravado na tabela rhempenhofolha 
     *        será $oNovoProjAtiv->projativnovo
     *     - Caso contrario, o projeto atividade a ser gravado na tabela rhempenhofolha 
     *        será $oProjAtivRec->projativ
     *    
     *   Caso exista algum registro na tabela rhlotavincrec em que o
     *     rh43_codlotavinc = $oProjAtivRec->lotavinc e rh43_codelenov = $oDadosNovos->elementonovo
     *     - Se tiver algum registro, o recurso a ser gravado na tabela rhempenhofolha
     *        será $oNovoRecurso->recursonovo
     *     - Caso contrário, o recurso a ser gravado na tabela rhempenhofolha
     *        será $oProjAtivRec->recurso        
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
      
      $sWhereParam  = null;
              
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
     *  Consulta Dotação
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
    $oEstrutural->iSubFuncao = $iSubFuncao;              
    $oEstrutural->iPrograma  = $iPrograma;    
    $oEstrutural->iRecurso   = $iRecurso;
    $oEstrutural->iElemento  = $iElemento;
    $oEstrutural->iDotacao   = $iDotacao;

    return $oEstrutural;
    
    
  }
  
  
  /**
   * Gera dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   */
  public function geraDadosEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Geração de empenhos FGTS abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\ntipo de folha não informado!");
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
      throw new BusinessException("{$sMsgErro}\nconfigure os parâmetros do orçamento para o ano {$iAnoUsu}!");
    }  

    /**
     *  Retorna as siglas referente aos tipos de folha
     */
    try {
    	$aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }

    
    $sSqlGerador = '';
    
    
    foreach ( $aSiglas as $iInd => $sSigla ){

      try {
	      $lLiberada = $this->isLiberada($sSigla,
	                                     3,
	                                     $iAnoUsu,
	                                     $iMesUsu);
	    } catch ( DBException $eException ){
        throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( BusinessException $eException ){
        throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( ParameterException $eException ){
        throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( FileException $eException ){
        throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	    } catch ( Exception $eException ){
        throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	    }    
	    
	    if ( $lLiberada ) {
	      throw new BusinessException("{$sMsgErro}\nempenho liberado!");
	    }
			$sSiglaGerador = $sSigla;
			if ('sup' == $sSigla) {
				$sSigla = "r14";
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
	    
	    $sSqlGerador   .= $clgeradorsql->gerador_sql( $sSiglaGerador,
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
            
          } catch ( DBException $eException ){
            throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( BusinessException $eException ){
            throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( ParameterException $eException ){
            throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( FileException $eException ){
            throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
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
               throw new DBException($oDaorhEmpenhoFolha->erro_msg);
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          
          try {
            $aExcecoes = $this->getExcessoesEmpenhoFolha($sSigla, $oGerador->rubric,$iAnoUsu,$iInstit);
          } catch ( DBException $eException ){
            throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( BusinessException $eException ){
            throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( ParameterException $eException ){
            throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( FileException $eException ){
            throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	        }

          if ( !empty($aExcecoes) ) {

            if (!empty($aExcecoes[0]->rh74_orgao)) { 
              $iOrgao     = $aExcecoes[0]->rh74_orgao;
            }
            if (!empty($aExcecoes[0]->rh74_unidade)) {
              $iUnidade   = $aExcecoes[0]->rh74_unidade;  
            }
            if (!empty($aExcecoes[0]->rh74_projativ)) {
              $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
            }
            if (!empty($aExcecoes[0]->rh74_funcao)) {
              $iFuncao    = $aExcecoes[0]->rh74_funcao;
            }
            if (!empty($aExcecoes[0]->rh74_subfuncao)) {
              $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
            }
            if (!empty($aExcecoes[0]->rh74_programa)) {
              $iPrograma  = $aExcecoes[0]->rh74_programa;
            }
            if (!empty($aExcecoes[0]->rh74_recurso)) {
              $iRecurso   = $aExcecoes[0]->rh74_recurso;
            }
            if (!empty($aExcecoes[0]->rh74_concarpeculiar)) {
              $iCaract    = "{$aExcecoes[0]->rh74_concarpeculiar}";
            }
            if(!empty($aExcecoes[0]->rh74_codele)) {
              $iElemento  = $aExcecoes[0]->rh74_codele;
            }
            
            $iDotacao = $this->getDotacaoByFiltro($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElemento, $iAnoUsu, $iFuncao, $iSubFuncao, $iPrograma);
                  
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
                throw new DBException($oDaorhEmpenhoFolha->erro_msg);
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
            throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
          
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
          $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
          }                    
          
        } 
        
      } else {
        throw new DBException("{$sMsgErro}\nnenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
      }
    } else {
      throw new DBException("{$sMsgErro}\nErro na consulta!");
    }
    
  }
  
  
  /**
   * Retorna os dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   * @return array
   */  
  public function getRubricasEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Consulta de empenhos FGTS abortada';
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\nTipo de folha não informado!");
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
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
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
      $aEmpenhosFolha = db_utils::getCollectionByRecord($rsEmpenhosFolha);
    } else {
      $aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha referente a FGTS ( rubricas = R991 )
   *
   * @param string  $sTipo     Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu   Exercício da Folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   Instituição
   */
  public function excluiDadosEmpenhosFGTS($sTipo='',$iAnoUsu='',$iMesUsu='',$iInstit=''){
    
    $sMsgErro = 'Exclusão de empenhos FGTS abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\ntipo de folha não informado!");
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
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }

    
    if ( count($aDadosEmpenhos) > 0  ) {
       
      $aListaEmpenhos = array();
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $aListaEmpenhos[] = $oEmpenhoFolha->rh72_sequencial;
        if ( trim($oEmpenhoFolha->rh76_sequencial) != '' ) {
          throw new BusinessException("{$sMsgErro}\n\nRegistros já empenhados!");
        }
      }
      
      $aListaEmpenhos = array_unique($aListaEmpenhos);
      $sListaEmpenhos = implode(',',$aListaEmpenhos);
      
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new DBException("{$sMsgErro}\n\n".pg_last_error());
      }      
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
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
            throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolha->erro_msg}");
          }
        }
      }
    }
    
  }  
  
  
  /**
   * Retorna um array contendo as siglas referente ao tipo de folha dependendo do tipo
   * passado por parâmetro 
   *
   * @param string $sTipo Tipo ( m = Mensal ou d = 13º )
   * @return array
   */
	public function getSiglasFGTS($sTipo=''){
		
		if ( trim($sTipo) == '') {
			throw new ParameterException("Tipo de folha não informado!");
		}
		
    if ( $sTipo == 'm') {
      
      $aSiglas = array('r14',
                       'r48',
                       'r20');

      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()){
        $aSiglas = array('r14',
                         'r48',
                         'r20',
			  	               'sup');
      }
      
    } else if( $sTipo == 'd') {
      $aSiglas = array('r35');
    } else {
    	throw new BusinessException("Tipo de folha difere de mensal e 13º!");
    }
    
    return $aSiglas;
    
	}
	
	
  /**
   * Gera dados para empenhos da folha referente a Previdência ( rubricas = R992 )
   *
   * @param string  $sTipo      Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu    Exercício da Folha
   * @param integer $iMesUsu    Mês da Folha
   * @param string  $sListaPrev Lista de Previdências 
   * @param integer $iInstit    Instituição
   */	
  public function geraDadosEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
    
    $sMsgErro = 'Geração de empenhos da previdência abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\ntipo de folha não informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($sListaPrev) == '' ) {
      throw new ParameterException("{$sMsgErro}\nprevidência não informada!");
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
      throw new BusinessException("{$sMsgErro}\nconfigure os parâmetros do orçamento para o ano {$iAnoUsu}!");
    }  
    
    try {
      $aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }

    
    $sSqlGerador = '';
    
    foreach ( $aSiglas as $iInd => $sSigla ){    
    
      try {
	      $lLiberada = $this->isLiberada($sSigla,
	                                     2,
	                                     $iAnoUsu,
	                                     $iMesUsu,
	                                     null, 
	                                     $sListaPrev); 
	    } catch ( DBException $eException ){
        throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( BusinessException $eException ){
        throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( ParameterException $eException ){
        throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	    } catch ( FileException $eException ){
        throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	    } catch ( Exception $eException ){
        throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	    }   
	    
	    if ( $lLiberada ) {
	      throw new BusinessException("{$sMsgErro}\nempenho liberado!");
	    }
			$sSiglaGerador = $sSigla;
			if ('sup' == $sSigla) {
				$sSigla = "r14";
			}
      $sCampos  = "distinct rh30_vinculo     as vinculo,                                            ";
      $sCampos .= "         rh02_lota        as lotacao,                                            ";
      $sCampos .= "case                                                                             ";
      $sCampos .= "   when (select distinct                                                         ";
      $sCampos .= "                r33_codele                                                       ";
      $sCampos .= "           from inssirf                                                          ";
      $sCampos .= "          where r33_anousu = {$iAnoUsu}                                          ";
      $sCampos .= "            and r33_mesusu = {$iMesUsu}                                          ";
      $sCampos .= "            and r33_instit = {$iInstit}                                          ";
      $sCampos .= "            and (cast((r33_codtab) as integer)-2) = rh02_tbprev) is null         ";
      $sCampos .= "     then rh23_codele                                                            ";
      $sCampos .= "   else (select distinct                                                         ";
      $sCampos .= "                r33_codele                                                       ";
      $sCampos .= "           from inssirf                                                          ";
      $sCampos .= "          where r33_anousu = {$iAnoUsu}                                          ";
      $sCampos .= "            and r33_mesusu = {$iMesUsu}                                          ";
      $sCampos .= "            and r33_instit = {$iInstit}                                          ";
      $sCampos .= "            and (cast((r33_codtab) as integer)-2) = rh02_tbprev) end as elemento,";
      $sCampos .= "         {$sSigla}_rubric as rubric,                                             ";
      $sCampos .= "         {$sSigla}_regist as regist,                                             ";
      $sCampos .= "         rh02_seqpes      as pessoalmov,                                         ";
      $sCampos .= "         {$sSigla}_pd     as pd,                                                 ";
      $sCampos .= "         {$sSigla}_quant  as quant,                                              ";
      $sCampos .= "         {$sSigla}_valor  as valor,                                              ";
      $sCampos .= "         {$sSigla}_anousu as anousu,                                             ";
      $sCampos .= "         {$sSigla}_mesusu as mesusu,                                             ";     
      $sCampos .= "         {$sSigla}_semest as semestre,                                           ";
      $sCampos .= "         '{$sSigla}'      as sigla,                                              ";
      $sCampos .= "         rh02_tbprev      as previdencia,                                        ";
      $sCampos .= "         r70_concarpeculiar as caract                                            ";
      

      $sWhereGerador  = "     rh23_codele is not null        ";
      $sWhereGerador .= " and {$sSigla}_rubric = 'R992'      ";
      $sWhereGerador .= " and rh02_tbprev in ({$sListaPrev}) ";
      
      
      if ( trim($sSqlGerador) != '' ) {
        $sSqlGerador .= ' union all ';
      }
      
      $sSqlGerador   .= $clgeradorsql->gerador_sql( $sSiglaGerador,
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
            
          } catch ( DBException $eException ){
            throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( BusinessException $eException ){
            throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( ParameterException $eException ){
            throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( FileException $eException ){
            throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
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
          $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'             ";
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
               throw new DBException($oDaorhEmpenhoFolha->erro_msg);
            }

            $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            
          }
          
          try {
            $aExcecoes = $this->getExcessoesEmpenhoFolha($sSigla, $oGerador->rubric,$iAnoUsu,$iInstit);
          } catch ( DBException $eException ){
            throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( BusinessException $eException ){
            throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( ParameterException $eException ){
            throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	        } catch ( FileException $eException ){
            throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	        }

          if ( !empty($aExcecoes) ) {

             if (!empty($aExcecoes[0]->rh74_orgao)) { 
              $iOrgao     = $aExcecoes[0]->rh74_orgao;
            }
            if (!empty($aExcecoes[0]->rh74_unidade)) {
              $iUnidade   = $aExcecoes[0]->rh74_unidade;  
            }
            if (!empty($aExcecoes[0]->rh74_projativ)) {
              $iProjAtiv  = $aExcecoes[0]->rh74_projativ;
            }
            if (!empty($aExcecoes[0]->rh74_funcao)) {
              $iFuncao    = $aExcecoes[0]->rh74_funcao;
            }
            if (!empty($aExcecoes[0]->rh74_subfuncao)) {
              $iSubFuncao = $aExcecoes[0]->rh74_subfuncao;
            }
            if (!empty($aExcecoes[0]->rh74_programa)) {
              $iPrograma  = $aExcecoes[0]->rh74_programa;
            }
            if (!empty($aExcecoes[0]->rh74_recurso)) {
              $iRecurso   = $aExcecoes[0]->rh74_recurso;
            }
            if (!empty($aExcecoes[0]->rh74_concarpeculiar)) {
              $iCaract    = "{$aExcecoes[0]->rh74_concarpeculiar}";
            }
            if(!empty($aExcecoes[0]->rh74_codele)) {
              $iElemento  = $aExcecoes[0]->rh74_codele;
            }
            
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
            $sWhereEmpenhoFolha .= " and rh72_concarpeculiar = '{$iCaract}'              ";            
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
                throw new DBException($oDaorhEmpenhoFolha->erro_msg);
              }
              $iCodEmpenhoFolha = $oDaorhEmpenhoFolha->rh72_sequencial; 
            }
          }

          $sSqlPercPatronal  = " select distinct r33_ppatro     ";
          $sSqlPercPatronal .= "   from inssirf                 ";
          $sSqlPercPatronal .= "  where r33_anousu = {$iAnoUsu} ";
          $sSqlPercPatronal .= "    and r33_mesusu = {$iMesUsu} ";
          $sSqlPercPatronal .= "    and r33_codtab = ".($oGerador->previdencia+2);
          $sSqlPercPatronal .= "    and r33_instit = {$iInstit} ";
          
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
            throw new DBException($oDaorhEmpenhoFolhaRubrica->erro_msg);
          }
                        
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolharubrica = $oDaorhEmpenhoFolhaRubrica->rh73_sequencial; 
          $oDaorhEmpenhoFolhaRhEmpRubrica->rh81_rhempenhofolha        = $iCodEmpenhoFolha;
          $oDaorhEmpenhoFolhaRhEmpRubrica->incluir(null);
                                    
          if ( $oDaorhEmpenhoFolhaRhEmpRubrica->erro_status == '0' ) {
            throw new DBException($oDaorhEmpenhoFolhaRhEmpRubrica->erro_msg);
          }          
          
        } 
        
      } else {
        throw new DBException("{$sMsgErro}\nnenhum registro encontrado para {$iAnoUsu} / {$iMesUsu}");
      }
    } else {
      throw new DBException("{$sMsgErro}\nErro na consulta!");
    }
    
  }


  /**
   * Retorna os dados para empenhos da folha referente a Previência ( rubricas = R992 )
   *
   * @param string  $sTipo       Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu     Exercício da Folha
   * @param integer $iMesUsu     Mês da Folha
   * @param string  $sListaPrev  Lista de Previência 
   * @param integer $iInstit     Instituição
   * @return array
   */  
  public function getRubricasEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
  	
    $sMsgErro = 'Consulta de empenhos da previdência abortada';
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\nTipo de folha não informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($sListaPrev) == '' ) {
      throw new ParameterException("{$sMsgErro}\nprevidência não informada!");
    }        
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    try {
      $aSiglas = $this->getSiglasFGTS($sTipo);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
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
      $aEmpenhosFolha = db_utils::getCollectionByRecord($rsEmpenhosFolha);
    } else {
      $aEmpenhosFolha = array();
    }

    return $aEmpenhosFolha;
    
  }
  
  
  /**
   * Exclui os dados para empenhos da folha referente a Previência ( rubricas = R992 )
   *
   * @param string  $sTipo       Tipo de Folha ( m = Mensal ou d = 13º )
   * @param integer $iAnoUsu     Exercício da Folha
   * @param integer $iMesUsu     Mês da Folha
   * @param string  $sListaPrev  Lista de Previência 
   * @param integer $iInstit     Instituição
   * @return array
   */    
  public function excluiDadosEmpenhosPrev($sTipo='',$iAnoUsu='',$iMesUsu='',$sListaPrev='',$iInstit=''){
    
    $sMsgErro = 'Exclusão de empenhos da previdência abortada';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }
    
    if ( trim($sTipo) == '' ) {
      throw new ParameterException("{$sMsgErro}\ntipo de folha não informado!");
    }
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($sListaPrev) == '' ) {
      throw new ParameterException("{$sMsgErro}\nprevidência não informada!");
    }            
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }      
    
    $oDaorhEmpenhoFolha        = db_utils::getDao('rhempenhofolha');
    $oDaorhEmpenhoFolhaRubrica = db_utils::getDao('rhempenhofolharubrica');
    
    try {
      $aDadosEmpenhos = $this->getRubricasEmpenhosPrev($sTipo,$iAnoUsu,$iMesUsu,$sListaPrev,$iInstit);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }

    if ( count($aDadosEmpenhos) > 0  ) {
       
      $aListaEmpenhos = array();
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $aListaEmpenhos[] = $oEmpenhoFolha->rh72_sequencial;
        if ( trim($oEmpenhoFolha->rh76_sequencial) != '' ) {
          throw new BusinessException("{$sMsgErro}\n\nRegistros já empenhados!");
        }
      }
      
      $aListaEmpenhos = array_unique($aListaEmpenhos);
      $sListaEmpenhos = implode(',',$aListaEmpenhos);
      
      $sSqlExcluiEmpenhosRub  = " delete from rhempenhofolharhemprubrica            ";
      $sSqlExcluiEmpenhosRub .= "  where rh81_rhempenhofolha in ({$sListaEmpenhos}) ";
      $rsExcluiEmpenhosRub    = db_query($sSqlExcluiEmpenhosRub);

      if ( !$rsExcluiEmpenhosRub ) {
        throw new DBException("{$sMsgErro}\n\n".pg_last_error());
      }      
      
      foreach ( $aDadosEmpenhos as $iInd => $oEmpenhoFolha ) {
        $oDaorhEmpenhoFolhaRubrica->excluir($oEmpenhoFolha->rh73_sequencial);
        if ( $oDaorhEmpenhoFolhaRubrica->erro_status == "0") {
          throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolhaRubrica->erro_msg}");
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
            throw new DBException("{$sMsgErro}\n\n{$oDaorhEmpenhoFolha->erro_msg}");
          }
        }
      }
    }    
    
  }  

  
  /**
   * Verifica se os dados gerados já estão liberados
   *
   * @param string  $sSigla    Tipo de Folha
   * @param string  $sTipoEmp  Tipo de Empenho ( Salário, Previdência ou FGTS )  
   * @param integer $iAnoUsu   Exercício da Folha 
   * @param integer $iMesUsu   Mês da Folha
   * @param string  $sSemestre Semestre ( Caso seja folha complementar ) 
   * @return boolean
   */
  public function isLiberada($sSigla='',$sTipoEmp='',$iAnoUsu='',$iMesUsu='',$sSemestre='', $sListaTabPrev = "") {
  	
  	$sMsgErro = " Consulta de liberação abortada";
  	
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
    }
    if ( trim($sTipoEmp) == '' ) {
      throw new ParameterException("{$sMsgErro}\ntipo não informado!");
    }  
    if ( trim($iAnoUsu) == '' ) {
      $iAnoUsu = db_anofolha();
    }   
    if ( trim($iMesUsu) == '' ) {
      $iAnoUsu = db_mesfolha();
    }
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }  	
  	
    /*
     * Caso exista resgistros na tabela rhempenhofolhaconfirma então está liberado 
     */
  	$oDaorhEmpenhoFolhaConfirma = db_utils::getDao('rhempenhofolhaconfirma');

		$sWhereLiberacao  = "     rh83_anousu       = {$iAnoUsu}   ";
		$sWhereLiberacao .= " and rh83_mesusu       = {$iMesUsu}   ";
		$sWhereLiberacao .= " and rh83_siglaarq     = '{$sSigla}'  ";
		if ($sSigla == 'r20') {
		  $sWhereLiberacao .= " and rh83_complementar in ({$sSemestre})";
		} else {
		  $sWhereLiberacao .= " and rh83_complementar = {$sSemestre} ";
		}
		$sWhereLiberacao .= " and rh83_tipoempenho  = {$sTipoEmp}  ";
		
		if (!empty($sListaTabPrev) && $sTipoEmp == 2) { 
		  $sWhereLiberacao .= " and rh83_tabprev in ({$sListaTabPrev})";
		}
		
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
   * Consulta a dotação apartir do estrutural passado por parâmetro
   *
   * @param integer $iOrgao
   * @param integer $iUnidade
   * @param integer $iProjAtiv
   * @param integer $iRecurso
   * @param integer $iElemento
   * @param integer $iAnoUsu
   * @param null $iFuncao
   * @param null $iSubFuncao
   * @param null $iPrograma
   * @return null
   * @throws ParameterException
   */
  function getDotacaoByFiltro ($iOrgao, $iUnidade, $iProjAtiv, $iRecurso, $iElemento, $iAnoUsu, $iFuncao = null, $iSubFuncao = null, $iPrograma = null) {
    
    
    $oDaoOrcParametro = db_utils::getDao('orcparametro');
    $oDaoOrcElemento  = db_utils::getDao('orcelemento');
    $sSqlParametro    = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
    $rsParametro      = $oDaoOrcParametro->sql_record($sSqlParametro);
       
    if ( $oDaoOrcParametro->numrows > 0 ) {
      $oParametro = db_utils::fieldsMemory($rsParametro,0);
    } else { 
      throw new ParameterException("Configure os parâmetros do orçamento para o ano {$iAnoUsu}!");
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
   * @param string  $sSigla    Tipo de Folha ( Ex: Salário, Férias, Complementar, etc. ) 
   * @param integer $iAnoUsu   Exercício da folha
   * @param integer $iMesUsu   Mês da Folha
   * @param integer $iInstit   instituição
   * @param string  $sSemestre Semestre ( Caso seja folha complementar )  
   * @return array Lista de Planilhas Geradas
   */
  public function geraPlanilhaGeral( $sSigla='', $iAnoUsu='', $iMesUsu='', $iInstit='', $iCgm='', $sSemestre='' ){
  	
     /**
     * Valida Credor
     */
    if(isset($iCgm) && $iCgm != "") {

      require_once("classes/db_cgm_classe.php");
      $cldb_cgm = new cl_cgm;
      $rsCredor = $cldb_cgm->sql_record($cldb_cgm->sql_query_file($iCgm,'*',null,""));

      if ($cldb_cgm->numrows == 0) {
        throw new Exception("Credor Inexistente.");
      }
    }

    $sMsgErro = 'Geração da planilha dos empenhos abortada.';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }

    require_once("libs/db_sql.php");
    
    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }  	
    
    $aPlanilha               = array();
    $lGeraRetencao           = '';
    
    /**
     * Verificamos se o parâmetro rh11_geraretencaoempenho é true ou false
     * true  : será gerada planilha por SLIP
     * false : será gerada planilha para todos os registros de retenções dos empenhos da folha 
     */
    
    $oDaoCfpess    = db_utils::getDao("cfpess");
    $sSqlParam     = $oDaoCfpess->sql_query_file($iAnoUsu, $iMesUsu, db_getsession("DB_instit"), "r11_geraretencaoempenho");
    $rsParam       = $oDaoCfpess->sql_record($sSqlParam);
        
    if ($rsParam){
      $lGeraRetencao = db_utils::fieldsMemory($rsParam,0)->r11_geraretencaoempenho;
    }

    
    //será gerada planilha por SLIP
    if ( $lGeraRetencao == 't') {    
      
       require_once("classes/db_rhslipfolha_classe.php");
       $clrhSlipFolha = new cl_rhslipfolha();
       
       require_once("model/slipFolha.model.php");
       $oSlipFolha = new slipFolha();
       
	     $sWhereSlip   = "     rh79_siglaarq    = '{$sSigla}'             "; 
	     $sWhereSlip  .= " and rh79_anousu      = {$iAnoUsu}              ";
	     $sWhereSlip  .= " and rh79_mesusu      = {$iMesUsu}              ";
	     $sWhereSlip  .= " and rh73_instit      = ".db_getsession('DB_instit');
	     $sWhereSlip  .= " and rh73_tiporubrica = 3                       ";
	     $sWhereSlip  .= " and rh79_seqcompl = {$sSemestre}               ";
	     $sWhereSlip  .= " and rh82_sequencial is not null                ";
	     
	     $rsSlips        = $clrhSlipFolha->sql_record($clrhSlipFolha->sql_query_slip(null,"k17_codigo",null,$sWhereSlip));
	     $aObjListaSlips = db_utils::getCollectionByRecord($rsSlips);
	     
	     if ( $clrhSlipFolha->numrows > 0 ) {
	     	
	     	foreach ($aObjListaSlips as $oSlip ) {
	     		$aListaSlip[] = $oSlip->k17_codigo;
	     	}
	     	
	     	try {
	     	  $iPlanilhaSlip = $oSlipFolha->geraPlanilhaSlip(implode(",",$aListaSlip), $iCgm);
	     	} catch ( DBException $eException ){
          throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( BusinessException $eException ){
          throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( ParameterException $eException ){
          throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( FileException $eException ){
          throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	      } catch ( Exception $eException ){
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	      }
	     	
	       if ( trim($iPlanilhaSlip) != '' ) {
           $aPlanilha[] = $iPlanilhaSlip;
         }
         
	     }
	  
	  //será gerada planilha para todos os registros de retenções dos empenhos da folha
    } else {
      
	     	try {
	     	  $iPlanilhaEmpenho = $this->geraPlanilhaEmpenhoFolha($sSigla, $iAnoUsu, $iMesUsu, $sSemestre, $iCgm, db_getsession("DB_instit"));
	     	} catch ( DBException $eException ){
          throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( BusinessException $eException ){
          throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( ParameterException $eException ){
          throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	      } catch ( FileException $eException ){
          throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	      } catch ( Exception $eException ){
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	      }
	     	
	      if ( trim($iPlanilhaEmpenho) != '' ) {
          $aPlanilha[] = $iPlanilhaEmpenho;
        }
         
    }
    
	  try {
	  	$iPlanilhaDevolucao = $this->geraPlanilhaDevolucao($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre);
	  } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	  }
	  
	  if ( trim($iPlanilhaDevolucao) != '' ) {
	    $aPlanilha[] = $iPlanilhaDevolucao;
	  }

	  return $aPlanilha;

  }
  
  
  /**
   * Gera planilha de devolução apartir dos dados para empenho 
   * @param string  $sSigla     Tipo de Folha  
   * @param integer $iAnoUsu    Exercício da folha
   * @param integer $iMesUsu    Mês da Folha
   * @param integer $iInstit    instituição
   * @param string  $sSemestre  Semestre ( Caso seja folha complementar )  
   * @return integer Código da Planilhas Geradas
   */  
  public function geraPlanilhaDevolucao( $sSigla='',$iAnoUsu='',$iMesUsu='',$iInstit='',$sSemestre='' ){
    
    $sMsgErro     = 'Geração das Planilhas de Devolução abortada.';
    $iCodPlanilha = '';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }     

    if ( trim($sSigla) == '' ) {
      throw new ParameterException("{$sMsgErro}\nsigla não informada!");
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
    if ( trim($sSemestre) == '' ) {
      $sSemestre = '0';
    }   
    
    $oDaoPlaCaixa     = db_utils::getDao('placaixa');
    $oDaoPlaCaixaRec  = db_utils::getDao('placaixarec');
    
    try {
      $sSqlRubricasDevolucao = $this->getRubricasDevolucaoFolha($sSigla,$iAnoUsu,$iMesUsu,$iInstit,$sSemestre,null,true);
    } catch ( DBException $eException ){
      throw new DBException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( BusinessException $eException ){
      throw new BusinessException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( ParameterException $eException ){
      throw new ParameterException("{$sMsgErro}\n{$eException->getMessage()}");
	  } catch ( FileException $eException ){
      throw new FileException("{$sMsgErro}\n{$eException->getMessage()}");  
	  } catch ( Exception $eException ){
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
        throw new DBException($sMsgErro."\n".$oDaoPlaCaixa->erro_msg);
      }       
      
      $iCodPlanilha = $oDaoPlaCaixa->k80_codpla;

      for ( $iInd=0; $iInd < $iLinhasDevolucao; $iInd++ ) {
        
      	$oDevolucao = db_utils::fieldsMemory($rsDevolucao,$iInd);

        if (empty($oDevolucao->contacredito)) {
          throw new BusinessException("O recurso {$oDevolucao->recurso} não tem nenhuma conta vinculada!");
        }

        /**
         * Buscamos se a conta credito possui uma conta extra-orçamentaria vinculada
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
          throw new DBException($sMsgErro."\n".$oDaoPlaCaixaRec->erro_msg);
        }
               
	    }
    }

    return $iCodPlanilha; 
    
  }

  public function geraPlanilhaEmpenhoFolha( $sSigla, $iAnoUsu, $iMesUsu, $sSemestre, $iCgm='', $iInstit) {

    $sMsgErro     = 'Geração das Planilhas para as retenções de Empenho abortada';
    $sCodPlanilha = '';
    
    if ( !db_utils::inTransaction() ){
      throw new DBException("{$sMsgErro}\nnenhuma transação encontrada!");
    }

  	if ( trim($iInstit) == '' ) {
  		$iInstit = db_getsession('DB_instit');
  	}
  	
  	$oDaoRhEmpenhoFolhaRubricaPlanilha = db_utils::getDao('rhempenhofolharubricaplanilha');
    $oDaoRhEmpenhoFolhaRubrica         = db_utils::getDao('rhempenhofolharubrica');
    $oDaoPlaCaixa                      = db_utils::getDao('placaixa');
    $oDaoPlaCaixaRec                   = db_utils::getDao('placaixarec');
    
    
    $sCampoRubricas  = " distinct on (rubric,recurso,sequencial)           ";
    $sCampoRubricas .= "          rh73_sequencial      as sequencial,      ";
    $sCampoRubricas .= "          rh73_rubric          as rubric,          ";
    $sCampoRubricas .= "          case                                     ";
    $sCampoRubricas .= "            when rh72_recurso is null              ";
    $sCampoRubricas .= "              then rh79_recurso                    ";
    $sCampoRubricas .= "            else rh72_recurso                      ";
    $sCampoRubricas .= "          end as recurso,                          ";
    $sCampoRubricas .= "          case                                     ";
    $sCampoRubricas .= "            when rh72_concarpeculiar is null       ";
    $sCampoRubricas .= "              then rh79_concarpeculiar             ";
    $sCampoRubricas .= "            else rh72_concarpeculiar               ";
    $sCampoRubricas .= "          end as caracteristicapeculiar,           ";
    $sCampoRubricas .= "          rh78_retencaotiporec as retencao,        ";
    $sCampoRubricas .= "          e21_receita          as receita,         ";
    $sCampoRubricas .= "          case                                     ";
    $sCampoRubricas .= "            when conta_empenho.rh41_codigo is null "; 
    $sCampoRubricas .= "              then conta_slip.rh41_conta           ";
    $sCampoRubricas .= "            else conta_empenho.rh41_conta          ";  
    $sCampoRubricas .= "          end as contacredito,                     ";
    $sCampoRubricas .= "          e48_cgm              as numcgm,          ";
    $sCampoRubricas .= "          rh73_valor           as valor            ";
    
    $sWhereRubricas  = " rh73_tiporubrica = 2            ";
    $sWhereRubricas .= " and ( rh72_siglaarq = '{$sSigla}' or rh79_siglaarq = '{$sSigla}' ) "; 
	  $sWhereRubricas .= " and ( ( rh72_anousu = {$iAnoUsu} and rh72_mesusu = {$iMesUsu} ) or ( rh79_anousu = {$iAnoUsu} and rh79_mesusu = {$iMesUsu} ) )  ";
	  $sWhereRubricas .= " and rh73_instit = {$iInstit}   ";
	  $sWhereRubricas .= " and ( rh72_seqcompl = {$sSemestre} or rh79_seqcompl = {$sSemestre} )";
	  $sWhereRubricas .= " and rh111_sequencial is null        ";
    
    $sSqlSubRubricas = $oDaoRhEmpenhoFolhaRubrica->sql_query_rhempenhofolharubricas(null,$sCampoRubricas,null,$sWhereRubricas);

    $sSqlRubricas  = " select rubric,                                                               ";
		$sSqlRubricas .= "        recurso,                                                              ";
		$sSqlRubricas .= "        retencao,                                                             ";
		$sSqlRubricas .= "        receita,                                                              ";
		$sSqlRubricas .= "        contacredito,                                                         ";
		$sSqlRubricas .= "        caracteristicapeculiar,                                               ";
		$sSqlRubricas .= "        case                                                                  ";
		$sSqlRubricas .= "          when numcgm is null                                                 ";
		$sSqlRubricas .= "            then (select numcgm                                               ";
		$sSqlRubricas .= "                    from db_config                                            ";
		$sSqlRubricas .= "                   where codigo = ".db_getsession('DB_instit').")             "; 
		$sSqlRubricas .= "          else numcgm                                                         ";
		$sSqlRubricas .= "        end as numcgm,                                                        ";
		$sSqlRubricas .= "        round(sum(valor),2) as valor,                                         ";
		$sSqlRubricas .= "        array_to_string(array_accum( distinct sequencial),',') as rhempenhofolharubrica ";
		$sSqlRubricas .= "   from ( {$sSqlSubRubricas} ) as x                                           ";
    $sSqlRubricas .= " group by rubric,                                                             ";
    $sSqlRubricas .= "          retencao,                                                           ";
    $sSqlRubricas .= "          receita,                                                            ";
    $sSqlRubricas .= "          contacredito,                                                       ";
		$sSqlRubricas .= "          caracteristicapeculiar,                                             ";
    $sSqlRubricas .= "          numcgm,                                                             ";
    $sSqlRubricas .= "          recurso                                                             ";
    $sSqlRubricas .= "   having sum(valor) > 0                                                      ";    
    $sSqlRubricas .= " order by rubric,                                                             ";
    $sSqlRubricas .= "          recurso                                                             ";
    $rsRubricas      = $oDaoRhEmpenhoFolhaRubrica->sql_record($sSqlRubricas);    
    $iNroRubricas    = $oDaoRhEmpenhoFolhaRubrica->numrows;
    
    if ( $iNroRubricas > 0 ) {
      
      $oDaoPlaCaixa->k80_data   = date('Y-m-d',db_getsession("DB_datausu"));  
      $oDaoPlaCaixa->k80_instit = $iInstit;
      $oDaoPlaCaixa->incluir(null);
      
      if ( $oDaoPlaCaixa->erro_status == '0' ) {
        throw new DBException($sMsgErro."\n1. ".$oDaoPlaCaixa->erro_msg);
      }       
      
      $sCodPlanilha = $oDaoPlaCaixa->k80_codpla;
      $oDaoTabRec   = db_utils::getDao("tabplan");       
      for ( $iInd=0; $iInd < $iNroRubricas; $iInd++ ) { 
      
      	$oDadosRubricas      = db_utils::fieldsMemory($rsRubricas,$iInd);
      	
        /**
         * Buscamos se a conta credito possui uma conta extra-orçamentaria vinculada
         * caso possua, devemos usar essa conta como conta pagadora apenas se a receita for extra-orçamentaria
         */
      	$sSqlExtra        = $oDaoTabRec->sql_query($oDadosRubricas->receita);
      	$rsReceitaExtra   = $oDaoTabRec->sql_record($sSqlExtra);
      	if ($oDaoTabRec->numrows > 0) {

          if (empty($oDadosRubricas->contacredito)) {
            throw new BusinessException("O recurso {$oDadosRubricas->recurso} não tem nenhuma conta vinculada!");
          }

      	  $oDaoSaltesExtra  = db_utils::getDao("saltesextra");
          $sSqlContaextra   = $oDaoSaltesExtra->sql_query_extra(null,
                                                              "k109_contaextra",
                                                               null,
                                                              "k109_saltes = {$oDadosRubricas->contacredito}");

          $rsContaExtra = $oDaoSaltesExtra->sql_record($sSqlContaextra);

          if ($oDaoSaltesExtra->numrows > 0) {
            $oDadosRubricas->contacredito = db_utils::fieldsmemory($rsContaExtra, 0)->k109_contaextra;
          }
      	}
	      $oDaoPlaCaixaRec->k81_codpla     = $sCodPlanilha;
	      $oDaoPlaCaixaRec->k81_conta      = $oDadosRubricas->contacredito; 
	      $oDaoPlaCaixaRec->k81_receita    = $oDadosRubricas->receita;
	      $oDaoPlaCaixaRec->k81_valor      = round($oDadosRubricas->valor,2);
	      $oDaoPlaCaixaRec->k81_codigo     = $oDadosRubricas->recurso;
        if ($iCgm !='') {
          $oDaoPlaCaixaRec->k81_numcgm     = $iCgm;
        } else {
	        $oDaoPlaCaixaRec->k81_numcgm     = $oDadosRubricas->numcgm;
        }
	      $oDaoPlaCaixaRec->k81_datareceb  = date('Y-m-d',db_getsession("DB_datausu"));
	      $oDaoPlaCaixaRec->k81_origem     = 1;
	      $oDaoPlaCaixaRec->k81_obs        = '';
        $oDaoPlaCaixaRec->k81_concarpeculiar = $oDadosRubricas->caracteristicapeculiar; 
	      $oDaoPlaCaixaRec->incluir(null); 
	  
	      if ( $oDaoPlaCaixaRec->erro_status == '0' ) {
	        throw new DBException($sMsgErro."\n2. ".$oDaoPlaCaixaRec->erro_msg);
	      }
    
	      $aRhEmpenhoFolhaRubrica = explode(",",$oDadosRubricas->rhempenhofolharubrica);
	      for ($iEmpenhoFolhaRubrica = 0; $iEmpenhoFolhaRubrica < count($aRhEmpenhoFolhaRubrica); $iEmpenhoFolhaRubrica++) {
	        
	        $oDaoRhEmpenhoFolhaRubricaPlanilha->rh111_rhempenhofolharubrica = $aRhEmpenhoFolhaRubrica[$iEmpenhoFolhaRubrica];
	        $oDaoRhEmpenhoFolhaRubricaPlanilha->rh111_placaixarec           = $oDaoPlaCaixaRec->k81_seqpla;
	        $oDaoRhEmpenhoFolhaRubricaPlanilha->incluir(null);
	        if($oDaoRhEmpenhoFolhaRubricaPlanilha->erro_status == "0") {
	          throw new DBException($sMsgErro."\n3. ".$oDaoRhEmpenhoFolhaRubricaPlanilha->erro_msg);
	        }
	        
	      }  
	      
      }
      
    } else {
      throw new BusinessException("Não foram encontrados dados para gerar a planilha\nEmpenhos não processados ou já possui Planilha gerada!");
    }

  	return $sCodPlanilha;
  }
  
  /**
   * Verifica se a sigla da folha de pagamento tem a permissão 
   * de utilizar a estrutura da suplementar na geração do empenho.
   * 
   * @static
   * @access public
   * @param String $sSigla
   * @return Boolean
   */
  public static function verificarPermissaoFolha($sSigla) {
    
    $aFolhasLiberadas = array("r14", "r48", "sup");
    
    if (in_array($sSigla, $aFolhasLiberadas)) {
      return true;
    }
    
    return false;
  }
  
}