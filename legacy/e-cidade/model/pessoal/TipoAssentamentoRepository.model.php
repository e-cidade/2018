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
 * Repositório para Tipos de assentamentos
 *
 * @package pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class TipoAssentamentoRepository {

 /**
   * Array com instancias de tipos de assentamentos
   *
   * @static
   * @var TipoAssentamento[]
   * @access private
   */
  static private $aColecao = array();

  /**
   * Representa a instancia a classe
   *
   * @var TipoAssentamentoRepository
   * @access private
   */
  private static   $oInstance;

  private static $aNaturezas = array(
    Assentamento::NATUREZA_PADRAO           => 'Padrão',
    Assentamento::NATUREZA_SUBSTITUICAO     => 'Substituição',
    Assentamento::NATUREZA_RRA              => 'RRA',
    Assentamento::NATUREZA_PONTO_ELETRONICO => 'Ponto Eletrônico',
    Assentamento::NATUREZA_JUSTIFICATIVA    => 'Justificativa',
    Assentamento::NATUREZA_DIA_EXTRA        => 'Dia Extra'
  );

  /**
   * Previne a criação do objeto externamente
   *
   * @return void
   */
  private function __construct() {
    return;
  }

  /**
   * Previne o clone
   *
   * @return void
   */
  private function __clone() {
    return;
  }

  /**
   * Retorna a instancia do repositório
   *
   * @return TipoAssentamentoRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {
      self::$oInstance = new TipoAssentamentoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona a coleção um tipo de assentamento
   *
   * @param TipoAssentamento $oTipoAssentamento
   */
  public static function add(TipoAssentamento $oTipoAssentamento) {

    $oRepository = self::getInstance();
    self::$aColecao[$oTipoAssentamento->getSequencial()] = $oTipoAssentamento;
  }

  /**
   * Monta um objeto TipoAssentamento
   *
   * @param  Integer $iCodigo
   *
   * @return TipoAssentamento
   */
  public function make($iCodigo) {

    /**
     * O objeto TipoAssentamento é montado no lazyLoad da classe TipoAssentamento.
     */
    $oTipoAssentamento = new TipoAssentamento($iCodigo);
    return $oTipoAssentamento;
  }

  /**
   * Retorna uma instancia da classe TipoAssentamento
   *
   * @param  Integer $iCodigo
   *
   * @return TipoAssentamento
   */
  public static function getInstanciaPorCodigo($iCodigo) {

    $oRepository = self::getInstance();

    if(!isset(self::$aColecao[$iCodigo])) {
      $oRepository->add($oRepository->make($iCodigo));
    }

    return self::$aColecao[$iCodigo];
  }

  /**
   * Retorna uma instancia da classe TipoAssentamento pelo tipo do assentamento
   * @param  int $sCodigo
   * @return TipoAssentamento
   * @throws BusinessException
   */
  public function getInstanciaPorTipo($sCodigo) {

    $oRepository = self::getInstance();

    foreach (self::$aColecao as $oAssentamento) {
      if ($oAssentamento->getCodigo() == $sCodigo) {
        return $oAssentamento;
      }
    }
    $oDaoTipoAsse     = new cl_tipoasse();
    $sSqlAssentamento = $oDaoTipoAsse->sql_query_file(null, 'h12_Codigo', null, "h12_assent='{$sCodigo}'");
    $rsDados          = db_query($sSqlAssentamento);
    if (!$rsDados || pg_num_rows($rsDados) == 0) {
      throw new BusinessException("Tipo de Assentamento {$sCodigo} não encontrado.");
    }

    $iCodigo = db_utils::fieldsMemory($rsDados, 0)->h12_codigo;
    if(!isset(self::$aColecao[$iCodigo])) {
      self::add($oRepository->make($iCodigo));
    }
    return self::$aColecao[$iCodigo];
  }

  /**
   * @param  int $iNatureza
   * @return TipoAssentamento[]
   * @throws BusinessException
   */
  public static function getInstanciasPorNatureza($iNatureza) {

    $oRepository = self::getInstance();

    $oDaoTipoAsse     = new cl_tipoasse();
    $sSqlAssentamento = $oDaoTipoAsse->sql_query_file(null, 'h12_Codigo', null, "h12_natureza = {$iNatureza}");
    $rsDados          = db_query($sSqlAssentamento);

    if (!$rsDados || pg_num_rows($rsDados) == 0) {

      $sMensagem  = "Nenhum assentamento com natureza do tipo '".self::$aNaturezas[$iNatureza]."' encontrado.";
      $sMensagem .= " Para configurá-lo, acesse o menu a seguir, incluindo um novo Tipo de Assentamento, ou alterando";
      $sMensagem .= " um registro existente:";
      $sMensagem .= "\n- RH > Cadastros > Tipos de Assentamento";

      throw new BusinessException($sMensagem);
    }

    foreach(db_utils::getCollectionByRecord($rsDados) as $oRetorno) {

      if(!isset(self::$aColecao[$oRetorno->h12_codigo])) {
        self::add($oRepository->make($oRetorno->h12_codigo));
      }
    }

    return self::$aColecao;
  }

  /**
   * @return TipoAssentamento[]
   * @throws BusinessException
   */
  public static function getInstanciasPorNaturezaComJustificativaConfigurada() {

    $oRepository = self::getInstance();

    $oDaoPontoeletronicojustificativatipoasse = new cl_pontoeletronicojustificativatipoasse();
    $sSqlTipoasse = $oDaoPontoeletronicojustificativatipoasse->sql_query_file();
    $rsDados      = db_query($sSqlTipoasse);

    if (!$rsDados || pg_num_rows($rsDados) == 0) {
      return array();
    }

    foreach(db_utils::getCollectionByRecord($rsDados) as $oRetorno) {

      if(!isset(self::$aColecao[$oRetorno->rh205_tipoasse])) {
        self::add($oRepository->make($oRetorno->rh205_tipoasse));
      }
    }

    return self::$aColecao;
  }

  public static function getInstanciasAfastamento() {

    if(!\DBRegistry::has('instanciasAssentamentoAfastamento')) {

      $oRepository  = self::getInstance();
      $oDaoTipoasse = new cl_tipoasse();
      $sSqlTipoasse = $oDaoTipoasse->sql_query_file(null, "*", null, "h12_tipo = 'A'");
      $rsTipoasse   = db_query($sSqlTipoasse);

      if(!$rsTipoasse) {
        throw new Exception("Ocorreu um erro ao consultar os tipos de assentamento de afastamento\n". pg_last_error());
      }

      if(pg_num_rows($rsTipoasse) == 0) {
        return array();
      }

      $colecaoRetornada = db_utils::getCollectionByRecord($rsTipoasse);

      \DBRegistry::add('instanciasAssentamentoAfastamento', $colecaoRetornada);
    }
    
    if(\DBRegistry::has('instanciasAssentamentoAfastamento')) {

      $colecaoRetornada = \DBRegistry::get('instanciasAssentamentoAfastamento');

      foreach($colecaoRetornada as $oRetorno) {
        if(!isset(self::$aColecao[$oRetorno->h12_codigo])) {
          self::add($oRepository->make($oRetorno->h12_codigo));
        }
      }
    }
    
    return self::$aColecao;
  }
}
