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

require_once("model/pessoal/Servidor.model.php");
require_once("model/pessoal/VinculoServidor.model.php");

/**
 * Repositorio para Servidores 
 * 
 * @abstract
 * @package Pessoal
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 */
abstract class ServidorRepository {

  /**
   * Array com instancias de servidores 
   * 
   * @static
   * @var Array
   * @access private
   */
  static private $aInstanciasServidores = array();

  /**
   * Adiciona uma rubrica ao array de servidores
   *
   * @static
   * @param Servidor $oServidor
   * @access private 
   * @return void
   */
  private static function adicionar( Servidor $oServidor, $iAno, $iMes, $iInstituicao ) {
  	
    ServidorRepository::$aInstanciasServidores[ $oServidor->getMatricula() ][$iAno][$iMes][$iInstituicao] = $oServidor;
    return;
  }

  /**
   * Retorna instancia do servidor pela matricula e competencia
   * 
   * @static
   * @param integer $iMatricula - codigo da matricula
   * @access public
   * @return Servidor
   */
  public static function getInstanciaByCodigo($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao = null) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}

    /**
     * Se não tiver servidor no array de instancias, adiciona  
     */
    if ( empty(ServidorRepository::$aInstanciasServidores[$iMatricula][$iAnoFolha][$iMesFolha][$iInstituicao]) ) {
      ServidorRepository::adicionar( new Servidor($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao), $iAnoFolha, $iMesFolha , $iInstituicao);
    }

    return ServidorRepository::$aInstanciasServidores[$iMatricula][$iAnoFolha][$iMesFolha][$iInstituicao];
  }

  /**
   * Busca Servidores pela Lotacao
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed
   *   -- Quando passado apenas um valor busca apenas pelo código da Lotação
   *   -- Quando For passado um array, buscara apenas as Lotacoes Indicadas
   *   -- Quando for passado dois numeros inteiros, vai buscar o intervalo entre eles
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByLotacao($iAnoFolha, $iMesFolha, $iInstituicao = null) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  
    $aArgumentos           = func_get_args();
    $iQuantidadeArgumentos = func_num_args();
    $aServidores           = array();
    $sTipoBusca            = null;  
  
    /**
     * Iniciando Validação dos parametros passados
     */
    if ( $iQuantidadeArgumentos == 2 || $iQuantidadeArgumentos > 4 ) {
      throw new ParameterException("Parametros passados Incorretamente");
    }
  
    if ( $iQuantidadeArgumentos == 3 && is_array($aArgumentos[2]) ) {
  
      $sTipoBusca    = "SELECIONADOS";
      $aSelecionados = $aArgumentos[2];
      
    } elseif ( $iQuantidadeArgumentos == 3 && DBNumber::isInteger($aArgumentos[2]) ) {
  
      $sTipoBusca  = "CHAVE";
      $iChaveBusca = $aArgumentos[2];
      
    } elseif ( $iQuantidadeArgumentos == 4 ) {
  
      $iPrimeiroArgumento = $aArgumentos[2];
      $iSegundoArgumento  = $aArgumentos[3];
  
      if ( !DBNumber::isInteger($iPrimeiroArgumento) || !DBNumber::isInteger($iPrimeiroArgumento) ) {
        throw new ParameterException("Parametros devem ser inteiros");
      }
      
      $sTipoBusca = "INTERVALO";
      
    } else {
      throw new ParameterException("Tipo(s) de Parametro(s) passados são incorretos");
    }
  
    /**
     * Lógica do SQL Implementada
     */
    switch ( $sTipoBusca ) {
  
      case "SELECIONADOS":
        $sWhere = " rh02_lota in (". implode(", ", $aSelecionados) .")";
        break;
  
      case "CHAVE":
        $sWhere = " rh02_lota = $iChaveBusca ";
        break;
  
      case "INTERVALO":
        $sWhere = " rh02_lota between $iPrimeiroArgumento and $iSegundoArgumento ";
        break;
    }
  
    $sWhere          .= " and rh02_anousu = $iAnoFolha and rh02_mesusu = $iMesFolha and rh02_instit = $iInstituicao ";
    $oDaoRHPessoalMov = db_utils::getDao("rhpessoalmov");
    $sSqlServidores   = $oDaoRHPessoalMov->sql_query_file(null, null,"rh02_regist","rh02_regist", $sWhere);
  
    $rsServidores     = db_query($sSqlServidores);
  
    if ( !$rsServidores ) {
      throw new DBException("Erro ao Buscar sevidores pela lotação");
    }
  
    /**
     * Utilizado FOR por causa de Desempenho
     */
    for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
  
      $iMatriculaServidor = db_utils::fieldsMemory( $rsServidores, $iIndiceServidor )->rh02_regist;
      $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
    }
  
    return $aServidores;
  }
  
  /**
   * Busca Servidores pela Órgão
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed
   *   -- Quando passado apenas um valor busca apenas pelo código da Lotação
   *   -- Quando For passado um array, buscara apenas as Lotacoes Indicadas
   *   -- Quando for passado dois numeros inteiros, vai buscar o intervalo entre eles
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByOrgao($iAnoFolha, $iMesFolha, $iInstituicao = null) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  
    $aArgumentos           = func_get_args();
    $iQuantidadeArgumentos = func_num_args();
    $aServidores           = array();
    $sTipoBusca            = null;
  
    /**
     * Iniciando Validação dos parametros passados
     */
  
    if ( $iQuantidadeArgumentos == 2 || $iQuantidadeArgumentos > 4 ) {
      throw new ParameterException("Parametros passados Incorretamente");
    }
  
    if ( $iQuantidadeArgumentos == 3 && is_array($aArgumentos[2]) ) {
  
      $sTipoBusca         = "SELECIONADOS";
      $aSelecionados      = $aArgumentos[2];
    } elseif ( $iQuantidadeArgumentos == 3 && DBNumber::isInteger($aArgumentos[2]) ) {
  
      $sTipoBusca         = "CHAVE";
      $iChaveBusca        = $aArgumentos[2];
    } elseif ( $iQuantidadeArgumentos == 4 ) {
  
      $iPrimeiroArgumento = $aArgumentos[2];
      $iSegundoArgumento  = $aArgumentos[3];
  
      if ( !DBNumber::isInteger($iPrimeiroArgumento) || !DBNumber::isInteger($iPrimeiroArgumento) ) {
        throw new ParameterException("Parametros devem ser inteiros");
      }
      
      $sTipoBusca         = "INTERVALO";
      
    } else {
      throw new ParameterException("Tipo(s) de Parametro(s) passados são incorretos");
    }
  
    /**
     * Lógica do SQL Implementada
     */
    switch ( $sTipoBusca ) {
  
      case "SELECIONADOS":
        $sWhere = " rh26_orgao in (". implode(", ", $aSelecionados) .")";
        break;
  
      case "CHAVE":
        $sWhere = " rh26_orgao= $iChaveBusca ";
        break;
  
      case "INTERVALO":
        $sWhere = " rh26_orgao between $iPrimeiroArgumento and $iSegundoArgumento ";
        break;
    }
  
    $oDaoRHLotaExe    = db_utils::getDao("rhlotaexe");
    $sSqlServidores   = $oDaoRHLotaExe->sql_query_servidores($iAnoFolha, $iMesFolha, "rh02_regist", $sWhere, $iInstituicao);
    $rsServidores     = db_query($sSqlServidores);
  
    if ( !$rsServidores ) {
      throw new DBException("Erro ao Buscar sevidores pelo Órgão");
    }
  
    /**
     * Utilizado FOR por causa de Desempenho
     */
    for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
  
      $iMatriculaServidor = db_utils::fieldsMemory( $rsServidores, $iIndiceServidor )->rh02_regist;
      $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
    }
    
    return $aServidores;
  }
  
  /**
   * Busca Servidores pelo Regime
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByRegime($iAnoFolha, $iMesFolha, $iCodigoRegime, $iInstituicao = null ) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  
    $oDaoRHRegime     = db_utils::getDao("rhregime");
    $sSqlServidores   = $oDaoRHRegime->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoRegime, "rh02_regist", $iInstituicao);
    $rsServidores     = db_query($sSqlServidores);
    $aServidores      = array();
  
    if ( !$rsServidores ) {
      throw new DBException("Erro ao Buscar sevidores pelo Regime");
    }
  
    /**
     * Utilizado FOR por causa de Desempenho
     */
    for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
  
      $oDadosServidor     =  db_utils::fieldsMemory( $rsServidores, $iIndiceServidor );
      $iMatriculaServidor =  $oDadosServidor->rh02_regist;
      $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
    }
    
    return $aServidores;
  }  
  
  /**
   * Busca Servidores pelo LocalTrabalho
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByLocalTrabalho($iAnoFolha, $iMesFolha, $iCodigoLocalTrabalho, $iInstituicao = null ) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  
    $oDaoRHPesLocalTrab = db_utils::getDao("rhpeslocaltrab");
    $sSqlServidores     = $oDaoRHPesLocalTrab->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoLocalTrabalho, "rh02_regist", $iInstituicao);
    $rsServidores       = db_query($sSqlServidores);
    $aServidores      = array();
  
    if ( !$rsServidores ) {
      throw new DBException("Erro ao Buscar sevidores pelo Local de Trabalho");
    }
  
    /**
     * Utilizado FOR por causa de Desempenho
     */
    for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
  
      $oDadosServidor     =  db_utils::fieldsMemory( $rsServidores, $iIndiceServidor );
      $iMatriculaServidor =  $oDadosServidor->rh02_regist;
      $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
    }
    
    return $aServidores;
  }
  
  /**
   * Busca Servidores pelo Recurso
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByRecurso($iAnoFolha, $iMesFolha, $iCodigoRecurso, $iInstituicao = null) {
  	
  	if ( empty($iInstituicao) ) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  
    $oDaoRHLotaVinc = db_utils::getDao("rhlotavinc");
    $sSqlServidores = $oDaoRHLotaVinc->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoRecurso, "rh02_regist", $iInstituicao);
    $rsServidores   = db_query($sSqlServidores);
    $aServidores    = array();
  
    if ( !$rsServidores ) {
      throw new DBException("Erro ao Buscar sevidores pelo Recurso");
    }
  
    /**
     * Utilizado FOR por causa de Desempenho
     */
    for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
  
      $oDadosServidor                   =  db_utils::fieldsMemory( $rsServidores, $iIndiceServidor );
      $iMatriculaServidor               =  $oDadosServidor->rh02_regist;
      $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
    }
    
    return $aServidores;
  }
  
  /**
   * Retorna servidor
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iCodigoSelecao
   * @throws DBException
   */
  public static function getServidoresBySelecao($iAnoFolha, $iMesFolha, $iCodigoSelecao, $iInstituicao = null ) {
  	 
  	if (empty($iInstituicao)) {
  		$iInstituicao = db_getsession('DB_instit');
  	}

  	$oDaoSelecao    = db_utils::getDao("selecao");
  	$sSqlServidores = $oDaoSelecao->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoSelecao, "rh02_regist", $iInstituicao);
  	$rsServidores   = db_query($sSqlServidores);
  	$aServidores    = array();

  	if ( !$rsServidores ) {
  		throw new DBException("Erro ao Buscar sevidores pela Selecão");
  	}

  	/**
  	 * Utilizado FOR por causa de Desempenho
  	 */
  	for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {

  		$oDadosServidor                   = db_utils::fieldsMemory( $rsServidores, $iIndiceServidor );
  		$iMatriculaServidor               = $oDadosServidor->rh02_regist;
  		$aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo( $iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao );
  	}

  	return $aServidores;
  }
  
  /**
   * Retorna uma coleção de objetos Servidor, selecionando por tipo de vinculo 
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iVinculoServidor
   * @param string $iInstituicao
   * @return multitype:Servidor
   */
  public static function getServidoresPorVinculo($iAnoFolha, $iMesFolha, $iVinculoServidor, $sInstituicao = null) {
  	
  	if ( empty($sInstituicao) ) {
  		$sInstituicao = db_getsession('DB_instit');
  	}
  	
		$oDaoRhRegime = db_utils::getDao('rhregime');

		/**
		 * Sql que retorna conjunto de servidores, dependendo do vinculo selecionado 
		 */
  	$sSqlServidoresPorVinculo = $oDaoRhRegime->sql_query_servidorerPorVinculo($iAnoFolha,
  			                                                                     $iMesFolha,
  												      						    												     $iVinculoServidor,
  			                                                                     "rh02_regist, rh02_instit",
  			                                                                     $sInstituicao);
  	
  	$rsServidoresPorVinculo = $oDaoRhRegime->sql_record($sSqlServidoresPorVinculo);
  	
  	$aServidores            = array();
  	
  	for ($iIndice =0; $iIndice < $oDaoRhRegime->numrows; $iIndice++) {
  		
  		$oServidorPorVinculo = db_utils::fieldsMemory($rsServidoresPorVinculo, $iIndice);
  		$aServidores[$oServidorPorVinculo->rh02_regist] = ServidorRepository::getInstanciaByCodigo( $oServidorPorVinculo->rh02_regist, 
  																																																$iAnoFolha,
  																																															  $iMesFolha, 
  																																															  $oServidorPorVinculo->rh02_instit );
  		
  	}
  	
  	return $aServidores;
  	
  }
    
  /**
   * Retorna servidores no intervalo informado
   * 
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @param integer $iMatricula
   */
  public static function getServidoresNoIntervalo( DBDate $oDataInicial, DBDate $oDataFinal, $iMatricula ){
    
  	$aServidores   = array();
  	$aCompetencias = array_reverse( DBPessoal::getCompetenciasIntervalo( $oDataInicial, $oDataFinal ) );
  	
  	foreach( $aCompetencias as $oCompetencia ) {
  	
  	  try {
  	
  	    $oServidorCompetencia = ServidorRepository::getInstanciaByCodigo(
  	        $iMatricula,
  	        $oCompetencia->getAno(),
  	        $oCompetencia->getMes()
  	    );
  	  } catch ( BusinessException $eErro ) {
  	    //caso não exitsta servidor na competencia.
  	    continue;
  	  }
  	  $aServidores[] = $oServidorCompetencia;
  	}
  	
  	return $aServidores;
  }
}