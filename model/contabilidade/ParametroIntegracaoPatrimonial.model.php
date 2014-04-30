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


require_once("std/DBDate.php");
require_once("model/configuracao/Instituicao.model.php");

/**
 * Patrametros de integracao da contabilidade com modulos do patrimonio
 * 
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @author Bruno de Boni <bruno.boni@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.2 $
 */
class ParametroIntegracaoPatrimonial {
  
  /**
   * Modulos
   */
  const CONTRATO   = 1;
  const MATERIAL   = 2;
  const PATRIMONIO = 3;

  /**
   * Codigo do parametro
   * @var integer
   */
  private $iCodigo;

  /**
   * data de implatancao da integracao com o modulo
   * @var DBDate
   */
  private $oDataImplantacao;

  /**
   * Instituicao do parametro
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Codigo do modulo
   * @var integer
   */
  private $iModulo;

  /**
   * Construtor, busca parametro pelo codigo, caso informado
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = 0) {
    
    if (empty($iCodigo)) {
      return;
    }

    $rsBuscarParametro = self::buscarParametro($iCodigo);   
    $oDadosParametros  = db_utils::fieldsMemory($rsBuscarParametro, 0);

    $this->iCodigo          = $iCodigo;
    $this->oDataImplantacao = new DBDate($oDadosParametros->c01_data);
    $this->oInstituicao     = new Instituicao($oDadosParametros->c01_instit);
    $this->iModulo          = $oDadosParametros->c01_modulo;
  }

  /**
   * Retorna o sequencial do parâmetro
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**  
   * Retorna a data de implantação
   * @return DBDate
   */
  public function getDataImplantacao() {
    return $this->oDataImplantacao;
  }

  /**
   * Define a data de implantação
   * @param DBDate $oDataImplantacao
   */
  public function setDataImplantacao($oDataImplantacao) {
    $this->oDataImplantacao = $oDataImplantacao;
  }

  /**
   * Retorna a instituição
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Define a instituicao
   * @param Instituicao $oInstituicao 
   */
  public function setInstituicao($oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna do modulo
   * @return integer
   */
  public function getModulo() {
    return $this->iModulo;
  }

  /**
   * Define modulo
   * @param integer $iModulo 
   */
  public function setModulo($iModulo) {
    $this->iModulo = $iModulo;
  }

  /**
   * Salva parametro no banco
   * @return void
   */
  public function salvar() {

    $oDaoParametroIntegracaoPatrimonial = db_utils::getDao('parametrointegracaopatrimonial');
    $oDaoParametroIntegracaoPatrimonial->c01_data = $this->getDataImplantacao()->getDate();
    $oDaoParametroIntegracaoPatrimonial->c01_instit = $this->getInstituicao()->getCodigo();
    $oDaoParametroIntegracaoPatrimonial->c01_modulo = $this->getModulo();
    $oDaoParametroIntegracaoPatrimonial->incluir(null);
    
    if ($oDaoParametroIntegracaoPatrimonial->erro_status == "0") {
      throw new Exception ($oDaoParametroIntegracaoPatrimonial->erro_msg);
    }
  }

  /** 
   * Retorna patametros de uma instituição
   * @param  Instituicao $oInstituicao
   * @return array                   
   */
  public static function getParametroPorInstituicao(Instituicao $oInstituicao) {

    $aParametros = array();
    $rsBuscarParametro = self::buscarParametro(null, $oInstituicao);
    $aDadosParametros  = db_utils::getCollectionByRecord($rsBuscarParametro);

    foreach ($aDadosParametros as $oDadosParametro) {
      $aParametros[] = new ParametroIntegracaoPatrimonial($oDadosParametro->c01_sequencial);
    }

    return $aParametros;
  }

  /**
   * Busca patrametros 
   * 
   * @param  integer $iCodigo         
   * @param  Instituicao $oInstituicao
   * @param  DBDate $oDataImplantacao
   * @param  integer $iModulo 
   * @return Resource
   */
  private static function buscarParametro($iCodigo = null, Instituicao $oInstituicao = null, DBDate $oDataImplantacao = null, $iModulo = null) {

    $aWhereParametro = array();
    $oDaoParametroIntegracaoPatrimonial = db_utils::getDao('parametrointegracaopatrimonial');

    if (! empty($iCodigo)) {
      $aWhereParametro[] = " c01_sequencial = {$iCodigo}";
    }

    if (! empty($oInstituicao)) {
      $aWhereParametro[] = " c01_instit = {$oInstituicao->getCodigo()}";
    }

    if (! empty($oDataImplantacao)) {
      $aWhereParametro[] = " c01_data <= '{$oDataImplantacao->getDate()}'";
    }

    if (! empty($iModulo)) {
      $aWhereParametro[] = " c01_modulo = {$iModulo}";
    }

    $sWhereBuscarParametro = implode(' and ', $aWhereParametro);
    
    $sSqlBuscarParametro = $oDaoParametroIntegracaoPatrimonial->sql_query_file(null, "*", null, $sWhereBuscarParametro);
    $rsBuscarParametro = db_query($sSqlBuscarParametro);

    if (! $rsBuscarParametro) {
      throw new BusinessException (_M("financeiro.contabilidade.ParametroIntegracaoPatrimonial.erro_buscar_parametros"));
    }

    return $rsBuscarParametro;   
  }

  /**
   * Valida implantacao do modulo
   * 
   * @param  DBDATE      $oDataImplantacao
   * @param  Instituicao $oInstituicao     
   * @param  integer     $iModulo          
   * @return boolean
   */
  public static function validarImplantacao(DBDate $oDataImplantacao, Instituicao $oInstituicao, $iModulo) {

    $rsBuscarParametro = self::buscarParametro(null, $oInstituicao, $oDataImplantacao, $iModulo);
    return pg_num_rows($rsBuscarParametro) > 0;
  }

  /**
   * Verifica se insituicao possui integração por contrato em determinada data
   * @param  DBDATE      $oDataImplantacao
   * @param  Instituicao $oInstituicao
   * @return boolean
   */
  public static function possuiIntegracaoContrato(DBDate $oDataImplantacao, Instituicao $oInstituicao) {
    return self::validarImplantacao($oDataImplantacao, $oInstituicao, self::CONTRATO);    
  }

  /**
   * Verifica se insituicao possui integração por material em determinada data
   * @param  DBDATE      $oDataImplantacao
   * @param  Instituicao $oInstituicao
   * @return boolean
   */
  public static function possuiIntegracaoMaterial(DBDate $oDataImplantacao, Instituicao $oInstituicao) {
    return self::validarImplantacao($oDataImplantacao, $oInstituicao, self::MATERIAL);    
  }

  /**
   * Verifica se insituicao possui integração por patrimônio em determinada data
   * @param  DBDATE      $oDataImplantacao
   * @param  Instituicao $oInstituicao
   * @return boolean
   */
  public static function possuiIntegracaoPatrimonio(DBDate $oDataImplantacao, Instituicao $oInstituicao) {
    return self::validarImplantacao($oDataImplantacao, $oInstituicao, self::PATRIMONIO);    
  }

}