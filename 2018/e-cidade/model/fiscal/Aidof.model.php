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

require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("std/DBDate.php");

/**
 * Classe de Representação de AIDOF( Autorização de Impressão de DOcumento Fiscal )
 *
 * @package Fiscal
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
class Aidof {

  /**
   * Codigo do Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iCodigo;

  /**
   * Nota Fiscal do Aidof
   * 
   * @var NotaFiscalISSQN
   * @access protected
   */
  protected $oNota;

  /**
   * Empresa do Aidof
   * 
   * @var Empresa
   * @access protected
   */
  protected $oEmpresa;

  /**
   * Data do lançamento do Aidof
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataLancamento;

  /**
   * Numero inicial da Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iNumeroInicial;

  /**
   * Numero final da Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iNumeroFinal;

  /**
   * Grafica do Aidof
   * 
   * @var Object
   * @access protected
   */
  protected $oGrafica;
  
  /**
   * Observações da Aidof
   * 
   * @var string
   * @access protected
   */
  protected $sObservacoes;

  /**
   * Quantidade solicitada da Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iQuantidadeSolicitada;

  /**
   * Quantidade Liberada Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iQuantidadeLiberada;

  /**
   * Flag usada para validar se o 
   * Aidof está cancelada ou não
   * 
   * @var Bollean
   * @access protected
   */
  protected $lCancelado;

  /**
   * Data limite da Aidof
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataLimite;

  /**
   * Data Liberação pela Grafica da Aidof
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataLiberacaoGrafica;

  /**
   * Data de recebimento pelo contribuinte
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataRecebimentoContribuinte;

  /**
   * Data Limite para usar as notas
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataLimiteSolicitada;

  /**
   * Data Limite liberada pelo Fiscal para Utilização das NF
   * 
   * @var DBDate
   * @access protected
   */
  protected $oDataLimiteLiberada;

  /**
   * Codigo do processo
   * 
   * @var integer
   * @access protected
   */
  protected $iProcesso;

  /**
   * Contrutor
   *
   * @param integer $iCodigo
   * @access public
   * @return void
   */
  public function __construct($iCodigo = null) {
  
    if ( empty($iCodigo) ) {
      return;
    }
  
    $oDaoAidof = db_utils::getDao('aidof');
    $sSqlAidof = $oDaoAidof->sql_query_file($iCodigo);

    $rsAidof   = db_query($sSqlAidof);
  
    if ( !$rsAidof ) {
      throw new Exception( "Erro ao buscar Aidof: " . pg_last_error());
    }
    
    if ( pg_num_rows($rsAidof) == 0 ) {
      throw new Exception('Nenhuma Aidof encontrada: ' . $iCodigo);
    }
  
    $oAidof = db_utils::fieldsMemory($rsAidof, 0);

    $this->iCodigo                      = $oAidof->y08_codigo;
    $this->oGrafica                     = new Grafica($oAidof->y08_numcgm);
    $this->oNota                        = new NotaFiscalISSQN($oAidof->y08_nota);
    $this->oEmpresa                     = new Empresa($oAidof->y08_inscr);
    $this->iNumeroInicial               = $oAidof->y08_notain;
    $this->iNumeroFinal                 = $oAidof->y08_notafi;
    $this->sObservacoes                 = $oAidof->y08_obs;
    $this->iQuantidadeSolicitada        = $oAidof->y08_quantsol;
    $this->iQuantidadeLiberada          = $oAidof->y08_quantlib;
    $this->lCancelado                   = $oAidof->y08_cancel;

    if ( !empty($oAidof->y08_dtlanc) ) {
      $this->oDataLancamento = new DBDate($oAidof->y08_dtlanc);
    }
    
    if ( !empty($oAidof->y08_datalimite)) {
      $this->oDataLimite = new DBDate($oAidof->y08_datalimite);
    }
    
    if ( !empty($oAidof->y08_dataliberacaografica)) {     
      $this->oDataLiberacaoGrafica = new DBDate($oAidof->y08_dataliberacaografica);
    }
    
    if ( !empty($oAidof->y08_datarecebimentocontribuinte)) {
      $this->oDataRecebimentoContribuinte = new DBDate($oAidof->y08_datarecebimentocontribuinte);
    }
    
    if ( !empty($oAidof->y08_datalimitesolicitada)) {
      $this->oDataLimiteSolicitada = new DBDate($oAidof->y08_datalimitesolicitada);
    }
    
    if ( !empty($oAidof->y08_datalimiteliberada)) {
      $this->oDataLimiteLiberada = new DBDate($oAidof->y08_datalimiteliberada);
    }

    $oDaoAidofProc = db_utils::getDao('aidofproc');
    $sSqlProcesso  = $oDaoAidofProc->sql_query_file(null,'y02_codproc', null, "y02_aidof = {$this->iCodigo}");
    $rsProcesso    = db_query($sSqlProcesso);
    
    if ( !$rsProcesso && pg_num_rows($rsProcesso) > 0 ) {
    
      $oProcesso = db_utils::fieldsMemory($rsProcesso, 0);
      $this->iProcesso = $oProcesso->y02_codproc;
    } 
  }

  /**
   * Retorna codigo do Aidof.
   *
   * @return integer $iCodigo.
   */
  public function getCodigo() { 
    return $this->iCodigo;
  }

  /**
   * Define o Numer do Aidof
   *
   * @param $iCodigo
   */
  public function setCodigo($iCodigo) { 

    $this->iCodigo = $iCodigo;
    return;
  }
 
  /**
   * Retorna o Tipo de Nota Utilizada no Aidof.
   *
   * @return NotaFiscalISSQN.
   */
  public function getNota() { 
    return $this->oNota;
  }
  
  /**
   * Define oNota.
   *
   * @param oNota 
   */
  public function setNota( $oNota ) { 
  
    $this->oNota = $oNota;
    return;
  }

  /**
   * Retorna Empresa.
   *
   * @return oEmpresa.
   */
  public function getEmpresa() { 
    return $this->oEmpresa;
  }
  
  /**
   * Define Empresa.
   *
   * @param oEmpresa 
   */
  public function setEmpresa( $oEmpresa ) { 

    if ( !$oEmpresa->isAtiva() ) {
      throw new BusinessException('Empresa baixada.');
    }
  
    $this->oEmpresa = $oEmpresa;
  }
  
  /**
   * Retorna DataLancamento.
   *
   * @return oDataLancamento.
   */
  public function getDataLancamento() { 
    return $this->oDataLancamento;
  }
  
  /**
   * Define DataLancamento.
   *
   * @param oDataLancamento 
   */
  public function setDataLancamento( $oDataLancamento ) { 
  
    $this->oDataLancamento = $oDataLancamento;
    return;
  }
  
  /**
   * Retorna NumeroInicial.
   *
   * @return iNumeroInicial.
   */
  public function getNumeroInicial() { 
    return $this->iNumeroInicial;
  }
  
  /**
   * Define NumeroInicial.
   *
   * @param iNumeroInicial 
   */
  public function setNumeroInicial( $iNumeroInicial ) { 
  
    $this->iNumeroInicial = $iNumeroInicial;
    return;
  }
  
  /**
   * Retorna NumeroFinal.
   *
   * @return iNumeroFinal.
   */
  public function getNumeroFinal() { 
    return $this->iNumeroFinal;
  }
  
  /**
   * Define NumeroFinal.
   *
   * @param iNumeroFinal 
   */
  public function setNumeroFinal( $iNumeroFinal ) { 
  
    $this->iNumeroFinal = $iNumeroFinal;
    return;
  }
  
  /**
   * Retorna Grafica.
   *
   * @return oGrafica.
   */
  public function getGrafica() { 
    return $this->oGrafica;
  }
  
  /**
   * Define Grafica.
   *
   * @param oGrafica 
   */
  public function setGrafica( Grafica $oGrafica ) { 
    $this->oGrafica = $oGrafica;
  }
  
  /**
   * Retorna Observacoes.
   *
   * @return sObservacoes.
   */
  public function getObservacoes() { 
    return $this->sObservacoes;
  }
  
  /**
   * Define Observacoes.
   *
   * @param sObservacoes 
   */
  public function setObservacoes( $sObservacoes ) { 
  
    $this->sObservacoes = $sObservacoes;
    return;
  }

  /**
   * Retorna quantidade Solicitada.
   * 
   * @return iQuantidadeSolicitada
   */
  public function getQuantidadeSolicitada() {
    return $this->iQuantidadeSolicitada;
  }

  /**
   * Define Quantidade solicitada.
   *
   * @param iQuantidadeSolicitada
   */
  public function setQuantidadeSolicitada($iQuantidadeSolicitada) {
    
    $this->iQuantidadeSolicitada = $iQuantidadeSolicitada;
    return;
  }

  /**
   * Retorna quantidade Liberada.
   * 
   * @return $iQuantidadeLiberada
   */
  public function getQuantidadeLiberada() {
    return $this->iQuantidadeLiberada;
  }

  /**
   * Define quantidade Liberada.
   *
   * @param iQuantidadeLiberada
   */
  public function setQuantidadeLiberada($iQuantidadeLiberada) {
   
    $this->iQuantidadeLiberada = $iQuantidadeLiberada;
    return;
  }

  /**
   * Retorna status do cancelamento.
   * 
   * @return lCancelado
   */
  public function getSituacao() {
    return $this->lCancelado;
  }

  /**
   * Define status do cancelamento.
   *
   * @param lCancelado
   */
  public function setCancelado($lCancelado) {
    $this->lCancelado = $lCancelado;
  }

  /**
   * Retorna data liberação da Gráfica.
   * 
   * @return oDataLiberacaoGrafica
   */
  public function getDataLiberacaoGrafica() {
    return $this->oDataLiberacaoGrafica;
  }

  /**
   * Define data liberação da Gráfica.
   *
   * @param oDataLiberacaoGrafica
   */
  public function setDataLiberacaoGrafica($oDataLiberacaoGrafica) {    
    
    $this->oDataLiberacaoGrafica = $oDataLiberacaoGrafica;
    return;
  }

  /**
   * Retorna Data recebimento contribuinte.
   * 
   * @return oDataRecebimentoContribuinte
   */
  public function getDataRecebimentoContribuinte() {
    return $this->oDataRecebimentoContribuinte;
  }

  /**
   * Define recebimento contribuinte.
   *
   * @param oDataRecebimentoContribuinte
   */
  public function setDataRecebimentoContribuinte($oDataRecebimentoContribuinte) {
    
    $this->oDataRecebimentoContribuinte = $oDataRecebimentoContribuinte;
    return;
  }

  /**
   * Retorna Data limite solicitada.
   * 
   * @return oDataLimiteSolicitada
   */
  public function getDataLimiteSolicitada() {
    return $this->oDataLimiteSolicitada;
  }

  /**
   * Define Data limite solicitada.
   *
   * @param oDataLimiteSolicitada
   */
  public function setDataLimiteSolicitada($oDataLimiteSolicitada) {
    
    $this->oDataLimiteSolicitada = $oDataLimiteSolicitada;
    return;
  }
  
  /**
   * Retorna Data Limite liberação.
   * 
   * @return oDataLimiteLiberada
   */
  public function getDataLimiteLiberada() {
    return $this->oDataLimiteLiberada;
  }

  /**
   * Define Limite liberação.
   *
   * @param oDataLimiteLiberada
   */
  public function setDataLimiteLiberada($oDataLimiteLiberada) {
    
    $this->oDataLimiteLiberada = $oDataLimiteLiberada;
    return;
  }

  /**
   * Define codigo do processo
   *
   * @param integer $iProcesso
   * @access public
   * @return void
   */
  public function setProcesso($iProcesso) {
    $this->iProcesso = $iProcesso;
  }
  
  /**
   * Retorna o codigo do processo
   *
   * @access public
   * @return integer
   */
  public function getProcesso() {
    return $this->iProcesso;
  }
  
  /**
   * Salva os dados Informados na tabela aidof
   * @return boolean
   */
  public function salvar() {
    
    if ( !db_utils::inTransaction() ) {
      throw new Exception('Não existe Transação Ativa');
    }

    db_utils::getDao('aidofproc', false);
    db_utils::getDao('aidof', false);
   
    $oDaoAidof               = new cl_aidof();
    $oDaoAidof->y08_nota     = $this->oNota->getCodigo();
    $oDaoAidof->y08_inscr    = $this->oEmpresa->getInscricao();
    $oDaoAidof->y08_notain   = $this->iNumeroInicial;
    $oDaoAidof->y08_notafi   = $this->iNumeroFinal;
    $oDaoAidof->y08_obs      = $this->sObservacoes;
    $oDaoAidof->y08_quantsol = $this->iQuantidadeSolicitada;
    $oDaoAidof->y08_quantlib = $this->iQuantidadeLiberada;

    $oDaoAidof->y08_cancel   = 'false';
    $oDaoAidof->y08_dtlanc   = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoAidof->y08_login    = db_getsession('DB_id_usuario');

    if ( !is_null($this->oGrafica) ) {
      $oDaoAidof->y08_numcgm = $this->oGrafica->getCodigo();
    }

    if ( !is_null($this->oDataLimite) ) {
      $oDaoAidof->y08_datalimite = $this->oDataLimite->getDate();
    }

    if ( !is_null($this->oDataLiberacaoGrafica ) ) {
      $oDaoAidof->y08_dataliberacaografica = $this->oDataLiberacaoGrafica->getDate();
    }

    if ( !is_null($this->oDataRecebimentoContribuinte ) ) {
      $oDaoAidof->y08_datarecebimentocontribuinte = $this->oDataRecebimentoContribuinte->getDate();
    }

    if (!is_null( $this->oDataLimiteSolicitada ) ) {
      $oDaoAidof->y08_datalimitesolicitada = $this->oDataLimiteSolicitada->getDate();
    }

    if ( !is_null($this->oDataLimiteLiberada) ) {
      $oDaoAidof->y08_datalimiteliberada = $this->oDataLimiteLiberada->getDate();
    }

    /**
     * Inclui ou altera AIDOF 
     */
    if ( empty($this->iCodigo) ) {

      $this->iCodigo = self::getProximaNumeracao();

      $oDaoAidof->y08_codigo = $this->iCodigo;
      $oDaoAidof->incluir($this->iCodigo);

      if ( $oDaoAidof->erro_status == "0" ) {
        throw new DBException($oDaoAidof->erro_msg);
      } 

    } else {

      $oDaoAidof->y08_codigo = $this->iCodigo;
      $oDaoAidof->alterar($this->iCodigo);

      if ( $oDaoAidof->erro_status == "0" ) {
        throw new DBException($oDaoAidof->erro_msg);
      } 

      /**
       * Exclui processos ja vinculados
       */
      $oDaoAidofProc = new cl_aidofproc();
      $oDaoAidofProc->excluir(null, "y02_aidof = {$this->iCodigo}");

      /**
       * Erro ao excluir processo do AIDOF 
       */
      if ( $oDaoAidofProc->erro_status == "0" ) {
        throw new DBException($oDaoAidofProc->erro_msg);
      } 
    }

    /**
     * Inclui, caso informe codigo do processo 
     */
    if ( !empty($this->iProcesso) ) {

      $oDaoAidofProc              = new cl_aidofproc();
      $oDaoAidofProc->y02_aidof   = $this->iCodigo;
      $oDaoAidofProc->y02_codproc = $this->iProcesso;
      $oDaoAidofProc->incluir(null);

      /**
       * Erro ao incluir processo do AIDOF 
       */
      if ( $oDaoAidofProc->erro_status == "0" ) {
        throw new DBException($oDaoAidofProc->erro_msg);
      } 
    }

    return $oDaoAidof->y08_codigo;
  }

  /**
   * Retorna Próxima Numeracao Disponivel para Aidof
   * 
   * @static
   * @access public
   * @return integer
   */
  public static function getProximaNumeracao() {

    if ( !db_utils::inTransaction() ) {
      throw new BusinessException("Sem Transacao Ativa");  
    }

    $iNumeroAidof = 0;

    $sUpdate  = "update aidofnumeracao                      ";
    $sUpdate .= "   set y115_numeracao = y115_numeracao + 1 ";
    $rsUpdate = db_query($sUpdate);

    if ( !$rsUpdate ) {
      throw new DBException("Não foi possivel definir a Numeração. \n\n Erro: ". pg_last_error());
    }
    
    $sSqlNextVal  = " select y115_numeracao         ";
    $sSqlNextVal .= "   from aidofnumeracao         ";
    $sSqlNextVal .= "  order by y115_numeracao desc ";
    $sSqlNextVal .= "  limit 1                      ";
      
    $rsNextVal = db_query($sSqlNextVal);

    if ( !$rsNextVal ) {
      throw new DBException("Não foi possivel buscar a próxima numeracao do AIDOF. \n\n Erro: ". pg_last_error());
    }

    if ( pg_num_rows($rsNextVal) == 0 ) {
      throw new BusinessException("Nenhuma numeração encontrada.");
    } 

    /**
     * Numeracao do AIDOF
     */
    $iNumeroAidof = db_utils::fieldsMemory($rsNextVal,0)->y115_numeracao;

    /**
     * Define sequencial da tabela aidof
     */
    $lAtualizaSequencial = db_query("select setval('aidof_y08_codigo_seq', {$iNumeroAidof})");

    /**
     * erro ao atualizar sequencial 
     */
    if ( !$lAtualizaSequencial ) {
      throw new DBException("Não foi possivel definir sequencial do AIDOF. \n\n Erro: ". pg_last_error());
    }

    return $iNumeroAidof;
  }

  /**
   * Retorna a numeração da ultima nota
   * 
   * @param Empresa $oEmpresa
   * @param NotaFiscalISSQN $oNota
   * @return integer
   */
  public function getNotaFinalAidof(Empresa $oEmpresa, NotaFiscalISSQN $oNota) {
    
    $aDaoAidof = new cl_aidof;
    
    $sSql      = $aDaoAidof->sql_query_file(null,
                                            "y08_notafi",
                                            "y08_notafi DESC LIMIT 1",
                                            "    y08_inscr={$oEmpresa->getInscricao()}
                                             and y08_cancel = 'f'
                                             and y08_nota = {$oNota->getCodigo()} ");
    
    $rsAidof    = db_query($sSql);
    
    if (!$rsAidof) {
      throw new DBException('Erro ao buscar a numeração final da nota na Aidof!' . pg_last_error());
    }
    
    return db_utils::fieldsMemory($rsAidof,0)->y08_notafi;
    
  }
  
}