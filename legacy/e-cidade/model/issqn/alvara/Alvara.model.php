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

require_once(modification("std/DBDate.php"));
require_once(modification("model/issqn/Empresa.model.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));

/**
 * Alvara
 * 
 * @package ALVARA
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class Alvara {

  const ATIVO   = 1;
  const INATIVO = 2;

  /**
   * Codigo do alvara
   * 
   * @var mixed
   * @access protected
   */
  protected $iCodigo;
  
  /**
   * Tipo de alvara
   * 
   * @var integer
   * @access protected
   */
  protected $iTipoAlvara;

  /**
   * Empresa
   * 
   * @var Empresa
   * @access protected
   */
  protected $oEmpresa;

  /**
   * Data de inclusao
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataInclusao;

  /**
   * Situaco do alvara
   * 1 - Ativo
   * 2 - Inativo
   * 
   * @var integer
   * @access protected
   */
  protected $iSituacao;

  /**
   * Usuario do sistema
   * 
   * @var UsuarioSistema
   * @access protected
   */
  protected $oUsuario;

  /**
   * Gerado automatico
   * 
   * @var bool
   * @access protected
   */
  protected $lGeradoAutomatico;
  
  /**
   * Array dos documentos da movimentação
   *
   * @access protected
   * @var array
   */
  protected $aDocumentos;

  /**
   * Construtor da funcao
   */
  public function __construct($iCodigo = null) {

    if ( $iCodigo == null ) {
      return false;
    }

    $oDaoIssAlvara = db_utils::getDAO("issalvara");
    $sSqlAlvara    = $oDaoIssAlvara->sql_query_file($iCodigo);
    $rsAlvara      = $oDaoIssAlvara->sql_record($sSqlAlvara);

    if ($oDaoIssAlvara->numrows == 0) {
      throw new Exception("Nenhum alvará encontrado com código: " . $iCodigo);
    }

    $oDadosAlvara = db_utils::fieldsMemory($rsAlvara, 0);

    $this->iCodigo           = $iCodigo;
    $this->iTipoAlvara       = $oDadosAlvara->q123_isstipoalvara;
    $this->iSituacao         = $oDadosAlvara->q123_situacao;        
    $this->oEmpresa          = new Empresa($oDadosAlvara->q123_inscr);
    $this->oDataInclusao     = new DBDate($oDadosAlvara->q123_dtinclusao);
    $this->oUsuario          = new UsuarioSistema($oDadosAlvara->q123_usuario);
    $this->lGeradoAutomatico = $oDadosAlvara->q123_geradoautomatico == 't';
  }

  /**
   * Define o codigo do alvara
   *
   * @param integer $iCodigo
   * @access public
   * @return void
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o codigo do alvara
   *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o tipo de alvara
   *
   * @param integer $iTipoAlvara
   * @access public
   * @return void
   */
  public function setTipoAlvara($iTipoAlvara) {
    $this->iTipoAlvara = $iTipoAlvara;
  }

  /**
   * Retorna tipo de alvara
   *
   * @access public
   * @return integer
   */
  public function getTipoAlvara() {
    return $this->iTipoAlvara;
  }

  /**
   * Define a situacao do alvará
   *
   * @param integer $iSituacao
   * @access public
   * @return void
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna a situação do alvará
   *
   * @access public
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Define a Empresa
   *
   * @param Empresa $oEmpresa
   * @access public
   * @return void
   */
  public function setEmpresa(Empresa $oEmpresa) {
    $this->oEmpresa = $oEmpresa;
  }

  /**
   * Retorna a Empresa
   *
   * @access public
   * @return Empresa
   */
  public function getEmpresa() {
    return $this->oEmpresa;
  }

  /**
   * Define a data de inclusão do alvará
   *
   * @param DBDate $oDataInclusao
   * @access public
   * @return void
   */
  public function setDataInclusao(DBDate $oDataInclusao) {
    $this->oDataInclusao = $oDataInclusao;
  }

  /**
   * Retorna a data de inclusão do alvará
   *
   * @access public
   * @return DBDate
   */
  public function getDataInclusao() {
    return $this->oDataInclusao;  
  }

  /**
   * Define usuario
   *
   * @param UsuarioSistema $oUsuario
   * @access public
   * @return void
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Retorna usuario
   *
   * @access public
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Define se alvara foi gerado automaticamente
   *
   * @param bool $lGeradoAutomatico
   * @access public
   * @return void
   */
  public function setGeradoAltomatico($lGeradoAutomatico) {
    $this->lGeradoAutomatico = $lGeradoAutomatico; 
  }

  /**
   * Retorna se alvara foi gerado automaticamente
   *
   * @access public
   * @return bool
   */
  public function getGeradoAltomatico() {
    return $this->lGeradoAutomatico; 
  }

  /**
   * Busca as movimentações do alvará
   *
   * @param integer $iTipoMovimentacao - Busca apenas as movimentacoes pelo tipo
   * @access public
   * @return mixed
   */
  public function getMovimentacoes($iTipoMovimentacao = null) {

    $aMovimentacoes     = array();
    $oDaoIssMovAlvara   = db_utils::getDAO("issmovalvara");
    $sWhereMovimentacao = "q120_issalvara = {$this->iCodigo}";

    if ( !empty($iTipoMovimentacao) ) {
      $sWhereMovimentacao .= " and q120_isstipomovalvara = " . $iTipoMovimentacao;
    }

    $sCampos        = "q120_sequencial, q120_isstipomovalvara";
    $sOrdem         = 'q120_sequencial desc';
    $sSqlMovAlvara  = $oDaoIssMovAlvara->sql_query_file(null, $sCampos, $sOrdem, $sWhereMovimentacao);
    $rsSqlMovAlvara = db_query($sSqlMovAlvara);
  
    if ( !$rsSqlMovAlvara ) {
      throw new DBException( "Erro ao Buscar movimentações do Alvará.\nErro Técnico:" . pg_last_error() );
    }

    foreach ( db_utils::getCollectionByRecord($rsSqlMovAlvara) as $oDadosAlvara ) {
      $aMovimentacoes[] = MovimentacaoAlvaraFactory::getInstancia($oDadosAlvara->q120_isstipomovalvara, $oDadosAlvara->q120_sequencial);
    } 

    return $aMovimentacoes;
  }

  /**
   * Adiciona documento a lista de documentos da movimentação do alvará
   * @param integer $iCodigoDocumento - SEQUENCIA TABELA caddocumento
   */
  public function addDocumento($iCodigoDocumento){
    $this->aDocumentos[] = $iCodigoDocumento;
  }
  
  /**
   * Salva os Documentos da movimentação
   *
   * @param $aDocumentos
   */
  public function gravaDocumentos(){

  	if ( count($this->aDocumentos) == 0) {
      return false;
	  }
  		
    $oDaoIssAlvaraDocumento = db_utils::getDAO('issalvaradocumento');
    $oDaoIssAlvaraDocumento->excluir("", "q122_issalvara = {$this->getCodigo()}");

    foreach ($this->aDocumentos as $iDocumento){

      $oDaoIssAlvaraDocumento->q122_issalvara    = $this->getCodigo();
      $oDaoIssAlvaraDocumento->q122_caddocumento = $iDocumento;
      $oDaoIssAlvaraDocumento->incluir("");

      if($oDaoIssAlvaraDocumento->erro_status == "0"){
        throw new DBException($oDaoIssAlvaraDocumento->erro_msg);
      } 
    }
  }

  public function incluirMovimentacao( $iTipoMovimentacao ) {
    
    $oInstanciaMovimentacao = MovimentacaoAlvaraFactory::getInstancia($iTipoMovimentacao);
    $oInstanciaMovimentacao->setAlvara($this);
    return $oInstanciaMovimentacao;
  }

}
