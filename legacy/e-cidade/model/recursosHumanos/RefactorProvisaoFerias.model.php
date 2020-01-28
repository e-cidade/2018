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
  	 *  Array usada para exibição de erro na tela, em caso de erro
  	 *  número mês => mês extenso
  	 */
  	$aMeses = array(1  => "Janeiro",
  			            2  => "Fevereiro",
  			            3  => "Março",
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
     * Busca pelo último processamento da provisao
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
     * Não há lançamentos para ser estornado
     */
    if ( $oDaoEscrituraProvisao->numrows == 0 && !$lProcessado ) {
    	throw new Exception("Não há escrituração processada.");
    }

    /**
     * Caso em que:
     * Não existe lançamentos
     * - deve fazer lançamento para o primeiro mês que possui cálculo de provisão
     */
    if ( $lProcessado && $oDaoEscrituraProvisao->numrows == 0 ) {

    	$oDadosCompetencia->iMes = self::getPrimeiraCompetenciaCalculoFolha($iAno, $iInstituicao, $lFerias);
    	$oDadosCompetencia->iAno = $iAno;
    	
    } else {
    
    	/**
    	 * Senão: 
       * - Processando: deve pegar o mês seguinte ao ultimo mes lançado
       * - Estornando : deve pagar o ultimo lancamento para estornalo 
    	 */
    	
	    $oDaoDadosUltimaEscrituracao = db_utils::fieldsMemory($rsBuscaEscrituraProvisao, 0);
	    $oDadosCompetencia->iMes     = $oDaoDadosUltimaEscrituracao->c102_mes;   
	    $oDadosCompetencia->iAno     = $oDaoDadosUltimaEscrituracao->c102_ano;     
	    
	    /**
	     * Se é um processamento, deve pegar o mês posterior ao último contabilizado
	     */
	    if ( $lProcessado ) {
	    	
	    	/**
	    	 * Casos de erro:
	    	 * Será possível apenas lançar e estonar no ano da sessão	  
	    	 */
	    	$sMensagemErro = "Existem processamentos para {$aMeses[ (int) $oDadosCompetencia->iMes]}/{$oDadosCompetencia->iAno}\n";
	    	 
	    	/**
  	     * esse if será alcançado somente no caso de haver lançamentos para anos posteriores ao ano da sessão
	    	 */
	    	if ($oDaoDadosUltimaEscrituracao->c102_ano > $iAno) {

	    		$sMensagemErro .= "Para processar é necessário estornar este lançamento.";
	    		throw new Exception ($sMensagemErro);
	    	}
	    	 
	    	/**
	    	 * esse if será alcançado somente no caso de haver lançamentos para anos anteriores ao ano da sessão 
	    	 */
	    	if ($oDaoDadosUltimaEscrituracao->c102_ano < $iAno && $oDadosCompetencia->iMes != 12 ) {

	    		$sMensagemErro .= "Para processar é necessário realizar todos os processamentos do ano anterior.";
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
	    			throw new Exception ("Já foram processados todos os meses do ano de {$iAno}.");
	    		}
	    		
	    	} else {
	    		$oDadosCompetencia->iMes++;
	    	}
	    }
	    
	    /**
	     * Não pode haver lancamento para um ano diferente da sessao
	     */
	    if ( $oDadosCompetencia->iAno != $iAno ) {
	    	throw new Exception ("Não é possível processar um ano diferente do atual.");
	    }
	
    }
    
    /**
     * Em caso de processamento e nao existir calculo de provisao
     */
    if ( $lProcessado && !self::existeProcessamentoProvisao( $lFerias, $oDadosCompetencia->iAno,$oDadosCompetencia->iMes, $iInstituicao) ) {
    	throw new Exception ("Não existem cálculo de provisão da folha para o mês de {$aMeses[ (int) $oDadosCompetencia->iMes]}");
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
  	 * Em caso de provisão de férias buscar na tabela gerfprovfer
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
   * Retorna o mes da primeiro calculo da folha para provisao de ferias/13º salario para o ano da sessao 
   * Esse método é usado quando ainda não há nenhuma escrituração da provisão no ano 
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
  	 * Em caso de provisão de férias buscar na tabela gerfprovfer
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
  	 * DAO de provisão de férias (gerfprovfer) possui o metodo sql_query_file com diferente passagem de parametros
  	 */
  	if ($lFerias) {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, null, $sCampos, $sOrder, $sWhere);
  	} else {
  		$sSql = $oDaoProvisao->sql_query_file(null, null, null, null, $sCampos, $sOrder, $sWhere);
  	}
  	
  	$rsProvisao = $oDaoProvisao->sql_record($sSql);
  	 
  	/**
  	 * Caso não tenha encontrado nenhum cálculo de folha para o ano atual, deve acusar erro
  	 */
  	if ( $oDaoProvisao->numrows == 0 ) {
  		throw new Exception("Não existe calculo de provisão na folha para o ano atual.");
  	} 	
  	
  	$oProvisao = db_utils::fieldsMemory($rsProvisao, 0);
  	
  	return $oProvisao->mesusu;  	
  }
    
}