<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
 * Repositório dos assentamentos do Sistema.
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 */
class AssentamentoRepository {
  

  const MENSAGEM = 'recursoshumanos.pessoal.AssentamentoRepository.';

  /**
   * Representa o collection com os assentamentos;
   *
   * @var Array Assentamentos
   */
  private $aAssentamentos = array();

  /**
   * Instancia da Classe
   *
   * @var Assentamento
   */
  private static $oInstance;

  private function __construct(){}

  private function __clone(){}

  /**
   * Retorna a instância do Repository
   *
   * @access protected
   * @return  AssentamentoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance === null) {
      self::$oInstance  = new AssentamentoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Monta o Objeto assentamento a partir do código informado por parâmetro.
   *
   * @access public
   * @param integer $iCodigoAssentamento
   * @return Assentamento $oAssentamento
   */
  public static function make($iCodigoAssentamento) {

    $oDaoAssenta = new cl_assenta();
    $sSqlAssenta = $oDaoAssenta->sql_query_file($iCodigoAssentamento);
    $rsAssenta = db_query($sSqlAssenta);

    if (!$rsAssenta) {
      throw new DBException(_M(self::MENSAGEM.'erro_buscar_assentamento'));
    }

    if (pg_num_rows($rsAssenta) == 0) {
      throw new BusinessException(_M(self::MENSAGEM.'nenhum_assentamento_encontrado'));
    }
    
    $oAssentamentoEncontrado = db_utils::FieldsMemory($rsAssenta, 0);

    $oAssentamento = AssentamentoFactory::getByCodigo($iCodigoAssentamento);
    
    $oAssentamento->setCodigo( $oAssentamentoEncontrado->h16_codigo );
    $oAssentamento->setMatricula( $oAssentamentoEncontrado->h16_regist );
    $oAssentamento->setTipoAssentamento( $oAssentamentoEncontrado->h16_assent );
    $oAssentamento->setHistorico( $oAssentamentoEncontrado->h16_histor );
    $oAssentamento->setCodigoPortaria( $oAssentamentoEncontrado->h16_nrport );
    $oAssentamento->setDescricaoAto( $oAssentamentoEncontrado->h16_atofic );
    $oAssentamento->setDias( $oAssentamentoEncontrado->h16_quant );
    $oAssentamento->setPercentual( $oAssentamentoEncontrado->h16_perc );
    $oAssentamento->setSegundoHistorico( $oAssentamentoEncontrado->h16_hist2 );
    $oAssentamento->setLoginUsuario( $oAssentamentoEncontrado->h16_login );
    $oAssentamento->setDataLancamento( $oAssentamentoEncontrado->h16_dtlanc );
    $oAssentamento->setConvertido( $oAssentamentoEncontrado->h16_conver );
    $oAssentamento->setAnoPortaria( $oAssentamentoEncontrado->h16_anoato );
    $oAssentamento->setHora( $oAssentamentoEncontrado->h16_hora );

    if (!empty($oAssentamentoEncontrado->h16_dtconc)) {

      $oDataConcessao = new DBDate($oAssentamentoEncontrado->h16_dtconc);
      $oAssentamento->setDataConcessao ($oDataConcessao);
    }

    if (!empty($oAssentamentoEncontrado->h16_dtterm)) {

      $oDataTermino = new DBDate($oAssentamentoEncontrado->h16_dtterm);
      $oAssentamento->setDataTermino   ($oDataTermino);
    }

    return $oAssentamento;
  }

  /**
   * Adiciona um objeto Assentamento ao collection de Assentamentos
   *
   * @access public
   * @param  Assentamento  $oAssentamento
   */
  public static function adicionar(Assentamento $oAssentamento) {
    self::getInstance()->aAssentamentos[$oAssentamento->getCodigo()] = $oAssentamento;
  }

  /**
   * Retorna a instância do Assentamento referente ao Código informado por parâmetro.
   *
   * @param  integer  $iCodigo
   * @return  Assentamento
   */
  public static function getInstanceByCodigo($iCodigo) {

    if (!isset(self::getInstance()->aAssentamentos[$iCodigo])) {
      self::adicionar(self::make($iCodigo));
    }
    
    return self::getInstance()->aAssentamentos[$iCodigo];
  }


  public static function persist(Assentamento $oAssentamento) {

    $mResponsePeristAssentamento = $oAssentamento->persist();

    if(!$mResponsePeristAssentamento instanceof Assentamento) {
      throw new BusinessException(_M(self::MENSAGEM."erro_persistir_assentamento")."\n\n".$mResponsePeristAssentamento);
    }

    return $mResponsePeristAssentamento;
  }

  public static function getServidoresAssentamentoSubstituicao() {

    $aListaServidores                  = array();
    $iNaturezaAssentamentoSubstituicao = AssentamentoSubstituicao::CODIGO_NATUREZA;
    $oCompetencia                      = DBPessoal::getCompetenciaFolha();
    $oDaoAssentamento                  = new cl_assenta();

    $sCamposAssentamentoSubstituicao   = " h16_regist as servidor ";

    $sWhereAssentamentoSubstituicao    = "    rh159_sequencial = {$iNaturezaAssentamentoSubstituicao}
                                          and rh155_ano = {$oCompetencia->getAno()}
                                          and rh155_mes = {$oCompetencia->getMes()}
                                           or rh160_assentamento is null";

    $sqlAssentamentoSubstituicao       = $oDaoAssentamento->sql_query_servidores_com_assentamento_substituicao(null, 
                                                                                                               $sCamposAssentamentoSubstituicao,
                                                                                                               "h16_regist",
                                                                                                               $sWhereAssentamentoSubstituicao);


    $rsAssentamentoSubstituicao        = db_query($sqlAssentamentoSubstituicao);

    if(!$rsAssentamentoSubstituicao){
      throw new BusinessException(_M(self::MENSAGEM."erro_buscar_servidores_assentamento_substituicao"));
    } else {
      if(pg_num_rows($rsAssentamentoSubstituicao) > 0){
        
        $aAssentamentos = db_utils::getCollectionByRecord($rsAssentamentoSubstituicao);

        foreach ($aAssentamentos as $oStdAssentamento) {
          
          $oServidor          = ServidorRepository::getInstanciaByCodigo($oStdAssentamento->servidor, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
          $aListaServidores[] = $oServidor;
        }
      }
    }

    return $aListaServidores;
  }

  public static function getAssentamentosSubstituicaoServidor($iMatricula, $oCompetencia = null) {

    if (is_null($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $oServidor      = ServidorRepository::getInstanciaByCodigo($iMatricula, $oCompetencia->getAno(), $oCompetencia->getMes());
    $aAssentamentos = $oServidor->getAssentamentosSubstituicao();
    $aAssentemaentosServidor = array();

    foreach ($aAssentamentos as $oAssentamento) {

      $oStdAssentamento = new stdClass();
      $oStdAssentamento->codigo              = $oAssentamento->getCodigo();
      $oStdAssentamento->dataConcessao       = ($oAssentamento->getDataConcessao() instanceof DBDate ? $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR) : $oAssentamento->getDataConcessao());
      $oStdAssentamento->dataTermino         = ($oAssentamento->getDataTermino() instanceof DBDate ? $oAssentamento->getDataTermino()->getDate(DBDate::DATA_PTBR) : $oAssentamento->getDataTermino());
      $oStdAssentamento->dias                = $oAssentamento->getDias();
      $oStdAssentamento->valor_substituicao  = $oAssentamento->getValorCalculado();
      $oStdAssentamento->hasLote             = false;
      $oStdAssentamento->isLoteFolhaFechada  = false;

      if($oAssentamento->hasLote() === false){
        $aAssentemaentosServidor[] = $oStdAssentamento;
      } else {

        if(DBPessoal::getCompetenciaFolha()->comparar($oAssentamento->hasLote()->getCompetencia())){

          $oStdAssentamento->hasLote  = true;
          $oFolhaPagamento            = $oAssentamento->hasLote()->getFolhaPagamento();

          if($oFolhaPagamento === false) {
            throw new BusinessException(_M(self::MENSAGEM."erro_buscar_folha_pagamento_lote"));
          } else {
            if(!$oFolhaPagamento->isAberto()) {
              $oStdAssentamento->isLoteFolhaFechada = true;
            }
          }

          $aAssentemaentosServidor[] = $oStdAssentamento;
        }
      }
    }
    
    return $aAssentemaentosServidor;
  }

  public static function persistLancamento(Assentamento $oAssentamento) {

  }

  /**
   * Retorna todos os assentamentos do servidor
   *
   * @param Servidor $oServidor
   * @param Integer  $iTipoAssentamento
   */
  public static function getAssentamentosPorServidor(Servidor $oServidor, $iTipoAssentamento = null , DBDate $oDataMinima = null, $sTipo = null, $lAssentamentoFuncional = null) {

    $sWhere = "h16_regist = {$oServidor->getMatricula()}";

    if($lAssentamentoFuncional !== null) {
      
      $sWhereAssentamentoFuncional = " and rh193_assentamento_funcional is not null";
      if($lAssentamentoFuncional === false) {
        $sWhereAssentamentoFuncional = " and rh193_assentamento_funcional is null";
      }
      
      $sWhere .= $sWhereAssentamentoFuncional;
    }

    if ( !empty($iTipoAssentamento) ) {

      if (is_array($iTipoAssentamento)) {
        $iTipoAssentamento = implode(",", $iTipoAssentamento);
      }
      $sWhere .= " and h16_assent in({$iTipoAssentamento})";
    }
    if ( $oDataMinima ) {
      $sWhere .= " and h16_dtconc >= '{$oDataMinima->getDate()}' ";
    }

    if ($sTipo) {
      $sWhere .= " and h12_tipo = '{$sTipo}'";
    }
    $oDaoAssentamento = new cl_assenta();
    $sSqlBusca        = $oDaoAssentamento->sql_query_funcional(
      null, 
      "h16_codigo", 
      null, 
      $sWhere
    );

    $rsAssentamentos  = db_query($sSqlBusca);
    
    if (!$rsAssentamentos)  {
      throw new DBException(_M(self::MENSAGEM . "erro_buscar_assentamentos_servidor")); 
    }

    $aAssentemaentos = array();

    foreach (db_utils::getCollectionByRecord($rsAssentamentos) as $oDados) {
      $aAssentemaentos[] = AssentamentoFactory::getByCodigo($oDados->h16_codigo);
    }

    return $aAssentemaentos;
  }

  /**
   * Exclui um assentamento
   *
   * @param Assentamento $oAssentamento
   * @throws \BusinessException
   */
  public static function excluir(Assentamento $oAssentamento) {

    $oDaoAssentamento = new cl_assenta();
    $oDaoAssentamento->excluir($oAssentamento->getCodigo());

    if ($oDaoAssentamento->erro_status == 0) {
      throw new BusinessException("Erro ao excluir o assentamento.\nErro:{$oDaoAssentamento->erro_sql}");
    }
  }

  /**
   * Retorna todos os assentamentos do tipo afastamento do servidor
   *
   * @param Servidor $oServidor
   * @param Integer  $iTipoAssentamento
   * @param \DBDate  $oDataMinima
   * @return \Assentamento[]
   * @throws \DBException
   */
  public static function getAssentamentosDeAfastamentoPorServidor(Servidor $oServidor, $iTipoAssentamento = null , DBDate $oDataMinima = null) {

    $sWhere           = "h16_regist = {$oServidor->getMatricula()}";

    if ( !empty($iTipoAssentamento) ) {

      if (is_array($iTipoAssentamento)) {
        $iTipoAssentamento = implode(",", $iTipoAssentamento);
      }
      $sWhere .= " and h16_assent in({$iTipoAssentamento})";
    }
    if ( $oDataMinima ) {
      $sWhere .= " and h16_dtconc >= '{$oDataMinima->getDate()}' ";
    }
    $sWhere .= " and h12_tipo = 'A'";
    $oDaoAssentamento = new cl_assenta();
    $sSqlBusca        = $oDaoAssentamento->sql_query(
      null,
      "h16_codigo",
      null,
      $sWhere
    );

    $rsAssentamentos  = db_query($sSqlBusca);

    if (!$rsAssentamentos)  {
      throw new DBException(_M(self::MENSAGEM . "erro_buscar_assentamentos_servidor"));
    }

    $aAssentemaentos = array();

    foreach (db_utils::getCollectionByRecord($rsAssentamentos) as $oDados) {
      $aAssentemaentos[] = AssentamentoFactory::getByCodigo($oDados->h16_codigo);
    }

    return $aAssentemaentos;
  }

  /**
   * Retorna assentamento de justificativa em determinado período
   *
   * @param integer $codigoTipoasse
   * @param integer $matricula
   * @param \DBDate $dataConcessao
   * @param \DBDate $dataTermino
   * @throws \DBException
   * @return \Assentamento
   */
  public static function getAssentamentoJustificativaPorTipoServidorPeriodo($codigoTipoasse, $matricula, DBDate $dataConcessao, $dataTermino = null) {

    $aWhere = array(
      "h12_natureza = ". Assentamento::NATUREZA_JUSTIFICATIVA,
      "h16_regist  = {$matricula}",
      "h16_assent  = {$codigoTipoasse}"
    );

    $sWhereDatas  = "(";
    $sWhereDatas .= "(h16_dtconc <= '{$dataConcessao->getDate()}'";
    $sWhereDatas .= " AND (h16_dtterm >= '{$dataConcessao->getDate()}' OR h16_dtterm is null))";

    if(empty($dataTermino)) {
      $sWhereDatas .= " OR  (h16_dtconc >= '{$dataConcessao->getDate()}')";
    }

    if(!empty($dataTermino)) {
      $sWhereDatas .= " OR  (h16_dtconc >= '{$dataConcessao->getDate()}' AND h16_dtterm is null)";
      $sWhereDatas .= " OR  (h16_dtconc >= '{$dataConcessao->getDate()}' AND h16_dtconc <= '{$dataTermino->getDate()}')";
    }
    $sWhereDatas .= " )";
    
    $aWhere[] = $sWhereDatas;

    $sWhere = implode(' AND ', $aWhere);

    $oDaoAssentamento = new cl_assenta();
    $sSqlBusca        = $oDaoAssentamento->sql_query(null,"h16_codigo",null,$sWhere);
    
    $rsAssentamentos  = db_query($sSqlBusca);
    
    if (!$rsAssentamentos)  {
      throw new DBException(_M(self::MENSAGEM . "erro_buscar_assentamentos_servidor"));
    }
    
    if (pg_num_rows($rsAssentamentos) > 0)  {
      return db_utils::makeFromRecord($rsAssentamentos, function ($retorno) {
        return AssentamentoFactory::getByCodigo($retorno->h16_codigo);
      });
    }

    return null;
  }

  /**
   * Retorna os assentamentos de um servidor por um tipo, natureza e data
   * @param \Servidor $servidor
   * @param String $tipoAssentamento
   * @param \DBDate $data
   * @param Integer $natureza
   * @return \Assentamento[] | null
   *
   * @throws \ParameterException
   * @throws \DBException
   */
  public static function getAssentamentosServidorPorTipoENatureza(\Servidor $servidor, $tipoAssentamento = 'S', \DBDate $data, $natureza = null, $lFuncional = false) {

    if(!($data instanceof \DBDate)) {
      throw new \ParameterException("Informe uma data válida para verificar se o servidor está afastado.");
    }

    $daoAssenta = new \cl_assenta;
    
    $aWhereAssenta   = array("h16_regist = {$servidor->getMatricula()}");
    $aWhereAssenta[] = "h12_tipo = '{$tipoAssentamento}'";
    $aWhereAssenta[] = "(    (h16_dtterm is null AND h16_dtconc <= '{$data->getDate()}')
                          OR (h16_dtterm >= '{$data->getDate()}' AND h16_dtconc <= '{$data->getDate()}')
                        )";

    if($lFuncional !== null) {

      if($lFuncional) {
        $aWhereAssenta [] = " rh193_assentamento_funcional is not null ";
      }

      if($lFuncional === false) {
        $aWhereAssenta [] = " rh193_assentamento_funcional is null ";
      }
    }

    if(!empty($natureza)) {
      $aWhereAssenta[] = "h12_natureza = {$natureza}";
    }

    $whereAssenta = implode(' and ', $aWhereAssenta);
    $rsAssenta    = db_query($sqlAssenta = $daoAssenta->sql_query_funcional(null, "*", "h16_dtconc, h16_codigo", $whereAssenta));
    
    if(!$rsAssenta) {
      throw new \DBException("Ocorreu um erro ao consultar os assentamentos de afastamento no módulo RH.\nContate o suporte.\n\n". pg_last_error());
    }

    if(pg_num_rows($rsAssenta) > 0) {

      $assentamentoRepository = self::getInstance();

      return \db_utils::makeCollectionFromRecord($rsAssenta, function($retorno) {
        return \AssentamentoFactory::getByCodigo($retorno->h16_codigo);
      });
    }
    
    return null;
  }
}
