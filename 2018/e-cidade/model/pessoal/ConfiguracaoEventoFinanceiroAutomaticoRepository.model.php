<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
* Repositório para manipulação de configuração de eventos financeiros
*
* @package pessoal
* @author Renan Silva <renan.silva@dbseller.com.br>
*/
class ConfiguracaoEventoFinanceiroAutomaticoRepository {
	
	/**
   * Array com instancias de configuração de eventos financeiros automáticos
   *
   * @static
   * @var Array
   * @access private
   */
  static private $aColecao = array();

  /**
   * Representa a instancia a classe
   * 
   * @var ConfiguracaoEventoFinanceiroAutomaticoRepository
   * @access private
   */
  private static   $oInstance;

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
   * @return ConfiguracaoEventoFinanceiroAutomaticoRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {

      $sClasse  = get_class();
      self::$oInstance = new \ConfiguracaoEventoFinanceiroAutomaticoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona a coleção uma configuração de evento financeiro automático
   * 
   * @param ConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico
   */
  protected function add(ConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico) {

    $oRepository = self::getInstance();
    $oRepository->aColecao[$oConfiguracaoEventoFinanceiroAutomatico->getCodigo()] = $oConfiguracaoEventoFinanceiroAutomatico;
  }

  /**
   * Monta um objeto ConfiguracaoEventoFinanceiroAutomatico
   * 
   * @param  Integer $iCodigo
   * 
   * @return ConfiguracaoEventoFinanceiroAutomatico
   */
  protected function make($iCodigo) {

  	if(empty($iCodigo)) {
  		throw new \ParameterException("Não foi informado o código sequencial para a configuração de evento financeiro automático.");
  	}

  	$oDaoEventoFinanceiroAutomatico = db_utils::getDao('eventofinanceiroautomatico');
  	$sSqlEventoFinanceiroAutomatico = $oDaoEventoFinanceiroAutomatico->sql_query($iCodigo);
  	$rsEventoFinanceiroAutomatico   = db_query($sSqlEventoFinanceiroAutomatico);

  	if(!$rsEventoFinanceiroAutomatico) {
  		throw new \DBException("Ocorreu um erro ao buscar a configuração de evento financeiro automático");
  	}

  	if(pg_num_rows($rsEventoFinanceiroAutomatico) == 0) {
  		throw new \BusinessException("Não foi encontrada a configuração para o código informado");
  	}

		$oConfiguracaoEventoFinanceiroAutomatico = new \ConfiguracaoEventoFinanceiroAutomatico;
		$oStdEventFinanceiroAutomatico           = db_utils::fieldsMemory($rsEventoFinanceiroAutomatico, 0);

		$oInstituicao = InstituicaoRepository::getInstituicaoByCodigo($oStdEventFinanceiroAutomatico->rh181_instituicao); 
		$oRubrica     = RubricaRepository::getInstanciaByCodigo($oStdEventFinanceiroAutomatico->rh181_rubrica, 
							    	 																				$oStdEventFinanceiroAutomatico->rh181_instituicao);

		$oConfiguracaoEventoFinanceiroAutomatico->setCodigo     ($oStdEventFinanceiroAutomatico->rh181_sequencial);
		$oConfiguracaoEventoFinanceiroAutomatico->setDescricao  ($oStdEventFinanceiroAutomatico->rh181_descricao);
		$oConfiguracaoEventoFinanceiroAutomatico->setRubrica    ($oRubrica);
		$oConfiguracaoEventoFinanceiroAutomatico->setMes        ($oStdEventFinanceiroAutomatico->rh181_mes);
		$oConfiguracaoEventoFinanceiroAutomatico->setSelecao    (new \Selecao($oStdEventFinanceiroAutomatico->rh181_selecao));
		$oConfiguracaoEventoFinanceiroAutomatico->setInstituicao($oInstituicao);

		return $oConfiguracaoEventoFinanceiroAutomatico;
  }

  /**
   * Retorna uma instancia da classe ConfiguracaoEventoFinanceiroAutomatico
   * 
   * @param  Integer $iCodigo
   * @return ConfiguracaoEventoFinanceiroAutomatico
   */
  public function getInstanciaPorCodigo($iCodigo) {

    $oRepository = self::getInstance();

    if(!isset($oRepository->aColecao[$iCodigo])) {
      self::add($oRepository->make($iCodigo));
    }

    return $oRepository->aColecao[$iCodigo];
  }

  /**
   * Retorna uma coleção de instâncias da classe ConfiguracaoEventoFinanceiroAutomatico
   *
   * @param  \DBCompetencia    $oCompetencia
   * @param  \Instituicao      $oInstituicao
   * @return \ConfiguracaoEventoFinanceiroAutomatico[]
   * @throws \DBException
   */
  public static function getConfiguracoesPorMesInstituicao($oCompetencia = null, $oInstituicao = null) {

    $aConfiguracoes                   = array();
    $aWhereEventoFinanceiroAutomatico = array();

    if(empty($oInstituicao)) {
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    if(! $oInstituicao instanceof Instituicao) {
      throw new ParameterException("Instituição informada não é uma Instituição válida.");
    }
    
    if(!empty($oCompetencia)) {

      $aWhereEventoFinanceiroAutomatico[] = " rh181_mes = ". $oCompetencia->getMes();

      if(!$oCompetencia instanceof DBCompetencia) {
        throw new ParameterException("A competência informada não é válida.");
      }
    }

    $oDaoEventoFinanceiroAutomatico     = db_utils::getDao('eventofinanceiroautomatico');
    $aWhereEventoFinanceiroAutomatico[] = " rh181_instituicao = ". $oInstituicao->getCodigo();
    $sSqlEventoFinanceiroAutomatico     = $oDaoEventoFinanceiroAutomatico->sql_query(null, 'rh181_sequencial as codigo', null, implode(" and ", $aWhereEventoFinanceiroAutomatico));
    $rsEventoFinanceiroAutomatico       = db_query($sSqlEventoFinanceiroAutomatico);

    if(!$rsEventoFinanceiroAutomatico) {
      throw new \DBException("Ocorreu um erro ao buscar as configurações de evento financeiro automático");
    }

    $oSelfRepository = ConfiguracaoEventoFinanceiroAutomaticoRepository::getInstance();
    $aConfiguracoes  = db_utils::makeCollectionFromRecord($rsEventoFinanceiroAutomatico, function($oStdConfiguracaoEventoFinanceiro) use ($oSelfRepository) {
      return $oSelfRepository->getInstanciaPorCodigo($oStdConfiguracaoEventoFinanceiro->codigo);
    });
    
    return $aConfiguracoes;
  }

  /**
   * Persiste na base de dados uma configuração de evento financeiro automático
   *
   * @param  \oConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico
   * @return \ConfiguracaoEventoFinanceiroAutomatico
   * @throws \DBException
   */
  public static function persist(ConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico) {

    $oDaoEventoFinanceiroAutomatico = db_utils::getDao('eventofinanceiroautomatico');

    $oDaoEventoFinanceiroAutomatico->rh181_sequencial  = $oConfiguracaoEventoFinanceiroAutomatico->getCodigo();
    $oDaoEventoFinanceiroAutomatico->rh181_descricao   = addslashes($oConfiguracaoEventoFinanceiroAutomatico->getDescricao());
    $oDaoEventoFinanceiroAutomatico->rh181_rubrica     = $oConfiguracaoEventoFinanceiroAutomatico->getRubrica()->getCodigo();
    $oDaoEventoFinanceiroAutomatico->rh181_mes         = $oConfiguracaoEventoFinanceiroAutomatico->getMes();
    $oDaoEventoFinanceiroAutomatico->rh181_selecao     = $oConfiguracaoEventoFinanceiroAutomatico->getSelecao()->getCodigo();
    $oDaoEventoFinanceiroAutomatico->rh181_instituicao = $oConfiguracaoEventoFinanceiroAutomatico->getInstituicao()->getCodigo();

    if(empty($oDaoEventoFinanceiroAutomatico->rh181_sequencial)) {
      
      if($oDaoEventoFinanceiroAutomatico->incluir(null)) {
        $oConfiguracaoEventoFinanceiroAutomatico->setCodigo($oDaoEventoFinanceiroAutomatico->rh181_sequencial);
      }
    } else {
      $oDaoEventoFinanceiroAutomatico->alterar($oDaoEventoFinanceiroAutomatico->rh181_sequencial);
    }

    if($oDaoEventoFinanceiroAutomatico->erro_status == "0") {
      throw new DBException($oDaoEventoFinanceiroAutomatico->erro_msg);
    }

    return $oConfiguracaoEventoFinanceiroAutomatico;
  }

  /**
   * Remover da base de dados uma configuração de evento financeiro automático
   *
   * @param  \oConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico
   * @return Boolean
   * @throws \DBException
   */
  public static function remover(ConfiguracaoEventoFinanceiroAutomatico $oConfiguracaoEventoFinanceiroAutomatico) {
    
    $oDaoEventoFinanceiroAutomatico = db_utils::getDao('eventofinanceiroautomatico');
    
    if(!$oDaoEventoFinanceiroAutomatico->excluir($oConfiguracaoEventoFinanceiroAutomatico->getCodigo())) {
      throw new DBException($oDaoEventoFinanceiroAutomatico->erro_msg);
    }

    return true;
  }
}