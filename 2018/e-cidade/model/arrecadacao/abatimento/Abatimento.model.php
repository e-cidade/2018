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

require_once 'model/configuracao/InstituicaoRepository.model.php';
require_once 'model/configuracao/UsuarioSistema.model.php';
require_once 'std/DBDate.php';

/**
 * Abatimento
 * 
 * @abstract
 * @package    Arrecadacao 
 * @subpackage Abatimento 
 * @version    $id$
 * @author     Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
abstract class Abatimento {

  /**
   * Situacoa do abatimento
   */
  const SITUACAO_ATIVO     = 1;
  const SITUACAO_CANCELADO = 2;

  /**
   * Tipo do abatimento 
   */
  const TIPO_PAGAMENTO_PARCIAL = 1;
  const TIPO_DESCONTO          = 2;
  const TIPO_CREDITO           = 3;
  const TIPO_COMPENSACAO       = 4;

  /**
   * Codigo do abatimento
   * - sequencial da tabela
   * 
   * @var numeric
   * @access private
   */
  private $iCodigo;
  
  /**
   * Tipo de abatimento:
   * 1 - PAGAMENTO PARCIAL
   * 2 - DESCONTO
   * 3 - CRÉDITO
   * 4 - COMPENSACAO
   * 
   * @var integer
   * @access private
   */
  private $iTipoAbatimento;

  /**
   * Data de lançamento do abatimento
   * 
   * @var mixed
   * @access private
   */
  private $oDataLancamento;

  /**
   * Hora que foi lançado abatimento
   * 
   * @var string
   * @access private
   */
  private $sHoraLancamento;

  /**
   * Usuario que lancou abatimento
   * 
   * @var UsuarioSistema
   * @access private
   */
  private $oUsuario;

  /**
   * Instituicao que pertence o abatimento
   * 
   * @var Instituicao
   * @access private
   */
  private $oInstituicao;

  /**
   * Valor do abatimento
   * 
   * @var numeric
   * @access private
   */
  private $nValor;

  /**
   * Percentual do valor do abatimento sobre o valor total do debito 
   * 
   * @var numeric
   * @access private
   */
  private $nPercentual;

  /**
   * Valor disponivel do abatimento
   * 
   * @var numeric
   * @access private
   */
  private $nValorDisponivel;

  /**
   * Situacao do abatimento
   * 1 - ATIVO
   * 2 - CANCELADO
   * 
   * @var integer
   * @access private
   */
  private $iSituacao;

  /**
   * Observação do abatimento
   * @var string
   */
  private $sObrservacao;  
  
  /**
   * Construtor da classe
   *
   * @access public
   * @return void
   */
  public function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return false;
    }

    $oDaoAbatimento = db_utils::getDao('abatimento');
    $sSqlAbatimento = $oDaoAbatimento->sql_query_file($iCodigo);
    $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);

    /**
     * Erro ao buscar abatimento
     * - Não encontrou abatimento ou erro na query
     */
    if ( $oDaoAbatimento->erro_status == "0" ) {
      throw new Exception($oDaoAbatimento->erro_msg);
    }

    $oAbatimento = db_utils::fieldsMemory($rsAbatimento, 0);

    $this->iCodigo          = $iCodigo;
    $this->iTipoAbatimento  = $oAbatimento->k125_tipoabatimento;
    $this->oDataLancamento  = new DBDate($oAbatimento->k125_datalanc);
    $this->sHoraLancamento  = $oAbatimento->k125_hora;
    $this->oUsuario         = new UsuarioSistema($oAbatimento->k125_usuario);
    $this->oInstituicao     = InstituicaoRepository::getInstituicaoByCodigo($oAbatimento->k125_instit);
    $this->nValor           = $oAbatimento->k125_valor;
    $this->nPercentual      = $oAbatimento->k125_perc;
    $this->nValorDisponivel = $oAbatimento->k125_valordisponivel;
    $this->iSituacao        = $oAbatimento->k125_abatimentosituacao;
  }

  /**
   * Retorna o codigo do abatimento
   *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define tipo de abatimento
   * 1 - PAGAMENTO PARCIAL
   * 2 - DESCONTO
   * 3 - CRÉDITO
   * 4 - COMPENSACAO
   *
   * @param integer $iTipoAbatimento
   * @access public
   * @return void
   */
  public function setTipoAbatimento($iTipoAbatimento) {
    $this->iTipoAbatimento = $iTipoAbatimento;
  }

  /**
   * Retorna o tipo de abatimento
   * 1 - PAGAMENTO PARCIAL
   * 2 - DESCONTO
   * 3 - CRÉDITO
   * 4 - COMPENSACAO
   *
   * @access public
   * @return integer
   */
  public function getTipoAbatimento() {
    return $this->iTipoAbatimento;
  }

  /**
   * Define a data de lancamento do abatimento
   *
   * @param DBDate $oDataLancamento
   * @access public
   * @return void
   */
  public function setDataLancamento(DBDate $oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;
  }

  /**
   * Retorna a data de lancamento do abatimento
   *
   * @access public
   * @return DBDate
   */
  public function getDataLancamento() {
    return $this->oDataLancamento;
  }

  /**
   * Define a hora de lancamento do abatimento
   *
   * @param string $sHoraLancamento
   * @access public
   * @return void
   */
  public function setHoraLancamento($sHoraLancamento) {
    $this->sHoraLancamento = $sHoraLancamento;
  }

  /**
   * Retorna a hora de lancamento do abatimento
   *
   * @access public
   * @return string
   */
  public function getHoraLancamento() {
    return $this->sHoraLancamento;
  }

  /**
   * Define o usuario que lancou abatimento
   *
   * @param UsuarioSistema $oUsuario
   * @access public
   * @return void
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Retorna o usuario que lancou abatimento
   *
   * @access public
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Define a instituicao que pertence o abatimento
   *
   * @param Instituicao $oInstituicao
   * @access public
   * @return void
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna a instituicao do abatimento
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Define o valor do abatimento
   *
   * @param number $nValor
   * @access public
   * @return void
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  } 

  /**
   * Retorna o valor do abatimento
   *
   * @access public
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o percentual do abatimento sobre o valor do debito
   *
   * @param numeric $nPercentual
   * @access public
   * @return void
   */
  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }
  
  /**
   * Retorna o percentual do abatimento sobre o valor do debito
   *
   * @access public
   * @return numeric
   */
  public function getPercentual() {
    return $this->nPercentual;
  }

  /**
   * Define o valor do abatimento disponivel
   *
   * @param numeric $nValorDisponivel
   * @access public
   * @return void
   */
  public function setValorDisponivel($nValorDisponivel) {
    $this->nValorDisponivel = $nValorDisponivel;
  }

  /**
   * Retorna o valor disponivel do abatimento
   *
   * @access public
   * @return numeric
   */
  public function getValorDisponivel() {
    return $this->nValorDisponivel;
  }

  /**
   * Define a situacao do abatimento
   * 1 - ATIVO
   * 2 - CANCELADO
   *
   * @param integer $iSituacao
   * @access public
   * @return void
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna a situacao do abatimento
   * 1 - ATIVO
   * 2 - CANCELADO
   *
   * @access public
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }
  
  /**
   * Define a observação
   * @param string $sObservacao
   * @return void
   */
  public function setObservacao($sObservacao) {
  	$this->sObrservacao = $sObservacao;
  }
  
  /**
   * Retorna a observação do abatimento
   * @return string
   */
  public function getObservacao() {
  	return $this->sObrservacao;
  }

  /**
   * Salva ou altera o abatimento
   *
   * @access public
   * @return boolean
   */
  public function salvar() {

    if( !db_utils::inTransaction() ) {
			throw new Exception("Nenhuma Transação com o banco Ativa");
		}

    $oDaoAbatimento = new cl_abatimento();

    $oDaoAbatimento->k125_tipoabatimento     = $this->getTipoAbatimento();
    $oDaoAbatimento->k125_datalanc           = $this->getDataLancamento()->getDate();
    $oDaoAbatimento->k125_hora               = $this->getHoraLancamento();
    $oDaoAbatimento->k125_usuario            = $this->getUsuario()->getIdUsuario();
    $oDaoAbatimento->k125_instit             = $this->getInstituicao()->getSequencial();
    $oDaoAbatimento->k125_valor              = $this->getValor();
    $oDaoAbatimento->k125_perc               = $this->getPercentual();
    $oDaoAbatimento->k125_valordisponivel    = $this->getValorDisponivel();
    $oDaoAbatimento->k125_abatimentosituacao = $this->getSituacao();
    $oDaoAbatimento->k125_observacao         = $this->getObservacao();
    
    /**
     * Incluir abatimento 
     */
    if ( empty($this->iCodigo) ) {

      $oDaoAbatimento->k125_sequencial = null;
      $oDaoAbatimento->incluir(null);
      $this->iCodigo = $oDaoAbatimento->k125_sequencial;
    } 
    
    /**
     * Alterar abatimento 
     */
    if ( !empty($this->iCodigo) ) {

      $oDaoAbatimento->k125_sequencial = $this->iCodigo;
      $oDaoAbatimento->alterar($this->iCodigo);
    }

    /**
     * Erro ao incluir ou alterar abatimento 
     */
    if ( $oDaoAbatimento->erro_status == '0' ) {
      throw new Exception($oDaoAbatimento->erro_msg);
    }

    return true;
  }
  
}