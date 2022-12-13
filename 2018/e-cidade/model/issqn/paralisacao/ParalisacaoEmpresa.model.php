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
 * @subpackage paralisacao
 */
class ParalisacaoEmpresa { 

  /**
   * Arquivo json para mensagens
   */
  const MENSAGEM = 'tributario.issqn.ParalisacaoEmpresa.';

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

    $oDaoIssBaseparalisacao = db_utils::getDao('issbaseparalisacao');
    $sSqlIssBaseparalisacao = $oDaoIssBaseparalisacao->sql_query_file($iCodigo);
    $rsIssBaseparalisacao   = db_query($sSqlIssBaseparalisacao);

    /**
     * Erro na consulta que realiza a busca da paralização
     */
    if ( !$rsIssBaseparalisacao ) {
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_buscar_paralisacao', (object) array('sErroBanco' => pg_last_error())));
    }

    /**
     * Nenhuma PAralização encontrada para o código informado
     */
    if ( pg_num_rows($rsIssBaseparalisacao) == 0 ) {
      throw new BusinessException(_M(ParalisacaoEmpresa::MENSAGEM . 'nenhuma_paralisacao', (object) array('iCodigo' => $iCodigo)));
    }

    $oDadosParalisacaoEmpresa = db_utils::fieldsMemory($rsIssBaseparalisacao, 0);

    $this->setCodigo($oDadosParalisacaoEmpresa->q140_sequencial);
    $this->setEmpresa(new Empresa($oDadosParalisacaoEmpresa->q140_issbase));
    $this->setMotivo($oDadosParalisacaoEmpresa->q140_issmotivoparalisacao);
    $this->setDataInicio(new DBDate($oDadosParalisacaoEmpresa->q140_datainicio));
    if ( $oDadosParalisacaoEmpresa->q140_datafim ) {
      $this->setDataFim(new DBDate($oDadosParalisacaoEmpresa->q140_datafim));
    }
    $this->setObservacao($oDadosParalisacaoEmpresa->q140_observacao);

    return true;
  }

  /**
   * Define o código da paralização, e valida se é integer
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {

    if ( !DBNumber::isInteger($iCodigo) ) {
      throw new ParameterException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_inteiro'));
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
      throw new ParameterException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_inteiro'));
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

    $oDaoIssMotivoparalisacao = db_utils::getDao('issmotivoparalisacao');
    $sSqlMotivoparalisacao    = $oDaoIssMotivoparalisacao->sql_query($this->iMotivo, 'q141_descricao');
    $rsMotivoparalisacao      = db_query($sSqlMotivoparalisacao);

    if ( !$rsMotivoparalisacao ) {

      $oErroBanco = (object) array('sErroBanco' => pg_last_error());
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_buscar_paralisacao', $oErroBanco));
    }

    if (pg_num_rows($rsMotivoparalisacao) == 0 ){

      throw new BusinessException(_M(ParalisacaoEmpresa::MENSAGEM . 'nenhum_motivo_encontrado'));
    }

    $oMotivoparalisacao = db_utils::fieldsMemory($rsMotivoparalisacao, 0);

    return $oMotivoparalisacao->q141_descricao;
  }

  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'nenhuma_transacao_banco'));
    }

    /**
     * Realiza validação dos campos que são obrigattórios
     */

    if ( empty($this->iMotivo) ) {
      throw new ParameterException(_M(ParalisacaoEmpresa::MENSAGEM . 'motivo_nao_informado'));
    }

    if ( empty($this->oDataInicio) ) {
      throw new ParameterException(_M(ParalisacaoEmpresa::MENSAGEM . 'datainicio_nao_informado')); 
    }

    if ( empty($this->sObservacao) ){
      throw new ParameterException(_M(ParalisacaoEmpresa::MENSAGEM . 'observacao_nao_informado'));
    }

    /**
     * Valida se a data inicial é maior que a data final quando é informado a data final.
     */
    if( $this->oDataFim && (strtotime($this->oDataInicio->getDate()) > strtotime($this->oDataFim->getDate()))) {
      throw new BusinessException(_M(ParalisacaoEmpresa::MENSAGEM . 'data_inicial_maior_data_final'));
    }

    /**
     * Verifica se já existe um periodo cadastrado para a empresa concorrente com a data inicial e final informadas
     */
    if( !ParalisacaoEmpresa::validarIntervalo($this) ) {
      throw new BusinessException(_M(ParalisacaoEmpresa::MENSAGEM . 'periodo_em_conflito'));
    }

    /**
     * Insere os dados informados na tabela issbaseparalisacao
     */
    $oDaoIssBaseparalisacao = db_utils::getDao('issbaseparalisacao');
    $oDaoIssBaseparalisacao->q140_issbase              = $this->getEmpresa()->getInscricao();
    $oDaoIssBaseparalisacao->q140_issmotivoparalisacao = $this->getMotivo();
    $oDaoIssBaseparalisacao->q140_datainicio           = $this->getDataInicio()->getDate();
    $oDaoIssBaseparalisacao->q140_datafim              = ($this->getDataFim()) ? $this->getDataFim()->getDate() : null;
    $oDaoIssBaseparalisacao->q140_observacao           = $this->getObservacao();
    $oDaoIssBaseparalisacao->q140_sequencial           = null;

    /**
     * Verifica se deve nser feita inclusão ou alteração da paralização
     */
    if( empty($this->iCodigo) ) {

      /**
       * Realiza a inclusão da paralização
       */
      $oDaoIssBaseparalisacao->incluir(null);

      /**
       * Tratamento do erro ao incluir paralização
       */
      if ( $oDaoIssBaseparalisacao->erro_status == "0" ) {

        $oErroBanco = (object) array('sErroBanco' => $oDaoIssBaseparalisacao->erro_banco);
        throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_incluir_paralisacao', $oErroBanco));
      }
    } else {

      /**
       * Realiza a alteração de uma paralização
       */
      $oDaoIssBaseparalisacao->q140_sequencial = $this->iCodigo;
      $oDaoIssBaseparalisacao->alterar($this->iCodigo);
 
      /**
       * Tratamento do erro ao incluir paralização
       */
      if ( $oDaoIssBaseparalisacao->erro_status == "0" ) {
 
        $oErroBanco = (object) array('sErroBanco' => $oDaoIssBaseparalisacao->erro_banco);
        throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_alterar_paralisacao', $oErroBanco));
      } 
    }

    /**
     * Define o codigo da paralisacao inserida.
     */
    $this->setCodigo($oDaoIssBaseparalisacao->q140_sequencial);

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
    $oDaoHistOcorrencia->ar23_descricao    = 'Cadastro de paralisacao de empresa';
    $oDaoHistOcorrencia->ar23_ocorrencia   = "A empresa com a inscricao municipal numero {$this->getEmpresa()->getInscricao()}, ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "foi paralisada com as seguintes informacoes: Data de Inicio da paralisacao: ";
    $oDaoHistOcorrencia->ar23_ocorrencia  .= "{$sDataInicio}, Data de Fim da paralisacao: ";
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
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_incluir_ocorrencia',$oErroBanco));
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
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_incluir_ocorrencia',$oErroBanco));
    }
    
    return true;
  }

  /**
   * Verifica se a empresa ja possui um periodo de paralisação cadastrado, que é concorrente ao periodo que será inserido
   * 
   * @param  DBDate $oDataInicial Data Inicial que se deseja cadastrar
   * @param  DBDate $oDataFinal   Data Fim que se deseja cadastrar
   * @return bollean -true: empresa não possui paralisação cadastrada para o intervalo de data informado, 
   *                 -false: a empresa já possuí um período cadastrado para o intervalo informado
   */
  static function validarIntervalo(ParalisacaoEmpresa $oParalisacaoEmpresa) {

    $sDataInicial = $oParalisacaoEmpresa->getDataInicio()->getDate();
    $sDataFinal   = '2999-12-31';

    if ( $oParalisacaoEmpresa->getDataFim() ){
      $sDataFinal = $oParalisacaoEmpresa->getDataFim()->getDate();
    } 

    $oDaoIssBaseparalisacao = db_utils::getDao('issbaseparalisacao');
    $sWhere  =  "q140_issbase = {$oParalisacaoEmpresa->getEmpresa()->getInscricao()} and ";

    /**
     * Para quando for alteração, verifica se o sequancial é diferente da data que esta sendo alterada
     */
    if ( $oParalisacaoEmpresa->getCodigo() ) {
      $sWhere .=  "q140_sequencial <> {$oParalisacaoEmpresa->getCodigo()} and ";
    }

    $sWhere .=  "(q140_datainicio, q140_datafim) overlaps ('{$sDataInicial}', '{$sDataFinal}')";

    $sSqlIssbaseparalisacao = $oDaoIssBaseparalisacao->sql_query_file(null, 'q140_sequencial', null, $sWhere);
    $rsIssBaseparalisacao   = pg_query($sSqlIssbaseparalisacao);

    /**
     * Tratamento de erro ao verificar paralisações
     */
    if ( !$rsIssBaseparalisacao ) {

      $sErroBanco = (object) array("sErroBanco" => pg_last_error());
      throw new DBException(_M(ParalisacaoEmpresa::MENSAGEM . 'erro_verificar_paralisacao', $sErroBanco));
    }

    /**
     * Se for encontrado pelo menos 1 paralisação com um periodo ja cadastrado 
     * concorrente com o informado, retorna false
     */
    if ( pg_num_rows($rsIssBaseparalisacao) > 0 ){
      return false;
    }

    return true;
  }
}