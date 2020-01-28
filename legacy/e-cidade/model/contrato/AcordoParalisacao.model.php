<?php
define("ACORDO_PARALISACAO_MENSAGEM", 'patrimonial.contratos.AcordoParalisacao.');
/**
 * Controle das paralisações de um acordo
 * Class AcordoParalisacao
 * @package Contrato
 */
class AcordoParalisacao {


  /**
   * Periodo adicionado como paralizado 
   * @var integer
   */
  const PERIODO_PARALIZADO = 1;
  
  /**
   * Periodo adicionado como complementar 
   * @var integer
   */
  const PERIODO_COMPLEMENTAR = 2;
  
  /**
   * Codigo da paralisação
   * @var integer
   */
  protected $iCodigo;

  /**
   * Data de inicio da paralização
   * @var DBDate
   */
  protected $oDataInicio;

  /**
   * Data de termino da paralização
   * @var DBDate
   */
  protected $oDataTermino;

  /**
   * Contrato da paralisação
   * @var Acordo
   */
  protected $oAcordo;

  /**
   * Movimentacoes do Acordo
   * @var AcordoMovimentacao[]
   */
  protected $aMovimentacoes;

  /**
   * Observacao da paralisacao
   * @var string
   */
  protected $sObservacao = '';
  
  /**
   * Lista de Periodos da paralizacao
   * @var array
   */
  protected $aPeriodos = array();
  
  
  protected $aPeriodosReativados = array();
  

  /**
   * Instancia uma nova Paralisação do Acordo
   *
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoAcordoParalisacao = new cl_acordoparalisacao();
      $sSqlDadosParalisacao  = $oDaoAcordoParalisacao->sql_query_file($iCodigo);
      $rsDadosParalisacao    = $oDaoAcordoParalisacao->sql_record($sSqlDadosParalisacao);
      if (!$rsDadosParalisacao || $oDaoAcordoParalisacao->numrows == 0) {
        throw new BusinessException(_M(ACORDO_PARALISACAO_MENSAGEM."paralisacao_nao_encontrada"));
      }

      $oDadosParalisacao = db_utils::fieldsMemory($rsDadosParalisacao, 0);
      $this->iCodigo     = $oDadosParalisacao->ac47_sequencial;
      $this->setAcordo(AcordoRepository::getByCodigo($oDadosParalisacao->ac47_acordo));
      $this->setDataInicio(new DBDate($oDadosParalisacao->ac47_datainicio));
      if (!empty($oDadosParalisacao->ac47_datafim)) {
        $this->setDataTermino(new DBDate($oDadosParalisacao->ac47_datafim));
      }
    }
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param \Acordo $oAcordo
   */
  public function setAcordo(Acordo $oAcordo) {
    $this->oAcordo = $oAcordo;
  }

  /**
   * @return \Acordo
   */
  public function getAcordo() {
    return $this->oAcordo;
  }

  /**
   * @param \DBDate $oDataInicio
   */
  public function setDataInicio($oDataInicio) {
    $this->oDataInicio = $oDataInicio;
  }

  /**
   * @return \DBDate
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * @param \DBDate|int $oDataTermino
   */
  public function setDataTermino(DBDate $oDataTermino) {
    $this->oDataTermino = $oDataTermino;
  }

  /**
   * @return DBDate
   */
  public function getDataTermino() {
    return $this->oDataTermino;
  }

  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }


  /**
   * Retorna todas as movimentacoes do Acordo
   * @return AcordoMovimentacao[]
   */
  public function getMovimentacoes() {

    if (count($this->aMovimentacoes) == 0 && !empty($this->iCodigo)) {

      $sCamposMovimentacao               = 'ac48_acordomovimentacao, ac10_acordomovimentacaotipo';
      $oDaoAcordoParalisacaoMovimentacao = new cl_acordoparalisacaoacordomovimentacao();
      $sSqlMovimentacao                  = $oDaoAcordoParalisacaoMovimentacao->sql_queryMovimentacao(null,
                                               $sCamposMovimentacao, "ac48_sequencial", "ac48_acordoparalisacao = {$this->iCodigo}");
      $rsAcordoParalisacaoMovimentacao   = $oDaoAcordoParalisacaoMovimentacao->sql_record( $sSqlMovimentacao );

      $aMovimentacoes = array();
      for ($iMovimentacao = 0; $iMovimentacao <  $oDaoAcordoParalisacaoMovimentacao->numrows; $iMovimentacao++) {

        $oDadosMovimentacao = db_utils::fieldsMemory($rsAcordoParalisacaoMovimentacao, $iMovimentacao);
        $aMovimentacoes[] = AcordoMovimentacaoFactory::getMovimentacaoPorTipo( $oDadosMovimentacao->ac48_acordomovimentacao,
                                                        $oDadosMovimentacao->ac10_acordomovimentacaotipo
                                                       );
      }

      $this->aMovimentacoes = $aMovimentacoes;
    }
    return $this->aMovimentacoes;
  }

  /**
   * @return AcordoMovimentacao|null
   */
  public function getUltimaMovimentacao() {

    if ( count($this->getMovimentacoes()) == 0 ) {
      return null;
    }
    return $this->aMovimentacoes[count($this->aMovimentacoes) - 1];
  }

  /**
   * @throws BusinessException
   * @throws DBException
   */
  public function salvar() {

    if ($this->getDataInicio() == '') {
      throw new BusinessException(_M(ACORDO_PARALISACAO_MENSAGEM."data_de_inicio_nao_informada"));
    }

    if ( $this->getAcordo() == '' ) {
      throw new BusinessException(_M(ACORDO_PARALISACAO_MENSAGEM."acordo_nao_informado"));
    }

    $oDaoAcordoParalisacao                  = new cl_acordoparalisacao();
    $oDaoAcordoParalisacao->ac47_datainicio = $this->getDataInicio()->getDate();
    $oDaoAcordoParalisacao->ac47_acordo     = $this->getAcordo()->getCodigoAcordo();
    if ($this->getDataTermino() != '') {
      $oDaoAcordoParalisacao->ac47_datafim = $this->getDataTermino()->getDate();
    }

    if (empty($this->iCodigo)) {

      $oDaoAcordoParalisacao->incluir(null);
      $this->iCodigo = $oDaoAcordoParalisacao->ac47_sequencial;

      $oMovimentacao = new AcordoMovimentacaoParalisacao();
      $this->vincularMovimentacao($oMovimentacao);

    } else {

      $oDaoAcordoParalisacao->ac47_sequencial = $this->getCodigo();
      $oDaoAcordoParalisacao->alterar($oDaoAcordoParalisacao->ac47_sequencial);
      $oUltimaMovimentacao = $this->getUltimaMovimentacao();
      if (!empty($oUltimaMovimentacao)) {

        $oUltimaMovimentacao->setObservacao($this->getObservacao());
        $oUltimaMovimentacao->save();
      }
    }

    if ( $oDaoAcordoParalisacao->erro_status == '0') {

      $oErro       = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacao->erro_msg;
      throw new DBException (_M(ACORDO_PARALISACAO_MENSAGEM . "erro_salvar" , $oErro));
    }
    
    $oDaoAcordoParalisacaoPeriodo = new cl_acordoparalisacaoperiodo();
    $oDaoAcordoParalisacaoPeriodo->excluir(null, "ac49_acordoparalisacao = {$this->getCodigo()}");
    if ($oDaoAcordoParalisacaoPeriodo->erro_status == 0) {
      
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacaoPeriodo->erro_msg;
      throw new DBException( _M( ACORDO_PARALISACAO_MENSAGEM . "erro_excluir_acordo_periodo", $oErro  ) );
    }
    
    foreach ($this->aPeriodos as $oPeriodo) {
      
      $oDaoAcordoParalisacaoPeriodo->ac49_acordoparalisacao    = $this->iCodigo; 
      $oDaoAcordoParalisacaoPeriodo->ac49_acordoposicaoperiodo = $oPeriodo->iCodigoPeriodo;          
      $oDaoAcordoParalisacaoPeriodo->ac49_tipoperiodo          = $oPeriodo->iTipo;
      $oDaoAcordoParalisacaoPeriodo->incluir( null );
      if ($oDaoAcordoParalisacaoPeriodo->erro_status == 0 ) {
        
        $oErro       = new stdClass();
        $oErro->erro = $oDaoAcordoParalisacaoPeriodo->erro_msg;
        throw new DBException( _M( ACORDO_PARALISACAO_MENSAGEM . "erro_incluir_vinculo_novo_periodo", $oErro ) ); 
      }
    }
  }

  /**
   * REaliza a vinculacao do movimento com a Paralisação
   * @param AcordoMovimentacao $oMovimentacao
   * @throws DBException
   */
  protected function vincularMovimentacao(AcordoMovimentacao $oMovimentacao) {

    $oMovimentacao->setObservacao($this->getObservacao());
    $oMovimentacao->setAcordo($this->getAcordo()->getCodigoAcordo());
    $oMovimentacao->save();

    $oDaoAcordoParalisacaoMovimento = new cl_acordoparalisacaoacordomovimentacao();
    $oDaoAcordoParalisacaoMovimento->ac48_acordomovimentacao = $oMovimentacao->getCodigo();
    $oDaoAcordoParalisacaoMovimento->ac48_acordoparalisacao  = $this->getCodigo();
    $oDaoAcordoParalisacaoMovimento->incluir(null);
    if ($oDaoAcordoParalisacaoMovimento->erro_status == 0) {

      $oErro       = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacaoMovimento->erro_msg;
      throw new DBException (_M(ACORDO_PARALISACAO_MENSAGEM . "erro_salvar" , $oErro));
    }

    $this->aMovimentacoes[] = $oMovimentacao;
  }

  /**
   *
   *
   * @throws DBException
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação ativa");
    }

    $oUltimaMovimentacao = $this->getUltimaMovimentacao();
     if (!empty($oUltimaMovimentacao) && $oUltimaMovimentacao->getTipo() == 17) {
       throw new DBException (_M(ACORDO_PARALISACAO_MENSAGEM . "paralisacao_ja_terminada"));
     }
    $oUltimaMovimentacao->setObservacao($this->getObservacao());
    $oUltimaMovimentacao->cancelar();
    $oDaoAcordoMovimentacoes = new cl_acordoparalisacaoacordomovimentacao();
    $oDaoAcordoMovimentacoes->excluir(null, "ac48_acordoparalisacao = {$this->getCodigo()}");
    if ($oDaoAcordoMovimentacoes->erro_status == 0) {

      $oErro       = new stdClass();
      $oErro->erro = $oDaoAcordoMovimentacoes->erro_msg;
      throw new DBException (_M(ACORDO_PARALISACAO_MENSAGEM . "erro_excluir_vinculo_mov_paralisacao" , $oErro));
    }

    $oDaoAcordoParalisacao = new cl_acordoparalisacao();
    $oDaoAcordoParalisacao->excluir($this->getCodigo());
    if ($oDaoAcordoParalisacao->erro_status == '0') {

      $oErro       = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacao->erro_msg;
      throw new DBException (_M(ACORDO_PARALISACAO_MENSAGEM . "erro_excluir_paralisacao" , $oErro));
    }

    $this->aMovimentacoes = array();

  }
  
  /**
   * Adiciona um periodo a paralizacao
   * @param integer $iCodigoPeriodo codigo do periodo
   * @param integer $iTipo tipo do periodo AcordoParalisacao::PERIODO_COMPLEMENTAR | AcordoParalisacao::PERIODO_PARALIZADO
   *  
   */
  public function adicionarPeriodos ($iCodigoPeriodo, $iTipo) {
    
    /*
     * @todo validar se o periodo ja está incluso
     */
    $oPeriodo                 = new stdClass();
    $oPeriodo->iCodigoPeriodo = $iCodigoPeriodo;
    $oPeriodo->iTipo          = $iTipo;
    $this->aPeriodos[]        = $oPeriodo;
    
  }
  
  public function getPeriodos() {
    
    if ( empty($this->aPeriodos)) {
      
      $oDaoReativados   = new cl_acordoparalisacaoperiodo();
      $sWhereParalisado = "ac49_acordoparalisacao = {$this->getCodigo()}"; 
      $sSqlReativados   = $oDaoReativados->sql_query_file(null, "*", null , $sWhereParalisado);
      $rsReativados     = $oDaoReativados->sql_record($sSqlReativados);
      if ($oDaoReativados->numrows > 0) {
        
        for($iReativado = 0; $iReativado < $oDaoReativados->numrows; $iReativado++){
          
          $oDados = db_utils::fieldsMemory($rsReativados, $iReativado);
          $this->adicionarPeriodos($oDados->ac49_acordoposicaoperiodo, $oDados->ac49_tipoperiodo);
        }
      }
    }
    return $this->aPeriodos;
  }
  
  
}