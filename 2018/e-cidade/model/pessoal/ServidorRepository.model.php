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
  public static function getInstanciaByCodigo($iMatricula, $iAnoFolha = null, $iMesFolha = null, $iInstituicao = null) {

    if ( empty($iInstituicao) ) {
      $iInstituicao = db_getsession('DB_instit');
    }

    if ( empty($iAnoFolha) ) {
      $iAnoFolha = DBPessoal::getAnoFolha();
    }

    if ( empty($iMesFolha) ) {
      $iMesFolha = DBPessoal::getMesFolha();
    }

    if ( $iMatricula == 0 ) {
      throw new BusinessException("Matrícula inválida.");
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
   * @param Integer $iInstituicao
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
  public static function getServidoresByLotacao($iAnoFolha, $iMesFolha, $mLotacoes, $iInstituicao = null) {

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
    if ( $iQuantidadeArgumentos <= 2 ) {
      throw new ParameterException("Parametros passados Incorretamente");
    }

    if (empty($mLotacoes)) {
      throw new BusinessException("Erro ao informar lotação.");
    } 

    if ( $iQuantidadeArgumentos >= 3 && is_array($mLotacoes) ) {

      $sTipoBusca    = "SELECIONADOS";
      $aSelecionados = $mLotacoes;

    } elseif ( $iQuantidadeArgumentos >= 3 && DBNumber::isInteger($mLotacoes) ) {

      $sTipoBusca  = "CHAVE";
      $iChaveBusca = $mLotacoes;

    } elseif ( $iQuantidadeArgumentos >= 3 ) {

      $sTipoBusca  = "INTERVALO";
      $iChaveBusca = $mLotacoes;
      list($iPrimeiroArgumento, $iSegundoArgumento) = explode(",", $mLotacoes, 2);

      if(strpos($iSegundoArgumento, ",") !== false) {
        throw new BusinessException("Erro ao informar range de lotações.");
      }
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
   * @param null    $iInstituicao
   * @return \Servidor[]
   * @throws \DBException
   */
  public static function getServidoresBySelecao($iAnoFolha, $iMesFolha, $iCodigoSelecao, $iInstituicao = null ) {

    if (empty($iInstituicao)) {
      $iInstituicao = db_getsession('DB_instit');
    }

    $oDaoSelecao    = new cl_selecao;
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

    /**
     * Ordenamos os servidores pela matricula.
     */
    ksort($aServidores);

    return $aServidores;
  }

  /**
   * Retorna uma coleção de objetos Servidor, selecionando por tipo de vinculo
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iVinculoServidor
   * @param null    $sInstituicao
   * @return \Servidor[]
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
   * @param DBDate  $oDataInicial
   * @param DBDate  $oDataFinal
   * @param integer $iMatricula
   * @return Servidor[]
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

  /**
   * Retorna os servidores no ponto conforme folha de pagamento informada
   * 
   * @param  FolhaPagamento $oFolhaPagamento [description]
   * @return
   */
  public static function getServidoresNoPontoPorFolhaPagamento( FolhaPagamento $oFolhaPagamento, $lRetornaDuploVinculo = false, $sMatriculas = null ) {

    $iMes         = $oFolhaPagamento->getCompetencia()->getMes();
    $iAno         = $oFolhaPagamento->getCompetencia()->getAno();
    $sPonto       = "cl_" . $oFolhaPagamento->getTabelaPonto();
    $oDaoPonto    = new $sPonto();

    switch ( $oFolhaPagamento->getTabelaPonto() ) {

    case PontoComplementar::TABELA: 
      $sSigla = PontoComplementar::SIGLA_TABELA;
      break;
    case PontoSalario::TABELA:
      $sSigla = PontoSalario::SIGLA_TABELA;
      break;
    default:
      return array();
    }

    $iInstituicao = db_getsession('DB_instit');

    $sWhereServidores  = "    {$sSigla}_anousu = {$iAno} ";
    $sWhereServidores .= "and {$sSigla}_mesusu = {$iMes} ";
    $sWhereServidores .= "and {$sSigla}_instit = {$iInstituicao} ";
    if (!empty($sMatriculas)) {
      $sWhereServidores .= "and {$sSigla}_regist in ({$sMatriculas})";
    }

    $sSqlServidores = $oDaoPonto->sql_query_file(null, null, null, null, "distinct {$sSigla}_regist as matricula", null, $sWhereServidores);

    $rsServidores   = db_query($sSqlServidores);

    if ( !$rsServidores ) {
      throw new DBException($oDaoPonto->erro_msg);
    }

    $aServidores = array();

    for ( $iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++ ) {

      $oDadosServidor                          = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
      $oServidor                               = self::getInstanciaByCodigo($oDadosServidor->matricula, $iAno, $iMes);
      $aServidores[$oDadosServidor->matricula] = $oServidor;

      if ( $lRetornaDuploVinculo && $oServidor->hasServidorVinculado() )  {

        $oServidorVinculado = $oServidor->getServidorVinculado();
        $aServidores[$oServidorVinculado->getMatricula()] = $oServidorVinculado;
      }
    }

    return $aServidores;
  }

  /**
   * Retorna os servidores no ponto conforme folha de pagamento informada
   * 
   * @param  FolhaPagamento $oFolhaPagamento [description]
   * @return
   */
  public static function getServidoresNoCalculoPorFolhaPagamento( FolhaPagamento $oFolhaPagamento, $aServidoresCalcular = null  ) {

    $iMes         = $oFolhaPagamento->getCompetencia()->getMes();
    $iAno         = $oFolhaPagamento->getCompetencia()->getAno();
    $sCalculo     = "cl_" . $oFolhaPagamento->getTabelaCalculo();
    $oDaoCalculo    = new $sCalculo();

    switch ( $oFolhaPagamento->getTabelaCalculo() ) {

    case CalculoFolhaComplementar::TABELA:
      $sSigla = CalculoFolhaComplementar::SIGLA_TABELA;
      break;
    case CalculoFolhaSalario::TABELA:
      $sSigla = CalculoFolhaSalario::SIGLA_TABELA;
      break;
    default:
      return array();
    }

    $sWhere  = "{$sSigla}_anousu = {$iAno} and ";
    $sWhere .= "{$sSigla}_mesusu = {$iMes} and ";
    $sWhere .= "{$sSigla}_regist in (select rh144_regist ";
    $sWhere .= "                  from rhhistoricoponto ";

    if(!empty($aServidoresCalcular) && count($aServidoresCalcular) > 0) {
      $sWhere                   .= " where rh144_folhapagamento = {$oFolhaPagamento->getSequencial()} ";
      $sWhereMatriculasCalcular  = implode(",", $aServidoresCalcular);
      $sWhere                   .= " and rh144_regist in ({$sWhereMatriculasCalcular}) )";
    } else {
      $sWhere .= "                where rh144_folhapagamento = {$oFolhaPagamento->getSequencial()} )";
    }

    $sSqlServidores = $oDaoCalculo->sql_query_file($iAno, $iMes, null, null, "distinct {$sSigla}_regist as matricula", null, $sWhere);


    $rsServidores   = db_query($sSqlServidores);

    if ( !$rsServidores ) {
      throw new DBException("erro");
    }

    $aServidores = array();

    for ( $iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++ ) {

      $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
      $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
    }
    return $aServidores;
  }

  public function getServidoresDuploVinculo (FolhaPagamento $oFolha) {

    $oDaoRHPessoalMov = new cl_rhpessoalmov();
    $sSqlRhPessoalMov = $oDaoRHPessoalMov->sql_duplo_vinculo($oFolha->getCompetencia()->getAno(), $oFolha->getCompetencia()->getMes());
    $rsRhPessoalMov   = db_query($sSqlRhPessoalMov);
    $aServidores      = array();


    if (pg_num_rows($rsRhPessoalMov) > 0) {

      for ($iTotalDuploVinculo = 0; $iTotalDuploVinculo < pg_num_rows($rsRhPessoalMov); $iTotalDuploVinculo++) {

        $oServidor = db_utils::fieldsMemory($rsRhPessoalMov, $iTotalDuploVinculo);
        $aServidores = array_merge($aServidores, explode(',',$oServidor->rh01_regist));
      }
    }

    return $aServidores;
  }

  /**
   * Retorna os servidores do histórico cálculo.
   * 
   * @static
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @return Servidor[]
   * @throws DBException
   */
  public static function getServidoresHistoricoCalculo(FolhaPagamento $oFolhaPagamento, $aServidoresCalcular = null){

    $iMes                 = $oFolhaPagamento->getCompetencia()->getMes();
    $iAno                 = $oFolhaPagamento->getCompetencia()->getAno();    
    $oDaoHistoricoCalculo = new cl_rhhistoricocalculo();

    switch ($oFolhaPagamento->getTipoFolha()) {

    case FolhaPagamento::TIPO_FOLHA_SALARIO:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    default:
      return array();
    }

    $sWhere         = "rh143_folhapagamento = {$iSequencialFolha} ";

    if(!empty($aServidoresCalcular) && count($aServidoresCalcular) > 0) {
      $sWhereMatriculasCalcular  = implode(",", $aServidoresCalcular);
      $sWhere                   .= " and rh143_regist in ({$sWhereMatriculasCalcular}) ";
    }

    $sSqlServidores = $oDaoHistoricoCalculo->sql_query_file(null, "distinct rh143_regist as matricula", null, $sWhere);
    $rsServidores   = db_query($sSqlServidores);

    if (!$rsServidores) {
      throw new DBException("Erro ao consultar os servidores do histórico cálculo.");
    }

    $aServidores = array();

    for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {

      $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
      $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
    }

    return $aServidores;
  }

  /**
   * Retorna os servidores do histórico ponto.
   * 
   * @static
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @return Servidor[]
   * @throws DBException
   */
  public static function getServidoresHistoricoPonto(FolhaPagamento $oFolhaPagamento){

    $iMes               = $oFolhaPagamento->getCompetencia()->getMes();
    $iAno               = $oFolhaPagamento->getCompetencia()->getAno();    
    $oDaoHistoricoPonto = new cl_rhhistoricoponto();

    switch ($oFolhaPagamento->getTipoFolha()) {

    case FolhaPagamento::TIPO_FOLHA_SALARIO:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
      $iSequencialFolha = $oFolhaPagamento->getSequencial();
      break;
    default:
      return array();
    }

    $sWhere         = "rh144_folhapagamento = {$iSequencialFolha} ";
    $sSqlServidores = $oDaoHistoricoPonto->sql_query_file(null, "distinct rh144_regist as matricula", null, $sWhere);
    $rsServidores   = db_query($sSqlServidores);

    if (!$rsServidores) {
      throw new DBException("Erro ao consultar os servidores do histórico ponto.");
    }

    $aServidores = array();

    for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {

      $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
      $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
    }

    return $aServidores;
  } 

  /**
   * Persist o servidor na base de dados.
   * 
   * @static
   * @access public
   * @param Servidor $oServidor
   * @return Servidor | false
   * @throws DBException
   */
  public static function persistServidor(Servidor $oServidor, $lSalvouVinculado = null) {

    $oRetorno = new stdClass();
    $oRetorno->erro_msg    = '';
    $oRetorno->erro_status = 1;
    $oRetorno->servidor    = null;

    if ( is_null($oServidor->getContaBancaria()) || is_null($oServidor->getContaBancaria()->getSequencialContaBancaria()) ){
      $oRetorno->servidor = $oServidor;
    } else {

      $iCodigoContaBancaria          = $oServidor->getContaBancaria()->salvar();
      $oDaoRHPessoalMovContaBancaria = new cl_rhpessoalmovcontabancaria();
      db_query("delete from rhpessoalmovcontabancaria where rh138_rhpessoalmov = {$oServidor->getCodigoMovimentacao()};");
      $oDaoRHPessoalMovContaBancaria->rh138_rhpessoalmov = $oServidor->getCodigoMovimentacao();
      $oDaoRHPessoalMovContaBancaria->rh138_contabancaria= $iCodigoContaBancaria;
      $oDaoRHPessoalMovContaBancaria->rh138_instit       = db_getsession("DB_instit");
      $oDaoRHPessoalMovContaBancaria->incluir(null);
      $oRetorno->servidor = $oServidor;
    }

    //Buscando da Base os dados da tabela rhpessoalmov
    $oDaoRHPessoalMov = new cl_rhpessoalmov();
    $dbwhere  = "     rh02_anousu = {$oServidor->getAnoCompetencia()} ";
    $dbwhere .= " and rh02_mesusu = {$oServidor->getMesCompetencia()} ";
    $dbwhere .= " and rh02_regist = {$oServidor->getMatricula()}      ";
    $rsRHPessoalMov = $oDaoRHPessoalMov->sql_record($oDaoRHPessoalMov->sql_query_file(null, null, "*", null, $dbwhere));

    //Usando o array post para pegar os nomes dos atributos da classe cl_rhpessoalmov
    $propriedadesRhPessoalMov = $_POST;
    foreach ($propriedadesRhPessoalMov as $key => $value) { 
      if(strpos($key, 'rh02') === false){
        unset($propriedadesRhPessoalMov[$key]);
      }
    }

    /**
     * Caso o servidor não tenha duplo vínculo ou o primeiro dos vinculados. Atualiza os atributos
     * da classe cl_rhpessoalmov com o que foi enviado via POST para persistir na base de dados
     */
    foreach ($propriedadesRhPessoalMov as $key => $value) {
      if( isset($oDaoRHPessoalMov->$key) ){
        $oDaoRHPessoalMov->$key = $value;
      }
    }

    $GLOBALS["HTTP_POST_VARS"]['rh02_abonopermanencia'] = $oDaoRHPessoalMov->rh02_abonopermanencia;
    $GLOBALS["HTTP_POST_VARS"]['rh02_equip']            = $oDaoRHPessoalMov->rh02_equip;
    $GLOBALS["HTTP_POST_VARS"]['rh02_deficientefisico'] = $oDaoRHPessoalMov->rh02_deficientefisico;
    $GLOBALS["HTTP_POST_VARS"]['rh02_portadormolestia'] = $oDaoRHPessoalMov->rh02_portadormolestia;

    /**
     * Persist a tabela rhpessoalmov na base de dados chamando o método 
     * alterar ou incluir de acordo com o cenário a que se aplica
     */
    if ( is_resource($rsRHPessoalMov) && pg_numrows($rsRHPessoalMov) > 0) {

      $oDaoRHPessoalMov->rh02_seqpes = db_utils::fieldsMemory($rsRHPessoalMov, 0)->rh02_seqpes;
      $oDaoRHPessoalMov->rh02_instit = db_utils::fieldsMemory($rsRHPessoalMov, 0)->rh02_instit;

      if ( $oDaoRHPessoalMov->alterar($oDaoRHPessoalMov->rh02_seqpes,$oDaoRHPessoalMov->rh02_instit) ){
        $oRetorno->servidor = $oServidor;
      }
      else {
        $oRetorno->erro_status = 0;
      }

    } else {

      $oDaoRHPessoalMov = new cl_rhpessoalmov();
      $oDaoRHPessoalMov->rh02_instit = $oServidor->getCodigoInstituicao();

      if( $oDaoRHPessoalMov->incluir(null, $oServidor->getCodigoInstituicao()) ){
        $oRetorno->servidor = $oServidor;
      } else {
        $oRetorno->erro_status = 0;
      }
    }

    $oRetorno->erro_msg    = $oDaoRHPessoalMov->erro_msg;
    $oServidor->setTabelaPrevidencia($oDaoRHPessoalMov->rh02_tbprev);
    $oServidor->setAbonoPermanencia($oDaoRHPessoalMov->rh02_abonopermanencia);

    return $oRetorno;
  }


  public static function isMatriculaValida($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao = null) {

    if ( empty($iInstituicao) ) {
      $iInstituicao = db_getsession('DB_instit');
    }

    $lMatriculaValida = true;

    try {
      $oServidor = new Servidor($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao);
    } catch (Exception $eException) {
      $lMatriculaValida = false;
    }
    return $lMatriculaValida;
  }

  public static function getServidoresByTabelaPrevidencia($iTabelaPrevidencia, DBCompetencia $oCompetencia = null) {

    if (is_null($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $oDaoRHPessoalMov       = new cl_rhpessoalmov();
    $sSqlMatriculas         = $oDaoRHPessoalMov->sql_query_file(
      null, 
      db_getsession("DB_instit"), 
      "rh02_regist", 
      null, 
      "rh02_tbprev = {$iTabelaPrevidencia} and rh02_anousu = {$oCompetencia->getAno()} and rh02_mesusu = {$oCompetencia->getMes()}");
    $rsMatriculas           = db_query($sSqlMatriculas); 
    $aServidoresEncontrados = array();

    if ( !$rsMatriculas ) {
      throw new DBException("Não foi possível retornar os dados dos servidores para a Tabela de Previdencia informada.");
    }

    $aServidores = db_utils::getCollectionByRecord($rsMatriculas);

    foreach ($aServidores as $oServidor) {
      $aServidoresEncontrados[] = ServidorRepository::getInstanciaByCodigo($oServidor->rh02_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
    }
    return $aServidoresEncontrados;
  }

  public static function getServidoresByCgm( CgmFisico $oCgm, DBCompetencia $oCompetencia=null ) {

    if (is_null($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $oDaoRHPessoalMov       = new cl_rhpessoalmov();
    $sSqlMatriculas         = $oDaoRHPessoalMov->sql_query(
      null, 
      db_getsession("DB_instit"), 
      "rh02_regist", 
      null, 
      "rh01_numcgm = {$oCgm->getCodigo()} 
      and rh02_anousu = {$oCompetencia->getAno()} 
      and rh02_mesusu = {$oCompetencia->getMes()}
      and rh02_instit = ".db_getsession("DB_instit") );
    $rsMatriculas           = db_query($sSqlMatriculas); 
    $aServidoresEncontrados = array();

    if ( !$rsMatriculas ) {
      throw new DBException("Não foi possível retornar os dados dos Servidores.");
    }

    $aServidores = db_utils::getCollectionByRecord($rsMatriculas);

    foreach ($aServidores as $oServidor) {
      $aServidoresEncontrados[] = ServidorRepository::getInstanciaByCodigo($oServidor->rh02_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
    }
    return $aServidoresEncontrados;
  }


  public static function getServidoresPorTipoAssentamento($iTipoAssentamento, DBDate $oDataMinima = null) {
    
    $sData = "";
    if ( $oDataMinima ) {
      $sData = " and h16_dtconc >= '{$oDataMinima->getDate()}' ";
    }

    $oDaoAssentamento = new cl_assenta();
    $sSqlMatriculas   = $oDaoAssentamento->sql_query(
      null, 
      'distinct h16_regist', 
      null, 
      "h16_assent = $iTipoAssentamento $sData"
    );

    $rsMatriculas  = db_query($sSqlMatriculas);

    if (!$rsMatriculas) {
      throw new DBException("Erro ao buscar os servidores pelo tipo de assentamento");
    }

    /**
     * @var Servidor[]
     */
    $aServidores = array();

    foreach (db_utils::getCollectionByRecord($rsMatriculas) as $oDadosServidor){
       
      $aServidores[] = ServidorRepository::getInstanciaByCodigo(
        $oDadosServidor->h16_regist,
        DBPessoal::getCompetenciaFolha()->getAno(),
        DBPessoal::getCompetenciaFolha()->getMes()
      );
    }

    return $aServidores;
    
  }

  /**
   * Retorna uma instância de Servidor pelo PIS
   *
   * @param $sPIS
   * @return Servidor
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public static function getServidorByPIS($sPIS) {

    if(empty($sPIS)) {
      throw new ParameterException("PIS/PASEP não informado.");
    }

    $oDaoRhpesdoc   = new cl_rhpesdoc();
    $sWhereRhpesdoc = "rh16_pis = '{$sPIS}' AND rh01_instit = " . db_getsession("DB_instit");
    $sSqlRhpesdoc   = $oDaoRhpesdoc->sql_query_pessoal(null, 'rh16_regist', null, $sWhereRhpesdoc);
    $rsRhpesdoc     = db_query($sSqlRhpesdoc);

    if(!$rsRhpesdoc) {
      throw new DBException("Erro ao buscar a matrícula do servidor pelo PIS.");
    }

    if(pg_num_rows($rsRhpesdoc) == 0) {
      return null;
    }

    return self::getInstanciaByCodigo(db_utils::fieldsMemory($rsRhpesdoc, 0)->rh16_regist);
  }
}