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
 * Classe em refatoracao
 * @author matheus felini <matheus.felini@dbseller.com.br>
 */
class RefactorProvisaoFerias {
	
  /**
   * Busca os dados disponiveis para a provisao de ferias
   * @param  string $lProcessado  true para processando false para estorno
   * @param  string $sTipoProvisao
   * @throws BusinessException
   * @throws Exception
   * @return stdClass
   */
  static function getCompentenciaDisponivelEscrituracao($lProcessado = true, $sTipoProvisao, $iAno, $iInstituicao) {
  	
  	/**
  	 *  Array usada para exibi��o de erro na tela, em caso de erro
  	 *  n�mero m�s => m�s extenso
  	 */
  	$aMeses = array(1  => "Janeiro",
  			            2  => "Fevereiro",
  			            3  => "Mar�o",
  			            4  => "Abril",
  			            5  => "Maio",
  			            6  => "Junho",
  			            7  => "Julho",
  			            8  => "Agosto",
  			            9  => "Setembro",
  			            10 => "Outubro",
  			            11 => "Novembro",
  			            12 => "Dezembro");  	
  	
    $aClasse = array("provisaoferias"         => "gerfprovfer",
                     "provisaodecimoterceiro" => "gerfprovs13");
    
    $aSiglas = array("provisaoferias"         => "r93",
                     "provisaodecimoterceiro" => "r94");

    $sClasse = $aClasse[$sTipoProvisao];
    $sSigla  = $aSiglas[$sTipoProvisao];
    
    $lFerias 		   = $sTipoProvisao == 'provisaoferias' ? true : false;
    $iTipoProvisao = $sTipoProvisao == 'provisaoferias' ? 2: 1;
    
    /**
     * Busca pelo �ltimo processamento da provisao
     */
    $oDaoEscrituraProvisao  = db_utils::getDao('escrituraprovisao');
    $sWhereImplantacao      = "     c102_instit = {$iInstituicao}";
    $sWhereImplantacao     .= " and c102_processado is true ";
    $sWhereImplantacao     .= " and c102_tipoprovisao = {$iTipoProvisao}";
    $sOrderBy               = " c102_ano desc, c102_mes desc limit 1";
    
    $sSqlBuscaEscrituraProvisao = $oDaoEscrituraProvisao->sql_query_file(null, "*", $sOrderBy, $sWhereImplantacao);
    $rsBuscaEscrituraProvisao   = $oDaoEscrituraProvisao->sql_record($sSqlBuscaEscrituraProvisao);
    
    /**
     *  Seta os valores do retorno
     */
    $oDadosCompetencia  			 = new stdClass();
    $oDadosCompetencia->dtData = db_getsession("DB_datausu");
    
    /**
     * Caso em que:
     * N�o h� lan�amentos para ser estornado
     */
    if ( $oDaoEscrituraProvisao->numrows == 0 && !$lProcessado ) {
    	throw new Exception("N�o h� escritura��o processada.");
    }

    /**
     * Caso em que:
     * N�o existe lan�amentos
     * - deve fazer lan�amento para o primeiro m�s que possui c�lculo de provis�o
     */
    if ( $lProcessado && $oDaoEscrituraProvisao->numrows == 0 ) {

    	$oDadosCompetencia->iMes = self::getPrimeiraCompetenciaCalculoFolha($iAno, $iInstituicao, $lFerias);
    	$oDadosCompetencia->iAno = $iAno;
    	
    } else {
    
    	/**
    	 * Sen�o: 
       * - Processando: deve pegar o m�s seguinte ao ultimo mes lan�ado
       * - Estornando : deve pagar o ultimo lancamento para estornalo 
    	 */
    	
	    $oDaoDadosUltimaEscrituracao = db_utils::fieldsMemory($rsBuscaEscrituraProvisao, 0);
	    $oDadosCompetencia->iMes     = $oDaoDadosUltimaEscrituracao->c102_mes;   
	    $oDadosCompetencia->iAno     = $oDaoDadosUltimaEscrituracao->c102_ano;     
	    
	    /**
	     * Se � um processamento, deve pegar o m�s posterior ao �ltimo contabilizado
	     */
	    if ( $lProcessado ) {
	    	
	    	/**
	    	 * Casos de erro:
	    	 * Ser� poss�vel apenas lan�ar e estonar no ano da sess�o	  
	    	 */
	    	$sMensagemErro = "Existem processamentos para {$aMeses[ (int) $oDadosCompetencia->iMes]}/{$oDadosCompetencia->iAno}\n";
	    	 
	    	/**
  	     * esse if ser� alcan�ado somente no caso de haver lan�amentos para anos posteriores ao ano da sess�o
	    	 */
	    	if ($oDaoDadosUltimaEscrituracao->c102_ano > $iAno) {

	    		$sMensagemErro .= "Para processar � necess�rio estornar este lan�amento.";
	    		throw new Exception ($sMensagemErro);
	    	}
	    	 
	    	/**
	    	 * esse if ser� alcan�ado somente no caso de haver lan�amentos para anos anteriores ao ano da sess�o 
	    	 */
	    	if ($oDaoDadosUltimaEscrituracao->c102_ano < $iAno && $oDadosCompetencia->iMes != 12 ) {

	    		$sMensagemErro .= "Para processar � necess�rio realizar todos os processamentos do ano anterior.";
	    		throw new Exception ($sMensagemErro);
	    	}
	    	 
	    	/**
	    	 * Em caso o ano anterior tenha feito todos os calculos, o ultimo mes sera 12, deve trocar o ano
	    	 * Senao pega o o proximo mes do ultimo calculo
	    	 */
	    	if ($oDadosCompetencia->iMes == 12) {
	    		
	    		$oDadosCompetencia->iMes = 1;
	    		$oDadosCompetencia->iAno++;
	    		
	    		if ($oDadosCompetencia->iAno != $iAno) {
	    			throw new Exception ("J� foram processados todos os meses do ano de {$iAno}.");
	    		}
	    		
	    	} else {
	    		$oDadosCompetencia->iMes++;
	    	}
	    }
	    
	    /**
	     * N�o pode haver lancamento para um ano diferente da sessao
	     */
	    if ( $oDadosCompetencia->iAno != $iAno ) {
	    	throw new Exception ("N�o � poss�vel processar um ano diferente do atual.");
	    }
	
    }
    
    /**
     * Em caso de processamento e nao existir calculo de provisao
     */
    if ( $lProcessado && !self::existeProcessamentoProvisao( $lFerias, $oDadosCompetencia->iAno,$oDadosCompetencia->iMes, $iInstituicao) ) {
    	throw new Exception ("N�o existem c�lculo de provis�o da folha para o m�s de {$aMeses[ (int) $oDadosCompetencia->iMes]}");
    }
    
    return $oDadosCompetencia;
  }  

  /**
   * Verifica se existe processamento de provisao
   * 
   * @param bool $lFerias
   * @param integer $iAno
   * @param integer $iMes
   * @param integer $iInstituicao
   * 
   * @return boolean
   */
  public static function existeProcessamentoProvisao ($lFerias, $iAno, $iMes, $iInstituicao) {
  	
  	$sTabela         = "gerfprovs13";
  	$sSigla          = "r94";
  	$iTipoLancamento = 1;
  	
  	/**
  	 * Em caso de provis�o de f�rias buscar na tabela gerfprovfer
  	 */
  	if ( $lFerias ) {
  		 
  		$sTabela         = "gerfprovfer";
  		$sSigla          = "r93";
  		$iTipoLancamento = 2;
  	}
  	
  	$oDaoProvisao = db_utils::getDao($sTabela);
  	
  	$sWhere     = "  {$sSigla}_mesusu = {$iMes}             ";
  	$sWhere    .= "   AND {$sSigla}_anousu = {$iAno}        ";
  	$sWhere    .= "   AND {$sSigla}_instit = {$iInstituicao}";
  	
  	if ($lFerias) {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, null, "*", null, $sWhere);
  	} else {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, "*", null, $sWhere);
  	}

  	$rsProvisao = $oDaoProvisao->sql_record($sSql);  	
  	
  	if ( $oDaoProvisao->numrows == 0 ) {
  		return false;
  	}
  	
  	return true;
  }
  
  /**
   * Retorna o mes da primeiro calculo da folha para provisao de ferias/13� salario para o ano da sessao 
   * Esse m�todo � usado quando ainda n�o h� nenhuma escritura��o da provis�o no ano 
   * 
   * @param integer $iAno
   * @param integer $iInstituicao
   * @param bool $lFerias
   * @throws Exception
   */
  public static function getPrimeiraCompetenciaCalculoFolha($iAno, $iInstituicao, $lFerias = false) {
  	
  	$sTabela         = "gerfprovs13";
  	$sSigla          = "r94";
  	$iTipoLancamento = 1;
  	 
  	/**
  	 * Em caso de provis�o de f�rias buscar na tabela gerfprovfer
  	 */
  	if ( $lFerias ) {
  			
  		$sTabela         = "gerfprovfer";
  		$sSigla          = "r93";
  		$iTipoLancamento = 2;
  	}
  	 
  	$oDaoProvisao = db_utils::getDao($sTabela);
  	 
  	$sWhere  = "     {$sSigla}_anousu = {$iAno}         ";
  	$sWhere .= " AND {$sSigla}_instit = {$iInstituicao} ";
  	$sCampos = " {$sSigla}_mesusu as mesusu             ";
  	$sOrder  = "{$sSigla}_mesusu asc limit 1";
  	
  	/**
  	 * DAO de provis�o de f�rias (gerfprovfer) possui o metodo sql_query_file com diferente passagem de parametros
  	 */
  	if ($lFerias) {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, null, $sCampos, $sOrder, $sWhere);
  	} else {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, $sCampos, $sOrder, $sWhere);
  	}
  	
  	$rsProvisao = $oDaoProvisao->sql_record($sSql);
  	 
  	/**
  	 * Caso n�o tenha encontrado nenhum c�lculo de folha para o ano atual, deve acusar erro
  	 */
  	if ( $oDaoProvisao->numrows == 0 ) {
  		throw new Exception("N�o existe calculo de provis�o na folha para o ano atual.");
  	} 	
  	
  	$oProvisao = db_utils::fieldsMemory($rsProvisao, 0);
  	
  	return $oProvisao->mesusu;  	
  }
    
}