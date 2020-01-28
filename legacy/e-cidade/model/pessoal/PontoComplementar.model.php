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
 
require_once 'model/pessoal/Ponto.model.php';
require_once 'model/pessoal/RegistroPonto.model.php';

/**
 * Defição do Ponto Complementar de Um servidor
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
class PontoComplementar extends Ponto {

  /**
   * Nome da tabela do ponto complementar 
   */
  const TABELA = Ponto::COMPLEMENTAR;

  /**
   * Sigla da tabela do ponto complementar 
   */
  const SIGLA_TABELA = 'r47';

  /**
   * Construtor da classe
   *
   * @param Servidor $oServidor
   * @access public
   * @return void
   */
  public function __construct( Servidor $oServidor) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
  }
  
  /**
   * Funcao para retornar as movimentacoes das rubricas do ponto
   */
  public function getMovimentacoes( $iCodigoRubrica = null ) {
    
  }

  public function getRubricas() {
  }

  /**
   * Retorna as Rubricas marcadas como Automática para geração do Ponto Complementar
   *
   * @static
   * @access public
   * @return Rubrica[]
   */
  public static function getRubricasAutomaticas() {

    require_once 'model/pessoal/RubricaRepository.model.php';

    $aRetorno     = array();
    $iInstituicao = db_getsession("DB_instit");
    $oDaoRubricas = db_utils::getDao("rhrubricas");
    $sSql         = $oDaoRubricas->sql_query_file(null,null,'rh27_rubric','rh27_rubric','rh27_complementarautomatica is true and rh27_instit = '.$iInstituicao);
    $rsRubricas   = db_query($sSql);

    if ( !$rsRubricas ) {
      throw new DBException("Erro ao buscar Rubricas Automáticas. Erro técnico".pg_last_error());
    }

    foreach ( db_utils::getCollectionByRecord($rsRubricas) as $oDadosRubrica ) {
      
      $sRubrica   = $oDadosRubrica->rh27_rubric;
      $aRetorno[] = RubricaRepository::getInstanciaByCodigo($sRubrica, $iInstituicao);
    }

    return $aRetorno;
  }
  
  /**
   * Adiciona um registro ao ponto
   * 
   * @param RegistroPonto $oRegistro
   * @return bool
   */
  public function adicionarRegistro( RegistroPonto $oRegistroPonto, $lSubstituir = true) {
    
    /**
     * Adiciona o Registro normalmente
     */
    parent::adicionarRegistro($oRegistroPonto,  $lSubstituir);
    
    /**
     * Adiciona rubricas automaticas
     */
    $this->adicionarRubricasAutomaticas();
    
    return true;
  }
  
  /**
   * Adicionar rubricas automaticas
   * - Procura rubricas automaticas que estejam no ponto fixo e adiciona no ponto complementar
   * 
   * @return bool
   */
  public function adicionarRubricasAutomaticas() {
    
    /**
     * Rubricas automaticas
     * @var Array
     */
    $aRubricasAutomatica = PontoComplementar::getRubricasAutomaticas();
    $aCodigoRubricas     = array();
    
    /**
     * Percorre as rubricas automaticas pegando seu código
     */
    foreach ( $aRubricasAutomatica as $oRubrica ) {
    
      /**
       * caso já tenha lançado no ponto complementar não precisa buscar
       */
      if ( isset($this->aRegistros[$oRubrica->getCodigo()]) ) {
        continue;
      }
    
      $aCodigoRubricas[] = $oRubrica->getCodigo();
    }

    /**
     * Nenhuma rubrica automatica para incluir no ponto complementar
     * - Não existe rubrica automatica ou já incluidas 
     */
    if ( empty($aCodigoRubricas) ) {
      return true;
    }

    /**
     * Busca as rubricas automaticas no ponto fixo, adicionando-as ao ponto complementar.
     */
    $oPontoFixo = new PontoFixo($this->oServidor);
    $oPontoFixo->carregarRegistros($aCodigoRubricas);

    /**
     * Adiciona ao ponto complementar as rubricas automaticas do ponto fixo
     */
    foreach ( $oPontoFixo->getRegistros() as $oRegistro ) {
      parent::adicionarRegistro($oRegistro);
    }
    
    return true;
  }
  
  /**
   * Remove o registro do ponto
   * 
   * @param Registro $oRegistro
   * @return bool
   */
  public function removerRegistro( Rubrica $oRubrica) {
  
    parent::removerRegistro($oRubrica);    
    
    foreach ( $this->getRegistros() as $oRegistroPonto ) {
      $oRubrica = $oRegistroPonto->getRubrica();

      if ( !$oRubrica->isAutomaticaComplementar() ) {
        return true;
      }
    }
    
    $this->limpar();
    $this->aRegistros = array();
    return true;
  }
  
  /**
   * Persiste os dados do ponto complementar.
   * 
   * @return bool
   */
  public function gerar() {
    
    db_utils::getDao("pontocom", false);

    $this->limpar();

    foreach ( $this->aRegistros as $oRegistro ) {

      $oDaoPontoComplementar = new cl_pontocom();
      $oDaoPontoComplementar->r47_anousu = $this->getServidor()->getAnoCompetencia();
      $oDaoPontoComplementar->r47_mesusu = $this->getServidor()->getMesCompetencia();
      $oDaoPontoComplementar->r47_regist = $this->getServidor()->getMatricula();
      $oDaoPontoComplementar->r47_rubric = $oRegistro->getRubrica()->getCodigo(); 
      $oDaoPontoComplementar->r47_valor  = "{$oRegistro->getValor()}";      //HACK para os Dao
      $oDaoPontoComplementar->r47_quant  = "{$oRegistro->getQuantidade()}"; //HACK para os Dao
      $oDaoPontoComplementar->r47_lotac  = $this->getServidor()->getCodigoLotacao(); 
      $oDaoPontoComplementar->r47_instit = $this->getServidor()->getInstituicao()->getSequencial();
      $oDaoPontoComplementar->incluir( $this->getServidor()->getAnoCompetencia(),
                                       $this->getServidor()->getMesCompetencia(),
                                       $this->getServidor()->getMatricula(),
                                       $oRegistro->getRubrica()->getCodigo() );
      
      if ( $oDaoPontoComplementar->erro_status == '0' ) {
        throw new DBException($oDaoPontoComplementar->erro_msg);
      }
    }
    
    return true;
  }

}
