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

/**
 * Dependencias
 */   
db_app::import('arrecadacao.boletos.RefactorGeracaoBoleto');
db_app::import('arrecadacao.boletos.RefactorImpressaoBoleto');

/**
 * Facade para geracao de boletos
 *
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 *
 * @version $
 */
class EmissaoBoleto {

  /**
   * CGM 
   * 
   * @var integer
   * @access protected
   */
  protected $iCodigoCgm = 0;

  /**
   * Inscricao 
   * 
   * @var integer
   * @access protected
   */
  protected $iInscricao = 0;
  
  /**
   * Matricula 
   * 
   * @var integer
   * @access protected
   */
  protected $iMatricula = 0;

  /**
   * Modelo de impressao do boleto 
   * 
   * @var integer
   * @access protected
   */
  protected $iModeloImpressao = 0;

  /**
   * TIpo de debito, k00_tipo 
   * 
   * @var integer
   * @access protected
   */
  protected $iTipoDebito = 0;

  /**
   * Array com numpre e numpar dos debitos 
   * 
   * @var array
   * @access protected
   */
  protected $aDebitos = array();

  /**
   * Numpre do boleto
   * numpre/numnov do recibo/carne 
   * 
   * @var integer
   * @access protected
   */
  protected $iNumpreBoleto = 0;

  /**
   * Data de operacao 
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataVencimento;

  /**
   * Caminho do Arquivo PDF
   * @var string
   * @access protected
   */
  protected $sCaminhoPdf = null;
 
  /**
   * Valor Corrigido 
   * 
   * @var number
   * @access protected
   */
  protected $nValorCorrigido = 0;

  /**
   * Valor do Historico 
   * 
   * @var number
   * @access protected
   */
  protected $nValorHistorico = 0;

  /**
   * Juros da Multa 
   * 
   * @var number
   * @access protected
   */
  protected $nJurosMulta = 0;
 
  /**
   * Codigo de Barras 
   * 
   * @var integer
   * @access protected
   */
  protected $iCodigoBarras = 0;
 
  /**
   * Linha Digitavel 
   * 
   * @var string
   * @access protected
   */
  protected $sLinhaDigitavel = null;
  
  /**
   * Força a data de vencimento
   * 
   * @var boolean
   * @access protected
   */
  protected $lForcaVencimento = false;
  
  /**
   * Processa desconto no recibo
   * 
   * @var boolean
   * @access protected
   */
  protected $lProcessaDescontoRecibo = false;
  
  /**
   * Novo recibo
   * 
   * @var boolean
   * @access protected
   */
  protected $lNovoRecibo = true;
  
  /**
   * Retorna se deve forçar o vencimento do recibo
   * 
   * @access public
   * @return boolean
   */
  public function getForcaVencimento() {
    return $this->lForcaVencimento;
  }
  
  /**
   * Seta se deve forçar o vencimento do recibo
   *
   * @param boolean $lForcaVencimento
   * @access public
   * @return void
   */
  public function setForcaVencimento($lForcaVencimento = false) {
    $this->lForcaVencimento = $lForcaVencimento;
  }
  
  /**
   * Retorna se deve processar o desconto no recibo
   *
   * @access public
   * @return boolean
   */
  public function getProcessaDescontoRecibo() {
    return $this->lProcessaDescontoRecibo;
  }
  
  /**
   * Seta se deve processar o desconto no recibo
   *
   * @param boolean $lProcessaDescontoRecibo
   * @access public
   * @return void
   */
  public function setProcessaDescontoRecibo($lProcessaDescontoRecibo = false) {
    $this->lProcessaDescontoRecibo = $lProcessaDescontoRecibo;
  }
  
  /**
   * Retorna se deve gerar o desconto no recibo
   *
   * @access public
   * @return boolean
   */
  public function getNovoRecibo() {
    return $this->lNovoRecibo;
  }
  
  /**
   * Seta se deve gerar o desconto no recibo
   *
   * @param boolean $lNovoRecibo
   * @access public
   * @return void
   */
  public function setNovoRecibo($lNovoRecibo = true) {
    $this->lNovoRecibo = $lNovoRecibo;
  }  
  
  /**
   * Construtor  
   * @todo criar lazzy load
   * 
   * @param integer $iNumpreBoleto 
   * @access public
   * @return void
   */
  public function __construct($iNumpreBoleto = 0) {

    if ( $iNumpreBoleto == 0 ) {
      return;
    }

    $this->iNumpreBoleto = $iNumpreBoleto; 
    $this->buscarDadosBoleto();
  }

  /**
   * Define o CGM do recibo
   * 
   * @param integer $iCodigoCgm 
   * @access public
   * @return void
   */
  public function setCodigoCgm($iCodigoCgm) {
    $this->iCodigoCgm = $iCodigoCgm;
  }

  /**
   * Retorna o CGM do boleto 
   * 
   * @access public
   * @return integer
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * Define a matricula do boleto
   * 
   * @param integer $iMatricula 
   * @access public
   * @return void
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula; 
  }

  /**
   * Retorna a matricula do boleto 
   * 
   * @access public
   * @return void
   */
  public function getMatricula() {
    return $this->iMatricula; 
  }

  /**
   * Define a inscricao do boleto  
   * 
   * @param mixed $iInscricao 
   * @access public
   * @return void
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao; 
  }

  /**
   * Retorna a inscricao 
   * 
   * @access public
   * @return integer
   */
  public function getInscricao() {
    return $this->iInscricao; 
  }

  /**
   * Define o tipo de debito 
   * 
   * @param integer $iTipoDebito 
   * @access public
   * @return void
   */
  public function setTipoDebito($iTipoDebito) {
    $this->iTipoDebito = $iTipoDebito; 
  }

  /**
   * Retorna o tipo de debito 
   * 
   * @access public
   * @return integer
   */
  public function getTipoDebito() {
    return $this->iTipoDebito; 
  }

  /**
   * Define modelo de impressao 
   * 
   * @param integer $iModeloImpressao 
   * @access public
   * @return void
   */
  public function setModeloImpressao($iModeloImpressao) {
    $this->iModeloImpressao = $iModeloImpressao; 
  }

  /**
   * Retorna o modelo de impressao 
   * 
   * @access public
   * @return integer
   */
  public function getModeloImpressao() {
    return $this->iModeloImpressao; 
  }

  /**
   * Define a data de operacao 
   * 
   * @param string $sDataOperacao 
   * @access public
   * @return void
   */
  public function setDataVencimento(DBDate $oDataVencimento) {
    $this->oDataVencimento = $oDataVencimento; 
  }

  /**
   * Retorna data de operaco 
   * 
   * @access public
   * @return DBDate
   */
  public function getDataOperacao() {
    return $this->oDataVencimento; 
  }
  
  /**
   * Retorna o numpre do boleto gerado
   *
   * @return integer
   * @access public
   */
  public function getNumpreBoleto() {   
    return $this->iNumpreBoleto;
  }
  
  /**
   * getCodigoBoleto 
   * 
   * @access public
   * @return void
   */
  public function getCodigoBoleto () {
    return $this->iCodigoBoleto;
  }

  /**
   * getDebitos 
   * 
   * @access public
   * @return void
   */
  public function getDebitos() {
    return $this->aDebitos;
  }

  /**
   * getValorBoleto 
   * 
   * @access public
   * @return void
   */
  public function getValorBoleto() {
    return $this->nValorBoleto;
  }

  /**
   * Retorna o valor do historico. 
   * 
   * @access public
   * @return number
   */
  public function getValorHistorico() {
    return $this->nValorHistorico;
  }

  /**
   * Retorna o valor corrigido 
   * 
   * @access public
   * @return number
   */
  public function getValorCorrigido() {
    return $this->nValorCorrigido;
  }

  /**
   * Retorna o valor do juro
   * 
   * @access public
   * @return number
   */
  public function getJuroMulta() {
    return $this->nJurosMulta;
  }

  /**
   * Retorna o Codigo de Barras 
   * 
   * @access public
   * @return integer
   */
  public function getCodigoBarras() {
    return $this->iCodigoBarras;
  }

  /**
   * Retorna a linha digitavel
   * 
   * @access public
   * @return string
   */
  public function getLinhaDigitavel() {
    return $this->sLinhaDigitavel;
  }

  /**
   * Retorna o caminho do PDF
   * 
   * @access public
   * @return string
   */
  public function getCaminhoPDF() {
    return $this->sCaminhoPdf; 
  }

  /**
   * Busca o tipo de debito pelo primeiro debito informado 
   * 
   * @access public
   * @return integer
   */
  public function buscarTipoDebito() {

    $oDaoArrecad = db_utils::getDao('arrecad');

    $aNumpres = array();

    foreach ($this->aDebitos as $oDebito) {
      $aNumpres[] = $oDebito->iNumpre;
    }

    $sNumpres = implode(',', $aNumpres);

    $sWhereTipoDebito = "arrecad.k00_numpre in({$sNumpres})";
    $sSqlTipoDebito   = $oDaoArrecad->sql_query(null, 'distinct arretipo.k03_tipo', null, $sWhereTipoDebito);
    $rsTipoDebito     = $oDaoArrecad->sql_record($sSqlTipoDebito);

    if ( $oDaoArrecad->erro_status == "0" ) {
      throw new Exception($oDaoArrecad->erro_msg);
    }

    return db_utils::fieldsMemory($rsTipoDebito, 0)->k03_tipo;
  }

  /**
   * Adicionar debito 
   * 
   * @param integer $iNumpre 
   * @param integer $iNumpar 
   * @access public
   * @return void
   */
  public function adicionarDebito( $iNumpre, $iNumpar ) {

    $oDadosDebito = new stdClass();
    $oDadosDebito->iNumpre = $iNumpre; 
    $oDadosDebito->iNumpar = $iNumpar; 
    $iIndice = (int) $iNumpre . (int) $iNumpar; 

    $this->aDebitos[$iIndice] = $oDadosDebito;
  }

  /**
   * Busca o codigo do cgm, numcgm pela matricula
   * 
   * @access public
   * @return integer
   */
  public function getCodigoCgmPelaMatricula() {

    db_app::import('cadastro.Imovel');

    $oImovel = new Imovel($this->iMatricula);
    $iCodigoCgm = $oImovel->getProprietarioPrincipal()->getCodigo();
    return $iCodigoCgm;
  }

  /**
   * Busca o codigo do cgm, numcgm pela inscricao
   * 
   * @access public
   * @return integer
   */
  public function getCodigoCgmPelaInscricao() {

    db_app::import('issqn.Empresa');

    $oEmpresa = new Empresa($this->iInscricao);
    $iCodigoCgm  = $oEmpresa->getCgmEmpresa()->getCodigo();
    return $iCodigoCgm;
  }
  
  /**
   * Gerar recibo 
   * 
   * @access public
   * @return void
   */
  public function gerarRecibo() {

    /**
     * Refactor de geracao de boleto 
     */
    $oGeracaoBoleto = new RefactorGeracaoBoleto();

    /**
     * Adiciona debitos ao refactor
     */
    foreach ( $this->aDebitos as $oDebito ) {
      $oGeracaoBoleto->adicionarDebito($oDebito->iNumpre, $oDebito->iNumpar);
    }

    /**
     * nao foi definido cgm 
     * pesquisa cgm pela matricula ou inscricao
     * caso nao for informado matricula ou inscricao, lanca exception
     */
    if ( empty($this->iCodigoCgm) ) {
    
      /**
       * matricula e inscricao vazias 
       */
      if ( empty($this->iMatricula) && empty($this->iInscricao) ) {
        throw new Exception('CGM, matrícula ou inscrição não informado.');
      }

      /**
       * se foi passado matricula, pesquisa cgm pela matricula 
       */
      if ( !empty($this->iMatricula) ) {
        $this->iCodigoCgm = $this->getCodigoCgmPelaMatricula(); 
      }

      /**
       * se foi passado inscricao, pesquisa cgm pela inscricao 
       */
      if ( !empty($this->iInscricao) && empty($this->iCodigoCgm) ) {
        $this->iCodigoCgm = $this->getCodigoCgmPelaInscricao(); 
      }

    }

    $oGeracaoBoleto->set('ver_inscr', $this->getInscricao());
    $oGeracaoBoleto->set('ver_matric', $this->getMatricula()); 
    $oGeracaoBoleto->set('ver_numcgm', $this->getCodigoCgm());

    /**
     * Nenhum debito informado 
     */
    if ( empty($this->aDebitos) ) {
      throw new Exception("Débitos não informados.");
    }

    /**
     * se nao for definido tipo de debito, procura, k03_tipo 
     */
    if ( empty($this->iTipoDebito) ) {
      $this->iTipoDebito = $this->buscarTipoDebito(); 
    }

    /**
     * Tipo de debito 
     */
    $oGeracaoBoleto->set('tipo_debito', $this->getTipoDebito());
    $oGeracaoBoleto->set('k03_tipo', $this->getTipoDebito());

    /**
     * fixos
     */
    $oGeracaoBoleto->set('totregistros', 1);
    $oGeracaoBoleto->set('forcarvencimento', $this->lForcaVencimento); 
    $oGeracaoBoleto->set('processarDescontoRecibo', $this->lProcessaDescontoRecibo); 
    $oGeracaoBoleto->set('lNovoRecibo', $this->lNovoRecibo);

    /**
     * Valida se foi informada data de operacao 
     */
    if ( empty($this->oDataVencimento)  ) {
      throw new Exception("Data de operação não informada.");
    }

    /**
     * Data de opercao em formato pt-br 
     */
    $oGeracaoBoleto->set('k00_dtoper', $this->oDataVencimento->getDate(DBDate::DATA_PTBR));

    /**
     * modelo que deve ser utlizado para emissão (cadtipomod)
     */
    $oGeracaoBoleto->set('iCodigoModeloImpressao', $this->getModeloImpressao());
    
    
    /**
     * Processar 
     */
    $oRetornoGeracaoBoleto = $oGeracaoBoleto->processar();

    /**
     * Numpre/numnov do boleto gerado
     */
    $this->iNumpreBoleto = $oRetornoGeracaoBoleto->recibos_emitidos[0]; 

    /**
     * Busca informacoes do boleto 
     */
    $this->buscarDadosBoleto();
  }

  /**
   * Imprimir boleto 
   * 
   * @access public
   * @return string - caminho do pdf gerado
   */
  public function imprimir() {

    /**
     * Numpre recibo nao informado 
     */
    if ( empty($this->iNumpreBoleto) ) {
      throw new Exception('Código do boleto não gerado.');
    }

    if ( empty($this->iModeloImpressao) ) {
      throw new Exception('Modelo de impressão não informado.');
    }

    /**
     * Refactor da impressao de boleto
     */
    $oImpressao = new RefactorImpressaoBoleto();

    $oImpressao->set("k03_numnov", $this->iNumpreBoleto);
    $oImpressao->set("k03_numpre", $this->iNumpreBoleto);
    $oImpressao->set('ver_inscr', $this->getInscricao());
    $oImpressao->set('ver_numcgm', $this->getCodigoCgm());
    $oImpressao->set('tipo_debito', $this->getTipoDebito());
    $oImpressao->set('k03_tipo' , $this->getTipoDebito());
    $oImpressao->set('tipo', $this->getTipoDebito());
    $oImpressao->set('k00_dtoper', $this->oDataVencimento->getDate(DBDate::DATA_PTBR));
    $oImpressao->set('iModeloRecibo', $this->getModeloImpressao());
    
    $oImpressao->set("reemite_recibo", true);
    $oImpressao->set('totregistros', 1);
    $oImpressao->set('forcarvencimento', $this->lForcaVencimento);

    $oImpressao->processar();

    /**
     * PDF nao gerado
     */
    if ( !file_exists($oImpressao->getCaminhoPDF()) ) {
      throw new Exception("Erro ao gerar PDF.");
    }

    $this->sCaminhoPdf = $oImpressao->getCaminhoPDF(); 
  }
   
  /**
   * Busca e define as informacoes do boleto gerado
   * codigo de barras, linha digitavel, valores
   */
  protected function buscarDadosBoleto() {

    $oDaoReciboPagaBoleto = db_utils::getDao('recibopagaboleto');

    $sSqlBoleto = $oDaoReciboPagaBoleto->sql_queryDadosRecibo($this->iNumpreBoleto);
    
    $rsBoleto   = db_query($sSqlBoleto);
    
    if ( !$rsBoleto) {      
      throw new Exception('Erro ao consultar informações do boleto.');
    }

    if ( pg_num_rows($rsBoleto) == 0 ) {
      throw new Exception('Nenhum boleto encontrado com código informado: ' . $this->iNumpreBoleto);
    }
    
    $aDados = db_utils::getCollectionByRecord($rsBoleto);

    foreach ($aDados as $oDadosBoleto) { 
      
      if ( $oDadosBoleto->tipo_receita == 1 ) {
        $this->nValorHistorico = $oDadosBoleto->valor_historico;
      }

      if ( in_array($oDadosBoleto->tipo_receita, array(2, 3, 5))) {
        $this->nJurosMulta += $oDadosBoleto->valor;
      }
      
      $this->nValorCorrigido += $oDadosBoleto->valor;      
      $this->iCodigoBarras    = $oDadosBoleto->codigo_barras;
      $this->sLinhaDigitavel  = $oDadosBoleto->linha_digitavel;
      $this->iInscricao       = $oDadosBoleto->inscricao;
      $this->iMatricula       = $oDadosBoleto->matricula;
      $this->iCodigoCgm       = $oDadosBoleto->cgm;
      $this->iTipoDebito      = $oDadosBoleto->tipo_debito;
      $this->setDataVencimento( new DBDate($oDadosBoleto->data_pagamento) );
      $this->adicionarDebito($oDadosBoleto->numpre_debito, $oDadosBoleto->numpar_debito);
    }
    
    //die(print_r($this));
  }
  
}