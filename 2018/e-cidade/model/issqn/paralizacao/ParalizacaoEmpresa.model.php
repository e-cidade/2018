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
 * Classe para manipular as paralizações das Empresas
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package issqn
 * @subpackage paralizacao
 */
class ParalizacaoEmpresa { 

  /**
   * Arquivo json para mensagens
   */
  const MENSAGEM = 'tributario.issqn.ParalizacaoEmpresa.';

  /**
   * Codigo da paralização
   * @var integer
   */
  private $iCodigo;

  /**
   * Objeto do tipo Empresa
   * @var Empresa
   */ 
  private $oEmpresa;

  /**
   * Codigo do Motivo da paralização
   * @var integer
   */
  private $iMotivo;

  /**
   * Data do inicio da paralização
   * @var DBDAte
   */
  private $oDataInicio;

  /**
   * Data do fim da paralização
   * @var DBDate
   */
  private $oDataFim;

  /**
   * Observação sobre a paralização
   * @var string
   */
  private $sObservacao;

  /**
   * Contrutor da classe, se for informado como parametro o 
   * código da paralização, realiza o lazy load da classe.
   * 
   * @param integer $iCodigo código da paralização
   * @return boolean -true sucesso, -false $ioCodigo não informado
   */
  function __construct ( $iCodigo = null) {

    if ( !$iCodigo ) {
      return false;
    }

    /**
     * Define o codigo da paralização
     */
    $this->setCodigo($iCodigo);

    $oDaoIssBaseParalizacao = db_utils::getDao('issbaseparalisacao');
    $sSqlIssBaseParalizacao = $oDaoIssBaseParalizacao->sql_query_file($iCodigo);
    $rsIssBaseParalizacao   = db_query($sSqlIssBaseParalizacao);

    /**
     * Erro na consulta que realiza a busca da paralização
     */
    if ( !$rsIssBaseParalizacao ) {
      throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_buscar_paralizacao', (object) array('sErroBanco' => pg_last_error())));
    }

    /**
     * Nenhuma PAralização encontrada para o código informado
     */
    if ( pg_num_rows($rsIssBaseParalizacao) == 0 ) {
      throw new BusinessException(_M(ParalizacaoEmpresa::MENSAGEM . 'nenhuma_paralizacao', (object) array('iCodigo' => $iCodigo)));
    }

    $oDadosParalizacaoEmpresa = db_utils::fieldsMemory($rsIssBaseParalizacao, 0);

    $this->setCodigo($oDadosParalizacaoEmpresa->q140_sequencial);
    $this->setEmpresa(new Empresa($oDadosParalizacaoEmpresa->q140_issbase));
    $this->setMotivo($oDadosParalizacaoEmpresa->q140_issmotivoparalisacao);
    $this->setDataInicio(new DBDate($oDadosParalizacaoEmpresa->q140_datainicio));
    if ( $oDadosParalizacaoEmpresa->q140_datafim ) {
      $this->setDataFim(new DBDate($oDadosParalizacaoEmpresa->q140_datafim));
    }
    $this->setObservacao($oDadosParalizacaoEmpresa->q140_observacao);

    return true;
  }

  /**
   * Define o código da paralização, e valida se é integer
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {

    if ( !DBNumber::isInteger($iCodigo) ) {
      throw new ParameterException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_inteiro'));
    }

    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o código da paralização
   * @return integer $iCodigo
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o objeto de Empresa
   * @param Empresa $oEmpresa
   */
  public function setEmpresa( Empresa $oEmpresa) {
    $this->oEmpresa = $oEmpresa;
  }

  /**
   * Retorna o objeto de Empresa
   * @return Empresa $oEmpresa
   */
  public function getEmpresa() {
    return $this->oEmpresa;
  }

  /**
   * Define o motivo da paralização
   * @param integer $iMotivo
   */       
  public function setMotivo( $iMotivo ) {

    if ( !DBNumber::isInteger($iMotivo) ) {
      throw new ParameterException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_inteiro'));
    }

    $this->iMotivo = $iMotivo;
  }

  /**
   * Retorna o motivo da paralização
   * @return integer $iMotivo
   */
  public function getMotivo(){
    return $this->iMotivo;
  }

  /**
   * Define a data de inicio da paralização
   * @param DBDate $oDataInicio
   */
  public function setDataInicio(  DBDate $oDataInicio ) {
    $this->oDataInicio = $oDataInicio;
  }

  /**
   * Retorna a data de inicio da paralização
   * @return DBDate $oDataInicio
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * Define a data do fim da paralização
   * @param DBDate $oDataFim
   */
  public function setDataFim( DBDate $oDataFim = null) {
    $this->oDataFim = $oDataFim;
  }

  /**
   * Retorna a data de fim da PAralização
   * @return DBDate $oDataFim
   */
  public function getDataFim() {
    return $this->oDataFim;
  }

  /**
   * Define a observação da paralização
   * @param string $sObservacao
   */     
  public function setObservacao( $sObservacao ) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna a observação sobre a paralização da empresa
   * @return string $sObservacao
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  public function getDescricaoMotivo() {

    $oDaoIssMotivoParalizacao = db_utils::getDao('issmotivoparalisacao');
    $sSqlMotivoParalizacao    = $oDaoIssMotivoParalizacao->sql_query($this->iMotivo, 'q141_descricao');
    $rsMotivoParalizacao      = db_query($sSqlMotivoParalizacao);

    if ( !$rsMotivoParalizacao ) {

      $oErroBanco = (object) array('sErroBanco' => pg_last_error());
      throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_buscar_paralizacao', $oErroBanco));
    }

    if (pg_num_rows($rsMotivoParalizacao) == 0 ){

      throw new BusinessException(_M(ParalizacaoEmpresa::MENSAGEM . 'nenhum_motivo_encontrado'));
    }

    $oMotivoParalizacao = db_utils::fieldsMemory($rsMotivoParalizacao, 0);

    return $oMotivoParalizacao->q141_descricao;
  }

  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'nenhuma_transacao_banco'));
    }

    /**
     * Realiza validação dos campos que são obrigattórios
     */

    if ( empty($this->iMotivo) ) {
      throw new ParameterException(_M(ParalizacaoEmpresa::MENSAGEM . 'motivo_nao_informado'));
    }

    if ( empty($this->oDataInicio) ) {
      throw new ParameterException(_M(ParalizacaoEmpresa::MENSAGEM . 'datainicio_nao_informado')); 
    }

    if ( empty($this->sObservacao) ){
      throw new ParameterException(_M(ParalizacaoEmpresa::MENSAGEM . 'observacao_nao_informado'));
    }

    /**
     * Insere os dados informados na tabela issbaseparalisacao
     */
    $oDaoIssBaseParalizacao = db_utils::getDao('issbaseparalisacao');
    $oDaoIssBaseParalizacao->q140_issbase              = $this->getEmpresa()->getInscricao();
    $oDaoIssBaseParalizacao->q140_issmotivoparalisacao = $this->getMotivo();
    $oDaoIssBaseParalizacao->q140_datainicio           = $this->getDataInicio()->getDate();
    $oDaoIssBaseParalizacao->q140_datafim              = ($this->getDataFim()) ? $this->getDataFim()->getDate() : null;
    $oDaoIssBaseParalizacao->q140_observacao           = $this->getObservacao();
    $oDaoIssBaseParalizacao->q140_sequencial           = null;

    /**
     * Verifica se deve nser feita inclusão ou alteração da paralização
     */
    if( empty($this->iCodigo) ) {

      /**
       * Realiza a inclusão da paralização
       */
      $oDaoIssBaseParalizacao->incluir(null);

      /**
       * Tratamento do erro ao incluir paralização
       */
      if ( $oDaoIssBaseParalizacao->erro_status == "0" ) {

        $oErroBanco = (object) array('sErroBanco' => $oDaoIssBaseParalizacao->erro_banco);
        throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_incluir_paralizacao', $oErroBanco));
      }
    } else {

      /**
       * Realiza a alteração de uma paralização
       */
      $oDaoIssBaseParalizacao->q140_sequencial = $this->iCodigo;
      $oDaoIssBaseParalizacao->alterar($this->iCodigo);
 
      /**
       * Tratamento do erro ao incluir paralização
       */
      if ( $oDaoIssBaseParalizacao->erro_status == "0" ) {
 
        $oErroBanco = (object) array('sErroBanco' => $oDaoIssBaseParalizacao->erro_banco);
        throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_alterar_paralizacao', $oErroBanco));
      } 
    }

    /**
     * Define o codigo da paralizacao inserida.
     */
    $this->setCodigo($oDaoIssBaseParalizacao->q140_sequencial);

    /**
     * Realiza o lançamento de uma ocorrência para a empresa
     */
    
    $sDataInicio = db_formatar($this->oDataInicio->getDate(), 'd');
    $sDataFim    = ($this->oDataFim) ? db_formatar($this->oDataFim->getDate(), 'd') : '';

    $oDaoHistOcorrencia = db_utils::getDao('histocorrencia');
    $oDaoHistOcorrencia->ar23_sequencial   = null;
    $oDaoHistOcorrencia->ar23_id_usuario   = db_getsession('DB_id_usuario');
    $oDaoHistOcorrencia->ar23_instit       = db_getsession('DB_instit');
    $oDaoHistOcorrencia->ar23_modulo       = db_getsession('DB_modulo');
    $oDaoHistOcorrencia->ar23_id_itensmenu = db_getsession('DB_itemmenu_acessado');
    $oDaoHistOcorrencia->ar23_data         = date('Y-m-d');
    $oDaoHistOcorrencia->ar23_hora         = date('h:i');
    $oDaoHistOcorrencia->ar23_tipo         = 2; //-1 - Manual, 2 - Automatico
    $oDaoHistOcorrencia->ar23_descricao    = 'Cadastro de paralizacao de empresa';
    $oDaoHistOcorrencia->ar23_ocorrencia   = "A empresa com a inscricao municipal numero {$this->getEmpresa()->getInscricao()}, ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "foi paralisada com as seguintes informacoes: Data de Inicio da Paralizacao: ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "{$sDataInicio}, Data de Fim da Paralizacao: ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "{$sDataFim}, Observacao: {$this->sObservacao}, ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "Motivo: {$this->getDescricaoMotivo()} ";

    /**
     * Realiza a inclusão na tabela histocorrencia
     */
    $oDaoHistOcorrencia->incluir(null);


    /**
     * Tratamento de erro ao incluir ocorrencia
     */
    if ( $oDaoHistOcorrencia->erro_status = "0" ) {

      $oErroBanco = (object) array('sErroBanco' => $oDaoHistOcorrencia->erro_sql);
      throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_incluir_ocorrencia',$oErroBanco));
    }

    $oDaoHistOcorrenciaInscr = db_utils::getDao('histocorrenciainscr');
    $oDaoHistOcorrenciaInscr->ar26_sequencial     = null;
    $oDaoHistOcorrenciaInscr->ar26_inscr          = $this->getEmpresa()->getInscricao();
    $oDaoHistOcorrenciaInscr->ar26_histocorrencia = $oDaoHistOcorrencia->ar23_sequencial;

    /**
     * Realiza a inclusao na tabela histocorrenciainscr
     */
    $oDaoHistOcorrenciaInscr->incluir(null);

    /**
     * Tratamento de erro ao incluir ocorrencia
     */
    if ( $oDaoHistOcorrenciaInscr->erro_status = "0" ) {

      $oErroBanco = (object) array('sErroBanco' => $oDaoHistOcorrenciaInscr->erro_banco);
      throw new DBException(_M(ParalizacaoEmpresa::MENSAGEM . 'erro_incluir_ocorrencia',$oErroBanco));
    }
    
    return true;
  }
}