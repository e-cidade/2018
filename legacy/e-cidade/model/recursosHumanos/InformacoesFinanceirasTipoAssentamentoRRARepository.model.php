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
 * Classe para manipulacao de configuracoes de informacoes financeiras
 *
 * @author   Renan Pigato Silva renan.silva@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbrenan.silva $
 * @version  $Revision: 1.6 $
 */

class InformacoesFinanceirasTipoAssentamentoRRARepository {

  const MENSAGEM = "recursoshumanos.rh.InformacoesFinanceirasTipoAssentamentoRRARepository.";

  /**
   * Representa a instancia da Classe de Informacoes financeiras
   *
   * @var InformacoesFinanceirasTipoAssentamentoRRARepository
   */
  private static $oInstance;

  /**
   * Representa uma colecao de informacoes financeiras de RRA
   *
   * @var $aInformacoesFinanceirasRRA;
   */
  private static $aInformacoesFinanceirasRRA = array();


  /**
    * Construtor/Clona a classe de Informacoes Financeiras de um Tipo de Assentamento
    */
  private function  __construct() {}

  /**
    * Construtor/Clona a classe de Informacoes Financeiras de um Tipo de Assentamento
    */
  private function __clone() {}

  /**
   * Retorna a instância do repository
   *
   * @access public
   * @return InformacoesFinanceirasTipoAssentamentoRRA
   */
  public function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new InformacoesFinanceirasTipoAssentamentoRRA();
    }

    return self::$oInstance;
  }


  /**
   * Constroi um objeto de configuracão de informacoes financeiras de um tipo de assentamento de RRA
   *
   * @access private
   * @param  TipoAssentamento $oTipoAssentamento
   * @return InformacoesFinanceirasTipoAssentamentoRRA
   */
  private function make(TipoAssentamento  $oTipoAssentamento ) {

    if (!$oTipoAssentamento->getSequencial() ) {
      throw new ParameterException( _M(self::MENSAGEM ."tipo_assentamento_sem_codigo_definido") );
    }

    $oDaoTipoassefinanceiroRRA    = new cl_tipoassefinanceirorra;
    $sWhereTipoassefinanceiroRRA  = "     rh172_tipoasse = {$oTipoAssentamento->getSequencial()}";
    $sWhereTipoassefinanceiroRRA .= " and rh172_instit   = ". db_getsession('DB_instit');
    $sSqlTipoassefinanceiroRRA    = $oDaoTipoassefinanceiroRRA->sql_query_file(null, "*", null, $sWhereTipoassefinanceiroRRA);

    $rsTipoassefinanceiroRRA = db_query($sSqlTipoassefinanceiroRRA);

    if ( !$rsTipoassefinanceiroRRA ) {
      throw new DBException(_M(self::MENSAGEM . "erro_ao_buscar_informacoes_financeiras_rra"));
    }

    if( pg_num_rows($rsTipoassefinanceiroRRA) == 0 ) {
      throw new BusinessException(_M(self::MENSAGEM . "configuracao_tipo_assentamento_vazio"));
    }

    $oDados                                     = db_utils::fieldsMemory($rsTipoassefinanceiroRRA, 0);

    $oInformacoesFinanceirasTipoAssentamentoRRA = new InformacoesFinanceirasTipoAssentamentoRRA();
    $oInformacoesFinanceirasTipoAssentamentoRRA->setSequencial($oDados->rh172_sequencial);
    $oInformacoesFinanceirasTipoAssentamentoRRA->setTipoAssentamento($oTipoAssentamento);
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaProvento(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricaprovento));
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaIrrf(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricairrf));
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaPrevidencia(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricaprevidencia));
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaPensao(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricapensao));
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaParcelaDeducao(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricaparceladeducao));
    $oInformacoesFinanceirasTipoAssentamentoRRA->setRubricaMolestia(RubricaRepository::getInstanciaByCodigo($oDados->rh172_rubricamolestia));

    return $oInformacoesFinanceirasTipoAssentamentoRRA;
  }

  /**
   * Adiciona uma configuracao financeira de um tipo de assentamento
   *
   * @access private
   * @param InformacoesFinanceirasTipoAssentamentoRRA $oInformacoesFinanceirasTipoAssentamentoRRA
   */
  private function adicionar(InformacoesFinanceirasTipoAssentamentoRRA $oInformacoesFinanceirasTipoAssentamentoRRA) {
    $oRepository = self::getInstance();
    $oRepository->aInformacoesFinanceirasRRA[$oInformacoesFinanceirasTipoAssentamentoRRA->getSequencial()] = $oInformacoesFinanceirasTipoAssentamentoRRA;
    return $oInformacoesFinanceirasTipoAssentamentoRRA;
  }

  /**
   * Retorna uma instancia de informacoes financeiras de tipo de assentamento de RRA
   *
   * @static
   * @access public
   * @return InformacoesFinanceirasTipoAssentamentoRRA
   */
  public static function getInstanciaBySequencial($iSequencial) {

    if ( !array_key_exists($iSequencial, self::$aInformacoesFinanceirasRRA ) ) {




      self::adicionar(self::make());
    }

    return self::$aInformacoesFinanceirasRRA[$iSequencial];
  }

  /**
   * Retorna uma instancia de informacoes financeiras de tipo de assentamento de RRA
   *
   * @static
   * @access public
   * @return InformacoesFinanceirasTipoAssentamentoRRA
   */
  public static function getInstanciaByTipoAssentamento(TipoAssentamento $oTipoAssentamento) {

    if(count(self::$aInformacoesFinanceirasRRA) > 0) {

      foreach (self::$aInformacoesFinanceirasRRA as $oInformacoesFinanceiras) {
        if ($oInformacoesFinanceiras->getTipoAssentamento()->getSequencial() == $oTipoAssentamento->getSequencial()) {
          return $oInformacoesFinanceiras;
        }
      }
    }

    return self::adicionar(self::make($oTipoAssentamento));
  }

  /**
   * Persiste na base de dados uma configuracao de tipo de assentamento de RRA
   *
   * @static
   * @access public
   */
  public static function persist(InformacoesFinanceirasTipoAssentamentoRRA $oInformacoesFinanceirasTipoAssentamentoRRA) {

    $oDadosTipoassefinanceirorra = new cl_tipoassefinanceirorra();

    $oDadosTipoassefinanceirorra->rh172_tipoasse               = $oInformacoesFinanceirasTipoAssentamentoRRA->getTipoAssentamento()->getSequencial();
    $oDadosTipoassefinanceirorra->rh172_rubricaprevidencia     = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaPrevidencia()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_rubricaprovento        = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaProvento()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_rubricapensao          = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaPensao()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_rubricairrf            = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaIrrf()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_rubricaparceladeducao  = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaParcelaDeducao()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_rubricamolestia        = $oInformacoesFinanceirasTipoAssentamentoRRA->getRubricaMolestia()->getCodigo();
    $oDadosTipoassefinanceirorra->rh172_instit                 = db_getsession("DB_instit");

    if($oInformacoesFinanceirasTipoAssentamentoRRA->getSequencial() == null) {
      $oDadosTipoassefinanceirorra->incluir(null);
      $oInformacoesFinanceirasTipoAssentamentoRRA->setSequencial($oDadosTipoassefinanceirorra->rh172_sequencial);
    } else { // Se nao for nulo segnifica que estou fazendo um update desta configuracao
      $oDadosTipoassefinanceirorra->alterar($oInformacoesFinanceirasTipoAssentamentoRRA->getSequencial());
    }

    if($oDadosTipoassefinanceirorra->erro_status == '0') {
      throw new DBException(self::MENSAGEM. "erro_persistir_informacoes_financeiras_rra"."\n\n".$oDadosTipoassefinanceirorra->erro_msg);
    }

    return $oInformacoesFinanceirasTipoAssentamentoRRA;
  }

  /**
   * Retorna a Rubrica de Provento configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaProvento(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaProvento();
  }

  /**
   * Retorna a Rubrica de Irrf configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaIrrf(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaIrrf();
  }

  /**
   * Retorna a Rubrica de Previdencia configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaPrevidencia(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaPrevidencia();
  }

  /**
   * Retorna a Rubrica de Pensao configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaPensao(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaPensao();
  }
  
  /**
   * Retorna a Rubrica da Parcela Isenta configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaParcelaIsenta(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaParcelaDeducao();
  }

  /**
   * Retorna a Rubrica de Molestia configurada em um tipo de assentamento
   *
   * @static
   * @access public
   * @return Rubrica
   */
  public static function getRubricaMolestia(TipoAssentamento $oTipoAssentamento) {
    return self::getInstanciaByTipoAssentamento($oTipoAssentamento)->getRubricaMolestia();
  }
}
