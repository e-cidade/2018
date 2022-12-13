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
 * Model Desconto Manual
 *
 * Classe que manipula os descontos lançados manualmente no sistema
 *
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @package Arrecadacao
 * @version $
 * @deprecated Utilizar Classe "Desconto"
 *
 */
class DescontoManual {

  /**
   * Codigo do desconto 
   * 
   * @var integer
   * @access private
   */
  private $iCodigoDesconto;
  
  /**
   * Instancia da classe CgmBase com os dados do contribuinte
   * @var CgmBase
   */
  private $oCgm;

  /**
   * Usuario que gerou desconto 
   * 
   * @var integer
   * @access private
   */
  private $iUsuario;

  /**
   * Instituicao do desconto 
   * 
   * @var integer
   * @access private
   */
  private $iInstituicao;

  /**
   * Valor do desconto 
   * 
   * @var float
   * @access private
   */
  private $nValor;

  /**
   * Percentual de desconto
   * 
   * @var float
   * @access private
   */
  private $nPercentual;

  /**
   * Data do lancamento 
   * 
   * @var DBDate
   * @access private
   */
  private $oDataDesconto;

  /**
   * Hora do processamento do desconto 
   * 
   * @var string
   * @access private
   */
  private $sHoraDesconto;
  
  /**
   * Observações do lançamento de desconto
   * @var string
   */
  private $sObservacao;
  
  /**
   * Numpre do recibo gerado para o desconto
   * @var integer
   */
  private $iNumpre;
  
  /**
   * Numpar do recibo gerado para o desconto
   * @var integer
   */
  private $iNumpar;
  
  /**
   * Codigo da receita do recibo gerado para o desconto
   * @var integer
   */
  private $iCodigoReceita;

  /**
   * Codigo de historico do debito 
   * 
   * @var integer
   * @access private
   */
  private $iCodigoHistorico;

  /**
   * codigo de tipo do debito 
   * 
   * @var integer
   * @access private
   */
  private $iTipoDebito;
  
  /**
   * Construtor da classe  
   */
  public function __construct() {}

  /**
   * Lanca desconto 
   * 
   * @access public
   * @return boolean
   */
  public function salvar() {

  	db_utils::getDao('abatimento', false);
    db_utils::getDao('abatimentoutilizacao', false);
    db_utils::getDao('arrecad', false);
    db_utils::getDao('arrehist', false);
    
    if (!$this->getCgm() instanceof CgmBase || $this->getCgm()->getCodigo() == '') {
      throw new Exception("CGM não informado ou inválido para a inclusão do desconto");
    }
    
    if ($this->getValor() == null || $this->getValor() <= 0) {
      throw new Exception("Valor não informado ou inválido para a inclusão do desconto");
    }
    
    $oDaoAbatimento                          = new cl_abatimento();
    $oDaoAbatimento->k125_tipoabatimento     = 2;
    $oDaoAbatimento->k125_datalanc           = $this->getDataDesconto()->getDate();
    $oDaoAbatimento->k125_hora               = $this->getHoraDesconto();
    $oDaoAbatimento->k125_usuario            = $this->getUsuario();
    $oDaoAbatimento->k125_instit             = $this->getInstituicao();
    $oDaoAbatimento->k125_valor              = $this->getValor();
    $oDaoAbatimento->k125_perc               = $this->getPercentual();
    $oDaoAbatimento->k125_valordisponivel    = '0';
    $oDaoAbatimento->k125_abatimentosituacao = 1;
    $oDaoAbatimento->incluir(null);
    
    if ($oDaoAbatimento->erro_status == "0") {
      throw new Exception("Erro ao incluir abatimento do tipo desconto para o cgm $this->getCgm->getCodigo(). \nErro (abatimento): {$oDaoAbatimento->erro_msg}");
    }
    
    /**
     * Inclui abatimentoutilizacao com tipo 2 - compensacao 
     */
    $oDaoAbatimentoUtilizacao                      = new cl_abatimentoutilizacao();
    $oDaoAbatimentoUtilizacao->k157_tipoutilizacao = 2;
    $oDaoAbatimentoUtilizacao->k157_data           = $this->getDataDesconto()->getDate();
    $oDaoAbatimentoUtilizacao->k157_valor          = $this->getValor();
    $oDaoAbatimentoUtilizacao->k157_hora           = $this->getHoraDesconto();
    $oDaoAbatimentoUtilizacao->k157_usuario        = $this->getUsuario();
    $oDaoAbatimentoUtilizacao->k157_abatimento     = $oDaoAbatimento->k125_sequencial;
    $oDaoAbatimentoUtilizacao->incluir(null);

    /**
     * Erro ao incluir abatimentoutilizacao 
     */
    if ($oDaoAbatimentoUtilizacao->erro_status == '0') {
      throw new Exception("Erro ao incluir registro de utilização do desconto. \nErro: (abatimentoutilizacao) {$oDaoAbatimentoUtilizacao->erro_msg}");
    }

    
    
    
    $oDaoArrehist = new cl_arrehist();
    $oDaoArrehist->k00_numpre     = $this->getNumpre();
    $oDaoArrehist->k00_numpar     = $this->getNumpar();
    $oDaoArrehist->k00_hist       = $this->getCodigoHistorico();
    $oDaoArrehist->k00_dtoper     = $this->getDataDesconto()->getDate();
    $oDaoArrehist->k00_hora       = $this->getHoraDesconto();
    $oDaoArrehist->k00_id_usuario = $this->getUsuario();
    $oDaoArrehist->k00_histtxt    = 'Desconto Manual';
    $oDaoArrehist->k00_limithist  = '';
    $oDaoArrehist->k00_idhist     = null;
    
    $oDaoArrehist->incluir(null);
    
    if ($oDaoArrehist->erro_status == '0') {
      throw new Exception("Erro ao incluir dados no arrehist para o numpre {$this->getNumpre()}, numpar {$this->getNumpar()}. \nErro: (arrehist) {$oDaoArrehist->erro_msg}");
    }
    
    $oDaoArrecad    = new cl_arrecad();
    
    $sWhereArrecad  = "     k00_numpre = {$this->getNumpre()}        ";
    $sWhereArrecad .= " and k00_numpar = {$this->getNumpar()}        ";
    $sWhereArrecad .= " and k00_receit = {$this->getCodigoReceita()} ";
    
    $rsArrecad      = $oDaoArrecad->sql_record($oDaoArrecad->sql_query_file(null, "k00_valor", null, $sWhereArrecad));
    
    if ($oDaoArrecad->numrows == 0) {
      throw new Exception("Débito do numpre {$this->getNumpre()}, numpar {$this->getNumpar()} e receita {$this->getCodigoReceita()} não encontrado na arrecad.");
    }
    
    $oArrecad = db_utils::fieldsMemory($rsArrecad, 0);
    
    $oDaoArrecad->k00_valor = ($oArrecad->k00_valor - $this->getValor());

    $oDaoArrecad->alterar(null, $sWhereArrecad);
    
    if ($oDaoArrecad->erro_status == '0') {
    	throw new Exception("Erro ao alterar valor do débito no arrecad. \nErro: {$oDaoArrecad->erro_msg}");
    }

    return true;
    
  }
  
  /**
   * Define o codigo do desconto 
   * 
   * @param integer $iCodigoDesconto 
   * @access public
   * @return void
   */
  public function setCodigoDesconto($iCodigoDesconto) {
    $this->iCodigoDesconto = $iCodigoDesconto;
  }

  /**
   * Retorna o codigo do desconto 
   * 
   * @access public
   * @return integer
   */
  public function getCodigoDesconto() {
    return $this->iCodigoDesconto;
  }

  /**
   * Define o percentual de desconto 
   * 
   * @param float $nPercentual 
   * @access public
   * @return void
   */
  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }

  /**
   * Retorna o percentual de desconto 
   * 
   * @access public
   * @return float
   */
  public function getPercentual() {
    return $this->nPercentual;
  }

  /**
   * Data do lancamento do desconto
   * 
   * @param DBDate $oDataDesconto 
   * @access public
   * @return void
   */
  public function setDataDesconto(DBDate $oDataDesconto) {
    $this->oDataDesconto = $oDataDesconto;
  }

  public function getDataDesconto() {
    return $this->oDataDesconto;
  }
  
  /**
   * Define hora do processamento do desconto 
   * 
   * @access public
   * @return string
   */
  public function setHoraDesconto($sHoraDesconto) {
    $this->sHoraDesconto = $sHoraDesconto;
  }

  /**
   * Retorna hora do processamento do desconto 
   * 
   * @access public
   * @return string
   */
  public function getHoraDesconto() {
    return $this->sHoraDesconto;
  }

  /**
   * Define valor do desconto 
   * 
   * @param float $nValor 
   * @access public
   * @return void
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna o valor do desconto 
   * 
   * @access public
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }
  
  /**
   * Retorna o cgm do desconto
   * @return object CgmFactory
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Define o cgm do desconto
   * @param CgmFactory $oCgm
   */
  public function setCgm(CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna a observação do desconto
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observação para o desconto
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Define o numpre para o recibo do desconto
   * @param integer $iNumpre
   */
  public function setNumpre($iNumpre){
    $this->iNumpre = $iNumpre;
  }
  
  /**
   * Retorna o numpre do recibo do desconto
   * @return integer
   */
  public function getNumpre(){
    return $this->iNumpre;
  }
  
  /**
   * Define o numpar do recibo do desconto
   * @param integer $iNumpar
   */
  public function setNumpar($iNumpar){
    $this->iNumpar = $iNumpar;
  }
  
  /**
   * Retorna o numpar do recibo do desconto
   * @return integer
   */
  public function getNumpar(){
    return $this->iNumpar;
  }
  
  /**
   * Define o código da receita do recibo do desconto
   * @param integer
   */
  public function setCodigoReceita($iCodigoReceita){
    $this->iCodigoReceita = $iCodigoReceita;
  }
  
  /**
   * Retorna o código da receita do recibo do desconto
   * @return integer
   */
  public function getCodigoReceita(){
    return $this->iCodigoReceita;
  }

  /**
   * Define codigo de historico do desconto  
   * 
   * @param integer $iCodigoHistorico 
   * @access public
   * @return void
   */
  public function setCodigoHistorico($iCodigoHistorico) {
    $this->iCodigoHistorico = $iCodigoHistorico;
  }

  /**
   * Retorna o codigo de historico do desconto 
   * 
   * @access public
   * @return integer
   */
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Define tipo de debito 
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
   * Define a instituição do desconto
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }
  
  /**
   * Retorna a instituição do desconto
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }
  
  /**
   * Define o usuario que efetuou a transferência
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Retorna o código do usuário que efetuou a tranferência
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }
  
}