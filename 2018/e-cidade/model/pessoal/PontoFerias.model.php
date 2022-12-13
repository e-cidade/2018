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
 
require_once(modification('model/pessoal/Ponto.model.php'));
require_once(modification('libs/db_libpessoal.php'));

/**
 * Classe para ponto de Ferias
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
class PontoFerias extends Ponto {

  /**
   * Nome da tabela para ponto de ferias 
   */
  const TABELA = Ponto::FERIAS;

  /**
   * Sigla da tabela do ponto de ferias 
   */
  const SIGLA_TABELA = 'r29';

  /**
   * Constantes para tipo de Pagamento de férias 
   */
  const TIPO_PAGAMENTO_FERIAS       = "F";
  const TIPO_PAGAMENTO_ADIANTAMENTO = "D";
  const TIPO_PAGAMENTO_ABONO        = "A";

  /**
   * Instancia do Objeto Ferias 
   * 
   * @var    Ferias
   * @access private
   */
  private $oPeriodoAquisitivoFerias;

  /**
   * Instancia do Objeto PeriodoGozoFerias
   * @var PeriodoGozoFerias
   */
  private $oPeriodoGozoFerias;

  /**
   * Construtor da classe
   *
   * @param Servidor $oServidor
   * @access public
   */
  public function __construct( Servidor $oServidor) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;
    $this->sSigla  = self::SIGLA_TABELA;
  }

  /**
   * Define a Instancia de Férias 
   */
  public function setPeriodoAquisitivoFerias( PeriodoAquisitivoFerias $oPeriodoAquisitivoFerias ) {
    $this->oPeriodoAquisitivoFerias = $oPeriodoAquisitivoFerias;
  }

  /**
   * Seta a instancia de Periodo Gozo Ferias
   * @param PeriodoGozoFerias $oPeriodoGozoFerias
   */
  public function setPeriodoGozoFerias( PeriodoGozoFerias $oPeriodoGozoFerias) {
    $this->oPeriodoGozoFerias = $oPeriodoGozoFerias;
  }

  /**
   * Gerar ponto de ferias
   *
   * @access public
   * @return bool
   * @throws DBException
   * @throws Exception
   */
  public function gerar() {

    /**
     * require do DAO 
     */
    db_utils::getDao("pontofe", false);

    /**
     * Limpa registro do ponto do servidor 
     */
    $this->limpar();

    /**
     * Inclui rubricas padroes do ponto de ferias
     * - R930
     * - R931
     * - R940
     */
    $this->adicionarRubricasPadrao();

    /**
     * Percorre os registros do ponto e inclui na tabela de ferias(pontofe)
     */
    foreach ( $this->aRegistros as $oRegistro ) {

      $oDaoPontoFe = new cl_pontofe();
      $oDaoPontoFe->r29_anousu = $this->getServidor()->getAnoCompetencia();
      $oDaoPontoFe->r29_mesusu = $this->getServidor()->getMesCompetencia();
      $oDaoPontoFe->r29_regist = $this->getServidor()->getMatricula();
      $oDaoPontoFe->r29_rubric = $oRegistro->getRubrica()->getCodigo();
      $oDaoPontoFe->r29_valor  = "{$oRegistro->getValor()}";
      $oDaoPontoFe->r29_quant  = "{$oRegistro->getQuantidade()}";
      $oDaoPontoFe->r29_lotac  = $this->getServidor()->getCodigoLotacao();
      $oDaoPontoFe->r29_media  = '0'; 
      $oDaoPontoFe->r29_calc   = '0'; 
      $oDaoPontoFe->r29_tpp    = $oRegistro->getTipoPagamento();
      $oDaoPontoFe->r29_instit = $this->getServidor()->getInstituicao()->getSequencial();

      $oDaoPontoFe->incluir($this->getServidor()->getAnoCompetencia(), 
                            $this->getServidor()->getMesCompetencia(), 
                            $this->getServidor()->getMatricula(), 
                            $oRegistro->getRubrica()->getCodigo(),
                            $oRegistro->getTipoPagamento()); 
      
      /**
       * Erro ao incluir ponto de ferias 
       */
      if ( $oDaoPontoFe->erro_status == '0' ) {
        throw new DBException($oDaoPontoFe->erro_msg);
      }

    }
   
    return true;
  }

  /**
   * Função para retornar as movimentações das rubricas do ponto
   */
  public function getMovimentacoes( $sRubrica = null) {

  }

  /**
   * Funções para retornar as rubricas utilizadas no ponto
   */
  public function getRubricas() {

  }

  /**
   * Adiciona as rubricas de férias padrão para todos os servidores
   *
   * @access public
   * @return bool
   * @throws BusinessException
   * @throws Exception
   */
  public function adicionarRubricasPadrao() {
  	
  	$aRubricasPadrao = array(PontoFerias::TIPO_PAGAMENTO_ADIANTAMENTO => RubricaRepository::getInstanciaByCodigo('R940'), 
  													 PontoFerias::TIPO_PAGAMENTO_FERIAS       => RubricaRepository::getInstanciaByCodigo('R931'), 
  													 PontoFerias::TIPO_PAGAMENTO_ABONO        => RubricaRepository::getInstanciaByCodigo('R932'));
  	



  	foreach ($aRubricasPadrao as $sTipoPagamento => $oRubricaPadrao) {

      $lInclui = false;

      foreach ( $this->getRegistros() as $oRegistroPontoFerias ) {

        if ( $oRegistroPontoFerias->getTipoPagamento() == $sTipoPagamento ) {

          $lInclui = true;
          break;
        }
      }
      
      if ( !$lInclui ) {
        continue;
      }

  		$oRegistroPonto = new RegistroPontoFerias();
  		$oRegistroPonto->setRubrica($oRubricaPadrao);
  		$oRegistroPonto->setServidor($this->getServidor());
      $oRegistroPonto->setTipoPagamento($sTipoPagamento);
  		$oRegistroPonto->setQuantidade('1');
  		$oRegistroPonto->setValor('0');
  		
  		if (!$this->adicionarRegistro($oRegistroPonto)) {
  			throw new Exception('Erro ao adicionar rubricas padrões para o ponto de férias.');
  		}
  		
  	}
  	
  	return true;
  }
   
  /**
   * Retorna a composicao do ponto
   * - caso o servidor possuir mais de um periodo de gozo
   *   com pagamento na competencia atual, a composicao é 
   *   os registros do ponto por periodo
   * - É gravado no ponto de ferias o total da composicao
   * - Agrupando por codigo da rubrica e tipo de pagamento(tpp)
   *
   * @access public
   * @return ComposicaoPontoFerias
   */
  public function getComposicao() {
    return new ComposicaoPontoFerias( $this->getServidor() );
  }

  /**
   * Carrega em memória os registros do ponto do servidor guardados na tabela.
   *
   * @param mixed $mRubrica - array de rubricas ou string com codigo da rubrica
   * @return bool|void
   * @throws BusinessException
   * @throws DBException
   * @access public
   */
  public function carregarRegistros( $mRubrica = null ) {
     
    $oDaoPonto = new cl_pontofe();
    $sWhere    = "     r29_regist = {$this->getServidor()->getMatricula()}                    ";
    $sWhere   .= " and r29_anousu = {$this->getServidor()->getAnoCompetencia()}               ";
    $sWhere   .= " and r29_mesusu = {$this->getServidor()->getMesCompetencia()}               ";
    $sWhere   .= " and r29_instit = {$this->getServidor()->getInstituicao()->getSequencial()} ";
    
    /**
     * Informou rubrica, adiciona ao where
     */
    if (! empty( $mRubrica )) {
      
      $sWhere .= " and r29_rubric ";
      
      /**
       * Rubrica é um array
       */
      if (is_array( $mRubrica )) {
        
        $aRubricas = array ();
        foreach ( $mRubrica as $sRubrica ) {
          $aRubricas [] = "'$sRubrica'";
        }
        
        $sWhere .= " in (" . implode( ", ", $aRubricas ) . ")";
      } else {
        
        /**
         * $mRubrica é uma string
         */
        $sWhere .= " = '{$mRubrica}' ";
      }
    }
    
    $sSql        = $oDaoPonto->sql_query_file( null, null, null, null, null, " r29_rubric as codigo_rubrica,
                                                                              r29_valor  as valor_rubrica,
                                                                              r29_quant  as quantidade_rubrica,
                                                                              r29_tpp    as tipo_pagamento  ",
                                                                              null, 
                                                                              $sWhere );
    $rsRegistros = db_query( $sSql );
    
    if ( !$rsRegistros ) {
      throw new DBException( "Erro ao Buscar dados dos registros do ponto." . pg_last_error() );
    }
    
    for($iEvento = 0; $iEvento < pg_num_rows( $rsRegistros ); $iEvento++) {
      
      $oDadosRegistro = db_utils::fieldsMemory( $rsRegistros, $iEvento );
      $oRegistro      = new RegistroPontoFerias();
      $oRubrica       = RubricaRepository::getInstanciaByCodigo( $oDadosRegistro->codigo_rubrica );
      $oRegistro->setServidor( $this->oServidor );
      $oRegistro->setRubrica( $oRubrica );
      $oRegistro->setQuantidade( $oDadosRegistro->quantidade_rubrica );
      $oRegistro->setValor( $oDadosRegistro->valor_rubrica );
      $oRegistro->setTipoPagamento($oDadosRegistro->tipo_pagamento);
      $this->aRegistros [$oRubrica->getCodigo().$oDadosRegistro->tipo_pagamento] = $oRegistro;
    }
    
    return true;
  }
  /**
   * Adiciona um registro ao ponto
   * @param RegistroPontoFerias $oRegistro
   * @return bool
   */
  public function adicionarRegistro( RegistroPontoFerias $oRegistroPonto, $lSubstituir = true ) {

    $sCodigoRubrica = $oRegistroPonto->getRubrica()->getCodigo().$oRegistroPonto->getTipoPagamento();

    if ( array_key_exists($sCodigoRubrica, $this->aRegistros) && !$lSubstituir) {

      $oRegistroAtual = $this->aRegistros[$sCodigoRubrica];
      $nQuantidade    = $oRegistroAtual->getQuantidade() + $oRegistroPonto->getQuantidade();
      $nValor         = $oRegistroAtual->getValor()      + $oRegistroPonto->getValor();

      $oRegistroAtual->setQuantidade($nQuantidade);
      $oRegistroAtual->setValor($nValor);
      return true;
    }

    $this->aRegistros[$sCodigoRubrica] = $oRegistroPonto;
    return true;
  }
}