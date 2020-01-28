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

define( "MENSAGEM_PRONTUARIO", "saude.ambulatorial.Prontuario.");

/**
 * Classe para controle dos dados do Prontuário
 * @author André Mello   andre.mello@dbseller.com.br
 *         Fábio Esteves fabio.esteves@dbseller.com.br
 * @package Ambulatorial
 */
class Prontuario {

  /**
   * Código do prontuário
   * @var integer
   */
  private $iCodigo;

  /**
   * Instância de Cgs
   * @var Cgs
   */
  private $oCgs;

  /**
   * Controla se Prontuário está finalizado
   * @var boolean
   */
  private $lFinalizado = false;

  /**
   * Administrações realizados dentro da FAA
   * @var AdministracaoMedicamento[]
   */
  private $aAdministracoes = array();

  /**
   * Data em que o paciente foi atendimento na recepção
   * @var DBDate|null
   */
  private $oDataAtendimento = null;

  /**
   * Construtor da classe. Recebe como parâmetro o código do prontuário a ser pesquisado
   * @param null $iProntuario
   * @throws DBException
   */
  public function __construct( $iProntuario = null ) {

    if ( empty( $iProntuario ) ) {
      return;
    }

    $oDaoProntuario = new cl_prontuarios();
    $sSqlProntuario = $oDaoProntuario->sql_query_file( $iProntuario );
    $rsProntuario   = db_query( $sSqlProntuario );

    if ( !$rsProntuario ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_PRONTUARIO . 'erro_buscar_prontuario', $oErro ) );
    }

    if( pg_num_rows( $rsProntuario ) > 0 ) {

      $oDadosProntuario       = db_utils::fieldsMemory( $rsProntuario, 0 );
      $this->iCodigo          = $iProntuario;
      $this->oCgs             = new Cgs( $oDadosProntuario->sd24_i_numcgs );
      $this->lFinalizado      = $oDadosProntuario->sd24_c_digitada == 'S';
      $this->oDataAtendimento = new DBDate( $oDadosProntuario->sd24_d_cadastro );
    }
  }

  /**
   * Retorno uma instância de Cgs
   * @return Cgs
   */
  public function getCGS() {
    return $this->oCgs;
  }

  /**
   * Seta uma instância de Cgs
   * @param Cgs
   */
  public function setCGS( Cgs $oCgs ) {
    $this->oCgs = $oCgs;
  }

  /**
   * Retorna se o prontuário está finalizado
   * @return boolean
   */
  public function isFinalizado() {
    return $this->lFinalizado;
  }

  /**
   * Define se o prontuário está finalizado
   * @param boolean $lFinalizado
   */
  public function setFinalizado( $lFinalizado ) {
    $this->lFinalizado = $lFinalizado;
  }

  /**
   * Salva os dados do prontuário
   */
  public function salvar() {

    $oDaoProntuario                  = new cl_prontuarios();
    $oDaoProntuario->sd24_c_digitada = $this->lFinalizado ? 'S' : 'N';

    if ( !empty( $this->iCodigo ) ) {

      $oDaoProntuario->sd24_i_codigo = $this->iCodigo;
      $oDaoProntuario->alterar( $this->iCodigo );
    } else {
      $oDaoProntuario->incluir( null );
    }

    if( $oDaoProntuario->erro_status == "0" ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoProntuario->erro_msg;

      throw new DBException( _M( MENSAGEM_PRONTUARIO . 'erro_salvar_prontuario', $oErro ) );
    }
  }

  /**
   * Retorna todas as triagens de um prontuário (FAA)
   *
   * @return TriagemAvulsa[]
   * @throws DBException
   */
  public function getTriagens() {

    if ( empty($this->iCodigo) ) {
      return;
    }

    $aTriagens                   = array();
    $oDaoTriagemAvulsaProntuario = new cl_sau_triagemavulsaprontuario();
    $sCamposTriagem              = "s155_i_triagemavulsa";
    $sOrderBy                    = "1 desc";
    $sWhereTriagem               = " s155_i_prontuario = {$this->iCodigo} ";
    $sSqlTriagemConsulta         = $oDaoTriagemAvulsaProntuario->sql_query_file(null, $sCamposTriagem, $sOrderBy, $sWhereTriagem);
    $rsTriagemConsulta           = db_query( $sSqlTriagemConsulta );

    if ( !$rsTriagemConsulta ) {

      $oErro = new stdClass();
      $oErro->sErro = $oDaoTriagemAvulsaProntuario->erro_msg;
      throw new DBException( _M( MENSAGEM_PRONTUARIO . 'erro_buscar_triagens', $oErro ) );
    }

    $iLinhasTriagem = pg_num_rows($rsTriagemConsulta) ;

    for( $iContador = 0; $iContador < $iLinhasTriagem; $iContador++ ) {

      $iTriagem     = db_utils::fieldsMemory( $rsTriagemConsulta, $iContador )->s155_i_triagemavulsa;      
      $aTriagens[]  = new TriagemAvulsa( $iTriagem );
    }

    return $aTriagens;
  }

  /**
   * Retorna todas as Administrações de medicamento realizadas na FAA
   * @return AdministracaoMedicamento[]
   */
  public function getAdministracoesDeMedicamento() {

    if (count($this->aAdministracoes) > 0) {
      return $this->aAdministracoes;
    }

    $this->aAdministracoes = AdministracaoMedicamentoRepository::getAdministracoesDaFaa($this);
    return $this->aAdministracoes;
  }

  /**
   * @param AdministracaoMedicamento $oAdministracaoMedicamento
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function administrarMedicamento(AdministracaoMedicamento $oAdministracaoMedicamento) {


    if (empty($oAdministracaoMedicamento)) {
      throw new ParameterException(_M(MENSAGEM_PRONTUARIO."administracao_nao_informada"));
    }

    $iCodigoAdministracao = $oAdministracaoMedicamento->getCodigo();
    if (empty($iCodigoAdministracao)) {
     $oAdministracaoMedicamento->salvar();
    }

    if (isset($this->aAdministracoes[$oAdministracaoMedicamento->getCodigo()])) {
      return;
    }
    $oDaoAdministracaoMedicamento = new cl_prontuarioadministracaomedicamento();
    $oDaoAdministracaoMedicamento->sd106_administracaomedicamento = $oAdministracaoMedicamento->getCodigo();
    $oDaoAdministracaoMedicamento->sd106_prontuario               = $this->iCodigo;
    $oDaoAdministracaoMedicamento->incluir(null);
    if ($oDaoAdministracaoMedicamento->erro_status == 0) {
      throw new BusinessException(MENSAGEM_PRONTUARIO."erro_ao_vincular_administracao");
    }
    $this->aAdministracoes[$oAdministracaoMedicamento->getCodigo()] = $oAdministracaoMedicamento;
  }

  /**
   * Remove a Administração do medicamento
   * @param AdministracaoMedicamento $oAdministracaoMedicamento
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function removerAdministracaoMedicamento(AdministracaoMedicamento $oAdministracaoMedicamento) {

    if (!db_utils::inTransaction()) {
      throw new DBException("sem transação com o banco de dados");
    }

    if (empty($oAdministracaoMedicamento)) {
      throw new ParameterException(_M(MENSAGEM_PRONTUARIO."administracao_nao_informada"));
    }

    if (isset($this->aAdministracoes[$oAdministracaoMedicamento->getCodigo()])) {
      unset($this->aAdministracoes[$oAdministracaoMedicamento->getCodigo()]);
    }
    $oDaoAdministracaoMedicamento = new cl_prontuarioadministracaomedicamento();
    $oDaoAdministracaoMedicamento->excluir(null, "sd106_administracaomedicamento = {$oAdministracaoMedicamento->getCodigo()}");
    if ($oDaoAdministracaoMedicamento->erro_status == 0) {
      throw new BusinessException(MENSAGEM_PRONTUARIO."erro_ao_remover_administracao_da_faa");
    }
    $oAdministracaoMedicamento->remover();
  }

  /**
   * Retorna o codigo da FAA
   * @return int|null
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a data de atendimento do paciente
   * @return DBDate|null
   */
  public function getDataAtendimento() {
    return $this->oDataAtendimento;
  }
}